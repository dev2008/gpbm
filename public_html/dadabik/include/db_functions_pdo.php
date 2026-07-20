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

$use_unicode_sqlserver_transformations = 0;

function connect_db($server, $user, $password, $name_db, $exit_on_error = 1)
{
	global $debug_mode, $dbms_type, $db_schema, $sqlserver_conn_additional_attributes, $disable_mysql_multiple_statements, $page_name, $trigger_fatal_error_db_operations, $additional_db_connection_string, $additional_db_connection_parameters, $error_enable_reporting;
	
		try {
			
			$temp = explode(':', $server);
			
			$server = $temp[0];
			$port_string = '';
			if (isset($temp[1])){
			    if ($dbms_type !== 'sqlserver'){
				    $port_string = ';port='.$temp[1];
				}
				else{
				    $port_string = ','.$temp[1];
				}
			}
			switch ($dbms_type){
				
				case 'sqlite':
					$array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

					if (isset($additional_db_connection_parameters) && is_array($additional_db_connection_parameters)) {
						$array_parameters = $array_parameters + $additional_db_connection_parameters;
					}

					$conn = new PDO($dbms_type.":".$name_db.$additional_db_connection_string, $user, $password, $array_parameters);
					break;
				case 'mysql':
					
					$array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
					
					if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS') && $disable_mysql_multiple_statements === 1) {
					    $array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_MULTI_STATEMENTS => FALSE);
					}
					else{
					    $array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
					}

					if (isset($additional_db_connection_parameters) && is_array($additional_db_connection_parameters)) {
						$array_parameters = $array_parameters + $additional_db_connection_parameters;
					}
					
					if ($page_name === 'install' || $page_name === 'check_requirements' ){
					    $conn = new PDO('mysql:host='.$server.$port_string.$additional_db_connection_string, $user, $password, $array_parameters);
					    $res = execute_db("CREATE DATABASE IF NOT EXISTS `".$name_db.'` character set utf8mb4 collate utf8mb4_unicode_ci', $conn); // I can't use $quote because in check_Requirements common_start is not included
					    $conn = new PDO('mysql:host='.$server.$port_string.';dbname='.$name_db.$additional_db_connection_string, $user, $password, $array_parameters);
					}
					else{
					    $conn = new PDO('mysql:host='.$server.$port_string.';dbname='.$name_db.$additional_db_connection_string, $user, $password, $array_parameters);
					}
					
					$res = execute_db("SET NAMES 'utf8mb4'", $conn);
					
					break;
				case 'sqlserver':

					$array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

					if (isset($additional_db_connection_parameters) && is_array($additional_db_connection_parameters)) {
						$array_parameters = $array_parameters + $additional_db_connection_parameters;
					}
					
					$conn = new PDO('sqlsrv:Server='.$server.$port_string.';Database='.$name_db.$additional_db_connection_string, $user, $password, $array_parameters);
					
					foreach($sqlserver_conn_additional_attributes as $key => $value){
					    $conn->setAttribute( $key , $value);
					}
					
					break;
				case 'postgres':

					$array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

					if (isset($additional_db_connection_parameters) && is_array($additional_db_connection_parameters)) {
						$array_parameters = $array_parameters + $additional_db_connection_parameters;
					}
					
					$conn = new PDO('pgsql:dbname='.$name_db.$port_string.';host='.$server.$additional_db_connection_string, $user, $password, $array_parameters);
					
					$res = execute_db("SET NAMES 'UTF8'", $conn);
					
					$res = execute_db("SET search_path TO ".$db_schema."", $conn);
					
					break;
				default:
					echo 'Error';
					exit;
			}
			$conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true); 
			
			return $conn;
  		}
		catch(PDOException $e)
    	{	
    		echo '<b>[06] Error:</b> during database connection. Please check $host, $user, $pass and $db_name in your config.php';
			if ($debug_mode === 1){
				echo '<br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				//echo ', set $debug_mode to 1 in your config.php to get further error information ';
			}
			
			if ($exit_on_error === 0){
				if ($error_enable_reporting === 1){
					
					dadabik_error_handler(
						E_USER_ERROR,
						$e->getMessage(),
						$e->getFile(),
						$e->getLine()
					);
				}
			}
			
			// if ($trigger_fatal_error_db_operations === 1){
			//     trigger_error('connection error', E_USER_ERROR);
			// }
			
			return NULL;
    	}
    
	
}

function create_table_db($conn, $table_name, $fields)
{
	global $dbms_type, $quote;
	
	if ($dbms_type === 'sqlserver'){
		//$fields = str_ireplace(' varchar(', ' nvarchar(', $fields);
		//$fields = str_ireplace(' char(', ' nchar(', $fields);
		$fields = str_ireplace(' text', ' varchar(max)', $fields);
	}
	
	$sql = "CREATE TABLE ".$quote.$table_name.$quote." (".$fields;
	
	if ($dbms_type === 'mysql'){
		$sql .= ' ENGINE=InnoDB';
	}

	execute_db($sql, $conn, 0, 0);
}

function drop_table_db($conn, $table_name, $table_type = 'table')
{
    global $quote;

	if (table_exists($table_name)) {
	    
	    if ($table_type === 'table'){
	        $sql = "DROP TABLE $quote$table_name$quote";
	    }
	    elseif($table_type === 'view'){
	        $sql = "DROP VIEW $quote$table_name$quote";
	    }
	    else{
	        die('Error, type must be table or view');
	    }

		execute_db($sql, $conn);
		
		return 'ok';
	} // end if
}

function delete_field_db($conn, $tablename_field_deletion, $fieldname_field_deletion)
{
    global $quote, $dbms_type;
	if (table_exists($tablename_field_deletion)) {
	
	    $fields_ar = get_fields_list($tablename_field_deletion);
	    
	    if (in_array($fieldname_field_deletion, $fields_ar)){
	    
	        if ($dbms_type === 'sqlserver'){
            
                $sql = "ALTER TABLE ".$quote.$tablename_field_deletion.$quote." DROP COLUMN ".$quote.$fieldname_field_deletion.$quote;
            }
            else{
                $sql = "ALTER TABLE ".$quote.$tablename_field_deletion.$quote." DROP  ".$quote.$fieldname_field_deletion.$quote;
            }

            execute_db($sql, $conn);
        
            return 'ok';
        }
	} // end if
}

function create_index_db($conn, $data_dictionary, $table_name, $index_name, $index_fields, $options_ar)
{
	$sql_ar = $data_dictionary->CreateIndexSQL($index_name, $table_name, $index_fields, $options_ar);
	foreach ($sql_ar as $sql){
		execute_db($sql, $conn);
	} // end foreach
}

function execute_db($sql, $conn, $return_control_on_error = 0, $show_logout_message = 1)
{
	global $debug_mode, $dbms_type, $unicode_sqlserver_transformations, $use_unicode_sqlserver_transformations, $trigger_fatal_error_db_operations, $mysql_set_sql_big_selects, $error_enable_reporting;
	
	if ($dbms_type === 'sqlserver' && $use_unicode_sqlserver_transformations === 1){
	    
	    foreach($unicode_sqlserver_transformations as $transformation){
	        $sql = str_ireplace($transformation['input'], $transformation['output'], $sql);
	    }
	}
    	
    try {	
        if ($mysql_set_sql_big_selects === 1){
            $conn->query ("SET SQL_BIG_SELECTS = 1");
        }
    	$results = $conn->query($sql);

    	//$results->setFetchMode(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
    	if ($return_control_on_error === 1){
    		$res['error_message'] = $e->getMessage();
    		return $res;
    	}
    	else{
			echo '<p><b>[08] Error:</b> during query execution.';
			if ($debug_mode === 1){
				echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				//echo ' Set $debug_mode to 1 in your config.php to get further error information ';
			}
			if ($show_logout_message === 1){
			    
			    // install already have $show_logout_message = 0 so GLOBALS['page_name'] !== 'install' should be useless
			    if (!isset($GLOBALS['page_name']) || $GLOBALS['page_name'] !== 'install' && $GLOBALS['page_name'] !== 'upgrade' && $GLOBALS['page_name'] !== 'check_requirements'){
			        echo '<p><b>If this error persists when you open a page</b>, try to <a href="login.php?function=logout">logout</a> and login again (this may remove an incorrect search filter).</p>';
			        
			    }
			}

			if ($error_enable_reporting === 1){
				$GLOBALS['latest_query'] = $sql;
				$GLOBALS['latest_query_params'] = array();
				$GLOBALS['latest_query_additional_params'] = array();

				dadabik_error_handler(
					E_USER_ERROR,
					$e->getMessage(),
					$e->getFile(),
					$e->getLine(),
					$show_logout_message
				);
			}

			
			
			// if ($trigger_fatal_error_db_operations === 1){
			//     trigger_error('query error', E_USER_ERROR);
			// }
			
			exit();
		}
    }
    
	return $results;
}

function prepare_db($conn, $sql, $return_control_on_error = 0)
{
	global $debug_mode, $dbms_type, $use_unicode_sqlserver_transformations, $unicode_sqlserver_transformations, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	$GLOBALS['latest_query'] = $sql;
	$GLOBALS['latest_query_params'] = array();
	
	if ($dbms_type === 'sqlserver' && $use_unicode_sqlserver_transformations === 1){
	    
	    foreach($unicode_sqlserver_transformations as $transformation){
	        $sql = str_ireplace($transformation['input'], $transformation['output'], $sql);
	    }
	}	
    try {
    	$res_prepare = $conn->prepare($sql);
    }
    catch(PDOException $e){
    	if ($return_control_on_error === 1){
    		$res_prepare['error_message'] = $e->getMessage();
    		return $res;
    	}
    	else{
			echo '<p><b>[08] Error:</b> during query preparation.';
			if ($debug_mode === 1){
				echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				//echo ' Set $debug_mode to 1 in your config.php to get further error information ';
			}

			if ($error_enable_reporting === 1){
			
				dadabik_error_handler(
					E_USER_ERROR,
					$e->getMessage(),
					$e->getFile(),
					$e->getLine()
				);
			}

			// if ($trigger_fatal_error_db_operations === 1){
			//     trigger_error('query preparation error', E_USER_ERROR);
			// }
			exit();
		}
    }
    
	return $res_prepare;
}

function bind_param_db($res_prepare, $key, $value, $return_control_on_error = 0)
{
	global $debug_mode, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	$GLOBALS['latest_query_params'][$key] = $value;

	try {
		$res_prepare->bindParam($key, $value);
	}
    catch(PDOException $e){
    	if ($return_control_on_error === 1){
    		$res_prepare['error_message'] = $e->getMessage();
    		return $res;
    	}
    	else{
			echo '<p><b>[08] Error:</b> during query execution.';
			if ($debug_mode === 1){
				echo ' <br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				//echo ' Set $debug_mode to 1 in your config.php to get further error information ';
			}
			if ($error_enable_reporting === 1){
					
					dadabik_error_handler(
						E_USER_ERROR,
						$e->getMessage(),
						$e->getFile(),
						$e->getLine()
					);
				}

			// if ($trigger_fatal_error_db_operations === 1){
			//     trigger_error('bind error', E_USER_ERROR);
			// }
			exit();
		}
    }
    return $res_prepare;
}

function execute_prepared_db($res_prepare, $return_control_on_error = 0, $params = NULL)
{
	global $debug_mode, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	$res_execute = new stdClass();
    	
    try {
    	$res_execute = $res_prepare->execute($params);
    }
    catch(PDOException $e){
    	if ($return_control_on_error === 1){
    		$res_execute->error_message = $e->getMessage().' SQL Query: '.$res_prepare->queryString;
    		return $res_execute;
    	}
    	else{
			echo '<p><b>[08] Error:</b> during query execution (prepared).';
			if ($debug_mode === 1){
				echo  $res_prepare->queryString.' The DBMS server said: '.$e->getMessage();
			}
			else{
				//echo ' Set $debug_mode to 1 in your config.php to get further error information ';
			}

			if ($error_enable_reporting === 1){

			
				$GLOBALS['latest_query_additional_params'] = $params;

				dadabik_error_handler(
					E_USER_ERROR,
					$e->getMessage(),
					$e->getFile(),
					$e->getLine()
				);
			}
			
			// if ($trigger_fatal_error_db_operations === 1){
			//     trigger_error('query error', E_USER_ERROR);
			// }
			exit();
		}
    }
    return $res_execute;
}

function format_date_for_dbms($date)
{
	return "'".$date."'";
}

function format_date_time_for_dbms($date_time)
{
	return "'".$date_time."'";
}

function get_db_function_string($function, $field)
// get in input a DB function and a field to apply the function on and returns the translation for the current DBMS
// the function assumes that $field is alrady quoted
{
	global $dbms_type, $weeks_start_on_sunday;
	
	switch (strtolower($function)){
		case 'year':
			switch($dbms_type){
				case 'mysql':
					$function_string = "YEAR(".$field.")";
					break;
				case 'sqlserver':
					$function_string = "DATEPART(YEAR, ".$field.")";
					break;
				case 'sqlite':
					$function_string = "strftime('%Y',".$field.")";
					break;
				case 'postgres':
					$function_string = "EXTRACT (YEAR FROM ".$field.")";
					break;
			}
			break;
		case 'month':
			switch($dbms_type){
				case 'mysql':
					$function_string = "MONTH(".$field.")";
					break;
				case 'sqlserver':
					$function_string = "DATEPART(MONTH, ".$field.")";
					break;
				case 'sqlite':
					$function_string = "strftime('%m',".$field.")";
					break;
				case 'postgres':
					$function_string = "EXTRACT (MONTH FROM ".$field.")";
					break;
			}
			break;
		case 'quarter':
			switch($dbms_type){
				case 'mysql':
					$function_string = "QUARTER(".$field.")";
					break;
				case 'sqlserver':
					$function_string = "DATEPART(QUARTER, ".$field.")";
					break;

				case 'sqlite':
				
					$function_string = "CASE WHEN cast(strftime('%m', ".$field.") as integer) BETWEEN 1 AND 3 THEN 1 WHEN cast(strftime('%m', ".$field.") as integer) BETWEEN 4 and 6 THEN 2  WHEN cast(strftime('%m', ".$field.") as integer) BETWEEN 7 and 9 THEN 3  ELSE 4 END";
  
					break;
				case 'postgres':
					$function_string = "EXTRACT (QUARTER FROM ".$field.")";
					break;
			}
			break;
		case 'week':
			switch($dbms_type){
				case 'mysql':
					$function_string = "WEEK(".$field.")";
					break;
				case 'sqlserver':
					$function_string = "DATEPART(WEEK, ".$field.")";
					break;
				case 'sqlite':
					$function_string = "strftime('%W',".$field.")";
					break;
				case 'postgres':
					$function_string = "EXTRACT (WEEK FROM ".$field.")";
					break;
			}
			break;
		case 'dayofweek':
			switch($dbms_type){
				case 'mysql':
					if ($weeks_start_on_sunday === 1){
						$function_string = "DAYOFWEEK(".$field.")";
					}
					else{
						$function_string = "WEEKDAY(".$field.")+1";
					}
					break;
				case 'sqlserver':
				    // mssql default starts on sunday
					if ($weeks_start_on_sunday === 1){
						$function_string = "DATEPART(DW, ".$field.")";
					}
					else{
						
						$function_string = "CASE WHEN DATEPART(DW, ".$field.")-1 = 0 THEN '7' ELSE DATEPART(DW, ".$field.")-1  END";
					}
					break;
				case 'sqlite':
				
					if ($weeks_start_on_sunday === 1){
						$function_string = "strftime('%w',".$field.")+1";
					}
					else{
						 $function_string = "CASE WHEN cast(strftime('%w', ".$field.") as integer) = 0 THEN '7' ELSE strftime('%w', ".$field.")  END";
					}
					
					
					break;
				case 'postgres':
					if ($weeks_start_on_sunday === 1){
					    // the day of the week as Sunday(0) to Saturday(6)
						$function_string = "EXTRACT (DOW FROM ".$field.")+1";
					}
					else{
					    // the day from Monday(1) to Sunday(7)
						$function_string = "EXTRACT (ISODOW FROM ".$field.")";
					}
					break;
			}
			break;
		case 'hour':
			switch($dbms_type){
				case 'mysql':
					$function_string = "HOUR(".$field.")";
					break;
				case 'sqlserver':
					$function_string = "DATEPART(HOUR, ".$field.")";
					break;
				case 'sqlite':
					$function_string = "strftime('%H',".$field.")";
					break;
				case 'postgres':
						$function_string = "EXTRACT (HOUR FROM ".$field.")";
					break;
			}
			break;
		default:
			echo 'Error';
			exit;
	}
	
	return $function_string;
	
}

function execute_db_limit($sql, $conn, $records_page, $start_from)
{
	global $debug_mode, $dbms_type, $use_unicode_sqlserver_transformations, $unicode_sqlserver_transformations, $trigger_fatal_error_db_operations, $mysql_set_sql_big_selects, $error_enable_reporting;
	
	if ($dbms_type === 'sqlserver' && $use_unicode_sqlserver_transformations === 1){
	    
	    foreach($unicode_sqlserver_transformations as $transformation){
	        $sql = str_ireplace($transformation['input'], $transformation['output'], $sql);
	    }
	}
	
	switch($dbms_type){
		case 'mysql':
		case 'sqlite':
			$sql .= " LIMIT ".$start_from.", ".$records_page;
			break;
		case 'postgres':
			$sql .= " OFFSET ".$start_from." LIMIT ".$records_page;
			break;
		case 'sqlserver':
		    $sql .= " OFFSET ".$start_from." ROWS FETCH NEXT ".$records_page." ROWS ONLY";
		    
		    break;
		default:
			echo 'Error';
			exit;
	}
	
	try {
	    if ($mysql_set_sql_big_selects === 1){
            $conn->query ("SET SQL_BIG_SELECTS = 1");
        }
    	$results = $conn->query($sql);
    	//$results->setFetchMode(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during query execution.';
    	if ($debug_mode === 1){
    		//echo ' '.htmlentities($sql).'<br/>The DBMS server said: '.$e->getMessage();
    		echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
    	}

		if ($error_enable_reporting === 1){

			$GLOBALS['latest_query'] = $sql;
			$GLOBALS['latest_query_params'] = array();
			$GLOBALS['latest_query_additional_params'] = array();

			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);

		}

    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('query error', E_USER_ERROR);
        // }
    	exit();
    }
    
	return $results;
}

function fetch_row_db(&$rs, $only_associative = 0)
{
    global $trigger_fatal_error_db_operations, $error_enable_reporting;
	try {
	    if ($only_associative === 1){
		    return $rs->fetch(PDO::FETCH_ASSOC);
		}
		else{
		    return $rs->fetch();
		}
		
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during record fetching.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('fetching error', E_USER_ERROR);
        // }
    	exit();
    }
	
}

function fetch_all_db(&$rs, $only_associative = 0)
{
    global $trigger_fatal_error_db_operations, $error_enable_reporting;
	try {
	    if ($only_associative === 1){
		    return $rs->fetchAll(PDO::FETCH_ASSOC);
		}
		else{
		    return $rs->fetchAll();
		}
		
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during record fetching all.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('fetching all error', E_USER_ERROR);
        // }
    	exit();
    }
	
}


function get_num_rows_db($input, $use_sql=0)
{
	global $conn, $quote, $host, $user, $pass, $db_name;
	
	if ($use_sql === 1){
		$sql = $input;
	}
	else{
		$sql = $input->queryString;
	}
	
	$pos = strpos_custom($sql, ' FROM ');
	$sql = "SELECT COUNT(*) FROM ".substr_custom($sql, $pos+6);
	
	$res = execute_db($sql, $conn);
	$row = fetch_row_db($res);
	
	return (int)($row[0]);
}

function get_last_ID_db($sequence_name = NULL)
{
	global $conn, $dbms_type, $prefix_internal_table, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	try {
		if (isset($sequence_name) && $sequence_name === $prefix_internal_table.'unique_ids_value_unique_id_seq'){   
			return $conn->lastInsertId($sequence_name);
		}
		else{
		    if ($dbms_type === 'postgres'){
		        return false; // to avoid possible errors with postgres, anyway without sequence name we can't get it
		    }
		    else{
			    return $conn->lastInsertId();
			}
		}
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during last ID fetching.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('id fetching error', E_USER_ERROR);
        // }
    	
    	exit();
    }
}

function num_fields_db($fields)
{
    global $trigger_fatal_error_db_operations, $error_enable_reporting;	
	try {
		return $fields->columnCount();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during number field fetching.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('number fields fetching error', E_USER_ERROR);
        // }
    	
    	exit();
    }
}

function get_autoincrement_field($table_name)
// only implemented for mysql at the moment
{
	global $conn, $dbms_type, $quote;
	
	$autoincrement_field = NULL;
	
	switch ($dbms_type){
	
	    case 'mysql';
	        $sql = "show columns from ".$quote.$table_name.$quote." WHERE Extra = 'auto_increment'";
	        	
	        $res = execute_db($sql, $conn);
	        
	        while($row = fetch_row_db($res)){
	        	$autoincrement_field = $row['Field'];
	        }
	        
	        break;
		case 'sqlserver':
	        break;
	    case 'postgres';
	        break;
	    case 'sqlite';
	        break;
	}
	
	return $autoincrement_field;
	
}



function get_unique_field_db($table_name, $directly_from_db = 0, $fallback_to_db = 0, $check_multiple_pk = 0)
// goal: get the name of the first uniqe field in a table
// input: $table_name, $fallback_to_db (0|1) if 1 and $directly_from_db = 0 and the table is not in the dadabik_table_list, go directly to the db
// output: $unique_field_name, the name of the first unique field in the table
{
	global $conn, $dbms_type, $quote, $table_list_name;
	
	switch($directly_from_db){
		case 0:
			$sql = "SELECT pk_field_table FROM ".$quote.$table_list_name.$quote." WHERE name_table = '".$table_name."'";
		
			$res = execute_db($sql, $conn);
			
			if ( ($row = fetch_row_db($res)) === false){
				if ($fallback_to_db === 1){
					return get_unique_field_db($table_name, 1);
				}
			}
			else{
			
				return $row['pk_field_table'];
			}
			
			break;
		case 1:
		
			switch ($dbms_type){
				case 'mysql':
					$sql = "show columns from ".$quote.$table_name.$quote;
					break;
				case 'sqlite':
					$sql = "PRAGMA table_info(".$quote.$table_name.$quote.")";
					break;
				case 'postgres':
					$sql = "SELECT pg_attribute.attname FROM pg_index, pg_class, pg_attribute WHERE pg_class.oid = '".$table_name."'::regclass AND indrelid = pg_class.oid AND pg_attribute.attrelid = pg_class.oid AND  pg_attribute.attnum = any(pg_index.indkey)  and indisprimary=true";
					break;
				case 'sqlserver':
				    $sql = "SELECT a.name field, ISNULL(c.is_primary_key, 0) pk FROM sys.columns a LEFT OUTER JOIN sys.index_columns b ON a.object_id = b.object_id AND a.column_id = b.column_id LEFT OUTER JOIN  sys.indexes c ON b.object_id = c.object_id AND b.index_id = c.index_id WHERE a.object_id = OBJECT_ID('".$table_name."')";
				    break;
				default:
					echo 'Error';
					exit;
			}
			
			$fields_pk = 0;
			foreach (execute_db($sql, $conn) as $row){
				switch ($dbms_type){
					case 'mysql':
						if ($row['Key'] == 'PRI'){
						    if ($check_multiple_pk === 0){
							    return $row['Field'];
							}
							else{
							    $pk_temp = $row['Field'];
							    $fields_pk++;
							}
						}
						break;
					case 'sqlite':
						if ($row['pk'] == 1){
							
						    if ($check_multiple_pk === 0){
                                return $row['name'];
                            }
                            else{
                                $pk_temp = $row['name'];
                                $fields_pk++;
                            }
						}
						break;
					case 'postgres':
						
						if ($check_multiple_pk === 0){
                            return $row['attname'];
                        }
                        else{
                            $pk_temp = $row['attname'];
                            $fields_pk++;
                        }
						
						break;
					case 'sqlserver':
						if ($row['pk'] == '1'){
						    if ($check_multiple_pk === 0){
                                return $row['field'];
                            }
                            else{
                                $pk_temp = $row['field'];
                                $fields_pk++;
                            }
						}
						break;
					default:
						echo 'Error';
						exit;
				}
			}
			
			if ($check_multiple_pk === 1 ){
			    if ($fields_pk === 1){
			        return $pk_temp;
			    }
			    elseif ($fields_pk > 1){
			        return 'error.composite_pk';
			    }
			}
			
			return NULL;
			
			
			break;
	}
	
} // end function get_unique_field_db

function get_total_rows()
{
	// goal: get an approximate number of rows in the db, considering all the tables 

	switch ($dbms_type){
		case 'mysql':
			$sql = "SELECT SUM(TABLE_ROWS) AS total_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=SCHEMA()";
			break;
		case 'sqlite':
			echo 'Error';
			exit;
			break;
		case 'postgres':
			echo 'Error';
			exit;
			break;
		case 'sqlserver':
			echo 'Error';
			exit;
			break;
		default:
			echo 'Error';
			exit;
	}

	$res = execute_db($sql, $conn);

	$row = fetch_row_db($res);

	return $row[0];

}

function get_tables_list($get_additional_info = 0)
// goal: get a list of the tables available in the current database
// input:
// output: $tables_ar, an array containing all the table names
{
	global $conn, $dbms_type, $db_schema;
	$tables_ar = array();
	
	switch ($dbms_type){
		case 'mysql':
			$sql = "SELECT TABLE_NAME as name, TABLE_TYPE as type FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=SCHEMA() order by TABLE_NAME";
			break;
		case 'sqlite':
			$sql = "SELECT name, type FROM sqlite_master WHERE (type='table' or type = 'view') AND name <> 'sqlite_sequence' order by name";
			break;
		case 'postgres':
			$sql = "select table_name as name, 'table' as type, table_schema from information_schema.tables where table_schema not in ('pg_catalog','information_schema') and table_type = 'BASE TABLE' union select table_name as name, 'view' as type, table_schema  from information_schema.views where table_schema not in ( 'pg_catalog','information_schema') order by name";
			
			
			break;
		case 'sqlserver':
			$sql = "SELECT TABLE_NAME  name, table_type  type FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_TYPE = 'BASE TABLE' or TABLE_TYPE = 'VIEW') order by name";
			break;
		default:
			echo 'Error';
			exit;
	}
	$c = 0;
	
	$tables_ar_for_unique_check = array();
	
	foreach (execute_db($sql, $conn) as $row){
		
		if ($dbms_type !== 'postgres' || $row['table_schema'] === $db_schema){
		    
		    // check if the table is already there, it means there are multiple schemas having same tables
		    if ($dbms_type === 'sqlserver' && in_array($row['name'], $tables_ar_for_unique_check)){
		        die('Error: I have found two tables/views having the same name: '.$row['name']);
		    }
		    
		    $tables_ar_for_unique_check[] = $row['name'];
		    
            if ($get_additional_info === 0){
                $tables_ar[] = $row['name'];
            }
            else{
                
                $tables_ar[$c]['name'] = $row['name'];
                if ($row['type'] === 'BASE TABLE' || $row['type'] === 'SYSTEM VERSIONED' && $dbms_type == 'mysql'  ){
				//if ($row['type'] === 'BASE TABLE'){ // for mysql and sqlserver
                    $row_temp = 'table';
                }
                else{
                    $row_temp = strtolower($row['type']);
                }
                $tables_ar[$c]['type'] = $row_temp;
            }
            $c++;
        }
	}
	
	return $tables_ar;
}

function get_fields_list($table_name, $add_additional_info = false, $add_fields_labels_info = false, $return_on_non_existing_table = false, $return_on_error_getting_fields = false)
// goal: get a list of the fields available in a table
// input:
// output: $fields_ar, an array containing all the field names
{
	global $conn, $dbms_type, $quote, $db_name, $prefix_internal_table;
	$fields_ar = array();
	
	if (table_exists($table_name) === false){
		if ($return_on_non_existing_table === false){
	    	die ('Unexpected table name: '.$table_name);
		}
		else{
			return 'non_existing_table';
		}
	}

	$return_control_on_error = 0;
	if ($return_on_error_getting_fields === true){
		$return_control_on_error = 1;
	}
	
	switch ($dbms_type){
		case 'mysql':
			$sql = "show columns from ".$quote.$table_name.$quote;
			$res = execute_db($sql, $conn, $return_control_on_error);
			if (is_array($res) && isset($res['error_message'])) {
				return 'error_on_getting_fields';
			}
			else{
				foreach ($res as $row){
					$fields_ar['name'][] = $row['Field'];
					$fields_ar['type'][] = $row['Type'];
					if ($row['Null'] == 'YES'){
						$fields_ar['null'][] = 1; 
					}
					else{
						$fields_ar['null'][] = 0; 
					}
					$fields_ar['extra'][] = $row['Extra'];
				}
			}
			
			$sql = "SELECT column_name, referenced_table_name, referenced_column_name FROM information_schema.KEY_COLUMN_USAGE WHERE table_schema = '".$db_name."' and table_name = '".$table_name."' and referenced_table_schema = '".$db_name."' and referenced_column_name IS NOT NULL";
			$fks=array();
			foreach (execute_db($sql, $conn) as $row){
			    // some MySQL versions require capital case
			    if (isset($row['REFERENCED_TABLE_NAME']) && isset($row['REFERENCED_COLUMN_NAME']) && isset($row['COLUMN_NAME']) ){
				
                    $fks[$row['COLUMN_NAME']]['referenced_table_name'] = $row['REFERENCED_TABLE_NAME'];
                    $fks[$row['COLUMN_NAME']]['referenced_field_name'] = $row['REFERENCED_COLUMN_NAME'];
                }
                else{
                    
				    $fks[$row['column_name']]['referenced_table_name'] = $row['referenced_table_name'];
				    $fks[$row['column_name']]['referenced_field_name'] = $row['referenced_column_name'];
				}
			}
			foreach($fields_ar['name'] as $key => $value){
				if (isset($fks[$value])){
					$fields_ar['referenced_table_name'][$key] = $fks[$value]['referenced_table_name'];
					$fields_ar['referenced_field_name'][$key] = $fks[$value]['referenced_field_name'];
				}
			}
			
			break;
		case 'sqlite':
			$sql = "PRAGMA table_info(".$quote.$table_name.$quote.")";

			$res = execute_db($sql, $conn, $return_control_on_error);
			if (is_array($res) && isset($res['error_message'])) {
				return 'error_on_getting_fields';
			}
			else{
				foreach ($res as $row){
					$fields_ar['name'][] = $row[1];
					$fields_ar['type'][] = $row[2];
					if ($row[3] == '1'){ // sqlite has a notnull 0|1 property
						$fields_ar['null'][] = 0; 
					}
					else{
						$fields_ar['null'][] = 1; 
					}
					
					$fields_ar['extra'][] = '';
				}
			}
			$sql = "PRAGMA foreign_key_list(".$quote.$table_name.$quote.")";
			$fks=array();
			foreach (execute_db($sql, $conn) as $row){
				$fks[$row['from']]['referenced_table_name'] = $row['table'];
				$fks[$row['from']]['referenced_field_name'] = $row['to'];
			}
			foreach($fields_ar['name'] as $key => $value){
				if (isset($fks[$value])){
					$fields_ar['referenced_table_name'][$key] = $fks[$value]['referenced_table_name'];
					$fields_ar['referenced_field_name'][$key] = $fks[$value]['referenced_field_name'];
				}
			}
			
			break;
		case 'postgres':
			/*$sql = "SELECT column_name, data_type, is_nullable, character_maximum_length FROM information_schema.columns WHERE table_name ='".$table_name."' order by ordinal_position";
			foreach (execute_db($sql, $conn) as $row){
				$fields_ar['name'][] = $row['column_name'];
				$fields_ar['type'][] = $row['data_type'];
				
				
				if ($row['is_nullable'] == 'YES'){
					$fields_ar['null'][] = 1; 
				}
				else{
					$fields_ar['null'][] = 0; 
				}
				$fields_ar['max_length'][] = $row['character_maximum_length'];
				$fields_ar['extra'][] = '';
			}
			*/
			
			$sql = "SELECT pg_attribute.attname, format_type(pg_attribute.atttypid, pg_attribute.atttypmod) as data_type, attnotnull, CASE WHEN pg_attribute.atttypid = ANY ('{int,int8,int2}'::regtype[]) AND EXISTS ( SELECT 1 FROM pg_attrdef WHERE  pg_attrdef.adrelid = pg_attribute.attrelid AND pg_attrdef.adnum   = pg_attribute.attnum
             AND    pg_get_expr(adbin, adrelid) = 'nextval(''' || (pg_get_serial_sequence (pg_attribute.attrelid::regclass::text , pg_attribute.attname))::regclass  || '''::regclass)')
             THEN CASE pg_attribute.atttypid
                WHEN 'int'::regtype  THEN 'serial'
                WHEN 'int8'::regtype THEN 'bigserial'
                WHEN 'int2'::regtype THEN 'smallserial'
             END
            END AS extra
        FROM   pg_attribute
        WHERE  pg_attribute.attrelid = '".$table_name."'::regclass
        AND    pg_attribute.attnum > 0
        AND    NOT pg_attribute.attisdropped
        ORDER  BY pg_attribute.attnum";

			$res = execute_db($sql, $conn, $return_control_on_error);
			if (is_array($res) && isset($res['error_message'])) {
				return 'error_on_getting_fields';
			}
			else{
				foreach ($res as $row){
					$fields_ar['name'][] = $row['attname'];
					$fields_ar['type'][] = $row['data_type'];
					
					if ($row['attnotnull'] === FALSE){
						$fields_ar['null'][] = 1; 
					}
					else{
						$fields_ar['null'][] = 0; 
					}
					//$fields_ar['max_length'][] = $row['character_maximum_length'];
					
					if ($row['extra'] !== NULL){
						$fields_ar['extra'][] = $row['extra'];
					}
					else{
						$fields_ar['extra'][] = '';
					}
				}
			}
			
			break;
		case 'sqlserver':
			$sql = "SELECT a.name 'column_name', b.Name 'data_type', a.max_length 'character_maximum_length', a.precision 'precision', a.scale 'scale', a.precision 'precision', a.is_nullable, a.is_identity FROM sys.columns a INNER JOIN sys.types b ON a.user_type_id = b.user_type_id LEFT OUTER JOIN sys.index_columns c ON c.object_id = a.object_id AND c.column_id = a.column_id LEFT OUTER JOIN sys.indexes d ON c.object_id = d.object_id AND c.index_id = d.index_id WHERE a.object_id = OBJECT_ID('".$table_name."') ";

			$res = execute_db($sql, $conn, $return_control_on_error);
			if (is_array($res) && isset($res['error_message'])) {
				return 'error_on_getting_fields';
			}
			else{
				foreach ($res as $row){
			
					$fields_ar['name'][] = $row['column_name'];
					$fields_ar['type'][] = $row['data_type'];
					
					
					if ($row['is_nullable'] == '1'){
						$fields_ar['null'][] = 1; 
					}
					else{
						$fields_ar['null'][] = 0; 
					}
					
					if ($row['data_type'] === 'char' || $row['data_type'] === 'varchar'){
						$fields_ar['max_length'][] = $row['character_maximum_length'];
					}
					elseif (  $row['data_type'] === 'nchar' || $row['data_type'] === 'nvarchar'){
						$fields_ar['max_length'][] = $row['character_maximum_length']/2;
					}
					else{
						$fields_ar['max_length'][] ='';
					
					}
					if ($row['is_identity'] == '1'){
						$fields_ar['extra'][] = 'identity';
					}
					else{
						$fields_ar['extra'][] = '';
					}
					if (  $row['data_type'] === 'decimal'){
						$fields_ar['precision'][] = $row['precision'];
						$fields_ar['scale'][] = $row['scale'];
					}
					else{
						$fields_ar['precision'][] = '';
						$fields_ar['scale'][] = '';
					}
				}
			}
			break;
		default:
			echo 'Error';
			exit;
	}
	
	// at the moment, just the linked table, if any
	if ($add_fields_labels_info === true){
	    
	    $fields_labels_ar = build_fields_labels_array($prefix_internal_table.$table_name, "1", 1, 0, 0);
	    
	    // re-build the array using the field name as key
	    foreach ($fields_labels_ar as $key => $value){
	        $fields_labels_ar_2[$value['name_field']] = $value;
	    }
	    
	    foreach ($fields_ar['name'] as $key => $value){
	        $fields_ar['linked_table'][$key] = NULL;
	        
	        if (isset($fields_labels_ar_2[$value])){ // if we have the field in the fields labels ar (could be not set because of lack of synch)
	            
	            // if lookup
	            if ( ($fields_labels_ar_2[$value]["type_field"] === 'select_single' || $fields_labels_ar_2[$value]["type_field"] === 'select_single_radio' || $fields_labels_ar_2[$value]["type_field"] === 'select_multiple_menu'  || $fields_labels_ar_2[$value]["type_field"] === 'select_multiple_checkbox') && $fields_labels_ar_2[$value]['primary_key_field_field'] != "" && $fields_labels_ar_2[$value]['primary_key_field_field'] != NULL){
	            
	                $fields_ar['linked_table'][$key] = $fields_labels_ar_2[$value]['primary_key_table_field'];
	                
	            }
	        }
	    }
	    
	}
	
	if ($add_additional_info === true){
	
		return $fields_ar;
	}
	else{
		return $fields_ar['name'];
	}
}

function escape($string)
{
	global $conn, $dbms_type;
	
	if ($string === NULL){
	    return NULL;
	}
	else{
	
        switch ($dbms_type){
            case 'mysql':
                return addslashes($string);
                break;
            case 'postgres':
                return str_replace("'", "''", $string);
                break;
            case 'sqlserver':
            case 'sqlite':
                return str_replace("'", "''", $string);
                break;
            default:
                echo 'Error';
                exit;
        }
    }
}

function unescape($string, $for_magic = 0)
{
	global $conn, $dbms_type;
	
    if (is_null($string)){
        return $string;
    }
	
	$unesape_pgsql_sqlite_from = '\\\'';
	$unesape_pgsql_sqlite_to = '\'';
	
	if (  ini_get('magic_quotes_sybase') == '1' || $for_magic == 0){
		$unesape_pgsql_sqlite_from = "''";
		$unesape_pgsql_sqlite_to = "'";
	}
	
	switch ($dbms_type){
		case 'mysql':
			if (is_array($string)){ // for select_multiple_*
				$array_new = array();
				foreach($string as $value){
					$array_new[] = stripslashes($value);
				}
				return $array_new;
			}
			return stripslashes($string); 
			break;
		case 'postgres':
			if (is_array($string)){ // for select_multiple_*
				$array_new = array();
				foreach($string as $value){
					$array_new[] = str_replace($unesape_pgsql_sqlite_from, $unesape_pgsql_sqlite_to, $value);
				}
				return $array_new;
			}
			return str_replace($unesape_pgsql_sqlite_from, $unesape_pgsql_sqlite_to, $string);
			break;
		case 'sqlserver':
		case 'sqlite':
			if (is_array($string)){ // for select_multiple_*
				$array_new = array();
				foreach($string as $value){
					$array_new[] = str_replace($unesape_pgsql_sqlite_from, $unesape_pgsql_sqlite_to, $value);
				}
				return $array_new;
			}
			return str_replace($unesape_pgsql_sqlite_from, $unesape_pgsql_sqlite_to, $string);
			break;
		default:
			echo 'Error';
			exit;
	}
}

function begin_trans_db()
{
	global $conn, $debug_mode, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	try {
		$conn->beginTransaction();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during transaction start.';
    	
    	if ($debug_mode === 1){
            echo '<br/>The DBMS server said: '.$e->getMessage();
        }
        else{
            //echo ', set $debug_mode to 1 in your config.php to get further error information ';
        }

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    		
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('start transaction error', E_USER_ERROR);
        // }
    	
    	exit();
    }
}

function complete_trans_db()
{
	global $conn, $trigger_fatal_error_db_operations, $error_enable_reporting;
	
	try {
		$conn->commit();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during transaction commit.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	
    	// if ($trigger_fatal_error_db_operations === 1){
        //     trigger_error('commit error'.($e->getMessage()), E_USER_ERROR);
        // }
    	
    	exit();
    }
}



function rollback_trans_db()
{
	global $conn, $error_enable_reporting;
	
	try {
		$conn->rollBack();
    }
    catch(PDOException $e){
    	echo '<p><b>[08] Error:</b> during transaction rollback.';

		if ($error_enable_reporting === 1){
					
			dadabik_error_handler(
				E_USER_ERROR,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine()
			);
		}
    	
    	exit();
    }
}


function escape_on_like($string, $prepared = 0)
{
    global $conn, $dbms_type;
	
	switch ($dbms_type){
		case 'mysql':
		case 'postgres':
		case 'sqlite':
		    // "\\", "\\\\" of course means replace \ with \\, it's \\ and \\\\ because I need to escape the \ itself
		    if ($prepared === 1){
			    return (str_replace("%", "\%", str_replace("_", "\_", str_replace("\\", "\\\\", unescape($string)))));
			}
			else{
			    return escape(str_replace("%", "\%", str_replace("_", "\_", str_replace("\\", "\\\\", unescape($string)))));
			}
			break;
			
		case 'sqlserver':
		    if ($prepared === 1){
			    return (str_replace("[", "\[", str_replace("]", "\]", str_replace("%", "\%", str_replace("_", "\_", str_replace("\\", "\\\\", unescape($string)))))));
			}
			else{
			    return escape(str_replace("[", "\[", str_replace("]", "\]", str_replace("%", "\%", str_replace("_", "\_", str_replace("\\", "\\\\", unescape($string)))))));
			}
			
			break;
			
		default:
			echo 'Error';
			exit;
	}
}

// giving in input a table name (or a table + field name), get the related fks
function get_related_fks_db($table_name, $field_name = NULL){

	global $conn, $dbms_type, $db_name;

	$fks_ar = [];
	$count_fks = 0;
	switch($dbms_type){
		case 'mysql':
			if (!is_null($field_name)){
				$sql = "SELECT TABLE_NAME, CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = :db_name AND ( (TABLE_NAME = :table_name AND COLUMN_NAME = :field_name) OR (REFERENCED_TABLE_NAME = :table_name AND REFERENCED_COLUMN_NAME = :field_name))";
									
				$res_prepare = prepare_db($conn, $sql);
				$res_bind = bind_param_db($res_prepare, ':db_name', $db_name);
				$res_bind = bind_param_db($res_prepare, ':table_name', $table_name);
				$res_bind = bind_param_db($res_prepare, ':field_name', $field_name);

				$res = execute_prepared_db($res_prepare,0);
				
			}
			else{
				$sql = "SELECT TABLE_NAME, CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = :db_name AND REFERENCED_TABLE_NAME = :table_name";
									
				$res_prepare = prepare_db($conn, $sql);
				$res_bind = bind_param_db($res_prepare, ':db_name', $db_name);
				$res_bind = bind_param_db($res_prepare, ':table_name', $table_name);

				$res = execute_prepared_db($res_prepare,0);

			}

			while($row = fetch_row_db($res_prepare)){

				$fks_ar[$count_fks]['table_name'] = $row['TABLE_NAME'];
				$fks_ar[$count_fks]['fk_name'] = $row['CONSTRAINT_NAME'];
				$count_fks++;
				
			}
			break;

		default:
			trigger_error('get_related_fks_db not supported for this DBMS', E_USER_ERROR);
			break;
	}

	return $fks_ar;

	
	
}

// CUSTOM CODE API
// **********************************************************************
// this is here just for backward compatibility
function get_record_details($table_name, $id_field, $id_value)
{
    return ddb_api::get_record_details($table_name, $id_field, $id_value);
}


?>