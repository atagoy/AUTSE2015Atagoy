<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuMobile
		Mobile menu class
*/
class WarpMenuMobile extends WarpMenu {
	
	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {

		// add mobile class
		$element->first('ul:first')->addClass('menu-mobile');

		return $element;
	}

}