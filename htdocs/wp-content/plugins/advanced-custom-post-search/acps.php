<?php
/*
* Plugin Name: Advanced Custom Post Search
* Plugin URI: http://www.creare.co.uk
* Description: A useful plugin for creating advanced post searches for custom post types & taxonomies.
* Version: 1.2.4
* Author: Shane Welland
* License: GPL2
*/

//Controllers
include_once('core/controllers/search_forms.php');
include_once('core/controllers/search_form.php');

//Core functions
include_once('core/acps_core_functions.php');

//Shortcodes
include_once('core/shortcodes/acps_shortcodes.php');

//Widgets
include_once('core/widgets/acps_widgets.php');

//Template
include_once('core/acps_results_template.php');

//Acps
class acps
{
	
	//Declare empty settings variable
	var $settings;
	
	//Setup blank body class array
	private $_body_classes = array();
	
	//Acps construct
	function __construct()
	{
		
		//Set up helper filters for directory and path
		add_filter('acps/helpers/get_path', array($this, 'helpers_get_path'), 1, 1);
		add_filter('acps/helpers/get_dir', array($this, 'helpers_get_directory'), 1, 1);
		
		//Create settings
		$this->settings = array(
			'path' => apply_filters('acps/helpers/get_path', __FILE__),
			'dir' => apply_filters('acps/helpers/get_dir', __FILE__),
			'template_path' => trailingslashit(get_stylesheet_directory()),
			'hook' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ),
			'version' => '1.2.4',
			'upgrade_version' => '1.2.4'
		);
		
		//Register Search Page on activation
		register_activation_hook( __FILE__, array($this, 'acps_activate') );
		
		//Actions
		add_action('init', array($this, 'init'), 1 );
		add_action('acps/save_post', array($this, 'save_post'), 10 );
		add_action( 'template_redirect', 'acps_results_template_redirect', 1 );
		
		//Filters
		add_filter('acps/get_settings', array($this, 'get_settings'), 1, 1);
		add_filter('acps/get_meta_data', array($this, 'acps_get_meta_data'), 1, 1);
		
		//If is_admin then setup menu
		if( is_admin() )
		{
			//Create menu item
			add_action('admin_menu', array($this,'admin_menu'));
			
			//Admin head styles
			add_action('admin_head', array($this,'admin_head'));
			
			//Include settings page
			include_once('core/controllers/acps_settings_page.php');
				
		}	
		return true;
		
	}
	
	//Initialise function
	function init()
	{
		//acps post type labels
		$labels = array(
				'name' => __( 'Post&nbsp;Search Forms', 'acps' ),
				'singular_name' => __( 'Advanced Post Search', 'acps' ),
				'add_new' => __( 'Add New' , 'acps' ),
				'add_new_item' => __( 'Add New Form' , 'acps' ),
				'edit_item' =>  __( 'Edit Form' , 'acps' ),
				'new_item' => __( 'New Form' , 'acps' ),
				'view_item' => __('View Form', 'acps'),
				'search_items' => __('Search Forms', 'acps'),
				'not_found' =>  __('No Forms found', 'acps'),
				'not_found_in_trash' => __('No Forms found in Trash', 'acps'),
			);
		
		//Register acps post type	
		register_post_type('acps', array(
				'labels' => $labels,
				'public' => false,
				'show_ui' => true,
				'_builtin' =>  false,
				'capability_type' => 'page',
				'hierarchical' => true,
				'rewrite' => false,
				'query_var' => "acps",
				'supports' => array(
					'title',
				),
				'show_in_menu'	=> false,
			));
		
		//Set up admin scripts in arrays for looped register
		$scripts = array();
		
		//Add admin ajax scripts
		$scripts[] = array(
			'handle' => 'acps-admin-ajax',
			'src' => $this->settings['dir'] . 'js/admin-ajax.js',
			'deps' => ''
		);
		
		//Add chosen dropdown scripts
		$scripts[] = array(
			'handle' => 'acps-chzn-scripts',
			'src' => $this->settings['dir'] . 'js/chzn.js',
			'deps' => ''
		);

		//Loop through scripts array to register them (enqueue separately)
		foreach( $scripts as $script )
		{
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->settings['version'] );
		}
				
		//Set up admin styles in array incase of additional sheets
		$styles = array(
			'acps-admin-styles' => $this->settings['dir'] . 'css/acps-admin.css',
			'acps-chzn-styles' => $this->settings['dir'] . 'css/chzn.css',
			'acps-frontend-styles' => $this->settings['dir'] . 'css/acps-frontend.css',
		);
		
		//Loop through styles to register styles (enqueue separately)
		foreach( $styles as $key => $value )
		{
			wp_register_style( $key, $value, false, $this->settings['version'] ); 
		}
		
		if( is_admin() )
		{
			//Change post update messages
			add_filter('post_updated_messages', array($this, 'acps_post_update_messages'));
		}
		
	}
	
	//Admin Menu
	function admin_menu()
	{
		//Create utility page
		add_utility_page(__("Advanced Post Search",'acps'), __("Advanced Post Search",'acps'), 'manage_options', 'edit.php?post_type=acps');
	}
	
	//Path Helper Function
	function helpers_get_path( $file )
    {
		//Add the trailing slash
        return trailingslashit( dirname( $file ) );
    }
	
	//Directory Helper Function
	function helpers_get_directory( $file )
    {
		//Set 'unclean' directory
        $directory = trailingslashit( dirname( $file ) );
        
        //Clean up backslashes for Win32 installs
        $directory = str_replace( '\\' ,'/', $directory ); 
        
        //Clean up plugin directory
        $wp_plugins_dir = str_replace( '\\' ,'/', WP_PLUGIN_DIR ); 
        
		//Replace string with plugins_url directory
		$directory = str_replace( $wp_plugins_dir, plugins_url(), $directory );
		
		//Return clean directory
        return $directory;
    }
	
	//Get_settings function
	function get_settings( $info )
	{
		//Setup variable
		$setting_request = false;
			
		//Check if seting exists
		if( isset( $this->settings[ $info ] ) )
		{
			//Save settings in request variable
			$setting_request = $this->settings[ $info ];
		}
		//Check if setting is 'all'
		else if( $info == 'all' )
		{
			//Save ALL settings in request variable instead
			$setting_request = $this->settings;
		}
		
		//Return requested settings
		return $setting_request;
	}
	
	//Get Data function
	function acps_get_meta_data( $post_id )
	{
		//Get post meta data
		$acps_post_meta = get_post_meta( $post_id );
		
		//Return if empty
		if( empty($acps_post_meta) )
		{
			return;
		}
		
		//Setup blank fields variable
		$acps_fields = false;
		
		//Format data
		foreach( $acps_post_meta as $k => $v )
		{
			if( strstr($k, 'acps_') )
			{
				@unserialize($v[0])!=false ? $acps_fields[$k] = unserialize($v[0]) : $acps_fields[$k] = $v[0];
			}
		}
		return $acps_fields;
		
	}
	
	function admin_head()
	{
	?>
    
    <style>
	#adminmenu #toplevel_page_edit-post_type-acps div.wp-menu-image:before {
	  content: '\f179';
	}
	</style>
    	
	<?php	
	}
	
	//Run when plugin is actviated
	function acps_activate()
	{
		
		include_once( 'admin/acps_admin_install.php' );
		do_acps_install();

    }
	
	//Change publishing/updating/deleting messages to avoid 404 pages
	function acps_post_update_messages( $messages )
	{
		global $post, $post_ID;
	
		$messages['acps'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Search Form updated.', 'acps'),
			2 => __('Search Form updated.', 'acps'),
			3 => __('Search Form deleted.', 'acps'),
			4 => __('Search Form updated.', 'acps'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Search Form restored to revision from %s', 'acps'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Search Form published.', 'acps'),
			7 => __('Search Form saved.', 'acps'),
			8 => __('Search Form submitted.', 'acps'),
			9 => __('Search Form scheduled for.', 'acps'),
			10 => __('Search Form draft updated.', 'acps'),
		);
	
		return $messages;
	}
	
}
$acps = new acps();