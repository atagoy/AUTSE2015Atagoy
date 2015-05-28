<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
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