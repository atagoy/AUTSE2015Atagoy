<?php

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'inception_customize_css_register' );

/* Output CSS into <head>. */
add_action( 'wp_head', 'print_custom_css', 20 );

/* Delete the cached data for this feature. */
add_action( 'update_option_theme_mods_' . get_stylesheet(), 'custom_css_cache_delete' );

/**
 * Deletes the cached style CSS that's output into the header.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */

function custom_css_cache_delete() {
	wp_cache_delete( 'inception_custom_css' );
}


function print_custom_css() {
	/* Get the cached style. */
	$style = wp_cache_get( 'inception_custom_css' );

	/* If the style is available, output it and return. */
	if ( !empty( $style ) ) {
		echo $style;
		return;
	} else {
	
		/* Adding custom css based on customizer setting */

		$layout_style = sanitize_key(get_theme_mod('inception_layout_style'));

		$layout_width = intval(get_theme_mod('inception_layout_width'));
		$layout_width_min = '';
		
		if($layout_width == "1600"){
			$layout_width_min = "1630";
		}
		
		if($layout_width == "1170"){
			$layout_width_min = "1200";
		}
		
		if($layout_width == "992"){
			$layout_width_min = "970";
		}
		
		if($layout_width == "768"){
			$layout_width_min = "750"; 
		}
		
		$custom_css = '';

		if($layout_style == "boxed") { 
		$custom_css .= ' 
		@media (min-width: '.$layout_width_min.'px) {
			.container, #container {
				width: '.$layout_width.'px!important;
			}
		}';
		} 
		else {
		$custom_css .= ' 
		@media (min-width: '.$layout_width_min.'px) {
			.container {
				width: '.$layout_width.'px!important;
			}  
		}'; 
		}
		
		/* Fetch header image and add css only if header image exists */
		$header_image = get_header_image();
		if(!empty($header_image)){
		$custom_css .= '
		#intro{
			height: auto;  
			margin: 0 auto; 
			width: 100%; 
			position: relative; 
			padding: 200px 0;
		}
		#intro{
			background: url('.$header_image.') 50% 0 fixed;
		}';
		}
		/* Put the final style output together. */
		$style = "\n" . '<style type="text/css" id="custom-css">' .$custom_css. "\n" .trim( htmlspecialchars_decode (get_theme_mod( 'inception_custom_css' )) ) . '</style>' . "\n";

		/* Cache the style, so we don't have to process this on each page load. */
		wp_cache_set( 'inception_custom_css', $style );

		/* Output the custom style. */
		echo $style;
	}
}

/**
 * Registers custom sections, settings, and controls for the $wp_customize instance.
 *
 * @since 1.0.0
 * @access private
 * @param object $wp_customize
 */
function inception_customize_css_register( $wp_customize ) {

	/* Add the section. */
	$wp_customize->add_section(
		'inception_custom',
		array(
			'title'      => esc_html__( 'Custom CSS', 'inception' ),
			'priority'   => 200,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'custom_css' setting. */
	$wp_customize->add_setting(
		'inception_custom_css',
		array(
			'default'              => '',
			'type'                 => 'theme_mod',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'custom_css_sanitize',
			'transport'            => 'postMessage',
		)
	);

	/* Add the textarea control for the 'custom_css' setting. */
	$wp_customize->add_control(
		new Hybrid_Customize_Control_Textarea(
			$wp_customize,
			'inception_custom_css',
			array(
				'label'    => '',
				'section'  => 'inception_custom',
				'settings' => 'inception_custom_css',
			)
		)
	);

	/* If viewing the customize preview screen, add a script to show a live preview. */
	if ( $wp_customize->is_preview() && !is_admin() ) {
		add_action( 'wp_footer', 'inception_customize_preview_script', 22 );
	}
}

/**
 * Handles changing settings for the live preview of the theme.
 *
 * @since 0.3.2
 * @access private
 */
function inception_customize_preview_script() {

	?>
	<script type="text/javascript">
	wp.customize(
		'inception_custom_css',
		function( value ) {
			value.bind(
				function( to ) {
					jQuery( '#custom-css' ).text( to );
				}
			);
		}
	);
	</script>
	<?php
}

/**
 * sanitize css input
 *
 * @since 1.0.0
 * @access private
 */
function custom_css_sanitize($value) {

	return stripslashes( wp_filter_post_kses( addslashes( $value ) ) );

}
?>