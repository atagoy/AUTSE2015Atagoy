/**
 * Function for turning a hex color into an RGB string.
 */
function inception_hex_to_rgb( hex ) {
	var color = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );

	return parseInt( color[1], 16 ) + ", " + parseInt( color[2], 16 ) + ", " + parseInt( color[3], 16 );
}

/**
 * Handles the customizer live preview settings.
 */
jQuery( document ).ready( function() {

	/*
	 * Shows a live preview of changing the site title.
	 */
	wp.customize( 'blogname', function( value ) {

		value.bind( function( to ) {

			jQuery( '#site-title a' ).html( to );

		} ); // value.bind

	} ); // wp.customize

	/*
	 * Shows a live preview of changing the site description.
	 */
	wp.customize( 'blogdescription', function( value ) {

		value.bind( function( to ) {

			jQuery( '#site-description' ).html( to );

		} ); // value.bind

	} ); // wp.customize

	/*
	 * Shows a live preview of changing the site title color.
	 */
	wp.customize( 'header_text_color', function( value ) {

		value.bind( function( to ) {

			jQuery( '#site-title a' ).
				css( 'color', to );

		} ); // value.bind

	} ); // wp.customize

	/*
	 * Handes the header image.  This code replaces the "src" attribute for the image.
	 */
	wp.customize( 'header_image', function( value ) {

		value.bind( function( to ) {

			/* If removing the header image, make sure to hide it so there's not an error image. */
			if ( 'remove-header' === to ) {
				jQuery( '.header-image' ).hide();
			}

			/* Else, make sure to show the image and change the source. */
			else {
				jQuery( '.header-image' ).show();
				jQuery( '.header-image' ).attr( 'src', to );
			}

		} ); // value.bind

	} ); // wp.customize

	/*
	 * Handles the Primary color for the theme.  This color is used for various elements and at different 
	 * shades. It must set an rgba color value to handle the "shades".
	 */
	wp.customize( 'color_primary', function( value ) {

		value.bind( function( to ) {

			var rgb = inception_hex_to_rgb( to );

			/* special case: hover */
			jQuery( 'a' ).
				not( '#header a, .menu a, .entry-title a, #footer a, .media-info-toggle, .comment-reply-link, .comment-reply-login, .wp-playlist-item, .wp-playlist-caption' ).
				hover(
					function() {
						jQuery( this ).css( 'color', to );

					},
					function() {
						jQuery( this ).css( 'color', 'rgba( ' + rgb + ', 0.75 )' );
					}
			); // .hover

			jQuery( '.wp-playlist-light .wp-playlist-item, .wp-playlist-light .wp-playlist-caption' ).
				hover(
					function() {
						jQuery( this ).css( 'color', to );

					},
					function() {
						jQuery( this ).css( 'color', 'inherit' );
					}
			); // .hover

			/* color */

			jQuery( 'a, .wp-playlist-light .wp-playlist-playing' ).
				not( '#header a, .menu a, .entry-title a, #footer a, .media-info-toggle, .comment-reply-link, .comment-reply-login, .wp-playlist-caption' ).
				css( 'color', 'rgba( ' + rgb + ', 0.75 )' );

			jQuery( 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6' ).
				css( 'color', to );

			jQuery( '.navbar > .container .navbar-brand, legend, mark, .comment-respond .required, pre, .form-allowed-tags code, pre code' ).
				css( 'color', to );
			

			/* background-color */

			jQuery( "input[type='submit'], input[type='reset'], input[type='button'], button, .page-links a, .comment-reply-link, .comment-reply-login, .wp-calendar td.has-posts a, #menu-sub-terms li a" ).
				css( 'background-color', 'rgba( ' + rgb + ', 0.8 )' );

			jQuery( 'blockquote' ).
				css( 'background-color', 'rgba( ' + rgb + ', 0.85 )' );

			jQuery( 'blockquote blockquote' ).
				css( 'background-color', 'rgba( ' + rgb + ', 0.9 )' );

			jQuery( 'legend, mark, pre, .form-allowed-tags code' ).
				css( 'background-color', 'rgba( ' + rgb + ', 0.1 )' );

			jQuery( '.widget-title > .wrap, #comments-number > .wrap, #reply-title > .wrap, .attachment-meta-title > .wrap, .widget_search > .search-form, .skip-link .screen-reader-text' ).
				css( 'background-color', to );

			/* background-color for bootstrap nav bar */
			
			jQuery( '.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus' ).
				css( 'background-color', to );
			
			/* border-color */

			jQuery( 'legend' ).css( 'border-color', 'rgba( ' + rgb + ', 0.15 )' );

			/* border-top-color */

			jQuery( 'body' ).css( 'border-top-color', to );

			/* border-bottom-color */

			jQuery( '.entry-content a, .entry-summary a, .comment-content a' ).
				css( 'border-bottom-color', 'rgba( ' + rgb + ', 0.15 )' );

			jQuery( 'body, .widget-title, #comments-number, #reply-title, .attachment-meta-title' ).
				css( 'border-bottom-color', to );

			/* outline-color */

			jQuery( 'blockquote' ).
				css( 'outline-color', 'rgba( ' + rgb + ', 0.85 )' );

		} ); // value.bind

	} ); // wp.customize

} ); // jQuery( document ).ready