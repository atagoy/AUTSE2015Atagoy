<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperJavascripts
		Javascript helper class, creates javascript html head tags
*/
class WarpHelperJavascripts extends WarpHelper {

	/* script declarations */
	var $_script = array();

	/* script includes */
	var $_scripts = array();

	/*
		Function: get
			Retrieve linked scripts

		Returns:
			Array
	*/
	function get() {
		return $this->_scripts;
	}

	/*
		Function: getDeclarations
			Retrieve declaration scripts

		Returns:
			Array
	*/
	function getDeclarations() {
		return $this->_script;
	}
	
	/*
		Function: add
			Add a linked script

		Parameters:
			$script - Script URL
			$attributes - Array of attributes

		Returns:
			Void
	*/
	function add($script, $attributes = array('type' => 'text/javascript')) {
        
	    if(preg_match('/^(http|https)\:\/\//i', $script)) {
	        $url = $script;
	    } else {
	    
    		// get path helper
    		$path = $this->getHelper('path');
            $url  = $path->url($script);
			
			if(empty($url)) $url = $script;
		}
		
		// add script url
		$this->_scripts[$url] = $attributes;
	}

	/*
		Function: addDeclaration
			Add a script

		Parameters:
			$script - Script
			$type - Type of script

		Returns:
			Void
	*/	
	function addDeclaration($script, $type = 'text/javascript') {
		$type = strtolower($type);

		if (!isset($this->_script[$type])) {
			$this->_script[$type] = $script;
		} else {
			$this->_script[$type] .= chr(13).$script;
		}
	}
	
	/*
		Function: render
			Render script HTML tags

		Returns:
			String
	*/
	function render() {
	    $html = array();
	
		foreach ($this->_scripts as $path => $attributes) {
			$attribs = array();
		
			foreach ($attributes as $key => $value) {
				$attribs[] = sprintf('%s="%s"', $key, $value);
			}

			$html[] = sprintf('<script src="%s" %s></script>', $path, implode(' ', $attribs));
		}

		foreach ($this->_script as $type => $content) {
			$html[] = sprintf('<script type="%s">', $type);
			$html[] = $content;
			$html[] = '</script>';
		}

	    return implode("\n", $html);
	}

}