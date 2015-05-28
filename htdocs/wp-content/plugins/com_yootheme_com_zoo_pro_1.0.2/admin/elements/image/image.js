/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var ElementImage = new Class({

	initialize: function(element, preview, url, options){
		this.element = $(element);
		this.preview = $(preview);
		this.url     = url;
	},

	attachEvents: function() {
		var obj = this;

		this.element.addEvent('change', function(){
			obj.preview.empty();
			new Element('img', { 'src': obj.url + this.getProperty('value') }).injectInside(obj.preview);
		});	
	}

});

ElementImage.implement(new Options);