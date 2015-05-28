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
		<div class="rt-page">
			
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<!-- Begin Post -->
			
			<div class="rt-article">					
				<div class="rt-article-bg">
					<div class="module-content">
						<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
						<div class="module-l">
							<div class="module-r">
								
								<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
									
									<?php if($gantry->get('page-title')) : ?>
					
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
									
												<?php if($gantry->get('page-meta-comments') || $gantry->get('page-meta-date') || $gantry->get('page-meta-modified') || $gantry->get('page-meta-author')) : ?>
														
												<!-- Begin Meta -->
												
												<div class="rt-articleinfo">
												
													<?php if($gantry->get('page-meta-date')) : ?>
													
													<!-- Begin Date & Time -->
								
													<span class="rt-date-posted"><!--<?php _re('Posted on'); ?> --><span><?php the_time('l, d F, Y H:i'); ?></span></span>
													
													<!-- End Date & Time -->
													
													<?php endif; ?>
													
													<?php if($gantry->get('page-meta-modified')) : ?>
													
													<!-- Begin Modified Date -->
								
													<span class="rt-date-modified"><?php _re('Last Updated on'); ?> <?php the_modified_date('l, d F, Y H:i', '<span>', '</span>'); ?></span>
													
													<!-- End Modified Date -->
													
													<?php endif; ?>
														
													<?php if($gantry->get('page-meta-author')) : ?>
												
													<!-- Begin Author -->
												
													<span class="rt-author"><?php _re('Written by'); ?> <span><?php the_author(); ?></span></span>
													
													<!-- End Author -->
													
													<?php endif; ?>
													
													<?php if($gantry->get('page-meta-comments')) : ?>
														
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
																																			
												<?php edit_post_link(_r('Edit this entry.'), '<div class="edit-entry">', '</div>'); ?>
													
												<?php if(comments_open() && $gantry->get('page-comments-form')) : ?>
							
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
				<?php _re('Sorry, no pages matched your criteria.'); ?>
			</h1>
				
			<?php endif; ?>
			
			<?php wp_reset_query(); ?>

		</div>
	</div>