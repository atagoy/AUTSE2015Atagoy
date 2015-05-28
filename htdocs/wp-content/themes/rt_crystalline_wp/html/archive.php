<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
?>

<?php global $post, $posts, $query_string, $wp_query; ?>

	<div class="rt-wordpress">
		<div class="rt-archive">
			
			<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			if (is_category()) {
				$post_count = $gantry->get('category-count');
				$page_title = $gantry->get('category-page-title');
				$title = $gantry->get('category-title');
				$title_link = $gantry->get('category-link-title');
				$author = $gantry->get('category-meta-author');
				$date = $gantry->get('category-meta-date');
				$comments = $gantry->get('category-meta-comments');
				$comments_link = $gantry->get('category-meta-link-comments');
				$modified = $gantry->get('category-meta-modified');
				$content = $gantry->get('category-content');
				$readmore = $gantry->get('category-readmore');
			}
			
			if (is_day() || is_month() || is_year() || is_author()) {
				$post_count = $gantry->get('archive-count');
				$page_title = $gantry->get('archive-page-title');
				$title = $gantry->get('archive-title');
				$title_link = $gantry->get('archive-link-title');
				$author = $gantry->get('archive-meta-author');
				$date = $gantry->get('archive-meta-date');
				$comments = $gantry->get('archive-meta-comments');
				$comments_link = $gantry->get('archive-meta-link-comments');
				$modified = $gantry->get('archive-meta-modified');
				$content = $gantry->get('archive-content');
				$readmore = $gantry->get('archive-readmore');
			}
			
			if (is_tag()) {
				$post_count = $gantry->get('tags-count');
				$page_title = $gantry->get('tags-page-title');
				$title = $gantry->get('tags-title');
				$title_link = $gantry->get('tags-link-title');
				$author = $gantry->get('tags-meta-author');
				$date = $gantry->get('tags-meta-date');
				$comments = $gantry->get('tags-meta-comments');
				$comments_link = $gantry->get('tags-meta-link-comments');
				$modified = $gantry->get('tags-meta-modified');
				$content = $gantry->get('tags-content');
				$readmore = $gantry->get('tags-readmore');
			}
			
            $query = $wp_query->query;
            if (!is_array($query)) parse_str($query, $query); 
			query_posts(array_merge($query, array('posts_per_page' => $post_count, 'paged' => $paged))); ?>
	
			<?php if(have_posts()) : ?>
			
			<?php if($page_title) : ?>
			
			<div class="module-content-pagetitle">
				<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
				<div class="module-l">
					<div class="module-r">
						<div class="rt-headline"><div class="module-title"><div class="module-title2">
		    																										
							<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
							<?php /* If this is a category archive */ if (is_category()) { ?>
								<h1 class="title"><?php _re('Category:'); ?> <?php single_cat_title(); ?></h1>
							<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
								<h1 class="title"><?php _re('Posts Tagged'); ?> &#8216;<?php single_tag_title(); ?>&#8217;</h1>
							<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
								<h1 class="title"><?php _re('Archive for'); ?> <?php the_time('F jS, Y'); ?></h1>
									<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
								<h1 class="title"><?php _re('Archive for'); ?> <?php the_time('F, Y'); ?></h1>
							<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
								<h1 class="title"><?php _re('Archive for'); ?> <?php the_time('Y'); ?></h1>
							<?php /* If this is an author archive */ } elseif (is_author()) { ?>
								<h1 class="title"><?php _re('Author Archive'); ?></h1>
							<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
								<h1 class="title"><?php _re('Blog Archives'); ?></h1>
							<?php } ?>
			
						</div></div></div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			
			<?php endif; ?>
															
			<?php while (have_posts()) : the_post(); ?>
		
			<!-- Begin Post -->
			
			<div class="rt-article">
				<div class="rt-article-bg">
					<div class="module-content">
						<div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div>
						<div class="module-l">
							<div class="module-r">
								
								<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
						
									<?php if($title) : ?>
					
									<!-- Begin Title -->
								
									<div class="rt-headline">
										<div class="module-title">
											<div class="module-title2">
										
												<?php if($title_link) : ?>
												
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
									
												<?php if($comments || $date || $modified || $author) : ?>
														
												<!-- Begin Meta -->
												
												<div class="rt-articleinfo">
												
													<?php if($date) : ?>
													
													<!-- Begin Date & Time -->
								
													<span class="rt-date-posted"><!--<?php _re('Posted on'); ?> --><span><?php the_time('l, d F, Y H:i'); ?></span></span>
													
													<!-- End Date & Time -->
													
													<?php endif; ?>
													
													<?php if($modified) : ?>
													
													<!-- Begin Modified Date -->
								
													<span class="rt-date-modified"><?php _re('Last Updated on'); ?> <?php the_modified_date('l, d F, Y H:i', '<span>', '</span>'); ?></span>
													
													<!-- End Modified Date -->
													
													<?php endif; ?>
														
													<?php if($author) : ?>
												
													<!-- Begin Author -->
												
													<span class="rt-author"><?php _re('Written by'); ?> <span><?php the_author(); ?></span></span>
													
													<!-- End Author -->
													
													<?php endif; ?>
													
													<?php if($comments) : ?>
														
														<!-- Begin Comments -->
														
														<?php if($comments_link) : ?>
														
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
											
												<?php if($content == 'content') : ?>
												
													<?php the_content(false); ?>
																	
												<?php else : ?>
																	
													<?php the_excerpt(); ?>
																		
												<?php endif; ?>
												
												<?php if(preg_match('/<!--more(.*?)?-->/', $post->post_content)) : ?>
												
												<p class="rt-readon-surround">
													<a href="<?php the_permalink(); ?>" class="readon"><span><?php echo $readmore; ?></span></a>
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
			
			<?php else : ?>
																																	
			<h1 class="title">
				<?php _re("Sorry, but there aren't any posts matching your query."); ?>
			</h1>
															
			<?php endif; ?>
															
			<?php wp_reset_query(); ?>
	
		</div>
	</div>