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

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<div class="posttitle">
					<h2 class="pagetitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small>Posted: <?php the_time('jS F Y') ?> by <strong><?php the_author() ?></strong> in <?php the_category(', ') ?><br />
				<?php the_tags( 'Tags: ', ', ', ''); ?></small>
				</div>
				<div class="postcomments"><?php comments_popup_link('0', '1', '%'); ?></div>

			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		<?php if (get_option('greyzed_theme_social') === "true") { ?>	
		<div id="share-con">
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
		</div>
		<?php } ?>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
	</div>
	</div>

		<div id="nav-post">
		<div class="navigation-bott">
			<?php next_post_link('%link', '<div class="leftnav">newer posts</div>') ?>
			<?php previous_post_link('%link', '<div class="rightnav">older posts</div>') ?>
		</div>

		</div>
	</div>
	
<?php get_footer(); ?>
