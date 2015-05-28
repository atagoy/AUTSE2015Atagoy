<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
require_once(dirname(__FILE__).'/crystalline_fusion/theme.php');
GantryWidgetMenu::registerTheme(dirname(__FILE__).'/crystalline_fusion','crystalline_fusion', __('Crystalline Fusion'), 'CrystallineFusionMenuTheme');
require_once(dirname(__FILE__).'/crystalline_splitmenu/theme.php');
GantryWidgetMenu::registerTheme(dirname(__FILE__).'/crystalline_splitmenu','crystalline_splitmenu', __('Crystalline SplitMenu'), 'CrystallineSplitMenuTheme');
require_once(dirname(__FILE__).'/crystalline_touch/theme.php');
GantryWidgetMenu::registerTheme(dirname(__FILE__).'/crystalline_touch','crystalline_touch', __('Crystalline Touch'), 'CrystallineTouchMenuTheme');