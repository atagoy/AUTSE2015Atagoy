<?php
/**
* @package   Warp Theme Framework
* @file      menu.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $wp_registered_widgets;

$default_options = array(
    'style' => '',
    'icon' => '',
    'badge' => '',
    'display' => array('*') 
);

$wp_registered_widgets['nav_menu-0'] = array(
    'id' => 'nav_menu-0',
    'name' => 'Main menu'
);

$this['system']->widget_options['nav_menu-0'] = $default_options;

echo '<!--widget-nav_menu-0-->';
wp_nav_menu(array('theme_location' => 'main_menu', 'fallback_cb' => create_function('', 'wp_nav_menu(array("fallback_cb" => false));')));
echo '<!--widget-end-->';