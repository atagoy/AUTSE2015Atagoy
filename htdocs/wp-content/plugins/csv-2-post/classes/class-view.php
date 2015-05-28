<?php
/**
 * Members and methods for CSV 2 POST views that use post boxes (eventually all views)
 *
 * @package CSV2POST
 * @subpackage Views
 * @author Ryan Bayne 
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * CSV 2 POST Base View class
 * 
 * Taking from CSV2POST plugin and customized to pave the way for postboxes on multiple
 * tabbed views within a single page, originally this class was meant for an admin page 
 * 
 * @package CSV2POST
 * @subpackage Views
 * @author Ryan Bayne (original author Tobias Bäthge)
 * @since 8.1.3
 * @version 1.0.2
 */
abstract class CSV2POST_View {

    /**
     * Data for the view
     *
     * @since 8.1.3
     *
     * @var array
     */
    protected $data = array();

    /**
     * Number of screen columns for post boxes
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 0;

    /**
     * User action for this screen
     *
     * @since 8.1.3
     *
     * @var string
     */
    protected $action = '';

    /**
     * Instance of the Admin Page Helper Class, with necessary functions
     *
     * @since 8.1.3
     *
     * @var CSV2POST_Admin_Page
     */
    protected $admin_page;

    /**
     * List of text boxes (similar to post boxes, but just with text and without extra functionality )
     *
     * @since 8.1.3
     *
     * @var array
     */
    protected $textboxes = array();

    /**
     * List of messages that are to be displayed as boxes below the page title
     *
     * @since 8.1.3
     *
     * @var array
     */
    protected $header_messages = array();

    /**
     * Whether there are post boxes registered for this screen,
     * is automatically set to true, when a meta box is added
     *
     * @since 8.1.3
     *
     * @var bool
     */
    protected $has_meta_boxes = false;

    /**
     * List of WP feature pointers for this view
     *
     * @since 8.1.3
     *
     * @var array
     */
    protected $wp_pointers = array();

    /**
     * Initialize the View class, by setting the correct screen columns and adding help texts
     *
     * @since 8.1.3
     */
    public function __construct() {
        $screen = get_current_screen();
          
        // discontinue if the view is dashboard, this class is loaded by view classes while processes dashboard widgets
        if( $screen->base === 'dashboard' ) {
            return false;    
        }
        
        if ( 0 != $this->screen_columns ) {
            $screen->add_option( 'layout_columns', array( 'max' => $this->screen_columns ) );
        }                        
                         
        // enable two column layout
        add_filter( "get_user_option_screen_layout_{$screen->id}", array( $this, 'set_current_screen_layout_columns' ) ); 
       
        // load classes
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );        
        $this->Tabmenu = $this->CSV2POST->load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        $this->Help = $this->CSV2POST->load_class( 'C2P_Help', 'class-help.php', 'classes' );
        $this->PHP = $this->CSV2POST->load_class( 'CSV2POST_PHP', 'class-phplibary.php', 'classes' );
        $this->UI = $this->CSV2POST->load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' );
        
        // load the help array
        $this->help_array = $this->Help->get_help_array();

        // call the menu_array
        $this->menu_array = $this->Tabmenu->menu_array();
                
        // get page name i.e. csv-2-post_page_csv2post_affiliates would return affiliates
        $page_name = $this->PHP->get_string_after_last_character( $screen->id, '_' );
        
        // if on main page "csv2post" then set tab name as main
        if( $page_name == 'csv2post' ){
            $page_name = 'main';
        }

        $help_content = ''; 
               
        // does the page have any help content? 
        if( isset( $this->help_array[ $page_name ] ) ){
                   
            // add a tab of content for the page itself, a general introduction and set of links
            if( isset( $this->help_array[ $page_name ][ 'pageinfo' ][ 'pageabout' ] ) ){
            
                // add help tab showing general help information (per page help tabs are added in admin_menu()
                $screen->add_help_tab( array(
                    'id' => $page_name,
                    'title' => __( 'About', 'csv2post' ) . ' ' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagetitle' ],
                    'content' => '<p>' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pageabout' ] . '</p>'
                ) );  
                
                // build the help tab sidebar which has some links
                $pagereadmeurl = '';
                $pagevideourl = '';
                $pagediscussionurl = '';
                $pagefaqurl = '';
                
                if( isset( $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagereadmoreurl' ] ) ){
                    $pagereadmeurl = '<p><a href="' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagereadmoreurl' ] . '" target="_blank">' . __( 'Documentation', 'csv2post') . '</a></p>';        
                } 
                               
                if( isset( $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagevideourl' ] ) ){
                    $pagevideourl = '<p><a href="' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagevideourl' ] . '" target="_blank">' . __( 'Video', 'csv2post') . '</a></p>';
                } 
                               
                if( isset( $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagediscussurl' ] ) ){
                    $pagediscussionurl = '<p><a href="' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagediscussurl' ] . '" target="_blank">' . __( 'Support', 'csv2post') . '</a></p>';
                }
        
                if( isset( $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagefaqurl' ] ) ){
                    $pagefaqurl = '<p><a href="' . $this->help_array[ $page_name ][ 'pageinfo' ][ 'pagefaqurl' ]  . '" target="_blank">' . __( 'FAQ', 'csv2post') . '</a></p>';
                }
                                
                // help tab sidebar
                $screen->set_help_sidebar( '
                <p><strong>' . __( 'For more information:', 'csv2post' ) . '</strong></p>' . $pagereadmeurl . $pagevideourl . $pagediscussionurl . $pagefaqurl );
               
            }   
        } 
    }

    /**
    * Change the value of the user option "screen_layout_{$screen->id}" through a filter
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    *
    * @param int|bool Current value of the user option
    * @return int New value for the user option
    */
    public function set_current_screen_layout_columns( $result ) {
        if ( false === $result ) {
            // the user option does not yet exist
            $result = $this->screen_columns;
        } elseif ( $result > $this->screen_columns ) {
            // the value of the user option is bigger than what is possible on this screen (e.g. because the number of columns was reduced in an update)
            $result = $this->screen_columns;
        }
        return $result;
    }

    /**
    * Set up the view with data and do things that are necessary for all views
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    *
    * @param string $action Action for this view
    * @param array $data Data for this view
    */
    public function setup( $action, array $data ) {
        $this->action = $action;
        $this->data = $data;
        
        // add .css meant for this view ONLY
        
        // add .js meant for this view ONLY
        $this->CSV2POST->enqueue_script( 'common', array( 'jquery', 'postbox' ), array() );
        
        $this->UI->add_admin_footer_text();

        // necessary fields for all views
        $this->add_text_box( 'default_nonce_fields', array( $this, 'default_nonce_fields' ), 'header', false );
        $this->add_text_box( 'action_nonce_field', array( $this, 'action_nonce_field' ), 'header', false );
        $this->add_text_box( 'action_field', array( $this, 'action_field' ), 'header', false );
    }
    
     /**
     * Outputs the meta boxes
     * 
     * @author Ryan R. Bayne
     * @package CSV 2 POST
     * @since 8.1.33
     * @version 1.1
     */
     public function register_metaboxes( $meta_box_array ) {
        // using array register many meta boxes
        foreach( $meta_box_array as $key => $metabox ) {
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        }     
     }
     
    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function dashboard_widgets( $meta_box_array ) { 
        global $csv2post_settings;
        
        // if dashboard widgets switch not enabled we do nothing, this check should really be done earlier also
        if( !isset( $csv2post_settings['widgetsettings']['dashboardwidgetsswitch'] ) || $csv2post_settings['widgetsettings']['dashboardwidgetsswitch'] !== 'enabled' ) {
            return;    
        }

        // create class objects for use in the dashboard functions, they aren't loaded on dashboard in this classes construct
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' ); 
        $this->DB = CSV2POST::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
                       
        // loop through array of meta boxes, which doubles as our array of dashboard widgets      
        foreach( $meta_box_array as $key => $metabox ) {
            
            // ensure the meta box is permitted to be setup as a dashboard widget (first line of security)
            if( isset( $metabox[6] ) && $metabox[6] === true ) {                  
            
                // now ensure the user as required capability to view the meta box
                if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {      
                          
                    wp_add_dashboard_widget(
                         $metabox[0] . 'dashboardwidget',// Dashboard Widget slug.
                         $metabox[1],// Title.
                         array( $this, 'postbox_' . $this->view_name . '_' . $metabox[5]['formid'] ),
                         false,
                         $metabox[5]// Arguments.
                    );
                    
                }
            }
        }    
    }
        
    /**
    * Register a header message for the view
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    *
    * @param string $text Text for the header message
    * @param string $class (optional) Additional CSS class for the header message
    */
    protected function add_header_message( $text, $class = 'updated' ) {
        $this->header_messages[] = "<div class=\"{$class}\"><p>{$text}</p></div>\n";
    }

    /**
    * Process header action messages, i.e. check if a message should be added to the page
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    *
    * @param array $action_messages Action messages for the screen
    */
    protected function process_action_messages( array $action_messages ) {
        if ( $this->data['message'] && isset( $action_messages[ $this->data['message'] ] ) ) {
            $class = ( 'error' == substr( $this->data['message'], 0, 5 ) ) ? 'error' : 'updated';
            $this->add_header_message( "<strong>{$action_messages[ $this->data['message'] ]}</strong>", $class );
        }
    }

    /**
    * Register a text box for the view
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    *
    * @param string $id Unique HTML ID for the text box container (only visible with $wrap = true)
    * @param string $callback Callback that prints the contents of the text box
    * @param string $context (optional) Context/position of the text box (normal, side, additional, header, submit)
    * @param bool $wrap Whether the content of the text box shall be wrapped in a <div> container
    */
    protected function add_text_box( $id, $callback, $context = 'normal', $wrap = false ) {
        if ( ! isset( $this->textboxes[ $context ] ) ) {
            $this->textboxes[ $context ] = array();
        }

        $long_id = "csv2post_{$this->action}-{$id}";
        
        $this->textboxes[ $context ][ $id ] = array(
            'id' => $long_id,
            'callback' => $callback,
            'context' => $context,
            'wrap' => $wrap,
        );
    }

    /**
    * Register a post meta box for the view, that is drag/droppable with WordPress functionality
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    * 
    * @uses add_meta_box()
    *
    * @param string $id Unique ID for the meta box
    * @param string $title Title for the meta box
    * @param string $callback Callback that prints the contents of the post meta box
    * @param string $context (optional) Context/position of the post meta box (normal, side, additional)
    * @param string $priority (optional) Order of the post meta box for the $context position (high, default, low)
    * @param bool $callback_args (optional) Additional data for the callback function (e.g. useful when in different class)
    */
    protected function add_meta_box( $id, $title, $callback, $context = 'normal', $priority = 'default', $callback_args = null ) {
        $this->has_meta_boxes = true;
        $id = "csv2post_{$this->action}-{$id}";         
        add_meta_box( $id, $title, $callback, null, $context, $priority, $callback_args );
    }

    /**
    * Render all text boxes for the given context
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1 
    * 
    * @since 8.1.3
    *
    * @param string $context Context (normal, side, additional, header, submit) for which registered text boxes shall be rendered
    */
    protected function do_text_boxes( $context ) {
        if ( empty( $this->textboxes[ $context ] ) ) {
            return;
        }

        foreach ( $this->textboxes[ $context ] as $box ) {   
            if ( $box['wrap'] ) {
                echo "<div id=\"{$box['id']}\" class=\"textbox\">\n";
            }
            call_user_func( $box['callback'], $this->data, $box );
            if ( $box['wrap'] ) {
                echo "</div>\n";
            }
        }
    }

    /**
    * Render all post meta boxes for the given context, if there are post meta boxes
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1 
    * 
    * @since 8.1.3
    * @uses do_meta_boxes()
    *
    * @param string $context Context (normal, side, additional) for which registered post meta boxes shall be rendered
    */
    protected function do_meta_boxes( $context ) {
        if ( ! $this->has_meta_boxes ) {
            return;
        }               
        do_meta_boxes( null, $context, $this->data );
    }

    /**
    * Print hidden fields with nonces for post meta box AJAX handling, if there are post meta boxes on the screen
    * (check is possible as this function is executed after post meta boxes have to be registered)
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @version 1.0
    * @since 8.1.3
    * @uses wp_nonce_field()
    *
    * @param array $data Data for this screen
    * @param array $box Information about the text box
    */
    protected function default_nonce_fields( array $data, array $box ) {
        if ( ! $this->has_meta_boxes ) {
            return;
        }
        wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); echo "\n";
        wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); echo "\n";
    }

    /**
    * Print hidden field with a nonce for the screen's action, to be transmitted in HTTP requests
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1 
    * 
    * @since 8.1.3
    * @uses wp_nonce_field()
    *
    * @param array $data Data for this screen
    * @param array $box Information about the text box
    */
    protected function action_nonce_field( array $data, array $box ) {
        wp_nonce_field( $this->CSV2POST->nonce( $this->action ) ); echo "\n";
    }

    /**
    * Print hidden field with the screen action
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1 
    * 
    * @since 8.1.3
    *
    * @param array $data Data for this screen
    * @param array $box Information about the text box
    */
    protected function action_field( array $data, array $box ) {
        echo "<input type=\"hidden\" name=\"action\" value=\"csv2post_{$this->action}\" />\n";
    }

    /**
    * Render the current view of meta boxes, including optional tab menu for sections with multiple pages
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.0.1
    */       
    public function render() { 

        global $c2p_tab_number, $wpecus_settings, $c2pm;

        // get the admin page name (slug in menu array, without prepend "csv2post_")
        $admin_page = $this->UI->get_admin_page_name();
        
        // if we are on the main page change $admin_page to 'main' as that is what we use in array
        if( $admin_page === 'csv2post' ){
            $admin_page = 'main';
        }   
        
        $current_user = get_currentuserinfo();
        
        // log users visit to page
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'trace',// using trace allows these log entries to be hidden easier
            'category' => 'usertrace',
            'action' => __( "User named $current_user visited admin page named " . $this->menu_array[ $admin_page ]['title'], 'csv2post' ),
            'priority' => 'low',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
         
        // view header - includes notices output and some admin side automation such as conflict prevention
        $this->CSV2POST->pageheader( $this->menu_array[ $admin_page ]['title'], 0);
                               
        // create tab menu for the giving page if the section has two or more pages
        if( !isset( $this->menu_array[ $admin_page ]['tabmenu'] ) || $this->menu_array[ $admin_page ]['tabmenu'] === true ) {
            if( $admin_page !== 'main' ) {
                $this->CSV2POST->build_tab_menu( $admin_page );
            }
        }
        
        $this->do_text_boxes( 'header' );
              
            ?>    
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-<?php echo ( isset( $GLOBALS['screen_layout_columns'] ) && ( 2 == $GLOBALS['screen_layout_columns'] ) ) ? '2' : '1'; ?>">
                     
                     <div id="postbox-container-2" class="postbox-container">
                        <?php
                        $this->do_text_boxes( 'normal' );
                        $this->do_meta_boxes( 'normal' );

                        $this->do_text_boxes( 'additional' );
                        $this->do_meta_boxes( 'additional' );

                        // print all submit buttons
                        $this->do_text_boxes( 'submit' );
                        ?>
                    </div>                                     

                    <div id="postbox-container-1" class="postbox-container">
                    <?php
                        // print all boxes in the sidebar
                        $this->do_text_boxes( 'side' );
                        $this->do_meta_boxes( 'side' );
                    ?>
                    </div>
                                                            
                </div>
                <br class="clear" />
            </div>
  
        </div>
        <?php     
    }

    /**
    * Print a submit button (only done when function is used as a callback for a text box)
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    *
    * @param array $data Data for this screen
    * @param array $box Information about the text box
    */
    protected function textbox_submit_button( array $data, array $box ) {
        $caption = isset( $data['submit_button_caption'] ) ? $data['submit_button_caption'] : __( 'Save Changes', 'csv2post' );
        ?>
        <p class="submit"><input type="submit" value="<?php echo esc_attr( $caption ); ?>" class="button button-primary button-large" name="submit" /></p>
        <?php
    }

    /**
    * Return the content for the help tab for this screen
    *
    * Has to be implemented for every view that is visible in the WP Dashboard!
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 7.0.0
    * @version 1.1
    */
    protected function help_tab_content() {
        // Has to be implemented for every view that is visible in the WP Dashboard!
        return '';
    }

} // class CSV2POST_View
