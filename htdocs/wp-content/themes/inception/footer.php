
	</div><!-- .row -->
		
		</div><!-- .container -->
			
			</div><!-- #main -->

		<footer class="site-footer" <?php hybrid_attr( 'footer' ); ?>>
		
			<div class="footer-widget">
			
				<div class="container">
					
					<?php hybrid_get_sidebar( 'subsidiary' ); // Loads the sidebar/subsidiary.php template. ?>
					
				</div>
				
			</div>
				
			<div class="container">
			
				<div class="pull-left footer-menu">
				
					<?php hybrid_get_menu( 'social-footer' ); // Loads the menu/social-footer.php template. ?>
					
				</div>
					
				<div class="pull-right">
				
					<p class="copyright">
						Copyright &copy; <?php echo date_i18n( 'Y' ); ?>.
						<?php printf( __( 'Proudly Powered by ', 'inception' ) ); ?><a href="<?php echo esc_url( __( 'http://wordpress.org/', 'inception' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'inception' ); ?>">WordPress</a> & <a href="<?php echo esc_url('http://webyatri.com/themes/inception', 'inception'); ?>"title="WebYatri Themes" target="_blank">WebYatri Themes</a>
					</p><!-- .copyright -->
					
				</div>
					
			</div>
			
		<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->

	<?php wp_footer(); // WordPress hook for loading JavaScript, toolbar, and other things in the footer. ?>
	
	</div><!-- #container -->

</body>
</html>