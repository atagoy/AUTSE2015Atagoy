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
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
   Class: ElementText
       The text element class
*/
class ElementText extends ElementSimple {

	/*
	   Function: Constructor
	*/
	function ElementText() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type  = 'text';
	}

	/*
		Function: getSearchData
			Get elements search data.
					
		Returns:
			String - Search data
	*/
	function getSearchData() {
		return $this->value;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {
		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$value = $this->default;
		}

		return JHTML::_('control.text', $this->name.'_value', $value, 'size="60" maxlength="255"');
	}

}