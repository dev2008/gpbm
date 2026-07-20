# Gameplan PBM League Database — Schema Redesign Proposal

**Purpose:** eliminate the Excel/CSV/GPVCon/GPAnalyst pipeline entirely. Turn files upload
directly into the app, which parses the raw text itself, block by block, straight into
normalized tables — no external desktop tools in the pipeline at all.

**Companion files:**
- `new_schema.sql` — runnable DDL implementing everything described here.
- `migration_data.sql` — real historical data: leagues, game types, seasons, weeks,
  franchises, coaches, coach tenures, rivalries, honors, and the full 1989–2038 game log
  (9,969 games, 19,938 team-game stat lines) generated from `fc_franchises.sql`,
  `fp_franchises.sql`, `f_gametypes.sql`, and `f_games.sql`. See §9.

---

## 1. What the source files actually contain

Every turn (`NFLAR-PE_*.txt`, `NCAA5-PI_*.txt`) is a single email whose body is split into
`<BK.section name>` blocks. (The `-PE`/`-PI` suffix in the filename is the *coach's own*
franchise abbreviation baked into that turnsheet — PE = Philadelphia Eagles, PI = Pittsburgh
Panthers — not the league code; the league itself is identified as `NFLAR` / `NCAA5` in the
report headers and in `fc_franchises.league` / `fp_franchises.league`.) The blocks that
matter for this project, and the parser each will need:

| Block | Parser | Contents | Scope |
|---|---|---|---|
| `Team Report` | — | Uploading coach's own gameplan config, training | Own franchise only |
| `Roster` | — | Full player roster with age/value/strengths/status | Own franchise only |
| `1st–4th Quarter` | **play parser** | Play-by-play: time, side, field pos, down/dist, off call, def call, result, running score — **one row per play** | Own franchise's game only |
| `League Report` | **results/standings parser** | One box-score paragraph per game, for **every game in the league that week** (FG, EP, punts, 3rd/4th down, passing, rushing, returns, play-calls) | Whole league |
| `Standings` | **results/standings parser** | Conference/division table: W-L-T, PF/PA, division record, streak | Whole league |
| *(within League Report)* | **transactions parser** | Free-text transaction lines: waives, coach recruiting picks, position/depth changes, FA signings | Whole league |
| `Draft Report` / `Draftsheet` | **transactions parser** | Annual rookie draft order and picks | Whole league |
| `Scouting Report - Game Summary` | **drive parser** | Drive-by-drive summary (not play-by-play) for the *next opponent's* most recent game — **one row per drive** | One other franchise's game |
| `Roundup` | — | End-of-season narrative summaries | Whole league |
| `Turnsheet` | — | Blank input form for the next turn | Not data — ignore on ingest |

**Two structurally different play-level blocks — do not conflate them:**
- The coach's own `1st–4th Quarter` blocks are true **per-play** detail: one row per snap,
  with down/distance, the specific off/def call, and the free-text result of that one play.
- `Scouting Report - Game Summary` (the next opponent's last game) is **per-drive** summary
  only: how the drive started, a "N plays for N yds" summary, the single longest play in the
  drive, and how the drive ended. No individual-play detail is given for it at all.

These map to two separate tables, `plays` and `drives` (§4), not one shared table — a drive
summary is not a coarser version of a play row, it's a genuinely different shape of record
(e.g. it has no `down`/`yards_to_go` at all, but has fields like `play_count` and
`longest_play_text` that `plays` doesn't).

**Key implication:** play-by-play (either form) is only ever available for two games per
turn (self + next opponent), never the full league. `plays` and `drives` will both be sparse
by design relative to `games` — that's expected, not a parsing failure.

Both leagues (`NFLAR`, pro; `NCAA5`, college) share this exact block structure, differing
only in whether "division" is populated (pro has conference+division, college has conference
only) and in what postseason events exist (bowls/CIC for college vs conference
championship/wildcard for pro — confirmed against real data, see §9).

---

## 2. Problems with the current schema this redesign fixes

1. **Games stored twice per row, wide.** `f_games`/`fc_vgames`/`fp_vgames` hold one row per
   team-perspective with ~90 `opp_*` mirror columns. → **Fixed**: `games` (one row per game)
   + `team_game_stats` (one row per team per game, long/normalized).
2. **History as delimited strings.** `WinnerYears`, `CottonYears`, `RivalryYears`, etc. are
   `varchar(1024)` comma-lists on `fc_franchises`; ~20 separate win-count columns alongside
   them. → **Fixed**: `franchise_honors` (one row per honor per season) + `honor_types` lookup.
3. **Nine near-identical bowl tables** (`fc_rosegames`, `fc_cottongames`, `fc_orangegames`,
   `fc_hawaiigames`, `fc_motorgames`, `fc_ncgames`, `fc_confgames`, `fc_cicgames`,
   `fc_rivalrygames`, plus `fp_bowlgames`). → **Fixed**: `games` with `game_type_id` (FK to a
   proper `game_types` lookup, loaded from the real `f_gametypes` table — see §9) +
   `franchise_honors` — the same handful of tables now serve every postseason event in both
   leagues.
4. **No structured play-by-play at all** — under the old pipeline this apparently never made
   it past the CSV stage. → **Fixed**: `plays` (own-game, per-play) and `drives` (opponent
   scouting, per-drive) — see §1 for why these are two tables, not one.
5. **Raw turn storage with no structure.** `g_turnsfull` is flat numbered lines
   (`up_id`, `tf_seq`, `tf_line`) with no idea which `<BK.>` section a line belongs to, and
   `g_turnsummary` carries `batstat1-8`/`pitstat1-8` fields that don't apply to football at
   all (baseball leftovers). → **Fixed**: `raw_uploads` (the file itself) +
   `raw_upload_blocks` (one row per `<BK.>` section, correctly typed).
6. **Two separate franchise ID spaces — now confirmed safe to merge.** `fc_franchises.franchise`
   and `fp_franchises.franchise` are independent `smallint` PKs in separate tables. With the
   real data supplied, pro franchises run 2001–2024 and college franchises run 5001–5012 —
   no overlap, across all 36 rows. The single unified `franchises` table (§4) is confirmed
   safe; this was Open Question 1 in the previous draft and is now resolved.
7. **Coaching changes as prose only.** Currently nowhere in the schema — League Report line
   items like "Jets waive no 32" have no home. → **Fixed**: `transactions` table with a typed
   `transaction_type` plus a `detail_text` fallback so nothing is lost even when the parser
   can't fully decompose a line.
8. **Two per-game flags with no column at all.** The League Report marks a team with `S`
   when they conceded a safety that game, and with `UP` when they played up (fielding at full
   strength — automatic in the postseason, optional in the regular season at the cost of not
   accumulating Form). Neither survived into the old schema. → **Fixed**: `team_game_stats
   .safety_conceded` and `team_game_stats.played_up`, both captured as reported rather than
   inferred, since `played_up` isn't strictly determined by `game_type` (it's a real choice in
   the regular season).

---

## 3. Design principles

- **One row per real-world fact.** A game is one row; a team's performance in that game is
  one row; a play is one row; a franchise honor is one row. No mirrored `opp_*` columns, no
  delimited lists.
- **Keep the raw text.** Every parsed row should be traceable back to the `raw_uploads` row
  (and ideally the `raw_upload_blocks` row) it came from, and any line the parser can't
  fully structure still gets captured verbatim in a `detail_text`/`result_text` column rather
  than dropped. Parsing is done incrementally and safely — partial understanding of the
  turn format shouldn't mean data loss.
- **Retain franchise IDs.** `franchises.franchise_id` is not auto-increment; it's populated
  from whatever numbering the current/legacy system already uses, so historical references
  (and anyone's muscle memory of "Eagles = 24") keep working.
- **Derive, don't duplicate, where cheap.** Season-cumulative stats, rankings, etc. shown in
  the "Scouting Report" season-stats tables are computable from `team_game_stats` via a VIEW
  — no need to store them separately. Standings are the one exception (see §5) because the
  "streak" and "division record" values are non-trivial to recompute and the source already
  gives you the correct, authoritative answer each week.
- **Design for Dadabik's grain, not against it.** File upload fields, master/detail
  (subform) views, VIEWS-as-pre-filtered-grids, hooks, and formula fields are all first-class
  Dadabik features — the schema is shaped so each of those maps onto a real table
  relationship rather than requiring custom code to fake it (see §7).

---

## 4. Entity overview

### Structural / reference
- **leagues** — one row per PBM league (`NFLAR`, `NCAA5`, ...), sport type, GM name.
- **seasons** — one row per league-year.
- **weeks** — one row per league-season-week; carries both `week_number` and `turn_number`
  (usually equal, but playoff turns can process a week that doesn't match the regular-season
  numbering — e.g. Turn 17 of a 16-week season). No `phase` column — see §5.
- **game_types** — lookup table, one row per distinct kind of game (Regular Season, Rose Bowl,
  Superbowl, ...), loaded verbatim from the legacy `f_gametypes` table with IDs retained,
  plus a `phase` (preseason/regular/postseason) column added for reporting. Replaces the
  guessed `game_type` ENUM + free-text `bowl_name` from earlier drafts — see §10.

### Franchises & people
- **franchises** — merged replacement for `fc_franchises` + `fp_franchises`. Identity fields
  only (city, nickname, conference, division, abbr); all the derived win-counts and Years
  lists are gone, replaced by queries against `games`/`franchise_honors`.
- **coaches** — the human league participants (distinct from the league's GM/commissioner,
  which is just a text field on `leagues`).
- **franchise_coach_tenures** — replaces the flattened `team` text column on
  `fc_coaches`/`fp_coaches`; one row per coaching stint, so franchise history (relocations,
  coaching changes) is queryable instead of string-parsed. Backfilled from real per-week coach
  data in `f_games` — see §10 (this was flagged as impossible in an earlier draft; it wasn't,
  the franchise snapshot just didn't have what was needed).

### Ingestion (the new part — no legacy equivalent)
- **raw_uploads** — one row per uploaded turn file. This is the table the Dadabik upload
  form targets directly.
- **raw_upload_blocks** — one row per `<BK.>` section within an upload, correctly typed
  (`Team Report`, `League Report`, `1st Quarter`, ...). Structured intermediate storage that
  `g_turnsfull` never had; makes debugging a bad parse trivial (open the block, not the whole
  email) and is a natural Dadabik master/detail view under `raw_uploads`.

### Games & stats
- **games** — one row per game (not per team). `game_type_id` (FK to `game_types`) covers every
  postseason event, replacing the nine bowl tables.
- **team_game_stats** — one row per team per game; every box-score figure from the League
  Report line (FG, EP, 3rd/4th down, passing, rushing, returns, play-calls), plus
  `safety_conceded` and `played_up`.
- **plays** — one row per play, populated only from the uploading coach's own quarter blocks
  (see §1). `result_text` always keeps the original free-text result.
- **drives** — one row per drive, populated only from the "Scouting Report - Game Summary"
  block (the next opponent's last game). Deliberately a separate table from `plays`, not a
  rolled-up view of it — the source data itself is drive-grain, not play-grain, for these
  games (see §1).

### Standings & history
- **standings_weekly** — one row per franchise per week, captured exactly as the Standings
  block printed it (wins/losses/PF/PA/division record/streak). See §5 for why this is
  captured rather than computed.
- **franchise_season_records** — one row per franchise per season: final wins/losses/ties/
  points-for/points-against. Usually `derived` (aggregated from `games`/`team_game_stats`,
  excluding preseason games — matching how the league's own Standings block only ever starts
  at Week 1). For a documented set of very old seasons, real per-game data doesn't survive at
  all and this table holds the legacy season-total instead (`source = 'legacy_rollup'`) — see
  §10.
- **honor_types** / **franchise_honors** — replaces the nine bowl tables' historical columns
  and the Years-list strings on `fc_franchises`/`fp_franchises`. One row per franchise per
  season per honor (league winner, conference champion, each named bowl winner, rivalry win,
  perfect season, ...). See §9 for the confirmed code list, decoded from the real data.
- **franchise_legacy_stat_counts** — a staging table for legacy career-total fields with no
  accompanying year list, for whatever future field might need this treatment. Currently
  empty: every field that started here during migration (§9) turned out to be derivable from
  real game data once the right query was found, and got moved into `franchise_honors`
  properly instead. Kept in the schema as a safety net, not because anything needs it today.
- **rivalries** — simple franchise-pair lookup, replacing `fc_rivalries`/`fc_rivalrygames`.
  Confirmed against the real data: `fc_franchises.Rivalry` is a shared grouping ID, and every
  group in the supplied data has exactly two members (e.g. Air Force/Army both carry `1000`).
  Rivalry *record* (head-to-head W-L) is a VIEW over `games` + `rivalries`, not a stored
  table — unlike weekly standings, it has no streak/division-record complexity, so it's safe
  to derive live (`v_rivalry_records` in the DDL).

### Transactions
- **transactions** — one row per roster/coaching move (waive, sign, draft pick, position
  change, retirement...), typed where the parser is confident, always keeping `detail_text`
  verbatim.

---

## 5. Standings: captured, not recomputed

Wins/losses/points-for/points-against **could** be a VIEW aggregating `team_game_stats`.
Division record and win/loss streak are much harder to get right in SQL (streak needs
ordered, stateful logic; division record needs to know each franchise's division at the time
of the game, which can change on relocation). Since the Standings block already prints these
correctly every week, `standings_weekly` **stores that snapshot directly** rather than
re-deriving it. This avoids a whole class of "the computed standings don't match what was
actually reported" bugs. Basic PF/PA/W-L are also stored here for convenience even though
they're technically re-derivable, so the whole weekly standings table is one clean read with
no joins.

A related fix from the same evidence: `weeks` originally had its own `phase` column
(preseason/regular/postseason). Real `f_games` data disproves that a week has one phase — 60
weeks (several NFLAR seasons' weeks 19–20) contain both postseason games for teams still alive
in the playoffs *and* preseason games for already-eliminated teams starting next season early,
in the same week. Phase is a per-game fact only (`game_types.phase` via `games.game_type_id`);
`weeks.phase` has been removed rather than left in as something that's sometimes wrong.

---

## 6. Multi-coach ingestion: every parsed table is upsert-safe

Every coach in a league uploads their own turn, and each turn independently contains the
*entire* league's shared facts for that week — the full League Report (every game, not just
theirs), the Standings block, and every transaction line — alongside two coach-specific
things: their own game's play-by-play, and their next opponent's drive-by-drive summary. So
with N coaches uploading, the shared data arrives N times over; the design goal is that
re-ingesting it is a no-op, never a duplicate row, while genuinely coach-specific data still
lands correctly.

This works out cleaner than it sounds, because it splits along a line the schema already
respects: **shared league-wide facts key on the fact itself** (a game, a franchise's weekly
standing, a transaction), and **the two coach-specific tables key on the actual game they
describe**, which — this is the part worth spelling out — is *also* shared more often than it
first appears:

- **`games`** — `UNIQUE KEY (week_id, home_franchise_id, away_franchise_id)`. Whichever of the
  two participating coaches' uploads gets parsed first creates the row; the second is an
  upsert against the same key.
- **`team_game_stats`** — `UNIQUE KEY (game_id, franchise_id)`. Same logic, one row per team
  per game regardless of which upload supplied it.
- **`standings_weekly`** — `UNIQUE KEY (week_id, franchise_id)`. Every coach's Standings block
  covers the whole league, so this table fills in from whichever upload gets parsed first
  each week; added `source_upload_id` for provenance, not for the dedup itself.
- **`transactions`** — free text has no natural key, so the parser computes a SHA-256 of
  `detail_text` into `detail_hash`, and `UNIQUE KEY (week_id, franchise_id, detail_hash)`
  does the deduplication. (Hashing sidesteps InnoDB's composite-key length limit that a raw
  `VARCHAR(255)` in the key would risk.)
- **`plays`** — this is the one that isn't obvious at first: a coach's own play-by-play is
  for a game that has *two* participants, and **both of them** will report play-by-play for
  it in their respective turns (Team A's "own game" this week is the exact same game as Team
  A's Week-N opponent's "own game"). So `plays` needs the same idempotency as `games` does:
  `UNIQUE KEY (game_id, quarter, play_seq)`.
- **`drives`** — similarly non-obvious: the "next opponent's last game" being scouted can
  recur across *multiple different coaches' turns over multiple weeks* — if Team Z has a bye,
  every team scheduled to face Z stays pointed at the same historical game until Z plays
  again. `UNIQUE KEY (game_id, quarter, drive_seq)` makes repeat coverage idempotent the same
  way.

None of this needs application-level "have I seen this before" logic — every parsed insert is
a plain `INSERT ... ON DUPLICATE KEY UPDATE` (or the Custom Code API equivalent) against a key
that's already unique for the right reason, so a second upload confirming the same fact is
cheap and harmless rather than something the parser has to detect and special-case.

---

## 7. How this maps onto Dadabik

- **`raw_uploads.file_path`** is a `generic_file` field — the actual upload control. A
  **hook** (insert/update hook, §10.8 of the Dadabik manual) fires the PHP parser after a
  file lands, which populates `raw_upload_blocks`, then `games`/`team_game_stats`/
  `plays`/`drives`/`standings_weekly`/`transactions`, and finally updates
  `raw_uploads.parse_status`.
- **`raw_uploads` → `raw_upload_blocks`** is a natural **master/detail (subform)**: open an
  upload, see its parsed sections inline, useful for QA-ing a parse without downloading the
  original file.
- **`games` → `team_game_stats`**, **`games` → `plays`** and **`games` → `drives`** are all
  master/detail relationships too — a game's detail page can show both teams' box scores
  plus whichever of the play log or drive summary is available for that particular game.
- **`v_current_standings`** (a VIEW filtering `standings_weekly` to each franchise's latest
  week) is exactly the "pre-filtered results grid using VIEWS" pattern the manual describes
  — gives coaches a standings page with zero custom code.
- **Season-total stat pages** (the "season, offence"/"season, defence" tables shown in the
  Scouting Report) are VIEWS aggregating `team_game_stats`, not stored tables — formula
  fields or advanced SQL reports can produce the ranked versions directly from the grid.
- **`franchises.franchise_id`** should be a `select_single` lookup wherever it's a foreign
  key, so users pick "Philadelphia Eagles" and the numeric ID is stored underneath —
  consistent with how the manual recommends handling lookups generally.

---

## 8. Out of scope for this pass (flagged, not designed)

- **Player-level roster tracking.** The `Roster` block has full per-player detail (age,
  value, strengths, status) but wasn't named as a parsing target. The schema doesn't
  preclude adding a `players` table later (`transactions.player_name` is free text for now,
  not a FK) but building it out is a separate phase.
- **Weekly free-agent pool.** ~170-row list of available players per turn; it's a live
  draft board, not history — probably doesn't belong in the database at all. Flagging rather
  than deciding.
- **Turn credits / billing.** `turn credits =26.0` appears in the Team Report header; this is
  an account-balance concern, not a results concern, and probably belongs in whatever system
  handles payments rather than this schema. Noted, not designed.

---

## 9. Validated against real data

### From `fc_franchises.sql` / `fp_franchises.sql` (36 rows, not just structure)

- **Franchise ID ranges don't collide.** Pro franchises run 2001–2024 (24 rows), college
  franchises run 5001–5012 (12 rows). The unified `franchises` table is safe as designed.
- **The `Rivalry` grouping column is exactly pairwise.** Every distinct value of
  `fc_franchises.Rivalry` (1000–1005 in this data) is shared by exactly two franchises — e.g.
  Air Force and Army both carry `1000`. `rivalries(franchise_a_id, franchise_b_id)` is a
  direct, lossless translation of this.
- **The G/GC/S/SC/B/BC bowl-tier codes decode cleanly** — since confirmed directly against the
  real `f_gametypes` table rather than left as an inference (see below).
- **Some fields are pure derived redundancy and were dropped**, not migrated: `team`/`ori_team`
  are exactly `city + nickname` concatenations; `ConfWins` equals `COUNT(ConferenceYears)`;
  `Perfect` equals `COUNT(PerfectYears)`; `RivalryWins` equals `COUNT(RivalryYears)`.
- **One legacy data-quality issue found and designed around:** `PerfectYears` sometimes
  contains raw HTML (e.g. `' <em>2027</em>'`), apparently used to style an in-progress
  streak differently from a completed one in the old front-end. The new schema stores just
  the year in `franchise_honors`; "is this streak still active" becomes a property of season
  status (`seasons.status`), not markup baked into a data column.

### From `f_gametypes.sql` (20 rows)

This fully resolved the bowl-tier guess: `GC_Winner`→Rose Bowl, `S_Winner`→Cotton Bowl,
`SC_Winner`→Orange Bowl, `B_Winner`→Hawaii Bowl. One correction to the earlier inference:
`BC_Winner` (from `fc_franchises.MotorYears`) is actually the **Music City Bowl**, not "Motor
[City] Bowl" as the legacy column name implied — `honor_types.MUSIC_CITY_BOWL_WINNER` uses the
name from the authoritative lookup table, not the franchise table's column name. `CIC` is
confirmed as the Commander-in-Chief's Trophy (Army/Navy/Air Force) — see §10 for the one
structural nuance worth flagging there.

It also justified replacing the guessed `game_type` ENUM entirely: `game_type_id` is now a
proper FK into a `game_types` lookup table loaded verbatim from `f_gametypes`, with a `phase`
column added for reporting. Confirmed safe as a single ID space the same way franchise IDs
were — every one of the 21 codes belongs to exactly one league across all 20,601 `f_games`
rows, no exceptions.

### From `f_games.sql` (20,601 rows — the full historical game log for both leagues)

This is what let me go from "designed and plausible" to "loaded and cross-checked against the
turn files." Concretely, `migration_data.sql` now also contains:

| Table | Rows | Source |
|---|---|---|
| `weeks` | 1,059 | every distinct (league, season, week) with real game data |
| `games` | 9,969 | paired `f_games` rows, deduplicated on (week, {franchise, opponent}) |
| `team_game_stats` | 19,938 | 2 per game, every box-score column mapped 1:1 |
| `franchise_season_records` | 1,488 | 1,158 derived (aggregated using the exact legacy formula — see below) + 330 `legacy_rollup` |
| `coaches` | 177 | up from 68 — real per-week coach names, not just current/original |
| `franchise_coach_tenures` | 169 | backfilled by detecting coach changes across each franchise's chronological game log |

### From the actual legacy PHP (`fp_updaterecords.php`, `fp_updateseasons.php`, `fc_updaterecords.php`, `fc_updateseasons.php`)

This is the actual source code that computes every field this whole `franchise_legacy_stat_counts`
conversation has been about — ground truth rather than statistical inference. It confirmed some
things, corrected one thing I'd gotten wrong, and closed out nearly everything that was still open.

**`ChampionshipL` — I was right the second time, and now I know exactly why.** The PHP does
precisely `WHERE gametype=35 AND week=19 AND win=0` (pro), matching what your query pointed at
last time — confirmed, not just re-derived independently.

**`ChampionshipW`/`ConferenceYears` — simpler than I made it sound.** Both get set from
Superbowl participation, and I described that as if it were a separate mechanism from "winning
the week-19 game" — it isn't. Winning week 19 *is* how a team reaches the Superbowl; "Superbowl
participant" and "won the conference championship game" are the same set of teams by
construction, not two derivations that happen to agree. Nothing to fix in the data — `CONFERENCE_
CHAMPION` was already correct — just a needlessly roundabout way of describing something
straightforward.

**`MaxWins`/`MaxLosses`/`MaxScored`/`MaxConceded` — fully solved, both leagues, and the earlier
"narrowed down but not solved" verdict on `MaxScored`/`MaxConceded` was based on a bug in my
own aggregation, not a genuine gap.** The exact source queries: pro is
`WHERE week NOT IN (0,17,18,19,20)` (i.e. strictly the 16-game regular season), college is
`WHERE gametype=1` exclusively (an 11-game regular season). Both replicate the legacy figures
with **0 mismatches**, all four stats, all 24 pro and all 12 college franchises. My own
`franchise_season_records` had a real bug: it excluded only preseason, not the full postseason
too, so for the seasons where per-game detail exists *only* as a playoff-round row (no regular-
season games at all, or vice versa) it was aggregating the wrong set of games entirely — a
handful of early seasons had off-by-orders-of-magnitude records as a result (e.g. Bills 1994
showed as 0-1 with 13 points instead of the real 13-3 with 444). Rebuilt `franchise_season_records`
from the exact legacy queries; all four `Max*` legacy rows deleted for both leagues.

**College bowl runner-up counts — I got this one wrong, and it wasn't a subtle mistake.** I'd
concluded these were structurally unrecoverable because the PHP's "Find Rose Bowl losses" block
(and the Cotton/Orange/Hawaii/Music-City equivalents) increments `GC_Runnerup` etc. with no
accompanying year-list update — and took "the legacy table never stored a year list" to mean
"the dated facts can't be reconstructed." That's wrong: I'd already used exactly this query
shape for the *winner* side (`gametype=X AND win=1`) to build dated `franchise_honors` rows
directly from `f_games`, without needing `fc_franchises` to have pre-stored anything. The
loser side needed nothing more than flipping `win=1` to `win=0` — I just didn't think to do
it. 0 mismatches, all five bowls, all 12 college franchises, all at week 13 (same week as the
corresponding wins). Fixed the same way as everything else: five new honor types
(`ROSE_BOWL_RUNNERUP` and siblings), 155 dated `franchise_honors` rows, legacy rows deleted.

**Two smaller confirmations:** `PerfectYears`'s `<em>` tags are applied unconditionally by the
PHP every time (`CONCAT(PerfectYears,' <em>$season</em>')`, always, no branch) — not an
"in-progress vs. completed" distinction as I'd guessed, just a permanent styling choice in the
old front-end. And a perfect season specifically requires **11-0** (college's fixed regular-
season length) *and* winning the national championship that year, not just going undefeated —
matches what I'd already stored (sourced directly from `PerfectYears`, not re-derived), just
useful to know precisely what it means.

### Was `franchise_legacy_stat_counts` too pessimistic? Yes, three times — it's empty now.

You pushed back three times on treating fields as permanently opaque, and every time you were
right. The third time wasn't even a case of finding a better signal — I had the right query
pattern already, from the winner side, and just didn't apply it to the loser side. Summary of
where everything landed:

| Field | Status | Fix |
|---|---|---|
| `Runnerup` (pro) | Fully derivable | New `LEAGUE_RUNNERUP` honor type, 45 dated rows |
| `ChampionshipL` (pro) | Fully derivable (confirmed by source) | New `CONFERENCE_RUNNERUP` honor type, 90 dated rows |
| `MaxWins`/`MaxLosses`/`MaxScored`/`MaxConceded` (pro + college) | Fully derivable (confirmed by source) | `franchise_season_records` rebuilt correctly; all rows deleted |
| Bowl runner-up counts (college, 5 bowls) | Fully derivable — I just hadn't tried | 5 new honor types, 155 dated rows |

`franchise_legacy_stat_counts` went from 252 rows to **0**. Every field that started in there
turned out to be derivable; none of it needed to be a permanent legacy staging table. It stays
in the schema as a safety net for whatever comes up next, not because anything currently needs
it.

A few other things this validated or fixed along the way:

- **Spot-checked against the turn files directly**: Eagles vs. Packers, Week 1, season 2032 —
  `f_games` gives 42–21, exactly matching the League Report text from the original sample.
- **`weeks.phase` was wrong and has been removed** (see §5) — real data disproves that a week
  has one phase.
- **`safety_conceded` needed to be a count, not a boolean** — `f_games.safe` is a run of
  repeated letters (`S`, `SS`, `SSS`, up to `SSSS` once). Renamed to `safeties_conceded`.
- **The `qb` flag is confirmed: starting QB benched during the game** (your answer) —
  `team_game_stats.starting_qb_benched`, no longer a guessed/held-verbatim field. This also now
  explains the earlier data-driven observation (186 pass yds / 11.8 pts average vs. 234 / 32.4
  otherwise) — a benched starter is usually a symptom of a team already losing badly, not a
  cause of worse stats on its own.
- **Two documented exclusions**, not silently dropped: 423 rows where `week` is a sentinel
  (`95`, `98`, or `99`) marking a season-end rollup with no real per-game detail — these feed
  `franchise_season_records` with `source='legacy_rollup'` instead of `games`. And 240 rows
  (NFLAR seasons 1989–2003, weeks 17–19) that record only a win/loss outcome for a playoff
  round with no opponent identified and no box score — these are **not** in `games` at all,
  since there's nothing genuine to attach to a game row. The final season records for those
  years are still complete and correct (via the rollup rows), but individual playoff-game
  detail for that specific window isn't recoverable from `f_games`.
- **`ret_type`/`ret_num`/`ret_yds`/`ret_td` on `team_game_stats`** (fumble/interception/
  defensive-TD returns) are left NULL for every migrated row: `f_games` has no columns for
  these at all, only kick and punt returns. This isn't a migration gap, it's a genuine hole in
  what the legacy table ever captured — going forward, the turn-text parser can fill this in
  from the League Report line (`FumR`/`IntR`/`DefR` do appear there), just not retroactively.

---

## 10. Open questions

1. ~~Single-uploader vs multi-coach ingestion~~ **Resolved: multi-coach, and it's handled.**
   Every parsed table now has a dedup key suited to how re-ingestion actually happens — see §6
   for the full breakdown (`games`, `team_game_stats`, `standings_weekly`, `transactions`,
   `plays`, and `drives` all upsert cleanly regardless of which coach's turn supplied a given
   fact, including the non-obvious cases where two *different* coaches' turns describe the
   exact same game).
2. ~~`played_up` isn't in the historical data~~ **Resolved: it was never recorded, and that's
   fine.** Confirmed — it wasn't captured historically, only submitted in orders, which are out
   of scope here. `team_game_stats.played_up` stays in the schema exactly as designed: it will
   simply start at 0/unknown for all migrated legacy rows and populate correctly from parsed
   turn text going forward. No further action needed.
3. **CIC's three-way structure isn't modeled, just its outcome.** Confirmed as the
   Commander-in-Chief's Trophy (Army/Navy/Air Force round robin), and a genuine structural
   check bears this out: Air Force and Army share pairwise `rivalries` group `1000`, but Navy
   doesn't belong to that group at all (paired with Maryland instead) — proving `Rivalry` and
   `CICWins`/`CICYears` track two different things. `honor_types.CIC_WINNER` correctly
   captures "who won the trophy that year"; it doesn't model the round robin itself. Only
   worth extending if you want in-progress three-way standings, not just the annual winner.
4. ~~Runner-up-only and Max-only fields~~ **Fully resolved.** `Runnerup`, `ChampionshipL`,
   `MaxWins`, `MaxLosses`, `MaxScored`, `MaxConceded`, and the college bowl runner-up counts
   are all exactly derivable, both leagues, backed by real source queries rather than
   inference — see §9. `franchise_legacy_stat_counts` is empty; every field that started there
   turned out to be recoverable.

---

## 11. Suggested build order

Largely overtaken by events — most of this is now loaded and validated rather than planned:

1. ~~`leagues`, `seasons`, `franchises`, `coaches`, `rivalries`, `honor_types`,
   `franchise_honors`, `franchise_legacy_stat_counts`~~ **Done.**
2. ~~`weeks`, `game_types`, `games`, `team_game_stats`, `franchise_season_records`,
   `franchise_coach_tenures`~~ **Done** — all loaded and cross-checked against the turn files
   in `migration_data.sql` (see §9).
3. `raw_uploads`, `raw_upload_blocks` — still the right place to start on the *live* ingestion
   side: get file upload + block-splitting working end to end before any parsing logic exists,
   so every future turn is captured even before its parser is finished.
4. The results/standings parser (`standings_weekly`, and new rows into `games`/
   `team_game_stats`/`franchise_season_records` going forward) — regular enough (fixed-format
   lines) to parse reliably first among the *new* parsing work.
5. `transactions` — same League Report source, free-text lines; lower confidence parsing, so
   build after the box-score parser is proven and keep `detail_text` as the safety net.
6. `plays` — the coach's own quarter blocks; structurally regular but highest volume.
7. `drives` — the "Scouting Report - Game Summary" block; same priority tier as `plays` but
   worth keeping as an explicitly separate parser given the different grain (§1).
