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
	
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
<!-- Date Posted by row -->
		<div id="metadata"><div id="metadata2">
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
			<?php edit_post_link('Edit this entry','[',']'); ?>
		<div class="clearboth"></div>
				<?php the_tags( '<p class="tags">Tags: ', ', ', '</p>'); ?>
				
				
			
		<div class="navigation">
				<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
				<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
			</div>
				<?php endwhile; else: ?>
			<p>Sorry, no posts matched your criteria.</p>
			<?php endif; ?>
<!-- Comments -->
<div id="commentswrapper">
	<?php comments_template(); ?>
</div>
	<td id="rightcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Column')) : ?><?php endif; ?></td>
  </tr>
</table>




<?php get_footer(); ?>