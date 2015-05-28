<div id="system">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<div class="item">
		
			<h1 class="title"><?php the_title(); ?></h1>
			
			<div class="content"><?php the_content(''); ?></div>

			<?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>
	
		</div>
		
		<?php endwhile; ?>
	<?php endif; ?>
	
	<?php comments_template(); ?>

</div>