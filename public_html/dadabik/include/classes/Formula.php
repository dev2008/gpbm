<?php

// Custom exception for tokenization errors
class TokenizationException extends \Exception {
}


/**
 * Class Formula
 * 
 * This class provides functionality to parse and convert DaDaBIK formulas into SQL and execute it
 * The main methods are:
 * - tokenize_formula: to break down the formula into individual tokens.
 * - convert_tokens_to_sql: to convert the tokens into an SQL prepared statement.
 * - execute_sql_formula: to execute the SQL prepared statement and return the calculated value
 * - execute_field_formula: a wrapper that encapsulates the entire flow 
 * 
 * The class supports common mathematical operators and can process functions 
 * like SUM, AVG, and placeholders for column references.
 * 
 * Example usage:
 * $formula = new Formula('{price} * 1.22'');
 * $calculated_value = $formula -> execute_field_formula($form_values, $current_function, $table_name, $where_field, $where_value);

 */

class Formula
{

private $formula_value;

public function __construct(string $formula_value)
{
    $this->formula_value = $formula_value;
}

/**
 * Tokenizes a given formula string into recognizable components (tokens).
 * 
 * This method parses the formula and breaks it down into operators, 
 * functions, numeric values, and placeholders for table/field references.
 * 
 * @return array<int, array<string, string>> An array of tokens where each token is represented by an associative array.
 */


public function tokenize_formula(): array
{
    $tokens = [];
    $formula_value_cleaned = str_replace(' ', '', $this->formula_value);

	// simple placeholders contain field names available in the current table, e.g. in a customers table, {first_name_customer} is a simple placeholder, linked placeholders contain field names of a linked table e.g. in a customers table {cities.name_city} is a linked placeholder, fields can be linked via lookup tables or via master/detail 

    $patterns = [
        'SQL_AGGREGATE_FUNCTION'    => '/^(SUM|AVG|COUNT|MIN|MAX)\b/i',
        'DELIMITERS' => '/^[(),]/',
        'PLACEHOLDER_LINKED' => '/^\{[a-zA-Z_\d]+\.[a-zA-Z_\d]+\}/',
        'PLACEHOLDER_SIMPLE' => '/^\{[a-zA-Z_\d]+\}/',
        'MATH_OPERATOR'    => '/^[+\-*\/]/',
        'SQL_FUNCTION'    => '/^(ROUND|DAYS_DIFF|YEAR|MONTH|DAY)\b/i',
        'NUMBER'      => '/^\d+(\.\d+)?/'
    ];

    $has_aggregate_function = 0;

    while (strlen($formula_value_cleaned) > 0) {
        $matched = false;

        foreach ($patterns as $type => $pattern) {

            if (preg_match($pattern, $formula_value_cleaned, $matches)) {
                $match = $matches[0];

                if ($type === 'SQL_AGGREGATE_FUNCTION'){

                    if ($has_aggregate_function === 1){ // we had one already

                        throw new \TokenizationException('Invalid token, more than one aggragate function in formula: ' . $this->formula_value);
                    }

                    $has_aggregate_function = 1;
                }

				// Create token
				$tokens[] = [
					'type' => $type,
					'value' => $match
				];

				// Remove matched portion from formula
				$formula_value_cleaned = substr($formula_value_cleaned, strlen($match));

				$matched = true;

				break;
            }
        }

        if (!$matched) {
            throw new \TokenizationException('Invalid token in formula: ' . $this->formula_value);
        }
    }

    return $tokens;
}

/**
 * Converts an array of tokens into a SQL query string.
 * 
 * This method processes the tokens generated from tokenizeFormula and 
 * converts them into a valid SQL expression, that, if executed, returns the calculated value
 * 
 * @param array<int, array<string, string>> $tokens The tokens generated from the formula.
 * @param array<string, string> $form_values associative array containing the current values of the form fields
 * @param string $current_function the current DaDaBIK function (show_insert_form|insert|edit|update)
 * @param string $table_name The current table
 * @param string $where_field the unique field of the current table
 * @param string $where_value the unique field value of the current record
 * @return array{
 *     sql: string,
 *     has_aggregate_function: int,
 *     has_empty_placeholder_values: int,
 *     bind_value: array
 * }
 *  The function return an associative array containing the actual SQL statement ("sql" key) and three additional info: has_aggregate_function (0|1), has_empty_placeholder_values (0|1), and an array of bind values 
 */

private function convert_tokens_to_sql(array $tokens, array $form_values, string $current_function, string $table_name, ?string $where_field, ?string $where_value) : array
{
	global $quote, $prefix_internal_table, $dbms_type;
	
	$output[] = 'SELECT ';
    $bind_values_count = 0;
    $bind_values = array();
	$has_aggregate_function = 0; // flag to know if it's an aggregate function
	$has_empty_placeholder_values = 0; // flag to know one or more placeholder are empty (we don't execute the query)

    $fields_labels_ar = build_fields_labels_array($prefix_internal_table.$table_name, "1");

	foreach ($tokens as $token) {
		
        switch ($token['type']) {
            case 'SQL_AGGREGATE_FUNCTION':
				$output[] = $token['value'];
				$has_aggregate_function = 1;
                break;
            case 'DELIMITERS':
				$output[] = $token['value'];
                break;
            case 'PLACEHOLDER_LINKED':
				// remove {} to get the table and field name
				$token_value_trimmed = trim($token['value'], '{}');
				$linked_table_name = strstr($token_value_trimmed, '.', true);
				$linked_field_name = ltrim(strstr($token_value_trimmed, '.'), '.');

				// if the linked table is not in master/details relationship with the main table, i.e. it should be a lookup table 
                if (!is_master_table($table_name, $linked_table_name)){

					$lookup_field_found = 0;

					// check, in the main table, the lookup field
                    foreach($fields_labels_ar as $value){
                        if ($value['primary_key_table_field'] === $linked_table_name ){
                            $primary_key_linked_table = $value['primary_key_field_field'];
                            $main_table_lookup_field = $value['name_field'];
							$lookup_field_found = 1;
                            break;
                        }
                    }

					if ($lookup_field_found === 0){
						throw new Exception('Invalid linked table in formula: ' . $linked_table_name);
					}

					// if the lookup field is empty, repalce the placeholder with ''
                    if($form_values[$main_table_lookup_field] === ''){
                        $output[] = "''";
                        $has_empty_placeholder_values = 1;
                    }
					// otherwise replace the placeholder with the linked field value
					else{
						
						// get the field values of the linked record
						$linked_record_infos = ddb_api::get_record_details($linked_table_name, $primary_key_linked_table, $form_values[$main_table_lookup_field]);

						$calculated_value = $linked_record_infos[$linked_field_name];

						if ($calculated_value === '' || $calculated_value === NULL){
							$output[] = "''"; 
                            $has_empty_placeholder_values = 1;
						}
						else{
                            $output[] = ' :'.$bind_values_count.' ';

                            $bind_values[$bind_values_count] = $calculated_value;
                            $bind_values_count++;
                            
						}
                    }
                }
				// otherwise, if it is in master/detail relationship, just replace the placeholder with the field name (e.g. from {cities.name_city} to cities.name_city), it will be used in the query. Also keep track of the $linked_table_name_aggragate, needed later 
                else{
                    $output[] = trim($token['value'], '{}');
                    $linked_table_name_aggragate = $linked_table_name;
                }
                break;
			case 'PLACEHOLDER_SIMPLE':
				// remove {} to get the field name
				$name_field = trim($token['value'], '{}');

				// if the value of the referred field is empty, replace the placeholder with ''
				if ($form_values[$name_field] === ''){
					$output[] = "''";
                    $has_empty_placeholder_values = 1;
				}
				// otherwise replace it with the actual value
				else{
                    $output[] = ' :'.$bind_values_count.' ';
                    $bind_values[$bind_values_count] = $form_values[$name_field];
                    $bind_values_count++;
				}
				break;
			case 'MATH_OPERATOR':
            case 'NUMBER':
                $output[] = $token['value'];
                break;
            case 'SQL_FUNCTION':
                if (strtoupper($token['value']) === 'DAYS_DIFF'){
                    switch ($dbms_type){
                        case 'mysql';
                            $output[] = 'DATEDIFF';
                            break;
                        default:
                            throw new Exception('DAYS_DIFF is only supported on MySQL');
                            break;
                    }
                }
                elseif (in_array(strtoupper($token['value']), ['DAY','MONTH','YEAR'])){
                    switch ($dbms_type){
                        case 'mysql';
                        case 'sqlserver';
                            $output[] = $token['value'];
                            break;
                        default:
                            throw new Exception('DAY/MONTH/YEAR is only supported on MySQL and MS SQL Server');
                            break;
                    }
                }
                else{
                    $output[] = $token['value'];
                }
                break;
        }
    }
	
	// now $output contains an SQL query where the placeholders have been replaced with the corresponding value e.g. SELECT (12+15)/2. However, if there is an aggregate function, the query is more complex and needs FROM and INNER JOIN. This, latter, kind of query is created when a master table field needs to be updated after an items table insert|update|delete operation (call from update_aggragation_formula_field_value()) and when you edit or update a master record, not when you insert a master record
    if ($has_aggregate_function === 1 && in_array($current_function, ['update_master_from_items', 'edit', 'update'])){
		
	    $output[] = " FROM ".$quote.$table_name.$quote;
		
		$linked_table_aggragate_found = 0;

		// search all the items table linked to the main table, to find the one we are looking for ($linked_table_name_aggragate)
        foreach($fields_labels_ar as $value){
            if ($value['items_table_names_field'] != '' && $value['items_table_fk_field_names_field'] != ''){
                $items_table_names = explode(FORM_CONFIGURATOR_SEPARATOR, $value['items_table_names_field']);
                $items_table_fk_field_names_field = explode(FORM_CONFIGURATOR_SEPARATOR, $value['items_table_fk_field_names_field']);

                foreach($items_table_names as $key2 => $value2){
					// when you find the table, store the fk field name of the items table and the corresponding master table unique field name, we need them to build the join later 
                    if ($value2 === $linked_table_name_aggragate){
                        $items_table_fk_field_name = $items_table_fk_field_names_field[$key2];
                        $master_table_unique_field_name = $value['name_field'];
						$linked_table_aggragate_found = 1;
                        break;
                    }
                }
            }
        }
		
		if ($linked_table_aggragate_found === 0){
			throw new Exception('Invalid items table in formula: ' . $linked_table_name_aggragate);
		}

        $output[] = " INNER JOIN ".$quote.$linked_table_name.$quote." ON ".$quote.$linked_table_name.$quote.".".$quote.$items_table_fk_field_name.$quote." = ".$quote.$table_name.$quote.".".$quote.$master_table_unique_field_name.$quote;

        $output[] = " AND ".$quote.$linked_table_name.$quote.".".$quote.$items_table_fk_field_name.$quote." = :".$bind_values_count.' ';

        $this_record_infos = ddb_api::get_record_details($table_name, $where_field, $where_value);
        $bind_values[$bind_values_count] = $this_record_infos[$master_table_unique_field_name];
        
        $bind_values_count++;
    }

	// build the SQL imploding the output array
	$sql_ar['query'] = implode($output);

	$sql_ar['has_aggregate_function'] = $has_aggregate_function;
	$sql_ar['has_empty_placeholder_values'] = $has_empty_placeholder_values;
	$sql_ar['bind_values'] = $bind_values;

	return $sql_ar;
}

/**
 * Execute the SQL statement created by convert_tokens_to_sql() and returns the calculated value
 * 
 * @param string $sql
 * @param int $has_aggregate_function (0|1) flag to know if the SQL statement is an aggregate function
 * @param int $has_empty_placeholder_values (0|1) flag to know if there are empty placeholder values
 * @param string $current_function the current function
 * @param array<int, string> $bind_values
 * @return string|null the calculated value
 * 
 */
private function execute_sql_formula(string $sql, int $has_aggregate_function, int $has_empty_placeholder_values, string $current_function, array $bind_values) : ?string
{
    global $conn;
    $res_prepare = prepare_db($conn, $sql);

    if (count($bind_values) > 0){
        foreach ($bind_values as $key => $value){
            $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
        }
    }

    // always execute the query if edit|update|update_master_from_items, if insert, only if it's not an aggregation function (when I insert a new record, there is no related item records yet)
    // never execute the query if one of the placehold value is still empty
    
    if ($has_empty_placeholder_values === 0 && 
    ($has_aggregate_function === 0 || in_array($current_function, ['update_master_from_items', 'edit', 'update'])
    )){
        $res = execute_prepared_db($res_prepare,0);
        $row = fetch_row_db($res_prepare);

        return $row[0];
    }
    else{
        return NULL;
    }
}

/**
 * wrapper, public method that encapsulates the entire flow: tokenize_formula + convert_tokens_to_sql + execute_sql_formula
 * 
 * 
 * @param array<string, string> $form_values associative array containing the current values of the form fields
 * @param string $current_function the current DaDaBIK function (show_insert_form|insert|edit|update)
 * @param string $table_name The current table
 * @param string $where_field the unique field of the current table
 * @param string $where_value the unique field value of the current record
 * @return string|null the calculated value
 * 
 */
public function execute_field_formula(array $form_values, string $current_function, string $table_name, ?string $where_field, ?string $where_value) : ?string
{
    $tokens = $this->tokenize_formula();
                        
    $sql_ar = $this->convert_tokens_to_sql($tokens, $form_values, $current_function, $table_name, $where_field, $where_value);
    
    return $this->execute_sql_formula($sql_ar['query'], $sql_ar['has_aggregate_function'], $sql_ar['has_empty_placeholder_values'], $current_function, $sql_ar['bind_values']
);

}

}