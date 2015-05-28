<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperTemplate
		Template helper class, render layouts
*/
class WarpHelperTemplate extends WarpHelper {

	/* slots */
	var $_slots = array();
    
	/*
		Function: render
			Render a layout file

		Parameters:
			$name - Layout name
			$args - Array of arguments

		Returns:
			String
	*/	
	function render($name, $args = array()) {
        
		// get helper
		$path  = $this->getHelper('path');
		$event = $this->getHelper('event');

		// trigger event
		$event->trigger('render.'.$name, array(&$name, &$args));

		// load layout
		$__layout = $path->path('layouts:'.$name.'.php');

		// render layout
		if ($__layout != false) {
			
			// import vars and get content
			extract($args);
			ob_start();
			include($__layout);
			return ob_get_clean();
		}
		
		trigger_error('<b>'.$name.'</b> not found in paths: ['.implode(', ', $path->_paths['layouts']).']');
		
		return null;
	}
    
	/*
		Function: has
			Slot exists ?

		Parameters:
			$name - Slot name

		Returns:
			Boolean
	*/	
	function has($name) {
		return isset($this->_slots[$name]);
	}

	/*
		Function: get
			Retrieve a slot

		Parameters:
			$name - Slot name
			$default - Default content

		Returns:
			Mixed
	*/	
	function get($name, $default = false) {
		return isset($this->_slots[$name]) ? $this->slots[$name] : $default;
	}

	/*
		Function: set
			Set a slot

		Parameters:
			$name - Slot name
			$content - Content

		Returns:
			Void
	*/	
	function set($name, $content) {
		$this->_slots[$name] = $content;
	}

	/*
		Function: output
			Outputs slot content

		Parameters:
			$name - Slot name
			$default - Default content

		Returns:
			Boolean
	*/	
	function output($name, $default = false) {

		if (!isset($this->_slots[$name])) {
		
			if (false !== $default) {
				echo $default;
				return true;
			}

			return false;
		}

		echo $this->_slots[$name];
		return true;
	}

}