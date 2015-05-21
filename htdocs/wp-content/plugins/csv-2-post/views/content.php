<?php
/**
 * Content [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Content [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Content_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'content';
    
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
            array( $this->view_name . '-defaulttitletemplate', __( 'Default Title Template', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaulttitletemplate' ), true, 'activate_plugins' ),
            array( $this->view_name . '-defaultcontenttemplate', __( 'Default Content Template', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaultcontenttemplate' ), true, 'activate_plugins' ),
            array( $this->view_name . '-newcontenttemplate', __( 'Create Content Template', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'newcontenttemplate' ), true, 'activate_plugins' ),
            array( $this->view_name . '-multipledesignsrules', __( 'Multiple Design Rules', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'multipledesignsrules' ), true, 'activate_plugins' ),
            array( $this->view_name . '-spintaxtest', __( 'Spintax Test', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'spintaxtest' ), true, 'activate_plugins' ),
            array( $this->view_name . '-groupimportlocalimages', __( 'Group Import Local Images', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'groupimportlocalimages' ), true, 'activate_plugins' ),
            array( $this->view_name . '-2000freehours', __( '2000 Free Hours', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => '2000freehours' ), true, 'activate_plugins' ),
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
            $this->add_meta_box( 'content-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
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
    public function postbox_content_defaulttitletemplate( $data, $box ) {    
        global $wpdb,$c2p_settings;
        
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Enter Column Replacement Tokens to create a template. On submission an example will be created providing you have imported some of your .csv files rows.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        $result = false;
        ?>  
                       
            <table class="form-table">                  
            <?php 
            // set the default value and generate a sample title using a random row of imported data
            $titletemplate = ''; 
            $generated_title = '';
            if( isset( $this->current_project_settings['titles']['defaulttitletemplate'] ) ){
                $titletemplate = $this->current_project_settings['titles']['defaulttitletemplate'];
                $generated_title = $titletemplate;
                
                // build example title - need to get projects source ID's
                $sourceid_array = $this->CSV2POST->get_project_sourcesid( $c2p_settings['currentproject'] );

                // for multiple table sources, track which tables have been queried
                $tables_already_used = array();

                // loop through source ID's
                foreach( $sourceid_array as $key => $source_id )
                {
                    // get the source row for the current ID
                    $row = $this->DB->selectrow( $wpdb->c2psources, 'sourceid = "' . $source_id . '"', 'tablename' );

                    // data source may have been deleted 
                    if( $row )
                    {
                        // avoid using same database table twice
                        if( in_array( $row->tablename, $tables_already_used ) ){
                            continue;
                        }
                        
                        $tables_already_used[] = $row->tablename;
                        $result = $this->DB->selectorderby( $row->tablename, null, 'c2p_rowid', '*', 20, ARRAY_A );
                    }
                }
                
                if( $result ) {
                    $rand_max = count( $result ) - 1;
                    
                    $projectcolumns = $this->CSV2POST->get_project_columns_from_db( $c2p_settings['currentproject'] );
                    
                    foreach( $result[ rand( 1, $rand_max ) ] as $a_column => $imported_data_value ){  
                        foreach( $projectcolumns as $table_name => $columnfromdb ){            
                            $generated_title = str_replace( '#'. $a_column . '#', $imported_data_value, $generated_title );
                        }    
                    }    
                }
            }

            if( empty( $generated_title ) ){
                $generated_title = 'Please import data to see a sample based on your template.';
            }
            
            $this->UI->option_text( __( 'Title Template', 'csv2post' ), 'defaulttitletemplate', 'defaulttitletemplate', $titletemplate, false, 'csv2post_inputtext',null,null,null,true );
            $this->UI->option_text( __( 'Title Sample', 'csv2post' ), 'sampletitle', 'sampletitle', $generated_title, true, 'csv2post_inputtext' );
            ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }    
    
    /**
    * Form for creating the default content template, the design stored in project settings not just as a post type.
    * This is for users who do not want the post type for content templates to be registered in the blog.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_content_defaultcontenttemplate( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This is the first editor you should used. If you do not plan to create multiple templates in a more advanced project, this is the only editor you need to use.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  
            <div id="poststuff">
            
                <?php 
                $wysiwygdefaultcontent = ''; 
                if( isset( $this->current_project_settings['content']['wysiwygdefaultcontent'] ) ){
                    $wysiwygdefaultcontent = $this->current_project_settings['content']['wysiwygdefaultcontent'];
                } 
                wp_editor( $wysiwygdefaultcontent, 'wysiwygdefaultcontent', array( 'textarea_name' => 'wysiwygdefaultcontent' ) );
                ?>
            
            </div>
            
            <br>
            
        <?php 
        $this->UI->postbox_content_footer();
    } 
       
    /**
    * Form for creating a new content template design. Only needed by users who plan to dynamically switched between
    * multiple content templates.
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.34
    * @version 1.0.0
    */
    public function postbox_content_newcontenttemplate( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Use this form to create new content templates for dynamically switching between them based on rules. The WYSIWYG editor is automatically populated with your default design to aid in creating alternative but similar post content designs.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <div id="poststuff">
            
                <?php 
                // set the content default, apply last $_POST value ELSE apply a stored default ELSE leave it empty
                $wysiwygdefaultcontent = '';
                if( isset( $_POST['wysiwygdefaultcontent2'] ) ) {
                    $wysiwygdefaultcontent = $_POST['wysiwygdefaultcontent2'];
                } elseif( isset( $this->current_project_settings['content']['wysiwygdefaultcontent'] ) ){
                    $wysiwygdefaultcontent = $this->current_project_settings['content']['wysiwygdefaultcontent'];
                } 
                
                wp_editor( $wysiwygdefaultcontent, 'editorfornewdesign', array( 'textarea_name' => 'editorfornewdesign' ) );
                ?>
            
            </div>
            <table class="form-table">
            <?php
            $this->UI->option_text_simple( __( 'Design Name', 'csv2post' ),'contenttemplatetitle2','',false);
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
    public function postbox_content_multipledesignsrules( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Apply different content templates depending on specific values in your data i.e. if each category requires a different layout, use of different images and styles.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

            <table class="form-table">
            <?php
            for( $i=1;$i<=3;$i++){
                
                // design rules data
                $designrulecolumn_table = ''; 
                $designrulecolumn_column = '';
                if( isset( $this->current_project_settings['content']["designrulecolumn$i"]['table'] ) ){$designrulecolumn_table = $this->current_project_settings['content']["designrulecolumn$i"]['table'];}
                if( isset( $this->current_project_settings['content']["designrulecolumn$i"]['column'] ) ){$designrulecolumn_column = $this->current_project_settings['content']["designrulecolumn$i"]['column'];}             
                $this->UI->option_projectcolumns( __( 'Data' ), $c2p_settings['currentproject'], "designrulecolumn$i", "designrulecolumn$i", $designrulecolumn_table, $designrulecolumn_column, 'notrequired', 'Not Required' );
    
                $designrulecolumn = ''; 
                if( isset( $this->current_project_settings['content']["designruletrigger$i"] ) ){$designrulecolumn = $this->current_project_settings['content']["designruletrigger$i"];}            
                $this->UI->option_text( "Trigger $i", "designruletrigger$i", "designruletrigger$i", $designrulecolumn, false );
                
                $designtemplate = 'nocurrent123'; 
                if( isset( $this->current_project_settings['content']["designtemplate$i"] ) ){$designtemplate = $this->current_project_settings['content']["designtemplate$i"];}            
                $this->UI->option_menu_posttemplates( "Design $i", "designtemplate$i", "designtemplate$i", $designtemplate); 
            }
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
    public function postbox_content_spintaxtest( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Test a spintax i.e. The best data import for WordPress is {CSV 2 POST|Easy CSV Importer|Data Importer for Dummies}.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
            <?php $this->UI->option_textarea(_( 'Text' ), 'spintaxteststring', 'spintaxteststring',20,40, '' );?>
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
    public function postbox_content_groupimportlocalimages( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Do you have a folder of images on your server? We can import them to the WordPress Media Library and use them as featured images. There are various approaches and your data does not need to include the full path to each image.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
    
        global $c2p_settings;
        
        if(!isset( $this->current_project_settings['content']['groupedimagesdir'] ) || !file_exists(ABSPATH . $this->current_project_settings['content']['groupedimagesdir'] ) ){
            echo $this->UI->notice_return( 'warning', 'Small', __( 'Grouped Image Directory Does Not Exist' ), __( 'Please create or change the path to your image directory where groups of images are stored.' ), null, true );
        }        
        ?>  

        <table class="form-table">
            <?php
            // enable this feature
            $enablegroupedimageimport = 'disabled';
            if( isset( $this->current_project_settings['content']['enablegroupedimageimport'] ) ){$enablegroupedimageimport = $this->current_project_settings['content']['enablegroupedimageimport'];}
            $this->UI->option_switch( __( 'Enable' ), 'enablegroupedimageimport', 'enablegroupedimageimport', $enablegroupedimageimport, 'Yes', 'No', 'disabled' );
            
            // column of filenames or partial filenames
            $localimages_table = ''; 
            $localimages_column = '';
            if( isset( $this->current_project_settings['content']["localimages"]['table'] ) ){$localimages_table = $this->current_project_settings['content']["localimages"]['table'];}
            if( isset( $this->current_project_settings['content']["localimages"]['column'] ) ){$localimages_column = $this->current_project_settings['content']["localimages"]['column'];}                 
            $this->UI->option_projectcolumns( 'Filename Data', $c2p_settings['currentproject'], 'localimagesdata', 'localimagesdata', $localimages_table, $localimages_column);
                            
            // search for increment
            $incrementalimages = 'enabled';
            if( isset( $this->current_project_settings['content']['incrementalimages'] ) ){$enablegroupedimageimport = $this->current_project_settings['content']['incrementalimages'];}        
            $this->UI->option_switch( 'Incremental', 'incrementalimages', 'incrementalimages', $enablegroupedimageimport, 'Yes', 'No', 'enabled' );
            
            // image dir
            $imgdir = '';
            if( isset( $this->current_project_settings['content']['groupedimagesdir'] ) ){$imgdir = $this->current_project_settings['content']['groupedimagesdir'];}
            $this->UI->option_text_simple( 'Image Directory', 'groupimportdir', $imgdir, 'Enter the path to your images.' );
            ?>
        </table>
     
        <?php 
        $this->UI->postbox_content_footer();
    }  

    /**
    * 2000 Free Hours side box
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_content_2000freehours() {
        echo '<p>' . __( "Over 2000 hours (and counting) have gone into providing a free edition and providing free support.
        I have no income if users do not donate as I'm a full-time father of 3 children under six. You can imaging the juggling I done to make this
        project happen. You do not need to donate to show your appreciation, just sharing the plugin is enough.
        I look forward to working with anyone who feels this direct request and openess is fair.", 'csv2post' ) . '</p>';
    }      
}?>