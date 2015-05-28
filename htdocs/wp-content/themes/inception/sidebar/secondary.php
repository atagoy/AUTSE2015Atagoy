<aside class="widget-area col-sm-12 <?php inception_sidebar_layout_class(); ?>" <?php hybrid_attr( 'sidebar', 'secondary' ); ?>>

	<h3 id="sidebar-secondary-title" class="screen-reader-text"><?php 
		/* Translators: %s is the sidebar name. This is the sidebar title shown to screen readers. */
		printf( _x( '%s Sidebar', 'sidebar title', 'inception' ), hybrid_get_sidebar_name( 'secondary' ) ); 
	?></h3>

	<?php if ( is_active_sidebar( 'secondary' ) ) : // If the sidebar has widgets. ?>

		<?php dynamic_sidebar( 'secondary' ); // Displays the secondary sidebar. ?>

	<?php else : // If the sidebar has no widgets. ?>

		<?php the_widget(
			'WP_Widget_Text',
			array(
				'title'  => __( 'Example Widget', 'inception' ),
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'text'   => sprintf( __( 'This is an example widget to show how the secondary sidebar looks by default. You can add custom widgets from the %swidgets screen%s in the admin.', 'inception' ), current_user_can( 'edit_theme_options' ) ? '<a href="' . admin_url( 'widgets.php' ) . '">' : '', current_user_can( 'edit_theme_options' ) ? '</a>' : '' ),
				'filter' => true,
			),
			array(
				'before_widget' => '<section class="widget widget_text">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>'
			)
		); ?>

	<?php endif; // End widgets check. ?>

</aside><!-- #sidebar-secondary -->