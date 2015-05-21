<?php
/**
 * old admin config file, this is being phased out 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$c2p_apisession_array = false;
$c2p_pub_set = array();
$c2p_pub_set['automation'] = 0;// 0 = off, 1 = on (controls automated background scripts, not CRON just page load triggered) 
?>
