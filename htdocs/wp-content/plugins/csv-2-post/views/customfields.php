<?php
/**
 * Custom Fields [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Custom Fields [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Customfields_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'customfields';
    
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
            array( 'customfields-newcustomfield', __( 'New Custom Field', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'newcustomfield' ), true, 'activate_plugins' ),
            array( 'customfields-customfieldstable', __( 'Custom Fields', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'customfieldstable' ), true, 'activate_plugins' ),
            array( 'customfields-shopperpresscustomfieldkeys', __( 'ShopperPress Custom Field Names', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'shopperpresscustomfieldkeys' ), true, 'activate_plugins' ),
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
            $this->add_meta_box( 'customfields-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
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
    public function postbox_customfields_newcustomfield( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Create a custom field template. Every post you create in this project will get that custom field and the value will be based on the template you create.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table"> 
                <?php 
                // custom field name
                $this->UI->option_text( 'Name', 'customfieldname', 'customfieldname', '', false, 'csv2post_inputtext', 'Example: mynew_customfield' );
                $this->UI->option_switch( 'Updating', 'customfieldupdating', 'customfieldupdating', 'disabled' );
                $this->UI->option_switch( 'Unique', 'customfieldunique', 'customfieldunique', 'enabled', __( 'Yes' ), __( 'No' ) );
                ?>        
            </table>
            
            <div id="poststuff">
                <?php 
                $cfcontent = '';
                if( isset( $_POST['customfielddefaultcontent'] ) ){$cfcontent = $_POST['customfielddefaultcontent'];}?>
                <?php wp_editor( $cfcontent, 'customfielddefaultcontent', array( 'textarea_name' => 'customfielddefaultcontent' ) );?>
            </div>
        
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
    public function postbox_customfields_customfieldstable( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This is a table of the custom fields you have setup. This will add meta data to every post you make in this project.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );

        // ensure we have an array
        if(!isset( $this->current_project_settings['customfields']['cflist'] ) || !is_array( $this->current_project_settings['customfields']['cflist'] ) ){
            $this->current_project_settings['customfields']['cflist'] = array();
        }
       
        $CFTable = new C2P_CustomFields_Table();
        $CFTable->prepare_items_further( $this->current_project_settings['customfields']['cflist'],100);
        ?>
                    
        <form id="movies-filter" method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $CFTable->display() ?>
        </form>
        
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
    public function postbox_customfields_shopperpresscustomfieldkeys( $data, $box ) {     
        echo '<p>' . __( 'A list of the custom field names (meta keys) for the ShopperPress theme.', 'csv2post' ) . '</p>';
        
        echo '<ul>';
        echo '<li>price<li>';
        echo '<li>old_price<li>';
        echo '<li>image<li>';
        echo '<li>images<li>';
        echo '<li>thumbnail<li>';
        echo '<li>shipping<li>';
        echo '<li>warranty<li>';
        echo '<li>qty<li>';
        echo '<li>featured<li>';
        echo '<li>customlist1<li>';
        echo '<li>customlist2<li>';
        echo '<li>amazon_link<li>';
        echo '<li>amazon_guid<li>';
        echo '<li>file<li>';
        echo '<li>type<li>';
        echo '</ul>';
    }    
}

class C2P_CustomFields_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
        
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }

    function column_default( $item, $column_name ){
        switch( $column_name){
            case 'delete':
                return '<a href="'. wp_nonce_url( admin_url() . 'admin.php?page=' . $_GET['page'] . '&csv2postaction=deletecustomfieldrule&cfid=' . $item['id'], 'deletecustomfieldrule' ) . '" title="Delete" class="button c2pbutton">Delete</a>';
                break;                                                                   
            default:
                return $item[$column_name];
        }        
    }

    /*
    function column_title( $item){

    } */

    function get_columns() {
        return array(
                'name' => 'Name/Key',
                'unique' => 'Unique',
                'updating' => 'Updating',
                'value' => 'Template',
                'delete' => 'Delete'
             ); 
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

    function prepare_items_further( $data, $per_page = 5 ) {
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