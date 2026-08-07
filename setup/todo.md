# Gameplan PBM — Roadmap & Sprint Tracker

## 🎯 Current High-Level Goal
Migrate Gameplan PBM's legacy MySQL schema and manual tooling to a normalized MariaDB schema
(`gplan_pbm`) with a Dadabik 13.5 front end, plus a set of staged, manually-triggered parser
pages that turn newly-arriving turn files into rows in that schema.

## 💾 Project Reference Documents
* `schema.md` (schema design & the historical migration)
* `lessons.md` (the live-parser build phase: Standings/Games/Play-by-Play parsers,
  `play_text_patterns`, `team_codes`, and every hard-won lesson from building them)

---

## 🏃‍♂️ Active Sprint (Current Feature)
* [ ] **Feature:** None currently active — Feature 1 (union views for `legacy_play_log` +
  `plays`, both stages) is now complete, see ✅ Completed Features below. Pick the next item
  from Backlog to promote here when starting the next chat.
  * **Current Step:** —
  * **Known Blockers:** None.

---

## 📋 Backlog (Upcoming Features)
*Move items up to "Active Sprint" one at a time. Start a NEW chat for each.*

* [ ] **Feature 2:** `drives` / "Scouting Report - Game Summary" parser
  * *Notes:* Structurally distinct from `plays` — per-drive, not per-play, for a *different*
    game (next week's opponent's most recent one), with fields like `play_count` and
    `longest_play_text` that have no `plays` equivalent. Not started.
* [ ] **Feature 3:** "Scouting Report - Game Summary" parser
  * *Notes:* Rest of Scouting report. Not started.
* [ ] **Feature 4:** Stats page
  * *Notes:* Mentioned earlier in the project as future work. Not started.
* [ ] **Feature 5:** Historical Stats page
  * *Notes:* Mentioned earlier in the project as future work. Not started.
* [ ] **Feature 6:** Historical Standings page
  * *Notes:* Mentioned earlier in the project as future work. Not started.
* [ ] **Feature 7:** League Roster pages
  * *Notes:* Read rosters, process free agent signings etc. Not started.
* [ ] **Feature 8:** Team Roster pages
  * *Notes:* Read rosters, process free agent signings etc but at a greater level of detail for own team. Not started. 
* [ ] **Feature 9:** Game page
  * *Notes:* Need a page that displays details of an individual game, linked from Team page
* [ ] **Feature 10:** Historical season results / game browser
  * *Notes:* team.php's "Current season results" table only ever shows the franchise's
    MAX(season_id) -- older seasons' games have no UI path to game.php at all, even though
    game.php itself is season-agnostic. Surfaced while testing Feature 9 (game 
    NFLAR-PE_s2032_w01_vs_Packers unreachable once the franchise has later seasons on
    record). May overlap with Feature 6 (Historical Standings) -- worth scoping together.  

---

## ✅ Completed Features (Historical Context)
* [x] Schema design & architecture proposal
* [x] Historical migration (`f_games`/`fc_franchises`/etc. → `games`/`franchises`/etc.,
  9,969 games; 253,450-row `legacy_play_log` migration; NuGameplan audit)
* [x] `extract_standings.php` — Standings parser
* [x] `extract_games.php` — Games/team_game_stats parser
* [x] `play_text_patterns` — 27 cross-validated text-classification patterns
* [x] `team_codes` — 76 fully-resolved team codes (replacing the retired `franchises.abbr`)
* [x] `extract_playbyplay.php` — Play-by-Play parser, fully live-tested: multiple games across
  both NFLAR and NCAA5, 1070 rows in `plays`, a thorough set of invariant-checking queries run
  directly against live data all clean. Three real bugs found and fixed in the process (a
  missing `formation` column, `NULL`-vs-`0` handling for `yards_gained`, and an NCAA5-specific
  `franchise_id` gap) — see `parser_build_notes.md` §3/§5 for full detail.
* [x] `current_standings.php`, `team.php`, `home.php`, `bowl_records.php` front-end pages
* [x] **Feature 1:** Union views for `legacy_play_log` + `plays`, both stages — seven new
  views (`v_playcall_formation_all`, `v_playcall_matchup_all`, `v_playcall_matchup_formation_all`,
  `v_relevant_offense_all`, `v_relevant_offense_formation_all`, `v_relevant_defense_all`,
  `v_relevant_defense_formation_all`) plus two helper views (`v_plays_normalized`,
  `v_relevant_teams_franchise`). The original eight legacy-only views are untouched throughout,
  per explicit requirement. Along the way: a real parser gap fixed (`plays` was missing
  `is_interception` entirely), a genuine design defect found and deliberately left alone in the
  old `v_relevant_*_current` views (hard-restricted to one season each, confirmed wrong against
  the real legacy `n_s_*` batch table structures), and a second, unrelated bug caught while
  verifying stage 2 — `extract_games.php`'s `games.label` used today's real-world date instead
  of the turn's actual season, confirmed cosmetic-only (the real `week_id` FK was always
  correct) via a 48-row cross-check, then fixed and backfilled. See `lessons.md` §3, §8, §9 for
  full detail.
