<?php
/**
 * @package WordPress
 * @copyright Copyright (C) 2010 pixelthemestudio.ca - All Rights Reserved.
 * @license GPL/GNU
 * @subpackage glowingamber
 */

get_header(); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" id="columns">
  <tr>
    <td id="leftcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Column')) : ?><?php endif; ?></td>
    <td id="content">
<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>


		

		<?php while (have_posts()) : the_post(); ?>
		<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		<!-- Date Posted by row -->
		<div id="metadata"><div>
			<?php $get_year = get_the_time('Y'); $get_month = get_the_time('m'); ?>
			Posted <a href="<?php echo get_month_link($get_year, $get_month); ?>"><?php the_time('M j Y') ?></a> by <?php the_author_posts_link(); ?>		
					<?php if ( count(($categories=get_the_category())) > 1 || $categories[0]->cat_ID != 1 ) : ?>
						 in <?php the_category(', ') ?>
					<?php endif; ?>
			with <?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?>
		</div></div>
<!-- Post Content -->
<div class="entry">
<?php the_content(); ?>
</div>


		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
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

	<td id="rightcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Column')) : ?><?php endif; ?></td>
  </tr>
</table>

<?php get_footer(); ?>
