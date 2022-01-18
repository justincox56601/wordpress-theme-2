<?php 

/*
=======================
    includes
=======================
*/


include get_template_directory() . "/email-integrations/email-interface.php";
include get_template_directory() . "/analytics/analytics.php";
include get_template_directory() . "/analytics/analytics-admin.php";
include get_template_directory() . "/customizer/front-page-customizer.php";
    new FrontPageCustomizer();

include_once get_template_directory() . '/test-terms.php';
    new Terms();

include get_template_directory() . "/customizer/navigation-customizer.php";
    new navCustomizer();

include_once get_template_directory() . "/inc/icons.php";

include get_template_directory() . "/email-integrations/email-interface-2.php";



/*
=======================
    Engueueing Scripts
=======================
*/

function newsletter_enqueue_scripts(){
    wp_enqueue_style("newsletterstyle", get_template_directory_uri() . "/css/newsletter.css", array(), "1.0", 'all');
    wp_enqueue_script('newsletterjs', get_template_directory_uri() . "/js/newsletter.js", array(), "1.0", TRUE);
}
add_action("wp_enqueue_scripts", "newsletter_enqueue_scripts");

/*function portfolio_custom_blocks(){
    wp_enqueue_script('blockjs', get_template_directory_uri() . "/js/blocks.js", array("wp-blocks", "wp-i18n", "wp-editor"), "1.0", TRUE);
    wp_enqueue_style("blockstyle", get_template_directory_uri() . "/css/block.css", array(), "1.0", 'all');
}
add_action("enqueue_block_editor_assets", "portfolio_custom_blocks");*/

/*
=======================
    Register Menus
=======================
*/
function newsletter_register_menus(){
    register_nav_menu("primary", "Main Header Navigation");
    register_nav_menu("secondary", "Secondary Header Navigation");
}
add_action("init", "newsletter_register_menus");
/*
=======================
    Theme Supports
=======================
*/

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo', array(
    "flex-width" => true,
    "flex-height" => true,
));
add_theme_support('html5');
add_theme_support('title-tag');
