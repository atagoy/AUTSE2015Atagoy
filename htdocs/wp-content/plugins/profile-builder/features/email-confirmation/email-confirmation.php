<?php
//functions needed for the email-confirmation on single-sites (to create the "_signups" table)
function wppb_signup_schema( $oldVal, $newVal ){
	// Declare these as global in case schema.php is included from a function.
	global $wpdb, $wp_queries, $charset_collate;

	if ($newVal['emailConfirmation'] == 'yes'){
		
		//The database character collate.
		$charset_collate = '';
		
		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET ".$wpdb->charset;
		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE ".$wpdb->collate;
		$tableName = $wpdb->prefix.'signups';

		$sql = "
			CREATE TABLE $tableName (
				domain varchar(200) NOT NULL default '',
				path varchar(100) NOT NULL default '',
				title longtext NOT NULL,
				user_login varchar(60) NOT NULL default '',
				user_email varchar(100) NOT NULL default '',
				registered datetime NOT NULL default '0000-00-00 00:00:00',
				activated datetime NOT NULL default '0000-00-00 00:00:00',
				active tinyint(1) NOT NULL default '0',
				activation_key varchar(50) NOT NULL default '',
				meta longtext,
				KEY activation_key (activation_key),
				KEY domain (domain)
			) $charset_collate;";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$res = dbDelta($sql);
	}
}
add_action( 'update_option_wppb_general_settings', 'wppb_signup_schema', 10, 2 );


//function to add new tab in the default WP userlisting with all the users who didn't confirm their account yet
function wppb_add_pending_users_header_script(){
?>
	<script type="text/javascript">	
		jQuery(document).ready(function() {
			jQuery.post( ajaxurl ,  { action:"wppb_get_unconfirmed_email_number"}, function(response) {
				jQuery('.wrap ul.subsubsub').append('<span id="separatorID"> |</span> <li class="listUsersWithUncofirmedEmail"><a class="unconfirmedEmailUsers" href="?page=unconfirmed_emails"><?php _e('Users with Unconfirmed Email Address', 'profilebuilder');?></a> <font id="unconfirmedEmailNo" color="grey">('+response.number+')</font></li>');	
			});			
		});
		
		function confirmECActionBulk( URL, message ) {
			if ( confirm(message) )
				window.location=URL;
		}
	
		// script to create a confirmation box for the user upon approving/unapproving a user
		function confirmECAction( URL, todo, user_email, actionText ) {
			actionText = '<?php _e( 'Do you want to', 'profilebuilder' ); ?>' + ' ' + actionText;
		
			if (confirm(actionText)) {
				jQuery.post( ajaxurl ,  { action:"wppb_handle_email_confirmation_cases", URL:URL, todo:todo, user_email:user_email}, function(response) {	
					if (jQuery.trim(response) == 'ok')
						window.location=URL;
						
					else
						alert( jQuery.trim(response) );
				});			
			}
		}
	</script>	
<?php
}

function wppb_get_unconfirmed_email_number(){
	global $wpdb;

    /* since version 2.0.7 for multisite we add a 'registered_for_blog_id' meta in the registration process
    so we can count only the users registered on that blog. Also for backwards compatibility we display the users that don't have that meta at all */
    $number_of_users = 0;
    $results = $wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."signups WHERE active = 0");
    foreach ($results as $result){
        if( !empty(  $result->meta ) ){
            $user_meta = maybe_unserialize( $result->meta );
            if( empty( $user_meta['registered_for_blog_id'] ) || $user_meta['registered_for_blog_id'] == get_current_blog_id() ){
                $number_of_users++;
            }
        }
    }

    header( 'Content-type: application/json' );
	die( json_encode( array( 'number' => $number_of_users ) ) );
}
	

function wppb_handle_email_confirmation_cases() {
	global $current_user;
	global $wpdb;
	
	//die($current_user);
	$url = trim($_POST['URL']);
	$todo = trim($_POST['todo']);
	$user_email = trim($_POST['user_email']);
	
	if ( current_user_can( 'delete_users' ) )
		if ( ( $todo != '' ) && ( $user_email != '' ) ){

			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->base_prefix . "signups WHERE active = 0 AND user_email = %s", $user_email ) );
			
			if ( count( $results ) != 1 )
				die( __( "There was an error performing that action!", "profilebuilder" ) );
				
			elseif ( $todo == 'delete' ){
				$sql_result = $wpdb->delete( $wpdb->base_prefix.'signups', array( 'user_login' => $results[0]->user_login, 'user_email' => $results[0]->user_email ) );
				if ( $sql_result )
					die( 'ok' );
					
				else
					die( __( "The selected user couldn't be deleted", "profilebuilder" ) );

			}elseif ( $todo == 'confirm' ){
				die( wppb_manual_activate_signup( $results[0]->activation_key ) );

			}elseif ( $todo == 'resend' ){
				$sql_result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->base_prefix . "signups WHERE user_login = %s AND user_email = %s", $results[0]->user_login, $results[0]->user_email ), ARRAY_A );
				
				if ( $sql_result ){
					wppb_signup_user_notification( trim( $sql_result['user_login'] ), trim( $sql_result['user_email'] ), $sql_result['activation_key'], $sql_result['meta'] );
					
					die( __( "Email notification resent to user", "profilebuilder" ) );
				}
				
			}
		}

	die( __("You either don't have permission for that action or there was an error!", "profilebuilder") );
}



// FUNCTIONS USED BOTH ON THE REGISTRATION PAGE AND THE EMAIL CONFIRMATION TABLE

// Hook to add AP user meta after signup autentification 
add_action( 'wpmu_activate_user', 'wppb_add_meta_to_user_on_activation', 10, 3 );

//function that adds AP user meta after signup autentification and it is also used when editing the user uprofile
function wppb_add_meta_to_user_on_activation( $user_id, $password, $meta ){
	$user = new WP_User( $user_id );

	//copy the data from the meta field (default fields)
	if( !empty( $meta['first_name'] ) )
		update_user_meta( $user_id, 'first_name', $meta['first_name'] );
	if( !empty( $meta['last_name'] ) )
		update_user_meta( $user_id, 'last_name', $meta['last_name'] );
	if( !empty( $meta['nickname'] ) )
		update_user_meta( $user_id, 'nickname', $meta['nickname'] );
	if( !empty( $meta['user_url'] ) )
		update_user_meta( $user_id, 'user_url', $meta['user_url'] );
	if( !empty( $meta['aim'] ) )
		update_user_meta( $user_id, 'aim', $meta['aim'] );
	if( !empty( $meta['yim'] ) )
		update_user_meta( $user_id, 'yim', $meta['yim'] );
	if( !empty( $meta['jabber'] ) )
		update_user_meta( $user_id, 'jabber', $meta['jabber'] );
	if( !empty( $meta['description'] ) )
		update_user_meta( $user_id, 'description', $meta['description'] );
	
	if( !empty( $meta['role'] ) )
		$user->set_role( $meta['role'] ); //update the users role (s)he registered for
	
	//copy the data from the meta fields (custom fields)
	$manage_fields = get_option( 'wppb_manage_fields', 'not_set' );
	if ( $manage_fields != 'not_set' ){
		foreach ( $manage_fields as $key => $value){
			switch ( $value['field'] ) {
				case 'Input':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}						
				case 'Input (Hidden)':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Checkbox':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Checkbox (Terms and Conditions)':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Radio':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Select':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Select (Country)':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Select (Multiple)':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Select (Timezone)':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Datepicker':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Textarea':{
					if ( isset( $meta[$value['meta-name']] ) )
						update_user_meta( $user_id, $value['meta-name'], trim( $meta[$value['meta-name']] ) );
					break;
				}
				case 'Upload':{
					if ( isset( $meta[$value['meta-name']] ) ){
                        if (is_numeric($meta[$value['meta-name']])) {
                            update_user_meta($user_id, $value['meta-name'], trim($meta[$value['meta-name']]));
                        }
                        else {
                            $wp_upload_array = wp_upload_dir(); // Array of key => value pairs

                            $file = trim($meta[$value['meta-name']]);
                            $file_name = substr($file, strpos($file, '_attachment_') + 12);

                            $random_user_number = apply_filters('wppb_register_wpmu_upload_random_user_number2', substr(md5($user->data->user_email), 0, 12), $user, $meta);

                            $old_path_on_disk = $wp_upload_array['basedir'] . '/profile_builder/attachments/' . substr($file, strpos($file, 'wpmuRandomID_'));
                            if (PHP_OS == "WIN32" || PHP_OS == "WINNT")
                                $old_path_on_disk = str_replace('\\', '/', $old_path_on_disk);

                            $new_path_on_disk = $wp_upload_array['basedir'] . '/profile_builder/attachments/userID_' . $user_id . '_attachment_' . $file_name;
                            if (PHP_OS == "WIN32" || PHP_OS == "WINNT")
                                $new_path_on_disk = str_replace('\\', '/', $new_path_on_disk);

                            if (rename($old_path_on_disk, $new_path_on_disk))
                                update_user_meta($user_id, $value['meta-name'], $wp_upload_array['baseurl'] . '/profile_builder/attachments/userID_' . $user_id . '_attachment_' . $file_name);
                        }
					}
					break;
				}				
				case 'Avatar':{
					if ( isset( $meta[$value['meta-name']] ) ) {
                        if (is_numeric($meta[$value['meta-name']])) {
                            update_user_meta($user_id, $value['meta-name'], trim($meta[$value['meta-name']]));
                        } else {
                            $wp_upload_array = wp_upload_dir(); // Array of key => value pairs

                            $file = trim($meta[$value['meta-name']]);
                            $file_name = substr($file, strpos($file, '_originalAvatar_') + 16);

                            $random_user_number = apply_filters('wppb_register_wpmu_avatar_random_user_number2', substr(md5($user->data->user_email), 0, 12), $user, $meta);

                            $old_path_on_disk = $wp_upload_array['basedir'] . '/profile_builder/avatars/' . substr($file, strpos($file, 'wpmuRandomID_'));
                            if (PHP_OS == "WIN32" || PHP_OS == "WINNT")
                                $old_path_on_disk = str_replace('\\', '/', $old_path_on_disk);

                            $new_path_on_disk = $wp_upload_array['basedir'] . '/profile_builder/avatars/userID_' . $user_id . '_originalAvatar_' . $file_name;
                            if (PHP_OS == "WIN32" || PHP_OS == "WINNT")
                                $new_path_on_disk = str_replace('\\', '/', $new_path_on_disk);

                            if (rename($old_path_on_disk, $new_path_on_disk)) {
                                $wp_filetype = wp_check_filetype(basename($file_name), null);
                                $attachment = array('post_mime_type' => $wp_filetype['type'],
                                    'post_title' => $file_name,
                                    'post_content' => '',
                                    'post_status' => 'inherit'
                                );

                                $attach_id = wp_insert_attachment($attachment, $new_path_on_disk);

                                $avatar = image_downsize($attach_id, 'thumbnail');

                                update_user_meta($user_id, $value['meta-name'], $avatar[0]);
                                update_user_meta($user_id, 'avatar_directory_path_' . $value['id'], $new_path_on_disk);
                                update_user_meta($user_id, 'resized_avatar_' . $value['id'] . '_relative_path', $new_path_on_disk);
                                wppb_resize_avatar($user_id);
                            }
                        }
                        break;
                    }
				}
                default:
                    do_action( 'wppb_add_meta_on_user_activation_'.Wordpress_Creation_Kit_PB::wck_generate_slug( $value['field'] ), $user_id, $password, $meta );
			}
		}
	}
}


// function to add the new user to the signup table if email confirmation is selected as active or it is a wpmu installation
function wppb_signup_user( $username, $user_email, $meta = '' ) {
	global $wpdb;

	// Format data
	$user = preg_replace( '/\s+/', '', sanitize_user( $username, true ) );
	$user_email = sanitize_email( $user_email );
	$activation_key = substr( md5( time() . rand() . $user_email ), 0, 16 );
	$meta = serialize( $meta );
	
	if ( is_multisite() ) 
		$wpdb->insert( $wpdb->signups, array('domain' => '', 'path' => '', 'title' => '', 'user_login' => $user, 'user_email' => $user_email, 'registered' => current_time('mysql', true), 'activation_key' => $activation_key, 'meta' => $meta ) );
	else
		$wpdb->insert( $wpdb->prefix.'signups', array('domain' => '', 'path' => '', 'title' => '', 'user_login' => $user, 'user_email' => $user_email, 'registered' => current_time('mysql', true), 'activation_key' => $activation_key, 'meta' => $meta ) );
	
	do_action ( 'wppb_signup_user', $username, $user_email, $activation_key, $meta );
	
	wppb_signup_user_notification( $username, $user_email, $activation_key, $meta );
}

/**
 * Notify user of signup success.
 *
 * Filter 'wppb_signup_user_notification_filter' to bypass this function or
 * replace it with your own notification behavior.
 *
 * Filter 'wppb_signup_user_notification_email' and
 * 'wppb_signup_user_notification_subject' to change the content
 * and subject line of the email sent to newly registered users.
 *
 * @param string $user The user's login name.
 * @param string $user_email The user's email address.
 * @param array $meta By default, an empty array.
 * @param string $activation_key The activation key created in wppb_signup_user()
 * @return bool
 */
function wppb_signup_user_notification( $user, $user_email, $activation_key, $meta = '' ) {
	if ( !apply_filters( 'wppb_signup_user_notification_filter', $user, $user_email, $activation_key, $meta ) )
		return false;
	
	$wppb_general_settings = get_option( 'wppb_general_settings' );	
	$admin_email = get_site_option( 'admin_email' );
	
	if ( $admin_email == '' )
		$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
		
	$from_name = apply_filters ( 'wppb_signup_user_notification_email_from_field', get_bloginfo( 'name' ) );
	
	$message_headers = apply_filters ( 'wppb_signup_user_notification_from', "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n" );
	
	$registration_page_url = ( ( isset( $wppb_general_settings['activationLandingPage'] ) && ( trim( $wppb_general_settings['activationLandingPage'] ) != '' ) ) ? add_query_arg( array('activation_key' => $activation_key ), get_permalink( $wppb_general_settings['activationLandingPage'] ) ) : 'not_set' );	
	if ( $registration_page_url == 'not_set' ){
		global $post;
        if( !empty( $post->ID ) )
            $permalink = get_permalink( $post->ID );
        else
            $permalink =  get_bloginfo( 'url' );

        if( !empty( $post->post_content ) )
            $post_content = $post->post_content;
        else
            $post_content = '';

		$registration_page_url = ( ( strpos( $post_content, '[wppb-register' ) !== false ) ? add_query_arg( array('activation_key' => $activation_key ), $permalink ) : add_query_arg( array('activation_key' => $activation_key ), get_bloginfo( 'url' ) ) );
	}
	
	$subject = sprintf( __( '[%1$s] Activate %2$s', 'profilebuilder'), $from_name, $user );
	$subject = apply_filters( 'wppb_signup_user_notification_email_subject', $subject, $user_email, $user, $activation_key, $registration_page_url, $meta, $from_name, 'wppb_user_emailc_registr_w_email_confirm_email_subject' );

	$message = sprintf( __( "To activate your user, please click the following link:\n\n%s%s%s\n\nAfter you activate it you will receive yet *another email* with your login.", "profilebuilder" ), '<a href="'.$registration_page_url.'">', $registration_page_url, '</a>.' );
    $message = apply_filters( 'wppb_signup_user_notification_email_content', $message, $user_email, $user, $activation_key, $registration_page_url, $meta, $from_name, 'wppb_user_emailc_registr_w_email_confirm_email_content' );

	wppb_mail( $user_email, $subject, $message, $from_name, '', $user, '', $user_email, 'register_w_email_confirmation', $registration_page_url, $meta );
	
	return true;
}


/**
 * Activate a signup.
 *
 *
 * @param string $activation_key The activation key provided to the user.
 * @return array An array containing information about the activated user and/or blog
 */
function wppb_manual_activate_signup( $activation_key ) {
	global $wpdb;

	if ( is_multisite() )
		$signup = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->signups WHERE activation_key = %s", $activation_key ) );
	else
		$signup = $wpdb->get_row( $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."signups WHERE activation_key = %s", $activation_key) );

	if ( !empty( $signup ) && !$signup->active ){
		$meta = unserialize( $signup->meta );
		$user_login = esc_sql( $signup->user_login );
		$user_email = esc_sql( $signup->user_email );
        /* the password is in hashed form in the signup table and we will copy it later to the user */
		$password = NULL;

		$user_id = username_exists($user_login);

		if ( ! $user_id )
			$user_id = wppb_create_user( $user_login, $password, $user_email );
		else
			$user_already_exists = true;

		if ( !$user_id )
			return __( 'Could not create user!', 'profilebuilder' );
			
		elseif ( isset( $user_already_exists ) && ( $user_already_exists == true ) )
			return __( 'That username is already activated!', 'profilebuilder' );
		
		else{
			$now = current_time('mysql', true);
			
			$retVal = ( is_multisite() ? $wpdb->update( $wpdb->signups, array('active' => 1, 'activated' => $now), array('activation_key' => $activation_key) ) : $wpdb->update( $wpdb->base_prefix.'signups', array('active' => 1, 'activated' => $now), array('activation_key' => $activation_key) ) );

			wppb_add_meta_to_user_on_activation( $user_id, '', $meta );
			
			// if admin approval is activated, then block the user untill he gets approved
			$wppb_general_settings = get_option( 'wppb_general_settings' );
			if( isset( $wppb_general_settings['adminApproval'] ) && ( $wppb_general_settings['adminApproval'] == 'yes' ) ){
				wp_set_object_terms( $user_id, array( 'unapproved' ), 'user_status', false);
				clean_object_term_cache( $user_id, 'user_status' );
			}

            /* copy the hashed password from signup meta to wp user table */
            if( !empty( $meta['user_pass'] ) ){
                /* we might still have the base64 encoded password in signups and not the hash */
                if( base64_encode(base64_decode($meta['user_pass'], true)) === $meta['user_pass'] )
                    $meta['user_pass'] = wp_hash_password( $meta['user_pass'] );

                $wpdb->update( $wpdb->users, array('user_pass' => $meta['user_pass'] ), array('ID' => $user_id) );
            }
			
			wppb_notify_user_registration_email( get_bloginfo( 'name' ), $user_login, $user_email, 'sending', $password, ( isset( $wppb_general_settings['adminApproval'] ) ? $wppb_general_settings['adminApproval'] : 'no' ) );
			
			do_action('wppb_activate_user', $user_id, $password, $meta);
			
			return ( $retVal ? 'ok' : __( 'There was an error while trying to activate the user', 'profilebuilder' ) );			
		}
	}
}

/**
 * Create a user.
 *
 * @param string $user_name The new user's login name.
 * @param string $password The new user's password.
 * @param string $email The new user's email address.
 * @return mixed Returns false on failure, or int $user_id on success
 */
function wppb_create_user( $user_name, $password, $email) {
    if( is_email( $user_name ) )
        $user_name = apply_filters( 'wppb_generated_random_username', Wordpress_Creation_Kit_PB::wck_generate_slug( trim( $user_name ) ), $user_name );
    else
	    $user_name = preg_replace( '/\s+/', '', sanitize_user( $user_name, true ) );


	$user_id = wp_create_user( $user_name, $password, $email );
	if ( is_wp_error($user_id) )
		return false;

	// Newly created users have no roles or caps until they are added to a blog.
	delete_user_option( $user_id, 'capabilities' );
	delete_user_option( $user_id, 'user_level' );

	do_action( 'wppb_new_user', $user_id );

	return $user_id;
}

//send an email to the admin regarding each and every new subscriber, and - if selected - to the user himself
function wppb_notify_user_registration_email( $bloginfo, $user_name, $email, $send_credentials_via_email, $password, $adminApproval ){

    /* if login with email is enabled user_name gets the value of the users email */
    $wppb_general_settings = get_option( 'wppb_general_settings' );
    if ( isset( $wppb_general_settings['loginWith'] ) && ( $wppb_general_settings['loginWith'] == 'email' ) ) {
        $user_name = $email;
    }

	//send email to the admin
	$message_from = apply_filters( 'wppb_register_from_email_message_admin_email', $bloginfo );
	
	$message_subject = '['.$message_from.'] '.__( 'A new subscriber has (been) registered!', 'profilebuilder' );
	$message_subject = apply_filters ('wppb_register_admin_email_subject_without_admin_approval', $message_subject, $email, $password, $message_from, 'wppb_admin_emailc_default_registration_email_subject' );
	
	$message_content = sprintf( __( 'New subscriber on %1$s.<br/><br/>Username:%2$s<br/>E-mail:%3$s<br/>', 'profilebuilder'), $message_from, $user_name, $email );
	
	if ( $adminApproval == 'yes' ){
		$message_subject = apply_filters( 'wppb_register_admin_email_subject_with_admin_approval', $message_subject, $email, $password, $message_from, 'wppb_admin_emailc_registration_with_admin_approval_email_subject' );
	
		$message_content .= '<br/>' . __( 'The "Admin Approval" feature was activated at the time of registration, so please remember that you need to approve this user before he/she can log in!', 'profilebuilder') ."\r\n";
		$message_content = apply_filters( 'wppb_register_admin_email_message_with_admin_approval', $message_content, $email, $password, $message_from, 'wppb_admin_emailc_registration_with_admin_approval_email_content' );
	}else
		$message_content = apply_filters( 'wppb_register_admin_email_message_without_admin_approval', $message_content, $email, $password, $message_from, 'wppb_admin_emailc_default_registration_email_content' );
	

	if ( trim( $message_content ) != '' )
		wppb_mail( get_option('admin_email'), $message_subject, $message_content, $message_from );

		
	
	//send an email to the newly registered user, if this option was selected
	if ( isset( $send_credentials_via_email ) && ( $send_credentials_via_email == 'sending' ) ){
		$user_message_from = apply_filters( 'wppb_register_from_email_message_user_email', $bloginfo );

		$user_message_subject = sprintf( __( '[%1$s] Your new account information', 'profilebuilder' ), $user_message_from, $user_name, $password );
		$user_message_subject = apply_filters( 'wppb_register_user_email_subject_without_admin_approval', $user_message_subject, $email, $password, $user_message_subject, 'wppb_user_emailc_default_registration_email_subject' );

        if ( $password === NULL ) {
            $password = __( 'Your selected password at signup', 'profilebuilder' );
        }
		$user_message_content = sprintf( __( 'Welcome to %1$s!<br/><br/><br/>Your username is:%2$s and password:%3$s', 'profilebuilder' ), $user_message_from, $user_name, $password );
        if ( $password === __( 'Your selected password at signup', 'profilebuilder' ) ) {
            $password = NULL;
        }
		
		if ( $adminApproval == 'yes' ){
			$user_message_subject = apply_filters( 'wppb_register_user_email_subject_with_admin_approval', $user_message_subject, $email, $password, $user_message_subject, 'wppb_user_emailc_registration_with_admin_approval_email_subject' );

			if( ! current_user_can( 'delete_users' ) ) {
				$user_message_content .= '<br/><br/>' . __( 'Before you can access your account, an administrator needs to approve it. You will be notified via email.', 'profilebuilder' );
			}
			$user_message_content = apply_filters( 'wppb_register_user_email_message_with_admin_approval', $user_message_content, $email, $password, $user_message_subject, 'wppb_user_emailc_registration_with_admin_approval_email_content' );
		
		}else
			$user_message_content = apply_filters( 'wppb_register_user_email_message_without_admin_approval', $user_message_content, $email, $password, $user_message_subject, 'wppb_user_emailc_default_registration_email_content' );
			
		$message_sent = wppb_mail( $email, $user_message_subject, $user_message_content, $user_message_from );
		
		return ( ( $message_sent ) ? 2 : 1 );
	}
}
// END FUNCTIONS USED BOTH ON THE REGISTRATION PAGE AND THE EMAIL CONFIRMATION TABLE


// Set up the AJAX hooks
add_action( 'wp_ajax_wppb_get_unconfirmed_email_number', 'wppb_get_unconfirmed_email_number' );	
add_action( 'wp_ajax_wppb_handle_email_confirmation_cases', 'wppb_handle_email_confirmation_cases' );

if ( is_multisite() ){

    /* don't display on network admin */
	if( strpos($_SERVER['SCRIPT_NAME'], 'users.php') && !is_network_admin() ){  //global $pagenow doesn't seem to work
		add_action( 'admin_head', 'wppb_add_pending_users_header_script' );
	}

	if ( file_exists ( WPPB_PLUGIN_DIR . '/features/admin-approval/admin-approval.php' ) )
		add_action( 'user_register', 'wppb_update_user_status_on_admin_registration' );
	
}else{
	$wppb_general_settings = get_option( 'wppb_general_settings', 'not_found' );
	
	if( $wppb_general_settings != 'not_found' )
		if( !empty($wppb_general_settings['emailConfirmation'] ) && ( $wppb_general_settings['emailConfirmation'] == 'yes' ) ){
			global $pagenow;
			
			if ( $pagenow == 'users.php' ){
				add_action( 'admin_head', 'wppb_add_pending_users_header_script' );

			}
			if ( file_exists ( WPPB_PLUGIN_DIR . '/features/admin-approval/admin-approval.php' ) )
				add_action( 'user_register', 'wppb_update_user_status_on_admin_registration' );
		}
}

// function to delete the users from the _signups table also
function wppb_delete_user_from_signups( $user_id ) {
	global $wpdb;

    $user = get_user_by( 'id', $user_id );
	$wpdb->delete( $wpdb->base_prefix.'signups', array( 'user_email' => $user->user_email ) );
}
add_action( 'delete_user', 'wppb_delete_user_from_signups' );