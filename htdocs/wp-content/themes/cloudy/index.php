<?php get_header(); ?>

<?php get_sidebar(); ?>
<div id="main-block">
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
                            <span class="comments"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
                        </div>
                		<div class="entry">
				<?php the_post_thumbnail() ?>
                		    <?php the_content('Continue&nbsp;reading&nbsp;&raquo;'); ?>
                		</div>
                                <div class="link-pages">
                                    <?php wp_link_pages() ?>
                                </div>
                		<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
                	</li>
            	<?php endwhile; ?>
             </ul>

        	<p><?php next_posts_link('&laquo; Previous Entries') ?>&nbsp;&nbsp;&nbsp;<?php previous_posts_link('Next Entries &raquo;') ?></p>

        	<?php else : ?>

        	<h3 class="t-center">Not Found</h3>

        	<p class="t-center">Sorry, but you are looking for something that isn't here.</p>

    	<?php endif; ?>
    </div>



    <div id="recent">
        <div class="posts">
            <h3>Recent Posts</h3>
            <?php $recent = new WP_Query(array(
                'posts_per_page'    => 5,
            ))?>
            <ul>
                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                <li>
                    <div class="date"><?php the_time(get_option('date_format')) ?></div>
                    <div class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute( array('before' => 'Permalink to: ', 'after' => '')); ?>"><?php the_title(); ?></a></div>
                </li>
            <?php endwhile;?>
            </ul>
        </div>

        <div class="comments">
            <?php if (function_exists('cloudy_recent_comments')) cloudy_recent_comments(5, 60, '<h3>Recent Comments</h3>', ''); ?>
        </div>
    </div><!--#recent-->

    <?php get_footer(); ?>
