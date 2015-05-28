<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
$gantry->init();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
    <head>
        <?php
            $gantry->displayHead();
            $gantry->addStyles(array('template.css','wordpress.css','wp.css','typography.css','iphone-gantry.css'));
			$gantry->addScript('iscroll.js');
        ?>
			<?php
				$scalable = $gantry->get('iphone-scalable', 0) == "0" ? "0" : "1";
			?>
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=<?php echo $scalable; ?>;">

			<script type="text/javascript">
				var orient = function() {
					var dir = "rt-normal";
					switch(window.orientation) {
						case 0: dir = "rt-normal";break;
						case -90: dir = "rt-right";break;
						case 90: dir = "rt-left";break;
						case 180: dir = "rt-flipped";break;
					}
					$$(document.body, '#rt-wrapper')
						.removeClass('rt-normal')
						.removeClass('rt-left')
						.removeClass('rt-right')
						.removeClass('rt-flipped')
						.addClass(dir);
				}

				window.addEvent('domready', function() {
					orient();
					window.scrollTo(0, 1);
					new iScroll($$('#rt-menu ul.menu')[0]);
				});

			</script>
    </head>
    <body <?php echo $gantry->displayBodyTag(); ?> onorientationchange="orient()">
		<div id="rt-wrapper">
		    <?php /** Begin Drawer **/ if ($gantry->countModules('mobile-drawer')) : ?>
	        <div id="rt-drawer">
	            <div class="rt-container">
	                <?php echo $gantry->displayModules('mobile-drawer','standard','standard'); ?>
	                <div class="clear"></div>
	            </div>
	        </div>
	        <?php /** End Drawer **/ endif; ?>
			<div id="rt-main-header" <?php echo $gantry->displayClassesByTag('rt-main-header'); ?>><div id="rt-header-overlay" <?php echo $gantry->displayClassesByTag('rt-header-overlay'); ?>><div id="rt-main-header2"><div id="rt-header-graphic" <?php echo $gantry->displayClassesByTag('rt-header-graphic'); ?>>
			<?php /** Begin Top **/ if ($gantry->countModules('mobile-top')) : ?>
			<div id="rt-top" <?php echo $gantry->displayClassesByTag('rt-top'); ?>>
				<div class="rt-container">
					<?php echo $gantry->displayModules('mobile-top','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Top **/ endif; ?>
			<?php /** Begin Header **/ if ($gantry->countModules('mobile-header')) : ?>
			<div id="rt-header">
				<div class="rt-container">
					<?php echo $gantry->displayModules('mobile-header','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Header **/ endif; ?>
			<?php /** Begin Menu **/ if ($gantry->countModules('mobile-navigation')) : ?>
			<div id="rt-menu">
				<div class="rt-container">
					<div id="rt-left-menu"></div>
					<div id="rt-right-menu"></div>
					<?php echo $gantry->displayModules('mobile-navigation','basic','basic'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Menu **/ endif; ?>
			<?php /** Begin Showcase **/ if ($gantry->countModules('mobile-showcase')) : ?>
			<div id="rt-showcase">
				<div class="rt-container">
					<?php echo $gantry->displayModules('mobile-showcase','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			</div></div></div></div>
			<?php /** End Showcase **/ endif; ?>
			<?php /** Begin Main Body **/ ?>
			<div id="rt-main-surround"><div id="rt-main-overlay" <?php echo $gantry->displayClassesByTag('rt-main-overlay'); ?>><div id="rt-main-surround2" <?php echo $gantry->displayClassesByTag('rt-main-surround'); ?>>
				<div id="rt-mainbody-bg" <?php echo $gantry->displayClassesByTag('rt-mainbody-bg'); ?>><div id="rt-body-overlay" <?php echo $gantry->displayClassesByTag('rt-body-overlay'); ?>>
					<div id="rt-mainbody-shadow">
						<div id="body-inner-l"><div id="body-inner-r">
		    				<?php echo $gantry->displayMainbody('iphonemainbody','sidebar','standard','standard','standard','standard','standard'); ?>
						</div>
					</div>
				</div></div>
			</div></div></div>
			<?php /** End Main Body **/ ?>
			<?php /** Begin Footer Wrap **/ if ($gantry->countModules('footer') or $gantry->countModules('copyright') or $gantry->countModules('debug')) : ?>
			<div id="rt-main-footer" <?php echo $gantry->displayClassesByTag('rt-bottom'); ?>><div id="rt-footer-overlay" <?php echo $gantry->displayClassesByTag('rt-bottom-overlay'); ?>><div id="rt-main-footer2">
			<?php /** Begin Footer **/ if ($gantry->countModules('mobile-footer')) : ?>
			<div id="rt-footer">
				<div class="rt-container">
					<?php echo $gantry->displayModules('mobile-footer','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Footer **/ endif; ?>
			<?php /** Begin Copyright **/ if ($gantry->countModules('mobile-copyright')) : ?>
			<div id="rt-copyright">
				<div class="rt-container">
					<?php echo $gantry->displayModules('mobile-copyright','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Copyright **/ endif; ?>
			<?php /** Begin Debug **/ if ($gantry->countModules('debug')) : ?>
			<div id="rt-debug">
				<div class="rt-container">
					<?php echo $gantry->displayModules('debug','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php /** End Debug **/ endif; ?>
			</div></div></div>
			<?php endif; ?>
			<?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
			<?php echo $gantry->displayModules('analytics','basic','basic'); ?>
			<?php /** End Analytics **/ endif; ?>
		</div>
		<?php $gantry->displayFooter();?>
	</body>
</html>
<?php
$gantry->finalize();
?>