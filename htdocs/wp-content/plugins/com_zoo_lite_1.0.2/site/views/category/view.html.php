<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
	Class: ZooViewCategory
		The View Class for category
*/
class ZooViewCategory extends YView {

	function display($tpl = null) {
		global $mainframe, $Itemid;

		// get dispatcher
		JPluginHelper::importPlugin('zoo');
		$dispatcher =& JDispatcher::getInstance();

		// get params
		$params              =& $mainframe->getParams();
		$catalog_category    = $params->get('catalog_category', '0:0');
		$alpha_index         = $params->get('alpha_index', 2);
		$show_feed_link      = $params->get('show_feed_link', 0);
		$alternate_feed_link = $params->get('alternate_feed_link', '');
		
		// parse catalog/category
		if (strpos($catalog_category, ':')) {
			list($catalog_id, $category_id) = explode(':', $catalog_category, 2);
		} else {
			list($catalog_id, $category_id) = array(0, 0);
		}		

		// init vars
		$user        =& JFactory::getUser();
		$document	 =& JFactory::getDocument();
		$pathway     =& $mainframe->getPathway();
		$template    = $mainframe->getTemplate();
		$option      = JRequest::getCmd('option');
		$controller  = JRequest::getWord('controller');
		$alpha_char  = JRequest::getCmd('alpha_char', '');
		$category_id = (int) JRequest::getInt('category_id', $category_id);

		// get model data
		$catalog    =& $this->get('catalog');
		$categories =& $this->get('categories');
		$items      =& $this->get('items');
		$pagination =& $this->get('pagination');

		// fire trigger
		$dispatcher->trigger('onPrepareDisplayCategory', array(&$this, &$catalog, &$categories, &$items, &$pagination));	

		// raise warning when no catalog id is set
		if (empty($catalog_id)) {
			JError::raiseWarning(500, JText::_('Unable to access catalog'));
			return;
		}

		// raise warning when category can not be accessed
		if (!isset($categories[$category_id])) {
			JError::raiseWarning(500, JText::_('Unable to access category'));
			return;
		}

		// set category and catgories to display
		if ($alpha_index && $alpha_char != '') {
			$category = $categories[0];
			$selected = $categories['index'][$alpha_char];

			// create pathway
			$link = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category&category_id=0');
			$pathway->addItem(JText::_('Alpha Index'), $link);
		} else {
			$category = $categories[$category_id];
			$selected = $categories[$category_id]->_children;

			// create pathway
			foreach ($category->getPathway() as $cat) {
				$link = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category&category_id='.$cat->id);
				$pathway->addItem($cat->getName(), $link);
			}
		}

		// set default layout
		$this->setLayout($category_id == 0 && $alpha_char == '' ? 'catalog' : 'category');

		// set layout override, if exists
		$path_default    = JPATH_BASE."/templates/$template/html/$option/".$this->getName();
		$path_catalog    = $path_default."/".$catalog->alias;
		$path_template   = $params->get('template') ? ZOO_SITE_PATH.'/templates/'.$params->get('template') : null;
		$layout_category = "category_".$category->id;

		if (JPath::find($path_default, $layout_category.'.php')) {
			$this->setLayout($layout_category);
		} else if (JPath::find($path_catalog, 'catalog.php') || JPath::find($path_catalog, 'category.php')) {
			$this->addTemplatePath($path_catalog);
		} else if ($path_template && (JPath::find($path_template, 'catalog.php') || JPath::find($path_template, 'category.php'))) {
			$this->addTemplatePath($path_template);
		}

		// add feed links
		if ($show_feed_link) {
			if ($alternate_feed_link) {
				$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
				$document->addHeadLink($alternate_feed_link, 'alternate', 'rel', $attribs);
			} else {
				$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
				$document->addHeadLink(JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category&format=feed&type=rss'), 'alternate', 'rel', $attribs);
				$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
				$document->addHeadLink(JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category&format=feed&type=atom'), 'alternate', 'rel', $attribs);
			}
		}

		// set template vars
		$this->assignRef('user', $user);
		$this->assignRef('option', $option);
		$this->assignRef('catalog', $catalog);
		$this->assignRef('category', $category);
		$this->assignRef('categories', $categories);
		$this->assignRef('selected_categories', $selected);
		$this->assignRef('items', $items);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('alpha_char', $alpha_char);
		$this->assign('link_base', 'index.php?Itemid='.$Itemid.'&option='.$option);
		$this->assign('sub_categories', $params->get('sub_categories', 1));
		$this->assign('alpha_index', $params->get('alpha_index', 2));
		$this->assign('item_count', $params->get('item_count', 1));
		$this->assign('category_cols', $params->get('category_cols', 1));
		$this->assign('item_cols', $params->get('item_cols', 1));

		// fire trigger
		$dispatcher->trigger('onBeforeDisplayCategory', array(&$this));	

		// call parent display 
		parent::display($tpl);

		// fire trigger
		$dispatcher->trigger('onAfterDisplayCategory', array(&$this));	
	}

}