<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetCopyright","init"));

class GantryWidgetCopyright extends GantryWidget {
    var $short_name = 'copyright';
    var $wp_name = 'gantry_copyright';
    var $long_name = 'Gantry Copyright';
    var $description = 'Gantry Copyright Widget';
    var $css_classname = 'widget_gantry_copyright';
    var $width = 300;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetCopyright");
    }
    
    function render_widget_open($args, $instance){ ?>
		<div class="clear"></div>
    <?php
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
	    <div id="<?php echo $this->id; ?>" class="widget <?php echo $this->css_classname; ?>">
	    	<div class="rt-block">
			    <div id="developed-by">
			    	<?php _re('Developed By'); ?> <a href="http://www.rockettheme.com/" title="rockettheme.com" id="rocket"></a> 
			    </div>
			    <div id="powered-by">
					<?php _re('Powered By'); ?> <a href="http://www.rockettheme.com/gantry" title="Gantry Framework" id="gantry-logo"></a>
				</div>
			</div>
			<div class="clear"></div>
			<span class="copytext"><?php echo $instance['text'];?></span>
	    </div>
	    <?php
	    echo ob_get_clean();
	}
}