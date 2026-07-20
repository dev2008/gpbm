<?php

/*
Add here the translations you want to modify, what you set here will override what is set in english.php. You can do the same for the other languages as well, for example adding an italian_custom.php or a french_custom.php file.

E.g. in english.php you have this sentence, that is showed when a record is updated:

"record_updated" => "Item correctly updated.",

if you don't like it, you can override the translation adding to the english_custom.php file the following line:

$normal_messages_ar['record_updated'] = 'The record has been correctly updated.';

You can also change the sentence according to the table you are managing, for example, the following sentence is used to override the original one only if we are usign the "quotes" table

if (isset($table_name) && $table_name === 'quotes'){
	$normal_messages_ar['record_inserted'] = 'quote correctly inserted, please edit it to add the products included in this quote. ';
} 

If you are working in BETA mode, you also need to create a *_custom_beta.php file and work on it. For English, both the file english_custom.php and english_custom_beta.php are already available. For all the other language you have to create both files, if you need to add your custom translations.


*/

/* IF YOU ARE EDITING A PREPACKAGED APP (e.g. dadasales, dadahelpdesk), THE FILE YOU WANT TO MODIFY IS english_custom_prepackaged_app.php */
