# Gameplan PBM — Live Parser Build Notes

**Purpose:** companion to `schema_design_proposal.md`, which covers schema design and the
historical migration (`f_games`/`fc_franchises`/etc. → `games`/`franchises`/`franchise_honors`/
etc., 9,969 games, and the 253,450-row `legacy_play_log` migration). That document stops once
the schema exists and historical data is loaded. This one picks up from there: building the
**live, staged extraction pages** that turn newly-uploaded turn files into rows in that schema,
plus every real bug, structural surprise, and design decision made along the way. If starting a
fresh conversation, load both files — this one is meaningless without the schema context in the
first.

---

## 1. Current state — what's built and working

**Ingestion (automatic, via Dadabik after-insert hook on `raw_uploads`):**
- `operational_hooks.php` — populates `original_filename`, computes `content_hash`, identifies
  league/season/week/franchise via a multi-source fallback chain (League Report header for
  week, Team Report for league+season, Draft Report for bye-week fallback), splits the turn
  into `raw_upload_blocks` (one row per `<BK.>` marker; `Turnsheet` excluded as a blank form,
  `Draftsheet` kept as real data).

**Staged, manually-triggered extraction pages (deliberately NOT automated hooks — see §3):**
- `extract_standings.php` — parses the `Standings` sub-block of `League Report` into
  `standings_weekly`.
- `extract_games.php` — parses the game-results portion of `League Report` (every game
  league-wide that week) into `games` + `team_game_stats`.
- `extract_playbyplay.php` — parses the `1st Quarter`–`4th Quarter` blocks (the receiving
  franchise's own game only) into `plays`. **Not yet live-tested against the database** as of
  this writing — built and validated at the parsing-logic level (see §5), but the actual
  `team_codes` lookup, `play_text_patterns` matching, and upsert have only been tested in
  isolation, not end-to-end against a real upload. Testing in progress by the user in parallel
  with this document being written.

**Supporting schema additions (beyond the original migration):**
- `play_text_patterns` — extensible lookup table for classifying play `result_text` into
  boolean flags (turnover, fumble, penalty offense/defense, sack, hurry, blitz pickup/no-pickup,
  safety, incomplete, touchdown). A real table, not hardcoded PHP, specifically so a newly
  discovered text variant is a row insert, not a code change. 27 patterns seeded, every one
  cross-validated against real, current turn files (see §4 for why that mattered).
- `team_codes` — flat `(code, team_name)` lookup for the 2-4 letter side codes used in play-by-
  play and League Report text (`PE`, `GB`, etc.). Deliberately has no `franchise_id` or
  `league_id` column — see §3 for why.
- `plays` gained several columns after the initial design that weren't in the original
  migration-era schema: `is_fumble`, `is_penalty`/`is_penalty_offense`/`is_penalty_defense`,
  `is_sack`, `is_hurry`, `is_blitz_pickup`, `is_blitz_no_pickup`, `is_safety`, `is_incomplete`,
  `is_first_down`. All confirmed necessary against real data or explicit instruction (see §4).
- `franchises.abbr` was **dropped entirely**, replaced by `team_codes` (see §3).

**Front-end pages:** `current_standings.php`, `team.php`, `home.php`, `bowl_records.php` — all
built earlier, standings page recently given a second logo for College (matching Pro's
conference-banner logo, placed after the league selector per explicit correction — see §6 for
why the first attempt was wrong).

**Live database:** fully loaded with historical data per the migration document; `raw_uploads`/
`raw_upload_blocks` populated automatically as new turns arrive; `standings_weekly` and `games`/
`team_game_stats` now have live-parsed rows from real 2034 NFLAR/NCAA5 turns in addition to the
historical migration data. `plays` still empty pending the first successful live test.

---

## 2. Architecture decisions specific to the live-parser phase

- **Staged, manually-triggered pages, not Dadabik hooks.** Each parser (Standings, Games,
  Play-by-Play) is a separate custom page the user runs deliberately, picking the upload from a
  dropdown. Chosen over automatic hook-based parsing for visibility and control while the
  parsers are still being hardened — a bad automatic parse on every upload would be much worse
  than a manual step that can be re-run once a bug is found.
- **Upsert semantics differ by table, and this is deliberate, not inconsistent:**
  - `games`: no-op upsert (`ON DUPLICATE KEY UPDATE game_id=game_id`) — the schema comment says
    `source_upload_id` is "first upload this result was captured from." A game's final score is
    a fixed historical fact once played; re-parsing a later upload of the same week shouldn't
    overwrite it.
  - `team_game_stats`, `standings_weekly`, `plays`: full upsert, every column updated on
    conflict — these are league-wide-per-week or per-play facts that should always reflect the
    latest parse, and re-parsing is expected to be idempotent (same input → same output), not
    something to protect a "first" value from.
- **`play_text_patterns` as a real table, not hardcoded regex/PHP arrays.** One master table
  with a boolean column per flag (not a single category enum), so one pattern row can set
  multiple flags at once (e.g. sacked *and* fumbled). Substring match, longest `pattern_text`
  wins on overlap, OR-ing together every flag from every pattern that matches a given
  `result_text` — a play can accumulate flags from more than one matching row.
- **`team_codes` deliberately has no `franchise_id` or `league_id` column.** Two real design
  arguments converged on this:
  1. The parser always already knows the game's two specific teams before it ever needs to
     resolve a code (via `games`, already parsed by that point), so cross-league collisions
     (`AF` meaning both Carolina Panthers in NFLAR and Air Force Falcons in NCAA5) are never
     actually ambiguous in practice — the game's two known teams disambiguate automatically.
  2. Some codes never have a current franchise to attach to at all — confirmed directly: `VT`
     (Virginia Tech Hokies) is a real, historically-used NCAA5 code with no current `franchises`
     row, since the league's own 12-team membership changed at some point (Iowa State Cyclones
     occupies that slot now).
  A `UNIQUE KEY (code, team_name)` constraint makes future franchise relocations safely
  upsertable (`INSERT ... ON DUPLICATE KEY UPDATE code_id=code_id`) without needing to check for
  an existing row first — confirmed the *common* case for a relocation is landing on a
  combination some other franchise used earlier (24 franchises drawn from a 32-team real-world
  pool), not a genuinely new pairing.
- **Historical `legacy_play_log` and live `plays` are staying separate tables**, not being
  merged or backfilled into one. `legacy_play_log` can't cleanly gain real `game_id` links for
  most of its 253,450 rows (most belong to leagues/seasons never fully migrated into `games`).
  Plan for offense/defense matchup views going forward: build new views that UNION both tables,
  rather than retrofitting the existing eight `v_playcall_matchup`-style views (which currently
  only query `legacy_play_log` and won't see anything `plays` picks up going forward) — **not
  yet built**, flagged as real follow-up work in §7.

---

## 3. Hard-won lessons — worth internalizing before continuing this work

These aren't just bug notes; several represent real, generalizable failure modes that recurred
more than once across this build.

### Legacy/historical data can genuinely diverge from what the current game engine prints —
### always cross-check against real, current turn files, not just the legacy source.
The clearest example: a `play_text_patterns` seed was built from a comprehensive SQL export of
`n_playbyplay.a_text` (every row with `a_peno=1`, confirmed by the user to be authoritative for
what patterns *exist*), which showed `"0 start against offence"` as far more common than
`"false start against offence"` for the same underlying event. Presented as "genuinely what the
game prints" — turned out to be wrong. The user grepped every real, current turn file they had
and found zero instances of `"0 start"`, only `"false start"`. Root cause: the legacy export
reflects however `gplan_main.n_playbyplay` was originally populated, which is a different, less
authoritative process than "the current game engine's own text output" — conflating "came from a
database table" with "therefore reliable, unparsed ground truth" was the actual error. Every
other pattern derived from that same legacy export was then re-validated independently against
real turn files before being trusted (all 19 others held up).

### A falsy-zero bug fix needs tracing through every consumer of the value, not just its source.
`lookup_game_type()` in `extract_games.php` used `return $stmt->fetchColumn() ?: null;` — since
NCAA5's current "Pre Season" game type is literally `game_type_id = 0`, and PHP treats `0` as
falsy, this silently discarded a valid result. Fixed with an explicit `!== false` check. The
*same* bug immediately resurfaced one call site further up the chain: the code consuming
`resolve_game_type_id()`'s return value used `if (!$game_type_id)`, which has the identical
falsy-zero problem — fixing the source function's return value didn't help until the *consumer*
was also fixed. Lesson: search the whole file for every place a value is checked, don't assume
fixing one spot closes the issue.

### Match-count validation can hide "genuinely wrong" matches that happen to be the right count.
Extract Games' offset arithmetic (mapping capture groups to named fields) had two independent
errors — a returns-line group miscounted (8 vs. actual 10) that cascaded into every field after
it. This was invisible to "does the regex match N games" testing, since the regex itself was
matching the correct span of text throughout; the bug was purely in how that match was sliced
apart afterward. Caught only by validating actual field *values* against raw text by hand, not
by trusting a passing match count.

### A field text pattern that's "always present" in every sample you've checked might not be
### universal — optional/blank fields cause regexes to silently over-match into the next record.
Two separate real bugs, same root cause: (1) a team with zero pass attempts has a completely
*blank* Pass field in its "Calls" line (`"Pass      , Def..."`), and (2) a literal `-` character
sometimes appears as a call-code placeholder (`"Fm S -,"`). Both broke a regex that assumed
exactly two word-characters were always present. Because the surrounding pattern used non-greedy
`.*?` segments, the failure didn't just skip the malformed record — it backtracked and matched
the *next* record's Calls line instead, silently merging two games into one corrupted match
(diagnosable by an unusually long match length, roughly double the norm). Fixed by allowing
each call-code slot to be `[\w-]*` (zero-or-more word characters or a hyphen) instead of
requiring `\w+`.

### Don't treat your own abbreviated summary of something as if it were the literal source text.
A game-type mapping used `"Cons Gold"` as a header-matching key, taken from an earlier
*shorthand outline* rather than verified against real turn text. The actual text is
`"Consolation Gold"` (confirmed directly: `"<B>Consolation Gold<L.45.1>"`). The shorthand had
never been checked against a real file before being used as a literal pattern.

### Regex `.` doesn't match newlines by default — files can genuinely differ in whether they use
### real line breaks or plain spaces between logical segments.
One real turn file had League Report content using plain spaces where every previously-tested
file used `\r\n`. A pattern relying on `.*?\n` to skip between stat lines silently failed to
match *any* games in that file (0 of 6) until switched to DOTALL mode, where `.*?` doesn't care
which separator a given file actually used.

### Down/distance progression is not always a valid cross-check for yardage — first downs reset
### the distance-to-go independent of exact yardage gained beyond the minimum needed.
When validating the play-by-play yardage-extraction rule (see §5) against consecutive plays'
down/distance, one compound-play case appeared to fail (predicted 15 yards gained, but distance
went 3rd-and-6 → 1st-and-10, an apparent -4). Turned out the discrepancy was in the *validation
method*, not the extraction rule: a first down resets distance to 10 regardless of yardage
beyond what was needed, breaking a same-drive distance-delta check. Field-position delta (not
affected by first-down resets) confirmed the extraction rule was correct all along. General
lesson: prefer field-position delta over down/distance delta for yardage cross-checks, and don't
conclude a rule is wrong before checking whether the *validation method itself* has blind spots.

### `field_position`'s direction was initially assumed backwards.
Assumed (without confirming) that higher `field_position` meant closer to the *offense's own*
goal (yards already traveled). It's the opposite: `field_position` is yards *remaining* to the
opponent's goal line (1-99), so a gain *decreases* it. Caught via the same yardage cross-
validation work above — a compound play's predicted +15-yard gain matched a field-position
*decrease* of exactly 15, not an increase.

---

## 4. Confirmed text-parsing rules (play-by-play specific)

All of the following were confirmed against real turn files, explicit user instruction, or both
— not assumed. See `play_text_patterns_seed.sql` and `extract_playbyplay.php` for the
implementations.

- **Turnover/fumble classification** (explicit rules, not inferred from possession changes):
  - `"intercepted"` anywhere in the text → always a turnover. `yards_gained` is always `0` on
    an interception, regardless of any yardage number in the text (that number describes how
    far the pass traveled, not an offensive gain).
  - Fumble, but **not** a turnover: `"fumbled and recovered"`, `"fumble recovered by offence"`,
    `"...yards and recovered"` (the fumbled-snap case — only the "recovered" form was ever
    confirmed to exist for this specific phrasing).
  - Fumble **and** a turnover: `"fumbled and not recovered"`, `"fumble recovered by defence"`.
  - Every fumble (recovered or not) still sets `is_fumble` independently of `is_turnover` —
    these are deliberately separate columns, restored after being found missing from the new
    schema despite `legacy_play_log` already having had `is_fumble` (a real gap that had been
    silently dropped when this table was designed separately from the migration schema).
- **Penalty classification**: `is_penalty_offense`/`is_penalty_defense` are genuinely separate,
  not derivable from one generic flag — confirmed via the original legacy source columns
  `a_peno`/`a_pend`, which were two distinct columns. `pattern_text` includes the full
  `"...against offence"`/`"...against defence"` suffix, not just the penalty-type name, since
  several types (holding, pass interference) occur on both sides and the bare type name alone
  would collide against the pattern table's unique constraint. Only combinations actually
  observed in real data were seeded — real asymmetry exists (personal foul/offside only ever
  appeared on defense; delay of game/false start/illegal procedure/illegal shift/ineligible
  player downfield only ever appeared on offense in the samples checked).
- **`yards_gained` extraction**: `"AT gain/loss of N yards"` is a cumulative position marker —
  the *last* such mention in a play's text wins, overriding any earlier one. A trailing
  `"FOR gain/loss of N yards"` is an *additional* increment on top of the last AT position, not
  a replacement. Validated two independent ways against real plays: down/distance progression
  (with the first-down caveat above) and field-position delta on the following play (only valid
  when the same team retains possession).
- **`is_first_down`**: computed directly (`yards_gained >= yards_to_go` on the same play row),
  not text-pattern-matched — deliberately has no `sets_first_down` counterpart in
  `play_text_patterns`. Left false for goal-line plays (`yards_to_go` is `NULL` there —
  `"1st & Goal"` has no explicit number — and "first down" doesn't apply the same way when the
  object of the play is reaching the endzone, which `is_touchdown` already captures).
- **Structural special cases in the quarter blocks** (all confirmed against real examples):
  - **Overtime** lives *within* the `4th Quarter` block, under a `<B>Overtime<C>` heading, not
    its own `<BK.>` marker. Plays after that heading get `quarter=5`; the cumulative game clock
    keeps counting past `60:00` rather than resetting.
  - **Blank `side` column** = same possession as the previous play, **except** the very first
    play after any non-play marker line (two-minute warning, quarter-end stat summary), which
    always has an explicit side. Detected structurally, not by enumerating every possible
    marker type: a real play row always starts with a digit (the time); a marker/summary line
    never does.
  - **Kickoff/onsides-kick rows** print no formation letter or field position at all —
    formation is synthesized as `'X'` per explicit instruction (matching the legacy migration's
    own convention for the same situation). The "side" column ambiguity this creates (a blank
    side directly followed by `"KO"`/`"ON"` looks structurally identical to "side is KO/ON") is
    resolved with a negative lookahead excluding those two literal strings from ever matching as
    a side code.
  - **Multi-line scoring plays**: the first physical line ends in its own `<L>`; a continuation
    line (no time/side, just further prose) carries the rest of the description plus the
    `<T>score<C>` suffix. Merged into one play by only accepting an `<L>` as a genuine play
    boundary when it is *not* immediately followed by a lowercase-starting continuation line.
  - **QB benching/replacement announcements** (`"<Z>[Player] benched, and replaced by [Player]
    <C>"`) consume that row's time+side entirely — the real play that follows has no time value
    of its own. Confirmed directly: borrow the time+side from the announcement line and attach
    it to the following line's real play data (field position, down/distance, formation,
    off/def calls, result); the announcement text itself is discarded. Implemented as a
    pre-processing text substitution before the main play regex runs, not folded into that
    regex directly.
  - **`"quarterback flop"` rows** (a clock-killing knee-down at the end of a half/game) have
    field position and down/distance but no formation/off/def columns at all — confirmed these
    should be dropped entirely, not treated as a real play with (necessarily garbage) stats.
  - Confirmed by the user as the complete set of "varying degrees of weird" in this block —
    flops, benchings, touchdowns, safeties — after these two were found. No further special
    cases known to exist as of this writing, though the same caution from §3 (legacy data can
    diverge from current output) applies to any future additions.

---

## 5. Extract Play-by-Play — validation status in detail

Everything below was validated against real files before being trusted; see §3 for why that
discipline matters here specifically.

**Fully validated, parsing-logic level (Python replica of the PHP, tested against real files):**
- Core play-row regex: 143/143 correct on a full real NFLAR game (`NFLAR-PE_s2032_w01_vs_
  Packers.txt`), correctly excluding the one genuine `quarterback flop` line in that file.
- Overtime detection: exact 41/13 regular-quarter/overtime split on a real OT game.
- QB-replacement time-borrowing merge: all three real plays in the test case correctly
  recovered, including the merged one with the borrowed time+side.
- Multi-line touchdown handling: field position, down/distance, cleaned result text,
  `score_after`, and `yards_gained` all independently confirmed correct.
- Yardage extraction rule and the interception-always-0 special case (see §4).
- Kickoff formation synthesis to `'X'`.
- `score_after` extraction and `result_text` cleanup (stripping `<Z>`/`<T>...<C>`/embedded
  `<L>` formatting debris while preserving the actual prose).

**Not yet tested against a live database** (the actual SQL, not just the logic):
- `team_codes` lookup resolution.
- `play_text_patterns` loading and matching against real seeded pattern rows.
- The `game_id` resolution query (franchise + week → `games`, requires Extract Games to have
  already run successfully for that upload's week).
- The upsert itself.

First live test was about to be run by the user in parallel with this document being written —
check back for the outcome before assuming this page is fully working end-to-end.

---

## 6. Other recent fixes worth remembering

- **`current_standings.php`'s second College logo**: first attempt placed it next to the main
  league logo, ignoring an explicit instruction that it should sit *after the league selector*
  (matching where Pro's conference-banner logo naturally appears, later in the page). Corrected
  after direct pushback — a reminder to actually re-check placement instructions against what
  gets built, not just against general visual judgment.
- **`extract_standings.php`'s pre-season detection** (`LIKE '%Week%Schedule%'`) was originally
  unanchored to position within the block, and turned out to match *every* week's Standings
  block, not just genuine pre-season ones — because every turn's Standings block ends with
  *next* week's schedule, not just pre-season turns specifically (a correction to an earlier,
  wrong assumption that only pre-season turns show a schedule at all). Fixed by checking only
  the first ~300 characters of the block, since the real distinguishing signal is *position*
  (a genuine pre-season block has the schedule heading immediately after the header, ~113
  characters in; a normal week has it only after a full table, 2000+ characters in), not mere
  presence of the phrase.
- **Every turn shows that same week's own result and standings, plus *next* week's schedule** —
  not a "previous game" reported one turn late, which was an earlier, incorrect framing that
  needed correcting.

---

## 7. Remaining work / open items

Roughly in the order they're likely to matter:

1. **Finish live-testing `extract_playbyplay.php`** against a real upload end-to-end (see §5).
2. **`team_codes` completion** — two currently-`TBD` gaps the user is filling in manually:
   - The remaining 37 codes from the full 70-code `a_poss` legacy list not yet identified
     (almost certainly belonging to the 8 defunct leagues covered by the historical migration).
     `'IS'` specifically may not need archaeology — likely just Iowa State Cyclones, the 12th
     current NCAA5 franchise, unconfirmed only because they didn't appear as an opponent in the
     one full season checked directly.
3. **`drives` / "Scouting Report - Game Summary" parser** — not started. Structurally distinct
   from `plays` (see the original design doc §1): per-drive, not per-play, for a *different*
   game (next week's opponent's most recent one), with fields like `play_count` and
   `longest_play_text` that have no `plays` equivalent.
4. **Union views for `legacy_play_log` + `plays`** — the existing eight `v_playcall_matchup`-
   style aggregate views only query `legacy_play_log`; once `plays` has real data, those views
   won't reflect any of it. Needs new views built specifically to combine both sources, not a
   retrofit of the existing ones. Flagged to the user as a real, not-yet-addressed gap in how
   useful the play-by-play data will be until this exists.
5. **Historical Standings page**, **Dadabik DEV area configuration** — mentioned earlier in the
   project as future work, not touched during this phase.
6. **`TEAM_PAGE_STATIC_ID` in `current_standings.php`** still needs updating once `team.php` is
   formally registered as a static page in Dadabik.
7. Consider whether other legacy `n_playbyplay` columns not covered above (`a_bado`,
   `a_twodowns`, `a_situationno`, `a_negative`, `a_playtype`, `n_offcoach`, `n_defcoach`) have
   any bearing on the live parser — not investigated in this phase; may be legacy-only concepts
   with no live-text equivalent (similar to `starting_qb_benched`, which turned out to be
   genuinely unrecoverable from current turn text — see the original design doc).

---

## 8. Live output file inventory (this phase)

All in `/mnt/user-data/outputs/` as of this writing:

- `extract_standings.php`, `extract_games.php`, `extract_playbyplay.php` — the three staged
  parser pages.
- `play_text_patterns_seed.sql` — schema + 27 seeded, cross-validated text patterns.
- `team_codes_seed.sql` — schema + confirmed codes (23 NFLAR, 11 NCAA5, 2 resolved historical,
  37 TBD placeholders pending user lookup).
- `plays_missing_flags.sql` — live-DB `ALTER TABLE` statements for the seven `is_*` columns
  found missing from `plays` partway through this phase.
- `new_schema.sql` — kept in sync with every live-DB change made this phase (should always
  match the actual database state exactly; treat any divergence as a bug to fix, not a
  reason to trust the live DB over this file or vice versa without checking).
- `current_standings.php`, `team.php`, `home.php`, `bowl_records.php`, `operational_hooks.php`
  — carried over from earlier phases, `current_standings.php` updated this phase (§6).
