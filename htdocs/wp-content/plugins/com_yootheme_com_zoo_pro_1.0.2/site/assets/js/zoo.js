/* Copyright (C) 2007 - 2009 YOOtheme GmbH */

/* Load IE6 fix */
if (window.ie6) {

	if (!$defined(window['DD_belatedPNG'])) {
		$ES('script').each(function(s, i){
			var src  = s.getProperty('src');
			if (src && src.match(/zoo\.js(\?.*)?$/)) {
				path = src.replace(/zoo\.js(\?.*)?$/,'');
				document.write('<script language="javascript" src="' + path + 'ie6png.js" type="text/javascript"></script>');
			}
		});
	}
	
	window.addEvent('domready', function(){
		DD_belatedPNG.fix('div#yoo-zoo div.downloads a.file, div#yoo-zoo div.downloads a.file span.file-2, div#yoo-zoo div.downloads div.row, div#yoo-zoo div.downloads div.download-type, div#yoo-zoo div.product div.alpha-index, div#yoo-zoo div.product div.alpha-index-r, div#yoo-zoo div.product div.box-t1, div#yoo-zoo div.product div.box-t2, div#yoo-zoo div.product div.box-b1, div#yoo-zoo div.product div.box-b2, div#yoo-zoo div.product h1.sub-categories-title, div#yoo-zoo div.product h1.sub-categories-title span, div#yoo-zoo div.product h1.items-title, div#yoo-zoo div.product h1.items-title span'); 
	});
}
