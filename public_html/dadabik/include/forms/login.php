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


<br>
<!--[if !lte IE 9]><!-->
<form method="post" class="css_form" action="<?php echo $dadabik_login_file; ?>?function=check_login">
<!--<![endif]-->

<!--[if lte IE 9]>
<form method="post" action="<?php echo $dadabik_login_file; ?>?function=check_login">
<![endif]-->

<input type="hidden" name="login_form_type" value="standard">
<table>
<?php
if (isset($hooks['login_form']['before'])){
    if ( substr($hooks['login_form']['before'],0,8) === 'dadabik_'){
        echo '<tr><td style="padding: 5px;">';
        call_user_func($hooks['login_form']['before']);
        echo '</td></tr>';
    }
}
?>

<tr><td style="padding: 5px;"><label for="username_user" class="form-label"><?php echo ucfirst($login_messages_ar['username']); ?></label><input type="text" name="username_user" class="input_login_form form-control" ></td></tr>
<tr><td style="padding: 5px;"><label for="password_user" class="form-label"><?php echo ucfirst($login_messages_ar['password']); ?></label><input type="password" name="password_user" class="input_login_form form-control" ></td></tr>
<tr><td colspan="2" align="right" style="padding: 5px;">
<?php

//$enable_ldap_authentication = 0;
$checked_temp = '';
if ($set_ldap_authentication_as_default === 1){
    $checked_temp = ' checked';
}
if ($enable_ldap_authentication === 1) echo '<input type="checkbox" name="ldap_authentication" value="1"'.$checked_temp.'>LDAP ';


?>

<input type="submit" class="btn btn-primary w-100" value="<?php echo ucfirst($login_messages_ar['login']); ?> "  id="form_login_btn"></td></tr>

<?php
echo '<tr><td colspan="2" align="right"><br>';

if ($enable_users_self_registration === 1){
    echo '<a href="login.php?function=register_account">'.$login_messages_ar['register'].'</a>&nbsp;&nbsp; ';
}
if ($enable_forgotten_password === 1){
    echo '<a href="login.php?function=forgotten_password">'.$login_messages_ar['forgotten_passowrd'].'</a>';
}

echo '</td></tr>';

?>


<?php
?>
<?php if ($enable_google_authentication === 1){  ?>

<tr><td colspan="2" align="center"><br><a href="login.php?function=start_google_login" style="text-decoration: none;">
  <div style="display: inline-flex; align-items: center; background: white; border: 1px solid #ccc; padding: 10px 20px; border-radius: 4px; font-family: Roboto, sans-serif;">
    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" style="width:20px; height:20px; margin-right:10px;">
    <span style="color: #555;">Sign in with Google</span>
  </div>
</a>
</td></tr>


<?php }  ?>

</table>

</form>

