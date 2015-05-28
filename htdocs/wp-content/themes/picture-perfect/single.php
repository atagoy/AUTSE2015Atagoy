<?php get_header(); ?>

<?php get_sidebar(); ?>


<div id="content">
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			
			<p><?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?></p>
				
				<p><?php the_tags();Ê?></p>

	<div class="post-details"> <h3>Posted in <?php the_category(' and ','');Ê?>  by <?php the_author() ?> on <?php the_time('F jS, Y') ?>  at <?php the_time() ?>. </h3><h3><a href="<?php the_permalink() ?>#comments"><?php comments_number('Add a comment','1 comment','% comments'); ?></a></h3></div>
			<h3><?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?></h3>
			<div class="prevnext">
<h3><?php previous_post_link('Previous Post: %link','%title',TRUE);Ê?> &nbsp <?php next_post_link('Next Post: %link','%title',TRUE);Ê?></p></h3></div>

			<?php edit_post_link('Edit Post', '<p>', '</p>'); ?></p>
		</div>
	<?php endwhile; ?>
	<?php else : ?>
	<div class="post" id="post-<?php the_ID(); ?>">
		<h2>Nothing to see here</h2>
		<p>You seemed to have found a mislinked file, page, or search query with zero results. If you feel that you've reached this page in error, double check the URL and or search string and try again.</p>
		<p>Alternatively, a more personalized method of tracking down and searching for content can be found <a href="#bottom_box">below</a>.</p>
	</div>
	<?php endif; ?>


	
	
	<?php comments_template(); ?>
</div>


<?php get_footer(); ?>