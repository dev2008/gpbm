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
<form method="post" class="css_form" action="<?php echo $dadabik_login_file; ?>?function=check_login">
    
<input type="hidden" name="login_form_type" value="2fa">
<table>
    

<tr><td style="padding: 5px;"><label for="2fa_code" class="form-label"><?php echo ucfirst($login_messages_ar['verification_code']); ?></label><input type="text" name="2fa_code" class="input_login_form form-control" ></td></tr>

<tr><td colspan="2" align="right" style="padding: 5px;">


<input type="submit" class="btn btn-primary w-100" value="<?php echo ucfirst($normal_messages_ar['continue']); ?> " id="2fa_code_continue_btn"></td></tr>


<?php
?>
</table>

</form>

