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
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap_5.3.2.css">

<link rel="stylesheet" href="css/normalize.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/styles_screen_13.5.css?c=<?php echo rand(1,1000); ?>" type="text/css">

    <title>Install/Upgrade DaDaBIK</title>



    <script src="include/jquery/jquery_3.7.1.min.js"></script>
<script src="include/jquery/jquery-ui-1.14.1/jquery-ui.min.js"></script>
<script src="include/general_functions_13.0.js"></script>
<link rel="stylesheet" href="include/jquery/jquery-ui-1.14.1/jquery-ui.min.css" type="text/css" media="screen">

<link rel="stylesheet" href="include/fontawesome5/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/fontawesome.css"> <!-- fontawesome4 -->

<script>
$(document).ready(function() {

$('#confirmation_message_container').on('click', '#error_message_close_link', function (e) {
	$("#error_message").css('display', 'none');
	e.preventDefault()
});
$('#confirmation_message_container').on('click', '#confirmation_message_close_link', function (e) {
	$("#confirmation_message").css('display', 'none');
	e.preventDefault()
});
$('#confirmation_message_container').on('click', '#alert_message_close_link', function (e) {
	$("#alert_message").css('display', 'none');
	e.preventDefault()
});
});

$('#confirmation_message_container').on('click', '#error_message_close_link', function (e) {
	$("#error_message").css('display', 'none');
	e.preventDefault()
});

$('form[name="installation_form"]').unbind('submit').submit();


function check_privacy_license(){

    if (document.getElementsByName('accept_license')[0].checked === true && document.getElementsByName('accept_privacy')[0].checked === true ){
        return true;
    }
    else{
        alert('You have to accept the license and the privacy policy to continue.');
        return false;
    }
}

show_installation_password = 0;
installation_password = ''

function check_installation_password(new_installation_password = installation_password, new_show_installation_password = show_installation_password){

    installation_password = new_installation_password;
    show_installation_password = new_show_installation_password;

    let number = new RegExp(/[0-9]/);
    let lower = new RegExp(/[a-z]/);
    let upper = new RegExp(/[A-Z]/);
    let special = new RegExp(/[^\w]/);
    
    if(installation_password.length >= 8 && number.test(installation_password) && lower.test(installation_password) && upper.test(installation_password) && special.test(installation_password) ) {
    //if(true) {
        if (show_installation_password === 0){
            document.getElementById('password_strength').innerHTML = ' <b>This password: ***** is <span style="color:green">FINE</span> (<a href="javascript:check_installation_password(undefined, 1)">show password</a>)</b>';
        }
        else{
            document.getElementById('password_strength').innerHTML = ' <b>This password: ' + installation_password +' is <span style="color:green">FINE</span></b>';
        }
        document.getElementById('button_install').disabled = false;
        document.getElementById('install_wait_after_clicking').innerHTML = 'You can install, please WAIT after clicking, installation may take some time';
        document.getElementById('install_wait_after_clicking').innerHTML = '';
    }
    else{
        if (show_installation_password === 0){
            document.getElementById('password_strength').innerHTML = ' <b>This password: ***** is <span style="color:red">WEAK</span> (<a href="javascript:check_installation_password(undefined, 1)">show password</a>)</b>';
        }
        else{
            document.getElementById('password_strength').innerHTML = ' <b>This password: ' + installation_password +' is <span style="color:red">WEAK</span></b>';
        }
        document.getElementById('button_install').disabled = true;
        document.getElementById('install_wait_after_clicking').innerHTML = 'Weak password, fix it before installing&nbsp;&nbsp;';
    }
}
</script>


  </head>
  <body>
  <div id="dialog" title=""></div>

<div id="div_help" style="width: 600px">
<div id="div_help_content">
<div id="div_help_content_title"></div>
<div id="div_help_content_text"></div>
</div>
</div>
<div id="container_logo" class="container mt-2 text-center"><img id="dadabik_logo" style="max-height: 100%; width: auto;" src="images/logo.png"></div>
    <div id="container_title" class="container mt-5"><h1><?= $title ?></h1></div>
    <div id="container_main" class="container mt-5">
  <?php echo $content; ?>
</div>
<div id="container_buttons" class="container mt-5">
 <div class="d-none align-items-center" id="spinner">
  <div class="spinner-border mr-3" role="status" aria-hidden="true"></div>
  
  <strong>Loading...</strong>
  
</div>

</div>

