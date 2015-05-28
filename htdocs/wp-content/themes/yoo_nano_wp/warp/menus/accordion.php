<?php
/**
* @package   Warp Theme Framework
* @file      accordion.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuAccordion
		Menu base class
*/
class WarpMenuAccordion extends WarpMenu {

	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {

		$remove = array();

		// remove all but active or seperators (spans)
		foreach ($element->find('li.level1 ul') as $ul) {
			if ($ul->parent()->hasClass('active') || $ul->prev()->tag() == 'span') continue;
			$remove[] = $ul;
		}

		foreach ($remove as $ul) {
			$ul->parent()->removeChild($ul);
		}

		return $element;
	}

}