<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
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