<?php

//ACPS template

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//Results Class
class acps_results
{
	
	//Setup blank variables
	var $settings;
	var $acps_data;
	var $acps_post_type;
	var $acps_tax_data;
	var $acps_args;
	var $acps_style_options;
	var $acps_keyword_input;
	var $acps_form_id;
	
	//Get data send through url (form data)
	function acps_get_data()
	{
		//Check if is an acps results page
		if( !is_acps() )
		return;
		
		//Get style options
		$this->acps_style_options = get_option( 'acps_options' );
		
		//Get all data
		$this->acps_data = $_POST;
		
		//Check that $_POST data exists
		if( !empty( $this->acps_data ) )
		{
			//Loop through data to save in a class variable
			foreach( $this->acps_data as $k => $v )
			{
				if( !empty($v) )
				{
					//Get post type to save separately
					if( $k == 'acps_post_type' )
					{
					
						$this->acps_post_type = $v;
						
					}
					//Get text input and save separately
					elseif( $k == 'keywords' )
					{
						$this->acps_keyword_input = $v;
					}
					//Get form ID for shortcode
					elseif( $k == 'acps_form_id' )
					{
						$this->acps_form_id = $v;
					}
					//Save rest of data for tax query
					else
					{
						
					$this->acps_tax_data[$k] = $v;
					
					}
				
				}
			
			}
		
		}
		
	}
	
	//Acps_get_args function to create WP_Query( $acps_args )
	function acps_get_args()
	{
		
		//Declare settings using filter
		$this->settings = apply_filters('acps/get_settings', 'all');
		
		//Count all of the taxonomy keys
		$acps_term_count = count($this->acps_tax_data);
		$acps_tax_args = array();
		$acps_term_settings = false;
		$acps_current_term_setting = 1;
		
		if( is_array($this->acps_tax_data) )
		{
			
			foreach($this->acps_tax_data as $k => $v)
			{
				//Make a tax query array for each array member
				$acps_term_settings[$acps_current_term_setting] = array(
					'taxonomy' => $k,
					'field' => 'slug',
					'terms' => $v
				);
				
				//Push the tax querys into a single array for the WP_Query args
				array_push($acps_tax_args,$acps_term_settings[$acps_current_term_setting]);
				
				//Add one to current term setting for main taxonomy array
				$acps_current_term_setting++;
			}
		
		}
		else
		{
			$acps_tax_args=false;	
		}
		
		//Save all args to main variable
		$this->acps_args = apply_filters('acps/query_args', array(
		  'post_type' => $this->acps_post_type,
		  's' => $this->acps_keyword_input,
		  'tax_query' => $acps_tax_args,
		));
		
		//Setup wrapper
		echo '<div class="acps_results">';
		echo '<section class="acps_results_list">';
		
		//Check template directory for loop file
		$file = $this->settings['template_path'].'acps/templates/acps-results_loop.php';

		//Setup boolean
		$file_exists = is_file($file);
		
		//Load appropriate template
		if( $file_exists )
		{
			
			include( $this->settings['template_path'].'acps/templates/acps-results_loop.php' );

		}
		elseif ( !$file_exists && is_file($this->settings['template_path'].'acps-results_loop.php' ) )
		{
			
			include( $this->settings['template_path'].'acps-results_loop.php' );
			
		}
		else
		{
			
			include( $this->settings['path'].'templates/acps-results_loop.php' );
			
		}
		
		echo '</section>';
		echo '</div>';
		
	}
	
}