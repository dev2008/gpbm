<?php
/*
***********************************************************************************
This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<?php
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Vložit nový záznam",
	"quick_search"    => "Rych. hledání",
	"search/update/delete" => "Hladat/upravit/vymazat záznamy",
	"insert_short"    => "Vložit",
	"search_short" => "Hledat",
	"advanced_search" => "Advanced Search", // to change
	"insert_anyway"    => "Vložit tak",
	"search"    => "Hledat záznam",
	"update"    => "Uložit",
	"ext_update"    => "Změnit profil",
	"yes"    => "Ano",
	"no"    => "Ne",
	"go_back" => "Zpět",
	"edit" => "Změnit",
	"delete" => "Vymazat",
	"details" => "Detaily",
	"insert_as_new" => "Insert as new", // to change
	"multiple_inserts" => "Multiple inserts", // to change
	"change_table" => "Změnit seznam",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Nemůžete editovat tento záznam, je uzamčen uživatelem: ", // to change
	"lost_locked_not_safe_update" => "Nemáte uzamčen nebo jste ztratil zámek editovaného záznamu, pokračujte jeho editací.", // to change
	"insert_item" => "Vožit položku",
	"show_all_records" => "Zobrazit všechny záznamy",
	"show_records" => "Zobrazit položky",
	"ldap_user_dont_update" => "This is an imported user (LDAP, Google, ...): his group is the only information which you should change, if needed.", // to change
	"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
	"remove_search_filter" => "smazat filtry hledání",
	"set_default_search_filter" => "set default search filter", // to change 
	"logout" => "Odhlásit",
	"top" => "Nahoru",
	"last_search_results" => "Poslední výsledky hledání",
	"show_all" => "Zobraz vše",
	"home" => "Start",
	"select_operator" => "Vyber operátor:",
	"all_conditions_required" => "Všechny podmínky jsou požadovány",
	"any_conditions_required" => "Některé podmínky jsou požadované",
	"all_contacts" => "Všechny kontakty",
	"removed" => "vymazané",
	"please" => "Prosím",
	"and_check_form" => "a zkontrolujte formulář.",
	"and_try_again" => "a zkuste znovu.",
	"none" => "nic",
	"are_you_sure" => "Jste si jistí?",
	"delete_all" => "vymazat vše",
	"really?" => "Opravdu?",
	"delete_are_you_sure" => "Chystáte se smazat záznam, jste si jistí?",
	"required_fields_missed" => "Nemáte vyplněné některé povinné položky!.",
	"alphabetic_not_valid" => "V textovém poli se nacházejí nějaká čísla.",
	"numeric_not_valid" => "V číselném poli se nachází jiné než číselné znaky.",
	"email_not_valid" => "E-mailova není platná.",
	"timestamp_not_valid" => "Vkládaný časový údaj není platný.",
	"url_not_valid" => "Url adresa je neplatná.",
	"phone_not_valid" => "Telefonní číslo je neplatné.<br>Prosím, pouzijte formát \"+(kod statu)(kod oblasti)(cislo)\"  napr. +420245005050, 00420245005050, 0245005050.",
	"date_not_valid" => "Vložili jste jeden nebo více nesprávných datumů.",
	"entered_value_not_valid" => "The entered value is not valid.", // to change
	"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin", // to change
	"similar_records" => "Zaznam se zdá být podobný již uloženému  (I'll show max ".$number_duplicated_records." similar items, there could be more)..<br>Co si přejete provést?", // to change
	"similar_records_short" => "Zaznam se zdá být podobný již uloženému  (I'll show max ".$number_duplicated_records." similar items, there could be more).", // to change
	"no_records_found" => "záznam nebyl nalezen.",
	"records_found" => "záznamů nalezených",
	"number_records" => "Počet záznamů: ",
	"details_of_record" => "Detaily záznamu",
	"details_of_record_just_inserted" => "Detaily záznamu byly uloženy",
	"edit_record" => "Zmenit záznam",
	"back_to_the_main_table" => "Zpět do hlavní tabulky",
	"last_search_results" => "Poslední výsledky hledání",
	"previous" => "předchozí",
	"next" => "dále",
	"edit_profile" => "Změnit údaje Vašeho profilu",
	"i_think_that" => "Myslim si že, ",
	"is_similar_to" => " je ",
	"page" => "Strana ",
	"of" => " z ",
	"records_per_page" => "záznamů na stránku",
	"day" => "Den",
	"month" => "Měsíc",
	"year" => "Rok",
	"administration" => "Admin",
	"create_update_internal_table" => "Vytvořit, nebo změnit interní tabulku",
	"other...." => "jiný....",
	"insert_record" => "Vložit nový záznam",
	"search_records" => "Hladat záznamy",
	"exactly" => "přesně",
	"like" => "jako",
	"required_fields_red" => "Požadované údaje jsou červené.",
	"insert_result" => "Vložte výsledek:",
	"record_inserted" => "Záznam vložen korektně.",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
	"update_result" => "Obnov vysledek:",
	"record_updated" => "Záznam obnovený korektně.",
	"profile_updated" => "Váš profil by obnoven korektně.",
	"delete_result" => "Vymazat vysledek:",
	"record_deleted" => "Záznam byl vymazán korektně.",
	"records_deleted" => "Item(s) correctly deleted.", // to change
	"duplication_possible" => "Povolit duplikace",
	"fields_max_length" => "Příliž mnoho textu nebo položek.",
	"current_upload" => "Aktualní soubor",
	"delete" => "vymazat",
	"total_records" => "Celkový počet záznamů",
	"confirm_delete?" => "Souhlasíte s vymazáním?",
    "unselect_all" => "Unselect all", // to change
    "select_all" => "Select all", // to change
    "only_elements_this_page_selected_other_pages_kept" => "Only the elements of the current page will be selected. If you selected elements in other pages, such selection will be kept.", // to change
    "all_elements_will_be_unselected_also_other_pages" => "All the elements will be unselected, also elements selected in other pages.", // to change
    "delete_selected" => "Delete selected", // to change
	"is_equal" => " je ",
	"is_different" => "is not equal to", // to change
	"is_not_null" => "is not null", // to change
	"is_not_empty" => "is not empty", // to change
	"contains" => "obsahuje",
	"doesnt_contain" => "doesn't contain", // to change
	"starts_with" => "začina s",
	"ends_with" => "končí na",
	"greater_than" => ">",
	"less_than" => "<",
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
    "between" => "between", // to change
    "between_and" => "and", // to change, used for the between search operator: between .... AND .....
	"export_to_csv" => "Export do CSV",
	"export_to_ical" => 'Export iCal', // to change,
	"generate_ical_url" => 'iCal URL', // to change,
	"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
	"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
	"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
	"new_insert_executed" => "Nový záznam proveden",
	"new_update_executed" => "Nová změna provedena",
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty", // to change
	"continue" => "continue", // to change
	'current_password' => 'current password', // to change
	'new_password' => 'new password', // to change
	'new_password_repeat' => 'new password (repeat)', // to change
	'password_changed' => 'the password has been changed', // to change
	'change_your_password' => 'change your password', // to change
	'your_info' => 'your info', // to change
	'sort_by' => 'sort by', // to change
	'sort' => 'sort', // to change
	'pie_chart' => 'Pie chart', // to change
'bar_chart' => 'Bar chart', // to change
'line_chart' => 'Line chart', // to change
'doughnut_chart' => 'Doughnut chart', // to change
'show_report' => 'Show chart', // to change
'show_labels' => 'Show labels', // to change
'show_legend' => 'Show legend', // to change
'group_data_by' => 'Aggregate data by', // to change
'x_axis' => 'X-axis', // to change
'y_axis' => 'Y-axis', // to change
'show' => 'show', // to change
'percentage' => '%', // to change
'count' => 'count', // to change
'sum' => 'sum', // to change
'average' => 'average', // to change
'min' => 'min', // to change
'max' => 'max', // to change
'variance' => 'variance', // to change
'standard_deviation' => 'standard deviation', // to change

'simple_report' => 'simple report', // to change
'advanced_sql_report' => 'advanced SQL report', // to change
'type_your_custom_sql_query_here' => 'Type your custom SQL query here: ', // to change
'current_search_filter_is_not_used' => '(The current search filter won\'t be used)', // to change
'advanced_sql_reports_are_disabled' => 'Advanced SQL reports are disabled', // to change
'advanced_sql_report_instructions_first_part' => 'You can write a custom SQL select query, e.g. suppose you have a <b>customers</b> table having an <b>age_customer</b> field, you can show the age composition of your customers using the following query:', // to change
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot; // to change>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // to change // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Remember, the first field you select will be used for the <b>X axis</b> of the graph, the second field for the <b>Y axis</b>.<br/><br/>Read the documentation for further examples.', // to change
'generate_report' => 'Generate chart', // to change
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'The use of semicolon (;) is not allowed for security reason, you can omit the final semicolon.', // to change
'sql_report_must_start_with_select' => 'The custom SQL report must start with "SELECT "', // to change
'show_embed_code' => 'Show embed code', // to change
'embed_code_instructions' => 'You can copy the code below and paste it in a custom page to embed this chart or grid report; by embedding several chart/grid reports in a page you can easily create a dashboard. Please note that, if the report has been generated after a search, the search filter is not saved in the embed code. If you need to embed a report based on a stable search filter, the best way is to create a VIEW and generate the report starting from it. Also consider that pagination is not available in an embedded grid report, only X records will be displayed, where X is your current <i>items per page</i> setting.', // to change
'produce_pdf' => 'Produce PDF', // to change
'choose_pdf_template' => 'Choose PDF template', // to change
'no_pdf_template' => 'Standard Template', // to change
'show_revisions' => 'Show revisions', // to change,
'hide_revisions' => 'Hide revisions', // to change,
'record_revisions' => 'Record revisions', // to change,
'revisions' => 'Revisions', // to change,
'for_this_table_revisions_not_enabled_no_revisions' => 'For this table, revisions are not enabled or you haven\'t revisions yet.', // to change,
'generate_pivot' => 'Generate pivot', // to change
'you_might_have_additional_rows_admin_set_to' => 'You might have additional rows but the admin set the maximum rows to ', // to change
'add_column' => 'add column', // add column in the pivot report // to change
'remove_this_column' => 'remove this column', // remove column in the pivot report // to change
'advanced_sql_report_instructions_pivot_part' => 'For Pivot Table generation, in addtion, you can use alias (to specify labels) and you can use more than one aggreagete functions, for example: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand', // to change
'advanced_sql_report_instructions_stat_card_part' => 'For <b>stat cards</b>, instead, there is no aggregation; you must select only one element. For example, this counts the number of customers: SELECT COUNT(*) FROM customers.', // to change
"record_inserted_close_window" => "The item has been correctly inserted, you can <a href='#' onclick='window.close();return false;'>close</a> this window.", // to change

"import" => "Import", // to change
'file_type' => 'File type', // to change
'delimiter' => 'Delimiter', // to change
'values_enclosed_by' => 'Values optionally enclosed by', // to change
'load_file' => 'Upload file', // to change
'error_no_file_selected' => 'Error, you have not selected a file to upload.', // to change
'values_enclosed_cannot_be_blank' => 'The parameter "Values optionally enclosed by" cannot be blank, you can leave the default one even if you do not use any enclose character.', // to change
'error_file_type_not_supported_or_different' => 'Error, this file type is not supported or it is not the one you selected in the previous page', // to change
'error_too_much_time_passed' => 'Error, too much time has passed.', // to change
'processing_row' => 'Processing row', // to change
'new_elements_will_be_inserted_to_proceed_click_continue' => 'new elements will be added. To proceed, click "Continue" at the end of the page', // this message will be used with a number, e.g. "5 new elements will be added ... ", // to change
'following_as_example_20_rows' => 'The following are only the first 20 rows of your file.', // to change
'possible_duplication_continue_to_update' => 'Possible duplication, some elements have the same values on the unique fields (the duplication could also be within your file). Here are the duplicated elements. At the moment no elements have been inserted or updated. If you click "Continue" at the end of the page, for these elements, I will update the records with teh new information provided in the file. ', // to change
'elements_have_been_inserted_updated' => 'elements have been inserted/updated.', // this message will be used with a number, e.g. "5 elements have been inserted/updated" // to change
'to_verify_elements_click_continue_filter_set' => 'To verify the elements inserted/updated click on "Continue". I have set a search filter that allows you to see only the inserted/updated elements (you might only see some of them if the administrator has set additional filters).', // to change
'no_element_has_been_inserted' => 'No element has been inserted.', // to change
'error_no_sheet_with_name'=> 'Error, no sheet with name:', // to change
'elements_results_last_import' => 'The elements you see are the result of the last import (you might only see some of them if the administrator has set additional filters). To see all the elements click on "Remove search filter"', // to change
'csv_file_must_be_utf8_encoded' => 'The CSV file must be UTF-8 encoded.', // to change
'hide_show_quick_filters' => 'Hide/Show quick filters', // to change,
'show_search_url' => 'Show search URL', // to change,
'search_url_instructions' => 'This URL executes the same search you made, also adding the sort criteria you applied (if any).', // to change,
"double_click_to_edit" => 'Double click to edit', // to change
'it_seems_you_uploaded_other_files_cancelled' => ' It seems you uploaded some files in another form but you finally did not complete the save/insert process. Those uplaods have been cancelled.', // to change,
'number_uploaded_files' => 'Number of uploaded files: ', // to change,
'file_uploaded_file_will_replace' => 'File uploaded! The file will replace the current one (if any) after saving the form.',// to change
'generic_upload_error' => 'Generic upload error! ', // to change
'collapse_sidebar' => 'Collapse sidebar', // to change
'ask_anything_about' => 'Ask anything about', // to change
'ask_ai' => 'Ask AI', // to change
'ask_questions_data_read_only' => 'Ask questions about your data (read-only).', // to change
'ai_generated_answer' => 'AI-generated Answer', // to change
'dadabrain_created_separate_view_results' => 'DaDaBrain created a separate view with the results.', // to change
'you_can_open_it' => 'You can open it', // to change
'here' => 'here', // to change
'please_verify_ai_generated_results_may_be_incorrect' => 'Please verify AI-generated results before making decisions. Results may be incorrect or incomplete.', // to change
'to_answer_I_used_this_query' => 'To answer, I have used this SQL query', // to change
'list_below_has_not_changed_current_filters_still_apply' => 'The list below has not changed. Your current filters (if any) are still applied.', // to change
'questions' => 'Questions', // to change
'discard_question' => 'Discard question', // to change
'question_discarded' => 'Question correctly discarded', // to change
'dadabrain_disabled_check_enable_dadabrain' => '<b>DaDaBrain is disabled</b>. Please check the configuration parameter $enable_dadabrain and review the related security implications.', // to change
'use_show_dadabrain_form_to_hide_ai_form' => 'Use $show_dadabrain_form to hide the DaDaBrain AI form.',
'open_the_view' => 'Open the View', // to change

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
	"int_db_empty" => "Chyba, interní dadabáze je prázdná.",
	"get" => "Chyba v proměnných.",
	"no_functions" => "Chyba, ziadna funkcia nebola vybrata<br>Vrat sa na domovsku stranku.",
	"no_unique_key" => "Chyba, nemáte určený Primární klíč ve vaší tabulce.",
	"upload_error" => "Chyba při obnovení souboru.",
	"no_authorization_update" => "Nemáte oprávnění změnit tento záznam.",
	"no_authorization_delete" => "Nemáte oprávnění vymazat tento záznam.",
	"no_authorization_view" => "Nemáte oprávnění zobrazit tento záznam.",
	"deleted_only_authorizated_records" => "Byly vymazány pouze záznamy, ke kterým máte patřičné oprávnění.",
	"record_from_which_you_come_no_longer_exists" => "Záznam, ze kterého jste vstoupil již neexistuje.",
	"date_not_representable" => "Hodnota záznamu v položce datum není zobrazitelná interním systémem day-month-year listboxes, hodnota je: ", // to change
	"this_record_is_the_last_one" => "Tento záznam je poslední.",
	"this_record_is_the_first_one" => "Tento záznam je první.",
	"current_password_wrong" => 'the current password is wrong', // to change
	"passwords_are_different" => 'the two passwords are different', // to change
	"new_password_must_be_different_old" => 'the new password must be different from the current one', // to change
	"new_password_is_empty" => 'the new password is empty', // to change,
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.' // to change
	);

//login messages
$login_messages_ar = array(
	"username" => "Jméno",
	"password" => "Heslo",
	"please_authenticate" => "Pro pokračování se musíte přihlásit",
	"login" => "přihlásit",
	"username_password_are_required" => "Musíte zadat jméno a heslo",
	"pwd_gen_link" => "vytvoření hesla",
	"incorrect_login" => "Jméno nebo heslo nejsou správná!",
	"pwd_explain_text" =>"Napište slovo pro heslo a  <b>Zašifrujte ho!</b>.",
	"pwd_explain_text_2" =>"Stiskněte <b>Zapsat</b> pro zápis do formuláře",
	"pwd_suggest_email_sending"=>"Můžete si poslat e-mail pro zapamatování hesla",
	"pwd_send_link_text" =>"pošli e-mail!",
	"pwd_encrypt_button_text" => "Zašifruj!",
	"pwd_register_button_text" => "Zaregistruj heslo a ukonči.",
	"too_many_failed_login_account_blocked" => "Too many failed attempts, your account has been blocked.", // to change
	"warning_you_still_have" => "Warning, you still have just ", // to change
	"attempts_before_account_blocking" => " attempts before your account is blocked.", // to change
	"verification_code" => "Verification code", // to change
	"verification_code_is_required" => "Verification code is required", // to change
	"incorrect_verification_code" => "The verification code is not correct", // to change
	"enable_two-factor_authentication" => "Enable Two-Factor Authentication", // to change
	"two-factor_authentication" => "Two-Factor Authentication", // to change
);

// to change, all the messages below

// Link "Register" in the login form
$login_messages_ar['register'] = 'Register';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Create your account';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'first name';
$login_messages_ar['last_name'] = 'last name';
$login_messages_ar['registration_form_checkbox_1'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_2'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_3'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change

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
$login_messages_ar['email_already_in_use'] = 'This email is already in use.'; // to change
$login_messages_ar['username_already_in_use'] = 'This username is already in use.'; // to change
$login_messages_ar['registration_email_subject'] = "Please confirm your registration"; // to change
$login_messages_ar['registration_email_content'] = "Hello,\nsomeone (hopefully you) has registered an account at ".$site_url_to_display.". To complete your registration click on this link within 24h:"; // to change

// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Forgotten password';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Enter your email, you will receive your username and a temporary new password';
// form submit button
$login_messages_ar['submit'] = 'Submit';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'If this email address corresponds to an existing user, you will receive a message with a temporary password.';
// email subject
$login_messages_ar['your_temporary_password'] = 'Your temporary password';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Someone (hopefully you) has requested a new password to access ".$site_url_to_display."\n\nIf YOU requested the new password, everything is fine, here is your new temporary password (valid for five minutes only). Please note that email is not a secure communication channel for passwords so immediately change your main password after logging-in and never use - as your main password - the  temporary passwords we sent.";
$login_messages_ar['temporary_password_email_content_part_2'] = "If you can't access your account using this temporary password we sent, it means someone else accessed your account first, please contact your system administrator.\n\nIf you did not request the new password, it means someone else might be trying to access your account: please login as soon as possible using your OLD password (this make the temporary password invalid) and contact your system administrator. If you can't login using your old password, it means that someone else probably already accessed your account using the new temporary password, please contact your system administrator.";

$login_messages_ar['intro_2fa_secret_page'] = '<h2>Important: This page is displayed only once for security reasons.</h2><p>Please complete the setup instructions before leaving the page, as you will not be able to access this information again.</p><p><b>Download an Authentication App:</b> Visit your app store (Google Play/App Store) and download an authentication app, such as Google Authenticator or Authy.<br><br><b>Scan the QR Code:</b> Use the app to scan the QR code displayed below. This will link your account to the authentication app.<br><br><b>Future Logins:</b> The next time you log in, you will be prompted to enter a verification code generated by your authentication app.</p>'; // to change
?>
