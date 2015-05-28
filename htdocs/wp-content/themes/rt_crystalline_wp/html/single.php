<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

// no direct access
?>

<?php global $post, $posts, $query_string; ?>

	<div class="rt-wordpress">
		<div class="rt-post">
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<!-- Begin Post -->
			
			<div class="rt-article">					
				<div class="rt-article-bg">
					<div class="module-content">
						<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
						<div class="module-l">
							<div class="module-r">
			
								<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
									
									<?php if($gantry->get('post-title')) : ?>
					
									<!-- Begin Title -->
								
									<div class="rt-headline">
										<div class="module-title">
											<div class="module-title2">										
												<h1 class="title">
													<?php the_title(); ?>
												</h1>						
											</div>
										</div>
									</div>
									<div class="clear"></div>
										
									<!-- End Title -->
										
									<?php endif; ?>
									
									<div class="module-inner">
										<div class="module-inner2">
											<div class="rt-article-content">
									
												<?php if($gantry->get('post-meta-comments') || $gantry->get('post-meta-date') || $gantry->get('post-meta-modified') || $gantry->get('post-meta-author')) : ?>
														
												<!-- Begin Meta -->
												
												<div class="rt-articleinfo">
												
													<?php if($gantry->get('post-meta-date')) : ?>
													
													<!-- Begin Date & Time -->
								
													<span class="rt-date-posted"><!--<?php _re('Posted on'); ?> --><span><?php the_time('l, d F, Y H:i'); ?></span></span>
													
													<!-- End Date & Time -->
													
													<?php endif; ?>
													
													<?php if($gantry->get('post-meta-modified')) : ?>
													
													<!-- Begin Modified Date -->
								
													<span class="rt-date-modified"><?php _re('Last Updated on'); ?> <?php the_modified_date('l, d F, Y H:i', '<span>', '</span>'); ?></span>
													
													<!-- End Modified Date -->
													
													<?php endif; ?>
														
													<?php if($gantry->get('post-meta-author')) : ?>
												
													<!-- Begin Author -->
												
													<span class="rt-author"><?php _re('Written by'); ?> <span><?php the_author(); ?></span></span>
													
													<!-- End Author -->
													
													<?php endif; ?>
													
													<?php if($gantry->get('post-meta-comments')) : ?>
														
													<!-- Begin Comments -->
								
													<span class="rt-comment-text"><?php comments_number(_r('0 Comments'), _r('1 Comment'), _r('% Comments')); ?></span>
													
													<!-- End Comments -->
													
													<?php endif; ?>
													
												</div>
												
												<!-- End Meta -->
												
												<?php endif; ?>
												
												<!-- Begin Post Content -->		
											
												<?php the_content(); ?>
						
												<div class="clear"></div>
												
												<?php wp_link_pages('before=<div class="rt-pagination">'._r('Pages:').'&after=</div><br />'); ?>
												
												<?php if (has_tag() && $gantry->get('post-tags')) : ?>
																																								
												<div class="rt-tags">
																	
													<?php the_tags('<span>'._r('Tags:').' &nbsp;</span>', ', ', ''); ?>
																				
												</div>
						
												<?php endif; ?>
												
												<?php edit_post_link(_r('Edit this entry.'), '<div class="edit-entry">', '</div>'); ?>
												
												<?php if($gantry->get('post-footer')) : ?>
									
												<div class="rt-post-footer">
													<small>
													
														<?php _re('This entry was posted'); ?>
														<?php /* This is commented, because it requires a little adjusting sometimes.
														You'll need to download this plugin, and follow the instructions:
														http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
														/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
														<?php _re('on'); ?> <?php the_time('l, F jS, Y') ?> <?php _re('at'); ?> <?php the_time() ?>
														<?php _re('and is filed under'); ?> <?php the_category(', ') ?>.
														<?php _re('You can follow any responses to this entry through the'); ?> <?php post_comments_feed_link('RSS 2.0'); ?> <?php _re('feed'); ?>.
						
														<?php if (('open' == $post->comment_status) && ('open' == $post->ping_status)) {
														// Both Comments and Pings are open ?>
														<?php _re('You can'); ?> <a href="#respond"><?php _re('leave a response'); ?></a>, <?php _re('or'); ?> <a href="<?php trackback_url(); ?>" rel="trackback"><?php _re('trackback'); ?></a> <?php _re('from your own site.'); ?>
						
														<?php } elseif (!('open' == $post->comment_status) && ('open' == $post->ping_status)) {
														// Only Pings are Open ?>
														<?php _re('Responses are currently closed, but you can'); ?> <a href="<?php trackback_url(); ?> " rel="trackback"><?php _re('trackback'); ?></a> <?php _re('from your own site.'); ?>
						
														<?php } elseif (('open' == $post->comment_status) && !('open' == $post->ping_status)) {
														// Comments are open, Pings are not ?>
														<?php _re('You can skip to the end and leave a response. Pinging is currently not allowed.'); ?>
						
														<?php } elseif (!('open' == $post->comment_status) && !('open' == $post->ping_status)) {
														// Neither Comments, nor Pings are open ?>
														<?php _re('Both comments and pings are currently closed.'); ?>
						
														<?php } edit_post_link(_r('Edit this entry'),'','.'); ?>
						
													</small>
												</div>
																					
												<?php endif; ?>
													
												<?php if(comments_open() && $gantry->get('post-comments-form')) : ?>
							
													<a name="comments"></a>
																							
													<?php echo $gantry->displayComments(true,'basic','basic'); ?>
												
												<?php endif; ?>
												
												<div class="clear"></div>
												
												<!-- End Post Content -->
						
											</div>
										</div>
									</div>
									
								</div>
								<div class="clear"></div>

							</div>
						</div>
						<div class="module-bm"><div class="module-bl"><div class="module-br"></div></div></div>
					</div>
				</div>				
			</div>
			
			<!-- End Post -->
			
			<?php endwhile;?>
			
			<?php else : ?>
																		
			<h1 class="rt-pagetitle">
				<?php _re('Sorry, no posts matched your criteria.'); ?>
			</h1>
				
			<?php endif; ?>
			
			<?php wp_reset_query(); ?>

		</div>
	</div>