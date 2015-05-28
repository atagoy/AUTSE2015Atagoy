<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>
<?php bloginfo('name'); ?>
<?php if(is_home()) { ?>
 - <?php bloginfo('description'); ?>
<?php } ?>
<?php if(is_single()) { ?>
<?php wp_title(); ?>
<?php } ?>
<?php if(is_404()) { ?>
 - Страница не найдена
<?php } ?>
<?php if(is_search()) { ?>
 - Результаты поиска: <?php echo wp_specialchars($s, 1); ?>
<?php } ?>
</title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<script src="<?php bloginfo('template_directory'); ?>/js/tabs.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/heightMatch.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/topmenudynamic.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/dropdown.js" type="text/javascript"></script>

<?php wp_head(); ?>

</head>
<body>

<div id="container">

<!-- begin page nav -->
<div id="top">

	<div id="topleft">
	<ul id="pagenavigation">
	<li<?php if(!is_page() ) { ?> class="current_page_item"<?php } ?>><a href="<?php bloginfo('home'); ?>">Главная</a></li>
	<?php wp_list_pages('sort_column=post_date&title_li='); ?>
	</ul>
	</div>
	
	<div id="topright">
	<form class="searchform" method="get" action="<?php bloginfo('url'); ?>/">
	<fieldset>
	<label>Поиск в заметках:</label>
	<input type="text" value="<?php the_search_query(); ?>" name="s" class="searchinput" />
	<input type="submit" value="Go" class="searchbutton" />
	</fieldset>
	</form>
	</div>
	
	<div class="clear"></div>
	
</div>
<!-- end -->

<!-- begin header -->
<div id="header">

	<div id="headerlogo">
	<h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
	<span><?php bloginfo('description'); ?></span>
	</div>
	
	<div id="headerad"><?php include (TEMPLATEPATH . "/468_60_ads.php"); ?></div>
	
	<div class="clear"></div>
	
</div>
<!-- end -->

<!-- begin cat navigation -->
<div id="menu">
	
	<div id="menux">
	<ul id="dmenu">
	<?php global $video; ?>
	<?php wp_list_categories('hide_empty=0&title_li=&exclude='.$video); ?>
	</ul>
	</div>
	
	<div id="menuy"></div>
	
	<div class="clear"></div>
	
</div>
<!-- end -->

<!-- begin info bar -->
<div id="rssbar">

	<div id="today"><script src="<?php bloginfo('template_url'); ?>/js/date.js" type="text/javascript"></script></div>
	
	<ul>
	<li><a href="<?php bloginfo('rss2_url'); ?>">Публикации RSS</a></li>
	<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Комментарии RSS</a></li>
	</ul>
	
	<div class="clear"></div>

</div>
<!-- end -->