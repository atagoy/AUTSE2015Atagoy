/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

window.addEvent('domready', function(){

	// add submenu css classes
	if ($chk($('submenu'))) {
		$('submenu').getElements('li').each(function(li, i) {
			li.addClass('item' + (i+1));
		});
	}

	// add auto submit
	$$('select.auto-submit').addEvent('change', function(){
		document.adminForm.submit();
	});

	// select image
	$$('select.image-select').each(function(select, i) {
		select.addEvent('change', function(){
			var preview = this.getParent().getElement('div.image-preview');
			if (preview) {
				preview.empty();
				new Element('img', { 'src': preview.getProperty('title') + select.getProperty('value') }).injectInside(preview);
			}
		});
	});

	// alias validation
	if ($('catalog-default')) addAliasValidation($('catalog-default'), 'index.php?option=com_zoo&controller=catalog&view=catalog&task=aliasexists&format=raw');
	if ($('category-default')) addAliasValidation($('category-default'), 'index.php?option=com_zoo&controller=category&view=category&task=aliasexists&format=raw');
	if ($('item-edit')) addAliasValidation($('item-edit'), 'index.php?option=com_zoo&controller=item&view=item&task=aliasexists&format=raw');
	if ($('type-edit')) addAliasValidation($('type-edit'), 'index.php?option=com_zoo&controller=type&view=type&task=aliasexists&format=raw');

	function addAliasValidation(form, url) {

		var id = form.getElement('input[name="id"]');
		var alias = form.getElement('input[name="alias"]');

		alias.addEvent('blur', function(){
			var value = alias.getProperty('value');

			new Ajax(url, {
				method: 'post',
				data: 'id=' + id + '&alias=' + value,
				onComplete: function(data){
					var res = data.split(';');
					var msg = res[1];


					if (msg == 'Ok') {
						msg = '';
						alias.removeClass('inputerror');
					} else {
						alias.addClass('inputerror');
					}

					alias.setProperty('value', res[0]);
					alias.getParent().getElement('span.notice').setHTML(msg);
				}
			}).request();
		});
	}

});