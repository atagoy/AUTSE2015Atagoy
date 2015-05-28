<?php
/**
* @package   Zoo Menu Module
* @version   1.0.0 2009-03-19 17:25:08
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!defined('modZooMenuXMLCallback')) {

	function modZooMenuXMLCallback(&$node, $args) {

		static $ordering;

		// remove child items deeper than end level
		if (($args['end']) && ($node->attributes('level') >= $args['end'])) {
			$children = $node->children();
			foreach ($node->children() as $child) {
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}

		if ($node->name() == 'ul') {
			
			// remove empty categories
			foreach ($node->children() as $child) {
				if (!$child->attributes('item_children')) {
					$node->removeChild($child);
				}
			}
			
			// set first/last for li
			$children_count = count($node->children());
			$children_index = 0;
			foreach ($node->children() as $child) {
				if ($children_index == 0) $child->addAttribute('first', 1);
				if ($children_index == $children_count - 1) $child->addAttribute('last', 1);
				$children_index++;
			}
			
			// set ul level
			if (isset($node->_children[0])) {
				$css = 'level' . ($node->_children[0]->attributes('level') - $args['start']);
				$node->attributes('class') ? $node->addAttribute('class', $node->attributes('class') . ' ' . $css) : $node->addAttribute('class', $css);
			}
		}

		// set item styling
		if ($node->name() == 'li') {

			$level = $node->attributes('level') - $args['start'];
			$ordering[$args['instance']][$level] = isset($ordering[$args['instance']][$level]) ? $ordering[$args['instance']][$level] + 1 : 1;

			$css = 'level'.$level.' item'.$ordering[$args['instance']][$level];
			if ($node->attributes('first')) $css .= ' first';
			if ($node->attributes('last')) $css .= ' last';
			if (isset($node->ul) && ($args['end'] == 0 || $node->attributes('level') < $args['end'])) $css .= ' parent';
			if (in_array($node->attributes('id'), $args['path'])) $css .= ' active';
			if (isset($args['path'][0]) && $node->attributes('id') == $args['path'][0]) $css .= ' current';

			// add a/span css classes
			if (isset($node->_children[0])) {
				$node->_children[0]->attributes('class') ? $node->_children[0]->addAttribute('class', $node->_children[0]->attributes('class') . ' ' . $css) : $node->_children[0]->addAttribute('class', $css);
			}

			// add item css classes
			$node->attributes('class') ? $node->addAttribute('class', $node->attributes('class') . ' ' . $css) : $node->addAttribute('class', $css);
		}

		// remove inactive child categories
		if (!(isset($args['path']) && in_array($node->attributes('id'), $args['path']))) {
			if (isset($args['children']) && !$args['children'])	{
				$children = $node->children();
				foreach ($node->children() as $child) {
					if ($child->name() == 'ul') {
						$node->removeChild($child);
					}
				}
			}
		}

		$node->removeAttribute('id');
		$node->removeAttribute('level');
		$node->removeAttribute('first');
		$node->removeAttribute('last');
		$node->removeAttribute('item_count');
		$node->removeAttribute('item_children');
	}

	define('modZooMenuXMLCallback', true);
}

modZooMenuHelper::render($params, 'modZooMenuXMLCallback');
