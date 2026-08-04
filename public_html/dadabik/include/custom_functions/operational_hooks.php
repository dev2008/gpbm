<?php
// ------------------------------------------------------------------
// After-insert hook on raw_uploads. The registration line and the hook
// function itself live together in this one file -- matching the
// DaDaBIK docs' own example (dadabik_send_notice_after_accounts_insert),
// which shows both side by side with no file split indicated. An
// earlier version of this file assumed the registration line belonged
// in config_custom.php instead, by analogy with where $permissions_template
// lives -- that was a guess extrapolated from an unrelated config
// pattern, not something the hooks documentation actually said, and it
// was wrong. Corrected here.
//
// Chosen as a hook rather than a manual page (unlike every extraction
// stage downstream of this, which are deliberately staged/manual pages,
// not hooks -- see conversation): this step is purely mechanical, with
// no judgment calls or partial-success states worth a human reviewing --
// read a file, hash it, split it on markers. Every upload needs exactly
// this, always, with no case where a human would want to delay or skip
// it. The extraction stages (standings, play-by-play, ...) stay manual
// precisely because they DO involve exactly that kind of judgment
// (unresolved franchise names, malformed blocks, partial data).
//
// Per the docs: hooks run inside the same transaction as the insert
// itself, so an uncaught exception here could disrupt the upload. Every
// entry point below is wrapped in try/catch for that reason -- a
// failure here should degrade to "this upload needs attention"
// (parse_status='error', parse_notes explaining why), never an
// exception propagating out of the hook.
// ------------------------------------------------------------------

$hooks['raw_uploads']['insert']['after'] = 'dadabik_process_raw_upload';

function dadabik_process_raw_upload($upload_id) {
    global $conn, $upload_directory;

    try {
        $warnings = [];
        $upload = ddb_api::get_record_details('raw_uploads', 'upload_id', $upload_id);

        // -------------------- Step 0: populate original_filename --------------------
        // Not set by Dadabik's generic_file field at insert time -- confirmed empirically
        // (both $_FILES and $parameters_ar were empty/unhelpful at before-insert). Set here,
        // verbatim from file_path, rather than trying to strip Dadabik's added suffix -- a
        // regex guessing at that suffix's exact pattern is one more thing that could be
        // subtly wrong, and the verbatim value is still perfectly identifiable either way.
        if (empty($upload['original_filename'])) {
            ddb_api::update_records('raw_uploads', 'upload_id', $upload_id,
                ['original_filename'], [$upload['file_path']]);
            $upload['original_filename'] = $upload['file_path'];
        }

        // -------------------- Step 1: read the uploaded file into raw_text --------------------
        // Dadabik's generic_file field only stores the file on disk + a reference in file_path --
        // it does not read the file's contents into the database. That's this step's job.
        $full_path = rtrim($upload_directory, '/') . '/' . $upload['file_path'];
        if (!file_exists($full_path)) {
            dadabik_mark_upload_error($upload_id, "Uploaded file not found on disk at $full_path");
            return;
        }
        $raw_text = file_get_contents($full_path);
        if ($raw_text === false) {
            dadabik_mark_upload_error($upload_id, "Could not read uploaded file at $full_path");
            return;
        }

        // -------------------- Step 1b: extract turn_number --------------------
        // MUST happen before the <STARTREP> trim below, not after -- confirmed the "X, Turn N"
        // line (e.g. "NFLAR-PE, Turn 1") sits BEFORE <STARTREP> in every file checked,
        // including genuinely clean ones with no email-client contamination at all. This
        // isn't Gmail boilerplate the way the rest of that leading text is -- it's part of
        // the legitimate email body every turn file has. Previously raw_uploads.turn_number
        // was never actually written by this hook at all (only ever read from it, always
        // NULL, which silently fed (int)NULL = 0 into every weeks.turn_number this hook has
        // created so far -- a real bug, not just a missing feature; see conversation).
        if (preg_match('/,\s*Turn\s+(\d+)/', $raw_text, $m)) {
            ddb_api::update_records('raw_uploads', 'upload_id', $upload_id, ['turn_number'], [(int)$m[1]]);
            $upload['turn_number'] = (int)$m[1];
        } else {
            $warnings[] = 'could not find a "Turn N" line to extract turn_number';
        }

        // Trim anything before <STARTREP> -- confirmed present exactly once at the true start
        // of every real turn file checked, including a genuinely contaminated one. Coaches
        // copy-paste these from their email client rather than downloading a clean file, and
        // that's prone to accidentally selecting extra page content along with it -- confirmed
        // directly: one real upload had Gmail's own web-interface chrome ("Skip to content",
        // "Using Gmail with screen readers", the inbox message list, sender/timestamp) pasted
        // in ahead of the actual report. Harmless for identification (it just searches the
        // whole text regardless), but there's no reason to let it sit in raw_text going
        // forward when it's this easy to normalize away. Non-fatal if the marker's missing --
        // proceeds with the untrimmed text and just notes it, rather than blocking the upload
        // over what block-splitting doesn't actually depend on.
        $startrep_pos = strpos($raw_text, '<STARTREP>');
        if ($startrep_pos !== false && $startrep_pos > 0) {
            $raw_text = substr($raw_text, $startrep_pos);
        } elseif ($startrep_pos === false) {
            $warnings[] = 'no <STARTREP> marker found -- raw_text may contain unexpected leading content';
        }
        ddb_api::update_records('raw_uploads', 'upload_id', $upload_id, ['raw_text'], [$raw_text]);

        // -------------------- Step 2: duplicate check --------------------
        // content_hash is a real MySQL GENERATED column (SHA2 of raw_text), so it's already
        // recomputed automatically by the database the moment raw_text was set above -- just
        // re-fetch to read it, no need to compute it here ourselves.
        $upload = ddb_api::get_record_details('raw_uploads', 'upload_id', $upload_id);
        $content_hash = $upload['content_hash'];

        $stmt = $conn->prepare(
            "SELECT upload_id FROM raw_uploads
             WHERE content_hash = :hash AND upload_id != :uid AND parse_status != 'error'
             LIMIT 1"
        );
        $stmt->bindParam(':hash', $content_hash);
        $stmt->bindParam(':uid', $upload_id);
        $stmt->execute();
        $duplicate_of_id = $stmt->fetchColumn();

        if ($duplicate_of_id) {
            $notes = "Identical content to upload #$duplicate_of_id -- not reprocessed.";
            if ($warnings) {
                $notes .= ' WARNINGS: ' . implode('; ', $warnings);
            }
            ddb_api::update_records('raw_uploads', 'upload_id', $upload_id,
                ['parse_status', 'parse_notes'],
                ['duplicate', $notes]);
            return;
        }

        // -------------------- Step 3: identify league/season/week --------------------
        // Only attempted if not already set. NOT sourced from the Standings block --
        // confirmed empirically (a real bowl/playoff-week upload) that Standings simply
        // doesn't exist for every week: once a season moves past its regular season into
        // bowls/playoffs, there's no running win-loss table to show, so League Report shows
        // bowl results and the next round's schedule instead. Sourced from three places
        // instead, each covering different week-types (confirmed against a normal week, a
        // bye week, and this bowl week specifically -- see dadabik_identify_upload):
        //   - week number: League Report's header ("Week N of total") -- present in every
        //     week-type seen so far, including bye and bowl weeks.
        //   - league code + season year: Team Report's header ("League X Season Y") for any
        //     week that has one (everything except bye weeks); Draft Report's header
        //     ("X ... Y Season" -- note the different word order) as the bye-week fallback.
        if (!$upload['league_id'] || !$upload['season_id'] || !$upload['week_id']) {
            [$league_code, $season_year, $week_number] = dadabik_identify_upload($raw_text);

            if (!$league_code || !$season_year || $week_number === null) {
                // Explicit === null check, not a plain falsy check (!$week_number) -- week 0
                // (pre-season) is a real, valid value, and PHP treats 0 as falsy same as null,
                // which would otherwise make this incorrectly reject every pre-season upload
                // as a failed identification.
                dadabik_mark_upload_error($upload_id,
                    "Could not identify league/season/week (league=" . ($league_code ?: '?')
                    . ", season=" . ($season_year ?: '?') . ", week=" . ($week_number === null ? '?' : $week_number)
                    . ") -- block splitting was NOT attempted.");
                return;
            }

            $stmt = $conn->prepare("SELECT league_id FROM leagues WHERE code = :code");
            $stmt->bindParam(':code', $league_code);
            $stmt->execute();
            $league_id = $stmt->fetchColumn();

            if ($league_id) {
                $week_id = dadabik_resolve_or_create_week(
                    $conn, $league_id, $season_year, $week_number, (int)$upload['turn_number']
                );
                $season_id = dadabik_get_season_id_for_week($conn, $week_id);
                ddb_api::update_records('raw_uploads', 'upload_id', $upload_id,
                    ['league_id', 'season_id', 'week_id'], [$league_id, $season_id, $week_id]);
                $upload['league_id'] = $league_id;
            } else {
                dadabik_mark_upload_error($upload_id, "Unrecognized league code '$league_code' -- no matching row in leagues.");
                return;
            }
        }

        // -------------------- Step 3b: resolve franchise_id --------------------
        // Records whose turn this is (the receiving franchise) -- not either game
        // participant. Checked independently of the league/season/week block above, since
        // it's a separate field that was simply never resolved at all until now, regardless
        // of whether the other three needed (re-)identifying. Sourced from Team Report's
        // header, e.g. "Pittsburgh Panthers (Alan Milnes)   turn credits = 8.5" -- the
        // franchise name immediately follows the report's own top header line. Resolved the
        // same way extract_standings.php resolves team names to franchise_id: exact
        // match against franchises.label, scoped to the league already confirmed above.
        if (empty($upload['franchise_id']) && !empty($upload['league_id'])) {
            if (preg_match('/<BK\.Team Report>.*?\n.*?\n([^(<]+?)\s*\(([^)]+)\)/s', $raw_text, $m)) {
                $franchise_name = trim($m[1]);
                $stmt = $conn->prepare("SELECT franchise_id FROM franchises WHERE league_id = :league_id AND label = :label");
                $stmt->bindParam(':league_id', $upload['league_id']);
                $stmt->bindParam(':label', $franchise_name);
                $stmt->execute();
                $franchise_id = $stmt->fetchColumn();

                if ($franchise_id) {
                    ddb_api::update_records('raw_uploads', 'upload_id', $upload_id, ['franchise_id'], [$franchise_id]);
                } else {
                    $warnings[] = "franchise name '$franchise_name' from Team Report did not match any franchise in this league";
                }
            } else {
                $warnings[] = 'could not find a Team Report header to resolve franchise_id (expected for bye weeks, which have no Team Report block)';
            }
        }

        // -------------------- Step 4: split into blocks --------------------
        $block_count = dadabik_split_into_blocks($conn, $upload_id, $raw_text);

        // 'partial', not 'parsed' -- this hook only identifies the upload and splits it into
        // blocks; the domain-table extraction stages (standings, play-by-play, ...) are
        // separate, manually-triggered pages, still pending after this step completes.
        $notes = "$block_count blocks split successfully; awaiting extraction stages.";
        if ($warnings) {
            $notes .= ' WARNINGS: ' . implode('; ', $warnings);
        }
        ddb_api::update_records('raw_uploads', 'upload_id', $upload_id,
            ['parse_status', 'parsed_at', 'parse_notes'],
            ['partial', date('Y-m-d H:i:s'), $notes]);

    } catch (\Throwable $e) {
        dadabik_mark_upload_error($upload_id, 'Exception during processing: ' . $e->getMessage());
    }
}

// --------------------------------------------------------------
// Helpers
// --------------------------------------------------------------

// Identifies league code / season year / week number from raw_text, trying multiple sources
// since no single block is both universally present AND has all three pieces. Patterns
// tested directly against real header lines from a normal week, a bye week, and a bowl/
// playoff week before this was written -- see conversation. Searches the whole raw_text
// rather than a specific split-out block, since these header phrases are distinctive enough
// not to plausibly appear elsewhere (e.g. in Roster or Scouting Report content), and doing
// so avoids needing block-splitting to happen before identification can run.
function dadabik_identify_upload($raw_text) {
    $league_code = null;
    $season_year = null;
    $week_number = null;

    // Week number: League Report's header, e.g. "Week 12 of 11" -- present for every
    // numbered week seen so far, including bye and bowl/playoff weeks. Pre-season weeks
    // don't have a numbered week at all though -- confirmed against real pre-season uploads
    // from both leagues, League Report's header says "Pre Season"/"Pre-Season" in place of
    // "Week N of total" entirely (mapped to week_number = 0 -- matching how these leagues'
    // own week numbering works: week 0 IS pre-season, confirmed directly). Both spacing AND
    // hyphenation variants matched deliberately -- confirmed a real upload uses "Pre-Season"
    // (hyphenated) in Team Report's header specifically, different from every earlier sample
    // checked, which all used "Pre Season" (space). Since the two headers aren't guaranteed
    // to agree on which form they use, matching only one risked silently failing
    // identification on real uploads exactly like this one.
    if (preg_match('/Week\s+(\d+)\s+of\s+\d+/', $raw_text, $m)) {
        $week_number = (int)$m[1];
    } elseif (preg_match('/Pre[\s-]Season/', $raw_text)) {
        $week_number = 0;
    }

    // League + season: Team Report's header, e.g. "League NCAA5   Season 2038" -- present
    // for any week that has a Team Report block (everything except bye weeks, which have no
    // game and so no Team Report at all).
    if (preg_match('/League\s+(\w+)\s+Season\s+(\d+)/', $raw_text, $m)) {
        $league_code = $m[1];
        $season_year = (int)$m[2];
    } elseif (preg_match('/GAMEPLAN\s+[\d.]+\s+(\w+)\s+.*?(\d{4})\s+Season/', $raw_text, $m)) {
        // Bye-week fallback: Draft Report's header, e.g. "NFLAR   Annual Draft   Round 2
        // 2032 Season" -- note the year comes BEFORE the word "Season" here, the opposite
        // order from Team Report's header, so this needs its own separate pattern.
        $league_code = $m[1];
        $season_year = (int)$m[2];
    }

    return [$league_code, $season_year, $week_number];
}

// Splits raw_text on every <BK.NAME> marker into raw_upload_blocks rows -- one row per block,
// content running from just after each marker to just before the next one (or end of text for
// the last block). 'Turnsheet' is deliberately excluded: it's the blank form for the NEXT
// turn, not data to extract. Upsert (ON DUPLICATE KEY UPDATE) rather than plain INSERT, since
// raw_upload_blocks has UNIQUE KEY (upload_id, block_seq) specifically so re-running this
// safely re-splits rather than creating a second, duplicate set of blocks.
function dadabik_split_into_blocks($conn, $upload_id, $raw_text) {
    if (!preg_match_all('/<BK\.([^>]+)>/', $raw_text, $matches, PREG_OFFSET_CAPTURE)) {
        return 0;
    }

    $names = $matches[1];       // [ [block_name, offset_of_name], ... ]
    $full_tags = $matches[0];   // [ [full_tag_text, offset_of_tag_start], ... ]
    $count = count($names);
    $seq = 1;
    $inserted = 0;

    $stmt = $conn->prepare(
        "INSERT INTO raw_upload_blocks (upload_id, block_seq, block_type, block_text)
         VALUES (:upload_id, :block_seq, :block_type, :block_text)
         ON DUPLICATE KEY UPDATE block_type = VALUES(block_type), block_text = VALUES(block_text)"
    );

    for ($i = 0; $i < $count; $i++) {
        $block_type = trim($names[$i][0]);
        if ($block_type === 'Turnsheet') {
            continue;
        }

        $content_start = $full_tags[$i][1] + strlen($full_tags[$i][0]);
        $content_end = ($i + 1 < $count) ? $full_tags[$i + 1][1] : strlen($raw_text);
        $block_text = substr($raw_text, $content_start, $content_end - $content_start);

        $stmt->bindValue(':upload_id', $upload_id);
        $stmt->bindValue(':block_seq', $seq);
        $stmt->bindValue(':block_type', $block_type);
        $stmt->bindValue(':block_text', $block_text);
        $stmt->execute();
        $seq++;
        $inserted++;
    }

    return $inserted;
}

// Find-or-create a week (and its season, if needed) -- same logic as extract_standings.php
// used before this hook existed; now the single place this happens, since every upload is
// identified here before any extraction page ever sees it. New seasons get their initial
// status inferred from week_number (preseason vs regular) rather than always assuming
// 'regular' -- see the status logic below for why that distinction matters concretely, not
// just in theory.
function dadabik_resolve_or_create_week($conn, $league_id, $year, $week_number, $turn_number) {
    $stmt = $conn->prepare("SELECT season_id FROM seasons WHERE league_id = :league_id AND year = :year");
    $stmt->bindParam(':league_id', $league_id);
    $stmt->bindParam(':year', $year);
    $stmt->execute();
    $season_id = $stmt->fetchColumn();

    if (!$season_id) {
        // Status inferred from whichever week happened to trigger this season's creation --
        // best-effort, not a guarantee (a season could theoretically get created from an
        // out-of-order upload), but more accurate than always assuming 'regular' regardless.
        // Matters concretely: a franchise eliminated early can start receiving pre-season
        // turns for the NEXT season while other franchises in the same league are still
        // finishing the previous one's playoffs/bowls -- confirmed this really happens, not
        // hypothetical -- so a brand new season's first-ever upload being its own pre-season
        // week is a real, expected case, not an edge case worth ignoring.
        $initial_status = ($week_number === 0) ? 'preseason' : 'regular';
        $season_id = ddb_api::insert_record('seasons',
            ['league_id', 'year', 'status'], [$league_id, $year, $initial_status]);
        $stmt = $conn->prepare("SELECT code FROM leagues WHERE league_id = :id");
        $stmt->bindParam(':id', $league_id);
        $stmt->execute();
        $league_code = $stmt->fetchColumn();
        ddb_api::update_records('seasons', 'season_id', $season_id, ['label'], ["$league_code $year"]);
    }

    $stmt = $conn->prepare("SELECT week_id FROM weeks WHERE season_id = :season_id AND week_number = :week_number");
    $stmt->bindParam(':season_id', $season_id);
    $stmt->bindParam(':week_number', $week_number);
    $stmt->execute();
    $week_id = $stmt->fetchColumn();

    if (!$week_id) {
        $season_label = ddb_api::get_record_details('seasons', 'season_id', $season_id)['label'];
        $week_id = ddb_api::insert_record('weeks',
            ['season_id', 'week_number', 'turn_number'], [$season_id, $week_number, $turn_number]);
        ddb_api::update_records('weeks', 'week_id', $week_id, ['label'], ["$season_label Wk $week_number"]);
    }

    return $week_id;
}

function dadabik_get_season_id_for_week($conn, $week_id) {
    $stmt = $conn->prepare("SELECT season_id FROM weeks WHERE week_id = :week_id");
    $stmt->bindParam(':week_id', $week_id);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function dadabik_mark_upload_error($upload_id, $message) {
    ddb_api::update_records('raw_uploads', 'upload_id', $upload_id,
        ['parse_status', 'parse_notes'], ['error', $message]);
}
?>
