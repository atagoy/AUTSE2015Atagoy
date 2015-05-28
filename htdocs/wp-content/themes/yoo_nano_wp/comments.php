<?php
/**
* @package   Nano Template
* @file      comments.php
* @version   1.0.0 May 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get warp
$warp = Warp::getInstance();

// load template file, located in /warp/systems/wordpress.3.0/layouts/comments.php
echo $warp['template']->render('comments');