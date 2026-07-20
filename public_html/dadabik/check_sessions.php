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
include ("./include/config.php");
require ("./include/htmlawed/htmLawed.php");
//include ("./include/languages/".$language.".php");
include ("./include/functions.php");
include ("./include/common_start.php");
//include ("./include/check_installation.php");
//include ("./include/check_login.php");
include ("./include/check_table.php");


if (!isset($_SESSION['test'])){ 
$_SESSION['test'] = 1; 
} 
else{ 
$_SESSION['test']++; 
} 
echo $_SESSION['test']; 

?>