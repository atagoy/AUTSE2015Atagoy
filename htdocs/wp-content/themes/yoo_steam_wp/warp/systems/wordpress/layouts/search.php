<div id="system">

	<?php if (have_posts()) : ?>

		<h1 class="page-title"><?php _e('Search Results for', 'warp'); ?> &#8216;<?php echo stripslashes(strip_tags(get_search_query()));?>&#8217;</h1>
		
		<?php echo $this->render('_posts'); ?>

		<?php echo $this->render("_pagination", array("type"=>"posts")); ?></p>

	<?php else : ?>

		<h1 class="page-title"><?php _e('No posts found. Try a different search?', 'warp'); ?></h1>
		<?php get_search_form(); ?>

	<?php endif; ?>

</div>