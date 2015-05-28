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
   Class: JHTMLType
   	  A class that contains type html functions
*/
class JHTMLType {

 	/*
    	Function: edit
    		Returns edit form html string.
 	*/
	function edit(&$item) {

		$type  =& $item->getType();

		$html  = "\t<table class=\"admintable\">\n";
		$html .= "\t<tbody>\n";

		foreach ($type->getElements($item) as $name => $element) {
			$element->loadAssets();			

			// set label
			$label = $element->label;
			if ($element->description) {
				$label = '<span class="editlinktip hasTip" title="'.$element->description.'">'.$label.'</span>';
			}
	
			$html .= "\t<tr>\n";
			$html .= "\t\t<td class=\"key\">$label</td>\n";
			$html .= "\t\t<td>".$element->edit()."</td>\n";
			$html .= "\t</tr>\n";
		}

		$html .= "\t</tbody>\n";
		$html .= "\t</table>\n";

		return $html;
	}

}

/*
	Class: TypeHelper
		The Helper Class for type
*/
class TypeHelper extends YObject {

	/*
		Function: getTypeIdByAlias
			Method to get type id by alias.
		
		Return:
			Int - The item id or 0 if not found
	*/
	function getTypeIdByAlias($alias) {

		// init vars
		$db =& JFactory::getDBO();

		// search alias
		$query = 'SELECT id'
			    .' FROM '.ZOO_TABLE_TYPE
			    .' WHERE alias = '.$db->Quote($alias);
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/*
 		Function: checkAliasExists
 			Method to check if a alias already exists.
	*/
	function checkAliasExists($alias, $id = 0) {

		$xid = intval(TypeHelper::getTypeIdByAlias($alias));
		if ($xid && $xid != intval($id)) {
			return true;
		}
		
		return false;
	}
	
	/*
 		Function: createTable
 	      Method to create a type table.

	   Parameters:
	      $name - table name

	   Returns:
	      Boolean. True on success
	*/
	function createTable($name){

		$db    =& JFactory::getDBO();
		$query = 'CREATE TABLE #__zoo_type_'.$name
		        .'(item_id INT NOT NULL PRIMARY KEY)';

		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	/*
 		Function: renameTable
 	      Method to rename a type table.

	   Parameters:
	      $old_name - old table name
	      $new_name - new table name

	   Returns:
	      Boolean. True on success
	*/
	function renameTable($old_name, $new_name){

		$db    =& JFactory::getDBO();
		$query = 'RENAME TABLE #__zoo_type_'.$old_name.' TO #__zoo_type_'.$new_name;

		$db->setQuery($query);
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	/*
 		Function: saveElements
 	      Method to save a types elements.

	   Parameters:
	      $post - post data
	      $type - current type data with xml

	   Returns:
	      Boolean. True on success
	*/
	function saveElements($post, &$type, &$delete){

		$elements = array();

		// update and delete old elements
		foreach ($type->getElements() as $name => $element) {
			$element->setType($type);
			if (isset($post['elements'][$name])) {
				$data = $post['elements'][$name];

				// filter name
				$data['name'] = YFilterOutput::stringDBSafe($data['name']);
				
				// check element data
				if (!$element->configCheck($data)) {
					$this->setError($element->getError());
					return false;
				}
				
				// save element
				$element->configSave($data);
				$elements[$data['ordering']] = $element;
			} else {

				// delete element
				$element->configDelete();
				$delete = true;
			}
		}

		// add new elements
		if (isset($post['new_elements']) && count($post['new_elements']) > 0) {
			foreach ($post['new_elements'] as $data) {
				$element =& ElementHelper::_loadElement($data['type']);
				$element->setType($type);
				
				// filter name
				$data['name'] = YFilterOutput::stringDBSafe($data['name']);

				// check element data
				if (!$element->configCheck($data)) {
					$this->setError($element->getError());
					return false;
				}

				// add element
				$element->configAdd($data);
				$elements[$data['ordering']] = $element;
			}
		}

		// sort elements
		ksort($elements);

		$type->elements  = ElementHelper::toXML($elements);
		$type->_elements = null;

		return true;
	}

}