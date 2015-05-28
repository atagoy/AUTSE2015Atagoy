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

class JElementZooTemplate extends JElement {

	var	$_name = 'ZooTemplate';

	function fetchElement($name, $value, &$node, $control_name) {

		// template select
		$path    = ZOO_SITE_PATH.'/templates';
		$ignore  = array('.', '..', '.svn', '.cvs');
		$options = array(JHTML::_('select.option', '', JText::_('Debug')));

		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir($path.DS.$file) && !in_array($file, $ignore)) {
					$label = ucwords(str_replace(array('_', '-'), ' ', $file));
					$options[] = JHTML::_('select.option', $file, $label);
				}
		    }
		    closedir($handle);
		}

		return JHTML::_('select.genericlist', $options, 'params['.$name.']', null, 'value', 'text', $value);
	}
	
}