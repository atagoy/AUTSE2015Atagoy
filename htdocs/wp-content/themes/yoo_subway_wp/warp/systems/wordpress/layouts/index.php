<div id="system">
<?php if (is_home()) { query_posts($query_string.'&cat=24&showposts=5'); } ?>
	<?php if (have_posts()) : ?>

		<?php echo $this->render('_posts'); ?>
		
		<?php echo $this->render("_pagination", array("type"=>"posts")); ?>

	<?php else : ?>

		<h1 class="page-title"><?php _e('Not Found', 'warp'); ?></h1>
		<p><?php _e("Sorry, but you are looking for something that isn't here.", "warp"); ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>

</div>