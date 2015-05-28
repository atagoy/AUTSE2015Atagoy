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

jimport('joomla.application.component.view');

/*
	Class: YView
		The View Class. Extends Joomla's JView functionality.
*/
class YView extends JView {

	/*
		Function: partial
			Render a partial view template file

		Parameters:
			$name - Partial name
			$__args - Array of arguments

		Returns:
			String - The output of the the partial
	*/	
	function partial($name, $__args = array()) {

		jimport('joomla.filesystem.path');

		// clean the file name
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', '_'.$name);

		// load the partial
		$file    = $this->_createFileName('template', array('name' => $file));
		$partial = JPath::find($this->_path['template'], $file);

		// render the partial
		if ($partial != false) {

			// remove vars from scope
			unset($name, $file);
			
			// init scope vars
			if (is_array($__args)) {
				foreach ($__args as $__var => $__value) {
					$$__var = $__value;
				}
			}

			// get content
			ob_start();
			include($partial);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		} 

		return JError::raiseError(500, 'Partial Layout "'.$file.'" not found');
	}

}