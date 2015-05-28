<?php
/**
* @package   Warp Theme Framework
* @file      text.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// set attributes
$attributes = $node->attributes();
$attributes['type']  = 'text';
$attributes['name']  = $name;
$attributes['value'] = $value;

printf('<input %s />', $control->attributes($attributes, array('label', 'description', 'default')));