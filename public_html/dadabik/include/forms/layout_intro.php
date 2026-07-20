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
<h1>Graphic Layout</h1>

<p>From your <b>configuration file</b> (<i>/include/config_custom.php</i>) you can change:

<ul>
<li>The logo
<li>The colour theme
<li>The menu type
<li>The results grid type
</ul>

From your <b>custom CSS file</b> (<i>/css/styles_screen_custom.css</i>) you can add your CSS code, overriding the default one. Almost all the elements in the DaDaBIK interface has a CSS class or ID so you can customize the interface in great detail.<br><br>

From your <b>layout hooks file</b> (<i>/include/custom_functions/layout_hooks.php</i>) you can add your HTML, Javascript and PHP code to 15 different parts of your application layout, to customize them.<br><br>

From the <a href="datagrid_configurator.php?tablename=<?php echo $table_name; ?>">Datagrid templates page</a> you can create HTML templates to be used for overriding the standard results grid layout.<br><br>

Finally, you can <b>edit some DaDaBIK core files</b> used for generating the main elements of the layout (header.php, footer.php, results_grid.php, form.php, details.php); this approach, however, is not recommended (your changes will be lost when you upgrade DaDaBIK).<br><br>

<?php
?>


You can see in action some of the above techniques in the <i>General Graphic Customization</i> chapter of this video:<br><br>
<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/aTSTzxp0_qc?start=5557" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

</p>