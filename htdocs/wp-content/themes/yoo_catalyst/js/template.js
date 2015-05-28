/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){

	$(document).bind('ready', function() {

		var config = $('body').data('config') || {};

		// Accordion menu
		$('.menu-sidebar').accordionMenu({ mode:'slide' });

		// Dropdown menu
		$('#menu').dropdownMenu({ mode: ($.browser.msie && $.browser.version<9 ? 'showhide':'slide'), dropdownSelector: 'div.dropdown', fancy:{ mode: 'move', duration: 300, transition: 'easeInOutCubic' } });

		// Smoothscroller
		$('a[href="#page"]').smoothScroller({ duration: 500 });

		// Social buttons
		$('article[data-permalink]').socialButtons(config);

		// Fix Browser Rounding
		$('.grid-block').matchWidth('.grid-h');

		// Match height of div tags
		var matchHeight = function(){
			$('#top-a .grid-h').matchHeight('.deepest');
			$('#top-b .grid-h').matchHeight('.deepest');
			$('#bottom-a .grid-h').matchHeight('.deepest');
			$('#bottom-b .grid-h').matchHeight('.deepest');
			$('#innertop .grid-h').matchHeight('.deepest');
			$('#innerbottom .grid-h').matchHeight('.deepest');
			$('#maininner, #sidebar-a, #sidebar-b').matchHeight();
		};

		matchHeight();
		
		$(window).bind('load',function(){
			$('#menu').trigger("menu:fixfancy");
			matchHeight();
		});

		$(window).bind("load", matchHeight);
		
	});
	
})(jQuery);