<?php
/**
* @package   Nano Template
* @file      template.config.php
* @version   1.0.0 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// generate css for layout
$css = '.wrapper { width: '.intval($this['config']->get('template_width'))."px; }\n";
		
// generate css for 3-column-layout
$maininner_width = intval($this['config']->get('template_width'));

if ($this['modules']->count('sidebar-a')) {
	$css .= '#sidebar-a { width: '.intval($this['config']->get('sidebar-a_width'))."px; }\n";
	$maininner_width = $maininner_width - intval($this['config']->get('sidebar-a_width'));
}

if ($this['modules']->count('sidebar-b')) {
	$css .= '#sidebar-b { width: '.intval($this['config']->get('sidebar-b_width'))."px; }\n";
	$maininner_width = $maininner_width - intval($this['config']->get('sidebar-b_width'));
}

$css .= '#maininner { width: '.intval($maininner_width)."px; }\n";
$sidebar_classes = 'sidebar-a-right sidebar-b-right';

if ($this['config']->get('sidebar-a') == 'left' && $this['config']->get('sidebar-b') == 'left') {
	$css .= "#maininner { float: ".($this['config']->get('direction') == 'rtl' ? 'left' : 'right')."; }\n";
	$sidebar_classes = 'sidebar-a-left sidebar-b-left';
} elseif (($this['config']->get('sidebar-a') == 'left') && $this['modules']->count('sidebar-a')) {
	$css .= "#maininner, #sidebar-a { position: relative; }\n";
	$css .= '#maininner { '.($this['config']->get('direction') == 'rtl' ? 'right' : 'left').': '.intval($this['config']->get('sidebar-a_width'))."px; }\n";
	$css .= '#sidebar-a { '.($this['config']->get('direction') == 'rtl' ? 'right' : 'left').': -'.intval($maininner_width)."px; }\n";
	$sidebar_classes = 'sidebar-a-left sidebar-b-right';
} elseif (($this['config']->get('sidebar-b') == 'left') && $this['modules']->count('sidebar-b')) {
	$css .= "#maininner, #sidebar-a, #sidebar-b { position: relative; }\n";
	$css .= '#maininner, #sidebar-a { '.($this['config']->get('direction') == 'rtl' ? 'right' : 'left').': '.intval($this['config']->get('sidebar-b_width'))."px; }\n";
	$css .= '#sidebar-b { '.($this['config']->get('direction') == 'rtl' ? 'right' : 'left').': -'.(intval($maininner_width)+intval($this['config']->get('sidebar-a_width')))."px; }\n";
	$sidebar_classes = 'sidebar-a-right sidebar-b-left';
}

// generate css for drop down menu
$css .= '#menu .dropdown { width: '.intval($this['config']->get('menu_width'))."px; }\n";
$css .= '#menu .columns2 { width: '.(2*intval($this['config']->get('menu_width')))."px; }\n";
$css .= '#menu .columns3 { width: '.(3*intval($this['config']->get('menu_width')))."px; }\n";
$css .= '#menu .columns4 { width: '.(4*intval($this['config']->get('menu_width')))."px; }\n";

// load css
$this['asset']->addFile('css', 'css:base.css');
$this['asset']->addFile('css', 'css:layout.css');
$this['asset']->addFile('css', 'css:menus.css');
$this['asset']->addString('css', $css);
$this['asset']->addFile('css', 'css:modules.css');
$this['asset']->addFile('css', 'css:tools.css');
$this['asset']->addFile('css', 'css:system.css');
$this['asset']->addFile('css', 'css:extensions.css');
$this['asset']->addFile('css', 'css:custom.css');
if (($color = $this['config']->get('color1')) && $this['path']->path("css:/color1/$color.css")) { $this['asset']->addFile('css', "css:/color1/$color.css"); }
if (($color = $this['config']->get('color2')) && $this['path']->path("css:/color2/$color.css")) { $this['asset']->addFile('css', "css:/color2/$color.css"); }
if (($font = $this['config']->get('font1')) && $this['path']->path("css:/font1/$font.css")) { $this['asset']->addFile('css', "css:/font1/$font.css"); }
if (($font = $this['config']->get('font2')) && $this['path']->path("css:/font2/$font.css")) { $this['asset']->addFile('css', "css:/font2/$font.css"); }
if (($font = $this['config']->get('font3')) && $this['path']->path("css:/font3/$font.css")) { $this['asset']->addFile('css', "css:/font3/$font.css"); }
$this['asset']->addFile('css', 'css:style.css');
if ($this['config']->get('direction') == 'rtl') $this['asset']->addFile('css', 'css:rtl.css');
$this['asset']->addFile('css', 'css:print.css');

// load fonts
$http  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$fonts = array(
	'bebas' => 'template:fonts/bebas.css',
	'droidsans' => 'template:fonts/droidsans.css',
	'yanonekaffeesatz' => $http.'://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:regular,light');

foreach (array_unique(array($this['config']->get('font1'), $this['config']->get('font2'), $this['config']->get('font3'))) as $font) {
	if (isset($fonts[$font])) {
		$this['asset']->addFile('css', $fonts[$font]);
	}
}

// set body css classes
$body_classes  = $sidebar_classes.' ';
$body_classes .= $this['system']->isBlog() ? 'isblog ' : 'noblog ';
$body_classes .= $this['config']->get('page_class');

$this['config']->set('body_classes', $body_classes);

// add javascripts
$this['asset']->addFile('js', 'js:warp.js');
$this['asset']->addFile('js', 'js:accordionmenu.js');
$this['asset']->addFile('js', 'js:dropdownmenu.js');
$this['asset']->addFile('js', 'js:template.js');

// internet explorer
if ($this['useragent']->browser() == 'msie') {

	$filters = array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor');

	// prepare assets
	$assets['ie.css']  = $this['asset']->cache('ie.css', $this['asset']->createFile('css:ie.css'), $filters);
	$assets['ie7.css'] = $this['asset']->cache('ie7.css', $this['asset']->createFile('css:ie7.css'), $filters);
	$assets['ie8.css'] = $this['asset']->cache('ie8.css', $this['asset']->createFile('css:ie8.css'), $filters);

	// versions 7 + 8
	$head[] = sprintf('<!--[if IE]><link rel="stylesheet" href="%s" /><![endif]-->', $assets['ie.css']->getUrl());
	$head[] = sprintf('<!--[if lte IE 8]><script src="%s"></script><![endif]-->', $this['path']->url('js:html5.js'));

	// version 7
	if ($this['useragent']->version() == '7.0') {
		$head[] = sprintf('<!--[if IE 7]><link rel="stylesheet" href="%s" /><![endif]-->', $assets['ie7.css']->getUrl());
	}

	// version 8
	if ($this['useragent']->version() == '8.0') {
		$head[] = sprintf('<!--[if IE 8]><link rel="stylesheet" href="%s" /><![endif]-->', $assets['ie8.css']->getUrl());
	}

}

// add $head
if (isset($head)) {
	$this['template']->set('head', implode("\n", $head));
}