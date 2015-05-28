<?php
/**
* @package   Cloud
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get warp
$warp = Warp::getInstance();

// load main template file, located in /layouts/template.php
echo $warp['template']->render('template');