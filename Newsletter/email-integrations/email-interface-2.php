<?php

if (is_object($email) !== TRUE){
    $email = new EmailInterface2();
}

class EmailInterface2{
    /**
     * This class handles the public facing interface for the newsletter sign up. 
     * all of the email integrations will be instantiated within this class.
     */

     /*============= Properties =============*/
        private $integrations = array('Mailchimp');


     /*============= Constructor =============*/
        function __construct(){
            add_action("admin_menu", array($this, "make_admin_pages"));
        }

    /*============= Interface Functions =============*/
    public function get_email_auth_link(){
        //this function returns authorization link from active email integration
    }

    public function check_api_key(){
        //this function returns true or false based on if the API kep for the active intergration is valid
    }

    public function get_email_lists(){
        //this function returns a list of email lists from the active email integration
    }

    public function add_subscriber(){
        //this function calls the add subscriber method of the active email integration
    }
     
     
    /*============= Admin Page Functions =============*/
        public function make_admin_pages(){
            //generate Email Settings page
            add_menu_page('Email Settings', "email-settings", "manage_options", "email_settings", array($this, "email_settings_create_page"), 'dashicons-email-alt2', 110);

            //activate custom settigns
            add_action("admin_init", array($this, "email_custom_settings"));
        }

        public function email_settings_create_page(){
            echo "<form action='options.php' method='post'>";  
            
            settings_fields("email-settings-group"); 
            do_settings_sections('email_settings');
            submit_button(); 

            echo "</form>";
        }

        public function email_custom_settings(){
            //register settings
            register_setting("email-settings-group", "esp_list");
            register_setting("email-settings-group", "auth_code");
            register_setting("email-settings-group", "active_email");
            register_setting("email-settings-group", "thank_you_page");
            register_setting("email-settings-group", "error_message");
            register_setting("email-settings-group", "already_subscribed_message");

            add_settings_section('email-options-section', 'Email Settings', array($this, 'email_settings_options'), 'email_settings');
    

            //regsiter setting fields
            add_settings_field('email-esp-list', 'Email Services', array($this, 'esp_list_callback'), 'email_settings', 'email-options-section');
            add_settings_field('email-auth-code', 'Authorization Code', array($this, 'auth_code_callback'), 'email_settings', 'email-options-section');
            add_settings_field('email-email-list', 'Email Lists', array($this, 'email_list_dropdown_callback'), 'email_settings', 'email-options-section');
            add_settings_field('email-thank-you-page', 'Thank You page', array($this, 'thank_you_page_callback'), 'email_settings', 'email-options-section');
            add_settings_field('email-error-message', 'Error Message', array($this, 'error_message_callback'), 'email_settings', 'email-options-section');
            add_settings_field('email-already-subscribed-message', 'Already Subscribed Message', array($this, 'already_subscribed_message_callback'), 'email_settings', 'email-options-section');
        }

        public function email_settings_options(){
            echo "<p>Customize Your Email Integration</p>";
        }

        //email settigns fields callbacks
        public function esp_list_callback(){
            echo "Select Your Email Service Provider<br>";
            $esp = $this->integrations;
            $active = get_option('esp_list');
            $dropdown = "<select name='esp_list' id='esp_list'><option value=''></option>";
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

        public function auth_code_callback(){
            //need an email->authlink for this  
            $code = esc_attr(get_option('auth_code'));
            echo "Enter Your Email Authorization Code<br>";
            echo "<a href='' target='_blank'>Click Here To Get An Authorization Code</a><br><br>";
            echo "<input type='text' id='auth_code' name='auth_code' value='$code' />";
        }

        public function email_list_dropdown_callback(){
            echo "Please choose an email list.<br>";
            $lists = array('list1', 'list2', 'list3'); //need to add a check for valid access token and retrieve these from the email class
            $active = get_option('active_email');
            $dropdown = "<select name='active_email' id='active_email'><option value=''></option>";
            foreach($lists as $l){
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

        public function thank_you_page_callback(){
            echo "Please select a thank you pagge.<br>";
            $active = get_option("thank_you_page");
            $dropdown = "<select name='thank_you_page' id='thank_you_page'><option value=''></option>";
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

        public function error_message_callback(){
            echo "Please enter an error message.<br>";
            $message = get_option('error_message');
            echo "<textarea name='error_message' id='error_message' cols='100' rows='5'>" . $message . "</textarea>";
        }

        public function already_subscribed_message_callback(){
            echo "Please enter an already subscribed message.<br>";
            $message = get_option('already_subscribed_message');
            echo "<textarea name='error_message' id='error_message' cols='100' rows='5'>" . $message . "</textarea>";
        }

     /*============= Private Functions =============*/
}