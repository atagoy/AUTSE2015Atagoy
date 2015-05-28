<?php
/*
 * Followig class handling email input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Email extends NM_Inputs_wpregisration{
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings;
	
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;
	
	function __construct(){
		
		$this -> plugin_meta = get_plugin_meta_wpregistration();
		
		$this -> title 		= __ ( 'Email Input', 'wp-registration' );
		$this -> desc		= __ ( 'regular email input', 'wp-registration' );
		$this -> settings	= self::get_settings();
		
	}
	
	
	
	
	private function get_settings(){
		
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
		
		'required' => array (
				'type' => 'checkbox',
				'title' => __ ( 'Required', 'wp-registration' ),
				'desc' => __ ( 'Select this if it must be required.', 'wp-registration' ) 
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
		
		'send_email' => array (
				'type' => 'checkbox',
				'title' => __ ( 'Send email', 'wp-registration' ),
				'desc' => __ ( 'Select this if you want user get this form.', 'wp-registration' ) 
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
		),
		);
	}
	
	
	/*
	 * @params: args
	*/
	function render_input($args, $content=""){
		
		$_html = '<input type="email" ';
		
		foreach ($args as $attr => $value){
			
			$_html .= $attr.'="'.stripslashes( $value ).'"';
		}
		
		if($content)
			$_html .= 'value="' . stripslashes($content	) . '"';
		
		$_html .= ' />';
		
		echo $_html;
	}
}