<?php get_header(); ?>

<?php if ( get_option('inreview_quote') == 'on' ) { ?>
	<div id="quote">
		<p id="tagline-quote">"<?php echo get_option('inreview_quote_text'); ?>"</p>
	</div> <!-- end #quote -->
<?php } ?>

<?php if ( is_home() && get_option('inreview_featured') == 'on' ) include(TEMPLATEPATH . '/includes/featured.php'); ?>

<div id="main-content">
	<div id="main-content-wrap" class="clearfix">
		<div id="left-area">
			<h4 class="widgettitle"><?php _e('recent product reviews','InReview'); ?></h4>
			
			<?php 
				$args=array(
					'showposts'=>get_option('inreview_homepage_posts'),
					'paged'=>$paged,
					'category__not_in' => get_option('inreview_exlcats_recent'),
				);
				$i = 0;
				if (get_option('inreview_duplicate') == 'false') $args['post__not_in'] = $ids;
				query_posts($args);
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