<?php 

/*
Plugin Name: N-Media WP Member Registration
Plugin URI: http://www.najeebmedia.com
Description: This plugin allow users to register, login and reset password using ajax based forms. Admin can attach unlimited user meta fields. User can update their profile using without going into admin dashboard.
Version: 1.4
Author: Najeeb Ahmad
Text Domain: wp-registration
Author URI: http://www.najeebmedia.com/
*/


/*
 * Lets start from here
*/

/*
 * loading plugin config file
 */
$_config = dirname(__FILE__).'/config.php';
if( file_exists($_config))
	include_once($_config);
else
	die('Reen, Reen, BUMP! not found '.$_config);


/* ======= the plugin main class =========== */
$_plugin = dirname(__FILE__).'/classes/plugin.class.php';
if( file_exists($_plugin))
	include_once($_plugin);
else
	die('Reen, Reen, BUMP! not found '.$_plugin);

/*
 * [1]
 * TODO: just replace class name with your plugin
 */
$nm_wpregistration = NM_WPRegistration::get_instance();
NM_WPRegistration::init();


if( is_admin() ){

	$_admin = dirname(__FILE__).'/classes/admin.class.php';
	if( file_exists($_admin))
		include_once($_admin );
	else
		die('file not found! '.$_admin);

	$wpregistration_admin = new NM_WPRegistration_Admin();
}

/*
 * activation/install the plugin data
*/
register_activation_hook( __FILE__, array('NM_WPRegistration', 'activate_plugin'));
register_deactivation_hook( __FILE__, array('NM_WPRegistration', 'deactivate_plugin'));


