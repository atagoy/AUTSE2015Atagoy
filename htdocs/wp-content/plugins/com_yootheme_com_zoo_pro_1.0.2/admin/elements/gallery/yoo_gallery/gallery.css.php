<?php 
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');
header('Content-type: text/css; charset: UTF-8');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', dirname(__FILE__).DS);

/* ie browser */
$is_ie7 = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 7') !== false;
$is_ie6 = !$is_ie7 && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie 6') !== false;

/* general styling */
loadCSS(PATH_ROOT.'styles/style.css');

if ($is_ie6) { loadCSS(PATH_ROOT.'styles/ie6hacks.css'); }

/* thumbnail: default styling */
loadCSS(PATH_ROOT.'styles/thumbnail_default/style.css');
loadCSS(PATH_ROOT.'styles/thumbnail_default/black/style.css');

/* thumbnail: polaroid styling */
loadCSS(PATH_ROOT.'styles/thumbnail_polaroid/style.css');

/* thumbnail: rounded styling */
loadCSS(PATH_ROOT.'styles/thumbnail_rounded/style.css');
loadCSS(PATH_ROOT.'styles/thumbnail_rounded/black/style.css');

if ($is_ie6) { 
	loadCSS(PATH_ROOT.'styles/thumbnail_rounded/ie6hacks.css');
	loadCSS(PATH_ROOT.'styles/thumbnail_rounded/black/ie6hacks.css');
}

/* thumbnail: cut styling */
loadCSS(PATH_ROOT.'styles/thumbnail_cut/style.css');
loadCSS(PATH_ROOT.'styles/thumbnail_cut/black/style.css');

if ($is_ie6) {
	loadCSS(PATH_ROOT.'styles/thumbnail_cut/ie6hacks.css');
	loadCSS(PATH_ROOT.'styles/thumbnail_cut/black/ie6hacks.css');
}

/* thumbnail: plain styling */
loadCSS(PATH_ROOT.'styles/thumbnail_plain/style.css');

/* css loader */
function loadCSS($file) {
	global $is_ie6;
	
	if (is_readable($file)) {
		$content = file_get_contents($file);
		if ($is_ie6) {
			$content = fixIE6Png($content);
		}
		echo $content;
	}
}

/* ie png fix */
function fixIE6Png($content) {
	if (strpos($content, 'ie6png') === false) return $content;
	$path    = dirname($_SERVER['SCRIPT_NAME']).'/';
	$regex   = "#(.*)background:.*url\((.*)\).*;[[:space:]]*/\*[[:space:]]*ie6png:(scale|crop)[[:space:]]*\*/#";
	$replace = "$1filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='".$path."$2', sizingMethod='$3'); background: none;";		
	return preg_replace($regex, $replace, $content);
}

?>