<?php get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
    
	<?php
    while ( have_posts() ) : the_post();
	
		//Content includes shortcode (only remove if you are calling it directly in your template)
		the_content();
		
	endwhile;
	
	//Reset postdata (avoid potential conflicts)
	wp_reset_postdata();
	?>
    
	</div>
</div>

<?php get_sidebar( 'content' ); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>