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
   Class: TableItem
      The Table Class for item. Manages the database operations.
*/
class TableItem extends YTable {

	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $type_id			= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $alias				= null;
	/** @var datetime */
	var $created			= null;
	/** @var datetime */
	var $modified			= null;
	/** @var datetime */
	var $modified_by		= null;
	/** @var datetime */
	var $publish_up			= null;
	/** @var datetime */
	var $publish_down		= null;
	/** @var int */
	var $hits				= null;
	/** @var int */
	var $state 				= null;
	/** @var string */
	var $metakey			= null;
	/** @var string */
	var $metadata			= null;
	/** @var string */
	var $metadesc 			= null;
	/** @var int */
	var $access				= null;
	/** @var int */
	var $created_by			= null;
	/** @var string */
	var $created_by_alias	= null;
	/** @var string */
	var $search_data		= null;
	/** @var string */
	var $params				= null;	

	/*
 	Function: __construct
 	  Constructor.

		Parameters:
	      db - A database connector object.
	*/
	function __construct(&$db) {
		parent::__construct(ZOO_TABLE_ITEM, 'id', $db);
	}

	/*
 	Function: check
	  Validate object vars. Returns True if the object is ok

	   Returns:
	      Boolean.
	*/
	function check() {

		if (!(is_numeric($this->type_id) && $this->type_id != 0)) {
			$this->setError(JText::_('Invalid type id'));
			return false;
		}
		
		if ($this->name == '') {
			$this->setError(JText::_('Invalid name'));
			return false;
		}
	
		if ($this->alias == '' || preg_match('/[^A-Za-z0-9_-]/', $this->alias)) {
			$this->setError(JText::_('Invalid alias'));
			return false;
		}

		if (ItemHelper::checkAliasExists($this->alias, $this->id)) {
			$this->setError(JText::_('Alias already exists, please choose a unique alias'));
			return false;
		}
		
		return true;
	}

	/*
		Function: delete
			Override. Delete object from database table.

		Returns:
			Boolean.
	*/
	function delete() {

		// delete item to category relations
		$table =& JTable::getInstance('categoryitem', 'Table');
		if (!$table->deleteItemCategoryRelations($this->id)) {
			$this->setError($table->getError());
			return false;
		}

		// delete related element data rows
		$type  =& Type::getInstance($this->type_id);
		$query = "DELETE FROM ".$type->getTableName()
				." WHERE item_id = ".$this->id;
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return parent::delete();
	}

	/*
		Function: getByTypeId
			Method to get types related items.

		Parameters:
			$type_id - Type id

		Returns:
			Array - Items
	*/
	function getByTypeId($type_id){
 		$query = "SELECT a.*"
		        ." FROM ".$this->getTableName()." AS a"
		        ." WHERE a.type_id = ".(int) $type_id;

		return $this->queryObjectList($query, 'id');		
	}

	/*
		Function: getTypeItemCount
			Method to get types related item count.

		Parameters:
			$type_id - Type id

		Returns:
			Integer - Item count
	*/
	function getTypeItemCount($type_id){
 		$query = "SELECT count(a.id) AS item_count"
		        ." FROM ".$this->getTableName()." AS a"
		        ." WHERE a.type_id = ".(int) $type_id;
		
		return $this->queryResult($query);
	}
	
	/*
		Function: getFromCategory
			Method to retrieve all items of a category.

		Parameters:
			$catalog_id - Catalog id
			$category_id - Category id(s)

		Returns:
			Array - Array of items
	*/
	function &getFromCategory($catalog_id, $category_id, $published = false, $access_id = false, $orderby = "", $offset = 0, $limit = 0){

		$date =& JFactory::getDate();
		$now  = $this->_db->Quote($date->toMySQL());
		$null = $this->_db->Quote($this->_db->getNullDate());
		
		$query = "SELECT a.*"
			." FROM ".$this->getTableName()." AS a"
			." LEFT JOIN ".ZOO_TABLE_CATEGORY_ITEM." AS b ON a.id = b.item_id"
			." WHERE b.catalog_id = ".(int) $catalog_id
			." AND b.category_id ".(is_array($category_id) ? " IN (".implode(",", $category_id).")" : " = ".(int) $category_id)
			.($published == true ? " AND a.state = 1" : "")
			.($access_id !== false ? " AND a.access <= ".(int) $access_id : "")
			." AND (a.publish_up = ".$null." OR a.publish_up <= ".$now.")"
			." AND (a.publish_down = ".$null." OR a.publish_down >= ".$now.")"
			.($orderby != "" ? " ORDER BY ".$orderby : "");

		return $this->queryObjectList($query, 'id', $offset, $limit);
	}	

}