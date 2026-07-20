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

// CUSTOM BUTTONS

/* add custom buttons here (see documentation for details) */

$custom_buttons['customers'][$cnt]['type'] = 'javascript';
$custom_buttons['customers'][$cnt]['callback_function'] = 'dadabik_copy_customer_address';
$custom_buttons['customers'][$cnt]['permission_needed'] = 'insert';
$custom_buttons['customers'][$cnt]['show_in'][] = 'insert_form';
$custom_buttons['customers'][$cnt]['position_form'] = 'address_account';
$custom_buttons['customers'][$cnt]['label_type'] = 'fixed';
$custom_buttons['customers'][$cnt]['label'] = 'Copy to the invoice address';
$custom_buttons['customers'][$cnt]['style'] = 'background:#000;width:200px';
$cnt++;



$custom_buttons['leads'][$cnt]['type'] = 'php_standard';
$custom_buttons['leads'][$cnt]['callback_function'] = 'dadabik_convert_lead';
$custom_buttons['leads'][$cnt]['permission_needed'] = 'edit';
$custom_buttons['leads'][$cnt]['show_in'][] = 'edit_form';
$custom_buttons['leads'][$cnt]['position_form'] = 'top';
$custom_buttons['leads'][$cnt]['label_type'] = 'fixed';
$custom_buttons['leads'][$cnt]['label'] = 'convert this lead';
$custom_buttons['leads'][$cnt]['style'] = 'background:#ee0000;width:200px';
$cnt++;


// CALCULATED FIELDS FUNCTIONS

function dadabik_calculate_price_tax($parameters_ar){

	if ( $parameters_ar['price_product'] !== '' && !is_null($parameters_ar['price_product']) ){
		return ($parameters_ar['price_product'] * 1.22);
	}
	else{
		return NULL;
	}

}

function dadabik_calculate_reorder_product($parameters_ar){

	if ($parameters_ar['quantity_product'] <= $parameters_ar['reorder_quantity_product']){
		return 'yes';
	}
	return 'no';
}


// CUSTOM VALIDATION FUNCTIONS


// CUSTOM FORMATTING FUNCTIONS

// custom required functions


function dadabik_format_reorder_product($value){

	if ($value === 'yes'){
		return '<img src="images_prepackaged_app/red.png">';
	}
	return '<img src="images_prepackaged_app/green.png">';

}

function dadabik_format_price($value){

	return '<strong><font color="#812704">$'.$value.'</font></strong>';


}

// HOOKS (Operational hooks work with DaDaBIK Enterprise/Platinum only, layout hooks work with DaDaBIK Pro as well )



// OPERATIONAL HOOKS


$hooks['quotes']['update']['before'] = 'dadabik_update_warehouse_date_order';



function dadabik_update_warehouse_date_order($id_sale, $parameters_ar)
{
	global $conn;

	// retreive the current status of the sale
	$old_status_sale = dadabik_get_status_sale($id_sale);

	// if a quote is becoming an order, update the date of the order and the warehouse quantity
	if ($parameters_ar['status_sale'] === 'order' && $old_status_sale = 'quote'){

		echo 0;
		$sql = "UPDATE sales SET date_order = '".date('Y-m-d')."' WHERE id_sale = :id_sale";

		$res_prepare = prepare_db($conn, $sql);

		$values_to_bind = array();

		$values_to_bind['id_sale'] = $id_sale;

		foreach ($values_to_bind as $key => $value)
		{
			$res_bind = bind_param_db($res_prepare, ':'.$key, $value);
		}

		$res_ex = execute_prepared_db($res_prepare,0);


		$sql = "SELECT sale_items.id_product, quantity_product, quantity_sale_item, reorder_quantity_product FROM sale_items INNER JOIN products ON sale_items.id_product = products.id_product WHERE id_sale = :id_sale";

		$res_prepare = prepare_db($conn, $sql);

		$values_to_bind = array();

		$values_to_bind['id_sale'] = $id_sale;

		foreach ($values_to_bind as $key => $value){
			$res_bind = bind_param_db($res_prepare, ':'.$key, $value);
		}

		$res = execute_prepared_db($res_prepare,0);


		while($row = fetch_row_db($res_prepare)){
			$id_product = $row['id_product'];
			$quantity_sale_item = $row['quantity_sale_item'];
			$reorder_quantity_product = $row['reorder_quantity_product'];
			$quantity_product = $row['quantity_product'];

			if ( ($quantity_product - $quantity_sale_item) <= $reorder_quantity_product ){
				$reorder_product = 'yes';
			}
			else{
				$reorder_product = 'no';
			}

			$sql = "UPDATE products SET quantity_product = quantity_product - :quantity_sale_item, reorder_product = :reorder_product WHERE id_product = :id_product";

			$res_prepare_2 = prepare_db($conn, $sql);

			$values_to_bind = array();

			$values_to_bind['id_product'] = $id_product;
			$values_to_bind['quantity_sale_item'] = $quantity_sale_item;
			$values_to_bind['reorder_product'] = $reorder_product;

			foreach ($values_to_bind as $key => $value)
			{
				$res_bind = bind_param_db($res_prepare_2, ':'.$key, $value);
			}

			$res_ex = execute_prepared_db($res_prepare_2,0);

		}

	}

}

function dadabik_get_status_sale($id_sale)
{

	global $conn;

	$sql = "SELECT status_sale from sales WHERE id_sale = :id_sale";

	$res_prepare = prepare_db($conn, $sql);

	$values_to_bind = array();

	$values_to_bind['id_sale'] = $id_sale;

	foreach ($values_to_bind as $key => $value)
	{
		$res_bind = bind_param_db($res_prepare, ':'.$key, $value);
	}

	$res = execute_prepared_db($res_prepare,0);

	$row = fetch_row_db($res_prepare);
	return $row['status_sale'];

}


$hooks['leads']['resultsgrid_header']['after'] = 'dadabik_print_leads_results_header';
$hooks['converted_leads']['resultsgrid_header']['after'] = 'dadabik_print_converted_leads_results_header';
$hooks['contacts']['resultsgrid_header']['after'] = 'dadabik_print_contacts_results_header';
$hooks['customers']['resultsgrid_header']['after'] = 'dadabik_print_customers_results_header';
$hooks['quotes']['resultsgrid_header']['after'] = 'dadabik_print_quotes_results_header';
$hooks['orders']['resultsgrid_header']['after'] = 'dadabik_print_orders_results_header';
$hooks['products']['resultsgrid_header']['after'] = 'dadabik_print_products_results_header';


function dadabik_print_leads_results_header(){
    echo '<p>A <b>lead</b> is a person who might be interested in purchasing products.</p>';
}

function dadabik_print_converted_leads_results_header(){
    echo '<p>When a lead actually shows interest in purchasing products, a salesman can convert the <b>lead</b> in a <b>converted lead</b>, also linking a <b>customer</b>.</p>';
}

function dadabik_print_contacts_results_header(){
    echo '<p>Contacts include <b>leads</b> and <b>converted leads</b>.</p>';
}

function dadabik_print_customers_results_header(){
    echo '<p>A <b>customer</b> can have several <b>contacts</b> linked to it, for example a company you sell products to is the customer, the people (working for the company) you are in touch with are contacts belonging to that customer. If you are familiar with CRM\'s terminology, here the concept of customer is similar to the concept of account in a CRM software.</p>';
}


function dadabik_print_quotes_results_header(){
    echo '<p>When a customer asks for a <b>QUOTE</b>, a salesman can register the quote (specifying the related products and quantities) and at some point convert the quote into an <b>ORDER</b>. Only the salesman who created a quote can modify it.</p>';
}

function dadabik_print_orders_results_header(){
    echo '<p>When a <b>QUOTE</b> is converted into <b>ORDER</b>, the correspondent products sold are subtracted from the warehouse stock; looking at the products page, you can immediately see which products requires reorder.</p>';
}

function dadabik_print_products_results_header(){
    echo '<p>Here is the products catalogue; for each product, among the other information, you can check if it requires reorder (based on quantity in stock and reorder quantity).</p>';
}

function dadabik_convert_lead($table_name, $where_field, $where_value)
{

    global $conn;

    $sql = "update contacts set status_contact = 'converted' where id_contact = :id_contact";

    $res_prepare = prepare_db($conn, $sql);

    $values_to_bind = array();

    $values_to_bind['id_contact'] = $where_value;

    foreach ($values_to_bind as $key => $value)
    {
    	$res_bind = bind_param_db($res_prepare, ':'.$key, $value);
    }

    $res = execute_prepared_db($res_prepare,0);

    header('Location:index.php?tablename=converted_leads&function=edit&where_field=id_contact&where_value=13');
    exit();

}



// LAYOUT HOOKS


// CUSTOM FILTERS (ONLY WORK WITH DaDaBIK Enterprise/Platinum)




?>
