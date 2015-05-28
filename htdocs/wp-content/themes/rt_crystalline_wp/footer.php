<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
?>
								<?php global $gantry; ?>
								<?php /** Begin Main Bottom **/ if ($gantry->countModules('mainbottom')) : ?>
								<div id="rt-mainbottom">
									<?php echo $gantry->displayModules('mainbottom','standard','standard'); ?>
									<div class="clear"></div>
								</div>
								<?php /** End Main Bottom **/ endif; ?>
							</div></div>
						</div>
					</div></div>
				</div></div>
			</div>
		</div></div></div>
		<?php /** Begin Bottom **/ if ($gantry->countModules('bottom')) : ?>
		<div id="rt-bottom" <?php echo $gantry->displayClassesByTag('rt-bottom'); ?>><div id="rt-bottom-overlay" <?php echo $gantry->displayClassesByTag('rt-bottom-overlay'); ?>><div id="rt-bottom2">
			<div class="rt-container">
				<div class="shadow-left"><div class="shadow-right">
					<?php echo $gantry->displayModules('bottom','standard','standard'); ?>
					<div class="clear"></div>
				</div></div>
			</div>
		</div></div></div>
		<?php /** End Bottom **/ endif; ?>
		<?php /** Begin Footer Wrap **/ if ($gantry->countModules('footer') or $gantry->countModules('copyright') or $gantry->countModules('debug')) : ?>
		<div id="rt-main-footer" <?php echo $gantry->displayClassesByTag('rt-bottom'); ?>><div id="rt-footer-overlay" <?php echo $gantry->displayClassesByTag('rt-bottom-overlay'); ?>><div id="rt-main-footer2">
			<?php /** Begin Footer **/ if ($gantry->countModules('footer')) : ?>
			<div id="rt-footer"><div id="rt-footer2">
				<div class="rt-container">
					<?php echo $gantry->displayModules('footer','standard','standard'); ?>
					<div class="clear"></div>
				</div>
			</div></div>
			<?php /** End Footer **/ endif; ?>
			<?php /** Begin Copyright **/ if ($gantry->countModules('copyright')) : ?>
			<div id="rt-copyright">
				<div class="rt-container">
					<?php echo $gantry->displayModules('copyright','standard','standard'); ?>
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
			<?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
        	<?php echo $gantry->displayModules('analytics','basic','basic'); ?>
        	<?php /** End Analytics **/ endif; ?>
		</div></div></div>
		<?php endif; ?>
		<?php /** Begin Popup **/
		echo $gantry->displayModules('popup','popup','popup');
		/** End Popup **/ ?>
		<?php $gantry->displayFooter(); ?>
	</body>
</html>
<?php
$gantry->finalize();
?>