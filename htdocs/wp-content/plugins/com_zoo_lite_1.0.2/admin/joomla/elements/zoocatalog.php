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

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

class JElementZooCatalog extends JElement {

	var	$_name = 'ZooCatalog';

	function fetchElement($name, $value, &$node, $control_name) {

		// load catalogs
		$db    =& JFactory::getDBO();		
		$query = 'SELECT id AS value, name AS text'
				.' FROM '.ZOO_TABLE_CATALOG
				.' ORDER BY name';	
		$db->setQuery($query);
		
		// set options
		$options = array();
		$options[] = JHTML::_('select.option',  '', '- '.JText::_('Select Catalog').' -');
		$options = array_merge($options, $db->loadObjectList());
		
		return JHTML::_('select.genericlist', $options, 'params['.$name.']', null, 'value', 'text', $value);
	}
	
}