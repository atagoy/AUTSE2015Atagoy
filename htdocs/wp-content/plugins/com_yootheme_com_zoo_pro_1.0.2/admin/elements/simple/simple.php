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

// register parent class
JLoader::register('Element', ZOO_ADMIN_PATH.'/elements/element/element.php');

/*
	Class: ElementSimple
		The simple element abstract class, provide basic implementation for simple elements
*/
class ElementSimple extends Element {

	/** @var mixed */
	var $value = null;
	/** @var array */
	var $_table_columns = array();

	/*
	   Function: Constructor
	*/
	function ElementSimple() {

		// call parent constructor
		parent::Element();
		
		// init vars
		$this->type  = 'simple';
		$this->_table_columns = array('_value' => 'VARCHAR(255)');
	}

	/*
		Function: setItem
			Set related item object.

		Parameters:
			$item - item object

		Returns:
			Void
	*/
	function setItem(&$item) {
		$this->_item =& $item;
		$this->load();
	}

	/*
		Function: hasValue
			Checks if the element's value is set.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		return !empty($this->value);
	}

	/*
		Function: render
			Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('value' => $this->value));
		}
		
		return $this->value;
	}

	/*
		Function: load
			Load items element data and set vars

		Returns:
			Void
 	*/
	function load() {
		if (isset($this->_item->id)) {
			
			// load element data from item
			$data = ElementSimple::loadData($this->_item->id, $this->_type->getTableName());
			
			// set element vars
			foreach ($this->_table_columns as $name => $type) {
				if (isset($data[$this->name.$name])) {
					$var = ltrim($name, '_');
					$this->$var = $data[$this->name.$name];
				}
			}
		}
	}

	/*
		Function: loadData
			Static. Load items element data

		Returns:
			Array - element data
 	*/
	function loadData($id, $table_name) {

		static $data;

		if (!isset($data)) {
			$data = array();
		}

		if (!isset($data[$id])) {

			// execute database query
			$db    =& JFactory::getDBO();
			$query = "SELECT * FROM ".$table_name
			   		." WHERE item_id = ".$id;
			$db->setQuery($query);
			$data[$id] = $db->loadAssoc();

			// check for errors
			if ($db->getErrorNum()) {
				return false;
			}
		}
		
		return $data[$id];
	}

	/*
    	Function: bind
    	  Binds a named array/hash to this object.
		  Can be overloaded/supplemented by the child class.
		
		Parameters:
	      $data - An associative array or object.
	
	   Returns:
	      Void	
 	*/
	function bind($data) {
		
		// set element vars
		foreach ($this->_table_columns as $name => $type) {
			if (isset($data[$this->name.$name])) {
				$var = ltrim($name, '_');
				$this->$var = $data[$this->name.$name];
			}
		}

	}

	/*
		Function: save
			Save the elements item data.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true on success
	*/
	function save() {

		// init vars
		$db      =& JFactory::getDBO();
		$table   = $this->_type->getTableName();
		$numeric = array('TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT');

		// build query
		$params = array();
		foreach ($this->_table_columns as $suffix => $type) {
			$var    = ltrim($suffix, '_');
			$column = $this->name.$suffix;
			
			if (in_array($type, $numeric)) {
				$params[] = $column." = ".(isset($this->$var) ? $db->getEscaped($this->$var) : 'NULL');
			} else {
				$params[] = $column." = ".(isset($this->$var) ? $db->Quote($this->$var) : 'NULL');
			}
		}

		if (count($params)) {

			// check if row already exists
			$query = "SELECT item_id FROM ".$table
				   	." WHERE item_id = ".$this->_item->id;
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}

			// build update or insert query
			if ($db->getNumRows()) {
				$query = "UPDATE ".$table
				   		." SET ".implode(", ", $params)
					   	." WHERE item_id = ".$this->_item->id;
			} else {
				$params[] = "item_id = ".$this->_item->id;
				$query    = "INSERT INTO ".$table
				   	       ." SET ".implode(", ", $params);
			}
			
			// execute query
			$db->setQuery($query);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	/*
	   Function: configEditName
	      Renders elements name field including hidden fields.
	      Can be overloaded/supplemented by the child class.

	   Parameters:
	      $var - form var name

	   Returns:
		  String - html form input
	*/
	function configEditName($var) {
		
		$html = JHTML::_('control.hidden', $var.'[type]', $this->type) .			
			    JHTML::_('control.hidden', $var.'[ordering]', $this->ordering) .			
			    JHTML::_('control.text', $var.'[name]', $this->name, 'maxlength="25"');

		return $html;
	}

	/*
		Function: configCheck
			Checks elements configuration.
			Can be overloaded/supplemented by the child class.

	   Parameters:
            $data - element configuration

		Returns:
			Boolean - true on success
	*/
	function configCheck($data){
		
		if (!parent::configCheck($data)) {
			return false;
		}

		foreach ($this->_table_columns as $suffix => $type) {

			// create column name
			$name = $data['name'].$suffix;

			// check if element name already exists in database table
			if ($this->name != $data['name'] && in_array($name, $this->_type->getTableColumns())) {
				$this->setError('Invalid name "'.$name.'", name already exists in database table');
				return false;
			}
		}

		return true;
	}

	/*
	   Function: configAdd
	   	  Add elements configuration.
	   	  Can be overloaded/supplemented by the child class.

	   Parameters:
          $data - element configuration

	   Returns:
		  boolean - true on success
	*/
	function configAdd($data){
		
		// init vars
		$db    =& JFactory::getDBO();
		$table = $this->_type->getTableName();

		// build query
		$params = array();
		foreach ($this->_table_columns as $suffix => $type) {
			$params[] = 'ADD COLUMN '.$data['name'].$suffix.' '.$type;
		}
		
		if (count($params)) {
			$query = 'ALTER TABLE '.$table
			        .' '.implode(', ', $params);

			$db->setQuery($query);

			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		// bind data
		$this->configBindArray($data);
		
		return true;
	}

	/*
	   Function: configSave
	   Saves elements configuration.
	   Can be overloaded/supplemented by the child class.

	   Parameters:
	      $data - element configuration

	   Returns:
		  boolean - true on success
	*/
	function configSave($data){
		
		// change column name
		if ($this->name != $data['name']) {

			// init vars
			$db    =& JFactory::getDBO();
			$table = $this->_type->getTableName();

			// build query
			$params = array();
			foreach ($this->_table_columns as $suffix => $type) {
			    $params[] = 'CHANGE '.$this->name.$suffix.' '.$data['name'].$suffix.' '.$type;
			}

			if (count($params)) {
				$query = 'ALTER TABLE '.$table
				        .' '.implode(', ', $params);

				$db->setQuery($query);

				if (!$db->query()) {
					$this->setError($db->getErrorMsg());
					return false;
				}
			}
		}

		// bind data
		$this->configBindArray($data);
		
		return true;
	}

	/*
		Function: configDelete
	   		Deletes elements configuration.
	   		Can be overloaded/supplemented by the child class.

		Returns:
 		  	boolean - true on success
	*/
	function configDelete(){

		// init vars
		$db    =& JFactory::getDBO();
		$table = $this->_type->getTableName();

		// build query
		$params = array();
		foreach ($this->_table_columns as $suffix => $type) {
		    $params[] = 'DROP COLUMN '.$this->name.$suffix;
		}

		if (count($params)) {
			$query = 'ALTER TABLE '.$table
			        .' '.implode(', ', $params);

			$db->setQuery($query);

			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

}