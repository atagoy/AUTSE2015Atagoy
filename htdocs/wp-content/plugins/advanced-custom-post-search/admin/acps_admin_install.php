<?php
//ACPS Install

function do_acps_install()
{
	
	//Setup default options
	acps_default_options();
	
	//Try to create new page for results
	acps_create_page( esc_sql( _x( 'advanced-search', 'page_slug', 'acps' ) ), 'acps_options', __( 'Advanced Search', 'acps' ), '[acps_results]' );
	
}

function acps_default_options() {

	$acps_settings_exist = get_option('acps_options');
	
	//Update default settings
	if( !$acps_settings_exist ):
	
		//Setup blank data for activation
		$acps_default_options = array(
			'acps_results_page' => -1,
			'acps_form_styles' => ''
		);
	
		//Save the blank data
		update_option( 'acps_options', $acps_default_options );
	
	endif;
		
}

//Create page function
function acps_create_page( $slug, $option, $page_title = '', $page_content = '' ) {
	
	//Get option (currently only acps_options though)
	$acps_options = get_option( $option );
	
	//Get page ID option
	$acps_page_id = $acps_options['acps_results_page'];
	
	//Check the page doesn't exist
	if ( $acps_page_id > 0 && get_post( $acps_page_id ) )
		return;

	//Create the page
	$page_data = array(
        'post_status' 		=> 'publish',
        'post_type' 		=> 'page',
        'post_author' 		=> 1,
        'post_name' 		=> $slug,
        'post_title' 		=> $page_title,
        'post_content' 		=> $page_content,
        'post_parent' 		=> 0,
        'comment_status' 	=> 'closed'
    );
    $page_id = wp_insert_post( $page_data );
	
	$acps_default_options = array(
		'acps_results_page' => -1,
		'acps_form_styles' => ''
	);
	
	$acps_default_options = array(
	'acps_form_styles' => $acps_options['acps_form_styles'],
	'acps_results_page' => $page_id
	);
	
	update_option('acps_options', $acps_default_options);
	
}