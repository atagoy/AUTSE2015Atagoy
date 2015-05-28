<?php
/**
* @package   Vanilla
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
 * Presets
 */

$default_preset = array();

$warp->config->addPreset('preset01', 'Plain Blue', array_merge($default_preset,array(
	'style' => 'plain',
	'bgcolor' => 'blue',
	'color' => 'default',
	'contentwrapper' => false,
	'font' => 'default',
	'load_googlefonts' => true
)));

$warp->config->addPreset('preset02', 'Plain Green', array_merge($default_preset,array(
	'style' => 'plain',
	'bgcolor' => 'green',
	'color' => 'orange',
	'contentwrapper' => true,
	'font' => 'lucida',
	'load_googlefonts' => false
)));

$warp->config->addPreset('preset03', 'Paper Blue', array_merge($default_preset,array(
	'style' => 'paper',
	'bgcolor' => 'blue',
	'color' => 'red',
	'contentwrapper' => false,
	'font' => 'default',
	'load_googlefonts' => true
)));

$warp->config->addPreset('preset04', 'Paper Green', array_merge($default_preset,array(
	'style' => 'paper',
	'bgcolor' => 'green',
	'color' => 'lilac',
	'contentwrapper' => true,
	'font' => 'default',
	'load_googlefonts' => false
)));

$warp->config->addPreset('preset05', 'Fabric Red', array_merge($default_preset,array(
	'style' => 'fabric',
	'bgcolor' => 'red',
	'color' => 'turquoise',
	'contentwrapper' => false,
	'font' => 'georgia',
	'load_googlefonts' => true
)));

$warp->config->addPreset('preset06', 'Fabric Brown', array_merge($default_preset,array(
	'style' => 'fabric',
	'bgcolor' => 'brown',
	'color' => 'green',
	'contentwrapper' => true,
	'font' => 'georgia',
	'load_googlefonts' => false
)));


$warp->config->addPreset('preset07', 'Leather Red', array_merge($default_preset,array(
	'style' => 'leather',
	'bgcolor' => 'red',
	'color' => 'turquoise',
	'contentwrapper' => false,
	'font' => 'default',
	'load_googlefonts' => true
)));

$warp->config->addPreset('preset08', 'Leather Black', array_merge($default_preset,array(
	'style' => 'leather',
	'bgcolor' => 'black',
	'color' => 'pink',
	'contentwrapper' => true,
	'font' => 'trebuchet',
	'load_googlefonts' => false
)));


$warp->config->applyPreset();