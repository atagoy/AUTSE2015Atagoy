<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
   Class: CategoryHelper
   The Helper Class for category
*/
class CategoryHelper {

	/*
		Function: translateAlias
			Set/get id to category alias.

		Parameters:
			$id - Category id
			$alias - Category alias
			$set - Set alias mode

		Returns:
			Mixed - Null or Category alias string
	*/
	function translateAlias($id, $alias = null, $set = false){

		static $aliases;

		if (!isset($aliases)) {
			$aliases = array(0 => ZOO_ROOT_ALIAS);
		}

		// get alias
		if (!$set) {
			if (isset($aliases[$id])) {
				$alias = $aliases[$id];
			} else {
				$categ = new Category($id);
				$alias = $categ->alias;
			}
		}

		// set alias
		if (empty($aliases[$id])) {
			$aliases[$id] = $alias;
		}
	
		return $alias;
	}

	/*
		Function: getCategoryIdByAlias
			Method to get category id by alias.
		
		Return:
			Int - The category id or 0 if not found
	*/
	function getCategoryIdByAlias($alias) {

		// init vars
		$db =& JFactory::getDBO();

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_CATEGORY
			    .' WHERE alias = '.$db->Quote($alias);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	function checkAliasExists($alias, $id = 0) {

		$xid = intval(CategoryHelper::getCategoryIdByAlias($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}

		if ($alias == ZOO_ROOT_ALIAS) {
			return true;
		}
		
		return false;
	}

	/*
		Function: buildTree
			Build category tree.

		Parameters:
			$rows - Category data from database table
			$item_count - Category related item count
			$alpha_index - Create a alpha index

		Returns:
			Array - Category list
	*/
	function buildTree(&$rows, $item_count = array(), $alpha_index = false) {
		
		// create category array
		$categories = array();

		// create alpha index
		if ($alpha_index) {
			$index    = array();
			$index[0] = array();
			for ($i=97; $i <= 122; $i++) { 
				$index[chr($i)] = array();
			}
		}

		// create root category
		$root = new Category();
		$root->id = 0;
		$root->name = 'ROOT';
		$categories[0] =& $root;

		foreach ($rows as $row) {
			if (!isset($categories[$row->id])) {
				$categories[$row->id] = new Category();
			}

			if (!isset($categories[$row->parent])) {
				$categories[$row->parent] = new Category();
			}

			// bind category data
			$categories[$row->id]->bind($row);
			
			// set item count
			if (isset($item_count[$row->id])) {
				$categories[$row->id]->item_count = $item_count[$row->id]->item_count;
			}

			// set parent and child relations
			$categories[$row->id]->_parent = $categories[$row->parent];
			$categories[$row->parent]->_children[] = $categories[$row->id];

			// alpha indexing
			if ($alpha_index && $categories[$row->id]->item_count) {
				$name  = $categories[$row->id]->getName();
				$first = ord(strtolower($name[0]));
				if ($first >= 97 && $first <= 122) {
					$index[chr($first)][] = $categories[$row->id];
				} else {
					$index['0'][] = $categories[$row->id];
				}
			}
		}

		// add alpha indexing
		if ($alpha_index) {
			$categories['index'] = $index;
		}
		
		return $categories;
	}

	/*
		Function: buildTreeList
			Build category list which reflects the tree structure.

		Parameters:
			$id - Category id to start
			$rows - Category data tree created with CategoryHelper::buildTree()
			$list - Category tree list return value
			$prefix - Sublevel prefix
			$spacer - Spacer
			$indent - Indent
			$level - Start level
			$maxlevel - Maximum level depth

		Returns:
			Array - Category tree list
	*/
	function buildTreeList($id, &$rows, $list = array(), $prefix = '<sup>|_</sup>&nbsp;', $spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $indent = '', $level = 0, $maxlevel = 9999) {

		if (@$rows[$id] && $level <= $maxlevel) {
			foreach ($rows[$id]->_children as $child) {

				// set treename
				$id        = $child->id;
				$list[$id] = $child;
				$list[$id]->treename = $indent.($indent == '' ? $child->name : $prefix.$child->name);
				$list = CategoryHelper::buildTreeList($id, $rows, $list, $prefix, $spacer, $indent.$spacer, $level+1, $maxlevel);
			}
		}

		return $list;
	}

	/*
		Function: parent
			Build the select list for parent category item.

		Parameters:
			$row - Category object

		Returns:
			String - Parent category selectbox
	*/
	function parent(&$row) {

		$db =& JFactory::getDBO();

		// if a not a new item, lets set the category item id
		$condition = $row->id ? ' AND id != '.(int) $row->id : null;

		// in case the parent was null
		if (!$row->parent) {
			$row->parent = 0;
		}

		// get a list of the category items, excluding the current category item and its child elements
		$query = 'SELECT *'
				.' FROM '.ZOO_TABLE_CATEGORY
				.' WHERE catalog_id = '.$row->catalog_id
				.$condition 
				.' ORDER BY parent, ordering';
		$db->setQuery($query);
		$rows       = $db->loadObjectList();
		$categories = CategoryHelper::buildTree($rows);
		$list       = CategoryHelper::buildTreeList(0, $categories);

		$options    = array();
		$options[]  = JHTML::_('select.option', '0', JText::_('Top'));
		foreach ($list as $category) {
			$options[] = JHTML::_('select.option', $category->id, '&nbsp;&nbsp;&nbsp;'.$category->treename);
		}

		return JHTML::_('select.genericlist', $options, 'parent', 'class="inputbox" size="10"', 'value', 'text', $row->parent);
	}

	/*
		Function: hasNeighbor
			Checks the neighbors of a category item.

		Parameters:
			$list - Category data from database table
			$item - Category object
			$dir - Direction

		Returns:
			Boolean - True on success
	*/
	function hasNeighbor($list, $item, $dir) {
		foreach ($list as $key => $tmpitem) {
			if($item->parent == $tmpitem->parent){
				$tmparray[] = $tmpitem;
			}
		}

		for ($i = 0; $i < sizeof($tmparray); $i++) {
			if($tmparray[$i]->id == $item->id){
				if ($dir) {
					if ($i < (sizeof($tmparray)-1)) return true;
				} else {
					if ($i > 0) return true;
				}
			}
		}

		return false;
	}

}