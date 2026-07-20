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


// this is the configuration file
// to install DaDaBIK you have to specify AT LEAST $serial_number, $dbms_type, $host, $db_name, $user, $pass, $timezone, $secret_key, $dadabik_session_name

/* IT'S A GOOD PRACTICE TO LEAVE THIS CONFIG.PHP FILE "CLEAN" AND WORK ON config_custom.php (open inclue/config_custom.php for additional details), THIS WILL MAKE YOUR DADABIK UPGRADES EASIER */

///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// required installation parameters: please specify at least the following parameters

// your license serial number, you can find it in the email you received from mailer@fastspring.com, containing the download link
// and having as a subject something like "Your DaDaBIK 10.2 Manarola ENTERPRISE File Download"
// the serial number is 8 characters long
$cfg_default['serial_number'] = '';

// dbms type ('mysql' or 'postgres' or 'sqlite' or 'sqlserver'); sqlserver is for Microsoft SQL Server; if you are using sqlserver be sure to have the TCP/IP protocol enabled, if you are using SQL Server on Azure please also check the parameter $tables_to_exclude.
$cfg_default['dbms_type'] = 'mysql';

// DBMS server host
$cfg_default['host'] = ''; // the name or the IP of the host (computer) where the DBMS is running (e.g. '127.0.0.1' if the DBMS is running locally); please try 'localhost' instead of '127.0.0.1' if '127.0.0.1' is not working; some Web Hosting providers can require a full name e.g. mysql.yourdomain.com; for SQLite this parameter is not needed. For mysql and postgres you can also specify the port (if it's not the default one) separated from the host by a ":", e.g. "127.0.0.1:5431" to use the port 5431

// database name
$cfg_default['db_name'] = ''; // for SQLite not only the name but 1) the full path is also needed (e.g. '/my_databases/music_databases/songs.db') and 2) you need to grant to the Web server write permissions on the database file

// database user
$cfg_default['user'] = 'root'; // this user must have select, insert, update, delete permissions, create and drop permissions are also needed for installation, upgrade and administration area e.g. 'root'; for SQLite this parameter is not needed
// for better security, you should choose a user who has privileges ONLY on the database used by this application ($db_name parameter)

// database password
$cfg_default['pass'] = ''; // for sqlite this parameter is not needed

// database schema (only needed for postgres, not needed for mysql and sqlite, if you don't know the schema, leave "public")
$cfg_default['db_schema'] = 'public';

// timezone, specify here your timezone (a list of available timezone here: http://php.net/manual/en/timezones.php)
$cfg_default['timezone'] = 'Europe/Rome';


// secret_key: a long (60+ characters), random and complicated phrase which is used to sign authentication cookies for this application and therefore improve authentication security; you don't need to remember this phrase
// please note that in order to benefit from this security mechanism you must choose a different secret_key for each DaDaBIK application you create and you must keep this value secret
$cfg_default['secret_key'] = '';



// dadabik_session_name: the name of the session for this DaDaBIK application, used to avoid unexpected and risky effects if other applications installed in the same domain make use of the same session variable names that this application uses
// it must contain only ASCII letters and numbers, with at least one letter and no blank spaces; its length must be less than 100 characters and must be different than $secret_key; you don't need to remember this name
// please note that in order to benefit from this security mechanism you must choose a name which is unique among session names used by other applications installed in the same domain
$cfg_default['dadabik_session_name'] = '';



// DaDaBIK complete url (e.g. http://www.mysite.com/john/dadabik/, REMEMBER the trailing slash)
// YOU CAN SAFELY LEAVE THIS PARAMETER EMPTY (''), you should try to set it only in two cases:
// 1) IF DADABIK REDIRECTS YOU TO THE WRONG URL AFTER LOGIN
// 2) IF YOU ARE HAVING ISSUES DISPLAYING IMAGES IN PDF
$cfg_default['site_url'] = '';


// DaDaBIK site path is the path your session cookies are valid for
// YOU CAN LEAVE THIS OPTION EMPTY (''), NORMALLY IT IS CORRECTLY GUESSED BY DADABIK
// If you want extra security, you can set it manually: if you execute DaDaBIK from e.g. http://www.mysite.com/john/dadabik/ the site path must be /john/dadabik/, remember to put slashes at the beginning and at the end; put just one slash '/' if DaDaBIK is installed in the root of a Website, (e.g. http://www.mysite.com/)

$cfg_default['site_path'] = '';


// chosose your graphic theme: 'classic', 'black', 'blue' or 'green'
$cfg_default['graphic_theme'] = 'classic';

// chosose your results grid layout scrolling: 'site_overflow', 'grid_overflow' or 'grid_scroll'
// this affect the layout when your results grid is wider than your screen:
// grid_scroll (default): an horizontal scrollbar appears just for the grid, the grid is independent
// site_overflow (old 7.x DaDaBIK style): an horizontal scrollbar appears for the whole page, all the site layout is affected
// grid_overflow: an horizontal scrollbar appears for the whole page, the site layout is not affected, the grid is independent
// Internet Explorer always works in grid_scroll mode, regardless of the settings.
// Small devices (display width < 768px, typycally smartphones) always work in grid_scroll mode as well
$cfg_default['grid_layout_scrolling'] = 'grid_scroll';

// choose the type of menu you want to use for page selection: 'left_side_menu' or 'drop_down_menu'
$cfg_default['menu_type'] = 'left_side_menu';

// don't show the menu if there is only one menu item (0|1)
$cfg_default['dont_show_menu_if_only_one_item'] = 0;

// display mode for the results grid ('both','classic_grid','list'), the default is 'both', which allows the user to choose through a drop-down menu, 'classic_grid' and 'list' don't show the drop-down menu.
$cfg_default['results_display_mode_menu'] = 'both';

// define the logo (by default it's the DaDaBIK logo)
$cfg_default['logo_img'] = 'images/logo.png';

// define the title of the application (<title> html tag)
$cfg_default['title_application'] = 'DaDaBIK database front-end - dadabik.com';


// additional installation parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// DaDaBIK needs a unique field for each table and view you want to use (tipycally this is the primary key of the table) . Since it doesnt' support composite primary keys (primary keys composed by multiple fields), you can, optionally, ask DaDaBIK to automatically add an additional, unique, auto-increment field, when, during installation, it finds a table having a composite primary key. This only works for MySQL.
$cfg_default['add_additional_dadabik_id_field'] = 0;

// the name of field to add (see the parameter above); please note that in case, for some reason, you already have a field with this name in the table, DaDaBIK won't add the field and the field will be used as unique field, regardles of its characteristics
$cfg_default['name_additional_dadabik_id_field'] = 'dadabik_id';



// prefix to use for the name of the tables created by DaDaBIK, all the tables created by DaDaBIK use this prefix
$cfg_default['prefix_internal_table'] = 'dadabik_'; // you can safety leave this option as is, you *must* leave this option as is after the installation


/*
DaDaBIK, during the installation, install in the application all the tables available in the database; add here the tables (if any) you want to exclude, one per line, for example if you want to exclude the tables "customers" and "countries" write:
$cfg_default['tables_to_exclude[0]='customers';
$cfg_default['tables_to_exclude[1]='countries';
don't touch this parameter if you want to include all the tables. You can always uninstall tables later using teh admin interface.
If you are using Microsoft SQL Server on Azure, consider that you cauld have in your database tables that Azure use for internal purposes that you probably don't want in your application. An example is the table "database_firewall_rules"
*/

$cfg_default['tables_to_exclude'][0]='';

/*
DaDaBIK, during the installation, install in the application all the tables available in the database; add here the tables (if any) you want to exclude, one prefix per line, for example if you want to exclude all the tables that start with "wp_" and all the tables that start with "test_" write:
$cfg_default['prefixes_to_exclude[0]='wp_';
$cfg_default['prefixes_to_exclude[1]='test_';

don't touch this parameter if you want to include all the tables. You can always uninstall tables later using teh admin interface.

This parameter, in particular, is often used to easily install and use two or more different DaDaBIK applications using a shared database: for each apppplication you need a different $prefix_internal_table and you need to set, for $prefixes_to_exclude, all the prefixes used by the other applications.

*/
 
$cfg_default['prefixes_to_exclude'][0]='';

// maintenance parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// when $maintenance_mode is 1, non-administrator users can't access the application, $debug_mode and $display_sql will be automatically enabled and the page execution time will be printed in the page's footer
// please note that the login page is still available and, for HTTP API, the tokens generation is still available
$cfg_default['maintenance_mode'] = 0;

// the message users will see when the application is maintenance mode
$cfg_default['maintenance_message'] = 'This application is currently under maintenance.';

// if $maintenance_page is not empty, instead of showing $maintenance_message, DaDaBIK will redirected users to a maintenance HTML or PHP page of your choice. The page must be in the DaDaBIK folder, set here the page filename (e.g. maintenance.html)
$cfg_default['maintenance_page'] = '';

// language parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// the list of languages the user can choose from; you can remove one or more languages or change the position. If you leave just one language, the change language listbox will not be displayed.
// the list of languages available is: 'english', 'italian', 'german', 'dutch', 'spanish', 'french', 'portuguese', 'croatian', 'polish', 'catalan', 'estonian', 'rumanian', 'hungarian','norwegian', 'slovak', 'swedish', 'russian', 'danish', 'finnish', 'czech','telugu', 'chinese'

$cfg_default['languages_ar'] = array ('english','italian','catalan','chinese','croatian','czech','danish','dutch','estonian','finnish','french','german','hungarian','norwegian','polish','portuguese','rumanian','russian','slovak','spanish','swedish','telugu');

// set to 1 if you want to specify, in form configurator, different labels, hints and tooltips for different languages, the correct label will be used according to the language the user chooses
$cfg_default['enable_multilanguage_labels'] = 0;

// if $enable_multilanguage_labels is set to 1, which language should DaDaBIK use in case of a missing translation? 
$cfg_default['default_language_missing_translation'] = 'english';

// authentication and security parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the users authentication (0|1). If you set 1, you have to login to use DaDaBIK
// by default DaDaBIK create an administrator user (username: root password: letizia) and a normal user (username: alfonso password: letizia); for security reasons please change both the passwords as soon as possible and never publish a DaDaBIK application having the default passwords
// if you disable the authentication and the "export to CSV" feature is enabled (see $export_to_csv_feature later), please consider that search engine robots, accessing the CSV export link, could consume an inordinate amount of processor time.
$cfg_default['enable_authentication'] = 1;

// fill this parameter if you set $enable_authentication'] = 1 but you still want to leave part of your application available to non-authenticated users. From the admin interface, create a group (for example "public") and a user belonging to that group (for example "public_user") and fill $username_public_user with the username of such user. A non-authenticated user will get the permissions that you granted to the group you have created.
// if you leave $username_public_user empty (default setting) and $enable_authentication'] = 1, users MUST login to access your DaDaBIK application
$cfg_default['username_public_user'] = '';

// enable user password modification (0|1). If you set 1, users can change their own password.
$cfg_default['enable_user_password_modification'] = 1;

// number of times a user can type the wrong password before the account is blocked
// the failed login counter, before reaching maximum allowed number, is reset to 0 when the user correctly access the application using the right password (even from another IP/computer)
// If a user access a DaDaBIK application through a Wordpress authentication, this limit has no effect
// An admin user can re-enable a blocked user from the "edit" page of the user
$cfg_default['number_failed_login_before_blocking'] = 10;

// enable the Wordpress authentication (0|1). If DaDaBIK is integrated into a Wordpress website and $wordpress_authentication is 1, users authenticated through Wordpress are automatically authenticated into DaDaBIK too, without doing the log-in again. The Wordpress authentication feature requires that the DaDABIK application is installed in a subdirectory of the Wordpress site and that the same users (same username) is available both in Wordpress and DaDaBIK.
// please note that if $wordpress_authentication'] = 1, two users having the same username (one on Wordpress and one on DaDaBIK) can coexist and both access DaDaBIK as they were the same user, even if they have two different passwords
// please note that the Wordpress and DaDaBIK session expirations are not synchronized: if your session expires in Wordpress it doesn't mean it automatically expires in DaDaBIK. Use the logout button in Wordpress to be sure to disconnect to both Wordpress and DaDaBIK.
// If you enable the Wordpress authentication, you should login/logout through Wordpress without using DaDaBIK login/lougout; mixing Wordpress and DaDaBIK login/logout can lead to unexpected behaviours.
$cfg_default['wordpress_authentication'] = 0;

// enable granular permissions (0|1). Granular permissions allow you to set, for each users group, which operations (read, delete, update, create, details, search and quick search) are allowed on each form and field.
$cfg_default['enable_granular_permissions'] = 1;

// enable password validation (0|1). Set it to 1 if you want to validate passwords according to rules, for example you want a password to contain at least one special character. If 1, when a new user is created or a user's password is changed through DaDaBIK, the password is validated using a validate_password($password) function that you have to write in custom_functions.php; the function must return true or false. You also have to specify the error message in your language file (see /include/languages): add a sentence to the $error_messages_ar array having key'] = password_not_valid 
// please note that if a user produces the passowrd hash from outside DaDaBIK and enter it direclty in the user form, the validation cannot be performed
$cfg_default['enable_password_validation'] = 0;

// always refresh permissions (0|1). DaDaBIK by default recompute the user permissions at each page load; we recommend to keep this default setting (1); if you set this parameter to 0, DaDaBIK computes the user permissions only when the user logs-in, this means that even if you (admin) edit permissions, the user will keep old permissions setting during all his/her session. If you have a lot of tables and/or fields and/or user groups, setting this value to 0 can speed up the page load. If $enable_granular_permissions is 0, $always_refresh_permissions has no effect.
$cfg_default['always_refresh_permissions'] = 1;

// leave FALSE for better security in passowrd storage, change to TRUE for maximum application portability on old systems (see http://www.openwall.com/phpass/ for further details)
$cfg_default['generate_portable_password_hash'] = FALSE;

// grant permissions after tables installation (0|1|2). By default, when you install a new table in DaDaBIK, all the related permissions are set to NO, leave 0 here if you want to keep this behaviour, 1 if you want to automatically set all the permissions to YES, 2 if you want to autmatically set all the permissions to YES only for the administrator group. If you are not using granular permissions, options 1 and 2 produce the same effect.
$cfg_default['grant_permissions_after_table_installation'] = 0;

// grant permissions to autoincrement fields after table installations. By default, DaDaBIK treats autoincremnt fields as normal fields, set to 0 if you don't want to grant permissions to autoincremnt fields. This is often useful because normally you don't want to see "ID" fields in your forms, especially in the insert forms (you want the value to be automatically generated and assigned). Of course you can alwasy remove the permissions (and hide a field from a form) later.
// this parameter is currently considered only with MySQL, with other DBMSs the autoincremnt fields are always treated as normal fields.
$cfg_default['grant_permissions_autoincrement_after_table_installation'] = 1;



// Information about the user and group tables
// You should leave the following 10 parameters as they are, unless you don't want to use your own custom users and groups tables
// If you change $users_table_name and/or $groups_table_name, DaDaBIK assumes the users and/or groups tables you specified already exists
// and it won't create them
// If you use your own users table, consider that the password field $users_table_password_field must contain the hash of the password
// computed using phpass (see http://www.openwall.com/phpass/ for further details) and at the moment the field needs to have a length of at least  60 characters, but you can use your own hashing function customizing the functions check_password_hash() and create_password_hash() in general_functions.php. If you change the create_password_hash() funciton, this also affects the hashes of the tokens (used by HTTP APIs); please note that at the moment those hashes can be maximum 100 characters log
// also consider that DaDaBIK WON'T WORK properly if you use both LDAP authentication and your own custom users/groups table (not the default one), if you decide to use your own custom users/groups table, $enable_ldap_authentication must be 0  

// the groups table is used just if $enable_granular_permissions'] = 1
$cfg_default['users_table_name'] = $prefix_internal_table.'users';
$cfg_default['users_table_id_field'] = 'id_user'; // must be an integer field and PK
$cfg_default['users_table_id_group_field'] = 'id_group'; // must be an integer field
$cfg_default['users_table_username_field'] = 'username_user'; // must be a char/varchar field
$cfg_default['users_table_password_field'] = 'password_user'; // must be a char/varchar field
$cfg_default['users_table_authentication_type_field'] = 'authentication_type_user'; // must be a char/varchar field, length 255, and must always contain the string "dadabik" as value

$cfg_default['groups_table_name'] = $prefix_internal_table.'groups';
$cfg_default['groups_table_name_field'] = 'name_group'; // must be an char/varchar field
$cfg_default['groups_table_id_field'] = 'id_group'; // must be an integer field and PK
$cfg_default['id_admin_group'] = 1; // the id of the group which is the administrators group

// Enable the use of custom PHP pages in a DaDaBIK application (0|1)
$cfg_default['enable_custom_php_pages'] = 0;

// Enable the use of custom button functions in a DaDaBIK application (0|1)
$cfg_default['enable_custom_button_functions'] = 0;




// Use the id_group of the user, instead of the username, to fill ID_user fields (0|1)
// this also affects how the owner permissions work (id_group instead of username will be used to control read, delete, update)
// please note that if you change this parameter for a running application that uses one or more ID_user field types, you will end up in an inconsistent situation where some records have the username of the user as oweer and some others the id_group
$cfg_default['use_id_group_for_ownership'] = 0;

// Enable create view (0|1): set to 1 to enable database views creation from DaDaBIK (admin interface -> Pages). Please note that when $enable_crate_view is set to 1, from admin -> Pages it is possible to create views incluing all the data the database user set for this application has privileges on, even data coming from other databases than the one is set in $db_name.
$cfg_default['enable_create_view'] = 0;

// By default, the use of semicolon characters is not allowed for the SQL query you use to create a VIEW in the admin -> pages section. It's a security measure, if an attacker gets access to the admin section, the semicolon can be exploited to add malicious queries to the "create view" query (e.g. insert, delete, drop queries on all the data the database user set for this application has privileges on).
// You can enable semicolons by setting this parameter to 1
$cfg_default['enable_semicolon_create_view'] = 0;

// By default, the use of semicolon characters is not allowed for the custom SQL query you use to create a report. It's a security measure, the semicolon can be exploited to add malicious queries to the "SELECT" query (e.g. insert, delete, drop queries on all the data the database user set for this application has privileges on).
// You can enable semicolons by setting this parameter to 1
$cfg_default['enable_semicolon_sql_report'] = 0;

// show the box containing the logout, edit your account and admin link (right top corner) (0|1)
// normally you want to set to 0 only if your DaDaBIK application is embedded in a Wordpress applicaiton and you enabled Wordpress authentication
$cfg_default['show_logout_account_admin_box'] = 1;

// for MySQL, you can disable the execution of multiple SQL statements coming from the same query execution. (0|1)
// This is useful to increase security in case of SQL injection bugs. This parameter makes use of MYSQL_ATTR_MULTI_STATEMENTS, a constant that exists as of PHP 5.5.21 and PHP 5.6.5; if you are running PHP 5.5 < 5.5.21 or PHP 5.6 < PHP 5.6.5 this parameter doesn't have effect. It also does not have effect on PostgreSQL, SQLite, SQL Server.
// If you set to 1, remember, when you write your own custom code, that you cannot execute a query containing multiple SQL statements
$cfg_default['disable_mysql_multiple_statements'] = 1;

// additional SQL security check (0|1). Check, for some of the SELECT queries DaDaBIK executes, for not expected strings such as ; DELETE INSERT UPDATE and others, strings that could represent an SQL injection attempt (0|1); You are strongly encouraged to leave this option set to 1, disable it only if you really know what you are doing.
$cfg_default['sql_select_security_check'] = 1;

// form configurator security check (0|1). For the parameters in form configurator involved in queries execution, such as "Lookup table name" or "Lookup table primary key field", DaDaBIK checks if they contain proper table and field names and if not, it throws an error. This is useful if for some reason their content has been maliciuosly modified to try an SQL injection attack. You are strongly encouraged to leave this option set to 1, disable it only if you really know what you are doing.
// you might get error even if in form configurator you are referring to a table (for example as an items table or as a linked table) or a field that is not available anymore (you dropped it, you uninstall it or you disable it). 
$cfg_default['form_config_security_check'] = 1;

// when enabled, before insert and update operations, for select_single, select_single_radio, select_multiple and select_multple_checkboxes fieldsm DaDaBIK checks if the value proposed belongs to the set of options available.
// the check is needed because a malicious user / attacker could try to add a value that is not available in the menu 
// the first parameter impact on the standard insert/update forms, the second one on insert/update operations performed using Excel/CSV import 
$cfg_default['dropdown_security_form_check'] = 1;

// enable Data Tab (admin section) operations (0|1). The data tab in the admin section allows admins to drop or alter any table in the database used by the DaDaBIK application or to add new tables. For security reason, by default, this option is set to 0, set it to 1 if you want to use the data tab.
// Please note that under particular circumstances this section allows malicious admins to execute arbitrary SQL code on your database or in other databases the DaDaBIK database user ($user in config) can access. Enable Data Tab only if you trust your admin users and if you are using a DaDaBIK database user who doesn't have privilages on other databases.
$cfg_default['enable_data_tab_operations'] = 0;


// LDAP parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable LDAP authentication (0|1), if 1, a checkbox "LDAP authentication" will be displayed below the log-in form; you can avoid setting the other LDAP parameters if LDAP authentication is disabled; please note that in a DaDaBIK application usernames must be unique, you can't have two users having the same username, even if one is a regular DaDaBK user and the other an LDAP user; please also note that DaDaBIK WON'T WORK properly if you use both LDAP authentication and your own custom users/groups table (not the default one), if you decide to use your own custom users/groups table, $enable_ldap_authentication must be 0  
$cfg_default['enable_ldap_authentication'] = 0;

// LDAP host, e.g. 'ldap.yourdomain.net'
$cfg_default['ldap_host'] = '';

// LDAP port, 389 is the default one
$cfg_default['ldap_port'] = '389';

// LDAP base dn, e.g. if your base dn is 'ou=Users,dc=yourdomain,dc=net', your settings for this parameter must be

// $ldap_base_dn_ar[0]['attribute_name']'] = 'ou';
// $ldap_base_dn_ar[0]['attribute_value']'] = 'Users';
// $ldap_base_dn_ar[1]['attribute_name']'] = 'dc';
// $ldap_base_dn_ar[1]['attribute_value']'] = 'yourdomain';
// $ldap_base_dn_ar[2]['attribute_name']'] = 'dc';
// $ldap_base_dn_ar[2]['attribute_value']'] = 'net';

// add as many element as you need 
// Please note that under the branch defined by base dn DaDaBIK ASSUMES that USERNAMES ARE UNIQUE, please also note that DaDaBIK doesn't work properly if usernames or base dn attribute values contain forward slashes ("/")

$cfg_default['ldap_base_dn_ar'][0]['attribute_name'] = '';
$cfg_default['ldap_base_dn_ar'][0]['attribute_value'] = '';

// LDAP default group: after LDAP authentication, the authenticated user is also inserted into the dadabik LDAP users table, here you can choose in which group you want to add him by default
$cfg_default['ldap_default_id_group'] = '2';

// LDAP attribute's name for username, e.g. 'cn'
$cfg_default['ldap_username_field'] = '';

// enable the local copy of LDAP users data (0|1), if 1, after LDAP authentication, not only the username but also name and email of the user, retrieved from the LDAP server, are copied into the local dadabik users list  
$cfg_default['ldap_copy_users_data'] = 1;

// next three parameters are needed only if $ldap_copy_users_data'] = 1
// LDAP attribute's name for first name
$cfg_default['ldap_first_name_field'] = '';

// LDAP attribute's name for last name
$cfg_default['ldap_last_name_field'] = '';

// LDAP attribute's name for email
$cfg_default['ldap_email_field'] = '';





// deletion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable delete all feature (delete operations must be enabled too, from the administration interface) (0|1)
$cfg_default['enable_delete_all_feature'] = 1;

// when a record is deleted, delete also the uploaded files related to that record (0|1)
$cfg_default['delete_files_when_delete_record'] = 1;

// ask confirmation before deleting a record? (0|1); note that this works just for the standard data grid, not for template data grids
$cfg_default['ask_confirmation_delete'] = 1;

// CSV / Excel parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// EXPORT
//////////////////////////////////////////////
// enable export to CSV for excel feature (0|1)
$cfg_default['export_to_csv_feature'] = 1;

// CSV separator
// If you want to directly open the CSV files with MS Excel, you have to use ; instead of ,
$cfg_default['csv_separator'] = ",";

// use sort for CSV (0|1): if 1 (default), the CSV is generated using the same sorting criteria used in the results grid; you might want to set it to 0 if you don't need a specific order and you have performances issues
$cfg_default['use_sort_for_csv'] = 1;

// CSV file creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$csv_creation_time_limt'] = 30;

// IMPORT
//////////////////////////////////////////////

// enable import from CSV / xls / ods feature (0|1)
// the user also needs the insert permissions to execute the import procedure 
$cfg_default['import_from_csv_feature'] = 1;

// after having uplaoded a file to import, you have a certain amount of time to complete the import operation (by default, 120 seconds), this is a security measure  
$cfg_default['seconds_to_complete_import'] = 120;

// formats allowed (csv, xlsx and ods can be enabeld)
$cfg_default['import_csv_allowed'] = 1;
$cfg_default['import_xlsx_allowed'] = 1;
$cfg_default['import_ods_allowed'] = 1;
    

// Same as the csv_creation_time_limt parameter (see before), but for CSV / Excel / ODS imports
//$csv_import_time_limit'] = 30;

// HTTP API parameters (HTTP API are available on DaDaBIK Platinum edition only).
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable http API (0|1). 
$cfg_default['enable_http_api'] = 0;

// limit the access through the HTTP API to these users only
// if you leave this field empty, the access through the HTTP API will be available to ANY user
// for security reason, it is better to limit the access to a few users and to exclude admin users
// e.g. if you want to limit the HTTP API to the users having id 5 and 10: $enable_http_api_user_ids'] = array(5,10);
// you can see the id of a user from your db (id_user field in dadabik_users table)
$cfg_default['enable_http_api_user_ids'] = array();

// lifetime (in seconds) of an access token 
$cfg_default['seconds_token_expiration'] = 86400;


// JSON answer creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$json_creation_time_limt'] = 30;

// PDF parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable export to PDF feature (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$cfg_default['export_to_pdf_feature'] = 1;

// PDF file creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$pdf_creation_time_limt'] = 30;

// PDF max number of records exported: uncomment the line below by removing // and set the limit if you need it. Since the PDF export is a resource-consuming activity, if you have a table with a huge number of records you might want to limit the export to a specific number of records: users could launch the export on the entire table (without filtering it) and cause a system freeze
// $pdf_max_number_records'] = 100;

// when you produce a PDF starting from a results set, set this parameter to 1 if you want a new page for each record, otherwise set it to 0 (useful especially if you want to create a grid, where each record is a row, in your pdf). Set this paramter to 0 only if you are using a custom template.
$cfg_default['add_pdf_page_for_each_record'] = 1;

// PDF page orientation: use P for portrait, L for landscape 
$cfg_default['pdf_page_orientation'] = 'P';

// set it to 1 if you want DaDaBIK to read the PDF template.php file even if $export_to_pdf_feature'] = 0. Why whould you need that? Because you can then override $export_to_pdf_feature in the template itself, enabling PDF export only for one or a few table.
$cfg_default['parse_pdf_php_template_even_if_export_pdf_disable'] = 0;

// use sort for PDF (0|1): if 1 (default), the PDF is generated using the same sorting criteria used in the results grid; you might want to set it to 0 if you don't need a specific order and you have performances issues
$cfg_default['use_sort_for_pdf'] = 1;

// null handling parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// set to 1 if you want to:
// 1) Treat empty form fields as NULL values during insert and update
// 2) View NULL values as empty form fields during edit
// $treat_blank_as_null'] = 1 should be the most user friendly option if your DaDaBIK application is used by non-technical users.
$cfg_default['treat_blank_as_null'] = 1;

// the word used to display a NULL content (could also be a blank string '', it is always a blank string in edit/insert forms if $treat_blank_as_null is 1)
$cfg_default['null_word'] = '';

// if $treat_blank_as_null is 0, for each field a NULL checkbox is displayed and allows to set the NULL value during insert/edit, unless you set $null_checkbox to 0. If you set $null_checkbox to 0 and $treat_blank_as_null to 0 there is no way to insert a NULL value as a field value of a record
// note that, if the record you are editing contains a NULL value for one or more fields, the NULL checboxes are displayed anyway, even if $null_checkbox is set to 0 (unless you have set treat_blank_as_null to 1)
$cfg_default['null_checkbox'] = 0;

// Upload parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable file uploads (0|1)
$cfg_default['enable_uploads'] = 0;

// $upload_directory is the absolute path (e.g. /users/john/dadabik/uploads/) of the directory where the files you upload with DaDaBIK are finally stored.
// if you leave it blank, everything should work anyway: the default uploads folder will be used (it's the directory UPLOADS in the DaDaBIK root folder) and its absolute path automatically determined by DaDaBIK
// however, you might want to set this paramtere anyway if you want to change the default upload folder (and you SHOULD DO THAT for SECURITY reasons, see the documentation, security chapter, for furhter details).
// if you are installing DaDaBIK on MS Windows, the $upload_directory automatic guessing could not work correctly so, on Windows, you MUST set it.

// how to properly set the $upload_directory
// please put slash (/) at the end
// e.g. 'c:\\data\\web\\dadabik\\uploads\\' on windows systems
// e.g. '/home/my/path/dadabik/uploads/' on unix systems
// make sure your webserver can write in this folder and in the temporary upload folder used by PHP
$cfg_default['upload_directory'] = '';

// max allowed size for the uploaded files (in bytes)
$cfg_default['max_upload_file_size'] = 20000000;

// allowed file extensions (users will be able to upload only files having these extensions); you can add new extensions and/or delete the default ones; if you add extensions you also have to provide the correct MIME type adding an element to the array $allowed_file_exts_mime_ar, if you delete an extension you have to delete also the corresponding MIME type in the $allowed_file_exts_mime_ar array

$cfg_default['allowed_file_exts_ar'][0] = 'jpg';
$cfg_default['allowed_file_exts_ar'][1] = 'gif';
$cfg_default['allowed_file_exts_ar'][2] = 'tif';
$cfg_default['allowed_file_exts_ar'][3] = 'tiff';
$cfg_default['allowed_file_exts_ar'][4] = 'png';
$cfg_default['allowed_file_exts_ar'][5] = 'txt';
$cfg_default['allowed_file_exts_ar'][6] = 'rtf';
$cfg_default['allowed_file_exts_ar'][7] = 'doc';
$cfg_default['allowed_file_exts_ar'][8] = 'xls';
$cfg_default['allowed_file_exts_ar'][9] = 'htm';
$cfg_default['allowed_file_exts_ar'][10] = 'html';
$cfg_default['allowed_file_exts_ar'][11] = 'csv';
$cfg_default['allowed_file_exts_ar'][12] = 'pdf';
$cfg_default['allowed_file_exts_ar'][13] = 'jpeg';
$cfg_default['allowed_file_exts_ar'][14] = 'docx';
$cfg_default['allowed_file_exts_ar'][15] = 'xlsx';

$cfg_default['allowed_file_exts_mime_ar'][0] = 'image/jpg';
$cfg_default['allowed_file_exts_mime_ar'][1] = 'image/gif';
$cfg_default['allowed_file_exts_mime_ar'][2] = 'image/tiff';
$cfg_default['allowed_file_exts_mime_ar'][3] = 'image/tiff';
$cfg_default['allowed_file_exts_mime_ar'][4] = 'image/png';
$cfg_default['allowed_file_exts_mime_ar'][5] = 'text/plain';
$cfg_default['allowed_file_exts_mime_ar'][6] = 'application/rtf';
$cfg_default['allowed_file_exts_mime_ar'][7] = 'application/msword';
$cfg_default['allowed_file_exts_mime_ar'][8] = 'application/vnd.ms-excel';
$cfg_default['allowed_file_exts_mime_ar'][9] = 'text/html';
$cfg_default['allowed_file_exts_mime_ar'][10] = 'text/html';
$cfg_default['allowed_file_exts_mime_ar'][11] = 'text/plain';
$cfg_default['allowed_file_exts_mime_ar'][12] = 'application/pdf';
$cfg_default['allowed_file_exts_mime_ar'][13] = 'image/jpeg';
$cfg_default['allowed_file_exts_mime_ar'][14] = 'application/msword';
$cfg_default['allowed_file_exts_mime_ar'][15] = 'application/vnd.ms-excel';


$cfg_default['allowed_all_files'] = 0; // set to 1 if you want to allow all extensions, and files without extension too; please note that, since you cannot provide the MIME type for all possibile files, file having extensions different than the ones specifiy in $allowed_file_exts_ar are not guaranteed to be handled as expected

// for generic files, when you click on the link to open the file itself, the file is open in a new window (_blank); set this parameter to 0 if you want to open it in the same window (_self)
$cfg_default['open_blank_generic_file'] = 1;

// THIS PARAMETER, $upload_relative_url, IS NOT USED ANYMORE and it's still in the config just for historical reasons
// relative URL from the DaDaBIK root dir to the upload folder; you can leave the default one, remember to put a slash (/) at the end if you change it
$cfg_default['upload_relative_url'] = 'uploads/';

// show uploaded images in the edit form (0 | 1); if 0, only the link to the image is displayed
$cfg_default['show_images_in_edit_form'] = 1;

// Uploded pictures, thumbnail parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

/*
If you don't want to use thumbnails, you can leave 0 for all the four parameters and the browser will display the uploaded images at their original size.
If you set a max width or height, the browser will resize the picture according to that. You can also force just width or just height, the browser will always scale the other dimension proportionally. 
*/

// maximum width (in px) of the pictures displayed in the results grid
$cfg_default['picture_thumbnail_results_grid_max_width'] = 0;

// maximum height (in px) of the pictures displayed in the results grid
$cfg_default['picture_thumbnail_results_grid_max_height'] = 0;

// maximum width (in px) of the pictures displayed in the details page
$cfg_default['picture_thumbnail_details_max_width'] = 0;

// maximum height (in px) of the pictures displayed in the details page
$cfg_default['picture_thumbnail_details_max_height'] = 0;

// Duplication check parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// maximum number of records to be displayed as duplicated during insert
$cfg_default['number_duplicated_records'] = 30;

// select similarity percentage used by duplication insert check
$cfg_default['percentage_similarity'] = 80;

// debug parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// display the main sql statements of insert/search/edit/detail operations for debugging (0|1)
// note that the insert sql statement is will be displayed only if $insert_again_after_insert is set to 1 and $show_details_after_insert'] = 0
$cfg_default['display_sql'] = 0;

// display all the sql statements and the MySQL error messages in case of DB error for debugging (0|1)
$cfg_default['debug_mode'] = 0;

// display the "I think that x is similar to y......" statements during duplication check (0|1)
$cfg_default['display_is_similar'] = 0;

// search parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the quick search feature, which provides a quick search row at the top of the results
$cfg_default['enable_quick_search_feature'] = 1;

// allow the "and/or" choice directly in the form during the search (0|1)
$cfg_default['select_operator_feature'] = 1;

// default operator (or/and), if the previous is set to 0
$cfg_default['default_operator'] = 'and';

// enable the possibility, for users, to specify AND / OR operators during a search e.g. typing "apple OR banana" (0|1)
// operators are case sensitive, OR and AND must be written in capital letters
// users cannot mix operators (city AND Europe OR USA)
$cfg_default['enable_user_booleans'] = 0;

// default order type: results grid, default order type (ascendant or descendant) to use ('ASC'|'DESC')
$cfg_default['default_order_type'] = 'ASC';

// data grid and details page parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// items of the menu used to choose the number of result records displayed per page, you can add new numbers and/or delete the default ones, don't delete $records_per_page_ar[0]
$cfg_default['records_per_page_ar'][0] = 10;
$cfg_default['records_per_page_ar'][1] = 20;
$cfg_default['records_per_page_ar'][2] = 50;
$cfg_default['records_per_page_ar'][3] = 100;

// target window for details/edit, 'self' is the same window, 'blank' a new window; this works just for the standard data grid, not for template data grids
$cfg_default['edit_target_window'] = 'self';

// coloumn at which a text, textarea and select_single field will be wrapped in the results page, this value also determines the width of the coloumn in the results table (standard data grid) if $word_wrap_fix_width is 1
$cfg_default['word_wrap_col'] = '25';

// allow that $word_wrap_col value also determines the width of the coloumn in the results table (standard data grid) (0|1)
$cfg_default['word_wrap_fix_width'] = 0;

// always wrap words at the $word_wrap_col column, even if it is necessary to cut them (0|1)
// FEATURE NO LONGER AVAILABLE!
$cfg_default['enable_word_wrap_cut'] = 1;

// image files to use as icons for delete, edit, details
// you can use both relative or absoulute url here
$cfg_default['delete_icon'] = 'images/delete.png';
$cfg_default['edit_icon'] = 'images/edit.png';
$cfg_default['details_icon'] = 'images/details.png';

// enable results table row highlighting (0|1)
$cfg_default['enable_row_highlighting'] = 1;

// enable the possibility to select, using a checkbox, several records in the results grid, for example to delete several records at once
$cfg_default['enable_record_checkboxes'] = 1;

// separator to use while displaying several lookup table linked field values related to the same field (in data grid and details page)
// e.g. Let's say if you have an id_customer select_single field having as linked lookup table linked fields first_name_customer and last_name_customer, the value ' - ' for $separator_display_linked_field will make an id_customer field value to be displayed as, e.g. "john - smith"
$cfg_default['separator_display_linked_field'] = ' '; // if you need more than one subsequent blank space, use the &nbsp; html entity for each one

// same as above, but for edit/search/quick search fields
$cfg_default['separator_display_linked_field_2'] = ' ';

// separator to use while displaying several values related to the same select_multiple_* field (in data grid and details page)
$cfg_default['separator_display_select_multiple'] = '<br/>';

// foor lookup field, you can choose in form configurator if you want to show a link to the related linked record; the parameter $function_link_to_record allows to choose which page you want to open when the link is clicked ('details' or 'edit')
$cfg_default['function_link_to_record'] = 'details';

// report parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable chart report generation (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$cfg_default['enable_report_generation'] = 1;

// enable pivot/summary table generation (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$cfg_default['enable_pivot_generation'] = 1;

// max number of rows as a result of a pivot table generation
$cfg_default['max_number_rows_pivot'] = 100;

// enable advanced SQL reports (0|1); if you enable advanced SQL reports, users will be able to define the report using a custom SQL SELECT query. Please note that ANY SELECT query is allowed, regardless of the permissions you set in DaDaBIK -> Admin Area, so the user might have access to any data in your database or even in other databases, if database user used by this DaDaBIK application ($user, look at the beginning of the file) has the needed permissions 
// Enabling this feature in combination with a non-empty $username_public_user can be dangerous (an unauthenticated user might have access to any data in your database or even in other databases).
$cfg_default['enable_advanced_sql_report'] = 0;

// The list of operators you can use to aggregate data when you generate a report; if you don't need one or more operator, simply comment the line adding // at the beginning of the row
$cfg_default['group_by_operators'][0]='percentage';
$cfg_default['group_by_operators'][1]='count';
$cfg_default['group_by_operators'][2]='sum';
$cfg_default['group_by_operators'][3]='average';
$cfg_default['group_by_operators'][4]='min';
$cfg_default['group_by_operators'][5]='max';
$cfg_default['group_by_operators'][6]='variance';
$cfg_default['group_by_operators'][7]='standard_deviation';

// The list of DaDaBIK field types you can use for "group by" and for aggregate functions when you generate reports; if you want to disable one or more field types, simply comment the line adding // at the beginning of the row, if you want to add a field type, add a new line. Please note that select_multiple field types don't work correctly here, don't add them.
$cfg_default['available_report_field_types'][0]='text';
$cfg_default['available_report_field_types'][1]='select_single_radio';
$cfg_default['available_report_field_types'][2]='select_single';
$cfg_default['available_report_field_types'][3]='date';
$cfg_default['available_report_field_types'][4]='date_time';
$cfg_default['available_report_field_types'][5]='insert_date_time';
$cfg_default['available_report_field_types'][6]='update_date_time';
$cfg_default['available_report_field_types'][7]='ID_user';

// The list of date functions you can use when, during the production of a report, you aggreagate your data based on a date or datetime field; if you want to disable one or more functions, simply comment the line adding // at the beginning of the row, you can't add additional functions
$cfg_default['report_date_functions'][0]='';
$cfg_default['report_date_functions'][1]='month';
$cfg_default['report_date_functions'][2]='year';
$cfg_default['report_date_functions'][3]='quarter';
$cfg_default['report_date_functions'][4]='week';
$cfg_default['report_date_functions'][5]='dayofweek';
$cfg_default['report_date_functions'][6]='hour';

// weekdays standard, set weeks_start_with_sunday to 1 if you want weeks to start on Sunday, otherwise weeks start on Monday
$cfg_default['weeks_start_on_sunday'] = 0;

// dates parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// date picker type: choose between 'flatpickr' (default date picker since DaDaBIK 10.2) or 'jquery' (the jQuery UI datapicker used before DaDaBIK 10.2). If your users use Internet Explorer, please note that flatpickr requires at least IE 10
$cfg_default['date_picker_type'] = 'flatpickr';

// format used to display date fields ('literal_english', 'latin' or 'numeric_english') in the results grid and details page
// 'literal_english': May 31, 2010
// 'latin': 31-5-2010
// 'numeric_english': 5-31-2010
// note that, depending on your system, you can have problem displaying dates prior to 01-01-1970 or after 19-01-2038 if you use the literal english format; in particular, it is known that this problem affects windows systems
$cfg_default['date_format'] = 'numeric_english';

// format used to display date fields in edit mode
// this parameter only has effect if you choose flatpickr as $date_picker_type, for the jquery date picker, in edit mode, dates are always displayed in year-month-day format
// for a list of possible formats see https://flatpickr.js.org/formatting/
$cfg_default['date_format_edit'] = 'm-j-Y';
$cfg_default['date_time_format_edit'] = 'm-j-Y H:i:S';

// date field separator (divides day, month and year; used only with latin and numeric_english date format)
$cfg_default['date_separator'] = "-";

// years range for the date picker, if you leave it empty by default it's 20 years (-10 +10 respect to the current year), but you can set a specific year e.g. $start_year'] = 1960
// this parameter only has effect if you choose jquery as $date_picker_type
$cfg_default['start_year'] = '';
$cfg_default['end_year'] = '';

// insertion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// choose if, in edit and insert forms, you want to see the "multiple inserts" checkbox  (0|1)
// this feature, to works, requires an auto-increment primary key on the related table 
$cfg_default['show_multiple_inserts_checkbox'] = 1;

// choose if, after an insert, you want DaDaBIK to display again the insert form (1) or not (0)
// this feature does not work when you insert a new record for an items table (master/details view) 
$cfg_default['insert_again_after_insert'] = 0;

// choose if, after an insert, you want DaDaBIK to show you a details page of the record just inserted
// this feature, to works, requires an auto-increment primary key on the related table
$cfg_default['show_details_after_insert'] = 0;

// record locking parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the record lock feature when a user is editing a record
$cfg_default['enable_record_lock_feature'] = 1;

// number of seconds after which a record is automatic unlocked (useful to avoid a record being locked forever when, for example, a user enters the record in edit mode and then left his workstation); choose a value >= 60
$cfg_default['seconds_after_automatic_unlock'] = 240;

// forms and results grid settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// show update and search submit buttons also at the top of the forms, not just at the bottom (0|1)
$cfg_default['show_top_buttons'] = 1;

// in the edit form show, after SAVE and BACK buttons, a SAVE+BACK button
$cfg_default['show_save_plus_back_button'] = 1;

// in the deails form, show an "edit" button
$cfg_default['show_edit_button_in_details'] = 0;

// number of items a select_multiple_menu field should show
$cfg_default['size_multiple_select'] = 3;

// show "previous" and "next" buttons in edit and details forms (0|1)
// if you have tables with millions of records and your memory is not enough, the Web server could crash trying to find the next/previous record; if this happens, you should disable $show_previous_next
$cfg_default['show_previous_next'] = 1;

// in the results grid page, show the page navigation links also at the bottom of the results grid, not just at the top (0|1)
$cfg_default['show_navigation_bar_bottom'] = 1;

// In a master/details form, by default, if the user opens the DETAILS page related to a record of the master table, the insert, delete and edit buttons are not displayed for the items table, regardless of the related permissions. Set this parameter to 0 if you want to show the buttons (of course the user must also have the permissions to do the insert, edit or delete operations).
$cfg_default['details_page_dont_show_insert_edit_delete_items'] = 1;


// menu settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// always show menu items; by default they are hidden if they are not at TOP level AND they (and their parents) are not currently selected) (0|1)
$cfg_default['always_show_menu_itmes'] = 0;

// email notices parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable_insert_notice_email_sending: when a new record is inserted an e-mail containing the record details will be sent to the addresses below (0|1)
// this feature requires an "auto increment" primary key in the relative table
$cfg_default['enable_insert_notice_email_sending'] = 0;

// set here the recipient addresses of the insert notice e-mail
// Here is an example
// $insert_notice_email_to_recipients_ar[0]'] = 'my_account_1@my_provider.com';
// $insert_notice_email_to_recipients_ar[1]'] = 'my_account_2@my_provider.com';
// $insert_notice_email_cc_recipients_ar[0]'] = 'my_account_3@my_provider.com';
// $insert_notice_email_bcc_recipients_ar[0]'] = 'my_account_4@my_provider.com';

$cfg_default['insert_notice_email_to_recipients_ar'][0] = '';

$cfg_default['insert_notice_email_cc_recipients_ar'][0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$cfg_default['insert_notice_email_bcc_recipients_ar'][0] = '';

// enable_update_notice_email_sending: when a record is updated an e-mail containing the new record details will be sent to the addresses below (0|1)
$cfg_default['enable_update_notice_email_sending'] = 0;

// set here the recipient addresses of the update notice e-mail
// Here is an example
// $update_notice_email_to_recipients_ar[0]'] = 'my_account_1@my_provider.com';
// $update_notice_email_to_recipients_ar[1]'] = 'my_account_2@my_provider.com';
// $update_notice_email_cc_recipients_ar[0]'] = 'my_account_3@my_provider.com';
// $update_notice_email_bcc_recipients_ar[0]'] = 'my_account_4@my_provider.com';

$cfg_default['update_notice_email_to_recipients_ar'][0] = '';

$cfg_default['update_notice_email_cc_recipients_ar'][0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$cfg_default['update_notice_email_bcc_recipients_ar'][0] = '';

// other parameters
///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////// 


// Insert, Update and Delete operations are logged into a log table. This can help a data recovery procedure in case of data loss.
// Please note that operations you execute through hooks or other custom functions are not logged.
$cfg_default['enable_sql_logging'] = 0;

// Successful login are logged into a log table, storing username and timestamp
// this only works if $enable_sql_logging'] = 1
$cfg_default['enable_access_logging'] = 0;




// $open_url_field_type_new_window (0|1); by default, for URL field type, the link is open in the current page (target _self), set this parameter to 1 if you prefer to open a new window (_blank)
$cfg_default['open_url_field_type_new_window'] = 0;


// Revisions/Audit parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// for each table you enable revisions for, a revisions table having the same fields is created: the table contains old versions of the records. If you change the field types of the original table, you should change them manually for the revisions table as well. If, however, you set $use_text_fields_for_revisions_table'] = 1, all the fields of the revisions table will be created as TEXT and you can avoid to keep the field types synchronized. TEXT fields work in most of the cases, but not in ALL the cases, futhermore, if you have lookup fields, with some DBMS (e.g. postgresql) you can have problems. Change this parameter only if you know what you are doing.
// If you use sqlite, however, at the moment setting $use_text_fields_for_revisions_table'] = 1 is the only way to use revisions.
$cfg_default['use_text_fields_for_revisions_table'] = 0;


// DATA section parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// You should leave the settings as they are

// Settings for MySQL
// list of avaliable data types, in case the "simple" data types option is chosen
$cfg_default['simple_field_types']['mysql']['int(11)'] = 'Integer number';
$cfg_default['simple_field_types']['mysql']['decimal(12,2)'] = 'Decimal number';
$cfg_default['simple_field_types']['mysql']['float'] = 'Float number';
$cfg_default['simple_field_types']['mysql']['varchar(255)'] = 'Short text';
$cfg_default['simple_field_types']['mysql']['text'] = 'Long text';
$cfg_default['simple_field_types']['mysql']['date'] = 'Date';
$cfg_default['simple_field_types']['mysql']['datetime'] = 'Date and time';
$cfg_default['simple_field_types']['mysql']['time'] = 'Time';
                        

// Settings for PostgreSQL
// list of avaliable data types, in case the "simple" data types option is chosen
$cfg_default['simple_field_types']['postgres']['integer'] = 'Integer number';
$cfg_default['simple_field_types']['postgres']['numeric'] = 'Decimal number';
$cfg_default['simple_field_types']['postgres']['real'] = 'Float number';
$cfg_default['simple_field_types']['postgres']['character varying(255)'] = 'Short text';
$cfg_default['simple_field_types']['postgres']['text'] = 'Long text';
$cfg_default['simple_field_types']['postgres']['date'] = 'Date';
$cfg_default['simple_field_types']['postgres']['timestamp without time zone'] = 'Timestamp';
$cfg_default['simple_field_types']['postgres']['time without time zone'] = 'Time';


// Settings for MS SQL Server
// list of avaliable data types, in case the "simple" data types option is chosen
$cfg_default['simple_field_types']['sqlserver']['int'] = 'Integer number';
$cfg_default['simple_field_types']['sqlserver']['decimal (12,2)'] = 'Decimal number';
$cfg_default['simple_field_types']['sqlserver']['float'] = 'Float number';
$cfg_default['simple_field_types']['sqlserver']['nvarchar (255)'] = 'Short text';
$cfg_default['simple_field_types']['sqlserver']['ntext'] = 'Long text';
$cfg_default['simple_field_types']['sqlserver']['date'] = 'Date';
$cfg_default['simple_field_types']['sqlserver']['datetime'] = 'Date and time';
$cfg_default['simple_field_types']['sqlserver']['time'] = 'Time';


// settings related to the auto-configurator feature
/*
DaDaBIK, using some rules, tries to guess the field types, labels field content types and other paramters you would set for a DaDaBIK application after its installation. The guess works according to some "signals"; for example, if a database field name contains the word "email", DaDaBIK set the content type of the field as email.
You can influence the rules used by DaDaBIK changing the following parameters. You can safety leave the the settings as they are if you want to use the default rules
*/
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the auto-configurator feature (0|1)
$cfg_default['enable_auto_configurator'] = 1;

// set the NOT NULL database fields as required in DaDaBIK update/insert forms (0|1)
$cfg_default['autoconf']['set_notnull_fields_as_required'] = 1;

// set the correspondence between database field types and DaDaBIK field type/content

// settings for MySQL
// DB field types that dadabik considers as numeric
$cfg_default['autoconf']['numeric_fields']['mysql'] = array('integer','int','smallint','tinyint','mediumint','bigint','decimal','numeric','float','double','bit','year');

// DB field types that dadabik considers as date
$cfg_default['autoconf']['date_fields']['mysql'] = array('date');

// DB field types that dadabik considers as date_time
$cfg_default['autoconf']['date_time_fields']['mysql'] = array('datetime','timestamp');

// DB field types that dadabik considers as textbox 
$cfg_default['autoconf']['textbox_fields']['mysql'] = array('char','varchar','binary','varbinary');

// DB field types that dadabik considers as textarea 
$cfg_default['autoconf']['textarea_fields']['mysql'] = array('blob','text');

// DB field types that dadabik considers as select_single 
$cfg_default['autoconf']['select_single_fields']['mysql'] = array('enum');

// settings for PostgreSQL
// DB field types that dadabik considers as numeric
$cfg_default['autoconf']['numeric_fields']['postgres'] = array('bigint','int8','bigserial','serial8','bit','bit varying','varbit','double precision','integer','int','int4','float8','numeric','decimal','real','float4','smallint','int2','serial','serial4');


// DB field types that dadabik considers as date
$cfg_default['autoconf']['date_fields']['postgres'] = array('date');

// DB field types that dadabik considers as date_time
$cfg_default['autoconf']['date_time_fields']['postgres'] = array('timestamp','timestamp with time zone','timestamp without time zone');

// DB field types that dadabik considers as textbox 
$cfg_default['autoconf']['textbox_fields']['postgres'] = array('character varying','character','varchar','char');

// DB field types that dadabik considers as textarea 
$cfg_default['autoconf']['textarea_fields']['postgres'] = array('text');

// DB field types that dadabik considers as select_single 
$cfg_default['autoconf']['select_single_fields']['postgres'] = array();

	

// settings for SQLite
// DB field types that dadabik considers as numeric
$cfg_default['autoconf']['numeric_fields']['sqlite'] = array('int','integer','tinyint','smallint','mediumint','bigint','unsigned big int','int2','int8','real','double','double precision','float','numeric','decimal'.'boolean');

// DB field types that dadabik considers as date
$cfg_default['autoconf']['date_fields']['sqlite'] = array('date');

// DB field types that dadabik considers as date_time
$cfg_default['autoconf']['date_time_fields']['sqlite'] = array('datetime');

// DB field types that dadabik considers as textbox 
$cfg_default['autoconf']['textbox_fields']['sqlite'] = array('character','varchar','varying character','nchar','native character','nvarchar');

// DB field types that dadabik considers as textarea 
$cfg_default['autoconf']['textarea_fields']['sqlite'] = array('text','clob','blob');

// DB field types that dadabik considers as select_single 
$cfg_default['autoconf']['select_single_fields']['sqlite'] = array();


// settings for sqlserver
$cfg_default['autoconf']['numeric_fields']['sqlserver'] = array('bigint','numeric','bit','smallint','decimal','smallmoney','int','tinyint','money','real','float');

// DB field types that dadabik considers as date
$cfg_default['autoconf']['date_fields']['sqlserver'] = array('date');

// DB field types that dadabik considers as date_time
$cfg_default['autoconf']['date_time_fields']['sqlserver'] = array('datetime','datetime2','smalldatetime','datetimeoffset');

// DB field types that dadabik considers as textbox 
$cfg_default['autoconf']['textbox_fields']['sqlserver'] = array('char','varchar','nchar','nvarchar');

// DB field types that dadabik considers as textarea 
$cfg_default['autoconf']['textarea_fields']['sqlserver'] = array('text','ntext','binary', 'varbinary');

// DB field types that dadabik considers as select_single 
$cfg_default['autoconf']['select_single_fields']['sqlserver'] = array();


// set the correspondence between the name of the database field and the DaDaBIK content

// words that DaDaBIK considers as a hint to guess the content of the field is phone
$cfg_default['autoconf']['phone_words'] = array ('phone', 'telefono');

// words that DaDaBIK considers as a hint to guess the content of the field is email
$cfg_default['autoconf']['email_words'] = array ('email');

// words that DaDaBIK considers as a hint to guess the content of the field is url
$cfg_default['autoconf']['url_words'] = array ('website');



$cfg_default['autoconf']['numeric_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_then/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$cfg_default['autoconf']['date_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_then/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$cfg_default['autoconf']['date_time_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_then/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$cfg_default['autoconf']['textbox_fields']['search_operators'] = 'contains/doesnt_contain/is_equal/is_different/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty/between';
$cfg_default['autoconf']['textarea_fields']['search_operators'] = 'contains/doesnt_contain/is_equal/is_different/starts_with/ends_with/greater_than/less_then/is_null/is_not_null/is_empty/is_not_empty/between';
$cfg_default['autoconf']['select_single_fields']['search_operators'] = 'is_equal/is_different/is_null/is_not_null';


// default settings: when DaDaBIK doesn't see any particular signal to guess a field configuration, it ueses these default values

// field type
$cfg_default['autoconf']['default']['type_field'] = 'text';

// field content type
$cfg_default['autoconf']['default']['content_field'] = 'alphanumeric'; 

// Maxlength
$cfg_default['autoconf']['default']['maxlength_field'] = '100';

// Search operators
$cfg_default['autoconf']['default']['search_operators'] = 'is_equal/is_different/contains/doesnt_contain/starts_with/ends_with/greater_than/less_then/greater_equal_than/less_equal_than/is_null/is_not_null/is_empty/is_not_empty/between';

// Is the field required in DaDaBIK insert/edit forms? ('0'|'1')
$cfg_default['autoconf']['default']['required_field'] = '0';

// advanced configuration settings, you can safety leave the following settings as they are
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// the name of the main file of DaDaBIK, you can safety leave this option as is unless you need to rename index.php to something else
$cfg_default['dadabik_main_file'] = 'index.php';

// the name of the login page of DaDaBIK, you can safety leave this option as is unless you need to rename login.php to something else
$cfg_default['dadabik_login_file'] = 'login.php';

// function to apply to all textbox values inserted by the user, before INSERT and UPDATE occur (e.g. you may want to capitalize values)
// leave empty if no function is needed (in fact, normally is NOT needed) or fill the parameter with the function's name (the name   must start with "dadabik_") and write the related function's code in custom_functions.php. In custom_functions.php you can find an example (function dadabik_capitalize)
$cfg_default['function_user_values'] = '';

// by default, the function set with $function_user_values is applied just to text field types, set the following parameter to 1 if you want to apply it also to textarea and rich_editor text types
$cfg_default['apply_function_user_values_to_textarea_richeditor'] = 0;

// for a calculated field, its value is computed when a new record is inserted and re-computed at every update ("Save" on the edit form), even if the field is hidden from the form or disabled. If you set this parameter to 0, during an update the value of a calculated field is not re-computed if the field is hidden or disabled.
$cfg_default['compute_calculated_fields_update_if_hidden_disabled'] = 1;

// function to use for generating IDs if a field type is "unique_ID", if you leave it blank the default DaDaBIK function will be used, if you want to use a custom function set the name here (the name must start with "dadabik_") and write the related function's code in custom_functions.php; the function must return the unique id value.
$cfg_default['unique_ID_custom_function'] = '';

// prefix of the NULL checkbox name
$cfg_default['null_checkbox_prefix'] = 'null_value__';

// prefix of the custom buttons' IDs
$cfg_default['custom_button_ids_prefix'] = 'cb__';

// prefix of the field+buttons+hint container IDs
$cfg_default['field_button_hint_container_id_prefix'] = 'cont_field_button_hint_';

// prefix of the selet checkbox 
$cfg_default['select_checkbox_prefix'] = 'sel_chk__';

// suffix of the select type (contains, is_null....) listbox
$cfg_default['select_type_select_suffix'] = '__select_type';

// suffix of the date time types
$cfg_default['year_field_suffix'] = '__year';
$cfg_default['month_field_suffix'] = '__month';
$cfg_default['day_field_suffix'] = '__day';
$cfg_default['hours_field_suffix'] = '__hours';
$cfg_default['minutes_field_suffix'] = '__minutes';
$cfg_default['seconds_field_suffix'] = '_seconds';

// htmLawed configuration, you shouldn't change this parameter, changing this parameter can lead to security problems, read documentation for more information
$cfg_default['htmlawed_config'] = array('safe'=>1, 'deny_attribute'=>'style, class');

// choose the correct db library; you shouldn't change this parameter
$cfg_default['db_library'] = 'pdo';

// perform a check about mbstring extension installation; this parameter should be always set to 1
$cfg_default['mbstring_check'] = 1;

// alias_prefix
$cfg_default['alias_prefix'] = '____'; // you can safety leave this option as is

// how does DaDaBIK read the files uploaded: 'public_url' or 'php'; for security reason, you should keep this option set to "php" and keep the upload folder (see $upload_directory) protected from public access (see documentation)
$cfg_default['file_access_mode'] = 'php';

// The separator characther used in form configurator to separate values in  "option to include", "Linked fields", "Linked items table names" and "Items table foreign key field names" parameters. If you change this parameter after the installation, you will have to modify the charaters whereever you used it in the form configurator
// the lenght of the separator needs to be 1
//define('FORM_CONFIGURATOR_SEPARATOR', '~');


// if 1, the custom formatting functions take in input, for select_single/select_multiple fields having more than one linked field, each linked field value singularly. This allows to have more control on the outupt. It should be set to 0 just for compatibility with DaDaBIK applications created with DaDaBIK < 7.1 (if you can't modify your formatting functions). (0|1)
$cfg_default['pass_linked_fields_to_format_function_as_array'] = 1;

// default function to be executed when you click on a menu item related to a page based on table/view. If you don't specify anything, by default the results grid is shown; alternatively, you can add an elmeent to the associative array $table_default_functions and specify 'show_search_form' or 'show_insert_form'
/* in this example
$cfg_default['table_default_functions['customers']'] = 'show_insert_form';
for the table customers, the default function is show_insert_form
*/

// minimum number of characters to type in a user-friendly searchable ajax drop-down menu before DaDaBIK starts querying the table to find the matching options
$cfg_default['ajax_dropdown_minimum_input_length'] = 3;

// check if table and field names, for the tables installed in DaDaBIK, contains not allowed characters. You are strongly encouraged to leave this option set to 1.
$cfg_default['check_table_field_names'] = 1;

// DaDaBIK checks (mysql only) if the NO_BACKSLASH_ESCAPES sql mode is enabled, and produces an error in case it is. NO_BACKSLASH_ESCAPES is not compatible with DaDaBIK, you are strongly encouraged to leave this option set to 1.
$cfg_default['check_sql_mode'] = 1;

// show a warning about the required field type when a custom user or group table is in use
$cfg_default['warning_custom_users_groups'] = 1;

// show a warning about the use of session.auto_start in php.ini setting.
// if you set session.auto_start = 1 in php.ini, DaDaBIK cannot assign a custom session name and cookie_path, this makes your applicaiton less secure and DaDaBIK shows a warning message. It is strongly recommended to set session.auto_start = 0. You can disable the warning by setting $warning_sessions = 0; disable it only if you really know what you are doing. 
$cfg_default['$warning_sessions'] = 1;

// set to 1 to include CSS and JS files by adding an additional random value to their URL, this is useful to prevent caching if you are changing the CSS / JS files, leave it to 0 if your app is in production
$cfg_default['force_reload_css_js'] = 0;

// absolute path of the directory where DaDaBIK lives - please put slash (/) at the end
// e.g. 'c:\\data\\web\\dadabik\\uploads\\' on windows systems
// e.g. '/home/my/path/dadabik/uploads/' on unix systems
// you can leave it empty, fill it only if the CSV/Excel process asks you to do it
$cfg_default['working_dir'] = '';

// don't change this parameter
$cfg_default['dir_custom_php_files'] = 'custom_php_files';

// don't change this parameter 
$cfg_default['dir_templates'] = 'templates';




// check data coming from insert/update forms to guess if all the data actually arrived (the PHP parameter post_max_size can limit the amount of data), you should always set this parameter to 1 
$cfg_default['check_post_for_data'] = 1;

// MS SQL Server unicode transformation rules 

// use rules (0|1)
$cfg_default['use_unicode_sqlserver_transformations'] = 1;

// rules list
$cfg_default['unicode_sqlserver_transformations'][0]['input'] = "('";
$cfg_default['unicode_sqlserver_transformations'][0]['output'] = "(N'";

$cfg_default['unicode_sqlserver_transformations'][1]['input'] = ",'";
$cfg_default['unicode_sqlserver_transformations'][1]['output'] = ",N'";

$cfg_default['unicode_sqlserver_transformations'][2]['input'] = ", '";
$cfg_default['unicode_sqlserver_transformations'][2]['output'] = ", N'";

$cfg_default['unicode_sqlserver_transformations'][3]['input'] = "= '";
$cfg_default['unicode_sqlserver_transformations'][3]['output'] = "= N'";

$cfg_default['unicode_sqlserver_transformations'][4]['input'] = "<> '";
$cfg_default['unicode_sqlserver_transformations'][4]['output'] = "<> N'";

$cfg_default['unicode_sqlserver_transformations'][5]['input'] = "> '";
$cfg_default['unicode_sqlserver_transformations'][5]['output'] = "> N'";

$cfg_default['unicode_sqlserver_transformations'][6]['input'] = "< '";
$cfg_default['unicode_sqlserver_transformations'][6]['output'] = "< N'";

$cfg_default['unicode_sqlserver_transformations'][7]['input'] = "like '";
$cfg_default['unicode_sqlserver_transformations'][7]['output'] = "like N'";

// by default, in form configurator, custom PHP functions (e.g. the custom validation function for a field) are chosen from a listbox, parsing the custom_functions.php and related files. If, instead, you want to type the name of the function manually (as it happened before DaDaBIK 10), set this parameter to 0.
$cfg_default['use_listbox_for_custom_function_selection'] = 1;


// enable form configurator live preview (0|1, default is 1)
// DaDaBIK shows a live preview of the form while you edit the form configurator. You can disable the live preview if you think it causes problems
$cfg_default['enable_form_config_live_preview'] = 1;

?>