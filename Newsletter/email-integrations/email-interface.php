<?php

include get_template_directory() . "/email-integrations/mailchimp-integration.php";
//include get_template_directory() . "/email-integrations/aweber-integration.php";
/*
=================================
    Outside of Class Funcitons
=================================
*/


function newsletter_add_admin_page(){
    //generate Newsletter Settings page
    add_menu_page('Newsletter Settings', "Newsletter", "manage_options", "newsletter_settings", "newsletter_settings_create_page", 'dashicons-email-alt2', 110);

    //generate submen pages
    add_submenu_page("newsletter_settings", 'Newsletter Settings', "Email Settings", 'manage_options', 'newsletter_settings', 'newsletter_settings_create_page');

    //activate custom settigns
    add_action("admin_init", "newsletter_custom_settings");
}
add_action("admin_menu", "newsletter_add_admin_page");

function newsletter_settings_create_page(){
    //generates the admin page for the email interface.
    require_once get_template_directory() . "/content/content-email-settings.php";
    
}

//function newsletter_settings_sub_page(){}

function newsletter_custom_settings(){
    $email = new emailInterface();
    //$email->email->wipe_data();
    $args = array(
        "obj" => $email
    );
    
    //register settings
    register_setting("newsletter-settings-group", "esp_list");
    register_setting("newsletter-settings-group", "auth_code");
    register_setting("newsletter-settings-group", "active_email");
    register_setting("newsletter-settings-group", "thank_you_page");
    register_setting("newsletter-settings-group", "error_message");
    register_setting("newsletter-settings-group", "already_subscribed_message");



    //regsister setting sections
    add_settings_section('newsletter-email-options', 'Email Settings', 'newsletter_email_options', 'newsletter_settings');
    

    //regsiter setting fields
    add_settings_field('newsletter-esp-list', 'Email Services', 'newsletter_esp_list', 'newsletter_settings', 'newsletter-email-options');
    add_settings_field('newsletter-auth-code', 'Authorization Code', 'newsletter_auth_code', 'newsletter_settings', 'newsletter-email-options', $args);
    add_settings_field('newsletter-email-list', 'Email Lists', 'newsletter_email_list_dropdown', 'newsletter_settings', 'newsletter-email-options', $args);
    add_settings_field('newsletter-thank-you-page', 'Thank You page', 'newsletter_thank_you_page', 'newsletter_settings', 'newsletter-email-options');
    add_settings_field('newsletter-error-message', 'Error Message', 'newsletter_error_message', 'newsletter_settings', 'newsletter-email-options');
    add_settings_field('newsletter-already-subscribed-message', 'Already Subscribed Message', 'newsletter_subscribed_message', 'newsletter_settings', 'newsletter-email-options');

    
}

function newsletter_email_options(){
    echo "<p>Customize Your Email Integration</p>";
}

function newsletter_auth_code_options(){}

function newsletter_email_lists(){}

function newsletter_messages(){}

function newsletter_esp_list(){
    echo "Select Your Email Service Provider<br>";
    $esp = array(
        "Mailchimp", //"Aweber" 
    );
    $active = get_option('esp_list');
    $dropdown = "<select name='esp_list' id='esp_list'>";
    foreach($esp as $email){
        if($email == $active){
            $selected = "selected";
        }else{
            $selected = "";
        }
        
        $dropdown .= "<option value='" . $email. "' $selected>$email</option>";
    }
    $dropdown .= "</select><br>";
    echo $dropdown;
     
}

function newsletter_auth_code($args){
    $email = $args['obj'];
    $code = get_option('auth_code');
    //echo "<a href='" .$email->get_auth_link()."' target='_blank'>Click Here To Get An Authorization Code</a><br><br>";
    if($email->check_access_token()){
        echo "You are all set.<br>";
        
    }else{
        if(get_option('auth_code')){
            $email->get_access_token($code);
            update_option('auth_code', "");
        }
        echo "<a href='" .$email->get_auth_link()."' target='_blank'>Click Here To Get An Authorization Code</a><br><br>";
        $code = esc_attr(get_option('auth_code'));
        echo "<input type='text' id='auth_code' name='auth_code' value='$code' />";
    }
    
}

function newsletter_email_list_dropdown($args){
    echo "Please choose an email list.<br>";
    $email = $args['obj'];
    $active = get_option('active_email');
    $dropdown = "<select name='active_email' id='active_email'>";
    if($email->email->lists){
        $list = $email->email->lists;
        foreach($list as $l){
            if($l->id == $active){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $dropdown .= "<option value='" . $l->id. "' $selected>". $l->name ."</option>";
        }
        $dropdown .= "</select><br>";
        echo $dropdown;
    }
}

function newsletter_thank_you_page(){
    echo "Please select a thank you pagge.<br>";
    $active = get_option("thank_you_page");
    $dropdown = "<select name='thank_you_page' id='thank_you_page'><option value=''>";
    $pages = get_pages();
    foreach($pages as $p){
        $link = get_page_link($p->ID);
        if($link == $active){
            $selected = "selected";
        }else{
            $selected = "";
        }
        $dropdown .= "<option value='$link' $selected >" . $p->post_title . "</option>";
    }
    $dropdown .= "</select>";
    echo $dropdown;
    
    
}

function newsletter_error_message(){
    echo "Please enter an eror message.<br>";
    $message = get_option('error_message');
    echo "<textarea name='error_message' id='error_message' cols='100' rows='5'>" . $message . "</textarea>";
}

function newsletter_subscribed_message(){
    echo "Please enter an already subscribed message.<br>";
    $message = get_option('already_subscribed_message');
    echo "<textarea name='already_subscribed_message' id='already_subscribed_message' cols='100' rows='5'>" . $message . "</textarea>";
}

/*
=================================
    Ajax Functions
=================================
*/

function email_integration(){
    $email = new emailInterface();
    if($_POST['email']){
        $args = array(
            "email" => sanitize_email($_POST["email"]),
            "status" => "subscribed"
        );
        $resp = $email->add_new_subscriber($args);
        echo $resp;
    }else{
        echo "I didn't get any info";
    }
    die();
}
add_action( 'wp_ajax_email_integration', 'email_integration' );
add_action( 'wp_ajax_nopriv_email_integration', 'email_integration' );

/*
=================================
    Class Definition
=================================
*/

class EmailInterface {
    /*
    This class is the interface between the user and the different email integrations
    */

    //properties
    public $availableIntegrations;
    public $activeIntegration;
    public $email;  //this will be an instance of the active email object


    //construct
    function __construct(){
        
        
        $this->activeIntegration = get_option('esp_list');
        if($this->activeIntegration){
            switch ($this->activeIntegration) {
            case 'Mailchimp':
                $this->email = new Mailchimp();
                break;

            case 'Aweber':
                $this->email = new Aweber();
                break;
            
            default:
                # code...
                break;
        }
        }
        
        

        //if(saved-data){load_data;}
        //get list of available integrations.
        
        //make instance of the slected email integration
    }

    //load and save
    public function save_email_interface(){}

    public function load_email_interface(){}

    //Interface Methods
    public function check_access_token(){
        //returns true or false depending on if the $this->email has a valid acccess token
        return $this->email->check_access_token();
    }
    
    public function get_auth_link(){
        return $this->email->get_auth_link();
        
    }

    public function get_access_token($code){
        echo $this->email->get_access_token($code);
    }

    public function get_email_lists(){
        echo $this->email->get_email_lists();
    }

    public function add_new_subscriber($args){
        $resp =  json_decode($this->email->add_new_subscriber($args));

        if($resp->status == "subscribed"){
            return "go-to:". get_option("thank_you_page");
        }else if($resp->status == 400){
            return get_option("already_subscribed_message");
        }else{
            return get_option("error_message") . "<br><br>" . print_r($resp);
        }
    }

    //Admin Page Methods
    public function admin_page(){
        $resp;
    }

    public function drop_down(){
        return "<select name='esp_list' id='esp_list'>
            <option value='Mailchimp'>Mailchimp</option>
            <option value='Aweber'>Aweber</option>
            <option value='Constant Contact'>Constant Contact</option>
          </select><br>The active option is: " . get_option("esp_list") . "<br>";
    }

    //Member Methods
    
    
    
}