<?php
/**
* @package   Subway
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// generate css for layout
$css[] = sprintf('body { min-width: %dpx; }', $this['config']->get('template_width')+150);
$css[] .= sprintf('.wrapper { width: %dpx; }', $this['config']->get('template_width'));

// generate css for 3-column-layout
$sidebar_a       = '';
$sidebar_b       = '';
$maininner_width = intval($this['config']->get('template_width'));
$sidebar_a_width = intval($this['config']->get('sidebar-a_width'));
$sidebar_b_width = intval($this['config']->get('sidebar-b_width'));
$rtl             = $this['config']->get('direction') == 'rtl';
$body_config	 = array();

// set widths
if ($this['modules']->count('sidebar-a')) {
	$sidebar_a = $this['config']->get('sidebar-a'); 
	$maininner_width -= $sidebar_a_width;
	$css[] = sprintf('#sidebar-a { width: %dpx; }', $sidebar_a_width);
}

if ($this['modules']->count('sidebar-b')) {
	$sidebar_b = $this['config']->get('sidebar-b'); 
	$maininner_width -= $sidebar_b_width;
	$css[] = sprintf('#sidebar-b { width: %dpx; }', $sidebar_b_width);
}

$css[] = sprintf('#maininner { width: %dpx; }', $maininner_width);

$sidebar_classes = "";

// all sidebars right
if (($sidebar_a == 'right' || !$sidebar_a) && ($sidebar_b == 'right' || !$sidebar_b)) {
	if($this['modules']->count('sidebar-a')) $sidebar_classes .= 'sidebar-a-right ';
	if($this['modules']->count('sidebar-b')) $sidebar_classes .= 'sidebar-b-right ';

// all sidebars left
} else if (($sidebar_a == 'left' || !$sidebar_a) && ($sidebar_b == 'left' || !$sidebar_b)) {
	if($this['modules']->count('sidebar-a')) $sidebar_classes .= 'sidebar-a-left ';
	if($this['modules']->count('sidebar-b')) $sidebar_classes .= 'sidebar-b-left ';
	$css[] = sprintf('#maininner { float: %s; }', $rtl ? 'left' : 'right');

// sidebar-a left and not sidebar-b 
} else if ($sidebar_a == 'left') {
	if($this['modules']->count('sidebar-a')) $sidebar_classes .= 'sidebar-a-left ';
	if($this['modules']->count('sidebar-b')) $sidebar_classes .= 'sidebar-b-right ';
	$css[] = '#maininner, #sidebar-a { position: relative; }';
	$css[] = sprintf('#maininner { %s: %dpx; }', $rtl ? 'right' : 'left', $sidebar_a_width);
	$css[] = sprintf('#sidebar-a { %s: -%dpx; }', $rtl ? 'right' : 'left', $maininner_width);

// sidebar-b left and not sidebar-a
} else if ($sidebar_b == 'left') {
	if($this['modules']->count('sidebar-a')) $sidebar_classes .= 'sidebar-a-right ';
	if($this['modules']->count('sidebar-b')) $sidebar_classes .= 'sidebar-b-left ';
	$css[] = '#maininner, #sidebar-a, #sidebar-b { position: relative; }';
	$css[] = sprintf('#maininner, #sidebar-a { %s: %dpx; }', $rtl ? 'right' : 'left', $sidebar_b_width);
	$css[] = sprintf('#sidebar-b { %s: -%dpx; }', $rtl ? 'right' : 'left', $maininner_width + $sidebar_a_width);
}

// generate css for dropdown menu
foreach (array(1 => '.dropdown', 2 => '.columns2', 3 => '.columns3', 4 => '.columns4') as $i => $class) {
	$css[] = sprintf('#menu %s { width: %dpx; }', $class, $i * intval($this['config']->get('menu_width')));
}

// load css
$this['asset']->addFile('css', 'css:base.css');
$this['asset']->addFile('css', 'css:layout.css');
$this['asset']->addFile('css', 'css:menus.css');
$this['asset']->addString('css', implode("\n", $css));
$this['asset']->addFile('css', 'css:modules.css');
$this['asset']->addFile('css', 'css:tools.css');
$this['asset']->addFile('css', 'css:system.css');
$this['asset']->addFile('css', 'css:extensions.css');
$this['asset']->addFile('css', 'css:custom.css');
if ($this['config']->get('animations')) $this['asset']->addFile('css', 'css:/animations.css');

if (($background = $this['config']->get('background'))) {

	if (strpos($background, "animated:")===0) {

		$parts = explode(':', $background);
		$body_config['animatedbg'] = $parts[1];

	} elseif ($this['path']->path("css:/background/$background.css")) {
		$this['asset']->addFile('css', "css:/background/$background.css");
	}
}

if (($texture = $this['config']->get('texture')) && $this['path']->path("css:/texture/$texture.css")) { $this['asset']->addFile('css', "css:/texture/$texture.css"); }

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
	'newciclefina' => 'template:fonts/newciclefina.css',
	'opensans' => 'template:fonts/opensans.css',
	'opensanslight' => 'template:fonts/opensanslight.css',
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
$body_classes .= ' '.$this['config']->get('transparency').' ';
$body_classes .= 'system-'.$this['config']->get('systembackground');

$this['asset']->addString('js', 'window["WarpSystemPath"]="'.$this["system"]->url.'";');
$this['asset']->addString('js', 'window["WarpThemePath"]="'.$this["path"]->url("template:").'";');


$this['config']->set('body_classes', $body_classes);

// add social buttons
$body_config['twitter'] = (int) $this['config']->get('twitter', 0);
$body_config['plusone'] = (int) $this['config']->get('plusone', 0);
$body_config['facebook'] = (int) $this['config']->get('facebook', 0);

$this['config']->set('body_config', json_encode($body_config));

// add javascripts
$this['asset']->addFile('js', 'js:warp.js');
$this['asset']->addFile('js', 'js:accordionmenu.js');
$this['asset']->addFile('js', 'js:dropdownmenu.js');
$this['asset']->addFile('js', 'js:Three.js');
$this['asset']->addFile('js', 'js:template.js');

if (isset($body_config['animatedbg'])) {
	$this['asset']->addFile('js', 'js:animatedbg.js');
}

// internet explorer
if ($this['useragent']->browser() == 'msie') {

	$filters = array('CSSImportResolver', 'CSSRewriteURL', 'CSSCompressor');

	// prepare assets
	$assets['ie.css']  = $this['asset']->cache('ie.css', $this['asset']->createFile('css:ie.css'), $filters);
	$assets['ie7.css'] = $this['asset']->cache('ie7.css', $this['asset']->createFile('css:ie7.css'), $filters);
	$assets['ie8.css'] = $this['asset']->cache('ie8.css', $this['asset']->createFile('css:ie8.css'), $filters);

	// add conditional comments
	$head[] = sprintf('<!--[if lte IE 8]>%s<script src="%s"></script><![endif]-->', ($url = $assets['ie.css']->getUrl()) ? sprintf('<link rel="stylesheet" href="%s" />', $url) : sprintf('<style>%s</style>', $assets['ie.css']->getContent($this['assetfilter']->create($filters))), $this['path']->url('js:html5.js'));
	$head[] = sprintf('<!--[if IE 7]>%s<![endif]-->', ($url = $assets['ie7.css']->getUrl()) ? sprintf('<link rel="stylesheet" href="%s" />', $url) : sprintf('<style>%s</style>', $assets['ie7.css']->getContent($this['assetfilter']->create($filters))));
	$head[] = sprintf('<!--[if IE 8]>%s<![endif]-->', ($url = $assets['ie8.css']->getUrl()) ? sprintf('<link rel="stylesheet" href="%s" />', $url) : sprintf('<style>%s</style>', $assets['ie8.css']->getContent($this['assetfilter']->create($filters))));

}

$body_config["style"] = $this['config']->get('style');

// add $head
if (isset($head)) {
	$this['template']->set('head', implode("\n", $head));
}