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
	Class: YObject
		Extends JObject related attributes, functions and add persistence.
*/
class YObject extends JObject {

	/*
    	Function: __construct
    	  Default Constructor

		Parameters:
	      id - Object id
 	*/
	function __construct($id = 0) {
		// load if it exists
		if (!empty($id)) {
			$table =& $this->getTable();
			if ($table->load($id)) {
				$this->setProperties($table);
			}
		}
	}

	/*
		Function: getTable
			Get related JTable instance

		Returns:
			Object - JTable object
 	*/
	function &getTable() {
		$table =& JTable::getInstance(get_class($this), 'Table');
		return $table;
	}

	/*
		Function: save
			Save object to database

		Returns:
			Boolean - true on success
 	*/
	function save() {
		// load table object
		$table =& $this->getTable();
		$table->bind($this->getProperties());

		// check object
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// store object
		if (!$result = $table->store()) {
			$this->setError($table->getError());
		}

		// set id
		if (empty($this->id)) {
			$this->id = $table->get('id');
		}

		return $result;
	}

	/*
		Function: delete
			Delete object from database

		Returns:
			Boolean - true on success
 	*/
	function delete() {
		// load table object
		$table =& $this->getTable();
		$table->bind($this->getProperties());

		// delete object
		if (!$result = $table->delete()) {
			$this->setError($table->getError());
		}

		return $result;
	}

	/*
    	Function: bind
    	  Binds a named array/hash to this object.
		  Can be overloaded/supplemented by the child class.
		
		Parameters:
	      from   - An associative array or object.
	      ignore - An array or space separated list of fields not to bind.
	
	   Returns:
	      Boolean.	
 	*/
	function bind($from, $ignore = array()) {
		$fromArray	= is_array($from);
		$fromObject	= is_object($from);

		if (!$fromArray && !$fromObject) {
			$this->setError(get_class($this).'::bind failed. Invalid from argument');
			return false;
		}
		
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}
		
		foreach ($this->getProperties() as $k => $v) {
			// internal attributes of an object are ignored
			if (!in_array($k, $ignore)) {
				if ($fromArray && isset($from[$k])) {
					$this->$k = $from[$k];
				} else if ($fromObject && isset($from->$k)) {
					$this->$k = $from->$k;
				}
			}
		}
		
		return true;
	}

}