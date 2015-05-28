<?php
/*
 * Followig class handling date input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Date extends NM_Inputs_wpregisration{
	
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
		
		$this -> title 		= __ ( 'Date Input', 'wp-registration' );
		$this -> desc		= __ ( 'regular date input', 'wp-registration' );
		$this -> settings	= self::get_settings();
		
		$this -> input_scripts = array('shipped'		=> array('jquery-ui-datepicker'),
										'custom'		=> NULL);
		
		add_action ( 'wp_enqueue_scripts', array ($this, 'load_input_scripts'));
		
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
		'date_formats' => array (
				'type' => 'select',
				'title' => __ ( 'Date formats', 'wp-registration' ),
				'desc' => __ ( 'Select date format.', 'wp-registration' ),
				'options' => array (
						'mm/dd/yy' => 'Default - mm/dd/yy',
						'yy-mm-dd' => 'ISO 8601 - yy-mm-dd',
						'd M, y' => 'Short - d M, y',
						'd MM, y' => 'Medium - d MM, y',
						'DD, d MM, yy' => 'Full - DD, d MM, yy',
						'\'day\' d \'of\' MM \'in the year\' yy' => 'With text - \'day\' d \'of\' MM \'in the year\' yy' 
				) 
		),
		
		);
	}
	
	
	/*
	 * @params: args
	*/
	function render_input($args, $content=""){
		
		$_html = '<input type="text" ';
		
		foreach ($args as $attr => $value){
			
			$_html .= $attr.'="'.stripslashes( $value ).'"';
		}
		
		if($content)
			$_html .= 'value="' . stripslashes($content	) . '"';
		
		$_html .= ' />';
		
		echo $_html;
		
		$this -> get_input_js($args);
	}
	
	/*
	 * following function is rendering JS needed for input
	*/
	function get_input_js($args){
	?>
		
				<script type="text/javascript">	
				<!--
				jQuery(function($){

					$("#<?php echo $args['id'];?>").datepicker({ 	changeMonth: true,
						changeYear: true,
						dateFormat: $("#<?php echo $args['id'];?>").attr('data-format')
						});
						
				});
				
				//--></script>
				<?php
		}
}