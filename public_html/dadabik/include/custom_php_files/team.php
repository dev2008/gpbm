<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
// __DIR__-relative include, not a bare relative path -- see current_standings.php for why.
// No g_functions.php include -- this page uses plain PDO throughout, same as
// current_standings.php.
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// Uses w3-theme-* throughout, matching home.php -- see there for the full reasoning on why
// every colored element needs BOTH an explicit background AND explicit text colour, never
// relying on inheritance from an ancestor. Tables use w3-striped's own built-in alternating
// effect rather than manually-coded per-row classes (there are three separate tables here,
// each built by several different helper functions rendering individual <tr> rows -- adding
// explicit per-row colour to every one of them would be a much larger, more error-prone
// change than just giving each <table> element itself an explicit light background + dark
// text; w3-striped's alternating tint layers correctly on top of that either way).
echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";


// ------------------------------------------------------------------
// Team Summary -- career honors, current-season results, and full
// season-by-season history for a single franchise.
//
// Built from fc_team.php/fp_team.php (uploaded for reference), but as one
// page covering both leagues rather than two near-duplicate files --
// same reasoning as current_standings.php. Pro and college genuinely
// track different honor categories though (divisions/wildcards vs.
// bowls/CIC), so those sections branch explicitly rather than being
// forced into one shared shape; only what's actually identical between
// the two (league/conference championship record, coach history, season
// totals) is shared.
//
// Three things deliberately fixed relative to the old pages, not just
// carried over:
//   1. Coach tenure. fp_team.php has a literal "//TODO Fix this as it
//      will not pick up leavers and rejoiners" next to its coach-since
//      query (MIN(season) WHERE coach=X breaks if that person left and
//      came back). franchise_coach_tenures already solves this properly.
//   2. Bowl runner-up records. The old college page only ever shows
//      bowl WINS (GC_Runnerup etc. were opaque career counts with no
//      year detail -- see the franchise_honors work earlier this
//      session). Real dated runner-up honors exist now, so bowl
//      records show both sides.
//   3. "National Championship Playoff Appearances" was approximated in
//      the old page as NC wins+losses + Rose wins+losses, before the
//      real playoff-round game types (semifinals, playoff-hosting
//      bowls) were understood. Computed directly from game
//      participation instead -- see playoff_appearances_college().
// ------------------------------------------------------------------

// game.php's registered id_static_page -- was a 0 placeholder, now the real value.
define('GAME_PAGE_STATIC_ID', 10);

function build_game_link($game_id) {
    return htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . GAME_PAGE_STATIC_ID
        . '&game=' . urlencode($game_id)
    );
}

$_cp_league = (isset($_GET['league']) && $_GET['league'] === 'NCAA5') ? 'NCAA5' : 'NFLAR';

// -------------------- League toggle --------------------
function build_page_link($params) {
    $parsed = parse_url($_SERVER['REQUEST_URI']);
    parse_str($parsed['query'] ?? '', $existing);
    $merged = array_merge($existing, $params);
    return htmlspecialchars($parsed['path'] . '?' . http_build_query($merged));
}

echo "<h1>Team Summary</h1>";

$_cp_leagues = ['NFLAR' => 'NFLAR (Pro)', 'NCAA5' => 'NCAA5 (College)'];
echo "<div class='w3-bar w3-light-grey w3-text-black' style='margin-bottom:16px'>";
foreach ($_cp_leagues as $code => $display_name) {
    $active = ($code === $_cp_league) ? 'w3-theme w3-text-white' : '';
    // switching league drops any previously selected franchise -- it belonged to the other league
    $link = build_page_link(['league' => $code, 'franchise' => null]);
    echo "<a href='$link' class='w3-bar-item w3-button $active'>$display_name</a>";
}
echo "</div>";

// -------------------- Franchise selector --------------------
$_cp_sql = "SELECT f.franchise_id, f.label
            FROM franchises f JOIN leagues l ON l.league_id = f.league_id
            WHERE l.code = :league ORDER BY f.label";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':league', $_cp_league);
$_cp_stmt->execute();
$_cp_franchise_list = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

$_cp_franchise_id = isset($_GET['franchise']) ? (int)$_GET['franchise'] : 0;
if (!$_cp_franchise_id && !empty($_cp_franchise_list)) {
    $_cp_franchise_id = (int)$_cp_franchise_list[0]['franchise_id'];
}

echo "<form method='get'>";
// Preserve Dadabik's own routing params (function=show_static_page&id_static_page=N) --
// a GET form with no explicit action submits to the current path but replaces the entire
// query string with only its own fields, dropping these -- exactly the same class of bug
// the league toggle links already had to work around (see build_page_link() above). Reading
// them from the current request rather than hardcoding this page's own id_static_page: it's
// self-correcting across any future reinstall, no constant to remember to update.
echo "<input type='hidden' name='function' value='" . htmlspecialchars($_GET['function'] ?? 'show_static_page') . "'>";
echo "<input type='hidden' name='id_static_page' value='" . htmlspecialchars($_GET['id_static_page'] ?? '') . "'>";
echo "<input type='hidden' name='league' value='" . htmlspecialchars($_cp_league) . "'>";
echo "<select name='franchise' onchange='this.form.submit()' style='width:280px'>";
foreach ($_cp_franchise_list as $fr) {
    $sel = ($fr['franchise_id'] == $_cp_franchise_id) ? 'selected' : '';
    echo "<option value='{$fr['franchise_id']}' $sel>" . htmlspecialchars($fr['label']) . "</option>";
}
echo "</select>";
echo "</form><br>";

if (empty($_cp_franchise_list) || !$_cp_franchise_id) {
    echo "<p><em>No franchises found for $_cp_league.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Franchise identity --------------------
$_cp_sql = "SELECT f.franchise_id, f.label, f.conference, f.division, f.is_academy, l.code AS league_code
            FROM franchises f JOIN leagues l ON l.league_id = f.league_id
            WHERE f.franchise_id = :fid";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_franchise = $_cp_stmt->fetch(PDO::FETCH_ASSOC);

if (!$_cp_franchise) {
    echo "<p><em>Franchise not found.</em></p>";
    echo "</div>";
    exit;
}

$_cp_is_pro = ($_cp_franchise['league_code'] === 'NFLAR');
$_cp_is_academy = (bool)$_cp_franchise['is_academy'];

// -------------------- Current coach --------------------
$_cp_sql = "SELECT coach_name FROM v_current_coach WHERE franchise_id = :fid";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_current_coach = $_cp_stmt->fetchColumn();
$_cp_current_coach_display = $_cp_current_coach ?: '-';

// Separate query, not folded into v_current_coach -- that view only exposes coach_name, and
// redefining it to also carry id_user isn't a change to make lightly without knowing what else
// might depend on its current shape. This mirrors the same join v_current_coach itself uses
// (franchise_coach_tenures with end_week_id IS NULL), just also selecting coaches.id_user.
$_cp_sql = "SELECT c.id_user
            FROM franchise_coach_tenures fct
            JOIN coaches c ON c.coach_id = fct.coach_id
            WHERE fct.franchise_id = :fid AND fct.end_week_id IS NULL";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_current_coach_user_id = $_cp_stmt->fetchColumn();

$_cp_coach_since = coach_since_year($conn, $_cp_franchise_id);

// -------------------- Header --------------------
echo "<div class='w3-panel w3-theme'>";
echo "<h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>";
echo "<b>" . htmlspecialchars($_cp_franchise['league_code'] . ' ' . $_cp_franchise['label']
    . ($_cp_current_coach ? " (Coach: $_cp_current_coach_display)" : '')) . "</b></h1>";
echo "</div>";

// -------------------- Honors table --------------------
echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:480px'>";

$_cp_league_champ_label = $_cp_is_pro ? 'Superbowl Champions' : 'National Championships';
render_honor_row($conn, $_cp_franchise_id, 'LEAGUE_WINNER', $_cp_league_champ_label);

$_cp_conf_label = htmlspecialchars($_cp_franchise['conference']) . ' Championships';
render_honor_row($conn, $_cp_franchise_id, 'CONFERENCE_CHAMPION', $_cp_conf_label);

if ($_cp_is_pro) {
    $_cp_div_label = htmlspecialchars($_cp_franchise['division']) . ' Winners';
    render_honor_row($conn, $_cp_franchise_id, 'DIVISION_CHAMPION', $_cp_div_label);
    render_honor_row($conn, $_cp_franchise_id, 'WILDCARD_BERTH', 'Wildcards');
} else {
    render_honor_row($conn, $_cp_franchise_id, 'PERFECT_SEASON', 'Perfect Seasons');
}

// Playoff appearances -- pro uses the same division+wildcard formula the old page used
// (division winners and wildcard berths together do comprehensively cover "made the
// playoffs" for pro); college computed directly from real playoff-round participation
// rather than the old NC+Rose win/loss approximation -- see file header.
if ($_cp_is_pro) {
    $_cp_div_years = get_honor_years($conn, $_cp_franchise_id, 'DIVISION_CHAMPION');
    $_cp_wc_years = get_honor_years($conn, $_cp_franchise_id, 'WILDCARD_BERTH');
    $_cp_playoffs = count($_cp_div_years) + count($_cp_wc_years);
    echo "<tr><th>Playoff Appearances:</th><td>$_cp_playoffs ("
       . count($_cp_div_years) . " Divisions and " . count($_cp_wc_years) . " Wildcards)</td></tr>";
} else {
    $_cp_playoffs = playoff_appearances_college($conn, $_cp_franchise_id);
    echo "<tr><th>National Championship Playoff Appearances:</th><td>$_cp_playoffs</td></tr>";
}

// coach.php's registered id_static_page -- was a 0 placeholder, now the real value.
define('COACH_PAGE_STATIC_ID', 12);

// Takes a zpbm_users.id_user value, sourced (below) via franchise_coach_tenures ->
// coaches.id_user for whoever currently holds this franchise's open tenure -- not from a
// franchises-level column, see migration_drop_coach_user_id.sql for why.
function build_coach_link($coach_user_id) {
    return htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . COACH_PAGE_STATIC_ID
        . '&user=' . urlencode($coach_user_id)
    );
}

$_cp_coach_name_html = htmlspecialchars($_cp_current_coach_display);
if ($_cp_current_coach_user_id) {
    $_cp_coach_name_html = "<a href='" . build_coach_link($_cp_current_coach_user_id)
        . "' style='color:inherit;text-decoration:underline'>$_cp_coach_name_html</a>";
}
echo "<tr><th>Coach:</th><td>" . $_cp_coach_name_html
   . ($_cp_coach_since ? " (Since $_cp_coach_since)" : '')
   . "</td></tr>";

$_cp_champ_record_label = $_cp_is_pro ? 'Superbowl record' : 'National Championship Game Record';
render_honor_record_row($conn, $_cp_franchise_id, 'LEAGUE_WINNER', 'LEAGUE_RUNNERUP', $_cp_champ_record_label);

if ($_cp_is_pro) {
    $_cp_conf_game_label = htmlspecialchars($_cp_franchise['conference']) . ' Championship Game Record';
    render_honor_record_row($conn, $_cp_franchise_id, 'CONFERENCE_CHAMPION', 'CONFERENCE_RUNNERUP', $_cp_conf_game_label);
} else {
    render_honor_record_row($conn, $_cp_franchise_id, 'ROSE_BOWL_WINNER', 'ROSE_BOWL_RUNNERUP', 'Rose Bowl record');
    render_honor_record_row($conn, $_cp_franchise_id, 'COTTON_BOWL_WINNER', 'COTTON_BOWL_RUNNERUP', 'Cotton Bowl record');
    render_honor_record_row($conn, $_cp_franchise_id, 'ORANGE_BOWL_WINNER', 'ORANGE_BOWL_RUNNERUP', 'Orange Bowl record');
    render_honor_record_row($conn, $_cp_franchise_id, 'HAWAII_BOWL_WINNER', 'HAWAII_BOWL_RUNNERUP', 'Hawaii Bowl record');
    render_honor_record_row($conn, $_cp_franchise_id, 'MUSIC_CITY_BOWL_WINNER', 'MUSIC_CITY_BOWL_RUNNERUP', 'Music City Bowl record');
}

render_rivalry_row($conn, $_cp_franchise_id);

if ($_cp_is_academy) {
    render_honor_row($conn, $_cp_franchise_id, 'CIC_WINNER', 'Commander in Chief Trophy');
}

render_season_extreme_row($conn, $_cp_franchise_id, 'wins', 'Most games won in a Season');
render_season_extreme_row($conn, $_cp_franchise_id, 'losses', 'Most games lost in a Season');
render_season_extreme_row($conn, $_cp_franchise_id, 'points_for', 'Most points scored in a Season', false);
render_season_extreme_row($conn, $_cp_franchise_id, 'points_against', 'Most points conceded in a Season', false);

echo "</table><br>";

// -------------------- Season selector --------------------
// Dropdown list: every season in franchise_season_records (career-long, includes
// legacy_rollup seasons -- schema.md section 9 -- which have a season-end record but no
// individual games rows behind them) PLUS whichever season currently holds this franchise's
// most recent games row, in case that season hasn't picked up a franchise_season_records row
// yet -- that table reads like a settled/aggregated one (built once per finished season), not
// necessarily kept live while a season is still being played, so don't assume the current
// season is already in it.
$_cp_sql = "SELECT fsr.season_id, s.label AS season_label, s.year
            FROM franchise_season_records fsr
            JOIN seasons s ON s.season_id = fsr.season_id
            WHERE fsr.franchise_id = :fid";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_season_options = [];  // season_id => ['label' => ..., 'year' => ...]
foreach ($_cp_stmt->fetchAll(PDO::FETCH_ASSOC) as $s) {
    $_cp_season_options[(int)$s['season_id']] = ['label' => $s['season_label'], 'year' => (int)$s['year']];
}

$_cp_sql = "SELECT MAX(w.season_id) FROM games g JOIN weeks w ON w.week_id = g.week_id
            WHERE g.home_franchise_id = :fid OR g.away_franchise_id = :fid";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_latest_games_season_id = (int)$_cp_stmt->fetchColumn();

if ($_cp_latest_games_season_id && !isset($_cp_season_options[$_cp_latest_games_season_id])) {
    $_cp_sql = "SELECT label, year FROM seasons WHERE season_id = :sid";
    $_cp_stmt = $conn->prepare($_cp_sql);
    $_cp_stmt->bindParam(':sid', $_cp_latest_games_season_id);
    $_cp_stmt->execute();
    if ($_cp_extra = $_cp_stmt->fetch(PDO::FETCH_ASSOC)) {
        $_cp_season_options[$_cp_latest_games_season_id] = ['label' => $_cp_extra['label'], 'year' => (int)$_cp_extra['year']];
    }
}

// Most-recent-first by year -- not by season_id, which isn't documented anywhere as being in
// year order, even though in practice it probably is.
uasort($_cp_season_options, fn($a, $b) => $b['year'] <=> $a['year']);

$_cp_season_id = isset($_GET['season']) ? (int)$_GET['season'] : 0;
if (!$_cp_season_id || !isset($_cp_season_options[$_cp_season_id])) {
    // Default unchanged from before this selector existed -- most recent season with a real
    // games row -- so existing bookmarks/links to this page keep behaving identically.
    $_cp_season_id = $_cp_latest_games_season_id ?: (array_key_first($_cp_season_options) ?: 0);
}

if (!empty($_cp_season_options)) {
    echo "<form method='get'>";
    echo "<input type='hidden' name='function' value='" . htmlspecialchars($_GET['function'] ?? 'show_static_page') . "'>";
    echo "<input type='hidden' name='id_static_page' value='" . htmlspecialchars($_GET['id_static_page'] ?? '') . "'>";
    echo "<input type='hidden' name='league' value='" . htmlspecialchars($_cp_league) . "'>";
    echo "<input type='hidden' name='franchise' value='" . htmlspecialchars($_cp_franchise_id) . "'>";
    echo "<select name='season' onchange='this.form.submit()' style='width:200px'>";
    foreach ($_cp_season_options as $_cp_sid => $_cp_info) {
        $sel = ($_cp_sid === $_cp_season_id) ? 'selected' : '';
        echo "<option value='$_cp_sid' $sel>" . htmlspecialchars($_cp_info['label']) . "</option>";
    }
    echo "</select>";
    echo "</form><br>";
}

// -------------------- Season results (for whichever season is selected above) --------------------
if ($_cp_season_id && isset($_cp_season_options[$_cp_season_id])) {
    $_cp_season_label = $_cp_season_options[$_cp_season_id]['label'];

    echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
       . "<b>" . htmlspecialchars($_cp_franchise['label'] . ' ' . $_cp_season_label . ' Results') . "</b></h1></div>";

    $_cp_sql = "SELECT g.game_id, w.week_number, g.home_franchise_id, g.away_franchise_id, g.home_score,
                       g.away_score, g.neutral_site,
                       hf.label AS home_label, af.label AS away_label,
                       htgs.coach_name AS home_coach, atgs.coach_name AS away_coach
                FROM games g
                JOIN weeks w ON w.week_id = g.week_id
                JOIN franchises hf ON hf.franchise_id = g.home_franchise_id
                JOIN franchises af ON af.franchise_id = g.away_franchise_id
                LEFT JOIN team_game_stats htgs ON htgs.game_id = g.game_id AND htgs.franchise_id = g.home_franchise_id
                LEFT JOIN team_game_stats atgs ON atgs.game_id = g.game_id AND atgs.franchise_id = g.away_franchise_id
                WHERE w.season_id = :sid AND (g.home_franchise_id = :fid1 OR g.away_franchise_id = :fid2)
                ORDER BY w.week_number DESC";
    $_cp_stmt = $conn->prepare($_cp_sql);
    $_cp_stmt->bindParam(':sid', $_cp_season_id);
    $_cp_stmt->bindParam(':fid1', $_cp_franchise_id);
    $_cp_stmt->bindParam(':fid2', $_cp_franchise_id);
    $_cp_stmt->execute();
    $_cp_games = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($_cp_games)) {
        // legacy_rollup seasons (schema.md section 9) have a franchise_season_records row but
        // no individual games rows behind them -- season-end totals only, no per-game detail
        // was ever migrated for these. Say so plainly instead of showing an empty table --
        // option (b): every season is selectable, this is the honest result for the ones with
        // nothing behind them, rather than hiding them from the dropdown entirely.
        echo "<p><em>No individual game log available for this season &mdash; only the "
           . "season-end record shown in Season by Season Records below.</em></p>";
    } else {
        echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:480px'>";
        echo "<tr><th>Week</th><th>Venue</th><th>Opponent</th><th>Score</th><th>Result</th></tr>";

        foreach ($_cp_games as $g) {
            // Explicit (int) cast on BOTH sides, not just the left -- $_cp_franchise_id was
            // properly cast to int back where it's first set, but bindParam() binds by
            // reference with a default type of PDO::PARAM_STR, and it's bound repeatedly to
            // several queries earlier in this file. Confirmed via direct runtime diagnostic
            // (not just reasoning about it) that this silently turns $_cp_franchise_id back
            // into a string by the time this comparison runs -- 'X' === X is false in PHP
            // regardless of the actual values, which is exactly why every game was showing as
            // a road game (the comparison never matched, home or not) and some weeks showed a
            // team as its own opponent (falling back to home_label on the specific weeks this
            // franchise actually was the home team).
            $_cp_is_home = ((int)$g['home_franchise_id'] === (int)$_cp_franchise_id);
            $venue = $g['neutral_site'] ? 'Neutral' : ($_cp_is_home ? 'Home' : 'Road');
            $my_score = $_cp_is_home ? $g['home_score'] : $g['away_score'];
            $opp_score = $_cp_is_home ? $g['away_score'] : $g['home_score'];
            $opp_label = $_cp_is_home ? $g['away_label'] : $g['home_label'];
            $opp_coach = $_cp_is_home ? $g['away_coach'] : $g['home_coach'];
            $result = ($my_score > $opp_score) ? 'Win' : (($my_score < $opp_score) ? 'Loss' : 'Tie');
            $opp_display = htmlspecialchars($opp_label) . ($opp_coach ? ' (' . htmlspecialchars($opp_coach) . ')' : '');
            // Score is now a link through to the new game detail page (game.php) -- see there
            // for the full box score, quarter-by-quarter, play-by-play and drive summary.
            $score_link = build_game_link($g['game_id']);
            $score_display = "<a href='$score_link' style='text-decoration:underline'>$my_score-$opp_score</a>";
            echo "<tr><td>{$g['week_number']}</td><td>$venue</td><td>$opp_display</td>"
               . "<td>$score_display</td><td>$result</td></tr>";
        }
        echo "</table><br>";
    }
}

// -------------------- Season by season --------------------
echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
   . "<b>Season by Season Records</b></h1></div>";

echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:55%;min-width:480px'>";
echo "<tr><th>Season</th><th>Coach</th><th>Record</th><th>Points</th></tr>";

$_cp_league_champ_years = get_honor_years($conn, $_cp_franchise_id, 'LEAGUE_WINNER');
$_cp_conf_champ_years = get_honor_years($conn, $_cp_franchise_id, 'CONFERENCE_CHAMPION');

$_cp_sql = "SELECT fsr.season_id, s.label AS season_label, s.year, fsr.wins, fsr.losses, fsr.ties,
                   fsr.points_for, fsr.points_against
            FROM franchise_season_records fsr
            JOIN seasons s ON s.season_id = fsr.season_id
            WHERE fsr.franchise_id = :fid
            ORDER BY s.year DESC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':fid', $_cp_franchise_id);
$_cp_stmt->execute();
$_cp_seasons = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($_cp_seasons as $s) {
    $year = (int)$s['year'];
    $is_league_champ = in_array($year, $_cp_league_champ_years);
    $is_conf_champ = in_array($year, $_cp_conf_champ_years);
    $season_coach = coach_for_season($conn, $_cp_franchise_id, $s['season_id']);

    $season_text = $s['season_label'] . ($is_league_champ ? ' <strong>*</strong>' : '');
    $coach_text = htmlspecialchars($season_coach ?: '-');
    if ($is_conf_champ) {
        $season_text = "<em>$season_text</em>";
        $coach_text = "<em>$coach_text</em>";
    }

    $record_text = "{$s['wins']}-{$s['losses']}";
    if ($s['ties'] == 1) {
        $record_text .= " (1 tie)";
    } elseif ($s['ties'] > 1) {
        $record_text .= " ({$s['ties']} ties)";
    }
    if ($is_conf_champ) {
        $record_text = "<em>$record_text</em>";
    }

    $points_text = number_format($s['points_for']) . '-' . number_format($s['points_against']);
    if ($is_conf_champ) {
        $points_text = "<em>$points_text</em>";
    }

    echo "<tr><td>$season_text</td><td>$coach_text</td><td>$record_text</td><td>$points_text</td></tr>";
}

echo "</table>";
$_cp_champ_word = $_cp_is_pro ? 'Superbowl' : 'National Championship';
echo "<p><strong>*</strong> indicates won $_cp_champ_word, <em>italics</em> indicate won Conference.</p>";
echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// Years this franchise won a given honor, e.g. get_honor_years($conn, 2015, 'LEAGUE_WINNER').
function get_honor_years($conn, $franchise_id, $code) {
    $sql = "SELECT s.year FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN seasons s ON s.season_id = fh.season_id
            WHERE fh.franchise_id = :fid AND ht.code = :code
            ORDER BY s.year";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

// "Label: (count)" / "year year year" row, e.g. "Superbowl Champions: (3)" / "1998 2005 2019"
function render_honor_row($conn, $franchise_id, $code, $label) {
    $years = get_honor_years($conn, $franchise_id, $code);
    $years_display = empty($years) ? '-' : implode(' ', $years);
    echo "<tr><th>$label: (" . count($years) . ")</th><td>$years_display</td></tr>";
}

// Win-loss record between a winner honor and its matching runner-up honor,
// e.g. "Superbowl record" -> "3 - 1"
function render_honor_record_row($conn, $franchise_id, $winner_code, $runnerup_code, $label) {
    $wins = count(get_honor_years($conn, $franchise_id, $winner_code));
    $losses = count(get_honor_years($conn, $franchise_id, $runnerup_code));
    echo "<tr><th>$label:</th><td>$wins - $losses</td></tr>";
}

// Coach whose tenure was open at the start of the CURRENT (open-ended) tenure -- i.e. the
// year the present coach took over. Not the same question as "first year this person ever
// coached the team" if they left and came back; franchise_coach_tenures already tracks each
// separate stint, so this correctly reflects only the current one.
function coach_since_year($conn, $franchise_id) {
    $sql = "SELECT s.year FROM franchise_coach_tenures fct
            JOIN weeks w ON w.week_id = fct.start_week_id
            JOIN seasons s ON s.season_id = w.season_id
            WHERE fct.franchise_id = :fid AND fct.end_week_id IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->execute();
    return $stmt->fetchColumn() ?: null;
}

// Which coach was in charge for a given season -- uses the tenure covering that season's
// LAST week, so a mid-season coaching change resolves to whoever finished the season, a
// consistent and defensible choice rather than an arbitrary one.
function coach_for_season($conn, $franchise_id, $season_id) {
    $sql = "SELECT c.name FROM franchise_coach_tenures fct
            JOIN coaches c ON c.coach_id = fct.coach_id
            JOIN weeks w ON w.week_id = (
                SELECT MAX(week_id) FROM weeks WHERE season_id = :sid1
            )
            WHERE fct.franchise_id = :fid
              AND fct.start_week_id <= w.week_id
              AND (fct.end_week_id IS NULL OR fct.end_week_id >= w.week_id)
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid1', $season_id);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->execute();
    return $stmt->fetchColumn() ?: null;
}

// "Most wins/losses/points in a season" -- the max value across franchise_season_records,
// with "(xN)" appended if that value was reached in more than one season. $higher_is_better
// only affects nothing here (always MAX) but kept for clarity at call sites -- for/against
// columns don't need a MIN version on this page, only the maximum ever recorded.
function render_season_extreme_row($conn, $franchise_id, $column, $label, $unused = true) {
    $allowed = ['wins', 'losses', 'points_for', 'points_against'];
    if (!in_array($column, $allowed, true)) {
        return;
    }
    $sql = "SELECT MAX($column) FROM franchise_season_records WHERE franchise_id = :fid";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->execute();
    $max_value = $stmt->fetchColumn();

    if ($max_value === false || $max_value === null) {
        echo "<tr><th>$label:</th><td>-</td></tr>";
        return;
    }

    $sql = "SELECT COUNT(*) FROM franchise_season_records WHERE franchise_id = :fid AND $column = :val";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->bindParam(':val', $max_value);
    $stmt->execute();
    $occurrences = (int)$stmt->fetchColumn();

    $suffix = ($occurrences > 1) ? " (x$occurrences)" : '';
    echo "<tr><th>$label:</th><td>" . number_format($max_value) . "$suffix</td></tr>";
}

// Head-to-head rivalry record, if this franchise has one -- uses v_rivalry_records (built
// earlier this session) rather than re-deriving it here. Only prints a row if a rivalry
// actually exists for this franchise; most franchises won't have one.
function render_rivalry_row($conn, $franchise_id) {
    $sql = "SELECT r.name, vr.franchise_a_id, vr.franchise_b_id, vr.franchise_a_wins, vr.franchise_b_wins,
                   fa.label AS a_label, fb.label AS b_label
            FROM v_rivalry_records vr
            JOIN rivalries r ON r.rivalry_id = vr.rivalry_id
            JOIN franchises fa ON fa.franchise_id = vr.franchise_a_id
            JOIN franchises fb ON fb.franchise_id = vr.franchise_b_id
            WHERE vr.franchise_a_id = :fid1 OR vr.franchise_b_id = :fid2";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid1', $franchise_id);
    $stmt->bindParam(':fid2', $franchise_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return;
    }

    $is_a = ((int)$row['franchise_a_id'] === $franchise_id);
    $my_wins = $is_a ? $row['franchise_a_wins'] : $row['franchise_b_wins'];
    $their_wins = $is_a ? $row['franchise_b_wins'] : $row['franchise_a_wins'];
    $opponent = $is_a ? $row['b_label'] : $row['a_label'];

    echo "<tr><th>Rivalry:</th><td><em>" . htmlspecialchars($row['name']) . "</em> against "
       . htmlspecialchars($opponent) . " : Record: $my_wins - $their_wins</td></tr>";
}

// College-only: real playoff-round participation (National Championship Game/Semi Finals,
// Cotton/Hawaii Bowl Playoffs -- game_type_id 8/14/15/16), rather than the old page's
// NC wins+losses + Rose wins+losses approximation. See file header for why.
function playoff_appearances_college($conn, $franchise_id) {
    $sql = "SELECT COUNT(DISTINCT g.game_id) FROM team_game_stats tgs
            JOIN games g ON g.game_id = tgs.game_id
            WHERE tgs.franchise_id = :fid AND g.game_type_id IN (8, 14, 15, 16)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fid', $franchise_id);
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}
?>
