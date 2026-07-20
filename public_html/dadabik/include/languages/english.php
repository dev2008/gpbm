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
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Insert a new item",
	"quick_search"    => "Quick search",
	"search/update/delete" => "Search/update/delete items",
	"insert_short"    => "Create new",
	"search_short" => "Search",
	"advanced_search" => "Advanced Search",
	"insert_anyway"    => "Insert anyway",
	"search"    => "Search for items",
	"update"    => "Save",
	"ext_update"    => "Update your profile",
	"yes"    => "Yes",
	"no"    => "No",
	"go_back" => "Go back",
	"edit" => "Edit",
	"delete" => "Delete",
	"details" => "Details",
	"insert_as_new" => "Insert as new",
	"multiple_inserts" => "Multiple inserts",
	"change_table" => "Change table",
	"search_includes_following_fields" => 'The search includes the following fields:',
);

// normal messages
$normal_messages_ar = array (
"cant_edit_record_locked_by" => "You can't edit this item, the item is locked by user: ",
"lost_locked_not_safe_update" => "You don't have or have lost your lock on this item, it is not safe to update, please start again the edit",
"insert_item" => "Create new item",
"show_all_records" => "Show all items",
"show_records" => "Show items",
"ldap_user_dont_update" => "This is an imported user (LDAP, Google, ...): his/her group is the only information you should change, if needed.",
"leave_blank_keep_current_password" => "Leave it blank to keep the current password",
"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).",
"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users",
"remove_search_filter" => "remove search filter",
"set_default_search_filter" => "set default search filter",
"logout" => "Logout",
"top" => "Top",
"last_search_results" => "Last search results",
"show_all" => "Show all",
"home" => "Home",
"select_operator" => "Select the operator:",
"all_conditions_required" => "All the conditions required",
"any_conditions_required" => "Any of the conditions required",
"all_contacts" => "All contacts",
"removed" => "removed",
"please" => "Please",
"and_check_form" => "and check the form.",
"and_try_again" => "and try again.",
"none" => "none",
"are_you_sure" => "Are you sure?",
"delete_all" => "delete all",
"really?" => "Really?",
"delete_are_you_sure" => "You are going to delete the item below, are you sure?",
"required_fields_missed" => "You haven't filled out some required fields.",
"alphabetic_not_valid" => "You have inserted a/some number/s into an alphabetic field.",
"numeric_not_valid" => "You have inserted a/some non-numeric characters into a numeric field.",
"email_not_valid" => "An e-mail address you have inserted is not valid.",
"timestamp_not_valid" => "A timestamp you have inserted is not valid.",
"url_not_valid" => "An url you have inserted is not valid.",
"phone_not_valid" => "A phone number you have inserted is not valid.<br>Please use the \"+(country code)(area code)(number)\" format e.g. +390523599318, 00390523599318, 0523599318.",
"date_not_valid" => "You have inserted one or more not valid dates.",
"entered_value_not_valid" => "The entered value is not valid.",
"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin",
"similar_records" => "The items below seem similar to the one you want to insert (I'll show max ".$number_duplicated_records." similar items, there could be more).<br>What do you want to do?",
"similar_records_short" => "The items below seem similar to the one you want to insert (I'll show max ".$number_duplicated_records." similar items, there could be more).",
"no_records_found" => "No items found.",
"records_found" => "items found",
"number_records" => "Number of items: ",
"details_of_record" => "Details of the item",
"details_of_record_just_inserted" => "Details of the item just inserted",
"edit_record" => "Edit the item",
"back_to_the_main_table" => "Back to the main table",
"previous" => "Previous",
"next" => "Next",
"edit_profile" => "Update your profile information",
"i_think_that" => "I think that ",
"is_similar_to" => " is similar to ",
"page" => "Page ",
"of" => " of ",
"records_per_page" => "items per page",
"day" => "Day",
"month" => "Month",
"year" => "Year",
"administration" => "Administration",
"create_update_internal_table" => "Create or update internal table",
"other...." => "other....",
"insert_record" => "Insert a new item",
"search_records" => "Search for items",
"exactly" => "exactly",
"like" => "like",
"required_fields_red" => "Required fields are in red.",
"insert_result" => "Insert result:",
"record_inserted" => "item correctly inserted.",
"a_corresponding_user_account" => 'A corresponding user account',
"has_been_created_with_random_password" => 'has been created with random password',
"update_result" => "Update result:",
"record_updated" => "Item correctly updated.",
"profile_updated" => "Your profile has been correctly updated.",
"delete_result" => "Delete result:",
"record_deleted" => "Item correctly deleted.",
"records_deleted" => "Item(s) correctly deleted.",
"duplication_possible" => "Possible duplication",
"fields_max_length" => "You have inserted too much text in one or more field.",
"current_upload" => "Current file",
"delete" => "delete",
"total_records" => "Total items",
"confirm_delete?" => "Confirm delete?",
"unselect_all" => "Unselect all",
"select_all" => "Select all",
"only_elements_this_page_selected_other_pages_kept" => "Only the elements of the current page will be selected. If you selected elements in other pages, such selection will be kept.",
"all_elements_will_be_unselected_also_other_pages" => "All the elements will be unselected, also elements selected in other pages.",
"delete_selected" => "Delete selected",
"is_equal" => "is equal to",
"is_different" => "is not equal to",
"is_not_null" => "is not null",
"is_not_empty" => "is not empty",
"contains" => "contains",
"doesnt_contain" => "doesn't contain",
"starts_with" => "starts with",
"ends_with" => "ends with",
"greater_than" => ">",
"less_than" => "<",
"greater_equal_than" => ">=",
"less_equal_than" => "<=",
"between" => "between",
"between_and" => "and", // used for the between search operator: between .... AND .....
"export_to_csv" => "Export CSV",
"export_to_ical" => 'Export iCal',
"generate_ical_url" => 'Generate iCal URL',
"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)',
"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table',
"generate_ical_url_page_3" => "and its linked tables (if any).",
"import" => "Import",
"new_insert_executed" => "New insert executed",
"new_update_executed" => "New update executed",
"null" => "Null",
"is_null" => "is null",
"is_empty" => "is empty",
"continue" => "continue",
'current_password' => 'current password',
'new_password' => 'new password', 
'new_password_repeat' => 'new password (repeat)', 
'password_changed' => 'the password has been changed',
'change_your_password' => 'change your password',
'your_info' => 'your info',
'sort_by' => 'sort by',
'sort' => 'sort',

'pie_chart' => 'Pie chart',
'bar_chart' => 'Bar chart',
'line_chart' => 'Line chart',
'doughnut_chart' => 'Doughnut chart',
'show_report' => 'Show chart',
'show_labels' => 'Show labels',
'show_legend' => 'Show legend',
'group_data_by' => 'Aggregate data by',
'x_axis' => 'X-axis',
'y_axis' => 'Y-axis',
'show' => 'show',
'percentage' => '%',
'count' => 'count',
'sum' => 'sum',
'average' => 'average',
'min' => 'min',
'max' => 'max',
'variance' => 'variance',
'standard_deviation' => 'standard deviation',
'of' => 'of',
'simple_report' => 'simple report',
'advanced_sql_report' => 'advanced SQL report',
'type_your_custom_sql_query_here' => 'Type your custom SQL query here: ',
'current_search_filter_is_not_used' => '(The current search filter won\'t be used)',
'advanced_sql_reports_are_disabled' => 'Advanced SQL reports are disabled',
'advanced_sql_report_instructions_first_part' => 'You can write a custom SQL select query, e.g. suppose you have a <b>customers</b> table having an <b>age_customer</b> field, you can show the age composition of your customers using the following query:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Remember, the first field you select will be used for the <b>X axis</b> of the graph, the second field for the <b>Y axis</b>.<br/><br/>Read the documentation for further examples.',
'generate_report' => 'Generate chart',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'The use of semicolon (;) is not allowed for security reason, you can omit the final semicolon.',
'sql_report_must_start_with_select' => 'The custom SQL report must start with "SELECT "',
'show_embed_code' => 'Show embed code',
'embed_code_instructions' => 'You can copy the code below and paste it in a custom page to embed this chart or grid report; by embedding several chart/grid reports in a page you can easily create a dashboard. Please note that, if the report has been generated after a search, the search filter is not saved in the embed code. If you need to embed a report based on a stable search filter, the best way is to create a VIEW and generate the report starting from it. Also consider that pagination is not available in an embedded grid report, only X records will be displayed, where X is your current <i>items per page</i> setting.',
'produce_pdf' => 'Produce PDF',
'choose_pdf_template' => 'Choose PDF template',
'no_pdf_template' => 'Standard Template',
'show_revisions' => 'Show revisions',
'hide_revisions' => 'Hide revisions',
'record_revisions' => 'Record revisions',
'revisions' => 'Revisions',
'for_this_table_revisions_not_enabled_no_revisions' => 'For this table, revisions are not enabled or you haven\'t revisions yet.',
'generate_pivot' => 'Generate pivot',
'you_might_have_additional_rows_admin_set_to' => 'You might have additional rows but the admin set the maximum rows to ',
'add_column' => 'add column', // add column in the pivot report
'remove_this_column' => 'remove this column', // remove column in the pivot report
'advanced_sql_report_instructions_pivot_part' => 'For Pivot Table generation, in addtion, you can use alias (to specify labels) and you can use more than one aggreagete functions, for example: SELECT brand AS ProductBrand, COUNT(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand',
'advanced_sql_report_instructions_stat_card_part' => 'For <b>stat cards</b>, instead, there is no aggregation; you must select only one element. For example, this counts the number of customers: SELECT COUNT(*) FROM customers.',
"record_inserted_close_window" => "The item has been correctly inserted, you can <a href='#' onclick='window.close();return false;'>close</a> this window.",

'file_type' => 'File type',
'delimiter' => 'Delimiter',
'values_enclosed_by' => 'Values optionally enclosed by',
'load_file' => 'Upload file',
'error_no_file_selected' => 'Error, you have not selected a file to upload.',
'values_enclosed_cannot_be_blank' => 'The parameter "Values optionally enclosed by" cannot be blank, you can leave the default one even if you do not use any enclose character.',
'error_file_type_not_supported_or_different' => 'Error, this file type is not supported or it is not the one you selected in the previous page',
'error_too_much_time_passed' => 'Error, too much time has passed.',
'processing_row' => 'Processing row',
'new_elements_will_be_inserted_to_proceed_click_continue' => 'new elements will be added. To proceed, click "Continue" at the end of the page', // this message will be used with a number, e.g. "5 new elements will be added ... ",
'following_as_example_20_rows' => 'The following are only the first 20 rows of your file.',
'possible_duplication_continue_to_update' => 'Possible duplication, some elements have the same values on the unique fields (the duplication could also be within your file). Here are the duplicated elements. At the moment no elements have been inserted or updated. If you click "Continue" at the end of the page, for these elements, I will update the records with the new information provided in the file. ',
'elements_have_been_inserted_updated' => 'elements have been inserted/updated.', // this message will be used with a number, e.g. "5 elements have been inserted/updated"
'to_verify_elements_click_continue_filter_set' => 'To verify the elements inserted/updated click on "Continue". I have set a search filter that allows you to see only the inserted/updated elements (you might only see some of them if the administrator has set additional filters).',
'no_element_has_been_inserted' => 'No element has been inserted.',
'error_no_sheet_with_name'=> 'Error, no sheet with name:',
'elements_results_last_import' => 'The elements you see are the result of the last import (you might only see some of them if the administrator has set additional filters). To see all the elements click on "Remove search filter"',
'csv_file_must_be_utf8_encoded' => 'The CSV file must be UTF-8 encoded.',
'hide_show_quick_filters' => 'Hide/Show quick filters',
'show_search_url' => 'Show search URL',
'search_url_instructions' => 'This URL executes the same search you made, also adding the sort criteria you applied (if any).',
'double_click_to_edit' => 'Double click to edit',
'it_seems_you_uploaded_other_files_cancelled' => ' It seems you uploaded some files in another form but you finally did not complete the save/insert process. Those uploads have been cancelled.',

'number_uploaded_files' => 'Number of uploaded files: ',
'format_yyyy_mm_dd' => '<strong>Format:</strong> yyyy-mm-dd<br/>e.g. 2010-08-07 for Aug 7, 2010',
'format_yyyy_mm_dd_hh_mm_ss' => '<strong>Format:</strong> yyyy-mm-dd hh:mm:ss<br/>e.g. 2010-08-07 19:45:00 for Aug 7, 2010 7:45PM',
'file_uploaded_file_will_replace' => 'File uploaded! The file will replace the current one (if any) after saving the form.',
'generic_upload_error' => 'Generic upload error!',
'collapse_sidebar' => 'Collapse sidebar',
'ask_anything_about' => 'Ask anything about',
'ask_ai' => 'Ask AI',
'ask_questions_data_read_only' => 'Ask questions about your data (read-only).',
'ai_generated_answer' => 'AI-generated Answer',
'dadabrain_created_separate_view_results' => 'DaDaBrain created a separate view with the results.',
'you_can_open_it' => 'You can open it',
'here' => 'here',
'please_verify_ai_generated_results_may_be_incorrect' => 'Please verify AI-generated results before making decisions. Results may be incorrect or incomplete.',
'to_answer_I_used_this_query' => 'To answer, I have used this SQL query',
'list_below_has_not_changed_current_filters_still_apply' => 'The list below has not changed. Your current filters (if any) are still applied.',
'questions' => 'Questions',
'discard_question' => 'Discard question',
'question_discarded' => 'Question correctly discarded',
'dadabrain_disabled_check_enable_dadabrain' => '<b>DaDaBrain is disabled</b>. Please check the configuration parameter $enable_dadabrain and review the related security implications.',
'use_show_dadabrain_form_to_hide_ai_form' => 'Use $show_dadabrain_form to hide the DaDaBrain AI form.',
'open_the_view' => 'Open the View',
);	
$normal_messages_ar['months_short'][1] = 'Jan';
$normal_messages_ar['months_short'][2] = 'Feb';
$normal_messages_ar['months_short'][3] = 'Mar';
$normal_messages_ar['months_short'][4] = 'Apr';
$normal_messages_ar['months_short'][5] = 'May';
$normal_messages_ar['months_short'][6] = 'Jun';
$normal_messages_ar['months_short'][7] = 'Jul';
$normal_messages_ar['months_short'][8] = 'Aug';
$normal_messages_ar['months_short'][9] = 'Sep';
$normal_messages_ar['months_short'][10] = 'Oct';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Dec';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'Mon';
$normal_messages_ar['days_short'][2] = 'Tue';
$normal_messages_ar['days_short'][3] = 'Wed';
$normal_messages_ar['days_short'][4] = 'Thu';
$normal_messages_ar['days_short'][5] = 'Fri';
$normal_messages_ar['days_short'][6] = 'Sat';
$normal_messages_ar['days_short'][7] = 'Sun';



// error messages
$error_messages_ar = array (
	"int_db_empty" => "Error, the internal database is empty.",
	"get" => "Error in get variables.",
	"no_functions" => "Error, no functions selected<br>Please go back to the homepage.",
	"no_unique_key" => "Error, you haven't any primary key in your table.",	
	"upload_error" => "An error occurred when uploading a file.",
	"no_authorization_update" => "You don't have the authorization to modify this item.",
	"no_authorization_delete" => "You don't have the authorization to delete this item.",
	"no_authorization_view" => "You don't have the authorization to view this item.",
	"deleted_only_authorizated_records" => "Only the items on which you have the authorization have been deleted.",
	"record_from_which_you_come_no_longer_exists" => "The item from which you came no longer exists.",
	"date_not_representable" => "A date value in this item can't be represented with DaDaBIK day-month-year listboxes, the value is: ",
	"this_record_is_the_last_one" => "This item is the last one.",
	"this_record_is_the_first_one" => "This item is the first one.",
	"current_password_wrong" => 'the current password is wrong', 
	"passwords_are_different" => 'the two passwords are different',
	"new_password_must_be_different_old" => 'the new password must be different from the current one',
	"new_password_is_empty" => 'the new password is empty',
	"you_cant_live_edit_click_edit" => 'You can\'t use "live edit" for this field, click on the edit icon on your left to edit the entire record.',
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.'
	);

//login messages
$login_messages_ar = array(
	"username" => "username",
	"password" => "password",
	"please_authenticate" => "You need to be identified to continue",
	"login" => "log in",
	"username_password_are_required" => "Username and password are required",
	"pwd_gen_link" => "create password",
	"incorrect_login" => "Username or password incorrect",
	"pwd_explain_text" =>"Enter a word or phrase to be used as password and press <b>Encrypt it!</b>.",
	"pwd_explain_text_2" =>"Press <b>Register</b> to fill it in the user form below",
	"pwd_suggest_email_sending"=>"You may want to send yourself an email to remeber the password",
	"pwd_send_link_text" =>"send email!",
	"pwd_encrypt_button_text" => "Encrypt it!",
	"pwd_register_button_text" => "Register password and exit",
	"too_many_failed_login_account_blocked" => "Too many failed attempts, your account has been blocked.",
	"warning_you_still_have" => "Warning, you only have",
	"attempts_before_account_blocking" => " attempts before your account is blocked.",
	"verification_code" => "Verification code",
	"verification_code_is_required" => "Verification code is required",
	"incorrect_verification_code" => "The verification code is not correct",
	"enable_two-factor_authentication" => "Enable Two-Factor Authentication",
	"two-factor_authentication" => "Two-Factor Authentication"
	
);


// Link "Register" in the login form
$login_messages_ar['register'] = 'Register';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Create your account';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'first name';
$login_messages_ar['last_name'] = 'last name';
$login_messages_ar['registration_form_checkbox_1'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>';
$login_messages_ar['registration_form_checkbox_2'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>';
$login_messages_ar['registration_form_checkbox_3'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>';



// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Submit and register a new account';
$login_messages_ar['back_to_login'] = 'Back to login';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Account created, you will receive a confirmation email containing an activation link. Please click on the link to activate your account.';
$login_messages_ar['email_confirmed_login'] = 'Your account has been activated, you can now login: ';
$login_messages_ar['account_created_login'] = 'Your account has been created, you can now login: ';
$login_messages_ar['confirmation_link_expired_resent'] = 'The confirmation link has expired, a new link has been sent to your email address.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'This confirmation link is not correct, your account cannot be activated.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'Your email has not been confirmed yet.';
$login_messages_ar['email_already_in_use'] = 'This email is already in use.';
$login_messages_ar['username_already_in_use'] = 'This username is already in use.';
$login_messages_ar['registration_email_subject'] = "Please confirm your registration";
$login_messages_ar['registration_email_content'] = "Hello,\nsomeone (hopefully you) has registered an account at ".$site_url_to_display.". To complete your registration click on this link within 24h:";


// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Forgot your password?';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Enter your email.<br>You will receive your username<br>and a temporary new password';
// form submit button
$login_messages_ar['submit'] = 'Submit';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'If this email address corresponds to an existing user, you will receive a message with a temporary password.';
// email subject
$login_messages_ar['your_temporary_password'] = 'Your temporary password';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Someone (hopefully you) has requested a new password to access ".$site_url_to_display."\n\nIf YOU requested the new password, everything is fine, here is your new temporary password (valid for five minutes only). Please note that email is not a secure communication channel for passwords so immediately change your main password after logging-in and never use - as your main password - the  temporary passwords we sent.";
$login_messages_ar['temporary_password_email_content_part_2'] = "If you can't access your account using this temporary password we sent, it means someone else accessed your account first, please contact your system administrator.\n\nIf you did not request the new password, it means someone else might be trying to access your account: please login as soon as possible using your OLD password (this make the temporary password invalid) and contact your system administrator. If you can't login using your old password, it means that someone else probably already accessed your account using the new temporary password, please contact your system administrator.";

$login_messages_ar['intro_2fa_secret_page'] = '<h2>Important: This page is displayed only once for security reasons.</h2><p>Please complete the setup instructions before leaving the page, as you will not be able to access this information again.</p><p><b>Download an Authentication App:</b> Visit your app store (Google Play/App Store) and download an authentication app, such as Google Authenticator or Authy.<br><br><b>Scan the QR Code:</b> Use the app to scan the QR code displayed below. This will link your account to the authentication app.<br><br><b>Future Logins:</b> The next time you log in, you will be prompted to enter a verification code generated by your authentication app.</p>';












?>