<?php
/**
* @package   Warp Theme Framework
* @file      stack.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

foreach ($modules as $module) {
	printf('<div class="grid-box width100 grid-v">%s</div>', $module);
}