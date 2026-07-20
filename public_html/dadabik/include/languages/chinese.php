<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
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
	"insert"    => "插入",
	"quick_search"    => "快速查找",
	"search/update/delete" => "搜索/更新/删除",
	"insert_short"    => "新建",
	"search_short" => "搜索",
	"advanced_search" => "Advanced Search", // to change
	"insert_anyway"    => "插入",
	"search"    => "搜索",
	"update"    => "保存",
	"ext_update"    => "上传你的文件",
	"yes"    => "是",
	"no"    => "不是",
	"go_back" => "返回",
	"edit" => "编辑",
	"delete" => "删除",
	"details" => "细节",
	"insert_as_new" => "Insert as new", // to change
	"multiple_inserts" => "Multiple inserts", // to change
	"change_table" => "调整表格",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
"cant_edit_record_locked_by" => "该项已被用户锁定，禁止编辑: ",
"lost_locked_not_safe_update" => "当前处于解锁模式，更新不安全，请重新开始编辑",
"insert_item" => "新建项",
"show_all_records" => "显示所有记录",
"show_records" => "显示记录",
"ldap_user_dont_update" => "This is an imported user (LDAP, Google, ...): his/her group is the only information you should change, if needed.",
"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
"remove_search_filter" => "删除搜索条件",
"set_default_search_filter" => "set default search filter", // to change 
"logout" => "登出",
"top" => "顶部",
"last_search_results" => "最后搜索结果",
"show_all" => "显示全部",
"home" => "主页",
"select_operator" => "选择操作: ",
"all_conditions_required" => "满足所有条件",
"any_conditions_required" => "满足任意条件",
"all_contacts" => "所有联系人",
"removed" => "移除",
"please" => "请",
"and_check_form" => "请核查表格",
"and_try_again" => "再试一次",
"none" => "无",
"are_you_sure" => "你确定吗？",
"delete_all" => "删除所有",
"really?" => "真的吗？",
"delete_are_you_sure" => "以下项将被删除，你确定吗？",
"required_fields_missed" => "必填项未完成填写",
"alphabetic_not_valid" => "无效填写",
"numeric_not_valid" => "无效数值",
"email_not_valid" => "无效邮件",
"timestamp_not_valid" => "无效的时间标记",
"url_not_valid" => "无效的网址",
"phone_not_valid" => "无效的电汇号码（请标注区号 e.g. +390523599318, 00390523599318, 0523599318.",
"date_not_valid" => "无效日期",
"entered_value_not_valid" => "The entered value is not valid.", // to change
"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin", // to change
"similar_records" => "The items below seem similar to the one you want to insert (I'll show max ".$number_duplicated_records." similar items, there could be more).<br>What do you want to do?", // to change
"similar_records_short" => "The items below seem similar to the one you want to insert (I'll show max ".$number_duplicated_records." similar items, there could be more).", // to change
"no_records_found" => "无记录",
"records_found" => "发现如下项",
"number_records" => "项目数: ",
"details_of_record" => "记录细节",
"details_of_record_just_inserted" => "刚插入的项目细节",
"edit_record" => "编辑记录",
"back_to_the_main_table" => "返回主表",
"previous" => "前一个",
"next" => "下一个",
"edit_profile" => "编辑文件",
"i_think_that" => "我认为 ",
"is_similar_to" => " 与..相似 ",
"page" => "页码 ",
"of" => " 属于； …的 ",
"records_per_page" => "每页的纪录",
"day" => "天",
"month" => "月",
"year" => "年",
"administration" => "管理单位",
"create_update_internal_table" => "创建或更新内部表格",
"other...." => "其他…",
"insert_record" => "插入新项",
"search_records" => "搜索记录",
"exactly" => "正确，的确",
"like" => "比如；像",
"required_fields_red" => "要求字段红色突出",
"insert_result" => "插入结果:",
"record_inserted" => "插入成功",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
"update_result" => "更新结果:",
"record_updated" => "更新成功",
"profile_updated" => "文件更新成功",
"delete_result" => "删除结果:",
"record_deleted" => "成功删除",
"records_deleted" => "Item(s) correctly deleted.", // to change
"duplication_possible" => "存在的副本",
"fields_max_length" => "输入字段过长",
"current_upload" => "当前文件",
"delete" => "删除",
"total_records" => "全部项",
"confirm_delete?" => "确认删除？",
"unselect_all" => "Unselect all", // to change
"select_all" => "Select all", // to change
"only_elements_this_page_selected_other_pages_kept" => "Only the elements of the current page will be selected. If you selected elements in other pages, such selection will be kept.", // to change
"all_elements_will_be_unselected_also_other_pages" => "All the elements will be unselected, also elements selected in other pages.", // to change
"delete_selected" => "Delete selected", // to change
"is_equal" => "等于",
"is_different" => "不同于",
"is_not_null" => "不等于零",
"is_not_empty" => "不为空",
"contains" => "包含",
"doesnt_contain" => "doesn't contain", // to change
"starts_with" => "开始于",
"ends_with" => "结束于",
"greater_than" => ">",
"less_than" => "<",
"greater_equal_than" => ">=",
"less_equal_than" => "<=",
"between" => "between", // to change
"between_and" => "and", // to change, used for the between search operator: between .... AND .....
"export_to_csv" => "导出到 CSV",
"export_to_ical" => 'Export iCal', // to change,
"generate_ical_url" => 'iCal URL', // to change,
"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
"new_insert_executed" => "新建插入任务生效",
"new_update_executed" => "新建更新任务生效",
"null" => "零；无效",
"is_null" => "等于零；无效",
"is_empty" => "无意义；空",
"continue" => "继续",
'current_password' => '目前密码',
'new_password' => '新密码',
'new_password_repeat' => '新密码重复输入',
'password_changed' => '更改密码',
'change_your_password' => '更改你的密码',
'your_info' => '你的信息',
'sort_by' => '以…分类',
'sort' => '分类',

'pie_chart' => '饼图',
'bar_chart' => '条形图',
'line_chart' => '折线图',
'doughnut_chart' => '统计图表',
'show_report' => '显示图表',
'show_labels' => '显示标签',
'show_legend' => '显示图例',
'group_data_by' => '综合数据',
'x_axis' => '横坐标轴',
'y_axis' => '纵坐标轴',
'show' => '显示',
'percentage' => '%',
'count' => '计算',
'sum' => '求和',
'average' => '平均值',
'min' => '最小值',
'max' => '最大值',
'variance' => '方差',
'standard_deviation' => '标准偏差',
'of' => '属于；…的',
'simple_report' => '简单报告',
'advanced_sql_report' => '高级标准数据查询报告',
'type_your_custom_sql_query_here' => '在此处输入自定义 SQL 查询。: ',
'current_search_filter_is_not_used' => '(The current search filter won\'t be used)', // to change
'advanced_sql_reports_are_disabled' => '禁用高级 SQL 报告',
'advanced_sql_report_instructions_first_part' => '您可以编写自定义 SQL 查询. e.g. 假设您有 <b>客户</b> 表 具有 <b>客户年龄</b> 字段, 您可以使用以下查询来显示客户的年龄构成:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => '您选择的第一个字段将是 <b>X-轴</b>, 第二个领域将是 <b>Y-轴</b>.<br/><br/>阅读文档的进一步例子.',
'generate_report' => '生成图表',
'use_semicolon_forbidden_omit_trailing_semicolmn' => '最后一个分号可省略 (;)',
'sql_report_must_start_with_select' => '自定义 SQL 报表必须以 开头 "SELECT "',
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
$normal_messages_ar['months_short'][1] = '1月';
$normal_messages_ar['months_short'][2] = '2月';
$normal_messages_ar['months_short'][3] = '3月';
$normal_messages_ar['months_short'][4] = '4月';
$normal_messages_ar['months_short'][5] = '5月';
$normal_messages_ar['months_short'][6] = '6月';
$normal_messages_ar['months_short'][7] = '7月';
$normal_messages_ar['months_short'][8] = '8月';
$normal_messages_ar['months_short'][9] = '9月';
$normal_messages_ar['months_short'][10] = '10月';
$normal_messages_ar['months_short'][11] = '11月';
$normal_messages_ar['months_short'][12] = '12月';

// please don't change the indexes (1,2,3,...) if you want your week to start on Sunday, set $weeks_start_on_sunday = 1 in config.php
$normal_messages_ar['days_short'][1] = '星期一';
$normal_messages_ar['days_short'][2] = '星期二';
$normal_messages_ar['days_short'][3] = '星期三';
$normal_messages_ar['days_short'][4] = '星期四';
$normal_messages_ar['days_short'][5] = '星期五';
$normal_messages_ar['days_short'][6] = '星期六';
$normal_messages_ar['days_short'][7] = '星期日';



// error messages
$error_messages_ar = array (
	"int_db_empty" => "错误！内部数据库空白",
	"get" => "变量错误",
	"no_functions" => "错误，请回到主页",
	"no_unique_key" => "错误，表格关键字不存在",
	"upload_error" => "文件上传时发生了错误",
	"no_authorization_update" => "无修改此项权限",
	"no_authorization_delete" => "无删除权限",
	"no_authorization_view" => "无权限查看该项",
	"deleted_only_authorizated_records" => "仅对你有权限的项作了删除",
	"record_from_which_you_come_no_longer_exists" => "已失效或者不存在",
	"date_not_representable" => "日期值不能在列表框中表示, 该值为: ",
	"this_record_is_the_last_one" => "最后一项",
	"this_record_is_the_first_one" => "该项是第一条",
	"current_password_wrong" => '当前密码错误',
	"passwords_are_different" => '两次输入密码不一致',
	"new_password_must_be_different_old" => 'the new password must be different from the current one', // to change
	"new_password_is_empty" => '新密码不能为空',
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => 'You don\'t have enough permissions to edit this field.' // to change
	);

//login messages
$login_messages_ar = array(
	"username" => "用户名",
	"password" => "密码",
	"please_authenticate" => "请先验证身份再继续",
	"login" => "登入",
	"username_password_are_required" => "请输入用户名和密码",
	"pwd_gen_link" => "创建密码",
	"incorrect_login" => "用户名或密码不正确",
	"pwd_explain_text" =>"输入单词作为密码并按<b>继续!</b>.",
	"pwd_explain_text_2" =>"按 <b>注册</b> 以下列形式写",
	"pwd_suggest_email_sending"=>"你也许需要发送邮件给自己用于记住密码",
	"pwd_send_link_text" =>"发送邮件!",
	"pwd_encrypt_button_text" => "继续!",
	"pwd_register_button_text" => "注销密码并退出",
	"too_many_failed_login_account_blocked" => "密码错误次数太多，你的账户已被锁定",
	"warning_you_still_have" => "警告 ",
	"attempts_before_account_blocking" => " 账户锁定前再试一次",
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
