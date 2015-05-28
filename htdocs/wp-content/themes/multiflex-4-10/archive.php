<?php get_header(); ?>

<?php include (TEMPLATEPATH . "/l_sidebar.php"); ?>

      <!-- B.1 MAIN CONTENT -->
      <div class="main-content">
      
      	<div class="pagination">
			<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
			<div class="alignright"><?php previous_posts_link('Следующие записи &raquo;') ?></div>
		</div>

      
      <?php is_tag(); ?>
		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="pagetitle">Archive for the<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> &#8216;<?php single_cat_title(); ?>&#8217; Category</h1>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="pagetitle">Posts Tagged<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> &#8216;<?php single_tag_title(); ?>&#8217;</h1>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="pagetitle">Archive for<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_time('F jS, Y'); ?></h1>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="pagetitle">Archive for<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_time('F, Y'); ?></h1>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="pagetitle">Archive for<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_time('Y'); ?></h1>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="pagetitle">Author Archive</h1>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="pagetitle">Blog Archives</h1>
 	  <?php } ?>

		<?php while (have_posts()) : the_post(); ?>
                <!-- Content unit - One column -->
        <!-- <h1 class="block"></h1> -->
            <div class="column1-unit">
                <h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                <h3><?php the_time('l, F jS, Y') ?></h3>
					<?php the_content() ?>
                <p class="details"><?php the_tags('Метки: ', ', ', '<br />'); ?> Категория<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_category(', ') ?> | <?php edit_post_link('Изменить', '', ' | '); ?>  <?php comments_popup_link('Нет комментариев &#187;', '1 Комментарий &#187;', '% Комментариев &#187;'); ?></p>
            </div>
        <hr class="clear-contentunit" />

		<?php endwhile; ?>

		<div class="pagination">
			<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
			<div class="alignright"><?php previous_posts_link('Следующие записи &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h1 class="pagetitle">Не найдено</h3>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
        
        </div> <!-- <div class="main-content"> -->

<?php include (TEMPLATEPATH . "/r_sidebar.php"); ?>

<?php get_footer(); ?>
