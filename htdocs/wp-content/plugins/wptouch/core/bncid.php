<?php

define( 'WPTOUCH_PRO_BNCAPI_PRODUCT_NAME', 'wptouch-pro-3' );
define( 'WPTOUCH_BNCID_CACHE_TIME', 3600 );

define( 'WPTOUCH_BNCID_DONT_CACHE', 0 );

function wptouch_has_license() {
	wptouch_check_api();

	$bnc_settings = wptouch_get_settings( 'bncid' );
	return $bnc_settings->license_accepted;
}

function wptouch_is_upgrade_available() {
	global $wptouch_pro;

	$upgrade_avail = $wptouch_pro->check_for_update();

	return $upgrade_avail;
}

function wptouch_get_available_cloud_themes() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	return $wptouch_pro->bnc_api->get_all_available_themes();;
}

function wptouch_get_available_cloud_addons() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	return $wptouch_pro->bnc_api->get_all_available_addons();
}

function wptouch_check_api() {
	global $wptouch_pro;
	$wptouch_pro->setup_bncapi();

	$bnc_settings = wptouch_get_settings( 'bncid' );

	$last_accepted = $bnc_settings->license_accepted;

	$current_time = time();
	if ( $current_time > $bnc_settings->next_update_check_time ) {
		$result = $wptouch_pro->bnc_api->check_api();

		if ( isset( $result[ 'has_valid_license' ] ) ) {
			if ( $result[ 'has_valid_license' ] ) {
				// The user HAS as valid license
				$bnc_settings->failures = 0;
				$bnc_settings->license_accepted = true;
				$bnc_settings->license_accepted_time = $current_time;
				$bnc_settings->license_expired = 0;
				$bnc_settings->license_expiry_date = 0;

				if ( $result[ 'license_expiry_date'] ) {
					$bnc_settings->license_expiry_date = $result[ 'license_expiry_date'];
				}

				// Check for the user's referral code
				if ( isset( $result[ 'user_id'] ) ) {
					$bnc_settings->referral_user_id = $result[ 'user_id' ];
				}				
			} else {
				$bnc_settings->license_accepted = false;	
				$bnc_settings->license_accepted_time = 0;

				if ( isset( $result[ 'license_expired' ] ) ) {
					$bnc_settings->license_expired = $result[ 'license_expired' ];	
				} else {
					$bnc_settings->license_expired = 0;
				}

				if ( isset( $result[ 'license_expiry_date' ] ) ) {
					$bnc_settings->license_expiry_date = $result[ 'license_expiry_date' ];
				} else {
					$bnc_settings->license_expiry_date = 0;
				}				
			}
		} 

		// Update next check time
		$bnc_settings->next_update_check_time = $current_time + WPTOUCH_API_CHECK_INTERVAL;
		$bnc_settings->save();		
	}

	// We've changed license states here, so clear our theme and add-on transients
	if ( $last_accepted != $bnc_settings->license_accepted ) {
		delete_transient( '_wptouch_available_cloud_addons' );
		delete_transient( '_wptouch_available_cloud_themes' );
	}
}

