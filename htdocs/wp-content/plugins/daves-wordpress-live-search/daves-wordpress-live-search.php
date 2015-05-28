<?php

/*
Plugin Name: Dave's WordPress Live Search
Description: Adds "live search" functionality to your WordPress site. Uses the built-in search and jQuery.
Version: 4.3
Author: Dave Ross
Author URI: http://davidmichaelross.com/
Plugin URI: http://wordpress.org/extend/plugins/daves-wordpress-live-search/
*/

/**
 * Copyright (c) 2014 Dave Ross <dave@csixty4.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *   The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * */

add_action( 'init', 'daves_wp_live_search_init' );

function daves_wp_live_search_init() {
	if ( defined( 'DOING_AJAX' ) ) {
		include_once "class-daves-wordpress-live-search-results.php";
	}
	else {
		include_once "class-daves-wordpress-live-search.php";
		add_action( 'admin_notices', array( 'DavesWordPressLiveSearch', 'admin_notices' ) );

		// Register hooks
		add_action( 'admin_menu', array( 'DavesWordPressLiveSearch', 'admin_menu' ) );
		add_action( 'wp_head', array( 'DavesWordPressLiveSearch', 'head' ) );
		add_action( 'admin_head', array( 'DavesWordPressLiveSearch', 'head' ) );
		register_activation_hook( __FILE__, array( 'DavesWordPressLiveSearch', 'activate' ) );
		add_action( 'admin_enqueue_scripts', array( 'DavesWordPressLiveSearch', 'admin_enqueue_scripts' ) );
		DavesWordPressLiveSearch::advanced_search_init();
	}
}

// Provide a json_encode implementation if none exists (PHP < 5.2.0)
if ( !function_exists( 'json_encode' ) ) {
	require ABSPATH . "/wp-includes/compat.php" ;
}

// Relevanssi "bridge" plugin
class DWLS_Relevanssi_Bridge {
	static function init() {
		if ( function_exists( 'relevanssi_do_query' ) ) {
			add_filter( 'dwls_alter_results', array( 'DWLS_Relevanssi_Bridge', 'dwls_alter_results' ), 10, 3 );
		}
	}

	static function dwls_alter_results( $wpQueryResults, $maxResults, $results ) {
		global $wp_query;

		// WP_Query::query() set everything up
		// Now have Relevanssi do the query over again
		// but do it in its own way
		// Thanks Mikko!
		relevanssi_do_query( $wp_query );
		$results->relevanssi = true;

		// Mikko says Relevanssi 2.5 doesn't handle limits
		// when it queries, so I need to handle them on my own.
		$wpQueryResults = array_slice( $wp_query->posts, 0, $maxResults );

		return $wpQueryResults;
	}
}
add_action( 'init', array( 'DWLS_Relevanssi_Bridge', 'init' ) );

class DWLS_Util {

	public static function updateFirstImagePostmeta( $post_id, $post ) {

		$parent_post = wp_is_post_revision( $post_id );
		if ( false !== $parent_post ) {
			$post_id = $parent_post;
			$post = get_post( $parent_post, OBJECT );
		}

		$applyContentFilter = get_option( 'daves-wordpress-live-search_apply_content_filter', false );
		$content = $post->post_content;
		if ( $applyContentFilter ) {
			$content = apply_filters( 'the_content', $content );
		}
		$content = str_replace( ']]>', ']]&gt;', $content );
		$attachment_thumbnail = self::firstImg( $content );
		update_post_meta( $post_id, '_dwls_first_image', $attachment_thumbnail );
		return $attachment_thumbnail;

	}

	public static function firstImg( $post_content ) {
		$matches = array();
		$output  = preg_match_all( '/<img [^>]*src=["|\']([^"|\']+)/i', $post_content, $matches );

		if ( isset( $matches[1][0] ) ) {
			$first_img = $matches[1][0];
		}

		if ( empty( $first_img ) ) {
			return '';
		}

		return $first_img;
	}

}
add_action( "save_post", array( "DWLS_Util", "updateFirstImagePostmeta" ), 10, 2 );
