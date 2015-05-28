<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperStylesheets
		Stylesheets helper class, creates css html head tags
*/
class WarpHelperStylesheets extends WarpHelper {

	/* style declarations */
	var $_style = array();

	/* stylesheets includes */
	var $_styles = array();

	/*
		Function: get
			Retrieve linked stylesheets

		Returns:
			Array
	*/
	function get() {
		return $this->_styles;
	}

	/*
		Function: getDeclarations
			Retrieve declaration styles

		Returns:
			Array
	*/
	function getDeclarations() {
		return $this->_style;
	}
			
	/*
		Function: add
			Add a linked stylesheet

		Parameters:
			$style - Stylesheet URL
			$attributes - Array of attributes

		Returns:
			Void
	*/
	function add($style, $attributes = array('type' => 'text/css')) {
		
	    if(preg_match('/^(http|https)\:\/\//i', $style)) {
	        $url = $style;
	    } else {
	    
    		// get path helper
    		$path = $this->getHelper('path');
            $url  = $path->url($style);

			if(empty($url)) $url = $style;
		}
        
		// add style url
		$this->_styles[$url] = $attributes;
	}
	
	/*
		Function: addDeclaration
			Add a stylesheet declaration

		Parameters:
			$style - Style declarations
			$type - Type of style declarations

		Returns:
			Void
	*/	
	function addDeclaration($style, $type = 'text/css') {
		$type = strtolower($type);
				
		if (!isset($this->_style[$type])) {
			$this->_style[$type] = $style;
		} else {
			$this->_style[$type] .= chr(13).$style;
		}
	}
	
	/*
		Function: render
			Render stylesheet HTML tags

		Returns:
			String
	*/
	function render() {
	    $html = array();
	
		foreach ($this->_styles as $path => $attributes) {
			$attribs = array();
		
			foreach ($attributes as $key => $value) {
				$attribs[] = sprintf('%s="%s"', $key, $value);
			}

			$html[] = sprintf('<link href="%s" rel="stylesheet" %s />', $path, implode(' ', $attribs));
		}

		foreach ($this->_style as $type => $content) {
			$html[] = sprintf('<style type="%s">', $type);
			$html[] = $content;
			$html[] = '</style>';
		}

	    return implode("\n", $html);
	}

}