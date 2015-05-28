<?php get_header(); ?>

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
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>" style=" float: none;">
				
					
					
					<div class="indent">
                            <div class="container">
                            	<img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icon-1.gif" class="img-left" />
                                <div class="indent1">
                                	<div class="title"><div><h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></div></div>
                                    <div class="date"><?php the_date(''); ?> @ <span><?php the_time(''); ?> </span></div>
                                    <div class="author">posted by <?php the_author_link() ?></div>
                                </div>
                                <br class="clear" />
                            </div>
                            <div class="text-box">
								<?php the_content('Read more'); ?>
                            </div>
                            <div class="comments"><?php comments_popup_link ( '0 comments', '1 comment', '% comments', '' , 'off'); ?></div>                        
                        </div>    
                        
                        
                        
                        
			
			</div>
		<?php endwhile; ?>
		
			<div class="navigation nav-top">
				<div class="alignleft"><?php next_posts_link('&lt;&lt; Older entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer entries &gt;&gt;') ?></div>
			</div>

	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='pagetitle'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2 class='pagetitle'>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='pagetitle'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='pagetitle'>No posts found.</h2>");
		}
		get_search_form();
	
		endif;
	?>
	


<?php get_footer(); ?>