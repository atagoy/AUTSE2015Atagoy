<?php  
/** 
 * The core WTG plugin class and other main class functions for CSV 2 POST WordPress plugin 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/** 
* Main class - add methods that will likely be used in all WTG plugins, use wpmain.php for those specific to the build
* 
* @since 8.0.0
* 
* @author Ryan Bayne 
*/                                                 
class CSV2POST {
    
    /**
     * Page hooks (i.e. names) WordPress uses for the CSV2POST admin screens,
     * populated in add_admin_menu_entry()
     *
     * @since 8.1.3
     *
     * @var array
     */
    protected $page_hooks = array();
    
    /**
     * CSV2POST version
     *
     * Increases everytime the plugin changes
     *
     * @since 8.1.3
     *
     * @const string
     */
    const version = '8.2.0';
    
    /**
     * CSV2POST version
     *
     * Increases on major releases
     *
     * @since 8.1.3
     *
     * @const string
     */    
    const shortversion = '8';  
    
    protected
        $filters = array(),
        $actions = array(),    
                                
        // add_action() controller
        // Format: array( event | function in this class(in an array if optional arguments are needed) | loading circumstances)
        // Other class requiring WordPress hooks start here also, with a method in this main class that calls one or more methods in one or many classes
        // create a method in this class for each hook required plugin wide
        $plugin_actions = array( 
            
            // plugins main menu (not in-page tab menu)
            array( 'admin_menu',                     'admin_menu',                                             'all' ),
            
            // WTG own security gets applied to all POST and GET requests
            array( 'admin_init',                     'process_admin_POST_GET',                                 'all' ),
            
            // this method adds callbacks for specific admin pages, add callbacks to the method
            array( 'admin_init',                     'add_adminpage_actions',                                  'all' ), 
            
            // automatically detect new csv files in data source directories   
            array( 'init',                           'detect_new_files',                                       'all' ), 
            
            // part of WTG schedule system - decides what event is due then calls applicable method
            array( 'init',                           'event_check',                                            'all' ),
            
            // TBC - not in use yet
            array( 'eventcheckwpcron',               'eventcheckwpcron',                                       'all' ),
            
            // TBC - not in use yet
            array( 'event_check_servercron',         'event_check_servercron',                                 'all' ),
            
            // adds widgets to the dashboard, WTG plugins can add the forms for any view to the dashboard
            array( 'wp_dashboard_setup',             'add_dashboard_widgets',                                  'all' ),
            
            // during the posts loop, checks for CSV 2 POST created posts and updates the post when applicable
            array( 'the_posts',                      'systematicpostupdate',                                   'systematicpostupdating' ),
            
            // adds script for media button
            array( 'admin_footer',                   'pluginmediabutton_popup',                                'pluginscreens' ),
            
            // adds the HTML media button 
            array( 'media_buttons_context',          'pluginmediabutton_button',                               'pluginscreens' ),
            
            array( 'admin_enqueue_scripts',          'plugin_admin_enqueue_scripts',                           'pluginscreens' ),
            array( 'init',                           'plugin_admin_register_styles',                           'pluginscreens' ),
            array( 'admin_print_styles',             'plugin_admin_print_styles',                              'pluginscreens' ),
            array( 'admin_notices',                  'admin_notices',                                          'admin_notices' ),
            array( 'upgrader_process_complete',      'complete_plugin_update',                                 'adminpages' ),
        ),        
                              
        $plugin_filters = array(
            /*
                Examples - last value are the sections the filter apply to
                    array( 'plugin_row_meta',                     array( 'examplefunction1', 10, 2),         'all' ),
                    array( 'page_link',                             array( 'examplefunction2', 10, 2),             'downloads' ),
                    array( 'admin_footer_text',                     'examplefunction3',                         'monetization' ),
                    
            */
        );     
        
    /**
    * This class is being introduced gradually, we will move various lines and config functions from the main file to load here eventually
    */
    public function __construct() {
        global $csv2post_settings;

        self::debugmode(); 
                  
        // load class used at all times
        $this->DB = self::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = self::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->Install = self::load_class( 'C2P_Install', 'class-install.php', 'classes' );
        $this->Files = self::load_class( 'C2P_Files', 'class-files.php', 'classes' );
  
        $csv2post_settings = self::adminsettings();
  
        $this->add_actions();
        $this->add_filters();

        if( is_admin() )
        {
            // admin globals 
            global $c2p_notice_array;
            
            $c2p_notice_array = array();// set notice array for storing new notices in (not persistent notices)
            
            // load class used from admin only                   
            $this->UI = self::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' );
            $this->Helparray = self::load_class( 'C2P_Help', 'class-help.php', 'classes' );
            $this->Tabmenu = self::load_class( "C2P_TabMenu", "class-pluginmenu.php", 'classes','pluginmenu' ); 
                     
        }            
      
        /**
        * load current project - new approach to begin reducing the number of times
        * project settings are queried
        * 
        * @since 8.1.3
        */
        $this->current_project_object = false;
        $this->current_project_settings = false;   
        if( isset( $this->settings['currentproject'] ) && $this->settings['currentproject'] !== false ){
            
            $this->current_project_object = self::get_project( $this->settings['currentproject'] ); 
            
            if( !$this->current_project_object ) {    
                $this->current_project_settings = false;
            } else {          
                $this->current_project_settings = maybe_unserialize( $this->current_project_object->projectsettings );
            }
        }
    }
    
    /**
    * register admin only .css must be done before printing styles
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.1
    */
    public function plugin_admin_register_styles() {
        wp_register_style( 'csv2post_css_notification',plugins_url( 'csv-2-post/css/notifications.css' ), array(), '1.0.0', 'screen' );
        wp_register_style( 'csv2post_css_admin',plugins_url( 'csv-2-post/css/admin.css' ), __FILE__);          
    }
    
    /**
    * print admin only .css - the css must be registered first
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function plugin_admin_print_styles() {
        wp_enqueue_style( 'csv2post_css_notification' );  
        wp_enqueue_style( 'csv2post_css_admin' );               
    }    
    
    /**
    * queues .js that is registered already
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function plugin_admin_enqueue_scripts() {
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );          
    }    

    /**
     * Enqueue a CSS file with ability to switch from .min for debug
     *
     * @since 8.1.3
     *
     * @param string $name Name of the CSS file, without extension(s)
     * @param array $dependencies List of names of CSS stylesheets that this stylesheet depends on, and which need to be included before this one
     */
    public function enqueue_style( $name, array $dependencies = array() ) {
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        $css_file = "css/{$name}{$suffix}.css";
        $css_url = plugins_url( $css_file, WTG_CSV2POST__FILE__ );
        wp_enqueue_style( "csv2post-{$name}", $css_url, $dependencies, CSV2POST::version );
    }
    
    /**
     * Enqueue a JavaScript file, can switch from .min for debug,
     * possibility with dependencies and extra information
     *
     * @since 8.1.3
     *
     * @param string $name Name of the JS file, without extension(s)
     * @param array $dependencies List of names of JS scripts that this script depends on, and which need to be included before this one
     * @param bool|array $localize_script (optional) An array with strings that gets transformed into a JS object and is added to the page before the script is included
     * @param bool $force_minified Always load the minified version, regardless of SCRIPT_DEBUG constant value
     */
    public function enqueue_script( $name, array $dependencies = array(), $localize_script = false, $force_minified = false ) {
        $suffix = ( ! $force_minified && defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        $js_file = "js/{$name}{$suffix}.js";
        $js_url = plugins_url( $js_file, WTG_CSV2POST__FILE__ );
        wp_enqueue_script( "csv2post-{$name}", $js_url, $dependencies, CSV2POST::version, true );
    }  
        
    /**
    * Create a new instance of the $class, which is stored in $file in the $folder subfolder
    * of the plugin's directory.
    * 
    * One bad thing about using this is suggestive code does not work on the object that is returned
    * making development a little more difficult. This behaviour is experienced in phpEd 
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.1
    *
    * @param string $class Name of the class
    * @param string $file Name of the PHP file with the class
    * @param string $folder Name of the folder with $class's $file
    * @param mixed $params (optional) Parameters that are passed to the constructor of $class
    * @return object Initialized instance of the class
    */
    public static function load_class( $class, $file, $folder, $params = null ) {
        /**
         * Filter name of the class that shall be loaded.
         *
         * @since 8.1.3
         *
         * @param string $class Name of the class that shall be loaded.
         */
        $class = apply_filters( 'csv2post_load_class_name', $class );
        if ( ! class_exists( $class ) ) {   
            self::load_file( $file, $folder );
        }
        
        // we can avoid creating a new object, we can use "new" after the load_class() line
        // that way functions in the lass are available in code suggestion
        if( is_array( $params ) && in_array( 'noreturn', $params ) ){
            return true;   
        }
        
        $the_class = new $class( $params );
        return $the_class;
    }
    
    /**
    * returns the C2P_WPMain class object already created in this CSV2POST class
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function class_wpmain() {
        return $this->wpmain;
    }

    /**
     * Load a file with require_once(), after running it through a filter
     *
     * @since 8.1.3
     *
     * @param string $file Name of the PHP file with the class
     * @param string $folder Name of the folder with $class's $file
     */
    public static function load_file( $file, $folder ) {   
        $full_path = WTG_CSV2POST_ABSPATH . $folder . '/' . $file;
        
        /**
         * Filter the full path of a file that shall be loaded.
         *
         * @since 8.1.3
         *
         * @param string $full_path Full path of the file that shall be loaded.
         * @param string $file      File name of the file that shall be loaded.
         * @param string $folder    Folder name of the file that shall be loaded.
         */
        $full_path = apply_filters( 'csv2post_load_file_full_path', $full_path, $file, $folder );
        if ( $full_path ) {   
            require_once $full_path;
        }
    }  
    
    /**  
     * Set up actions for each page
     *
     * @since 8.1.3
     */
    public function add_adminpage_actions() {
        // register callbacks to trigger load behavior for admin pages
        foreach ( $this->page_hooks as $page_hook ) {
            add_action( "load-{$page_hook}", array( $this, 'load_admin_page' ) );
        }
    }
        
    /**
     * Render the view that has been initialized in load_admin_page() (called by WordPress when the actual page content is needed)
     *
     * @since 8.1.3
     */
    public function show_admin_page() {   
        $this->view->render();
    }    
    
    /**
     * Create a new instance of the $view, which is stored in the "views" subfolder, and set it up with $data
     * 
     * Requires a main view file to be stored in the "views" folder, unlike the original view approach.
     * 
     * Do not move this to another file not even interface classes 
     *
     * @since 8.1.3
     * @uses load_class()
     *
     * @param string $view Name of the view to load
     * @param array $data (optional) Parameters/PHP variables that shall be available to the view
     * @return object Instance of the initialized view, already set up, just needs to be render()ed
     */
    public static function load_draggableboxes_view( $page_slug, array $data = array() ) {
        global $c2pm;
        
        // include the view class
        require_once( WTG_CSV2POST_ABSPATH . 'classes/class-view.php' );
        
        // make first letter uppercase for a better looking naming pattern
        $ucview = ucfirst( $page_slug );// this is page name 
        
        // get the file name using $page and $tab_number
        $dir = 'views';
        
        // include the view file and run the class in that file                                
        $the_view = self::load_class( "CSV2POST_{$ucview}_View", "{$page_slug}.php", $dir );
                       
        $the_view->setup( $page_slug , $data );
        
        return $the_view;
    }

    /**
     * Generate the complete nonce string, from the nonce base, the action and an item
     *
     * @since 8.1.3
     *
     * @param string $action Action for which the nonce is needed
     * @param string|bool $item (optional) Item for which the action will be performed, like "table"
     * @return string The resulting nonce string
     */
    public static function nonce( $action, $item = false ) {
        $nonce = "csv2post_{$action}";
        if ( $item ) {
            $nonce .= "_{$item}";
        }
        return $nonce;
    }
    
    /**
     * Begin render of admin screen
     * 1. determining the current action
     * 2. load necessary data for the view
     * 3. initialize the view
     * 
     * @uses load_draggableboxes_view() which includes class-view.php
     * 
     * @author Ryan Bayne
     * @package CSV 2 POST
     * @since 8.1.3
     * @version 1.0.1
     */
    public function load_admin_page() {
        // check if action is a supported action, and whether the user is allowed to access this screen
        /*
        if ( ! isset( $this->view_actions[ $action ] ) || ! current_user_can( $this->view_actions[ $action ]['required_cap'] ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'default' ) );
        }
        */
        
        // load tab menu class which contains help content array
        $CSV2POST_TabMenu = self::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        
        // call the menu_array
        $menu_array = $CSV2POST_TabMenu->menu_array();        

        // remove "csv2post_" from page
        $page = 'main';
        if( isset( $_GET['page'] ) && $_GET['page'] !== 'csv2post' ){    
            $page = substr( $_GET['page'], 9 );
        }

        // pre-define data for passing to views
        $data = array( 'datatest' => 'A value for testing' );

        // depending on page load extra data
        switch ( $page ) {
            case 'dataimport':
                break;           
            case 'updateplugin':
                break;
            case 'categories':
                break;
            case 'meta':
                break;
            case 'design':
                break;
            case 'postcreation':
                break;            

        }
          
        // prepare and initialize draggable panel view for prepared pages
        // if this method is not called the plugin uses the old view method
        $this->view = $this->load_draggableboxes_view( $page, $data );
    }   
                   
    protected function add_actions() {          
        foreach( $this->plugin_actions as $actionArray ) {        
            list( $action, $details, $whenToLoad) = $actionArray;
                                   
            if(!$this->filteraction_should_beloaded( $whenToLoad) ) {      
                continue;
            }
                 
            switch(count( $details) ) {         
                case 3:
                    add_action( $action, array( $this, $details[0] ), $details[1], $details[2] );     
                break;
                case 2:
                    add_action( $action, array( $this, $details[0] ), $details[1] );   
                break;
                case 1:
                default:
                    add_action( $action, array( $this, $details) );
            }
        }    
    }
    
    protected function add_filters() {
        foreach( $this->plugin_filters as $filterArray ) {
            list( $filter, $details, $whenToLoad) = $filterArray;
                           
            if(!$this->filteraction_should_beloaded( $whenToLoad) ) {
                continue;
            }
            
            switch(count( $details) ) {
                case 3:
                    add_filter( $filter, array( $this, $details[0] ), $details[1], $details[2] );
                break;
                case 2:
                    add_filter( $filter, array( $this, $details[0] ), $details[1] );
                break;
                case 1:
                default:
                    add_filter( $filter, array( $this, $details) );
            }
        }    
    }    
    
    /**
    * Should the giving action or filter be loaded?
    * 1. we can add security and check settings per case, the goal is to load on specific pages/areas
    * 2. each case is a section and we use this approach to load action or filter for specific section
    * 3. In early development all sections are loaded, this function is prep for a modular plugin
    * 4. addons will require core functions like this to be updated rather than me writing dynamic functions for any possible addons
    *  
    * @param mixed $whenToLoad
    */
    private function filteraction_should_beloaded( $whenToLoad) {
        $csv2post_settings = $this->adminsettings();
          
        switch( $whenToLoad) {
            case 'all':    
                return true;
            break;
            case 'adminpages':
                // load when logged into admin and on any admin page
                if( is_admin() ){return true;}
                return false;    
            break;
            case 'pluginscreens':
       
                // load when on a CSV 2 POST admin screen
                if( isset( $_GET['page'] ) && strstr( $_GET['page'], 'csv2post' ) ){return true;}
                
                return false;    
            break;            
            case 'pluginanddashboard':

                if( self::is_dashboard() ) {
                    return true;    
                }

                if( isset( $_GET['page'] ) && strstr( $_GET['page'], 'csv2post' ) ){
                    return true;
                }
                
                return false;    
            break;
            case 'projects':
                return true;    
            break;            
            case 'systematicpostupdating':  
                if(!isset( $csv2post_settings['standardsettings']['systematicpostupdating'] ) || $csv2post_settings['standardsettings']['systematicpostupdating'] != 'enabled' ){
                    return false;    
                }      
                return true;
            break;
            case 'admin_notices':                         

                if( self::is_dashboard() ) {
                    return true;    
                }
                                                           
                if( isset( $_GET['page'] ) && strstr( $_GET['page'], 'csv2post' ) ){
                    return true;
                }
                                                                                                   
                return false;
            break;
        }

        return true;
    }   
    
    /**
    * Determine if on the dashboard page. 
    * 
    * $current_screen is not set early enough for calling in some actions. So use this
    * function instead.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function is_dashboard() {
        global $pagenow;
        if( isset( $pagenow ) && $pagenow == 'index.php' ) { return true; }
        return strstr( $this->PHP->currenturl(), 'wp-admin/index.php' );
    }
                   
    /**
    * When request will display maximum php errors including WordPress errors 
    */
    public function debugmode() {
        global $c2p_debug_mode;
        if( $c2p_debug_mode){
            global $wpdb;
            ini_set( 'display_errors',1);
            error_reporting(E_ALL);      
            if(!defined( "WP_DEBUG_DISPLAY") ){define( "WP_DEBUG_DISPLAY", true);}
            if(!defined( "WP_DEBUG_LOG") ){define( "WP_DEBUG_LOG", true);}
            //add_action( 'all', create_function( '', 'var_dump( current_filter() );' ) );
            //define( 'SAVEQUERIES', true );
            //define( 'SCRIPT_DEBUG', true );
            $wpdb->show_errors();
            $wpdb->print_error();
        }
    }
    
    /**
    * Gets option value for csv2post _adminset or defaults to the file version of the array if option returns invalid.
    * 1. Called in the main csv2post.php file.
    * 2. Installs the admin settings option record if it is currently missing due to the settings being required by all screens, this is to begin applying and configuring settings straighta away for a per user experience 
    */
    public function adminsettings() {
        $result = $this->option( 'csv2post_settings', 'get' );
        $result = maybe_unserialize( $result); 
        if(is_array( $result) ){
            return $result; 
        }else{     
            return $this->install_admin_settings();
        }  
    }
    
    /**
    * Control WordPress option functions using this single function.
    * This function will give us the opportunity to easily log changes and some others ideas we have.
    * 
    * @param mixed $option
    * @param mixed $action add, get, wtgget (own query function) update, delete
    * @param mixed $value
    * @param mixed $autoload used by add_option only
    */
    public function option( $option, $action, $value = 'No Value', $autoload = 'yes' ){
        if( $action == 'add' ){  
            return add_option( $option, $value, '', $autoload );            
        }elseif( $action == 'get' ){
            return get_option( $option);    
        }elseif( $action == 'update' ){        
            return update_option( $option, $value );
        }elseif( $action == 'delete' ){
            return delete_option( $option);        
        }
    }
                      
    /**
     * Add a widget to the dashboard.
     *
     * This function is hooked into the 'wp_dashboard_setup' action below.
     */
     
    /**
    * Hooked by wp_dashboard_setup
    * 
    * @uses CSV2POST_UI::add_dashboard_widgets() which has the widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.1
    */
    public function add_dashboard_widgets() {
        $this->UI->add_dashboard_widgets();            
    }  
            
    /**
    * Determines if the plugin is fully installed or not
    * 
    * NOT IN USE - I've removed a global and a loop pending a new class that will need to be added to this function
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 6.0.0
    * @version 1.0.1
    */       
    public function is_installed() {
        return true;        
    }                       

    public function screen_options() {
        global $pippin_sample_page;
        $screen = get_current_screen();

        // toplevel_page_csv2post (main page)
        if( $screen->id == 'toplevel_page_csv2post' ){
            $args = array(
                'label' => __( 'Members per page' ),
                'default' => 1,
                'option' => 'csv2post_testoption'
            );
            add_screen_option( 'per_page', $args );
        }     
    }

    public function save_screen_option( $status, $option, $value ) {
        if ( 'csv2post_testoption' == $option ) return $value;
    }
      
    /**
    * WordPress Help tab content builder
    * 
    * uses help text from the main menu array to build help content
    * function must not be moved, keep it close to where it is called in the menu function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.2
    */
    public function help_tab () {
                               
        // get the current screen array
        $screen = get_current_screen();
        
        // load help class which contains help content array
        $CSV2POST_Help = self::load_class( 'C2P_Help', 'class-help.php', 'classes' );

        // call the array
        $help_array = $CSV2POST_Help->get_help_array();
        
        // load tab menu class which contains help content array
        $CSV2POST_TabMenu = self::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        
        // call the menu_array
        $menu_array = $CSV2POST_TabMenu->menu_array();
             
        // get page name i.e. csv-2-post_page_csv2post_affiliates would return affiliates
        $page_name = $this->PHP->get_string_after_last_character( $screen->id, '_' );
        
        // if on main page "csv2post" then set tab name as main
        if( $page_name == 'csv2post' ){$page_name = 'main';}
     
        // does the page have any help content? 
        if( !isset( $menu_array[ $page_name ] ) ){
            return false;
        }
        
        // set view name
        $view_name = $page_name;

        // does the view have any help content
        if( !isset( $help_array[ $page_name ][ $view_name ] ) ){
            return false;
        }
              
        // build the help content for the view
        $help_content = '<p>' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewabout' ] . '</p>';

        // add a link encouraging user to visit site and read more OR visit YouTube video
        if( isset( $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewreadmoreurl' ] ) ){
            $help_content .= '<p>';
            $help_content .= __( 'You are welcome to visit the', 'csv2post' ) . ' ';
            $help_content .= '<a href="' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewreadmoreurl' ] . '"';
            $help_content .= 'title="' . __( 'Visit the CSV 2 POST website and read more about', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ] . '"';
            $help_content .= 'target="_blank"';
            $help_content .= '>';
            $help_content .= __( 'CSV 2 POST Website', 'csv2post' ) . '</a> ' . __( 'to read more about', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ];           
            $help_content .= '.</p>';
        }  
        
        // add a link to a Youtube
        if( isset( $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewvideourl' ] ) ){
            $help_content .= '<p>';
            $help_content .= __( 'There is a', 'csv2post' ) . ' ';
            $help_content .= '<a href="' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewvideourl' ] . '"';
            $help_content .= 'title="' . __( 'Go to YouTube and watch a video about', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ] . '"';
            $help_content .= 'target="_blank"';
            $help_content .= '>';            
            $help_content .= __( 'YouTube Video', 'csv2post' ) . '</a> ' . __( 'about', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ];           
            $help_content .= '.</p>';
        }

        // add a link to a Youtube
        if( isset( $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewdiscussurl' ] ) ){
            $help_content .= '<p>';
            $help_content .= __( 'We invite you to take discuss', 'csv2post' ) . ' ';
            $help_content .= '<a href="' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewdiscussurl' ] . '"';
            $help_content .= 'title="' . __( 'Visit the WebTechGlobal forum to discuss', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ] . '"';
            $help_content .= 'target="_blank"';
            $help_content .= '>';            
            $help_content .= $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ] . '</a> ' . __( 'on the WebTechGlobal Forum', 'csv2post' );           
            $help_content .= '.</p>';
        }         

        // finish by adding the first tab which is for the view itself (soon to become registered pages) 
        $screen->add_help_tab( array(
            'id'    => $page_name,
            'title'    => __( 'About', 'csv2post' ) . ' ' . $help_array[ $page_name ][ $view_name ][ 'viewinfo' ][ 'viewtitle' ] ,
            'content'    => $help_content,
        ) );
  
        // add a tab per form
        $help_content = '';
        foreach( $help_array[ $page_name ][ $view_name ][ 'forms' ] as $form_id => $value ){
                                
            // the first content is like a short introduction to what the box/form is to be used for
            $help_content .= '<p>' . $value[ 'formabout' ] . '</p>';
                         
            // add a link encouraging user to visit site and read more OR visit YouTube video
            if( isset( $value[ 'formreadmoreurl' ] ) ){
                $help_content .= '<p>';
                $help_content .= __( 'You are welcome to visit the', 'csv2post' ) . ' ';
                $help_content .= '<a href="' . $value[ 'formreadmoreurl' ] . '"';
                $help_content .= 'title="' . __( 'Visit the CSV 2 POST website and read more about', 'csv2post' ) . ' ' . $value[ 'formtitle' ] . '"';
                $help_content .= 'target="_blank"';
                $help_content .= '>';
                $help_content .= __( 'CSV 2 POST Website', 'csv2post' ) . '</a> ' . __( 'to read more about', 'csv2post' ) . ' ' . $value[ 'formtitle' ];           
                $help_content .= '.</p>';
            }  
            
            // add a link to a Youtube
            if( isset( $value[ 'formvideourl' ] ) ){
                $help_content .= '<p>';
                $help_content .= __( 'There is a', 'csv2post' ) . ' ';
                $help_content .= '<a href="' . $value[ 'formvideourl' ] . '"';
                $help_content .= 'title="' . __( 'Go to YouTube and watch a video about', 'csv2post' ) . ' ' . $value[ 'formtitle' ] . '"';
                $help_content .= 'target="_blank"';
                $help_content .= '>';            
                $help_content .= __( 'YouTube Video', 'csv2post' ) . '</a> ' . __( 'about', 'csv2post' ) . ' ' . $value[ 'formtitle' ];           
                $help_content .= '.</p>';
            }

            // add a link to a Youtube
            if( isset( $value[ 'formdiscussurl' ] ) ){
                $help_content .= '<p>';
                $help_content .= __( 'We invite you to discuss', 'csv2post' ) . ' ';
                $help_content .= '<a href="' . $value[ 'formdiscussurl' ] . '"';
                $help_content .= 'title="' . __( 'Visit the WebTechGlobal forum to discuss', 'csv2post' ) . ' ' . $value[ 'formtitle' ] . '"';
                $help_content .= 'target="_blank"';
                $help_content .= '>';            
                $help_content .= $value[ 'formtitle' ] . '</a> ' . __( 'on the WebTechGlobal Forum', 'csv2post' );           
                $help_content .= '.</p>';
            } 
                               
            // loop through options
            foreach( $value[ 'options' ] as $key_two => $option_array ){  
                $help_content .= '<h3>' . $option_array[ 'optiontitle' ] . '</h3>';
                $help_content .= '<p>' . $option_array[ 'optiontext' ] . '</p>';
                            
                if( isset( $option_array['optionurl'] ) ){
                    $help_content .= ' <a href="' . $option_array['optionurl'] . '"';
                    $help_content .= ' title="' . __( 'Read More about', 'csv2post' )  . ' ' . $option_array['optiontitle'] . '"';
                    $help_content .= ' target="_blank">';
                    $help_content .= __( 'Read More', 'csv2post' ) . '</a>';      
                }
      
                if( isset( $option_array['optionvideourl'] ) ){
                    $help_content .= ' - <a href="' . $option_array['optionvideourl'] . '"';
                    $help_content .= ' title="' . __( 'Watch a video about', 'csv2post' )  . ' ' . $option_array['optiontitle'] . '"';
                    $help_content .= ' target="_blank">';
                    $help_content .= __( 'Video', 'csv2post' ) . '</a>';      
                }
            }
            
            // add the tab for this form and its help content
            $screen->add_help_tab( array(
                'id'    => $page_name . $view_name,
                'title'    => $help_array[ $page_name ][ $view_name ][ 'forms' ][ $form_id ][ 'formtitle' ],
                'content'    => $help_content,
            ) );                
                
        }
  
    }  

    /**
    * Gets the required capability for the plugins page from the page array
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.1
    *  
    * @param mixed $csv2post_page_name
    * @param mixed $default
    */
    public function get_page_capability( $page_name ){
        $capability = 'administrator';// script default for all outcomes

        // get stored capability settings 
        $saved_capability_array = get_option( 'csv2post_capabilities' );
                
        if( isset( $saved_capability_array['pagecaps'][ $page_name ] ) && is_string( $saved_capability_array['pagecaps'][ $page_name ] ) ) {
            $capability = $saved_capability_array['pagecaps'][ $page_name ];
        }
                   
        return $capability;   
    }   

    /**
    * Determine if further changes needed to the plugin or entire WP installation
    * straight after the plugin has been updated.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */
    public function complete_plugin_update() {
        // determine if manual update required or not 
        // if not then update installed version to the file package
        $manual_update_require = false;
        $this->UpdatePlugin = self::load_class( 'CSV2POST_UpdatePlugin', 'class-updates.php', 'classes' );        
       
        if( !$package_version_cleaned )
        {
            // does new version have an update method
            if( method_exists( $this->UpdatePlugin, 'patch_' . str_replace( '.', '', self::version ) ) )
            { 
                // run the new packages update method - these methods can take a command for special situations
                eval( '$update_result_array = $this->Updates->patch_' . self::version .'( "update" );' );
                
                // update stored version
                if( $update_result_array['failed'] !== true){           
                    global $csv2post_filesversion;        
                    update_option( 'csv2post_installedversion', $csv2post_filesversion);        
                }                                                                                        
            }
        }
    }
    
    /**
    * WordPress plugin menu
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 6.0.0
    * @version 1.2.8
    */
    public function admin_menu() { 
        global $csv2post_filesversion, $c2pm, $csv2post_settings;
         
        $CSV2POST_TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        $CSV2POST_Menu = $CSV2POST_TabMenu->menu_array();
 
        // set the callback, we can change this during the loop and call methods more dynamically
        // this approach allows us to call the same function for all pages
        $subpage_callback = array( $this, 'show_admin_page' );

        // add menu
        $this->page_hooks[] = add_menu_page( $CSV2POST_Menu['main']['title'], 
        'CSV 2 POST', 
        'administrator', 
        'csv2post',  
        $subpage_callback ); 
        
        // help tab                                                 
        add_action( 'load-toplevel_page_csv2post', array( $this, 'help_tab' ) );

        // track which group has already been displayed using the parent name
        $groups = array();
        
        // remove arrayinfo from the menu array
        unset( $CSV2POST_Menu['arrayinfo'] );
        
        // get all group menu titles
        $group_titles_array = array();
        foreach( $CSV2POST_Menu as $key_pagename => $page_array ){ 
            if( $page_array['parent'] === 'parent' ){                
                $group_titles_array[ $page_array['groupname'] ]['grouptitle'] = $page_array['menu'];
            }
        }          
        
        // loop through sub-pages - remove pages that are not to be registered
        foreach( $CSV2POST_Menu as $key_pagename => $page_array ){                 

            // if not visiting this plugins pages, simply register all the parents
            if( !isset( $_GET['page'] ) || !strstr( $_GET['page'], 'csv2post' ) ){
                
                // remove none parents
                if( $page_array['parent'] !== 'parent' ){    
                    unset( $CSV2POST_Menu[ $key_pagename ] ); 
                }        
            
            }elseif( isset( $_GET['page'] ) && strstr( $_GET['page'], 'csv2post' ) ){
                
                // remove pages that are not the main, the current visited or a parent
                if( $key_pagename !== 'main' && $page_array['slug'] !== $_GET['page'] && $page_array['parent'] !== 'parent' ){
                    unset( $CSV2POST_Menu[ $key_pagename ] );
                }     
                
            } 
            
            // remove the parent of a group for the visited page
            if( isset( $_GET['page'] ) && $page_array['slug'] === $_GET['page'] ){
                unset( $CSV2POST_Menu[ $CSV2POST_Menu[ $key_pagename ]['parent'] ] );
            }
                    
            // if no current project is set remove all but the "1. Projects" page
            if( !isset( $csv2post_settings['currentproject'] ) && $page_array['groupname'] !== 'projects' ) {
                unset( $CSV2POST_Menu[ $key_pagename ] );
            }
            
            // remove update page as it is only meant to show when new version of files applied
            if( $page_array['slug'] == 'csv2post_pluginupdate' ) {
                unset( $CSV2POST_Menu[ $key_pagename ] );
            }
        }

        foreach( $CSV2POST_Menu as $key_pagename => $page_array ){ 
            
            $this->page_hooks[] = add_submenu_page( 'csv2post', 
                   $group_titles_array[ $page_array['groupname'] ]['grouptitle'], 
                   $group_titles_array[ $page_array['groupname'] ]['grouptitle'], 
                   self::get_page_capability( $key_pagename ), 
                   $CSV2POST_Menu[ $key_pagename ]['slug'], 
                   $subpage_callback );     

                // help tab                                                 
                add_action( 'load-csv-2-post_page_csv2post_' . $key_pagename, array( $this, 'help_tab' ) );       
                          
        }
    }
    
    /**
     * Tabs menu loader - calls function for css only menu or jquery tabs menu
     * 
     * @param string $thepagekey this is the screen being visited
     */
    public function build_tab_menu( $current_page_name ){           
        // load tab menu class which contains help content array
        $CSV2POST_TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        
        // call the menu_array
        $menu_array = $CSV2POST_TabMenu->menu_array();
                
        echo '<h2 class="nav-tab-wrapper">';
        
        // get the current pages viewgroup for building the correct tab menu
        $view_group = $menu_array[ $current_page_name ][ 'groupname'];
            
        foreach( $menu_array as $page_name => $values ){
                                                         
            if( $values['groupname'] === $view_group ){
                
                $activeclass = 'class="nav-tab"';
                if( $page_name === $current_page_name ){                      
                    $activeclass = 'class="nav-tab nav-tab-active"';
                }
                
                echo '<a href="' . self::create_adminurl( $values['slug'] ) . '" '.$activeclass.'>' . $values['pluginmenu'] . '</a>';       
            }
        }      
        
        echo '</h2>';
    }   
        
    /**
    * $_POST and $_GET request processing procedure.
    * 
    * function was reduced to two lines, the contents mode to C2P_Requests itself.
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.4
    */
    public function process_admin_POST_GET() {  
        // include the class that processes form submissions and nonce links
        $CSV2POST_REQ = self::load_class( 'C2P_Requests', 'class-requests.php', 'classes' );
        $CSV2POST_REQ->process_admin_request();
    }  

    /**
    * Used to display this plugins notices on none plugin pages i.e. dashboard.
    * 
    * filteraction_should_beloaded() decides if the admin_notices hook is called, which hooks this function.
    * I think that check should only check which page is being viewed. Anything more advanced might need to
    * be performed in display_users_notices().
    * 
    * @uses display_users_notices()
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function admin_notices() {
        $this->UI->display_users_notices();
    }
                                 
    /**
    * Popup and content for media button displayed just above the WYSIWYG editor 
    */
    public function pluginmediabutton_popup() {
        global $csv2post_settings;
        ?>
        <div id="csv2post_popup_container" style="display:none;">
            <h2>Column Replacement Tokens</h2>
            <?php 
            if(!isset( $csv2post_settings['currentproject'] ) || !is_numeric( $csv2post_settings['currentproject'] ) ){
                echo '<p>' . __( '' ) . '</p>';
            }else{
                $projectcolumns = self::get_project_columns_from_db( $csv2post_settings['currentproject'], true );
                unset( $projectcolumns['arrayinfo'] ); 
                $tokens = '';
                foreach( $projectcolumns as $table_name => $columnfromdb ){
                    foreach( $columnfromdb as $key => $acol){
                        $tokens .= "#$acol#&#13;&#10;";
                    }  
                }     
                
                echo '<textarea rows="35" cols="70">' . $tokens . ' </textarea>';
            }
            ?>
        </div><?php
    }    
    
    /**
    * Determines if an event is due and processes what we refer to as an action (event action) it if true.
    * 1. Early in the function we do every possible check to find a reason not to process
    * 2. This function checks all required values exist, else it sets them then returns as this is considered an event action
    * 3. This function itself is considered part of the event, we cycle through event types
    * 
    * Trace
    * $c2p_schedule_array['history']['trace'] is used to indicate how far the this script went before a return.
    * This is a simple way to quickly determine where we are arriving.
    * 
    * @return boolean false if no due events else returns true to indicate event was due and full function ran
    */
    public function event_check() {
        $c2p_schedule_array = self::get_option_schedule_array();
        
        // do not continue if WordPress is DOING_AJAX
        if( self::request_made() ){return;}
                      
        self::log_schedule( __( 'The schedule is being checked. There should be further log entries explaining the outcome.', 'csv2post' ), __( 'schedule being checked', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
        
        // now do checks that we will store the return reason for to allow us to quickly determine why it goes no further                     
        //  get and ensure we have the schedule array
        //  we do not initialize the schedule array as the user may be in the processing of deleting it
        //  do not use csv2post_event_refused as we do not want to set the array
        if(!isset( $c2p_schedule_array ) || !is_array( $c2p_schedule_array ) ){       
            self::log_schedule( __( 'Scheduled events cannot be peformed due to the schedule array of stored settings not existing.', 'csv2post' ), __( 'schedule settings missing', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            return false;
        }// hack
                      
        // check when last event was run - avoid running two events within 1 minute of each other
        // I've set it here because this function could grow over time and we dont want to go through all the checks PER VISIT or even within a few seconds of each other.
        if( isset( $c2p_schedule_array['history']['lasteventtime'] ) )
        {    
            // increase lasteventtime by 60 seconds
            $soonest = $c2p_schedule_array['history']['lasteventtime'] + 60;// http://www.webtechglobal.co.uk/hacking/increase-automatic-events-delay-time
            
            if( $soonest > time() ){
                self::log_schedule( __( 'No changed made as it has not been 60 seconds since the last event.', 'csv2post' ), __( 'enforcing schedule event delay', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
                self::event_return( __( 'has not been 60 seconds since list event', 'csv2post' ) ); 
                return;
            }             
        }
        else
        {               
            // set lasteventtime value for the first time
            $c2p_schedule_array['history']['lasteventtime'] = time();
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The last even time event was set for the first time, no further processing was done.', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'The plugin initialized the timer for enforcing a delay between events. This action is treated as an event itself and no further
            changes are made during this schedule check.', 'csv2post' ), __( 'initialized schedule delay timer', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);

            self::event_return( __( 'initialised the last event time value', 'csv2post' ) );
            return;        
        }                             
                                           
        // is last event type value set? if not set default as dataupdate, this means postcreation is the next event
        if(!isset( $c2p_schedule_array['history']['lasteventtype'] ) )
        {    
            $c2p_schedule_array['history']['lasteventtype'] = 'dataupdate';
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The last event type value was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );

            self::log_schedule( __( 'The plugin initialized last event type value, this tells the plugin what event was last performed and it is used to
            determine what event comes next.', 'csv2post' ), __( 'initialized schedule last event value', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            
            self::event_return( __( 'initialised last event type value', 'csv2post' ) );
            return;
        }
                 
        // does the "day_lastreset"" time value exist, if not we set it now then return
        if(!isset( $c2p_schedule_array['history']['day_lastreset'] ) )
        {    
            $c2p_schedule_array['history']['day_lastreset'] = time();
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The last daily reset time was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            
            self::log_schedule( __( 'Day timer was set in schedule system. This is the 24 hour timer used to track daily events. It was set, no further action was taking 
            and should only happen once.', 'csv2post' ), __( '24 hour timer set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            
            self::event_return( __( 'initialised last daily reset time', 'csv2post' ) );        
            return;
        } 
                                                         
        // does the "hour_lastreset"" time value exist, if not we set it now then return
        if(!isset( $c2p_schedule_array['history']['hour_lastreset'] ) )
        { 
            $c2p_schedule_array['history']['hour_lastreset'] = time();
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The hourly reset time was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            
            self::log_schedule( __( 'Hourly timer was set in schedule system. The time has been set for hourly countdown. No further action was 
            taking. This should only happen once.', 'csv2post' ), __( 'one hour timer set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);        
            
            self::event_return( __( 'initialised hourly reset time', 'csv2post' ) );
            return;
        }    
               
        // does the hourcounter value exist, if not we set it now then return (this is to initialize the variable)
        if(!isset( $c2p_schedule_array['history']['hourcounter'] ) )
        {     
            $c2p_schedule_array['history']['hourcounter'] = 0;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The hourly events counter was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'Number of events per hour has been set for the first time, this change is treated as an event.', 'csv2post' ), __( 'hourly events counter set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);     
            self::event_return( __( 'initialised hourly events counter', 'csv2post' ) );   
            return;
        }     
                                     
        // does the daycounter value exist, if not we set it now then return (this is to initialize the variable)
        if(!isset( $c2p_schedule_array['history']['daycounter'] ) )
        {
            $c2p_schedule_array['history']['daycounter'] = 0;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'The daily events counter was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'The daily events counter was not set. No further action was taking. This measure should only happen once.', 'csv2post' ), __( 'daily events counter set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);     
            self::event_return( __( 'initialised daily events counter', 'csv2post' ) );           
            return;
        } 

        // has hourly target counter been reset for this hour - if not, reset now then return (this is an event)
        // does not actually start at the beginning of an hour, it is a 60 min allowance not hour to hour
        $hour_reset_time = $c2p_schedule_array['history']['hour_lastreset'] + 3600;
        if(time() > $hour_reset_time )
        {     
            // reset hour_lastreset value and the hourlycounter
            $c2p_schedule_array['history']['hour_lastreset'] = time();
            $c2p_schedule_array['history']['hourcounter'] = 0;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'Hourly counter was reset for another 60 minute period', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'Hourly counter has been reset, no further action is taking during this event. This should only happen once every hour.', 'csv2post' ), __( 'hourly counter reset', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( __( 'hourly counter was reset', 'csv2post' ) );        
            return;
        }  

        // have all target counters been reset for today - if not we will reset now and end event check (in otherwords this was the event)
        $day_reset_time = $c2p_schedule_array['history']['day_lastreset'] + 86400;
        if(time() > $day_reset_time )
        {
            $c2p_schedule_array['history']['hour_lastreset'] = time();
            $c2p_schedule_array['history']['day_lastreset'] = time();
            $c2p_schedule_array['history']['hourcounter'] = 0;
            $c2p_schedule_array['history']['daycounter'] = 0;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'Daily and hourly events counter reset for a new 24 hours period', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array ); 
            self::log_schedule( __( '24 hours had passed and the daily counter had to be reset. No further action is taking during these events and this should only happen once a day.', 'csv2post' ), __( 'daily counter reset', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);   
            self::event_return( '24 hour counter was reset' );            
            return;
        }

        // ensure event processing allowed today
        $day = strtolower(date( 'l' ) );
        if(!isset( $c2p_schedule_array['days'][$day] ) )
        {
            self::event_return( __( 'Event processing is has not been permitted for today', 'csv2post' ) );
            self::log_schedule( __( 'Event processing is not permitted for today. Please check schedule settings to change this.', 'csv2post' ), __( 'schedule not permitted today', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( 'schedule not permitting day' );        
            return;    
        } 

        // ensure event processing allow this hour   
        $hour = strtolower( date( 'G' ) );
        if(!isset( $c2p_schedule_array['hours'][$hour] ) )
        {
            self::event_return( __( 'Event processing is has not been permitted for the hour', 'csv2post' ) );
            self::log_schedule( __( 'Processsing is not permitted for the current hour. Please check schedule settings to change this.', 'csv2post' ), __( 'hour not permitted', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( __( 'schedule not permitting hour', 'csv2post' ) );        
            return;    
        }

        // ensure hourly limit value has been set
        if(!isset( $c2p_schedule_array['limits']['hour'] ) )
        {  
            $c2p_schedule_array['limits']['hour'] = 1;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'Hourly limit was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'The hourly limit value had not been set yet. You can change the limit but the default has been set to one. No further action is taking during this event and this should only happen once.', 'csv2post' ), __( 'no hourly limit set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( __( 'initialised hourly limit', 'csv2post' ) );        
            return;
        }     
                    
        // ensure daily limit value has been set
        if(!isset( $c2p_schedule_array['limits']['day'] ) )
        {
            $c2p_schedule_array['limits']['day'] = 1;
            $c2p_schedule_array['history']['lastreturnreason'] = __( 'Daily limit was set for the first time', 'csv2post' );
            self::update_option_schedule_array( $c2p_schedule_array );
            self::log_schedule( __( 'The daily limit value had not been set yet. It has now been set as one which allows only one post to be created or updated etc. This action should only happen once.', 'csv2post' ), __( 'no daily limit set', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__); 
            self::event_return( __( 'initialised daily limit', 'csv2post' ) );           
            return;
        }

        // if this hours target has been met return
        if( $c2p_schedule_array['history']['hourcounter'] >= $c2p_schedule_array['limits']['hour'] )
        {
            self::event_return( 'The hours event limit/target has been met' );
            self::log_schedule( __( 'The events target for the current hour has been met so no further processing is permitted.', 'csv2post' ), __( 'hourly target met', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( __( 'hours limit reached', 'csv2post' ) );            
            return;        
        }
         
        // if this days target has been met return
        if( $c2p_schedule_array['history']['daycounter'] >= $c2p_schedule_array['limits']['day'] )
        {
            self::event_return( __( 'The days event limit/target has been met', 'csv2post' ) );
            self::log_schedule( __( 'The daily events target has been met for the current 24 hour period (see daily timer counter). No events will be processed until the daily timer reaches 24 hours and is reset.', 'csv2post' ), __( 'daily target met', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            self::event_return( __( 'days limit reached', 'csv2post' ) );        
            return;       
        }
               
        // decide which event should be run (based on previous event, all events history and settings)
        $run_event_type = $this->event_decide();
                  
        self::log_schedule(sprintf( __( 'The schedule system decided that the next event type is %s.', 'csv2post' ), $run_event_type), __( 'next event type determined', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);
            
        // update $c2p_schedule_array with decided event type to advance the cycle and increase hourly plus daily counter
        $c2p_schedule_array['history']['lasteventtype'] = $run_event_type;
        $c2p_schedule_array['history']['lasteventtime'] = time(); 
        $c2p_schedule_array['history']['hourcounter'] = $c2p_schedule_array['history']['hourcounter'] + 1; 
        $c2p_schedule_array['history']['daycounter'] = $c2p_schedule_array['history']['daycounter'] + 1;
        self::update_option_schedule_array( $c2p_schedule_array );
        
        // run procedure for decided event
        $event_action_outcome = $this->event_action( $run_event_type); 
        
        return $event_action_outcome;   
    }

    /**
    * add_action hook init for calling using WP CRON events as a simple
    * framework solution. Passing value to this method, can be used to call more specific method
    * for an event.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function eventcheckwpcron() {
        return false;
    }
    
    /**
    * add_action hook init to act as a parent function to cron jobs run
    * using the server and not WP CRON or the WebTechGlobal automation system.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function event_check_servercron() {
        return false;
    }
        
    /**
    * Establishes which event should be run then return it.
    * Must call ths function within a function that checks that uses csv2post_DOING_AJAX() first 
    * 
    * 1. If you add a new event type, you must also update csv2post_tab1_pagecreation.php (Schedule), specifically Events Status panel
    * 2. Update event_action when adding a new event type
    * 3. Update the Event Types panel and add option for new event types
    * 4. Update csv2post_form_save_eventtypes
    * 
    * @link http://www.webtechglobal.co.uk/hacking/event-types
    */
    public function event_decide() {
        global $c2p_schedule_array, $csv2post_settings;
        
        // return focused event if active
        $override_event = $this->event_decide_focus();// returns false if no override settings in place    
        if( $override_event && is_string( $override_event) )
        {
            self::log_schedule(sprintf( __( 'The plugins ability to override the next due event type has been applied and then next event forced is %s.', 'csv2post' ), $override_event), __( 'next event type override', 'csv2post' ),1, 'scheduledeventcheck', __LINE__, __FILE__, __FUNCTION__);         
            return $override_event;
        }    

        // set default
        $run_event_type = 'createposts';
        
        // if we have no last event to establish the next event return the default
        if(!isset( $c2p_schedule_array['history']['lasteventtype'] ) ){
            return $run_event_type;
        }
        $bypass = false;// change to true when the next event after the last is not active, then the first available in the list will be the event 
        
        // dataimport -> dataupdate  
        if( $c2p_schedule_array['history']['lasteventtype'] == 'dataimport' ){
            if( isset( $c2p_schedule_array['eventtypes']['dataupdate']['switch'] ) && $c2p_schedule_array['eventtypes']['dataupdate']['switch'] == true){
                 return 'dataupdate';    
            }else{
                $bypass = true; 
            }            
        }
        
        // dataupdate -> postcreation
        if( $c2p_schedule_array['history']['lasteventtype'] == 'dataupdate' || $bypass == true){
            if( isset( $c2p_schedule_array['eventtypes']['postcreation']['switch'] ) && $c2p_schedule_array['eventtypes']['postcreation']['switch'] == true){
                 return 'postcreation';    
            }else{
                $bypass = true; 
            }            
        }    
        
        // postcreation -> postupdate
        if( $c2p_schedule_array['history']['lasteventtype'] == 'postcreation' || $bypass == true){
            if( isset( $c2p_schedule_array['eventtypes']['postupdate']['switch'] ) && $c2p_schedule_array['eventtypes']['postupdate']['switch'] == true){
                return 'postupdate';    
            }else{
                $bypass = true; 
            }            
        }    

        // postupdate -> dataimport
        if( $c2p_schedule_array['history']['lasteventtype'] == 'postupdate' || $bypass == true){
            if( isset( $c2p_schedule_array['eventtypes']['dataimport']['switch'] ) && $c2p_schedule_array['eventtypes']['dataimport']['switch'] == true){
                 return 'dataimport';    
            }else{
                $bypass = true; 
            }            
        }      
                           
        return $run_event_type;        
    }
    
    /**
    * Determines if user wants the schedule to focus on one specific event type
    */
    public function event_decide_focus() {
        $c2p_schedule_array = self::get_option_schedule_array();
        if( isset( $c2p_schedule_array['focus'] ) && $c2p_schedule_array['focus'] != false ){
            return $c2p_schedule_array['focus'];    
        }
    }
    
    /**
    * Runs the required event
    * 1. The event type determines what function is to be called. 
    * 2. We can add arguments here to call different (custom) functions and more than one action.
    * 3. Global settings effect the event type selected, it is always cycled to ensure good admin
    * 
    * @param mixed $run_event_type, see event_decide() for list of event types 
    */
    public function event_action( $run_event_type){    
        global $csv2post_settings, $CSV2POST;
        $c2p_schedule_array = CSV2POST::get_option_schedule_array();       
        $c2p_schedule_array['history']['lasteventaction'] = $run_event_type . ' Requested'; 
            
        // we can override the $run_event_type                          
        // run specific script for the giving action      
        switch ( $run_event_type) {
            case "dataimport":  
            
                // find a project with data still to import and return the project id (this includes new csv files with new rows)
                
                // enter project id into log
                
                // import data
                
                // enter result into log
                
                break;  
            case "dataupdate":
                 
                // find a project with a new csv file and return the id
                
                // import and update table where previously imported rows have now changed (do not import the new rows)

                break;
            case "postcreation":
            
                // find a project with unused rows
                
                // create posts based on global settings and project settings
                
                //csv2post_event_return( 'post creation procedure complete' );
                
                break;
            case "postupdate":

                /*
                * This has been designed to carry out one method of updating at a time.
                * 1st = Changed Records: search for records updated but not applied (post update function will update the csv2post_applied or at least the sql function called will)
                * 2nd = Meta Key: csv2post_outdated meta is searched, any meta value of 'yes' indicates posts needs to be updated 
                * 3rd = Changed Projected: project array includes a value ['updatecomplete'] which is set to true when no posts can be found with csv2post_updated dates older than ['modified']. This allows an update to all posts. 
                */                                

                $updating_carried_out = false;// change to true to avoid processing further methods after update performed
                
                ############################################################################
                #                                                                          #
                #                     UPDATED JOB TABLE RECORD METHOD                      #
                #                                                                          #
                ############################################################################

                // check which project was auto updated last and do the next, if none get the first project
                
                // does our project have updated rows not applied to their post
      
                if( $record){
         
                    // determine number of rows to apply to posts based on settings
                    
                    // ensure no further methods are processed if one or more rows are about to be updated
                    $updating_carried_out = true; 
                                        
                    // query that number of applicable rows
                    
                    // loop through those rows, updating their linked posts
                    
                    // lasteventaction value needs a few words explaining what was done during schedule event
                    $c2p_schedule_array['history']['lasteventaction'] = 'post with ID ' . $r['csv2post_postid'] . ' was updated with a new record';        

                }
          
                ############################################################################
                #                                                                          #
                #             csv2post_outdated META VALUE SET TO "yes" METHOD             #
                #                                                                          #
                ############################################################################         
                if(!$updating_carried_out){

                    // locate a post that have been marked as csv2post_outdated
                    //$post = csv2post_WP_SQL_get_posts_join_meta( 'csv2post_outdated', 'yes', $limit = 1, $select = 'ID' );
                    if( $post){
                        
                        // discontinue further post update methods
                        $updating_carried_out = true;
                        
                        // loop through posts
                        foreach( $post as $key => $p){

                            // continue foreach if no $project_code exists
                            $project_code = get_post_meta( $p->ID, 'csv2post_project_code', true);
                            if(!$project_code){continue;}
                            
                            //csv2post_post_updatepost( $p->ID,get_post_meta( $p->ID, 'csv2post_record_id', true), $project_code);
             
                            //csv2post_log_schedule( 'Post with ID '.$p->ID.' was updated because it was marked as outdated', 'post updated',1, $run_event_type, __LINE__, __FILE__, __FUNCTION__, 'medium' );
             
                            // update the lasteventaction value - a very short sentence explaining the specific changes in blog
                            $c2p_schedule_array['history']['lasteventaction'] = 'post ' . $p->ID . ' was updated using csv2post_outdated method';        
                        }
                    }                  
                }

                ############################################################################
                #                                                                          #
                #                  CHANGED PROJECT CONFIGURATION METHOD                    #
                #                                                                          #
                ############################################################################          
                // this method checks $c2p_projectslist_array[PROJECTCODE]['updatecomplete']
                // if ['updatecomplete'] = false then it means we have yet to determine if some or no posts need updating
                // if we cannot locate just 1 post requiring updating, we change ['updatecomplete'] to true
                // we determine if a post requires updating by comparing $c2p_projectslist_array[PROJECTCODE]['modified']
                // against the csv2post_updated meta value  
                if(!$updating_carried_out){
                                         
                    // locate a project with ['updatecomplete'] set to false
                    
                    // if we find an outdated project we will store the code
                    $outdated_project_code = false;                    
                    
                    // get the projects full array
                    
                    /*
                        now using the ['modified'] date not stored in $project_modified, we can search for a post not updated
                        1. if we find no posts then we change ['updatecomplete'] to true to avoid doing all this again
                        2. if we find a post we will update just one post due to the amount of processing involved in this                     
                    */
                    
                    // run query for a post that has an older csv2post_last_updated date than the projects modified date                        
                    $post = csv2post_SQL_get_posts_oudated( $project_modified, $project_code, $limit = 1, $select = 'ID' );
            
                    if( $post){
    
                        foreach( $post as $key => $p){

                            // get record ID used to create post and ensure the value is numeric (precaution against user editing the value)
                            $record_id = get_post_meta( $p->ID, 'csv2post_record_id', true);
                            if( $record_id && is_numeric( $record_id) ){
                                
                                // update the post, this function does not just use new records
                                //csv2post_post_updatepost( $p->ID, $record_id, $project_code);
             
                                // update the lasteventaction value - a very short sentence explaining the specific changes in blog
                                $c2p_schedule_array['history']['lasteventaction'] = 'post with ID ' . $p->ID . ' was updated because project configuration was changed';
                                
                            }
                        }                         

                    }else{
                                
                        // update the lasteventaction value - a very short sentence explaining the specific changes in blog
                        $c2p_schedule_array['history']['lasteventaction'] = 'schedule determine posts for project '.$outdated_project_code.' are all updated';
                                                            
                        // set the ['updatecomplete'] value to true to indicate all posts have been updated 
                            
                    }
                }                
        
                csv2post_event_return( 'data update procedure finished' ); 
                    
            break;
            
        }// end switch
        self::update_option_schedule_array( $c2p_schedule_array );
    } 
    
    /**
    * HTML for a media button that displays above the WYSIWYG editor
    * 
    * @param mixed $context
    */
    public function pluginmediabutton_button( $context )   
                                                          {
        //append the icon
        $context = "<a class='button thickbox' title='CSV 2 POST Column Replacement Tokens (CTRL + C then CTRL + V)'
        href='#TB_inline?width=400&inlineId=csv2post_popup_container'>CSV 2 POST</a>";
        
        return $context;
    }
    /**
    * Used in admin page headers to constantly check the plugins status while administrator logged in 
    */
    public function diagnostics_constant() {
        if( is_admin() && current_user_can( 'manage_options' ) ){
            
            // avoid diagnostic if a $_POST, $_GET or Ajax request made (it is installation state diagnostic but active debugging)                                          
            if( self::request_made() ){
                return;
            }
                              
        }
    } 
    
    /**
    * DO NOT CALL DURING FULL PLUGIN INSTALL
    * This function uses update. Do not call it during full install because user may be re-installing but
    * wishing to keep some existing option records.
    * 
    * Use this function when installing admin settings during use of the plugin. 
    */
    public function install_admin_settings() {
        require_once( WTG_CSV2POST_ABSPATH . 'arrays/settings_array.php' );
        return $this->option( 'csv2post_settings', 'update', $csv2post_settings );# update creates record if it does not exist   
    } 
     
    /**
    * includes a file per custom post type, we can customize this to include or exclude based on settings
    */
    public function custom_post_types() { 
        global $csv2post_settings;                          
        if( isset( $csv2post_settings['flagsystem']['status'] ) && $csv2post_settings['flagsystem']['status'] === 'enabled' ) {    
            require( WTG_CSV2POST_ABSPATH . 'posttypes/flags.php' );   
        }                                                       
        require( WTG_CSV2POST_ABSPATH . 'posttypes/posts.php' );
    }
 
    /**
    * Admin Triggered Automation
    */
    public function admin_triggered_automation() {
        // clear out log table (48 hour log)
        self::log_cleanup();
    }

    /**
    * Returns array holding the headers of the giving filename
    * It also prepares the array to hold other formats of the column headers in prepartion for the plugins various uses
    */
    public function get_headers_formatted( $filename, $separator = ', ', $quote = '"', $fields = 0){
        $header_array = array();
        
        // read and loop through the first row in the csv file  
        $handle = fopen( $filename, "r");
        while (( $row = fgetcsv( $handle, 10000, $separator, $quote) ) !== FALSE) {
       
            for ( $i = 0; $i < $fields; $i++ ){
                $header_array[$i]['original'] = $row[$i];
                $header_array[$i]['sql'] = self::clean_sqlcolumnname( $row[$i] );// none adapted/original sql version of headers, could have duplicates with multi-file jobs             
            }           
            break;
        }
                            
        return $header_array;    
    }
    
    /**
    * Creates a data source row, the data is used to track and manage the source
    * 
    * @param mixed $path
    * @param mixed $parentfile_id
    * @param mixed $tablename
    * @param mixed $sourcetype
    * @param mixed $csvconfig_array
    * @param mixed $idcolumn - important for relationship between source and database table plus between multiple tables in a multi-file project
    */
    public function insert_data_source( $path, $parentfile_id, $tablename, $sourcetype = 'localcsv', $csvconfig_array = array(), $idcolumn, $monitorfilechange = 1, $monitordirchange = 1 ){
        global $wpdb;
        return $this->DB->insert( $wpdb->c2psources,
            array(                 
                'path' => $path,
                'sourcetype' => $sourcetype,
                'datatreatment' => $csvconfig_array['datatreatment'],
                'parentfileid' => $parentfile_id,
                'tablename' => $tablename,
                'thesep' => $csvconfig_array['sep'],
                'theconfig' => maybe_serialize( $csvconfig_array ),
                'idcolumn' => $idcolumn,
                'monitorfilechange' => $monitorfilechange,
                'directory' => trailingslashit( dirname ( $path ) ),// used for handling multiple files and switching from an old file to new
            ) );
    } 
    
    /**
    * inserts a new project, merges a larger projects settings array into the fields_array
    * 
    * @param string $project_name 
    * @param mixed $sourceid_array - usually will be a single source with the key being "source1" however this increments for further sources
    * @param string $data_treatment (single | append | join | individual )
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.2
    */
    public function insert_project( $project_name, $sourceid_array, $data_treatment = 'single' ){
        global $wpdb;
        $fields_array = array( 'projectname' => $project_name, 'datatreatment' => $data_treatment );
        $fields_array = array_merge( $fields_array, $sourceid_array );        
        return $this->DB->insert( $wpdb->c2pprojects, $fields_array );
    }
    
    public function query_projects( $sort = null ) {
        global $wpdb;
        return $this->DB->selectwherearray( $wpdb->c2pprojects, 'projectid = projectid', 'timestamp', '*' );
    }
    
    public function create_project_table( $table_name, $columns_array ){
        global $wpdb;
 
        $query = "CREATE TABLE `{$table_name}` (
        `c2p_rowid` int(10) unsigned NOT NULL auto_increment,
        `c2p_postid` int(10) unsigned default 0,
        `c2p_use` tinyint(1) unsigned default 1,
        `c2p_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
        `c2p_applied` datetime NOT NULL,
        `c2p_changecounter` int(8) unsigned default 0, 
        ";                               
            
        // loop through jobs files
        foreach( $columns_array as $key => $column){
            $query .= "`" . $key . "` text default NULL, ";                                                                                                              
        }
      
        $query .= "PRIMARY KEY  (`c2p_rowid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Table created by CSV 2 POST';";
        
        $createresult1 = $wpdb->query( $query );
    }
    
    /**
    * Updates giving project with a compatible array of pre-defined project settings
    * 
    * this should be called as early as possible in project creation however if a user wanted to use it to
    * re-set a project after editing it manually that is fine.
    * 
    * @param mixed $project_id
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.0
    * @version 1.0.2
    */
    public function apply_project_defaults( $project_id ){
        global $csv2post_settings;     
        if( !isset( $csv2post_settings['projectdefaults'] ) ) { return false; }
        self::update_project( $project_id, array( 'projectsettings' => maybe_serialize( $csv2post_settings['projectdefaults'] ) ), true);
    }
    
    /**
    * Function is called to update posts before the page is rendered, the visitor
    * sees the new data. This function is called using add_action('the_posts'...  
    * 
    * @parameter $post array 
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function systematicpostupdate( $post ){
        global $wpdb, $csv2post_settings;
        
        // set a limit when updating multiple posts
        // default is to one avoid updating posts when not in single post view
        $multiple_posts_limit = 1;
        
        if( count( $post ) > $multiple_posts_limit ){
            return $post;    
        }
                         
        // avoid doing this if user is on the post trash view
        if( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ){
            return $post;
        }
        
        // return $post now if systematic post updating is not active
        if(!isset( $csv2post_settings['standardsettings']['systematicpostupdating'] ) || $csv2post_settings['standardsettings']['systematicpostupdating'] != 'enabled' ){
            return $post;    
        }
            
        // do we have a post - this should be the case due to the add_action but just to be sure
        if( isset( $post) && is_array( $post ) ){
            
            // loop through all post objects
            foreach( $post as $key => $thepost ){
                
                // check if post is owned by CSV 2 POST by getting the project ID from post meta                                                   
                $project_id = get_post_meta( $thepost->ID, 'c2p_project', true);
                
                // ensure we have three valid ID's else nothing can be done
                if( !is_numeric( $project_id) ){
                    continue;// moves on to next item in loop
                }
   
                // get the project row
                $project_array = self::get_project( $project_id );
                
                // continue if there is no project
                if(!$project_array){
                    continue;
                }
                 
                // change this to true if a reason for updating the post is found
                // keep in mind updating is a lot like re-building the post
                $update_this_post = false;
                
                // get the posts import data            
                $tables_array = self::get_dbtable_sources( $project_id );                 
                $row = $this->DB->query_multipletables( $tables_array, $this->get_project_idcolumn( $project_id), 'c2p_postid = ' . $thepost->ID, 1 );
                         
                // if no row of data there is nothing more to be done continue to next item
                if( !$row ){
                    continue;
                }
                
                // take the data out of [0]
                $row = $row[0];
                
                // check if c2p_updated > c2p_applied, if so the post will need to be updated
                if( isset( $row->c2p_updated ) && isset( $row->c2p_applied ) && $row->c2p_updated > c2p_applied ){
                    $update_this_post = true;    
                }

                // if still no reason to update, check if project settings changed since post was updated  
                if( !$update_this_post ){      
                    
                    // we will need the $project_array for updating but we need it right now for the settingschanged value  
                    if( isset( $project_array->settingschange ) ){ 
                        
                        // select meta row that matches our criteria
                        $outofdate = $this->DB->selectrow( $wpdb->postmeta,
                            "meta_key = 'c2p_updated'
                             AND meta_value < '".$project_array->settingschange."' 
                             AND post_id = '".$thepost->ID."'" );
                             
                        // if a row is returned then this CSV 2 POST post has not been updated since project changed 
                        if( $outofdate ){   
                            $update_this_post = true;
                        }
                    }
                }

                // update the post if a reason is found
                if( $update_this_post ){        
                    
                    // perform update on current post
                    $updatepost = new CSV2POST_UpdatePost();
                    $updatepost->settings = $csv2post_settings;
                    $updatepost->currentproject = $csv2post_settings['currentproject'];// dont automatically use by default, request may be for specific project
                    $updatepost->project = $project_array;// gets project row, includes sources and settings
                    $updatepost->projectid = $project_id;
                    $updatepost->maintable = self::get_project_main_table( $project_id );// main table holds post_id and progress statistics
                    $updatepost->projectsettings = maybe_unserialize( $updatepost->project->projectsettings );// unserialize settings
                    $updatepost->projectcolumns = self::get_project_columns_from_db( $project_id );
                    $updatepost->requestmethod = 'systematic';             
                    
                    // pass row to $autob
                    $updatepost->row = $row; 
                    
                    // pass the current post array for editing then returning and displaying the changes to the visitor
                    $updatepost->thepost = $thepost;   
                    
                    // update post - start method is the beginning of many nested functions
                    $updatepost->start();     

                    // log
                    $atts = array(
                        'outcome' => 1,
                        'line' => __LINE__, 
                        'function' => __FUNCTION__,
                        'file' => __FILE__,          
                        'type' => 'general',
                        'category' => 'usertrace',
                        'action' => __( "Systematic post update applied to post ID " . $thepost->ID . " and belongs to project ID " . $project_id . '.', 'csv2post' ),
                        'priority' => 'normal',                        
                        'triga' => 'systematic'
                    );
                    
                    self::newlog( $atts );
                            
                    // if a single post we can refresh - not going to do this if form submitted
                    // if for any reason the posts update status is not updated, this would cause a looping refresh
                    // should that happen, corrections need to be applied to custom field value c2p_updated
                    if( count( $post ) && $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
                        
                        $page = $this->PHP->currenturl();
                        header("Refresh: 0; url=$page");   
                             
                    }                                 
                }                                   
            }
        }  
                     
        return $post;
    }  

    /**
    * gets the giving posts row of imported data if the row has been updated since the post was created
    * 
    * can use this when checking if a post needs updated, rather than returning the posts row to another argument
    * 
    * @param mixed $project_id
    * @param mixed $post_id
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1 
    */
    public function get_posts_row_ifoutdated( $project_id, $post_id, $idcolumn = false ){
        $tables_array = self::get_dbtable_sources( $project_id );                       
        return self::query_multipletables( $tables_array, $idcolumn, 'c2p_postid = '.$post_id.' AND c2p_updated > c2p_applied', 1 );
    }
        
    /**
    * gets the id column column from project array and returns the colum name 
    * 
    * @returns boolean false on failure or no idcolumn set
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1 
    */
    public function get_project_idcolumn( $project_id ){
        global $wpdb;
        
        // get the project row
        $project_array = self::get_project( $project_id );   
        
        // unserialize settings vlaue
        $project_settings_array = maybe_unserialize( $project_array->projectsettings );
        
        // ensure idcolumn value exists
        if( isset( $project_settings_array['idcolumn'] ) ){
            return $project_settings_array['idcolumn'];
        }
        
        // get sources ID - we only need the first/main table
        $source_id_array = self::get_project_sourcesid( $project_id );
        
        if( !$source_id_array ){
            return false;
        }

        $row = $this->DB->selectrow( $wpdb->c2psources, 'sourceid = "' . $source_id_array[0] . '"', 'idcolumn' );   
        
        if( !$row ){
            return false;
        }
        
        if( empty( $row->idcolumn ) ){
            return false;
        }
         
        return $row->idcolumn;
    }
    
    /**
    * gets the specific row/s for a giving post ID
    * 
    * UPDATE: "c2p_postid != $post_id" was in use but this is wrong. I'm not sure how this has gone
    * undetected considering where the function has been used. 
    *
    * @param mixed $project_id
    * @param mixed $total
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.1
    */
    public function get_posts_rows( $project_id, $post_id, $idcolumn = false ){
        $this->DB = self::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $tables_array = $this->get_dbtable_sources( $project_id );
        return $this->DB->query_multipletables( $tables_array, $idcolumn, 'c2p_postid = '.$post_id );
    }
    
    /**
    * gets one or more rows from imported data for specific post created by specific project
    * 
    * @uses get_posts_rows() which does a join query 
    * 
    * @param mixed $project_id
    * @param mixed $post_id
    * @param mixed $idcolumn
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1 
    */
    public function get_posts_record( $project_id, $post_id, $idcolumn = false ){
        return self::get_posts_rows( $project_id, $post_id, $idcolumn );
    } 
    
    /**
    * Gets the MySQL version of column
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    * 
    * @returns false if no column set
    */
    public function get_category_column( $project_id, $level ) {
        if( isset( $this->current_project_settings['categories']['data'][$level]['column'] ) ){
            return $this->current_project_settings['categories']['data'][$level]['column'];    
        }           
        
        return false;
    } 

    /**
    * gets values from set category columns where the row has been used to create a post already
    * the intention is to use this to update existing posts where category settings or data changes
    * since the posts were created.
    * 
    * @returns OBJECT from $wpdb->get_results() unless $postid_as_key = true and array returned
    * @uses CSV2POST_DB::selectorderby()
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.1` 
    * 
    * @param mixed $project_id
    * @param mixed $total
    * @param mixed $idcolumn
    */
    public function get_category_data_used( $project_id, $total = 1, $postid_as_key = false, $post_id = false ){
        global $csv2post_settings;
        
        $select = 'c2p_postid';
        $from = 'notsetyet';

        if( isset( $csv2post_settings['currentproject'] ) && $csv2post_settings['currentproject'] !== false ){
            $this->current_project_object = self::get_project( $csv2post_settings['currentproject'] ); 
        }
        
        if( !$this->current_project_object ) {    
            $this->current_project_settings = false;
        } else {          
            $this->current_project_settings = maybe_unserialize( $this->current_project_object->projectsettings );
        }

        // loop through category data columns, their order is assumed to be the intended heirarchy order
        foreach( $this->current_project_settings['categories']['data'] as $level => $catarray ){
                                         
            $column = $catarray['column'];
            $from = $catarray['table'];
            
            if( $select === '' ){
                $select .= $column;
            }else{
                $select .= ', ' . $column;
            }

        }      
        
        // set condition to get all rows that have a post ID or get a specific post
        $condition = 'c2p_postid != 0';
        if( is_numeric( $post_id ) && $post_id != 0 ){
            $condition = 'c2p_postid = ' . $post_id;
        }
        
        $result = $this->DB->selectorderby( $from, $condition, $column, $select, $total, ARRAY_A );
        
        if( !$postid_as_key ) {
            return $result;
        }
        
        // arriving here means $postid_as_key has been requested
        // this allows a loop on post related data and then a loop on the category terms 
        $rebuilt = array();
        foreach( $result as $key => $row ){
            foreach( $row as $column => $value ){
                if( $column !== 'c2p_postid' ){
                    $rebuilt[ $row['c2p_postid'] ][] = $value;
                }
            } 
        }
        
        return $rebuilt;
    }

    /**
    * Determines if process request of any sort has been requested
    * 1. used to avoid triggering automatic processing during proccess requests
    * 
    * @returns true if processing already requested else false
    */
    public function request_made() {
        // ajax
        if(defined( 'DOING_AJAX' ) && DOING_AJAX){
            return true;    
        } 
        
        // form submissions - if $_POST is set that is fine, providing it is an empty array
        if( isset( $_POST) && !empty( $_POST) ){
            return true;
        }
        
        // CSV 2 POST own special processing triggers
        if( isset( $_GET['c2pprocsub'] ) || isset( $_GET['csv2postaction'] ) || isset( $_GET['nonceaction'] ) ){
            return true;
        }
        
        return false;
    } 
   
    /**
    * Used to build history, flag items and schedule actions to be performed.
    * 1. it all falls under log as we would probably need to log flags and scheduled actions anyway
    *
    * @global $wpdb
    * @uses extract, shortcode_atts
    */
    public function newlog( $atts ){     
        global $csv2post_settings, $wpdb, $csv2post_filesversion;

        $table_name = $wpdb->prefix . 'c2plog';
        
        // if ALL logging is off - if ['uselog'] not set then logging for all files is on by default
        if( isset( $csv2post_settings['globalsettings']['uselog'] ) && $csv2post_settings['globalsettings']['uselog'] == 0){
            return false;
        }
        
        // if log table does not exist return false
        if( !$this->DB->does_table_exist( $table_name ) ){
            return false;
        }
             
        // if a value is false, it will not be added to the insert query, we want the database default to kick in, NULL mainly
        extract( shortcode_atts( array(  
            'outcome' => 1,# 0|1 (overall outcome in boolean) 
            'line' => false,# __LINE__ 
            'function' => false,# __FUNCTION__
            'file' => false,# __FILE__ 
            'sqlresult' => false,# dump of sql query result 
            'sqlquery' => false,# dump of sql query 
            'sqlerror' => false,# dump of sql error if any 
            'wordpresserror' => false,# dump of a wp error 
            'screenshoturl' => false,# screenshot URL to aid debugging 
            'userscomment' => false,# beta testers comment to aid debugging (may double as other types of comments if log for other purposes) 
            'page' => false,# related page 
            'version' => $csv2post_filesversion, 
            'panelid' => false,# id of submitted panel
            'panelname' => false,# name of submitted panel 
            'tabscreenid' => false,# id of the menu tab  
            'tabscreenname' => false,# name of the menu tab 
            'dump' => false,# dump anything here 
            'ipaddress' => false,# users ip 
            'userid' => false,# user id if any     
            'comment' => false,# dev comment to help with troubleshooting
            'type' => 'general',# like a parent category, category being more of a child i.e. general|error|trace|usertrace|communication 
            'category' => false,# postevent|dataevent|uploadfile|deleteuser|edituser|usertrace|projectchange|settingschange 
            'action' => false,# 3 posts created|22 posts updated (the actuall action performed)
            'priority' => 'normal',# low|normal|high (use high for errors or things that should be investigated, use low for logs created mid procedure for tracing progress)                        
            'triga' => false# autoschedule|cronschedule|wpload|manualrequest|systematic|unknown
        ), $atts ) );
        
        // start query
        $query = "INSERT INTO $table_name";
        
        // add columns and values
        $query_columns = '(outcome';
        $query_values = '(1';
        
        if( $line ){$query_columns .= ',line';$query_values .= ', "'.$line.'"';}
        if( $file ){$query_columns .= ',file';$query_values .= ', "'.$file.'"';}                                                                           
        if( $function ){$query_columns .= ',function';$query_values .= ', "'.$function.'"';}  
        if( $sqlresult ){$query_columns .= ',sqlresult';$query_values .= ', "'.$sqlresult.'"';}     
        if( $sqlquery ){$query_columns .= ',sqlquery';$query_values .= ', "'.$sqlquery.'"';}     
        if( $sqlerror ){$query_columns .= ',sqlerror';$query_values .= ', "'.$sqlerror.'"';}    
        if( $wordpresserror ){$query_columns .= ',wordpresserror';$query_values .= ', "'.$wordpresserror.'"';}     
        if( $screenshoturl ){$query_columns .= ',screenshoturl';$query_values .= ', "'.$screenshoturl.'"' ;}     
        if( $userscomment ){$query_columns .= ',userscomment';$query_values .= ', "'.$userscomment.'"';}     
        if( $page ){$query_columns .= ',page';$query_values .= ', "'.$page.'"';}     
        if( $version ){$query_columns .= ',version';$query_values .= ', "'.$version.'"';}     
        if( $panelid ){$query_columns .= ',panelid';$query_values .= ', "'.$panelid.'"';}     
        if( $panelname ){$query_columns .= ',panelname';$query_values .= ', "'.$panelname.'"';}     
        if( $tabscreenid ){$query_columns .= ',tabscreenid';$query_values .= ', "'.$tabscreenid.'"';}     
        if( $tabscreenname ){$query_columns .= ',tabscreenname';$query_values .= ', "'.$tabscreenname.'"';}     
        if( $dump ){$query_columns .= ',dump';$query_values .= ', "'.$dump.'"';}     
        if( $ipaddress ){$query_columns .= ',ipaddress';$query_values .= ', "'.$ipaddress.'"';}     
        if( $userid ){$query_columns .= ',userid';$query_values .= ', "'.$userid.'"';}     
        if( $comment ){$query_columns .= ',comment';$query_values .= ', "'.$comment.'"';}     
        if( $type ){$query_columns .= ',type';$query_values .= ', "'.$type.'"';}     
        if( $category ){$query_columns .= ',category';$query_values .= ', "'.$category.'"';}     
        if( $action ){$query_columns .= ',action';$query_values .= ', "'.$action.'"';}     
        if( $priority ){$query_columns .= ',priority';$query_values .= ', "'.$priority.'"';}     
        if( $triga ){$query_columns .= ',triga';$query_values .= ', "'.$triga.'"';}
        
        $query_columns .= ' )';
        $query_values .= ' )';
        $query .= $query_columns .' VALUES '. $query_values;  
        $wpdb->query( $query );     
    } 
    
    /**
    * Log general/common/normal processing activity - no errors, no trace, no testing.
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POSt
    * @since 0.0.1
    * @version 1.0
    * 
    * @param mixed $line __LINE__
    * @param mixed $function __FUNCTION__
    * @param mixed $file __FILE__
    * @param string $triga autoschedule|cronschedule|wpload|manualrequest or add custom
    * @param string $category postevent|dataevent|uploadfile|deleteuser|edituser or add custom
    * @param string $action user readable information
    * @param boolean $outcome 
    * @param integer $userid
    */
    public function newlog_general( $line, $function, $file, $triga, $category, $action, $outcome = 1, $userid = false ) {

        $atts = array(
            'line' => $line,
            'function' => $function,
            'file' => $file,
            'triga' => $triga,
            'category' => $category,
            'action' => $action,
            'outcome' => $outcome,
            'priority' => 'normal',# low|normal|high
            'type' => 'general',# general|error|trace
        );
        
        if( $userid && is_numeric( $userid ) ) {
            $atts['userid'] = $userid;
        }
    
        self::newlog( $atts );
    }
    
    /**
    * Use this to log automated events and track progress in automated scripts.
    * Mainly used in schedule function but can be used in any functions called by add_action() or
    * other processing that is triggered by user events but not specifically related to what the user is doing.
    * 
    * @param mixed $outcome
    * @param mixed $trigger schedule, hook (action hooks such as text spinning could be considered automation), cron, url, user (i.e. user does something that triggers background processing)
    * @param mixed $line
    * @param mixed $file
    * @param mixed $function
    */
    public function log_schedule( $comment, $action, $outcome, $category = 'scheduledeventaction', $trigger = 'autoschedule', $line = 'NA', $file = 'NA', $function = 'NA' ){
        $atts = array();   
        $atts['logged'] = self::datewp();
        $atts['comment'] = $comment;
        $atts['action'] = $action;
        $atts['outcome'] = $outcome;
        $atts['category'] = $category;
        $atts['line'] = $line;
        $atts['file'] = $file;
        $atts['function'] = $function;
        $atts['trigger'] = $function;
        // set log type so the log entry is made to the required log file
        $atts['type'] = 'automation';
        self::newlog( $atts);    
    } 
   
    /**
    * Cleanup log table - currently keeps 2 days of logs
    */
    public function log_cleanup() {
        global $wpdb;     
        if( $this->DB->database_table_exist( $wpdb->c2plog) ){
            global $wpdb;
            $twodays_time = strtotime( '2 days ago midnight' );
            $twodays = date( "Y-m-d H:i:s", $twodays_time);
            $wpdb->query( 
                "
                    DELETE FROM $wpdb->c2plog
                    WHERE timestamp < '".$twodays."'
                "
            );
        }
    }

    /**
    * Query the log table.
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function getlog( $columns = '*', $where = '' ) {
        global $csv2post_settings, $wpdb;
        
        // if ALL logging is off return
        if( isset( $csv2post_settings['globalsettings']['uselog'] ) && $csv2post_settings['globalsettings']['uselog'] == 0 ){
            return false;
        }
           
        // if log table does not exist return false
        if( !$this->DB->does_table_exist( $wpdb->prefix . 'c2plog' ) ){
            return false;
        }
        
        // if $columns is not a string
        if( !is_string( $columns ) ) {
            $columns = '*';
        }
        
        // add space to query
        if( is_string( $where ) ) {
            $where = ' WHERE ' . $where;    
        }

        $complete_query = "SELECT $columns FROM " . $wpdb->prefix . 'c2plog' . $where;

        return $wpdb->get_results( "SELECT $columns FROM " . $wpdb->prefix . 'c2plog' . $where, ARRAY_A );     
    }
        
    /**
     * Checks existing plugins and displays notices with advice or informaton
     * This is not only for code conflicts but operational conflicts also especially automated processes
     *
     * $return $critical_conflict_result true or false (true indicatesd a critical conflict found, prevents installation, this should be very rare)
     */
    function conflict_prevention( $outputnoneactive = false ){
        // track critical conflicts, return the result and use to prevent installation
        // only change $conflict_found to true if the conflict is critical, if it only effects partial use
        // then allow installation but warn user
        $conflict_found = false;
            
        // we create an array of profiles for plugins we want to check
        $plugin_profiles = array();

        // Tweet My Post (javascript conflict and a critical one that breaks entire interface)
        $plugin_profiles[0]['switch'] = 1;//used to use or not use this profile, 0 is no and 1 is use
        $plugin_profiles[0]['title'] = __( 'Tweet My Post', 'csv2post' );
        $plugin_profiles[0]['slug'] = 'tweet-my-post/tweet-my-post.php';
        $plugin_profiles[0]['author'] = 'ksg91';
        $plugin_profiles[0]['title_active'] = __( 'Tweet My Post Conflict', 'csv2post' );
        $plugin_profiles[0]['message_active'] = __( 'Please deactivate Twitter plugins before performing mass post creation. This will avoid spamming Twitter and causing more processing while creating posts.', 'csv2post' );
        $plugin_profiles[0]['message_inactive'] = __( 'If you activate this or any Twitter plugin please ensure the plugins options are not setup to perform mass tweets during post creation.', 'csv2post' );
        $plugin_profiles[0]['type'] = 'info';//passed to the message function to apply styling and set type of notice displayed
        $plugin_profiles[0]['criticalconflict'] = true;// true indicates that the conflict will happen if plugin active i.e. not specific settings only, simply being active has an effect
                             
        // loop through the profiles now
        if( isset( $plugin_profiles) && $plugin_profiles != false ){
            foreach( $plugin_profiles as $key=>$plugin){   
                if( is_plugin_active( $plugin['slug'] ) ){ 
                   
                    // recommend that the user does not use the plugin
                    $this->notice_depreciated( $plugin['message_active'], 'warning', 'Small', $plugin['title_active'], '', 'echo' );

                    // if the conflict is critical, we will prevent installation
                    if( $plugin['criticalconflict'] == true){
                        $conflict_found = true;// indicates critical conflict found
                    }
                    
                }elseif(is_plugin_inactive( $plugin['slug'] ) ){
                    
                    if( $outputnoneactive)
                    {   
                        $this->n_incontent_depreciated( $plugin['message_inactive'], 'warning', 'Small', $plugin['title'] . ' Plugin Found' );
                    }
        
                }
            }
        }

        return $conflict_found;
    }     
        
    public function send_email( $recipients, $subject, $content, $content_type = 'html' ){     
                           
        if( $content_type == 'html' )
        {
            add_filter( 'wp_mail_content_type', 'csv2post_set_html_content_type' );
        }
        
        $result = wp_mail( $recipients, $subject, $content );

        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'communication',
            'category' => 'sendmail',
            'action' => __( 'Send mail attempt.', 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'unknown'
        );
        
        $this->CSV2POST->newlog( $atts );
        self::newlog( $atts );
                
        if( $content_type == 'html' )
        {    
            remove_filter( 'wp_mail_content_type', 'csv2post_set_html_content_type' );  
        }   
        
        return $result;
    }    
    
    /**
    * Creates url to an admin page
    *  
    * @param mixed $page, registered page slug i.e. csv2post_install which results in wp-admin/admin.php?page=csv2post_install   
    * @param mixed $values, pass a string beginning with & followed by url values
    */
    public function url_toadmin( $page, $values = '' ){                                  
        return get_admin_url() . 'admin.php?page=' . $page . $values;
    }
    
    /**
    * Adds <button> with jquerybutton class and </form>, for using after a function that outputs a form
    * Add all parameteres or add none for defaults
    * @param string $buttontitle
    * @param string $buttonid
    */
    public function formend_standard( $buttontitle = 'Submit', $buttonid = 'notrequired' ){
            if( $buttonid == 'notrequired' ){
                $buttonid = 'csv2post_notrequired'.rand(1000,1000000);# added during debug
            }else{
                $buttonid = $buttonid.'_formbutton';
            }?>

            <p class="submit">
                <input type="submit" name="csv2post_wpsubmit" id="<?php echo $buttonid;?>" class="button button-primary" value="<?php echo $buttontitle;?>">
            </p>

        </form><?php
    }
    
    /**
     * Echos the html beginning of a form and beginning of widefat post fixed table
     * 
     * @param string $name (a unique value to identify the form)
     * @param string $method (optional, default is post, post or get)
     * @param string $action (optional, default is null for self submission - can give url)
     * @param string $enctype (pass enctype="multipart/form-data" to create a file upload form)
     */
    public function formstart_standard( $name, $id = 'none', $method = 'post', $class, $action = '', $enctype = '' ){
        if( $class){
            $class = 'class="'.$class.'"';
        }else{
            $class = '';         
        }
        echo '<form '.$class.' '.$enctype.' id="'.$id.'" method="'.$method.'" name="csv2post_request_'.$name.'" action="'.$action.'">
        <input type="hidden" id="csv2post_admin_action" name="csv2post_admin_action" value="true">';
    } 
    
    public function get_project_name( $project_id){
        global $wpdb;
        $row = $this->DB->selectrow( $wpdb->c2pprojects, 'projectid = ' . $project_id, 'projectname' );
        if(!isset( $row->projectname) ){return 'No Current Project';}
        return $row->projectname;
    }   
    
    /**
    * Adds Script Start and Stylesheets to the beginning of pages
    */
    public function pageheader( $pagetitle, $layout){
        global $current_user, $c2pm, $csv2post_settings, $c2p_pub_set;

        // get admin settings again, all submissions and processing should update settings
        // if the interface does not show expected changes, it means there is a problem updating settings before this line
        $csv2post_settings = self::adminsettings(); 

        get_currentuserinfo();?>
                    
        <div id="csv2post-page" class="wrap">
            <?php self::diagnostics_constant();?>
        
            <div id="icon-options-general" class="icon32"><br /></div>
            
            <?php 
            // build page H2 title
            $h2_title = '';
            
            // if not "CSV 2 POST" set this title
            if( $pagetitle !== 'CSV 2 POST' ) {
                $h2_title = 'CSV 2 POST: ' . $pagetitle;    
            }

            // if update screen set this title
            if( $_GET['page'] == 'csv2post_pluginupdate' ){
                $h2_title = __( 'New CSV 2 POST Update Ready', 'csv2post' );
            }           
            ?>
            
            <h2><?php echo $h2_title;?></h2>

            <?php 
            // if not on main/about/news view show the project/campaign information
            if( $_GET['page'] !== 'csv2post' && $_GET['page'] !== 'csv2post_pluginupdate' ){
                $this->UI->display_current_project();
            }
                 
            // run specific admin triggered automation tasks, this way an output can be created for admin to see
            self::admin_triggered_automation();  

            // check existing plugins and give advice or warnings
            self::conflict_prevention();
                     
            // display form submission result notices
            $this->UI->output_depreciated();// now using display_all();
            $this->UI->display_all();              
          
            // process global security and any other types of checks here such such check systems requirements, also checks installation status
            $c2p_requirements_missing = self::check_requirements(true);
    }                          
    
    /**
    * Checks if the cores minimum requirements are met and displays notices if not
    * Checks: Internet Connection (required for jQuery ), PHP version, Soap Extension
    */
    public function check_requirements( $display ){
        // variable indicates message being displayed, we will only show 1 message at a time
        $requirement_missing = false;

        // php version
        if(defined(WTG_CSV2POST_PHPVERSIONMINIMUM) ){
            if(WTG_CSV2POST_PHPVERSIONMINIMUM > phpversion() ){
                $requirement_missing = true;
                if( $display == true){
                    self::notice_depreciated(sprintf( __( 'The plugin detected an older PHP version than the minimum requirement which 
                    is %s. You can requests an upgrade for free from your hosting, use .htaccess to switch
                    between PHP versions per WP installation or sometimes hosting allows customers to switch using their control panel.', 'csv2post' ),WTG_CSV2POST_PHPVERSIONMINIMUM)
                    , 'warning', 'Large', __( 'CSV 2 POST Requires PHP ', 'csv2post' ) . WTG_CSV2POST_PHPVERSIONMINIMUM);                
                }
            }
        }
        
        return $requirement_missing;
    }               
    
    /**       
     * Generates a username using a single value by incrementing an appended number until a none used value is found
     * @param string $username_base
     * @return string username, should only fail if the value passed to the function causes so
     * 
     * @todo log entry functions need to be added, store the string, resulting username
     */
    public function create_username( $username_base){
        $attempt = 0;
        $limit = 500;// maximum trys - would we ever get so many of the same username with appended number incremented?
        $exists = true;// we need to change this to false before we can return a value

        // clean the string
        $username_base = preg_replace( '/([^@]*).*/', '$1', $username_base );

        // ensure giving string does not already exist as a username else we can just use it
        $exists = username_exists( $username_base );
        if( $exists == false )
        {
            return $username_base;
        }
        else
        {
            // if $suitable is true then the username already exists, increment it until we find a suitable one
            while( $exists != false )
            {
                ++$attempt;
                $username = $username_base.$attempt;

                // username_exists returns id of existing user so we want a false return before continuing
                $exists = username_exists( $username );

                // break look when hit limit or found suitable username
                if( $attempt > $limit || $exists == false ){
                    break;
                }
            }

            // we should have our login/username by now
            if ( $exists == false ) 
            {
                return $username;
            }
        }
    }
    
    /**
    * Wrapper, uses csv2post_url_toadmin to create local admin url
    * 
    * @param mixed $page
    * @param mixed $values 
    */
    public function create_adminurl( $page, $values = '' ){
        return self::url_toadmin( $page, $values);    
    }
    
    /**
    * Returns the plugins standard date (MySQL Date Time Formatted) with common format used in WordPress.
    * Optional $time parameter, if false will return the current time().
    * 
    * @param integer $timeaddition, number of seconds to add to the current time to create a future date and time
    * @param integer $time optional parameter, by default causes current time() to be used
    */
    public function datewp( $timeaddition = 0, $time = false, $format = false ){
        // initialize time string
        if( $time != false && is_numeric( $time) ){$thetime = $time;}else{$thetime = time();}
        // has a format been past
        if( $format == 'gm' ){
            return gmdate( 'Y-m-d H:i:s', $thetime + $timeaddition);
        }elseif( $format == 'mysql' ){
            // return actual mysql database current time
            return current_time( 'mysql',0);// example 2005-08-05 10:41:13
        }
        
        // default to standard PHP with a common format used by WordPress and MySQL but not the actual database time
        return date( 'Y-m-d H:i:s', $thetime + $timeaddition);    
    }   
    
    public function get_installed_version() {
        return get_option( 'csv2post_installedversion' );    
    }  
    
    /**
    * Use to start a new result array which is returned at the end of a function. It gives us a common set of values to work with.

    * @uses self::arrayinfo_set()
    * @param mixed $description use to explain what array is used for
    * @param mixed $line __LINE__
    * @param mixed $function __FUNCTION__
    * @param mixed $file __FILE__
    * @param mixed $reason use to explain why the array was updated (rather than what the array is used for)
    * @return string
    */                                   
    public function result_array( $description, $line, $function, $file ){
        $array = self::arrayinfo_set(array(), $line, $function, $file );
        $array['description'] = $description;
        $array['outcome'] = true;// boolean
        $array['failreason'] = false;// string - our own typed reason for the failure
        $array['error'] = false;// string - add php mysql wordpress error 
        $array['parameters'] = array();// an array of the parameters passed to the function using result_array, really only required if there is a fault
        $array['result'] = array();// the result values, if result is too large not needed do not use
        return $array;
    }         
    
    /**
    * Get arrays next key (only works with numeric key )
    * 
    * @version 0.2 - return 0 if not array, used to return 1 but no longer a reason to do that
    * @author Ryan Bayne
    */
    public function get_array_nextkey( $array ){
        if(!is_array( $array ) || empty( $array ) ){
            return 0;   
        }
        
        ksort( $array );
        end( $array );
        return key( $array ) + 1;
    }
    
    /**
    * Gets the schedule array from wordpress option table.
    * Array [times] holds permitted days and hours.
    * Array [limits] holds the maximum post creation numbers 
    */
    public static function get_option_schedule_array() {
        $c2p_schedule_array = get_option( 'csv2post_schedule' );
        return maybe_unserialize( $c2p_schedule_array );    
    }
    
    /**
    * Builds text link, also validates it to ensure it still exists else reports it as broken
    * 
    * The idea of this function is to ensure links used throughout the plugins interface
    * are not broken. Over time links may no longer point to a page that exists, we want to 
    * know about this quickly then replace the url.
    * 
    * @return $link, return or echo using $response parameter
    * 
    * @param mixed $text
    * @param mixed $url
    * @param mixed $htmlentities, optional (string of url passed variables)
    * @param string $target, _blank _self etc
    * @param string $class, css class name (common: button)
    * @param strong $response [echo][return]
    */
    public function link( $text, $url, $htmlentities = '', $target = '_blank', $class = '', $response = 'echo', $title = '' ){
        // add ? to $middle if there is no proper join after the domain
        $middle = '';
                                 
        // decide class
        if( $class != '' ){$class = 'class="'.$class.'"';}
        
        // build final url
        $finalurl = $url.$middle.htmlentities( $htmlentities);
        
        // check the final result is valid else use a default fault page
        $valid_result = self::validate_url( $finalurl);
        
        if( $valid_result){
            $link = '<a href="'.$finalurl.'" '.$class.' target="'.$target.'" title="'.$title.'">'.$text.'</a>';
        }else{
            $linktext = __( 'Invalid Link, Click To Report' );
            $link = '<a href="http://www.webtechglobal.co.uk/wtg-blog/invalid-application-link/" target="_blank">'.$linktext.'</a>';        
        }
        
        if( $response == 'echo' ){
            echo $link;
        }else{
            return $link;
        }     
    }     
    
    /**
    * Updates the schedule array from wordpress option table.
    * Array [times] holds permitted days and hours.
    * Array [limits] holds the maximum post creation numbers 
    */
    public function update_option_schedule_array( $schedule_array ){
        $schedule_array_serialized = maybe_serialize( $schedule_array );
        return update_option( 'csv2post_schedule', $schedule_array_serialized);    
    }
    
    public function update_settings( $csv2post_settings ){
        $admin_settings_array_serialized = maybe_serialize( $csv2post_settings );
        return update_option( 'csv2post_settings', $admin_settings_array_serialized);    
    }
    
    /**
    * Returns WordPress version in short
    * 1. Default returned example by get_bloginfo( 'version' ) is 3.6-beta1-24041
    * 2. We remove everything after the first hyphen
    */
    public function get_wp_version() {
        $longversion = get_bloginfo( 'version' );
        return strstr( $longversion , '-', true );
    }
    
    /**
    * Determines if the giving value is a CSV 2 POST page or not
    */
    public function is_plugin_page( $page){
        return strstr( $page, 'csv2post' );  
    } 
    
    /**
    * Determines if giving tab for the giving page should be displayed or not based on current user.
    * 
    * Checks for reasons not to display and returns false. If no reason found to hide the tab then true is default.
    * 
    * @param mixed $page
    * @param mixed $tab
    * 
    * @return boolean
    */
    public function should_tab_be_displayed( $page, $tab){
        global $c2pm;

        if( isset( $c2pm[$page]['tabs'][$tab]['permissions']['capability'] ) ){
            $boolean = current_user_can( $c2pm[$page]['tabs'][$tab]['permissions']['capability'] );
            if( $boolean ==  false ){
                return false;
            }
        }

        // if screen not active
        if( isset( $c2pm[$page]['tabs'][$tab]['active'] ) && $c2pm[$page]['tabs'][$tab]['active'] == false ){
            return false;
        }    
        
        // if screen is not active at all (used to disable a screen in all packages and configurations)
        if( isset( $c2pm[$page]['tabs'][$tab]['active'] ) && $c2pm[$page]['tabs'][$tab]['active'] == false ){
            return false;
        }
                     
        return true;      
    } 
    
    /**
    * Builds a nonced admin link styled as button by WordPress
    *
    * @package CSV 2 POST
    * @since 8.0.0
    *
    * @return string html a href link nonced by WordPress  
    * 
    * @param mixed $page - $_GET['page']
    * @param mixed $action - examplenonceaction
    * @param mixed $title - Any text for a title
    * @param mixed $text - link text
    * @param mixed $values - begin with & followed by values
    * 
    * @deprecated this method has been moved to the CSV2POST_UI class
    */
    public function linkaction( $page, $action, $title = 'CSV 2 POST admin link', $text = 'Click Here', $values = '' ){
        return '<a href="'. wp_nonce_url( admin_url() . 'admin.php?page=' . $page . '&csv2postaction=' . $action  . $values, $action ) . '" title="' . $title . '" class="button c2pbutton">' . $text . '</a>';
    }
    
    /**
    * Get POST ID using post_name (slug)
    * 
    * @param string $name
    * @return string|null
    */
    public function get_post_ID_by_postname( $name){
        global $wpdb;
        // get page id using custom query
        return $wpdb->get_var( "SELECT ID 
        FROM $wpdb->posts 
        WHERE post_name = '".$name."' 
        AND post_type='page' ");
    }       
    
    /**
    * Returns all the columns in giving database table that hold data of the giving data type.
    * The type will be determined with PHP not based on MySQL column data types. 
    * 1. Table must have one or more records
    * 2. 1 record will be queried 
    * 3. Each columns values will be tested by PHP to determine data type
    * 4. Array returned with column names that match the giving type
    * 5. If $dt is false, all columns will be returned with their type however that is not the main purpose of this function
    * 6. Types can be custom, using regex etc. The idea is to establish if a value is of the pattern suitable for intended use.
    * 
    * @param string $tableName table name
    * @param string $dataType data type URL|IMG|NUMERIC|STRING|ARRAY
    * 
    * @returns false if no record could be found
    */
    public function cols_by_datatype( $tableName, $dataType = false ){
        global $wpdb;
        
        $ra = array();// returned array - our array of columns matching data type
        $matchCount = 0;// matches
        $ra['arrayinfo']['matchcount'] = $matchCount;

        $rec = $wpdb->get_results( 'SELECT * FROM '. $tableName .'  LIMIT 1',ARRAY_A);
        if(!$rec){return false;}
        
        $knownTypes = array();
        foreach( $rec as $id => $value_array ){
            foreach( $value_array as $column => $value ){     
                             
                $isURL = self::is_url( $value );
                if( $isURL){++$matchCount;$ra['matches'][] = $column;}
           
            }       
        }
        
        $ra['arrayinfo']['matchcount'] = $matchCount;
        return $ra;
    }  
    
    public function querylog_bytype( $type = 'all', $limit = 100){
        global $wpdb;

        // where
        $where = '';
        if( $type != 'all' ){
          $where = 'WHERE type = "'.$type.'"';
        }

        // limit
        $limit = 'LIMIT ' . $limit;
        
        // get_results
        $rows = $wpdb->get_results( 
        "
        SELECT * 
        FROM csv2post_log
        ".$where."
        ".$limit."

        ",ARRAY_A);

        if(!$rows){
            return false;
        }else{
            return $rows;
        }
    }  
    
    /**
    * Determines if all tables in a giving array exist or not
    * @returns boolean true if all table exist else false if even one does not
    */
    public function tables_exist( $tables_array ){
        if( $tables_array && is_array( $tables_array ) ){         
            // foreach table in array, if one does not exist return false
            foreach( $tables_array as $key => $table_name){
                $table_exists = $this->DB->does_table_exist( $table_name);  
                if(!$table_exists){          
                    return false;
                }
            }        
        }
        return true;    
    } 
    
    /**
    * Stores the last known reason why auto event was refused during checks in event_check()
    */
    public function event_return( $return_reason){
        $c2p_schedule_array = self::get_option_schedule_array();
        $c2p_schedule_array['history']['lastreturnreason'] = $return_reason;
        self::update_option_schedule_array( $c2p_schedule_array );   
    }  
    
    /**
    * Uses wp-admin/includes/image.php to store an image in WordPress files and database
    * from HTTP
    * 
    * @uses wp_insert_attachment()
    * @param mixed $imageurl
    * @param mixed $postid
    * @return boolean false on fail else $thumbid which is stored in post meta _thumbnail_id
    */
    public function create_localmedia_fromhttp( $url, $postid ){ 
        $photo = new WP_Http();
        $photo = $photo->request( $url );
     
        if(is_wp_error( $photo) ){  
            return false;
        }
           
        $attachment = wp_upload_bits( basename( $url ), null, $photo['body'], date( "Y-m", strtotime( $photo['headers']['last-modified'] ) ) );
               
        $file = $attachment['file'];
                
        // get filetype
        $type = wp_check_filetype( $file, null );
                
        // build attachment object
        $att = array(
            'post_mime_type' => $type['type'],
            'post_content' => '',
            'guid' => $url,
            'post_parent' => null,
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $attachment['file'] ) ),
        );
       
        // action insert attachment now
        $attach_id = wp_insert_attachment( $att, $file, $postid);
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id,  $attach_data );
        
        return $attach_id;
    }
    
    public function create_localmedia_fromlocalimages( $file_url, $post_id ){           
        require_once(ABSPATH . 'wp-load.php' );
        require_once(ABSPATH . 'wp-admin/includes/image.php' );
        global $wpdb, $csv2post_settings;
               
        if(!$post_id ) {
            return false;
        }

        //directory to import to 
        if( isset( $csv2post_settings['create_localmedia_fromlocalimages']['destinationdirectory'] ) ){   
            $artDir = $csv2post_settings['create_localmedia_fromlocalimages']['destinationdirectory'];
        }else{
            $artDir = 'wp-content/uploads/importedmedia/';
        }

        //if the directory doesn't exist, create it    
        if(!file_exists(ABSPATH . $artDir) ) {
            mkdir(ABSPATH . $artDir);
        }
        
        // get extension
        $ext = pathinfo( $file_url, PATHINFO_EXTENSION);
        
        // do we need to change the new filename to avoid existing files being overwritten?
        $new_filename = basename( $file_url); 

        if (@fclose(@fopen( $file_url, "r") )) { //make sure the file actually exists
            copy( $file_url, ABSPATH . $artDir . $new_filename);

            $siteurl = get_option( 'siteurl' );
            $file_info = getimagesize(ABSPATH . $artDir . $new_filename);

            //create an array of attachment data to insert into wp_posts table
            $artdata = array(
                'post_author' => 1, 
                'post_date' => current_time( 'mysql' ),
                'post_date_gmt' => current_time( 'mysql' ),
                'post_title' => $new_filename, 
                'post_status' => 'inherit',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_name' => sanitize_title_with_dashes(str_replace( "_", "-", $new_filename) ),                                            
                'post_modified' => current_time( 'mysql' ),
                'post_modified_gmt' => current_time( 'mysql' ),
                'post_parent' => $post_id,
                'post_type' => 'attachment',
                'guid' => $siteurl.'/'.$artDir.$new_filename,
                'post_mime_type' => $file_info['mime'],
                'post_excerpt' => '',
                'post_content' => ''
            );

            $uploads = wp_upload_dir();
            $save_path = $uploads['basedir'] . '/importedmedia/' . $new_filename;

            //insert the database record
            $attach_id = wp_insert_attachment( $artdata, $save_path, $post_id );

            //generate metadata and thumbnails
            if ( $attach_data = wp_generate_attachment_metadata( $attach_id, $save_path) ) {
                wp_update_attachment_metadata( $attach_id, $attach_data);
            }

            //optional make it the featured image of the post it's attached to
            $rows_affected = $wpdb->insert( $wpdb->prefix.'postmeta', array( 'post_id' => $post_id, 'meta_key' => '_thumbnail_id', 'meta_value' => $attach_id) );
        }else {
            return false;
        }

        return true;        
    }    
    
    /**
    * First function to adding a post thumbnail
    * 
    * @todo create_localmedia_fromlocalimages() needs to be used when image is already local
    * @param mixed $overwrite_existing, if post already has a thumbnail do we want to overwrite it or leave it
    */
    public function create_post_thumbnail( $post_id, $image_url, $overwrite_existing = false ){
        global $wpdb;

        if(!file_is_valid_image( $image_url) ){  
            return false;
        }
             
        // if post has existing thumbnail
        if( $overwrite_existing == false ){
            if ( get_post_meta( $post_id, '_thumbnail_id', true) || get_post_meta( $post_id, 'skip_post_thumb', true ) ) {
                return false;
            }
        }
        
        // call action function to create the thumbnail in wordpress gallery 
        $thumbid = self::create_localmedia_fromhttp( $image_url, $post_id );
        // or from create_localmedia_fromlocalimages()  
        
        // update post meta with new thumbnail
        if ( is_numeric( $thumbid) ) {
            update_post_meta( $post_id, '_thumbnail_id', $thumbid );
        }else{
            return false;
        }
    }
    
    /**
    * builds a url for form action, allows us to force the submission to specific tabs
    */
    public function form_action( $values_array = false ){
        $get_values = '';

        // apply passed values
        if(is_array( $values_array ) ){
            foreach( $values_array as $varname => $value ){
                $get_values .= '&' . $varname . '=' . $value;
            }
        }
        
        echo self::url_toadmin( $_GET['page'], $get_values);    
    }
    
    /**
    * count the number of posts in the giving month for the giving post type
    * 
    * @param mixed $month
    * @param mixed $year
    * @param mixed $post_type
    */
    public function count_months_posts( $month, $year, $post_type){                    
        $countposts = get_posts( "year=$year&monthnum=$month&post_type=$post_type");
        return count( $countposts);    
    }     
    
    /**
    * adds project id to sources for that project 
    */
    public function update_sources_withprojects( $project_id, $sourcesid_array ){
        global $wpdb;
        foreach( $sourcesid_array as $key => $soid ){
            $this->DB->update( $wpdb->c2psources, 'sourceid = ' . $soid, array( 'projectid' => $project_id) );
        }    
    }
    
    /**
    * Update data source in c2psources table: updates path value and the config array which holds path
    * 
    * @uses get_source
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.2
    *
    * @param mixed $path
    * @param mixed $sourceid
    * @param mixed $theconfig_array
    */
    public function update_source_path( $new_path, $sourceid ){
        global $wpdb;
        
        // get the source data
        $source_array = self::get_source( $sourceid );
                
        // update the configuration array also 
        $theconfig_array = maybe_unserialize( $source_array->theconfig );
        $theconfig_array['submittedpath'] = $new_path;
        $theconfig_array['fullpath'] = $new_path;
                
        return $this->DB->update( $wpdb->c2psources, "sourceid = $sourceid", array( 'path' => $new_path, 'theconfig' => maybe_serialize( $theconfig_array ) ) );            
    }

    /**
    * by default gets entire project row, specify specific field to return a single value
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.0.1
    * 
    * @param mixed $project_id
    * @param mixed $field
    */
    public function get_project( $project_id, $field = false ){
        global $wpdb; 
        $result = $this->DB->selectrow( $wpdb->c2pprojects, "projectid = $project_id", '*' );  
        if( $field === false || !is_string( $field) ){return $result;}
        return $result->$field;  
    } 
        
    /**
    * update specific columns in project table
    * 
    * @param mixed $project_id
    * @param mixed $fields an array of columns and new value i.e. array( 'categories' => maybe_serialize( $categories_array ) )
    */
    public function update_project( $project_id, $fields, $settingschange = false ){
        global $wpdb;
        
        // general timestamp, do not use this field for triggering post updating as it changes for the slightest thing
        $autofields = array( 'timestamp' => current_time( 'mysql' ) );
        
        // if project settings are being changed we store the time so the plugin knows which posts need to be updated
        if( $settingschange === true){$autofields['settingschange'] = current_time( 'mysql' );}
        
        $fields = array_merge( $fields, $autofields);
        return $this->DB->update( $wpdb->c2pprojects, "projectid = $project_id", $fields);   
    }
    
    /**
    * queries the projects datasources and puts all column names into an array with the table being the key
    * so that multi table projects can be used, however right now some of the plugin requires unique column names
    * 
    * @param mixed $project_id
    * @param mixed $from
    * @param boolean $apply_exclusions removes c2p columns in db method
    */
    public function get_project_columns_from_db( $project_id, $apply_exclusions = true ){
        if( !is_numeric( $project_id ) ){return false;}
                    
        global $wpdb;
                    
        $sourceid_array = array();// source id's from project table into an array for looping
        $final_columns_array = array();// array of all columns with table names as keys
        $queried_already = array();// track        
        
        // get project source id's and data treatment from the project table to apply that treatment to all sources 
        $project_row = $this->DB->selectrow( $wpdb->c2pprojects, "projectid = $project_id", 'datatreatment,source1,source2,source3,source4,source5' );
        
        // return the data treatment with columns to avoid having to query it again
        $final_columns_array['arrayinfo']['datatreatment'] = $project_row->datatreatment;
                      
        // put source id's into array because the rest of the function was already written before adding this approach
        $sourceid_array[] = $project_row->source1;
        if(!empty( $project_row->source2) ){$sourceid_array[] = $project_row->source2;}
        if(!empty( $project_row->source3) ){$sourceid_array[] = $project_row->source3;}
        if(!empty( $project_row->source4) ){$sourceid_array[] = $project_row->source4;}
        if(!empty( $project_row->source5) ){$sourceid_array[] = $project_row->source5;}
        
        // loop through source ID's
        foreach( $sourceid_array as $key => $source_id)
        {    
            // get the source row
            $row = $this->DB->selectrow( $wpdb->c2psources, 'sourceid = "' . $source_id . '"', 'path,tablename,thesep' );
            
            // user might have deleted source and $row is null - project contains sourceid for reference
            if( $row )
            {
                // avoid querying the same table twice to prevent a single table project being queried equal to number of sources
                if( !in_array( $row->tablename, $queried_already ) ){
                    $queried_already[] = $row->tablename;
                    $final_columns_array[ $row->tablename ] = $this->DB->get_tablecolumns( $row->tablename, true, true);
                    $final_columns_array[ 'arrayinfo' ][ 'sources' ][ $row->tablename ] = $source_id;
                }
            }
        }
              
        if( $apply_exclusions)
        {    
            // array of columns not to be used as column replacement tokens
            $excluded_array = array( 'c2p_changecounter', 'c2p_rowid', 'c2p_postid', 'c2p_use', 'c2p_updated', 'c2p_applied', 'c2p_categories', 'c2p_changecounter' );
            
            // loop through tables first
            foreach( $final_columns_array as $table_name => $columns_array ){
                // skip array of information
                if( $table_name !== 'arrayinfo' ) {        
                    // loop through the columns for $table_name
                    foreach( $columns_array as $numeric_key => $column ) {
                        if( in_array( $column, $excluded_array ) ) {
                            unset( $final_columns_array[ $table_name ][ $numeric_key ] );    
                        }
                    }    
                }
            }
        }       
            
        return $final_columns_array;
    }      
    
    /**
    * returns array of source ID for the giving project 
    */
    public function get_project_sourcesid( $project_id ){
        global $wpdb;
        $result = $this->DB->selectwherearray( $wpdb->c2pprojects, 'projectid = ' . $project_id, 'projectid', 'source1,source2,source3,source4,source5' );
        $id_array = array();
        for( $i=0;$i<=5;$i++){
            if(!empty( $result[0]["source$i"] ) && $result[0]["source$i"] !== '0' ){
                $id_array[] = $result[0]["source$i"];    
            }
        }        
        return $id_array;
    }  
    
    public function get_rules_array( $project_id ){
        global $wpdb;
        $query_results = $this->DB->selectwherearray( $wpdb->c2psources, 'projectid = ' . $project_id, 'sourceid', 'rules' );
        return array();
        $unser = maybe_unserialize( $query_results['rules'] );
        if(!is_array( $unser) ){
            return array();
        }else{
            return $unser;
        }
    }    
    
    /**
    * adds a new rule to the rules array in giving sources row in c2psources table
    * 
    * @param mixed $source_id
    * @param mixed $rule_type datatype,uppercaseall,lowercaseall,roundnumberup,roundnumberdown,captalizefirstletter
    * @param mixed $rule may be a single value i.e. a data type for the "datatype" $ruletype or it may be an array for a more complex rule
    */
    public function add_new_data_rule( $source_id, $rule_type, $rule, $column ){
        global $wpdb;
        $rules_array = $this->get_data_rules_source( $source_id );
        
        // add rule to array                                                          
        $rules_array[$rule_type][$column] = $rule;   
        
        // serialize 
        $rules_array = maybe_serialize( $rules_array );
                                               
        $this->DB->update( $wpdb->c2psources, "sourceid = $source_id", array( 'rules' => $rules_array ) );
    }
    
    public function delete_data_rule( $source_id, $rule_type, $column){
        global $wpdb;
        $rules_array = $this->get_data_rules_source( $source_id );
        unset( $rules_array[$rule_type][$column] );
        // serialize 
        $rules_array = maybe_serialize( $rules_array );
                                               
        $this->DB->update( $wpdb->c2psources, "sourceid = $source_id", array( 'rules' => $rules_array ) );            
    }   
    
    /**
    * returns an array of rules if any, can return empty array
    * 
    * @param numeric $source_id
    */
    public function get_data_rules_source( $source_id ){
        global  $wpdb;
        $row = $this->DB->selectrow( $wpdb->c2psources, "sourceid = $source_id", 'rules' );
        
        // initiate the rules array
        if(is_null( $row->rules) ){
            return array();
        }else{
            return maybe_unserialize( $row->rules );
        }                      
    }    
    
    /**
    * gets the rules column value (null or array ) for the parent source
    * 
    * use for join and append projects that have multiple sources going into one database table.
    * This is because the rule forms will submit only one table. In procedures where we loop through the 2nd and 3rd rules
    * we must compare columns to the rules array in the parent source.
    * 
    * @param mixed $project_id
    */
    public function get_parent_rulesarray( $project_id ){
        global $wpdb;                                                                                       
        $rules_array = $this->get_value( 'rules', $wpdb->c2psources, "projectid = $project_id AND parentfileid = 0" );
        return maybe_unserialize( $rules_array );
    }
    
    public function get_data_treatment( $project_id ){
        global $wpdb;
        return $this->DB->get_value( 'datatreatment', $wpdb->c2psources, "projectid = $project_id AND parentfileid = 0" );    
    }
    
    /**
    * import data from a single csv file
    * 
    * @param mixed $table_name
    * @param mixed $source_id
    * @param mixed $project_id
    */         
    public function import_from_csv_file( $source_id, $project_id, $event_type = 'import', $inserted_limit = 9999999 ){
        global $wpdb;
        // get source
        $source_row = $this->get_source( $source_id );
        
        // if $event_type == update then we reset progress
        if( $event_type == 'update' ){
            $source_row->progress = 0;
        } 
        
        // get rules array - if multiple source project + sources were joined then we must use 
        // the main source rules array, no matter how many files are added to multi-file project, 
        // we store rules in the main source unless individual tables are in use
        $rules_array = array();
        $datatreatment = $this->get_data_treatment( $project_id );
        if( $datatreatment == 'join' || $datatreatment == 'append' ){   
            $rules_array = $this->get_parent_rulesarray( $project_id );// one rules array applied to many sources
        }else{
            $rules_array = maybe_unserialize( $source_row->rules );// rule array per source (individual tables, also the default for single table users which is most common)    
        }
        
        // ensure file exists
        if( !file_exists( $source_row->path ) ) {
            $this->UI->create_notice( "Please ensure the .csv file at the following path still exists. The plugin failed to find it.
            If you see an invalid path, please contact WebTechGlobal so we can correct it: " . $source_row->path, 
            'success', 'Small', __( '.csv File Not Located' ) );        
            return;    
        }
                             
        //$source_object = $this->DB->selectrow( $table_name, ' )
        $file = new SplFileObject( $source_row->path );
            
        // put headers into array, we will use the key while processing each row, by doing it this way 
        // it should be possible for users to change their database and not interfere this procedure
        $original_headers_array = array();
        
        $rows_looped = 0;
        $inserted = 0;
        $updated = 0;
        $voidbaddata = 0;
        $voidduplicate = 0;
        
        // if rows already exist, change to false
        // if it stays true and any rows are updated, it means a duplicate ID value exists in the .csv file
        // we will let the user know with a message and create flags for duplicate rows
        $firsttimeimport = true; 
        $rows_exist = $this->DB->count_rows( $source_row->tablename );
        if( $rows_exist){$firsttimeimport = false;}
        
        while ( !$file->eof() ) {      
            $insertready_array = array();
            $currentcsv_row = $file->fgetcsv( $source_row->thesep, '"' );
    
            // set an array of headers for keeps, we need it to build insert query and rules checking
            if( $rows_looped == 0){
                $original_headers_array = $currentcsv_row;
                // create array of mysql ready headers
                $cleaned_headers_array = array();
                foreach( $original_headers_array as $key => $origheader ){
                    $cleaned_headers_array[] = $this->PHP->clean_sqlcolumnname( $origheader );    
                }                
                ++$rows_looped;
                continue;
            }
            
            // skip rows until $rows_looped == progress
            if( $rows_looped < $source_row->progress ){
                continue;
            }
                       
            // build insert part of query - loop through values, build a new array with columns as the key
            foreach( $currentcsv_row as $key => $value ){
                $insertready_array[$cleaned_headers_array[$key]] = $value;
            }
                         
            // does the row id value already exist in table, if it does we do not perform insert
            $exists = false;
            if( isset( $source_row->idcolumn ) && !empty( $source_row->idcolumn ) ){
                $exists = $this->DB->selectrow( $source_row->tablename, $source_row->idcolumn . ' = ' . $insertready_array[$source_row->idcolumn], 'c2p_rowid' );
            }
            
            if( $exists){
                $row_id = $exists->c2p_rowid;
                
                // flag the row
                if( $firsttimeimport ){
                    $flagthisrow = new C2P_Flags();
                    $flagthisrow->title = 'First Data Import: Duplicate ID Detected';
                    $flagthisrow->content = __( "During a first time data import the same
                    value was encountered a second time in your .csv file. The second row
                    will automatically update the first row and so the first will not have been
                    imported. That is fine if the entire row is a duplicate otherwise you need to
                    correct this problem. <br> Project ID: $project_id <br> Data Source ID: $source_id <br> Row ID: $row_id
                    <br><br>
                    The ID value that should be unique per row but appears to be duplicate is: " . $insertready_array[$source_row->idcolumn] );
                    $flagthisrow->newflag();
                }
                
                ++$updated; 
            }else{
                // insert the row
                $row_id = $this->DB->insert( $source_row->tablename, $insertready_array );
                ++$inserted;     
            }
         
            // apply rules
            if(!empty( $rules_array ) ){      
                $currentcsv_row = $this->apply_rules( $insertready_array, $rules_array, $row_id );
            }else{                          
                $currentcsv_row = $insertready_array;
            }

            // update row
            $this->DB->update( $source_row->tablename, "c2p_rowid = $row_id", $currentcsv_row);

            ++$rows_looped;   
            
            if( $inserted >= $inserted_limit){
                break;
            }                   
        }      
        
        // update the source with progress
        $total_progress = $source_row->progress + $inserted + $updated;// update request resets progress and so updates still count towards progress 
     
        $this->DB->update( $wpdb->c2psources, "sourceid = $source_id", array( 'progress' => $total_progress) );
        
        if( $source_row->progress == $total_progress){
            $this->UI->create_notice( __( "All rows have already been imported according to the progress counter. If you wish to re-import due to your file being updated please click Update Data."), 'success', 'Small', __( 'Source Fully Imported' ) );            
        }else{    
            if( $event_type == 'import' ){    
                $this->UI->create_notice( "A total of $rows_looped .csv file rows were processed (including header). 
                $inserted rows were inserted to $source_row->tablename and $updated were updated. This event 
                may not import every row depending on your settings. Click import again to ensure all rows are 
                imported.", 'success', 'Small', __( 'Data Import Event Finished' ) );
            }elseif( $event_type == 'update' ){
                $this->UI->create_notice( "A total of <strong>$rows_looped</strong> .csv file rows were processed (including header). <strong>$inserted</strong> rows were inserted
                to <strong>$source_row->tablename</strong> and <strong>$updated</strong> were updated. This event processes the entire source, all rows should now be
                in your projects database table.", 'success', 'Small', __( 'Data Update Event Finished' ) );            
            }
        }
        
        if( $firsttimeimport && $updated !== 0){
            // if this was a first time update yet a row or more was updated we tell the user they have duplicate ID values
            $this->UI->create_notice( __( "The plugin has detected $updated with duplicate ID values. This may be duplicate rows
            or rows that share the same ID. This needs to be corrected before continuing. Either start over or run an update
            on the data already imported to correct the problem."), 'warning', 'Small',
            'Duplicate Rows/ID In .CSV File' );
        }
    }
    
    /**
    * applies data rules to a single array imported from csv file
    * 1. data source is used to get row from csv file, so we know we are working with the rules for that file + table
    * 2. rules array is for a single data source and a single data source is for a single file
    * 
    * @param mixed $insertready_array
    * @param mixed $rules_array
    */
    public function apply_rules( $insertready_array, $rules_array, $row_id){
        global $wpdb;    

        foreach( $insertready_array as $column => $value ){

            if( isset( $rules_array['splitter'] )
            && isset( $rules_array['splitter']['datasplitertable'] ) 
            && $rules_array['splitter']['datasplitercolumn'] == $column){
                
                $table = $rules_array['splitter']['datasplitertable'];
                
                $exploded = explode( $rules_array['splitter']['separator'], $value,5);
                
                for( $i=1;$i<=count( $exploded);$i++){
                    $receiving_column = $rules_array['splitter']["receivingcolumn$i"];
                    $x = $i - 1;
                    $category_array[$receiving_column] = $exploded[$x];
                }              

                $this->DB->update( $table, "c2p_rowid = $row_id", $category_array );
            } 
                        
            // round number up (roundnumberupcolumns)
            if( isset( $rules_array['roundnumberupcolumns'] ) && isset( $rules_array['roundnumberupcolumns'][$column] ) ){
                if(is_numeric( $value ) ){
                    $value = ceil( $value ); 
                }    
            }
            
            // round number (roundnumbercolumns)
            if( isset( $rules_array['roundnumbercolumns'] ) && isset( $rules_array['roundnumbercolumns'][$column] ) ){
                if(is_numeric( $value ) ){
                    $value = round( $value ); 
                }                
            }
                        
            // make first character uppercase (captalizefirstlettercolumns) 
            if( isset( $rules_array['captalizefirstlettercolumns'] ) && isset( $rules_array['captalizefirstlettercolumns'][$column] ) ){
                $value = ucwords( $value );    
            }
                    
            // make entire string lower case (lowercaseallcolumns)
            if( isset( $rules_array['lowercaseallcolumns'] ) && isset( $rules_array['lowercaseallcolumns'][$column] ) ){
                $value = strtolower( $value );    
            }
                        
            // make entire string upper case (uppercaseallcolumns)
            if( isset( $rules_array['lowercaseallcolumns'] ) && isset( $rules_array['lowercaseallcolumns'][$column] ) ){
                $value = strtoupper( $value );        
            }
            
            if( isset( $rules_array['pluralizecolumns'] ) && isset( $rules_array['pluralizecolumns'][$column] ) ){
                $value = self::pluralize(2, $value, false );            
            }
           
            // update the main array
            $insertready_array[$column] = $value;
        }
       
        return $insertready_array;
    }
    
    /**
    * gets a single row from c2psources table, returns query result
    * 
    * @uses $this->DB->selectrow()
    * 
    * @param mixed $project_id
    * @param mixed $source_id
    */
    public function get_source( $source_id ){
        global $wpdb;
        return $this->DB->selectrow( $wpdb->c2psources, "sourceid = $source_id", '*' );
    }

    public function get_parentsource_id( $project_id ){
        global $wpdb;                                                                                       
        return $this->DB->get_value( 'sourceid', $wpdb->c2psources, "projectid = $project_id AND parentfileid = 0");
    }    
    
    public function get_project_main_table( $project_id ){
        global $wpdb;                                                                                       
        return $this->DB->get_value( 'tablename', $wpdb->c2psources, "projectid = $project_id AND parentfileid = 0");    
    }    
    
    public function does_project_table_exist( $project_id ){
        $table_name = self::get_project_main_table( $project_id );   
        return $this->DB->does_table_exist( $table_name );     
    }
    
    /**
    * gets imported rows not used to create posts, uses method to select data from multiple tables based on an ID field
    * 
    * @param mixed $project_id
    */
    public function get_unused_rows( $project_id, $total = 1, $idcolumn = false ){
        $tables_array = self::get_dbtable_sources( $project_id);       
        return $this->DB->query_multipletables( $tables_array, $idcolumn, 'c2p_postid = 0', $total );
    }
    
    /**
    * query project rows which have changed but not yet been applied to their post
    * 
    * @param mixed $project_id
    * @param mixed $total
    */
    public function get_updated_rows( $project_id, $total = 1, $idcolumn = false ){
        $tables_array = self::get_dbtable_sources( $project_id);
        return $this->DB->query_multipletables( $tables_array, $idcolumn, 'c2p_postid != 0 AND c2p_updated > c2p_applied', $total);
    }   
    
    /**
    * get specific number of updated rows that have not been applied to their post since
    * a projects settings has been changed 
    *        
    * @param mixed $project_id
    * @param mixed $total
    * @param mixed $idcolumn
    */
    public function get_outdatedpost_rows( $project_id, $total = 1, $idcolumn = false ){
        $tables_array = self::get_dbtable_sources( $project_id); 
        $settingschange = self::get_project( $project_id, 'settingschange' );   
        return $this->DB->query_multipletables( $tables_array, $idcolumn, "c2p_postid != 0 AND '".$settingschange."' > c2p_applied", $total);
    }
    
    /**
    * returns an array of database table names ONLY for the projects database table type data sources 
    */
    public function get_dbtable_sources( $project_id ){
        global $wpdb;
        
        // get projects data source ID's
        $sourceid_array = self::get_project_sourcesid( $project_id );
        
        // create an array for storing table names
        $tables_added = array();

        // loop through source ID's and get tablename from source row
        foreach( $sourceid_array as $key => $source_id){

            $row = $this->DB->selectrow( $wpdb->c2psources, 'sourceid = "' . $source_id . '"', 'tablename,idcolumn' );

            // avoid using the same table twice
            if(in_array( $row->tablename, $tables_added) ){
                continue;
            }
            
            $tables_added[] = $row->tablename;       
        }    
        return $tables_added;
    }
    
    /**
    * Create new posts/pages
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.2
    * 
    * @param mixed $project_id
    * @param mixed $total - apply a limit for this import (global settings can offer a default limit suitable for server also)
    * @param mixed $row_ids - only use if creating posts using specific row ID's 
    */
    public function create_posts( $project_id, $total = 1 ){
        global $csv2post_settings;
        
        $autoblog = new CSV2POST_InsertPost();
        $autoblog->settings = $csv2post_settings;
        $autoblog->currentproject = $csv2post_settings['currentproject'];
        $autoblog->project = $this->get_project( $project_id );// gets project row, includes sources and settings
        $autoblog->projectid = $project_id;
        $autoblog->maintable = self::get_project_main_table( $project_id );// main table holds post_id and progress statistics
        $autoblog->projectsettings = maybe_unserialize( $autoblog->project->projectsettings );// unserialize settings
        $autoblog->projectcolumns = $this->get_project_columns_from_db( $project_id );
        $autoblog->requestmethod = 'manual';
        $sourceid_array = self::get_project_sourcesid( $project_id );
        $autoblog->mainsourceid = $sourceid_array[0];// update the main source database table per post with new post ID
        unset( $autoblog->project->projectsettings);// just simplifying the project object by removing the project settings

        $idcolumn = false;
        if( isset( $autoblog->projectsettings['idcolumn'] ) ){
            $idcolumn = $autoblog->projectsettings['idcolumn'];    
        }
        
        // get rows not used to create posts
        $unused_rows = $this->get_unused_rows( $project_id, $total, $idcolumn );  
        if(!$unused_rows){
            $this->UI->create_notice( __( 'You have used all imported rows to create posts. Please ensure you have imported all of your data if you expected more posts than CSV 2 POST has already created.' ), 'info', 'Small', 'No Rows Available' );
            return;
        }
        
        // we will control how and when we end the operation
        $autoblog->finished = false;// when true, output will be complete and foreach below will discontinue, this can be happen if maximum execution time is reached
        
        $foreach_done = 0;
        foreach( $unused_rows as $key => $row){
            ++$foreach_done;
                    
            // to get the output at the end, tell the class we are on the final post, only required in "manual" requestmethod
            if( $foreach_done == $total){    
                $autoblog->finished = true;// not completely finished, indicates this is the last post
            }
            
            // pass row to $autob
            $autoblog->row = $row;    
            // create a post - start method is the beginning of many nested functions
            $autoblog->start();
        }
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'postsevent',
            'action' => __( "Up to $foreach_done posts may have been created.", 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );

        self::newlog( $atts );
    }
    
    /**
    * Update one or more posts
    * 1. can pass a post ID and force update even if imported row has not changed
    * 2. Do not pass a post ID and query is done to get changed imported rows only to avoid over processing
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.0.2
    * 
    * @param integer $project_id
    * @param integer $total
    * @param mixed $post_id boolean false or integer post ID
    * @param array $atts
    */
    public function update_posts( $project_id, $total = 1, $post_id = false, $atts = array() ){
        global $csv2post_settings;
        
        extract( shortcode_atts( array( 
            'rows' => false
        ), $atts ) );
                
        $autoblog = new CSV2POST_UpdatePost();
        $autoblog->settings = $csv2post_settings;
        $autoblog->currentproject = $csv2post_settings['currentproject'];// dont automatically use by default, request may be for specific project
        $autoblog->project = self::get_project( $project_id);// gets project row, includes sources and settings
        $autoblog->projectid = $project_id;
        $autoblog->maintable = self::get_project_main_table( $project_id);// main table holds post_id and progress statistics
        $autoblog->projectsettings = maybe_unserialize( $autoblog->project->projectsettings);// unserialize settings
        $autoblog->projectcolumns = self::get_project_columns_from_db( $project_id);
        $autoblog->requestmethod = 'manual';
        $sourceid_array = self::get_project_sourcesid( $project_id);
        $autoblog->mainsourceid = $sourceid_array[0];// update the main source database table per post with new post ID
        unset( $autoblog->project->projectsettings);// simplifying the object by removing the project settings

        // we will control how and when we end the operation
        $autoblog->finished = false;// when true, output will be complete and foreach below will discontinue, this can be happen if maximum execution time is reached
        
        $idcolumn = false;
        if( isset( $autoblog->projectsettings['idcolumn'] ) ){
            $idcolumn = $autoblog->projectsettings['idcolumn'];    
        }
                               
        // get rows updated and not yet applied, this is a default query
        // pass a query result to $updated_rows to use other rows
        if( $post_id === false ){
            $updated_rows = self::get_updated_rows( $project_id, $total, $idcolumn);
        }else{
            $updated_rows = self::get_posts_record( $project_id, $post_id, $idcolumn);
        }
        
        if( !$updated_rows ){
            $this->UI->create_notice( __( 'None of your imported rows have been updated since their original import.' ), 'info', 'Small', 'No Rows Updated' );
            return;
        }
            
        $foreach_done = 0;
        foreach( $updated_rows as $key => $row){
            ++$foreach_done;
                        
            // to get the output at the end, tell the class we are on the final post, only required in "manual" requestmethod
            if( $foreach_done == $total){
                $autoblog->finished = true;
            }            
            // pass row to $autob
            $autoblog->row = $row;    
            // create a post - start method is the beginning of many nested functions
            $autoblog->start();
        } 
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'postsevent',
            'action' => __( "Up to $foreach_done posts may have been updated.", 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );

        self::newlog( $atts );                 
    }
    
    /**
    * determines if the giving term already exists within the giving level
    * 
    * this is done first by checking if the term exists in the blog anywhere at all, if not then it is an instant returned false.
    * if a match term name is found, then we investigate its use i.e. does it have a parent and does that parent have a parent. 
    * we count the number of levels and determine the existing terms level
    * 
    * if term exists in level then that terms ID is returned so that we can make use of it
    * 
    * @param mixed $term_name
    * @param mixed $level
    * 
    * @deprecated C2P_Categories class created
    */
    public function term_exists_in_level( $term_name = 'No Term Giving', $level = 0){                 
        global $wpdb;
        $all_terms_array = $this->DB->selectwherearray( $wpdb->terms, "name = '$term_name'", 'term_id', 'term_id' );
        if(!$all_terms_array ){return false;}

        $match_found = false;
                
        foreach( $all_terms_array as $key => $term_array ){
                     
            $term = get_term( $term_array['term_id'], 'category',ARRAY_A);

            // if level giving is zero and the current term does not have a parent then it is a match
            // we return the id to indicate that the term exists in the level
            if( $level == 0 && $term['parent'] === 0){      
                return $term['term_id'];
            }
             
            // get the current terms parent and the parent of that parent
            // keep going until we reach level one
            $toplevel = false;
            $looped = 0;    
            $levels_counted = 0;
            $parent_termid = $term['parent'];
            while(!$toplevel){    
                                
                // we get the parent of the current term
                $category = get_category( $parent_termid );  

                if( is_wp_error( $category )|| !isset( $category->category_parent ) || $category->category_parent === 0){
                    
                    $toplevel = true;
                    
                }else{ 
                    
                    // term exists and must be applied as a parent for the new category
                    $parent_termid = $category->category_parent;
                    
                }
                      
                ++$looped;
                if( $looped == 20){break;}
                
                ++$levels_counted;
            }  
            
            // so after the while we have a count of the number of levels above the "current term"
            // if that count + 1 matches the level required for the giving term term then we have a match, return current term_id
            $levels_counted = $levels_counted;
            if( $levels_counted == $level){
                return $term['term_id'];
            }       
        }
                  
        // arriving here means no match found, either create the term or troubleshoot if there really is meant to be a match
        return false;
    }
    
    /**
    * call to process all methods of spinning on a string
    * 
    * @param mixed $content
    */
    public function spin( $content){
        $content = $this->spinner_brackets( $content);
        return $content;
    }
    
    public function spinner_brackets( $content){
        $mytext = $content;
        while( $this->PHP->stringinstring_using_strpos( "}", $mytext) ){
            $rbracket = strpos( $mytext, "}",0);
            $tString = substr( $mytext,0, $rbracket);
            $tStringToken = explode( "{", $tString);
            $tStringCount = count( $tStringToken) - 1;
            $tString = $tStringToken[$tStringCount];
            $tStringToken = explode( "|", $tString);
            $tStringCount = count( $tStringToken) - 1;
            $i = rand(0, $tStringCount);
            $replace = $tStringToken[$i];
            $tString = "{".$tString."}";
            $mytext = $this->PHP->str_replaceFirst( $tString, $replace, $mytext);
        }
        
        $content = $mytext;

        // set our start and stop characters
        $start_string ='{';
        $stop_string = '}';
        
        // preg match all possible spinners, putting them into $strings array
        preg_match_all( '/' . $start_string. '(.*)' . $stop_string . '/Usi' , $content, $strings);
        
        // count through loop, used to replace the entire string including brackets
        $count = 0; 
        foreach( $strings[1] as &$value ){
            $explodePhrase = explode( "|", $value );// $value is the string without brackets so we explode that
            $key = array_rand( $explodePhrase);// we get one random value
            // $strings includes both with and without brackets. We use $coutn to str_replace the brackets version in our content
            $content = str_replace( $strings[0][$count], $explodePhrase[$key], $content);                 
            ++$count;
        } 
        
        return $content;    
    }  

    /**
    * Deletes a datasource from the wp_c2psources table
    * 
    * @returns the result from WP delete query
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function delete_datasource( $source_id ) {
        global $wpdb;
        return $this->DB->delete( $wpdb->c2psources, 'sourceid = ' . $source_id );    
    }
    
    /**
    * checks for a new .csv file and can switch to that file for import.
    * 
    * This function is called during a manual request. Another method named detect_new_files()
    * is now being written. It will be a simplier version of this that focuses on dealing with new
    * files, not dealing with potential issues detected. I will start putting those checks into
    * individual methods.
    * 
    * @returns array $result_array outcome is boolean, false means no changes detected, true means there was new file or existing file modified
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function recheck_source_directory( $source_id, $switch = false ) {       
        
        $result_array = array( 'outcome' => false,// boolean, change to true to indicate a change detected  
                               'error' => false,// boolean, not PHP error, this is for a problem with the directory 
                               'message' => __( 'No message.', 'csv2post'),
        );
        
        // get source data
        $source_row = self::get_source( $source_id );
        if( !$source_row ) { 
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = __( 'Source data not found, this should be reported to WebTechGlobal.', 'csv2post' );
            return $result_array;
        }
        
        // ensure we have a clean path
        $directory_path = trailingslashit( $source_row->directory );
        
        // it would be best to check this before now, for the users attention
        // but lets ensure the directory column has a value
        if( !isset( $directory_path ) || !is_string( $directory_path ) ) {
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = __( 'Source data does not include a directory value. This would happen in versions before 8.1.32 and is a minor issue WebTechGlobal can help you with.', 'csv2post' );
            return $result_array; 
        }
        
        // get the newest file in the directory - currently this is done based on last change
        // time, which means changing an old file would cause a switch, should not happen though
        // but it might serve someone
        $newest_file = $this->Files->directories_newest_file( $directory_path, 'csv' );
                   
        // get basename for the current main file, that is just the file in the "path" value now
        $current_main_file = basename( $source_row->path );
          
        // if current main file is still the newest nothing needs to be done          
        if( $newest_file === $current_main_file ) {
            $result_array['outcome'] = false;  
            $result_array['error'] = false;// normal operation should lead to a green notice  
            $result_array['message'] = __( 'Your sources directory does not have a new file since CSV 2 POST last checked.', 'csv2post' );  
            
            // log
            $atts = array(
                'outcome' => 1,
                'line' => __LINE__, 
                'function' => __FUNCTION__,
                'file' => __FILE__, 
                'userid' => get_current_user_id(),          
                'type' => 'general',
                'category' => 'dataevent',
                'action' => __( "Source directory has been checked for new files but none found for source ID $source_id.", 'csv2post' ),
                'priority' => 'normal',                        
                'triga' => 'manualrequest'
            );

            self::newlog( $atts );
        
            return $result_array;          
        }

        // arriving here indicates there is a newer file in play, do we switch to the new file ?
        if( $switch ) {
            // before the switch, must ensure newer file configuration matches the old
            $comparison_results = $this->Files->compare_csv_files( $directory_path . $newest_file, $directory_path . $current_main_file );
        
            if( $comparison_results['outcome'] = true ) { 
                // apply the new path as the current/primary document for processing
                self::update_source_path( $directory_path . $newest_file, $source_id ); 
                $result_array['outcome'] = true;  
                $result_array['error'] = false;// normal operation should lead to a green notice  
                $result_array['message'] = __( 'A new .csv file was found in your sources directory. The plugin has switched to the new file', 'csv2post' );                                                    
            
                // log
                $atts = array(
                    'outcome' => 1,
                    'line' => __LINE__, 
                    'function' => __FUNCTION__,
                    'file' => __FILE__, 
                    'userid' => get_current_user_id(),          
                    'type' => 'general',
                    'category' => 'dataevent',
                    'action' => __( "Source directory check discovered a newer .csv file for source ID $source_id. The new file has been made the primary file.", 'csv2post' ),
                    'priority' => 'normal',                        
                    'triga' => 'manualrequest'
                );

                self::newlog( $atts );            
            }
        }    

        return $result_array;
    }              

    /**
    * Checks all data source directories for new files (not updated,changed files).
    * 
    * On finding new file will process, adding information about the file to the
    * database and may begin data import. 
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.37
    * @version 1.0
    */
    public function detect_new_files() {
        global $csv2post_settings;
        if( !isset( $csv2post_settings['standardsettings']['detectnewfiles'] ) || $csv2post_settings['standardsettings']['detectnewfiles'] !== true ) {
            return;    
        }
        
        // get all csv source ID and directory
        $sources_result = $this->DB->selectwherearray( $wpdb->c2psources, 'sourcetype = "localcsv"', 'timestamp', 'sourceid, directory', 'ARRAY_A', null );
        if( !$sources_result ){ return; }
        
        
        
        
    }

    /**
    * Get two or more projects - use get_project() for a single project.
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.2.0
    * @version 1.0
    */
    public function get_projects() {
        global $wpdb;
        return $this->DB->selectorderby( $wpdb->c2pprojects, null, 'projectid', '*', null, 'ARRAY_A' );
    }        
}// end CSV2POST class 

if(!class_exists( 'WP_List_Table' ) ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
* Use to insert a new post, class systematically calls all methods one after the other building the $my_post object
* 
* 1. methods are in alphabetical order
* 2. each method calls the next one in the list
* 3. eventually a method creates the post using the $my_post object built along the way
* 4. $my_post is then used to add custom fields, thumbnail/featured image and other attachments or meta
* 5. many methods check for their target value to exist in $my_post already and instead alter it (meaning we can re-call the class on an object)
* 6. some methods check for values in the $my_post object and perform procedures based on the values found or not found
* 
* @author Ryan Bayne
* @package CSV 2 POST
* @since 8.0.0
* @version 2.1.10
*/
class CSV2POST_InsertPost{
    public $my_post = array();
    public $report_array = array();// will include any problems detected, can be emailed to user    
    
    public function replace_tokens( $subject ){
        unset( $this->projectcolumns['arrayinfo'] );
        foreach( $this->row as $columnfromquery => $usersdata ){ 
            foreach( $this->projectcolumns as $table_name => $columnfromdb ){   
                $subject = str_replace( '#'. $columnfromquery . '#', $usersdata, $subject);
            }    
        }         
        return $subject;
    } 
    
    /**
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function __construct() {
        $this->CSV2POST = new CSV2POST();
    }
    
    /**
    * This is not like a __construct() - this called to create a single post,
    * often while looping through data
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */
    public function start() { 
        $this->my_post = array();  
        $this->replacevaluerules();
    }
    
    /**
    * rules for replacing specific values in selected columns with other content 
    */
    public function replacevaluerules() {
      
        if( isset( $this->projectsettings['content']['valuerules'] ) && is_array( $this->projectsettings['content']['valuerules'] ) ){
            
            foreach( $this->projectsettings['content']['valuerules'] as $key => $rule_array ){
                               
                // if we do not have all the values we require in this rule just continue to the next item
                if(!isset( $rule_array['column'] ) ){continue;}
                if(!isset( $rule_array['vrvdatavalue'] ) ){continue;}             
                if(!isset( $rule_array['vrvreplacementvalue'] ) ){continue;}                         
                       
                // ensure the column is set in the $row
                if( isset( $this->row[ $rule_array['column'] ] ) ){     
                    // does our value match the data         
                    if( $this->row[ $rule_array['column'] ] == $rule_array['vrvdatavalue'] ){       
                        $this->row[ $rule_array['column'] ] = $rule_array['vrvreplacementvalue'];
                    }
                }    
            }              
        }        

        $this->author();
    }
    
    /**
    * decide author (from data or a default author)
    */
    public function author() {      
        $author_set = false;
        
        // check projects author settings for author data          
        if( isset( $this->projectsettings['authors']['email']['column'] ) && !empty( $this->projectsettings['authors']['email']['column'] )
        && isset( $this->projectsettings['authors']['username']['column'] ) && !empty( $this->projectsettings['authors']['username']['column'] ) ){
            // check data $row for a numeric value
            if( isset( $this->row[ $this->projectsettings['authors']['email']['column'] ] ) && is_numeric( $this->row[ $this->projectsettings['authors']['email']['column'] ] ) ){
                $this->my_post['post_author'] = $this->row[ $this->projectsettings['authors']['email']['column'] ];
                $author_set = true;
            }
        }
                                   
        // if not $author_set check for a project default author
        if( !$author_set && isset( $this->projectsettings['basicsettings']['defaultauthor'] ) && is_numeric( $this->projectsettings['basicsettings']['defaultauthor'] ) ){
            $this->my_post['post_author'] = $this->projectsettings['basicsettings']['defaultauthor']; 
        }
        
        // call next method
        $this->categories();
    } 
    
    /**
    * Category creator
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */
    public function categories() {
        if( isset( $this->projectsettings['categories'] ) ){  
            if( isset( $this->projectsettings['categories']['data'] ) ){
                
                $projection_array = array();
                $group_array = array();// we will store each cat ID in this array (use to apply parent and store in project table)
                $level = 0;// in theory this should increment, so that is a good check to do when debugging
                
                // loop through all values in $row
                unset( $this->projectcolumns['arrayinfo'] );
                
                // loop through all columns/values in the row of data
                foreach( $this->row as $columnfromquery => $my_term ){ 
                    
                    // loop through all project columns
                    foreach( $this->projectcolumns as $table_name => $columnfromdb ){
               
                        // determine if $columnfromquery and $table_name are selected category data columns
                        // also establish the level by using array of category column data 
                        foreach( $this->projectsettings['categories']['data'] as $thelevel => $catarray ){
                            
                            if( $catarray['table'] == $table_name && $catarray['column'] == $columnfromquery ){
                                $level = $thelevel;
                    
                                // is post manually mapped to this table + column + term
                                if( isset( $categories_array['categories']['mapping'][$table_name][$columnfromdb][ $my_term ] ) ){
                                    
                                    // we apply $level here, this is important in this procedure because the order we encounter category terms
                                    // within data may not be in the level order, possibly!
                                    $group_array[$level] = $categories_array['categories']['mapping'][$table_name][$columnfromdb][ $my_term ];
                                
                                }else{
                                    
                                    // does term exist within the current level?  
                                    $existing_term_id = $this->CSV2POST->term_exists_in_level( $my_term, $level );                                              
                                    if(is_numeric( $existing_term_id) ){
                                        
                                        $group_array[$level] = $existing_term_id;
                                        
                                    }else{
                                        
                                        // set parent id, by deducting one from the current $level and getting the previous category from $group_array
                                        $parent_id = 0;
                                        if( $level > 0 ){
                                            $parent_keyin_group = $level - 1;
                                            
                                            if( !isset( $group_array[$parent_keyin_group] ) ) {
                                                $parent_id = 0;// $parent_keyin_group encounter no in array, possibly level one or third or fourth when second level no selected    
                                            } else {
                                                $parent_id = $group_array[$parent_keyin_group];    
                                            }
                                               
                                        }
                                        
                                        // create a new category term with a parent (if first category it parent is 0)
                                        $new_cat_id = wp_create_category( $my_term, $parent_id );
                                          
                                        if( isset( $new_cat_id ) && is_numeric( $new_cat_id ) ){
                                            $group_array[$level] = $new_cat_id;    
                                        }
                                    }
                                }                             
                            }
                        }
                    }    
                }
            }
            
            if( isset( $group_array ) && is_array( $group_array ) ){
                $this->my_post['post_category'] = $group_array;
            }    
                     
        } 
        
        // apply default category if post has none
        if( empty( $this->my_post['post_category'] ) && isset( $this->projectsettings['basicsettings']['defaultcategory'] ) && is_numeric( $this->projectsettings['basicsettings']['defaultcategory'] ) ) {
            $this->my_post['post_category'] = array( $this->projectsettings['basicsettings']['defaultcategory'] );
        }  
   
        // call next method
        $this->commentstatus();
    }  
      
    public function commentstatus() {
        
        if( isset( $this->projectsettings['basicsettings']['commentstatus'] ) && is_numeric( $this->projectsettings['basicsettings']['commentstatus'] ) ){
            $this->my_post['comment_status'] = $this->projectsettings['basicsettings']['commentstatus']; 
        }
                
        // call next method
        $this->content();        
    }   
     
    public function content() {
        
        if( isset( $this->projectsettings['content']['wysiwygdefaultcontent'] ) ){
            $this->my_post['post_content'] = $this->replace_tokens( $this->projectsettings['content']['wysiwygdefaultcontent'] );    
        }    
        
        if(!isset( $this->my_post['post_content'] ) || empty( $this->my_post['post_content'] ) ){
            $this->my_post['post_content'] = __( 'No Post Content Setup' );    
        }
                
        // call next method
        $this->customfields();        
    } 
           
    /**
    * does not create meta data, only establishes what meta data is to be created and it is created later
    * it is done this way so that rules can take meta into consideration and meta can be adjusted prior to post creation 
    */
    public function customfields() {
        $this->my_post['newcustomfields'] = array();// we will add keys and values to this, the custom fields are created later    
        $i = 0;// count number of custom fields, use it as array key
        
        // seo title meta
        if( isset( $this->projectsettings['customfields']['seotitletemplate'] ) && !empty( $this->projectsettings['customfields']['seotitletemplate'] )
        && isset( $this->projectsettings['customfields']['seotitlekey'] ) && !empty( $this->projectsettings['customfields']['seotitlekey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seotitletemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seotitlekey']; 
            ++$i; 
        }
        
        // seo description meta
        if( isset( $this->projectsettings['customfields']['seodescriptiontemplate'] ) && !empty( $this->projectsettings['customfields']['seodescriptiontemplate'] )
        && isset( $this->projectsettings['customfields']['seodescriptionkey'] ) && !empty( $this->projectsettings['customfields']['seodescriptionkey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seodescriptiontemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seodescriptionkey'];  
            ++$i; 
        }
                
        // seo keywords meta
        if( isset( $this->projectsettings['customfields']['seokeywordstemplate'] ) && !empty( $this->projectsettings['customfields']['seokeywordstemplate'] )
        && isset( $this->projectsettings['customfields']['seokeywordskey'] ) && !empty( $this->projectsettings['customfields']['seokeywordskey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seokeywordstemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seokeywordskey']; 
            ++$i;       
        }       
        
        // now add all other custom fields
        if( isset( $this->projectsettings['customfields']['cflist'] ) ){
            foreach( $this->projectsettings['customfields']['cflist'] as $key => $cf){
                $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $cf['value'] );
                $this->my_post['newcustomfields'][$i]['name'] = $cf['name'];                
                ++$i;     
            }
        }

        // call next method
        $this->poststatus();        
    }
    
    public function poststatus() {
        
        if( isset( $this->projectsettings['basicsettings']['poststatus'] ) ){
            $this->my_post['post_status'] = $this->projectsettings['basicsettings']['poststatus'];
        }else{
            $this->my_post['post_status'] = 'draft';
        }
        
        // call next method
        $this->excerpt();        
    }
    
    public function excerpt() {
        
        // call next method
        $this->format();         
    }  
      
    public function format() {
        
       if( isset( $this->projectsettings['postformat'] ) ){
            if( isset( $this->projectsettings['postformat']['title_table'] ) && isset( $this->projectsettings['postformat']['title_column'] ) ){
                if( isset( $record_array[ $this->projectsettings['postformat']['title_column'] ] ) && is_string( $record_array[ $this->projectsettings['postformat']['title_column'] ] ) ){
                    wp_set_post_terms( $this->my_post['ID'], 'post-format-'.$record_array[ $this->projectsettings['postformat']['title_column'] ], 'post_format' ); 
                }   
            }elseif( isset( $this->projectsettings['postformat']['default'] ) ){
                wp_set_post_terms( $this->my_post['ID'], 'post-format-'.$this->projectsettings['postformat']['default'], 'post_format' );    
            }
        }   

        // call next method
        $this->permalink();        
    }   
     
    public function permalink() {

        if( isset( $this->projectsettings['permalinks']['column'] ) && is_string( $this->projectsettings['permalinks']['column'] ) && !empty( $this->projectsettings['permalinks']['column'] ) ){
            $this->my_post['post_name'] = $this->row[ $this->projectsettings['permalinks']['column'] ]; 
        }
                
        // call next method
        $this->pingstatus();        
    }  
      
    public function pingstatus() {
        
        if( isset( $this->projectsettings['basicsettings']['pingstatus'] ) ){
            $this->my_post['ping_status'] = $this->projectsettings['basicsettings']['pingstatus']; 
        }
        
        // call next method
        $this->posttype();        
    }
    
    /**
    * applys post type based on rules
    * 1. if multiple rules apply to the $row the last rule and post type is the one used 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */
    public function posttype() {
        if( isset( $this->projectsettings['posttypes'] ) ){
            for( $i=1;$i<=3;$i++){
                if( isset( $this->projectsettings['posttypes']["posttyperule$i"]['column'] )
                && isset( $this->projectsettings['posttypes']["posttyperuletrigger$i"] )
                && isset( $this->projectsettings['posttypes']["posttyperuleposttype$i"] )
                && isset( $this->row[ $this->projectsettings['posttypes']["posttyperule$i"]['column'] ] )
                && $this->row[ $this->projectsettings['posttypes']["posttyperule$i"]['column'] ] === $this->projectsettings['posttypes']["posttyperuletrigger$i"] ){
                    $my_post['post_type'] = $this->projectsettings['posttypes']["posttyperuleposttype$i"];            
                }
            }            
        }    

        if( !isset( $this->my_post['post_type'] ) ) {
            $this->my_post['post_type'] = $this->projectsettings['basicsettings']['defaultposttype'];
        }
                
        // call next method
        $this->publishdate();         
    }  
      
    public function publishdate() {
        if( isset( $this->projectsettings['dates']['publishdatemethod'] ) && is_string( $this->projectsettings['dates']['publishdatemethod'] ) ){
            switch ( $this->projectsettings['dates']['publishdatemethod'] ) {
               case 'data':
                    if( isset( $this->projectsettings['dates']['column'] ) && is_string( $this->projectsettings['dates']['column'] ) ){
                        $strtotime = strtotime( $this->row[$this->projectsettings['dates']['column']] );
                        $this->my_post['post_date'] = date( "Y-m-d H:i:s", $strtotime);
                    }
                 break;  
               case 'incremental':
            
                // establish minutes increment
                $increment = rand( $this->projectsettings['dates']['naturalvariationlow'], $this->projectsettings['dates']['naturalvariationhigh'] );    

                // get start date/time - this is updated per post so that the next post increments properly
                $start_time = strtotime( $this->projectsettings['dates']['incrementalstartdate'] );
       
                // add increment to start time to establish publish time
                $publish_seconds = $start_time + $increment; 
        
                $this->my_post['post_date'] =  date( "Y-m-d H:i:s", $publish_seconds );
                $this->my_post['post_date_gmt'] = gmdate( "Y-m-d H:i:s", $publish_seconds ); 
                
                // update project array with latest date
                $this->projectsettings['dates']['incrementalstartdate'] = date( 'Y-m-d H:i:s', $publish_seconds);               

                 break;
               case 'random':
                    
                    // establish start time and end time
                    $start_time = strtotime( $this->projectsettings['dates']['randomdateearliest'] );
                    $end_time = strtotime( $this->projectsettings['dates']['randomdatelatest'] );            
                    
                    // make random time between our start and end
                    $publish_time = rand( $start_time, $end_time );
                    
                    $this->my_post['post_date'] =  date( "Y-m-d H:i:s", $publish_time );
                    $this->my_post['post_date_gmt'] = gmdate( "Y-m-d H:i:s", $publish_time );
              
                 break;
            }        
        }                

        // call next method
        $this->spinner_brackets();        
    }
    
    /**
    * bracketed spinner (spintax)
    */
    public function spinner_brackets() {
        $this->my_post['post_content'] = $this->CSV2POST->spin( $this->my_post['post_content'] );
        // call next method
        $this->tags();        
    }   
     
    public function tags() {
    	
        if( isset( $this->projectsettings['tags'] ) && is_array( $this->projectsettings['tags'] ) ){
            
            // set excluded tags array
            if( isset( $this->projectsettings['tags']['excludedtags'] ) ){
                if( is_array( $this->projectsettings['tags']['excludedtags'] ) ){
                    $excluded_tags = $this->projectsettings['tags']['excludedtags'];
                }else{
                    $excluded_tags = explode( ', ', $this->projectsettings['tags']['excludedtags'] );
                }
            }
            
            $final_tags_array = array();
            // get tags data, break down into array using comma
            if( $this->projectsettings['tags']['column'] ){
                $exploded_tags = explode( ', ', $this->row[ $this->projectsettings['tags']['column'] ] );
                $final_tags_array = array_merge( $final_tags_array, $exploded_tags);
            }
            
            // generate tags from text data
            if( isset( $this->projectsettings['tags']['textdata']['column'] ) && !is_string( $this->projectsettings['tags']['textdata']['column'] ) ){
                // remove multiple spaces, returns, tabs, etc.
                $text = preg_replace( "/[\n\r\t\s ]+/i", " ", $this->row[$this->projectsettings['tags']['textdata']['column']] );                
                // replace full stops and spaces with a comma (command required in explode)
                $text = str_replace(array( "   ", "  ", " ", ".", '"' ), ", ", $text );
                $exploded_tags = explode( ', ', $text);
                $final_tags_array = array_merge( $final_tags_array, $exploded_tags);                        
            }
            
            // cleanup tags
            foreach( $final_tags_array as $key => $tag){
                // remove numeric values
                if( isset( $this->projectsettings['tags']['defaultnumerics'] ) 
                && $this->projectsettings['tags']['defaultnumerics'] == 'disallow'
                && is_numeric( $tag) ){
                    unset( $final_tags_array[$key] );
                }
                // remove exclusions
                if(in_array( $tag, $excluded_tags) ){
                    unset( $final_tags_array[$key] );
                }   
            }
            
            // remove extra tags  
            if( isset( $this->projectsettings['tags']['maximumtags'] ) && is_numeric( $this->projectsettings['tags']['maximumtags'] ) ){
                $final_tags_array = array_slice( $final_tags_array, 0, $this->projectsettings['tags']['maximumtags'] );
            }
            
            $this->my_post['tags_input'] = implode( ", ", $final_tags_array );                        
        }
        
        // call next method
        $this->title();        
    }  
         
    public function title() {

        if( isset( $this->projectsettings['titles']['defaulttitletemplate'] ) ){
            $this->my_post['post_title'] = $this->replace_tokens( $this->projectsettings['titles']['defaulttitletemplate'] );    
        }       
        
        if(!isset( $this->my_post['post_title'] ) || empty( $this->my_post['post_title'] ) ){
            $this->my_post['post_title'] = __( 'No Post Content Title' );    
        }
                
        // call next method
        $this->wpautop();         
    }
    
    /**
    * change double line breaks into paragraphs
    * 
    * @link https://codex.wordpress.org/Function_Reference/wpautop
    */
    public function wpautop() {
        
        /*  need a setting to apply this or not, it changes double line breaks into <p>
        if( isset( $this->my_post['post_content'] ) ){
            $this->my_post['post_content'] = wpautop( $this->my_post['post_content'] );
        }
        */

        // call next method
        $this->insert_post();        
    }
    
    public function insert_post() {                      
        $this->my_post['ID'] = wp_insert_post( $this->my_post );
        // call next method
        $this->localimagegroupimport();        
    }
    
    /**
    * imports groups of images from a local directory, per post using a single term and not full paths
    */
    public function localimagegroupimport() {
        if( isset( $this->projectsettings['content']['groupedimagesdir'] ) && is_string( $this->projectsettings['content']['groupedimagesdir'] ) ){   
            $continue = true;// do checks and change to false if problem detected
            if( !file_exists( ABSPATH . $this->projectsettings['content']['groupedimagesdir'] ) ){
                $continue = false;
            }
            
            if( isset( $this->projectsettings['content']["localimages"] ) ){
                if(!isset( $this->projectsettings['content']['enablegroupedimageimport'] ) || $this->projectsettings['content']['enablegroupedimageimport'] !== 'enabled' ){
                    $continue = false;
                    if(!isset( $project_array['content']["localimages"]['column'] ) ){
                        $continue = false;
                        if( isset( $this->row[ $this->projectsettings['content']["localimages"]['column'] ] ) && !empty( $this->row[ $this->projectsettings['content']["localimages"]['column'] ] ) ){
                            $continue = false;
                        }                 
                    }
                }    
            }else{
                $continue = false;
            }
            
            if( $continue ){
                //setting not yet used = $project_array['content']["incrementalimages"];
                // create an array to hold directory list
                $results = array();

                // create a handler for the directory
                $handler = opendir( ABSPATH . $this->projectsettings['content']['groupedimagesdir'] );
                  
                // open directory and walk through the filenames
                while ( $file = readdir( $handler ) ) {

                    // if file isn't this directory or its parent, add it to the results
                    if ( $file != "." && $file != "..") {

                        // check with regex that the file format is what we're expecting and not something else
                        // BRO_HOLM_7_1 to BRO-HOLM-7-1
                        if( strstr( $file, $this->row[ $this->projectsettings['content']["localimages"]['column'] ] ) ) {
        
                            $fullpath = ABSPATH . $this->projectsettings['content']['groupedimagesdir'] . '/' . $file;
                           
                            // import image to WordPress media
                            $thumbid = $CSV2POST->create_localmedia_fromlocalimages( $fullpath, $this->my_post['ID'] );  
                              
                            // add to our file array for later use
                            $results[] = $file;
                        }
                    }
                }            
                
                // build list or gallery of images based on settings
                $image_list_or_gallery = '';
                 
                // replace applicable token in post content if it exists, right now we are using one token no custom ones
                if( isset( $this->my_post['post_content'] ) && strstr( $this->my_post['post_content'], '#localimagelist#' ) ){
                    $this->my_post['post_content'] = str_replace( '#localimagelist#', $image_list_or_gallery, $this->my_post['post_content'] );    
                }
            }
        }
        
        // call next method
        $this->externalimagegroupimport();           
    } 
    
    /**
    * external (HTTP) image import
    * 
    * @todo this method was copied and has not been worked on yet
    */
    public function externalimagegroupimport() {
        
        // call next method
        $this->insert_customfields();           
    }   
        
    /**
    * insert users custom fields, the values are determined before this method
    */
    public function insert_customfields() {
        if( isset( $this->my_post['ID'] ) && is_numeric( $this->my_post['ID'] ) ){
            foreach( $this->my_post['newcustomfields'] as $key => $cf){
                add_post_meta( $this->my_post['ID'], $cf['name'], $cf['value'] );
            }
        }
        
        // call next method
        $this->insert_plugins_customfields();    
    } 
       
    /**
    * insert plugins custom fields, used to track and mass manage posts
    */
    public function insert_plugins_customfields() {
        // add data source ID for querying source
        add_post_meta( $this->my_post['ID'], 'c2p_project', $this->projectid);
                
        // add data source ID for querying source
        add_post_meta( $this->my_post['ID'], 'c2p_datasource', $this->mainsourceid);
                                                   
        // add meta to hold the data row ID for querying row that was used to create post
        add_post_meta( $this->my_post['ID'], 'c2p_rowid', $this->row['c2p_rowid'] );        
        
        // update date and time will be compared against the row time and projects timestamp
        add_post_meta( $this->my_post['ID'], 'c2p_updated',current_time( 'mysql' ) );

        // call next method
        $this->update_project();    
    }
    
    /**
    * update project statistics
    * 1. save the last post created for reference
    * 2. store the entire $my_post object for debugging
    * 3. update the projectsettings value itself (incremental publish date is one example value that needs tracked per post)    
    */
    public function update_project() {
        $this->CSV2POST->update_project( $this->projectid, array( 'projectsettings' => maybe_serialize( $this->projectsettings) ), false );
        // call next method
        $this->update_row();        
    }
    
    /**
    * updates the users data row, add post ID to create a relationship between imported row and the new post 
    */
    public function update_row() {      
        global $wpdb;
        
        $query = "UPDATE " . $this->maintable; 
        $query .= " SET c2p_postid = " . $this->my_post['ID'] . ", " . "c2p_applied = '" . current_time( 'mysql' ) . "'";
        $query .= " WHERE c2p_rowid = " . $this->row['c2p_rowid'];
        $wpdb->query( $query );         
        
        // call next method
        $this->output();        
    }
    
    /**
    * output results, method is used for manual post creation request only
    */
    public function output() {
        // $autoblog->finished == true indicates procedure just done the final post or the time limit is reached
        if( $this->requestmethod == 'manual' && $this->finished === true ){
            //$this->UI->create_notice( __( 'Sorry no report setup yet more information will be added later.' ), 'success', 'Extra', 'Posts Creation Report' );
        }
    }
}// end CSV2POST_InsertPost

/**
* Use to update a post created by or adopted by CSV 2 POST, class systematically calls all methods one after the other building the $my_post 
* and making changes to meta or media
* 
* 1. methods/functions are in alphabetical order
* 2. each method calls the next one in the list
* 3. eventually a method updates apost using the $my_post object built along the way
* 4. $my_post is used to store then update meta, thumbnail/featured image and other attachments
* 5. many methods check for their target value to exist in $my_post already and instead alter it (meaning we can re-call the class on an object)
* 6. some methods check for values in the $my_post object and perform procedures based on the values found or not found
* 
* $this->requestmethod - systematic|manual|schedule
* 
* Systematic: this method happens while posts are being opened, it means the post object
* 
* @author Ryan Bayne
* @package CSV 2 POST
* @since 8.0.0
* @version 1.2.32
*/
class CSV2POST_UpdatePost{
    public $my_post = array();
    public $report_array = array();// will include any problems detected, can be emailed to user 
       
    private function replace_tokens( $subject){
        unset( $this->projectcolumns['arrayinfo'] );
        foreach( $this->row as $columnfromquery => $usersdata){ 
            foreach( $this->projectcolumns as $table_name => $columnfromdb){  
                $subject = str_replace( '#'. $columnfromquery . '#', $usersdata, $subject);
            }    
        }
        return $subject;
    } 
    
    public function start() {
        $this->CSV2POST = new CSV2POST();
        $this->my_post['ID'] = $this->row['c2p_postid'];
        $this->replacevaluerules();
    }
    
    /**
    * rules for replacing specific values in selected columns with other content 
    */
    public function replacevaluerules() {
        if( isset( $this->projectsettings['content']['valuerules'] ) && is_array( $this->projectsettings['content']['valuerules'] ) ){
            foreach( $this->projectsettings['content']['valuerules'] as $key => $rule_array ){
                
                // if we do not have all the values we require in this rule just continue to the next item
                if(!isset( $rule_array['column'] ) ){continue;}
                if(!isset( $rule_array['vrvdatavalue'] ) ){continue;}
                if(!isset( $rule_array['vrvreplacementvalue'] ) ){continue;}
                
                // ensure the column is set in the $row
                if( isset( $this->row[ $rule_array['column'] ] ) ){
                    // does our value match the data
                    if( $this->row[ $rule_array['column'] ] === $rule_array['vrvdatavalue'] ){
                        // we have exact match to the rule so we replace the value
                        $this->row[ $rule_array['column'] ] = $rule_array['vrvreplacementvalue'];
                    }
                }    
            }              
        }        
        $this->title();
    }
    
    public function title() {

        if( isset( $this->projectsettings['titles']['defaulttitletemplate'] ) ){
            $this->my_post['post_title'] = $this->replace_tokens( $this->projectsettings['titles']['defaulttitletemplate'] );    
        }       
        
        if(!isset( $this->my_post['post_title'] ) || empty( $this->my_post['post_title'] ) ){
            $this->my_post['post_title'] = __( 'No Post Content Title' );    
        }
                
        // call next method
        $this->categories();         
    }            

    public function categories() { 
        if( isset( $this->projectsettings['categories'] ) ){
            if( isset( $this->projectsettings['categories']['data'] ) ){ 
                $CSV2POST = new CSV2POST();
                              
                // load categories class
                $CSV2POST->load_class( 'C2P_Categories', 'class-categories.php', 'classes',array( 'noreturn' ) );
                $CSV2POST_Categories = new C2P_Categories();
                
                // establish if pre-set parent in use
                $preset_parent = false;
                if( isset(  $this->project_settings_array['categories']['presetcategoryid'] ) ){
                    $preset_parent = $this->project_settings_array['categories']['presetcategoryid'];
                }
                
                // create $posts_array which consists of a post ID as key and array of category terms  
                $posts_array = array();      
                foreach( $this->projectsettings['categories']['data'] as $level => $catarray ){  
                    $posts_array[ $this->my_post['ID'] ][] = $this->row[ $catarray['column'] ];
                }   
                        
                // $posts_array must have post ID has key and each item an array of terms in order as they are to be in WP
                $CSV2POST_Categories->mass_update_posts_categories( $posts_array, $preset_parent );
            }
        }
        
        // call next method
        $this->content();
    }
      
    public function content() {
        
        if( isset( $this->projectsettings['content']['wysiwygdefaultcontent'] ) ){
            $this->my_post['post_content'] = $this->replace_tokens( $this->projectsettings['content']['wysiwygdefaultcontent'] );    
        }    
        
        // call next method
        $this->customfields();        
    }    
    
    /**
    * does not create meta data, only establishes what meta data is to be created and it is created later
    * it is done this way so that rules can take meta into consideration and meta can be adjusted prior to post creation 
    */
    public function customfields() {
        $this->my_post['newcustomfields'] = array();// we will add keys and values to this, the custom fields are created later    
        $i = 0;// count number of custom fields, use it as array key
        
        // seo title meta
        if( isset( $this->projectsettings['customfields']['seotitletemplate'] ) && !empty( $this->projectsettings['customfields']['seotitletemplate'] )
        && isset( $this->projectsettings['customfields']['seotitlekey'] ) && !empty( $this->projectsettings['customfields']['seotitlekey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seotitletemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seotitlekey']; 
            ++$i; 
        }
        
        // seo description meta
        if( isset( $this->projectsettings['customfields']['seodescriptiontemplate'] ) && !empty( $this->projectsettings['customfields']['seodescriptiontemplate'] )
        && isset( $this->projectsettings['customfields']['seodescriptionkey'] ) && !empty( $this->projectsettings['customfields']['seodescriptionkey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seodescriptiontemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seodescriptionkey'];  
            ++$i; 
        }
                
        // seo keywords meta
        if( isset( $this->projectsettings['customfields']['seokeywordstemplate'] ) && !empty( $this->projectsettings['customfields']['seokeywordstemplate'] )
        && isset( $this->projectsettings['customfields']['seokeywordskey'] ) && !empty( $this->projectsettings['customfields']['seokeywordskey'] ) ){
            $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $this->projectsettings['customfields']['seokeywordstemplate'] );
            $this->my_post['newcustomfields'][$i]['name'] = $this->projectsettings['customfields']['seokeywordskey']; 
            ++$i;       
        }       
        
        // now add all other custom fields
        if( isset( $this->projectsettings['customfields']['cflist'] ) ){
            foreach( $this->projectsettings['customfields']['cflist'] as $key => $cf){
                $this->my_post['newcustomfields'][$i]['value'] = $this->replace_tokens( $cf['value'] );
                $this->my_post['newcustomfields'][$i]['name'] = $cf['name'];                
                ++$i;     
            }
        }

        // call next method
        $this->excerpt();        
    }
    
    public function excerpt() {
        
        // call next method
        $this->wpautop();         
    }   
               
    /**
    * change double line breaks into paragraphs
    * 
    * @link https://codex.wordpress.org/Function_Reference/wpautop
    */
    public function wpautop() {
        
        /*  need a setting to apply this or not, it changes double line breaks into <p>
        if( isset( $this->my_post['post_content'] ) ){
            $this->my_post['post_content'] = wpautop( $this->my_post['post_content'] );
        }
        */

        // call next method
        $this->update_post();        
    }
    
    public function update_post() {                   
        wp_update_post( $this->my_post );
        // call next method
        $this->update_customfields();        
    }
    
    /**
    * insert users custom fields, the values are determined before this method
    */
    public function update_customfields() {
        if( isset( $this->my_post['ID'] ) && is_numeric( $this->my_post['ID'] ) ){
            foreach( $this->my_post['newcustomfields'] as $key => $cf){
                update_post_meta( $this->my_post['ID'], $cf['name'], $cf['value'] );
            }
        }
        
        // call next method
        $this->update_plugins_customfields();    
    } 
       
    /**
    * insert plugins custom fields, used to track and mass manage posts
    */
    public function update_plugins_customfields() {
        // update date and time will be compared against the row time and projects timestamp
        update_post_meta( $this->my_post['ID'], 'c2p_updated',current_time( 'mysql' ) );
                
        // call next method
        $this->then_update_project();    
    }
    
    /**
    * update project statistics
    * 1. save the last post created for reference
    * 2. store the entire $my_post object for debugging
    * 3. update the projectsettings value itself (incremental publish date is one example value that needs tracked per post)    
    */
    public function then_update_project() {
        $this->CSV2POST->update_project( $this->projectid, array( 'projectsettings' => maybe_serialize( $this->projectsettings) ) );
        
        // call next method
        $this->update_row();        
    }
    
    /**
    * updates the users data row, add post ID to create a relationship between imported row and the new post 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.2
    */
    public function update_row() {     
        global $wpdb;
        
        $query = "UPDATE " . $this->maintable; 
        $query .= " SET c2p_postid = " . $this->my_post['ID'] . ", " . "c2p_applied = '" . current_time( 'mysql' ) . "'";
        $query .= " WHERE c2p_rowid = " . $this->row['c2p_rowid'];
        $wpdb->query( $query );
               
        // call next method
        $this->output();        
    }
    
    /**
    * output results, method is used for manual post creation request only
    */
    public function output() {
        // $autoblog->finished == true indicates procedure just done the final post or the time limit is reached
        if( $this->requestmethod == 'manual' && $this->finished === true){
            //$this->UI->create_notice( __( 'Sorry a report is not available yet, more information coming later.' ), 'info', 'Small', 'Posts Update Report' );
        }
        
        return $this->my_post;
    }
}// end CSV2POST_UpdatePost  
        
/**
* Lists tickets post type using standard WordPress list table
*/
class C2P_Log_Table extends WP_List_Table {
    
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default( $item, $column_name){
             
        $attributes = "class=\"$column_name column-$column_name\"";
                
        switch( $column_name){
            case 'row_id':
                return $item['row_id'];    
                break;
            case 'timestamp':
                return $item['timestamp'];    
                break;                
            case 'outcome':
                return $item['outcome'];
                break;
            case 'category':
                echo $item['category'];  
                break;
            case 'action':
                echo $item['action'];  
                break;  
            case 'line':
                echo $item['line'];  
                break;                 
            case 'file':
                echo $item['file'];  
                break;                  
            case 'function':
                echo $item['function'];  
                break;                  
            case 'sqlresult':
                echo $item['sqlresult'];  
                break;       
            case 'sqlquery':
                echo $item['sqlquery'];  
                break; 
            case 'sqlerror':
                echo $item['sqlerror'];  
                break;       
            case 'wordpresserror':
                echo $item['wordpresserror'];  
                break;       
            case 'screenshoturl':
                echo $item['screenshoturl'];  
                break;       
            case 'userscomment':
                echo $item['userscomment'];  
                break;  
            case 'page':
                echo $item['page'];  
                break;
            case 'version':
                echo $item['version'];  
                break;
            case 'panelname':
                echo $item['panelname'];  
                break; 
            case 'tabscreenname':
                echo $item['tabscreenname'];  
                break;
            case 'dump':
                echo $item['dump'];  
                break; 
            case 'ipaddress':
                echo $item['ipaddress'];  
                break; 
            case 'userid':
                echo $item['userid'];  
                break; 
            case 'comment':
                echo $item['comment'];  
                break;
            case 'type':
                echo $item['type'];  
                break; 
            case 'priority':
                echo $item['priority'];  
                break;  
            case 'thetrigger':
                echo $item['thetrigger'];  
                break; 
                                        
            default:
                return 'No column function or default setup in switch statement';
        }
    }
                    
    /** ************************************************************************
    * Recommended. This is a custom column method and is responsible for what
    * is rendered in any column with a name/slug of 'title'. Every time the class
    * needs to render a column, it first looks for a method named 
    * column_{$column_title} - if it exists, that method is run. If it doesn't
    * exist, column_default() is called instead.
    * 
    * This example also illustrates how to implement rollover actions. Actions
    * should be an associative array formatted as 'slug'=>'link html' - and you
    * will need to generate the URLs yourself. You could even ensure the links
    * 
    * 
    * @see WP_List_Table::::single_row_columns()
    * @param array $item A singular item (one full row's worth of data)
    * @return string Text to be placed inside the column <td> (movie title only )
    **************************************************************************/
    /*
    function column_title( $item){

    } */
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns() {
        $columns = array(
            'row_id' => 'Row ID',
            'timestamp' => 'Timestamp',
            'category'     => 'Category'
        );
        
        if( isset( $this->action ) ){
            $columns['action'] = 'Action';
        }                                       
           
        if( isset( $this->line ) ){
            $columns['line'] = 'Line';
        } 
                     
        if( isset( $this->file ) ){
            $columns['file'] = 'File';
        }
                
        if( isset( $this->function ) ){
            $columns['function'] = 'Function';
        }        
  
        if( isset( $this->sqlresult ) ){
            $columns['sqlresult'] = 'SQL Result';
        }

        if( isset( $this->sqlquery ) ){
            $columns['sqlquery'] = 'SQL Query';
        }
 
        if( isset( $this->sqlerror ) ){
            $columns['sqlerror'] = 'SQL Error';
        }
          
        if( isset( $this->wordpresserror ) ){
            $columns['wordpresserror'] = 'WP Error';
        }

        if( isset( $this->screenshoturl ) ){
            $columns['screenshoturl'] = 'Screenshot';
        }
        
        if( isset( $this->userscomment ) ){
            $columns['userscomment'] = 'Users Comment';
        }
 
        if( isset( $this->columns_array->page ) ){
            $columns['page'] = 'Page';
        }

        if( isset( $this->version ) ){
            $columns['version'] = 'Version';
        }
 
        if( isset( $this->panelname ) ){
            $columns['panelname'] = 'Panel Name';
        }
  
        if( isset( $this->tabscreenid ) ){
            $columns['tabscreenid'] = 'Screen ID';
        }

        if( isset( $this->tabscreenname ) ){
            $columns['tabscreenname'] = 'Screen Name';
        }

        if( isset( $this->dump ) ){
            $columns['dump'] = 'Dump';
        }

        if( isset( $this->ipaddress) ){
            $columns['ipaddress'] = 'IP Address';
        }

        if( isset( $this->userid ) ){
            $columns['userid'] = 'User ID';
        }

        if( isset( $this->comment ) ){
            $columns['comment'] = 'Comment';
        }

        if( isset( $this->type ) ){
            $columns['type'] = 'Type';
        }
                                    
        if( isset( $this->priority ) ){
            $columns['priority'] = 'Priority';
        }
       
        if( isset( $this->thetrigger ) ){
            $columns['thetrigger'] = 'Trigger';
        }

        return $columns;
    }
    
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items_further() and sort
     * your data accordingly (usually by modifying your query ).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array( 'data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items_further()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable);
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
      
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count( $data);

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);
 
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
  
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

class C2P_Projects_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default( $item, $column_name){
             
        $attributes = "class=\"$column_name column-$column_name\"";
                
        switch( $column_name){
            case 'projectid':
                return $item['projectid'];    
                break;
            case 'projectname':
                return $item['projectname'];    
                break;
            case 'timestamp':
                return $item['timestamp'];    
                break;                                                        
            case 'source1':
                return $item['source1'];    
                break;                                                        
            case 'source2':
                return $item['source2'];    
                break;                                                        
            case 'source3':
                return $item['source3'];    
                break;                                                        
            default:
                return 'No column function or default setup in switch statement';
        }
    }

    /*
    function column_title( $item){

    } */

    function get_columns() {
        $columns = array(
            'projectid' => 'Project ID',
            'projectname' => 'Project Name',
            'timestamp' => 'Timestamp',
            'source1' => 'Source 1',
            'source2' => 'Source 2',
            'source3' => 'Source 3'
        );

        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }

    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable);

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = count( $data);

        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

class C2P_ProjectDataSources_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default( $item, $column_name){
             
        $attributes = "class=\"$column_name column-$column_name\"";
                
        switch( $column_name){
            case 'sourceid':
                return $item['sourceid'];    
                break;            
            case 'projectid':
                if( $item['projectid'] == 0){return 'Unknown';}
                return $item['projectid'];    
                break;
            case 'sourcetype':
                return $item['sourcetype'];    
                break;
            case 'path':
                return $item['path'];    
                break;            
            case 'timestamp':
                return $item['timestamp'];    
                break;                                                        
            default:
                return 'No column function or default setup in switch statement';
        }
    }

    /*
    function column_title( $item){

    } */

    function get_columns() {
        $columns = array(
            'sourceid' => 'Source ID',
            'projectid' => 'projectid',
            'sourcetype' => 'Type',
            'path' => 'Path',
            'timestamp' => 'Timestamp'
        );

        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }

    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable);

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = count( $data);

        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

class C2P_CategoryProjection_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default( $item, $column_name ){
        foreach( $item as $table_name => $column_array ){

            foreach( $column_array as $category_column => $projection_array ){
                
                foreach( $projection_array as $catterm => $item_array ){
                    if( $column_name == 'term' ){
                        return $catterm;    
                    }    
                 
                }

                return $item_array[ $column_name ];
            }
        }
        
        return 'Nothing';
    }

    /*
    function column_title( $item){

    } */

    function get_columns() {
        return array(
                'term' => 'Term',
                'mapped' => 'Mapped ID',
                'catslug' => 'Slug',
                'level' => 'Level',
                'parentterm' => 'Parent Term',
                'parentid' => 'Parent ID'
             ); 
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }

    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable);

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = count( $data);

        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

class C2P_ReplaceValueRules_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }

    /**
    * use to apply a default value to any column
    * 
    * @param mixed $item
    * @param mixed $column_name
    */
    function column_default( $item, $column_name){
        switch( $column_name){
            case 'delete':
                return self::linkaction( $_GET['page'], 'deletereplacevaluerule', __( 'Delete this replacement value rule' ), 'Delete', '&cfid='.$item['id'] );    
                break;                                                                   
            default:
                return $item[$column_name];
        }        
    }

    /*
    function column_title( $item){

    } */

    function get_columns() {
        return array(
                'table' => 'Data Table',
                'column' => 'Data Column',
                'vrvdatavalue' => 'Value',
                'vrvreplacementvalue' => 'Replacement',
                'delete' => 'Delete'
             ); 
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
    }

    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable);

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = count( $data);

        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

/**
* Uses WP_List_Table to display a list of posts created by CSV 2 POST
* that are in the default category
* 
* @author Ryan Bayne
* @package CSV 2 POST
* @since 8.1.3
* @version 1.1
* @uses WP_List_Table() in WP 3.9.1
*/
class CSV2POST_UncatPosts_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' ); 
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' );
    }      
    
    /**
    * sets the value on a per row, per columb basis
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1     
    */     
    function column_default( $item, $column_name){
             
        $attributes = "class=\"$column_name column-$column_name\"";

        // get the current posts record
        $record = $this->CSV2POST->get_posts_record( $item['c2p_project'], $item['ID'] );
                
        switch( $column_name){
            case 'ID':
                return $item['ID'];    
                break;            
            case 'post_title':
                return $item['post_title'];    
                break;            
            case 'level_one':

                if( $record ){
                    $cat_col = $this->CSV2POST->get_category_column( $item['c2p_project'], 0 );     
                          
                    if( $cat_col ){
                        return $record[0][ $cat_col ];
                    }else{
                        return '';
                    }         
                }

                break;            
            case 'level_two':
            
                if( $record ){
                    $cat_col = $this->CSV2POST->get_category_column( $item['c2p_project'], 1 );     
                          
                    if( $cat_col ){
                        return $record[0][ $cat_col ];
                    }else{
                        return '';
                    }         
                }
                   
                break;            
            case 'level_three':
            
                if( $record ){
                    $cat_col = $this->CSV2POST->get_category_column( $item['c2p_project'], 2 );     
                          
                    if( $cat_col ){
                        return $record[0][ $cat_col ];
                    }else{
                        return '';
                    }         
                }
                    
                break;            
            case 'level_four':
            
                if( $record ){
                    $cat_col = $this->CSV2POST->get_category_column( $item['c2p_project'], 3 );     
                          
                    if( $cat_col ){
                        return $record[0][ $cat_col ];
                    }else{
                        return '';
                    }         
                }
                    
                break;            
            case 'level_five':
            
                if( $record ){
                    $cat_col = $this->CSV2POST->get_category_column( $item['c2p_project'], 4 );     
                          
                    if( $cat_col ){
                        return $record[0][ $cat_col ];
                    }else{
                        return '';
                    }         
                }
                    
                break;                                             
            default:
                return 'No column function or default setup in switch statement';
        }
    }

    /**
     * Render a cell in the "post_title" column
     *
     * @since 8.1.3
     *
     * @param array $item Data item for the current row
     * @return string HTML content of the cell
     */
    protected function column_post_title( array $item ) {

        // ensure user has permission to use individual actions else do not display them
        $user_can_edit_posts = current_user_can( 'edit_posts' );
               
        // build the nonced URL
        $edit_url = $this->UI->action_url( $file = 'post.php', false, 'edit', 'post=' . $item['ID'] );
             
        // build row of actions
        $row_actions = array();
        
        if ( $user_can_edit_posts ) {
            $row_text = '<strong><a title="' . esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'csv2post' ), esc_attr( $item['post_title'] ) ) ) . '" class="row-title" href="' . $edit_url . '">' . esc_html( $item['post_title'] ) . '</a></strong>';
        } else {
            $row_text = '<strong>' . esc_html( $item['name'] ) . '</strong>';
        }
         
        // add action that takes user to Edit Posts screen
        if ( $user_can_edit_posts ) {
            $row_actions['edit'] = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', $edit_url, esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;', 'csv2post' ), $item['post_title'] ) ), __( 'Edit', 'csv2post' ) );
        }
          
        return $row_text . $this->row_actions( $row_actions );
    }

    function get_columns() {
        $columns = array(
            'ID' => __( 'Post ID', 'csv2post' ),
            'post_title' => __( 'Post Title', 'csv2post' ),
            'level_one' => __( 'Level One', 'csv2post' ),
            'level_two' => __( 'Level Two', 'csv2post' ),
            'level_three' => __( 'Level Three', 'csv2post' ),
            'level_four' => __( 'Level Four', 'csv2post' ),
            'level_five' => __( 'Level Five', 'csv2post' )
        );

        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }
    
    function prepare_items_further( $data, $per_page = 5) {
        global $wpdb; //This is used only if making any database queries        

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable);

        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        $total_items = count( $data);

        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);

        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    } 
}
?>