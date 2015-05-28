<?php
/*
 * Followig class handling all inputs control and their 
 * dependencies. Do not make changes in code
 * Create on: 9 November, 2013 
 */

class NM_Inputs_wpregisration{
	

	/*
	 * this var is pouplated with current plugin meta 
	 */
	var $plugin_meta;
	
	
	/*
	 * this var contains the scripts info 
	 * requested by input
	 */
	var $input_scripts;
	
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @param 
	 */
	public function __construct() {
		
		$this -> plugin_meta = get_plugin_meta_wpregistration();
	}
	
	/*
	 * returning relevant input object
	 */
	function get_input($type){
		
		$class_name 	= 'NM_' . ucfirst($type);
		$file_name		= 'input.' . $type . '.php';
				
		if (! class_exists ( $class_name )) {
			$_inputs = $this -> plugin_meta ['path'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'inputs' . DIRECTORY_SEPARATOR . $file_name;
			if (file_exists ( $_inputs ))
				include_once ($_inputs);
			else
				die ( 'Reen, Reen, BUMPs! not found ' . $_inputs );
		}
		
		return new $class_name();
	}
	
	/*
	 * loading scripts needed by input control
	 */
	function load_input_scripts(){
		
		if($this -> input_scripts['shipped']){
			foreach($this -> input_scripts['shipped'] as $handler){
				wp_enqueue_script($handler);
			}
		}
		
		//front end scripts
		if( isset( $this -> input_scripts['custom'] ) && $this -> input_scripts['custom']){
			foreach( $this -> input_scripts['custom'] as $scripts => $script ){
				
				//checking if it is style
				if( $script['type'] == 'js'){
					wp_enqueue_script($this->plugin_meta['shortname'].'-'.$script['script_name'], $this->plugin_meta['url'].$script['script_source'], $script['depends'], '3.0', $script['in_footer']);
				}else{
					wp_enqueue_style($this->plugin_meta['shortname'].'-'.$script['script_name'], $this->plugin_meta['url'].$script['script_source']);
				}
			}
		}
	}
	
	
	/*
	 * check if browser is ie
	 */
	function if_browser_is_ie()
	{
		//print_r($_SERVER['HTTP_USER_AGENT']);
		
		if (isset($_SERVER['HTTP_USER_AGENT']) &&
		(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') == false) || strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') == false)
			return true;
		else
			return false;
	}
	
	/*
	 * get current page url with query string
	 */
	function current_page_url() {
		$page_url = 'http';
		if( isset($_SERVER["HTTPS"]) ) {
			if ($_SERVER["HTTPS"] == "on") {$page_url .= "s";}
		}
		$page_url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $page_url;
	}
	
	
}