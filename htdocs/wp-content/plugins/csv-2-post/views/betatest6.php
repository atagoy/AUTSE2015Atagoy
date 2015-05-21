<?php
/**
 * Beta Test 6 [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Beta Test 6 [class] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Betatest6_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'betatest6';

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
            
            // test forms
            array( $this->view_name . '-alpha', __( 'alpha', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'alpha' ), true, 'activate_plugins' ),
            array( $this->view_name . '-alphanumeric', __( 'alphanumeric', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'alphanumeric' ), true, 'activate_plugins' ),
            array( $this->view_name . '-numeric', __( 'numeric', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'numeric' ), true, 'activate_plugins' ),
            array( $this->view_name . '-url', __( 'URL', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'URL' ), true, 'activate_plugins' ),
            array( $this->view_name . '-disabledhacked', __( 'Disabled Input Hacked', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'disabledhacked' ), true, 'activate_plugins' ),
            array( $this->view_name . '-menuhacked', __( 'Menu Hacked (new item added)', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'menuhacked' ), true, 'activate_plugins' ),
            array( $this->view_name . '-capability', __( 'Ensure User Is Permitted To Use form', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'capability' ), true, 'activate_plugins' ),
            array( $this->view_name . '-logrequest', __( 'Log Form Requests', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'logrequest' ), true, 'activate_plugins' ),
            array( $this->view_name . '-hiddenvaluehacked', __( 'Hidden Value Hack Test', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'hiddenvaluehacked' ), true, 'activate_plugins' ),
            array( $this->view_name . '-required', __( 'Required Input (may need hidden input)', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'empty' ), true, 'activate_plugins' ),
            array( $this->view_name . '-maximumlength', __( 'Maximum String Length (string too long)', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'maximumlength' ), true, 'activate_plugins' ),
            array( $this->view_name . '-minimumlength', __( 'Minimum String Length (string too short)', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'minimumlength' ), true, 'activate_plugins' ),
            array( $this->view_name . '-specificpattern', __( 'Specific Pattern (regex)', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'specificpattern' ), true, 'activate_plugins' ),
            array( $this->view_name . '-maximumcheckboxes', __( 'Maximum Checkboxes Checked', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'maximumcheckboxes' ), true, 'activate_plugins' ),
            array( $this->view_name . '-minimumcheckboxes', __( 'Minimum Checkboxes Checked', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'minimumcheckboxes' ), true, 'activate_plugins' ),
            array( $this->view_name . '-checkboxesspecifictotal', __( 'Required Specific Number Of Checkboxes Checked', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'checkboxesspecifictotal' ), true, 'activate_plugins' ),
            array( $this->view_name . '-radiohacked', __( 'Radio Value Hack Test', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'radiohacked' ), true, 'activate_plugins' ),
            array( $this->view_name . '-autocomplete', __( 'autocomplete="off" Attribute', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'autocomplete' ), true, 'activate_plugins' ),
            array( $this->view_name . '-radiohacked', __( 'Radio Value Hacked', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'radiohacked' ), true, 'activate_plugins' ),
            array( $this->view_name . '-shortfunctions', __( 'Short Functions', 'csv2post' ), array( $this, 'parent' ), 'normal', 'default', array( 'formid' => 'shortfunctions' ), true, 'activate_plugins' ),
  
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
     
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest6_currenttesting( $data, $box ) { ?>
        
        <p>The form builder class has two goals. The first is to speed up development. The second is to increase security for all forms especially
        during a faster development cycle. The PHP class handling this is being launched in CSV 2 POST but will be found in the Wordpress Plugin Framework
        by WebTechGlobal.</p>
        
        <p>The tests below require most of the forms to be hacked. We view the source and edit it i.e. we can change the value of a hidden input. It is not
        hidden to those who know how to view it and as a developer it is my job to consider that the user may have done this.
        Right click on the page and select Inspect Element or View Source. Some browsers tools will only allow viewing but most now allow the source to
        be edited. Changes usually apply to the page straight away. If done right you can test each form as intended and see my security prevent the request
        to be processed. </p>
        
        <p>Development speed comes in the form of functions per input type. So rather than coding the HTML each time I made a form I just call on a range
        of functions. This approach is what allows extra security because the PHP functions being used can do more than output some HTML. I'm not going
        into detail about what they do because a) it's technical and b) I'll be providing subscriber only documentation for developers so that anyone can
        use the Wordpress Plugin Framework with success.</p>
 
        <?php              
    }

    public function postbox_betatest6_alpha( $data, $box ) {    
        $introduction = __( 'Ensure entry is letters only.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
        
         $this->Forms->input( 'text', __( 'Alpha Test', 'csv2post' ), 'alphatest', array('required' => true), 'alpha', '' );
        
        echo '</table>';
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_alphanumeric( $data, $box ) {    
        $introduction = __( 'Ensure entry are letters or numbers, no special characters.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
       
        $this->Forms->input( 'text', __( 'Alphanumeric Test', 'csv2post' ), 'alphanumerictest', array('required' => true), 'alphanumeric', '' );
        
        echo '</table>';
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_numeric( $data, $box ) {    
        $introduction = __( 'Ensure entry is numeric only.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
       
        $this->Forms->input( 'text', __( 'Numeric Test', 'csv2post' ), 'numerictest', array( 'required' => true ), 'numeric', '' );
        
        echo '</table>'; 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_url( $data, $box ) {    
        $introduction = __( 'Ensure entry is a URL.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
       
        $this->Forms->input( 'text', __( 'URL Test', 'csv2post' ), 'urltest', array( 'required' => true ), 'url', '' );
        
        echo '</table>'; 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_disabledhacked( $data, $box ) {    
        $introduction = __( 'Add every input in disabled state, then hack them individually to make them enabled. Detect this and prevent request being processed. This security measure helps to detect hackers quickly. We can then suspend the users account.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        echo '<table class="form-table">';
       
        $this->Forms->input( 'text', __( 'Disabled Text', 'csv2post' ), 'disabledtext', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'hidden', __( 'Disabled hidden', 'csv2post' ), 'disabledhidden', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'dateandtime', __( 'Disabled dateandtime', 'csv2post' ), 'disableddateandtime', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu_cronhooks', __( 'Disabled menu_cronhooks', 'csv2post' ), 'disabledmenu_cronhooks', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu_cronrepeat', __( 'Disabled menu_cronrepeat', 'csv2post' ), 'disabledmenu_cronrepeat', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu_capabilities', __( 'Disabled menu_capabilities', 'csv2post' ), 'disabledmenu_capabilities', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'file', __( 'Disabled file', 'csv2post' ), 'disabledfile', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'radiogroup_postformats', __( 'Disabled radiogroup_postformats', 'csv2post' ), 'disabledradiogroup_postformats', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'radiogroup_posttypes', __( 'Disabled radiogroup_posttypes', 'csv2post' ), 'disabledradiogroup_posttypes', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu_categories', __( 'Disabled menu_categories', 'csv2post' ), 'disabledmenu_categories', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu_users', __( 'Disabled menu_users', 'csv2post' ), 'disabledmenu_users', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'checkbox_single', __( 'Disabled checkbox_single', 'csv2post' ), 'disabledcheckbox_single', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'menu', __( 'Disabled menu', 'csv2post' ), 'disabledmenu', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'textarea', __( 'Disabled textarea', 'csv2post' ), 'disabledtextarea', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
        $this->Forms->input( 'radiogroup', __( 'Disabled radiogroup', 'csv2post' ), 'disabledradiogroup', array( 'disabled' => true, 'required' => true, 'items' => array( 'itemone' => 'Item One', 'itemtwo' => 'Item Two' ) ), 'alpha', '' );
        $this->Forms->input( 'switch', __( 'Disabled switch', 'csv2post' ), 'disabledswitch', array( 'disabled' => true, 'required' => true ), 'alpha', '' );
                
        echo '</table>'; 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_menuhacked( $data, $box ) {    
        $introduction = __( 'Hack a menu by adding a new item to it, select that new item then submit. Ensure security detects this.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_capability( $data, $box ) {    
        $introduction = __( 'Ensure user has capability for giving form, this should hide the form but this test needs to apply the security during processing as a backup.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_logrequest( $data, $box ) {    
        $introduction = __( 'Log all form requests even failed ones. This can help to monitor specific users and so support security.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_hiddenvaluehacked( $data, $box ) {    
        $introduction = __( 'Hack a hidden input. Change the input name and detect it. Change the input value and detect that also. We need to prevent
        a user creating a hidden input possibly used elsewhere in a plugin and triggering something the current form was not intended to do.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_required( $data, $box ) {    
        $introduction = __( 'Ensure a required field not used is detected. Will need to disable HTML 5 validation temporarily to perform this test.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_maximumlength( $data, $box ) {    
        $introduction = __( 'Maximum length of text field and add a textarea apply the same test to that.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_minimumlength( $data, $box ) {    
        $introduction = __( 'Test minimum length validation on a text field and text area.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_specificpattern( $data, $box ) {    
        $introduction = __( 'Apply regex test and ensure text field string and text area string have specific pattern.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_maximumcheckboxes( $data, $box ) {    
        $introduction = __( 'Enforce maximum number of checkbox selection, not just on interface but after submission.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_minimumcheckboxes( $data, $box ) {    
        $introduction = __( 'Enforce a required number of checks. Not on interface using Ajax or HTML 5 but after submission, these tests are about 
        an interface hack or fault that allows submission to be caught.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
           
        $this->UI->postbox_content_footer();
    }

    public function postbox_betatest6_checkboxesspecifictotal( $data, $box ) {    
        $introduction = __( 'Enforce specific number of checks on submission.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }
    
    public function postbox_betatest6_radiohacked( $data, $box ) {    
        $introduction = __( 'Add another radio option, detect it on submission. Also edit the value of an existing radio and detect that on submission.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          

        $this->UI->postbox_content_footer();
    }
    
    public function postbox_betatest6_autocomplete( $data, $box ) {    
        $introduction = __( 'Not sure what this test is going to do but explore what autocomplete="" attribute could allow by hack.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
               
        $this->UI->postbox_content_footer();
    }
      
    public function postbox_betatest6_shortfunctions( $data, $box ) {    
        $introduction = __( 'Test a function for each input that has the minimum and common parameters for that input. Those functions will call
        the input() function. The reason for not doing this in the first place is registry. All inputs are registered in the same place, all possibly
        values registered. There is a funnel of all input configuration which will come in handy later for further security and advanced configuration.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
               
        $this->UI->postbox_content_footer();
    }
  
      
    ###############################################################
    #                                                             #
    #                     COMMON BETA PAGE INFORMATION            #
    #                                                             #
    ###############################################################    
    public function postbox_betatest6_errors( $data, $box ) {
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
    public function postbox_betatest6_guidelines( $data, $box ) {
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
    public function postbox_betatest6_warning( $data, $box ) {
        ?>
        
        <p>Some tests may be ALPHA and not BETA. The beta term is simply more recognized. In either case
        you should not copy and paste the form or processing function into a live environment. None of the
        code here is considered finished and I myself will work on it again when putting it on the live pages.</p>
        
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
    public function postbox_betatest6_empty( $data, $box ) {    
        $introduction = __( 'Do not use this box, it allows multiple entries to meta box array without making the boxes.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $introduction, false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );          
       
 
                         
        $this->UI->postbox_content_footer();
    }
         
}?>