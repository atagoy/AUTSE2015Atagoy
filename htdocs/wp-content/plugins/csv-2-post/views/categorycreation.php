<?php
/**
 * Category Creation [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class for Beta Testing [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Categorycreation_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'categorycreation';

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
            array( $this->view_name . '-categorycreation', __( 'Category Creation', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'categorycreation' ), true, 'activate_plugins' ),
            array( $this->view_name . '-setpostscategories', __( 'Set Posts Categories', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'setpostscategories' ), true, 'activate_plugins' ),
            array( $this->view_name . '-resetpostscategories', __( 'Re-Set Posts Categories', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'resetpostscategories' ), true, 'activate_plugins' ),
            array( $this->view_name . '-uncategorisedpoststable', __( 'Uncategorised Posts Table', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'uncategorisedpoststable' ), true, 'activate_plugins' ),
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
        $this->Class_Categories = CSV2POST::load_class( 'C2P_Categories', 'class-categories.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->FORMS = CSV2POST::load_class( 'CSV2POST_FORMS', 'class-forms.php', 'classes' );
                        
        // load the current project row and settings from that row
        if( isset( $c2p_settings['currentproject'] ) && $c2p_settings['currentproject'] !== false ) {
            
            $this->project_object = $this->CSV2POST->get_project( $c2p_settings['currentproject'] ); 
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
            $this->add_meta_box( $this->view_name . '-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
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
    * post box function for category creation
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_categorycreation_categorycreation( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Once you have selected your category columns you can initiate category creation using this form.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] ); 
        $this->UI->postbox_content_footer();
    }      
    
    /**
    * post box function for category updating
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_categorycreation_setpostscategories( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Does not create categories. Submitting this form will put posts by CSV 2 POST into categories based on your category column settings.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] ); 
        $this->UI->postbox_content_footer();
    }
        
    /**
    * post box function for resetting post categories by removing the relationship
    * and putting all posts into Uncategorized
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_categorycreation_resetpostscategories( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Another user requested feature. This one removes all of the current projects posts from their categories. They will end up in your WP blogs default category.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] ); 
        $this->UI->postbox_content_footer();
    }
 
    /**
    * displays a table of the posts that are in the uncategorised category and 
    * were created by CSV 2 POST. This allows the user to investigate why they are
    * not categorised.  
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_categorycreation_uncategorisedpoststable( $data, $box ) {    
        global $c2p_settings, $wpdb;
        
        // load uncategorised posts table class
        //$table = CSV2POST::load_class( 'CSV2POST_UncatPosts_Table', 'class-uncategorisedpoststable.php', 'classes' );
               
        // first query all posts in categorised that have the c2p_projects meta
        $args = array(
            'category'         => '1',
            'order'            => 'DESC',
            'meta_key'         => 'c2p_project',
            'meta_value'       => $c2p_settings['currentproject'],
            'post_type'        => 'post',
            'post_status'      => 'any' );       
        $uncatposts = get_posts( $args );
        
        // build array for table, involves getting each posts imported row to display category terms from the data
        $items_array = array();
        foreach( $uncatposts as $key => $post_array ){
            $items_array[ $key ]['ID'] = $post_array->ID;
            $items_array[ $key ]['post_title'] = $post_array->post_title;
            $items_array[ $key ]['c2p_project'] = $post_array->c2p_project;
            $items_array[ $key ]['level_one'] = '';
            $items_array[ $key ]['level_two'] = '';
            $items_array[ $key ]['level_three'] = '';
            $items_array[ $key ]['level_four'] = '';
            $items_array[ $key ]['level_five'] = '';
        }

        $SourcesTable = new CSV2POST_UncatPosts_Table();
        $SourcesTable->prepare_items_further( $items_array,10);
        ?>

        <form id="movies-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $SourcesTable->display() ?>
        </form>
        
        <?php 
    }    
}?>