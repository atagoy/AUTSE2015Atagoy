<?php 
/*
Template Name: Sitemap Page
*/
?>
<?php the_post(); ?>

<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];
?>

<?php get_header(); ?>

<div id="main-content" <?php if($fullwidth) echo ('class="fullwidth"');?>>
	<div id="main-content-wrap" class="clearfix">
		<div id="left-area">
			<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
			<div class="entry post clearfix">							
				<?php if (get_option('inreview_page_thumbnails') == 'on') { ?>
					<?php 
						$thumb = '';
						$width = 222;
						$height = 231;
						$classtext = 'post-thumb';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Single');
						$thumb = $thumbnail["thumb"];
					?>
					
					<?php if($thumb <> '') { ?>
						<div class="post-thumbnail">
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
							<span class="post-overlay"></span>
						</div> 	<!-- end .post-thumbnail -->
					<?php } ?>
				<?php } ?>
				
				<h1 class="title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','InReview').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
				<div id="sitemap">
					<div class="sitemap-col">
						<h2><?php _e('Pages','InReview'); ?></h2>
						<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
					</div> <!-- end .sitemap-col -->
					
					<div class="sitemap-col">
						<h2><?php _e('Categories','InReview'); ?></h2>
						<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
					</div> <!-- end .sitemap-col -->
					
					<div class="sitemap-col<?php if (!$fullwidth) echo ' last'; ?>">
						<h2><?php _e('Tags','InReview'); ?></h2>
						<ul id="sitemap-tags">
							<?php $tags = get_tags();
							if ($tags) {
								foreach ($tags as $tag) {
									echo '<li><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li> ';
								}
							} ?>
						</ul>
					</div> <!-- end .sitemap-col -->
					
					<?php if (!$fullwidth) { ?>
						<div class="clear"></div>
					<?php } ?>
					
					<div class="sitemap-col<?php if ($fullwidth) echo ' last'; ?>">
						<h2><?php _e('Authors','InReview'); ?></h2>
						<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
					</div> <!-- end .sitemap-col -->
				</div> <!-- end #sitemap -->
				
				<div class="clear"></div>
				
				<?php edit_post_link(__('Edit this page','InReview')); ?>			
			</div> <!-- end .entry -->
						
		</div> 	<!-- end #left-area -->

		<?php if (!$fullwidth) get_sidebar(); ?>
	</div> <!-- end #main-content-wrap -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>