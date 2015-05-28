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

class JHTMLZoo {

	/*
		Function: categoryList
			Returns category select list html string.
	*/
	function categoryList($options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {

		// load catalogs
		$table    =& JTable::getInstance('catalog', 'Table');
		$catalogs = $table->getAll();

		// set options
		if (is_array($options)) {
			reset($options);
		} else {
			$options = array($options);
		}

		foreach ($catalogs as $catalog) {

			// get category tree list
			$tree = CategoryHelper::buildTree($catalog->getCategories());
			$list = CategoryHelper::buildTreeList(0, $tree, array(), '-&nbsp;', '.&nbsp;&nbsp;&nbsp;', '&nbsp;&nbsp;');

			// create options
			$options[] = JHTML::_('select.option', $catalog->id.':0', '&raquo;&nbsp;'.$catalog->name);
			foreach ($list as $category) {
				$options[] = JHTML::_('select.option', $catalog->id.':'.$category->id, $category->treename);
			}
		}

		return JHTML::_('select.genericlist', $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);
	}

 	/*
    	Function: catalogList
    		Returns catalog select list html string.
 	*/
	function catalogList($options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {
		$query = 'SELECT c.id AS value, c.name AS text'
			    .' FROM '.ZOO_TABLE_CATALOG.' AS c'
			    .' ORDER BY c.name';

		return JHTMLZoo::queryList($query, $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);		
	}

 	/*
    	Function: typeList
    		Returns type select list html string.
 	*/
	function typeList($options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {
		$query = 'SELECT t.id AS value, t.name AS text'
			   	.' FROM '.ZOO_TABLE_TYPE.' AS t'
			   	.' ORDER BY t.name';

		return JHTMLZoo::queryList($query, $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);		
	}

 	/*
    	Function: itemAuthorList
    		Returns author select list html string.
 	*/
	function itemAuthorList($options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {
		$query = 'SELECT i.created_by AS value, u.name AS text'
			    .' FROM '.ZOO_TABLE_ITEM.' AS i'
			    .' JOIN #__users AS u ON i.created_by = u.id'
			    .' GROUP BY i.created_by';
			
		return JHTMLZoo::queryList($query, $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);		
	}

 	/*
    	Function: queryList
			Returns select list html string.
 	*/
	function queryList($query, $options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {

		if (is_array($options)) {
			reset($options);
		} else {
			$options = array($options);
		}

		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$list = $db->loadObjectList();
		
		if ($db->getErrorMsg()) {
			echo $db->stderr(true);
		} 
		
		$options = array_merge($options, $list);
		return JHTML::_('select.genericlist', $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);		
	}	

 	/*
    	Function: arrayList
			Returns select list html string.
 	*/
	function arrayList($array, $options, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false) {

		if (is_array($options)) {
			reset($options);
		} else {
			$options = array($options);
		}

		$options = array_merge($options, JHTMLZoo::listOptions($array));
		return JHTML::_('select.genericlist', $options, $name, $attribs, $key, $text, $selected, $idtag, $translate);		
	}	

 	/*
    	Function: selectOptions
    		Returns select option as JHTML compatible array.
 	*/
	function listOptions($array, $value = 'value', $text = 'text') {
		
		$options = array();
		
		if (is_array($array)) {
			foreach ($array as $val => $txt) {
				$options[] = JHTML::_('select.option', strval($val), $txt, $value, $text);
			}
		}

		return $options;
	}

}