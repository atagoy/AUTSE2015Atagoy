<?php
/*
 * Followig class handling text input control and their
* dependencies. Do not make changes in code
* Create on: 9 November, 2013
*/

class NM_Section extends NM_Inputs_wpregisration{
	
	/*
	 * input control settings
	 */
	var $title, $desc, $settings;
	
	
	/*
	 * check if section is started
	 */
	var $is_section_stared;
	/*
	 * this var is pouplated with current plugin meta
	*/
	var $plugin_meta;
	
	function __construct(){
		
		$this -> plugin_meta = get_plugin_meta_wpregistration();
		
		$this -> title 		= __ ( 'Section', 'wp-registration' );
		$this -> desc		= __ ( 'Form section', 'wp-registration' );
		$this -> settings	= self::get_settings();
		
	}
	
	
	
	
	private function get_settings(){
		
		return array (
						'title' => array (
								'type' => 'text',
								'title' => __ ( 'Title', 'wp-registration' ),
								'desc' => __ ( 'It will as section heading wrapped in h2', 'wp-registration' ) 
						),
						'description' => array (
								'type' => 'text',
								'title' => __ ( 'Description', 'wp-registration' ),
								'desc' => __ ( 'Type description, it will be diplay under section heading.', 'wp-registration' ) 
						)
				);
	
	}
	
	
	/*
	 * @params: args
	*/
	function render_input($args, $content=""){
		
		
		$_html =  '<section id="'.$args['id'].'">';
		$_html .= '<div style="clear: both"></div>';
		
		$_html .= '<header class="filemanager-section-header">';
		$_html .= '<h2>'. stripslashes( $args['title'] ).'</h2>';
		$_html .= '<p id="box-'.$name.'">'. stripslashes( $args['description']).'</p>';
		$_html .= '</header>';
		
		$_html .= '<div style="clear: both"></div>';
		
		echo $_html;
	}
}