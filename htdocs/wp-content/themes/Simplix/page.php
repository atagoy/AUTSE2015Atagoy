<?php get_header();?>
<?php get_sidebar();?>
<div class="right_col">
					<div class="twitter-block">
						<div id="status">	
						<div id="status-twitter">&nbsp;</div>						
							<p><strong>Currently:</strong></p>
                			<ul id="twitter_update_list">
													<?php 
								if (function_exists('lifestream'))  {
									$events = lifestream_get_events(array('number_of_results' => 1));
									
									foreach ($events as $result) {
										echo '<li>';
										echo $result->render($_);	
										echo '</li>';
									}
					
								} else {
									echo 'Install the <a href="http://wordpress.org/extend/plugins/lifestream/">Lifestream plugin</a>'; 
								} ?>
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
					<div class="main-post"><div class="single-pad">
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>   
<div class="tit"><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1></div>
							<div class="feat-data"><?php the_date('F/j/Y'); ?></div>
							<div style="clear:both;"></div>
							<div class="content">
								<?php the_content('Read the whole story &raquo;'); ?>
													<div style="clear:both;"></div>
														<div style="text-align:center;">
																			
																	
									<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
									<?php endif; ?>
									
									</div>
							<div class="post-date">Categories: <?php the_category(', '); ?></div>
							<div class="post-date"><?php if ( function_exists('the_tags') ) : ?><?php the_tags(); ?><?php endif; ?></div>
							</div>
							 
						<?php endwhile; ?>
						<?php else: ?>	
						<div id="module-block">	
							<h2 class="title">Not Found</h2>
							<div class="content">No posts found. Try a different search? <br /><br />
							<?php include (TEMPLATEPATH . '/searchform.php'); ?><br /><br />
							</div>
						</div>
						<?php endif;?>
						<br /></div>
					</div>

					<div class="post-bottom"></div>
					<div id="featured-bot"></div>
					
					
							<?php comments_template(); ?>

		<div style="clear:both;"></div>

					</div>

<?php get_footer();?>					
				