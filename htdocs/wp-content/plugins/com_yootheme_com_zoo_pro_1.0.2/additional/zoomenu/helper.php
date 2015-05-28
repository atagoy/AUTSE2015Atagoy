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

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class modZooMenuHelper {

	function render(&$params, $decorator, $decorator_args = array()) {

		// get xml document
		$xml = modZooMenuHelper::getXML($params, $decorator, $decorator_args);
		
		if ($xml) {

			// set class suffix and id
			$class = $params->get('class_sfx');
			$xml->addAttribute('class', 'menu'.$class);
			if ($tag_id = $params->get('tag_id')) {
				$xml->addAttribute('id', $tag_id);
			}

			// render menu
			$result = JFilterOutput::ampReplace($xml->toString());
			$result = str_replace(array('<ul/>', '<ul />'), '', $result);
			echo $result;
		}
	}

	function getXML(&$params, $decorator, $decorator_args = array()) {

		static $instance;

		// init vars
		$user      =& JFactory::getUser();
		$instance  = empty($instance) ? 1 : $instance + 1;
		$menu_item = $params->get('menu_item', 0);
		$start	   = $params->get('start_level', 0);
		$end	   = $params->get('end_level', 0);
		$children  = $params->get('show_all_children', 0);

		// load menu item
		$item =& JTable::getInstance('menu');
		$item->load($menu_item);
		$item_params = new JParameter($item->params);
		
		$catalog_category = $item_params->get('catalog_category', '0:0');

		// parse catalog/category
		if (strpos($catalog_category, ':')) {
			list($catalog_id, $category_id) = explode(':', $catalog_category, 2);
		} else {
			list($catalog_id, $category_id) = array(0, 0);
		}		

		// get catalog/categories
		$catalog    =& Catalog::getInstance($catalog_id);
		$all_cats   = $catalog->getCategories(true);
		$item_count = $catalog->getCategoryItemCount($user->get('aid', 0));
		$categories = CategoryHelper::buildTree($all_cats, $item_count);

		// get active category and its path
		$active = JRequest::getInt('category_id', $category_id);
		$path   = isset($categories[$active]) ? $categories[$active]->getPath() : array();

		// get category subtree if start is set
		if ($start) {
			$levels = array_reverse($path);
			if (isset($levels[$start])) {
				$category_id = $levels[$start];
			} else {
				return false;
			}
		}

		// xml document
		$xml = JFactory::getXMLParser('Simple');
		$xml->loadString(modZooMenuHelper::buildXML($params, $category_id, $categories, '', $start + 1));
		$doc = &$xml->document;

		// execute decorator callback
		if ($doc && is_callable($decorator)) {
			$args = $decorator_args + array('instance' => $instance, 'start'=> $start, 'end'=> $end, 'children'=> $children, 'path'=> $path);
			$doc->map($decorator, $args);
		}

		return $doc;
	}

	function buildXML(&$params, $id, &$rows, $xml = '', $level = 0, $maxlevel = 9999) {

		// init vars
		$menu_item = $params->get('menu_item', 0);

		if (isset($rows[$id]) && $level <= $maxlevel && count($rows[$id]->_children)) {
			$xml .= '<ul>';
			foreach ($rows[$id]->_children as $child) {
				$id   = $child->id;
				$xml .= '<li id="'.$id.'" level="'.$level.'" item_count="'.$child->item_count.'" item_children="'.$child->countItems().'">';
				$xml .= '<a href="'.JRoute::_('index.php?Itemid='.$menu_item.'&option=com_zoo&view=category&category_id='.$child->id).'"><span>'.$child->name.'</span></a>';
				$xml  = modZooMenuHelper::buildXML($params, $id, $rows, $xml, $level+1, $maxlevel);
				$xml .= '</li>';
			}
			$xml .= '</ul>';
		}

		return $xml;
	}

}