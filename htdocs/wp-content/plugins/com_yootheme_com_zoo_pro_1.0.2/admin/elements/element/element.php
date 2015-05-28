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

// defines 
define('ZOO_VIEW_ITEM', 0);
define('ZOO_VIEW_CATEGORY', 1);
define('ZOO_VIEW_FEED', 2);

/*
	Class: Element
		The Element abstract class
*/
class Element {

	/** @var string */
	var $name;
	/** @var string */
	var $type;
	/** @var string */
	var $label;
	/** @var string */
	var $description;
	/** @var string */
	var $default;
	/** @var int */
	var $display = 3;
	/** @var int */
	var $ordering = 0;
	/** @var type object */
	var $_metaxml;
	/** @var type object */
	var $_type;
	/** @var item object */
	var $_item;
	/** @var var type array */
	var $_var_types = array();	
	/** @var callback array */
	var $_callbacks = array();
	/** @var error array */
	var $_errors = array();

	/*
	   Function: Constructor
	*/
	function Element() {

		// init vars
		$this->type  = 'element';
	}

	/*
		Function: getVarTypes
			Get elements var names and types.
			Can be overloaded/supplemented by the child class.

		Returns:
			Array - Varibale names and type
	*/
	function getVarTypes() {
		
		$vars = array();
		
		foreach ($this->_var_types as $name => $type) {
			$vars[$this->name.$name] = $type;
		}

		return $vars;
	}

	/*
		Function: getDisplay
			Display element on item view (1), category view (2), both (3), hide (0)
		
		Returns:
			Int - display number
	*/
	function getDisplay() {
		return $this->display;
	}

	/*
		Function: getLayout
			Get element layout path and use override if exists.
		
		Returns:
			String - Layout path
	*/
	function getLayout() {

		// init vars
		$path_template = ZOO_SITE_TEMPLATE_PATH.'/element';
		$path_site     = ZOO_SITE_PATH.'/views/element/tmpl';
		$path_type     = $path_template.'/'.$this->_type->alias;
		$layout        = $this->type.'.php';
				
		// find layout
		if (JPath::find($path_type, $layout)) {
			return $path_type.'/'.$layout;
		} else if (JPath::find($path_template, $layout)) {
			return $path_template.'/'.$layout;
		} else if (JPath::find($path_site, $layout)) {
			return $path_site.'/'.$layout;
		}

		return null;
	}

	/*
		Function: getSearchData
			Get elements search data.
			Can be overloaded/supplemented by the child class.
					
		Returns:
			String - Search data
	*/
	function getSearchData() {
		return null;
	}

	/*
		Function: getOrdering
			Get order number.
		
		Returns:
			Int - order number
	*/
	function getOrdering() {
		return $this->ordering;
	}

	/*
		Function: getItem
			Get related item object.
		
		Returns:
			Item - item object
	*/
	function &getItem() {
		return $this->_item;
	}

	/*
		Function: getType
			Get related type object.

		Returns:
			Type - type object
	*/
	function &getType() {
		return $this->_type;
	}

	/*
		Function: getClone
			Get object clone.

		Returns:
			Element - element object
	*/
	function getClone() {
		
		// PHP4 compatibility
		if (version_compare(PHP_VERSION, '5.0.0', '<')) {
			return $this;
		}

		return clone($this);
	}

	/*
		Function: setOrdering
			Set order number.

		Parameters:
			$ordering - order number

		Returns:
			Void
	*/
	function setOrdering($ordering) {
		$this->ordering = $ordering;
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
	}

	/*
		Function: setType
			Set related type object.

		Parameters:
			$type - type object

		Returns:
			Void
	*/
	function setType(&$type) {
		$this->_type =& $type;
	}

	/*
		Function: hasValue
			Checks if the element's value is set.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		return false;
	}

	/*
		Function: render
			Renders the element.
			Can be overloaded/supplemented by the child class.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {
		return null;
	}

	/*
		Function: renderLayout
			Renders the element using template layout file.
			Can be overloaded/supplemented by the child class.

	   Parameters:
            $__layout - layouts template file
	        $__args - layouts template file

		Returns:
			String - html
	*/
	function renderLayout($__layout, $__args = array()) {
		
		// init vars
		if (is_array($__args)) {
			foreach ($__args as $__var => $__value) {
				$$__var = $__value;
			}
		}
	
		// render layout
		$__html = '';
		ob_start();
		include($__layout);
		$__html = ob_get_contents();
		ob_end_clean();
		
		return $__html;
	}

	/*
	   Function: edit
	       Renders the edit form field.
		   Can be overloaded/supplemented by the child class.

	   Returns:
	       String - html
	*/
	function edit() {
		return null;
	}

	/*
    	Function: bind
    	  Binds a named array/hash to this object.
		  Can be overloaded/supplemented by the child class.
		
		Parameters:
	      $from - An associative array or object.
	
	   Returns:
	      Void
 	*/
	function bind($data) {
	}

	/*
		Function: save
			Save the elements item data.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true on success
	*/
	function save() {
		return true;
	}

	/*
		Function: delete
		    Deletes the elements item data.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true on success
	*/
	function delete(){
		return true;
	}

	/*
		Function: loadAssets
			Load elements css/js assets.
			Can be overloaded/supplemented by the child class.

		Returns:
			Void
	*/
	function loadAssets() {
	}

	/*
		Function: configMetaData
			Get elements xml meta data.
			Can be overloaded/supplemented by the child class.

		Returns:
			Array - Meta information
	*/
	function configMetaData() {

		$data = array();
		$xml  =& $this->configMetaXML();

		if (!$xml) {
			return false;
		}

		$type = $xml->document->attributes('type');
		$data['type'] = $type ? $type : 'Unknown';

		$group = $xml->document->attributes('group');
		$data['group'] = $group ? $group : 'Unknown';

		$hidden = $xml->document->attributes('hidden');
		$data['hidden'] = $hidden ? $hidden : 'false';
			
		$element =& $xml->document->name[0];
		$data['name'] = $element ? $element->data() : '';

		$element =& $xml->document->creationDate[0];
		$data['creationdate'] = $element ? $element->data() : 'Unknown';

		$element =& $xml->document->author[0];
		$data['author'] = $element ? $element->data() : 'Unknown';

		$element =& $xml->document->copyright[0];
		$data['copyright'] = $element ? $element->data() : '';

		$element =& $xml->document->authorEmail[0];
		$data['authorEmail'] = $element ? $element->data() : '';

		$element =& $xml->document->authorUrl[0];
		$data['authorUrl'] = $element ? $element->data() : '';

		$element =& $xml->document->version[0];
		$data['version'] = $element ? $element->data() : '';

		$element =& $xml->document->description[0];
		$data['description'] = $element ? $element->data() : '';

		return $data;
	}
	
	/*
		Function: configMetaXML
			Get elements xml meta file.
			Can be overloaded/supplemented by the child class.

		Returns:
			Object - JSimpleXMLElement
	*/
	function &configMetaXML() {

		if (empty($this->_metaxml)) {
			$path = ZOO_ADMIN_PATH.'/elements/'.$this->type.'/'.$this->type.'.xml';

			if (file_exists($path)) {
				$xml =& JFactory::getXMLParser('Simple');
				if ($xml->loadFile($path)) {
					$this->_metaxml =& $xml;
				}
			}
		}

		return $this->_metaxml;
	}

	/*
		Function: configBindArray
			Binds the data array.
			Can be overloaded/supplemented by the child class.

		Parameters:
			$data - data array
	        $ignore - An array or space separated list of fields not to bind

		Returns:
			Void
	*/
	function configBindArray($data, $ignore = array()) {

		// convert space separated list
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}
		
		// bind data array
		foreach ($this->getProperties() as $name => $value) {
			if (isset($data[$name]) && !in_array($name, $ignore)) {
				$this->$name = $data[$name];
			}
		}
		
		// set default label
		if (empty($this->label)) {
			$this->label = $data['name'];
		}
	}

	/*
		Function: configBindXML
			Converts the XML to a Dataarray and calls the bind method.
			Can be overloaded/supplemented by the child class.

		Parameters:
			$xml - XML for this element
	        $ignore - An array or space separated list of fields not to bind
	
		Returns:
			Void
	*/
	function configBindXML($xml, $ignore = array()) {

		$attributes = $xml->attributes();

		// convert space separated list
		if (!is_array($ignore)) {
			$ignore = explode(' ', $ignore);
		}

		// bind xml data
		if (is_array($attributes)) {
			foreach ($attributes as $name => $value) {
				if (!in_array($name, $ignore)) {
					$this->$name = $value;
				}
			}
		}
	}

	/*
		Function: configToXML
			Get elements XML.
			Can be overloaded/supplemented by the child class.

		Parameters:
	        $ignore - An array or space separated list of fields not to include

		Returns:
			Object - JSimpleXMLElement
	*/
	function configToXML($ignore = array()) {
		
		$xml = new JSimpleXMLElement('param');
		$xml->addAttribute('type', $this->type);
		$xml->addAttribute('name', $this->name);
		$xml->addAttribute('label', $this->label);

		if ($xmlfile =& $this->configMetaXML()) {
			if ($params =& $xmlfile->document->params[0]->children()) {
				foreach ($params as $param) {
					$name = $param->attributes('name');
					if (isset($this->$name) && !in_array($name, $ignore)) {
						$xml->addAttribute($name, $this->$name);
					}
				}
			}
		}
		
		return $xml;
	}

	/*
		Function: configParams
			Get elements params.
			Can be overloaded/supplemented by the child class.

		Returns:
			Object - JParameter
	*/
	function &configParams() {
		
		$params = new ElementParameter('');
		$params->setElement($this);

		// set data
		foreach ($this->getProperties() as $name => $value) {
			$params->set($name, $value);
		}
		
		return $params;
	}

	/*
		Function: configEdit
			Renders elements edit configuration form input.
			Can be overloaded/supplemented by the child class.

		Parameters:
			$var - form var name

		Returns:
			String - html form input
	*/
	function configEdit($var) {

		$params	= $this->configParams();

		if ($xml =& $this->configMetaXML()) {
			if ($ps = & $xml->document->params) {
				foreach ($ps as $p) {
					$params->setXML($p);
				}
				
				return $params->render($var);
			}
		}

		return null;
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

		// validate element name
		if (!preg_match('/^[a-z][a-z0-9_]{3,24}$/i', $data['name'])) {
			$this->setError('Invalid name "'.$data['name'].'"');
			return false;
		}		
		
		return true;
	}

	/*
		Function: configAdd
			Adds elements configuration.
			Can be overloaded/supplemented by the child class.

		Parameters:
			$data - element configuration

		Returns:
			Boolean - true on success
	*/
	function configAdd($data){
		return true;
	}

	/*
		Function: configSave
			Saves elements configuration.
			Can be overloaded/supplemented by the child class.

		Parameters:
			$data - element configuration

		Returns:
			Boolean - true on success
	*/
	function configSave($data){
		return true;
	}

	/*
		Function: configDelete
			Deletes elements configuration.
			Can be overloaded/supplemented by the child class.

		Returns:
			Boolean - true on success
	*/
	function configDelete(){
		return true;
	}

	/*
		Function: configLoadAssets
			Load elements css/js config assets.
			Can be overloaded/supplemented by the child class.

		Returns:
			Void
	*/
	function configLoadAssets() {
	}

	/*
		Function: registerCallback
			Register a callback function.
			Can be overloaded/supplemented by the child class.

		Returns:
			Void
	*/
	function registerCallback($method) {
		if (!in_array(strtolower($method), $this->_callbacks)) {
			$this->_callbacks[] = strtolower($method);
		}
	}

	/*
		Function: callback
			Execute elements callback function.
			Can be overloaded/supplemented by the child class.

		Returns:
			Mixed
	*/
	function callback($method, $args = array()) {
		// try to call a elements class method
		if (in_array(strtolower($method), $this->_callbacks) && method_exists($this, $method)) {
			// call method
			$res = call_user_func_array(array($this, $method), $args);
			// output if method returns a string
			if (is_string($res)) {
				echo $res;
			}
		} else {
			// trigger callback method not found error
			jexit('Invalid Callback');
		}
	}

	/*
		Function: getProperties
			Returns an associative array of object properties.

		Parameters:
			$public - If true, returns only the public properties

		Returns:
			Array - Object property/variable names
	*/
	function getProperties( $public = true ) {

		$vars = get_object_vars($this);

        if ($public) {
			foreach ($vars as $key => $value) {
				if ('_' == substr($key, 0, 1)) {
					unset($vars[$key]);
				}
			}
		}

        return $vars;
	}

	/*
		Function: setProperties
			Set the element properties based on a named array/hash.

		Parameters:
			$properties - Either and associative array or another object

		Returns:
			Boolean - True on success
	*/
	function setProperties($properties) {

		$properties = (array) $properties; //cast to an array

		if (is_array($properties)) {
			foreach ($properties as $k => $v) {
				$this->$k = $v;
			}

			return true;
		}

		return false;
	}

	/*
		Function: getError
			Get the most recent error message.

		Parameters:
			$i - Option error index
			$toString - Indicates if JError objects should return their error message

		Returns:
			String - Error message
	*/
	function getError($i = null, $toString = true) {
		
		// find the error
		if ($i === null) {
			// default, return the last message
			$error = end($this->_errors);
		} else if (!array_key_exists($i, $this->_errors)) {
			// if $i has been specified but does not exist, return false
			return false;
		} else {
			$error = $this->_errors[$i];
		}

		// check if only the string is requested
		if (JError::isError($error) && $toString) {
			return $error->toString();
		}

		return $error;
	}

	/*
		Function: setError
			Add an error message.

		Parameters:
			$error - Error message

		Returns:
			Void
	*/
	function setError($error) {
		array_push($this->_errors, $error);
	}	

}

/*
	Class: ElementParameter
		ElementParameter functions to process/visualize elements meta data.
*/
class ElementParameter extends JParameter {
    
	/*
       Variable: _element
         Related element.
    */
	var $_element = null;
	
	/*
 		Function: setElement
 	  	  Set related element.
		
		Parameters:
	      $element - Element object.
	
	   Returns:
	      Void	
	*/
	function setElement($element) {
		$this->_element = $element;
	}

	/*
		Function: getParam
	  	  Override. Render a parameter type.
		
		Parameters:
	      $node - A param tag node.
	      $control_name - The control name.
	      $group - Param group.
	
	   Returns:
	      Array	- Any array of the label, the form element and the tooltip
	*/
	function getParam(&$node, $control_name = 'params', $group = '_default') {

		//get the type of the parameter
		$type = $node->attributes('type');

		//remove any occurance of a mos_ prefix
		$type = str_replace('mos_', '', $type);

		$element =& $this->loadElement($type);
		
		// check element method
		if ($element === false && method_exists($this->_element, $type)) {
			$name  = $node->attributes('name');
			$label = $node->attributes('label');
			$descr = $node->attributes('description');
			$value = $this->get($node->attributes('name'), $node->attributes('default'), $group);

			//make sure we have a valid label
			$label = $label ? $label : $name;
			$result[0] = JElement::fetchTooltip($label, $descr, $node, $control_name, $name);
			$result[1] = call_user_func(array(&$this->_element, $type), $control_name);
			$result[2] = $descr;
			$result[3] = $label;
			$result[4] = $value;
			$result[5] = $name;

			return $result;
		}

		// error happened
		if ($element === false) {
			$result[0] = $node->attributes('name');
			$result[1] = JText::_('Element not defined for type').' = '.$type;
			$result[5] = $result[0];

			return $result;
		}

		//get value
		$value = $this->get($node->attributes('name'), $node->attributes('default'), $group);

		return $element->render($node, $value, $control_name);
	}
	
}