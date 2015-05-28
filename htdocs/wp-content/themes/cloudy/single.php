<?php get_header(); ?>
<?php get_sidebar(); ?>
    <div id="main-block">
        <div class="left-line">
            <div id="content">
            	<?php if (have_posts()) : ?>
                    <ul>
                    	<?php while (have_posts()) : the_post(); ?>

                        	<li <?php post_class() ?> id="post-<?php the_ID(); ?>">
                                <div class="date"><a href="<?php the_permalink() ?>" rel="nofollow"><?php the_time('d') ?><span><?php the_time('M') ?></span></a></div>
                                <div class="title">
                                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(array('before' => 'Permalink to: ', 'after' => '')); ?>"><?php the_title(); ?></a></h2>
                                </div>
                                <div class="postdata">
                                    <span class="category"><?php the_category(', ') ?></span>
                                </div>
                        		<div class="entry">
                                            <?php the_post_thumbnail() ?>
                        		    <?php the_content('Continue reading &raquo;'); ?>
                        		</div>
                                        <div class="link-pages">
                                            <?php wp_link_pages() ?>
                                        </div>
                        		<p>Posted by <?php the_author() ?> @ <?php the_time() ?> <?php edit_post_link('Edit'); ?></p>
                        		<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
                        	</li>
                    	<?php endwhile; ?>
                     </ul>
                    <?php comments_template(); ?>
                <?php else : ?>
                	<h3 class="t-center">Not Found</h2>
                	<p class="t-center">Sorry, but you are looking for something that isn't here.</p>
            	<?php endif; ?>
            </div><!--#content-->
        </div><!--.left-line-->
<?php get_footer(); ?>
