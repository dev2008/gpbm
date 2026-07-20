<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<?php
// submit buttons
$submit_buttons_ar = array (
	"insert"    => 'Új bejegyzés hozzáadása',
	"quick_search"    => 'Gyorskeresés',
	"search/update/delete" => 'Bejegyzés(ek) keresése/frissítése/törlése',
	"insert_short"    => 'Hozzáad',
	"search_short" => 'Keres',
	"advanced_search" => "Részletes Keresés",
	"insert_anyway"    => 'Mégiscsak illessze be',
	"search"    => 'Bejegyzés(ek) keresése',
	"update"    => 'Mentés',
	"ext_update"    => 'Profil frissítése',
	"yes"    => 'Igen',
	"no"    => 'Nem',
	"go_back" => 'Vissza',
	"edit" => 'Szerkesztés',
	"delete" => 'Törlés',
	"details" => 'Részletek',
	"insert_as_new" => 'Beillesztés újként',
	"multiple_inserts" => 'Többszörös beillesztés',
	"change_table" => 'Tábla hozzáadása',
	"search_includes_following_fields" => 'A keresés a következő mezőket érinti:',
);

// normal messages
$normal_messages_ar = array (
	"cant_edit_record_locked_by" => 'A bejegyzés nem szerkeszthető, mert éppen a következő felhasználó használja: ',
	"lost_locked_not_safe_update" => 'A bejegyzés zárolása érvényét vesztette, ezért a frissítése nem biztonságos. Kérem kezdje újra a szerkesztést',
	"insert_item" => 'Új bejegyzés hozzáasáaa',
	"show_all_records" => 'Összes bejegyzés megjelenítése',
	"show_records" => 'Bejegyzések megjelenítése',
	"ldap_user_dont_update" => 'Ez egy importált felhasználó (LDAP, Google, ...): csak a csoportja az egyedüli dolog, amit meg kell változtatni, ha szükséges.',
	"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
	"remove_search_filter" => 'Keresési szűrő kikapcsolása',
	"set_default_search_filter" => "set default search filter", // to change 
	"logout" => 'Kijelentkezés',
	"top" => 'Tetejére',
	"last_search_results" => 'Legutóbbi keresés eredménye',
	"show_all" => 'Összes',
	"home" => 'Kezdőlap',
	"select_operator" => 'Válasszon elválasztót:',
	"all_conditions_required" => 'Minden feltételnek feleljen meg',
	"any_conditions_required" => 'Bármely feltételnek megfelehet',
	"all_contacts" => 'Minden kapcsolat',
	"removed" => 'eltávolításra került',
	"please" => 'Kérem',
	"and_check_form" => 'és nézze át a táblát.',
	"and_try_again" => 'és próbálja újra.',
	"none" => 'egy sem',
	"are_you_sure" => 'Biztos benne?',
	"delete_all" => 'összes törlése',
	"really?" => 'Valóban?',
	"delete_are_you_sure" => 'Kitörli az összes alábbi bejegyzést? Biztos ebben?',
	"required_fields_missed" => 'Nem töltött ki egy/néhány kötelező mezőt.',
	"alphabetic_not_valid" => 'Számokat írt egy mezőbe, amelybe betűk valók.',
	"numeric_not_valid" => 'Helytelen betűket írt egy mezőbe, melybe számok valók.',
	"email_not_valid" => 'Az email cím(ek), amely(ek)et megadott helytelen(ek).',
	"timestamp_not_valid" => 'A megadott dátumok helytelenek.',
	"url_not_valid" => 'Az URL(ek), amely(ek)et megadott, helytelen(ek).',
	"phone_not_valid" => 'A telefonszám(ok), amely(ek)et megadott, helytelen(ek).<br>Használja az alábbi formátumot: \"+(ország kód)(területi kód)(telefonszám)\" pl. +36301234567, 0036301234567, 301234567.',
	"date_not_valid" => 'Egy, vagy több megadott dátum helytelen formátumú.',
	"entered_value_not_valid" => "A megadott érték érvénytelen.",
	"id_group_admin_by_non_admin_not_valid" => "Nem hozhat létre és nem szerkeszthet adminisztrátor felhasználót, ha nem adminisztrátor",
	"similar_records" => "Az alábbi bejegyzések elég hasonlónak tűnnek ahhoz, amit be akar illeszteni (maximum ".$number_duplicated_records." bejegyzés szerepel a listán, de lehet ennél több is).<br>Mi legyen?",
	"similar_records_short" => "Az alábbi bejegyzések elég hasonlónak tűnnek ahhoz, amit be akar illeszteni (maximum ".$number_duplicated_records." bejegyzés szerepel a listán, de lehet ennél több is).",
	"no_records_found" => 'Nincs megfelelő bejegyzés.',
	"records_found" => 'megfelelő bejegyzés található',
	"number_records" => 'Bejegyzések száma: ',
	"details_of_record" => 'Bejegyzés részletei',
	"details_of_record_just_inserted" => 'Az imént hozzáadott bejegyzés részletei',
	"edit_record" => 'Bejegyzés szerkesztése',
	"back_to_the_main_table" => 'Vissza a fő táblázathoz',
	"previous" => 'Előző',
	"next" => 'Következő',
	"edit_profile" => 'Profil adatok szerkesztése',
	"i_think_that" => 'Úgy tűnik, hogy a(z) ',
	"is_similar_to" => ' érték hasonlít ehhez: ',
	"page" => 'Oldalszám: ',
	"of" => ' összes oldal: ',
	"records_per_page" => 'Oldalankénti bejegyzés',
	"day" => 'Nap',
	"month" => 'Hónap',
	"year" => 'Év',
	"administration" => 'Adminisztráció',
	"create_update_internal_table" => 'Belső tábla létrehozása/frissítése.',
	"other...." => 'más....',
	"insert_record" => 'Új bejegyzés hozzáadása',
	"search_records" => 'Bejegyzés keresése',
	"exactly" => 'ugyanolyan',
	"like" => 'hasonló',
	"required_fields_red" => 'A kötelező mezők pirosak.',
	"insert_result" => 'Hozzáadás eredménye:',
	"record_inserted" => 'Bejegyzés sikeresen hozzáadva.',
	"a_corresponding_user_account" => 'A kérdéses felhasználót',
	"has_been_created_with_random_password" => 'létrehozta a rendszer egy véletlen jelszóval',
	"update_result" => 'Frissítés eredménye:',
	"record_updated" => 'Bejegyzés sikeresen frissítve.',
	"profile_updated" => 'Profil sikeresen frissítve.',
	"delete_result" => 'Törlés eredménye:',
	"record_deleted" => 'Bejegyzés sikeresen törölve.',
	"records_deleted" => "A bejegyzés(ek) sikeresen eltávolításra került(ek).",
	"duplication_possible" => 'Ismételt előfordulás megengedett',
	"fields_max_length" => 'Túl sok adatot vitt be valamelyik beviteli mezőbe.',
	"current_upload" => 'Aktuális feltöltendő fájl',
	"delete" => 'törlés',
	"total_records" => 'Összes bejegyzés',
	"confirm_delete?" => 'Megerősíti a törlést?',
    "unselect_all" => "Kijelölések visszavonása",
    "select_all" => "Mindent kijelöl",
    "only_elements_this_page_selected_other_pages_kept" => "Csak az aktuális oldal bejegyzései kerülnek kiválasztásra. Ha más oldalon is választott ki bejegyzés(eke)t, az(oka)t nem fogja érinteni.",
    "all_elements_will_be_unselected_also_other_pages" => "Lekerül a kiválasztás minden bejegyzésről, a többi oldalon is.",
    "delete_selected" => "Kijelölt(ek) törlése",
	"is_equal" => 'azonos',
	"is_different" => 'különbözik',
	"is_not_null" => 'nem nulla',
	"is_not_empty" => 'nem üres',
	"contains" => 'tartalmazza',
	"doesnt_contain" => 'nem tartalmazza',
	"starts_with" => 'kezdődik',
	"ends_with" => 'végződik',
	"greater_than" => 'nagyobb',
	"less_than" => 'kisebb',
    "greater_equal_than" => ">=",
    "less_equal_than" => "<=",
	"between" => 'között',
	"between_and" => 'ÉS',
	"export_to_csv" => 'CSV exportálás',
	"export_to_ical" => 'iCal exportálás',
	"generate_ical_url" => 'iCAL URL létrehozása',
	"generate_ical_url_page_1" => 'Az alábbi URL-lel felíratkozhat, hozzáadhatja a naptár alkalmazásához (mint a Google Naptár vagy Apple Naptár):',
	"generate_ical_url_page_2" => 'Fontos megjegyezni, hogy tartalmazza a felhasználó nevét és a tokent, ami nem jár le. A token hatóköre a ', 
	"generate_ical_url_page_3" => "és a társuló táblázatok (ha van ilyen) olvasására korlátozódik.",
	"new_insert_executed" => 'Beillesztés végrehajtva',
	"new_update_executed" => 'Frissítés végrehajtva',
	"null" => 'Null',
	"is_null" => 'nulla',
	"is_empty" => 'üres',
	"continue" => 'Folytatás',
	"current_password" => 'jelenlegi jelszó',
	"new_password" => 'új jelszó',
	"new_password_repeat" => 'új jelszó (ismét)',
	"password_changed" => 'a jelszó megváltozott',
	"change_your_password" => 'változtassa meg jelszavát',
	"your_info" => 'információja',
	"sort_by" => 'alábbiak szerint rendezze',
	"sort" => 'rendezés',
	"pie_chart" => 'Torta diagram',
	"bar_chart" => 'Oszlop diagram',
	"line_chart" => 'Vonal diagram',
	"doughnut_chart" => 'Fánk diagram',
	"show_report" => 'Mutassa a jelentést',
	"show_labels" => 'Mutassa a jelzéseket',
	"show_legend" => 'Mutassa a felíratokat',
	"group_data_by" => 'Következő képpen csoportosítsa az adatokat',
	"x_axis" => 'X tengely',
	"y_axis" => 'Y tengely',
	"show" => 'mutassa',
	"percentage" => '%',
	"count" => 'számosság',
	"sum" => 'összeg',
	"average" => 'átlag',
	"min" => 'minimum',
	"max" => 'maximum',
	"variance" => 'variancia',
	"standard_deviation" => 'standard deviáció',

	"simple_report" => 'egyszerű jelentés',
	"advanced_sql_report" => 'összetett SQL jelentés',
	"type_your_custom_sql_query_here" => 'Adja meg a személyre szabott SQL lekérdezését: ',
	"current_search_filter_is_not_used" => '(Az aktuális keresési szűrő nem kerül alkalmazásra)',
	"advanced_sql_reports_are_disabled" => 'Az összetett SQL jelentés nem engedélyezett',
	"advanced_sql_report_instructions_first_part" => 'Írhat egy személyre szabott SQL lekérdezést, pl. feltételezzük, hogy van egy <b>vasarlok</b> tábla <b>eletkor</b> mezővel, megjeleníthetu a vásárlók korösszetételét e következő lekérdeéssel:',
	"advanced_sql_report_instructions_query_part" => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT eletkor, count(*) FROM vasarolok GROUP BY eletkor</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
	"advanced_sql_report_instructions_second_part" => 'Tartsa észben, hogy az első mező, amit kiválaszt a grafikon <b>X tengelyét</b> fogja képezni, a második mező pedig az <b>Y tengely</b> lesz.<br/><br/>A dokumentációban példákat talál.',
	"advanced_sql_report_instructions_second_part" => 'Ne felejtse, hogy az első választott mező a grafikon <b>X tengelye</b> lesz, a második mező pedig az <b>Y tengely</b>.<br/><br/>A dokumentációban további példákat talál.',
	"generate_report" => 'Jelentés létrehozása',
	"use_semicolon_forbidden_omit_trailing_semicolmn" => 'A pontosvessző (;) nem használható biztonsági okokból, hagyja ki a záró pontosvesszőt.',
	"sql_report_must_start_with_select" => 'A személyre szabott SQL lekérdezésnek "SELECT "-tel kell kezdődnie',
	"show_embed_code" => 'Mutassa a beágyazott kódot',
	"embed_code_instructions" => 'Bemásolhatja az alábbi kódot és beillesztheti egy személyre szabott oldalba, hogy beágyazza ezt a grafikont vagy táblázatot; több grafikon/táblázat beágyazásával könnyedén készíthet információs oldalt. Gondoljon rá, hogy ha a jelentés egy keresés után jött létre, a kereésis szűrő nem kerül bele a beágyazott kódba. Hogyha egy jelentést meghatározott keresési feltételekkel szeretné beilleszteni, akkor a legjobb módszer, ha előbb létrehoz egy NÉZET-et és a jelentést onann kiindulva készíti el. Vegye figyelembe, hogy az oldalakra tördelés nem érhető el beágyazott megjelenítés esetén, ezért csak annyi bejegyzés látszódik, amennyi a jelenlegi oldalanként megjelenített darabszám a beállítás szerint.',
	"produce_pdf" => 'PDF létrehozása',
	"choose_pdf_template" => 'Válasszon PDF sablon',
	"no_pdf_template" => 'Standard Template', // to change
    'show_revisions' => 'Verziók mutatása',
    'hide_revisions' => 'Verziók elrejtés',
    'record_revisions' => 'Verzió mentése',
    'revisions' => 'Verziók',
    'for_this_table_revisions_not_enabled_no_revisions' => 'Ehhez a táblához nincsenek engedélyezve a verziókövetés, vagy még nem mentett el egy verziót sem.',
    'generate_pivot' => 'Pivot generálása',
'you_might_have_additional_rows_admin_set_to' => 'Lehetnek még további sorok is, de az admin a sorok maximális számát limitálta: ',
'add_column' => 'oszlop hozzáadása',
'remove_this_column' => 'oszlop eltávolítása',
'advanced_sql_report_instructions_pivot_part' => 'A Pivot Tábla létrehozáshoz továbbá használható álnév/alias is (címke/label magadásához) és használhat két összegyűjtott/aggregate függvényt, például: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand',
'advanced_sql_report_instructions_stat_card_part' => '<b>Adat kártyák</b> esetén ellenben nincs összesítés; ki kell választani
egy konkrét bejegyzést. Például ez összeszámolja a vásárlók számát: SELECT COUNT(*) FROM customers.',
"record_inserted_close_window" => "A bejegyzés rendben hozzáadásra került, most már <a href='#' onclick='window.close();return false;'>becsukhatja</a> ezt az ablakot.",

"import" => "Importálás",
'file_type' => 'Fájl típus',
'delimiter' => 'Elválasztó',
'values_enclosed_by' => 'Az értékeket határolhatja',
'load_file' => 'Fájl feltöltés',
'error_no_file_selected' => 'Hiba: nem választott ki fájlt a feltöltéshez.',
'values_enclosed_cannot_be_blank' => '"Az értékekekt határolhatja" paraméter nem lehet üres, úgyhgyhatja az alapértelmezettet is, még akkor is, ha nem használ semmilyen határoló karaktert.',
'error_file_type_not_supported_or_different' => 'Hiba: ez a fájl típus nem támogatott, vagy nem ezt válaszotta ki az előző képernyőn',
'error_too_much_time_passed' => 'Hiba: túl sok idő telt el.',
'processing_row' => 'Sor feldolgozása',
'new_elements_will_be_inserted_to_proceed_click_continue' => 'új bejegyzés kerül hozzáadásra. Tovább lépéshez kattintson a "Folytatás"-ra az oldal végén', // this message will be used with a number, e.g. "5 new elements will be added ... ",
'following_as_example_20_rows' => 'Alább a fájl legfeljebb első 20 sora látható.',
'possible_duplication_continue_to_update' => 'Lehetséges duplikáció, néhány bejegyzésnek ugyanolyan egyedi értéke van (a duplikáció lehet a fájlban is). Íme a duplikált bejegyzések. Jelenleg egy bejegyzés sem került hozzáadásra/frissítésre. Amennyiben a "Folytatás"-ra kattint az oldal végén, akkor frissítésre kerülnek majd a bejegyzések a fájlban szereplő információkkal. ',
'elements_have_been_inserted_updated' => 'bejegyzés került hozzáadásra/frissítésre.', // this message will be used with a number, e.g. "5 elements have been inserted/updated"
'to_verify_elements_click_continue_filter_set' => 'A hozzáadott/frissített bejegyzések ellenőrzéséhez kattintson a "Folytatás"-ra. Beállításra került egy olyan keresési szűrő, hogy csak az imént hozzáadott bejegzések látszódjanak (lehet hogy csak néhány fog látszani, ha az adminisztrátor további keresési szűrőket adott meg).',
'no_element_has_been_inserted' => 'Nem került egy bejegyzés sem hozzáadásra.',
'error_no_sheet_with_name'=> 'Hiba: nincs ilyen nevű táblázat lap:',
'elements_results_last_import' => 'A látott bejegyzések a legutóbbi importálás eredményei (esetleg csak egy részüket láthatja, ha az adminisztrátor további szűrőket állított be). Ahhoz hogy minden bejegyzés látszódjon, kattintson a "Keresési szűrő eltávolítása"-ra',
'csv_file_must_be_utf8_encoded' => 'CSV fájl UTF-8 kódolású kell legyen.',
'hide_show_quick_filters' => 'Gyorsszűrők Mutatása/Elrejtése',
'show_search_url' => 'Mutassa a keresés URL-jét',
'search_url_instructions' => 'Ez az URL végrehatja ugyanazt a keresést, hozzáadva a rendezési kritériumo(ka)t (ha van olyan).',
"double_click_to_edit" => 'Kattintson duplán szerkesztéshez',
'it_seems_you_uploaded_other_files_cancelled' => ' Úgy tűnik, hogy töltött fel fájl)oka)t egy másik oldalon, de nem fejezte be a mentést/beillesztést. Ezen függőben lévő feltöltés(ek) megszakadt(ak).',
'number_uploaded_files' => 'Feltöltött fájlok száma: ',
'file_uploaded_file_will_replace' => 'Fájl feltötlve! A fájl mentéskor lecseréli a korábbit (ha már volt).',
'generic_upload_error' => 'Feltöltési hiba! ',
'collapse_sidebar' => 'Oldalmenü bezárása',
'ask_anything_about' => 'Bármiről kérdezhet:',
'ask_ai' => 'Kérdezzen az AI-tól',
'ask_questions_data_read_only' => 'Kérdezzen az adatairól (csak olvasható).',
'ai_generated_answer' => 'AI Válasza',
'dadabrain_created_separate_view_results' => 'A DaDaBrain létrehozott egy külön nézetet az eredményekkel.',
'you_can_open_it' => 'Amit megnyithat',	
'here' => 'itt',
'please_verify_ai_generated_results_may_be_incorrect' => 'Ellenőrizze az AI által biztosított eredményeket, mielőtt döntést hoz. Az
eredmények helytelenek és hiányosak lehetnek.',
'to_answer_I_used_this_query' => 'A megválaszolás során használt SQL lekérdezés',
'list_below_has_not_changed_current_filters_still_apply' => 'Az alábbi lista nem változott. A jelenlegi szűrő(k) (amennyiben be
volt(ak) állítva) még mindig aktívak.',
'questions' => 'Kérdések',
'discard_question' => 'Kérdés eltávolítása',
'question_discarded' => 'A kérdés eltávolításra került',
'dadabrain_disabled_check_enable_dadabrain' => '<b>A DaDaBrain ki van kapcsolva</b>. Kérem ellenőrízze az $enable_dadabrain bállítási lehetőséget és fontolja meg az ezzel járó biztonsági szempontokat.',
'use_show_dadabrain_form_to_hide_ai_form' => 'Használja a $show_dadabrain_form"-ot a DaDaBrain AI elrejtéséhez.',
'open_the_view' => 'Nézet megnyitása',	

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
	"int_db_empty" => 'Hiba: a belső adatbázis üres.',
	"get" => 'Hiba: a változók lekérdezése közben.',
	"no_functions" => 'Hiba: válasszon a leehtőségek közül<br>Kérem menjen vissza a kiiduló lapra.',
	"no_unique_key" => 'Hiba: nincsen elsődleges azonosító kulcs az adatbázisban.',
	"upload_error" => 'Hiba jelentkezett a fájl feltöltése során.',
	"no_authorization_update" => 'Nincs meg a jogosultsága a bejegyzés frissítéséhez.',
	"no_authorization_delete" => 'Nincs meg a jogosultsága a bejegyzés törléséhez.',
	"no_authorization_view" => 'Nincs joga, hogy megnézze a bejegyzést.',
	"deleted_only_authorizated_records" => 'Csak azon bejegyzések törlődtek, melyekhez jogosultsággal rendelkezett.',
	"record_from_which_you_come_no_longer_exists" => 'A bejegyzés már nem létezik.',
	"date_not_representable" => 'A bejegyzés egy dátum mezője nem jeleníthető meg a DaDaBIK-kal. A mező értéke: ',
	"this_record_is_the_last_one" => 'Ez az utolsó bejegyzés.',
	"this_record_is_the_first_one" => 'Ez az első bejegyzés.',
	"current_password_wrong" => 'a jelenlegi jelszó hibás',
	"passwords_are_different" => 'a két jelszó eltérő',
	"new_password_must_be_different_old" => 'Az új jelszónak különböznie kell a korábbitól',
	"new_password_is_empty" => 'az új jelszó üres',
	"you_cant_live_edit_click_edit" => 'Nem tudja helyben szerkeszteni a mezőt, kérem kattintson a szerkesztés ikonra bal oldalon és szerkessze a teljes bejegyzést.',
	"you_dont_have_enough_permissions_to_edit_field" => 'Nincs elég jogosultsága a mező szerkesztéséhez.'
	);


//login messages
$login_messages_ar = array(
	"username" => 'Felhasználó',
	"password" => 'Jelszó',
	"please_authenticate" => 'Folytatáshoz be kell jelentkeznie',
	"login" => 'bejelentkezés',
	"username_password_are_required" => 'Username and password are required',
	"pwd_gen_link" => 'jelszó létrehozása',
	"incorrect_login" => 'Helytelen felhasználó, vagy jelszó',
	"pwd_explain_text" => 'Írja be a kívánt jelszót és kattintson a <b>Titkosítás!</b> gombra.',
	"pwd_explain_text_2" => 'Nyomja meg a <b>Regisztrálás</b> gombot, hogy átmásolódjon a megfelelő mezőbe.',
	"pwd_suggest_email_sending" => 'Elküldheti magának a jelszót egy emailben, ha kívánja.',
	"pwd_send_link_text" => 'email küldése!',
	"pwd_encrypt_button_text" => 'Titkosítás!',
	"pwd_register_button_text" => 'A jelszó regisztrálása és kilépés',
	"too_many_failed_login_account_blocked" => 'Túl sok sikertelen bejelentkezési kísérlet: a hozzáférés blokkolásra került.',
	"warning_you_still_have" => 'Figyelem, már csak ',
	"attempts_before_account_blocking" => ' sikertelen belépési kísérlet van hátra a hozzáférés blokkolásáig.',
	"verification_code" => "Hitelesítő kód",
	"verification_code_is_required" => "Hitelesítő kód szükséges",
	"incorrect_verification_code" => "Helytelen hitelesítő kód",
	"enable_two-factor_authentication" => "Két faktoros hitelesítés aktiválása",
	"two-factor_authentication" => "Két faktoros hitelesítés",
);

// Link "Register" in the login form
$login_messages_ar['register'] = 'Regisztrálás';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Hozza létre hozzáférését';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'keresztnév';
$login_messages_ar['last_name'] = 'vezetéknév';
$login_messages_ar['registration_form_checkbox_1'] = '<a href="example_terms.html" target="_blank">Felhasználási feltételek</a> elfogadása';
$login_messages_ar['registration_form_checkbox_2'] = '<a href="example_terms.html" target="_blank">Felhasználási feltételek</a> elfogadása';
$login_messages_ar['registration_form_checkbox_3'] = '<a href="example_terms.html" target="_blank">Felhasználási feltételek</a> elfogadása';

// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Beküldés új hozzáférés regisztrálásához';
$login_messages_ar['back_to_login'] = 'Vissza a bejelentkező oldalra';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Hozzáférés létrehozva, kapni fog egy megerősítő emailt egy aktiváló linkkel. Kérem Please click on the link to activate your account.';
$login_messages_ar['email_confirmed_login'] = 'A hozzáférése aktiválva, most már bejelentkezhet: ';
$login_messages_ar['account_created_login'] = 'A hozzáférése létrehozva, most már belejentkezhet: ';
$login_messages_ar['confirmation_link_expired_resent'] = 'A megerősítő link lejárt, egy új linket fog kapni az email címére.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'A megerősítő link nem érvényes, a hozzáférése nincs aktiválva.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'Az email cím még nincs megerősítve.';
$login_messages_ar['email_already_in_use'] = 'Az email cím már használatban van.';
$login_messages_ar['username_already_in_use'] = 'A felhasználó név már használatban van.';
$login_messages_ar['registration_email_subject'] = "Erősítse meg a regisztrációját";
$login_messages_ar['registration_email_content'] = "Üdvözlöm, valaki (remélhetőleg ön) regisztrációt hozott létre a ".$site_url_to_display."
oldalra. A regisztráció megerősítéséhez kattintson az alábbi linkre 24 órán belül:";




// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Elfelejtett jelszó';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Adja meg az email címét, és egy üzenetet fog kapni a felhasználó nevével és egy ideiglenes jelszóval';
// form submit button
$login_messages_ar['submit'] = 'Beküldés';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'Ha ez az email cím egy létező felhasználóhoz tartozik, akkor kapni fog egy üzenetet egy ideiglenes jelszóval.';
// email subject
$login_messages_ar['your_temporary_password'] = 'Ideiglenes jelszó';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Valaki (remélhetőleg ön) új jelszót kért a ".$site_url_to_display." oldalhoz.\n\nHa ÖN igényelte az új jelszót, akkor minden rendben, itt van az ideiglenes jelszava (csak öt percig érvényes). Kérem vegye figyelembe, hogy az email nem egy biztonságos kommunikációs csatorna jelszavakhoz, ezért belépés után azonnal változtassa meg és a továbbiakban az ideiglenes jelszót ne használja.";
$login_messages_ar['temporary_password_email_content_part_2'] = "Ha nem tud belépni az ideiglenes jelszavával, elképzelhető hogy valaki más már belépett. Vegye fel a kapcsolatot a rendszergazdával.\n\nHa ön nem igényelt új jelszót, lehet hogy valaki más próbálja megszerezni a hozzáférését: minél hamarabb próbáljon meg belépni a RÉGI jelszavával (ez érvényteleníti az ideiglenes jelszót) és vegye fel a kapcsolatot a rendszergazdával. Ha nem tud belépni a régi jelszavával, lehet hogy valaki más már átvette az irányítást a hozzáférése felett az ideiglenes jelszóval. Vegye fel a kapcsolatot a rendszergazdával";



$login_messages_ar['intro_2fa_secret_page'] = '<h2>Fontos: ez az oldal egyszer jelenik meg biztonsági okokból.</h2><p>Kövesse az utasításokat mielőtt elhagyná az oldalt, mert nem fog tudni
újból hozzáférni az itt megjelenő információkhoz.</p><p><b>Töltse le a Hitelesítő Alkalmazást:</b> Látogasson el az alkalmazás boltba (Google Play/App Stora) és töltsön le egy hitelesítő alkalmazást, mint a Google Authenticator vagy Authy.<br><br><b>Olvassa be a QR Kódot: </b> Használja az alkalmazást a lent megjelenő QR Kód beolvasására. Ezzel hozzá társítja a hozzáférését a hitelesítő alkalmazáshoz.<br><br><b>Későbbi Bejelentkezéseknél:</b> legközelebb már egy hitelesítő kódot is meg kell adnia, amit az alkalmazás jelenít meg.</p>';





 

 

?>
