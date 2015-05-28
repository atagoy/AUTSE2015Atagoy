<?php
/**
* @package   Warp Theme Framework
* @file      pre.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuPre
		Menu base class
*/
class WarpMenuPre extends WarpMenu {

	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {
		return $element;
	}

}