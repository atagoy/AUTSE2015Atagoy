/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){

	$(document).bind('ready', function() {

		var config = $('body').data('config') || {};
		
		// Accordion menu
		$('.menu-sidebar').accordionMenu({ mode:'slide' });

		// Dropdown menu
		$('#menu').dropdownMenu({ mode: 'slide', duration: 300, dropdownSelector: 'div.dropdown'});

		// Smoothscroller
		$('a[href="#page"]').smoothScroller({ duration: 500 });

		// Fix Browser Rounding
		$('.grid-block').matchWidth('.grid-h');

		// Social buttons
		$('article[data-permalink]').socialButtons(config);

		// Match height of div tags
		var matchHeight = function(){
			$('#top-a .grid-h').matchHeight('.deepest');
			$('#top-b .grid-h').matchHeight('.deepest');
			$('#bottom-a .grid-h').matchHeight('.deepest');
			$('#bottom-b .grid-h').matchHeight('.deepest');
			$('#innertop .grid-h').matchHeight('.deepest');
			$('#innerbottom .grid-h').matchHeight('.deepest');
			$('#maininner, #sidebar-a, #sidebar-b').matchHeight();
			$('.page-body-1').css("min-height", $(window).height());
		};

		matchHeight();

		// Socialbar for touch devices
		$(window).bind("load", matchHeight);
		
		if("ontouchend" in document){

			var socialbar = $("#socialbar").bind("click", function(){

				if (socialbar.hasClass("touched")) {
					socialbar.removeClass("touched").addClass("untouched");
				}else{
					socialbar.removeClass("untouched").addClass("touched");
				}
			});
		}

	});
	
})(jQuery);