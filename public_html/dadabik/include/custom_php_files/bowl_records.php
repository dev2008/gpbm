<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Bowl Records -- Superbowl (pro) / College Bowls (National
// Championship, Rose, Cotton, Orange, Hawaii, Music City).
//
// Built from fc_bowlrecords.php + fc_bowlrecordsinclude.php +
// fc_recordsinclude.php + fc_recordsinclude2.php + fp_bowlrecords.php +
// fp_bowlrecordsinclude.php + fp_bowlrecordsinclude2.php (all uploaded
// for reference) -- one page rather than seven, since pro and college
// are structurally the same problem (who's won it how many times, by
// how much, record by conference) once you allow for pro having
// exactly one bowl (no selector needed) and college having six (needs
// a bowl-type selector, defaulting to "All Bowls" -- replacing the old
// system's separate fc_bowlrecords.php overview file).
//
// Everything is parameterized by an ARRAY of game_type_id values,
// rather than one query per bowl hardcoded to a specific gametype
// number the way the old fc_recordsinclude.php/2.php pair was (and
// then included six times with a different $_cp_gametype set each
// time). The same functions serve pro's single-element array, any one
// college bowl's single-element array, and "All Bowls"' six-element
// array without any special-casing.
//
// "Flattened perspective" pattern: games has one row per GAME (home/
// away), but most of these stats want one row per TEAM per game (a
// team's own score, their opponent's score, did they win) -- exactly
// what f_games gave for free with its two-rows-per-game structure.
// Rebuilt here via UNION ALL rather than duplicating this logic in
// every stat function -- see perspectives_sql().
//
// Real bug fixed, not carried over: the old fp_bowlrecordsinclude.php
// "Record by Conference" query only includes a conference in the
// summary `if ($row[1]>1)` -- a conference with exactly one Superbowl
// win gets silently dropped from its own totals. Fixed to show every
// conference's real total.
//
// The "Danny name" footnote (Cotton Bowl = "Silver Bowl", etc.) isn't
// in the schema anywhere -- it's league lore with no query behind it,
// so it's a small hardcoded lookup here, kept only for the single-bowl
// view (doesn't make sense for pro, which has no alternate name, or
// for "All Bowls", which isn't about one specific bowl).
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>Bowl Records</h1>";

function build_bowl_link($params) {
    $parsed = parse_url($_SERVER['REQUEST_URI']);
    parse_str($parsed['query'] ?? '', $existing);
    $merged = array_merge($existing, $params);
    return htmlspecialchars($parsed['path'] . '?' . http_build_query($merged));
}

// -------------------- League toggle --------------------
$_cp_league = (isset($_GET['league']) && $_GET['league'] === 'NCAA5') ? 'NCAA5' : 'NFLAR';
$_cp_leagues = ['NFLAR' => 'NFLAR (Pro)', 'NCAA5' => 'NCAA5 (College)'];
echo "<div class='w3-bar w3-light-grey w3-text-black' style='margin-bottom:16px'>";
foreach ($_cp_leagues as $code => $display_name) {
    $active = ($code === $_cp_league) ? 'w3-theme w3-text-white' : '';
    $link = build_bowl_link(['league' => $code, 'bowl' => null]);
    echo "<a href='$link' class='w3-bar-item w3-button $active'>$display_name</a>";
}
echo "</div>";

// -------------------- Bowl selector (college only -- pro has exactly one bowl) --------------------
// code => [game_type_id(s), display name, "Danny name" or null]
$_cp_college_bowls = [
    'all'   => [[8, 9, 10, 11, 12, 13], 'All Bowls', null],
    'nc'    => [[8],  'National Championship', 'Gold Bowl'],
    'rose'  => [[9],  'Rose Bowl', 'Consolation Gold'],
    'cotton'=> [[10], 'Cotton Bowl', 'Silver Bowl'],
    'orange'=> [[11], 'Orange Bowl', 'Consolation Silver'],
    'hawaii'=> [[12], 'Hawaii Bowl', 'Bronze Bowl'],
    'music' => [[13], 'Music City Bowl', 'Consolation Bronze'],
];

if ($_cp_league === 'NCAA5') {
    $_cp_bowl_code = $_GET['bowl'] ?? 'all';
    if (!isset($_cp_college_bowls[$_cp_bowl_code])) {
        $_cp_bowl_code = 'all';
    }
    echo "<div class='w3-bar w3-light-grey w3-text-black' style='margin-bottom:16px'>";
    foreach ($_cp_college_bowls as $code => $info) {
        $active = ($code === $_cp_bowl_code) ? 'w3-theme w3-text-white' : '';
        $link = build_bowl_link(['bowl' => $code]);
        echo "<a href='$link' class='w3-bar-item w3-button $active'>{$info[1]}</a>";
    }
    echo "</div>";
    [$_cp_game_type_ids, $_cp_bowl_name, $_cp_danny_name] = $_cp_college_bowls[$_cp_bowl_code];
    $_cp_is_all_bowls = ($_cp_bowl_code === 'all');
} else {
    $_cp_game_type_ids = [36];
    $_cp_bowl_name = 'Superbowl';
    $_cp_danny_name = null;
    $_cp_is_all_bowls = false;
}

$_cp_sql = "SELECT league_id FROM leagues WHERE code = :league";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':league', $_cp_league);
$_cp_stmt->execute();
$_cp_league_id = $_cp_stmt->fetchColumn();

echo "<div class='w3-panel w3-theme'><h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'>"
   . "<b>" . htmlspecialchars($_cp_bowl_name) . " Records</b></h1></div>";

if ($_cp_is_all_bowls) {
    render_all_bowls_summary($conn, $_cp_league_id, $_cp_college_bowls);
} else {
    render_win_breakdown($conn, $_cp_league_id, $_cp_game_type_ids, $_cp_bowl_name);
    render_conference_division_record($conn, $_cp_league_id, $_cp_game_type_ids, $_cp_league === 'NFLAR');
}

render_game_stat_table($conn, $_cp_game_type_ids, $_cp_is_all_bowls);

if ($_cp_danny_name) {
    echo "<h4><em>* " . htmlspecialchars($_cp_bowl_name) . " also known as " . htmlspecialchars($_cp_danny_name) . "</em></h4>";
}

echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// One row per TEAM per game (a team's own score, their opponent's, whether they won) --
// rebuilt via UNION ALL from games (home/away), since that's the shape most of these stats
// actually want, the same shape f_games gave for free with its two-rows-per-game structure.
// $game_type_ids is always internally-controlled (never raw user input), cast to int before
// interpolating, so this is safe despite not being a bound parameter -- column/list values
// like this can't be parameterized via bindParam anyway.
function perspectives_sql($game_type_ids) {
    $ids = implode(',', array_map('intval', $game_type_ids));
    return "(
        SELECT g.game_id, g.label, g.home_franchise_id AS franchise_id, g.away_franchise_id AS opponent_id,
               g.home_score AS score, g.away_score AS opp_score
        FROM games g WHERE g.game_type_id IN ($ids)
        UNION ALL
        SELECT g.game_id, g.label, g.away_franchise_id AS franchise_id, g.home_franchise_id AS opponent_id,
               g.away_score AS score, g.home_score AS opp_score
        FROM games g WHERE g.game_type_id IN ($ids)
    )";
}

// Multi-time winners / single-time winners / appeared-but-never-won / never-appeared-at-all.
function render_win_breakdown($conn, $league_id, $game_type_ids, $bowl_name) {
    $persp = perspectives_sql($game_type_ids);

    $sql = "SELECT f.franchise_id, f.label, COUNT(*) AS wins
            FROM $persp p
            JOIN franchises f ON f.franchise_id = p.franchise_id
            WHERE f.league_id = :league_id AND p.score > p.opp_score
            GROUP BY f.franchise_id, f.label
            ORDER BY wins DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $win_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $multi = []; $single = []; $winner_ids = [];
    foreach ($win_rows as $r) {
        $winner_ids[] = $r['franchise_id'];
        if ($r['wins'] > 1) {
            $multi[] = htmlspecialchars($r['label']) . " ({$r['wins']})";
        } else {
            $single[] = htmlspecialchars($r['label']);
        }
    }

    $sql = "SELECT DISTINCT f.franchise_id, f.label
            FROM $persp p
            JOIN franchises f ON f.franchise_id = p.franchise_id
            WHERE f.league_id = :league_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $appeared_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $appeared_ids = array_column($appeared_rows, 'franchise_id');

    $never_won = [];
    foreach ($appeared_rows as $r) {
        if (!in_array($r['franchise_id'], $winner_ids)) {
            $never_won[] = htmlspecialchars($r['label']);
        }
    }

    $sql = "SELECT franchise_id, label FROM franchises WHERE league_id = :league_id ORDER BY label";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $all_franchises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $never_appeared = [];
    foreach ($all_franchises as $r) {
        if (!in_array($r['franchise_id'], $appeared_ids)) {
            $never_appeared[] = htmlspecialchars($r['label']);
        }
    }

    $bn = htmlspecialchars($bowl_name);
    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:70%;min-width:520px'>";
    echo "<tr><th>Schools with multiple $bn wins (" . count($multi) . "):</th><td>" . implode(', ', $multi) . "</td></tr>";
    echo "<tr><th>Schools with one $bn win (" . count($single) . "):</th><td>" . implode(', ', $single) . "</td></tr>";
    echo "<tr><th>Schools that appeared but never won (" . count($never_won) . "):</th><td>" . implode(', ', $never_won) . "</td></tr>";
    echo "<tr><th>Schools that never appeared (" . count($never_appeared) . "):</th><td>" . implode(', ', $never_appeared) . "</td></tr>";
    echo "</table><br>";
}

// Total wins by conference (and division, pro only) -- every conference/division that's ever
// appeared is shown, unlike the old fp_bowlrecordsinclude.php, which silently dropped any
// conference with exactly one win (`if ($row[1]>1)`).
function render_conference_division_record($conn, $league_id, $game_type_ids, $show_division) {
    $persp = perspectives_sql($game_type_ids);

    $sql = "SELECT f.conference, SUM(CASE WHEN p.score > p.opp_score THEN 1 ELSE 0 END) AS wins
            FROM $persp p
            JOIN franchises f ON f.franchise_id = p.franchise_id
            WHERE f.league_id = :league_id
            GROUP BY f.conference ORDER BY wins DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $conf_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conf_parts = [];
    foreach ($conf_rows as $r) {
        $conf_parts[] = htmlspecialchars($r['conference']) . " {$r['wins']}";
    }

    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:70%;min-width:520px'>";
    echo "<tr><th>Record by Conference:</th><td>" . implode(', ', $conf_parts) . "</td></tr>";

    if ($show_division) {
        $sql = "SELECT f.division, SUM(CASE WHEN p.score > p.opp_score THEN 1 ELSE 0 END) AS wins
                FROM $persp p
                JOIN franchises f ON f.franchise_id = p.franchise_id
                WHERE f.league_id = :league_id
                GROUP BY f.division ORDER BY wins DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $div_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $div_parts = [];
        foreach ($div_rows as $r) {
            $div_parts[] = htmlspecialchars($r['division']) . " {$r['wins']}";
        }
        echo "<tr><th>Record by Division:</th><td>" . implode(', ', $div_parts) . "</td></tr>";
    }

    echo "</table><br>";
}

// The six game-level stat categories shared by every bowl view (single bowl or "All Bowls").
// Every SIGNED cast here is deliberate -- score/opp_score are SMALLINT UNSIGNED, and MySQL (in
// strict mode) errors outright on an unsigned-minus-unsigned result that would be negative,
// rather than wrapping -- the same issue that broke v_current_standings earlier this session.
function render_game_stat_table($conn, $game_type_ids, $show_bowl_name = false) {
    $persp = perspectives_sql($game_type_ids);

    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:70%;min-width:520px'>";

    render_stat_row($conn, $persp, 'Biggest margin of victory',
        "(CAST(p.score AS SIGNED) - CAST(p.opp_score AS SIGNED)) DESC", null, true, $show_bowl_name);
    render_stat_row($conn, $persp, 'Smallest margin of victory',
        "(CAST(p.score AS SIGNED) - CAST(p.opp_score AS SIGNED)) ASC", 'p.score > p.opp_score', true, $show_bowl_name);
    render_stat_row($conn, $persp, 'Most points scored',
        'p.score DESC', null, false, $show_bowl_name);
    render_stat_row($conn, $persp, 'Least points scored',
        'p.score ASC', null, false, $show_bowl_name);
    render_stat_row($conn, $persp, 'Most total points',
        '(p.score + p.opp_score) DESC', 'p.score > p.opp_score', false, $show_bowl_name);
    render_stat_row($conn, $persp, 'Most points in defeat',
        'p.score DESC', 'p.score < p.opp_score', false, $show_bowl_name);

    echo "</table><br>";
}

// season shown WITHOUT the week number -- every bowl game happens at a fixed week (20 for
// the Superbowl, 13 for every college bowl), so "Wk 20"/"Wk 13" is the same on every single
// row and adds nothing; seasons.label (year only, no week) is used instead of games.label.
// $show_bowl_name is only true for the "All Bowls" view, where a single table can mix results
// from any of the six bowl types and needs to say which one each entry is from -- in a
// single-bowl view it would just be the same bowl name repeated on every row, so it's left out.
function render_stat_row($conn, $persp, $label, $order_by, $where_extra, $show_margin, $show_bowl_name) {
    $sql = "SELECT f.label AS franchise_label, opp.label AS opponent_label, p.score, p.opp_score,
                   s.label AS season_label, gt.name AS bowl_type_name
            FROM $persp p
            JOIN franchises f ON f.franchise_id = p.franchise_id
            JOIN franchises opp ON opp.franchise_id = p.opponent_id
            JOIN games g ON g.game_id = p.game_id
            JOIN weeks w ON w.week_id = g.week_id
            JOIN seasons s ON s.season_id = w.season_id
            JOIN game_types gt ON gt.game_type_id = g.game_type_id"
          . ($where_extra ? " WHERE $where_extra" : "")
          . " ORDER BY $order_by LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $lines = [];
    foreach ($rows as $r) {
        $margin_prefix = '';
        if ($show_margin) {
            $margin = (int)$r['score'] - (int)$r['opp_score'];
            $margin_prefix = "$margin : ";
        }
        $context = htmlspecialchars($r['season_label']) . ' vs ' . htmlspecialchars($r['opponent_label']);
        if ($show_bowl_name) {
            $context = htmlspecialchars($r['bowl_type_name']) . ', ' . $context;
        }
        $lines[] = "<strong>{$margin_prefix}" . htmlspecialchars($r['franchise_label']) . " {$r['score']}</strong>"
                  . " - {$r['opp_score']} <em>($context)</em>";
    }
    echo "<tr><th>" . htmlspecialchars($label) . ":</th><td>" . implode('<br>', $lines) . "</td></tr>";
}

// College-only "All Bowls" overview: one row per bowl type, each cell listing every school's
// record in that specific bowl -- matches the old fc_bowlrecords.php overview table.
function render_all_bowls_summary($conn, $league_id, $college_bowls) {
    echo "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' style='width:70%;min-width:520px'>";
    foreach ($college_bowls as $code => $info) {
        if ($code === 'all') {
            continue;
        }
        [$game_type_ids, $bowl_name] = $info;
        $persp = perspectives_sql($game_type_ids);
        $sql = "SELECT f.label, SUM(CASE WHEN p.score > p.opp_score THEN 1 ELSE 0 END) AS wins,
                       SUM(CASE WHEN p.score < p.opp_score THEN 1 ELSE 0 END) AS losses
                FROM $persp p
                JOIN franchises f ON f.franchise_id = p.franchise_id
                WHERE f.league_id = :league_id
                GROUP BY f.franchise_id, f.label
                ORDER BY wins DESC, f.label";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':league_id', $league_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $parts = [];
        foreach ($rows as $r) {
            $parts[] = htmlspecialchars($r['label']) . " {$r['wins']}-{$r['losses']}";
        }
        echo "<tr><th>" . htmlspecialchars($bowl_name) . ":</th><td>" . implode(', ', $parts) . "</td></tr>";
    }
    echo "</table><br>";
}
?>
