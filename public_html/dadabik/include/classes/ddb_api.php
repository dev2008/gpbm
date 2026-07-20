<?php
#[\AllowDynamicProperties]
class ddb_api {
    
    // just an alias to update_records to guarantee backward compatibility, the first version of the method was called update_record instead of update_records then the name was changed
    static public function update_record($table_name, $field_name, $field_value, $fields_to_update_ar, $fields_to_update_values_ar)
    {
        return static::update_records($table_name, $field_name, $field_value, $fields_to_update_ar, $fields_to_update_values_ar);
    }
  
    // Update the records of the table $table_name matching an $field_name / $field_value, changing the value of fields defined in $fields_to_update_ar to $fields_to_update_values_ar
    // Example: ddb_api::update_records('customers', 'id_customer', 10, ['address_customer', 'city_customer'], ['2299 Piedmont Ave.', 'Berkeley'])
    static public function update_records($table_name, $field_name, $field_value, $fields_to_update_ar, $fields_to_update_values_ar)
    {
        global $conn, $quote, $current_user;

        $table_infos_ar = get_installed_table_infos($table_name);
        $enable_revision_table = $table_infos_ar['enable_revision_table'];
    
        $in_transaction = $conn->inTransaction();
    
        if ($in_transaction === false){
            begin_trans_db();
        }
    
        $sql = "UPDATE ".$quote.$table_name.$quote." SET ";
    
        $values_to_bind = array();
        foreach ($fields_to_update_ar as $key => $value){
            $values_to_bind[$value] = $fields_to_update_values_ar[$key];
            $sql .= $quote.$value.$quote." = :".$value.", ";
        }
    
        $sql = substr($sql, 0, -2);
    
        if (count($values_to_bind) === 0){
            die('update_record: wrong parameters, no fields to update');
        }
    
        $sql .= " WHERE ".$quote.$field_name.$quote." = :id_value";
        $res_prepare = prepare_db($conn, $sql);
    
        $bind_values = '';
        foreach ($values_to_bind as $key => $value){
            $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
            $bind_values .= ':'.$key.'->'.$value.'//';
        }
        $res_bind = bind_param_db($res_prepare, ':id_value', $field_value);
        $bind_values .= ':id_value->'.$field_value.'//';
    
        $res = execute_prepared_db($res_prepare,0);
        
        log_operation('update', $sql.$bind_values);
    
        if ($enable_revision_table == 1){
            register_revision($table_name, $field_name, $field_value, $current_user, 'update');
        }

    
        if ($in_transaction === false){
            complete_trans_db();
        }
    }
    
    // Insert a record in the table $table_name, setting the value of the fields defined in $fields_to_insert_ar to $fields_to_insert_values_ar
    // Example: ddb_api::insert_record('products', ['name', 'price'], ['Korg microKorg', '399']);
    static public function insert_record($table_name, $fields_to_insert_ar, $fields_to_insert_values_ar)
    {
        global $conn, $quote, $current_user, $dbms_type;

        $table_infos_ar = get_installed_table_infos($table_name);
        $enable_revision_table = $table_infos_ar['enable_revision_table'];
    
        $in_transaction = $conn->inTransaction();
    
        if ($in_transaction === false){
            begin_trans_db();
        }
    
        $sql = "INSERT INTO ".$quote.$table_name.$quote." (";
    
        foreach ($fields_to_insert_ar as $key => $value){
            $sql .= $quote.$value.$quote.", ";
        }
    
        $sql = substr($sql, 0, -2).") VALUES (";
        
        $values_to_bind = array();
        foreach ($fields_to_insert_ar as $key => $value){
            $values_to_bind[$value] = $fields_to_insert_values_ar[$key];
            $sql .= ":".$value.", ";
        }
        
        $sql = substr($sql, 0, -2).")";
        
        // SAME CODE insert_other, insert_record
        if ($dbms_type === 'postgres'){
            $unique_field = get_unique_field_db($table_name);
            if ($unique_field !== NULL){
                $sql .= " RETURNING ".$unique_field;
            }
        }
    
        if (count($values_to_bind) === 0){
            die('insert_record: wrong parameters, no fields specified');
        }
    
        $res_prepare = prepare_db($conn, $sql);
    
        $bind_values = '';
        foreach ($values_to_bind as $key => $value){
            $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
            $bind_values .= ':'.$key.'->'.$value.'//';
        }
        
        $res = execute_prepared_db($res_prepare,0);
        
        
        // SAME CODE insert_other, insert_record
        if ($dbms_type === 'postgres'){
            $last_inserted_ID = false;
            if ($unique_field !== NULL){
                $row = fetch_row_db($res_prepare);
                $last_inserted_ID = $row[0];
            }
        }
        else{
            $last_inserted_ID = get_last_ID_db();
        }
   
        log_operation('insert', $sql.$bind_values);
    
        if ($enable_revision_table == 1){
            register_revision($table_name, '', $last_inserted_ID, $current_user, 'insert');
        }
    
        if ($in_transaction === false){
            complete_trans_db();
        }

        return $last_inserted_ID;
    }
    
    
    // Delete (one or more) records from the table $table_name matching a $field_name / $field_value pair
    // Example: ddb_api::delete_records('customers', 'id_customer', 10)
    static public function delete_records($table_name, $field_name, $field_value)
    {
        global $conn, $quote, $current_user, $delete_files_when_delete_record, $prefix_internal_table, $upload_directory;

        $table_infos_ar = get_installed_table_infos($table_name);
        $enable_revision_table = $table_infos_ar['enable_revision_table'];
    
        $in_transaction = $conn->inTransaction();
    
        if ($in_transaction === false){
        
            begin_trans_db();
        }
        
        // select the files to be deleted and fill $files_to_delete_ar, I obviously need to do this before record deletion 
        if ($delete_files_when_delete_record === 1){
	
            $fields_containing_files_ar = array(); // the names of the fields which contain files, if any
            $files_to_delete_ar = array(); // here the name of the uploaded files to delete, if any
            $fields_labels_ar = build_fields_labels_array($prefix_internal_table.$table_name, '1');
    
            foreach ($fields_labels_ar as $fields_labels_ar_element){
                if ($fields_labels_ar_element['type_field'] === 'generic_file' || $fields_labels_ar_element['type_field'] === 'image_file' || $fields_labels_ar_element['type_field'] === 'camera'){ // check if the field is a file field
                    $fields_containing_files_ar[] = $fields_labels_ar_element['name_field'];
                } // end if
            } // end foreach
    
            $fields_count = count($fields_containing_files_ar);
        
            if ($fields_count > 0){
                $sql = "SELECT ";
                foreach ($fields_containing_files_ar as $fields_containing_files_ar_element){
                    $sql .= $fields_containing_files_ar_element.", ";
                } // end foreach
                $sql = substr_custom($sql, 0, -2);
                $sql .= " FROM ".$quote.$table_name.$quote." WHERE ".$quote.$field_name.$quote." = :field_value";
                
                $res_prepare = prepare_db($conn, $sql);
                $res_bind = bind_param_db($res_prepare, ':field_value', $field_value);
        
                // execute the query
                $res = execute_prepared_db($res_prepare,0);
        
                while ($row = fetch_row_db($res_prepare)){;
        
                    for ($i=0; $i<$fields_count; $i++){
                        $files_to_delete_ar[] = $row[$i];
                    } // end for
                
                } // end while
            } // end if
            
        } // end if
        
        $sql = "DELETE FROM ".$quote.$table_name.$quote;
    
        $sql .= " WHERE ".$quote.$field_name.$quote." = :field_value";
        $res_prepare = prepare_db($conn, $sql);
    
        $bind_values = '';
        $res_bind = bind_param_db($res_prepare, ':field_value', $field_value);
        $bind_values .= ':field_value->'.$field_value.'//';

        if ($enable_revision_table == 1){
            register_revision($table_name, $field_name, $field_value, $current_user, 'delete');
        }

        $res = execute_prepared_db($res_prepare,0);

        log_operation('delete', $sql.$bind_values);
        
        // delete the related files
        if ($delete_files_when_delete_record === 1){
            
            foreach($files_to_delete_ar as $files_to_delete_ar_element){				
                if (is_file($upload_directory.$files_to_delete_ar_element)){ // for security
                    unlink_custom($upload_directory.$files_to_delete_ar_element); // delete the file
                } // end if
            } // end foreach
            
        } // end if
        
        if ($in_transaction === false){
            complete_trans_db();
        }
    }

    // Returns the number of records matching a specified field name / field value pair
    // Example: ddb_api::count_records('customers','city_customer','Bologna')
    static public function count_records($table_name, $field_name, $field_value)
    {
        global $conn, $quote, $api_method_count_records_return_string;
    
        $sql = "SELECT COUNT(*) FROM ".$quote.$table_name.$quote." WHERE ".$quote.$field_name.$quote." = :field_value";
    
        $res_prepare = prepare_db($conn, $sql);
    
        $res_bind = bind_param_db($res_prepare, ':field_value', $field_value);
    
        $res = execute_prepared_db($res_prepare);
    
        $row = fetch_row_db($res_prepare);

        if ($api_method_count_records_return_string === 1){
            return $row[0];
        }
        else{
            return (int)$row[0];
        }
    
        
    }
    
    // Returns the details of the (unique) record matching an id_field / id_value pair 
    // Example: ddb_api::get_record_details('customers','id','10')
    public static function get_record_details($table_name, $id_field, $id_value, $return_false_if_no_results = FALSE)
    {
        global $conn, $quote;
    
        $sql = "SELECT * FROM ".$quote.$table_name.$quote." WHERE ".$quote.$id_field.$quote." = :id_value";
        $res_prepare = prepare_db($conn, $sql);
    
        $res_bind = bind_param_db($res_prepare, ':id_value', $id_value);
    
        $res = execute_prepared_db($res_prepare,0);
    
        $num_rows = 0;
        while($row = fetch_row_db($res_prepare,1)){
            $record_details = $row;
            $num_rows++;
        }

        if ($num_rows === 0){
            if ($return_false_if_no_results === TRUE){
                return false;
            }
        }
    
        if ($num_rows !== 1){
            echo 'Unexpected error, more than one records having same ID'.$table_name.$id_field.$id_value;
            exit;
        }
    
        return $record_details;
    }
    
    
    /*****
    
    Load a page (based on a table/view), optionally displaying a message. $id_value is used to identify a specific record, if needed. 
    
    Parameters:
     
    $table_name: name of the table or view
    $page_type: 'results_grid'|'edit'|'details'|'insert'|'advanced_search'
    $id_value: for edit and details pages, the value of the unique field of the record you want to select
    $message_text: the messasge to display, if any. A corresponding $txt key must be available in the language file. The key can't contain spaces or quotes
    $message_type: 'success'|'warning'|'error'
    
    Example 1: load the results grid of the table customers, showing a success message
    ddb_api::load_table_page('customers', 'results_grid', NULL, 'operation_done', 'success');
    (In my language file, e.g. english_custom.php, I added something like $txt['operation_done'] = 'Operation executed!';)
    
    Example 2: load the edit form of the record having id_customer 10 in the customers table, showing a warning message
    ddb_api::load_table_page('customers', 'edit', '10', 'check_this_and_that', 'warning');
    
    Example 3: ddb_api::load_table_page('products', 'details', '5');
    
    Example 4: ddb_api::load_table_page('products', 'insert');
    
    ******/
    public static function load_table_page($table_name, $page_type, $id_value=NULL, $message_text=NULL, $message_type=NULL)
    {
        global $site_url, $dadabik_main_file;
        
        $where_field_value_string = '';
        if (in_array($page_type, array('edit','details'))){
            $where_field = get_unique_field_db($table_name);
            
            $where_field_value_string = '&where_field='.urlencode($where_field).'&where_value='.urlencode($id_value);
        }
        
        $message_text_type_string = '';
        if ($message_text !== NULL && $message_type !== NULL){
            $message_text_type_string = '&top_message_text='.urlencode($message_text).'&top_message_type='.urlencode($message_type);
        }
        
        
        switch($page_type){
            case 'results_grid':
                $function = 'search';
                break;
            case 'edit':
                $function = 'edit';
                break;
            case 'insert':
                $function = 'show_insert_form';
                break;
            case 'details':
                $function = 'details';
                break;
            case 'advanced_search':
                $function = 'show_search_form';
                break;
            default:
                die('load_table_page: unknown page_type');
                break;
        }
        
        $location_value = $site_url.$dadabik_main_file.'?function='.$function.'&tablename='.urlencode($table_name).$where_field_value_string.$message_text_type_string;
        
        header('location:'.$location_value);
        
        exit;
    }
    
    /*****
    
    Load a page (custom PHP or HTML page)
    
    Parameters:
     
    $id: the ID of the page to load 
    $additional_input: optional string containing any value you may need to pass via GET to the page
    
    Example 1: load the page having ID 19
    ddb_api::load_custom_page(19);
    
    Example 2: load the page having ID 22, passing to the page the string "from_confirmation_button"
    ddb_api::load_custom_page(22, 'from_confirmation_button');
    
    ******/
    public static function load_custom_page($id, $additional_input = NULL)
    {
        global $site_url, $dadabik_main_file;
        
        $additional_input_string = '';
        if ($additional_input !== NULL ){
            $additional_input_string = '&additional_input='.urlencode($additional_input);
        }
        
        $location_value = $site_url.$dadabik_main_file.'?function=show_static_page&id_static_page='.urlencode($id).$additional_input_string;
        
        header('location:'.$location_value);
        
        exit;
    }
    
    
    
    
}