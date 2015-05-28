<?php class TopRatedWidget extends WP_Widget
{
    function TopRatedWidget(){
		$widget_ops = array('description' => 'Displays Top Rated Products');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='ET Top Rated Products Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Top Rated Products' : $instance['title']);
		$productsNumber = empty($instance['productsNumber']) ? '4' : $instance['productsNumber'];
		$reviewMethod = isset($instance['reviewMethod']) ? (int) $instance['reviewMethod'] : 1;
		
		echo $before_widget;

		if ( $title )
		echo $before_title . $title . $after_title;
?>	
		<?php
			$et_metakey = ( $reviewMethod == 1 ) ? '_et_inreview_user_rating' : '_et_inreview_comments_rating';
			$et_rating_query = new WP_Query( array ( 'orderby' => 'meta_value', 'meta_key' => $et_metakey, 'showposts'=> $productsNumber ) );
			while ( $et_rating_query->have_posts() ) : $et_rating_query->the_post();
		?>
				<div class="et-top-rated-top">
					<div class="et-top-rated-bottom">
						<div class="et-top-rated clearfix">
							<div class="rated-info">
								<?php
									global $post;
									$thumb = '';
									$width = 33;
									$height = 33;
									$classtext = '';
									$titletext = get_the_title();
									$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'widget_thumb');
									$thumb = $thumbnail["thumb"];
									$widget_et_author_rating = get_post_meta($post->ID,$et_metakey,true) ? get_post_meta($post->ID,$et_metakey,true) : 0;
								?>
								<?php if( $thumb <> '' ) { ?>
									<div class="rated-thumb">
										<a href="<?php the_permalink(); ?>">
											<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
											<span class="overlay"></span>
										</a>
									</div> <!-- end .rated-thumb -->
								<?php } ?>
								<h3 class="title"><a href="<?php the_permalink(); ?>"><?php truncate_title(20); ?></a></h3>
							</div> <!-- end .rated-info -->
							
							<?php if ( $widget_et_author_rating <> 0 ) { ?>
								<div class="rated-widget-stars">
									<div class="review-rating">
										<div class="review-score" style="width: <?php echo et_get_star_rating($widget_et_author_rating); ?>px;"></div> 
									</div>
								</div> <!-- end .rated-widget-stars -->
							<?php } ?>
						</div> <!-- end .et-top-rated -->
					</div> <!-- end .et-top-rated-bottom -->
				</div> <!-- end .et-top-rated-top -->
		<?php
			endwhile; wp_reset_postdata();
		?>
<?php
		echo $after_widget;
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = stripslashes($new_instance['title']);
		$instance['productsNumber'] = stripslashes($new_instance['productsNumber']);
		$instance['reviewMethod'] = (int) $new_instance['reviewMethod'];

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'Top Rated Products', 'imagePath'=>'', 'productsNumber'=>'4', 'reviewMethod'=>1) );

		$title = htmlspecialchars($instance['title']);
		$productsNumber = htmlspecialchars($instance['productsNumber']);
		$reviewMethod = (int) $instance['reviewMethod'];

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
		# Number of Posts
		echo '<p><label for="' . $this->get_field_id('productsNumber') . '">' . 'Number of Products:' . '</label><input class="widefat" id="' . $this->get_field_id('productsNumber') . '" name="' . $this->get_field_name('productsNumber') . '" type="text" value="' . $productsNumber . '" /></p>';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('reviewMethod'); ?>">Based on: </label>
			<select name="<?php echo $this->get_field_name('reviewMethod'); ?>" id="<?php echo $this->get_field_id('reviewMethod'); ?>" class="widefat">
				<option value="1"<?php selected( $instance['reviewMethod'], 1 ); ?>>Author ratings</option>
				<option value="2"<?php selected( $instance['reviewMethod'], 2 ); ?>>Users ratings</option>
			</select>
		</p>
	<?php }

}// end TopRatedWidget class

function TopRatedWidgetInit() {
  register_widget('TopRatedWidget');
}

add_action('widgets_init', 'TopRatedWidgetInit');

?>