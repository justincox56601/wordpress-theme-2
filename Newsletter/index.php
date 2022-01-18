<?php
get_header();
if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); 
    $link = get_the_permalink();?>
    <div class="archive-section">
        <?php
        the_title("<h2 class='title'><a href='$link' >", '</a></h2>');
        echo "<a href='$link' >";
        the_post_thumbnail('', array('class'=>'image'));
        echo "</a>";
        the_excerpt();?>
    </div> 
        
    <?php endwhile; 
endif;

 get_footer(); ?>