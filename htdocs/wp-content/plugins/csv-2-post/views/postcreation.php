<?php
/**
 * Post Creation Tools [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Post Creation Tools [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Postcreation_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'postcreation';
    
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
            array( $this->view_name . '-createpostsbasic', __( 'Create Posts', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createpostsbasic' ), true, 'activate_plugins' ),
            array( $this->view_name . '-updatepostsbasic', __( 'Update Posts', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'updatepostsbasic' ), true, 'activate_plugins' ),
            array( $this->view_name . '-updatepostsbasicnewdataonly', __( 'Update Posts: New Data Only', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'updatepostsbasicnewdataonly' ), true, 'activate_plugins' ),
            array( $this->view_name . '-updatepostsbasicprojectchangesonly', __( 'Update Posts: Project Changes Only', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'updatepostsbasicprojectchangesonly' ), true, 'activate_plugins' ),
            array( $this->view_name . '-updatespecificpost', __( 'Update Specific Post', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'updatespecificpost' ), true, 'activate_plugins' ),
            array( $this->view_name . '-queryduplicateposts', __( 'Delete Duplicate Posts', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'queryduplicateposts' ), true, 'activate_plugins' ),
            array( $this->view_name . '-refreshallposts', __( 'Refresh All Posts', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'refreshallposts' ), true, 'activate_plugins' ),
            array( $this->view_name . '-undoprojectposts', __( 'Undo Projects Posts', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'undoprojectposts' ), true, 'activate_plugins' ),
            array( $this->view_name . '-resetimportedrows', __( 'Reset Imported Rows', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'resetimportedrows' ), true, 'activate_plugins' ),
            array( $this->view_name . '-freeupdatingfeatures', __( 'Free Advanced Features Require Donations', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'freeupdatingfeatures' ), true, 'activate_plugins' ),
            array( $this->view_name . '-recreatemissingposts', __( 'Re-Create Missing Posts', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'recreatemissingposts' ), true, 'activate_plugins' ),
            array( $this->view_name . '-masspublishposts', __( 'Mass Publish Posts', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'masspublishposts' ), true, 'activate_plugins' )
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
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' );// extended by CSV2POST_Forms
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

            // only output meta boxes
            if( $this->purpose == 'normal' ) {
                self::metaboxes();
            } elseif( $this->purpose == 'dashboard' ) {
                //self::dashboard();
            } elseif( $this->purpose == 'customdashboard' ) {
                return self::meta_box_array();
            } else {
                // do nothing - can call metaboxes() manually and place them 
            }        
              
       } else {
            $this->add_meta_box( 'tools-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
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
    public function postbox_postcreation_createpostsbasic( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Import your data, create a title template, a content template and your ready to use this form. Enter the number of posts you would like to create. Start with small numbers to test your project settings.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_text( __( 'Total Posts' ), 'totalposts', 'totalposts', '1' )?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    } 
    
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_updatepostsbasic( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Update posts where applicable. This will only update posts if a change of settings or data has occurred.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_text( __( 'Total Posts' ), 'totalposts', 'totalposts', '1' )?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }

    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_updatepostsbasicnewdataonly( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Update posts created by the current project if the imported record used to create them has been updated.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_text( __( 'Total Posts' ), 'totalposts', 'totalposts', '1' )?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }  
      
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_updatepostsbasicprojectchangesonly( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Update posts that have not been updated since the current projects settings were changed.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_text( __( 'Total Posts' ), 'totalposts', 'totalposts', '1' )?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }
        
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_updatespecificpost( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Enter the ID of a post created by CSV 2 POST to initiate an update. This is a great way to test your updating configuration.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_text( __( 'Post ID' ), 'updatespecificpostid', 'updatespecificpostid', '1' );?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }   
     
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_queryduplicateposts( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'If you suspect your .csv file contains duplicate rows you can use this to find the resulting duplicate posts then delete one of them.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            
            <tr valign="top">
                <th scope="row">Force Delete (do not use trash)</th>
                <td><?php $this->UI->option_checkbox_single( 'forcedelete', 'Yes', 'forcedelete', true);?></td>
            </tr>
            
            <?php $this->UI->option_subline( __( 'Post Title' ) );?>
            <?php $this->UI->option_text( __( 'Safety Code' ), 'deletetduplicatebytitlecode', 'deletetduplicatebytitlecode',rand(99,9999), true);?>
            <?php $this->UI->option_text( __( 'Confirm Code' ), 'deletetduplicatebytitleconfirm', 'deletetduplicatebytitleconfirm', '' );?>            
            
            <?php $this->UI->option_subline( __( 'One Post Per Row' ) );?>
            <?php $this->UI->option_text( __( 'Safety Code' ), 'deleteduplicatepostssafetycode', 'deleteduplicatepostssafetycode',rand(99,9999), true);?>
            <?php $this->UI->option_text( __( 'Confirm Code' ), 'safetycodeconfirmed', 'safetycodeconfirmed', '' );?>
            
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }   
      
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_refreshallposts( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'If your using Systematic Post Updating you can force an update on all posts for the current active project you are working on by submitting this form.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        $this->UI->postbox_content_footer();
    }      
    
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_undoprojectposts( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Delete posts created by the current project. Currently has a restriction to be safe but it can easily inreased by editing source or the tools.php view file.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <tr valign="top">
                <th scope="row">Force Delete (do not use trash)</th>
                <td><?php $this->UI->option_checkbox_single( 'forcedelete', 'Yes', 'forcedelete', true);?></td>
            </tr>            
            <?php $this->UI->option_text( __( 'Limit' ), 'undopostslimit', 'undopostslimit', 50, true);?>
            <?php $this->UI->option_text( __( 'Safety Code' ), 'undoallpostssafecode', 'undoallpostssafecode',rand(9999,99999), true);?>
            <?php $this->UI->option_text_simple( __( 'Confirm Code' ), 'undoallpostsconfirm', '', __( 'Please enter the random number shown above this field.' ) );?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }    
    
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_resetimportedrows( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Delete 100% of the data imported for the current projects data source/s. Please use with care.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] ); 
        $this->UI->postbox_content_footer();
    }  
        
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_postcreation_freeupdatingfeatures( $data, $box ) {    
        echo '<p>';
        
        _e( 'No other FREE WordPress importer offers systematic updating at this time (January 2015). It is a tool
        that allows a website to refresh content constantly. The refresh of a post or page is triggered by a visitor
        or WordPress loading. It is highly valuable and deserves donation.', 'csv2post' );
        
        echo '</p>';
    } 
    
    /**
    * Locates imported rows that are meant to have a post but the post cannot be found.
    * 1. Re-creating the posts is optional
    * 2. Total missing posts is confirmed after subsmission
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function postbox_postcreation_recreatemissingposts( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'If admin accidently deletes posts created by CSV 2 POST you will need to go through the correct repair process. Tools like this are to help avoid it being a complex and time consuming task. This processes will query the database one or more times per record imported. This may only work with 200 or less missing posts. Contact WebTechGlobal for consultation if you are dealing with a far greater number of accidently deleted posts.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] ); 
        ?>  

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e( 'Re-Create Posts', 'csv2post' ); ?></th>
                    <td><?php $this->UI->option_checkbox_single( 'recreatemissingposts', __( 'Yes', 'csv2post' ), 'recreatemissingpostsyes', 'off', 1 );?></td>
                </tr>            
            </table>
        
        <?php       
        $this->UI->postbox_content_footer();
    } 
    
    /**
    * Mass publish posts.
    * 1. Select all posts for current project.
    * 2. Select all posts for CSV 2 POST.
    * 3. Select all posts even those not created by CSV 2 POST.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_postcreation_masspublishposts( $data, $box ) {
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Mass publish draft posts with ability to select the scope.', 'csv2post' ), false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] ); 
     
        $items_array = array( 'currentproject' => __( 'Current Project', 'csv2post' ), 'allprojects' => __( 'All Projects', 'csv2post' ), 'entireblog' => __( 'Entire Blog', 'csv2post' )  );
        ?>  

            <table class="form-table">
                <tr valign="top">
                    <td><?php $this->FORMS->radiogroup_basic( $box['args']['formid'], 'masspublishscope', 'masspublishscope', __( 'Mass Publish Scope', 'csv2post' ), $items_array, 'currentproject', true, array() ); ?></td>
                </tr>            
            </table>
        
        <?php       
        $this->UI->postbox_content_footer();    
    }
}?>