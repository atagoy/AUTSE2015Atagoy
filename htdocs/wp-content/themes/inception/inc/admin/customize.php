<?php
/**
 * Handles the theme's theme customizer functionality.
 *
 * @package    inception
 * @author     Rajeeb Banstola <rajeebthegreat@gmail.com>
 * @copyright  Copyright (c) 2014, Rajeeb Banstola
 * @link       http://rajeebbanstola.com.np
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */

/* Custom Controls */
add_action( 'customize_register', 'inception_custom_controls' );

/**
 * Loads custom control for layout settings
 */
function inception_custom_controls() {
	
	require_once get_template_directory() . '/inc/admin/customize-control-layout.php';
	
}

/* Theme Customizer setup. */
add_action( 'customize_register', 'inception_customize_register' );

/**
 * Sets up the theme customizer sections, controls, and settings.
 *
 * @since  1.0.0
 * @access public
 * @param  object  $wp_customize
 * @return void
 */
function inception_customize_register( $wp_customize ) {

	/* Load JavaScript files. */
	add_action( 'customize_preview_init', 'inception_enqueue_customizer_scripts' );

	/* Enable live preview for WordPress theme features. */
	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_image' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'background_position_x' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_repeat' )->transport     = 'postMessage';
	$wp_customize->get_setting( 'background_attachment' )->transport = 'postMessage';

	/* Remove the WordPress background image control. */
	$wp_customize->remove_control( 'background_image' );

	/* Add our custom background image control. */
	$wp_customize->add_control( 
		new hybrid_Customize_Control_Background_Image( $wp_customize ) 
	);
	
	/* Remove the WordPress display header text control. */
	$wp_customize->remove_control( 'display_header_text' );
	
	/* Add 'site_title' setting */
	$wp_customize->add_setting( 
		'inception_site_title', 
		array(
			'default'  	 => '1',
			'sanitize_callback'	   => 'inception_sanitize_checkbox',
		)
	);
	
	/* Add 'site_title' control */
	$wp_customize->add_control(
		'inception_site_title',
		array(
			'label'    	 => esc_html__( 'Display Site Title', 'inception' ),
			'section' 	 => 'title_tagline',
			'type'    	 => 'checkbox'
		)
	);
	
	/* Add 'site_description' setting */
	$wp_customize->add_setting( 
		'inception_site_description', 
		array(
			'default'  	 => '1',
			'sanitize_callback'	   => 'inception_sanitize_checkbox',
		)
	);
	
	/* Add 'site_description' control */
	$wp_customize->add_control(
		'inception_site_description',
		array(
			'label'    	 => esc_html__( 'Display Site Tagline', 'inception' ),
			'section' 	 => 'title_tagline',
			'type'    	 => 'checkbox'
		)
	);
	/* Add 'logo/favicon' section */
	$wp_customize->add_section(
		'inception_logo_section',
		array(
			'title'      => esc_html__( 'Logo & Favicon', 'inception' ),
			'priority'   => 30,
			'capability' => 'edit_theme_options'
		)
	);
	
	/* Add the 'logo' upload setting. */
	$wp_customize->add_setting(
		'inception_logo',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'esc_url_raw',
		)
	);

	/* Add the upload control for the 'inception_logo' setting. */
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'inception_logo',
			array(
				'label'    => esc_html__( 'Logo Upload', 'inception' ),
				'section'  => 'inception_logo_section',
				'settings' => 'inception_logo',
			)
		)
	);
	
	/* Add the 'favicon' upload setting. */
	$wp_customize->add_setting(
		'inception_favicon',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'esc_url_raw',
		)
	);

	/* Add the upload control for the 'inception_favicon' setting. */
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'inception_favicon',
			array(
				'label'    => esc_html__( 'Favicon Upload', 'inception' ),
				'section'  => 'inception_logo_section',
				'settings' => 'inception_favicon',
			)
		)
	);
	
	/* Add 'layout' section */
	$wp_customize->add_section(
			'inception_layout',
			array(
				'title'      	=> esc_html__( 'Layout', 'inception' ),
				'description'	=> 'Select main content and sidebar layout for blog.(Note: Layout for individual posts and pages can be selected in the respective posts and pages.',
				'priority'   	=> 50,
				'capability'	=> 'edit_theme_options'
			)
		);

	/* Add 'sidebar layout' setting. */
	$wp_customize->add_setting(
			'inception_sidebar',
			array(
				'default'  			   => 'content-sidebar',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   =>  'inception_sanitize_layout_sidebar',
			)
		);
		
	/* Add 'sidebar layout' control. */
	$wp_customize->add_control(
		new Layout_Picker_Custom_Control(
			$wp_customize,
			'inception_sidebar',
			array(
				'label'    => esc_html__( 'Layout Sidebar', 'inception' ),
				'section'  => 'inception_layout'
			)
		)
	);
	
	/* Add 'layout style' setting. */
	$wp_customize->add_setting(
			'inception_layout_style',
			array(
				'default'  			   => 'boxed',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   =>  'inception_sanitize_layout_style',
			)
		);
		
	/* Add 'layout style' control. */
	$wp_customize->add_control(
		'inception_layout_style',
		array(
			'label'    	 => esc_html__( 'Layout Style', 'inception' ),
			'section' 	 => 'inception_layout',
			'type'    	 => 'select',
			'choices'    => array(
				'wide' => 'Wide Layout',
				'boxed' => 'Boxed Layout',
			)
		)
	);
	
	/* Add 'layout width' setting. */
	$wp_customize->add_setting(
			'inception_layout_width',
			array(
				'default'  			   => '1170',
				'capability'           => 'edit_theme_options',
				'transport'            => 'refresh',
				'sanitize_callback'	   =>  'inception_sanitize_layout_width',
			)
		);
		
	/* Add 'layout width' control. */
	$wp_customize->add_control(
		'inception_layout_width',
		array(
			'label'    => esc_html__( 'Layout Width', 'inception' ),
			'section'  => 'inception_layout',
			'type'    => 'select',
			'choices'    => array(
				'1600' => '1600px',
				'1170' => '1170px (Default)',
				'992' => '992px',
				'768' => '768px',
			)
		)
	);
	
	/* Add 'header_info' section */
	$wp_customize->add_section(
		'inception_header_info',
		array(
			'title'      => esc_html__( 'Header Info', 'inception' ),
			'priority'   => 60,
			'capability' => 'edit_theme_options'
		)
	);
	
	/* Add the 'phone info' setting. */
	$wp_customize->add_setting(
		'inception_phone_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'inception_sanitize_integer',
		)
	);

	/* Add 'phone info' control. */
	$wp_customize->add_control(
		'inception_phone_info',
		array(
			'label'    => esc_html__( 'Phone Number', 'inception' ),
			'section'  => 'inception_header_info',
			'settings' => 'inception_phone_info',
		)
	);
	
	/* Add the 'email info' setting. */
	$wp_customize->add_setting(
		'inception_email_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'is_email',
		)
	);

	/* Add 'email info' control. */
	$wp_customize->add_control(
		'inception_email_info',
		array(
			'label'    => esc_html__( 'Email', 'inception' ),
			'section'  => 'inception_header_info',
			'settings' => 'inception_email_info',
		)
	);
	
	/* Add the 'location info' setting. */
	$wp_customize->add_setting(
		'inception_location_info',
		array(
			'default'  			   => '',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
			'sanitize_callback'	   =>  'inception_sanitize_text',
		)
	);

	/* Add the upload control for the 'location info' setting. */
	$wp_customize->add_control(
		'inception_location_info',
		array(
			'label'    => esc_html__( 'Location', 'inception' ),
			'section'  => 'inception_header_info',
			'settings' => 'inception_location_info',
		)
	);
	
	/* Add 'featured' section */
	$wp_customize->add_section(
		'inception_featured_section',
		array(
			'title'      => esc_html__( 'Front Page Featured Section', 'inception' ),
			'priority'   => 80,
			'capability' => 'edit_theme_options'
		)
	);
	
	/* Add the 'featured first' setting. */
	$wp_customize->add_setting(
		'inception_featured_first',
		array(
			'sanitize_callback'	   => 'inception_sanitize_integer',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
		)
	);

	/* Add 'featured first' control. */
	$wp_customize->add_control(
		'inception_featured_first',
		array(
			'label'    => esc_html__( 'First Featured Page', 'inception' ),
			'type' 	   => 'dropdown-pages',
			'section'  => 'inception_featured_section',
		)
	);
	
	/* Add the 'featured second' setting. */
	$wp_customize->add_setting(
		'inception_featured_second',
		array(
			'sanitize_callback'	   => 'inception_sanitize_integer',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
		)
	);

	/* Add 'featured second' control. */
	$wp_customize->add_control(
		'inception_featured_second',
		array(
			'label'    => esc_html__( 'Second Featured Page', 'inception' ),
			'type' 	   => 'dropdown-pages',
			'section'  => 'inception_featured_section',
		)
	);
	
	/* Add the 'featured third' setting. */
	$wp_customize->add_setting(
		'inception_featured_third',
		array(
			'sanitize_callback'	   => 'inception_sanitize_integer',
			'capability'           => 'edit_theme_options',
			'transport'            => 'postMessage',
		)
	);

	/* Add 'featured first' control. */
	$wp_customize->add_control(
		'inception_featured_third',
		array(
			'label'    => esc_html__( 'Third Featured Page', 'inception' ),
			'type' 	   => 'dropdown-pages',
			'section'  => 'inception_featured_section',
		)
	);

}

/**
 * Sanitize Integer
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_integer( $int ) {
	if( is_numeric( $int ) ) {
	return intval( $int );
}
}

/**
 * Sanitize text
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_text( $txt ) {
    return wp_kses_post( force_balance_tags( $txt ) );
}
/**
 * Sanitize text
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Sanitize layout sidebar radiobutton
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_layout_sidebar( $layout_sidebar ) {
    $valid = array(
        'full-width' 				=> 'full-width',
        'sidebar-content' 			=> 'sidebar-content',
        'content-sidebar' 			=> 'content-sidebar',
        'sidebar-sidebar-content' 	=> 'sidebar-sidebar-content',
        'sidebar-content-sidebar' 	=> 'sidebar-content-sidebar',
        'content-sidebar-sidebar' 	=> 'content-sidebar-sidebar',
    );
 
    if ( array_key_exists( $layout_sidebar, $valid ) ) {
        return $layout_sidebar;
    } else {
        return '';
    }
}

/**
 * Sanitize layout style
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_layout_style( $layout_style ) {
    $valid = array(
        'wide' 		=> 'Wide Layout',
		'boxed' 	=> 'Boxed Layout',
    );
 
    if ( array_key_exists( $layout_style, $valid ) ) {
        return $layout_style;
    } else {
        return '';
    }
}
/**
 * Sanitize layout width
 *
 * @since  1.0.1
 * @access public
 * @return sanitized output
 */
function inception_sanitize_layout_width( $layout_width ) {
    $valid = array(
       '1600' => '1600px',
		'1170' => '1170px (Default)',
		'992' => '992px',
		'768' => '768px',
    );
 
    if ( array_key_exists( $layout_width, $valid ) ) {
        return $layout_width;
    } else {
        return '';
    }
}
/**
 * Loads theme customizer JavaScript.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function inception_enqueue_customizer_scripts() {

	/* Use the .min script if SCRIPT_DEBUG is turned off. */
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script(
		'inception-customize',
		trailingslashit( get_template_directory_uri() ) . "js/customize{$suffix}.js",
		array( 'jquery' ),
		null,
		true
	);
}
