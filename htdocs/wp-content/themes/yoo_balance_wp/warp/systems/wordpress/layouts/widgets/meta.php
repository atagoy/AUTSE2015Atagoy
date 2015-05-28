<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

if ($ul = $this['dom']->create($module->content)->first('ul:first')) {
    echo $ul->attr('class', 'line line-icon')->html();
} else {
    echo $module->content;
}