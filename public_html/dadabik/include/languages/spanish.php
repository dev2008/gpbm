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
"insert"    => "Adiciona un nuevo registro",
"quick_search"    => "Búsqueda rápida",
"search/update/delete" => "Buscar/actualizar/borrar registros",
"insert_short"    => "Adicionar",
"search_short" => "Buscar",
"advanced_search" => "Búsqueda Avanzada",
"insert_anyway"    => "Adicionar de todos modos",
"search"    => "Buscar registro",
"update"    => "Actualizar",
"ext_update"    => "Actualizar tu perfil",
"yes"    => "Si",
"no"    => "No",
"go_back" => "Atrás",
"edit" => "Editar",
"delete" => "Borrar",
"details" => "Detalles",
"insert_as_new" => "Adicionar como nuevo",
"multiple_inserts" => "Multiples adiciones",
"change_table" => "Cambiar tabla",
"search_includes_following_fields" => 'La búsqueda incluye los siguientes campos:',
);

// normal messages
$normal_messages_ar = array (
"cant_edit_record_locked_by" => "No puedes editar este registro porque esta bloqueado por el usuario: ",
"lost_locked_not_safe_update" => "No tienes, o has perdido el bloqueo sobre este registro, no es seguro actualizarlo, por favor edítalo nuevamente",
"insert_item" => "Adicionar item",
"show_all_records" => "Mostrar todos los registros",
"show_records" => "Mostrar items",
"ldap_user_dont_update" => "Este es un usuario importado (LDAP, Google, ...): su grupo es la única información que podrás cambiar.",
"leave_blank_keep_current_password" => "Campo vacío para mantener la contraseña actual",
"users_will_be_forced_change_after_login_except_ldap" => "Si lo habilitas, este usuario deberá cambiar su contraseña después del siguiente ingreso. No aplica para las autenticaciones externas (ej. LDAP, Google).",
"deleting_group_also_delete_users" => "Por favor nota que al remover un grupo también removerás a todos sus usuarios",
"remove_search_filter" => "borrar el filtro de búsqueda",
"set_default_search_filter" => "configura el filtro de búsqueda por defecto", 
"logout" => "Cerrar sesión",
"top" => "Tope",
"last_search_results" => "últimos resultados",
"show_all" => "Mostrar todo",
"home" => "Principal",
"select_operator" => "Selecciona el operador:",
"all_conditions_required" => "Todas las condiciones requeridas",
"any_conditions_required" => "Cualquiera de las condiciones requeridas",
"all_contacts" => "Todos los contactos",
"removed" => "removido",
"please" => "Por favor",
"and_check_form" => "y revisa la forma.",
"and_try_again" => "e intenta de nuevo.",
"none" => "ninguno",
"are_you_sure" => "¿Estas seguro?",
"delete_all" => "borrar todo",
"really?" => "Seguro?",
"delete_are_you_sure" => "Estas a punto de borrar el registro inferior, estas seguro?",
"required_fields_missed" => "No has llenado algunos de los campos requeridos.",
"alphabetic_not_valid" => "Has adicionado números en un campo alfabético.",
"numeric_not_valid" => "Has adicionado caracteres no numéricos en un campo numérico.",
"email_not_valid" => "La dirección de e-mail que ingresaste no es valida.",
"timestamp_not_valid" => "Timestamp no es válido.",
"url_not_valid" => "la(s) URL(s) ingresada(s) no es/son válida(s)",
"phone_not_valid" => "El número telefónico adicionado no es válido.",
"date_not_valid" => "Has ingresado una o mas fechas erradas.",
"entered_value_not_valid" => "El valor ingresado no es válido.",
"id_group_admin_by_non_admin_not_valid" => "No puedes crear o editar un usuario administrador si no eres un administrador igualmente",
"similar_records" => "Los siguientes elementos se parecen a los que quieres adicionar (podrás ver máximo ".$number_duplicated_records." elementos similares, pueden ser mas).<br>¿Que quieres hacer?",
"similar_records_short" => "Los siguientes elementos se parecen a los que quieres adicionar (podrás ver máximo ".$number_duplicated_records." elementos similares, pueden ser mas).",
"no_records_found" => "No se encontraron registos.",
"records_found" => "registros encontrados",
"number_records" => "Número de registros: ",
"details_of_record" => "Detalles del registro",
"details_of_record_just_inserted" => "Detalles del registro recién ingresado",
"edit_record" => "Editar el registro",
"back_to_the_main_table" => "Volver a la tabla principal",
"previous" => "Anterior",
"next" => "Siguiente",
"edit_profile" => "Editar tu perfil",
"i_think_that" => "Creo que ",
"is_similar_to" => " es similar a ",
"page" => "Página ",
"of" => " de ",
"records_per_page" => "registros por página",
"day" => "Día",
"month" => "Mes",
"year" => "Año",
"administration" => "Administración",
"create_update_internal_table" => "Crear o modificar tabla interna",
"other...." => "otro....",
"insert_record" => "Adicionar nuevo registro",
"search_records" => "Buscar registros",
"exactly" => "exacto",
"like" => "similar",
"required_fields_red" => "Los campos requeridos estan en rojo.",
"insert_result" => "Adicionar resultado:",
"record_inserted" => "registro adicionado exitosamente.",
"a_corresponding_user_account" => 'Una cuenta de usuario correspondiente',
"has_been_created_with_random_password" => 'ha sido creada con una contraseña aleatoria',
"update_result" => "Actualizar resultado:",
"record_updated" => "Registro actualizado.",
"profile_updated" => "Su perfil ha sido actualizado.",
"delete_result" => "Borrar resultado:",
"record_deleted" => "Registro eliminado.",
"records_deleted" => "Los elemento(s) han sido borrado(s) correctamente.",
"duplication_possible" => "Es posible duplicar",
"fields_max_length" => "Ha ingresado demasiado texto en uno o mas campos.",
"current_upload" => "Archivo actual",
"delete" => "Borrar",
"total_records" => "Total de registros",
"confirm_delete?" => "Confirmas el borrado?",
"unselect_all" => "De-seleccionar todos",
"select_all" => "Elegir todo",
"only_elements_this_page_selected_other_pages_kept" => "Solo los elementos de la página actual serán elegidos. Si elegiste elementos de otras páginas, estos elementos se mantendrán elegidos.",
"all_elements_will_be_unselected_also_other_pages" => "Todos los elementos serán no-elegidos, también aquellos de otras páginas.",
"delete_selected" => "Borrar los seleccionados",
"is_equal" => "es igual a",
"is_different" => "no es igual a",
"is_not_null" => "no es nulo",
"is_not_empty" => "no es vacío",
"contains" => "contiene",
"doesnt_contain" => "no contiene",
"starts_with" => "comienza con",
"ends_with" => "termina con",
"greater_than" => ">",
"less_than" => "<",
"greater_equal_than" => ">=",
"less_equal_than" => "<=",
"between" => "entre",
"between_and" => "y", // used for the between search operator: between .... AND .....
"export_to_csv" => "Exportar a CSV",
"export_to_ical" => 'Exportar iCal',
"generate_ical_url" => 'Generar iCal URL',
"generate_ical_url_page_1" => 'Este es el URL para suscribirte, puedes adicionarlo a tu app de calendario ( como Google Calendar o Apple Calendar)',
"generate_ical_url_page_2" => 'Nota que contiene tu usuario y un token que no expira. El alcance del token esta limitado a leer datos de la tabla',
"generate_ical_url_page_3" => "y sus tablas relacionadas.",
"new_insert_executed" => "Adición ejecutada",
"new_update_executed" => "modificación ejecutada",
"null" => "Nulo",
"is_null" => "es nulo",
"is_empty" => "es vacío",
"continue" => "continuar",
'current_password' => 'clave actual',
'new_password' => 'clave nueva',
'new_password_repeat' => 'clave nueva (repetir)',
'password_changed' => 'la clave ha sido cambiada',
'change_your_password' => 'cambia tu clave',
'your_info' => 'tu información',
'sort_by' => 'ordenar por',
'sort' => 'ordenar',
'pie_chart' => 'Diagrama de torta',
'bar_chart' => 'Diagrama de barras',
'line_chart' => 'Diagrama de linea',
'doughnut_chart' => 'Diagrama de rosca',
'show_report' => 'Mostrar diagrama',
'show_labels' => 'Mostrar etiquetas',
'show_legend' => 'Mostrar leyenda',
'group_data_by' => 'Agregar datos por',
'x_axis' => 'eje-X',
'y_axis' => 'eje-Y',
'show' => 'mostrar',
'percentage' => 'porcentaje',
'count' => 'conteo',
'sum' => 'suma',
'average' => 'promedio',
'min' => 'mínimo',
'max' => 'máximo',
'variance' => 'variabilidad',
'standard_deviation' => 'desviación estándar',

'simple_report' => 'reporte simple',
'advanced_sql_report' => 'reporte SQL avanzado',
'type_your_custom_sql_query_here' => 'Ingresa tu busqueda SQL aquí: ',
'current_search_filter_is_not_used' => '(El siguiente filtro de búsqueda no será usado)',
'advanced_sql_reports_are_disabled' => 'Los reportes SQL avanzados están deshabilitados',
'advanced_sql_report_instructions_first_part' => 'Puedes escribir una selección SQL personalizada,  ej. supón que tienes una tabla de <b>clientes</b> con un campo <b>edad_cliente</b>, puedes mostrar la composición de edades de tus clientes usando la siguiente selección:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => 'Recuerda, el primer campo que selecciones sera usado como el <b>eje X</b> del diagrama, el segundo campo como el <b>eje Y</b>.<br/><br/>En la documentación encontrarás mas ejemplos.',
'generate_report' => 'Generar Diagrama',
'use_semicolon_forbidden_omit_trailing_semicolmn' => 'El uso del punto y coma (;) no es permitido por seguridad, puedes omitir el último punto y coma.',
'sql_report_must_start_with_select' => 'El reporte personalizado SQL debe empezar con "SELECT "',
'show_embed_code' => 'Mostrar código incrustado',
'embed_code_instructions' => 'Puedes copiar el código a continuación y pegarlo en una página personalizada para incrustar este gráfico o reporte; al incrustar varios gráficos/reportes en una página, puedes crear fácilmente una consola. Ten en cuenta que, si el informe se ha generado después de una búsqueda, el filtro de búsqueda no se guarda en el código del informe. Si necesita incrustar un informe basado en un filtro de búsqueda estable, la mejor manera es crear una VISTA y generar el informe a partir de ella. También considera que la Paginación no esta disponible en un reporte contenido, solo X registros serán mostrados, donde X es el numero de <i>registros por página</i>.
',
'produce_pdf' => 'Generar PDF',
'choose_pdf_template' => 'Seleccionar plantilla PDF',
'no_pdf_template' => 'Standard Template', // to change
'show_revisions' => 'Mostrar cambios',
'hide_revisions' => 'Esconder cambios',
'record_revisions' => 'Grabar cambios',
'revisions' => 'Cambios',
'for_this_table_revisions_not_enabled_no_revisions' => 'Para esta tabla, los cambios no están habilitados o aún no tiene cambios.',
'generate_pivot' => 'Generar pivot',
'you_might_have_additional_rows_admin_set_to' => 'Es posible que tenga filas adicionales, pero el administrador estableció el máximo de filas en  ',
'add_column' => 'adicionar columna ', // add column in the pivot report
'remove_this_column' => 'remover esta columna', // remove column in the pivot report
'advanced_sql_report_instructions_pivot_part' => 'Para la generación de tabla pivot, además, puedes usar un alias (para especificar etiquetas) y puedes usar más de una función agregada, por ejemplo: SELECT marca AS marca_producto, count(*) AS Number, AVG (precio_producto) AS AvgPrice FROM productos GROUP BY marca',
'advanced_sql_report_instructions_stat_card_part' => 'Para <b>stat cards</b>, no hay agregado; debes seleccionar un solo elemento. Por ejemplo, esto cuenta el número de clientes: SELECT COUNT(*) FROM customers.',
"record_inserted_close_window" => "El item se ha adicionado correctamente, puedes <a href='#' onclick='window.close();return false;'>cerrar</a> esta ventana.",

"import" => "Importar",
'file_type' => 'Tipo de documento',
'delimiter' => 'Delimitador',
'values_enclosed_by' => 'Valores opcionalmente incluidos por',
'load_file' => 'Cargar documento',
'error_no_file_selected' => 'Error, no has seleccionado un documento para cargar.',
'values_enclosed_cannot_be_blank' => 'El parámetro "Values optionally enclosed by" no puede estar en blanco, puedes dejar el párametro por defecto aun si no usas un caracter incuido.',
'error_file_type_not_supported_or_different' => 'Error, este tipo de documento no es soportado o no es el mismo que seleccionaste en la página anterior',
'error_too_much_time_passed' => 'Error, ha pasado mucho tiempo.',
'processing_row' => 'Procesando fila',
'new_elements_will_be_inserted_to_proceed_click_continue' => 'nuevos elementos serán adicionados. Para proceder, presiona "Continuar" al final de la página', // this message will be used with a number, e.g. "5 new elements will be added ... ",
'following_as_example_20_rows' => 'Los siguientes son solo los primeros 20 registros de tu documento.',
'possible_duplication_continue_to_update' => 'Posible dupicación, algunos elementos tienen los mismos valores en los mismos campos únicos (la duplicación puede estar también dentro del documento). Estos son algunos elementos duplicados. Hasta el momento no se han adicionado o actualizado elementos. Si presionas "Continuar" al final de la página, para estos elementos, se actualizarán los registros con la nueva información dentro del documento. ',
'elements_have_been_inserted_updated' => 'elementos han sido adicionados/actualizados.', // this message will be used with a number, e.g. "5 elements have been inserted/updated"
'to_verify_elements_click_continue_filter_set' => 'Para verificar los elementos adicionados/actualizados presiona "Continuar". Tienes un filtro de búsqueda que te permite ver solo los elementos adicionados/actualizados (podrás ver solo agunos de ellos si el administrador define filtros adicionales).',
'no_element_has_been_inserted' => 'No se ha adicionado un elemento.',
'error_no_sheet_with_name'=> 'Error, no hay hoja con nombre:',
'elements_results_last_import' => 'Los elementos que ves son el resultado de la ultima importación (podrás ver solo agunos de ellos si el administrador define filtros adicionales). Para ver todos los elementos presiona "Remover filtro de búsqueda"',
'csv_file_must_be_utf8_encoded' => 'El documento CSV debe estar codificado en UTF-8.',
'hide_show_quick_filters' => 'Mostrar/esconder filtros rápidos',
'show_search_url' => 'Mostrar el URL de búsqueda',
'search_url_instructions' => 'Este URL ejecuta la misma busqueda que hiciste, y también le aplica el mismo criterio de orden (si lo tiene).',
'it_seems_you_uploaded_other_files_cancelled' => ' Parece que cargaste algunos documentos en otro formulario pero no completaste el proceso de salvar/adicionar. Esas cargas han sido canceladas.',
"double_click_to_edit" => 'Double click to edit', // to change
'number_uploaded_files' => 'Número de documentos cargados: ',
'file_uploaded_file_will_replace' => 'Archivo enviado! El archivo actual (si existe) será reemplazado después de salvar el formulario.',
'generic_upload_error' => 'Error genérico de envío!',
'collapse_sidebar' => 'Cerrar Barra de Menu',
'ask_anything_about' => 'Haz cualquier pregunta sobre',
'ask_ai' => 'Preguntar a IA',
'ask_questions_data_read_only' => 'Preguntas sobre tus datos (solo lectura)',
'ai_generated_answer' => 'Respuesta generada por IA',
'dadabrain_created_separate_view_results' => 'DaDaBrain creó una vista separada con los resultados.',
'you_can_open_it' => 'Puedes abrirla',
'here' => 'acá',
'please_verify_ai_generated_results_may_be_incorrect' => 'Por favor verifica los resultados generados por IA antes de tomar decisiones. Los resultados pueden ser incorrectos o estar incompletos.',
'to_answer_I_used_this_query' => 'Para responder, he usado este comando SQL',
'list_below_has_not_changed_current_filters_still_apply' => 'La lista abajo no ha cambiado. Tus filtros actuales (si hay) están aplicados.',
'questions' => 'Preguntas',
'discard_question' => 'Descartar pregunta',
'question_discarded' => 'Pregunta descartada correctamente',
'dadabrain_disabled_check_enable_dadabrain' => '<b>DaDaBrain deshabilitado</b>. Por favor revisa el parámetro de configuración $enable_dadabrain y las implicaciones de seguridad relacionadas.',
'use_show_dadabrain_form_to_hide_ai_form' => 'Usa $show_dadabrain_form para esconder el formulario IA DaDaBrain.',
'open_the_view' => 'Abrir vista',
);

$normal_messages_ar['months_short'][1] = 'Ene';
$normal_messages_ar['months_short'][2] = 'Feb';
$normal_messages_ar['months_short'][3] = 'Mar';
$normal_messages_ar['months_short'][4] = 'Abr';
$normal_messages_ar['months_short'][5] = 'May';
$normal_messages_ar['months_short'][6] = 'Jun';
$normal_messages_ar['months_short'][7] = 'Jul';
$normal_messages_ar['months_short'][8] = 'Ago';
$normal_messages_ar['months_short'][9] = 'Sep';
$normal_messages_ar['months_short'][10] = 'Oct';
$normal_messages_ar['months_short'][11] = 'Nov';
$normal_messages_ar['months_short'][12] = 'Dic';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = 'Lun';
$normal_messages_ar['days_short'][2] = 'Mar';
$normal_messages_ar['days_short'][3] = 'Mie';
$normal_messages_ar['days_short'][4] = 'Jue';
$normal_messages_ar['days_short'][5] = 'Vie';
$normal_messages_ar['days_short'][6] = 'Sab';
$normal_messages_ar['days_short'][7] = 'Dom';
// error messages
$error_messages_ar = array (
"int_db_empty" => "Error, la base de datos interna esta vacia.",
"get" => "Error obteniendo variables.",
"no_functions" => "Error, no se eligieron funciones<br>Por favor vuelve a la pagina principal.",
"no_unique_key" => "Error, no se determino clave primaria en tu tabla.",
"upload_error" => "Un error ha ocurrido mientras se subia el archivo.",
"no_authorization_update" => "No tienes autorización para modificar este registro.",
"no_authorization_delete" => "No tienes autorización para eliminar este registro.",
"no_authorization_view" => "No tienes autorización para consultar este registro.",
"deleted_only_authorizated_records" => "Han sido borrados los registros sobre los cuales tienes autorización.",
"record_from_which_you_come_no_longer_exists" => "El registro del que proviene ya no existe.",
"date_not_representable" => "El valor de una fecha en este registro no puede ser representado por la forma dia-mes-año, el valor es: ",
"this_record_is_the_last_one" => "Este es el último registro.",
"this_record_is_the_first_one" => "Este es el primer registro.",
"current_password_wrong" => 'la clave no es correcta',
"passwords_are_different" => 'las dos claves son diferentes',
"new_password_must_be_different_old" => 'la nueva contraseña debe ser diferente de la actual',
"new_password_is_empty" => 'la nueva clave esta vacía',
"you_cant_live_edit_click_edit" => 'No puedes editar este campo en vivo, presiona el boton de edición a la izquierda para editar el regstro completo.',
"you_dont_have_enough_permissions_to_edit_field" => 'No tienes suficientes permisos para editar este archivo.'
);

//login messages
$login_messages_ar = array(
"username" => "usuario",
"password" => "clave",
"please_authenticate" => "Necesitas identificarte para continuar",
"login" => "login",
"username_password_are_required" => "Se requiere Usuario y Clave",
"pwd_gen_link" => "crear clave",
"incorrect_login" => "Usuario o clave incorrectos",
"pwd_explain_text" =>"Ingresa una palabra a ser usada como clave y presiona <b>Encriptar!</b>",
"pwd_suggest_email_sending"=>"Si lo deseas puedes enviarte un mensaje para recordar la clave",
"pwd_send_link_text" =>"enviar mensaje!",
"pwd_encrypt_button_text" => "Encriptar!",
"pwd_register_button_text" => "Registrar clave y salir",
"too_many_failed_login_account_blocked" => "Muchos intentos fallidos, tu cuenta ha sido bloqueada.",
"warning_you_still_have" => "Atención, todavía tienes ",
"attempts_before_account_blocking" => " intentos antes de que tu cuenta sea bloqueada.",
"verification_code" => "Código de verificación",
"verification_code_is_required" => "Es necesario el código de verificación",
"incorrect_verification_code" => "Código de verificación incorrecto",
"enable_two-factor_authentication" => "Habilitar la autenticación de dos factores",
"two-factor_authentication" => "Autenticación de dos factores",
);

// Link "Register" in the login form
$login_messages_ar['register'] = 'Registrar';

// Registration form messages
$login_messages_ar['create_your_account'] = 'Crear tu cuenta';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = 'nombre';
$login_messages_ar['last_name'] = 'apellido';
$login_messages_ar['registration_form_checkbox_1'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_2'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change
$login_messages_ar['registration_form_checkbox_3'] = 'Accept <a href="example_terms.html" target="_blank">terms and conditions</a>'; // to change


// form submit buttons
$login_messages_ar['submit_register_new_account'] = 'Envía y crea una nueva cuenta';
$login_messages_ar['back_to_login'] = 'Regresar al login';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = 'Cuenta creada, recibirás un email de confirmación que contiene un enlace de activación. Por favor selecciona el enlace para activar tu cuenta.';
$login_messages_ar['email_confirmed_login'] = 'Tu cuenta ha sido activada, ya puedes ingresar: ';
$login_messages_ar['account_created_login'] = 'Tu cuenta ha sido creada, ya puedes ingresar: ';
$login_messages_ar['confirmation_link_expired_resent'] = 'El enlace de confirmación ha expirado, un nuevo enlace ha sido enviado a tu correo electrónico.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = 'Este enlace de confirmación no es correcto, tu cuenta no puede ser activada.';
$login_messages_ar['your_email_not_confirmed_yet'] = 'Tu correo electrónico no ha sido confirmado todavía.';
$login_messages_ar['email_already_in_use'] = 'This email is already in use.'; // to change
$login_messages_ar['username_already_in_use'] = 'This username is already in use.'; // to change
$login_messages_ar['registration_email_subject'] = "Please confirm your registration"; // to change
$login_messages_ar['registration_email_content'] = "Hello,\nsomeone (hopefully you) has registered an account at ".$site_url_to_display.". To complete your registration click on this link within 24h:"; // to change

// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = 'Olvide mi contraseña';


// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = 'Ingresa tu correo electrónico, recibirás tu usuario y una contraseña temporal';

// form submit button
$login_messages_ar['submit'] = 'Enviar';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = 'Si este correo electrónico pertenece a un usuario, recibirás un mensaje con una contraseña temporal.';

// email subject
$login_messages_ar['your_temporary_password'] = 'Tu contraseña temporal';

// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "Alguien (ojalá tu) ha pedido una nueva contraseña de acceso ".$site_url_to_display."\n\Si TU pediste la nueva contraseña, esta bien, aca esta tu contraseña temporal (valida por cinco minutos solamente). POr favor note que el email no es un canal seguro de comnunicación para contraseñas asi que cambie su contraseña inmediatamente despues de ingresar y nunca uses - como tu contraseña principal - la contraseña temporal que te hemos enviado.";

$login_messages_ar['temporary_password_email_content_part_2'] = "Si no puedes accesar a tu cuenta usando esta contraseña temporal, significa que alguien acceso primero, por favor comumícate con el administrador del sistema.\n\nSi no pediste la nueva contraseña, significa que alguien mas esta tratando de accesar a tu cuenta: por favor ingresa tan pronto como puedas usando tu antigua contraseña (esto hace que la contraseña temporal sea inválida) y comumícate con el administrador del sistema. Si no puedes ingresar con tu antigua  contraseña, significa que alguien probablemente ya accesó tu cuenta usando la nueva contraseña temporal, por favor comumícate con el administrador del sistema.";



$login_messages_ar['intro_2fa_secret_page'] = '<h2>Importante: Esta página se muestra solo por razones de seguridad.</h2><p>Por favor seguir las instrucciones antes de salir de la página, ya que no será posible acceder a esta información nuevamente.</p><p><b>Descarga una Aplicación de Autenticación:</b> Visita tu tienda de apps (Google Play/App Store) y descarga una aplicación de autenticación, como Google Authenticator o Authy.<br><br><b>Escanea el código QR:</b> Usa la aplicación para escanear el código QR que aparece abajo. Esto enlazará tu cuenta con la aplicación de autenticación.<br><br><b>Ingresos Futuros:</b> La siguiente vez que ingreses, se te pedirá ingresar un código de verificación generado por tu aplicación de autenticación</p>';



?>
