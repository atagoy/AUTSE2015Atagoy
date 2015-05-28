<?php
/**
 * Dates [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Dates [page] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Dates_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'dates';
    
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
            array( 'dates-defaultpublishdates', __( 'Default Publish Dates', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'defaultpublishdates' ), true, 'activate_plugins' )
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
            $this->add_meta_box( 'dates-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
        }   
    }
    
    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
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
    * @since 8.1.33
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
    public function postbox_dates_defaultpublishdates( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( "Rather than let WordPress set the date and time for a post as it would when creating them manually. We can import dates or generate them in a way that looks more natural to visitors.", 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $csv2post_settings;
        ?>  

            <table class="form-table">
                <?php
                // date method
                $publishdatemethod = 'nocurrent123';
                if( isset( $this->current_project_settings['dates']['publishdatemethod'] ) ){$publishdatemethod = $this->current_project_settings['dates']['publishdatemethod'];}         
                $this->UI->option_radiogroup( __( 'Date Method' ), 'publishdatemethod', 'publishdatemethod', array( 'wordpress' => 'WordPress', 'data' => __( 'Imported Dates' ), 'incremental' => __( 'Incremental' ), 'random' => __( 'Random' ) ), $publishdatemethod, 'random' );
                
                // imported dates
                $datescolumn_table = ''; 
                $datescolumn_column = '';
                if( isset( $this->current_project_settings['dates']['datescolumn']['table'] ) ){$datescolumn_table = $this->current_project_settings['dates']['datescolumn']['table'];}
                if( isset( $this->current_project_settings['dates']['datescolumn']['column'] ) ){$datescolumn_column = $this->current_project_settings['dates']['datescolumn']['column'];}             
                $this->UI->option_projectcolumns( __( 'Pre-Made Dates' ), $csv2post_settings['currentproject'], 'datescolumn', 'datescolumn', $datescolumn_table, $datescolumn_column, 'notrequired', 'Not Required' );?>
                
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"> Format </th>
                    <td>
                    
                        <?php
                        $dateformat = 'nocurrent123'; 
                        if( isset( $this->current_project_settings['dates']['dateformat'] ) ){$dateformat = $this->current_project_settings['dates']['dateformat'];}
                        ?>
                        <select name="dateformat" id="dateformat">
                            <option value="noformat" <?php if( $dateformat == 'noformat' ){echo 'selected="selected"';} ?>>Do Not Format (use date data as it is)</option>       
                            <option value="uk" <?php if( $dateformat == 'uk' ){echo 'selected="selected"';} ?>>UK (will be formatted to MySQL standard)</option>
                            <option value="us" <?php if( $dateformat == 'us' ){echo 'selected="selected"';} ?>>US (will be formatted to MySQL standard)</option>
                            <option value="mysql" <?php if( $dateformat == 'mysql' ){echo 'selected="selected"';} ?>>MySQL Standard</option>
                            <option value="unsure" <?php if( $dateformat == 'unsure' ){echo 'selected="selected"';} ?>>Unsure</option>                                                                                                                     
                        </select>
                        
                    </td>
                </tr>
                <!-- Option End --><?php  
            
                // incremental                
                $this->UI->option_subline( __( 'Incremental dates configuration...' ) );
                
                $incrementalstartdate = ''; 
                if( isset( $this->current_project_settings['dates']['incrementalstartdate'] ) ){$incrementalstartdate = $this->current_project_settings['dates']['incrementalstartdate'];}        
                $this->UI->option_text( __( 'Start Date' ), 'incrementalstartdate', 'incrementalstartdate', $incrementalstartdate, false );
                
                $naturalvariationlow = ''; 
                if( isset( $this->current_project_settings['dates']['naturalvariationlow'] ) ){$naturalvariationlow = $this->current_project_settings['dates']['naturalvariationlow'];}        
                $this->UI->option_text( __( 'Variation Low' ), 'naturalvariationlow', 'naturalvariationlow', $naturalvariationlow, false );
                
                $naturalvariationhigh = ''; 
                if( isset( $this->current_project_settings['dates']['naturalvariationhigh'] ) ){$naturalvariationhigh = $this->current_project_settings['dates']['naturalvariationhigh'];}        
                $this->UI->option_text( __( 'Variation High' ), 'naturalvariationhigh', 'naturalvariationhigh', $naturalvariationhigh, false );
                            
                // random
                $this->UI->option_subline( __( 'Random dates configuration...' ) );
                
                $randomdateearliest = ''; 
                if( isset( $this->current_project_settings['dates']['randomdateearliest'] ) ){$randomdateearliest = $this->current_project_settings['dates']['randomdateearliest'];}        
                $this->UI->option_text( __( 'Earliest Date' ), 'randomdateearliest', 'randomdateearliest', $randomdateearliest, false );
               
                $randomdatelatest = ''; 
                if( isset( $this->current_project_settings['dates']['randomdatelatest'] ) ){$randomdatelatest = $this->current_project_settings['dates']['randomdatelatest'];}         
                $this->UI->option_text( __( 'Latest Date' ), 'randomdatelatest', 'randomdatelatest', $randomdatelatest, false ); 
                ?>
            </table>
        
        <?php 
        $this->UI->postbox_content_footer();
    }
}?>