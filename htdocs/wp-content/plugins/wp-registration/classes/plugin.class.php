<?php
/**
 * this is main plugin class
*/


/* ======= the model main class =========== */
if(!class_exists('NM_Framwork_V1_wpregistration')){
	$_framework = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'nm-framework.php';
	if( file_exists($_framework))
		include_once($_framework);
	else
		die('Reen, Reen, BUMP! not found '.$_framework);
}


/*
 * [1]
 * TODO: change the class name of your plugin
 */
class NM_WPRegistration extends NM_Framwork_V1_wpregistration{

	private static $ins = null;
	
	public static function init()
	{
		add_action('plugins_loaded', array(self::get_instance(), '_setup'));
	}
	
	public static function get_instance()
	{
		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
	
	/*
	 * plugin constructur
	 */
	function _setup(){
		
		//setting plugin meta saved in config.php
		$this -> plugin_meta = get_plugin_meta_wpregistration();

		//getting saved settings
		$this -> plugin_settings = get_option($this->plugin_meta['shortname'].'_settings');
		
		// populating $inputs with NM_Inputs object
		$this->inputs = $this->get_all_inputs ();
		/*
		 * [2]
		 * TODO: update scripts array for SHIPPED scripts
		 * only use handlers
		 */
		//setting shipped scripts
		$this -> wp_shipped_scripts = array('jquery');
		
		
		/*
		 * [3]
		* TODO: update scripts array for custom scripts/styles
		*/
		//setting plugin settings
		$this -> plugin_scripts =  array(
				
										array(	'script_name'	=> 'scripts-chosen',
												'script_source'	=> '/js/chosen/chosen.jquery.min.js',
												'localized'		=> false,
												'type'			=> 'js',
												'page_slug'		=> $this->plugin_meta['shortname'],
												'depends' => array ('jquery')
										),
										
										array (
												'script_name' => 'chosen-style',
												'script_source' => '/js/chosen/chosen.min.css',
												'localized' => false,
												'type' => 'style',
												'page_slug' => $this->plugin_meta['shortname'],
										),
										array(	'script_name'	=> 'scripts',
												'script_source'	=> '/js/script.js',
												'localized'		=> true,
												'type'			=> 'js'
										),
										array(	'script_name'	=> 'styles',
												'script_source'	=> '/plugin.styles.css',
												'localized'		=> false,
												'type'			=> 'style'
										),
				
										array (
												'script_name' => 'nm-ui-style',
												'script_source' => '/js/ui/css/smoothness/jquery-ui-1.10.3.custom.min.css',
												'localized' => false,
												'type' => 'style',
												'page_slug' => array (
														'nm-new-form'
												)
										),
										);
		
		/*
		 * [4]
		* TODO: localized array that will be used in JS files
		* Localized object will always be your pluginshortname_vars
		* e.g: pluginshortname_vars.ajaxurl
		*/
		$validation_message = ($this->get_option('_validation_notification') != '' ? $this->get_option('_validation_notification') : 'Please provide detail in red above');
		$this -> localized_vars = array('ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_url' 		=> $this->plugin_meta['url'],
				'doing'				=> $this->plugin_meta['url'] . '/images/loading.gif',
				'val_notification'	=> sprintf(__("%s", 'wp-registration'), $validation_message),
				'settings'			=> $this -> plugin_settings);
		
		
		/*
		 * [5]
		 * TODO: this array will grow as plugin grow
		 * all functions which need to be called back MUST be in this array
		 * setting callbacks
		 */
		//following array are functions name and ajax callback handlers
		$this -> ajax_callbacks = array('save_settings',		//do not change this action, is for admin
										'save_file_meta',
										'create_user',
										'reset_user_password');
		
		/*
		 * plugin localization being initiated here
		 */
		add_action('init', array($this, 'wpp_textdomain'));
		
		
		/*
		 * plugin main shortcode if needed
		 */
		add_shortcode('nm-wp-registration', array($this , 'render_shortcode_registration'));
		
		add_shortcode('nm-wp-login', array($this , 'render_shortcode_login'));
		
		
		
		add_filter( 'login_redirect', array($this, 'login_redirect'), 10, 3 );
		
		
		/*
		 * hooking up scripts for front-end
		*/
		add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
		
		/*
		 * checking if page is protectd
		*/
		add_action('wp', array($this, 'check_login_required'));
		//add_action( 'post_submitbox_misc_actions', array($this, 'nm_metabox_addition') );
		add_action( 'add_meta_boxes', array($this, 'registration_add_meta_box') );
		//add_action( 'add_meta_boxes', 'myplugin_add_meta_box' );
		add_action( 'save_post', array( $this, 'nm_page_attributes' ));
		
		/*
		 * hooking up scripts for user meta in profile
		*/
		add_action('show_user_profile', array($this, 'profile_user_meta'));
		add_action('edit_user_profile', array($this, 'profile_user_meta'));
		
		/*
		 * registering callbacks
		*/
		$this -> do_callbacks();
	}
	
	
	
	/*
	 * =============== NOW do your JOB ===========================
	 * 
	 */
	
	/*
	 * returning NM_Inputs object
	*/
	private function get_all_inputs() {
		if (! class_exists ( 'NM_Inputs_wpregisration' )) {
			$_inputs = $this->plugin_meta ['path'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'input.class.php';
			if (file_exists ( $_inputs ))
				include_once ($_inputs);
			else
				die ( 'Reen, Reen, BUMP! not found ' . $_inputs );
		}
	
		$nm_inputs = new NM_Inputs_wpregisration ();
		// filemanager_pa($this->plugin_meta);
	
		// registering all inputs here
	
		return array (
	
				'text' 		=> $nm_inputs->get_input ( 'text' ),
				'select' 	=> $nm_inputs->get_input ( 'select' ),
				'radio' 	=> $nm_inputs->get_input ( 'radio' ),
				'checkbox' 	=> $nm_inputs->get_input ( 'checkbox' ),
				'date' 		=> $nm_inputs->get_input ( 'date' ),
				'masked' 	=> $nm_inputs->get_input ( 'masked' ),
				'email' 	=> $nm_inputs->get_input ( 'email' ),
				'number' 	=> $nm_inputs->get_input ( 'number' ),
				'section' 	=> $nm_inputs->get_input ( 'section' ),
				
				//'file' 		=> $nm_inputs->get_input ( 'file' ),
		);
	
		// return new NM_Inputs($this->plugin_meta);
	}
	
	
	/*
	 * saving admin setting in wp option data table
	 */
	function save_settings(){
	
		//pa($_REQUEST);
		$existingOptions = get_option($this->plugin_meta['shortname'].'_settings');
		//pa($existingOptions);
	
		update_option($this->plugin_meta['shortname'].'_settings', $_REQUEST);
		_e('All options are updated', $this->plugin_meta['shortname']);
		die(0);
	}
	
	/*
	 * saving form meta in admin call
	*/
	function save_file_meta() {
	
		//print_r($_REQUEST); exit;
		global $wpdb;
	
		update_option('wpregistration_meta', $_REQUEST['file_meta']);
	
		$resp = array (
				'message' => __ ( 'Meta updated successfully', 'wp-registration' ),
				'status' => 'success',
				'form_id' => $res_id
		);
	
		echo json_encode ( $resp );
		die ( 0 );
	}

	/*
	 * creating user
	*/
	function create_user() {
		
		//print_r($_REQUEST);
		$response = array();
		
		if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['nm_wpregistration_nonce'], 'doing_registration' )) {
			print 'Sorry, You are not HUMANE.';
			exit ();
		}
		extract( $_REQUEST );
		$submitted_data = $_REQUEST;
		
		unset ( $submitted_data ['action'] );
		unset ( $submitted_data ['nm_wpregistration_nonce'] );
		unset ( $submitted_data ['_wp_http_referer'] );
		unset ( $submitted_data ['wp_registration_login'] );
		unset ( $submitted_data ['wp_registration_email'] );
		unset ( $submitted_data ['wp_registration_first_name'] );
		unset ( $submitted_data ['wp_registration_last_name'] );
		 
		//unset ( $submitted_data ['nm_firstname'] );
		//unset ( $submitted_data ['nm_lastname'] );
		//unset ( $submitted_data ['nm_email'] );
				
		$pwd = wp_generate_password( 12, false );
		$userdata = array(	"user_pass"		=> $pwd,
							"user_login"	=> $wp_registration_login,
							"first_name"	=> $wp_registration_first_name,
							"last_name"		=> $wp_registration_last_name,
							//"user_url"		=> wp_login_url(),
							//"user_nicename"	=> $nm_firstname.'-'.$nm_lastname,
							//"display_name"	=> $nm_firstname.' '.$nm_lastname,
							"user_email"	=> $wp_registration_email,
							//"role" => 'pending'
		);

		$userid = wp_insert_user( $userdata );
	
		if ( $userid && !is_wp_error( $userid ) ) {
			foreach ( $submitted_data as $key => $val ) {
				update_user_meta ( $userid, $key, $val );
			}
			
			
			$site_title = get_bloginfo();
			$admin_email = get_bloginfo('admin_email');
			
			$headers[] = "From: {$site_title}<{$admin_email}>";
			$headers[] = "Content-Type: text/html";
			$headers[] = "MIME-Version: 1.0\r\n";
			
			$msg = $this -> get_option('_new_user');
			$suject = __('New user registration ', 'wp-registration').$wp_registration_login;			
			
			$msg = str_replace("%USERNAME%", $wp_registration_login, $msg);
			//$msg = str_replace("%USER_LOGIN%", $nm_email, $msg);
			$msg = str_replace("%USER_PASSWORD%", $pwd, $msg);
			$msg = str_replace("%SITE_NAME%", $site_title, $msg);
			$msg .= '<p><a href="' . wp_login_url() . '" title="Login">Click here to login</a>';
			$msg = str_replace("\n", "<br />", $msg);

			if(wp_mail($wp_registration_email, $suject, $msg, $headers)){
				
				$success_message = $this -> get_option('_success_message');
				$success_message = ($success_message == '' ? 'User created! Please check your mail.' : $success_message);
				$response = array('status'	=> 'success', 'message' => sprintf(__('%s', 'wp-registration'), $success_message));
			}else{
				$error_email_msg = 'Regisration is completed but system could not send you email with login detail, please contact site admin';
				$response = array('status'	=> 'error', 'message' => sprintf(__('%s', 'wp-registration'), $error_email_msg));
				
			}
			
		} else {
			
			$response = array('status'	=> 'error', 'message' => sprintf(__('%s', 'wp-registration'), $userid -> get_error_message()));
		}
		
		echo json_encode($response);
		
		die(0);
		
	}

	/*
	 * resetting user password
	*/
	function reset_user_password(){
		
		$response = array();
		/** its not a form. should wp_nonce apply?????? **/
		/*if (empty ( $_POST ) || ! wp_verify_nonce ( $_POST ['nm_wpregistration_nonce'], 'doing_login' )) {
			print 'Sorry, You are not HUMANE.';
			exit ();
		}*/
		
		$email = $_REQUEST['user_name'];
		$exists = email_exists($email);
		if ( $exists ){
			$user_id = $exists;
			$password = wp_generate_password( 12, false );
			wp_set_password( $password, $user_id );

			$reset_password_msg = $this -> get_option('_reset_password');
			$reset_password_msg = ($reset_password_msg == '' ? 'Your new password is ' : $reset_password_msg);
			$reset_password_msg .= $password;
			
			$headers [] = "Content-Type: text/html";
			
			//$msg = $this -> get_option('_reset_password');
			
			//$msg = str_replace("%USER_PASSWORD%", $password, $msg);
			//$msg .= '<p><a href="' . wp_login_url() . '" title="Login">Login</a>';
			//$msg = str_replace("\n", "<br />", $msg);

			wp_mail( $email, 'Password Reset', $reset_password_msg, $headers);
			
			$response = array('status'	=> 'success', 'message' => sprintf(__('%s', 'wp-registration'), 'New password sent via email.'));
			
			//_e('New password sent via email.', 'wp-registration');
		}
		
		else
			$response = array('status'	=> 'error', 'message' => sprintf(__('%s', 'wp-registration'), "That E-mail doesn't belong to any registered users on this site"));
			//_e("That E-mail doesn't belong to any registered users on this site", 'wp-registration');
		echo json_encode($response);	
		die(0);
	}
	
	function login_redirect( $redirect_to, $request, $user ) {
		if ($this -> get_option('_redirect_url_login') != '')
			return $this -> get_option('_redirect_url_login');
		else
			return $redirect_to;
	}
	
	/*
	 * rendering template against shortcode
	*/
	function render_shortcode_registration($atts){

		if ( !is_user_logged_in() ) {
			
			ob_start();
			
			echo '<div id="nm-wp-registration-form">';
			echo '<h2>'.$this -> get_option('_form_title').'</h2>';
			echo '<p>'.$this -> get_option('_form_desc').'</p>';
	
			$this -> load_template('_template_registration_meta.php');
			
			echo '</div>';
	
			$output_string = ob_get_contents();
			ob_end_clean();
				
			return $output_string;
		}else{
			
			global $current_user;
			get_currentuserinfo();
			printf(__("Hi %s", "wp-registration"), $current_user -> user_login);  
		}
		
	}

	/*
	 * rendering template against shortcode
	*/
	function render_shortcode_login($atts){

		if ( !is_user_logged_in() ) {
			
			ob_start();
			
			$this -> load_template('_template_login.php');
			
			echo '</div>';
	
			$output_string = ob_get_contents();
			ob_end_clean();
				
			return $output_string;
		}else{
			
			global $current_user;
			get_currentuserinfo();
			printf(__("Hi %s", "wp-registration"), $current_user -> user_login);  
		}
	}


	// ================================ SOME HELPER FUNCTIONS =========================================

	function check_login_required(){
         
         if(is_page()){
         
         	global $post;
			global $current_user;
			
			$nm_for_members = get_post_meta( $post -> ID, 'nm_members', true );

			if(!is_user_logged_in() && $nm_for_members == 'yes' ){
				$redirect = home_url() . '/wp-login.php?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] );
				wp_redirect( $redirect );
				exit;
			}

		 }
    }

	function nm_metabox_addition($post){
		$nm_for_members = get_post_meta( $post -> ID, 'nm_members', true );
		$all_users = '';
		if ( $nm_for_members == 'yes' ){
			$all_users = 'checked';
		}

	?>
        <div>
            <input type="checkbox" name="nm_for_members" value="yes" <?php echo $all_users; ?>/> For Members Only<br/>
        </div>
    <?php

	}

	function registration_add_meta_box() {
	
		add_meta_box(
			'myplugin_sectionid',
			__( 'WP_Registration Access Control', 'nm-wpregistration' ),
			array($this, 'nm_metabox_addition'),
			'page',
			'side',
			'core'
		);
		
	}

	function nm_page_attributes($post_id){
		update_post_meta($post_id, 'nm_members', $_POST['nm_for_members']);		
		//wpregistration_pa($_POST);exit;
	}	
	
	function profile_user_meta($user) {
		
		$existing_meta = get_option('wpregistration_meta');
		
		if( $existing_meta ){ 
			echo '<table class="form-table">';
			foreach ($existing_meta as $key => $meta){
				
				$meta_key 	= strtolower( preg_replace("![^a-z0-9]+!i", "_", $meta['data_name']) );
				$meta_val 	= get_user_meta( $user->ID, $meta_key, true );
				$title 		= $meta['title'];
				
				if($meta_val && $meta['type'] != 'section'){
					$meta_val =(is_array($meta_val) ? implode(",", $meta_val) : $meta_val);
			?>
	        
		        <tr>
		            <th>
		                <label for="my_select"><?php printf(__("%s", 'nm-wpregisration'), $title); ?></label>
		            </th>
		            <td>
		                <input type="text" name="<?php echo $meta_key?>" id="<?php echo $meta_key?>" value="<?php echo $meta_val; ?>" class="regular-text" />
		                <br><span class="description"><?php printf(__("%s", 'nm-wpregisration'), $meta['description']); ?></span>
		            </td>
		        </tr>
	        
	     	<?php
				}
			}
			
			echo '</table>';
		}
	}



	function activate_plugin(){
		
		$plugin_meta = get_plugin_meta_wpregistration();

		
		if( !get_option( 'wpregistration_meta' ) )
			update_option('wpregistration_meta', $plugin_meta['default_fields']);
		//wpregistration_pa($plugin_meta);
		

	}

	function deactivate_plugin(){

		//do nothing so far.
		//delete_option('wpregistration_meta');
	}
}