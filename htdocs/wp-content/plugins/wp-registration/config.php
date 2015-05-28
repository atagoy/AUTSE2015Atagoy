<?php
/*
 * this file contains pluing meta information and then shared between pluging and admin classes [1] TODO: change this meta as plugin needs
 */
function get_plugin_meta_wpregistration() {
	
		$plugin_meta = array (
			'name' => 'WP Registration',
			'shortname' => 'nm_wpregistration',
			'path'		=> untrailingslashit(plugin_dir_path( __FILE__ )),
			'url'		=> untrailingslashit(plugin_dir_url( __FILE__ )),
			'db_version'=> 1.0,
			'logo' 		=> untrailingslashit(plugin_dir_url( __FILE__ )) . '/images/logo.png',
			'men_position' => 78,
			'default_fields' => array (
					'0' => array (
							'type' => 'text',
							'title' => 'User Name',
							'data_name' => 'wp_registration_login',
							'description' => '',
							'error_message' => '',
							'required' => 'on',
							'class' => '',
							'width' => '' 
					),
					'1' => array (
							'type' => 'text',
							'title' => 'First Name',
							'data_name' => 'wp_registration_first_name',
							'description' => '',
							'error_message' => '',
							'required' => 'on',
							'class' => '',
							'width' => '' 
					),
					'2' => array (
							'type' => 'text',
							'title' => 'Last Name',
							'data_name' => 'wp_registration_last_name',
							'description' => '',
							'error_message' => '',
							'required' => 'on',
							'class' => '',
							'width' => '' 
					),
					'3' => array (
							'type' => 'email',
							'title' => 'User Email',
							'data_name' => 'wp_registration_email',
							'description' => '',
							'error_message' => '',
							'required' => 'on',
							'class' => '',
							'width' => '' 
					) 
			) 
	);
	
	// print_r($plugin_meta);
	
	return $plugin_meta;
}
function wpregistration_pa($arr) {
	echo '<pre>';
	print_r ( $arr );
	echo '</pre>';
}
