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
   Class: CatalogHelper
   The Helper Class for catalog
*/
class CatalogHelper {
	
	/*
		Function: translateAlias
			Set/get id to catalog alias.

		Parameters:
			$id - Catalog id
			$alias - Catalog alias
			$set - Set alias mode

		Returns:
			Mixed - Null or Catalog alias string
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
				$cat   = new Catalog($id);
				$alias = $cat->alias;
			}
		}

		// set alias
		if (empty($aliases[$id])) {
			$aliases[$id] = $alias;
		}
			
		return $alias;
	}

	/*
		Function: getCatalogIdByAlias
			Method to get catalog id by alias.
		
		Return:
			Int - The catalog id or 0 if not found
	*/
	function getCatalogIdByAlias($alias) {

		// init vars
		$db =& JFactory::getDBO();

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_CATALOG
			    .' WHERE alias = '.$db->Quote($alias);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	function checkAliasExists($alias, $id = 0) {

		$xid = intval(CatalogHelper::getCatalogIdByAlias($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		
		return false;
	}

}