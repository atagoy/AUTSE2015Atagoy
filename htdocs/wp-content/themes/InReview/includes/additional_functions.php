<?php 

/* Meta boxes */

function admin_init(){
	add_meta_box("et_post_meta", "ET Settings", "et_post_meta", "post", "normal", "high");
}
add_action("admin_init", "admin_init");

function et_post_meta($callback_args) {
	global $post;
	$temp_array = array();
	$temp_array = maybe_unserialize(get_post_meta($post->ID,'_et_inreview_settings',true));
	
	$et_feature_ratings = array();
	$features = get_post_meta($post->ID,'_et_inreview_features',true) ? get_post_meta($post->ID,'_et_inreview_features',true) : array();
	$et_features_rating = get_post_meta($post->ID,'_et_features_rating',true) ? get_post_meta($post->ID,'_et_features_rating',true) : array();
	$et_feature_ratings = apply_filters( 'et_inreview_features', $et_features_rating );
	do_action( 'et_inreview_get_features' );
		
	$et_is_editor_choice = isset( $temp_array['et_is_editor_choice'] ) ? (int) $temp_array['et_is_editor_choice'] : 0;
	$et_fs_variation = isset( $temp_array['et_fs_variation'] ) ? (int) $temp_array['et_fs_variation'] : 0;
	$et_fs_button = isset( $temp_array['et_fs_button'] ) ? $temp_array['et_fs_button'] : '';
	$et_fs_link = isset( $temp_array['et_fs_link'] ) ? $temp_array['et_fs_link'] : '';
	$et_fs_price = isset( $temp_array['et_fs_price'] ) ? $temp_array['et_fs_price'] : '';
	$et_affiliate_link = isset( $temp_array['et_affiliate_link'] ) ? $temp_array['et_affiliate_link'] : '';
	$et_affiliate_text = isset( $temp_array['et_affiliate_text'] ) ? $temp_array['et_affiliate_text'] : '';
	?>
	
	<div id="et_custom_settings" style="margin: 13px 0 17px 4px;">			
		<?php 
			$features_number = apply_filters( 'et_inreview-features-number', 5 );
			for ( $i=1; $i <= $features_number; $i++ ) { ?>
				<div class="et_setting" style="margin: 13px 0 26px 4px; overflow: hidden;">
					<p style="float: left;"><label for="et_feature_name<?php echo $i; ?>" style="color: #000; font-weight: bold;"> Feature #<?php echo $i; ?>: </label>
					<input type="text" style="width: 15em;" value="<?php if ( isset($features[$i-1]) ) echo $features[$i-1]; ?>" name="et_feature_name[]" id="et_feature_name<?php echo $i; ?>" /></p>
					
					<p style="float: left;">
						<?php for ( $increment = 0.5; $increment <= 5; $increment = $increment+0.5  ) { ?>
							<?php 
								if ( isset($et_feature_ratings[$i-1]) && $et_feature_ratings[$i-1] <> 0 && $et_feature_ratings[$i-1] == $increment ) $checked = ' checked="checked"';
								else $checked = '';
							?>
							<input name="et_star<?php echo $i; ?>" type="radio" class="star {half:true}" value="<?php echo $increment; ?>"<?php echo $checked; ?> />
						<?php } ?>
					</p>
				</div>
		<?php 
			} 
		?>

		<div class="et_setting" style="margin: 13px 0 26px 4px;">
			<label class="selectit" for="et_is_editor_choice" style="font-weight: bold;">
				<input type="checkbox" name="et_is_editor_choice" id="et_is_editor_choice" value=""<?php checked( $et_is_editor_choice ); ?> /> Editor's Choice
			</label>
			<br/>
		</div>
		
		<div class="et_setting" style="margin: 13px 0 26px 4px;">
			<label for="et_fs_price" style="color: #000; font-weight: bold;"> Price: </label>
			<input type="text" style="width: 30em;" value="<?php echo $et_fs_price; ?>" id="et_fs_price" name="et_fs_price" size="67" />
			<br />
			<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("$25");?></code></small>
		</div>
		
		<div class="et_setting" style="margin: 13px 0 26px 4px;">
			<label for="et_affiliate_link" style="color: #000; font-weight: bold;"> Affiliate URL: </label>
			<input type="text" style="width: 30em;" value="<?php echo $et_affiliate_link; ?>" id="et_affiliate_link" name="et_affiliate_link" size="67" />
			<br />
			<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("http://elegantthemes.com");?></code></small>
		</div>
		
		<div class="et_setting" style="margin: 13px 0 26px 4px;">
			<label for="et_affiliate_text" style="color: #000; font-weight: bold;"> Affiliate Button Text: </label>
			<input type="text" style="width: 30em;" value="<?php echo $et_affiliate_text; ?>" id="et_affiliate_text" name="et_affiliate_text" size="67" />
			<br />
			<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("Purchase Photoshop CS Today");?></code></small>
		</div>
				
		<div class="et_fs_settings" style="margin: 13px 0 26px 4px; border-top: 1px solid #EEE;">
			<h2>Featured Slider Options</h2>
					
			<div class="et_setting" style="margin: 13px 0 26px 4px;">
				<label class="selectit" for="et_fs_variation" style="font-weight: bold;">
					<input type="checkbox" name="et_fs_variation" id="et_fs_variation" value=""<?php checked( $et_fs_variation ); ?> /> Use Png Image for Featured Slider
				</label>
				<br/>
			</div>
			
			<div class="et_setting" style="margin: 13px 0 26px 4px;">
				<label for="et_fs_button" style="color: #000; font-weight: bold;"> Custom Button Text: </label>
				<input type="text" style="width: 30em;" value="<?php echo $et_fs_button; ?>" id="et_fs_button" name="et_fs_button" size="67" />
				<br />
				<small style="position: relative; top: 8px;">ex: <code><?php echo htmlspecialchars("Click Here to Read The Full Review");?></code></small>
			</div>
			
			<div class="et_setting" style="margin: 13px 0 26px 4px;">
				<label for="et_fs_link" style="color: #000; font-weight: bold;"> Custom Read More Link: </label>
				<input type="text" style="width: 30em;" value="<?php echo $et_fs_link; ?>" id="et_fs_link" name="et_fs_link" size="67" />
				<br />
			</div>
		</div>
	</div> <!-- #et_custom_settings -->
		
	<?php
}

add_action('save_post', 'save_details');
function save_details($post_id){
	global $post;
	$rating_number = 0;
	$features_number = 0;
	$temp_array = array();
			
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	
	$temp_array['et_is_editor_choice'] = isset( $_POST["et_is_editor_choice"] ) ? 1 : 0;
	$temp_array['et_fs_variation'] = isset( $_POST["et_fs_variation"] ) ? 1 : 0;
	$temp_array['et_fs_button'] = isset($_POST["et_fs_button"]) ? $_POST["et_fs_button"] : '';
	$temp_array['et_fs_link'] = isset($_POST["et_fs_link"]) ? $_POST["et_fs_link"] : '';
	$temp_array['et_fs_price'] = isset($_POST["et_fs_price"]) ? $_POST["et_fs_price"] : '';
	$temp_array['et_affiliate_link'] = isset($_POST["et_affiliate_link"]) ? $_POST["et_affiliate_link"] : '';
	$temp_array['et_affiliate_text'] = isset($_POST["et_affiliate_text"]) ? $_POST["et_affiliate_text"] : '';
	update_post_meta( $post->ID, "_et_inreview_settings", $temp_array );
	
	
	$features = isset( $_POST["et_feature_name"] ) ? $_POST["et_feature_name"] : '';
	if ( $features <> '' ) update_post_meta( $post->ID, "_et_inreview_features", $features );
	
	$rating1 = isset( $_POST["et_star1"] ) ? $_POST["et_star1"] : 0;
	$rating2 = isset( $_POST["et_star2"] ) ? $_POST["et_star2"] : 0;
	$rating3 = isset( $_POST["et_star3"] ) ? $_POST["et_star3"] : 0;
	$rating4 = isset( $_POST["et_star4"] ) ? $_POST["et_star4"] : 0;
	$rating5 = isset( $_POST["et_star5"] ) ? $_POST["et_star5"] : 0;
	$features_rating = apply_filters( 'et_inreview_features_save', array($rating1, $rating2, $rating3, $rating4, $rating5) );
	
	foreach ( $features_rating as $rating ) {
		#actual number of features used in the post
		if ( $rating != 0 ) $features_number++;
	}
	for ( $i=1; $i<=$features_number; $i++ ){
		$rating_number += $features_rating[$i-1];
	}
	
	$post_rating = $features_number <> 0 ? round( $rating_number / $features_number, 2 ) : 0;
	
	if ( get_post_meta( $post->ID, "_et_inreview_user_rating", true ) == $post_rating ) return $post_id;
	else {
		update_post_meta( $post->ID, "_et_features_rating", $features_rating );
		update_post_meta( $post->ID, "_et_inreview_user_rating", $post_rating );
	}
	
	do_action( 'et_inreview_save_features' );
}

add_action( 'admin_enqueue_scripts', 'upload_etsettings_scripts' );
function upload_etsettings_scripts( $hook_suffix ) {
	if ( in_array( $hook_suffix, array('post.php','post-new.php') ) ) {
		wp_enqueue_script('metadata', get_bloginfo('template_directory') . '/js/jquery.MetaData.js', array('jquery'), '3.13', true);
		wp_enqueue_script('rating', get_bloginfo('template_directory') . '/js/jquery.rating.pack.js', array('jquery'), '3.13', true);
		wp_enqueue_style('et-rating', get_bloginfo('template_directory') . '/css/jquery.rating.css');
	}
}


// RATING Functions //

add_action('wp_enqueue_scripts', 'et_add_comment_rating');
function et_add_comment_rating(){
	#integrate jquery rating files into single post pages ( frontend )
	if ( is_single() ) {
		wp_enqueue_script('metadata', get_bloginfo('template_directory') . '/js/jquery.MetaData.js', array('jquery'), '3.13', true);
		wp_enqueue_script('rating', get_bloginfo('template_directory') . '/js/jquery.rating.pack.js', array('jquery'), '3.13', true);
		wp_enqueue_style('et-rating', get_bloginfo('template_directory') . '/css/jquery.rating.css');
	}
}

add_action('comment_post','et_add_rating_commentmeta', 10, 2);
function et_add_rating_commentmeta( $comment_id, $comment_approved ){
	#when user adds a comment, check if it's approved
	
	$comment_rating = ( isset($_POST['et_star']) ) ? $_POST['et_star'] : 0;
	add_comment_meta($comment_id,'et_comment_rating',$comment_rating);
	if ( $comment_approved == 1 ) {	
		$comment_info = get_comment($comment_id);		
		et_update_post_user_rating( $comment_info->comment_post_ID );
	}
}

add_action('et-comment-meta','et_show_comment_rating');
function et_show_comment_rating( $comment_id ){
	#displays user comment rating on single post page ( frontend )
	
	$user_comment_rating = get_comment_meta($comment_id,'et_comment_rating',true) ? get_comment_meta($comment_id,'et_comment_rating',true) : 0;				
	if ( $user_comment_rating <> 0 ) { ?>
		<div class="rating-container">
			<div class="rating-inner clearfix">
				<div class="review-rating">
					<div class="review-score" style="width: <?php echo et_get_star_rating($user_comment_rating); ?>px;"></div> 
				</div>
			</div> <!-- end .rating-inner -->
		</div> <!-- end .rating-container -->
	<?php }
}

add_action('et_comment_form','et_choose_rating_form');
function et_choose_rating_form(){
	#adds 'Choose a Rating' to the comment form ( frontend )
	if ( !is_page() ) {
?>
		<div id="et-rating" class="clearfix">
			<span id="choose_rating"><?php _e('Choose a Rating','InReview'); ?></span>
			
			<div class="rating-container">
				<div class="rating-inner clearfix">
					<?php for ( $increment = 0.5; $increment <= 5; $increment = $increment+0.5  ) { ?>
						<input name="et_star" type="radio" class="star {half:true}" value="<?php echo $increment; ?>" />
					<?php } ?>
				</div> <!-- end .rating-inner -->
			</div> <!-- end .rating-container -->
		</div> <!-- end #et-rating -->
<?php }
}

function et_get_post_user_rating( $post_id ){
	#calculates user (comments) rating for the post
	$approved_comments = et_get_approved_comments( $post_id );
	if ( empty($approved_comments) ) return 0;
	
	$user_rating = 0;
	$approved_comments_number = count($approved_comments);
	
	foreach ( $approved_comments as $comment ) {
		$comment_rating = get_comment_meta($comment->comment_ID,'et_comment_rating',true) ? get_comment_meta($comment->comment_ID,'et_comment_rating',true) : 0;
		if ( $comment_rating == 0 ) $approved_comments_number--;
		
		$user_rating += $comment_rating;
	}
	
	$result = ( $user_rating <> 0 ) ? round( $user_rating / $approved_comments_number, 2 ) : 0;
	# save user rating to the post meta
	if ( !get_post_meta($post_id,'_et_inreview_comments_rating',true) ) update_post_meta($post_id,'_et_inreview_comments_rating',$result);
	
	return $result;
}

function et_update_post_user_rating( $post_id ){
	#update (recalculate) user (comments) rating for the post
	$new_comments_rating = et_get_post_user_rating( $post_id );
	
	if ( get_post_meta($post_id,'_et_inreview_comments_rating',true) <> $new_comments_rating )
		update_post_meta($post_id,'_et_inreview_comments_rating',$new_comments_rating);
}

add_action('wp_set_comment_status','et_comment_status_changed', 10, 2);
function et_comment_status_changed($comment_id, $comment_status){
	$comment_info = get_comment( $comment_id );		
	et_update_post_user_rating( $comment_info->comment_post_ID );
}

function et_get_approved_comments($post_id) {
	global $wpdb;
	return $wpdb->get_results($wpdb->prepare("SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' ORDER BY comment_date", $post_id));
}

function et_get_star_rating( $rating ) {
	#round to 0.5, 1.5, 2.5 etc. * 16px ( star sprite image )
	$width_value = ( round( $rating*2 ) / 2 ) * 16;
	
	return $width_value;
}
?>