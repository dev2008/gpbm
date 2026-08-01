<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
// __DIR__-relative include, not a bare relative path -- see current_standings.php/team.php.
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Home page. Built from g_home.php (uploaded for reference), with real
// changes rather than a straight port:
//
//   1. No more /2 division for game counts -- games is already one row
//      per game, unlike f_games which had one row per team per game.
//   2. League-agnostic: loops over whatever's in `leagues` rather than
//      hardcoding "College"/"Pro" via LIKE 'NC%'/'NF%'.
//   3. Uses w3-theme-* (w3-theme-d5, w3-theme, w3-theme-l3/l4), same as
//      the old page, kept applied to the WHOLE page rather than just
//      the top banner -- confirmed this Dadabik install has a theme
//      file loaded, so these resolve to real colours correctly. The
//      actual low-contrast bug in the old page was narrower than "the
//      theme doesn't work": w3-text-white gets set once on the outer
//      wrapper and never reset before the table, which uses the LIGHT
//      tier (w3-theme-l3/l4) for its rows -- white text on a light
//      background. Fixed by being explicit about text colour on every
//      light-background element individually (the table rows, the
//      highlighted-fact box), rather than closing the dark wrapper
//      early and losing the theme for the rest of the page.
//   4. No "Quick Links" section -- Dadabik's own left-hand menu already
//      lists Current Standings and Teams, so a duplicate set of links
//      here was redundant.
//   5. A random highlighted stat, picked from a small pool of "fact"
//      generators each time the page loads -- see the bottom of the
//      file. Easy to add more to the pool later.
// ------------------------------------------------------------------

$_cp_myname = $_SESSION['logged_user_infos_ar']['username_user'] ?? 'there';

// Public/not-logged-in access is group 3 in this Dadabik install (confirmed directly --
// $_SESSION['logged_user_infos_ar']['id_group'] is already populated, no extra query needed
// -- and there's only ever one public user by design, so checking the group rather than a
// specific username is both simpler and correct regardless of what that account happens to
// be named or whether it's ever renamed).
$_cp_is_public_user = (($_SESSION['logged_user_infos_ar']['id_group'] ?? null) == 3);

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>&nbsp;Welcome to the Gameplan PBM site</h1>";

// Same dev/pre-prod/production distinction as the old page, kept for whenever a separate
// dev or staging database exists -- currently always falls through to the generic branch,
// since gplan_pbm matches neither prefix. The dev/pre-prod indicator is worth keeping even
// for a public visitor (useful, non-personal context); the generic "Welcome, X" line is
// suppressed entirely for a public/dummy user instead, rather than showing a name that
// doesn't mean anything.
$_cp_db_prefix = substr($db_name ?? '', 0, 3);
if ($_cp_db_prefix === 'dev') {
    $who = $_cp_is_public_user ? 'You are' : htmlspecialchars($_cp_myname) . ' you are';
    echo "<div class='w3-panel w3-theme w3-text-white w3-round-large'>";
    echo "<h2>$who logged on to ** DEV **</h2>";
    echo "</div>";
} elseif ($_cp_db_prefix === 'pre') {
    $who = $_cp_is_public_user ? 'You are' : htmlspecialchars($_cp_myname) . ' you are';
    echo "<div class='w3-panel w3-theme w3-text-white w3-round-large'>";
    echo "<h2>$who logged on to ** PRE-PROD **</h2>";
    echo "</div>";
} elseif (!$_cp_is_public_user) {
    echo "<div class='w3-panel w3-theme w3-text-white w3-round-large'>";
    echo "<h2>Welcome, " . htmlspecialchars($_cp_myname) . "</h2>";
    echo "</div>";
}

echo "<h1>Gameplan Football</h1>";

// -------------------- Game counts + latest data, per league --------------------
$_cp_sql = "SELECT l.league_id, l.code, l.sport_type, COUNT(g.game_id) AS game_count, MAX(w.week_id) AS latest_week_id
            FROM leagues l
            JOIN seasons s ON s.league_id = l.league_id
            JOIN weeks w ON w.season_id = s.season_id
            JOIN games g ON g.week_id = w.week_id
            GROUP BY l.league_id, l.code, l.sport_type
            ORDER BY l.code";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->execute();
$_cp_league_stats = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

$_cp_summary_parts = [];
foreach ($_cp_league_stats as $ls) {
    $word = ($ls['sport_type'] === 'college') ? 'College' : 'Pro';
    $_cp_summary_parts[] = "<i>" . number_format($ls['game_count']) . "</i> $word";
}
echo "<p>We have " . implode(' and ', $_cp_summary_parts) . " games in our database, the latest updates are:-</p>";

echo "<table style='width:40%' class='w3-table w3-striped w3-bordered'>";
echo "<tr class='w3-blue w3-text-white'><th>League</th><th>Latest Week</th></tr>";
$i = 0;
foreach ($_cp_league_stats as $ls) {
    $row_class = (($i % 2) == 1) ? 'w3-white w3-text-black' : 'w3-light-grey w3-text-black';
    $_cp_stmt2 = $conn->prepare(
        "SELECT s.year, w.week_number FROM weeks w JOIN seasons s ON s.season_id = w.season_id WHERE w.week_id = :wid"
    );
    $_cp_stmt2->bindParam(':wid', $ls['latest_week_id']);
    $_cp_stmt2->execute();
    $_cp_week_row = $_cp_stmt2->fetch(PDO::FETCH_ASSOC);
    $latest_label = $_cp_week_row ? "{$_cp_week_row['year']} Wk {$_cp_week_row['week_number']}" : '-';
    echo "<tr class='$row_class'><td>" . htmlspecialchars($ls['code']) . "</td><td>"
       . htmlspecialchars($latest_label) . "</td></tr>";
    $i++;
}
echo "</table><br>";

// -------------------- Random highlighted stat --------------------
$_cp_fact = pick_random_fact($conn);
if ($_cp_fact) {
    echo "<div class='w3-panel w3-pale-yellow w3-text-black w3-leftbar w3-border-orange w3-round-large'>";
    echo "<h3>Did you know?</h3>";
    echo "<p>$_cp_fact</p>";
    echo "</div>";
}

echo "</div>";  // closes the outer w3-theme-d5 wrapper opened at the very top of the page

// --------------------------------------------------------------
// Random stat pool
// --------------------------------------------------------------

// Tries facts in random order until one actually returns something -- a fact generator can
// return null if its underlying data happens to be empty (e.g. no games loaded yet), and the
// page should still show SOMETHING rather than a blank section or an empty "Did you know?" box.
function pick_random_fact($conn) {
    $generators = [
        'fact_longest_win_streak',
        'fact_longest_coaching_tenure',
        'fact_most_league_championships',
        'fact_longest_division_streak',
        'fact_highest_scoring_game',
    ];
    shuffle($generators);
    foreach ($generators as $fn) {
        $result = $fn($conn);
        if ($result) {
            return $result;
        }
    }
    return null;
}

function fact_longest_win_streak($conn) {
    $sql = "SELECT franchise_label, streak, league_code FROM v_current_standings
            WHERE streak LIKE 'W%'
            ORDER BY CAST(SUBSTRING(streak, 2) AS UNSIGNED) DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    $wins = substr($row['streak'], 1);
    return "The " . htmlspecialchars($row['franchise_label']) . " (" . htmlspecialchars($row['league_code'])
         . ") are on the longest active winning streak right now -- $wins games in a row.";
}

function fact_longest_coaching_tenure($conn) {
    $sql = "SELECT c.name, f.label, s.year, l.code AS league_code
            FROM franchise_coach_tenures fct
            JOIN coaches c ON c.coach_id = fct.coach_id
            JOIN franchises f ON f.franchise_id = fct.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            JOIN weeks w ON w.week_id = fct.start_week_id
            JOIN seasons s ON s.season_id = w.season_id
            WHERE fct.end_week_id IS NULL
            ORDER BY fct.start_week_id ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    return htmlspecialchars($row['name']) . " has been coaching the " . htmlspecialchars($row['label'])
         . " (" . htmlspecialchars($row['league_code']) . ") continuously since {$row['year']} -- the longest current tenure of any coach in the league.";
}

function fact_most_league_championships($conn) {
    $sql = "SELECT f.label, l.code AS league_code, COUNT(*) AS titles
            FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN franchises f ON f.franchise_id = fh.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            WHERE ht.code = 'LEAGUE_WINNER'
            GROUP BY fh.franchise_id, f.label, l.code
            ORDER BY titles DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    $word = ($row['league_code'] === 'NCAA5') ? 'National Championships' : 'Superbowls';
    return "The " . htmlspecialchars($row['label']) . " have won more $word than any other "
         . htmlspecialchars($row['league_code']) . " franchise -- {$row['titles']} titles.";
}

function fact_longest_division_streak($conn) {
    $sql = "SELECT f.label, f.division, l.code AS league_code, s.year
            FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN franchises f ON f.franchise_id = fh.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            JOIN seasons s ON s.season_id = fh.season_id
            WHERE ht.code = 'DIVISION_CHAMPION'
            ORDER BY f.division, s.year DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        return null;
    }

    $by_division = [];
    foreach ($rows as $r) {
        $by_division[$r['division']][] = $r;
    }

    $best = null;
    foreach ($by_division as $division => $entries) {
        $champion = $entries[0]['label'];
        $streak = 1;
        $prev_year = (int)$entries[0]['year'];
        for ($i = 1; $i < count($entries); $i++) {
            $this_year = (int)$entries[$i]['year'];
            if ($entries[$i]['label'] === $champion && $this_year === $prev_year - 1) {
                $streak++;
                $prev_year = $this_year;
            } else {
                break;
            }
        }
        if ($streak > 1 && ($best === null || $streak > $best['streak'])) {
            $best = ['label' => $champion, 'division' => $division, 'league_code' => $entries[0]['league_code'], 'streak' => $streak];
        }
    }

    if (!$best) {
        return null;
    }
    return "The " . htmlspecialchars($best['label']) . " have won the " . htmlspecialchars($best['division'])
         . " ({$best['league_code']}) for {$best['streak']} consecutive seasons -- the longest active division streak in the league.";
}

function fact_highest_scoring_game($conn) {
    $sql = "SELECT label, home_score, away_score FROM games
            WHERE home_score IS NOT NULL AND away_score IS NOT NULL
            ORDER BY (home_score + away_score) DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    $total = $row['home_score'] + $row['away_score'];
    return "The highest-scoring game on record: " . htmlspecialchars($row['label'])
         . " -- $total combined points.";
}
?>
