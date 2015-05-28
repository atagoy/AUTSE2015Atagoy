<?php class AdsenseWidget extends WP_Widget
{
    function AdsenseWidget(){
		$widget_ops = array('description' => 'Displays Adsense Ads');
		$control_ops = array('width' => 400, 'height' => 500);
		parent::WP_Widget(false,$name='ET Adsense Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Adsense' : $instance['title']);
		$adsenseCode = empty($instance['adsenseCode']) ? '' : $instance['adsenseCode'];

		echo $before_widget;

		if ( $title )
		echo $before_title . $title . $after_title;
?>	
<div style="overflow: hidden;">
	<?php echo($adsenseCode)?>
	<div class="clearfix"></div>
</div> <!-- end adsense -->
<?php
		echo $after_widget;
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = stripslashes($new_instance['title']);
		$instance['adsenseCode'] = stripslashes($new_instance['adsenseCode']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'Adsense', 'adsenseCode'=>'') );

		$title = htmlspecialchars($instance['title']);
		$adsenseCode = htmlspecialchars($instance['adsenseCode']);

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
		# Adsense Code
		echo '<p><label for="' . $this->get_field_id('adsenseCode') . '">' . 'Adsense Code:' . '</label><textarea cols="20" rows="12" class="widefat" id="' . $this->get_field_id('adsenseCode') . '" name="' . $this->get_field_name('adsenseCode') . '" >'. $adsenseCode .'</textarea></p>';	
	}

}// end AdsenseWidget class

function AdsenseWidgetInit() {
  register_widget('AdsenseWidget');
}

add_action('widgets_init', 'AdsenseWidgetInit');

?>