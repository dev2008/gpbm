<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<h1>Users and Groups</h1>

<p>User and group management is available from within <a href="<?php echo $dadabik_main_file; ?>?function=search&tablename=<?php echo $users_table_name; ?>">the application you're building</a>. You can access it directly through the Users link in your app's menu, based on the permissions assigned. In most applications, that's all you need to manage users and groups.

However, in some cases the people who use the application also correspond to records in a business table. For example, in a school management system you might have a students table where each student is also a user of the application. In these situations, you can define <b>a user entity</b> from this page.<br><br>

A <b>user entity</b> is a table whose records are also application users. In practice, the table acts as an extension of the users table, storing additional domain-specific information while the users table continues to handle authentication and permissions.<br><br>

For example:

<ul>
    <li>the users table stores login information and security data

    <li>the students table stores student-specific information
    <li>each student record is linked to a corresponding user account
</ul>

This page allows you to define which tables behave as user entities and how they are linked to the users table.<br><br>

For a complete explanation and configuration guidelines, see the <a target="_blank" href="https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=<?= $installed_dadabik_version ?>&chapter=user_entities">User Entities chapter in the manual</a> <br><br>



<?php
?>


<?php

if ($enable_granular_permissions === 0){

    echo '<div class="msg_error" id="alert_message"><p>Error: User entities work properly only if $enable_granular_permissions is set to 1.<br/><br/><a href="javascript:{}" id="alert_message_close_link">Ok, close</a></p></div>';
}



$_POST = unescape_array($_POST);
$_COOKIE = unescape_array($_COOKIE);
$_GET = unescape_array($_GET);


// NOTE: $conn and all _db functions (execute_db, prepare_db, bind_param_db,
// execute_prepared_db, fetch_row_db, fetch_all_db, get_num_rows_db) are
// assumed to be already available from the framework's included files.

// ============================================================
// AJAX HANDLER
// ============================================================
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    ob_clean();
    header('Content-Type: application/json');

    $action = $_POST['action'] ?? '';

    // ---- GET FIELDS FOR A TABLE ----
    if ($action === 'get_fields') {
        $table_name = trim($_POST['table_name'] ?? '');
        if ($table_name === '') {
            echo json_encode(['success' => false, 'fields' => []]);
            exit;
        }
        $fields = get_fields_list($table_name); // returns array of field name strings
        echo json_encode(['success' => true, 'fields' => array_values($fields)]);
        exit;
    }

    // ---- SAVE (insert or update) ----
    if ($action === 'save') {


        $rows = json_decode($_POST['rows'] ?? '[]', true) ?? [];
        $errors = [];
        $saved  = [];




        // Uniqueness check across submitted rows (before hitting the DB)
        $seen_tables = [];
        $seen_groups = [];
        foreach ($rows as $idx => $row) {
            $table    = trim($row['table_user_entity'] ?? '');
            $id_group = intval($row['id_group_user_entity'] ?? 0);

            if ($table !== '') {
                if (isset($seen_tables[$table])) {
                    $errors[] = "Rows " . ($seen_tables[$table] + 1) . " and " . ($idx + 1) . ": duplicate table name \"$table\".";
                } else {
                    $seen_tables[$table] = $idx;
                }
            }
            if ($id_group > 0) {
                if (isset($seen_groups[$id_group])) {
                    $errors[] = "Rows " . ($seen_groups[$id_group] + 1) . " and " . ($idx + 1) . ": duplicate group.";
                } else {
                    $seen_groups[$id_group] = $idx;
                }
            }
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors, 'saved' => []]);
            exit;
        }


        foreach ($rows as $idx => $row) {
            $id        = intval($row['id_user_entity'] ?? 0);
            $table     = trim($row['table_user_entity'] ?? '');
            $id_field  = trim($row['id_user_field_user_entity'] ?? '');
            $fname     = trim($row['first_name_field_user_entity'] ?? '');
            $lname     = trim($row['last_name_field_user_entity'] ?? '');
            $email     = trim($row['email_field_user_entity'] ?? '');
            $sync_c    = !empty($row['sync_create_user_entity']) ? 1 : 0;
            $sync_u    = !empty($row['sync_update_user_entity']) ? 1 : 0;
            $sync_d    = !empty($row['sync_delete_user_entity']) ? 1 : 0;
            $id_group  = intval($row['id_group_user_entity'] ?? 0);

            // Required field validation
            if ($table === '' || $id_field === '' || $id_group === 0) {
                $errors[] = "Row " . ($idx + 1) . ": table name, ID field, and group are required.";
                continue;
            }

            if ($id > 0) {
                // UPDATE
                $sql = "UPDATE " . $quote . $dadabik_user_entities_tab_name . $quote . "
                        SET
                            " . $quote . "table_user_entity" . $quote . "             = :table_user_entity,
                            " . $quote . "id_user_field_user_entity" . $quote . "     = :id_user_field,
                            " . $quote . "first_name_field_user_entity" . $quote . "  = :fname,
                            " . $quote . "last_name_field_user_entity" . $quote . "   = :lname,
                            " . $quote . "email_field_user_entity" . $quote . "       = :email,
                            " . $quote . "sync_create_user_entity" . $quote . "       = :sync_c,
                            " . $quote . "sync_update_user_entity" . $quote . "       = :sync_u,
                            " . $quote . "sync_delete_user_entity" . $quote . "       = :sync_d,
                            " . $quote . "id_group_user_entity" . $quote . "          = :id_group
                        WHERE " . $quote . "id_user_entity" . $quote . " = :id";

                $stmt = prepare_db($conn, $sql);
                bind_param_db($stmt, ':table_user_entity', $table);
                bind_param_db($stmt, ':id_user_field',     $id_field);
                bind_param_db($stmt, ':fname',             $fname);
                bind_param_db($stmt, ':lname',             $lname);
                bind_param_db($stmt, ':email',             $email);
                bind_param_db($stmt, ':sync_c',            $sync_c);
                bind_param_db($stmt, ':sync_u',            $sync_u);
                bind_param_db($stmt, ':sync_d',            $sync_d);
                bind_param_db($stmt, ':id_group',          $id_group);
                bind_param_db($stmt, ':id',                $id);
                $res = execute_prepared_db($stmt, 0);

                //var_dump($res);

                if ($res === false) {
                    $errors[] = "Row " . ($idx + 1) . ": update failed.";
                } else {
                    $saved[] = ['tempId' => $row['tempId'] ?? null, 'id' => $id];
                }
            } else {
                // INSERT
                $sql = "INSERT INTO " . $quote . $dadabik_user_entities_tab_name . $quote . "
                        (" . $quote . "table_user_entity" . $quote . ",
                         " . $quote . "id_user_field_user_entity" . $quote . ",
                         " . $quote . "first_name_field_user_entity" . $quote . ",
                         " . $quote . "last_name_field_user_entity" . $quote . ",
                         " . $quote . "email_field_user_entity" . $quote . ",
                         " . $quote . "sync_create_user_entity" . $quote . ",
                         " . $quote . "sync_update_user_entity" . $quote . ",
                         " . $quote . "sync_delete_user_entity" . $quote . ",
                         " . $quote . "id_group_user_entity" . $quote . ")
                        VALUES
                        (:table_user_entity, :id_user_field, :fname, :lname, :email,
                         :sync_c, :sync_u, :sync_d, :id_group)";

                $stmt = prepare_db($conn, $sql);
                bind_param_db($stmt, ':table_user_entity', $table);
                bind_param_db($stmt, ':id_user_field',     $id_field);
                bind_param_db($stmt, ':fname',             $fname);
                bind_param_db($stmt, ':lname',             $lname);
                bind_param_db($stmt, ':email',             $email);
                bind_param_db($stmt, ':sync_c',            $sync_c);
                bind_param_db($stmt, ':sync_u',            $sync_u);
                bind_param_db($stmt, ':sync_d',            $sync_d);
                bind_param_db($stmt, ':id_group',          $id_group);
                $res = execute_prepared_db($stmt, 0);
                if ($res === false) {
                    $errors[] = "Row " . ($idx + 1) . ": insert failed.";
                } else {
                    $new_id = $conn->lastInsertId();
                    $saved[] = ['tempId' => $row['tempId'] ?? null, 'id' => $new_id];
                }
            }
        }

        echo json_encode([
            'success' => empty($errors),
            'errors'  => $errors,
            'saved'   => $saved,
        ]);
        exit;
    }

    // ---- DELETE ----
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID.']);
            exit;
        }
        $sql_delete = "DELETE FROM " . $quote . $dadabik_user_entities_tab_name . $quote .
                      " WHERE " . $quote . "id_user_entity" . $quote . " = '" . $id . "'";
        $res_delete = execute_db($sql_delete, $conn);
        echo json_encode(['success' => $res_delete !== false]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action.']);
    exit;
}

// ============================================================
// PAGE LOAD — fetch data
// ============================================================
$rows   = [];
$groups = [];

// Load groups
$sql_g = "SELECT " . $quote . $groups_table_id_field . $quote . ", " .
         $quote . $groups_table_name_field . $quote .
         " FROM " . $quote . $groups_table_name . $quote .
         " WHERE " . $quote . $groups_table_id_field . $quote . " != '" . intval($id_admin_group) . "'" .
         " ORDER BY " . $quote . $groups_table_name_field . $quote;
$stmt_g = prepare_db($conn, $sql_g);
execute_prepared_db($stmt_g, 0);
while ($g = fetch_row_db($stmt_g)) {
    $groups[] = $g;
}

// Load entities
$sql_e = "SELECT * FROM " . $quote . $dadabik_user_entities_tab_name . $quote .
         " ORDER BY " . $quote . "id_user_entity" . $quote;
$stmt_e = prepare_db($conn, $sql_e);
execute_prepared_db($stmt_e, 0);
while ($r = fetch_row_db($stmt_e)) {
    $rows[] = $r;
}

// Load full table list for the table dropdown
$all_tables = build_tables_names_array(0, 1, 0); // returns array of table name strings

// Pre-load field lists for tables already referenced in saved rows,
// so field dropdowns are populated correctly on first render (no extra AJAX needed).
$fields_map = [];
foreach ($rows as $r) {
    $tbl = $r['table_user_entity'] ?? '';
    if ($tbl !== '' && !isset($fields_map[$tbl])) {
        $fields_map[$tbl] = array_values(get_fields_list($tbl));
    }
}

$groups_json     = json_encode($groups);
$rows_json       = json_encode($rows);
$tables_json     = json_encode(array_values($all_tables));
$fields_map_json = json_encode($fields_map);
$gid_field       = $groups_table_id_field;
$gname_field     = $groups_table_name_field;
?>
<style>
  #ue-manager {
    --ue-bg:      #f7f7f6;
    --ue-surface: #ffffff;
    --ue-border:  #e2e2de;
    --ue-border2: #d0d0cb;
    --ue-text:    #1a1a18;
    --ue-muted:   #7a7a74;
    --ue-accent:  #2563eb;
    --ue-accenth: #1d4ed8;
    --ue-danger:  #dc2626;
    --ue-success: #16a34a;
    --ue-new-row: #f0f7ff;
    --ue-row-h:   #fafaf9;
    --ue-shadow:  0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
    --ue-radius:  8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: var(--ue-text);
    box-sizing: border-box;
  }
  #ue-manager *, #ue-manager *::before, #ue-manager *::after {
    box-sizing: border-box;
  }

  /* ---- HEADER ---- */
  #ue-manager .ue-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px;
  }
  #ue-manager .ue-page-title { font-size: 22px; font-weight: 600; letter-spacing: -.3px; }
  #ue-manager .ue-page-title span { font-weight: 300; color: var(--ue-muted); font-size: 14px; margin-left: 10px; }
  #ue-manager .ue-header-actions { display: flex; gap: 10px; }

  /* ---- BUTTONS ---- */
  #ue-manager .ue-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--ue-radius);
    font-family: inherit; font-size: 13px; font-weight: 500;
    cursor: pointer; border: none;
    transition: background .15s, transform .1s;
    white-space: nowrap;
    text-decoration: none;
  }
  #ue-manager .ue-btn:active { transform: scale(.97); }
  #ue-manager .ue-btn-primary { background: var(--ue-accent); color: #fff; box-shadow: 0 1px 2px rgba(37,99,235,.25); }
  #ue-manager .ue-btn-primary:hover { background: var(--ue-accenth); }
  #ue-manager .ue-btn-outline { background: var(--ue-surface); color: var(--ue-text); border: 1px solid var(--ue-border2); box-shadow: var(--ue-shadow); }
  #ue-manager .ue-btn-outline:hover { background: var(--ue-row-h); }
  #ue-manager .ue-btn-ghost { background: transparent; color: var(--ue-muted); padding: 6px 8px; }
  #ue-manager .ue-btn-ghost:hover { color: var(--ue-danger); background: #fef2f2; }

  /* ---- NOTICE BAR ---- */
  #ue-notice-bar {
    display: none; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: var(--ue-radius);
    font-size: 13px; font-weight: 500; margin-bottom: 18px;
    border: 1px solid transparent;
  }
  #ue-notice-bar.success { background: #f0fdf4; color: var(--ue-success); border-color: #bbf7d0; display: flex; }
  #ue-notice-bar.error   { background: #fef2f2; color: var(--ue-danger);  border-color: #fecaca; display: flex; }

  /* ---- TABLE WRAP ---- */
  #ue-manager .ue-table-wrap {
    background: var(--ue-surface); border: 1px solid var(--ue-border);
    border-radius: var(--ue-radius); box-shadow: var(--ue-shadow); overflow-x: auto;
  }

  #ue-manager #ue-entities-table { width: 100%; border-collapse: collapse; min-width: 980px; }

  #ue-manager #ue-entities-table thead th {
    background: var(--ue-bg); padding: 11px 14px; text-align: left;
    font-size: 11.5px; font-weight: 600; letter-spacing: .5px;
    text-transform: uppercase; color: var(--ue-muted);
    border-bottom: 1px solid var(--ue-border); white-space: nowrap;
  }

  #ue-manager #ue-entities-table tbody tr { border-bottom: 1px solid var(--ue-border); transition: background .12s; }
  #ue-manager #ue-entities-table tbody tr:last-child { border-bottom: none; }
  #ue-manager #ue-entities-table tbody tr:hover { background: var(--ue-row-h); }
  #ue-manager #ue-entities-table tbody tr.ue-is-new { background: var(--ue-new-row); }
  #ue-manager #ue-entities-table tbody tr.ue-is-new:hover { background: #e8f3ff; }
  #ue-manager #ue-entities-table tbody td { padding: 10px 14px; vertical-align: middle; }

  /* ---- FORM CONTROLS ---- */
  #ue-manager .ue-cell-input {
    width: 100%; font-size: 12.5px;
    padding: 6px 9px; border: 1px solid var(--ue-border); border-radius: 5px;
    background: transparent; color: var(--ue-text);
    transition: border .15s, box-shadow .15s; min-width: 90px;
  }
  #ue-manager .ue-cell-input:focus {
    outline: none; border-color: var(--ue-accent);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1); background: #fff;
  }
  #ue-manager select.ue-cell-input { cursor: pointer; }
  #ue-manager select.ue-cell-input:disabled { color: var(--ue-muted); cursor: not-allowed; font-style: italic; opacity: .7; }

  #ue-manager .ue-cell-id { font-size: 12px; color: var(--ue-muted); }

  /* ---- TOGGLE CHECKBOXES ---- */
  #ue-manager input[type="checkbox"].ue-toggle {
    appearance: none; width: 36px; height: 20px;
    background: var(--ue-border2); border-radius: 20px;
    cursor: pointer; position: relative; transition: background .2s; flex-shrink: 0;
  }
  #ue-manager input[type="checkbox"].ue-toggle::after {
    content: ''; position: absolute; width: 14px; height: 14px;
    background: #fff; border-radius: 50%; top: 3px; left: 3px;
    transition: left .2s; box-shadow: 0 1px 2px rgba(0,0,0,.2);
  }
  #ue-manager input[type="checkbox"].ue-toggle:checked { background: var(--ue-accent); }
  #ue-manager input[type="checkbox"].ue-toggle:checked::after { left: 19px; }

  /* ---- SYNC GROUP ---- */
  #ue-manager .ue-sync-group { display: flex; gap: 12px; justify-content: center; }
  #ue-manager .ue-sync-item { display: flex; flex-direction: column; align-items: center; gap: 4px; }
  #ue-manager .ue-sync-label { font-size: 10px; color: var(--ue-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .4px; }

  /* ---- TABLE FOOTER ---- */
  #ue-manager .ue-table-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; border-top: 1px solid var(--ue-border);
    background: var(--ue-bg); border-radius: 0 0 var(--ue-radius) var(--ue-radius);
  }

  /* ---- SPINNER ---- */
  #ue-manager .ue-spinner {
    display: inline-block; width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
    border-radius: 50%; animation: ue-spin .6s linear infinite; vertical-align: middle;
  }
  @keyframes ue-spin { to { transform: rotate(360deg); } }

  /* ---- EMPTY STATE ---- */
  #ue-manager .ue-empty-row td { text-align: center; padding: 40px; color: var(--ue-muted); font-size: 13px; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<div id="ue-manager">

<div class="ue-page-header">
  <div>
    <h3>User Entities <font color="red">(New!)</font></h3>
  </div>
  
  <div class="ue-header-actions">
    <button class="ue-btn ue-btn-outline" onclick="addRow()">+ Add Row</button>
    <button class="ue-btn ue-btn-primary" id="ue-save-btn" onclick="saveAll()">Save Changes</button>
  </div>
</div>

<div id="ue-notice-bar"></div>

<div class="ue-table-wrap">
  <table id="ue-entities-table">
    <thead>
      <tr>
        <th style="width:46px">ID</th>
        <th>Table Name <span style="color:#c00">*</span></th>
        <th>ID User Field <span style="color:#c00">*</span></th>
        <th>First Name Field</th>
        <th>Last Name Field</th>
        <th>Email Field</th>
        <th style="text-align:center">Sync</th>
        <th>Group <span style="color:#c00">*</span></th>
        <th style="width:48px"></th>
      </tr>
    </thead>
    <tbody id="ue-table-body"></tbody>
  </table>
  <div class="ue-table-footer">
    <span style="font-size:12px; color:var(--ue-muted)">
      <span style="color:#c00">*</span> Required &nbsp;·&nbsp; Blue rows are unsaved
    </span>
    <button class="ue-btn ue-btn-primary" onclick="saveAll()">Save Changes</button>
  </div>
</div>

</div><!-- #ue-manager -->

<script>
// ============================================================
// BOOT DATA (PHP → JS)
// ============================================================
const GROUPS     = <?= $groups_json ?>;
const GID        = <?= json_encode($gid_field) ?>;
const GNAME      = <?= json_encode($gname_field) ?>;
const ALL_TABLES = <?= $tables_json ?>;        // string[] — full table list for table dropdown
let   FIELDS_MAP = <?= $fields_map_json ?>;    // { tableName: string[] } — pre-loaded for existing rows

let rows        = <?= $rows_json ?>;
let tempCounter = 1;

// ============================================================
// HELPERS
// ============================================================
function esc(str) {
  return String(str ?? '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function buildFieldOptions(fields, currentVal) {
  // First option is always a blank placeholder
  let html = '<option value="">— select field —</option>';
  html += fields.map(f =>
    `<option value="${esc(f)}" ${currentVal === f ? 'selected' : ''}>${esc(f)}</option>`
  ).join('');
  return html;
}

// ============================================================
// RENDER
// ============================================================
function render() {
  const tbody = document.getElementById('ue-table-body');

  if (rows.length === 0) {
    tbody.innerHTML = '<tr class="ue-empty-row"><td colspan="9">No records yet. Click <strong>+ Add Row</strong> to create one.</td></tr>';
    return;
  }

  tbody.innerHTML = rows.map((r, i) => {
    const isNew  = !r.id_user_entity;
    const idCell = isNew
      ? `<span style="color:var(--ue-muted);font-size:11px">new</span>`
      : `<span class="ue-cell-id">${r.id_user_entity}</span>`;

    // ---- Table dropdown (always fully populated from ALL_TABLES) ----
    const tableOptions = ALL_TABLES.map(t =>
      `<option value="${esc(t)}" ${r.table_user_entity === t ? 'selected' : ''}>${esc(t)}</option>`
    ).join('');

    // ---- Field dropdowns ----
    const hasTable = r.table_user_entity !== '';
    let fields = null;
    if (hasTable) {
        fields = FIELDS_MAP[r.table_user_entity] ?? null;
    }
    const loading = hasTable && fields === null;

    function fieldSelect(id, fieldKey) {
      if (!hasTable) {
        return `<select id="${id}" class="ue-cell-input" disabled
                        onchange="setField(${i},'${fieldKey}',this.value)">
                  <option value="">— pick a table first —</option>
                </select>`;
      }
      if (loading) {
        return `<select id="${id}" class="ue-cell-input" disabled
                        onchange="setField(${i},'${fieldKey}',this.value)">
                  <option value="">Loading…</option>
                </select>`;
      }
      return `<select id="${id}" class="ue-cell-input"
                      onchange="setField(${i},'${fieldKey}',this.value)">
                ${buildFieldOptions(fields, r[fieldKey] || '')}
              </select>`;
    }

    // ---- Group dropdown ----
    const groupOptions = GROUPS.map(g =>
      `<option value="${esc(g[GID])}" ${String(r.id_group_user_entity) === String(g[GID]) ? 'selected' : ''}>${esc(g[GNAME])}</option>`
    ).join('');

    return `
    <tr class="${isNew ? 'ue-is-new' : ''}" data-idx="${i}">
      <td>${idCell}</td>

      <td>
        <select class="ue-cell-input" onchange="onTableChange(${i}, this.value)">
          <option value="">— select table —</option>
          ${tableOptions}
        </select>
      </td>

      <td>${fieldSelect('ue-f-id-'+i,    'id_user_field_user_entity')}</td>
      <td>${fieldSelect('ue-f-fname-'+i, 'first_name_field_user_entity')}</td>
      <td>${fieldSelect('ue-f-lname-'+i, 'last_name_field_user_entity')}</td>
      <td>${fieldSelect('ue-f-email-'+i, 'email_field_user_entity')}</td>

      <td>
        <div class="ue-sync-group">
          <div class="ue-sync-item">
            <input type="checkbox" class="ue-toggle" ${parseInt(r.sync_create_user_entity) === 1 ? 'checked' : ''}
                   onchange="setField(${i},'sync_create_user_entity', this.checked ? 1 : 0)"
            <span class="ue-sync-label">Create</span>
          </div>
          <div class="ue-sync-item">
            <input type="checkbox" class="ue-toggle" ${parseInt(r.sync_update_user_entity) === 1 ? 'checked' : ''}
                   onchange="setField(${i},'sync_update_user_entity',this.checked ? 1 : 0)"
            <span class="ue-sync-label">Update</span>
          </div>
          <div class="ue-sync-item">
            <input type="checkbox" class="ue-toggle" ${parseInt(r.sync_delete_user_entity) === 1 ? 'checked' : ''}
                   onchange="setField(${i},'sync_delete_user_entity',this.checked ? 1 : 0)"
            <span class="ue-sync-label">Delete</span>
          </div>
        </div>
      </td>

      <td>
        <select class="ue-cell-input" onchange="setField(${i},'id_group_user_entity',this.value)">
          <option value="">— select —</option>
          ${groupOptions}
        </select>
      </td>

      <td>
        <button class="ue-btn ue-btn-ghost" title="Delete row" onclick="deleteRow(${i})">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
            <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
          </svg>
        </button>
      </td>
    </tr>`;
  }).join('');
}

// ============================================================
// TABLE CHANGE → fetch fields via AJAX, then re-render
// ============================================================
function onTableChange(idx, tableName) {
  // Reset all field selections when the table changes
  rows[idx].table_user_entity              = tableName;
  rows[idx].id_user_field_user_entity      = '';
  rows[idx].first_name_field_user_entity   = '';
  rows[idx].last_name_field_user_entity    = '';
  rows[idx].email_field_user_entity        = '';

  if (!tableName) {
    render();
    return;
  }

  // Already cached — no need to fetch
  if (FIELDS_MAP[tableName] !== undefined) {
    render();
    return;
  }

  // Mark as "loading" in the map (null = in-flight)
  FIELDS_MAP[tableName] = null;
  render(); // renders field selects in disabled/loading state

  ajax({ action: 'get_fields', table_name: tableName }, function(res) {
    FIELDS_MAP[tableName] = (res.success && Array.isArray(res.fields)) ? res.fields : [];
    render();
  });
}

// ============================================================
// MUTATIONS
// ============================================================
function setField(idx, field, value) {
  rows[idx][field] = value;
}

function addRow() {
  rows.push({
    id_user_entity:               null,
    tempId:                       'new_' + (tempCounter++),
    table_user_entity:            '',
    id_user_field_user_entity:    '',
    first_name_field_user_entity: '',
    last_name_field_user_entity:  '',
    email_field_user_entity:      '',
    sync_create_user_entity:      0,
    sync_update_user_entity:      0,
    sync_delete_user_entity:      0,
    id_group_user_entity:         '',
  });
  render();
  const tbody   = document.getElementById('ue-table-body');
  const lastRow = tbody.lastElementChild;
  if (lastRow) {
    lastRow.querySelector('select.ue-cell-input')?.focus();
    lastRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

function deleteRow(idx) {
  const row = rows[idx];
  if (!row) return;

  if (!row.id_user_entity) {
    // Not yet saved — remove from UI only
    rows.splice(idx, 1);
    render();
    return;
  }

  if (!confirm('Delete this record?')) return;

  ajax({ action: 'delete', id: row.id_user_entity }, function(res) {
    if (res.success) {
      rows.splice(idx, 1);
      render();
      notify('Record deleted.', 'success');
    } else {
      notify(res.error || 'Delete failed.', 'error');
    }
  });
}

// ============================================================
// CLIENT-SIDE VALIDATION
// ============================================================
function validateRows() {
  const errors     = [];
  const seenTables = {};
  const seenGroups = {};

  rows.forEach((r, i) => {
    const n = i + 1;

    // Required fields
    if (!r.table_user_entity)           errors.push(`Row ${n}: table name is required.`);
    if (!r.id_user_field_user_entity)   errors.push(`Row ${n}: ID field is required.`);
    if (!r.id_group_user_entity)        errors.push(`Row ${n}: group is required.`);

    // Duplicate table
    if (r.table_user_entity) {
      if (seenTables[r.table_user_entity] !== undefined) {
        errors.push(`Rows ${seenTables[r.table_user_entity] + 1} and ${n}: duplicate table "${r.table_user_entity}".`);
      } else {
        seenTables[r.table_user_entity] = i;
      }
    }

    // Duplicate group
    if (r.id_group_user_entity) {
      if (seenGroups[r.id_group_user_entity] !== undefined) {
        errors.push(`Rows ${seenGroups[r.id_group_user_entity] + 1} and ${n}: duplicate group.`);
      } else {
        seenGroups[r.id_group_user_entity] = i;
      }
    }
  });

  return errors;
}

// ============================================================
// SAVE
// ============================================================
function saveAll() {
  const clientErrors = validateRows();
  if (clientErrors.length) {
    notify(clientErrors.join(' · '), 'error');
    return;
  }

  const btn = document.getElementById('ue-save-btn');
  btn.innerHTML = '<span class="ue-spinner"></span> Saving…';
  btn.disabled  = true;

  ajax({ action: 'save', rows: JSON.stringify(rows) }, function(res) {
    btn.innerHTML = 'Save Changes';
    btn.disabled  = false;

    if (res.success) {
      // Stamp real IDs onto newly inserted rows
      if (res.saved && res.saved.length) {
        res.saved.forEach(s => {
          const r = rows.find(x => x.tempId && x.tempId === s.tempId);
          if (r) { r.id_user_entity = s.id; r.tempId = undefined; }
        });
      }
      render();
      notify('Changes saved successfully.', 'success');
    } else {
      const msg = (res.errors && res.errors.length) ? res.errors.join(' · ') : 'Save failed.';
      notify(msg, 'error');
    }
  });
}

// ============================================================
// AJAX
// ============================================================
function ajax(data, cb) {
  const fd = new FormData();
  for (const k in data) fd.append(k, data[k]);

  fetch(window.location.href, {
    method:  'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body:    fd,
  })
  .then(r => r.json())
  .then(cb)
  .catch(() => {
    notify('Network error. Please try again.', 'error');
    const btn = document.getElementById('ue-save-btn');
    if (btn) { btn.innerHTML = 'Save Changes'; btn.disabled = false; }
  });
}

// ============================================================
// NOTIFY
// ============================================================
function notify(msg, type) {
  const bar = document.getElementById('ue-notice-bar');
  bar.className   = type;
  bar.textContent = (type === 'success' ? '✓ ' : '⚠ ') + msg;
  bar.style.display = 'flex';
  clearTimeout(bar._t);
  bar._t = setTimeout(() => { bar.style.display = 'none'; }, 5000);
}

// ============================================================
// INIT
// ============================================================
render();
</script>