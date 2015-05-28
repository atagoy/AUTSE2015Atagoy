<?php
/**
* @package   Symphony Template
* @file      breadcrumbs.php
* @version   5.5.0 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

class Warp_Breadcrumbs extends WP_Widget {

	function Warp_Breadcrumbs() {
		$widget_ops = array('description' => 'Display your sites breadcrumb navigation');
		parent::WP_Widget(false, 'Warp - Breadcrumbs', $widget_ops);      
	}

	function widget($args, $instance) {  
		
		global $wp_query;
		
		extract($args);

		$title = $instance['title'];
		$home_title = trim($instance['home_title']);
		
		if (empty($home_title)) {
			$home_title = 'Home';
		}
		
		echo $before_widget;

		if ($title) {
			echo $before_title . $title . $after_title;
		}
		
		if (!is_home() && !is_front_page()) {
			
			$output = '<div class="breadcrumbs">';
			
			$output .= '<a class="box-1" href="'.get_option('home').'"><span class="box-2"><span class="box-3">';
			$output .= $home_title;
			$output .= '</span></span></a> ';

			if (is_single()) {
				$cats = get_the_category();
				$cat = $cats[0];
				if (is_object($cat)) {
					if ($cat->parent != 0) {

						$cats = explode("@@@", get_category_parents($cat->term_id, TRUE, '@@@'));

						unset($cats[count($cats)-1]);

						for($i=0;$i<count($cats);$i++) {
							$link  = $cats[$i];

							preg_match('/^<a (.*)>(.*)<\/a>$/iU',trim($link),$m);

							if (count($m)!=3) continue;

							$cats[$i] = '<a class="box-1" '.$m[1].'><span class="box-2"><span class="box-3">'.$m[2].'</span></span></a> ';
						}				

						$output .= implode("", $cats);

					} else {
						$output .= '<a class="last box-1" href="'.get_category_link($cat->term_id).'"><span class="box-2"><span class="box-3">'.$cat->name.'</span></span></a>';
					}
				}
			}

			if (is_category()) {

				$cat_obj = $wp_query->get_queried_object();

				$cats = explode("@@@", get_category_parents($cat_obj->term_id, TRUE, '@@@'));

				unset($cats[count($cats)-1]);

				$cats[count($cats)-1] = '<span class="current box-1"><span class="box-2"><span class="box-3">'.strip_tags($cats[count($cats)-1]).'</span></span></span>';

				for($i=0;$i<count($cats)-1;$i++) {
					$link  = $cats[$i];

					preg_match('/^<a (.*)>(.*)<\/a>$/iU',trim($link),$m);

					if (count($m)!=3) continue;

					$cats[$i] = '<a class="box-1 '.($i==count($cats)-1 ? 'last':'').'" '.$m[1].'><span class="box-2"><span class="box-3">'.$m[2].'</span></span></a> ';
				}				

				$output .= implode("", $cats);
				
			} elseif (is_tag()) {
				$output .= '<span class="current last box-1"><span class="box-2"><span class="box-3">'.single_cat_title('',false).'</span></span></span>';
			} elseif (is_date()) {
				$output .= '<span class="current last box-1"><span class="box-2"><span class="box-3">'.single_month_title(' ',false).'</span>';
			} elseif (is_author()) {
				

				$user = !empty($wp_query->query_vars['author_name']) ? get_userdatabylogin($wp_query->query_vars['author']) : get_user_by("id", ((int) $_GET['author']));
				
				$output .= '<span class="current last box-1"><span class="box-2"><span class="box-3">'.$user->display_name.'</span></span></span>';
			} elseif (is_search()) {
				$output .= '<span class="current box-1"><span class="box-2"><span class="box-3">'.stripslashes(strip_tags(get_search_query())).'</span></span></span>';
			} else if (is_tax()) {
				$taxonomy = get_taxonomy (get_query_var('taxonomy'));
				$term = get_query_var('term');
				$output .= '<span class="current last box-1"><span class="box-2"><span class="box-3">'.$taxonomy->label .': '.$term.'</span></span></span>';
			} else {
				$output .= '<span class="current last box-1"><span class="box-2"><span class="box-3">'.get_the_title().'</span></span></span>';
			}

			$output .= '</div>';
			
		} else {
			
			$output = '<div class="breadcrumbs">';
			
			$output .= '<span class="box-1 last"><span class="box-2"><span class="box-3">'.$home_title.'</span></span></span>';
			
			$output .= '</div>';

		}
		
		echo $output;

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$title = esc_attr($instance['title']);
		$home_title = esc_attr($instance['home_title']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','warp'); ?></label>
			<input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('home_title'); ?>"><?php _e('Home title:','warp'); ?></label>
			<input type="text" placeholder="Home" name="<?php echo $this->get_field_name('home_title'); ?>"  value="<?php echo $home_title; ?>" class="widefat" id="<?php echo $this->get_field_id('home_title'); ?>" />
		</p>
<?php
	}

} 

register_widget('Warp_Breadcrumbs');