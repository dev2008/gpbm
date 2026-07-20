<script>

function show_hide_report_options(report_type)
{

	if (report_type == 'pie' || report_type == 'doughnut'){
		$("#pie_options").show();
	}
	else{
		$("#pie_options").hide();

	}
	
	if (report_type == 'stat_card'){
		$(".stat_card_incompatible_options").hide();
	}
	else{
		$(".stat_card_incompatible_options").show();

	}
	
}

function show_hide_report_value_field_option(group_by_operator, index_operator)
{
    if (index_operator === 1){
        suffix = '';
    }
    else{
        suffix = '_' + index_operator;
    }
    
	if (group_by_operator == 'count' || group_by_operator == 'percentage' ){
		$("#report_value_field_option_1" + suffix).css('display', 'none');;
		$("#report_value_field_option_2" + suffix).css('display', 'none');;
	}
	else{
		$("#report_value_field_option_1" + suffix).css('display', 'inline-block');;
		$("#report_value_field_option_2" + suffix).css('display', 'inline-block');;

	}
}

$( document ).ready(function() {

	<?php if (isset($report_type) ) { ?>

	show_hide_report_options('<?php echo $report_type; ?>');


	<?php } else{ ?>

	show_hide_report_options('bar');


	<?php } ?>
	
	
	<?php if (isset($_GET['group_by_operator']) ) { ?>

	show_hide_report_value_field_option('<?php echo $_GET["group_by_operator"]; ?>', 1);


	<?php } else{ ?>

	show_hide_report_value_field_option('count');


	<?php } ?>
	
	<?php if (isset($_GET['group_by_operator_2']) ) { ?>

	show_hide_report_value_field_option('<?php echo $_GET["group_by_operator_2"]; ?>', 2);


	<?php } else{ ?>

	show_hide_report_value_field_option('count');


	<?php } ?>
	
	<?php if (isset($_GET['group_by_operator_3']) ) { ?>

	show_hide_report_value_field_option('<?php echo $_GET["group_by_operator_3"]; ?>', 3);


	<?php } else{ ?>

	show_hide_report_value_field_option('count');


	<?php } ?>
	
	<?php if (isset($_GET['group_by_operator_4']) ) { ?>

	show_hide_report_value_field_option('<?php echo $_GET["group_by_operator_4"]; ?>', 4);


	<?php } else{ ?>

	show_hide_report_value_field_option('count');


	<?php } ?>
	
	<?php if (isset($_GET['group_by_operator_5']) ) { ?>

	show_hide_report_value_field_option('<?php echo $_GET["group_by_operator_5"]; ?>', 5);


	<?php } else{ ?>

	show_hide_report_value_field_option('count');


	<?php } ?>
	
	
	
})

</script>


<?php

if (isset($_GET['show_result_static_page']) && isset($_GET['id_static_page'])){
	
	foreach($static_pages_ar as $static_page){
		if ($static_page['id_static_page'] == $_GET['id_static_page']){
			
			txt_out ('<p><h1>'.($static_page['link_static_page']).'</b></h1>');
		}
	}
	
}

else{

txt_out ('<p><h1 id="main_page_heading">'.(get_table_alias($table_name)).'</b></h1>');

}

switch ($function){
    case 'generate_report':
        $function_to_call = 'generate_report';
        $submit_label = 'generate_report';
        break;
    case 'generate_pivot':
        $function_to_call = 'generate_pivot';
        $submit_label = 'generate_pivot';
        break;
    default:
        die('Unexpected report funciton');
}
    


?>

<form  class="css_form"  action="<?php echo $dadabik_main_file; ?>" method="GET">

<input type="hidden" name="tablename" value="<?php echo urlencode($table_name); ?>">
<input type="hidden" name="function" value="<?php echo $function_to_call; ?>">
<input type="hidden" name="show_report_result" value="1">




<?php

// if it's a report coming from a static page (saved report) we have to re-pass the original show_result_static_page and id_static_page to display as selected the right menu item
if (isset($_GET['show_result_static_page']) && isset($_GET['id_static_page'])){

	echo '<input type="hidden" name="show_result_static_page" value="'.(int)$_GET['show_result_static_page'].'">';
	echo '<input type="hidden" name="id_static_page" value="'.(int)$_GET['id_static_page'].'">';

}


echo '<input type="radio" onclick="javascript:document.getElementById(\'advanced_report_parameters\').style.display=\'none\';document.getElementById(\'simple_report_parameters\').style.display=\'inline\';if (document.getElementById(\'chart_container\')){document.getElementById(\'chart_container\').innerHTML=\'\';}" name="report_input_type" value="simple"';

if ( !isset($_GET['report_input_type']) || $_GET['report_input_type'] === 'simple'){
	echo ' checked';
}

echo '>';
echo ucfirst($normal_messages_ar['simple_report']);

echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="report_input_type" onclick="javascript:document.getElementById(\'advanced_report_parameters\').style.display=\'inline\';document.getElementById(\'simple_report_parameters\').style.display=\'none\';if (document.getElementById(\'chart_container\')){document.getElementById(\'chart_container\').innerHTML=\'\';}" value="advanced"';
if ( isset($_GET['report_input_type']) && $_GET['report_input_type'] === 'advanced'){
	echo ' checked';
}

echo '>';
echo ucfirst($normal_messages_ar['advanced_sql_report']);

echo $remove_search_filter_link;
?>
<br/>
<!--<table><tr><td>-->

<?php if ($function === 'generate_report'){ ?>

<br/>

<div class="select_element" style="display:inline-block;vertical-align: middle" ><select class="form-select" name="report_type" onchange="show_hide_report_options(this.value)">
<option value="bar" <?php if (isset($report_type) && $report_type == 'bar') { echo 'selected'; }?>><?php echo $normal_messages_ar['bar_chart']; ?></option>
<option value="line" <?php if (isset($report_type) && $report_type == 'line') { echo 'selected'; }?>><?php echo $normal_messages_ar['line_chart']; ?></option>
<option value="pie" <?php if (isset($report_type) && $report_type == 'pie') { echo 'selected'; }?>><?php echo $normal_messages_ar['pie_chart']; ?></option>
<option value="doughnut" <?php if (isset($report_type) && $report_type == 'doughnut') { echo 'selected'; }?>><?php echo $normal_messages_ar['doughnut_chart']; ?></option>
<option value="stat_card" <?php if (isset($report_type) && $report_type == 'stat_card') { echo 'selected'; }?>>Stat card</option>
</select>  </div> &nbsp;&nbsp;&nbsp;
<!--
</td><td>
-->
<?php if ($function === 'generate_report' ){ ?>
 <span id="chart_dimension_container" class="stat_card_incompatible_options"><input style="display:inline-block" type="text" size="3" name="width_chart" value="<?php echo isset($_GET['width_chart']) ? $_GET['width_chart'] : 400; ?>"> x
 <!--
 </td><td>&nbsp;x&nbsp;</td></td>
<td><td>
-->

<input style="display:inline-block" type="text" size="3" name="height_chart" value="<?php echo isset($_GET['height_chart']) ? $_GET['height_chart'] : 400; ?>">
<!--
</td><td>
-->

&nbsp;px&nbsp;&nbsp;&nbsp;
</span>
<?php } ?>

<!--
</td>

<td>
-->

<span id="pie_options" style="display:none;">
<!--<input type="checkbox" <?php if (isset($_GET['show_labels']) ) { echo 'checked'; }?> name="show_labels"> <?php echo $normal_messages_ar['show_labels']; ?>-->
<!--<input type="checkbox" name="show_legend" <?php if (isset($_GET['show_legend']) ) { echo 'checked'; }?>> <?php echo $normal_messages_ar['show_legend']; ?>-->
</span>
</div>
<!--</td></tr></table>-->
<br/>


<?php } ?>

<br/>
<div id="simple_report_parameters" <?php if (isset($_GET['report_input_type']) && $_GET['report_input_type'] === 'advanced') echo 'style="display:none;"'; ?>>



<div>
<span id="group_by_container" class="stat_card_incompatible_options"><?php


echo $normal_messages_ar['group_data_by'];

if ($function === 'generate_report'){ 
    echo ' ('.$normal_messages_ar['x_axis'].'): ';
}

?>


<div id="date_functions_container" class="select_element"  style="display:inline-block;vertical-align: middle"><select class="form-select" name="date_function" id="date_function">

<?php
foreach($report_date_functions as $key => $value){ 
	
	echo '<option value="'.$value.'"';
	
	if (isset($_GET['date_function']) && $_GET['date_function'] == $value) {
		echo 'selected';
	}
	
	echo '>';
	
	echo ucfirst($report_date_functions[$key]).'</option>';

}
?>
</select></div>

<div class="select_element"  style="display:inline-block;vertical-align: middle"><select  class="form-select" name="group_by_field" id="group_by_field" onchange="show_hide_date_functions_select();">

<?php
foreach($group_by_fields_ar as $key => $value){ 
	
	echo '<option field_type=\''.$group_by_field_types_ar[$key].'\' value=\''.$value.'\'';
	
	if (isset($_GET['group_by_field']) && $_GET['group_by_field'] == $value) {
		echo 'selected';
		
		$selected_group_by_field_label = $group_by_field_labels_ar[$key]; // I need it for the report
	}
	
	echo '>';
	
	echo $group_by_field_labels_ar[$key].'</option>';
	
	

}


?>
</select></div>
</span>
&nbsp;

<?php

if ($function === 'generate_pivot'){
    echo ' <br><br>';
}

echo $normal_messages_ar['show'];

if ($function === 'generate_report' ){ 
    echo ' <span class="stat_card_incompatible_options">('.$normal_messages_ar['y_axis'].')</span>: ';
}
else{
    echo ':<br>';
}
?>

<div class="select_element select_element_100px" style="display:inline-block;vertical-align: middle"><select  class="form-select" name="group_by_operator" onchange="show_hide_report_value_field_option(this.value,1)">

<?php
foreach($group_by_operators as $value){ 
	
	echo '<option value="'.$value.'"';
	
	if (isset($_GET['group_by_operator']) && $_GET['group_by_operator'] == $value) {
		echo 'selected';
	}
	
	echo '>';
	
	echo $normal_messages_ar[$value].'</option>';

}
?>
</select></div>

&nbsp;<div  style="display:none;" id="report_value_field_option_1"><?php echo $normal_messages_ar['of'].': '; ?></div>

<div class="select_element" style="display:none;vertical-align: middle" id="report_value_field_option_2"><select  class="form-select" name="report_value_field">
<?php
foreach($report_value_fields_ar as $key => $value){ 
	
	echo '<option value=\''.$value.'\'';
	
	if (isset($_GET['report_value_field']) && $_GET['report_value_field'] == $value) {
		echo ' selected';
	}
	
	echo '>';
	
	echo $report_value_field_labels_ar[$key].'</option>';

}
?>
</select>

</div>

<?php
if ($function === 'generate_pivot'){ // show the other columns only for pivot
?>
<a class="add_remove_column btn btn-xs btn-primary" data-targetid="2" data-function="add" href=""><?php echo ucfirst($normal_messages_ar['add_column']); ?></a> <br>



<?php
//////////////////////////////////////////////
// start 2
//////////////////////////////////////////////

if (isset($_GET['group_by_operator_2'])){
    $display_css_next_block = 'block';
    $disabled_next_input = '';
}
else{
    $display_css_next_block = 'none';
    $disabled_next_input = ' disabled';
}

?>

<div id="column_container_2" style="display:<?php echo $display_css_next_block; ?>;">
<div class="select_element select_element_100px"    style="display:inline-block;vertical-align: middle"><select  class="form-select" <?php echo $disabled_next_input; ?> name="group_by_operator_2" onchange="show_hide_report_value_field_option(this.value,2)">

<?php
foreach($group_by_operators as $value){ 	
	echo '<option value="'.$value.'"';
	if (isset($_GET['group_by_operator_2']) && $_GET['group_by_operator_2'] == $value) {
		echo 'selected';
	}
	echo '>';
	echo $normal_messages_ar[$value].'</option>';

}
?>
</select></div>

&nbsp;<div  style="display:none;" id="report_value_field_option_1_2"><?php echo $normal_messages_ar['of'].': '; ?></div>

<div class="select_element"  style="display:none;vertical-align: middle" id="report_value_field_option_2_2"><select  class="form-select" name="report_value_field_2" >
<?php
foreach($report_value_fields_ar as $key => $value){ 
	
	echo '<option value=\''.$value.'\'';
	
	if (isset($_GET['report_value_field_2']) && $_GET['report_value_field_2'] == $value) {
		echo ' selected';
	}
	
	echo '>';
	
	echo $report_value_field_labels_ar[$key].'</option>';

}
?>
</select></div>
<a class="add_remove_column btn btn-xs btn-primary" data-targetid="3" data-function="add" href=""><?php echo ucfirst($normal_messages_ar['add_column']); ?></a>&nbsp;&nbsp;&nbsp;<a class="add_remove_column btn btn-xs btn-outline-primary" data-targetid="2" data-function="remove" href=""><?php echo ucfirst($normal_messages_ar['remove_this_column']); ?></a> <br>




</div>

<?php
//////////////////////////////////////////////
// end 2
//////////////////////////////////////////////
?>

<?php
//////////////////////////////////////////////
// start 3
//////////////////////////////////////////////

if (isset($_GET['group_by_operator_3'])){
    $display_css_next_block = 'block';
    $disabled_next_input = '';
}
else{
    $display_css_next_block = 'none';
    $disabled_next_input = ' disabled';
}

?>

<div id="column_container_3" style="display:<?php echo $display_css_next_block; ?>;">
<div class="select_element select_element_100px"    style="display:inline-block;vertical-align: middle"><select  class="form-select" <?php echo $disabled_next_input; ?> name="group_by_operator_3" onchange="show_hide_report_value_field_option(this.value,3)">

<?php
foreach($group_by_operators as $value){ 	
	echo '<option value="'.$value.'"';
	if (isset($_GET['group_by_operator_3']) && $_GET['group_by_operator_3'] == $value) {
		echo 'selected';
	}
	echo '>';
	echo $normal_messages_ar[$value].'</option>';

}
?>
</select></div>

&nbsp;<div  style="display:none;" id="report_value_field_option_1_3"><?php echo $normal_messages_ar['of'].': '; ?></div>

<div class="select_element"  style="display:none;vertical-align: middle" id="report_value_field_option_2_3"><select  class="form-select" name="report_value_field_3" >
<?php
foreach($report_value_fields_ar as $key => $value){ 
	
	echo '<option value=\''.$value.'\'';
	
	if (isset($_GET['report_value_field_3']) && $_GET['report_value_field_3'] == $value) {
		echo ' selected';
	}
	
	echo '>';
	
	echo $report_value_field_labels_ar[$key].'</option>';

}
?>
</select></div>
<a class="add_remove_column btn btn-xs btn-primary" data-targetid="4" data-function="add" href=""><?php echo ucfirst($normal_messages_ar['add_column']); ?></a>&nbsp;&nbsp;&nbsp;<a class="add_remove_column btn btn-xs btn-outline-primary" data-targetid="3" data-function="remove" href=""><?php echo ucfirst($normal_messages_ar['remove_this_column']); ?></a> <br>

</div>

<?php
//////////////////////////////////////////////
// end 3
//////////////////////////////////////////////
?>

<?php
//////////////////////////////////////////////
// start 4
//////////////////////////////////////////////

if (isset($_GET['group_by_operator_4'])){
    $display_css_next_block = 'block';
    $disabled_next_input = '';
}
else{
    $display_css_next_block = 'none';
    $disabled_next_input = ' disabled';
}

?>

<div id="column_container_4" style="display:<?php echo $display_css_next_block; ?>;">
<div class="select_element select_element_100px"    style="display:inline-block;vertical-align: middle"><select  class="form-select" <?php echo $disabled_next_input; ?> name="group_by_operator_4" onchange="show_hide_report_value_field_option(this.value,4)">

<?php
foreach($group_by_operators as $value){ 	
	echo '<option value="'.$value.'"';
	if (isset($_GET['group_by_operator_4']) && $_GET['group_by_operator_4'] == $value) {
		echo 'selected';
	}
	echo '>';
	echo $normal_messages_ar[$value].'</option>';

}
?>
</select></div>

&nbsp;<div  style="display:none;" id="report_value_field_option_1_4"><?php echo $normal_messages_ar['of'].': '; ?></div>

<div class="select_element"  style="display:none;vertical-align: middle" id="report_value_field_option_2_4"><select  class="form-select" name="report_value_field_4" >
<?php
foreach($report_value_fields_ar as $key => $value){ 
	
	echo '<option value=\''.$value.'\'';
	
	if (isset($_GET['report_value_field_4']) && $_GET['report_value_field_4'] == $value) {
		echo ' selected';
	}
	
	echo '>';
	
	echo $report_value_field_labels_ar[$key].'</option>';

}
?>
</select></div>
<a class="add_remove_column btn btn-xs btn-primary" data-targetid="5" data-function="add" href=""><?php echo ucfirst($normal_messages_ar['add_column']); ?></a>&nbsp;&nbsp;&nbsp;<a class="add_remove_column btn btn-xs btn-outline-primary" data-targetid="4" data-function="remove" href=""><?php echo ucfirst($normal_messages_ar['remove_this_column']); ?></a> <br>

</div>

<?php
//////////////////////////////////////////////
// end 4
//////////////////////////////////////////////
?>


<?php
//////////////////////////////////////////////
// start 5
//////////////////////////////////////////////

if (isset($_GET['group_by_operator_5'])){
    $display_css_next_block = 'block';
    $disabled_next_input = '';
}
else{
    $display_css_next_block = 'none';
    $disabled_next_input = ' disabled';
}

?>

<div id="column_container_5" style="display:<?php echo $display_css_next_block; ?>;">
<div class="select_element select_element_100px"    style="display:inline-block;vertical-align: middle"><select  class="form-select"  <?php echo $disabled_next_input; ?> name="group_by_operator_5" onchange="show_hide_report_value_field_option(this.value,5)">

<?php
foreach($group_by_operators as $value){ 	
	echo '<option value="'.$value.'"';
	if (isset($_GET['group_by_operator_5']) && $_GET['group_by_operator_5'] == $value) {
		echo 'selected';
	}
	echo '>';
	echo $normal_messages_ar[$value].'</option>';

}
?>
</select></div>

&nbsp;<div  style="display:none;" id="report_value_field_option_1_5"><?php echo $normal_messages_ar['of'].': '; ?></div>

<div class="select_element"  style="display:none;vertical-align: middle" id="report_value_field_option_2_5"><select  class="form-select" name="report_value_field_5" >
<?php
foreach($report_value_fields_ar as $key => $value){ 
	
	echo '<option value=\''.$value.'\'';
	
	if (isset($_GET['report_value_field_5']) && $_GET['report_value_field_5'] == $value) {
		echo ' selected';
	}
	
	echo '>';
	
	echo $report_value_field_labels_ar[$key].'</option>';

}
?>
</select></div>
 <a class="add_remove_column btn btn-xs btn-outline-primary" data-targetid="5" data-function="remove" href=""><?php echo ucfirst($normal_messages_ar['remove_this_column']); ?></a> <br>

</div>

<?php
//////////////////////////////////////////////
// end 5
//////////////////////////////////////////////
?>

 <?php } ?>
</div>
<br/>
<br/>
<input class="button_form btn btn-primary"  type="submit" value="<?php echo $normal_messages_ar[$submit_label]; ?>"  id="form_generate_chart_pivot_btn"> <a class="btn btn-outline-primary" href="<?php echo $dadabik_main_file; ?>?function=search&tablename=<?php echo urlencode($table_name); ?>"> <?php echo $submit_buttons_ar['go_back']; ?></a>

</div>



<br/>

<div id="advanced_report_parameters" <?php if (!isset($_GET['report_input_type']) || $_GET['report_input_type'] === 'simple') echo 'style="display:none;"'; ?>>

<?php echo $normal_messages_ar['type_your_custom_sql_query_here']; ?>  <?php if (isset($_SESSION['filters_ar_'.$table_name]) || isset($_SESSION['search_all_filter_'.$table_name])){ echo $normal_messages_ar['current_search_filter_is_not_used'];} ?>  <a href="javascript:show_admin_help('', '<?php echo $normal_messages_ar['advanced_sql_report_instructions_first_part'].$normal_messages_ar['advanced_sql_report_instructions_query_part'].$normal_messages_ar['advanced_sql_report_instructions_second_part']; ?><br><br><?php echo $normal_messages_ar['advanced_sql_report_instructions_stat_card_part']; ?> <br><br><?php echo $normal_messages_ar['advanced_sql_report_instructions_pivot_part']; ?>');"><i class="far fa-question-circle fa-lg"></i></a><br/>


<?php if ($enable_advanced_sql_report === 1){ ?>

<textarea rows="5" cols="100" name="sql_report" placeholder="SELECT ... "><?php if (isset($_GET['sql_report'])) echo $_GET['sql_report']; ?></textarea><br/>
<input class="button_form btn btn-primary"  type="submit" value="<?php echo $normal_messages_ar[$submit_label]; ?> "  id="form_generate_chart_pivot_btn">  <a class="btn btn-outline-primary" href="<?php echo $dadabik_main_file; ?>?function=search&tablename=<?php echo urlencode($table_name); ?>"> <?php echo $submit_buttons_ar['go_back']; ?></a>
<br/><br/>




<?php } else{ 

txt_out('<div class="msg_alert"><p>'.$normal_messages_ar["advanced_sql_reports_are_disabled"].'</p></div>');

 }  ?>
 


</div>


</form>
