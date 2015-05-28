<?php 
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// set header
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');
header('Content-type: text/css; charset=UTF-8');
header('Cache-Control: max-age=86400');

// include file
if (isset($_GET['id']) && $_GET['id']) {

	$id   = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $_GET['id']);
	$file = dirname(__FILE__).'/css-'.$id.'.css';

	if (is_file($file)) {
		include($file);
	}
}