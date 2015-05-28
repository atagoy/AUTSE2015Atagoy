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

/*
	Class: YFilterOutput
		The Filter Output Class.
*/
class YFilterOutput extends JFilterOutput {
		
	/*
		Function: stringDBSafe
	 		This method processes a string and replaces all accented UTF-8 characters by unaccented
			ASCII-7 "equivalents", whitespaces are replaced by underscore and the string is lowercased.

	   Parameters:
			$string - string

	   Returns:
			String - safe string
	*/	
	function stringDBSafe($string) {
		//remove any '_' from the string they will be used as concatonater
		$str = str_replace('_', ' ', $string);

		$lang =& JFactory::getLanguage();
		$str = $lang->transliterate($str);

		// remove any duplicate whitespace, and ensure all characters are alphanumeric
		$str = preg_replace(array('/\s+/','/[^A-Za-z0-9_]/'), array('_',''), $str);

		// lowercase and trim
		$str = trim(strtolower($str));
		$str = trim($str, '_');
		return $str;
	}
	
}