<?php class AboutMeWidget extends WP_Widget
{
    function AboutMeWidget(){
		$widget_ops = array('description' => 'Displays About Me Information');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='ET About Me Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'About Me' : $instance['title']);
		$imagePath = empty($instance['imagePath']) ? '' : $instance['imagePath'];
		$aboutText = empty($instance['aboutText']) ? '' : $instance['aboutText'];

		echo $before_widget;

		if ( $title )
		echo $before_title . $title . $after_title;
?>	
<div class="clearfix">
	<img src="<?php bloginfo('template_directory'); ?>/timthumb.php?src=<?php echo et_multisite_thumbnail($imagePath); ?>&amp;h=74&amp;w=74&amp;zc=1" id="about-image" alt="about us image" />
	<p><?php echo($aboutText)?></p>
</div> <!-- end about me section -->
<?php
		echo $after_widget;
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = stripslashes($new_instance['title']);
		$instance['imagePath'] = stripslashes($new_instance['imagePath']);
		$instance['aboutText'] = stripslashes($new_instance['aboutText']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'About Me', 'imagePath'=>'', 'aboutText'=>'') );

		$title = htmlspecialchars($instance['title']);
		$imagePath = htmlspecialchars($instance['imagePath']);
		$aboutText = htmlspecialchars($instance['aboutText']);

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
		# Image
		echo '<p><label for="' . $this->get_field_id('imagePath') . '">' . 'Image:' . '</label><textarea cols="20" rows="2" class="widefat" id="' . $this->get_field_id('imagePath') . '" name="' . $this->get_field_name('imagePath') . '" >'. $imagePath .'</textarea></p>';	
		# About Text
		echo '<p><label for="' . $this->get_field_id('aboutText') . '">' . 'Text:' . '</label><textarea cols="20" rows="5" class="widefat" id="' . $this->get_field_id('aboutText') . '" name="' . $this->get_field_name('aboutText') . '" >'. $aboutText .'</textarea></p>';
	}

}// end AboutMeWidget class

function AboutMeWidgetInit() {
  register_widget('AboutMeWidget');
}

add_action('widgets_init', 'AboutMeWidgetInit');

?>