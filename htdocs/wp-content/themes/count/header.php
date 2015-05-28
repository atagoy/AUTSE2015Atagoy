<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> 
<link rel="shortcut icon" href="http://www.themecss.com/img/favicon.ico" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Архив сайта <?php } ?> <?php wp_title(); ?></title>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); wp_head(); ?>
</head>
<body>

<?php include (TEMPLATEPATH . "/includes/retrieve-options.php"); ?>

<div class="container">
	<div class="logo-menu clear">		
        <div class="logo">
			<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('title'); ?>">
			<?php
				if($wp_logo){
					?>
						<img src="<?php bloginfo('template_url')?>/<?php echo $wp_logo; ?>" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" />
					<?php
				}else {
					?>
						<img src="<?php bloginfo('template_url')?>/img/logo.png" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" />
					<?php
				}
			?>  
			</a>			
        </div>
        <div class="menu">
            <ul>
                <li <?php if( is_home() ) : ?> class="current_page_item page_item" <?php else : ?> class="page_item" <? endif; ?> >
                    <a href="<?php bloginfo('url'); ?>" title="Главная">Главная</a>
                </li>
                <?php wp_list_pages('title_li=&depth=1'); ?>     			
            </ul>		
        </div>
	</div>
