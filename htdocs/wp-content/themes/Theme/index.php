<?php get_header(); ?>


		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

					                 
                    
                    <div class="indent">
                            <div class="container">
                            	<img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-1.gif" class="img-left" />
                                <div class="indent1">
                                	<div class="title"><div><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></div></div>
                                    <div class="date"><?php the_date(''); ?> @ <span><?php the_time(''); ?> </span></div>
                                    <div class="author">posted by <?php the_author_link() ?></div>
                                </div>
                                <br class="clear" />
                            </div>
                            <div class="text-box">
								<?php the_content('Read more'); ?>
                            </div>
                            <div class="comments"><?php comments_popup_link ( '0 comments', '1 comment', '% comments', '' , 'off'); ?></div>                        
                        </div>    
                    
                    
					
				</div>
			<?php endwhile; ?>
		
			<div class="navigation nav-top">
				<div class="alignleft"><?php next_posts_link('&lt;&lt; Older entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer entries &gt;&gt;') ?></div>
			</div>
		
		<?php else : ?>
			<h2 class="pagetitle">Not Found</h2>
			<p class="center">Sorry, but you are looking for something that isn't here.</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
		
<?php get_footer(); ?>