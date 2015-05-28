/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var ElementRating = new Class({

	initialize: function(element, url, options){
		this.element = $(element);
		this.url     = url;

		var obj  = this;
		var elms = this.element.getElements('div.stars');

		elms.each(function(elm, i) {
			elm.addEvent('click', function() { obj.vote(elms.length - i); });
			elm.addEvent('mouseenter', function() { elm.addClass('hover'); });
			elm.addEvent('mouseleave', function() { elm.removeClass('hover'); });
		});
	},

	vote: function(value) {
		var obj = this;
				
		new Ajax(this.url, {
			method: 'post',
			data: 'method=vote&args[0]=' + value,
			onComplete: function(data){
				var res = data.split(',');
				var width = res[0];
				var message = res[1];
				if (width > 0) obj.element.getElement('div.previous-rating').setStyle('width', width + '%');
				obj.element.getElement('div.vote-message').setHTML(message);
			}
		}).request();
	}

});

ElementRating.implement(new Options);