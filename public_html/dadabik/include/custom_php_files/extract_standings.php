<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Extract Standings -- first of a planned SERIES of staged, manually
// -triggered extraction pages (not a Dadabik hook -- see conversation:
// deliberately chosen over hooks for visibility/control/timeout-safety
// given how much parsing a single turn file needs). Originally named
// "Extract League Report" -- renamed once it became clear that's a
// misleading description: this page only ever targeted the 'Standings'
// sub-block specifically, not the broader 'League Report' block, which
// ALSO contains per-game results and full team stat lines for every
// game played that week (out of scope here -- that's "Extract Games",
// a separate page, since it's a substantially different extraction
// targeting a different part of the same block, feeding `games` and
// `team_game_stats` rather than `standings_weekly`), plus the week's
// schedule, free agent list, and transaction notices (out of scope
// for either page).
//
// Confirmed by reading real turn files, not assumed:
//   - Pro (NFLAR) standings: divisions paired two-per-line with <U>...<UC>
//     headers, teams paired two-per-line, 7 stat columns (W L T FOR AGN
//     Div SK).
//   - College (NCAA5) standings: NO division headers at all, one flat
//     list, one team per line, only 6 stat columns (W L T FOR AGN SK --
//     no Div record) -- matches the established fact that NCAA5 doesn't
//     use conference/division grouping in standings. Building one parser
//     against only the pro sample would have silently failed (or thrown
//     confusing errors) on every college upload.
//
// Idempotent by design: standings_weekly has UNIQUE KEY (week_id,
// franchise_id), and every coach's turn repeats the SAME league-wide
// standings -- so this page uses INSERT ... ON DUPLICATE KEY UPDATE and
// is safe to re-run on the same upload, or on a DIFFERENT franchise's
// upload for the same week, without creating duplicates or needing any
// "already extracted" tracking.
//
// League/season/week identification (and block-splitting itself) now
// happens once, upstream, in the after-insert hook on raw_uploads
// (dadabik_process_raw_upload, in operational_hooks.php) -- this page
// used to have its own fallback for that, removed once the hook existed
// to do it reliably at upload time instead.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>Extract Standings</h1>";

// -------------------- Upload selector --------------------
// Excludes uploads for two distinct, confirmed reasons -- both cases where standings_weekly
// can never get rows for this week, not just "not yet processed":
//   1. No 'Standings' block at all -- playoff/bowl/bye weeks (confirmed directly: no running
//      win-loss table once a season moves into a single-elimination bracket, or once there's
//      no game played at all for a bye).
//   2. A 'Standings' block that DOES exist, but contains next week's schedule instead of a
//      table -- pre-season weeks specifically (confirmed directly: nothing meaningful to
//      show before any games have been played, so the engine substitutes the schedule under
//      the same block marker).
// Checked directly against block_text ('%Week%Schedule%') rather than via a dedicated
// tracking column -- reasonable for two confirmed cases; if a third distinct "nothing to
// extract" pattern turns up later, a more general flag/column approach would be worth
// reconsidering rather than continuing to bolt on more LIKE conditions here.
// Also excludes uploads whose week already has standings_weekly rows -- "already processed".
// Checked via "does standings_weekly have any row for this week_id" rather than a dedicated
// tracking column, since standings are league-wide per week (every franchise's row gets
// written together by the same upsert), not per-upload -- if any row exists for a week,
// that whole week's standings are already in, regardless of which specific upload did it.
// Not-yet-identified/not-yet-split uploads still show, deliberately -- there's no confirmed
// answer yet for those, so excluding them would be a guess, not a fact.
$_cp_sql = "SELECT ru.upload_id, ru.original_filename, ru.turn_number,
                   l.code AS league_code, s.year AS season_year, w.week_number
            FROM raw_uploads ru
            LEFT JOIN leagues l ON l.league_id = ru.league_id
            LEFT JOIN seasons s ON s.season_id = ru.season_id
            LEFT JOIN weeks w ON w.week_id = ru.week_id
            WHERE NOT EXISTS (
                SELECT 1 FROM standings_weekly sw WHERE sw.week_id = ru.week_id
            )
            AND (
                ru.parse_status != 'partial'
                OR EXISTS (
                    SELECT 1 FROM raw_upload_blocks rub
                    WHERE rub.upload_id = ru.upload_id AND rub.block_type = 'Standings'
                      AND rub.block_text NOT LIKE '%Week%Schedule%'
                )
            )
            ORDER BY ru.upload_id ASC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->execute();
$_cp_uploads = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<form method='get'>";
// Hidden inputs preserving Dadabik's own routing params (function=show_static_page&
// id_static_page=N) -- a GET form with no explicit action submits to the current path but
// replaces the ENTIRE query string with only its own fields, dropping these -- Dadabik then
// doesn't recognize the request as "show this static page" and falls through to its default
// (home) page instead. Same fix already applied to current_standings.php's league toggle and
// team.php's franchise selector -- missed here when this page was first built, even though I
// already knew the pattern by then. Read from the current request rather than hardcoded, so
// it's self-correcting across any future reinstall.
echo "<input type='hidden' name='function' value='" . htmlspecialchars($_GET['function'] ?? 'show_static_page') . "'>";
echo "<input type='hidden' name='id_static_page' value='" . htmlspecialchars($_GET['id_static_page'] ?? '') . "'>";
if (empty($_cp_uploads)) {
    echo "<p><em>Nothing left to process -- every uploaded turn either already has its standings extracted, or genuinely has no Standings block to extract (playoff/bowl weeks).</em></p>";
}
echo "<select name='upload_id' onchange='this.form.submit()' style='width:480px'>";
echo "<option value=''>-- select a turn --</option>";
foreach ($_cp_uploads as $u) {
    $identified = $u['league_code']
        ? "{$u['league_code']} {$u['season_year']} Wk {$u['week_number']}"
        : 'not yet identified';
    $sel = (isset($_GET['upload_id']) && $_GET['upload_id'] == $u['upload_id']) ? 'selected' : '';
    echo "<option value='{$u['upload_id']}' $sel>"
       . htmlspecialchars("{$u['original_filename']} ($identified)") . "</option>";
}
echo "</select>";
echo "</form><br>";

$_cp_upload_id = isset($_GET['upload_id']) && $_GET['upload_id'] !== '' ? (int)$_GET['upload_id'] : null;

if (!$_cp_upload_id) {
    echo "</div>";
    return;
}

// -------------------- Fetch the Standings block --------------------
$_cp_sql = "SELECT block_text FROM raw_upload_blocks WHERE upload_id = :uid AND block_type = 'Standings'";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':uid', $_cp_upload_id);
$_cp_stmt->execute();
$_cp_block_text = $_cp_stmt->fetchColumn();

if (!$_cp_block_text) {
    echo "<p><em>No 'Standings' block found for this upload. Has it been split into blocks yet?</em></p>";
    echo "</div>";
    return;
}

// -------------------- Confirm identification (done by the after-insert hook, not here) --------------------
// League/season/week identification now happens once, in the after-insert hook on raw_uploads
// (dadabik_process_raw_upload), at the moment a file is uploaded -- not in this page anymore.
// Every upload reaching this dropdown should already have it set; if not, something upstream
// (the hook itself, or block-splitting) didn't complete, and that's what needs investigating,
// not a fallback re-derivation here.
$_cp_upload = ddb_api::get_record_details('raw_uploads', 'upload_id', $_cp_upload_id);

if (!$_cp_upload['league_id'] || !$_cp_upload['season_id'] || !$_cp_upload['week_id']) {
    echo "<p><em>This upload has no league/season/week identified yet. Check its <code>parse_status</code>/<code>parse_notes</code> -- "
       . "the after-insert hook should have identified it automatically when it was uploaded.</em></p>";
    echo "</div>";
    return;
}

$_cp_league_id = $_cp_upload['league_id'];
$_cp_week_id = $_cp_upload['week_id'];

$_cp_stmt = $conn->prepare("SELECT code FROM leagues WHERE league_id = :id");
$_cp_stmt->bindParam(':id', $_cp_league_id);
$_cp_stmt->execute();
$_cp_league_code = $_cp_stmt->fetchColumn();

echo "<p>Upload identified as <strong>" . htmlspecialchars($_cp_league_code) . "</strong>, week_id $_cp_week_id.</p>";

// -------------------- Parse --------------------
$_cp_is_pro = ($_cp_league_code !== 'NCAA5');
$_cp_rows = $_cp_is_pro
    ? parse_standings_pro($_cp_block_text)
    : parse_standings_college($_cp_block_text);

if (empty($_cp_rows) && preg_match('/Week\s+\d+\s+Schedule/', $_cp_block_text)) {
    // Pre-season uploads: the Standings block contains next week's schedule instead of a
    // win-loss table -- there's nothing meaningful to show yet since no games have been
    // played. Confirmed directly against a real pre-season upload's actual block content,
    // not assumed. Zero rows here is the CORRECT outcome, not a failure -- this message
    // exists so that's clear rather than looking like a silent, unexplained zero.
    echo "<p><em>This upload's Standings block contains next week's schedule instead of a "
       . "standings table -- expected for a pre-season week, since no games have been played "
       . "yet. 0 rows is the correct outcome here, not a failure.</em></p>";
    echo "</div>";
    return;
}

echo "<p>Found " . count($_cp_rows) . " team rows in the Standings block.</p>";

// -------------------- Resolve franchises + upsert --------------------
$_cp_resolved = 0;
$_cp_unresolved = [];

$_cp_upsert_sql = "INSERT INTO standings_weekly
        (week_id, franchise_id, wins, losses, ties, points_for, points_against,
         division_record, streak, conference, division, source, source_upload_id)
    VALUES (:week_id, :franchise_id, :wins, :losses, :ties, :points_for, :points_against,
         :division_record, :streak, :conference, :division, 'parsed', :upload_id)
    ON DUPLICATE KEY UPDATE
        wins = VALUES(wins), losses = VALUES(losses), ties = VALUES(ties),
        points_for = VALUES(points_for), points_against = VALUES(points_against),
        division_record = VALUES(division_record), streak = VALUES(streak),
        conference = VALUES(conference), division = VALUES(division),
        source = 'parsed', source_upload_id = VALUES(source_upload_id)";
$_cp_upsert_stmt = $conn->prepare($_cp_upsert_sql);

$_cp_franchise_stmt = $conn->prepare(
    "SELECT franchise_id FROM franchises WHERE league_id = :league_id AND label = :label"
);

foreach ($_cp_rows as $row) {
    $_cp_franchise_stmt->bindParam(':league_id', $_cp_league_id);
    $_cp_franchise_stmt->bindParam(':label', $row['team_name']);
    $_cp_franchise_stmt->execute();
    $franchise_id = $_cp_franchise_stmt->fetchColumn();

    if (!$franchise_id) {
        $_cp_unresolved[] = $row['team_name'];
        continue;
    }

    $_cp_upsert_stmt->bindValue(':week_id', $_cp_week_id);
    $_cp_upsert_stmt->bindValue(':franchise_id', $franchise_id);
    $_cp_upsert_stmt->bindValue(':wins', $row['wins']);
    $_cp_upsert_stmt->bindValue(':losses', $row['losses']);
    $_cp_upsert_stmt->bindValue(':ties', $row['ties']);
    $_cp_upsert_stmt->bindValue(':points_for', $row['points_for']);
    $_cp_upsert_stmt->bindValue(':points_against', $row['points_against']);
    $_cp_upsert_stmt->bindValue(':division_record', $row['division_record']);
    $_cp_upsert_stmt->bindValue(':streak', $row['streak']);
    $_cp_upsert_stmt->bindValue(':conference', $row['conference']);
    $_cp_upsert_stmt->bindValue(':division', $row['division']);
    $_cp_upsert_stmt->bindValue(':upload_id', $_cp_upload_id);
    $_cp_upsert_stmt->execute();
    $_cp_resolved++;
}

echo "<div class='w3-panel w3-pale-green w3-text-black w3-round-large'>";
echo "<p><strong>$_cp_resolved</strong> rows written to standings_weekly.</p>";
echo "</div>";

if ($_cp_unresolved) {
    echo "<div class='w3-panel w3-pale-red w3-text-black w3-round-large'>";
    echo "<p><strong>" . count($_cp_unresolved) . "</strong> team name(s) could not be matched to a franchise "
       . "(no row in <code>franchises</code> with that exact label for this league):</p>";
    echo "<ul>";
    foreach ($_cp_unresolved as $name) {
        echo "<li>" . htmlspecialchars($name) . "</li>";
    }
    echo "</ul>";
    echo "</div>";
}

echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// Pro standings: divisions paired two-per-line via <U>Name<UC>, teams paired two-per-line,
// 7 stat columns including a division record. Walks the block line by line, tracking which
// pair of divisions is currently "active" until the next header line replaces it.
function parse_standings_pro($text) {
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $rows = [];
    $current_divisions = [null, null];

    // Excludes BOTH '(' and '>' from the name capture -- excluding only '(' was tried first
    // and tested against real data, which caught a real bug: two teams share a line separated
    // by "<T>", and without excluding '>' too, the non-greedy name match can start matching
    // from partway through that preceding tag (e.g. capturing "T>Philadelphia Eagles" instead
    // of "Philadelphia Eagles"), since '>' alone doesn't stop it.
    // Streak group is [WLT], not [WL] -- confirmed a real upload had two teams sitting on a
    // tie streak ("T1"), which the original win/loss-only pattern silently failed to match at
    // all, dropping those rows from the results entirely with no error or warning (they never
    // reached the franchise-resolution step, so didn't even show up as "unresolved").
    $team_pattern = '/([^(<>]+?)\s*\(([^)]+)\)<T>\s*(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+-\d+)\s+([WLT]\d+)/';

    foreach ($lines as $line) {
        if (preg_match_all('/<U>([^<]+)<UC>/', $line, $div_matches)) {
            $current_divisions = [$div_matches[1][0] ?? null, $div_matches[1][1] ?? null];
            continue;
        }
        if (preg_match_all($team_pattern, $line, $m, PREG_SET_ORDER)) {
            foreach ($m as $i => $match) {
                $division = $current_divisions[$i] ?? null;
                $conference = $division ? trim(explode(' ', $division)[0]) : null;
                $rows[] = [
                    'team_name' => trim($match[1]),
                    'wins' => (int)$match[3], 'losses' => (int)$match[4], 'ties' => (int)$match[5],
                    'points_for' => (int)$match[6], 'points_against' => (int)$match[7],
                    'division_record' => $match[8], 'streak' => $match[9],
                    'conference' => $conference, 'division' => $division,
                ];
            }
        }
    }
    return $rows;
}

// College standings: flat list, one team per line, no division headers, 6 stat columns
// (no division record). conference/division are always NULL here -- matches the
// established fact that NCAA5 doesn't use conference/division grouping in standings.
function parse_standings_college($text) {
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $rows = [];

    $team_pattern = '/([^(<>]+?)\s*\(([^)]+)\)<T>\s*(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+([WLT]\d+)/';

    foreach ($lines as $line) {
        if (preg_match($team_pattern, $line, $match)) {
            $rows[] = [
                'team_name' => trim($match[1]),
                'wins' => (int)$match[3], 'losses' => (int)$match[4], 'ties' => (int)$match[5],
                'points_for' => (int)$match[6], 'points_against' => (int)$match[7],
                'division_record' => null, 'streak' => $match[8],
                'conference' => null, 'division' => null,
            ];
        }
    }
    return $rows;
}
?>
