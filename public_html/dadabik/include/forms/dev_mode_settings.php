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


        
$conn_temp = $conn;
if ($use_db_name_beta === 1 && $_SESSION['dev_mode'] === 'beta'){ // we are connected to the beta db here, so set a temporary conn 
    $conn_temp = connect_db($host, $user, $pass, $db_name);
}
// BETA USERS 
$beta_users_installation = '';
$sql = "SELECT beta_users_installation FROM ".$quote.$prefix_internal_table."installation_tab".$quote;
$res = execute_db($sql, $conn_temp);
$row = fetch_row_db($res);
$beta_users_installation = $row['beta_users_installation'];

if ($beta_users_installation === NULL){
    $beta_users_installation = '';
}

$publications_cnt = 0;

// LATEST PUBBLICATIONS
$sql = "SELECT COUNT(*) FROM ".$quote.$prefix_internal_table."pushes".$quote;
$res = execute_db($sql, $conn_temp);
$row = fetch_row_db($res);
$publications_cnt = $row[0];
if ($publications_cnt > 0){

    $sql = "SELECT * FROM ".$quote.$prefix_internal_table."pushes".$quote." ORDER BY date_time_push DESC";

    if (!isset($_SESSION['show_all_pushes'])){
        $_SESSION['show_all_pushes'] = 0;
    }

    if (isset($_GET['show_all_pushes'])){
        if ($_GET['show_all_pushes'] === '1'){
            $_SESSION['show_all_pushes'] = 1;
        }
        elseif ($_GET['show_all_pushes'] === '0'){
            $_SESSION['show_all_pushes'] = 0;
        }
    }

    if ($_SESSION['show_all_pushes'] === 0){
        $res_pushses = execute_db_limit($sql, $conn, 20, 0);
    }
    else{
        $res_pushses = execute_db($sql, $conn);
    }
}


?>


<p><h1>DEV Mode</h1>

    
<?php

if ($orazio_edition === 1){ 

echo '<span id="confirmation_message_container"><div class="msg_error" id="alert_message"><h4>This feature is <b>DISABLED</b> when you use an '.$orazio_name.' free account.<br>You need to <a href="index.php?function=deploy">publish and deploy</a> this application to enable it.</h4></div></span>';

} ?>

<h2>Settings</h2>
<p>In <b>LIVE</b> Dev Mode, all the changes you make here in the DEV Area or in your custom code files are immediately available to the users of your application.<br><br>In <b>BETA</b> Dev Mode, most of the changes are only visible to admins (and other trusted tester users, if you want), until you decide to publish and make them available to everyone.</p>
<p>If you are new to DaDaBIK, <strong>we suggest to stay in LIVE mode to practice</strong>.<br>The BETA mode is very powerful but if you don't know how to use it you can lose your work. Be sure to read <a href="javascript:show_admin_help('Beta Vs. Live Mode', 'The DaDaBIK manual contains a chapter dedicated to the BETA development mode. This is a <a target=\'_blank\' href=\'https://dadabik.com/index.php?function=show_documentation#beta_dev_mode\'>a link</a> to the online version of the chapter; if you are not using the latest DaDaBIK version, you should read the documentation included in the packged you downloaded.<br><br>');">the documentation</a>  before using it, that may differ.</p>

<form id="change_dev_mode_form">
<input type="hidden" name="reload_after_change" value="0">
<p>You are currently in <b><span id="current_dev_mode_container"><?= $_SESSION['dev_mode'] ?></span></b> mode. Change the mode: <input type="radio" name="dev_mode" value="live" <?php if ($_SESSION['dev_mode'] === 'live'){ echo 'checked';} ?>
>LIVE <input type="radio" name="dev_mode" value="beta" <?php if ($_SESSION['dev_mode'] === 'beta'){ echo 'checked';} ?>
>BETA 
 &nbsp;<input type="submit" class="btn btn-primary" value="Change"></form>

<form id="set_beta_users_form"><p>Set the users who can access your BETA changes (list of usernames, separated by comma):<br><input type="text" name="beta_users" size="80" value="<?= htmlSpecialChars($beta_users_installation); ?>"> &nbsp;<input type="submit" class="btn btn-primary" value="Set Users"></form>

<br>
<h2>Publish your changes (PUSH)</h2>
If you are ready to publish LIVE your changes, write a comment and press PUSH.

<form id="push_form"><textarea name="comment_push" cols="80" rows="5"></textarea><br><input type="submit" class="btn btn-primary" value="Push"></form><br><br>

<h2>PULL your changes</h2>


<form id="pull_form">You can aso <input type="submit" class="btn btn-primary" value="Pull"> your LIVE version to your BETA version.</form>
<br><br>

<h2>Latest publications</h2>
<p>Here is the list of <b>BETA > LIVE</b> publications.

<?php

if ($publications_cnt == 0){
    echo '<p>No publications yet.</p>';
}
else{
    if ($_SESSION['show_all_pushes'] === 0){
        echo ' Only the latest 20 are displayed, <a href="admin.php?function=dev_mode_settings&show_all_pushes=1">show all publications</a>.';
    }
    else{
        echo ' All the publications are displayed, <a href="admin.php?function=dev_mode_settings&show_all_pushes=0">show only the latest 20</a>.';
    }

    echo '<table cellpadding=5><tr bgcolor="#d3d3d3"><th>User</th><th>Date & Time</th><th>Commnet</th></tr>';
    while($row = fetch_row_db($res_pushses)){
        $username_push = $row['username_push'];
        $date_time_push = $row['date_time_push'];
        $comment_push = $row['comment_push'];

        echo '<tr><td>'.htmlspecialchars($username_push).'</td><td>'.htmlspecialchars($date_time_push).'</td><td>'.htmlspecialchars($comment_push).'</td></tr>';
    }
    echo '</table>';
}

?>