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
	Class: Item
		Item related attributes and functions.
*/
class Item extends YObject {

    /*
       Variable: id
         Primary key.
    */
	var $id	= null;

    /*
       Variable: type_id
         Related type id.
    */
	var $type_id = null;

    /*
       Variable: name
         Item name.
    */
	var $name = null;

    /*
		Variable: alias
			Item alias.
    */
	var $alias = null;
	
    /*
       Variable: created
         Creation date.
    */
	var $created = null;

    /*
       Variable: modified
         Modified date.
    */
	var $modified = null;

    /*
       Variable: modified_by
         Modified by user.
    */
	var $modified_by = null;

    /*
       Variable: publish_up
         Publish up date.
    */
	var $publish_up = null;

    /*
       Variable: publish_down
         Publish down date.
    */
	var $publish_down = null;

    /*
       Variable: hits
         Hit count.
    */
	var $hits = null;

    /*
       Variable: state
         Item published state.
    */
	var $state = 0;

    /*
       Variable: metakey
         Item metakey.
    */
	var $metakey = null;

    /*
       Variable: metadata
         Item metadata.
    */
	var $metadata = null;

    /*
       Variable: metadesc
         Item meta description.
    */
	var $metadesc = null;

    /*
       Variable: access
         Item access level.
    */
	var $access = null;

    /*
       Variable: created_by
         Item created by user.
    */
	var $created_by = null;

    /*
       Variable: created_by_alias
         Item created by alias.
    */
	var $created_by_alias = null;

    /*
       Variable: search_data
         Item search data.
    */
	var $search_data = null;

    /*
       Variable: params
         Catalog params.
    */
	var $params = null;
	
    /*
       Variable: _type
         Related type object.
    */
	var $_type = null;

    /*
       Variable: elements
         Element objects.
    */
	var $_elements = null;

	/*
		Function: getType
			Get related type object.

		Returns:
			Type - type object
	*/
	function &getType() {

		if (empty($this->_type)) {
			$this->_type =& Type::getInstance($this->type_id);
		}

		return $this->_type;
	}

	/*
		Function: getAuthor
			Get author name.

		Returns:
			String - author name
	*/
	function getAuthor() {

		$author = $this->created_by_alias;

		if (!$author) {

			$user =& JUser::getInstance($this->created_by);

			if ($user && $user->id) {
				$author = $user->name;
			}
		}

		return $author;
	}
	
	/*
    	Function: getState
    	  Get published state.

	   Returns:
	      -
 	*/
	function getState() {
		return $this->state;
	}

	/*
    	Function: setState
    	  Set published state.

	   Returns:
	      -
 	*/
	function setState($val) {
		$this->state = $val;
	}

	/*
    	Function: getElement
    	  Get element object by name.

	   	  Returns:
	        Object
 	*/
	function getElement($name) {
		// load elements
		$elements =& $this->getElements();
		
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
	function &getElements($has_value = false) {

		// get type
		$type =& $this->getType();

		// get types elements
		if (empty($this->_elements)) {
			$this->_elements = $type->getElements($this);
		}

		// return only the elements with a value
		if ($has_value) {
			$elements = array();
			
			foreach ($this->_elements as $name => $element) {
				if ($element->hasValue()) {
					$elements[$name] = $element;
				}
			}
			
			return $elements;
		}

		return $this->_elements;
	}

	/*
		Function: getRelatedCategories
			Method to retrieve item's related categories.

		Returns:
			Array - category id's
	*/
	function getRelatedCategories() {

		// get category objects
		$table =& JTable::getInstance('category', 'Table');
		if (!$result = $table->getByItemId($this->id)) {
			$this->setError($table->getError());
			return false;
		}

		return $result;
	}

	/*
		Function: getRelatedCategoryIds
			Method to retrieve item's related category id's.

		Returns:
			Array - catalog/category id's
	*/
	function getRelatedCategoryIds() {

		// get category id's
		$table =& JTable::getInstance('categoryitem', 'Table');
		if (!$result = $table->getItemsRelatedCategoryIds($this->id)) {
			$this->setError($table->getError());
			return false;
		}

		return $result;
	}

	/*
    	Function: canAccess
    	  Check if item is accessible with users access rights.

	   Returns:
	      Boolean - True, if access granted
 	*/
	function canAccess($user) {
		return $user->get('aid', 0) >= $this->access;
	}

	/*
		Function: hit
			Method to increment the hit counter

		Returns:
			Boolean - true on success
	*/
	function hit() {
		if ($this->id) {
			$table =& $this->getTable();
			$table->hit($this->id);
			return true;
		}

		return false;
	}

	/*
		Function: save
			Save object to database

		Returns:
			Boolean - true on success
 	*/
	function save() {

		// create item row first, for new items
		if (!$this->id && !parent::save()) {
			return false;
		}

		// init vars
		$search   = array();
		$elements =& $this->getElements();

		// callback elements save function
		foreach ($elements as $name => $element) {
			$elements[$name]->_item =& $this; // needed for php4
			if (!$elements[$name]->save()) {
				$this->setError($elements[$name]->getError());
				return false;
			}
			if ($search_data = $element->getSearchData()) {
				$search[] = $search_data;
			}
		}

		// add elements search data
		$this->search_data = implode("\n", $search);

		return parent::save();
	}

	/*
		Function: delete
			Delete object from database

		Returns:
			Boolean - true on success
 	*/
	function delete() {

		// init vars
		$elements =& $this->getElements();

		// callback elements delete function
		foreach ($elements as $name => $element) {
			if (!$elements[$name]->delete()) {
				$this->setError($elements[$name]->getError());
				return false;
			}
		}

		return parent::delete();
	}

	/*
    	Function: bindElements
    	  Bind post/data array to elements.

	   Returns:
	      Void
 	*/
	function bindElements($data) {

		// init vars
		$elements =& $this->getElements();

		// callback elements bind function
		foreach ($elements as $name => $element) {
			$elements[$name]->bind($data);
		}
	}
	
	/*
    	Function: bindMetadata
    	  Bind metadata array to object.

	   Returns:
	      Void
 	*/
	function bindMetadata($data) {
		$txt = array();
		
		foreach ($data as $key => $value) {
			if ($key == 'description') {
				$this->metadesc = $value;
			} else if ($key == 'keywords') {
				$this->metakey = $value;
			} else {
				$txt[] = "$key=$value";
			}
		}
		
		$this->metadata = implode("\n", $txt);
	}

	/*
		Function: setProperties
		  Override. Set the object properties based on a named array/hash.

		Parameters:
	      $properties - Array
	
	   Returns:
	      Boolean - True, if success
	*/
	function setProperties($properties) {
		$return = parent::setProperties($properties);
		
		// add alias to translate list
		ItemHelper::translateAlias($this->id, $this->alias, true);
		
		return $return;
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