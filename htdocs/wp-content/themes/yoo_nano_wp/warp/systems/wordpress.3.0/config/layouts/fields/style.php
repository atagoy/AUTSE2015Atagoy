<?php
/**
* @package   Warp Theme Framework
* @file      style.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

printf('<select %s>', $control->attributes(array('class' => 'widget-style', 'name' => $name)));

foreach ($node->children('option') as $option) {

	// set attributes
	$attributes = array('value' => $option->attr('value'));
	
	// is checked ?
	if ($option->attr('value') == $value) {
		$attributes = array_merge($attributes, array('selected' => 'selected'));
	}

	printf('<option %s>%s</option>', $control->attributes($attributes), $option->attr('name'));
	
	// has colors ?
	if ($colors = $option->children('color')) {
		$selects[] = sprintf('<select class="widget-color %s">', $option->attr('value'));

		foreach ($colors as $color) {

			// set attributes
			$attributes = array('value' => $color->attr('value'));

			// is checked ?
			if (isset($widget->options['color']) && $color->attr('value') == $widget->options['color']) {
				$attributes = array_merge($attributes, array('selected' => 'selected'));
			}

			$selects[] = sprintf('<option %s>%s</option>', $control->attributes($attributes), $color->attr('name'));
		}

		$selects[] = '</select>';
	}
	
}

printf('</select>');

if (isset($selects)) {
	echo implode("", $selects);
}