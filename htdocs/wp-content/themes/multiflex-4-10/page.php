<?php get_header(); ?>

<?php include (TEMPLATEPATH . "/l_sidebar.php"); ?>

      <!-- B.1 MAIN CONTENT -->
      <div class="main-content">
      
      	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        
        <!-- Pagetitle -->
        <h1 class="pagetitle"><?php the_title(); ?></h1>

        <!-- Content unit - One column -->
        <!-- <h1 class="block"></h1> -->
            <div class="column1-unit">
                <h1></h1>
                <h3></h3>
					<?php the_content('<p class="serif">Читать далее &raquo;</p>'); ?>
                
                	<?php wp_link_pages(array('before' => '<p><strong>Страницы:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
            </div>
        <hr class="clear-contentunit" />
        
		<?php endwhile; endif; ?>
		<?php edit_post_link('Изменить.', '<p>', '</p>'); ?>
        
        </div> <!-- <div class="main-content"> -->

<?php include (TEMPLATEPATH . "/r_sidebar.php"); ?>

<?php get_footer(); ?>
