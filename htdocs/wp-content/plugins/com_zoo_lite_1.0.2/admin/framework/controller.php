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

jimport('joomla.application.component.controller');

/*
	Class: YController
		The Controller Class. Extends Joomla's JController functionality.
*/
class YController extends JController {

	/*
		Function: cleanCacheOnTask
			Clean the component cache triggered by a task

		Parameters:
			$name - Cache name
			$tasks - Array of task names

		Returns:
			Void	
	*/	
	function cleanCacheOnTask($name, $tasks = array()) {
		if (in_array(JRequest::getCmd('task', ''), $tasks)) {
			$cache =& JFactory::getCache($name);
			$cache->clean();
		}
	}
	
}