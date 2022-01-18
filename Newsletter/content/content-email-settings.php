<?php $email = new EmailInterface(); ?>
<h1>Newsletter Settings</h1>
<?php settings_errors(); ?>
<form action="options.php" method="post"> 
    <?php 
    
    settings_fields("newsletter-settings-group"); 
    do_settings_sections('newsletter_settings');
    
   

    submit_button(); ?>
</form>
