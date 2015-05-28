/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

var Zoo = new Class({

	initialize: function(url, options){
		this.setOptions({
			msgDeletelog: 'Are you sure you want to delete the element?'
		}, options);

		this.url   = url;
		this.count = 0;
		this.sort  = null;
	},

	attachEvents: function() {
		var obj = this;

		this.attachSort();
		
		$('element-list').getElements('div.delete-element').each(function(elm, i) {
			elm.addEvent('click', function() { obj.deleteElement(elm); });
		});
		
		$('add-element').getElements('ul.elements li').each(function(elm, i) {
			elm.addEvent('click', function() { obj.addElement(elm.getProperty('class'), obj.count++); });
		});
	},

	attachSort: function() {
		var obj = this;

		this.sort = new Sortables($('element-list'), {
			'handles': $('element-list').getElements('div.sort-element'),
			'onDragStart': function(element, ghost){
				var coord = element.getCoordinates();
				element.setStyle('opacity', 0.3);
				element.addClass('dragging');
				ghost.setStyles({
				   'background-color': '#ffffff',
				   'width': coord.width,
				   'opacity': 1
				});
			},
			'onDragComplete': function(element, ghost){
				element.setStyle('opacity', 1);
				element.removeClass('dragging');
				ghost.remove();
				this.trash.remove();
				obj.orderElements();
			}	
		});
	},

	addElement: function(name, count) {
		var obj  = this;
		var list = $('element-list');
		var div  = new Element('div', {'class': 'element'});
		var fx   = div.effects({duration: 200, transition: Fx.Transitions.linear});

		new Ajax(this.url+'&task=addelement', {
			method: 'post',
			data: 'element=' + name + '&count=' + count,
			onRequest: function(){
				div.addClass('loading');
				div.injectTop(list);
			},
			onComplete: function(data){
				div.removeClass('loading');
				div.setHTML(data);

				obj.orderElements();
				
				obj.sort.detach();
				obj.attachSort();
	
				div.getElements('div.delete-element').each(function(elm, i) {
					elm.addEvent('click', function() { obj.deleteElement(elm); });
				});
				
				new Tips(div.getElements('.hasTip'), { maxTitleChars: 50, fixed: false });

				div.getElements('script').each(function(scr, i) {
					eval(scr.getText());
				});

				var coord = div.getCoordinates();
				div.setStyles({'height': 0, 'opacity': 0});
		
				fx.start({
					'height': (coord.height+10),
					'opacity': 1			
				}).chain(function(){
					obj.highlight(div);
				});
			}
		}).request();
	},

	deleteElement: function(trigger) {
		if (confirm(this.options.msgDeletelog)) {
			var obj  = this;
			var element = trigger.getParent().getParent();
			var fx = element.effects({duration: 200, transition: Fx.Transitions.linear});

			fx.start({
				'height': 0,
				'opacity': 0			
			}).chain(function(){
				element.remove();
				obj.orderElements();				
			});
		}
	},

	orderElements: function() {
		$('element-list').getElements('input[name*=ordering]').each(function(elm, i) {
			elm.setProperty('value', i);
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

Zoo.implement(new Options);