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
* [ ] **Feature 1:** Union views for `legacy_play_log` + `plays`
  * **Current Step:** Stage 1 is complete and live-verified — the three `v_playcall_*`-style
    aggregate views (`v_playcall_formation`, `v_playcall_matchup`, `v_playcall_matchup_formation`)
    now have `_all` union counterparts covering both sources. Also required, and now done:
    a real parser bug fix (`plays` was missing `is_interception` entirely — see `lessons.md`
    §3/§5/§8 for full detail). Stage 2 is next: the same union treatment for the four
    `v_relevant_*` aggregate views (`v_relevant_defense_current`,
    `v_relevant_defense_formation_current`, `v_relevant_offense_current`,
    `v_relevant_offense_formation_current`) plus their shared helper view
    (`v_relevant_current_season`) — explicitly out of scope for stage 1, not started yet.
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
