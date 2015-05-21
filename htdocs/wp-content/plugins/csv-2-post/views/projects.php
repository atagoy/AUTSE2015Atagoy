<?php
/**
 * Main [section] - Projects [page]
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class for Main [section] - Projects [page]
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Projects_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'projects';
    
    public $purpose = 'normal';// normal, dashboard

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
            array( 'projects-newprojectusingexistingsource', __( 'Create New Project', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'newprojectusingexistingsource' ), true, 'activate_plugins' ),
            array( 'projects-allprojectstable', __( 'All Projects Table', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'allprojectstable' ), true, 'activate_plugins' ),
            array( 'projects-deleteproject', __( 'Delete Project', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'deleteproject' ), true, 'activate_plugins' ),
            array( 'projects-setactiveproject', __( 'Set Currently Active Project', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'setactiveproject' ), true, 'activate_plugins' ),
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
        global $c2p_settings;
        
        // create constant for view name
        if(!defined( "WTG_CSV2POST_VIEWNAME") ){define( "WTG_CSV2POST_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' ); 
        $this->DB = CSV2POST::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->FORMS = CSV2POST::load_class( 'CSV2POST_FORMS', 'class-forms.php', 'classes' );
                              
        // set current project values
        if( isset( $c2p_settings['currentproject'] ) && $c2p_settings['currentproject'] !== false ) {    
            $this->project_object = $this->CSV2POST->get_project( $c2p_settings['currentproject'] ); 
            if( !$this->project_object ) {
                $this->current_project_settings = false;
            } else {
                $this->current_project_settings = maybe_unserialize( $this->project_object->projectsettings ); 
            }
        }
        
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
    * @since 0.0.2
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
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_projects_allprojectstable( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This plugin can make use of data in multiple tables to create a single post. This is a list of the tables for your current project.', 'csv2post' ), false );        

        $query_results = $this->CSV2POST->query_projects();
        $ProjectsTable = new C2P_Projects_Table();
        $ProjectsTable->prepare_items_further( $query_results,5);
        ?>

        <form id="movies-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $ProjectsTable->display() ?>
        </form>
        
        <?php                
    }  
    
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_projects_newprojectusingexistingsource( $data, $box ) {    
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
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_projects_deleteproject( $data, $box ) {    
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
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_projects_setactiveproject( $data, $box ) {    
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