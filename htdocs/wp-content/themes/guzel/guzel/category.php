<?php get_header(); ?>

<!-- please do not remove this - rounded corners -->
<div id="fixcornersT"><div id="rightcornerT"></div></div>
<!-- end -->

<!-- begin main column -->
<div id="bigcolumn">

	<div id="widecolumn">

	<div class="postbody">

	<?php if (have_posts()) : ?>
	<h2>&#8216;<?php single_cat_title(); ?>&#8217; Новости</h2>

	<?php while (have_posts()) : the_post(); ?>
	<div class="p">
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="summary">
	<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
	<div class="excerpt"><p><?php the_excerpt_reloaded(50, '<a>', FALSE, FALSE, 2); ?></p></div>
	<div class="info"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></div>
	<?php the_tags('<div class="tags">Метки: ', ', ', '</div>'); ?>
	</div>
	</div>
	<?php endwhile; ?>

	<div class="navigation">
	<div class="navleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
	<div class="navright"><?php previous_posts_link('Новые записи &raquo;') ?></div>
	<div class="clear"></div>
	</div>

	<?php else : ?>

	<h1>Не найдено.</h1>
	<h3>Попробуете поискать снова?</h3>
	<?php include(TEMPLATEPATH."/searchform.php"); ?>

	<?php endif; ?>

	</div>

	</div>

	<?php get_sidebar(); ?>

<div class="clear"></div>
</div>
<!-- end main column -->

<?php get_footer(); ?>