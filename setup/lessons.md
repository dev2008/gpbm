# Gameplan PBM — Live Parser Build Notes

**Purpose:** companion to `schema_design_proposal.md`, which covers schema design and the
historical migration (`f_games`/`fc_franchises`/etc. → `games`/`franchises`/`franchise_honors`/
etc., 9,969 games, and the 253,450-row `legacy_play_log` migration). That document stops once
the schema exists and historical data is loaded. This one picks up from there: building the
**live, staged extraction pages** that turn newly-uploaded turn files into rows in that schema,
plus every real bug, structural surprise, and design decision made along the way. If starting a
fresh conversation, load all three files — this one is meaningless without the schema context
in the first, and open/upcoming work now lives separately in `todo.md`, not here.

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
  franchise's own game only) into `plays`. **Live-tested and working** as of this writing —
  multiple games across both NFLAR and NCAA5 processed successfully, 1070 total rows, a
  thorough set of invariant-checking queries run directly against the live data all coming
  back clean (see §5 for the full validation detail, and the three real bugs this first live
  testing round found and fixed).

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
  migration-era schema: `is_fumble`, `is_interception`, `is_penalty`/`is_penalty_offense`/
  `is_penalty_defense`, `is_sack`, `is_hurry`, `is_blitz_pickup`, `is_blitz_no_pickup`,
  `is_safety`, `is_incomplete`, `is_first_down`. All confirmed necessary against real data or
  explicit instruction (see §4). `is_interception` was the last of these added, well after the
  others — see §8 for the full story of that gap.
- `franchises.abbr` was **dropped entirely**, replaced by `team_codes` (see §3).
- `v_playcall_formation_all` / `v_playcall_matchup_all` / `v_playcall_matchup_formation_all` /
  `v_relevant_offense_all` / `v_relevant_offense_formation_all` / `v_relevant_defense_all` /
  `v_relevant_defense_formation_all` — Feature 1 complete, both stages, all seven union views
  live and verified; see §8. The original eight legacy-only views are untouched throughout.
- `extract_games.php`'s `games.label` — fixed and backfilled; was silently using today's
  real-world date instead of the turn's actual season on every live-created game. Found while
  verifying Feature 1 stage 2, unrelated to Feature 1 itself; see §9.

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
- **`team_codes` deliberately has no `franchise_id` or `league_id` column.** Confirmed directly
  (not assumed): codes are globally consistent, not league-scoped — the same code means the
  same team everywhere it's used, across every league. `CI` (Cincinnati Bengals) was checked
  directly against real NFLAR games spanning 2010-2028 and a real NFLC game, and is the same
  code both places. Given that, there's no per-league ambiguity to design around in the first
  place, and no `league_id` column is needed. (An earlier version of this reasoning leaned on
  a "cross-league collision" example — the same code supposedly meaning different teams in
  different leagues — but that example was built on data that later turned out to be simply
  wrong, not a real collision; see §3's note on `AF`. Worth having corrected here rather than
  leaving the disproven version standing.)
  Some codes never have a current franchise to attach to at all, though — confirmed directly:
  `VT` (Virginia Tech Hokies) is a real, historically-used NCAA5 code with no current
  `franchises` row, since the league's own 12-team membership changed at some point (Iowa State
  Cyclones occupies that slot now). `BA` (Baltimore Ravens) and `VI` (Virginia Cavaliers) are
  the same situation for different reasons — both real, both confirmed against actual games,
  neither belonging to a currently-active league.
  A `UNIQUE KEY (code, team_name)` constraint makes future franchise relocations safely
  upsertable (`INSERT ... ON DUPLICATE KEY UPDATE code_id=code_id`) without needing to check for
  an existing row first — confirmed the *common* case for a relocation is landing on a
  combination some other franchise used earlier (24 franchises drawn from a 32-team real-world
  pool), not a genuinely new pairing.
  Also deliberately does NOT try to normalize different eras of the same real-world franchise
  into one canonical name — confirmed necessary via a genuinely messy real case: `WR`
  ("Washington Redskins"), `WT` ("Washington Team"), and the current name (not yet needed under
  any code) are three different eras of the same franchise, each with its own code at the time.
  `team_codes`' job is only "what was this code called when it was used," not "what's the
  current canonical name" — that reconciliation already belongs to `franchises`/
  `franchise_name_history`, and trying to duplicate it here would just create a second,
  competing source of truth for something already handled elsewhere.
- **Historical `legacy_play_log` and live `plays` are staying separate tables**, not being
  merged or backfilled into one. `legacy_play_log` can't cleanly gain real `game_id` links for
  most of its 253,450 rows (most belong to leagues/seasons never fully migrated into `games`).
  Plan for offense/defense matchup views going forward: build new views that UNION both tables,
  rather than retrofitting the existing eight `v_playcall_matchup`-style views (which currently
  only query `legacy_play_log` and won't see anything `plays` picks up going forward) — **not
  yet built**, tracked as an open backlog item in `todo.md`.

---

## 3. Hard-won lessons — worth internalizing before continuing this work

**Governing principle, stated explicitly by the user after the `CB` case below: when the user
asserts something, the default response should be a query to validate that assertion, where
one is possible — not accepting it and moving on.** This isn't about distrust; it's that
confidence and correctness turned out to be independent in this project often enough that the
gap is worth checking by default rather than as an exception. Several of the lessons below are
specific instances of this same rule playing out — `CB` is the clearest one (a direct,
confident correction that turned out to be wrong, precisely because it was never checked the
way the systematically-found errors were), but the "0 start" and `AF` cases below are really
the same principle applied to *this assistant's own* assumptions and inherited data, not just
the user's. The rule cuts both ways and applies regardless of source.

**Reusable testing methodology, proven during the Games build: test every parser against the
full, agreed matrix of week types for both leagues, not just a couple of "normal" samples.**
The specific regime settled on: NCAA5 weeks 0, 1, 11, 12, 13 (pre-season, a regular week, the
*last* regular week specifically — confirmed to have its own oddities, distinct from any other
regular week — playoffs, bowls) and NFLAR weeks 0, 1, 16, 17, 18, 19, 20 (the same shape, plus
the extra playoff/bowl rounds NFLAR has that NCAA5 doesn't). This wasn't a formality — running
`extract_games.php` against all 12 cases directly caught multiple real bugs that a couple of
"looks fine" spot checks never would have: the DOTALL/line-ending issue (one file used plain
spaces where every other sample used real newlines), the header-detection fragility (tag
ordering varied between files), the blank-call-field and hyphen-placeholder merging bugs (both
silently combined two adjacent games into one corrupted match, only visible by checking an
unusual match length), and the "Consolation Gold" mismatch (later found to be more nuanced than
first recorded — see §3 below for the correction). None of these were reachable from NFLAR
week 1 alone, which is exactly why the fuller matrix mattered. Worth explicitly re-running the
same regime — or a league-appropriate equivalent — against any *future* parser built against
this same turn-file
format (the still-backlogged `drives`/Scouting-Report parser is the obvious next candidate),
rather than assuming a couple of successful spot checks are representative. Note also that this
methodology itself was never separately captured in this document until asked about directly —
worth remembering that a valuable, proven process can go undocumented even when its individual
*results* (the bugs it caught) are written up in detail; the process itself deserves its own
explicit record, not just its outputs.

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

### Correction to the lesson below, found later: the source itself uses two different forms
### for the same name, depending on context — not simply "shorthand vs. literal text."
Originally recorded as a straightforward case of using an abbreviated outline term (`"Cons
Gold"`) as if it were literal source text, when the actual game-result section header reads
`"Consolation Gold"`. That fix was correct, but the earlier framing wasn't the full picture:
directly comparing a Week 12 file (showing the upcoming bowl pairings, before they're played)
against the following Week 13 file (showing the actual results for those same games) confirmed
the game engine genuinely prints **both** forms — the abbreviated `"Cons Gold"`/`"Cons
Silver"`/`"Cons Bronze"` in the schedule-preview section at the end of League Report, and the
full `"Consolation Gold"` etc. in the actual game-result section header the following week.
The original outline wasn't careless or fabricated — it was accurately drawn from the preview
section, just a different context than the one the parser actually needed to match against.
Confirmed directly (not assumed) that this poses no risk to `extract_games.php` as it
currently stands, for two independent reasons: the schedule-preview text always falls after
the last real game match's end position, already excluded by the existing header-detection
boundary regardless of which form appears there, and `KNOWN_HEADERS` no longer contains the
abbreviated form at all following the original fix, so it wouldn't match even if that boundary
were ever removed. Lesson, restated more precisely: a discrepancy between two readings of "the
same" text doesn't always mean one reading was wrong — sometimes the source itself genuinely
varies by context, and that's worth checking directly (a real, matched-pair comparison, not
just re-reading the same excerpt more carefully) before concluding a mistake was made on one
side or the other, on the assumption that only one framing could ever be correct.

### Don't treat your own abbreviated summary of something as if it were the literal source text.
A game-type mapping used `"Cons Gold"` as a header-matching key, taken from an earlier
*shorthand outline* rather than verified against real turn text. The actual game-*result*
section header text is `"Consolation Gold"` (confirmed directly: `"<B>Consolation
Gold<L.45.1>"`). The shorthand had never been checked against a real file before being used as
a literal pattern. (See the correction directly above this entry — the fix itself was right,
but the outline's form turned out to be independently real too, just from a different part of
the source than the one being matched against.)

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

### A zero-row check only catches a code that was never used — it can't catch one that's real
### but mislabeled, which is a genuinely more dangerous failure mode.
While resolving `team_codes`' remaining unknowns, `SELECT COUNT(*) FROM n_playbyplay WHERE
a_off='JJ'` (and later `'AT'`, `'CP'`) came back at zero, confirming those codes from the
original `franchises.abbr` data were simply never used — safe to drop. `'AF'` looked the same
kind of case at first, but came back with 4426 real rows — under the old, inherited label
"Carolina Panthers." Checking the actual games it appeared in showed it's really Atlanta
Falcons. A zero-row check would never have caught this on its own, since the code itself is
genuinely real and heavily used; only cross-referencing the specific games it appeared in
exposed the mislabeling. Worth remembering this means `franchises.abbr` may have carried other
silent mislabelings beyond the ones already found — a present, non-zero row count is necessary
but not sufficient evidence that a code's *name* is correct, only that the code itself is real.

### Resolving an unknown code by joining directly against `f_games` only works if a `game_id`-
### style link actually exists for that data — some codes belong to leagues that never got one.
An early attempt to resolve remaining unknown codes joined `n_playbyplay` to `f_games` on
league/season/week and returned nothing for `'BA'`, even with no other join conditions
narrowing the result. Root cause: `BA` belongs entirely to `NFLBC`, one of the 8 defunct
leagues whose raw play-by-play text was migrated into `n_playbyplay` without ever getting
matching game-level rows in `f_games` (consistent with the original migration notes: "most
leagues have no leagues/franchises/games rows"). No join against `f_games` was ever going to
resolve it, regardless of how it was constructed — confirmed directly by finding a real turn
file for that league (`NFLBC-PS Turn 14, "Pittsburgh Steelers vs Baltimore Ravens"`), not by
refining the query further. When a cross-reference against migrated data returns nothing at
all rather than an ambiguous result, worth checking whether the target table has any coverage
for that data's source *at all* before assuming the join logic itself is wrong.

### "Complete" against one source column doesn't mean complete against the full picture —
### a sparse column can hide codes that only ever appear in the columns that are always filled.
`team_codes` was declared fully resolved (75 codes, zero `TBD`s) based on covering every
distinct value from `n_playbyplay.a_poss` — but `a_poss` is the *sparse* possession column,
populated only when possession is shown explicitly. `legacy_play_log`'s `offense_team_code`/
`defense_team_code` columns are always populated, by contrast, and a direct coverage check
against all three of `legacy_play_log`'s code columns (not just the one `team_codes` was
originally built from) turned up two more real, heavily-used codes `a_poss` alone had never
surfaced: `CI` (2323 rows) and `US` (304 rows). Both resolved the same way as everything else
— `US` is USC Trojans (NCAA6), `CI` is Cincinnati Bengals — confirmed directly to be the same
code across both NFLC and real NFLAR games (2010-2028), not a separate NFLAR-specific code as
first assumed (see the next lesson below for what that original assumption actually was).
Lesson: "confirmed against every value in column X" is a claim about column X
specifically, not about the underlying concept in general — worth checking whether a more
complete, always-populated source exists before calling something fully resolved.

### A resolution the user provided directly still needs the same validation as one found by
### systematic checking — being confident isn't the same as having actually checked.
`CB` was used early on to resolve a genuine duplicate-name collision (`CB`/`CH` both showing
"Cincinnati Bengals"/"Chicago Bears" ambiguously) via direct correction, and from that point
on was treated as settled — unlike `JJ`/`AT`/`CP`, which were caught by the same systematic
`SELECT COUNT(*) FROM n_playbyplay WHERE a_off=...` check applied across the board. `CB` never
actually got that check, precisely because it had already been "resolved." When it finally was
checked, it came back with zero rows — the same signature as the three already-known unused
codes, meaning it was never real either. Cincinnati Bengals' actual code was `CI` all along
(confirmed directly against real NFLAR games across 2010-2028, not assumed), which was already
sitting in the table under an *apparently* different, NFLC-specific meaning at the time —
itself a second, compounding error: assuming two same-name entries under different codes must
mean two different situations (one per league), rather than checking whether they were simply
the same underlying fact recorded twice. Lesson: a code's provenance (systematically checked
vs. directly asserted vs. inherited from old data) doesn't change how much validation it
actually needs — every code deserves the same zero-row check regardless of how it entered the
table, and a plausible-sounding explanation for why two entries differ (like "these must be
league-specific") is itself worth checking before being written down as settled fact.

### A value can be extracted correctly and still never reach storage — parsing-logic
### validation alone can't catch a field the schema never had a column for.
`formation` was computed correctly by the parser the entire time the play-by-play work was
underway — including the `'X'` kickoff-synthesis rule — but `plays` never actually had a
`formation` column, and the computed value was silently dropped every time, all the way
through to the first live test. Every earlier validation pass (see §5) checked whether
*extraction* was correct against real text, never whether every extracted field actually had
somewhere to go in the final output — a gap invisible to Python-replica testing against raw
files, only found by looking at real rows in the actual database. Lesson: validating that a
parser extracts the right values is a different claim from validating that those values
survive all the way to storage — the second needs checking against the live schema and real
output specifically, not just the parsing logic in isolation.

### `NULL` should mean "unknown," not "the value is zero" — and the two get confused easily
### when a value is genuinely absent from the source text rather than present-but-unparsed.
`yards_gained` came back `NULL` for incomplete passes and for the `"no gain"` phrasing, in both
cases because no "gain/loss of N yards" phrase existed in the text for the extraction regex to
find anything to parse — but the real answer in both cases is unambiguously known (0), not
missing. The `"no gain"` case was also structurally different enough (`"for no gain"`, with no
`"of"`/`"yards"` at all) that it needed its own regex branch, not just a value-substitution
fix — and a compound play combining an `"at no gain"` position marker with a later `"for gain
of N"` increment was silently dropping the first segment entirely before the fix, arriving at
the correct total only by coincidence in the one case checked (0+3 and just-3 both equal 3).
Lesson: absence of a match in extraction isn't automatically "unknown" — worth checking
whether the specific play type has a definite, known value regardless of whether the text
states a number explicitly, and treating a coincidentally-correct compound-play result as
confirmation that the underlying logic is right is exactly the trap the "0 start"/`AF` lessons
already warned about, just recurring in a new form.

### A hook only ever runs once, at insert time — data ingested before a hook gets a piece of
### logic (or before a bug in that logic is fixed) has no way to pick that fix up retroactively.
All 6 NCAA5 uploads had `franchise_id = NULL`, silently excluding every one of them from Extract
Play-by-Play's dropdown. The hook's franchise-resolution logic (step 3b) was present and
structurally sound at the time this was investigated — not a live, currently-active bug — the
real cause was that these specific uploads were ingested before that step existed in the hook
(or before a fix to it), and a hook has no mechanism to reach back and reprocess records that
already exist. Fixed with a one-off backfill page reusing the hook's exact logic, rather than
waiting to notice this again the next time a hook gains new resolution logic. Worth remembering
as a general category: any time hook logic changes, existing records may need an explicit,
one-off backfill pass — the hook firing correctly from that point forward doesn't retroactively
fix anything already sitting in the table.

### The same "computed but never stored" failure shape recurred a second time, in a place the
### first occurrence's own fix should have made easier to catch, not harder.
Found while scoping Feature 1 (union views for `legacy_play_log` + `plays`): `plays` had no
`is_interception` column at all, only a combined `is_turnover` plus a separate `is_fumble` —
even though `play_text_patterns.sets_interception` had existed the whole time, and
`apply_play_text_patterns()` in `extract_playbyplay.php` was already computing it correctly
into the `$flags` array on every single call. The value simply never got read back out of that
array into the upsert — the exact same shape of bug as the missing `formation` column above,
right down to being invisible to parsing-logic validation for the identical reason (extraction
was correct; storage was the gap). Worth being honest about the recurrence rather than treating
it as a one-off: the first occurrence's fix added the missing `formation` column and, per its
own lesson, should have prompted a check of every other computed-value-to-storage path at the
time — it didn't, and this is what slipped through. Fixed properly rather than worked around:
added `plays.is_interception`, backfilled the 1070 pre-existing rows by joining
`play_text_patterns` the same way the parser itself does (substring match on `sets_interception
= 1` rows), patched both places `extract_playbyplay.php` needed it (the upsert's column list /
`VALUES` / `ON DUPLICATE KEY UPDATE`, and the bound-params array feeding it), and updated
`new_schema.sql` to match. See §8 for the full build and live verification. A cheap general
check worth running any time a new `is_*`/`sets_*` pattern flag is added in the future: grep
the parser for every place `$flags[...]` is read, not just where it's written — the write side
working doesn't confirm the read side does too.

### An unverified assumption pattern-matched against nearby context and got stated as fact —
### the correction came from the user re-checking, not from re-deriving it independently.
While walking through Feature 1 stage 2's live verification, a claim was made that "the live
turns you've been parsing are from season 2032" — stated with full confidence, based purely on
the project workspace's sample turn files happening to be named `s2032`. No query was ever run
to check what season the actual live `plays` rows belonged to; the sample filenames were the
only "evidence," and they aren't the same thing as the real uploads that produced the real
data. The user's own query (joining `plays` to `games.label`) surfaced "2026" instead, which
didn't match that guess either — and turned out to be a third, unrelated thing entirely: a real
bug in `extract_games.php`'s label construction (see §9), while the turns' actual seasons were
2034/2038/2039, confirmed only once someone actually checked. The governing principle already
established in this document — when someone asserts something, the default is a query to
validate it, not acceptance — applies exactly as much to Claude's own inferences drawn from
surrounding context as it does to the user's assertions or Claude's own prior work. This is the
same category of mistake as the "0 start"/`AF` cases earlier in this section, just committed by
a different party within this exact conversation. Worth remembering: pattern-matching to the
closest available reference material sitting in view (here, sample files in the project
workspace) is not the same thing as checking the actual data, even when the match feels obvious.

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

**Live database interaction — now fully tested and confirmed working, after three real bugs
were found and fixed during the process (all detailed below and in §3):**
- `team_codes` lookup resolution, `play_text_patterns` loading/matching, `game_id` resolution,
  and the upsert itself all confirmed working correctly against real uploads.
- Multiple games processed successfully across both NFLAR and NCAA5 (once the NCAA5-specific
  `franchise_id` gap below was fixed), reaching 1070 total rows in `plays`.
- A thorough set of invariant-checking queries run directly against the live data, all
  confirming clean: `is_first_down=1` never paired with `yards_gained < yards_to_go` (the
  computed comparison holds with zero exceptions); `is_touchdown`/`is_fumble`/`is_sack`/
  `is_hurry`/`is_blitz_pickup`/`is_blitz_no_pickup`/`is_safety` never set without the
  corresponding keyword actually present in `result_text`; and `is_turnover=1` never occurring
  without either `is_fumble=1` or an interception mentioned in the text (the more precise
  version of this last check — `is_turnover=1 AND is_fumble=0 AND result_text NOT LIKE
  '%inter%'` — is the one that actually matters, since turnovers legitimately come from two
  independent sources; a plain `NOT LIKE '%inter%'` check alone will show real, expected rows
  for fumble-turnovers and shouldn't be read as a failure).

**Three real bugs found during this first live testing round, all now fixed:**
1. **`formation` was computed correctly but never stored anywhere at all** — not a parsing
   bug, a genuine schema gap: `plays` never had a `formation` column in the first place,
   despite the parser extracting it (including the `'X'` kickoff synthesis) since the
   play-by-play work began. The value was computed, then silently dropped every single time,
   invisible to all the earlier parsing-logic validation because that validation only checked
   whether extraction was correct, never whether every extracted field had somewhere to go in
   the final output. Column added; parser fixed to actually include it in the returned array
   and the upsert (see §3 for the general lesson this represents).
2. **`yards_gained` was `NULL` for plays where it should have been the known value `0`** —
   confirmed for incomplete passes (no "gain/loss of N yards" phrase appears in text like
   `"pass thrown away, incomplete"`, but the real answer is unambiguously 0, not unknown) and
   separately for `"no gain"` phrasing (`"HB run for no gain"`), which uses no `"of"`/`"yards"`
   at all and so was never matched by the extraction regex, returning `NULL` outright. Worse,
   a compound play combining `"at no gain"` with a later `"for gain of N"` mention silently
   dropped the first segment entirely, only arriving at the correct final total by coincidence
   in the one example checked before the fix — a genuinely different, more dangerous failure
   than the simple missing-value case, since it could have produced a *wrong* non-null number
   for some other compound play, not just a missing one. See §3 for the general NULL-vs-0
   lesson this represents.
3. **All 6 NCAA5 uploads had `franchise_id = NULL`**, which is why none of them appeared in
   the dropdown at all (it requires `franchise_id IS NOT NULL`). The ingestion hook's own
   franchise-resolution logic (step 3b) is present and structurally sound — not a live bug in
   current code — but hooks only ever fire once, at insert time, so any upload ingested before
   that step existed (or before a later fix to it) is left permanently stuck with no way to
   retroactively trigger the hook again. Fixed with a one-off backfill page
   (`backfill_franchise_id.php`) that reuses the hook's exact same regex/lookup logic against
   any upload still missing `franchise_id`, rather than reimplementing it slightly differently.

**Confirmed since: both sides of the same game report byte-for-byte identical play-by-play
text.** Directly verified against a real matched pair — one coach's own `.txt` turn and the
other participant's `.eml` turn, same game (NFLAR, season 2031, week 11, Eagles vs Vikings).
All four quarter blocks matched exactly, character for character, between both files. This
confirms the multi-coach dedup mechanism works as designed for exactly the reason assumed: the
dropdown-exclusion query keys off `game_id` (via `games`, not `upload_id`/`franchise_id`
directly), so once either participant's turn has been processed for a given game, the other
participant's turn for that same game simply never appears in the dropdown — and even if it
somehow did get processed anyway, `plays`' `UNIQUE KEY (game_id, quarter, play_seq)` would
upsert cleanly rather than duplicate, since both sides' texts produce identical play sequences.

**Also confirmed in the same investigation: `.eml` files are handled correctly by the existing
pipeline with no changes needed.** A real `.eml` turn report (forwarded email, full SMTP
routing/authentication headers, `multipart/mixed` MIME structure) was checked directly. Two
things make it work already: the actual turn content is a `text/plain; charset="us-ascii"` part
with `Content-Transfer-Encoding: 7bit` — meaning no base64/quoted-printable decoding is needed,
it's genuinely plain ASCII text sitting behind MIME boundary markers and a large block of
routing headers. And critically, the file already contains the `<STARTREP>` marker the
ingestion hook already trims at — everything before it (however much header/routing noise
precedes it) gets stripped the same way regardless of whether that's ordinary Gmail chrome or a
full raw SMTP header block. Confirmed directly: trimming at `<STARTREP>` on the real `.eml` file
produces output identical in shape to a normal `.txt` upload, with all expected `<BK.>` blocks
present.

**A fourth real bug, found later — not part of the first live testing round above, surfaced
instead while scoping Feature 1's union views:** `plays` was missing `is_interception`
entirely, the same "computed but never stored" shape as bug 1 (`formation`) above. Full detail,
fix, and live verification in §3 and §8 — noted here separately, and dated after the fact, to
keep this section's own timeline honest rather than folding it into the "three bugs" list above
as if it had been caught in that same round.

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

## 7. Live output file inventory (this phase)

All in `/mnt/user-data/outputs/` as of this writing:

- `extract_standings.php`, `extract_games.php`, `extract_playbyplay.php` — the three staged
  parser pages.
- `play_text_patterns_seed.sql` — schema + 27 seeded, cross-validated text patterns.
- `team_codes_seed.sql` — schema + 76 fully-resolved codes (22 NFLAR, 12 NCAA5 — complete —,
  and 42 historical/other, covering both currently-active franchises and confirmed-real
  entries with no current franchise at all). No `TBD` placeholders remaining as of this
  writing — every code from the original 70-code legacy list has been either resolved or
  confirmed never actually used and dropped, AND separately verified complete against all
  three of `legacy_play_log`'s code columns (not just the sparse one the original list came
  from — see §3, this check is what surfaced `CI`/`US`). `CB` (originally used to resolve a
  duplicate-name collision, later found to have zero real rows) was removed after the fact —
  see §3 for why it slipped past the same validation the other unused codes got. See §3 also
  for the two distinct failure modes (unused-and-safe-to-drop vs. real-but-mislabeled) caught
  while resolving these.
- `plays_missing_flags.sql` — live-DB `ALTER TABLE` statements for the seven `is_*` columns
  found missing from `plays` partway through this phase.
- `add_formation_column.sql` — live-DB `ALTER TABLE` statement for the `formation` column
  found missing entirely (see §3) during the first live test of `extract_playbyplay.php`.
- `backfill_franchise_id.php` — one-off admin page, reuses the ingestion hook's exact
  franchise-resolution logic against any upload still missing `franchise_id` (see §3 for why
  this can happen — a hook only ever runs once, at insert time).
- `new_schema.sql` — kept in sync with every live-DB change made this phase (should always
  match the actual database state exactly; treat any divergence as a bug to fix, not a
  reason to trust the live DB over this file or vice versa without checking). Updated again
  in the Feature 1 phase to add `is_interception` — see below and §8.
- `current_standings.php`, `team.php`, `home.php`, `bowl_records.php`, `operational_hooks.php`
  — carried over from earlier phases, `current_standings.php` updated this phase (§6).

**Feature 1 (stage 1) additions, added later than the rest of this inventory — see §8:**
- `add_is_interception_column.sql` — live-DB `ALTER TABLE` adding `plays.is_interception`,
  plus the backfill for the 1070 pre-existing rows and its verification queries.
- `union_views_playcall.sql` — the three `v_playcall_*_all` union views plus their shared
  `v_plays_normalized` helper view, and verification/test queries.
- `extract_playbyplay.php` — updated in place (not a new file) to also write
  `is_interception` going forward; the version in this inventory's first entry above is now
  stale.

**Feature 1 (stage 2) additions — see §8:**
- `check_relevant_teams_franchise_mapping.sql` — diagnostic confirming all three tracked
  teams (PE/MV/PI) round-trip cleanly between `legacy_relevant_teams.team_code` and current
  `franchise_id` before the no-schema-change join was used.
- `union_views_relevant.sql` — extends `v_plays_normalized` (additive), adds the
  `v_relevant_teams_franchise` helper, the four `v_relevant_*_all` union views, and
  verification/test queries.

**`extract_games.php` `games.label` bug fix — see §9:**
- `check_live_play_season_resolution.sql` — first diagnostic, ruled out the "plays matched
  onto pre-existing historical games" hypothesis.
- `check_games_label_bug.sql` — confirmed the bug was cosmetic-only (48/48 rows agreed between
  the FK-derived season and `raw_uploads`' independently-resolved one).
- `extract_games.php` — updated in place to resolve the label's season from `seasons.year`
  instead of `date('Y')`; the version in this inventory's first entry above is now stale.
- `backfill_games_label.sql` — corrects the label on all 48 already-affected games.

---

## 8. Feature 1 — union views for `legacy_play_log` + `plays` (both stages)

### Stage 1: the three `v_playcall_*` views

**Scope:** the three `v_playcall_*`-style aggregate views only (`v_playcall_formation`,
`v_playcall_matchup`, `v_playcall_matchup_formation`). The four `v_relevant_*` views and their
`v_relevant_current_season` helper are a deliberately separate stage 2, covered below.

**Design decisions, confirmed with the user before building:**
- **Special-teams filtering on the `plays` side uses formation only**
  (`formation NOT IN ('P','X','F')`) — `plays` has no `play_category` column at all, unlike
  `legacy_play_log`. The formation encoding, including the synthesized `'X'` for kickoffs, was
  directly confirmed to match between the two tables by reading real turn files, not assumed.
- **Interception filtering required a real parser fix, not a proxy.** The obvious shortcuts —
  filtering on the broader `is_turnover`, or on `is_turnover=1 AND is_fumble=0` as an
  interception stand-in — were both rejected once it became clear `is_interception` should
  simply exist and didn't, purely because of a storage gap (see §3, §5). The proper fix was
  small enough (one column, one backfill, a two-line parser patch) that there was no good
  reason to ship an approximation instead.
- **`sport_type` for `plays` rows is resolved via `game_id → games.week_id → weeks.season_id →
  seasons.league_id → leagues.sport_type`**, not via the nullable `plays.offense_franchise_id`
  — every column in that join chain is `NOT NULL`, so this path can't produce a silent NULL
  `sport_type` the way the franchise-based path could.
- **New views use an `_all` suffix** (`v_playcall_formation_all`, etc.). The original three
  legacy-only views are untouched, per the explicit requirement that they keep querying
  `legacy_play_log` exclusively.
- **A shared helper view, `v_plays_normalized`**, carries the join chain above plus
  legacy-style column aliases (`formation` → `formation_code`, `off_call` →
  `offense_call_code`, `def_call` → `defense_call_code`) so the three target views don't each
  repeat the same four-table join — the same composition pattern already used elsewhere
  (`v_current_coach` → `v_current_standings`; `v_relevant_current_season` → the four
  `v_relevant_*` views).
- **Raw rows are unioned before aggregating, not two pre-aggregated `avg_yards` values
  averaged against each other** — the latter would be an unweighted mean of means, wrong as
  soon as `plays` carries any real weight of its own.

**The `is_interception` gap (see §3 for the general lesson):** scoping this feature surfaced
that `plays` had no `is_interception` column — only a combined `is_turnover` plus a separate
`is_fumble`, with no way to isolate "interception" the way `legacy_play_log` does. Root cause:
`play_text_patterns.sets_interception` had always existed and `extract_playbyplay.php` was
already computing it correctly on every call, but the computed value was never read back out
into the upsert — the same failure shape as the earlier missing `formation` column. Fixed
properly: `add_is_interception_column.sql` adds the column and backfills the 1070 existing
rows via the same pattern-table join the parser itself uses, then cross-checks the result two
independent ways (against the literal `"intercepted"` substring rule, and against the
already-validated `is_turnover=1 AND is_fumble=0` invariant) — both checks came back clean,
zero mismatches. `extract_playbyplay.php` was patched at both places that needed it (the
upsert's column list / `VALUES` / `ON DUPLICATE KEY UPDATE`, and the bound-params array), so
every future extraction now writes `is_interception` correctly. `new_schema.sql` updated to
match.

**Live verification results (run in this order — column fix, then views):**
1. `add_is_interception_column.sql`'s two cross-checks both returned all zeros — the backfill
   and the parser fix agree with each other and with the independently-confirmed text rule.
2. `v_playcall_formation_all` vs. the original `v_playcall_formation`, same `formation_code =
   'W' AND offense_call_code = 'CW'` example from the task brief: `pro` unchanged at 52/4.17
   (no live pro data matches that key yet — expected, still early data), `college` moved from
   1035/5.07 (legacy-only) to 1048/5.01 (combined) — 13 real plays from `plays` correctly
   folded into the aggregate, with `avg_yards` recalculated across the full unioned set rather
   than averaged against the old figure.
3. 842 of the 1070 rows in `plays` survive the filters in `v_plays_normalized` — the right
   ballpark once special teams, penalties, interceptions, and fumbles are all excluded, same
   as `legacy_play_log`.
4. `sport_type` resolves for every single row in `v_plays_normalized` — 155 pro + 915 college
   = 1070, exactly matching the known total, confirming the join chain never produces a silent
   NULL bucket.
5. Grain check on all three `_all` views (`GROUP BY` the view's own key, `HAVING COUNT(*) > 1`)
   — all three came back empty, confirming one row per matchup as required.

### Stage 2: the four `v_relevant_*` views

**Scope:** `v_relevant_defense_current`, `v_relevant_defense_formation_current`,
`v_relevant_offense_current`, `v_relevant_offense_formation_current`, and their
`v_relevant_current_season` helper — all four left completely untouched, per explicit decision
below, despite a real defect found in them.

**A genuine design defect found in the existing views before any building started.** The user
supplied the original legacy `gplan_main` structure — `n_s_pe_off`/`n_s_pe_def` (+ `_f`
formation variants), and the same for `pi`/`mv` (owner codes for the three tracked
owner/franchise pairs: PE = Philadelphia Eagles, PI = Pittsburgh Panthers, MV = Minnesota
Vikings). Every one of those tables carries `season` as a plain data column across full
multi-season history, with no restriction at the table level at all — filtering by year was
always an end-user, UI-level concern. The current `v_relevant_current_season` helper instead
computes `MAX(season)` per team and every `_current` view hard-joins on equality against it —
meaning these views can only ever surface each team's single most recent season, with no way to
reach any other one. Confirmed directly by reading the actual view SQL, not assumed. This is a
real regression relative to the original design, not a simplification.

**Decision: leave the old views and their defect alone; build stage 2 correctly from scratch.**
The "untouched eight" requirement was originally about not retrofitting the union onto them,
but touching them to fix this separate, real defect was raised as an option anyway — the user's
call was to leave them exactly as they are and get stage 2 right instead, rather than change
behavior on views that may already be depended on elsewhere. New views expose `season` as a
plain, unrestricted, filterable column, matching the legacy `n_s_*` design exactly — no
"current" restriction, and no need for an equivalent to `v_relevant_current_season` at all,
since relevance is about which *teams* are tracked, not which *season*.

**The `plays`-side relevance-matching problem, and how it was resolved.** `legacy_relevant_teams`
identifies relevant teams by text `team_code` (matching `legacy_play_log` natively); `plays`
identifies teams by `franchise_id`, a completely different identity system, with no direct link
between the two. The only bridge available is `franchises.label → team_codes.team_name → code`
— a text match on the franchise's *current* name, which is fragile in general (the project's own
`team_codes` build already surfaced a real case, the Washington Redskins/Washington Team
franchise, where the current name had no code seeded for it at all — a silent, zero-match
failure were it to be hit here). Rather than assume this fragility didn't apply to the three
specific teams actually in play, it was checked directly: a round-trip diagnostic
(`check_relevant_teams_franchise_mapping.sql`) confirmed all three relevant teams
(`PE`→Philadelphia Eagles/franchise_id 2015, `MV`→Minnesota Vikings/franchise_id 2019,
`PI`→Pittsburgh Panthers/franchise_id 5008) resolve cleanly 1:1 in both directions, no `NULL`s,
no fan-out. Given that clean result, the no-schema-change join was used rather than adding a
`legacy_relevant_teams.franchise_id` column — the schema-change option remains the right call if
a future relevant team's current name ever lacks a `team_codes` entry, and is worth re-checking
any time a new row is added to `legacy_relevant_teams`.

**Build:** `v_plays_normalized` (stage 1's helper) extended additively with `league_code`,
`season`, `offense_franchise_id`, and a derived `defense_franchise_id` (whichever of
`games.home_franchise_id`/`away_franchise_id` isn't the offense side, explicitly `NULL` rather
than guessed when `offense_franchise_id` itself is `NULL`) — purely additive, so stage 1's three
views needed no re-verification. A new small helper, `v_relevant_teams_franchise`, resolves each
`legacy_relevant_teams` row to its currently-resolvable `franchise_id` via the validated join
above. The four `_all` views union `legacy_play_log` (filtered via `legacy_relevant_teams`
directly, no season restriction) with `v_plays_normalized` (filtered via
`v_relevant_teams_franchise`), grouped by `league_code, team_code, season[, formation_code],
play_call` — one row per key, `season` now a real dimension instead of a single forced value.

**Live verification results, and a real, concrete illustration of the defect this stage fixed:**
1. `season_count` per team in the new `v_relevant_offense_all`: PI 33 distinct seasons
   (2000–2039), MV 31 (2004–2034), PE 30 (2005–2034) — the actual multi-season history now
   genuinely reachable, versus one row per team through the old views.
2. Regression check: the legacy-only portion matches the old `_current` views exactly, row for
   row, for every `play_call` on the one season the old views can reach (spot-checked on
   MV/2034 across all 85 rows returned).
3. `v_relevant_teams_franchise` returns exactly the same 3 rows as the earlier standalone
   diagnostic — franchise resolution is stable.
4. 477 offense rows and 593 defense rows in `plays` belong to one of the three tracked teams —
   real data is flowing through the plays-side join, not silently filtered to zero.
5. Grain check clean on all four views.

Point 4 combined with point 2 initially looked like a contradiction worth chasing — real
matching plays exist, yet the regression check showed no difference from the old view. It
wasn't a contradiction: the live turns actually parsed so far are from NFLAR/NCAA5 seasons
2034/2038/2039, none of which is the single season (2032, at the time) the old `_current` view
happened to expose for those teams. All ~1070 rows of live play data were sitting in seasons the
old view could never reach at all — concrete, current evidence the season restriction wasn't a
theoretical problem, it was actively hiding real data the moment this stage's diagnostic ran.
(Chasing down exactly *which* season those live rows belonged to also surfaced an unrelated bug
in a different, already-"completed" parser — see §9, and §3 for the mistaken assumption that
led there.)

---

## 9. `extract_games.php` bug: `games.label` used today's date, not the turn's season

Found while verifying Feature 1 stage 2 — a real, separate bug in a different, already-
"completed" parser, unrelated to the union views themselves. See §3 for the mistaken assumption
that kicked off the investigation (a claim that the live turns were from season 2032, based on
nothing more than the project workspace's sample filenames, never actually checked).

**Symptom:** every game created via live extraction carried a label like `"NFLAR 2026 Wk 15:
..."`, regardless of what season the turn was actually for — all 48 affected games showed 2026,
the same across turns whose filenames (and, it turned out, correctly-resolved `season_id`)
said 2034, 2038, and 2039.

**First hypothesis, checked and ruled out:** that live plays had been mismatched onto
pre-existing historical games left over from the original migration (i.e. a `week_id`
resolution bug landing on the wrong, already-populated week). Ruled out directly:
`games.source_upload_id` matched `plays.source_upload_id` exactly for every affected game —
these were genuinely new games, created fresh by this same live-extraction round, with the
wrong label baked in from the start, not old ones being reused.

**Root cause**, found by reading `extract_games.php` directly rather than continuing to guess:
```php
$label = "{$_cp_league_code} " . date('Y') . " Wk {$_cp_week_number}: {$game['home_team']} vs {$game['away_team']}";
```
`date('Y')` returns today's real-world wall-clock year — not the turn's actual in-league
season, which was already sitting resolved and correct in `$_cp_upload['season_id']` the whole
time, just never read for the label.

**Confirmed cosmetic-only before fixing or backfilling anything** — the same discipline as
everywhere else in this project: don't assume a bug's blast radius, check it. A query
cross-referenced `games.week_id → weeks → seasons` (what the union views and everything else
downstream actually reads) against `raw_uploads`' independently-resolved season (set by
`operational_hooks.php`, before `extract_games.php` ever ran) for all 48 affected games. All 48
rows agreed exactly with each other — 2038/2039/2034, matching the turn filenames — while every
one disagreed with the label's "2026." Conclusive: the actual `week_id` FK, and therefore every
one of Feature 1's union views (both stages), was correct throughout. Only the display text was
ever wrong — Feature 1's live verification results above stand as reported, no correction
needed there.

**Fixed:** `extract_games.php` now resolves `season_year` from `seasons.year` via the upload's
own `season_id` (same pattern already used for `league_code`/`week_number` two lines above it in
the same file), with a `season_id` `NULL` guard added alongside the existing `league_id`/
`week_id` check. `backfill_games_label.sql` corrects the label on all 48 already-affected games
by reconstructing it from the same FK chain (`games.week_id → weeks → seasons → leagues`, plus
`franchises.label` for the two team names), restricted to `source_upload_id IS NOT NULL` so
migration-era games are left untouched. Also worth noting for next time this kind of bug shows
up: `extract_games.php`'s own upload dropdown excludes any `week_id` that already has `games`
rows, so simply re-running the parser against these same uploads would never have picked these
48 rows up again — the backfill was the only way to correct them, the same "a hook/parser only
ever runs once" shape as the NCAA5 `franchise_id` gap in §5.

---

## 10. Deleting an uploaded game: `games.source_upload_id` is many-to-one, not one-to-one

**One upload's `games` rows are one-to-many, not one-to-one — same shape as `standings_weekly`.**
A turn's Results/League Report block reports on every game played league-wide that week, not
just the uploader's own — confirmed directly while testing a full delete-and-reupload cycle: a
single NFLAR upload (`upload_id 12`) produced 12 `games` rows, one per game across all 24
franchises that week, not one. `games.source_upload_id` is genuinely many-to-one: many game rows
share one upload's ID as their "first upload this result was captured from" source, exactly per
the schema comment already on that column (§2) — just not previously written down as a
many-per-upload fact, only as a "which upload wins on conflict" fact.

**Consequence: deleting an uploaded game means deleting by `source_upload_id`, not by a single
`game_id`.** Targeting only the one `game_id` of interest leaves the other ~11 games from that
same upload still referencing it. Since `games.source_upload_id → raw_uploads.upload_id` has no
`ON DELETE CASCADE` (confirmed directly against `new_schema.sql`), attempting to delete the
`raw_uploads` row afterward fails with a foreign key error (`#1451`) until every game from that
upload is gone, not just the first one attempted — found directly, live, mid-test.

**Correct procedure, in order:**
```sql
-- 1. Find every game this upload touched (there will likely be more than one)
SELECT game_id, label, week_id FROM games WHERE source_upload_id = {upload_id};

-- 2. Safety check -- franchise_honors.game_id also has no cascade
SELECT * FROM franchise_honors
WHERE game_id IN (SELECT game_id FROM games WHERE source_upload_id = {upload_id});

-- 3. Delete all of them -- cascades to team_game_stats/plays/drives automatically
DELETE FROM games WHERE source_upload_id = {upload_id};

-- 4. That week's standings will regenerate identically on re-processing, safe to
--    wipe entirely rather than trying to selectively unlink
DELETE FROM standings_weekly WHERE week_id = {week_id};

-- 5. Now safe -- cascades to raw_upload_blocks automatically
DELETE FROM raw_uploads WHERE upload_id = {upload_id};
```

**Cascade map worth having alongside this, confirmed directly against `new_schema.sql`'s actual
constraints rather than assumed:** `team_game_stats`, `plays`, `drives` → `games`, and
`raw_upload_blocks` → `raw_uploads`, all cascade automatically. `games.source_upload_id`,
`standings_weekly.source_upload_id`, and `franchise_honors.game_id` do not — those are the ones
that block a delete if handled out of order.

---

## 11. Schema-check discipline: DESCRIBE the whole table before adding to it

**Two redundant columns added this same session, both from the identical mistake.**
`franchises.coach_user_id` (`coaches.id_user` already existed for exactly that purpose) and
`raw_uploads.id_user` (`uploaded_by` already existed, already configured as DaDaBIK field type
`ID_user`, already working). Both times, a new column was designed by reasoning about the
feature being built, without first checking the table's actual, complete definition. The first
case at least checked `schema.md`'s narrative summary — just not `new_schema.sql`'s own DDL,
where the real answer was sitting in a comment. The second case checked neither, only whatever
custom PHP code happened to already be visible.

**Standing habit going forward: run a full `DESCRIBE` (or view the complete `CREATE TABLE`) on
any table before adding a column to it — every time, not just when something feels like it
might already exist.** Checking only the specific thing in mind, rather than the table's actual
complete definition, is what let both of these slip through undetected until they were live and
populated.

**One live operational detail surfaced while investigating the second case, confirmed by an
actual live insert, not just theorized:** DaDaBIK's `ID_user` field-type auto-population only
populates one field when a table has two configured at once — `raw_uploads` briefly had both
`uploaded_by` (pre-existing) and `id_user` (freshly configured) set to type `ID_user`
simultaneously, and a real upload inserted during that window came back with `uploaded_by =
'AlanM'`, `id_user = NULL`. `uploaded_by` sits earlier in the column order; `id_user`, added
later via `ALTER TABLE`, sits at position 16 (last) — column position as the tie-break is a
clean fit for this result, though confirmed from one instance, not proven as a hard rule for
every case.
