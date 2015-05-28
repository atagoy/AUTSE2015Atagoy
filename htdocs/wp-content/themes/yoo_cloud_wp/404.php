<?php
/**
* @package   Cloud
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// get warp
$warp = Warp::getInstance();

// render error layout
echo $warp['template']->render('error', array('title' => __('Page not found', 'warp'), 'error' => '404', 'message' => sprintf(__('404_page_message', 'warp'), $warp['system']->url, $warp['config']->get('site_name'))));