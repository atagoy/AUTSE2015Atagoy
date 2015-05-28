<?php

if ( !defined( 'ABSPATH' ) ) die( "Cannot access files directly." );

class DavesWordPressLiveSearchResults {

	// Search sources
	const SEARCH_CONTENT = 0;
	const SEARCH_WPCOMMERCE = 1;

	public $searchTerms;
	public $results;
	public $displayPostMeta;

	/**
	 * Constructor
	 *
	 * @param string  $searchTerms
	 * @param boolean $displayPostMeta Show author & date for each post. Defaults to TRUE to keep original bahavior from before I added this flag
	 */
	function DavesWordPressLiveSearchResults( $searchTerms, $displayPostMeta = true ) {

		$this->results = array();
		$this->populate( $searchTerms, $displayPostMeta );
		$this->displayPostMeta = $displayPostMeta;

	}

	/**
	 * Run the query and build an array of results
	 *
	 * @global type $wp_locale
	 * @global type $wp_query
	 * @param type    $wpQueryResults
	 * @param type    $displayPostMeta
	 */
	private function populate( $wpQueryResults, $displayPostMeta ) {

		global $wp_locale;
		global $wp_query;
		global $post;

		$dateFormat = get_option( 'date_format' );

		// Get the search terms to include in the AJAX response
		$this->searchTerms = $_GET['s'];

		$wpQueryResults = $wp_query->get_posts();
		$wpQueryResults = apply_filters( 'dwls_alter_results', $wpQueryResults, -1, $this );

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			// Add author names & permalinks
			if ( $displayPostMeta ) {
				$authorName = get_the_author_meta( 'user_nicename', $post->post_author );
				$authorName = apply_filters( 'dwls_author_name', $authorName );
				$post->post_author_nicename = $authorName;
			}

			$post->permalink = get_permalink( $post->ID );

			if ( function_exists( 'get_post_thumbnail_id' ) ) {
				// Support for WP 2.9 post thumbnails
				$postImageID = get_post_thumbnail_id( $post->ID );
				$postImageData = wp_get_attachment_image_src( $postImageID, apply_filters( 'post_image_size', 'thumbnail' ) );
				$hasThumbnailSet = ( $postImageData !== false );
			}
			else {
				// No support for post thumbnails
				$hasThumbnailSet = false;
			}

			if ( $hasThumbnailSet ) {

				$post->attachment_thumbnail = $postImageData[0];

			} else {

				$firstImageMeta = get_post_meta( $post->ID, '_dwls_first_image', true );
				if ( $firstImageMeta ) {
					$post->attachment_thumbnail = $firstImageMeta;
				}
				else {
					// If no post thumbnail, grab the first image from the post_date
					$post->attachment_thumbnail = DWLS_Util::updateFirstImagePostmeta( $post->ID, $post );
				}

			}

			$post->attachment_thumbnail = apply_filters( 'dwls_attachment_thumbnail', $post->attachment_thumbnail );

			$post->post_excerpt = $this->excerpt( $post );

			$post->post_date = date_i18n( $dateFormat, strtotime( $post->post_date ) );
			$post->post_date = apply_filters( 'dwls_post_date', $post->post_date );

			// We don't want to send all this content to the browser
			unset( $post->post_content );

			// xLocalization
			$post->post_title = apply_filters( "localization", $post->post_title );

			$post->post_title = apply_filters( 'dwls_post_title', $post->post_title );

			$post->show_more = true;

			// Let plugins & themes define their own template variables
			$post = apply_filters( 'dwls_post_custom', $post );

			$this->results[] = $post;

		}
	}

	private function excerpt( $result ) {

		static $excerptLength = null;
		// Only grab this value once
		if ( null == $excerptLength ) {
			$excerptLength = intval( get_option( 'daves-wordpress-live-search_excerpt_length' ) );
		}
		// Default value
		if ( 0 == $excerptLength ) {
			$excerptLength = 100;
		}

		if ( empty( $result->post_excerpt ) ) {
			$content = apply_filters( "localization", $result->post_content );
			$excerpt = explode( " ", strrev( substr( strip_tags( $content ), 0, $excerptLength ) ), 2 );
			$excerpt = strrev( isset( $excerpt[1] ) ? $excerpt[1] : '' );
			$excerpt .= " [...]";
		} else {
			$excerpt = apply_filters( "localization", $result->post_excerpt );
		}

		$excerpt = apply_filters( 'the_excerpt', $excerpt );
		$excerpt = apply_filters( 'dwls_the_excerpt', $excerpt );

		return $excerpt;
	}

	public static function ajaxSearch() {
		global $wp_query;

		// This needs to be registered here so it's only invoked when processing a DWLS AJAX request
		add_action( 'pre_get_posts', array( "DavesWordPressLiveSearchResults", "pre_get_posts" ) );

		$displayPostMeta = (bool) get_option( 'daves-wordpress-live-search_display_post_meta' );
		$results = new DavesWordPressLiveSearchResults( $_GET['s'], $displayPostMeta );

		wp_send_json( $results );
	}

	public static function pre_get_posts( $query ) {

		// These fields don't seem to be getting set right during an AJAX call
		$query->parse_query( http_build_query( $_GET ) );

		// Force post status to 'publish' because admin-ajax.php runs in an admin context
		$query->set( 'post_status', 'publish' );

		// Ignore sticky posts
		$query->set( 'ignore_sticky_posts', true );

		if ( array_key_exists( 'search_source', $_REQUEST ) ) {
			$searchSource = $_GET['search_source'];
		} else {
			$searchSource = intval( get_option( 'daves-wordpress-live-search_source' ) );
		}

		$maxResults = intval( get_option( 'daves-wordpress-live-search_max_results' ) );
		if ( $maxResults === 0 ) {
			$maxResults = -1;
		}

		if ( function_exists( 'relevanssi_do_query' ) ) {
			// Relevanssi isn't treating 0 as "unlimited" results
			// like WordPress's native search does. So we'll replace
			// $maxResults with a really big number, the biggest one
			// PHP knows how to represent, if $maxResults == -1
			// (unlimited)
			if ( -1 == $maxResults ) {
				$maxResults = PHP_INT_MAX;
			}
		}

		$query->set( 'posts_per_page', $maxResults );

		// Override post_type if none provided
		if ( !isset( $_GET['post_type'] ) ) {
			if ( self::SEARCH_WPCOMMERCE === $searchSource ) {
				$query->set( 'post_type', 'wpsc-product' );
			}
		}

		// Limit the WP_Query response to just the fields
		$query_fields = array( 'ID', 'post_author', 'post_content', 'post_excerpt', 'post_date', 'post_title' );
		$query_fields = apply_filters( 'dwls_query_fields', $query_fields );
		$query->set( 'fields', $query_fields );

		// Tell WordPress not to bother updating post caches
		$query->set( 'cache_results', false );

		// Tell WordPress not to get SQL_CALC_FOUND_ROWS
		$query->set( 'no_found_rows', true );
	}

}

// Set up the AJAX hooks
add_action( "wp_ajax_dwls_search", array( "DavesWordPressLiveSearchResults", "ajaxSearch" ) );
add_action( "wp_ajax_nopriv_dwls_search", array( "DavesWordPressLiveSearchResults", "ajaxSearch" ) );
