<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title><?php 
	 	$replacethese = array('[',']');
		$replacewith = array(' ',' ');
		echo str_replace($replacethese, $replacewith, get_bloginfo('title')); ?>

	
	
	<?php if ( !(is_404()) && (is_single()) or (is_page()) or (is_archive()) ) { ?> &raquo; <?php wp_title(''); ?><?php } ?></title>

	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
	
	
<?php $url = get_stylesheet_directory_uri()?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>/imagemenu/imageMenu.css">
<script type="text/javascript" src="<?php echo $url; ?>/imagemenu/mootools.js"></script>
<script type="text/javascript" src="<?php echo $url; ?>/imagemenu/imageMenu.js"></script>


	
</head>

<body>

<div id="wrapper">

<div id="welcomeheading">
<h1><a href="<?php bloginfo('url'); ?>/">

<?php 	$replacethese = array('[',']');
		$replacewith = array('<span id="middleword">','</span>');
		echo str_replace($replacethese, $replacewith, get_bloginfo('title')); ?>
		</a></h1>
		<div id="description"><?php bloginfo('description'); ?></div>
</div>
	

<div id="imageMenu">
			<ul>
				<?php slider_menuparse(wp_list_pages('sort_column=ID&depth=1&number=7&title_li=&echo=0')); ?>
			</ul>
</div>  <!-- END imagemenu -->
		

		
<script type="text/javascript">
			
			window.addEvent('domready', function(){
				var myMenu = new ImageMenu($$('#imageMenu a'),{openWidth:300, border:2, onOpen:function(e,i){location=(e);}});
			});
		</script>	

		
