<div id="system">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<div class="item">
		
			<h1 class="title"><?php the_title(); ?></h1>

			<p class="meta"><?php printf(__('Written by %s on %s. Posted in %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', get_the_date(), get_the_category_list(', ')); ?></p>

			<div class="content"><?php the_content(''); ?></div>

			<?php the_tags('<p class="taxonomy">'.__('Tags: ', 'warp'), ', ', '</p>'); ?>

			<?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>
			
			<?php if (pings_open()) : ?>
			<p class="trackback"><?php printf(__('<a href="%s">Trackback</a> from your site.', 'warp'), get_trackback_url()); ?></p>
			<?php endif; ?>

		</div>
		
		<?php if (get_the_author_meta('description')) : ?>
		<div class="author-box">
			<div>
	
				<?php echo get_avatar(get_the_author_meta('user_email')); ?>
				
				<h2 class="name"><?php the_author(); ?></h2>
				
				<div class="description"><?php the_author_meta('description'); ?></div>
	
			</div>
		</div>
		<?php endif; ?>
		
		<?php comments_template(); ?>

		<?php endwhile; ?>
	<?php endif; ?>

</div>