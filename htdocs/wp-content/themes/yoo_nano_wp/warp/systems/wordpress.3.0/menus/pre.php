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
		
		// has ul ?
		if (!$element->first('ul:first')) {
			return false;
		}
		
		// remove id
		$element->first('ul:first')->removeAttr('id');

		// set active
		foreach ($element->find('li') as $li) {
			if ($li->attr('data-menu-active') == 2) {

				$ele = $li;
				
				while (($ele = $ele->parent()) && $ele->nodeType == XML_ELEMENT_NODE) {
					if ($ele->tag() == 'li' && !$ele->attr('data-menu-active')) {
						$ele->attr('data-menu-active', 1);
					}
				}
			}
		}

		return $element;
	}

}