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
   Class: TypeViewTypes
   The View Class for types
*/
class TypeViewTypes extends JView {

	function display($tpl = null) {
		global $mainframe;

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// set toolbar items
		JToolBarHelper::title(JText::_('Types'), ZOO_ICON);
		JToolBarHelper::custom('docopy', 'copy.png', 'copy_f2.png', 'Copy', false);		
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		JHTML::_('behavior.tooltip');

		// get request vars
		$option           = JRequest::getCmd('option');
		$controller       = JRequest::getWord('controller');
		$name			  = $this->get('name');
		$filter_order	  = $mainframe->getUserStateFromRequest($option.$name.'.filter_order', 'filter_order', 'a.name', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$name.'.filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search	          = $mainframe->getUserStateFromRequest($option.$name.'.search', 'search', '', 'string');
		$search			  = JString::strtolower($search);

		// get data from the model
		$items		=& $this->get('data');
		$total		=& $this->get('total');
		$pagination =& $this->get('pagination');

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('lists', $lists);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}

}