<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// init vars
$path = dirname(__FILE__);

// load classes
require_once($path.'/classes/object.php');
require_once($path.'/classes/helper.php');
require_once($path.'/helpers/path.php');

class Warp extends WarpObject {
    
    /* helpers */
	var $_helpers = array();
    
    /* dynamic preset selector name */
	var $preset_var = 'preset';
    
	/*
		Function: getInstance
			Retrieve warp instance

		Returns:
			Template
	*/
	function &getInstance() {      
	    static $instance;

        if (!isset($instance)) {
            $instance = new Warp();
            
            $path = dirname(__FILE__);
            
            // add default helper
            $instance->addHelper(new WarpHelperPath());
            
            // set default paths
            $instance->path->register($path, 'warp');
            $instance->path->register($path.'/helpers', 'helpers');
            $instance->path->register($path.'/libraries', 'lib');
            $instance->path->register($path.'/css', 'css');
            $instance->path->register($path.'/js', 'js');
            $instance->path->register($path.'/layouts', 'layouts');
            $instance->path->register($path.'/menus', 'menu');
            $instance->path->register(dirname($path), 'template');
            
            $instance->loadHelper(array('legacy', 'template', 'menu'));
        }

        return $instance;
    }
    
    /*
		Function: getHelper
			Retrieve a helper

		Parameters:
			$name - Helper name
	*/
	function &getHelper($name) {

		// try to load helper, if not found
		if (!isset($this->_helpers[$name])) {
		    $this->loadHelper($name);
		}

		// get helper
		if (isset($this->_helpers[$name])) {
			return $this->_helpers[$name];
		}
		
		return null;
	}

	/*
		Function: addHelper
			Adds a helper

		Parameters:
			$helper - Helper object
			$alias - Helper alias (optional)
	*/
	function addHelper(&$helper, $alias = null) {

		// add to helpers
		$name = $helper->getName();
		$this->_helpers[$name] =& $helper;

		// add alias
		if (!empty($alias)) {
			$this->_helpers[$alias] =& $helper;
		}

		// compatibility for PHP4
		if (version_compare(PHP_VERSION, '5.0', '<')) {
			$this->$name =& $helper;
			if (!empty($alias))	$this->$alias =& $helper;
		}
		
		// execute callbacks
		if (isset($helper->callbacks['construct']) && is_array($helper->callbacks['construct'])) {
			foreach ($helper->callbacks['construct'] as $method) {
				$helper->$method();
			}
		}
	}

	/*
		Function: loadHelper
			Load helper from path

		Parameters:
			$helpers - Helper names
			$prefix - Helper class prefix
	*/
	function loadHelper($helpers, $prefix = 'WarpHelper') {
		$helpers = (array) $helpers;
		
		foreach ($helpers as $name) {
			$class = $prefix.$name;

			// autoload helper class
			if (!class_exists($class) && ($file = $this->path->path('helpers:'.$name.'.php'))) {
			    require_once($file);
			}

			// add helper, if not exists
			if (!isset($this->_helpers[$name])) {
				$this->addHelper(new $class());
			}
		}
	}
    
    /*
		Function: __get
			Retrieve a helper

		Parameters:
			$name - Helper name

		Returns:
			Mixed
	*/	
	function __get($name) {
		return $this->getHelper($name);
	}

}