<?php get_header(); ?>

<!-- please do not remove this - rounded corners -->
<div id="fixcornersT"><div id="rightcornerT"></div></div>
<!-- end -->

<!-- begin main column -->
<div id="bigcolumn">

	<div id="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="postbody" id="post-<?php the_ID(); ?>">
	<h1><?php the_title(); ?></h1>
	<?php the_content('Читать полностью &raquo;'); ?>
	<?php wp_link_pages(array('before' => '<p><strong>Страницы:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
	<?php edit_post_link('Редактировать.', '<p>', '</p>'); ?>
	</div>

	<?php endwhile; endif; ?>

	</div>

	<?php get_sidebar(); ?>

<div class="clear"></div>
</div>
<!-- end main column -->

<?php get_footer(); ?>