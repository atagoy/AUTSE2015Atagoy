<?php if (is_archive()) $post_number = get_option('inreview_archivenum_posts');
if (is_search()) $post_number = get_option('inreview_searchnum_posts');
if (is_tag()) $post_number = get_option('inreview_tagnum_posts');
if (is_category()) $post_number = get_option('inreview_catnum_posts'); ?>
<?php get_header(); ?>

<div id="main-content">
	<div id="main-content-wrap" class="clearfix">
		<div id="left-area">
			<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
			<?php 
				global $query_string;
				$i = 0;
				if (is_category()) query_posts($query_string . "&showposts=$post_number&paged=$paged&cat=$cat");
				else query_posts($query_string . "&showposts=$post_number&paged=$paged"); 
			?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/entry.php'); ?>
			<?php endwhile; ?>
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { ?>
					 <?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
				<?php } ?>
			<?php else : ?>
				<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
			<?php endif; wp_reset_query(); ?>
		</div> <!-- end #left-area -->
		
		<?php get_sidebar(); ?>
	</div> <!-- end #main-content-wrap -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>