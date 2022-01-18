<?php
/*
==============================
    FRONT PAGE CUSTOMIZER SETTINGS
==============================
    - Custom panel
    1) Contnet Settings
        a) Logo / title check box
        b) logo image upload
        c) title text
        d) Headline text
        e) Sub headline text
        f) submit button text
    2) Image Settings
        a) image upload
    3) Font Settings
        a) title size, color, weight
        b) headline size, color, weight
        c) sub headline size, color, weight
    4) Color Settings
        a) background color
        b) highlight color
    5) Custom CSS
    6) Utility Functions
        a) text sanitize
        b) color sanitize
*/

class FrontPageCustomizer{
    
    public $theme = "newsletter";
    public $font_options = array(
        "Verdana, Geneva, Tahoma, sans-serif" => 'Verdana',
        "Georgia, 'Times New Roman', Times, serif" => 'Georgia',
        "'Montserrat', sans-serif" => 'Montserrat',
        "'Ubuntu', sans-serif" => 'Ubuntu',
        "'Times New Roman', Times, serif" => 'Times New Roman',
        "Arial, Helvetica, sans-serif" => 'Arial'
        );

    function __construct(){
        add_action('customize_register', array($this, "register_custom_sections"));
        add_action('wp_head', array($this, "custom_css"));
    }

    public function register_custom_sections($wp_customize){
        //adds the custom sections 
        $this->custom_panel($wp_customize);
        $this->content_section($wp_customize);
        $this->image_section($wp_customize);
        $this->general_settings($wp_customize);
    }


    private function custom_panel($wp_customize){
        $wp_customize->add_panel('front-page-settings', array(
            'title' => "Front Page Settings",
            'description' => "All of your front page settings are here",
            'priority' => 30
        ));
    }
    /*
    ==============================
        General Settings
    ==============================
    */
    private function general_settings($wp_customize){
        //creates the general settings panel in the customizer
        $wp_customize->add_section('general-settings', array(
            'title' => "General Settings",
            //'panel' => 'front-page-settings', - This is commented out because I am putting it in the main panel
            'priority' => 2
            
        ));

        $this->general_font_settings($wp_customize);
        $this->background_color($wp_customize);
        $this->highlight_color($wp_customize);
    }

    private function general_font_settings($wp_customize){
        //sets the general font settings
        
        //font
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'site-settings-font',
            'section' => 'general-settings',
            'default' => '',
            'label' => "Font",
            'sanitize' => '',
            'control' => 'site-settings-fonts-control',
            'type' => 'select',
            'choices' => $this->font_options,
        ));

        //Font color
        $this->custom_color_settings($wp_customize, array(
            'setting' => 'site-settings-font-color',
            'section' => 'general-settings',
            'default' => '#000000',
            'label' => "Font Color",
            'sanitize' => array($this, 'color_sanitize'),
            'control' => 'site-settings-font-color-control',
        ));

        //Font Size
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'site-settings-font-size',
            'section' => 'general-settings',
            'default' => 16,
            'label' => "Font Size",
            'sanitize' => '',
            'control' => 'site-settings-font-control',
            'type' => 'number',
            'options' => '',
        ));

        //title font weight
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'site-settings-font-weight',
            'section' => 'general-settings',
            'default' => 400,
            'label' => "Font Weight",
            'sanitize' => '',
            'control' => 'site-settings-font-weight-control',
            'type' => 'number',
            'options' => '',
        ));

        //Line height
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'site-settings-font-line-height',
            'section' => 'general-settings',
            'default' => 1,
            'label' => "Line Height",
            'sanitize' => '',
            'control' => 'site-settings-font-height-control',
            'type' => 'number',
            'options' => '',
        ));
    }

    private function background_color($wp_customize){
        //sets the sites background color.
        $this->custom_color_settings($wp_customize, array(
            'setting' => "site-background-color",
            'section' => 'general-settings',
            'default' => "#e6e6e6",
            'label' => "Background Color",
            'control' => 'site-background-color-control',
            'sanitize' => array($this, 'color_sanitize')
        ));
    }

    private function highlight_color($wp_customize){
        $this->custom_color_settings($wp_customize, array(
            'setting' => "site-highlight-color",
            'section' => 'general-settings',
            'default' => "#f66832",
            'label' => "Highlight Color",
            'control' => 'site-highlight-color-control',
            'sanitize' => array($this, 'color_sanitize')
        ));
    }


    /*
    ==============================
        Content Settings
    ==============================
    */

    private function content_section($wp_customize){
        $wp_customize->add_section('content-settings', array(
            'title' => "Content Settings",
            'panel' => 'front-page-settings',
            'priority' => 3
            
        ));

        $this->show_title_setting($wp_customize);
        $this->title_text_setting($wp_customize);
        $this->logo_image_setting($wp_customize);
        $this->headline_setting($wp_customize);
        $this->subheadline_setting($wp_customize);
        $this->submit_button_text($wp_customize);
        $this->terms_page($wp_customize);
    }


    private function show_title_setting($wp_customize){
        $wp_customize->add_setting('show-title', array(
            'default' => "Title",
            'sanitize_callback' => array($this, 'show_title_sanitize')
        ));

        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'front-page-show-title-control', array(
            'label' => "Show Title Text or Logo Image",
            'section' => 'content-settings',
            'settings' => 'show-title',
            'type' => 'select',
            'choices' => array('Title' => 'Title', 'Logo' => 'Logo')
        )));
    }

    public function show_title_sanitize($input){
        return ($input === "Title") ? "Title" : "Logo";
    }

    private function title_text_setting($wp_customize){

        //title text
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-title',
            'section' => 'content-settings',
            'default' => 'My Title',
            'label' => "Title",
            'sanitize' => array($this, 'text_sanitize'),
            'control' => 'front-page-title-control',
            'type' => 'text',
            'options' => ''
        ));

        //title font
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-title-font',
            'section' => 'content-settings',
            'default' => '',
            'label' => "Title Font",
            'sanitize' => '',
            'control' => 'front-page-title-font-control',
            'type' => 'select',
            'choices' => $this->font_options,
        ));

        //Title color
        $this->custom_color_settings($wp_customize, array(
            'setting' => 'front-page-title-color',
            'section' => 'content-settings',
            'default' => '#000000',
            'label' => "Title Color",
            'sanitize' => array($this, 'color_sanitize'),
            'control' => 'front-page-title-color-control',
        ));

        //title size
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-title-size',
            'section' => 'content-settings',
            'default' => 16,
            'label' => "Title Size",
            'sanitize' => '',
            'control' => 'front-page-title-size-control',
            'type' => 'number',
            'options' => '',
        ));

        //title font weight
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-title-font-weight',
            'section' => 'content-settings',
            'default' => 100,
            'label' => "Title Font Weight",
            'sanitize' => '',
            'control' => 'front-page-title-font-weight-control',
            'type' => 'number',
            'options' => '',
        ));

        //title line height
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-title-line-height',
            'section' => 'content-settings',
            'default' => 1,
            'label' => "Title Line Height",
            'sanitize' => '',
            'control' => 'front-page-title-line-height-control',
            'type' => 'number',
            'options' => '',
        ));

        
    }


    private function logo_image_setting($wp_customize){
        $wp_customize->add_setting('front-page-logo', array(
            'default' => ''
        ));

        $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'front-page-logo-control', array(
            'label' => 'Logo Image',
            'section' => 'content-settings',
            'settings' => 'front-page-logo',
            'height' => 400,
            'width' => 400,
            'flex_width' => true,
            'flex_height' =>true
        )));
    }

    private function headline_setting($wp_customize){

        //headline text
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-headline',
            'section' => 'content-settings',
            'default' => 'More Leads, Less Work',
            'label' => "Headline",
            'sanitize' => array($this, 'text_sanitize'),
            'control' => 'front-page-headline-control',
            'type' => 'text',
            'options' => ''
        ));

        //headline font
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-headline-font',
            'section' => 'content-settings',
            'default' => '',
            'label' => "headline Font",
            'sanitize' => '',
            'control' => 'front-page-headline-font-control',
            'type' => 'select',
            'choices' => $this->font_options,
        ));

        //headline color
        $this->custom_color_settings($wp_customize, array(
            'setting' => 'front-page-headline-color',
            'section' => 'content-settings',
            'default' => '#000000',
            'label' => "Headline Color",
            'sanitize' => array($this, 'color_sanitize'),
            'control' => 'front-page-headline-color-control',
        ));

        //headline size
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-headline-size',
            'section' => 'content-settings',
            'default' => 16,
            'label' => "Headline Size",
            'sanitize' => '',
            'control' => 'front-page-headline-size-control',
            'type' => 'number',
            'options' => '',
        ));

        //headline font weight
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-headline-font-weight',
            'section' => 'content-settings',
            'default' => 700,
            'label' => "Headline Font Weight",
            'sanitize' => '',
            'control' => 'front-page-headline-font-weight-control',
            'type' => 'number',
            'options' => '',
        ));

        //headline line height
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-healine-line-height',
            'section' => 'content-settings',
            'default' => 1,
            'label' => "Headline Line Height",
            'sanitize' => '',
            'control' => 'front-page-headline-line-height-control',
            'type' => 'number',
            'options' => '',
        ));
    }

    private function subheadline_setting($wp_customize){

        //sub headline text
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-sub-headline',
            'section' => 'content-settings',
            'default' => 'This light weight theme gets you up and running in minutes',
            'label' => "Sub Headline",
            'sanitize' => array($this, 'text_sanitize'),
            'control' => 'front-page-sub-headline-control',
            'type' => 'textarea',
            'options' => ''
        ));

        //Sub Headline font
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-sub-headline-font',
            'section' => 'content-settings',
            'default' => '',
            'label' => "Sub Headline Font",
            'sanitize' => '',
            'control' => 'front-page-sub-headline-font-control',
            'type' => 'select',
            'choices' => $this->font_options,
        ));

        //sub headline color
        $this->custom_color_settings($wp_customize, array(
            'setting' => 'front-page-sub-headline-color',
            'section' => 'content-settings',
            'default' => '#000000',
            'label' => "Sub Headline Color",
            'sanitize' => array($this, 'color_sanitize'),
            'control' => 'front-page-sub-headline-color-control',
        ));

        //sub headline size
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-sub-headline-size',
            'section' => 'content-settings',
            'default' => 16,
            'label' => "Sub Headline Size",
            'sanitize' => '',
            'control' => 'front-page-sub-headline-size-control',
            'type' => 'number',
            'options' => '',
        ));

        //sub headline font weight
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-sub-headline-font-weight',
            'section' => 'content-settings',
            'default' => 400,
            'label' => "Sub Headline Font Weight",
            'sanitize' => '',
            'control' => 'front-page-sub-headline-font-weight-control',
            'type' => 'number',
            'options' => '',
        ));

        //headline line height
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-sub-healine-line-height',
            'section' => 'content-settings',
            'default' => 1,
            'label' => "Sub Headline Line Height",
            'sanitize' => '',
            'control' => 'front-page-sub-headline-line-height-control',
            'type' => 'number',
            'options' => '',
        ));
    }

    private function submit_button_text($wp_customize){
        //sub headline text
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-submit-text',
            'section' => 'content-settings',
            'default' => 'SUBMIT',
            'label' => "Submit Button Text",
            'sanitize' => array($this, 'text_sanitize'),
            'control' => 'front-page-submit-text-control',
            'type' => 'text',
            'options' => ''
        ));
    }

    private function terms_page($wp_customize){
        //Drop down for terms and privacy policy page
        $this->custom_basic_settings($wp_customize, array(
            'setting' => 'front-page-terms-page',
            'section' => 'content-settings',
            'default' => '',
            'label' => "Terms and Privacy Policy Page",
            'sanitize' => '',
            'control' => 'front-page-terms-page-control',
            'type' => 'dropdown-pages',
            'options' => ''
        ));
    }

    /*
    ==============================
        Image Settings
    ==============================
    */

    private function image_section($wp_customize){
        $wp_customize->add_section('featured-image-settings', array(
            'title' => "Featured Image Settings",
            'panel' => 'front-page-settings',
            'priority' => 3
            
        ));

        $this->featured_image_setting($wp_customize);
    }

    private function featured_image_setting($wp_customize){
        $wp_customize->add_setting('front-page-featured-image', array(
            'default' => ''
        ));

        $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize, 'front-page-featured-image-control', array(
            'label' => 'Featured Image',
            'section' => 'featured-image-settings',
            'settings' => 'front-page-featured-image',
            //'height' => 400,
            //'width' => 400,
            'flex_width' => true,
            'flex_height' =>true
        )));
    }

    /*
    ==============================
        Color Settings
    ==============================
    */

    private function color_settings($wp_customize){
       /*$wp_customize->add_section('front-page-color-settings', array(
            'title' => "Color Settings",
            'panel' => 'general-settings',
            'priority' => 4
        ));*/

        $this->background_color($wp_customize);
        //$this->title_color($wp_customize);
        //$this->headline_color($wp_customize);
        //$this->sub_headline_color($wp_customize);
        $this->highlight_color($wp_customize);
        
    }

    

    

    /*
    ==============================
        Custom CSS
    ==============================
    */

    public function custom_css(){
        ?>
            <style>
                :root{
                    --highlight-color: <?php echo get_theme_mod('site-highlight-color') ?>;
                }

                * {
                    font-family: <?php echo get_theme_mod('site-settings-font') ?>;
                }

                body{
                    background-color: <?php echo get_theme_mod('site-background-color') ?>;
                }

                p, a{
                    color: <?php echo get_theme_mod('site-settings-font-color') ?>;
                    font-size: <?php echo get_theme_mod('site-settings-font-size') ?>px;
                    font-weight: <?php echo get_theme_mod('site-settings-font-weight') ?>;
                    line-height: <?php echo get_theme_mod('site-settings-font-height') ?>;
                }

                #hero{
                    background-color: <?php echo get_theme_mod('site-background-color') ?>;
                }

                #hero .content .title{
                    color: <?php echo get_theme_mod('front-page-title-color') ?>;
                    font-size:<?php echo get_theme_mod('front-page-title-size') ?>px;
                    line-height:  <?php echo get_theme_mod('front-page-title-line-height') ?>;
                    font-weight: <?php echo get_theme_mod('front-page-title-font-weight') ?>;
                    font-family: <?php echo get_theme_mod('front-page-title-font') ?>;
                }

                #hero .content .headline{
                    color: <?php echo get_theme_mod('front-page-headline-color') ?>;
                    font-size:<?php echo get_theme_mod('front-page-headline-size') ?>px;
                    line-height:  <?php echo get_theme_mod('front-page-headline-line-height') ?>;
                    font-weight: <?php echo get_theme_mod('front-page-headline-font-weight') ?>;
                    font-family: <?php echo get_theme_mod('front-page-headline-font') ?>;
                }

                #hero .content .subheadline{
                    color: <?php echo get_theme_mod('front-page-sub-headline-color') ?>;
                    font-size:<?php echo get_theme_mod('front-page-sub-headline-size') ?>px;
                    line-height:  <?php echo get_theme_mod('front-page-sub-healine-line-height') ?>;
                    font-weight: <?php echo get_theme_mod('front-page-sub-headline-font-weight') ?>;
                    font-family: <?php echo get_theme_mod('front-page-sub-headline-font') ?>;
                }

                

               
            </style>
        <?php
    }

    /*
    ==============================
        Utility Functions
    ==============================
    */

    public function text_sanitize($input){
        return sanitize_text_field($input);
    }

    public function color_sanitize($input){
        return sanitize_hex_color( $input );
    }

    private function custom_color_settings($wp_customize, $args){
        extract($args);

        $wp_customize->add_setting($setting, array(
            'default' => $default,
            'sanitize_callback' => $sanitize 
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $control, array(
            'label' => __($label, $this->theme),
            'section' => $section,
            'settings' => $setting
        )));

    }

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
            'options' => $options,
            'choices' => $choices,
        )));
    }

    
    
}



