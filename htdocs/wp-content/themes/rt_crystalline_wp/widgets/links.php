<?php
/**
 * @package   gantry
 * @subpackage widgets
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * Widget based on the WordPress core Links Widget.
 */ 
 
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetLinks","init"));

class GantryWidgetLinks extends GantryWidget {
    var $short_name = 'links';
    var $wp_name = 'gantry_links';
    var $long_name = 'Gantry Links';
    var $description = 'Gantry Links Widget';
    var $css_classname = 'widget_gantry_links';
    var $width = 200;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetLinks");
    }
    
    function render_title($args, $instance) {
    	global $gantry;
    	if($instance['title'] != '') :
    		echo $instance['title'];
    	endif;
    }

    function render($args, $instance){
        global $gantry, $post;
	    ob_start();
	    
	    $show_description = $instance['description'];
		$show_name = $instance['name'];
		$show_rating = $instance['rating'];
		$show_images = $instance['images'];
		$catid = get_term_by('slug', $instance['category'], 'link_category');
	    $menu_class = $instance['menu_class'];
			
		if($menu_class != '') :
			$menu_class = $menu_class.' ';
		else :
			$menu_class = '';
		endif;
		
		$links = array();
		
		$links = wp_list_bookmarks(apply_filters('widget_links_args', array(
			'title_before' => '<h1 class="title">', 'title_after' => '</h1>',
			'category_before' => '<div class="'.$instance['category_class'].'">', 'category_after' => '</div>',
			'show_images' => $show_images, 'show_description' => $show_description,
			'show_name' => $show_name, 'show_rating' => $show_rating,
			'category' => $catid->term_id, 'class' => 'linkcat widget', 'category_orderby' => $instance['category_orderby'],
			'orderby' => $instance['links_orderby'], 'categorize' => $instance['categorize'], 'title_li' => '', 'limit' => $instance['limit'],
			'link_before' => '<span>', 'link_after' => '</span>'
		)));
		
		$links = ob_get_clean();
		$lines = explode("\n", $links);
		$out = '';
		
		foreach($lines as $line) {
			$line = trim($line);
			if (substr($line, 0, 11) == "<ul class='") :
				$line = str_replace("<ul class='", "<ul class='".$menu_class, $line);
			endif;
			$out .= $line."\n";
		}
		
		if (!$instance['categorize']) :
			echo '<ul class="'.trim($menu_class).'">';
		endif;
		
		echo $out;
	    
	    if (!$instance['categorize']) :
			echo '</ul>';
		endif;
	    
	}
}