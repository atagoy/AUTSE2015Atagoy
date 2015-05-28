<?php
/**
 * All projects tools view.   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.2.0
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class CSV2POST_allprojectstools_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'allprojectstools';
    
    public $purpose = 'normal';// normal, dashboard, customdashboard

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

            array( $this->view_name . '-newprojectusingexistingsource', __( 'Create New Project', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'newprojectusingexistingsource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-deleteproject', __( 'Delete Project', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'deleteproject' ), true, 'activate_plugins' ),
            array( $this->view_name . '-setactiveproject', __( 'Set Currently Active Project', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'setactiveproject' ), true, 'activate_plugins' ),
        
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
    public function postbox_allprojectstools_newprojectusingexistingsource( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'A project holds the settings that configure the posts you wish to create. We can make multiple projects, even using the same imported data, but each creating different posts in different ways.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
                <?php
                $this->UI->option_text( 'Project Name', 'newprojectname', 'newprojectname', '' );
                $this->UI->option_switch( 'Apply Defaults', 'applydefaults', 'applydefaults', false, 'Yes', 'No', 'disabled' );
                $this->UI->option_menu_datasources( 'Data Source', 'newprojectdatasource', 'newprojectdatasource', null, true );
                ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();               
    }
         
    /**
    * Delete any project.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_allprojectstools_deleteproject( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Delete a project. This will not delete posts created by a project or effect the data source the project is using.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  
                   
            <table class="form-table">
            <?php
                $rand = rand(100000,999999);
                $this->UI->option_text( 'Project ID', 'projectid', 'projectid', '' );
                $this->UI->option_text( 'Code', 'randomcode', 'randomcode', $rand, true);
                $this->UI->option_text( 'Confirm Code', 'confirmcode', 'confirmcode', '' );
            ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();               
    } 
    
    /**
    * Change the current active project in admin.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0
    */
    public function postbox_allprojectstools_setactiveproject( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], 
        __( 'Most of this plugins pages display the settings for a single project. This is called the Current Project. Enter a projects ID here to set it as the Current Project.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  
                   
            <table class="form-table">
            <?php
            $this->UI->option_text( 'Project ID', 'setprojectid', 'setprojectid', '' );
            ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();              
    }  
}?>