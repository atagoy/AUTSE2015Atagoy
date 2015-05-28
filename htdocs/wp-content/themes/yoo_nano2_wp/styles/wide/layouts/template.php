<?php
/**
* @package   Nano2
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get template configuration
include($this['path']->path('layouts:template.config.php'));
	
?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>">

<head>
<?php echo $this['template']->render('head'); ?>
</head>

<body id="page" class="page <?php echo $this['config']->get('body_classes'); ?>" data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

	<?php if ($this['modules']->count('absolute')) : ?>
	<div id="absolute">
		<?php echo $this['modules']->render('absolute'); ?>
	</div>
	<?php endif; ?>
	
	<?php if ($this['modules']->count('toolbar-l + toolbar-r + search') || $this['config']->get('date')) : ?>
	<div id="block-toolbar"><div class="wrapper">

		<div id="toolbar" class="clearfix">

			<?php if ($this['modules']->count('toolbar-l') || $this['config']->get('date')) : ?>
			<div class="float-left">
			
				<?php if ($this['config']->get('date')) : ?>
				<time datetime="<?php echo $this['config']->get('datetime'); ?>"><?php echo $this['config']->get('actual_date'); ?></time>
				<?php endif; ?>
			
				<?php echo $this['modules']->render('toolbar-l'); ?>
				
			</div>
			<?php endif; ?>
				
			<?php if ($this['modules']->count('toolbar-r + search')) : ?>

				<?php if ($this['modules']->count('search')) : ?>
				<div id="search"><?php echo $this['modules']->render('search'); ?></div>
				<?php endif; ?>

				<?php if ($this['modules']->count('toolbar-r')) : ?>
				<div class="float-right"><?php echo $this['modules']->render('toolbar-r'); ?></div>
				<?php endif; ?>

			<?php endif; ?>
			
		</div>

	</div></div>
	<?php endif; ?>


	<div id="block-header"><div class="wrapper">

		<header id="header" class="clearfix">

			<?php if ($this['modules']->count('logo')) : ?>	
			<a id="logo" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['modules']->render('logo'); ?></a>
			<?php endif; ?>

			<?php if ($this['modules']->count('headerbar')) : ?>	
			<div id="headerbar" class="bfc-o"><?php echo $this['modules']->render('headerbar'); ?></div>
			<?php endif; ?>

			<?php if ($this['modules']->count('menu')) : ?>
			<nav id="menu"><?php echo $this['modules']->render('menu'); ?></nav>
			<?php endif; ?>
		
			<?php if ($this['modules']->count('banner')) : ?>
			<div id="banner"><?php echo $this['modules']->render('banner'); ?></div>
			<?php endif; ?>
		
		</header>

	</div></div>

	<div id="block-main"><div class="wrapper">

		<?php if ($this['modules']->count('top-a')) : ?>
		<section id="top-a" class="grid-block"><?php echo $this['modules']->render('top-a', array('layout'=>$this['config']->get('top-a'))); ?></section>
		<?php endif; ?>
		
		<?php if ($this['modules']->count('top-b')) : ?>
		<section id="top-b" class="grid-block"><?php echo $this['modules']->render('top-b', array('layout'=>$this['config']->get('top-b'))); ?></section>
		<?php endif; ?>
		
		<?php if ($this['modules']->count('innertop + innerbottom + sidebar-a + sidebar-b') || $this['config']->get('system_output')) : ?>
		<div id="main" class="grid-block">

			<div id="maininner" class="grid-box">

				<?php if ($this['modules']->count('innertop')) : ?>
				<section id="innertop" class="grid-block"><?php echo $this['modules']->render('innertop', array('layout'=>$this['config']->get('innertop'))); ?></section>
				<?php endif; ?>

				<?php if ($this['modules']->count('breadcrumbs')) : ?>
				<section id="breadcrumbs"><?php echo $this['modules']->render('breadcrumbs'); ?></section>
				<?php endif; ?>

				<?php if ($this['config']->get('system_output')) : ?>
				<section id="content" class="grid-block"><?php echo $this['template']->render('content'); ?></section>
				<?php endif; ?>

				<?php if ($this['modules']->count('innerbottom')) : ?>
				<section id="innerbottom" class="grid-block"><?php echo $this['modules']->render('innerbottom', array('layout'=>$this['config']->get('innerbottom'))); ?></section>
				<?php endif; ?>

			</div>
			<!-- maininner end -->
			
			<?php if ($this['modules']->count('sidebar-a')) : ?>
			<aside id="sidebar-a" class="grid-box"><?php echo $this['modules']->render('sidebar-a', array('layout'=>'stack')); ?></aside>
			<?php endif; ?>
			
			<?php if ($this['modules']->count('sidebar-b')) : ?>
			<aside id="sidebar-b" class="grid-box"><?php echo $this['modules']->render('sidebar-b', array('layout'=>'stack')); ?></aside>
			<?php endif; ?>

		</div>
		<?php endif; ?>
		<!-- main end -->

		<?php if ($this['modules']->count('bottom-a')) : ?>
		<section id="bottom-a" class="grid-block"><?php echo $this['modules']->render('bottom-a', array('layout'=>$this['config']->get('bottom-a'))); ?></section>
		<?php endif; ?>
		
	</div></div>

	<?php if ($this['modules']->count('bottom-b')) : ?>
	<div id="block-bottom"><div class="wrapper">

		<section id="bottom-b" class="grid-block"><?php echo $this['modules']->render('bottom-b', array('layout'=>$this['config']->get('bottom-b'))); ?></section>

	</div></div>
	<?php endif; ?>

	<?php if ($this['modules']->count('footer + debug') || $this['config']->get('warp_branding') || $this['config']->get('totop_scroller')) : ?>
	<div id="block-footer"><div class="wrapper">

		<footer id="footer">

			<?php if ($this['config']->get('totop_scroller')) : ?>
			<a id="totop-scroller" href="#page"></a>
			<?php endif; ?>

			<?php
				echo $this['modules']->render('footer');
				$this->output('warp_branding');
				echo $this['modules']->render('debug');
			?>

		</footer>
		
	</div></div>
	<?php endif; ?>
	
	<?php echo $this->render('footer'); ?>
	
</body>
</html>