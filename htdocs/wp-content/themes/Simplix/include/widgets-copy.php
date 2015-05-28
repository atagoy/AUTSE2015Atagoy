<?php

// 125x125 Banner Widget
function BannerWidget()
{
	$settings = get_option("widget_banwidget");
	$name = $settings['name'];
	$image = $settings['image'];
	$url = $settings['url'];
	
	$image2 = $settings['image2'];
	$url2 = $settings['url2'];
	
	$image3 = $settings['image3'];
	$url3 = $settings['url3'];
	
	$image4 = $settings['image4'];
	$url4 = $settings['url4'];

?>

				<div class="sidebar-wg">
							<div class="sidebar-head"><h2><?php echo $name; ?></h2></div>
								<ul class="advert">
									<?php if ($image != '') { ?><li><a href="<?php echo $url; ?>"><img src="<?php echo $image; ?>" width="125" height="125" alt="" /></a></li><?php } ?>
									<?php if ($image2 != '') { ?><li><a href="<?php echo $url2; ?>"><img src="<?php echo $image2; ?>" width="125" height="125" alt="" /></a></li><?php } ?>
									<?php if ($image3 != '') { ?><li><a href="<?php echo $url3; ?>"><img src="<?php echo $image3; ?>" width="125" height="125" alt="" /></a></li><?php } ?>
									<?php if ($image4 != '') { ?><li><a href="<?php echo $url4; ?>"><img src="<?php echo $image4; ?>" width="125" height="125" alt="" /></a></li><?php } ?>
									
								</ul>
								<div style="clear:both;"></div>
						</div>
					
						
<?php
}

function BannerWidgetAdmin() {

	$settings = get_option("widget_banwidget");

	// check if anything's been sent
	if (isset($_POST['update_banner'])) {
		$settings['name'] = stripslashes($_POST['ban_name']);
		
		$settings['image'] = stripslashes($_POST['ban_image']);
		$settings['url'] = stripslashes($_POST['ban_url']);
		
		$settings['image2'] = stripslashes($_POST['ban_image2']);
		$settings['url2'] = stripslashes($_POST['ban_url2']);
		
		$settings['image3'] = stripslashes($_POST['ban_image3']);
		$settings['url3'] = stripslashes($_POST['ban_url3']);
		
		$settings['image4'] = stripslashes($_POST['ban_image4']);
		$settings['url4'] = stripslashes($_POST['ban_url4']);


		update_option("widget_banwidget",$settings);
	}

	echo '<p>
			<label for="ban_name">Section name:
			<input id="ban_name" name="ban_name" type="text" class="widefat" value="'.$settings['name'].'" /></label></p>';
	
	echo '<p>
			<label for="ban_image">Full Image Path:
			<input id="ban_image" name="ban_image" type="text" class="widefat" value="'.$settings['image'].'" /></label></p>';
			
	echo '<p>
			<label for="ban_url">URL:
			<input id="ban_url" name="ban_url" type="text" class="widefat" value="'.$settings['url'].'" /></label></p>';
			
			
	
	echo '<p>
			<label for="ban_image2">Full Image Path:
			<input id="ban_image2" name="ban_image2" type="text" class="widefat" value="'.$settings['image2'].'" /></label></p>';
			
	echo '<p>
			<label for="ban_url2">URL:
			<input id="ban_url2" name="ban_url2" type="text" class="widefat" value="'.$settings['url2'].'" /></label></p>';
			
			
			
	
	echo '<p>
			<label for="ban_image3">Full Image Path:
			<input id="ban_image3" name="ban_image3" type="text" class="widefat" value="'.$settings['image3'].'" /></label></p>';
			
	echo '<p>
			<label for="ban_url3">URL:
			<input id="ban_url3" name="ban_url3" type="text" class="widefat" value="'.$settings['url3'].'" /></label></p>';
			
			
			
			
			
	echo '<p>
			<label for="ban_image4">Full Image Path:
			<input id="ban_image4" name="ban_image4" type="text" class="widefat" value="'.$settings['image4'].'" /></label></p>';
			
	echo '<p>
			<label for="ban_url4">URL:
			<input id="ban_url4" name="ban_url4" type="text" class="widefat" value="'.$settings['url4'].'" /></label></p>';
	
	
	
	echo '<input type="hidden" id="update_banner" name="update_banner" value="1" />';

}

register_sidebar_widget('Combo 125x125 Banner', 'BannerWidget');
register_widget_control('Combo 125x125 Banner', 'BannerWidgetAdmin', 400, 200);