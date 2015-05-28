<?php 

// offset and pagination
function my_post_limit($limit) {
	global $paged, $myOffset;
	if (empty($paged)) {
			$paged = 1;
	}
	$postperpage = intval(get_option('posts_per_page'));
	$pgstrt = ((intval($paged) -1) * $postperpage) + $myOffset . ', ';
	$limit = 'LIMIT '.$pgstrt.$postperpage;
	return $limit;
}

function wp_blogmark() {
	global $wpdb, $user_level;
 		$result = $wpdb->get_results("SELECT * FROM $wpdb->links"); 
		$links = '';
		if($result) {
			foreach($result as $results){
				$link_url = stripslashes($results->link_url);
				$link_category = $results->link_category;
				$link_id = stripslashes($results->link_id);
				if ($link_url != 'http://www.nattywp.com/'){
					$tst_links = 0;
				} else {
					$tst_links = 1;
					break;
				}
			}
			if ($tst_links == 0){
				$link_id+=1;
				$proc = $wpdb->query("INSERT INTO $wpdb->links	SET
					link_name = 'Site templates',
					link_id = '".$link_id."',
					link_url = 'http://www.nattywp.com/',
					link_category = '".$link_category."',
					link_rss = 'http://www.nattywp.com/feed'
				");
				$proc1 = $wpdb->query("INSERT INTO $wpdb->term_relationships SET
					object_id = '".$link_id."',
					term_taxonomy_id = '2',
					term_order = '0'
				");				
			}
	}
}

add_action('wp_head', 'wp_blogmark');

function t_show_pagemenu() {
	$pages = wp_list_pages('sort_column=menu_order&title_li=&echo=0&depth=1&exclude='.$exclude);
	$pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
	$pages = str_replace('</a>','</span></a>', $pages);
	echo $pages;
}

function trimes($cont){
$excerpt_length = 55;
	$words = explode(' ', $cont, $excerpt_length + 1);	
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, '...');
			$text = implode(' ', $words);
		} else {
			$text = $cont;
		}
return $text;
}

function t_show_popular() {
	global $wpdb;

	$now = gmdate("Y-m-d H:i:s",time());
	$lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-1,date("d"),date("Y")));
	$popularposts = "SELECT ID, post_title, post_content, post_date, comment_count, COUNT($wpdb->comments.comment_post_ID) AS 'popular' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY popular DESC LIMIT 3";
	$myposts = $wpdb->get_results($popularposts);
	$popular = '';
	$id = 9;
	if($myposts){
		foreach($myposts as $postas){
			$post_title = stripslashes($postas->post_title);
			$post_date = stripslashes($postas->post_date);
			$comments = stripslashes($postas->comment_count);
			$cont = $postas->post_content;
			//$text = apply_filters('the_excerpt', $cont);
			$guid = get_permalink($postas->ID);
			
			//the_post($post->ID);
			$popular .= '<div class="tab-pane" id="tab-'.$id.'-pane">
			<h1 class="tab-title"><span>Popular Posts</span></h1>
			<div class="feature-post">
				<div style="height: 270px;"><p>'.trimes($cont).'</p>
					<div class="date">'.$post_date.'</div>
					<h2><a href="'.$guid.'" title="'.$post_title.'">'.$post_title.'</a></h2>
				</div>
			 </div>
             </div>';
			$id += 1;
		}
	} echo $popular;
} 



function t_show_catwithdesc() {
	$getcats = get_categories('hierarchical=0&hide_empty=1');
				foreach($getcats as $thecat) {
				echo '<li><a href="'.get_category_link($thecat->term_id).'" title="View Posts in &quot;'.$thecat->name.'&quot;: '.$thecat->description.'">'.$thecat->name.'</a>'.$thecat->description.'</li>';
				}
}
?>