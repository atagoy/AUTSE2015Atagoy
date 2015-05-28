<?php
/**
 * Data Tools [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class CSV2POST_Datatools_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'datatools';
    
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

            // create datasource group
            array( $this->view_name . '-createuploadcsvdatasource', __( 'Create Data Source: Upload File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createuploadcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createurlcsvdatasource', __( 'Create Data Source: Import Via URL', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createurlcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createservercsvdatasource', __( 'Create Data Source: Existing Server File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createservercsvdatasource' ), true, 'activate_plugins' ),           
            array( $this->view_name . '-createdirectorycsvdatasource', __( 'Create Data Source: Process Directory/Folder', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createdirectorycsvdatasource' ), true, 'activate_plugins' ),           

            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( $this->view_name . '-rechecksourcedirectory', __( 'Re-check Source Directory', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'rechecksourcedirectory' ), true, 'activate_plugins' ),
                        
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
    * form for manual re-check of a sources directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_datatools_rechecksourcedirectory( $data, $box ) { 
        $intro = __( 'Manual re-check of source directory will make the plugin switch to newer .csv files.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
                <?php $this->UI->option_menu_datasources( 'Data Source', 'datasourceidforrecheck', 'datasourceidforrecheck' ); ?> 
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    } 
    
    /**
    * upload a new file via form to an existing data source directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_datatools_createuploadcsvdatasource( $data, $box ) { 
        $intro = __( 'Upload a .csv file. This also creates a Data Source based on your files configuration. If your data is sensitive please import the content to the database now. Then delete the .csv file from your server.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false, true );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php
            $this->UI->option_file( __( 'Select .csv File', 'csv2post' ), 'uploadsinglefile1', 'uploadsinglefile1' );
            $wp_upload_dir_array = wp_upload_dir();
            $this->UI->option_text_simple( __( 'Path', 'csv2post' ), 'newdatasourcethepath1',$wp_upload_dir_array['path'], true );                
            $this->UI->option_text( __( 'ID Column', 'csv2post' ), 'uniqueidcolumn1', 'uniqueidcolumn1', '' );
            $this->UI->option_text( __( 'Source Name', 'csv2post' ), 'newprojectname1', 'newprojectname1', '' );
            $this->FORMS->switch_basic( $box['args']['formid'], 'detectfilechanges3', 'detectfilechanges3', __( 'Detect File Changes', 'csv2post'), 'enabled', 'enabled' );
            ?>
            
            </table>
            
            <hr>
            
            <table class="form-table">
            
            <?php 
            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable1', 'deleteexistingtable1',rand(100000,999999), true);
            $this->UI->option_text( __( 'Confirm Code', 'csv2post' ), 'deleteexistingtablecode1', 'deleteexistingtablecode1', '' );
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    } 
         
    /**
    * upload a new file via form to an existing data source directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_datatools_createurlcsvdatasource( $data, $box ) { 
        $intro = __( 'Transfer your .csv file to your server using a URL. This also creates a Data Source which holds your .csv files configuration. Try my test file http://www.webtechglobal.co.uk/public/wordpress/csv2post/ComputersMain.csv', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false, true );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php                     
            $this->UI->option_text_simple( __( 'URL', 'csv2post' ), 'newdatasourcetheurl2','', true, 'URL' );
            $wp_upload_dir_array = wp_upload_dir();                
            $this->UI->option_text_simple( __( 'Path', 'csv2post' ), 'newdatasourcethepath2',$wp_upload_dir_array['path'], true );                
            $this->UI->option_text( __( 'ID Column', 'csv2post' ), 'uniqueidcolumn2', 'uniqueidcolumn2', '' );
            $this->UI->option_text( __( 'Source Name', 'csv2post' ), 'newprojectname2', 'newprojectname2', '' );
            $this->FORMS->switch_basic( $box['args']['formid'], 'detectfilechanges', 'detectfilechanges', __( 'Detect File Changes', 'csv2post'), 'enabled', 'enabled' );
            ?>
            
            </table>
            
            <hr>
            
            <table class="form-table">
            
            <?php 
            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable2', 'deleteexistingtable2',rand(100000,999999), true);
            $this->UI->option_text( __( 'Confirm Code', 'csv2post' ), 'deleteexistingtablecode2', 'deleteexistingtablecode2', '' );
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    }    
    
    /**
    * Use a .csv file already on the server as a datasource without moving or altering it.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_datatools_createservercsvdatasource( $data, $box ) { 
        $intro = __( 'Use a .csv file already uploaded to your server.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false, true );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php                     
            $wp_upload_dir_array = wp_upload_dir();                
            $this->UI->option_text_simple( __( 'Files Path', 'csv2post' ), 'newdatasourcethepath3',$wp_upload_dir_array['path'], true );                
            $this->UI->option_text( __( 'ID Column', 'csv2post' ), 'uniqueidcolumn3', 'uniqueidcolumn3', '' );
            $this->UI->option_text( __( 'Source Name', 'csv2post' ), 'newprojectname3', 'newprojectname3', '' );
            $this->FORMS->switch_basic( $box['args']['formid'], 'detectfilechanges2', 'detectfilechanges2', __( 'Detect File Changes', 'csv2post'), 'enabled', 'enabled' );         
            ?>
            
            </table>
            
            <hr>
            
            <table class="form-table">
            
            <?php 
            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable3', 'deleteexistingtable3',rand(100000,999999), true);
            $this->UI->option_text( __( 'Confirm Code', 'csv2post' ), 'deleteexistingtablecode3', 'deleteexistingtablecode3', '' );
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    }   
        
    /**
    * Point to a folder on the local server that contains one or more (usually two or more) .csv files.
    * 
    * The plugin will read all files and create a new data source using each .csv file. That is so each file
    * can be managed on its own. A Directory Data Source will be created which is not much different to
    * a normal data source in terms of data. 
    * 
    * The purpose of the Directory Data Source record will be to hold settings regarding the automatic finding
    * and handling of more .csv files in the specific directory i.e. ignore new files, import them, only create a
    * data source do not import etc.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_datatools_createdirectorycsvdatasource( $data, $box ) { 
        $intro = __( 'Process all .csv in a folder. One file is made a parent for all other files. The parent files configuration is used to create the database table which all files will be imported into. It means all files must have the same configuration.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false, true );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php                     
            $wp_upload_dir_array = wp_upload_dir();                
            $this->UI->option_text_simple( __( 'Folder Path', 'csv2post' ), 'newdirectorysource', $wp_upload_dir_array['path'], true );                
            $this->UI->option_text( __( 'ID Column (all files)', 'csv2post' ), 'uniqueidcolumnallfiles', 'uniqueidcolumnallfiles', '' );
            $this->UI->option_text( __( 'Source Name', 'csv2post' ), 'newsourcename1', 'newsourcename1', '' );
            $this->FORMS->switch_basic( $box['args']['formid'], 'detectfilechanges1', 'detectfilechanges1', __( 'Detect File Changes', 'csv2post'), 'enabled', 'enabled' );
            $this->FORMS->switch_basic( $box['args']['formid'], 'detectnewfiles1', 'detectnewfiles1', __( 'Detect New Files', 'csv2post'), 'enabled', 'enabled' );
            ?>
            
            </table>
            
            <hr>
            
            <table class="form-table">
            
            <?php 
            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable3', 'deleteexistingtable3',rand(100000,999999), true);
            $this->UI->option_text( __( 'Confirm Code', 'csv2post' ), 'deleteexistingtablecode3', 'deleteexistingtablecode3', '' );
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    }   
             
}?>