<?php         
/*
Plugin Name: CSV 2 POST
Version: 8.1.37
Plugin URI: http://www.webtechglobal.co.uk/csv-2-post
Description: CSV 2 POST data importer for WordPress by Ryan Bayne @WebTechGlobal.
Author: WebTechGlobal
Author URI: http://www.webtechglobal.co.uk/
Last Updated: January 2015
Text Domain: csv2post
Domain Path: /languages

GPL v3 

This program is free software downloaded from WordPress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.
*/           
  
// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'Direct script access is not allowed!' );

// exit early if CSV 2 POST doesn't have to be loaded
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) // Login screen
    || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
    || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
    return;
}
              
// package variables
$csv2post_filesversion = '8.1.37';# to be removed, version is now in the CSV2POST() class 
$c2p_debug_mode = false;# to be phased out, going to use environment variables (both WP and php.ini instead)

// go into dev mode if on test installation               
if( strstr( ABSPATH, 'OFFcsv2post' ) ){
    $c2p_debug_mode = true;     
}               

// avoid error output when...              
if ( ( 'wp-login.php' === basename( $_SERVER['SCRIPT_FILENAME'] ) ) // Login screen
        || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
        || ( defined( 'DOING_CRON' ) && DOING_CRON )
        || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    $c2p_debug_mode = false;
}                   

// define constants                              
if(!defined( "WTG_CSV2POST_NAME") ){define( "WTG_CSV2POST_NAME", 'CSV 2 POST' );} 
if(!defined( "WTG_CSV2POST__FILE__") ){define( "WTG_CSV2POST__FILE__", __FILE__);}
if(!defined( "WTG_CSV2POST_BASENAME") ){define( "WTG_CSV2POST_BASENAME",plugin_basename( WTG_CSV2POST__FILE__ ) );}
if(!defined( "WTG_CSV2POST_ABSPATH") ){define( "WTG_CSV2POST_ABSPATH", plugin_dir_path( __FILE__) );}//C:\AppServ\www\wordpress-testing\wtgplugintemplate\wp-content\plugins\wtgplugintemplate/  
if(!defined( "WTG_CSV2POST_PHPVERSIONMINIMUM") ){define( "WTG_CSV2POST_PHPVERSIONMINIMUM", '5.3.0' );}// The minimum php version that will allow the plugin to work                                
if(!defined( "WTG_CSV2POST_IMAGES_URL") ){define( "WTG_CSV2POST_IMAGES_URL",plugins_url( 'images/' , __FILE__ ) );}
 
// require main class
require_once( WTG_CSV2POST_ABSPATH . 'classes/class-csv2post.php' );

// call key methods for this package, remove each method that is not required when building a new plugin
$CSV2POST = new CSV2POST();
$CSV2POST->custom_post_types();

// localization
function csv2post_textdomain() {
    load_plugin_textdomain( 'csv2post', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'csv2post_textdomain' );                                                                                                       
?>