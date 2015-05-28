<?php get_header(); ?>

<!-- не удалять - закругленные углы -->
<div id="fixcornersT"><div id="rightcornerT"></div></div>
<!-- end -->

<!-- основная часть -->
<div id="bigcolumn">

	<div id="widecolumn">

	<div class="postbody">
	<h1>404 - не найдено.</h1>
	<h3>Попробуете поискать тут?</h3>
	<?php include(TEMPLATEPATH."/searchform.php"); ?>
	</div>

	<?php query_posts('showposts=10'); ?>
	<?php if (have_posts()) : ?>
	<div class="postbody">
	<h2>Попробуйте поискать в публикациях</h2>
	<?php while (have_posts()) : the_post(); ?>
	<div class="p">
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="summary">
	<h3><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<div class="time"><?php the_time('l,  j F Y G:i'); ?></div>
	<div class="excerpt"><p><?php the_excerpt_reloaded(50, '<a>', FALSE, FALSE, 2); ?></p></div>
	<div class="info"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php the_permalink() ?>#commenting" title="прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></div>
	</div>
	</div>
	<?php endwhile; ?>
	</div>
	<?php endif; ?>

	</div>

	<?php get_sidebar(); ?>

<div class="clear"></div>
</div>
<!-- конец основной части -->

<?php get_footer(); ?>