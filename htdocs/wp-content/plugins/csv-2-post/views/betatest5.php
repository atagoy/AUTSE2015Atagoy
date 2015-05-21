<?php
/**
 * Beta Test 5 [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Beta Test 5 [class] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Betatest5_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'betatest5';           
            
    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function meta_box_array() {
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( $this->view_name . '-currenttesting', __( 'Test Introduction', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'currenttesting' ), true, 'activate_plugins' ),
            array( $this->view_name . '-guidelines', __( 'Development Guidelines', 'csv2post' ), array( $this, 'parent' ), 'side', 'default', array( 'formid' => 'guidelines' ), true, 'activate_plugins' ),
            array( $this->view_name . '-warning', __( 'Alpha & Beta', 'csv2post' ), array( $this, 'parent' ), 'side', 'default', array( 'formid' => 'warning' ), true, 'activate_plugins' ),
            array( $this->view_name . '-errors', __( 'Errors', 'csv2post' ), array( $this, 'parent' ), 'side','default', array( 'formid' => 'errors' ), true, 'activate_plugins' ),
            array( $this->view_name . '-newcronrepeatevent', __( 'New Repeated Event', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'newcronrepeatevent' ), true, 'activate_plugins' ),
            array( $this->view_name . '-newcronsingleevent', __( 'New Single Event', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'newcronsingleevent' ), true, 'activate_plugins' ),
            array( $this->view_name . '-getcronarray', __( '_get_cron_array() Dump', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'getcronarray' ), true, 'activate_plugins' ),
            array( $this->view_name . '-wpgetschedules', __( 'wp_get_schedules() Dump', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'wpgetschedules' ), true, 'activate_plugins' ),
            array( $this->view_name . '-wpgetschedule', __( 'wp_get_schedule( eventcheckwpcron ) Dump', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'wpgetschedule' ), true, 'activate_plugins' ),
            array( $this->view_name . '-unscheduleeventbyhook', __( 'Clear Scheduled Action', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'unscheduleeventbyhook' ), true, 'activate_plugins' ),
            array( $this->view_name . '-scheduledeventstable', __( 'Scheduled Events Table', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'scheduledeventstable' ), true, 'activate_plugins' ),
            array( $this->view_name . '-wpcrondeactivationprocess', __( 'WP CRON Deactivation Process', 'csv2post' ), array( $this, 'parent' ), 'normal','default', array( 'formid' => 'wpcrondeactivationprocess' ), true, 'activate_plugins' ),
        );    
    }     
        
    /**
    * Set up the view with data and do things that are specific for this view
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.31
    * @version 1.0.0
    *
    * @param string $action Action for this view
    * @param array $data Data for this view
    */
    public function setup( $action, array $data ) {
        global $c2p_settings;
        
        // create constant for view name
        if(!defined( "WTG_CSV2POST_VIEWNAME") ){define( "WTG_CSV2POST_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'C2P_UI', 'class-ui.php', 'classes' );  
        $this->DB = CSV2POST::load_class( 'C2P_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'C2P_PHP', 'class-phplibrary.php', 'classes' );

        parent::setup( $action, $data );
                                   
        // using array register many meta boxes
        foreach( self::meta_box_array() as $key => $metabox ) {      
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) { 
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        }        
    }

    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function metaboxes() {  
        parent::register_metaboxes( self::meta_box_array() );     
    }

    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class. 
    * 
    * @uses dashboard_widgets() in parent class CSV2POST_View which loops through meta boxes and registeres widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function dashboard() {    
        parent::dashboard_widgets( self::meta_box_array() );  
    }
        
    /**
    * All add_meta_box() callback to this function to keep the add_meta_box() call simple.
    * 
    * This function also offers a place to apply more security or arguments.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.0.1
    */
    function parent( $data, $box ) {   
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    }
     
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest5_currenttesting( $data, $box ) { ?>
        
        <p>It is time to make use of Wordpress CRON and not rely on the CSV 2 POST schedule system. WP CRON has the benefit of working with
        hosting that do not provide CRON. It is still not 100% accurate but has the benefit of allow users to register specific actions with
        targetted due times. The CSV 2 POST schedule system simply runs one of many possibly actions within permitted hours.</p>
        
        <p>CSV 2 POST existing systematic automation is a type of schedule system but in some ways is not. It is closer to a systematic triggering of
        actions within a set rate rather than targetting a specific time. It allows any number of actions to run during
        permitted times giving it the schedule feel but it is more about how long processing is allowed to be run for and what gets done during that
        processing i.e. update 10 posts or 20 posts, create 5 posts or 7 posts. The existing system is all about creating a constant automated progress
        with a speed suitable for the server. It is not meant for accuracy but instead is meant to ensure a gradual and controlled progress that does not
        slow down the blog. It has always been
        recommended that very small tasks are executed within the existing system. I may need to define that system on its own.</p>
        
        <p>This test area</p>
 
        <?php              
    }
       
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest5_newcronrepeatevent( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], '', false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );                            
        
        $UI = new C2P_UI();
        
        echo '<table class="form-table">';
        
        $UI->option_date_time( __( 'Target Time', 'csv2post' ), 'newcrontargettime', 'newcrontargettime', null, true );
        $UI->option_menu_cronrepeat( __( 'Frequency', 'csv2post' ), 'newcronfrequency', 'newcronfrequency' ); 
        $UI->option_menu_cronhooks( __( 'Job/Action', 'csv2post' ), 'newcronhook', 'newcronhook' );
        
        echo '</table>';
        
        $this->UI->postbox_content_footer();
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest5_newcronsingleevent( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], '', false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );                            
        
        $UI = new C2P_UI();
        
        echo '<table class="form-table">';
        
        $UI->option_date_time( __( 'Target Time', 'csv2post' ), 'newcrontargettime', 'newcrontargettime', null, true ); 
        $UI->option_menu_cronhooks( __( 'Action', 'csv2post' ), 'newcronhook', 'newcronhook' );
        
        echo '</table>';
        
        $this->UI->postbox_content_footer();
    }
    
    /**
    * Table of scheduled events - this plugins events only, using this packages hooks
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_betatest5_unscheduleeventbyhook( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], '', false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );                            
        
        $UI = new C2P_UI();
        
        echo '<table class="form-table">';
        
        $UI->option_menu_cronhooks( __( 'Action', 'csv2post' ), 'cronhook2', 'cronhook2' );
        
        echo '</table>';
        
        $this->UI->postbox_content_footer(); 
    }     
    
    /**
    * Table of scheduled events - this plugins events only, using this packages hooks
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_betatest5_getcronarray() { 
        $cron = _get_cron_array();
        var_dump($cron);
    }    
    
    /**
    * Table of scheduled events - this plugins events only, using this packages hooks
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_betatest5_wpgetschedules() {
        $schedules = wp_get_schedules();
        var_dump($schedules);
    } 
       
    /**
    * Table of scheduled events - this plugins events only, using this packages hooks
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_betatest5_wpgetschedule() {
        $schedule = wp_get_schedule( 'eventcheckwpcron' );
        var_dump($schedule);
    }
    
    /**
    * About
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_betatest5_scheduledeventstable() {
        global $wpdb;

        $allowed_hooks = array( 'eventcheckwpcron' );// change to false to list all or add a hook name to focus on that
        $new_key = 0;
        $table_rows_array = array();
        
        // loop through all scheduled WP cron events
        $cron = _get_cron_array();   
        foreach( $cron as $time => $events_group_array ) {
            
            // loop through hooked events schedule for $time, they have a random value key
            foreach( $events_group_array as $hook => $hooked_event_array ) {

                // filter the hook being added to table
                if( in_array( $hook, $allowed_hooks ) || empty( $allowed_hooks ) ) {
                    
                    // $hooked_event_array is yet another array holding another array with the final settings, which include an array of "args"
                    foreach( $hooked_event_array as $randomkey => $the_event_settings ) {
                     
                        $table_rows_array[ $new_key ]['date'] = date( 'Y-m-d H:i:s', $time );    
                        $table_rows_array[ $new_key ]['schedule'] = $the_event_settings['schedule'];
                        $table_rows_array[ $new_key ]['interval'] = $the_event_settings['interval'];  
                        $table_rows_array[ $new_key ]['hook'] = $hook;
                        
                        ++$new_key;                   
                    }     
                }
            }
        }
   
        $SourcesTable = new C2P_Scheduledevents_Table();
        $SourcesTable->prepare_items_further( $table_rows_array, 10 );
        ?>

        <form id="movies-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <?php $SourcesTable->display() ?>
        </form>

        <?php     
    }
    
    public function postbox_betatest5_wpcrondeactivationprocess( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], '', false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );                            
        
        $UI = new C2P_UI();
        
        echo '<p>Need to create a procedure for clearing WP schedule items for CSV 2 POST. Then make it so that deactivation
        of CSV 2 POST uses that new procedure. This ensures WP does not attempt to do the impossible.</p>';
        
        $this->UI->postbox_content_footer(); 
    }
  
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest5_errors( $data, $box ) {
        ?>
        
        <p>Errors and notices will be experienced in this area. They will show on your servers error log.
        Normally this will happen when you have visited beta testing pages. It does not means the plugin
        is faulty but you may report what you experience. Just keep in mind that some tests are setup for
        my blog and will fail on others unless you edit the PHP i.e. change post or category ID values to 
        match those in your own blog.</p>
        
        <?php 
    }     
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */                         
    public function postbox_betatest5_guidelines( $data, $box ) {
        ?>
        
        <p>Do not make changes to a metabox already marked with "COMPLETE". We need to keep simplier test as they are for re-testing
        early functions as newer tests use them. Possibly resulting in changes being made and breaking older functionality.
        Please copy and paste the metabox to create a new one then work on that.</p>
        
        <?php 
    }   
       
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */                         
    public function postbox_betatest5_warning( $data, $box ) {
        ?>
        
        <p>Some tests may be ALPHA and not BETA. The beta term is simply more recognized. In either case
        you should not copy and paste the form or processing function into a live environment. None of the
        code here is considered finished and I myself will work on it again when putting it on the live pages.</p>
        
        <?php 
    }        
}

class C2P_Scheduledevents_Table extends WP_List_Table {

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
            case 'date':
                return $item['date'];    
                break;                                                                  
            case 'schedule':
                return $item['schedule'];    
                break;                                                                  
            case 'interval':
                return $item['interval'];    
                break;            
            case 'hook':
                return $item['hook'];    
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
            'hook' => __( 'Event/Hook', 'csv2post' ),        
            'date' => __( 'Date & Time', 'csv2post' ),
            'schedule' => __( 'Schedule', 'csv2post' ),
            'interval' => __( 'Interval', 'csv2post' ),
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