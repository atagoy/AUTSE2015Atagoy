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

class JHTMLControl {

	/*
    	Function: selectfile
    		Returns file select html string.
 	*/
	function selectfile($directory, $filter, $name, $value = null, $attribs = null) {

		// get files
		$options = array(JHTML::_('select.option',  '', '- '.JText::_('Select File').' -'));
		$files   = YFile::readDirectory($directory, '', $filter);

		natsort($files);
		
		foreach ($files as $file) {
			$options[] = JHTML::_('select.option', $file, $file);
		}

		return JHTML::_('select.genericlist', $options, $name, $attribs, 'value', 'text', $value);
	}

	/*
    	Function: label
    		Returns label html string.
 	*/
	function label($name, $for = null, $attribs = null) {

		$html  = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		 }

		$for = ($for != '') ? 'for="'.$for.'"' : ''; 

		$html .= "\n\t<label $for $attribs />$name</label>\n";

		return $html;
	}

	/*
    	Function: textarea
    		Returns form textarea html string.
 	*/
	function textarea($name, $value = null, $attribs = null) {

		$html  = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		 }

		// convert <br /> tags so they are not visible when editing
		$value = str_replace('<br />', "\n", $value);

		$html .= "\n\t<textarea name=\"$name\" $attribs />$value</textarea>\n";

		return $html;
	}

 	/*
    	Function: text
    		Returns form text input html string.
 	*/
	function text($name, $value = null, $attribs = null) {
		return JHTML::_('control.input', 'text', $name, $value, $attribs);
	}

 	/*
    	Function: hidden
    		Returns form hidden input html string.
 	*/
	function hidden($name, $value = null, $attribs = null) {
		return JHTML::_('control.input', 'hidden', $name, $value, $attribs);
	}

 	/*
    	Function: input
    		Returns form input html string.
 	*/
	function input($type, $name, $value = null, $attribs = null) {

		$html = '';

		if (is_array($attribs)) {
			$attribs = JArrayHelper::toString($attribs);
		 }

		$html .= "\n\t<input type=\"$type\" name=\"$name\" value=\"$value\" $attribs />\n";

		return $html;
	}

}