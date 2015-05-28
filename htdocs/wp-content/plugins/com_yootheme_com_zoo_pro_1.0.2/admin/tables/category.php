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
   Class: TableCategory
   The Table Class for category. Manages the database operations.
*/
class TableCategory extends YTable {

	/** @var int Primary key */
	var $id					= null;
	/** @var int */
	var $catalog_id		    = null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $alias				= null;
	/** @var string */
	var $description		= null;
	/** @var int */
	var $parent				= null;
	/** @var int */
	var $ordering			= null;
	/** @var int */
	var $published			= null;
	/** @var string */
	var $params				= null;

	/*
		Function: __construct
			Constructor.

		Parameters:
			$db - A database connector object.
	*/
	function __construct(&$db) {
		parent::__construct(ZOO_TABLE_CATEGORY, 'id', $db);
	}

	/*
		Function: check
			Validate object vars. Returns True if the object is ok

		Returns:
			Boolean.
	*/
	function check() {

		if (!(is_numeric($this->catalog_id) && $this->catalog_id != 0)) {
			$this->setError(JText::_('Invalid catalog id'));
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
		
		if (CategoryHelper::checkAliasExists($this->alias, $this->id)) {
			$this->setError(JText::_('Alias already exists, please choose a unique alias'));
			return false;
		}		
		
		if (!is_numeric($this->parent)) {
			$this->setError(JText::_('Invalid parent id'));
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
		
		// update childrens parent category
		$query = "UPDATE ".$this->_tbl
		    	." SET parent=".$this->parent
			    ." WHERE parent=".$this->id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// delete category to item relations
		$table =& JTable::getInstance('categoryitem', 'Table');
		if (!$table->deleteCategoryItemRelations($this->id)) {
			$this->setError($table->getError());
			return false;
		}
		
		return parent::delete();
	}

	/*
		Function: getbyId
			Method to retrieve categories by id.

		Parameters:
			$category_id - Categorie id(s)

		Returns:
			Array - Array of categories
	*/
	function &getById($category_id){
		$query = "SELECT *"
			    ." FROM ".$this->getTableName()
			    ." WHERE ".(is_array($category_id) ? "id IN (".implode(",", $category_id).")" : "id = ".(int) $category_id);

		return $this->queryObjectList($query, 'id');
	}

	/*
		Function: getAll
			Method to retrieve all categories of a catalog.

		Parameters:
			$catalog_id - Catalog id
			$published - Only published categories

		Returns:
			Array - Array of categories
	*/
	function &getAll($catalog_id, $published = false){
		$query = "SELECT *"
			." FROM ".$this->getTableName()
			." WHERE catalog_id = ".(int) $catalog_id
			.($published == true ? " AND published = 1" : "")
			." ORDER BY parent, ordering";

		return $this->queryObjectList($query, 'id');
	}	

	/*
		Function: getByItemId
			Method to retrieve item's related categories.

		Parameters:
			$catalog_id - Catalog id
			$published - Only published categories

		Returns:
			Array - Related categories
	*/
	function getByItemId($item_id, $published = false) {
		$query = 'SELECT b.*'
	            .' FROM '.ZOO_TABLE_CATEGORY_ITEM.' AS a'
	            .' JOIN '.$this->getTableName().' AS b ON b.id = a.category_id'
			    .' WHERE a.item_id='.(int) $item_id
			    .($published == true ? " AND a.published = 1" : "");

		return $this->queryObjectList($query, 'id');
	}

	/*
		Function: moveCategory
			Method to category from one catalog to another.

		Parameters:
			$category - Category
			$other_catgory_ids - Other categories to move
			$catalog_id - Target Catalog id
			$parent - Target parent category id

		Returns:
			Boolean - true on succes
	*/
	function moveCategory($category, $other_catgory_ids, $catalog_id, $parent){

		// update childrens parent category
		$query = "UPDATE ".ZOO_TABLE_CATEGORY
		    	." SET parent=".$category->parent
			    ." WHERE parent=".$category->id
			    .(count($other_catgory_ids) ? " AND id NOT IN (".implode(",", $other_catgory_ids).")" : "");
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// update catalog and parent category
		$query = "UPDATE ".ZOO_TABLE_CATEGORY
		    	." SET catalog_id=".$catalog_id
				.(!in_array($category->parent, $other_catgory_ids) ? ", parent=".$parent : "")
			    ." WHERE id=".$category->id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// move category to target catalog
		$query = "UPDATE ".ZOO_TABLE_CATEGORY_ITEM
		    	." SET catalog_id=".$catalog_id
			    ." WHERE category_id=".$category->id;
		$this->_db->setQuery($query);
		$this->_db->query();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/*
		Function: count
			Method to retrieve count categories of a catalog.

		Parameters:
			$catalog_id - Catalog id

		Returns:
			Int - Number of categories
	*/
	function count($catalog_id){
		$query = "SELECT COUNT(*) AS category_count"
			." FROM ".$this->getTableName()." AS a"
			." WHERE a.catalog_id = ".(int) $catalog_id
	    	." GROUP BY a.id";
		$result = $this->query($query);

		// check error
		if ($result === false) {
			return false;
		}
		
		return $result[0]->category_count;
	}	

	/*
		Function: query
			Method to retrieve/query categories.

		Parameters:
			$query - SQL query string

		Returns:
			Array - Rows
	*/
	function &query($query){

		// query database table
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			$return = false;
			return $return;
		}

		return $rows;
	}	
	
	/*
		Function: saveorder
			Method to save reordered categories.

		Parameters:
			$catalog_id - Catalog id
			$cid - Category id's
			$order - New order

		Returns:
			Boolean. True on success
	*/
	function saveorder($catalog_id, $cid = array(), $order) {

		// init vars
		$parents = array();

		// update ordering values
		for ($i=0; $i < count($cid); $i++) {
			$this->load(intval($cid[$i]));
			
			// track parent categories
			$parents[] = $this->parent;

			// update ordering, if order changed
			if ($this->ordering != $order[$i]) {
				$this->ordering = $order[$i];
				if (!$this->store()) {
					return false;
				}
			}
		}

		// update category ordering
		return $this->updateorder($catalog_id, $parents);
	}

	/*
		Function: updateorder
			Method to check/fix category ordering.

		Parameters:
			$catalog_id - Catalog id
			$parents - Parent category id(s)

		Returns:
			Boolean. True on success
	*/
	function updateorder($catalog_id, $parents = array()) {
		
		if (!is_array($parents)) {
			$parents = array($parents);
		}
		
		// execute update order for each parent categories
		$parents = array_unique($parents);
		foreach ($parents as $parent) {
			if (!$this->reorder('catalog_id = '.(int) $catalog_id.' AND parent = '.(int) $parent)) {
				return false;
			}
		}

		return true;
	}

	/*
		Function: reorder
			Override. Compacts the ordering sequence of the selected records.

		Parameters:
			$where - SQL where condition

		Returns:
			Boolean. True on success
	*/
	function reorder($where = '') {

		// get rows
		$query = 'SELECT '.$this->_tbl_key.', ordering'
		        .' FROM '.$this->_tbl
		        .($where ? ' WHERE '.$where : '')
		        .' ORDER BY ordering';
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList($this->_tbl_key);
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// init vars
		$i      = 1;
		$update = array();

		// collect rows which ordering need to be updated
		foreach ($rows as $id => $row) {

			if ($row->ordering != $i) {
				$update[$i - $row->ordering][] = $id;
			}

			$i++;
		}
		
		// do the ordering update
		foreach ($update as $diff => $ids) {

			// build ordering update query
			$query = 'UPDATE '.$this->_tbl
				    .sprintf(' SET ordering = (ordering'.($diff >= 0 ? '+' : '').'%s)', $diff)
				    .sprintf(' WHERE '.$this->_tbl_key.(count($ids) == 1 ? ' = %s' : ' IN (%s)'), implode(',', $ids));

			// set and execute query
			$this->_db->setQuery($query);
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

}