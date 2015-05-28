<?php
/**
* @package   Warp Theme Framework
* @file      pre.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
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