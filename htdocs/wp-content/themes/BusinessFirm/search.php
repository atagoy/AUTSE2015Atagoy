<?php get_header(); ?>
<div class="span-24" id="contentwrap">
	<div class="span-17">
		<div id="content">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Результаты поиска</h2>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?>>
				<h2 class="title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="postdate"><img src="<?php bloginfo('template_url'); ?>/images/date.png" /> <?php the_time('F jS, Y') ?> <img src="<?php bloginfo('template_url'); ?>/images/user.png" /> <?php the_author() ?> <?php if (current_user_can('edit_post', $post->ID)) { ?> <img src="<?php bloginfo('template_url'); ?>/images/edit.png" /> <?php edit_post_link('Редактировать', '', ''); } ?></div>

				<div class="entry">
<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(200,160), array("class" => "alignleft post_thumbnail")); } ?>
					<?php the_excerpt() ?>
				</div>
        
        	</div>

		<?php endwhile; ?>

		<div class="navigation">
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
			<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
			<div class="alignright"><?php previous_posts_link('Следующие записи &raquo;') ?></div>
			<?php } ?>
		</div>

	<?php else : ?>

		<h2 class="pagetitle">Записей не найдено.</h2>

	<?php endif; ?>

		</div>
	</div>

<?php get_sidebars(); ?>
</div>
<?php get_footer(); ?>