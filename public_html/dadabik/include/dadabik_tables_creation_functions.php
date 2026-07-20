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

function create_internal_table($table_internal_name, $only_beta_for_updgrade = 0)
// goal: drop (if present) the old internal table and create the new one.
// input: $table_internal_name
// IF YOU ADD OR REMOVE FIELDS FROM HERE, YOU MUST MODIFY THE TWO INSERT QUERY IN INTERNAL_TABLE_MANAGER.PHP

// ****************************UP
{
	global $conn, $dbms_type, $prefix_internal_table, $autoincrement_word;
	
	if ($only_beta_for_updgrade === 0){
	    drop_table_db($conn, $table_internal_name);
	}
	if ($table_internal_name === $prefix_internal_table.'forms'){ // we don't do beta for preview 
	    drop_table_db($conn, $table_internal_name.'_beta');
	}

// IF YOU ADD OR REMOVE FIELDS FROM HERE, YOU MUST MODIFY THE TWO INSERT QUERY IN INTERNAL_TABLE_MANAGER.PHP

	$fields = "
	id_field ".$autoincrement_word.",
	name_field varchar(255),
	label_field varchar(500) DEFAULT '' NOT NULL,
	label_grid_field varchar(500) DEFAULT '' NOT NULL,
	type_field varchar(50) DEFAULT 'text' NOT NULL,
	show_if_field_field varchar(255) DEFAULT '' NOT NULL,
	show_if_operator_field varchar(15) DEFAULT '' NOT NULL,
	show_if_value_field varchar(50) DEFAULT '' NOT NULL,
	formula_field varchar(255) DEFAULT '' NOT NULL,
	calculated_function_field varchar(255) default '' NOT NULL,
	custom_validation_function_field varchar(255) default '' NOT NULL,
	custom_formatting_function_field varchar(255) default '' NOT NULL,
	js_custom_formatting_function_field varchar(800) default '' NOT NULL,
	custom_csv_formatting_function_field varchar(255) default '' NOT NULL,
	custom_required_function_field varchar(255) default '' NOT NULL,
	js_event_functions_field varchar(500) default '' NOT NULL,
	content_field varchar(50) DEFAULT 'alphanumeric' NOT NULL,
	present_search_form_field varchar(1) DEFAULT '1' NOT NULL,
	present_results_search_field varchar(1) DEFAULT '1' NOT NULL,
	present_details_form_field varchar(1) DEFAULT '1' NOT NULL,
	present_insert_form_field varchar(1) DEFAULT '1' NOT NULL,
	present_edit_form_field varchar(1) DEFAULT '1' NOT NULL,
	present_csv_field varchar(1) DEFAULT '1' NOT NULL,
	present_filter_form_field varchar(1) DEFAULT '0' NOT NULL,
	present_ext_update_form_field varchar(1) DEFAULT '1' NOT NULL,
	required_field varchar(1) NOT NULL,
	check_duplicated_insert_field varchar(1) DEFAULT '0' NOT NULL,
	other_choices_field varchar(1) DEFAULT '0' NOT NULL,
	show_lookup_link_field varchar(1) default '0' NOT NULL,
	chosen_field varchar(1) NOT NULL default '0',
	chosen_ajax_field varchar(1) NOT NULL default '0',
	select_options_field varchar(500) DEFAULT '' NOT NULL,
	primary_key_field_field varchar(500) DEFAULT '' NOT NULL,
	primary_key_table_field varchar(500) DEFAULT '' NOT NULL,
	primary_key_db_field varchar(50) DEFAULT '' NOT NULL,
	linked_fields_field varchar(500) DEFAULT '' NOT NULL,
	linked_fields_order_by_field varchar(500) DEFAULT '' NOT NULL,
	linked_fields_order_type_field varchar(100) DEFAULT '' NOT NULL,
	where_clause_field varchar(500) DEFAULT '' NOT NULL,
	select_type_field varchar(200) NOT NULL,
	items_table_names_field varchar(500) DEFAULT '' NOT NULL,
	items_table_fk_field_names_field varchar(500) DEFAULT '' NOT NULL,
	cascade_filter_field varchar(500) DEFAULT '' NOT NULL,
	cascade_parent_field varchar(500) DEFAULT '' NOT NULL,
	prefix_field varchar(500) DEFAULT '' NOT NULL,
	default_value_field varchar(500) DEFAULT '' NOT NULL,
	width_field varchar(500) DEFAULT '' NOT NULL,
	height_field varchar(500) DEFAULT '' NOT NULL,
	maxlength_field varchar(500) DEFAULT '100' NOT NULL,
	hint_insert_field varchar(500) DEFAULT '' NOT NULL,
	tooltip_field varchar(500) DEFAULT '' NOT NULL,
	order_form_field integer NOT NULL,
	separator_field varchar(500) DEFAULT '~' NOT NULL,
	details_new_line_after_field varchar(1) default '1' NOT NULL,
	search_new_line_after_field varchar(1) default '1' NOT NULL,
	insert_new_line_after_field varchar(1) default '1' NOT NULL,
	edit_new_line_after_field varchar(1) default '1' NOT NULL,
	insert_separator_before_field varchar(500) default '' NOT NULL,
	edit_separator_before_field varchar(500) default '' NOT NULL,
	search_separator_before_field varchar(500) default '' NOT NULL,
	details_separator_before_field varchar(500) default '' NOT NULL,
	min_width_results_grid_column_field varchar(50) default '' NOT NULL,
	table_name varchar(255) NOT NULL";

// IF YOU ADD OR REMOVE FIELDS FROM HERE, YOU MUST MODIFY THE TWO INSERT QUERY IN INTERNAL_TABLE_MANAGER.PHP

	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_field\")";
	}
	
	$fields .= ")
	";

	
	if ($only_beta_for_updgrade === 0){
	    create_table_db($conn, $table_internal_name, $fields);
	}
	if ($table_internal_name === $prefix_internal_table.'forms'){ // we don't do beta for preview 
	    create_table_db($conn, $table_internal_name.'_beta', $fields);
	}
	

} // end function create_internal_table

function create_table_list_table($only_beta_for_updgrade = 0)
// goal: drop (if present) the old table list and create the new one.
{
	global $conn, $dbms_type, $prefix_internal_table;
	
	$table_list_name = $prefix_internal_table.'table_list';
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	
		if ($only_beta_for_updgrade === 0){
		    drop_table_db($conn, $table_list_name);
		}
		drop_table_db($conn, $table_list_name.'_beta');
	
		$fields = "
		name_table varchar(128) NOT NULL default '' PRIMARY KEY,
		allowed_table varchar(1) NOT NULL default '',
		enable_insert_table varchar(1) NOT NULL default '',
		enable_edit_table varchar(1) NOT NULL default '',
		enable_csv_table varchar(1) NOT NULL default '',
		enable_delete_table varchar(1) NOT NULL default '',
		enable_details_table varchar(1) NOT NULL default '',
		enable_list_table varchar(1) NOT NULL default '',
		enable_delete_authorization_table varchar(1) NOT NULL default '0',
		enable_update_authorization_table varchar(1) NOT NULL default '0',
		enable_browse_authorization_table varchar(1) NOT NULL default '0',
		enable_csv_authorization_table varchar(1) NOT NULL default '0',
		enable_revision_table CHAR(1) NOT NULL DEFAULT '0',
		alias_table varchar(255) NOT NULL default '',
		enable_template_table varchar(1) NOT NULL default '0',
		template_table text NOT NULL,
		menu_order_table INT NOT NULL default '1',
		menu_parent_table VARCHAR(255) NOT NULL DEFAULT 'top',
		separator_before_table CHAR(1) NOT NULL DEFAULT 'n',
		order_by_table VARCHAR(500) NOT NULL DEFAULT '',
		order_type_table VARCHAR(4) NOT NULL DEFAULT '',
		pk_field_table varchar(500) NOT NULL default '',
		icon_table varchar(255) NOT NULL default '',
		
	heading_before_table TEXT NULL,
	heading_after_table TEXT NULL,
	calendar_id_field_table varchar(255) default '' NOT NULL,
	calendar_title_field_table varchar(255) default '' NOT NULL,
	calendar_description_field_table varchar(255) default '' NOT NULL,
	calendar_lastupdate_field_table varchar(255) default '' NOT NULL,
	calendar_location_field_table varchar(255) default '' NOT NULL,
	calendar_tooltip_field_table varchar(255) default '' NOT NULL,
	calendar_category_field_table varchar(255) default '' NOT NULL,
	calendar_start_date_time_field_table varchar(255) default '' NOT NULL,
	calendar_end_date_time_field_table varchar(255) default '' NOT NULL,
	record_list_layout varchar(50) default 'grid_list' NOT NULL
	";
		
		/*
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"name_table\")";
  		}
  		*/
  		
		$fields .= ")
		";
		if ($only_beta_for_updgrade === 0){
		    create_table_db($conn, $table_list_name, $fields);
		 }   
		create_table_db($conn, $table_list_name.'_beta', $fields);
	}
	else{

		$data_dictionary = NewDataDictionary($conn);

		drop_table_db($conn, $data_dictionary, $table_list_name);
	
	
		$fields = "
		name_table C(255) NOTNULL default '' PRIMARY,
		allowed_table C(1) NOTNULL default '',
		enable_insert_table C(1) NOTNULL default '',
		enable_edit_table C(1) NOTNULL default '',
		enable_delete_table C(1) NOTNULL default '',
		enable_details_table C(1) NOTNULL default '',
		enable_list_table C(1) NOTNULL default '',
		alias_table C(255) NOTNULL default '',
		enable_template_table c(1) NOT NULL default '0',
		template_table X NOT NULL default ''
		)
		";
		
		create_table_db($conn, $data_dictionary, $table_list_name, $fields);
	}

} // end function create_table_list_table

function create_users_table($installation_password, $for_orazio = 0)
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $users_table_name, $quote, $dbms_type, $generate_portable_password_hash, $autoincrement_word, $date_time_word;
	
	drop_table_db($conn, $users_table_name);
	
	$unique_constraint = ' UNIQUE';
	
	if ($dbms_type === 'sqlserver'){
	    $unique_constraint = '';
	}

	$fields = "
	id_user ".$autoincrement_word.",
	id_group integer NOT NULL,
	ids_group_others TEXT NULL,
	first_name_user varchar(100) NULL,
	last_name_user varchar(100) NULL,
	email_user varchar(100) NULL".$unique_constraint.",
	username_user varchar(50) NOT NULL UNIQUE,
	password_user varchar(255) NOT NULL,
	authentication_type_user varchar(255) NOT NULL DEFAULT 'dadabik',
	id_confirmation_user VARCHAR(100) NULL".$unique_constraint.",
	id_confirmation_timestamp_user INT NULL ,
	temporary_password_user VARCHAR(255) NULL,
	temporary_password_timestamp_user INT NULL,
	confirmed_timestamp_user INT NULL,
	force_password_change_user VARCHAR(3) NOT NULL DEFAULT 'no',
	accept_terms_privacy_1_user ".$date_time_word." NULL,
	accept_terms_privacy_2_user ".$date_time_word." NULL,
	accept_terms_privacy_3_user ".$date_time_word." NULL,
	enable_2fa_user VARCHAR(3) NOT NULL DEFAULT 'no',
	secret_2fa_user VARCHAR(255) NULL,
	nonce_secret_2fa_user VARCHAR(255) NULL";
	
        
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_user\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $users_table_name, $fields);
	
	if ($dbms_type === 'sqlserver'){
	     $sql = "create unique NONCLUSTERED index email_user_unique on  ".$quote.$users_table_name.$quote."(email_user) where email_user is not null";
        $res = execute_db($sql, $conn);
                                
        $sql = "create unique NONCLUSTERED index id_confirmation_user_unique on  ".$quote.$users_table_name.$quote."(id_confirmation_user) where id_confirmation_user is not null";
        $res = execute_db($sql, $conn);
	}
	
	//$t_hasher = new PasswordHash(8, $generate_portable_password_hash);
	//$encrypted = $t_hasher->HashPassword('letizia');
	//$encrypted = create_password_hash('letizia');

	

	if (strlen($installation_password) > 72){
		echo 'Error, password too long';
		exit();
	} 
	
	$encrypted = create_password_hash($installation_password);
	if (strlen_custom($encrypted) < 20){
		echo 'Error';
		exit();
	}

	//$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_group, username_user, password_user, authentication_type_user, confirmed_timestamp_user) VALUES (1, 'root', '".$encrypted."', 'dadabik', '".time()."')";
	//$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_group, username_user, password_user, authentication_type_user, confirmed_timestamp_user) VALUES (1, 'root', :encrypted, 'dadabik', '".time()."')";
	
	$res_prepare = prepare_db($conn, $sql);
	$res_bind = bind_param_db($res_prepare, ':encrypted', $encrypted);
	$res = execute_prepared_db($res_prepare,0);

	//$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_group, username_user, password_user, authentication_type_user, confirmed_timestamp_user) VALUES (2, 'alfonso', '".$encrypted."', 'dadabik', '".time()."')";
	//$res_table = execute_db($sql, $conn);

	if ($for_orazio === 0){
		$sql = "INSERT INTO ".$quote.$users_table_name.$quote." (id_group, username_user, password_user, authentication_type_user, confirmed_timestamp_user) VALUES (2, 'alfonso', :encrypted, 'dadabik', '".time()."')";
		$res_prepare = prepare_db($conn, $sql);
		$res_bind = bind_param_db($res_prepare, ':encrypted', $encrypted);
		$res = execute_prepared_db($res_prepare,0);

	}
	
	
	
} // end function create_users_table

function create_groups_table()
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $groups_table_name, $quote, $dbms_type, $autoincrement_word, $prefix_internal_table;
	
	drop_table_db($conn, $prefix_internal_table.'user_entities');
	drop_table_db($conn, $prefix_internal_table.'user_entities_beta');
	drop_table_db($conn, $groups_table_name);

	$fields = "
	id_group ".$autoincrement_word.",
	name_group varchar(100) NOT NULL UNIQUE";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_group\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $groups_table_name, $fields);
	
	$sql = "INSERT INTO ".$quote.$groups_table_name.$quote." (name_group) VALUES ('admin')";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$quote.$groups_table_name.$quote." (name_group) VALUES ('default')";

	$res_table = execute_db($sql, $conn);

} // end function create_groups_table

function create_config_table($data_types = 'simple', $install_enable_tables_on_creation = 'no', $only_beta_for_updgrade = 0)
// ****************************UP
{
    global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word, $mediumtext_word;
	
	if ($only_beta_for_updgrade === 0){
	    drop_table_db($conn, $prefix_internal_table.'config');
	}
	drop_table_db($conn, $prefix_internal_table.'config_beta');
	
	$fields = "
	name_config varchar(100) NOT NULL PRIMARY KEY,
	value_config ".$mediumtext_word." NOT NULL";
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	if ($only_beta_for_updgrade === 0){
	    create_table_db ($conn, $prefix_internal_table.'config', $fields);
	}
	create_table_db ($conn, $prefix_internal_table.'config_beta', $fields);
	
	if ($only_beta_for_updgrade === 0){
        $sql_part_1_prod = "INSERT INTO ".$quote.$prefix_internal_table."config".$quote;
        $sql_part_1_beta = "INSERT INTO ".$quote.$prefix_internal_table."config_beta".$quote;
    
        $sql_part_2 = "(name_config, value_config) VALUES ('data_types', '".$data_types."'), ('install_enable_tables_on_creation', '".$install_enable_tables_on_creation."'),('dont_show_menu_if_only_one_item', '0'),('graphic_theme', 'bluegray'),('grid_layout_scrolling', 'grid_scroll'),
    ('logo_img', 'images/logo.png'),
	('logo_uploaded', ''),
	('font', 'manrope'),
	('theme', 'Default'),
	('left_menu_bgcolor', '#0B0F1A'),
	('left_menu_text_color', '#FFFFFF'),
	('standard_buttons_bgcolor', '#5268BE'),
	('standard_buttons_text_color', '#ffffff'),
	('custom_buttons_bgcolor', '#6C757D'),
	('custom_buttons_text_color', '#ffffff'),
	('danger_buttons_bgcolor', '#DF3445'),
	('results_grid_header_bgcolor', '#EEEEEE'),
    ('menu_type', 'left_side_menu'),
    ('results_display_mode_menu', 'both'),
    ('results_grid_fixed_header', '0'),
    ('title_application', 'DaDaBIK no-code low-code platform and database front-end - dadabik.com'),
    ('custom_css', ''),
    ('maxlength_grid', '0'),
    ('hide_top_bar', '0'),
    ('hide_menu', '0'),
    ('top_bar_bgcolor', '#ffffff'),
    ('edit_icon_color', '#5067c1'),
    ('delete_icon_color', '#eb1515'),
    ('details_icon_color', '#4f84f3'),
    ('username_public_user', ''),
    ('hide_advanced_features_public_user', '0'),
    ('hide_top_bar_public_user', '0'),
    ('hide_menu_public_user', '0')";
	
        $res = execute_db($sql_part_1_prod.$sql_part_2, $conn);
        $res = execute_db($sql_part_1_beta.$sql_part_2, $conn);
    }

}

function create_api_tokens_table()
{
// ****************************UP
    global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word, $autoincrement_word_bigint, $date_time_word, $users_table_name, $users_table_id_field, $restrict_word;
	
	drop_table_db($conn, $prefix_internal_table.'api_tokens');
	
	$fields = "
	id_api_token ".$autoincrement_word_bigint.",
	id_user int NOT NULL,
	value_api_token varchar(100) NOT NULL UNIQUE,
	date_time_api_token ".$date_time_word." NOT NULL,
	scope_api_token VARCHAR(50) NOT NULL DEFAULT 'general',
	table_api_token VARCHAR(255) NULL,
	where_clause_api_token TEXT NULL
	";
	
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_api_token\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	

	create_table_db ($conn, $prefix_internal_table.'api_tokens', $fields);
}

function create_errors_table()
{
// ****************************UP
    global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word, $autoincrement_word_bigint, $date_time_word, $users_table_name, $users_table_id_field, $restrict_word;
	
	drop_table_db($conn, $prefix_internal_table.'errors');
	
	$fields = "

	id_error ".$autoincrement_word_bigint.",
	date_time_error ".$date_time_word." NOT NULL,
	desc_error text  NOT NULL,
	details_error text  NOT NULL,
	user_error varchar(50) DEFAULT NULL,
	table_error varchar(255)  DEFAULT NULL,
	ip_error varchar(255)  DEFAULT NULL,
	browser_error varchar(255)  DEFAULT NULL,
	function_error varchar(255)  DEFAULT NULL,
	solved_error varchar(3) NOT NULL DEFAULT 'no'
	";

	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_error\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $prefix_internal_table.'errors', $fields);
}

function create_unique_ids_table()
// goal: drop (if present) the old users table and create the new one.
// ****************************UP
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word_bigint;
	
	drop_table_db($conn, $prefix_internal_table.'unique_ids');

	$fields = "
	value_unique_id ".$autoincrement_word_bigint;
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"value_unique_id\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}

	create_table_db ($conn, $prefix_internal_table.'unique_ids', $fields);
	

} // end function create_unique_ids_table

function create_row_level_filters_table()
{
	global $conn, $dbms_type, $prefix_internal_table, $autoincrement_word;
	
	drop_table_db($conn, $prefix_internal_table.'row_level_filters');
	drop_table_db($conn, $prefix_internal_table.'row_level_filters_beta');

	$fields = "
	id_row_level_filter ".$autoincrement_word.",
	id_group integer NOT NULL,
	table_row_level_filter varchar(255) DEFAULT '' NOT NULL,
	field_row_level_filter varchar(255) DEFAULT '' NOT NULL,
	operator_row_level_filter varchar(50) DEFAULT '' NOT NULL,
	type_value_row_level_filter varchar(50) DEFAULT '' NOT NULL,
	value_row_level_filter varchar(500) DEFAULT '' NOT NULL";

// IF YOU ADD OR REMOVE FIELDS FROM HERE, YOU MUST MODIFY THE TWO INSERT QUERY IN INTERNAL_TABLE_MANAGER.PHP

	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_row_level_filter\")";
	}
	
	$fields .= ")
	";

	create_table_db($conn, $prefix_internal_table.'row_level_filters', $fields);
	create_table_db($conn, $prefix_internal_table.'row_level_filters_beta', $fields);

	
}

function create_permissions_tables($only_beta_for_updgrade = 0)
// goal: drop (if present) the old users table and create the new one.
{
	global $conn, $quote, $dbms_type, $autoincrement_word, $prefix_internal_table;
	
	if ($only_beta_for_updgrade === 0){
	    drop_table_db($conn, $prefix_internal_table."permissions");
	}
	drop_table_db($conn, $prefix_internal_table."permissions_beta");

	$fields_part_1 = "
	id_permission ".$autoincrement_word.",
	subject_type_permission varchar(50) NOT NULL,
	id_subject integer NOT NULL,
	object_type_permission varchar(50) NOT NULL,
	object_permission varchar(130) NOT NULL,
	id_permission_type integer,
	value_permission integer NOT NULL,";
	
	$fields_part_2_prod = "
	
	CONSTRAINT unique_permission UNIQUE (id_subject,object_type_permission,object_permission,id_permission_type)";
	$fields_part_2_beta = "
	
	CONSTRAINT unique_permission_beta UNIQUE (id_subject,object_type_permission,object_permission,id_permission_type)";
	
	
	
	if ($dbms_type === 'postgres'){
		$fields_part_2_prod .= ",
		PRIMARY KEY (\"id_permission\")";
		$fields_part_2_beta .= ",
		PRIMARY KEY (\"id_permission\")";
	}
	
	$fields_part_2_prod .= ")";
	$fields_part_2_beta .= ")";

	if ($only_beta_for_updgrade === 0){
	    create_table_db ($conn, $prefix_internal_table."permissions", $fields_part_1.$fields_part_2_prod);
	}
	create_table_db ($conn, $prefix_internal_table."permissions_beta", $fields_part_1.$fields_part_2_beta);
	
	drop_table_db($conn, $prefix_internal_table."permission_options");

	$fields = "
	id_permission_option ".$autoincrement_word.",
	label_permission_option varchar(50) NOT NULL,
	value_permission_option integer NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_permission_option\")";
	}
	
	$fields .= ")";
	
	create_table_db ($conn, $prefix_internal_table."permission_options", $fields);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('Yes', 1)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('No', 0)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('My', 2)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('Yes but disabled', 3)";

	$res_table = execute_db($sql, $conn);
	
	$sql = "INSERT INTO ".$prefix_internal_table."permission_options  (label_permission_option, value_permission_option) VALUES ('Yes but don''t show in menu', 4)";

	$res_table = execute_db($sql, $conn);
	
	drop_table_db($conn, $prefix_internal_table."permission_types");

	$fields = "
	id_permission_type ".$autoincrement_word.",
	type_permission_type varchar(50) NOT NULL,
	object_type_permission_type varchar(50) NOT NULL,
	options_permission_type varchar(50) NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_permission_type\")";
	}
	
	$fields .= ")";

	create_table_db ($conn, $prefix_internal_table."permission_types", $fields);
	
	$sql_identity = '';
	if ($dbms_type == 'sqlserver'){
	    $sql_identity = "SET IDENTITY_INSERT ".$quote.$prefix_internal_table."permission_types".$quote."  ON;";
	}
	
	
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (1, 'read', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (2, 'delete', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (3, 'edit', 'table', '0,1,2')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (4, 'create', 'table', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (5, 'details', 'table', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (7, 'results', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (8, 'edit', 'field', '0,1,3')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (9, 'create', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (10, 'details', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (11, 'quick search', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (12, 'search', 'field', '0,1')";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (13, 'view', 'custom_page', '0,1,4');";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (14, 'csv', 'table', '0,1,2');";
	
	$res_table = execute_db($sql, $conn);
	
	$sql = $sql_identity."INSERT INTO ".$quote.$prefix_internal_table."permission_types".$quote." (id_permission_type, type_permission_type, object_type_permission_type, options_permission_type) VALUES (15, 'csv', 'field', '0,1');";
	
	$res_table = execute_db($sql, $conn);

	
	
} // end function create_permissions_tables

function create_locks_table()
// goal: drop (if present) the old locks table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $autoincrement_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
		drop_table_db($conn, $prefix_internal_table."locked_records");
	
		$fields = "
		id_locked_record ".$autoincrement_word.",
		table_locked_record varchar(50) NOT NULL,
		pk_locked_record varchar(50) NOT NULL,
		value_locked_record varchar(100) NOT NULL,
		username_user varchar(255) NOT NULL,
		timestamp_locked_record integer NOT NULL,
		UNIQUE (table_locked_record,pk_locked_record,value_locked_record)";
		
		if ($dbms_type === 'postgres'){
  			$fields .= ",
  			PRIMARY KEY (\"id_locked_record\")";
  		}
  		
		$fields .= ")
	";
	
		create_table_db ($conn, $prefix_internal_table."locked_records", $fields);
	
	}
	else{
	
	$data_dictionary = NewDataDictionary($conn);

	drop_table_db($conn, $data_dictionary, $prefix_internal_table."locked_records");

	
	$fields = "
		id_locked_record I NOTNULL PRIMARY AUTOINCREMENT,
		table_locked_record C(50) NOTNULL,
		pk_locked_record C(50) NOTNULL,
		value_locked_record C(100) NOTNULL,
		username_user C(255) NOTNULL,
		timestamp_locked_record I NOTNULL
		)
	";
	
	create_table_db ($conn, $data_dictionary, $prefix_internal_table."locked_records", $fields);
	
	$index_name = 'record_index';
	$index_fields = 'table_locked_record,pk_locked_record,value_locked_record';
	$options_ar[0] = 'UNIQUE';
	create_index_db ($conn, $data_dictionary, $prefix_internal_table."locked_records", $index_name, $index_fields, $options_ar);
	
	}
	

} // end function create_locks_table

function create_static_pages_table($only_beta_for_updgrade = 0)
// goal: drop (if present) the old static_pages table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word;
	
	if ($only_beta_for_updgrade === 0){
	    drop_table_db($conn, $prefix_internal_table."static_pages");
	}
	drop_table_db($conn, $prefix_internal_table."static_pages_beta");
	$fields = "
		id_static_page ".$autoincrement_word.",
		link_static_page varchar(255) NOT NULL,
		type_static_page varchar(10) NOT NULL default '',
		file_static_page varchar(100) NOT NULL default '',
		content_static_page text NOT NULL,
		menu_order_static_page INT NOT NULL default '1',
		menu_parent_page VARCHAR(500) NOT NULL DEFAULT 'top',
		enabled_static_page INT NOT NULL DEFAULT '0',
		href_link_static_page TEXT NOT NULL,
		separator_before_static_page CHAR(1) NOT NULL DEFAULT 'n',
		is_homepage_static_page char(1) NOT NULL,
		icon_static_page varchar(255) NOT NULL default ''";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_static_page\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	if ($only_beta_for_updgrade === 0){
	    create_table_db ($conn, $prefix_internal_table."static_pages", $fields);
	}
	create_table_db ($conn, $prefix_internal_table."static_pages_beta", $fields);
	
	if ($only_beta_for_updgrade === 0){
        $sql_part_1_prod = "INSERT INTO ".$quote.$prefix_internal_table."static_pages".$quote;
        $sql_part_1_beta = "INSERT INTO ".$quote.$prefix_internal_table."static_pages_beta".$quote;
    
        $sql_part_2 = " (enabled_static_page, link_static_page, content_static_page, type_static_page, is_homepage_static_page, href_link_static_page, icon_static_page) VALUES (1, 'Home', '<p>Congratulations! This is the homepage of the DaDaBIK application you have created.</p>\r\n<p>If you login as administrator you can customize this application by clicking on <b>Edit this app</b><br/><br/>To change this homepage: <b>Edit this app</b> > <b>Pages</b> > <b>Show: Custom Pages</b>.</p>', 'html', 'y', '', 'home-alt')";
    
        $res = execute_db($sql_part_1_prod.$sql_part_2, $conn);
        $res = execute_db($sql_part_1_beta.$sql_part_2, $conn);
    }
	
} // end function create_static_pages_table

function create_buttons_table()
// goal: drop (if present) the old buttons table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word;
	
	drop_table_db($conn, $prefix_internal_table."buttons");
	drop_table_db($conn, $prefix_internal_table."buttons_beta");

	$fields = "
		id_button ".$autoincrement_word.",
		table_button varchar(255) NOT NULL,
		type_button varchar(50) NOT NULL,
		permission_needed_button varchar(50) NOT NULL,
		show_in_button varchar(255) NOT NULL,
		position_form_button varchar(50) DEFAULT NULL,
		label_type_button varchar(50) NOT NULL,
		label_button varchar(255) NOT NULL,
		style_button text DEFAULT NULL,
		html_id_button varchar(100) DEFAULT NULL,
		confirmation_message_button varchar(255) DEFAULT NULL,
		status_field_button varchar(100) DEFAULT NULL,
		from_status_button varchar(100) DEFAULT NULL,
		to_status_button varchar(100) DEFAULT NULL,
		code_button text DEFAULT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_button\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."buttons", $fields);
	create_table_db ($conn, $prefix_internal_table."buttons_beta", $fields);
	
} // end function create_buttons_table

function create_automations_table()
// goal: drop (if present) the old automations table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word;
	
	drop_table_db($conn, $prefix_internal_table."automations");
	drop_table_db($conn, $prefix_internal_table."automations_beta");

	$fields = "
		id_automation ".$autoincrement_word.",
		trigger_type_automation varchar(50) NOT NULL,
		trigger_table_automation varchar(255) NOT NULL,
		trigger_action_automation varchar(50) NOT NULL,
		trigger_time_automation varchar(10) NOT NULL,
		action_type_automation varchar(50) NOT NULL,
		to_email_automation varchar(255) DEFAULT NULL,
		subject_email_automation varchar(255) DEFAULT NULL,
		body_email_automation text,
		insert_query_automation varchar(500) DEFAULT NULL,
		insert_query_placeholder_automation int DEFAULT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_automation\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."automations", $fields);
	create_table_db ($conn, $prefix_internal_table."automations_beta", $fields);
	
} // end function create_automations_table

function create_custom_reports_table()
// goal: drop (if present) the old custom reports table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word, $date_time_word;
	
	drop_table_db($conn, $prefix_internal_table."custom_reports");

	$fields = "
		id_custom_report ".$autoincrement_word.",
		date_time_custom_report ".$date_time_word." NOT NULL,
		view_custom_report varchar(255) NOT NULL UNIQUE,
		sql_custom_report text NOT NULL,
		ai_custom_report int NOT NULL,
		ai_question_custom_report text default NULL,
		id_user int not NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_custom_report\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."custom_reports", $fields);
	
} // end function create_automations_table

function create_dadabrain_queries_table()
// goal: drop (if present) the old custom reports table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word, $date_time_word;
	
	drop_table_db($conn, $prefix_internal_table."dadabrain_queries");

	$fields = "
		id_dadabrain_query ".$autoincrement_word.",
		date_time_dadabrain_query ".$date_time_word." NOT NULL,
		id_user INT DEFAULT NULL,
		desc_dadabrain_query TEXT NOT NULL,
		sql_dadabrain_query TEXT NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_dadabrain_query\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."dadabrain_queries", $fields);
	
} // end function create_automations_table

function create_user_entities_table()
// goal: drop (if present) the old user entitties table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word, $date_time_word, $groups_table_name, $groups_table_id_field;
	
	drop_table_db($conn, $prefix_internal_table."user_entities");
	drop_table_db($conn, $prefix_internal_table."user_entities_beta");

	$fields = "
		id_user_entity ".$autoincrement_word.",
		table_user_entity VARCHAR(128) NOT NULL UNIQUE,
		id_user_field_user_entity VARCHAR(255) NOT NULL,
		first_name_field_user_entity VARCHAR(100) NULL,
		last_name_field_user_entity VARCHAR(100) NULL,
		email_field_user_entity VARCHAR(100) NULL,
		sync_create_user_entity SMALLINT NOT NULL,
		sync_update_user_entity SMALLINT NOT NULL,
		sync_delete_user_entity SMALLINT NOT NULL,
		id_group_user_entity INT NOT NULL UNIQUE,
        FOREIGN KEY (id_group_user_entity)
        REFERENCES ".$quote.$groups_table_name.$quote."(".$quote.$groups_table_id_field.$quote.")
        ON DELETE CASCADE
        ON UPDATE CASCADE";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_user_entity\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."user_entities", $fields);
	create_table_db ($conn, $prefix_internal_table."user_entities_beta", $fields);
	
} // end function create_automations_table

function create_pushes_table()
// goal: drop (if present) the old table and create the new one.
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $table_list_name, $autoincrement_word, $date_time_word;
		
	drop_table_db($conn, $prefix_internal_table."pushes");
	
	$fields = "
		id_push ".$autoincrement_word.",
		username_push varchar(255) NOT NULL,
		date_time_push ".$date_time_word." NOT NULL,
		comment_push TEXT  NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_push\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	create_table_db ($conn, $prefix_internal_table."pushes", $fields);
	
} // end function create_pushes_table

function create_installation_table()
// goal: create the installation table
{
	global $conn, $prefix_internal_table, $quote, $dbms_type, $date_time_word;
	
	if (true || $dbms_type === 'sqlite' || $dbms_type === 'sqlite2'){
	
	drop_table_db($conn, $prefix_internal_table.'installation_tab');
	
	$fields = "
		id_installation integer NULL,
		code_installation varchar(19) PRIMARY KEY,
		date_time_installation ".$date_time_word." NOT NULL,
		dbms_type_installation varchar(50) NOT NULL,
		dadabik_version_installation varchar(50) NOT NULL,
		dadabik_version_2_installation varchar(10) NOT NULL,
		other_infos_installation varchar(255) NULL,
		other_infos_2_installation TEXT NULL,
		url_installation varchar(500) NULL,
		status_installation VARCHAR(50) DEFAULT 'available' NOT NULL,
		users_maintenance_installation VARCHAR(500) DEFAULT NULL,
		beta_users_installation TEXT NULL
		)
	";

	
	create_table_db ($conn, $prefix_internal_table.'installation_tab', $fields);
	}
	else{
	
	$data_dictionary = NewDataDictionary($conn);
	
	drop_table_db($conn, $data_dictionary, $prefix_internal_table.'installation_tab');

	$data_dictionary = NewDataDictionary($conn);

	
	$fields = "
		id_installation I,
		code_installation C(19),
		date_time_installation T NOTNULL,
		dbms_type_installation C(50) NOTNULL,
		dadabik_version_installation C(10) NOTNULL,
		other_infos_installation C(255)
		)
	";
	
	create_table_db ($conn, $data_dictionary, $prefix_internal_table.'installation_tab', $fields);
	
	}

} // end function create_installation_table

function create_logs_table()
// goal: create the logs table
{
	global $conn, $prefix_internal_table, $dbms_type, $quote, $date_time_word, $autoincrement_word;
	
	$fields = "
	id_log ".$autoincrement_word.",
	timestamp_log integer NOT NULL,
	username_user VARCHAR( 255 ) NULL,
	operation_log varchar(50) NOT NULL,
	sql_log text NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_log\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	drop_table_db($conn, $prefix_internal_table.'logs');
	
	create_table_db ($conn, $prefix_internal_table.'logs', $fields);
	

} // end function create_logs_table

function create_failed_login_table()
// goal: create the logs table
// ****************************UP
{
	global $conn, $prefix_internal_table, $dbms_type, $quote, $date_time_word, $autoincrement_word;
	
	$fields = "
	id_failed_login ".$autoincrement_word.",
	timestamp_failed_login integer NOT NULL,
	username_user varchar(255) NOT NULL";
	
	if ($dbms_type === 'postgres'){
		$fields .= ",
		PRIMARY KEY (\"id_failed_login\")";
	}
	
	$fields .= ")";
	
	if ($dbms_type === 'mysql'){
		$fields .= " ENGINE = INNODB";
	}
	
	drop_table_db($conn, $prefix_internal_table.'failed_login');
	
	create_table_db ($conn, $prefix_internal_table.'failed_login', $fields);
	

} // end function create_failed_login_table

?>