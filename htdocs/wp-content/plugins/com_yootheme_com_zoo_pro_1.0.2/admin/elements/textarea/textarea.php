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
   Class: ElementTextarea
   The textarea element class
*/
class ElementTextarea extends ElementSimple {

	/** @var int */
	var $feed = 0;
	/** @var int */
	var $editor = 0;
	/** @var int */
	var $rows = 20;
	/** @var int */
	var $cols = 60;

	/*
	   Function: Constructor
	*/
	function ElementTextarea() {
		
		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type  = 'textarea';
		$this->_table_columns = array('_value' => 'TEXT');		
		$this->_var_types = array('_value' => 'raw');
	}

	/*
		Function: getSearchData
			Get elements search data.
					
		Returns:
			String - Search data
	*/
	function getSearchData() {

		// clean html tags
		$filter	= new JFilterInput();
		$value  = $filter->clean($this->value);

		return $value;
	}

	/*
		Function: hasValue
			Override. Checks if the element's value is set.

		Returns:
			Boolean - true, on success
	*/
	function hasValue($type = 'full') {
		$text = ElementTextarea::parseText($this->value);
		return !empty($text[$type]);
	}

	/*
		Function: render
			Override. Renders the element.

	   Parameters:
            $view - current view
	        $type - render type intro/full

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM, $type = 'full') {
		
		$text = ElementTextarea::parseText($this->value);

		// render feed
		if ($view == ZOO_VIEW_FEED) {
			if ($this->feed == 0) return null;
			if ($this->feed == 1) $type = 'intro';
			if ($this->feed == 2) $type = 'full';
			if ($this->feed == 3) $type = 'readmore';
		}

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('text' => $text[$type], 'intro' => $text['intro'], 'readmore' => $text['readmore'], 'full' => $text['full']));
		}
		
		return $text[$type];
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {
		
		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$this->value = $this->default;
		}

		// use joomla editor
		if ($this->editor) {
			// params: areaname, content, width, height, cols, rows, show xtd buttons
			$editor =& JFactory::getEditor();
			return $editor->display($this->name.'_value', $this->value, '550', '300', $this->cols, $this->rows, array('pagebreak'));
		}
		
		return JHTML::_('control.textarea', $this->name.'_value', $this->value, 'cols="'.$this->cols.'" rows="'.$this->rows.'"');
	}

	/*
		Function: configCheck
			Checks elements configuration.

	   Parameters:
            $data - element configuration

		Returns:
			Boolean - true on success
	*/
	function configCheck($data){
		
		if (!parent::configCheck($data)) {
			return false;
		}
		
		// check if rows & cols have numeric values
		if ($data['rows'] != '' && !is_numeric($data['rows'])) {
			$this->setError('Rows have to be numeric value, please verify element '.$data['name']);
			return false;
		}

		if ($data['cols'] != '' && !is_numeric($data['cols'])) {
			$this->setError('Columns have to be numeric value, please verify element '.$data['name']);
			return false;
		}

		return true;
	}	

	/*
		Function: parseText
			Static. Parses the text for readmore and splits it in intro/readmore/full text.

	   Parameters:
            $text - Text to parse

		Returns:
			Array - intro/readmore/full text values
	*/
	function parseText($text) {

		// search for the {readmore} tag and split the text up accordingly
		$pattern  = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
		$readmore = preg_match($pattern, $text);
		$value    = array('intro' => $text, 'readmore' => null, 'full' => $text);

		if ($readmore > 0) {
			list($value['intro'], $value['readmore']) = preg_split($pattern, $text, 2);
			$value['full'] = $value['intro'].$value['readmore'];
		}

		return $value;
	}

}