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
		<div class="rt-blog">
	
			<?php if ($gantry->get('blog-page-title') != '') : ?>
			
			<div class="module-content-pagetitle">
				<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
				<div class="module-l">
					<div class="module-r">
						<div class="rt-headline"><div class="module-title"><div class="module-title2">
							<h1 class="title">
								<?php echo $gantry->get('blog-page-title'); ?>
							</h1>
						</div></div></div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			
			<?php endif; ?>
			
			<div class="rt-leading-articles">
			
				<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				
				if($gantry->get('blog-query') != '') : query_posts("'paged=".$paged.'&'.$gantry->get('blog-query')."'");
				
				else: query_posts('paged='.$paged.'&orderby='.$gantry->get('blog-order').'&cat='.$gantry->get('blog-cat').'&post_type='.$gantry->get('blog-type'));
				
				endif; 
				
				?>
		
				<?php while (have_posts()) : the_post(); ?>
			
				<!-- Begin Post -->
			
				<div class="rt-article">					
					<div class="rt-article-bg">
						<div class="module-content">
							<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
							<div class="module-l">
								<div class="module-r">
						
									<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
										
										<?php if($gantry->get('blog-title')) : ?>
						
										<!-- Begin Title -->
									
											<div class="rt-headline">
												<div class="module-title">
													<div class="module-title2">
												
														<?php if($gantry->get('blog-link-title')) : ?>
														
														<h1 class="title">
															<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
														</h1>
															
														<?php else : ?>
															
														<h1 class="title">
															<?php the_title(); ?>
														</h1>
															
														<?php endif; ?>
													
													</div>
												</div>
											</div>
											<div class="clear"></div>
											
										<!-- End Title -->
											
										<?php endif; ?>
										
										<div class="module-inner">
											<div class="module-inner2">
												<div class="rt-article-content">
												
													<?php if(function_exists('the_post_thumbnail') && has_post_thumbnail()) : the_post_thumbnail('gantryThumb', array('class' => 'rt-image '.$gantry->get('thumb-position'))); endif; ?>
										
													<?php if($gantry->get('blog-meta-comments') || $gantry->get('blog-meta-date') || $gantry->get('blog-meta-modified') || $gantry->get('blog-meta-author')) : ?>
														
													<!-- Begin Meta -->
													
													<div class="rt-articleinfo">
													
														<?php if($gantry->get('blog-meta-date')) : ?>
														
														<!-- Begin Date & Time -->
									
														<span class="rt-date-posted"><!--<?php _re('Posted on'); ?> --><span><?php the_time('l, d F, Y H:i'); ?></span></span>
														
														<!-- End Date & Time -->
														
														<?php endif; ?>
														
														<?php if($gantry->get('blog-meta-modified')) : ?>
														
														<!-- Begin Modified Date -->
									
														<span class="rt-date-modified"><?php _re('Last Updated on'); ?> <?php the_modified_date('l, d F, Y H:i', '<span>', '</span>'); ?></span>
														
														<!-- End Modified Date -->
														
														<?php endif; ?>
															
														<?php if($gantry->get('blog-meta-author')) : ?>
													
														<!-- Begin Author -->
													
														<span class="rt-author"><?php _re('Written by'); ?> <span><?php the_author(); ?></span></span>
														
														<!-- End Author -->
														
														<?php endif; ?>
														
														<?php if($gantry->get('blog-meta-comments')) : ?>
															
															<!-- Begin Comments -->
															
															<?php if($gantry->get('blog-meta-link-comments')) : ?>
														
															<span class="rt-comment-text">
																<a href="<?php the_permalink(); ?>#comments">
																	<?php comments_number(_r('0 Comments'), _r('1 Comment'), _r('% Comments')); ?>
																</a>
															</span>
							
															<?php else : ?>
										
															<span class="rt-comment-text"><?php comments_number(_r('0 Comments'), _r('1 Comment'), _r('% Comments')); ?></span>
															
															<?php endif; ?>
															
															<!-- End Comments -->
														
														<?php endif; ?>
														
													</div>
													
													<!-- End Meta -->
													
													<?php endif; ?>							
													
													<!-- Begin Post Content -->		
												
													<?php if($gantry->get('blog-content') == 'content') : ?>
													
														<?php the_content(false); ?>
																		
													<?php else : ?>
																		
														<?php the_excerpt(); ?>
																			
													<?php endif; ?>
													
													<?php if(preg_match('/<!--more(.*?)?-->/', $post->post_content)) : ?>
													
														<p class="rt-readon-surround">																			
															<a href="<?php the_permalink(); ?>" class="readon"><span><?php echo $gantry->get('blog-readmore'); ?></span></a>
														</p>
													
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
				
				<!-- Begin Navigation -->
											
				<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if($total_pages > 1) : ?>
						
				<div class="rt-pagination nav">
					<div class="alignleft">
						<?php next_posts_link('&laquo; '._r('Older Entries')); ?>
					</div>
					<div class="alignright">
						<?php previous_posts_link(_r('Newer Entries').' &raquo;') ?>
					</div>
					<div class="clear"></div>
				</div><br />
							
				<?php endif; ?>
		
				<!-- End Navigation -->
	
			</div>
		</div>
	</div>