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
   Class: ItemViewItem
   The View Class for item
*/
class ItemViewItem extends JView {

	function display($tpl = null) {

		switch ($this->getLayout()) {
			case 'add':
				$this->add($tpl);
				break;
			case 'edit':
			default:
				$this->edit($tpl);
				break;
		}
		
	}

	function add($tpl = null) {

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// get request vars
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');

		// set toolbar items
		JToolBarHelper::title(JText::_('Item') .': <small><small>[ '.JText::_('New').' ]</small></small>');
		JToolBarHelper::cancel();
		
		// get types
		$table =& JTable::getInstance('type', 'Table');
		$types = $table->getAll();

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('controller', $controller);
		$this->assignRef('option', $option);
		$this->assignRef('types',	$types);

		parent::display($tpl);
	}

	function edit($tpl = null) {

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// get request vars
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$cid        = JRequest::getVar('cid', array(0), '', 'array');
		$edit       = intval($cid[0]) > 0;

		// get item
		$item = new Item(intval($cid[0]));

		// set toolbar items
		JToolBarHelper::title(JText::_('Item').': '.$item->name.' <small><small>[ '.($edit ? JText::_('Edit') : JText::_('New')).' ]</small></small>', ZOO_ICON);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$edit ?	JToolBarHelper::cancel('cancel', 'Close') : JToolBarHelper::cancel();
		
		// set defaults, if new
		if ($item->id == 0) {
			$item->type_id      = JRequest::getVar('type_id', 0);
			$item->publish_down = $db->getNullDate();
		}

		// categories select
		$selected = $item->getRelatedCategoryIds();
		$lists['select_categories'] = JHTML::_('zoo.categorylist', array(), 'categories[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $selected);

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('lists', $lists);
		$this->assignRef('item', $item);
		$this->assign('type', $item->getType());

		parent::display($tpl);
	}

}