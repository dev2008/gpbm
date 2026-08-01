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
require './include/config.php'; 
require './include/db_functions_pdo.php'; 
//require './include/header_install.php';

$function = 'install';
$page_name = 'check_requirements';
$check = 1;

function is_curl_installed()
{
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}

function file_get_contents_3($url, $timeout=3)
{
    if ((int)ini_get('allow_url_fopen') == 1){
        
        // @ because otherwise if there is an error, the URL called is displayed 
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> true,
                "verify_peer_name"=> true
            ),
        );
        return @file_get_contents($url, false, stream_context_create($arrContextOptions));
        
        
    }
    elseif (is_curl_installed() === true){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);

    
        curl_close($ch);
        

        return $result;
    }
    else{
        return false;
        //$content .= 'Error: you need the cURL PHP extension installed or, alternatively, enable allow_url_fopen.';
    }
}

$step = 1;
if (isset($_GET['step'])){
    $step = (int)$_GET['step'];
}

$inst_params['site_url'] = "http".(isset($_SERVER['HTTPS']) ? 's' : ''). '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';

$temp = file_get_contents('version_first_installation') or die('<p>[10] Error: the file <b>version_first_installation</b> is not available in the DaDaBIK folder.</p>');
$temp = explode(' ', $temp);
$inst_params['dadabik_version_edition'] = $temp[0].' '.$temp[1];

$inst_params['ioncube_version']  = 'none';
if (extension_loaded('IonCube Loader') === true){
     $inst_params['ioncube_version'] =  ioncube_loader_version();
}

//echo  '<div class="container_install_text">';



$content = '';

switch($step){
    case 1:
        //$content .= '<h2>This procedue will guide you through the installation of DaDaBIK</h2>';
        
        $title = 'Installation';
        
        $content .= '<h2>If you continue, you will share with us the following information</h2><p><b>Your DaDaBIK serial number: </b>'.$serial_number.'<br/><br/><b>URL, date & time of the Installation: </b>'.$inst_params['site_url'].' '.date("Y-m-d H:i:s").'<br/><br/><b>OS, DBMS and PHP Version: </b>'.(php_uname('s')).', '.$dbms_type.', PHP '.(phpversion()).'<br/><br/><b>Installation type</b><br/><br><b>DB connection result: </b> <i>success</i> or <i>fail</i>';
        $content .= '<p><form action="install.php?step=2" method="POST">';
        $content .= '<p>Please always check the latest online version of the license and privacy policy, which may have changed.<br><br>*I accept the <a href="https://dadabik.com/index.php?function=show_license" target="_blank">license</a> <input type="checkbox" name="accept_license" value="1"  required>';
        $content .= '<br>*I specifically accept articles 1, 4.2, 5 and 6 of the <a href="https://dadabik.com/index.php?function=show_license" target="_blank">license</a> <input type="checkbox" name="accept_license_2" value="1"  required>'; 
        $content .= '<br>*I accept the <a href="https://www.iubenda.com/privacy-policy/875935" target="_blank">privacy policy</a> <input type="checkbox" name="accept_privacy" value="1" required>';
        

        $content .= '<p><input type="submit" class="btn btn-primary" role="button" value="CONTINUE">';
        //$content .= '<p><input type="submit" value="NEXT >>" style="font-size:20px;"> (please <span style="color:red;font-weight:bold">WAIT</span> after clicking)</form>';

        break;
    
    case 2:
        if (!isset($_POST['accept_license']) || !isset($_POST['accept_license_2']) || !isset($_POST['accept_privacy'])  || $_POST['accept_license'] !== '1' || $_POST['accept_license_2'] !== '1' || $_POST['accept_privacy'] !== '1' ){
            die('You have to accept privacy and license. <a href="install.php">Restart</a> the installation.');
        }
        $title = 'Requirements check';



        ob_start();
        require './include/requirements_check.php';
        $content .= ob_get_contents();
        ob_end_clean();
        
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> true,
                "verify_peer_name"=> true
            ),
        );
        
        $connection_check = 0;
        if ($conn !== NULL){
            $connection_check = 1;
        }
        file_get_contents_3('https://dadabik.com/ia.php?s='.urlencode($inst_params['site_url']).'&dbms='.$dbms_type.'&v='.urlencode($inst_params['dadabik_version_edition']).'&os='.urlencode(php_uname('s')).'&php='.urlencode(phpversion()).'&date_time='.urlencode(date("Y-m-d H:i:s")).'&s2='.urlencode($serial_number).'&i='.urlencode($inst_params['ioncube_version']).'&c='.$connection_check);
        
        if ($check === 0){
            $content .= '<p style="color:#aa0000">Please fix the requirement issues and <a href="install.php">restart</a> the installation.<br><br>If, for some reason, you want to force the installation anyway (it is not recommended), run install2.php.</p>';
        }
        else{
            $content .= '<p><form action="install2.php" method="POST"><input type="submit" class="btn btn-primary" value="NEXT" style="font-size:20px;"></form>';
        }
        break;
}
$content .= '</div>';


require './include/header_install.php';


?>



<?php
require './include/footer_install.php';
