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
	"insert"    => "新增",
	"quick_search"    => "快速搜尋",
	"search/update/delete" => "查詢/更新/删除",
	"insert_short"    => "新增",
	"search_short" => "搜尋",
	"advanced_search" => "進階查詢", 
	"insert_anyway"    => "確定新增",
	"search"    => "搜尋",
	"update"    => "更新",
	"ext_update"    => "上傳你的文件",
	"yes"    => "是",
	"no"    => "不是",
	"go_back" => "返回",
	"edit" => "编輯",
	"delete" => "删除",
	"details" => "細項內容",
	"insert_as_new" => "插入為新一筆", 
	"multiple_inserts" => "多筆新增", 
	"change_table" => "調整表格",
	"search_includes_following_fields" => 'The search includes the following fields:', // to change
);

// normal messages
$normal_messages_ar = array (
"cant_edit_record_locked_by" => "此筆記錄被佔用鎖定，禁止编輯: ",
"lost_locked_not_safe_update" => "當前處于解鎖模式，更新不安全，請重新開始编輯",
"insert_item" => "新增項",
"show_all_records" => "顯示所有記錄",
"show_records" => "顯示記錄",
"ldap_user_dont_update" => "This is an imported user (LDAP, Google, ...): his/her group is the only information you should change, if needed.", // to change
"leave_blank_keep_current_password" => "Leave it blank to keep the current password", // to change
	"users_will_be_forced_change_after_login_except_ldap" => "If enabled, this user will be required to set a new password after their next login. Not applied to external authentication (e.g., LDAP, Google).", // to change
	"deleting_group_also_delete_users" => "Please note that deleting a group will also remove all of its users", // to change
"remove_search_filter" => "解除搜尋條件",
"logout" => "登出",
"top" => "頂部",
"last_search_results" => "最後搜尋结果",
"show_all" => "顯示全部",
"home" => "首頁",
"select_operator" => "選擇操作: ",
"all_conditions_required" => "满足所有條件",
"any_conditions_required" => "满足任一條件",
"all_contacts" => "所有聯絡人",
"removed" => "移除",
"please" => "請",
"and_check_form" => "請檢查表格",
"and_try_again" => "再試一次",
"none" => "無",
"are_you_sure" => "你確定嗎？",
"delete_all" => "删除所有",
"really?" => "真的嗎？",
"delete_are_you_sure" => "全部删除，確定嗎？",
"required_fields_missed" => "必填項目未填寫",
"alphabetic_not_valid" => "無效的填寫",
"numeric_not_valid" => "無效的数值",
"email_not_valid" => "無效郵件",
"timestamp_not_valid" => "無效的時間格式",
"url_not_valid" => "無效的網址",
"phone_not_valid" => "無效的電話（請標註區域號 e.g. +390523599318, 00390523599318, 0523599318.", 
"date_not_valid" => "無效日期",
"entered_value_not_valid" => "The entered value is not valid.", // to change
"id_group_admin_by_non_admin_not_valid" => "You can't create or edit an admin user if you are not admin", // to change
"similar_records" => "下一筆資訊似乎相近於您要新增的 (您想顯示 最大的重覆筆數? ".$number_duplicated_records." 還是可能有更多的相近筆數？<br>哪一個是您想做的?", // to change
"similar_records_short" => "下一筆資訊似乎相近於您要新增的 (您想顯示 最大的重覆筆數? ".$number_duplicated_records." 還是可能有更多的相近筆數？", // to change
"no_records_found" => "無記錄",
"records_found" => "找到的記錄如下",
"number_records" => "筆數: ",
"details_of_record" => "記錄的細項",
"details_of_record_just_inserted" => "剛新增的記錄細項",
"edit_record" => "編輯記錄",
"back_to_the_main_table" => "返回主資料表",
"previous" => "前一筆",
"next" => "下一筆",
"edit_profile" => "編輯文件",
"i_think_that" => "我認為 ",
"is_similar_to" => " 與..相似 ",
"page" => "頁 ",
"of" => "of",
"records_per_page" => "每頁筆數",
"day" => "天",
"month" => "月",
"year" => "年",
"administration" => "管理階層",
"create_update_internal_table" => "創建或更新内部資料表格",
"other...." => "其他…",
"insert_record" => "插入新一筆",
"search_records" => "搜尋記錄",
"exactly" => "完全相符",
"like" => "類似；像",
"required_fields_red" => "必要項_红色突顯",
"insert_result" => "新增结果:",
"record_inserted" => "新增成功",
	"a_corresponding_user_account" => 'A corresponding user account', // to change
	"has_been_created_with_random_password" => 'has been created with random password', // to change
"update_result" => "更新结果:",
"record_updated" => "更新成功",
"profile_updated" => "文件更新成功",
"delete_result" => "删除结果:",
"record_deleted" => "成功删除",
"records_deleted" => "Item(s) correctly deleted.", // to change
"duplication_possible" => "存在的副本",
"fields_max_length" => "欄位最大長度",
"current_upload" => "此次上傳",
"delete" => "删除",
"total_records" => "全部資料",
"confirm_delete?" => "確認删除？",
"unselect_all" => "unselect全部",
"select_all" => "選擇全部", 
"only_elements_this_page_selected_other_pages_kept" => "只有本頁選擇項目會被保存. 一旦您選擇了其他頁, 此頁的選擇仍會被保留.", 
"all_elements_will_be_unselected_also_other_pages" => "所有項目會變unselected, 包含在其他頁的已挑選.",
"delete_selected" => "刪除已挑選的記錄", 
"is_equal" => "等於",
"is_different" => "不等於",
"is_not_null" => "不為Null",
"is_not_empty" => "不是空的",
"contains" => "包含",
"doesnt_contain" => "不包含",
"starts_with" => "開始於",
"ends_with" => "結束於",
"greater_than" => ">",
"less_than" => "<",
"greater_equal_than" => ">=",
"less_equal_than" => "<=",
"between" => "介於", 
"between_and" => "和", // to change, used for the between search operator: between .... AND .....
"export_to_csv" => "匯出到 CSV", 
"export_to_ical" => 'Export iCal', // to change,
"generate_ical_url" => 'iCal URL', // to change,
"generate_ical_url_page_1" => 'Here is the URL to subscribe, you can add it to your calendar app (such as Google Calendar or Apple Calendar)', // to change,
"generate_ical_url_page_2" => 'Please note that it contains your username and a token that never expires. The scope of the token is limited to reading data from the table', // to change,
"generate_ical_url_page_3" => "and its linked tables (if any).", // to change,
"new_insert_executed" => "新增生效",
"new_update_executed" => "更新生效",
"null" => "Null；無效",
"is_null" => "等于Null；無效",
"is_empty" => "無意義；空值",
"continue" => "繼續",
'current_password' => '目前密碼',
'new_password' => '新密碼',
'new_password_repeat' => '重覆輸入新密碼',
'password_changed' => '更改密碼',
'change_your_password' => '更改你的密碼',
'your_info' => '你的資訊',
'sort_by' => '以…分類排序',
'sort' => '分類排序',

'pie_chart' => '派餅圖',
'bar_chart' => '長條圖',
'line_chart' => '折線圖',
'doughnut_chart' => '甜甜圈圖',
'show_report' => '顯示圖表',
'show_labels' => '顯示標籤名',
'show_legend' => '顯示圖例',
'group_data_by' => '數據以何分群',
'x_axis' => '横坐標軸',
'y_axis' => '縱坐標軸',
'show' => '顯示',
'percentage' => '%',
'count' => '筆數',
'sum' => '總和',
'average' => '平均值',
'min' => '最小值',
'max' => '最大值',
'variance' => '方差',
'standard_deviation' => '標準差',
'of' => 'of',
'simple_report' => '簡單報表',
'advanced_sql_report' => '進階SQL查詢報表',
'type_your_custom_sql_query_here' => '在此處輸入自定義 SQL 查詢: ',
'current_search_filter_is_not_used' => '(搜尋過濾條件不使用)', // to change
'advanced_sql_reports_are_disabled' => '禁用進階 SQL 報表',
'advanced_sql_report_instructions_first_part' => '您可以编寫自定義 SQL 查詢. e.g. 假設您有 <b>客户</b> 資料表 具有 <b>客户年齡</b> 欄位, 您可以使用以下查詢来顯示客户的年齡構成:',
'advanced_sql_report_instructions_query_part' => '<br/><br/><div class=&quot;code_snippet&quot;>SELECT age_customer, count(*) FROM customers GROUP BY age_customer</div><br/><br/>', // DON'T TRANSLATE, LEAVE IT UNCHANGED
'advanced_sql_report_instructions_second_part' => '您的第一個字段將是 <b>X-軸</b>, 第二個部分將是 <b>Y-軸</b>.<br/><br/>閱讀說明檔的進一步範例.',
'generate_report' => '生成圖表',
'use_semicolon_forbidden_omit_trailing_semicolmn' => '最後一個分號可省略 (;)',
'sql_report_must_start_with_select' => '自定義 SQL 報表必須以 "SELECT " 開頭',
'show_embed_code' => '顯示嵌入的程式碼', 
'embed_code_instructions' => '您可以複製下列程式碼到您要顯示圖表之處; by embedding several chart/grid reports in a page you can easily create a dashboard. Please note that, if the report has been generated after a search, the search filter is not saved in the embed code. If you need to embed a report based on a stable search filter, the best way is to create a VIEW and generate the report starting from it. Also consider that pagination is not available in an embedded grid report, only X records will be displayed, where X is your current <i>items per page</i> setting.', // to change
'produce_pdf' => '產生 PDF', 
'choose_pdf_template' => '選擇 PDF template',
'no_pdf_template' => 'Standard Template', // to change
'show_revisions' => '顯示修訂', 
'hide_revisions' => '隱藏修訂', 
'record_revisions' => '記錄修訂', 
'revisions' => '版本修訂', 
'for_this_table_revisions_not_enabled_no_revisions' => '這個資料表尚未啟用修訂記錄,或者您尚未有記錄之前的修訂.', 
'generate_pivot' => '產生樞紐分析', // to change
'you_might_have_additional_rows_admin_set_to' => '超過最大權限記錄數量 ',
'add_column' => '新增樞紐分析欄位', 
'remove_this_column' => '移除樞紐分析欄位', 
'advanced_sql_report_instructions_pivot_part' => 'For Pivot Table generation, in addtion, you can use alias (to specify labels) and you can use more than one aggreagete functions, for example: SELECT brand AS ProductBrand, count(*) As Number, AVG(price_product) AS AvgPrice FROM products GROUP BY brand', // to change
'advanced_sql_report_instructions_stat_card_part' => 'For <b>stat cards</b>, instead, there is no aggregation; you must select only one element. For example, this counts the number of customers: SELECT COUNT(*) FROM customers.', // to change
"record_inserted_close_window" => "新增成功, 可以使用 <a href='#' onclick='window.close();return false;'>關閉</a> 本視窗.",

"import" => "匯入", 
'file_type' => '檔案形態', 
'delimiter' => '分隔符號', 
'values_enclosed_by' => 'Values optionally enclosed by', // to change
'load_file' => '上傳檔案', 
'error_no_file_selected' => '錯誤, 未選擇任何檔案.',
'values_enclosed_cannot_be_blank' => '這欄位 "Values optionally enclosed by" 不能空白, 如果您沒有任何enclosed values您可以使用default.', // to change
'error_file_type_not_supported_or_different' => '錯誤, 檔案類型不正確', // to change
'error_too_much_time_passed' => '錯誤, 已逾時.',
'processing_row' => '處理中．．row',
'new_elements_will_be_inserted_to_proceed_click_continue' => '如果點此頁末的"繼續"，新項目將被新增', // this message will be used with a number, e.g. "5 new elements will be added ... ", // to change
'following_as_example_20_rows' => '如下只是你檔案中前20筆資料.', // to change
'possible_duplication_continue_to_update' => '可能重覆, 有些必需單一值的欄位內容重覆了(重覆值也可能是存在在您的檔案中). 這裏是重覆項目. 此刻還未新增或修改任何資料.直到您點選本頁尾"繼續" ,那麼重覆的這些記錄將會被修正為新的值. ', // to change
'elements_have_been_inserted_updated' => '編修已生效', // this message will be used with a number, e.g. "5 elements have been inserted/updated" // to change
'to_verify_elements_click_continue_filter_set' => '想確認編修狀況請點選 "繼續". ', // to change
'no_element_has_been_inserted' => '無資料新增.', // to change
'error_no_sheet_with_name'=> '錯誤, 沒有這種名稱的sheet:', // to change
'elements_results_last_import' => '只顯示最後匯入的項目 (you might only see some of them if the administrator has set additional filters). 如果想看全部項目，請點選 "Remove search filter"', // to change
'csv_file_must_be_utf8_encoded' => 'CSV 檔必需是使用 UTF-8 編碼.', 
'hide_show_quick_filters' => '隱藏/顯示 快速搜尋', 
'show_search_url' => '顯示搜尋 URL', 
'search_url_instructions' => 'This URL executes the same search you made, also adding the sort criteria you applied (if any).', // to change,
"double_click_to_edit" => '雙擊編修', // to change
'it_seems_you_uploaded_other_files_cancelled' => ' 似乎你已經在他處想上傳一些檔案但未完成上傳程序，那些上傳將被取消.', 
'number_uploaded_files' => '上傳檔案數: ', 
'file_uploaded_file_will_replace' => '上傳完成! 儲存表單時，新檔案將取代原有檔案(如果有的話) ',
'generic_upload_error' => '上傳出現一般性錯誤! ',// to change,
'collapse_sidebar' => 'Collapse sidebar',// to change,
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
	"int_db_empty" => "錯誤！内部資料庫空白",
	"get" => "變量錯誤",
	"no_functions" => "錯誤，請回到主頁",
	"no_unique_key" => "錯誤，鍵值重覆",
	"upload_error" => "文件上傳錯誤",
	"no_authorization_update" => "無修改權限",
	"no_authorization_delete" => "無刪除權限",
	"no_authorization_view" => "無權限查看",
	"deleted_only_authorizated_records" => "僅對你有權限的記錄做删除",
	"record_from_which_you_come_no_longer_exists" => "已失效或者不存在",
	"date_not_representable" => "日期值不能在列表框中表示, 該值為: ",
	"this_record_is_the_last_one" => "最後一項",
	"this_record_is_the_first_one" => "該項是第一條",
	"current_password_wrong" => '當前密碼錯誤',
	"passwords_are_different" => '兩次輸入密碼不一致',
	"new_password_must_be_different_old" => '新密碼必需不同於原密碼',
	"new_password_is_empty" => '新密码不能是空值',
	"you_cant_live_edit_click_edit" => 'You can\'t live edit this field, please click on the edit icon on your left to edit the entire record.', // to change
	"you_dont_have_enough_permissions_to_edit_field" => '你沒有權限修改此欄位' 
	);

//login messages
$login_messages_ar = array(
	"username" => "帳號",
	"password" => "密碼",
	"please_authenticate" => "請先登入",
	"login" => "登入",
	"username_password_are_required" => "請輸入帳號和密碼",
	"pwd_gen_link" => "創建密碼",
	"incorrect_login" => "帳號或密碼不正確",
	"pwd_explain_text" =>"輸入欲設密碼並按<b>繼續!</b>.",
	"pwd_explain_text_2" =>"按 <b>註冊</b> 以下列形式寫",
	"pwd_suggest_email_sending"=>"建議寄送郵件给自己以便保存密碼",
	"pwd_send_link_text" =>"寄送郵件!",
	"pwd_encrypt_button_text" => "繼續!", 
	"pwd_register_button_text" => "註冊密碼並退出",
	"too_many_failed_login_account_blocked" => "密碼錯誤次數太多，帳戶已被鎖定",
	"warning_you_still_have" => "警告 ",
	"attempts_before_account_blocking" => " 帳戶鎖定前再試一次",
	"verification_code" => "Verification code", // to change
	"verification_code_is_required" => "Verification code is required", // to change
	"incorrect_verification_code" => "The verification code is not correct", // to change
	"enable_two-factor_authentication" => "Enable Two-Factor Authentication", // to change
	"two-factor_authentication" => "Two-Factor Authentication", // to change
	
);

// to change, all the messages below

// Link "Register" in the login form
$login_messages_ar['register'] = '註冊新帳戶';

// Registration form messages
$login_messages_ar['create_your_account'] = '建立新帳戶';
$login_messages_ar['email'] = 'email';
$login_messages_ar['first_name'] = '名';
$login_messages_ar['last_name'] = '姓';
$login_messages_ar['registration_form_checkbox_1'] = '同意 <a href="example_terms.html" target="_blank">規定和協議條約</a>'; // to change
$login_messages_ar['registration_form_checkbox_2'] = '同意 <a href="example_terms.html" target="_blank">規定和協議條約</a>'; // to change
$login_messages_ar['registration_form_checkbox_3'] = '同意 <a href="example_terms.html" target="_blank">規定和協議條約</a>'; // to change

// form submit buttons
$login_messages_ar['submit_register_new_account'] = '確定註冊';
$login_messages_ar['back_to_login'] = '返回登入畫面';

// registration creation and confirmation messages and errors
$login_messages_ar['account_created_please_confirm_via_email'] = '帳號已新增, 你將收到一封包含啟動驗證連結的email. 請點選該連結以啟動你的帳號.';
$login_messages_ar['email_confirmed_login'] = '帳號已生效, 現在你可以登入: ';
$login_messages_ar['account_created_login'] = '帳號已新增, 現在你可以登入: ';
$login_messages_ar['confirmation_link_expired_resent'] = '驗證連結已過期失效, 新的驗證連結已重新email給你.';
$login_messages_ar['confirmation_link_not_correct_account_not_activated'] = '驗證連結不正確,帳號啟動失敗.';
$login_messages_ar['your_email_not_confirmed_yet'] = '你尚未確認驗證的email.';
$login_messages_ar['email_already_in_use'] = '這email已經被使用過.';
$login_messages_ar['username_already_in_use'] = '這帳戶名稱已被使用.'; 
$login_messages_ar['registration_email_subject'] = "請確認註冊"; // to change
$login_messages_ar['registration_email_content'] = "Hello,\n有人 (希望是你) 已經在 ".$site_url_to_display.". 註冊了一個帳戶，要完成註冊請在24小時內點選這連結:"; 

// Link "Forgotten password" in the login form
$login_messages_ar['forgotten_passowrd'] = '忘記密碼';

// forgotten password form
$login_messages_ar['enter_email_will_get_temporary_password'] = '輸入您的email, 您將會收到新帳密';
// form submit button
$login_messages_ar['submit'] = '確認送出';

// forgotten password confirmation messages and email
$login_messages_ar['if_email_is_user_temporary_password_sent'] = '如果這email 正確對應一個已存的用戶, 你將在這email中收到一個暫時的新密碼.';
// email subject
$login_messages_ar['your_temporary_password'] = '你的暫時密碼';
// email body
$login_messages_ar['temporary_password_email_content_part_1'] = "某人(但願是你) 要求更新cess ".$site_url_to_display."的密碼\n\n如果正是閤下，那麼就沒有任何疑點, 這是你新的暫時密碼 (有效時間僅5分鐘). 請注意這 email 並未使用安全加密協議，所以請在登入後立即變更為你的長久主要密碼，也永遠不要使用這個我們給您的暫時密碼當你的長久主要密碼.";
$login_messages_ar['temporary_password_email_content_part_2'] = "如果你使用這個暫時密碼未能登入, 那表示有其他人先你一步已經登入並修改了你的密碼, 請立即連絡你的系統管理員.\n\n如果你並未要求這暫時密碼, 那表示有其他人正想試著使用你的帳號: 請儘速使用你的原密碼登入 (這將可以使暫時密碼失效alid) 然後聯絡你的系統管理員. 如果你已無法使用原密碼登入, 那表示有其他人已經用暫時密碼登入在使用你的帳號, 請聯絡系統管理員.";


$login_messages_ar['intro_2fa_secret_page'] = '<h2>Important: This page is displayed only once for security reasons.</h2><p>Please complete the setup instructions before leaving the page, as you will not be able to access this information again.</p><p><b>Download an Authentication App:</b> Visit your app store (Google Play/App Store) and download an authentication app, such as Google Authenticator or Authy.<br><br><b>Scan the QR Code:</b> Use the app to scan the QR code displayed below. This will link your account to the authentication app.<br><br><b>Future Logins:</b> The next time you log in, you will be prompted to enter a verification code generated by your authentication app.</p>'; // to change
?>
