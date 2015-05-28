/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var ElementCheckbox = new Class({

	initialize: function(element, url, options){
		this.setOptions({
			'variable': null
		}, options);

		this.element = $(element);
		this.url     = url;
		this.count   = this.element.getElement('ul').getChildren().length;
	},

	attachEvents: function() {
		var obj = this;

		this.element.getElements('div.option-delete').each(function(elm, i) {
			elm.addEvent('click', function() { obj.deleteOption(elm); });
		});

		this.element.getElement('div.option-add').addEvent('click', function() { obj.addOption(); });
	},

	addOption: function() {
		var obj = this;
		var ul  = this.element.getElement('ul');
		var li  = new Element('li');
		var fx  = li.effects({duration: 500, transition: Fx.Transitions.linear});

		new Ajax(this.url+'&task=callelement&method=configeditoption', {
			method: 'post',
			data: 'element=checkbox&args[0]=' + this.options.variable + '&args[1]=' + this.count++,
			onRequest: function(){
			},
			onComplete: function(data){
				li.injectInside(ul);
				li.setHTML(data);
				li.getElements('div.option-delete').each(function(elm, i) {
					elm.addEvent('click', function() { obj.deleteOption(elm); });
				});
				
				obj.highlight(li);
			}
		}).request();
	},

	deleteOption: function(trigger) {
		var obj = this;
		var elm = trigger.getParent();
		var fx  = elm.effects({duration: 100, transition: Fx.Transitions.linear});

		fx.start({
			'height': 0,
			'opacity': 0			
		}).chain(function(){
			elm.remove();
			obj.orderOptions();
		});
	},

	orderOptions: function() {
		var options = this.element.getElement('ul').getChildren();
		var pattern = /^(\S+\[options\])\[\d+\](\[name\]|\[value\])$/;
		
		this.count = options.length;
		options.each(function(elm, i) {
			elm.getElements('input').each(function(inp, j) {
				var name = inp.getProperty('name').replace(pattern, "$1["+i+"]$2");
				
				inp.setProperty('name', name);
			});
		});
	},

	highlight: function(element) {
		var fx = element.effects({duration: 100, transition: Fx.Transitions.linear});

		fx.start({
			'background-color': '#ffffaa'
		}).chain(function(){
			this.setOptions({duration: 700});
			this.start({
				'background-color': '#ffffff'
			});
		}).chain(function(){
			element.removeProperty('style');
		});
	}

});

ElementCheckbox.implement(new Options);