<?php
/**
* @package   Zoo Menu Module
* @version   1.0.0 2009-03-19 17:25:08
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementZooMenuItem extends JElement {

	var	$_name = 'ZooMenuItem';

	function fetchElement($name, $value, &$node, $control_name) {

		// load menu items
		$db    =& JFactory::getDBO();		
		$query = "SELECT id AS value, name AS text, menutype"
				." FROM  #__menu"
				." WHERE type = 'component' AND published <> -2 AND link LIKE '%option=com_zoo&view=category%'"
				." ORDER BY menutype, name";	
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		// set options
		$menutype  = null;
		$options   = array();
		$options[] = JHTML::_('select.option',  '', '- '.JText::_('Select Menu Item').' -');
		
		foreach ($items as $item) {
			
			// group menutypes
			if ($menutype != $item->menutype) {
				$menutype  = $item->menutype;
				$options[] = JHTML::_('select.optgroup', $item->menutype);
			}
			
			$options[] = JHTML::_('select.option',  $item->value, $item->text);
		}
		
		return JHTML::_('select.genericlist', $options, 'params['.$name.']', null, 'value', 'text', $value);
	}
	
}