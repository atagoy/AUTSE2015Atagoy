<?php
/**
* @package   Warp Theme Framework
* @file      field.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: FieldWarpHelper
		Field helper class. Renders HTML input fields.
*/
class FieldWarpHelper extends WarpHelper {

	/*
		Function: render
			Render a field like text, select or radio button

		Returns:
			String
	*/
	public function render($type, $name, $value, $node, $args = array()) {
		
		// set vars
		$args['control'] = $this;
		$args['name']    = $name;
		$args['value']   = $value;
		$args['node']    = $node;
		
		return $this['template']->render('config:layouts/fields/'.$type, $args);
	}

	/*
		Function: attributes
			Create html attribute string from array

		Returns:
			String
	*/
	public function attributes($attributes, $ignore = array()) {

		$attribs = array();
		$ignore  = (array) $ignore;
		
		foreach ($attributes as $name => $value) {
			if (in_array($name, $ignore)) continue;

			$attribs[] = sprintf('%s="%s"', $name, htmlspecialchars($value));
		}
		
		return implode(' ', $attribs);
	}

}