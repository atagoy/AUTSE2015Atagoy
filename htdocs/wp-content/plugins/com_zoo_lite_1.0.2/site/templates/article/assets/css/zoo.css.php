<?php 
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');
header('Content-type: text/css; charset: UTF-8');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', dirname(__FILE__) . DS);

/* ie browser */
if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
	$is_ie7 = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 7') !== false;	
	$is_ie6 = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 6') !== false;
}

/* category styling */
include(PATH_ROOT . 'category.css');

/* item styling */
include(PATH_ROOT . 'item.css');

if ($is_ie6 && !$is_ie7) include(PATH_ROOT . 'ie6hacks.css');

?>