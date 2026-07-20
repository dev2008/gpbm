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
    "insert"    => "Record toevoegen",
    "quick_search"    => "Snel zoeken",
    "search/update/delete" => "Zoek/update/wis records",
    "insert_short"    => "Toevoegen",
    "search_short" => "Zoek",
    "advanced_search" => "Uitgebreid zoeken",
    "insert_anyway"    => "Altijd toevoegen",
    "search"    => "Zoek voor record",
    "update"    => "Update dit record",
    "ext_update"    => "Update uw profiel",
    "yes"    => "Ja",
    "no"    => "Nee",
    "go_back" => "Terug",
    "edit" => "Edit",
    "delete" => "Verwijder",
    "details" => "Details",
    "insert_as_new" => "Als nieuw invoegen",
    "multiple_inserts" => "Meerdere invoegen",
    "change_table" => "Verander tabel",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
    "cant_edit_record_locked_by" => "U kunt niet editeren, record in gebruik door: ",
    "lost_locked_not_safe_update" => "Record is niet (meer) vergrendeld. Het is niet veilig om te updaten. Herbegin ajb.",
    "insert_item" => "Item invoegen",
    "show_all_records" => "Toon alle records",
    "show_records" => "Toon records",
    "ldap_user_dont_update" => "Dit is een geïmporteerde gebruiker (LDAP, Google, ...): enkel de groep moet veranderd worden, indien nodig.",
	"leave_blank_keep_current_password" => "Leeg laten om het huidige paswoord te behouden",
	"users_will_be_forced_change_after_login_except_ldap" => "Indien ingeschakeld zal de gebruiker een nieuw paswoord moeten instellen bij de volgende login. Dit geldt niet voor externe authenticatie (bv. LDAP, Google)",
	"deleting_group_also_delete_users" => "Opgelet: bij het verwijderen van een groep zullen alle gebruikers ook verwijderd worden",
    "remove_search_filter" => "Wis zoekfilter",
	"set_default_search_filter" => "instellen van het standaard zoekfilter",
    "logout" => "Log uit",
    "top" => "Top",
    "last_search_results" => "Laatste zoekresultaten",
    "show_all" => "Toon Alles",
    "home" => "Home",
    "select_operator" => "Selecteer de operator:",
    "all_conditions_required" => "Alle condities zijn vereist",
    "any_conditions_required" => "Een of meerdere condities zijn vereist",
    "all_contacts" => "Alle contacten",
    "removed" => "verwijderd",
    "please" => "Gelieve",
    "and_check_form" => "en controleer het formulier.",
    "and_try_again" => "en probeer opnieuw.",
    "none" => "Geen",
    "are_you_sure" => "Weet U het zeker?",
    "delete_all" => "Alles wissen",
    "really?" => "Zeker?",
    "delete_are_you_sure" => "U gaat onderstaand record wissen, zeker ?",
    "required_fields_missed" => "Niet alle vereiste velden zijn ingevuld.",
    "alphabetic_not_valid" => "Getallen in een alleen-letters veld ?",
    "numeric_not_valid" => "Niet numerieke tekens in een alleen-cijfer veld.",
    "email_not_valid" => "Het/De emailadress(en) is/zijn ongeldig.",
    "timestamp_not_valid" => "Ingevoegde tijd is ongeldig.",
    "url_not_valid" => "Ongeldig webadres/URL.",
    "phone_not_valid" => "Telefoonnummer niet geldig.",
    "similar_records" => "Onderstaande records gelijken sterk op de toe te voegen record  (max ".$number_duplicated_records." gelijkaardige items getoond, mogelijk meerdere.).<br>Wat wilt U doen?",
    "similar_records_short" => "Onderstaande records gelijken sterk op de toe te voegen record  (max ".$number_duplicated_records." gelijkaardige items getoond, mogelijk meerdere.)",
    "date_not_valid" => "Ongeldige datum.",
    "entered_value_not_valid" => "De ingevoerde waarde is niet geldig.",
	"id_group_admin_by_non_admin_not_valid" => "U kunt geen admin maken of wijzigen als u zelf geen admin bent",
    "no_records_found" => "Geen records gevonden.",
    "records_found" => "records gevonden",
    "number_records" => "Aantal records: ",
    "details_of_record" => "Details van het record",
    "details_of_record_just_inserted" => "Details ingevoegd record",
    "edit_record" => "Editeer record",
    "back_to_the_main_table" => "Terug naar hoofdtabel",
    "previous" => "Vorige",
    "next" => "Volgende",
    "edit_profile" => "Update uw profiel",
    "i_think_that" => "Ik denk dat ",
    "is_similar_to" => " gelijk is aan ",
    "page" => "Pagina ",
    "of" => " van ",
    "records_per_page" => "records per pagina",
    "day" => "Dag",
    "month" => "Maand",
    "year" => "Jaar",
    "administration" => "Administratie",
    "create_update_internal_table" => "Maak/update interne tabel",
    "other...." => "Andere....",
    "insert_record" => "Nieuw record toevoegen",
    "search_records" => "Zoek voor record",
    "exactly" => "precies",
    "like" => "bevattende",
    "required_fields_red" => "Vereiste velden zijn rood.",
    "insert_result" => "Toevoegen resultaat:",
    "record_inserted" => "Record correct toegevoegd.",
	"a_corresponding_user_account" => 'Een overeenkomstig gebruikersaccount',
	"has_been_created_with_random_password" => 'werd gemaakt met een willekeurig wachtwoord',
    "update_result" => "Update resultaat:",
    "record_updated" => "Record correct geüpdatet.",
    "profile_updated" => "Uw profiel is correct geüpdatet.",
    "delete_result" => "Wis resultaat:",
    "record_deleted" => "Record correct verwijderd.",
	"records_deleted" => "Item(s) correctly deleted.", // to change
    "duplication_possible" => "Duplicatie is mogelijk",
    "fields_max_length" => "Teveel tekst in 1 of meerdere velden.",
    "you are_going_unsubscribe" => "U zal verwijderd worden van de mailinglijst. Verder gaan ?",
    "current_upload" => "Huidig bestand",
    "delete" => "verwijder",
    "total_records" => "Totaal records",
    "confirm_delete?" => "Bevestig wissen ?",
    "unselect_all" => "Deselecteer alles",
    "select_all" => "Selecteer alles",
    "only_elements_this_page_selected_other_pages_kept" => "Enkel de elementen op de huidige pagina worden geselecteerd. Indien u elementen op andere pagina’s geselecteerd hebt, dan worden deze selecties bewaard.",
    "all_elements_will_be_unselected_also_other_pages" => "Alle selecties worden verwijderd, ook deze op andere pagina’s",
    "delete_selected" => "Verwijder geselecteerde",
    "is_equal" => "gelijk aan",
    "is_different" => "verschillend",
    "is_not_null" => "niet NULL",
    "is_not_empty" => "niet leeg",
    "contains" => "bevat",
    "doesnt_contain" => "bevat niet",
    "starts_with" => "begint met",
    "ends_with" => "eindigt met",
    "greater_than" => "groter dan",
    "less_than" => "kleiner dan",
    "greater_equal_than" => "groter of =",
    "less_equal_than" => "kleiner of =",
    "between" => "tussen",
    "between_and" => "EN",
    "export_to_csv" => "Export naar CSV",
	"export_to_ical" => 'Exporteer iCal',
	"generate_ical_url" => 'Genereer de iCal URL',
	"generate_ical_url_page_1" => 'Hier is de URL om je te abonneren, je kunt deze toevoegen aan je agenda-app (zoals Google Agenda of Apple Calendar)',
	"generate_ical_url_page_2" => 'Houd er rekening mee dat het uw gebruikersnaam bevat en een token dat nooit verloopt. Het bereik van het token is beperkt tot het lezen van gegevens uit de tabel',
	"generate_ical_url_page_3" => "en de gekoppelde tabellen (indien aanwezig).",
    "new_insert_executed" => "Nieuwe insert uitgevoerd",
    "new_update_executed" => "Nieuwe update uitgevoerd",
    "null" => "NULL",
    "is_null" => "is NULL",
    "is_empty" => "is leeg",
    "continue" => "verder",
    "current_password" => "huidig paswoord",
    "new_password" => "nieuw paswoord",
    "new_password_repeat" => "nieuw paswoord (herhaal)",
    "password_changed" => "het paswoord is veranderd",
    "change_your_password" => "verander uw paswoord",
    "your_info" => "uw info",
    "sort_by" => "sorteer op",
    "sort" => "sorteer",

    "pie_chart" => "Taartdiagram",
    "bar_chart" => "Staafdiagram",
    "line_chart" => "Lijndiagram",
    "doughnut_chart" => "Ringdiagram",
    "show_report" => "Toon rapport",
    "show_labels" => "Toon labels",
    "show_legend" => "Toon legende",
    "group_data_by" => "Groepeer data op",
    "x_axis" => "x-as",
    "y_axis" => "y-as",
    "show" => "toon",
    "percentage" => "%",
    "count" => "aantal",
    "sum" => "som",
    "average" => "gemiddelde",
    "min" => "min",
    "max" => "max",
    "variance" => "variantie",
    "standard_deviation" => "standaardafwijking",
    "of" => "van",

    "simple_report" => "Eenvoudig rapport",
    "advanced_sql_report" => "Geavanceerd SQL-rapport",
    "type_your_custom_sql_query_here" => "Typ uw SQL-query hier: ",
    "current_search_filter_is_not_used" => "(Huidige zoekfilter wordt niet gebruikt)",
    "advanced_sql_reports_are_disabled" => "Geadvanceerde SQL reports zijn uitgeschakeld",
    "advanced_sql_report_instructions_first_part" => "U kunt een eigen SQL select query schrijven, bijvoorbeeld : veronderstel dat er een <b>klanten</b> tabel is met een <b>leeftijd_klant</b> veld, dan kunt u de leeftijd tonen met volgende:",
    "advanced_sql_report_instructions_query_part" => "<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>", // DON'T TRANSLATE, LEAVE IT UNCHANGED
    "advanced_sql_report_instructions_second_part" => "Opgelet, eerste geselecteerde veld wordt gebruikt voor <b>X axis</b> of the graph, the second field for the <b>Y axis</b>.<br/><br/>Lees de handleiding voor meer voorbeelden.",
    "generate_report" => "Maak rapport",
    "use_semicolon_forbidden_omit_trailing_semicolmn" => "Het gebruik van puntkomma is om veiligheidsreden uitgeschakeld. Laat de laatste puntkomma weg.",
    "sql_report_must_start_with_select" => "Uw eigen SQL-rapport moet starten met 'SELECT '",
    "show_embed_code" => "Toon embedded code",
    "embed_code_instructions" => "Onderstaande code kunt U copy-pasten in een eigen pagina om deze te tonen; door er meerdere te gebruiken kunt U een dashboard maken. Opgelet na creatie is de zoekfilter niet bewaard in de embedded code. Beste manier is om een VIEW te maken en daarvan een rapport te maken en dan vanaf daar te beginnen. Denk eraan dat paginering niet beschikbaar is in een ingesloten gridrapport. Er zullen slechts X records getoond worden met X = items per pagina",

    "produce_pdf" => "Maak PDF",
    "choose_pdf_template" => "Kies PDF template",
    "no_pdf_template" => 'Standard Template', // to change
    "show_revisions" => "Toon revisies",
    "hide_revisions" => "Verberg revisies",
    "record_revisions" => "Neem revisies op",
    "revisions" => "Revisies",
    "for_this_table_revisions_not_enabled_no_revisions" => "Voor deze tabel zijn geen revisies actief of aanwezig.",
    "generate_pivot" => "Genereer draaitabel",
    "you_might_have_additional_rows_admin_set_to" => "Mogelijk meerdere rijen maar admin heeft max aantal rijen gezet op ",
    "add_column" => "voeg kolom toe",
    "remove_this_column" => "verwijder deze kolom",
    "advanced_sql_report_instructions_pivot_part" => "Voor draaitabellen kunt u aliassen gebruiken(om labels te specificeren) en u kunt meer dan 1 aggregatie gebruiken, voorbeeld: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand",
    'advanced_sql_report_instructions_stat_card_part' => 'For <b>stat cards</b>, instead, there is no aggregation; you must select only one element. For example, this counts the number of customers: SELECT COUNT(*) FROM customers.', // to change

    "record_inserted_close_window" => "Het item werd correct ingevoegd, u kunt dit venster sluiten <a href='#' onclick='window.close();return false;'>close</a>.",
    "import" => "Importeer",
    "file_type" => "Bestandstype",
    "delimiter" => "Scheidingsteken",
    "values_enclosed_by" => "Waarden kunnen ingesloten worden in",
    "load_file" => "Upload bestand",
    "error_no_file_selected" => "Fout, U hebt geen bestand geselecteerd om te uploaden",
    "values_enclosed_cannot_be_blank" => "De paramater 'Waarden optioneel ingesloten in' mag niet blanco zijn, U kunt de defaultwaarde behouden als u geen eigen karakter gebruikt",
    "error_file_type_not_supported_or_different" => "Fout, dit bestandstype wordt niet ondersteund of het is niet hetzelfde als deze van de vorige pagina",
    "error_too_much_time_passed" => "Fout, er is te veel tijd verstreken",
    "processing_row" => "Rij wordt verwerkt",
    "new_elements_will_be_inserted_to_proceed_click_continue" => "nieuwe elementen worden toegevoegd. Om verder te gaan, klik op 'Verder' op het einde van de pagina",
    "following_as_example_20_rows" => "Hier volgen enkel de eerste 20 rijen van uw bestand",
    "possible_duplication_continue_to_update" => "Mogelijk duplicaat, sommige elementen hebben dezelfde waarde  in het unieke veld (het duplicaat kan zich ook in uw bestand bevinden). Hier zijn de duplicaten. Momenteel werden geen elementen toegevoegd of gewijzigd. Als u op 'Verder' klikt op het einde van de pagina, zullen de elementen gewijzigd worden met de informatie van het bestand.",
    "elements_have_been_inserted_updated" => "elementen werden ingevoegd/gewijzigd.",
    "to_verify_elements_click_continue_filter_set" => "Om de ingevoerde/gewijzigde elementen te controleren klik op 'Verder'. Er is een filter zodat enkel de ingevoerde/gewijzigde elementen getoond worden (mogelijk heeft de administrator bijkomende beperkingen ingesteld).",
    "no_element_has_been_inserted" => "Er werd geen element ingevoegd.",
    "error_no_sheet_with_name"=> "Fout, er is geen blad met de naam:",
    "elements_results_last_import" => "De elementen die u ziet, zijn het resultaat van de meest recent import (indien de administrator bijkomende filters gedefinieerd heeft, ziet u er maar een beperkt aantal van). Wil u alle elementen zien, klik dan op 'Wis zoekfilter'",
    "csv_file_must_be_utf8_encoded" => "Het CSV-bestand moet UTF-8 gecodeerd zijn.",
    "hide_show_quick_filters" => "Verberg/Toon de snelle filters",
    "show_search_url" => "Toon de zoek-URL",
    "search_url_instructions" => "Deze URL voert dezelfde zoekopdracht uit die u maakte, de sorteercriteria werden eveneens toegevoegd (voor zover nodig).",
    "double_click_to_edit" => 'Dubbelklikken om te editeren',
    "it_seems_you_uploaded_other_files_cancelled" => " Het lijkt dat u bepaalde bestanden in een andere vorm geüpload hebt maar dat u het proces niet voltooid hebt. Deze uploads werden niet uitgevoerd.",
    "number_uploaded_files" => "Aantal geüploade bestanden: ",
    "file_uploaded_file_will_replace" => "Bestand geüpload! Het bestand zal het bestaande bestand vervangen (als dit bestaat) nadat het formulier bewaard is.",
    "generic_upload_error" => "Algemene uploadfout!",
'collapse_sidebar' => 'Inklappen menubalk',
'ask_anything_about' => 'Vraag iets over',
'ask_ai' => 'Stel uw vraag aan AI',
'ask_questions_data_read_only' => 'Stel vragen over uw gegevens (alleen lezen).',
'ai_generated_answer' => 'Antwoord gemaakt door AI',
'dadabrain_created_separate_view_results' => 'DaDaBrain heeft een afzonderlijke view met resultaten gemaakt.',
'you_can_open_it' => 'U kunt het hier openen',
'here' => 'hier',
'please_verify_ai_generated_results_may_be_incorrect' => 'Gelieve de door AI gemaakte resultaten te controleren voor u beslissingen neemt. De resultaten kunnen verkeerd of onvolledig zijn.',
'to_answer_I_used_this_query' => 'Om te antwoorden heb ik deze SQL-query gebruikt',
'list_below_has_not_changed_current_filters_still_apply' => 'De lijst hieronder is niet gewijzigd. Uw huidige filters (als die er zijn) gelden nog steeds.',
'questions' => 'Vragen',
'discard_question' => 'Verwijder de vraag',
'question_discarded' => 'De vraag is correct verwijderd',
'dadabrain_disabled_check_enable_dadabrain' => '<b>DaDaBrain is uitgeschakeld</b>. Gelieve de configuratieparameter  $enable_dadabrain na te kijken en bekijk de verbonden veiligheidsgevolgen.',
'use_show_dadabrain_form_to_hide_ai_form' => 'Gebruik  $show_dadabrain_form om het DaDaBrain-formulier te verbergen.',
'open_the_view' => 'Open de view',
);

$normal_messages_ar['months_short'][1] = 'Jan';
$normal_messages_ar['months_short'][2] = 'Feb';
$normal_messages_ar['months_short'][3] = 'Mrt';
$normal_messages_ar['months_short'][4] = 'Apr';
$normal_messages_ar['months_short'][5] = 'Mei';
$normal_messages_ar['months_short'][6] = 'Jun';
$normal_messages_ar['months_short'][7] = 'Jul';
$normal_messages_ar['months_short'][8] = 'Aug';
$normal_messages_ar['months_short'][9] = 'Sep';
$normal_messages_ar['months_short'][10] = 'Okt';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Dec';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'Ma ';
$normal_messages_ar['days_short'][2] = 'Di ';
$normal_messages_ar['days_short'][3] = 'Wo ';
$normal_messages_ar['days_short'][4] = 'Do ';
$normal_messages_ar['days_short'][5] = 'Vr ';
$normal_messages_ar['days_short'][6] = 'Za ';
$normal_messages_ar['days_short'][7] = 'Zo ';


// error messages
$error_messages_ar = array (
    "int_db_empty" => "Fout, de interne database is leeg.",
    "get" => "Fout, kan de variabelen niet bekomen.",
    "no_functions" => "Fout, geen functies geselecteerd<br>Ga terug naar de homepagina.",
    "no_unique_key" => "Fout, U heeft geen primaire sleutel in uw tabel.",
    "upload_error" => "Er is een probleem met het uploaden van een bestand.",
    "no_authorization_update" => "U heeft geen rechten om dit record te wijzigen.",
    "no_authorization_delete" => "U heeft geen rechten om dit record te wissen.",
    "no_authorization_view" => "U heeft geen rechten om dit record te bekijken.",
    "deleted_only_authorizated_records" => "Enkel de records waar U rechten op heeft zijn verwijderd.",
    "record_from_which_you_come_no_longer_exists" => "Startrecord bestaat niet meer.",
    "date_not_representable" => "Een datumwaarde in dit record kan niet getoond worden met de DaDaBIK dag-maand-jaar lijstbox, de waarde is: ",
    "this_record_is_the_last_one" => "Dit is het laatste record.",
    "this_record_is_the_first_one" => "Dit is het eerste record.",
    "current_password_wrong" => 'huidig paswoord is fout',
    "passwords_are_different" => 'de twee paswoorden zijn niet gelijk',
    "new_password_must_be_different_old" => 'het nieuwe paswoord moet verschillend zijn van het huidige',
    "new_password_is_empty" => 'nieuw paswoord is leeg',
    "you_cant_live_edit_click_edit" => 'u kunt dit veld niet direct wijzigen, gelieve op het Wijzig-icoon te klikken om het volledige record te wijzigen.',
    "you_dont_have_enough_permissions_to_edit_field" => 'u hebt niet voldoende rechten om dit veld te wijzigen.'
);

//login messages
$login_messages_ar = array(
   "username" => "gebruikersnaam",
   "password" => "paswoord",
   "please_authenticate" => "Geef Uw logingegevens",
   "login" => "login",
   "username_password_are_required" => "Gebruikersnaam en paswoord vereist",
   "incorrect_login" => "Gebruikersnaam of paswoord niet correct",
   "pwd_gen_link" => "maak paswoord",
   "pwd_explain_text" =>"Geef uw paswoord en druk <b>Versleutel!</b>.",
   "pwd_explain_text_2" =>"Klik <b>Registreer/b> om in onderstaande te plaatsen",
   "pwd_suggest_email_sending"=> "U kan uzelf een mail sturen om het paswoord te onthouden",
   "pwd_send_link_text" =>"Verstuur email!",
   "pwd_encrypt_button_text" => "Versleutel!",
   "pwd_register_button_text" => "Registreer paswoord en be-eindig",
   "too_many_failed_login_account_blocked" => "Te veel pogingen, account is geblokkeerd.",
   "warning_you_still_have" => "Waarschuwing, U heeft nog maar ",
   "attempts_before_account_blocking" => " pogingen voordat uw account geblokkeerd wordt.",
   "verification_code" => "Verificatiecode",
   "verification_code_is_required" => "Een verificatiecode is vereist",
   "incorrect_verification_code" => "De verificatiecode is niet correct",
   "enable_two-factor_authentication" => "Schakel tweestapsverificatie in",
   "two-factor_authentication" => "Tweestapsverificatie",
);

// Link "Register" in the login form
$login_messages_ar['register'] = 'Registreer';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Maak uw account';
$login_messages_ar['email'] = 'mail';
$login_messages_ar['first_name'] = 'voornaam';
$login_messages_ar['last_name'] = 'naam';
$login_messages_ar['registration_form_checkbox_1'] = 'Aanvaard <a href="example_terms.html" target="_blank">terms and conditions</a>';
$login_messages_ar['registration_form_checkbox_2'] = 'Aanvaard <a href="example_terms.html" target="_blank">terms and conditions</a>';
$login_messages_ar['registration_form_checkbox_3'] = 'Aanvaard <a href="example_terms.html" target="_blank">terms and conditions</a>';

// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Registreer een nieuw account';
$login_messages_ar['back_to_login'] = 'Terug naar login';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Account is gemaakt, u zult een mail met bevesting ontvangen met een activatielink. Klik op de link om uw account te activeren.';
$login_messages_ar['email_confirmed_login'] = 'Uw account is geactiveerd, u kan nu inloggen.';
$login_messages_ar['account_created_login'] = 'Uw account is gemaakt, u kan nu inloggen';
$login_messages_ar['confirmation_link_expired_resent'] = 'De activatielink is verlopen, een nieuwe link zal opgezonden worden naar uw mailadres.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'Deze activatielink is niet correct, uw account kan niet geactiveerd worden.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'Uw mail werd nog niet bevestigd.';
$login_messages_ar['email_already_in_use'] = 'Dit mailadres is al in gebruik.';
$login_messages_ar['username_already_in_use'] = 'Deze gebruikersnaam is al in gebruik.';
$login_messages_ar['registration_email_subject'] = "Gelieve uw registratie te bevestigen";
$login_messages_ar['registration_email_content'] = "Iemand (hopelijk uzelf) heeft een account geregistreerd op ".$site_url_to_display.". Klik binnen de 24 uur op deze link om uw registratie te bevestigen:";




// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Paswoord vergeten';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Voer uw mailadres in, u zal uw gebruikersnaam en een tijdelijk nieuw paswoord ontvangen.';

// form submit button
$login_messages_ar['submit'] = 'Verzend';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'Als dit mailadres overeenkomt met een bestaande gebruiker, zal u een mail ontvangen met een tijdelijk paswoord.';

// email subject
$login_messages_ar['your_temporary_password'] = 'Uw tijdelijk paswoord';

// email body
$login_messages_ar['temporary_password_email_content_part_1'] = 'Iemand (hopelijk uzelf) heeft een nieuw paswoord gevraagd om toegang te krijgen tot ".$site_url_to_display."\n\nAls U dit nieuw paswoord aanvroeg, is alles in orde en hier is uw nieuw paswoord (slechts geldig gedurende vijf minuten). Denk eraan dat mail geen veilig communicatiemiddel is voor een paswoord en verander dus uw paswoord onmiddellijk na het inloggen. Gebruik het tijdelijke paswoord nooit als uw hoofdpaswoord.';
$login_messages_ar['temporary_password_email_content_part_2'] = 'Als u niet kunt inloggen met dit tijdelijk paswoord, heeft iemand anders eerder met uw account ingelogd. Gelieve uw administrator te verwittigen.\n\nIndien u geen nieuw paswoord aangevraagd hebt, probeert iemand met uw account binnen te geraken: log zo snel mogelijk in met uw OUD paswoord (dat maakt het tijdelijke paswoord ongeldig) en verwittig uw administrator. Als u niet kunt inloggen met uw oud paswoord, heeft iemand waarschijnlijk al ingelogd. Verwittig uw administrator.';


$login_messages_ar['intro_2fa_secret_page'] = '<h2>Belangrijk: deze pagina wordt slechts eenmaal getoond om veiligheidsredenen.</h2><p>Voer de setupinstructies volledig uit voor U de pagina verlaat, vermits U deze informatie achteraf niet meer kunt raadplegen.</p><p><b>Download een authenticatie-app:</b> ga naar uw app-winkel (Google Play/App Store) en download een authenticatie-app, zoals Google Authenticator of Authy.<br><br><b>Scan de QR-code:</b> gebruik de app om de QR-code hieronder te scannen. Dit zal uw account aan de authenticatie-app verbinden.<br><br><b>Toekomstige Logins:</b> de volgende keer dat U inlogt, zult U gevraagd worden om de verificatiecode in te tikken die door uw authenticatie-app gegenereerd wordt.</p>';






?>
