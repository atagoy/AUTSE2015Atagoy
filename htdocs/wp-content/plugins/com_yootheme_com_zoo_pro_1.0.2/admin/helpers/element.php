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
   Class: JHTMLElement
   	  A class that contains element html functions
*/
class JHTMLElement {

 	/*
    	Function: editRow
    		Returns edit row html string.
 	*/
	function editRow($name, $value) {

		$html  = "\t<tr>\n";
		$html .= "\t\t<td style=\"color:#666666;\">$name</td>\n";
		$html .= "\t\t<td>$value</td>\n";
		$html .= "\t</tr>\n";

		return $html;
	}

	/*
    	Function: configEdit
    		Returns config edit form part html string.
 	*/
	function configEdit($element, $name, $var) {

		// get elements meta data
		$metadata = $element->configMetaData();

		// create html
		$html  = "<fieldset class=\"adminform\">\n";
		$html .= "<legend>".$name." (".$metadata['name'].")</legend>\n";
		$html .= "<div class=\"sort-element\" title=\"".JText::_('Drag to sort')."\"></div>\n";
		$html .= "<div class=\"delete-element\" title=\"".JText::_('Delete element')."\"></div>\n";
		$html .= $element->configEdit($var);
		$html .= "</fieldset>\n";

		return $html;
	}
 	
}

/*
	Class: ElementHelper
		A class that contains element helper functions
*/
class ElementHelper {

	/*
	   Function: getAll
	      Returns a XML representation of the element array.

	   Returns:
	      XML representation of element array
	*/
	function getAll(){
		jimport('joomla.filesystem.file');

		$elements = array();
		$path     = ZOO_ADMIN_PATH.'/elements';

		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir($path.DS.$file) && is_file($path.DS.$file.DS.$file.'.php')) {
					if ($element = ElementHelper::_loadElement($file)) {
						$metadata = $element->configMetaData();
						if ($metadata && $metadata['hidden'] != 'true') {
							$elements[] = $element;
						}
					}
				}
		    }
		    closedir($handle);
		}
		
		return $elements;
	}

	/*
		Function: createElementsFromXML
			Creates the elements described by the XML.

		Parameters:
			$xml - String

		Returns:
			Array - Array of element objects
	*/
	function createElementsFromXML($xml){
		
		$i          = 0;
		$results    = array();
		$xmlAsArray = ElementHelper::_parseXML($xml);

		if ($xmlAsArray) {
			foreach ($xmlAsArray->children() as $param)  {
				$elem = ElementHelper::_getElementFromXMLNode($param);
				$results[$elem->name] = $elem;
				$results[$elem->name]->setOrdering($i++);
			}
		}

		return $results;
	}

	/*
		Function: _parseXML
			Parses the XML

		Parameters:
			$xml - XML string to parse

		Returns:
			Array - Array representation of xml
	*/
	function _parseXML($xml) {
		$xmlAsArray = null;
		$xmlParser =& JFactory::getXMLParser('Simple');

		if ($xmlParser->loadString($xml)) {
			if ($params =& $xmlParser->document->params) {
				foreach ($params as $param) {
					$xmlAsArray = $param;
				}
			}
		}
		
		return $xmlAsArray;
	}

	/*
		Function: _getElementFromXMLNode
			Creates an Element and binds data from XMLNode

		Parameters:
			$nodeXML - Node describes Element

		Returns:
			Object - element object
	*/
	function _getElementFromXMLNode(&$nodeXML) {

		// load element
		$type    =  $nodeXML->attributes('type');
		$element =& ElementHelper::_loadElement($type);

		// bind element data or set undefined
		if ($element !== false) {
			$element->configBindXML($nodeXML);
		} else {
			$element        = new Element();
			$element->name  = $nodeXML->attributes('name');
			$element->type  = $type;
			$element->label = JText::_('Element not defined for type').' = '.$type;
		}

		return $element;
	}

	/*
		Function: _loadElement
			Creates element of $type

		Parameters:
			$type - Type of the element subclass to create

		Returns:
			Object - element object
	*/
	function &_loadElement($type) {
		$false = false;
		$path  = ZOO_ADMIN_PATH.'/elements/'.$type;

		// register element class
		JLoader::register('Element', ZOO_ADMIN_PATH.'/elements/element/element.php');

		// load element class
		$elementClass = 'Element'.$type;
		if (!class_exists($elementClass)) {
			$file = JFilterInput::clean(str_replace('_', DS, strtolower(substr($type, 0, 1)) . substr($type, 1)).'.php', 'path');

			if ($elementFile = JPath::find($path, $file)) {
				require_once $elementFile;
			} else {
				return $false;
			}
		}

		if (!class_exists($elementClass)) {
			return $false;
		}

		$element = new $elementClass();

		return $element;
	}

	/*
		Function: toXML
			Returns a XML representation of the element array.

		Parameters:
			$elements - ElementArray

		Returns:
			String - XML representation of element array
	*/
	function toXML($elements){

		$type   = new JSimpleXMLElement('type', array('version' => '1.0.0'));
		$params = new JSimpleXMLElement('params');
		
		foreach ($elements as $element) {
			ElementHelper::_addChild($params, $element->configToXML());
		}
		
		$type = ElementHelper::_addChild($type, $params);

		return '<?xml version="1.0" encoding="utf-8"?>'.$type->toString();
	}

	 /*
		Function: _addChild
			Adds a child to the parent.

		Parameters:
			$parent - Parent as JSimpleXMLElement
			$child - Child as JSimpleXMLElement

		Returns:
			Object - JSimpleXMLElement parent
	*/
	function _addChild(&$parent, $child){

		// init vars
		$name = $child->name();

		if (!isset($parent->$name)) {
			$parent->$name = array();
		}

		$parent->{$name}[]   =& $child;
		$parent->_children[] =& $child;

		return $parent;
	}

}