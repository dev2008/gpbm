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



/******************************** PLEASE READ HERE BEFORE EDITING! **************************************************
***************************************************************************************************
***************************************************************************************************





DON'T EDIT THIS FILE, STARTING FROM DADABIK 10.4 YOU MUST USE inclue/config_custom.php TO SET YOUR CONFIGURATION PARAMETERS, SEE THE DOCUMENTATION https://dadabik.com/index.php?function=show_documentation#config
YOU SHOULD USE THIS FILE ONLY AS A DOCUMENTATION FILE, WHERE YOU CAN SEE ALL THE PARAMETERS AVAILABLE AND THEIR POSSIBLE VALUES.




***************************************************************************************************
***************************************************************************************************
****************************************************************************************************/


///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// required installation parameters: please specify at least the following parameters

// your license serial number, you can find it in the email you received from mailer@fastspring.com, containing the download link
// and having as a subject something like "Your DaDaBIK 13.5 Levanto Enterprise Delivery Information"
// the serial number is 8 characters long
$serial_number = '';

// dbms type ('mysql' or 'postgres' or 'sqlite' or 'sqlserver'); sqlserver is for Microsoft SQL Server; if you are using sqlserver be sure to have the TCP/IP protocol enabled, if you are using SQL Server on Azure please also check the parameter $tables_to_exclude.
$dbms_type = 'mysql';

// DBMS server host
$host = ''; // the name or the IP of the host (computer) where the DBMS is running (e.g. '127.0.0.1' if the DBMS is running locally); please try 'localhost' instead of '127.0.0.1' if '127.0.0.1' is not working; some Web Hosting providers can require a full name e.g. mysql.yourdomain.com; for SQLite this parameter is not needed. For MySQL and PostgreSQL you can also specify the port (if it's not the default one) separated from the host by a ":", e.g. '127.0.0.1:54310' to use the port 5431

// database name
// DaDaBIK will be installed in a database, specify its name here. It can be an empty DB (perfect to start building your app from scratch) or a pre-populated DB (DaDaBIK will create an application on the top of it). If you use MySQL and the DB you set here doesn't exist, DaDaBIK will try to create it; for postgres, sqlite and sqlserver, instead, the DB (even if empty) must be already available.
$db_name = ''; // if you use SQLite: not only the name but 1) the full path is also needed (e.g. '/my_databases/music_databases/songs.db') and 2) you need to grant to the Web server write permissions on the database file

// database user
$user = 'root'; // this user must have select, insert, update, delete permissions, create and drop permissions are also needed for installation, upgrade and administration area e.g. 'root'; for SQLite this parameter is not needed
// for better security, you should choose a user who has privileges ONLY on the database used by this application ($db_name parameter)

// database password
$pass = ''; // for sqlite this parameter is not needed

// database schema (only needed for postgres, not needed for mysql and sqlite, if you don't know the schema, leave "public")
$db_schema = 'public';

// timezone, specify here your timezone (You can find a list of available timezones here: http://php.net/manual/en/timezones.php)
$timezone = 'Europe/Rome';


// secret_key: a long (60+ characters), random and complicated phrase which is used to sign authentication cookies for this application and therefore improve authentication security; you don't need to remember this phrase
// please note that in order to benefit from this security mechanism you must choose a different secret_key for each DaDaBIK application you create and you must keep this value secret
$secret_key = '';


// dadabik_session_name: the name of the session for this DaDaBIK application, used to avoid unexpected and risky effects if other applications installed in the same domain make use of the same session variable names that this application uses
// it must contain only ASCII letters and numbers, with at least one letter and no blank spaces; its length must be less than 100 characters and must be different than $secret_key; you don't need to remember this name
// please note that in order to benefit from this security mechanism you must choose a name which is unique among session names used by other applications installed in the same domain
$dadabik_session_name = '';



// DaDaBIK complete url (e.g. 'http://www.mysite.com/john/dadabik/', REMEMBER the trailing slash)
// YOU CAN SAFELY LEAVE THIS PARAMETER EMPTY (''), you should try to set it only in two cases:
// 1) IF DADABIK REDIRECTS YOU TO THE WRONG URL AFTER LOGIN
// 2) IF YOU ARE HAVING ISSUES DISPLAYING IMAGES IN PDF
$site_url = '';


// DaDaBIK site path is the path your session cookies are valid for
// YOU CAN LEAVE THIS OPTION EMPTY (''), NORMALLY IT IS CORRECTLY GUESSED BY DADABIK
// If you want extra security, you can set it manually: if you execute DaDaBIK from e.g. 'http://www.mysite.com/john/dadabik/' the site path must be /john/dadabik/, remember to put slashes at the beginning and at the end; put just one slash '/' if DaDaBIK is installed in the root of a Website, (e.g. 'http://www.mysite.com/')

$site_path = '';


// User Interface Style ('classic' | 'modern')
// 'classic': the standard DaDaBIK user interface.
// 'modern' : a new, experimental user interface (beta) introduced in DaDaBIK 13.5.
$ui_style = 'classic';

// choose your graphic theme: 'classic', 'bluegray', 'black', 'blue', 'green'
// this option is no more available 
$graphic_theme = 'bluegray';

// choose your results grid layout scrolling: 'site_overflow', 'grid_overflow' or 'grid_scroll'
// this affect the layout when your results grid is wider than your screen:
// grid_scroll (default): an horizontal scrollbar appears just for the grid, the grid is independent
// site_overflow (old 7.x DaDaBIK style): an horizontal scrollbar appears for the whole page, all the site layout is affected
// grid_overflow: an horizontal scrollbar appears for the whole page, the site layout is not affected, the grid is independent
// Internet Explorer always works in grid_scroll mode, regardless of the settings.
// Small devices (display width < 768px, typycally smartphones) always work in grid_scroll mode as well
$grid_layout_scrolling = 'grid_scroll';

// set to 1 to enable, for the results grid, the fixed header. The fixed header is not compatible with $grid_layout_scrolling = grid_overflow
$results_grid_fixed_header = 0;

// choose the type of menu you want to use for page selection: 'left_side_menu' or 'drop_down_menu'
$menu_type = 'left_side_menu';

// don't show the menu if there is only one menu item (0|1)
$dont_show_menu_if_only_one_item = 0;

// by default, show the left menu collapsed (closed)
$show_collapsed_menu_by_default = 0;

// display mode for the results grid ('both','classic_grid','list'), the default is 'both', which allows the user to choose through a drop-down menu, 'classic_grid' and 'list' don't show the drop-down menu.
$results_display_mode_menu = 'both';

// define the logo (by default it's the DaDaBIK logo)
$logo_img = 'images/logo.png';

// define the title of the application (<title> html tag)
$title_application = 'DaDaBIK database front-end - dadabik.com';

/*
max number of characters displayed in the results grid for textarea fields (excluding HTML fields).
If you specify a number (e.g., 150), only the first 150 characters of longer texts will be shown, followed by an ellipsis (...). If set to 0 (the default setting), the text will not be truncated.
*/
$maxlength_grid = 0;


// additional installation parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// DaDaBIK needs a unique field for each table and view you want to use (tipycally this is the primary key of the table) . Since it doesn't support composite primary keys (primary keys composed by multiple fields), you can, optionally, ask DaDaBIK to automatically add an additional, unique, auto-increment field, when, during installation, it finds a table having a composite primary key. This only works for MySQL.
$add_additional_dadabik_id_field = 0;

// the name of field to add (see the parameter above); please note that in case, for some reason, you already have a field with this name in the table, DaDaBIK won't add the field and the field will be used as unique field, regardles of its characteristics
$name_additional_dadabik_id_field = 'dadabik_id';


// prefix to use for the name of the tables created by DaDaBIK, all the tables created by DaDaBIK use this prefix
$prefix_internal_table = 'dadabik_'; // you can safely leave this option as is, you *must* leave this option as is after the installation


/*
DaDaBIK, during the installation, installs in the application all the tables available in the database; add here the tables (if any) you want to exclude, one per line, for example if you want to exclude the tables "customers" and "countries" write:
$tables_to_exclude[0]='customers';
$tables_to_exclude[1]='countries';
don't touch this parameter if you want to include all the tables. You can always uninstall tables later using the admin interface.
If you are using Microsoft SQL Server on Azure, consider that you cauld have in your database tables that Azure use for internal purposes that you probably don't want in your application. An example is the table "database_firewall_rules"
*/

$tables_to_exclude[0]='';

/*
DaDaBIK, during the installation, installs in the application all the tables available in the database; add here the tables (if any) you want to exclude, one prefix per line, for example if you want to exclude all the tables that start with "wp_" and all the tables that start with "test_" write:
$prefixes_to_exclude[0]='wp_';
$prefixes_to_exclude[1]='test_';

don't touch this parameter if you want to include all the tables. You can always uninstall tables later using the admin interface.

This parameter, in particular, is often used to easily install and use two or more different DaDaBIK applications using a shared database: for each apppplication you need a different $prefix_internal_table and you need to set, for $prefixes_to_exclude, all the prefixes used by the other applications.

*/

$prefixes_to_exclude[0]='';

// DaDaBrain AI
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// By default, DaDaBrain AI is set to 0 (disabled)
// Before enabling it (set it to 1), please review the related chapter in the manual, in particular the security implications
$enable_dadabrain = 0;

// By default, the DaDaBrain AI form is displayed, set it to 0 if you want to hide it
$show_dadabrain_form = 1;

// limit the use of DaDaBrain AI to these users only
// if you leave this array empty, the access to DaDaBrain will be available to ANY user (if $enable_dadabrain = 1)
// e.g. if you want to limit DaDaBrain to the users having id 5 and 10: $enable_dadabrain_user_ids = array(5,10);
// you can see the id of a user from your db (id_user field in dadabik_users table)
$enable_dadabrain_user_ids = array();

// DaDaBrain AI can optionally log user questions and the corresponding generated SQL queries in the database table dadabik_dadabrain_queries
// When logging is enabled, administrators can audit AI-generated queries, review potentially incorrect results, and better understand how the feature is being used.
// If query logging is enabled, administrators are responsible for informing users according to their organization’s logging policies.
// Please note that, even when log_dadabrain_queries is disabled, DaDaBrain queries that create views are stored in the dadabik_custom_reports table: datetime, id_user, user query, SQL generated and other info are stored; however each user can discard these custom reports
$log_dadabrain_queries = 0;

// when logging user questions, also log the id of the user
$log_dadabrain_queries_user = 0;

// custom label
$dadabrain_label = '🤖 <strong>DaDaBrain AI</strong>';


// maintenance parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// when $maintenance_mode is 1, non-administrator users can't access the application, $debug_mode and $display_sql will be automatically enabled and the page execution time will be printed in the page's footer
// please note that the login page is still available and, for HTTP API, the tokens generation is still available
// when maintenance_mode is 0, you can still set maintenance mode from the application itself (DEV Area -> status)
$maintenance_mode = 0;

// the message users will see when the application is maintenance mode
$maintenance_message = 'This application is currently under maintenance.';

// if $maintenance_page is not empty, instead of showing $maintenance_message, DaDaBIK will redirected users to a maintenance HTML or PHP page of your choice. The page must be in the DaDaBIK folder, set here the page filename (e.g. 'maintenance.html')
$maintenance_page = '';

// language parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// the list of languages the user can choose from; you can remove one or more languages or change the position. If you leave just one language, the change language listbox will not be displayed.
// the list of languages available is: 'english', 'italian', 'german', 'dutch', 'spanish', 'french', 'portuguese', 'croatian', 'polish', 'catalan', 'estonian', 'rumanian', 'hungarian','norwegian', 'slovak', 'swedish', 'russian', 'danish', 'finnish', 'czech','telugu', 'chinese','traditionalChinese'
// when the language is not set yet (for example in the login form), the language used is the first in the list
$languages_ar = array ('english','catalan','chinese','croatian','czech','danish','dutch','estonian','finnish','french','german','hungarian','italian','norwegian','polish','portuguese','rumanian','russian','slovak','spanish','swedish','telugu','traditionalChinese');

// set to 1 if you want to specify, in form configurator, different labels, hints and tooltips for different languages, the correct label will be used according to the language the user chooses
$enable_multilanguage_labels = 0;

// if $enable_multilanguage_labels is set to 1, which language should DaDaBIK use in case of a missing translation?
$default_language_missing_translation = 'english';

// error handling parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable DaDaBIK error handling (0|1). If enabled (1), PHP errors, warnings, uncaught exceptions and fatal errors are handled by DaDaBIK, according to the parameters below.
// Error details can include, among other things, file and line, URL, logged user, table, function, browser and IP address.
$error_enable_reporting = 0;

// include the latest known SQL query in error notifications/logs, when available (0|1).
// The SQL query can also include values submitted by users or other application data, including sensitive data.
$error_include_sql_query = 1;

// send an email notification when DaDaBIK handles an error (0|1)
// if you are using a $custom_mail_function (see later, email parameters), your custom email function must support the from parameter and must be defined in config_custom.php, instead of custom_functions.php (this ensures the function is available even when an error occurs early during execution)
$error_notify_email = 0;

// email address that receives error notifications. If $error_notify_email = 1, this parameter must be set.
$error_email_to = '';

// sender email address used for error notifications.
$error_email_from = '';

// show a generic error message to the user when DaDaBIK handles an error (0|1).
// if you set this parameter to 0, DaDaBIK will show a blank screen
// if $debug_mode is set to 1, all the error details will be displayed instead and this parameter has no effect
$error_show_generic_message = 1;

// generic error message shown to the user when $error_show_generic_message = 1
$error_generic_message = 'Sorry, something went wrong.';

// PHP error reporting level used by DaDaBIK error handling. Use 'E_ALL' to handle all reportable PHP errors, warnings, notices and deprecations, or any valid PHP error reporting expression supported by DaDaBIK.
$error_reporting_config = E_ALL;

// write DaDaBIK-handled errors also to the PHP error log using error_log() (0|1). The actual destination depends on your PHP/Web server configuration.
$error_log_to_php_error_log = 1;

// write DaDaBIK-handled errors to a text file (0|1). This is independent from the PHP error log.
$error_log_to_file = 0;

// full path of the text file used to log DaDaBIK-handled errors.
// for security reasons, use a file outside the Web root; the web server user must have write permission on this file/path.
$error_log_file = '';

// write DaDaBIK-handled errors to the DaDaBIK errors database table (0|1).
// please note that database connection errors cannot be logged to the database.
// this features is only available for DaDaBIK Platinum
$error_log_to_db = 0;

// minimum number of seconds between two email notifications for the same or similar error. Set to 0 to disable throttling.
$error_email_min_interval_seconds = 120;

// full path of the file used to store email throttling data. This is not an error log file: it only stores timestamps and error fingerprints.
// The default file error_email_throttle.json is included in DaDaBIK; if you set a different path, create/copy the file yourself and make sure PHP has write permission on it.
// For better security, you can use a file outside the Web root.
$error_email_throttle_file = 'error_logs/error_email_throttle.json';

// authentication and security parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// If you use Two-Factor authentication, set the name of your app as you want to be displayed in the Authenticator app
$two_factor_auth_app_name = '';

// If you use Two-Factor authentication, TOTP secrets are stored in the database, encrypted. Set an encryption key.
// The easiest way to create a solid encryption key is to run locally the script gen_encryption_key.php
$encryption_key = '';

// If Two-Factor Authentication (2FA) is enabled, this setting determines whether to display the secret key as text in addition to the QR code during the user's first login.
// By default, this is set to 0 (disabled), meaning only the QR code is shown, which is more secure.
// However, in certain scenarios (e.g., when scanning the QR code is not possible), enabling this option (set to 1) allows users to view and manually input the secret key in the Authentication App.
$show_2fa_secret_code_text = 0;

// enable user password modification (0|1). If enabled (1) , users can change their own password.
$enable_user_password_modification = 1;

// enable user self-registration (0|1). If enabled (1), users can create their own account, otherwise only administrators can create new users
$enable_users_self_registration = 0;

// enable by default 2fa after self-registration (0|1)
$defult_2fa_self_registration = 0;

// default group for self registration. If $enable_users_self_registration = 1, here you can set the id of the group the users belong, by default, when they register. When DaDaBIK is installed, there are only two groups: admin (having ID 1) and default (having ID 2)
$default_group_user_self_registration = 2;

// if 1 (default), the self registration form contains first and last name, otherwise (0) only username, email and password
$show_first_last_name_registration_form = 1;

// if 0 (default), no checkboxes will be shown in the registation form
// set to 1, 2 or 3 to show 1, 2 or 3 checkboxes (e.g. "Accept terms", "Accept privacy policy", "Register to our newsletter" and similar)
// the label of the checkbox can be changed in your custom language file (set $login_messages_ar['registration_form_checkbox_1'], $login_messages_ar['registration_form_checkbox_2'], $login_messages_ar['registration_form_checkbox_3']), also including an href link (for example to your terms and conditions page)
$show_accept_terms_privacy_checkbox_registration_form = 0;

// is checking each of the above checkboxes required for registration? 1 = required, 0 = not required
// if you don't show checkboxes, this parameter does not have effect
$accept_terms_privacy_checkbox_registration_form_required = [1,1,0];


// If enabled (1), users can request a new, temporary, password that DaDaBIK sends via email. Please note that this random generated password doesn't need to meet the requirements you set if you enable password validation (see $enable_password_validation later), at the moment it's a 16-chars string composed by letters and numbers and it expires after five minutes.
$enable_forgotten_password = 0;

// If enabled (1), users after self registration need to confirm the registration by clicking a link sent via email. Users registered by admins (Admin -> users), users coming from LDAP or Google Authentication authentication and deafult users created by DaDaBIK during the installation are automatically confirmed. This parameter has been introduced in V. 11.1, if you upgrade your DaDaBIK installation from a version < 11.1, all existing users are confirmed.
$enable_email_confirmation = 1;

// number of times a user can type the wrong password before the account is blocked
// the failed login counter, before reaching maximum allowed number, is reset to 0 when the user correctly access the application using the right password (even from another IP/computer)
// If a user access a DaDaBIK application through Wordpress authentication, this limit has no effect
// An admin user can re-enable a blocked user from the "edit" page of the user
$number_failed_login_before_blocking = 10;

// enable the Wordpress authentication (0|1). If DaDaBIK is integrated into a Wordpress website and $wordpress_authentication is 1, users authenticated through Wordpress are automatically authenticated into DaDaBIK too, without doing the log-in again. The Wordpress authentication feature requires that the DaDABIK application is installed in a subdirectory of the Wordpress site and that the same users (same username) is available both in Wordpress and DaDaBIK.
// please note that if $wordpress_authentication = 1, two users having the same username (one on Wordpress and one on DaDaBIK) can coexist and both access DaDaBIK as they were the same user, even if they have two different passwords
// please note that the Wordpress and DaDaBIK session expirations are not synchronized: if your session expires in Wordpress it doesn't mean it automatically expires in DaDaBIK. Use the logout button in Wordpress to be sure to disconnect to both Wordpress and DaDaBIK.
// If you enable the Wordpress authentication, you should login/logout through Wordpress without using DaDaBIK login/lougout; mixing Wordpress and DaDaBIK login/logout can lead to unexpected behaviours.
$wordpress_authentication = 0;

// enable granular permissions (0|1). Granular permissions allow you to set, for each users group, which operations (read, delete, update, create, details, search and quick search) are allowed on each form and field.
$enable_granular_permissions = 1;





// enable multiple groups permissions (0|1). Each user belongs to a (main) group and to other, additional, groups. If $enable_multiple_groups_permissions = 1, permissions will be computed considering the additional groups too, based on the "most permissive" criteria
$enable_multiple_groups_permissions = 1;



// enable password validation (0|1). Set it to 1 if you want to validate passwords according to rules, for example you want a password to contain at least one special character. If 1, when a new user is created or a user's password is changed through DaDaBIK, the password is validated using a validate_password($password) function that you must write in custom_functions.php; the function must return true or false. You also have to specify the error message in your language file (see /include/languages): add a sentence to the $normal_messages_ar array having key = 'password_not_valid'
$enable_password_validation = 0;

// always refresh permissions (0|1). DaDaBIK by default recomputes the user permissions at each page load; we recommend to keep this default setting (1); if you set this parameter to 0, DaDaBIK computes the user permissions only when the user logs-in, this means that even if you (admin) edit permissions, the user will keep old permissions setting during all his/her session. If you have a lot of tables and/or fields and/or user groups, setting this value to 0 can speed up the page load. If $enable_granular_permissions is 0, $always_refresh_permissions has no effect.
$always_refresh_permissions = 1;

// leave FALSE for better security in password storage, change to TRUE for maximum application portability on old systems (see http://www.openwall.com/phpass/ for further details)
// starting from DaDaBIK 12.7, this parameter is no longer used for generating new password, it is only used to check old passwords.
$generate_portable_password_hash = FALSE;

// Grant permissions after tables installation (0|1|2), default is 2
// When you install a new table or add a new field to your DaDaBIK applications:
// 0: All the related permissions are set to NO
// 1: All the related permissions are set to YES
// 2: All the related permissions are set to YES, but only for the administrator user group.
// If you are not using granular permissions ($enable_granular_permissions = 0), options 2 produces the same effect as option 1.
$grant_permissions_after_table_installation = 2;

// grant permissions to autoincrement fields after table installations. By default, DaDaBIK treats autoincremnt fields as normal fields, set to 0 if you don't want to grant permissions to autoincremnt fields. This is often useful because normally you don't want to see "ID" fields in your forms, especially in the insert forms (you want the value to be automatically generated and assigned). Of course you can always remove the permissions (and hide a field from a form) later.
// this parameter is currently considered only with MySQL, with other DBMSs the autoincremnt fields are always treated as normal fields.
$grant_permissions_autoincrement_after_table_installation = 0;



// Permissions Templates
// write here your pemissions templates, if any: a set of granular permissions you want to grant, automatically, when you add a new table or a new field to your application.
// e.g. $permissions_template['table_default'][2] = '101111'; // for group 2, by default set all the table permissions to YES except DELETE
// check the documentation (chapter "Permissions Templates") for all the details





// Information about the user and group tables
/*
You should leave the following 10 parameters as they are, unless you want to use your own custom users and groups tables.

If you change $users_table_name and/or $groups_table_name before installing, DaDaBIK assumes the users and/or groups tables you specified already exists and it won't create them during the installation.

If you use your own users table, consider that the password field $users_table_password_field must contain the hash of the password computed using the password_hash() PHP function and at the moment the field needs to have a length of at least 60 characters, but you can use your own hashing function customizing check_password_hash() and create_password_hash() in /include/general_functions.php (when you upgrade DaDaBIK, however, general_functions.php will be overwritten so you'll need to edit it again). If you change the create_password_hash() function, this also affects the hashes of the tokens (used by HTTP APIs); please note that at the moment those hashes can be maximum 100 characters long.

Also consider that DaDaBIK WON'T WORK properly if you use both LDAP authentication or Google authentication and your own custom users/groups table, if you decide to use your own custom users/groups table, $enable_ldap_authentication and $enable_google_authentication must be 0.
The custom users and groups table is also not compatible with two-factor authentication (enable_2fa must be set to 0).

*/
// the groups table is used just if $enable_granular_permissions = 1
$users_table_name = $prefix_internal_table.'users';
$users_table_id_field = 'id_user'; // must be an integer field and PK
$users_table_id_group_field = 'id_group'; // must be an integer field
$users_table_ids_group_others_field = 'ids_group_others'; // must be a TEXT field (if $enable_multiple_groups = 0, you can leave it empty)
$users_table_username_field = 'username_user'; // must be a char/varchar field
$users_table_password_field = 'password_user'; // must be a char/varchar field
$users_table_authentication_type_field = 'authentication_type_user'; // must be a char/varchar field, length 255, and must always contain the string "dadabik" as value

$groups_table_name = $prefix_internal_table.'groups';
$groups_table_name_field = 'name_group'; // must be an char/varchar field
$groups_table_id_field = 'id_group'; // must be an integer field and PK
$id_admin_group = 1; // the id of the group which is the administrators group

/*
In addition to the fields you see above, the follwing fields must also be in your custom users table
temporary_password_user VARCHAR(255) NULL
temporary_password_timestamp_user INT NULL
confirmed_timestamp_user INT NULL
id_confirmation_user VARCHAR(100) NULL UNIQUE
id_confirmation_timestamp_user INT NULL
force_password_change_user VARCHAR(3) NOT NULL DEFAULT 'no'
enable_2fa_user VARCHAR(3)  NOT NULL DEFAULT 'no'
Their value is not important and won't be used, just add the fields.
*/

// Enable the use of custom PHP pages in a DaDaBIK application (0|1)
$enable_custom_php_pages = 0;

// Enable the use of custom button functions in a DaDaBIK application (0|1)
$enable_custom_button_functions = 0;


// enable the use of custom display / required functions for results grid (if 0, it only works for the insert/edit form, if 1 you have to rewrite your custom display / required functions accordingly, see documentation)
$enable_custom_display_results_grid = 0;



// Use the id_group of the user, instead of the username, to fill ID_user fields (0|1)
// this also affects how the owner permissions work (id_group instead of username will be used to control read, delete, update)
// please note that if you change this parameter for a running application that uses one or more ID_user field types, you will end up in an inconsistent situation where some records have the username of the user as owner and some others the id_group
$use_id_group_for_ownership = 0;

/* Set here the list of tables/views you don't want to show in the left menu
for example $menu_hidden_tables = ['customers','products'];
to exclude the "customers" and "products" table
This is a parameter you will rarely need, in fact you can control the permissions for a table from the DEV Area (Edit This App -> Permissions) and if you disable read permissions for a table, the table will not be displayed in the let menu. However, you may need to handle corner cases where the read permission is enabled but you don't want to see the table listed in the menu, these are the only cases where you need to set $menu_hidden_tables  */
$menu_hidden_tables = [];

// Enable create view (0|1): set to 1 to enable database views creation from DaDaBIK (admin interface -> Pages). Please note that when $enable_crate_view is set to 1, from admin -> Pages it is possible to create views incluing all the data the database user set for this application has privileges on, even data coming from other databases than the one that is set in $db_name.
$enable_create_view = 0;

// By default, the use of semicolon characters is not allowed for the SQL query you use to create a VIEW in the admin -> pages section. It's a security measure, if an attacker gets access to the admin section, the semicolon can be exploited to add malicious queries to the "create view" query (e.g. insert, delete, drop queries on all the data the database user set for this application has privileges on).
// You can enable semicolons by setting this parameter to 1
$enable_semicolon_create_view = 0;

// By default, the use of semicolon characters is not allowed for the custom SQL query you use to create a report. It's a security measure, the semicolon can be exploited to add malicious queries to the "SELECT" query (e.g. insert, delete, drop queries on all the data the database user set for this application has privileges on).
// You can enable semicolons by setting this parameter to 1
$enable_semicolon_sql_report = 0;

// show the box containing the logout, edit your account and admin link (right top corner) (0|1)
// normally you want to set to 0 only if your DaDaBIK application is embedded in a Wordpress application and you enabled Wordpress authentication
$show_logout_account_admin_box = 1;

// for MySQL, you can disable the execution of multiple SQL statements coming from the same query execution. (0|1)
// This is useful to increase security in case of SQL injection bugs. This parameter makes use of MYSQL_ATTR_MULTI_STATEMENTS, a constant that exists as of PHP 5.5.21 and PHP 5.6.5; if you are running PHP 5.5 < 5.5.21 or PHP 5.6 < PHP 5.6.5 this parameter doesn't have effect. It also does not have effect on PostgreSQL, SQLite, SQL Server.
// If you set to 1, remember, when you write your own custom code, that you cannot execute a query containing multiple SQL statements
$disable_mysql_multiple_statements = 1;

// additional SQL security check (0|1). Check, for some of the SELECT queries DaDaBIK executes, for unexpected strings such as ; DELETE INSERT UPDATE and others, strings that could represent an SQL injection attempt (0|1); You are strongly encouraged to leave this option set to 1, disable it only if you really know what you are doing.
$sql_select_security_check = 1;

// form configurator security check (0|1). For the parameters in form configurator involved in queries execution, such as "Lookup table name" or "Lookup table primary key field", DaDaBIK checks if they contain proper table and field names and if not, it throws an error. This is useful if for some reason their content has been maliciously modified to try an SQL injection attack. You are strongly encouraged to leave this option set to 1, disable it only if you really know what you are doing.
// you might get error even if in form configurator you are referring to a table (for example as an items table or as a linked table) or a field that is no longer available (you dropped it, you uninstalled it or you disabled it).
$form_config_security_check = 1;

// when enabled, before insert and update operations, for select_single, select_single_radio, select_multiple and select_multple_checkboxes fields DaDaBIK checks if the value proposed belongs to the set of options available.
// the check is needed because a malicious user / attacker could try to add a value that is not available in the menu
// the first parameter impact on the standard insert/update forms, the second one on insert/update operations performed using Excel/CSV import
$dropdown_security_form_check = 1;
$dropdown_security_csv_check = 1;

// enable Data Tab (admin section) operations (0|1). The data tab in the admin section allows admins to drop or alter any table in the database used by the DaDaBIK application or to add new tables. For security reason, by default, this option is set to 0, set it to 1 if you want to use the data tab.
// Please note that under particular circumstances this section allows malicious admins to execute arbitrary SQL code on your database or in other databases the DaDaBIK database user ($user in config) can access. Enable Data Tab only if you trust your admin users and if you are using a DaDaBIK database user who doesn't have privileges on other databases.
$enable_data_tab_operations = 0;

// before an import from Excel/CSV/ODS, DaDaBIK checks, if you are using MySQL, that the table uses the InnoDB engine. Using the MyISAM engine could lead to problems (e.g. in case of errors in the middle of an import, despite the message "No element has been inserted.", some rows might be inserted)
$check_innodb_engine = 1;

// until v13.4, the Custom Code API method count_records() returned a string, starting from v13.5 it returns an integer. Set this parameter to 1 if, for backward compatibility reasons, you prefer the old behaviour. 
$api_method_count_records_return_string = 0;




// Google Authentication parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable Google authentication (0|1); this feature allows users to self-register by signing in through Google. Once authenticated, DaDaBIK checks if the user's email address matches an existing user. If not, a new user is automatically created using basic information retrieved from the provider (email). After registration, the user becomes a regular DaDaBIK user, like any other. They will be able to log in to the DaDaBIK application through Google from that point onward.

// This feature is currently in beta. Do not use it in production environments.

// Please also note that DaDaBIK WON'T WORK properly if you use both google authentication and your own custom users/groups table (not the default one), if you decide to use your own custom users/groups table, $enable_google_authentication must be 0

// Please read the manual (Google Authentication chapter) for additional details.

$enable_google_authentication = 0;

// Google default group: after registration, a user is created into the dadabik users table, here you can choose in which group you want to add them by default
$google_default_id_group = '2';

// before enabling this feature, you must create the credentials for your applicaiton from the google cloud console (https://console.cloud.google.com/apis/credentials):
// Create Credentials > OAuth Client ID
// Application Type: Web application
// Authorized redirect URIs > Add URI (the URI to add is http://your-app-url/login.php?function=google_callback replace your-app-url with the base URL of your DaDaBIK app)
// use the info provided by google to fill the following parameters in your config_custom.php file
$google_client_id = '';
$google_client_secret = '';
$googole_redirect_uri = '';




// LDAP parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable LDAP authentication (0|1), if 1, an "LDAP authentication" checkbox will be displayed below the log-in form; you can avoid setting the other LDAP parameters if LDAP authentication is disabled; please note that in a DaDaBIK application usernames must be unique, you can't have two users having the same username, even if one is a regular DaDaBK user and the other an LDAP user; please also note that DaDaBIK WON'T WORK properly if you use both LDAP authentication and your own custom users/groups table (not the default one), if you decide to use your own custom users/groups table, $enable_ldap_authentication must be 0
$enable_ldap_authentication = 0;

// if set_ldap_authentication_as_default is 1 (and ldap authentation is enabled), the "LDAP authentication" checkbox is checked by default
$set_ldap_authentication_as_default = 0;

// LDAP host URI, e.g. 'ldap://ldap.yourdomain.net' or 'ldaps://ldap.yourdomain.net'
$ldap_host = '';

// LDAP port, 389 is the default one
$ldap_port = '389';

// $ldap_binding_type can be 'classic' (default option) or 'ad' (which stands for Active Directory).
// 'classic' means that the binding will be done using the base dn specified later in $ldap_base_dn_ar, together with the $ldap_username_field and the username specified in the login form, for example something like cn=paul,ou=Users,DC=example,DC=com
// 'ad' is an alternative syntax, the binding will be done using just the username (e.g. "paul") or the username preceded by a prefix and a backslash  (e.g. "mysubtree\paul"). You can set the prefix with $ldap_binding_prexif. Please note that DaDaBIK ASSUMES that USERNAMES ARE UNIQUE
$ldap_binding_type = 'classic';

$ldap_binding_prefix = ''; // e.g. if you set this parameter to 'mysubtree\\', $ldap_binding_type to 'ad' and the user fills the username field in the login form with 'paul', the binding will be done with: mysubtree\paul (and the password specified by the user); if $ldap_binding_type is 'classic', this parameter is not cosindered. If any character contained in $ldap_binding_prefix needs to be escaped, you have to provide the string already escaped

// set these parameters if you want to use, for the initial binding/authentication, the credentials of a specific user, otherwise DaDaBIK will use the user/password pair provided in the login form
// when these parameters are set, an additional, second, bind is anyway executed to check the password of the user, according to user/password pair provided in the login form

$ldap_service_account_username = '';
$ldap_service_account_password = '';

// enable the use of ldap_escape for DN (username of the user when used for ldap_bind, attribute values in $ldap_base_dn_ar )
// the details of the escaped function used are in ldap_apply_escape_if_enabled_dn() (file /include/general_functions.php)
// if $enable_ldap_escape_dn is 0, you should provide values correctly escaped
$enable_ldap_escape_dn = 1;

// enable the use of ldap_escape for filter (username of the user when used as a filter for ldap_search)
// the details of the escaped function used are in ldap_apply_escape_if_enabled_filter() (file /include/general_functions.php)
// if $enable_ldap_escape_filter is 0, you should provide values correctly escaped
$enable_ldap_escape_filter = 1;


// LDAP base dn, e.g. if your base dn is 'ou=Users,dc=yourdomain,dc=net', your settings for this parameter must be

// $ldap_base_dn_ar[0]['attribute_name'] = 'ou';
// $ldap_base_dn_ar[0]['attribute_value'] = 'Users';
// $ldap_base_dn_ar[1]['attribute_name'] = 'dc';
// $ldap_base_dn_ar[1]['attribute_value'] = 'yourdomain';
// $ldap_base_dn_ar[2]['attribute_name'] = 'dc';
// $ldap_base_dn_ar[2]['attribute_value'] = 'net';

// add as many element as you need
// Please note that under the branch defined by base dn (and also by ldap_binding_prefix, if you use 'ad' binding type) DaDaBIK ASSUMES that USERNAMES (values of $ldap_username_field, see later) ARE UNIQUE, please also note that DaDaBIK doesn't work properly if usernames or base dn attribute values contain forward slashes ("/")

$ldap_base_dn_ar[0]['attribute_name'] = '';
$ldap_base_dn_ar[0]['attribute_value'] = '';

// LDAP default group: after LDAP authentication, the authenticated user is also inserted into the dadabik users table, here you can choose in which group you want to add them by default
$ldap_default_id_group = '2';

// LDAP attribute's name for username, e.g. 'cn' or 'uid'
$ldap_username_field = '';

// enable the local copy of LDAP users data (0|1), if 1, after LDAP authentication, not only the username but also name and email of the user, retrieved from the LDAP server, are copied into the local dadabik users list
$ldap_copy_users_data = 1;

// next three parameters are needed only if $ldap_copy_users_data = 1
// LDAP attribute's name for first name
$ldap_first_name_field = '';

// LDAP attribute's name for last name
$ldap_last_name_field = '';

// LDAP attribute's name for email
$ldap_email_field = '';

// enable LDAP debug mode: in case the login/bind process doesn't work as expected, you can set this parameter to 1 to see some debug information. Don't set it to 1 in a production application because it can reveal security-related information
$enable_ldap_debug_mode = 0;




// if enabled, you can use User entities (see "User entities" chapter in the manual). By default it's enabled.
// This feature is available on DaDaBIK Enterprise/Platinum version only.
$enable_user_entities = 1;

// barcode and qrcode generation parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// you can find the list of supported formats here: https://github.com/picqer/php-barcode-generator/tree/main?tab=readme-ov-file#accepted-barcode-types
$barcode_format = 'TYPE_CODE_128';

// choose svg or png for barcodes; if you need to produce PDFs containing barcodes, you must choose png. For qrcode PNG is always used.
$barcode_image_format = 'png';

// qrcode size (in px)
$qrcode_size = 150;

// deletion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable delete all feature (delete operations must be enabled too, from the administration interface) (0|1)
$enable_delete_all_feature = 1;

// when a record is deleted, delete also the uploaded files related to that record (0|1)
$delete_files_when_delete_record = 1;

// ask confirmation before deleting a record? (0|1); note that this works just for the standard data grid, not for template data grids
$ask_confirmation_delete = 1;

// Export app parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the export app feature (0|1), available in the DEV Area; it produces a backup of your app (files + database)
// it's available on MySQL only on Unix/Linux and uses the mysqldump program
$enable_export_app = 0;

// when you export the complete database, add --events --routines --triggers options to mysqldump command (0|1).
// if set to 0, --skip-events --skip-routines --skip-triggers will be used instead
$add_triggers_events_routines_dump = 1;

// the path to the mysqldump program, used to export the DaDaBIK DB (e.g. '/usr/local/mysql/bin/')
// if you set $enable_export_app = 1, copy the line below in your config_custom.php file, edit the path and uncomment it
//const PATH_MYSQLDUMP = '/usr/local/mysql/bin/';

// CSV / Excel parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// EXPORT
//////////////////////////////////////////////
// enable export to CSV for excel feature (0|1)
$export_to_csv_feature = 1;

// CSV separator
// If you want to directly open the CSV files with MS Excel, you have to use ; instead of ,
$csv_separator = ",";

// use sort for CSV (0|1): if 1 (default), the CSV is generated using the same sorting criteria used in the results grid; you might want to set it to 0 if you don't need a specific order and you have performances issues
$use_sort_for_csv = 1;

// CSV file creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$csv_creation_time_limt = 30;

// the name of a function (if any) to apply to labels before using them as CSV headers
// this parameter is normally empty, you might need it for example if you have labels containing "<br>", because you want to remove the "<br>" from the CSV headers
// if your labels contain double quotes ("), this is automatically escaped (\") so you don't need to do it
// after having set the name of the function here, you must write the function in /include/custom_functions.php
$csv_headers_formatting_function = ''; // the name, if any, should start with "dadabik_"


// IMPORT
//////////////////////////////////////////////

// enable import from CSV / xls / ods feature (0|1)
// the user also needs the insert permissions to execute the import procedure
$import_from_csv_feature = 1;

// after having uploaded a file to import, you have a certain amount of time to complete the import operation (by default, 120 seconds), this is a security measure
$seconds_to_complete_import = 120;

// formats allowed (csv, xlsx and ods can be enabled)
$import_csv_allowed = 1;
$import_xlsx_allowed = 1;
$import_ods_allowed = 1;

// Same as the csv_creation_time_limt parameter (see before), but for CSV / Excel / ODS imports
//$csv_import_time_limit = 30;



// IMPORT SYNCH parameters
///////////////////////////////////////////////////////////////////////////////////////////////

// enable_import_csv_synch (0|1) when enabled, if during the import of a row DaDaBIK detects the same record is already available in the table, the existent record is updated instead of adding a new one.
// the unique field of the table or the csv_check_uniqueness_fields array (if set) are used to detect duplications (see the documentation for further details)
$enable_import_csv_synch = 0;


// set here the number of seconds DaDaBIK has to wait before attempting to delete the uploaded CSV/XLS/ODS files (import)
// by default, this is set to 0 and it is the recommended value, you may try a different value if deletion generates errors
$seconds_before_delete_imported_csv_file = 0;


// HTTP API parameters (HTTP API are available on DaDaBIK Platinum edition only).
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable http API (0|1).
$enable_http_api = 0;

// limit the access through the HTTP API to these users only
// if you leave this array empty, the access through the HTTP API will be available to ANY user
// for security reason, it is better to limit the access to a few users and to exclude admin users
// e.g. if you want to limit the HTTP API to the users having id 5 and 10: $enable_http_api_user_ids = array(5,10);
// you can see the id of a user from your db (id_user field in dadabik_users table)
$enable_http_api_user_ids = array();

// lifetime (in seconds) of an access token
// please note that tokens generated for iCal synch never expire
$seconds_token_expiration = 86400;


// JSON answer creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$json_creation_time_limt = 30;

// PDF parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable export to PDF feature (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$export_to_pdf_feature = 1;

// PDF file creation time limit (in seconds), in order to use it uncomment the line below by removing // and set the number of seconds. This feature makes use of the set_time_limit() function and has no effect when PHP is running in safe mode
//$pdf_creation_time_limt = 30;

// PDF max number of records exported: uncomment the line below by removing // and set the limit if you need it. Since the PDF export is a resource-consuming activity, if you have a table with a huge number of records you might want to limit the export to a specific number of records: users could launch the export on the entire table (without filtering it) and cause a system freeze
// $pdf_max_number_records = 100;

// when you produce a PDF starting from a results set, set this parameter to 1 if you want a new page for each record, otherwise set it to 0 (useful especially if you want to create a grid, where each record is a row, in your pdf). Set this paramter to 0 only if you are using a custom template.
$add_pdf_page_for_each_record = 1;

// PDF page orientation: use P for portrait, L for landscape
$pdf_page_orientation = 'P';

// set it to 1 if you want DaDaBIK to read the PDF template.php file even if $export_to_pdf_feature = 0. Why whould you need that? Because you can then override $export_to_pdf_feature in the template itself, enabling PDF export only for one or a few table.
$parse_pdf_php_template_even_if_export_pdf_disable = 0;

// use sort for PDF (0|1): if 1 (default), the PDF is generated using the same sorting criteria used in the results grid; you might want to set it to 0 if you don't need a specific order and you have performances issues
$use_sort_for_pdf = 1;

// PDF page format
$pdf_page_format = 'A4';

// assign custom PDF templates to tables
// by default, for each table/view, you can export to PDF using any of the custom templates you have in the /templates folder
// you can, however, optionally, assign one or more specific template to each table by setting the $table_pdf_templates array
// in this example we assign to the table customers the customers_template_new template
//
// example:
// $table_pdf_templates['customers']['template_names'][0] = 'customers_template_new';
//
// check the manual for additional info, including how to change the PDF button label

// null handling parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// When this parameters is set to 1 (as it is by default):
// 1) DaDaBIK treats empty form fields as NULL values during insert and update
// 2) DaDaBIK shows NULL values as empty form fields during edit
// $treat_blank_as_null = 1 is the recommended option to keep
// please note that when you import a CSV/XLS/ODS file, a blank, non-string, field is always converted to NULL, even if you set this to 0
// please note that when you insert into or edit users and groups tables, DaDaBIK works as if $treat_blank_as_null was set to 1, even if you set this to 0
$treat_blank_as_null = 1;

// the word used to display a NULL content (could also be a blank string '', it is always a blank string in edit/insert forms if $treat_blank_as_null is 1)
$null_word = '';

// if $treat_blank_as_null is 0, for each field a NULL checkbox is displayed and allows to set the NULL value during insert/edit, unless you set $null_checkbox to 0. If you set $null_checkbox to 0 and $treat_blank_as_null to 0 there is no way to insert a NULL value as a field value of a record
// note that, if the record you are editing contains a NULL value for one or more fields, the NULL checboxes are displayed anyway, even if $null_checkbox is set to 0 (unless you have set treat_blank_as_null to 1)
$null_checkbox = 0;

// Upload parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable file uploads (0|1)
$enable_uploads = 0;

// upload field type ('classic' or 'ajax')
// 'classic' is the approach used before DaDaBIK 11, files are uploaded using a standard HTML file input field and the upload starts when you submit the form; 'ajax' is a more modern approach: you can drop your file onto an upload area and the file is uploaded immediately, asynchronously, showing a progress bar
// ajax works for generic_file and image_file field types, for camera field type, the "classic" approach is always used
$upload_field_type = 'ajax';

// for "camera" field types, choose the camera to use, default is "environment" (back camera), set it to 'user' to use the front camera (not all the browsers support 'user')
$camera_capture_value = 'environment';

// $upload_directory is the absolute path (e.g. '/users/john/dadabik/uploads/') of the directory where the files you upload with DaDaBIK (for file field types) are finally stored.
// if you leave it blank, everything should work anyway: the default uploads folder will be used (it's the directory UPLOADS in the DaDaBIK root folder) and its absolute path automatically determined by DaDaBIK
// however, you might want to set this parameter anyway if you want to change the default upload folder (and you SHOULD DO THAT for SECURITY reasons, see the documentation, security chapter, for furhter details).
// if you are installing DaDaBIK on MS Windows, the $upload_directory automatic guessing may not work properly so, on Windows, you MUST set it.

// how to properly set the $upload_directory
// please put slash (/) at the end
// e.g. 'c:\\data\\web\\dadabik\\uploads\\' on windows systems
// e.g. '/home/my/path/dadabik/uploads/' on unix systems
// make sure your webserver can write in this folder and in the temporary upload folder used by PHP
$upload_directory = '';

// $upload_import_directory is the directory where the XLS/CSV/ODS files you want to import into a table are (normally, only temporarily) stored. Everything explained for the previous parameter ($upload_directory) is also valid for this one
$upload_import_directory = '';

// max allowed size for the uploaded files (in bytes)
$max_upload_file_size = 20000000;

// allowed file extensions (users will be able to upload only files having these extensions); you can add new extensions and/or delete the default ones; if you add extensions you also have to provide the correct MIME type adding an element to the array $allowed_file_exts_mime_ar, if you delete an extension you have to delete also the corresponding MIME type in the $allowed_file_exts_mime_ar array

$allowed_file_exts_ar[0] = 'jpg';
$allowed_file_exts_ar[1] = 'gif';
$allowed_file_exts_ar[2] = 'tif';
$allowed_file_exts_ar[3] = 'tiff';
$allowed_file_exts_ar[4] = 'png';
$allowed_file_exts_ar[5] = 'txt';
$allowed_file_exts_ar[6] = 'rtf';
$allowed_file_exts_ar[7] = 'doc';
$allowed_file_exts_ar[8] = 'xls';
$allowed_file_exts_ar[9] = 'htm';
$allowed_file_exts_ar[10] = 'html';
$allowed_file_exts_ar[11] = 'csv';
$allowed_file_exts_ar[12] = 'pdf';
$allowed_file_exts_ar[13] = 'jpeg';
$allowed_file_exts_ar[14] = 'docx';
$allowed_file_exts_ar[15] = 'xlsx';

$allowed_file_exts_mime_ar[0] = 'image/jpg';
$allowed_file_exts_mime_ar[1] = 'image/gif';
$allowed_file_exts_mime_ar[2] = 'image/tiff';
$allowed_file_exts_mime_ar[3] = 'image/tiff';
$allowed_file_exts_mime_ar[4] = 'image/png';
$allowed_file_exts_mime_ar[5] = 'text/plain';
$allowed_file_exts_mime_ar[6] = 'application/rtf';
$allowed_file_exts_mime_ar[7] = 'application/msword';
$allowed_file_exts_mime_ar[8] = 'application/vnd.ms-excel';
$allowed_file_exts_mime_ar[9] = 'text/html';
$allowed_file_exts_mime_ar[10] = 'text/html';
$allowed_file_exts_mime_ar[11] = 'text/plain';
$allowed_file_exts_mime_ar[12] = 'application/pdf';
$allowed_file_exts_mime_ar[13] = 'image/jpeg';
$allowed_file_exts_mime_ar[14] = 'application/msword';
$allowed_file_exts_mime_ar[15] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';


$allowed_all_files = 0; // set to 1 if you want to allow all extensions, and files without extension too; please note that, since you cannot provide the MIME type for all possibile files, file having extensions different than the ones specifiy in $allowed_file_exts_ar are not guaranteed to be handled as expected

// for generic files, when you click on the link to open the file itself, the file is open in a new window (_blank); set this parameter to 0 if you want to open it in the same window (_self)
$open_blank_generic_file = 1;

// This parameter, $upload_relative_url, is only used if $file_access_mode is set to 'public_url'
// if you change it, you must change $upload_directory accordingly 
// relative URL from the DaDaBIK root dir to the upload folder; you can leave the default one, remember to put a slash (/) at the end if you change it
$upload_relative_url = 'uploads/';

// show uploaded images in the edit form (0 | 1); if 0, only the link to the image is displayed
$show_images_in_edit_form = 1;

// Uploded pictures, thumbnail parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

/*
If you don't want to use thumbnails, you can leave 0 for all the four parameters and the browser will display the uploaded images at their original size.
If you set a max width or height, the browser will resize the picture according to that. You can also force just width or just height, the browser will always scale the other dimension proportionally.
*/

// maximum width (in px) of the pictures displayed in the results grid
$picture_thumbnail_results_grid_max_width = 0;

// maximum height (in px) of the pictures displayed in the results grid
$picture_thumbnail_results_grid_max_height = 0;

// maximum width (in px) of the pictures displayed in the details page
$picture_thumbnail_details_max_width = 0;

// maximum height (in px) of the pictures displayed in the details page
$picture_thumbnail_details_max_height = 0;

// Duplication check parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// maximum number of records to be displayed as duplicated during insert
$number_duplicated_records = 30;

// select similarity percentage used by duplication insert check
$percentage_similarity = 80;

// enable check similarity also during xls/csv/ods import (0|1) and not only during normal insert operationss
$check_similarity_during_import = 1;

// debug parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// display the main sql statements of insert/search/edit/detail operations for debugging (0|1)
// note that the insert sql statement is will be displayed only if $insert_again_after_insert is set to 1 and $show_details_after_insert = 0
$display_sql = 0;

// display all the sql statements and the MySQL error messages in case of DB error for debugging (0|1)
// if $error_enable_reporting is set to 1 (see later), additional error context will also be displayed
$debug_mode = 0;

// display the "I think that x is similar to y......" statements during duplication check (0|1)
$display_is_similar = 0;

// search parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the quick search feature, which provides a quick search row at the top of the results
$enable_quick_search_feature = 1;

// allow the "and/or" choice directly in the form during the search (0|1)
$select_operator_feature = 1;

// default operator (or/and), if the previous is set to 0
$default_operator = 'and';

// enable the possibility, for users, to specify AND / OR operators during a search e.g. typing "apple OR banana" (0|1)
// operators are case sensitive, OR and AND must be written in capital letters
// users cannot mix operators (city AND Europe OR USA)
$enable_user_booleans = 0;

// default order type: results grid, default order type (ascendant or descendant) to use ('ASC'|'DESC')
$default_order_type = 'ASC';

// users can search and specify WHERE filters not only using the search forms but also passing values through URLs (show search url button ) (0 | 1)
$enable_search_by_url = 1;

// At the top of each results grid there is a quick search bar that users can hide by clicking on "Hide/Show quick filters". Set this parameter to 1 (default is 0) if you want to hide it by default.
$hide_quick_search = 0;

// the advanced search button by default is displayed only if at least one field has been allowed for the advanced search form
// if you set this parameter to 1, the advanced search is always showed
// the only reason why you may want to set it to 1 is to load the results grid slightly faster
$always_show_advanced_search_button = 0;

// If Quick Search is disabled for all fields in a table, the OmniSearch form (a single-field, search-all textbox) will appear instead, allowing you to search across all compatible fields, using the "contains" operator.
$enable_omnisearch_feature = 1;

// OmniSearch can use, when performing the search, all the fields the user sees in the results grid ('grid', default option), all the fields the user sees in the advanced search form ('advanced_search') or all the fields the user sees in the details page ('details')
$omnisearch_fields_to_include = 'grid';

// by default, OmniSearch uses, when performing the search, the fields having the following field types: 'text','textarea','rich_editor','select_single','select_single_radio','select_multiple_menu','select_multiple_checkbox','id_user', 'generic_file','image_file','camera','date','date_time','insert_date_time','update_date_time', if combined with the following content type 'alphabetic','alphanumeric','url','email','phone','html'. Fields having content type 'numeric' are only used for select_single and select_single_radio lookup fields.  You can choose to exclude date and file fields by setting the following two parameters to 0.
// if you use SQLite, please note that omnisearch currently does not support the date_format "literal_english"
$include_date_fields_omnisearch = 1;
$include_file_fields_omnisearch = 1;

// since V 12.0, this parameter by default is set to 0. This means that DaDaBIK uses, to count the number of results of a quick or advanced search, a simple SELECT COUNT(*) for the first part of the query. If set to 1, DaDaBIK uses the same query used to show the results, i.e. also joining other tables (if you have lookup fields). We recommend to keep this parameter set to 0 unless you are experiencing issues.
$use_long_select_query_for_count = 0;

// cascade drop-down menus, by default, only work in insert and edit forms. If you set this parameter to 1, they will work in the advanced search form as well. They never work in quick search forms.
$enable_cascade_advanced_search_form = 0;

// show a "set default search filter" button in case, for the current table, a prepopulated search fields callback function is set
$show_set_default_filter_button = 1;

// data grid and details page parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// items of the menu used to choose the number of result records displayed per page, you can add new numbers and/or delete the default ones, don't delete $records_per_page_ar[0]
$records_per_page_ar[0] = 10;
$records_per_page_ar[1] = 20;
$records_per_page_ar[2] = 50;
$records_per_page_ar[3] = 100;

// target window for details/edit, 'self' is the same window, 'blank' a new window; this works just for the standard data grid, not for template data grids
$edit_target_window = 'self';

// column at which a text, textarea and select_single field will be wrapped in the results page, this value also determines the width of the column in the results table (standard data grid) if $word_wrap_fix_width is 1
$word_wrap_col = '25';

// allow that $word_wrap_col value also determines the width of the column in the results table (standard data grid) (0|1)
$word_wrap_fix_width = 0;

// The results grid, for each row, shows edit, delete and details icons. By default, you can use Font Awesome and boxicons icons; if instead you want to use images (e.g. a custom png file) set this parameter to 'images'  ('font_icons','images')
$edit_delete_details_icons_type = 'font_icons';

// Font icons or image files to use as icons for delete, edit, details, file
// As Font icons, Boxicons and Font Awesome 4/5 are supported; if instead, you chose 'images' for $edit_delete_details_icons_type you must speficy here the files using their absolute or relative path (e.g. 'images/delete.png')
$delete_icon = 'bx bx-trash-alt';
$edit_icon = 'bx bx-edit';
$details_icon = 'bx bx-info-circle';
$file_icon = 'fas fa-paperclip fa-lg';

// colors of the icons (only if you are using font icons)
/* delete, edit and details icons color is now set from admin > layout > basic settings
$delete_icon_color = '#eb1515';
$edit_icon_color = '#5067c1';
$details_icon_color = '#4f84f3';
*/
$file_icon_color = 'gray';

// enable results table row highlighting (0|1)
$enable_row_highlighting = 1;

// enable the possibility to select, using a checkbox, several records in the results grid, for example to delete several records at once
$enable_record_checkboxes = 1;

// separator to use while displaying several lookup table linked field values related to the same field (in data grid and details page)
// e.g. Let's say if you have an id_customer select_single field having as linked lookup table linked fields first_name_customer and last_name_customer, the value ' - ' for $separator_display_linked_field will make an id_customer field value to be displayed as, e.g. "john - smith"
$separator_display_linked_field = ' '; // if you need more than one subsequent blank space, use the &nbsp; html entity for each one

// same as above, but for edit/search/quick search fields
$separator_display_linked_field_2 = ' ';

// separator to use while displaying several values related to the same select_multiple_* field (in data grid and details page)
$separator_display_select_multiple = '<br/>';

// foor lookup field, you can choose in form configurator if you want to show a link to the related linked record; the parameter $function_link_to_record allows to choose which page you want to open when the link is clicked ('details' or 'edit')
$function_link_to_record = 'details';

// enable live edit (0|1) Set to 1 if you want to edit records directly from the results grid, without opening the records in edit mode. Before enabling it, please read the "Live edit" chapter in the documentation
// if you want to enable the live edit for specific tables/fields only, you can specify it by using the $allowed_fields_live_edit parameter; this parameter also allows you to override some live edit limitations (e.g. live edits are normally not allowed if you have calculated fields in your table); read the documentation for all the details.
$enable_live_edit = 1;

// (0|1) if live edit is enabled, a "double click to edit" message is displayed when the mouse is over a text, textarea, date or date_time field cell
$show_double_click_message = 1;

// show the hide/show quick filters link
$show_hide_show_quick_filters_link = 1;

// conditional rules (Show ONLY if and conditional functions) always apply to edit and insert forms
// if this parameter is set to 1 (as it is by default) also apply to the details page, PDFs generated with the standard template and list view
$use_conditional_rules_for_details_pdf = 1;

// for fields having type TEXT and content NUMERIC, in the results grid, align right (instead of left) their value
$align_right_numeric_values = 1;

// in Dev Area > Layout > Record List Layout you can set a Datagrid Heading (before and after the title)
// leave this parameter to 1 if you always want to show the after title heading, set it to 0 if you want to hide it when the display mode is "list" (display mode tipically used with mobile devices)
$always_show_datagrid_heading_after_title = 1;


// report parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable chart report generation (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$enable_report_generation = 1;

// the color used for the bins of barcharts
// you can also set multiple colors, adding $barchart_bin_colors[1], $barchart_bin_colors[2], ...
$barchart_bin_colors[0] = '#365e96';

// enable pivot/summary table generation (0|1). This feature is available on DaDaBIK Enterprise/Platinum version only.
$enable_pivot_generation = 1;

// max number of rows as a result of a pivot table generation
$max_number_rows_pivot = 100;


// Enable advanced SQL reports (0|1); if you enable advanced SQL reports, users will be able to define
// the report using a custom SQL SELECT query.
//
// The SQL query is subject to a basic sanity check based on keyword matching, aimed at preventing
// the execution of dangerous SQL instructions and the explicit reference to particularly sensitive
// fields (e.g. password_user and some authentication or security-related fields).
//
// Please note that this sanity check is not based on a full SQL parser and therefore cannot guarantee
// that every possible unsafe query will be detected.
//
// As with any feature allowing custom SQL, users may still gain access to data outside the permissions
// defined in DaDaBIK -> DEV Area, including sensitive tables such as dadabik_users, or even to other
// databases, if the database user used by this DaDaBIK application ($user, see the beginning of the file)
// has the required privileges.
//
// Enabling this feature for a public user can be particularly dangerous
// (an unauthenticated user might have access to sensitive data).
$enable_advanced_sql_report = 0;

// Disable the SQL sanity check for advanced SQL reports (0|1) (see previous comment for details)
// This option is provided only for backward compatibility with existing installations
// relying on unrestricted SQL queries. Disabling the sanity check is strongly discouraged.
$disable_advanced_sql_report_sanity_check = 0;

// limit the use of advanced SQL reports to these users only
// if you leave this array empty, the access to DaDaBrain will be available to ANY user (if $enable_advanced_sql_report is 1)
// e.g. if you want to limit the feature to the users having id 5 and 10: $enable_advanced_sql_report_user_ids = array(5,10);
// you can see the id of a user from your db (id_user field in dadabik_users table)
$enable_advanced_sql_report_user_ids = array();

// The list of operators you can use to aggregate data when you generate a report; if you don't need one or more operator, simply comment the line adding // at the beginning of the row
$group_by_operators[0]='percentage';
$group_by_operators[1]='count';
$group_by_operators[2]='sum';
$group_by_operators[3]='average';
$group_by_operators[4]='min';
$group_by_operators[5]='max';
$group_by_operators[6]='variance';
$group_by_operators[7]='standard_deviation';

// The list of DaDaBIK field types you can use for "group by" and for aggregate functions when you generate reports; if you want to disable one or more field types, simply comment the line adding // at the beginning of the row, if you want to add a field type, add a new line. Please note that select_multiple field types don't work correctly here, don't add them.
$available_report_field_types[0]='text';
$available_report_field_types[1]='select_single_radio';
$available_report_field_types[2]='select_single';
$available_report_field_types[3]='date';
$available_report_field_types[4]='date_time';
$available_report_field_types[5]='insert_date_time';
$available_report_field_types[6]='update_date_time';
$available_report_field_types[7]='ID_user';

// The list of date functions you can use when, during the production of a report, you aggreagate your data based on a date or datetime field; if you want to disable one or more functions, simply comment the line adding // at the beginning of the row, you can't add additional functions
$report_date_functions[0]='';
$report_date_functions[1]='month';
$report_date_functions[2]='year';
$report_date_functions[3]='quarter';
$report_date_functions[4]='week';
$report_date_functions[5]='dayofweek';
$report_date_functions[6]='hour';

// weekdays standard, set weeks_start_with_sunday to 1 if you want weeks to start on Sunday, otherwise weeks start on Monday
$weeks_start_on_sunday = 0;

// dates parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// format used to display date fields ('literal_english', 'latin' or 'numeric_english') in the results grid and details page
// 'literal_english': May 31, 2023
// 'latin': 31-05-2023
// 'numeric_english': 05-31-2023
// 'ISO_8601': 2023-05-31
$date_format = 'numeric_english';

// format used to display date fields in edit mode
// for a list of possible formats see https://flatpickr.js.org/formatting/
$date_format_edit = 'm-j-Y';
$date_time_format_edit = 'm-j-Y H:i:S';

// date field separator (divides day, month and year; used only with latin and numeric_english date format)
$date_separator = "-";

// years range for the date picker, if you leave it empty by default it's 20 years (-10 +10 respect to the current year), but you can set a specific year e.g. $start_year = '1960'
$start_year = '';
$end_year = '';

// the theme for the flatpickr date picker, "default" or "dark". When you choose the dark DaDaBIK Theme from Admin > Layout, the flatpickr theme is set to "dark" regardless of this setting
$date_picker_theme = 'default';

// insertion parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// choose if, in edit and insert forms, you want to see the "multiple inserts" checkbox  (0|1)
// this feature, to works, requires on the related table a DaDaBIK unique field (typically, the primary key) that is auto-increment; if the DBMS is postgresql, the DaDaBIK unique field doesn't need to be autoincrement
$show_multiple_inserts_checkbox = 1;

// choose if, after an insert, you want DaDaBIK to display again the insert form (1) or not (0)
// this feature does not work when you insert a new record for an items table (master/details view)
$insert_again_after_insert = 0;

// choose if, after an insert, you want DaDaBIK to show you a details page of the record just inserted
// this feature, to works, requires on the related table a DaDaBIK unique field (typically, the primary key) that is auto-increment; if the DBMS is postgresql, the DaDaBIK unique field doesn't need to be autoincrement
$show_details_after_insert = 0;

// allow users, for lookup fields, to add an option to the listbox without leaving the current form (insert popup page). You can enable this option only if you have $treat_blank_as_null = 1 and $null_checkbox = 0 (which is the default setting).
// unless you are using PostgreSQL as DBMS, the
$enable_lookup_insert_popup = 1;

// record locking parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the record lock feature when a user is editing a record
// please note that each user can "lock" only one record at a specific time, so if, for example, a user opens two records in two different windows, the lock will be on the second one only and the lock on the first will be lost
$enable_record_lock_feature = 1;

// number of seconds after which a record is automatic unlocked (useful to avoid a record being locked forever when, for example, a user enters the record in edit mode and then left his workstation); choose a value >= 60
$seconds_after_automatic_unlock = 240;

// forms and results grid settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// show update and search submit buttons also at the top of the forms, not just at the bottom (0|1)
$show_top_buttons = 1;

// in the forms show a BACK (to the results grid) button
$show_back_button = 1;

// in the edit form show, after SAVE and BACK buttons, a SAVE+BACK button
$show_save_plus_back_button = 1;

// in the details form, show an "edit" button
$show_edit_button_in_details = 1;

// in the edit form, show a "delete" button
$show_delete_button_in_edit = 1;

// number of items a select_multiple_menu field should show
$size_multiple_select = 3;

// show "previous" and "next" buttons in edit and details forms (0|1)
// if you have tables with millions of records and your memory is not enough, the Web server could crash trying to find the next/previous record; if this happens, you should disable $show_previous_next
$show_previous_next = 1;

// in the results grid page, show the page navigation links also at the bottom of the results grid, not just at the top (0|1)
$show_navigation_bar_bottom = 1;

// In a master/details form, by default, if the user opens the DETAILS page related to a record of the master table, the insert, delete and edit buttons are not displayed for the items table, regardless of the related permissions. Set this parameter to 0 if you want to show the buttons (of course the user must also have the permissions to do the insert, edit or delete operations).
$details_page_dont_show_insert_edit_delete_items = 1;

// the submit button label for the insert form is "Save", set this parameter to 1 to use the old label ("Insert a new item")
$use_old_insert_form_button_label = 0;

// choose if the logo must link the home of the application
$link_logo_home = 1;

// warn users if they changed data in a form and then try to leave the page without saving (0|1)
// this feature relies on the beforeunload event, it is still experimental and the behaviour may change according to the browser in use
// according to our tests:
// - it works well on Chrome and Firefox (only tested on MacOS but it should be the same on other OSs)
// - on Safari, it seems it is "cached" by the browser: it works well the first time and then the browser doesn't warn the user anymore for the following times
// - on iOS Safari, it doesn't work (the browser doesn't warn the user)
// - when a form contains a select_multiple_menu field, the warning is displayed even if the user just clicks on a textbox (without changing the data) and then leaves the form
$warn_unsaved_changes_edit_form = 0;
$warn_unsaved_changes_insert_form = 0;

// when a page is displayed by the public user, hide the header of the page (0|1)
$hide_header_for_public_user = 0;

// when a page is displayed by the public user, hide the fixed header and footer of the page for small screens (0|1)
$hide_mobile_header_footer_for_public_user = 0;

// Calendar and iCal settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////




// for calendar views, show an iCal button, allowing to download in iCal format the (filtered) list of events 
$enable_download_ical = 1;

// max number of records exported when an iCal file is generated (both for the download iCal button and for iCal URLs)
$max_records_export_ical = 1000;

// iCal synch
// for calendar views, show a "Generate iCal URL" button, allowing to generate an iCal URL that other calendar apps can subscribe
$enable_ical_url = 1;


/*
ical_timestamp_current_time (0,1 default 0)

Each event in the generated iCal file includes a DTSTAMP field, which indicates when that event was last updated. Calendar applications use
this timestamp to detect whether an event has changed and needs to be refreshed during synchronization.

By default, DaDaBIK uses the event’s actual “last updated” value from the database. This produces accurate synchronization, but if two or more updates happen within the same second, the subsequent updates might not be detected by a calendar clients (they will see the
same DTSTAMP value).

If you set $ical_timestamp_current_time = 1, DaDaBIK will instead use the current server timestamp (the moment the iCal file is generated)
as DTSTAMP for all events. This guarantees that every export has a newer DTSTAMP and that no updates are missed — but it can cause many
unnecessary updates (“false positives”), since all events will appear as modified even if they haven’t changed.

Set to 1 if your use case requires guaranteed detection of every possible update, at the cost of extra sync noise.
*/
$ical_timestamp_current_time = 0;

/* DaDaBIK uses FullCalendar (https://fullcalendar.io) to generate calendar pages.
The content of the $fullcalendar_additional_code parameter is injected inside the FullCalendar configuration
object (new FullCalendar.Calendar(calendarEl, { ... })).
Use it to add custom FullCalendar settings and code
Example: "eventDisplay: 'block'," will display all events as "block"*/
$fullcalendar_additional_code = "";

// if you use event categories, set here the colors used for the categories. If you have more than 10 categories, you should add more colors here.
$fullcalendar_category_colors = array('#3F7FBF', '#4F8F45', '#D9822B', '#C94C4C', '#6F5BAA', '#B8860B', '#3F8C8C', '#A64D79', '#3D73D9', '#5A8F3F');

// leave this empty to use the default fullcalendar text color
$fullcalendar_text_color = '';

// Calendar initial view: dayGridMonth, timeGridWeek, timeGridDay or listMonth
$fullcalendar_initial_view = 'dayGridMonth';


// menu settings
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// always show menu items; by default they are hidden if they are not at TOP level AND they (and their parents) are not currently selected) (0|1)
$always_show_menu_itmes = 0;

// enable/disable (1/0) table/view pages custom names, e.g. "group1:Customers~group2:My account".
$enable_menu_page_custom_names = 1;

// if $enable_menu_page_custom_names is enabled, specify here the group to consider for the name, if the group the currently logged user belongs to is missing
$default_group_missing_name = 1;

// in the permissions page, show set yes / set no for all combinations links (since V 12, these links are hidden by default)
$show_set_yes_no_all_combinations_links = 0;

// email notices parameters and other email parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable_insert_notice_email_sending: when a new record is inserted an e-mail containing the record details will be sent to the addresses below (0|1)
// this feature, to works, requires on the related table a DaDaBIK unique field (typically, the primary key) that is auto-increment; if the DBMS is postgresql, the DaDaBIK unique field doesn't need to be autoincrement
$enable_insert_notice_email_sending = 0;

// set here the recipient addresses of the insert notice e-mail
// Here is an example
// $insert_notice_email_to_recipients_ar[0] = 'my_account_1@my_provider.com';
// $insert_notice_email_to_recipients_ar[1] = 'my_account_2@my_provider.com';
// $insert_notice_email_cc_recipients_ar[0] = 'my_account_3@my_provider.com';
// $insert_notice_email_bcc_recipients_ar[0] = 'my_account_4@my_provider.com';

$insert_notice_email_to_recipients_ar[0] = '';

$insert_notice_email_cc_recipients_ar[0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$insert_notice_email_bcc_recipients_ar[0] = '';

// enable_update_notice_email_sending: when a record is updated an e-mail containing the new record details will be sent to the addresses below (0|1)
$enable_update_notice_email_sending = 0;

// set here the recipient addresses of the update notice e-mail
// Here is an example
// $update_notice_email_to_recipients_ar[0] = 'my_account_1@my_provider.com';
// $update_notice_email_to_recipients_ar[1] = 'my_account_2@my_provider.com';
// $update_notice_email_cc_recipients_ar[0] = 'my_account_3@my_provider.com';
// $update_notice_email_bcc_recipients_ar[0] = 'my_account_4@my_provider.com';

$update_notice_email_to_recipients_ar[0] = '';

$update_notice_email_cc_recipients_ar[0] = '';

// please note that on some PHP versions, probably only on MS Windows, the mail() function doesn't work fine: the messages are not send to the bcc addresses and the bcc addresses are shown in clear!!!
$update_notice_email_bcc_recipients_ar[0] = '';

// custom mail function. DaDaBIK, by default, sends all the email using the mail_custom() function defined in /include/general_functions.php
// if you want to use your own custom mail function (for example you want to send messages using a library such as PHPMailer), you can set the name of your function here and then define your function in /include/custom_functions.php
// the first four parameters of your custom function must be $to, $subject, $body, $header.
// $header contains the headers (if any) that DaDaBIK wants to add to the message, for example it is used to add Bcc addresses when email notices (automations) are sent
// in addition, your function may have threee additional parameters, in this order: $from_email, $from_name, $to_name: currently, $from_email is only used (and needed) by the error email notifications; $from_name, $to_name are currently not used by DaDaBIK
$custom_mail_function = '';

// other parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// Insert, Update and Delete operations are logged into a log table. This can help a data recovery procedure in case of data loss.
// Please note that operations you execute through hooks or other custom functions, unelss you use custom code API, are not logged.
$enable_sql_logging = 0;

// Successful login are logged into a log table, storing username and timestamp
// this only works if $enable_sql_logging = 1
// at the moment, only the standard logins executed through the form (no WP login, no HTTP APIs login) are logged
$enable_access_logging = 0;




// $open_url_field_type_new_window (0|1); by default, for URL field type, the link is open in the current page (target _self), set this parameter to 1 if you prefer to open a new window (_blank)
$open_url_field_type_new_window = 0;

// if you set a field as email, DaDaBIK evaluates the content of the field to check if the format is correct. Set this parameter to 1 to allow leading and trailing spaces (and other similar characters such as \n \t).
$allows_leading_trailing_spaces_email = 0;


// Revisions/Audit parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// for each table you enable revisions for, a revisions table having the same fields is created: the table contains old versions of the records. If you change the field types of the original table, you should change them manually for the revisions table as well. If, however, you set $use_text_fields_for_revisions_table = 1, all the fields of the revisions table will be created as TEXT and you can avoid to keep the field types synchronized. TEXT fields work in most of the cases, but not in ALL the cases, futhermore, if you have lookup fields, with some DBMS (e.g. postgresql) you can have problems. Change this parameter only if you know what you are doing.
// If you use sqlite, however, at the moment setting $use_text_fields_for_revisions_table = 1 is the only way to use revisions.
$use_text_fields_for_revisions_table = 0;

// Hide the "show revisions" button
$always_hide_show_revisions_button = 0;


// Beta DEV parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// ...

// use_db_name_beta


$use_db_name_beta = 0;

// the name of your beta DB
$db_name_beta = '';



// DATA section parameters
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// You should leave the settings as they are

// Settings for MySQL
// list of available data types, in case the "simple" data types option is chosen
$simple_field_types['mysql']['int'] = 'Integer number';
$simple_field_types['mysql']['decimal(12,2)'] = 'Decimal number';
$simple_field_types['mysql']['float'] = 'Float number';
$simple_field_types['mysql']['varchar(255)'] = 'Short text';
$simple_field_types['mysql']['text'] = 'Long text';
$simple_field_types['mysql']['date'] = 'Date';
$simple_field_types['mysql']['datetime'] = 'Date and time';
$simple_field_types['mysql']['time'] = 'Time';

$simple_field_types_variations['mysql']['int'] = array();
$simple_field_types_variations['mysql']['int'][] = 'int(11)';


// Settings for PostgreSQL
// list of available data types, in case the "simple" data types option is chosen
$simple_field_types['postgres']['integer'] = 'Integer number';
$simple_field_types['postgres']['numeric'] = 'Decimal number';
$simple_field_types['postgres']['real'] = 'Float number';
$simple_field_types['postgres']['character varying(255)'] = 'Short text';
$simple_field_types['postgres']['text'] = 'Long text';
$simple_field_types['postgres']['date'] = 'Date';
$simple_field_types['postgres']['timestamp without time zone'] = 'Timestamp';
$simple_field_types['postgres']['time without time zone'] = 'Time';


// Settings for MS SQL Server
// list of available data types, in case the "simple" data types option is chosen
$simple_field_types['sqlserver']['int'] = 'Integer number';
$simple_field_types['sqlserver']['decimal (12,2)'] = 'Decimal number';
$simple_field_types['sqlserver']['float'] = 'Float number';
$simple_field_types['sqlserver']['varchar (255)'] = 'Short text';
$simple_field_types['sqlserver']['varchar (max)'] = 'Long text';
$simple_field_types['sqlserver']['date'] = 'Date';
$simple_field_types['sqlserver']['datetime'] = 'Date and time';
$simple_field_types['sqlserver']['time'] = 'Time';

// settings related to the auto-configurator feature
/*
DaDaBIK, using some rules, tries to guess the field types, labels field content types and other parameters you would set for a DaDaBIK application after its installation. The guess works according to some "signals"; for example, if a database field name contains the word "email", DaDaBIK set the content type of the field as email.
You can influence the rules used by DaDaBIK changing the following parameters. You can safely leave the the settings as they are if you want to use the default rules
*/
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// enable the auto-configurator feature (0|1)
$enable_auto_configurator = 1;

// set the NOT NULL database fields as required in DaDaBIK update/insert forms (0|1)
$autoconf['set_notnull_fields_as_required'] = 1;

// set the correspondence between database field types and DaDaBIK field type/content

// settings for MySQL
// DB field types that dadabik considers as numeric
$autoconf['numeric_fields']['mysql'] = array('integer','int','smallint','tinyint','mediumint','bigint','decimal','numeric','float','double','bit','year');

// DB field types that dadabik considers as date
$autoconf['date_fields']['mysql'] = array('date');

// DB field types that dadabik considers as date_time
$autoconf['date_time_fields']['mysql'] = array('datetime','timestamp');

// DB field types that dadabik considers as textbox
$autoconf['textbox_fields']['mysql'] = array('char','varchar','binary','varbinary');

// DB field types that dadabik considers as textarea
$autoconf['textarea_fields']['mysql'] = array('blob','text');

// DB field types that dadabik considers as select_single
$autoconf['select_single_fields']['mysql'] = array('enum');

// settings for PostgreSQL
// DB field types that dadabik considers as numeric
$autoconf['numeric_fields']['postgres'] = array('bigint','int8','bigserial','serial8','bit','bit varying','varbit','double precision','integer','int','int4','float8','numeric','decimal','real','float4','smallint','int2','serial','serial4');

// DB field types that dadabik considers as date
$autoconf['date_fields']['postgres'] = array('date');

// DB field types that dadabik considers as date_time
$autoconf['date_time_fields']['postgres'] = array('timestamp','timestamp with time zone','timestamp without time zone');

// DB field types that dadabik considers as textbox
$autoconf['textbox_fields']['postgres'] = array('character varying','character','varchar','char');

// DB field types that dadabik considers as textarea
$autoconf['textarea_fields']['postgres'] = array('text');

// DB field types that dadabik considers as select_single
$autoconf['select_single_fields']['postgres'] = array();


// settings for SQLite
// DB field types that dadabik considers as numeric
$autoconf['numeric_fields']['sqlite'] = array('int','integer','tinyint','smallint','mediumint','bigint','unsigned big int','int2','int8','real','double','double precision','float','numeric','decimal');

// DB field types that dadabik considers as date
$autoconf['date_fields']['sqlite'] = array('date');

// DB field types that dadabik considers as date_time
$autoconf['date_time_fields']['sqlite'] = array('datetime');

// DB field types that dadabik considers as textbox
$autoconf['textbox_fields']['sqlite'] = array('character','varchar','varying character','nchar','native character','nvarchar');

// DB field types that dadabik considers as textarea
$autoconf['textarea_fields']['sqlite'] = array('text','clob','blob');

// DB field types that dadabik considers as select_single
$autoconf['select_single_fields']['sqlite'] = array();


// settings for sqlserver
$autoconf['numeric_fields']['sqlserver'] = array('bigint','numeric','bit','smallint','decimal','smallmoney','int','tinyint','money','real','float');

// DB field types that dadabik considers as date
$autoconf['date_fields']['sqlserver'] = array('date');

// DB field types that dadabik considers as date_time
$autoconf['date_time_fields']['sqlserver'] = array('datetime','datetime2','smalldatetime','datetimeoffset');

// DB field types that dadabik considers as textbox
$autoconf['textbox_fields']['sqlserver'] = array('char','varchar','nchar','nvarchar');

// DB field types that dadabik considers as textarea
$autoconf['textarea_fields']['sqlserver'] = array('text','ntext','binary', 'varbinary');

// DB field types that dadabik considers as select_single
$autoconf['select_single_fields']['sqlserver'] = array();


// set the correspondence between the name of the database field and the DaDaBIK field type / content

// words that DaDaBIK considers as a hint to guess the content of the field is phone
$autoconf['phone_words'] = array ('phone', 'telefono');

// words that DaDaBIK considers as a hint to guess the content of the field is email
$autoconf['email_words'] = array ('email');

// words that DaDaBIK considers as a hint to guess the content of the field is url
$autoconf['url_words'] = array ('website');

// words that DaDaBIK considers as a hint to guess the content of the field is image_file
$autoconf['image_words'] = array ('image','picture');

// words that DaDaBIK considers as a hint to guess the content of the field is generic_file (exact match)
$autoconf['file_words_exact_match'] = array ('file');

// words that DaDaBIK considers as a hint to guess the content of the field is generic_file
$autoconf['file_words'] = array ('attachment');

$autoconf['numeric_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_than/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$autoconf['date_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_than/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$autoconf['date_time_fields']['search_operators'] = 'is_equal/is_different/greater_than/less_than/greater_equal_than/less_equal_than/is_null/is_not_null/between';
$autoconf['textbox_fields']['search_operators'] = 'contains/doesnt_contain/is_equal/is_different/starts_with/ends_with/greater_than/less_than/is_null/is_not_null/is_empty/is_not_empty/between';
$autoconf['textarea_fields']['search_operators'] = 'contains/doesnt_contain/is_equal/is_different/starts_with/ends_with/greater_than/less_than/is_null/is_not_null/is_empty/is_not_empty/between';
$autoconf['select_single_fields']['search_operators'] = 'is_equal/is_different/is_null/is_not_null';


// default settings: when DaDaBIK doesn't see any particular signal to guess a field configuration, it ueses these default values

// field type
$autoconf['default']['type_field'] = 'text';

// field content type
$autoconf['default']['content_field'] = 'alphanumeric';

// Maxlength
$autoconf['default']['maxlength_field'] = '100';

// Search operators
$autoconf['default']['search_operators'] = 'is_equal/is_different/contains/doesnt_contain/starts_with/ends_with/greater_than/less_than/greater_equal_than/less_equal_than/is_null/is_not_null/is_empty/is_not_empty/between';

// Is the field required in DaDaBIK insert/edit forms? ('0'|'1')
$autoconf['default']['required_field'] = '0';

// advanced configuration settings, you can safely leave the following settings as they are
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

// THIS PARAMETER IS NO LONGER SUPPORTED, YOU MUST LEAVE IT TO 0
// some config parameters are now controlled from the DEV Area (at the moment, only the ones you find in the admin > layout tab). Set $dont_use_admin_config to 1 if you want to continue using config_custom.php to set such parameters
$dont_use_admin_config = 0;

// the name of the main file of DaDaBIK, you can safely leave this option as is unless you need to rename index.php to something else
$dadabik_main_file = 'index.php';

// the name of the login page of DaDaBIK, you can safely leave this option as is unless you need to rename login.php to something else
$dadabik_login_file = 'login.php';

// Force search before results: for each table, for performance reasons, you can force users to execute a search with one or more filters before executing the SELECT query and showing the records
// Export to CSV, PDF also won't work if there isn't a filter set, the same for JSON replies (HTTP API)
// When you display a master/details form, the details table is always displayed regardless of $force_search_before_rsults settings
// If you embed a results grid and for the related table you set this parameter to 1, the grid will be never displayed, since embedding does not consider the search filter
// here is an example if you want to force search only for the tables products and books:
// $force_search_before_rsults['products'] = 1;
// $force_search_before_rsults['books'] = 1;

// since v13.5, a new, much faster function, has been introduced to check wheather a user can do a specific action on a field. Set this to 1 if you want to use the old function.
$use_old_user_allowed_function = 0;

// if, for one or more tables, you set force_search_before_rsults to 1, here you can set an (optional) message to show to the users when the search filter is not set and therefore they don't see the records, something like "Please set one or more search parameters to see the items". The value you set here must be a key you then set in your custom languages file, e.g. if you write here 'please_set_one_search_param', DaDaBIK will look for $normal_messages_ar['please_set_one_search_param'] in your custom languages file
$force_search_before_rsults_message = '';

// function to apply to all textbox values inserted by the user, before INSERT and UPDATE occur (e.g. you may want to capitalize values)
// leave empty if no function is needed (in fact, normally is NOT needed) or fill the parameter with the function's name (the name   must start with "dadabik_") and write the related function's code in custom_functions.php. In custom_functions.php you can find an example (function dadabik_capitalize)
$function_user_values = '';

// by default, the function set with $function_user_values is applied just to text field types, set the following parameter to 1 if you want to apply it also to textarea and rich_editor text types
$apply_function_user_values_to_textarea_richeditor = 0;

// for a calculated field or a formula field, its value is computed when a new record is inserted and re-computed at every update ("Save" on the edit form), even if the field is hidden from the form or disabled. If you set this parameter to 0, during an update the value of a calculated or formula field is not re-computed if the field is hidden or disabled.
$compute_calculated_fields_update_if_hidden_disabled = 1;

// in forum configurator, for the formula parameter, enable autocompletion
// by default i'ts enabled
// for databases with a very very large number of tables and fields, autocompletion may shlow down the loading of the form configurator
$enable_formula_field_autocompletion = 1;

// function to use for generating IDs if a field type is "unique_ID", if you leave it blank the default DaDaBIK function will be used, if you want to use a custom function set the name here (the name must start with "dadabik_") and write the related function's code in custom_functions.php; the function must return the unique id value.
$unique_ID_custom_function = '';

// you can add an additional PDO connection string that will be added after the default one
// in the file /include/db_functions_pdo.php, function connect_db(), you can see the default connection strings used 
$additional_db_connection_string = '';

/* you can add additional connection parameters (for example to establish an SSL connection) by setting an array for additional_db_connection_parameters
e.g. 
$additional_db_connection_parameters = array(
    PDO::MYSQL_ATTR_SSL_CA => '/path/to/a.pem',
    PDO::MYSQL_ATTR_SSL_CERT => '/path/to/b.pem',
    PDO::MYSQL_ATTR_SSL_KEY => '/path/to/c.pem',
);
*/
$additional_db_connection_parameters = '';

// emoji used in the DEV Area for the "VIEW this app" button (empty string = no emoji) 
$emoji_view_this_app_button = '🚀';

// in DEV area, for code text boxes (e.g. the JS custom formatting function textarea), enable codemirror to get syntax highlighting
$enable_codemirror = 1;

// prefix of the NULL checkbox name
$null_checkbox_prefix = 'null_value__';

// prefix of the custom buttons' IDs
$custom_button_ids_prefix = 'cb__';

// prefix of the field+buttons+hint container IDs
$field_button_hint_container_id_prefix = 'cont_field_button_hint_';

// prefix of the selet checkbox
$select_checkbox_prefix = 'sel_chk__';

// suffix of the select type (contains, is_null....) listbox
$select_type_select_suffix = '__select_type';

// suffix of the date time types
$year_field_suffix = '__year';
$month_field_suffix = '__month';
$day_field_suffix = '__day';
$hours_field_suffix = '__hours';
$minutes_field_suffix = '__minutes';
$seconds_field_suffix = '_seconds';

// htmLawed configuration, you shouldn't change this parameter, changing this parameter can lead to security problems, read documentation for more information
$htmlawed_config = array('safe'=>1, 'deny_attribute'=>'style, class');

// choose the correct db library; you shouldn't change this parameter
$db_library = 'pdo';

// perform a check about mbstring extension installation; this parameter should be always set to 1
$mbstring_check = 1;

// alias_prefix
$alias_prefix = '____'; // you can safely leave this option as is

// how does DaDaBIK read the files uploaded: 'public_url' or 'php'; for security reasons, you should keep this option set to "php" and keep the upload folder (see $upload_directory) protected from public access (see documentation)
$file_access_mode = 'php';

// The separator character used in form configurator to separate values in  "option to include", "Linked fields", "Linked items table names" and "Items table foreign key field names" parameters. If you change this parameter after the installation, you will have to modify the charaters whereever you used it in the form configurator
// the lenght of the separator needs to be 1
define('FORM_CONFIGURATOR_SEPARATOR', '~');

// if 1, the custom formatting functions take in input, for select_single/select_multiple fields having more than one linked field, each linked field value singularly. This allows to have more control on the outupt. It should be set to 0 just for compatibility with DaDaBIK applications created with DaDaBIK < 7.1 (if you can't modify your formatting functions). (0|1)
$pass_linked_fields_to_format_function_as_array = 1;

// default function to be executed when you click on a menu item related to a page based on table/view. If you don't specify anything, by default the results grid is shown; alternatively, you can add an element to the associative array $table_default_functions and specify 'show_search_form' or 'show_insert_form'
/* in this example
$table_default_functions['customers'] = 'show_insert_form';
for the table customers, the default function is show_insert_form
*/

// minimum number of characters to type in a search-as-you-type ajax drop-down menu before DaDaBIK starts querying the table to find the matching options
$ajax_dropdown_minimum_input_length = 3;

// check if table and field names, for the tables installed in DaDaBIK, contains not allowed characters. It is highly recommended that you keep this option set to 1.
$check_table_field_names = 1;

// regex pattern for valid table and field names. It is highly recommended that you keep this option as it is.
$valid_table_field_name_pattern= '/^[a-zA-Z0-9_\-]+$/';

// DaDaBIK checks (mysql only) if the NO_BACKSLASH_ESCAPES sql mode is enabled, and produces an error in case it is. NO_BACKSLASH_ESCAPES is not compatible with DaDaBIK, you are strongly encouraged to leave this option set to 1.
$check_sql_mode = 1;

// show a warning about the required field type when a custom user or group table is in use
$warning_custom_users_groups = 1;

// show a warning about the use of session.auto_start in php.ini setting.
// if you set session.auto_start = 1 in php.ini, DaDaBIK cannot assign a custom session name and cookie_path and cannot set the session cookie as httponly, this makes your applicaiton less secure and DaDaBIK shows a warning message. It is strongly recommended to set session.auto_start = 0. You can disable the warning by setting $warning_sessions = 0; disable it only if you really know what you are doing.
$warning_sessions = 1;

// show a warning in the admin section when a select_multiple field is available in the quick search form
$warning_select_multiple_quick = 1;

// set to 1 to include CSS and JS files by adding an additional random value to their URL, this is useful to prevent caching if you are changing the CSS / JS files, leave it to 0 if your app is in production
$force_reload_css_js = 0;

// absolute path of the directory where DaDaBIK lives - please put slash (/) at the end
// e.g. 'c:\\data\\web\\dadabik\\' on windows systems
// e.g. '/home/my/path/dadabik/' on unix systems
// you can leave it empty, fill it only if the CSV/Excel process asks you to do it
$working_dir = '';

// when you upgrade DaDaBIK using the up/up2 procedure, a temporary upgrades folder is created
// by default, permissions assigned are 0755, it is recommended to keep the default value but you can change it here
$permissions_upgrades_folder = 0755;

// max upload size for uploaded logo (in bytes, default is 100k, 102400 bytes)
$max_upload_file_size_logo = 102400;

// set to 1 if you don't want to use tags in form configurator for the custom list of options and for the search operators
$dont_tagify_form_config = 0;

// don't change this parameter
$dir_custom_php_files = 'custom_php_files';

// don't change this parameter
$dir_templates = 'templates';

$add_delay_form_submission_during_ajax_calls = 1;


// check data coming from insert/update forms to guess if all the data actually arrived (the PHP parameter post_max_size can limit the amount of data), you should always set this parameter to 1
$check_post_for_data = 1;

// same as before, but for search forms
$check_search_post_for_data = 1;

// by default, in form configurator, custom PHP functions (e.g. the custom validation function for a field) are chosen from a listbox, parsing the custom_functions.php and related files. If, instead, you want to type the name of the function manually (as it happened before DaDaBIK 10), set this parameter to 0.
$use_listbox_for_custom_function_selection = 1;

// enable form configurator live preview (0|1, default is 1)
// DaDaBIK shows a live preview of the form while you edit the form configurator. You can disable the live preview if you think it causes problems
$enable_form_config_live_preview = 1;

// the max height (viewport %) of the results grid fixed header
$vh_fixed_header = '60';

// MS Internet Explorer is not supported anymore and some DaDaBIK features don't work as expected with this browser, by default DaDaBIK checks which browser is being used and warn the user in case it's IE. You can disable this check but it is strongly recommended to leave it enabled.
$check_ie_browser = 1;

// In the admin > Data > settings page, the default value for the setting "Install & enable tables on creation"
$default_install_enable_tables_on_creation = 'yes';

// In the admin > Data page, when you add a field, by default make it NULLABLE
$data_add_field_default_nullable = 0;

/*************************************/

// don't change the following lines

$date_picker_type = 'flatpickr';

$add_nosniff_header = 1;

$add_nocache_header = 1;

$enable_support_for_nested_master_details = 1;

$prefix_internal_table_bkp = $prefix_internal_table;

$GLOBALS_config = $GLOBALS;

$unlock_when_execute_custom_function = 0;

$set_no_field_permissions_when_no_table_permission = 0;

$add_custom_header_footer_each_page = 0;

$never_show_review_invitation = 0;

// up2
$trigger_fatal_error_db_operations = 0;

// up2
$mysql_set_sql_big_selects = 0;

require './include/config_custom.php';


if ($prefix_internal_table !== 'dadabik_'){ // the prefix has been changed

    if ($groups_table_name === $prefix_internal_table_bkp.'groups'){

        // re-set, because $prefix_internal_table might change in config_custom
        $groups_table_name = $prefix_internal_table.'groups';
    }

    if ($users_table_name === $prefix_internal_table_bkp.'users'){

        // re-set, because $prefix_internal_table might change in config_custom
        $users_table_name = $prefix_internal_table.'users';
    }

}



// table_list_name name
$table_list_name = $prefix_internal_table."table_list";

if ($dbms_type === 'sqlserver'){
    // MS SQL Server unicode connection encoding
    $sqlserver_conn_additional_attributes[PDO::SQLSRV_ATTR_ENCODING] = PDO::SQLSRV_ENCODING_UTF8;
}

$field_type_for_date = 'date_picker';



$config_basic_settings_fields[0] = 'logo_img';
$config_basic_settings_fields[1] = 'logo_uploaded';
$config_basic_settings_fields[2] = 'font';
$config_basic_settings_fields[3] = 'left_menu_bgcolor';
$config_basic_settings_fields[4] = 'standard_buttons_bgcolor';
$config_basic_settings_fields[5] = 'custom_buttons_bgcolor';
$config_basic_settings_fields[6] = 'danger_buttons_bgcolor';
$config_basic_settings_fields[7] = 'left_menu_text_color';
$config_basic_settings_fields[8] = 'standard_buttons_text_color';
$config_basic_settings_fields[9] = 'custom_buttons_text_color';
$config_basic_settings_fields[10] = 'results_grid_header_bgcolor';
$config_basic_settings_fields[11] = 'title_application';
//$config_basic_settings_fields[] = 'graphic_theme';
$config_basic_settings_fields[12] = 'grid_layout_scrolling';
$config_basic_settings_fields[13] = 'results_grid_fixed_header';
//$config_basic_settings_fields[] = 'menu_type';
$config_basic_settings_fields[14] = 'dont_show_menu_if_only_one_item';
$config_basic_settings_fields[15] = 'results_display_mode_menu';
$config_basic_settings_fields[16] = 'maxlength_grid';
$config_basic_settings_fields[17] = 'theme';
$config_basic_settings_fields[18] = 'hide_top_bar';
$config_basic_settings_fields[19] = 'hide_menu';
$config_basic_settings_fields[20] = 'top_bar_bgcolor';
$config_basic_settings_fields[21] = 'edit_icon_color';
$config_basic_settings_fields[22] = 'delete_icon_color';
$config_basic_settings_fields[23] = 'details_icon_color';

$config_basic_settings_field_categories[3] = 'theme_color';
$config_basic_settings_field_categories[4] = 'theme_color';
$config_basic_settings_field_categories[5] = 'theme_color';
$config_basic_settings_field_categories[6] = 'theme_color';
$config_basic_settings_field_categories[7] = 'theme_color';
$config_basic_settings_field_categories[8] = 'theme_color';
$config_basic_settings_field_categories[9] = 'theme_color';
$config_basic_settings_field_categories[10] = 'theme_color';
$config_basic_settings_field_categories[20] = 'theme_color';
$config_basic_settings_field_categories[21] = 'theme_color';
$config_basic_settings_field_categories[22] = 'theme_color';
$config_basic_settings_field_categories[23] = 'theme_color';

$config_basic_settings_field_labels[3] = 'Left Menu Bgcolor';
$config_basic_settings_field_labels[4] = 'Standard Buttons';
$config_basic_settings_field_labels[5] = 'Custom Buttons';
$config_basic_settings_field_labels[6] = 'Danger Buttons';
$config_basic_settings_field_labels[7] = 'Left Menu Text';
$config_basic_settings_field_labels[8] = 'Standard Buttons Text';
$config_basic_settings_field_labels[9] = 'Custom Buttons Text';
$config_basic_settings_field_labels[10] = 'Results Grid Header';
$config_basic_settings_field_labels[20] = 'Top Bar Bgcolor';
$config_basic_settings_field_labels[21] = 'Edit icon';
$config_basic_settings_field_labels[22] = 'Delete icon';
$config_basic_settings_field_labels[23] = 'Details icon';

$config_basic_settings_field_options['theme'][] = 'Default';
$config_basic_settings_field_options['theme'][] = 'Default_Alternative';
$config_basic_settings_field_options['theme'][] = 'Dark';
$config_basic_settings_field_options['theme'][] = 'Dark_Light-Top';
$config_basic_settings_field_options['theme'][] = 'Dashboard_Classic';
$config_basic_settings_field_options['theme'][] = 'Dashboard_Bright';
$config_basic_settings_field_options['theme'][] = 'Graphite_&_Smoke';
$config_basic_settings_field_options['theme'][] = 'Midnight_Blue';
$config_basic_settings_field_options['theme'][] = 'Stone_&_Teal';
$config_basic_settings_field_options['theme'][] = 'Pine_&_Ash';
$config_basic_settings_field_options['theme'][] = 'Terracotta_Glow';
$config_basic_settings_field_options['theme'][] = 'Burnt_Sienna';
$config_basic_settings_field_options['theme'][] = 'Slate_Blue';
$config_basic_settings_field_options['theme'][] = 'Blue_Green';
$config_basic_settings_field_options['theme'][] = 'Calm_Horizon';
$config_basic_settings_field_options['theme'][] = 'Custom';

$config_basic_settings_field_options['font'][] = 'manrope';
$config_basic_settings_field_options['font'][] = 'open_sans';
$config_basic_settings_field_options['font'][] = 'source_sans_3';
$config_basic_settings_field_options['font'][] = 'montserrat';
$config_basic_settings_field_options['font'][] = 'nunito';

// deprecated, will be deleted
/*$config_basic_settings_field_options['graphic_theme'][] = 'classic';
$config_basic_settings_field_options['graphic_theme'][] = 'bluegray';
$config_basic_settings_field_options['graphic_theme'][] = 'black';
$config_basic_settings_field_options['graphic_theme'][] = 'blue';
$config_basic_settings_field_options['graphic_theme'][] = 'green';*/

$config_basic_settings_field_options['grid_layout_scrolling'][] = 'site_overflow';
$config_basic_settings_field_options['grid_layout_scrolling'][] = 'grid_overflow';
$config_basic_settings_field_options['grid_layout_scrolling'][] = 'grid_scroll';

$config_basic_settings_field_options['menu_type'][] = 'left_side_menu';
$config_basic_settings_field_options['menu_type'][] = 'drop_down_menu';

$config_basic_settings_field_options['results_display_mode_menu'][] = 'both';
$config_basic_settings_field_options['results_display_mode_menu'][] = 'classic_grid';
$config_basic_settings_field_options['results_display_mode_menu'][] = 'list';

$allowed_field_types_omnisearch = ['text','textarea','rich_editor','select_single','select_single_radio','select_multiple_menu','select_multiple_checkbox','id_user', 'generic_file','image_file','camera','date','date_time','insert_date_time','update_date_time','barcode','qrcode'];

$allowed_field_contents_omnisearch = ['alphabetic','alphanumeric','url','email','phone','html'];

const DADABIK_TABLES = array('api_tokens','config','config_beta','failed_login','forms','forms_beta','forms_preview','groups','installation_tab','locked_records','logs','permission_options','permission_types','permissions','permissions_beta','static_pages','static_pages_beta','table_list','table_list_beta','unique_ids','users','pushes','buttons','buttons_beta','automations','automations_beta','custom_reports','dadabrain_queries','user_entities','user_entities_beta','errors');

// up2
const COMPLETE_LANGUAGES_AR = array  ('english','catalan','chinese','croatian','czech','danish','dutch','estonian','finnish','french','german','hungarian','italian','norwegian','polish','portuguese','rumanian','russian','slovak','spanish','swedish','telugu','traditionalChinese');

$enable_authentication = 1;

$google_allowed_iss_ar[0] = 'https://accounts.google.com';
$google_allowed_iss_ar[1] = 'accounts.google.com';

$dadabik_config_version = '13.5';
$dadabik_config_codename = 'Levanto';