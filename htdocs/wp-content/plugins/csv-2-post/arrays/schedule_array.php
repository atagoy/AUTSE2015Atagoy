<?php
/** 
 * Default schedule array for CSV 2 POST plugin 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$c2p_schedule_array = array();
// history
$c2p_schedule_array['history']['lastreturnreason'] = __( 'None', 'csv2post' );
$c2p_schedule_array['history']['lasteventtime'] = time();
$c2p_schedule_array['history']['lasteventtype'] = __( 'None', 'csv2post' );
$c2p_schedule_array['history']['day_lastreset'] = time();
$c2p_schedule_array['history']['hour_lastreset'] = time();
$c2p_schedule_array['history']['hourcounter'] = 1;
$c2p_schedule_array['history']['daycounter'] = 1;
$c2p_schedule_array['history']['lasteventaction'] = __( 'None', 'csv2post' );
// times/days
$c2p_schedule_array['days']['monday'] = true;
$c2p_schedule_array['days']['tuesday'] = true;
$c2p_schedule_array['days']['wednesday'] = true;
$c2p_schedule_array['days']['thursday'] = true;
$c2p_schedule_array['days']['friday'] = true;
$c2p_schedule_array['days']['saturday'] = true;
$c2p_schedule_array['days']['sunday'] = true;
// times/hours
$c2p_schedule_array['hours'][0] = true;
$c2p_schedule_array['hours'][1] = true;
$c2p_schedule_array['hours'][2] = true;
$c2p_schedule_array['hours'][3] = true;
$c2p_schedule_array['hours'][4] = true;
$c2p_schedule_array['hours'][5] = true;
$c2p_schedule_array['hours'][6] = true;
$c2p_schedule_array['hours'][7] = true;
$c2p_schedule_array['hours'][8] = true;
$c2p_schedule_array['hours'][9] = true;
$c2p_schedule_array['hours'][10] = true;
$c2p_schedule_array['hours'][11] = true;
$c2p_schedule_array['hours'][12] = true;
$c2p_schedule_array['hours'][13] = true;
$c2p_schedule_array['hours'][14] = true;
$c2p_schedule_array['hours'][15] = true;
$c2p_schedule_array['hours'][16] = true;
$c2p_schedule_array['hours'][17] = true;
$c2p_schedule_array['hours'][18] = true;
$c2p_schedule_array['hours'][19] = true;
$c2p_schedule_array['hours'][20] = true;
$c2p_schedule_array['hours'][21] = true;
$c2p_schedule_array['hours'][22] = true;
$c2p_schedule_array['hours'][23] = true;
// limits
$c2p_schedule_array['limits']['hour'] = '1000';
$c2p_schedule_array['limits']['day'] = '5000';
$c2p_schedule_array['limits']['session'] = '300';
// event types (update event_action() if adding more eventtypes)
// deleteuserswaiting - this is the auto deletion of new users who have not yet activated their account 
$c2p_schedule_array['eventtypes']['deleteuserswaiting']['name'] = __( 'Delete Users Waiting', 'csv2post' ); 
$c2p_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'disabled';
// send emails - rows are stored in wp_c2pmailing table for mass email campaigns 
$c2p_schedule_array['eventtypes']['sendemails']['name'] = __( 'Send Emails', 'csv2post' ); 
$c2p_schedule_array['eventtypes']['sendemails']['switch'] = 'disabled';    
?>