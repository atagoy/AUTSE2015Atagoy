<?php if ( has_nav_menu( 'social-footer' ) ) : // Check if there's a menu assigned to the 'social-footer' location. ?>

	<?php wp_nav_menu(
		array(
			'theme_location'  => 'social-footer',
			'container'       => 'div',
			'container_id'    => 'menu-social',
			'container_class' => 'menu',
			'menu_id'         => 'menu-social-items',
			'menu_class'      => 'menu-items',
			'depth'           => 1,
			'link_before'     => '<span class="screen-reader-text">',
			'link_after'      => '</span>',
			'fallback_cb'     => '',
		)
	); ?>

<?php endif; // End check for menu. ?>