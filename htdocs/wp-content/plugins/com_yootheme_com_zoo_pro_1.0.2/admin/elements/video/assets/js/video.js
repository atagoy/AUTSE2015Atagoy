/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var ElementVideo = new Class({

	initialize: function(element, options) {
		this.setOptions({
			'file': '.file',
			'url': '.url',
			'type': '.type',
			'notice': 'span.notice',
			'formats': ['avi','divx','flv','mov','mpg','mp4','wmv','swf'],
			'sites': ['video.google.com', 'youtube.com', 'liveleak.com', 'vids.myspace.com', 'vimeo.com'],
			'msgNoMatch': " Can't match format, select manually."
		}, options);

		this.element = $(element);		
	},

	attachEvents: function(){
		var obj    = this;
		var file  = this.element.getElement(this.options.file);
		var url = this.element.getElement(this.options.url);
		var type   = this.element.getElement(this.options.type);
		var notice = type.getParent().getElement(this.options.notice);

		file.addEvent('change', function(){
			var value   = file.getProperty('value');
			var format  = obj.getVideoFormat(value);
			var message = "";

			if (value && !format) {
				message = obj.options.msgNoMatch;
			}			

			type.setProperty('value', format);
			notice.setHTML(message);
		});	

		url.addEvent('blur', function(){
			var value   = url.getProperty('value');
			var format  = obj.getVideoFormat(value) || obj.getVideoSite(value);
			var message = "";
			
			if (file.getProperty('value')) {
				return;
			}

			if (value && !format) {
				message = obj.options.msgNoMatch;
			}			

			type.setProperty('value', format);
			notice.setHTML(message);
		});	
	},
	
	getVideoFormat: function(value){
		var ret = "";		

		this.options.formats.each(function(format){
  			if (value.test("^.*\."+format+"$","i")) {
  				ret = format;
  			}
		});
				
		return ret;
	},

	getVideoSite: function(value){
		var ret = "";		
				
		this.options.sites.each(function(format){
  			if (value.test(format)) {
  				ret = format;
  			}
		});
		
		return ret;
	}

});

ElementVideo.implement(new Options);