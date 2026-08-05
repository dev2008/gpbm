<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Extract Play-by-Play -- third staged extraction page (after Standings, Games).
// Targets the '1st Quarter' through '4th Quarter' blocks -- per-play granularity for the
// specific game THIS franchise played that week (unlike League Report, which covers every
// game league-wide). Populates `plays` only -- NOT `drives`, which is structurally distinct
// (populated from "Scouting Report - Game Summary", a different game entirely: next week's
// opponent's most recent one -- see conversation).
//
// Every rule below was confirmed against real turn files before being encoded, not assumed --
// see conversation for the full derivation of each:
//   - Overtime lives WITHIN the 4th Quarter block, under a "<B>Overtime<C>" heading, not its
//     own <BK.> marker. Plays after that heading get quarter=5; the cumulative clock keeps
//     counting past 60:00 rather than resetting.
//   - A blank "side" column means "same possession as the previous play" -- EXCEPT for the
//     very first play after any non-play marker line (two-minute warning, quarter-end
//     summary), which always has an explicit side. Detected structurally: a real play row
//     always starts with a digit; a marker/summary line never does.
//   - Kickoff and onsides-kick rows have no formation letter or field position printed at
//     all -- formation is synthesized as 'X' per explicit instruction, matching the legacy
//     migration's own convention for the same situation.
//   - Scoring plays can span two physical lines: the first ends in its own "<L>", the
//     continuation carries the rest of the description plus the "<T>score<C>" suffix. Merged
//     into one play by only accepting an "<L>" as a true play-ending when it is NOT
//     immediately followed by a lowercase-starting continuation line.
//   - QB benching/replacement announcements ("<Z>X benched, and replaced by Y<C>") consume
//     that row's time+side entirely -- the REAL play that follows has no time of its own.
//     Confirmed directly: borrow the time+side from the announcement line and attach it to
//     the following line's real play data; the announcement text itself is discarded.
//   - "quarterback flop" rows (clock-killing knee-down at a dead clock) have field position
//     and down/distance but no formation/off/def columns at all -- confirmed these should be
//     dropped entirely, not treated as a real play.
//   - yards_gained: "AT gain/loss of N" is a cumulative position marker (the LAST such
//     mention in a play wins); a trailing "FOR gain/loss of N" is an ADDITIONAL increment on
//     top of the last AT position. Validated two independent ways against real plays: down/
//     distance progression, and field-position delta on the following play (only valid when
//     the same team retains possession). Interceptions are a confirmed special case:
//     yards_gained is always 0 regardless of any yardage mentioned in the text (that number
//     describes how far the pass traveled, not an offensive gain). Fumbles use the actual
//     gained yardage up to the point of the fumble, per the AT/FOR rule above.
//   - field_position is yards REMAINING to the opponent's goal line (1-99) -- a gain
//     DECREASES it. Confirmed directly; this was initially assumed backwards.
//   - is_first_down is a computed comparison (yards_gained >= yards_to_go on this same
//     play), not a text pattern -- deliberately has no counterpart in play_text_patterns.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>Extract Play-by-Play</h1>";

// -------------------- Upload selector --------------------
// Same pattern as extract_games.php: exclude uploads whose game already has plays rows.
$_cp_sql = "SELECT ru.upload_id, ru.original_filename, ru.turn_number,
                   l.code AS league_code, s.year AS season_year, w.week_number
            FROM raw_uploads ru
            LEFT JOIN leagues l ON l.league_id = ru.league_id
            LEFT JOIN seasons s ON s.season_id = ru.season_id
            LEFT JOIN weeks w ON w.week_id = ru.week_id
            WHERE ru.franchise_id IS NOT NULL
            AND NOT EXISTS (
                SELECT 1 FROM games g
                JOIN plays p ON p.game_id = g.game_id
                WHERE g.week_id = ru.week_id
                  AND (g.home_franchise_id = ru.franchise_id OR g.away_franchise_id = ru.franchise_id)
            )
            ORDER BY ru.upload_id ASC";
$_cp_stmt = $conn->prepare($_cp_sql);
$_cp_stmt->execute();
$_cp_uploads = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<form method='get'>";
echo "<input type='hidden' name='function' value='" . htmlspecialchars($_GET['function'] ?? 'show_static_page') . "'>";
echo "<input type='hidden' name='id_static_page' value='" . htmlspecialchars($_GET['id_static_page'] ?? '') . "'>";
if (empty($_cp_uploads)) {
    echo "<p><em>Nothing left to process -- every uploaded turn with an identified franchise already has its play-by-play extracted.</em></p>";
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

// -------------------- Resolve upload, game, teams --------------------
$_cp_upload = ddb_api::get_record_details('raw_uploads', 'upload_id', $_cp_upload_id);

if (!$_cp_upload['league_id'] || !$_cp_upload['week_id'] || !$_cp_upload['franchise_id']) {
    echo "<p><em>This upload has no league/week/franchise identified yet.</em></p>";
    echo "</div>";
    return;
}

$_cp_week_id = $_cp_upload['week_id'];
$_cp_franchise_id = $_cp_upload['franchise_id'];

$_cp_stmt = $conn->prepare(
    "SELECT g.game_id, g.home_franchise_id, g.away_franchise_id,
            fh.label AS home_label, fa.label AS away_label
     FROM games g
     JOIN franchises fh ON fh.franchise_id = g.home_franchise_id
     JOIN franchises fa ON fa.franchise_id = g.away_franchise_id
     WHERE g.week_id = :week_id
       AND (g.home_franchise_id = :fid OR g.away_franchise_id = :fid2)"
);
$_cp_stmt->execute([':week_id' => $_cp_week_id, ':fid' => $_cp_franchise_id, ':fid2' => $_cp_franchise_id]);
$_cp_game = $_cp_stmt->fetch(PDO::FETCH_ASSOC);

if (!$_cp_game) {
    echo "<p><em>No game found for this franchise/week in the `games` table yet -- run Extract Games for this upload's week first.</em></p>";
    echo "</div>";
    return;
}

$_cp_game_id = $_cp_game['game_id'];
$_cp_home_id = $_cp_game['home_franchise_id'];
$_cp_away_id = $_cp_game['away_franchise_id'];
$_cp_home_label = $_cp_game['home_label'];
$_cp_away_label = $_cp_game['away_label'];

echo "<p>Game identified: <strong>" . htmlspecialchars($_cp_home_label) . "</strong> vs <strong>"
   . htmlspecialchars($_cp_away_label) . "</strong> (game_id $_cp_game_id).</p>";

// -------------------- Fetch quarter blocks --------------------
$_cp_stmt = $conn->prepare(
    "SELECT block_type, block_text FROM raw_upload_blocks
     WHERE upload_id = :uid AND block_type IN ('1st Quarter','2nd Quarter','3rd Quarter','4th Quarter')"
);
$_cp_stmt->bindParam(':uid', $_cp_upload_id);
$_cp_stmt->execute();
$_cp_quarter_blocks = [];
foreach ($_cp_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $_cp_quarter_blocks[$row['block_type']] = $row['block_text'];
}

if (empty($_cp_quarter_blocks)) {
    echo "<p><em>No quarter blocks found for this upload. Has it been split into blocks yet?</em></p>";
    echo "</div>";
    return;
}

// -------------------- Parse each quarter --------------------
$_cp_all_plays = [];
$_cp_last_offense = null; // carried forward across quarters -- possession doesn't reset at a quarter boundary

$_cp_quarter_map = ['1st Quarter' => 1, '2nd Quarter' => 2, '3rd Quarter' => 3, '4th Quarter' => 4];
foreach (['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'] as $qname) {
    if (!isset($_cp_quarter_blocks[$qname])) { continue; }
    $qnum = $_cp_quarter_map[$qname];
    $plays = parse_quarter_plays($_cp_quarter_blocks[$qname], $qnum);
    foreach ($plays as $p) { $_cp_all_plays[] = $p; }
}

echo "<p>Found " . count($_cp_all_plays) . " plays across " . count($_cp_quarter_blocks) . " quarter block(s).</p>";

// -------------------- Resolve team_codes, build final rows, upsert --------------------
$_cp_team_code_stmt = $conn->prepare("SELECT team_name FROM team_codes WHERE code = :code");

$_cp_play_upsert = $conn->prepare(
    "INSERT INTO plays (game_id, quarter, play_seq, time_gone_seconds, offense_franchise_id,
         field_side, field_position, down, yards_to_go, formation, off_call, def_call, result_text,
         yards_gained, is_touchdown, is_fumble, is_turnover, is_penalty, is_penalty_offense,
         is_penalty_defense, is_sack, is_hurry, is_blitz_pickup, is_blitz_no_pickup, is_safety,
         is_incomplete, is_first_down, score_after, source_upload_id)
     VALUES (:game_id, :quarter, :play_seq, :time_gone_seconds, :offense_franchise_id,
         :field_side, :field_position, :down, :yards_to_go, :formation, :off_call, :def_call, :result_text,
         :yards_gained, :is_touchdown, :is_fumble, :is_turnover, :is_penalty, :is_penalty_offense,
         :is_penalty_defense, :is_sack, :is_hurry, :is_blitz_pickup, :is_blitz_no_pickup, :is_safety,
         :is_incomplete, :is_first_down, :score_after, :upload_id)
     ON DUPLICATE KEY UPDATE
         time_gone_seconds = VALUES(time_gone_seconds), offense_franchise_id = VALUES(offense_franchise_id),
         field_side = VALUES(field_side), field_position = VALUES(field_position), down = VALUES(down),
         yards_to_go = VALUES(yards_to_go), formation = VALUES(formation), off_call = VALUES(off_call), def_call = VALUES(def_call),
         result_text = VALUES(result_text), yards_gained = VALUES(yards_gained),
         is_touchdown = VALUES(is_touchdown), is_fumble = VALUES(is_fumble), is_turnover = VALUES(is_turnover),
         is_penalty = VALUES(is_penalty), is_penalty_offense = VALUES(is_penalty_offense),
         is_penalty_defense = VALUES(is_penalty_defense), is_sack = VALUES(is_sack), is_hurry = VALUES(is_hurry),
         is_blitz_pickup = VALUES(is_blitz_pickup), is_blitz_no_pickup = VALUES(is_blitz_no_pickup),
         is_safety = VALUES(is_safety), is_incomplete = VALUES(is_incomplete),
         is_first_down = VALUES(is_first_down), score_after = VALUES(score_after)"
);

$_cp_patterns = load_play_text_patterns($conn);

$_cp_written = 0;
$_cp_unresolved_sides = [];
$_cp_play_seq = 0;

foreach ($_cp_all_plays as $play) {
    $_cp_play_seq++;

    // Resolve offense_franchise_id: explicit side code -> look up team_codes -> match
    // against this game's two known teams. Blank side -> carry forward. Per the rule
    // confirmed directly: a marker line (two-minute warning, quarter-end) always precedes an
    // explicit side on the next real play, so blank-carry-forward is safe everywhere else.
    $offense_id = $_cp_last_offense;
    if ($play['side'] !== '') {
        $_cp_team_code_stmt->execute([':code' => $play['side']]);
        $team_name = $_cp_team_code_stmt->fetchColumn();
        if ($team_name === $_cp_home_label) {
            $offense_id = $_cp_home_id;
        } elseif ($team_name === $_cp_away_label) {
            $offense_id = $_cp_away_id;
        } else {
            $_cp_unresolved_sides[] = $play['side'];
        }
        $_cp_last_offense = $offense_id;
    }

    $flags = apply_play_text_patterns($play['result_text'], $_cp_patterns);

    // An incomplete pass genuinely gains 0 yards -- that's a known fact, not an absence of
    // information, so NULL is the wrong representation here even though no "at/for gain/loss
    // of N yards" phrase appears in the text to extract a number from. Reuses the already-
    // computed is_incomplete flag (the same single source of truth play_text_patterns
    // already provides) rather than re-checking the text separately here.
    if ($flags['sets_incomplete'] && $play['yards_gained'] === null) {
        $play['yards_gained'] = 0;
    }

    // is_first_down: computed directly, not pattern-matched -- see file header. Left false
    // for goal-line plays (yards_to_go is null there; "first down" doesn't apply the same
    // way when the object of the play is reaching the endzone, which is_touchdown already
    // captures).
    $is_first_down = ($play['yards_to_go'] !== null && $play['yards_gained'] !== null
                       && $play['yards_gained'] >= $play['yards_to_go']) ? 1 : 0;

    $_cp_play_upsert->execute([
        ':game_id' => $_cp_game_id, ':quarter' => $play['quarter'], ':play_seq' => $_cp_play_seq,
        ':time_gone_seconds' => $play['time_gone_seconds'], ':offense_franchise_id' => $offense_id,
        ':field_side' => $play['side'] !== '' ? $play['side'] : null,
        ':field_position' => $play['field_position'], ':down' => $play['down'],
        ':yards_to_go' => $play['yards_to_go'], ':formation' => $play['formation'],
        ':off_call' => $play['off_call'], ':def_call' => $play['def_call'],
        ':result_text' => $play['result_text'], ':yards_gained' => $play['yards_gained'],
        ':is_touchdown' => $flags['sets_touchdown'], ':is_fumble' => $flags['sets_fumble'],
        ':is_turnover' => $flags['sets_turnover'], ':is_penalty' => ($flags['sets_penalty_offense'] || $flags['sets_penalty_defense']) ? 1 : 0,
        ':is_penalty_offense' => $flags['sets_penalty_offense'], ':is_penalty_defense' => $flags['sets_penalty_defense'],
        ':is_sack' => $flags['sets_sack'], ':is_hurry' => $flags['sets_hurry'],
        ':is_blitz_pickup' => $flags['sets_blitz_pickup'], ':is_blitz_no_pickup' => $flags['sets_blitz_no_pickup'],
        ':is_safety' => $flags['sets_safety'], ':is_incomplete' => $flags['sets_incomplete'],
        ':is_first_down' => $is_first_down, ':score_after' => $play['score_after'],
        ':upload_id' => $_cp_upload_id,
    ]);

    $_cp_written++;
}

echo "<div class='w3-panel w3-pale-green w3-text-black w3-round-large'>";
echo "<p><strong>$_cp_written</strong> plays written to plays.</p>";
echo "</div>";

if ($_cp_unresolved_sides) {
    echo "<div class='w3-panel w3-pale-red w3-text-black w3-round-large'>";
    echo "<p><strong>" . count($_cp_unresolved_sides) . "</strong> side code(s) could not be resolved to either of this game's two teams:</p><ul>";
    foreach (array_unique($_cp_unresolved_sides) as $code) { echo "<li>" . htmlspecialchars($code) . "</li>"; }
    echo "</ul></div>";
}

echo "</div>";

// --------------------------------------------------------------
// Helper functions
// --------------------------------------------------------------

// Parses one quarter block's text into an array of structured play arrays. Handles the
// Overtime heading (only ever appears within the 4th Quarter block) by splitting on it and
// assigning quarter=5 to everything after.
function parse_quarter_plays($block_text, $quarter_num) {
    $ot_pos = strpos($block_text, '<B>Overtime<C>');

    // QB-replacement merge: borrow time+side from the announcement line, discard its text,
    // attach to the following line's real play data. Done as a pre-processing substitution
    // before the main play regex runs, rather than folding into that regex directly.
    $qb_replacement_pattern =
        '/(\d+:\d+\s+[A-Z]{0,4})\s*<Z>[^<]*and replaced by[^<]*<C><L>\s*\n\s*' .
        '(\d+\s+\d\w\w(?:\s+and\s+\d+|\s*&\s*Goal)\s+\w\s+\w+\s+\w+\s+.*?<L>)/s';
    $preprocessed = preg_replace($qb_replacement_pattern, '$1 $2', $block_text);

    $play_pattern =
        '/(\d+:\d+)\s+((?!KO\b|ON\b)[A-Z]{0,4})\s*' .
        '(?:(\d+)\s+(\d\w\w(?:\s+and\s+\d+|\s*&\s*Goal))\s+(\w)\s+)?' .
        '(\w+)\s+(\w+)\s+(.*?)<L>(?!\s*\n\s+[a-z])/s';

    preg_match_all($play_pattern, $preprocessed, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    $plays = [];
    foreach ($matches as $m) {
        $pos = $m[0][1];
        $g = array_map(fn($x) => $x[0], $m);

        $raw_result = $g[8];
        if (stripos($raw_result, 'quarterback flop') !== false) {
            continue; // confirmed: not a real play, drop entirely
        }

        $quarter = ($ot_pos !== false && $pos > $ot_pos) ? 5 : $quarter_num;

        [$field_position, $down, $yards_to_go] = parse_down_distance($g[3], $g[4]);
        [$result_text, $score_after] = clean_result_and_score($raw_result);
        $yards_gained = extract_yards_gained($result_text, $raw_result);

        // Interception special case: yards_gained is always 0 regardless of any yardage
        // mentioned in the text -- confirmed directly, that number describes pass distance,
        // not an offensive gain.
        if (stripos($raw_result, 'intercepted') !== false) {
            $yards_gained = 0;
        }

        $form = $g[5];
        $off_call = $g[6];
        // Kickoffs/onsides kicks print no formation letter at all -- synthesized as 'X' per
        // explicit instruction, matching the legacy migration's own convention.
        if ($form === '' && in_array($off_call, ['KO', 'ON'])) {
            $form = 'X';
        }

        $plays[] = [
            'quarter' => $quarter,
            'time_gone_seconds' => time_to_seconds($g[1]),
            'side' => trim($g[2]),
            'field_position' => $field_position,
            'down' => $down,
            'yards_to_go' => $yards_to_go,
            'formation' => $form,
            'off_call' => $off_call,
            'def_call' => $g[7],
            'result_text' => $result_text,
            'yards_gained' => $yards_gained,
            'score_after' => $score_after,
        ];
    }

    return $plays;
}

// "mm:ss" (cumulative clock, can exceed 60:00 in overtime) -> total seconds.
function time_to_seconds($time_str) {
    [$m, $s] = explode(':', trim($time_str));
    return ((int)$m) * 60 + (int)$s;
}

// down/distance: "1st and 10" -> down=1, yards_to_go=10. "1st & Goal" -> down=1,
// yards_to_go=null (no explicit number given for goal-line situations).
function parse_down_distance($fld_str, $downdist_str) {
    if ($fld_str === '') {
        return [null, null, null]; // kickoff-style row -- no field position at all
    }
    $field_position = (int)$fld_str;
    if (preg_match('/^(\d)\w\w\s+and\s+(\d+)$/', trim($downdist_str), $m)) {
        return [$field_position, (int)$m[1], (int)$m[2]];
    }
    if (preg_match('/^(\d)\w\w\s*&\s*Goal$/i', trim($downdist_str), $m)) {
        return [$field_position, (int)$m[1], null];
    }
    return [$field_position, null, null];
}

// Extracts score_after if present, and strips <Z>/<T>...<C>/embedded <L> formatting markers
// from the raw result text, leaving clean, readable prose.
function clean_result_and_score($raw_result) {
    $score_after = null;
    if (preg_match('/<T>([^<]*)<C>/', $raw_result, $m)) {
        $score_after = trim($m[1]);
        $raw_result = substr($raw_result, 0, strpos($raw_result, $m[0]));
    }
    $cleaned = preg_replace('/<Z>\s*/', '', $raw_result);
    $cleaned = preg_replace('/<L>\s*/', ' ', $cleaned);
    $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
    return [$cleaned, $score_after];
}

// yards_gained: "AT gain/loss of N" is a cumulative position marker (last one wins); a
// trailing "FOR gain/loss of N" is an additional increment on top of it. Validated two
// independent ways against real plays (down/distance progression, field-position delta) --
// see file header and conversation.
function extract_yards_gained($cleaned_result, $raw_result) {
    // Match against the raw (pre-cleaned) text so <Z>/<T>/<L> markers don't interfere with
    // "at"/"for" positioning, though the phrase content itself is unaffected by cleaning.
    // "no gain" (e.g. "HB run for no gain", "pass dumped off to RB at no gain, and run for
    // gain of 3 yards") is a genuinely different phrasing from "gain/loss of N yards" -- no
    // "of"/"yards" at all -- confirmed directly against real text, not assumed. Missing this
    // branch entirely meant a play with only "for no gain" mentioned returned NULL (no match
    // found at all) rather than the known value 0, and a compound play with both an "at no
    // gain" position marker and a later "for gain of N" increment silently dropped the first
    // segment, only getting the right final total by coincidence in the one case checked.
    preg_match_all('/(at|for) (?:(gain|loss) of ([\w-]+) yards?|(no) gain)/i', $raw_result, $matches, PREG_SET_ORDER);
    if (empty($matches)) {
        return null;
    }
    $total = null;
    foreach ($matches as $m) {
        if (!empty($m[4])) {
            // The "no gain" branch matched -- unambiguously 0, regardless of gain/loss
            // wording (there is none here).
            $val = 0;
        } else {
            $num = strtolower($m[3]) === 'no' ? 0 : (int)$m[3];
            $val = (strtolower($m[2]) === 'loss') ? -$num : $num;
        }
        if (strtolower($m[1]) === 'at') {
            $total = $val;
        } else {
            $total = ($total ?? 0) + $val;
        }
    }
    return $total;
}

// Loads every row from play_text_patterns once per page load, longest pattern_text first so
// a more specific phrase is checked before a shorter one that might be its substring.
function load_play_text_patterns($conn) {
    $stmt = $conn->query("SELECT * FROM play_text_patterns");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    usort($rows, fn($a, $b) => strlen($b['pattern_text']) <=> strlen($a['pattern_text']));
    return $rows;
}

// Checks result_text against every pattern row (substring match), OR-ing together every
// flag from every pattern that matches -- a play can accumulate flags from more than one
// matching row, not just the single best match.
function apply_play_text_patterns($result_text, $patterns) {
    $flag_names = ['sets_touchdown', 'sets_fumble', 'sets_interception', 'sets_turnover',
        'sets_penalty_offense', 'sets_penalty_defense', 'sets_sack', 'sets_hurry',
        'sets_blitz_pickup', 'sets_blitz_no_pickup', 'sets_safety', 'sets_incomplete'];
    $flags = array_fill_keys($flag_names, 0);
    foreach ($patterns as $p) {
        if (stripos($result_text, $p['pattern_text']) !== false) {
            foreach ($flag_names as $fn) {
                if ((int)$p[$fn] === 1) { $flags[$fn] = 1; }
            }
        }
    }
    return $flags;
}
?>
