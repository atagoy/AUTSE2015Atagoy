<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php global $gantry; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
<head>
	<?php
		$gantry->displayHead();
		$gantry->addStyles(array('template.css','wordpress.css','wp.css','style.css','typography.css'));
	?>
</head>
	<body id="rt-variation" <?php echo $gantry->displayBodyTag(); ?>>
		<?php /** Begin Drawer **/ if ($gantry->countModules('drawer')) : ?>
		<div id="rt-drawer">
			<div class="rt-container">
				<?php echo $gantry->displayModules('drawer','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Drawer **/ endif; ?>
		<div id="rt-main-header" <?php echo $gantry->displayClassesByTag('rt-main-header'); ?>><div id="rt-header-overlay" <?php echo $gantry->displayClassesByTag('rt-header-overlay'); ?>><div id="rt-main-header2"><div id="rt-header-graphic" <?php echo $gantry->displayClassesByTag('rt-header-graphic'); ?>>
			<?php /** Begin Top **/ if ($gantry->countModules('top')) : ?>
			<div id="rt-top">
				<div class="rt-container">
					<?php echo $gantry->displayModules('top','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Top **/ endif; ?>
			<?php /** Begin Header **/ if ($gantry->countModules('header')) : ?>
			<div id="rt-header">
				<div class="rt-container">
					<div class="shadow-left"><div class="shadow-right"><div class="shadow-bottom">
						<?php echo $gantry->displayModules('header','standard','standard'); ?>
						<div class="clear"></div>
					</div></div></div>
				</div>
			</div>
			<?php /** End Header **/ endif; ?>
			<?php /** Begin Menu **/ if ($gantry->countModules('navigation')) : ?>
			<div id="rt-navigation"><div id="rt-navigation2"><div id="rt-navigation3">
				<div class="rt-container">
					<div class="shadow-left"><div class="shadow-right">
						<?php echo $gantry->displayModules('navigation','basic','basic'); ?>
					    <div class="clear"></div>
					</div></div>
				</div>
			</div></div></div>
			<?php /** End Menu **/ endif; ?>
			<?php /** Begin Showcase **/ if (true or $gantry->countModules('showcase')) : ?>
			<div id="rt-showcase"><div id="rt-showcase2">
				<div class="rt-container">
					<div class="shadow-left"><div class="shadow-right"><div class="shadow-bl"><div class="shadow-br">
						<?php echo $gantry->displayModules('showcase','standard','showcase'); ?>
						<div class="clear"></div>
					</div></div></div></div>
				</div>
			</div></div>
			<?php /** End Showcase **/ endif; ?>
		</div></div></div></div>
		<div id="rt-main-surround"><div id="rt-main-overlay" <?php echo $gantry->displayClassesByTag('rt-main-overlay'); ?>><div id="rt-main-surround2" <?php echo $gantry->displayClassesByTag('rt-main-surround'); ?>>
			<div class="rt-container">
				<div class="shadow-left"><div class="shadow-right">
					<?php /** Begin Utility **/ if ($gantry->countModules('utility')) : ?>
					<div id="rt-utility" <?php echo $gantry->displayClassesByTag('rt-feature'); ?>><div id="rt-utility2"><div id="rt-utility3">
						<?php echo $gantry->displayModules('utility','standard','basic'); ?>
						<div class="clear"></div>
					</div></div></div>
					<?php /** End Utility **/ endif; ?>
					<?php /** Begin Feature **/ if ($gantry->countModules('feature')) : ?>
					<div id="rt-feature" <?php echo $gantry->displayClassesByTag('rt-feature'); ?>><div id="rt-feature-overlay" <?php echo $gantry->displayClassesByTag('rt-feature-overlay'); ?>><div id="rt-feature2">
						<?php echo $gantry->displayModules('feature','standard','feature'); ?>
						<div class="clear"></div>
					</div></div></div>
					<?php /** End Feature **/ endif; ?>
					<div id="rt-mainbody-bg" <?php echo $gantry->displayClassesByTag('rt-mainbody-bg'); ?>><div id="rt-body-overlay" <?php echo $gantry->displayClassesByTag('rt-body-overlay'); ?>>
						<div id="rt-mainbody-shadow">
							<div id="body-inner-l"><div id="body-inner-r">
								<?php /** Begin Breadcrumbs **/ if ($gantry->countModules('breadcrumb')) : ?>
								<div id="rt-breadcrumbs">
									<?php echo $gantry->displayModules('breadcrumb','basic','breadcrumbs'); ?>
									<div class="clear"></div>
								</div>
								<?php /** End Breadcrumbs **/ endif; ?>
								<?php /** Begin Main Top **/ if ($gantry->countModules('maintop')) : ?>
								<div id="rt-maintop">
									<?php echo $gantry->displayModules('maintop','standard','standard'); ?>
									<div class="clear"></div>
								</div>
								<?php /** End Main Top **/ endif; ?>