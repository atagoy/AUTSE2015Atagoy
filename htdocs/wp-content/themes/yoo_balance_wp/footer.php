<?php
/**
* @package   Balance
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get warp
$warp = Warp::getInstance();

// get content from output buffer and set a slot for the template renderer
$warp['template']->set('content', ob_get_clean());

// load main template file, located in /layouts/template.php
echo $warp['template']->render('template');