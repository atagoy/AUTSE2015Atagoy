<?php
/**
* @package   Warp Theme Framework
* @file      equal.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

switch (count($modules)) {

	case 1:
		printf('<div class="grid-box width100 grid-h">%s</div>', $modules[0]);
		break;

	case 2:
		printf('<div class="grid-box width50 grid-h">%s</div>', $modules[0]);
		printf('<div class="grid-box width50 grid-h">%s</div>', $modules[1]);
		break;

	case 3:
		printf('<div class="grid-box width33 grid-h">%s</div>', $modules[0]);
		printf('<div class="grid-box width33 grid-h">%s</div>', $modules[1]);
		printf('<div class="grid-box width33 grid-h">%s</div>', $modules[2]);
		break;

	case 4:
		printf('<div class="grid-box width25 grid-h">%s</div>', $modules[0]);
		printf('<div class="grid-box width25 grid-h">%s</div>', $modules[1]);
		printf('<div class="grid-box width25 grid-h">%s</div>', $modules[2]);
		printf('<div class="grid-box width25 grid-h">%s</div>', $modules[3]);
		break;

	case 5:
		printf('<div class="grid-box width20 grid-h">%s</div>', $modules[0]);
		printf('<div class="grid-box width20 grid-h">%s</div>', $modules[1]);
		printf('<div class="grid-box width20 grid-h">%s</div>', $modules[2]);
		printf('<div class="grid-box width20 grid-h">%s</div>', $modules[3]);
		printf('<div class="grid-box width20 grid-h">%s</div>', $modules[4]);
		break;

}