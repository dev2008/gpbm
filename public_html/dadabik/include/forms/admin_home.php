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
<?php if ($orazio_edition === 1){
    echo '<h1>How to Customize Your Application</h1>';
}
else{
    
    echo '<h1>How to Configure & Customize Your DaDaBIK Application</h1>';
 
}
?>
<?php if ($orazio_edition === 1){ ?>

    <p>AppifyText.ai is an AI agent that uses the low-code/no-code platform <a href="https://dadabik.com" target="_blank">DaDaBIK</a> to build applications for you. The application you create through AppifyText.ai is essentially a DaDaBIK application. Therefore, you can refer to the <a href="https://dadabik.com/index.php?function=show_documentation" target="_blank">DaDaBIK documentation</a> for comprehensive details on customization and configuration. However, this page provides some simple instructions to help you get started.</p>

<?php }else{ ?>
    <p>This DEV Area allows you to configure the DaDaBIK application you have created.</p>

<?php } ?>

<h2>Standard CRUD pages</h2>

<?php if ($orazio_edition === 1){ ?>

<p>A <b>Table</b> and <strong>CRUD Page</strong> has been created for each <i>entity</i> you need in your application (e.g. if you are building a CRM, one of your entities will be <i>customers</i>); this page contains: a standard report (a results grid that represents the records you have in your table), the forms needed to: insert, edit and filter records and some additional tools that allow you to generate graph, pivot and PDF reports.</p>

<?php } else{ ?>
<p>During the <i>classic</i> installation, DaDaBIK automatically creates a <strong>CRUD</strong> (Create, Read, Update, Delete) page for each database table or Excel sheet; these pages feature a standard report, forms for record management (insert/edit/search forms), and tools for generating graphs, pivot tables, and PDF reports.</p>
<?php } ?>

<?php if ($orazio_edition === 0){ ?>

<p>If, instead, you have built a <strong>blank</strong> application (i.e. based on an empty database), the first thing you have to do is create one or more tables using the <a href="data.php">Data</a> tab. DaDaBIK will guide you through the <i>installation</i> of these tables and, even in this case, for each table, a <strong>CRUD page</strong> will be created.</p>

<?php } ?>

<h2>Customization</h2>

<p>You can <strong>customize those pages</strong> in various ways, here is something you can try:<p>

<ul>

<li>You want to change a label or a field type? Use the <a href="internal_table_manager.php" class="tool-name">🛠️ Forms Configurator</a></li>

<li>You want to hide a field from a form? Use the <a href="permissions_manager.php?function=configure" class="tool-name">🔐 Permissions Manager</a>, choose the users group you want to set the permissions for and set NO for any particular field/form combination you want to remove.</li>

<li>You want to re-arrange the pages in your Menu? Use the <a href="tables_inclusion.php" class="tool-name">📄 Pages</a> and the <a href="admin.php?function=show_menu_preview" class="tool-name">📋 Menu</a> tab.</li>

<?php if (false && $orazio_edition === 0){ ?>

<li>You want to synchronize your application after having modified your DB schema (e.g. you added a new field to a table)? Use  <a href="db_synchro.php">DB Synchro</a>.</li>

<?php } ?>

</ul>

<p>The Forms Configurator and the Permissions Manager will probably cover most of your customization needs. Consider the use of <strong>lookup fields</strong> (in Forms Configurator choose select_single as field type and see the lookup parameters), <strong>formula fields</strong> and <strong>custom formatting functions</strong> (also available in Form Configurator), which are very powerful tools.</p>

<h2>Tables & Views</h2>

<p>If you need additional tabels or want to add/edit existing tables you can do it from the <a href="data.php" class="tool-name">🔢 Data</a> tab.</p>

<?php if ($orazio_edition === 1){ ?>

<p>Sometimes you may need to create a <strong>VIEW</strong> page, for example to have a pre-filtered version of of a <b>Table</b>. Exactly as tables, also views can be created from the <a href="data.php" class="tool-name">🔢 Data</a> tab.</p>

<?php } else{ ?>

    <p>Sometimes the default pages created by DaDaBIK (one for each table) are not enough, maybe you need a report page based on two or more tables and the lookup fields can't solve your needs, or you may need particular filters. In theses case you can create a <strong>VIEW</strong>, DaDaBIK will create the corresponding page in your application exactly as it does it for tables. You can do that from the <a href="data.php" class="tool-name">🔢 Data</a> tab.</p>

<?php } ?>




<h2>Custom pages</h2>

<p>Finally, you can also create completely custom HTML, Javascript or PHP pages, write your own code and add the pages to your DaDaBIK applications, these are called <strong>custom pages</strong> and you can set them from the	 <a href="tables_inclusion.php" class="tool-name">📄 Pages</a> section.</p>


<?php if ($orazio_edition === 1){ ?>

    <p>For additional and more specific information, including instructions to master advanced features such as <strong>custom buttons</strong>, <strong>Hooks</strong>, <strong>PDF templates</strong> and others, consult the <a href="https://dadabik.com/index.php?function=show_documentation" target="_blank">DaDaBIK documentation</a></p>

        <p>You can also follow one of our video tutorials:</p>




<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/JxmuWePr2JQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
<?php } else{ ?>

    <h2>Advanced Features</h2>
    
<p>For additional and more specific information, including instructions to master advanced features such as <strong>custom buttons</strong>, <strong>Hooks</strong>, <strong>PDF templates</strong>, and more, please refer to the <a href="https://dadabik.com/index.php?function=show_documentation" target="_blank">DaDaBIK documentation</a>.

<p>You can also watch one of our <a target="_blank" href="https://www.youtube.com/@DaDaBIK">video tutorials</a>. Here are two recommended starting points:

<p>1) To explore No-Code features, this is a great place to begin:<br><br>

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/1zHHe7TbAB4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<p>2) To explore Low-Code features (e.g. adding custom PHP and JavaScript), we recommend the following video — start from 1:43:05<br><br>

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/JxmuWePr2JQ?si=lX9H9wAyLu-6ioyN&amp;start=6185" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<p>Finally, some additional general configuration parameters can be directly set from the file <code>/include/config_custom.php</code>; here you can, for example, enable/disable/adjust some DaDaBIK features (e.g. authentication, upload, email notices, languages, ...).</p>

<p>Have fun 🙂</p>


<?php } ?>


