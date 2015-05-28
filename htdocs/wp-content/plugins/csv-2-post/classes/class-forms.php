<?php
/** 
 * Form builder and handler classes for WordPress only.
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 0.0.1
 * @version 1.0
 */  

/** 
* Main form builder and handler class for WordPress
* 
* @package CSV 2 POST
* @author Ryan Bayne   
* @since 0.0.1
* @version 1.0
*/
class CSV2POST_Forms extends CSV2POST_UI {
    
    // array of valid input types, custom ones can be added which relate to special variations of original functions
    var $input_types = array( 
        'text', 
        'hidden', 
        'dateandtime',
        'menu_cronhooks',
        'menu_cronrepeat',
        'menu_capabilities',
        'file',
        'radiogroup_postformats',
        'radiogroup_posttypes',
        'menu_categories',
        'menu_users',
        'checkboxes',
        'menu',
        'textarea',
        'radiogroup',
        'switch'
    );
    
    // array of available validation methods (applies validation automatically on submission with standard outputs)
    var $validation_types = array( 
        'alpha', 
        'alphanumeric',
        'numeric',
        'unset',
        'URL',
        'url',
        'none'
    );
    
    // build an array of known inputs we do not need to validate ever, used to exit processes as early as possible
    var $ignored_inputs = array( '_wpnonce', '_wp_http_referer' );

    // array of used defaults that are invalid i.e. used to detect user did not make a selection, they must not possible match a real value
    var $common_defaults = array( 'notselected123', 'notrequired123' );
    
    // array of input types that are groups of individual inputs, they need to be treated slightly different in various functions
    var $grouped_fields = array( 'dateandtime', 'checkboxes', 'radiogroup', 'radios', 'radiogroup_postformats', 'radiogroup_posttypes'  );   
    
    public function __construct() {
        // create class objects
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->WPCore = CSV2POST::load_class( 'CSV2POST_WPCore', 'class-wpcore.php', 'classes' );
        
        // get form validation array
        $this->form_val_arr = get_option( 'csv2post_formvalidation' );
        
        // re-populate class var with the localized value
        $this->first_switch_label = __( 'Enabled', 'csv2post' );
        $this->second_switch_label = __( 'Disabled', 'csv2post' );
    }
               
    /**
    * Returns an array of input types by default but can be used to check if a specific type is valid
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    * 
    * @returns array of input types, if $string will return boolean (true if $string is a valid input type)
    */
    public function input_types( $string = null ) {  
        if( !$string ) { return $this->input_types; }
        if( in_array( $string, $this->input_types ) ) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * Returns array of validation types or if $string used confirms if a validation type is valid
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validation_types( $string = null ) {
        if( !$string ) { return $this->validation_types; }
        if( in_array( $string, $this->validation_types ) ) {
            return true;
        } else {
            return false;
        }    
    }
    
    /**
    * Call this for any new form input, the idea is to make form building quick but safe by including all security measures.
    * You can pass values to $att_array or use the parameters after $att_array.
    * 
    * 1. Copy and paste the function line, remove variable name and leave "null" for any par not required for input
    * OR
    * 2. Build up $array() 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */
    public function input( $formid, $inputtype, $inputid, $inputname, $optiontitle, $titleatt = '', $required = false, $currentvalue = null, $atts_array = array(), $validation_array = array() ) {
       
        // ensure input type is valid else add a message to the form indicating code issue
        if( !in_array( $inputtype, $this->input_types, false ) ) {
            self::option_subline( __( 'This is a technical matter please report it. There should be a form input here that you may require before you continue.', 'csv2post' ), __( 'Invalid Input Type', 'csv2post' ) );
            return false;    
        }      
        
        // unset object values to prevent a previous inputs settings being applied
        unset( $this->formid );
        unset( $this->inputtype );
        unset( $this->inputid );
        unset( $this->inputname );
        unset( $this->optiontitle );
        unset( $this->titleatt );
        unset( $this->required );
        unset( $this->currentvalue );
        unset( $this->validationarray );   
        unset( $this->alternativetext );
        unset( $this->defaultvalue );
        unset( $this->cols ); 
        unset( $this->rows );
        unset( $this->defaultitem_name );
        unset( $this->defaultitem_value );
        unset( $this->appendtext );
        unset( $this->class );
        unset( $this->readonly );
        unset( $this->disabled );
        unset( $this->instructions );
        unset( $this->checkon );
        unset( $this->labelone );
        unset( $this->labeltwo );
        unset( $this->itemsarray );
        unset( $this->minimumchecks );
        unset( $this->maximumchecks );
        unset( $this->html5 );
        unset( $this->ajax );
        
        
        // add values to class object, these ones are the most common and so we set them early
        $this->formid = $formid;
        $this->inputtype = $inputtype;
        $this->inputid = $inputid;
        $this->inputname = $inputname;
        $this->optiontitle = $optiontitle;// title seen by user, added to left column of WP styled <table>
        $this->titleatt = $titleatt;
        $this->required = $required;
        $this->currentvalue = $currentvalue;
        $this->validationarray = $validation_array;
                     
        // add optional parameters that are only passed in array due to how many there is      
        if( isset( $atts_array['alternativetext'] ) ) { $this->alternativetext = $atts_array['alternativetext']; }
        if( isset( $atts_array['defaultvalue'] ) ) { $this->defaultvalue = $atts_array['defaultvalue']; }
        if( isset( $atts_array['cols'] ) ) { $this->cols = $atts_array['cols']; }  
        if( isset( $atts_array['rows'] ) ) { $this->rows = $atts_array['rows']; }
        if( isset( $atts_array['defaultitem_name'] ) ) { $this->defaultitem_name = $atts_array['defaultitem_name']; }
        if( isset( $atts_array['defaultitem_value'] ) ) { $this->defaultitem_value = $atts_array['defaultitem_value']; }
        if( isset( $atts_array['appendtext'] ) ) { $this->appendtext = $atts_array['appendtext']; }
        if( isset( $atts_array['class'] ) ) { $this->class = $atts_array['class']; }
        if( isset( $atts_array['readonly'] ) ) { $this->readonly = $atts_array['readonly']; }
        if( isset( $atts_array['disabled'] ) ) { $this->disabled = $atts_array['disabled']; }
        if( isset( $atts_array['instructions'] ) ) { $this->instructions = $atts_array['instructions']; }
        if( isset( $atts_array['checkon'] ) ) { $this->checkon = $atts_array['checkon']; }
        if( isset( $atts_array['labelone'] ) ) { $this->labelone = $atts_array['labelone']; }
        if( isset( $atts_array['labeltwo'] ) ) { $this->labeltwo = $atts_array['labeltwo']; }
        if( isset( $atts_array['itemsarray'] ) ) { $this->itemsarray = $atts_array['itemsarray']; }
        if( isset( $atts_array['minimumchecks'] ) ) { $this->minimumchecks = $atts_array['minimumchecks']; }
        if( isset( $atts_array['maximumchecks'] ) ) { $this->maximumchecks = $atts_array['maximumchecks']; }
        if( isset( $atts_array['html5'] ) ) { $this->html5 = $atts_array['html5']; }
        if( isset( $atts_array['ajax'] ) ) { $this->ajax = $atts_array['ajax']; }

        // register the input - also uses the above values, the idea is to register everything and ensure it has not been changed by hacker before submission
        self::register_input();
        
        // create input
        eval( 'self::input_' . $this->inputtype . '();' );
    }     
    
    /**
    * Register the form, done before registering each input
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function register_form( $form_id ) {
        
        // initiate array if need to
        if( !is_array( $this->form_val_arr )  ) {   
            $this->form_val_arr = array();
        }
        
        $user_ID = get_current_user_id();

        $this->form_val_arr[ $user_ID ][ $form_id ]['registrytime'] = time();    

        update_option( 'csv2post_formvalidation', $this->form_val_arr );
    }
    
    /**
    * Deletes form registration for the current user and current submitted form, performed 
    * after form processing.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.2
    */
    public function deregister_form( $form_id ) {
        $user_ID = get_current_user_id();
        if( isset( $this->form_val_arr[ $user_ID ][ $form_id ] ) ) {
            unset( $this->form_val_arr[ $user_ID ][ $form_id ] );    
        }
        update_option( 'csv2post_formvalidation', $this->form_val_arr );
    }
    
    /**
    * Stores the input attribute details, we use it to validate the input and ensure the form was not hacked using source code inspection tools.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function register_input() {
              
        // initiate array if need to
        if( !is_array( $this->form_val_arr )  ) {
            $this->form_val_arr = array();
        }
        
        // we need to register using user ID because a form might be different per user
        $user_ID = get_current_user_id(); 
                                              
        // register required and most common attributes     
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['inputtype'] = $this->inputtype;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['inputid'] = $this->inputid;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['optiontitle'] = $this->optiontitle;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['titleatt'] = $this->titleatt;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['required'] = $this->required;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['currentvalue'] = $this->currentvalue;
        $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['validationarray'] = $this->validationarray;
        
        // register optional values
        if( isset( $this->alternativetext ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['alternativetext'] = $this->alternativetext; }
        if( isset( $this->defaultvalue ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['defaultvalue'] = $this->defaultvalue; }
        if( isset( $this->cols ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['cols'] = $this->cols; }
        if( isset( $this->rows ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['rows'] = $this->rows; }
        if( isset( $this->defaultitem_name ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['defaultitem_name'] = $this->defaultitem_name; }
        if( isset( $this->defaultitem_value ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['defaultitem_value'] = $this->defaultitem_value; }
        if( isset( $this->appendtext ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['appendtext'] = $this->appendtext; }
        if( isset( $this->class ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['class'] = $this->class; }
        if( isset( $this->readonly ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['readonly'] = $this->readonly; }
        if( isset( $this->disabled ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['disabled'] = $this->disabled; }
        if( isset( $this->instructions ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['instructions'] = $this->instructions; }
        if( isset( $this->checkon ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['checkon'] = $this->checkon; }
        if( isset( $this->labelone ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['labelone'] = $this->labelone; }
        if( isset( $this->labeltwo ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['labeltwo'] = $this->labeltwo; }
        if( isset( $this->itemsarray ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['itemsarray'] = $this->itemsarray; }
        if( isset( $this->minimumchecks ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['minimumchecks'] = $this->minimumchecks; }
        if( isset( $this->maximumchecks ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['maximumchecks'] = $this->maximumchecks; }
        if( isset( $this->html5 ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['html5'] = $this->html5; }
        if( isset( $this->ajax ) ) { $this->form_val_arr[ $user_ID ][ $this->formid ][ $this->inputname ]['ajax'] = $this->ajax; }

        update_option( 'csv2post_formvalidation', $this->form_val_arr );   
    } 

    /**
    * Validates the values for individual form inputs and stop further processing of request if a value does not pass
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */
    public function apply_input_validation( $formid ) {
        // set result, default true to allow processing to complete, change to false if user made invalid entry
        $result = true;
        
        $user_ID = get_current_user_id();
        
        if( !isset( $this->form_val_arr[ $user_ID ][ $formid ] ) ) {
            return $result;
        }
        
        // if no validation array return a true result now to avoid failure
        if( !$this->form_val_arr || !$this->form_val_arr[ $user_ID ][ $formid ] ) {
            return $result;
        }                                               
         
        // loop through the forms REGISTERED inputs
        foreach( $this->form_val_arr[ $user_ID ][ $formid ] as $input_name => $input_array ) { 
       
            // if current field is required   
            if( isset( $input_array['required']) && $input_array['required'] === true ) {
                
                // ensure required checkboxes selected
                if( $input_array['inputtype'] === 'checkboxes' ) {
                             
                    $box_checked = 0;
                    $i = 0;
                    
                    // grouped or separate checkboxes require different process - default is grouped
                    if( !isset( $this->form_val_arr[ $user_ID ][ $formid ][ $input_name ]['groupcheckboxes'] ) || $this->form_val_arr[ $user_ID ][ $formid ][ $input_name ]['groupcheckboxes'] === true ) {
                        
                        // ensure checkbox array exists (user may not have made any selection)
                        if( isset( $_POST[ $input_name ] ) ) {
                            foreach( $input_array['itemsarray'] as $item_value => $item_title ) { 
                                
                                // grouped items name and ID include form + item value to be unique on the entire page
                                if( in_array( $item_value, $_POST[ $input_name ] ) ) {
                                    ++$box_checked;        
                                }  
                                
                                ++$i;                          
                            }                            
                        }
                            
                    } else {   
                    
                        foreach( $input_array['itemsarray'] as $item_value => $item_title ) { 
                            
                            // grouped items name and ID include form + item value to be unique on the entire page
                            if( isset( $_POST[ $input_array['inputid'] . $i ] ) ) {
                                ++$box_checked;        
                            }  
                            
                            ++$i;                          
                        }
                    }
                    
                    // if none checked FAIL
                    if( $box_checked === 0 ) {
                        $this->UI->create_notice( sprintf( __( 'Oops! You missed something. Please make selection for %s as it is a required option.', 'csv2post' ), $input_array['optiontitle'] ), 'error', 'Small', __( 'Required Option', 'csv2post' ) );                    
                        return false;                    
                    }                    
                    
                    // if not enough checked FAIL
                     if( $box_checked < $input_array['minimumchecks'] ) {
                        $this->UI->create_notice( sprintf( __( 'You have not checked enough boxes for the %s option.', 'csv2post' ), $input_array['optiontitle'] ), 'error', 'Small', __( 'Check More Boxes', 'csv2post' ) );                    
                        return false;                    
                    }
                                       
                    // if too many checked....you guessed it FAIL
                    if( $box_checked > $input_array['maximumchecks'] ) {
                        $this->UI->create_notice( sprintf( __( 'You have checked too many boxes for the %s option.', 'csv2post' ), $input_array['optiontitle'] ), 'error', 'Small', __( 'Please Uncheck Boxes', 'csv2post' ) );                    
                        return false;                    
                    }                    
                    
                }elseif( !isset( $_POST[ $input_name ] ) || empty( $_POST[ $input_name ] ) || in_array( $_POST[ $input_name ], $this->common_defaults ) ) {
                    
                    // arriving here indicates a required input has not been used
                    $this->UI->create_notice( sprintf( __( 'Form incomplete because the %s is a required option.', 'csv2post' ), $input_array['optiontitle'] ), 'error', 'Small', __( 'Required Option', 'csv2post' ) );                    
                    return false;                      
                }
            }           
        }
        
        // loop through $_POST values and if the name is registered apply validation    
        foreach( $_POST as $name => $value ) {
                 
            // main validation - get the inputs validation method if it has been registered
            //$validation_methods_array = self::get_input_validation_methods_array( $formid, $name );
            $validation_methods_array = false;
            if( isset( $this->form_val_arr[ $user_ID ][ $formid ][ $name ]['validationarray'] ) ) { 
                $validation_methods_array = $this->form_val_arr[ $user_ID ][ $formid ][ $name ]['validationarray'];
            }
                    
            if( $validation_methods_array !== false && is_array( $validation_methods_array ) ) {
                
                // loop through each validation method, calling its function
                foreach( $validation_methods_array as $validation_method => $atts_mixed ) { 
                                                          
                    // build attributes array i.e. if maximum length validation, pass ['maxlength']
                    $atts_array = array();// not needed for all validation
                                        
                    // ensure the expected input_validation_example() function exists
                    if( method_exists( $this, 'validate_' . $validation_method ) ) { 
                                  
                        // input_validation functions return boolean false on fail and output a notice to make user aware
                        // else nothing else happens, next input is processed
                        eval( '$result = self::validate_' . $validation_method . '( $value, $this->form_val_arr[ $user_ID ][ $formid ][ $name ][ "optiontitle" ], $atts_mixed );');// $result is always boolean    
                        
                        // if false return 
                        if( !$result ) {
                            return $result;
                        }
                    }    
                }
            }
        }
        
        // true allows the final function to be called, processing the request further
        // that function may perform further sanitization and even validation
        return $result;
    }
    
    /**
    * Checks registered input values for HTML form attributes and detects anything wrong.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.2
    */
    public function apply_input_security() {
        $result = true;
        
        # if form ID value does not exist FAIL - this is used to access form registration
        if( !isset( $_POST['csv2post_form_formid'] ) ) { 
            return false;
        }
        
        $form_id = $_POST['csv2post_form_formid'];
        
        $user_ID = get_current_user_id();
        
        # does $_POST value exist for an input that was disabled="disabled" indicating a hacker endabled it
        foreach( $_POST as $input_name => $value ) {
            
            // we are looping through $_POST  - is the current input registered 
            if( isset( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ] ) ) {
                
                // was the input meant to be disabled (would not be in $_POST)
                if( isset( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['disabled'] ) && $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['disabled'] === true ) {
                    
                    // this state indicates user hack enabled input
                    $this->UI->create_notice( __( 'Possible security violation detected. Please discontinue your action. This event has been logged for investigation.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                    return false;    
                }
            }    
        }                  
    
        
        # is a $_POST value missing that should exist (hacker disabled it)
        # did the selected item for a menu exist when the form was registered (detect if hacker changed a value or added their own)
        # ensure hidden input values have not been hacked
        if( isset( $this->form_val_arr[ $user_ID ][ $form_id ] ) ) {
            
            // loop through all registered inputs
            foreach( $this->form_val_arr[ $user_ID ][ $form_id ] as $input_name => $input_array ) {
                
                // checkboxes and radio do not get this test, the test later for specific registered values covers the security
                if( !in_array( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['inputtype'], $this->grouped_fields ) ) {
  
                    // $_POST missing - is the current input meant to be enabled (would be in $_POST)
                    if( isset( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['disabled'] ) && $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['disabled'] === false ) {
       
                        // did a hack disable the input (it is in $_POST when it should not be)
                        if( !isset( $_POST[ $input_name ] ) ) {   
                            $this->UI->create_notice( __( 'Possible security violation. Please discontinue your action. This event has been logged for investigation.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                            return false;                        
                        } 
                    }
                }
                                
                // check if hacker added new item to menu
                if( $input_array['inputtype'] == 'menu' ) {
                    
                    // loop through the registered items and find match
                    $found_match = false;
                    $i = 0;
                    foreach( $input_array['itemsarray'] as $item_value => $item_title ) {  
                        if( isset( $_POST[ $input_name ] ) && $item_value == $_POST[ $input_name ] ) {
                            // submitted $_POST value was pre-registered (not added by hacker)
                            $found_match = true;
                        }    
                        ++$i;
                    } 
                    
                    // if no match, the submitted item value was not registered when the form was parsed
                    if( $found_match === false ) {
                        $this->UI->create_notice( __( 'CSV 2 POST has security related concerns about a menu on the form you submitted. Please discontinue your action and contact support. This event has been logged for investigation.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                        return false;    
                    }
                }                
                
                // checkboxes - check if hacker added new checkbox, the purpose is not to ensure user made selection
                // for a required field. The purpose is to ensure not one of the values was edited. 
                if( $input_array['inputtype'] == 'checkboxes' ) {
                    
                    // if checkboxes are not grouped a different security process is required
                    if( isset( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['groupcheckboxes'] ) && $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['groupcheckboxes'] == false ) {
                        
                        // loop through the registered items and find match
                        $failed_to_match = false;
                        $i = 0;
                        foreach( $input_array['itemsarray'] as $item_value => $item_title ) {  
                            if( isset( $_POST[ $input_array['inputid'] . $i ] ) && $item_value != $_POST[ $input_array['inputid'] . $i ] ) {
                                $failed_to_match = true;  
                            }    
                            ++$i;
                        } 
                        
                        // if one or more seletions made but no match this indicates values have been tampered with
                        if( $failed_to_match === true ) {
                            $this->UI->create_notice( __( 'WTG CSV Exporter security has detected a possible problem with the form you submitted. Please discontinue your action and contact support.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                            return false;    
                        }
                        
                    } elseif( !isset( $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['groupcheckboxes'] ) || $this->form_val_arr[ $user_ID ][ $form_id ][ $input_name ]['groupcheckboxes'] === true ) {
                     
                        // ensure array exists - user may not have checked a single box
                        if( isset( $_POST[ $input_name ] ) && is_array( $_POST[ $input_name ] ) ) {
                            // loop through the submitted array and ensure all values are registered
                            $failed_to_match = false;
                            $i = 0;
                            
                            foreach( $_POST[ $input_name ] as $submitted_value ) {
                                if( !in_array( $submitted_value, $input_array['itemsarray'] ) ) {
                                    $failed_to_match = true;
                                }
                                ++$i;       
                            }
            
                            // if one or more seletions made but no match this indicates values have been tampered with
                            if( $failed_to_match === true ) {
                                $this->UI->create_notice( __( 'WTG CSV Exporter detected problem relating to the security of the form you submitted. Please discuss this on the WebTechGlobal Forum.', 'csv2post' ), 'error', 'Small', __( 'Something Went Wrong', 'csv2post' ) );                    
                                return false;    
                            }
                        }
                    }    
                }
                
                // check if hacker added new radio
                if( $input_array['inputtype'] == 'radiogroup' ) {
                    
                    // loop through the registered items and find match
                    $found_match = false;
                    $i = 0;
                    foreach( $input_array['itemsarray'] as $item_value => $item_title ) {  
                        if( isset( $_POST[ $input_name ] ) && $item_value == $_POST[ $input_name ] ) {
                            $found_match = true;
                        }    
                        ++$i;
                    } 
                    
                    // if no match, the submitted item value was not registered when the form was parsed
                    if( $found_match === false ) {
                        $this->UI->create_notice( __( 'CSV 2 POST detected a problem and could not continue. Please try again and if it continues contact support.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                        return false;    
                    }
                }               
                
                // hidden input texting - skip if not a hidden input
                if( $input_array['inputtype'] == 'hidden' ) {
     
                    if( !isset( $_POST[ $input_name ] ) ) {
                       // arriving here indicates hidden input missing, a hack has removed it
                        $this->UI->create_notice( __( 'A security matter has arisen. Please discontinue your action and contact support if this message continues to appear. The event has been logged.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                        return false;                         
                    } else {
                        // if registered value does not match value submitted, hacker has changed it
                        if( $input_array['currentvalue'] !== $_POST[ $input_name ] ) {
                            $this->UI->create_notice( __( 'CSV 2 POST has detected a possible hack attempt. Your activity has been logged. Please discontinue your action and contact support if this message continues to appear without any known reason.', 'csv2post' ), 'warning', 'Small', __( 'Warning', 'csv2post' ) );                    
                            return false;                            
                        }
                    }
                }   
                             
            }
        }
                    
        # did all checked boxes exist when the form was parsed (if one exists that did not, hacker added one)
        
        return $result;
    }
    
    /**
    * Overall form security
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function apply_form_security() {
        $result = true;

        # if form ID value does not exist FAIL - this is used to access form registration data
        if( !isset( $_POST['csv2post_form_formid'] ) ) { 
            return false;
        }
                
        # possible move nonce check to here, where it is may be better as it is before even this but not 100% sure
        
        # detect extra fields (not registered), unless form is not registered at all then we do not do this
        
        # check capability and ensure user submitting form still has permission (what if permission is revoked after form loads?)
            //  task has been created for this, form registation needs to begin earlier, before actual inputs so that
            // capability is registered then available in registration array  
                
        return $result;    
    }

    /**
    * Returns array of validation methods to be applied to input value
    * 
    * @returns optional array (human rea
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function get_input_validation_methods_array( $formid, $name, $return = 'validationarray' ) {
               
        // get the form validation array
        $this->form_val_arr = get_option( 'csv2post_formvalidation' );  
                
        $user_ID = get_current_user_id();

        // for now I do not want to assume the input ID will exist, when confident in the system this could be
        // removed and the security enforced further.
        if( isset( $this->form_val_arr[ $user_ID ][ $formid ][ $name ] ) ) {
            
            if( $return === 'validationarray' ) {    
                return $this->form_val_arr[ $user_ID ][ $formid ][ $name ]['validationarray']; 
            } elseif( $return === 'array' ) {       
                return $this->form_val_arr[ $user_ID ][ $formid ][ $name ]; 
            } else {                          
                return $this->form_val_arr[ $user_ID ][ $formid ][ $name ]['validationarray'];
            } 
        }
        
        return false;   
    }
    
    /**
    * standard notice generated indicating no permission if no capability exists in users data
    * @returns boolean false if that is the case, else returns true
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0.2
    */    
    public function user_can( $capability = 'activate_plugins' ){
        if(!current_user_can( $capability ) ){
            $this->UI->create_notice( __( 'Your request was rejected as your account does not have the required permission.', 'csv2post' ), 'success', 'Small', __( 'Not Permitted', 'csv2post' ) );          
            return false;
        }   
        return true;
    }     

    /**
    * Add hidden form fields, to help with processing and debugging
    * Adds the _form_processing_required value, required to call the form validation file
    * 
    * @todo each hidden input should be registered
    */
    public function form_start( $form_id, $form_name, $form_title, $uploader = false ){
        global $c2p_page_name;
        
        $form_name = strtolower( $form_name ); 
        $form_id = strtolower( $form_id );
        
        // register the form 
        self::register_form( $form_id );
        
        $enctype = '';
        if( $uploader ) {
            $enctype = ' enctype="multipart/form-data"';
        }
        
        // begin building the start of form and add standard hidden inputs
        echo '<form' . $enctype . ' method="post" name="' . $form_name . '" id="' . $form_id . '" action="' . $this->PHP->currenturl() . '">';
        
        // add WP nonce
        wp_nonce_field( $form_name ); 

        // add packages hidden inputs (mostly part of security system)
        echo '<input type="hidden" name="csv2post_admin_action" value="true">';
        echo '<input type="hidden" name="csv2post_hidden_pagename" value="' . $c2p_page_name . '">';
        echo '<input type="hidden" name="csv2post_form_formid" value="' . $form_name . '">';
        echo '<input type="hidden" name="csv2post_form_name" value="' . $form_name . '">';
        echo '<input type="hidden" name="csv2post_form_title" value="' . $form_title . '">';        
    }
    
    /**
    * Function to aid the building of a validation array
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validation_array( $alpha = false, $alphanumeric = false, $numeric = false, $url = false ) {
        $v = array();
        
        // alpha or alphanumeric or numeric 
        if( $alpha ){ $v[] = 'alpha'; }
        elseif( $alphanumeric ){ $v[] = 'alphanumeric'; }
        elseif( $numeric ){ $v[] = 'numeric'; }
        
        // all others
        if( $url ){ $v[] = 'url'; }  
        
        return $v;
    }
    
    /**
    * Function aids the building of the parameters array
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function optionalparameters_array( $alternativetext = null, $defaultvalue = null, $cols = null, $rows = null, $defaultitem_name = null, $defaultitem_value = null, $appendtext = null, $class = null, $readonly = null, $disabled = null, $instructions = null, $checkon = null, $labelone = null, $labeltwo = null, $itemsarray = null, $minimumchecks = null, $maximumchecks = null, $html5 = null, $ajax = null ) {
        $o = array();
        
        if( $alternativetext !== null ) { $o['alternativetext'] = $alternativetext; }
        
        if( $defaultvalue !== null ) { $o['defaultvalue'] = $defaultvalue; }
        
        if( is_numeric( $cols ) ) { $o['cols'] = $cols; }
        
        if( is_numeric( $rows ) ) { $o['rows'] = $rows; }
        
        if( $defaultitem_name !== null ) { $o['defaultitem_name'] = $defaultitem_name; }
        
        if( $defaultitem_value !== null ) { $o['defaultitem_value'] = $defaultitem_value; }
        
        if( $appendtext !== null ) { $o['appendtext'] = $appendtext; }
        
        if( $class !== null ) { $o['class'] = $class; }
        
        if( $readonly !== null && $readonly !== false ) { $o['readonly'] = true; }
        
        if( $disabled !== null && $disabled !== false ) { $o['disabled'] = true; }
        
        if( $instructions !== null ) { $o['instructions'] = $instructions; }
        
        if( $checkon !== null && $checkon !== false ) { $o['checkon'] = true; }
        
        if( $labelone !== null ) { $o['labelone'] = $labelone; }
        
        if( $labeltwo !== null ) { $o['labeltwo'] = $labeltwo; }
        
        if( $itemsarray !== null ) { $o['itemsarray'] = $itemsarray; }
        
        if( is_numeric( $minimumchecks ) ) { $o['minimumchecks'] = $minimumchecks; }
        
        if( is_numeric( $maximumchecks ) ) { $o['maximumchecks'] = $maximumchecks; }
        
        if( $html5 !== null && $html5 !== false ) { $o['html5'] = true; }
        
        if( $ajax !== null && $ajax !== false ) { $o['ajax'] = true; }
        
        return $o;  
    }
    
    ##########################################################
    #                                                        #
    #                  VALIDATION FUNCTIONS                  #
    #                                                        #
    ##########################################################
    
    /**
    * Requires string to be alphabetic characters only (not alpha numeric or special characters letters only)
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    * 
    * @param mixed $value
    * @param mixed $optiontitle
    * @param mixed $atts_array not currently in use
    * 
    * @todo add support for sentence
    */
    public function validate_alpha( $value, $optiontitle, $atts_array ) {
        if( !ctype_alpha( $value ) ) {
            $this->UI->create_notice( sprintf( __( "The %s input is only for letters, please edit your entry and remove any numbers or special characters.", 'csv2post' ), $optiontitle ), 'error', 'Small', __( 'Invalid Entry', 'csv2post' ) );               
            return false;            
        }
        return true;
    }
             
    /**
    * Confirms if a value is alphanumeric or not (must always return boolean)
    * 
    * @returns boolean but also generates notice
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validate_alphanumeric( $value, $optiontitle ) {
        if( !ctype_alnum( $value ) ) {   
            $this->UI->create_notice( sprintf( __( "The %s input is for letters or numbers, no special characters allowed.", 'csv2post' ), $optiontitle ), 'error', 'Small', __( 'Invalid Entry', 'csv2post' ) );               
            return false;
        }
        return true;
    }
    
    /**
    * Confirms value is a URL or not
    * 
    * @returns boolean but also generates notice
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validate_url( $url, $optiontitle ) {    
        if( !$this->PHP->validate_url( $url ) ) {
            $this->UI->create_notice(sprintf( __( "You did not enter a valid URL in the %s input. Please ensure that all characters match the online URL.", 'csv2post' ), $optiontitle ), 'error', 'Small', __( 'Invalid Entry', 'csv2post' ) );               
            return false;
        }        
        return true;
    } 
    
    /**
    * Checks if string is numeric only (no alpha or special characters allowed)
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validate_numeric( $value, $optiontitle ) {  
        if( !ctype_digit( $value ) ) {
            $this->UI->create_notice( sprintf( __( "Only numbers are meant to be entered into the %s field.", 'csv2post' ), $optiontitle ), 'error', 'Small', __( 'Invalid Entry', 'csv2post' ) );               
            return false;
        }        
        return true;    
    }
    
    /**
    * Ensure submitted input is no longer than giving length
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    * 
    * @param mixed $str
    * @param mixed $optiontitle
    * @param mixed $atts_mixed currently pass an integer (other functions can take an array or string)
    */
    public function validate_maxlength( $str, $optiontitle, $atts_mixed ) {
        if ( strlen( $str ) > $atts_mixed ) {
            $this->UI->create_notice( sprintf( __( "The maximum length for the %s field is %s ", 'csv2post' ), $optiontitle, $atts_mixed ), 'error', 'Small', __( 'Too Long', 'csv2post' ) );               
            return false;
        }        
        return true;        
    }    
    
    /**
    * Ensure submitted input is no shorter than giving length
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    * 
    * @param mixed $str
    * @param mixed $optiontitle
    * @param mixed $atts_mixed currently pass an integer (other functions can take an array or string)
    */
    public function validate_minlength( $str, $optiontitle, $atts_mixed ) {
        if ( strlen( $str ) < $atts_mixed ) {
            $this->UI->create_notice( sprintf( __( "The minimum length for the %s field is %s ", 'csv2post' ), $optiontitle, $atts_mixed ), 'error', 'Small', __( 'Too Short', 'csv2post' ) );               
            return false;
        }        
        return true;        
    }
    
    /**
    * Match regex.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function validate_regex( $str, $optiontitle, $atts_mixed ) {
        if ( !preg_match( $atts_mixed[0], $str ) ) {
            $this->UI->create_notice( sprintf( __( "The %s field entry is not the correct format. A specific pattern of characters is required i.e. %s.", 'csv2post' ), $optiontitle, $atts_mixed[1] ), 'error', 'Small', __( 'Invalid Value', 'csv2post' ) );               
            return false;
        }        
        return true;    
    }
    
    ##########################################################
    #                                                        #
    #                     INPUT FUNCTIONS                    #
    #                                                        #
    ##########################################################
    
    /*
    * use in options table to add a line of text like a separator 
    * 
    * @param mixed $secondfield
    * @param mixed $firstfield
    *
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_subline( $secondfield, $firstfield = '' ){?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><?php echo $firstfield;?></th>
            <td><?php echo $secondfield;?></td>
        </tr>
        <!-- Option End --><?php     
    } 
    
    /**
    * add text input to WordPress style form which is tabled and has optional HTML5 support
    * 
    * 1. the $capability value is set systematically, by default it is 'active_plugins' so minimum use is fine, it
    * is also not required if forms are being hidden from users who shouldnt see them. The security is there as a 
    * precation from hack (hacker manages to access page form is on) or developer mistake (they open up the page or form
    * to the wrong users in another method, hopefully this catches the user at submission point)
    * 
    * @param string $title
    * @param string $name - html name
    * @param string $id - html id
    * @param string|numeric $current_value
    * @param boolean $readonly 
    * @param string $class
    * @param string $append_text
    * @param boolean $left_field
    * @param string $right_field_content
    * @param boolean $required
    * @param string $validation - appends 'input_validation_' to call a function that validates, so any function and validation method can be setup 
    * 
    * @deprecated use class-forms.php      
    */
    public function input_text(){
        
        $readonly = '';
        $disabled = '';
        $appendtext = '';
        
        if( isset( $this->readonly ) && $this->readonly === true ){ $readonly = ' readonly'; }
        if( isset( $this->disabled ) && $this->disabled === true ){ $disabled = ' disabled';}
        if( isset( $this->appendtext ) && $this->appendtext === true ){ $appendtext = $this->appendtext; }
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row">
            
                <?php echo $this->optiontitle; ?>
            
                <?php 
                // if required add asterix ( "required" also added to input to make use of HTML5 control")
                if( $this->required === true ){
                    echo '<abbr class="req" title="required">*</abbr>';
                }
                ?>
                            
            </td>
            <td>

                <input type="text" name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>" value="<?php echo $this->currentvalue;?>" title="<?php echo $this->titleatt; ?>"<?php echo $readonly;?><?php echo $this->required;?><?php echo $disabled; ?>> 
                
                <?php echo $appendtext;?>

            </td>
        </tr>
        <!-- Option End --><?php 
    }   
    
    /**
    * Creates a hidden input, registered like all other inputs and security applied during submission to prevent hacking.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_hidden(){
        $disabled = '';
        if( isset( $this->disabled ) && $this->disabled === true ) { $disabled = ' disabled'; }
        ?>
        <input type="hidden" name="<?php echo $this->inputname; ?>" id="<?php echo $this->inputid; ?>" value="<?php echo $this->currentvalue;?>"<?php echo $disabled; ?>><?php     
    }
    
    /**
    * table row with two choice radio group styled by WordPress and used for switch type settings
    * 
    * $current_value should be enabled or disabled, use another method and do not change this if you need other values
    *     
    * @param mixed $title
    * @param mixed $name
    * @param mixed $id
    * @param mixed $current_value
    * @param string $default pass enabled or disabled depending on the softwares default state
    */
    public function input_switch(){
        
        if( !isset( $this->defaultvalue ) ) {
            $defaultvalue = 'disabled';
        } elseif( $this->defaultvalue != 'enabled' && $this->defaultvalue != 'disabled' ){
            $defaultvalue = 'disabled';
        }
             
        // only enabled and disabled is allowed for the switch, do not change this, create a new method for a different approach
        if( isset( $this->currentvalue ) ) {
            if( $this->currentvalue != 'enabled' && $this->currentvalue != 'disabled' ){
                $this->currentvalue = $this->defaultvalue;
            }    
        }
        
        // set disabled state
        $disabled = '';
        if( isset( $this->disabled ) && $this->disabled === true ) {
            $disabled = ' disabled';
        }
        ?>
    
        <!-- Option Start -->
        <tr valign="top">
            <th scope="row"><?php _e( $this->optiontitle, 'csv2post' ); ?></th>
            <td>
                <fieldset<?php echo $disabled; ?>><legend class="screen-reader-text"><span><?php echo $this->optiontitle; ?></span></legend>
                    <input type="radio" id="<?php echo $this->inputname;?>_enabled" name="<?php echo $this->inputname;?>" value="enabled" <?php echo self::is_checked( $this->currentvalue, 'enabled', 'return' );?> />
                    <label for="<?php echo $this->inputname;?>_enabled"> <?php echo $this->first_switch_label; ?></label>
                    <br />
                    <input type="radio" id="<?php echo $this->inputname;?>_disabled" name="<?php echo $this->inputname;?>" value="disabled" <?php echo self::is_checked( $this->currentvalue, 'disabled', 'return' );?> />
                    <label for="<?php echo $this->inputname;?>_disabled"> <?php echo $this->second_switch_label; ?></label>
                </fieldset>
            </td>
        </tr>
        <!-- Option End -->
                            
    <?php  
    }  
    
    /**
    * Basic radiogroup input.
    * 
    * @param mixed $title
    * @param mixed $id
    * @param mixed $name
    * @param mixed $radio_array
    * @param mixed $current
    * @param mixed $default
    * @param mixed $validation
    */
    public function input_radiogroup(){
        echo '
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row">' . $this->optiontitle . '</th>
            <td><fieldset>';
            
            echo '<legend class="screen-reader-text"><span>' . $this->optiontitle . '</span></legend>';
            
            $default_set = false;

            $items_count = count( $this->itemsarray );
            
            $itemsapplied = 0;
            
            $i = 0;
            
            foreach( $this->itemsarray as $item_value => $item_title ){
                ++$itemsapplied;
                    
                // determine if this option is the currently stored one
                $checked = '';
                if( $this->currentvalue === $item_value ){
                    $default_set = true;
                    $checked = ' checked';
                }elseif( $this->currentvalue == 'nocurrent123' && $default_set == false ){
                    $default_set = true;
                    $checked = ' checked';
                }
                
                // set the current to that just submitted
                if( isset( $_POST[ $this->inputname ] ) && $_POST[ $this->inputname ] == $item_value ){
                    $default_set = true;
                    $checked = ' checked';                
                }
                
                // check current item is not current giving or current = '' 
                if( is_null( $this->currentvalue ) || $this->currentvalue == 'nocurrent123' ){
                    if( $this->default == $item_value ){
                        $default_set = true;
                        $checked = ' checked';                
                    } 
                }
                
                // if on last option and no current set then check last item
                if( $default_set == false && $items_count == $itemsapplied){
                    $default_set = true;
                    $checked = ' checked';                
                }                
                ?>
                      
                <input type="radio" id="<?php echo $this->inputid . $i; ?>" name="<?php echo $this->inputid; ?>" value="<?php echo $item_value; ?>"<?php echo $checked;?><?php if( isset( $this->disabled ) && $this->disabled === true ){ echo ' disabled';} ?>/>
                <label for="<?php echo $this->inputid . $i; ?>"><?php echo $item_title; ?></label>
                <br />
                
                <?php  
                ++$i;
            }
            
        echo '</fieldset>
            </td>
        </tr>
        <!-- Option End -->';                 
    }
    
    /**
    * HTML form textarea with WordPress table structure.
    * 
    * @param mixed $title
    * @param mixed $id
    * @param mixed $name
    * @param mixed $rows
    * @param mixed $cols
    * @param mixed $current_value
    * @param mixed $required
    * @param mixed $validation
    */
    public function input_textarea(){
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row">
            
                <?php echo $this->optiontitle;
                 
                // if required add asterix ( "required" also added to input to make use of HTML5 control")
                if( $this->required ){
                    echo '<abbr class="req" title="required">*</abbr>';
                }?>
                
                </th>
            <td>
                <textarea id="<?php echo $this->inputid;?>" name="<?php echo $this->inputname;?>" rows="<?php echo $this->rows;?>" cols="<?php echo $this->cols;?>"<?php if( isset( $this->required ) && $this->required === true ){echo ' required';}?><?php if( isset( $this->disabled ) && $this->disabled === true ){ echo ' disabled';} ?>><?php echo $this->currentvalue;?></textarea>
            </td>
        </tr>
        <!-- Option End --><?php     
    }
    
    /**
    * Basic form menu within table row.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.2
    */
    public function input_menu(){
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><label for="<?php echo $this->inputid; ?>"><?php echo $this->optiontitle; ?></label></th>
            <td>            
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( isset( $this->disabled ) && $this->disabled === true ){ echo ' disabled'; } ?>>
                    <?php
                    // apply default item - it should be selected on a form that has never been used
                    // we will add default item if just one part of it has been set
                    if( isset( $this->defaultitem_name ) || isset( $this->defaultitem_value ) ) {
                        
                        // set the visible item name
                        if( !isset( $this->defaultitem_name ) ) {
                            $def_item_name = __( 'Not Selected', 'wtgportalmanager' );
                        } else {
                            $def_item_name = $this->defaultitem_name;
                        }   
                        
                        // set the item value
                        if( !isset( $this->defaultitem_value ) ) {
                            $def_item_value = __( 'notselected123', 'wtgportalmanager' );
                        } else {
                            $def_item_value = $this->defaultitem_value;
                        } 
                        
                        echo '<option selected="selected" value="' . $def_item_value . '">' . $def_item_name . '</option>';
                    }
                    
                                
                    foreach( $this->itemsarray as $key => $option_title ){
                        
                        $selected = '';
                        if( $key == $this->currentvalue ){
                            $selected = 'selected="selected"';
                        } 
                        
                        echo '<option ' . $selected . ' value="' . $key . '">' . $option_title . '</option>';    
                    }
                    ?>
                </select>                  
            </td>
        </tr>
        <!-- Option End --><?php          
    }   
    
    /**
    * Creates one or more checkboxes. Pass an array using $this->currentvalue to put boxes on. 
    * 
    * wrap in <fieldset><legend class="screen-reader-text"><span>Membership</span></legend></fieldset>
    * 
    * @param mixed $label
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */
    public function input_checkboxes(){
        
        $disabled = '';
        if( isset( $this->disabled ) && $this->disabled === true ){ 
            $disabled = ' disabled';
        } 
        ?>
        
        <!-- Option Start -->
        <tr valign="top">
            <th scope="row"><?php echo $this->optiontitle;?></th>
            <td>
            
            <?php  
            $first = true;
            $i = 0;
            foreach( $this->itemsarray as $item_value => $item_title ) {
                
                // new line
                if( !$first ) {
                    echo '<br><br>';    
                }
                
                // set check/on condition using $this->currentvalue (pass array rather than a string)
                // if a value is in the $this->currentvalue array then it is on/checked
                $checked = '';      
                if( in_array( $item_value, $this->currentvalue ) ) {
                    $checked = ' checked';   
                }
                
                // we group checks by default
                $inputname = $this->inputid . '[]';
                if( !isset( $this->groupcheckboxes ) || $this->groupcheckboxes === false ) {
                    $inputname = $this->inputid . $i;
                }
                 
                echo '<label for="' . $this->inputid . $i . '"><input type="checkbox" name="' . $inputname . '" id="' . $this->inputid . $i . '" value="' . $item_value . '"' .  $checked . $disabled . '>' . $item_title . '</label>';
                $first = false;
                ++$i;
            }
            ?>
            
            </td>
        </tr>
        <!-- Option End --><?php     
    }

    /**
    * a standard menu of users wrapped in <td> 
    */
    public function input_menu_users(){
        $blogusers = get_users( 'blog_id=1&orderby=nicename' );?>

        <!-- Option Start -->
        <tr valign="top">
            <th scope="row"><?php echo $this->optiontitle;?></th>
            <td>                                     
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( $this->disabled ){ echo ' disabled';} ?>>
            
                    <?php        
                    $selected = '';                    
                    ?>
                    
                    <option value="notselected" <?php echo $selected;?>><?php _e( 'None Selected', 'csv2post' ); ?></option> 
                                                    
                    <?php         
                    foreach ( $blogusers as $user ){ 

                        // apply selected value to current save
                        $selected = '';
                        if( $this->currentvalue == $user->ID ) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option value="' . $user->ID . '" ' . $selected . '>' . $user->ID . ' - ' . $user->display_name . '</option>'; 
                    }?>
                                                                                                                                                                 
                </select>  
            </td>
        </tr>
        <!-- Option End --><?php 
    } 
    
    /**
    * a standard menu of categories wrapped in <td> 
    */
    public function input_menu_categories(){    
        $cats = get_categories( 'hide_empty=0&echo=0&show_option_none=&style=none&title_li=' );?>

        <!-- Option Start -->
        <tr valign="top">
            <th scope="row"><?php echo $this->optiontitle;?></th>
            <td>                                     
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( $this->disabled ){ echo ' disabled';} ?>>
            
                    <?php        
                    $selected = '';                    
                    ?>
                    
                    <option value="notselected" <?php echo $selected;?>><?php _e( 'None Selected', 'csv2post' ); ?></option> 

                    <?php         
                    foreach( $cats as $c ){ 
                        
                        // apply selected value to current save
                        $selected = '';
                        if( $this->currentvalue == $c->term_id ) {
                            $selected = 'selected="selected"';
                        }
                        
                        echo '<option value="' . $c->term_id . '" ' . $selected . '>' . $c->term_id . ' - ' . $c->name .'</option>'; 
                    }?>
                                                                                                                                                                 
                </select>  
            </td>
        </tr>
        <!-- Option End --><?php 
    } 
    
    /**
    * radio group of post types wrapped in <tr>
    * 
    * @param string $title
    * @param string $name
    * @param string $id
    * @param string $current_value
    */
    public function input_radiogroup_posttypes(){ 
        
        // apply disabled status
        $disabled = '';
        if( $this->disabled ){ $disabled = ' disabled="disabled"';}
         
        echo '
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row">' . $this->optiontitle . '</th>
            <td><fieldset' . $disabled . '>';
            
            echo '<legend class="screen-reader-text"><span>' . $this->optiontitle . '</span></legend>';
            
            $post_types = get_post_types( '', 'names' );
            $current_applied = false;        
            $i = 0; 
            foreach( $post_types as $post_type ){
                
                // dont add "post" as it is added last so that it can be displayed as current default when required
                if( $post_type != 'post' ){
                    $checked = '';
                    
                    if( $post_type == $this->currentvalue){
                        $checked = 'checked="checked"';
                        $current_applied = true;    
                    }elseif( $this->currentvalue == 'nocurrent123' && $current_applied == false ){
                        $checked = 'checked="checked"';
                        $current_applied = true;                        
                    }
                    
                    echo '<input type="radio" name="' . $this->inputname . '" id="' . $this->inputid . $i . '" value="' . $post_type . '" ' . $checked . ' />
                    <label for="' . $this->inputid . $i . '"> ' . $post_type . '</label><br>';
    
                    ++$i;
                }
            }
            
            // add post last, if none of the previous post types are the default, then we display this as default as it would be in WordPress
            $post_default = '';
            if(!$current_applied){
                $post_default = 'checked="checked"';            
            }
            
            echo '<input type="radio" name="' . $this->inputname . '" id="' . $this->inputid . $i . '" value="post" ' . $post_default . ' />
            <label for="' . $this->inputid . $i . '">post</label>';
                    
        echo '</fieldset>
            </td>
        </tr>
        <!-- Option End -->';
    } 
    
    /**
    * Radio group of post formats wrapped in table.
    * 
    * @param mixed $title
    * @param mixed $name
    * @param mixed $id
    * @param mixed $current_value
    * @param mixed $validation
    */
    public function input_radiogroup_postformats(){  
    
        // apply disabled status
        $disabled = '';
        if( $this->disabled ){ $disabled = ' disabled="disabled"';}
                
        echo '
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row">' . $this->optiontitle . '</th>
            <td><fieldset' . $disabled . '>';
            
            echo '<legend class="screen-reader-text"><span>' . $this->optiontitle . '</span></legend>';
      
            $optionchecked = false;     
            $post_formats = get_theme_support( 'post-formats' );
            if ( is_array( $post_formats[0] ) ) {
                
                $i = 0;
                
                foreach( $post_formats[0] as $key => $format ){
                    
                    $statuschecked = '';
                    if( $this->currentvalue === $format ){
                        $optionchecked = true;
                        $statuschecked = ' checked="checked" ';    
                    }
                                   
                    echo '<input type="radio" id="' . $this->inputid . $i . '" name="' . $this->inputname . '" value="' . $format . '"' . $statuschecked . '/>
                    <label for="' . $this->inputid . $i . '">' . $format . '</label><br>';
                    ++$i; 
                }
                
                if(!$optionchecked){$statuschecked = ' checked="checked" ';}
                
                echo '<input type="radio" id="' . $this->inputid . $i . '" name="' . $this->inputname . '" value="standard"' . $statuschecked . '/>
                <label for="' . $this->inputid . $i . '">standard (default)</label>';               
                    
            }            
                    
        echo '</fieldset>
            </td>
        </tr>
        <!-- Option End -->';
    }    

    /**
    * Add a file uploader to a form
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    * 
    * @param string $title
    * @param string $name
    * @param string $id
    * @param string $validation - pass name of a custom validation function 
    */
    public function input_file(){?>         
        <tr valign="top">
            <th scope="row"><?php echo $this->optiontitle;?></th>
            <td>
                <input type="file" name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( isset( $this->disabled ) && $this->disabled === true ){ echo ' disabled';} ?>> 
            </td>
        </tr><?php 
    }      
    
    /**
    * A table row with menu of all WordPress capabilities
    * 
    * @param mixed $title
    * @param mixed $id
    * @param mixed $name
    * @param mixed $current
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_menu_capabilities(){
        global $wp_roles; 
        $capabilities_array = $this->WPCore->capabilities();
        
        // put into alphabetical order as it is a long list 
        ksort( $capabilities_array );
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><label for="<?php echo $this->inputid; ?>"><?php echo $this->optiontitle; ?></label></th>
            <td>            
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( $this->disabled ){ echo ' disabled';} ?>>
                    <?php            
                    foreach( $capabilities_array as $key => $cap ){
                        $selected = '';
                        if( $key == $this->currentvalue ){
                            $selected = 'selected="selected"';
                        } 
                        echo '<option value="' . $key . '" ' . $selected . '>' . $key . '</option>';    
                    }
                    ?>
                </select>                  
            </td>
        </tr>
        <!-- Option End --><?php          
    }

    /**
    * Menu of CRON schedules, I have named them cron repeats because "schedule" is used
    * methods very often.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_menu_cronrepeat() {
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><label for="<?php echo $this->inputid; ?>"><?php echo $this->optiontitle; ?></label></th>
            <td>            
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( $this->disabled ){ echo ' disabled';} ?>>
                    <option value="hourly" <?php if( $this->currentvalue == 'hourly' ){ echo $selected; }?>><?php _e( 'Hourly', 'csv2post' ); ?></option>
                    <option value="twicedaily" <?php if( $this->currentvalue == 'twicedaily' ){ echo $selected; }?>><?php _e( 'Twice Daily', 'csv2post' ); ?></option>
                    <option value="daily" <?php if( $this->currentvalue == 'daily' ){ echo $selected; }?>><?php _e( 'Daily', 'csv2post' ); ?></option>
                </select>                  
            </td>
        </tr>
        <!-- Option End --><?php     
    }
    
    /**
    * Menu of permitted CRON hooks.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_menu_cronhooks() {
        $selected = 'selected="selected"';
        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><label for="<?php echo $this->inputid; ?>"><?php echo $this->optiontitle; ?></label></th>
            <td>            
                <select name="<?php echo $this->inputname;?>" id="<?php echo $this->inputid;?>"<?php if( $this->disabled ){ echo ' disabled';} ?>>
                    <option value="eventcheckwpcron" <?php if( $this->currentvalue == 'eventcheckwpcron' ){ echo $selected; }?>>eventcheckwpcron</option>
                </select>                  
            </td>
        </tr>
        <!-- Option End --><?php     
    }
    
    /**
    * Set of text field inputs as used on Edit Post page.
    * 
    * Day, Month, Year, Hour and Minute inputs on a single row (taking from Edit Post screen)
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function input_dateandtime() {
        global $wp_locale;
                       
        // apply disabled status
        $disabled = '';
        if( isset( $this->disabled ) && $this->disabled === true ){ $disabled = ' disabled="disabled"';}
        
        if( !empty( $this->currentvalue ) && is_numeric( $this->currentvalue ) ) {
            $time_adj = $this->currentvalue;
        } else {        
            $time_adj = current_time('timestamp');
        }
        
        $jj = gmdate( 'd', $time_adj );
        $mm = gmdate( 'm', $time_adj );
        $aa = gmdate( 'Y', $time_adj );
        $hh = gmdate( 'H', $time_adj );
        $mn = gmdate( 'i', $time_adj );
        $ss = gmdate( 's', $time_adj );

        $month = '<select name="mm"' . $disabled . '>';
        
        for ( $i = 1; $i < 13; $i = $i +1 ) {
            $monthnum = zeroise($i, 2);
            $month .= "\t\t\t" . '<option value="' . $monthnum . '" ' . selected( $monthnum, $mm, false ) . '>';
            /* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
            $month .= sprintf( __( '%1$s-%2$s' ), $monthnum, $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) . "</option>\n";
        }
        $month .= '</select>';

        $day = '<input type="text" id="jj" name="jj" value="' . $jj . '" size="2" maxlength="2" autocomplete="off"' . $disabled . ' />';
        $year = '<input type="text" ="aa" name="aa" value="' . $aa . '" size="4" maxlength="4" autocomplete="off"' . $disabled . ' />';
        $hour = '<input type="text" id="hh" name="hh" value="' . $hh . '" size="2" maxlength="2" autocomplete="off"' . $disabled . ' />';
        $minute = '<input type="text" id="mn" name="mn" value="' . $mn . '" size="2" maxlength="2" autocomplete="off"' . $disabled . ' />';

        ?>
        <!-- Option Start -->        
        <tr valign="top">
            <th scope="row"><?php echo $this->optiontitle; ?></th>
            <td>            
                <?php  
                /* translators: 1: month, 2: day, 3: year, 4: hour, 5: minute */
                printf( __( '%1$s %2$s, %3$s @ %4$s : %5$s' ), $month, $day, $year, $hour, $minute );
                ?>                 
            </td>
        </tr>
        <!-- Option End --><?php              
    }    

    /**
    * Hidden input, basic parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function hidden_basic( $form_id, $id, $name, $current_value ) {
        self::input( $form_id, 'hidden', $id, $name, __( 'Hidden Input ' . $id, 'csv2post' ), __( 'Hidden Input ' . $id, 'csv2post' ), true, $current_value, array(), array() ); 
    }
    
    /**
    * Data and time - group of fields as used on Edit Post screen by WP.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function dateandtime_basic( $form_id, $id, $name, $title, $current_value = null, $required = false, $validation_array = array() ) {
        self::input( $form_id, 'dateandtime', $id, $name, $title, $title, $required, $current_value, array(), $validation_array );   
    }  
    
    /**
    * Text input with basic parameters for common requirements. 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function text_basic( $form_id, $id, $name, $title, $current_value = null, $required = false, $validation_array = array() ) {
        self::input( $form_id, 'text', $id, $name, $title, $title, $required, $current_value, array(), $validation_array );       
    }
     
    /**
    * Form menu with common parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function menu_basic( $form_id, $id, $name, $title, $items_array, $required = false, $current_value = '', $validation_array = array() ) {
        self::input( $form_id, 'menu', $id, $name, $title, $title, $required, $current_value, array( 'itemsarray' => $items_array ), $validation_array );
    }

    /**
    * File upload input with common parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function file_basic( $form_id, $id, $name, $title, $required = false, $validation_array = array() ) {
        self::input( $form_id, 'file', $id, $name, $title, $title, $required, '', array(), $validation_array );
    }
        
    /**
    * Radiogroup with common parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function radiogroup_basic( $form_id, $id, $name, $title, $items_array, $current_value, $required = false, $validation_array = array() ) {
        self::input( $form_id, 'radiogroup', $id, $name, $title, $title, $required, $current_value, array( 'itemsarray' => $items_array ), $validation_array );
    }
 
    /**
    * Checkboxes with common parameters.
    * 
    * 1. No minimum number of checks (use advanced instead)
    * 2. No maximum number of checks (use advanced instead)
    *  
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */
    public function checkboxes_basic( $form_id, $id, $name, $title, $items_array, $current_value, $required = false, $validation_array = array(), $groupcheckboxes = true ) {
        self::input( $form_id, 'checkboxes', $id, $name, $title, $title, $required, $current_value, array( 'groupcheckboxes' => $groupcheckboxes, 'itemsarray' => $items_array ), $validation_array );        
    }
   
    /**
    * Text area input with common parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function textarea_basic( $form_id, $id, $name, $title, $current_value = null, $required = false, $rows = 5, $cols = 5, $validation_array = array() ) {
        self::input( $form_id, 'textarea', $id, $name, $title, $title, $required, $current_value, array( 'rows' => $rows, 'cols' => $cols ), $validation_array );
    }

    /**
    * Switch configuration (two radios for switching between two states, modes)
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function switch_basic( $form_id, $id, $name, $title, $defaultvalue = 'disabled', $current_value = '', $required = false ) {
        self::input( $form_id, 'switch', $id, $name, $title, $title, $required, $current_value, array( 'defaultvalue' => 'disabled' ), array() );
    }

    /**
    * Hidden input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function hidden_advanced( $form_id, $id, $name, $title, $current_value ) {
        self::input( $form_id, 'hidden', $id, $name, $title, $title, true, $current_value, '', array(), array() );
    }

    /**
    * Data and time input group with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function dateandtime_advanced( $form_id, $id, $name, $title, $current_value = array(), $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'dateandtime', $id, $name, $title, $title, $required, $current_value, array( 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax ), $validation_array );    
    }

    /**
    * Text input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function text_advanced( $form_id, $id, $name, $title, $current_value = null, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'text', $id, $name, $title, $title, $required, $current_value, array( 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax ), $validation_array );    
    }

    /**
    * Menu input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function menu_advanced( $form_id, $id, $name, $title, $current_value = null, $items, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'menu', $id, $name, $title, $title, $required, $current_value, array( 'itemsarray' => $items, 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax ), $validation_array );    
    }

    /**
    * File upload input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function file_advanced( $form_id, $id, $name, $title, $current_value = null, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'file', $id, $name, $title, $title, $required, $current_value, array( 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax ), $validation_array );    
    }

    /**
    * Radiogroup input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function radiogroup_advanced( $form_id, $id, $name, $title, $current_value, $items, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'radiogroup', $id, $name, $title, $title, $required, $current_value, array( 'itemsarray' => $items, 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax, 'items' => array( 'itemone' => 'Item One', 'itemtwo' => 'Item Two' ) ), $validation_array );    
    }

    /**
    * Checkboxes input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.1
    */                                                                                                                                                                                                        
    public function checkboxes_advanced( $form_id, $id, $name, $title, $current_value, $items, $minimumchecks = 1, $maximumchecks = 100, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $groupcheckboxes = true ) {                    
        self::input( $form_id, 'checkboxes', $id, $name, $title, $title, $required, $current_value, array( 'groupcheckboxes' => $groupcheckboxes,'itemsarray' => $items, 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax, 'minimumchecks' => $minimumchecks, 'maximumchecks' => $maximumchecks ) );            
    }
    
    /**
    * Text area input with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function textarea_advanced( $form_id, $id, $name, $title, $current_value = null, $rows = 10, $cols = 10, $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'textarea', $id, $name, $title, $title, $required, $current_value, array( 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax, 'rows' => $rows, 'cols' => $cols), $validation_array ); 
    }

    /**
    * Switch setup (using two radios) with advanced parameters.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function switch_advanced( $form_id, $id, $name, $title, $current_value = 'disabled', $disabled = false, $required = false, $tabled = true, $html5 = true, $ajax = true, $validation_array = array() ) {
        self::input( $form_id, 'switch', $id, $name, $title, $title, $required, $current_value, array( 'disabled' => $disabled, 'tabled' => $tabled, 'html5' => $html5, 'ajax' => $ajax ), $validation_array );    
    }
    
    /**
    * Use to apply selected="selected" to HTML form menu
    * 
    * @param mixed $actual_value
    * @param mixed $item_value
    * @param mixed $output
    * @return mixed
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.1
    * @version 1.0
    */
    public function is_selected( $actual_value, $item_value, $output = 'return' ){
        if( $actual_value === $item_value){
            if( $output == 'return' ){
                return ' selected="selected"';
            }else{
                echo ' selected="selected"';
            }
        }else{
            if( $output == 'return' ){
                return '';
            }else{
                echo '';
            }
        }
    } 
    
    /**
    * returns "checked" for us in html form radio groups
    * 
    * @param mixed $actual_value
    * @param mixed $item_value
    * @param mixed $output
    * @return mixed
    */
    public function is_checked( $actual_value, $item_value, $output = 'return' ){
        if( $actual_value === $item_value){
            if( $output == 'return' ){
                return ' checked';
            }else{
                echo ' checked';
            }
        }else{
            if( $output == 'return' ){
                return '';
            }else{
                echo '';
            }
        }
    }               
}
?>