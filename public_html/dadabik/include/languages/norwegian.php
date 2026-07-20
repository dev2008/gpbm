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
	"insert"    => "Sett inn post",
	"quick_search"    => "Hurtigsøk",
	"search/update/delete" => "Søk/oppdater/slett",
	"insert_short"    => "Sett inn",
	"search_short" => "Søk",
	"advanced_search" => "Advanced Search", // to change
	"insert_anyway"    => "Sett inn likevel",
	"search"    => "Søk",
	"update"    => "Lagre",
	"ext_update"    => "Oppdater profil",
	"yes"    => "Ja",
	"no"    => "Nei",
	"go_back" => "Tilbake",
	"edit" => "Rediger",
	"delete" => "Slett",
	"details" => "Detaljer",
	"insert_as_new" => "Insert as new", // to change
	"multiple_inserts" => "Multiple inserts", // to change
	"change_table" => "Rediger tabell",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Du kan ikke redigere denne posten, den er låst for redigering av: ",
	"lost_locked_not_safe_update" => "Du har ikke låst denne post så det er ikke trygt å oppdatere den. Start på nytt.",
	"insert_item" => "Sett inn post",
	"show_all_records" => "Vis alle poster",
	"show_records" => "Vis poster",
	"ldap_user_dont_update" => "Dette er en importeret bruker (LDAP, Google, ...): Du kan kun endre gruppen den tilhører.",
	"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
	"remove_search_filter" => "fjern søkefilter",
	"set_default_search_filter" => "set default search filter", // to change 
	"logout" => "Logg ut",
	"top" => "Topp",
	"last_search_results" => "Siste søkeresultater",
	"show_all" => "Vis alle",
	"home" => "Hjem",
	"select_operator" => "Velg operatorer:",
	"all_conditions_required" => "Alle betingelser skal fylles ut",
	"any_conditions_required" => "En eller flere betingelser skal fylles ut",
	"all_contacts" => "Alle kontakter",
	"removed" => "fjernet",
	"please" => "Venligst",
	"and_check_form" => "og sjekk skjemaet.",
	"and_try_again" => "og prøv igen.",
	"none" => "ingen",
	"are_you_sure" => "Er du sikker?",
	"delete_all" => "slette alt",
	"really?" => "Mener du det?",
	"delete_are_you_sure" => "Du vil slette posten nedenfor. Er du sikker?",
	"required_fields_missed" => "Du har ikke fylt ut alle nødvendige felt.",
	"alphabetic_not_valid" => "Du har satt inn et/flere tall i et alpha felt.",
	"numeric_not_valid" => "Du har satt inn alpha bokstaver i et numerisk felt.",
	"email_not_valid" => "Emailadressen du har angitt er ikke gyldig.",
	"timestamp_not_valid" => "Den tidsangivelse du har angitt er ikke gyldig.",
	"url_not_valid" => "Linken (URL) du har angitt er ikke gyldig.",
	"phone_not_valid" => "Telefonnummeret du har angitt er ikke gyldig. <br> Angi \"+(landkode)(områdekode)(nummer)\" f.eks. +390523599318, +00390523599318, 0523599318.",
	"date_not_valid" => "Du har angitt en eller flere ugyldige datoer.",
	"entered_value_not_valid" => "The entered value is not valid.", // to change
	"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin", // to change
	"similar_records" => "Posten nedenfor er identisk til den du vil sette inn (I'll show max ".$number_duplicated_records." similar items, there could be more).<br> Hva ønsker du å gjøre?",// to change
	"similar_records_short" => "Posten nedenfor er identisk til den du vil sette inn (I'll show max ".$number_duplicated_records." similar items, there could be more).",// to change
	"no_records_found" => "Fant ingen poster.",
	"records_found" => "poster funnet",
	"number_records" => "Antall poster: ",
	"details_of_record" => "Postdetaljer",
	"details_of_record_just_inserted" => "Detaljer for posten som ble satt inn",
	"edit_record" => "Rediger posten",
	"back_to_the_main_table" => "Tilbake til hovedtabellen",
	"previous" => "Forrige",
	"next" => "Neste",
	"edit_profile" => "Oppdater profilen din",
	"i_think_that" => "Jeg tror at ",
	"is_similar_to" => " er identisk med ",
	"page" => "Side ",
	"of" => " av ",
	"records_per_page" => "poster pr. side",
	"day" => "Dag",
	"month" => "Måned",
	"year" => "År",
	"administration" => "Administrasjon",
	"create_update_internal_table" => "Lag eller oppdater intern tabell",
	"other...." => "annet....",
	"insert_record" => "Sett inn ny post",
	"search_records" => "Søk etter poster",
	"exactly" => "presis",
	"like" => "minner om",
	"required_fields_red" => "Nødvendige felter er røde.",
	"insert_result" => "Sett inn resultat:",
	"record_inserted" => "post korrekt satt inn.",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
	"update_result" => "Oppdater resultat:",
	"record_updated" => "Posten er korrekt oppdatert.",
	"profile_updated" => "Din profil er korrekt oppdatert.",
	"delete_result" => "Slett resultat:",
	"record_deleted" => "Post slettet.",
	"records_deleted" => "Item(s) correctly deleted.", // to change
	"duplication_possible" => "Dobbling er mulig",
	"fields_max_length" => "Du har skrevet for mye tekst i et eller flere felter.",
	"current_upload" => "Aktuell fil",
	"delete" => "slett",
	"total_records" => "Totalt antall poster",
	"confirm_delete?" => "Bekreft sletting?",
    "unselect_all" => "Unselect all", // to change
    "select_all" => "Select all", // to change
    "only_elements_this_page_selected_other_pages_kept" => "Only the elements of the current page will be selected. If you selected elements in other pages, such selection will be kept.", // to change
    "all_elements_will_be_unselected_also_other_pages" => "All the elements will be unselected, also elements selected in other pages.", // to change
    "delete_selected" => "Delete selected", // to change
	"is_equal" => "er lik med",
	"is_different" => "er ikke lik med",
	"is_not_null" => "er ikke null",
	"is_not_empty" => "er ikke tom",
	"contains" => "inneholder",
    "doesnt_contain" => "doesn't contain", // to change
	"starts_with" => "starter med",
	"ends_with" => "slutter med",
	"greater_than" => ">",
	"less_than" => "<",
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
    "between" => "between", // to change
    "between_and" => "and", // to change, used for the between search operator: between .... AND .....
	"export_to_csv" => "Eksport til CSV",
	"export_to_ical" => 'Export iCal', // to change,
	"generate_ical_url" => 'iCal URL', // to change,
	"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
	"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
	"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
	"new_insert_executed" => "Innsetting utført",
	"new_update_executed" => "Oppdatering utført",
	"null" => "Null",
	"is_null" => "er null",
	"is_empty" => "er tom",
	"continue" => "fortsett",
	'current_password' => 'nåværende passord',
	'new_password' => 'nytt passord',
	'new_password_repeat' => 'nytt passord (gjenta)',
	'password_changed' => 'passord er endret',
	'change_your_password' => 'endre passord',
	'your_info' => 'din informasjon',
	'sort_by' => 'sortert etter',
	'sort' => 'sorter',
	'pie_chart' => 'Kakediagram',
'bar_chart' => 'Søylediagram',
'line_chart' => 'Linjediagram',
'doughnut_chart' => 'Smultringdiagram',
'show_report' => 'Vis diagram',
'show_labels' => 'Vis navn',
'show_legend' => 'Vis legend',
'group_data_by' => 'Grupper data pr',
'x_axis' => 'X-akse',
'y_axis' => 'Y-akse',
'show' => 'vis',
'percentage' => '%',
'count' => 'antall',
'sum' => 'sum',
'average' => 'gjennomsnitt',
'min' => 'min',
'max' => 'maks',
'variance' => 'variasjon',
'standard_deviation' => 'standard avvik',
'of' => 'av',
'simple_report' => 'enkel rapport',
'advanced_sql_report' => 'avansert SQL rapport',
'type_your_custom_sql_query_here' => 'Skriv ditt eget SQL uttrykk her: ',
'current_search_filter_is_not_used' => '(The current search filter won\'t be used)', // to change
'advanced_sql_reports_are_disabled' => 'Avansert SQL rapport er ikke aktiv',
'advanced_sql_report_instructions_first_part' => 'Du kan skrive din egen select spørring. Hvis du f.eks. har en <b>kunde</b> tabell med et <b>år_kunde</b> felt, kan du vise alderssammensetningen av dine kunder med følgende spørring:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot; // to change>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // to change // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Husk at det første felt du vælger vil bli brukt som <b>x-akse</b> i grafen, det andre feltet som <b>y-akse</b>.<br/><br/>Les dokumentasjonen for flere eksempler.',
'generate_report' => 'Lag diagram',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'Bruk av semikolon (;) er ikke tillatt av sikkerhetsgrunner, du kan utelate det siste semikolon.',
'sql_report_must_start_with_select' => 'Din SQL rapport skal starte med "SELECT "',
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
$normal_messages_ar['months_short'][5] = 'Mai';
$normal_messages_ar['months_short'][6] = 'Jun';
$normal_messages_ar['months_short'][7] = 'Jul';
$normal_messages_ar['months_short'][8] = 'Aug';
$normal_messages_ar['months_short'][9] = 'Sep';
$normal_messages_ar['months_short'][10] = 'Okt';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Des';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'Man';
$normal_messages_ar['days_short'][2] = 'Tir';
$normal_messages_ar['days_short'][3] = 'Ons';
$normal_messages_ar['days_short'][4] = 'Tor';
$normal_messages_ar['days_short'][5] = 'Fre';
$normal_messages_ar['days_short'][6] = 'Lør';
$normal_messages_ar['days_short'][7] = 'Søn';
// error messages
$error_messages_ar = array (
	"int_db_empty" => "Feil, intern database er tom.",
	"get" => "Feil ved lesing av poster.",
	"no_functions" => "Feil, Det er ikke valgt noen funksjon <br> Gå tilbake til forside.",
	"no_unique_key" => "Feil. Det er ingen primærnøkkel i tabellen.",
	"upload_error" => "Det oppstod en feil ved uplasting af en fil.",
	"no_authorization_update" => "Du har ikke tillatelse til å oppdatere denne posten.",
	"no_authorization_delete" => "Du har ikke tillatelse til å slette denne posten.",
	"no_authorization_view" => "Du har ikke tillatelse til å se denne posten.",
	"deleted_only_authorizated_records" => "Kun de poster du har autorisasjon til er blitt slettet.",
	"record_from_which_you_come_no_longer_exists" => "Den post du kom fra finnes ikke lengre.",
	"date_not_representable" => "En dato i denne post kan ikke vises i dag-måned-år listboksen, verdien er: ",
	"this_record_is_the_last_one" => "Dette er den siste posten.",
	"this_record_is_the_first_one" => "Dette er den første posten.",
	"current_password_wrong" => 'det nåværende passord er feil',
	"passwords_are_different" => 'de to passordene er forskjellige',
	"new_password_must_be_different_old" => 'the new password must be different from the current one', // to change
	"new_password_is_empty" => 'det nye passordet er tomt',
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.' // to change
	);

//login messages
$login_messages_ar = array(
	"username" => "Brukernavn",
	"password" => "Passord",
	"please_authenticate" => "Du skal identifisere deg for å kunne fortsette",
	"login" => "logg inn",
	"username_password_are_required" => "Brukernavn og passord er nødvendig",
	"pwd_gen_link" => "Lag password",
	"incorrect_login" => "Brukernavn eller passord er feil",
	"pwd_explain_text" =>"Sett inn ordet som skal brukes som passord og trykk <b>Krypter det!</b>.",
	"pwd_explain_text_2" =>"Trykk <b>Registrer</b> for å overføre det til det andre feltet",
	"pwd_suggest_email_sending"=>"Du kan sende en email til deg selv med passordet",
	"pwd_send_link_text" =>"send mail!",
	"pwd_encrypt_button_text" => "Krypter det!",
	"pwd_register_button_text" => "Registrer passord and avslutt",
	"too_many_failed_login_account_blocked" => "For mange uriktige forsøk. Din konto er blitt sperret.",
	"warning_you_still_have" => "Advarsel, du har kun ",
	"attempts_before_account_blocking" => " forsøk igjen før kontoen din blir sperret.",
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
