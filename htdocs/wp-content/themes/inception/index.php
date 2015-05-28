<?php get_header(); // Loads the header.php template. ?>

<?php
if(is_front_page()) : // Checks for front page

	$featured_page_array = array( get_theme_mod( 'inception_featured_first' ), get_theme_mod( 'inception_featured_second' ), get_theme_mod( 'inception_featured_third' ) );
	$get_featured_pages = new WP_Query( array(
				'posts_per_page' 			=> 3,
				'post_type'					=> array( 'page' ),
				'post__in'		 			=> $featured_page_array,
				'orderby' 		 			=> 'post__in',
				'ignore_sticky_posts' 	=> 1 					
			));
	?>
	<div id="featured-page-section" class="clearfix">
		<?php
		while ( $get_featured_pages->have_posts() ) : 
			$get_featured_pages->the_post();
			?>
			<div class="col-md-4">				
				<div class="page_text_container">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
					<h1 class="entry-title"><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_excerpt(); ?><a class="btn btn-default more-link" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php _e( 'Read more','inception' ); ?></a>
				</div>					
			</div>
			<?php
		endwhile; 
		wp_reset_postdata();
		?>
	</div>
<?php endif; // End checking for front page ?>

<?php
$layout_global = get_theme_mod('inception_sidebar','content-sidebar');

	if(is_singular()){
		
		$layout = get_post_meta( get_the_ID(), 'inception_sidebarlayout', 'default', true );
		
			if(($layout == "default") || (empty($layout))){
				$layout = $layout_global;
			}
	}
	else {
		$layout = $layout_global;
	}

	if ($layout == "sidebar-sidebar-content" ) {
		hybrid_get_sidebar( 'secondary' ); // Loads the sidebar/secondary.php template.
		}

	if ($layout == "sidebar-content-sidebar" || $layout == "sidebar-sidebar-content" || $layout == "sidebar-content") {
		hybrid_get_sidebar( 'primary' ); // Loads the sidebar/primary.php template.
		}
 ?> 

<main class="col-sm-12 <?php inception_main_layout_class(); ?>" <?php hybrid_attr( 'content' ); ?>>

	<?php if ( !is_front_page() && !is_singular() && !is_404() ) : // If viewing a multi-post page ?>

		<?php locate_template( array( 'misc/loop-meta.php' ), true ); // Loads the misc/loop-meta.php template. ?>

	<?php endif; // End check for multi-post page. ?>

	<?php if ( have_posts() ) : // Checks if any posts were found. ?>

		<?php while ( have_posts() ) : // Begins the loop through found posts. ?>

			<?php the_post(); // Loads the post data. ?>

			<?php hybrid_get_content_template(); // Loads the content/*.php template. ?>

			<?php if ( is_singular() ) : // If viewing a single post/page/CPT. ?>

				<?php comments_template( '', true ); // Loads the comments.php template. ?>

			<?php endif; // End check for single post. ?>

		<?php endwhile; // End found posts loop. ?>

		<?php locate_template( array( 'misc/loop-nav.php' ), true ); // Loads the misc/loop-nav.php template. ?>

	<?php else : // If no posts were found. ?>

		<?php locate_template( array( 'content/error.php' ), true ); // Loads the content/error.php template. ?>

	<?php endif; // End check for posts. ?>

</main><!-- #content -->

<?php

if ($layout == "sidebar-content-sidebar" || $layout == "content-sidebar-sidebar" || $layout == "content-sidebar") {
	hybrid_get_sidebar( 'secondary' ); // Loads the sidebar/secondary.php template.
	}
	
if ($layout == "content-sidebar-sidebar" ) {
	hybrid_get_sidebar( 'primary' ); // Loads the sidebar/primary.php template.
	}
 ?>
 
<?php get_footer(); // Loads the footer.php template. ?>