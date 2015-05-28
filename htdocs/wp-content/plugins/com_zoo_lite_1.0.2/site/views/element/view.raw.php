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
	Class: ZooViewElement
		The View Class for element (raw)
*/
class ZooViewElement extends YView {

	function display($tpl = null) {

		switch ($this->getLayout()) {
			case 'callelement':
				$this->callElement($tpl);
				break;
		}

	}

	function callElement($tpl = null) {

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();
		
		// get request vars		
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$element 	= JRequest::getWord('element', '');
		$method 	= JRequest::getCmd('method', '');
		$args       = JRequest::getVar('args', array(), 'default', 'array');
		$item_id    = (int) JRequest::getInt('item_id', 0);
		
		JArrayHelper::toString($args);

		// get item 
		$item = new Item($item_id);
		
		// raise error when item can not be accessed
		if (empty($item->id) || !$item->canAccess($user)) {
			JError::raiseError(500, JText::_('Unable to access item'));
			return;
		}

		// get element
		$element = $item->getElement($element);
		
		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('element', $element);
		$this->assignRef('method', $method);
		$this->assignRef('args', $args);
		
		parent::display($tpl);
	}

}