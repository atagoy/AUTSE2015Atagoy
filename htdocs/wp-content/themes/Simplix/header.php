<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="template" content="Simplix <?php if (function_exists('ihinfo')) { ihinfo('version'); } ?>" />

<?php if (get_settings('t_meta_desc') != '') { ?>
<meta name="description" content="<?php echo get_settings('t_meta_desc'); ?>" />
<?php } else { ?>
<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php } ?>

<?php wp_head(); ?>

<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

<?php $t_favico = get_settings( "t_favico" ); 
		if( $t_favico != "" ) { 
		?>
<link rel="icon" href="<?php bloginfo('template_url'); ?>/images/icons/<?php echo $t_favico; ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/icons/<?php echo $t_favico; ?>" />
<?php	} ?>


<?php $t_css = get_settings('t_css'); 
	if($t_css != '' ){ ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/<?php echo $t_css; ?>" type="text/css" media="screen" />
 <?php } else { ?>
 <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css" type="text/css" media="screen" />
 <?php } ?>
 
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/mootools_002.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/flashstockslidestrip.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/newsrotator.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/slider.js"></script>



</head>

<body>
	<div class="page_block">
		<div class="main_block">
		
			<div class="top_block">
				<?php $t_logo = get_settings( "t_logo" ); 
					if( $t_logo != "" ) { 
				?>
				<div id="logo">
					<a href="<?php bloginfo('url'); ?>"><img alt="<?php bloginfo('name'); ?>" src="<?php bloginfo('template_url'); ?>/images/logo/<?php echo $t_logo; ?>" border="0" /></a>
				</div>
				<?php } ?>	
				<div id="top-page-navi">
					<ul>
						<li <?php if(is_home()){?> class="current_page_item"<?php } ?>><a href="<?php bloginfo('url'); ?>"><span>Home</span></a></li>
						<?php t_show_pagemenu(); ?>
						 
					</ul>
				</div>
			</div>
			
			<div class="content_block">
				<div class="left_bottom_bg">