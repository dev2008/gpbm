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
	"insert"    => "Inserisci un nuovo record",
	"quick_search"    => "Ricerca veloce",
	"search/update/delete" => "Cerca/aggiorna/cancella record",
	"insert_short"    => "Inserisci",
	"search_short" => "Cerca",
	"advanced_search" => "Ricerca Avanzata",
	"insert_anyway"    => "Inserisci comunque",
	"search"    => "Cerca record",
	"update"    => "Salva",
	"ext_update"    => "Aggiorna il tuo profilo",
	"yes"    => "Sì",
	"no"    => "No",
	"go_back" => "Torna indietro",
	"edit" => "Modifica",
	"delete" => "Cancella",
	"details" => "Dettagli",
	"insert_as_new" => "Inserisci come nuovo",
	"multiple_inserts" => "Inserimenti multipli",
	"change_table" => "Cambia tabella",
	"search_includes_following_fields" => 'La ricerca include i seguenti campi:',
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Non è possibile modificare questo record, il record è bloccato dall'utente: ",
	"lost_locked_not_safe_update" => "Non hai il blocco oppure hai perso il blocco su questo record, non è possibile effettuare la modifica, devi ricominciare la modifica da capo",
	"insert_item" => "Inserisci item",
	"show_all_records" => "Visualizza tutti i records",
	"show_records" => "Mostra record",
	"ldap_user_dont_update" => "Questo è un utente importato (LDAP, Google, ...): l'unica informazione che dovresti modificare (se necessario) è il suo gruppo.",
	"leave_blank_keep_current_password" => "Lascia in bianco per mantenere la password corrente",
	"users_will_be_forced_change_after_login_except_ldap" => "Se selezioanto, l'utente dovrà modificare la password al prossimo login. Non viene applicato in caso di autenticazione esterna (es. LDAP, Google).",
	"deleting_group_also_delete_users" => "Se viene cancellato un gruppo, vengono automaticamente cancellati anche tutti gli utenti appartenenti al gruppo stesso",
	"remove_search_filter" => "rimuovi filtro di ricerca",
	"set_default_search_filter" => "imposta filtro default",
	"logout" => "Logout",
	"top" => "Top",
	"last_search_results" => "Risultati ultima ricerca",
	"show_all" => "Visualizza tutti",
	"home" => "Home",
	"select_operator" => "Seleziona operatore:",
	"all_conditions_required" => "Tutte le condizioni",
	"any_conditions_required" => "Almeno una condizione",
	"all_contacts" => "Tutti i contatti",
	"removed" => "cancellato/i",
	"please" => "",
	"and_check_form" => "e controlla il form.",
	"and_try_again" => "e prova ancora.",
	"none" => "nessuno",
	"are_you_sure" => "Sei sicuro?",
	"delete_all" => "cancella tutti",
	"really?" => "Veramente?",
	"delete_are_you_sure" => "Stai per cancellare questo record, sei sicuro?",
	"required_fields_missed" => "Non hai compilato alcuni campi obbligatori.",
	"alphabetic_not_valid" => "Hai inserito un numero in un campo alfabetico.",
	"numeric_not_valid" => "Hai inserito un carattere non numerico in un campo numerico.",
	"email_not_valid" => "Hai inserito uno o piu' indirizzi e-mail non validi.",
	"timestamp_not_valid" => "Hai inserito uno o piu' timestamp non validi.",
	"url_not_valid" => "Hai inserito uno o più URL non validi.",
	"phone_not_valid" => "Hai inserito uno o piu' numeri di telefono non validi.<br>Devi utilizzare il formato \"+(codice nazionale)(prefisso)(numero)\" es. +390523599318, 00390523599318, 0523599318.",
	"date_not_valid" => "Hai inserito una o più date non valide.",
	"entered_value_not_valid" => "Il valore inserito non è valido.",
	"id_group_admin_by_non_admin_not_valid" => "Non puoi creare o modificare un utente admin se non sei admin",
	"similar_records" => "I seguenti record sembrano simili a quello che vuoi inserire (mostro al massimo ".$number_duplicated_records." simili, potrebbero essercene di più).<br>Come vuoi procedere?",
	"similar_records_short" => "I seguenti record sembrano simili a quello che vuoi inserire (mostro al massimo ".$number_duplicated_records." simili, potrebbero essercene di più).",
	"no_records_found" => "Non è stato trovato nessun record.",
	"records_found" => "record trovati.",
	"number_records" => "Numero di record: ",
	"details_of_record" => "Dettagli del record",
	"details_of_record_just_inserted" => "Dettagli del record appena inserito",
	"edit_record" => "Modifica il record",
	"back_to_the_main_table" => "Torna alla tabella principale",
	"previous" => "Precedente",
	"next" => "Successivo",
	"edit_profile" => "Aggiorna le informazioni relative al tuo profilo",
	"i_think_that" => "Penso che  ",
	"is_similar_to" => " sia simile a ",
	"page" => "Pagina ",
	"of" => " di ",
	"records_per_page" => "record per pagina",
	"day" => "Giorno",
	"month" => "Mese",
	"year" => "Anno",
	"administration" => "Amministrazione",
	"create_update_internal_table" => "Crea o aggiorna la tabella interna",
	"other...." => "altro....",
	"insert_record" => "Inserisci un nuovo record",
	"search_records" => "Cerca record",
	"exactly" => "uguale",
	"like" => "simile",
	"required_fields_red" => "I campi obbligatori sono in rosso.",
	"insert_result" => "Risultato dell'inserimento:",
	"record_inserted" => "Il record è stato inserito correttamente.",
	"a_corresponding_user_account" => 'Un account utente',
	"has_been_created_with_random_password" => 'è stato creato con una password casuale',
	"update_result" => "Risultato dell'aggiornamento:",
	"record_updated" => "Il record è stato aggiornato correttamente.",
	"profile_updated" => "Il tuo profilo è stato correttamente aggiornato.",
	"delete_result" => "Risultato della cancellazione:",
	"record_deleted" => "Il record è stato cancellato correttamente.",
	"records_deleted" => "Il record - o i record - sono stati cancellati correttamente.",
	"duplication_possible" => "E' possibile che si verifichi una duplicazione",
	"fields_max_length" => "Hai inserito troppo testo in uno o più caratteri.",
	"current_upload" => "File corrente",
	"delete" => "cancella",
	"total_records" => "Record totali",
	"confirm_delete?" => "Confermi la cancellazione?",
    "unselect_all" => "Deseleziona tutti",
    "select_all" => "Seleziona tutti",
    "only_elements_this_page_selected_other_pages_kept" => "Solo gli elementi della pagina corrente verranno selezionati. Se hai selezionato elementi in altre pagine, la selezione verrà mantenuta.",
    "all_elements_will_be_unselected_also_other_pages" => "Tutti gli elementi verranno deselezionati, anche le selezioni effettuate in altre pagine.",
    "delete_selected" => "Cancella selezionati",
	"is_equal" => "è uguale a",
	"is_different" => "è diverso da",
	"is_not_null" => "non è null",
	"is_not_empty" => "non è vuoto",
	"contains" => "contiene",
	"doesnt_contain" => "non contiene",
	"starts_with" => "inizia con",
	"ends_with" => "finisce con",
	"greater_than" => ">",
	"less_than" => "<",
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
    "between" => "intervallo",
    "between_and" => "a", // used for the between search operator: between .... AND .....
	"export_to_csv" => "Esporta CSV",
	"export_to_ical" => 'Esporta iCal',
	"generate_ical_url" => 'Genera iCal URL',
	"generate_ical_url_page_1" => 'Ecco l\'URL a cui iscriverti, puoi aggiungerlo alla tua applicazione calendario (come Google Calendar o Apple Calendar)',
	"generate_ical_url_page_2" => 'Tieni presente che contiene il tuo nome utente e un token che non scade mai. L’ambito del token è limitato alla sola lettura dei dati dalla tabella',
	"generate_ical_url_page_3" => "e alle sue linked tables (se ce ne sono).",
	"import" => "Importa",
	"new_insert_executed" => "Nuovo inserimento eseguito",
	"new_update_executed" => "Nuovo aggiornamento eseguito",
	"null" => "Null",
	"is_null" => "è null",
	"is_empty" => "è vuoto",
	"continue" => "Continua",
	'current_password' => 'password corrente',
	'new_password' => 'nuova password',
	'new_password_repeat' => 'nuova password (ripetere)',
	'password_changed' => 'la password è stata cambiata',
	'change_your_password' => 'cambia la password',
	'your_info' => 'le tue informazioni',
	'sort_by' => 'ordina per',
	'sort' => 'ordina',
	'pie_chart' => 'Grafico a torta',
'bar_chart' => 'Grafico a barre',
'line_chart' => 'Grafico a linee',
'doughnut_chart' => 'Grafico a doughnut',
'show_report' => 'Mostra grafico',
'show_labels' => 'Mostra etichette',
'show_legend' => 'Mostra legenda',
'group_data_by' => 'Aggrega data per ',
'x_axis' => 'Asse X',
'y_axis' => 'Asse Y',
'show' => 'mostra',
'percentage' => '%',
'count' => 'count',
'sum' => 'sum',
'average' => 'average',
'min' => 'min',
'max' => 'max',
'variance' => 'variance',
'standard_deviation' => 'standard deviation',
'of' => 'di',
'simple_report' => 'report semplice',
'advanced_sql_report' => 'report avanzato SQL',
'type_your_custom_sql_query_here' => 'Scrivi la tua query SQL personalizzata qui: ',
'current_search_filter_is_not_used' => '(Il filtro di ricerca corrente non sarà utilizzato)',
'advanced_sql_reports_are_disabled' => 'Advanced SQL reports are disabled',
'advanced_sql_report_instructions_first_part' => 'Puoi scrivere una query SQL personalizzata, es. immagina di avere unata tabella <b>clienti</b> con un campo <b>eta_cliente</b>, puoi mostrare la composizione di età dei tuoi clienti utilizzando la query seguente:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT eta_cliente, count(*) FROM clienti GROUP BY eta_cliente</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Ricorda, il primo campo che selezioni verrà utilizzato per <b>asse X</b> del grafico, il secondo campo per  <b>asse Y</b>.<br/><br/>Leggi la documentazione per vedere ulteriori esempi.',
'generate_report' => 'Genera grafico',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'L\'uso del punto e virgola (;) non è permesso per motivi di sicurezza, puoi omettere il punto e virgola finale.',
'sql_report_must_start_with_select' => 'Il report SQL personalizzato deve iniziare con "SELECT"',
'show_embed_code' => 'Mostra embed code',
'embed_code_instructions' => 'Puoi copiare il codice qua sotto e incollarlo in una custom page per includere questo report grafico o tabulare; includendo diversi report nella stessa pagina puoi facilmente creare una dashboard. Nota che se questo report è stato generato dopo una ricerca, il filtro di ricerca non verrà salvato nell\'embed code. Se ti serve includere un report basato su di un filtro di ricerca, il modo migliore è quello di creare una VIEW e di generare il report a partire dalla VIEW stessa. Considera inoltre che la paginazione non è disponibile quando includi un report tabulare, solo X elementi saranno visualizzati, dove X è la tua attuale impostazione per <i>record per pagina</i>.',
'produce_pdf' => 'Produci PDF',
'choose_pdf_template' => 'Scegli template PDF',
'no_pdf_template' => 'Standard template',
'show_revisions' => 'Mostra revisioni',
'hide_revisions' => 'Nascondi revisioni',
'record_revisions' => 'Revisioni di questo record',
'revisions' => 'Revisioni',
'for_this_table_revisions_not_enabled_no_revisions' => 'Per questa tabella, le revisioni non sono abilitati oppure non è ancora stata registarta alcuna revisione.',
'generate_pivot' => 'Genera pivot',
'you_might_have_additional_rows_admin_set_to' => 'Potresti avere ulteriori righe ma l\'amministratore ha impostato il numero massimo di righe a ',
'add_column' => 'aggiungi colonna', // add column in the pivot report
'remove_this_column' => 'rimuovi colonna', // remove column in the pivot report
'advanced_sql_report_instructions_pivot_part' => 'Per la generazione di tabelle pivot, in aggiunta, puoi usare gli alias (per specificare la intestazione delle colonne della tabella) e puoi usare più di una funzione aggregata, per esempio: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand',
'advanced_sql_report_instructions_stat_card_part' => 'Per le <b>stat cards</b>, invece, non c\\\'è aggregazione; devi selezionare un solo elemento. Per esempio, questo conta il numero totale dei clienti: SELECT COUNT(*) FROM customers.',

"record_inserted_close_window" => "Il record è stato inserito correttamente, puoi <a href='#' onclick='window.close();return false;'>chiudere</a> questa finestra.",

'file_type' => 'Tipo di file',
'delimiter' => 'Delimitatore',
'values_enclosed_by' => 'Valori opzionalmente racchiusi da',
'load_file' => 'Carica file',
'error_no_file_selected' => 'Errore, non hai selezionato un file da caricare.',
'values_enclosed_cannot_be_blank' => 'Il parametro "Valori opzionalmente racchiusi da" non può essere vuoto, puoi lasciare il valore di default se non usi alcun carattere.',
'error_file_type_not_supported_or_different' => 'Errore, questo tipo di file non è supportato oppure non è quello che hai selezionato nella pagina precedente',
'error_too_much_time_passed' => 'Errore, è trascorso troppo tempo. Ti invitiamo a ricominciare la procedura di importazione.',
'processing_row' => 'Sto processando la riga',

'new_elements_will_be_inserted_to_proceed_click_continue' => 'nuovi elementi verranno importati. Per procedere all\'inserimento premi il pulsante "Continua" a fondo pagina.', // this message will be used with a number, e.g. "5 nuovi elementi verranno importati ... ".
'following_as_example_20_rows' => 'Di seguito sono mostrate, a titolo di esempio, solo le prime 20 righe del tuo file',
'possible_duplication_continue_to_update' => 'E\' stata rilevata una possibile duplicazione, alcuni elementi hanno valori uguali sugli attributi univoci (la duplicazione potrebbe anche essere all\'interno del tuo file). Di seguito gli elementi duplicati. Al momento non è stato inserito o aggiornato alcun elemento. Cliccando continua a fondo pagina, per questi elementi, sarà effettuato un aggiornamento con i nuovi dati forniti nel file.',
'elements_have_been_inserted_updated' => 'elementi sono stati inseriti/aggiornati.', // this message will be used with a number, e.g. "5 elementi sono stati inseriti/aggiornati.",

'to_verify_elements_click_continue_filter_set' => 'Per verificare gli elementi inseriti/aggiornati premi il pulsante "Continua". E\' stato impostato un filtro di ricerca che ti permette di vedere solo gli elmenti inseriti/aggiornati (potresti non vederli tutti se sono stati impostati filtri aggiuntivi dall\'amministratore).',

'elements_results_last_import' => 'Gli elementi che vedi sono il risultato dell\'ultima importazione che hai effettuato (potresti non vederli tutti se l\'amministratore ha impostato altri filtri). Per tornare a vedere tutti gli elementi clicca su "Rimuovi filtro di ricerca"',


'no_element_has_been_inserted' => 'Nessun elemento è stato inserito.',
'error_no_sheet_with_name'=> 'Errore, nessun foglio di nome:',
'csv_file_must_be_utf8_encoded' => 'Il file CSV deve esere codificato in UTF-8.',
'hide_show_quick_filters' => 'Mostra/Nascondi filtri',
'show_search_url' => 'Mostra search URL',
'search_url_instructions' => 'Questa URL esegue la stessa ricerca che hai eseguito tu, applicando anche eventuali criteri di ordinamento.',
"double_click_to_edit" => 'Doppio click per modificare',
'it_seems_you_uploaded_other_files_cancelled' => ' Sembra che tu abbia caricato uno o più file da un\'altra form senza completare il salvataggio. Quei caricamenti sono stati annullati.',

'number_uploaded_files' => 'Numero di file caricati: ',
'file_uploaded_file_will_replace' => 'File caricato! Il file sostituirà quello corrente (se esistente) dopo aver salvato la form.',
'generic_upload_error' => 'Errore generico di upload! ',
'collapse_sidebar' => 'Chiudi barra menu',
'ask_anything_about' => 'Fai una domanda su',
'ask_ai' => 'Chiedi all\'AI',
'ask_questions_data_read_only' => 'Fai domande sui dati (sola lettura).',
'ai_generated_answer' => 'Risposta generata da AI',
'dadabrain_created_separate_view_results' => 'DaDaBrain ha creato una vista separata con i risultati.',
'you_can_open_it' => 'Puoi aprirla',
'here' => 'qui',
'please_verify_ai_generated_results_may_be_incorrect' => 'Verifica i risultati generati dall’AI prima di prendere decisioni. I risultati potrebbero essere incompleti o contenere errori.',
'to_answer_I_used_this_query' => 'Per rispondere, ho utilizzato la seguente query SQL',
'list_below_has_not_changed_current_filters_still_apply' => 'L’elenco sottostante non è stato modificato. I filtri attualmente applicati (se presenti) sono ancora attivi.',
'questions' => 'Domande',
'discard_question' => 'Elimina domanda',
'question_discarded' => 'Domanda correttamente eliminata',
'dadabrain_disabled_check_enable_dadabrain' => '<b>DaDaBrain è disabilitato</b>. Controlla il parametro di configurazione $enable_dadabrain e verifica le implicazioni di sicurezza.',
'use_show_dadabrain_form_to_hide_ai_form' => 'Utilizza il prametro $show_dadabrain_form per nascondere la form DaDaBrain AI.',
'open_the_view' => 'Apri la vista',




);
$normal_messages_ar['months_short'][1] = 'Gen';
$normal_messages_ar['months_short'][2] = 'Feb';
$normal_messages_ar['months_short'][3] = 'Mar';
$normal_messages_ar['months_short'][4] = 'Apr';
$normal_messages_ar['months_short'][5] = 'Mag';
$normal_messages_ar['months_short'][6] = 'Giu';
$normal_messages_ar['months_short'][7] = 'Lug';
$normal_messages_ar['months_short'][8] = 'Ago';
$normal_messages_ar['months_short'][9] = 'Set';
$normal_messages_ar['months_short'][10] = 'Ott';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Dic';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'Lun';
$normal_messages_ar['days_short'][2] = 'Mar';
$normal_messages_ar['days_short'][3] = 'Mer';
$normal_messages_ar['days_short'][4] = 'Gio';
$normal_messages_ar['days_short'][5] = 'Ven';
$normal_messages_ar['days_short'][6] = 'Sab';
$normal_messages_ar['days_short'][7] = 'Dom';

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Errore, il database interno è vuoto.",
	"get" => "Errore nelle variabili get.",


	"no_functions" => "Errore, non è stata selezionata alcuna funzione<br>Torna alla homepage.",
	"no_unique_key" => "Errore, non e' stata impostato nessuna chiave primaria nella tabella.",
	"upload_error" => "Si è verificato un errore durante l'upload del file.",
	"no_authorization_update" => "Non hai l'autorizzazione per modificare questo record.",
	"no_authorization_delete" => "Non hai l'autorizzazione per cancellare questo record.",
	"no_authorization_view" => "Non hai l'autorizzazione per vedere questo record.",
	"deleted_only_authorizated_records" => "Sono stati cancellati solo i record che sei autorizzato a cancellare.",
	"record_from_which_you_come_no_longer_exists" => "Il record dal quale provieni non esiste più.",
	"date_not_representable" => "Una data presente in questo record non può essere rappresentata con le listbox giorno-mese-anno, la data è: ",
	"this_record_is_the_last_one" => "Questo record è l'ultimo.",
	"this_record_is_the_first_one" => "Questo record è il primo.",
	"current_password_wrong" => 'la password corrente non è corretta',
	"passwords_are_different" => 'le due password non corrispondono',
	"new_password_must_be_different_old" => 'la nuova password deve essere diversa dalla password corrente',
	"new_password_is_empty" => 'la nuova password è vuota',
	"you_cant_live_edit_click_edit" => 'Non puoi utilizzare il "live edit" per questo campo, clicca sull\'icona modifica alla tua sinistra per modificare l\'intero record.',
	"you_dont_have_enough_permissions_to_edit_field" => 'Non hai sufficienti permessi per modificare questo campo.'
	);

//login messages
$login_messages_ar = array(
	"username" => "username",
	"password" => "password",
	"please_authenticate" => "Devi identificarti per procedere",
	"login" => "log in",
	"username_password_are_required" => "Username e password sono obbligatori",
	"pwd_gen_link" => "crea password",
	"incorrect_login" => "Username o password errati",
	"pwd_explain_text" =>"Inserisci una parola da utilizzare come password e premi <b>Cripta!</b>.",
	"pwd_explain_text_2" =>"Premi <b>Registra</b> per scriverla nella form sottostante",
	"pwd_suggest_email_sending"=>"Puoi inviarti una mail come promemoria della password",
	"pwd_send_link_text" =>"invia mail!",
	"pwd_encrypt_button_text" => "Cripta!",
	"pwd_register_button_text" => "Registra password ed esci",
	"too_many_failed_login_account_blocked" => "Troppi tentativi non andati a buon fine, l'account è stato bloccato.",
	"warning_you_still_have" => "Attenzione, hai ancora solo ",
	"attempts_before_account_blocking" => " tentativi prima che il tuo account venga bloccato.",
	"verification_code" => "Codice di verifica",
	"verification_code_is_required" => "Il codice di verifica è obbligatorio",
	"incorrect_verification_code" => "Il codice di verifica non è corretto",
	"enable_two-factor_authentication" => "Abilita autenticazione a due fattori",
	"two-factor_authentication" => "Autenticazione a due fattori",
);


// Link "Register" in the login form
$login_messages_ar['register'] = 'Registrati';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Crea il tuo account';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'nome';
$login_messages_ar['last_name'] = 'cognome';
$login_messages_ar['registration_form_checkbox_1'] = 'Accetto <a href="example_terms.html" target="_blank">termini e condizioni</a>';
$login_messages_ar['registration_form_checkbox_2'] = 'Accetto <a href="example_terms.html" target="_blank">termini e condizioni</a>';
$login_messages_ar['registration_form_checkbox_3'] = 'Accetto <a href="example_terms.html" target="_blank">termini e condizioni</a>';

// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Invia e registra un nuovo account';
$login_messages_ar['back_to_login'] = 'Torna al login';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Account creato, riceverai un\'email di conferma contenente un link di attivazione. Clicca il link per attiviare il tuo account.';
$login_messages_ar['email_confirmed_login'] = 'La tua email è stata confermata, ora puoi eseguire il login: ';
$login_messages_ar['account_created_login'] = 'Il tuo account è stato creato, ora puoi eseguire il login: ';
$login_messages_ar['confirmation_link_expired_resent'] = 'Questo link di conferma è scaduto, ti abbiamo inviato un nuovo link.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'Questo link di conferma non è corretto, il tuo account non può essere attivato.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'La tua email non è ancora stata confermata.';
$login_messages_ar['email_already_in_use'] = 'Questa email è già in uso.';
$login_messages_ar['username_already_in_use'] = 'Questo username è già in uso.';

$login_messages_ar['registration_email_subject'] = "Conferma la tua registrazione";
$login_messages_ar['registration_email_content'] = "Ciao,\nqualcuno (probabilmente tu) ha registrato un account per accedere a ".$site_url_to_display.". Per completare la tua registrazione clicca su questo link entro 24h:";


// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Password dimenticata?';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Inserisci la tua email. Riceverai il tuo username<br>e una nuova password temporanea';
// form submit button
$login_messages_ar['submit'] = 'Invia';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'Se questa email corrisponde ad un utente esistente, riceverai un messaggio contenente una password temporanea.';
// email subject
$login_messages_ar['your_temporary_password'] = 'La tua password temporanea';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Qualcuno (probabilmente tu) ha richiesto una nuova password per accedere a ".$site_url_to_display."\n\nSe sei stato tu a richiederla, ecco la tua nuova password temporanea (valida solo per cinque minuti). Considera che l'email non è un canale sicuro per la comunicazione di  password quindi cambia la tua password principale immediatamente dopo avere eseguito il login con quella temporanea e non usare mai, come tua password principale, una della password temporanee che ti abbiamo inviato.";
$login_messages_ar['temporary_password_email_content_part_2'] = "Se non riesci ad accedere al tuo account utilizzando questa nuova password temporanea, significa che qualcuno ha avuto accesso al tuo account prima di te, ti invitiamo a contattatare l'amministratore di sistema.\n\nSe NON hai richiesto la nuova password, significa che qualcuno potrebbe aver tentato di accedere al tuo account: ti invitiamo ad effettuare il login il prima possibile utilizzando la tua VECCHIA password (questo rende invalida la password temporanea) e a contattare l'amministratore di sistema. Se non riesci ad eseguire il login utilizzando la tua vecchia password, significa che qualcuno ha probabilmente già avuto accesso al tuo account, ti invitiamo a contattare l'amministratore di sistema.";

$login_messages_ar['intro_2fa_secret_page'] = '<h2>Importante: questa pagina, per ragioni di sicurezza, verrà visualizzata solo una volta.</h2><p>Si prega di seguire le seguenti istruzioni prima di abbandonare questa pagina, non sarà infatti possibile accedere nuovamente a queste informazioni.</p><p><b>Scarica un\'applicazione di autenticazione:</b> Visita il tuo app store (Google Play/App Store) e scarica un\'app di autenticazione, come  Google Authenticator o Authy.<br><br><b>Scansione il codice QR:</b> Utilizza l\'app per scansionare il codice QR visualizzato qui sotto. Questo collegherà il tuo account all\'app di autenticazione.<br><br><b>Futuri login:</b> La prossima volta che effettuerai il log in, il sistema ti chiederà un codice di verifica generato dalla tua app di autenticzione.</p>';

?>
