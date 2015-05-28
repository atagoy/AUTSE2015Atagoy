<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
* 
* 
* ::::::::::::::::::::::: CREDIT :::::::::::::::::::::::
* Copyright (c) 2007-2013 Josh Bush (digitalbush.com)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

class NM_Masked extends NM_Inputs_wpregisration{
	
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
		
		$this -> title 		= __ ( 'Masked Input', 'wp-registration' );
		$this -> desc		= __ ( 'masked input', 'wp-registration' );
		$this -> settings	= self::get_settings();
		
		
		$this -> input_scripts = array(	'shipped'		=> array(''),
		
										'custom'		=> array(
												array (
														'script_name' 	=> 'mask_script',
														'script_source' => '/js/mask/jquery.maskedinput.min.js',
														'localized' 	=> false,
														'type' 			=> 'js',
														'depends'		=> array('jquery'),
														'in_footer'		=> '',
												),
													
										)
								);
		
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
				
		'mask' => array (
				'type' => 'text',
				'title' => __ ( 'Input Mask', 'wp-registration' ),
				'desc' => __ ( 'Input mask e.g:<br>a - Represents an alpha character (A-Z,a-z)<br>9 - Represents a numeric character (0-9)<br>* - Represents an alphanumeric character (A-Z,a-z,0-9)', 'wp-registration' )
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
		
		// mask as placeholder
		$_html .= 'placeholder="' . stripslashes($args['data-mask']) . '"';
		
		if($content)
			$_html .= 'value="' . stripslashes($content) . '"';
		
		$_html .= ' />';
		
		echo $_html;
		
		$this -> get_input_js($args);
	}
	
	
	/*
	 * following function is rendering JS needed for input
	*/
	function get_input_js($args){
	
		$input_mask =  $args['data-mask'];
		?>
	
			<script type="text/javascript">	
			<!--
			jQuery("#<?php echo $args['id'];?>").mask("<?php echo $input_mask;?>",{completed:function(){
				this.attr('data-ismask', 'yes');
				}
			});
			//--></script>
			<?php
	}
}