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
	Class: ZooViewItem
		The View Class for item
*/
class ZooViewItem extends YView {
	
	function display($tpl = null) {
		global $mainframe, $Itemid;

		// get dispatcher
		JPluginHelper::importPlugin('zoo');
		$dispatcher =& JDispatcher::getInstance();

		// init vars
		$db          =& JFactory::getDBO();
		$user        =& JFactory::getUser();
		$document    =& JFactory::getDocument();
		$date        =& JFactory::getDate();
		$pathway     =& $mainframe->getPathway();
		$params      =& $mainframe->getParams();
		$template    = $mainframe->getTemplate();
		$option      = JRequest::getCmd('option');
		$controller  = JRequest::getWord('controller');
		$category_id = (int) JRequest::getInt('category_id', 0);

		// if category_id is set, generate pathway
		if ($category_id) {

			// if category set, get categories from model data
			$categories =& $this->get('categories');

			// raise warning when category can not be accessed
			if (!isset($categories[$category_id])) {
				JError::raiseWarning(500, JText::_('Unable to access category'));
				return;
			}

			// create pathway
			foreach ($categories[$category_id]->getPathway() as $cat) {
				$link = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category&category_id='.$cat->id);
				$pathway->addItem($cat->getName(), $link);
			}
		}

		// get item from model data
		$item =& $this->get('item');

		// fire trigger
		$dispatcher->trigger('onPrepareDisplayItem', array(&$this, &$item));	
		
		// raise warning when item can not be accessed
		if (empty($item->id) || !$item->canAccess($user)) {
			JError::raiseError(500, JText::_('Unable to access item'));
			return;
		}

		// raise warning when item is not published
		$publish_up   =& JFactory::getDate($item->publish_up);
		$publish_down =& JFactory::getDate($item->publish_down);
		if ($item->state != 1 || !(
		   ($item->publish_up == $db->getNullDate() || $publish_up->toUnix() <= $date->toUnix()) &&
		   ($item->publish_down == $db->getNullDate() || $publish_down->toUnix() >= $date->toUnix()))) {
			JError::raiseError(404, JText::_('Item not published'));
			return;
		}

		// create item pathway
		$link = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=item'.($category_id ? '&category_id='.$category_id : '').'&item_id='.$item->id);
		$pathway->addItem($item->name, $link);

		// update hit count
		$item->hit();

		// get page title, if exists
		$title = $item->name;
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();
		if ($menu) {
			$menu_params = new JParameter($menu->params);
			if ($menu_params->get('page_title')) {
				$title = $menu_params->get('page_title');
			}
		}

	 	// set metadata
		$document->setTitle($title);
		if ($item->metadesc) $document->setDescription($item->metadesc);
		if ($item->metakey) $document->setMetadata('keywords', $item->metakey);
		if ($mainframe->getCfg('MetaTitle')) $document->setMetadata('title', $item->name);
		if ($mainframe->getCfg('MetaAuthor')) $document->setMetadata('author', $item->author);
		$metadata = new JParameter($item->metadata);
		foreach ($metadata->toArray() as $tag => $value) {
			if ($value) $document->setMetadata($tag, $value);
		}

		// set default layout
		$this->setLayout('item');

		// set layout override, if exists
		$type          = $item->getType();
		$path_default  = JPATH_BASE."/templates/$template/html/$option/".$this->getName();
		$path_template = $params->get('template') ? ZOO_SITE_PATH.'/templates/'.$params->get('template') : null;
		$layout_type   = $type->alias;
		$layout_item   = "item_".$item->id;

		if (JPath::find($path_default, $layout_item.'.php')) {
			$this->setLayout($layout_item);
		} else if (JPath::find($path_default, $layout_type.'.php')) {
			$this->setLayout($layout_type);
		} else if ($path_template && JPath::find($path_template, 'item.php')) {
			$this->addTemplatePath($path_template);
		}

		// set template vars
		$this->assignRef('option', $option);
		$this->assignRef('item', $item);
		$this->assign('link_base', 'index.php?Itemid='.$Itemid.'&option='.$option);

		// fire trigger
		$dispatcher->trigger('onBeforeDisplayItem', array(&$this));	

		// call parent display 
		parent::display($tpl);

		// fire trigger
		$dispatcher->trigger('onAfterDisplayItem', array(&$this));	
	}

}