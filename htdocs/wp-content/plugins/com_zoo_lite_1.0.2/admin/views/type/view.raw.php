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

jimport( 'joomla.application.component.view');

/*
	Class: TypeViewType
		The View Class for type (raw)
*/
class TypeViewType extends JView {

	function display($tpl = null) {

		switch ($this->getLayout()) {
			case 'addelement':
				$this->addElement($tpl);
				break;

			case 'callelement':
				$this->callElement($tpl);
				break;
		}

	}

	function addElement($tpl = null) {

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();
		
		// get request vars		
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$element 	= JRequest::getWord('element', 'text');
		$count		= JRequest::getVar('count', 0);

		// load element
		$element =& ElementHelper::_loadElement($element);

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('element', $element);
		$this->assign('var', 'new_elements['.$count.']');
		
		parent::display($tpl);
	}

	function callElement($tpl = null) {

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();
		
		// get request vars		
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$element 	= JRequest::getWord('element', 'text');
		$method 	= JRequest::getCmd('method', '');
		$args       = JRequest::getVar('args', array(), 'default', 'array');
		
		JArrayHelper::toString($args);

		// load element
		$element =& ElementHelper::_loadElement($element);

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