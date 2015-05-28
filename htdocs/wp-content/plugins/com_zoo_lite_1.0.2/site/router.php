<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

function ZooBuildRoute(&$query) {

	// init vars
	$segments = array();

	// category view
	if (@$query['view'] == 'category' && isset($query['category_id'])) {
		$segments[] = 'category';
		$segments[] = CategoryHelper::translateAlias((int) $query['category_id']);		
		unset($query['view']);
		unset($query['category_id']);

		// pagination
		if (isset($query['page'])) {
			$segments[] = $query['page'];
			unset($query['page']);
		}
	}

	// alpha index view
	else if (@$query['view'] == 'category' && isset($query['alpha_char'])) {
		$segments[] = 'alphaindex';
		$segments[] = $query['alpha_char'];
		unset($query['view']);
		unset($query['alpha_char']);
	}

	// feed view
	else if (@$query['view'] == 'category' && @$query['format'] == 'feed' && isset($query['type'])) {
		$segments[] = 'feed';
		$segments[] = $query['type'];
		unset($query['view']);
		unset($query['format']);
		unset($query['type']);
	}

	// item view
	else if (@$query['view'] == 'item' && isset($query['category_id'], $query['item_id'])) {
		$segments[] = 'item';
		$segments[] = CategoryHelper::translateAlias((int) $query['category_id']);
		$segments[] = ItemHelper::translateAlias((int) $query['item_id']);
		unset($query['view']);
		unset($query['category_id']);
		unset($query['item_id']);
	}

	// element view
	else if (@$query['view'] == 'element' && @$query['task'] == 'callelement' && @$query['format'] == 'raw'
	         && isset($query['item_id'], $query['element'], $query['method'])) {
		$segments[] = 'callelement';
		$segments[] = ItemHelper::translateAlias((int) $query['item_id']);
		$segments[] = $query['element'];
		$segments[] = $query['method'];
		unset($query['view']);
		unset($query['task']);
		unset($query['format']);
		unset($query['item_id']);
		unset($query['element']);
		unset($query['method']);
	}

	return $segments;
}

function ZooParseRoute($segments) {
		
	// init vars
	$vars  = array();
	$count = count($segments);

	// fix segments (see JRouter::_decodeSegments)
	foreach (array_keys($segments) as $key) {
		$segments[$key] = str_replace(':', '-', $segments[$key]);
	}

	// category view
	if ($count == 2 && $segments[0] == 'category') {
		$vars['view']        = 'category';
		$vars['category_id'] = (int) CategoryHelper::getCategoryIdByAlias($segments[1]);			
	}

	// category view with pagination
	else if ($count == 3 && $segments[0] == 'category') {
		$vars['view']        = 'category';
		$vars['category_id'] = (int) CategoryHelper::getCategoryIdByAlias($segments[1]);			
		$vars['page']        = (int) $segments[2];			
	}

	// alpha index view
	else if ($count == 2 && $segments[0] == 'alphaindex') {
		$vars['view']       = 'category';
		$vars['alpha_char'] = (string) $segments[1];			
	}

	// feed view
	else if ($count == 2 && $segments[0] == 'feed') {
		$vars['view']   = 'category';
		$vars['format'] = 'feed';			
		$vars['type']   = (string) $segments[1];			
	}

	// item view
	else if ($count == 3 && $segments[0] == 'item') {
		$vars['view']        = 'item';
		$vars['category_id'] = (int) CategoryHelper::getCategoryIdByAlias($segments[1]);			
		$vars['item_id']     = (int) ItemHelper::getItemIdByAlias($segments[2]);			
	}

	// element view
	else if ($count == 4 && $segments[0] == 'callelement') {
		$vars['view']    = 'element';
		$vars['task']    = 'callelement';
		$vars['format']  = 'raw';
		$vars['item_id'] = (int) ItemHelper::getItemIdByAlias($segments[1]);			
		$vars['element'] = (string) $segments[2];			
		$vars['method']  = (string) $segments[3];			
	}
		
	return $vars;
}