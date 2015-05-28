<?php
/**
 * Post Types [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class CSV2POST_Posttypes_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'posttypes';
    
    public $purpose = 'normal';// normal, dashboard

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function meta_box_array() {
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( 'posttypes-defaultposttyperules', __( 'Post Type Rules', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaultposttyperules' ), true, 'activate_plugins' )
        );    
    }
            
    /**
     * Set up the view with data and do things that are specific for this view
     *
     * @since 8.1.3
     *
     * @param string $action Action for this view
     * @param array $data Data for this view
     */
    public function setup( $action, array $data ) {
        global $csv2post_settings;
        
        // create constant for view name
        if(!defined( "WTG_CSV2POST_VIEWNAME") ){define( "WTG_CSV2POST_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' ); 
        $this->DB = CSV2POST::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->FORMS = CSV2POST::load_class( 'CSV2POST_FORMS', 'class-forms.php', 'classes' );
                        
        // load the current project row and settings from that row
        if( isset( $csv2post_settings['currentproject'] ) && $csv2post_settings['currentproject'] !== false ) {
            
            $this->project_object = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] ); 
            if( !$this->project_object ) {
                $this->current_project_settings = false;
            } else {
                $this->current_project_settings = maybe_unserialize( $this->project_object->projectsettings ); 
            }
      
            parent::setup( $action, $data );
            
            // using array register many meta boxes
            foreach( self::meta_box_array() as $key => $metabox ) {
                // the $metabox array includes required capability to view the meta box
                if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                    $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
                }               
            }
            
        } else {
            $this->add_meta_box( 'posttypes-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
        }    
    }

    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.3
    * @version 1.1
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
    * @since 0.0.2
    * @version 1.1
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
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_posttypes_defaultposttyperules( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Advanced projects may require posts to be filtered into different post types. If you have no idea what that means, just ignore this form.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $csv2post_settings;
        ?>  

            <table class="form-table">
            <?php
            for( $i=1;$i<=3;$i++){
                
                $posttyperule_table = ''; 
                $posttyperule_column = '';
                if( isset( $this->current_project_settings['posttypes']["posttyperule$i"]['table'] ) ){$posttyperule_table = $this->current_project_settings['posttypes']["posttyperule$i"]['table'];}
                if( isset( $this->current_project_settings['posttypes']["posttyperule$i"]['column'] ) ){$posttyperule_column = $this->current_project_settings['posttypes']["posttyperule$i"]['column'];}             
                $this->UI->option_projectcolumns( __( "Column $i"), $csv2post_settings['currentproject'], "posttyperule$i", "posttyperule$i", $posttyperule_table, $posttyperule_column, 'notrequired', 'Not Required' );

                $posttyperuletrigger = '';
                if( isset( $this->current_project_settings['posttypes']["posttyperuletrigger$i"] ) ){$posttyperuletrigger = $this->current_project_settings['posttypes']["posttyperuletrigger$i"];}            
                $this->UI->option_text( __( "Trigger $i"), "posttyperuletrigger$i", "posttyperuletrigger$i", $posttyperuletrigger, false );
                
                $posttyperuleposttype = '';
                if( isset( $this->current_project_settings['posttypes']["posttyperuleposttype$i"] ) ){$posttyperuleposttype = $this->current_project_settings['posttypes']["posttyperuleposttype$i"];}            
                $this->UI->option_radiogroup_posttypes( __( "Post Type $i"), "posttyperuleposttype$i", "posttyperuleposttype$i", $posttyperuleposttype);
            }
            ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }
}?>