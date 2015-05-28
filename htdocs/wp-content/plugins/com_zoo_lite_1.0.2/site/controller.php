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
	Class: ZooController
		The Zoo site controller Class
*/
class ZooController extends JController {

 	/*
		Function: Constructor

		Parameters:
			$default - Array

		Returns:
			Void
	*/
	function __construct($default = array()) {
		parent::__construct($default);

	}

	function display() {

		// set view
		$view = JRequest::getCmd('view');
		$task = $this->getTask();

		// set layout
		if ($view == 'element' && $task == 'callelement') {
			JRequest::setVar('layout', $task);
		}

		// set the default view
		if (empty($view)) {
			JRequest::setVar('view', 'category');
		}

		parent::display();
	}

}