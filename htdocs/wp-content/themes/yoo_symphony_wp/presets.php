<?php
/**
* @package   Symphony Template
* @file      presets.php
* @version   5.5.0 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
 * Presets
 */

$default_preset = array();

$warp->config->addPreset('default', 'Grey', array_merge($default_preset,array(
	'color' => 'default'
)));

$warp->config->addPreset('red', 'Red',  array_merge($default_preset,array(
	'color' => 'red'
)));

$warp->config->addPreset('green', 'Green',  array_merge($default_preset,array(
	'color' => 'green'
)));

$warp->config->addPreset('beige', 'Beige',  array_merge($default_preset,array(
	'color' => 'beige'
)));

$warp->config->addPreset('blue', 'Blue',  array_merge($default_preset,array(
	'color' => 'blue'
)));

$warp->config->addPreset('brown', 'Brown',  array_merge($default_preset,array(
	'color' => 'brown'
)));

$warp->config->addPreset('black', 'Black',  array_merge($default_preset,array(
	'color' => 'black'
)));

$warp->config->addPreset('orange', 'Orange',  array_merge($default_preset,array(
	'color' => 'orange'
)));

$warp->config->addPreset('lilac', 'Lilac',  array_merge($default_preset,array(
	'color' => 'lilac'
)));

$warp->config->addPreset('landscape', 'Landscape',  array_merge($default_preset,array(
	'color' => 'landscape'
)));

$warp->config->addPreset('cooking', 'Cooking',  array_merge($default_preset,array(
	'color' => 'cooking'
)));

$warp->config->addPreset('gaming', 'Gaming',  array_merge($default_preset,array(
	'color' => 'gaming'
)));

$warp->config->applyPreset();