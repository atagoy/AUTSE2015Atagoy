<?php get_header(); ?>

<!-- please do not remove this - rounded corners -->
<div id="fixcornersT"><div id="rightcornerT"></div></div>
<!-- end -->

<!-- begin main column -->
<div id="bigcolumn">

	<div id="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="postbody" id="post-<?php the_ID(); ?>">
	<div class="postbody_singlepost">

	<h1><?php the_title(); ?></h1>
	<?php the_tags('<div class="tags"><strong>Метки:</strong> ', ', ', '</div>'); ?>
	<div class="time"><?php the_time('l, j F  Y, G:i'); ?></div>
	<div class="cc">Размещено в рубрике<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj46PC9hPg=='; echo base64_decode($str);?> <?php the_category(', ') ?> и имеет <a href="<?php the_permalink() ?>#commenting" title="комментарии"><?php comments_number('0 комментариев','1 комментарий','% коммент.'); ?></a>.</div>
	<div class="the_content"><?php the_content('Читать запись полностью &raquo;'); ?></div><div class="clear"></div>


	<?php wp_link_pages(array('before' => '<p><strong>Страницы:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

	<div class="edit"><?php edit_post_link('Редактировать', '', ''); ?></div>

		<div class="track">
	<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { ?>
	Вы можете <a href="#respond">оставить комментарий</a> на заметку.
	
	<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { ?>
	Комментирование и пинг закрыты.
	<?php } ?>
	</div>

	<?php comments_template(); ?>

	<?php endwhile; ?>

	<div class="navigation">
	<div class="navleft"><?php previous_post_link('%link') ?></div>
	<div class="navright"><?php next_post_link('%link') ?></div>
	<div class="clear"></div>
	</div>

	</div>
	</div>

	<?php else : ?>

	<div class="postbody">
	<h1>Извините, не найдено.</h1>
	<h3>Попробуете поискать снова?</h3>
	<?php include(TEMPLATEPATH."/searchform.php"); ?>
	</div>

	<?php endif; ?>

	</div>

	<?php get_sidebar(); ?>

<div class="clear"></div>
</div>
<!-- end main column -->

<?php get_footer(); ?>