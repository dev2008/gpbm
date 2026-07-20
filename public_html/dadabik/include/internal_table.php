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

/*
0: label
1: fieldname
2: field type
3: size or options
4: help
5: section
6: don't know, probably not used anymore
7: id of the TR
8: javascript function onchange (optional)
9: id of the html element  (optional)
10: set to 1 if you don't want it in a new line (optional)
11: style of the input element (optional)
12: set to 1 if you want an empty option for select_custom (optional)
13: maxlength for text fields (optional, otherwise is 500)
14: disabled in demo
15: gives error when saving if demo

the arr_index doesn't matter, I can add a new field in the middle of the others and everything will work
*/

$ar_index = 0;

$int_fields_ar[$ar_index][0] = "(*) Label form: ";
$int_fields_ar[$ar_index][1] = "label_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "The text DaDaBIK will display in the forms as label of the field.<br/><br/>You can specify a single label (e.g. <i>name</i>) or multi-language labels (e.g. EN:name".FORM_CONFIGURATOR_SEPARATOR."IT:nome".FORM_CONFIGURATOR_SEPARATOR."DE:name".FORM_CONFIGURATOR_SEPARATOR."ES:nombre), see <strong>Multi-language support for labels, hints and tooltips</strong> in the documentation for further details.";
$int_fields_ar[$ar_index][5] = "Basic settings";


$ar_index++;
$int_fields_ar[$ar_index][0] = "Label grid (if different): ";
$int_fields_ar[$ar_index][1] = "label_grid_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "The text DaDaBIK will display in the grid as label of the field. Leave it blank if you want to use the same label for forms and grids. For charts and pivot tables, the label used is always the form label.<br/><br/> You can specify a single label (e.g. <i>name</i>) or multi-language labels (e.g. EN:name".FORM_CONFIGURATOR_SEPARATOR."IT:nome".FORM_CONFIGURATOR_SEPARATOR."DE:name".FORM_CONFIGURATOR_SEPARATOR."ES:nombre), see <strong>Multi-language support for labels, hints and tooltips</strong> in the documentation for further details.";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Position:";
$int_fields_ar[$ar_index][1] = "order_form_field";
$int_fields_ar[$ar_index][2] = "order_form_field";
$int_fields_ar[$ar_index][4] = "The position of this field in the form (e.g. 2 means the field will be the second field in the form). When you specify a new position, all the other field positions will be shifted correctly. This also affects the datagrid order.";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Field type:";
$int_fields_ar[$ar_index][1] = "type_field";
$int_fields_ar[$ar_index][2] = "select_custom";
//$int_fields_ar[$ar_index][3] = "text/textarea/password/insert_date/update_date/date/select_single/select_multiple_menu/select_multiple_checkbox/generic_file/image_file/ID_user/unique_ID";
$int_fields_ar[$ar_index][3] = "Text fields~text/textarea/rich_editor/Date and time fields~date/date_time/insert_date_time/update_date_time/List fields~select_single/select_single_radio/select_multiple_menu/select_multiple_checkbox/File fields~generic_file/image_file/camera/Barcode fields~barcode/qrcode/Other~ID_user/unique_ID";
$int_fields_ar[$ar_index][4] = "<ul><li><b>text:</b> a text box</li><br/>
    <li><b>textarea:</b> a textarea box</li><br/>
    <li><b>rich_editor:</b> a rich text editor that allows to easily insert/modify HTML content. THIS COULD LEAD TO SECURITY PROBLEMS. READ THE DOCUMENTATION BEFORE USING!</li><br/>
    <li><b>insert_date_time:</b> the current date and time will be automatically inserted into this field when you insert a new record in a table; an insert_date_time field is automatically excluded from the insert form. The corresponding database field type must be date or datetime</li><br/>
    <li><b>update_date_time:</b> the current date and time will be automatically inserted into this field when you update a record in a table; an update_date_time field is automatically excluded from the insert and edit forms. The corresponding database field type must be date or datetime</li><br/>
    <li><b>date:</b> a datepicker tool. The corresponding database field type must be date</li><br/>
    <li><b>date_time:</b> a datetimepicker tool. The corresponding database field type must be datetime</li><br/>
    <li><b>select_single:</b> a customizable dropdown menu</li><br/>
    <li><b>select_single_radio:</b> a customizable list of radio buttons.</li><br/>
    <li><b>select_multiple_menu:</b> a customizable dropdown menu with the possibility to choose more than one option. The corresponding database field type must be varchar.</li><br/>
    <li><b>select_multiple_checkbox:</b> a list of checkboxes, with the possibility to choose more than one. The corresponding database field type must be varchar.</li><br/>
    <li><b>generic_file:</b> an input field which allows the user to browse their local file system and upload a file. You need to enable uploads (\$enable_uploads = 1) and specify the allowed file extensions in config.php. The corresponding database field type must be varchar.</li><br/>
    <li><b>image_file:</b> the same as the above, but in this case DaDaBIK assumes the file is an image and shows it when the record is displayed.</li><br/>
    <li><b>camera:</b> like image_file, but for compatible devices (typically smartphones), the device\&apos;s camera opens directly when the user clicks <i>select file</i>. Read the <a target=\'_blank\' href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=configuration_upload\'>documentation</a> for further details.</li><br/>
    <li><b>barcode/qrcode:</b> generates a barcode or QR code for each record. By default, the encoded data is a string that uniquely identifies the record in your database (e.g. customers_12, where <i>customers</i> is the table name and <i>12</i> is the unique record ID). You can customize the generated data as needed. For details, limitations and requirements, please refer to the <a target=\'_blank\' href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=barcode_generation\'>documentation</a>.</li><br/>
    <li><b>ID_user:</b> the username of the current user will be automatically inserted into this field when you insert a new record; an ID_user field MUST BE excluded from the insert form. The corresponding database field type must be varchar.</li><br/> 
    <li><b>unique_ID:</b> a unique ID is generated and automatically inserted in this field when you insert a new record in a table; a unique_ID field must be excluded from the insert form. The corresponding database field type must be varchar(50). Probably you will rarely use this field type, pleaset notet that it is NOT the field type you should use for your Primary Key autoincrement fields, for those fields you can just use TEXT.</li></ul>";
    
$int_fields_ar[$ar_index][8] = "hide_show_interface_configurator_fields(this);";
$int_fields_ar[$ar_index][9] = 'e1';



$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Content type:";
$int_fields_ar[$ar_index][1] = "content_field";
$int_fields_ar[$ar_index][2] = "select_custom";
$int_fields_ar[$ar_index][3] = "alphabetic/alphanumeric/numeric/url/timestamp/email/html/phone";
$int_fields_ar[$ar_index][4] = "The content type determines which validation process DaDaBIK will perform during an insert/update and how a field value is displayed (formatting); select among:<ul>

    <li><b>alphabetic</b> Validation: only alphabetic characters allowed. Formatting: none.</li><br/>
    <li><b>alphanumeric:</b> Validation: all characters allowed. Formatting: none.</li><br/>
    <li><b>numeric:</b> Validation: only numeric characters allowed. Formatting: none.</li><br/>
    <li><b>url:</b> Validation: only URL with a correct syntax allowed. Formatting: field displayed as a URL link.</li><br/>
    <li><b>timestamp:</b> Validation: only integers allowed. Formatting: field displayed as date and time according to the <i>date_format</i> parameter in config.php</li><br/>
    <li><b>email:</b> Validation: only e-mail addresses with a correct syntax allowed. Formatting: field displayed as a mailto: link</li><br/>
    <li><b>html:</b> Validation: all characters allowed. Formatting: DaDaBIK doesn\'t perform a conversion from special characters to HTML entities with htmlspecialchars for the fields having this content type. For example, this content type can be used to enter a full custom URL in the standard HTML format or an HTML formatted text. THIS COULD LEAD TO SECURITY PROBLEMS. READ THE DOCUMENTATION BEFORE USING!</li><br/>
    <li><b>phone:</b> Validation: only phone numbers with a correct syntax (a + sign followed by numbers e.g. +39025689781) allowed. Formatting: none.</li>
    </ul><br/>Note that, for select_single, select_single_radio, select_multiple_menu and select_multiple_checkbox field types, the validation is not performed and, if the options are driven from another table (lookup field), the content types set for the linked fields are used to derive the formatting.
";
$int_fields_ar[$ar_index][7] = 20;


$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Required field";
$int_fields_ar[$ar_index][1] = "required_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "Choose if the field is required during insert and update operations.<br><br>For some field types that are automatically excluded from the insert and/or edit forms and whose value is auto-generated (e.g. insert_date_time, update_date_time, barcode, qrcode ... ) this parameter has no effect.";






$ar_index++;
$int_fields_ar[$ar_index][0] = "Custom list of options:";
$int_fields_ar[$ar_index][1] = "select_options_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify the options for a select_single or select_multiple field. Press ENTER after typing each option.<br/><br/> If you want the options to come from another table, use the lookup parameters instead. In that case, the custom options you enter here will be ignored.";
$int_fields_ar[$ar_index][5] = "Additional, field type specific, settings";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 1;
$int_fields_ar[$ar_index][9] = 'select_options_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Lookup table name";
$int_fields_ar[$ar_index][1] = "primary_key_table_field";
$int_fields_ar[$ar_index][2] = "primary_key_table_field";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "if a field is a select_single/select_multiple and the options must be driven from another table, specify here the name of that table.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 3;
$int_fields_ar[$ar_index][9] = 'primary_key_table_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Lookup table primary key field";
$int_fields_ar[$ar_index][1] = "primary_key_field_field";
$int_fields_ar[$ar_index][2] = "primary_key_field_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "if a field is a select_single/select_multiple and the options must be driven from another table (so the field can be considered foreign key for DaDaBIK, regardless of an actual dbms foreign key declaration), specify here the primary key of that table.<br/><br/>For select_multiple type: please note that if the values of the primary key field you are configuring here contain the character ~ (or the character set as FORM_CONFIGURATOR_SEPARATOR in config.php) DaDaBIK will not work properly.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 2;
$int_fields_ar[$ar_index][9] = 'primary_key_field_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Lookup table linked fields";
$int_fields_ar[$ar_index][1] = "linked_fields_field";
$int_fields_ar[$ar_index][2] = "linked_fields_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = " The fields in the primary key table you want to display. Imagine you have a table <i>albums</i> that contains information about CDs. In this table you have a field <i>ID_author</i> that &quot;links&quot; the table <i>authors</i>. If you want to display, for each record, <i>first_name_author</i> and <i>last_name_author</i> of <i>authors</i>, you have to use <i>ID_author</i> as lookup table primary key field, <i>authors_tab</i> as lookup table name, <i>first_name_author</i> and <i>last_name_author</i> as Lookup table linked fields.<br>Please note that you cannot specify as linked field a select_multiple field or a field that has, in turn, linked fields associated.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 4;
$int_fields_ar[$ar_index][9] = 'linked_fields_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Lookup table order by";
$int_fields_ar[$ar_index][1] = "linked_fields_order_by_field";
$int_fields_ar[$ar_index][2] = "primary_key_order_by_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "The linked field by which you want to order the items in the menu created by a select_single/select_multiple field.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 5;
$int_fields_ar[$ar_index][9] = 'linked_fields_order_by_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Lookup table order type";
$int_fields_ar[$ar_index][1] = "linked_fields_order_type_field";
$int_fields_ar[$ar_index][2] = "select_custom";
$int_fields_ar[$ar_index][3] = "/ASC/DESC";
$int_fields_ar[$ar_index][4] = "The order type (ASC or DESC, if you leave blank ASC is the default) to be used on the &quot;order by&quot; field.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 6;
$int_fields_ar[$ar_index][9] = 'linked_fields_order_type_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Where clause ";
$int_fields_ar[$ar_index][1] = "where_clause_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "if a field is a select_single/select_multiple and the options must be driven from other tables, you can specify here a where clause to use in the query, eg. <b>id_customer > 10</b>.<br/><br/>";


$int_fields_ar[$ar_index][4] .= "You can also use PHP variables which will be evaluated runtime. You just need to specify the variable between two <i>dadabik_var</i> tags and the variable will be parsed. For example here is a filter on a <i>group</i> field which depends on the id_group of the current user:<br/><br/>

<b>group = dadabik_var current_id_group dadabik_var</b><br/><br/>

Please use the PHP variables with cautions: since their values, UNESCAPED, will become part of an SQL query, you should make sure they cannot assume values that need to be escaped (e.g. quotes) to avoid unexpected behaviours and security issues.<br/><br/>
Variables must be available in the global scope and can\'t be an array. In the above example <i>current_id_group</i> is a variable already available in the global scope. If you need an array element and/or a variable which is not in the global scope already, you must assign the value to a (global scope) variable before the listboxes are created (for example in the startup function, see custom_functions.php, if you already have the value).<br/><br/> When you create a new variable or assign a value to an existing one in the DaDaBIK code, you must follow the variable naming rules (see custom_php_files/example.php for details). ";



$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 7;
$int_fields_ar[$ar_index][9] = 'where_clause_field';





$ar_index++;
$int_fields_ar[$ar_index][0] = "Cascade parent field";
$int_fields_ar[$ar_index][1] = "cascade_parent_field";
$int_fields_ar[$ar_index][2] = "cascade_parent_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "You have to fill this parameter and the next one (Cascade filter field) if the field is a <i>select_single</i> with values coming from a table and the values to show need to be filtered according to the choice made on another select_single field belonging to the same form. <br/><br/>Let\'s say for example we are configuring the table <i>customers</i>, having, among the others, some fields that represent the address of the customer, including a field <i>id_region</i> (select_single, populated by values coming from the table <i>regions</i>) and a field <i>id_country</i> (select_single, populated by values coming from the table <i>regions</i>). Let also say the table <i>regions</i> contains a field <i>id_country</i>, saying the country a region belongs to and that we want to reload the dropdown menu <i>region</i>  (produced by the select_single field <i>id_region</i>) according to what the user selects from the dropdown menu <i>country</i> (produced by the select_single field <i>id_country</i>): in this case we would specify here the name of the parent field (<i>id_country</i>) and in the next parameter the name of the correspondant filter field in the table <i>regions</i> (in this case, again <i>id_country</i>, but the name could be different).<br/><br/>Pleas note that the cascade feature only works in EDIT and INSERT forms (and in ADVANCED SEARCH forms if \$enable_cascade_advanced_search_form is set to 1) and that a cascade child field is not compatible with the options <i>Other choices allowed?</i> or the option <i>Use ajax to load options?</i> set to yes. Furhtermore, if you set a default value for the cascade parent field, the cascade action will not be triggered automatically, the cascade action is only triggered by a manual value change. ";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 21;
$int_fields_ar[$ar_index][9] = 'cascade_parent_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Cascade filter field";
$int_fields_ar[$ar_index][1] = "cascade_filter_field";
$int_fields_ar[$ar_index][2] = "cascade_filter_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "No help available, see help for Cascade parent field.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][7] = 22;
$int_fields_ar[$ar_index][9] = 'cascade_filter_field';


$ar_index++;
$int_fields_ar[$ar_index][0] = "Other choices allowed?";
$int_fields_ar[$ar_index][1] = "other_choices_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "Choose if the field, a select_single/select_single_radio one, can accept also values other than the pre-set options during an insert/update.<br><br>If the user add a different value, it will be included in the list of pre-set options. Note that if you fill the lookup parameters, the <i>other</i> value is used to insert a new record in the primary key table  <b>EVEN IF THE CURRENT USER HAS NOT INSERT PERMISSIONS ON THE PRIMARY KEY TABLE</b>.<br><br>The use of this option together with a foreign key field makes sense only if there is just one linked field and requires that <i>Primary key table</i> has a DaDaBIK unique field <b>auto-increment</b> (if you use PostgreSQL, it doesn\'t need to be auto increment) field.<br><br>The other choices option does not work if you set  <i>Use ajax to load options?</i> to yes. If you enable this option, the content type of the field must be compatible also with the content the user will add with the \'other\' textbox. Please also consider that such content is not checked against custom or standard validation when it is automatically inserted in the primary key table.<br/><br/>The &quot;Other&quot; option is not available during &quot;live edit&quot;, only in standard form.";
$int_fields_ar[$ar_index][7] = 9;


$ar_index++;
$int_fields_ar[$ar_index][0] = "Show link to record?";
$int_fields_ar[$ar_index][1] = "show_lookup_link_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "In results grid and details page, if you choose YES, an HTML link to the relatd linked record will be displayed close the value showed. E.g. in a table <i>sales</i> you might have an <i>id_customer</i> field, <i>select_single</i>, and show first and last name of the customer. If you choose yes here, the user will also see an HTML link close to the customer\'s name; the link allows the user to open a window displaying ALL the details of that customer (e.g. name, address, phone, ...). This option, of course, works only if the options are driven from another table (lookup field).";
$int_fields_ar[$ar_index][7] = 23;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Search-as-you-type";
$int_fields_ar[$ar_index][1] = "chosen_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "Choose yes if the number of options is high and you want to show them as suggestions based on what you type.";
$int_fields_ar[$ar_index][7] = 24;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Use ajax to load options?";
$int_fields_ar[$ar_index][1] = "chosen_ajax_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "Choose yes if your menu has a lot of options and you do not want the browser to load all the options immediately, you want the browser to load options via ajax according to what the user is searching for. <br><br>This option has effect only for lookup fields (not if you use a custom list of options) and only if you selected Yes for the previous paramter, <i>Search-as-you-type</i> and it is not compatible with cascade drop-down fields so if you set <i>Cascade parent field</i>, the Ajax loading must be set to No. <br><br>If you use PostgreSQL, all your <i>Lookup table linked fields</i> must have a <i>String data type</i> (e.g. Varchar or Text) in your Database, otherwise the Ajax loading will not work correctly.";
$int_fields_ar[$ar_index][7] = 25;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Prefix:";
$int_fields_ar[$ar_index][1] = "prefix_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "for text, textarea and rich_editor fields you can choose a prefix default value for your field, e.g. &quot;http://&quot; if your field need to be filled with a Web url; the prefix will be displayed directly in the insert form, but if the user doesn\'t fill-in the field it will be considered as blank.";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 2;
$int_fields_ar[$ar_index][7] = 10;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Default value:";
$int_fields_ar[$ar_index][1] = "default_value_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "For text, textarea, rich_editor, date, date_time, select_single and select_single_radio fields you can choose a default value for your field; the value will be displayed directly in the insert form or the corresponding option will be selected. A default value can be set even if the corresponding field is not present in the insert form.<br/><br/>
Furthermore, you can specify a default value through an SQL SELECT query; in this case the default value must start with SQL:SELECT (uppercase), for example SQL:SELECT MAX(id_customer) FROM customers; when you use a SQL: default value, instead of taking a static default value, the query is executed and the first selected field of the first row returned is used as default value. Don\'t add a semicolumn at the end of the query.

<br/><br/>Finally, you can specify a default value even using a custom function (only for DaDaBIK Enterprise/Platinum); in this case, specify here the <b>name</b> of the function to be used to compute the default value. The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_functions.php</b>. The <b>/include/custom_functions.php</b> contains all the instructions to write it correctly.

<br/><br/>For default values used with select_single and select_single_radio fields, the  SQL SELECT query and the custom funciton option only work if the field is a lookup field.  
";

$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 2;
$int_fields_ar[$ar_index][7] = 11;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Width (chars):";
$int_fields_ar[$ar_index][1] = "width_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "2";
$int_fields_ar[$ar_index][4] = "The width of an input box (only text, textarea and rich_editor field types are affected).";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 2;
$int_fields_ar[$ar_index][7] = 12;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Height (chars):";
$int_fields_ar[$ar_index][1] = "height_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "2";
$int_fields_ar[$ar_index][4] = "The height of an input box (only textarea and rich_editor field tyeps are affected).";
$int_fields_ar[$ar_index][7] = 13;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Maxlength:";
$int_fields_ar[$ar_index][1] = "maxlength_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "4";
$int_fields_ar[$ar_index][4] = "The maximum number of characters allowed in the input box (only text, textarea, rich_editor boxes and select_single - just for the &quot;other&quot; option - field types are affected ).";
$int_fields_ar[$ar_index][7] = 14;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Hint:";
$int_fields_ar[$ar_index][1] = "hint_insert_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "The text of a hint for the user that will appear during the insert/update procedure near the input field.<br/><br/>You can specify a single hint (e.g. &quot;name&quot;) or multi-language hints (e.g. &quot;EN:name".FORM_CONFIGURATOR_SEPARATOR."IT:nome".FORM_CONFIGURATOR_SEPARATOR."DE:name".FORM_CONFIGURATOR_SEPARATOR."ES:nombre&quot;), see <strong>Multi-language support for labels, hints and tooltips</strong> in the documentation for further details.<br/><br/>Please note that the tooltip does not appear during &quot;live edit&quot;, only in standard forms.";
$int_fields_ar[$ar_index][7] = 15;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Tooltip:";
$int_fields_ar[$ar_index][1] = "tooltip_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "A tooltip which appears close to text, textarea and file fields when the mouse pointer is hover the field. <br/><br/>You can specify a single tooltip (e.g. &quot;name&quot;) or multi-language tooltips (e.g. &quot;EN:name".FORM_CONFIGURATOR_SEPARATOR."IT:nome".FORM_CONFIGURATOR_SEPARATOR."DE:name".FORM_CONFIGURATOR_SEPARATOR."ES:nombre&quot;), see <strong>Multi-language support for labels, hints and tooltips</strong> in the documentation for further details.<br/><br/>Please note that the tooltip does not appear during &quot;live edit&quot;, only in standard forms.";
$int_fields_ar[$ar_index][7] = 16;


if ($enable_granular_permissions === 0 || $enable_authentication === 0){
	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the search form?";
	$int_fields_ar[$ar_index][1] = "present_search_form_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "No help available.";
	$int_fields_ar[$ar_index][5] = "Forms presence";
	$int_fields_ar[$ar_index][7] = 17;


	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the results page?";
	$int_fields_ar[$ar_index][1] = "present_results_search_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "No help available.";

	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the details page?";
	$int_fields_ar[$ar_index][1] = "present_details_form_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "No help available.";

	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the insert form?";
	$int_fields_ar[$ar_index][1] = "present_insert_form_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "If the field is auto-increment you should choose N.";

	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the edit form?";
	$int_fields_ar[$ar_index][1] = "present_edit_form_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "If the field is auto-increment you should choose N.";

	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the CSV?";
	$int_fields_ar[$ar_index][1] = "present_csv_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "No help available.";


	$ar_index++;
	$int_fields_ar[$ar_index][0] = "Field present in the Quick Search Form?";
	$int_fields_ar[$ar_index][1] = "present_filter_form_field";
	$int_fields_ar[$ar_index][2] = "select_yn";
	$int_fields_ar[$ar_index][4] = "The Quick Search Form appears at the top of the Results Grid for a table.<br><br>Set Yes/No to add or remove fields from the Quick Search Form.<br><br>If Quick Search is disabled for all fields in a table, the OmniSearch form (a single-field, search-all textbox) will appear instead, allowing you to search across all compatible fields.";
}


$ar_index++;
$int_fields_ar[$ar_index][0] = "Show ONLY if (conditional):";
$int_fields_ar[$ar_index][1] = "show_if_field_field";
$int_fields_ar[$ar_index][2] = "select_custom";
$int_fields_ar[$ar_index][3] = "5";
$int_fields_ar[$ar_index][4] = "You can show a field in your forms according to a conditional rule you set here, something like: show the field <b>state_customer</b> only if the field <b>country_customer</b> is equal to \'USA\'.<br>
This parameter allows you to set simple rules, if you need more complex rules, you can write a conditional field function in PHP (see Custom functions at the bottom of this page).<br><br>
Here are some rules to consider:

<ul>
<li> the input field (in the country/state example above, the field country_customer):<br>
1) cannot be disabled in the edit form, cannot be a calculated or auto-generated (e.g. barcode) field and cannot be a select_multiple or file field. If one of these conditions is verified, yoru condition will not work as expected;<br>
2) if it\'s a select_single_radio and the user does not select an option, the value of the field will be evaluated as an empty string<br><br>

<li> your condition just hides/displays the field in the form, but the PERMISSIONS tab in the admin section (or \'Forms presence\' in form configurator if you disabled granlar permissions) is still the place where you decide if a field will be considered or not for INSERT and UPDATE operations. For example, in the above \'state/country\' case, for the field state_customer you should set YES for both CREATE and EDIT so that insert (or update) operations will use the \'state_customer\' field value (yes, they will use it even if the field is hidden, but this shouldn\'t be a problem, if the user didn\'t fill it, it will be empty or null; it can be a problem, however, if the user fills a field with a value and then makes the field disappear: even if it is not in the form, the field will pass its value to the insert or update process).<br><br>

<li> if the field you are setting the condition for is a calculated or auto-generated (e.g. barcode) field, even if the field is hidden in the form, its value will be computed  during insert and update operations.<br><br>

<li> this normally doesn\'t cause any problem but consider that values in your conditions are always evaluated as strings, even if they are numbers. E.g. if your rule is: show the <b>discount_product</b> field only if the <b>price_product</b> is > 50, both the value of price_product and the value \'50\' are evaluated as strings.
</ul>
";
//$int_fields_ar[$ar_index][7] = 20;
$int_fields_ar[$ar_index][5] = "No-code logic";
$int_fields_ar[$ar_index][11] = "width:150px;display:inline";



$ar_index++;
$int_fields_ar[$ar_index][0] = "";
$int_fields_ar[$ar_index][1] = "show_if_operator_field";
$int_fields_ar[$ar_index][2] = "select_custom";
$int_fields_ar[$ar_index][3] = "=/>/</<=/>=/<>/is null/is not null/is empty/is not empty";
$int_fields_ar[$ar_index][4] = "";
$int_fields_ar[$ar_index][8] = "hide_show_show_if_value_field(this);";
$int_fields_ar[$ar_index][9] = "show_if_operator_field";
$int_fields_ar[$ar_index][10] = "1";
$int_fields_ar[$ar_index][11] = "width:125px;display:inline";
//$int_fields_ar[$ar_index][7] = 20;
$int_fields_ar[$ar_index][12] = "1";



$ar_index++;
$int_fields_ar[$ar_index][0] = "";
$int_fields_ar[$ar_index][1] = "show_if_value_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "5";
$int_fields_ar[$ar_index][4] = "";
//$int_fields_ar[$ar_index][7] = 20;
$int_fields_ar[$ar_index][9] = "show_if_value_field";
$int_fields_ar[$ar_index][10] = "1";
$int_fields_ar[$ar_index][11] = "width:200px;display:inline";
$int_fields_ar[$ar_index][13] = "50";


$ar_index++;

$int_fields_ar[$ar_index][0] = "Formula field:";
$int_fields_ar[$ar_index][1] = "formula_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][7] = 26;
$int_fields_ar[$ar_index][9] = 'formula_field';
$int_fields_ar[$ar_index][4] = "A <b>formula field</b> offers a no-code, simplified alternative to calculated fields, allowing dynamic field values without custom coding.<br><br>For instance, in a product catalog table that includes name, price, and discount for each product, a <i>final_price</i> field can be set up with a formula to calculate the final price, including taxes:<br><br>

<code>({price}-{discount}) * 1.05</code><br><br>

In this case, the formula calculates the final_price value with a 5% sales tax.<br><br>Formula fields support a variety of operations and functions:<ul><li>You can access form field values with {field_name}<li>you can access linked table values with {table_name.field_name}<li>You can use aggragate functions (e.g. SUM(), COUNT(), ... ) <li>You can use basic mah operations, parenthesis and date functions (DAY(), MONTH(), YEAR(), DAYS_DIFF()). </ul>For all the details, check the <a target=\'blank\' href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=low_coding_calculated\'>documentation</a>. For more complex calculated fields, use calculated field functions.";
$int_fields_ar[$ar_index][13] = "255";





$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) New line after this field (insert form)?";
$int_fields_ar[$ar_index][1] = "insert_new_line_after_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "In the insert form, do you want the next field after this on a new line?";
$int_fields_ar[$ar_index][5] = "Form layout (New lines and Separators)";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) New line after this field (search form)?";
$int_fields_ar[$ar_index][1] = "search_new_line_after_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "In the search form, do you want the next field after this on a new line?";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) New line after this field (details page)?";
$int_fields_ar[$ar_index][1] = "details_new_line_after_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "In the details page, do you want the next field after this on a new line?";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) New line after this field (edit form)?";
$int_fields_ar[$ar_index][1] = "edit_new_line_after_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "In the edit form, do you want the next field after this on a new line?";



$ar_index++;
$int_fields_ar[$ar_index][0] = "Separator before this field (insert form):";
$int_fields_ar[$ar_index][1] = "insert_separator_before_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "In the insert form, do you want a titled separator before this field? If yes, type the title here";

$ar_index++;
$int_fields_ar[$ar_index][0] = "Separator before this field (search form):";
$int_fields_ar[$ar_index][1] = "search_separator_before_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "In the search form, do you want a titled separator before this field? If yes, type the title here";

$ar_index++;
$int_fields_ar[$ar_index][0] = "Separator before this field (details page):";
$int_fields_ar[$ar_index][1] = "details_separator_before_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "In the details page, do you want a titled separator before this field? If yes, type the title here";

$ar_index++;
$int_fields_ar[$ar_index][0] = "Separator before this field (edit form):";
$int_fields_ar[$ar_index][1] = "edit_separator_before_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "In the edit form, do you want a titled separator before this field? If yes, type the title here";


/*
$ar_index++;
$int_fields_ar[$ar_index][0] = "Separator char for options, linked fields and master/details:";
$int_fields_ar[$ar_index][1] = "separator_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "2";
$int_fields_ar[$ar_index][4] = "This is the separator characther used here to separate values in the <i>Option to include</i>, <i>Linked fields</i>, <i>Linked items table names</i> and <i>Items table foreign key field names</i> parameters. In most of the case you can safely leave (and use) the default one, ~";
$int_fields_ar[$ar_index][6] = array();
$int_fields_ar[$ar_index][6][] = 1;
$int_fields_ar[$ar_index][5] = 'Other';
*/

$ar_index++;
$int_fields_ar[$ar_index][0] = "Calculated field function:";
$int_fields_ar[$ar_index][1] = "calculated_function_field";
if ($use_listbox_for_custom_function_selection === 1){
    $int_fields_ar[$ar_index][2] = "select_custom";
}
else{
    $int_fields_ar[$ar_index][2] = "text";
}
$int_fields_ar[$ar_index][3] = "50";

$int_fields_ar[$ar_index][4] = '';



$int_fields_ar[$ar_index][4] .= "Specify here the <b>name</b> of the function (if any) to be used to compute the value of this field. If this parameter is set, the field is considered a <b>calculated field</b> and its value is not directly entered by the user but calculated according to this function. For example you might have a total_price field, which is calculated according to the values of other fields, e.g. as price + tax.<br><br>Please note that for this kind of fields, DaDaBIK does not execute the standard checks about length, requiredness and duplication before insert/update operations.<br/><br/>The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_functions.php</b>. The <b>/include/custom_functions.php</b> contains all the instructions to write it correctly.";

$low_code_separator_text = 'Low-code logic (Custom functions)';


if ($orazio_edition === 1){

    $low_code_separator_text =  'Custom functions (LOW-CODE)<span id="confirmation_message_container"><div class="msg_error" id="alert_message"><p>Here you can add your custom PHP/Javascript custom code.<br>For example you can make a field <i>calculated</i>, expressing the formula in PHP <br> or write a Javascript function that is fired when a particualr event occurs. <br><br>This section is <b>DISABLED</b> when you use an '.$orazio_name.' free account.<br>You need to <a href="index.php?function=deploy">publish and deploy</a> this application to enable it.</h3></div></span>';
}

$int_fields_ar[$ar_index][5] = $low_code_separator_text;

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;



$ar_index++;
$int_fields_ar[$ar_index][0] = "Custom validation function:";
$int_fields_ar[$ar_index][1] = "custom_validation_function_field";
if ($use_listbox_for_custom_function_selection === 1){
    $int_fields_ar[$ar_index][2] = "select_custom";
}
else{
    $int_fields_ar[$ar_index][2] = "text";
}
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify here the <b>name</b> of the function to be used to validate this field\'s data. This function override the built-in validation functions executed according to the content type (if any).<br/><br/>The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_functions.php</b>. The <b>/include/custom_functions.php</b> contains all the instructions to write it correctly.<br/><br/> <b>Please note</b> that these functions can be applied to: text, textarea, rich_editor, date, date_time, select_single* and select_multiple* fields. ";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;

$ar_index++;
$int_fields_ar[$ar_index][0] = "Custom formatting function:";
$int_fields_ar[$ar_index][1] = "custom_formatting_function_field";
if ($use_listbox_for_custom_function_selection === 1){
    $int_fields_ar[$ar_index][2] = "select_custom";
}
else{
    $int_fields_ar[$ar_index][2] = "text";
}
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify here the <b>name</b> of the function (if any) to be used to format this field\'s data in the datagrid view and details view. This function override the built-in formatting functions executed according to the content type (if any).<br/><br/>The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_formatting_functions.php</b>.<br><br>Refer to the <a target=\'blank\' href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=low_coding_formatting\'>documentation</a> for detailed instructions on creating custom formatting functions.<br><br>Custom formatting functions do not apply to calendar views.";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;


$ar_index++;
$int_fields_ar[$ar_index][0] = "Custom JS formatting code:";
$int_fields_ar[$ar_index][1] = "js_custom_formatting_function_field";
$int_fields_ar[$ar_index][2] = "textarea";
$int_fields_ar[$ar_index][3] = "2000";
$int_fields_ar[$ar_index][4] = "A <b>JavaScript custom formatting function</b> allows you to dynamically change how a field value is displayed (in the results grid and details page), using custom client-side logic.<br><br>

This feature is useful when you want to enrich the visual presentation of data — for example, by adding emojis, colors, labels, or context based on other field values.<br><br>

The JavaScript function has access to:<br><br>

value → the current field’s value<br><br>

record → an object containing all field values of the current record<br><br>

<b>Example #1:</b> Add a dollar sign in front of the value.<br>
<div style=\'color:black;background:white;border:solid
      gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'>
      return \'$\' + value;

</div><br><br>

<b>Example #2:</b> Add a visual clue to the quantity available depending on the value itself and on the value of another field.
<div style=\'color:black;background:white;border:solid
      gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'>
      if (value === 0) {<br>
  &nbsp;&nbsp;return \'🔴 Out of stock\';<br>
} else if (value < 10 && record[\'supplier_status\'] === \'delayed\') {<br>
  &nbsp;&nbsp;return \'🟠 Low + delayed - \' + value;<br>
} else if (value < 10 ) {<br>
  &nbsp;&nbsp;return \'🟠 Low - \' + value;<br>
} else {<br>
  &nbsp;&nbsp;return \'🟢 Available \' + value;<br>
}<br>

</div><br><br>

<b>Example #3:</b> Dynamic star ratings display from numeric score
<div style=\'color:black;background:white;border:solid
      gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'>
      const rating = Math.round(value);<br>
return \'⭐\'.repeat(rating) + \' (\' + value + \')\';<br>


</div><br><br>

The function should return the formatted string or HTML you want to display.<br><br>
Only the function body should be written — do not include the function(...) {} wrapper.<br><br>

This feature is only for formatting: it does not affect stored values in the database.<br><br>

In case a PHP custom formatting function is already set for a field, the JS custom formatting function is ignored.<br>Please note that this feature is not compatible with PDF export, if you need to export to PDF, you must use PHP custom formatting functions.<br><br>

You can find additional examples in the <a target=\'_blank\' href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=low_coding_js_formatting\'>documentation</a><br><br>Custom JS formatting code does not apply to calendar views.";

$int_fields_ar[$ar_index][9] = "js_custom_formatting_function_field";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;


$ar_index++;
$int_fields_ar[$ar_index][0] = "Custom CSV formatting function:";
$int_fields_ar[$ar_index][1] = "custom_csv_formatting_function_field";
if ($use_listbox_for_custom_function_selection === 1){
    $int_fields_ar[$ar_index][2] = "select_custom";
}
else{
    $int_fields_ar[$ar_index][2] = "text";
}
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify here the <b>name</b> of the function (if any) to be used to format this field\'s data in the CSV view. This function override the built-in formatting functions executed according to the content type (if any).<br/><br/>The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_functions.php</b>. The <b>/include/custom_functions.php</b> contains all the instructions to write it correctly.";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;


$ar_index++;
$int_fields_ar[$ar_index][0] = "Conditional field function:";
$int_fields_ar[$ar_index][1] = "custom_required_function_field";

if ($use_listbox_for_custom_function_selection === 1){
    $int_fields_ar[$ar_index][2] = "select_custom";
}
else{
    $int_fields_ar[$ar_index][2] = "text";
}
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify here the <b>name</b> of the function (if any) to be used to evaluate if this field needs to be displayed in the current form or not, for example according to the value of other fields.<br><br>The same function can also be used to determines if the field is <b>required</b> or not, overriding the parameter <i>Is the field a required one?</i>. This function only affects insert end edit forms.<br><br>

In most of the cases you do not need this function because:<br><br>

1) You can set if a field is displayed or not, in which forms and for which user groups, using the tab <b>permissions</b><br><br>

2) You can set if a field is required or not using the <i>Is the field a required one?</i> parameter<br><br>

in some other cases, however, you might need to use this function, for example because you want to hide (or make <i>not required</i>) a field <i>on the fly</i>, when a user selects a particular value from another field (e.g. show the listbox \'States\' only if the user selects \'USA\' as \'Country\').<br><br>

Example:<br>
<div style=\'color:black;background:white;border:solid
      gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;\'>function
      dadabik_display_required_state_customer(&dollar;params){<br>
      &nbsp;&nbsp; &nbsp;if (&dollar;params[\'country_customer\'] === \'USA\'){<br>
      &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &dollar;field[\'show\'] = true;<br>
      &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &dollar;field[\'required\'] = true;<br>
      &nbsp;&nbsp; &nbsp;}<br>
      &nbsp;&nbsp; &nbsp;else{<br>
      &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &dollar;field[\'show\'] = false;<br>
      &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &dollar;field[\'required\'] = false;<br>
      &nbsp;&nbsp; &nbsp;}<br>
      &nbsp;&nbsp; &nbsp;return &dollar;field;<br>
      }</div>

<br>The function name must start with <b>dadabik_</b> and you must write the function code in <b>/include/custom_functions.php</b>. The <b>/include/custom_functions.php</b> contains all the instructions to write it correctly.<br><br>Further details in the <a href=\'https://dadabik.com/open_manual_from_local.php?installed_dadabik_version=".$installed_dadabik_version."&chapter=low_coding_required\' target=\'_blank\'>documentation</a> (this is a link to the online documentation, if you are not running the latest DaDaBIK, check your local documentation, it might differ). ";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;


$ar_index++;
$int_fields_ar[$ar_index][0] = "JS event functions:";
$int_fields_ar[$ar_index][1] = "js_event_functions_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify here the <b>name</b> of a Javascript function (if any) to be used when a particular event occurs on the field; you have to specify both the <b>event name</b> and the <b>function name</b> separated by column. For example, if you want to automatically make uppercase the content of a field while the user is typing, you should specify <b>onkeyup:dadabik_capitalize</b> and then write your dadabik_capitalize function in <b>/include/custom_functions.js</b> (You will find a function example in <b>/include/custom_functions.js</b>).<br/><br/>You can also add multiple events separated by semicolumn, for example <b>onfocus:dadabik_function_1;onblur:dadabik_function2</b><br/><br/>The function names must start with <b>dadabik_</b><br/><br/>Please note that not all the Javascript events are compatible with all the field type; tipically the field types used in combination with Javascript events are: text, textarea, select_single and select_multiple_menu; onchange events are not compatible with fields which are also parents in a cascade Drop-down lists. <br/><br/>The JS function is only executed for insert and edit forms, not for search forms. <br/><br/>The JS function is also executed during &quot;live edit&quot;, take that into consideration when you write your code (the standard form and maybe some other fields are not available in the results grid) ";

$int_fields_ar[$ar_index][14] = 0;
$int_fields_ar[$ar_index][15] = 1;


$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Check for duplicates before inserting";
$int_fields_ar[$ar_index][1] = "check_duplicated_insert_field";
$int_fields_ar[$ar_index][2] = "select_yn";
$int_fields_ar[$ar_index][4] = "Choose if the field value should be checked for possible duplication before Insert operations (the check is not performed before update operations). This feature does not work and can create unexpected issues with file, date, date_time, insert_date_time and update_date_time field types and with all fields which are hidden in the insert form.";
$int_fields_ar[$ar_index][5] = "Other settings";

$ar_index++;
$int_fields_ar[$ar_index][0] = "(*) Search operators:";
$int_fields_ar[$ar_index][1] = "select_type_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "50";
$int_fields_ar[$ar_index][4] = "Specify is_equal, is_different, contains, doesnt_contain, starts_with, ends_with, greater_than, less_than, greater_equal_than, less_equal_than, is_null, is_not_null, is_empty, is_not_empty, between or a combination of these operators. <br>Examples: if you specify just &quot;contains&quot; DaDaBIK will always use the contains operator for this field during the search; if you specify both &quot;is_equal&quot; and &quot;contains&quot; DaDaBIK will create for this field a listbox with the operators <i>is equal</i> and <i>contains</i>, the user can then choose the preferred one during each search operation.<br/>Please note that you must use operators compatible with your field type, otherwise users get unhandled errors during search operations. Here is a list of incompatibility:<br/><br/>1) <i>select_single</i> fields are compatible just with <i>is_null</i>, <i>is_not_null</i>, <i>is_empty</i>, <i>is_not_empty</i>, <i>is_equals</i> and <i>is_different</i><br/><br/>2) <i>date, date_time, insert_date_time and update_date_time fields</i> are compatible just with <i>is_null</i>, <i>is_not_null</i>, <i>greater_than</i>, <i>less_than</i>, <i>greater_equal_than</i>, <i>less_equal_than</i>, <i>is_equals</i>, <i>is_different</i> and <i>between</i><br/><br/>3) <i>select_multiple_menu and select_multiple_checkbox</i> are compatible just with <i>is_null</i>, <i>is_equal</i> and <i>is_different</i>; please note that when you search a value in a select_multiple_* field using <i>is_equal</i>, you will find records having all the selected value(s) for the field (plus, optionally, other values).";
$int_fields_ar[$ar_index][9] = 'select_type_field';

$ar_index++;
$int_fields_ar[$ar_index][0] = "Min width (in px) of the results grid column:";
$int_fields_ar[$ar_index][1] = "min_width_results_grid_column_field";
$int_fields_ar[$ar_index][2] = "text";
$int_fields_ar[$ar_index][3] = "5";
$int_fields_ar[$ar_index][4] = "The min width (in px) of the corresponding column in the results grid. If you leave it blank, the browser will arrange the width of each column. Please note that you may need to adjust the parameter $word_wrap_col in config.php, that affects how the content is distributed in a cell of the results grid.";


$ar_index++;
$int_fields_ar[$ar_index][0] = "Items table name:";
$int_fields_ar[$ar_index][1] = "items_table_names_field";
$int_fields_ar[$ar_index][2] = "items_table_names_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "You should fill this property only for the primary key of a table, when you want to enable a master/details view.<br><br>For example imagine you have an albums (id_album, title_album) table and a songs (id_song, id_album, title_song) table; if you want to display, in the album detail view, also the list of the linked songs, you have to set the two parameters for Master/Details view for the field <i>id_album</i> of the table <i>albums</i>. In particular, set <i>songs</i> as <i>items table name</i> and <i>id_album</i> as <i>Items table foreign key field name</i> (see below). Please note that each table can have more than one items table.<br><br>It is also important to highlight that, in this example, <i>id_album</i> in <i>songs</i> should be a select_single (lookup) field with values driven from albums (see field type for information about select_single fields).";
$int_fields_ar[$ar_index][5] = "Master/details views (Subforms)";
$int_fields_ar[$ar_index][9] = "items_table_names_field";


$ar_index++;
$int_fields_ar[$ar_index][0] = "Items table foreign key field name:";
$int_fields_ar[$ar_index][1] = "items_table_fk_field_names_field";
$int_fields_ar[$ar_index][2] = "items_table_fk_field_names_field";
$int_fields_ar[$ar_index][3] = "";
$int_fields_ar[$ar_index][4] = "No help available, see help for <i>Items table name</i>";
$int_fields_ar[$ar_index][9] = "items_table_fk_field_names_field";

?>
