<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrywidget');

add_action('widgets_init', array("GantryWidgetColorChooser","init"));

class GantryWidgetColorChooser extends GantryWidget {
    var $short_name = 'colorchooser';
    var $wp_name = 'gantry_colorchooser';
    var $long_name = 'Gantry ColorChooser';
    var $description = 'Gantry ColorChooser Widget';
    var $css_classname = 'widget_gantry_colorchooser';
    var $width = 200;
    var $height = 400;

    function init() {
        register_widget("GantryWidgetColorChooser");
    }

	function render_widget_open($args, $instance){ ?>
    	<div class="clear"></div>
    <?php }
    
    function render_widget_close($args, $instance){
    }
    
    function pre_render($args, $instance) {
    }
    
    function post_render($args, $instance) {
    }

    function render($args, $instance){
        global $gantry;
        
        gantry_import('core.gantryjson');
		
		$gantry->addStyle('preset-creator.css');
		$gantry->addScript($gantry->gantryUrl . "/admin/widgets/colorchooser/js/mooRainbow.js");
		$gantry->addScript($gantry->gantryUrl . "/admin/widgets/slider/js/slider.js");
		$gantry->addScript($gantry->gantryUrl . "/admin/widgets/selectbox/js/selectbox.js");
		
		if ($this->_isAdmin()) {
			$gantry->addInlineScript("
				//<![CDATA[
				var GantryLang = {
					'preset_title': '" . _r('Gantry Presets Saver') . "',
					'preset_select': '" . _r('Select the Presets you want to save and choose a new name for them. Hit "skip" on a Presets section if you dont want to save as new that specific Preset.') . "',
					'preset_name': '" . _r('Preset Name') . "',
					'key_name': '" . _r('Key Name') . "',
					'preset_naming': '" . _r('Preset Naming for') . "',
					'preset_skip': '" . _r('Skip') . "',
					'success_save': '" . _r('NEW PRESET SAVED WITH SUCCESS!') . "',
					'success_msg': '" . _r('<p>The new Presets have been successfully saved and they are ready to be used right now. You will find them from the list of the respective presets.</p><p>Click "Close" button below to close this window.</p>') . "',
					'fail_save': '" . _r('SAVE FAILED') . "',
					'fail_msg': '" . _r('<p>It looks like the saving of the new Preset didnt succeed. Make sure your template folder and "custom/presets.ini" at your template folder root have write permissions.</p><p>Once you think you have fixed the permission, hit the button "Retry" below.</p><p>If it still fails, please ask for support on RocketTheme forums</p>') . "',
					'cancel': '" . _r('Cancel') . "',
					'save': '" . _r('Save') . "',
					'retry': '" . _r('Retry') . "',
					'close': '" . _r('Close') . "',
					'show_parameters': '" . _r('Show Parameters') . "'
				};
				//]]>
			");
			$gantry->addScript('preset-saver.js');
		}
		
		$gantry->addScript('preset-creator.js');
		
		$gantry->addInlineScript($this->_loadPresets());
		
		$cookie_time = $gantry->get('cookie_time') / 86400000 * 1000;
		
		$this->_jsInitialization($cookie_time);
		
		$shadows = array(
			'header' => $gantry->get('header_shadows'),
			'main' => $gantry->get('main_shadows'),
			'feature' => $gantry->get('feature_shadows'),
			'body' => $gantry->get('body_shadows'),
			'bottom' => $gantry->get('bottom_shadows')
		);
        
	    ob_start();
	    
	    if ($gantry->browser->name == "ie" && $gantry->browser->shortversion == "6") {
	    	echo '';
	    } else {
	    
	    ?>

		<div id="<?php echo $this->id; ?>" class="widget <?php echo $this->css_classname; ?> rt-block">
			<div id="rt-colorchooser">
				<a href="#" id="preset-creator">
					<span><?php echo $instance['text']; ?></span>
				</a>
			</div>
		</div>
		
		<div id="presets-popup">
			<div class="popup-toolbar">
				<a href="#" id="popup-minimize" class="big"><span><?php _re('min'); ?></span></a>
				<a href="#" id="popup-close"><span><?php _re('close'); ?></span></a>
			</div>
			<div id="popup-miniature"></div>
			<div class="popup-row" id="popup-presets">
				<div class="popup-title"><span><?php _re('Presets'); ?></span></div>
				<div class="popup-content">
					<div class="selectbox-container">
						<span class="popup-spantitle"><?php _re('Load Preset'); ?></span>
						<div class="selectbox-wrapper">	
							<div class="selectbox">		
								<?php
									$lis = $dropdownSpan = "<li>"._r('Choose Preset')."</li>";
									
									foreach($gantry->presets['presets'] as $preset_key => $preset_values) {
										$lis .=  "<li>".$preset_values['name']."</li>";
										$dropdownSpan = $preset_values['name'];
									}

								?>
								<div class="selectbox-top">			
									<div class="selected"><span><?php _re('Choose Preset'); ?></span></div>
									<div class="arrow"></div>
								</div>
								<div class="selectbox-dropdown">
									<ul>
										<?php echo $lis; ?>
									</ul>
								<div class="selectbox-dropdown-bottom">
									<div class="selectbox-dropdown-bottomright"></div>
								</div>
							</div>
						</div>
						<select id="presets" name="params[presets]" class="selectbox-real">
								<option value="_disabled_" class="disabled" selected="selected">Choose Preset</option>
							<?php
								foreach($gantry->presets['presets'] as $preset_key => $preset_values) {
									echo "<option value='".$preset_key."'>".$preset_values['name']."</option>";
								}
							?>
						</select>
					</div>
					<div class="clr"></div>
					<div class="controls">
						<?php if ($this->_isAdmin()): ?>
							<button id="popup-savenew" class="preset-saver"><span><?php _re('Save New'); ?></span></button>
						<?php endif; ?>
						<button id="popup-apply"><span><?php _re('Apply'); ?></span></button>
					</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-header">
				<div class="popup-title"><span><?php _re('Header'); ?></span></div>
				<div class="popup-content">
					<div class="popup-colors">
						<div class="popup-color background">
							<span><?php _re('Background'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color text">
							<span><?php _re('Text'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color link">
							<span><?php _re('Link'); ?></span>
							<div class="color-preview"></div>
						</div>
					</div>
					<div class="popup-overlay">
						<div class="popup-overlaycolor">
							<span><?php _re('Overlay'); ?></span>
							<div class="overlay-preview"><div></div></div>
						</div>
						<div class="popup-slider">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>

						<div class="popup-radios">
							<span><?php _re('Shadows'); ?></span>
							<div class="popup-shadows">
								<input type="radio" name="header-radio" id="header-none" value="none" alt="None" <?php echo ($shadows['header'] == 'none') ? 'checked="checked"' : ''; ?> />
								<label for="header-none"><?php _re('None'); ?></label>
								<input type="radio" name="header-radio" id="header-light" value="light" alt="Light" <?php echo ($shadows['header'] == 'light') ? 'checked="checked"' : ''; ?> />
								<label for="header-light"><?php _re('Light'); ?></label>
								<input type="radio" name="header-radio" id="header-dark" value="dark" alt="Dark" <?php echo ($shadows['header'] == 'dark') ? 'checked="checked"' : ''; ?> />
								<label for="header-dark"><?php _re('Dark'); ?></label>
							</div>
						</div>
					</div>
					<div class="popup-headeroverlay" style="height: 24px;">
						<span><?php _re('Header Graphic'); ?></span>
						<div class="popup-slider overlays-header">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-main">
				<div class="popup-title"><span><?php _re('Main'); ?></span></div>
				<div class="popup-content">
					<div class="popup-colors">
						<div class="popup-color background">
							<span><?php _re('Background'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color text">
							<span><?php _re('Text'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color link">
							<span><?php _re('Link'); ?></span>
							<div class="color-preview"></div>
						</div>
					</div>
					<div class="popup-overlay">
						<div class="popup-overlaycolor">
							<span><?php _re('Overlay'); ?></span>
							<div class="overlay-preview"><div></div></div>
						</div>
						<div class="popup-slider">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>

						<div class="popup-radios">
							<span><?php _re('Shadows'); ?></span>
							<div class="popup-shadows">
								<input type="radio" name="main-radio" id="main-none" value="none" alt="None"  <?php echo ($shadows['main'] == 'none') ? 'checked="checked"' : ''; ?> />
								<label for="main-none"><?php _re('None'); ?></label>
								<input type="radio" name="main-radio" id="main-light" value="light" alt="Light" <?php echo ($shadows['main'] == 'light') ? 'checked="checked"' : ''; ?> />
								<label for="main-light"><?php _re('Light'); ?></label>
								<input type="radio" name="main-radio" id="main-dark" value="dark" alt="Dark" <?php echo ($shadows['main'] == 'dark') ? 'checked="checked"' : ''; ?> />
								<label for="main-dark"><?php _re('Dark'); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-feature">
				<div class="popup-title"><span><?php _re('Feature'); ?></span></div>
				<div class="popup-content">
					<div class="popup-colors">
						<div class="popup-color background">
							<span><?php _re('Background'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color text">
							<span><?php _re('Text'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color link">
							<span><?php _re('Link'); ?></span>
							<div class="color-preview"></div>
						</div>
					</div>
					<div class="popup-overlay">
						<div class="popup-overlaycolor">
							<span><?php _re('Overlay'); ?></span>
							<div class="overlay-preview"><div></div></div>
						</div>
						<div class="popup-slider">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>

						<div class="popup-radios">
							<span><?php _re('Shadows'); ?></span>
							<div class="popup-shadows">
								<input type="radio" name="feature-radio" id="feature-none" value="none" alt="None" <?php echo ($shadows['feature'] == 'none') ? 'checked="checked"' : ''; ?> />
								<label for="feature-none"><?php _re('None'); ?></label>
								<input type="radio" name="feature-radio" id="feature-light" value="light" alt="Light" <?php echo ($shadows['feature'] == 'light') ? 'checked="checked"' : ''; ?> />
								<label for="feature-light"><?php _re('Light'); ?></label>
								<input type="radio" name="feature-radio" id="feature-dark" value="dark" alt="Dark" <?php echo ($shadows['feature'] == 'dark') ? 'checked="checked"' : ''; ?> />
								<label for="feature-dark"><?php _re('Dark'); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-body">
				<div class="popup-title"><span><?php _re('Body'); ?></span></div>
				<div class="popup-content">
					<div class="popup-colors">
						<div class="popup-color background">
							<span><?php _re('Background'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color text">
							<span><?php _re('Text'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color link">
							<span><?php _re('Link'); ?></span>
							<div class="color-preview"></div>
						</div>
					</div>
					<div class="popup-overlay">
						<div class="popup-overlaycolor">
							<span><?php _re('Overlay'); ?></span>
							<div class="overlay-preview"><div></div></div>
						</div>
						<div class="popup-slider">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>

						<div class="popup-radios">
							<span><?php _re('Shadows'); ?></span>
							<div class="popup-shadows">
								<input type="radio" name="body-radio" id="body-none" value="none" alt="None" <?php echo ($shadows['body'] == 'none') ? 'checked="checked"' : ''; ?> />
								<label for="body-none"><?php _re('None'); ?></label>
								<input type="radio" name="body-radio" id="body-light" value="light" alt="Light" <?php echo ($shadows['body'] == 'light') ? 'checked="checked"' : ''; ?> />
								<label for="body-light"><?php _re('Light'); ?></label>
								<input type="radio" name="body-radio" id="body-dark" value="dark" alt="Dark" <?php echo ($shadows['body'] == 'dark') ? 'checked="checked"' : ''; ?> />
								<label for="body-dark"><?php _re('Dark'); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-bottom">
				<div class="popup-title"><span><?php _re('Bottom'); ?></span></div>
				<div class="popup-content">
					<div class="popup-colors">
						<div class="popup-color background">
							<span><?php _re('Background'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color text">
							<span><?php _re('Text'); ?></span>
							<div class="color-preview"></div>
						</div>
						<div class="popup-color link">
							<span><?php _re('Link'); ?></span>
							<div class="color-preview"></div>
						</div>
					</div>
					<div class="popup-overlay">
						<div class="popup-overlaycolor">
							<span><?php _re('Overlay'); ?></span>
							<div class="overlay-preview"><div></div></div>
						</div>
						<div class="popup-slider">
							<div class="popup-sliderinner"></div>
							<div class="popup-knob"></div>
						</div>

						<div class="popup-radios">
							<span><?php _re('Shadows'); ?></span>
							<div class="popup-shadows">
								<input type="radio" name="bottom-radio" id="bottom-none" value="none" alt="None" <?php echo ($shadows['bottom'] == 'none') ? 'checked="checked"' : ''; ?> />
								<label for="bottom-none"><?php _re('None'); ?></label>
								<input type="radio" name="bottom-radio" id="bottom-light" value="light" alt="Light" <?php echo ($shadows['bottom'] == 'light') ? 'checked="checked"' : ''; ?> />
								<label for="bottom-light"><?php _re('Light'); ?></label>
								<input type="radio" name="bottom-radio" id="bottom-dark" value="dark" alt="Dark" <?php echo ($shadows['bottom'] == 'dark') ? 'checked="checked"' : ''; ?> />
								<label for="bottom-dark"><?php _re('Dark'); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="popup-row" id="popup-palette">
				<div class="popup-title"><span><?php _re('Palette'); ?></span></div>
				<div class="popup-content">
						<div class="ase-colors-wrapper">
						<div id="ase-color1" class="ase-color"></div>
						<div id="ase-color2" class="ase-color"></div>
						<div id="ase-color3" class="ase-color"></div>
						<div id="ase-color4" class="ase-color"></div>
						<div id="ase-color5" class="ase-color"></div>
						<div id="ase-color6" class="ase-color"></div>
						<div id="ase-color7" class="ase-color"></div>
						<div id="ase-color8" class="ase-color"></div>
						<div id="ase-color9" class="ase-color"></div>
						<div id="ase-color10" class="ase-color"></div>
						</div>
						<div class="ase-controls">
		                    <form id="ase-load-form" action="index.php" enctype="multipart/form-data" method="post">
								<div class="ase-file-wrapper">
		                        	<input name="ase-file" id="ase-file" type="file" />
		                        	<div class="ase-file-fake"></div>
								</div>
		                    </form>
		                    <form id="ase-save-form" action="index.php" enctype="multipart/form-data" method="post">
								<button id="ase-save"><span><?php _re('Save Palette'); ?></span></button>
								<input type="hidden" value="" name="ase-colors-list" id="ase-colors-list" />
							</form>
						</div>
                        <iframe id="ase-load-target" name="ase-load-target"></iframe>
						<iframe id="ase-save-target" name="ase-save-target"></iframe>
                </div>
			</div>
			<div class="popup-tip">
				<div class="popup-tip-left"></div>
				<div class="popup-tip-mid">
					<span></span>
				</div>
				<div class="popup-tip-right"></div>
			</div>
		</div>
				
	    <?php
	    
	    }
	    
	    echo ob_get_clean();
	}
	
	function _isAdmin() {
		if (current_user_can('edit_theme_options')) return true;
		else return false;
	}
	
	function _loadPresets() {
		global $gantry;
		
		$output = "";
		
		foreach($gantry->presets['presets'] as $key => $preset) {
            $preset_name = $preset['name'];
			$output .= "'$preset_name': {";
			foreach($preset as $keyName => $preset_value) {
                if ($keyName != 'name'){
				    $output .= "'$keyName': '$preset_value', ";
                }
			}
			$output = substr($output, 0, -2);
			$output .= "}, ";
		}

		$output = substr($output, 0, -2);
		
		$output = 'Presets = new Hash({'.$output.'});';
		
		return "var Presets = {}; " . $output;
	}
	
	function _jsInitialization($cookie_time) {
		global $gantry;
		
		$overlays = array();

        $__overlays = $gantry->retrieveTemp('overlays','overlays', array());
        $_templateCSSRules =  $gantry->retrieveTemp('overlays','templatecss', array());
        $_templateCSSVariations =  $gantry->retrieveTemp('overlays','templatecssvariations', array());
		
		foreach ($__overlays as $overlay => $list) {
			$overlays[$overlay] = "'none': {'file': 'overlay-off.png', 'value': 'none', 'name': 'Off', 'path': '".$gantry->templateUrl."/images/overlay-off.png'}, ";
			foreach ($list as $name => $file) {
				$overlays[$overlay] .= "'" . $file['name'] . "': {'file': '" . $file['file'] . "', 'value': '".$file['name']."', 'name': '".$name."', 'path': '".$gantry->templateUrl."/images/overlays/".$overlay."/".$file['file']."'}, ";
			}
			$overlays[$overlay] = substr($overlays[$overlay], 0, -2); 
		}
		
        if (substr($gantry->baseUrl, -1, 1) == '/') {
            $baseUrl = substr($gantry->baseUrl, 0, -1);
        }
        else {
            $baseUrl = $gantry->baseUrl;
        }

		$gantry->addInlineScript("
			
			var Crystalline = {
				cssRules: new Hash(".GantryJSON::encode($_templateCSSRules)."),
				varRules: new Hash(".GantryJSON::encode($_templateCSSVariations)."),
				savedCSS: new Hash({
					'main-background': '".$gantry->get('main-background')."',
					'header-background': '".$gantry->get('header-background')."',
					'header-text': '".$gantry->get('header-text')."',
					'header-link': '".$gantry->get('header-link')."',
					'feature-background': '".$gantry->get('feature-background')."',
					'feature-text': '".$gantry->get('feature-text')."',
					'feature-link': '".$gantry->get('feature-link')."',
					'body-background': '".$gantry->get('body-background')."',
					'body-text': '".$gantry->get('body-text')."',
					'body-link': '".$gantry->get('body-link')."',
					'bottom-background': '".$gantry->get('bottom-background')."',
					'bottom-text': '".$gantry->get('bottom-text')."',
					'bottom-link': '".$gantry->get('bottom-link')."',
					'header-overlay': '".$gantry->get('header-overlay')."',
					'main-text': '".$gantry->get('main-text')."',
					'main-link': '".$gantry->get('main-link')."',
					'main-overlay': '".$gantry->get('main-overlay')."',
					'feature-overlay': '".$gantry->get('feature-overlay')."',
					'body-overlay': '".$gantry->get('body-overlay')."',
					'bottom-overlay': '".$gantry->get('bottom-overlay')."',
					'header-graphic': '".$gantry->get('header-graphic')."',
					'header-shadows': '".$gantry->get('header-shadows')."',
					'main-shadows': '".$gantry->get('main-shadows')."',
					'feature-shadows': '".$gantry->get('feature-shadows')."',
					'body-shadows': '".$gantry->get('body-shadows')."',
					'bottom-shadows': '".$gantry->get('bottom-shadows')."'
				}),
				headers: new Hash({".$overlays['headers']."}),
				patterns: new Hash({".$overlays['patterns']."}),
				shadows: new Hash({
					'header-': ['rt-main-header'],
					'main-': ['rt-main-surround2'],
					'feature-': ['rt-utility', 'rt-feature'],
					'body-': ['rt-mainbody-bg'],
					'bottom-': ['rt-bottom', 'rt-main-footer']
				}),
				mooRainbow: {},
				sliders: {},
				templateName: '".$gantry->templateName."',
				baseUrl: '".$baseUrl."',
				templateUrl: '".$gantry->templateUrl."',
				gantryURL: '".$gantry->gantryUrl."',
				aseLoadUrl: '".$gantry->templateUrl."/ase-load.php',
				aseSaveUrl: '".$gantry->templateUrl."/ase-save.php',
				cookiePrefix: '".$gantry->template_prefix.$gantry->_base_params_checksum."',
				cookieTime: ".$cookie_time."
			}
		");
		
	}
	
}