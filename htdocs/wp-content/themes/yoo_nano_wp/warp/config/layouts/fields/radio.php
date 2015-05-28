<?php
/**
* @package   Warp Theme Framework
* @file      radio.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

foreach ($node->children('option') as $option) {

	// set attributes
	$attributes = array('type' => 'radio', 'name' => $name, 'value' => $option->attr('value'));
	
	// is checked ?
	if ($option->attr('value') == $value) {
		$attributes = array_merge($attributes, array('checked' => 'checked'));
	}
	
	printf('<input %s /> %s ', $control->attributes($attributes), $option->text());
}