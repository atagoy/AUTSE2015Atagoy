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
   Class: CategoryViewCategories
   The View Class for categories
*/
class CategoryViewCategories extends JView {

	function display($tpl = null) {

		switch ($this->getLayout()) {
			case 'move':
				$this->move($tpl);
				break;
			case 'default':
			default:
				$this->listing($tpl);
				break;
		}
		
	}

	function listing($tpl = null) {
		global $mainframe;

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// get catalog from the model
		$catalog =& $this->get('catalog');

		// set toolbar items
		JToolBarHelper::title(JText::_('Categories').': '.$catalog->name, ZOO_ICON);
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::customX('move', 'move.png', 'move_f2.png', 'Move', true);
		JToolBarHelper::custom('docopy', 'copy.png', 'copy_f2.png', 'Copy', false);		
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();

		JHTML::_('behavior.tooltip');

		// get request vars
		$model_name       = $this->get('name');
		$option           = JRequest::getCmd('option');
		$controller       = JRequest::getWord('controller');
		$catalog_id       = JRequest::getInt('catalog_id');
		$filter_order	  = $mainframe->getUserStateFromRequest($option.$model_name.'.filter_order', 'filter_order', 'a.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$model_name.'.filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search	          = $mainframe->getUserStateFromRequest($option.$model_name.'.search', 'search', '', 'string');
		$search			  = JString::strtolower($search);

		// get data from the model
		$items		=& $this->get('data');
		$allitems	=& $this->get('allitems');
		$total		=& $this->get('total');
		$pagination =& $this->get('pagination');

		// ensure ampersands and double quotes are encoded in item titles
		foreach ($items as $i => $item) {
			$treename = $item->treename;
			$treename = JFilterOutput::ampReplace($treename);
			$treename = str_replace('"', '&quot;', $treename);
			$items[$i]->treename = $treename;
		}

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
		$this->assignRef('allitems', $allitems);
		$this->assignRef('catalog_id', $catalog_id);
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}

	function move($tpl = null) {
		global $mainframe;
		
		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// get catalog/categories from the model
		$catalog    =& $this->get('catalog');
		$categories =& $this->get('categories');

		// set toolbar items
		JToolBarHelper::title(ZOO_TOOLBAR_TITLE.JText::_('Move Category').': <small><small>[ '.$catalog->name.' ]</small></small>');
		JToolBarHelper::customX('domove', 'move.png', 'move_f2.png', 'Move', false);		
		JToolBarHelper::cancel();

		// get request vars
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$catalog_id = JRequest::getInt('catalog_id');		
		$cid        = JRequest::getVar('cid', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a category to move'));
		}
		
		// target parent category select
		$table    =& JTable::getInstance('catalog', 'Table');
		$catalogs = $table->getAll();

		foreach ($catalogs as $catalog) {
			if ($catalog_id != $catalog->id) {
				
				// get category tree list
				$tree = CategoryHelper::buildTree($catalog->getCategories());
				$list = CategoryHelper::buildTreeList(0, $tree, array(), '-&nbsp;', '.&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;');
		
				// create options
				$options[] = JHTML::_('select.option', $catalog->id.':0', '&raquo;&nbsp;'.$catalog->name);
				foreach ($list as $category) {
					$options[] = JHTML::_('select.option', $catalog->id.':'.$category->id, $category->treename);
				}
			}
		}

		$lists['select_parent'] =JHTML::_('select.genericlist', $options, 'parent', 'class="inputbox" size="25" style="margin:10px;"', 'value', 'text');		

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('controller', $controller);
		$this->assignRef('option', $option);
		$this->assignRef('lists', $lists);
		$this->assignRef('categories', $categories);
		$this->assignRef('catalog_id', $catalog_id);

		parent::display($tpl);
	}

}