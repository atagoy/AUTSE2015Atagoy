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
	Class: MenuHelper
		The Helper Class for JMenu
*/
class MenuHelper {
	
	/*
 		Function: getItemIdsByComponent
 	      Method to get menu items by component.

	   Parameters:
	      $component - component name
	      $published - only published menu items

	   Returns:
	      Array - Menu Items
	*/
	function getItemsByComponent($component, $published = false) {

		$db    =& JFactory::getDBO();
		$query = "SELECT id FROM #__components"
		        ." WHERE `option` = ".$db->Quote($component);

		$db->setQuery($query);
		$id = $db->loadResult();

		if (!$id) {
			return null;
		}

		$query = "SELECT * FROM #__menu"
		        ." WHERE componentid = ".$id
		        .($published ? " AND published = 1" : null);

		$db->setQuery($query);
		return $db->loadObjectList();
	}

}