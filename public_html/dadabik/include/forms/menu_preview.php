<?php

$home_available = 0;
$static_pages_ar = build_static_pages_ar();

$id_group_preview = 'group_'.$id_admin_group;
if (isset($_POST['id_group_preview'])){
    $id_group_preview = $_POST['id_group_preview'];
}

$sql = "SELECT ".$quote.$users_table_username_field.$quote." FROM ".$quote.$users_table_name.$quote." WHERE ".$quote.$users_table_id_group_field.$quote." = :id_group_preview";
$res_prepare = prepare_db($conn, $sql);

$values_to_bind = array();
$values_to_bind['id_group_preview'] = substr($id_group_preview,6); // remove group_

foreach ($values_to_bind as $key => $value){
    $res_bind = bind_param_db($res_prepare, ':'.$key, $value);
}
$res = execute_prepared_db($res_prepare,0);

$num_rows = 0;
while($row = fetch_row_db($res_prepare)){
    $username_preview = $row[$users_table_username_field];
    $num_rows++;
    break;
}

if ($num_rows !== 1){
    echo 'Unexpected error, no users available for this group';
    exit;
}

if ($enable_granular_permissions === 1 && $enable_authentication === 1){
    // refresh permissions
    $_SESSION['logged_user_infos_ar_for_menu'] = get_user_infos_ar_from_username_password($username_preview, '',0,1);
}

foreach($static_pages_ar as $static_page){
	if ($static_page['is_homepage_static_page'] == 'y'){
		$home_available = 1;
		
		$home_url = $dadabik_main_file.'?function=show_static_page&id_static_page='.$static_page['id_static_page'];
		$home_desc = $static_page['link_static_page'];
		break;
	}
}

$allowed_table_infos_ar = build_allowed_table_infos_ar($_SESSION['logged_user_infos_ar_for_menu']);

$allowed_menu_custom_page_infos_ar = build_allowed_menu_custom_page_infos_ar($static_pages_ar,$_SESSION['logged_user_infos_ar_for_menu']);

$home_desc_to_pass = NULL;
$home_url_to_pass = NULL;
if (isset($home_url)){
    $home_url_to_pass = $home_url;
    $home_desc_to_pass = $home_desc;
}

$menu_items_ar = build_menu_items_ar($allowed_table_infos_ar, $allowed_menu_custom_page_infos_ar, $home_url_to_pass, $home_desc_to_pass);

$group_ids_select = build_subjects_select($id_group_preview, 'id_group_preview', ' onchange="if ($(\'#id_group_preview\').val() !== \'\') { this.form.submit(); }"');

// check if there are duplicated order (position in the menu), if yes, the menu cannot work
$duplicated_menu_order = 0;
$menu_order = array();

$sql = "select menu_order_static_page FROM ".$quote.$dadabik_static_pages_tab_name.$quote;;
$res = execute_db($sql, $conn);
while ($row = fetch_row_db($res)){
    if (isset($menu_order[$row[0]])){
        $menu_order[$row[0]]++;
    }
    else{
        $menu_order[$row[0]] = 1;
    }

}
$sql = "select menu_order_table FROM ".$quote.$table_list_name.$quote;;
$res = execute_db($sql, $conn);
while ($row = fetch_row_db($res)){
    if (isset($menu_order[$row[0]])){
        $menu_order[$row[0]]++;
    }
    else{
        $menu_order[$row[0]] = 1;
    }

}

foreach($menu_order as $value){
    if ($value > 1){
        $duplicated_menu_order = 1;
        break;
    }
}



?>

<p><h1>Menu Preview</h1>
  <p>This is a preview of your application menu, drag & drop menu items to change their position. Select <b>Pages</b> in the top menu for additional settings.

<?php
if ($duplicated_menu_order === 1){
    txt_out('<div class="msg_alert"><p>One or more of your menu items have the same "Menu order", the drag & drop feature cannot correctly work.<br>From the Pages tab, make "Menu order" unique (across table-based, view-based and custom pages) before continuing.</p></div>');
 }
 ?>

<?php if ($enable_granular_permissions === 1 && $enable_authentication === 1){ ?>
  <p><form action="admin.php?tablename=<?= urlencode($table_name)?>&function=show_menu_preview" method="POST">Preview the menu as <?= $group_ids_select ?></form></p>
<?php } ?>

	<div class="container">
		<div class="row header">
			
      

		<div class="row">
				<div id="menu" class="list-group col">
<?php
$current_parent = 'top';
foreach ($menu_items_ar as $menu_item){
    
    $indent_to_display = '';
    $additional_class_to_display = '';
    $parent_to_display = '';
    $title_to_display = '';
    
    $show_item = 1;
    if (  ($menu_item['link'] === $users_table_name || $menu_item['link'] === $groups_table_name) && $menu_item['type'] === 'table'     ){
        $show_item = 0;
    }
    
    if ($show_item === 1){
    
        if ($menu_item['parent'] !== 'top' && $menu_item['parent'] !== ''){
            $additional_class_to_display .= ' indented-menu-item';
            if ($menu_item['parent'] !== $current_parent){
                $parent_to_display = '<div class="list-group-item ignore-elements" style="cursor:not-allowed;">'.$menu_item['parent'].'</div>';
            }
        }
        if ($menu_item['parent'] !== $current_parent){
            $current_parent = $menu_item['parent'];
        }
        
        if ($menu_item['type'] === 'home'){
            $additional_class_to_display .= ' cursor-not-allowed';
            $title_to_display .= 'A page set as home is always the first menu item, you cannot move it.';
        }
        else{
            $additional_class_to_display .= ' cursor-grab'; 
        }

    
        ?>
                <?= $parent_to_display; ?><div title="<?= $title_to_display; ?>" class="list-group-item<?php echo $additional_class_to_display; ?>" data-menu_item_type="<?php echo $menu_item['type']; ?>" data-menu_item_parent="<?php echo $menu_item['parent']; ?>" data-menu_item_link="<?php echo $menu_item['link']; ?>" data-menu_item_order="<?php echo $menu_item['order']; ?>"> <?php //echo $indent_to_display.$menu_item['desc'].' '.$menu_item['order'].' '.$menu_item['type']; ?><?php echo $indent_to_display.$menu_item['desc']; ?></div>
        <?php
    }
}
?>
			</div>
			
            
		</div>

		
        
        
		

	</div>





	
	<script>


menu = document.getElementById('menu'),


dragget_to_item = '';

// Example 5 - Handle
new Sortable(menu, {
    animation: 150,
    filter: ".ignore-elements",
    onMove: function (/**Event*/evt, /**Event*/originalEvent) {
         dragget_to_item = evt.related;
	},
    
    // Changed sorting within list
	onUpdate: function (evt) {
	    //alert(evt.item.dataset.menu_item_order);
	    //alert(dragget_to_item.dataset.menu_item_order)
	    /*
	    alert(evt.item.dataset.menu_item_order);
	    alert(evt.item.dataset.menu_item_type);
	    alert(evt.item.dataset.menu_item_link);
	    alert(evt.oldIndex+1);
	    alert(evt.newIndex+1);
	    */
	    //console.log((evt.item.previousElementSibling));
	    
	    
		$.ajax({
            url: "api.php?function=save_menu_order",
            data: ({
            type_menu_item: evt.item.dataset.menu_item_type,
            link_menu_item: evt.item.dataset.menu_item_link,
            old_position_menu_item: evt.item.dataset.menu_item_order,
            new_position_menu_item: dragget_to_item.dataset.menu_item_order,
            parent_destination_item: dragget_to_item.dataset.menu_item_parent,
            }),

            type: "POST",
            dataType: "json",
            success: function(data){

                enable_disable_loader('disable');

                if (data.status === 'ok'){
                    if (data.result !== 'done'){
                        alert('unexpected error save_menu_order.submit - ' + data.result+ ' - '+data.error_message);
                    }
                    else{
                        /*if ( dragget_to_item.dataset.menu_item_parent !== '' && dragget_to_item.dataset.menu_item_parent !== 'top' ){
                            evt.item.classList.add('indented-menu-item');
                        }
                        else{
                            evt.item.classList.remove('indented-menu-item');
                        }
                         $("body").addClass("showing_confirmation_message");
                         window.setTimeout( remove_showing_confirmation_message_class, 1000); 
                         */
                         location.reload();

                         
                    }
                }
                else{
                    alert('unexpected error save_menu_order.submit status not OK');
                }
            },
            error: function(data, status, e){
                enable_disable_loader('disable');
                alert('unexpected error save_menu_order.submit ajax error'+JSON.stringify(data));
            }
        });
		
		
		
		
	
		
	},
});



</script>




