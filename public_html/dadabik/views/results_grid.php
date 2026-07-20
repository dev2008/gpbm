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
// shares some core with build_csv, build_json, get_calendar_field_value, build_details_table
function build_results_table($results_display_mode, $fields_labels_ar, $table_name, $res_records, $results_type, $name_mailing, $action, $where_clause, $page, $order, $order_type, $master_table_name, $master_table_function, $master_table_where_field , $master_table_where_value, $is_items_table, $template_infos_ar, $onlygrid = 0, $show_revisions = 0, $linked_tables_count = 0)
// goal: build the results grid you see when you click on a table/view or after a search
// input: ...
// output: $results_table, the HTML results table
// global: ...
{
	global $submit_buttons_ar, $normal_messages_ar, $edit_target_window, $delete_icon, $edit_icon, $details_icon, $enable_edit, $enable_delete, $enable_details, $conn, $quote, $ask_confirmation_delete, $word_wrap_col, $word_wrap_fix_width, $alias_prefix, $dadabik_main_file, $enable_row_highlighting, $prefix_internal_table, $enable_granular_permissions, $enable_authentication, $separator_display_select_multiple, $separator_display_linked_field, $function_link_to_record, $results_display_mode_menu, $enable_csv, $select_checkbox_prefix, $enable_record_checkboxes, $fields_to_display_ar, $details_link, $edit_link, $delete_link, $enable_custom_display_results_grid, $grid_ids, $results_grid_fixed_header, $grid_layout_scrolling, $align_right_numeric_values;
	
	$grid_ids = array();
	
	/////////////////////////////////////////////////////////////////
	// SOME CODE IS SHARED WITH build_details_table() AND build_csv()
	/////////////////////////////////////////////////////////////////
	
	// get the list of the installed tables, useful later if we have lookup fields
	$tables_names_ar = build_tables_names_array(0);
	
	// for items tables, record selection checkboxes are not implemented
	if ($is_items_table === 1){
	    $enable_record_checkboxes = 0;
	}
	
	if ($is_items_table === 1){
				
		// re-build the enabled features and permissions, the ones coming from global are the ones related to the master table, here we need the ones related to the items table
		
		if ($enable_granular_permissions === 1 && $enable_authentication === 1){
			$enabled_features_ar_2 = build_enabled_features_ar_2($table_name);
			$enable_edit = $enabled_features_ar_2['edit'];
			$enable_insert = $enabled_features_ar_2['insert'];
			$enable_delete = $enabled_features_ar_2['delete'];
			$enable_details = $enabled_features_ar_2['details'];
			$enable_csv = $enabled_features_ar_2['csv'];
		}
		elseif ($enable_granular_permissions === 0 || $enable_authentication === 0){
			$enabled_features_ar = build_enabled_features_ar($table_name);
			$enable_edit = $enabled_features_ar['edit'];
			$enable_insert = $enabled_features_ar['insert'];
			$enable_delete = $enabled_features_ar['delete'];
			$enable_details = $enabled_features_ar['details'];
			$enable_csv = $enabled_features_ar['csv'];
		}
		else{
			echo "<p>An error occurred";
		} // end else
	
	} // end if
	
	$function = 'search'; // legacy code, $function used to be check_form or search, now it's always search
	
	// get the unique field of the table
	$unique_field_name = get_unique_field_db($table_name);
	
	// the grid
	$results_table = "";
	
	// the display mode form (classic grid | list)
	$results_display_mode_form = '';
	
	// build the display mode form
	if ( false && $results_type == "search" && $results_display_mode_menu === 'both' && $onlygrid === 0 && $linked_tables_count < 2) { // not for duplication or for embedding or for the second (and next) items table
	
		$results_display_mode_form = '<form name="results_display_mode_form" mentod="GET" action="'.$dadabik_main_file.'"  style="display:inline-block">';
	
		if ($is_items_table === 1) {
			$results_display_mode_form .= '<input type="hidden" name="tablename" value="'.urlencode($master_table_name).'">';
			$results_display_mode_form .= '<input type="hidden" name="function" value="'.$master_table_function.'">';
			$results_display_mode_form .= '<input type="hidden" name="items_table_name" value="'.urlencode($table_name).'">';
			$results_display_mode_form .= '<input type="hidden" name="where_field" value="'.urlencode($master_table_where_field).'">';
			$results_display_mode_form .= '<input type="hidden" name="where_value" value="'.urlencode(unescape($master_table_where_value)).'">';
			$results_display_mode_form .= '<input type="hidden" name="store_session_table_infos_for_items_table" value="1">';
		} // end if
		else {
			$results_display_mode_form .= '<input type="hidden" name="tablename" value="'.urlencode($table_name).'">';
			$results_display_mode_form .= '<input type="hidden" name="function" value="'.$function.'">';
			$results_display_mode_form .= '<input type="hidden" name="where_clause" value="'.urlencode($where_clause).'">';
		} // end else
	
		$results_display_mode_form .= '<select onchange="javascript:document.results_display_mode_form.submit()" name="results_display_mode">
		<option value="classic_grid"'.( ($results_display_mode === 'classic_grid') ? ' selected':'').'>classic grid</option>
		<option value="list"'.(($results_display_mode === 'list') ? ' selected':'').'>list</option>
		</select>
		</form>';
	}


	$results_display_mode_form = '';
	
	// build the display mode form
	if ( $results_type == "search" && $results_display_mode_menu === 'both' && $onlygrid === 0 && $linked_tables_count < 2) { // not for duplication or for embedding or for the second (and next) items table
		
		$results_display_mode_form = '<span style="vertical-align:middle">';
		
		$link_fixed = $dadabik_main_file;

		if ($is_items_table === 1) {

			$link_fixed .= '?tablename='.urlencode($master_table_name).'&function='.$master_table_function.'&items_table_name='.urlencode($table_name).'&where_field='.urlencode($master_table_where_field).'&where_value='.urlencode(unescape($master_table_where_value)).'&store_session_table_infos_for_items_table=1';

		} // end if
		else {

			$link_fixed .= '?tablename='.urlencode($table_name).'&function=search&where_clause='.urlencode($where_clause);
		} // end else

		/*

		if ($table_infos_ar['enable_grid_layout_table'] === '1'){
			if ($results_display_mode === 'classic_grid'){
				$results_display_mode_form .= '<i class="bx bx-grid-alt fs-4" title="grid view"></i>';
			}
			else{
				$results_display_mode_form .= '<a href="'.$link_fixed.'&results_display_mode=classic_grid"><i class="bx bx-grid-alt fs-4" title="grid view"></i></a>';
			}
		}

		if ($table_infos_ar['enable_list_layout_table'] === '1'){
			if ($results_display_mode === 'list'){
				$results_display_mode_form .= '<i class="bx bx-grid-alt fs-4" title="list view"></i>';
			}
			else{
				$results_display_mode_form .= '<a href="'.$link_fixed.'&results_display_mode=list"><i class="bx bx-grid-alt fs-4" title="list view"></i></a>';
			}
		}

		if ($table_infos_ar['enable_calendar_layout_table'] === '1'){
			if ($results_display_mode === 'calendar'){
				$results_display_mode_form .= '<i class="bx bx-grid-alt fs-4" title="calendar view"></i>';
			}
			else{
				$results_display_mode_form .= '<a href="'.$link_fixed.'&results_display_mode=calendar"><i class="bx bx-grid-alt fs-4" title="calendar view"></i></a>';
			}
		}

		*/

		if ($results_display_mode === 'classic_grid'){
			$results_display_mode_form .= '<i class="bx bx-grid-alt fs-4" title="grid view"></i> <a href="'.$link_fixed.'&results_display_mode=list"><i class="bx bx-list-ul fs-4" title="list view"></i></a>';
		}
		else{
			$results_display_mode_form .= '<a href="'.$link_fixed.'&results_display_mode=classic_grid"><i class="bx bx-grid-alt fs-4" title="grid view"></i></a> <i class="bx bx-list-ul fs-4"  title="list view"></i>';

		}

		$results_display_mode_form .= '</span>';
		//$results_display_mode_form = '';
	}
	
	// list version, for mobile, one details pane under each other	
	if ($results_display_mode === 'list'){
		
		$results_table .= $results_display_mode_form;
		
		$order_by_form = '<form name="order_by_form" mentod="GET" action="'.$dadabik_main_file.'" style="display:inline-block">';
		
		$order_by_form .= '<input type="hidden" name="page" value="'.$page.'">';
		$order_by_form .= '<input type="hidden" name="where_clause" value="'.urlencode($where_clause).'">';

		if ($is_items_table === 1) {
			$order_by_form .= '<input type="hidden" name="tablename" value="'.urlencode($master_table_name).'">';
			$order_by_form .= '<input type="hidden" name="function" value="'.$master_table_function.'">';
			$order_by_form .= '<input type="hidden" name="items_table_name" value="'.urlencode($table_name).'">';
			$order_by_form .= '<input type="hidden" name="where_field" value="'.urlencode($master_table_where_field).'">';
			$order_by_form .= '<input type="hidden" name="where_value" value="'.urlencode(unescape($master_table_where_value)).'">';
			$order_by_form .= '<input type="hidden" name="store_session_table_infos_for_items_table" value="1">';
		} // end if
		else {
			$order_by_form .= '<input type="hidden" name="tablename" value="'.urlencode($table_name).'">';
			$order_by_form .= '<input type="hidden" name="function" value="'.$function.'">';
			$order_by_form .= '<input type="hidden" name="where_clause" value="'.urlencode($where_clause).'">';
		} // end else
		
		$order_by_form .= '&nbsp;&nbsp;<table><tr class="list_view_sort_by_container"><td>'.$normal_messages_ar['sort_by'].':</td><td><select name="order" class="form-select" style="display:inline-block">';
		
		foreach($fields_labels_ar as $value){
			if ($value['present_results_search_field'] == '1'){
				$order_by_form .= '<option value="'.$value['name_field'].'"';
				if ($order === $value['name_field']){	
					$order_by_form .= ' selected';
				}			
				$order_by_form .= '>'.$value['label_field'].'</option>';
			}
		}
		
		$order_by_form .= '</select></td><td>';
	
		$order_by_form .= ' <select name="order_type" class="form-select" style="display:inline-block">
	<option value="ASC"'.( ($order === 'ASC') ? ' selected':'').'>ASC</option>
	<option value="DESC"'.(($results_display_mode === 'DESC') ? ' selected':'').'>DESC</option></select></td><td> <input type="submit" class="btn btn-primary" value="'.$normal_messages_ar['sort'].'" id="sort_btn"></td></tr></table></form>';
	
		$results_table .= $order_by_form;
		
		$c_temp = 0;
		// build the table body
		while ($records_row = fetch_row_db($res_records)){
		
			$class_temp = 'details_pane even';
			if (($c_temp%2) !== 0){
				$class_temp = 'details_pane odd';
			}
			
			$results_table .= '<div class="'.$class_temp.'">';
				
			// set where clause for delete and update
			///////////////////////////////////////////
			if ($unique_field_name != "" && $unique_field_name != NULL){ // exists a unique field
				$where_field = $unique_field_name;
				$where_value = $records_row[$unique_field_name];
			} // end if
			///////////////////////////////////////////
			// end build where clause for delete and update
			
			$edit_link = '';
			$details_link = '';
			$delete_link = '';
			
			if ($unique_field_name != "" && $unique_field_name != NULL ){ // exists a unique field: edit, delete, details available
		
				$links_ar = build_edit_details_delete_links($table_name, $where_field, $where_value, $enable_edit, $enable_delete, $enable_details, $master_table_name, $master_table_function, $master_table_where_value, $master_table_where_field, $is_items_table, $fields_labels_ar);
				
				$edit_link = $links_ar['edit_link'];
				$delete_link = $links_ar['delete_link'];
				$details_link = $links_ar['details_link'];
				
				$results_table .= $links_ar['links_html'];
				
				if ($links_ar['buttons_html'] !== ''){
					$results_table .= '<br>'.$links_ar['buttons_html'];
				}

			} // end if
			
			$results_table .= build_details_table($fields_labels_ar, $records_row, $table_name, 0, 0, '', '', '', '', $where_field, $where_value, 'web_results', 1).'</div>';
			
			$c_temp++;
		}
		
		return $results_table;
		
	}
	
	else{ // normal grid version

		// build the results HTML table
		///////////////////////////////

		$template_results_table = '';
	
		if ($is_items_table == '1'){
			$template_results_table .= '<br/>';
		}
	
		$results_table .= "<table id=\"table-placeholder-international-names\" class=\"results\" cellpadding=\"5\" cellspacing=\"0\"><thead>";
	
		// build the table heading
		$results_table .= "<tr class=\"tr_results_header\">";
		
		if ($unique_field_name != "" && $unique_field_name != NULL && $onlygrid === 0){ // exists a unique field
		    $results_table .= '<th class="results"';
		    
		    if ($results_grid_fixed_header === 1 && $grid_layout_scrolling !== 'grid_overflow'){
                $results_table .=  ' style = "position: sticky; top: 0; z-index: 1; background:#ccc;"';
            }
		    
		    $results_table .= ">\n";
		    //$results_table .= $results_display_mode_form."</th>";
			$results_table .= "</th>";
		    
		    $results_table .= "<th class=\"normalpad\" >".$results_display_mode_form."</th>"; // skip the first column and for edit, delete and details
		    
		    // skip the second column for the record selection checkboxes
		    if ($enable_record_checkboxes === 1){
		        $results_table .= '<th class="zeropad"';
		        
		        if ($results_grid_fixed_header === 1 && $grid_layout_scrolling !== 'grid_overflow'){
                    $results_table .=  ' style = "position: sticky; top: 0; z-index: 1; background:#ccc;"';
                }
		        
		        $results_table .= '></th>';
		    }
		}
		
		$count_temp = count($fields_labels_ar);
		// for each field
		for ($i=0; $i<$count_temp; $i++){
			if ($fields_labels_ar[$i]["present_results_search_field"] == "1"){ // the user want to display the field
                
                $show_column[$fields_labels_ar[$i]["name_field"]] = 1;
                if ($enable_custom_display_results_grid === 1 && $fields_labels_ar[$i]["custom_required_function_field"] !== ''){
                    
                    $params = array();
                    if (isset($_SESSION['advanced_filters_ar_'.$table_name])){
                        $params = $_SESSION['advanced_filters_ar_'.$table_name];
                    }
                    
                    $temp = call_user_func($fields_labels_ar[$i]["custom_required_function_field"], $params, 'results_grid');
                    
                    
                    if ($temp['show'] === false){
                        $show_column[$fields_labels_ar[$i]["name_field"]] = 0;
                    }
                    
                }
                
                if ($show_column[$fields_labels_ar[$i]["name_field"]] === 1){
                    
                    $label_to_display = $fields_labels_ar[$i]["label_field"].'&nbsp;&nbsp;';
                    if ($fields_labels_ar[$i]["label_grid_field"] !== ''){
                        $label_to_display = $fields_labels_ar[$i]["label_grid_field"].'&nbsp;&nbsp;';
                    }
                    
                    if ($word_wrap_fix_width === 1){
            
                        $spaces_to_add = $word_wrap_col-strlen_custom($label_to_display)-2;

                        if ( $spaces_to_add > 0) {
                            for ($j=0; $j<$spaces_to_add; $j++) {
                                $label_to_display .= '&nbsp;';
                            }
                        }
                    } // end if
                
                    $results_table .= '<th class="results" style="';
                
                    if ($fields_labels_ar[$i]["min_width_results_grid_column_field"] !== ''){
                        $results_table .= 'min-width:'.$fields_labels_ar[$i]["min_width_results_grid_column_field"].'px; ';
                    }
                    
                    if ($results_grid_fixed_header === 1 && $grid_layout_scrolling !== 'grid_overflow'){
                        $results_table .=  'position: sticky; top: 0; z-index: 1; background:#ccc;';
                    }
                    
                    $results_table .= '">'."\n";
                
                    $field_is_current_order_by = 0;

                    if ( $results_type == "search" && $onlygrid === 0 && $show_revisions === 0) { // not for duplication or for embedding
                        if ($order != $fields_labels_ar[$i]["name_field"]){ // the results are not sorted by this field at the moment
                            $link_class="order_link";
                            $new_order_type = "ASC";
                        }
                        else{ // the results are sorted by this field
                            $field_is_current_order_by = 1;
                            $link_class="order_link_selected";
                            if ( $order_type == "DESC") {
                                $new_order_type = "ASC";
                            }
                            else{
                                $new_order_type = "DESC";
                            }
                        } // end else if ($order != $fields_labels_ar[$i]["name_field"])

                        if ($is_items_table === 1) {
                            $results_table .= "<a class=\"".$link_class." \" href=\"".$action."?tablename=". urlencode($master_table_name)."&function=".$master_table_function."&items_table_name=".urlencode($table_name)."&where_field=".urlencode($master_table_where_field)."&where_value=".urlencode(unescape($master_table_where_value))."&page=".$page."&order=".urlencode($fields_labels_ar[$i]["name_field"])."&order_type=".$new_order_type."&store_session_table_infos_for_items_table=1\">";
                        } // end if
                        else {
                            $results_table .= "<a class=\"".$link_class." \" href=\"".$action."?tablename=". urlencode($table_name)."&function=".$function."&page=".$page."&order=".urlencode($fields_labels_ar[$i]["name_field"])."&order_type=".$new_order_type."\">";
                        } // end else
                    
                        if ($field_is_current_order_by === 1) {
                            if ($order_type === 'ASC') {
                                $results_table .= '<span class="arrow">&uarr;</span> ';
                            } // end if
                            else {
                                $results_table .= '<span class="arrow">&darr;</span> ';
                            } // end if
                        } // end if
                
                        $results_table .= $label_to_display."</a></th>"; // insert the linked name of the field in the <th>
                    }
                    else{
                        $results_table .= '<span class="text_heading">'.$label_to_display."</span></th>"; // insert the  name of the field in the <th>
                    } // end if
                }

			} // end if
		} // end for
		$results_table .= "</tr></thead><tbody>";

		$tr_results_class = 'tr_results_1';
		$td_controls_class = 'controls_1';

		// build the table body
		// for each record
		
		while ($records_row = fetch_row_db($res_records)){

			if ($tr_results_class === 'tr_results_1') {
				$td_controls_class = 'controls_2';
				$tr_results_class = 'tr_results_2';
			} // end if
			else {
				$td_controls_class = 'controls_1';
				$tr_results_class = 'tr_results_1';
			} // end else

			// set where clause for delete and update
			///////////////////////////////////////////
			if ($unique_field_name != "" && $unique_field_name != NULL){ // exists a unique field
				$where_field = $unique_field_name;
				$where_value = $records_row[$unique_field_name];
			} // end if
			elseif ($show_revisions === 1){ // in this case it's ok, we don't have it
			    $where_field = '';
			    $where_value = '';
			}
			///////////////////////////////////////////
			// end build where clause for delete and update
			
			$results_table .= "<tr class=\"".$tr_results_class."\">";
			
			$edit_link = '';
			$details_link = '';
			$delete_link = '';
			
			// buld the first two columns (edit/delete/details and selection checkbox)
			if ($unique_field_name != "" && $unique_field_name != NULL && ($results_type == "search" || $results_type == "possible_duplication") && $onlygrid === 0){ // exists a unique field: edit, delete, details available ... don't do it for embedding
				
				if ($enable_record_checkboxes === 1){
				    $results_table .= '<td class="zeropad"><input type="checkbox" id="'.$select_checkbox_prefix.''.htmlspecialchars($where_value).'"';
				
                    if (isset($_SESSION['checked_ids'][$table_name][$where_value])){
                        $results_table .= ' checked';
                    }
                
                    $results_table .= '></td>';
                }

			    $results_table .= "<td class=\"".$td_controls_class."\" nowrap>";
			
				$links_ar = build_edit_details_delete_links($table_name, $where_field, $where_value, $enable_edit, $enable_delete, $enable_details, $master_table_name, $master_table_function, $master_table_where_value, $master_table_where_field, $is_items_table, $fields_labels_ar);
				
				$grid_ids[] = $where_value;
				
				$edit_link = $links_ar['edit_link'];
				$delete_link = $links_ar['delete_link'];
				$details_link = $links_ar['details_link'];
				
				$results_table .= $links_ar['links_html'];
				
				$results_table .= '</td><td';
				
				if ($links_ar['buttons_html'] === ''){
				    $results_table .= ' class="zeropad"';
				}
				
				$results_table .= '>'.$links_ar['buttons_html'].'</td>';
				
				
                
			} // end if

			//$results_table .= "</td>";
			
			// for each field
			for ($i=0; $i<$count_temp; $i++){
				$lookup_link = '';
				if ($fields_labels_ar[$i]["present_results_search_field"] == "1" && $show_column[$fields_labels_ar[$i]["name_field"]] === 1){ // the user want to display the field in the search results page
				
				    /*$data_calc = 0;
				    $data_formatting = 0;
				    
				    if ($fields_labels_ar[$i]["calculated_function_field"] !== ''){
				        $data_calc = 1;
				    }
				    
				    if ($fields_labels_ar[$i]["custom_formatting_function_field"] !== ''){
				        $data_formatting = 1;
				    }*/


					// to avoid user_is_allowed for each row
					if (!isset($fields_live_edit_ar[$fields_labels_ar[$i]["name_field"]])){
						$data_live_edit_part = '';
						
						if ( (user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, 3) === 1 || user_is_allowed($_SESSION['logged_user_infos_ar'], 'table', $table_name, 3) === 2) && user_is_allowed($_SESSION['logged_user_infos_ar'], 'field', $table_name.'.'.$fields_labels_ar[$i]["name_field"], 8) === 1){
							$data_live_edit_part = 'data-live-edit';
						}
						$fields_live_edit_ar[$fields_labels_ar[$i]["name_field"]] = $data_live_edit_part;
					}
					else{
						$data_live_edit_part = $fields_live_edit_ar[$fields_labels_ar[$i]["name_field"]];
					}

					$align_attribute = '';

					if ($align_right_numeric_values === 1 && $fields_labels_ar[$i]['type_field'] === 'text' && $fields_labels_ar[$i]['content_field'] === 'numeric' ){
						$align_attribute = ' align="right"';
					}

					$results_table .= '<td class="results_grid_cell" '.$data_live_edit_part.' data-source="'.$table_name.'" data-row="'.htmlspecialchars($where_value).'" data-field="'.$fields_labels_ar[$i]["name_field"].'"  data-type="'.$fields_labels_ar[$i]["type_field"].'"'.$align_attribute.'>'; // start the cell

					$field_name_temp = $fields_labels_ar[$i]["name_field"];
					$field_type = $fields_labels_ar[$i]["type_field"];
					$field_content = $fields_labels_ar[$i]["content_field"];
					$field_separator = $fields_labels_ar[$i]["separator_field"];
				
					// this is an array containing the values to display for a single field each time
					// a "normal" field will have the value contained in $field_values_ar[0][0]
					// a select_single lockup field will have the value contained in $field_values_ar[0][0], [0][1], [0][2]...
					// a select_multiple lockup field will have the value contained in
					// $field_values_ar[0][0], [0][1], [0][2]...[1][0], [1][1], [1][2]
					// where the first index represent the option selected and the second the various linked fields
				
					$field_values_ar = array(); // reset the array containing values to display, otherwise for each loop I have the previous values
				
					$c_multiple = 0; // counter for multiple options
				    
				    // set $field_values_ar according to the specifc case: select_multiple non-lookup, select_multiple lookup, select_single lookup, select_single non-lookuy
					// SELECT MULTIPLE
					if ($fields_labels_ar[$i]["type_field"] === 'select_multiple_menu' || $fields_labels_ar[$i]["type_field"] === 'select_multiple_checkbox'){
					
						// Select multiple with fixed options, non-lookup
						if ($fields_labels_ar[$i]["primary_key_field_field"] == ""){
							$primary_key_field_field = '';
							$temp = $records_row[$field_name_temp];
							
							if (is_null($temp)){
								$field_values_ar[0][0] = NULL;
								$c_multiple++;
							}
							else{
								$temp = substr_custom($temp, 1, -1); // delete the first and the last separator
								$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$temp);
								$select_labels_ar_number = count($select_labels_ar);
						
								foreach($select_labels_ar as $key => $value){
									$field_values_ar[$key][0] = $value;
									$c_multiple++;
								}
							}
						}
						// Select multiple lookup field
						else{
					
							$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
							$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
							$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
							$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
							$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
						
							$temp = $records_row[$field_name_temp];
	
							// if the linked table is installed I can get type content and separator of the linked field
							if (in_array($primary_key_table_field, $tables_names_ar)) {
								$linked_table_installed = 1;
						        
						        // the the fields_labels_array for the linked table, and store it in case you need it again 
								if (!isset($fields_labels_linked_field_ar_archive[$primary_key_table_field])){
									$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1", 1);
									$fields_labels_linked_field_ar_archive[$primary_key_table_field] = $fields_labels_linked_field_ar;
								}
								else{
									$fields_labels_linked_field_ar = $fields_labels_linked_field_ar_archive[$primary_key_table_field];
								}
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
								    // get the values store in the fields e.g. ~5~7~ and store them in $select_labels_ar 
									$select_labels_ar = explode($fields_labels_ar[$i]["separator_field"],$temp);
									$select_labels_ar_number = count($select_labels_ar);
				                    
				                    // create the queries to get the linked values in the corresponding linked table
									$sql = "SELECT ".$quote.$primary_key_field_field.$quote;
			
									// add the linked fields to the query
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
									
									// for each row 
									while ($row = fetch_row_db($res)){
									    // for each linked field
										for ($j=0;$j<count($linked_fields_ar);$j++) {
											$field_values_ar[$c_multiple][$j] = $row[$j+1];
										} // end for
										$c_multiple++;
									}
								}
							}
						} // end if
					}
					// SELECT SINGLE
					else{
						$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
					
						// select_single lookup field
						if ( ($fields_labels_ar[$i]["type_field"] === 'select_single' || $fields_labels_ar[$i]["type_field"] === 'select_single_radio' ) && $primary_key_field_field != "" && $primary_key_field_field != NULL){
						
							$primary_key_field_field = $fields_labels_ar[$i]["primary_key_field_field"];
							$primary_key_table_field = $fields_labels_ar[$i]["primary_key_table_field"];
							$primary_key_db_field = $fields_labels_ar[$i]["primary_key_db_field"];
							$linked_fields_field = $fields_labels_ar[$i]["linked_fields_field"];
							$alias_suffix_field = $fields_labels_ar[$i]["alias_suffix_field"];
							$linked_fields_ar = explode($fields_labels_ar[$i]["separator_field"], $linked_fields_field);
	
							// if the linked table is installed I can get type content and separator of the linked field
							if (in_array($primary_key_table_field, $tables_names_ar)) {
								$linked_table_installed = 1;
							
								if (!isset($fields_labels_linked_field_ar_archive[$primary_key_table_field])){
									$fields_labels_linked_field_ar = build_fields_labels_array($prefix_internal_table.$primary_key_table_field, "1", 1);
									$fields_labels_linked_field_ar_archive[$primary_key_table_field] = $fields_labels_linked_field_ar;
								}
								else{
									$fields_labels_linked_field_ar = $fields_labels_linked_field_ar_archive[$primary_key_table_field];
								}
							
							} // end if
							else {
								$linked_table_installed = 0;
							} // end else
	
							for ($j=0;$j<count($linked_fields_ar);$j++) {
							
								$field_values_ar[0][$j] = $records_row[$primary_key_table_field.$alias_prefix.$linked_fields_ar[$j].$alias_prefix.$alias_suffix_field];
							
							} // end for
						
							if ($fields_labels_ar[$i]["show_lookup_link_field"] === '1' && $records_row[$field_name_temp] !== NULL){
								$lookup_link = ' <a target="_blank" href="index.php?tablename='.urlencode($primary_key_table_field).'&function='.$function_link_to_record.'&where_field='.urldecode($primary_key_field_field).'&where_value='.urlencode($records_row[$field_name_temp]).'"><span class="fa fa-sign-out fa-lg" aria-hidden="true"></span></a>';
							}
						}
						// select_single non-lookup
						else{
							$field_values_ar[0][0] = $records_row[$field_name_temp];
						}
					}
				    // end field_values_ar settings
				    
				    // use the field_values_ar to render the value
					if ($c_multiple === 0) $c_multiple = 1; // not select_multiple but there is one value for sure
				
					$composite_field_to_display = ''; // this is used to temporarily store the whole value included in a cell (if it's a select multiple, it contains all the options and if it's a lookup all the linked field values), the variable $field_to_display, instead, is used to temporarily store the value of the single piece (i.e. the single linked field value of the single option)
					
					if ($fields_labels_ar[$i]['custom_formatting_function_field'] !== ''){
					
						// if there is a custom formatting function, just combine (implode) all the linked fields and apply the function to the whole thing
					
						for ($x=0; $x<$c_multiple; $x++){ // just one if the field is not a select multiple
						
							// performances? count() every time
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

							$field_to_display = get_field_correct_displaying($field_to_pass, $linked_field_type, $linked_field_content, $linked_field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar, $where_field, $where_value, $is_items_table, $master_table_name, $table_name, $show_revisions, $records_row);
						
							$results_table .= $field_to_display;
							$results_table .= $separator_display_select_multiple;
							
							$composite_field_to_display .= $field_to_display.$separator_display_select_multiple;
						}
						$results_table = substr_custom($results_table, 0, -(strlen_custom($separator_display_select_multiple))); // delete last <br>
						
						$composite_field_to_display = substr_custom($composite_field_to_display, 0, -(strlen($separator_display_select_multiple))); // delete last <br>
						
					}
					// if there is not a formatting function
					else{
					
                        $count_temp_2 = count($field_values_ar[0]);
                                       
                        for ($x=0; $x<$c_multiple;$x++){ // for each element of a select multiple (or just one for normal field)
                            for ($j=0; $j<$count_temp_2; $j++) { // for each linked value (or just one if this is not a lookup field)
                        
                                // if it's a lookup field and the linked table is installed, get the correct $field_type $field_content $field_separator
                                
                                if (($fields_labels_ar[$i]['type_field'] === 'select_single' || $fields_labels_ar[$i]['type_field'] === 'select_single_radio' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_menu' || $fields_labels_ar[$i]['type_field'] === 'select_multiple_checkbox') && $primary_key_field_field != "" && $primary_key_field_field != NULL && $linked_table_installed === 1){
    
                                    foreach ($fields_labels_linked_field_ar as $key => $fields_labels_linked_field_ar_element){
                                        if ($fields_labels_linked_field_ar_element['name_field'] === $linked_fields_ar[$j]) {
                                            $linked_field_type = $fields_labels_linked_field_ar_element['type_field'];
                                            $linked_field_content = $fields_labels_linked_field_ar_element['content_field'];
                                            $linked_field_separator = $fields_labels_linked_field_ar_element['separator_field'];
                                            $key_linked = $key;
                                    
                                        } // end if
                                    } // end foreach
    
                                    reset($fields_labels_linked_field_ar);
                            
                                    $field_to_display = get_field_correct_displaying($field_values_ar[$x][$j], $linked_field_type, $linked_field_content, $linked_field_separator, "results_table", $fields_labels_linked_field_ar[$key_linked], $template_infos_ar, $primary_key_field_field, $records_row[$field_name_temp], $is_items_table, $master_table_name, $table_name, $show_revisions, $records_row); // get the correct display mode for the field
                            
                            
                                } // end if
                                else {
                                    if ($unique_field_name != "" && $unique_field_name != NULL){
                                        $field_to_display = get_field_correct_displaying($field_values_ar[$x][$j], $field_type, $field_content, $field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar, $where_field, $where_value, $is_items_table, $master_table_name, $table_name, $show_revisions, $records_row); // get the correct display mode for the field
                                    }
                                    else{
                                        $field_to_display = get_field_correct_displaying($field_values_ar[$x][$j], $field_type, $field_content, $field_separator, "results_table", $fields_labels_ar[$i], $template_infos_ar, NULL, NULL, $is_items_table, $master_table_name, $table_name, $show_revisions, $records_row); // get the correct display mode for the field

                                    }
                                    
                                } // end else
                        
                                if ( $field_to_display == "") {
                                    $field_to_display = "&nbsp;";
                                }
                                
                                $composite_field_to_display .= $field_to_display.$separator_display_linked_field;
    
                                $results_table .= $field_to_display.$separator_display_linked_field; // add the field value to the table
                            }
                            
                            $results_table = substr($results_table, 0, -strlen($separator_display_linked_field)); // delete the last &nbsp;
                            
                            $composite_field_to_display = substr($composite_field_to_display, 0, -strlen($separator_display_linked_field)); // delete the last &nbsp;
                    
                            $results_table .= $separator_display_select_multiple;
                            $composite_field_to_display .= $separator_display_select_multiple; 
                        }
                        
                        $results_table = substr($results_table, 0, -(strlen($separator_display_select_multiple))); // delete last br
                        
                        $composite_field_to_display = substr($composite_field_to_display, 0, -(strlen($separator_display_select_multiple))); // delete last br
                        
					}
					
					$results_table .= $lookup_link."</td>"; // end the cell
				
					$fields_to_display_ar[$field_name_temp] = $composite_field_to_display;
				
				} // end if
			} // end for
		
			if (is_array($template_infos_ar) && $template_infos_ar['enable_template_table'] == '1'){
				// performance
				foreach ($fields_to_display_ar as $fields_to_display_ar_el){
					if (strpos_custom($fields_to_display_ar_el, 'dadabik_field') === false && strpos_custom($fields_to_display_ar_el, 'dadabik_link') === false){
					}
					else{
						echo 'Error';
						exit;
						break;
					}
				}
			
				$template_results_table .= parse_template($template_infos_ar);
			}
		
			$results_table .= "</tr>";
			
		} // end while
		
		$results_table .= "</tbody></table>";
		if ($template_infos_ar['enable_template_table'] == '1'){
			return $template_results_table;
		}
		else{
			return $results_table;
		}
	}
    
} // end function build_results_table
?>