<?php
/**
 * Default Project Settings [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class for Default Project Settings [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Defaultglobalpostsettings_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'defaultglobalpostsettings';
    
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
            array( 'datasources-defaultglobalpostsettings', __( 'Default Project Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaultglobalpostsettings' ), true, 'activate_plugins' )
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
        
        // set current project values
        if( isset( $csv2post_settings['currentproject'] ) && $csv2post_settings['currentproject'] !== false ) {        
            $this->project_object = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] ); 
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
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function postbox_defaultglobalpostsettings_defaultglobalpostsettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'The basic post settings and all optional. Posts will use your blogs defaults if you do not submit this form.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $csv2post_settings;?>  
        
            <table class="form-table">
 
                <?php
                // common post settings
                $this->UI->option_radiogroup( 'Status', 'poststatus', 'poststatus', array( 'draft' => 'Draft', 'auto-draft' => 'Auto-Draft', 'publish' => 'Publish', 'pending' => 'Pending', 'future' => 'Future', 'private' => 'Private' ), $csv2post_settings['projectdefaults']['basicsettings']['poststatus'] );             
                $this->UI->option_radiogroup( __( 'Ping Status' ), 'pingstatus', 'pingstatus', array( 'open' => 'Open', 'closed' => 'Closed' ), $csv2post_settings['projectdefaults']['basicsettings']['pingstatus'] );
                $this->UI->option_radiogroup( __( 'Comment Status' ), 'commentstatus', 'commentstatus', array( 'open' => 'Open', 'closed' => 'Closed' ), $csv2post_settings['projectdefaults']['basicsettings']['commentstatus'] );
                $this->UI->option_menu_users( __( 'Default Author' ), 'defaultauthor', 'defaultauthor', $csv2post_settings['projectdefaults']['basicsettings']['defaultauthor'] );
                $this->UI->option_menu_categories( __( 'Default Category' ), 'defaultcategory', 'defaultcategory', $csv2post_settings['projectdefaults']['basicsettings']['defaultcategory'] );
                $this->UI->option_radiogroup_posttypes( __( 'Default Post Type' ), 'defaultposttype', 'defaultposttype', $csv2post_settings['projectdefaults']['basicsettings']['defaultposttype'] );
                $this->UI->option_radiogroup_postformats( __( 'Default Post Format' ), 'defaultpostformat', 'defaultpostformat', $csv2post_settings['projectdefaults']['basicsettings']['defaultpostformat'] );
                $this->UI->option_radiogroup( __( 'Category Assignment' ), 'categoryassignment', 'categoryassignment', array( 'last' => 'Last Category', 'all' => 'All Categories' ), $csv2post_settings['projectdefaults']['basicsettings']['categoryassignment'] );
                ?>                              
  
                <?php        
                // post settings requiring data
                $this->UI->option_text( __( 'Featured Image Column' ), 'featuredimage', 'featuredimage', $csv2post_settings['projectdefaults']['images']['featuredimage']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'featuredimageexample', 'featuredimageexample', '', true, 'csv2post_inputtext' );
                
                $this->UI->option_text( __( 'Permalink Column' ), 'permalink', 'permalink', $csv2post_settings['projectdefaults']['permalinks']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'permalinkexample', 'permalinkexample', '', true, 'csv2post_inputtext' );
                
                $this->UI->option_text( __( 'Email Column' ), 'email', 'email', $csv2post_settings['projectdefaults']['authors']['email']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'emailexample', 'emailexample', '', true, 'csv2post_inputtext' );
                
                $this->UI->option_text( __( 'Username Column' ), 'username', 'username', $csv2post_settings['projectdefaults']['authors']['username']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'usernameexample', 'usernameexample', '', true, 'csv2post_inputtext' );
                
                $this->UI->option_text( __( 'Cloak Column' ), 'urlcloak1', 'urlcloak1', $csv2post_settings['projectdefaults']['cloaking']['urlcloak1']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'urlcloak1example', 'urlcloak1example', '', true, 'csv2post_inputtext' );
                ?>    

                <?php   
                // title template                          
                $this->UI->option_text( __( 'Title Template' ), 'defaulttitletemplate', 'defaulttitletemplate', $csv2post_settings['projectdefaults']['titles']['defaulttitletemplate'], false, 'csv2post_inputtext' );
                $this->UI->option_text( '', 'sampletitle', 'sampletitle', '', true, 'csv2post_inputtext' );
                ?>
        
                <div id="poststuff">
                    <?php wp_editor( $csv2post_settings['projectdefaults']['content']['wysiwygdefaultcontent'], 'wysiwygdefaultcontent', array( 'textarea_name' => 'wysiwygdefaultcontent' ) );?>
                </div>
                
                <?php
                // seo
                $this->UI->option_text( 'Title Key', 'seotitlekey', 'seotitlekey', $csv2post_settings['projectdefaults']['customfields']['seotitlekey'], false );
                $this->UI->option_text( 'Title Template', 'seotitletemplate', 'seotitletemplate', $csv2post_settings['projectdefaults']['customfields']['seotitletemplate'], false );
                $this->UI->option_text( '', 'seotitlesample', 'seotitlesample', '', true);
                $this->UI->option_text( 'Description Key', 'seodescriptionkey', 'seodescriptionkey', $csv2post_settings['projectdefaults']['customfields']['seodescriptionkey'], false );
                $this->UI->option_text( 'Description Template', 'seodescriptiontemplate', 'seodescriptiontemplate', $csv2post_settings['projectdefaults']['customfields']['seodescriptiontemplate'], false );
                $this->UI->option_text( '', 'seodescriptionsample', 'seodescriptionsample', '', true);
                $this->UI->option_text( 'Keywords Key', 'seokeywordskey', 'seokeywordskey', $csv2post_settings['projectdefaults']['customfields']['seokeywordskey'], false );
                $this->UI->option_text( 'Keywords Template', 'seokeywordstemplate', 'seokeywordstemplate', $csv2post_settings['projectdefaults']['customfields']['seokeywordstemplate'], false );
                $this->UI->option_text( '', 'keywordssample', 'keywordssample', '', true);
                ?>

                <?php
                // custom fields
                for( $i=0;$i<=2;$i++){
                    $inc = $i + 1;
                    
                    $cfname = '';
                    $cfvalue = '';
                    
                    if( isset( $csv2post_settings['projectdefaults']['customfields']['cflist'][$i]["name"] ) ){$cfname = $csv2post_settings['projectdefaults']['customfields']['cflist'][$i]["name"];}
                    if( isset( $csv2post_settings['projectdefaults']['customfields']['cflist'][$i]["value"] ) ){$cfvalue = $csv2post_settings['projectdefaults']['customfields']['cflist'][$i]["value"];}
                    
                    $this->UI->option_text( "Key $inc", "cfkey$i", "cfkey$i", $cfname, false );
                    $this->UI->option_text( "Template $inc", "cftemplate$i", "cftemplate$i", $cfvalue, false );
                    $this->UI->option_text( "", "cfsample$i", "cfsample$i", '', true);
                }
                ?>
                   
                <?php
                // design rules
                for( $i=1;$i<=3;$i++){
                    // title
                    $this->UI->option_text( "Column $i", "designrule$i", "designrule$i", $csv2post_settings['projectdefaults']['content']["designrule$i"]['column'], false );
                    $this->UI->option_text( "Trigger $i", "designruletrigger$i", "designruletrigger$i", $csv2post_settings['projectdefaults']['content']["designruletrigger$i"], false );
                    $this->UI->option_menu_posttemplates( "Design $i", "designtemplate$i", "designtemplate$i", $csv2post_settings['projectdefaults']['content']["designtemplate$i"] );
                }
                ?>
                        
                <?php
                // tags
                $this->UI->option_text( __( 'Ready-made Tags' ), 'tags', 'tags', $csv2post_settings['projectdefaults']['tags']['column'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( '' ), 'tagsexample', 'tagsexample', '', true, 'csv2post_inputtext' );
                $this->UI->option_text( __( 'Text Data' ), 'textdata', 'textdata', $csv2post_settings['projectdefaults']['tags']['textdata']['column'], false );// originally  generatetags
                $this->UI->option_text( __( '' ), 'generatetagsexample', 'generatetagsexample', '', true, 'csv2post_inputtext' );        
                $this->UI->option_radiogroup( __( 'Numerics' ), 'defaultnumerics', 'defaultnumerics', array( 'allow' => 'Allow', 'disallow' => 'Disallow' ), $csv2post_settings['projectdefaults']['tags']['defaultnumerics'] );
                $this->UI->option_text( __( 'Tag String Length' ), 'tagstringlength', 'tagstringlength', $csv2post_settings['projectdefaults']['tags']['tagstringlength'], false, 'csv2post_inputtext' );
                $this->UI->option_text( __( 'Maximum Tags' ), 'maximumtags', 'maximumtags', $csv2post_settings['projectdefaults']['tags']['maximumtags'], false );
                $this->UI->option_textarea( 'Excluded', 'excludedtags', 'excludedtags',5,40, $csv2post_settings['projectdefaults']['tags']['excludedtags'] );
                ?> 
                
                <?php
                // Categories
                $cat_lev_1 = false;
                if( isset( $csv2post_settings['projectdefaults']['categories']['data'][0]['column'] ) ) { $cat_lev_1 = $csv2post_settings['projectdefaults']['categories']['data'][0]['column']; }
                $this->UI->option_text( "Level 1 Column", "categorylevel1", "categorylevel1", $cat_lev_1, false, 'csv2post_inputtext' );
                
                $cat_lev_2 = false;
                if( isset( $csv2post_settings['projectdefaults']['categories']['data'][1]['column'] ) ) { $cat_lev_2 = $csv2post_settings['projectdefaults']['categories']['data'][1]['column']; }
                $this->UI->option_text( "Level 2 Column", "categorylevel2", "categorylevel2", $cat_lev_2, false, 'csv2post_inputtext' );
                
                $cat_lev_3 = false;
                if( isset( $csv2post_settings['projectdefaults']['categories']['data'][2]['column'] ) ) { $cat_lev_3 = $csv2post_settings['projectdefaults']['categories']['data'][2]['column']; }
                $this->UI->option_text( "Level 3 Column", "categorylevel3", "categorylevel3", $cat_lev_3, false, 'csv2post_inputtext' );
                
                $cat_lev_4 = false;
                if( isset( $csv2post_settings['projectdefaults']['categories']['data'][3]['column'] ) ) { $cat_lev_4 = $csv2post_settings['projectdefaults']['categories']['data'][3]['column']; }
                $this->UI->option_text( "Level 4 Column", "categorylevel4", "categorylevel4", $cat_lev_4, false, 'csv2post_inputtext' );
                
                $cat_lev_5 = false;
                if( isset( $csv2post_settings['projectdefaults']['categories']['data'][4]['column'] ) ) { $cat_lev_5 = $csv2post_settings['projectdefaults']['categories']['data'][4]['column']; }
                $this->UI->option_text( "Level 5 Column", "categorylevel5", "categorylevel5", $cat_lev_5, false, 'csv2post_inputtext' );
                ?>
       
                <?php   
                // category descriptions                                                                                                                                     
                for( $i=0;$i<=4;$i++){
                    $inc = $i + 1;
                    $this->UI->option_text( "Level $inc Column", "categorydescription$i", "categorydescription$i", $csv2post_settings['projectdefaults']['categories']["descriptiondata"][$i]['column'], false, 'csv2post_inputtext' );
                    $this->UI->option_text( "Or Template", "categorydescriptiontemplate$i", "categorydescriptiontemplate$i", $csv2post_settings['projectdefaults']['categories']["descriptiontemplates"][$i], false, 'csv2post_inputtext' );
                }?>

                <?php
                // post adoption
                $this->UI->option_radiogroup( __( 'Adoption Method' ), 'adoptionmethod', 'adoptionmethod', array( 'title' => 'Post Title', 'slug' => 'Post Slug', 'content' => 'Content', 'meta' => 'Meta' ), $csv2post_settings['projectdefaults']['adoption']['adoptionmethod'] );
                $this->UI->option_radiogroup( __( 'Allowed Changes' ), 'allowedchanges', 'allowedchanges', array( 'everything' => 'Everything', 'meta' => 'Meta Only', 'content' => 'Content' ), $csv2post_settings['projectdefaults']['adoption']['allowedchanges'] );
                $this->UI->option_subline( __( 'Settings below here are for the meta adoption method...' ) );
                $this->UI->option_text( 'Meta Key', 'adoptionmetakey', 'adoptionmetakey', $csv2post_settings['projectdefaults']['adoption']['adoptionmetakey'], false, 'csv2post_inputtext' );
                $this->UI->option_text( 'Meta Column', 'adoptionmeta', 'adoptionmeta', $csv2post_settings['projectdefaults']['adoption']['adoptionmeta']['column'], false, 'csv2post_inputtext' );
                ?>  

                <?php
                // post type rules
                for( $i=1;$i<=3;$i++){
                    $this->UI->option_text( __( "Column $i"), "posttyperule$i", "posttyperule$i", $csv2post_settings['projectdefaults']['posttypes']["posttyperule$i"]['column'], false );
                    $this->UI->option_text( __( "Trigger $i"), "posttyperuletrigger$i", "posttyperuletrigger$i", $csv2post_settings['projectdefaults']['posttypes']["posttyperuletrigger$i"], false );
                    $this->UI->option_radiogroup_posttypes( __( "Post Type $i"), "posttyperuleposttype$i", "posttyperuleposttype$i", $csv2post_settings['projectdefaults']['posttypes']["posttyperuleposttype$i"] );
                }
                ?>

                <?php
                // date settings
                $this->UI->option_radiogroup( 'Date Method', 'publishdatemethod', 'publishdatemethod', array( 'wordpress' => 'WordPress', 'data' => 'Imported Dates', 'incremental' => 'Incremental', 'random' => 'Random' ), $csv2post_settings['projectdefaults']['dates']['publishdatemethod'] );
                
                // imported dates
                $this->UI->option_subline( 'imported dates configuration' );
                $this->UI->option_text( 'Dates Column', 'dates', 'dates', $csv2post_settings['projectdefaults']['dates']['column'], false );?>
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"> Format </th>
                    <td>          
                        <select name="dateformat">
                            <option value="noformat" <?php if( $csv2post_settings['projectdefaults']['dates']['dateformat'] == 'noformat' ){echo 'selected="selected"';} ?>>Do Not Format (use date data as it is)</option>       
                            <option value="uk" <?php if( $csv2post_settings['projectdefaults']['dates']['dateformat'] == 'uk' ){echo 'selected="selected"';} ?>>UK (will be formatted to MySQL standard)</option>
                            <option value="us" <?php if( $csv2post_settings['projectdefaults']['dates']['dateformat'] == 'us' ){echo 'selected="selected"';} ?>>US (will be formatted to MySQL standard)</option>
                            <option value="mysql" <?php if( $csv2post_settings['projectdefaults']['dates']['dateformat'] == 'mysql' ){echo 'selected="selected"';} ?>>MySQL Standard</option>
                            <option value="unsure" <?php if( $csv2post_settings['projectdefaults']['dates']['dateformat'] == 'unsure' ){echo 'selected="selected"';} ?>>Unsure</option>                                                                                                                     
                        </select>
                        
                    </td>
                </tr>
                <!-- Option End --><?php  
                
                // incremental                
                $this->UI->option_subline( 'incremental dates configuration' );
                $this->UI->option_text( __( 'Start Date' ), 'incrementalstartdate', 'incrementalstartdate', $csv2post_settings['projectdefaults']['dates']['incrementalstartdate'], false );
                $this->UI->option_text( __( 'Variation Low' ), 'naturalvariationlow', 'naturalvariationlow', $csv2post_settings['projectdefaults']['dates']['naturalvariationlow'], false );
                $this->UI->option_text( __( 'Variation High' ), 'naturalvariationhigh', 'naturalvariationhigh', $csv2post_settings['projectdefaults']['dates']['naturalvariationhigh'], false );
                            
                // random
                $this->UI->option_subline( 'random dates configuration' );
                $this->UI->option_text( __( 'Earliest Date' ), 'randomdateearliest', 'randomdateearliest', $csv2post_settings['projectdefaults']['dates']['randomdateearliest'], false );
                $this->UI->option_text( __( 'Latest Date' ), 'randomdatelatest', 'randomdatelatest', $csv2post_settings['projectdefaults']['dates']['randomdatelatest'], false ); 
                ?>

                <?php
                // post updating
                $this->UI->option_switch( 'Update On Viewing', 'updatepostonviewing', 'updatepostonviewing', $csv2post_settings['projectdefaults']['updating']['updatepostonviewing'] );
                ?>

                <?php    
                if(!isset( $csv2post_settings['projectdefaults']['content']['groupedimagesdir'] ) || !file_exists(ABSPATH . $csv2post_settings['projectdefaults']['content']['groupedimagesdir'] ) ){
                    echo $this->UI->notice_return( 'warning', 'Small', __( 'Grouped Image Directory Does Not Exist' ), __( 'Please create or change the path to your image directory where groups of images are stored.' ) );
                }
                ?>
                        
                <?php
                // enable this feature
                $enablegroupedimageimport = 'disabled';
                if( isset( $csv2post_settings['projectdefaults']['content']['enablegroupedimageimport'] ) ){$enablegroupedimageimport = $csv2post_settings['projectdefaults']['content']['enablegroupedimageimport'];}
                $this->UI->option_switch( __( 'Enable' ), 'enablegroupedimageimport', 'enablegroupedimageimport', $enablegroupedimageimport, 'Yes', 'No', 'disabled' );
                
                // column of filenames or partial filenames
                $localimages_column = '';
                if( isset( $csv2post_settings['projectdefaults']['content']["localimages"]['column'] ) ){$localimages_column = $csv2post_settings['projectdefaults']['content']["localimages"]['column'];}                 
                $this->UI->option_text( 'Filename Data', 'localimagesdatacolumn', 'localimagesdatacolumn', $localimages_column);                
                // search for increment
                $incrementalimages = 'enabled';
                if( isset( $csv2post_settings['projectdefaults']['content']['incrementalimages'] ) ){$enablegroupedimageimport = $csv2post_settings['projectdefaults']['content']['incrementalimages'];}        
                $this->UI->option_switch( 'Incremental', 'incrementalimages', 'incrementalimages', $enablegroupedimageimport, 'Yes', 'No', 'enabled' );
                
                // image dir
                $imgdir = '';
                if( isset( $csv2post_settings['projectdefaults']['content']['groupedimagesdir'] ) ){$imgdir = $csv2post_settings['projectdefaults']['content']['groupedimagesdir'];}
                $this->UI->option_text_simple( 'Image Directory', 'groupimportdir', $imgdir );            
                ?>
        </table>
        <?php 
        $this->UI->postbox_content_footer();
    }
}?>