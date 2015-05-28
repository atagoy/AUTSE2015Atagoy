<?php

/* Register custom image sizes. */
add_action( 'init', 'inception_register_image_sizes', 5 );

/* Register custom menus. */
add_action( 'init', 'inception_register_menus', 5 );

/* Register sidebars. */
add_action( 'widgets_init', 'inception_register_sidebars', 5 );

/* Add custom scripts. */
add_action( 'wp_enqueue_scripts', 'inception_enqueue_scripts', 5 );

/* Add custom styles. */
add_action( 'wp_enqueue_scripts', 'inception_enqueue_styles', 5 );

/* Excerpt-related filters. */
add_filter( 'excerpt_length', 'inception_excerpt_length' );

/* Adds custom attributes to the footer sidebar. */
add_filter( 'hybrid_attr_sidebar', 'inception_sidebar_footer_class', 10, 2 );

/* Filters the calendar output. */
add_filter( 'get_calendar', 'inception_get_calendar' );

/* Adds bootstrap caption style */
add_filter('img_caption_shortcode', 'inception_caption', 10, 3);

/* Get the template directory and make sure it has a trailing slash. */
$inception_inc = trailingslashit( get_template_directory() );

/* Load theme files. */
require_once( $inception_inc . 'inc/functions/custom-background.php' 			);
require_once( $inception_inc . 'inc/functions/custom-header.php'    			);
require_once( $inception_inc . 'inc/functions/custom-css.php'       			);
require_once( $inception_inc . 'inc/functions/custom-colors.php'     			);
require_once( $inception_inc . 'inc/admin/customize.php'    		  			);
require_once( $inception_inc . 'inc/admin/metabox.php'    		  			);
require_once( $inception_inc . 'inc/classes/bootstrap_nav_walker.php' 		);
require_once( $inception_inc . 'inc/classes/bootstrap_nav_walker_select.php' 	);

/**
 * Registers custom image sizes for the theme. 
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_register_image_sizes() {

	/* Sets the 'post-thumbnail' size. */
	set_post_thumbnail_size( 175, 131, true );

	/* Adds the 'inception-full' image size. */
	add_image_size( 'inception-full', 1025, 500, false );
}

/**
 * Registers nav menu locations.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_register_menus() {
	register_nav_menu( 'primary',		_x( 'Primary',			'nav menu location', 'inception' ) );
	register_nav_menu( 'social-header',	_x( 'Header Social',	'nav menu location', 'inception' ) );
	register_nav_menu( 'social-footer',	_x( 'Footer Social',	'nav menu location', 'inception' ) );
}

/**
 * Registers sidebars.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_register_sidebars() {

	hybrid_register_sidebar(
		array(
			'id'          => 'primary',
			'name'        => _x( 'Primary', 'sidebar', 'inception' ),
			'description' => __( 'Main Sidebar used in both two column and three column layout. Generally present in Right section.', 'inception' )
		)
	);
	
	hybrid_register_sidebar(
		array(
			'id'          => 'secondary',
			'name'        => _x( 'Secondary', 'sidebar', 'inception' ),
			'description' => __( 'Secondary Sidebar used in three column layout only.', 'inception' )
		)
	);

	hybrid_register_sidebar(
		array(
			'id'          => 'subsidiary',
			'name'        => _x( 'Subsidiary', 'sidebar', 'inception' ),
			'description' => __( 'Footer Sidebar. Optimized for one, two and three column layout.', 'inception' )
		)
	);
}

/**
 * Load scripts for the front end.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_enqueue_scripts() {
	
	$suffix = hybrid_get_min_suffix();

	wp_register_script( 'inception', trailingslashit( get_template_directory_uri() ) . "js/theme{$suffix}.js", array( 'jquery' ), null, true );
	
	wp_enqueue_script( 'inception' );
}

/**
 * Load stylesheets for the front end.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_enqueue_styles() {

	/* Gets ".min" suffix. */
	$suffix = hybrid_get_min_suffix();

	/* Load one-five base style. */
	if ( current_theme_supports( 'one-five' ) ) {
		wp_enqueue_style( 'one-five', trailingslashit( HYBRID_CSS ) . "one-five{$suffix}.css" );
	}
	
	/* Load gallery style if 'cleaner-gallery' is active. */
	if ( current_theme_supports( 'cleaner-gallery' ) ) {
		wp_enqueue_style( 'gallery', trailingslashit( HYBRID_CSS ) . "gallery{$suffix}.css" );
	}

	/* Load parent theme stylesheet if child theme is active. */
	if ( is_child_theme() ) {
		wp_enqueue_style( 'parent', trailingslashit( get_template_directory_uri() ) . "style{$suffix}.css" );
	}

	/* Load active theme stylesheet. */
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}

/**
 * Adds a custom excerpt length.
 *
 * @since  1.0.0
 * @access public
 * @param  int     $length
 * @return int
 */
function inception_excerpt_length( $length ) {
	return 30;
}

/**
 * Removes the [..] sign/symbol for excerpt.
 *
 * @since  1.0.0
 * @access public
 * @param  string     $text
 * @return string
 */
function trim_excerpt($text) {
  return rtrim($text,'[...]');
}
add_filter('get_the_excerpt', 'trim_excerpt');

/**
 * Adds a custom class to the 'footer' sidebar.  This is used to determine the number of columns used to 
 * display the sidebar's widgets.  This optimizes for 1, 2, and 3 columns or multiples of those values.
 *
 * Note that we're using the global $sidebars_widgets variable here. This is because core has marked 
 * wp_get_sidebars_widgets() as a private function. Therefore, this leaves us with $sidebars_widgets for 
 * figuring out the widget count.
 * @link http://codex.wordpress.org/Function_Reference/wp_get_sidebars_widgets
 *
 * @since  1.0.0
 * @access public
 * @param  array  $attr
 * @param  string $context
 * @return array
 */
function inception_sidebar_footer_class( $attr, $context ) {

	if ( 'subsidiary' === $context ) {
		global $sidebars_widgets;

		if ( is_array( $sidebars_widgets ) && !empty( $sidebars_widgets[ $context ] ) ) {

			$count = count( $sidebars_widgets[ $context ] );

			if ( 1 === $count )
				$attr['class'] .= ' sidebar-col-1';

			elseif ( !( $count % 3 ) || $count % 2 )
				$attr['class'] .= ' sidebar-col-3';

			elseif ( !( $count % 2 ) )
				$attr['class'] .= ' sidebar-col-2';
		}
	}

	return $attr;
}

/**
 * Turns the IDs into classes for the calendar.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $calendar
 * @return string
 */
function inception_get_calendar( $calendar ) {
	return preg_replace( '/id=([\'"].*?[\'"])/i', 'class=$1', $calendar );
}

/**
 * Checks the values of theme_mod andd metabox to generate class for the main section.
 *
 * @since  1.0.0
 */
function inception_main_layout_class() {

$layout_global = get_theme_mod('inception_sidebar','content-sidebar');

	if(is_singular()){
		$layout = get_post_meta( get_the_ID(), 'inception_sidebarlayout', 'default', true );

		if(($layout == "default") || (empty($layout))){
			$layout = $layout_global;
		}
	}
	else {
		$layout = $layout_global;
	}
	/* Adds class according to the sidebar layout */
	if ($layout == "sidebar-content" || $layout == "content-sidebar") { 
		echo "col-md-8";
	} 
	elseif ($layout == "full-width"){ 
		echo "col-md-12";
	} 
	else { 
		echo "col-md-6";
	}
}

/**
 * Checks the values of theme_mod andd metabox to generate class for the main section.
 *
 * @since  1.0.0
 */
function inception_sidebar_layout_class() {

$layout_global = get_theme_mod('inception_sidebar','content-sidebar');

	if(is_singular()){
		$layout = get_post_meta( get_the_ID(), 'inception_sidebarlayout', 'default', true );

		if(($layout == "default") || (empty($layout))){
			$layout = $layout_global;
		}
	}
	else {
		$layout = $layout_global;
	}
	/* Adds class according to the sidebar layout */
	if ($layout == "sidebar-content" || $layout == "content-sidebar") { 
		echo "col-md-4";
	}
	
	else { 
		echo "col-md-3";
	}
	
}

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @since  1.0.0
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function inception_caption($output, $attr, $content) {
  if (is_feed()) {
    return $output;
  }

  $defaults = array(
    'id'      => '',
    'align'   => 'alignnone',
    'width'   => '',
    'caption' => ''
  );

  $attr = shortcode_atts($defaults, $attr);

  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
  if ($attr['width'] < 1 || empty($attr['caption'])) {
    return $content;
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
  $attributes .= ' style="width: ' . (esc_attr($attr['width']) + 10) . 'px"';

  $output  = '<figure' . $attributes .'>';
  $output .= do_shortcode($content);
  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
  $output .= '</figure>';

  return $output;
}

/**
 * Handles the array for wp_nav_menu mobile menu
 *
 * @since  1.0.0
 */
function inception_select_mobile_nav_menu() {
	$mobnav = wp_nav_menu(array(
		'container' => false,                           // remove nav container
		'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
		'menu_class' => 'clearfix',        				 // adding custom nav class
		'theme_location' => 'primary',                 // where it's located in the theme
		'before' => '',
		'echo' => false,                                // echo
		'after' => '',                                  // after the menu
		'link_before' => '',                            // before each link
		'link_after' => '',                             // after each link
		'depth' => 3,                                   // limit the depth of the nav
		'items_wrap' => '<div class="row eo-mobile-select-wrap hidden-sm hidden-md hidden-lg"><form><div class="form-group col-xs-12"><select onchange="if (this.value) window.location.href=this.value" id="%1$s" class="%2$s nav form-control">%3$s</select></div></form></div>',
		'walker' => new select_mobile_Walker_Nav_Menu(),
	//	'show_home' => true,
		'fallback_cb' => 'eo_mobile_nav_fallback'      // fallback function
	));
	echo strip_tags($mobnav, '<div><select><option><form><input>' );
	}
?>