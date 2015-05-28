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
	Class: Type
		Type related attributes and functions.
*/
class Type extends YObject {

    /*
       Variable: id
         Primary key.
    */
	var $id	= null;

    /*
       Variable: name
         Type name.
    */
	var $name = null;

    /*
       Variable: alias
         Type alias.
    */
	var $alias = null;

    /*
       Variable: elements
         Type elements.
    */
	var $elements = null;

    /*
       Variable: params
         Catalog params.
    */
	var $params = null;

    /*
       Variable: table_columns
         Related table columns.
    */
	var $table_columns = null;

    /*
       Variable: elements
         Element objects.
    */
	var $_elements = null;

	/*
 	Function: getInstance
		Returns a reference to the global User object, only creating it if it doesn't already exist.

	   	  Returns:
	        Object - Type
	*/
	function &getInstance($id = 0) {
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		if (empty($id)) {
			$type = new Type();
			return $type;
		} else if (!is_numeric($id)) {
			$retval = null;
			return $retval;
		}

		if (empty($instances[$id])) {
			$type = new Type($id);
			$instances[$id] = $type;
		}

		return $instances[$id];
	}

	/*
    	Function: getElement
    	  Get element object by name.

	   	  Returns:
	        Object
 	*/
	function getElement($name, $item = null) {
		$elements =& $this->getElements($item);
		
		if (isset($elements[$name])) {
			return $elements[$name];
		}
		
		return null;
	}

	/*
    	Function: getElements
    	  Get all element objects from the parsed type elements xml.

	   	  Returns:
	        Array - Array of element objects
 	*/
	function &getElements($item = null) {
		$elements = array();

		// init elements from type xml
		if (empty($this->_elements)) {
			$this->_elements = ElementHelper::createElementsFromXML($this->elements);
		}

		// set type and item object
		foreach ($this->_elements as $name => $element) {
			$elements[$name] = $this->_elements[$name]->getClone();
			$elements[$name]->setType($this);
			$elements[$name]->setItem($item);
		}

		return $elements;
	}

	/*
    	Function: getTableName
    	  Get types database table name based on alias.

	   Returns:
	      String - table name
 	*/
	function getTableName() {
		return '#__zoo_type_'.$this->alias;
	}

	/*
    	Function: getTableColumns
    	  Get types database table columns.

	   Returns:
	      Array - table column names
 	*/
	function getTableColumns() {
		if (empty($this->table_columns)) {

			$db =& JFactory::getDBO();
			$db->setQuery('SHOW COLUMNS FROM '.$this->getTableName());
			$rows = $db->loadObjectList();
			if ($db->getErrorNum()) {
				$this->setError($db->getErrorMsg());
				return false;
			}

			$this->table_columns = array();
			foreach ($rows as $row) {
				if ($row->Field != 'item_id') {
					$this->table_columns[] = $row->Field;
				}
			}
		}

		return $this->table_columns;
	}

	/*
 		Function: get
 	  	  Override. Returns a property of the object or the default value if the property is not set.
		
		Parameters:
	      $property - The name of the property.
	      $default - The default value.
	
	   Returns:
	      Mixed - Property.	
	*/
	function get($property, $default = null) {

		// get property from object
		if (isset($this->$property)) {
			return $this->$property;
		}

		// get property from params
		$params = new JParameter($this->params);
		return $params->get($property, $default);
	}

}