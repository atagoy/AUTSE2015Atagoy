<?php
/**
* @package   Warp Theme Framework
* @file      default.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuDefault
		Menu base class
*/
class WarpMenuDefault extends WarpMenu {

	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {
		self::_process($module, $element->first('ul:first'));
		return $element;
	}

	/*
		Function: _process

		Returns:
			Void
	*/
	protected static function _process($module, $element, $level = 0) {

		if ($level == 0) {
			$element->attr('class', 'menu '.$module->menu_style);
		} else {
			$element->addClass('level'.($level + 1));
		}

		foreach ($element->children('li') as $i => $li) {

			// is active ?
			if ($active = $li->attr('data-menu-active')) {
				$active = $active == 2 ? ' active current' : ' active';
			}

			// is parent ?
			$ul = $li->children('ul');
			$parent = $ul->length ? ' parent' : null;

			// set class in li
			$li->attr('class', sprintf('level%d item%d'.$parent.$active, $level + 1, $i + 1));
			
			// set class in a/span
			foreach ($li->children('a,span') as $child) {

				// get title
				$title = $child->first('span:first');

				// set subtile
				$subtitle = explode('||', $title->text());
				
				if (count($subtitle) == 2) {
					$li->addClass('hassubtitle');
					$title->html(sprintf('<span class="title">%s</span><span class="subtitle">%s</span>', trim($subtitle[0]), trim($subtitle[1])));
				}

				// set image
				if ($image = $li->attr('data-menu-image')) {
					$title->prepend(sprintf('<span class="icon" style="background-image: url(%s);"> </span>', $image));
				}

				$child->addClass(sprintf('level%d'.$parent.$active, $level + 1));
			}

			// process submenu
			if ($ul->length) {
				self::_process($module, $ul->item(0), $level + 1);
			}
		}

	}

}