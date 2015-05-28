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

class JElementZooCategory extends JElement {

	var	$_name = 'ZooCategory';

	function fetchElement($name, $value, &$node, $control_name) {
		$options = array(JHTML::_('select.option', '0:0', '- '.JText::_('Select Category').' -'));
		return JHTML::_('zoo.categorylist', $options, 'params['.$name.']', null, 'value', 'text', $value);
	}
	
}