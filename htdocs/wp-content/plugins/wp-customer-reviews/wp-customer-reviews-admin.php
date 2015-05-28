<?php
class WPCustomerReviewsAdmin
{
	var $parentClass = '';

	function WPCustomerReviewsAdmin($parentClass) {
            define('IN_WPCR_ADMIN',1);

            /* begin - haxish but it works */
            $this->parentClass = &$parentClass;
            foreach ($this->parentClass as $col => $val) {
                $this->$col = &$this->parentClass->$col;
            }
            /* end - haxish but it works */
	}
	
	function real_admin_init() {
		$this->parentClass->init();
		$this->enqueue_admin_stuff();
		
		register_setting( 'wpcr_options', 'wpcr_options' );

		/* used for redirecting to settings page upon initial activation */
		if (get_option('wpcr_gotosettings', false)) {
			delete_option('wpcr_gotosettings');
			unregister_setting('wpcr_gotosettings', 'wpcr_gotosettings');

			/* no auto settings redirect if upgrading */
			if ( isset($this->p->action) && $this->p->action == 'activate-plugin' ) { return false; }

			$url = get_admin_url().'options-general.php?page=wpcr_options';
			$this->parentClass->wpcr_redirect($url);
		}
		
		$this->notice_ignore(); /* admin notices */
	}
	
	function notice_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add to user meta */
		if ( isset($_GET['wpcr_notice_1']) ) {
			$n1 = $_GET['wpcr_notice_1'];
			if ( $n1 == 'redir' ) {
				wp_redirect('http://www.gowebsolutions.com/wp-customer-reviews/?from=wpcr_admin_notice_1');
				exit();
			} else if ( $n1 == 'ignore' ) {
				add_user_meta($user_id, 'wpcr_admin_notice_read_1', 'true', true);
			}
		}
	}
	/* end - admin notices */
	
	function wpcr_add_meta_box() {
		global $meta_box;
		
		$prefix = 'wpcr_';

		$meta_box = array(
			'id' => 'wpcr-meta-box',
			'title' => '<img src="'.$this->parentClass->getpluginurl().'star.png" />&nbsp;WP Customer Reviews',
			'page' => 'page',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => '<span style="font-weight:bold;">Enable WP Customer Reviews</span> for this page',
					'desc' => 'Plugin content will be displayed below your page contents',
					'id' => $prefix . 'enable',
					'type' => 'checkbox'
				),
				array(
					'name' => 'Product Name',
					'desc' => '<span style="color:#BE5409;">This is where you need to enter in the product name. Only necessary if you are showing PRODUCT style reviews. This field will be ignored if you have the plugin setup for BUSINESS style reviews</span>',
					'id' => $prefix . 'product_name',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => 'Product Description',
					'desc' => '',
					'id' => $prefix . 'product_desc',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => 'Manufacturer/Brand of Product',
					'desc' => '',
					'id' => $prefix . 'product_brand',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => 'Model',
					'desc' => '',
					'id' => $prefix . 'product_model',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => 'SKU',
					'desc' => '',
					'id' => $prefix . 'product_sku',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => 'UPC',
					'desc' => '',
					'id' => $prefix . 'product_upc',
					'type' => 'text',
					'std' => ''
				)
			)
		);
		
		/* add for pages and posts */
		add_meta_box($meta_box['id'], $meta_box['title'], array(&$this, 'wpcr_show_meta_box'), 'page', $meta_box['context'], $meta_box['priority']);
		add_meta_box($meta_box['id'], $meta_box['title'], array(&$this, 'wpcr_show_meta_box'), 'post', $meta_box['context'], $meta_box['priority']);
	}
	
	function real_admin_save_post($post_id) {
            global $meta_box,$wpdb;

            // check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            // check permissions
            if ( isset($this->p->post_type) && $this->p->post_type == 'page' ) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

			if ( isset($meta_box) && isset($meta_box['fields']) && is_array($meta_box['fields']) )
			{
				foreach ($meta_box['fields'] as $field) {
					
					if ( isset($this->p->post_title) ) {
						$old = get_post_meta($post_id, $field['id'], true);
						
						if (isset($this->p->$field['id'])) {
							$new = $this->p->$field['id'];
							if ($new && $new != $old) {
								update_post_meta($post_id, $field['id'], $new);
							} elseif ($new == '' && $old) {
								delete_post_meta($post_id, $field['id'], $old);
							}
						} else {
							delete_post_meta($post_id, $field['id'], $old);
						}
					}
					
				}
			}

            return $post_id;
	}
	
	function wpcr_show_meta_box() {
		global $meta_box, $post;
		
		echo '<table class="form-table">';

		foreach ($meta_box['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);
                                               
			if ($field['id'] == 'wpcr_enable' && $post->post_name == '') {
				if ($post->post_type == 'post' && $this->options['enable_posts_default'] == 1) {
					$meta = 1; /* enable by default for posts */
				}
				else if ($post->post_type == 'page' && $this->options['enable_pages_default'] == 1) {
					$meta = 1; /* enable by default for pages */
				}
			}
			
			echo '<tr>',
				 '<th style="width:30%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				 '<td>';
			switch ($field['type']) {
				case 'text':
					echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
					break;
				case 'textarea':
					echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
					break;
				case 'select':
					echo '<select name="', $field['id'], '" id="', $field['id'], '">';
					foreach ($field['options'] as $option) {
						echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
					}
					echo '</select>';
					break;
				case 'radio':
					foreach ($field['options'] as $option) {
						echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
					}
					break;
				case 'checkbox':
					echo '<input value="1" type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
					break;
			}
			echo '<td></tr>';
		}
		
		echo '</table>';
	}
	
	function createUpdateReviewTable() {
            require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
            
            $sql = "CREATE TABLE $this->dbtable (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      date_time datetime NOT NULL,
                      reviewer_name varchar(150) DEFAULT NULL,
                      reviewer_email varchar(150) DEFAULT NULL,
                      reviewer_ip varchar(15) DEFAULT NULL,
                      review_title varchar(150) DEFAULT NULL,
                      review_text text,
                      review_response text,
                      status tinyint(1) DEFAULT '0',
                      review_rating tinyint(2) DEFAULT '0',
                      reviewer_url varchar(255) NOT NULL,
                      page_id int(11) NOT NULL DEFAULT '0',
                      custom_fields text,
                      PRIMARY KEY (id),
                      KEY status (status),
                      KEY page_id (page_id)
                      )";
            
            dbDelta($sql);
        }
	
	function force_update_cache() {
			return; /* testing to increase performance */
			global $wpdb;
				
			/* update all pages, since some may have just disabled the plugin */
			$pages = $wpdb->get_results( "SELECT `ID` FROM $wpdb->posts AS `p`" );
			foreach ($pages as $page) {
                $post = get_post($page->ID);
				if ($post) {
					clean_post_cache($page->ID);
					wp_update_post($post); /* comment to prevent some plugins from firing tweets/etc when updating, can have side effects */
                }
            }
    }

	/* some admin styles can override normal styles for inplace edits */
	function enqueue_admin_stuff() {
            $pluginurl = $this->parentClass->getpluginurl();

            if (isset($this->p->page) && ( $this->p->page == 'wpcr_view_reviews' || $this->p->page == 'wpcr_options' ) ) {
                wp_register_script('wp-customer-reviews-admin',$pluginurl.'wp-customer-reviews-admin.js',array('jquery'),$this->plugin_version);
				wp_register_style('wp-customer-reviews-admin',$pluginurl.'wp-customer-reviews-admin.css',array(),$this->plugin_version);  
				wp_enqueue_script('wp-customer-reviews-admin');
				wp_enqueue_style('wp-customer-reviews-admin');
            }
	}
	
	/* v4 uuid */
	function gen_uuid() {
            return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
	}
	
     /* 
     * This is used purely for analytics and for notification of critical security releases.
     * It gives us a chance to review who is using it, how it is being used, 
     * and to verify theme and version compatibility. None of this information will ever be 
     * shared, sold, or given away.
     */
    function notify_activate($act_flag) {
        global $wp_version;
        
        /* TO DISABLE THIS FUNCTION, UNCOMMENT THE FOLLOWING LINE */
        /* return false; */
		
        if ($this->options['act_uniq'] == '') {
            $this->options['act_uniq'] = $this->gen_uuid();
            update_option('wpcr_options', $this->options);
        }
        
        $request = 'doact='.$act_flag.'&email='.urlencode(stripslashes($this->options['act_email'])).'&version='.$this->plugin_version.'&support='.$this->options['support_us'].'&uuid='.$this->options['act_uniq'];
        $host = "www.gowebsolutions.com";
        $port = 80;
        $wpurl = get_bloginfo('wpurl');
        
        $http_request  = "POST /plugin-activation/activate.php HTTP/1.0\r\n";
        $http_request .= "Host: www.gowebsolutions.com\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded; charset=".get_option('blog_charset')."\r\n";
        $http_request .= "Content-Length: ".strlen($request)."\r\n";
        $http_request .= "Referer: $wpurl\r\n";
        $http_request .= "User-Agent: WordPress/$wp_version\r\n\r\n";
        $http_request .= $request;

        $response = '';
        if( false != ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
            fwrite($fs, $http_request);
            while ( !feof($fs) ) {
                $response .= fgets($fs, 1160);
            }
            fclose($fs);
            $response = explode("\r\n\r\n", $response, 2);
        }

        return $response;
    }
	
    function update_options() {
        /* we still process and validate this internally, instead of using the Settings API */
        
        global $wpdb;
        $msg ='';
        
        $this->security();
                
        if (isset($this->p->optin))
        {      			
            if ($this->options['activate'] == 0)
            {
                $this->options['activate'] = 1;
                $this->options['act_email'] = $this->p->email;

                update_option('wpcr_options', $this->options);
                $this->notify_activate(1);
                $msg = 'Thank you. Please configure the plugin below.';
            }
        }
        else
        {
            check_admin_referer('wpcr_options-options'); /* nonce check */
            
            $updated_options = $this->options;
		
            /* reset these to 0 so we can grab the settings below */
            $updated_options['ask_fields']['fname'] = 0;
            $updated_options['ask_fields']['femail'] = 0;
            $updated_options['ask_fields']['fwebsite'] = 0;
            $updated_options['ask_fields']['ftitle'] = 0;
            $updated_options['require_fields']['fname'] = 0;
            $updated_options['require_fields']['femail'] = 0;
            $updated_options['require_fields']['fwebsite'] = 0;
            $updated_options['require_fields']['ftitle'] = 0;
            $updated_options['show_fields']['fname'] = 0;
            $updated_options['show_fields']['femail'] = 0;
            $updated_options['show_fields']['fwebsite'] = 0;
            $updated_options['show_fields']['ftitle'] = 0;
            $updated_options['ask_custom'] = array();
            $updated_options['field_custom'] = array();
            $updated_options['require_custom'] = array();
            $updated_options['show_custom'] = array();
		
            /* quick update of all options needed */
            foreach ($this->p as $col => $val)
            {
                if (isset($this->options[$col]))
                {
                    switch($col)
                    {
                        case 'field_custom': /* we should always hit field_custom before ask_custom, etc */
                            foreach ($val as $i => $name) { $updated_options[$col][$i] = ucwords( strtolower( $name ) ); } /* we are so special */
                            break;
                        case 'ask_custom':
                        case 'require_custom':
                        case 'show_custom':
                            foreach ($val as $i => $v) { $updated_options[$col][$i] = 1; } /* checkbox array with ints */
                            break;
                        case 'ask_fields':
                        case 'require_fields':
                        case 'show_fields':
                            foreach ($val as $v) { $updated_options[$col]["$v"] = 1; } /* checkbox array with names */
                            break;
                        default:
                            $updated_options[$col] = $val; /* a non-array normal field */
                            break;
                    }
                }
            }
            
            /* prevent E_NOTICE warnings */
            if (!isset($this->p->enable_pages_default)) { $this->p->enable_pages_default = 0; }
            if (!isset($this->p->enable_posts_default)) { $this->p->enable_posts_default = 0; }
            if (!isset($this->p->goto_show_button)) { $this->p->goto_show_button = 0; }
            if (!isset($this->p->support_us)) { $this->p->support_us = 0; }
            
            /* some int validation */
            $updated_options['enable_pages_default'] = intval($this->p->enable_pages_default);
            $updated_options['enable_posts_default'] = intval($this->p->enable_posts_default);
            $updated_options['form_location'] = intval($this->p->form_location);
            $updated_options['goto_show_button'] = intval($this->p->goto_show_button);
            $updated_options['reviews_per_page'] = intval($this->p->reviews_per_page);
            $updated_options['show_hcard'] = intval($this->p->show_hcard);
            $updated_options['show_hcard_on'] = intval($this->p->show_hcard_on);
            $updated_options['support_us'] = intval($this->p->support_us);
            
            if ($updated_options['reviews_per_page'] < 1) { $updated_options['reviews_per_page'] = 10; }

            if ($updated_options['show_hcard_on']) {
                if (
                    empty($updated_options['business_name']) ||
                    empty($updated_options['business_url']) ||
                    empty($updated_options['business_email']) ||
                    empty($updated_options['business_street']) ||
                    empty($updated_options['business_city']) ||
                    empty($updated_options['business_state']) ||
                    empty($updated_options['business_zip']) ||
                    empty($updated_options['business_phone'])
                ) {
                    $msg .= "* Notice: You must enter in ALL business information to use the hCard output *<br /><br />";
                    $updated_options['show_hcard_on'] = 0;
                }
            }
			
            $msg .= 'Your settings have been saved.';
            update_option('wpcr_options', $updated_options);
            $this->force_update_cache(); /* update any caches */
        }

        return $msg;
    }
	
    function show_activation() {
        echo '
        <div class="postbox" style="width:700px;">
            <h3>Notify me of new releases</h3>
            <div style="padding:10px; background:#ffffff;">
                <p style="color:#060;">If you would like to be notified of any critical security updates, please enter your email address below. Your information will only be used for notification of future releases.</p><br />
                <form method="post" action="">
                    <input type="hidden" name="optin" value="1" />
                    <label for="email">Email Address: </label><input type="text" size="32" id="email" name="email" />&nbsp;
                    <input type="submit" class="button-primary" value="OK!" name="submit" />&nbsp;
                    <input type="submit" class="button-primary" value="No Thanks!" name="submit" />
                </form>
                <p style="color:#BE5409;font-size:14px;font-weight:bold;"><br />Click "OK!" or "No Thanks!" above to access the full plugin settings.</p>
            </div>
        </div>';
    }
	
    function my_get_pages() { /* gets pages, even if hidden using a plugin */
        global $wpdb;
        
        $res = $wpdb->get_results("SELECT `ID`,`post_title` FROM `$wpdb->posts` WHERE `post_status` = 'publish' AND `post_type` = 'page' ORDER BY `ID`");
        return $res;
    }
	
    function show_options() {
        
        $su_checked = '';
        if ($this->options['support_us']) {
            $su_checked = 'checked';
        }
        
        $enable_posts_checked = '';
        if ($this->options['enable_posts_default']) {
            $enable_posts_checked = 'checked';
        }
        
        $enable_pages_checked = '';
        if ($this->options['enable_pages_default']) {
            $enable_pages_checked = 'checked';
        }
        
        $goto_show_button_checked = '';
        if ($this->options['goto_show_button']) {
            $goto_show_button_checked = 'checked';
        }
		
        $af = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['ask_fields']['fname'] == 1) { $af['fname'] = 'checked'; }
        if ($this->options['ask_fields']['femail'] == 1) { $af['femail'] = 'checked'; }
        if ($this->options['ask_fields']['fwebsite'] == 1) { $af['fwebsite'] = 'checked'; }
        if ($this->options['ask_fields']['ftitle'] == 1) { $af['ftitle'] = 'checked'; }

        $rf = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['require_fields']['fname'] == 1) { $rf['fname'] = 'checked'; }
        if ($this->options['require_fields']['femail'] == 1) { $rf['femail'] = 'checked'; }
        if ($this->options['require_fields']['fwebsite'] == 1) { $rf['fwebsite'] = 'checked'; }
        if ($this->options['require_fields']['ftitle'] == 1) { $rf['ftitle'] = 'checked'; }
        
        $sf = array('fname' => '','femail' => '','fwebsite' => '','ftitle' => '');
        if ($this->options['show_fields']['fname'] == 1) { $sf['fname'] = 'checked'; }
        if ($this->options['show_fields']['femail'] == 1) { $sf['femail'] = 'checked'; }
        if ($this->options['show_fields']['fwebsite'] == 1) { $sf['fwebsite'] = 'checked'; }
        if ($this->options['show_fields']['ftitle'] == 1) { $sf['ftitle'] = 'checked'; }
        
        echo '
        <div class="postbox" style="width:700px;">
            <h3>Display Options</h3>
            <div id="wpcr_ad">
                <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                    <legend>Tips</legend>
                </div>
                <div style="padding:10px;">
                    How to use: <small>When adding/editing any post/page, you have a setting block on the page for WP Customer Reviews. If you enable the plugin for that post, it will then use the default options set on this page.</small>
                    <br /><br />
                    Shortcodes: <small>The following shortcodes can be used in the page/post content of any page. These codes will not work when placed directly in a theme template file, since Wordpress does not parse their content. Shortcode features are in beta testing.</small>
                    <br /><br />
                    [WPCR_INSERT] <small>is available for you to use on any page/post. Simply include [WPCR_INSERT] in the content of the post where you would like the reviews/form output to be displayed. If this code is found, the plugin will automatically enable itself for the post.</small>
                    <br /><br />
                    [WPCR_SHOW 
                        POSTID="<span style="color:#00c;">ALL</span>" 
                        NUM="<span style="color:#00c;">3</span>" 
                        SNIPPET="" 
                        MORE="" 
                        HIDECUSTOM="<span style="color:#00c;">0</span>" 
                        HIDERESPONSE="<span style="color:#00c;">0</span>"] 
                    <br /><small>is available to show the latest reviews. Explanation below: <br /> 
                    POSTID="ALL" to show recent reviews from ALL posts/pages or POSTID="123" to show recent reviews from post/page ID #123<br />
                    NUM="3" will show a maximum of 3 reviews (without pagination).<br />
                    SNIPPET="140" will only show the first 140 characters of a review.<br />
                    MORE="view more" will show "... view more" with a link to the actual review on the associated page.<br />
                    HIDECUSTOM="1" will hide all custom fields in the shortcode output.<br />
                    HIDERESPONSE="1" will hide the administrator response to the review.<br />
                    </small>
                </div>
                <form method="post" action="">
                    <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                        <legend>Business Information (for hidden hCard)</legend>
                    </div>
                    <div style="padding:10px;">
                        <label for="show_hcard_on">Enable Business hCard output on: </label>
                        <select id="show_hcard_on" name="show_hcard_on">
                            <option ';if ($this->options['show_hcard_on'] == 1) { echo "selected"; } echo ' value="1">All wordpress posts &amp; pages</option>
                            <option ';if ($this->options['show_hcard_on'] == 2) { echo "selected"; } echo ' value="2">Homepage &amp; review page</option>
                            <option ';if ($this->options['show_hcard_on'] == 3) { echo "selected"; } echo ' value="3">Only the review page</option>
                            <option ';if ($this->options['show_hcard_on'] == 0) { echo "selected"; } echo ' value="0">Never</option>
                        </select><br />
                        <small>This will enable the hCard microformat, which includes your business contact information. This is recommended to enable for all posts &amp; pages.</small>
                        <br /><br />
                        <label for="show_hcard">Business hCard Visibility: </label>
                        <select id="show_hcard" name="show_hcard">
                            <option ';if ($this->options['show_hcard'] == 0) { echo "selected"; } echo ' value="0">Hide hCard to Visitors</option>
                            <option ';if ($this->options['show_hcard'] == 1) { echo "selected"; } echo ' value="1">Show hCard to Visitors</option>
                        </select><br />
                        <small>This will display the hCard to visitors (and search engines). Search engines will normally ignore microformat information that is hidden, so it is usually a good idea to set this to "Show". "Show" will ONLY affect individual enabled pages/posts. If you want to hide elements of the hCard output, the individual styles can be overridden by your theme CSS.</small>
                        <br /><br />
                        <label for="business_name">Business Name (<span style="color:#c00;">Required</span>): </label><input style="width:250px;" type="text" id="business_name" name="business_name" value="'.$this->options['business_name'].'" />
                        <br />
                        <small>This business name is a required field in the review microformat. This is why it is required.</small>
                        <br /><br />
                        <label for="business_url">Business URL: </label><input style="width:350px;" type="text" id="business_url" name="business_url" value="'.$this->options['business_url'].'" />
                        <br /><br />
                        <label for="business_email">Business Email: </label><input style="width:250px;" type="text" id="business_email" name="business_email" value="'.$this->options['business_email'].'" />
                        <br /><br />
                        <label for="business_street">Business Street Address: </label><input style="width:320px;" type="text" id="business_street" name="business_street" value="'.$this->options['business_street'].'" />
                        <br /><br />
                        <label for="business_city">City: </label><input style="width:150px;" type="text" id="business_city" name="business_city" value="'.$this->options['business_city'].'" />
                        &nbsp;
                        <label for="business_state">State (2 letters): </label><input style="width:40px;" type="text" id="business_state" name="business_state" value="'.$this->options['business_state'].'" />
                        &nbsp;
                        <label for="business_zip">Zip Code: </label><input style="width:60px;" type="text" id="business_zip" name="business_zip" value="'.$this->options['business_zip'].'" />
                        <br /><br />
                        <label for="business_country">Country: </label><input style="width:100px;" type="text" id="business_country" name="business_country" value="'.$this->options['business_country'].'" />
                        &nbsp;
                        <label for="business_phone">Phone # (555-555-5555): </label><input style="width:120px;" type="text" id="business_phone" name="business_phone" value="'.$this->options['business_phone'].'" />
                        <br />
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Save Changes" name="Submit"></div>
                    </div>
                    <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                        <legend>General Settings</legend>
                    </div>
                    <div style="padding:10px;padding-bottom:10px;">
                        <label for="hreview_type">Review Format: </label>
                        <select id="hreview_type" name="hreview_type">
                            <option ';if ($this->options['hreview_type'] == 'business') { echo "selected"; } echo ' value="business">Business</option>
                            <option ';if ($this->options['hreview_type'] == 'product') { echo "selected"; } echo ' value="product">Product</option>
                        </select><br />
                        <small>If using the "Product" type, you can enter the product name in the "WP Customer Reviews" box when editing your pages. If this is set to "Business", the plugin will present all reviews as if they are reviews of your business as listed above.</small>
                        <br /><br />
                        <input id="enable_posts_default" name="enable_posts_default" type="checkbox" '.$enable_posts_checked.' value="1" />&nbsp;<label for="enable_posts_default"><small>Enable the plugin by default for new posts.</small></label>
                        <br /><br />
                        <input id="enable_pages_default" name="enable_pages_default" type="checkbox" '.$enable_pages_checked.' value="1" />&nbsp;<label for="enable_pages_default"><small>Enable the plugin by default for new pages.</small></label>
                        <br /><br />
                        <input id="support_us" name="support_us" type="checkbox" '.$su_checked.' value="1" />&nbsp;<label for="support_us"><small>Support our work and keep this plugin free. By checking this box, a small "Powered by WP Customer Reviews" link will be placed at the bottom of pages that use the plugin.</small></label>
                        <br />
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Save Changes" name="Submit"></div>
                    </div>
                    <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                        <legend>Review Page Settings</legend>
                    </div>
                    <div style="padding:10px;padding-bottom:10px;">
                        <span style="color:#BE5409;">You can use this plugin on multiple pages/posts. You will find a "WP Customer Reviews" settings box when editing any page/post.</span>
                        <br /><br />
                        <label for="reviews_per_page">Reviews shown per page: </label><input style="width:40px;" type="text" id="reviews_per_page" name="reviews_per_page" value="'.$this->options['reviews_per_page'].'" />
                        <br /><br />
                        <label for="form_location">Location of Review Form: </label>
                        <select id="form_location" name="form_location">
                            <option ';if ($this->options['form_location'] == 0) { echo "selected"; } echo ' value="0">Above Reviews</option>
                            <option ';if ($this->options['form_location'] == 1) { echo "selected"; } echo ' value="1">Below Reviews</option>
                        </select>
                        <br /><br />
                        <label>Fields to ask for on review form: </label>
                        <input data-what="fname" id="ask_fname" name="ask_fields[]" type="checkbox" '.$af['fname'].' value="fname" />&nbsp;<label for="ask_fname"><small>Name</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="femail" id="ask_femail" name="ask_fields[]" type="checkbox" '.$af['femail'].' value="femail" />&nbsp;<label for="ask_femail"><small>Email</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="fwebsite" id="ask_fwebsite" name="ask_fields[]" type="checkbox" '.$af['fwebsite'].' value="fwebsite" />&nbsp;<label for="ask_fwebsite"><small>Website</small></label>&nbsp;&nbsp;&nbsp;
                        <input data-what="ftitle" id="ask_ftitle" name="ask_fields[]" type="checkbox" '.$af['ftitle'].' value="ftitle" />&nbsp;<label for="ask_ftitle"><small>Review Title</small></label>
                        <br /><br />
                        <label>Fields to require on review form: </label>
                        <input id="require_fname" name="require_fields[]" type="checkbox" '.$rf['fname'].' value="fname" />&nbsp;<label for="require_fname"><small>Name</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_femail" name="require_fields[]" type="checkbox" '.$rf['femail'].' value="femail" />&nbsp;<label for="require_femail"><small>Email</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_fwebsite" name="require_fields[]" type="checkbox" '.$rf['fwebsite'].' value="fwebsite" />&nbsp;<label for="require_fwebsite"><small>Website</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="require_ftitle" name="require_fields[]" type="checkbox" '.$rf['ftitle'].' value="ftitle" />&nbsp;<label for="require_ftitle"><small>Review Title</small></label>
                        <br /><br />
                        <label>Fields to show on each approved review: </label>
                        <input id="show_fname" name="show_fields[]" type="checkbox" '.$sf['fname'].' value="fname" />&nbsp;<label for="show_fname"><small>Name</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_femail" name="show_fields[]" type="checkbox" '.$sf['femail'].' value="femail" />&nbsp;<label for="show_femail"><small>Email</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_fwebsite" name="show_fields[]" type="checkbox" '.$sf['fwebsite'].' value="fwebsite" />&nbsp;<label for="show_fwebsite"><small>Website</small></label>&nbsp;&nbsp;&nbsp;
                        <input id="show_ftitle" name="show_fields[]" type="checkbox" '.$sf['ftitle'].' value="ftitle" />&nbsp;<label for="show_ftitle"><small>Review Title</small></label>
                        <br />
                        <small>It is usually NOT a good idea to show email addresses publicly.</small>
                        <br /><br />
                        <label>Custom fields on review form: </label>(<small>You can type in the names of any additional fields you would like here.</small>)
                        <div style="font-size:10px;padding-top:6px;">
                        ';
                        for ($i = 0; $i < 6; $i++) /* 6 custom fields */
                        {
                            if ( !isset($this->options['ask_custom'][$i]) ) { $this->options['ask_custom'][$i] = 0; }
                            if ( !isset($this->options['require_custom'][$i]) ) { $this->options['require_custom'][$i] = 0; }
                            if ( !isset($this->options['show_custom'][$i]) ) { $this->options['show_custom'][$i] = 0; }
                            
                            if ($this->options['ask_custom'][$i] == 1) { $caf = 'checked'; } else { $caf = ''; }
                            if ($this->options['require_custom'][$i] == 1) { $crf = 'checked'; } else { $crf = ''; }
                            if ($this->options['show_custom'][$i] == 1) { $csf = 'checked'; } else { $csf = ''; }
                            echo '
                            <label for="field_custom'.$i.'">Field Name: </label><input id="field_custom'.$i.'" name="field_custom['.$i.']" type="text" value="'.$this->options['field_custom'][$i].'" />&nbsp;&nbsp;&nbsp;
                            <input '.$caf.' class="custom_ask" data-id="'.$i.'" id="ask_custom'.$i.'" name="ask_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="ask_custom'.$i.'">Ask</label>&nbsp;&nbsp;&nbsp;
                            <input '.$crf.' class="custom_req" data-id="'.$i.'" id="require_custom'.$i.'" name="require_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="require_custom'.$i.'">Require</label>&nbsp;&nbsp;&nbsp;
                            <input '.$csf.' class="custom_show" data-id="'.$i.'" id="show_custom'.$i.'" name="show_custom['.$i.']" type="checkbox" value="1" />&nbsp;<label for="show_custom'.$i.'">Show</label><br />
                            ';
                        }
                        echo '
                        </div>
                        <br /><br />
                        <label for="title_tag">Heading to use for Review Titles: </label>
                        <select id="title_tag" name="title_tag">
                            <option ';if ($this->options['title_tag'] == 'h2') { echo "selected"; } echo ' value="h2">H2</option>
                            <option ';if ($this->options['title_tag'] == 'h3') { echo "selected"; } echo ' value="h3">H3</option>
                            <option ';if ($this->options['title_tag'] == 'h4') { echo "selected"; } echo ' value="h4">H4</option>
                            <option ';if ($this->options['title_tag'] == 'h5') { echo "selected"; } echo ' value="h5">H6</option>
                            <option ';if ($this->options['title_tag'] == 'h6') { echo "selected"; } echo ' value="h6">H7</option>
                        </select>
                        <br /><br />
                        <label for="goto_show_button">Show review form: </label><input type="checkbox" id="goto_show_button" name="goto_show_button" value="1" '.$goto_show_button_checked.' />
                        <br />
                        <small>If this option is unchecked, there will be no visible way for visitors to submit reviews.</small>
                        <br /><br />
                        <label for="goto_leave_text">Button text used to show review form: </label><input style="width:250px;" type="text" id="goto_leave_text" name="goto_leave_text" value="'.$this->options['goto_leave_text'].'" />
                        <br />
                        <small>This button will be shown above the first review.</small>
                        <br /><br />
                        <label for="leave_text">Text to be displayed above review form: </label><input style="width:250px;" type="text" id="goto_leave_text" name="goto_leave_text" value="'.$this->options['goto_leave_text'].'" />
                        <br />
                        <small>This will be shown as a heading immediately above the review form.</small>
                        <br /><br />
                        <label for="submit_button_text">Text to use for review form submit button: </label><input style="width:200px;" type="text" id="submit_button_text" name="submit_button_text" value="'.$this->options['submit_button_text'].'" />
                        <br />
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Save Changes" name="Submit"></div>
                    </div>
                    <div style="background:#eaf2fa;padding:6px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
                        <legend>Advanced</legend>
                    </div>
                    <div style="padding:10px;padding-bottom:0px;">
                        <small><span style="color:#c00;">Be very careful when using these options. They should do exactly what they say, but are experimental, so use them at your own risk. Most users do not need to even think about using these options, but they are here in case you need them.</span></small>
                        <br /><br />
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Enable Plugin for all Existing Posts" name="Submit"></div>
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Disable Plugin for all Existing Posts" name="Submit"></div>
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Enable Plugin for all Existing Pages" name="Submit"></div>
                        <div class="submit" style="padding:10px 0px 0px 0px;"><input type="submit" class="button-primary" value="Disable Plugin for all Existing Pages" name="Submit"></div>
                    </div>';
                    settings_fields("wpcr_options");
                    echo '
                </form>
                <br />
            </div>
        </div>';
        /* settings_fields is for Settings API / WPMU / future WP compatibility */
    }
    
    function security() {
        if (!current_user_can('manage_options'))
        {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
    }
	
    function real_admin_options() {
        $this->security();

        $msg = '';
		
		// make sure the db is created
		global $wpdb;
		$exists = $wpdb->get_var("SHOW TABLES LIKE '$this->dbtable'");
		if ($exists != $this->dbtable) {
			$this->parentClass->check_migrate(true);
			$exists = $wpdb->get_var("SHOW TABLES LIKE '$this->dbtable'");
			if ($exists != $this->dbtable) {
				print "<br /><br /><br />COULD NOT CREATE DATABASE TABLE, PLEASE REPORT THIS ERROR";
			}
		}
        
        if (!isset($this->p->Submit)) { $this->p->Submit = ''; }
        
        if ($this->p->Submit == 'Save Changes') {
            $msg = $this->update_options();
            $this->parentClass->get_options();
        }
        elseif ($this->p->Submit == 'Enable Plugin for all Existing Posts') {
            global $wpdb;
            $wpdb->query( "DELETE $wpdb->postmeta FROM $wpdb->postmeta
                            LEFT JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
                            WHERE $wpdb->posts.post_type = 'post' AND $wpdb->postmeta.meta_key = 'wpcr_enable' " );
            
            $wpdb->query( "INSERT INTO $wpdb->postmeta 
                            SELECT 0,$wpdb->posts.ID,'wpcr_enable',1
                            FROM $wpdb->posts
                            WHERE $wpdb->posts.post_type = 'post' " ); 
        }
        elseif ($this->p->Submit == 'Disable Plugin for all Existing Posts') {
            global $wpdb;
            $wpdb->query( "DELETE $wpdb->postmeta FROM $wpdb->postmeta
                            LEFT JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
                            WHERE $wpdb->posts.post_type = 'post' AND $wpdb->postmeta.meta_key = 'wpcr_enable' " );
        }
        elseif ($this->p->Submit == 'Enable Plugin for all Existing Pages') {
            global $wpdb;
            $wpdb->query( "DELETE $wpdb->postmeta FROM $wpdb->postmeta
                            LEFT JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
                            WHERE $wpdb->posts.post_type = 'page' AND $wpdb->postmeta.meta_key = 'wpcr_enable' " );
            
            $wpdb->query( "INSERT INTO $wpdb->postmeta 
                            SELECT 0,$wpdb->posts.ID,'wpcr_enable',1
                            FROM $wpdb->posts
                            WHERE $wpdb->posts.post_type = 'page' " );                 
        }
        elseif ($this->p->Submit == 'Disable Plugin for all Existing Pages') {
            global $wpdb;
            $wpdb->query( "DELETE $wpdb->postmeta FROM $wpdb->postmeta
                            LEFT JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
                            WHERE $wpdb->posts.post_type = 'page' AND $wpdb->postmeta.meta_key = 'wpcr_enable' " );
        }
        
        if (isset($this->p->email)) {
            $msg = $this->update_options();
            $this->parentClass->get_options();
        }
        
        echo '
        <div id="wpcr_respond_1" class="wrap">
            <h2>WP Customer Reviews - Options</h2>';
            if ($msg) { echo '<h3 style="color:#a00;">'.$msg.'</h3>'; }
            echo '
            <div class="metabox-holder">
            <div class="postbox" style="width:700px;">
                <h3 style="cursor:default;">About WP Customer Reviews</h3>
                <div style="padding:0 10px; background:#ffffff;">
                    <p>
                        Version: <strong>'.$this->plugin_version.'</strong>
					</p>';

					echo '
					<p style="margin-top:10px !important;padding:3px 5px 3px 5px !important;background:#fffbcc;border:1px solid #e6db55;color:#555;">
						WP Customer Reviews: Big News! Version 3 is on the way. 
						<a style="font-weight:bold;" target="_blank" href="http://www.gowebsolutions.com/wp-customer-reviews/?from=wpcr_plugin_notice_1">Click here for details</a> and to submit your feature requests.
					</p>
					';
						
					echo '
					<p style="margin-top:10px !important;">
                        WP Customer Reviews allows your customers and visitors to leave reviews or testimonials of your services. Reviews are Microformat enabled and can help crawlers such as Google Local Search and Google Places to index these reviews. The plugin also allows for your business information, in hCard microformat, to be (non-visibly) added to all pages.
                    </p>
					';
					
					echo '
                    <br />
                </div>
                <div style="padding:6px; background:#eaf2fa;">
                    Plugin Homepage: <a target="_blank" href="http://www.gowebsolutions.com/wp-customer-reviews/">http://www.gowebsolutions.com/wp-customer-reviews/</a><br /><br />
                    Support Forum: <a target="_blank" href="http://wordpress.org/tags/wp-customer-reviews?forum_id=10">http://wordpress.org/tags/wp-customer-reviews?forum_id=10</a><br /><br />
                    Support Email: <a href="mailto:aaron@gowebsolutions.com">aaron@gowebsolutions.com</a><br /><br />
                    <div style="color:#BE5409;font-weight:bold;">If you like this plugin, please <a target="_blank" href="http://wordpress.org/extend/plugins/wp-customer-reviews/">login and rate it 5 stars here</a> or consider a donation via our plugin homepage.</div>
                </div>
            </div>';
        
        if ($this->options['activate'] == 0) {
            $this->show_activation();
            echo '<br /></div>';
            return;
        }

        $this->show_options();
        echo '<br /></div>';
    }
	
    function real_admin_view_reviews() {      
        global $wpdb;
        
        if (!isset($this->p->s)) { $this->p->s = ''; }
        $this->p->s_orig = $this->p->s;
        
        if (!isset($this->p->review_status)) { $this->p->review_status = 0; }
        $this->p->review_status = intval($this->p->review_status);
        
        /* begin - actions */
        if (isset($this->p->action)) {
		
            if (isset($this->p->r)) {
                $this->p->r = intval($this->p->r);

                switch ($this->p->action) {
                    case 'deletereview':
                        $wpdb->query("DELETE FROM `$this->dbtable` WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'trashreview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=2 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'approvereview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=1 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'unapprovereview':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=0 WHERE `id`={$this->p->r} LIMIT 1");
                        break;
                    case 'update_field':
                        
                        ob_end_clean();
                        
                        if (!is_array($this->p->json)) {
                            header('HTTP/1.1 403 Forbidden');
                            echo json_encode(array("errors" => 'Bad Request'));
                            exit(); 
                        }
                        
                        $show_val = '';
                        $update_col = false;
                        $update_val = false;
                        
                        foreach ($this->p->json as $col => $val) {
                            
                            switch ($col) {
                                case 'date_time':
                                    $d = date("m/d/Y g:i a",strtotime($val));
                                    if (!$d || $d == '01/01/1970 12:00 am') {
                                        header('HTTP/1.1 403 Forbidden');
                                        echo json_encode(array("errors" => 'Bad Date Format'));
                                        exit(); 
                                    }
                                    
                                    $show_val = $d;
                                    $d2 = date("Y-m-d H:i:s",strtotime($val));
                                    $update_col = mysql_real_escape_string($col);
                                    $update_val = mysql_real_escape_string($d2);
                                    break;
                                    
                                default:
                                    if ($val == '') {
                                        header('HTTP/1.1 403 Forbidden');
                                        echo json_encode(array("errors" => 'Bad Value'));
                                        exit(); 
                                    }
									
                                    /* for storing in DB - fix with IE 8 workaround */
                                    $val = str_replace( array("<br />","<br/>","<br>") , "\n" , $val );	

                                    if (substr($col,0,7) == 'custom_') /* updating custom fields */
                                    {
                                        $custom_fields = array(); /* used for insert as well */
                                        $custom_count = count($this->options['field_custom']); /* used for insert as well */
                                        for ($i = 0; $i < $custom_count; $i++)
                                        {
                                            $custom_fields[$i] = $this->options['field_custom'][$i];
                                        }

                                        $custom_num = substr($col,7); /* gets the number after the _ */
                                        /* get the old custom value */
                                        $old_value = $wpdb->get_results("SELECT `custom_fields` FROM `$this->dbtable` WHERE `id`={$this->p->r} LIMIT 1");										
                                        if ($old_value && $wpdb->num_rows)
                                        {
                                            $old_value = @unserialize($old_value[0]->custom_fields);
                                            if (!is_array($old_value)) { $old_value = array(); }
                                            $custom_name = $custom_fields[$custom_num];
                                            $old_value[$custom_name] = $val;
                                            $new_value = serialize($old_value);											
                                            $update_col = mysql_real_escape_string('custom_fields');
                                            $update_val = mysql_real_escape_string($new_value);
                                        }
                                    }
                                    else /* updating regular fields */
                                    {									
                                        $update_col = mysql_real_escape_string($col);
                                        $update_val = mysql_real_escape_string($val);
                                    }

                                    $show_val = $val;
                                    
                                    break;
                            }
                            
                        }
                        
                        if ($update_col !== false && $update_val !== false) {
                            $query = "UPDATE `$this->dbtable` SET `$update_col`='$update_val' WHERE `id`={$this->p->r} LIMIT 1";
                            $wpdb->query($query);
                            echo $show_val;
                        }
                        
                        exit();
                        break;
                }
            }
			
            if ( isset($this->p->delete_reviews) && is_array($this->p->delete_reviews) && count($this->p->delete_reviews) ) {
                
                foreach ($this->p->delete_reviews as $i => $rid) {
                    $this->p->delete_reviews[$i] = intval($rid);
                }
				
                if (isset($this->p->act2)) { $this->p->action = $this->p->action2; }
				
                switch ($this->p->action) {
                    case 'bapprove':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=1 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'bunapprove':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=0 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'btrash':
                        $wpdb->query("UPDATE `$this->dbtable` SET `status`=2 WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                    case 'bdelete':
                        $wpdb->query("DELETE FROM `$this->dbtable` WHERE `id` IN(".implode(',',$this->p->delete_reviews).")");
                        break;
                }
            }
			
            $this->force_update_cache(); /* update any caches */            
            $this->parentClass->wpcr_redirect("?page=wpcr_view_reviews&review_status={$this->p->review_status}");
        }
        /* end - actions */
        
        /* begin - searching */
        if ($this->p->review_status == -1) {
            $sql_where = '-1=-1';
        } else {
            $sql_where = 'status='.$this->p->review_status;
        }
        
        $and_clause = '';
        if ($this->p->s != '') { /* searching */
            $this->p->s = '%'.$this->p->s.'%';
            $sql_where = '-1=-1';
            $this->p->review_status = -1;
            $and_clause = "AND (`reviewer_name` LIKE %s OR `reviewer_email` LIKE %s OR `reviewer_ip` LIKE %s OR `review_text` LIKE %s OR `review_response` LIKE %s OR `reviewer_url` LIKE %s)";
            $and_clause = $wpdb->prepare($and_clause,$this->p->s,$this->p->s,$this->p->s,$this->p->s,$this->p->s,$this->p->s);
            
            $query = "SELECT 
                `id`,
                `date_time`,
                `reviewer_name`,
                `reviewer_email`,
                `reviewer_ip`,
                `review_title`,
                `review_text`,
                `review_response`,
                `review_rating`,
                `reviewer_url`,
                `status`,
                `page_id`,
                `custom_fields`
                FROM `$this->dbtable` WHERE $sql_where $and_clause ORDER BY `id` DESC"; 
            
            $reviews = $wpdb->get_results($query);
            $total_reviews = 0; /* no pagination for searches */
        }
        /* end - searching */
        else
        {
            $arr_Reviews = $this->parentClass->get_reviews(-1,$this->page,$this->options['reviews_per_page'],$this->p->review_status);
            $reviews = $arr_Reviews[0];
            $total_reviews = $arr_Reviews[1];
        }
		
        $status_text = "";
        switch ($this->p->review_status)
        {
            case -1:
                $status_text = 'Submitted';
                break;
            case 0:
                $status_text = 'Pending';
                break;
            case 1:
                $status_text = 'Approved';
                break;
            case 2:
                $status_text = 'Trashed';
                break;
        }
        
        $pending_count = $wpdb->get_results("SELECT COUNT(*) AS `count_pending` FROM `$this->dbtable` WHERE `status`=0");
        $pending_count = $pending_count[0]->count_pending;
		
        $approved_count = $wpdb->get_results("SELECT COUNT(*) AS `count_approved` FROM `$this->dbtable` WHERE `status`=1");
        $approved_count = $approved_count[0]->count_approved;

        $trash_count = $wpdb->get_results("SELECT COUNT(*) AS `count_trash` FROM `$this->dbtable` WHERE `status`=2");
        $trash_count = $trash_count[0]->count_trash;
        ?>
        <div id="wpcr_respond_1" class="wrap">
            <div class="icon32" id="icon-edit-comments"><br /></div>
            <h2>Customer Reviews - <?php echo $status_text; ?> Reviews</h2>
            
              <ul class="subsubsub">
                <li class="all"><a <?php if ($this->p->review_status == -1) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=-1">All</a> |</li>
                <li class="moderated"><a <?php if ($this->p->review_status == 0) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=0">Pending 
                    <span class="count">(<span class="pending-count"><?php echo $pending_count;?></span>)</span></a> |
                </li>
                <li class="approved"><a <?php if ($this->p->review_status == 1) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=1">Approved
                    <span class="count">(<span class="pending-count"><?php echo $approved_count;?></span>)</span></a> |
                </li>
                <li class="trash"><a <?php if ($this->p->review_status == 2) { echo 'class="current"'; } ?> href="?page=wpcr_view_reviews&amp;review_status=2">Trash</a>
                    <span class="count">(<span class="pending-count"><?php echo $trash_count;?></span>)</span></a>
                </li>
              </ul>

              <form method="GET" action="" id="search-form" name="search-form">
                  <p class="search-box">
                      <?php if ($this->p->s_orig): ?><span style='color:#c00;font-weight:bold;'>RESULTS FOR: </span><?php endif; ?>
                      <label for="comment-search-input" class="screen-reader-text">Search Reviews:</label> 
                      <input type="text" value="<?php echo $this->p->s_orig; ?>" name="s" id="comment-search-input" />
                      <input type="hidden" name="page" value="wpcr_view_reviews" />
                      <input type="submit" class="button" value="Search Reviews" />
                  </p>
              </form>

              <form method="POST" action="?page=wpcr_view_reviews" id="comments-form" name="comments-form">
              <input type="hidden" name="review_status" value="<?php echo $this->p->review_status; ?>" />
              <div class="tablenav">
                <div class="alignleft actions">
                      <select name="action">
                            <option selected="selected" value="-1">Bulk Actions</option>
                            <option value="bunapprove">Unapprove</option>
                            <option value="bapprove">Approve</option>
                            <option value="btrash">Move to Trash</option>
                            <option value="bdelete">Delete Forever</option>
                      </select>&nbsp;
                      <input type="submit" class="button-secondary apply" name="act" value="Apply" id="doaction" />
                </div><br class="clear" />
              </div>
			  
              <div class="clear"></div>
              <table cellspacing="0" class="widefat comments fixed">
                <thead>
                  <tr>
                    <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox" /></th>
                    <th style="" class="manage-column column-author" id="author" scope="col">Author</th>
                    <th style="" class="manage-column column-comment" id="comment" scope="col">Review</th>
                  </tr>
                </thead>

                <tfoot>
                  <tr>
                    <th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox" /></th>
                    <th style="" class="manage-column column-author" scope="col">Author</th>
                    <th style="" class="manage-column column-comment" scope="col">Review</th>
                  </tr>
                </tfoot>

                <tbody class="list:comment" id="the-comment-list">
                  <?php
                  if (count($reviews) == 0) {
                      ?>
                        <tr><td colspan="3" align="center"><br />There are no <strong><?php echo $status_text; ?></strong> reviews yet.<br /><br /></td></tr>
                      <?php
                  }
                                    
                  foreach ($reviews as $review)
                  {                    
                      $rid = $review->id;
                      $update_path = get_admin_url()."admin-ajax.php?page=wpcr_view_reviews&r=$rid&action=update_field";
                      $hash = md5( strtolower( trim( $review->reviewer_email ) ) );
                      $review->review_title = stripslashes($review->review_title);
                      $review->review_text = stripslashes($review->review_text);
                      $review->review_response = stripslashes($review->review_response);
                      $review->reviewer_name = stripslashes($review->reviewer_name);
                      if ($review->reviewer_name == '') { $review->reviewer_name = 'Anonymous'; }
                      $review_text = nl2br($review->review_text);
                      $review_text = str_replace( array("\r\n","\r","\n") , "" , $review_text );
                      $review_response = nl2br($review->review_response);
                      $review_response = str_replace( array("\r\n","\r","\n") , "" , $review_response );
                      $page = get_post($review->page_id);
                      if (!$page) { continue; } /* page no longer exists */
                  ?>
                      <tr class="approved" id="review-<?php echo $rid;?>">
                        <th class="check-column" scope="row"><input type="checkbox" value="<?php echo $rid;?>" name="delete_reviews[]" /></th>
                        <td class="author column-author">
                            <img width="32" height="32" class="avatar avatar-32 photo" src=
                            "http://1.gravatar.com/avatar/<?php echo $hash; ?>?s=32&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D32&amp;r=G"
                            alt="" />&nbsp;<span style="font-weight:bold;" class="best_in_place" data-url='<?php echo $update_path; ?>' data-object='json' data-attribute='reviewer_name'><?php echo $review->reviewer_name; ?></span>
                            <br />
                            <a href="<?php echo $review->reviewer_url; ?>"><?php echo $review->reviewer_url; ?></a><br />
                            <a href="mailto:<?php echo $review->reviewer_email; ?>"><?php echo $review->reviewer_email; ?></a><br />
                            <a href="?page=wpcr_view_reviews&amp;s=<?php echo $review->reviewer_ip; ?>"><?php echo $review->reviewer_ip; ?></a><br />
                            <?php
                            $custom_count = count($this->options['field_custom']); /* used for insert as well */
                            $custom_unserialized = @unserialize($review->custom_fields);
                            if ($custom_unserialized !== false)
                            {							
                                for ($i = 0; $i < $custom_count; $i++)
                                {
                                    $custom_field_name = $this->options['field_custom'][$i];
                                    if ( isset($custom_unserialized[$custom_field_name]) ) {
                                        $custom_value = $custom_unserialized[$custom_field_name];
                                        if ($custom_value != '')
                                        {
                                            echo "$custom_field_name: <span class='best_in_place' data-url='$update_path' data-object='json' data-attribute='custom_$i'>$custom_value</span><br />";
                                        }
                                    }
                                }
                            }
                            ?>
                            <div style="margin-left:-4px;">
                                <div style="height:22px;" class="best_in_place" 
                                     data-collection='[[1,"Rated 1 Star"],[2,"Rated 2 Stars"],[3,"Rated 3 Stars"],[4,"Rated 4 Stars"],[5,"Rated 5 Stars"]]' 
                                     data-url='<?php echo $update_path; ?>' 
                                     data-object='json'
                                     data-attribute='review_rating' 
                                     data-callback='make_stars_from_rating'
                                     data-type='select'><?php echo $this->parentClass->output_rating($review->review_rating,false); ?></div>
                            </div>
                        </td>
                        <td class="comment column-comment">
                          <div class="wpcr-submitted-on">
                            <span class="best_in_place" data-url='<?php echo $update_path; ?>' data-object='json' data-attribute='date_time'>
                            <?php echo date("m/d/Y g:i a",strtotime($review->date_time)); ?></a>
                            </span>&nbsp;on&nbsp;<?php echo get_the_title($review->page_id); ?>
                            <?php if ($review->status == 1) : ?>[<a target="_blank" href="<?php echo $this->parentClass->get_jumplink_for_review($review,$this->page); ?>">View Review on Page</a>]<?php endif; ?>
                          </div>
                          <p>
                              <span style="font-size:13px;font-weight:bold;">Title:&nbsp;</span>
                              <span style="font-size:14px; font-weight:bold;" 
                                    class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>' 
                                    data-object='json'
                                    data-attribute='review_title'><?php echo $review->review_title; ?></span>
                              <br /><br />
                              <div class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>' 
                                    data-object='json'
                                    data-attribute='review_text' 
                                    data-callback='callback_review_text'
                                    data-type='textarea'><?php echo $review_text; ?></div>
                             <div style="font-size:13px;font-weight:bold;">
                                 <br />
                                 Official Response:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                 <span style="font-size:11px;font-style:italic;">Leave this blank if you do not want it to be public</span>
                             </div>
                             <div class="best_in_place" 
                                    data-url='<?php echo $update_path; ?>'
                                    data-object='json'
                                    data-attribute='review_response' 
                                    data-callback='callback_review_text'
                                    data-type='textarea'><?php echo $review_response; ?></div>
                          </p>
                          <div class="row-actions">
                            <span class="approve <?php if ($review->status == 0 || $review->status == 2) { echo 'wpcr_show'; } else { echo 'wpcr_hide'; }?>"><a title="Mark as Approved"
                            href="?page=wpcr_view_reviews&amp;action=approvereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            Mark as Approved</a>&nbsp;|&nbsp;</span>
                            <span class="unapprove <?php if ($review->status == 1 || $review->status == 2) { echo 'wpcr_show'; } else { echo 'wpcr_hide'; }?>"><a title="Mark as Unapproved"
                            href="?page=wpcr_view_reviews&amp;action=unapprovereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            Mark as Unapproved</a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                            <span class="trash <?php if ($review->status == 2) { echo 'wpcr_hide'; } else { echo 'wpcr_show'; }?>"><a title="Move to Trash" 
                            href= "?page=wpcr_view_reviews&amp;action=trashreview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            Move to Trash</a><?php if ($review->status != 2): ?>&nbsp;|&nbsp;<?php endif; ?></span>
                            <span class="trash <?php if ($review->status == 2) { echo 'wpcr_hide'; } else { echo 'wpcr_show'; }?>"><a title="Delete Forever" 
                            href= "?page=wpcr_view_reviews&amp;action=deletereview&amp;r=<?php echo $rid;?>&amp;review_status=<?php echo $this->p->review_status;?>">
                            Delete Forever</a></span>
                          </div>
                        </td>
                      </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>

              <div class="tablenav">
                <div class="alignleft actions" style="float:left;">
                      <select name="action2">
                            <option selected="selected" value="-1">Bulk Actions</option>
                            <option value="bunapprove">Unapprove</option>
                            <option value="bapprove">Approve</option>
                            <option value="btrash">Move to Trash</option>
                            <option value="bdelete">Delete Forever</option>
                      </select>&nbsp;
                      <input type="submit" class="button-secondary apply" name="act2" value="Apply" id="doaction2" />
                </div>
                <div class="alignleft actions" style="float:left;padding-left:20px;"><?php echo $this->parentClass->pagination($total_reviews, $this->options['reviews_per_page']); ?></div>  
                <br class="clear" />
              </div>
            </form>

            <div id="ajax-response"></div>
          </div>
        <?php
    }
	
}

if (!defined('IN_WPCR_ADMIN')) {
    global $WPCustomerReviews, $WPCustomerReviewsAdmin;
    $WPCustomerReviewsAdmin = new WPCustomerReviewsAdmin($WPCustomerReviews);
}
?>