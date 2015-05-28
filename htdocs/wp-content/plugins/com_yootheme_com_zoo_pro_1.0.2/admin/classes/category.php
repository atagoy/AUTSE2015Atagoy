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
	Class: Category
		Category related attributes and functions.
*/
class Category extends YObject {

    /*
       Variable: id
         Primary key.
    */
	var $id	= null;

    /*
       Variable: catalog_id
         Related catalog id.
    */
	var $catalog_id = null;

    /*
       Variable: name
         Category name.
    */
	var $name = null;

    /*
		Variable: alias
			Category alias.
    */
	var $alias = null;
	
    /*
       Variable: description
         Category description.
    */
	var $description = null;

    /*
       Variable: full_description
         Category full description.
    */
	var $full_description = null;

    /*
       Variable: teaser_description
         Category teaser description.
    */
	var $teaser_description = null;

    /*
       Variable: parent
         Categories parent id.
    */
	var $parent = null;

    /*
       Variable: ordering
         Categories ordering.
    */
	var $ordering = null;

    /*
       Variable: published
         Category published state.
    */
	var $published = null;

    /*
       Variable: params
         Catalog params.
    */
	var $params = null;
	
    /*
       Variable: item_count
         Related category item count.
    */
	var $item_count = 0;

    /*
       Variable: _parent
         Related category parent object.
    */
	var $_parent = null;

    /*
       Variable: _children
         Related category children objects.
    */
	var $_children = array();

	/*
		Function: getName
			Method to retrieve category's name.

		Returns:
			String - Name
	*/
	function getName() {
		return $this->name;
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
    	Function: getTeaserImage
  	      Get category teaser image resource info. 

	   Returns:
	      Array - Image info
 	*/
	function getTeaserImage() {
		
		// init vars
		$image = $this->get('teaser_image');
		$path  = JPATH_ROOT.DS.ZOO_IMAGE_PATH.DS.$image;
		
		if ($image && is_file($path)) {

			$info['path'] = $path;
			$info['src'] = JURI::root().ZOO_IMAGE_PATH.'/'.$image;
			$info['width'] = $this->get('teaser_image_width');
			$info['height'] = $this->get('teaser_image_height');
			$info['width_height'] = sprintf('width="%d" height="%d"', $info['width'], $info['height']);

			return $info;
		}

		return null;
	}

	/*
		Function: getPathway
			Method to get category's pathway.

		Returns:
			Array - Array of parent categories
	*/
	function getPathway() {
		if ($this->_parent == null) {
			return array();
		}
		
		$pathway   = $this->_parent->getPathway();
		$pathway[] = $this;

		return $pathway;
	}

	/*
    	Function: isPublished
    	  Get published state.

	   Returns:
	      -
 	*/
	function isPublished() {
		return $this->published;
	}

	/*
    	Function: setPublished
    	  Set published state.

	   Returns:
	      -
 	*/
	function setPublished($val) {
		$this->published = $val;
	}

	/*
		Function: getPath
			Method to get the path to this category.

		Returns:
			Array - Category path
	*/
	function getPath($path = array()) {

		$path[] = $this->id;

		if ($this->_parent != null) {
			$path = $this->_parent->getPath($path);
		}

		return $path;
	}

	/*
		Function: countItems
			Method to count category's items including all childrens items.

		Returns:
			Int - Number of items
	*/
	function countItems() {
		$count = $this->item_count;
		
		foreach ($this->_children as $child) {
			$count += $child->countItems();
		}
		
		return $count;
	}

	/*
		Function: parseDescription
			Parses the description text for readmore and splits it in teaser/full text.

		Returns:
			Void
	*/
	function parseDescription() {

		// search for the {readmore} tag and split the text up accordingly
		$pattern  = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$readmore = preg_match($pattern, $this->description);

		if ($readmore == 0) {
			$this->teaser_description = $this->description;
			$this->full_description   = $this->description;
		} else {
			list($this->teaser_description, $this->full_description) = preg_split($pattern, $this->description, 2);
		}
	}

	/*
 	Function: move
 	   Method to move a category.

	   Returns:
	      Boolean. True on success
	*/
	function move($direction) {
		// load table object
		$table =& $this->getTable();
		$table->bind($this->getProperties());
		
		if (!$table->move($direction, ' parent = '.intval($this->parent). ' AND catalog_id = '.intval($this->catalog_id))) {
			$this->setError($table->getError());
			return false;
		}

		return true;
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
		$teaser = JPATH_ROOT.DS.ZOO_IMAGE_PATH.DS.$params->get('teaser_image');

		// set image width/height
		if (is_file($image) && $size = getimagesize($image)) {
			$params->set('image_width', $size[0]);
			$params->set('image_height', $size[1]);
		}		

		// set teaser image width/height
		if (is_file($teaser) && $size = getimagesize($teaser)) {
			$params->set('teaser_image_width', $size[0]);
			$params->set('teaser_image_height', $size[1]);
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

		// parse description text
		$this->parseDescription();

		// bind params
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
		CategoryHelper::translateAlias($this->id, $this->alias, true);
		
		return $return;
	}	

}