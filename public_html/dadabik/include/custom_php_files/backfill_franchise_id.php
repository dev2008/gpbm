<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
include_once(__DIR__ . '/../error_handler.php');
ini_set('display_errors', '1');

// ------------------------------------------------------------------
// Backfill franchise_id -- one-off administrative page, not a regular extraction stage.
//
// Confirmed root cause: none of the 6 NCAA5 uploads had franchise_id resolved, which is why
// none appeared in Extract Play-by-Play's dropdown (it requires franchise_id IS NOT NULL).
// The hook's franchise_id resolution logic (operational_hooks.php step 3b) is present and
// looks structurally sound -- not obviously broken for NCAA5 specifically. Since hooks only
// ever fire once, at insert time, the far more likely explanation is that these particular
// uploads were ingested before this step existed in the hook (or before a later fix to it),
// leaving them permanently stuck with franchise_id=NULL with no way to retroactively trigger
// the hook again. This page reuses that exact same logic (same regex, same lookup, same
// update call) against any existing upload still missing it, rather than re-implementing it
// differently and risking a subtly different result.
// ------------------------------------------------------------------

echo "<div class='w3-panel w3-theme-d5 w3-text-white w3-round-xxlarge'>";
echo "<h1>Backfill franchise_id</h1>";
echo "<p>One-off: resolves franchise_id for any upload where the ingestion hook's own step 3b never ran (or ran before that step existed), using the exact same logic the hook itself uses.</p>";

$_cp_stmt = $conn->prepare(
    "SELECT upload_id, raw_text, league_id, original_filename
     FROM raw_uploads
     WHERE franchise_id IS NULL AND league_id IS NOT NULL"
);
$_cp_stmt->execute();
$_cp_candidates = $_cp_stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<p>Found " . count($_cp_candidates) . " upload(s) with franchise_id still unresolved.</p>";

$_cp_resolved = 0;
$_cp_unresolved = [];

$_cp_franchise_stmt = $conn->prepare("SELECT franchise_id FROM franchises WHERE league_id = :league_id AND label = :label");

foreach ($_cp_candidates as $row) {
    $upload_id = $row['upload_id'];
    $raw_text = $row['raw_text'];
    $league_id = $row['league_id'];

    // Identical regex to operational_hooks.php step 3b -- same source, same pattern.
    if (preg_match('/<BK\.Team Report>.*?\n.*?\n([^(<]+?)\s*\(([^)]+)\)/s', $raw_text, $m)) {
        $franchise_name = trim($m[1]);
        $_cp_franchise_stmt->bindParam(':league_id', $league_id);
        $_cp_franchise_stmt->bindParam(':label', $franchise_name);
        $_cp_franchise_stmt->execute();
        $franchise_id = $_cp_franchise_stmt->fetchColumn();

        if ($franchise_id) {
            ddb_api::update_records('raw_uploads', 'upload_id', $upload_id, ['franchise_id'], [$franchise_id]);
            $_cp_resolved++;
            echo "<p>upload_id $upload_id (" . htmlspecialchars($row['original_filename']) . "): resolved to franchise_id $franchise_id (\"" . htmlspecialchars($franchise_name) . "\")</p>";
        } else {
            $_cp_unresolved[] = "$upload_id (" . htmlspecialchars($row['original_filename']) . "): franchise name '$franchise_name' did not match any franchise in this league";
        }
    } else {
        $_cp_unresolved[] = "$upload_id (" . htmlspecialchars($row['original_filename']) . "): no Team Report header found (expected for bye weeks, which have no Team Report block)";
    }
}

echo "<div class='w3-panel w3-pale-green w3-text-black w3-round-large'>";
echo "<p><strong>$_cp_resolved</strong> upload(s) resolved.</p>";
echo "</div>";

if ($_cp_unresolved) {
    echo "<div class='w3-panel w3-pale-red w3-text-black w3-round-large'>";
    echo "<p><strong>" . count($_cp_unresolved) . "</strong> upload(s) could not be resolved:</p><ul>";
    foreach ($_cp_unresolved as $u) { echo "<li>" . $u . "</li>"; }
    echo "</ul></div>";
}

echo "</div>";
?>
