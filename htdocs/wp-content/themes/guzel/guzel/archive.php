<?php get_header(); ?>

<!-- please do not remove this - rounded corners -->
<div id="fixcornersT"><div id="rightcornerT"></div></div>
<!-- end -->

<!-- begin main column -->
<div id="bigcolumn">

	<div id="widecolumn">

	<div class="postbody">

	<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>
	<h2>Архив рубрики <?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj46PC9hPg=='; echo base64_decode($str);?><?php single_cat_title(); ?>&#8217; </h2>
	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
	<h2>Записи с меткой &#8216;<?php single_tag_title(); ?>&#8217;</h2>
	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	<h2>Архив <?php the_time(' jS F Y'); ?></h2>
	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	<h2>Архив <?php the_time('F, Y'); ?></h2>
	<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	<h2>Архив <?php the_time('Y'); ?></h2>
	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	<h2>Архив автора</h2>
	<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<h2>Архив блога</h2>
	<?php } ?>

	<?php while (have_posts()) : the_post(); ?>
	<div class="p">
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="summary">
	<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
	<div class="excerpt"><p><?php the_excerpt_reloaded(50, '<a>', FALSE, FALSE, 2); ?></p></div>
	<div class="info">Размещено в рубрике<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj46PC9hPg=='; echo base64_decode($str);?> <?php the_category(', ') ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></div>
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