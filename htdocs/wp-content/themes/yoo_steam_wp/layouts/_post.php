<article id="item-<?php the_ID(); ?>" class="item" data-permalink="<?php the_permalink(); ?>">

	<header>
		
		<time datetime="<?php echo get_the_time('Y-m-d'); ?>" pubdate>
			<span class="month"><?php echo the_time('M'); ?></span>
			<span class="day"><?php echo the_time('d'); ?></span>
		</time>
		
		<h1 class="title">
			<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		</h1>
		
		<p class="meta">
			<?php 
				printf(__('Written by %s. Posted in %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', get_the_category_list(', '));
			?>
		</p>
		
	</header>
		
	<div class="content clearfix"><?php the_content(''); ?></div>

	<p class="links">
		<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php _e('Continue Reading', 'warp'); ?></a>
		<?php comments_popup_link(__('No Comments', 'warp'), __('1 Comment', 'warp'), __('% Comments', 'warp'), "", ""); ?>
	</p>

	<?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>

</article>