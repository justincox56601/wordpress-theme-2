<?php
get_header();
if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); ?>
    <div class="section">
        <?php
        the_title("<h2 class='title'>", '</h2>');
        the_post_thumbnail('', array('class'=>'image'));
        the_content();?>
    </div> 
        
    <?php endwhile; 
endif;

 get_footer(); ?>