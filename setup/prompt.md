# Task: Implement Union Views for Legacy Play Log and Live Plays

## Context
We are modernizing a complex legacy system (transitioning from a bespoke Windows tool, Excel, and legacy web frontend) into a pure web application.

Please carefully review the following project files before beginning:
- `schema.md` (for database structure and table definitions)
- `todo.md` (for broader task tracking)
- `lessons.md` (for established architectural patterns and past pitfalls to avoid)
- `new_schema.sql` (for the schema as designed)
- `gplan_pbm__2026-08-05_19-31.sql` (for the schema as implemented, hopefully the same!!)

## Objective
Implement new database views that combine data from `legacy_play_log` and the new `plays` table, satisfying **Feature 1** from our task list.

### Specific Requirements
1. **Do not modify or retrofit existing views:** The current eight `v_playcall_matchup`-style aggregate views must remain untouched because they exclusively query `legacy_play_log`.
2. **Build dedicated union views:** Create new views specifically designed to merge historical data from `legacy_play_log` with live data from `plays` once real data is present.
3. **Consistency:** Ensure column naming, data types, and aggregation logic align with the patterns established in `schema.md` and any relevant conventions noted in `lessons.md`.
4. **Ask for clarification if needed** If anything is unclear or needs a decision refer it the user before progressing.

## Deliverables
- The SQL script / migration file to create the new union views.
- Brief verification steps or test queries to ensure both data sources are correctly represented in the output.

## Scope
To keep this managable we will break it down into logical groupings so our first stage is these three views:-
 `v_playcall_formation`
 `v_playcall_matchup`
 `v_playcall_matchup_formation`
 
These 3 are all aggregate functions and need to retain one output per matchup 

e.g. 
localhost:3306/gplan_pbm/v_playcall_formation/		http://localhost/phpmyadmin/index.php?route=/table/search&db=gplan_pbm&table=v_playcall_formation

   Showing rows 0 -  1 (2 total, Query took 0.0297 seconds.)


SELECT *  FROM `v_playcall_formation` WHERE `formation_code` LIKE 'W' AND `offense_call_code` LIKE 'CW'


sport_type	formation_code	offense_call_code	times_called	avg_yards	
pro	W	CW	52	4.17	
college	W	CW	1035	5.07	

This shows how many times the counter trap play (CW) has been called and the average yardage gained, this is broken down by type (Pro and College), the result of this task will be the same output but reflecting both legacy data and data from the new `plays` table.   
