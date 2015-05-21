<?php
/**
 * Post Settings [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Post Settings [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Postsettings_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'postsettings';
    
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
            array( 'postsettings-basicpostoptions', __( 'Common Options', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'basicpostoptions' ), true, 'activate_plugins' ),
            array( 'postsettings-databasedoptions', __( 'Options Requiring Data', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'databasedoptions' ), true, 'activate_plugins' ),
            array( 'postsettings-authoroptions', __( 'Author Options', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'authoroptions' ), true, 'activate_plugins' ),
            array( 'postsettings-defaulttagrules', __( 'Tag Rules', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaulttagrules' ), true, 'activate_plugins' ),
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
            $this->add_meta_box( 'postsettings-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
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
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_postsettings_basicpostoptions( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Basic options all posts have. If you do not submit this form all posts will use your blogs own defaults. So feel free to make use of WordPress own core settings instead.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">  
            <?php
            // status 
            $poststatus = 'nocurrent123';
            if( isset( $this->current_project_settings['basicsettings']['poststatus'] ) ){$poststatus = $this->current_project_settings['basicsettings']['poststatus'];}
            $this->UI->option_radiogroup( __( 'Post Status', 'csv2post' ), 'poststatus', 'poststatus', array( 'draft' => 'Draft', 'auto-draft' => 'Auto-Draft', 'publish' => 'Publish', 'pending' => 'Pending', 'future' => 'Future', 'private' => 'Private' ), $poststatus, 'publish' );             
            
            // ping status
            $pingstatus = 'nocurrent123';
            if( isset( $this->current_project_settings['basicsettings']['pingstatus'] ) ){$pingstatus = $this->current_project_settings['basicsettings']['pingstatus'];}
            $this->UI->option_radiogroup( __( 'Ping Status', 'csv2post' ), 'pingstatus', 'pingstatus', array( 'open' => 'Open', 'closed' => 'Closed' ), $pingstatus, 'closed' );
            
            // comment status
            $commentstatus = 'nocurrent123';
            if( isset( $this->current_project_settings['basicsettings']['commentstatus'] ) ){$commentstatus = $this->current_project_settings['basicsettings']['commentstatus'];}            
            $this->UI->option_radiogroup( __( 'Comment Status' ), 'commentstatus', 'commentstatus', array( 'open' => 'Open', 'closed' => 'Closed' ), $commentstatus, 'open' );
            
            // default author
            $defaultauthor = '';
            if( isset( $this->current_project_settings['basicsettings']['defaultauthor'] ) ){$defaultauthor = $this->current_project_settings['basicsettings']['defaultauthor'];}
            $this->UI->option_menu_users( __( 'Default Author' ), 'defaultauthor', 'defaultauthor', $defaultauthor);
            
            // default category
            $defaultcategory = '';
            if( isset( $this->current_project_settings['basicsettings']['defaultcategory'] ) ){$defaultcategory = $this->current_project_settings['basicsettings']['defaultcategory'];}
            $this->UI->option_menu_categories( __( 'Default Category' ), 'defaultcategory', 'defaultcategory', $defaultcategory );
            
            // default post type
            $defaultposttype = 'nocurrent123';
            if( isset( $this->current_project_settings['basicsettings']['defaultposttype'] ) ){$defaultposttype = $this->current_project_settings['basicsettings']['defaultposttype'];}            
            $this->UI->option_radiogroup_posttypes( __( 'Default Post Type' ), 'defaultposttype', 'defaultposttype', $defaultposttype);
            
            // default post format
            $defaultpostformat = 'nocurrent123';
            if( isset( $this->current_project_settings['basicsettings']['defaultpostformat'] ) ){$defaultpostformat = $this->current_project_settings['basicsettings']['defaultpostformat'];}            
            $this->UI->option_radiogroup_postformats( __( 'Default Post Format' ), 'defaultpostformat', 'defaultpostformat', $defaultpostformat);
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
    public function postbox_postsettings_databasedoptions( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'More very common post options but these require specific data. They are also optional. Ignore this form if your unsure.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;?>  

        <table class="form-table">    
            <?php
            // tags (ready made tags data)
            $tags_table = ''; 
            $tags_column = '';
            if( isset( $this->current_project_settings['tags']['table'] ) ){$tags_table = $this->current_project_settings['tags']['table'];}
            if( isset( $this->current_project_settings['tags']['column'] ) ){$tags_column = $this->current_project_settings['tags']['column'];}             
            $this->UI->option_projectcolumns( __( 'Tags' ), $c2p_settings['currentproject'], 'tags', 'tags', $tags_table, $tags_column, 'notrequired', 'Not Required' );

            // featured image
            $featuredimage_table = ''; 
            $featuredimage_column = '';
            if( isset( $this->current_project_settings['featuredimage']['table'] ) ){$featuredimage_table = $this->current_project_settings['featuredimage']['table'];}
            if( isset( $this->current_project_settings['featuredimage']['column'] ) ){$featuredimage_column = $this->current_project_settings['featuredimage']['column'];}             
            $this->UI->option_projectcolumns( __( 'Featured Images' ), $c2p_settings['currentproject'], 'featuredimage', 'featuredimage', $featuredimage_table, $featuredimage_column, 'notrequired', 'Not Required' );

            // permalink
            $permalink_table = ''; 
            $permalink_column = '';
            if( isset( $this->current_project_settings['permalink']['table'] ) ){$permalink_table = $this->current_project_settings['permalink']['table'];}
            if( isset( $this->current_project_settings['permalink']['column'] ) ){$permalink_column = $this->current_project_settings['permalink']['column'];}             
            $this->UI->option_projectcolumns( __( 'Permalink Column' ), $c2p_settings['currentproject'], 'permalink', 'permalink', $permalink_table, $permalink_column, 'notrequired', 'Not Required' );

            // url cloak 1
            $urlcloak1_table = ''; 
            $urlcloak1_column = '';
            if( isset( $this->current_project_settings['urlcloak1']['table'] ) ){$urlcloak1_table = $this->current_project_settings['urlcloak1']['table'];}
            if( isset( $this->current_project_settings['urlcloak1']['column'] ) ){$urlcloak1_column = $this->current_project_settings['urlcloak1']['column'];}             
            $this->UI->option_projectcolumns( __( 'URL Cloak 1' ), $c2p_settings['currentproject'], 'urlcloak1', 'urlcloak1', $urlcloak1_table, $urlcloak1_column, 'notrequired', 'Not Required' );
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
    public function postbox_postsettings_authoroptions( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'If you have email address and usernames in your data. You can create users who will be applied as authors to the posts they share a row with.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

           <table class="form-table">    
            <?php
            // email (for user creation)
            $email_table = ''; 
            $email_column = '';
            if( isset( $this->current_project_settings['authors']['email']['table'] ) ){$urlcloak1_table = $this->current_project_settings['authors']['email']['table'];}
            if( isset( $this->current_project_settings['authors']['email']['column'] ) ){$urlcloak1_column = $this->current_project_settings['authors']['email']['column'];}             
            $this->UI->option_projectcolumns( __( 'Email Address Data' ), $c2p_settings['currentproject'], 'email', 'email', $email_table, $email_column, 'notrequired', 'Not Required' );
            $this->UI->option_text( __( '' ), 'emailexample', 'emailexample', '', true, 'csv2post_inputtext' );

            // username (for user creation)
            $username_table = ''; 
            $username_column = '';
            if( isset( $this->current_project_settings['authors']['username']['table'] ) ){$username_table = $this->current_project_settings['authors']['username']['table'];}
            if( isset( $this->current_project_settings['authors']['username']['column'] ) ){$username_column = $this->current_project_settings['authors']['username']['column'];}             
            $this->UI->option_projectcolumns( __( 'Username Data' ), $c2p_settings['currentproject'], 'username', 'username', $username_table, $username_column, 'notrequired', 'Not Required' );
            $this->UI->option_text( __( '' ), 'usernameexample', 'usernameexample', '', true, 'csv2post_inputtext' );
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
    public function postbox_postsettings_defaulttagrules( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Import pre-made tags or generate them.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

           <table class="form-table">    
            <?php
            // generate tags
            $generatetags_table = ''; 
            $generatetags_column = '';
            if( isset( $this->current_project_settings['tags']['generatetags']['table'] ) ){$generatetags_table = $this->current_project_settings['tags']['generatetags']['table'];}
            if( isset( $this->current_project_settings['tags']['generatetags']['column'] ) ){$generatetags_column = $this->current_project_settings['tags']['generatetags']['column'];}             
            $this->UI->option_projectcolumns( __( 'Text Data' ), $c2p_settings['currentproject'], 'generatetags', 'generatetags', $generatetags_table, $generatetags_column, 'notrequired', 'Not Required' );
            $this->UI->option_text( __( '' ), 'generatetagsexample', 'generatetagsexample', '', true, 'csv2post_inputtext' );

            // numeric tags
            $numerictags = 'nocurrent123';
            if( isset( $this->current_project_settings['tags']['numerictags'] ) ){$numerictags = $this->current_project_settings['tags']['numerictags'];}  
            $this->UI->option_radiogroup( __( 'Numeric Tags' ), 'numerictags', 'numerictags', array( 'allow' => 'Allow', 'disallow' => 'Disallow' ), $numerictags, 'disallow' );
            
            // tag string length
            $tagstringlength = 'nocurrent123';
            if( isset( $this->current_project_settings['tags']['tagstringlength'] ) ){$tagstringlength = $this->current_project_settings['tags']['tagstringlength'];}           
            $this->UI->option_text( __( 'Tag String Length' ), 'tagstringlength', 'tagstringlength', $tagstringlength, false, 'csv2post_inputtext' );

            // maximum tags
            $maximumtags = 'nocurrent123';
            if( isset( $this->current_project_settings['tags']['maximumtags'] ) ){$maximumtags = $this->current_project_settings['tags']['maximumtags'];}                     
            $this->UI->option_text( __( 'Maximum Tags' ), 'maximumtags', 'maximumtags', $maximumtags, false );

            // excluded tags
            $excludedtags = 'nocurrent123';
            if( isset( $this->current_project_settings['tags']['excludedtags'] ) ){$excludedtags = $this->current_project_settings['tags']['excludedtags'];}                                 
            $this->UI->option_textarea( 'Excluded', 'excludedtags', 'excludedtags',5,40, $excludedtags);
            ?>    
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }
}?>