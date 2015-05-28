<div id="system">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<article class="item">
		
			<header>
		
				<h1 class="title"><?php the_title(); ?></h1>
				
			</header>
			
			<div class="content clearfix"><?php the_content(''); ?></div>

			<?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>
	
		</article>
		
		<?php endwhile; ?>
	<?php endif; ?>
	
	<?php comments_template(); ?>

</div>