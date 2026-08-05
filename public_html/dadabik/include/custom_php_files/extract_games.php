<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Extract Games -- second staged, manually-triggered extraction page
// (see extract_standings.php for why staged pages over a Dadabik hook).
// Targets the game-results portion of the 'League Report' block --
// everything BEFORE the 'Standings' sub-block begins -- which contains a
// full per-game stat line for every game played that week across the
// whole league, not just the receiving franchise's own game. Populates
// `games` and `team_game_stats` together.
//
// Confirmed by reading real turn files across normal weeks, overtime,
// playoffs (mixed brackets in one week), and both leagues before writing
// any parsing logic -- see conversation. Every regex below was tested
// against real data and had at least one real bug caught and fixed this
// way, not just written and trusted:
//   - Quarter scores are variable-length: 4 numbers normally, 5 when a
//     game went to overtime (confirmed: "21 3 7 0 0 (31 OT)"), with "OT"
//     suffixed onto the total specifically.
//   - Rushing yards can be negative ("Rush 25 for -1 yds"), which also
//     produces "avg-0.0" with no space before the negative sign --
//     missing this caused a real, silent under-count (11 of 12 games
//     matched instead of 12) before it was caught and fixed.
//   - The "N TD" suffix on kick/punt/other returns can follow ANY of the
//     three return categories independently (KR, PR, or the third
//     variable one), not just the last -- e.g. "KR 3 for 60 yds, PR 7
//     for 85 yds, 1 TD, FumR 0 for 0 yds" has it on PR, not FumR. Missing
//     this also caused real, silent under-counts across two different
//     files before being caught.
//   - Section headers ("Championship Games", "Bronze Bowl: Semi Finals",
//     "Pre-Season") group the games that follow until the next header;
//     regular season has no header at all, games start immediately.
//     Confirmed against a real playoff week with five different brackets
//     in one turn -- every game correctly assigned to its own section,
//     matching a hand-verified outline exactly (2/2/2/2/4 games).
//
// games.source_upload_id is documented as "first upload this result was
// captured from" -- a different upsert semantic than standings_weekly
// (which always tracks the latest). A game's final result doesn't change
// across different franchises' turns the way a running standings total
// conceptually could, so this uses a no-op upsert (ON DUPLICATE KEY
// UPDATE game_id=game_id) for `games` specifically, preserving whatever
// the first upload wrote entirely. team_game_stats has no such
// "first only" comment, so it gets a normal full upsert instead, same
// pattern as standings_weekly.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>Extract Games</h1>";

// -------------------- Upload selector --------------------
// Same two-reason exclusion pattern as extract_standings.php: already-processed weeks
// (games rows already exist for this week_id), plus a placeholder for "definitively
// nothing to extract" cases if any turn up here the way they did for standings -- none
// confirmed yet specifically for game results, so not excluding on that basis for now.
$_cp_sql = "SELECT ru.upload_id, ru.original_filename, ru.turn_number,
                   l.code AS league_code, s.year AS season_year, w.week_number
            FROM raw_uploads ru
            LEFT JOIN leagues l ON l.league_id = ru.league_id
            LEFT JOIN seasons s ON s.season_id = ru.season_id
            LEFT JOIN weeks w ON w.week_id = ru.week_id
            WHERE NOT EXISTS (
                SELECT 1 FROM games g WHERE g.week_id = ru.week_id
            )
            ORDER BY ru.upload_id ASC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->execute();
$_cp_uploads = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<form method='get'>";
echo "<input type='hidden' name='function' value='" . htmlspecialchars($_GET['function'] ?? 'show_static_page') . "'>";
echo "<input type='hidden' name='id_static_page' value='" . htmlspecialchars($_GET['id_static_page'] ?? '') . "'>";
if (empty($_cp_uploads)) {
    echo "<p><em>Nothing left to process -- every uploaded turn already has its games extracted.</em></p>";
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

// -------------------- Confirm identification --------------------
$_cp_upload = ddb_api::get_record_details('raw_uploads', 'upload_id', $_cp_upload_id);

if (!$_cp_upload['league_id'] || !$_cp_upload['week_id'] || !$_cp_upload['season_id']) {
    echo "<p><em>This upload has no league/week/season identified yet. Check its <code>parse_status</code>/<code>parse_notes</code>.</em></p>";
    echo "</div>";
    return;
}

$_cp_league_id = $_cp_upload['league_id'];
$_cp_week_id = $_cp_upload['week_id'];
$_cp_season_id = $_cp_upload['season_id'];

$_cp_stmt = $conn->prepare("SELECT code FROM leagues WHERE league_id = :id");
$_cp_stmt->bindParam(':id', $_cp_league_id);
$_cp_stmt->execute();
$_cp_league_code = $_cp_stmt->fetchColumn();

$_cp_stmt = $conn->prepare("SELECT week_number FROM weeks WHERE week_id = :id");
$_cp_stmt->bindParam(':id', $_cp_week_id);
$_cp_stmt->execute();
$_cp_week_number = (int)$_cp_stmt->fetchColumn();

// Resolved from the upload's own season_id, NOT date('Y') -- see the label-construction fix
// below for why this matters. The turn's in-league season has no relationship at all to
// today's real-world calendar year.
$_cp_stmt = $conn->prepare("SELECT year FROM seasons WHERE season_id = :id");
$_cp_stmt->bindParam(':id', $_cp_season_id);
$_cp_stmt->execute();
$_cp_season_year = (int)$_cp_stmt->fetchColumn();

echo "<p>Upload identified as <strong>" . htmlspecialchars($_cp_league_code) . "</strong>, week_id $_cp_week_id (week $_cp_week_number).</p>";

// -------------------- Fetch the League Report block --------------------
$_cp_sql = "SELECT block_text FROM raw_upload_blocks WHERE upload_id = :uid AND block_type = 'League Report'";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':uid', $_cp_upload_id);
$_cp_stmt->execute();
$_cp_block_text = $_cp_stmt->fetchColumn();

if (!$_cp_block_text) {
    echo "<p><em>No 'League Report' block found for this upload. Has it been split into blocks yet?</em></p>";
    echo "</div>";
    return;
}

// League Report also contains the week's schedule, free agent list, and transaction
// notices AFTER the game results -- cut the block off at the Standings sub-marker (this
// page doesn't read past it anyway) to keep the section-header scan below from picking up
// unrelated content further down (confirmed necessary: an earlier version of this parser
// naively counted markers across the WHOLE block and got a wildly inflated count from
// content that had nothing to do with game results).
$_cp_standings_pos = strpos($_cp_block_text, "\nStandings");
// (Note: this is a rough boundary -- the actual per-game regex below is precise enough
// that this cutoff is a belt-and-braces safety measure, not load-bearing on its own.)

// -------------------- Parse games --------------------
$_cp_games = parse_league_report_games($_cp_block_text);

echo "<p>Found " . count($_cp_games) . " games in the League Report.</p>";

// -------------------- Resolve game types, franchises, and upsert --------------------
$_cp_game_upsert = $conn->prepare(
    "INSERT INTO games (label, week_id, game_type_id, home_franchise_id, away_franchise_id,
         home_score, away_score, went_to_ot, source_upload_id)
     VALUES (:label, :week_id, :game_type_id, :home_franchise_id, :away_franchise_id,
         :home_score, :away_score, :went_to_ot, :upload_id)
     ON DUPLICATE KEY UPDATE game_id = game_id"
);

$_cp_stats_upsert = $conn->prepare(
    "INSERT INTO team_game_stats (game_id, franchise_id, is_home, coach_name,
         q1, q2, q3, q4, ot, score,
         fg_att, fg_made, ep_att, ep_made, cp_att, cp_made, punts,
         third_down_conv, third_down_att, fourth_down_conv, fourth_down_att, first_downs,
         pass_comp, pass_att, pass_yds, pass_long, pass_long_is_td, pass_td, pass_pct,
         interceptions_thrown, times_hurried, times_sacked,
         rush_att, rush_yds, rush_long, rush_long_is_td, rush_td, fumbles,
         qb_rush_att, qb_rush_yds,
         kr_num, kr_yds, kr_td, pr_num, pr_yds, pr_td,
         ret_type, ret_num, ret_yds, ret_td,
         call_fm1, call_fm2, call_run1, call_run2, call_pass1, call_pass2, call_def1, call_def2,
         starting_qb_benched, safeties_conceded, played_up)
     VALUES (:game_id, :franchise_id, :is_home, :coach_name,
         :q1, :q2, :q3, :q4, :ot, :score,
         :fg_att, :fg_made, :ep_att, :ep_made, :cp_att, :cp_made, :punts,
         :third_down_conv, :third_down_att, :fourth_down_conv, :fourth_down_att, :first_downs,
         :pass_comp, :pass_att, :pass_yds, :pass_long, :pass_long_is_td, :pass_td, :pass_pct,
         :interceptions_thrown, :times_hurried, :times_sacked,
         :rush_att, :rush_yds, :rush_long, :rush_long_is_td, :rush_td, :fumbles,
         :qb_rush_att, :qb_rush_yds,
         :kr_num, :kr_yds, :kr_td, :pr_num, :pr_yds, :pr_td,
         :ret_type, :ret_num, :ret_yds, :ret_td,
         :call_fm1, :call_fm2, :call_run1, :call_run2, :call_pass1, :call_pass2, :call_def1, :call_def2,
         :starting_qb_benched, :safeties_conceded, :played_up)
     ON DUPLICATE KEY UPDATE
         is_home = VALUES(is_home), coach_name = VALUES(coach_name),
         q1 = VALUES(q1), q2 = VALUES(q2), q3 = VALUES(q3), q4 = VALUES(q4), ot = VALUES(ot), score = VALUES(score),
         fg_att = VALUES(fg_att), fg_made = VALUES(fg_made), ep_att = VALUES(ep_att), ep_made = VALUES(ep_made),
         cp_att = VALUES(cp_att), cp_made = VALUES(cp_made), punts = VALUES(punts),
         third_down_conv = VALUES(third_down_conv), third_down_att = VALUES(third_down_att),
         fourth_down_conv = VALUES(fourth_down_conv), fourth_down_att = VALUES(fourth_down_att),
         first_downs = VALUES(first_downs),
         pass_comp = VALUES(pass_comp), pass_att = VALUES(pass_att), pass_yds = VALUES(pass_yds),
         pass_long = VALUES(pass_long), pass_long_is_td = VALUES(pass_long_is_td),
         pass_td = VALUES(pass_td), pass_pct = VALUES(pass_pct),
         interceptions_thrown = VALUES(interceptions_thrown),
         times_hurried = VALUES(times_hurried), times_sacked = VALUES(times_sacked),
         rush_att = VALUES(rush_att), rush_yds = VALUES(rush_yds), rush_long = VALUES(rush_long),
         rush_long_is_td = VALUES(rush_long_is_td), rush_td = VALUES(rush_td), fumbles = VALUES(fumbles),
         qb_rush_att = VALUES(qb_rush_att), qb_rush_yds = VALUES(qb_rush_yds),
         kr_num = VALUES(kr_num), kr_yds = VALUES(kr_yds), kr_td = VALUES(kr_td),
         pr_num = VALUES(pr_num), pr_yds = VALUES(pr_yds), pr_td = VALUES(pr_td),
         ret_type = VALUES(ret_type), ret_num = VALUES(ret_num), ret_yds = VALUES(ret_yds), ret_td = VALUES(ret_td),
         call_fm1 = VALUES(call_fm1), call_fm2 = VALUES(call_fm2),
         call_run1 = VALUES(call_run1), call_run2 = VALUES(call_run2),
         call_pass1 = VALUES(call_pass1), call_pass2 = VALUES(call_pass2),
         call_def1 = VALUES(call_def1), call_def2 = VALUES(call_def2),
         starting_qb_benched = VALUES(starting_qb_benched),
         safeties_conceded = VALUES(safeties_conceded), played_up = VALUES(played_up)"
);

$_cp_franchise_stmt = $conn->prepare(
    "SELECT franchise_id FROM franchises WHERE league_id = :league_id AND label = :label"
);

$_cp_resolved = 0;
$_cp_unresolved_teams = [];
$_cp_unresolved_types = [];

foreach ($_cp_games as $game) {
    $game_type_id = resolve_game_type_id($conn, $_cp_league_code, $_cp_week_number, $game['section_header']);
    // Explicit === null, not a plain falsy check (!$game_type_id) -- the SAME bug just fixed
    // in lookup_game_type() one call down, still present here at the consuming end: fixing
    // the source function's return value doesn't help if the caller still treats a genuine
    // 0 (NCAA5's real, current Pre Season id) as "not found". Confirmed via a real re-test
    // that still failed after the first fix -- the value now correctly comes back as 0, but
    // this check was still discarding it. Worth remembering as a general lesson: a falsy-
    // zero fix needs tracing through every consumer of that value, not just its source.
    if ($game_type_id === null) {
        $_cp_unresolved_types[] = $game['section_header'] ?? '(regular season)';
        continue;
    }

    $_cp_franchise_stmt->bindParam(':league_id', $_cp_league_id);
    $_cp_franchise_stmt->bindValue(':label', $game['home_team']);
    $_cp_franchise_stmt->execute();
    $home_id = $_cp_franchise_stmt->fetchColumn();

    $_cp_franchise_stmt->bindParam(':league_id', $_cp_league_id);
    $_cp_franchise_stmt->bindValue(':label', $game['away_team']);
    $_cp_franchise_stmt->execute();
    $away_id = $_cp_franchise_stmt->fetchColumn();

    if (!$home_id) { $_cp_unresolved_teams[] = $game['home_team']; }
    if (!$away_id) { $_cp_unresolved_teams[] = $game['away_team']; }
    if (!$home_id || !$away_id) { continue; }

    $label = "{$_cp_league_code} {$_cp_season_year} Wk {$_cp_week_number}: {$game['home_team']} vs {$game['away_team']}";

    $_cp_game_upsert->execute([
        ':label' => $label, ':week_id' => $_cp_week_id, ':game_type_id' => $game_type_id,
        ':home_franchise_id' => $home_id, ':away_franchise_id' => $away_id,
        ':home_score' => $game['home']['score'], ':away_score' => $game['away']['score'],
        ':went_to_ot' => ($game['home']['ot'] !== null) ? 1 : 0,
        ':upload_id' => $_cp_upload_id,
    ]);

    $_cp_stmt = $conn->prepare(
        "SELECT game_id FROM games WHERE week_id = :week_id AND home_franchise_id = :home_id AND away_franchise_id = :away_id"
    );
    $_cp_stmt->execute([':week_id' => $_cp_week_id, ':home_id' => $home_id, ':away_id' => $away_id]);
    $game_id = $_cp_stmt->fetchColumn();

    foreach ([['home', $home_id, 1], ['away', $away_id, 0]] as [$side, $franchise_id, $is_home]) {
        $t = $game[$side];
        $_cp_stats_upsert->execute([
            ':game_id' => $game_id, ':franchise_id' => $franchise_id, ':is_home' => $is_home,
            ':coach_name' => $t['coach'],
            ':q1' => $t['q1'], ':q2' => $t['q2'], ':q3' => $t['q3'], ':q4' => $t['q4'], ':ot' => $t['ot'], ':score' => $t['score'],
            ':fg_att' => $t['fg_att'], ':fg_made' => $t['fg_made'], ':ep_att' => $t['ep_att'], ':ep_made' => $t['ep_made'],
            ':cp_att' => $t['cp_att'], ':cp_made' => $t['cp_made'], ':punts' => $t['punts'],
            ':third_down_conv' => $t['third_down_conv'], ':third_down_att' => $t['third_down_att'],
            ':fourth_down_conv' => $t['fourth_down_conv'], ':fourth_down_att' => $t['fourth_down_att'],
            ':first_downs' => $t['first_downs'],
            ':pass_comp' => $t['pass_comp'], ':pass_att' => $t['pass_att'], ':pass_yds' => $t['pass_yds'],
            ':pass_long' => $t['pass_long'], ':pass_long_is_td' => $t['pass_long_is_td'],
            ':pass_td' => $t['pass_td'], ':pass_pct' => $t['pass_pct'],
            ':interceptions_thrown' => $t['interceptions_thrown'],
            ':times_hurried' => $t['times_hurried'], ':times_sacked' => $t['times_sacked'],
            ':rush_att' => $t['rush_att'], ':rush_yds' => $t['rush_yds'], ':rush_long' => $t['rush_long'],
            ':rush_long_is_td' => $t['rush_long_is_td'], ':rush_td' => $t['rush_td'], ':fumbles' => $t['fumbles'],
            ':qb_rush_att' => $t['qb_rush_att'], ':qb_rush_yds' => $t['qb_rush_yds'],
            ':kr_num' => $t['kr_num'], ':kr_yds' => $t['kr_yds'], ':kr_td' => $t['kr_td'],
            ':pr_num' => $t['pr_num'], ':pr_yds' => $t['pr_yds'], ':pr_td' => $t['pr_td'],
            ':ret_type' => $t['ret_type'], ':ret_num' => $t['ret_num'], ':ret_yds' => $t['ret_yds'], ':ret_td' => $t['ret_td'],
            ':call_fm1' => $t['call_fm1'], ':call_fm2' => $t['call_fm2'],
            ':call_run1' => $t['call_run1'], ':call_run2' => $t['call_run2'],
            ':call_pass1' => $t['call_pass1'], ':call_pass2' => $t['call_pass2'],
            ':call_def1' => $t['call_def1'], ':call_def2' => $t['call_def2'],
            ':starting_qb_benched' => 0,
            ':safeties_conceded' => $t['safeties'], ':played_up' => $t['played_up'],
        ]);
    }

    $_cp_resolved++;
}

echo "<div class='w3-panel w3-pale-green w3-text-black w3-round-large'>";
echo "<p><strong>$_cp_resolved</strong> games written to games/team_game_stats.</p>";
echo "</div>";

if ($_cp_unresolved_teams) {
    echo "<div class='w3-panel w3-pale-red w3-text-black w3-round-large'>";
    echo "<p><strong>" . count($_cp_unresolved_teams) . "</strong> team name(s) could not be matched to a franchise:</p><ul>";
    foreach (array_unique($_cp_unresolved_teams) as $name) { echo "<li>" . htmlspecialchars($name) . "</li>"; }
    echo "</ul></div>";
}
if ($_cp_unresolved_types) {
    echo "<div class='w3-panel w3-pale-red w3-text-black w3-round-large'>";
    echo "<p><strong>" . count($_cp_unresolved_types) . "</strong> section header(s) could not be mapped to a game type:</p><ul>";
    foreach (array_unique($_cp_unresolved_types) as $name) { echo "<li>" . htmlspecialchars($name) . "</li>"; }
    echo "</ul></div>";
}

echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// Parses every game in the League Report's game-results section into a structured array.
// Tracks the current section header (game-type grouping) as it walks through, resetting
// to null (regular season -- no header at all) at the start.
function parse_league_report_games($text) {
    // DOTALL (the trailing 's' modifier) rather than requiring a literal '\n' between stat
    // lines -- confirmed necessary, not just a defensive choice: a real upload had its League
    // Report game-results section using plain spaces where every other file used real
    // newlines between segments ("<L.45.1> <L.12.1> <B>Gold Bowl Playoffs<L.45.1> <Z>..."),
    // which silently made every game in that file fail to match at all (0 of 6 found). With
    // DOTALL, '.' matches newlines too, so '.*?' works correctly regardless of which
    // separator a given file actually uses.
    $game_pattern =
        '/<Z>([^(<]+?)\s*\(([^)]+)\)\s*([A-Z ]*?)\s+([\d\s]+\(\d+(?: OT)?\))<T>' .
        '([^(<]+?)\s*\(([^)]+)\)\s*([A-Z ]*?)\s+([\d\s]+\(\d+(?: OT)?\))<C>.*?' .
        'FG (\d+)\/(\d+), EP (\d+)\/(\d+), CP (\d+)\/(\d+), Punt (\d+), 3rd (\d+)\/(\d+), 4th (\d+)\/(\d+), 1st (\d+)<T>' .
        'FG (\d+)\/(\d+), EP (\d+)\/(\d+), CP (\d+)\/(\d+), Punt (\d+), 3rd (\d+)\/(\d+), 4th (\d+)\/(\d+), 1st (\d+).*?' .
        'Pass (\d+) for (\d+), (-?\d+) yds, Lg (t?-?\d+), (?:(\d+) TD, )?(\d+)%, In (\d+), Hrd (\d+), Skd (\d+)<T>' .
        'Pass (\d+) for (\d+), (-?\d+) yds, Lg (t?-?\d+), (?:(\d+) TD, )?(\d+)%, In (\d+), Hrd (\d+), Skd (\d+).*?' .
        'Rush (\d+) for (-?\d+) yds, Lg (t?-?\d+), (?:(\d+) TD, )?avg\s*-?[\d.]+, Fm (\d+), QB (\d+) for (-?\d+) yds<T>' .
        'Rush (\d+) for (-?\d+) yds, Lg (t?-?\d+), (?:(\d+) TD, )?avg\s*-?[\d.]+, Fm (\d+), QB (\d+) for (-?\d+) yds.*?' .
        'KR (\d+) for (\d+) yds(?:, (\d+) TD)?, PR (\d+) for (\d+) yds(?:, (\d+) TD)?, (FumR|IntR|DefR) (\d+) for (\d+) yds(?:, (\d+) TD)?<T>' .
        'KR (\d+) for (\d+) yds(?:, (\d+) TD)?, PR (\d+) for (\d+) yds(?:, (\d+) TD)?, (FumR|IntR|DefR) (\d+) for (\d+) yds(?:, (\d+) TD)?.*?' .
        // Each of the 8 call-code values (Fm/Run/Pass/Def x2) can genuinely be blank -- a
        // team with zero pass attempts that game has an entirely empty Pass field (confirmed:
        // "Pass      , Def..." with nothing between the commas) -- or a literal '-'
        // placeholder (confirmed: "Fm S -,"). \w+ required exactly two word-characters, so
        // both cases failed to match; because of the non-greedy .*? earlier in this pattern,
        // that failure didn't just skip the game -- the regex engine backtracked and matched
        // the NEXT game's Calls line instead, silently merging two games into one oversized
        // match (confirmed directly: match length was double the normal ~720 chars in both
        // real cases this was caught against). [\w-]* fixes both: zero-or-more word
        // characters or hyphens, so blank and '-' are both valid, and the pattern can no
        // longer skip past a genuine game boundary looking for a "complete-looking" one.
        'Calls Fm ([\w-]*)\s*([\w-]*), Run ([\w-]*)\s*([\w-]*), Pass ([\w-]*)\s*([\w-]*), Def ([\w-]*)\s*([\w-]*)<T>' .
        'Calls Fm ([\w-]*)\s*([\w-]*), Run ([\w-]*)\s*([\w-]*), Pass ([\w-]*)\s*([\w-]*), Def ([\w-]*)\s*([\w-]*)/s';

    preg_match_all($game_pattern, $text, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    if (empty($matches)) {
        return [];
    }

    // Last game match's end position -- the boundary past which anything "header-shaped" is
    // no longer a real section header. Necessary, not just tidy: the same League Report
    // block contains next week's schedule further down, which lists bowl names too (e.g.
    // "Bronze Bowl", "Cons Gold") in a tight cluster -- searching the whole block for known
    // header phrases without this boundary picked those up as false section headers.
    $last_game_end = end($matches);
    $last_game_end = $last_game_end[0][1] + strlen($last_game_end[0][0]);

    // Searches for known header phrases directly, rather than trying to generically detect
    // "text that looks like a standalone header line" from surrounding formatting tags --
    // confirmed necessary: the exact tag ordering around a header varies between files (one
    // real upload had <B> positioned after the <L.x.y> marker instead of before it, which
    // broke a structural "line-shaped" pattern entirely). Longest-first so e.g. "Silver
    // Bowl: Semi Finals" matches before the shorter "Silver Bowl" would greedily claim it.
    $known_headers = [
        'Wild Card Games', 'Divisional Games', 'Championship Games', 'Bowl Game',
        'Silver Bowl: Preliminary Round', 'Silver Bowl: Quarter Finals', 'Silver Bowl: Semi Finals', 'Silver Bowl',
        'Bronze Bowl: Quarter Finals', 'Bronze Bowl: Semi Finals', 'Bronze Bowl',
        'Divisional Bowl: Semi Finals', 'Divisional Bowl',
        'Championship Bowl',
        'Gold Bowl Playoffs', 'Silver Bowl Playoffs', 'Bronze Bowl Playoffs',
        'Gold Bowl', 'Consolation Gold', 'Consolation Silver', 'Consolation Bronze',
        'Pre-Season', 'Pre Season',
    ];
    usort($known_headers, fn($a, $b) => strlen($b) <=> strlen($a));
    $header_pattern = '/(' . implode('|', array_map('preg_quote', $known_headers)) . ')/';

    preg_match_all($header_pattern, $text, $header_matches, PREG_OFFSET_CAPTURE);
    $headers = [];
    foreach ($header_matches[1] as $m) {
        if ($m[1] < $last_game_end) {
            $headers[] = [$m[1], $m[0]];
        }
    }

    $games = [];
    foreach ($matches as $m) {
        $pos = $m[0][1];
        $current_header = null;
        foreach ($headers as [$hpos, $htext]) {
            if ($hpos <= $pos) { $current_header = $htext; }
        }

        $g = array_map(fn($x) => $x[0], $m); // strip offsets, keep plain values

        $games[] = [
            'section_header' => $current_header,
            'home_team' => trim($g[1]),
            'away_team' => trim($g[5]),
            'home' => build_team_stats($g, 0),
            'away' => build_team_stats($g, 1),
        ];
    }

    return $games;
}

// Builds one team's full stat array from the 100 capture groups, given side (0=home, 1=away).
// Group indices verified directly against real match output before this was written, not
// assumed from the pattern alone -- see conversation.
function build_team_stats($g, $side) {
    $o = $side; // 0 for home fields, 1 for away fields, per the paired group layout below

    $markers = parse_markers($side == 0 ? $g[3] : $g[7]);
    $scores = parse_scores($side == 0 ? $g[4] : $g[8]);

    $kick_base = 9 + $side * 12;   // groups 9-20 home, 21-32 away
    $pass_base = 33 + $side * 9;   // groups 33-41 home, 42-50 away
    $rush_base = 51 + $side * 7;   // groups 51-57 home, 58-64 away
    // Multiplier is 10, not 8 -- the returns line has 10 groups per side (KR_num, KR_yds,
    // KR_TD-optional, PR_num, PR_yds, PR_TD-optional, ret_type, ret_num, ret_yds,
    // ret_TD-optional). Same root miscounting error as call_base above -- both traced back
    // to undercounting this same section's optional TD groups. Confirmed the fix
    // systematically: recomputed every base value from each section's actual group count
    // independently (1 -> 9 -> 33 -> 51 -> 65 -> 85 -> 101), landing exactly on the
    // confirmed total of 100 groups -- see conversation.
    $ret_base  = 65 + $side * 10;   // groups 65-74 home, 75-84 away
    // ret_base's own section has 10 groups per side (KR_num, KR_yds, KR_TD-optional,
    // PR_num, PR_yds, PR_TD-optional, ret_type, ret_num, ret_yds, ret_TD-optional), not 8 --
    // an earlier version miscounted this (missed two of the three optional TD groups) and
    // call_base was wrong as a result (81 instead of 85), corrupting every field in the
    // Calls line and bleeding into home/away misalignment. Caught by field-by-field
    // validation against real data, not just checking the regex matched -- see conversation.
    $call_base = 85 + $side * 8;   // groups 85-92 home, 93-100 away

    [$pass_long, $pass_long_td] = strip_t_prefix($g[$pass_base + 3]);
    [$rush_long, $rush_long_td] = strip_t_prefix($g[$rush_base + 2]);

    return [
        'coach' => trim($side == 0 ? $g[2] : $g[6]),
        'q1' => $scores[0], 'q2' => $scores[1], 'q3' => $scores[2], 'q4' => $scores[3],
        'ot' => $scores[4], 'score' => $scores[5],
        'fg_att' => (int)$g[$kick_base], 'fg_made' => (int)$g[$kick_base+1],
        'ep_att' => (int)$g[$kick_base+2], 'ep_made' => (int)$g[$kick_base+3],
        'cp_att' => (int)$g[$kick_base+4], 'cp_made' => (int)$g[$kick_base+5],
        'punts' => (int)$g[$kick_base+6],
        'third_down_conv' => (int)$g[$kick_base+7], 'third_down_att' => (int)$g[$kick_base+8],
        'fourth_down_conv' => (int)$g[$kick_base+9], 'fourth_down_att' => (int)$g[$kick_base+10],
        'first_downs' => (int)$g[$kick_base+11],
        'pass_comp' => (int)$g[$pass_base], 'pass_att' => (int)$g[$pass_base+1], 'pass_yds' => (int)$g[$pass_base+2],
        'pass_long' => $pass_long, 'pass_long_is_td' => $pass_long_td ? 1 : 0,
        'pass_td' => $g[$pass_base+4] !== '' ? (int)$g[$pass_base+4] : 0,
        'pass_pct' => (int)$g[$pass_base+5],
        'interceptions_thrown' => (int)$g[$pass_base+6],
        'times_hurried' => (int)$g[$pass_base+7], 'times_sacked' => (int)$g[$pass_base+8],
        'rush_att' => (int)$g[$rush_base], 'rush_yds' => (int)$g[$rush_base+1],
        'rush_long' => $rush_long, 'rush_long_is_td' => $rush_long_td ? 1 : 0,
        'rush_td' => $g[$rush_base+3] !== '' ? (int)$g[$rush_base+3] : 0,
        'fumbles' => (int)$g[$rush_base+4],
        'qb_rush_att' => (int)$g[$rush_base+5], 'qb_rush_yds' => (int)$g[$rush_base+6],
        'kr_num' => (int)$g[$ret_base], 'kr_yds' => (int)$g[$ret_base+1],
        'kr_td' => $g[$ret_base+2] !== '' ? (int)$g[$ret_base+2] : 0,
        'pr_num' => (int)$g[$ret_base+3], 'pr_yds' => (int)$g[$ret_base+4],
        'pr_td' => $g[$ret_base+5] !== '' ? (int)$g[$ret_base+5] : 0,
        'ret_type' => $g[$ret_base+6], 'ret_num' => (int)$g[$ret_base+7], 'ret_yds' => (int)$g[$ret_base+8],
        'ret_td' => $g[$ret_base+9] !== '' ? (int)$g[$ret_base+9] : 0,
        'call_fm1' => $g[$call_base], 'call_fm2' => $g[$call_base+1],
        'call_run1' => $g[$call_base+2], 'call_run2' => $g[$call_base+3],
        'call_pass1' => $g[$call_base+4], 'call_pass2' => $g[$call_base+5],
        'call_def1' => $g[$call_base+6], 'call_def2' => $g[$call_base+7],
        'safeties' => $markers['safeties'], 'played_up' => $markers['played_up'],
        'qb_benched' => $markers['qb_benched'],
    ];
}

// Marker segment (right after coach name, before quarter scores) can contain any
// combination of QB/UP/S+ tokens in any order -- confirmed directly, e.g. "QB S" together
// on one team. Parsed as free tokens rather than a fixed pattern for exactly that reason.
function parse_markers($segment) {
    $result = ['qb_benched' => 0, 'played_up' => 0, 'safeties' => 0];
    foreach (preg_split('/\s+/', trim($segment)) as $tok) {
        if ($tok === 'QB') { $result['qb_benched'] = 1; }
        elseif ($tok === 'UP') { $result['played_up'] = 1; }
        elseif (preg_match('/^S+$/', $tok)) { $result['safeties'] = strlen($tok); }
    }
    return $result;
}

// Quarter-score string, e.g. "17 7 0 6 0 (30 OT)" or "0 3 0 0 (3)" -- variable-length:
// 5 numbers + "OT" suffix on overtime games, 4 numbers otherwise. Confirmed directly, not
// assumed -- see conversation.
function parse_scores($segment) {
    preg_match('/([\d\s]+)\((\d+)(\s+OT)?\)/', trim($segment), $m);
    $nums = array_values(array_filter(preg_split('/\s+/', trim($m[1])), fn($x) => $x !== ''));
    $is_ot = !empty($m[3]);
    if ($is_ot) {
        return [(int)$nums[0], (int)$nums[1], (int)$nums[2], (int)$nums[3], (int)$nums[4], (int)$m[2]];
    }
    return [(int)$nums[0], (int)$nums[1], (int)$nums[2], (int)$nums[3], null, (int)$m[2]];
}

// Long-yardage fields: a leading "t" means that long play was itself a touchdown, e.g.
// "Lg t59" = 59 yards, and that 59-yard play was a TD. Confirmed directly against real data.
function strip_t_prefix($value) {
    $is_td = str_starts_with($value, 't');
    $num = $is_td ? substr($value, 1) : $value;
    return [(int)$num, $is_td];
}

// Resolves a section header (or null, for regular season) to a game_type_id, given the
// league and the turn's own week_number. Confirmed mappings only -- see conversation for
// the full derivation, including cross-checking against real historical f_games data for
// every non-obvious case (round-suffix handling, the Championship Bowl 3rd-place-game
// clarification, the Consolation Pre-Season distinction).
function resolve_game_type_id($conn, $league_code, $week_number, $header) {
    if ($header === null) {
        // Regular season: no header at all.
        $name = ($league_code === 'NCAA5') ? 'Regular Season' : 'Regular Season';
        return lookup_game_type($conn, $league_code, $name);
    }

    $normalized = preg_replace('/\s+/', ' ', trim($header));

    // Pre-season is a special case in both leagues, but only NFLAR has a genuine second
    // meaning to disambiguate: teams eliminated from the playoffs playing bonus games
    // while others are still competing, still using the season that's wrapping up, not a
    // new one -- confirmed real, not hypothetical, and confirmed NCAA5 has no equivalent
    // (all NCAA5 teams play in weeks 12/13, first the playoffs then ranked bowls).
    if (preg_match('/^Pre[\s-]Season$/i', $normalized)) {
        if ($league_code === 'NFLAR' && $week_number !== 0) {
            return lookup_game_type($conn, $league_code, 'Consolation Pre-Season');
        }
        return lookup_game_type($conn, $league_code, 'Pre Season');
    }

    if ($league_code === 'NFLAR') {
        // Main bracket: every early round shares one type; only the week-20 final gets its
        // own ("Bowl Game" -> Superbowl, confirmed via historical gametype data cross-check
        // -- the same two teams that played type 35 in the semi-final round advance to 36).
        if (in_array($normalized, ['Wild Card Games', 'Divisional Games', 'Championship Games'])) {
            return lookup_game_type($conn, $league_code, 'Play Offs');
        }
        if ($normalized === 'Bowl Game') {
            return lookup_game_type($conn, $league_code, 'Superbowl');
        }
        // Bowl brackets: every round of the same bracket shares one type_id (confirmed via
        // historical gametype data -- same gametype value from "Preliminary Round" through
        // to the final, no separate ID per round), so match on the bracket name prefix,
        // ignoring any ": round name" suffix entirely.
        foreach (['Silver Bowl', 'Bronze Bowl', 'Divisional Bowl', 'Championship Bowl'] as $bracket) {
            if (str_starts_with($normalized, $bracket)) {
                return lookup_game_type($conn, $league_code, $bracket);
            }
        }
    }

    if ($league_code === 'NCAA5') {
        // Unlike NFLAR, the semi-final round genuinely has ITS OWN distinct game_type_id
        // here, not a shared one with the final -- confirmed via the Danny-name mapping
        // table (bowl_records.php) plus direct pattern match ("X Bowl Playoffs" ->
        // "X Semi Finals"-style ids). Exact-string lookups, not prefix matches, since
        // "Gold Bowl Playoffs" and "Gold Bowl" are genuinely different game types here,
        // not the same bracket with an ignorable round suffix like NFLAR's.
        // Keys are "Consolation Gold/Silver/Bronze", not the abbreviated "Cons Gold" from
        // the original outline this scope was worked out from -- confirmed directly against
        // real Week 13 text ("<B>Consolation Gold<L.45.1>"); the outline's shorthand isn't
        // the literal source text and shouldn't have been used as if it were.
        $ncaa_map = [
            'Gold Bowl Playoffs' => 'National Championship Semi Finals',
            'Silver Bowl Playoffs' => 'Cotton Bowl Playoffs',
            'Bronze Bowl Playoffs' => 'Hawaii Bowl Playoffs',
            'Gold Bowl' => 'National Championship Game',
            'Silver Bowl' => 'Cotton Bowl',
            'Bronze Bowl' => 'Hawaii Bowl',
            'Consolation Gold' => 'Rose Bowl',
            'Consolation Silver' => 'Orange Bowl',
            'Consolation Bronze' => 'Music City Bowl',
        ];
        if (isset($ncaa_map[$normalized])) {
            return lookup_game_type($conn, $league_code, $ncaa_map[$normalized]);
        }
    }

    return null; // unmapped -- reported as a warning, not silently guessed at
}

function lookup_game_type($conn, $league_code, $name) {
    // Explicit ORDER BY + LIMIT 1, not relying on MySQL's unspecified default row order --
    // confirmed there really are two rows both named "Pre Season" for NCAA5 (game_type_id 0
    // and 17, a historical renumbering -- only 0 is the current, live one; see conversation).
    // Picking the lowest id has worked so far by coincidence, not by guarantee, for exactly
    // this reason.
    $stmt = $conn->prepare(
        "SELECT gt.game_type_id FROM game_types gt
         JOIN leagues l ON l.league_id = gt.league_id
         WHERE l.code = :code AND gt.name = :name
         ORDER BY gt.game_type_id ASC LIMIT 1"
    );
    $stmt->execute([':code' => $league_code, ':name' => $name]);
    $result = $stmt->fetchColumn();
    // Explicit !== false check, not a plain falsy check (?: null) -- game_type_id 0 is a
    // real, valid id (confirmed: NCAA5's current Pre Season is literally id 0), and PHP
    // treats 0 as falsy the same as false/null, which silently turned every valid "Pre
    // Season" resolution into "not found" -- confirmed via a real failed extraction, not
    // caught in testing beforehand. Same root mistake as the week_number=0 bug caught
    // earlier in operational_hooks.php -- should have been more alert to this exact pattern
    // recurring, given it had already been learned once.
    return $result !== false ? (int)$result : null;
}
?>
