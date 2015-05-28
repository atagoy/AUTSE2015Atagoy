<?php the_post(); ?>
<?php get_header(); ?>

<?php
	$et_inreview_settings = maybe_unserialize( get_post_meta($post->ID,'_et_inreview_settings',true) );
	$et_is_editor_choice = isset( $et_inreview_settings['et_is_editor_choice'] ) ? (bool) $et_inreview_settings['et_is_editor_choice'] : false;
	$et_fs_price = isset( $et_inreview_settings['et_fs_price'] ) && !empty($et_inreview_settings['et_fs_price']) ? $et_inreview_settings['et_fs_price'] : '';
	$et_affiliate_text = isset( $et_inreview_settings['et_affiliate_text'] ) && !empty($et_inreview_settings['et_affiliate_text']) ? $et_inreview_settings['et_affiliate_text'] : '';
	$et_affiliate_link = isset( $et_inreview_settings['et_affiliate_link'] ) && !empty($et_inreview_settings['et_affiliate_link']) ? $et_inreview_settings['et_affiliate_link'] : '';
	
	$et_author_rating = get_post_meta($post->ID,'_et_inreview_user_rating',true) ? get_post_meta($post->ID,'_et_inreview_user_rating',true) : 0;
	$et_comments_rating = get_post_meta($post->ID,'_et_inreview_comments_rating',true) ? get_post_meta($post->ID,'_et_inreview_comments_rating',true) : et_get_post_user_rating($post->ID);
	
	$et_features = get_post_meta($post->ID,'_et_inreview_features',true) ? get_post_meta($post->ID,'_et_inreview_features',true) : array();
	if ( !empty($et_features) ) $et_features = array_filter($et_features, 'strlen');
	
	$et_features_rating = get_post_meta($post->ID,'_et_features_rating',true) ? get_post_meta($post->ID,'_et_features_rating',true) : array();
?>

<div id="main-content">
	<div id="main-content-wrap" class="clearfix">
		<div id="left-area">
			<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
			<div class="entry post clearfix">
				<?php if (get_option('inreview_integration_single_top') <> '' && get_option('inreview_integrate_singletop_enable') == 'on') echo(get_option('inreview_integration_single_top')); ?>
				
				<?php if (get_option('inreview_thumbnails') == 'on') { ?>
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
							
							<?php if ( $et_is_editor_choice ){ ?>
								<span class="et-belt et-choice"></span>
							<?php } ?>
							<?php if ( $et_fs_price ){ ?>
								<span class="price-tag"><span><?php echo $et_fs_price; ?></span></span>
							<?php } ?>
						</div> 	<!-- end .post-thumbnail -->
					<?php } ?>
				<?php } ?>
				
				<div class="post-description">					
					<h1 class="title"><?php the_title(); ?></h1>
					<?php include(TEMPLATEPATH . '/includes/postinfo.php'); ?>
					
					<?php if ( $et_author_rating <> 0 ) { ?>
						<div class="rating-container">
							<div class="rating-inner clearfix">
								<span><?php _e('Author','InReview'); ?></span>
								<div class="review-rating">
									<div class="review-score" style="width: <?php echo et_get_star_rating($et_author_rating); ?>px;"></div> 
								</div>
							</div> <!-- end .rating-inner -->
						</div> <!-- end .rating-container -->
					<?php } ?>
					
					<?php if ( $et_comments_rating <> 0 ) { ?>
						<div class="rating-container">
							<div class="rating-inner clearfix">
								<span><?php _e('Users','InReview'); ?></span>
								<div class="review-rating">
									<div class="review-score" style="width: <?php echo et_get_star_rating($et_comments_rating); ?>px;"></div>
								</div>
							</div> <!-- end .rating-inner -->
						</div> <!-- end .rating-container -->
					<?php } ?>
					
					<div class="clear"></div>
					
					<?php if ( !empty($et_features) && !empty($et_features_rating) ) { ?>
						<div id="feature-box">
							<div id="feature-container">
								<ul id="feature-items">
									<?php for ( $i=0; $i < count($et_features); $i++ ){ ?>
										<li class="clearfix<?php if ( $i == ( count($et_features) - 1 ) ) echo ' last'; ?>">
											<span><?php echo $et_features[$i]; ?></span>
											<div class="review-rating">
												<div class="review-score" style="width: <?php echo et_get_star_rating($et_features_rating[$i]); ?>px;"></div> <!-- 16 * rating ( 0.5, 1, 1.5, 2... ) -->
											</div>
										</li>
									<?php } ?>
								</ul>
							</div> <!-- end #feature-container -->
						</div> <!-- end #feature-box -->
					<?php } ?>
				</div> 	<!-- end .post-description -->	
				<div class="clear"></div>
				
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','InReview').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php edit_post_link(__('Edit this page','InReview')); ?>
				</div> <!-- end .entry-content -->
			</div> <!-- end .entry -->
			
			<?php if ( $et_affiliate_link <> '' && $et_affiliate_text <> '' ) { ?>
				<div class="et-link"><a href="<?php echo $et_affiliate_link; ?>" class="readmore"><span><?php echo $et_affiliate_text; ?></span></a></div>
			<?php } ?>
			
			<?php if (get_option('inreview_integration_single_bottom') <> '' && get_option('inreview_integrate_singlebottom_enable') == 'on') echo(get_option('inreview_integration_single_bottom')); ?>		
						
			<?php if (get_option('inreview_468_enable') == 'on') { ?>
					  <?php if(get_option('inreview_468_adsense') <> '') echo(get_option('inreview_468_adsense'));
					else { ?>
					   <a href="<?php echo(get_option('inreview_468_url')); ?>"><img src="<?php echo(get_option('inreview_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
			   <?php } ?>   
			<?php } ?>
			
			<?php if (get_option('inreview_show_postcomments') == 'on') comments_template('', true); ?>
		</div> 	<!-- end #left-area -->
		
		<?php get_sidebar(); ?>

	</div> <!-- end #main-content-wrap -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>