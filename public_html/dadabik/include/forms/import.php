        <p><form  action='index.php?tablename=<?php echo urlencode($table_name); ?>&function=import_from_csv&step=2' method='post' enctype='multipart/form-data'  >

        <p><?php echo $normal_messages_ar['file_type']; ?> 
<?php if ($import_xlsx_allowed === 1){ ?>  
        <input type="radio" name="file_type" value="xlsx" checked onclick="show_hide_csv_options(0)">XLSX
<?php } ?>  


<?php if ($import_csv_allowed === 1){ ?>          
        <input type="radio" name="file_type" value="csv" onclick="show_hide_csv_options(1)">CSV
<?php } ?>  


<?php if ($import_ods_allowed === 1){ ?>  
        <input type="radio" name="file_type" value="ods" onclick="show_hide_csv_options(0)">ODS 
<?php } ?>  
        
        <div id="csv_options" style="display:none">
        
        <p> <?php echo $normal_messages_ar['csv_file_must_be_utf8_encoded']; ?>
        <table><tr><td style="width:100px;"><?php echo $normal_messages_ar['delimiter']; ?>:</td><td style="width:150px;"><?php echo $normal_messages_ar['values_enclosed_by']; ?>:</td><!--<td>New line character:</td>--></tr>
        
        <tr><td><input type="text" size="3" name="csv_delimiter" value="," maxlength="1"></td><td> 
         
        <input type="text" size="3" name="csv_enclosure" value='"'> </td><!--<td> 
          
        <input type="text" size="3" name="csv_new_line" value="\n"></td> --></tr></table></div> 
        
        <br><p><input type="file" class="form-control-file" name="source_file">
        <br>
        <input type="submit" class="btn btn-primary" value="<?php echo $normal_messages_ar['load_file']; ?>"  id="upload_file_btn">

<?php

if ($orazio_edition === 1){ 

echo '<span id="confirmation_message_container"><div class="msg_error" id="alert_message"><h3>The feature is <b>DISABLED</b> when you use an '.$orazio_name.' free account.<br>You need to <a href="index.php?function=deploy" >publish and deploy</a> this application to enable it.</h3></div></span>';


}



?>

