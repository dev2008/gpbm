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

<h1>About/upgrade</h1>
<p>DaDaBIK&trade; is a product conceived and developed by Eugenio Tacchini<br>Copyright © 2001-2026 Eugenio Tacchini<br>Proudly ❤️ made in <a href="https://en.wikipedia.org/wiki/Emilia-Romagna" target="_blank">Emilia</a><br><a href="https://dadabik.com/" target="_blank">dadabik.com</a></p>

<?php if ($orazio_edition === 1){

echo '<p>'.$orazio_name.' is based on DaDaBIK + OpenAI API</p>';

} ?>

<?php if ($orazio_edition === 0){ ?>
<h2>Your current DaDaBIK version</h2>
<p>You are using DaDaBIK version <?php echo $current_release_version.' '.$current_release_version_2 ?>, installed on <?php echo format_date($current_release_date); ?> (installation code: <?php echo $code_installation; ?>), the latest version of DaDaBIK is

<?php 
if (isset($last_release_version) && isset($last_release_date)){

    echo $last_release_version.' released on '.format_date($last_release_date);
    
    if ($current_release_version != $last_release_version){

        echo '<p><b>You are not running</b> the last release of DaDaBIK, the release you are running might have bugs and security holes, see the official <a href="https://dadabik.com/index.php?function=show_changelog">change log</a> for further information. You can upgrade DaDaBIK <a href="https://dadabik.com/download/">here</a>.';

    }
    else{
        echo '<p>You are running the latest release of DaDaBIK</p>';
    }

}
else{
    echo '<br><br><font color="red">There are problems with the Internet connection, we cannot check if there are upgrades available.</font>';
}
?>
<p>In case you want to upgrade to a more powerful edition (from Pro to Enterprise/Platinum, from Enterprise to Platinum) please <a href="https://dadabik.com/index.php?function=show_contacts" target="_blank">contact us</a>.</p>

<h2>System info</h2>
<p><b>PHP Version:</b> <?php echo phpversion(); ?></p>

<p><b><?php echo $dbms_type; ?> version:</b> <?php echo $conn->getAttribute(constant('PDO::ATTR_SERVER_VERSION')); ?></p>

<p><b>Web server:</b> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>

<p><b>Client:</b> <?php echo $_SERVER['HTTP_USER_AGENT']; ?></p>

<p><b>URL installation:</b> <?php echo $url_installation; ?></p>

<?php } ?>