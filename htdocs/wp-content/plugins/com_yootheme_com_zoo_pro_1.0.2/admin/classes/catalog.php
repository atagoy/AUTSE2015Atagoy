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
		Catalog related attributes and functions.
*/
class Catalog extends YObject {

    /*
		Variable: id
			Primary key.
    */
	var $id	= null;

    /*
		Variable: name
			Catalog name.
    */
	var $name = null;

    /*
		Variable: alias
			Catalog alias.
    */
	var $alias = null;

    /*
		Variable: description
			Catalog description.
    */
	var $description = null;

    /*
       Variable: params
         Catalog params.
    */
	var $params = null;

    /*
       Variable: _categories
         Catalog categories.
    */
	var $_categories = null;

	/*
		Function: getInstance
		  Returns a reference to the global Catalog object, only creating it if it doesn't already exist.

		Parameters:
	      $id - The catalog id.
	
	   Returns:
	      Object - Catalog.	
	*/	
	function &getInstance($id = 0) {
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		if (empty($id)) {
			$catalog = new Catalog();
			return $catalog;
		} else if (!is_numeric($id)) {
			$retval = null;
			return $retval;
		}

		if (empty($instances[$id])) {
			$catalog = new Catalog($id);
			$instances[$id] = $catalog;
		}

		return $instances[$id];
	}

	/*
    	Function: getCategories
    	  Get catalog categories. 

		Parameters:
	      $published - If true, return only published categories.

	   Returns:
	      Array - Categories
 	*/
	function getCategories($published = false) {

		if (empty($this->_categories)) {

			// get cache
			$cache =& JFactory::getCache('com_zoo');
			$cache->setCaching(1);
						
			// get categories
			$table =& JTable::getInstance('category', 'Table');
			$table->setCache($cache);
			$this->_categories = $table->getAll($this->id);
		}
		
		if ($published) {
			$categories = array();

			// filter published
			foreach ($this->_categories as $id => $category) {
				if ($category->published) {
					$categories[$id] = $category;
				}
			}
			
			return $categories;
		}

		return $this->_categories;
	}

	/*
    	Function: getCategoryItemCount
    	  Get catalog categories related item count. 

		Parameters:
	      $access - User access id.

	   Returns:
	      Array - Item count
 	*/
	function getCategoryItemCount($access = 0) {

		// get cache
		$cache =& JFactory::getCache('com_zoo');
		$cache->setCaching(1);

		// get item count
		$table =& JTable::getInstance('categoryitem', 'Table');
		$table->setCache($cache);
		$item_count = $table->getCategoryItemCount($this->id, $access);

		return $item_count;
	}

	/*
    	Function: getImage
    	  Get catalog image resource info. 

	   Returns:
	      Array - Image info
 	*/
	function getImage() {
		
		// init vars
		$image = $this->get('image');
		$path  = JPATH_ROOT.DS.ZOO_IMAGE_PATH.DS.$image;
		
		if ($image && is_file($path)) {

			$info['path'] = $path;
			$info['src'] = JURI::root().ZOO_IMAGE_PATH.'/'.$image;
			$info['width'] = $this->get('image_width');
			$info['height'] = $this->get('image_height');
			$info['width_height'] = sprintf('width="%d" height="%d"', $info['width'], $info['height']);

			return $info;
		}

		return null;
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

	/*
		Function: save
			Override. Save object to database

		Returns:
			Boolean - true on success
 	*/
	function save() {

		// get params
		$params = new JParameter($this->params);
		$image  = JPATH_ROOT.DS.ZOO_IMAGE_PATH.DS.$params->get('image');

		// set image width/height
		if (is_file($image) && $size = getimagesize($image)) {
			$params->set('image_width', $size[0]);
			$params->set('image_height', $size[1]);
		}		

		// set params
		$this->params = $params->toString();

		return parent::save();
	}
	
	/*
    	Function: bind
    	  Override. Binds a named array/hash to this object.
		
		Parameters:
	      from   - An associative array or object.
	      ignore - An array or space separated list of fields not to bind.
	
	   Returns:
	      Boolean.	
 	*/
	function bind($from, $ignore = array()) {
		
		if (!parent::bind($from, $ignore)) {
			return false;
		}
		
		if (is_array($from) && isset($from['params'])) {
			$params = new JParameter('');
			$params->bind($from['params']);
			$this->params = $params->toString();
		}

		if (is_object($from) && isset($from->params)) {
			$params = new JParameter('');
			$params->bind($from->params);
			$this->params = $params->toString();
		}
	
		return true;
	}

}