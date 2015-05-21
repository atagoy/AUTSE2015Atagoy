<?php
/**
 * Data Sources [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class for Data Sources [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Datasources_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'datasources';
    
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
            array( $this->view_name . '-createuploadcsvdatasource', __( 'Create Data Source: Upload File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createuploadcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createurlcsvdatasource', __( 'Create Data Source: Import Via URL', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createurlcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createservercsvdatasource', __( 'Create Data Source: Existing Server File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createservercsvdatasource' ), true, 'activate_plugins' ),           
            array( $this->view_name . '-createdirectorycsvdatasource', __( 'Create Data Source: Process Directory/Folder', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createdirectorycsvdatasource' ), true, 'activate_plugins' ),           
            array( $this->view_name . '-changecsvfilepath', __( 'Change CSV File Path', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'changecsvfilepath' ), true, 'activate_plugins' ),
            array( $this->view_name . '-rechecksourcedirectory', __( 'Re-check Source Directory', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'rechecksourcedirectory' ), true, 'activate_plugins' ),
            array( $this->view_name . '-urlimporttoexistingsource', __( 'URL Import To Data Source', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'urlimporttoexistingsource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-uploadfiletodatasource', __( 'Upload File To Data Source', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'uploadfiletodatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-deletedatasource', __( 'Delete Data Source', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'deletedatasource' ), true, 'activate_plugins' ),
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
    * upload a new file via form to an existing data source directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_datasources_createuploadcsvdatasource( $data, $box ) { 
        $intro = __( 'Upload a .csv file. This also creates a Data Source based on your files configuration. If your data is sensitive please
        import the content to the database now. Then delete the .csv file from your server.', 'csv2post' );
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

            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable1', 'deleteexistingtable1',rand(100000,999999), true);
            $this->UI->option_text( __( 'Enter Code', 'csv2post' ), 'deleteexistingtablecode1', 'deleteexistingtablecode1', '' );
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
    * @version 1.0.0
    */
    public function postbox_datasources_createurlcsvdatasource( $data, $box ) { 
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

            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable2', 'deleteexistingtable2',rand(100000,999999), true);
            $this->UI->option_text( __( 'Enter Code', 'csv2post' ), 'deleteexistingtablecode2', 'deleteexistingtablecode2', '' );
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
    * @version 1.0.0
    */
    public function postbox_datasources_createservercsvdatasource( $data, $box ) { 
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

            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable3', 'deleteexistingtable3',rand(100000,999999), true);
            $this->UI->option_text( __( 'Enter Code', 'csv2post' ), 'deleteexistingtablecode3', 'deleteexistingtablecode3', '' );
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
    * @version 1.0.0
    */
    public function postbox_datasources_createdirectorycsvdatasource( $data, $box ) { 
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

            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Delete Existing Table', 'csv2post' ), 'deleteexistingtable3', 'deleteexistingtable3',rand(100000,999999), true);
            $this->UI->option_text( __( 'Enter Code', 'csv2post' ), 'deleteexistingtablecode3', 'deleteexistingtablecode3', '' );
            ?>
            
            </table>

        <?php 
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
    public function postbox_datasources_changecsvfilepath( $data, $box ) {  
        global $wpdb;
        $query_results = $this->DB->selectwherearray( $wpdb->c2psources, 'sourceid = sourceid', 'sourceid', '*' );
        if(!$query_results){
            $intro = __( 'No sources were found.' );
        }else{
            $intro = __( 'The new .csv file must have identical configuration...' );
        }
          
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

        <?php if( $query_results){?>
        
            <table class="form-table">
                <?php $this->UI->option_text_simple( __( 'New Path' ), 'newpath', '', true);?> 
                <?php $this->UI->option_menu_datasources(); ?>
            </table>
        
        <?php }?>
        
        <?php 
        $this->UI->postbox_content_footer();
    } 
    
    /**
    * form for manual re-check of a sources directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_datasources_rechecksourcedirectory( $data, $box ) { 
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
    * import a new file via URL to an existing data source directory
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_datasources_urlimporttoexistingsource( $data, $box ) { 
        $intro = __( 'Import a .csv file via URL to an existing data source directory to add newer data.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php
            $this->UI->option_text_simple( __( 'URL', 'csv2post' ), 'newdatasourcetheurl','', true, 'URL' );
            $this->UI->option_menu_datasources( 'Data Source', 'newprojectdatasource', 'newprojectdatasource' );
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
    * @version 1.0.0
    */
    public function postbox_datasources_uploadfiletodatasource( $data, $box ) { 
        $intro = __( 'Upload a .csv file to an existing data source directory for adding newer data.', 'csv2post' );
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], $intro, false, true );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php
            $this->UI->option_file( __( 'Select .csv File', 'csv2post' ), 'uploadsinglefile', 'uploadsinglefile' );
            $this->UI->option_menu_datasources( 'Data Source', 'datasourcefornewfile', 'datasourcefornewfile' );
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();
    } 
              
    /**
    * details about why there is so much space in the plugin and the sandbox approach
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_datasources_emptyspace( $data, $box ) { 
        _e( 'The short story is that the plugin has an intended sandbox design. I want users to 
        position the meta boxes (accordians) they use at the top and the rest to the bottom. The
        sidebars are intended for information rather than forms or quick tools where a single button
        is required. The plugin was re-developed beginning 2014 and a lot of features are still to be added.
        The sandbox approach will really come into effect then as the interface will be packed. The first thing
        users will do is hide all the boxes they never use. It will almost be like creating a custom plugin and 
        progress is being made to deliver that experience.', 'csv2post' );
    } 
    
    /**
    * Form for deleting a specific data source.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_datasources_deletedatasource( $data, $box ) {
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], '', false, true );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">

            <?php
            $this->UI->option_menu_datasources( 'Data Source', 'datasourcefornewfile', 'datasourcefornewfile' );
            // offer option to delete an existing table if the file matches one, user needs to enter random number
            $this->UI->option_text( __( 'Copy Code', 'csv2post' ), 'confirmdeletedatasourcecode', 'confirmdeletedatasourcecode',rand(1000,9999), true);
            $this->UI->option_text( __( 'Enter Code', 'csv2post' ), 'confirmdeletedatasource', 'confirmdeletedatasource', '' );            
            ?>
            
            </table>

        <?php 
        $this->UI->postbox_content_footer();    
    }   
}?>