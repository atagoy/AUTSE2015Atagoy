<?php
/**
* @package   Nano Template
* @file      config.php
* @version   1.0.0 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

require_once(dirname(__FILE__).'/warp/warp.php');

$warp = Warp::getInstance();

// add paths
$warp['path']->register(dirname(__FILE__).'/warp/systems/wordpress.3.0/helpers','helpers');
$warp['path']->register(dirname(__FILE__).'/warp/systems/wordpress.3.0/layouts','layouts');    
$warp['path']->register(dirname(__FILE__).'/layouts','layouts');    
$warp['path']->register(dirname(__FILE__).'/js', 'js');
$warp['path']->register(dirname(__FILE__).'/css', 'css');

// init system
$warp['system']->init();