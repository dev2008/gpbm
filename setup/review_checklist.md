# Review Checklist — Game Page + Coach/Access Work

Restructured after point 7's migration-ordering warning got missed on a top-to-bottom read —
the previous version grouped by feature area, which buried a hard dependency (run the
migrations before anything that writes to the columns they add) partway down the list. This
version is **Deploy, strictly in order**, then **Verify**, where order matters much less.
Files referenced are all in your downloads from this conversation.

---

## PART 1 — DEPLOY, IN THIS ORDER

Each numbered step depends on the ones before it. Don't skip ahead.

### 1. Run both migrations first — before deploying any PHP

- [x] `migration_add_id_user.sql`
- [x] `migration_add_coach_user_id.sql` — **superseded, see step 9**

Independent of each other (either order is fine), but **both need to be done before step 2**,
regardless of which PHP files you deploy first — this is the step that was buried at old
point 7 and caused the upload 12 error.

### 2. Deploy the upload pipeline (needs migration 1 from step 1)

- [x] `operational_hooks.php` — id_user threaded into `raw_upload_blocks`
- [x] `extract_standings.php` — id_user added to `standings_weekly`
- [x] `extract_games.php` — id_user added to `games` and `team_game_stats`
- [x] `extract_playbyplay.php` — **two unrelated changes bundled in one file:** id_user added
      to `plays` (needs migration 1), and a new "Watch the Live Replay" button after a
      successful extraction (needs `replay.php` to exist — step 4 — and `REPLAY_PAGE_STATIC_ID`
      updated — step 6). The button will render but 404 until step 6 is done; that's expected,
      not a bug, if you deploy this before registering `replay.php`.

### 3. Deploy the DB-independent pages

- [x] `game.php`
- [x] `home.php`
- [x] `current_standings.php`

No migration dependency — safe to deploy any time, included here just to keep this a single
pass rather than because order matters for these three specifically.

### 4. Deploy replay.php (new — draft, never yet deployed)

- [x] `replay.php`

No migration dependency itself (read-only against `plays`), but extract_playbyplay.php's new
button (step 2) links here, so deploy this before or alongside step 2, not after.

### 5. Deploy team.php (needs migration 2 from step 1)

- [x] `team.php` — **this was the version using `franchises.coach_user_id`, now superseded —
      see step 9 for the corrected version that needs redeploying**

### 6. Deploy coach.php (needs migration 2 from step 1)

- [x] `coach.php` — **same as team.php above, superseded by step 9's corrected version**

### 7. Register new pages in DaDaBIK, then update placeholder IDs

- [x] Register `replay.php` in DaDaBIK → note its `id_static_page`
- [x] Register `coach.php` in DaDaBIK → note its `id_static_page`
- [x] Update `REPLAY_PAGE_STATIC_ID` (currently `0`) in `extract_playbyplay.php` → redeploy
      that file
- [x] Update `COACH_PAGE_STATIC_ID` (currently `0`) in `team.php` → redeploy that file
- [x] `game.php` needed none of this — already registered (`id_static_page` 10) from earlier

### 8. Set coach_user_id for testing (temporary — not the real population mechanism)

- [x] ~~`UPDATE franchises SET coach_user_id = ...`~~ — **superseded by step 9.** The screenshot
      this confirmed (Alan Milnes → Pittsburgh Panthers + Philadelphia Eagles) is genuinely
      good evidence the *shape* of the feature works; the underlying column it depended on has
      since been retired

### 9. NEW — Correct the coach-linkage mechanism

**What happened:** `coaches.id_user` — a column already designed and already present in both
`new_schema.sql` and the live database, from a much longer separate conversation whose
decisions got compressed into this project's `.md` docs — turned out to already solve exactly
what steps 5/6/8 above were solving with a new, redundant `franchises.coach_user_id` column.
The reasoning survived as a `new_schema.sql` inline comment but never made it into `schema.md`'s
narrative summary, so it didn't surface until asked about directly. Full writeup in
`security.md` §7.

Deploy in this order — the migration must come **after** the code, not before, or the
currently-live pages break immediately (they still reference the column being dropped):

- [x] Redeploy the corrected `team.php` (Coach: row now sources its link from
      `franchise_coach_tenures` → `coaches.id_user`, not `franchises.coach_user_id`)
- [x] Redeploy the corrected `coach.php` (franchise-listing query now joins through
      `coaches.id_user` → open tenure → `franchises`, not `franchises.coach_user_id`)
- [x] Run `migration_drop_coach_user_id.sql`
- [x] Set test data on the new target (`UPDATE coaches SET id_user = ... WHERE coach_id IN
      (5, 160)`)
- [x] Query, data, and variable all confirmed correct end-to-end via `var_dump` — link genuinely
      was present in the rendered HTML the whole time (confirmed via View Source)

### 10. NEW — Fix: links with no explicit style are visually invisible

**Root cause, finally found via View Source, not more query debugging:** the Coach: row link
was never actually broken — the `<a>` tag was correctly present in the HTML from the start of
step 9. It just had no styling at all, and this site's theme apparently doesn't give plain
`<a>` tags a default underline/color the way a browser normally would — so it rendered as
completely plain text, indistinguishable from everything around it. Nine messages of
elimination (query logic, tenure data, variable survival, duplicate blocks) were all genuinely
worth ruling out and all came back clean, because none of them were ever the problem — this
was a CSS question hiding behind what looked like a logic question the whole time.

`game.php`'s equivalent link (`team_heading()`) already had explicit
`style='color:inherit;text-decoration:underline'` for this exact reason — that precedent just
didn't get carried over when the other links were built. Audited every `<a>` tag across every
page in this project once this pattern was understood; four needed the same fix:

- [x] `team.php` — Coach: row's "all their teams" link
- [x] `team.php` — season-results table's score links
- [x] `coach.php` — franchise-listing links (this is almost certainly why the earlier
      screenshot's links only looked underlined — that was the table's row border, not real
      link styling)
- [x] `current_standings.php` — team-name links
- [x] `replay.php` — the spoiler-warning link (worse case than the others: explicit `color:#888`
      but no underline, and the surrounding paragraph text is the *same* `#888`, so the link was
      genuinely unreadable as one, not just easy to miss)

`game.php`'s own team-name links (`team_heading()`) already had the correct styling and needed
no change.

- [x] Reload `team.php` and confirm the Coach: row link and score links are now visibly
      underlined
- [x] Reload `coach.php` and confirm the franchise links look the same way
- [x] Reload `current_standings.php` and confirm team-name links are now visibly underlined
- [x] Reload `replay.php`'s spoiler link and confirm it's now distinguishable from the
      surrounding text

---

## PART 2 — VERIFY

Everything below assumes Part 1 is fully done, **including step 9**. Order matters much less
here — the one item worth doing first regardless is the admin-gate test, since it's the actual
family-data boundary.

### Do this one first

- [x] **Admin gate, tested both ways.** Confirmed directly — `$current_user_is_administrator`
      works as designed.

### game.php

- [x] ~~Table/Playback buttons not showing~~ — confirmed false alarm, unrelated deployment
      issue
- [x] Read through end to end
- [x] Spot-check against a game you know well beyond "seems ok at first glance" (game 9847 was
      your first look) — box score numbers, quarter-by-quarter, OT handling if you have an OT
      game handy
- [x] **Table** and **Play by Play (Live Replay)** buttons both work; neither view renders
      before you pick one
- [x] In Playback: formation/off call/def call show *before* reveal, result/yards/score only
      *after* clicking Reveal
- [x] **Drive Summary** renders above **Play by Play** (order swap from partway through)
- [x] Score in the results table (via `team.php`) is a real link here; click through on a few
      games

### replay.php

- [x] Confirm the page goes **straight into** Playback on load — no Table option, no mode
      choice
- [x] "Watch the Live Replay" button on `extract_playbyplay.php`'s success message lands here,
      on the right game
- [x] Confirm the small "View full game details (spoilers)" link at the bottom goes to the
      right game on `game.php`, is now visibly underlined (step 10 fix — it previously matched
      the surrounding text's color exactly, making it unreadable as a link at all), and stays
      visually de-emphasized rather than prominent

### id_user population (audit-trail — unrelated to the coach_user_id rework above)

- [x] ~~`raw_uploads` needs `id_user` set to DaDaBIK field type `ID_user`~~ — **superseded.**
      `raw_uploads` already had a pre-existing column doing this exact job — `uploaded_by`
      (`VARCHAR(60)`), populated for all 8 existing uploads, with no custom PHP code setting it
      anywhere I can see. **Not fully confirmed as a live DaDaBIK auto-fill mechanism, though —
      genuinely unverified, not just unstated.** Could be a DB trigger (invisible to a
      PHP-only search), a different DaDaBIK convention than the `ID_user` field-type one this
      whole thread has been reasoning about, or a one-time/manual historical population — all 8
      existing rows are the same person, which doesn't distinguish "reliable mechanism" from
      "only one uploader so far." `id_user` on `raw_uploads` was still redundant either way and
      still worth dropping (same shape of mistake as `coach_user_id` vs. `coaches.id_user`
      earlier, one table over) — but whether `uploaded_by` actually keeps populating going
      forward is exactly what the fresh-upload test below needs to settle, not something to
      take as already answered. See `migration_drop_raw_uploads_id_user.sql` and `security.md`
      §7 — `operational_hooks.php`/`extract_standings.php`/`extract_games.php`/
      `extract_playbyplay.php` all updated to read `uploaded_by` instead.
- [x] **New deploy order because of the above** — redeploy the four corrected pipeline files
      *first*, confirm they work reading `uploaded_by`, *then* run
      `migration_drop_raw_uploads_id_user.sql`. Dropping the column before redeploying the code
      would break every extraction stage immediately.
- [x] After dropping the column, resync `raw_uploads` in DaDaBIK once more — removals aren't
      auto-detected the way additions are, so this time you'll need to explicitly tell DaDaBIK
      which field was removed (`id_user`) as part of the sync, not just run it expecting
      DaDaBIK to notice on its own
- [x] **DB Synchronization for the remaining tables `migration_add_id_user.sql` touched** — these
      are unaffected by the correction above, their `id_user` columns stay exactly as designed:
      - [x] `plays` — done, and turned out to also pick up 13 other fields that predate this
        session entirely (`formation`, `is_fumble`, `is_interception`, etc. — see `lessons.md`
        §1) and had apparently never been synced since they were first added, well before this
        conversation. Good catch beyond just this session's own change.
      - [x] `raw_upload_blocks`
      - [x] `standings_weekly`
      - [x] `games`
      - [x] `team_game_stats`
      - [x] `drives`
- [x] Quick sanity check, lower priority: `franchises` had `coach_user_id` added and dropped
      this session, never configured as a DaDaBIK field either way — confirm no dangling
      reference remains. `coaches.id_user` is a different, older case (predates this session's
      migrations entirely) and very likely already properly configured from original setup —
      not part of this batch.
- [x] ~~Confirm `raw_uploads.uploaded_by` populates on a genuinely fresh upload~~ — answered:
      `upload_id 13` (inserted after `id_user`'s field-type config was saved) came back
      `uploaded_by = 'AlanM'`, confirming the mechanism works live, not just historically. Same
      row also confirmed the two-`ID_user`-fields caveat (`id_user` stayed `NULL` on that row —
      see `lessons.md` §11).
- [x] Fresh upload (`game_id 10048`) confirmed `id_user` populates correctly on all five
      downstream tables — `raw_upload_blocks`, `standings_weekly`, `games`, `team_game_stats`
      all passed immediately; `plays` initially failed, traced to `extract_playbyplay.php`
      specifically not having received the `uploaded_by` redeploy that the other three files
      had already gotten (confirmed via the deployed file's actual content, not assumed) —
      fixed and re-verified, now passing. **The whole `id_user` audit-trail thread is closed.**
- [x] Skim the sticky-vs-refresh choice per table in `migration_add_id_user.sql`'s comments
      (games: sticky; everything else: refresh) — matches how each table already treats every
      other column on conflict, but worth your eyes

### team.php

- [x] Season dropdown — pick an old season for a franchise, confirm it shows that season's
      games with working links
- [x] Pick a season you know is `legacy_rollup`-only (any NFLAR franchise, pre-2003) — confirm
      the "No individual game log available" message appears instead of an empty table
- [x] Confirm the default (no `?season=` in URL) still shows the same thing it always did
- [x] Coach: row's "all their teams" link — confirmed working end-to-end (query, data, and
      styling all verified via `var_dump` and View Source, see step 10). Still worth confirming
      it correctly does **not** appear for a franchise with no current tenure holder set.
- [x] Score links in the season-results table — reload and confirm they're now visibly
      underlined (step 10 fix)

### coach.php

- [x] Visit with no `?user=` param while logged in, via DaDaBIK's own "Coaches" left-hand menu
      entry (not the `team.php` link) — **confirms the session-fallback
      (`$_SESSION['logged_user_infos_ar']['id_user']`) genuinely works,** since that route
      carries no `?user=` at all
- [x] Confirm it lists **both** Pittsburgh Panthers (NCAA5) and Philadelphia Eagles (NFLAR) —
      confirmed via screenshot
- [x] Franchise links — reload and confirm they're now visibly underlined (step 10 fix; the
      original screenshot's apparent underline was almost certainly just the table's row
      border, not real link styling)
- [x] `security.md` §7 now has the full worked-out design for how `coaches.id_user` should
      eventually get populated automatically (the `raw_uploads` signal, the per-franchise
      current-coach validation gate, surface-don't-auto-update, the registration-page
      name-matching note) — worth a read since it's real design even though nothing's built
      yet; not something to action from this checklist

### coach.php — Career Record & Championships/Honors (new, undeployed)

Covers **every tenure**, not just current franchises — a coach's full career record, including
franchises they no longer manage. Reuses `team.php`'s existing season-ending-coach tie-break
rule (`coach_for_season()`) for consistency, so a mid-season coaching change attributes that
whole season to whoever finished it, same as everywhere else in this app.

- [x] Deploy and reload — confirm Career Record (Overall + one row per league) and
      Championships & Honors (grouped by league) both render, above the existing franchise list
- [ ] Cross-check Alan Milnes' Overall record against the sum of his own Season by Season table
      on `team.php` (both franchises, all years) — this is real arithmetic worth spot-checking
      once against known numbers, not just eyeballing that a table appeared
- [ ] Confirm **Bowl Wins** shows a single combined count (Cotton/Orange/Hawaii/Music City/CIC
      bucketed together, Rose Bowl broken out separately) rather than every bowl merged —
      deliberate scope decision, flagged in case a full per-bowl breakdown is actually wanted
- [ ] Confirm the league championship row reads **"National Championships"** for NCAA5 and
      **"Superbowl Champions"** for NFLAR — not a generic "League Championships" for both
      (matches `team.php`'s own established wording exactly; the count was always right, only
      the label needed fixing, and needed fixing twice — first attempt guessed at
      `league_name`'s exact string content instead of using the reliable `league_code` check
      `team.php` itself uses)
- [ ] Confirm `RIVALRY_WINNER` is correctly excluded from Honors entirely — deliberate call
      (a season-by-season rivalry result didn't feel like a "career honor" the way a
      championship or bowl win is), reversible if that's wrong
- [ ] Test a coach with career history but **no current franchise** (if one exists in the data)
      — Career Record/Honors should still render, only the franchise list below should show
      the "isn't currently linked to any franchises" message

### current_standings.php

- [ ] Click a team name from the standings page, confirm it lands on the right team page
      (`TEAM_PAGE_STATIC_ID` was `0`/broken, now `4`)
- [ ] Confirm team-name links are now visibly underlined (step 10 fix)

### security.md

- [ ] Read the whole thing once, since it's the reference for every access decision above
- [ ] §2 — confirm group 2 ("Normal") really is unused everywhere today
- [ ] §7 — now describes `coaches.id_user` (corrected) rather than `franchises.coach_user_id`
      (retired) — worth a fresh read even if you read the earlier version already, several
      sections changed meaningfully, not just find-and-replaced

---

## Still open, no deliverable yet — not part of this batch, just don't want it lost

- [ ] `DESCRIBE f_games;` — needed before the `legacy_play_log` → `games.game_id` backfill
      (the NFLAR-PE_s2032_w01_vs_Packers unreachable-game thread) can move from design to an
      actual migration script
- [ ] The `f_games` collision check (`GROUP BY league, season, team HAVING COUNT(DISTINCT
      franchise) > 1`) — same thread, still not run
- [ ] Backlog note for the historical season/game browser gap — worth checking it got added to
      `todo.md`
- [ ] The quarter-bucket "immutable rule" (Q1 0–14 / Q2 15–29 / Q3 30–44 / Q4 45–59 /
      OT 60–75) — belongs in `lessons.md` or `schema.md` as confirmed engine behavior; I don't
      have write access to add it myself
- [ ] `coaches.id_user` auto-population — fully designed (`security.md` §7: `raw_uploads`
      signal, per-franchise current-coach validation, surface-don't-auto-update on mismatch,
      registration-page exact-name instruction), but not built. Worth its own feature slot in
      `todo.md` whenever it's ready to move from design to implementation
- [ ] **Perfect Season honors for NFLAR (pro)** — currently college-only, confirmed two ways:
      `new_schema.sql`'s own comment on `honor_types.code` (verified against the original
      migration data — "12/12 college rows, 24/24 pro rows"), and Alan checked the legacy code
      directly and confirmed it's genuinely only ever referenced for college there too. Not a
      coach.php display bug — the honor type is scoped to NCAA5's `league_id` at the schema
      level, so there's nothing to surface even if the query changed. Rule as given: 16-0
      regular season **and** won the Superbowl that year. Three real pieces once this gets
      picked up, not just one: (1) a new `honor_types` row for NFLAR, (2) a one-time historical
      backfill matching the rule above, (3) an open question — is `franchise_honors` populated
      only by the original historical migration, or is there a live process adding honor rows
      as each season completes? Unknown, not investigated — needed before scoping (2) vs.
      whether an ongoing mechanism is also required, not just a backfill
- [ ] Worth a general note for `schema.md`/`lessons.md`, not just this checklist: DDL comments
      in `new_schema.sql` are the authoritative record of past design decisions, more so than
      `schema.md`'s prose — this session's `coach_user_id` detour happened because a decision
      survived in the former but not the latter
