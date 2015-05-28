/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){

	$(document).bind('ready', function() {

		if (!$("#menu").find("#searchbox").length) {
			$("#menu > ul:last").addClass("last");
		}
		
		/* Accordion menu */
		$('.menu-accordion').accordionMenu({ mode:'slide' });
		
		/* Smoothscroller */
		$('a[href="#page"]').smoothScroller({ duration: 500 });

		/* Spotlight */
		$('.warpspotlight').warpspotlight({fade: 300});

		var matchHeight = function(selector, deepest) {
			
			var deepest  = deepest || ".deepest";
			var elements = $(selector);
			var max      = 0;

			elements.each(function(){
				max = Math.max(max, $(this).outerHeight());
			});
			
			
			elements.each(function(){
				var box = $(this),
					ele = box.find(deepest+":first"),
					height = (ele.height() + (max - box.outerHeight()));

				ele.css("min-height", height+"px");
			});
		};  

		/* Match height of div tags */
		var matchHeights = function() {
			
			matchHeight('#top > .horizontal');
			matchHeight('#bottom > .horizontal');
			matchHeight('#maintop > .horizontal');
			matchHeight('#mainbottom > .horizontal');
			matchHeight('#contenttop > .horizontal');
			matchHeight('#contentbottom > .horizontal');

		};

		$('#menu').css("visibility", "hidden");
		
		$(window).bind("load", function(){
			
			matchHeights();
			
			/* Dropdown menu */
			$('#menu').dropdownMenu({ mode: 'slide', dropdownSelector: 'div.dropdown:first', centerDropdown: true, fixWidth: true}).css("visibility", "visible");	
				
		})
		
	});

})(jQuery);