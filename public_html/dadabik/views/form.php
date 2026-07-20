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

require ("./views/form_element.php");

function build_form($table_name, $action, $fields_labels_ar, $form_type, $res_details, $where_field, $where_value, $_POST_2, $_FILES_2, $show_insert_form_after_error, $show_edit_form_after_error, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $set_field_default_value, $default_value_field_name, $default_value, $not_valid_fields_ar=array(), $edit_from_multiple_insert=0, $onlyform = 0, $from_form_configurator_preview = 0)
// goal: build a tabled form by using the info specified in the array $fields_labels_ar
// input: $table_name, array containing labels and other info about fields, $action (the action of the form), $form_type, $res_details, $where_field, $where_value (the last three useful just for update forms), $_POST_2, $_FILES_2 (the last two useful for insert and edit to refill the form), $show_insert_form_after_error (0|1), $show_edit_form_after_error (0|1), tha last two useful to know if the inser or edit forms are showed after a not successful insert and update and so it is necessary to refill the fields, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $set_field_default_value, $default_value_field_name, $default_value (the last two to set a default value for an insert form, used to insert a record of an item table in a master/details view)
// global: $submit_buttons_ar, the array containing the values of the submit buttons, $normal_messages_ar, the array containig the normal messages, $select_operator_feature, wheter activate or not displaying "and/or" in the search form, $default_operator, the default operator if $select_operator_feature is not activated, $db_name, $size_multiple_select, the size (number of row) of the select_multiple_menu fields, $table_name, $not_valid_fields_ar (the array containing the name of the field having not valid values, to highlight in the form)
// output: $form, the html tabled form
{
	global $conn, $submit_buttons_ar, $normal_messages_ar, $select_operator_feature, $default_operator, $db_name, $size_multiple_select, $upload_relative_url, $show_top_buttons, $quote, $enable_authentication, $enable_browse_authorization, $current_user, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix, $start_year, $null_checkbox, $users_table_name, $prefix_internal_table, $enable_granular_permissions, $dadabik_main_file, $field_type_for_date, $htmlawed_config, $treat_blank_as_null, $use_id_group_for_ownership, $current_id_group, $file_access_mode, $link_target_generic_file, $site_url, $enable_browse, $alias_prefix, $show_multiple_inserts_checkbox, $insert_again_after_insert, $show_details_after_insert, $enable_insert, $separator_display_linked_field_2, $lenght_separator_display_linked_field_2, $enable_uploads, $picture_thumbnail_details_max_width, $picture_thumbnail_details_max_height, $show_images_in_edit_form, $custom_button_ids_prefix, $field_button_hint_container_id_prefix, $users_table_password_field, $enable_lookup_insert_popup, $upload_field_type, $use_old_insert_form_button_label, $alternative_primary_key_tables, $button_confirm_messages, $enable_delete, $enable_edit, $enable_details, $ask_confirmation_delete, $show_delete_button_in_edit;

	global $lookup_insert_popup, $lookup_insert_popup_table_from, $lookup_insert_popup_field_from,$lookup_insert_popup_pk_field_from,$modal_mode,$lookup_insert_popup, $lookup_insert_popup_linked_field_from, $fid, $camera_capture_value;

	switch ($form_type) {
		case 'insert':
			$function = 'insert';
			$new_line_field = 'insert_new_line_after_field';
			$separator_before_field = 'insert_separator_before_field';
			break;
		case 'update':
			$function = 'update';
			$new_line_field = 'edit_new_line_after_field';
			$separator_before_field = 'edit_separator_before_field';
			break;
		case 'ext_update':
			$function = 'ext_update';
			break;
		case 'search':
			$function = 'search';
			$new_line_field = 'search_new_line_after_field';
			$separator_before_field = 'search_separator_before_field';
			break;
	} // end switch

	$user_group_name = $current_user;
	if ($use_id_group_for_ownership === 1){
		$user_group_name = $current_id_group;
	}

	$form_querystring_temp = '';

	if ( $form_type == "update" or $form_type == "ext_update") {
		$form_querystring_temp .= "&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value));
	}

	if ( $form_type == "search") {
		$form_querystring_temp .= "&execute_search=1";
	}

	if ($is_items_table === 1) {
		$form_querystring_temp .= '&master_table_name='. urlencode($master_table_name).'&master_table_function='.$master_table_function.'&master_table_where_field='.urlencode($master_table_where_field).'&master_table_where_value='.urlencode(unescape($master_table_where_value)).'&is_items_table='.$is_items_table;
	} // end if

	if ($set_field_default_value === 1) {
		$form_querystring_temp .= '&set_field_default_value=1&default_value_field_name='. urlencode($default_value_field_name).'&default_value='.urlencode($default_value);
	} // end if

	if ( $lookup_insert_popup === 1  ) {
		$form_querystring_temp .= '&lookup_insert_popup_table_from='. urlencode($lookup_insert_popup_table_from).'&lookup_insert_popup_field_from='.urlencode($lookup_insert_popup_field_from).'&lookup_insert_popup_pk_field_from='.urlencode($lookup_insert_popup_pk_field_from).'&lookup_insert_popup_linked_field_from='.urlencode($lookup_insert_popup_linked_field_from).'&modal_mode='.urlencode($modal_mode).'&lookup_insert_popup='.urlencode($lookup_insert_popup);
	} // end if

	$form = "";

	$form .= "\n<!--[if !lte IE 9]><!-->\n";

	$form .= "<form class=\"css_form\" id=\"dadabik_main_form\" name=\"contacts_form\" method=\"post\" action=\"".$action."?fid=".$fid."&tablename=".urlencode($table_name)."&function=".$function.$form_querystring_temp."\" enctype=\"multipart/form-data\">\n";
	$form .= "<!--<![endif]-->\n";
	$form .= "<!--[if lte IE 9]>\n";
	$form .= "<form id=\"dadabik_main_form\" name=\"contacts_form\" method=\"post\" action=\"".$action."?tablename=".urlencode($table_name)."&function=".$function.$form_querystring_temp."\" enctype=\"multipart/form-data\">\n";
	$form .= "<![endif]-->\n";

	switch($form_type){
		case 'insert':
			$button_show_in = 'insert_form';
			break;
		case 'update':
			$button_show_in = 'edit_form';
			break;
		case 'search':
			$button_show_in = 'search_form';
			break;
		default:
			die('unexpected error');
			break;
	}

	$form .= '<input type="hidden" value="1" name="'.$prefix_internal_table.$alias_prefix.'check_post">';

	$buttons_html = '';
	$custom_buttons_to_display = get_custom_buttons_to_display($table_name, $fields_labels_ar, $button_show_in, 'top', 1,  unescape($where_field), unescape($where_value));

	if (count($custom_buttons_to_display) > 0){
		foreach($custom_buttons_to_display as $button){
		    $id_attribute = '';
            if (isset($button['id'])){
                $id_attribute = ' id="'.htmlspecialchars($custom_button_ids_prefix.$button['id']).'"';
            }
			$buttons_html .= '<input'.$id_attribute.' type="button" class="button_form btn btn-secondary custom_button" style="'.$button['style'].'" value="'.$button['label'].'"';

			$url_to_call = $dadabik_main_file."?function=execute_custom_function&tablename=".urlencode($table_name)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."&custom_function=".urlencode($button['callback_function'])."&name_button=".urlencode($button['name']);

			$buttons_html = $buttons_html . get_custom_button_onclick_part($button, $url_to_call);
		}
	}
	
	$standard_buttons['edit'] = array('save','insert_as_new');
	$standard_buttons['insert'] = array('save');

    if ($form_type === 'update' || $form_type === 'insert'){
        $form_type_to_check_confirm = $form_type;
        if ($form_type === 'update'){
            $form_type_to_check_confirm = 'edit';
        }
        foreach($standard_buttons[$form_type_to_check_confirm] as $value){
            $onclick_confirm_part[$value] = '';

            // e.g. $button_confirm_messages['edit']['insert_as_new']
            if (isset($button_confirm_messages[$form_type_to_check_confirm][$value])){
                if ($button_confirm_messages[$form_type_to_check_confirm][$value]['type'] === 'fixed'){
                    $confirm_message = $button_confirm_messages[$form_type_to_check_confirm][$value]['value'];
                }
                elseif ($button_confirm_messages[$form_type_to_check_confirm][$value]['type'] === 'language_file'){
                    $confirm_message = $normal_messages_ar[$button_confirm_messages[$form_type_to_check_confirm][$value]['value']];
                }
                else{
                    die('Error: unkown type for button confirm message');
                }

				$js = "if (!confirm(" . json_encode($confirm_message) . ")){return false;}";
				$onclick_confirm_part[$value] = htmlspecialchars($js, ENT_QUOTES, 'UTF-8');
            }

            // e.g. $button_confirm_messages['customers']['edit']['insert_as_new'], this ovveride general, not table-specirfic, button confirms
            if (isset($button_confirm_messages[$table_name][$form_type_to_check_confirm][$value])){
                if ($button_confirm_messages[$table_name][$form_type_to_check_confirm][$value]['type'] === 'fixed'){
                    $confirm_message = $button_confirm_messages[$table_name][$form_type_to_check_confirm][$value]['value'];
                }
                elseif ($button_confirm_messages[$table_name][$form_type_to_check_confirm][$value]['type'] === 'language_file'){
                    $confirm_message = $normal_messages_ar[$button_confirm_messages[$table_name][$form_type_to_check_confirm][$value]['value']];
                }
                else{
                    die('Error: unkown type for button confirm message');
                }

				$js = "if (!confirm(" . json_encode($confirm_message) . ")){return false;}";
				$onclick_confirm_part[$value] = htmlspecialchars($js, ENT_QUOTES, 'UTF-8');
            }
        }
    }

	switch($form_type){
		case "insert":
			$number_cols = 3;
			$field_to_ceck = "present_insert_form_field";
			$form .= "<div class=\"save_buttons_container\">";
			$form .= $buttons_html;
			$form .= "</div>";

			//$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);
			break;
		case "update":
			$number_cols = 3;
			//$field_to_ceck = "present_insert_form_field";
			$field_to_ceck = "present_edit_form_field";
			//$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);


			if ($table_name === $users_table_name && $enable_authentication === 1 && is_ldap_user($where_value)){

				$form .= $normal_messages_ar['ldap_user_dont_update'].'<br/><br/>';

			}


			if ($show_edit_form_after_error === 0) {
				$details_row = fetch_row_db($res_details); // get the values of the details

				if ($details_row === false){
					header('location:'.$site_url.$dadabik_main_file.'?tablename='.urlencode($table_name).'&function=search');
					exit;
				}

				$_SESSION['details_row_back'] = $details_row; // keep it in session, could be useful after submit with error to enable_browse_authorization
			} // end if
			$form .= "<div class=\"save_buttons_container\">";
			if ( $show_top_buttons == 1 && $onlyform === 0) {

				$form .= '<input class="button_form btn btn-primary" type="submit" value="'.$submit_buttons_ar[$form_type].'" onclick="'.$onclick_confirm_part['save'].'" id="form_save_top_btn"> ';




				// pro
				// enterprise
				if ($is_items_table === 1){

		            if ( $GLOBALS['show_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
					    $form .= ' <input type="button" class="button_form btn btn-outline-primary" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?tablename='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&from_back_edit_details_insert_delete_items_table=1\'">';
					}

					if ($form_type === 'update' && $GLOBALS['show_save_plus_back_button'] === 1 && $GLOBALS['hide_back'] === 0){

						$form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="button_form btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar[$form_type].' + '.$submit_buttons_ar['go_back'].'" onclick="'.$onclick_confirm_part['save'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&go_back_after_saving=1\'">';
					}

				}

				else {
				// end pro
				// end enterprise
				    if ( $GLOBALS['show_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
						$form .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-outline-primary" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&tablename='.urlencode($table_name).'\'">';
					}
					if ($form_type === 'update' && $GLOBALS['show_save_plus_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
						$form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar[$form_type].' + '.$submit_buttons_ar['go_back'].'" onclick="'.$onclick_confirm_part['save'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&go_back_after_saving=1\'">';
					}
				// pro
				// enterprise
				}
				// end pro
				// end enterprise

				if ($form_type === 'update' && $enable_insert == '1'){
				    $form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="button_form btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar['insert_as_new'].'" onclick="'.$onclick_confirm_part['insert_as_new'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&function=insert\'">';
				}

				if ($form_type === 'update' && $enable_delete == '1' && $show_delete_button_in_edit === 1){

					$links_ar = build_edit_details_delete_links($table_name, $where_field, $where_value, $enable_edit, $enable_delete, $enable_details, $master_table_name, $master_table_function, $master_table_where_value, $master_table_where_field, $is_items_table, $fields_labels_ar);
					$delete_link = $links_ar['delete_link'];
					
				    $form .= '&nbsp;&nbsp;&nbsp;<a class="button_form btn btn-outline-primary" href="'.$delete_link.'"';

					if ($ask_confirmation_delete === 1){
						$form .= " onclick=\"if (!confirm('".str_replace('\'', '\\\'', $normal_messages_ar['confirm_delete?'])."')){ return false;}\"";
					}
					
					$form .= '>'.$submit_buttons_ar['delete'].'</a>';
				}

			}
			$form .= '&nbsp;&nbsp;'.$buttons_html;
			$form .= "</div>";
			// if there is a field having associated a NULL value, the null_checkbox parameter is set to 1, even if it was 0 in config
			for ($i=0; $i<count($fields_labels_ar); $i++){
				$field_name_temp = $fields_labels_ar[$i]["name_field"];

				if (
								($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')
								||
								(isset($details_row) && is_null($details_row[$field_name_temp]))
							){
					$null_checkbox = 1;
					break;
				}
			}

			break;
		case "search":
			$number_cols = 2;
			$field_to_ceck = "present_search_form_field";
			//$max_number_fields_row = get_max_number_fields_row($fields_labels_ar, $field_to_ceck, $new_line_field);
			if ($select_operator_feature == "1"  && $onlyform === 0){
				$form .= "<div class=\"select_element select_element_search_boolean_operator\"><select name=\"operator\" class=\"form-select\"><option value=\"and\">".$normal_messages_ar["all_conditions_required"]."</option><option value=\"or\">".$normal_messages_ar["any_conditions_required"]."</option></select></div>";
			} // end if
			else{
				$form .= "<input type=\"hidden\" name=\"operator\" value=\"".$default_operator."\">";
			} // end else
			if ( $show_top_buttons == 1 && $onlyform === 0) {

				//$form .= "<br/><input  class=\"button_form\" type=\"submit\" value=\"".$submit_buttons_ar['search_short']." >>\">";

				$form .= '<br/><button class="btn btn-primary" type="submit" id="form_search_top_btn"><i class="fa fa-search"></i> '.$submit_buttons_ar['search_short'].'</button>';
			}

			$form .= "<div class=\"save_buttons_container\">";
			$form .= $buttons_html;
			$form .= "</div>";
			break;
	} // end switch



	if ($form_type === 'insert' || $form_type === 'update') {
		// check if there is at least a not required field in the form or there is a null value in the db
		$form_has_not_requied_field = 0;
		$record_has_null_fields = 0;


		$i=0;
		$count_temp = count($fields_labels_ar);
		while ($i < $count_temp && $form_has_not_requied_field === 0) {
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			//if (($fields_labels_ar[$i]['present_insert_form_field'] === '1' && $form_type === 'insert' || $fields_labels_ar[$i]['present_edit_form_field'] === '1' && $form_type === 'update')  && $fields_labels_ar[$i]['required_field'] === '0') {
			if (($fields_labels_ar[$i]['present_insert_form_field'] === '1' && $form_type === 'insert' || $fields_labels_ar[$i]['present_edit_form_field'] === '1' && $form_type === 'update')  && $fields_labels_ar[$i]['required_field'] === '0' || $fields_labels_ar[$i]['present_edit_form_field'] === '2') {
				$form_has_not_requied_field = 1;
			} // end if

			if ($form_type === 'update'){
				if (($show_edit_form_after_error === 0 && is_null($details_row[$field_name_temp])) || ($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')){
					$record_has_null_fields = 1;
				}
			}


			$i++;
		} // end while

		/*
		if ($form_has_not_requied_field && $null_checkbox === 1 || $form_type === 'update' && $record_has_null_fields === 1) {
			$form .= '<tr>';
				for ($i=0;$i<$max_number_fields_row;$i++){
					$form .= '<td></td><td><span class="null_word">'.$normal_messages_ar['null'].'</td><td></td>';
				}
			$form .= '</tr>';
		} // end if
		*/
	} // end if


    $open_new_line = 1;
    $first_field = 1;
    $fields_in_row_counter = 0;
	for ($i=0; $i<count($fields_labels_ar); $i++){
		if ( ($fields_labels_ar[$i][$field_to_ceck] == "1" || $fields_labels_ar[$i][$field_to_ceck] == "3") && ($fields_labels_ar[$i]['type_field'] !== 'insert_date_time' && $fields_labels_ar[$i]['type_field'] !== 'barcode' && $fields_labels_ar[$i]['type_field'] !== 'qrcode'|| $form_type !== 'insert') && ($fields_labels_ar[$i]['type_field'] !== 'update_date_time' || $form_type !== 'update' && $form_type !== 'insert' )  ) { // the user want to display the field in the form

			$field_name_temp = $fields_labels_ar[$i]["name_field"];

			// make the field disabled if the user asked it (edit yes but disabled) or if it is a calculated field
			if ($fields_labels_ar[$i][$field_to_ceck] == "1"){


				if ( ( $fields_labels_ar[$i]['calculated_function_field'] !== '' || $fields_labels_ar[$i]['formula_field'] !== '') && $form_type !== 'search'){
					$disabled_attribute = ' disabled';
				}
				else{
					$disabled_attribute = '';
				}

			}
			elseif($fields_labels_ar[$i][$field_to_ceck] == "3"){
				$disabled_attribute = ' disabled';
			}

			if ($fields_labels_ar[$i][$separator_before_field] !== ''){
				if ($first_field === 0){ // not the first field, close the previous form_fields_set
					$form .= '</span>';
				}
				$form .= '<div class="form_separator">'.$fields_labels_ar[$i][$separator_before_field].'</div>';
			}

			if ($fields_labels_ar[$i][$separator_before_field] !== '' || $first_field === 1){
				$form .= '<span class="form_fields_set">';
			}

			// similar code in insert_record business_logic
			if (substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){

				$sql_temp = substr_custom($fields_labels_ar[$i]["default_value_field"],4,strlen_custom($fields_labels_ar[$i]["default_value_field"]));

				$res_temp = execute_db($sql_temp, $conn);

				$row_temp = fetch_row_db($res_temp);

				$default_value_field = htmlspecialchars($row_temp[0]);
			}
			elseif(substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 8) === 'dadabik_'){


				$default_value_field = htmlspecialchars(call_user_func($fields_labels_ar[$i]["default_value_field"]));



			}
			else{
				$default_value_field = htmlspecialchars($fields_labels_ar[$i]["default_value_field"]);
			}

			if ($show_edit_form_after_error === 0) {
				// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
				if ($form_type === 'update' && $details_row[$field_name_temp] === ' ') {
					//$details_row[$field_name_temp] = '';
				} // end if
			} // end if

			if ($open_new_line === 1){
                $form .= '<div class="form_row">';
			}

            $fields_in_row_counter++;

			// build the first coloumn (label)
			//////////////////////////////////
			// I put a table inside the cell to get the same margin of the second coloumn
			//$form .= "<tr><td align=\"right\" valign=\"top\"><table><tr><td class=\"td_label_form\">";
			$form .= '<label for="'.$field_name_temp.'" id="'.$field_name_temp.'_label"';
			if ($from_form_configurator_preview === 1){
			    $form .= 'style="cursor: pointer;"';
			}
			$form .=' >';

			$form .= $fields_labels_ar[$i]["label_field"];
			// modreq
			$form .= '<span id="'.$field_name_temp.'_req" class="required_asterisk">';
			if ($fields_labels_ar[$i]["required_field"] == "1" and $form_type != "search"){
				$form .= '*';
			} // end if
			$form .= '</span>';
			// fine modreq
			$form .= "</label>";
			//////////////////////////////////
			// end build the first coloumn (label)

			if ($null_checkbox === 1 && $treat_blank_as_null === 0){

				// build the second coloumn (NULL checkbox)
				//////////////////////////////////

				$form .= '<span class="null_checkbox_cell">';

				switch ($form_type) {
					case 'insert':
						// display the NULL checkbox only if the field is not required
						if ($fields_labels_ar[$i]['required_field'] === '0'){
							//$form .= '<div class="td_null_checkbox_form">';



							// display the null checkbox selected if it is a re-display after an error and the checkbox was selected before submitting
							if ($show_insert_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1'){
								$form .= '<input type="checkbox" name="'.$null_checkbox_prefix.$field_name_temp.'"  title="'.$normal_messages_ar['is_null'].'" value="1" checked onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end if
							else {
								$form .= '<input type="checkbox" name="'.$null_checkbox_prefix.$field_name_temp.'"  title="'.$normal_messages_ar['is_null'].'" value="1" onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end else



							//$form .= '</div>';
						} // end if
						// otherwise build an empty cell
						else {
							//$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
							//$form .= '</td></tr></table></td>';
						} // end else
						break;
					case 'update':
						// display the NULL checkbox only if the field is not required, unless the NULL value is already in the database
						if ($fields_labels_ar[$i]['required_field'] === '0' || ($show_edit_form_after_error === 0 && is_null($details_row[$field_name_temp])) || ($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')){
							//$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';

							/*
							display the null checkbox selected in two cases:
							2) it is a re-display after an error and the checkbox was selected before submitting
							3) the corresponding field in the db is NULL
							*/
							if (
								($show_edit_form_after_error === 1 && isset($_POST_2[$null_checkbox_prefix.$field_name_temp]) && $_POST_2[$null_checkbox_prefix.$field_name_temp] === '1')
								||
								(isset($details_row) && is_null($details_row[$field_name_temp]))
							){
								$form .= '<input'.$disabled_attribute.' type="checkbox" title="'.$normal_messages_ar['is_null'].'" name="'.$null_checkbox_prefix.$field_name_temp.'" value="1" checked onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')"></span>';
							} // end if
							else {
								$form .= '<input'.$disabled_attribute.' type="checkbox" title="'.$normal_messages_ar['is_null'].'" name="'.$null_checkbox_prefix.$field_name_temp.'" value="1" onclick="javascript:enable_disable_input_box_insert_edit_form(\''.$null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix.'\')">';
							} // end else


							//$form .= '</td></tr></table></td>';
						} // end if
						// otherwise build an empty cell
						else {
							//$form .= '<td align="right" valign="top"><table><tr><td class="td_null_checkbox_form">';
							//$form .= '</td></tr></table></td>';
						} // end else
						break;
				} // end switch

				$form .= '</span>';

				//////////////////////////////////
				// end build the second coloumn (NULL checkbox)
			}

			// build the third coloumn (input field)
			/////////////////////////////////////////
			$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];

			if (($fields_labels_ar[$i]['type_field'] === 'select_single' || $fields_labels_ar[$i]['type_field'] === 'select_single_radio' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_menu' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_checkbox') && $primary_key_field_field != ""){
				/*
				if (substr_custom($foreign_key_temp, 0, 4) == "SQL:"){
					$sql = substr_custom($foreign_key_temp, 4, strlen_custom($foreign_key_temp)-4);
				} // end if
				else{
				*/
				$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
				$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
				$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
				$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
				$where_clause_field = $fields_labels_ar[$i]["where_clause_field"];
				$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
				$linked_fields_order_by_field = $fields_labels_ar[$i]["linked_fields_order_by_field"];
				if ($linked_fields_order_by_field !== '' && $linked_fields_order_by_field !== NULL) {
					$linked_fields_order_by_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_order_by_field);
				} // end if
				else {
					unset($linked_fields_order_by_ar);
				} // end else

				$linked_fields_order_type_field = $fields_labels_ar[$i]["linked_fields_order_type_field"];

				$cascade_parent_field = $fields_labels_ar[$i]["cascade_parent_field"];
				$cascade_filter_field = $fields_labels_ar[$i]["cascade_filter_field"];

				if ($cascade_parent_field !== '' && $cascade_filter_field !== ''){

					if ($form_type === 'update') {
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST_2[$cascade_parent_field])){
								$filter_field_value_to_pass = unescape($_POST_2[$cascade_parent_field]);
							} // end if
							else{ // disabled
								$filter_field_value_to_pass = $_SESSION['details_row_back'][$cascade_parent_field];
							}
						} // end if
						else {
							$filter_field_value_to_pass = $details_row[$cascade_parent_field];
						} // end else
					} // end if
					elseif ($form_type === 'insert') {
						if ($show_insert_form_after_error === 1) {
							if (isset($_POST_2[$cascade_parent_field])){
								$filter_field_value_to_pass = unescape($_POST_2[$cascade_parent_field]);
							} // end if
							else{ // disabled
								$filter_field_value_to_pass = NULL;
							}
						} // end if
						else{
							$filter_field_value_to_pass = NULL;
						}
					}
					else{
						$filter_field_value_to_pass = NULL;
					}
				}
				else{
					$filter_field_value_to_pass = NULL;
				}

				if ($form_type == 'update' && $show_edit_form_after_error === 0){
					$details_row_to_pass = $details_row;
				}
				else{
					$details_row_to_pass = NULL;
				}

				$alternative_primary_key_table = NULL;

				if (isset($alternative_primary_key_tables[$table_name][$fields_labels_ar[$i]["name_field"]]) && $form_type !== 'search' ){
				    $alternative_primary_key_table = $alternative_primary_key_tables[$table_name][$fields_labels_ar[$i]["name_field"]];
				}

				// build the query that retrieves the options, it's something link SELECT ID, linked_field_1, linked_field_2, .... FROM linked_table
				$temp_ar = build_dropdown_query($fields_labels_ar[$i], $form_type, $user_group_name, $show_edit_form_after_error, $details_row_to_pass, $filter_field_value_to_pass, $_POST_2,0,NULL,$alternative_primary_key_table);



				$sql = $temp_ar['sql'];
				$sql_res_primary_key_back = $temp_ar['sql_res_primary_key_back'];

				$res_primary_key = execute_db($sql, $conn);

			} // end if
			else{
				$sql_res_primary_key_back = '';
				$res_primary_key = '';
			}

			if ($form_type == "search"){
				$select_type_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 0); // build the select type select form (is_equal....)

				// 18/01/2017 now they are the equals, it doesn't make sense to have a specifc select_type_select for dates
				$select_type_date_select = build_select_type_select($field_name_temp, $fields_labels_ar[$i]["select_type_field"], 0); // build the select type select form (is_equal....) for date fields, with the first option blank
			} // end if
			else{
				$select_type_select = "";
				$select_type_date_select = "";
			} // end else

			//$form .= "<td valign=\"top\"><table border=\"0\"><tr>";

			//****
			$form .= "<span style='vertical-align:text-top' id=\"".$field_button_hint_container_id_prefix.$field_name_temp."\">";


            if ($form_type === 'search'){

                $form .= '<span class="search_operator">';

                if ($fields_labels_ar[$i]["type_field"] === 'date' || $fields_labels_ar[$i]["type_field"] === 'date_time' || $fields_labels_ar[$i]["type_field"] === 'insert_date_time' || $fields_labels_ar[$i]["type_field"] === 'update_date_time'){
                    $form .= $select_type_date_select;
                }
                else{
                    $form .= $select_type_select;
                }

                $form .= '</span>';

			}

						$form .= "<span id=\"".$field_name_temp."\"  class=\"form_input_element\">";


			switch ($fields_labels_ar[$i]["type_field"]){
				case "text":
				case "ID_user":
				case "unique_ID":
				case "date":
				case "date_time":
				case "insert_date_time":
				case "update_date_time":
				case "barcode":
				case "qrcode":
					if ($field_type_for_date === 'date_picker' || ($fields_labels_ar[$i]["type_field"] !== 'date' && $fields_labels_ar[$i]["type_field"] !== 'date_time')){

						if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
							$form .= "<span class=\"tooltip2\">";
						}

						if ($field_name_temp === $users_table_password_field && $table_name === $users_table_name){
						    $form .= "<input".$disabled_attribute." type=\"password\" name=\"".$field_name_temp."\"";
						}
						else{
						    $form .= "<input".$disabled_attribute." type=\"text\" name=\"".$field_name_temp."\"";
						}

						if ($fields_labels_ar[$i]["type_field"] === 'date'){

							$form .= ' id="date_picker_'.$field_name_temp.'"';
						}
						if (in_array($field_name_temp, $not_valid_fields_ar)){
							$form .= ' class="form-control not_valid"';
						}
						else{
							$form .= ' class="form-control"';
						}

						if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
							$temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
							foreach($temp as $value){
								$temp_2 = explode(':', $value);
								$form .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
							}
						}
						if ( in_array($fields_labels_ar[$i]["type_field"], array('date_time','insert_date_time','update_date_time'))){

							$form .= ' id="date_time_picker_'.$field_name_temp.'"';
						}
						if ($fields_labels_ar[$i]["width_field"] != ""){
							$form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";
						} // end if
						else{
							$form .= ' size="30"';
						}
						$form .= " maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";
						if ($form_type == "update" or $form_type == "ext_update"){
							if ($show_edit_form_after_error === 1){
								if (isset($_POST_2[$field_name_temp])) {
									$form .= " value=\"".htmlspecialchars(unescape($_POST_2[$field_name_temp]))."\"";
								} // end if
								else{
                                    if (is_null($_SESSION['details_row_back'][$field_name_temp])){
                                        $form .= ' value=""';
                                    }
                                    else{
									    $form .= " value=\"".htmlspecialchars($_SESSION['details_row_back'][$field_name_temp] ?? '')."\"";
									}
								}
							} // end if
							else {
							    if (is_null($details_row[$field_name_temp])){
							        $form .= ' value=""';
							    }
							    else{
									if ($field_name_temp === $users_table_password_field && $table_name === $users_table_name){
										$form .= ' value=""';
									}
								    else{
										$form .= " value=\"".htmlspecialchars($details_row[$field_name_temp] ?? '')."\"";
									}
								}
							} // end else
						} // end if
						if ($form_type == "insert"){
							if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
								$form .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp])).'"';
							} // end if
							else {
								$form .= " value=\"".htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field."\"";
							} // end else
						} // end if

						$form .= ">";

						if ($fields_labels_ar[$i]["tooltip_field"] !== ''){

							$tooltip_temp = htmLawed($fields_labels_ar[$i]["tooltip_field"], $htmlawed_config);

							$form .= "<span class=\"tooltip2_content\">".$tooltip_temp."</span></span>";
						}

						if (($form_type == "update" or $form_type == "insert") and $fields_labels_ar[$i]["content_field"] == "city"){
							$form .= "<a name=\"".$field_name_temp."\" href=\"#".$field_name_temp."\" onclick=\"javascript:fill_cap('".$field_name_temp."')\">?</a>";
						} // end if

						// between textbox
						if ($form_type == "search" && in_array($fields_labels_ar[$i]["type_field"], array('text', 'date','date_time','insert_date_time','update_date_time','barcode','qrcode'))){

						    $form .= ' <div id="between_textbox_'.$field_name_temp.'" style="display:none;"> '.$normal_messages_ar['between_and'].' <input type="text" ';

						    if ($fields_labels_ar[$i]["width_field"] != ""){
                                $form .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";
                            } // end if

                            if ( in_array($fields_labels_ar[$i]["type_field"], array('date'))){

                                $form .= ' id="date_picker_'.$field_name_temp.'_between____"';
                            }


                            if ( in_array($fields_labels_ar[$i]["type_field"], array('date_time','insert_date_time','update_date_time'))){
                                $form .= ' id="date_time_picker_'.$field_name_temp.'_between____"';
                            }

						    $form .= ' name="'.$field_name_temp.'_between____" maxlength="'.$fields_labels_ar[$i]["maxlength_field"].'"></div>';

						}


						//$form .= "</td>"; // add the second coloumn to the form
					}
					break;
				case "generic_file":
				case "image_file":
				case "camera":
					if ($form_type == "search") { // build a textbox instead of a file input

						if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
							$form .= "<span class=\"tooltip2\">";
						}

						$form .= "<input type=\"text\" name=\"".$field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\">";

					}
					else{
					    if ($enable_uploads === 0){
                            die('Error: you have a file field type in this form but $enable_uploads is set to 0 in config.php, set $enable_uploads = 1 if you want to enable uploads.');
                        }
                        //$form .= "<td class=\"td_input_form\">";

						if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
							$form .= "<span class=\"tooltip2\">";
						}

				if ($upload_field_type === 'ajax'){
					$form .=   '<div ><table style="background-color: rgba(243, 244, 246)"><tr>
                <td valign="top" >
                    <div data-dadabik-uploader
                         data-tablename="'.htmlspecialchars($table_name).'"
                         data-form-type="'.$form_type.'"
                         data-field-name="'.htmlspecialchars($fields_labels_ar[$i]["name_field"]).'"
                         data-id="'.htmlspecialchars($where_value).'"
                         data-fid="'.$fid.'">
                    </div>
                    <div></div></td></tr></table></div>';

                    $form .=   '<template id="uploader-dadabik-form-template">
                    <div class="uploader_container">
                        <div data-uploader-drop-zone class="dropzone">
                            <input';
					
					if ($fields_labels_ar[$i]["type_field"] === 'camera'){
						$form .= " accept=\"image/*\" capture = \"".$camera_capture_value."\"";

					}
							
					$form.= ' id="" data-uploader-input type="file" style="display:none">
                            <div style="display:flex; align-items:center">
                                <label for="" style="margin: 0; padding:0;display:flex; justify-content: space-between;" data-uploader-label>
                                    <div class="upload_button">
                                        <span style="font-weight:bold;">+</span> select file
                                    </div>
                                </label>
                            </div>
                        </div>
                        <progress data-uploader-progress-bar id="progress-bar" style="width: 100%; height:10px" max=100 value=0 ></progress>
                        <div class="uploader_result" id="uploader_result"  data-uploader-result></div>
                    </div>
                </template>';
           }
           $form .= "<div id=\"file_container\"";
           if (in_array($field_name_temp, $not_valid_fields_ar)){
                $form .= ' class="not_valid"';
            }

            $form .= ">";
           if($upload_field_type === 'classic'){

						$form .= "<input";

						if ($form_type == "update"){
							$form .= $disabled_attribute;
						}

						if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
							$temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
							foreach($temp as $value){
								$temp_2 = explode(':', $value);
								$form .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
							}
						}

						if ($fields_labels_ar[$i]["type_field"] === 'camera'){
							$form .= " accept=\"image/*\" capture = \"".$camera_capture_value."\"";

						}

						$form .= " type=\"file\" name=\"".
						  $field_name_temp."\" size=\"".$fields_labels_ar[$i]["width_field"]."\">";
			}
						if ($form_type == "update" or $form_type == "ext_update"){
							if ($show_edit_form_after_error === 0) { // show the current file
								$file_name_temp = $details_row[$field_name_temp];
							}
							else{
								$file_name_temp = $_SESSION['details_row_back'][$field_name_temp];
							}

							if ($file_name_temp != ""){
								if ($file_access_mode === 'public_url'){
									$form .= " ".$normal_messages_ar["current_upload"].": <a href=\"".htmlspecialchars($upload_relative_url);
									$form .= rawurlencode($file_name_temp);
									$form .= "\">";
									$form .= htmlspecialchars($file_name_temp);
									$form .= "</a><br/><input".$disabled_attribute." type=\"checkbox\" value=\"".htmlspecialchars($file_name_temp)."\" name=\"".$field_name_temp."_file_uploaded_delete\"> (".$normal_messages_ar['delete'].")";
								}
								elseif($file_access_mode === 'php'){


									$form .= " <br>".$normal_messages_ar["current_upload"].": <a target=\"".$link_target_generic_file."\" href=\"index.php?r=".time()."&function=show_file&file_type=".$fields_labels_ar[$i]["type_field"]."&tablename=".urlencode($table_name)."&file_field_name=".urlencode($field_name_temp)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."&is_items_table=".urlencode($is_items_table)."&master_table_name=".urlencode($master_table_name);
									$form .= "\">";
									$form .= htmlspecialchars($file_name_temp);
									$form .= "</a><br/><input".$disabled_attribute." type=\"checkbox\" value=\"".htmlspecialchars($file_name_temp)."\" name=\"".$field_name_temp."_file_uploaded_delete\"> (".$normal_messages_ar['delete'].")";

								}
								else{
									echo 'Error';
									exit();
								}

                                if ( ($fields_labels_ar[$i]["type_field"] === 'image_file' || $fields_labels_ar[$i]["type_field"] === 'camera') && $show_images_in_edit_form === 1){


                                    $image_to_display = '<img ';

                                    if ($picture_thumbnail_details_max_width !== 0){
                                        $image_to_display .= ' style="max-width:'.$picture_thumbnail_details_max_width.'px;"';
                                    }

                                    if ($picture_thumbnail_details_max_height !== 0){
                                        $image_to_display .= ' style="max-height:'.$picture_thumbnail_details_max_height.'px;"';
                                    }

                                    $image_to_display .= " src=\"index.php?r=".time()."&function=show_file&file_type=".$fields_labels_ar[$i]["type_field"]."&tablename=".urlencode($fields_labels_ar[$i]["table_name"])."&tablename_original=".urlencode($table_name)."&file_field_name=".urlencode($fields_labels_ar[$i]["name_field"])."&master_table_name=".urlencode($master_table_name)."&is_items_table=".urlencode($is_items_table)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."\">";

                                    $form .= '<br>'.$image_to_display;
                                }





								$form .= "<input type=\"hidden\" value=\"1\" name=\"".$field_name_temp."_file_available\">";
							} // end if
							else {
								$form .= "<input type=\"hidden\" value=\"0\" name=\"".$field_name_temp."_file_available\">";
							} // end else
						} // end if


						$form .= "</div>";
						/*	 fine parte vecchia */
					}


					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
						$tooltip_temp = htmLawed($fields_labels_ar[$i]["tooltip_field"], $htmlawed_config);
						$form .= "<span class=\"tooltip2_content\">".$tooltip_temp."</span></span>";
					}
					//$form .= "</td>";
					break;
				case "textarea":


					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
						$form .= "<span class=\"tooltip2\">";
					}

					$form .= "<textarea";
					if ($form_type == "update"){
						$form .= $disabled_attribute;
					}
					if (in_array($field_name_temp, $not_valid_fields_ar)){
						$form .= ' class="form-control not_valid"';
					}
					else{
						$form .= ' class="form-control"';
					}
					if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
						$temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
						foreach($temp as $value){
							$temp_2 = explode(':', $value);
							$form .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
						}
					}

					$rows = 4;

					if ($fields_labels_ar[$i]["height_field"] !== ''){
                        $rows = $fields_labels_ar[$i]["height_field"];
					}

					$cols = 30;
					if ($fields_labels_ar[$i]["width_field"] !== ''){
						$cols = $fields_labels_ar[$i]["width_field"];
					}

					$form .= " cols=\"".$cols."\"";

					$form .= " rows=\"".$rows."\" name=\"".$field_name_temp."\"";

					$form .= ">";

					if ($form_type == "update" or $form_type == "ext_update"){
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST_2[$field_name_temp])) {
								$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
							} // end if
							else{
								$form .= htmlspecialchars($_SESSION['details_row_back'][$field_name_temp] ?? '');
							}
						} // end if
						else {
							$form .= htmlspecialchars($details_row[$field_name_temp] ?? '');
						} // end else
					} // end if
					if ($form_type == "insert"){

						if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
							$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
						} // end if
						else {
							$form .= htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field;
						} // end else

					} // end if

					$form .= "</textarea>";

					if ($fields_labels_ar[$i]["tooltip_field"] !== ''){
						$tooltip_temp = htmLawed($fields_labels_ar[$i]["tooltip_field"], $htmlawed_config);
						$form .= "<span class=\"tooltip_content\">".$tooltip_temp."</span></span>";
					}

					//$form .= "</td>";
					break;
				case "rich_editor":
					$form .= "<div id=\"rich_editor_container\"";

					if (in_array($field_name_temp, $not_valid_fields_ar)){
						$form .= ' class="not_valid"';
					}

					$form .= ">";
					//$form .= "<td class=\"td_input_form\"><textarea cols=\"".$fields_labels_ar[$i]["width_field"]."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\">";

					$cols = 30;
					if ($fields_labels_ar[$i]["width_field"] !== ''){
						$cols = $fields_labels_ar[$i]["width_field"];
					}

					if ($form_type === 'search'){ // just a normal textarea if it is a search form
						$form .= "<textarea cols=\"".$cols."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\">";
					}
					else{
						//$form .= "<td class=\"td_input_form\">";

						if ($disabled_attribute === ''){


							$form .= "<textarea cols=\"".$cols."\" rows=\"".$fields_labels_ar[$i]["height_field"]."\" name=\"".$field_name_temp."\"";


							if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
								$temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
								foreach($temp as $value){
									$temp_2 = explode(':', $value);
									$form .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
								}
							}
							$form .= " class=\"rich_editor\">";


						}
					}

					if ($form_type == "update" or $form_type == "ext_update"){
						if ($show_edit_form_after_error === 1) {
							if (isset($_POST_2[$field_name_temp])) {

								$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));


							} // end if
							else{ // it means it was disabled
								$form .= htmLawed($_SESSION['details_row_back'][$field_name_temp], $htmlawed_config);
							}
						} // end if
						else {



							if ($disabled_attribute !== ''){
								$form .= htmLawed($details_row[$field_name_temp], $htmlawed_config);
							}
							else{
								$form .= htmlspecialchars($details_row[$field_name_temp] ?? '');
							}



						} // end else
					} // end if
					if ($form_type == "insert"){

						if ($show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp])) {
							$form .= htmlspecialchars(unescape($_POST_2[$field_name_temp]));
						} // end if
						else {
							$form .= htmlspecialchars($fields_labels_ar[$i]["prefix_field"]).$default_value_field;
						} // end else

					} // end if
					if ($disabled_attribute === ''){
						$form .= "</textarea>"; // add the second coloumn to the form
					}
					else{
						$form .= '';
					}
					$form .= "</div>";
					//$form .= "<script language=\"javascript1.2\">editor_generate('".$field_name_temp."');</script>";
					break;
				case "select_multiple_menu":
				case "select_multiple_checkbox":


					if ($fields_labels_ar[$i]["type_field"] === 'select_multiple_checkbox' || $fields_labels_ar[$i]["type_field"] === 'select_multiple_menu' && $fields_labels_ar[$i]["chosen_field"] == '1' ){ // we need a container for the required red border, the normal approach doesn't work with select2 and we also need it for checkboxes

						$form .= '<div';
						if ( in_array($field_name_temp, $not_valid_fields_ar)  ){
							$form .= ' class="not_valid"';
						}
						$form .= '>';
					}

					if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
						$form .= "<select data-placeholder=\" \"";

						if ($form_type == "update"){
							$form .= $disabled_attribute;
						}

						if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
							$temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
							foreach($temp as $value){
								$temp_2 = explode(':', $value);
								$form .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
							}
						}

						$form .= " name=\"".$field_name_temp."[]\" size=".$size_multiple_select." multiple class=\"select_element form-select";

						if ($fields_labels_ar[$i]["chosen_field"] == '1'){
							$form .= " searchable_select";
						}
						elseif(in_array($field_name_temp, $not_valid_fields_ar)){
							$form .= ' not_valid"';
						}

						$form .= '"><option disabled></option>';
					} // end if

					if ($fields_labels_ar[$i]["primary_key_field_field"] == ""){ // no linked, just hardcoded

						if ( $fields_labels_ar[$i]["select_options_field"] != "") {
							$options_labels_temp = substr_custom($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator

							$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$options_labels_temp);

							$select_labels_ar_number = count($select_labels_ar);

							for ($j=0; $j<$select_labels_ar_number; $j++){
								if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
									$form .= "<option value=\"".htmlspecialchars($select_labels_ar[$j])."\"";
								} // end if
								elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
									$form .= "<input";
									if ($form_type == "update"){
										$form .= $disabled_attribute;
									}
									$form .= " type=\"checkbox\" name=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($select_labels_ar[$j])."\"";
								} // end elseif

								if ($form_type == "update" or $form_type == "ext_update"){
									$select_values_ar = array();
									if ($show_edit_form_after_error === 1) {
										//$options_values_temp = substr_custom(unescape($_POST[$field_name_temp]), 1, -1); // delete the first and the last separator
										if (isset($_POST_2[$field_name_temp])){
											$options_values_temp = unescape($_POST_2[$field_name_temp]);
											$select_values_ar = $options_values_temp;
										}
										else{
										    if ($disabled_attribute!==''){
											$options_values_temp = $_SESSION['details_row_back'][$field_name_temp];
											$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
											}
										}
									}
									else{
										if ($details_row[$field_name_temp] !== '' && $details_row[$field_name_temp] !== NULL){
											$options_values_temp = $details_row[$field_name_temp];
											$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
										}
									}

									if ( in_array($select_labels_ar[$j],$select_values_ar )) {
										if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
											$form .= " selected";
										} // end if
										elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
											$form .= " checked";
										} // end elseif
									}

								}

								if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) ){


									//$options_values_temp = substr_custom(unescape($_POST[$field_name_temp]), 1, -1); // delete the first and the last separator
									$options_values_temp = unescape($_POST_2[$field_name_temp]);

									//$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
									$select_values_ar = $options_values_temp;

									if ( in_array($select_labels_ar[$j], $select_values_ar)) {
										if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
											$form .= " selected";
										} // end if
										elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
											$form .= " checked";
										} // end elseif
									}

								} // end if


								if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
									$form .= "> ".htmlspecialchars($select_labels_ar[$j])."</option>";
								} // end if
								elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
									$form .= "> ".htmlspecialchars($select_labels_ar[$j])."<br>";
								} // end elseif
							} // end for - central part of the form row
						} // end if
					}
					else{
						if (get_num_rows_db($sql_res_primary_key_back, 1) > 0){
							$fields_number = num_fields_db($res_primary_key);

							if ( $fields_labels_ar[$i]["chosen_field"] == 1 && $fields_labels_ar[$i]["chosen_ajax_field"] == 1 && $fields_labels_ar[$i]["type_field"] === 'select_multiple_menu'){ // it's a lookup field with ajax data, we show options  only if it has an already selected options

							   $show_selected_value = 0; // flag, 1 if we need to show an option
							   if ($form_type === 'update') {
									if ($show_edit_form_after_error === 1) {
										if (isset($_POST_2[$field_name_temp])){

											$options_values_temp = unescape($_POST_2[$field_name_temp]);
											$select_values_ar = $options_values_temp;
											$show_selected_value = 1;
										} // end if
										else{ // it happens if the field is disabled

											if( $disabled_attribute !== ''){
											$options_values_temp = substr_custom($_SESSION['details_row_back'][$field_name_temp], 1, -1); // delete the first and the last separator
											$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
											$show_selected_value = 1;
											}
										}
									} // end if
									else {

										$options_values_temp = substr_custom($details_row[$field_name_temp], 1, -1); // delete the first and the last separator
										$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
										$show_selected_value = 1;
									} // end else
								} // end if

								elseif ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) ){

									$options_values_temp = unescape($_POST_2[$field_name_temp]);

									//$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
									$select_values_ar = $options_values_temp;


									$show_selected_value = 1;
								} // end if


								// if we have a value, but it's '' or NULL we don't have to add the option, by default a first blank option is always displayed
								if ($show_selected_value === 1 && ($options_values_temp === '' || is_null($options_values_temp))){
									$show_selected_value = 0;
								}

								if ($show_selected_value === 1){ // we need to show an option, we know the ID but not the linked values

									// number of linked fields
									$fields_number = num_fields_db($res_primary_key);

									// query to get the linked field values
									$sql =  $sql_res_primary_key_back." AND ";

									foreach($select_values_ar as $key => $select_values_ar_element){
										$sql .= "(".$quote.$fields_labels_ar[$i]["primary_key_field_field"].$quote." = :id_value_to_show_".$key.") OR ";
									}

									$sql = substr_custom($sql, 0, -4); // delete the last " OR "

									$res_prepare = prepare_db($conn, $sql);

									$values_to_bind = array();

									foreach($select_values_ar as $key => $select_values_ar_element){

										$values_to_bind['id_value_to_show_'.$key] = $select_values_ar_element;
									}

									foreach ($values_to_bind as $key => $value)
									{
										$res_bind = bind_param_db($res_prepare, ':'.$key, $value);
									}

									$res = execute_prepared_db($res_prepare,0);

									while($row = fetch_row_db($res_prepare)){
										$desc_value_to_show = "";
										for ($z=1; $z<$fields_number; $z++){
											$desc_value_to_show .= $row[$z];
											//$desc_value_to_show .= " - ";
											$desc_value_to_show .= $separator_display_linked_field_2;
										} // end for
										$desc_value_to_show = substr_custom($desc_value_to_show, 0, -($lenght_separator_display_linked_field_2)); // delete the last " -

										$form .= "<option value=\"".htmlspecialchars($row[0])."\"";
										$form .= " selected>".htmlspecialchars($desc_value_to_show)."</option>";
									}
								}

							}
							else{

								while ($primary_key_row = fetch_row_db($res_primary_key)){

									$primary_key_value = $primary_key_row[0];
									$linked_fields_value = "";
									for ($z=1; $z<$fields_number; $z++){
										$linked_fields_value .= $primary_key_row[$z];
										//$linked_fields_value .= " - ";
										$linked_fields_value .= $separator_display_linked_field_2;
									} // end for
									$linked_fields_value = substr_custom($linked_fields_value, 0, -($lenght_separator_display_linked_field_2)); // delete the last " -

									if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
										$form .= "<option value=\"".htmlspecialchars($primary_key_value)."\"";
									} // end if
									elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
										$form .= "<input";
										if ($form_type == "update"){
											$form .= $disabled_attribute;
										}
										$form .= " type=\"checkbox\" name=\"".$field_name_temp."[]\" value=\"".htmlspecialchars($primary_key_value)."\"";
									} // end elseif

									if ($form_type == "update" or $form_type == "ext_update"){
										$select_values_ar = array();
										if ($show_edit_form_after_error === 1) {
											//$options_values_temp = substr_custom(unescape($_POST[$field_name_temp]), 1, -1); // delete the first and the last separator
											if (isset($_POST_2[$field_name_temp])){
												$options_values_temp = unescape($_POST_2[$field_name_temp]);
												$select_values_ar = $options_values_temp;
											}
											else{
											    if($disabled_attribute !==''){
												if ($_SESSION['details_row_back'][$field_name_temp] !== ''){
													$options_values_temp = substr_custom($_SESSION['details_row_back'][$field_name_temp], 1, -1); // delete the first and the last separator
													$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
												}
												}
											}
										}
										else{
											if ($details_row[$field_name_temp] !== ''){
												$options_values_temp = substr_custom($details_row[$field_name_temp], 1, -1); // delete the first and the last separator
												$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
											}
										}

										if ( in_array($primary_key_value, $select_values_ar)) {
											if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
												$form .= " selected";
											} // end if
											elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
												$form .= " checked";
											} // end elseif
										}

									} // end if


									if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) ){

										$options_values_temp = unescape($_POST_2[$field_name_temp]);

										//$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$options_values_temp);
										$select_values_ar = $options_values_temp;

										if ( in_array($primary_key_value, $select_values_ar)) {
											if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
												$form .= " selected";
											} // end if
											elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
												$form .= " checked";
											} // end elseif
										}
									} // end if

									if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
										$form .= ">".htmlspecialchars($linked_fields_value)."</option>"; // second part of the form row
									} // end if
									elseif ($fields_labels_ar[$i]["type_field"] == "select_multiple_checkbox"){
										$form .= ">".htmlspecialchars($linked_fields_value)."<br>"; // second part of the form row
									} // end elseif

								} // end while
							}

						} // end if
					} // end if ($fields_labels_ar[$i]["foreign_key_field"] != "")

					if ($fields_labels_ar[$i]["type_field"] == "select_multiple_menu"){
						$form .= "</select>";
					} // end if

					//$form .= "</td>"; // last part of the second coloumn of the form

					if ($fields_labels_ar[$i]["type_field"] === 'select_multiple_checkbox' || $fields_labels_ar[$i]["type_field"] === 'select_multiple_menu' && $fields_labels_ar[$i]["chosen_field"] == '1' ){ // we need a container for the required red border, the normal approach doesn't work with select2 and we also need it for checkboxes
					    $form .= '</div>'; // close container

					}
					break;

				case "select_single":
				case "select_single_radio":

					if ($form_type == 'update' && $show_edit_form_after_error === 0){
						$details_row_to_pass = $details_row;
					}
					else{
						$details_row_to_pass = NULL;
					}


					$form_element = build_form_element($fields_labels_ar, $i, $_POST_2, $details_row_to_pass, $form_type, $disabled_attribute, $set_field_default_value, $default_value_field_name, $default_value, $sql_res_primary_key_back, $res_primary_key, $show_edit_form_after_error, $show_insert_form_after_error, $user_group_name, $not_valid_fields_ar);



					$form .= $form_element;



					break;
			} // end switch

			$form .= "</span>"; // last part of the second coloumn of the form
			/////////////////////////////////////////
			// end build the third coloumn (input field)

			$buttons_html = '';

			// for select_single lookup, show the + button if the current user has insert permissions and if it's not already a popup insert form
			if ($enable_lookup_insert_popup === 1 && ($form_type === 'insert' || $form_type === 'update') &&  $fields_labels_ar[$i]["primary_key_field_field"] !== '' && $fields_labels_ar[$i]["type_field"] === 'select_single' && $lookup_insert_popup === 0){

				// don't do it for the FK lookup of an items table, that is disabled
				if($form_type !== 'insert' || ( $set_field_default_value === 0 || $field_name_temp !== $default_value_field_name)){

					if ($enable_granular_permissions === 1 && $enable_authentication === 1){

						$enabled_features_ar_2_linked = build_enabled_features_ar_2($fields_labels_ar[$i]["primary_key_table_field"]);
						$enable_insert_linked = (int)$enabled_features_ar_2_linked['insert'];

					}
					elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){

						$enabled_features_ar_linked = build_enabled_features_ar($fields_labels_ar[$i]["primary_key_table_field"]);
						$enable_insert_linked = (int)$enabled_features_ar_linked['insert'];
					}
					if ($enable_insert_linked === 1){

						// don't show the + button if we are in a edit form and the field has edit permissions "yes but disabled"
						if ($form_type !== 'update' || $fields_labels_ar[$i][$field_to_ceck] != "3"){
							$buttons_html .= '<button type="button" class="btn btn-icon btn-primary" onclick="javascript:void(generic_js_popup(\'index.php?tablename='.urlencode($fields_labels_ar[$i]["primary_key_table_field"]).'&function=show_insert_form&modal_mode=1&lookup_insert_popup=1&lookup_insert_popup_table_from='.urlencode($table_name).'&lookup_insert_popup_field_from='.urlencode($fields_labels_ar[$i]["name_field"]).'&lookup_insert_popup_linked_field_from='.urlencode($fields_labels_ar[$i]["linked_fields_field"]).'&lookup_insert_popup_pk_field_from='.urlencode($fields_labels_ar[$i]["primary_key_field_field"]).'\',\'\',800,500));return false;" id="plus_btn_'.$field_name_temp.'"><b>+</b></button>';
						}
					}

				}
			}


			$custom_buttons_to_display = get_custom_buttons_to_display($table_name, $fields_labels_ar, $button_show_in, $fields_labels_ar[$i]['name_field'], 1, unescape($where_field), unescape($where_value));

			if (count($custom_buttons_to_display) > 0){
				foreach($custom_buttons_to_display as $button){
				    $id_attribute = '';
                    if (isset($button['id'])){
                        $id_attribute = ' id="'.htmlspecialchars($custom_button_ids_prefix.$button['id']).'"';
                    }
					$buttons_html .= ' <input '.$id_attribute.' type="button" class="button_form btn btn-secondary custom_button" style="'.$button['style'].'" value="'.$button['label'].'"';

					$url_to_call = $dadabik_main_file."?function=execute_custom_function&tablename=".urlencode($table_name)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."&custom_function=".urlencode($button['callback_function'])."&name_button=".urlencode($button['name']);

					$buttons_html = $buttons_html . get_custom_button_onclick_part($button, $url_to_call);
				}
			}

			$form .= '<span class="form_input_element_button">'.$buttons_html.'</span>';

			if ($table_name === $users_table_name ){ // overwrite the hint 
				if ($fields_labels_ar[$i]["name_field"] === 'password_user' && $form_type === 'update'){
					$fields_labels_ar[$i]["hint_insert_field"] = $normal_messages_ar['leave_blank_keep_current_password'];
				}
				elseif ($fields_labels_ar[$i]["name_field"] === 'force_password_change_user'){
					$fields_labels_ar[$i]["hint_insert_field"] = $normal_messages_ar['users_will_be_forced_change_after_login_except_ldap'];
				}

				
			}

			if ($form_type == "insert" or $form_type == "update" or $form_type == "ext_update"){


				$form .= '<span class="form_hint">'.$fields_labels_ar[$i]["hint_insert_field"].'</span>'; // display the insert hint if it's the insert form




			} // end if
			//$form .= "</tr></table></td>";
			// *****
			$form .= "</span>"; // close span containing field, hint and buttons
            if ($fields_labels_ar[$i][$new_line_field] === '1'){
            	$form .= '</div>'; //close the previous div class ROW
                $open_new_line = 1;


                /*


                for ($j=0;$j<($max_number_fields_row-$fields_in_row_counter);$j++){
                	if (($form_type === 'insert' || $form_type === 'update') && $treat_blank_as_null === 0 &&  ($form_has_not_requied_field && $null_checkbox === 1 || $form_type === 'update' && $record_has_null_fields === 1)) {
                    	$form .= '<td align="right" valign="top"><table><tr><td class="td_label_form"></td></tr></table></td><td align="right" valign="top"><table><tr><td class="td_null_checkbox_form"></td></tr></table></td><td><table border="0"><tr><td class="td_input_form"></td><td class="td_hint_form"></td></tr></table></td>';
					}
					else{
						$form .= '<td align="right" valign="top"><table><tr><td class="td_label_form"></td></tr></table></td><td><table border="0"><tr><td class="td_input_form"></td><td class="td_hint_form"></td></tr></table></td>';
					}

                }
                $form .= "</tr>";
                 $fields_in_row_counter = 0;
                */

            }
            else{
                $open_new_line = 0;
            }

            if ($first_field === 1){
				$first_field = 0;
			}

		} // end if ($fields_labels_ar[$i]["$field_to_ceck"] == "1")
		else{ // even for hidden fields, if we have to open a new line, do it
		    if ($fields_labels_ar[$i][$new_line_field] === '1'){
            	$form .= '</div>'; //close the previous div class ROW
                $open_new_line = 1;
            }
            else{
                $open_new_line = 0;
            }
		}


	} // enf for loop for each field in the label array

	 $form .= '</div>'; // close the last div class ROW

	 $form .= '</span>'; // close the last form_fields_set

	if ($onlyform === 0){

	    if ($form_type === 'search'){
	        $form .= '<div class="save_buttons_container"><button class="btn btn-primary" type="submit" id="form_search_bottom_btn"><i class="fa fa-search"></i> '.$submit_buttons_ar['search_short'].'</button>';
	    }
	    elseif ($form_type === 'insert' && $use_old_insert_form_button_label === 0 ){
	        $form .= '<div class="save_buttons_container"><input type="submit" class="button_form btn btn-primary" value="'.$submit_buttons_ar['update'].'" onclick="'.$onclick_confirm_part['save'].'" id="form_save_bottom_btn">';
	    }
	    else{
            $form .= '<div class="save_buttons_container"><input type="submit" class="button_form btn btn-primary" value="'.$submit_buttons_ar[$form_type].'" onclick="'.$onclick_confirm_part['save'].'" id="form_save_bottom_btn">';
        }

        if ($is_items_table === 1){
            if ( $GLOBALS['show_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
                $form .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-outline-primary" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?tablename='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&from_back_edit_details_insert_delete_items_table=1\'">';
            }

            if ($form_type === 'update' && $GLOBALS['show_save_plus_back_button'] === 1 && $GLOBALS['hide_back'] === 0){

                $form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="button_form btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar[$form_type].' + '.$submit_buttons_ar['go_back'].'" onclick="'.$onclick_confirm_part['save'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&go_back_after_saving=1\'">';
            }
        }

        else {
            if ($enable_browse === '1' && $lookup_insert_popup === 0){
                if ( $GLOBALS['show_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
                    $form .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-outline-primary" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&tablename='.urlencode($table_name).'\'">';
                }
            }
            if ($form_type === 'update' && $GLOBALS['show_save_plus_back_button'] === 1 && $GLOBALS['hide_back'] === 0){
                $form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="button_form btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar[$form_type].' + '.$submit_buttons_ar['go_back'].'" onclick="'.$onclick_confirm_part['save'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&go_back_after_saving=1\'">';
            }
        }

        if ($form_type === 'update' && $enable_insert == '1'){
            $form .= '&nbsp;&nbsp;&nbsp;<input type="submit" class="button_form btn btn-outline-primary save_go_back" value="'.$submit_buttons_ar['insert_as_new'].'" onclick="'.$onclick_confirm_part['insert_as_new'].'javascript:document.getElementById(\'dadabik_main_form\').action=document.getElementById(\'dadabik_main_form\').action + \'&function=insert\'">';
        }

		if ($form_type === 'update' && $enable_delete == '1' && $show_delete_button_in_edit === 1){

			$links_ar = build_edit_details_delete_links($table_name, $where_field, $where_value, $enable_edit, $enable_delete, $enable_details, $master_table_name, $master_table_function, $master_table_where_value, $master_table_where_field, $is_items_table, $fields_labels_ar);
			$delete_link = $links_ar['delete_link'];
			
			$form .= '&nbsp;&nbsp;&nbsp;<a class="button_form btn btn-outline-primary" href="'.$delete_link.'"';

			if ($ask_confirmation_delete === 1){
				$form .= " onclick=\"if (!confirm('".str_replace('\'', '\\\'', $normal_messages_ar['confirm_delete?'])."')){ return false;}\"";
			}
			
			$form .= '>'.$submit_buttons_ar['delete'].'</a>';
		}

        if ($show_multiple_inserts_checkbox === 1 && $show_details_after_insert === 0 && $lookup_insert_popup === 0 && ($form_type === 'update' || $form_type === 'insert')){
            $form .= '<br/><br/><input type="checkbox" name="multiple_inserts'.$alias_prefix.'" value="1"';
            if ($edit_from_multiple_insert === 1){
                $form .= ' checked';
            }
            $form .= '> '.$submit_buttons_ar['multiple_inserts'];
        }
 	}

	//$form .= $buttons_html;
	$form.= "</div>";

	$form.= "</form>";
	return $form;
} // end build_form function



?>
