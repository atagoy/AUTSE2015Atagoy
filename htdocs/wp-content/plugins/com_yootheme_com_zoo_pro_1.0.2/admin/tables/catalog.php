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
   Class: Catalog
   The Table Class for catalog. Manages the database operations.
*/
class TableCatalog extends YTable {

	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $alias				= null;
	/** @var string */
	var $description		= null;
	/** @var string */
	var $params			    = null;

	/*
 	Function: __construct
 	  Constructor.

		Parameters:
	      db - A database connector object.
	*/	
	function __construct(&$db) {
		parent::__construct(ZOO_TABLE_CATALOG, 'id', $db);
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
		
		return true;
	}

	/*
		Function: delete
			Override. Delete object from database table.

		Returns:
			Boolean.
	*/
	function delete() {

		// check if catalog has categories
		$table =& JTable::getInstance('category', 'Table');
		if ($table->count($this->id)) {
			$this->setError(JText::_('Cannot delete catalog, please delete the categories first'));
			return false;
		}

		return parent::delete();
	}

	/*
		Function: getAll
			Method to retrieve all catalogs.

		Returns:
			Array - Array of catalogs
	*/
	function &getAll(){
		$query = 'SELECT *'
			    .' FROM '.$this->getTableName()
		   	    .' ORDER BY name';
	
		return $this->queryObjectList($query, 'id');
	}

}