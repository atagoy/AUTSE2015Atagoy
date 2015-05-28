<?php get_header();?>
<?php get_sidebar();?>
<div class="right_col">
					<div class="twitter-block">
						<div id="status">	
						<div id="status-twitter">&nbsp;</div>						
							<p><strong>Currently:</strong></p>
                			<ul id="twitter_update_list">
								<li>
								<span>listening to the new oasis album. havent listened to them in a while... hope its good.</span> 
								<a href="http://twitter.com/adii/statuses/1016084436">2 days ago</a>						
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
							<div class="post-top"></div>	
					<div class="main-post"><div id="single-pad">
							<h1>Error 404 - Not Found</h1>						
							<div style="clear:both;"></div>
							<div class="content">
													<p> 		
		It appears you've missed you're intended destination, either through a bad or outdated link, or a typo in the page you were hoping to reach.</p>
		<p><strong>You can take a look through my recent posts.</strong></p>
		<?php query_posts('showposts=15'); ?>
		<?php while (have_posts()) : the_post(); ?>
		<ul>
			<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
		</ul>
		<?php endwhile;?>
					<div style="clear:both;"></div>	
							</div>
							 
								
						<br /></div>
					</div>

					<div class="post-bottom"></div>
					<div id="featured-bot"></div>				
					

<?php get_footer();?>					