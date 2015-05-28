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

jimport('joomla.filesystem.file');
jimport('joomla.application.component.view');

/*
   Class: CatalogViewCatalog
   The View Class for catalog
*/
class CatalogViewCatalog extends JView {

	function display($tpl = null) {
		global $mainframe;

		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();

		// get request vars
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');
		$cid        = JRequest::getVar('cid', array(0), '', 'array');
		$edit       = intval($cid[0]) > 0;

		// get catalog
		$catalog =& Catalog::getInstance(intval($cid[0]));

		// set toolbar items
		$text = $edit ? JText::_('Edit') : JText::_('New');
		JToolBarHelper::title(JText::_('Catalog').': '.$catalog->name.' <small><small>[ '.$text.' ]</small></small>', ZOO_ICON);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		$edit ?	JToolBarHelper::cancel('cancel', 'Close') : JToolBarHelper::cancel();

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('controller', $controller);
		$this->assignRef('lists', $lists);
		$this->assignRef('catalog', $catalog);

		parent::display($tpl);
	}

}