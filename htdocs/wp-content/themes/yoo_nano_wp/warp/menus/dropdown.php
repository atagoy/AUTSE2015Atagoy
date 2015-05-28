<?php
/**
* @package   Warp Theme Framework
* @file      dropdown.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuDropdown
		Menu base class
*/
class WarpMenuDropdown extends WarpMenu {

	/*
		Function: process

		Returns:
			Object
	*/	
	public function process($module, $element) {
		
		foreach ($element->find('ul.level2') as $ul) {
			
			// get parent li
			$li = $ul->parent();

			// get columns
			$columns = (int) $li->attr('data-menu-columns');

			if ($columns > 1) {

				$children = $ul->children('li');
				$colrows  = ceil($children->length / $columns);
				$index    = 1;
				$column   = 0;
				$i        = 0;

				foreach ($children as $child) {
					$col = intval($i / $colrows);
					
					if ($column != $col) {
						$column = $col;
						$index  = 1;
					}

					if ($li->children('ul')->length == $column) {
						$li->append('<ul class="level2"></ul>');
					}
					
					if ($column > 0) {
						$li->children('ul')->item($column)->append($child);
					}

					$child->attr('class', preg_replace('/item\d+/', "item$index", $child->attr('class')));

					$index++;
					$i++;
				}

			} else {
				$columns = 1;
			}

			// get width
			$width = (int) $li->attr('data-menu-columnwidth');
			$style = $width > 0 ? sprintf(' style="width:%spx;"', $columns * $width) : null;

			// append dropdown divs		
			$li->append(sprintf('<div class="dropdown columns%d"%s><div class="dropdown-bg"><div></div></div></div>', $columns, $style));
			$div = $li->first('div.dropdown div.dropdown-bg div:first');

			foreach ($li->children('ul') as $i => $u) {
				$div->append(sprintf('<div class="width%d column"></div>', floor(100 / $columns)))->children('div')->item($i)->append($u);
			}
		}

		return $element;
	}

}