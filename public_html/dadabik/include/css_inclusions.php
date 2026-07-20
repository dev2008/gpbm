<?php

$force_reload_content = '';
if ( $force_reload_css_js === 1){
    $force_reload_content = '?v='.rand(1,10000);
}

echo '<link rel="stylesheet" href="css/normalize.css" type="text/css" media="screen"><link rel="stylesheet" href="css/bootstrap_5.3.2.css"><link rel="stylesheet" href="css/styles_screen_13.5.css'.$force_reload_content.'" type="text/css"><link href="include/boxicons/css/boxicons.min.css" rel="stylesheet">';

if (isset($_GET['only_graph'])){
    // override margin
    echo '<style>body{margin-top: 0px; margin-bottom: 0px;}</style>';
}

echo '<link rel="preload" href="include/fonts/'.$config['font'].'/'.$config['font'].'.woff2" as="font" type="font/woff2" crossorigin><link rel="stylesheet" media="screen" href="include/fonts/'.$config['font'].'/fonts.css">';

echo '<link rel="stylesheet" href="include/fontawesome5/css/all.min.css" rel="stylesheet">';
echo '<link rel="stylesheet" href="css/fontawesome.css"> <!-- fontawesome4 -->';

if($config['theme'] !== 'Default' && $ui_style === 'classic'){
    $css_theme = parse_css_theme_template();
    echo '<style>'.$css_theme.'</style>';

    if($config['theme'] === 'Dark'  || $config['theme'] === 'Dark_Light-Top'){ // additional CSS rules just for the dark theme
        echo '<link href="css/dark_theme_12.7.css?v='.$force_reload_content.'" rel="stylesheet" />';
        if($config['theme'] === 'Dark'){
            echo '<style>#language_button, #account_button{color: #cfcfcf!important;}</style>';
        }
    }        
} 
echo '<link rel="stylesheet" href="css/styles_screen_custom.css'.$force_reload_content.'" type="text/css" media="screen">';
echo "<style>\n".$config['custom_css']."\n</style>";

if ($config['font'] !== 'manrope'){
    echo "<style>\nbody,input,textarea,.main-menu{font-family: '".ucwords(str_replace('_', ' ', $config['font']))."',Arial, sans-serif;}\n</style>"; 
}

if ($ui_style === 'modern'){
    echo '<link rel="stylesheet" href="css/styles_screen_13.5_26_ui.css'.$force_reload_content.'" type="text/css">';
}