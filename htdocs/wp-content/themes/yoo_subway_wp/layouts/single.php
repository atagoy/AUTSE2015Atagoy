<div id="system">

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		
		<article class="item" data-permalink="<?php the_permalink(); ?>">
		
			<header>
				
				<time datetime="<?php echo get_the_time('Y-m-d'); ?>" pubdate>
					<span class="day"><?php echo the_time('d'); ?></span>
					<span class="month"><?php echo the_time('M'); ?></span>
					<span class="year"><?php echo the_time('Y'); ?></span>
				</time>
		
				<h1 class="title"><?php the_title(); ?></h1>
	
				<p class="meta">
					<?php
						$date = '<time datetime="'.get_the_date('Y-m-d').'" pubdate>'.get_the_date().'</time>';
						printf(__('Written by %s on. Posted in %s', 'warp'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', get_the_category_list(', '));
					?>
				</p>

			</header>

			<div class="content clearfix"><?php the_content(''); ?></div>

			<?php the_tags('<p class="taxonomy">'.__('Tags: ', 'warp'), ', ', '</p>'); ?>

			<?php edit_post_link(__('Edit this post.', 'warp'), '<p class="edit">','</p>'); ?>
			
			<?php if (pings_open()) : ?>
			<p class="trackback"><?php printf(__('<a href="%s">Trackback</a> from your site.', 'warp'), get_trackback_url()); ?></p>
			<?php endif; ?>

			<?php if (get_the_author_meta('description')) : ?>
			<section class="author-box clearfix">
		
				<?php echo get_avatar(get_the_author_meta('user_email')); ?>
				
				<h3 class="name"><?php the_author(); ?></h3>
				
				<div class="description"><?php the_author_meta('description'); ?></div>

			</section>
			<?php endif; ?>
			
			<?php comments_template(); ?>

		</article>

		<?php endwhile; ?>
	<?php endif; ?>

</div>