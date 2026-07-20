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
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;



$use_unicode_sqlserver_transformations = 0;

function get_site_path()
// ****************************UP
{
	$parsed_url = parse_url($_SERVER['SCRIPT_NAME']);
	
	$site_path = $parsed_url['path'];
	
	if (substr($site_path, -4) == '.php') {
		$site_path = dirname($site_path);
	}

	if (substr($site_path, -1) != '/') {
		$site_path = $site_path . '/';
	}
	
	return $site_path;
}

function unlink_custom($file)
{
	if (is_file($file)){
		if (!unlink($file)){
			die('error while deleting a file.');
		}
	}
}

function check_blank_dot_names($table_name = NULL, $only_tables = 0, $only_installed = 0, $field_name = NULL)
// check if the field names of a table or the table name contain blank spaces, dots or other not allowed charaters, if no table in input, check all the tables, if a field name in input, just check that field name
{
    // PARTIAL CODE DUPLICATION WITH GET_VALID_FIELD_NAME and in db_synchro.php
    
	global $conn, $quote, $prefix_internal_table, $dbms_type, $alias_prefix, $null_checkbox_prefix, $select_type_select_suffix, $select_checkbox_prefix, $field_button_hint_container_id_prefix, $custom_button_ids_prefix, $valid_table_field_name_pattern;
	
	$items_containing_blank = array();
	
	if (!is_null($field_name) ){
	    // the same check is just after in this function
	    if (!preg_match($valid_table_field_name_pattern, $field_name) || strpos($field_name, ' ') !== false || strpos($field_name, '.') !== false || strpos($field_name, '\\') !== false || strpos($field_name, '\'') !== false || $dbms_type !== 'sqlite' && strpos($field_name, $quote) !== false || strpos($field_name, '"') !== false  || strpos($field_name, chr(0)) !== false  || strpos($field_name, $alias_prefix) !== false  || strpos($field_name, $null_checkbox_prefix) !== false   || strpos($field_name, $custom_button_ids_prefix) !== false  || strpos($field_name, $field_button_hint_container_id_prefix) !== false    || strpos($field_name, $select_checkbox_prefix) !== false  || strpos($field_name, $select_type_select_suffix) !== false || strpos($field_name, ';') !== false || $field_name === 'operator'  || strlen_custom($field_name) > 64){
            $items_containing_blank['fields']['field_names'][]=$field_name;
        }
	}
	else{
	
        if ($only_installed === 1){
            $fields_list = array();
            
            $sql = "SELECT ".$quote."name_field".$quote.",".$quote."table_name".$quote."  FROM ".$quote.$GLOBALS['dadabik_forms_tab_name'].$quote;
            
            $res = execute_db($sql, $conn);
        
            while($row = fetch_row_db($res)){
                $fields_list[$row['table_name']][] = $row['name_field'];
            }
        }
    
        if (is_null($table_name)){
            if ($only_installed === 0){
                $tables_names_ar = build_tables_names_array(0, 0, 1);
            }
            else{
                $tables_names_ar = build_tables_names_array(0, 1, 1);
            }
        }
        else{
            $tables_names_ar[0] = $table_name;
        }
    
        foreach ($tables_names_ar as $table_name) {
			
			// same check in orazio.php
            if (!preg_match($valid_table_field_name_pattern, $table_name) || strpos($table_name, ' ') !== false || strpos($table_name, '.') !== false || strpos($table_name, '\\') !== false || strpos($table_name, '\'') !== false || $dbms_type !== 'sqlite' && strpos($table_name, $quote) !== false || strpos($table_name, '"') !== false || strpos($table_name, chr(0)) !== false || strpos($table_name, ';') !== false || strlen_custom($table_name) > 64){
                $items_containing_blank['tables'][] = $table_name;
            }
        
            if ($only_tables === 0){
        
                if ($only_installed === 1){
                    // the same check is just after and in db_synchro.php
                   foreach ($fields_list[$table_name] as $field_name){
                        if (!preg_match($valid_table_field_name_pattern, $field_name) || strpos($field_name, ' ') !== false || strpos($field_name, '.') !== false || strpos($field_name, '\\') !== false || strpos($field_name, '\'') !== false || $dbms_type !== 'sqlite' && strpos($field_name, $quote) !== false || strpos($field_name, '"') !== false  || strpos($field_name, chr(0)) !== false  || strpos($field_name, $alias_prefix) !== false  || strpos($field_name, $null_checkbox_prefix) !== false   || strpos($field_name, $custom_button_ids_prefix) !== false  || strpos($field_name, $field_button_hint_container_id_prefix) !== false    || strpos($field_name, $select_checkbox_prefix) !== false  || strpos($field_name, $select_type_select_suffix) !== false || strpos($field_name, ';') !== false || $field_name === 'operator'  || strlen_custom($field_name) > 64){
                            $items_containing_blank['fields']['field_names'][]=$table_name.'.'.$field_name;
                            $items_containing_blank['fields']['table_names'][]=$table_name;
                        }
                   }
                }
                else{
        
                    $fields_ar =get_fields_list($table_name);
        
                    foreach($fields_ar as $field_name){
                        if (!preg_match($valid_table_field_name_pattern, $field_name) || strpos($field_name, ' ') !== false || strpos($field_name, '.') !== false || strpos($field_name, '\\') !== false || strpos($field_name, '\'') !== false || $dbms_type !== 'sqlite' && strpos($field_name, $quote) !== false || strpos($field_name, '"') !== false  || strpos($field_name, chr(0)) !== false  || strpos($field_name, $alias_prefix) !== false  || strpos($field_name, $null_checkbox_prefix) !== false    || strpos($field_name, $custom_button_ids_prefix) !== false  || strpos($field_name, $field_button_hint_container_id_prefix) !== false    || strpos($field_name, $select_checkbox_prefix) !== false  || strpos($field_name, $select_type_select_suffix) !== false || strpos($field_name, ';') !== false || $field_name === 'operator'  || strlen_custom($field_name) > 64){
                            $items_containing_blank['fields']['field_names'][]=$table_name.'.'.$field_name;
                            $items_containing_blank['fields']['table_names'][]=$table_name;
                        }
                    }
                }
            }
        
        }
    }
	
	return $items_containing_blank;
}

function copy_custom($file_1, $file_2)
{
	if (!copy($file_1, $file_2)){
		die('error while copying file '.$file_1.' '.$file_2);
	}
}

function get_unique_id()
{
	global $conn, $prefix_internal_table, $quote, $dbms_type;
	
	
	//begin_trans_db(); // non servono

	if ($dbms_type === 'mysql'){
		$sql = "INSERT INTO ".$quote.$prefix_internal_table."unique_ids".$quote." () VALUES ()";
	}
	elseif($dbms_type === 'postgres'){
		$sql = "INSERT INTO ".$quote.$prefix_internal_table."unique_ids".$quote." (value_unique_id) VALUES (DEFAULT)";
	}
	elseif($dbms_type === 'sqlite'){
		$sql = "INSERT INTO ".$quote.$prefix_internal_table."unique_ids".$quote." (value_unique_id) VALUES (NULL)";
	}
	elseif($dbms_type === 'sqlserver'){
		$sql = "INSERT ".$quote.$prefix_internal_table."unique_ids".$quote." DEFAULT VALUES";
	}
	
	$res = execute_db($sql, $conn);
	
		
	if ($dbms_type === 'mysql' || $dbms_type === 'sqlite' || $dbms_type === 'sqlserver'){
		$unique_id = get_last_ID_db();
	}
	elseif($dbms_type === 'postgres'){
		$unique_id = get_last_ID_db($prefix_internal_table.'unique_ids_value_unique_id_seq');
	}
	
	//complete_trans_db();
	
	return $unique_id;
	
	
	
}

function split_camelcase_word($word)
{
	// get in input 'heyMan' or 'HeyMan' and returns 'hey Man'
	$reg_exp = '/(?<=[a-z]) (?=[A-Z]) | (?<=[A-Z])(?=[A-Z][a-z]) /x';
	return implode(' ', preg_split($reg_exp, $word));
	
}

function strtolower_custom($string){
    if (is_null($string)){
        return '';
    }
	if (function_exists('mb_strtolower')){
		return mb_strtolower($string, 'UTF-8');
	}
	else{
		return strtolower($mb_strlen);
	}
} // end function strtolower_custom

function strrpos_custom(){
	$parameters_ar = func_get_args();
	
	if (!isset($parameters_ar[2])){
		$parameters_ar[2] = 0;
	}
	
	if (function_exists('mb_strrpos')){
		return mb_strrpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2], 'UTF-8');
	}
	else{
		return strrpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2]);
	}
} // end function strrpos_custom

function strlen_custom($string){
// ****************************UP
	if (function_exists('mb_strlen')){
		return mb_strlen($string, 'UTF-8');
	}
	else{
		return strlen($string);
	}
} // end function strlen_custom

function make_starndard_version($v, $n)
// ****************************UP
// from 5.12.6 to 005012006, useful to compare two versions, $n is the number of components
{
    $temp_ar = explode('.', $v);
    
    $v_standard = '';
    foreach($temp_ar as $value){
        
        if (strpos_custom($value, '-') !== false){ // remove e.g. -Manarola
            $value = substr_custom($value, 0, strpos_custom($value, '-'));
        }
        $v_standard .= str_pad($value, 3, "0", STR_PAD_LEFT);
    }
    
    if (count($temp_ar) < $n){
        for ($i=0; $i<($n-count($temp_ar));$i++){
            $v_standard .= '000';
        }
    }
    
    return $v_standard;
}

function strpos_custom(){
// ****************************UP
	$parameters_ar = func_get_args();
	
	if (!isset($parameters_ar[2])){
		$parameters_ar[2] = 0;
	}
	
	if (function_exists('mb_strpos')){
		return mb_strpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2], 'UTF-8');
	}
	else{
		return strpos($parameters_ar[0], $parameters_ar[1], $parameters_ar[2]);
	}
} // end function strpos_custom

function strtoupper_custom($string){
	if (function_exists('mb_strtoupper')){
		return mb_strtoupper($string, 'UTF-8');
	}
	else{
		return strtoupper($mb_strlen);
	}
} // end function strtolower_custom

function substr_custom(){
// ****************************UP
	$parameters_ar = func_get_args();
	
	if (is_null($parameters_ar[0])){
	    return '';
	}
	if (function_exists('mb_substr')){
		if (isset($parameters_ar[2])){
			return mb_substr($parameters_ar[0], $parameters_ar[1], $parameters_ar[2], 'UTF-8');
		}
		else{
			return mb_substr($parameters_ar[0], $parameters_ar[1], mb_strlen($parameters_ar[0]), 'UTF-8');
		}
		
	}
	else{
		if (isset($parameters_ar[2])){
			return substr($parameters_ar[0], $parameters_ar[1], $parameters_ar[2]);
		}
		else{
			return substr($parameters_ar[0], $parameters_ar[1]);
		}
	}
} // end function substr_custom

function mail_custom($to, $subject, $message, $header = '', $from_email = '', $from_name = '', $to_name = '') { 
    global $custom_mail_function;
    
    if ($custom_mail_function !== '' && function_exists($custom_mail_function)){
        call_user_func($custom_mail_function, $to, $subject, $message, $header, $from_email, $from_name, $to_name);
    }
    else{
        $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n"; 
		if ($from_email !== '' && $header === ''){
			$header = 'From:'.$from_email;
		}
        mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header); 
    }
  
} // end function

function format_date($date)
// from "2000-12-15" to "15 Dec 2000"
// ****************************UP
{
	global $date_format, $date_separator;
	$temp_ar=explode("-",$date);
	$temp_ar[2] = substr_custom($temp_ar[2], 0, 2); // e.g. from 11 00:00:00 to 11 if the field is datetime
	switch ($date_format){
		case "literal_english":
			//$date=@date("M j, Y",mktime(0,0,0,$temp_ar[1],$temp_ar[2],$temp_ar[0]));
            $date_object = date_create($date);
            $date = date_format($date_object, 'M j, Y');
            
			break;
		case "latin":
			$date = $temp_ar[2].$date_separator.$temp_ar[1].$date_separator.$temp_ar[0];
			break;
		case "numeric_english":
			$date = $temp_ar[1].$date_separator.$temp_ar[2].$date_separator.$temp_ar[0];
			break;
		case "ISO_8601":
			$date = $temp_ar[0].$date_separator.$temp_ar[1].$date_separator.$temp_ar[2];
			break;
	} // end switch
	return $date;
}
	
function format_date_time($date_time)
// from "2000-12-15" to "15 Dec 2000"
{
	global $date_format, $date_separator;
	$temp_ar=explode(" ",$date_time);
	
	$temp_2_ar=explode("-",$temp_ar[0]);
	
	$time_part = '';
	if (isset($temp_ar[1])){ // otherwise is just a date
		$time_part = ' '.$temp_ar[1];
	}
	switch ($date_format){
		case "literal_english":
			//$date_time=@date("M j, Y",mktime(0,0,0,$temp_2_ar[1],$temp_2_ar[2],$temp_2_ar[0])).$time_part;
			$date_object = date_create($date_time);
            $date_time = date_format($date_object, 'M j, Y').$time_part;
			break;
		case "latin":
			$date_time = $temp_2_ar[2].$date_separator.$temp_2_ar[1].$date_separator.$temp_2_ar[0].$time_part;
			break;
		case "numeric_english":
			$date_time = $temp_2_ar[1].$date_separator.$temp_2_ar[2].$date_separator.$temp_2_ar[0].$time_part;
			break;
		case "ISO_8601":
			$date_time = $temp_2_ar[0].$date_separator.$temp_2_ar[1].$date_separator.$temp_2_ar[2].$time_part;
			break;
	} // end switch
	return $date_time;
}

function split_date($date, &$day, &$month, &$year)
// goal: split a mysql date returning $day, $mont, $year
// input: $date, a MySQL date, &$day, &$month, &$year
// output: &$day, &$month, &$year
{
	$temp=explode("-",$date); 
	$day=$temp[2];
	$month=$temp[1];
	$year=$temp[0];
} // end function split_date

function split_date_time($date, &$day, &$month, &$year, &$hours, &$minutes, &$seconds)
// goal: split a mysql datetime returning $day, $mont, $year $hours $minutes $seconds
// input: $datetime, a MySQL datetime
// output: &$day, &$month, &$year...
{
	$temp=explode(" ",$date); 
	$temp_2 = explode("-",$temp[0]); 
	$temp_3 = explode(":",$temp[1]); 
	$day=$temp_2[2];
	$month=$temp_2[1];
	$year=$temp_2[0];
	
	$hours=$temp_3[0];
	$minutes=$temp_3[1];
	$seconds=$temp_3[2];
} // end function split_date

function build_date_select_type_select($field_name)
// goal: build a select with operators: nothing = > <
// input: $field_name
// output: $operator_select
{
	$operator_select = "<div class=\"select_element select_element_selec_type\">";
	$operator_select .= "<select name=\"".$field_name."\">";
	$operator_select .= "<option value=\"\"></option>";
	$operator_select .= "<option value=\"=\">=</option>";
	$operator_select .= "<option value=\">\">></option>";
	$operator_select .= "<option value=\"<\"><</option>";
	$operator_select .= "</select></div>";

	return $operator_select;
} // end function build_date_select_type_select

function display_sql($sql)
// goal: display a sql query
// input: $sql
// output: nothing
// global: $display_sql
{
	global $display_sql, $dbms_type, $use_unicode_sqlserver_transformations, $unicode_sqlserver_transformations;
	
	if ($dbms_type === 'sqlserver' && $use_unicode_sqlserver_transformations === 1){
	    
	    foreach($unicode_sqlserver_transformations as $transformation){
	        $sql = str_ireplace($transformation['input'], $transformation['output'], $sql);
	    }
	}
	
	if ($display_sql == "1"){
		//echo "<p><font color=\"#ff0000\"><b>Your SQL query (for debugging purpose): </b></font>".htmlentities($sql)."</p>";
		echo "<p><font color=\"#ff0000\"><b>Your SQL query (for debugging purposes): </b></font>".htmlspecialchars($sql)."</p>";
	} // end if
} // end function display_sql

function txt_out($message, $class="")
// goal: display text
// input: $message, $font_size, $font_color, $bold (1 if bold)
// output: nothing
{
	if ( $class != "") {
		$message = "<font class=\"".$class."\">".$message."</font>";
	}
	
	echo $message;
} // end function txt_out

function get_pages_number($results_number, $records_per_page)
// goal: calculate the total number of pages necessary to display results
// input: $results_number, $records_per_page
// ouptut: $pages_number
{
	$pages_number = $results_number / $records_per_page;
	$pages_number = (int)($pages_number);
	if (($results_number % $records_per_page) != 0) $pages_number++; // if the reminder is greater than 0 I have to add a page because I have to round to excess

	return $pages_number;
} // end function get_pages_number

function build_date_select ($field_name, $day, $month, $year, $update_function = 0, $for_filters = 0, $disabled_attribute='')
// goal: build three select to select a data (day, mont, year), if are set $day, $month and $year select them
// input: $field_name, the name of the date field, $day, $month, $year (or "", "", "" if not set), $update_function (1 if call from update, it means I have to check if the data is representable)
// output: $date_select, the HTML date select
// global $start_year, $end_year
{
	global $start_year, $end_year, $year_field_suffix, $month_field_suffix, $day_field_suffix;

	$date_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	
	$day_select .= "<select".$disabled_attribute." name=\"".$field_name.$day_field_suffix."\">";
	$month_select .= "<select".$disabled_attribute." name=\"".$field_name.$month_field_suffix."\">";
	$year_select .= "<select".$disabled_attribute." name=\"".$field_name.$year_field_suffix."\">";
	
	$count_representable_field = 0;

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected";
		} // end if
		if($day == $i){
			$count_representable_field++;
		} // end if
		
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected";
		} // end if
		if($month == $i){
			$count_representable_field++;
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected";
		} // end if
		if($year == $i){
			$count_representable_field++;
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for
	
	if ($update_function === 1 && $count_representable_field !== 3){
		return false;
	}

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";
	
	if ($for_filters === 1){
		$date_select = "<td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td>";
	}
	else{
		$date_select = "<td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td>";
	}

	

	return $date_select;

} // end function build_date_select

function build_date_time_select ($field_name, $day, $month, $year, $hours, $minutes, $seconds, $update_function = 0, $for_filters = 0, $disabled_attribute='')
// goal: build six dropdown mensu  to select a datetime
// input: $update_function (1 if call from update, it means I have to check if the data is representable)
// output: $date_time_select, the HTML date select
{
	global $start_year, $end_year, $year_field_suffix, $month_field_suffix, $day_field_suffix, $hours_field_suffix, $minutes_field_suffix, $seconds_field_suffix;

	$date_time_select = "";
	$day_select = "";
	$month_select = "";
	$year_select = "";
	$hours_select = "";
	$minutes_select = "";
	$seconds_select = "";
	
	$day_select .= "<select".$disabled_attribute." name=\"".$field_name.$day_field_suffix."\">";
	$month_select .= "<select".$disabled_attribute." name=\"".$field_name.$month_field_suffix."\">";
	$year_select .= "<select".$disabled_attribute." name=\"".$field_name.$year_field_suffix."\">";
	$hours_select .= "<select".$disabled_attribute." name=\"".$field_name.$hours_field_suffix."\">";
	$minutes_select .= "<select".$disabled_attribute." name=\"".$field_name.$minutes_field_suffix."\">";
	$seconds_select .= "<select".$disabled_attribute."  name=\"".$field_name.$seconds_field_suffix."\">";
	
	$count_representable_field = 0;

	for ($i=1; $i<=31; $i++){
		$day_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($day != "" and $day == $i){
			$day_select .= " selected";
		} // end if
		if($day == $i){
			$count_representable_field++;
		} // end if
		
		$day_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=1; $i<=12; $i++){
		$month_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($month != "" and $month == $i){
			$month_select .= " selected";
		} // end if
		if($month == $i){
			$count_representable_field++;
		} // end if
		$month_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	for ($i=$start_year; $i<=$end_year; $i++){
		$year_select .= "<option value=\"$i\"";
		if($year != "" and $year == $i){
			$year_select .= " selected";
		} // end if
		if($year == $i){
			$count_representable_field++;
		} // end if
		$year_select .= ">".$i."</option>";
	} // end for
	
	if ($update_function === 1 && $count_representable_field !== 3){
		return false;
	}
	

	for ($i=0; $i<=23; $i++){
		$hours_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($hours != "" and $hours == $i){
			$hours_select .= " selected";
		} // end if
		$hours_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for
	for ($i=0; $i<=59; $i++){
		$minutes_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($minutes != "" and $minutes == $i){
			$minutes_select .= " selected";
		} // end if
		$minutes_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for
	for ($i=0; $i<=59; $i++){
		$seconds_select .= "<option value=\"".sprintf("%02d",$i)."\"";
		if($seconds != "" and $seconds == $i){
			$seconds_select .= " selected";
		} // end if
		$seconds_select .= ">".sprintf("%02d",$i)."</option>";
	} // end for

	$day_select .= "</select>";
	$month_select .= "</select>";
	$year_select .= "</select>";
	$hours_select .= "</select>";
	$minutes_select .= "</select>";
	$seconds_select .= "</select>";
	
	if ($for_filters === 1){
		$date_time_select = "<td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$hours_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$minutes_select."</div></td><td valign=\"top\"><br/><div class=\"select_element select_element_date_dd_mm\">".$seconds_select."</div></td>";
	}
	else{
		$date_time_select = "<td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$day_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$month_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_yyyy\">".$year_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$hours_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$minutes_select."</div></td><td valign=\"top\"><div class=\"select_element select_element_date_dd_mm\">".$seconds_select."</div></td>";
	}

	

	return $date_time_select;

} // end function build_date_select

function contains_numerics($string)
// goal: verify if a string contains numbers
// input: $string
// output: true if the string contains numbers, false otherwise
{
	$count_temp = strlen_custom($string);
	//if(ereg("[0-9]+", $string)) {
	
	if(preg_match("/^.*[0-9]+.*$/", $string)) {
		return true;
		
	}
	return false;
	
} // end function contains_numerics

function is_valid_email($email)
// goal: chek if an email address is valid, according to its syntax
// input: $email
// output: true if it's valid, false otherwise
{
    global $allows_leading_trailing_spaces_email;
    
    if ($allows_leading_trailing_spaces_email === 1){
   return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?a-z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9a-z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9a-z]){2,24}$/',                    // top-level domain (TLD) 
        trim(strtolower_custom($email)))); 
    }
    else{
         return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?a-z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9a-z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9a-z]){2,24}$/',                    // top-level domain (TLD) 
        (strtolower_custom($email)))); 
    }
} // end function is_valid_email

function is_valid_url($url)
// goal: chek if an url address is valid, according to its syntax
// input: $url
// output: true if it's valid, false otherwise
{
	//return (preg_match("/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i", $url));
	
	return ( ! preg_match('/^(http|https|ftp):\/\/([a-z0-9][a-z0-9_-]*(?:\.[a-z0-9][a-z0-9_-]*)+):?(\d+)?\/?/', strtolower_custom($url))) ? FALSE : TRUE;

} // end function is_valid_url

function get_valid_field_name($name)
{
    global $quote, $alias_prefix, $null_checkbox_prefix, $select_type_select_suffix, $select_checkbox_prefix, $field_button_hint_container_id_prefix, $custom_button_ids_prefix;
    
    // partial code duplication with check_blank_dot_names
    
    $characters_to_change = array(' ', '.', ';', '\\', '\'', '"', $quote, chr(0), $alias_prefix, $null_checkbox_prefix, $select_checkbox_prefix, $select_type_select_suffix, $field_button_hint_container_id_prefix, $custom_button_ids_prefix, 'operator');
    
    
    foreach($characters_to_change as $char){
        $name = str_replace($char, '_', $name);
    }
    
    return strtolower($name);
}

function is_valid_phone($phone)
// goal: chek if a phone numbers is valid, according to its syntax (should be: "+390523599314")
// input: $phone
// output: true if it's valid, false otherwise
{
	
	if ($phone[0] === "+"){
		$phone = substr_custom($phone, 1); // delete the "+"
	}
	$phone = str_replace('-', '', $phone);
	$phone = str_replace(' ', '', $phone);
	
	if (!is_numeric($phone)){
		return false;
	} // end if
	
	return true;
} // end function is_valid_phone

function db_error($sql)
// goal: exit the script
// input: $sql
// output: nothing
{
	exit;
} // end function db_error

function check_password_hash($password_clear, $password_hash)
// the function must the boolean false (failure) or true (success)
{
	global $generate_portable_password_hash;

	$info = password_get_info($password_hash);
	
	if ($info['algo'] !== 0 && $info['algo'] !== NULL) { // it is compatible with password_verify
		
		return password_verify($password_clear, $password_hash);
	} else {
		// Fallback to old method (only for old hashes)
		$t_hasher = new PasswordHash(8, $generate_portable_password_hash);
		$check = $t_hasher->CheckPassword($password_clear, $password_hash);
		return $check;
	}
} // end function check_password_hash

function array_orderby()
// goal: order an array having the form $a[0]['name'] $a[0]['price'] $a[0]['age'] $a[1]['name'] $a[1]['price'] $a[1]['age'], ... by a column, database-style
// input: the array and a list (at least one) of field/orderstyle couples, e.g. $array = array_orderby($array, 'price', SORT_DESC)
{
    $args = func_get_args();
    
    $input_array = array_shift($args);
    foreach ($args as $key => $field) {
        if (is_string($field)) { // it's a field, otherwise it's SORT_DESC or SORT_ASC or the array
            $temp = array();
            // create intermediate arrays
            foreach ($input_array as $key2 => $row)
                $temp[$key2] = $row[$field];
            	$args[$key] = $temp;
            }
    }
    $args[] = &$input_array;
    
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

// ****************************UP
function create_password_hash($password_clear)
{
	
	return password_hash($password_clear, PASSWORD_DEFAULT);

} // end function create_password_hash

function ldap_apply_escape_if_enabled_dn($string)
{
    global $enable_ldap_escape_dn;
    
    if ($enable_ldap_escape_dn === 1){
        return ldap_escape($string, '', LDAP_ESCAPE_DN);
    }
    return $string;
}

function ldap_apply_escape_if_enabled_filter($string)
{
    global $enable_ldap_escape_filter;
    
    if ($enable_ldap_escape_filter === 1){
        return ldap_escape($string, '', LDAP_ESCAPE_FILTER);
    }
    return $string;
}


/* not used anymore, the built-in ldap_escape function is now used  (see ldap_escape_dn and ldap_escape_filter)
function ldap_escape_2($string, $is_for_dn) 
{
	
	$string_escaped = str_replace('\\','\\'.str_pad(dechex(ord('\\')), 2, '0'),$string);

	if ($is_for_dn === true){
		$chars_to_escape_ar = array(',', '=', '+', '<', '>', ';', '"'); 
	}
	else{
		$chars_to_escape_ar = array('*', ')', '(', chr(0)); 
	}
	
	$escaped_chars = array();
	
	foreach ($chars_to_escape_ar as $key => $value){
		$escaped_chars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
	}
	
	$string_escaped = str_replace($chars_to_escape_ar,$escaped_chars,$string_escaped);
	
	
	if (substr_custom($string_escaped, 0, 1) === ' '){
		$string_escaped = '\\'.str_pad(dechex(ord(' ')), 2, '0').substr_custom($string_escaped, 1);
	}
	
	if (substr_custom($string_escaped, 0, 1) === '#'){
		$string_escaped = '\\'.str_pad(dechex(ord('#')), 2, '0').substr_custom($string_escaped, 1);
	}
	
	if (substr_custom($string_escaped, -1) === ' '){
		$string_escaped = substr_custom($string_escaped, 0, -1).'\\'.str_pad(dechex(ord(' ')), 2, '0');
	}
	 
	return $string_escaped;

}
*/

function is_curl_installed()
// ****************************UP
{
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}

function file_get_contents_3($url, $timeout=3)
// ****************************UP
{
    if ((int)ini_get('allow_url_fopen') == 1){
        
        // @ because otherwise if there is an error, the URL called is displayed 
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> true,
                "verify_peer_name"=> true
            ),
        );
        return @file_get_contents($url, false, stream_context_create($arrContextOptions));
        
        
    }
    elseif (is_curl_installed() === true){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);

    
        curl_close($ch);
        

        return $result;
    }
    else{
        return false;
        //echo 'Error: you need the cURL PHP extension installed or, alternatively, enable allow_url_fopen.';
    }
}

/* not used anymore
function file_get_contents_2($url, $timeout=3)
// ****************************UP
{

    if (is_curl_installed() === true){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);

    
        curl_close($ch);
        

        return $result;
    }
    else{
        $result = file_get_contents($url);
        
        return $result;
    }
}*/


function generate_barcode_id($table_name, $field_name, $unique_field, $last_inserted_ID )
{
	if (function_exists('generate_barcode_id_custom')){
		return generate_barcode_id_custom($table_name, $field_name, $unique_field, $last_inserted_ID);
	}
	return $table_name.'_'.$last_inserted_ID;

}

function generate_barcode($text, $field_type){

	global $barcode_format, $barcode_image_format, $qrcode_size;
	

	if (function_exists('generate_barcode_custom')){
		return generate_barcode_custom($text, $field_type, $barcode_format, $barcode_image_format, $qrcode_size);
	}

	// convert $barcode_format (e.g. TYPE_CODE_128 to the corresponding class name e.g. Picqer\\Barcode\\Types\\TypeCode128 )
	$class_name = str_replace('_', ' ', strtolower($barcode_format));
    $class_name = ucwords($class_name);
    $class_name = str_replace(' ', '', $class_name);
	$class_name = "Picqer\\Barcode\\Types\\".$class_name;

	require_once 'vendor/autoload.php';
	
	if ($field_type === 'barcode'){
		// Make Barcode object
		$barcode = (new $class_name())->getBarcode($text);

		switch($barcode_image_format){
			case 'svg':
				$renderer = new Picqer\Barcode\Renderers\SvgRenderer();
				return $renderer->render($barcode);
				break;
			case 'png';
				$renderer = new Picqer\Barcode\Renderers\PngRenderer();
				return '<img src="data:image/png;base64,' . base64_encode($renderer->render($barcode, $barcode->getWidth() * 2)) . '">';
				break;
			default:
				die('Wrong barcode image format');
		}
	}
	elseif($field_type === 'qrcode'){
		

		$renderer = new GDLibRenderer($qrcode_size);
		$writer = new Writer($renderer);

		$data = $writer->writeString($text);
		return '<img src="data:image/png;base64,'.base64_encode($data).'" alt="'.htmlspecialchars($text).'">';

	}
	else{
		die('Wrong barcode field type');
	}
}

function format_barcode_layout($table_name, $field_name, $field_type, $barcode_image, $barcode_text)
{	
	if (function_exists('format_barcode_layout_custom')){
		return format_barcode_layout_custom($table_name, $field_name, $field_type, $barcode_image, $barcode_text);
	}
	
	if ($field_type == 'barcode'){
		return '<div style="text-align:center;">'.$barcode_image.'<br>'.$barcode_text.'</div>';
	}
	elseif ($field_type == 'qrcode'){
		return $barcode_image;
	}
	else{
		die('Wrong barcode field type');
	}
	
}
?>