<?php if($title && !(@$params['noTitle'])){
	echo $params['before_title'].$title.$params['after_title'];
}?>
	<form method='get' class='<?php echo $formCssClass?>' action='<?php echo $formAction?>'>
<?php echo $hidden ?>
		<div class='searchform-params'>
<?php		foreach($inputs as $input){?>
<div class='<?php echo $input->getCSSClass()?>'><?php echo $input->getInput()?></div>
<?php		}?>
</div>
<div class='searchform-controls'>

<input type='submit' name='search' value='<?php _e('Search','wp-custom-fields-search')?>'/>
</div>
<?php if(array_key_exists('showlink',$config) && $config['showlink']) { ?>
<div class='searchform-spoiler'><?php echo $spoiler_link; ?></div>
<?php } ?>
</form>
