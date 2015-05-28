<?php
/**
* @package   Planet Template
* @file      presets.php
* @version   5.5.1 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
 * Presets
 */

$default_preset = array();

$warp->config->addPreset('default', 'Ice Planet: Blue',array_merge($default_preset,array(
	'color' => 'default'
)));

$warp->config->addPreset('blackblue', 'Dark Planet: Blue', array_merge($default_preset,array(
	'color' => 'blackblue'
)));

$warp->config->addPreset('blackgreen', 'Dark Planet: Green', array_merge($default_preset,array(
	'color' => 'blackgreen'
)));

$warp->config->addPreset('blacklilac', 'Dark Planet: Lilac', array_merge($default_preset,array(
	'color' => 'blacklilac'
)));

$warp->config->addPreset('blackorange', 'Dark Planet: Orange', array_merge($default_preset,array(
	'color' => 'blackorange'
)));

$warp->config->addPreset('whiteorange', 'Ice Planet: Orange', array_merge($default_preset,array(
	'color' => 'whiteorange'
)));

$warp->config->addPreset('greenorange', 'Battle Planet: Orange', array_merge($default_preset,array(
	'color' => 'greenorange'
)));

$warp->config->addPreset('greenwhite', 'Battle Planet: White', array_merge($default_preset,array(
	'color' => 'greenwhite'
)));

$warp->config->addPreset('bluewhite', 'Water Planet: White', array_merge($default_preset,array(
	'color' => 'bluewhite'
)));

$warp->config->addPreset('blueyellow', 'Water Planet: Yellow', array_merge($default_preset,array(
	'color' => 'blueyellow'
)));

$warp->config->applyPreset();