<div id="system">

	<h1 class="title"><?php _e('Author Archive', 'warp'); ?></h1>

	<?php the_post(); ?>

	<?php if (get_the_author_meta('description')) : ?>
	<div class="author-box">
		<div>

			<?php echo get_avatar(get_the_author_meta('user_email')); ?>
			
			<h2 class="name"><?php the_author(); ?></h2>
			
			<div class="description"><?php the_author_meta('description'); ?></div>

		</div>
	</div>
	<?php endif; ?>

	<?php rewind_posts(); ?>
	<?php if (have_posts()) : ?>
	
		<?php echo $this->render('_posts'); ?>
		
		<?php echo $this->render("_pagination", array("type"=>"posts")); ?></p>
		
	<?php endif; ?>

</div>
