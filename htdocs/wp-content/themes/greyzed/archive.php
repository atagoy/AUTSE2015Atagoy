<?php
/**
 * @package WordPress
 * @subpackage Greyzed
 */

get_header(); ?>

<div id="container">	
<?php get_sidebar(); ?>	
	<div id="content" role="main">

	
	<div class="column">


		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="archivetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="archivetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="archivetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="archivetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="archivetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="archivetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="archivetitle">Blog Archives</h2>
 	  <?php } ?>
		<?php $postcount = "0"; // reset post counter ?>
		<?php while (have_posts()) : the_post(); ?>
		 <?php $postcount++; // post counter ?>
		<div <?php post_class() ?>>
				
				<div class="posttitle">
				<h2 class="pagetitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small>Posted: <?php the_time('jS F Y') ?> by <strong><?php the_author() ?></strong> in <?php the_category(', ') ?><br />
				<?php the_tags( 'Tags: ', ', ', ''); ?></small>
				</div>
				<div class="postcomments"><?php comments_popup_link('0', '1', '%'); ?></div>

				<div class="entry">
					<?php the_excerpt() ?>
				</div>

			</div>

		<?php endwhile; ?>

		<?php
			// Find page with last post
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$postsppage= get_option('posts_per_page');
			$total = $paged * $postsppage;
			$remainder = $total - $wp_query->found_posts;
			$endvar =  $postsppage - $remainder;
		?>	

	
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
		get_search_form();

	endif;
?>
</div>

<div id="nav-post">
		<div class="navigation-bott">
					<div class="navigation">
			<?php if($endvar == 0 || $postcount == $endvar) { } else { ?>
				<div class="leftnav"><?php next_posts_link('Older Entries') ?></div>
				<?php } if ($paged > 1) { ?>
				<div class="rightnav"><?php previous_posts_link('Newer Entries') ?></div>
			<?php } ?>
		</div>
		</div>

		</div>


</div>

<?php get_footer(); ?>
