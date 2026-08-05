<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
// __DIR__-relative include rather than a bare relative path -- PHP resolves this correctly
// regardless of the current working directory or include_path, which a bare
// include_once('error_handler.php') depends on and isn't safe to assume. error_handler.php
// lives one directory up from this file (include/error_handler.php vs.
// include/custom_php_files/current_standings.php).
// No g_functions.php include here (unlike the old fc_standings.php/fp_standings.php this
// was modeled on) -- that file doesn't exist in this Dadabik install, and this page never
// actually calls anything it would have provided (fetch_row_db/execute_db); it uses plain
// PDO throughout instead.
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// Uses w3-theme-* throughout, matching home.php/team.php -- see home.php for the full
// reasoning on why every colored element needs BOTH an explicit background AND explicit
// text colour, never relying on inheritance from an ancestor. No early-exit paths in this
// file (unlike team.php), so the outer wrapper only needs one open here and one matching
// close at the true end of the file -- no branching to account for.
echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";

// ------------------------------------------------------------------
// Current Standings -- always shows the latest week on record for
// whichever league is selected. No season/week picker here on purpose:
// that's what the separate Historical Standings page is for.
// Queries v_current_standings only -- see new_schema.sql for how that
// view is built (it already resolves league/franchise names to text,
// handles the East/Central/West division ordering, and suppresses
// conference grouping for leagues that don't use it, e.g. NCAA5).
//
// Images: uses the app's $images_url config value (set in
// config_custom.php) rather than hardcoding images.gpbm.local here --
// if that URL ever changes, this page doesn't need editing.
// League/conference/division logo filenames are mapped from the actual
// files present in the images directory, not guessed:
//   league:     NFLAR -> NFL_logo.png, NCAA5 -> NCAA_logo.png
//   conference: 'AFC' -> afc_logo.png, 'NFC' -> nfc_logo.png (lowercased)
//   division:   'AFC East' -> AFC-East.png (space -> hyphen)
// No width/height attributes hardcoded -- the old code's fixed pixel
// sizes could easily be stale for the current files; max-height + auto
// width scales safely regardless of each image's real dimensions.
// ------------------------------------------------------------------

// TODO: set this to team.php's actual id_static_page once it's registered in Dadabik --
// same "Dadabik routes by query parameter, not path" reasoning as build_league_link() below.
// Using 0 as an obviously-wrong placeholder rather than guessing a real-looking number.
define('TEAM_PAGE_STATIC_ID', 0);

$_cp_league = (isset($_GET['league']) && $_GET['league'] === 'NCAA5') ? 'NCAA5' : 'NFLAR';

$_cp_league_logos = ['NFLAR' => 'NFL_logo.png', 'NCAA5' => 'NCAA_logo.png'];
$_cp_league_logo_url = $images_url . $_cp_league_logos[$_cp_league];

echo "<h1>Current Standings</h1>";
echo "<p><img src='" . htmlspecialchars($_cp_league_logo_url) . "' alt='$_cp_league logo' style='width:180px;height:90px;object-fit:contain;object-position:left center'></p>";

// League toggle -- two options today, but written as a loop over a list
// rather than two hardcoded blocks, so a third league someday is a
// one-line change, not a copy-paste.
// Links are built from the actual current request (function=show_static_page&
// id_static_page=N etc.), not a bare '?league=...' -- Dadabik routes by query
// parameter on index.php, not by path, so a relative query-string-only link
// would drop those parameters entirely and land nowhere. Building it dynamically
// also means this isn't hardcoded to whatever id_static_page this page happens
// to be registered under right now -- it keeps working if that ever changes,
// e.g. after a future reinstall.
function build_league_link($code) {
    $parsed = parse_url($_SERVER['REQUEST_URI']);
    parse_str($parsed['query'] ?? '', $params);
    $params['league'] = $code;
    return htmlspecialchars($parsed['path'] . '?' . http_build_query($params));
}

$_cp_leagues = ['NFLAR' => 'NFLAR (Pro)', 'NCAA5' => 'NCAA5 (College)'];
echo "<div class='w3-bar w3-light-grey w3-text-black' style='margin-bottom:16px'>";
foreach ($_cp_leagues as $code => $display_name) {
    $active = ($code === $_cp_league) ? 'w3-theme w3-text-white' : '';
    $link = build_league_link($code);
    echo "<a href='$link' class='w3-bar-item w3-button $active'>$display_name</a>";
}
echo "</div>";

// Placed here specifically -- after the league selector, not next to the main logo above --
// per explicit instruction. College has no conference grouping (confirmed earlier this
// session), so unlike Pro it never gets a second logo later from a conference banner,
// leaving this spot looking bare by comparison.
if ($_cp_league === 'NCAA5') {
    $_cp_league_logo2_url = $images_url . 'NCAA_logo2.png';
    echo "<p><img src='" . htmlspecialchars($_cp_league_logo2_url) . "' alt='NCAA5 logo' style='width:180px;height:90px;object-fit:contain;object-position:left center'></p>";
}

// One query for the whole page -- already sorted correctly (league,
// conference, division in E/C/W order, wins/ties/differential desc)
// via sort_key, so no further ORDER BY needed here.
$_cp_sql = "SELECT * FROM `v_current_standings` WHERE `league_code` = :league ORDER BY `sort_key` ASC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':league', $_cp_league);
$_cp_stmt->execute();
$_cp_rows = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($_cp_rows)) {
    echo "<p><em>No standings data available yet for $_cp_league.</em></p>";
} else {
    $_cp_has_divisions = !empty($_cp_rows[0]['division']);
    $_cp_current_division = null;
    $_cp_current_conference = null;

    if (!$_cp_has_divisions) {
        // College-style: one flat table, no division grouping at all.
        echo standings_table_header();
        foreach ($_cp_rows as $row) {
            echo standings_table_row($row);
        }
        echo "</table>";
    } else {
        // Pro-style: a new table every time the division changes, each preceded by its own
        // division heading (logo + name) and an "all-time division leaders" callout.
        // Detected by comparing consecutive rows, not a hardcoded row count -- works no
        // matter how many teams end up in a division.
        // Conference logo shown once, the first time each conference (AFC/NFC) appears --
        // and only then, since it's only echoed inside this same "did the value change"
        // check, never re-triggered while still inside that conference's own divisions.
        foreach ($_cp_rows as $row) {
            // Division is checked first, and the previous table closed before anything else
            // gets echoed -- a conference change (AFC -> NFC) always coincides with a division
            // change too (there's no "AFC West" -> "AFC East" case where conference changes
            // alone), so nesting the conference check inside this block is safe, and it means
            // the conference banner can never get echoed while the previous table is still
            // open. That was the actual bug: checking conference first meant the NFC banner
            // printed before AFC West's closing </table> tag, landing a <div> inside a table
            // (invalid HTML) that the browser then had to visually relocate on its own,
            // explaining why the logo appeared to "jump" to an unexpected spot.
            if ($row['division'] !== $_cp_current_division) {
                if ($_cp_current_division !== null) {
                    echo "</table>";
                }
                if ($row['conference'] !== $_cp_current_conference) {
                    $_cp_current_conference = $row['conference'];
                    echo conference_banner($_cp_current_conference, $images_url);
                }
                $_cp_current_division = $row['division'];
                echo division_heading($_cp_current_division, $images_url);
                echo division_current_streak($conn, $_cp_league, $_cp_current_division);
                echo division_leaders_callout($conn, $_cp_league, $_cp_current_division);
                echo standings_table_header();
            }
            echo standings_table_row($row);
        }
        echo "</table>";
    }
}
echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

function conference_banner($conference, $images_url) {
    $filename = strtolower($conference) . '_logo.png';
    $url = htmlspecialchars($images_url . $filename);
    // clear:both -- explicit block-level separation, not relying on <br> tags for spacing,
    // which can render unpredictably around a preceding floated/inline image.
    return "<div style='clear:both;margin-top:32px'>"
         . "<img src='$url' alt='$conference logo' style='width:160px;height:86px;object-fit:contain;object-position:left center'>"
         . "</div>";
}

function division_logo_url($division, $images_url) {
    $filename = str_replace(' ', '-', $division) . '.png';
    return htmlspecialchars($images_url . $filename);
}

// Division logo + name as an explicit heading BEFORE the <table>, not inside a <caption> --
// <caption> positioning (default caption-side:top) can be overridden by surrounding CSS
// (w3.css or Dadabik's own styles) in ways that aren't safe to assume from here. A plain
// heading placed directly before the table in the DOM has no such ambiguity.
function division_heading($division, $images_url) {
    $logo_url = division_logo_url($division, $images_url);
    // Explicit font-size throughout this page's text hierarchy, deliberately not relying on
    // browser/Dadabik default heading sizes (h1-h6) -- that's what caused the mismatch here:
    // an <h3> picked up a larger default size than a styled span was given, inverting the
    // intended hierarchy. Division name is the primary heading for this section, so it's
    // the largest text on the page below the page <h1> itself.
    return "<div style='clear:both;margin-top:16px'>"
         . "<img src='$logo_url' alt='$division logo' style='width:60px;height:40px;object-fit:contain;object-position:left center;vertical-align:middle;margin-right:8px'>"
         . "<span style='font-weight:900;font-size:1.6em;vertical-align:middle'>$division</span>"
         . "</div>";
}

function standings_table_header() {
    // table-layout:fixed + an explicit <colgroup> rather than leaving column widths to
    // stretch evenly (w3.css's default) -- that's what put "For"/"Against"/"Diff" so far
    // apart: six columns splitting a wide table equally gives short numeric values the same
    // width as "New England Patriots". Team gets the lion's share of the space; the four
    // numeric columns stay narrow and close together. Also narrower overall (35% vs the
    // previous 60%) and tighter cell padding for a more compact look throughout.
    $out = "<table class='w3-table w3-striped w3-bordered w3-theme-l5 w3-text-black' "
         . "style='width:45%;min-width:520px;table-layout:fixed;border-collapse:collapse;margin-bottom:24px'>";
    $out .= "<colgroup>"
          . "<col style='width:32%'>"
          . "<col style='width:22%'>"
          . "<col style='width:14%'>"
          . "<col style='width:8%'>"
          . "<col style='width:8%'>"
          . "<col style='width:8%'>"
          . "<col style='width:8%'>"
          . "</colgroup>";
    $out .= "<tr>"
          . "<th style='padding:4px 8px'>Team</th>"
          . "<th style='padding:4px 8px'>Coach</th>"
          . "<th style='padding:4px 8px'>Record</th>"
          . "<th style='padding:4px 8px' class='w3-right-align'>For</th>"
          . "<th style='padding:4px 8px' class='w3-right-align'>Against</th>"
          . "<th style='padding:4px 8px' class='w3-right-align'>Diff</th>"
          . "<th style='padding:4px 8px' class='w3-right-align'>Streak</th>"
          . "</tr>";
    return $out;
}

function standings_table_row($row) {
    $record = "{$row['wins']} - {$row['losses']}";
    if ($row['ties'] > 0) {
        $record .= " ({$row['ties']}t)";
    }
    $diff = $row['points_differential'];
    $diff_display = ($diff > 0 ? '+' : '') . $diff;
    $team = htmlspecialchars($row['franchise_label']);
    $coach = htmlspecialchars($row['coach_name'] ?? '-');
    $streak = htmlspecialchars($row['streak'] ?? '-');

    $team_link_url = htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . TEAM_PAGE_STATIC_ID
        . '&league=' . urlencode($row['league_code']) . '&franchise=' . urlencode($row['franchise_id'])
    );
    $team_link = "<a href='$team_link_url'>$team</a>";

    return "<tr>"
         . "<td style='padding:4px 8px'>$team_link</td>"
         . "<td style='padding:4px 8px'>$coach</td>"
         . "<td style='padding:4px 8px'>$record</td>"
         . "<td style='padding:4px 8px' class='w3-right-align'>{$row['points_for']}</td>"
         . "<td style='padding:4px 8px' class='w3-right-align'>{$row['points_against']}</td>"
         . "<td style='padding:4px 8px' class='w3-right-align'>$diff_display</td>"
         . "<td style='padding:4px 8px' class='w3-right-align'>$streak</td>"
         . "</tr>";
}

// Current division championship streak -- walks backward from the most recent season with a
// recorded DIVISION_CHAMPION honor, counting consecutive years the SAME franchise held it.
// Stops at either a different franchise winning, or a gap in the years (missing/incomplete
// data for some season) -- a gap correctly breaks the streak rather than being silently
// skipped over, since that's not really "consecutive" if a season in between is unaccounted for.
function division_current_streak($conn, $league, $division) {
    $sql = "SELECT f.label, s.year
            FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN franchises f ON f.franchise_id = fh.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            JOIN seasons s ON s.season_id = fh.season_id
            WHERE ht.code = 'DIVISION_CHAMPION' AND l.code = :league AND f.division = :division
            ORDER BY s.year DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league', $league);
    $stmt->bindParam(':division', $division);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        return '';
    }

    $champion = $rows[0]['label'];
    $streak = 1;
    $prev_year = (int)$rows[0]['year'];

    for ($i = 1; $i < count($rows); $i++) {
        $this_year = (int)$rows[$i]['year'];
        if ($rows[$i]['label'] === $champion && $this_year === $prev_year - 1) {
            $streak++;
            $prev_year = $this_year;
        } else {
            break;
        }
    }

    $champion_html = htmlspecialchars($champion);
    if ($streak > 1) {
        return "<p style='font-size:1.15em'><strong>The $champion_html have won the " . htmlspecialchars($division)
             . " for $streak consecutive seasons.</strong></p>";
    }
    return "<p style='font-size:1.15em'><strong>The $champion_html are the current champions.</strong></p>";
}

// "All-time division leaders" -- replaces the old fp_franchises.DivisionW
// column lookup. That counter doesn't exist in the new schema; this
// derives the same information from dated franchise_honors rows instead,
// so it can't silently drift from what franchise_awards actually records.
function division_leaders_callout($conn, $league, $division) {
    $sql = "SELECT f.label, COUNT(*) AS titles
            FROM franchise_honors fh
            JOIN honor_types ht ON ht.honor_type_id = fh.honor_type_id
            JOIN franchises f ON f.franchise_id = fh.franchise_id
            JOIN leagues l ON l.league_id = f.league_id
            WHERE ht.code = 'DIVISION_CHAMPION' AND l.code = :league AND f.division = :division
            GROUP BY f.franchise_id, f.label
            ORDER BY titles DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':league', $league);
    $stmt->bindParam(':division', $division);
    $stmt->execute();
    $leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($leaders)) {
        return '';
    }

    $parts = [];
    foreach ($leaders as $l) {
        $parts[] = htmlspecialchars($l['label']) . " ({$l['titles']})";
    }

    $out = "<div style='font-size:1em;font-weight:700;margin-top:8px'>$division all-time division titles:-</div>";
    $out .= "<p><em>" . implode(', ', $parts) . "</em></p>";
    return $out;
}
?>
