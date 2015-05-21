<?php
/**
 * Beta Test 7 [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Beta Test 7 [class] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Betatest7_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'betatest7';

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
            array( $this->view_name . '-deactivationuninstallation', __( 'EMPTY', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'deactivationuninstallation' ), true, 'activate_plugins' ),

            // common beta page information
            array( $this->view_name . '-guidelines', __( 'Development Guidelines', 'csv2post' ), array( $this, 'parent' ), 'side', 'default', array( 'formid' => 'guidelines' ), true, 'activate_plugins' ),
            array( $this->view_name . '-warning', __( 'Alpha & Beta', 'csv2post' ), array( $this, 'parent' ), 'side', 'default', array( 'formid' => 'warning' ), true, 'activate_plugins' ),
            array( $this->view_name . '-errors', __( 'Errors', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'errors' ), true, 'activate_plugins' ),
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
        $this->Forms = CSV2POST::load_class( 'C2P_Formbuilder', 'class-forms.php', 'classes' );

        $forms = new C2P_Formbuilder();

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

    public function postbox_betatest7_deactivationuninstallation( $data, $box ) {    
        $introduction = __( 'Option to make deactivation a 100% uninstall of the plugin.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
        

        echo '</table>';
                         
        $this->UI->postbox_content_footer();
    }

      
    ###############################################################
    #                                                             #
    #                     COMMON BETA PAGE INFORMATION            #
    #                                                             #
    ###############################################################    
    public function postbox_betatest7_errors( $data, $box ) {
        ?>
        
        <p>Errors and notices will be experienced in this area. They will show on your servers error log. It does not mean
        the plugin is faulty. This is a beta testing section of the plugin only. Do not expect perfect results and plugin behaviour
        while visiting these pages.</p>
        
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
    public function postbox_betatest7_guidelines( $data, $box ) {
        ?>
        
        <p>Do not make changes to a metabox already marked with "COMPLETE". They will be used as a starting point to another box/form.</p>
        
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
    public function postbox_betatest7_warning( $data, $box ) {
        ?>
        
        <p>This is a text area. The forms have been made available because they can be of use but I do not recommend using
        any features on this page on anything but a test installation of WP.</p>
        
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
    public function postbox_betatest7_empty( $data, $box ) {    
        $introduction = __( 'Do not use this box, it allows multiple entries to meta box array without making the boxes.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          

        echo '<table class="form-table">';
       

        echo '</table>';
                
        $this->UI->postbox_content_footer();
    }
         
}?>