<?php
/**
* @package   Warp Theme Framework
* @file      menu.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenu
		Menu base class
*/
class WarpMenu extends WarpObject {

	/*
		Function: process
			Abstract function. New implementation in child classes.

		Returns:
			Xml Object
	*/	
	function process($xmlobj, $level=0) {
		return $xmlobj;
	}

}