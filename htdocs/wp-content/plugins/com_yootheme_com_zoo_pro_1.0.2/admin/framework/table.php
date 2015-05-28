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

/*
	Class: YTable
		The Table Class. Extends Joomla's JTable functionality.
*/
class YTable extends JTable {

	/*
	    Variable: _cache
	      Cache object.
	*/	
	var $_cache = null;

	/*
		Function: setCache
			Method to set cache object.

		Parameters:
			$cache - Cache object

		Returns:
			Void
	*/
	function setCache($cache){
		$this->_cache = $cache;
	}

	/*
		Function: queryResult
			Static method to retrieve/query table for a single result.

		Parameters:
			$query - SQL query string
			$numinarray - field index of the result column
			$offset - offset to start selection
			$limit - number of results to return

		Returns:
			Mixed - Result
	*/
	function &queryResult($query, $numinarray = 0, $offset = 0, $limit = 0){

		// query database table or use cached result
		if ($this->_cache) {
			$res = $this->_cache->call(array('YTable', 'queryMode'), 'result', $query, '', $numinarray, $offset, $limit);
		} else {
			$res = YTable::queryMode('result', $query, '', $numinarray, $offset, $limit);
		}

		// return false, on error
		if ($res === false) {
			$this->setError(YError::getError());
			$return = false;
			return $return;
		}

		return $res;
	}
	
	/*
		Function: queryObjectList
			Method to retrieve list of objects from table entries.

		Parameters:
			$query - SQL query string
			$key - field name of a primary key
			$offset - offset to start selection
			$limit - number of results to return
			
		Returns:
			Array - Objects
	*/
	function &queryObjectList($query, $key = '', $offset = 0, $limit = 0){

		// query database table or use cached result
		if ($this->_cache) {
			$res = $this->_cache->call(array('YTable', 'queryMode'), 'objectlist', $query, $key, 0, $offset, $limit);
		} else {
			$res = YTable::queryMode('objectlist', $query, $key, 0, $offset, $limit);
		}

		// return false, on error
		if ($res === false) {
			$this->setError(YError::getError());
			$return = false;
			return $return;
		}

		// if class exists, create class objects
		$table = strtolower(get_class($this));
		if (substr($table, 0, 5) == 'table' && $class = substr($table, 5)) {
			if (class_exists($class)) {

				$objects = array();

				foreach ($res as $key => $row) {
					$objects[$key] = new $class();
					$objects[$key]->setProperties($row);
				}

				return $objects;
			}
		}

		return $res;
	}

	/*
		Function: queryAssocList
			Method to retrieve rows from a table formatted as array with key/value assocciation.

		Parameters:
			$query - SQL query string
			$key - field name of a primary key
			$offset - offset to start selection
			$limit - number of results to return
			
		Returns:
			Array - Objects
	*/
	function &queryAssocList($query, $key = '', $offset = 0, $limit = 0){

		// query database table or use cached result
		if ($this->_cache) {
			$res = $this->_cache->call(array('YTable', 'queryMode'), 'assoclist', $query, $key, 0, $offset, $limit);
		} else {
			$res = YTable::queryMode('assoclist', $query, $key, 0, $offset, $limit);
		}

		// return false, on error
		if ($res === false) {
			$this->setError(YError::getError());
			$return = false;
			return $return;
		}

		return $res;
	}

	/*
		Function: queryMode
			Static method to retrieve/query table entries.

		Parameters:
			$mode - query mode result/resultarray/rowlist/assoclist/objectlist
			$query - SQL query string
			$key - field name of a primary key
			$numinarray - field index of the result column
			$offset - offset to start selection
			$limit - number of results to return

		Returns:
			Mixed - Result
	*/
	function &queryMode($mode, $query, $key = '', $numinarray = 0, $offset = 0, $limit = 0){

		// query database table
		$db =& JFactory::getDBO();
		$db->setQuery($query, $offset, $limit);

		// switch result mode
		switch ($mode) {
			case 'result':
				$res = $db->loadResult();
				break;
			case 'resultarray':
				$res = $db->loadResultArray($numinarray);
				break;
			case 'rowlist':
				$res = $db->loadRowList($key);
				break;
			case 'assoclist':
				$res = $db->loadAssocList($key);
				break;
			case 'objectlist':
			default:
				$res = $db->loadObjectList($key);
				break;
		}
		
		// return false, on error
		if ($db->getErrorNum()) {
			YError::setError($db->getErrorMsg());
			$return = false;
			return $return;
		}

		return $res;
	}

	/*
		Function: query
			Static method to query table.

		Parameters:
			$query - SQL query string
			$offset - offset to start selection
			$limit - number of results to return

		Returns:
			Boolean - True, on success
	*/
	function query($query, $offset = 0, $limit = 0){

		// query database table
		$db =& JFactory::getDBO();
		$db->setQuery($query, $offset, $limit);
		$db->query();
		
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}
	
}