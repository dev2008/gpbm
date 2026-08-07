# Security & Access Control (DRAFT)

Status: draft, written from what's been confirmed in conversation so far — check every
"confirmed" claim below against what you actually meant, and correct anything that's wrong or
incomplete rather than editing around it silently. This covers *what's shown to whom and why*
(authorization). It does not cover login/authentication itself (how `id_group` gets set,
password handling, session security) or database-level access control (MySQL grants) — both
are real topics, just not this document.

---

## 1. Why this exists

This site has two audiences with genuinely different information rights, not just two levels
of the same thing:

- **The public** — anyone browsing the site, logged in or not. This includes every other
  player-owner in the league (e.g. Danny), not just anonymous visitors. **Confirmed: for the
  purposes of this site, a player-owner who isn't family is just an ordinary member of the
  public** — GM status doesn't grant any elevated access.
- **Family** — currently Alan and his son, the two coaches who share strategy information with
  each other that would be a competitive advantage if any other player-owner saw it.

Nothing below is about keeping data safe from malicious actors in a general sense — it's about
not accidentally showing one coach's game-planning detail to the coaches they play against.

---

## 2. The groups

| `id_group` | Meaning | Who's in it | Current custom-page behavior |
|---|---|---|---|
| `1` | Administrator | **Confirmed: exactly Alan and his son, nobody else.** For this site, "Administrator" and "family" are the same population. | Sees everything, including family-only content. |
| `2` | Normal | Confirmed to exist as a group, not yet populated/used for anything on the custom pages built so far. Future home for other coaches (see §7). | **Currently identical to Public** — the admin gate is `$current_user_is_administrator`, which group 2 doesn't have, so it gets exactly the same view as group 3 today. Nothing elevates it yet. |
| `3` | Public/guest | Not logged in, or any other player-owner without an elevated group. Established earlier (`home.php`) as the existing public-detection value. | Public view only. |

**Design principle — allow-list family, don't block-list public.** The admin check
(`$current_user_is_administrator`, §4) doesn't reference `id_group` at all anymore, which is
the strongest version of this principle — nothing to keep correct as group numbers change,
because there's no group number in the check to begin with. The public check still does name a
group directly (`$current_id_group == 3`, §4) since DaDaBIK has no equivalent
`$current_user_is_public` global — but it's still phrased as the narrow allow-list ("is this
specifically group 3") rather than a block-list ("is this not group 1"), which matters for the
same reason: a block-list phrasing would have already been silently wrong the moment group 2
became meaningful for anything, where the allow-list phrasing stays correct regardless. Keep
new gating checks phrased this way — narrow allow-list, never a broad block-list — even where,
as with the public check, a specific group number can't be avoided entirely.

---

## 3. What's public vs. family-only

| Content | Status | Access |
|---|---|---|
| Game results / scores | Built (`team.php`, `game.php` header, box score) | Public |
| Standings | Built (`current_standings.php`) | Public |
| Summary roster | Not built yet (Feature 8) | Public |
| Detailed roster | Not built yet (Feature 8) | **Family only** |
| Play by play | Built (`game.php`) | **Family only** |
| Drive summary / matchup scouting detail | Built (`game.php`) | **Family only** |
| Live Replay (spoiler-free playback) | Built (`replay.php`) | **Family only** |

Confirmed explicitly: game results, standings, and summary rosters are public; detailed roster,
play by play, and matchup detail are family-only. `replay.php` carries the same play-by-play
data as `game.php`'s Playback mode, just without the score/box score alongside it — same
content, same access rule, different page.

---

## 4. How it's implemented today

Uses DaDaBIK's own custom-code globals (confirmed from DaDaBIK's docs — worth having as a
standing reference beyond just these two checks):

| Global | What it holds |
|---|---|
| `$conn` | PDO connection — already used everywhere |
| `$current_user` | Username of the logged-in user |
| `$current_id_group` | The logged-in user's `id_group` |
| `$current_user_is_administrator` | `1`/`0` — is the logged-in user in the administrators group |
| `$quote` | Correct identifier-quote character for the current DBMS |

`game.php`'s family gate:

```php
$_cp_is_admin = ($current_user_is_administrator == 1);
```

No group ID involved at all — this keeps working correctly even if the Administrator group's
`id_group` value ever changed. `game.php` wraps both the **section headers and the data** for
Play by Play and Drive Summary in this check — not just the data. A non-family visitor sees no
trace the feature exists at all, rather than an empty section or a "members only" placeholder
that would itself leak information (that something exists, that it's worth asking about).

`home.php`'s public/guest check:

```php
$_cp_is_public_user = ($current_id_group == 3);
```

This one still names a specific group ID — DaDaBIK's globals give an
`$current_user_is_administrator` flag but no equivalent `$current_user_is_public` one, so
there's no way to ask "is this definitely nobody logged in" without naming group 3 somewhere.
There's only ever one public user by design, so checking the group rather than a specific
username is still both simpler and correct regardless of what that account happens to be named
or whether it's ever renamed. `home.php` also uses `$current_user` directly now (for the
logged-in username shown in the greeting), replacing the same kind of manual session read.

**Both switched over from manually reading `$_SESSION['logged_user_infos_ar'][...]`** — that
was the original implementation, replaced now while still in local testing, before either page
carries any real traffic. **Not yet tested against a real login** — worth confirming
`$current_user_is_administrator`/`$current_id_group` actually behave as expected for both an
Administrator account and a guest before relying on this. `$quote` isn't relevant to anything
built so far — no custom SQL in this project currently hardcodes backtick-quoted identifiers —
but worth knowing it exists if that ever changes.

This is presentation-layer gating only — enforced by each page's own PHP, not by anything at
the database level. It's only as good as every custom page remembering to apply it; there's
currently no fallback that protects a page which simply forgets the check.

---

## 5. Checklist for new pages/features

Before shipping any new custom page:

1. Does it show anything beyond game results, standings, or summary rosters?
2. If yes, wrap the relevant section(s) — header included — in the `$current_user_is_administrator`
   check above. If the page is a mix (like `game.php`: box score public, play-by-play
   family-only), gate only the specific sections, not the whole page.
3. Don't rely on a page being hard to find (an unlinked/unregistered `id_static_page`, an
   unguessable-feeling URL param) as the actual protection — `current_standings.php`'s stale
   `TEAM_PAGE_STATIC_ID` placeholder already showed that link wiring breaks silently and
   independently of any of this; the admin check is what actually protects the content, not how
   the link got there.

**Immediate open item:** Feature 8 (roster pages) isn't built yet — when it is, the detailed
roster needs the same `$current_user_is_administrator` gate `game.php`'s Play by Play/Drive
Summary already use, same header-and-data-both-hidden treatment. Summary roster ships public,
same as everything else in that row of the table above.

---

## 6. Not covered here (flag if these need their own document)

- How `id_group` actually gets set — login flow, session creation, anything about Dadabik's own
  user management.
- Password/credential handling.
- Database-level permissions (MySQL grants on `gplan_pbm` itself).
- Anything about `gplan_main`/legacy tables — those aren't web-exposed at all currently, this
  document is only about what the PHP front-end shows.

---

## 7. Future: opening group 2 up to other coaches

**Confirmed direction:** eventually other coaches may get "Normal" (group 2) accounts, using
DaDaBIK's own **owner permissions** feature (Enterprise/Platinum: delete/modify/view only your
own records) to scope what they see to their own franchise. That feature requires a field of
DaDaBIK type `ID_user` on every table/view being protected — DaDaBIK auto-populates it with the
current username on insert, but **only for records inserted through DaDaBIK's own forms.**

**Now underway, ahead of actually needing it** — future record-keeping should track who
submitted it from the start, since retrofitting later means an ownerless backlog. `id_user
VARCHAR(50) NULL` (nullable — all current data predates this, honest NULL over a fabricated
value; width confirmed against `zpbm_users.username_user`, not guessed) has been added to every
table with a live insert/upsert path today:

| Table | Populated by | id_user source |
|---|---|---|
| `raw_uploads` | DaDaBIK's own form (confirmed — the after-insert hook fires on it) | **Automatic**, once the field is configured as type `ID_user` in the permissions manager — no code needed |
| `raw_upload_blocks` | `operational_hooks.php` (custom PDO) | Copied from the parent upload's `id_user`, not re-derived from session |
| `standings_weekly` | `extract_standings.php` (custom PDO) | Same, from the upload — **see caveat below** |
| `games`, `team_game_stats` | `extract_games.php` (custom PDO) | Same, from the upload |
| `plays` | `extract_playbyplay.php` (custom PDO) | Same, from the upload |
| `drives` | *(no writer yet)* | Column added proactively — whichever page eventually extracts Drive Summary just needs to populate it, not also add it |

Migration: `migration_add_id_user.sql`. Not run against the live database yet.

**Caveat worth keeping in view — and this one's a real limit, not just a rough edge:**
ownership-by-submitter only cleanly means "whose data this is" when exactly one side of a
relationship ever submits it. That holds for `raw_uploads`/`raw_upload_blocks` — each row is
genuinely one specific coach's own uploaded file, and if both participants in a game each
upload their own turn, that's two separate `raw_uploads` rows, no collision.

It does **not** hold for `games`/`team_game_stats`/`plays`. A game has two legitimate
stakeholders, but `id_user` can only ever hold one value — and DaDaBIK's owner-permission
model is explicitly single-owner (*"the owner of a record is the user who inserted it"*,
singular). If both participating coaches eventually upload their own turn for the same shared
game, whichever one's upload didn't "win" the column has no way to be recognized as an owner of
their own game's data, no matter how the upsert conflict is resolved (sticky-to-first locks out
whoever uploads second; refresh-to-latest locks out whoever uploaded first). This isn't a bug
in the upsert logic — it's DaDaBIK's owner-permission feature genuinely not fitting a two-sided
relationship.

**Consequence:** `id_user` on `games`/`team_game_stats`/`plays` is being kept as an audit/
provenance field ("who most recently submitted or confirmed this data"), not as the field a
future access gate should key off. The correct future gate for "a coach sees their own team's
games/stats/plays" is **franchise participation** — is the logged-in coach's franchise either
`home_franchise_id` or `away_franchise_id` on the game — checked directly, the same way
`$current_user_is_administrator` is a direct check today, not derived from `id_user`. That
requires a not-yet-built mapping from login to "which franchise do you own," which isn't being
built now, just flagged so nobody reaches for `id_user` there later and gets it wrong. (This
mapping now already exists in one sense — `coaches.id_user` → `franchise_coach_tenures`, per
above — worth checking whether that same join is the right tool for this too when the time
comes, rather than building a second one.)

`standings_weekly` was already flagged separately, for a related but distinct reason — a
Standings block spans all 24 franchises, not two, so `id_user` there doesn't track "whose"
data it is at all, for anyone. Same underlying lesson (a single-owner field can't represent
data with more than one legitimate owner), different shape of problem.

Two mechanisms worth keeping distinct, not conflated just because they'll eventually share this
column:

- **Public vs. family (§3)** — a *content-type* split: the same page shows or hides an entire
  section depending on who's looking, no notion of "whose" data it is.
- **Group 2 + owner permissions** — a *row-ownership* split: the same table shows different
  *rows* depending on who inserted them.

And one implementation note for whenever this actually gets turned on: DaDaBIK's automatic
`ID_user` population is native-form-only. `raw_uploads` gets it for free; every other table
above needed `id_user` added to its INSERT/upsert statements by hand, because those are custom
PDO code, not DaDaBIK forms. Any *future* custom page that inserts into a protected table will
need to do the same — it doesn't come free just because the column exists.

**The missing piece for that franchise-participation check — already existed, just not where
first assumed.** `coaches.id_user` — a nullable lookup to `zpbm_users.id_user`, already present
in both `new_schema.sql` and the live database before any of this session's work started.
First pass at this feature (session history, corrected below) built a *new* column,
`franchises.coach_user_id`, without knowing `coaches.id_user` already existed for the same
purpose — the design decision behind it survived as an inline SQL comment in `new_schema.sql`
but never made it into this document's or `schema.md`'s narrative summary of a much longer,
separate conversation, so it didn't surface until asked about directly. Once found,
`franchises.coach_user_id` was retired (`migration_drop_coach_user_id.sql`) and `coach.php`/
`team.php` rewritten against `coaches.id_user` instead. Worth remembering generally: this
schema's own DDL comments are more authoritative than this document's prose summary of them —
worth grepping the actual `.sql` directly before assuming a summary is complete.

`coaches.id_user` is deliberately **NOT UNIQUE**, against DaDaBIK's own User Entities
recommendation, on purpose — the same real person can coach in both leagues at once (confirmed
in the real data: Alan Milnes has two `coaches` rows, one per league), so one login legitimately
needs to map to more than one `coaches` row. `franchises.coach_user_id` couldn't represent that
as cleanly (one column, one franchise, no natural way to express "also coaches this other
franchise in the other league" without a second lookup elsewhere) — `coaches.id_user` handles
it for free, since each league's coaching identity is already its own row.

Current path from a login to "which franchises does this person control right now":
`zpbm_users.id_user` → `coaches.id_user` (every `coaches` row for that person, one per league
they're active in) → `franchise_coach_tenures` filtered to `end_week_id IS NULL` (that coach's
currently-open tenure, if any) → `franchise_id` → `franchises`. `coach.php` and `team.php` (its
"all their teams" link on the Coach: row) both use exactly this join now.

**How `coaches.id_user` actually gets populated — designed, not yet built.** The question worth
being precise about: not "how do we associate `zpbm_users` IDs with team coaches" — that's
ambiguous between two genuinely different tenses. `coaches.name` is the historical record (who
coached this team when, back to 1989, free text from turn box scores). `coaches.id_user` is the
current fact (does this specific coach record correspond to an active website login) — same
row, different tense, deliberately still two separate columns rather than collapsed into one.

The signal for keeping `id_user` current already exists without building anything new:
`raw_uploads` carries both `id_user` (who uploaded — automatic, DaDaBIK's own form) and
`franchise_id` (which franchise the turn was for) on the same row, every time. Every turn
upload is natively a `(coach, franchise)` pairing — *when it's genuinely that coach's own
upload.*

**It's not automatically that, though, and this is the part worth being careful about.**
Nothing stops an Administrator (or, in principle, any logged-in user) from uploading a turn for
a franchise that isn't theirs — filling in for someone, fixing a mistake, or in principle
something less innocent. So the design isn't "trust every upload's (id_user, franchise_id)
pairing" — it's:

1. Candidate signal: an upload's `(id_user, franchise_id)` pair.
2. Resolve which `coaches` row currently holds that franchise's open tenure (same join as
   above, in reverse — franchise → current tenure → coach_id).
3. Validation gate: does the uploader's registered name match *that specific* `coaches.name`?
   (Not "matches some coach somewhere" — matches the one currently holding this franchise.)
4. **Match → fine to update that `coaches` row's `id_user` automatically. Mismatch → surface it
   for an Administrator to review, never auto-update.**

That fourth point is the one worth stating as a general rule, not just a decision about this
one feature: **a permission-relevant field should never be silently updated from a path that
includes user-supplied content, no matter how indirect.** Whether that's someone hand-editing
turn text before uploading, or just selecting the wrong franchise from a dropdown (mistake or
otherwise) — either way, auto-granting `id_user` off the back of it would mean the access
decision is effectively being made by whoever's logged in, not by an Administrator. Surfacing
instead of auto-updating keeps that decision on the right side of the line. Worth applying this
same reasoning anywhere else this project ends up deriving something access-relevant from
uploaded or parsed data, not just here.

Known limitation of the match, addressed at the *source* rather than by fuzzy-matching text:
`coaches.name` is free text pulled from turn-file box scores ("Alan Milnes"), and comparing
that against `zpbm_users.first_name_user`/`last_name_user` is fragile on formatting alone (case,
middle names, nicknames) even when the underlying match is genuinely correct. Rather than build
normalization logic to compensate, the plan is to prevent the mismatch at registration time —
whenever a registration page exists, it should carry an explicit, prominent instruction:
**register your name exactly as it appears on your turns.**

Residual risk, accepted rather than solved: a non-admin coach uploading *another* coach's turn
as a favor would still pass the validation gate if the names happen to line up (unlikely, low
stakes, but a real gap — not the same as the admin case, which the mismatch-surfacing design
already handles cleanly by treating every uploader identically, admin or not).

Not being built now — this is design for a future enhancement, captured here so the reasoning
doesn't just live in conversation history.

This page carries no new privacy exposure and isn't gated — which franchise a coach manages is
already public via `team_game_stats.coach_name` on every game page.
