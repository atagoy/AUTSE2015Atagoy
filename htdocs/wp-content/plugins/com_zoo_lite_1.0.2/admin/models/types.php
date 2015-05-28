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
   Class: TypeModelTypes
   The Model Class for types
*/
class TypeModelTypes extends JModel {

	/**
	 * Table
	 *
	 * @var array
	 */
	var $_table = 'type';

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
		$filter_order     = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_order', 'filter_order', 'a.name', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$this->getName().'.filter_order_Dir',	'filter_order_Dir',	'', 'word');
		$limit		      = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart	      = $mainframe->getUserStateFromRequest($option.$this->getName().'.limitstart', 'limitstart', 0, 'int');
		$search	          = $mainframe->getUserStateFromRequest($option.$this->getName().'.search', 'search', '', 'string');

		// convert search to lower case
		$search = JString::strtolower($search);

		// in case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		// set model vars
		$this->setState('filter_order', $filter_order);
		$this->setState('filter_order_Dir', $filter_order_Dir);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('search', $search);
	}

	/**
	 * Method to get item data
	 *
	 * @access public
	 * @return array
	 */
	function getData() {
		// Lets load the content if it doesn't already exist
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
		// Lets load the content if it doesn't already exist
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
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	function _buildQuery() {
		$table   =& $this->getTable($this->_table);
		$where   =  $this->_buildContentWhere();
		$orderby =  $this->_buildContentOrderBy();

		$query = ' SELECT a.*'
			. ' FROM '.$table->getTableName().' AS a '
			. $where
			. $orderby
		;

		return $query;
	}

	function _buildContentWhere() {
		global $mainframe, $option;

		$db		=& JFactory::getDBO();
		$search = $this->getState('search');

		$where = array();

		if ($search) {
			$where[] = 'LOWER(a.name) LIKE '.$db->Quote('%'.$db->getEscaped( $search, true ).'%', false);
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