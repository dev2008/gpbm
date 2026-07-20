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
	"insert"    => "Indsæt post",
	"quick_search"    => "Hurtig søgning",
	"search/update/delete" => "Søg/opdater/slet post",
	"insert_short"    => "Opret ny",
	"search_short" => "Søg",
	"advanced_search" => "Advanced Search", // to change
	"insert_anyway"    => "Indsæt alligevel",
	"search"    => "Søg efter poster",
	"update"    => "Gem",
	"ext_update"    => "Opdater din profil",
	"yes"    => "Ja",
	"no"    => "Nej",
	"go_back" => "Gå tilbage",
	"edit" => "Ret",
	"delete" => "Slet",
	"details" => "Detaljer",
	"insert_as_new" => "Indsæt som ny",
	"multiple_inserts" => "Flere indsættelser",
	"change_table" => "Skift tabel",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Du kan ikke rette denne post da den er spærret af brugeren: ",
	"lost_locked_not_safe_update" => "Du har ikke låst denne post så det er ikke sikkert at opdatere den. Start veligst edit igen.",
	"insert_item" => "Dan ny post",
	"show_all_records" => "Vis alle poster",
	"show_records" => "Vis poster",
	"ldap_user_dont_update" => "Dette er en importeret bruger (LDAP, Google, ...): Du kan kun ændre den gruppe han tilhører.",
	"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
	"remove_search_filter" => "fjern søgefilter",
	"set_default_search_filter" => "set default search filter", // to change 
	"logout" => "Logud",
	"top" => "Top",
	"last_search_results" => "Sidste søge resultater",
	"show_all" => "Vis alle",
	"home" => "Hjem",
	"select_operator" => "Vælg alle operatorer:",
	"all_conditions_required" => "Alle betingelser skal udfyldes",
	"any_conditions_required" => "En eller flere betingelser skal udfyldes",
	"all_contacts" => "Alle kontrakter",
	"removed" => "fjernet",
	"please" => "Please",
	"and_check_form" => "og kontroller formen.",
	"and_try_again" => "og prøv igen.",
	"none" => "ingen",
	"are_you_sure" => "Er du sikker?",
	"delete_all" => "slet alle",
	"really?" => "Mener du det?",
	"delete_are_you_sure" => "Du vil slette posten nedenfor. Er du sikker?",
	"required_fields_missed" => "Du har ikke udfyldt alle nødvendige felter.",
	"alphabetic_not_valid" => "Du har indsat et/flere tal i et alpha felt.",
	"numeric_not_valid" => "Du har indsat alpha karakterer i et numerisk felt.",
	"email_not_valid" => "Den email adresse du har angivet er ikke gyldig.",
	"timestamp_not_valid" => "Den tidsangivelse du har angivet er ikke gyldig.",
	"url_not_valid" => "Det link (URL) du har angivet er ikke gyldig.",
	"phone_not_valid" => "Det telefon nummer du har angivet er ikke gyldigt. <br> Angive \"+(lande kode)(område kode)(nummer)\" f.eks. +390523599318, +00390523599318, 0523599318.",
	"date_not_valid" => "Du har angivet en eller flere ugyldige datoer.",
	"entered_value_not_valid" => "The entered value is not valid.", // to change
	"similar_records" => "Posterne nedenfor synes at være meget lig den du er ved at indsætte. (Der vises kun ".$number_duplicated_records." poster. Der kan være flere.) <br>Hvad ønsker du at gøre ?",
	"similar_records_short" => "Posterne nedenfor synes at være meget lig den du er ved at indsætte. (Der vises kun ".$number_duplicated_records." poster. Der kan være flere.)",
	"no_records_found" => "Ingen poster fundet.",
	"records_found" => "poster fundet",
	"number_records" => "Antal poster: ",
	"details_of_record" => "Detaljer for posten",
	"details_of_record_just_inserted" => "Detaljer for den post der lige er indsat",
	"edit_record" => "Ret posten",
	"back_to_the_main_table" => "Tilbage til hovedtabellen",
	"previous" => "Foregående",
	"next" => "Næste",
	"edit_profile" => "Opdater din profil",
	"i_think_that" => "Jeg tror at ",
	"is_similar_to" => " er identisk med ",
	"page" => "Side ",
	"of" => " af ",
	"records_per_page" => "poster per side",
	"day" => "Dag",
	"month" => "Måned",
	"year" => "År",
	"administration" => "Administration",
	"create_update_internal_table" => "Dan eller opdater intern tabel",
	"other...." => "andet....",
	"insert_record" => "Indsæt ny post",
	"search_records" => "Søg efter poster",
	"exactly" => "præcis",
	"like" => "minder om",
	"required_fields_red" => "Nødvendige felter er røde.",
	"insert_result" => "Insæt resultat:",
	"record_inserted" => "post korrekt indsat.",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
	"update_result" => "Opdater resultat:",
	"record_updated" => "Posten er korrekt opdateret.",
	"profile_updated" => "Din profil er korrekt opdateret.",
	"delete_result" => "Slet resultat:",
	"record_deleted" => "Post slettet.",
	"records_deleted" => "Item(s) correctly deleted.", // to change
	"duplication_possible" => "Dublering er muligt",
	"fields_max_length" => "Du har skrevet for meget tekst i et eller flere felter.",
	"current_upload" => "Aktuel fil",
	"delete" => "slet",
	"total_records" => "Total antal poster",
	"confirm_delete?" => "Bekræft sletning?",
    "unselect_all" => "fjern selektering for alle",
    "select_all" => "Select all", // to change
    "only_elements_this_page_selected_other_pages_kept" => "Only the elements of the current page will be selected. If you selected elements in other pages, such selection will be kept.", // to change
    "all_elements_will_be_unselected_also_other_pages" => "All the elements will be unselected, also elements selected in other pages.", // to change
    "delete_selected" => "slet de valgte",
	"is_equal" => "er lig med",
	"is_different" => "er ikke lig med",
	"is_not_null" => "er ikke null",
	"is_not_empty" => "er ikke tom",
	"contains" => "indeholder",
	"doesnt_contain" => "indeholder ikke",
	"starts_with" => "starter med",
	"ends_with" => "slutter med",
	"greater_than" => ">",
	"less_than" => "<",
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
    "between" => "mellem",
    "between_and" => "og", // used for the between search operator: between .... AND .....
	"export_to_csv" => "Eksport til CSV",
	"export_to_ical" => 'Export iCal', // to change,
	"generate_ical_url" => 'iCal URL', // to change,
	"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
	"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
	"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
	"new_insert_executed" => "Indsæt udført",
	"new_update_executed" => "Opdatering udført",
	"null" => "Null",
	"is_null" => "er null",
	"is_empty" => "er tom",
	"continue" => "fortsæt",
	'current_password' => 'nuværende password',
	'new_password' => 'nyt password',
	'new_password_repeat' => 'nyt password (gentag)',
	'password_changed' => 'password er rettet',
	'change_your_password' => 'ret dit password',
	'your_info' => 'din information',
	'sort_by' => 'sorteret efter',
	'sort' => 'sorter',
	'pie_chart' => 'Lagkage diagram',
'bar_chart' => 'Søjle diagram',
'line_chart' => 'Linie diagram',
'doughnut_chart' => 'Doughnut diagram',
'show_report' => 'Vis diagram',
'show_labels' => 'Vis labels',
'show_legend' => 'Vis legend',
'group_data_by' => 'Aggreger data pr',
'x_axis' => 'X-akse',
'y_axis' => 'Y-akse',
'show' => 'vis',
'percentage' => '%',
'count' => 'antal',
'sum' => 'sum',
'average' => 'gennemsnit',
'min' => 'min',
'max' => 'max',
'variance' => 'varians',
'standard_deviation' => 'standard afvigelse',
'of' => 'af',
'simple_report' => 'simple rapport',
'advanced_sql_report' => 'avanceret SQL rapport',
'type_your_custom_sql_query_here' => 'Skriv dit eget SQL udtryk her: ',
'current_search_filter_is_not_used' => '(Det nuværende søgefilter bruges ikke)',
'advanced_sql_reports_are_disabled' => 'Avanceret SQL rapport er ikke aktiv',
'advanced_sql_report_instructions_first_part' => 'Du kan skrive dit eget select query. Hvis du f.eks. har en <b>customers</b> tabel med et <b>age_customer</b> feld, kan du vise alders sammensætningen af dine kunder med følgende query:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Husk at det første felt du vælger vil blive brugt som <b>x-akse</b> i grafen, det andet felt som <b>y-akse</b>.<br/><br/>Læs dokumentationen for flere eksempler.',
'generate_report' => 'Dan diagram',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'Brug af semikolon (;) er ikke tilladt af sikkerhedsgrunde, du kan udelade det sidste semikolon.',
'sql_report_must_start_with_select' => 'Din SQL rapport skal starte med "SELECT "',
'show_embed_code' => 'Show embed code',
'embed_code_instructions' => 'You can copy the code below and paste it in a custom page to embed this graph; by embedding several graphs in a page you can easily create a dashboard. Please note that, if the report has been generated after a search, the search filter is not saved in the embed code. If you need to embed a report based on a stable search filter, the best way is to create a VIEW and generate the report starting from it. Also consider that pagination is not available in an embedded grid report, only X records will be displayed, where X is your current <i>items per page</i> setting.', // to change
'produce_pdf' => 'Dan PDF',
'choose_pdf_template' => 'Vælg PDF skabelon',
'no_pdf_template' => 'Standard Template', // to change
'show_revisions' => 'Vis ændringer',
'hide_revisions' => 'Skjul ændringer',
'record_revisions' => 'Gem ændringer',
'revisions' => 'Ændringer',
'for_this_table_revisions_not_enabled_no_revisions' => 'For denne tabel, er ændringer ikke tilladt eller du har ikke lavet nogen ændringer.',
'generate_pivot' => 'Dan pivot',
'you_might_have_additional_rows_admin_set_to' => 'Du har måske flere rækker - men administrator har sat max. antal rækker til ',
'add_column' => 'tilføj kolonner',
'remove_this_column' => 'fjern kolonner ',
'advanced_sql_report_instructions_pivot_part' => 'Til at danne pivot tabellen kan du bruge alias (til at angive lables) og du kan bruge mere end en aggregerings funktion f.eks.: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand',
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
	"int_db_empty" => "Fejl, intern database er tom.",
	"get" => "Fejl ved læsning af poster.",
	"no_functions" => "Fejl, Der er ikke valgt nogen funktion <br> Gå tilbage til homepage.",
	"no_unique_key" => "Fejl. Der er ingen primær nøgle i tabellen.",
	"upload_error" => "Der opstod en fejl ved upload en en fil.",
	"no_authorization_update" => "Du har ikke tilladelse til at opdatere denne post.",
	"no_authorization_delete" => "Du har ikke tilladelse til at slette denne post.",
	"no_authorization_view" => "Du har ikke tilladelse til at se denne post.",
	"deleted_only_authorizated_records" => "Kun de poster du har autorisation til er blevet slettet.",
	"record_from_which_you_come_no_longer_exists" => "Den post du kom fra findes ikke længere.",
	"date_not_representable" => "En dato i denne post kan ikke vises i DaDaBIK dag-måned-år listbox, værdien er: ",
	"this_record_is_the_last_one" => "Dette er den sidste post.",
	"this_record_is_the_first_one" => "Dette er den første post.",
	"current_password_wrong" => 'det nuværende password er forkert',
	"passwords_are_different" => 'de to passwords er forskellige',
	"new_password_must_be_different_old" => 'the new password must be different from the current one', // to change
	"new_password_is_empty" => 'det nye password er tomt',
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.' // to change
	);

//login messages
$login_messages_ar = array(
	"username" => "Brugernavn",
	"password" => "Password",
	"please_authenticate" => "Du skal identifiere dig for at kunne fortsætte",
	"login" => "login",
	"username_password_are_required" => "Brugernavn og password er nødvendige",
	"pwd_gen_link" => "Opret password",
	"incorrect_login" => "Brugernavn eller password er forkert",
	"pwd_explain_text" =>"Indsæt det ord der skal bruges som password og tryk <b>Crypt it!</b>.",
	"pwd_explain_text_2" =>"Tryk <b>Register</b> for at overføre det til den anden form",
	"pwd_suggest_email_sending"=>"Du kan sende en email til dig selv for at du kan huske passwordet",
	"pwd_send_link_text" =>"send mail!",
	"pwd_encrypt_button_text" => "Cript it!",
	"pwd_register_button_text" => "Register password and exit",
	"too_many_failed_login_account_blocked" => "For mange fejlagtige forsøg. Din konto er blevet låst.",
	"warning_you_still_have" => "Advarsel, du har kun ",
	"attempts_before_account_blocking" => " forsøg tilbage inden din konto bliver spærret.",
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
