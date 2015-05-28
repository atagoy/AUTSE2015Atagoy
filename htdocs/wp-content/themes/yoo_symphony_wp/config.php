<?php
/**
* @package   Symphony Template
* @file      config.php
* @version   5.5.0 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

require_once(dirname(__FILE__).'/warp/warp.php');

$warp =& Warp::getInstance();

// add paths
$warp->path->register(dirname(__FILE__).'/warp/systems/wordpress.3.0/helpers','helpers');
$warp->path->register(dirname(__FILE__).'/warp/systems/wordpress.3.0/layouts','layouts');    
$warp->path->register(dirname(__FILE__).'/layouts','layouts');    

$warp->path->register(dirname(__FILE__).'/js', 'js');
$warp->path->register(dirname(__FILE__).'/css', 'css');

// load helpers
$warp->loadHelper(array('system', 'modules', 'javascripts', 'stylesheets', 'useragent', 'cache'));

require_once(dirname(__FILE__)."/presets.php");