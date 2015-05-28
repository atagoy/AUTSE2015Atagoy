<?php
/**
* @package   Warp Theme Framework
* @file      position.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

printf('<select %s>', $control->attributes(compact('name')));

foreach ($this['system']->xml->find('positions > position') as $position) {

	// set attributes
	$attributes = array('value' => $position->text());

	// is checked ?
	if ($position->text() == $value) {
		$attributes = array_merge($attributes, array('selected' => 'selected'));
	}

	printf('<option %s>%s</option>', $control->attributes($attributes), $position->text());
}

printf('</select>');