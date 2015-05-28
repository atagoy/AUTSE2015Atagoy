<?php
/**
* @package   Nano Template
* @file      index.php
* @version   1.0.0 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get warp
$warp = Warp::getInstance();

// load main template file, located in /layouts/template.php
echo $warp['template']->render('template');