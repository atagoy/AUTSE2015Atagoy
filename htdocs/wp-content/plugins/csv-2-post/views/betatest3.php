<?php
/**
 * Beta Test 3 [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Beta Test 3 [class] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Betatest3_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'betatest3';

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
            array( 'betatest3-currenttesting', __( 'Test Introduction', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'currenttesting' ), true, 'activate_plugins' ),
            array( $this->view_name . '-t1', __( 'WP Roles Array', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 't1' ), true, 'activate_plugins' ),
            array( $this->view_name . '-t2', __( 'Box Array: add_meta_box()', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 't2' ), true, 'activate_plugins' ),
            array( $this->view_name . '-t3', __( 'Invalid Entry: Text Field', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 't3' ), true, 'activate_plugins' ),
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
        
        // load the current project row and settings from that row
        parent::setup( $action, $data );  
              
        // using array register many meta boxes
        foreach( self::meta_box_array() as $key => $metabox ) {
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        }
            
        $pointer = new C2P_Pointers('mypointer3', 'csv2postpointer1', 'My Pointers Title', 'This pointer will not stay hidden. Indicating the Ajax process is not updating user meta with closed pointers.');
        $pointer->add_action(); 

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
    public function postbox_betatest3_currenttesting( $data, $box ) {     
        ?>
        <p>Roles and capabilities are going to be applied to individual forms/boxes and pages. I will find the best way to
        build a list of the plugins forms so that administrators can easily change the capability required to access each form. That
        will allow multiple roles to access the same page, but all roles will not see the same forms. In the complex and advanced
        plugins I plan to make over the years to come. This will be a valuable tool. The purpose is to making hacking of forms harder,
        increase the speed of development by not having to re-code validation scripts in $_POST processing functions and have a fluid
        system that does not rely on jQuery or even HTML 5. </p>
        
        <p>This testing area also covers the validation of form submission within boxes. The values used to create the meta boxes are also
        used with the forms and they tie together in someway during processing. By re-checking the values used to create the meta box and form
        at the point of processing a request we can ensure the user did not change source code in a malicious manner.</p>
        
        <h4>The following work and tests are still to be carried out...</h4>
        
        <table class="form-table">
            <tr>
                <td>Name</td>
                <td>Status</td>
                <td>Description</td>
            </tr>
            <tr>
                <td>Escape</td>
                <td>100%</td>
                <td>Consider how best to funnel all $_POST and $_GET values through the same lines for strip slash and escape etc so that this does not need to be done within every processing function. The problem is $_POST processing functions make use of $_POST already and it is impossible to add paramaters to them. So not sure if this is actually possible and instead I may need to create a standard function for placing in every processing function. So these functions will have a sort of standard line of 2-3 other functions.</td>
            </tr>            
            <tr>
                <td>Register Hidden Inputs</td>
                <td>100%</td>
                <td>Using the existing registration approach the hidden inputs used in all forms, then validate them on $_POST action. This is where we increase anti hack measures as recieving function expects specific inputs and specific values.</td>
            </tr>            
            <tr>
                <td>Re-validate Page Permission</td>
                <td>100%</td>
                <td>Lets say someone is on an admin page they should not be on. It could be that they were viewing the page before an admin or moderator changed the users permissions. Slim chances of this scenario but we don't take chances so we'll use $_POST value with page name in it to check if user is still permitted to access said page.</td>
            </tr>            
            <tr>
                <td>Consider $_GET</td>
                <td>100%</td>
                <td>The existing function apply_input_validation() which checks individual $_POST values against registration needs to do the same for $_GET</td>
            </tr>            
            <tr>
                <td>Delete Form Validation</td>
                <td>100%</td>
                <td>Delete the data added to user meta for validating individual values, it is added to user data to create a per user and recent set of values matching the forms they have recently viewed in admin. It needs to be cleaned up after use. </td>
            </tr>            
            <tr>
                <td>Clearer Invalid Entry Notice</td>
                <td>100%</td>
                <td>Test show the new per input validation system works well. The notices simply need to be clearer about what input they are for. </td>
            </tr>            
            <tr>
                <td>Indication On Forms</td>
                <td>100%</td>
                <td>How best do we indicate on the form what values are invalid without using HTML5 and jQuery? Could the registration data in user meta possibly be used by updating it with results then calling on the results when parsing the form. That would require deleting user meta later than planned. </td>
            </tr>
        </table>
 
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
    public function postbox_betatest3_t1( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Roles testing.', 'csv2post' ), false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title']);
        
          global $wp_roles; 
         var_dump( $wp_roles->roles );

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
    public function postbox_betatest3_t2( $data, $box ) {                                 
        var_dump( $box );                   
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest3_t3( $data, $box ) {   
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Invalid Text Field Entry', 'csv2post' ), false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title']);                                 
        ?>  
        
        <p>This field requires alphanumeric characters only. Enter # or £ or $ or % and it should result in a none HTML 5 notice. The test is the
        processing part. A generic validation to make development quicker and apply more security. The validation involves checking the database
        for the type of validation to be applied. The database is populated with the validation method when the page is requested.</p>
 
        <?php 
        $UI = new C2P_UI();
        $UI->option_text( 'Enter Text', 'entertext', 'entertext', '', false, null, null, null, null, true, 'alphanumeric', 'activate_plugins' );
        
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
    public function postbox_betatest3_t4( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t5( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t6( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t7( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t8( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t9( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_t10( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
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
    public function postbox_betatest3_errors( $data, $box ) {
        ?>
        
        <p>Errors and notices will be experienced in this area. They will show on your servers error log.
        Normally this will happen when you have visited beta testing pages. It does not means the plugin
        is faulty but you may report what you experience. Just keep in mind that some tests are setup for
        my blog and will fail on others unless you edit the PHP i.e. change post or category ID values to 
        match those in your own blog.</p>
        
        <?php 
    }        
}?>