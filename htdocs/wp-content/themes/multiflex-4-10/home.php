<?php get_header(); ?>

<?php include (TEMPLATEPATH . "/l_sidebar.php"); ?>

      <!-- B.1 MAIN CONTENT -->
      <div class="main-content">
      
      	<?php if (have_posts()) : ?>
  		<?php while (have_posts()) : the_post(); ?>

        
        <!-- Pagetitle -->
        <h1 class="pagetitle" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
	    <?php the_title(); ?></a>
		</h1>

        <!-- Content unit - One column -->
        <!-- <h1 class="block"></h1> -->
            <div class="column1-unit">
                <h1></h1>
                <h3>
					Дата<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?> <?php the_time('F jS, Y') ?>
                    Автор<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj46PC9hPg=='; echo base64_decode($str);?> <?php the_author() ?>
                </h3>
					<?php the_content('Читать далее &raquo;'); ?>
                <p class="details">
					<?php the_tags('Tags: ', ', ', '<br />'); ?>
                    Категория<?php $str = 'PGEgaHJlZj0iaHR0cDovL3d3dy53cHRoZW1lLnVzIiB0aXRsZT0i0KLQtdC80Ysg0LTQu9GPIFdvcmRwcmVzcyI+OjwvYT4='; echo base64_decode($str);?>
                    <?php the_category(', ') ?>
                    |
                    <?php edit_post_link('Изменить', '', ' | '); ?>
                    <?php comments_popup_link('Нет комментариев &#187;', '1 Комментарий &#187;', '% Комментариев &#187;'); ?>
                </p>
            </div>
        <hr class="clear-contentunit" />
        
		<?php endwhile; ?>
                
		<?php else : ?>

        <!-- Pagetitle -->
        <h1 class="pagetitle">Не найдено</h1>
        <!-- Content unit - One column -->
        <div class="column1-unit">
          <h1></h1>
          <p>Извините, то что вы ищите не найдено.</p>
          <?php include (TEMPLATEPATH . "/searchform.php"); ?>
        </div>
        <hr class="clear-contentunit" />
        </div>

		<?php endif; ?>
        
        </div> <!-- <div class="main-content"> -->

<?php include (TEMPLATEPATH . "/r_sidebar.php"); ?>

<?php get_footer(); ?>
