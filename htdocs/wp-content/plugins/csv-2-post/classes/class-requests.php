<?php
/** 
* Class for handling $_POST and $_GET requests
* 
* The class is called in the process_admin_POST_GET() method found in the CSV2POST class. 
* The process_admin_POST_GET() method is hooked at admin_init. It means requests are handled in the admin
* head, globals can be updated and pages will show the most recent data. Nonce security is performed
* within process_admin_POST_GET() then the require method for processing the request is used.
* 
* Methods in this class MUST be named within the form or link itself, basically a unique identifier for the form.
* i.e. the Section Switches settings have a form name of "sectionswitches" and so the method in this class used to
* save submission of the "sectionswitches" form is named "sectionswitches".
* 
* process_admin_POST_GET() uses eval() to call class + method 
* 
* @package CSV 2 POST
* @author Ryan Bayne   
* @since 8.0.0
*/

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
* Class processes form submissions, the class is only loaded once nonce and other security checked
* 
* @author Ryan R. Bayne
* @package CSV 2 POST
* @since 8.0.0
* @version 1.0.2
*/
class C2P_Requests {  
    public function __construct() {
        global $csv2post_settings;
    
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' ); # plugin specific functions
        $this->UI = $this->CSV2POST->load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' ); # interface, mainly notices
        $this->DB = $this->CSV2POST->load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' ); # database interaction
        $this->PHP = $this->CSV2POST->load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' ); # php library by Ryan R. Bayne
        $this->Files = $this->CSV2POST->load_class( 'C2P_Files', 'class-files.php', 'classes' );
        $this->Forms = $this->CSV2POST->load_class( 'CSV2POST_Forms', 'class-forms.php', 'classes' );
        $this->WPCore = $this->CSV2POST->load_class( 'CSV2POST_WPCore', 'class-wpcore.php', 'classes' );
        $this->TabMenu = $this->CSV2POST->load_class( "C2P_TabMenu", "class-pluginmenu.php", 'classes','pluginmenu' ); 
                      
        // set current project values
        if( isset( $csv2post_settings['currentproject'] ) && $csv2post_settings['currentproject'] !== false ) {    
            $this->project_object = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] ); 
            if( !$this->project_object ) {
                $this->current_project_settings = false;
            } else {
                $this->current_project_settings = maybe_unserialize( $this->project_object->projectsettings ); 
            }
        }   
    }
    
    /**
    * Processes security for $_POST and $_GET requests,
    * then calls another function to complete the specific request made.
    * 
    * This function is called by process_admin_POST_GET() which is hooked by admin_init.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.0.1
    */
    public function process_admin_request() {  
        $method = 'post';// post or get
         
        // ensure processing requested
        // if a hacker changes this, no processing happens so no validation required
        if(!isset( $_POST['csv2post_admin_action'] ) && !isset( $_GET['csv2postaction'] ) ) {
            return;
        }          
               
        // handle $_POST action - form names are validated
        if( isset( $_POST['csv2post_admin_action'] ) && $_POST['csv2post_admin_action'] == true){        
            if( isset( $_POST['csv2post_admin_referer'] ) ){        
                
                // a few forms have the csv2post_admin_referer where the default hidden values are not in use
                check_admin_referer( $_POST['csv2post_admin_referer'] ); 
                $function_name = $_POST['csv2postaction'];     
                   
            } else {                                       
                
                // 99% of forms will use this method
                check_admin_referer( $_POST['csv2post_form_name'] );
                $function_name = $_POST['csv2post_form_name'];
            
            }        
        }
                          
        // $_GET request
        if( isset( $_GET['csv2postaction'] ) ){      
            check_admin_referer( $_GET['csv2postaction'] );        
            $function_name = $_GET['csv2postaction'];
            $method = 'get';
        }     
                   
        // arriving here means check_admin_referer() security is positive       
        global $c2p_debug_mode, $cont;

        $this->PHP->var_dump( $_POST, '<h1>$_POST</h1>' );           
        $this->PHP->var_dump( $_GET, '<h1>$_GET</h1>' );    
                              
        // $_POST security
        if( $method == 'post' ) {                      
            // check_admin_referer() wp_die()'s if security fails so if we arrive here WordPress security has been passed
            // now we validate individual values against their pre-registered validation method
            // some generic notices are displayed - this system makes development faster
            $post_result = true;
            $post_result = $this->Forms->apply_form_security();// ensures $_POST['csv2post_form_formid'] is set, so we can use it after this line
    
            // apply my own level of security per individual input
            if( $post_result ){ $post_result = $this->Forms->apply_input_security(); }// detect hacking of individual inputs i.e. disabled inputs being enabled 
      
            // validate users values
            if( $post_result ){ $post_result = $this->Forms->apply_input_validation( $_POST['csv2post_form_formid'] ); }// values (string,numeric,mixed) validation

            // cleanup to reduce registered data
            $this->Forms->deregister_form( $_POST['csv2post_form_formid'] );
                    
            // if $overall_result includes a single failure then there is no need to call the final function
            if( $post_result === false ) { 
            
                // log
                $atts = array(
                    'outcome' => 1,
                    'line' => __LINE__, 
                    'function' => __FUNCTION__,
                    'file' => __FILE__, 
                    'userid' => get_current_user_id(),          
                    'type' => 'trace',
                    'category' => 'usertrace',
                    'action' => __( 'Form submit request rejected after security and validation applied for form ID ' . $_POST['csv2post_form_formid'], 'csv2post' ),
                    'priority' => 'normal',                        
                    'triga' => 'manualrequest'
                );
                
                $this->CSV2POST->newlog( $atts );

                return false;
            }
        }
        
        // handle a situation where the submitted form requests a function that does not exist
        if( !method_exists( $this, $function_name ) ){
            
            // log
            $atts = array(
                'outcome' => 1,
                'line' => __LINE__, 
                'function' => __FUNCTION__,
                'file' => __FILE__, 
                'userid' => get_current_user_id(),          
                'type' => 'error',// using error is enough to attract attention to the problem
                'category' => 'usertrace',// still use usertrace so that the negative event the user witnessed can be considered when communicating with the user
                'action' => __( 'Requested function/method could not be found. This should be reported to WebTechGlobal.', 'csv2post' ),
                'priority' => 'high',                        
                'triga' => 'manualrequest'
            );
            
            $this->CSV2POST->newlog( $atts );
                
            wp_die( sprintf( __( "The method for processing your request was not found. This can usually be resolved quickly. Please report method %s does not exist. <a href='https://www.youtube.com/watch?v=vAImGQJdO_k' target='_blank'>Watch a video</a> explaining this problem.", 'wtgpluginframework' ), 
            $function_name) ); 
            return false;// should not be required with wp_die() but it helps to add clarity when browsing code and is a precaution.   
        }
        
        // all security passed - call the processing function
        if( isset( $function_name) && is_string( $function_name ) ) {
            eval( 'self::' . $function_name .'();' );
        }        
    }  

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.1
    */    
    public function request_success( $form_title, $more_info = '' ){  
        $this->UI->create_notice( "Your submission for $form_title was successful. " . $more_info, 'success', 'Small', "$form_title Updated");          
    } 

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.1
    */    
    public function request_failed( $form_title, $reason = '' ){
        $this->UI->n_depreciated( $form_title . ' Unchanged', "Your settings for $form_title were not changed. " . $reason, 'error', 'Small' );    
    }

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.1
    */    
    public function logsettings() {
        global $csv2post_settings;
        $csv2post_settings['globalsettings']['uselog'] = $_POST['csv2post_radiogroup_logstatus'];
        $csv2post_settings['globalsettings']['loglimit'] = $_POST['csv2post_loglimit'];
                                                   
        ##################################################
        #           LOG SEARCH CRITERIA                  #
        ##################################################
        
        // first unset all criteria
        if( isset( $csv2post_settings['logsettings']['logscreen'] ) ){
            unset( $csv2post_settings['logsettings']['logscreen'] );
        }
                                                           
        // if a column is set in the array, it indicates that it is to be displayed, we unset those not to be set, we dont set them to false
        if( isset( $_POST['csv2post_logfields'] ) ){
            foreach( $_POST['csv2post_logfields'] as $column){
                $csv2post_settings['logsettings']['logscreen']['displayedcolumns'][$column] = true;                   
            }
        }
                                                                                 
        // outcome criteria
        if( isset( $_POST['csv2post_log_outcome'] ) ){    
            foreach( $_POST['csv2post_log_outcome'] as $outcomecriteria){
                $csv2post_settings['logsettings']['logscreen']['outcomecriteria'][$outcomecriteria] = true;                   
            }            
        } 
        
        // type criteria
        if( isset( $_POST['csv2post_log_type'] ) ){
            foreach( $_POST['csv2post_log_type'] as $typecriteria){
                $csv2post_settings['logsettings']['logscreen']['typecriteria'][$typecriteria] = true;                   
            }            
        }         

        // category criteria
        if( isset( $_POST['csv2post_log_category'] ) ){
            foreach( $_POST['csv2post_log_category'] as $categorycriteria){
                $csv2post_settings['logsettings']['logscreen']['categorycriteria'][$categorycriteria] = true;                   
            }            
        }         

        // priority criteria
        if( isset( $_POST['csv2post_log_priority'] ) ){
            foreach( $_POST['csv2post_log_priority'] as $prioritycriteria){
                $csv2post_settings['logsettings']['logscreen']['prioritycriteria'][$prioritycriteria] = true;                   
            }            
        }         

        ############################################################
        #         SAVE CUSTOM SEARCH CRITERIA SINGLE VALUES        #
        ############################################################
        // page
        if( isset( $_POST['csv2post_pluginpages_logsearch'] ) && $_POST['csv2post_pluginpages_logsearch'] != 'notselected' ){
            $csv2post_settings['logsettings']['logscreen']['page'] = $_POST['csv2post_pluginpages_logsearch'];
        }   
        // action
        if( isset( $_POST['csv2pos_logactions_logsearch'] ) && $_POST['csv2pos_logactions_logsearch'] != 'notselected' ){
            $csv2post_settings['logsettings']['logscreen']['action'] = $_POST['csv2pos_logactions_logsearch'];
        }   
        // screen
        if( isset( $_POST['csv2post_pluginscreens_logsearch'] ) && $_POST['csv2post_pluginscreens_logsearch'] != 'notselected' ){
            $csv2post_settings['logsettings']['logscreen']['screen'] = $_POST['csv2post_pluginscreens_logsearch'];
        }  
        // line
        if( isset( $_POST['csv2post_logcriteria_phpline'] ) ){
            $csv2post_settings['logsettings']['logscreen']['line'] = $_POST['csv2post_logcriteria_phpline'];
        }  
        // file
        if( isset( $_POST['csv2post_logcriteria_phpfile'] ) ){
            $csv2post_settings['logsettings']['logscreen']['file'] = $_POST['csv2post_logcriteria_phpfile'];
        }          
        // function
        if( isset( $_POST['csv2post_logcriteria_phpfunction'] ) ){
            $csv2post_settings['logsettings']['logscreen']['function'] = $_POST['csv2post_logcriteria_phpfunction'];
        }
        // panel name
        if( isset( $_POST['csv2post_logcriteria_panelname'] ) ){
            $csv2post_settings['logsettings']['logscreen']['panelname'] = $_POST['csv2post_logcriteria_panelname'];
        }
        // IP address
        if( isset( $_POST['csv2post_logcriteria_ipaddress'] ) ){
            $csv2post_settings['logsettings']['logscreen']['ipaddress'] = $_POST['csv2post_logcriteria_ipaddress'];
        }
        // user id
        if( isset( $_POST['csv2post_logcriteria_userid'] ) ){
            $csv2post_settings['logsettings']['logscreen']['userid'] = $_POST['csv2post_logcriteria_userid'];
        }
        
        $this->CSV2POST->update_settings( $csv2post_settings );
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Settings Saved', 'csv2post' ), __( 'It may take sometime for new log entries to be created depending on your websites activity.', 'csv2post' ) );  
    }  
    
    /**
    * Create a data rule for replacing specific values after import 
    */
    public function eventtypes() {   
        $c2p_schedule_array = $this->CSV2POST->get_option_schedule_array();
        $c2p_schedule_array['eventtypes']["deleteuserswaiting"]['switch'] = $_POST["deleteuserswaiting"];
        $this->CSV2POST->update_option_schedule_array( $c2p_schedule_array );
        $this->UI->notice_depreciated( __( 'Schedule event types have been saved, the changes will have an effect on the types of events run, straight away.', 'csv2post' ), 'success', 'Large', __( 'Schedule Event Types Saved', 'csv2post' ), '', 'echo' );
    } 
    
    /**
    * Data panel one settings
    */
    public function panelone() {
        global $csv2post_settings;
        $csv2post_settings['globalsettings']['encoding']['type'] = $_POST['csv2post_radiogroup_encoding'];
        $csv2post_settings['globalsettings']['admintriggers']['newcsvfiles']['status'] = $_POST['csv2post_radiogroup_detectnewcsvfiles'];
        $csv2post_settings['globalsettings']['admintriggers']['newcsvfiles']['display'] = $_POST['csv2post_radiogroup_detectnewcsvfiles_display'];
        $csv2post_settings['globalsettings']['postfilter']['status'] = $_POST['csv2post_radiogroup_postfilter'];          
        $csv2post_settings['globalsettings']['postfilter']['tokenrespin']['status'] = $_POST['csv2post_radiogroup_spinnertokenrespin'];        
        $this->CSV2POST->update_settings( $csv2post_settings );
        $this->UI->n_postresult_depreciated( 'success', __( 'Data Related Settings Saved', 'csv2post' ), __( 'We recommend that you monitor the plugin for a short time and ensure your new settings are as expected.', 'csv2post' ) );
    }

    /**
    * update global data settings
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0.1
    */ 
    public function globaldatasettings () {
        global $csv2post_settings;
        
        if(!is_numeric( $_POST['importlimit'] ) ){
            $this->UI->create_notice( 'You must enter a numeric value for the maximum number of records to be imported and inserted to the database per event.
            This applies to manual events only.', 'error', 'Small', 'Import Limit Number Required' );
            return;
        }
        
        $value = $_POST['importlimit'];
        if( $_POST['importlimit'] < 1){
            $value = 1;
        }
        
        $csv2post_settings['datasettings']['insertlimit'] = $_POST['importlimit'];
        $this->CSV2POST->update_settings( $csv2post_settings );
        $this->UI->create_notice( __( 'Your data options have been saved.', 'csv2post' ), 'success', 'Small', __( 'Global Data Settings Saved', 'csv2post' ) );        
    } 
    
    /**
    * Save drip feed limits  
    */
    public function schedulesettings() {
        $c2p_schedule_array = $this->CSV2POST->get_option_schedule_array();
        
        // if any required values are not in $_POST set them to zero
        if(!isset( $_POST['day'] ) ){
            $c2p_schedule_array['limits']['day'] = 0;        
        }else{
            $c2p_schedule_array['limits']['day'] = $_POST['day'];            
        }
        
        if(!isset( $_POST['hour'] ) ){
            $c2p_schedule_array['limits']['hour'] = 0;
        }else{
            $c2p_schedule_array['limits']['hour'] = $_POST['hour'];            
        }
        
        if(!isset( $_POST['session'] ) ){
            $c2p_schedule_array['limits']['session'] = 0;
        }else{
            $c2p_schedule_array['limits']['session'] = $_POST['session'];            
        }
                                 
        // ensure $c2p_schedule_array is an array, it may be boolean false if schedule has never been set
        if( isset( $c2p_schedule_array ) && is_array( $c2p_schedule_array ) ){
            
            // if times array exists, unset the [times] array
            if( isset( $c2p_schedule_array['days'] ) ){
                unset( $c2p_schedule_array['days'] );    
            }
            
            // if hours array exists, unset the [hours] array
            if( isset( $c2p_schedule_array['hours'] ) ){
                unset( $c2p_schedule_array['hours'] );    
            }
            
        }else{
            // $schedule_array value is not array, this is first time it is being set
            $c2p_schedule_array = array();
        }
        
        // loop through all days and set each one to true or false
        if( isset( $_POST['csv2post_scheduleday_list'] ) ){
            foreach( $_POST['csv2post_scheduleday_list'] as $key => $submitted_day ){
                $c2p_schedule_array['days'][$submitted_day] = true;        
            }  
        } 
        
        // loop through all hours and add each one to the array, any not in array will not be permitted                              
        if( isset( $_POST['csv2post_schedulehour_list'] ) ){
            foreach( $_POST['csv2post_schedulehour_list'] as $key => $submitted_hour){
                $c2p_schedule_array['hours'][$submitted_hour] = true;        
            }           
        }    

        if( isset( $_POST['deleteuserswaiting'] ) )
        {
            $c2p_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'enabled';                
        }
        
        if( isset( $_POST['eventsendemails'] ) )
        {
            $c2p_schedule_array['eventtypes']['sendemails']['switch'] = 'enabled';    
        }        
  
        $this->CSV2POST->update_option_schedule_array( $c2p_schedule_array );
        $this->UI->notice_depreciated( __( 'Schedule settings have been saved.', 'csv2post' ), 'success', 'Large', __( 'Schedule Times Saved', 'csv2post' ) );   
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function logsearchoptions() {
        $this->UI->n_postresult_depreciated( 'success', __( 'Log Search Settings Saved', 'csv2post' ), __( 'Your selections have an instant effect. Please browse the Log screen for the results of your new search.', 'csv2post' ) );                   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function deleteproject() {
        global $wpdb, $csv2post_settings;
        
        if(empty( $_POST['confirmcode'] ) ){
            $this->UI->create_notice( __( 'Please re-enter the confirmation code.' ), 'error', 'Small', __( 'Confirmation Code Required' ) );
            return;
        }    
        
        if( $_POST['randomcode'] !== $_POST['confirmcode'] ){
            $this->UI->create_notice( __( 'Confirmation codes do not match.' ), 'error', 'Small', __( 'No Match' ) );
            return;
        }
        
        if(empty( $_POST['projectid'] ) ){
            $this->UI->create_notice( __( 'Project ID required, please ensure you get the correct ID.' ), 'error', 'Small', __( 'Project ID Required' ) );
            return;
        }
        
        if(!is_numeric( $_POST['projectid'] ) ){
            $this->UI->create_notice( __( 'Project ID must be numeric.' ), 'error', 'Small', __( 'Invalid Project ID' ) );
            return;
        }
        
        // delete row from c2pprojects table
        $this->DB->delete( $wpdb->c2pprojects, "projectid = '" . $_POST['projectid'] . "'");
               
        // unset current project setting
        unset( $csv2post_settings['currentproject'] );
        $this->CSV2POST->update_settings( $csv2post_settings );
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'projectchange',
            'action' => sprintf( __( 'Project deleted. The projects ID is %d.', 'csv2post' ), $_POST['projectid'] ),
            'priority' => 'high',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                
        $this->UI->create_notice( __( 'Your project was deleted.' ), 'success', 'Small', __( 'Success' ) );
    } 

    /**
    * changes the current project to the next in the list
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function currentprojectnext() {
        global $wpdb, $csv2post_settings;
        $projects_array = $this->DB->selectwherearray( $wpdb->c2pprojects, 'projectid = projectid', 'timestamp', '*', ARRAY_A, 'ASC' );
        
        // get array key for current project
        $active_array_key = false;
        foreach( $projects_array as $key => $project ) {
            if( $project['projectid'] === $csv2post_settings['currentproject'] ) {
                $active_array_key = $key; 
                break;       
            }    
        }
        
        // count total projects so we avoid trying to access a none existing array key 
        $total_projects = count( $projects_array );
        
        $last_key = $total_projects - 1;

        if( $active_array_key == $last_key ) {
            // current project is also the last in the array, set the first as the current project
            $csv2post_settings['currentproject'] = $projects_array[ 0 ]['projectid'];        
        } else {
            $new_active_key = $active_array_key + 1;
            $csv2post_settings['currentproject'] = $projects_array[ $new_active_key ]['projectid'];    
        }
   
        $this->CSV2POST->update_settings( $csv2post_settings );    
    }    
    
    /**
    * changes the current project to the previous in the list
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function currentprojectprevious() {
        global $wpdb, $csv2post_settings;
        $projects_array = $this->DB->selectwherearray( $wpdb->c2pprojects, 'projectid = projectid', 'timestamp', '*', ARRAY_A, 'ASC' );
        
        // get array key for current project
        $active_array_key = false;
        foreach( $projects_array as $key => $project ) {
            if( $project['projectid'] === $csv2post_settings['currentproject'] ) {
                $active_array_key = $key;        
            }    
        }
        
        // if $active_array_key is 1 or 0 or accidental -1 set the last item in array as next
        if( $active_array_key < 2 ) {
            $new_active_key = count( $projects_array ) - 1;
            $csv2post_settings['currentproject'] = $projects_array[ $new_active_key ]['projectid'];        
        } else {
            $new_active_key = $active_array_key - 1;
            $csv2post_settings['currentproject'] = $projects_array[ $new_active_key ]['projectid'];    
        }
   
        $this->CSV2POST->update_settings( $csv2post_settings );
    }
       
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function setexpecteddatatypes() {
        global $csv2post_settings, $wpdb;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);

        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            foreach( $columns_array as $key => $column){
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    // is the current table and column set in $_POST
                    if( isset( $_POST['datatypescolumns#'.$source_table.'#'.$column] ) ){
                        if( $_POST['datatypescolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'datatypes', $_POST['datatypescolumns#'.$source_table.'#'.$column], $column);                       
                        }elseif( $_POST['datatypescolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'datatypes', $column);
                        }
                    }
                }
            }
        }
        
        $this->UI->create_notice( __( 'Your column data types have been updated. There are forms that will adapt to your changes to simplify things for you.' ), 'success', 'Small', __( 'Data Types Updated' ) );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function roundnumberup () {
        global $csv2post_settings;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);
 
        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            foreach( $columns_array as $key => $column){
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    // is the current table and column set in $_POST
                    if( isset( $_POST['roundnumberupcolumns#'.$source_table.'#'.$column] ) ){
                        if( $_POST['roundnumberupcolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'roundnumberupcolumns', true, $column);                       
                        }elseif( $_POST['roundnumberupcolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'roundnumberupcolumns', $column);
                        }
                    }
                }
            }
        }

        $this->UI->create_notice( __( 'Values in the selected columns will be rounded upwards.' ), 'success', 'Small', __( 'Rules Updated' ) );   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function roundnumberdown() {
        global $csv2post_settings;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);
 
        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            foreach( $columns_array as $key => $column){
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    // is the current table and column set in $_POST
                    if( isset( $_POST['roundnumbercolumns#'.$source_table.'#'.$column] ) ){
                        if( $_POST['roundnumbercolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'roundnumbercolumns', true, $column);                       
                        }elseif( $_POST['roundnumbercolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'roundnumbercolumns', $column);
                        }
                    }
                }
            }
        }
        
        $this->UI->create_notice( __( 'Values in the selected columns will be rounded down.' ), 'success', 'Small', __( 'Round-down Rules Updated' ) );   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function captalizefirstletter() {
        global $csv2post_settings;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);
 
        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            foreach( $columns_array as $key => $column){
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    // is the current table and column set in $_POST
                    if( isset( $_POST['captalizefirstlettercolumns#'.$source_table.'#'.$column] ) ){
                        if( $_POST['captalizefirstlettercolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'captalizefirstlettercolumns', true, $column);                       
                        }elseif( $_POST['captalizefirstlettercolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'captalizefirstlettercolumns', $column);
                        }
                    }
                }
            }
        }
        
        $this->UI->create_notice( __( 'The first letter in the selected column text data will be capitalized.' ), 'success', 'Small', __( 'Capitalize Rules Updated' ) );   
    }  
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function lowercaseall() {
        global $csv2post_settings;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);
 
        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            foreach( $columns_array as $key => $column){
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    // is the current table and column set in $_POST
                    if( isset( $_POST['lowercaseallcolumns#'.$source_table.'#'.$column] ) ){
                        if( $_POST['lowercaseallcolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'lowercaseallcolumns', true, $column);                       
                        }elseif( $_POST['lowercaseallcolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'lowercaseallcolumns', $column);
                        }
                    }
                }
            }
        }
        
        $this->UI->create_notice( __( 'All text in the selected column will be made lower-case during importing.' ), 'success', 'Small', __( 'Lower-case Rules Updated' ) );   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function uppercaseall() {
        global $csv2post_settings;
        
        $project_columns_array = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'], true);
 
        // all columns are in one array but the key are the tables, each table needs its own update query 
        foreach( $project_columns_array as $source_table => $columns_array ){
            
            foreach( $columns_array as $key => $column){
                
                // array contains information we must skip
                if( $key != 'datatreatment' && $key != 'sources' ){
                    
                    // is the current table and column set in $_POST
                    if( isset( $_POST['uppercaseallcolumns#'.$source_table.'#'.$column] ) ){
                        
                        if( $_POST['uppercaseallcolumns#'.$source_table.'#'.$column] != 'notrequired' ){
                            
                            $this->CSV2POST->add_new_data_rule( $_POST['sourceid_' . $source_table . $column], 'uppercaseallcolumns', true, $column);  
                                                 
                        }elseif( $_POST['uppercaseallcolumns#'.$source_table.'#'.$column] == 'notrequired' ){
                            
                            $this->CSV2POST->delete_data_rule( $_POST['sourceid_' . $source_table . $column], 'uppercaseallcolumns', $column);
                            
                        }
                    }
                }
            }
        }
        
        $this->UI->create_notice( __( 'All text in the selected column will be made upper-case during importing.' ), 'success', 'Small', __( 'Upper-case Rules Updated' ) );   
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */             
    public function datasplitter() {
        global $wpdb, $csv2post_settings;
        
        // column to be splitted makes its source the primary/head in this procedure and the lead is taking from that during import
        $explode_splitter_column = explode( '#', $_POST['datasplitercolumn'] );
        
        // we need the source id that the splitter rules applies to
        $sourceid = $this->DB->selectrow( $wpdb->c2psources, 'projectid = ' . $csv2post_settings['currentproject'] .' AND tablename = "' . $explode_splitter_column[0] .'"', 'sourceid' );

        // now query the rules column in the established source
        $rules_array = $this->CSV2POST->get_data_rules_source( $sourceid->sourceid);# returns array no matter what so we can just get working with it
        
        // begin adding values to the rules_array
        $rules_array['splitter']['separator'] = $_POST['datasplitterseparator'];// separator character
        $rules_array['splitter']['datasplitertable'] = $explode_splitter_column[0];
        $rules_array['splitter']['datasplitercolumn'] = $explode_splitter_column[1];
        
        // ensure we have the first and second tables + columns
        if(empty( $_POST["receivingcolumn1"] ) || empty( $_POST["receivingtable1"] ) || empty( $_POST["receivingcolumn2"] ) || empty( $_POST["receivingtable2"] ) ){
            $this->UI->create_notice( __( 'The data splitting procedure requires a minimum of two columns for the split data to be inserted to. Please complete the first four text fields.' ), 'error', 'Small', 'Two Columns Required' );
            return;
        }
        
        // add up to 5 tables+columns to rules array
        for( $i=1;$i<=5;$i++){
            if(empty( $_POST["receivingcolumn$i"] ) || empty( $_POST["receivingtable$i"] ) ){
                break;                        
            }

            $rules_array['splitter']["receivingtable$i"] = $_POST["receivingtable$i"];
            $rules_array['splitter']["receivingcolumn$i"] = $_POST["receivingcolumn$i"];
            
            // do the receiving columns exist if not create them
            $table_columns_array = $this->DB->get_tablecolumns( $_POST["receivingtable$i"], true, true);

            if(!in_array( $_POST["receivingcolumn$i"], $table_columns_array ) ){
                $result = $wpdb->query( 'alter table '.$_POST["receivingtable$i"].' add column '.$_POST["receivingcolumn$i"].' TEXT default null' );
                if(!$result){
                    $this->UI->create_notice( 'CSV 2 POST attempted to add a new column named '.$_POST["receivingcolumn$i"].' to table named '.$_POST["receivingtable$i"], 'error', 'Small', 'Database Query Failure' );
                }elseif( $result){
                    $this->CSV2POST->create_notice( 'A new column named '.$_POST["receivingcolumn$i"].' was added to table named '.$_POST["receivingtable$i"], 'success', 'Small', 'New Column Added' );
                }
            }    
        }
          
        $rules_array = maybe_serialize( $rules_array );                
        $this->DB->update( $wpdb->c2psources, "sourceid = $sourceid->sourceid", array( 'rules' => $rules_array ) );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function importdata0() {
        global $csv2post_settings;
        $this->CSV2POST->import_from_csv_file( $_POST['sourceid'], $csv2post_settings['currentproject'] );
    }
     
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */      
    public function importdata1() {
        global $csv2post_settings;
        $this->CSV2POST->import_from_csv_file( $_POST['sourceid'], $csv2post_settings['currentproject'] );   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function importdata2() {
        global $csv2post_settings;
        $this->CSV2POST->import_from_csv_file( $_POST['sourceid'], $csv2post_settings['currentproject'] );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function importdata3() {
        global $csv2post_settings;
        $this->CSV2POST->import_from_csv_file( $_POST['sourceid'], $csv2post_settings['currentproject'] );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function importdata4() {
        global $csv2post_settings;
        $this->CSV2POST->import_from_csv_file( $_POST['sourceid'], $csv2post_settings['currentproject'] );    
    }
    
    /**
    * quick action data import request
    */
    public function importdatecurrentproject() {
        global $csv2post_settings;
        
        // import records from all sources for curret project
        $sourceid_array = $this->CSV2POST->get_project_sourcesid( $csv2post_settings['currentproject'] );
        foreach( $sourceid_array as $key => $source_id ){
            $this->CSV2POST->import_from_csv_file( $source_id, $csv2post_settings['currentproject'], 'import',2 );
        }        
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function updatedatacurrentproject() {
        global $csv2post_settings;
        
        // import records from all sources for curret project
        $sourceid_array = $this->CSV2POST->get_project_sourcesid( $csv2post_settings['currentproject'] );
        foreach( $sourceid_array as $key => $source_id){
            $this->CSV2POST->import_from_csv_file( $source_id, $csv2post_settings['currentproject'], 'update' );
        }  
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function categorydata() {
        global $csv2post_settings;
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        $project_settings = maybe_unserialize( $project_array->projectsettings );
        if(!is_array( $project_settings ) ){$project_settings = array();}
        
        unset( $project_settings['categories']['data'] );
        
        $onceonly_array = array();
        
        // first menu is required
        if( $_POST['categorylevel0'] == 'notselected' ){
            $this->UI->create_notice( __( 'The first level is always required. Please make a selection in the first menu.' ), 'error', 'Small', 'Level 0 Required' );
            return;
        }        

        // add first level to final array 
        $onceonly_array[] = $_POST['categorylevel0'];
        $cat1_exploded = explode( '#', $_POST['categorylevel0'] );
        $project_settings['categories']['data'][0]['table'] = $cat1_exploded[0];
        $project_settings['categories']['data'][0]['column'] = $cat1_exploded[1];
        
        for( $i=1;$i<=4;$i++){   
            if( $_POST["categorylevel$i"] !== 'notselected' ){
                if(in_array( $_POST["categorylevel$i"], $onceonly_array ) ){
                    $this->UI->create_notice( __( 'You appear to have selected the same table and column twice. Each level of categories normally requires
                    different terms/titles and so this validation exists to prevent accidental selection of the same column more than once.' ),
                    'error', 'Small', "Column Selected Twice");
                    return;
                }
                
                $onceonly_array[] = $_POST["categorylevel$i"];
                $exploded = explode( '#', $_POST["categorylevel$i"] );
                $project_settings['categories']['data'][$i]['table'] = $exploded[0];
                $project_settings['categories']['data'][$i]['column'] = $exploded[1];
                        
            }else{
                // break when we reach the first not selected, this ensures we do not allow user to skip a level
                break;
            }
        }

        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_settings ) ), true);
        $this->UI->create_notice( __( "Your category options have been saved."), 'success', 'Small', __( 'Categories Saved' ) );
    }
     
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */      
    public function categorydescriptions() {
        global $csv2post_settings;
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        $categories_array = maybe_unserialize( $project_array->projectsettings );
        
        if( !is_array( $categories_array ) ){
            $categories_array = array();
        }
        
        for( $i=0;$i<=4;$i++){
            if( isset( $_POST['level'.$i.'description'] ) ){
                $categories_array['categories']['meta'][$i]['description'] = $_POST['level'.$i.'description'];
            }           
        }
         
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $categories_array ) ), true);
        $this->UI->create_notice( __( "Your category descriptons have been saved."), 'success', 'Small', __( 'Category Descriptions Saved' ) );        
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function categorypairing() {
        global $wpdb, $csv2post_settings;
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        $categories_array = maybe_unserialize( $project_array->projectsettings);
        
        // remove all existing mapping
        if( isset( $categories_array['categories']['mapping'] ) ){unset( $categories_array['categories']['mapping'] );}
        
        // query and loop through distinct category terms
        foreach( $categories_array['categories']['data'] as $key => $catarray ){
                               
            $column = $catarray['column'];
            $table = $catarray['table'];

            $distinct_result = $wpdb->get_results( "SELECT DISTINCT $column FROM $table",ARRAY_A);
            foreach( $distinct_result as $key => $value ){
                
                $distinctval_cleaned = $this->PHP->clean_string( $value[$column] );
                $nameandid = 'distinct'.$table.$column.$distinctval_cleaned;
                
                if( isset( $_POST[$column .'#'. $table .'#'. $distinctval_cleaned] ) ){
                    
                    if( isset( $_POST['existing'. $distinctval_cleaned] ) && $_POST['existing'. $distinctval_cleaned] != 'notselected' ){

                        $ourterm = $_POST[$column .'#'. $table .'#'. $distinctval_cleaned];// I think this is the same as $value[$column]
                        $categories_array['categories']['mapping'][$table][$column][ $ourterm ] = $_POST['existing'. $distinctval_cleaned];

                    }
                }
            }
        }
                         
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $categories_array ) ), true);
        $this->UI->create_notice( __( "Your category mapping has been saved."), 'success', 'Small', __( 'Category Map Saved' ) );
    }
    
    /**
    * manual category creation
    * 
    * requires all of users data to be imported to project table
    * the general procedure is the same as used on the projection screen but it has been adapted
    */          
    public function categorycreation() {
        global $csv2post_settings;    
        
        $projection_array = array();

        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        
        $projectsettings = maybe_unserialize( $project_array->projectsettings);
          
        // we need to build a query that includes all columns and gets all rows
        $select = '';
        $from = 'notsetyet';
        foreach( $projectsettings['categories']['data'] as $level => $catarray ){
                                         
            $c = $catarray['column'];
            $t = $catarray['table'];
            
            if( $select === '' ){
                $select .= $c;
            }else{
                $select .= ', ' . $c;
            }
            
            // all columns must be in the same table (this can be adapted though)
            if( $from == 'notsetyet' ){
                $from = $t;
            }elseif( $from != $t){
                $this->UI->create_notice( __( 'Your category columns are not all within the same database table, no categories have been created. This is a strict requirement in the current version. It can be adapted if needed but the plugin is not currently ready to create categories using two or more tables.' ), 'error', 'Small', 'Too Many Tables' );
                return;
            }  
        } 
          
        $rows = $this->DB->selectorderby( $from, false, false, $select);

        if(!$rows){
            $this->UI->create_notice( __( 'No data appears to be available for creating your categories. Please ensure your data has been imported.' ), 'warning', 'Small', 'No Categories Created' );
            return;
        }
        
        foreach( $rows as $key => $category_group){
            $group_array = array();// we will store each cat ID in this array (use to apply parent and store in project table)
            $level = 0;// use this to query terms within current level, compare level + term name
     
            // if a level one category ID has been pre-set apply it and increase level 
            if( $level == 0 && isset( $projectsettings['categories']['presetcategoryid'] ) && is_numeric( $projectsettings['categories']['presetcategoryid'] ) ){       
                $group_array[$level] = $projectsettings['categories']['presetcategoryid'];
                
                ++$level;
            }
            
            foreach( $category_group as $column => $my_term){

                // do we need to create a category using $my_term or has user mapped the term to an existing cat ID
                if( isset( $projectsettings['categories']['mapping'][$from][$column][ $my_term ] ) && is_numeric( $projectsettings['categories']['mapping'][$from][$column][ $my_term ] ) ){         
                    $group_array[$level] = $projectsettings['categories']['mapping'][$from][$column][ $my_term ];    
                }else{       
                    // does term exist within the current level? 
                    $existing_term_id = $this->CSV2POST->term_exists_in_level( $my_term, $level);
        
                    if(is_numeric( $existing_term_id) ){
                        /* 
                        
                        we could check if the existing terms parent is also the expected parent within the users data 
                        however it gets complicated because the value in the data may also be mapped and so the expected parent
                        could be a mapped one, not sure how badly this is needed I can add it later 
                         
                        */
                        $group_array[$level] = $existing_term_id;
                    }else{
                        
                        // set parent id
                        $parent_id = 0;
                        if( $level > 0){
                            $parent_keyin_group = $level - 1;
                            $parent_id = $group_array[$parent_keyin_group];   
                        }
                        
                        // create a new category term
                        $new_cat_id = wp_create_category( $my_term, $parent_id );
                          
                        if( isset( $new_cat_id) && is_numeric( $new_cat_id) ){
                            $group_array[$level] = $new_cat_id; 
                        }else{
                            $this->UI->create_notice( __( 'WP returned an error when attempting to create a new category. The category creation procedure has been aborted, please report this to WebTechGlobal and provide your data for testing purposes.' ), 'error', 'Small', __( 'Category Creation Failure' ) );
                            return false;
                        }                         
                    }
                }                

                ++$level;
            }
        } 
        $this->UI->create_notice( __( 'Category creation has finished. Has the plugin has created them as expected? If you have any difficulties please seek support at WebTechGlobal.' ), 'success', 'Small', 'Categories Created' );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function newcustomfield() {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $customfields_array = maybe_unserialize( $project_array->projectsettings);
        
        // clean the cf name and if the cleaned string differs then the original is not suitable
        $cleaned_string = preg_replace( "/[^a-zA-Z0-9s_]/", "", $_POST['customfieldname'] );    
        if( $cleaned_string !== $_POST['customfieldname'] ){ 
            $this->UI->create_notice( __( 'Your custom field name/key is invalid. It must not contain spaces or special characters. Underscore is acceptable.' ), 'error', 'Small', 'Invalid Name' );
            return false;
        }
        
        // ensure a value was submitted
        if(empty( $_POST['customfielddefaultcontent'] ) ){
            $this->UI->create_notice( __( 'You did not enter a column token or any other content to the WYSIWYG editor.' ), 'error', 'Small', 'Custom Field Value Required' );
            return false;
        }
        
        // if unique ensure the custom field name has not already been used
        if( $_POST['customfieldunique'] == 'enabled' && isset( $customfields_array['customfields']['cflist'][$_POST['customfieldname']] ) ){ 
            $this->UI->create_notice( __( 'That custom field name already exists in your list. You opted for the custom field name to be unique for each post so you cannot use the name/key twice. If you require the custom field name to exist multiple times per post i.e. to create a list of items. Then please select No for the Unique option.' ), 'error', 'Small', 'Name Exists Already' );
            return false;       
        }

        // set next array key value
        $next_key = 0;

        // determine next array key
        if( isset( $customfields_array['customfields']['cflist'] ) ){    
            $next_key = $this->CSV2POST->get_array_nextkey( $customfields_array['customfields']['cflist'] );
        }   
               
        $customfields_array['customfields']['cflist'][$next_key]['id'] = $next_key;
        $customfields_array['customfields']['cflist'][$next_key]['name'] = $_POST['customfieldname'];
        $customfields_array['customfields']['cflist'][$next_key]['unique'] = $_POST['customfieldunique'];
        $customfields_array['customfields']['cflist'][$next_key]['updating'] = $_POST['customfieldupdating'];
        $customfields_array['customfields']['cflist'][$next_key]['value'] = $_POST['customfielddefaultcontent'];
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $customfields_array ) ), true);
        $this->UI->create_notice( __( "Your custom field has been added to the list."), 'success', 'Small', __( 'New Custom Field Created' ) );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function deletecustomfieldrule() {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $customfields_array = maybe_unserialize( $project_array->projectsettings);
        
        if(!isset( $_GET['cfid'] ) ){
            $this->UI->create_notice( __( 'No ID was, no custom fields deleted.' ), 'error', 'Small', __( 'ID Required' ) );
            return;    
        }
        
        if(!isset( $customfields_array['cflist'][$_GET['cfid']] ) ){
            $this->UI->create_notice( __( 'The ID submitted could not be found, it appears your custom field has already been deleted.' ), 'error', 'Small', __( 'ID Does Not Exist' ) );
            return;            
        }
        
        unset( $customfields_array['cflist'][$_GET['cfid']] );
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $customfields_array ) ), true );
        $this->UI->create_notice( __( "Your custom field has been added to the list."), 'success', 'Small', __( 'New Custom Field Created' ) );        
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function singletaxonomies() {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $taxonomies_array = maybe_unserialize( $project_array->projectsettings);
        if(!is_array( $taxonomies_array ) ){$taxonomies_array = array();}

        for( $i=1; $i<=$_POST['numberoftaxonomies']; $i++){
            if( isset( $_POST["column$i"] ) && $_POST["column$i"] !== 'notselected' ) {
                $exploded_tax = explode( '.', $_POST["taxonomy$i"] );
                $exploded_col = explode( '#', $_POST["column$i"] );
                $post_type = $exploded_tax[0];
                $tax_name = $exploded_tax[1];                       
                $taxonomies_array['taxonomies']['columns'][$post_type][$tax_name]['table'] = $exploded_col[0];
                $taxonomies_array['taxonomies']['columns'][$post_type][$tax_name]['column'] = $exploded_col[1];
            }
        }

        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $taxonomies_array ) ), true);
        $this->UI->create_notice( __( "Your taxonomy setup has been updated."), 'success', 'Small', __( 'Taxonomies Updated' ) );                
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function basicpostoptions () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 
               
        $project_array['basicsettings']['poststatus'] = $_POST['poststatus'];
        $project_array['basicsettings']['pingstatus'] = $_POST['pingstatus'];
        $project_array['basicsettings']['commentstatus'] = $_POST['commentstatus'];
        $project_array['basicsettings']['defaultauthor'] = $_POST['defaultauthor'];
        $project_array['basicsettings']['defaultcategory'] = $_POST['defaultcategory'];
        $project_array['basicsettings']['defaultposttype'] = $_POST['defaultposttype'];
        $project_array['basicsettings']['defaultpostformat'] = $_POST['defaultpostformat'];
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true);
        $this->UI->create_notice( __( "Your basic post options have been saved."), 'success', 'Small', __( 'Basic Post Options Saved' ) );        
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function databasedoptions () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 

        if( $_POST['tags'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['tags'] );
            $project_array['tags']['table'] = $exploded[0];
            $project_array['tags']['column'] = $exploded[1];
        }
        
        if( $_POST['featuredimage'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['featuredimage'] );
            $project_array['featuredimage']['table'] = $exploded[0];
            $project_array['featuredimage']['column'] = $exploded[1];
        }
        
        if( $_POST['permalink'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['permalink'] );
            $project_array['permalink']['table'] = $exploded[0];
            $project_array['permalink']['column'] = $exploded[1];
        }
        
        if( $_POST['permalink'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['permalink'] );
            $project_array['urlcloak1']['table'] = $exploded[0];
            $project_array['urlcloak1']['column'] = $exploded[1];
        }
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "Your data based post options have been saved."), 'success', 'Small', __( 'Data Based Post Options Saved' ) );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function authoroptions () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 

        if( $_POST['email'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['email'] );
            $project_array['authors']['email']['table'] = $exploded[0];
            $project_array['authors']['email']['column'] = $exploded[1];
        }
        
        if( $_POST['username'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['username'] );
            $project_array['authors']['username']['table'] = $exploded[0];
            $project_array['authors']['username']['column'] = $exploded[1];
        }
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "Your selected author options have been saved. It is recommended that you run a small test on the new settings before mass creating posts/users."), 'success', 'Small', __( 'Author Options Saved' ) );    
    }    
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function defaulttagrules () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 

        if( $_POST['generatetags'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['generatetags'] );
            $project_array['tags']['generatetags']['table'] = $exploded[0];
            $project_array['tags']['generatetags']['column'] = $exploded[1];
        }
        
        $project_array['tags']['generatetagsexample'] = $_POST['generatetagsexample'];
        $project_array['tags']['numerictags'] = $_POST['numerictags'];
        $project_array['tags']['tagstringlength'] = $_POST['tagstringlength'];
        $project_array['tags']['maximumtags'] = $_POST['maximumtags'];
        $project_array['tags']['excludedtags'] = $_POST['excludedtags'];

        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true);
        $this->UI->create_notice( __( "You may want to test these settings before mass creating posts and generating a lot of tags."), 'success', 'Small', __( 'Tag Rules Saved' ) );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function defaultpublishdates () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 

        $project_array['dates']['publishdatemethod'] = $_POST['publishdatemethod'];
        
        if( $_POST['datescolumn'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['datescolumn'] );
            $project_array['dates']['table'] = $exploded[0];
            $project_array['dates']['column'] = $exploded[1];
        }
        
        $project_array['dates']['dateformat'] = $_POST['dateformat'];
        $project_array['dates']['incrementalstartdate'] = $_POST['incrementalstartdate'];
        $project_array['dates']['naturalvariationlow'] = $_POST['naturalvariationlow'];
        $project_array['dates']['naturalvariationhigh'] = $_POST['naturalvariationhigh'];
        $project_array['dates']['randomdateearliest'] = $_POST['randomdateearliest'];
        $project_array['dates']['randomdatelatest'] = $_POST['randomdatelatest'];

        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ) );
        $this->UI->create_notice( __( "You m a lot of tags."), 'success', 'Small', __( 'Post Dates Settings Saved' ) );    
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */          
    public function defaulttitletemplate () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings); 
        $project_array['titles']['defaulttitletemplate'] = $_POST['defaulttitletemplate'];
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "Your title template design has been saved to your project settings."), 'success', 'Small', __( 'Title Template Updated' ) );    
    }  

    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */        
    public function defaultcontenttemplate () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_settings_array = maybe_unserialize( $project_array->projectsettings ); 
        
        // store the content as default value
        $project_settings_array['content']['wysiwygdefaultcontent'] = stripslashes_deep( $_POST['wysiwygdefaultcontent'] );

        $this->UI->create_notice( __( 'Your default content template was saved. This is a basic template, other advanced options are available by using CSV 2 POST Templates - a custom post type for managing multiple designs.' ), 'success', 'Small', __( 'Default Content Template Updated' ) );    

        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_settings_array ) ), true);     
    }
        
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */        
    public function newcontenttemplate () {
        global $csv2post_settings;    

        if( !isset( $_POST['contenttemplatetitle2'] ) || empty( $_POST['contenttemplatetitle2'] ) ) {
            $this->UI->create_notice( __( 'Please enter a name for your content template.' ), 'error', 'Small', __( 'Name Required' ) );
            return;      
        }
        
        $post = array(
          'comment_status' => 'closed',
          'ping_status' => 'closed',
          'post_author' => get_current_user_id(),
          'post_content' => stripslashes_deep( $_POST['editorfornewdesign'] ),
          'post_status' => 'publish', 
          'post_title' => $_POST['contenttemplatetitle2'],
          'post_type' => 'wtgcsvcontent'
        );  
                    
        wp_insert_post( $post, true );
        
        $this->UI->create_notice( __( 'A new content template has been created and your projects default content template was also updated.' ), 'success', 'Small', __( 'Content Template Created' ) );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */           
    public function multipledesignsrules () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);
        
        if( $_POST['designrulecolumn1'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['designrulecolumn1'] );    
            $project_array['content']['designrule1']['table'] = $exploded[0];         
            $project_array['content']['designrule1']['column'] = $exploded[1];
            $project_array['content']['designruletrigger1'] = $_POST['designruletrigger1'];
            $project_array['content']['designtemplate1'] = $_POST['designtemplate1'];
        }
        
        if( $_POST['designrulecolumn2'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['designrulecolumn2'] );
            $project_array['content']['designrule2']['table'] = $exploded[0];         
            $project_array['content']['designrule2']['column'] = $exploded[1];
            $project_array['content']['designruletrigger2'] = $_POST['designruletrigger2'];
            $project_array['content']['designtemplate2'] = $_POST['designtemplate2'];
        }
        
        if( $_POST['designrulecolumn3'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['designrulecolumn3'] );
            $project_array['content']['designrule3']['table'] = $exploded[0];         
            $project_array['content']['designrule3']['column'] = $exploded[1];        
            $project_array['content']['designruletrigger3'] = $_POST['designruletrigger3'];
            $project_array['content']['designtemplate3'] = $_POST['designtemplate3'];
        }
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "Design template rules have been updated."), 'success', 'Small', __( 'Content Design Rules Saved' ) );    
    }
    
    /**
    * Force systematic update on all posts for the current project
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function refreshallposts() {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);
        $project_array['lastrefreshtime'] = time();         
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "Please ensure the Systematic Post Updating switch has been set to Enabled. That will allow CSV 2 POST to update posts as they are visited."), 'success', 'Small', __( 'Refresh Initiated', 'csv2post' ) );     
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function defaultposttyperules () {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);
        
        if( $_POST['posttyperule1'] !== 'notrequired' ) {    
            $exploded = explode( '#', $_POST['posttyperule1'] );
            $project_array['posttypes']['posttyperule1']['table'] = $exploded[0];         
            $project_array['posttypes']['posttyperule1']['column'] = $exploded[1];        
            $project_array['posttypes']['posttyperuletrigger1'] = $_POST['posttyperuletrigger1'];
            $project_array['posttypes']['posttyperuleposttype1'] = $_POST['posttyperuleposttype1'];
        }
        
        if( $_POST['posttyperule2'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['posttyperule2'] );
            $project_array['posttypes']['posttyperule2']['table'] = $exploded[0];         
            $project_array['posttypes']['posttyperule2']['column'] = $exploded[1];        
            $project_array['posttypes']['posttyperuletrigger2'] = $_POST['posttyperuletrigger2'];
            $project_array['posttypes']['posttyperuleposttype2'] = $_POST['posttyperuleposttype2'];
        }
        
        if( $_POST['posttyperule3'] !== 'notrequired' ) {
            $exploded = explode( '#', $_POST['posttyperule3'] );
            $project_array['posttypes']['posttyperule3']['table'] = $exploded[0];         
            $project_array['posttypes']['posttyperule3']['column'] = $exploded[1];        
            $project_array['posttypes']['posttyperuletrigger3'] = $_POST['posttyperuletrigger3'];
            $project_array['posttypes']['posttyperuleposttype3'] = $_POST['posttyperuleposttype3'];
        }
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ) );
        $this->UI->create_notice( __( "Your post type rules have been updated."), 'success', 'Small', __( 'Post Type Rules Saved' ) );    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function applydefaultprojectsettings () {
        global $csv2post_settings;
        $this->CSV2POST->apply_project_defaults( $csv2post_settings['currentproject'] );
        $this->UI->create_notice( __( "Your current active projects settings have been set using your configured defaults."), 'success', 'Small', __( 'Project Settings Updated' ) );           
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function resetdefaultprojectsettings () {
        global $csv2post_settings;
        
        // put users stored settings into another variable
        $active_settings = $csv2post_settings;
        
        // include the settings array to get the original array of defaultproject settings
        require_once( WTG_CSV2POST_ABSPATH . 'arrays/settings_array.php' );
        $active_settings['projectdefaults'] = $csv2post_settings['projectdefaults'];
        
        $this->CSV2POST->option( 'csv2post_settings', 'update', $active_settings);
        
        $this->UI->create_notice( __( "Default project settings have been reset to those stored in the settings_array.php file."), 'success', 'Small', __( 'Default Project Settings Reset' ) );           
    } 
    
    /**
    * a default array for projects settings
    * 
    * 1. right now table defaults to project main table as this will be the most common requirement, we'll upgrade this on request
    * 
    */
    public function defaultglobalpostsettings () {
        global $csv2post_settings;
        
        $default_table = $this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] );
        
        $csv2post_settings['projectdefaults'] = array();
        
        // the default settings will go into basicsettings as it is the main and most commonly used array     
        $csv2post_settings['projectdefaults']['basicsettings']['poststatus'] = $_POST['poststatus'];
        $csv2post_settings['projectdefaults']['basicsettings']['pingstatus'] = $_POST['pingstatus'];
        $csv2post_settings['projectdefaults']['basicsettings']['commentstatus'] = $_POST['commentstatus'];
        $csv2post_settings['projectdefaults']['basicsettings']['defaultauthor'] = $_POST['defaultauthor'];
        $csv2post_settings['projectdefaults']['basicsettings']['defaultcategory'] = $_POST['defaultcategory'];
        $csv2post_settings['projectdefaults']['basicsettings']['defaultposttype'] = $_POST['defaultposttype'];
        $csv2post_settings['projectdefaults']['basicsettings']['defaultpostformat'] = $_POST['defaultpostformat'];
        $csv2post_settings['projectdefaults']['basicsettings']['categoryassignment'] = $_POST['categoryassignment'];
        
        // wordpress post settings requiring data - right now only column is entered on UI        
        $csv2post_settings['projectdefaults']['images']['featuredimage']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['images']['featuredimage']['column'] = $_POST['featuredimage'];
        $csv2post_settings['projectdefaults']['permalinks']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['permalinks']['column'] = $_POST['permalink'];
        $csv2post_settings['projectdefaults']['cloaking']['urlcloak1']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['cloaking']['urlcloak1']['column'] = $_POST['urlcloak1'];
        
        // authors
        $csv2post_settings['projectdefaults']['authors']['email']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['authors']['email']['column'] = $_POST['email'];
        $csv2post_settings['projectdefaults']['authors']['username']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['authors']['username']['column'] = $_POST['username'];
                
        // title template
        $csv2post_settings['projectdefaults']['titles']['defaulttitletemplate'] = $_POST['defaulttitletemplate'];
        
        // main content
        $csv2post_settings['projectdefaults']['content']['wysiwygdefaultcontent'] = $_POST['wysiwygdefaultcontent'];
        
        // custom fields specifically for seo plugins
        $csv2post_settings['projectdefaults']['customfields']['seotitletemplate'] = $_POST['seotitletemplate'];        
        $csv2post_settings['projectdefaults']['customfields']['seotitlekey'] = $_POST['seotitlekey']; 
        $csv2post_settings['projectdefaults']['customfields']['seodescriptionkey'] = $_POST['seodescriptionkey'];
        $csv2post_settings['projectdefaults']['customfields']['seodescriptiontemplate'] = $_POST['seodescriptiontemplate'];
        $csv2post_settings['projectdefaults']['customfields']['seokeywordskey'] = $_POST['seokeywordskey'];
        $csv2post_settings['projectdefaults']['customfields']['seokeywordstemplate'] = $_POST['seokeywordstemplate'];
        
        // custom fields
        if( !empty( $_POST['cfkey1'] ) && $_POST['cfkey1'] != '' && !empty( $_POST['cftemplate1'] ) ){
            $csv2post_settings['projectdefaults']['customfields']['cflist'][0]['id'] = 0;// used in WP_Table class to pass what is actually the key, use they key though where unique value is required
            $csv2post_settings['projectdefaults']['customfields']['cflist'][0]['name'] = $_POST['cfkey1'];// the key
            $csv2post_settings['projectdefaults']['customfields']['cflist'][0]['value'] = $_POST['cftemplate1'];// on UI it is a template 
            $csv2post_settings['projectdefaults']['customfields']['cflist'][0]['unique'] = '';
            $csv2post_settings['projectdefaults']['customfields']['cflist'][0]['updating'] = '';
        }
        
        if(!empty( $_POST['cfkey2'] ) && $_POST['cfkey2'] != '' && !empty( $_POST['cftemplate2'] ) ){
            $csv2post_settings['projectdefaults']['customfields']['cflist'][1]['id'] = 1;
            $csv2post_settings['projectdefaults']['customfields']['cflist'][1]['name'] = $_POST['cfkey2'];
            $csv2post_settings['projectdefaults']['customfields']['cflist'][1]['value'] = $_POST['cftemplate2'];
            $csv2post_settings['projectdefaults']['customfields']['cflist'][1]['unique'] = '';
            $csv2post_settings['projectdefaults']['customfields']['cflist'][1]['updating'] = '';
        }
        
        if(!empty( $_POST['cfkey3'] ) && $_POST['cfkey3'] != '' && !empty( $_POST['cftemplate3'] ) ){
            $csv2post_settings['projectdefaults']['customfields']['cflist'][2]['id'] = 2;
            $csv2post_settings['projectdefaults']['customfields']['cflist'][2]['name'] = $_POST['cfkey3'];
            $csv2post_settings['projectdefaults']['customfields']['cflist'][2]['value'] = $_POST['cftemplate3'];
            $csv2post_settings['projectdefaults']['customfields']['cflist'][2]['unique'] = '';
            $csv2post_settings['projectdefaults']['customfields']['cflist'][2]['updating'] = '';
        }
        
        // template design rules
        $csv2post_settings['projectdefaults']['content']['designrule1']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['content']['designrule1']['column'] = $_POST['designrule1'];
        $csv2post_settings['projectdefaults']['content']['designruletrigger1'] = $_POST['designruletrigger1'];
        $csv2post_settings['projectdefaults']['content']['designtemplate1'] = $_POST['designtemplate1'];
        $csv2post_settings['projectdefaults']['content']['designrule2']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['content']['designrule2']['column'] = $_POST['designrule2'];
        $csv2post_settings['projectdefaults']['content']['designruletrigger2'] = $_POST['designruletrigger2'];
        $csv2post_settings['projectdefaults']['content']['designtemplate2'] = $_POST['designtemplate2'];
        $csv2post_settings['projectdefaults']['content']['designrule3']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['content']['designrule3']['column'] = $_POST['designrule3'];
        $csv2post_settings['projectdefaults']['content']['designruletrigger3'] = $_POST['designruletrigger3'];
        $csv2post_settings['projectdefaults']['content']['designtemplate3'] = $_POST['designtemplate3'];

        // images and media requireing token or shortcode to be entered to post content
        $csv2post_settings['projectdefaults']['content']['enablegroupedimageimport'] = $_POST['enablegroupedimageimport'];
        $csv2post_settings['projectdefaults']['content']["localimages"]['table'] = $default_table;
        $csv2post_settings['projectdefaults']['content']["localimages"]['column'] = $_POST['localimagesdatacolumn'];
        $csv2post_settings['projectdefaults']['content']['incrementalimages'] = $_POST['incrementalimages'];
        $csv2post_settings['projectdefaults']['content']['groupedimagesdir'] = 
        // pre-set level one category
        $csv2post_settings['projectdefaults']['categories']['presetcategoryid'] = false;// change to a category ID
        
        // category columns
        if(!empty( $_POST['categorylevel1'] ) ){
            $csv2post_settings['projectdefaults']['categories']['data'][0]['table'] = $default_table;
            $csv2post_settings['projectdefaults']['categories']['data'][0]['column'] = $_POST['categorylevel1'];
        
            if(!empty( $_POST['categorylevel2'] ) ){
                $csv2post_settings['projectdefaults']['categories']['data'][1]['table'] = $default_table;
                $csv2post_settings['projectdefaults']['categories']['data'][1]['column'] = $_POST['categorylevel2'];
            
                if(!empty( $_POST['categorylevel3'] ) ){
                    $csv2post_settings['projectdefaults']['categories']['data'][2]['table'] = $default_table;
                    $csv2post_settings['projectdefaults']['categories']['data'][2]['column'] = $_POST['categorylevel3'];
                
                    if(!empty( $_POST['categorylevel4'] ) ){
                        $csv2post_settings['projectdefaults']['categories']['data'][3]['table'] = $default_table;
                        $csv2post_settings['projectdefaults']['categories']['data'][3]['column'] = $_POST['categorylevel4'];
                        
                        if(!empty( $_POST['categorylevel5'] ) ){
                            $csv2post_settings['projectdefaults']['categories']['data'][4]['table'] = $default_table;
                            $csv2post_settings['projectdefaults']['categories']['data'][4]['column'] = $_POST['categorylevel5'];
                        }                    
                    }                                
                }          
            }        
        }
                       
        // category descriptions
        for( $i=0;$i<=4;$i++){                      
            $csv2post_settings['projectdefaults']['categories']["descriptiondata"][$i]['table'] = $default_table;
            $csv2post_settings['projectdefaults']['categories']["descriptiondata"][$i]['column'] = $_POST["categorydescription$i"];
            $csv2post_settings['projectdefaults']['categories']["descriptiontemplates"][$i] = $_POST["categorydescriptiontemplate$i"];
        }
        
        // adoption
        $csv2post_settings['projectdefaults']['adoption']['adoptionmethod'] = $_POST['adoptionmethod'];
        $csv2post_settings['projectdefaults']['adoption']['allowedchanges'] = $_POST['allowedchanges'];
        $csv2post_settings['projectdefaults']['adoption']['adoptionmetakey'] = $_POST['adoptionmetakey'];
        $csv2post_settings['projectdefaults']['adoption']['adoptionmeta']['table'] = $default_table;
        $csv2post_settings['projectdefaults']['adoption']['adoptionmeta']['column'] = $_POST['adoptionmeta'];
        
        // tags
        $csv2post_settings['projectdefaults']['tags']['column'] = $_POST['tags'];// ready made tags
        $csv2post_settings['projectdefaults']['tags']['textdata']['table'] = $default_table;// generate tags from text
        $csv2post_settings['projectdefaults']['tags']['textdata']['column'] = $_POST['textdata'];// generate tags from text
        $csv2post_settings['projectdefaults']['tags']['defaultnumerics'] = $_POST['defaultnumerics'];
        $csv2post_settings['projectdefaults']['tags']['tagstringlength'] = $_POST['tagstringlength'];
        $csv2post_settings['projectdefaults']['tags']['maximumtags'] = $_POST['maximumtags'];
        $csv2post_settings['projectdefaults']['tags']['excludedtags'] = $_POST['excludedtags'];
        
        // post type rules
        for( $i=1;$i<=3;$i++){
            $csv2post_settings['projectdefaults']['posttypes']["posttyperule$i"]['table'] = $default_table;
            $csv2post_settings['projectdefaults']['posttypes']["posttyperule$i"]['column'] = $_POST["posttyperule$i"];
            $csv2post_settings['projectdefaults']['posttypes']["posttyperuletrigger$i"] = $_POST["posttyperuletrigger$i"];
            $csv2post_settings['projectdefaults']['posttypes']["posttyperuleposttype$i"] = $_POST["posttyperuleposttype$i"];
        }
        
        // publish dates
        $csv2post_settings['projectdefaults']['dates']['publishdatemethod'] = $_POST['publishdatemethod'];
        $csv2post_settings['projectdefaults']['dates']['column'] = $_POST['dates'];
        $csv2post_settings['projectdefaults']['dates']['dateformat'] = $_POST['dateformat'];
        $csv2post_settings['projectdefaults']['dates']['incrementalstartdate'] = $_POST['incrementalstartdate'];
        $csv2post_settings['projectdefaults']['dates']['naturalvariationlow'] = $_POST['naturalvariationlow'];
        $csv2post_settings['projectdefaults']['dates']['naturalvariationhigh'] = $_POST['naturalvariationhigh'];
        $csv2post_settings['projectdefaults']['dates']['randomdateearliest'] = $_POST['randomdateearliest'];
        $csv2post_settings['projectdefaults']['dates']['randomdatelatest'] = $_POST['randomdatelatest'];
        
        // post updating
        $csv2post_settings['projectdefaults']['updating']['updatepostonviewing'] = $_POST['updatepostonviewing'];     
        
        $this->CSV2POST->update_settings( $csv2post_settings );
        $this->UI->create_notice( __( 'Your default project settings have been stored. Many of the setting will only work with multiple different
        projects if each projects datasource has the same format/configuration. Please configure projects individually where required and do not
        rely on these defaults if working with various different sources.', 'csv2post' ), 'success', 'Small', __( 'Default Project Options Stored Successfully', 'csv2post' ) );
    }
    
    /**
    * creates posts manually from tools with manual insert of parameters
    */
    public function createpostsbasic() {
        global $csv2post_settings;
        
        // set total posts to be created
        $total = 1;
        
        if( isset( $_POST['totalposts'] ) && is_numeric( $_POST['totalposts'] ) ){
            $total = $_POST['totalposts'];
        }        
                 
        $this->CSV2POST->create_posts( $csv2post_settings['currentproject'], $total ); 
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'projectchange',
            'action' => sprintf( __( 'User requested %s posts to be created.', 'csv2post' ), $total ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                 
        $this->UI->create_notice( __( 'Post creation procedure has finished. This notice marks the end of the procedure whatever the outcome.', 'csv2post' ),  'success', 'Small', __( 'Post Creation Ended', 'csv2post' ) );   
    }
    
    /**
    * creates posts manually from quick actions and uses pre-set parameters
    */    
    public function createpostscurrentproject() {
        global $csv2post_settings;

        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'projectchange',
            'action' => sprintf( __( 'User requested %s posts to be made.', 'csv2post' ), $total),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                
        $this->CSV2POST->create_posts( $csv2post_settings['currentproject'],1);    
    } 
    
    /**
    * updates posts manually from tools with manual insert of parameters
    */
    public function updatepostsbasic() {
        global $csv2post_settings; 
           
        // set total posts to be created
        $total = 1;
        
        if( isset( $_POST['totalposts'] ) && is_numeric( $_POST['totalposts'] ) ){
            $total = $_POST['totalposts'];
        }
        
        $this->CSV2POST->update_posts( $csv2post_settings['currentproject'], $total);    
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function updatepostsbasicnewdataonly() {
        global $csv2post_settings;
        
        // set total posts to be created
        $total = 1;
        if( isset( $_POST['totalposts'] ) && is_numeric( $_POST['totalposts'] ) ){$total = $_POST['totalposts'];}
            
        // query for rows of data that have been updated
        $rows = $this->CSV2POST->get_updated_rows( $csv2post_settings['currentproject'], $total, $this->CSV2POST->get_project_idcolumn( $csv2post_settings['currentproject'] ) );
        if(!$rows){
            $this->UI->create_notice( __( 'None of your imported rows have been updated since their original import.' ), 'info', 'Small', 'No Posts Updated' );
            return;            
        }
     
        $this->CSV2POST->update_posts( $csv2post_settings['currentproject'], $total, false, array( 'rows' => $rows) );    
    }   
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */        
    public function updatepostsbasicprojectchangesonly() {
        global $csv2post_settings;
                                                  
        // set total posts to be created
        $total = 1;
        if( isset( $_POST['totalposts'] ) && is_numeric( $_POST['totalposts'] ) ){$total = $_POST['totalposts'];}

        // query for rows that have not been applied since the project configuration changed
        $rows = $this->CSV2POST->get_outdatedpost_rows( $csv2post_settings['currentproject'], $total);
        
        if(!$rows){        
            $this->UI->create_notice( __( 'No posts appear to need updating. All applied dates are later than when you changed your projects settings.' ), 'info', 'Small', 'No Posts Require Updating' );
            return;            
        }
                  
        $this->CSV2POST->update_posts( $csv2post_settings['currentproject'], $total, false, array( 'rows' => $rows) );    
    }  
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */         
    public function updatespecificpost() {
        global $csv2post_settings;
        
        // set total posts to be created
        $total = 1;
        if( empty( $_POST['updatespecificpostid'] ) ){
            $this->UI->create_notice( __( 'A post ID is required. Please enter a numeric value then submit the form again.' ), 'error', 'Small', __( 'Post ID Required' ) );
            return;
        } 
        
        if( !is_numeric( $_POST['updatespecificpostid'] ) ){
            $this->UI->create_notice( __( 'You have not entered a numeric value for your post ID.' ), 'error', 'Small', __( 'Numeric Post ID Required' ) );           
            return;    
        }                                   

        $this->CSV2POST->update_posts( $csv2post_settings['currentproject'], $total, $_POST['updatespecificpostid'] ); 
         
        $this->UI->create_notice( __( 'Your update request for post with ID '. $_POST['updatespecificpostid'] .' has finished. The results should be displayed in another notice.' ), 'success', 'Small', __( 'Post Update Request Complete' ) );            
    }
    
    /**
    * updates posts manually from quick actions and uses pre-set parameters
    */    
    public function updatepostscurrentproject() {
        global $csv2post_settings;
        $this->CSV2POST->update_posts( $csv2post_settings['currentproject'],1);    
    }
    
    /**
    * processes submitted string for nested spinner and outputs   
    */
    public function spintaxtest() {
        if(!isset( $_POST['spintaxteststring'] ) || empty( $_POST['spintaxteststring'] ) ){
            $this->UI->create_notice( 'No value was submitted. Please paste or type some text with bracket/nested spintax included.', 'info', 'Small', 'No Value Submitted' );
            return;
        }
        
        $result = $this->CSV2POST->spin( $_POST['spintaxteststring'] );
        
        $this->UI->create_notice( $result, 'success', 'Extra', 'Spintax Test Result' );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function groupimportlocalimages () {  
        if(!isset( $_POST['groupimportdir'] ) || empty( $_POST['groupimportdir'] ) ){
            $this->UI->create_notice( __( 'A path to your image directory is required.' ), 'error', 'Small', __( 'Image Directory Required' ) );
            return;
        }
                      
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);

        $project_array['content']['enablegroupedimageimport'] = $_POST['enablegroupedimageimport'];
        
        $exploded = explode( '#', $_POST['localimagesdata'] );
        
        // these values are for unique values making up part or all of file names, not for data with paths
        $project_array['content']["localimages"]['table'] = $exploded[0];
        $project_array['content']["localimages"]['column'] = $exploded[1]; 
        $project_array['content']["incrementalimages"] = $_POST['incrementalimages'];
        $project_array['content']["groupedimagesdir"] = $_POST['groupimportdir'];
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true);
        $this->UI->create_notice( __( "Images will be imported during post creation to the WordPress media library and inserted to
        content as a list if you are using the #localimagelist# token."), 'success', 'Small', __( 'Grouped Image Import Settings Saved' ) );                        
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function reinstalldatabasetables() {
        $installation = new C2P_Install();
        $installation->reinstalldatabasetables();
        $this->UI->create_notice( 'All tables were re-installed. Please double check the database status list to ensure this is correct before using the plugin.', 'success', 'Small', 'Tables Re-Installed' );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.0
    */       
    public function presetlevelonecategory() {           
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);
            
        if(!is_numeric( $_POST['presetcategoryid'] ) ){
            $this->UI->create_notice( 'You did not enter a valid category ID also known as a slug ID.', 'error', 'Small', 'No Category ID' );
            return;    
        }
        
        $project_array['categories']['presetcategoryid'] = $_POST['presetcategoryid'];
        
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( "You have setup a pre-set level one category. Any categories CSV 2 POST creates will be level two
        or lower and will be children of the parent with ID " . $project_array['categories']['presetcategoryid'] ), 'success', 'Small', __( 'Pre-Set Level One Category' ) );   
    }  
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */          
    public function globalswitches() {
        global $csv2post_settings;
        $csv2post_settings['noticesettings']['wpcorestyle'] = $_POST['uinoticestyle'];        
        $csv2post_settings['standardsettings']['textspinrespinning'] = $_POST['textspinrespinning'];
        $csv2post_settings['standardsettings']['systematicpostupdating'] = $_POST['systematicpostupdating'];
        $csv2post_settings['flagsystem']['status'] = $_POST['flagsystemstatus'];
        $csv2post_settings['widgetsettings']['dashboardwidgetsswitch'] = $_POST['dashboardwidgetsswitch'];
        $this->CSV2POST->update_settings( $csv2post_settings ); 
        $this->UI->create_notice( __( 'Global switches have been updated. These switches can initiate the use of 
        advanced systems. Please monitor your blog and ensure the plugin operates as you expected it to. If
        anything does not appear to work in the way you require please let WebTechGlobal know.' ),
        'success', 'Small', __( 'Global Switches Updated' ) );       
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */         
    public function replacevaluerules() {
        global $csv2post_settings;    
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );      
        $project_array = maybe_unserialize( $project_array->projectsettings);

        // set next array key value
        $next_key = 0;
                 
        // determine next array key
        if( isset( $project_array['content']['valuerules'] ) ){    
            $next_key = $this->CSV2POST->get_array_nextkey( $project_array['content']['valuerules'] );
        }   
        
        $exploded = explode( '#', $_POST['vrvdata'] ); 
        $project_array['content']['valuerules'][$next_key]['id'] = $next_key;       
        $project_array['content']['valuerules'][$next_key]['table'] = $exploded[0];
        $project_array['content']['valuerules'][$next_key]['column'] = $exploded[1];
        $project_array['content']['valuerules'][$next_key]['vrvdatavalue'] = $_POST['vrvdatavalue'];
        $project_array['content']['valuerules'][$next_key]['vrvreplacementvalue'] = $_POST['vrvreplacementvalue'];
  
        $this->CSV2POST->update_project( $csv2post_settings['currentproject'], array( 'projectsettings' => maybe_serialize( $project_array ) ), true );
        $this->UI->create_notice( __( 'A new value replacement rule has been stored. Please test your new rule.' ), 'success', 'Small',
        __( 'Replace Value Rule Created' ) );   
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function displayprojectsummary() {
        global $csv2post_settings;
        $message = '';
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        $project_settings = maybe_unserialize( $project_array->projectsettings);

        $message .= '<table class="form-table">';        
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">ID</th>';
        $message .= '<td>'.$project_array->projectid.'</td>';
        $message .= '</tr>';
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Project Name</th>';
        $message .= '<td>'.$project_array->projectname.'</td>';
        $message .= '</tr>';        
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Timestamp</th>';
        $message .= '<td>'.$project_array->timestamp.'</td>';
        $message .= '</tr>';        
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Source 1 ID</th>';
        $message .= '<td>'.$project_array->source1.'</td>';
        $message .= '</tr>';        
        
        if( isset( $project_array->source2) && $project_array->source2 != 0){
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source 2 ID</th>';
            $message .= '<td>'.$project_array->source2.'</td>';
            $message .= '</tr>';        
        }
        
        if( isset( $project_array->source3) && $project_array->source3 != 0){
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source 3 ID</th>';
            $message .= '<td>'.$project_array->source3.'</td>';
            $message .= '</tr>'; 
        }       
        
        if( isset( $project_array->source4) && $project_array->source4 != 0){
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source 4 ID</th>';
            $message .= '<td>'.$project_array->source4.'</td>';
            $message .= '</tr>';
        }
        
        if( isset( $project_array->source5) && $project_array->source5 != 0){
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source 5 ID</th>';
            $message .= '<td>'.$project_array->source5.'</td>';
            $message .= '</tr>';
        }

        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Lock Content</th>';
        $message .= '<td>'.$project_array->lockcontent.'</td>';
        $message .= '</tr>';
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Lock Meta</th>';
        $message .= '<td>'.$project_array->lockmeta.'</td>';
        $message .= '</tr>';
        
        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Data Treatment</th>';
        $message .= '<td>'.$project_array->datatreatment.'</td>';
        $message .= '</tr>'; 

        $message .= '<tr valign="top">';
        $message .= '<th scope="row">Settings Changed</th>';
        $message .= '<td>'.$project_array->settingschange.'</td>';
        $message .= '</tr>';
                                        
        $message .= '</table>';
  
        $this->UI->create_notice( $message, 'info', 'Extra', 'Project Summary', false, 'http://www.webtechglobal.co.uk/wordpress/csv-2-post/project-summary-explained/' );
    } 
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */         
    public function displaysourcesummary() {
        global $csv2post_settings;
        $project_array = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );
        $source = $this->CSV2POST->get_source( $project_array->source1 ); 
        $config = maybe_unserialize( $source->theconfig );
        if( $source ){
            $message = '';
            $message .= '<table class="form-table">';

            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source ID</th>';
            $message .= '<td>'.$source->sourceid.'</td>';
            $message .= '</tr>';  
      
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Project ID</th>';
            $message .= '<td>'.$source->projectid.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Submitted Path</th>';
            $message .= '<td>'.$config['submittedpath'].'</td>';  
            $message .= '</tr>';        
           
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Full Path</th>';
            $message .= '<td>'.$config['fullpath'].'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Separator</th>';
            $message .= '<td>'.$config['sep'].'</td>';
            $message .= '</tr>';  
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Fields</th>';
            $message .= '<td>'.$config['fields'].'</td>';
            $message .= '</tr>';              
                    
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Source Type</th>';
            $message .= '<td>'.$source->sourcetype.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Progress</th>';
            $message .= '<td>'.$source->progress.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Timestamp</th>';
            $message .= '<td>'.$source->timestamp.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Path</th>';
            $message .= '<td>'.$source->path.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Key Column</th>';
            $message .= '<td>'.$source->idcolumn.'</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Monitor Change</th>';
            $message .= '<td>' . $source->monitorfilechange . '</td>';
            $message .= '</tr>';      
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Change Counter</th>';
            $message .= '<td>' . $source->changecounter . '</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Data Treatment</th>';
            $message .= '<td>' . $source->datatreatment . '</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Parent File ID</th>';
            $message .= '<td>' . $source->parentfileid . '</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Database Table</th>';
            $message .= '<td>' . $source->tablename . '</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Rules</th>';
            $message .= '<td>' . $source->rules . '</td>';
            $message .= '</tr>';
            
            $message .= '<tr valign="top">';
            $message .= '<th scope="row">Separator</th>';
            $message .= '<td>' . $source->thesep . '</td>';
            $message .= '</tr>';

            $message .= '</table>';            
            $this->UI->create_notice( $message, 'info', 'Extra', 'Data Source Summary', false, 'http://www.webtechglobal.co.uk/wordpress/csv-2-post/data-source-summary/' );
        }else{
             $this->UI->create_notice( __( 'Data source entry does not exist. If the data source exist i.e. database table
             then it will need to be entered into the sources table so CSV 2 POST can monitor it.' ), 'info', 'Extra', __( 'Data Source Entry Not Found' ) );
        }
    }
    /**
    * used by queryduplicateposts to deletion duplicates
    * 
    * expects a specific ARRAY_A $result which includes a string of ids created
    * by a GROUP_CONCAT query
    * 
    * @param mixed $result
    */
    static function deleteduplicateposts( $result ){
        $total_posts_deleted = 0;
        // loop through the posts that have duplicates
        foreach( $result as $key => $dup){
            // explode the returned post ID's (avoid deleting the first, that is the one we will keep)
            $dup_ids_array = explode( ', ', $dup['ids'] );
            
            $first = true;
            
            // loop through duplicate post ID's
            foreach( $dup_ids_array as $key => $postid){
                if( $key !== 0){
                    $forcedelete = false;
                    if( isset( $_POST['forcedelete'] ) ){$forcedelete = true;}
                    wp_delete_post( $postid, $forcedelete );
                    ++$total_posts_deleted;
                }
            }
        }  
        return $total_posts_deleted;  
    } 
    
    /**
    * deletes duplicate posts
    */
    public function queryduplicateposts () {
        global $wpdb;

        $total_posts_deleted = 0;
        
        // get one of two ID's where post titles are perfect match
        $result = $wpdb->get_results( 'SELECT COUNT(*) as cnt, GROUP_CONCAT(ID) AS ids
                                        FROM '.$wpdb->posts.'
                                        WHERE post_status != "inherit"
                                        AND post_status != "trash"
                                        GROUP BY post_title
                                        HAVING cnt > 1',ARRAY_A );
        if( $result){
            $deleted_count = $this->deleteduplicateposts( $result);
            $total_posts_deleted = $total_posts_deleted + $deleted_count;
        }
        
        // get one of two ID's where post content is perfect match  
        /*                        
        $result = $wpdb->get_results( 'SELECT COUNT(*) as c, GROUP_CONCAT(ID) AS ids
                                        FROM wp_posts
                                        GROUP BY post_content
                                        HAVING c > 1' );
                                        
        if( $result){
            echo 'Second method found duplicates <br> ';
        }  */

        // delete post tat share the same c2p_rowid value
        if( isset( $_POST['safetycodeconfirmed'] ) && $_POST['safetycodeconfirmed'] === $_POST['deleteduplicatepostssafetycode'] ){
                                   
            // get one of two ID's where                                 
            $result = $wpdb->get_results( 'SELECT COUNT(*) as cnt, GROUP_CONCAT(post_id) AS ids
                                            FROM '.$wpdb->postmeta.'
                                            WHERE meta_key = "c2p_rowid"
                                            GROUP BY meta_key,meta_value
                                            HAVING cnt > 1',ARRAY_A );

            if( $result){
                $deleted_count = $this->deleteduplicateposts( $result);
                $total_posts_deleted = $total_posts_deleted + $deleted_count;
            }
        }
                  
        if( $total_posts_deleted == 0){
            $this->UI->create_notice( __( 'No duplicate posts were found, no changes were made to your blog.' ), 'info', 'Small', __( 'No Duplicates' ) );
        }else{
            $this->UI->create_notice( "A total of $total_posts_deleted posts were deleted.", 'success', 'Small', 'Duplicate Posts Deleted' );
        }
    }  
     
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */         
    public function undoprojectposts() {
        global $csv2post_settings, $wpdb;
        if(!current_user_can( 'delete_posts' ) ){
            $this->UI->create_notice( __( 'You do not have permission to delete posts.' ), 'error', 'Small', __( 'Not Permitted' ) );
            return;
        }
        if(!isset( $_POST['undoallpostsconfirm'] ) || $_POST['undoallpostsconfirm'] !== $_POST['undoallpostssafecode'] ){
            $this->UI->create_notice( __( 'You must enter the safety code and confirm you really want to delete all of the current projects posts.' ), 'error', 'Small', __( 'Not Permitted' ) );
            return;        
        }
        
        $limit = 30;
        if( isset( $_POST['undopostslimit'] ) && is_numeric( $_POST['undopostslimit'] ) ){$limit = $_POST['undopostslimit'];}
        
        $result = $wpdb->get_results( 'SELECT post_id
        FROM '.$wpdb->postmeta.' 
        WHERE meta_key = "c2p_project" 
        AND meta_value = "'.$csv2post_settings['currentproject'].'"
        LIMIT ' . $limit,ARRAY_A );
         
        if(!$result){
            $this->UI->create_notice( __( 'No posts were found for the current project. Are you sure you created some?' ), 'info', 'Small', __( 'No Posts Deleted' ) );
        }else{
            $posts_deleted = 0;
            foreach( $result as $key => $postid){
                $forcedelete = false;
                if( isset( $_POST['forcedelete'] ) ){$forcedelete = true;}
                wp_delete_post( $postid['post_id'], $forcedelete );
                $wpdb->query( 'UPDATE `'.$this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] ).'` SET `c2p_postid` = 0 WHERE `c2p_postid` = ' . $postid['post_id'] );
                ++$posts_deleted;
            }
            $this->UI->create_notice( __( "A total of $posts_deleted posts were deleted. All
            post ID have been removed from the projects database table so that you can re-create the posts."), 'success', 'Small', __( 'Posts Deleted' ) );
        }
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'projectchange',
            'action' => sprintf( __( 'User requested %s posts to be deleted.', 'csv2post' ), $limit ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
    }    
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function resetimportedrows() {
        global $wpdb, $csv2post_settings;
        $result = $wpdb->query( 'UPDATE `'.$this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] ).'` SET `c2p_postid` = 0' );        
        
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'dataevent',
            'action' => sprintf( __( 'Imported data reset for project ID %s and ready to use again.', 'csv2post' ), $csv2post_settings['currentproject'] ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest',
            'dump' => $result,
        );
        
        $this->CSV2POST->newlog( $atts );
                
        $this->UI->create_notice( "The plugin removed all post ID's from a total of $result rows in your imported data for the current project.", 'success', 'Small', __( 'Result Rows Results' ) );
    }
    
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */       
    public function newprojectusingexistingsource() {
        $sourceid_array = array();// increment string key: source1,source2,source3 (not sure why I done this but stick with it for now)
        
        // set project name - default to the date if user did not enter a name
        if( empty( $_POST['newprojectname'] ) ){
            $project_name = CSV2POST::datewp();
        }else{
            $project_name = sanitize_text_field( $_POST['newprojectname'] );
        } 

        if( !isset( $_POST['newprojectdatasource'] ) || !is_numeric( $_POST['newprojectdatasource'] ) ) {
            $this->UI->create_notice( __( 'The source ID submitted is invalid and your request to create a new project was denied.' ), 'error', 'Small', __( 'Invalid Source ID' ) );                    
            return false;                
        }
        
        $sourceid_array['source1'] = $_POST['newprojectdatasource'];
        
        // create a new project in the c2pprojects table
        $projectid = $this->CSV2POST->insert_project( $project_name, $sourceid_array ); 

        // ensure we have valid $projectid and set it as the current project
        if( !is_numeric( $projectid ) ){
            $this->UI->create_notice( __( 'The plugin could not finish inserting your new project to the database. This should never happen, please report it.' ), 'error', 'Small', __( 'Problem Detected When Creating Project' ) );                    
            return false;
        } 
         
        // if applicable we apply defaults to the projects settings array, initialize it with coded settings or users own defaults
        if( isset( $_POST['applydefaults'] ) && $_POST['applydefaults'] == 'enabled' ) 
        {
            $this->CSV2POST->apply_project_defaults( $projectid );
        }
        
        // from here on we can begin using the $project_array
        $project_array = $this->CSV2POST->get_project( $projectid );      
        $project_settings_array = maybe_unserialize( $project_array->projectsettings );
        
        // add the id column to the project settings array
        // this helps to avoid calling entire source record in some situations when querying imported data
        // get the source data
        $source_array = $this->CSV2POST->get_source( $sourceid_array['source1'] );                 
        $project_settings_array['idcolumn'] = $source_array->idcolumn;
        
        $this->CSV2POST->update_project( $projectid, array( 'projectsettings' => maybe_serialize( $project_settings_array ) ), false );

        // set the current project
        global $csv2post_settings;
        $csv2post_settings['currentproject'] = $projectid;
        $this->CSV2POST->update_settings( $csv2post_settings );

        // update source rows with project id
        // this is not required, I intend for multiple projects to use a single source, but in a simple setup
        // where the user isnt doing anything advanced, this measure may help
        $this->CSV2POST->update_sources_withprojects( $projectid, $sourceid_array );
            
        $this->UI->create_notice( 'Your project has been created and the ID is ' . $projectid . '. The default project name is ' . $project_name . ' which you can change using project settings.', 'success', 'Small', __( 'Project Created' ) );                    

        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'projectchange',
            'action' => sprintf( __( 'New project created with ID %s.', 'csv2post' ), $projectid ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );    
    }  

    public function a_table_was_created( $table_name){
        $this->UI->create_notice( 'CSV 2 POST created a new database table named ' . $table_name .'. Your data will be imported into this table where it can be prepared (optional) then used to create posts.', 'success', 'Small', 'Table Created Named ' . $table_name);
    }
              
    /**
    * form processing function
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.0.0
    * @version 1.1
    */            
    public function changecsvfilepath() {
        global $csv2post_settings, $wpdb;
        if(!isset( $_POST['newpath'] ) || empty( $_POST['newpath'] ) ){
            $this->UI->create_notice( __( 'Please enter the path to the .csv file that is replacing your sources existing file.' ), 'error', 'Small', __( 'New File Path Required' ) );
            return;
        }    
        
        // check if file exists
        $file_one_exists = file_exists( ABSPATH . $_POST['newpath'] );
        if(!$file_one_exists){
            $this->UI->create_notice( __( "The new .csv file could not be located or its permissions do not all access. Your source has not been changed."), 'error', 'Small', __( 'File Not Found' ) );    
            return;
        }
        
        // ensure is .csv file
        if( pathinfo( ABSPATH . $_POST['newpath'], PATHINFO_EXTENSION) != 'csv' ){
            $this->UI->create_notice( __( "The submitted path does not point to a .csv file. This plugin only works with CSV files. Please see the WebTechGlobal plugin range for one that handles other file types and types of data storage."), 'error', 'Small', __( 'CSV File Required' ) );
            return;
        }  
        
        // get the source data
        $source_array = $this->CSV2POST->get_source( $_POST['datasource'] );
        
        // has the source row been deleted
        if( $source_array ){
            $this->UI->create_notice( __( 'No source row could be found in the sources table. This can happen if you have deleted the associated project.', 'csv2post' ), 'success', 'Small', __( 'Source Missing', 'csv2post' ) );
            return;            
        }        
        
        // apply the new path (updates the "path" column and updates the config array)
        $this->CSV2POST->update_source_path( ABSPATH . $_POST['newpath'], $_POST['datasource'] );                              
    
        $this->UI->create_notice( __( 'The request has been complete and the source record has been updated. Any projects using the
        source will now also use the new .csv file.' ), 'success', 'Small', 'Source Path Updated' );    
    }
    
    /**
    * mass update posts for current project with new categories array
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function setpostscategories() {
        global $csv2post_settings;
       
        // load categories class
        $this->CSV2POST->load_class( 'C2P_Categories', 'class-categories.php', 'classes',array( 'noreturn' ) );
        $CSV2POST_Categories = new C2P_Categories();
        
        // establish if pre-set parent in use
        $preset_parent = false;
        if( isset(  $this->current_project_settings['categories']['presetcategoryid'] ) ){
            $preset_parent = $this->current_project_settings['categories']['presetcategoryid'];
        }

        // get rows used to create posts, this function will add post ID's as array key for use in mass_update_posts_categories() 
        $used_category_data = $this->CSV2POST->get_category_data_used( $csv2post_settings['currentproject'], 5, true );   
               
        // run a posts category update, it includes creating categories to apply any 
        // changes and updating posts to reflect any category changes
        $CSV2POST_Categories->mass_update_posts_categories( $used_category_data, $preset_parent );
    }

    /**
    * mass removal the relationships between posts and categories
    * this should be done before mass delete to split the operation into phases
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function resetpostscategories() {
        
    }
    
    /**
    * using an array of keys that exist twice or more, will delete the extra rows
    * and also delete posts if posts have been created using any of the extra rows
    * 
    * @returns false if no rows deleted else returns total rows deleted
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.1
    */
    public function deleteduplicaterowsandposts() {
        global $csv2post_settings;

        // if no idcolumn set this operation can be executed
        if( !isset( $this->current_project_settings['idcolumn'] ) ){
            $this->UI->create_notice( __( 'No ID column was found. It means you are not applying any strict validation that
            requires a key column of unique/distinct values per row. This operation you have requested is for deleting
            duplicates based on that type of rule.', 'csv2post' ), 'warning', 'Small', __( 'No ID Column', 'csv2post' ) );     
            return false;  
        }                                                                                                           
        
        $table_name = $this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] );
        
        // get an array of the keys which have duplicates (not every duplicate just an array of keys that have 2 or more)
        $duplicate_keys = $this->DB->get_duplicate_keys( $table_name, $this->current_project_settings['idcolumn'] );
        
        $orderby = $this->current_project_settings['idcolumn'];
        
        $select = 'c2p_rowid, c2p_postid, ' . $this->current_project_settings['idcolumn'];

        // count number of rows sharing the same post_id so that we can avoid deleting that post
        $rows_sharing_posts = 0;
        
        // count deletions
        $rows_deleted = 0;
        
        // count post deletion
        $posts_deleted = 0;
            
        // loop through doubled up key rows
        foreach( $duplicate_keys as $array_key => $data_key ) {
            
            $condition = $this->current_project_settings['idcolumn'] . ' = ' . $data_key;
             
            // get all rows with the current $data_key
            $result = $this->DB->selectwherearray( $table_name, $condition, $orderby, $select, ARRAY_A );
             
            // store the first post id to avoid deleting it, rows may be sharing the same post
            $first_post_id = false;
            
            // count which row we are on, avoid deleting the first
            $row_count = 0;
            
            // loop through the rows  
            if( $result ){
                
                foreach( $result as $key => $row ){
                    
                    // if first post and it has a post id then set the $first_post_id to prevent its deletion
                    // if another row also has the same post id
                    if( $row_count === 0 && isset( $row['c2p_postid'] ) && is_numeric( $row['c2p_postid'] ) ) {
                        
                        $first_post_id = $row['c2p_postid'];
 
                    } 
                    
                    // delete all rows after the first
                    if( $row_count > 0 ) {
                        
                        $this->DB->delete( $table_name, 'c2p_rowid = ' . $row['c2p_rowid'] );
                        
                        ++$rows_deleted;  
                        
                        // delete post 
                        if( isset( $row['c2p_postid'] ) && $row['c2p_postid'] !== $first_post_id ) {
                            
                            wp_delete_post( $row['c2p_postid'] );
                            
                            ++$posts_deleted;
                            
                        }  
                    }                   
                    
                    ++$row_count;
                }
            }     
        }
        
        $this->UI->create_notice( "A total of $posts_deleted posts were deleted and $rows_deleted imported rows were delete.", 'success', 'Small', __( 'Duplicate Deletion Finished', 'csv2post' ) );
    }
     
    /**
    * save capability settings for plugins pages
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.1
    */
    public function pagecapabilitysettings() {
        
        // get the capabilities array from WP core
        $capabilities_array = $this->WPCore->capabilities();

        // get stored capability settings 
        $saved_capability_array = get_option( 'csv2post_capabilities' );
        
        // get the tab menu 
        $pluginmenu = $this->TabMenu->menu_array();
                
        // to ensure no extra values are stored (more menus added to source) loop through page array
        foreach( $pluginmenu as $key => $page_array ) {
            
            // ensure $_POST value is also in the capabilities array to ensure user has not hacked form, adding their own capabilities
            if( isset( $_POST['pagecap' . $page_array['name'] ] ) && in_array( $_POST['pagecap' . $page_array['name'] ], $capabilities_array ) ) {
                $saved_capability_array['pagecaps'][ $page_array['name'] ] = $_POST['pagecap' . $page_array['name'] ];
            }
                
        }
          
        update_option( 'csv2post_capabilities', $saved_capability_array );
         
        $this->UI->create_notice( __( 'Capabilities for this plugins pages have been stored. Due to this being security related I recommend testing before you logout. Ensure that each role only has access to the plugin pages you intend.' ), 'success', 'Small', __( 'Page Capabilities Updated' ) );        
    }
    
    /**
    * re-checks submitted sources directory and switches to a newer file if found
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function rechecksourcedirectory() {
        // validation
        if( !is_numeric( $_POST['datasourceidforrecheck'] ) ) {
            $this->UI->create_notice( "The source ID you submitted is not valid. A numeric ID is required.", 'error', 'Small', __( 'Invalid Source ID', 'csv2post' ) );    
            return;
        }

        // recheck the source
        $recheck_outcome_array =  $this->CSV2POST->recheck_source_directory( $_POST['datasourceidforrecheck'], true, true, false, false );
    
        if( isset( $recheck_outcome_array['error'] ) && $recheck_outcome_array['error'] === true ) {
            $this->UI->create_notice( $recheck_outcome_array['message'], 'success', 'Small', __( 'Problem Detected', 'csv2post' ) );
            return;        
        }        
        
        if( isset( $recheck_outcome_array['outcome'] ) && $recheck_outcome_array['outcome'] === false ) {
            $this->UI->create_notice( $recheck_outcome_array['message'], 'info', 'Small', __( 'No New Files', 'csv2post' ) );
            return;        
        }        
        
        if( isset( $recheck_outcome_array['outcome'] ) && $recheck_outcome_array['outcome'] === true ) {
            $this->UI->create_notice( $recheck_outcome_array['message'], 'success', 'Small', __( 'New File Detected', 'csv2post' ) );
            return;        
        }

        $this->UI->create_notice( __( 'The plugin checked the directory but the information returned is unexpected. The plugin may have switched to a new file if that is what you expected to happen but you must check and be sure.', 'csv2post' ), 'success', 'Small', __( 'Unknown State', 'csv2post' ) );       
    }    
    
    /**
    * processes request to import a new .csv file to an existing sources directory
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function urlimporttoexistingsource() {
        // perform sanitization
        $url = sanitize_url( $_POST['newdatasourcetheurl'] );

        // we cannot continue if santization results in a different path
        if( $url !== $_POST['newdatasourcetheurl'] ) {
            $this->UI->create_notice( __( 'Sanitization resulted in a change to your URL. This would only happen if you entered invalid characters. Please try again.', 'csv2post' ), 'error', 'Small', __( 'Invalid URL Entered', 'csv2post' ) );
            return false;            
        }
        
        // ensure user is attempting to transfer a .csv file, this concludes validation
        $path_parts = pathinfo( $url );
        if( $path_parts['extension'] !== 'csv' ) {
            $this->UI->create_notice( __( 'CSV 2 POST focuses on .csv files only. Please see the WebTechGlobal plugin range for other solutions.', 'csv2post' ), 'error', 'Small', __( 'Only .csv Files Allowed', 'csv2post' ) );
            return false;
        }
        
        // get data source - directory value only
        $source_array = $this->CSV2POST->get_source( $_POST['newprojectdatasource'] );
        if( !$source_array ) {
            $this->UI->create_notice( __( 'Problem obtaining source data. Please seek support from WebTechGlobl.', 'csv2post' ), 'error', 'Small', __( 'Source Query Faileda', 'csv2post' ) );
            return false;
        }
                   
        $uploads = array( 'path' => $source_array->directory, 
                          'url' => $url, 
                          'subdir' => '',// use to create a new directory
                          'error' => false 
        );
          
        $result = $this->Files->file_from_url( $url, $uploads, false );
        
        if( isset( $result['error'] ) && $result['error'] ) {
            $type = 'error';
        } else {
            $type = 'success';
        }
            
        $this->UI->create_notice( $result['message'], $type, 'Small', __( 'File Import Result', 'csv2post' ) );
    }  
    
    /**
    * upload .csv file and place it in the directory of an existing data source
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function uploadfiletodatasource() {
        // get data source - directory value only
        $source_array = $this->CSV2POST->get_source( $_POST['datasourcefornewfile'] );
        if( !$source_array ) {
            $this->UI->create_notice( __( 'The data sources record could not be found. Please seek support from WebTechGlobal.', 'csv2post' ), 'error', 'Small', __( 'Source Query Faileda', 'csv2post' ) );
            return false;
        }
                   
        $uploads = array( 'path' => $source_array->directory, 
                          'subdir' => '',// use to create a new directory
                          'error' => false 
        ); 
                
        $file_import_result = $this->Files->singlefile_uploader( $_FILES['uploadsinglefile'], $uploads );
                               
        // let the user know it has all gone very wrong and they are DOOMED! 
        if( !$file_import_result['outcome'] ) {             
            $this->UI->create_notice( $file_import_result['message'], 'error', 'Small', __( 'File Upload Failed', 'csv2post' ) );
            return;    
        }
                          
        $this->UI->create_notice( $file_import_result['message'], 'success', 'Small', __( 'File Uploaded', 'csv2post' ) );    
    }
    
    /**
    * Import new .csv file via URL and create a new data source
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function createurlcsvdatasource() {
       global $wpdb, $csv2post_settings;
        $CSV2POST_Files = CSV2POST::load_class( 'C2P_Files', 'class-files.php', 'classes' );

        // URL is a required field 
        if( empty( $_POST['newdatasourcetheurl2'] ) ){
            $this->UI->create_notice( __( 'Please enter the URL to your .csv file.', 'csv2post' ), 'error', 'Small', __( 'URL Field Required', 'csv2post' ) );
            return;
        }
                            
        // perform sanitization
        $url = sanitize_url( $_POST['newdatasourcetheurl2'] );
        $path = sanitize_text_field( $_POST['newdatasourcethepath2'] );
                
        // ensure user is attempting to transfer a .csv file
        $path_parts = pathinfo( $url );
        if( $path_parts['extension'] !== 'csv' ) {
            $this->UI->create_notice( __( 'CSV 2 POST focuses on .csv files only. Please see the WebTechGlobal plugin range for other solutions.', 'csv2post' ), 'error', 'Small', __( 'Only .csv Files Allowed', 'csv2post' ) );
            return false;
        }
        
        $uploads = array( 'path' => $path, 
                          'subdir' => '',// use to create a new directory
                          'error' => false 
        ); 
           
        // import file using URL to the giving path  
        $file_import_result = $CSV2POST_Files->file_from_url( $url, $uploads, false );       
        if( !$file_import_result['outcome'] ) {             
            $this->UI->create_notice( $file_import_result['failurereason'], 'error', 'Small', __( 'File Transfer Failed', 'csv2post' ) );
            return;    
        } 
        
        // build array of information for this file (this can be customized to work with multiple files)
        $files_array = array( 'total_files' => 1 );
                
        // add path for this file to $files_array which is then stored in data source table
        $files_array[1]['fullpath'] = $file_import_result['filepath'];
            
        // create success notice regarding file processing 
        $this->UI->create_notice( $file_import_result['message'], 'success', 'Small', __( 'File Transferred', 'csv2post' ) );   
                
        // establish separator
        $files_array[1]['sep'] = $this->Files->established_separator( $file_import_result['filepath'] );
        
        // we are ready to read the file
        $file = new SplFileObject( $file_import_result['filepath'] );
        while (!$file->eof() ) {
            $header_array = $file->fgetcsv( $files_array[1]['sep'], '"' );
            break;// we just need the first line to do a count()
        }       
        
        // count number of fields
        $files_array[1]['fields'] = count( $header_array );
        
        // create arrays of original headers and one of sql prepared headers
        foreach( $header_array as $key => $header ){  
            $files_array[1]['originalheaders'][$key] = $header;
            $files_array[1]['sqlheaders'][$key] = $this->PHP->clean_sqlcolumnname( $header );        
        }    
        
        // set basename
        $files_array[1]['basename'] = basename( $file_import_result['filepath'] );            

        // use basename to create database table name
        $files_array[1]['tablename'] = $wpdb->prefix . $this->PHP->clean_sqlcolumnname( $files_array[1]['basename'] );
        
        // set data treatment, the form allows a single file so 'single' is applied
        $files_array[1]['datatreatment'] = 'single';
        
        // ensure ID column is valid
        $cleanedidcolumn = '';
        if(!empty( $_POST['uniqueidcolumn'] ) ){
            $cleanedidcolumn = $this->PHP->clean_sqlcolumnname( $_POST['uniqueidcolumn'] );
            if(!in_array( $cleanedidcolumn, $files_array[1]['sqlheaders'] ) ){
                $this->UI->create_notice( 'You entered '.$_POST['uniqueidcolumn'].' as your ID column but it
                does not match any column header in your .csv file.', 'error', 'Small', __( "Invalid ID Column") );
                return;
            }
        } 
        
        // set project name
        if( empty( $_POST['newprojectname'] ) ){
            $files_array['projectname'] = basename( $files_array[1]['fullpath'] );
        }else{
            $files_array['projectname'] = $_POST['newprojectname'];
        }
        
        // does planned database table name exist                       
        $table_exists_result = $this->DB->does_table_exist( $files_array[1]['tablename'] );
        if( $table_exists_result){
            // drop table if user entered the random number
            if( isset( $_POST['deleteexistingtablecode1'] ) && $_POST['deleteexistingtable1'] == $_POST['deleteexistingtablecode1'] ){   
                $this->DB->drop_table( $files_array[1]['tablename'] );           
            }else{                
                $this->UI->create_notice( 'A database table already exists named ' . $files_array[1]['tablename'] . '. Please delete the existing table if it is not in use or change the name of your .csv file a little.', 'error', 'Small', __( 'Table Exists Already', 'csv2post' ) );
                return;  
            } 
        }
        
        // make entry in the c2psources table
        $files_array[1]['sourceid'] = $this->CSV2POST->insert_data_source( $file_import_result['filepath'], 0, $files_array[1]['tablename'], 'localcsv', $files_array[1], $cleanedidcolumn, $_POST['detectfilechanges'] );
        
        // create database table for importing data into, this is where we prepare it 
        $sqlheaders_array = array();
        foreach( $files_array[1]['sqlheaders'] as $key => $header ){
            $sqlheaders_array[$header] = 'nodetails';
        }

        $this->CSV2POST->create_project_table( $files_array[1]['tablename'], $sqlheaders_array ); 
        self::a_table_was_created( $files_array[1]['tablename'] );                                                              

        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'dataevent',
            'action' => __( 'Created URL .csv data source.', 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                       
        $this->UI->create_notice( sprintf( __( 'Your new source of data has been setup. You can now create a project using this source. The source ID is %s.', 'csv2post' ), $files_array[1]['sourceid'] ), 'success', 'Small', __( 'Data Source Ready' ) );  
    }
    
    /**
    * uploads new .csv file and uses it to create a new source
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function createuploadcsvdatasource() {
        global $wpdb;
        
        // perform sanitization
        $path = sanitize_text_field( $_POST['newdatasourcethepath1'] );
        $id_column = sanitize_text_field( $_POST['uniqueidcolumn1'] ); 
        $project_name = sanitize_text_field( $_POST['newprojectname1'] ); 
                     
        // handle file upload, create the uploads array
        $uploads = array( 'path' => $path, 
                          'subdir' => '',// use to create a new directory
                          'error' => false 
        ); 
                
        $file_import_result = $this->Files->singlefile_uploader( $_FILES['uploadsinglefile1'], $uploads );
             
        // let the user know it has all gone very wrong and they are DOOMED! 
        if( $file_import_result['outcome'] === false ) {   
            $this->UI->create_notice( $file_import_result['message'], 'error', 'Small', __( 'File Upload Failed', 'csv2post' ) );
            return;    
        }

        // build array of information for this file (this can be customized to work with multiple files)
        $files_array = array( 'total_files' => 1 );
            
        // add path for this file to $files_array which is then stored in data source table
        $files_array[1]['fullpath'] = $file_import_result['filepath'];
            
        // create success notice regarding file processing 
        $this->UI->create_notice( $file_import_result['message'], 'success', 'Small', __( 'File Transferred', 'csv2post' ) );   
                
        // establish separator
        $files_array[1]['sep'] = $this->Files->established_separator( $file_import_result['filepath'] );
        
        // we are ready to read the file
        $file = new SplFileObject( $file_import_result['filepath'] );
        while (!$file->eof() ) {
            $header_array = $file->fgetcsv( $files_array[1]['sep'], '"' );
            break;// we just need the first line to do a count()
        }       
        
        // count number of fields
        $files_array[1]['fields'] = count( $header_array );
        
        // create arrays of original headers and one of sql prepared headers
        foreach( $header_array as $key => $header ){  
            $files_array[1]['originalheaders'][$key] = $header;
            $files_array[1]['sqlheaders'][$key] = $this->PHP->clean_sqlcolumnname( $header );        
        }    
        
        // set basename
        $files_array[1]['basename'] = basename( $file_import_result['filepath'] );            

        // use basename to create database table name
        $files_array[1]['tablename'] = $wpdb->prefix . $this->PHP->clean_sqlcolumnname( $files_array[1]['basename'] );
        
        // set data treatment, the form allows a single file so 'single' is applied
        $files_array[1]['datatreatment'] = 'single';
        
        // ensure ID column is valid
        $cleanedidcolumn = '';
        if( !empty( $id_column ) && is_string( $id_column ) ){
            $cleanedidcolumn = $this->PHP->clean_sqlcolumnname( $id_column );
            if(!in_array( $cleanedidcolumn, $files_array[1]['sqlheaders'] ) ){
                $this->UI->create_notice( 'You entered ' . $id_column . ' as your ID column but it does not match any column header in your .csv file.', 'error', 'Small', __( "Invalid ID Column") );
                return;
            }
        } 
        
        // set project name
        if( empty( $project_name ) ){
            $files_array['projectname'] = basename( $files_array[1]['fullpath'] );
        }else{
            $files_array['projectname'] = $project_name;
        }
        
        // does planned database table name exist                       
        $table_exists_result = $this->DB->does_table_exist( $files_array[1]['tablename'] );
        if( $table_exists_result){
            // drop table if user entered the random number
            if( isset( $_POST['deleteexistingtablecode2'] ) && $_POST['deleteexistingtable2'] == $_POST['deleteexistingtablecode2'] ){   
                $this->DB->drop_table( $files_array[1]['tablename'] );           
            }else{                
                $this->UI->create_notice( 'A database table already exists named ' . $files_array[1]['tablename'] . '. Please delete the existing table if it is not in use or change the name of your .csv file a little.', 'error', 'Small', 'Table Exists Already' );
                return;  
            } 
        }
               
        // make entry in the c2psources table                                                                                                                                                     
        $files_array[1]['sourceid'] = $this->CSV2POST->insert_data_source( $file_import_result['filepath'], 0, $files_array[1]['tablename'], 'localcsv', $files_array[1], $cleanedidcolumn, $_POST['detectfilechanges3'] );
        
        // create database table for importing data into, this is where we prepare it 
        $sqlheaders_array = array();
        foreach( $files_array[1]['sqlheaders'] as $key => $header ){
            $sqlheaders_array[$header] = 'nodetails';
        }

        $this->CSV2POST->create_project_table( $files_array[1]['tablename'], $sqlheaders_array ); 
        self::a_table_was_created( $files_array[1]['tablename'] );                                                              

        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'dataevent',
            'action' => __( 'Created data source using uploaded .csv file.', 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                            
        $this->UI->create_notice( __( 'Your new source of data has been setup. You can now create a project using this source. The source ID is ' . $files_array[1]['sourceid'], 'csv2post' ), 'success', 'Small', __( 'Data Source Ready' ) );  
    }
    
    /**
    * Use a single .csv file already local on server to create a datasource.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.34
    * @version 1.1
    */
    public function createservercsvdatasource() {
       global $wpdb, $csv2post_settings;
        $CSV2POST_Files = CSV2POST::load_class( 'C2P_Files', 'class-files.php', 'classes' );

        // path is a required field 
        if( empty( $_POST['newdatasourcethepath3'] ) ){
            $this->UI->create_notice( __( 'Please enter a path to the .csv file on your server.', 'csv2post' ), 'error', 'Small', __( '.csv File Path Required', 'csv2post' ) );
            return;
        }
                            
        // perform sanitization
        $path = sanitize_text_field( $_POST['newdatasourcethepath3'] );
              
        // ensure user is attempting to use a .csv file
        $path_parts = pathinfo( $path );
        if( $path_parts['extension'] !== 'csv' ) {
            $this->UI->create_notice( __( 'CSV 2 POST focuses on .csv files only. Please see the WebTechGlobal plugin range for other solutions.', 'csv2post' ), 'error', 'Small', __( 'Only .csv Files Allowed', 'csv2post' ) );
            return false;
        }
        
        // build array of information for this file (this can be customized to work with multiple files)
        $files_array = array( 'total_files' => 1 );
                
        // add path for this file to $files_array which is then stored in data source table
        $files_array[1]['fullpath'] = $path;
            
        // establish separator
        $files_array[1]['sep'] = $this->Files->established_separator( $path );
        
        // we are ready to read the file
        $file = new SplFileObject( $path );
        while (!$file->eof() ) {
            $header_array = $file->fgetcsv( $files_array[1]['sep'], '"' );
            break;// we just need the first line to do a count()
        }       
        
        // count number of fields
        $files_array[1]['fields'] = count( $header_array );
        
        // create arrays of original headers and one of sql prepared headers
        foreach( $header_array as $key => $header ){  
            $files_array[1]['originalheaders'][$key] = $header;
            $files_array[1]['sqlheaders'][$key] = $this->PHP->clean_sqlcolumnname( $header );        
        }    
        
        // set basename
        $files_array[1]['basename'] = basename( $path );            

        // use basename to create database table name
        $files_array[1]['tablename'] = $wpdb->prefix . $this->PHP->clean_sqlcolumnname( $files_array[1]['basename'] );
        
        // set data treatment, the form allows a single file so 'single' is applied
        $files_array[1]['datatreatment'] = 'single';
        
        // ensure ID column is valid
        $cleanedidcolumn = '';
        if(!empty( $_POST['uniqueidcolumn'] ) ){
            $cleanedidcolumn = $this->PHP->clean_sqlcolumnname( $_POST['uniqueidcolumn'] );
            if(!in_array( $cleanedidcolumn, $files_array[1]['sqlheaders'] ) ){
                $this->UI->create_notice( 'You entered '.$_POST['uniqueidcolumn'].' as your ID column but it does not match any column header in your .csv file.', 'error', 'Small', __( "Invalid ID Column") );
                return;
            }
        } 
        
        // set project name
        if( empty( $_POST['newprojectname'] ) ){
            $files_array['projectname'] = basename( $files_array[1]['fullpath'] );
        }else{
            $files_array['projectname'] = $_POST['newprojectname'];
        }
        
        // does planned database table name exist                       
        $table_exists_result = $this->DB->does_table_exist( $files_array[1]['tablename'] );
        if( $table_exists_result){
            // drop table if user entered the random number
            if( isset( $_POST['deleteexistingtablecode3'] ) && $_POST['deleteexistingtable3'] == $_POST['deleteexistingtablecode3'] ){   
                $this->DB->drop_table( $files_array[1]['tablename'] );           
            }else{                
                $this->UI->create_notice( 'A database table already exists named ' . $files_array[1]['tablename'] . '. Please delete the existing table if it is not in use or change the name of your .csv file a little.', 'error', 'Small', 'Table Exists Already' );
                return;  
            } 
        }
        
        // make entry in the c2psources table
        $files_array[1]['sourceid'] = $this->CSV2POST->insert_data_source( $files_array[1]['fullpath'], 0, $files_array[1]['tablename'], 'localcsv', $files_array[1], $cleanedidcolumn, $_POST['monitorfilechange2'] );
        
        // create database table for importing data into, this is where we prepare it 
        $sqlheaders_array = array();
        foreach( $files_array[1]['sqlheaders'] as $key => $header ){
            $sqlheaders_array[$header] = 'nodetails';
        }

        $this->CSV2POST->create_project_table( $files_array[1]['tablename'], $sqlheaders_array ); 
        self::a_table_was_created( $files_array[1]['tablename'] );                                                              
         
        // log
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'type' => 'general',
            'category' => 'dataevent',
            'action' => __( 'Created data-source using local file that was already on the server.', 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );
                   
        $this->UI->create_notice( __( 'Your new source of data has been setup. You can now create a project using this source. The source ID is ' . $files_array[1]['sourceid'], 'csv2post' ), 'success', 'Small', __( 'Data Source Ready' ) );  
    }
    
    /**
    * Use all the .csv files in a directory. The first file is treated as the parent.
    * The parent files configuration must match the rest.
    * The parent files configuraton is used to create the database table.
    * All files will be added as datasources for individual management.
    * Only the parent file will be available when creating a project though.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.35
    * @version 1.1
    */
    public function createdirectorycsvdatasource() {
        global $wpdb, $csv2post_settings;
        
        // count the number of .csv files used to create a datasource
        $total_new_datasources = 0;
        
        // need the parent source ID for all sorts of reasons, including telling the user in a notice
        $parent_source_id = 0;
        
        // table name is needed for all files, as each file/data-source is handled 
        $table_name = false;
        
        // load class for file handling
        $CSV2POST_Files = CSV2POST::load_class( 'C2P_Files', 'class-files.php', 'classes' );
        
        // path to a directory of files is a required field 
        if( empty( $_POST['newdirectorysource'] ) ){
            $this->UI->create_notice( __( 'Please enter a path to the folder on your server containing your .csv files.', 'csv2post' ), 'error', 'Small', __( 'Folder Path Required', 'csv2post' ) );
            return;
        }
        
        //sanitize path
        $path = sanitize_text_field( $_POST['newdirectorysource'] );
        $path = stripslashes_deep( $path );
        $path = rtrim($path, '/') . '/';// ensure trailing slash
                
        // treat the first file different
        $is_first_file = true;
        
        // open directory and walk through the filenames
        $handler = opendir( $path );        
        while ( $file = readdir( $handler ) ) {   
            if ( $file != "." && $file != "..") {

                // ensure is .csv file
                $path_parts = pathinfo( $file );   
                if( $path_parts['extension'] === 'csv' ) {  
                
                    // build array of information for this file
                    // we have a chicken & egg situation here, but I'm not sure it matters 
                    // as I don't think the total_files value will be used when handling none parent files
                    $files_array = array( 'total_files' => $total_new_datasources );                    
            
                    // add path for this file to $files_array which is then stored in data source table
                    $files_array[1]['fullpath'] = $path . $file;
                        
                    // establish separator
                    $files_array[1]['sep'] = $this->Files->established_separator( $files_array[1]['fullpath'] );
                    
                    // we are ready to read the file
                    $file = new SplFileObject( $files_array[1]['fullpath'] );
                    while (!$file->eof() ) {
                        $header_array = $file->fgetcsv( $files_array[1]['sep'], '"' );
                        break;// we just need the first line to do a count()
                    }       
                    
                    // count number of fields
                    $files_array[1]['fields'] = count( $header_array );
                    
                    // create arrays of original headers and one of sql prepared headers
                    foreach( $header_array as $key => $header ){  
                        $files_array[1]['originalheaders'][$key] = $header;
                        $files_array[1]['sqlheaders'][$key] = $this->PHP->clean_sqlcolumnname( $header );        
                    }    
                    
                    // set basename
                    $files_array[1]['basename'] = basename( $path );            

                    // set data treatment, the form allows a single file so 'single' is applied
                    $files_array[1]['datatreatment'] = 'multiple';
             
                    // ensure ID column is valid
                    $cleanedidcolumn = '';
                    if(!empty( $_POST['uniqueidcolumn'] ) ){
                        $cleanedidcolumn = $this->PHP->clean_sqlcolumnname( $_POST['uniqueidcolumn'] );
                        if(!in_array( $cleanedidcolumn, $files_array[1]['sqlheaders'] ) ){
                            $this->UI->create_notice( 'You entered '.$_POST['uniqueidcolumn'].' as your ID column but it does not match any column header in one of your .csv files. This is required in multiple file projects to prevent duplicate records. Open a threa on the WebTechGlobal forum if you wish to discuss ways to get around this should you not have an ID column.', 'error', 'Small', __( "Invalid ID Column") );
                            return;
                        }
                    } 
                                            
                    // if on the first VALID file, we make it the parent and use it to build the profile
                    if( $is_first_file ) {
                        
                        $is_first_file = false;
                            
                        // use basename to create database table name
                        $files_array[1]['tablename'] = $wpdb->prefix . $this->PHP->clean_sqlcolumnname( $files_array[1]['basename'] );
           
                        // keep new database table name for adding to all new source records
                        if( $table_name === false ) {
                            $table_name = $files_array[1]['tablename'];    
                        }
                        
                        // does planned database table name exist                       
                        $table_exists_result = $this->DB->does_table_exist( $table_name );
                        if( $table_exists_result){
                            // drop table if user entered the random number
                            if( isset( $_POST['deleteexistingtablecode3'] ) && $_POST['deleteexistingtable3'] == $_POST['deleteexistingtablecode3'] ){   
                                $this->DB->drop_table( $table_name );           
                            }else{                
                                $this->UI->create_notice( 'A database table already exists named ' . $table_name . '. Please delete the existing table if it is not in use or change the name of your .csv file a little.', 'error', 'Small', 'Table Exists Already' );
                                return;  
                            } 
                        }
                        
                        // create database table for importing data into, this is where we prepare it 
                        $sqlheaders_array = array();
                        foreach( $files_array[1]['sqlheaders'] as $key => $header ){
                            $sqlheaders_array[$header] = 'nodetails';
                        }

                        $this->CSV2POST->create_project_table( $files_array[1]['tablename'], $sqlheaders_array ); 
                        self::a_table_was_created( $files_array[1]['tablename'] );                                                              
                    }   
                                        
                    // make entry in the c2psources table
                    $files_array[1]['sourceid'] = $this->CSV2POST->insert_data_source( $files_array[1]['fullpath'], $parent_source_id, $table_name, 'localcsv', $files_array[1], $cleanedidcolumn, $_POST['detectfilechanges1'], $_POST['detectnewfiles1'] );
                    
                    // keep parent ID for adding to all source records
                    if( $parent_source_id === 0 ) {
                        $parent_source_id = $files_array[1]['sourceid'];    
                    }
                     
                    ++$total_new_datasources;
                }              
            }
        }  
       
        if( !$parent_source_id ) {
 	         $this->UI->create_notice( __( 'No new .csv file data sources have been setup because the directory you submitted does not contain a .csv file or one that is properly formatted. The directory must contain one or more .csv files, one will be selected as a parent and used to configure the plugin further.', 'csv2post' ), 'error', 'Small', __( 'No Source of Data' ) );  	
        		return;	
        }     
        
        // set path as a new directory source - I will use the path to get data from the sources table i.e. total .csv files setup as data sources, compare that to a total of .csv files in the directory
        $csv2post_settings['directorysources'][$path]['monitor'] = true;
        $csv2post_settings['directorysources'][$path]['name'] = $_POST['newsourcename1'];    
        $this->CSV2POST->update_settings( $csv2post_settings );

        // make log entry under dataevent category
        $atts = array(
            'outcome' => 1,
            'line' => __LINE__, 
            'function' => __FUNCTION__,
            'file' => __FILE__, 
            'userid' => get_current_user_id(),          
            'general' => 'general',
            'category' => 'dataevent',
            'action' => __( 'Directory data source created.', 'csv2post' ),
            'priority' => 'normal',                        
            'triga' => 'manualrequest'
        );
        
        $this->CSV2POST->newlog( $atts );        
        
        // finishing notice            
        $this->UI->create_notice( __( 'All done! A total of ' . $total_new_datasources . ' data-sources have been setup. The parent source ID is ' . $parent_source_id . ' and that is the one you should work with when creating a project to use the data you import from all files.', 'csv2post' ), 'success', 'Small', __( 'Data Source Ready' ) );  
    }
    
    /**
    * use project ID to set giving project
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function setactiveproject() {
        global $wpdb, $csv2post_settings;
        $projects_array = $this->DB->selectwherearray( $wpdb->c2pprojects, 'projectid = projectid', 'timestamp', '*', ARRAY_A, 'ASC' );
               
        $valid_project_id = false;
        foreach( $projects_array as $key => $project ) {
            if( $project['projectid'] === $_POST['setprojectid'] ) {
                $csv2post_settings['currentproject'] = $_POST['setprojectid']; 
                $this->CSV2POST->update_settings( $csv2post_settings );
                $valid_project_id = true;
                break;       
            }    
        }
   
        if( $valid_project_id ) {
            $this->UI->create_notice( __( 'You have actived project with ID ' . $_POST['setprojectid'] . ' and can now change the settings for that project.', 'csv2post' ), 'success', 'Small', __( 'Project Ready', 'csv2post' ) );    
        } else { 
            $this->UI->create_notice( __( 'The project ID you submitted is not valid. Either no existing project has that ID or your entry is not numeric.', 'csv2post' ), 'error', 'Small', __( 'Failed' ) );
        }       
    }
    
    /**
    * Saves the plugins global dashboard widget settings i.e. which to display, what to display, which roles to allow access
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function dashboardwidgetsettings() {
        global $csv2post_settings;
        
        // loop through pages
        $CSV2POST_TabMenu = CSV2POST::load_class( 'C2P_TabMenu', 'class-pluginmenu.php', 'classes' );
        $menu_array = $CSV2POST_TabMenu->menu_array();       
        foreach( $menu_array as $key => $section_array ) {

            if( isset( $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ] ) ) {
                $csv2post_settings['widgetsettings'][ $section_array['name'] . 'dashboardwidgetsswitch'] = $_POST[ $section_array['name'] . 'dashboardwidgetsswitch' ];    
            }
            
            if( isset( $_POST[ $section_array['name'] . 'widgetscapability' ] ) ) {
                $csv2post_settings['widgetsettings'][ $section_array['name'] . 'widgetscapability'] = $_POST[ $section_array['name'] . 'widgetscapability' ];    
            }

        }

        $this->CSV2POST->update_settings( $csv2post_settings );    
        $this->UI->create_notice( __( 'Your dashboard widget settings have been saved. Please check your dashboard to ensure it is configured as required per role.', 'csv2post' ), 'success', 'Small', __( 'Settings Saved', 'csv2post' ) );         
    }
    
    /**
    * Deletes data source submitted via form $_POST
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function deletedatasource () {
    
        if( !isset( $_POST['confirmdeletedatasource'] ) || empty( $_POST['confirmdeletedatasource'] ) ) {
            $this->UI->create_notice( __( 'To prevent accidental deletion of large amounts of data. The plugin requires users to copy and paste the random code found.', 'csv2post' ), 'error', 'Small', __( 'Confirmation Code Required', 'csv2post' ) );
            return;    
        }
        
        if( $_POST['confirmdeletedatasource'] !== $_POST['confirmdeletedatasourcecode'] ) {
            $this->UI->create_notice( __( 'The confirmation code is incorrect please try again.', 'csv2post' ), 'error', 'Small', __( 'Confirmation Code Required', 'csv2post' ) );
            return;    
        }
        
        $delete = $this->CSV2POST->delete_datasource( $_POST['datasourcefornewfile'] );
        if( $delete ) {
            $this->UI->create_notice( __( 'Your datasource with ID '.$_POST['datasourcefornewfile'].' was deleted.', 'csv2post' ), 'success', 'Small', __( 'Deleted', 'csv2post' ) );         
        } else {
            $this->UI->create_notice( __( 'Your datasource either no longer exists or could not be deleted. If it is in use by a project that may have caused this.', 'csv2post' ), 'warning', 'Small', __( 'No Changes Made', 'csv2post' ) );                 
        }        
    }

    /**
    * Locates imported records that have a post ID but the post no longer exists.
    * 
    * A count is done and optional re-creation of posts available.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function recreatemissingposts() {
        global $csv2post_settings;
                       
        $table_name = $this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] );

        $select = 'c2p_rowid, c2p_postid';

        $missing_posts = 0;
        
        // count re-creations
        $recreated = 0;
               
        // get all rows that have a c2p_postid
        $result = $this->DB->selectwherearray( $table_name, 'c2p_postid != ""', 'c2p_rowid', $select, ARRAY_A );
        
        if( $result ){
            
            $autoblog = new CSV2POST_InsertPost();
            $autoblog->settings = $csv2post_settings;
            $autoblog->currentproject = $csv2post_settings['currentproject'];
            $autoblog->project = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] );// gets project row, includes sources and settings
            $autoblog->projectid = $csv2post_settings['currentproject'];
            $autoblog->maintable = $this->CSV2POST->get_project_main_table( $csv2post_settings['currentproject'] );// main table holds post_id and progress statistics
            $autoblog->projectsettings = maybe_unserialize( $autoblog->project->projectsettings );// unserialize settings
            $autoblog->projectcolumns = $this->CSV2POST->get_project_columns_from_db( $csv2post_settings['currentproject'] );
            $autoblog->requestmethod = 'manual';
            $sourceid_array = $this->CSV2POST->get_project_sourcesid( $csv2post_settings['currentproject'] );
            $autoblog->mainsourceid = $sourceid_array[0];// update the main source database table per post with new post ID
            unset( $autoblog->project->projectsettings );// just simplifying the project object by removing the project settings

            $idcolumn = false;
            if( isset( $autoblog->projectsettings['idcolumn'] ) ){
                $idcolumn = $autoblog->projectsettings['idcolumn'];    
            }
            
            // we will control how and when we end the operation
            $autoblog->finished = false;// when true, output will be complete and foreach below will discontinue, this can be happen if maximum execution time is reached

            foreach( $result as $key => $row ) {
                
                $get_post_result = get_post( $row['c2p_postid'] );
                  
                // create missing post
                if( !$get_post_result ) {
                    
                    ++$missing_posts;
                    
                    if( isset( $_POST['recreatemissingposts'] ) ) { 

                        // pass row to $autob
                        $autoblog->row = $row;    
                        
                        // create a post - start method is the beginning of many nested functions
                        $autoblog->start();
                                         
                        ++$recreated;
                    }
                }
            }          
        }
        
        if( $missing_posts === 0 ) {
            
            $this->UI->create_notice( __( 'You do not have any missing posts, no changes were made to your blog. If you feel posts are missing, please ensure they are not in the WordPress trash.' ), 'info', 'Small', __( 'No Missing Posts', 'csv2post' ) );
            
        } elseif( $recreated > 0 ) {
            
            $this->UI->create_notice( __( "A total of $recreated posts were missing and have been re-created." ), 'info', 'Small', __( 'No Rows Available', 'csv2post' ) );
             
        } elseif( $missing_posts > 0 && !isset( $_POST['recreatemissingposts'] ) ) {
            
            $this->UI->create_notice( __( "A total of $missing_posts posts appear to be missing but do not worry if you still have your data WTG has made it easy to fix such problems." ), 'info', 'Small', __( 'Posts Missing', 'csv2post' ) );
    
        }
    }    
    
    /**
    * Handle a mass post publication request with a selected scope.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function masspublishposts() {
        global $csv2post_settings;
        
        $meta_key = '';
        $meta_value = '';
        
        // establish search criteria    
        switch ( $_POST['masspublishscope'] ) {
           case 'currentproject':
                // get drafts for the current project only
                $meta_key = 'c2p_project';
                $meta_value = $csv2post_settings['currentproject'];             
             break;
           case 'allprojects':
                // get drafts for all CSV 2 POST projects
                $meta_key = 'c2p_project'; 
             break;
           case 'entireblog':
             // get all drafts, even those not created by CSV 2 POST
             break;
        }
        
        $args = array(
            'posts_per_page'   => 5,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => $meta_key,
            'meta_value'       => $meta_value,
            'post_type'        => 'post',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'draft',
            'suppress_filters' => true );
        
        $posts_array = get_posts( $args ); 

        if( !$posts_array )
        {
            $this->UI->create_notice( __( "No drafts were applicable to your search criteria." ), 'info', 'Small', __( 'No Applicable Drafts', 'csv2post' ) );
            return;
        }

        // publish all using the core
        $i = 0;
        foreach( $posts_array as $post ){
            wp_publish_post( $post->ID );
            ++$i;
        }

        $this->UI->create_notice( __( "A total of ." ), 'info', 'Small', __( 'Publish Request Results', 'csv2post' ) );               
    }
          
}// C2P_Requests       
?>
