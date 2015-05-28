<?php 
	get_header(); 
?>

	<div class="contents clear"> 
		<?php 
            require_once 'includes/left-side.php';			
        ?>
        <div class="middle-content">
        	<?php if (have_posts()) : ?>
				<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
                <?php /* If this is a category archive */ if (is_category()) { ?>
                <h2 class="title_top"><?php single_cat_title(); ?></h2>
                <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
                <h2 class="title_top">Метка &laquo;<?php single_tag_title(); ?>&raquo;</h2>
                <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
                <h2 class="title_top"><?php the_time('d M Y'); ?></h2>
                <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
                <h2 class="title_top"><?php the_time('F Y'); ?></h2>
                <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
                <h2 class="title_top"><?php the_time('Y'); ?></h2>
                <?php /* If this is an author archive */ } elseif (is_author()) { ?>
                <h2 class="title_top">Архив автора</h2>
                <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
                <h2 class="title_top">Архив сайта</h2>
            <?php } ?>
            
        	<div class="archives">
           			
				<?php 
                    while (have_posts()) : the_post(); 
                        include (TEMPLATEPATH . "/post.php"); 
                    endwhile; 
                    endif; 
                
				    if(function_exists('wp_pagenavi')) { 
                        wp_pagenavi(); 
                    } 
                ?>  			                
        	</div>
		</div>
        <?php 
            get_sidebar();
        ?>    	
    </div>
</div>
    
<?php 
	get_footer(); 
?>
