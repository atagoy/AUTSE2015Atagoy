<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();


gantry_import('core.gantrywidget');

/**
 * @package     gantry
 * @subpackage  features
 */
add_action('widgets_init', array("GantryWidgetToTop","init"));


class GantryWidgetToTop extends GantryWidget {
    var $short_name = 'totop';
    var $wp_name = 'gantry_totop';
    var $long_name = 'Gantry To Top';
    var $description = 'Gantry To Top Widget';
    var $css_classname = 'totop';
    var $width = 300;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetToTop");
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

        $gantry->addScript('gantry-totop.js');
        
	    ob_start();
	    ?>
		<a href="#" id="gantry-totop"><span><?php echo _g($instance['text']); ?></span></a>
		<?php
	    echo ob_get_clean();
	}
}