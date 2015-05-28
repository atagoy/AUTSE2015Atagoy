<?php get_header(); ?>

<!-- featured section html -->
<?php include (TEMPLATEPATH . "/featured.php"); ?>
<!-- end -->

<!-- begin main column -->
<div id="bigcolumn">

	<div id="top_posts">
	
	<h2>Реклама</h2>
	<div class="postbox">
	<a href="http://www.wpbot.ru/"><img src="<?php bloginfo('template_directory'); ?>/images/ads.gif" alt="поставьте здесь рекламу или фото" /></a>
	</div>

	<?php query_posts('showposts='.$leftcolumn_category_1_postsnum.'&cat='.$leftcolumn_category_1); ?>
	<h2><?php single_cat_title(); ?></h2>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="postbox">
	<div class="top_thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="top_entry">
		<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class="time"><?php the_time('l, j F  Y G:i'); ?></div>
		<div class="excerpt"><p><?php the_excerpt_reloaded($postexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>
		<ul class="postbit">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li><a href="<?php the_permalink() ?>#commenting" title="прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
	</div><div class="clear"></div>
	</div>
	<?php endwhile; ?>
	<?php else : ?>
	<div class="postbox">
	<p>В этой рубрике пока нет записей.</p>
	</div>
	<?php endif; ?>

	<?php query_posts('showposts='.$leftcolumn_category_2_postsnum.'&cat='.$leftcolumn_category_2); ?>
	<h2><?php single_cat_title(); ?></h2>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="postbox">
	<div class="top_thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>

	<div class="top_entry">
		<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class="time"><?php the_time('l, j F  Y G:i'); ?></div>
		<div class="excerpt"><p><?php the_excerpt_reloaded($postexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>
		<ul class="postbit">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li><a href="<?php the_permalink() ?>#commenting" title="прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
	</div><div class="clear"></div>
	</div>
	<?php endwhile; ?>
	<?php else : ?>
	<div class="postbox">
	<p>В этой рубрике пока нет записей.</p>
	</div>
	<?php endif; ?>

	</div>

	<div id="recent_posts">

	<?php query_posts('showposts='.$rightcolumn_category_1_postsnum.'&cat='.$rightcolumn_category_1); ?>
	<h2><?php single_cat_title(); ?></h2>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="postbox">
	<div class="recent_thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="recent_entry">
		<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class="time"><?php the_time('l, j  F Y G:i'); ?></div>
		<div class="excerpt"><p><?php the_excerpt_reloaded($postexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>
		<ul class="postbit">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать запись полностью</a></li>
		<li><a href="<?php the_permalink() ?>#commenting" title="прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
	</div><div class="clear"></div>
	</div>
	<?php endwhile; ?>
	<?php else : ?>
	<div class="postbox">
	<p>В этой рубрике пока нет записей.</p>
	</div>
	<?php endif; ?>

	<?php query_posts('showposts='.$rightcolumn_category_2_postsnum.'&cat='.$rightcolumn_category_2); ?>
	<h2><?php single_cat_title(); ?></h2>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="postbox">
	<div class="recent_thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="recent_entry">
		<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
		<div class="time"><?php the_time('l, j F  Y G:i'); ?></div>
		<div class="excerpt"><p><?php the_excerpt_reloaded($postexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>
		<ul class="postbit">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li><a href="<?php the_permalink() ?>#commenting" title="прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
	</div><div class="clear"></div>
	</div>
	<?php endwhile; ?>
	<?php else : ?>
	<div class="postbox">
	<p>В этой рубрике пока нет записей.</p>
	</div>
	<?php endif; ?>

	</div>

	<?php get_sidebar(); ?>

<div class="clear"></div>
</div>
<!-- end main column -->

<?php get_footer(); ?>