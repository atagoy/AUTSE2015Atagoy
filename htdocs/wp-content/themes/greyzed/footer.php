<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */
?>

<hr />
<div id="footer" role="contentinfo">
	<div class="socialfooter"><H4 class="footerwidget">Social Connections</H4>
		<ul>
<?php
// Build Organic Social Sites
$count = 0;
if (get_option('greyzed_facebook') != ""){ $count++; ?><li class="facebook-link"><a href="<?php echo get_option('greyzed_facebook'); ?>" target='_blank'>Facebook</a></li><?php }
if (get_option('greyzed_twitter') != ""){ $count++; ?><li class="twitter-link"><a href="<?php echo get_option('greyzed_twitter'); ?>" target='_blank'>Twitter</a></li><?php }
if (get_option('greyzed_friendfeed') != ""){ $count++; ?><li class="friend-link"><a href="<?php echo get_option('greyzed_friendfeed'); ?>" target='_blank'>FriendFeed</a></li><?php }
if (get_option('greyzed_flickr') != ""){ $count++; ?><li class="flickr-link"><a href="<?php echo get_option('greyzed_flickr'); ?>" target='_blank'>Flickr</a></li><?php }
if (get_option('greyzed_linkedin') != ""){ $count++; ?><li class="linkedin-link"><a href="<?php echo get_option('greyzed_linkedin'); ?>" target='_blank'>Linked In</a></li><?php }
if ((get_option('greyzed_lastfm') != "") AND ($count != 5)){ $count++; ?><li class="lastfm-link"><a href="<?php echo get_option('greyzed_lastfm'); ?>" target='_blank'>Last.fm</a></li><?php }
if ((get_option('greyzed_youtube') != "") AND ($count != 5)){ ?><li class="youtube-link"><a href="<?php echo get_option('greyzed_youtube'); ?>" target='_blank'>Youtube</a></li><?php }
?>
		</ul>
	</div>
	
	<div class="blogroll-foot">
		
		
		<!-- begin widgetized footer -->	
		
		<ul>
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Middle') ) : ?>		
		
		<H4 class="footerwidget">Blog Roll</H4>
		<?php		
		$links = wp_list_bookmarks('title_li=&categorize=0&sort_column=menu_order&echo=0');
		$bookmarks = explode('</li>', $links); 
		$links = $bookmarks;			
		$link_n = count($links) - 1;
		for ($i=0;$i<$link_n;$i++):
			if ($i<$link_n/2):
				$left = $left.''.$links[$i].'</li>';
			elseif ($i>=$link_n/2):
				$right = $right.''.$links[$i].'</li>';
			endif;
		endfor;
		?>
		<ul class="left_blogroll">
			<?php echo $left;?>
		</ul>
		<ul class="right_blogroll">
			<?php echo $right;?>
		</ul>		
	
		
		
			<?php endif; ?>
		</ul>
		
		<!-- end widgetized footer -->	
		
		</div>
		
		<!-- begin widgetized footer right -->
		
	<div class="recent-foot">
	<div class="getintouch">
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Right') ) : ?>		
	<H4 class="footerwidget">Recent Comments</H4>
		<?php
		  global $wpdb;
		  $sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 10";
		
		  $comments = $wpdb->get_results($sql);
		  $output = $pre_HTML;
		  $output .= "\n<ul>";
		  foreach ($comments as $comment) {
			$output .= "\n<li>".strip_tags($comment->comment_author) .": " . "<a href=\"" . get_permalink($comment->ID)."#comment-" . $comment->comment_ID . "\" title=\"on ".$comment->post_title . "\">" . strip_tags(substr($comment->com_excerpt,0,20))."</a></li>";
		  }
		  $output .= "\n</ul>";
		  $output .= $post_HTML;
		  echo $output;
		?>
	
	<!-- end widgetized footer right -->
	<?php endif; ?>
	</div></div>

</div>
</div>
	
	<div class="footerlinks">
	
   <a href="<?php echo get_option('home'); ?>/">Home</a> &nbsp;&nbsp;|			
		<?php
			$links = get_pages('number=6&sort_column=post_date&depth=1&title_li=');
			foreach($links as $i => $page)
			$links[$i] = '&nbsp;<a href="' . get_page_link($page->ID) . '" title="' . attribute_escape(apply_filters('the_title', $page->post_title)) . '">' . apply_filters('the_title', $page->post_title) . '</a>';
			echo implode('&nbsp; | &nbsp;', $links);
		?>	
		&nbsp;|&nbsp; <a href="<?php if (get_option('greyzed_feedburner') == "#") { bloginfo('rss_url'); } else echo get_option('greyzed_feedburner');?>" title="RSS">Posts RSS</a>
		&nbsp;|&nbsp; <a href="<?php bloginfo('comments_rss2_url'); ?>" title="Comments RSS">Comments RSS</a>

	</div>
</div>

<div id="footer-bott">&copy; 2010 <?php bloginfo('name'); ?>. All Rights Reserved. Greyzed Theme created by <a href="http://www.theforge.co.za/" title="The Forge Web Creations">The Forge Web Creations</a>. Powered by <a href="http://wordpress.org/">WordPress</a>.</div>

<?php echo get_option("greyzed_analytics"); ?>
		<?php wp_footer(); ?>
</body>
</html>
