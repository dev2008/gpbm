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

if (!isset($function)){
    die('Function not set.');
}

$min_php_version = '7.2.0';
$min_php_version_sqlserver = '7.2.0';
$min_ioncube_version = '10.1';
$min_dbms_version['mysql'] = '5';
$min_dbms_version['sqlite'] = '3';
$min_dbms_version['postgres'] = '8.2'; 
$min_dbms_version['sqlserver'] = '11';

$check = 1;


// same check in common_start
if ($dbms_type !== 'mysql' && $dbms_type !== 'postgres' && $dbms_type !== 'sqlite'  && $dbms_type !== 'sqlserver' ){
	echo '<p><b>[01] Error:</b> $dbms_type must be \'mysql\' or \'postgres\' or \'sqlite\' or \'sqlserver\' please check your config.php'; 
	exit;
}
elseif($host === '' && $dbms_type !== 'sqlite'){
		echo '<p><b>[01] Error:</b> Please specify $host in your config.php';
		exit;
}
elseif($db_name === ''){
		echo '<p><b>[01] Error:</b> Please specify $db_name in your config.php'; 
		exit;
}
elseif($user === '' && $dbms_type !== 'sqlite'){
		echo '<p><b>[01] Error:</b> Please specify $user in your config.php'; 
		exit;
}
else{
	

    // CHECK PHP VERSION
    $phpversion = phpversion();
    echo '<p><strong>Current PHP version:</strong> '.$phpversion.' <strong>';
    
    $additional_errror_text = '';
    if ($dbms_type === 'sqlserver'){
        $min_php_version = $min_php_version_sqlserver;
        $additional_errror_text = ' for MS SQL Server';
    }
    if (version_compare($phpversion, $min_php_version, '>=') === true && substr($phpversion, 0, 3) !== '8.0') {
        echo ' <span style="color:#007700">OK<span>';
    }
    else{
        echo ' <span style="color:#aa0000">NO<span> (min PHP version'.$additional_errror_text.' is: '.$min_php_version.', PHP 8.0 is not supported, PHP 8.1/8.2 is supported)';
        $check = 0;
    }
    echo '</strong>';

    // CHECK MBSTRING
    echo '<p><strong>mbsgtring extension: ';

    if (extension_loaded('mbstring') === true){
        echo ' <span style="color:#007700">Installed<span>';
    }
    else{
    
        echo ' <span style="color:#aa0000">NOT Installed (if you need to handle multibyte characters, you need to install it)<span>';
        $check = 0;
    }
    echo '</strong>';

    // CHECK IONCUBE
    echo '<p><strong>ioncube extension: ';

    if (extension_loaded('IonCube Loader') === true){
         echo ' <span style="color:#007700">Installed<span></strong>';
     
         $ioncube_version =  ioncube_loader_version();
     
         echo '<p><strong>ioncube extension version: </strong> '.$ioncube_version;
     
         $temp_ar = explode('.', $ioncube_version);
     
         if(count($temp_ar) === 1){
            $ioncube_version_normalized .= '.0';
         }
         else{
            $ioncube_version_normalized = implode('.', array_slice($temp_ar, 0, 2));
         }
     
         echo '<strong>';
         if ( $ioncube_version_normalized >= $min_ioncube_version) {
            echo ' <span style="color:#007700">OK<span>';
        }
        else{
            echo ' <span style="color:#aa0000">NO<span> (min ioncube version is: '.$min_ioncube_version.')';
            
            $check = 0;
        }
        echo '</strong>';
     
    }
    else{
    
        echo ' <span style="color:#aa0000">NOT Installed<span></strong>&nbsp;&nbsp;You can download it from here <a href="https://www.ioncube.com/loaders.php" target="_blank">ioncube.com/loaders.php</a>';
        $check = 0;
    }
    
    // CHECK CONNECTION    
    echo '<p><strong>Check DB connection:  ';

    $exit_on_error = 0;
    
    $conn = connect_db($host, $user, $pass, $db_name, $exit_on_error);
    
	if ($conn === NULL){
	    echo ' <span style="color:#aa0000">There is a connection problem, check $host, $user, $pass, $db_schema, $db_name in config.php<span>';
        $check = 0;
	}
	else{
	    echo ' <span style="color:#007700">OK<span>';
	}
	
	if ($conn !== NULL){
        // CHECK DBMS VERSION
        $dbms_version = $conn->getAttribute(constant('PDO::ATTR_SERVER_VERSION'));
    
        $dbms_version_original = $dbms_version;

        if ($dbms_type === 'sqlserver'){
            $temp_ar = explode('.', $dbms_version);
            $dbms_version = $temp_ar[0];
        }
        
        preg_match('/^\d+(?:\.\d+){1,2}/', $dbms_version, $m); // remove strings, if any, e.g. 8.0.36-ubuntu
        $dbms_version = $m[0] ?? null;

        echo '<p><strong>'.$dbms_type.' version:</strong> '.$dbms_version.' <strong>';

        if ( version_compare($dbms_version, $min_dbms_version[$dbms_type], '>=') || $dbms_type === 'mysql' && strpos($dbms_version_original, 'MariaDB') !== false) { // mariadb is always ok
            echo ' <span style="color:#007700">OK<span>';
        }
        else{
            echo ' <span style="color:#aa0000">NO<span> (min '.$dbms_type.' version is: '.$min_dbms_version[$dbms_type].'. In some conditions the DBMS version detected and parsed might be not correct, if you think your DBMS version respects the minimum requirements, you can ignore this alert).';
            $check = 0;
        }
        echo '</strong>';

    
        echo '<p>For addtional recommended settings (DB engine, encoding, ...) check the <a href="https://dadabik.com/index.php?function=show_documentation#requirements" target="blank">requirements</a> chapter of the documentation.</p>';
    }
    
    echo '<p>If you are having troubles installing DaDaBIK, <a href="https://dadabik.com/index.php?function=show_contacts&install_troubles=1" target="_blank">CONTACT US</a>. During office hours we typically reply within one hour.';
	
	    
}
