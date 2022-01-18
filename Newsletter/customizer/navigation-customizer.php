<?php

class NavCustomizer{

    public $theme = 'newsletter';

    function __construct(){
        add_action('customize_register', array($this, "register_custom_sections"));
    }

    public function register_custom_sections($wp_customize){
        $wp_customize->add_section('nav-settings', array(
            'title' => "Navigation Settings",
            'panel' => 'nav_menus',
            'priority' => 1
        ));

        //number of menus
        $this->custom_basic_settings($wp_customize, array(
            'label' => 'Number of Menus',
            'default' => 1,
            'section' => 'nav-settings',
            'setting' => 'number-menus',
            'control' => 'number-menus-control',
            'type' => 'select',
            'choices' => array(
                1 => '1',
                2 => '2'
            ),
            'sanitize' => '',
        ));

        //Main Menu Alignment
        $this->custom_basic_settings($wp_customize, array(
            'label' => 'Single Menu Alignment',
            'default' => 'left',
            'section' => 'nav-settings',
            'setting' => 'menu-alignment',
            'control' => 'menu-alignment-control',
            'type' => 'select',
            'choices' => array(
                'left-align' => 'left',
                'right-align' => 'right',
            ),
            'sanitize' => '',
        ));

        //show logo
        $this->custom_basic_settings($wp_customize, array(
            'label' => 'Show Logo',
            'default' => 'checked',
            'section' => 'nav-settings',
            'setting' => 'nav-show-logo',
            'control' => 'nav-show-logo-control',
            'type' => 'checkbox',
            'choices' =>'',
            'sanitize' => '',
        ));
        
    }


    /*
    ==============================
        Utility Functions
    ==============================
    */

    private function custom_basic_settings($wp_customize, $args){
        extract($args);

        $wp_customize->add_setting($setting, array(
            'default' => $default,
            'sanitize_callback' => $sanitize 
        ));

        $wp_customize->add_control(new WP_Customize_Control($wp_customize, $control, array(
            'label' => __($label, $this->theme),
            'section' => $section,
            'settings' => $setting,
            'type' => $type,
            'choices' => $choices,
        )));
    }
}