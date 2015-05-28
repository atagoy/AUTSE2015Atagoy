<?php
/**
* @package   Warp Theme Framework
* @file      links.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

if ($ul = $this['dom']->create($module->content)->first('ul:first')) {
    echo $ul->attr('class', 'line')->html();
} else {
    echo $module->content;
}