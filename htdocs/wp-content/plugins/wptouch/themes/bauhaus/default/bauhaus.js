/* WPtouch Bauhaus Theme JS File */
/* Public functions called here reside in base.js, found in the Foundation theme */

function doBauhausReady() {
	bauhausSliderMods();
	bauhausMoveFooterDiv();
	bauhausBindTappableLinks();
	bauhausSearchToggle();
	bauhausWebAppMenu();
	bauhausVideoUnwrap();
	bauhausHandleWebAppScrolling();
	bauhausHandleSearch();
	bauhausHandlePostImgs();
	if ( jQuery.fn.pushIt ) {
		jQuery( 'body' ).pushIt( { menuWidth: '270' } );
		bauhausOffCanvasMods();
	}
}

// Spice up the appearance of Foundation's Featured Slider
function bauhausSliderMods(){
	jQuery( '.swipe-wrap a' ).each( function(){
		imgCloned = jQuery( this ).find( 'img' ).clone();
		jQuery( this ).append( imgCloned );
		imgCloned.addClass( 'clone' );
		jQuery( this ).find( 'p' ).not( 'p.featured-date' ).addClass( 'heading-font' );
	});
}

// CSS animated slideout
function bauhausSearchToggle(){
	jQuery( '#search-toggle' ).on( 'click', function(){
		jQuery( '#search-dropper' ).toggleClass( 'toggled' );
	});
}

// Move the footer below the switch
function bauhausMoveFooterDiv(){
	if ( jQuery( '#switch' ).length ) {
		var footerDiv = jQuery( '.footer' ).detach();
		jQuery( '#switch' ).after( footerDiv );
	}
}

// Add 'touched' class to these elements when they're actually touched (100ms delay) for a better UI experience (tappable module)
function bauhausBindTappableLinks(){
	// Drop down menu items
	jQuery( 'li.menu-item' ).each( function(){
		jQuery( this ).addClass( 'tappable' );
	});
}

// In Web-App Mode, dynamically ensure that the Menu height is correct and scrollable
function bauhausWebAppMenu(){
	if ( navigator.standalone ) {
		jQuery( window ).resize( function() {
			var windowHeight = jQuery( window ).height() - 74;
			if ( jQuery( 'body.web-app-mode.ios7.smartphone.portrait' ).length ) {
				jQuery( '#menu' ).css( 'max-height', windowHeight );
			}
			if ( jQuery( 'body.web-app-mode.ios7.smartphone.landscape' ).length ) {
				jQuery( '#menu' ).css( 'max-height', windowHeight );
			}
		}).resize();
	}
}

// Unwrap video & photo from p tags, allows full-width display
function bauhausVideoUnwrap(){
var pTags = jQuery( '.fluid-width-video-wrapper, iframe, video' );
if ( pTags.parent().is( 'p' ) ) {
	pTags.unwrap();
  }
}

function bauhausHandleWebAppScrolling(){
	var startPosition = 0;
	var backButton = jQuery( '.back-button' );

	if ( backButton.is( 'div' ) ) {
		jQuery( window ).scroll( function () {
			var newPosition = jQuery( this ).scrollTop();
			if ( newPosition > startPosition ) {
				backButton.removeClass( 'visible' );
			} else {
				if ( !backButton.hasClass( 'visible' ) ) {
					backButton.addClass( 'visible' );
				}
			}
			startPosition = newPosition;
		});
	}
}

function bauhausHandlePostImgs(){
var img = jQuery( '.post-page-content p img' );
	jQuery( img ).each( function(){
		if ( !jQuery( this ).is( '.aligncenter, .alignleft, .alignright' ) ) {
			jQuery( this ).addClass( 'aligncenter' );
		}
	});
}

function bauhausHandleSearch() {
	if ( jQuery( '.search' ).length ) {
		jQuery( '.search-select' ).change( function( e ) {
			var sectionName = ( '#' + jQuery( this ).find( ':selected' ).attr( 'data-section' ) + '-results' );
			jQuery( '#content > div:not(.post-page-head-area)' ).hide();
			jQuery( sectionName ).show();
			e.preventDefault();
		}).trigger( 'change' );
	}
}

function bauhausOffCanvasMods(){
	jQuery( '.wptouch-login-wrap' ).detach().appendTo( 'body' );
}

jQuery( document ).ready( function() { doBauhausReady(); } );
