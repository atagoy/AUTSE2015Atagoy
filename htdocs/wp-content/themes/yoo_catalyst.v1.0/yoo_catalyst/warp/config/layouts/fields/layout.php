<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/
printf('<select %s>', $this['field']->attributes(compact('name')));

$selected_value = $this['field']->attributes(compact('value'));

foreach ($this['path']->files("layouts:modules/layouts") as $filename) {

    $option = rtrim($filename, ".php");
    $readable = ucfirst($option);

    $selected = "";
    if ($option == $value) {
        $selected = "selected=\"true\"";
    }

    printf("<option value=\"%s\" %s>%s</option>", $option, $selected, $readable); 

}

printf('</select>');