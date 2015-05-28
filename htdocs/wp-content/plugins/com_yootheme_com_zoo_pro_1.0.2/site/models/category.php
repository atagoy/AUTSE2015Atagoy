<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class ZooModelCategory extends JModel {

	var $user           = null;
	var $catalog_id     = null;
	var $category_id    = null;
	var $alpha_index    = null;
	var $items_per_page = null;
	var $item_order     = null;
	var $page           = null;
	var $catalog        = null;
	var $categories     = null;
	var $items          = null;
	var $feed_items     = null;
	var $total          = null;
	var $cache          = null;

	function __construct() {
		parent::__construct();
		
		global $mainframe;

		// get params
		$params               =& $mainframe->getParams();
		$catalog_category     = $params->get('catalog_category', '0:0');
		$this->alpha_index    = $params->get('alpha_index', 2);
		$this->items_per_page = $params->get('items_per_page', 15);
		$this->item_order     = $params->get('item_order', '');
		
		// parse catalog/category
		if (strpos($catalog_category, ':')) {
			list($this->catalog_id, $this->category_id) = explode(':', $catalog_category, 2);
		} else {
			list($this->catalog_id, $this->category_id) = array(0, 0);
		}		

		// init vars
		$this->user        =& JFactory::getUser();
		$this->category_id = (int) JRequest::getInt('category_id', $this->category_id);
		$this->page        = (int) JRequest::getInt('page', 1);

		// cache
		$this->cache =& JFactory::getCache('com_zoo');
		$this->cache->setCaching(1);
	}

	function getCatalog() {

		if (empty($this->catalog)) {
			$this->catalog =& Catalog::getInstance($this->catalog_id);
		}

		return $this->catalog;
	}

	function getCategories() {

		if (empty($this->categories)) {
			
			// get catalog/categories
			$catalog    =& Catalog::getInstance($this->catalog_id);
			$categories = $catalog->getCategories(true);
			$item_count = $catalog->getCategoryItemCount($this->user->get('aid', 0));

			$this->categories = CategoryHelper::buildTree($categories, $item_count, $this->alpha_index);
		}

		return $this->categories;
	}

	function getItems() {

		if (empty($this->items)) {

			// set order
			$orders = array('date' => 'a.created ASC', 'rdate' => 'a.created DESC',
							'alpha' => 'a.name ASC', 'ralpha' => 'a.name DESC',
							'hits' => 'a.hits DESC', 'rhits' => 'a.hits ASC');
			$order = isset($orders[$this->item_order]) ? $orders[$this->item_order] : '';
			
			// get items
			$table =& JTable::getInstance('item', 'Table');
			$table->setCache($this->cache);
			$this->items = $table->getFromCategory($this->catalog_id, $this->category_id, true, $this->user->get('aid', 0), $order);
			
			// set pagination
			$this->pagination = new YPagination('page', count($this->items), $this->page, $this->items_per_page);
			$this->pagination->setShowAll($this->items_per_page == 0);

			// slice out items
			if (!$this->pagination->getShowAll()) {
				$this->items = array_slice($this->items, $this->pagination->limitStart(), $this->items_per_page);
			}
		}

		return $this->items;
	}

	function getFeedItems() {
		global $mainframe;

		if (empty($this->feed_items)) {

			// get params
			$params =& $mainframe->getParams();
			$limit  = (int) $mainframe->getCfg('feed_limit');
			$catalog_category = $params->get('catalog_category', '0:0');

			// parse catalog/category
			if (strpos($catalog_category, ':')) {
				list($catalog_id, $category_id) = explode(':', $catalog_category, 2);
			} else {
				list($catalog_id, $category_id) = array(0, 0);
			}		

			// get categories
			$categories   = CategoryHelper::buildTreeList($category_id, $this->getCategories());
			$category_ids = array_merge(array($category_id), array_keys($categories));
			
			// get items
			$table =& JTable::getInstance('item', 'Table');
			$table->setCache($this->cache);
			$this->feed_items = $table->getFromCategory($catalog_id, $category_ids, true, $this->user->get('aid', 0), 'a.created DESC', 0, $limit);
		}

		return $this->feed_items;
	}

	function getPagination() {
		return $this->pagination;
	}

}