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
   Class: TableType
   The Table Class for type. Manages the database operations.
*/
class TableType extends YTable {

	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $alias				= null;
	/** @var string */
	var $elements			= null;
	/** @var string */
	var $params				= null;	

	/*
 	Function: __construct
 	  Constructor.

		Parameters:
	      db - A database connector object.
	*/
	function __construct(&$db) {
		parent::__construct(ZOO_TABLE_TYPE, 'id', $db);
	}

	/*
 	Function: check
	  Validate object vars. Returns True if the object is ok

	   Returns:
	      Boolean.
	*/
	function check() {

		if ($this->name == '') {
			$this->setError(JText::_('Invalid name'));
			return false;
		}

		if ($this->alias == '' || preg_match('/[^A-Za-z0-9_]/', $this->alias)) {
			$this->setError(JText::_('Invalid alias'));
			return false;
		}

		if (TypeHelper::checkAliasExists($this->alias, $this->id)) {
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

		// check if type has items
		$table =& JTable::getInstance('item', 'Table');
		if ($table->getTypeItemCount($this->id)) {
			$this->setError(JText::_('Cannot delete type, please delete the items related first'));
			return false;
		}

		// delete related type table
		$query = 'DROP TABLE #__zoo_type_'.$this->alias;
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return parent::delete();
	}

	/*
		Function: getAll
			Method to retrieve all types.

		Returns:
			Array - Array of types
	*/
	function &getAll(){
		$query = 'SELECT *'
			    .' FROM '.$this->getTableName()
		   	    .' ORDER BY name';
		
		return $this->query($query);
	}	

	/*
		Function: query
			Method to retrieve/query types.

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

}