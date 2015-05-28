<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetBreadcrumbs","init"));

class GantryWidgetBreadcrumbs extends GantryWidget {
    var $short_name = 'breadcrumbs';
    var $wp_name = 'gantry_breakcrumbs';
    var $long_name = 'Gantry Breadcrumbs';
    var $description = 'Gantry Breadcrumbs Widget';
    var $css_classname = 'widget_gantry_breadcrumbs';
    var $width = 200;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetBreadcrumbs");
    }

    function render($args, $instance){
        global $gantry, $post;
	    ob_start();
	    
	    ?>
	    
	    <a href="<?php bloginfo('url'); ?>" id="breadcrumbs-home"></a>
	    
	    <?php if ((is_page() || is_single()) && !is_front_page()) : ?>
	    
	    <div class="breadcrumbs pathway">
							
			<?php
			
			if(!is_home() || !is_front_page()) :
			
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<div class="module-l"><div class="module-r"><div class="module-inner"><div class="module-inner2"><a href="'.get_permalink($page->ID).'" class="pathway">'.get_the_title($page->ID).'</a></div></div></div></div>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			
			foreach ($breadcrumbs as $crumb) echo '<div class="breadcrumb-wrapper">'.$crumb.'</div>'; ?>
			
			<div class="no-link"><div class="module-l"><div class="module-r"><div class="module-inner"><div class="module-inner2"><?php the_title(); ?></div></div></div></div></div>
			
			<?php endif; ?>
			
		</div>
		
		<?php
		
		endif;
		
		echo ob_get_clean();
	    
	}
}