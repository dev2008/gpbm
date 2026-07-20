<?php
/*
Plugin Name: DaDaBIK_wrapper
Version: 1.6
Author: Eugenio Tacchini
Author URI: https://dadabik.com
License: While this plugin is distributed under the GPL2 license, the software DaDaBIK is not, for information about the DaDaBIK license, see DaDaBIK's documentation https://dadabik.com/index.php?function=show_documentation. 
*/
?>
<?php
// /*
// ***********************************************************************************
// DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
// Copyright (C) 2001-2026 Eugenio Tacchini
// 
// This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.
// 
// This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.
// 
// If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
// ***********************************************************************************
// */

?>
<?php
add_shortcode('dadabik_wrapper', 'dadabik_wrapper_func');
add_action('plugins_loaded','set_user_session');
add_action('wp_enqueue_scripts', 'dadabik_enqueue_script');

// secret_key and session_name: these parameters must be the same as the ones you set in the DaDaBIK application you want to embed in this Wordpress installation (see DaDaBIK's config.php for further details)
$secret_key = '';
$dadabik_session_name = '';

// site_path
// site_path can be empty in your DaDaBIK config_custom file but it cannot be empty here, it's the path where the DaDaBIK application you want to embed in Wordpress is installed, e.g if DaDaBIK is reachable at http://www.mysite.com/john/dadabik/ the site path must be '/john/dadabik/', remember to put slashes at the beginning and at the end; put just one slash '/' if DaDaBIK is installed in the root of a Website, (e.g. if DaDaBIK is installed at http://www.mysite.com/)
$site_path = '';

if ($secret_key === '' or is_null($secret_key) or $dadabik_session_name === '' or is_null($dadabik_session_name) or $site_path === '' or is_null($site_path)) die('secret key or session name or site path not set');

function set_user_session(){
	global $secret_key, $dadabik_session_name, $site_path;
	$temp = wp_get_current_user();
	
	
	session_name($dadabik_session_name);
	ini_set('session.cookie_path', $site_path);

    if (PHP_VERSION_ID < 70300) {
        session_set_cookie_params(0, $site_path.'; samesite=Lax');
    } else {
        session_set_cookie_params(array(
        'lifetime' => 0,
        'path' => $site_path,
        'samesite' => 'Lax',
        'httponly' => true
        ));
    }
	
	session_start();
	
	if (isset($temp->user_login)){
	
		$_SESSION['wordpress_user'] = $temp->user_login;
		$_SESSION['wordpress_secret'] = md5($_SESSION['wordpress_user'].$secret_key);
	}
	else{
		unset($_SESSION['wordpress_user']); 
		unset($_SESSION['wordpress_secret']);
	}
	
	session_write_close();
	
}
function dadabik_wrapper_func($atts){
	return '<iframe frameborder="0" src="'.$atts['url'].'" width="100%" height="800"  id="dadabik_iframe" style="border:0;overflow-y:auto;"></iframe>';
}
function dadabik_enqueue_script(){
    wp_register_script('dadabik_wrapper_js', plugins_url('dadabik_wrapper_js.js', __FILE__)); 
	wp_enqueue_script('jquery'); 
	wp_enqueue_script('dadabik_wrapper_js'); 
}
?>