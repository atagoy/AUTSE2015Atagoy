<?php
/**
* @package   Warp Theme Framework
* @file      menu.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenu
		Menu base class
*/
class WarpMenu{

	/*
		Function: process
			Abstract function. New implementation in child classes.

		Returns:
			Xml Object
	*/	
	public function process($xmlobj, $level=0) {
		return $xmlobj;
	}

}