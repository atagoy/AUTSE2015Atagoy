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
   Class: TableCategoryItem
   The Table Class for categoryitem. Manages the database operations.
*/
class TableCategoryItem extends YTable {

	/** @var int Primary key */
	var $category_id		= null;
	/** @var int Primary key */
	var $item_id			= null;

	/*
 	Function: __construct
 	  Constructor.

		Parameters:
	      db - A database connector object.
	*/
	function __construct(&$db) {
		parent::__construct(ZOO_TABLE_CATEGORY_ITEM, '', $db);
	}

	/*
 	Function: check
	  Validate object vars. Returns True if the object is ok

	   Returns:
	      Boolean.
	*/
	function check() {

		if (!(is_numeric($this->category_id) && $this->category_id != 0)) {
			$this->setError(JText::_('Invalid category id'));
			return false;
		}

		if (!(is_numeric($this->item_id) && $this->item_id != 0)) {
			$this->setError(JText::_('Invalid category id'));
			return false;
		}
				
		return true;
	}

	/*
		Function: getItemsRelatedCategoryIds
			Method to retrieve item's related category id's.

		Returns:
			Array - catalog/category id's
	*/
	function getItemsRelatedCategoryIds($item_id) {

		// select item to category relations
		$query = 'SELECT catalog_id, category_id'
		       . ' FROM '.$this->getTableName()
			   . ' WHERE item_id='.$item_id;
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$ids = array();
		
		foreach ($rows as $row) {
			$ids[] = $row->catalog_id.':'.$row->category_id;
		}
		
		return $ids;
	}
	
	/*
		Function: getCategoryItemCount
			Method to get categories related item's count.

		Parameters:
			$catalog_id - Catalog id
			$access_id - User access id

		Returns:
			Array - Array of categories item count
	*/
	function getCategoryItemCount($catalog_id, $access_id = false){
 		$query = "SELECT a.category_id, count(b.id) AS item_count"
		        ." FROM ".$this->getTableName()." AS a"
		        ." JOIN ".ZOO_TABLE_ITEM." AS b ON b.id = a.item_id"
		        ." WHERE a.catalog_id = ".(int) $catalog_id
				." AND (b.state = 1"
				.($access_id !== false ? " AND b.access <= ".(int) $access_id : "")
				." AND ((NOW() BETWEEN b.publish_up AND b.publish_down)"
				." OR (NOW() >= b.publish_up AND b.publish_down = '".$this->_db->getNullDate()."')))"
		    	." GROUP BY a.category_id";

		return $this->queryObjectList($query, 'category_id');
	}

	/*
		Function: saveCategoryItemRelations
			Method to add category related item's.

		Returns:
			Boolean - true on succes
	*/
	function saveCategoryItemRelations($item, $categories){

		// delete category to item relations
		$query = "DELETE FROM ".$this->getTableName()
			    ." WHERE item_id=".$item->id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// add category to item relations
		foreach($categories as $catalog_category){
			if (strpos($catalog_category, ':')) {
				
				// parse catalog/category
				list($catalog_id, $category_id) = explode(':', $catalog_category, 2);

				// insert relation to database
				$query = "INSERT INTO ".$this->getTableName()
					    ." SET catalog_id=".(int) $catalog_id.","
					    ." category_id=".(int) $category_id.","
						." item_id=".(int) $item->id;
				$this->_db->setQuery($query);
				$this->_db->query();
				if ($this->_db->getErrorNum()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		return true;
	}

	/*
		Function: deleteCategoryItemRelations
			Method to delete category related item's.

		Returns:
			Boolean - true on succes
	*/
	function deleteCategoryItemRelations($category_id){

		// delete category to item relations
		$query = "DELETE FROM ".$this->getTableName()
			    ." WHERE category_id = ".(int) $category_id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/*
		Function: deleteItemCategoryRelations
			Method to delete item related categories.

		Returns:
			Boolean - true on succes
	*/
	function deleteItemCategoryRelations($item_id){

		// delete item to category relations
		$query = "DELETE FROM ".$this->getTableName()
			    ." WHERE item_id = ".(int) $item_id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

}