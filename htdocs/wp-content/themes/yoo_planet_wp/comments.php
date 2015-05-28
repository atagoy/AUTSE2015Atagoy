<?php
/**
* @package   Planet Template
* @file      comments.php
* @version   5.5.1 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// find related file in /warp/systems/wordpress.3.0/layouts/comments.php
$warp =& Warp::getInstance();
echo $warp->template->render('comments');