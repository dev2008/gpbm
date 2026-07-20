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
	"insert"    => "Insérer le nouvel enregistrement",
	"quick_search"    => "Recherche rapide",
	"search/update/delete" => "Chercher/Mettre à jour/Effacer un enregistrement",
	"insert_short"    => "Insérer",
	"search_short" => "Chercher",
	"advanced_search" => "Advanced Search", // to change
	"insert_anyway"    => "Insérer quand même",
	"search"    => "Chercher un enregistrement",
	"update"    => "Mettre à jour un enregistrement",
	"ext_update"    => "Mettre à jour votre profil",
	"yes"    => "Oui",
	"no"    => "Non",
	"go_back" => "Retour",
	"edit" => "Editer",
	"delete" => "Effacer",
	"details" => "Détails",
	"insert_as_new" => "Insérer comme nouveau",
	"multiple_inserts" => "Insertions multiples",
	"change_table" => "Changer de table",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => "Vous ne pouvez pas modifier cet enregistrement, il est verrouillé par l'utilisateur : ",
	"lost_locked_not_safe_update" => "Vous n'avez pas ou plus la priorité sur cet enregistrement, la modification n'est pas fiable, veuillez recommencer la modification",
	"insert_item" => "Insérer un élément",
	"show_all_records" => "Montrer tous les enregistrements",
	"show_records" => "Afficher",
	"ldap_user_dont_update" => "C'est un utilisateur importé (LDAP, Google, ...) : son groupe est la seule information que vous pouvez modifier si nécessaire.",
	"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
	"remove_search_filter" => "supprimer le filtre de recherche",
	"set_default_search_filter" => "set default search filter", // to change 
	"logout" => "Se déconnecter",
	"top" => "Haut",
	"last_search_results" => "Résultats de la dernière recherche",
	"show_all" => "Tout Montrer",
	"home" => "Accueil",
	"select_operator" => "Sélectionner l'opérateur :",
	"all_conditions_required" => "Toutes conditions requises",
	"any_conditions_required" => "N'importe quelle condition requise",
	"all_contacts" => "Tous les contacts",
	"removed" => "retiré",
	"please" => "SVP",
	"and_check_form" => "et contrôler le formulaire.",
	"and_try_again" => "et essayer à nouveau.",
	"none" => "aucun",
	"are_you_sure" => "Etes-vous sûr ? ",
	"delete_all" => "Effacer tout",
	"really?" => "Vraiment ? ",
	"delete_are_you_sure" => "Effacer l'enregistrement ci-dessous, êtes-vous sûr ? ",
	"required_fields_missed" => "Vous n'avez pas rempli tous les champs requis.",
	"alphabetic_not_valid" => "Vous avez inséré un/plusieurs nombre(s) dans un champ lettre.",
	"numeric_not_valid" => "Vous avez inséré un/plusieurs caractères non numériques dans un champ numérique.",
	"email_not_valid" => "L'e-mail inséré n'est pas valide.",
	"timestamp_not_valid" => "L'horodatage insérée n'est pas valide.",
	"url_not_valid" => "Le(s) URL insérée(s) ne sont pas valides.",
	"phone_not_valid" => "Numéro de téléphone non valide.",
	"date_not_valid" => "Date(s) non valide(s)",
	"entered_value_not_valid" => "The entered value is not valid.", // to change
	"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin", // to change
	"similar_records" => "Les enregistrements ci-dessous semblent être identiques à celui que vous voulez inséré (I'll show max ".$number_duplicated_records." similar items, there could be more).<br>Que voulez-vous faire ? ",  // to change
	"similar_records_short" => "Les enregistrements ci-dessous semblent être identiques à celui que vous voulez inséré (I'll show max ".$number_duplicated_records." similar items, there could be more).",  // to change
	"no_records_found" => "Pas d'enregistrement trouvé.",
	"records_found" => "enregistrements trouvés",
	"number_records" => "Nombre d'enregistrement : ",
	"details_of_record" => "Détails de l'enregistrement",
	"details_of_record_just_inserted" => "Details de l'enregistrement qui vient d'etre inséré",
	"edit_record" => "Editer l'enregistrement",
	"back_to_the_main_table" => "Retour à la table principale",
	"previous" => "Précédent",
	"next" => "Suivant",
	"edit_profile" => "Mettre à jour vos informations de profil",
	"i_think_that" => "Je pense que ",
	"is_similar_to" => " est identique à ",
	"page" => "Page ",
	"of" => " de ",
	"records_per_page" => "enregistrements par page",
	"day" => "Jour",
	"month" => "Mois",
	"year" => "Année",
	"administration" => "Administration",
	"create_update_internal_table" => "Créer ou mettre à jour une table interne",
	"other...." => "autres....",
	"insert_record" => "Insérer un nouvel enregistrement",
	"search_records" => "Chercher les enregistrements",
	"exactly" => "exactement",
	"like" => "comme",
	"required_fields_red" => "Les champs requis sont en rouge.",
	"insert_result" => "Insérer le résultat : ",
	"record_inserted" => "Enregistrement correctement inséré.",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
	"update_result" => "Mettre à jour le résultat : ",
	"record_updated" => "Enregistrement correctement mis à jour.",
	"profile_updated" => "Votre profil a correctement été mis à jour.",
	"delete_result" => "Effacer le résultat : ",
	"record_deleted" => "Enregistrement correctement effacé.",
	"records_deleted" => "Item(s) correctly deleted.", // to change
	"duplication_possible" => "Duplication possible",
	"fields_max_length" => "Vous avez inséré trop de texte dans un ou plusieurs champs.",
	"current_upload" => "Fichier en cours",
	"delete" => "Effacer",
	"total_records" => "Total des enregistrements ",
	"confirm_delete?" => "Confirmer la supression ?",
    "unselect_all" => "Tout désélectionner",
    "select_all" => "Tout sélectionner", // to change
    "only_elements_this_page_selected_other_pages_kept" => "Seuls les éléments de la page actuelle seront sélectionnés. Si vous avez sélectionné des éléments dans d'autres pages, cette sélection sera conservée.", // to change
    "all_elements_will_be_unselected_also_other_pages" => "Tous les éléments seront désélectionnés, y compris les éléments sélectionnés dans d'autres pages.", // to change
    "delete_selected" => "Supprimer la sélection",
	"is_equal" => "est égal à",
	"is_different" => "n'est pas égal à",
	"is_not_null" => "n'est pas NULL",
	"is_not_empty" => "n'est pas vide",
	"contains" => "contient",
	"doesnt_contain" => "ne contient pas",
	"starts_with" => "commence par",
	"ends_with" => "se termine par",
	"greater_than" => ">",
	"less_than" => "<",
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
    "between" => "entre",
    "between_and" => "et", // used for the between search operator: between .... AND .....
	"export_to_csv" => "Exporter en format CSV",
	"export_to_ical" => 'Export iCal', // to change,
	"generate_ical_url" => 'iCal URL', // to change,
	"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
	"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
	"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
	"new_insert_executed" => "Nouvelle insertion effectuée",
	"new_update_executed" => "Nouvelle mise à jour effectuée",
	"null" => "Null",
	"is_null" => "est NULL",
	"is_empty" => "est vide",
	"continue" => "continuer",
	'current_password' => 'mot de passe actuel',
	'new_password' => 'nouveau mot de passe',
	'new_password_repeat' => 'nouveau mot de passe (répéter)',
	'password_changed' => 'le mot de passe a été modifié',
	'change_your_password' => 'modifiez votre mot de passe',
	'your_info' => 'vos infos', // to change
	'sort_by' => 'trier par', // to change
	'sort' => 'trier', // to change
	'pie_chart' => 'Graphique circulaire', // to change
'bar_chart' => 'Graphique en barres', // to change
'line_chart' => 'Graphique linéaire', // to change
'doughnut_chart' => 'Graphique donut', // to change
'show_report' => 'Afficher le graphique', // to change
'show_labels' => 'Afficher les labels', // to change
'show_legend' => 'Afficher la légende', // to change
'group_data_by' => 'Regrouper les données par', // to change
'x_axis' => 'axe X', // to change
'y_axis' => 'axe Y', // to change
'show' => 'afficher', // to change
'percentage' => '%', // to change
'count' => 'compter', // to change
'sum' => 'somme', // to change
'average' => 'moyenne', // to change
'min' => 'min', // to change
'max' => 'max', // to change
'variance' => 'variance', // to change
'standard_deviation' => 'écart type', // to change

'simple_report' => 'rapport simple', // to change
'advanced_sql_report' => 'rapport SQL avancé', // to change
'type_your_custom_sql_query_here' => 'Tapez votre requête SQL personnalisée ici : ', // to change
'current_search_filter_is_not_used' => '(Le filtre de recherche actuel ne sera pas utilisé)', // to change
'advanced_sql_reports_are_disabled' => 'Les rapports SQL avancés sont désactivés', // to change
'advanced_sql_report_instructions_first_part' => 'Vous pouvez écrire une requête SQL select personnalisée, par exemple, supposons que vous ayez une table <b>clients</b> ayant un champ <b>âge_client</b>, vous pouvez montrer la composition par âge de vos clients en utilisant la requête suivante :', // to change
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot; // to change>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // to change // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'N\'oubliez pas que le premier champ que vous sélectionnez sera utilisé pour l\'axe <b>X</b> du graphique, le second champ pour l\'axe <b>Y</b>.<br/><br/>Lisez la documentation pour d\'autres exemples.', // to change
'generate_report' => 'Générer le graphique',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'L\'utilisation du point-virgule ( ;) n\'est pas autorisée pour des raisons de sécurité, vous pouvez omettre le point-virgule final.', // to change
'sql_report_must_start_with_select' => 'Le rapport SQL personnalisé doit commencer par "SELECT".', // to change
'show_embed_code' => 'Afficher le code d\'intégration', // to change
'embed_code_instructions' => 'Vous pouvez copier le code ci-dessous et le coller dans une page personnalisée pour intégrer ce rapport sous forme de graphique ou de grille. En intégrant plusieurs rapports sous forme de graphique ou de grille dans une page, vous pouvez facilement créer un tableau de bord. Veuillez noter que, si le rapport a été généré après une recherche, le filtre de recherche n\'est pas enregistré dans le code d\'intégration. Si vous avez besoin d\'intégrer un rapport basé sur un filtre de recherche stable, la meilleure façon est de créer un VIEW et de générer le rapport à partir de celui-ci. Also consider that pagination is not available in an embedded grid report, only X records will be displayed, where X is your current <i>items per page</i> setting.', // to change
'produce_pdf' => 'Produire le PDF',
'choose_pdf_template' => 'Choisir le template PDF',
'no_pdf_template' => 'Standard Template', // to change
'show_revisions' => 'Afficher les révisions', // to change,
'hide_revisions' => 'Cacher les révisions', // to change,
'record_revisions' => 'Enregistrer les révisions', // to change,
'revisions' => 'Révisions', // to change,
'for_this_table_revisions_not_enabled_no_revisions' => 'Pour cette table, les révisions ne sont pas activées ou vous n\'avez pas encore de révisions.', // to change,
'generate_pivot' => 'Générer le pivot', // to change
'you_might_have_additional_rows_admin_set_to' => 'Vous pouvez avoir des lignes supplémentaires mais l\'administrateur a fixé le nombre maximum de lignes à ', // to change
'add_column' => 'ajouter une colonne', // add column in the pivot report // to change
'remove_this_column' => 'supprimer cette colonne', // remove column in the pivot report // to change
'advanced_sql_report_instructions_pivot_part' => 'Pour la génération de tableaux Pivot, vous pouvez en outre utiliser des alias (pour spécifier les étiquettes) et vous pouvez utiliser plusieurs fonctions d\'agrégation, par exemple : SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand', // to change
'advanced_sql_report_instructions_stat_card_part' => 'For <b>stat cards</b>, instead, there is no aggregation; you must select only one element. For example, this counts the number of customers: SELECT COUNT(*) FROM customers.', // to change
"record_inserted_close_window" => "L'élément a été correctement inséré, vous pouvez <a href='#' onclick='window.close();return false;'>fermer</a> cette fenêtre.", // to change

"import" => "Importer", // to change
'file_type' => 'Type de fichier', // to change
'delimiter' => 'Délimiteur', // to change
'values_enclosed_by' => 'Valeurs éventuellement entourées de', // to change
'load_file' => 'Télécharger le fichier', // to change
'error_no_file_selected' => 'Erreur, vous n\'avez pas sélectionné de fichier à télécharger.', // to change
'values_enclosed_cannot_be_blank' => 'Le paramètre "Valeurs éventuellement entourées par" ne peut pas être vide, vous pouvez laisser la valeur par défaut même si vous n\'utilisez aucun caractère d\'entourage.', // to change
'error_file_type_not_supported_or_different' => 'Erreur, ce type de fichier n\'est pas supporté ou ce n\'est pas celui que vous avez sélectionné dans la page précédente.', // to change
'error_too_much_time_passed' => 'Erreur, trop de temps a passé.', // to change
'processing_row' => 'Traitement de la ligne', // to change
'new_elements_will_be_inserted_to_proceed_click_continue' => 'de nouveaux éléments seront ajoutés. Pour continuer, cliquez sur "Continuer" à la fin de la page.', // this message will be used with a number, e.g. "5 new elements will be added ... ", // to change
'following_as_example_20_rows' => 'Voici seulement les 20 premières lignes de votre fichier.', // to change
'possible_duplication_continue_to_update' => 'Duplication possible, certains éléments ont les mêmes valeurs sur les champs uniques (la duplication peut aussi être dans votre fichier). Voici les éléments dupliqués. Pour le moment, aucun élément n\'a été inséré ou mis à jour. Si vous cliquez sur "Continuer" à la fin de la page, pour ces éléments, je mettrai à jour les enregistrements avec les nouvelles informations fournies dans le fichier. ', // to change
'elements_have_been_inserted_updated' => 'les éléments ont été insérés/mises à jour.', // this message will be used with a number, e.g. "5 elements have been inserted/updated" // to change
'to_verify_elements_click_continue_filter_set' => 'Pour vérifier les éléments insérés/mises à jour, cliquez sur "Continuer". J\'ai mis un filtre de recherche qui vous permet de voir seulement les éléments insérés/mises à jour (vous pourriez voir seulement certains d\'entre eux si l\'administrateur a mis des filtres supplémentaires).', // to change
'no_element_has_been_inserted' => 'Aucun élément n\'a été inséré.', // to change
'error_no_sheet_with_name'=> 'Erreur, pas de feuille avec le nom :', // to change
'elements_results_last_import' => 'Les éléments que vous voyez sont le résultat de la dernière importation (vous pourriez ne voir que certains d\'entre eux si l\'administrateur a défini des filtres supplémentaires). Pour voir tous les éléments, cliquez sur "Supprimer le filtre de recherche".', // to change
'csv_file_must_be_utf8_encoded' => 'Le fichier CSV doit être codé en UTF-8.', // to change
'hide_show_quick_filters' => 'Cacher/afficher les filtres rapides', // to change,
'show_search_url' => 'Afficher l\'URL de recherche', // to change,
'search_url_instructions' => 'Cette URL exécute la même recherche que celle que vous avez effectuée, en ajoutant également les critères de tri que vous avez appliqués (le cas échéant).', // to change,
"double_click_to_edit" => 'Double click to edit', // to change
'it_seems_you_uploaded_other_files_cancelled' => ' Il semble que vous ayez téléchargé des fichiers dans un autre formulaire mais que vous n\'ayez finalement pas terminé le processus de sauvegarde/insertion. Ces téléchargements ont été annulés.', // to change,
'number_uploaded_files' => 'Nombre de fichiers téléchargés: ', // to change,
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
$normal_messages_ar['months_short'][2] = 'Fév'; // to change,
$normal_messages_ar['months_short'][3] = 'Mar';
$normal_messages_ar['months_short'][4] = 'Avr'; // to change,
$normal_messages_ar['months_short'][5] = 'Mai'; // to change,
$normal_messages_ar['months_short'][6] = 'Jun';
$normal_messages_ar['months_short'][7] = 'Jul';
$normal_messages_ar['months_short'][8] = 'Aou'; // to change,
$normal_messages_ar['months_short'][9] = 'Sep';
$normal_messages_ar['months_short'][10] = 'Oct';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Dec';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'lun'; // to change,
$normal_messages_ar['days_short'][2] = 'mar'; // to change,
$normal_messages_ar['days_short'][3] = 'mer'; // to change,
$normal_messages_ar['days_short'][4] = 'jeu'; // to change,
$normal_messages_ar['days_short'][5] = 'ven'; // to change,
$normal_messages_ar['days_short'][6] = 'sam'; // to change,
$normal_messages_ar['days_short'][7] = 'dim'; // to change,
// error messages
$error_messages_ar = array (
	"int_db_empty" => "Erreur, la base interne est vide.",
	"get" => "Erreur dans la récupération des variables.",


	"no_functions" => "Erreur, pas de fonction sélectionnée.<br>Retournez à l'accueil.",
	"no_unique_key" => "Erreur, vous n'avez pas de clé primaire dans votre base.",
	"upload_error" => "Une erreur est intervenue durant le transfert.",
	"no_authorization_update" => "Vous n'avez pas l'autorisation de modifier cet enregistrement.",
	"no_authorization_delete" => "Vous n'avez pas l'autorisation de detruire cet enregistrement.",
	"no_authorization_view" => "Vous n'avez pas l'autorisation de voir cet enregistrement.",
	"deleted_only_authorizated_records" => "Seuls les enregistrements pour lesquels vous etes autorise ont ete detruits.",
	"record_from_which_you_come_no_longer_exists" => "L'enregistrement d'où vous venez n'existe plus.",
	"date_not_representable" => "Une date dans cet enregistrement ne correspond pas au format 'Jour / Mois / Anne' de DaDaBIK. Sa valeur est :  ",
	"this_record_is_the_last_one" => "Cet enregistrement est le dernier.",
	"this_record_is_the_first_one" => "Cet enregistrement est le premier.",
	"current_password_wrong" => 'le mot de passe actuel est faux',
	"passwords_are_different" => 'les deux mots de passe sont différents',
	"new_password_must_be_different_old" => 'the new password must be different from the current one', // to change
	"new_password_is_empty" => ' nouveau mot de passe est vide',
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.' // to change
	);

//login messages
$login_messages_ar = array(
	"username" => "nom d'utilisateur",
	"password" => "mot de passe",
	"please_authenticate" => "Vous devez vous identifier pour continuer",
	"login" => "connection",
	"username_password_are_required" => "nom d'utilisateur et mot de passe sont obligatoires",
	"pwd_gen_link" => "créer un mot de passe",
	"incorrect_login" => "Nom d'utilisateur ou mot de passe incorrect",
	"pwd_explain_text" =>"Tapez un mot à utiliser comme mot de passe et pressez <b>Cryptage !</b>.",
	"pwd_explain_text_2" =>"Pressez <b>Enregistrer le mot de passe et quitter</b> pour l'écrire sur le formulaire ci-dessous",
	"pwd_suggest_email_sending"=>"Vous pouvez vous envoyer un email pour mémoriser le mot de passe",
	"pwd_send_link_text" =>"Envoi courrier ! ",
	"pwd_encrypt_button_text" => "Cryptage ! ",
	"pwd_register_button_text" => "Enregistrer le mot de passe et quitter" ,
	"too_many_failed_login_account_blocked" => "Trop de tentatives, votre compte est bloqué.",
	"warning_you_still_have" => "Attention, il ne vous reste plus que  ",
	"attempts_before_account_blocking" => " tentative avant le blocage de votre compte.",
	"verification_code" => "Verification code", // to change
	"verification_code_is_required" => "Verification code is required", // to change
	"incorrect_verification_code" => "The verification code is not correct", // to change
	"enable_two-factor_authentication" => "Enable Two-Factor Authentication", // to change
	"two-factor_authentication" => "Two-Factor Authentication", // to change
);

// to change, all the messages below

// Link "Register" in the login form
$login_messages_ar['register'] = 'S\'enregistrer';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Créez votre compte';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'Prénom';
$login_messages_ar['last_name'] = 'Nom';
$login_messages_ar['registration_form_checkbox_1'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_2'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_3'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change

// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Soumettre et enregistrer un nouveau compte';
$login_messages_ar['back_to_login'] = 'Retour au login';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Compte créé, vous recevrez un e-mail de confirmation contenant un lien d\'activation. Veuillez cliquer sur le lien pour activer votre compte.';
$login_messages_ar['email_confirmed_login'] = 'Votre compte a été activé, vous pouvez maintenant vous connecter : ';
$login_messages_ar['account_created_login'] = 'Votre compte a été créé, vous pouvez maintenant vous connecter : ';
$login_messages_ar['confirmation_link_expired_resent'] = 'Le lien de confirmation a expiré, un nouveau lien a été envoyé à votre adresse e-mail.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'Ce lien de confirmation n\'est pas correct, votre compte ne peut être activé.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'Votre courriel n\'a pas encore été confirmé.';
$login_messages_ar['email_already_in_use'] = 'This email is already in use.'; // to change
$login_messages_ar['username_already_in_use'] = 'This username is already in use.'; // to change
$login_messages_ar['registration_email_subject'] = "Please confirm your registration"; // to change
$login_messages_ar['registration_email_content'] = "Hello,\nsomeone (hopefully you) has registered an account at ".$site_url_to_display.". To complete your registration click on this link within 24h:"; // to change

// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Mot de passe oublié';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Entrez votre email, vous recevrez votre nom d\'utilisateur et un nouveau mot de passe temporaire.';
// form submit button
$login_messages_ar['submit'] = 'Soumettre';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'Si cette adresse électronique correspond à un utilisateur existant, vous recevrez un message avec un mot de passe temporaire.';
// email subject
$login_messages_ar['your_temporary_password'] = 'Votre mot de passe temporaire';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Quelqu'un (vous, j'espère) a demandé un nouveau mot de passe pour accéder à ".$site_url_to_display."\n\nSi VOUS avez demandé le nouveau mot de passe, tout va bien, voici votre nouveau mot de passe temporaire (valable pendant cinq minutes seulement). Veuillez noter que l'email n'est pas un canal de communication sécurisé pour les mots de passe. Changez donc immédiatement votre mot de passe principal après vous être connecté et n'utilisez jamais - comme mot de passe principal - les mots de passe temporaires que nous vous avons envoyés.";
$login_messages_ar['temporary_password_email_content_part_2'] = "Si vous ne pouvez pas accéder à votre compte à l'aide du mot de passe temporaire que nous vous avons envoyé, cela signifie que quelqu'un d'autre a déjà accédé à votre compte, veuillez contacter votre administrateur système.\n\nSi vous n'avez pas demandé le nouveau mot de passe, cela signifie que quelqu'un d'autre pourrait essayer d'accéder à votre compte : connectez-vous dès que possible en utilisant votre ANCIEN mot de passe (ce qui rend le mot de passe temporaire invalide) et contactez votre administrateur système. Si vous ne pouvez pas vous connecter en utilisant votre ancien mot de passe, cela signifie que quelqu'un d'autre a probablement déjà accédé à votre compte en utilisant le nouveau mot de passe temporaire, veuillez contacter votre administrateur système.";

$login_messages_ar['intro_2fa_secret_page'] = '<h2>Important: This page is displayed only once for security reasons.</h2><p>Please complete the setup instructions before leaving the page, as you will not be able to access this information again.</p><p><b>Download an Authentication App:</b> Visit your app store (Google Play/App Store) and download an authentication app, such as Google Authenticator or Authy.<br><br><b>Scan the QR Code:</b> Use the app to scan the QR code displayed below. This will link your account to the authentication app.<br><br><b>Future Logins:</b> The next time you log in, you will be prompted to enter a verification code generated by your authentication app.</p>'; // to change
?>
