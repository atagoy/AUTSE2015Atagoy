<?php
/**
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    inception
 * @subpackage Functions
 * @version    1.0.0
 * @author     Rajeeb Banstola <rajeebthegreat@gmail.com>
 * @copyright  Copyright (c) 2014, Rajeeb Banstola
 * @link       http://rajeebbanstola.com.np
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Get the template directory and make sure it has a trailing slash. */
$inception_dir = trailingslashit( get_template_directory() );

/* Load the Hybrid Core framework and theme files. */
require_once( $inception_dir . 'library/hybrid.php'        );

/* Launch the Hybrid Core framework. */
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'inception_theme_setup', 5 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_theme_setup() {

	/* Load files. */
	require_once( trailingslashit( get_template_directory() ) . 'inc/theme.php' );
	
	/* Load stylesheets. */
	add_theme_support(
		'hybrid-core-styles',
		array( 'style', 'parent', 'gallery' )
	);
		
	/* Enable custom template hierarchy. */
	add_theme_support( 'hybrid-core-template-hierarchy' );

	/* The best thumbnail/image script ever. */
	add_theme_support( 'get-the-image' );

	/* Breadcrumbs. Yay! */
	add_theme_support( 'breadcrumb-trail' );

	/* Pagination. */
	add_theme_support( 'loop-pagination' );

	/* Nicer [gallery] shortcode implementation. */
	add_theme_support( 'cleaner-gallery' );

	/* Better captions for themes to style. */
	add_theme_support( 'cleaner-caption' );

	/* Automatically add feed links to <head>. */
	add_theme_support( 'automatic-feed-links' );

	/* Post formats. */
	add_theme_support( 
		'post-formats', 
		array( 'aside', 'audio', 'chat', 'image', 'gallery', 'link', 'quote', 'status', 'video' ) 
	);

	/* Handle content width for embeds and images. */
	hybrid_set_content_width( 1280 );
}

/* Load bootstrap css and js files */
add_action( 'wp_enqueue_scripts', 'inception_scripts', 1 );

/**
 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_scripts() {

	/* Load the bootstrap files */
	wp_enqueue_style( 
		'inception-bootstrap', 
		trailingslashit( get_template_directory_uri() ) . 'css/bootstrap.min.css'
	);
	
	wp_enqueue_script( 
		'inception-bootstrap-js', 
		trailingslashit( get_template_directory_uri() ) . 'js/bootstrap.min.js',
		array( 'jquery' ),
		null,
		true
	);
	
	/* Load the font awesome icons */
	wp_enqueue_style( 
		'inception-fontawesome', 
		trailingslashit( get_template_directory_uri() ) . 'css/font-awesome.min.css'
	);
	
}

/* Load bootstrap css and js files */
add_action( 'wp_head', 'inception_favicon', 4 );

/**
 * Tells WordPress to load the favicon below other head inforamtion.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_favicon() { 
if(get_theme_mod('inception_favicon') !== ""):?>
<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod( 'inception_favicon' )); ?>" type="image/x-icon">
	<?php endif;
}