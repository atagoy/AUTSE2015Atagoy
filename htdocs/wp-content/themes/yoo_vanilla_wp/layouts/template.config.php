<?php
/**
* @package   Vanilla
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// init vars
$config =& $this->warp->getHelper('config');
$style  = $config->get('style');
$bgcolor  = $config->get('bgcolor');
$color  = $config->get('color');
		
// set template css
$css = 'body { min-width: '.intval($config->get('template_width')).$config->get('template_width_unit')."; }\n";
$css .= '.wrapper { width: '.intval($config->get('template_width')).$config->get('template_width_unit')."; }\n";
		
// 3-column-layout
if ($this->warp->modules->count('left')) {
	$css .= '#main-shift { margin-left: '.(intval($config->get('left_width')) + intval($config->get('left_space')))."px; }\n";
	$css .= '#left { width: '.intval($config->get('left_width'))."px; }\n";
}

if ($this->warp->modules->count('right')) {
	if (!$this->warp->modules->count('left'))  { $config->set('left_width', '0'); }
	$css .= '#main-shift { margin-right: '.(intval($config->get('right_width')) + intval($config->get('right_space')))."px; }\n";
	$css .= '#right { width: '.intval($config->get('right_width')).'px; margin-left: -'.(intval($config->get('left_width')) + intval($config->get('right_width')))."px; }\n";
}

// inner 3-column-layout
if ($this->warp->modules->count('contentleft')) {
	$css .= '#content-shift { margin-left: '.(intval($config->get('contentleft_width')) + intval($config->get('contentleft_space')))."px; }\n";
	$css .= '#contentleft { width: '.intval($config->get('contentleft_width'))."px; }\n";
}		

if ($this->warp->modules->count('contentright')) {
	if (!$this->warp->modules->count('contentleft')) { $config->set('contentleft_width', '0'); }
	$css .= '#content-shift { margin-right: '.(intval($config->get('contentright_width')) + intval($config->get('contentright_space')))."px; }\n";
	$css .= '#contentright { width: '.intval($config->get('contentright_width')).'px; margin-left: -'.(intval($config->get('contentleft_width')) + intval($config->get('contentright_width')))."px; }\n";
}

// drop down menu
$css .= '#menu .dropdown { width: '.intval($config->get('menu_width'))."px; }\n";
$css .= '#menu .columns2 { width: '.(2*intval($config->get('menu_width')))."px; }\n";
$css .= '#menu .columns3 { width: '.(3*intval($config->get('menu_width')))."px; }\n";
$css .= '#menu .columns4 { width: '.(4*intval($config->get('menu_width')))."px; }\n";

$this->warp->stylesheets->addDeclaration($css);
$this->warp->stylesheets->add('css:reset.css');
$this->warp->stylesheets->add('css:layout.css');
$this->warp->stylesheets->add('css:typography.css');
$this->warp->stylesheets->add('css:menus.css');
$this->warp->stylesheets->add('css:modules.css');
$this->warp->stylesheets->add('css:system.css');
$this->warp->stylesheets->add('css:extensions.css');
$this->warp->stylesheets->add('css:style.css');

if ($config->get('direction') == 'rtl') {
	$this->warp->stylesheets->add('css:rtl.css');
}
	
$this->warp->stylesheets->add("css:styles/{$style}-{$bgcolor}.css");
$this->warp->stylesheets->add("css:styles/color-{$color}.css");
$this->warp->stylesheets->add("css:styles/{$style}.css");
$this->warp->stylesheets->add('css:custom.css');

// set google fonts
if ($config->get('load_googlefonts', false)) {
	
	$httpmode = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	
	$this->warp->config->set('webfonts','webfonts');
	$this->warp->stylesheets->add($httpmode.'://fonts.googleapis.com/css?family=Lobster');	
}


$config->set('contentwrapper-class', $config->get("contentwrapper") ? "contentwrapper":"");

// javascripts
$this->warp->javascripts->addDeclaration('var Warp = Warp || {}; Warp.Settings = '.json_encode(array('itemColor' => $config->get('itemcolor'))).';');
$this->warp->javascripts->add('js:warp.js');
$this->warp->javascripts->add('js:accordionmenu.js');
$this->warp->javascripts->add('js:dropdownmenu.js');
$this->warp->javascripts->add('js:spotlight.js');
$this->warp->javascripts->add('js:template.js');

// ie8 hacks
if ($this->warp->useragent->browser() == 'msie' && $this->warp->useragent->version() == '8.0') {
	$css = '<link rel="stylesheet" href="%s" type="text/css" />';
	$ie8[] = sprintf($css, $this->warp->path->url('css:ie8hacks.css'));
	$head[] = '<!--[if IE 8]>'.implode("\n", $ie8).'<![endif]-->';
}

// ie7 hacks
if ($this->warp->useragent->browser() == 'msie' && $this->warp->useragent->version() == '7.0') {
	$css = '<link rel="stylesheet" href="%s" type="text/css" />';
	$ie7[] = sprintf($css, $this->warp->path->url('css:ie7hacks.css'));
	$head[] = '<!--[if IE 7]>'.implode("\n", $ie7).'<![endif]-->';
}

// ie6 hacks
if ($this->warp->useragent->browser() == 'msie' && $this->warp->useragent->version() == '6.0') {
	$css = '<link rel="stylesheet" href="%s" type="text/css" />';
	$js = '<script type="text/javascript" src="%s"></script>';
	$ie6[] = sprintf($css, $this->warp->path->url('css:ie6hacks.css'));

	$ie6[] = sprintf($js, $this->warp->path->url('js:ie6fix.js'));
	$ie6[] = sprintf($js, $this->warp->path->url('js:ie6png.js'));
	$ie6[] = sprintf($js, $this->warp->path->url('js:ie6.js'));
	$head[] = '<!--[if IE 6]>'.implode("\n", $ie6).'<![endif]-->';
}

// add $head
if (isset($head)) {
	$this->warp->template->set('head', implode("\n", $head));
}

// set css class for specific columns
$columns = null;
if ($this->warp->modules->count('left')) $columns .= 'column-left';
if ($this->warp->modules->count('right')) $columns .= ' column-right';
if ($this->warp->modules->count('contentleft')) $columns .= ' column-contentleft';
if ($this->warp->modules->count('contentright')) $columns .= ' column-contentright';

$config->set('columns', $columns);