/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var YOOgalleryfx = new Class({

	initialize: function(container){
		var fx = [];

		$(container).getElements('.thumbnail').each(function(el, i) {
			var image = el.getElement('img');
			
			image.setStyle('opacity', 0.3);
			fx[i] = image.effect('opacity',{
				duration: 700, wait: false
			});	
			
			el.addEvents({
				mouseenter: function(event) {
					fx[i].setOptions({ 'duration': 300 });
					fx[i].start(0.3,1);
				},
				mouseleave: function(event) {
					fx[i].setOptions({ 'duration': 700 });
					fx[i].start(1,0.3);
				}
			});
		});
	}

});