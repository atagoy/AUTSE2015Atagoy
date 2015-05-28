<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
   Class: ItemHelper
   The Helper Class for item
*/
class ItemHelper {

	/*
		Function: translateAlias
			Set/get id to item alias.

		Parameters:
			$id - Item id
			$alias - Item alias
			$set - Set alias mode

		Returns:
			Mixed - Null or Item alias string
	*/
	function translateAlias($id, $alias = null, $set = false){

		static $aliases;

		if (!isset($aliases)) {
			$aliases = array();
		}

		// get alias
		if (!$set) {
			if (isset($aliases[$id])) {
				$alias = $aliases[$id];
			} else {
				$item  = new Item($id);
				$alias = $item->alias;
			}
		}

		// set alias
		if (empty($aliases[$id])) {
			$aliases[$id] = $alias;
		}
			
		return $alias;
	}

	/*
		Function: getItemIdByAlias
			Method to get item id by alias.
		
		Return:
			Int - The item id or 0 if not found
	*/
	function getItemIdByAlias($alias) {

		// init vars
		$db =& JFactory::getDBO();

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_ITEM
			    .' WHERE alias = '.$db->Quote($alias);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	function checkAliasExists($alias, $id = 0) {

		$xid = intval(ItemHelper::getItemIdByAlias($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		
		return false;
	}

	/*
		Function: filterElementData
			Filters item elements post data.

		Parameters:
			$data - Array

		Returns:
			Array - Array of element data
	*/
	function filterElementData($item, $data){

		$elements = $item->getElements();

		foreach ($elements as $element) {
			foreach ($element->getVarTypes() as $name => $type) {
				if (array_key_exists($name, $data)) {
					
					switch ($type) {
						case 'int':
							$data[$name] = (int) $data[$name];
							break;
						case 'string':
							$data[$name] = (string) $data[$name];
							break;
						case 'raw':
							$data[$name] = JRequest::getVar($name, '', 'post', 'string', JREQUEST_ALLOWRAW);
							break;
					}

				}
			}
		}

		return $data;
	}

}