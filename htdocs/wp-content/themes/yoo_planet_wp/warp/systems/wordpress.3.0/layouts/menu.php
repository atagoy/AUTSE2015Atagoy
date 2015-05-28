<?php
/**
* @package   Warp Theme Framework
* @file      menu.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $wp_registered_widgets;

$wp_registered_widgets['nav_menu-0'] = array(
    'id' => 'nav_menu-0',
    'name' => 'Main menu'
);

$this->warp->system->widget_options['nav_menu-0'] = $default_options;

echo '<!--widget-nav_menu-0-->';
wp_nav_menu(array('theme_location' => 'main_menu'));
echo '<!--widget-end-->';