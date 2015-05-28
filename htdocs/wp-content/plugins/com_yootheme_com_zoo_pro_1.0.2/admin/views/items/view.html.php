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

jimport( 'joomla.application.component.view');

/*
   Class: ItemViewItems
   The View Class for items
*/
class ItemViewItems extends JView {

	function display($tpl = null) {
		global $mainframe;

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// set toolbar items
		JToolBarHelper::title(JText::_('Items'), ZOO_ICON);
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom('docopy', 'copy.png', 'copy_f2.png', 'Copy', false);		
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		JHTML::_('behavior.tooltip');

		// get request vars
		$option             = JRequest::getCmd('option');
		$controller         = JRequest::getWord('controller');
		$name			    = $this->get('name');
		$filter_order	    = $mainframe->getUserStateFromRequest($option.$name.'.filter_order', 'filter_order', 'a.created', 'cmd');
		$filter_order_Dir   = $mainframe->getUserStateFromRequest($option.$name.'.filter_order_Dir', 'filter_order_Dir', 'desc', 'word');
		$filter_category_id = $mainframe->getUserStateFromRequest($option.$name.'filter_category_id', 'filter_category_id', '0:0', 'string');
		$filter_type_id     = $mainframe->getUserStateFromRequest($option.$name.'filter_type_id', 'filter_type_id', 0, 'int');
		$filter_author_id   = $mainframe->getUserStateFromRequest($option.$name.'filter_author_id', 'filter_author_id', 0, 'int');
		$search	            = $mainframe->getUserStateFromRequest($option.$name.'.search', 'search', '', 'string');
		$search			    = JString::strtolower($search);

		// get data from the model
		$users		=& $this->get('users');
		$groups		=& $this->get('groups');
		$items		=& $this->get('data');
		$total		=& $this->get('total');
		$pagination =& $this->get('pagination');

		// category select
		$options = array(JHTML::_('select.option', '0:0', '- '.JText::_('Select Catalog/Category').' -'));
		$lists['select_category'] = JHTML::_('zoo.categorylist', $options, 'filter_category_id', 'class="inputbox auto-submit"', 'value', 'text', $filter_category_id);

		// type select
		$options = array(JHTML::_('select.option', '0', '- '.JText::_('Select Type').' -'));
		$lists['select_type'] = JHTML::_('zoo.typelist',  $options, 'filter_type_id', 'class="inputbox auto-submit"', 'value', 'text', $filter_type_id);

		// author select
		$options = array(JHTML::_('select.option', '0', '- '.JText::_('Select Author').' -'));
		$lists['select_author'] = JHTML::_('zoo.itemauthorlist',  $options, 'filter_author_id', 'class="inputbox auto-submit"', 'value', 'text', $filter_author_id);

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
		$this->assignRef('users', $users);
		$this->assignRef('groups', $groups);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}