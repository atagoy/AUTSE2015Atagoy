<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

foreach ($node->children('option') as $option) {

	// set attributes
	$attributes = array('type' => 'radio', 'name' => $name, 'value' => $option->attributes('value'));
	
	// is checked ?
	if ($option->attributes('value') == $value) {
		$attributes = array_merge($attributes, array('checked' => 'checked'));
	}
	
	printf('<input %s /> %s ', $control->attributes($attributes), $option->data());
}