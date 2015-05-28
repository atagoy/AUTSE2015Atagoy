<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// set attributes
$attributes = $node->attributes();
$attributes['type']  = 'text';
$attributes['name']  = $name;
$attributes['value'] = $value;

printf('<input %s />', $control->attributes($attributes, array('label', 'description', 'default')));