<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/


/*
	Class: WarpMenuPost
		Menu base class
*/
class WarpMenuPost extends WarpMenu {
	
	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {

		foreach ($element->find('li') as $li) {
			$li->removeAttr('data-id')->removeAttr('data-menu-active')->removeAttr('data-menu-columns')->removeAttr('data-menu-columnwidth')->removeAttr('data-menu-image');
		}

		return $element;
	}

}