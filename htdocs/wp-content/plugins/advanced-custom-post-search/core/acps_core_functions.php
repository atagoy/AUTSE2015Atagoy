<?php
//Acps core functions

//Checks whether its a frontend acps page
function is_acps() {

	return ( is_results() ) ? true : false;
	
}

//Returns true or false depending on $_POST data
function is_results()
{
	
	$acps_results = $_POST;
	
	if( !empty($acps_results) ) :
	
		$is_results = $acps_results['acps_post_type'] ? true : false;	
		return $is_results;
		
	endif;
	
}

//Get template redirect
function acps_results_template_redirect()
{

	//Global post data
	global $post;
	
	//Grab results
	$acps_options = get_option('acps_options');
	
	//Get results page
	$acps_results_page = $acps_options['acps_results_page'];
	
	//Setup template file variables
	$template = '';
	$slug = 'acps';
	$name = 'results';
	
	//Get settings
	$settings = apply_filters('acps/get_settings', 'all');
	
	//Return if post doesn't exist
	if( !$post )
		return;
	
	//If we aren't on the advanced search page, don't redirect
	if( $post->ID != $acps_results_page)
		return;	
	
	//Try to locate template in [template_path]/acps/template directory
	$template = is_file("{$settings['template_path']}acps/templates/{$slug}-{$name}.php");

	//If template exists, set the path
	if( $template )
		$template = "{$settings['template_path']}acps/templates/{$slug}-{$name}.php";
	
	//If template doesn't exist then look again in the theme root
	if( !$template )
		$template = locate_template( array ( "{$slug}-{$name}.php", "{$settings['template_path']}{$slug}-{$name}.php" ) );
	
	//If none of the above are found, load the plugin default	
	if( !$template )
		$template = $settings['path'].'templates/acps-results.php';
	
	//Setup results body class	
	add_filter('body_class','acps_body_class');
	
	//Load the located template
	load_template( $template );
	
	//Stop any wordpress header/ footer from running
	exit();
	
}

//Add body class to results page
function acps_body_class($classes)
{
	
	//Add to existing classes
	$classes[] = 'acps_results_archive';
	$classes[] = 'search';
	$classes[] = 'search_results';
	
	//Return the classes array
	return $classes;
	
}

//Get the template 'template part'
function acps_get_template_part( $name )
{

	//Switch for future improvement
	switch( $name )
	{
		//If on results page, load results template using class
		case 'results':
		
			//Start results
			$acps_results = new acps_results();
			
			//Find all $_POST data
			$acps_results->acps_get_data();
			
			//Get WP_Query 'args'
			$acps_results->acps_get_args();
		
		break;
	}

}