<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register parent class
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
	Class: ElementRadio
		The radio element class
*/
class ElementRadio extends ElementSimple {

	/** @var array */
	var $options;

	/*
	   Function: Constructor
	*/
	function ElementRadio() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'radio';
		$this->options = array();
		
		// set callbacks
		$this->registerCallback('configEditOption');		
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {

		$options = array();

		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$this->value = $this->default;
		}

		foreach ($this->options as $option) {
			$options[] = JHTML::_('select.option', $option['value'], $option['name']);
		}

		return JHTML::_('select.radiolist', $options, $this->name.'_value', null, 'value', 'text', $this->value);
	}
	
	/*
		Function: configLoadAssets
			Load elements css/js config assets.

		Returns:
			Void
	*/
	function configLoadAssets() {
		JHTML::script('radio.js', 'administrator/components/com_zoo/elements/radio/');
		JHTML::stylesheet('radio.css', 'administrator/components/com_zoo/elements/radio/');
	}

	/*
		Function: configBindArray
			Binds the data array.

		Parameters:
			$data - data array
	        $ignore - An array or space separated list of fields not to bind

		Returns:
			Void
	*/
	function configBindArray($data, $ignore = array()) {

		parent::configBindArray($data, $ignore);

		if (!isset($data['options'])) {
			$this->options = array();
		}
	}
	
	/*
	   Function: configBindXML
	       Converts the XML to a Dataarray and calls the bind method.

	   Parameters:
	      XML - The XML for this Element
	*/
	function configBindXML($xml) {

		parent::configBindXML($xml);
		
		if (isset($xml->option)) {
			foreach ($xml->option as $option) {
				$this->options[] = array(
					'name'  => $option->attributes('name'),
					'value' => $option->attributes('value'));
			}
		}
	}
		
	/*
	   Function: configToXML
   	      Get elements XML.

	   Returns:
	      Object - JSimpleXMLElement
	*/
	function configToXML() {
		
		$xml = parent::configToXML(array('options'));
		
		foreach ($this->options as $option) {
			if ($option['name'] != '' || $option['value'] != '') {
				$xml->addChild('option', array(
					'name'  => $option['name'],
					'value' => $option['value']));	
			}
		}
		
		return $xml;
	}

	/*
		Function: configParams
			Get elements params.

		Returns:
			Object - JParameter
	*/
	function &configParams() {
		
		$params = new ElementParameter('');
		$params->setElement($this);

		// set data
		foreach ($this->getProperties() as $name => $value) {
			if ($name != 'options') {
				$params->set($name, $value);
			}
		}
		
		return $params;
	}

	/*
	   Function: configEditOptions
	      Renders elements options for form input.

	   Parameters:
	      $var - form var name

	   Returns:
		  String
	*/
	function configEditOptions($var){
	
		// init vars
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$id         = str_replace(array('[', ']'), '_', $var).'options';		
		$i          = 0;

		// create options
		$options  = '<div id="'.$id.'" class="radio-options">';
		$options .= '<ul>';
		foreach ($this->options as $opt) {
			$options .= '<li>'.$this->configEditOption($var, $i++, $opt['name'], $opt['value']).'</li>';
		}
		$options .= '</ul>';
		$options .= '<div class="option-add">'.JText::_('Add Option').'</div>';
		$options .= '</div>';

		// create js
		$javascript  = "var rad = new ElementRadio('$id', '".JRoute::_('index.php?option='.$option.'&controller='.$controller.'&view=type&format=raw', false)."', { variable: '$var' });";
		$javascript .= "rad.attachEvents();";
		$javascript  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";

		return $options.$javascript;
	}
	
	/*
	   Function: configEditOption
	      Renders a single element option for form input.

	   Parameters:
	      $var - form var name
	      $num - option order number

	   Returns:
		  String
	*/
	function configEditOption($var, $num, $name = null, $value = null){

		$html  = '<div><label for="name">Name</label>';
		$html .= JHTML::_('control.text', $var.'[options]['.$num.'][name]', $name, 'id="name"').'</div>';
		$html .= '<div><label for="value">Value</label>';
		$html .= JHTML::_('control.text', $var.'[options]['.$num.'][value]', $value, 'id="value"').'</div>';
		$html .= '<div class="option-delete" title="'.JText::_('Delete option').'"><img src="'.ZOO_ADMIN_URI.'assets/images/delete.png"/></div>';

		return $html;
	}

}