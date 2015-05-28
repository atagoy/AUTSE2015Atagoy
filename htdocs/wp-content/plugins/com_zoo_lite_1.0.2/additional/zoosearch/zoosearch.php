<?php
/**
* @package   Search - Zoo Plugin
* @version   1.0.1 2009-03-21 18:59:22
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

jimport('joomla.plugin.plugin');

class plgSearchZoosearch extends JPlugin {

	/* menu item mapping */
	var $menu;

	/*
		Function: plgSearchZoosearch
		  Constructor.

		Parameters:
	      $subject - Array
	      $params - Array
	
	   Returns:
	      Void
	*/    
	function plgSearchZoosearch(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	/*
		Function: onSearchAreas
		  Get search areas.
	
	   Returns:
	      Array - Search areas
	*/	
	function &onSearchAreas() {
		static $areas = array();
		return $areas;
	}

	/*
		Function: onSearch
		  Get search results. The sql must return the following fields that are used in a common display routine: href, title, section, created, text, browsernav

		Parameters:
	      $text - Target search string
	      $phrase - Matching option, exact|any|all
	      $ordering - Ordering option, newest|oldest|popular|alpha|category
	      $areas - An array if the search it to be restricted to areas, null if search all
	
	   Returns:
	      Array - Search results
	*/
	function onSearch($text, $phrase = '', $ordering = '', $areas = null) {

		$db	  =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$date =& JFactory::getDate();

		// init vars
		$now  = $db->Quote($date->toMySQL());
		$null = $db->Quote($db->getNullDate());		
		$text = trim($text);

		// return empty array, if no search text provided
		if (empty($text)) {
			return array();
		}

		// get plugin info
	 	$plugin   =& JPluginHelper::getPlugin('search', 'zoosearch');
	 	$params   = new JParameter($plugin->params);
		$fulltext = $params->get('search_fulltext', 1) && strlen($text) > 3 && intval($db->getVersion()) >= 4;
		$limit    = $params->get('search_limit', 50);

		// prepare search query
		switch ($phrase) {
			case 'exact':

				if ($fulltext) {
					$where = 'MATCH(a.name, a.metakey, a.metadesc, a.search_data) AGAINST (\'"'.$db->getEscaped($text).'"\' IN BOOLEAN MODE)';
				} else {
					$text		= $db->Quote('%'.$db->getEscaped($text, true).'%', false);
					$wheres2 	= array();
					$wheres2[] 	= 'a.name LIKE '.$text;
					$wheres2[] 	= 'a.metakey LIKE '.$text;
					$wheres2[] 	= 'a.metadesc LIKE '.$text;
					$wheres2[] 	= 'a.search_data LIKE '.$text;
					$where 		= '(' .implode(') OR (', $wheres2).')';
				}

				break;

			case 'all':
			case 'any':
			default:

				if ($fulltext) {
					$where = 'MATCH(a.name, a.metakey, a.metadesc, a.search_data) AGAINST (\''.$db->getEscaped($text).'\' IN BOOLEAN MODE)';
				} else {
					$words 	= explode(' ', $text);
					$wheres = array();

					foreach ($words as $word) {
						$word      = $db->Quote('%'.$db->getEscaped($word, true).'%', false);
						$wheres2   = array();
						$wheres2[] = 'a.name LIKE '.$word;
						$wheres2[] = 'a.metakey LIKE '.$word;
						$wheres2[] = 'a.metadesc LIKE '.$word;
						$wheres2[] = 'a.search_data LIKE '.$word;
						$wheres[]  = implode(' OR ', $wheres2);
					}

					$where = '('.implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres).')';
				}
		}

		// set search ordering
		switch ($ordering) {
			case 'newest':
				$order = 'a.created DESC';
				break;

			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
			case 'category':
			default:
				$order = 'a.name ASC';
		}

		// set and execute search query
		$query = "SELECT DISTINCT a.id, a.name AS title, a.created AS created, a.search_data AS text, '' AS section, '2' AS browsernav, b.catalog_id"
                ." FROM ".ZOO_TABLE_ITEM." AS a"
				." LEFT JOIN ".ZOO_TABLE_CATEGORY_ITEM." AS b ON a.id = b.item_id"
                ." WHERE (".$where.")"
                ." AND a.state = 1"
                ." AND a.access <= ".(int) $user->get('aid', 0)
				." AND (a.publish_up = ".$null." OR a.publish_up <= ".$now.")"
				." AND (a.publish_down = ".$null." OR a.publish_down >= ".$now.")"
                ." ORDER BY ".$order;
		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();

		foreach ($rows as $key => $row) {
			$itemid = $this->getItemId($row->catalog_id);
			$rows[$key]->href = 'index.php?option=com_zoo&view=item&item_id='.$row->id.($itemid ? '&Itemid='.$itemid : null);
		}

		return $rows;
	}
	
	/*
		Function: getItemId
		  Get menu item id.

		Parameters:
	      $cat_id - Catalog id
		
	   Returns:
	      Int - Menu item id
	*/	
	function getItemId($cat_id) {

		if (empty($this->menu)) {
			$this->menu = array();
			
			if ($items = MenuHelper::getItemsByComponent('com_zoo', true)) {
				foreach ($items as $i => $item) {
					$params = new JParameter($item->params);
					
					// parse catalog/category
					if (strpos($params->get('catalog_category'), ':')) {
						list($catalog_id, $category_id) = explode(':', $params->get('catalog_category'), 2);
					} else {
						list($catalog_id, $category_id) = array(0, 0);
					}		

					// set catalog id/menu item id
					if ($catalog_id && !isset($this->menu[$catalog_id])) {
						$this->menu[$catalog_id] = $catalog_id;
					}
				}
			}
		}

		if (isset($this->menu[$cat_id])) {
			return $this->menu[$cat_id];
		}

		return 0;
	}	

}