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
<br>
<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator'){
	echo '<br><br>';
	$powered_alignment = 'right';
	$spaces = 1;
}
elseif ($page_name === 'data'){
	$powered_alignment = 'right';
	$spaces = 1;
}
else{
	$powered_alignment = 'center';
	$spaces = 0;
}	
?>
<?php
if ($page_name === 'main'){
    
    if (isset($hooks['custom_footer_1'])){
        if ( substr($hooks['custom_footer_1'],0,8) === 'dadabik_'){
            call_user_func($hooks['custom_footer_1']);
        }
    }
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
<?php
if ($page_name === 'main'){
    if (isset($hooks['custom_footer_2'])){
        if ( substr($hooks['custom_footer_2'],0,8) === 'dadabik_'){
            call_user_func($hooks['custom_footer_2']);
        }
    }
}
?>
<?= $br_before_powered ?>
<div class="powered_by_dadabik" align="<?php echo $powered_alignment; ?>">Powered by: <a target="_blank" href="https://dadabik.com/" rel="nofollow">DaDaBIK</a>, the Low-code Development Platform</a>
<?php
if ($spaces === 1){
    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
}
?>
<br/><br/></div>
</td>
</tr>
</table>
<div class="modal"></div>
<div class="confirmation_message"><h1>Done!</h1></div>
<script>

$(document).ready(function() {

    function set_opener_lookup_field_value(field_name, field_value, field_display_value, field_type){
        if (field_type === 'standard'){
        
            var select_element = window.opener.document.getElementsByName(field_name)[0]
            var option = document.createElement("option");
            option.text = field_display_value;
            option.value = field_value;
            select_element.add(option);
            select_element.value = field_value;
            window.opener.$('select[name="'+field_name+'"]').trigger('change');
        
            /*if ( window.opener.$('select[name="'+field_name+'"]').hasClass("select2-hidden-accessible") ) {
        
                // manually trigger the `select2:select` event
                window.opener.$('select[name="'+field_name+'"]').trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            }
            else{
                window.opener.$('select[name="'+field_name+'"]').trigger('change');
            }
            });
            */
        }
    }
<?php if (isset($set_opener_lookup_field_value) && $set_opener_lookup_field_value === 1){ ?>

field_name = '<?php echo $lookup_insert_popup_field_from; ?>';
field_value = '<?php echo $lookup_insert_popup_field_new_value; ?>';
field_display_value = '<?php echo $lookup_insert_popup_field_new_display_value; ?>';
field_type = 'standard';


set_opener_lookup_field_value(field_name, field_value, field_display_value, field_type);
<?php } ?>

<?php 
if ( $page_name === 'main' && ( $function === 'show_insert_form' ||  $function === 'insert' ||  $function === 'edit' ||  $function === 'update' ||  $function === 'show_search_form'  ||  $function === 'search' ) ){  ?>
function enable_ajax_dropdown(field_name, chosen_ajax_field, _POST_2, details_row, form_type, show_edit_form_after_error, user_group_name, i, field_type){
    
    field_name_for_selection = field_name;

    if (field_type === 'select_multiple_menu'){
        field_name_for_selection = field_name + '[]';
    }
    
    if (chosen_ajax_field == '0'){
        $('select[name="'+field_name_for_selection+'"]').select2({
            dropdownAutoWidth : 'true',
            width: 'auto',
            allowClear: true,
          //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: <?php echo $ajax_dropdown_minimum_input_length; ?>
          //templateResult: formatRepo, // omitted for brevity, see the source of this page
          //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    }
    else{
         // similar code in header
        $('select[name="'+field_name_for_selection+'"]').select2({
          ajax: {
            url: "index.php?function=get_options<?php if (isset($master_table_name)){ echo "&master_table_name=".urlencode($master_table_name); } ?>&tablename=<?php echo $table_name; ?>&api_call=1",
    
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page,
                current_function: '<?php echo $function; ?>',
                field_name: field_name,
                //field_value: field_value,
                _POST_2:  _POST_2,
                details_row: details_row,
                form_type: form_type,
                show_edit_form_after_error: show_edit_form_after_error,
                user_group_name: user_group_name,
                i: i,
              };
            },
            processResults: function (data, params) {
              // parse the results into the format expected by Select2
              // since we are using custom formatting functions we do not need to
              // alter the remote JSON data, except to indicate that infinite
              // scrolling can be used
          
              params.page = params.page || 1;

              return {
                results: data.results
                /*
                pagination: {
                  more: (params.page * 30) < data.total_count
                }
                */
              };
         
            }
          },
  
            dropdownAutoWidth : 'true',
            allowClear: true,
            width: 'auto',
          //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: <?php echo $ajax_dropdown_minimum_input_length; ?>
          //templateResult: formatRepo, // omitted for brevity, see the source of this page
          //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    }
}

<?php

    if (  $function === 'show_insert_form' ||  $function === 'insert' ){
        $form_type_to_pass_ajax = 'insert';
    }
    else if (  $function === 'edit' ||  $function === 'update' ){
        $form_type_to_pass_ajax = 'update';
    }
    else if (  $function === 'show_search_form' || $function === 'search'){
        $form_type_to_pass_ajax = 'search';
    }
    
    if (isset($fields_labels_ar_before_master_details)){
    $fields_labels_ar = $fields_labels_ar_before_master_details;
}
    
    // details_row useful for cascade and ownership, currently not considered
    foreach ($fields_labels_ar as $key => $value){

       if ( $value['chosen_field'] == '1' ){
            //echo 'enable_ajax_dropdown('.json_encode($value['name_field']).', $(\'select[name="'.$value['name_field'].'"]\').val(), '.json_encode(unescape_array($_POST)).', \'\', \''.$form_type_to_pass_ajax.'\', '.$show_edit_form_after_error.', '.json_encode($user_group_name).', '.$key.');';
            echo 'enable_ajax_dropdown('.json_encode($value['name_field']).','.json_encode($value['chosen_ajax_field']).', '.json_encode(unescape_array($_POST)).', \'\', \''.$form_type_to_pass_ajax.'\', '.$show_edit_form_after_error.', '.json_encode($user_group_name).', '.$key.', \''.$value['type_field'].'\');';
        }
    }
}

?>

<?php
if ($page_name === 'interface_configurator'){
foreach ($custom_functions_files_ar as $key => $value){
?>

$('select[name^="<?php echo $key; ?>"]').select2({
            dropdownAutoWidth : 'true',
            width: 'auto',
            allowClear: true,
            placeholder: "",
            language: { inputTooShort: function () { return 'Type "dada" to start'; } },
          //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: <?php echo $ajax_dropdown_minimum_input_length; ?>
          //templateResult: formatRepo, // omitted for brevity, see the source of this page
          //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
<?php
}
}
?>

});

</script>
<?php
if ($page_name === 'interface_configurator' && $enable_form_config_live_preview === 1){
?>
<script>
function createSplitPanel() {
    return Split(['#left-panel', '#right-panel'], {
        gutterSize: 7,
        elementStyle: (dimension, size, gutterSize) => ({
            'flex-basis': `calc(${size}% - ${gutterSize}px)`,
        }),
        gutterStyle: (dimension, gutterSize) => ({
            'flex-basis': `${gutterSize}px`,
        }),
    })
}

var splitPaneInstance;
showPreview();

function hidePreview() {
    splitPaneInstance.destroy();
    $('[data-hide-preview]').hide();
    $('[data-show-preview]').show();
    document.querySelector('#right-panel').style.display = 'none';
    document.querySelector('#left-panel').style.width = '100%';
}

function showPreview() {
        $('[data-hide-preview]').show();
        $('[data-show-preview]').hide();
    document.querySelector('#right-panel').style.display = 'block';
    document.querySelector('#left-panel').style.width = '50%';
    splitPaneInstance = createSplitPanel();
}
</script>
<?php
}
?>
<?php
if (isset($show_maintenance_header) && $show_maintenance_header === 1){

    $end_time = microtime(true);
    
    echo '<div align="center"><b>YOU ARE IN MAINTENANCE MODE</b>. This page has been processed in '.($end_time-$start_time).' seconds.</div>';
    
}
?>
<?php if ($page_name === 'main' && $enable_live_edit === 1 && $show_double_click_message === 1){ ?>
<!-- "Double click to edit" toast message -->
<div class="toast-container position-fixed bottom-0 start-50 translate-middle-x zindex-6 p-3">
      <div class="toast bg-info show" id="double_click_tooltip" data-bs-autohide="false" role="alert" aria-live="assertive" aria-atomic="true" style="--dk-toast-max-width: 220px;" >
        <div class="toast-body py-2"  style="background-color:rgb(76, 130, 247);border-radius: 6px;">
          <div class="d-flex align-items-center fw-semibold text-white py-1" >
            <i class="bx bx-edit-alt fs-xl py-1 me-2"></i>
            <?= $normal_messages_ar['double_click_to_edit'] ?>
          </div>
        </div>
      </div>
    </div>
<?php } ?>
</body>
</html>