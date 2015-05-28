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
JLoader::register('ElementSimpleParams', ZOO_ADMIN_PATH.'/elements/simpleparams/simpleparams.php');

/*
	Class: ElementFile
		The file element class
*/
class ElementFile extends ElementSimpleParams {

	/** @var string */
	var $directory = null;
	/** @var string */
	var $file = null;

	/*
	   Function: Constructor
	*/
	function ElementFile() {

		// call parent constructor
		parent::ElementSimpleParams();
		
		// init vars
		$this->type = 'file';
		$this->_table_columns = array('_file' => 'TEXT', '_params' => 'TEXT');

		// set defaults
		$params =& JComponentHelper::getParams('com_media');
		$this->directory = $params->get('file_path');
	}
	
	/*
		Function: hasValue
			Checks if the element's value is set.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		
		// init vars
		$filepath = rtrim(JPATH_ROOT.DS.$this->directory, '/').'/'.$this->file;				
		
		return !empty($this->file) && is_readable($filepath) && is_file($filepath);
	}
	
	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {
		return JHTML::_('control.selectfile', JPATH_ROOT.DS.$this->directory, false, $this->name.'_file', $this->file);
	}

}