<?php
function analytics_add_admin_page(){
    

    //generate submen pages
    add_submenu_page("newsletter_settings", 'Analytics Settings', "Analytics", 'manage_options', 'analytics_settings', 'analytics_settings_create_page');

    //activate custom settigns
    add_action("admin_init", "analytics_custom_settings");
}
add_action("admin_menu", "analytics_add_admin_page");

function analytics_settings_create_page(){
    //generates the admin page for the analytics
    require_once get_template_directory() . "/content/content-analytics-settings.php";
}

function analytics_custom_settings(){
    //regiser settings
    register_setting("analytics-settings-group", "fb_pixel");
    register_setting("analytics-settings-group", "google_analytics");

    //register settings section
    add_settings_section('analytics-settings-options', 'Analytics Settings', 'analytics_settings_options', 'analytics_settings');

    //register settings fields
    add_settings_field('analytics-fb-pixel', 'Facebook Pixel', 'analytics_fb_pixel_field', 'analytics_settings', 'analytics-settings-options');
    add_settings_field('analytics-google-analytics', 'Google Analytics', 'analytics_google_analytics_field', 'analytics_settings', 'analytics-settings-options');
}

function analytics_settings_options(){
    echo "<p>Customize Your Analytics Settings</p>";
}

function analytics_fb_pixel_field(){
    $pixel = get_option('fb_pixel');
    echo "<textarea name='fb_pixel' id='fb_pixel' cols='100' rows='10'>" . $pixel . "</textarea>";
}

function analytics_google_analytics_field(){
    $code = get_option('google_analytics');
    echo "<textarea name='google_analytics' id='google_analytics' cols='100' rows='10'>" . $code . "</textarea>";
}

function add_analytics_to_head(){
    if(get_option('fb_pixel') !== NULL){
        echo get_option('fb_pixel');
    }

    if(get_option('google_analytics') !== NULL){
        echo get_option('google_analytics');
    }
}
add_action('wp_head', 'add_analytics_to_head');