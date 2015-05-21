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
class CSV2POST_Main_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'main';
    
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
        global $c2p_settings;

        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( $this->view_name . '-welcome', __( 'Start Here', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'welcome' ), true, 'activate_plugins' ),
            array( $this->view_name . '-computersampledata', __( 'Computer Sample Data', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'computersampledata' ), true, 'activate_plugins' ),
            array( $this->view_name . '-twitterupdates', __( 'Twitter Updates', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'twitterupdates' ), true, 'activate_plugins' ),
            array( $this->view_name . '-facebook', __( 'Facebook', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'facebook' ), true, 'activate_plugins' ),       
            
            // settings group
            array( $this->view_name . '-schedulesettings', __( 'Schedule Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'schedulesettings' ), true, 'activate_plugins' ),
            array( $this->view_name . '-globalswitches', __( 'Global Switches', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'globalswitches' ), true, 'activate_plugins' ),
            array( $this->view_name . '-globaldatasettings', __( 'Global Data Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'globaldatasettings' ) , true, 'activate_plugins' ),
            array( $this->view_name . '-logsettings', __( 'Log Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'logsettings' ), true, 'activate_plugins' ),
            array( $this->view_name . '-pagecapabilitysettings', __( 'Page Capability Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'pagecapabilitysettings' ), true, 'activate_plugins' ),
            
            // create datasource group
            array( $this->view_name . '-createuploadcsvdatasource', __( 'Create Data Source: Upload File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createuploadcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createurlcsvdatasource', __( 'Create Data Source: Import Via URL', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createurlcsvdatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-createservercsvdatasource', __( 'Create Data Source: Existing Server File', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createservercsvdatasource' ), true, 'activate_plugins' ),           
            array( $this->view_name . '-createdirectorycsvdatasource', __( 'Create Data Source: Process Directory/Folder', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'createdirectorycsvdatasource' ), true, 'activate_plugins' ),           

            // project management group
            array( $this->view_name . '-newprojectusingexistingsource', __( 'Create New Project', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'newprojectusingexistingsource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-allprojectstable', __( 'All Projects Table', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'allprojectstable' ), true, 'activate_plugins' ),
            array( $this->view_name . '-deleteproject', __( 'Delete Project', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'deleteproject' ), true, 'activate_plugins' ),
            array( $this->view_name . '-setactiveproject', __( 'Set Currently Active Project', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'setactiveproject' ), true, 'activate_plugins' ),
                        
            // side boxes
            array( $this->view_name . '-support', __( 'Support', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'support' ), true, 'activate_plugins' ),            
            array( $this->view_name . '-deletedatasource', __( 'Delete Data Source', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'deletedatasource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-changecsvfilepath', __( 'Change CSV File Path', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'changecsvfilepath' ), true, 'activate_plugins' ),
            array( $this->view_name . '-rechecksourcedirectory', __( 'Re-check Source Directory', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'rechecksourcedirectory' ), true, 'activate_plugins' ),
            array( $this->view_name . '-urlimporttoexistingsource', __( 'URL Import To Existing Data Source', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'urlimporttoexistingsource' ), true, 'activate_plugins' ),
            array( $this->view_name . '-uploadfiletodatasource', __( 'Upload File To Existing Data Source', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'uploadfiletodatasource' ), true, 'activate_plugins' ),
                                
        );
        
        // add meta boxes that have conditions i.e. a global switch
        if( isset( $c2p_settings['widgetsettings']['dashboardwidgetsswitch'] ) && $c2p_settings['widgetsettings']['dashboardwidgetsswitch'] == 'enabled' ) {
            $this->meta_boxes_array[] = array( 'main-dashboardwidgetsettings', __( 'Dashboard Widget Settings', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'dashboardwidgetsettings' ), true, 'activate_plugins' );   
        }
        
        return $this->meta_boxes_array;                
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
        $this->TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
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
        
        // only output meta boxes
        if( $this->purpose == 'normal' ) {
            self::metaboxes();// register meta boxes for the current view
        } elseif( $this->purpose == 'dashboard' ) {
            // do nothing - add_dashboard_widgets() in class-ui.php calls dashboard_widgets() from this class
        } elseif( $this->purpose == 'customdashboard' ) {
            return self::meta_box_array();// return meta box array
        } else {
            // do nothing 
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
    * @package CSV2POST
    * @since 8.1.33
    * @version 1.0.1
    */
    public function dashboard() { 
        // setup() is not called when viewing dashboard so we need to do some loading here
        $this->FORMS = CSV2POST::load_class( 'CSV2POST_FORMS', 'class-forms.php', 'classes' );
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
    public function postbox_main_welcome( $data, $box ) {    
        echo '<p>' . __( "Please begin by configuring this plugin dashboard view. The Screen Options tab
        top-right corner allows forms to be hidden. Let me make a couple of suggestions that will help you to get started.
        <ol>
            <li>Please support the project by giving a Facebook like. Then hide the Facebook postbox by removing the check in Screen Options.</li>
            <li>Visit each postbox (accordian controlled with forms in them) and hide the ones that you aren't sure about. Do not hold back and focus on starting with the minimum tools. We can display it all later when needed.</li>             
            <li>You can also drag some postboxes into the right-side column - that is where I put the rarely used forms.</li>
            <li>I prepared some data for testing and training. If your confident you don't need those then hide the Computer Sample Data postbox.</li>
            <li>You will need one of the Create Data Source forms - which one depends on how you want to manage your file. Come to the forum for advice on this.</li>
            <li>Much of the tools are for longterm management, not correcting mistakes on your initial setup. The best way to fix a new project that has not begun yet is deleting it and starting again. So hide the forms that appear to be tools.</li>
            <li>You will need the Create New Project form so if you hid that...get it back again.</li>
            <li>If your new to WordPress and a whole lot of other things your probably going to need to .</li>
            <li>The very big form named Default Project Settings is a set of options applied to all new projects. It is optional and to assist users who plan to create many projects that require much the same configuration. Hide it if your doing a one time import.</li>
        </ol>", 'csv2post' ) . '</p>';
    }       
        
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_main_computersampledata( $data, $box ) {    
        ?>  
 
        <p><?php _e( "Sample files of PC sales data is available. The file is stored on Google Docs and is public. Feel free
        to download and test the plugin using my sample data which I also use in tutorials."); ?>
        </p>
        
        <ol>
            <li><a href="https://docs.google.com/spreadsheets/d/1J140c85Kl5fE_9dQAIgSq-uATtD-Jtwje4G1WQFgBbU/edit?usp=sharing" id="csv2postviewmainfile" target="_blank">View Main File</a></li>
        </ol>        
         
        <h2>Advanced Multi-File Samples</h2> 
        
        <p><?php _e( "To push the plugin I've saved information from PC World, split into three separate files.
        This will be used to test multi-file projects and the data engine within CSV 2 POST."); ?>
        </p>
                  
        <h3><?php _e( 'View Original Spreadsheets' );?></h3>
        <ol>
            <li><a href="https://docs.google.com/spreadsheet/ccc?key=0An6BbeiXPNK0dHM5Q043QzBtTGw4QU9SeXNYdEM1UHc&usp=sharing" target="_blank">File 1: Main PC Details</a></li>
            <li><a href="https://docs.google.com/spreadsheet/ccc?key=0An6BbeiXPNK0dHlFZUx1V3p6bHJrOHJZMUNmcGRyUWc&usp=sharing" target="_blank">File 2: Specifications</a></li>
            <li><a href="https://docs.google.com/spreadsheet/ccc?key=0An6BbeiXPNK0dDhEdHZIYVJ4YkViUkQ3MTFESFdUR2c&usp=sharing" target="_blank">File 3: Descriptions and Images</a></li>
        </ol>
        
        <h3><?php _e( 'Download CSV files from Google' );?></h3>
        <p><?php _e( 'Warning: Google does not seem to handle the third file with text well, often adding line breaks in different places each time the file is downloaded. Simply correct them before using.' );?></p>

        <ol>
            <li><a href="https://docs.google.com/spreadsheet/pub?key=0An6BbeiXPNK0dHM5Q043QzBtTGw4QU9SeXNYdEM1UHc&output=csv" target="_blank">File 1: Main PC Details</a></li>
            <li><a href="https://docs.google.com/spreadsheet/pub?key=0An6BbeiXPNK0dHlFZUx1V3p6bHJrOHJZMUNmcGRyUWc&output=csv" target="_blank">File 2: Specifications</a></li>
            <li><a href="https://docs.google.com/spreadsheet/pub?key=0An6BbeiXPNK0dDhEdHZIYVJ4YkViUkQ3MTFESFdUR2c&output=csv" target="_blank">File 3: Descriptions and Images</a></li>
        </ol>        
        
        <h3><?php _e( 'Download CSV files from WebTechGlobal' );?></h3>
        <p><?php _e( 'The zip file contains all 3 files however I recommend beginners use the first file only.' );?></p>

        <ol>
            <li><a href="http://www.webtechglobal.co.uk/wp-content/uploads/2015/01/WordPress-Data-Importer-Sample-Data.zip" target="_blank">All Three Files</a></li>
        </ol>      
          
        <h3><?php _e( 'Try .csv URL on WebTechGlobal' );?></h3>
        <p><?php _e( 'Use these URL to the plugins ability to import a .csv file from URL.' );?></p>

        <ol>
            <li><a href="http://www.webtechglobal.co.uk/wp-content/uploads/2015/01/Computers-1.csv" target="_blank">File 1: Main PC Details</a></li>
            <li><a href="http://www.webtechglobal.co.uk/wp-content/uploads/2015/01/Computers-2.csv" target="_blank">File 2: Specifications</a></li>
            <li><a href="http://www.webtechglobal.co.uk/wp-content/uploads/2015/01/Computers-3.csv" target="_blank">File 3: Descriptions and Images</a></li>
        </ol> 
            
        <?php                             
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_main_schedulesettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This is a less of a specific day and time schedule. More of a system that allows systematic triggering of events within permitted hours. A new schedule system is in development though and will offer even more control with specific timing of events capable.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

            <table class="form-table">
 
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Days', 'csv2post' ); ?></th>
                    <td>
                        <?php 
                        $days_array = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
                        $days_counter = 1;
                        
                        foreach( $days_array as $key => $day ){
                            
                            // set checked status
                            if( isset( $this->schedule['days'][$day] ) ){
                                $day_checked = 'checked';
                            }else{
                                $day_checked = '';            
                            }
                                 
                            echo '<input type="checkbox" name="csv2post_scheduleday_list[]" id="daycheck'.$days_counter.'" value="'.$day.'" '.$day_checked.' />
                            <label for="daycheck'.$days_counter.'">'.ucfirst( $day ).'</label><br />';    
                            ++$days_counter;
                        }?>
                    </td>
                </tr>
                <!-- Option End -->                          

                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Hours', 'csv2post' ); ?></th>
                    <td>
                    <?php
                    // loop 24 times and create a checkbox for each hour
                    for( $i=0;$i<24;$i++){
                        
                        // check if the current hour exists in array, if it exists then it is permitted, if it does not exist it is not permitted
                        if( isset( $this->schedule['hours'][$i] ) ){
                            $hour_checked = ' checked'; 
                        }else{
                            $hour_checked = '';
                        }
                        
                        echo '<input type="checkbox" name="csv2post_schedulehour_list[]" id="hourcheck'.$i.'"  value="'.$i.'" '.$hour_checked.' />
                        <label for="hourcheck'.$i.'">'.$i.'</label><br>';    
                    }
                    ?>
                    </td>
                </tr>
                <!-- Option End -->          
         
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Daily Limit', 'csv2post' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Daily Limit</span></legend>
                            <input type="radio" id="csv2post_radio1_dripfeedrate_maximumperday" name="day" value="1" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 1){echo 'checked';} ?> /><label for="csv2post_radio1_dripfeedrate_maximumperday"> <?php _e( '1', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio2_dripfeedrate_maximumperday" name="day" value="5" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 5){echo 'checked';} ?> /><label for="csv2post_radio2_dripfeedrate_maximumperday"> <?php _e( '5', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio3_dripfeedrate_maximumperday" name="day" value="10" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 10){echo 'checked';} ?> /><label for="csv2post_radio3_dripfeedrate_maximumperday"> <?php _e( '10', 'csv2post' );?> </label><br> 
                            <input type="radio" id="csv2post_radio9_dripfeedrate_maximumperday" name="day" value="24" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 24){echo 'checked';} ?> /><label for="csv2post_radio9_dripfeedrate_maximumperday"> <?php _e( '24', 'csv2post' );?> </label><br>                    
                            <input type="radio" id="csv2post_radio4_dripfeedrate_maximumperday" name="day" value="50" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 50){echo 'checked';} ?> /><label for="csv2post_radio4_dripfeedrate_maximumperday"> <?php _e( '50', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio5_dripfeedrate_maximumperday" name="day" value="250" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 250){echo 'checked';} ?> /><label for="csv2post_radio5_dripfeedrate_maximumperday"> <?php _e( '250', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio6_dripfeedrate_maximumperday" name="day" value="1000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 1000){echo 'checked';} ?> /><label for="csv2post_radio6_dripfeedrate_maximumperday"> <?php _e( '1000', 'csv2post' );?> </label><br>                                                                                                                       
                            <input type="radio" id="csv2post_radio7_dripfeedrate_maximumperday" name="day" value="2000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 2000){echo 'checked';} ?> /><label for="csv2post_radio7_dripfeedrate_maximumperday"> <?php _e( '2000', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio8_dripfeedrate_maximumperday" name="day" value="5000" <?php if( isset( $this->schedule['limits']['day'] ) && $this->schedule['limits']['day'] == 5000){echo 'checked';} ?> /><label for="csv2post_radio8_dripfeedrate_maximumperday"> <?php _e( '5000', 'csv2post' );?> </label>                   
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->   
                         
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Hourly Limit', 'csv2post' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Hourly Limit</span></legend>
                            <input type="radio" id="csv2post_radio1_dripfeedrate_maximumperhour" name="hour" value="1" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 1){echo 'checked';} ?> /><label for="csv2post_radio1_dripfeedrate_maximumperhour"> <?php _e( '1', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio2_dripfeedrate_maximumperhour" name="hour" value="5" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 5){echo 'checked';} ?> /><label for="csv2post_radio2_dripfeedrate_maximumperhour"> <?php _e( '5', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio3_dripfeedrate_maximumperhour" name="hour" value="10" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 10){echo 'checked';} ?> /><label for="csv2post_radio3_dripfeedrate_maximumperhour"> <?php _e( '10', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio9_dripfeedrate_maximumperhour" name="hour" value="24" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 24){echo 'checked';} ?> /><label for="csv2post_radio9_dripfeedrate_maximumperhour"> <?php _e( '24', 'csv2post' );?> </label><br>                    
                            <input type="radio" id="csv2post_radio4_dripfeedrate_maximumperhour" name="hour" value="50" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 50){echo 'checked';} ?> /><label for="csv2post_radio4_dripfeedrate_maximumperhour"> <?php _e( '50', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio5_dripfeedrate_maximumperhour" name="hour" value="100" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 100){echo 'checked';} ?> /><label for="csv2post_radio5_dripfeedrate_maximumperhour"> <?php _e( '100', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio6_dripfeedrate_maximumperhour" name="hour" value="250" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 250){echo 'checked';} ?> /><label for="csv2post_radio6_dripfeedrate_maximumperhour"> <?php _e( '250', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio7_dripfeedrate_maximumperhour" name="hour" value="500" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 500){echo 'checked';} ?> /><label for="csv2post_radio7_dripfeedrate_maximumperhour"> <?php _e( '500', 'csv2post' );?> </label><br>       
                            <input type="radio" id="csv2post_radio8_dripfeedrate_maximumperhour" name="hour" value="1000" <?php if( isset( $this->schedule['limits']['hour'] ) && $this->schedule['limits']['hour'] == 1000){echo 'checked';} ?> /><label for="csv2post_radio8_dripfeedrate_maximumperhour"> <?php _e( '1000', 'csv2post' );?> </label><br>                                                                                                                           
                       </fieldset>
                    </td>
                </tr>
                <!-- Option End -->   

                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row"><?php _e( 'Event Limit', 'csv2post' );?></th>
                    <td>
                        <fieldset><legend class="screen-reader-text"><span>Event Limit</span></legend>
                            <input type="radio" id="csv2post_radio1_dripfeedrate_maximumpersession" name="session" value="1" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 1){echo 'checked';} ?> /><label for="csv2post_radio1_dripfeedrate_maximumpersession"> <?php _e( '1', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio2_dripfeedrate_maximumpersession" name="session" value="5" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 5){echo 'checked';} ?> /><label for="csv2post_radio2_dripfeedrate_maximumpersession"> <?php _e( '5', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio3_dripfeedrate_maximumpersession" name="session" value="10" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 10){echo 'checked';} ?> /><label for="csv2post_radio3_dripfeedrate_maximumpersession"> <?php _e( '10', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio9_dripfeedrate_maximumpersession" name="session" value="25" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 25){echo 'checked';} ?> /><label for="csv2post_radio9_dripfeedrate_maximumpersession"> <?php _e( '25', 'csv2post' );?> </label><br>                    
                            <input type="radio" id="csv2post_radio4_dripfeedrate_maximumpersession" name="session" value="50" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 50){echo 'checked';} ?> /><label for="csv2post_radio4_dripfeedrate_maximumpersession"> <?php _e( '50', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio5_dripfeedrate_maximumpersession" name="session" value="100" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 100){echo 'checked';} ?> /><label for="csv2post_radio5_dripfeedrate_maximumpersession"> <?php _e( '100', 'csv2post' );?> </label><br>
                            <input type="radio" id="csv2post_radio6_dripfeedrate_maximumpersession" name="session" value="200" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 200){echo 'checked';} ?> /><label for="csv2post_radio6_dripfeedrate_maximumpersession"> <?php _e( '200', 'csv2post' );?> </label><br>                                                                                                                        
                            <input type="radio" id="csv2post_radio7_dripfeedrate_maximumpersession" name="session" value="300" <?php if( isset( $this->schedule['limits']['session'] ) && $this->schedule['limits']['session'] == 300){echo 'checked';} ?> /><label for="csv2post_radio7_dripfeedrate_maximumpersession"> <?php _e( '300', 'csv2post' );?> </label><br>          
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->     
                
            </table>
                 
            <h4><?php _e( 'Last Schedule Finish Reason', 'csv2post' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lastreturnreason'] ) ){
                echo $this->schedule['history']['lastreturnreason']; 
            }else{
                _e( 'No event refusal reason has been set yet', 'csv2post' );    
            }?>
            </p>
            
            <h4><?php _e( 'Events Counter - 60 Minute Period', 'csv2post' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['hourcounter'] ) ){
                echo $this->schedule['history']['hourcounter']; 
            }else{
                _e( 'No events have been done during the current 60 minute period', 'csv2post' );    
            }?>
            </p> 

            <h4><?php _e( 'Events Counter - 24 Hour Period', 'csv2post' );?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['daycounter'] ) ){
                echo $this->schedule['history']['daycounter']; 
            }else{
                _e( 'No events have been done during the current 24 hour period', 'csv2post' );    
            }?>
            </p>

            <h4><?php _e( 'Last Event Type', 'csv2post' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventtype'] ) ){
                
                if( $this->schedule['history']['lasteventtype'] == 'dataimport' ){
                    echo 'Data Import';            
                }elseif( $this->schedule['history']['lasteventtype'] == 'dataupdate' ){
                    echo 'Data Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'postcreation' ){
                    echo 'Post Creation';
                }elseif( $this->schedule['history']['lasteventtype'] == 'postupdate' ){
                    echo 'Post Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twittersend' ){
                    echo 'Twitter: New Tweet';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twitterupdate' ){
                    echo 'Twitter: Send Update';
                }elseif( $this->schedule['history']['lasteventtype'] == 'twitterget' ){
                    echo 'Twitter: Get Reply';
                }
                 
            }else{
                _e( 'No events have been carried out yet', 'csv2post' );    
            }?>
            </p>

            <h4><?php _e( 'Last Event Action', 'csv2post' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventaction'] ) ){
                echo $this->schedule['history']['lasteventaction']; 
            }else{
                _e( 'No event actions have been carried out yet', 'csv2post' );    
            }?>
            </p>
                
            <h4><?php _e( 'Last Event Time', 'csv2post' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['lasteventtime'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['lasteventtime'] ); 
            }else{
                _e( 'No schedule events have ran on this server yet', 'csv2post' );    
            }?>
            </p>
            
            <h4><?php _e( 'Last Hourly Reset', 'csv2post' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['hour_lastreset'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['hour_lastreset'] ); 
            }else{
                _e( 'No hourly reset has been done yet', 'csv2post' );    
            }?>
            </p>   
                
            <h4><?php _e( 'Last 24 Hour Period Reset', 'csv2post' ); ?></h4>
            <p>
            <?php 
            if( isset( $this->schedule['history']['day_lastreset'] ) ){
                echo date( "F j, Y, g:i a", $this->schedule['history']['day_lastreset'] ); 
            }else{
                _e( 'No 24 hour reset has been done yet', 'csv2post' );    
            }?>
            </p> 
               
            <h4><?php _e( 'Your Servers Current Data and Time', 'csv2post' ); ?></h4>
            <p><?php echo date( "F j, Y, g:i a",time() );?></p>     
            
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
    public function postbox_main_globalswitches( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'These switches disable or enable systems. Disabling systems you do not require will improve the plugins performance.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

            <table class="form-table">
            <?php        
            $this->UI->option_switch( __( 'WordPress Notice Styles', 'csv2post' ), 'uinoticestyle', 'uinoticestyle', $c2p_settings['noticesettings']['wpcorestyle'] );
            $this->UI->option_switch( __( 'Text Spin Re-spinning', 'csv2post' ), 'textspinrespinning', 'textspinrespinning', $c2p_settings['standardsettings']['textspinrespinning'] );
            $this->UI->option_switch( __( 'Systematic Post Updating', 'csv2post' ), 'systematicpostupdating', 'systematicpostupdating', $c2p_settings['standardsettings']['systematicpostupdating'] );
            $this->UI->option_switch( __( 'WTG Flag System', 'csv2post' ), 'flagsystemstatus', 'flagsystemstatus', $c2p_settings['flagsystem']['status'] );
            $this->UI->option_switch( __( 'Dashboard Widgets Switch', 'csv2post' ), 'dashboardwidgetsswitch', 'dashboardwidgetsswitch', $c2p_settings['widgetsettings']['dashboardwidgetsswitch'], 'Enabled', 'Disabled', 'disabled' );      
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
    public function postbox_main_globaldatasettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'These are settings related to data management and apply to all projects.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

            <table class="form-table">
            <?php
            $this->UI->option_text( 'Import/Insert Limit', 'importlimit', 'importlimit', $c2p_settings['datasettings']['insertlimit'], false );
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
    public function postbox_main_logsettings( $data, $box ) {    
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'The plugin has its own log system with multi-purpose use. Not everything is logged for the sake of performance so please request increased log use if required.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        global $c2p_settings;
        ?>  

            <table class="form-table">
                <!-- Option Start -->
                <tr valign="top">
                    <th scope="row">Log</th>
                    <td>
                        <?php 
                        // if is not set ['admintriggers']['newcsvfiles']['status'] then it is enabled by default
                        if(!isset( $c2p_settings['globalsettings']['uselog'] ) ){
                            $radio1_uselog_enabled = 'checked'; 
                            $radio2_uselog_disabled = '';                    
                        }else{
                            if( $c2p_settings['globalsettings']['uselog'] == 1){
                                $radio1_uselog_enabled = 'checked'; 
                                $radio2_uselog_disabled = '';    
                            }elseif( $c2p_settings['globalsettings']['uselog'] == 0){
                                $radio1_uselog_enabled = ''; 
                                $radio2_uselog_disabled = 'checked';    
                            }
                        }?>
                        <fieldset><legend class="screen-reader-text"><span>Log</span></legend>
                            <input type="radio" id="logstatus_enabled" name="csv2post_radiogroup_logstatus" value="1" <?php echo $radio1_uselog_enabled;?> />
                            <label for="logstatus_enabled"> <?php _e( 'Enable', 'csv2post' ); ?></label>
                            <br />
                            <input type="radio" id="logstatus_disabled" name="csv2post_radiogroup_logstatus" value="0" <?php echo $radio2_uselog_disabled;?> />
                            <label for="logstatus_disabled"> <?php _e( 'Disable', 'csv2post' ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                <!-- Option End -->
      
                <?php       
                // log rows limit
                if(!isset( $c2p_settings['globalsettings']['loglimit'] ) || !is_numeric( $c2p_settings['globalsettings']['loglimit'] ) ){$c2p_settings['globalsettings']['loglimit'] = 1000;}
                $this->UI->option_text( 'Log Entries Limit', 'csv2post_loglimit', 'loglimit', $c2p_settings['globalsettings']['loglimit'] );
                ?>
            </table> 
            
                    
            <h4>Outcomes</h4>
            <label for="csv2post_log_outcomes_success"><input type="checkbox" name="csv2post_log_outcome[]" id="csv2post_log_outcomes_success" value="1" <?php if( isset( $c2p_settings['logsettings']['logscreen']['outcomecriteria']['1'] ) ){echo 'checked';} ?>> Success</label>
            <br> 
            <label for="csv2post_log_outcomes_fail"><input type="checkbox" name="csv2post_log_outcome[]" id="csv2post_log_outcomes_fail" value="0" <?php if( isset( $c2p_settings['logsettings']['logscreen']['outcomecriteria']['0'] ) ){echo 'checked';} ?>> Fail/Rejected</label>

            <h4>Type</h4>
            <label for="csv2post_log_type_general"><input type="checkbox" name="csv2post_log_type[]" id="csv2post_log_type_general" value="general" <?php if( isset( $c2p_settings['logsettings']['logscreen']['typecriteria']['general'] ) ){echo 'checked';} ?>> General</label>
            <br>
            <label for="csv2post_log_type_error"><input type="checkbox" name="csv2post_log_type[]" id="csv2post_log_type_error" value="error" <?php if( isset( $c2p_settings['logsettings']['logscreen']['typecriteria']['error'] ) ){echo 'checked';} ?>> Errors</label>
            <br>
            <label for="csv2post_log_type_trace"><input type="checkbox" name="csv2post_log_type[]" id="csv2post_log_type_trace" value="flag" <?php if( isset( $c2p_settings['logsettings']['logscreen']['typecriteria']['flag'] ) ){echo 'checked';} ?>> Trace</label>

            <h4>Priority</h4>
            <label for="csv2post_log_priority_low"><input type="checkbox" name="csv2post_log_priority[]" id="csv2post_log_priority_low" value="low" <?php if( isset( $c2p_settings['logsettings']['logscreen']['prioritycriteria']['low'] ) ){echo 'checked';} ?>> Low</label>
            <br>
            <label for="csv2post_log_priority_normal"><input type="checkbox" name="csv2post_log_priority[]" id="csv2post_log_priority_normal" value="normal" <?php if( isset( $c2p_settings['logsettings']['logscreen']['prioritycriteria']['normal'] ) ){echo 'checked';} ?>> Normal</label>
            <br>
            <label for="csv2post_log_priority_high"><input type="checkbox" name="csv2post_log_priority[]" id="csv2post_log_priority_high" value="high" <?php if( isset( $c2p_settings['logsettings']['logscreen']['prioritycriteria']['high'] ) ){echo 'checked';} ?>> High</label>
            
            <h1>Custom Search</h1>
            <p>This search criteria is not currently stored, it will be used on the submission of this form only.</p>
         
            <h4>Page</h4>
            <select name="csv2post_pluginpages_logsearch" id="csv2post_pluginpages_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php
                $current = '';
                if( isset( $c2p_settings['logsettings']['logscreen']['page'] ) && $c2p_settings['logsettings']['logscreen']['page'] != 'notselected' ){
                    $current = $c2p_settings['logsettings']['logscreen']['page'];
                } 
                $this->UI->page_menuoptions( $current);?> 
            </select>
            
            <h4>Action</h4> 
            <select name="csv2pos_logactions_logsearch" id="csv2pos_logactions_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $c2p_settings['logsettings']['logscreen']['action'] ) && $c2p_settings['logsettings']['logscreen']['action'] != 'notselected' ){
                    $current = $c2p_settings['logsettings']['logscreen']['action'];
                }
                $action_results = $this->DB->log_queryactions( $current);
                if( $action_results){
                    foreach( $action_results as $key => $action){
                        $selected = '';
                        if( $action['action'] == $current){
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="'.$action['action'].'" '.$selected.'>'.$action['action'].'</option>'; 
                    }   
                }?> 
            </select>
            
            <h4>Screen Name</h4>
            <select name="csv2post_pluginscreens_logsearch" id="csv2post_pluginscreens_logsearch" >
                <option value="notselected">Do Not Apply</option>
                <?php 
                $current = '';
                if( isset( $c2p_settings['logsettings']['logscreen']['screen'] ) && $c2p_settings['logsettings']['logscreen']['screen'] != 'notselected' ){
                    $current = $c2p_settings['logsettings']['logscreen']['screen'];
                }
                $this->UI->screens_menuoptions( $current);?> 
            </select>
                  
            <h4>PHP Line</h4>
            <input type="text" name="csv2post_logcriteria_phpline" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['line'] ) ){echo $c2p_settings['logsettings']['logscreen']['line'];} ?>">
            
            <h4>PHP File</h4>
            <input type="text" name="csv2post_logcriteria_phpfile" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['file'] ) ){echo $c2p_settings['logsettings']['logscreen']['file'];} ?>">
            
            <h4>PHP Function</h4>
            <input type="text" name="csv2post_logcriteria_phpfunction" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['function'] ) ){echo $c2p_settings['logsettings']['logscreen']['function'];} ?>">
            
            <h4>Panel Name</h4>
            <input type="text" name="csv2post_logcriteria_panelname" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['panelname'] ) ){echo $c2p_settings['logsettings']['logscreen']['panelname'];} ?>">

            <h4>IP Address</h4>
            <input type="text" name="csv2post_logcriteria_ipaddress" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['ipaddress'] ) ){echo $c2p_settings['logsettings']['logscreen']['ipaddress'];} ?>">
           
            <h4>User ID</h4>
            <input type="text" name="csv2post_logcriteria_userid" value="<?php if( isset( $c2p_settings['logsettings']['logscreen']['userid'] ) ){echo $c2p_settings['logsettings']['logscreen']['userid'];} ?>">    
          
            <h4>Display Fields</h4>                                                                                                                                        
            <label for="csv2post_logfields_outcome"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_outcome" value="outcome" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Outcome', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_line"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_line" value="line" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['line'] ) ){echo 'checked';} ?>> <?php _e( 'Line', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_file"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_file" value="file" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['file'] ) ){echo 'checked';} ?>> <?php _e( 'File', 'csv2post' );?></label> 
            <br>
            <label for="csv2post_logfields_function"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_function" value="function" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['function'] ) ){echo 'checked';} ?>> <?php _e( 'Function', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_sqlresult"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_sqlresult" value="sqlresult" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['sqlresult'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Result', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_sqlquery"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_sqlquery" value="sqlquery" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['sqlquery'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Query', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_sqlerror"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_sqlerror" value="sqlerror" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['sqlerror'] ) ){echo 'checked';} ?>> <?php _e( 'SQL Error', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_wordpresserror"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_wordpresserror" value="wordpresserror" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['wordpresserror'] ) ){echo 'checked';} ?>> <?php _e( 'WordPress Erro', 'csv2post' );?>r</label>
            <br>
            <label for="csv2post_logfields_screenshoturl"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_screenshoturl" value="screenshoturl" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['screenshoturl'] ) ){echo 'checked';} ?>> <?php _e( 'Screenshot URL', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_userscomment"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_userscomment" value="userscomment" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['userscomment'] ) ){echo 'checked';} ?>> <?php _e( 'Users Comment', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_page"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_page" value="page" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['page'] ) ){echo 'checked';} ?>> <?php _e( 'Page', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_version"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_version" value="version" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['version'] ) ){echo 'checked';} ?>> <?php _e( 'Plugin Version', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_panelname"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_panelname" value="panelname" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] ) ){echo 'checked';} ?>> <?php _e( 'Panel Name', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_tabscreenname"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_tabscreenname" value="tabscreenname" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] ) ){echo 'checked';} ?>> <?php _e( 'Screen Name *', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_dump"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_dump" value="dump" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['dump'] ) ){echo 'checked';} ?>> <?php _e( 'Dump', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_ipaddress"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_ipaddress" value="ipaddress" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['ipaddress'] ) ){echo 'checked';} ?>> <?php _e( 'IP Address', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_userid"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_userid" value="userid" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['userid'] ) ){echo 'checked';} ?>> <?php _e( 'User ID', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_comment"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_comment" value="comment" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['comment'] ) ){echo 'checked';} ?>> <?php _e( 'Developers Comment', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_type"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_type" value="type" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['type'] ) ){echo 'checked';} ?>> <?php _e( 'Entry Type', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_category"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_category" value="category" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['category'] ) ){echo 'checked';} ?>> <?php _e( 'Category', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_action"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_action" value="action" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['action'] ) ){echo 'checked';} ?>> <?php _e( 'Action', 'csv2post' );?></label>
            <br>
            <label for="csv2post_logfields_priority"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_priority" value="priority" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['priority'] ) ){echo 'checked';} ?>> <?php _e( 'Priority', 'csv2post' );?></label> 
            <br>
            <label for="csv2post_logfields_thetrigger"><input type="checkbox" name="csv2post_logfields[]" id="csv2post_logfields_thetrigger" value="thetrigger" <?php if( isset( $c2p_settings['logsettings']['logscreen']['displayedcolumns']['thetrigger'] ) ){echo 'checked';} ?>> <?php _e( 'Trigger', 'csv2post' );?></label> 

    
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
    public function postbox_main_iconsexplained( $data, $box ) {    
        ?>  
        <p class="about-description"><?php _e( 'The plugin has icons on the UI offering different types of help...' ); ?></p>
        
        <h3>Help Icon<?php echo $this->UI->helpicon( 'http://www.webtechglobal.co.uk/csv-2-post' )?></h3>
        <p><?php _e( 'The help icon offers a tutorial or indepth description on the WebTechGlobal website. Clicking these may open
        take a key page in the plugins portal or post in the plugins blog. On a rare occasion you will be taking to another users 
        website who has published a great tutorial or technical documentation.' )?></p>        
        
        <h3>Discussion Icon<?php echo $this->UI->discussicon( 'http://www.webtechglobal.co.uk/csv-2-post' )?></h3>
        <p><?php _e( 'The discussion icon open an active forum discussion or chat on the WebTechGlobal domain in a new tab. If you see this icon
        it means you are looking at a feature or area of the plugin that is a hot topic. It could also indicate the
        plugin author would like to hear from you regarding a specific feature. Occasionally these icons may take you to a discussion
        on other websites such as a Google circles, an official page on Facebook or a good forum thread on a users domain.' )?></p>
                          
        <h3>Info Icon<img src="<?php echo WTG_CSV2POST_IMAGES_URL;?>info-icon.png" alt="<?php _e( 'Icon with an i click it to read more information in a popup.' );?>"></h3>
        <p><?php _e( 'The information icon will not open another page. It will display a pop-up with extra information. This is mostly used within
        panels to explain forms and the status of the panel.' )?></p>        
        
        <h3>Video Icon<?php echo $this->UI->videoicon( 'http://www.webtechglobal.co.uk/csv-2-post' )?></h3>
        <p><?php _e( 'clicking on the video icon will open a new tab to a YouTube video. Occasionally it may open a video on another
        website. Occasionally a video may even belong to a user who has created a good tutorial.' )?></p> 
               
        <h3>Trash Icon<?php echo $this->UI->trashicon( 'http://www.webtechglobal.co.uk/csv-2-post' )?></h3>
        <p><?php _e( 'The trash icon will be shown beside items that can be deleted or objects that can be hidden.
        Sometimes you can hide a panel as part of the plugins configuration. Eventually I hope to be able to hide
        notices, especially the larger ones..' )?></p>      
      <?php     
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_main_twitterupdates( $data, $box ) {    
        ?>
        <p class="about-description"><?php _e( 'If you are using the plugin it makes sense to support the project. Every tweet helps to promote it...', 'csv2post' ); ?></p>    
        <a class="twitter-timeline" href="https://twitter.com/CSV2POST" data-widget-id="478813344225189889">Tweets by @CSV2POST</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id) ){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>                                                   
        <?php     
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.4
    */
    public function postbox_main_support( $data, $box ) {    
        ?>      
        <p><?php _e( 'All users are giving free support with the degree of support varying depending on your support for the project. Please get to know the plugins <a href="http://www.webtechglobal.co.uk/csv-2-post-support/" title="CSV 2 POST Support" target="_blank">support page</a> and portal.', 'csv2post' ); ?></p>                     
        <?php     
    }   
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_main_facebook( $data, $box ) {    
        ?>      
        <p class="about-description"><?php _e( "I've spent over 2000 hours creating and supporting this plugin for you. All I ask is a quick click on the Like button please...", 'csv2post' ); ?></p>
        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FWebTechGlobal1&amp;width=350&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true" scrolling="no" frameborder="0" style="padding: 10px 0 0 0;border:none; overflow:hidden; width:100%; height:290px;" allowTransparency="true"></iframe>                                                                             
        <?php     
    }

    /**
    * Form for setting which captability is required to view the page
    * 
    * By default there is no settings data for this because most people will never use it.
    * However when it is used, a new option record is created so that the settings are
    * independent and can be accessed easier.  
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.0.0
    */
    public function postbox_main_pagecapabilitysettings( $data, $box ) {
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Set the capability a user requires to view any of the plugins pages. This works independently of role plugins such as Role Scoper.', 'csv2post' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        
        // get the tab menu 
        $pluginmenu = $this->TabMenu->menu_array();
        ?>
        
        <table class="form-table">
        
        <?php 
        // get stored capability settings 
        $saved_capability_array = get_option( 'csv2post_capabilities' );
        
        // add a menu for each page for the user selecting the required capability 
        foreach( $pluginmenu as $key => $page_array ) {
            
            // do not add the main page to the list as a strict security measure
            if( $page_array['name'] !== 'main' ) {
                $current = null;
                if( isset( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) && is_string( $saved_capability_array['pagecaps'][ $page_array['name'] ] ) ) {
                    $current = $saved_capability_array['pagecaps'][ $page_array['name'] ];
                }
                
                $this->UI->option_menu_capabilities( $page_array['menu'], 'pagecap' . $page_array['name'], 'pagecap' . $page_array['name'], $current );
            }
        }?>
        
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
    public function postbox_main_dashboardwidgetsettings( $data, $box ) { 
        global $c2p_settings;
           
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'This panel is new and is advanced.   
        Please seek my advice before using it.
        You must be sure and confident that it operates in the way you expect.
        It will add widgets to your dashboard. 
        The capability menu allows you to set a global role/capability requirements for the group of wigets from any giving page. 
        The capability options in the "Page Capability Settings" panel are regarding access to the admin page specifically.', 'csv2post' ), false );   
             
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );

        echo '<table class="form-table">';

        // now loop through views, building settings per box (display or not, permitted role/capability  
        $CSV2POST_TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        $menu_array = $CSV2POST_TabMenu->menu_array();
        foreach( $menu_array as $key => $section_array ) {

            /*
                'groupname' => string 'main' (length=4)
                'slug' => string 'csv2post_generalsettings' (length=24)
                'menu' => string 'General Settings' (length=16)
                'pluginmenu' => string 'General Settings' (length=16)
                'name' => string 'generalsettings' (length=15)
                'title' => string 'General Settings' (length=16)
                'parent' => string 'main' (length=4)
            */
            
            // get dashboard activation status for the current page
            $current_for_page = '123nocurrentvalue';
            if( isset( $c2p_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] ) ) {
                $current_for_page = $c2p_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'];   
            }
            
            // display switch for current page
            $this->UI->option_switch( $section_array['menu'], $section_array['name'] . 'dashboardwidgetsswitch', $section_array['name'] . 'dashboardwidgetsswitch', $current_for_page, 'Enabled', 'Disabled', 'disabled' );
            
            // get current pages minimum dashboard widget capability
            $current_capability = '123nocapability';
            if( isset( $c2p_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] ) ) {
                $current_capability = $c2p_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'];   
            }
                            
            // capabilities menu for each page (rather than individual boxes, the boxes will have capabilities applied in code)
            $this->UI->option_menu_capabilities( __( 'Capability Required', 'csv2post' ), $section_array['name'] . 'widgetscapability', $section_array['name'] . 'widgetscapability', $current_capability );
        }

        echo '</table>';
                    
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
    public function postbox_main_createuploadcsvdatasource( $data, $box ) { 
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
    public function postbox_main_createurlcsvdatasource( $data, $box ) { 
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
    public function postbox_main_createservercsvdatasource( $data, $box ) { 
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
    public function postbox_main_createdirectorycsvdatasource( $data, $box ) { 
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
    public function postbox_main_changecsvfilepath( $data, $box ) {  
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
    public function postbox_main_rechecksourcedirectory( $data, $box ) { 
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
    public function postbox_main_urlimporttoexistingsource( $data, $box ) { 
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
    public function postbox_main_uploadfiletodatasource( $data, $box ) { 
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
    * Form for deleting a specific data source.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function postbox_main_deletedatasource( $data, $box ) {
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
    
    /**
    * post box function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_main_allprojectstable( $data, $box ) {    
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
    public function postbox_main_newprojectusingexistingsource( $data, $box ) {    
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
    public function postbox_main_deleteproject( $data, $box ) {    
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
    public function postbox_main_setactiveproject( $data, $box ) {    
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