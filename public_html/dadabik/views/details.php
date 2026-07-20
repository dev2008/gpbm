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
// shares some core with build_csv, build_json, get_calendar_field_value, build_results_table
function build_details_table($fields_labels_ar, $res_details, $table_name, $is_items_table, $just_inserted, $master_table_name, $master_table_function, $master_table_where_field, $master_table_where_value, $where_field, $where_value, $output = 'web_details', $row_already_fetched = 0, $return_field_values_instead_of_table = 0, $enable_edit = 0, $show_revisions = 0, $onlyform = 0, $from_back_edit_details_insert_delete_items_table = 0)
// goal: build an html table with details of a record
// input: $fields_labels_ar $res_details the recordset result of the query, $row_already_fetched is 1 when called from build_results_table, in this case $res_details is not a rcordset but an already fetched row. $output can be web_details (normal details page), email, web_results (results grid responsive version) or PDF
// ouptut: $details_table, the html table
{
	global $conn, $quote, $alias_prefix, $prefix_internal_table, $submit_buttons_ar, $normal_messages_ar, $dadabik_main_file, $show_top_buttons, $separator_display_select_multiple, $separator_display_linked_field, $function_link_to_record, $site_url, $export_to_pdf_feature, $dir_templates, $enable_edit, $show_edit_button_in_details, $custom_button_ids_prefix, $from_form_configurator_preview, $use_conditional_rules_for_details_pdf,$table_pdf_templates;
	

	// build the table
	$details_table = "";
	
	if ($output === 'web_results'){
		$display_mode = 'details_table_for_results';
		$presence_field_to_check = 'present_results_search_field';
	}
	elseif ($output === 'web_details' || $output === 'PDF'){
		$display_mode = 'details_table';
		$presence_field_to_check = 'present_details_form_field';
	}
	elseif ($output === 'email'){
		$separator_display_select_multiple = "\n";
		$presence_field_to_check = 'present_details_form_field';
		$display_mode = 'plain_text';
	}
	
	if ( $export_to_pdf_feature == 1) {
	
		$files = scandir('./'.$dir_templates);

		$templates_cnt = 0;
		$assigned_templates_cnt = 0;
		$value_pdf_template_no_menu = '';
		$label_pdf_button_no_menu = 'PDF';
	
		$templates_menu = '<select name="pdf_template" class="form-select" id="pdf_template" style="visibility:hidden;display:inline-block;"><option value="" disabled selected>'.$normal_messages_ar['choose_pdf_template'].'</option>';
		$templates_menu_2 = '<select name="pdf_template" class="form-select" id="pdf_template_2" style="visibility:hidden;display:inline-block;"><option value="" disabled selected>'.$normal_messages_ar['choose_pdf_template'].'</option>';

		if (!isset($table_pdf_templates[$table_name])){
			$templates_menu .= '<option value="">'.$normal_messages_ar['no_pdf_template'].'</option>';
			$templates_menu_2 .= '<option value="">'.$normal_messages_ar['no_pdf_template'].'</option>';
		}
		
		foreach($files as $file){ 
			// it's ok to not use _custom here
			if (substr($file, -5) === '.html'){
				$file_without_html = substr($file, 0, -5);
				if ($file_without_html !== 'default_footer_no_template' && $file_without_html !== 'default_header_no_template' && substr($file, -11) !== 'header.html' && substr($file, -11) !== 'footer.html'){

					if (!isset($table_pdf_templates[$table_name])){
						$templates_menu .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
						$templates_menu_2 .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
						$templates_cnt++;
					}
					else{
						$template_key = array_search($file_without_html, $table_pdf_templates[$table_name]['template_names']);

						if($template_key !== false){
							$templates_menu .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
							$templates_menu_2 .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
							$templates_cnt++;
							$assigned_templates_cnt++;
							$value_pdf_template_no_menu = $file_without_html;
							if (isset($table_pdf_templates[$table_name]['button_labels'][$template_key])){
								$label_pdf_button_no_menu = $normal_messages_ar[$table_pdf_templates[$table_name]['button_labels'][$template_key]];
							}
							
						}
					}
				}
			}
			
            if (substr($file, -4) === '.php' && substr($file, -10) !== 'header.php' && substr($file, -10) !== 'footer.php' && substr($file, -13) !== '_settings.php'){
                $file_without_html = substr($file, 0, -4);
                if ($file_without_html !== 'default_footer_no_template' && $file_without_html !== 'default_header_no_template'){
                    if (!isset($table_pdf_templates[$table_name])){
						$templates_menu .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
						$templates_menu_2 .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
						$templates_cnt++;
					}
					else{
						$template_key = array_search($file_without_html, $table_pdf_templates[$table_name]['template_names']);

						if($template_key !== false){
							$templates_menu .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
							$templates_menu_2 .= '<option value="'.htmlspecialchars($file_without_html).'">'.htmlspecialchars($file_without_html).'</option>';
							$templates_cnt++;
							$assigned_templates_cnt++;
							$value_pdf_template_no_menu = $file_without_html;
							if (isset($table_pdf_templates[$table_name]['button_labels'][$template_key])){
								$label_pdf_button_no_menu = $normal_messages_ar[$table_pdf_templates[$table_name]['button_labels'][$template_key]];
							}
							
						}
					}
                }
            }
		}
		$templates_menu .= '</select>';
		$templates_menu_2 .= '</select>';


	}

	
	if ($output === 'web_details'){
	
		$buttons_html = '';
		
		if ($onlyform === 0){
            $custom_buttons_to_display = get_custom_buttons_to_display($table_name, $fields_labels_ar, 'details_page', 'top', 1, unescape($where_field), unescape($where_value));
                
            if (count($custom_buttons_to_display) > 0){
                foreach($custom_buttons_to_display as $key => $button){

					/*$margin_left_style = '';
					if ($key === 0){
						$margin_left_style = 'margin-left:0px;';
					}*/
                    $id_attribute = '';
                    if (isset($button['id'])){
                        $id_attribute = ' id="'.htmlspecialchars($custom_button_ids_prefix.$button['id']).'"';
                    }
                    $buttons_html .= ' <input'.$id_attribute.' type="button" class="button_form btn btn-secondary custom_button" style="'.$button['style'].'" value="'.$button['label'].'"';
            
                    $url_to_call = $dadabik_main_file."?function=execute_custom_function&tablename=".urlencode($table_name)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."&custom_function=".urlencode($button['callback_function'])."&name_button=".urlencode($button['name']);
            
                    $buttons_html = $buttons_html . get_custom_button_onclick_part($button, $url_to_call);
                }	
            }
        }
	
		if ( $show_top_buttons == 1 && $onlyform === 0) {
			$details_table .= '<form target="_blank" class="css_form" method="POST" action="'.$dadabik_main_file.'?tablename='.urlencode($table_name).'&function=details&export_to_pdf=1&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'">';
			if ($is_items_table === 1 && ($just_inserted === 0 || $from_back_edit_details_insert_delete_items_table === 1)){
			
				$details_table .= '<input type="button" class="button_form  btn btn-outline-primary go_back" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?tablename='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&from_back_edit_details_insert_delete_items_table=1\'">';
			}
			elseif ($just_inserted === 0  || $from_back_edit_details_insert_delete_items_table === 1){
		
				$details_table .= '<input type="button" class="button_form btn btn-outline-primary go_back" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&tablename='.urlencode($table_name).'\'">';
			}
			
			if ($enable_edit == "1" && $show_edit_button_in_details === 1){
		
                $details_table .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-primary" value="'.$submit_buttons_ar['edit'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=edit&tablename='.urlencode($table_name).'&where_field='.urlencode($where_field).'&where_value='.urlencode(unescape($where_value));
        
        
                if ($is_items_table === 1) {
        
                    $details_table .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
            
                }
            
                $details_table .= '\'" id="details_page_edit_top_btn">';
        
            }
			
			
			if ( $export_to_pdf_feature == 1) {

				if ($templates_cnt === 0 || $assigned_templates_cnt === 1 ){

					$details_table .= '&nbsp;&nbsp;&nbsp;<input type="hidden" name="pdf_template" value="'.htmlspecialchars($value_pdf_template_no_menu).'"><button type="submit" id="produce_pdf_button" class="btn btn-primary btn-sm"><li class="fa fa-file-pdf-o fa-lg"></li>&nbsp;&nbsp;'.htmlspecialchars($label_pdf_button_no_menu).'</button>';
				}
				else{
					$details_table .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-primary" value="PDF" id="pdf_button" onclick=" document.getElementById(\'pdf_template\').style.visibility = \'visible\';document.getElementById(\'pdf_button\').style.display = \'none\'; document.getElementById(\'produce_pdf_button\').style.visibility = \'visible\';">'.$templates_menu.' <input type="submit" id="produce_pdf_button" value="'.$normal_messages_ar['produce_pdf'].'" style="visibility:hidden;display:inline-block;" class="btn btn-primary ">';
				}
			}
			$details_table .= '</form>';
			
		}
		$details_table .= '<br>'.$buttons_html;
		
	}
	
	
	if ($row_already_fetched === 0){
		$details_row = fetch_row_db($res_details);
		

		if ($details_row === false){
			header('location:'.$site_url.$dadabik_main_file.'?tablename='.urlencode($table_name).'&function=search');
			exit;
		}
	}
	else{
		$details_row = $res_details;
	}
	
	$open_new_line = 1;
	$first_field = 1;
	
	// get the list of all the installed tables
	$tables_names_ar = build_tables_names_array(0);
	
	$count_temp = count($fields_labels_ar);
	for ($i=0; $i<$count_temp; $i++){

		// set to 1 if, for a conditional rule, the field must be hidden
		$hide_field_conditional = 0;

		if ($use_conditional_rules_for_details_pdf === 1){
		
			// code partially duplicated in front_logic get_required_fields
			if ( $fields_labels_ar[$i]["custom_required_function_field"] !== ''){

				$required_show_infos = call_user_func($fields_labels_ar[$i]["custom_required_function_field"], $details_row, 'form');
				
				if (is_array($required_show_infos)){
					$required_show_infos_2 = $required_show_infos['show'];
				}
				else{
					if (isset($required_show_infos_2)){ // otherwise I may get the value from a previous item
						unset($required_show_infos_2);
					}
				}
				
				if (isset($required_show_infos_2)){
					if ($required_show_infos_2 === false){
						$hide_field_conditional = 1;
					}
					elseif($required_show_infos_2 !== true){
						die('Error: custom required function return unexpected value.');
					}
				}
			}
			elseif($fields_labels_ar[$i]["show_if_field_field"] !== ''){ // simplified boolean
				
				$field_cond = $fields_labels_ar[$i]["show_if_field_field"];
				$value_cond = $fields_labels_ar[$i]["show_if_value_field"];
			
				$hide_field_conditional = 1;

				if (get_nocode_conditional_field_display($details_row[$field_cond], $fields_labels_ar[$i]["show_if_operator_field"], $value_cond) === 1){
					$hide_field_conditional = 0;
				}
			}
		}
	
		$lookup_link = '';
		$content_field_temp = '';
	
		$c_multiple = 0;
		if ($fields_labels_ar[$i][$presence_field_to_check] == "1" && $hide_field_conditional === 0){
			$field_name_temp = $fields_labels_ar[$i]["name_field"];
			
			if ($output === 'web_details' || $output === 'PDF'){
				if ($fields_labels_ar[$i]['details_separator_before_field'] !== ''){
					
					if ($first_field === 0){ // not the first field, close the previous form_fields_set
						$details_table .= '</span>';
					}
				
					$details_table .= '<div class="form_separator">';
					
					if ($output === 'PDF'){
						$details_table .= ' '; // hack for tcpdf, margin is not respected I need to add a blank space
					}
					
					$details_table .= $fields_labels_ar[$i]['details_separator_before_field'].'</div>';
			
					
				}
				
				if ($fields_labels_ar[$i]['details_separator_before_field'] !== '' || $first_field === 1){
					$details_table .= '<span class="form_fields_set">';	
				}
			}

			// hack for mssql, an empty varchar ('') is returned as ' ' by the driver, see http://bugs.php.net/bug.php?id=26315
			// could be not set if it's a foreign key
			if (isset($details_row[$field_name_temp]) && $details_row[$field_name_temp] === ' ') {
				//$details_row[$field_name_temp] = '';
			} // end if

			$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop if I don't call build_linked_field_values_ar I have the previous values
			
			switch ($fields_labels_ar[$i]['type_field']){
			
				case 'select_single':
				case 'select_single_radio':
				
					$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					
					// SELECT_SINGLE, LOOKUP FIELD
					if ($primary_key_field_field !== ""){
						
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
						$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
						$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
						$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
	
						// if the linked table is installed I can get type content and separator of the linked field
						if (in_array($primary_key_table_field, $tables_names_ar)) {
							$linked_table_installed = 1;
	
							$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
						} // end if
						else {
							$linked_table_installed = 0;
						} // end else
	
						for ($j=0;$j<count($linked_fields_ar);$j++) {
						
							$field_values_ar[0][$j] = $details_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
	
						} // end for
						
						if ($fields_labels_ar[$i]["show_lookup_link_field"] === '1' && $details_row[$field_name_temp] !== NULL){
							$lookup_link = ' <a target="_blank" href="index.php?tablename='.urlencode($primary_key_table_field).'&function='.$function_link_to_record.'&where_field='.urldecode($primary_key_field_field).'&where_value='.urlencode($details_row[$field_name_temp]).'"><span class="fa fa-sign-out fa-lg" aria-hidden="true"></span></a>';
						}
						
					}
					// SELECT SINGLE, HARDCODED OPTIONS
					else{
						$field_values_ar[0][0] = $details_row[$field_name_temp];
					}
					break;
				case 'select_multiple_menu':
				case 'select_multiple_checkbox':
					
					// SELECT_MULTIPLE, LOOKUP FIELD
					if ($fields_labels_ar[$i]["primary_key_field_field"] !== ""){
					
						
					
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
						$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
						$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
						$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
						$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
						
						$temp = $details_row[$field_name_temp];
						
						// if the linked table is installed I can get type content and separator of the linked field
						if (in_array($primary_key_table_field, $tables_names_ar)) {
							$linked_table_installed = 1;
							$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1");
						} // end if
						else {
							$linked_table_installed = 0;
						} // end else
						
						if (is_null($temp)){
							$field_values_ar[0][0] = NULL;
							$c_multiple++;
						}
						else{
						
							$temp = substr_custom($temp, 1, -1); // delete the first and the last separator
							
							if ($temp === ''){
								$field_values_ar[0][0] = '';
							}
							else{
								$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$temp);
								$select_labels_ar_number = count($select_labels_ar);
							
								$sql = "SELECT ".$quote.$primary_key_field_field.$quote;
		
								$c_temp = count($linked_fields_ar);
								for ($j=0; $j<$c_temp; $j++) {
									$sql .= ", ".$quote.$linked_fields_ar[$j].$quote;
								}
								$sql .= " FROM ".$quote.$primary_key_table_field.$quote." WHERE ";
								
								for ($j=0; $j<$select_labels_ar_number; $j++){	
									$sql .= $quote.$primary_key_field_field.$quote." = '".escape($select_labels_ar[$j])."' OR ";
								}
							
								$sql = substr_custom($sql, 0, -4); // delete the last OR
								$res = execute_db($sql, $conn);
								
								$field_values_ar[0][0] = ''; // fill it with '' in case there is no record in the corresponding linked table (it shoudln't happen in a normal situation)
							
								while ($row = fetch_row_db($res)){
							
									for ($j=0;$j<count($linked_fields_ar);$j++) {
										$field_values_ar[$c_multiple][$j] = $row[$j+1];
									} // end for
									$c_multiple++;
								}
							}
						}
					}
					// SELECT MULTIPLE, HARD CODED OPTIONS
					else{
					    $primary_key_field_field = '';
						$temp = $details_row[$field_name_temp];
						
						if (is_null($temp)){
							$field_values_ar[0][0] = NULL;
							$c_multiple++;
						}
						else{
						
							$temp = substr_custom($temp, 1, -1); // delete the first and the last separator
						
							$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$temp);
						
							foreach($select_labels_ar as $key => $value){
								$field_values_ar[$key][0] = $value;
								$c_multiple++;
							}
						}
					
						
					} // end if
					
					break;
				default:
					$field_values_ar[0][0] = $details_row[$field_name_temp];
					break;
			}

			$count_temp_2 = count($field_values_ar[0]);
			
			if ($output === 'web_details' || $output === 'PDF'){
				if ($open_new_line === 1 || $output === 'PDF'){
					$details_table .= '<div class="form_row">';
				}
			}
			elseif($output === 'web_results'){
				$details_table .= "<br/><br/>";
			}
			elseif($output === 'email'){
				$details_table .= "\n\n";
			}
			else{
				die ('Unexpected error');
			}
		
			if ($output === 'web_details' || $output === 'PDF'){
				
				$details_table .= '<label class="detail_page_label" id="'.$field_name_temp.'_label" for="'.$field_name_temp.'"';

				if ($from_form_configurator_preview === 1){
					$details_table .= 'style="cursor: pointer;"';
				}
				
				
				$details_table .= '>'.$fields_labels_ar[$i]["label_field"].'</label>&nbsp;&nbsp;&nbsp;';
			}
			elseif($output === 'email' || $output === 'web_results'){
				$details_table .= $fields_labels_ar[$i]["label_field"].': ';
			}
			else{
				die ('Unexpected error');
			}
			
			if ($output === 'web_details' || $output === 'PDF'){
				$details_table .= '<span class="form_input_element">';
			}
			if ($c_multiple === 0) $c_multiple = 1; // not select_multiple but there is one value for sure
			
			$fields_to_display_ar[$field_name_temp] = '';
			
			// enterprise
			// pro
			if ($fields_labels_ar[$i]['custom_formatting_function_field'] !== ''){
				
				for ($x=0; $x<$c_multiple;$x++){
					// performances? count every time
					if ( is_null($field_values_ar[$x][0]) && count($field_values_ar[$x]) === 1 ){
						$field_to_display = NULL;
					}
					else{
						$field_to_display = implode(' ', $field_values_ar[$x]);
					}
					
					$linked_field_type = '';
					$linked_field_content = '';
					$linked_field_separator = '';
					
					if ($GLOBALS['pass_linked_fields_to_format_function_as_array'] === 1 && count($field_values_ar[$x]) > 1){

						$field_to_pass = $field_values_ar[$x];
					}
					else{
						$field_to_pass = $field_to_display;
					}
					
					$field_to_display = get_field_correct_displaying($field_to_pass, $linked_field_type, $linked_field_content, $linked_field_separator, $display_mode, $fields_labels_ar[$i], 0, $where_field, $where_value, $is_items_table, $master_table_name, $table_name, $show_revisions, $details_row);

					$details_table .= $field_to_display;
					
					$details_table .= $separator_display_select_multiple;
					
					$fields_to_display_ar[$field_name_temp] .= $field_to_display;
					$fields_to_display_ar[$field_name_temp] .= $separator_display_select_multiple;
					
				}
				$details_table = substr_custom($details_table, 0, -(strlen_custom($separator_display_select_multiple))); // delete last br
				
				$fields_to_display_ar[$field_name_temp] = substr_custom($fields_to_display_ar[$field_name_temp], 0, -(strlen_custom($separator_display_select_multiple))); // delete last br
			}
			else{
			// end enterprise
			// end pro
				
				for ($x=0; $x<$c_multiple;$x++){
					
					for ($j=0; $j<$count_temp_2; $j++) {
	
						// if it's a linked field and the linked table is installed, get the correct $field_type $field_content $field_separator
						
						if (($fields_labels_ar[$i]['type_field'] === 'select_single' || $fields_labels_ar[$i]['type_field'] === 'select_single_radio' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_menu' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_checkbox') && isset($primary_key_field_field) && $primary_key_field_field != "" && $primary_key_field_field != NULL && isset($linked_table_installed) && $linked_table_installed === 1){
							
							foreach ($fields_labels_linked_field_ar as $key => $fields_labels_linked_field_ar_element){
								if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
									$linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
									$linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
									$linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
									$key_linked = $key;
								} // end if
							} // end foreach
	
							reset($fields_labels_linked_field_ar);
							
							$field_to_display = get_field_correct_displaying($field_values_ar[$x][$j], $linked_field_type, $linked_field_content, $linked_field_separator, $display_mode, $fields_labels_linked_field_ar[$key_linked], 0, $primary_key_field_field, $details_row[$field_name_temp], $is_items_table, $master_table_name, $table_name, $show_revisions, $details_row); // get the correct display mode for the field
							
							
						} // end if
						else {
							$field_to_display = get_field_correct_displaying($field_values_ar[$x][$j], $fields_labels_ar[$i]["type_field"], $fields_labels_ar[$i]["content_field"], $fields_labels_ar[$i]["separator_field"], $display_mode, $fields_labels_ar[$i], 0, $where_field, $where_value, $is_items_table, $master_table_name, $table_name, $show_revisions, $details_row); // get the correct display mode for the field
						} // end else
	
						//$details_table .= $field_to_display."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; // at the field value to the table
						$details_table .= $field_to_display.$separator_display_linked_field;
						
						$fields_to_display_ar[$field_name_temp] .= $field_to_display.$separator_display_linked_field;;
					}
					//$details_table = substr_custom($details_table, 0, -6*5); // delete the last &nbsp;
					$details_table = substr($details_table, 0, -(strlen($separator_display_linked_field))); // delete the last &nbsp;
					$details_table .= $separator_display_select_multiple;
					
					$fields_to_display_ar[$field_name_temp] = substr($fields_to_display_ar[$field_name_temp], 0, -(strlen($separator_display_linked_field))); // delete the last &nbsp;
					$fields_to_display_ar[$field_name_temp] .= $separator_display_select_multiple;
					
					
					
				}
				$details_table = substr_custom($details_table, 0, -(strlen_custom($separator_display_select_multiple))); // delete last br
				
				$fields_to_display_ar[$field_name_temp] = substr_custom($fields_to_display_ar[$field_name_temp], 0, -(strlen_custom($separator_display_select_multiple))); // delete last br
				
			// enterprise
			// pro
			} // end else
			
			if ($output === 'web_details' || $output === 'web_results'){
				$details_table .= $lookup_link;
			}
			if ($output === 'web_details' || $output === 'PDF'){
				$details_table .= "</span>";
			}
			
			if ($output === 'web_details'){
			
				$buttons_html = '';
				$custom_buttons_to_display = get_custom_buttons_to_display($table_name, $fields_labels_ar, 'details_page', $fields_labels_ar[$i]['name_field'],  1, unescape($where_field), unescape($where_value));
				
				if (count($custom_buttons_to_display) > 0){
					foreach($custom_buttons_to_display as $key => $button){
					    
                        $id_attribute = '';
						/*$margin_left_style = '';
						if ($key === 0){
							$margin_left_style = 'margin-left:0px;';
						}*/
                        if (isset($button['id'])){
                            $id_attribute = ' id="'.htmlspecialchars($custom_button_ids_prefix.$button['id']).'"';
                        }
						$buttons_html .= ' <input'.$id_attribute.' type="button" class="button_form btn btn-secondary custom_button" style="'.$button['style'].'" value="'.$button['label'].'"';
			
						$url_to_call = $dadabik_main_file."?function=execute_custom_function&tablename=".urlencode($table_name)."&where_field=".urlencode($where_field)."&where_value=".urlencode(unescape($where_value))."&custom_function=".urlencode($button['callback_function'])."&name_button=".urlencode($button['name']);
						
						$buttons_html = $buttons_html . get_custom_button_onclick_part($button, $url_to_call);
						
					}	
				} 
			
				$details_table .= '<span class="form_input_element_button">'.$buttons_html.'</span>';
			}	
			// end enterprise
			// end pro
			
			if ( $output === 'web_details' && $fields_labels_ar[$i]['details_new_line_after_field'] === '1'  || $output === 'PDF' ){
				$details_table .= '</div>'; //close the previous div class ROW
				$open_new_line = 1;
			}
			else{
				$open_new_line = 0;
			}
			
            if ($first_field === 1){
				$first_field = 0;
			}
			
		} // end if
		else{ // even for hidden fields, if we have to open a new line, do it
            if ( $output === 'web_details' && $fields_labels_ar[$i]['details_new_line_after_field'] === '1' ){
                $details_table .= '</div>'; //close the previous div class ROW
                $open_new_line = 1;
            }
            else{
                $open_new_line = 0;
            }
        }
		
	} // end for
	
	if ($output === 'web_details'){
		if ( $fields_labels_ar[($i-1)]['details_new_line_after_field'] !== '1'){ // otherwise already closed
			$details_table .= '</div>'; // close the last div class ROW
		}
	 
		
	}
	if ($output === 'web_details' || $output == 'PDF'){
		$details_table .= '</span>'; // close the last form_fields_set
	}
	if ($output === 'web_details'  && $onlyform === 0){
	    
		$details_table .= '<br/><br/><form target="_blank" class="css_form" method="POST" action="'.$dadabik_main_file.'?tablename='.urlencode($table_name).'&function=details&export_to_pdf=1&where_field='.urlencode($where_field).'&where_value='.urlencode($where_value).'">';
	
		if ($is_items_table === 1 && ($just_inserted === 0 || $from_back_edit_details_insert_delete_items_table === 1)){
		
			$details_table .= '<input type="button" class="button_form btn btn-outline-primary go_back" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?tablename='.urlencode($master_table_name).'&function='.$master_table_function.'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&from_back_edit_details_insert_delete_items_table=1\'">';
		}
		elseif ($just_inserted === 0  || $from_back_edit_details_insert_delete_items_table === 1){
	
			$details_table .= '<input type="button" class="button_form btn btn-outline-primary go_back" value="'.$submit_buttons_ar['go_back'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=search&tablename='.urlencode($table_name).'\'">';
		}
		
		if ($enable_edit == "1" && $show_edit_button_in_details === 1){
		
            $details_table .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-primary " value="'.$submit_buttons_ar['edit'].'" onclick="javascript:document.location=\''.$dadabik_main_file.'?function=edit&tablename='.urlencode($table_name).'&where_field='.urlencode($where_field).'&where_value='.urlencode(unescape($where_value));
        
        
            if ($is_items_table === 1) {
        
                $details_table .= "&master_table_name=".urlencode($master_table_name)."&master_table_function=".$master_table_function."&master_table_where_field=".urlencode($master_table_where_field)."&master_table_where_value=".urlencode(unescape($master_table_where_value))."&is_items_table=1";
            
            }
            
            $details_table .= '\'" id="details_page_edit_bottom_btn">';
		
		}

			
		if ( $export_to_pdf_feature == 1) {
		
			if ($templates_cnt === 0 || $assigned_templates_cnt === 1 ){

				$details_table .= '&nbsp;&nbsp;&nbsp;<input type="hidden" name="pdf_template" value="'.htmlspecialchars($value_pdf_template_no_menu).'"><button type="submit" id="produce_pdf_button_2" class="btn btn-primary btn-sm"><li class="fa fa-file-pdf-o fa-lg"></li>&nbsp;&nbsp;'.htmlspecialchars($label_pdf_button_no_menu).'</button>';
			}
			else{
				$details_table .= '&nbsp;&nbsp;&nbsp;<input type="button" class="button_form btn btn-primary " value="PDF" id="pdf_button_2" onclick=" document.getElementById(\'pdf_template_2\').style.visibility = \'visible\';document.getElementById(\'pdf_button_2\').style.display = \'none\'; document.getElementById(\'produce_pdf_button_2\').style.visibility = \'visible\';">'.$templates_menu_2.' <input type="submit" id="produce_pdf_button_2" value="'.$normal_messages_ar['produce_pdf'].'" style="visibility:hidden;display:inline-block;"  class="btn btn-primary ">';
			}
		}

			
		$details_table .= '</form>';
		
	}
	
	if ($output === 'email'){
		$details_table = $normal_messages_ar['details_of_record']."\n--------------------------------------------\n\n".$details_table."\n\n--------------------------------------------\nPowered by DaDaBIK - https://dadabik.com/";
	}
	
	if ($return_field_values_instead_of_table === 1){
		return $fields_to_display_ar;
	}
	else{
		return $details_table;
	}
		
} // end function build_details_table

?>