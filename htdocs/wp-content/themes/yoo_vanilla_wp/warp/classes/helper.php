<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelper
		Helper base class
*/
class WarpHelper extends WarpObject {

	/* warp */
	var $warp;

	/* name */
	var $name;

	/* callbacks */
	var $callbacks = array();

	/*
		Function: Constructor
			Class Constructor.
	*/
	function __construct() {

		// set default name
		$this->warp  =& Warp::getInstance();
		$this->name = strtolower(substr(get_class($this), strlen(__CLASS__)));

	}

	/*
		Function: getName
			Get helper name

		Returns:
			String
	*/	
	function getName() {
		return $this->name;
	}

	/*
		Function: getWarp
			Retrieve related template object

		Returns:
			Template
	*/	
	function &getWarp() {
		return $this->warp;
	}
	
	/*
		Function: getHelper
			Retrieve Helper based on related template object

		Returns:
			Helper
	*/	
	function &getHelper($name) {
		return $this->warp->getHelper($name);
	}

	/*
		Function: _call
			Execute function call

		Returns:
			Mixed
	*/	
	function _call($function, $args = array()) {

		if (is_array($function)) {

			list($object, $method) = $function;

			if (is_object($object)) {
				switch (count($args)) { 
					case 0 :
						return $object->$method();
						break;
					case 1 : 
						return $object->$method($args[0]); 
						break; 
					case 2: 
						return $object->$method($args[0], $args[1]); 
						break; 
					case 3: 
						return $object->$method($args[0], $args[1], $args[2]); 
						break; 
					case 4: 
						return $object->$method($args[0], $args[1], $args[2], $args[3]); 
						break; 
				} 
			}

		}

		return call_user_func_array($function, $args);                               
	}

}