<?php
    if(get_theme_mod('number-menus')==2){
        $p_align = 'left-align';
        $s_align = 'right-align';
    }else{
        $p_align = get_theme_mod('menu-alignment');
        $s_align = 'no-show';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name') . " " . wp_title()?></title>
    <?php wp_head()?>
</head>
<body>
    <div class='hamburger-menu'><?php echo SvgIcon::get_svg('menu', 25) ?>MENU</div>
    <nav class="<?php $navClass?>">
        <?php wp_nav_menu(array(
            'menu' => 'primary',
            'menu_class' => '',
            'container_class' => $p_align,
            'theme_location' => 'primary'
        ));?>
        <?php if(get_theme_mod('nav-show-logo')){ ?>
            <img src="<?php echo wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'); ?>" alt="">
        <?php } ?>
        
        <?php wp_nav_menu(array(
            'menu' => 'secondary',
            'menu_class' => '',
            'container_class' => $s_align,
            'theme_location' => 'secondary'
        ));?>
    </nav>
    <header>

    </header>
