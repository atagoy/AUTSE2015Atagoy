<?php if ( has_nav_menu( 'primary' ) ) : // Check if there's a menu assigned to the 'primary' location. ?>

	<nav class="navbar navbar-default" <?php hybrid_attr( 'menu', 'primary' ); ?>>
	
	<div class="navbar-header">
		<?php inception_select_mobile_nav_menu(); ?>
	</div>
	
	<?php 
		wp_nav_menu(array(
			'menu'              => 'primary',
			'theme_location'    => 'primary',
			'depth'             => 4,
			'container'         => 'div',
			'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
			'menu_class'        => 'nav navbar-nav main-nav',
			'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
			'walker'            => new wp_bootstrap_navwalker()
		));			
	?>
	
	</nav>

<?php endif; // End check for menu. ?>