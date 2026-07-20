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
<?php

$table_name_examples = build_tables_names_array(0,0);

$path_temp = 'PATH_MYSQLDUMP/';
if (defined("PATH_MYSQLDUMP")){
    $path_temp = PATH_MYSQLDUMP;
}

if ($add_triggers_events_routines_dump === 1){
    $mysqldump_options = '--events --routines --triggers ';
}
else{
    $mysqldump_options = '--skip-events --skip-routines --skip-triggers ';
}

$mysqldump_command_all_tables = escapeshellarg($path_temp)."mysqldump ".$mysqldump_options."-u YOURUSER --password=YOURPASSWORD ".escapeshellarg('YOURDBNAME')." | gzip --best ";

$mysqldump_command_standard =  escapeshellarg($path_temp)."mysqldump -u YOURUSER --password=YOURPASSWORD ".escapeshellarg('YOURDBNAME')." ".$prefix_internal_table.implode(' '.$prefix_internal_table, DADABIK_TABLES)." | gzip --best ";  

?>

<p><h1>Export App</h1>

<p>Exporting the application can be useful both for <strong>backup purposes</strong> and for <strong>transferring the app</strong> to another server.<br>A DaDaBIK application is composed by several files and a database. If you export both files and the database, you  get two .gz files that will allow you to restore the application to exactly its current state.

<p>Unless this app is on your own localhost, we suggest to <strong>export it only if the HTTP connection is secure (HTTPS)</strong>, otherwise some critical info (such as the password of your DB, stored in config_custom.php or the data in your DB) will travel unencrypted on the internet.

<p>We also recommend to export only when nobody is using this app and its DB (except from you) and when there isn't any pending requests to the app or the DB.

<form action="admin.php?function=export_app" method="POST">
<!--<input type="checkbox" onclick="is_disabled = document.getElementById('include_uploads_folder').disabled;document.getElementById('include_uploads_folder').disabled = !is_disabled;" value="1" name="export_files">Export files <a href="javascript:show_admin_help('Export files', 'All files and folders you have in the DaDaBIK root folder (the one containing index.php) of this app will be exported, even files unrelated to DaDaBIK that you may have copied there. <br><br>The only exception is the uploads folder, unless you check <i>Include the uploads folder</i>; the uploads folder can grow to a huge size and slow down the export process, consider this before including it in the export. ');"><img alt="Help" title="Help" border="0" src="images/help.png"></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;-->


<input type="hidden" name="execute_export_files" value="1">
<input type="hidden" name="dont_add_header" value="1">
<button class="btn btn-primary mb-2" type="submit">Export files</button> <a href="javascript:show_admin_help('Export files', 'All files and folders you have in the DaDaBIK root folder (the one containing index.php) of this app will be exported, even files unrelated to DaDaBIK that you may have copied there. <br><br>The only exception is the uploads folder, unless you check <i>Include the uploads folder</i>; the uploads folder can grow to a huge size and slow down the export process, consider this before including it in the export.<br><br>A few additional things you should know about the uploads folder: 
<ul>
<li>It\'s the folder where files uploaded for files fields are permanently stored.</li>
<li>The export process uses <a href=\'https://en.wikipedia.org/wiki/Tar_(computing)\' target=\'_blank\'>Tar</a>; for some old versions of Tar, the exclusion of the uploads folder may not work.</li><li>The inclusion/exclusion option only works for the default /uploads folder: if you set a custom uploads folder outside of this DaDaBIK app root folder, such uploads folder is always excluded; if you set a custom uploads folder inside this DaDaBIK app root folder, such uploads folder is always included.</li></ul> ');"><img alt="Help" title="Help" border="0" src="images/help.png"></a><br>
<input type="checkbox" value="1" name="include_uploads_folder" id="include_uploads_folder">Include the uploads folder 
</form>

<form action="admin.php?function=export_app" method="POST">
<br><br>
<!--<input type="checkbox" onclick="is_disabled = document.getElementById('include_all_tables').disabled;document.getElementById('include_all_tables').disabled = !is_disabled;" value="1" name="export_db_dadabik">Export DB <a href="javascript:show_admin_help('Export DB (DaDaBIK tables)', 'An SQL dump of all the DaDaBIK tables (the ones whose name starts with <b><?php echo $prefix_internal_table; ?></b> e.g. <b><?php echo $prefix_internal_table; ?>forms, <?php echo $prefix_internal_table; ?>permissions</b>, ...) will be produced. If you check <i>Inlcude all tables</i> also the non-DaDaBIK tables will be exported.');"><img alt="Help" title="Help" border="0" src="images/help.png"></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;-->

<input type="hidden" name="execute_export_db" value="1">
<input type="hidden" name="dont_add_header" value="1">
<button class="btn btn-primary mb-2" type="submit">Export Database</button> <a href="javascript:show_admin_help('Export Database', 'An SQL dump of all the DaDaBIK internal tables (the ones whose name starts with <b><?php echo $prefix_internal_table; ?></b> e.g. <b><?php echo $prefix_internal_table; ?>forms, <?php echo $prefix_internal_table; ?>permissions</b>, ...), except the revision tables (the tables where DaDaBIK tracks all the changes to your data, if revisions are enabled) and the views created by DaDaBrain AI will be produced.<br><br>If you check <i>Inlcude all tables</i>, the entire DB <b><?php echo $db_name; ?></b> will be exported, including revision tables, DaDaBrain AI views and non-DaDaBIK tables.<br><br><b>For tech-savvy users, these are the commands executed:</b><br><br>Standard command:<br><div style=\'color:black;background:white;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'><?php echo str_replace("'", "\'", $mysqldump_command_standard); ?></div><br>Include all tables command:<br><div style=\'color:black;background:white;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'><?php echo str_replace("'", "\'", $mysqldump_command_all_tables); ?>');"></div><img alt="Help" title="Help" border="0" src="images/help.png"></a><br>
<input type="checkbox" value="1" name="include_all_tables" id="include_all_tables">Include all tables 



</form>
