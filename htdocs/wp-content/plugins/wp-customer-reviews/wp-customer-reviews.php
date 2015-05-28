<?php
/*
 * Plugin Name: WP Customer Reviews
 * Plugin URI: http://www.gowebsolutions.com/wp-customer-reviews/
 * Description: WP Customer Reviews allows your customers and visitors to leave reviews or testimonials of your services. Reviews are Microformat enabled (hReview).
 * Version: 2.4.8
 * Revision Date: August 22, 2013
 * Requires at least: WP 2.8.6
 * Tested up to: WP 3.6
 * Author: Go Web Solutions
 * Author URI: http://www.gowebsolutions.com/
 * License: GNU General Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

class WPCustomerReviews {

    var $dbtable = 'wpcreviews';
    var $force_active_page = false;
    var $got_aggregate = false;
    var $options = array();
    var $p = '';
    var $page = 1;
    var $plugin_version = '0.0.0';
    var $shown_form = false;
    var $shown_hcard = false;
    var $status_msg = '';

    function WPCustomerReviews() {
        global $wpdb;

        define('IN_WPCR', 1);
        
        /* uncomment the below block to display strict/notice errors */
        /*
        restore_error_handler();
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('html_errors',TRUE);
        ini_set('display_errors',TRUE);
        */

        $this->dbtable = $wpdb->prefix . $this->dbtable;
        $this->plugin_version = $this->plugin_get_version();

        add_action('the_content', array(&$this, 'do_the_content'), 10); /* prio 10 prevents a conflict with some odd themes */
        add_action('init', array(&$this, 'init')); /* init also tries to insert script/styles */
        add_action('admin_init', array(&$this, 'admin_init'));
        
		add_action('admin_notices', array(&$this, 'notice_init')); /* admin notices */
		
        add_action('template_redirect',array(&$this, 'template_redirect')); /* handle redirects and form posts, and add style/script if needed */
        
        add_action('admin_menu', array(&$this, 'addmenu'));
        add_action('wp_ajax_update_field', array(&$this, 'admin_view_reviews')); /* special ajax stuff */
        add_action('save_post', array(&$this, 'admin_save_post'), 10, 2); /* 2 arguments */
        
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'plugin_settings_link'));
    }
	
	/* begin - admin notices */
	/* keep out of admin file */
	function notice_init() {
	
		return true; /* removed per WP guidelines */
	
		global $current_user;
		$user_id = $current_user->ID;
		
		$url = get_admin_url().'options-general.php?page=wpcr_options';
		
		/* Check if the user has already clicked to ignore the message using meta */
		if ( ! get_user_meta($user_id, 'wpcr_admin_notice_read_1') ) {
			echo '<div class="updated"><p>'; 
			echo '
				WP Customer Reviews: Big News! Version 3 is on the way. 
				<a style="font-weight:bold;" target="_blank" href="'.$url.'&wpcr_notice_1=redir">Click here for details</a> and to submit your feature requests.&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; 
				<a href="'.$url.'&wpcr_notice_1=ignore">Hide Notice</a>
			';
			echo "</p></div>";
		}
	}
	/* end - admin notices */

    /* keep out of admin file */
    function plugin_settings_link($links) {
        $url = get_admin_url().'options-general.php?page=wpcr_options';
        $settings_link = '<a href="'.$url.'"><img src="' . $this->getpluginurl() . 'star.png" />&nbsp;Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /* keep out of admin file */
    function addmenu() {
        add_options_page('Customer Reviews', '<img src="' . $this->getpluginurl() . 'star.png" />&nbsp;Customer Reviews', 'manage_options', 'wpcr_options', array(&$this, 'admin_options'));
        add_menu_page('Customer Reviews', 'Customer Reviews', 'edit_others_posts', 'wpcr_view_reviews', array(&$this, 'admin_view_reviews'), $this->getpluginurl() . 'star.png', '50.91'); /* try to resolve issues with other plugins */
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->wpcr_add_meta_box();
    }

    /* forward to admin file */
    function admin_options() {
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->real_admin_options();
    }

    /* forward to admin file */
    function admin_save_post($post_id, $post) {
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->real_admin_save_post($post_id);
    }

    /* forward to admin file */
    function admin_view_reviews() {
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->real_admin_view_reviews();
    }
    
    /* returns current plugin version */
    function plugin_get_version() {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php');
        $plugin_data = get_plugin_data( __FILE__ );
        $plugin_version = $plugin_data['Version'];
        return $plugin_version;
    }
    
    function get_jumplink_for_review($review,$page) {
        /* $page will be 1 for shortcode usage since it pulls most recent, which SHOULD all be on page 1 */
        $link = get_permalink( $review->page_id );
        
        if (strpos($link,'?') === false) {
            $link = trailingslashit($link) . "?wpcrp=$page#hreview-$review->id";
        } else {
            $link = $link . "&wpcrp=$page#hreview-$review->id";
        }
        
        return $link;
    }

    function get_options() {
        $home_domain = @parse_url(get_home_url());
        $home_domain = $home_domain['scheme'] . "://" . $home_domain['host'] . '/';

        $default_options = array(
            'act_email' => '',
            'act_uniq' => '',
            'activate' => 0,
            'ask_custom' => array(),
            'ask_fields' => array('fname' => 1, 'femail' => 1, 'fwebsite' => 1, 'ftitle' => 1, 'fage' => 0, 'fgender' => 0),
            'business_city' => '',
            'business_country' => 'USA',
            'business_email' => get_bloginfo('admin_email'),
            'business_name' => get_bloginfo('name'),
            'business_phone' => '',
            'business_state' => '',
            'business_street' => '',
            'business_url' => $home_domain,
            'business_zip' => '',
            'dbversion' => 0,
            'enable_posts_default' => 0,
            'enable_pages_default' => 0,
            'field_custom' => array(),
            'form_location' => 0,
            'goto_leave_text' => 'Click here to submit your review.',
            'goto_show_button' => 1,
            'hreview_type' => 'business',
            'leave_text' => 'Submit your review',
            'require_custom' => array(),
            'require_fields' => array('fname' => 1, 'femail' => 1, 'fwebsite' => 0, 'ftitle' => 0, 'fage' => 0, 'fgender' => 0),
            'reviews_per_page' => 10,
            'show_custom' => array(),
            'show_fields' => array('fname' => 1, 'femail' => 0, 'fwebsite' => 0, 'ftitle' => 1, 'fage' => 0, 'fgender' => 0),
            'show_hcard' => 1,
            'show_hcard_on' => 1,
            'submit_button_text' => 'Submit your review',
            'support_us' => 0,
            'title_tag' => 'h2'
        );
        
        $this->options = get_option('wpcr_options', $default_options);

        /* magically easy migrations to newer versions */
        $has_new = false;
        foreach ($default_options as $col => $def_val) {

            if (!isset($this->options[$col])) {
                $this->options[$col] = $def_val;
                $has_new = true;
            }

            if (is_array($def_val)) {
                foreach ($def_val as $acol => $aval) {
                    if (!isset($this->options[$col][$acol])) {
                        $this->options[$col][$acol] = $aval;
                        $has_new = true;
                    }
                }
            }
        }

        if ($has_new) {
            update_option('wpcr_options', $this->options);
        }
    }

    function make_p_obj() {
        $this->p = new stdClass();

        foreach ($_GET as $c => $val) {
            if (is_array($val)) {
                $this->p->$c = $val;
            } else {
                $this->p->$c = trim(stripslashes($val));
            }
        }

        foreach ($_POST as $c => $val) {
            if (is_array($val)) {
                $this->p->$c = $val;
            } else {
                $this->p->$c = trim(stripslashes($val));
            }
        }
    }

    function check_migrate() {
        global $wpdb;
        $migrated = false;

        /* remove me after official release */
        $current_dbversion = intval(str_replace('.', '', $this->options['dbversion']));
        $plugin_db_version = intval(str_replace('.', '', $this->plugin_version));

        if ($current_dbversion == $plugin_db_version) {
            return false;
        }
        
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->createUpdateReviewtable(); /* creates AND updates table */

        /* initial installation */
        if ($current_dbversion == 0) {
            $this->options['dbversion'] = $plugin_db_version;
            $current_dbversion = $plugin_db_version;
            update_option('wpcr_options', $this->options);
            return false;
        }

        /* check for upgrades if needed */

        /* upgrade to 2.0.0 */
        if ($current_dbversion < 200) {
            /* add multiple page support to database */

            /* change all current reviews to use the selected page id */
            $pageID = intval($this->options['selected_pageid']);
            $wpdb->query("UPDATE `$this->dbtable` SET `page_id`=$pageID WHERE `page_id`=0");

            /* add new meta to existing selected page */
            update_post_meta($pageID, 'wpcr_enable', 1);

            $this->options['dbversion'] = 200;
            $current_dbversion = 200;
            update_option('wpcr_options', $this->options);
            $migrated = true;
        }

        /* done with all migrations, push dbversion to current version */
        if ($current_dbversion != $plugin_db_version || $migrated == true) {
            $this->options['dbversion'] = $plugin_db_version;
            $current_dbversion = $plugin_db_version;
            update_option('wpcr_options', $this->options);

            global $WPCustomerReviewsAdmin;
            $this->include_admin(); /* include admin functions */
            $WPCustomerReviewsAdmin->notify_activate(3);
            $WPCustomerReviewsAdmin->force_update_cache(); /* update any caches */

            return true;
        }

        return false;
    }
    
    function is_active_page() {
        global $post;
        
        $has_shortcode = $this->force_active_page;
        if ( $has_shortcode !== false ) {
            return 'shortcode';
        }
        
        if ( !isset($post) || !isset($post->ID) || intval($post->ID) == 0 ) {
            return false; /* we can only use the plugin if we have a valid post ID */
        }
        
        if (!is_singular()) {
            return false; /* not on a single post/page view */
        }
        
        $wpcr_enabled_post = get_post_meta($post->ID, 'wpcr_enable', true);
        if ( $wpcr_enabled_post ) {
            return 'enabled';
        }
        
        return false;
    }
    
    function add_style_script() {
        /* to prevent compatibility issues and for shortcodes, add to every page */
        wp_enqueue_style('wp-customer-reviews');
		wp_enqueue_script('wp-customer-reviews');
    }
	
	function template_redirect() {
	
		/* do this in template_redirect so we can try to redirect cleanly */
        global $post;
        if (!isset($post) || !isset($post->ID)) {
            $post = new stdClass();
            $post->ID = 0;
        }
        
        if (isset($_COOKIE['wpcr_status_msg'])) {
            $this->status_msg = $_COOKIE['wpcr_status_msg'];
            if ( !headers_sent() ) {
                setcookie('wpcr_status_msg', '', time() - 3600); /* delete the cookie */
                unset($_COOKIE['wpcr_status_msg']);
            }
        }
        
        $GET_P = "submitwpcr_$post->ID";

        if ($post->ID > 0 && isset($this->p->$GET_P) && $this->p->$GET_P == $this->options['submit_button_text'])
        {
            $msg = $this->add_review($post->ID);
            $has_error = $msg[0];
            $status_msg = $msg[1];
            $url = get_permalink($post->ID);
            $cookie = array('wpcr_status_msg' => $status_msg);
            $this->wpcr_redirect($url, $cookie);
        }
	}
    
    function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = '';

        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }

        return $str;
    }

    function get_aggregate_reviews($pageID) {
        if ($this->got_aggregate !== false) {
            return $this->got_aggregate;
        }

        global $wpdb;

        $pageID = intval($pageID);
        $row = $wpdb->get_results("SELECT COUNT(*) AS `total`,AVG(review_rating) AS `aggregate_rating`,MAX(review_rating) AS `max_rating` FROM `$this->dbtable` WHERE `page_id`=$pageID AND `status`=1");

        /* make sure we have at least one review before continuing below */
        if ($wpdb->num_rows == 0 || $row[0]->total == 0) {
            $this->got_aggregate = array("aggregate" => 0, "max" => 0, "total" => 0, "text" => 'Reviews for my site');
            return false;
        }

        $aggregate_rating = $row[0]->aggregate_rating;
        $max_rating = $row[0]->max_rating;
        $total_reviews = $row[0]->total;

        $row = $wpdb->get_results("SELECT `review_text` FROM `$this->dbtable` WHERE `page_id`=$pageID AND `status`=1 ORDER BY `date_time` DESC LIMIT 1");
        $sample_text = substr($row[0]->review_text, 0, 180);

        $this->got_aggregate = array("aggregate" => $aggregate_rating, "max" => $max_rating, "total" => $total_reviews, "text" => $sample_text);
        return true;
    }

    function get_reviews($postID, $startpage, $perpage, $status) {
        global $wpdb;

        $startpage = $startpage - 1; /* mysql starts at 0 instead of 1, so reduce them all by 1 */
        if ($startpage < 0) { $startpage = 0; }

        $limit = 'LIMIT ' . $startpage * $perpage . ',' . $perpage;

        if ($status == -1) {
            $qry_status = '1=1';
        } else {
            $qry_status = "`status`=$status";
        }

        $postID = intval($postID);
        if ($postID == -1) {
            $and_post = '';
        } else {
            $and_post = "AND `page_id`=$postID";
        }

        $reviews = $wpdb->get_results("SELECT 
            `id`,
            `date_time`,
            `reviewer_name`,
            `reviewer_email`,
            `review_title`,
            `review_text`,
            `review_response`,
            `review_rating`,
            `reviewer_url`,
            `reviewer_ip`,
            `status`,
            `page_id`,
            `custom_fields`
            FROM `$this->dbtable` WHERE $qry_status $and_post ORDER BY `date_time` DESC $limit
            ");

        $total_reviews = $wpdb->get_results("SELECT COUNT(*) AS `total` FROM `$this->dbtable` WHERE $qry_status $and_post");
        $total_reviews = $total_reviews[0]->total;

        return array($reviews, $total_reviews);
    }

    function aggregate_footer() {
        
        $aggregate_footer_output = '';
        
        if ($this->options['show_hcard_on'] != 0 && $this->shown_hcard === false) {

            $this->shown_hcard = true;

            /* start - make sure we should continue */
            $show = false;

            if ( $this->options['show_hcard_on'] == 1 ) {
                $show = true;
            } else if ( $this->options['show_hcard_on'] == 2 && ( is_home() || is_front_page() ) ) {
                $show = true;
            } else if ( $this->options['show_hcard_on'] == 3 && $this->is_active_page() ) {
                $show = true;
            }
            /* end - make sure we should continue */
            
            $div_id = "wpcr_hcard_h";
            if ( $this->is_active_page() ) {
                if ( $this->options['show_hcard'] == 1 ) {
                    $div_id = "wpcr_hcard_s";
                }
            }

            if ($show) { /* we append like this to prevent newlines and wpautop issues */
                
                $aggregate_footer_output = '<div id="' . $div_id . '" class="vcard">';
                $aggregate_footer_output .= '<a class="url fn org" href="' . $this->options['business_url'] . '">' . $this->options['business_name'] . '</a><br />';
                
                if (
                        $this->options['business_street'] != '' || 
                        $this->options['business_city'] != '' ||
                        $this->options['business_state'] != '' ||
                        $this->options['business_zip'] != '' ||
                        $this->options['business_country'] != ''
                   )
                {
                    $aggregate_footer_output .= '<span class="adr">';
                    if ($this->options['business_street'] != '') {
                        $aggregate_footer_output .= '<span class="street-address">' . $this->options['business_street'] . '</span>&nbsp;';
                    }
                    if ($this->options['business_city'] != '') {
                        $aggregate_footer_output .='<span class="locality">' . $this->options['business_city'] . '</span>,&nbsp;';
                    }
                    if ($this->options['business_state'] != '') {
                        $aggregate_footer_output .='<span class="region">' . $this->options['business_state'] . '</span>,&nbsp;';
                    }
                    if ($this->options['business_zip'] != '') {
                        $aggregate_footer_output .='<span class="postal-code">' . $this->options['business_zip'] . '</span>&nbsp;';
                    }
                    if ($this->options['business_country'] != '') {
                        $aggregate_footer_output .='<span class="country-name">' . $this->options['business_country'] . '</span>&nbsp;';
                    }

                    $aggregate_footer_output .= '</span>';
                }

                if ($this->options['business_email'] != '' && $this->options['business_phone'] != '') {
                    $aggregate_footer_output .= '<br />';
                }

                if ($this->options['business_email'] != '') {
                    $aggregate_footer_output .= '<a class="email" href="mailto:' . $this->options['business_email'] . '">' . $this->options['business_email'] . '</a>';
                }
                if ($this->options['business_email'] != '' && $this->options['business_phone'] != '') {
                    $aggregate_footer_output .= '&nbsp;&bull;&nbsp;';
                }
                if ($this->options['business_phone'] != '') {
                    $aggregate_footer_output .= '<span class="tel">' . $this->options['business_phone'] . '</span>';
                }

                $aggregate_footer_output .= '</div>';
            }
        }

        return $aggregate_footer_output;
    }

    function iso8601($time=false) {
        if ($time === false)
            $time = time();
        $date = date('Y-m-d\TH:i:sO', $time);
        return (substr($date, 0, strlen($date) - 2) . ':' . substr($date, -2));
    }

    function pagination($total_results, $reviews_per_page) {
        global $post; /* will exist if on a post */

        $out = '';
        $uri = false;
        $pretty = false;

        $range = 2;
        $showitems = ($range * 2) + 1;

        $paged = $this->page;
        if ($paged == 0) { $paged = 1; }
        
        if (!isset($this->p->review_status)) { $this->p->review_status = 0; }

        $pages = ceil($total_results / $reviews_per_page);

        if ($pages > 1) {
            if (is_admin()) {
                $url = '?page=wpcr_view_reviews&amp;review_status=' . $this->p->review_status . '&amp;';
            } else {
                $uri = trailingslashit(get_permalink($post->ID));
                if (strpos($uri, '?') === false) {
                    $url = $uri . '?';
                    $pretty = true;
                } /* page is using pretty permalinks */ else {
                    $url = $uri . '&amp;';
                    $pretty = false;
                } /* page is using get variables for pageid */
            }

            $out .= '<div id="wpcr_pagination"><div id="wpcr_pagination_page">Page: </div>';

            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                if ($uri && $pretty) {
                    $url2 = $uri;
                } /* not in admin AND using pretty permalinks */ else {
                    $url2 = $url;
                }
                $out .= '<a href="' . $url2 . '">&laquo;</a>';
            }

            if ($paged > 1 && $showitems < $pages) {
                $out .= '<a href="' . $url . 'wpcrp=' . ($paged - 1) . '">&lsaquo;</a>';
            }

            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $paged) {
                    $out .= '<span class="wpcr_current">' . $paged . '</span>';
                } else if (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems) {
                    if ($i == 1) {
                        if ($uri && $pretty) {
                            $url2 = $uri;
                        } /* not in admin AND using pretty permalinks */ else {
                            $url2 = $url;
                        }
                        $out .= '<a href="' . $url2 . '" class="wpcr_inactive">' . $i . '</a>';
                    } else {
                        $out .= '<a href="' . $url . 'wpcrp=' . $i . '" class="wpcr_inactive">' . $i . '</a>';
                    }
                }
            }

            if ($paged < $pages && $showitems < $pages) {
                $out .= '<a href="' . $url . 'wpcrp=' . ($paged + 1) . '">&rsaquo;</a>';
            }
            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                $out .= '<a href="' . $url . 'wpcrp=' . $pages . '">&raquo;</a>';
            }
            $out .= '</div>';
            $out .= '<div class="wpcr_clear wpcr_pb5"></div>';

            return $out;
        }
    }
        
    function output_reviews_show($inside_div, $postid, $perpage, $max, $hide_custom = 0, $hide_response = 0, $snippet_length = 0, $show_morelink = '') {
        
        if ($max != -1) {
            $thispage = 1;
        } else {
            $thispage = $this->page;
        }
                
        $arr_Reviews = $this->get_reviews($postid, $thispage, $perpage, 1);
        
        $reviews = $arr_Reviews[0];
        $total_reviews = intval($arr_Reviews[1]);

        $reviews_content = '';
        $hidesummary = '';
        $title_tag = $this->options['title_tag'];

        /* trying to access a page that does not exists -- send to main page */
        if ( isset($this->p->wpcrp) && $this->p->wpcrp != 1 && count($reviews) == 0 ) {
            $url = get_permalink($postid);
            $this->wpcr_redirect($url);
        }
        
        if ($postid == 0) {
            /* NOTE: if using shortcode to show reviews for all pages, could do weird things when using product type */
            $postid = $reviews[0]->page_id;
        }

        $meta_product_name = get_post_meta($postid, 'wpcr_product_name', true);
        if (!$meta_product_name) {
            $meta_product_name = get_the_title($postid);
        }

        $meta_product_desc = get_post_meta($postid, 'wpcr_product_desc', true);
        $meta_product_brand = get_post_meta($postid, 'wpcr_product_brand', true);
        $meta_product_upc = get_post_meta($postid, 'wpcr_product_upc', true);
        $meta_product_sku = get_post_meta($postid, 'wpcr_product_sku', true);
        $meta_product_model = get_post_meta($postid, 'wpcr_product_model', true);

        if (!$inside_div) {
            $reviews_content .= '<div id="wpcr_respond_1">';
        }
        
        if (count($reviews) == 0) {
            /* $reviews_content .= '<p>There are no reviews yet. Be the first to leave yours!</p>'; */
        } else {

            $this->get_aggregate_reviews($postid);

            $summary = $this->got_aggregate["text"];
            $best_score = number_format($this->got_aggregate["max"], 1);
            $average_score = number_format($this->got_aggregate["aggregate"], 1);

            if ($this->options['hreview_type'] == 'product') {
                $reviews_content .= '
                    <span class="item hproduct" id="hproduct-' . $postid . '">
                        <span class="wpcr_hide">
                            <span class="brand">' . $meta_product_brand . '</span>
                            <span class="fn">' . $meta_product_name . '</span>
                            <span class="description">' . $meta_product_desc . '</span>
                            <span class="identifier">
                                <span class="type">SKU</span>
                                <span class="value">' . $meta_product_sku . '</span>
                            </span>
                            <span class="identifier">
                                <span class="type">UPC</span>
                                <span class="value">' . $meta_product_upc . '</span>
                            </span>
                            <span class="identifier">
                                <span class="type">Model</span>
                                <span class="value">' . $meta_product_model . '</span>
                            </span>
                        </span>
                    ';
            }

            foreach ($reviews as $review) {
                
                if ($snippet_length > 0)
                {
                    $review->review_text = $this->trim_text_to_word($review->review_text,$snippet_length);
                }
                
                $review->review_text .= '<br />';

                $hide_name = '';
                if ($this->options['show_fields']['fname'] == 0) {
                    $review->reviewer_name = 'Anonymous';
                    $hide_name = 'wpcr_hide';
                }
                if ($review->reviewer_name == '') {
                    $review->reviewer_name = 'Anonymous';
                }

                if ($this->options['show_fields']['fwebsite'] == 1 && $review->reviewer_url != '') {
                    $review->review_text .= '<br /><small><a href="' . $review->reviewer_url . '">' . $review->reviewer_url . '</a></small>';
                }
                if ($this->options['show_fields']['femail'] == 1 && $review->reviewer_email != '') {
                    $review->review_text .= '<br /><small>' . $review->reviewer_email . '</small>';
                }
                if ($this->options['show_fields']['ftitle'] == 1) {
                    /* do nothing */
                } else {
                    $review->review_title = substr($review->review_text, 0, 150);
                    $hidesummary = 'wpcr_hide';
                }
                
                if ($show_morelink != '') {
                    $review->review_text .= " <a href='".$this->get_jumplink_for_review($review,1)."'>$show_morelink</a>";
                }
                
                $review->review_text = nl2br($review->review_text);
                $review_response = '';
                
                if ($hide_response == 0)
                {
                    if (strlen($review->review_response) > 0) {
                        $review_response = '<p class="response"><strong>Response:</strong> ' . nl2br($review->review_response) . '</p>';
                    }
                }

                $custom_shown = '';
                if ($hide_custom == 0)
                {
                    $custom_fields_unserialized = @unserialize($review->custom_fields);
                    if (!is_array($custom_fields_unserialized)) {
                        $custom_fields_unserialized = array();
                    }
					
                    foreach ($this->options['field_custom'] as $i => $val) {  
                        if ( isset($custom_fields_unserialized[$val]) ) {
                            $show = $this->options['show_custom'][$i];							
                            if ($show == 1 && $custom_fields_unserialized[$val] != '') {
                                $custom_shown .= "<div class='wpcr_fl'>" . $val . ': ' . $custom_fields_unserialized[$val] . '&nbsp;&bull;&nbsp;</div>';
                            }
                        }
                    }

                    $custom_shown = preg_replace("%&bull;&nbsp;</div>$%si","</div><div class='wpcr_clear'></div>",$custom_shown);
                }

                $name_block = '' .
                    '<div class="wpcr_fl wpcr_rname">' .
                    '<abbr title="' . $this->iso8601(strtotime($review->date_time)) . '" class="dtreviewed">' . date("M d, Y", strtotime($review->date_time)) . '</abbr>&nbsp;' .
                    '<span class="' . $hide_name . '">by</span>&nbsp;' .
                    '<span class="reviewer vcard" id="hreview-wpcr-reviewer-' . $review->id . '">' .
                    '<span class="fn ' . $hide_name . '">' . $review->reviewer_name . '</span>' .
                    '</span>' .
                    '<div class="wpcr_clear"></div>' .
                    $custom_shown .
                    '</div>';

                if ($this->options['hreview_type'] == 'product') {
                    $reviews_content .= '
                        <div class="hreview" id="hreview-' . $review->id . '">
                            <' . $title_tag . ' class="summary ' . $hidesummary . '">' . $review->review_title . '</' . $title_tag . '>
                            <span class="item" id="hreview-wpcr-hproduct-for-' . $review->id . '" style="display:none;">
                                <span class="fn">' . $meta_product_name . '</span>
                            </span>
                            <div class="wpcr_fl wpcr_sc">
                                <abbr class="rating" title="' . $review->review_rating . '"></abbr>
                                <div class="wpcr_rating">
                                    ' . $this->output_rating($review->review_rating, false) . '
                                </div>					
                            </div>
                            ' . $name_block . '
                            <div class="wpcr_clear wpcr_spacing1"></div>
                            <blockquote class="description"><p>' . $review->review_text . '</p></blockquote>
                            ' . $review_response . '
                            <span style="display:none;" class="type">product</span>
                            <span style="display:none;" class="version">0.3</span>
                        </div>
                        <hr />';
                } else if ($this->options['hreview_type'] == 'business') {
                    $reviews_content .= '
                        <div class="hreview" id="hreview-' . $review->id . '">
                            <' . $title_tag . ' class="summary ' . $hidesummary . '">' . $review->review_title . '</' . $title_tag . '>
                            <div class="wpcr_fl wpcr_sc">
                                <abbr class="rating" title="' . $review->review_rating . '"></abbr>
                                <div class="wpcr_rating">
                                    ' . $this->output_rating($review->review_rating, false) . '
                                </div>					
                            </div>
                            ' . $name_block . '
                            <div class="wpcr_clear wpcr_spacing1"></div>
                            <span class="item vcard" id="hreview-wpcr-hcard-for-' . $review->id . '" style="display:none;">
                                <a class="url fn org" href="' . $this->options['business_url'] . '">' . $this->options['business_name'] . '</a>
                                <span class="tel">' . $this->options['business_phone'] . '</span>
                                <span class="adr">
                                    <span class="street-address">' . $this->options['business_street'] . '</span>
                                    <span class="locality">' . $this->options['business_city'] . '</span>
                                    <span class="region">' . $this->options['business_state'] . '</span>, <span class="postal-code">' . $this->options['business_zip'] . '</span>
                                    <span class="country-name">' . $this->options['business_country'] . '</span>
                                </span>
                            </span>
                            <blockquote class="description"><p>' . $review->review_text . '</p></blockquote>
                            ' . $review_response . '
                            <span style="display:none;" class="type">business</span>
                            <span style="display:none;" class="version">0.3</span>
                       </div>
                       <hr />';
                }
            }

            if ($this->options['hreview_type'] == 'product') {
                $reviews_content .= '
                    <span class="hreview-aggregate haggregatereview" id="hreview-wpcr-aggregate">
                       <span style="display:none;">
                           <span class="rating">
                             <span class="average">' . $average_score . '</span>
                             <span class="best">' . $best_score . '</span>
                           </span>  
                           <span class="votes">' . $this->got_aggregate["total"] . '</span>
                           <span class="count">' . $this->got_aggregate["total"] . '</span>
                           <span class="summary">' . $summary . '</span>
                           <span class="item" id="hreview-wpcr-vcard">
                            <span class="fn">' . $meta_product_name . '</span>
                           </span>
                       </span>
                    </span>';
                $reviews_content .= '</span>'; /* end hProduct */
            } else if ($this->options['hreview_type'] == 'business') {
                $reviews_content .= '
                    <span class="hreview-aggregate" id="hreview-wpcr-aggregate">
                       <span style="display:none;">
                            <span class="item vcard" id="hreview-wpcr-vcard">
                                <a class="url fn org" href="' . $this->options['business_url'] . '">' . $this->options['business_name'] . '</a>
                                <span class="tel">' . $this->options['business_phone'] . '</span>
                                <span class="adr">
                                    <span class="street-address">' . $this->options['business_street'] . '</span>
                                    <span class="locality">' . $this->options['business_city'] . '</span>
                                    <span class="region">' . $this->options['business_state'] . '</span>, <span class="postal-code">' . $this->options['business_zip'] . '</span>
                                    <span class="country-name">' . $this->options['business_country'] . '</span>
                                </span>
                            </span>
                           <span class="rating">
                                 <span class="average">' . $average_score . '</span>
                                 <span class="best">' . $best_score . '</span>
                           </span>  
                           <span class="votes">' . $this->got_aggregate["total"] . '</span>
                           <span class="count">' . $this->got_aggregate["total"] . '</span>
                           <span class="summary">' . $summary . '</span>
                       </span>
                    </span>
                    ';
            }
        }
        
        if (!$inside_div) {
            $reviews_content .= '</div>'; /* wpcr_respond_1 */
        }
        
        return array($reviews_content, $total_reviews);
    }
    
    /* trims text, but does not break up a word */
    function trim_text_to_word($text,$len) {
        if(strlen($text) > $len) {
          $matches = array();
          preg_match("/^(.{1,$len})[\s]/i", $text, $matches);
          $text = $matches[0];
        }
        return $text.'... ';
    }

    function do_the_content($original_content) {
        global $post;
        
        $using_shortcode_insert = false;
        if ($original_content == 'shortcode_insert') {
            $original_content = '';
            $using_shortcode_insert = true;
        }
        
        $the_content = '';
        $is_active_page = $this->is_active_page();
        
        /* return normal content if this is not an enabled page, or if this is a post not on single post view */
        if (!$is_active_page) {
            $the_content .= '<div id="wpcr_respond_1">';
            $the_content .= $this->aggregate_footer(); /* check if we need to show something in the footer then */
            $the_content .= '</div>';
            return $original_content . $the_content;
        }
        
        $the_content .= '<div id="wpcr_respond_1">'; /* start the div */
        $inside_div = true;
        
        if ($this->options['form_location'] == 0) {
            $the_content .= $this->show_reviews_form();
        }

        $ret_Arr = $this->output_reviews_show( $inside_div, $post->ID, $this->options['reviews_per_page'], -1 );
        $the_content .= $ret_Arr[0];
        $total_reviews = $ret_Arr[1];
        
        $the_content .= $this->pagination($total_reviews, $this->options['reviews_per_page']);

        if ($this->options['form_location'] == 1) {
            $the_content .= $this->show_reviews_form();
        }

        if ($this->options['support_us'] == 1) {
            $the_content .= '<div class="wpcr_clear wpcr_power">Powered by <strong><a href="http://www.gowebsolutions.com/wp-customer-reviews/">WP Customer Reviews</a></strong></div>';
        }
        
        $the_content .= $this->aggregate_footer(); /* check if we need to show something in the footer also */
        
        $the_content .= '</div>'; /* wpcr_respond_1 */

        //$the_content = preg_replace('/\n\r|\r\n|\n|\r|\t|\s{2}/', '', $the_content); /* minify to prevent automatic line breaks */
        $the_content = preg_replace('/\n\r|\r\n|\n|\r|\t/', '', $the_content); /* minify to prevent automatic line breaks, not removing double spaces */

        return $original_content . $the_content;
    }

    function output_rating($rating, $enable_hover) {
        $out = '';

        $rating_width = 20 * $rating; /* 20% for each star if having 5 stars */

        $out .= '<div class="sp_rating">';

        if ($enable_hover) {
            $out .= '<div class="status"><div class="score"><a class="score1">1</a><a class="score2">2</a><a class="score3">3</a><a class="score4">4</a><a class="score5">5</a></div></div>';
        }

        $out .= '<div class="base"><div class="average" style="width:' . $rating_width . '%"></div></div>';
        $out .= '</div>';

        return $out;
    }

    function show_reviews_form() {
        global $post, $current_user;

        $fields = '';
        $out = '';
        $req_js = "<script type='text/javascript'>";

        if ( isset($_COOKIE['wpcr_status_msg']) ) {
            $this->status_msg = $_COOKIE['wpcr_status_msg'];
        }
        
        if ($this->status_msg != '') {
            $req_js .= "wpcr_del_cookie('wpcr_status_msg');";
        }

        /* a silly and crazy but effective antispam measure.. bots wont have a clue */
        $rand_prefixes = array();
        for ($i = 0; $i < 15; $i++) {
            $rand_prefixes[] = $this->rand_string(mt_rand(1, 8));
        }
        
        if (!isset($this->p->fname)) { $this->p->fname = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->ftitle)) { $this->p->ftitle = ''; }
        if (!isset($this->p->ftext)) { $this->p->ftext = ''; }

        if ($this->options['ask_fields']['fname'] == 1) {
            if ($this->options['require_fields']['fname'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[0] . '-fname" class="comment-field">Name: ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[0] . '-fname" name="' . $rand_prefixes[0] . '-fname" value="' . $this->p->fname . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['femail'] == 1) {
            if ($this->options['require_fields']['femail'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[1] . '-femail" class="comment-field">Email: ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[1] . '-femail" name="' . $rand_prefixes[1] . '-femail" value="' . $this->p->femail . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['fwebsite'] == 1) {
            if ($this->options['require_fields']['fwebsite'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[2] . '-fwebsite" class="comment-field">Website: ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[2] . '-fwebsite" name="' . $rand_prefixes[2] . '-fwebsite" value="' . $this->p->fwebsite . '" /></td></tr>';
        }
        if ($this->options['ask_fields']['ftitle'] == 1) {
            if ($this->options['require_fields']['ftitle'] == 1) {
                $req = '*';
            } else {
                $req = '';
            }
            $fields .= '<tr><td><label for="' . $rand_prefixes[3] . '-ftitle" class="comment-field">Review Title: ' . $req . '</label></td><td><input class="text-input" type="text" id="' . $rand_prefixes[3] . '-ftitle" name="' . $rand_prefixes[3] . '-ftitle" maxlength="150" value="' . $this->p->ftitle . '" /></td></tr>';
        }

        $custom_fields = array(); /* used for insert as well */
        $custom_count = count($this->options['field_custom']); /* used for insert as well */
        for ($i = 0; $i < $custom_count; $i++) {
            $custom_fields[$i] = $this->options['field_custom'][$i];
        }

        foreach ($this->options['ask_custom'] as $i => $val) {
            if ( isset($this->options['ask_custom'][$i]) ) {
                if ($val == 1) {
                    if ($this->options['require_custom'][$i] == 1) {
                        $req = '*';
                    } else {
                        $req = '';
                    }

                    $custom_i = "custom_$i";
                    if (!isset($this->p->$custom_i)) { $this->p->$custom_i = ''; }
                    $fields .= '<tr><td><label for="custom_' . $i . '" class="comment-field">' . $custom_fields[$i] . ': ' . $req . '</label></td><td><input class="text-input" type="text" id="custom_' . $i . '" name="custom_' . $i . '" maxlength="150" value="' . $this->p->$custom_i . '" /></td></tr>';
                }
            } 
        }

        $some_required = '';
        
        foreach ($this->options['require_fields'] as $col => $val) {
            if ($val == 1) {
                $col = str_replace("'","\'",$col);
                $req_js .= "wpcr_req.push('$col');";
                $some_required = '<small>* Required Field</small>';
            }
        }

        foreach ($this->options['require_custom'] as $i => $val) {
            if ($val == 1) {
                $req_js .= "wpcr_req.push('custom_$i');";
                $some_required = '<small>* Required Field</small>';
            }
        }
        
        $req_js .= "</script>\n";
        
        if ($this->options['goto_show_button'] == 1) {
            $button_html = '<div class="wpcr_status_msg">' . $this->status_msg . '</div>'; /* show errors or thank you message here */
            $button_html .= '<p><a id="wpcr_button_1" href="javascript:void(0);">' . $this->options['goto_leave_text'] . '</a></p><hr />';
            $out .= $button_html;
        }

        /* different output variables make it easier to debug this section */
        $out .= '<div id="wpcr_respond_2">' . $req_js . '
                    <form class="wpcrcform" id="wpcr_commentform" method="post" action="javascript:void(0);">
                        <div id="wpcr_div_2">
                            <input type="hidden" id="frating" name="frating" />
                            <table id="wpcr_table_2">
                                <tbody>
                                    <tr><td colspan="2"><div id="wpcr_postcomment">' . $this->options["leave_text"] . '</div></td></tr>
                                    ' . $fields;

        $out2 = '   
            <tr>
                <td><label class="comment-field">Rating:</label></td>
                <td><div class="wpcr_rating">' . $this->output_rating(0, true) . '</div></td>
            </tr>';

        $out3 = '
                            <tr><td colspan="2"><label for="' . $rand_prefixes[5] . '-ftext" class="comment-field">Review:</label></td></tr>
                            <tr><td colspan="2"><textarea id="' . $rand_prefixes[5] . '-ftext" name="' . $rand_prefixes[5] . '-ftext" rows="8" cols="50">' . $this->p->ftext . '</textarea></td></tr>
                            <tr>
                                <td colspan="2" id="wpcr_check_confirm">
                                    ' . $some_required . '
                                    <div class="wpcr_clear"></div>    
                                    <input type="checkbox" name="' . $rand_prefixes[6] . '-fconfirm1" id="fconfirm1" value="1" />
                                    <div class="wpcr_fl"><input type="checkbox" name="' . $rand_prefixes[7] . '-fconfirm2" id="fconfirm2" value="1" /></div><div class="wpcr_fl" style="margin:-2px 0px 0px 5px"><label for="fconfirm2">Check this box to confirm you are human.</label></div>
                                    <div class="wpcr_clear"></div>
                                    <input type="checkbox" name="' . $rand_prefixes[8] . '-fconfirm3" id="fconfirm3" value="1" />
                                </td>
                            </tr>
                            <tr><td colspan="2"><input id="wpcr_submit_btn" name="submitwpcr_' . $post->ID . '" type="submit" value="' . $this->options['submit_button_text'] . '" /></td></tr>
                        </tbody>
                    </table>
                </div>
            </form>';

        $out4 = '<hr /></div>';
        $out4 .= '<div class="wpcr_clear wpcr_pb5"></div>';

        return $out . $out2 . $out3 . $out4;
    }

    function add_review($pageID) {
        global $wpdb;

        /* begin - some antispam magic */
        $this->newp = new stdClass();

        foreach ($this->p as $col => $val) {
            $pos = strpos($col, '-');
            if ($pos !== false) {
                $col = substr($col, $pos + 1); /* off by one */
            }
            $this->newp->$col = $val;
        }

        $this->p = $this->newp;
        unset($this->newp);
        /* end - some antispam magic */

        /* some sanitation */
        $date_time = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if (!isset($this->p->fname)) { $this->p->fname = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->ftitle)) { $this->p->ftitle = ''; }
        if (!isset($this->p->ftext)) { $this->p->ftext = ''; }
        if (!isset($this->p->femail)) { $this->p->femail = ''; }
        if (!isset($this->p->fwebsite)) { $this->p->fwebsite = ''; }
        if (!isset($this->p->frating)) { $this->p->frating = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm1)) { $this->p->fconfirm1 = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm2)) { $this->p->fconfirm2 = 0; } /* default to 0 */
        if (!isset($this->p->fconfirm3)) { $this->p->fconfirm3 = 0; } /* default to 0 */
        
        $this->p->fname = trim(strip_tags($this->p->fname));
        $this->p->femail = trim(strip_tags($this->p->femail));
        $this->p->ftitle = trim(strip_tags($this->p->ftitle));
        $this->p->ftext = trim(strip_tags($this->p->ftext));
        $this->p->frating = intval($this->p->frating);

        /* begin - server-side validation */
        $errors = '';

        foreach ($this->options['require_fields'] as $col => $val) {
            if ($val == 1) {
                if (!isset($this->p->$col) || $this->p->$col == '') {
                    $nice_name = ucfirst(substr($col, 1));
                    $errors .= 'You must include your ' . $nice_name . '.<br />';
                }
            }
        }

        $custom_fields = array(); /* used for insert as well */
        $custom_count = count($this->options['field_custom']); /* used for insert as well */
        for ($i = 0; $i < $custom_count; $i++) {
            $custom_fields[$i] = $this->options['field_custom'][$i];
        }

        foreach ($this->options['require_custom'] as $i => $val) {
            if ($val == 1) {
                $custom_i = "custom_$i";
                if (!isset($this->p->$custom_i) || $this->p->$custom_i == '') {
                    $nice_name = $custom_fields[$i];
                    $errors .= 'You must include your ' . $nice_name . '.<br />';
                }
            }
        }
        
        /* only do regex matching if not blank */
        if ($this->p->femail != '' && $this->options['ask_fields']['femail'] == 1) {
            if (!preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/', $this->p->femail)) {
                $errors .= 'The email address provided is not valid.<br />';
            }
        }

        /* only do regex matching if not blank */
        if ($this->p->fwebsite != '' && $this->options['ask_fields']['fwebsite'] == 1) {
            if (!preg_match('/^\S+:\/\/\S+\.\S+.+$/', $this->p->fwebsite)) {
                $errors .= 'The website provided is not valid. Be sure to include http://<br />';
            }
        }

        if (intval($this->p->fconfirm1) == 1 || intval($this->p->fconfirm3) == 1) {
            $errors .= 'You have triggered our anti-spam system. Please try again. Code 001.<br />';
        }

        if (intval($this->p->fconfirm2) != 1) {
            $errors .= 'You have triggered our anti-spam system. Please try again. Code 002<br />';
        }

        if ($this->p->frating < 1 || $this->p->frating > 5) {
            $errors .= 'You have triggered our anti-spam system. Please try again. Code 003<br />';
        }

        if (strlen(trim($this->p->ftext)) < 30) {
            $errors .= 'You must include a review. Please make reviews at least a couple of sentences.<br />';
        }

        /* returns true for errors */
        if ($errors) {
            return array(true, "<div>$errors</div>");
        }
        /* end - server-side validation */

        $custom_insert = array();		
        for ($i = 0; $i < $custom_count; $i++) {		
            if ($this->options['ask_custom'][$i] == 1) {
                $name = $custom_fields[$i];
                $custom_i = "custom_$i";				
                if ( isset($this->p->$custom_i) ) {
                    $custom_insert[$name] = ucfirst($this->p->$custom_i);
                }
            }
        }
        $custom_insert = serialize($custom_insert);

        $query = $wpdb->prepare("INSERT INTO `$this->dbtable` 
                (`date_time`, `reviewer_name`, `reviewer_email`, `reviewer_ip`, `review_title`, `review_text`, `status`, `review_rating`, `reviewer_url`, `custom_fields`, `page_id`) 
                VALUES (%s, %s, %s, %s, %s, %s, %d, %d, %s, %s, %d)", $date_time, $this->p->fname, $this->p->femail, $ip, $this->p->ftitle, $this->p->ftext, 0, $this->p->frating, $this->p->fwebsite, $custom_insert, $pageID);

        $wpdb->query($query);

        $admin_link = get_admin_url().'admin.php?page=wpcr_view_reviews';
        $admin_link = "Link to admin approval page: $admin_link";

        @wp_mail(get_bloginfo('admin_email'), "WP Customer Reviews: New Review Posted on " . date('m/d/Y h:i'), "A new review has been posted for " . $this->options['business_name'] . " via WP Customer Reviews. \n\nYou will need to login to the admin area and approve this review before it will appear on your site.\n\n{$admin_link}");

        /* returns false for no error */
        return array(false, '<div>Thank you for your comments. All submissions are moderated and if approved, yours will appear soon.</div>');
    }

    function deactivate() {
        /* do not fire on upgrading plugin or upgrading WP - only on true manual deactivation */
        if (isset($this->p->action) && $this->p->action == 'deactivate') {
            $this->options['activate'] = 0;
            update_option('wpcr_options', $this->options);
            global $WPCustomerReviewsAdmin;
            $this->include_admin(); /* include admin functions */
            $WPCustomerReviewsAdmin->notify_activate(2);
        }
    }

    function wpcr_redirect($url, $cookie = array()) {
        
        $headers_sent = headers_sent();
        
        if ($headers_sent == true) {
            /* use JS redirect and add cookie before redirect */
            /* we do not html comment script blocks here - to prevent any issues with other plugins adding content to newlines, etc */
            $out = "<html><head><title>Redirecting...</title></head><body><div style='clear:both;text-align:center;padding:10px;'>" .
                    "Processing... Please wait..." .
                    "<script type='text/javascript'>";
            foreach ($cookie as $col => $val) {
                $val = preg_replace("/\r?\n/", "\\n", addslashes($val));
                $out .= "document.cookie=\"$col=$val\";";
            }
            $out .= "window.location='$url';";
            $out .= "</script>";
            $out .= "</div></body></html>";
            echo $out;
        } else {
            foreach ($cookie as $col => $val) {
                setcookie($col, $val); /* add cookie via headers */
            }
            ob_end_clean();
            wp_redirect($url); /* nice redirect */
        }
        
        exit();
    }

    function init() { /* used for admin_init also */
        $this->make_p_obj(); /* make P variables object */
        $this->get_options(); /* populate the options array */
        $this->check_migrate(); /* call on every instance to see if we have upgraded in any way */

        if ( !isset($this->p->wpcrp) ) { $this->p->wpcrp = 1; }
        
        $this->page = intval($this->p->wpcrp);
        if ($this->page < 1) { $this->page = 1; }
        
        add_shortcode( 'WPCR_INSERT', array(&$this, 'shortcode_wpcr_insert') );
        add_shortcode( 'WPCR_SHOW', array(&$this, 'shortcode_wpcr_show') );
        
        wp_register_style('wp-customer-reviews', $this->getpluginurl() . 'wp-customer-reviews.css', array(), $this->plugin_version);
        wp_register_script('wp-customer-reviews', $this->getpluginurl() . 'wp-customer-reviews.js', array('jquery'), $this->plugin_version);
		/* add style and script here if needed for some theme compatibility */
		$this->add_style_script();
    }
    
    function shortcode_wpcr_insert() {
        $this->force_active_page = 1;
        return $this->do_the_content('shortcode_insert');        
    }
    
    function shortcode_wpcr_show($atts) {
        $this->force_active_page = 1;
        
        extract( shortcode_atts( array('postid' => 'all','num' => '3','hidecustom' => '0','hideresponse' => '0', 'snippet' => '0','more' => ''), $atts ) );
        
        if (strtolower($postid) == 'all') { $postid = -1; /* -1 queries all reviews */ }
        $postid = intval($postid);
        $num = intval($num);
        $hidecustom = intval($hidecustom);
        $hideresponse = intval($hideresponse);
        $snippet = intval($snippet);
        $more = $more;
        
        if ($postid < -1) { $postid = -1; }
        if ($num < 1) { $num = 3; }
        if ($hidecustom < 0 || $hidecustom > 1) { $hidecustom = 0; }
        if ($hideresponse < 0 || $hideresponse > 1) { $hideresponse = 0; } 
        if ($snippet < 0) { $snippet = 0; }
        
        $inside_div = false;
        
        $ret_Arr = $this->output_reviews_show( $inside_div, $postid, $num, $num, $hidecustom, $hideresponse, $snippet, $more );
        return $ret_Arr[0];
    }

    function activate() {
        register_setting('wpcr_gotosettings', 'wpcr_gotosettings');
        add_option('wpcr_gotosettings', true); /* used for redirecting to settings page upon initial activation */
    }

    function include_admin() {
        global $WPCustomerReviewsAdmin;
        require_once($this->getplugindir() . 'wp-customer-reviews-admin.php'); /* include admin functions */
    }

    function admin_init() {
        global $WPCustomerReviewsAdmin;
        $this->include_admin(); /* include admin functions */
        $WPCustomerReviewsAdmin->real_admin_init();
    }

    function getpluginurl() {
        return trailingslashit(plugins_url(basename(dirname(__FILE__))));
    }

    function getplugindir() {
        return trailingslashit(WP_PLUGIN_DIR . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)));
    }

}

if (!defined('IN_WPCR')) {
    global $WPCustomerReviews;
    $WPCustomerReviews = new WPCustomerReviews();
    register_activation_hook(__FILE__, array(&$WPCustomerReviews, 'activate'));
    register_deactivation_hook(__FILE__, array(&$WPCustomerReviews, 'deactivate'));
}
?>