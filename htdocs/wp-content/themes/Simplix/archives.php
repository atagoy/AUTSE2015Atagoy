<?php
/*
Template Name: Archives
*/
?>

<?php get_header();?>
<?php get_sidebar();?>
<div class="right_col">
					<div class="twitter-block">
						<div id="status">	
						<div id="status-twitter">&nbsp;</div>						
							<p><strong>Currently:</strong></p>
                			<ul id="twitter_update_list">
								<li>
								<div><a href="<?php echo get_option('home'); ?>/">Home</a> &raquo; Archives</div>								
								</li>
							</ul>
						</div>
					</div>
					
					<?php $t_feedburnerurl = get_settings('t_feedburnerurl'); if ($t_feedburnerurl == '') {?>
					<a href="<?php bloginfo('rss2_url'); ?>" class="rss">&nbsp;</a>
					<?php } else { ?>
					<a href="<?php echo $t_feedburnerurl ?>" class="rss">&nbsp;</a>
					<?php } ?>
						
						<div style="clear:both;"></div>
					<div class="post-top"></div>	
					<div class="main-post"><div class="single-pad">
					
						<?php while (have_posts()) : the_post(); ?>   
							<div class="content">
							
				<h1>Archives by Month:</h1>
				<div style="clear:both;"></div>
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
						<br /><br />
				<h1>Archives by Subject:</h1>
				<div style="clear:both;"></div>
				<ul>
					 <?php wp_list_categories('title_li='); ?>
				</ul>
				
			</div>

						<?php endwhile; ?>						
						<br /></div>
					</div>

					<div class="post-bottom"></div>
					<div id="featured-bot"></div>
					
					
							<?php comments_template(); ?>

</div>
<?php get_footer();?>	