<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Coach View -- lists every franchise (across both leagues) associated
// with one coach, via coaches.id_user -> franchise_coach_tenures (the
// tenure with end_week_id IS NULL, i.e. currently open) -> franchises.
//
// coaches.id_user already existed for exactly this purpose before this
// page was first built (confirmed in new_schema.sql and the live DB
// dump) -- an earlier version of this page used a new franchises.
// coach_user_id column instead, built without knowing coaches.id_user
// was already there (see conversation -- it survived in new_schema.sql's
// own inline comment but not in schema.md's narrative summary of a much
// longer, separate conversation, so it didn't surface until asked about
// directly). coach_user_id has been dropped (see
// migration_drop_coach_user_id.sql); this is the corrected version.
//
// coaches.id_user is deliberately NOT UNIQUE, against DaDaBIK's own User
// Entities recommendation -- the same real person can coach in both
// leagues at once (confirmed in the real data: Alan Milnes has two
// `coaches` rows, one per league), so one login legitimately maps to
// multiple `coaches` rows, each with its own current tenure. That's
// exactly why this query joins through franchise_coach_tenures rather
// than expecting a single franchise per coach.
//
// Field names below (first_name_user/last_name_user/username_user)
// confirmed against this install's actual users table -- it's
// zpbm_users, not DaDaBIK's default dadabik_users (custom table prefix,
// per DESCRIBE zpbm_users).
//
// Which team ownership a coach has is already effectively public
// information (team_game_stats.coach_name is shown, unguarded, on every
// game page and the team page's Coach: row) -- so this page carries no
// new privacy exposure, and isn't gated.
//
// Reached via ?user={zpbm_users.id_user}. No `user` param -> falls
// back to the logged-in session's own id_user, so a coach can find this
// page and see their own teams with no query string at all. Doesn't
// currently have a picker/search-by-name -- out of scope for a first
// draft, same reasoning as game.php's original "no game browser" note:
// build the destination page first, decide how people should arrive at
// it as a separate, later step.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";

// team.php's registered id_static_page (confirmed value, see conversation).
define('TEAM_PAGE_STATIC_ID', 4);

function build_team_link($league_code, $franchise_id) {
    return htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . TEAM_PAGE_STATIC_ID
        . '&league=' . urlencode($league_code) . '&franchise=' . urlencode($franchise_id)
    );
}

$_cp_user_id = isset($_GET['user']) ? (int)$_GET['user'] : 0;
if (!$_cp_user_id) {
    // No explicit ?user= -- fall back to whoever's logged in. id_user matches
    // zpbm_users' own column name exactly, same as id_group/username_user
    // already confirmed elsewhere in this app (home.php) -- reasonably
    // confident this session key exists, though not directly confirmed
    // the way id_group/username_user were.
    $_cp_user_id = (int)($_SESSION['logged_user_infos_ar']['id_user'] ?? 0);
}

if (!$_cp_user_id) {
    echo "<h1>Coach View</h1>";
    echo "<p><em>No coach specified, and no one is logged in to default to. "
       . "Use <code>?user={id}</code>, or log in to see your own teams.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Resolve the coach's display name --------------------
// get_record_details()'s 4th param (TRUE) makes it return FALSE instead of
// throwing on no match, rather than a raw query here -- this table
// belongs to DaDaBIK itself, not this project, so going through the
// Custom Code API (documented, stable interface) rather than assuming
// exact column names/types in a hand-written SELECT.
$_cp_user = ddb_api::get_record_details('zpbm_users', 'id_user', $_cp_user_id, true);

if (!$_cp_user) {
    echo "<h1>Coach View</h1>";
    echo "<p><em>No user found for ID $_cp_user_id.</em></p>";
    echo "</div>";
    exit;
}

// Best-effort display name -- try first+last, fall back to username, fall
// back to a bare ID.
$_cp_first = trim($_cp_user['first_name_user'] ?? '');
$_cp_last = trim($_cp_user['last_name_user'] ?? '');
if ($_cp_first || $_cp_last) {
    $_cp_coach_display = trim("$_cp_first $_cp_last");
} elseif (!empty($_cp_user['username_user'])) {
    $_cp_coach_display = $_cp_user['username_user'];
} else {
    $_cp_coach_display = "User #$_cp_user_id";
}

// -------------------- Franchises this coach manages --------------------
// Via coaches.id_user -> the currently-open tenure (end_week_id IS NULL) ->
// franchises. DISTINCT as a defensive measure, not because it's expected to be
// needed -- franchise_coach_tenures should never have two open tenures for the
// same franchise, but a query one join away from a person's actual data access
// is worth being defensive in, not just optimistic.
$_cp_sql = "SELECT DISTINCT f.franchise_id, f.label, l.code AS league_code, l.name AS league_name
            FROM coaches c
            JOIN franchise_coach_tenures fct ON fct.coach_id = c.coach_id AND fct.end_week_id IS NULL
            JOIN franchises f ON f.franchise_id = fct.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            WHERE c.id_user = :user_id
            ORDER BY l.code, f.label";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':user_id', $_cp_user_id);
$_cp_stmt->execute();
$_cp_franchises = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------- Career record (every tenure, not just the current one) --------------------
// Same season-ending-coach tie-break rule as team.php's coach_for_season() -- a mid-season
// coaching change attributes that season's full record to whoever finished it, not split or
// attributed to whoever started it, for consistency with how the rest of this app already
// handles that ambiguity.
//
// Reusing franchise_season_records (not summing raw games/team_game_stats) is deliberate: it's
// the only source that includes the legacy_rollup era (pre-2003 NFLAR, no individual game data
// at all) -- summing games directly would silently drop a coach's entire pre-2003 career, the
// same gap schema.md already documents for team.php's own season table.
$_cp_sql = "SELECT fsr.franchise_id, fsr.season_id, l.code AS league_code, l.name AS league_name,
                   fsr.wins, fsr.losses, fsr.ties, fsr.points_for, fsr.points_against
            FROM franchise_season_records fsr
            JOIN seasons s ON s.season_id = fsr.season_id
            JOIN leagues l ON l.league_id = s.league_id
            JOIN weeks w ON w.week_id = (SELECT MAX(week_id) FROM weeks WHERE season_id = fsr.season_id)
            JOIN franchise_coach_tenures fct ON fct.franchise_id = fsr.franchise_id
                AND fct.start_week_id <= w.week_id
                AND (fct.end_week_id IS NULL OR fct.end_week_id >= w.week_id)
            JOIN coaches c ON c.coach_id = fct.coach_id
            WHERE c.id_user = :user_id";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':user_id', $_cp_user_id);
$_cp_stmt->execute();
$_cp_seasons_coached = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

// Tallied in PHP rather than a second SQL pass -- this result set is one row per season this
// specific person coached across their whole career, small regardless of how the totals get
// sliced.
$_cp_record_overall = ['wins' => 0, 'losses' => 0, 'ties' => 0, 'pf' => 0, 'pa' => 0];
$_cp_record_by_league = [];
foreach ($_cp_seasons_coached as $row) {
    foreach (['wins', 'losses', 'ties'] as $k) {
        $_cp_record_overall[$k] += (int)$row[$k];
    }
    $_cp_record_overall['pf'] += (int)$row['points_for'];
    $_cp_record_overall['pa'] += (int)$row['points_against'];

    $lg = $row['league_name'];
    if (!isset($_cp_record_by_league[$lg])) {
        $_cp_record_by_league[$lg] = ['wins' => 0, 'losses' => 0, 'ties' => 0, 'pf' => 0, 'pa' => 0];
    }
    foreach (['wins', 'losses', 'ties'] as $k) {
        $_cp_record_by_league[$lg][$k] += (int)$row[$k];
    }
    $_cp_record_by_league[$lg]['pf'] += (int)$row['points_for'];
    $_cp_record_by_league[$lg]['pa'] += (int)$row['points_against'];
}

// -------------------- Honors won during those same seasons --------------------
// Same tenure-join pattern as above, applied to franchise_honors -- an honor only counts toward
// this coach's career if they were the season-ending coach when it was won, for consistency
// with how the record above is attributed.
$_cp_sql = "SELECT l.code AS league_code, l.name AS league_name, ht.code AS honor_code
            FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN seasons s ON s.season_id = fh.season_id
            JOIN leagues l ON l.league_id = s.league_id
            JOIN weeks w ON w.week_id = (SELECT MAX(week_id) FROM weeks WHERE season_id = fh.season_id)
            JOIN franchise_coach_tenures fct ON fct.franchise_id = fh.franchise_id
                AND fct.start_week_id <= w.week_id
                AND (fct.end_week_id IS NULL OR fct.end_week_id >= w.week_id)
            JOIN coaches c ON c.coach_id = fct.coach_id
            WHERE c.id_user = :user_id";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':user_id', $_cp_user_id);
$_cp_stmt->execute();
$_cp_honors_won = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

// Every *_BOWL_WINNER/CIC_WINNER code bucketed together as one "Other Bowl Wins" count, EXCEPT
// the Rose Bowl, which gets its own line -- calling out every specific bowl by name felt like
// more detail than a career-summary page needs, but Rose Bowl specifically is a big enough deal
// (the Granddaddy of Them All) to warrant not disappearing into a merged number. team.php's own
// per-franchise honors table already gives full per-bowl detail for anyone who wants it.
// honor_types codes confirmed in new_schema.sql: LEAGUE_WINNER, LEAGUE_RUNNERUP (college only),
// CONFERENCE_CHAMPION, DIVISION_CHAMPION + WILDCARD_BERTH (pro only), the five named bowls +
// CIC_WINNER (college only), RIVALRY_WINNER, PERFECT_SEASON (college only). RIVALRY_WINNER
// deliberately excluded below -- a season-by-season rivalry result isn't really a "career
// honor" the way a championship or bowl win is.
//
// Keyed by league_code (NFLAR/NCAA5), not league_name -- code is the reliable pro/college
// signal already used everywhere else in this app (team.php: $_cp_is_pro =
// $_cp_franchise['league_code'] === 'NFLAR'), name is just display text and shouldn't be
// parsed/guessed at to infer anything.
$_cp_honor_counts = []; // [league_code]['name' => ..., 'counts' => [bucket => count]]
$_cp_bowl_codes = ['COTTON_BOWL_WINNER', 'ORANGE_BOWL_WINNER',
                    'HAWAII_BOWL_WINNER', 'MUSIC_CITY_BOWL_WINNER', 'CIC_WINNER'];
foreach ($_cp_honors_won as $row) {
    if ($row['honor_code'] === 'RIVALRY_WINNER') {
        continue;
    }
    $lc = $row['league_code'];
    if (!isset($_cp_honor_counts[$lc])) {
        $_cp_honor_counts[$lc] = ['name' => $row['league_name'], 'counts' => []];
    }
    $bucket = in_array($row['honor_code'], $_cp_bowl_codes, true) ? 'OTHER_BOWL_WINS' : $row['honor_code'];
    $_cp_honor_counts[$lc]['counts'][$bucket] = ($_cp_honor_counts[$lc]['counts'][$bucket] ?? 0) + 1;
}

// -------------------- Header --------------------
echo "<div class='w3-panel w3-theme'>";
echo "<h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>";
echo "<b>" . htmlspecialchars($_cp_coach_display) . "</b></h1>";
echo "</div>";

// -------------------- Career Record --------------------
if (!empty($_cp_seasons_coached)) {
    echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
       . "<b>Career Record</b></h1></div>";
    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:420px'>";
    echo "<tr><th>&nbsp;</th><th>Record</th><th>Points</th></tr>";
    echo career_record_row('Overall', $_cp_record_overall);
    foreach ($_cp_record_by_league as $lg => $rec) {
        echo career_record_row($lg, $rec);
    }
    echo "</table><br>";
}

// -------------------- Championships & Honors --------------------
if (!empty($_cp_honor_counts)) {
    echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
       . "<b>Championships &amp; Honours</b></h1></div>";
    foreach ($_cp_honor_counts as $lc => $league_data) {
        echo "<h3 style='margin-top:16px'>" . htmlspecialchars($league_data['name']) . "</h3>";
        echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:420px'>";
        foreach (honor_display_labels($lc) as $bucket => $label) {
            if (!empty($league_data['counts'][$bucket])) {
                echo "<tr><td style='padding:4px 8px'>" . htmlspecialchars($label) . "</td>"
                   . "<td style='padding:4px 8px'>{$league_data['counts'][$bucket]}</td></tr>";
            }
        }
        echo "</table>";
    }
    echo "<br>";
}

if (empty($_cp_franchises)) {
    echo "<p><em>" . htmlspecialchars($_cp_coach_display) . " isn't currently linked to any franchises.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Franchise list, grouped by league --------------------
$_cp_by_league = [];
foreach ($_cp_franchises as $f) {
    $_cp_by_league[$f['league_name']][] = $f;
}

foreach ($_cp_by_league as $_cp_league_name => $_cp_league_franchises) {
    echo "<h3 style='margin-top:16px'>" . htmlspecialchars($_cp_league_name) . "</h3>";
    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:400px'>";
    foreach ($_cp_league_franchises as $f) {
        $link = build_team_link($f['league_code'], $f['franchise_id']);
        echo "<tr><td style='padding:4px 8px'><a href='$link' style='color:inherit;text-decoration:underline'>" . htmlspecialchars($f['label']) . "</a></td></tr>";
    }
    echo "</table>";
}
// Breathing room after the last table, before the outer panel closes -- the h3's margin-top
// above spaces multiple league tables apart from each other, but nothing was giving the final
// table any room before the panel's bottom edge.
echo "<br>";
echo "</div>";

// Record-text formatting matches team.php's Season by Season table exactly (see
// coach_since_year()/coach_for_season() area there) -- "{wins}-{losses}", with "(1 tie)" or
// "(N ties)" appended, not a new format invented for this page.
function career_record_row($label, $rec) {
    $record_text = "{$rec['wins']}-{$rec['losses']}";
    if ($rec['ties'] == 1) {
        $record_text .= " (1 tie)";
    } elseif ($rec['ties'] > 1) {
        $record_text .= " ({$rec['ties']} ties)";
    }
    $points_text = number_format($rec['pf']) . '-' . number_format($rec['pa']);
    return "<tr><th>" . htmlspecialchars($label) . "</th>"
         . "<td style='padding:4px 8px'>$record_text</td>"
         . "<td style='padding:4px 8px'>$points_text</td></tr>";
}

// Display order and labels for the Championships & Honors table. Only buckets with a nonzero
// count actually get a row (see call site) -- this list covers every honor_types code that can
// occur in either league, so it's safe to share across both league sections without
// hardcoding which codes belong to which league; a bucket a given league never earns simply
// never has a nonzero count and never renders.
function honor_display_labels($league_code) {
    // LEAGUE_WINNER's label matches team.php's own convention exactly (see there:
    // $_cp_is_pro ? 'Superbowl Champions' : 'National Championships') -- not a flat generic
    // label for both leagues. This is arguably the single most important honor on this whole
    // page, worth getting the actual name right rather than a placeholder-sounding one.
    // Checked against league_code (reliable, same signal team.php itself uses), not
    // league_name -- a display string shouldn't be parsed to infer anything.
    $league_champ_label = ($league_code === 'NFLAR') ? 'Superbowl Champions' : 'National Championships';

    return [
        'LEAGUE_WINNER' => $league_champ_label,
        'LEAGUE_RUNNERUP' => 'League Runner-Up',
        'ROSE_BOWL_WINNER' => 'Rose Bowl Wins',
        'CONFERENCE_CHAMPION' => 'Conference Championships',
        'DIVISION_CHAMPION' => 'Division Championships',
        'WILDCARD_BERTH' => 'Wildcard Berths',
        'PERFECT_SEASON' => 'Perfect Seasons',
        'OTHER_BOWL_WINS' => 'Other Bowl Wins',
    ];
}
