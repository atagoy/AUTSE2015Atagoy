<div id="featured">
	<div id="featured-bottom">
		<h3 id="recommended"><span><?php _e('recommended products','InReview'); ?></span></h3>
		<a id="left-arrow" href="#"><?php _e('Previous','InReview'); ?></a>
		<a id="right-arrow" href="#"><?php _e('Next','InReview'); ?></a>
	<?php 
		global $ids;
		$ids = array();
		$arr = array();
		$i=1;
		
		$width = 320;
		$height = 204;
						
		$featured_cat = get_option('inreview_feat_cat'); 
		$featured_num = get_option('inreview_featured_num'); 
			
		if (get_option('inreview_use_pages') == 'false') query_posts("showposts=$featured_num&cat=".get_catId($featured_cat));
		else {
			global $pages_number;
			
			if (get_option('inreview_feat_pages') <> '') $featured_num = count(get_option('inreview_feat_pages'));
			else $featured_num = $pages_number;
					
			query_posts(array('post_type' => 'page',
							'orderby' => 'menu_order',
							'order' => 'DESC',
							'post__in' => get_option('inreview_feat_pages'),
							'showposts' => $featured_num));
		};
				
		while (have_posts()) : the_post();
			global $post, $authordata;
								
			$arr[$i]["title"] = truncate_title(250,false);
			$arr[$i]["fulltitle"] = truncate_title(250,false);
			$arr[$i]["excerpt"] = truncate_post(290,false);
			
			$arr[$i]["metainfo"] = '';
			if ( get_option('inreview_postinfo1') <> '' ) {
				$arr[$i]["metainfo"] = __('Reviewed','InReview') . ' ';
				if ( in_array('author', get_option('inreview_postinfo1')) ) {
					$arr[$i]["metainfo"] .= __('by','InReview') . ' ' . '<a href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) .'">' . get_the_author() . '</a>' . ' ';
				}
				if (in_array('date', get_option('inreview_postinfo1'))) {
					$arr[$i]["metainfo"] .= __('on','InReview') . ' ' . get_the_time(get_option('inreview_date_format'))  . ' ';
				}
				if (in_array('categories', get_option('inreview_postinfo1'))) {
					$arr[$i]["metainfo"] .= __('in','InReview') . get_the_category(', ') . ' ';
				}
			}
								
			$et_inreview_settings = maybe_unserialize( get_post_meta($post->ID,'_et_inreview_settings',true) );
			
			$arr[$i]["et_fs_variation"] = isset( $et_inreview_settings['et_fs_variation'] ) ? (bool) $et_inreview_settings['et_fs_variation'] : false;
			$arr[$i]["permalink"] = isset( $et_inreview_settings['et_fs_link'] ) && !empty($et_inreview_settings['et_fs_link']) ? $et_inreview_settings['et_fs_link'] : get_permalink();
			$arr[$i]["moretext"] = isset( $et_inreview_settings['et_fs_button'] ) && !empty($et_inreview_settings['et_fs_button']) ? $et_inreview_settings['et_fs_button'] : 'Click to Read The Full Review';
					
			$arr[$i]["thumbnail"] = get_thumbnail($width,$height,'',$arr[$i]["fulltitle"],$arr[$i]["fulltitle"],true,'Featured');
			$arr[$i]["thumb"] = $arr[$i]["thumbnail"]["thumb"];		
			$arr[$i]["use_timthumb"] = $arr[$i]["thumbnail"]["use_timthumb"];
			
			$arr[$i]["et_author_rating"] = get_post_meta($post->ID,'_et_inreview_user_rating',true) ? get_post_meta($post->ID,'_et_inreview_user_rating',true) : 0;
			$arr[$i]["et_comments_rating"] = get_post_meta($post->ID,'_et_inreview_comments_rating',true) ? get_post_meta($post->ID,'_et_inreview_comments_rating',true) : et_get_post_user_rating($post->ID);
			
			$i++;
			$ids[] = $post->ID;
		endwhile; wp_reset_query();	?>
		<div id="featured-slides">
			<?php for ($i = 1; $i <= $featured_num; $i++) { ?>
				<div class="slide clearfix">
					<div class="featured-img<?php if ( $arr[$i]["et_fs_variation"] ) echo ' pngimage'; ?>">
						<a href="<?php echo $arr[$i]["permalink"]; ?>">
							<?php print_thumbnail($arr[$i]["thumb"], $arr[$i]["use_timthumb"], $arr[$i]["fulltitle"], $width, $height, 'featured-image'); ?>
							<?php if ( !$arr[$i]["et_fs_variation"] ) { ?>
								<span class="overlay"></span>
							<?php } ?>
						</a>	
					</div> 	<!-- end .featured-img -->
					<div class="featured-description">
						<h2 class="featured-title"><a href="<?php echo $arr[$i]["permalink"]; ?>"><?php echo $arr[$i]["title"]; ?></a></h2>
						
						<?php if ( $arr[$i]["metainfo"] <> '' ) { ?>
							<p class="featured-meta"><?php echo $arr[$i]["metainfo"]; ?></p>
						<?php } ?>
						
						<?php if ( $arr[$i]["et_author_rating"] <> 0 ) { ?>
							<div class="rating-container">
								<div class="rating-inner clearfix">
									<span><?php _e('Author','InReview'); ?></span>
									<div class="review-rating">
										<div class="review-score" style="width: <?php echo et_get_star_rating($arr[$i]["et_author_rating"]); ?>px;"></div> 
									</div>
								</div> <!-- end .rating-inner -->
							</div> <!-- end .rating-container -->
						<?php } ?>
						
						<?php if ( $arr[$i]["et_comments_rating"] <> 0 ) { ?>
							<div class="rating-container">
								<div class="rating-inner clearfix">
									<span><?php _e('Users','InReview'); ?></span>
									<div class="review-rating">
										<div class="review-score" style="width: <?php echo et_get_star_rating($arr[$i]["et_comments_rating"]); ?>px;"></div>
									</div>
								</div> <!-- end .rating-inner -->
							</div> <!-- end .rating-container -->
						<?php } ?>
						
						<div class="clear"></div>
						
						<p><?php echo $arr[$i]["excerpt"]; ?></p>
					</div> <!-- end .description -->
					<a href="<?php echo $arr[$i]["permalink"]; ?>" class="readmore"><span><?php echo $arr[$i]["moretext"]; ?></span></a>
				</div> <!-- end .slide -->
			<?php } ?>
		</div> <!-- end #featured-slides -->
	</div> <!-- end #featured-bottom -->
</div> <!-- end #featured -->