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

jimport('joomla.application.component.model');

/*
   Class: ItemModelItems
   The Model Class for items
*/
class ItemModelItems extends JModel {

	/**
	 * Table
	 *
	 * @var array
	 */
	var $_table = 'item';

	/**
	 * Data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	var $_pagination = null;

	/**
	 * Constructor
	 *
	 */
	function __construct() {
		parent::__construct();

		global $mainframe, $option;

		// get request vars
		$filter_order       = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_order', 'filter_order', 'a.created', 'cmd');
		$filter_order_Dir   = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_order_Dir',	'filter_order_Dir',	'desc', 'word');
		$limit		        = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart			= $mainframe->getUserStateFromRequest($option.$this->getName().'.limitstart', 'limitstart', 0,	'int');
		$search	            = $mainframe->getUserStateFromRequest($option.$this->getName().'.search', 'search', '', 'string');
		$filter_category_id = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_category_id', 'filter_category_id', '0:0', 'string');
		$filter_type_id     = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_type_id', 'filter_type_id', 0, 'int');
		$filter_author_id   = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_author_id', 'filter_author_id', 0, 'int');

		// convert search to lower case
		$search = JString::strtolower($search);

		// in case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		// set model vars
		$this->setState('filter_order', $filter_order);
		$this->setState('filter_order_Dir', $filter_order_Dir);
		$this->setState('filter_category_id', $filter_category_id);
		$this->setState('filter_type_id', $filter_type_id);
		$this->setState('filter_author_id', $filter_author_id);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('search', $search);
	}

	/**
	 * Method to get users
	 *
	 * @access public
	 * @return array
	 */
	function getUsers() {

		$db    =& JFactory::getDBO();
		$table =& $this->getTable($this->_table);
		$query = 'SELECT u.id, u.name'
			    .' FROM '.$table->getTableName().' AS i'
			    .' JOIN #__users AS u ON i.created_by = u.id'
			    .' GROUP BY i.created_by';

		$db->setQuery($query);
		
		return $db->loadObjectList("id");
	}

	/**
	 * Method to get groups
	 *
	 * @access public
	 * @return array
	 */
	function getGroups() {

		$db    =& JFactory::getDBO();
		$query = "SELECT g.id, g.name FROM #__groups AS g";

		$db->setQuery($query);

		return $db->loadObjectList("id");
	}

	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getData() {

		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}

	/**
	 * Method to get the total number of items
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal() {

		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination() {

		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	function _buildQuery() {
		$table       =& $this->getTable($this->_table);
		$where       =  $this->_buildContentWhere();
		$orderby     =  $this->_buildContentOrderBy();

		// parse catalog/category
		if (strpos($this->getState('filter_category_id'), ':')) {
			list($catalog_id, $category_id) = explode(':', $this->getState('filter_category_id'), 2);
		} else {
			list($catalog_id, $category_id) = array(0, 0);
		}

		$query = 'SELECT a.*, t.name AS itemtype'
			    .' FROM '.$table->getTableName().' AS a'
			    .' LEFT JOIN '.ZOO_TABLE_TYPE.' AS t ON a.type_id = t.id'
			    .(($catalog_id > 0 || $category_id > 0) ? ' JOIN '.ZOO_TABLE_CATEGORY_ITEM.' AS ci ON a.id = ci.item_id' : '')
			    .$where
			    .' GROUP BY a.id'
			    .$orderby;

		return $query;
	}

	function _buildContentWhere() {
		global $mainframe, $option;

		$db		   =& JFactory::getDBO();		
		$search    = $this->getState('search');
		$type_id   = $this->getState('filter_type_id');
		$author_id = $this->getState('filter_author_id');

		// parse catalog/category
		if (strpos($this->getState('filter_category_id'), ':')) {
			list($catalog_id, $category_id) = explode(':', $this->getState('filter_category_id'), 2);
		} else {
			list($catalog_id, $category_id) = array(0, 0);
		}

		$where = array();

		// catalog filter
		if ($catalog_id > 0) {
			$where[] = 'ci.catalog_id = ' . (int) $catalog_id;
		}
		
		// category filter
		if ($category_id > 0) {
			$where[] = 'ci.category_id = ' . (int) $category_id;
		}

		// type filter
		if ($type_id > 0) {
			$where[] = 'a.type_id = ' . (int) $type_id;
		}
		
		// author filter
		if ($author_id > 0) {
			$where[] = 'a.created_by = ' . (int) $author_id;
		}

		if ($search) {
			$where[] = 'LOWER(a.name) LIKE '.$db->Quote('%'.$db->getEscaped($search, true).'%', false);
		}

		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');

		return $where;
	}

	function _buildContentOrderBy() {
		global $mainframe, $option;

		$orderby = ' ORDER BY '.$this->getState('filter_order').' '.$this->getState('filter_order_Dir');

		return $orderby;
	}

}