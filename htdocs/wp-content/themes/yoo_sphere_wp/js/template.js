/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){

	$(document).ready(function() {

		var config = $('body').data('config') || {};
		
		// Accordion menu
		$('.menu-sidebar').accordionMenu({ mode:'slide' });

		// Dropdown menu
		$('#menu').dropdownMenu({ mode: 'slide', dropdownSelector: 'div.dropdown', boundary: $('#maininner'), boundarySelector: '.dropdown-bg > div' });

		// Smoothscroller
		$('a[href="#page"]').smoothScroller({ duration: 500 });

		// Social buttons
		$('article[data-permalink]').socialButtons(config);

		var last_inner_section = $("#maininner > section:last");

		if (last_inner_section.length) {
			var bg_color = last_inner_section.css("background-color");
			if ($.inArray(bg_color, ["","transparent","rgba(0, 0, 0, 0)"]) == -1) {
				last_inner_section.parent().css("background-color", bg_color);
			}
		}

		// auto size background
		$(window).bind("resize", (function(){
			var res = function(){ $('.page-body').css("min-height", $(window).height()); return res; };
			return res();
		})());

	});

	$.onMediaQuery('(min-width: 960px)', {
		init: function() {
			if (!this.supported) this.matches = true;
		},
		valid: function() {
			//fixes chrome matchheight problem
			setTimeout(function(){
				$.matchWidth('grid-block', '.grid-block', '.grid-h').match();
				$.matchHeight('main', '#maininner, #sidebar-a, #sidebar-b').match();
				$.matchHeight('top-a', '#top-a .grid-h', '.deepest').match();
				$.matchHeight('top-b', '#top-b .grid-h', '.deepest').match();
				$.matchHeight('bottom-a', '#bottom-a .grid-h', '.deepest').match();
				$.matchHeight('bottom-b', '#bottom-b .grid-h', '.deepest').match();
				$.matchHeight('innertop-a', '#innertop-a .grid-h', '.deepest').match();
				$.matchHeight('innertop-b', '#innertop-b .grid-h', '.deepest').match();
				$.matchHeight('innerbottom-a', '#innerbottom-a .grid-h', '.deepest').match();
				$.matchHeight('innerbottom-b', '#innerbottom-b .grid-h', '.deepest').match();
			}, 10);

		},
		invalid: function() {
			$.matchWidth('grid-block').remove();
			$.matchHeight('main').remove();
			$.matchHeight('top-a').remove();
			$.matchHeight('top-b').remove();
			$.matchHeight('bottom-a').remove();
			$.matchHeight('bottom-b').remove();
			$.matchHeight('innertop-a').remove();
			$.matchHeight('innertop-b').remove();
			$.matchHeight('innerbottom-a').remove();
			$.matchHeight('innerbottom-b').remove();
		}
	});

	var pairs = [];

	$.onMediaQuery('(min-width: 480px) and (max-width: 959px)', {
		valid: function() {
			$.matchHeight('sidebars', '.sidebars-2 #sidebar-a, .sidebars-2 #sidebar-b').match();
			pairs = [];
			$.each(['.sidebars-1 #sidebar-a > .grid-box', '.sidebars-1 #sidebar-b > .grid-box', '#top-a .grid-h', '#top-b .grid-h', '#bottom-a .grid-h', '#bottom-b .grid-h', '#innertop-a .grid-h', '#innertop-b .grid-h', '#innerbottom-a .grid-h', '#innerbottom-b .grid-h'], function(i, selector) {
				for (var i = 0, elms = $(selector), len = parseInt(elms.length / 2); i < len; i++) {
					var id = 'pair-' + pairs.length;
					$.matchHeight(id, [elms.get(i * 2), elms.get(i * 2 + 1)], '.deepest').match();
					pairs.push(id);
				}
			});
		},
		invalid: function() {
			$.matchHeight('sidebars').remove();
			$.each(pairs, function() { $.matchHeight(this).remove(); });
		}
	});

	$.onMediaQuery('(max-width: 767px)', {
		valid: function() {
			var header = $('#header-responsive');
			if (!header.length) {
				header = $('<div id="header-responsive"/>').prependTo('#header');
				$('#logo').clone().removeAttr('id').addClass('logo').appendTo(header);
				$('.searchbox').first().clone().removeAttr('id').appendTo(header);
				$('#menu').responsiveMenu().next().addClass('menu-responsive').appendTo(header);
			}
		}
	});

})(jQuery);