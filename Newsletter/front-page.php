<?php
   if(get_theme_mod('show-title') == "Logo"){
       $logo = "<div class='logo'>
                <img src=" . wp_get_attachment_url(get_theme_mod('front-page-logo')) ." alt='logo'>
                </div>";
   }else{
       $logo = "<h2 class='title'>" . get_theme_mod('front-page-title') . "</h2>";
   }

get_header('front-page');?>

<div id="hero">
    <div class="content">
        <div class="container">
            <?php echo $logo ?>
            <h1 class="headline"><?php echo get_theme_mod('front-page-headline') ?></h1>
            <p class="subheadline"><?php echo get_theme_mod('front-page-sub-headline') ?></p>
            <form action="" id='form' data-url='<?php echo admin_url('admin-ajax.php')?>'>
                <input type="email" name="email" id="email">
                <input type="submit" value="<?php echo get_theme_mod('front-page-submit-text') ?>">
                <div id="response"></div>
            </form>
        </div>
        <div class="image">
            <img src="<?php echo wp_get_attachment_url(get_theme_mod('front-page-featured-image')) ?>" alt="">
        </div>
        
    </div>
    
    <div class="optout">
       <p class="small">
        If you don't like it, unsubscribe at any time. Seriously, no hard feelings.<br> <a href="<?php echo get_permalink(get_theme_mod('front-page-terms-page')) ?>" class="highlight small" target='_blank'>Terms and Privacy Policy</a>.
       </p> 
        
    </div>
    <div class="powered-by">
        <p class='small'>Powered By: <a href="http://justincox.tech" target="_blank" rel="noopener noreferrer" class='small'>Justin Cox</a></p>
    </div>
</div>





<?php get_footer('front-page');?>