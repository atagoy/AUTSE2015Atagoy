<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="stylesheet" type="text/css" href="style.css">
<?php wp_head(); ?>
</head>
<body>
<div id="titlewrapper">
  <div id="title"><h1><?php bloginfo('name'); ?> <?php // if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Logo')) : ?><?php // endif; ?></h1></div>
  <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Top Caption')) : ?><?php endif; ?>
</div>

<div id="menuwrapper"><?php include (TEMPLATEPATH . '/menu.php'); ?>  <?php // if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Menu')) : ?><?php // endif; ?></div>
<div id="ctop"></div>
	<div id="cbg">
    	<div id="ctop2">
    	<div id="header" class="clearfix">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" id="columns">
  <tr>
    <td id="headercaption"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Caption')) : ?><?php endif; ?></td>
    <td id="headermedia"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Media')) : ?><?php endif; ?></td>
  </tr>
</table>
		</div> 	
		<?php include (TEMPLATEPATH . '/breadcrumbs.php'); ?>
		<!-- content area -->
