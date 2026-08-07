<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
// __DIR__-relative include, not a bare relative path -- see current_standings.php/team.php for
// why. Plain PDO throughout, same as every other custom page in this app -- no g_functions.php.
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Game Detail -- single-game page: final score, quarter-by-quarter line,
// full two-team box score, and (when the data exists) play-by-play and/or
// drive summary for this specific game.
//
// This is exactly the "games -> team_game_stats, games -> plays and
// games -> drives are all master/detail relationships" page called for in
// schema.md section 7 -- "a game's detail page can show both teams' box
// scores plus whichever of the play log or drive summary is available for
// that particular game." Nothing here is a new design decision; this page
// is the front-end for a relationship the schema was already built around.
//
// Reached via `?game={game_id}` -- from team.php's results table (the score
// is now a link, see there) or any future page that wants to point at a
// specific game. No game picker/browser on this page itself -- out of
// scope for this pass (team.php's own results table is the only entry
// point today); a standalone game-finder would be a separate feature.
//
// plays/drives are BOTH expected to be sparse for the large majority of
// games -- per schema.md section 1, play-by-play (either the coach's own
// per-play log or a scouting opponent's per-drive summary) is only ever
// captured for two games per turn, never league-wide. An empty section
// here is the normal case, not a sign anything's broken -- messaged
// accordingly rather than left looking like a silent failure.
//
// Play by Play / Drive Summary are Administrator-only -- explicit requirement, not shown to
// the public or any other group. See $_cp_is_admin below (DaDaBIK's own
// $current_user_is_administrator global, not a hardcoded id_group check); both the data AND
// the section headers are skipped entirely for non-admins.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";

// team.php's registered id_static_page -- was a 0 placeholder, now the real value.
define('TEAM_PAGE_STATIC_ID', 4);

function build_team_link($league_code, $franchise_id) {
    return htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . TEAM_PAGE_STATIC_ID
        . '&league=' . urlencode($league_code) . '&franchise=' . urlencode($franchise_id)
    );
}

$_cp_game_id = isset($_GET['game']) ? (int)$_GET['game'] : 0;

// Play-by-play and drive summary are Administrator-only -- not shown to the public or any
// other group. Uses DaDaBIK's own $current_user_is_administrator global (confirmed via
// DaDaBIK's documented custom-code globals) rather than reading id_group out of the session
// directly -- no hardcoded group ID at all now, so this keeps working correctly even if the
// Administrator group's id_group value ever changes. Previously:
//   (($_SESSION['logged_user_infos_ar']['id_group'] ?? null) == 1)
// -- switched over during local testing, before this matters in production.
$_cp_is_admin = ($current_user_is_administrator == 1);

if (!$_cp_game_id) {
    echo "<h1>Game Detail</h1>";
    echo "<p><em>No game selected. Follow a score link from a Team Summary page to view a game.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Core game record --------------------
$_cp_sql = "SELECT g.game_id, g.label, g.home_score, g.away_score, g.went_to_ot, g.neutral_site,
                   gt.name AS game_type_name, gt.phase,
                   w.week_id, w.week_number, w.label AS week_label,
                   s.season_id, s.year AS season_year, s.label AS season_label,
                   l.code AS league_code, l.name AS league_name,
                   hf.franchise_id AS home_franchise_id, hf.label AS home_label,
                   af.franchise_id AS away_franchise_id, af.label AS away_label
            FROM games g
            JOIN weeks w ON w.week_id = g.week_id
            JOIN seasons s ON s.season_id = w.season_id
            JOIN leagues l ON l.league_id = s.league_id
            JOIN game_types gt ON gt.game_type_id = g.game_type_id
            JOIN franchises hf ON hf.franchise_id = g.home_franchise_id
            JOIN franchises af ON af.franchise_id = g.away_franchise_id
            WHERE g.game_id = :gid";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':gid', $_cp_game_id);
$_cp_stmt->execute();
$_cp_game = $_cp_stmt->fetch(PDO::FETCH_ASSOC);

if (!$_cp_game) {
    echo "<h1>Game Detail</h1>";
    echo "<p><em>Game not found.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Per-team box score (0, 1, or 2 rows) --------------------
$_cp_sql = "SELECT tgs.* FROM team_game_stats tgs
            WHERE tgs.game_id = :gid ORDER BY tgs.is_home DESC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':gid', $_cp_game_id);
$_cp_stmt->execute();
$_cp_stats_rows = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

$_cp_home_stats = null;
$_cp_away_stats = null;
foreach ($_cp_stats_rows as $row) {
    if ((int)$row['franchise_id'] === (int)$_cp_game['home_franchise_id']) {
        $_cp_home_stats = $row;
    } elseif ((int)$row['franchise_id'] === (int)$_cp_game['away_franchise_id']) {
        $_cp_away_stats = $row;
    }
}

// -------------------- Header --------------------
$_cp_home_label = htmlspecialchars($_cp_game['home_label']);
$_cp_away_label = htmlspecialchars($_cp_game['away_label']);
$_cp_title = $_cp_game['label'] ? htmlspecialchars($_cp_game['label'])
    : "{$_cp_game['league_code']} {$_cp_game['season_year']} Wk {$_cp_game['week_number']}: $_cp_home_label vs $_cp_away_label";

echo "<div class='w3-panel w3-theme'>";
echo "<h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'><b>$_cp_title</b></h1>";

$_cp_subtitle_parts = [];
if ($_cp_game['game_type_name'] && $_cp_game['phase'] !== 'regular') {
    $_cp_subtitle_parts[] = "<strong>" . htmlspecialchars($_cp_game['game_type_name']) . "</strong>";
}
$_cp_subtitle_parts[] = htmlspecialchars($_cp_game['season_label'] ?: ($_cp_game['league_code'] . ' ' . $_cp_game['season_year']));
$_cp_subtitle_parts[] = htmlspecialchars($_cp_game['week_label'] ?: ('Week ' . $_cp_game['week_number']));
if ($_cp_game['neutral_site']) {
    $_cp_subtitle_parts[] = "Neutral Site";
}
if ($_cp_game['went_to_ot']) {
    $_cp_subtitle_parts[] = "Overtime";
}
echo "<p class='w3-text-white'>" . implode(' &middot; ', $_cp_subtitle_parts) . "</p>";
echo "</div>";

// -------------------- Final score line, with quarter-by-quarter --------------------
echo score_line_table($_cp_game, $_cp_home_stats, $_cp_away_stats);

// -------------------- Box score --------------------
echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
   . "<b>Box Score</b></h1></div>";

if (!$_cp_home_stats && !$_cp_away_stats) {
    echo "<p><em>Box score not yet available for this game.</em></p>";
} else {
    echo box_score_table($_cp_game, $_cp_home_stats, $_cp_away_stats);
}

// -------------------- Drive summary (opponent-scouting only, sparse by design -- see file header) --------------------
// Administrator-only -- see $_cp_is_admin above. Not just the data hidden: the section header
// itself is skipped too for non-admins, rather than showing an empty/"admins only" placeholder,
// so the page gives no signal to the public that this data exists at all.
// Shown before Play by Play -- drives are per-drive summaries (a handful of rows), plays are
// per-snap (often 100+ rows), so the shorter section comes first.
if ($_cp_is_admin) {
    echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
       . "<b>Drive Summary</b></h1></div>";
    render_drives_section($conn, $_cp_game_id, $_cp_game);
}

// -------------------- Play-by-play (own-game only, sparse by design -- see file header) --------------------
// Administrator-only -- same reasoning as Drive Summary above.
if ($_cp_is_admin) {
    echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
       . "<b>Play by Play</b></h1></div>";
    render_plays_section($conn, $_cp_game_id, $_cp_game);
}

echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// "S" / "UP" markers next to a team's name -- same convention the League Report itself prints
// (e.g. "Minnesota Vikings (Gordon Milnes) S  7 3 0 10 6 (26 OT)"), reused here rather than
// invented -- these are markers the coaches already read every week.
function team_badges($stats) {
    if (!$stats) {
        return '';
    }
    $badges = '';
    if ((int)$stats['safeties_conceded'] > 0) {
        $badges .= " <span class='w3-tag w3-red w3-round' style='font-size:0.7em;padding:2px 6px'>S</span>";
    }
    if ((int)$stats['played_up']) {
        $badges .= " <span class='w3-tag w3-blue w3-round' style='font-size:0.7em;padding:2px 6px'>UP</span>";
    }
    if ($stats && (int)$stats['starting_qb_benched']) {
        $badges .= " <span class='w3-tag w3-grey w3-round' style='font-size:0.7em;padding:2px 6px'>QB Benched</span>";
    }
    return $badges;
}

// Team name as a link back to team.php, with the (Home)/(Road) tag and badges.
function team_heading($league_code, $franchise_id, $label_html, $venue_tag, $stats) {
    $link = build_team_link($league_code, $franchise_id);
    return "<a href='$link' style='color:inherit;text-decoration:underline'>$label_html</a>"
         . " <span style='font-weight:normal;font-size:0.75em'>($venue_tag)</span>"
         . team_badges($stats);
}

// Score line: team names (linked) + quarter-by-quarter + final. OT column only shown if either
// side has a non-NULL `ot` value -- most games never go there, so the column is omitted rather
// than shown empty every single time.
function score_line_table($game, $home_stats, $away_stats) {
    $show_ot = ($home_stats && $home_stats['ot'] !== null) || ($away_stats && $away_stats['ot'] !== null);

    $out = "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' "
         . "style='width:60%;min-width:520px;table-layout:fixed'>";
    $out .= "<tr><th>Team</th><th class='w3-center'>Q1</th><th class='w3-center'>Q2</th>"
          . "<th class='w3-center'>Q3</th><th class='w3-center'>Q4</th>"
          . ($show_ot ? "<th class='w3-center'>OT</th>" : "")
          . "<th class='w3-center'>Final</th></tr>";

    $rows = [
        ['label' => $game['home_label'], 'league' => $game['league_code'], 'fid' => $game['home_franchise_id'],
         'venue' => $game['neutral_site'] ? 'Neutral' : 'Home', 'stats' => $home_stats, 'score' => $game['home_score']],
        ['label' => $game['away_label'], 'league' => $game['league_code'], 'fid' => $game['away_franchise_id'],
         'venue' => $game['neutral_site'] ? 'Neutral' : 'Road', 'stats' => $away_stats, 'score' => $game['away_score']],
    ];

    foreach ($rows as $r) {
        $s = $r['stats'];
        $name_html = team_heading($r['league'], $r['fid'], htmlspecialchars($r['label']), $r['venue'], $s);
        $out .= "<tr><td>$name_html</td>";
        foreach (['q1', 'q2', 'q3', 'q4'] as $q) {
            $out .= "<td class='w3-center'>" . ($s && $s[$q] !== null ? (int)$s[$q] : '-') . "</td>";
        }
        if ($show_ot) {
            $out .= "<td class='w3-center'>" . ($s && $s['ot'] !== null ? (int)$s['ot'] : '-') . "</td>";
        }
        $final = $r['score'] !== null ? (int)$r['score'] : '-';
        $out .= "<td class='w3-center'><strong>$final</strong></td></tr>";
    }
    $out .= "</table><br>";
    return $out;
}

// "x/x" for made/attempted-style pairs -- '-' when nothing was recorded at all, rather than
// printing "0/0" for a category the parser simply never populated (NULL, not zero).
function frac($made, $att) {
    if ($made === null && $att === null) {
        return '-';
    }
    return (int)$made . '/' . (int)$att;
}

// Long-play yardage with the same 't' prefix the League Report itself uses for a long play that
// went for a touchdown (e.g. "Lg t59") -- reused convention, not a new one, see team_game_stats
// .pass_long_is_td / .rush_long_is_td.
function long_yds($yds, $is_td) {
    if ($yds === null) {
        return '-';
    }
    return ($is_td ? 't' : '') . (int)$yds;
}

function nz($val) {
    return $val === null ? '-' : (int)$val;
}

// Full two-team box score, laid out as one stat category per row, matching the League Report's
// own field groupings (FG/EP/CP/Punt/downs, Passing, Rushing, Returns, Calls) and column
// naming -- see team_game_stats in schema.md/new_schema.sql, this table doesn't introduce any
// naming of its own.
function box_score_table($game, $home, $away) {
    $out = "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' "
         . "style='width:75%;min-width:600px;table-layout:fixed'>";
    $out .= "<colgroup><col style='width:22%'><col style='width:39%'><col style='width:39%'></colgroup>";
    $out .= "<tr><th>&nbsp;</th><th>" . htmlspecialchars($game['home_label']) . "</th>"
          . "<th>" . htmlspecialchars($game['away_label']) . "</th></tr>";

    $out .= box_row('Coach', $home['coach_name'] ?? null, $away['coach_name'] ?? null, true);

    $out .= box_row('FG', $home ? frac($home['fg_made'], $home['fg_att']) : null, $away ? frac($away['fg_made'], $away['fg_att']) : null);
    $out .= box_row('EP', $home ? frac($home['ep_made'], $home['ep_att']) : null, $away ? frac($away['ep_made'], $away['ep_att']) : null);
    $out .= box_row('2pt Conv (CP)', $home ? frac($home['cp_made'], $home['cp_att']) : null, $away ? frac($away['cp_made'], $away['cp_att']) : null);
    $out .= box_row('Punts', $home ? nz($home['punts']) : null, $away ? nz($away['punts']) : null);
    $out .= box_row('3rd Down', $home ? frac($home['third_down_conv'], $home['third_down_att']) : null, $away ? frac($away['third_down_conv'], $away['third_down_att']) : null);
    $out .= box_row('4th Down', $home ? frac($home['fourth_down_conv'], $home['fourth_down_att']) : null, $away ? frac($away['fourth_down_conv'], $away['fourth_down_att']) : null);
    $out .= box_row('1st Downs', $home ? nz($home['first_downs']) : null, $away ? nz($away['first_downs']) : null);

    $out .= box_section_header('Passing');
    $out .= box_row('Comp/Att', $home ? frac($home['pass_comp'], $home['pass_att']) : null, $away ? frac($away['pass_comp'], $away['pass_att']) : null);
    $out .= box_row('Yards', $home ? nz($home['pass_yds']) : null, $away ? nz($away['pass_yds']) : null);
    $out .= box_row('Long', $home ? long_yds($home['pass_long'], $home['pass_long_is_td']) : null, $away ? long_yds($away['pass_long'], $away['pass_long_is_td']) : null);
    $out .= box_row('TD', $home ? nz($home['pass_td']) : null, $away ? nz($away['pass_td']) : null);
    $out .= box_row('Pct', $home && $home['pass_pct'] !== null ? $home['pass_pct'] . '%' : null, $away && $away['pass_pct'] !== null ? $away['pass_pct'] . '%' : null);
    $out .= box_row('Interceptions Thrown', $home ? nz($home['interceptions_thrown']) : null, $away ? nz($away['interceptions_thrown']) : null);
    $out .= box_row('Hurried', $home ? nz($home['times_hurried']) : null, $away ? nz($away['times_hurried']) : null);
    $out .= box_row('Sacked', $home ? nz($home['times_sacked']) : null, $away ? nz($away['times_sacked']) : null);

    $out .= box_section_header('Rushing');
    $out .= box_row('Att', $home ? nz($home['rush_att']) : null, $away ? nz($away['rush_att']) : null);
    $out .= box_row('Yards', $home ? nz($home['rush_yds']) : null, $away ? nz($away['rush_yds']) : null);
    $out .= box_row('Long', $home ? long_yds($home['rush_long'], $home['rush_long_is_td']) : null, $away ? long_yds($away['rush_long'], $away['rush_long_is_td']) : null);
    $out .= box_row('TD', $home ? nz($home['rush_td']) : null, $away ? nz($away['rush_td']) : null);
    $out .= box_row('Fumbles', $home ? nz($home['fumbles']) : null, $away ? nz($away['fumbles']) : null);
    $out .= box_row('QB Rush', $home ? frac_yds($home['qb_rush_att'], $home['qb_rush_yds']) : null, $away ? frac_yds($away['qb_rush_att'], $away['qb_rush_yds']) : null);

    $out .= box_section_header('Returns');
    $out .= box_row('Kick Returns', $home ? ret_summary($home['kr_num'], $home['kr_yds'], $home['kr_td']) : null, $away ? ret_summary($away['kr_num'], $away['kr_yds'], $away['kr_td']) : null);
    $out .= box_row('Punt Returns', $home ? ret_summary($home['pr_num'], $home['pr_yds'], $home['pr_td']) : null, $away ? ret_summary($away['pr_num'], $away['pr_yds'], $away['pr_td']) : null);
    $out .= box_row('Other Return', $home ? other_return_summary($home) : null, $away ? other_return_summary($away) : null);

    $out .= box_section_header('Calls');
    $out .= box_row('Formation', $home ? calls_pair($home['call_fm1'], $home['call_fm2']) : null, $away ? calls_pair($away['call_fm1'], $away['call_fm2']) : null);
    $out .= box_row('Run', $home ? calls_pair($home['call_run1'], $home['call_run2']) : null, $away ? calls_pair($away['call_run1'], $away['call_run2']) : null);
    $out .= box_row('Pass', $home ? calls_pair($home['call_pass1'], $home['call_pass2']) : null, $away ? calls_pair($away['call_pass1'], $away['call_pass2']) : null);
    $out .= box_row('Defense', $home ? calls_pair($home['call_def1'], $home['call_def2']) : null, $away ? calls_pair($away['call_def1'], $away['call_def2']) : null);

    $out .= "</table><br>";
    return $out;
}

function box_section_header($label) {
    return "<tr class='w3-theme-l3'><th colspan='3' style='padding-top:8px'>" . htmlspecialchars($label) . "</th></tr>";
}

function box_row($label, $home_val, $away_val, $is_text = false) {
    $home_display = ($home_val === null) ? '-' : ($is_text ? htmlspecialchars($home_val) : $home_val);
    $away_display = ($away_val === null) ? '-' : ($is_text ? htmlspecialchars($away_val) : $away_val);
    return "<tr><th>" . htmlspecialchars($label) . "</th><td>$home_display</td><td>$away_display</td></tr>";
}

function frac_yds($att, $yds) {
    if ($att === null && $yds === null) {
        return null;
    }
    return (int)$att . ' for ' . (int)$yds . ' yds';
}

function ret_summary($num, $yds, $td) {
    if ($num === null && $yds === null) {
        return null;
    }
    $out = (int)$num . ' for ' . (int)$yds . ' yds';
    if ($td) {
        $out .= ', ' . (int)$td . ' TD';
    }
    return $out;
}

// ret_type/ret_num/ret_yds/ret_td cover whichever ONE "other return" stat line the League
// Report printed that game (FumR/IntR/DefR -- see team_game_stats.ret_type) -- confirmed in
// schema.md section 9 that only one such line ever appears per team per game.
function other_return_summary($stats) {
    if (!$stats['ret_type']) {
        return null;
    }
    $out = htmlspecialchars($stats['ret_type']) . ' ' . (int)$stats['ret_num'] . ' for ' . (int)$stats['ret_yds'] . ' yds';
    if ($stats['ret_td']) {
        $out .= ', ' . (int)$stats['ret_td'] . ' TD';
    }
    return $out;
}

function calls_pair($a, $b) {
    if (!$a && !$b) {
        return null;
    }
    return htmlspecialchars(trim("$a $b"));
}

// mm:ss from a "time gone" seconds value -- NOT modulo-60'd, since the cumulative clock
// genuinely runs past 60:00 in overtime (see lessons.md -- the clock continues rather than
// resetting), so a naive %60 would print a wrong, wrapped-around minute count for OT plays.
function format_clock($seconds) {
    if ($seconds === null) {
        return '-';
    }
    $seconds = (int)$seconds;
    $m = intdiv($seconds, 60);
    $s = $seconds % 60;
    return $m . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);
}

// Self-contained (no file-scope globals -- consistent with the rest of this codebase, which
// always passes state through function parameters rather than reading page-level variables).
function ordinal_suffix($n) {
    $map = [1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th'];
    return $map[(int)$n] ?? ((int)$n . 'th');
}

function format_down_distance($down, $yards_to_go) {
    if ($down === null) {
        return '-';
    }
    $dist_text = ($yards_to_go !== null) ? (int)$yards_to_go : '?';
    return ordinal_suffix($down) . " &amp; $dist_text";
}

function quarter_label($quarter) {
    if ((int)$quarter === 5) {
        return 'Overtime';
    }
    return ordinal_suffix($quarter) . ' Quarter';
}

// Structured flags for the boolean columns on `plays` (touchdown/turnover/penalty/sack/
// safety) -- pattern-level only, not a re-statement of result_text, which is shown verbatim
// alongside these in both views. Kept as data (not HTML) so the table and the JS playback
// widget can render identical badges from one definition instead of two.
function play_flags($play) {
    $flag_map = [
        'is_touchdown' => ['TD', 'w3-green'],
        'is_turnover' => ['TO', 'w3-red'],
        'is_penalty' => ['PEN', 'w3-orange'],
        'is_sack' => ['SK', 'w3-grey'],
        'is_safety' => ['SFTY', 'w3-red'],
    ];
    $out = [];
    foreach ($flag_map as $col => $info) {
        if (!empty($play[$col])) {
            $out[] = ['text' => $info[0], 'color' => $info[1]];
        }
    }
    return $out;
}

function play_flag_badges($play) {
    $badges = '';
    foreach (play_flags($play) as $flag) {
        $badges .= " <span class='w3-tag {$flag['color']} w3-round' style='font-size:0.65em;padding:1px 5px'>{$flag['text']}</span>";
    }
    return $badges;
}

// Plain-text down & distance ("1st & 10") for the JS playback widget -- format_down_distance()
// (below) wraps this with an HTML entity for the table; JS only ever sets it via textContent,
// where a literal '&' is correct and an entity would show up wrong.
function format_down_distance_plain($down, $yards_to_go) {
    if ($down === null) {
        return '-';
    }
    $dist_text = ($yards_to_go !== null) ? (int)$yards_to_go : '?';
    return ordinal_suffix($down) . ' & ' . $dist_text;
}

// Play-by-play, grouped by quarter (1-4, 5 = Overtime -- see quarter_label()). Only ever
// populated from the uploading coach's OWN game (schema.md section 1) -- present for at most
// two franchises' turns per game, so an empty result here is the normal case for the large
// majority of games, not a parsing gap.
//
// Two ways to view it once data exists, and -- deliberately -- NEITHER is shown until the
// coach picks one. Defaulting to the table would spoil every result before Playback mode even
// got a chance to hide anything; the whole point of Playback is nothing past the play call is
// visible until asked for, so the choice has to come before any play data renders, not just
// control what happens after Playback is already selected.
function render_plays_section($conn, $game_id, $game) {
    $sql = "SELECT p.*, f.label AS offense_label
            FROM plays p
            LEFT JOIN franchises f ON f.franchise_id = p.offense_franchise_id
            WHERE p.game_id = :gid
            ORDER BY p.play_order ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':gid', $game_id);
    $stmt->execute();
    $plays = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($plays)) {
        echo "<p><em>No play-by-play recorded for this game. This is only captured from the "
           . "uploading coach's own game each turn, so most games in the archive won't have "
           . "it.</em></p>";
        return;
    }

    // One pass to build a running score (score_after is nullable and only carries a new value
    // on the rows that actually printed one -- schema.md/new_schema.sql: "'25-28 PS' as
    // printed" -- so an unpopulated row means "unchanged", not "0-0"). Reused for the Score
    // column in the table AND as score_before/score_after in the JS payload, rather than
    // computing it twice.
    $running_score = null;
    $pbp_data = [];
    $score_column = [];
    foreach ($plays as $p) {
        $score_before = $running_score;
        if ($p['score_after'] !== null && $p['score_after'] !== '') {
            $running_score = $p['score_after'];
        }
        $score_column[] = $running_score ?: '-';
        $pbp_data[] = [
            'quarter' => (int)$p['quarter'],
            'quarter_label' => quarter_label($p['quarter']),
            'time' => format_clock($p['time_gone_seconds']),
            'offense' => $p['offense_label'] ?: '-',
            'ball_on' => $p['field_position'] !== null ? trim(($p['field_side'] ?? '') . ' ' . $p['field_position']) : '-',
            'down_dist' => format_down_distance_plain($p['down'], $p['yards_to_go']),
            'formation' => $p['formation'] ?: '-',
            'off_call' => $p['off_call'] ?: '-',
            'def_call' => $p['def_call'] ?: '-',
            'score_before' => $score_before ?: '-',
            'result' => $p['result_text'],
            'yards' => $p['yards_gained'] !== null ? (int)$p['yards_gained'] : null,
            'score_after' => $running_score ?: '-',
            'flags' => play_flags($p),
        ];
    }

    // -------------------- Mode choice --------------------
    echo "<div id='pbp-mode-choice'>";
    echo "<button type='button' class='w3-button w3-theme' onclick='pbpShowTable()'>View as Table</button> ";
    echo "<button type='button' class='w3-button w3-theme-d3' onclick='pbpShowPlayback()'>Play by Play (Live Replay)</button>";
    echo "</div><br>";

    // -------------------- Table view (existing table, now hidden until chosen) --------------------
    echo "<div id='pbp-table-view' style='display:none'>";
    $current_quarter = null;
    foreach ($plays as $i => $p) {
        if ($p['quarter'] !== $current_quarter) {
            if ($current_quarter !== null) {
                echo "</table>";
            }
            $current_quarter = $p['quarter'];
            echo "<h3 style='margin-top:16px'>" . htmlspecialchars(quarter_label($current_quarter)) . "</h3>";
            echo plays_table_header();
        }
        echo plays_table_row($p, $score_column[$i]);
    }
    echo "</table>";
    echo "<p><button type='button' class='w3-button w3-theme-l1' onclick='pbpBackToChoice()'>&larr; Back</button></p>";
    echo "</div>";

    // -------------------- Playback view (built client-side from the JSON payload below) --------------------
    echo "<div id='pbp-playback-view' style='display:none'>";
    echo playback_widget_skeleton();
    echo "</div>";

    // Data for the widget only -- JSON, never executed. HEX_TAG/AMP/APOS/QUOT so a result_text
    // containing something like '</script>' (however unlikely in practice) can't break out of
    // the tag.
    echo "<script type='application/json' id='pbp-data'>"
       . json_encode($pbp_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
       . "</script>";

    echo playback_widget_script();
}

function plays_table_header() {
    $out = "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' "
         . "style='width:95%;min-width:880px;table-layout:fixed'>";
    $out .= "<colgroup><col style='width:6%'><col style='width:14%'><col style='width:9%'>"
          . "<col style='width:8%'><col style='width:6%'><col style='width:6%'><col style='width:6%'>"
          . "<col style='width:25%'><col style='width:7%'><col style='width:13%'></colgroup>";
    $out .= "<tr><th>Time</th><th>Offense</th><th>Ball On</th><th>Down &amp; Dist</th>"
          . "<th>Fmtn</th><th>Off</th><th>Def</th><th>Result</th><th class='w3-right-align'>Yds</th>"
          . "<th>Score</th></tr>";
    return $out;
}

function plays_table_row($p, $score_after_display) {
    $offense = $p['offense_label'] ? htmlspecialchars($p['offense_label']) : '-';
    $ball_on = $p['field_position'] !== null
        ? htmlspecialchars(trim(($p['field_side'] ?? '') . ' ' . $p['field_position']))
        : '-';
    $result = htmlspecialchars($p['result_text']) . play_flag_badges($p);
    if ($p['is_touchdown']) {
        $result = "<strong>$result</strong>";
    }
    $yds = $p['yards_gained'] !== null ? (int)$p['yards_gained'] : '-';
    return "<tr>"
         . "<td>" . format_clock($p['time_gone_seconds']) . "</td>"
         . "<td>$offense</td>"
         . "<td>$ball_on</td>"
         . "<td>" . format_down_distance($p['down'], $p['yards_to_go']) . "</td>"
         . "<td>" . htmlspecialchars($p['formation'] ?? '-') . "</td>"
         . "<td>" . htmlspecialchars($p['off_call'] ?? '-') . "</td>"
         . "<td>" . htmlspecialchars($p['def_call'] ?? '-') . "</td>"
         . "<td>$result</td>"
         . "<td class='w3-right-align'>$yds</td>"
         . "<td>" . htmlspecialchars($score_after_display) . "</td>"
         . "</tr>";
}

// Static shell for Playback mode -- every field is blank here and filled in by JS from the
// #pbp-data JSON payload (see playback_widget_script()) once a play is rendered. No content
// worth hiding lives in this markup itself, only in the JSON blob it reads from.
function playback_widget_skeleton() {
    $out = "<div class='w3-panel w3-theme-l4 w3-text-black' style='padding:16px'>";
    $out .= "<div id='pbp-quarter-heading' style='font-weight:bold;font-size:1.15em;margin-bottom:4px'></div>";
    $out .= "<div id='pbp-play-counter' style='font-size:0.85em;color:#555;margin-bottom:12px'></div>";

    $out .= "<div style='display:flex;justify-content:space-between;flex-wrap:wrap;margin-bottom:12px'>"
          . "<div><strong>Score:</strong> <span id='pbp-score'></span></div>"
          . "<div><strong>Offense:</strong> <span id='pbp-offense'></span></div></div>";

    $out .= "<table class='w3-table w3-bordered w3-white w3-text-black' style='margin-bottom:14px'>"
          . "<tr><th>Time</th><td id='pbp-time'></td><th>Ball On</th><td id='pbp-ballon'></td></tr>"
          . "<tr><th>Down &amp; Dist</th><td id='pbp-downdist'></td><th>Formation</th><td id='pbp-formation'></td></tr>"
          . "<tr><th>Off Call</th><td id='pbp-offcall'></td><th>Def Call</th><td id='pbp-defcall'></td></tr>"
          . "</table>";

    $out .= "<div id='pbp-result-box' class='w3-white w3-text-black' "
          . "style='min-height:70px;padding:10px;border:1px dashed #999;margin-bottom:14px'></div>";

    $out .= "<div>"
          . "<button type='button' class='w3-button w3-theme-d1' id='pbp-prev-btn' onclick='pbpPrev()'>&larr; Previous</button> "
          . "<button type='button' class='w3-button w3-theme' id='pbp-main-btn' onclick='pbpMainAction()'>Reveal Result</button> "
          . "<button type='button' class='w3-button w3-theme-l1' onclick='pbpBackToChoice()'>Back</button>"
          . "</div>";

    $out .= "</div>";
    return $out;
}

// Plain vanilla JS, no framework/build step -- consistent with the rest of this app, and the
// first place any of it needs real client-side state rather than a GET-param page reload (see
// team.php's season selector for the pattern this deliberately does NOT use -- a reveal/
// advance interaction can't round-trip the server per click without losing the point of it).
// All state lives in this one IIFE's closure; nothing touches window except the handful of
// functions the onclick= attributes above call directly.
function playback_widget_script() {
    return <<<'JS'
<script>
(function () {
    var pbpData = JSON.parse(document.getElementById('pbp-data').textContent);
    var pbpIndex = 0;
    var pbpRevealed = false;

    function pbpEl(id) { return document.getElementById(id); }

    function pbpRenderPlay() {
        var p = pbpData[pbpIndex];
        pbpEl('pbp-quarter-heading').textContent = p.quarter_label;
        pbpEl('pbp-play-counter').textContent = 'Play ' + (pbpIndex + 1) + ' of ' + pbpData.length;
        pbpEl('pbp-score').textContent = p.score_before;
        pbpEl('pbp-offense').textContent = p.offense;
        pbpEl('pbp-time').textContent = p.time;
        pbpEl('pbp-ballon').textContent = p.ball_on;
        pbpEl('pbp-downdist').textContent = p.down_dist;
        pbpEl('pbp-formation').textContent = p.formation;
        pbpEl('pbp-offcall').textContent = p.off_call;
        pbpEl('pbp-defcall').textContent = p.def_call;

        var box = pbpEl('pbp-result-box');
        box.textContent = '';
        var em = document.createElement('em');
        em.textContent = 'Think about what happens next, then reveal the result.';
        box.appendChild(em);

        pbpEl('pbp-main-btn').textContent = 'Reveal Result';
        pbpEl('pbp-prev-btn').disabled = (pbpIndex === 0);
        pbpRevealed = false;
    }

    function pbpRevealPlay() {
        var p = pbpData[pbpIndex];
        var box = pbpEl('pbp-result-box');
        box.textContent = '';

        var resultLine = document.createElement('div');
        resultLine.textContent = p.result + (p.yards !== null ? ' (' + p.yards + ' yds)' : '');
        var isTd = p.flags.some(function (f) { return f.text === 'TD'; });
        if (isTd) {
            resultLine.style.fontWeight = 'bold';
        }
        box.appendChild(resultLine);

        if (p.flags.length) {
            var badgeRow = document.createElement('div');
            badgeRow.style.marginTop = '6px';
            p.flags.forEach(function (f) {
                var span = document.createElement('span');
                span.className = 'w3-tag ' + f.color + ' w3-round';
                span.style.fontSize = '0.7em';
                span.style.padding = '2px 6px';
                span.style.marginRight = '4px';
                span.textContent = f.text;
                badgeRow.appendChild(span);
            });
            box.appendChild(badgeRow);
        }

        var scoreLine = document.createElement('div');
        scoreLine.style.marginTop = '6px';
        scoreLine.textContent = 'Score: ' + p.score_after;
        box.appendChild(scoreLine);

        pbpEl('pbp-score').textContent = p.score_after;
        pbpEl('pbp-main-btn').textContent = (pbpIndex < pbpData.length - 1) ? 'Next Play \u2192' : 'End of Game';
        pbpRevealed = true;
    }

    window.pbpShowTable = function () {
        pbpEl('pbp-mode-choice').style.display = 'none';
        pbpEl('pbp-table-view').style.display = 'block';
    };

    window.pbpShowPlayback = function () {
        pbpEl('pbp-mode-choice').style.display = 'none';
        pbpEl('pbp-playback-view').style.display = 'block';
        pbpIndex = 0;
        pbpRenderPlay();
    };

    window.pbpBackToChoice = function () {
        pbpEl('pbp-table-view').style.display = 'none';
        pbpEl('pbp-playback-view').style.display = 'none';
        pbpEl('pbp-mode-choice').style.display = 'block';
    };

    window.pbpMainAction = function () {
        if (!pbpRevealed) {
            pbpRevealPlay();
        } else if (pbpIndex < pbpData.length - 1) {
            pbpIndex++;
            pbpRenderPlay();
        }
    };

    // Going back always shows the target play hidden again, even if it was revealed before --
    // kept simple deliberately for this first version (an explicit "have I seen this one
    // already" cache per play would be a small, easy follow-up if that turns out to feel wrong
    // in practice, rather than something worth guessing needed upfront).
    window.pbpPrev = function () {
        if (pbpIndex > 0) {
            pbpIndex--;
            pbpRenderPlay();
        }
    };
})();
</script>
JS;
}

// Drive summary, grouped by quarter the same way as plays. Only ever populated from a FUTURE
// opponent's "Scouting Report - Game Summary" block (schema.md section 1) -- i.e. this game was
// someone else's most recent result at the time they scouted it, not necessarily related to
// who's viewing this page today. Genuinely different grain from plays -- no down/distance at
// all, see drives in new_schema.sql.
function render_drives_section($conn, $game_id, $game) {
    $sql = "SELECT d.*, f.label AS offense_label
            FROM drives d
            LEFT JOIN franchises f ON f.franchise_id = d.offense_franchise_id
            WHERE d.game_id = :gid
            ORDER BY d.drive_order ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':gid', $game_id);
    $stmt->execute();
    $drives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($drives)) {
        echo "<p><em>No drive summary recorded for this game. This is only captured when a "
           . "future opponent's turn scouts this specific game, so most games in the archive "
           . "won't have it.</em></p>";
        return;
    }

    $current_quarter = null;
    foreach ($drives as $d) {
        if ($d['quarter'] !== $current_quarter) {
            if ($current_quarter !== null) {
                echo "</table>";
            }
            $current_quarter = $d['quarter'];
            echo "<h3 style='margin-top:16px'>" . htmlspecialchars(quarter_label($current_quarter)) . "</h3>";
            echo drives_table_header();
        }
        echo drives_table_row($d);
    }
    echo "</table><br>";
}

function drives_table_header() {
    $out = "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' "
         . "style='width:95%;min-width:820px;table-layout:fixed'>";
    $out .= "<tr><th>Start Time</th><th>Offense</th><th>Started At</th><th>Start After</th>"
          . "<th>Plays</th><th>Yards</th><th>Longest Play</th><th>Result</th></tr>";
    return $out;
}

function drives_table_row($d) {
    $offense = $d['offense_label'] ? htmlspecialchars($d['offense_label']) : '-';
    $start_at = $d['start_field_position'] !== null
        ? htmlspecialchars(trim(($d['start_field_side'] ?? '') . ' ' . $d['start_field_position']))
        : '-';
    $result = htmlspecialchars($d['result_text']);
    if ($d['is_touchdown']) {
        $result = "<strong>$result</strong>";
    } elseif ($d['is_score']) {
        $result = "<em>$result</em>";
    }
    return "<tr>"
         . "<td>" . format_clock($d['start_time_seconds']) . "</td>"
         . "<td>$offense</td>"
         . "<td>$start_at</td>"
         . "<td>" . htmlspecialchars($d['start_after_text'] ?? '-') . "</td>"
         . "<td>" . ($d['play_count'] !== null ? (int)$d['play_count'] : '-') . "</td>"
         . "<td>" . ($d['drive_yards'] !== null ? (int)$d['drive_yards'] : '-') . "</td>"
         . "<td>" . htmlspecialchars($d['longest_play_text'] ?? '-') . "</td>"
         . "<td>$result</td>"
         . "</tr>";
}
?>
