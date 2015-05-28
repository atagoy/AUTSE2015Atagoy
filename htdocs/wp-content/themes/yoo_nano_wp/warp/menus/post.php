<?php
/**
* @package   Warp Theme Framework
* @file      post.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
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
			$li->removeAttr('data-menu-active')->removeAttr('data-menu-columns')->removeAttr('data-menu-columnwidth')->removeAttr('data-menu-image');
		}

		return $element;
	}

}