<h1>Newsletter Settings</h1>
<?php settings_errors(); ?>
<form action="options.php" method="post">
    <?php 
    
    settings_fields("analytics-settings-group"); 
    do_settings_sections('analytics_settings');
    
   

    submit_button(); ?>
</form>