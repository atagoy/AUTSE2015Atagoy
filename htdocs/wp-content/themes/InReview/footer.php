			</div> 	<!-- end .container -->
		</div> <!-- end #top-shadow -->
	</div> <!-- end #content-area -->
	
	<div id="footer">
		<div id="footer-pattern">
			<div class="container">
				<div id="footer-widgets" class="clearfix">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
					<?php endif; ?>
				</div> <!-- end #footer-widgets -->	
			</div> <!-- end .container -->	
			<div id="footer-bottom">
				<div class="container clearfix">
					<?php 
						$menuID = 'bottom-menu';
						$footerNav = '';
					
						if (function_exists('wp_nav_menu')) $footerNav = wp_nav_menu( array( 'theme_location' => 'footer-menu', 'container' => '', 'fallback_cb' => '', 'menu_id' => $menuID, 'menu_class' => 'bottom-nav', 'echo' => false, 'depth' => '1' ) );
						if ($footerNav == '') show_page_menu($menuID);
						else echo($footerNav); 
					?>
					<p id="copyright"><?php _e('Designed by ','InReview'); ?> <a href="http://www.elegantthemes.com" title="Premium WordPress Themes">Elegant WordPress Themes</a> | <?php _e('Powered by ','InReview'); ?> <a href="http://www.templat.org.ua">WordPress</a></p>
				</div> <!-- end .container -->		
			</div> <!-- end #footer-bottom -->
		</div> <!-- end #footer-pattern -->	
	</div> <!-- end #footer -->
	
	<?php include(TEMPLATEPATH . '/includes/scripts.php'); ?>
	<?php wp_footer(); ?>
	
</body>
</html>