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
function build_form_element($fields_labels_ar, $i, $_POST_2, $details_row, $form_type, $disabled_attribute, $set_field_default_value, $default_value_field_name, $default_value, $sql_res_primary_key_back, $res_primary_key, $show_edit_form_after_error, $show_insert_form_after_error, $user_group_name, $not_valid_fields_ar=array())
{
	global $conn, $submit_buttons_ar, $normal_messages_ar, $select_operator_feature, $default_operator, $db_name, $size_multiple_select, $upload_relative_url, $show_top_buttons, $quote, $enable_authentication, $enable_browse_authorization, $current_user, $null_checkbox_prefix, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix, $start_year, $null_checkbox, $users_table_name, $prefix_internal_table, $enable_granular_permissions, $dadabik_main_file, $field_type_for_date, $htmlawed_config, $treat_blank_as_null, $use_id_group_for_ownership, $current_id_group, $file_access_mode, $separator_display_linked_field_2, $lenght_separator_display_linked_field_2, $enable_cascade_advanced_search_form;
	
	$add_hidden_field = 0;
	
	$form_element = '';
	$field_name_temp = $fields_labels_ar[$i]['name_field'];
	
	$javascript_function = 'refresh_cascade_children('.json_encode($field_name_temp).', this.value, '.json_encode(unescape_array($_POST_2)).', '.json_encode(unescape_array($details_row)).', \''.$form_type.'\', \''.$disabled_attribute.'\', '.$set_field_default_value.', '.json_encode($default_value_field_name).', '.json_encode($default_value).', '.$show_edit_form_after_error.', '.$show_insert_form_after_error.', '.json_encode($user_group_name).')';
	
	//$javascript_function_2 = 'enable_ajax_dropdown('.json_encode($field_name_temp).', this.value, '.json_encode(unescape_array($_POST_2)).', '.json_encode(unescape_array($details_row)).', \''.$form_type.'\', \''.$disabled_attribute.'\', '.$set_field_default_value.', '.json_encode($default_value_field_name).', '.json_encode($default_value).', '.$show_edit_form_after_error.', '.$show_insert_form_after_error.', '.json_encode($user_group_name).', '.$i.')';
	
	switch($fields_labels_ar[$i]["type_field"]){
		case 'select_single':
		case 'select_single_radio':
			
			if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
				$selected_word = ' selected';
			}
			elseif($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
				$selected_word = ' checked';
			}
			
			if ($fields_labels_ar[$i]["type_field"] === 'select_single' && $fields_labels_ar[$i]["chosen_field"] == '1' || $fields_labels_ar[$i]["type_field"] === 'select_single_radio' ){ // we need a container for the required red border, the normal approach doesn't work with select2 and we also need it for radios
               
                $form_element .= '<div';
                if ( in_array($field_name_temp, $not_valid_fields_ar)  ){
                    $form_element .= ' class="not_valid"';
                }
                $form_element .= '>';
            }
			
			
			if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
			    $form_element .= '<div class="select_element">';
				$form_element .= '<select class="form-select" data-placeholder=" "';
				
				if($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name){
					$form_element .= ' disabled';
					$add_hidden_field = 1;
				}
				else{
				    $form_element .= $disabled_attribute;
				}
				if ($fields_labels_ar[$i]['is_cascade_parent_field'] === 1 && ($form_type === 'insert' || $form_type === 'update' || $form_type === 'search' && $enable_cascade_advanced_search_form === 1)){
				
					$form_element .= ' onchange="'.htmlentities( $javascript_function).'"';
				
				}
						
                if ($fields_labels_ar[$i]['js_event_functions_field'] !== '' && ($form_type == "update" || $form_type == "insert")){
                    $temp = explode(';', $fields_labels_ar[$i]['js_event_functions_field']);
                    foreach($temp as $value){
                        $temp_2 = explode(':', $value);
                        $form_element .= ' '.$temp_2[0].'="'.$temp_2[1].'(this)"';
                    }
                }
				
				$form_element .= " name=\"".$field_name_temp."\" onchange=\"javascript:show_hide_text_other('".$fields_labels_ar[$i]["type_field"]."');\""; // first part of the second coloumn of the form
				
				if ($fields_labels_ar[$i]["chosen_field"] == '1'){
				    $form_element .= ' class="searchable_select';
				    
				    // this approach doesn't work with select2
				    /*if (in_array($field_name_temp, $not_valid_fields_ar)){
                        $form_element .= ' not_valid';
                    }*/
				    
				    $form_element .= '"';
				}
				else{
				    
				    if (in_array($field_name_temp, $not_valid_fields_ar)){
                        $form_element .= ' class="not_valid"';
                    }
                    
				}

				$form_element .= "><option value=\"\"></option>"; // first blank option
			}
			

			if ($fields_labels_ar[$i]["primary_key_field_field"] == ""){


				$field_temp = substr_custom($fields_labels_ar[$i]["select_options_field"], 1, -1); // delete the first and the last separator

				if (trim($field_temp) !== '') {
					$select_values_ar = explode($fields_labels_ar[$i]["separator_field"],$field_temp);

					$count_temp = count($select_values_ar);
					//if ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
					    
					//}
					for ($j=0; $j<$count_temp; $j++){
					
						if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
						
							$form_element .= "<option value=\"".htmlspecialchars($select_values_ar[$j])."\"";
						}
						elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
						
							$form_element .= '<input type="radio" ';
							
							if ($form_type === 'update'){
								$form_element .= $disabled_attribute;
							}
							else if($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name){
								$form_element .= ' disabled';
								$add_hidden_field = 1;
							}
							if ($fields_labels_ar[$i]['is_cascade_parent_field'] === 1){
								$form_element .= ' onclick="'.htmlentities( $javascript_function).'"';
							}
				
							$form_element .= " name=\"".$field_name_temp."\" onclick=\"javascript:show_hide_text_other('".$fields_labels_ar[$i]["type_field"]."');\""; // first part of the second coloumn of the form
							$form_element .= ' value="'.htmlspecialchars($select_values_ar[$j]).'"';
							
							
						}
						

						if ($form_type === 'update' or $form_type === 'ext_update') {
							if ($show_edit_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp])){
									if ($select_values_ar[$j] == unescape($_POST_2[$field_name_temp])) {
										$form_element .= $selected_word;
									}
								} // end if
								else{ // disabled
									if($select_values_ar[$j] == $_SESSION['details_row_back'][$field_name_temp]){
										$form_element .= $selected_word;
									}
								}
							} // end if
							else {
								if ($select_values_ar[$j] == $details_row[$field_name_temp]) {
									$form_element .= $selected_word;
								} // end if
							} // end else
						} // end if

						elseif ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $select_values_ar[$j] == unescape($_POST_2[$field_name_temp])){
							$form_element .= $selected_word;
						} // end if
						elseif ($form_type === 'insert' && $show_insert_form_after_error === 0 && $select_values_ar[$j] == $fields_labels_ar[$i]["default_value_field"] ){
							$form_element .= $selected_word;
						} // end else
						
						
						if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
						
							$form_element .= ">".htmlspecialchars($select_values_ar[$j])."</option>"; // second part of the form row

						}
						elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
						
							$form_element .= ">".htmlspecialchars($select_values_ar[$j])." "; // second part of the form row

						}
		
					} // end for
					
					//if ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
					    //$form_element .= '</div>';
					//}
				} // end if
			}

			if ($fields_labels_ar[$i]["primary_key_field_field"] != ""){ // it's a lookup field
			    
			    // compute default value (id_value_to_show) for both chosen and not chosen in the SQL: case and in case of custom function
			    // similar code in insert_record()
			    if ($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){
                
                    $sql_temp = substr_custom($fields_labels_ar[$i]["default_value_field"],4,strlen_custom($fields_labels_ar[$i]["default_value_field"])); 
            
                    $res_temp = execute_db($sql_temp, $conn);
            
                    $row_temp = fetch_row_db($res_temp);
                    
                    $id_value_to_show = htmlspecialchars($row_temp[0]);
                }
                elseif($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 8) === 'dadabik_'){
            
        
                    $id_value_to_show = htmlspecialchars(call_user_func($fields_labels_ar[$i]["default_value_field"]));
            
            
            
                }
			    
			    if ( $fields_labels_ar[$i]["chosen_field"] == 1 && $fields_labels_ar[$i]["chosen_ajax_field"] == 1 && $fields_labels_ar[$i]["type_field"] === 'select_single'){ // it's a lookup field with ajax data, we show options (only one) only if it has an already selected option
			    
			       $show_selected_value = 0; // flag, 1 if we need to show an option
			       if ($form_type === 'update') {
                        if ($show_edit_form_after_error === 1) {
                            if (isset($_POST_2[$field_name_temp])){
                                $id_value_to_show = unescape($_POST_2[$field_name_temp]);
                                $show_selected_value = 1;
                            } // end if
                            else{ // it happens if the field is disabled
                                $id_value_to_show =  $_SESSION['details_row_back'][$field_name_temp];
                                $show_selected_value = 1;
                            }
                        } // end if
                        else {
                            $id_value_to_show = $details_row[$field_name_temp];
                            $show_selected_value = 1;
                        } // end else
                    } // end if

                    elseif ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) ){
                        $id_value_to_show = unescape($_POST_2[$field_name_temp]);
                        $show_selected_value = 1;
                    } // end if
					elseif ($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name){
						$id_value_to_show = $default_value;
                        $show_selected_value = 1;
					} // end elseif
					// similar code in insert_record business_logic and in this file for the non chosen control
                    elseif ($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){
                
                        // id_value_to_show already computer
                        $show_selected_value = 1;
                    }
                    elseif($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 8) === 'dadabik_'){
                
                        // id_value_to_show already computer
                        $show_selected_value = 1;
                
                
                
                    }
                    elseif ($form_type === 'insert' && $fields_labels_ar[$i]["default_value_field"] !== '' ){
                        $id_value_to_show = $fields_labels_ar[$i]["default_value_field"];
                        $show_selected_value = 1;
                    } // end else
                    
                    // if we have a value, but it's '' or NULL we don't have to add the option, by default a first blank option is always displayed
                    if ($show_selected_value === 1 && ($id_value_to_show === '' || is_null($id_value_to_show))){
                        $show_selected_value = 0;
                    }
                    
                    if ($show_selected_value === 1){ // we need to show an option, we know the ID but not the linked values
                    
                        if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
                            $desc_value_to_show = $normal_messages_ar["other...."];
                        } // end if
                        if ($form_type === 'update' && $show_edit_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......') {
                            $desc_value_to_show = $normal_messages_ar["other...."];
                        } // end if
                        else{
                            // number of linked fields
                            $fields_number = num_fields_db($res_primary_key);
                            
                            // query to get the linked field values
                            $sql =  $sql_res_primary_key_back." AND (".$quote.$fields_labels_ar[$i]["primary_key_field_field"].$quote." = :id_value_to_show)";
                    
                            $res_prepare = prepare_db($conn, $sql);
                    
                            $values_to_bind = array();
                    
                            $values_to_bind['id_value_to_show'] = $id_value_to_show;
                    
                            foreach ($values_to_bind as $key => $value)
                            {
                                $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
                            }
                    
                            $res = execute_prepared_db($res_prepare,0);
                    
                            $num_rows = 0;
                            while($row = fetch_row_db($res_prepare)){
                                $desc_value_to_show = "";
                                for ($z=1; $z<$fields_number; $z++){
                                    $desc_value_to_show .= $row[$z];
                                    //$desc_value_to_show .= " - ";
                                    $desc_value_to_show .= $separator_display_linked_field_2;
                                    
                                } // end for
                                $desc_value_to_show = substr_custom($desc_value_to_show, 0, -($lenght_separator_display_linked_field_2)); // delete the last " - 
                                $num_rows++;
                            }
                    
                            if ($num_rows !== 1){
                                echo 'Unexpected error';
                                exit;
                            }
                        }
                        
                    
                        $form_element .= "<option value=\"".htmlspecialchars($id_value_to_show)."\"";
                   
                        $form_element .= " selected>".htmlspecialchars($desc_value_to_show)."</option>";
                    
                    }
			    }
			    else{
				    if (get_num_rows_db($sql_res_primary_key_back, 1) > 0){
					$fields_number = num_fields_db($res_primary_key);
					
					while ($primary_key_row = fetch_row_db($res_primary_key)){
		
						$primary_key_value = $primary_key_row[0];
						$linked_fields_value = "";
						for ($z=1; $z<$fields_number; $z++){
							$linked_fields_value .= $primary_key_row[$z];
							//$linked_fields_value .= " - ";
                            $linked_fields_value .= $separator_display_linked_field_2;
						} // end for
						$linked_fields_value = substr_custom($linked_fields_value, 0, -($lenght_separator_display_linked_field_2)); // delete the last " - 
						
						if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
						
							$form_element .= "<option value=\"".htmlspecialchars($primary_key_value)."\"";
						}
						elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
						
							$form_element .= '<input type="radio" ';
							
							if ($form_type === 'update'){
								$form_element .= $disabled_attribute;
							}
							else if($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name){
								$form_element .= ' disabled';
								$add_hidden_field = 1;
							}
							if ($fields_labels_ar[$i]['is_cascade_parent_field'] === 1){
								$form_element .= ' onclick="'.htmlentities( $javascript_function).'"';
							}
				
							$form_element .= " name=\"".$field_name_temp."\" onclick=\"javascript:show_hide_text_other('".$fields_labels_ar[$i]["type_field"]."');\""; // first part of the second coloumn of the form
							$form_element .= ' value="'.htmlspecialchars($primary_key_value).'"';
						}
						

						if ($form_type === 'update' or $form_type === 'ext_update') {
							if ($show_edit_form_after_error === 1) {
								if (isset($_POST_2[$field_name_temp])){
									if ($primary_key_value == unescape($_POST_2[$field_name_temp])) {
					
										$form_element .= $selected_word;
									}
								} // end if
								else{
									if ($primary_key_value == $_SESSION['details_row_back'][$field_name_temp]){
										$form_element .= $selected_word;
									}
								}
							} // end if
							else {
								if ($primary_key_value == $details_row[$field_name_temp]) {
									$form_element .= $selected_word;
								} // end if
							} // end else
						} // end if
						
						if ($show_insert_form_after_error === 1){
						    if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $primary_key_value == unescape($_POST_2[$field_name_temp])){
							    $form_element .= $selected_word;
						    } // end if
						}
						elseif ($set_field_default_value === 1 ){
						    if ($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name && $primary_key_value == $default_value){
							    $form_element .= $selected_word;
						    } // end if
						}
						else{
						
						    // similar code in insert_record business_logic and in this file for the chosen control
                            if ($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 4) === 'SQL:'){
                
                                // id_value_to_show already computer
                            
                                if ($primary_key_value == $id_value_to_show){
                                    $form_element .= $selected_word;
                                }
                            
                            }
                            elseif($form_type === 'insert' && substr_custom($fields_labels_ar[$i]["default_value_field"], 0, 8) === 'dadabik_'){
                
            
                                // id_value_to_show already computer
                            
                                if ($primary_key_value == $id_value_to_show){
                                    $form_element .= $selected_word;
                                }
                
                
                
                            }
                            elseif ($form_type === 'insert' && $primary_key_value == $fields_labels_ar[$i]["default_value_field"] ){
                                $form_element .= $selected_word;
                            } // end else
						
						}
						
                        
						
						if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
						
							$form_element .= ">".htmlspecialchars($linked_fields_value)."</option>"; // second part of the form row

						}
						elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
						
							$form_element .= ">".htmlspecialchars($linked_fields_value)." "; // second part of the form row

						}
		
						
					} // end while
					//if ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
					    //$form_element .= '</div>';
					//}
				} // end if
			    }
			} // end if ($fields_labels_ar[$i]["foreign_key_field"] != "")

			if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")){
			
				if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
					$form_element .= "<option value=\"......\"";
				}
				elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
					
					
					$form_element .= '<input type="radio" ';
							
					if ($form_type === 'update'){
						$form_element .= $disabled_attribute;
					}
					else if($form_type === 'insert' && $set_field_default_value === 1 && $field_name_temp === $default_value_field_name){
						$form_element .= ' disabled';
						$add_hidden_field = 1;
					}
					if ($fields_labels_ar[$i]['is_cascade_parent_field'] === 1){
						$form_element .= ' onclick="'.htmlentities( $javascript_function).'"';
					}
		
					$form_element .= " name=\"".$field_name_temp."\" onclick=\"javascript:show_hide_text_other('".$fields_labels_ar[$i]["type_field"]."');\""; // first part of the second coloumn of the form
					$form_element .= ' value="......"';

				}
				
				if ($form_type === 'insert' && $show_insert_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
					$form_element .= $selected_word;
				} // end if
				if ($form_type === 'update' && $show_edit_form_after_error === 1 && isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......') {
					$form_element .= $selected_word;
				} // end if
				if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
					$form_element .= ">".$normal_messages_ar["other...."]."</option>"; // last option with "other...."
				}
				elseif ($fields_labels_ar[$i]["type_field"] === 'select_single_radio'){
					$form_element .= ">".$normal_messages_ar["other...."]." "; // last option with "other...."
				}
			} // end if
			
			if ($fields_labels_ar[$i]["type_field"] === 'select_single'){
				$form_element .= "</select></div>";
			}
			
			if ($fields_labels_ar[$i]["type_field"] === 'select_single' && $fields_labels_ar[$i]["chosen_field"] == '1' || $fields_labels_ar[$i]["type_field"] === 'select_single_radio' ){ // we need a container for the required red border, the normal approach doesn't work with select2 and we also need it for radios
                $form_element .= '</div>';
            }
            

			if ($fields_labels_ar[$i]["other_choices_field"] == "1" and ($form_type == "insert" or $form_type == "update")){

				$form_element .= '<span id="other_textbox_'.$field_name_temp.'" class="form_input_element" style="display:none">';

				$form_element .= " <input placeholder=\"...\" type=\"text\" name=\"".$field_name_temp."_other____"."\" maxlength=\"".$fields_labels_ar[$i]["maxlength_field"]."\"";

				if ($fields_labels_ar[$i]["width_field"] != ""){
					$form_element .= " size=\"".$fields_labels_ar[$i]["width_field"]."\"";
				} // end if

				if ($form_type == "insert" && $show_insert_form_after_error === 1){
					if (isset($_POST_2[$field_name_temp."_other____"])) {
						if (isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
							$form_element .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp."_other____"])).'"';
						} // end if
					} // end if
				} // end if

				if ($form_type == "update" && $show_edit_form_after_error === 1){
					if (isset($_POST_2[$field_name_temp."_other____"])) {
						if (isset($_POST_2[$field_name_temp]) && $_POST_2[$field_name_temp] === '......'){
							$form_element .= ' value="'.htmlspecialchars(unescape($_POST_2[$field_name_temp."_other____"])).'"';
						} // end if
					} // end if
				} // end if

				$form_element .= ">"; // text field for other....
				$form_element .= "</span>";
			} // end if
			
			
			

			if ($add_hidden_field === 1){
				$form_element .= '<input type="hidden" name="'.$default_value_field_name.'" value="'.htmlspecialchars($default_value).'">';
			}
			break;
	}
	return $form_element;
}
?>