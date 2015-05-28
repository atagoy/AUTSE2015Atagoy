<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/
	
	// init vars
	$presets = $this->warp->config->get('_presets', array());

?>

<select id="selectPreset" style="width:200px;">
    <option value="-1">Please choose a preset...</option>
    <?php foreach($presets as $key => $preset): ?>
        <option value="<?php echo $key;?>"><?php echo $preset['name'];?></option>
    <?php endforeach; ?>
</select>

<script type="text/javascript">

		jQuery(function(){
			
			jQuery("#selectPreset").bind("change", function(select){
				
				var warp_presets = <?php echo json_encode($presets);?>;
				var select 		 = this;
				
				if(select.value == -1) return;
				
				var preset = warp_presets[select.value];
				var elements = jQuery("form[id=options]:first").find('[name^=<?php echo $this->warp->system->prefix;?>]');
				
				for(var i=0;i<elements.length;i++){
					var node = elements[i];
					var $name = node.name.replace('<?php echo $this->warp->system->prefix;?>','');
					
					if(preset.options[$name] || preset.options[$name]===0){
						if(node.type=='radio') {
							if(node.value==preset.options[$name]) jQuery(node).attr('checked',true);
						} else {
							jQuery(node).val(preset.options[$name]);
						}
					}
	  
				}
				//select.selectedIndex = 0;
			});
		});
        
       
</script>