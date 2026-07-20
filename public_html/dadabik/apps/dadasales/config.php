<?php

// FIXED PARAMETERS, don't change the following parameters
$dir_custom_php_files = 'custom_php_files_prepackaged_app';


$dir_templates = 'templates_prepackaged_app';

$prefix_internal_table = 'dadabik_';

$users_table_name = $prefix_internal_table.'users';
$users_table_id_field = 'id_user';
$users_table_id_group_field = 'id_group';
$users_table_username_field = 'username_user';
$users_table_password_field = 'password_user';
$users_table_authentication_type_field = 'authentication_type_user';

$groups_table_name = $prefix_internal_table.'groups';

$groups_table_name_field = 'name_group';
$groups_table_id_field = 'id_group';
$id_admin_group = 1;
// end of FIXED PARAMETERS

$use_listbox_for_custom_function_selection = 0;

?>