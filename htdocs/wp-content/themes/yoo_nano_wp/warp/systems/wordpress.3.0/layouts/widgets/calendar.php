<?php
/**
* @package   Warp Theme Framework
* @file      calendar.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

if ($table = $this['dom']->create($module->content)->first('table:first')) {
	
	foreach ($table->attr() as $name => $value) {
		$table->removeAttr($name);
	}
	
    echo $table->attr('class', 'calendar')->html();

} else {
    echo $module->content;
}