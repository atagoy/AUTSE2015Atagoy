<?php
/**
* @package   Vanilla
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// find related file in /warp/systems/wordpress.3.0/layouts/comments.php
$warp =& Warp::getInstance();
echo $warp->template->render('comments');