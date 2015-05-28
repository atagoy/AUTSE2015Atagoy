<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
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
			Xml Object
	*/	
	function process(&$module, &$xmlobj) {
		return $xmlobj;
	}

}