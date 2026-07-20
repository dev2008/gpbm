// /*
// ***********************************************************************************
// DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
// Copyright (C) 2001-2020  Eugenio Tacchini
// 
// This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.
// 
// This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.
// 
// If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
// ***********************************************************************************
// */
// While this Wordpress plugin (files dadabik_wrapper_js.js and dadabik_wrapper.php ) is distributed under the GPL2 license, the software DaDaBIK is not, for information about the DaDaBIK license, see DaDaBIK's documentation https://dadabik.com/index.php?function=show_documentation. 

jQuery(document).ready(function() {
    jQuery('iframe').load(function() {
	document.getElementById('dadabik_iframe').style.height = 'auto';
	jQuery("#dadabik_iframe").height(jQuery("#dadabik_iframe").contents().find("html").height()+35);

    });
});