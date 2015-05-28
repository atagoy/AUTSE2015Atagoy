<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class() ?>>
    <div id="root">
    	<div id="header">
            <div id="access" role="navigation">
                <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary', 'depth' => 1 ) ); ?>
            </div>
            <?php get_search_form() ?>
            <div id="heading">
                <?php if ('blank' != get_header_textcolor()) : ?>
                    <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
                    <div class="description"><?php bloginfo('description'); ?></div>
                <?php endif; ?>
            </div>
            <div id="about">
                
                <?php if (cloudy_theme_option('welcome_title')) : ?>
                    <strong><?php echo cloudy_theme_option('welcome_title'); ?></strong><br/>
                <?php endif; ?>
                <?php echo cloudy_theme_option('welcome_message'); ?>
                <p><?php if (cloudy_theme_option('welcome_author')) : ?></p>
                    <div class="alignright">
                        <em><?php echo cloudy_theme_option('welcome_author'); ?></em>
                    </div>
                <?php endif; ?>
            </div>
    	</div><!--#header-->
        <div id="main">
