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

class JElementZooItem extends JElement {

	var	$_name = 'ZooItem';

	function fetchElement($name, $value, &$node, $control_name) {
		global $mainframe;

		JHTML::_('behavior.modal', 'a.modal');

		// init vars
		$document 	=& JFactory::getDocument();
		$field_name	= $control_name.'['.$name.']';
		$item_name  = JText::_('Select Item');
		
		if ($value) {
			$item = new Item($value);
			$item_name = $item->name;
		}
		
		$js = "
		function jSelectArticle(id, title, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
		}";
		$document->addScriptDeclaration($js);

		$link = 'index.php?option=com_zoo&amp;controller=item&amp;task=element&amp;tmpl=component&amp;object='.$name;

		$html  = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select Item').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 800, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$field_name.'" value="'.(int)$value.'" />';

		return $html;
	}

}