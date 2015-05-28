<?php get_header(); ?>
<?php get_sidebar();?>
<div class="right_col">
					<div class="twitter-block">
						<div id="status">
							<div id="status-twitter">&nbsp;</div>
							<p><strong>Currently:</strong></p>
                			<ul id="twitter_update_list">
								<li>
								<div>						
				
				<?php if (is_category()) { ?>
					<h2>Browsing all posts in <?php single_cat_title(); ?>.</h2>
				<?php } elseif( is_tag() ) { ?>
					<h1>Browsing all posts in "<?php single_tag_title(); ?>".</h1>
				<?php } elseif (is_month()) { ?>	
					<h1>Browsing all posts in <?php the_time('F, Y'); ?>.</h1>
				<?php } elseif (is_author()) { ?>
					<h1>Browsing all posts in <?php echo $curauth->display_name; ?>.</h1>
				<?php } ?>
			
			</div>
								</li>
							</ul>
						</div>
					</div>
					
					<?php $t_feedburnerurl = get_settings('t_feedburnerurl'); if ($t_feedburnerurl == '') {?>
					<a href="<?php bloginfo('rss2_url'); ?>" class="rss">&nbsp;</a>
					<?php } else { ?>
					<a href="<?php echo $t_feedburnerurl ?>" class="rss">&nbsp;</a>
					<?php } ?>
						
						<div style="clear:both;"></div>
						

		<!-- content -->        

		<?php if(have_posts()) : ?>
			<?php $post = $posts[0]; ?>
			
			<?php while(have_posts()) : the_post() ?>	
			
				<?php 
		$t_thumb = get_settings("t_thumb_auto");
		if ($t_thumb == "first") {				
			$id =$post->ID;
			$the_content =$wpdb->get_var("SELECT post_content FROM $wpdb->posts WHERE ID = $id");
			$pattern = '!<img.*?src="(.*?)"!';
			preg_match_all($pattern, $the_content, $matches);
			$image_src = $matches['1'][0]; 
		} else {
			$values = get_post_custom_values("Image");
			$image_src = $values[0];
		}
	?>	
						
			<!-- post -->
		<div class="post-top"></div>
		<div class="main-post"><div class="single-pad latest-articles">	
				
					<div class="tit"><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1></div>
					<div class="feat-data"><?php the_time('F/j/Y'); ?></div>
							
				<div style="clear:both"></div>
				
				<?php if ($image_src != ''){ ?>
				<div class="image"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $image_src; ?>" alt="<?php the_title(); ?>" border="0"/></a>
				</div>
				<?php } ?>
				
				<div class="content">
				<?php the_excerpt(); ?>
				</div>
			
				<div style="clear:both"></div>
				
				<div class="post-slug"><div><a href="<?php the_permalink(); ?>">Read all story</a> — </div>  <div class="admin"><?php the_author(); ?></div>  <div class="comment"><?php comments_popup_link('0 comments', '1 comment', '% comments');?></div> </div>
				<br /><br />
			</div>	
		</div>
		<div class="post-bottom"></div>
			<!-- /post -->
			
			<?php endwhile; ?>
			
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
		<div id="bottom-arch">
			<div class="lt"><h2><?php next_posts_link('&laquo; Older Entries') ?></h2></div>
			<div class="rt"><h2><?php previous_posts_link('Newer Entries &raquo;') ?></h2></div>
		</div>
			

			<?php } ?>				
		
		<?php endif; ?>
		<!-- /content -->

</div>
<?php get_footer(); ?>