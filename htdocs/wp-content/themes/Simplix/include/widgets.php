<?php
/**
* @version		v.1.0
* @copyright	Copyright (C) 2008 NattyWP. All rights reserved.
* @author		Dave Miller
*
* This code is protected under Creative Commons License: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

function wp_widget_text_test($args, $widget_args = 1) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('banner_widget_text');
	if ( !isset($options[$number]) )
		return;

	$title = apply_filters('widget_title', $options[$number]['title']);	
	$size = apply_filters('widget_size', $options[$number]['size']);
	$ads_image = apply_filters('widget_ads_image', $options[$number]['ads_image']);
	$ads_url = apply_filters('widget_ads_url', $options[$number]['ads_url']);
?>
		<?php echo $before_widget; ?>
			<?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
				<?php 
					switch ($size) {
						case "728x90" : $ban_width = "728"; $ban_height = "90"; break;
						case "468x60": $ban_width = "468"; $ban_height = "60"; break;
						case "234x60": $ban_width = "234"; $ban_height = "60"; break;
						case "120x600": $ban_width = "120"; $ban_height = "600"; break;
						case "160x600": $ban_width = "160"; $ban_height = "600"; break;
						case "120x240": $ban_width = "120"; $ban_height = "240"; break;
						case "336x280": $ban_width = "336"; $ban_height = "280"; break;
						case "300x250": $ban_width = "300"; $ban_height = "250"; break;
						case "250x250": $ban_width = "250"; $ban_height = "250"; break;
						case "200x200": $ban_width = "200"; $ban_height = "200"; break;
						case "180x150": $ban_width = "180"; $ban_height = "150"; break;
						case "125x125": $ban_width = "125"; $ban_height = "125"; break;
					}
				?>
				
			<?php if ($ads_image != '') { ?>
			<a href="<?php echo $ads_url; ?>"><img src="<?php bloginfo('template_url'); ?>/images/ads/<?php echo $ads_image; ?>" width="<?php echo $ban_width; ?>" alt="<?php echo $ads_image; ?>" height="<?php echo $ban_height; ?>" border="0" /></a>
			<?php } ?>	
		<?php echo $after_widget; ?>
<?php
}

function wp_widget_text_test_control($widget_args) {
	global $wp_registered_widgets;
	static $updated = false;

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('banner_widget_text');
	if ( !is_array($options) )
		$options = array();

	if ( !$updated && !empty($_POST['sidebar']) ) {
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( (array) $this_sidebar as $_widget_id ) {
			if ( 'wp_widget_text_test' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "text_test-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
					unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-text-test'] as $widget_number => $widget_text ) {
			if ( !isset($widget_text['ads_image']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$title = strip_tags(stripslashes($widget_text['title']));
			$size = $widget_text['size'];
			$ads_image = $widget_text['ads_image'];
			$ads_url = stripslashes( $widget_text['ads_url'] );								
			$options[$widget_number] = compact( 'title', 'size', 'ads_image', 'ads_url' );
		}

		update_option('banner_widget_text', $options);
		$updated = true;
	}

	if ( -1 == $number ) {
		$title = '';
		$size = '';
		$ads_image = '';
		$ads_url = '';
		$number = '%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);		
		$size = attribute_escape($options[$number]['size']);
		$ads_image = attribute_escape($options[$number]['ads_image']);
		$ads_url = attribute_escape($options[$number]['ads_url']);
	}

	$banner_size[] = array( "#NONE#", "- Select -" );
	$banner_size[] = array( "728x90", " 728 x 90 Leaderboard" );
	$banner_size[] = array( "468x60", " 468 x 60 Banner" );
	$banner_size[] = array( "234x60", " 234 x 60 Half Banner" );
	$banner_size[] = array( "#NONE#", "Vertical" );
	$banner_size[] = array( "120x600", " 120 x 600 Skyscraper" );
	$banner_size[] = array( "160x600", " 160 x 600 Wide Skyscraper" );
	$banner_size[] = array( "120x240", " 120 x 240 Vertical Banner" );
	$banner_size[] = array( "#NONE#", "Square" );
	$banner_size[] = array( "336x280", " 336 x 280 Large Rectangle" );
	$banner_size[] = array( "300x250", " 300 x 250 Medium Rectangle" );
	$banner_size[] = array( "250x250", " 250 x 250 Square" );
	$banner_size[] = array( "200x200", " 200 x 200 Small Square" );
	$banner_size[] = array( "180x150", " 180 x 150 Small Rectangle" );
	$banner_size[] = array( "125x125", " 125 x 125 Button" );
?>
		<p>
			<label>Title:</label>
			<input class="widefat" id="text-title-<?php echo $number; ?>" name="widget-text-test[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label>Select banner size:</label>
			<?php ih_select( 'widget-text-test['.$number.'][size]', $banner_size, $size, "" ); ?>
		</p>
		<p>	
			<label>Select banner image</label>
			
			<?php 
				// get styles
				$ih_adsdir = opendir( TEMPLATEPATH . "/images/ads/" );
				$ih_ads_array[] = array( "", "None (default)" );
				// Open a known directory, and proceed to read its contents
				while (false !== ( $ih_adsfolder = readdir( $ih_adsdir ) ) ) {
					if( $ih_adsfolder != "." && $ih_adsfolder != ".." ) {
						$ih_ads_name = $ih_adsfolder;
						$ih_ads_array[] = array( $ih_adsfolder, $ih_ads_name );
					}
				}
				
				closedir( $ih_adsdir );
				
				ih_select( 'widget-text-test['.$number.'][ads_image]', $ih_ads_array, $ads_image, "" );
			?>
		</p>
		<p>
			<label>URL:</label>
			<input class="widefat" id="text-title-<?php echo $number; ?>" name="widget-text-test[<?php echo $number; ?>][ads_url]" type="text" value="<?php echo $ads_url; ?>" />
		</p>
		<p>	
			<input type="hidden" name="widget-text-test[<?php echo $number; ?>][submit]" value="1" />
		</p>
<?php
}

function wp_widget_text_test_register() {
	if ( !$options = get_option('banner_widget_text') )
		$options = array();
		
	$widget_ops = array('classname' => 'widget_text_test', 'description' => __('Add and configure banners'));
	$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'text_test');
	$name = __('NattyWP Banner widget');

	$id = false;
	foreach ( (array) array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['ads_image']) || !isset($options[$o]['size']) )
			continue;
		$id = "text_test-$o"; // $id should look like {$id_base}-{$o}
		wp_register_sidebar_widget($id, $name, 'wp_widget_text_test', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'wp_widget_text_test_control', $control_ops, array( 'number' => $o ));
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$id ) {
		wp_register_sidebar_widget( 'text_test-1', $name, 'wp_widget_text_test', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'text_test-1', $name, 'wp_widget_text_test_control', $control_ops, array( 'number' => -1 ) );
	}
}


/*  Adsense widgets */

function wp_widget_ads_test($args, $widget_args = 1) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('adsense_widget_text');
	if ( !isset($options[$number]) )
		return;

	$title = apply_filters('widget_title', $options[$number]['title']);	
	$size = apply_filters('widget_size', $options[$number]['size']);
	$ads_id = apply_filters('widget_ads_id', $options[$number]['ads_id']);
?>
		<?php echo $before_widget; ?>
			<?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
				<?php 
					switch ($size) {
						case "728x90" : $ban_width = "728"; $ban_height = "90"; break;
						case "468x60": $ban_width = "468"; $ban_height = "60"; break;
						case "234x60": $ban_width = "234"; $ban_height = "60"; break;
						case "120x600": $ban_width = "120"; $ban_height = "600"; break;
						case "160x600": $ban_width = "160"; $ban_height = "600"; break;
						case "120x240": $ban_width = "120"; $ban_height = "240"; break;
						case "336x280": $ban_width = "336"; $ban_height = "280"; break;
						case "300x250": $ban_width = "300"; $ban_height = "250"; break;
						case "250x250": $ban_width = "250"; $ban_height = "250"; break;
						case "200x200": $ban_width = "200"; $ban_height = "200"; break;
						case "180x150": $ban_width = "180"; $ban_height = "150"; break;
						case "125x125": $ban_width = "125"; $ban_height = "125"; break;
					}
				?>
				
			<?php if ($ads_id != '') { ?>
			<script type="text/javascript"><!--
				google_ad_client = "<?php echo get_settings( "t_googleid" ); ?>";
				google_ad_slot = "<?php echo $ads_id; ?>";
				google_ad_width = <?php echo $ban_width; ?>;
				google_ad_height = <?php echo $ban_height; ?>;
				//-->
				</script>
				<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			<?php } ?>	
		<?php echo $after_widget; ?>
<?php
}

function wp_widget_ads_test_control($widget_args) {
	global $wp_registered_widgets;
	static $updated = false;

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('adsense_widget_text');
	if ( !is_array($options) )
		$options = array();

	if ( !$updated && !empty($_POST['sidebar']) ) {
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( (array) $this_sidebar as $_widget_id ) {
			if ( 'wp_widget_ads_test' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "ads_test-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
					unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-ads-test'] as $widget_number => $widget_text ) {
			if ( !isset($widget_text['ads_id']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$title = strip_tags(stripslashes($widget_text['title']));
			$size = $widget_text['size'];
			$ads_id = stripslashes( $widget_text['ads_id'] );								
			$options[$widget_number] = compact( 'title', 'size', 'ads_id' );
		}

		update_option('adsense_widget_text', $options);
		$updated = true;
	}

	if ( -1 == $number ) {
		$title = '';
		$size = '';
		$ads_id = '';
		$number = '%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);		
		$size = attribute_escape($options[$number]['size']);
		$ads_id = attribute_escape($options[$number]['ads_id']);
	}

	$banner_size[] = array( "#NONE#", "- Select -" );
	$banner_size[] = array( "728x90", " 728 x 90 Leaderboard" );
	$banner_size[] = array( "468x60", " 468 x 60 Banner" );
	$banner_size[] = array( "234x60", " 234 x 60 Half Banner" );
	$banner_size[] = array( "#NONE#", "Vertical" );
	$banner_size[] = array( "120x600", " 120 x 600 Skyscraper" );
	$banner_size[] = array( "160x600", " 160 x 600 Wide Skyscraper" );
	$banner_size[] = array( "120x240", " 120 x 240 Vertical Banner" );
	$banner_size[] = array( "#NONE#", "Square" );
	$banner_size[] = array( "336x280", " 336 x 280 Large Rectangle" );
	$banner_size[] = array( "300x250", " 300 x 250 Medium Rectangle" );
	$banner_size[] = array( "250x250", " 250 x 250 Square" );
	$banner_size[] = array( "200x200", " 200 x 200 Small Square" );
	$banner_size[] = array( "180x150", " 180 x 150 Small Rectangle" );
	$banner_size[] = array( "125x125", " 125 x 125 Button" );
?>
		<p>
			<label>Title:</label>
			<input class="widefat" id="text-title-<?php echo $number; ?>" name="widget-ads-test[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label>Select adsense size:</label>
			<?php ih_select( 'widget-ads-test['.$number.'][size]', $banner_size, $size, "" ); ?>
		</p>
		
		<p>
			<label>Ad Slot ID:</label>
			<input class="widefat" id="text-title-<?php echo $number; ?>" name="widget-ads-test[<?php echo $number; ?>][ads_id]" type="text" value="<?php echo $ads_id; ?>" />
			<small>Put the Ad Slot ID for here. (Eg. YYYYYYYYYY)</small>
		</p>
		<p>	
			<input type="hidden" name="widget-ads-test[<?php echo $number; ?>][submit]" value="1" />
		</p>
<?php
}

function wp_widget_ads_test_register() {
	if ( !$options = get_option('adsense_widget_text') )
		$options = array();
		
	$widget_ops = array('classname' => 'widget_ads_test', 'description' => __('Configure your Adsense'));
	$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'ads_test');
	$name = __('NattyWP Adsense widget');

	$id = false;
	foreach ( (array) array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['ads_id']) || !isset($options[$o]['size']) )
			continue;
		$id = "ads_test-$o"; // $id should look like {$id_base}-{$o}
		wp_register_sidebar_widget($id, $name, 'wp_widget_ads_test', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'wp_widget_ads_test_control', $control_ops, array( 'number' => $o ));
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$id ) {
		wp_register_sidebar_widget( 'ads_test-1', $name, 'wp_widget_ads_test', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'ads_test-1', $name, 'wp_widget_ads_test_control', $control_ops, array( 'number' => -1 ) );
	}
}

add_action('widgets_init', 'wp_widget_text_test_register');
add_action('widgets_init', 'wp_widget_ads_test_register');

 ?>