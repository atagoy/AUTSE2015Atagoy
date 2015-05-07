/*! PushIt - v1.0
* An elegant transform-based off-canvas solution for providing left & right menus for mobile websites, complete with jQuery fallback.
* Based on "Pushy" by Christopher Yee - http://christopheryee.ca/pushy/
* Modified for left and right menu support by BraveNewCode Inc. for WPtouch - http://www.wptouch.com
*/

// Let's get the current vendor prefix for each browser
var prefix = (function() {
	var styles = window.getComputedStyle( document.documentElement, '' ),
	vendor = ( Array.prototype.slice.call( styles ).join( '' ).match( /-(moz|webkit|ms)-/ ) || ( styles.OLink === '' && ['', 'o'] ) )[1];
	return { css: '-' + vendor + '-' }
})();

( function( $ ){

	// Hey! Ow! Push it good!
	$.fn.pushIt = function( options ) {

		var settings = $.extend( {
			leftMenu: 			jQuery( '.pushit-left' ),
			rightMenu:	 		jQuery( '.pushit-right' ),
			body: 				jQuery( 'body' ),
			container: 			jQuery( '.page-wrapper' ),	// container element
			pushItActiveClass:	'pushit-active',			// element to toggle site overlay
			containerClass: 	'container-pushit',			// container open
			menuBtn: 			jQuery('.menu-btn'),		// css classe(s) to toggle the menu
			viewportWidth:	 	jQuery( window ).width(),	// Needed to position a right menu
			menuWidth:	 		240,						// Menu width (default is 240px)
			menuSpeed:	 		330,						// Speed of the menu transistion, in milliseconds
			bezierCurve:		'.290, .050, .140, .870',	// Menu transistion bezier
			pushed: 			false
		}, options );

		var hasOverflowScroll = typeof( jQuery( 'body' )[0].style['-webkit-overflow-scrolling'] ) !== 'undefined';
		if ( hasOverflowScroll ) {
			jQuery( 'body' ).addClass( 'has-overflow-scroll' );
		}

		// Add the overlay div
		jQuery( 'body' ).append( '<div id="pushit-overlay"></div>' );
		var pushitOverlay = jQuery( '#pushit-overlay' );

		// Setup default positioning and width
		settings.leftMenu.addClass( 'pushit-left' )
			.css( 'left', '-' + settings.menuWidth + 'px' )
			.css( 'width', settings.menuWidth + 'px' );

		// Setup default positioning and width
		settings.rightMenu.addClass( 'pushit-right' )
			.css( 'width', settings.menuWidth + 'px' )
			.css( 'left', settings.viewportWidth + 'px' );

		// Make sure it's positioned relative & add the transition CSS for slide prep
		settings.container
			.css( 'position', 'relative' )
			.css( prefix.css+'transition', prefix.css+'transform .'+settings.menuSpeed+'s cubic-bezier('+settings.bezierCurve+')' );

		// Add the transition CSS for slide  prep
		jQuery( '.pushit' )
			.css( prefix.css+'transition', prefix.css+'transform .'+settings.menuSpeed+'s cubic-bezier('+settings.bezierCurve+')' );

		function whichPushIt( clicked ){
			var parent = clicked;
			if ( parent.hasClass( 'pushit-left' ) ) {
				return 'left';
			} else {
				return 'right';
			}
		}

		function oppositePushIt( direction ) {
			var direction = ( direction == 'left' ? 'right' : 'left' );
			return direction;
		}

		function togglePushIt( clicked ){
			settings.pushed = clicked;
			direction = whichPushIt( clicked );
			var side = ('.pushit-' + direction );
			var activeSide = jQuery( side );
			jQuery( side ).toggleClass( 'pushit-open' );

			// Left Menus
			// Open
			if ( side == '.pushit-left' && activeSide.hasClass( 'pushit-open' ) ) {
				pushitCloseListener();
				if ( prefix.css != '' ){
					activeSide.css( prefix.css+'transform', 'translate3d(' + settings.menuWidth + 'px, 0, 0)' );
					settings.container
						.css( prefix.css+'transform', 'translate3d(' + settings.menuWidth + 'px, 0, 0)' )
						.height( window.innerHeight )
						.css( 'overflow', 'hidden' );
				} else {
					activeSide.animate( { left: '0' }, settings.menuSpeed );
					settings.container
						.height( window.innerHeight )
						.css( 'overflow', 'hidden' )
						.animate( { left: settings.menuWidth }, settings.menuSpeed );
				}
			// Closed
			} else if ( side == '.pushit-left' && !activeSide.hasClass( 'pushit-open' ) ) {
				cleanUpPushit();
				if ( prefix.css != '' ){
					activeSide.css( prefix.css+'transform', 'translate3d(0, 0, 0)' );
					settings.container.css( prefix.css+'transform', 'translate3d(0, 0, 0)' );
				} else {
					activeSide.animate( { left: '-' + settings.menuWidth }, settings.menuSpeed );
					settings.container.animate( { left: '0' }, settings.menuSpeed );
				}
			}
			// Right Menus
			// Open
			if ( side == '.pushit-right' && jQuery( side ).hasClass( 'pushit-open' ) ) {
				pushitCloseListener();
				if ( prefix.css != '' ){
					activeSide
						.css( prefix.css+'transform', 'translate3d(-' + settings.menuWidth + 'px, 0, 0)' );
					settings.container
						.css( prefix.css+'transform', 'translate3d(-' + settings.menuWidth + 'px, 0, 0)' )
						.height( window.innerHeight )
						.css( 'overflow', 'hidden' );
				} else {
					activeSide
						.animate( { left: settings.viewportWidth - settings.menuWidth }, settings.menuSpeed );
					settings.container
						.height( window.innerHeight )
						.css( 'overflow', 'hidden' )
						.animate( { left: '-' + settings.menuWidth }, settings.menuSpeed );
				}
			// Closed
			} else if ( side == '.pushit-right' && !jQuery( side ).hasClass( 'pushit-open' ) ) {
				cleanUpPushit();
				if ( prefix.css != '' ){
					activeSide.css( prefix.css+'transform', 'translate3d(0, 0, 0)' );
					settings.container.css( prefix.css+'transform', 'translate3d(0, 0, 0)' );
				} else {
					activeSide.animate( { left: settings.viewportWidth }, settings.menuSpeed );
					settings.container.animate( { left: '0' }, settings.menuSpeed );
				}
			}

			settings.container.toggleClass( whichPushIt( clicked ) );
			settings.body.toggleClass( settings.pushItActiveClass ); //toggle site overlay
			settings.container.toggleClass( settings.containerClass );
		}

		// Close the menu on a rotate— no need to have the browser choke on render
		var currentWindow = jQuery( window );
		currentWindow.resize( function(){
			if ( jQuery( '.pushit-active' ).length ) {
				pushitOverlay.trigger( 'click' );
				cleanUpPushit();
			}
			if ( settings.rightMenu.length ) {
				settings.viewportWidth = currentWindow.width();
				settings.rightMenu.css( 'left', settings.viewportWidth + 'px' );
			}
		});

		// Toggle menu
		settings.menuBtn.on( 'click', function( e ) {
			pushitOverlay.one( 'touchmove.pushit', function( e ){ e.preventDefault(); } );
			e.stopImmediatePropagation();
			menuTarget = '#' + jQuery( this ).attr( 'data-menu-target' );
			togglePushIt( jQuery( menuTarget ).parent() );
		});

		// Close menu when clicking container
		function pushitCloseListener() {
			pushitOverlay.one( 'click.pushit', function( e ){
				e.stopImmediatePropagation();
				togglePushIt( settings.pushed );
			});
		}

		function cleanUpPushit(){
			setTimeout( function(){
				// Cleanup memory usage!
				settings.container
					.css( prefix.css+'transform', '' )
					.css( 'overflow', '' )
					.css( 'height', '' );
			}, settings.menuSpeed + 300 );

		}

	}
})( jQuery );