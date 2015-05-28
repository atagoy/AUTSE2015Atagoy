<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

defined('GANTRY_VERSION') or die();


gantry_import('core.gantrywidget');

/**
 * @package     gantry
 * @subpackage  features
 */
add_action('widgets_init', array("GantryWidgetResetSettings","init"));


class GantryWidgetResetSettings extends GantryWidget {
    var $short_name = 'resetsettings';
    var $wp_name = 'gantry_resetsettings';
    var $long_name = 'Gantry Reset Settings';
    var $description = 'Gantry Reset Settings Widget';
    var $css_classname = 'widget_gantry_resetsettings';
    var $width = 300;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetResetSettings");
    }

    function render_widget_open($args, $instance){
    }
    
    function render_widget_close($args, $instance){
    }
    
    function pre_render($args, $instance) {
    }
    
    function post_render($args, $instance) {
    }

	function render($args, $instance){
		global $gantry;
	    ob_start();
	    ?>
		<div class="clear"></div>
		<span  id="gantry-resetsettings">
            [ <a href="<?php echo $gantry->addQueryStringParams($gantry->getCurrentUrl($gantry->_setbyurl),array('reset-settings'=>'')); ?>"><?php echo _g($instance['text']); ?></a> ]
		</span>
		<div class="clear"></div>
	    <?php
	    echo ob_get_clean();
	}
}