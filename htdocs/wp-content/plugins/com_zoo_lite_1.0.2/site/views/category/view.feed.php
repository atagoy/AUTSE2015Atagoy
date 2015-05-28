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
	Class: ZooViewCategory (RSS Feed)
		The View Class for category
*/
class ZooViewCategory extends YView {

	function display($tpl = null) {
		global $mainframe, $Itemid;

		// get params
		$params         =& $mainframe->getParams();
		$show_feed_link = $params->get('show_feed_link', 0);
		$feed_title     = $params->get('feed_title', '');

		// init vars
		$document	=& JFactory::getDocument();
		$option     = JRequest::getCmd('option');
		$controller = JRequest::getWord('controller');

		// raise error when feed is link is disabled
		if (empty($show_feed_link)) {
			JError::raiseError(500, JText::_('Unable to access feed'));
			return;
		}

		// get model data
		$feed_items =& $this->get('feeditems');

		// set title
		if ($feed_title) {
			$document->setTitle(html_entity_decode($this->escape($feed_title)));
		}

		// set feed link
		$document->link = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=category');

		foreach ($feed_items as $feed_item) {

			// create feed item
			$item         = new JFeedItem();
			$item->title  = html_entity_decode($this->escape($feed_item->name));
			$item->link   = JRoute::_('index.php?Itemid='.$Itemid.'&option='.$option.'&view=item&item_id='.$feed_item->id);
			$item->date   = $feed_item->created;
			$item->author = $feed_item->getAuthor();

			// set description
			$item->description = '';
			foreach ($feed_item->getElements() as $element) {
				if ($element->type == 'textarea') {
					$item->description .= $element->render(ZOO_VIEW_FEED);
				}
			}
						
			// add to feed document
			$document->addItem($item);
		}
	}

}