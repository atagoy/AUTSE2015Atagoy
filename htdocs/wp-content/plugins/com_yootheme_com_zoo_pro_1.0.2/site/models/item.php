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

class ZooModelItem extends JModel {

	var $user        = null;
	var $catalog_id  = null;
	var $category_id = null;
	var $item_id     = null;
	var $catalog     = null;
	var $categories  = null;
	var $item        = null;
	var $cache       = null;

	function __construct() {
		parent::__construct();
		
		global $mainframe;

		// get params
		$params           =& $mainframe->getParams();
		$catalog_category = $params->get('catalog_category', '0:0');

		// parse catalog/category
		if (strpos($catalog_category, ':')) {
			list($this->catalog_id, $this->category_id) = explode(':', $catalog_category, 2);
		} else {
			list($this->catalog_id, $this->category_id) = array(0, 0);
		}		
		
		// init vars
		$this->user        =& JFactory::getUser();
		$this->category_id = (int) JRequest::getInt('category_id', $this->category_id);
		$this->item_id     = (int) JRequest::getInt('item_id', $params->get('item_id', 0));

		// cache
		$this->cache =& JFactory::getCache('com_zoo');
		$this->cache->setCaching(1);
	}

	function getCatalog() {

		if ($this->catalog_id && empty($this->catalog)) {
			$this->catalog =& Catalog::getInstance($this->catalog_id);
		}

		return $this->catalog;
	}
	
	function getCategories() {

		if (empty($this->categories)) {
			
			// get catalog/categories
			$catalog    =& Catalog::getInstance($this->catalog_id);
			$categories = $catalog->getCategories(true);
			
			$this->categories = CategoryHelper::buildTree($categories);
		}

		return $this->categories;
	}

	function getItem() {

		if (empty($this->item)) {
			$this->item = new Item($this->item_id);
			
			// set author
			$author =& JFactory::getUser($this->item->created_by);
			$this->item->author = $author ? $author->name : $this->item->created_by_alias;
		}

		return $this->item;
	}

}