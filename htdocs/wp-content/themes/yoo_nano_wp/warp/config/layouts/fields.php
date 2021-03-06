<?php
/**
* @package   Warp Theme Framework
* @file      fields.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

printf('<ul class="properties" %s>', $this['field']->attributes($attr));

$profile = preg_match('/^profile_data\[(.+)\]$/', $prefix, $matches);

foreach ($fields->find('field') as $field) {

    $name  = $field->attr('name');
    $type  = $field->attr('type');
    $label = $field->attr('label');
    $desc  = $field->attr('description');
	$value = $values->get($name, $field->attr('default'));
	$class = $profile && $matches[1] != 'default' && $values->get($name) === null ? ' class="ignore"' : null; 

	if ($type == 'separator') {
		printf('<li class="separator">%s</li>', $name);
	} else {
		printf('<li%s><div class="label">%s</div><div class="field">%s</div><div class="description">%s</div></li>', $class, $label, $this['field']->render($type, $prefix.'['.$name.']', $value, $field, compact('config')), $desc);
	}
}

if ($profile) {
	printf('<li style="display:none;"><input type="hidden" name="%s[present]" value="1" /></li>', $prefix);
}

echo '</ul>';