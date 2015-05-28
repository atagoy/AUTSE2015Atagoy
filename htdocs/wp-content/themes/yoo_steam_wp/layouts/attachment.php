<div id="system">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<article class="item">
		
			<header>
			
				<time datetime="<?php echo get_the_time('Y-m-d'); ?>" pubdate>
					<span class="month"><?php echo the_time('M'); ?></span>
					<span class="day"><?php echo the_time('d'); ?></span>
				</time>
				
				<h1 class="title">
					<?php the_title(); ?>
				</h1>
	
				<p class="meta">
					<?php printf(__('Written by %s. Posted in %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', get_the_category_list(', ')); ?>

					<?php 
						if (wp_attachment_is_image()) {
							$metadata = wp_get_attachment_metadata();
							printf(__('Full size is %s pixels.', 'warp'),
								sprintf('<a href="%1$s" title="%2$s">%3$s&times;%4$s</a>',
									wp_get_attachment_url(),
									esc_attr(__('Link to full-size image', 'warp')),
									$metadata['width'],
									$metadata['height']
								)
							);
						}
					?>
				
				</p>
				
			</header>
			
			<div class="content clearfix">
				<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>"><?php echo wp_get_attachment_image($post->ID, 'full-size'); ?></a>
			</div>
			
			<?php edit_post_link(__('Edit this attachment.', 'warp'), '<p class="edit">','</p>'); ?>

		</article>
		
		<?php comments_template(); ?>

		<?php endwhile; ?>
	<?php endif; ?>

</div>