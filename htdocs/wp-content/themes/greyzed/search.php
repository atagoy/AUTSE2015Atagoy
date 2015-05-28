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
	<?php $postcount = "0"; // reset post counter ?>
	<?php if (have_posts()) : ?>

		<h2 class="archivetitle">Search Results</h2>


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

	<?php else : ?>

		<h2>No posts found. Try a different search?</h2>
		<!-- begin search -->
		<div class="search-box2">
			<form method="get" action="<?php bloginfo('url'); ?>/">
			<input type="text" size="15" class="search-field" name="s" id="s" value="search this site" onfocus="if(this.value == 'search this site') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search this site';}"/><input type="submit"  value="" class="search-go" />
			</form>
		</div>
		<!-- end search -->

	<?php endif; ?>

	</div>
	<div id="nav-post">
		<div class="navigation-bott">
			<?php if($endvar == 0 || $postcount == $endvar) { } else { ?>
				<div class="leftnav"><?php next_posts_link('Older Entries') ?></div>
				<?php } if ($paged > 1) { ?>
				<div class="rightnav"><?php previous_posts_link('Newer Entries') ?></div>
			<?php } ?>
		</div>

		</div>

</div>

<?php get_footer(); ?>
