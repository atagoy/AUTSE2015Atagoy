<?php 

if (function_exists('create_initial_post_types')) create_initial_post_types(); //fix for wp 3.0 beta 1
if (function_exists('add_custom_background')) add_custom_background();
if (function_exists('add_post_type_support')) add_post_type_support( 'page', 'excerpt' );


/* activate shortcodes */
require_once(TEMPLATEPATH . '/epanel/shortcodes/shortcodes.php');

/* activate page templates */
require_once(TEMPLATEPATH . '/epanel/page_templates/page_templates.php'); 
add_filter('widget_text', 'do_shortcode'); 

add_filter('body_class','et_browser_body_class');
function et_browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}

/* import epanel settings */
require_once(TEMPLATEPATH . '/epanel/import_settings.php'); 


/*this function allows for the auto-creation of post excerpts*/
function truncate_post($amount,$echo=true,$post='') {
	global $shortname;
	
	if ( $post == '' ) global $post;
		
	$postExcerpt = '';
	$postExcerpt = $post->post_excerpt;
	
	if (get_option($shortname.'_use_excerpt') == 'on' && $postExcerpt <> '') { 
		if ($echo) echo $postExcerpt;
		else return $postExcerpt;	
	} else {
		$truncate = $post->post_content;
		
		$truncate = preg_replace('@\[caption[^\]]*?\].*?\[\/caption]@si', '', $truncate);
		
		if ( strlen($truncate) <= $amount ) $echo_out = ''; else $echo_out = '...';
		$truncate = apply_filters('the_content', $truncate);
		$truncate = preg_replace('@<script[^>]*?>.*?</script>@si', '', $truncate);
		$truncate = preg_replace('@<style[^>]*?>.*?</style>@si', '', $truncate);
		
		$truncate = strip_tags($truncate);
		
		if ($echo_out == '...') $truncate = substr($truncate, 0, strrpos(substr($truncate, 0, $amount), ' '));
		else $truncate = substr($truncate, 0, $amount);

		if ($echo) echo $truncate,$echo_out;
		else return ($truncate . $echo_out);
	};
}


/*this function truncates titles to create preview excerpts*/
function truncate_title($amount,$echo=true,$post='') {
	if ( $post == '' ) $truncate = get_the_title(); 
	else $truncate = $post->post_title; 
	if ( strlen($truncate) <= $amount ) $echo_out = ''; else $echo_out = '...';
	$truncate = mb_substr( $truncate, 0, $amount, 'UTF-8' );
	if ($echo) {
		echo $truncate;
		echo $echo_out;
	}
	else { return ($truncate . $echo_out); }
}


/*this function allows users to use the first image in their post as their thumbnail*/
function first_image() {
	global $post, $posts;
	$img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$img = $matches [1] [0];

	return trim($img);
} 


/* this function gets thumbnail from Post Thumbnail or Custom field or First post image */
function get_thumbnail($width=100, $height=100, $class='', $alttext='', $titletext='', $fullpath=false, $custom_field='', $post='')
{
	if ( $post == '' ) global $post;
	global $shortname, $posts;
	
	$thumb_array['thumb'] = '';
	$thumb_array['use_timthumb'] = true;
	if ($fullpath) $thumb_array['fullpath'] = ''; //full image url for lightbox
	
	if ( function_exists('has_post_thumbnail') ) {
		if ( has_post_thumbnail( $post->ID ) ) {
			$thumb_array['use_timthumb'] = false;
			
			$args='';
			if ($class <> '') $args['class'] = $class;
			if ($alttext <> '') $args['alt'] = $alttext;
			if ($titletext <> '') $args['title'] = $titletext;
			
			$thumb_array['thumb'] = get_the_post_thumbnail( $post->ID, array($width,$height), $args );
			
			if ($fullpath) {
				$thumb_array['fullpath'] = get_the_post_thumbnail( $post->ID );
				if ($thumb_array['fullpath'] <> '') { 
					$thumb_array['fullpath'] = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $thumb_array['fullpath'], $matches);
					$thumb_array['fullpath'] = trim($matches[1][0]);
				}
			}
		}
	}
	
	if ($thumb_array['thumb'] == '') {
		if ($custom_field == '') $thumb_array['thumb'] = get_post_meta($post->ID, 'Thumbnail', $single = true);
		else { 
			$thumb_array['thumb'] = get_post_meta($post->ID, $custom_field, $single = true);
			if ($thumb_array['thumb'] == '') $thumb_array['thumb'] = get_post_meta($post->ID, 'Thumbnail', $single = true);
		}
		
		if (($thumb_array['thumb'] == '') && ((get_option($shortname.'_grab_image')) == 'on')) $thumb_array['thumb'] = first_image();
		
		#if custom field used for small pre-cropped image, open Thumbnail custom field image in lightbox
		if ($fullpath) {
			if ($custom_field == '') $thumb_array['fullpath'] = apply_filters('et_fullpath', $thumb_array['thumb']);
			elseif ( $custom_field <> '' && get_post_meta($post->ID, 'Thumbnail', $single = true) ) $thumb_array['fullpath'] = apply_filters( 'et_fullpath', get_post_meta($post->ID, 'Thumbnail', $single = true) );
		}
	}
			
	return $thumb_array;
}


/* this function prints thumbnail from Post Thumbnail or Custom field or First post image */
function print_thumbnail($thumbnail = '', $use_timthumb = true, $alttext = '', $width = 100, $height = 100, $class = '', $echoout = true, $forstyle = false, $resize = true, $post='') {
	if ( $post == '' ) global $post;
	
	$output = '';
	$thumbnail_orig = $thumbnail;
	
	$thumbnail = et_multisite_thumbnail($thumbnail);
	
	$cropPosition = get_post_meta($post->ID, 'etcrop', true) ? get_post_meta($post->ID, 'etcrop', true) : '';
	if ($cropPosition <> '') $cropPosition = '&amp;a=' . $cropPosition;
	
	if ($forstyle === false) {
	
		if ($use_timthumb === false) {
			$output = $thumbnail_orig;
		} else { 
			$output = '<img src="'.get_bloginfo('template_directory').'/timthumb.php?src='.$thumbnail.'&amp;h='. $height .'&amp;w='. $width .'&amp;zc=1&amp;q=90'.$cropPosition.'"';
		
			if ($class <> '') $output .= " class='$class' ";

			$output .= " alt='$alttext' width='$width' height='$height' />";
			
			if (!$resize) $output = $thumbnail;
		}
	
	} else {
		
		$output = $thumbnail;
		
		if ($use_timthumb === false) {
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $output, $matches);
			$output = $matches[1][0];
		} else {
			$output = get_bloginfo('template_directory').'/timthumb.php?src='.$output.'&amp;h='.$height.'&amp;w='.$width.'&amp;q=90&amp;zc=1'.$cropPosition;
		}
		
	}
	
	if ($echoout) echo $output;
	else return $output;
	
}

function et_multisite_thumbnail($thumbnail='') {
	global $post;
	if (get_post_meta($post->ID, 'blogid', true)) $blog_id = get_post_meta($post->ID, 'blogid', true);
	else global $blog_id;

	if (isset($blog_id) && $blog_id > 0) {
		$imagePath = explode('/files/', $thumbnail);
		if (isset($imagePath[1])) {
			$thumbnail = '/blogs.dir/' . $blog_id . '/files/' . $imagePath[1];
		}
	}
	
	return $thumbnail;
}

function et_is_portrait($imageurl, $post='', $ignore_cfields = false){
	if ( $post == '' ) global $post;
	
	if ( get_post_meta($post->ID,'et_disable_portrait',true) == 1 ) return false;
	
	if ( !$ignore_cfields ) {
		if ( get_post_meta($post->ID,'et_imagetype',true) == 'l' ) return false;
		if ( get_post_meta($post->ID,'et_imagetype',true) == 'p' ) return true;
	}
	
	$et_thumb_size = getimagesize($imageurl);
	$et_thumb_width = $et_thumb_size[0];
	$et_thumb_height = $et_thumb_size[1];
	
	$result = ($et_thumb_width < $et_thumb_height) ? true : false;
	
	return $result;
}

function in_subcat($blogcat,$current_cat='') {
	$in_subcategory = false;
	
	if (cat_is_ancestor_of($blogcat,$current_cat) || $blogcat == $current_cat) $in_subcategory = true;
		
    return $in_subcategory;
}


function show_page_menu($customClass = 'nav clearfix', $addUlContainer = true, $addHomeLink = true){
	global $shortname, $themename, $exclude_pages, $strdepth, $page_menu, $is_footer;
	
	//excluded pages
	if (get_option($shortname.'_menupages') <> '') $exclude_pages = implode(",", get_option($shortname.'_menupages'));
	
	//dropdown for pages
	$strdepth = '';
	if (get_option($shortname.'_enable_dropdowns') == 'on') $strdepth = "depth=".get_option($shortname.'_tiers_shown_pages');
	if ($strdepth == '') $strdepth = "depth=1";
	
	if ($is_footer) { $strdepth="depth=1"; $strdepth2 = $strdepth; }
	
	$page_menu = wp_list_pages("sort_column=".get_option($shortname.'_sort_pages')."&sort_order=".get_option($shortname.'_order_page')."&".$strdepth."&exclude=".$exclude_pages."&title_li=&echo=0");
	
	if ($addUlContainer) echo('<ul class="'.$customClass.'">');
		if (get_option($shortname . '_home_link') == 'on' && $addHomeLink) { ?> 
			<li <?php if (is_front_page() || is_home()) echo('class="current_page_item"') ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home',$themename); ?></a></li>
		<?php };
		
		echo $page_menu;
	if ($addUlContainer) echo('</ul>');
	
};


function show_categories_menu($customClass = 'nav clearfix', $addUlContainer = true){
	global $shortname, $themename, $category_menu, $exclude_cats, $hide, $strdepth2, $projects_cat;
		
	//excluded categories
	if (get_option($shortname.'_menucats') <> '') $exclude_cats = implode(",", get_option($shortname.'_menucats')); 
	
	//hide empty categories
	if (get_option($shortname.'_categories_empty') == 'on') $hide = '1';
	else $hide = '0';
	
	//dropdown for categories
	$strdepth2 = '';
	if (get_option($shortname.'_enable_dropdowns_categories') == 'on') $strdepth2 = "depth=".get_option($shortname.'_tiers_shown_categories'); 
	if ($strdepth2 == '') $strdepth2 = "depth=1";
	
	$args = "orderby=".get_option($shortname.'_sort_cat')."&order=".get_option($shortname.'_order_cat')."&".$strdepth2."&exclude=".$exclude_cats."&hide_empty=".$hide."&title_li=&echo=0";
	
	$categories = get_categories($args);
	
	if (!empty($categories)) {
		$category_menu = wp_list_categories($args);	
		if ($addUlContainer) echo('<ul class="'.$customClass.'">');
			if ($category_menu <> '<li>No categories</li>') echo($category_menu); 
		if ($addUlContainer) echo('</ul>');
	};
	
};


function head_addons(){
	global $shortname, $default_colorscheme;
	
	if (get_option($shortname.'_color_scheme') <> $default_colorscheme) { ?>
		<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style-<?php echo(get_option($shortname.'_color_scheme'));?>.css" type="text/css" media="screen" />
	<?php }; 

	if (get_option($shortname.'_child_css') == 'on') { //Enable child stylesheet  ?>
		<link rel="stylesheet" href="<?php echo(get_option($shortname.'_child_cssurl')); ?>" type="text/css" media="screen" />
	<?php };
	
	//prints the theme name, version in meta tag
	$theme_info = get_theme_data(TEMPLATEPATH . '/style.css');	
	echo '<meta content="'.$theme_info['Name'].' v.'.$theme_info['Version'].'" name="generator"/>';

	if (get_option($shortname.'_custom_colors') == 'on') custom_colors_css();
	
};// end function head_addons()
add_action('wp_head','head_addons',7);


function integration_head(){
	global $shortname;
	if (get_option($shortname.'_integration_head') <> '' && get_option($shortname.'_integrate_header_enable') == 'on') echo(get_option($shortname.'_integration_head')); 
};
add_action('wp_head','integration_head',12);

function integration_body(){
	global $shortname;
	if (get_option($shortname.'_integration_body') <> '' && get_option($shortname.'_integrate_body_enable') == 'on') echo(get_option($shortname.'_integration_body')); 
};
add_action('wp_footer','integration_body',12);

/*this function gets page name by its id*/
function get_pagename($page_id)
{
	global $wpdb;
	$page_name = $wpdb->get_var("SELECT post_title FROM $wpdb->posts WHERE ID = '".$page_id."' AND post_type = 'page'");
	return $page_name;
}


/*this function gets category name by its id*/
function get_categname($cat_id)
{
	global $wpdb;
	$cat_name = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id = '".$cat_id."'");
	return $cat_name;
}


/*this function gets category id by its name*/
function get_catId($cat_name)
{
	$cat_name_id = get_cat_ID($cat_name);
	return $cat_name_id;
}


/*this function gets page id by its name*/
function get_pageId($page_name)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$page_name."' AND post_type = 'page'");
	
	//fix for qtranslate plugin
	if ( $page_name_id == '' ) $page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%".trim($page_name)."%' AND post_type = 'page'");

	return $page_name_id;
}


/*this function controls the meta titles display*/
function elegant_titles() {
	global $shortname;
	
	#if the title is being displayed on the homepage
	if (is_home() || is_front_page()) {
		if (get_option($shortname.'_seo_home_title') == 'on') echo get_option($shortname.'_seo_home_titletext');  
		else { 
			if (get_option($shortname.'_seo_home_type') == 'BlogName | Blog description') echo get_bloginfo('name').get_option($shortname.'_seo_home_separate').get_bloginfo('description'); 
			if ( get_option($shortname.'_seo_home_type') == 'Blog description | BlogName') echo get_bloginfo('description').get_option($shortname.'_seo_home_separate').get_bloginfo('name');
			if ( get_option($shortname.'_seo_home_type') == 'BlogName only') echo get_bloginfo('name');
		}
	}
	#if the title is being displayed on single posts/pages
	if (is_single() || is_page()) { 
		global $wp_query; 
		$postid = $wp_query->post->ID; 
		$key = get_option($shortname.'_seo_single_field_title');
		$exists3 = get_post_meta($postid, ''.$key.'', true);
				if (get_option($shortname.'_seo_single_title') == 'on' && $exists3 !== '' ) echo $exists3; 
				else { 
					if (get_option($shortname.'_seo_single_type') == 'BlogName | Post title') echo get_bloginfo('name').get_option($shortname.'_seo_single_separate').wp_title('',false,''); 
					if ( get_option($shortname.'_seo_single_type') == 'Post title | BlogName') echo wp_title('',false,'').get_option($shortname.'_seo_single_separate').get_bloginfo('name');
					if ( get_option($shortname.'_seo_single_type') == 'Post title only') echo wp_title('',false,'');
			    }
					
	}
	#if the title is being displayed on index pages (categories/archives/search results)
	if (is_category() || is_archive() || is_search()) { 
		if (get_option($shortname.'_seo_index_type') == 'BlogName | Category name') echo get_bloginfo('name').get_option($shortname.'_seo_index_separate').wp_title('',false,''); 
		if ( get_option($shortname.'_seo_index_type') == 'Category name | BlogName') echo wp_title('',false,'').get_option($shortname.'_seo_index_separate').get_bloginfo('name');
		if ( get_option($shortname.'_seo_index_type') == 'Category name only') echo wp_title('',false,'');
		 }	  
} 

/*this function controls the meta description display*/
function elegant_description() {
	global $shortname;
	
	#homepage descriptions
	if (is_home() && get_option($shortname.'_seo_home_description') == 'on') echo '<meta name="description" content="'.get_option($shortname.'_seo_home_descriptiontext').'" />';
	
	#single page descriptions
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	$key2 = get_option($shortname.'_seo_single_field_description');
	if (isset($postid)) $exists = get_post_meta($postid, ''.$key2.'', true);
	if (get_option($shortname.'_seo_single_description') == 'on' && $exists !== '') {
		if (is_single() || is_page()) echo '<meta name="description" content="'.$exists.'" />';
	}
	
	#index descriptions
	remove_filter('term_description','wpautop');
	$cat = get_query_var('cat'); 
    $exists2 = category_description($cat);
	if ($exists2 !== '' && get_option($shortname.'_seo_index_description') == 'on') {
		if (is_category()) echo '<meta name="description" content="'. $exists2 .'" />';
	}
	if (is_archive() && get_option($shortname.'_seo_index_description') == 'on') echo '<meta name="description" content="Currently viewing archives from'. wp_title('',false,'') .'" />';
	if (is_search() && get_option($shortname.'_seo_index_description') == 'on') echo '<meta name="description" content="'. wp_title('',false,'') .'" />';
}


/*this function controls the meta keywords display*/
function elegant_keywords() {
	global $shortname;
	
	#homepage keywords
	if (is_home() && get_option($shortname.'_seo_home_keywords') == 'on') echo '<meta name="keywords" content="'.get_option($shortname.'_seo_home_keywordstext').'" />';
	
	#single page keywords
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	$key3 = get_option($shortname.'_seo_single_field_keywords');
	if (isset($postid)) $exists4 = get_post_meta($postid, ''.$key3.'', true);
	if (isset($exists4) && $exists4 !== '' && get_option($shortname.'_seo_single_keywords') == 'on') {
		if (is_single() || is_page()) echo '<meta name="keywords" content="'.$exists4.'" />';	
	}
}


/*this function controls canonical urls*/
function elegant_canonical() {
	global $shortname;
	
	#homepage urls
	if (is_home() && get_option($shortname.'_seo_home_canonical') == 'on') echo '<link rel="canonical" href="'.get_bloginfo('url').'" />';
	
	#single page urls
	global $wp_query; 
	if (isset($wp_query->post->ID)) $postid = $wp_query->post->ID; 
	if (get_option($shortname.'_seo_single_canonical') == 'on') {
		if (is_single() || is_page()) echo '<link rel="canonical" href="'.get_permalink().'" />';	
	}
	
	#index page urls
	if (get_option($shortname.'_seo_index_canonical') == 'on') {
		if (is_archive() || is_category() || is_search()) echo '<link rel="canonical" href="'.get_permalink().'" />';	
	}
}

add_action('wp_head','add_favicon');
function add_favicon(){
	global $shortname;
	
	$faviconUrl = get_option($shortname.'_favicon');
	if ($faviconUrl <> '') echo('<link rel="shortcut icon" href="'.$faviconUrl.'" />');
}
?>