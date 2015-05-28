<?php 
/**
* @package   Warp Theme Framework
* @file      gzip.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// set gzip handler
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) @ob_start('ob_gzhandler');

// include file
if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {

	$query = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $_SERVER['QUERY_STRING']);

	if (($file = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.$query)) && is_file($file)) {
		if ($type = pathinfo($file, PATHINFO_EXTENSION)) {
			
			// set header
			if ($type == 'css') header('Content-type: text/css; charset=UTF-8');
			if ($type == 'js') header('Content-type: application/x-javascript');
			header('Cache-Control: max-age=86400');

			// load file
			include($file);

		}
	}

}