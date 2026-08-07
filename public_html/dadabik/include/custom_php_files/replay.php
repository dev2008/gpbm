<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Live Replay -- standalone, spoiler-free entry point for a single game's
// play-by-play. Deliberately a SEPARATE page from game.php, not a
// "?mode=replay" flag on it.
//
// game.php puts the final score, quarter-by-quarter line, and full box
// score at the top of the page, above Play by Play -- exactly right for
// "look up a game," exactly wrong for "relive a game blind." Hiding
// those sections with CSS instead of building a separate page wouldn't
// actually fix that: the score would still be sitting in the page's
// HTML/JSON response, spoiled by anyone glancing at view-source or the
// network tab before clicking anything. This page simply never queries
// games.home_score/away_score/went_to_ot or team_game_stats at all --
// there's nothing in the response to spoil in the first place, not just
// nothing visible.
//
// Same Administrator-only gate as game.php's Play by Play section (see
// security.md) -- this data is family-only regardless of which page
// serves it.
//
// Skips game.php's Table/Playback mode choice entirely and goes straight
// into Playback -- this page exists specifically for that one experience
// (that's what the "Watch the Live Replay" link from
// extract_playbyplay.php sends someone here for); anyone who wants the
// table or full box score has game.php for that instead. A link to
// game.php sits at the very bottom, clearly labeled as containing
// spoilers, for whenever the coach is ready to see it.
//
// DUPLICATES several helper functions from game.php (format_clock,
// quarter_label, ordinal_suffix, play_flags, format_down_distance_plain,
// the JSON-building loop, most of the playback JS) rather than sharing
// an include -- consistent with this app's existing pattern of every
// custom page being self-contained (no shared includes anywhere in this
// project besides error_handler.php). Worth reconsidering if this logic
// ever needs to change in both places -- flagged here rather than
// resolved, since introducing a shared include is a bigger architectural
// call than this one page justifies by itself.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";

$_cp_is_admin = ($current_user_is_administrator == 1);
if (!$_cp_is_admin) {
    // Same "no trace it exists" treatment as game.php's Play by Play gate -- not even a
    // "members only" message, since this whole page is family-only content.
    echo "</div>";
    exit;
}

// game.php's registered id_static_page (confirmed value, see conversation) -- used only for
// the spoiler-labeled link at the very bottom of this page.
define('GAME_PAGE_STATIC_ID', 10);

function build_game_link($game_id) {
    return htmlspecialchars(
        'index.php?function=show_static_page&id_static_page=' . GAME_PAGE_STATIC_ID
        . '&game=' . urlencode($game_id)
    );
}

$_cp_game_id = isset($_GET['game']) ? (int)$_GET['game'] : 0;
if (!$_cp_game_id) {
    echo "<h1>Live Replay</h1>";
    echo "<p><em>No game selected.</em></p>";
    echo "</div>";
    exit;
}

// Deliberately NOT selecting home_score/away_score/went_to_ot/neutral_site -- see file header.
$_cp_sql = "SELECT g.label, w.week_number, w.label AS week_label, s.year AS season_year,
                   s.label AS season_label, l.code AS league_code, gt.name AS game_type_name,
                   hf.label AS home_label, af.label AS away_label
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
    echo "<h1>Live Replay</h1>";
    echo "<p><em>Game not found.</em></p>";
    echo "</div>";
    exit;
}

$_cp_title = $_cp_game['label'] ?: ($_cp_game['home_label'] . ' vs ' . $_cp_game['away_label']);

echo "<div class='w3-panel w3-theme'>";
echo "<h1 class='w3-text-white' style='text-shadow:1px 1px 0 #444'><b>"
   . htmlspecialchars($_cp_title) . " &mdash; Live Replay</b></h1>";
$_cp_season_text = htmlspecialchars($_cp_game['season_label'] ?: $_cp_game['league_code'] . ' ' . $_cp_game['season_year']);
$_cp_week_text = htmlspecialchars($_cp_game['week_label'] ?: 'Week ' . $_cp_game['week_number']);
echo "<p class='w3-text-white'>$_cp_season_text &middot; $_cp_week_text</p>";
echo "</div>";

// -------------------- Plays -- same query/logic as game.php's render_plays_section --------------------
$_cp_sql = "SELECT p.*, f.label AS offense_label
            FROM plays p
            LEFT JOIN franchises f ON f.franchise_id = p.offense_franchise_id
            WHERE p.game_id = :gid
            ORDER BY p.play_order ASC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->bindParam(':gid', $_cp_game_id);
$_cp_stmt->execute();
$_cp_plays = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($_cp_plays)) {
    echo "<p><em>No play-by-play recorded for this game.</em></p>";
    echo "</div>";
    exit;
}

// -------------------- Helpers (duplicated from game.php -- see file header) --------------------

function ordinal_suffix($n) {
    $map = [1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th'];
    return $map[(int)$n] ?? ((int)$n . 'th');
}

function quarter_label($quarter) {
    if ((int)$quarter === 5) {
        return 'Overtime';
    }
    return ordinal_suffix($quarter) . ' Quarter';
}

function format_clock($seconds) {
    if ($seconds === null) {
        return '-';
    }
    $seconds = (int)$seconds;
    $m = intdiv($seconds, 60);
    $s = $seconds % 60;
    return $m . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);
}

function format_down_distance_plain($down, $yards_to_go) {
    if ($down === null) {
        return '-';
    }
    $dist_text = ($yards_to_go !== null) ? (int)$yards_to_go : '?';
    return ordinal_suffix($down) . ' & ' . $dist_text;
}

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

// -------------------- Build the JSON payload (running score, same as game.php) --------------------
$running_score = null;
$pbp_data = [];
foreach ($_cp_plays as $p) {
    $score_before = $running_score;
    if ($p['score_after'] !== null && $p['score_after'] !== '') {
        $running_score = $p['score_after'];
    }
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

// -------------------- Playback widget (no mode choice, no table -- goes straight in) --------------------
echo "<div class='w3-panel w3-theme-l4 w3-text-black' style='padding:16px'>";
echo "<div id='pbp-quarter-heading' style='font-weight:bold;font-size:1.15em;margin-bottom:4px'></div>";
echo "<div id='pbp-play-counter' style='font-size:0.85em;color:#555;margin-bottom:12px'></div>";
echo "<div style='display:flex;justify-content:space-between;flex-wrap:wrap;margin-bottom:12px'>"
   . "<div><strong>Score:</strong> <span id='pbp-score'></span></div>"
   . "<div><strong>Offense:</strong> <span id='pbp-offense'></span></div></div>";
echo "<table class='w3-table w3-bordered w3-white w3-text-black' style='margin-bottom:14px'>"
   . "<tr><th>Time</th><td id='pbp-time'></td><th>Ball On</th><td id='pbp-ballon'></td></tr>"
   . "<tr><th>Down &amp; Dist</th><td id='pbp-downdist'></td><th>Formation</th><td id='pbp-formation'></td></tr>"
   . "<tr><th>Off Call</th><td id='pbp-offcall'></td><th>Def Call</th><td id='pbp-defcall'></td></tr>"
   . "</table>";
echo "<div id='pbp-result-box' class='w3-white w3-text-black' "
   . "style='min-height:70px;padding:10px;border:1px dashed #999;margin-bottom:14px'></div>";
echo "<div>"
   . "<button type='button' class='w3-button w3-theme-d1' id='pbp-prev-btn' onclick='pbpPrev()'>&larr; Previous</button> "
   . "<button type='button' class='w3-button w3-theme' id='pbp-main-btn' onclick='pbpMainAction()'>Reveal Result</button>"
   . "</div>";
echo "</div>";

echo "<script type='application/json' id='pbp-data'>"
   . json_encode($pbp_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
   . "</script>";

echo <<<'JS'
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

    window.pbpMainAction = function () {
        if (!pbpRevealed) {
            pbpRevealPlay();
        } else if (pbpIndex < pbpData.length - 1) {
            pbpIndex++;
            pbpRenderPlay();
        }
    };

    // Going back always shows the target play hidden again, even if it was revealed before --
    // same deliberately-simple choice as game.php's Playback mode, see there for the reasoning.
    window.pbpPrev = function () {
        if (pbpIndex > 0) {
            pbpIndex--;
            pbpRenderPlay();
        }
    };

    // No mode choice on this page -- straight into the first play on load.
    pbpRenderPlay();
})();
</script>
JS;

echo "</div>";

// Spoiler warning, deliberately de-emphasized (small, muted) rather than a prominent button --
// this page exists to NOT show this by default; the link out is an opt-in for when the coach
// is ready, not something competing for attention with the replay itself.
echo "<p style='font-size:0.8em;color:#888'>Want the full box score and final result instead? "
   . "<a href='" . build_game_link($_cp_game_id) . "' style='color:#888;text-decoration:underline'>View full game details (spoilers)</a></p>";
