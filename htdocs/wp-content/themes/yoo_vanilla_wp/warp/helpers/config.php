<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperConfig
		Config helper class, configuration container
*/    
class WarpHelperConfig extends WarpHelper {

    /*
		Variable: _data
			Config data array.
    */
	var $_data = array();

	/*
		Function: has
			Has a key ?

		Parameters:
			$name - String

		Returns:
			Mixed
	*/
	function has($name) {
		return array_key_exists($name, $this->_data);
	}
	
	/*
		Function: get
			Get a value

		Parameters:
			$name - String
			$default - Default value

		Returns:
			Mixed
	*/
	function get($name, $default = null) {

		if (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		}

		return $default;
	}

 	/*
		Function: set
			Set a value

		Parameters:
			$name - String
			$value - Mixed
			
		Returns:
			Void
	*/
	function set($name, $value) {
		$this->_data[$name] = $value;
	}
	
	/*
		Function: remove
			Remove a value

		Parameters:
			$name - String
			
		Returns:
			Void
	*/
	function remove($name) {
		unset($this->_data[$name]);
	}
    
    /*
		Function: addPreset
			Add a preset

		Parameters:
			$key - String
			$name - String
			$options - Array
			
		Returns:
			Void
	*/
	function addPreset($key, $name, $options) {

    $this->_data['_presets'][$key]['name'] = $name;
    $this->_data['_presets'][$key]['options'] = $options;
	}
    
    /*
		Function: getPreset
			Get a preset

		Parameters:
			$key - String
			
		Returns:
			array
	*/
	function getPreset($key) {
		return isset($this->_data['_presets'][$key]) ? $this->_data['_presets'][$key] : null;
	}
    
    
    /*
		Function: applyPreset
			Apply a preset
			
		Returns:
			void
	*/
	function applyPreset() {
		
        if($this->get('_current_preset',false)){
			$preset = $this->getPreset($this->get('_current_preset'));
			if ($preset) $this->loadArray($preset['options']);
        }
	}

	/*
		Function: __isset
			Has a key ? (via magic method)

		Parameters:
			$name - String

		Returns:
			Mixed
	*/
	function __isset($name) {
		return $this->has($name);
	}

	/*
		Function: __get
			Get a value (via magic method)

		Parameters:
			$name - String

		Returns:
			Mixed
	*/
	function __get($name) {
		return $this->get($name);
	}

 	/*
		Function: __set
			Set a value (via magic method)

		Parameters:
			$name - String
			$value - Mixed
			
		Returns:
			Void
	*/
	function __set($name, $value) {
		$this->set($name, $value);
	}

 	/*
		Function: __unset
			Unset a value (via magic method)

		Parameters:
			$name - String
			
		Returns:
			Void
	*/
	function __unset($name) {
		$this->remove($name);
	}

 	/*
		Function: toArray
			Retrieve data array

		Returns:
			Array
	*/
	function toArray() {
		return $this->_data;
	}

 	/*
		Function: loadArray
			Load data array

		Parameters:
			$array - Array

		Returns:
			Void
	*/
    function loadArray($array){
        if (!is_array($array)) return;
        
        foreach ($array as $name => $value){
			$this->_data[$name] = $value;
		}  
    }

 	/*
		Function: parseString
			Load string data

		Parameters:
			$string - String

		Returns:
			Void
	*/
	function parseString($string) {
        
		$string = trim($string);
		
		if(!strlen($string)) return;
		
		$parts = preg_split('/[\s]+/', $string);

		foreach ($parts as $part) {
			if (strpos($part, '-') !== false) {
				list($name, $value) = explode('-', $part, 2);
				$this->_data[$name] = $value;
			}
		}
	}

}