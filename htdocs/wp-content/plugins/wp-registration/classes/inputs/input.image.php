<?php
/*
 * Followig class handling pre-uploaded image control and their dependencies. Do not make changes in code Create on: 9 November, 2013
 */
class NM_Image extends NM_Inputs_wpregisration {
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings;
	
	/*
	 * this var is pouplated with current plugin meta
	 */
	var $plugin_meta;
	function __construct() {
		
		$this -> plugin_meta = get_plugin_meta_wpregistration();
		
		$this->title = __ ( 'Image (Pre-uploaded)', 'wp-registration' );
		$this->desc = __ ( 'Images selection', 'wp-registration' );
		$this->settings = self::get_settings ();
	}
	private function get_settings() {
		return array (
				'title' => array (
						'type' => 'text',
						'title' => __ ( 'Title', 'wp-registration' ),
						'desc' => __ ( 'It will be shown as field label', 'wp-registration' ) 
				),
				'data_name' => array (
						'type' => 'text',
						'title' => __ ( 'Data name', 'wp-registration' ),
						'desc' => __ ( 'REQUIRED: The identification name of this field, that you can insert into body email configuration. Note:Use only lowercase characters and underscores.', 'wp-registration' ) 
				),
				'description' => array (
						'type' => 'text',
						'title' => __ ( 'Description', 'wp-registration' ),
						'desc' => __ ( 'Small description, it will be diplay near name title.', 'wp-registration' ) 
				),
				'error_message' => array (
						'type' => 'text',
						'title' => __ ( 'Error message', 'wp-registration' ),
						'desc' => __ ( 'Insert the error message for validation.', 'wp-registration' ) 
				),
				
				'class' => array (
						'type' => 'text',
						'title' => __ ( 'Class', 'wp-registration' ),
						'desc' => __ ( 'Insert an additional class(es) (separateb by comma) for more personalization.', 'wp-registration' ) 
				),
				
				'width' => array (
						'type' => 'text',
						'title' => __ ( 'Width', 'wp-registration' ),
						'desc' => __ ( 'Type field width in % e.g: 50%', 'wp-registration' ) 
				),
				
				'required' => array (
						'type' => 'checkbox',
						'title' => __ ( 'Required', 'wp-registration' ),
						'desc' => __ ( 'Select this if it must be required.', 'wp-registration' ) 
				),
				
				'images' => array (
						'type' => 'pre-images',
						'title' => __ ( 'Select images', 'wp-registration' ),
						'desc' => __ ( 'Select images from media library', 'wp-registration' ) 
				),
				
				'multiple_allowed' => array (
						'type' => 'checkbox',
						'title' => __ ( 'Allow multiple?', 'wp-registration' ),
						'desc' => __ ( 'Allow users to select more then one images?.', 'wp-registration' ) 
				),
				
				'popup_width' => array (
						'type' => 'text',
						'title' => __ ( 'Popup width', 'wp-registration' ),
						'desc' => __ ( 'Popup window width in px e.g: 750', 'wp-registration' ) 
				),
				
				'popup_height' => array (
						'type' => 'text',
						'title' => __ ( 'Popup height', 'wp-registration' ),
						'desc' => __ ( 'Popup window height in px e.g: 550', 'wp-registration' ) 
				),
				
				'logic' => array (
						'type' => 'checkbox',
						'title' => __ ( 'Enable conditional logic', 'wp-registration' ),
						'desc' => __ ( 'Tick it to turn conditional logic to work below', 'wp-registration' ) 
				),
				'conditions' => array (
						'type' => 'html-conditions',
						'title' => __ ( 'Conditions', 'wp-registration' ),
						'desc' => __ ( 'Tick it to turn conditional logic to work below', 'wp-registration' ) 
				) 
		);
	}
	
	/*
	 * @params: $options
	 */
	function render_input($args, $images = "", $existing_val = "") {
		$existing_images = unserialize ( $existing_val );
		
		$_html = '<div class="pre_upload_image_box">';
		
		$img_index = 0;
		$popup_width = $args ['popup-width'] == '' ? 600 : $args ['popup-width'];
		$popup_height = $args ['popup-height'] == '' ? 450 : $args ['popup-height'];
		
		if ( is_array($images) ){
		foreach ( $images as $image ) {
			$checked = '';
			/*if ( !isset($image ['link']) ){
				$image ['link'] = '';
			}*/
			$_html .= '<div class="pre_upload_image">';
			if ( isset($image ['link']) ){
				$_html .= '<img width="75" src="' . $image ['link'] . '" />';
			}
			if ($existing_images) {
				
				if ( isset($image ['link']) && in_array ( $image ['link'], $existing_images )) {
					$checked = 'checked="checked"';
				} else {
					$checked = '';
				}
			}
			
			// for bigger view
			if ( isset($image ['link']) ){
				$_html .= '<div style="display:none" id="pre_uploaded_image_' . $args ['id'] . '-' . $img_index . '"><img style="margin: 0 auto;display: block;" src="' . $image ['link'] . '" /></div>';
			}
			$_html .= '<div class="input_image">';
			if ($args['multiple-allowed'] == 'on') {
				$_html .= '<input type="checkbox" data-price="' . $image ['price'] . '" data-title="' . stripslashes ( $image ['title'] ) . '" name="' . $args ['name'] . '[]" value="' . $image ['link'] . '" ' . $checked . ' />';
			} else {
				$_html .= '<input type="radio" data-price="' . $image ['price'] . '" data-title="' . stripslashes ( $image ['title'] ) . '" name="' . $args ['name'] . '" value="' . $image ['link'] . '" ' . $checked . ' />';
			}
			
			// image big view
			$price = '';
			/*if (function_exists ( woocommerce_price ))
				$price = woocommerce_price ( $image ['price'] );
			else*/
				$price = $image ['price'];
			
			$_html .= '<a href="#TB_inline?width=' . $popup_width . '&height=' . $popup_height . '&inlineId=pre_uploaded_image_' . $args ['id'] . '-' . $img_index . '" class="thickbox" title="' . $image ['title'] . '"><img width="15" src="' . $this->plugin_meta ['url'] . '/images/zoom.png" /></a>';
			$_html .= '<div class="p_u_i_name">' . stripslashes ( $image ['title'] ) . ' ' . $price . '</div>';
			$_html .= '</div>'; // input_image
			
			$_html .= '</div>';
			
			$img_index ++;
		}}
		
		$_html .= '<div style="clear:both"></div>'; // container_buttons
		
		$_html .= '</div>'; // container_buttons
		
		echo $_html;
		
		$this->get_input_js ( $args );
	}
	
	/*
	 * following function is rendering JS needed for input
	 */
	function get_input_js($args) {
		?>

<script type="text/javascript">	
					<!--
					jQuery(function($){
	
						
					});
					
					//--></script>
<?php
	}
}