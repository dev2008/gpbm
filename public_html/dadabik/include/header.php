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
<!DOCTYPE html>
<html
<?php
switch($grid_layout_scrolling)
{
	case 'site_overflow':
		echo ' class="site-overflow "';
		break;
	case 'grid_overflow':
		echo ' class="grid-overflow "';
		break;
}

?>>
<head>
<title><?php echo $title_application; ?></title>
<link rel="stylesheet" href="css/normalize.css" type="text/css" media="screen">
<?php if (true || get_page_type($page_name) === 'admin'){ ?>

<link rel="stylesheet" href="css/bootstrap_5.3.2.css">

<?php } else{ ?>

<link rel="stylesheet" href="css/bootstrap.css">

<?php } ?>
<?php if ($page_name === 'main' || $page_name === 'login'){ ?>
<link rel="stylesheet" href="css/styles_screen_13.5.css<?php if ( $force_reload_css_js === 1){ echo '?v='.rand(1,10000);} ?>" type="text/css">
<?php if ($menu_type === 'drop_down_menu' || isset($menu_items_ar) && count($menu_items_ar) <= 1 && $dont_show_menu_if_only_one_item === 1){ ?>
<link rel="stylesheet" href="css/styles_screen_drop_down_menu_12.6.css" type="text/css">
<?php } ?>
<?php
if ($ui_style === 'modern'){
    echo '<link rel="stylesheet" href="css/styles_screen_13.5_26_ui.css';
    
    if ( $force_reload_css_js === 1){
        echo '?v='.rand(1,10000);
    }
    
    echo '" type="text/css">';
}
?>
<?php } else{ ?>
<link rel="stylesheet" href="css/styles_screen_old_13.5.css<?php if ( $force_reload_css_js === 1){ echo '?v='.rand(1,10000);} ?>" type="text/css" media="screen">
<?php } ?>
<link href='include/boxicons/css/boxicons.min.css' rel='stylesheet'>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Generator" content="DaDaBIK <?= $dadabik_config_version ?> <?= $dadabik_config_codename ?> - http://dadabik.com/">
<meta name="viewport" content="initial-scale=1.0"/>

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">


<?php if ($page_name === 'main' || $page_name === 'login' || $page_name === 'datagrid_configurator'){ ?>
<!-- Web font -->
<link rel="preload" href="include/fonts/<?= $config['font'] ?>/<?= $config['font'] ?>.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" media="screen" href="include/fonts/<?= $config['font'] ?>/fonts.css">
<?php } ?>

<script language="javascript" type="text/javascript" src="include/tinymce_6.8.6/tinymce.min.js"></script>

<script src="include/tagify_4.37.1/tagify.js"></script>
<script src="include/tagify_4.37.1/tagify.polyfills.min.js"></script>
<link href="include/tagify_4.37.1/tagify.css" rel="stylesheet" type="text/css" />


<script src="include/jquery/jquery_3.7.1.min.js"></script>
<script src="include/jquery/jquery-ui-1.14.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="include/jquery/jquery-ui-1.14.1/jquery-ui.min.css" type="text/css" media="screen">
<script src="include/FileUploader_12.6.js"></script>

<?php if ($page_name === 'admin' || $page_name === 'interface_configurator'){ ?>
<script src="include/Sortable_1.15.17.min.js"></script>
<?php } ?>

<script>
if (!document.cookie.includes("is_mobile=")) {
    const isMobile = window.innerWidth <= 768;
    document.cookie = `is_mobile=${isMobile}; path=/`;
}
<?php if ($page_name === 'login'){ ?>

// remove localstorage data about calendar start dates
// I should do it only when logout (or successful login), but they have problematic redirects
const sitePath = '<?=$site_path; ?>';
const keyPrefix = 'dadabik_' + sitePath.replace(/\//g, '_');
const localstorage_key = keyPrefix + '_calendar_start_dates';
localStorage.removeItem(localstorage_key);
console.log(0);


<?php } ?>


<?php if ($page_name === 'main'){ ?>
var msg_generic_upload_error = '<?php echo $normal_messages_ar["generic_upload_error"]; ?>';
var msg_file_uploaded_file_will_replace = '<?php echo $normal_messages_ar["file_uploaded_file_will_replace"]; ?>';
<?php } ?>
<?php if ($page_name === 'datagrid_configurator' && isset($function) && $function === 'show_basic_settings'){ ?>
var msg_generic_upload_error = 'Upload error';
var msg_file_uploaded_file_will_replace = 'File uploaded! The file will replace the current logo (if any) after saving the form.';

// theme color input fields
const input_field_names = [
<?php
foreach ($config_basic_settings_fields as $key => $value){
    if (isset($config_basic_settings_field_categories[$key]) && $config_basic_settings_field_categories[$key] === 'theme_color'){
        echo "'".$value."',";
    }
}
?>
];

$(document).ready(function() {

    // add event listener for each theme color field related to the custom theme (for the others, we don't need, since they are readonly)
    input_field_names.forEach(input_field_name => {
        const input_field = document.getElementById(input_field_name + '_custom');
        const color_picker = document.getElementById(input_field_name + '_picker_custom');
        
        // Update the text input when the picker changes
        color_picker.addEventListener('input', function() {
            input_field.value = this.value;
        });

        // Update the picker when the text input changes
        input_field.addEventListener('input', function() {
            color_picker.value = this.value;
        });
        
    });

});


function change_font_preview(font)
{
    
    const font_url = `include/fonts/${font}/${font}.woff2`;

    // Remove any previously added font-face style
    $('#font_face_style').remove();

    // Create a new @font-face rule
    const font_face_rule = `
        @font-face {
            font-family: '${font}';
            src: url('${font_url}') format('woff2');
        }
    `;

    // Append the new @font-face rule to the document
    $('<style>', {
        id: 'font_face_style',
        text: font_face_rule
    }).appendTo('head');

    // Apply the selected font to the preview text
    $('#font_preview').css('font-family', font);
    $('#font_preview_font_name').html('<b>' + font.replace(/_/g, ' ') + '</b>');
}

// change color values when a theme is selected 
function change_theme_color_hexs(theme){
    
    fetch('themes/' + theme + '/colors.json')
        .then(response => {
            if (!response.ok) {
                throw new Error("Bad network response change_theme_color_hexs " + response.statusText);
            }
            return response.json();
        })
        .then(data => {

<?php
foreach ($config_basic_settings_fields as $key => $value){
    if (isset($config_basic_settings_field_categories[$key]) && $config_basic_settings_field_categories[$key] === 'theme_color'){
        echo 'var field_name = "'.$value.'"';
?>      
            
        if (theme === 'Custom'){ // just show the DIV, don't update values
            $('.theme_color_selectors_wrapper').css("display", "block");
            $('.theme_color_readonly_wrapper').css("display", "none");

        }
        else{
            $('.theme_color_selectors_wrapper').css("display", "none");
            $('.theme_color_readonly_wrapper').css("display", "block");
            $("#" + field_name).val(data[field_name]);
            $("#" + field_name + '_picker').val(data[field_name]);  
        }
        
<?php
    }
}
?>
        })
        .catch(error => {
            console.error("Error fetching JSON:", error);
        });
}
</script>




<?php } ?>
</script>
<script type="application/javascript" src="include/FileUploaderFactory_12.6.js"></script>
<?php if ($page_name === 'main'){ ?>
<?php if ($date_picker_type === 'flatpickr'){ ?>
<link rel="stylesheet" href="include/flatpickr_4.6.13/flatpickr.min.css">
<?php if ($date_picker_theme === 'dark') { ?>
<link rel="stylesheet" type="text/css" href="include/flatpickr_4.6.13/dark.css">
<?php } ?>
<script src="include/flatpickr_4.6.13/flatpickr.js"></script>
<script src="include/flatpickr_4.6.13/l10n/<?php echo $languages_flatpickr_codes[$language]; ?>.js"></script>
<?php } else{ ?>
<script src="include/date_picker/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css" type="text/css" media="screen">
<?php } ?>
<?php } ?>
<link rel="stylesheet" href="include/fontawesome5/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/fontawesome.css"> <!-- fontawesome4 -->



<!-- select2 -->
<link href="css/select2/select2_4.0.13.min.css" rel="stylesheet" />
<script src="include/select2/select2_4.0.13.min.js"></script>
<script src="include/LiveEditing_12.6.js<?php if ( $force_reload_css_js === 1){ echo '?v='.rand(1,10000);} ?>"></script>
<?php
if ($page_name === 'main' || $page_name === 'login'){

    // If the theme is Default, the standard CSS file is used, without additional internal style.
    // Otherwise, we parse the CSS Theme Template according to the colors defined in the colors.json file for the theme seleced. If the theme is Custom, we parse the Template according to the user's custom defined colors. This logic is implemented by parse_css_theme_template()  
    if($config['theme'] !== 'Default' && $ui_style === 'classic'){
        $css_theme = parse_css_theme_template();
        echo '<style>'.$css_theme.'</style>';

        if($config['theme'] === 'Dark' || $config['theme'] === 'Dark_Light-Top'){ // additional CSS rules just for the dark theme
            echo '<link href="css/dark_theme_12.7.css';

            if ( $force_reload_css_js === 1){
                echo '?v='.rand(1,10000);
            }
            
            echo '" rel="stylesheet" />';
            if($config['theme'] === 'Dark'){
                echo '<style>#language_button, #account_button{color: #cfcfcf!important;}</style>';
            }
        }        
    } 
    
	echo '<link rel="stylesheet" href="css/styles_screen_custom.css';
	if ( $force_reload_css_js === 1){
	    echo '?v='.rand(1,10000);
	}
	echo '" type="text/css" media="screen">';
	echo "<style>\n".$config['custom_css']."\n</style>";

    if ($config['font'] !== 'manrope'){
        echo "<style>\nbody,input,textarea,.main-menu{font-family: '".ucwords(str_replace('_', ' ', $config['font']))."',Arial, sans-serif;}\n</style>"; 
    }



}
?>

<script language="javascript" type="text/javascript">
tinyMCE.init({
	//mode : "specific_textareas",
	//editor_selector : "rich_editor",
	selector : "textarea.rich_editor",
	promotion: false,
	branding: false,
 <?php if ($page_name !== 'tables_inclusion' && $page_name !== 'datagrid_configurator'){ ?>
    sandbox_iframes: true,
<?php } ?>
    convert_unsafe_embeds: true,

<?php if ($page_name === 'tables_inclusion' || $page_name === 'datagrid_configurator'){ ?>
	min_height: 600,
    content_css: [
                'css/bootstrap_5.3.2.css'
            ],
<?php } ?>

<?php if ($page_name === 'main' && isset($function) && ( ($function == 'insert' || $function == 'show_insert_form') && $warn_unsaved_changes_insert_form === 1 || ($function == 'edit' || $function == 'update') && $warn_unsaved_changes_edit_form === 1)){ ?>

    setup: function(editor) {
        editor.on('Dirty', function(e) {
            $("#dadabik_main_form").dirty('setAsDirty')   ;
        });
    },
<?php } ?>
	plugins: [
		'advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','code','fullscreen','insertdatetime','media','table','lists'
	],
});


</script>
<?php if (is_file('include/custom_functions_prepackaged_app.js')){ ?>
<script src="include/custom_functions_prepackaged_app.js<?php if ( $force_reload_css_js === 1){ echo '?v='.rand(1,10000);} ?>"></script>
<?php } else{ ?>
<script src="include/<?=$file_custom_functions_js; ?><?php if ( $force_reload_css_js === 1){ echo '?v='.rand(1,10000);} ?>"></script>
<?php } ?>

<script>
var js_select_type_select_suffix = '<?php echo $select_type_select_suffix; ?>';
</script>
<script src="include/general_functions_13.0.js"></script>

<script>
$(document).ready(function() {

<?php if ( $page_name === 'main'){ ?>

var dadabik_main_form_submission_enabled = true;
$('#dadabik_main_form').on('submit', function(e){
if (dadabik_main_form_submission_enabled === true){
    dadabik_main_form_submission_enabled = false;
}
else{
    e.preventDefault();
}
});

var dadabik_duplication_form_submission_enabled = true;
$('#dadabik_duplication_form').on('submit', function(e){
if (dadabik_duplication_form_submission_enabled === true){
    dadabik_duplication_form_submission_enabled = false;
}
else{
    e.preventDefault();
}
});

<?php } ?>

<?php if ( $page_name === 'interface_configurator' && $enable_form_config_live_preview === 1){ ?>

    $(document).on('click', "label[id$='_label']" , function() {
        window.location.replace("internal_table_manager.php?tablename=<?= $table_name; ?>&field_name_to_show="+encodeURI(this.htmlFor));
    });

<?php } ?>


<?php if ( $page_name === 'main' && $enable_row_highlighting === 1){ ?>
    $( ".tr_results_1" ).mouseover(function() {
        if (this.className!='tr_highlighted_onclick'){
            this.className='tr_highlighted_onmouseover';
        }
    });

    $( ".tr_results_1" ).mouseout(function() {
        if (this.className!='tr_highlighted_onclick'){
            this.className='tr_results_1';
        }
    });

    $( ".tr_results_1" ).click(function() {
        if (this.className=='tr_highlighted_onclick'){
            this.className='tr_results_1';
        }
        else{
            this.className='tr_highlighted_onclick';
        }
    });

    a=$('[myc="blue"][myid="1"],[myc="blue"][myid="3"]');

    $( "td[data-live-edit][data-type=\"text\"][data-field!=\"id\"],[data-live-edit][data-type=\"date\"][data-field!=\"id\"],[data-live-edit][data-type=\"date_time\"][data-field!=\"id\"],[data-live-edit][data-type=\"textarea\"][data-field!=\"id\"]" ).hover(function() {
        $( "#double_click_tooltip" ).css('visibility', 'visible');
        $( "#double_click_tooltip" ).css('opacity', '1');
    }, function(){
        $( "#double_click_tooltip" ).css('visibility', 'hidden');
        $( "#double_click_tooltip" ).css('opacity', '0');
    });

    $( "td[data-live-edit][data-type=\"text\"][data-field!=\"id\"],[data-live-edit][data-type=\"date\"][data-field!=\"id\"],[data-live-edit][data-type=\"date_time\"][data-field!=\"id\"],[data-live-edit][data-type=\"textarea\"][data-field!=\"id\"]" ).click(function() {
        $( "#double_click_tooltip" ).css('visibility', 'hidden');
        $( "#double_click_tooltip" ).css('opacity', '0');
    });

    $( ".tr_results_2" ).mouseover(function() {
        if (this.className!='tr_highlighted_onclick'){
            this.className='tr_highlighted_onmouseover';
        }
    });

    $( ".tr_results_2" ).mouseout(function() {
        if (this.className!='tr_highlighted_onclick'){
            this.className='tr_results_1';
        }
    });

    $( ".tr_results_2" ).click(function() {
        if (this.className=='tr_highlighted_onclick'){
            this.className='tr_results_2';
        }
        else{
            this.className='tr_highlighted_onclick';
        }
    });
<?php } ?>
    $.widget.bridge('uitooltip', $.ui.tooltip); // to avoid conflicts with bootstrap tooltip
    $(document).uitooltip({show: null});

<?php if ( $page_name === 'datagrid_configurator'){ ?>
     $('[data-dadabik-uploader]').each(function () {
        dadabikUploader($(this), $('#uploader-dadabik-form-template'), 'api.php?function=upload_logo');
    })

    function show_hide_calendar_fields() {
        const selected_valude = $('input[name="record_list_layout"]:checked').val();
        if (selected_valude === 'calendar') {
            $('#calendar_fields_container').show();
        } else {
            $('#calendar_fields_container').hide();
        }
    }
    $('input[name="record_list_layout"]').on('change', show_hide_calendar_fields);

    // Initial check on page load
    show_hide_calendar_fields();


<?php }else{ ?>
     $('[data-dadabik-uploader]').each(function () {
        dadabikUploader($(this), $('#uploader-dadabik-form-template'), 'api_fe.php?function=upload_file');
    })
<?php } ?>

    $(function () {
        $('li.has-sub-menu > a').on("click", function (e) {
            e.preventDefault();
            var $listItem = $(this).parents("li:first");
            $listItem.toggleClass("sub-menu-opened");
            $listItem.toggleClass("selected");
        });

        $('[data-mobile-menu-toggle]').on("click", function () {
            $('.main-menu-container').toggleClass("menu-mobile-opened");
        });

<?php if ($ui_style === 'modern'){ ?>

$('#hide_quick_filters_link').on("click", function (e) {
            e.preventDefault();

            var $form = $("#dadabik_quick_search_form");
            $form.toggleClass("dbk-filters-collapsed");

            updateQuickFiltersToggle();
        });

        function updateQuickFiltersToggle() {
            var $form = $("#dadabik_quick_search_form");
            var $toggle = $("#hide_quick_filters_link");
            var collapsed = $form.hasClass("dbk-filters-collapsed");
            var activeFilters = 0;

            $form.find(':input')
                .not(':button, :submit, :reset, [type="hidden"]')
                .each(function () {
                    var name = this.name || "";
                    var value = $(this).val();

                    if (name.indexOf("__select_type") !== -1) {
                        return;
                    }

                    if (value !== null && String(value).trim() !== "") {
                        activeFilters++;
                    }
                });

            $toggle.attr("aria-expanded", collapsed ? "false" : "true");
            $toggle.toggleClass("is-open", !collapsed);

            if (activeFilters > 0) {
                $toggle.find(".dbk-filter-badge").text(activeFilters).show();
            }
            else {
                $toggle.find(".dbk-filter-badge").hide();
            }
        }

        $(document).ready(function () {
            updateQuickFiltersToggle();

            $("#dadabik_quick_search_form").on("change keyup", ":input", function () {
                updateQuickFiltersToggle();
            });
        });



<?php } else { ?>

$('#hide_quick_filters_link').on("click", function (e) {
            e.preventDefault();
            if ( $("#dadabik_quick_search_form").css('display') === 'none'){
                $("#dadabik_quick_search_form").css('display', 'block');
            }
            else{
                $("#dadabik_quick_search_form").css('display', 'none');
            }
	    });

<?php } ?>

	    


        

















        




    });

<?php if (isset($_POST['name_view']) && isset($_POST['sql_create_view']) ){ // display the create view form if the fields are set (i.e. if we are displaying the page after a post with ) ?>
	$("#create_new_view_form").css('display', 'block');
<?php } ?>
	$('#confirmation_message_container').on('click', '#error_message_close_link', function (e) {
		$("#error_message").css('display', 'none');
		e.preventDefault()
	});
	$('#confirmation_message_container').on('click', '#confirmation_message_close_link', function (e) {
		$("#confirmation_message").css('display', 'none');
		e.preventDefault()
	});
	$('#confirmation_message_container').on('click', '#alert_message_close_link', function (e) {
		$("#alert_message").css('display', 'none');
		e.preventDefault()
	});
	$('#confirmation_message_2_container').on('click', '#alert_message_2_close_link', function (e) {
		$("#alert_message_2").css('display', 'none');
		e.preventDefault()
	});
	$('#confirmation_message_3_container').on('click', '#alert_message_3_close_link', function (e) {
		$("#alert_message_3").css('display', 'none');
		e.preventDefault()
	});
	$('#confirmation_message_container_2').on('click', '#confirmation_message_2_close_link', function (e) {
		$("#confirmation_message_2").css('display', 'none');
		e.preventDefault()
	});



	// adjust diabled attribute for template_table when the page is loaded
	if ($('#enable_template_table_checkbox').is(':checked')){
		$('#template_table').prop('readonly', false);
        
	}
	else{
		$('#template_table').prop('readonly', true);
	}
	// adjust it again when the user click on the enable checkbox
	$("#enable_template_table_checkbox").click(function(e) {
		if ($('#enable_template_table_checkbox').is(':checked')){
			$('#template_table').prop('readonly', false);
		}
		else{
			$('#template_table').prop('readonly', true);

		}
	});
    
<?php if ($page_name === 'main'){ ?>
    // pivot table, [add another column] / [remove this column] links
	$('.add_remove_column').on("click", function (e) {
        // each link contains a target id and a function
	    id_container = $(this).data('targetid');
	    function_to_execute = $(this).data('function');

	    // show / hide the container div and enable/disable the first input field (the second is not important)
	    field_name_temp = 'group_by_operator_'+id_container;
	    //field_name_temp_2 = 'report_value_field_'+id_container;

	    if (function_to_execute === 'add'){
	        $('#column_container_'+id_container).show();
	        $("[name="+field_name_temp+"]").attr("disabled", false);
	    }
	    else if(function_to_execute === 'remove'){
	        $('#column_container_'+id_container).hide();
	        $("[name="+field_name_temp+"]").attr("disabled", true);

	    }

	    e.preventDefault();
	});

    left_menu_is_collapsed = 0;
<?php if (isset($_SESSION['left_menu_is_collapsed']) && $_SESSION['left_menu_is_collapsed'] === 1 || !isset($_SESSION['left_menu_is_collapsed']) && $show_collapsed_menu_by_default === 1){ ?>
    left_menu_is_collapsed = 1;
    document.getElementById('td_left_menu').classList.add("collapsed");
    document.getElementById('collapse_menu_link').style.display = 'none';
    document.getElementById('expand_menu_link').style.display = 'block';
    menu_item_texts = document.getElementsByClassName('menu_item_text');
    for (var i = 0; i < menu_item_texts.length; i++ ) {
        menu_item_texts[i].style.visibility = "hidden";
    }
<?php } ?>




    $('#collapse_menu_link').on("click", function (e) {
        document.getElementById('td_left_menu').classList.add("collapsed");
        document.getElementById('collapse_menu_link').style.display = 'none';
        document.getElementById('expand_menu_link').style.display = 'block';
        menu_item_texts = document.getElementsByClassName('menu_item_text');
        for (var i = 0; i < menu_item_texts.length; i++ ) {
            menu_item_texts[i].style.visibility = "hidden";
        }

        left_menu_is_collapsed = 1;

        $.ajax({
            url: "api_fe.php?function=set_left_menu_collapsed",
            data: ({
                left_menu_is_collapsed: left_menu_is_collapsed
            }),
            type: "POST",
            dataType: "json",
            success: function(data){
            },
            error: function(data, status, e){
                alert('unexpected error set_left_menu_collapsed ajax error'+JSON.stringify(data));
            }
        });

        e.preventDefault();
    });

    $('#td_left_menu').on("mouseenter", function (e) {
        if (left_menu_is_collapsed === 1){
            document.getElementById('td_left_menu').classList.remove("collapsed");
            menu_item_texts = document.getElementsByClassName('menu_item_text');
            for (var i = 0; i < menu_item_texts.length; i++ ) {
                //menu_item_texts[i].style.display = "inline";
                menu_item_texts[i].style.visibility = "visible";
            }
        }
    });

    $('#td_left_menu').on("mouseleave", function (e) {
        if (left_menu_is_collapsed === 1){
            document.getElementById('td_left_menu').classList.add("collapsed");
            menu_item_texts = document.getElementsByClassName('menu_item_text');
            for (var i = 0; i < menu_item_texts.length; i++ ) {
                //menu_item_texts[i].style.display = "none";
                menu_item_texts[i].style.visibility = "hidden";
            }
        }
    });

    $('#expand_menu_link').on("click", function (e) {
        document.getElementById('td_left_menu').classList.remove("collapsed");
        document.getElementById('collapse_menu_link').style.display = 'block';
        document.getElementById('expand_menu_link').style.display = 'none';
        menu_item_texts = document.getElementsByClassName('menu_item_text');
        for (var i = 0; i < menu_item_texts.length; i++ ) {
            //menu_item_texts[i].style.display = "inline";

            menu_item_texts[i].style.visibility = "visible";
        }
        left_menu_is_collapsed = 0;

        $.ajax({
            url: "api_fe.php?function=set_left_menu_collapsed",
            data: ({
                left_menu_is_collapsed: left_menu_is_collapsed
            }),
            type: "POST",
            dataType: "json",
            success: function(data){
            },
            error: function(data, status, e){
                alert('unexpected error set_left_menu_collapsed ajax error'+JSON.stringify(data));
            }
        });
        e.preventDefault();
    });

<?php } ?>

// DEV AREA, permissions manager, after having selected table and group
<?php if ($page_name === 'permissions_manager' && $function === 'configure' && isset($object)  && isset($subject)){ ?>



$(document).ready(function() {
    updateValueInput(1);
})

<?php } ?>

<?php if ($page_name === 'interface_configurator' && $enable_formula_field_autocompletion === 1){ ?>

    var available_tokens = <?= get_available_formula_tokens($installed_tables_ar, $table_name) ?>;
    var current_suggestions = []; // Array to store current suggestions

    // Extract the current token based on the cursor position
    function extract_current_token(input_element) {
        var cursor_position = input_element.selectionStart; // Get the cursor position
        var text_before_cursor = input_element.value.substring(0, cursor_position); // Text before the cursor
        var tokens = text_before_cursor.split(/\s+/); // Split by spaces
        return tokens.pop(); // Get the token where the cursor is
    }

    $("#formula_field").autocomplete({
        source: function(request, response) {
            var term = extract_current_token(this.element[0]); // Get the current token based on the cursor position

            // Show suggestions only if the term has at least one character
            if (term.length > 0) {
                var suggestions = $.grep(available_tokens, function(value) {
                    return value.toLowerCase().indexOf(term.toLowerCase()) === 0;
                });
                current_suggestions = suggestions; // Store the current suggestions
                response(suggestions);
            } else {
                response([]); // Empty response if no term
            }
        },
        focus: function() {
            // Prevent the value from being inserted on focus
            return false;
        },
        select: function(event, ui) {
            var input_element = this;
            var cursor_position = input_element.selectionStart; // Get the cursor position
            var text_before_cursor = input_element.value.substring(0, cursor_position); // Text before cursor
            var text_after_cursor = input_element.value.substring(cursor_position); // Text after cursor
            var tokens = text_before_cursor.split(/\s+/);
            tokens.pop(); // Remove the current token before the cursor
            tokens.push(ui.item.value); // Add the selected suggestion
            tokens.push(""); // add a new, empty, token

            // Save current cursor position relative to the inserted text
            var new_cursor_position = tokens.join(" ").length; // The new cursor position after the insertion

            // Update the input value
            input_element.value = tokens.join(" ") + text_after_cursor; 

            input_element.setSelectionRange(new_cursor_position, new_cursor_position);

            return false; // Prevent default behavior
        }
    });

    // Add Tab key functionality to autocomplete current token
    $("#formula_field").on("keydown", function(event) {
        if (event.key === "Tab" && $(this).autocomplete("widget").is(":visible")) {
            event.preventDefault(); // Prevent default Tab behavior
            var term = extract_current_token(this); // Get the current token based on the cursor position

            // Use the first suggestion from the stored current suggestions
            if (current_suggestions.length > 0) {
                var selected_item = current_suggestions[0]; // Get the first suggestion
                var cursor_position = this.selectionStart; // Get the cursor position
                var text_before_cursor = this.value.substring(0, cursor_position); // Text before the cursor
                var text_after_cursor = this.value.substring(cursor_position); // Text after cursor
                var tokens = text_before_cursor.split(/\s+/);
                tokens.pop(); // Remove the current token
                tokens.push(selected_item); // Add the selected suggestion
                tokens.push(""); 

                // Save current cursor position relative to the inserted text
                var new_cursor_position = tokens.join(" ").length; // The new cursor position after the insertion

                // Update the input value
                this.value = tokens.join(" ") + text_after_cursor; 

                this.setSelectionRange(new_cursor_position, new_cursor_position);
            }
        }
    });
    
<?php } ?>

<?php if ($page_name === 'interface_configurator'){ ?>

$("#add_linked_field_link").click(function(e) {

// increment the hidden number_linked_fields and add a new listbox
var current_number_linked_fields = Number($("#number_linked_fields").val());

var field_position = $("#field_position").val(); // from hidden field that stores the value of the field_position in the table (0, 1, 2, ...)

// get the value of the last linked fields listbox (we need it to copy and paste it)
var last_linked_fields_select_content = $("#linked_fields_field_"+ (current_number_linked_fields-1)).html();

// increment the hidden number_linked_fields
$("#number_linked_fields").val( current_number_linked_fields+1  );

// paste the copied listbox
$("#linked_fields_field_container").append(' <select id="linked_fields_field_'+current_number_linked_fields+'" name="linked_fields_field_'+field_position+'_'+current_number_linked_fields+'" >'+last_linked_fields_select_content+'</select>');

e.preventDefault();
});

$("#remove_linked_field_link").click(function(e) {

// decrement the hidden number_linked_fields and remove the last linked fields listbox
var current_number_linked_fields = Number($("#number_linked_fields").val());

if (current_number_linked_fields !== 1){

var field_position = $("#field_position").val(); // from hidden field that stores the value of the field_position in the table (0, 1, 2, ...)

// remove the last linked fields listbox
$("#linked_fields_field_"+ (current_number_linked_fields-1)).remove();

// decrement the hidden number_linked_fields
$("#number_linked_fields").val( current_number_linked_fields-1  );

}

e.preventDefault();
});

$("#add_master_details").click(function(e) {

// increment the hidden number_master_details and add a new listbox
var current_number_master_details = Number($("#number_master_details").val());

var field_position = $("#field_position").val(); // from hidden field that stores the value of the field_position in the table (0, 1, 2, ...)

// get the value of the last listboxes (we need it to copy and paste it)
var last_items_table_fk_field_names_select_content = $("#items_table_fk_field_names_field_"+ (current_number_master_details-1)).html();
var last_items_table_names_field_select_content = $("#items_table_names_field_"+ (current_number_master_details-1)).html();

// increment the hidden number_master_details
$("#number_master_details").val( current_number_master_details+1  );

// paste the copied listbox
$("#items_table_fk_field_names_field_container").append(' <select id="items_table_fk_field_names_field_'+current_number_master_details+'" name="items_table_fk_field_names_field_'+field_position+'_'+current_number_master_details+'" >'+last_items_table_fk_field_names_select_content+'</select>');

$("#items_table_names_field_container").append(' <select id="items_table_names_field_'+current_number_master_details+'" name="items_table_names_field_'+field_position+'_'+current_number_master_details+'"  onchange="refresh_items_table_fk_field_names(this.value, '+current_number_master_details+')">'+last_items_table_names_field_select_content+'</select>');

var items_table_names_field_width = ($('#items_table_names_field_'+current_number_master_details).css("width"));

$('#items_table_fk_field_names_field_'+current_number_master_details).css("width", items_table_names_field_width);

e.preventDefault();
});

$("#remove_master_details").click(function(e) {

// decrement the hidden number_master_details and remove the last linked fields listbox
var current_number_master_details = Number($("#number_master_details").val());

if (current_number_master_details !== 1){

var field_position = $("#field_position").val(); // from hidden field that stores the value of the field_position in the table (0, 1, 2, ...)

// remove the last listbox
$("#items_table_fk_field_names_field_"+ (current_number_master_details-1)).remove();
$("#items_table_names_field_"+ (current_number_master_details-1)).remove();

// decrement the hidden number_master_details
$("#number_master_details").val( current_number_master_details-1  );

}

e.preventDefault();
});

<?php } ?>

	$("#craete_new_view_link").click(function(e) {

		if ($("#create_new_view_form").css('display') === 'block'){
			$("#create_new_view_form").css('display', 'none');
		}
		else{
			$("#create_new_view_form").css('display', 'block');

		}
		e.preventDefault();
	});

	///////////////////////////////////////////////////////////////////////////////
	// DELETE FIELD
	$(document).on('click', ".delete_field_button", function (e) {

	    id_field_container = '#container_'+(this.id);
	    tablename_field_deletion = $(id_field_container).data('tablename');
	    fieldname_field_deletion = $(id_field_container).data('fieldname');

	    reload_page = 0;

	    var error = '';

         // here the code to check errors, if any
<?php if ($enable_data_tab_operations === 0){ ?>
        error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{

            if(typeof fieldname_field_deletion === 'undefined'){ // the field has been added without saving, you don't have the fieldname yet, just remove it
                if (confirm("Are you sure you want to DELETE this field?\n\nIf you have added / modified fields, save your changes before deleting this field, otherwise the changes will be lost.")){
                    $(id_field_container).remove();
                    // same code after
                    // need to reload the page otherwise there is a hole in the indexes
                    // don't like this solution, tofix
                    // window.location.href + return false was the only working solution for chrome
                    window.location.href = 'data.php?tablename=' + encodeURI(tablename_field_deletion) + '#anchor_' + encodeURI(tablename_field_deletion);
                    return false;
                    // also need to reload otherwise the browser just move to the anchor without loading the page
                    //window.location.reload(true);
                }
            }
            else{
                enable_disable_loader('enable');
                if (!confirm("Are you sure you want to DELETE this field? All the related data and DaDaBIK configuration/permissions about the field "+fieldname_field_deletion+" (table "+tablename_field_deletion+") will be erased.\n\nIf you have added / modified fields, save your changes before deleting this field, otherwise the changes will be lost.")){
                    enable_disable_loader('disable');
                }
                else{

                    $('#confirmation_message_container').html('');
                    $.ajax({
                        url: "api.php?function=delete_field",
                        data: ({
                        tablename_field_deletion: tablename_field_deletion,
                        fieldname_field_deletion: fieldname_field_deletion
                        }),

                        type: "POST",
                        dataType: "json",
                        success: function(data){

                            enable_disable_loader('disable');

                            if (data.status === 'ok'){
                                if (data.result !== 'done'){
                                    alert('unexpected error delete_field.submit - ' + data.result+ ' - '+data.error_message);
                                }
                                else{
                                     $("body").addClass("showing_confirmation_message");
                                     window.setTimeout( remove_showing_confirmation_message_class, 1000);

                                    $(id_field_container).remove();

                                    // same code before
                                    // need to reload the page otherwise there is a hole in the indexes
                                    // don't like this solution, tofix
                                    // window.location.href + return false was the only working solution for chrome
                                    window.location.href = 'data.php?tablename=' + encodeURI(tablename_field_deletion) + '#anchor_' + encodeURI(tablename_field_deletion);
                                    return false;

                                    // also need to reload otherwise the browser just move to the anchor without loading the page
                                    //window.location.reload(true);
                                }
                            }
                            else{
                                alert('unexpected error delete_field.submit status not OK');
                            }
                        },
                        error: function(data, status, e){
                            enable_disable_loader('disable');
                            alert('unexpected error delete_field.submit ajax error'+JSON.stringify(data));
                        }
                    });
                }
            }
        }

		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

<?php
if ($page_name === 'data'){
?>

	///////////////////////////////////////////////////////////////////////////////
	// ADD FIELD
	$('.add_field_button').on("click", function (e) {


	    alter_form_id = $(this).parents("form").attr("id");
	    tablename = $('#'+alter_form_id).find('input[name="tablename_to_alter"]').val();
        var error = '';

        // here the code to check errors, if any
<?php if ($enable_data_tab_operations === 0){ ?>
        error = '<?php echo $error_data_disabled; ?>';
<?php } ?>
        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{

            // search for the next index to use, if there are 2 fields already, it will be 2 (indexes start from 0)
            found = 0;
            i=0;
            while (found === 0){
                if ( $('#container_' + tablename + '_' + i).length ){
                    i++;
                }
                else{
                    found = 1;
                }
            }
<?php
if ($config['data_types'] === 'simple'){
?>
            // code repeated below, to fix
            // append the new field after the last one (i-1)
            $('#container_'+tablename + '_' + (i-1)).after('<div id="container_' + tablename + '_' + i + '"  data-tablename="' + tablename +'"><p><input type="text" style="width:150px" name="name_field_'  +i+ '" > <select style="width:150px" name="type_field_' + i + '"><option value=""></option><?php
            foreach($simple_field_types[$dbms_type] as $key => $value){
                echo '<option value="'.htmlspecialchars($key).'" >'.htmlspecialchars($value).'</option>';
            }?></select> <select style="width:100px"  type="text" name="null_field_' + i + '"><option value="0">No</option><option value="1" <?= $data_add_field_default_nullable === 1 ? 'selected' : '' ?>>Yes</option></select> <select style="width:150px" type="text" name="pk_field_' + i + '"><option value="0"></option><option value="1" >Primary Key</option></select> <select style="width:180px"  type="text" name="extra_field_' + i + '"><option value=""></option><?php
            foreach($autoincrement_options_ar as $autoincrement_options_ar_el){
                echo '<option value="'.$autoincrement_options_ar_el.'" >'.$autoincrement_options_ar_el.'</option>';
            }?></select> <span style="width:150px;display:inline-block"><i>No</i></span> <input class="button_admin btn btn-primary btn-sm delete_field_button" id="' + tablename + '_' + i + '" type="button" value="DELETE FIELD"></div>');
<?php
} else{
?>
            // code repeated above, to fix
            // append the new field after the last one (i-1)
            $('#container_'+tablename + '_' + (i-1)).after('<div id="container_' + tablename + '_' + i + '"  data-tablename="' + tablename +'"><p><input type="text" style="width:150px" name="name_field_'  +i+ '" > <input style="width:150px"  type="text" name="type_field_' + i + '" > <select style="width:100px"  type="text" name="null_field_' + i + '"><option value="0">No</option><option value="1" <?= $data_add_field_default_nullable === 1 ? 'selected' : '' ?>>Yes</option></select> <select style="width:150px" type="text" name="pk_field_' + i + '"><option value="0"></option><option value="1" >Primary Key</option></select> <select style="width:180px"  type="text" name="extra_field_' + i + '"><option value=""></option><?php
            foreach($autoincrement_options_ar as $autoincrement_options_ar_el){
                echo '<option value="'.$autoincrement_options_ar_el.'" >'.$autoincrement_options_ar_el.'</option>';
            }?></select> <span style="width:150px;display:inline-block"><i>No</i></span> <input class="button_admin btn btn-primary btn-sm delete_field_button" id="' + tablename + '_' + i + '" type="button" value="DELETE FIELD"></div>');
<?php
}
?>
        }
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	///////////////////////////////////////////////////////////////////////////////
	// DROP TABLE
	$('form[id^="drop_table_form"]').submit(function(e) {

	    enable_disable_loader('enable');
	    tablename_to_drop = $(this).find('input[name="tablename_to_drop"]').val();
	    table_type_to_drop = $(this).find('input[name="table_type_to_drop"]').val();

		var error = '';

		// here the code to check errors, if any
<?php if ($enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{
		    if (!confirm("Are you sure you want to DROP the table/view? All the data and DaDaBIK configuration/permissions about "+tablename_to_drop+" will be erased.\n\nIf you have used this table/view as a source for a lookup field or as an items table please remove those references before dropping the table/view.")){
		        enable_disable_loader('disable');
		    }
		    else{

                $('#confirmation_message_container').html('');
                $.ajax({
                    url: "api.php?function=drop_table",

                    data: ({
                    tablename_to_drop: tablename_to_drop,
                    table_type_to_drop: table_type_to_drop,
                    } ),

                    type: "POST",
                    dataType: "json",
                    success: function(data){

                        enable_disable_loader('disable');

                        if (data.status === 'ok'){
                            if (data.result !== 'done'){
                                alert('unexpected error drop_table_form.submit - ' + data.result+ ' - '+data.error_message);
                            }
                            else{
                                 $("body").addClass("showing_confirmation_message");
                                 window.setTimeout( remove_showing_confirmation_message_class, 1000);
                                 $("#container_"+tablename_to_drop).remove();
                            }
                        }
                        else{
                            alert('unexpected error drop_table_form.submit status not OK');
                        }
                    },
                    error: function(data, status, e){
                        enable_disable_loader('disable');
                        alert('unexpected error drop_table_form.submit ajax error'+JSON.stringify(data));
                    }
                });
            }
		}

		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	///////////////////////////////////////////////////////////////////////////////
	// ALTER TABLE
	$('form[id^="alter_table_form"]').submit(function(e) {

	    enable_disable_loader('enable');
	    tablename_to_alter = $(this).find('input[name="tablename_to_alter"]').val();

	    id_form = this.id;

		var error = '';

		// here the code to check errors, if any
<?php if ($enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{

            $('#confirmation_message_container').html('');

            $.ajax({
                url: "api.php?function=get_fields_list",

                data: ({
                table_name: tablename_to_alter,

                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error alter_table_form.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            //$("body").addClass("showing_confirmation_message");
                            //window.setTimeout( remove_showing_confirmation_message_class, 1000);

                            name_field_new = [];
                            type_field_new = [];
                            null_field_new = [];
                            pk_field_new = [];
                            extra_field_new = [];

                            fields_add_ar = [];
                            fields_modify_ar = [];

                            name_field_current = data['fields_ar']['name'];
                            type_field_current = data['fields_ar']['type'];
                            null_field_current = data['fields_ar']['null'];
                            pk_field_current = data['fields_ar']['pk'];
                            extra_field_current = data['fields_ar']['extra'];

                            // loop through the fields of the form
                            form_is_over = false;
                            i=0;
                            cnt_modify=0;
                            cnt_add=0;
                            drop_pk_needed = 0;
                            pk_changed = 0;
                            required_error = 0;
                            while (form_is_over === false){

                                // name_field
                                field_name_temp = "name_field_" + i;

                                // doesn't exist or it's blank
                                if ( $('#'+id_form).find('input[name="'+field_name_temp+'"]').val()){

                                    name_field_new[i] = $('#'+id_form).find('input[name="'+field_name_temp+'"]').val();

                                    field_name_temp = "type_field_" + i;

<?php
if ($config['data_types'] === 'simple'){
?>
                                    type_field_new[i] = $('#'+id_form).find('select[name="'+field_name_temp+'"]').val();
<?php
} else{
?>
                                    type_field_new[i] = $('#'+id_form).find('input[name="'+field_name_temp+'"]').val();
<?php
}
?>

                                    if (  type_field_new[i] === '' ){
                                        alert('You have to specify the field type.');
                                        required_error = 1;
                                        form_is_over = true;
                                    }
                                    else{

                                        field_name_temp = "null_field_" + i;
                                        null_field_new[i] = parseInt($('#'+id_form).find('select[name="'+field_name_temp+'"]').val());

                                        field_name_temp = "pk_field_" + i;
                                        pk_field_new[i] = parseInt($('#'+id_form).find('select[name="'+field_name_temp+'"]').val());

                                        field_name_temp = "extra_field_" + i;
                                        extra_field_new[i] = $('#'+id_form).find('select[name="'+field_name_temp+'"]').val();

                                        if ( name_field_current[i] ){ // field already exists

                                            if (name_field_new[i] !== name_field_current[i] || type_field_new[i] !== type_field_current[i] || null_field_new[i] !== null_field_current[i] || pk_field_new[i] !== pk_field_current[i]  || extra_field_new[i] !== extra_field_current[i] ){ // something has changed

                                                fields_modify_ar[cnt_modify] = {};
                                                fields_modify_ar[cnt_modify]['name_field'] = name_field_current[i];
                                                fields_modify_ar[cnt_modify]['name_field_new'] = name_field_new[i];
                                                fields_modify_ar[cnt_modify]['type_field'] = type_field_new[i];
                                                fields_modify_ar[cnt_modify]['null_field'] = null_field_new[i];
                                                fields_modify_ar[cnt_modify]['pk_field'] = pk_field_new[i];
                                                fields_modify_ar[cnt_modify]['extra_field_new'] = extra_field_new[i];
                                                fields_modify_ar[cnt_modify]['extra_field'] = extra_field_current[i];
                                                fields_modify_ar[cnt_modify]['index_form_field'] = i;


                                                // pk has changed
                                                if (pk_field_new[i] !== pk_field_current[i] ){
                                                    pk_changed = 1;

                                                    // pk has been removed
                                                    if (pk_field_current[i] === 1){
                                                        drop_pk_needed = 1;
                                                    }
                                                }
                                                cnt_modify++;
                                            }
                                        }
                                        else{
                                            // add column
                                            fields_add_ar[cnt_add] = {};

                                            fields_add_ar[cnt_add]['name_field_new'] = name_field_new[i];
                                            fields_add_ar[cnt_add]['type_field'] = type_field_new[i];
                                            fields_add_ar[cnt_add]['null_field'] = null_field_new[i];
                                            fields_add_ar[cnt_add]['pk_field'] = pk_field_new[i];
                                            fields_add_ar[cnt_add]['extra_field_new'] = extra_field_new[i];
                                            fields_add_ar[cnt_add]['index_form_field'] = i;
                                            cnt_add++;
                                        }
                                        i++;
                                    }
                                }
                                else{
                                    form_is_over = true;
                                }
                            }

                            if (required_error === 0){

                                $.ajax({
                                    url: "api.php?function=alter_table",

                                    data: ({
                                    tablename_to_alter: tablename_to_alter,
                                    fields_add_ar: fields_add_ar,
                                    fields_modify_ar: fields_modify_ar,
                                    drop_pk_needed: drop_pk_needed,
                                    pk_changed: pk_changed
                                    } ),

                                    type: "POST",
                                    dataType: "json",
                                    success: function(data){

                                        enable_disable_loader('disable');

                                        if (data.status === 'ok'){
                                            if (data.result !== 'done'){
                                                alert('unexpected error alter_table_form.submit - ' + data.result+ ' - '+data.error_message);
                                            }
                                            else{
                                                // for each new field added, add the data-fieldname attribute
                                                fields_add_ar.forEach(function(fields_add_ar_el) {
                                                    index_temp = fields_add_ar_el['index_form_field'];

                                                    $('#container_'+tablename_to_alter + '_' + index_temp).attr('data-fieldname', fields_add_ar_el['name_field_new']);
                                                });

                                                // for each renamed field added, modify the data-fieldname attribute
                                                //
                                                fields_modify_ar.forEach(function(fields_modify_ar_el) {
                                                    index_temp = fields_modify_ar_el['index_form_field'];

                                                    if (fields_modify_ar_el['name_field_new'] !== fields_modify_ar_el['name_field']){

                                                        $('#container_'+tablename_to_alter + '_' + index_temp).attr('data-fieldname', fields_modify_ar_el['name_field_new']);
                                                    }
                                                });

                                                $("body").addClass("showing_confirmation_message");
                                                window.setTimeout( remove_showing_confirmation_message_class, 1000);
                                            }
                                        }
                                        else{
                                            alert('unexpected error drop_table_form.submit status not OK');
                                        }
                                    },
                                    error: function(data, status, e){
                                        enable_disable_loader('disable');
                                        alert('unexpected error drop_table_form.submit ajax error'+JSON.stringify(data));
                                    }
                                });
                            }
                        }
                    }
                    else{
                        alert('unexpected error alter_table_form.submit status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error alter_table_form.submit ajax error'+JSON.stringify(data));
                }
            });
		}
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	// DATA SETTINGS, SAVE
	$('form[id="data_settings_form"]').submit(function(e) {

	    enable_disable_loader('enable');
	    install_enable_tables_on_creation = $(this).find('input[name="install_enable_tables_on_creation"]:checked').val();
	    data_types = $(this).find('input[name="data_types"]:checked').val();

	    id_form = this.id;

		var error = '';

		// here the code to check errors, if any
<?php if ($enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{

            $('#confirmation_message_container').html('');

            $.ajax({
                url: "api.php?function=save_data_settings",

                data: ({
                install_enable_tables_on_creation: install_enable_tables_on_creation,
                data_types: data_types
                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error save_data_settings.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        }
                    }
                    else{
                        alert('unexpected error save_data_settings.submit status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error save_data_settings.submit ajax error'+JSON.stringify(data));
                }
            });
		}
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

<?php } // end if page === 'data'?>

<?php
if ($page_name === 'admin' || $page_name === 'main'){
?>

// CHANGE DEV MODE from admin
$('form[id="change_dev_mode_form"]').submit(function(e) {

    enable_disable_loader('enable');
    dev_mode = $(this).find('input[name="dev_mode"]:checked').val();

    reload_after_change = $(this).find('input[name="reload_after_change"]').val();

    id_form = this.id;

    var error = '';

    if (error !== ''){
         window.scrollTo(0,0);
         $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
         enable_disable_loader('disable');
    }
    else{

        $('#confirmation_message_container').html('');

        $.ajax({
            url: "api_fe.php?function=change_dev_mode",

            data: ({
            dev_mode: dev_mode
            } ),

            type: "POST",
            dataType: "json",
            success: function(data){

                enable_disable_loader('disable');

                if (data.status === 'ok'){
                    if (data.result !== 'done'){
                        if (data.result === 'demo_error'){
                            alert(data.error_message);
                        }
                        else{
                            alert('unexpected error change_dev_mode.submit - ' + data.result+ ' - '+data.error_message);
                        }
                    }
                    else{
                        $("body").addClass("showing_confirmation_message");
                        window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        $("#current_dev_mode_container").html(dev_mode);

                        if (dev_mode == 'live'){
                            //bgcolor_temp = '#b3c150';
                            bgcolor_temp = '#848792';
                            $("#btn_change_dev_mode").addClass('btn-success');
                            $("#btn_change_dev_mode").removeClass('btn-secondary');
                            
                        }
                        else{
                            //bgcolor_temp = '#F1EB90';
                            bgcolor_temp = '#068757';
                            $("#btn_change_dev_mode").addClass('btn-secondary');
                            $("#btn_change_dev_mode").removeClass('btn-success');
                        }
                        
                        
                        if(document.getElementById("current_dev_mode_td") !== null){ // admin area
                            document.getElementById('current_dev_mode_td').bgColor = bgcolor_temp;
                        }


                        if (reload_after_change == 1){
                            window.location.replace("index.php");
                        }
                    }
                }
                else{
                    alert('unexpected error change_dev_mode.submit status not OK');
                }
            },
            error: function(data, status, e){
                enable_disable_loader('disable');
                alert('unexpected error change_dev_mode.submit ajax error'+JSON.stringify(data));
            }
        });
    }
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

// CHANGE DEV MODE from frontend
$('#dev_mode').change(function(e) {

enable_disable_loader('enable');
dev_mode = 'live';
if (document.getElementById("dev_mode").checked == true){
    dev_mode = 'beta';
}

var error = '';

if (error !== ''){
     window.scrollTo(0,0);
     $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
     enable_disable_loader('disable');
}
else{

    $('#confirmation_message_container').html('');

    $.ajax({
        url: "api_fe.php?function=change_dev_mode",

        data: ({
        dev_mode: dev_mode
        } ),

        type: "POST",
        dataType: "json",
        success: function(data){

            enable_disable_loader('disable');

            if (data.status === 'ok'){
                if (data.result !== 'done'){

                    if (data.result === 'demo_error'){
                            alert(data.error_message);
                        }
                    else{
                        alert('unexpected error change_dev_mode.submit - ' + data.result+ ' - '+data.error_message);
                    }
                }
                else{
                    $("body").addClass("showing_confirmation_message");
                    window.setTimeout( remove_showing_confirmation_message_class, 1000);

                    window.location.replace("index.php");
                }
            }
            else{
                alert('unexpected error change_dev_mode.submit status not OK');
            }
        },
        error: function(data, status, e){
            enable_disable_loader('disable');
            alert('unexpected error change_dev_mode.submit ajax error'+JSON.stringify(data));
        }
    });
}
e.preventDefault(); // avoid to execute the actual submit of the form.
});

// PUSH BETA LIVE
$('form[id="push_form"]').submit(function(e) {

    if (confirm('Do you want to Publish your BETA version to LIVE? Your LIVE version will be overwritten.\n\nMake sure that no one (including automation/cron scripts) other than you is using this application and that there are no pending operations on the application to complete (e.g. a script started and not still completed).')){

        enable_disable_loader('enable');
        comment_push = $(this).find('[name="comment_push"]').val();

        var error = '';

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{

            $('#confirmation_message_container').html('');

            $.ajax({
                url: "api.php?function=push_beta_live",

                data: ({
                comment_push: comment_push
                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error push_beta_live.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                            window.location.reload(true);
                        }
                    }
                    else{
                        alert('unexpected error push_beta_live.submit status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error push_beta_live.submit ajax error'+JSON.stringify(data));
                }
            });
        }
    }
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

// PULL LIVE BETA
$('form[id="pull_form"]').submit(function(e) {

    if (confirm('Do you want to Pull your LIVE version? Your BETA version will be overwritten.')){
        enable_disable_loader('enable');

        var error = '';

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{

            $('#confirmation_message_container').html('');

            $.ajax({
                url: "api.php?function=pull_live_beta",

                /*data: ({
                comment_push: comment_push
                } ),*/

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error pull_live_beta.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        }
                    }
                    else{
                        alert('unexpected error pull_live_beta.submit status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error pull_live_beta.submit ajax error'+JSON.stringify(data));
                }
            });
        }
    }
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

$('form[id="set_beta_users_form"]').submit(function(e) {

    enable_disable_loader('enable');
    beta_users = $(this).find('input[name="beta_users"]').val();

    var error = '';

    if (error !== ''){
         window.scrollTo(0,0);
         $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
         enable_disable_loader('disable');
    }
    else{

        $('#confirmation_message_container').html('');

        $.ajax({
            url: "api.php?function=set_beta_users",

            data: ({
            beta_users: beta_users
            } ),

            type: "POST",
            dataType: "json",
            success: function(data){

                enable_disable_loader('disable');

                if (data.status === 'ok'){
                    if (data.result !== 'done'){
                        alert('unexpected error set_beta_users.submit - ' + data.result+ ' - '+data.error_message);
                    }
                    else{
                        $("body").addClass("showing_confirmation_message");
                        window.setTimeout( remove_showing_confirmation_message_class, 1000);
                    }
                }
                else{
                    alert('unexpected error set_beta_users.submit status not OK');
                }
            },
            error: function(data, status, e){
                enable_disable_loader('disable');
                alert('unexpected error set_beta_users.submit ajax error'+JSON.stringify(data));
            }
        });
    }
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

<?php } // end if page === 'admin'?>

<?php
if ($page_name === 'permissions_manager'){
?>

    
    

    // PUBLIC USER, SAVE
	$('form[id="public_user_form"]').submit(function(e) {

	    enable_disable_loader('enable');

	    username_public_user =  $(this).find('select[name="username_public_user"] option:selected').val();

        hide_top_bar_public_user = $(this).find('input[name="hide_top_bar_public_user"]:checked').val();
        hide_advanced_features_public_user = $(this).find('input[name="hide_advanced_features_public_user"]:checked').val();
        hide_menu_public_user = $(this).find('input[name="hide_menu_public_user"]:checked').val();

	    id_form = this.id;

		var error = '';

		// here the PHP code to check errors, if any
<?php if (false && $enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{

            $('#confirmation_message_container').html('');


            $.ajax({
                url: "api.php?function=save_public_user",

                data: ({
                    username_public_user: username_public_user,
                    hide_top_bar_public_user: hide_top_bar_public_user,
                    hide_advanced_features_public_user: hide_advanced_features_public_user,
                    hide_menu_public_user: hide_menu_public_user
                }),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');


                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error save_public_user.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        }
                    }
                    else{
                        alert('unexpected error save_public_user.submit status not OK');
                    }

                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error save_public_user.submit ajax error'+JSON.stringify(data));
                }
            });

		}
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});



<?php } // end if page === 'permissions_manager'?>


<?php
if ($page_name === 'datagrid_configurator'){
?>

    
    // LAYOUT BASIC SETTINGS, SAVE
	$('form[id="basic_settings_form"]').submit(function(e) {

	    enable_disable_loader('enable');



	    logo_img =  $(this).find('input[name="logo_img"]').val();

        // set to false if the delete checkbox is not there
        delete_logo_uploaded = $(this).find('input[name="delete_logo_uploaded"]').length ? $(this).find('input[name="delete_logo_uploaded"]').is(':checked') : false;

        // convert true / false to 1 / 0
        delete_logo_uploaded = delete_logo_uploaded ? 1 : 0;

        font =  $(this).find('select[name="font"] option:selected').val();

        title_application =  $(this).find('input[name="title_application"]').val();
        theme =  $(this).find('select[name="theme"] option:selected').val();
        grid_layout_scrolling =  $(this).find('select[name="grid_layout_scrolling"] option:selected').val();
        menu_type =  $(this).find('select[name="menu_type"] option:selected').val();
        results_grid_fixed_header = $(this).find('input[name="results_grid_fixed_header"]:checked').val();
        dont_show_menu_if_only_one_item = $(this).find('input[name="dont_show_menu_if_only_one_item"]:checked').val();
        results_display_mode_menu =  $(this).find('select[name="results_display_mode_menu"] option:selected').val();
	    maxlength_grid =  $(this).find('input[name="maxlength_grid"]').val();
        hide_menu = $(this).find('input[name="hide_menu"]:checked').val();
        hide_top_bar = $(this).find('input[name="hide_top_bar"]:checked').val();


	    left_menu_bgcolor =  $(this).find('input[name="left_menu_bgcolor"]').val();
	    left_menu_text_color =  $(this).find('input[name="left_menu_text_color"]').val();
	    standard_buttons_bgcolor =  $(this).find('input[name="standard_buttons_bgcolor"]').val();
	    standard_buttons_text_color =  $(this).find('input[name="standard_buttons_text_color"]').val();
	    custom_buttons_bgcolor =  $(this).find('input[name="custom_buttons_bgcolor"]').val();
	    custom_buttons_text_color =  $(this).find('input[name="custom_buttons_text_color"]').val();
	    danger_buttons_bgcolor =  $(this).find('input[name="danger_buttons_bgcolor"]').val();
	    results_grid_header_bgcolor =  $(this).find('input[name="results_grid_header_bgcolor"]').val();
	    top_bar_bgcolor =  $(this).find('input[name="top_bar_bgcolor"]').val();
	    edit_icon_color =  $(this).find('input[name="edit_icon_color"]').val();
	    delete_icon_color =  $(this).find('input[name="delete_icon_color"]').val();
	    details_icon_color =  $(this).find('input[name="details_icon_color"]').val();

	    id_form = this.id;

		var error = '';

		// here the code to check errors, if any
<?php if (false && $enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{

            $('#confirmation_message_container').html('');


            $.ajax({
                url: "api.php?function=save_basic_settings",
                
                data: ({
                logo_img: logo_img,
                delete_logo_uploaded: delete_logo_uploaded,
                font: font,
                title_application: title_application,
                theme: theme,
                grid_layout_scrolling: grid_layout_scrolling,
                results_grid_fixed_header: results_grid_fixed_header,
                menu_type: menu_type,
                dont_show_menu_if_only_one_item: dont_show_menu_if_only_one_item,
                results_display_mode_menu: results_display_mode_menu,
                maxlength_grid: maxlength_grid,
                left_menu_bgcolor: left_menu_bgcolor,
                left_menu_text_color: left_menu_text_color,
                standard_buttons_bgcolor: standard_buttons_bgcolor,
                standard_buttons_text_color: standard_buttons_text_color,
                custom_buttons_bgcolor: custom_buttons_bgcolor,
                custom_buttons_text_color: custom_buttons_text_color,
                danger_buttons_bgcolor: danger_buttons_bgcolor,
                results_grid_header_bgcolor: results_grid_header_bgcolor,
                hide_menu: hide_menu,
                hide_top_bar: hide_top_bar,
                top_bar_bgcolor: top_bar_bgcolor,
                edit_icon_color: edit_icon_color,
                delete_icon_color: delete_icon_color,
                details_icon_color: details_icon_color
                }),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');


                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error save_basic_settings.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");

                            if (data.logo_uploaded != ''){
                                
                                $("#logo_uploaded_preview").html('<img src="data:image/png;base64,' +  data.logo_uploaded + '" width="50"><br><input type="checkbox" name="delete_logo_uploaded" value="1"> Delete current uploaded logo');
                            }
                            else if (delete_logo_uploaded == 1){
                                $("#logo_uploaded_preview").html('');
                                
                            }
                   
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        }
                    }
                    else{
                        alert('unexpected error save_basic_settings.submit status not OK');
                    }

                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error save_basic_settings.submit ajax error'+JSON.stringify(data));
                }
            });

		}
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});


    // LAYOUT CUSTOM CSS, SAVE
	$('form[id="custom_css_form"]').submit(function(e) {

	    enable_disable_loader('enable');

	    custom_css =  $(this).find('textarea[name="custom_css"]').val();

	    id_form = this.id;

		var error = '';

		if (custom_css.length > 2000){
		    error = 'More than 2000 characters, size not allowed.'
		}

		// here the PHP code to check errors, if any
<?php if (false && $enable_data_tab_operations === 0){ ?>
		error = '<?php echo $error_data_disabled; ?>';
<?php } ?>

		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{

            $('#confirmation_message_container').html('');


            $.ajax({
                url: "api.php?function=save_custom_css",

                data: ({
                custom_css: custom_css
                }),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');


                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error save_custom_css.submit - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                            $("body").addClass("showing_confirmation_message");
                            window.setTimeout( remove_showing_confirmation_message_class, 1000);
                        }
                    }
                    else{
                        alert('unexpected error save_custom_css.submit status not OK');
                    }

                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error save_custom_css.submit ajax error'+JSON.stringify(data));
                }
            });

		}
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});



<?php } // end if page === 'datagrid_configurator'?>









    // STATUS SETTINGS, SAVE
	$('form[id="status_settings_form"]').submit(function(e) {

	    enable_disable_loader('enable');
	    status_installation = $( "#status_installation" ).val();
	    users_maintenance_installation = $( "#users_maintenance_installation" ).val();

		var error = '';

        $('#confirmation_message_container').html('');

        $.ajax({
            url: "api.php?function=save_status",

            data: ({
            status_installation: status_installation,
            users_maintenance_installation: users_maintenance_installation
            } ),

            type: "POST",
            dataType: "json",
            success: function(data){

                enable_disable_loader('disable');

                if (data.status === 'ok'){
                    if (data.result !== 'done'){
                        alert('unexpected error save_data_settings.submit - ' + data.result+ ' - '+data.error_message);
                    }
                    else{
                        $("body").addClass("showing_confirmation_message");
                        window.setTimeout( remove_showing_confirmation_message_class, 1000);
                    }
                }
                else{
                    alert('unexpected error save_data_settings.submit status not OK');
                }
            },
            error: function(data, status, e){
                enable_disable_loader('disable');
                alert('unexpected error save_data_settings.submit ajax error'+JSON.stringify(data));
            }
        });
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	$('[id^="<?php echo $select_checkbox_prefix; ?>"]').click(function(){
	    where_value = this.id.substr(<?php echo strlen_custom($select_checkbox_prefix); ?>);
<?php
if (isset($table_name)){
    echo 'table_name = "'.$table_name.'";';
}
?>
        if (this.checked === true){
             type = 'check';
        }
        else{
             type = 'uncheck';
        }

	    var error = '';

        // here the code to check errors, if any
<?php if ($enable_record_checkboxes === 0){ ?>
        error = 'Please set $enable_record_checkboxes to 1 in config.php and reload this page if you want to use the record checkboxes.';
<?php } ?>

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{
           $('#confirmation_message_container').html('');
            $.ajax({
                url: "api_fe.php?function=check_uncheck_row",

                data: ({
                    where_value: where_value,
                    tablename: table_name,
                    type: type,
                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error check_uncheck_row - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                             // don't need the confirmation
                             //$("body").addClass("showing_confirmation_message");
                             //window.setTimeout( remove_showing_confirmation_message_class, 1000);

                             $("#checked_ids_counter_container").html(data.counter);

                             if (data.counter === 0){
                                $("#checked_ids_container").hide();
                             }
                             else{
                                $("#checked_ids_container").show();
                            }
                        }
                    }
                    else{
                        alert('unexpected error check_uncheck_row status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error check_uncheck_row ajax error'+JSON.stringify(data));
                }
            });
        }
	});

	$('#uncheck_all_link').click(function(event){

<?php
if (isset($table_name)){
    echo 'table_name = "'.$table_name.'";';
}
?>
        enable_disable_loader('enable');
	    var error = '';

        // here the code to check errors, if any
<?php if ($enable_record_checkboxes === 0){ ?>
        error = 'Please set $enable_record_checkboxes to 1 in config.php and reload this page if you want to use the record checkboxes.';
<?php } ?>

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{
           $('#confirmation_message_container').html('');
            $.ajax({
                url: "api_fe.php?function=check_uncheck_row",

                data: ({
                    tablename: table_name,
                    type: 'uncheck_all',
                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error check_uncheck_row - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                             $("body").addClass("showing_confirmation_message");
                             window.setTimeout( remove_showing_confirmation_message_class, 1000);

                             $("#checked_ids_counter_container").html(data.counter);

                             $("#checked_ids_container").hide();

                             $('input[id^="<?php echo $select_checkbox_prefix; ?>"]').prop( "checked", false );

                        }
                    }
                    else{
                        alert('unexpected error check_uncheck_row status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error check_uncheck_row ajax error'+JSON.stringify(data));
                }
            });

        }
	    event.preventDefault();
	});


	$('#check_all_link').click(function(event){

<?php
if (isset($table_name)){
    echo 'table_name = "'.$table_name.'";';
}
?>
        enable_disable_loader('enable');
	    var error = '';

        // here the code to check errors, if any
<?php if ($enable_record_checkboxes === 0){ ?>
        error = 'Please set $enable_record_checkboxes to 1 in config.php and reload this page if you want to use the record checkboxes.';
<?php } ?>

        if (error !== ''){
             window.scrollTo(0,0);
             $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
             enable_disable_loader('disable');
        }
        else{
           $('#confirmation_message_container').html('');

           ids = [];

           $('input[id^="<?php echo $select_checkbox_prefix; ?>"]').each(function () {
                    ids.push(this.id.substr( <?php echo strlen_custom($select_checkbox_prefix); ?> ));
            });

            $.ajax({
                url: "api_fe.php?function=check_uncheck_row",

                data: ({
                    tablename: table_name,
                    type: 'check_all',
                    where_value: ids
                } ),

                type: "POST",
                dataType: "json",
                success: function(data){

                    enable_disable_loader('disable');

                    if (data.status === 'ok'){
                        if (data.result !== 'done'){
                            alert('unexpected error check_uncheck_row - check_all - ' + data.result+ ' - '+data.error_message);
                        }
                        else{
                             $("body").addClass("showing_confirmation_message");
                             window.setTimeout( remove_showing_confirmation_message_class, 1000);

                             $("#checked_ids_counter_container").html(data.counter);

                             $('input[id^="<?php echo $select_checkbox_prefix; ?>"]').prop( "checked", true );

                        }
                    }
                    else{
                        alert('unexpected error check_uncheck_row - check_all status not OK');
                    }
                },
                error: function(data, status, e){
                    enable_disable_loader('disable');
                    alert('unexpected error check_uncheck_row ajax error'+JSON.stringify(data));
                }
            });

        }
	    event.preventDefault();
	});

	$("#include_tables_form").submit(function(e) {
		var field_names_to_update = [];
		var field_values_to_update  = [];
		var where_values  = [];

		enable_disable_loader('enable');

		var number_items = $('#number_items').val();

		var pages_to_show = $('#pages_to_show').val()

		var error = '';

		if (pages_to_show === 'custom_pages'){

			var tablename_to_update = '<?php echo $GLOBALS["dadabik_static_pages_tab_name"]; ?>';

			for (i=0;i<number_items;i++){
				field_names_to_update[i] = [];
				field_values_to_update[i] = [];
				field_names_to_update[i][0] = 'link_static_page';
				field_values_to_update[i][0] =  $('#link_static_page_'+(i+1)).val();

				field_names_to_update[i][1] = 'enabled_static_page';
				if ($('#enabled_static_page_'+(i+1)).is(':checked')){
					field_values_to_update[i][1] =  '1';
				}
				else{
					field_values_to_update[i][1] =  '0';
				}

				field_names_to_update[i][2] = 'file_static_page';
				field_values_to_update[i][2] =  $('#file_static_page_'+(i+1)).val();

				field_names_to_update[i][3] = 'content_static_page';
				field_values_to_update[i][3] = tinyMCE.get('content_static_page_'+(i+1)).getContent();

				field_names_to_update[i][4] = 'menu_parent_page';
				field_values_to_update[i][4] =  $('#menu_parent_page_'+(i+1)).val();

				field_names_to_update[i][5] = 'is_homepage_static_page';

				if ($('#is_homepage_static_page_'+(i+1)).is(':checked')){
					field_values_to_update[i][5] =  'y';
				}
				else{
					field_values_to_update[i][5] =  'n';
				}

				field_names_to_update[i][6] = 'type_static_page';
				field_values_to_update[i][6] =  $('#type_static_page_'+(i+1)).val();

				field_names_to_update[i][7] = 'menu_order_static_page';
				field_values_to_update[i][7] =  $('#menu_order_static_page_'+(i+1)).val();

				field_names_to_update[i][8] = 'href_link_static_page';
				field_values_to_update[i][8] =  $('#href_link_static_page_'+(i+1)).val();

				field_names_to_update[i][9] = 'separator_before_static_page';
				if ($('#separator_before_static_page_'+(i+1)).is(':checked')){
					field_values_to_update[i][9] =  'y';
				}
				else{
					field_values_to_update[i][9] =  'n';
				}

                field_names_to_update[i][10] = 'icon_static_page';
                field_values_to_update[i][10] =  $('#menu_icon_page_'+(i+1)).val();

<?php
?>
<?php
?>

				if (field_values_to_update[i][6] === 'PHP' &&  field_values_to_update[i][2] === '') {
					error = 'If you choose PHP as <i>type</i>, you need to specify a PHP source file as well.';
					break;
				}
				else if (field_values_to_update[i][6] === 'html' &&  field_values_to_update[i][3] === '') {
					error = 'If you choose HTML as <i>type</i>, you need to specify the HTML content as well.';
					break;
				}
				else if (field_values_to_update[i][6] === 'link' &&  field_values_to_update[i][8] === '') {
					error = 'If you choose link as <i>type</i>, you need to specify the href link as well.';
					break;
				}

<?php
?>

				where_values[i] = $('#id_static_page_'+(i+1)).val();
			}
		}
		else{
			var tablename_to_update =  '<?php echo $table_list_name; ?>';

			for (i=0;i<number_items;i++){
				field_names_to_update[i] = [];
				field_values_to_update[i] = [];
				field_names_to_update[i][0] = 'alias_table';
				field_values_to_update[i][0] =  $('#alias_table_'+(i+1)).val();

				field_names_to_update[i][1] = 'pk_field_table';
				field_values_to_update[i][1] =  $('#pk_field_table_'+(i+1)).val();
				field_names_to_update[i][2] = 'menu_parent_table';
				field_values_to_update[i][2] =  $('#menu_parent_table_'+(i+1)).val();

				field_names_to_update[i][3] = 'allowed_table';

				if ($('#allowed_table_'+(i+1)).is(':checked')){
					field_values_to_update[i][3] =  '1';
				}
				else{
					field_values_to_update[i][3] =  '0';
				}

				field_names_to_update[i][4] = 'menu_order_table';
				field_values_to_update[i][4] =  $('#menu_order_table_'+(i+1)).val();

				field_names_to_update[i][5] = 'separator_before_table';
				if ($('#separator_before_table_'+(i+1)).is(':checked')){
					field_values_to_update[i][5] =  'y';
				}
				else{
					field_values_to_update[i][5] =  'n';
				}

				field_names_to_update[i][6] = 'enable_revision_table';

				if ($('#enable_revision_table_'+(i+1)).is(':checked')){
					field_values_to_update[i][6] =  '1';
				}
				else{
					field_values_to_update[i][6] =  '0';
				}

				field_names_to_update[i][7] = 'order_by_table';
				field_values_to_update[i][7] =  $('#order_by_table_'+(i+1)).val();

				field_names_to_update[i][8] = 'order_type_table';
				field_values_to_update[i][8] =  $('#order_type_table_'+(i+1)).val();

                field_names_to_update[i][9] = 'icon_table';
                field_values_to_update[i][9] =  $('#menu_icon_table_'+(i+1)).val();


				where_values[i] = $('#name_table_'+(i+1)).val();
			}
		}
		if (error !== ''){
			 window.scrollTo(0,0);
			 $('#confirmation_message_container').html('<div class="msg_error" id="error_message"><p>'+error+'<br/><br/><a href="javascript:{}" id="error_message_close_link">Ok, close</a></p></div>');
			 enable_disable_loader('disable');
		}
		else{
			$('#confirmation_message_container').html('');
			$.ajax({
				url: "api.php?function=update_records",

				data: ({
				where_values: where_values,
				field_names_to_update: field_names_to_update,
				field_values_to_update: field_values_to_update,
				tablename_to_update : tablename_to_update,

				} ),

				type: "POST",
				dataType: "json",
				success: function(data){

					enable_disable_loader('disable');

					if (data.status === 'ok'){
						if (data.result !== 'done'){
							alert('unexpected error include_tables_form.submit - ' + data.result+ ' - '+data.error_message);
						}
						else{
							 $("body").addClass("showing_confirmation_message");
							 window.setTimeout( remove_showing_confirmation_message_class, 1000);
						}
					}
					else{
						alert('unexpected error include_tables_form.submit status not OK');
					}
				},
				error: function(data, status, e){
					enable_disable_loader('disable');
					alert('unexpected error include_tables_form.submit ajax error'+JSON.stringify(data));
				}
			});
		}

		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

// modreq
<?php if ($page_name === 'main' && isset($function) && ($function == 'insert' || $function == 'show_insert_form' || $function == 'edit' || $function == 'update')){ ?>
    
    var ajax_call_in_progress = false;

    function delay_form_submission(){
        
<?php if ($add_delay_form_submission_during_ajax_calls === 1){ ?> 
        $('#dadabik_main_form').on('submit', function (e) {

        e.preventDefault();  // Prevent immediate form submission

        // Check if an AJAX call is in progress
        if (ajax_call_in_progress) {
            console.log("Form submission is delayed, please wait for the AJAX call to finish.");
            
            // Keep checking if the AJAX call has finished
            var checkInterval = setInterval(function () {
                if (!ajax_call_in_progress) {
                    clearInterval(checkInterval);
                    
                    $('#dadabik_main_form')[0].submit(); // Submit the form
                    console.log("Form submitted after AJAX call finished.");
                }
            }, 5); // Check every 5ms
        } else {
            // If no AJAX call is in progress, submit the form immediately
            $('#dadabik_main_form')[0].submit();
        }
        });
<?php } ?>
    }
        

    
	function set_required(){
		var c_temp = 0;
		var field_names = [];
		var field_values = [];
		var at_least_one_field = 0;
		$('#dadabik_main_form *').filter(':input').each(function(key, value){
			var name = this.name;
			at_least_one_field = 1;
			if (name != ''){
                if ( $(this).is('input:radio') ){
                    if ( $(this).is(':checked')){
                        field_names[c_temp]=name;
                        field_values[c_temp]=this.value;
                        c_temp = c_temp+1;
                    }
                }
                else{
                    field_names[c_temp]=name;
                    field_values[c_temp]=this.value;
                    c_temp = c_temp+1;
                }
            }
		});
		if (at_least_one_field === 1){
        ajax_call_in_progress = true; // Disable form submission
        delay_form_submission();
		$.ajax({
			url: "index.php?function=get_required_fields&tablename=<?php echo $table_name; ?>&api_call=1",
			data: ({

			current_function: '<?php echo $function; ?>',
			field_names: field_names,
			field_values: field_values,

			} ),
			type: "POST",
			dataType: "json",
			success: function(data){
				if (data.status === 'ok'){

					if (typeof data.fields_show !== 'undefined'){


                        $.each(data.fields_show, function(key, val) {

                            if (val == 1){
                                //$('#'+key+'_req').html('*');

                               // document.getElementById(key).style.display = 'block';
                               $('label[for="'+key+'"]').show();

                                document.getElementById('<?php echo $field_button_hint_container_id_prefix; ?>' + key).style.display = 'inline';

                               if (typeof document.getElementsByName('<?php echo $null_checkbox_prefix; ?>' + key)[0] !== 'undefined'){

                                 document.getElementsByName('<?php echo $null_checkbox_prefix; ?>' + key)[0].style.display = 'inline';
                                }
                            }
                            else{
                                //$('#'+key+'_req').html('');
                              //     document.getElementById(key).style.display = 'none';
                               $('label[for="'+key+'"]').hide();

                               document.getElementById('<?php echo $field_button_hint_container_id_prefix; ?>' + key).style.display = 'none';

                               if (typeof document.getElementsByName('<?php echo $null_checkbox_prefix; ?>' + key)[0] !== 'undefined'){
                               document.getElementsByName('<?php echo $null_checkbox_prefix; ?>' + key)[0].style.display = 'none';
                               }

                            }

                        })
                    }
                    if (typeof data.fields_required !== 'undefined'){
                        $.each(data.fields_required, function(key, val) {


                            if (val == 1){
                                $('#'+key+'_req').html('*');
                            }
                            else{
                                $('#'+key+'_req').html('');
                            }

                        })
                    }


				}
				else{
					alert('unexpected error function get_required_fields code 1');
				}
                ajax_call_in_progress = false;
			},
			error: function(data, status, e){
				alert('unexpected error function get_required_fields code 2');
                ajax_call_in_progress = false;
			}
		});
		}
	}


	function set_calculated_field_values(){
		var c_temp = 0;
		var field_names = [];
		var field_values = [];

		var at_least_one_field = 0;
		$('#dadabik_main_form *').filter(':input').each(function(key, value){
			var name = this.name;
			at_least_one_field = 1;

			if (name != ''){
                if ( $(this).is('input:radio') ){
                    if ( $(this).is(':checked')){
                        field_names[c_temp]=name;
                        field_values[c_temp]=this.value;
                        c_temp = c_temp+1;
                    }
                }
                else{
                    field_names[c_temp]=name;
                    field_values[c_temp]=this.value;
                    c_temp = c_temp+1;
                }
            }
		});
	    if (at_least_one_field === 1){
            ajax_call_in_progress = true; // Disable form submission
            delay_form_submission();
		$.ajax({
            <?php if ($function == 'edit' || $function == 'update'){ ?>
			
            url: "index.php?function=get_calculated_field_values&tablename=<?php echo $table_name; ?>&where_field=<?php echo urlencode($where_field); ?>&where_value=<?php echo urlencode($where_value); ?>&api_call=1",

            <?php } else{ ?>
                url: "index.php?function=get_calculated_field_values&tablename=<?php echo $table_name; ?>&api_call=1",

            <?php } ?>
			data: ({

			current_function: '<?php echo $function; ?>',
			field_names: field_names,
			field_values: field_values,

			} ),
			type: "POST",
			dataType: "json",
			success: function(data){
				if (data.status === 'ok'){

					$.each(data.fields, function(key, val) {

<?php if ($date_picker_type === 'flatpickr'){ ?>

                        if (   document.querySelector("[name='"+key+"']").id.substr(0,12) === 'date_picker_' || document.querySelector("[name='"+key+"']").id.substr(0,17) === 'date_time_picker_'){
						    fp = document.querySelector("[name='"+key+"']")._flatpickr;

						    fp.setDate(val);
						}
						else{

						    $("[name='"+key+"']").val(val);
						}
<?php }else{ ?>
						$("[name='"+key+"']").val(val);
<?php } ?>

					})

				}
				else{
					alert('unexpected error function get_calculated_field_values code 1');
				}
                ajax_call_in_progress = false;
                
			},
			error: function(data, status, e){
				alert('unexpected error function get_calculated_field_values code 2');

                ajax_call_in_progress = false;
			}
		});
		}
	}


<?php if ($table_needs_ajax_required_check === 1){ ?>
	set_required();
	$('#dadabik_main_form').change(function() {
		set_required();
	});
<?php } ?>

<?php
?>

<?php if ($table_needs_ajax_calculated_fields_check === 1){ ?>

	set_calculated_field_values();
	$('#dadabik_main_form').change(function() {
		set_calculated_field_values();
	});

<?php } ?>
<?php
?>
<?php } ?>
// fine modreq

<?php if ($page_name === 'main'){ ?>
<?php if ($date_picker_type === 'flatpickr'){ ?>

$('input[id^="date_picker"],input[id^="quick_search_date_picker"]').flatpickr({altInput: true, altFormat: "<?php echo $date_format_edit; ?>", dateFormat: "Y-m-d",enableTime: false ,allowInput: false, weekNumbers: true, "locale": "<?php echo $languages_flatpickr_codes[$language]; ?>"});
$('input[id^="date_time_picker"],input[id^="quick_search_date_time_picker"]').flatpickr({altInput: true, altFormat: "<?php echo $date_time_format_edit; ?>", dateFormat: "Y-m-d H:i:S",enableTime: true ,allowInput: false, weekNumbers: true, "locale": "<?php echo $languages_flatpickr_codes[$language]; ?>", enableSeconds: true});

<?php }else{ ?>


$('input[id^="date_picker"],input[id^="quick_search_date_picker"]').datepicker({
     yearRange: '<?php echo $start_year; ?>:<?php echo $end_year; ?>',
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
     changeYear: true
});

     $('input[id^="date_time_picker"],input[id^="quick_search_date_time_picker"]').datetimepicker({
    dateFormat: 'yy-mm-dd',
     yearRange: '<?php echo $start_year; ?>:<?php echo $end_year; ?>',
    changeMonth: true,
     changeYear: true,
	showSecond: true,
	timeFormat: 'HH:mm:ss'
});

<?php } ?>
<?php } ?>

});
</script>

<?php if ($page_name === 'interface_configurator'){ ?>
<script language="Javascript" src="include/hide_show_interface_configurator_fields_12.6.js"></script>
<script language="Javascript" src="include/split/split_1.6.0.min.js"></script>

<?php if ($dont_tagify_form_config === 0){ ?>

<script>

$(document).ready(function() {

    var input = document.getElementById('select_options_field');

    var tagify = new Tagify(input, {
        delimiters: '\n',
        originalInputValueFormat: valuesArr => `~${valuesArr.map(item => item.value).join('~')}~` // this is the format returned and submitted, tags "photo" and "electronics" become a "~photo~electronic~" string
    });

    // handle the translation from the db value (e.g. "~photo~electronic~") to the tags
    // Get the value from the input (e.g., "~photo~electronic~")
    var savedValue = input.value.trim();

    if (savedValue) {
        // Remove the leading and trailing tildes, then split by tilde to get individual tags
        var tags = savedValue.slice(1, -1).split('~').map(tag => ({ value: tag }));

        // Add the tags
        tagify.removeAllTags();
        tagify.addTags(tags);
    }

    var input = document.getElementById('select_type_field');

    var tagify_2 = new Tagify(input, {
        delimiters: '\n',
        whitelist : ['is_equal','is_different','contains','doesnt_contain','starts_with','ends_with','greater_than','less_than','greater_equal_than','less_equal_than','is_null','is_not_null','is_empty','is_not_empty','between'],
        originalInputValueFormat: valuesArr => `${valuesArr.map(item => item.value).join('/')}` // this is the format returned and submitted, tags "photo" and "electronics" become a "~photo~electronic~" stgring
    });

    // handle the translation from the db value (e.g. "~photo~electronic~") to the tags
    // Get the value from the input (e.g., "~photo~electronic~")
    var savedValue = input.value.trim();

    if (savedValue) {
        // Remove the leading and trailing tildes, then split by tilde to get individual tags
        var tags = savedValue.split('/').map(tag => ({ value: tag }));

        // Add the tags
        tagify_2.removeAllTags();
        tagify_2.addTags(tags);
    }

    // The enter key is used to insert a tag, but if we are not in the middle of inserting something, Enter should submit the form
    var main_form = document.getElementById('form_configurator_form');

    tagify_2.on('keydown', onTagifyKeyDown)

    function onTagifyKeyDown(e){
        if( e.detail.event.key== 'Enter' && !tagify_2.state.inputText && !tagify_2.state.editing ){
            setTimeout(() => main_form.submit())
        }
    }

    // let's use sortable.js to allow tags drag and drop
    var elements = document.getElementsByClassName('tagify');
    
    var sortable = Sortable.create(elements[0],{
        draggable: "."+tagify.settings.classNames.tag,
        onEnd: function (evt) {
            tagify.updateValueByDOMTags();
        }
    });

    // we may have two tagify fields
    if (elements[1]){

        var sortable_2 = Sortable.create(elements[1],{
            draggable: "."+tagify_2.settings.classNames.tag,
            onEnd: function (evt) {
                tagify_2.updateValueByDOMTags();
            }
        });
    }
    

    
});
</script>

<?php } ?>
<script>

var timer;

function set_session_variable(variable_name, variable_value)
{
    $.ajax({
		url: "internal_table_manager.php?function=set_session_variable&api_call=1&tablename=<?php echo urlencode($table_name); ?>",
		data: ({
		variable_name: variable_name,
		variable_value:  variable_value,
		} ),
		type: "POST",
		dataType: "json",
		success: function(data){
			if (data.status === 'ok'){
				// enable_disable_loader('disable');
			}
			else{
				//enable_disable_loader('disable');
				alert('unexpected error set session variable');
			}
		},
		error: function(data, status, e){
			//enable_disable_loader('disable');
			alert('unexpected error set session variable 2 ');
		}
	});
}

function delay(function_to_call, delay_ms) {
  var timer = 0;
  return function() {
    var context_to_call = this, arguments_to_call = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      function_to_call.apply(context_to_call, arguments_to_call);
    }, delay_ms);
  };
}

function update_form_preview(form_preview_type, form_preview_id_group)
{
    function_to_call = '';
    error_message = '';
    additional_parameters = '';

    enable_disable_loader_form_preview('enable');

<?php if(!isset($_SESSION[$table_name.'_where_value_form_preview']) || !isset($_SESSION[$table_name.'_where_field_form_preview'])  ){ ?>



    $.ajax({
    url: "index.php?function=get_where_value_form_preview&tablename=<?php echo urlencode($table_name); ?>&api_call=1",
    data: ({
    } ),
    type: "POST",
    dataType: "json",
    success: function(data){

        if (data.status === 'ok'){

            where_value_form_preview = data.where_value_form_preview;
            where_field_form_preview = data.where_field_form_preview;

            set_session_variable('<?php echo $table_name; ?>_where_value_form_preview', where_value_form_preview);
            set_session_variable('<?php echo $table_name; ?>_where_field_form_preview', where_field_form_preview);
<?php } else{

        echo 'where_value_form_preview = \''.str_replace("'", "\'",$_SESSION[$table_name.'_where_value_form_preview']).'\';';
        echo 'where_field_form_preview = \''.$_SESSION[$table_name.'_where_field_form_preview'].'\';';

} ?>
        switch (form_preview_type){
                case 'edit':

                    if (where_field_form_preview === '' || where_value_form_preview === ''){
                        error_message = '<p>Your table doesn\'t hava a unique field or it\'s empty (no records), I cannot create a preview for the edit form';
                    }
                    else{

                        function_to_call = 'edit';
                        additional_parameters = '&where_field='+encodeURI(where_field_form_preview)+'&where_value='+encodeURI(where_value_form_preview);
                    }
                    break;
                case 'search':
                    function_to_call = 'show_search_form';
                    break;
                case 'insert':
                    function_to_call = 'show_insert_form';
                    break;
                case 'details':

                    if (where_field_form_preview === '' || where_value_form_preview === ''){
                        error_message = '<p>Your table doesn\'t hava a unique field or it\'s empty (no records), I cannot create a preview for the edit form';
                    }
                    else{
                        function_to_call = 'details';
                        additional_parameters = '&where_field='+encodeURI(where_field_form_preview)+'&where_value='+encodeURI(where_value_form_preview);
                    }
                    break;
             }

            if (function_to_call !== ''){
                $.post('internal_table_manager.php?tablename=<?php echo urlencode($table_name); ?>&use_preview_table=1', $('#form_configurator_form').serialize(), function(){
                $('#form_configurator_preview_div').load("index.php?tablename=<?php echo urlencode($table_name); ?>&function=" + function_to_call + additional_parameters +  "&form_preview_id_group=" + form_preview_id_group + "&onlyform&from_form_configurator_preview=1", function(){
                current_field = $("#change_field_select option:selected" ).text();

                enable_disable_loader_form_preview('disable');

                });

                }
                );
            }
            else if (error_message !== ''){

                $('#form_configurator_preview_div').html(error_message);

                enable_disable_loader_form_preview('disable');

            }
<?php if(!isset($_SESSION[$table_name.'_where_value_form_preview']) ){ ?>


        }
        else{
            enable_disable_loader_form_preview('disable');
            alert('unexpected error function get_where_value_form_preview code 1');
        }
    },
    error: function(data, status, e){
        enable_disable_loader_form_preview('disable');
        alert('unexpected error function get_where_value_form_preview code 2. Possible reasons:\n-The table you are configuring is disabled  ("Pages" tab)\n- You (admin) do not have the permissions to read it\n - You have  deleted a table/view (or the table/view is not installed/enabled) used in form configurator as source for a lookup field or as an items table');
    }

    });

<?php } else{ ?>
<?php } ?>
}
<?php if ( $enable_form_config_live_preview === 1){ ?>

$(document).ready(function() {

    var form_preview_type = '<?php echo $form_preview_type; ?>';
    var form_preview_id_group = '<?php echo $form_preview_id_group; ?>';

    update_form_preview(form_preview_type, form_preview_id_group);

    // when the form type select changes
    $('#form_preview_type_select').change(function() {
        form_preview_type = $('#form_preview_type_select').val();

        // set the new form type in session
	    set_session_variable('form_preview_type', form_preview_type);

	    // refresh form preview
	    update_form_preview(form_preview_type, form_preview_id_group);
	});

    // when the id group select changes
    $('#form_preview_id_group_select').change(function() {

        form_preview_id_group = $('#form_preview_id_group_select').val();

        // set the new form type in session
	    set_session_variable('form_preview_id_group', form_preview_id_group);

	    // refresh form preview
	    update_form_preview(form_preview_type, form_preview_id_group);
	});

    // when the form configurator changes
    //$('#form_configurator_form').on('keyup change paste', 'input, select, textarea', function(){

    $('#form_configurator_form').on('keyup change paste', 'input, select, textarea', delay(function (event) {

    if(event.which != 13){ // no enter
        update_form_preview(form_preview_type, form_preview_id_group);
    }
    }, 500));
});


<?php } //  if ( $enable_form_config_live_preview === 1){?>

</script>
<?php } ?>
<?php if ($page_name === 'interface_configurator' || $page_name == 'datagrid_configurator' || $page_name == 'data'){ ?>

<link rel="stylesheet" href="include/codemirror-5.65.20/codemirror.min.css">
<script src="include/codemirror-5.65.20/codemirror.min.js"></script>
<script src="include/codemirror-5.65.20/javascript.min.js"></script>
<script src="include/codemirror-5.65.20/sql.min.js"></script>
<?php } ?>

<script>
<?php if ($page_name === 'main' && isset($function) && ($function == 'insert' || $function == 'show_insert_form' || $function == 'edit' || $function == 'update' || $function == 'show_search_form')){ ?>


function refresh_cascade_children(field_name, field_value, _POST_2, details_row, form_type, disabled_attribute, set_field_default_value, default_value_field_name, default_value, show_edit_form_after_error, show_insert_form_after_error, user_group_name)
// goal: when a select_single field is changed, change all the select_* fields who depends on it in cascade
{

	enable_disable_loader('enable');

	$.ajax({
		url: "index.php?function=get_cascade_children<?php if (isset($master_table_name)){ echo "&master_table_name=".urlencode($master_table_name); } ?><?php if (isset($master_table_where_field)){ echo "&master_table_where_field=".urlencode($master_table_where_field); } ?><?php if (isset($master_table_where_value)){ echo "&master_table_where_value=".urlencode($master_table_where_value); } ?>&tablename=<?php echo $table_name; ?>&api_call=1",
		data: ({

		current_function: '<?php echo $function; ?>',
		field_name: field_name,
		field_value: field_value,
		_POST_2:  _POST_2,
		details_row: details_row,
		form_type: form_type,
		disabled_attribute: disabled_attribute,
		set_field_default_value: set_field_default_value,
		default_value_field_name: default_value_field_name,
		default_value: default_value,
		show_edit_form_after_error: show_edit_form_after_error,
		show_insert_form_after_error: show_insert_form_after_error,
		user_group_name: user_group_name,
		} ),
		type: "POST",
		dataType: "json",
		success: function(data){

			if (data.status === 'ok'){
			    if (typeof data.fields !== 'undefined'){
                    $.each(data.fields, function(key, val) {

                        if (val == 'reset_all'){ // child of a changed child, just remove all the options

                            var x = document.getElementsByName(key);
                            var length = x[0].length;

                            if ( length > 0) {

                                for (i=length;i>0;i--){
                                    x[0].remove(i);
                                }
                            }
                        }
                        else{
                            $('#'+key).html(val);
                        }

                        // similar code in footer
                        if ( $('select[name="'+key+'"]').hasClass("searchable_select") ){

                            $('select[name="'+key+'"]').select2({dropdownAutoWidth : 'true',allowClear: true,width: 'auto',minimumInputLength: <?php echo $ajax_dropdown_minimum_input_length; ?>});
                        }

                        $('select[name="'+key+'"]').trigger('change');

                    })
                }
				enable_disable_loader('disable');

			}
			else{
				enable_disable_loader('disable');
				alert('unexpected error function refresh_cascade_children code 1');
			}
		},
		error: function(data, status, e){
			enable_disable_loader('disable');
			alert('unexpected error function refresh_cascade_children code 2');
		}
	});
}
<?php } ?>

<?php if ($page_name === 'interface_configurator' || $page_name === 'datagrid_configurator' || $page_name === 'data'){ ?>

<?php if ($enable_codemirror === 1){ ?>
    
$(document).ready(function() {

    const jsField = document.getElementById("js_custom_formatting_function_field");
    if (jsField) {
        editor = CodeMirror.fromTextArea(jsField, {
            lineNumbers: true,
            mode: "javascript",
            theme: "default",
            indentUnit: 2,
            lineWrapping: true
        });
        document.querySelector('form').addEventListener('submit', function () {
            editor.save();

        });
        
    }

    const sqlField = document.getElementById("sql_create_view");
    if (sqlField) {
        editor = CodeMirror.fromTextArea(sqlField, {
            lineNumbers: true,
            mode: "sql",
            theme: "default",
            lineWrapping: true
        });
        document.getElementById('sql_create_view_form').addEventListener('submit', function () {
            editor.save(); // Syncs editor content back to the textarea
        });
    }

    const templateField = document.getElementById("template_table");
    if (templateField) {
        editor = CodeMirror.fromTextArea(templateField, {
            lineNumbers: true,
            mode: "javascript",
            theme: "default",
            indentUnit: 2,
            lineWrapping: true
        });
        document.getElementById('datagrid_template_form').addEventListener('submit', function () {
            editor.save(); // Syncs editor content back to the textarea
        });

        const checkbox = document.getElementById("enable_template_table_checkbox");
        if (checkbox) {
            if (checkbox.checked === false){
                editor.setOption("readOnly", true);
                editor.getWrapperElement().classList.toggle("CodeMirror-disabled", true);
            }
            checkbox.addEventListener("change", function () {
                const enabled = this.checked;
                editor.setOption("readOnly", !enabled);
                editor.getWrapperElement().classList.toggle("CodeMirror-disabled", !enabled);
            });
        }
    }

    const custom_css = document.getElementById("custom_css");
    if (custom_css) {
        editor = CodeMirror.fromTextArea(custom_css, {
            lineNumbers: true,
            mode: "javascript",
            theme: "default",
            indentUnit: 2,
            lineWrapping: true
        });

        const cssSaveBtn = document.getElementById("save_css_button");
        if (cssSaveBtn) {
            cssSaveBtn.addEventListener('click', function () {
            editor.save(); // Aggiorna il contenuto della textarea prima della chiamata AJAX
            });
        }
    }
    
});

<?php } // end if($enable_codemirror === 1)  ?>


function hide_show_show_if_value_field(field)
{
    if(field.value === 'is empty' || field.value === 'is not empty' || field.value === 'is null' || field.value === 'is not null' || field.value === '' ){
        $('#show_if_value_field').val('');
        $('#show_if_value_field').css('visibility', 'hidden');
    }
    else{
        $('#show_if_value_field').css('visibility', 'visible');
    }
}

function refresh_items_table_fk_field_names(table_name, index)
{
	enable_disable_loader('enable');
	var dropdown_list_options = '';

	var items_table_names_field_width = ($('#items_table_names_field_'+index).css("width"));

	if (table_name === ''){

		$('#items_table_fk_field_names_field_'+index).html(dropdown_list_options);
		$('#items_table_fk_field_names_field_'+index).css("width", items_table_names_field_width);
		$('#items_table_fk_field_names_field_'+index).prop( "disabled", true );
		enable_disable_loader('disable');
	}
	else{
		$.ajax({
			url: "admin.php?function=get_table_fields&tablename="+table_name+"&api_call=1",
			data: ({
			} ),
			type: "POST",
			dataType: "json",
			success: function(data){

				if (data.status === 'ok'){
					var dropdown_list_options = dropdown_list_options +  '<option value="""></option>';
					$.each(data.fields, function(key, value) {
						dropdown_list_options = dropdown_list_options + '<option value="'+value+'">'+value+'</option>';
					})

					$('#items_table_fk_field_names_field_'+index).html(dropdown_list_options);
					$('#items_table_fk_field_names_field_'+index).css("width", items_table_names_field_width);
					$('#items_table_fk_field_names_field_'+index).prop( "disabled", false );
					$('#add_remove_linked_fields_container').css( "display", 'inline' );

					enable_disable_loader('disable');
				}
				else{
					enable_disable_loader('disable');
					alert('unexpected error function refresh_items_table_fk_field_names code 1');
				}
			},
			error: function(data, status, e){
				enable_disable_loader('disable');
				alert(JSON.stringify(data));
				alert('unexpected error function refresh_items_table_fk_field_names code 2');
			}
		});
	}
}



function refresh_internal_table_lookup_fields(table_name, lookup_table_name)
// goal: when the primary_key_table_field field is changed in the interface configurator, change all the fields that depends on it in cascade
{

	enable_disable_loader('enable');
	var dropdown_list_options = '';

	// remove the additional linked fields after the first
	var number_linked_fields = Number($('#number_linked_fields').val());

	for (i=1;i<number_linked_fields;i++){
        // remove the linked fields listbox
        $("#linked_fields_field_"+ i).remove();

        // decrement the hidden number_linked_fields
        $("#number_linked_fields").val( number_linked_fields-i  );
	}

	if (lookup_table_name === ''){
		$('#linked_fields_field_0').html(dropdown_list_options);
		$('#linked_fields_field_0').prop( "disabled", true );



		$('#primary_key_field_field').html(dropdown_list_options);
		$('#primary_key_field_field').prop( "disabled", true );

		$('#linked_fields_order_by_field').html(dropdown_list_options);
		$('#linked_fields_order_by_field').prop( "disabled", true );
		$('#add_remove_linked_fields_container').css( "display", 'none' );

		$('#linked_fields_order_type_field').prop( "disabled", true );
		$('#where_clause_field').prop( "disabled", true );
		$('#cascade_parent_field').prop( "disabled", true );
		$('#cascade_filter_field').prop( "disabled", true );


		enable_disable_loader('disable');
	}
	else{
		$.ajax({
			url: "admin.php?function=get_table_fields&tablename="+lookup_table_name+"&api_call=1",
			data: ({
			} ),
			type: "POST",
			dataType: "json",
			success: function(data){

				if (data.status === 'ok'){
					var dropdown_list_options = dropdown_list_options +  '<option value="""></option>';
					$.each(data.fields, function(key, value) {
						dropdown_list_options = dropdown_list_options + '<option value="'+value+'">'+value+'</option>';
					})

					$('#linked_fields_field_0').html(dropdown_list_options);
					$('#primary_key_field_field').html(dropdown_list_options);
					$('#linked_fields_order_by_field').html(dropdown_list_options);
					$('#cascade_filter_field').html(dropdown_list_options);

					$('#linked_fields_field_0').prop( "disabled", false );
					$('#primary_key_field_field').prop( "disabled", false );
					$('#linked_fields_order_by_field').prop( "disabled", false );
					$('#linked_fields_order_type_field').prop( "disabled", false );
					$('#where_clause_field').prop( "disabled", false );
					$('#cascade_filter_field').prop( "disabled", false );

					$('#add_remove_linked_fields_container').css( "display", 'inline' );

					$.ajax({
						url: "admin.php?function=get_table_fields&tablename="+table_name+"&api_call=1",
						data: ({
						} ),
						type: "POST",
						dataType: "json",
						success: function(data){

							if (data.status === 'ok'){
								var dropdown_list_options = dropdown_list_options +  '<option value="""></option>';
								$.each(data.fields, function(key, value) {
									dropdown_list_options = dropdown_list_options + '<option value="'+value+'">'+value+'</option>';
								})
								$('#cascade_parent_field').html(dropdown_list_options);
								$('#cascade_parent_field').prop( "disabled", false );

								enable_disable_loader('disable');
							}
							else{
								enable_disable_loader('disable');
								alert('unexpected error function refresh_internal_table_lookup_fields code 3');
							}
						},
						error: function(data, status, e){
							enable_disable_loader('disable');
							alert(JSON.stringify(data));
							alert('unexpected error function refresh_internal_table_lookup_fields code 4');
						}
					});

				}
				else{
					enable_disable_loader('disable');
					alert('unexpected error function refresh_internal_table_lookup_fields code 1');
				}
			},
			error: function(data, status, e){
				enable_disable_loader('disable');
				alert(JSON.stringify(data));
				alert('unexpected error function refresh_internal_table_lookup_fields code 2');
			}
		});
	}
}
<?php } ?>

$(document).ready(function() {


function acquireLock(tableCell) {
/*
 * Nel caso specifico resituiamo il campo della form in base
 * a un parametro della data table
 */
const $tableCell = $(tableCell);
// edit eu
//const value = $tableCell.attr('data-text');

const payload = {};
// edit eu
//payload.value = value;
payload.row = $tableCell.attr('data-row');
payload.field = $tableCell.attr('data-field');
// edit eu
//payload.text = $tableCell.attr('data-text');
payload.type = $tableCell.attr('data-type');
payload.dataSource = $tableCell.attr('data-source');


return new Promise((resolve, reject) => {
    $.ajax({
        method: 'POST',
        url: `api_fe.php?function=acquire_lock`,
        dataType: 'json',
        data: payload,
    }).then((response) => {
        resolve(response);
    }).fail((xhr) => {
        reject(xhr.responseText)
    })
});


}

function cancel(tableCell) {
const $tableCell = $(tableCell);

// edit eu
//const value = $tableCell.attr('data-text');

const payload = {};

// edit eu
//payload.value = value;
payload.row = $tableCell.attr('data-row');
payload.field = $tableCell.attr('data-field');
// eu edit
// payload.text = $tableCell.attr('data-text');
payload.type = $tableCell.attr('data-type');
payload.dataSource = $tableCell.attr('data-source');

return new Promise((resolve, reject) => {
    $.ajax({
        method: 'POST',
        url: `api_fe.php?function=release_lock`,
        dataType: 'json',
        data: payload,
    }).then((response) => {
        resolve(response);
    }).fail((xhr) => {
        reject(xhr.responseText)
    })
});
}

function saveData(tableCell) {
/*
 * Nel caso specifico resituiamo iif ($('#error-on-save')l campo della form in base
 * a un parametro della data table
 */
const $tableCell = $(tableCell);
// eu edit
// const value = $tableCell.attr('data-text');

const payload = {};

// edit eu
//payload.value = value;
payload.row = $tableCell.attr('data-row');
payload.field = $tableCell.attr('data-field');
// eu edit
//payload.text = $tableCell.attr('data-text');
payload.type = $tableCell.attr('data-type');
payload.dataSource = $tableCell.attr('data-source');
payload.newValue = $tableCell.find('textarea').val() || $tableCell.find('input').val() || $tableCell.find('select').val() || null;


return new Promise((resolve, reject) => {
    const savePromise = $.ajax({
        method: 'POST',
        url: `api_fe.php?function=update_record`,
        dataType: 'json',
        data: payload,
    });

    savePromise.then((response) => {
        resolve(response);

        $("body").addClass("showing_confirmation_message");
        window.setTimeout( remove_showing_confirmation_message_class, 1000);



/*
        // Ricarichiamo la tabella
        $.ajax({
            method: 'GET',
            url: `${apiBaseUrl}/table-data-` + payload.dataSource,
            dataType: 'json',
        }).then((res) => {
            renderTable($("#table-placeholder-" + payload.dataSource + "-names"), payload.dataSource, res);
            $alert = '<div id="alert" style="position: absolute; background-color: red; color: white; top:10px; left:10px; padding: 10px 20px;">Salvato!!!!</div>'
            $('body').append($alert);
            setTimeout(() => {
                $('body').find('#alert').remove();
            }, 1000);
        });
        */
    });

    savePromise.fail((xhr) => {
        reject(xhr.responseText)
    })
});
}

<?php if ($page_name === 'main' && $enable_live_edit === 1 && $enable_edit == '1'){ ?>
(new LiveEditing()).init({
                selector: '#table-placeholder-international-names',
                onCellEditRequest: acquireLock,
                onSave: saveData,
                onCancel: cancel
            });


<?php } ?>





});

</script>

<?php if ($page_name === 'main' && isset($function) && ( ($function == 'insert' || $function == 'show_insert_form') && $warn_unsaved_changes_insert_form === 1 || ($function == 'edit' || $function == 'update') && $warn_unsaved_changes_edit_form === 1)){ ?>
<script src="include/jquery.dirty.js"></script>

<script>

$(document).ready(function() {
    if($("#dadabik_main_form").length){

        $("#dadabik_main_form").dirty({
            preventLeaving:true
        });


    <?php if  ($function == 'insert' && $warn_unsaved_changes_insert_form === 1 ||  $function == 'update'  && $warn_unsaved_changes_edit_form === 1){ ?>
        $("#dadabik_main_form").dirty('setAsDirty');
    <?php } ?>
    }
});
</script>


<?php } ?>

<?php
?>

<?php
if (  isset($modal_mode) && $modal_mode === 1){
    $show_logout_account_admin_box = 0;
}
?>
<?php if ( $page_name === 'interface_configurator' && $enable_form_config_live_preview === 1){ ?>
<style>
    .tr_form_separator {
        margin-top: 4px;
    }

    #left-panel table tr td {
        padding-top: 10px;
    }

    #left-panel table tr.tr_form_separator td {
        padding: 10px;
    }
    .form-configurator-title-label {
        background: #e5e7eb;
        color: black;
        font-size: 20px;
        padding: 10px 15px;
        font-weight: bold;
        border-bottom: 1px solid #d1d5db;
    }
    #form-settings-panel {
        margin-top: 15px;
        border: 2px solid #e5e7eb;
    }
    #left-panel input:not([type=checkbox]):not([type=radio]),
    #left-panel select,
    #left-panel textarea {
        width: 100%;
    }

    .form_hint {
        display: none;
    }

    #left-panel {
        padding: 15px;
        padding-right: 30px;
        overflow: hidden;
        width: 50%;
        min-width: 300px;
    }

    #right-panel {
        padding: 15px;
        padding-left: 30px;
        flex-basis: 50%;
        flex-grow: 1;
        min-width: 300px;
    }

    #right-panel .form_separator:first-child {
        margin-top: 4px;
    }
    #right-panel input, #right-panel select, #right-panel textarea {
        min-width: 100px;
    }

    .gutter.gutter-horizontal {
        cursor: ew-resize;
        background: #e5e7eb;
    }
</style>
<?php } ?>

<?php if ($page_name === 'main' && $check_ie_browser === 1 && (!isset($_GET['function']) || $_GET['function'] !== 'show_ie_message')) { ?>
<script>
var user_agent = window.navigator.userAgent;

if (user_agent.indexOf('MSIE ') > 0 || user_agent.indexOf('Trident/') > 0){
    window.location.replace("index.php?function=show_ie_message");
}
</script>

<?php } ?>

</head>

<body
<?php
if ($page_name === 'main' && ($function === 'insert' || $function === 'edit' || $function === 'update')) {
?>
onload="enable_disable_input_box_insert_edit_form('<?php echo $null_checkbox_prefix.'\', \''.$year_field_suffix.'\', \''.$month_field_suffix.'\', \''.$day_field_suffix.'\', \''.$hours_field_suffix.'\', \''.$minutes_field_suffix.'\', \''.$seconds_field_suffix; ?>');show_hide_text_other()"
<?php
} // end if
if ($page_name === 'interface_configurator') {
?>
onload="hide_show_interface_configurator_fields(document.getElementById('e1'));hide_show_show_if_value_field(document.getElementById('show_if_operator_field'));"
<?php
} // end if
if ($page_name === 'main' && ($function === 'generate_report' || $function === 'generate_pivot')) {
?>
onload="show_hide_date_functions_select();"
<?php
} // end if
if ($page_name === 'main' && ($function === 'show_search_form' || $function === 'search')) {
?>
onload="show_hide_text_between();"
<?php
} // end if
?>
>
<div id="dialog" title=""></div>

<div id="div_help" style="width: 600px">
<div id="div_help_content">
<div id="div_help_content_title"></div>
<div id="div_help_content_text"></div>
</div>
</div>

<div id="div_help_frontend" style="width: 600px;z-index: 1">
<div id="div_help_frontend_content">
<div id="div_help_frontend_content_title"></div>
<div id="div_help_frontend_content_text"></div>
</div>
</div>
<?php
if (isset($show_maintenance_header) && $show_maintenance_header === 1){
    echo '<div align="center"><h1>YOU ARE IN MAINTENANCE MODE</h1></div>';
}
?>
<?php
if ($page_name === 'main'){
?>
<div class="fixed-header" style="<?= $display_style_mobile_header_footer ?>">
	<div class="row">
		<div class="col-xs-8">
			<!--<img class="logo img-responsive" src="images/logo_alpha.png">-->

			<?php if (count($languages_ar) > 1){ ?>
			<form method="GET" class="css_form" style="margin:auto;"  action="<?php echo $dadabik_main_file; ?>">
			 <input type="hidden" name="function" value="change_language"><div  class="select_element select_element_change_language"  ><select name="language" onchange="this.form.submit()">
			<?php
			foreach ($languages_ar as $value){
				echo '<option value="'.$value.'"';
				if ($language === $value){
					echo ' selected';
				}
				echo '>'.ucfirst($value).'</option>';
			}
			?>
			</select>
			</div>
			</form>
			 <?php } ?>
		</div>
		<div class="col-xs-4 right">
			<a href="#" data-mobile-menu-toggle style="<?= $display_style_menu ?>">
				<i class="fa fa-bars fa-2x"></i>
			</a>
		</div>
	</div>
</div>

<?php if ($enable_authentication === 1){ ?>
<?php if ($show_logout_account_admin_box === 1){ ?>
<div class="fixed-footer" style="<?= $display_style_mobile_header_footer ?>">
<?php if ($config['username_public_user'] !== '' && $config['username_public_user'] === $current_user){ ?>
<a href="login.php?function=show_login_form" class="btn  btn-primary" title="Login" id="mobile_login_btn"><i class="fa fa-sign-in"></i> Login</a>
<?php } else{ ?>
<?php if ($enable_user_password_modification === 1 && !is_ldap_user($_SESSION['logged_user_infos_ar']['id_user'])){ ?>
	<a href="index.php?function=edit_account" class="btn  btn-primary" title="Your account" id="mobile_your_account_btn"><i class="fa fa-user"></i> Your account</a>
<?php } ?>
	<a href="login.php?function=logout" title="Logout" class="btn  btn-danger" id="mobile_logout_btn"><i class="fa fa-sign-out"></i> Logout</a>
<?php } ?>
</div>
<?php } ?>
<?php } ?>
<?php
}
?>

<table class="main_table" role="presentation">
<tr>
<td class="main_table_td">
<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'permissions_manager'  || $page_name === 'interface_configurator' || $page_name === 'install' || $page_name === 'upgrade' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator' || $page_name === 'data') {
	$class_temp = 'table_interface_container';
}
else{
	$class_temp = 'table_interface_container_login';
}
?>
<table class="<?php echo $class_temp; ?>" role="presentation"  align="center" cellpadding=0>

<?php
if ($page_name === 'main' || $page_name === 'login') {


	if ($page_name === 'login'){
		//echo '<tr class="table_interface_container_tr_logo_login"><td><img id="dadabik_logo" style="max-width: 100%;height: auto;" src="'.$logo_img.'">';

        echo '<tr class="table_interface_container_tr_logo_login"><td><div class="navbar-brand justify-content-center page-sidebar-compact-hidden flex-shrink-0 text-white px-sm-3 py-3 me-0">
        <img id="dadabik_logo"  src="'.$src_logo.'">
      </div>';


	}
	if ($page_name === 'main'){
        echo '<tr class="table_interface_container_tr_logo" style="'. $display_style_header. '"><td>';
	    if ($link_logo_home === 1){
	        //echo '<tr class="table_interface_container_tr_logo"><td><a href="index.php"><img id="dadabik_logo" style="max-width: 100%;height: auto;" src="'.$logo_img.'"></a>';
	    }
	    else{
		    //echo '<tr class="table_interface_container_tr_logo"><td><img id="dadabik_logo" style="max-width: 100%;height: auto;" src="'.$logo_img.'">';
		}



   





?><div class="d-flex align-items-center w-100" style="align-items: flex-start;">
<!-- Logo aligned to the left -->
<div class="mr-auto"> <!-- mr-auto pushes everything else to the right -->

<?php
if ($link_logo_home === 1){
    echo '<a href="index.php"><img id="dadabik_logo" style="max-height: 100%; width: auto; margin: 8px;" src="'.$src_logo.'"></a>';
}
else{
    echo '<img id="dadabik_logo" style="max-height: 100%; width: auto; margin: 8px;" src="'.$src_logo.'">';
    
}
?>

</div>
    <div class="d-flex align-items-center justify-content-end w-100">


<?php if (false && $orazio_edition === 1 && (!isset($config['username_public_user']) || $config['username_public_user'] !== $current_user)){ ?>

    <table  role="presentation" style="display:inline;" ><tr align="right"><td><a class="btn btn-sm btn-primary" href="admin.php">Advanced App Editor</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-danger" href="index.php?function=deploy">Publish this App</a>&nbsp;&nbsp;&nbsp;<a target="_blank" title="Ask a question, we'll get in touch very soon" class="btn btn-sm btn-secondary" href="/contacts" title="Ask a question">Contact us</a> </td></tr></table>&nbsp;&nbsp;



<?php } ?>

<?php if ($orazio_edition === 1 && (!isset($config['username_public_user']) || $config['username_public_user'] !== $current_user)){ ?>

    <table  role="presentation" style="display:inline;" ><tr align="right"><td><a class="btn btn-sm btn-danger" href="index.php?function=deploy"><li class="fa fa-cloud-upload fa-lg" aria-hidden="true"></li>&nbsp;&nbsp;Publish this App</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="admin.php"><li class="fa fa-code fa-lg" aria-hidden="true"></li>&nbsp;&nbsp;Advanced App Editor</a></td></tr></table>&nbsp;&nbsp;



<?php } ?>

          <div class="d-flex align-items-center">
            
            <!--<span class="fs-sm me-3 text-grey">You are currently in <strong>live</strong> mode. Change the mode:</span>--> 
             
            <?php if ($current_user_is_administrator === 1 || $current_user_is_beta === 1){ ?><span class="fs-5<?php if ($_SESSION['dev_mode'] === 'live'){ echo ' fw-bold';} ?>" id="dev_live_option">Live</span>



            <div class="form-check form-switch mode-switch pe-lg-1 ms-3">
            <form id="change_dev_mode_form">
                <input type="hidden" name="reload_after_change" value="1">
               <input type="checkbox" name="dev_mode"  class="form-check-input beta-live-check" id="dev_mode" style="transform: scale(1.5);" <?php if ($_SESSION['dev_mode'] === 'beta'){ echo 'checked';} ?>> 
            </form>
            </div>
            <span class="ms-2 fs-5<?php if ($_SESSION['dev_mode'] === 'beta'){ echo ' fw-bold';} ?>" id="dev_beta_option">Beta</span>
          </div>

          <?php } ?>
        
          <!-- Language dropdown -->
          <div class="dropdown nav ms-3">
          <?php
        if (count($languages_ar) > 1){
        ?>
            <button id="language_button" type="button" class="nav-link text-dark py-0 px-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-globe fs-xl opacity-60 me-1"></i>
              <?= ucfirst($language); ?>
            </button>
            
            <?php
                echo '<ul class="dropdown-menu dropdown-menu-end my-3" style="min-width: 7rem;">';
			    foreach ($languages_ar as $value){
                    echo  '<li><a href="'.$dadabik_main_file.'?function=change_language&language='.$value.'" class="dropdown-item';
                    if ($language === $value){
                        echo ' active';
                    }
                    echo '">'.ucfirst($value).'</a></li>';
			    }
                echo '</ul>';
            }
			?>
            
          </div>

<?php
$show_login_link = 0;
$show_logout_link = 0;
$show_current_user = 0;
$show_your_account = 0;
if ($enable_authentication === 1){
    if ($show_logout_account_admin_box === 1){
        if ($config['username_public_user'] !== '' && $config['username_public_user'] === $current_user){
            $show_login_link = 1;
        }
        else{
            $show_current_user = 1;
            $show_logout_link = 1;

            if ($enable_user_password_modification === 1 && !is_ldap_user($_SESSION['logged_user_infos_ar']['id_user'])){
                $show_your_account = 1;
            }
        }
    }
}
$current_user_to_display = '';
if ($show_current_user ===1 ){
    $current_user_to_display = $current_user;
    if (isset($_SESSION['google_logged_user']['first_name'])){
        $current_user_to_display = $_SESSION['google_logged_user']['first_name'];
    }
}
?>


            <!-- User account dropdown -->
          <div class="dropdown nav ms-3">
            <button type="button" id="account_button" class="nav-link text-dark p-0 fw-bold" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
              <!-- <span class="position-relative bg-primary bg-opacity-25 rounded-circle p-4 me-2">

                <span class="position-absolute top-50 start-50 translate-middle text-dark fs-lg" ><?= ucfirst(substr($current_user,0,1)) ?></span>
              </span> -->
              <?= $current_user_to_display ?>
              <i class="bx bx-dots-vertical-rounded fs-xl opacity-60 ms-1"></i>
            </button>&nbsp;&nbsp;&nbsp;

<ul class="dropdown-menu dropdown-menu-end my-2">
<?php if ($show_your_account ===1 ){ ?>
              <li>
                <a href="<?=$site_url ?>index.php?function=edit_account" class="dropdown-item d-flex align-items-center">
                  <i class="bx bx-user fs-lg opacity-75 me-2"></i>
                  Your account
                </a>
              </li>
<?php } ?>

<?php if ($show_logout_link ===1 ){ ?>

              <li>
                <a href="<?=$site_url ?>login.php?function=logout" class="dropdown-item d-flex align-items-center">
                  <i class="bx bx-log-out fs-lg opacity-75 me-2"></i>
                  Log out
                </a>
              </li>

<?php } ?>


<?php if ($show_login_link ===1 ){ ?>

<li>
  <a href="<?=$site_url ?>login.php?function=show_login_form" class="dropdown-item d-flex align-items-center">
    <i class="bx bx-log-in fs-lg opacity-75 me-2"></i>
    Log in
  </a>
</li>

<?php } ?>

            </ul>
          </div>
<?php
if ($current_user_is_administrator === 1) {

if (isset($_SESSION['current_admin_page'])){
    $script_name = $_SESSION['current_admin_page'];
}
else{
    $script_name = 'admin.php';
}
if ($orazio_edition === 0){
?>
<a href="<?= $script_name ?>" class="btn btn-sm btn-success fw-semibold px-3 mb-1 me-2">
            <i class="bx bx-edit fs-base ms-n1 me-1"></i>
            Edit this App
          </a>
<?php
}
} // end if
?>
          

          
        </div></div>

       
<?php
        /*
		if (false && $enable_authentication === 1){ // old box
			if ($show_logout_account_admin_box === 1){
			echo '<div class="app-info pull-right text-center"><div class="user-info">';
			if ($username_public_user !== '' && $username_public_user === $current_user){
			    //echo '<strong>User:</strong> '.$current_user.' <br> ';
			    echo ' <a class="btn btn-xm btn-primary" href="'.$site_url.'login.php?function=show_login_form" title="Logout"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a><br>';
			}
			else{

                echo '<strong>User:</strong> '.$current_user.' <br> ';

                if ($enable_user_password_modification === 1 && !is_ldap_user($_SESSION['logged_user_infos_ar']['id_user'])){
                    echo ' <a href="index.php?function=edit_account" class="btn btn-xs btn-primary" title="Your account"><i class="fa fa-user" aria-hidden="true"></i> Your account</a>';
                }
                echo ' <a class="btn btn-xs btn-danger" href="'.$site_url.'login.php?function=logout" title="Logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a><br>';
            }

			echo '</div>';

			if ($current_user_is_administrator === 1) {

				if (isset($_SESSION['current_admin_page'])){
					$script_name = $_SESSION['current_admin_page'];
				}
				else{
					$script_name = 'admin.php';
				}
                if ($orazio_edition === 0){
			 	    echo '<a href="'.$script_name.'?tablename='.urlencode($table_name).'" class="btn btn-xs btn-primary btn-block"><i class="fa fa-edit"></i>EDIT this App</a>';
			 	}
			} // end if
			echo '</div>';
			}

		}
        */
		if ($page_name === 'main'){
		    if (isset($hooks['custom_header_1'])){
                if ( substr($hooks['custom_header_1'],0,8) === 'dadabik_'){
                    call_user_func($hooks['custom_header_1']);
                }
            }
		}
	}


}
elseif ($page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator' || $page_name === 'data') {


	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" style="padding-right:10px"  role="presentation"><tr><td align="left">';

	if ($orazio_edition === 0){
	    echo '<img id="dadabik_logo" src="images/logo.png">';
	}
	else{
	    echo '<img id="dadabik_logo" src="images/logo_appify.svg" width="300">';

	}
	echo '</td><td align="right" style="padding-right: 20px;padding-bottom:8px"> <font size="6">DEV AREA</font>&nbsp;<br/>';

	if ($enable_authentication === 1){

		echo '<strong>User:</strong> '.$current_user.'&nbsp;&nbsp;&nbsp;';

	}

    if ($orazio_edition === 0){
    echo '<strong>DB:</strong> '.$db_name.'<br>';
    }


    $class = 'top_link_admin';
    if ($page_name === 'admin' && $function === 'show_status'){
		$class = 'top_link_admin_selected';
	}
    //echo '<a class="'.$class.'" href="admin.php?function=show_status&tablename='.urlencode($table_name).'">&nbsp;Status&nbsp;</a> | ';


if ($orazio_edition === 0){
    $class = 'top_link_admin';
    if ($page_name === 'admin' && $function === 'show_help'){
		$class = 'top_link_admin_selected';
	}

	echo '<a class="'.$class.'" href="admin.php?function=show_help&tablename='.urlencode($table_name).'">&nbsp;Help&nbsp;</a> | ';

    $class = 'top_link_admin';
	if ($page_name === 'admin' && ( $function === 'show_feedback' ||$function === 'show_feedback_2' ||$function === 'send_feedback') ){
		$class = 'top_link_admin_selected';
	}
	echo '<a class="'.$class.'" href="admin.php?function=show_feedback&tablename='.urlencode($table_name).'">&nbsp;Feedback&nbsp;</a> | ';
}   
	
    $class = 'top_link_admin';
	if ($page_name === 'admin' && $function === 'show_about'){
		$class = 'top_link_admin_selected';
	}
    echo '<a class="'.$class.'" href="admin.php?function=show_about&tablename='.urlencode($table_name).'">&nbsp;About/Upgrade&nbsp;</a>';

	if (!isset($_SESSION['check_update'])){

	    $_SESSION['check_update'] = 0;

	    $temp = get_current_version();
		$current_version_infos_ar =  explode(',', $temp);
		$current_release_version = $current_version_infos_ar[0];
		$current_release_version_2 = $current_version_infos_ar[2];
		$current_release_date = $current_version_infos_ar[1];
		$code_installation = $current_version_infos_ar[3];

		$temp = file_get_contents_3('https://dadabik.com/last_release.php');

		if ($temp !== false){

            $last_release_infos_ar = explode(',', $temp);
            $last_release_version = $last_release_infos_ar[0];
            $last_release_date = $last_release_infos_ar[1];

            if ($current_release_version != $last_release_version){
                $_SESSION['check_update'] = 1;
            }

		}

	}
    
	if ($orazio_edition === 0){
	if ($_SESSION['check_update'] === 1){
        echo ' <font color="red">(New version available!)</font>';
    }



	echo ' | <a class="top_link_admin" href="'.$site_url.'login.php?function=logout">Logout</a>';
	}




	echo '</td></tr></table>';
}
elseif ($page_name === 'install') {

	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" cellpadding="0"  role="presentation"><tr><td><img id="dadabik_logo" src="images/logo.png">';
	echo '</td><td align="right"> <font size="10">INSTALLATION</font>&nbsp;<br/>';
	echo '</td></tr></table>';
}
elseif ($page_name === 'upgrade') {

	echo '<tr class="table_interface_container_tr_logo_admin"><td><table width="100%" cellspacing="0" cellpadding="0"  role="presentation"><tr><td><img id="dadabik_logo" src="images/logo.png">';
	echo '</td><td align="right"> <font size="10">UPGRADE<font>&nbsp;<br/>';
	echo '</td></tr></table>';
}
?>

</td>
</tr>

<?php
if (false && $page_name === 'login') {
?>

<tr class="table_interface_container_tr_top_menu" >
<td>
	<table class="table_interface_container_table_top_menu"  role="presentation">

	<tr><td>&nbsp;</td></tr>
	</table>
</td>
</tr>

<?php
}
elseif ($page_name === 'main') {
?>



<?php
if (!isset($is_items_table) || $is_items_table !== 1){ // if it's an item table no insert search show results and show all links
?>

<?php
if ($function === 'search'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
<?php
if ($function === 'show_search_form'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<?php
if ($function === 'show_insert_form' || $function === 'insert'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
<?php
if ($enable_insert == "1"){
?>

<?php
}


foreach ($static_pages_ar as $static_page){
if ($static_page['enabled_static_page'] == 1){
if ($static_page['is_homepage_static_page'] == 'n'){


if ($function === 'show_static_page' && isset($id_static_page) && (int)$id_static_page === (int)$static_page['id_static_page']){
	$class_temp = 'static_pages_menu_active';
}
else{
	$class_temp = 'static_pages_menu';
}
}
}
}
?>
<?php

}
?>
<?php

if ($current_user_is_administrator === 1) {


	//echo '</td><td style="width:*"><a class="bottom_menu" href="admin.php">&nbsp;&nbsp;'.$normal_messages_ar["administration"].'&nbsp;&nbsp;</a>';




} // end if
?>

<?php if (count($languages_ar) > 1){ ?>

<?php } ?>

<?php if ($current_user_is_administrator === 0 && $current_user_is_beta === 1){ ?>

<?php } ?>



<?php
}
?>

<?php
// this is just for the admin area
if ($page_name === 'admin' || $page_name === 'interface_configurator' || $page_name === 'permissions_manager' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator' || $page_name === 'data') {
?>

<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_menu_admin_area" id="table_interface_container_table_top_menu_admin_area"  role="presentation">

	<tr>


  <?php
if ($page_name === 'admin' && $function === 'show_admin_home'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td ><a class="<?php echo $class_temp; ?>" href="admin.php?tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Home'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'data'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td nowrap ><a class="<?php echo $class_temp; ?>" href="data.php?tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Data'; ?>&nbsp;&nbsp;</a></td>


<?php
if ($page_name === 'tables_inclusion'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="tables_inclusion.php?tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Pages'; ?>&nbsp;&nbsp;</a></td>


<?php
if ($page_name === 'admin' && $function === 'show_menu_preview'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="admin.php?tablename=<?php echo urlencode($table_name); ?>&function=show_menu_preview">&nbsp;&nbsp;<?php echo 'Menu'; ?>&nbsp;&nbsp;</a></td>


<?php
if ($page_name === 'interface_configurator'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td nowrap ><a class="<?php echo $class_temp; ?>" href="internal_table_manager.php?tablename=<?php echo urlencode($table_name); ?>&reset_preview=1">&nbsp;&nbsp;<?php echo 'Forms configurator'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'permissions_manager'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?function=configure&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Permissions'; ?>&nbsp;&nbsp;</a></td>
<!--
<?php
if ($page_name === 'admin' && $function === 'show_layout_intro'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_layout_intro&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Layout'; ?>&nbsp;&nbsp;</a></td>
-->
<?php
if ($page_name === 'datagrid_configurator'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

 <td nowrap><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?function=show_basic_settings&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Layout'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'admin' && $function === 'show_automations'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_automations&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Automations'; ?>&nbsp;&nbsp;</a></td>

    <?php
if ($page_name === 'db_synchro'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<?php if ($orazio_edition === 0){ ?>
 <td nowrap><a class="<?php echo $class_temp; ?>" href="db_synchro.php?tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'DB Synchro'; ?>&nbsp;&nbsp;</a></td>
<?php } ?>
      

<?php
if ($page_name === 'admin' && $function === 'show_status'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
<td nowrap><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_status&tablename=<?= urlencode($table_name) ?>">&nbsp;&nbsp;App Status&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'admin' && $function === 'export_app'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<?php if ($orazio_edition === 0){ ?>
 <td nowrap><a class="<?php echo $class_temp; ?>" href="admin.php?function=export_app">&nbsp;&nbsp;<?php echo 'Export App'; ?>&nbsp;&nbsp;</a></td>
<?php } ?>
      <?php
if ($page_name === 'admin' && $function === 'show_users'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>





<td ><a class="<?php echo $class_temp; ?>" href="admin.php?function=show_users&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Users'; ?>&nbsp;<font color="#f1e89b">(NEW!)</font>&nbsp;&nbsp;</a></td>

<?php
/*
 */
?>
<?php if (true || $orazio_edition === 1){

    echo '<td >&nbsp;&nbsp;&nbsp;<a title="Exit the DEV Area and view the app you are building" class="view-app-button" href="'.$dadabik_main_file.'?function=search&tablename='.urlencode($table_name).'">&nbsp;&nbsp;'.$emoji_view_this_app_button.' VIEW this app&nbsp;&nbsp;</a></td>';
} ?>


<?php if (true){

if ($_SESSION['dev_mode'] == 'live'){
    $bgcolor_temp = '#b3c150';
    $bgcolor_temp = '#4B5563';
    $button_class = 'btn-success';
}
else{
    $bgcolor_temp = '#F1EB90';
    $bgcolor_temp = '#068757';
    $button_class = 'btn-secondary';
}

?>

<td style="width:100%"></td><td id="current_dev_mode_td" style="padding-right: 20px;" bgcolor="<?= $bgcolor_temp; ?>" align="right">&nbsp;&nbsp;&nbsp;&nbsp;DEV MODE: <b><span id="current_dev_mode_container"><?php echo $_SESSION['dev_mode']; ?></span></b>&nbsp;&nbsp;<a id="btn_change_dev_mode" class="btn btn-sm <?= $button_class ?> fw-semibold px-3 mt-1 mb-1 me-2" href="admin.php?function=dev_mode_settings" title="Change dev mode, pull, push and related settings"><i class="bx bx-cog fs-base ms-n1 me-1"></i> DEV mode settings</a></td>

<?php }else{ ?>

<td style="width:100%" align="right"></td>

<?php } ?>


</tr>
</table>

</td>
</tr>


<?php
// this is just for the permissions_manager menu
if  ($page_name === 'permissions_manager' && $enable_authentication === 1 && $enable_granular_permissions === 1) {
?>


<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_submenu_admin_area"  role="presentation">

	<tr>


<?php
if ($page_name === 'permissions_manager' && $function === 'configure'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?function=configure&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Configure group permissions'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'permissions_manager' && $function === 'copy_permissions'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?function=copy_permissions">&nbsp;&nbsp;<?php echo 'Copy Permissions'; ?>&nbsp;&nbsp;</a></td>
<?php
if ($page_name === 'permissions_manager' && $function === 'show_user_permissions'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?function=show_user_permissions&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Show user permissions'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'permissions_manager' && $function === 'show_public_user'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="permissions_manager.php?function=show_public_user&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Public access'; ?>&nbsp;&nbsp;</a></td>

<td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>

<?php
// this is just for the data menu
if  ($page_name === 'data') {
?>


<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_submenu_admin_area"  role="presentation">

	<tr>


<?php
if ($page_name === 'data' && $function === 'show_tables_views'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="data.php?function=show_tables_views&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Tables and Views'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'data' && $function === 'show_relationships'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="data.php?function=show_relationships&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Relationships'; ?>&nbsp;&nbsp;</a></td>
<?php
if ($page_name === 'data' && $function === 'show_settings'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>

<td ><a class="<?php echo $class_temp; ?>" href="data.php?function=show_settings&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Settings'; ?>&nbsp;&nbsp;</a></td>
<td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>





<?php
// this is just for the layout menu
if  ($page_name === 'datagrid_configurator') {
?>


<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_submenu_admin_area"  role="presentation">

	<tr>


<?php
if ($page_name === 'datagrid_configurator' && $function === 'show_basic_settings'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
 <td ><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?function=show_basic_settings&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Basic settings'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'datagrid_configurator' && $function === 'show_custom_css'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
 <td ><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?function=show_custom_css&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Custom CSS '; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'datagrid_configurator' && $function === 'show_datagrid_templates'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
<td ><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?function=show_datagrid_templates&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Record List Layout'; ?>&nbsp;&nbsp;</a></td>

<?php
if ($page_name === 'datagrid_configurator' && $function === 'show_layout_hooks'){
	$class_temp = 'bottom_menu_active';
}
else{
	$class_temp = 'bottom_menu';
}
?>
 <td ><a class="<?php echo $class_temp; ?>" href="datagrid_configurator.php?function=show_layout_hooks&tablename=<?php echo urlencode($table_name); ?>">&nbsp;&nbsp;<?php echo 'Layout hooks'; ?>&nbsp;&nbsp;</a></td>









<td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>



<?php
}
?>
<?php
// this is just for the install page
if ($page_name === 'install') {
?>

<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_menu_admin_area"  role="presentation"><tr>
 <td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>

<?php
// this is just for the upgrade page
if ($page_name === 'upgrade') {
?>

<tr class="table_interface_container_tr_top_menu_admin_area">
<td>
	<table class="table_interface_container_table_top_menu_admin_area"  role="presentation"><tr>
 <td style="width:100%"></td>
</tr>
</table>

</td>
</tr>
<?php
}
?>

<tr>
<td class="table_interface_container_td_content">
<?php
if ($page_name === 'main' || $page_name === 'admin' || $page_name === 'permissions_manager' || $page_name === 'interface_configurator' || $page_name === 'install' || $page_name === 'tables_inclusion' || $page_name === 'db_synchro' || $page_name === 'datagrid_configurator' || $page_name === 'data'){
	echo '<table  class="table_interface_container_table_content"  role="presentation">';
}
else{ // login
	echo '<table cellpadding="5" class="table_login_form"  role="presentation">';
}
?>
<tr>
<?php
if ($page_name === 'main'){
if (!isset($modal_mode) || $modal_mode === 0){

    $collapsed_class_text = '';
    if (isset($_SESSION['left_menu_is_collapsed']) && $_SESSION['left_menu_is_collapsed'] === 1){
        $collapsed_class_text = ' collapsed';
    }

	echo '<td class="td_left_menu'.$collapsed_class_text.'" id="td_left_menu" style="'.$display_style_menu.'">';
	echo '<div class="main-menu-container">';

	echo $menu_left_side;

    echo '</div>';


if ($ui_style === 'classic'){

    echo '<br><div align="right"><a href id="collapse_menu_link" title="'.$normal_messages_ar['collapse_sidebar'].'"><i class="bx bx-arrow-to-left page-sidebar-compact-none fs-4 me-2"></i></a></div>';
    echo '<div><a href id="expand_menu_link" style="display:none"><i class="bx bx-arrow-to-right page-sidebar-compact-none fs-4 me-2"></i></a></div>';
}
	echo '</td>';
}
}
?>
<td class="td_content" valign="top">
<div id="top_messages"></div>
