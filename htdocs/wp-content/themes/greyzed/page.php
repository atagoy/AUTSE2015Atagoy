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

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="posttitle">
				<h2 class="pagetitle"><?php the_title(); ?></h2>
				<small>By <strong><?php the_author() ?></strong></small>
			</div>
			<div class="postcomments"><?php comments_popup_link('0', '1', '%'); ?></div>

			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
			
		<!--<div id="share-con">
			<ul id="sharemenu">
				<li id="share-facebook"><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" title="Share on Facebook"><span>Share on Facebook</span></a></li>
				<li id="share-divider"></li>
				<li id="share-twitter"><a href="http://twitter.com/home?status=Currently reading <?php the_permalink(); ?>" title="Tweet this"><span>Tweet this</span></a></li>
				<li id="share-divider"></li>
				<li id="share-digg"><a href="http://www.digg.com/submit?phase=2&url=<?php the_permalink();?>" title="Digg It"><span>Digg It</span></a></li>
				<li id="share-divider"></li>
				<li id="share-delicious"><a href="http://delicious.com/post?url=<?php the_permalink();?>&title=<?php the_title();?>" title="Add to Delicious"><span>Add to delicious</span></a></li>
				<li id="share-divider"></li>
				<li id="share-stumble"><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Stumble upon it"><span>Stumble upon it</span></a></li>
				<li id="share-divider"></li>
				<li id="share-technorati"><a href="http://technorati.com/faves?sub=addfavbtn&add=<?php the_permalink();?>" title="Share on technorati"><span>Share on technorati</span></a></li>
				<li id="share-divider"></li>
				<li id="share-email"><a href="mailto:?subject=Thought you might like this&body=<?php the_permalink();?>" title="Email"><span>Email</span></a></li>
			</ul>
		</div>-->
		

	<?php comments_template(); ?>

			
		</div>
		
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	
	</div>
	
	</div>
<?php get_footer(); ?>

<?php get_footer(); ?>
