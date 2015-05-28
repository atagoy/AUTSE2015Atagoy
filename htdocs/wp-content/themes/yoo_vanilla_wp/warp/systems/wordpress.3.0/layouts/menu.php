<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $wp_registered_widgets;

$wp_registered_widgets['nav_menu-0'] = array(
    'id' => 'nav_menu-0',
    'name' => 'Main menu'
);

$this->warp->system->widget_options['nav_menu-0'] = isset($default_options) ? $default_options:array();

echo '<!--widget-nav_menu-0-->';
wp_nav_menu(array('theme_location' => 'main_menu'));
echo '<!--widget-end-->';