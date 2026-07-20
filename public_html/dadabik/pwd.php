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
$results_grid_mode = "standard_row_high";
$form_mode = "standard_edit_form";	

include ("./include/config.php");

if (file_exists('./include/config_prepackaged_app.php')){
	require ('./include/config_prepackaged_app.php');
}
require ("./include/htmlawed/htmLawed.php");


include ("./include/functions.php");
include ("./include/common_start.php");

if (isset($_SESSION['language']) && in_array($_SESSION['language'], $languages_ar)){
	$language = $_SESSION['language'];
}
else{
	$language = $languages_ar[0];
}

include ("./include/languages/".$language.".php");
include ("./include/check_login.php");
// include ("./include/check_table.php"); // not required here
require ('./include/PasswordHash.php');

if (file_exists('./include/languages/'.$language.'_custom.php')){

    if ($_SESSION['dev_mode'] === 'beta'){
	    require ('./include/languages/'.$language.'_custom_beta.php');
	}
	else{
	    require ('./include/languages/'.$language.'_custom.php');
	}
}

if (file_exists('./include/languages/'.$language.'_custom_prepackaged_app.php')){
	require ('./include/languages/'.$language.'_custom_prepackaged_app.php');
}
?>
<html>
	<head>
	<title></title>
		
	<meta name="viewport" content="initial-scale=1.0"/>
	<link rel="stylesheet" href="./css/styles_screen_12.6.css" type ="text/css" media="screen">
	
	<link rel="stylesheet" href="css/fontawesome.css">
	<link rel="stylesheet" href="css/bootstrap_5.3.2.css">
	
	<style>
	/* because styles_screen.css set to 0 only over 768px */
	html, body { margin: 0; padding: 0; overflow: hidden; }
	</style>
	<script>
	function register_pwd(pwd){
		opener.document.forms['contacts_form'].elements['<?php echo $users_table_password_field; ?>'].value = document.forms['encrypter'].elements['encrypted'].value;
		self.close();
	}
	</script>
	<body>
	<table  class="main_table" >
	<tr>
	<td class="main_table_td">
	<table  class="table_interface_container" align="center"  >
	
	<tr>
    <td class="table_interface_container_td_content">
    <table class="table_interface_container_table_content">
<?php
echo "<tr><td class=\"td_content\" valign=\"top\"><b>Password encrypter</b></td></tr><tr><td  class=\"td_content\" valign=\"top\">";
echo $login_messages_ar['pwd_explain_text'];
echo "<form action=\"pwd.php\" name=\"pwd_gen\" method=\"POST\">";
echo "<input type=\"password\" name=\"pwd\" value=\"";
if (isset($_POST['pwd'])) {
	echo unescape($_POST['pwd']);
} // end if
echo "\" size=\"40\"><br>";
echo "<input type=\"submit\"  class=\"button_form\" name=\"\" value=\"".$login_messages_ar['pwd_encrypt_button_text']."\">";
echo "</form>";
if(isset($_POST['pwd'])){
	
	if (strlen(unescape($_POST['pwd'])) > 72){
		echo 'Error';
		exit();
	} 
	
	$clear = unescape($_POST['pwd']);

	if($enable_password_validation === 1 && validate_password($clear) === false){
		echo '<p>'.$normal_messages_ar['password_not_valid'];
	}
	else{
	
        $encrypted = create_password_hash($clear);
    
        echo '<br/>'.$login_messages_ar['pwd_explain_text_2'];
        echo "<form name=\"encrypter\">";
        echo "<input type=\"password\" name=\"encrypted\" value=\"$encrypted\" size=\"40\"><br>";
        echo "<input type=\"button\"  class=\"button_form\" name=\"encrypt-it\" value=\"".$login_messages_ar['pwd_register_button_text']."\" onclick=register_pwd('".$encrypted."')>";
        echo "</form>";
    }
	
}
echo "</td></tr>";
?>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	</body>
	</head>
</html>