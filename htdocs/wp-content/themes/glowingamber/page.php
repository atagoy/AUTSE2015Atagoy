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
		<div class="post" id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
			<div class="entry">
						<?php the_content(); ?>
						<div class="clearboth"></div>
					</div>
		</div>
		<?php endwhile; endif; ?>
	
	
	<!-- Comments -->
<div id="commentswrapper">
	<?php comments_template(); ?>
</div>
	
	<td id="rightcol"><?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Column')) : ?><?php endif; ?></td>
  </tr>
</table>
	

<?php get_footer(); ?>