<?php
/*
Plugin Name: User Recent Search History
Plugin URI: http://webelusion.com/
Description: Keeps track of logged In User's search history and also anonymous user's search history.
Version: 1.1
Author: Dipali Dhole
Author URI: http://profiles.wordpress.org/dipalidhole27gmailcom/
Donate link: http://webelusion.com/

*/
add_action('init', 'ursh_init_session', 1);
function ursh_init_session()
{ 
  // if session is not started then start the session.
  if ( !is_user_logged_in() ) {
  session_start();
  }
}
if (is_admin()) {
    	register_activation_hook(__FILE__, 'ursh_init');
}

function ursh_init() {
	ursh_create_search_table();
}

function ursh_create_search_table() {
 // Create the table if not already there.
	global $wpdb;
	$table_name = $wpdb->prefix . "searchhistory";
	if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
		dbDelta("
			CREATE TABLE `{$table_name}` (
                                `sid` INT(11)  NOT NULL AUTO_INCREMENT,
                                `uid` INT(11) NOT NULL,
				`terms` VARCHAR(50) NOT NULL,
				`date` DATETIME NOT NULL,
			         PRIMARY KEY (`sid`,`terms`)
			)
			CHARACTER SET utf8 COLLATE utf8_general_ci;
			");
	}
}
add_filter('the_posts', 'ursh_save_search', 20);

function ursh_save_search($posts) {

// Check if the request is a search, and if so then save details.
// This is a filter but does not change the posts.
    global $wpdb, $wp_query;

    if (is_search()
            && !is_paged() // not the second or subsequent page of a previously-counted search
            && !is_admin() // not using the administration console
    ) {
        // Get all details of this search term

        $search_string = $wp_query->query_vars['s'];
        if (get_magic_quotes_gpc()) {
            $search_string = stripslashes($search_string);
        }
        // search terms is the words in the query
        $search_terms = $search_string;
        $search_terms = preg_replace('/[," ]+/', ' ', $search_terms);
        $search_terms = trim($search_terms);


        $search_string = $wpdb->escape($search_string); // Sanitise as necessary
        $search_terms = $wpdb->escape($search_terms);

        //get logged In user details

       

        if (is_user_logged_in()) {
            //if user is logged in the search data stored in the database.
            $current_user = wp_get_current_user();
            $results = $wpdb->get_results(
                    "SELECT `sid`
		FROM `{$wpdb->prefix}searchhistory`
                where uid= $current_user->ID and terms='$search_terms'"
            );
            if (count($results) <= 0) {
                $query = "INSERT INTO `{$wpdb->prefix}searchhistory` (`uid`,`terms`,`date`)
		VALUES ('$current_user->ID','$search_string',NOW())";
                $success = $wpdb->query($query);
            }
        } else {
             //if anonymous users search data stored in the session.
            $_SESSION['search'][] = $search_terms;
        }
    }
    return $posts;
}
// added the widget
add_action('widgets_init', 'ursh_register_widgets');

function ursh_register_widgets() {
	register_widget('User_Search_History_Widget');
}
class User_Search_History_Widget extends WP_Widget {

    function User_Search_History_Widget() {
        $widget_ops = array('classname' => 'widget_your_search', 'description' => __("A list of User Searches."));
        $this->WP_Widget('user_searches', __('User\'s recent Search History'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['your-searches-title']) ? __('Popular Searches') : $instance['your-searches-title']);
        $count = (int) (empty($instance['your-searches-number']) ? 5 : $instance['your-searches-number']);
        if (is_user_logged_in()) { // logged In user's search history display
            echo $before_widget;
            if ($title) {
                $search_no = ursh_return_search_count();
                if ($search_no > 0) {
                    echo $before_title . $title . $after_title;
                }
            }
            ursh_list_your_searches($count);
            echo $after_widget;
        } else {  // anonymous user's search history display
            if (!empty($_SESSION['search'])) {
                $searched_items = array_unique($_SESSION['search']);
            }
            if (!empty($searched_items)) {
                echo $before_widget;
                if (count($searched_items) > 0) {
                    if ($title) {
                        echo $before_title . $title . $after_title;
                    }
                }
                echo "$before\n<ul>\n";
                $home_url_slash = get_settings('home') . '/';
                $searched_items = array_reverse($searched_items);
                $searched_items = array_slice($searched_items, 0, $count, true);
                foreach ($searched_items as $val) {
                    echo '<li><a href="' . $home_url_slash . '?s=' . $val . '">' . htmlspecialchars($val) . '</a></li>' . "\n";
                }
                echo "</ul>\n$after\n";
                echo $after_widget;
            }
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['your-searches-title'] = strip_tags(stripslashes($new_instance['your-searches-title']));
        $instance['your-searches-number'] = (int) ($new_instance['your-searches-number']);
        return $instance;
    }

    function form($instance) {
        //Defaults
        $instance = wp_parse_args((array) $instance, array('your-searches-title' => 'User Searches', 'your-searches-number' => 5));

        $title = htmlspecialchars($instance['your-searches-title']);
        $count = htmlspecialchars($instance['your-searches-number']);

        # Output the options
        echo '<p><label for="' . $this->get_field_name('your-searches-title') . '">' . __('Title:') . ' <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('your-searches-title') . '" type="text" value="' . $title . '" /></label></p>';
        echo '<p><label for="' . $this->get_field_name('your-searches-number') . '">' . __('Number of searches to show:') . ' <input id="' . $this->get_field_id('your-searches-number') . '" name="' . $this->get_field_name('your-searches-number') . '" type="text" value="' . $count . '" size="3" /></label></p>';
    }

}

function ursh_list_your_searches ($count) { // display the serach terms of logged In user
    global $wpdb, $wp_rewrite;
     $current_user = wp_get_current_user();
	$count = intval($count);
      	$results = $wpdb->get_results(
		"SELECT distinct
                `terms`
		FROM `{$wpdb->prefix}searchhistory`
                where uid= $current_user->ID
		GROUP BY `terms`
		ORDER BY date DESC
		LIMIT $count");
	if (count($results)) {
		echo "$before\n<ul>\n";
		$home_url_slash = get_settings('home') . '/';
		foreach ($results as $result) {
			echo '<li><a href="'. $home_url_slash .'?s='.urlencode($result->terms).'">'. htmlspecialchars($result->terms) .'</a></li>'."\n";
		}
		echo "</ul>\n$after\n";
	}
}
// returns the count of search terms
function ursh_return_search_count() {
  global $wpdb;
  $current_user = wp_get_current_user();
  $results = $wpdb->get_results(
		"SELECT distinct
                `terms`
		FROM `{$wpdb->prefix}searchhistory`
                where uid= $current_user->ID
		GROUP BY `terms`
		ORDER BY date DESC"
		);
    return intval(count($results));
}

// deactivation hook
register_deactivation_hook( __FILE__, 'pluginUninstall' );
function pluginUninstall() {
//delete table
        global $wpdb;
        $table = $wpdb->prefix."searchhistory";
        $wpdb->query("DROP TABLE IF EXISTS $table");
}