<?php
/*
Plugin Name: Custom Fields Search by BestWebSoft
Plugin URI: http://bestwebsoft.com/products/
Description: This plugin allows you to add website search any existing custom fields.
Author: BestWebSoft
Version: 1.2.5
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  Copyright 2015  BestWebSoft  ( http://support.bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Function are using to add on admin-panel Wordpress page 'bws_plugins' and sub-page of this plugin */
if ( ! function_exists( 'cstmfldssrch_add_to_admin_menu' ) ) {
	function cstmfldssrch_add_to_admin_menu() {
		bws_add_general_menu( plugin_basename( __FILE__ ) );
		add_submenu_page( 'bws_plugins', __( 'Custom fields search settings', 'custom-fields-search' ), 'Custom fields search', 'manage_options', "custom-fields-search.php", 'cstmfldssrch_page_of_settings' );
	}
}

if ( ! function_exists( 'cstmfldssrch_init' ) ) {
	function cstmfldssrch_init() {
		global $cstmfldssrch_plugin_info;
		/* Adding translations in this plugin */
		load_plugin_textdomain( 'custom-fields-search', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_functions.php' );

		if ( empty( $cstmfldssrch_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cstmfldssrch_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version  */
		bws_wp_version_check( plugin_basename( __FILE__ ), $cstmfldssrch_plugin_info, "3.0" );
		
		/* Call register settings function */
		if ( ! is_admin() || ( isset( $_GET['page'] ) && "custom-fields-search.php" == $_GET['page'] ) )
			cstmfldssrch_register_options();
	}
}

if ( ! function_exists( 'cstmfldssrch_admin_init' ) ) {
	function cstmfldssrch_admin_init() {
		global $bws_plugin_info, $cstmfldssrch_plugin_info;

		if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '85', 'version' => $cstmfldssrch_plugin_info["Version"] );		
	}
}

/* Function create column in table wp_options for option of this plugin. If this column exists - save value in variable. */
if ( ! function_exists( 'cstmfldssrch_register_options' ) ) {
	function cstmfldssrch_register_options() {
		global $cstmfldssrch_options, $cstmfldssrch_plugin_info;

		$cstmfldssrch_options_defaults = array(
			'plugin_option_version'	=>	$cstmfldssrch_plugin_info["Version"],
			'fields' 				=> array(),
			'show_hidden_fields'	=> 0
		);

		if ( ! get_option( 'cstmfldssrch_options' ) )
			add_option( 'cstmfldssrch_options', $cstmfldssrch_options_defaults );

		$cstmfldssrch_options = get_option( 'cstmfldssrch_options' );

		/* Array merge incase this version has added new options */
		if ( ! isset( $cstmfldssrch_options['plugin_option_version'] ) || $cstmfldssrch_options['plugin_option_version'] != $cstmfldssrch_plugin_info["Version"] ) {
			if ( ! isset( $cstmfldssrch_options['fields'] ) ) {
				$cstmfldssrch_options_array = $cstmfldssrch_options;
				unset( $cstmfldssrch_options_array['plugin_option_version'] );
				$cstmfldssrch_options = array( 'fields' => $cstmfldssrch_options_array );
			}
			$cstmfldssrch_options = array_merge( $cstmfldssrch_options_defaults, $cstmfldssrch_options );
			$cstmfldssrch_options['plugin_option_version'] = $cstmfldssrch_plugin_info["Version"];
			update_option( 'cstmfldssrch_options', $cstmfldssrch_options );
		}
	}
}

/* Function are using to register script and styles for plugin settings page */
if ( ! function_exists ( 'cstmfldssrch_admin_head' ) ) {
	function cstmfldssrch_admin_head() {
		if ( isset( $_GET['page'] ) && "custom-fields-search.php" == $_GET['page'] ) {
			wp_enqueue_style( 'cstmfldssrch_style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_enqueue_script( 'cstmfldssrch_script', plugins_url( 'js/script.js' , __FILE__ ) );
		}
	}
}

/* Function exclude records that contain duplicate data in selected fields */
if ( ! function_exists( 'cstmfldssrch_distinct' ) ) {
	function cstmfldssrch_distinct( $distinct ) {
		global $wp_query, $cstmfldssrch_options;
		if ( ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmfldssrch_options['fields'] ) && is_search() ) {
			$distinct .= "DISTINCT";
		}
		return $distinct;
	}
}

/* Function join table `wp_posts` with `wp_postmeta` */
if ( ! function_exists( 'cstmfldssrch_join' ) ) {
	function cstmfldssrch_join( $join ) {
		global $wp_query, $wpdb, $cstmfldssrch_options;
		if ( ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmfldssrch_options['fields'] ) && is_search() ) {
			$join .= "JOIN " . $wpdb->postmeta . " ON " . $wpdb->posts . ".ID = " . $wpdb->postmeta . ".post_id ";
		}
		return $join;
	}
}

/* Function adds in request keyword search on custom fields, and list of meta_key, which user has selected */
if( ! function_exists( 'cstmfldssrch_request' ) ) {
	function cstmfldssrch_request( $where ) {
		global $wp_query, $wpdb, $cstmfldssrch_options;
		$pos = strrpos( $where, '%' );
		if ( false !== $pos && ! empty( $wp_query->query_vars['s'] ) && ! empty( $cstmfldssrch_options['fields'] ) && is_search() ) {
			$end_pos_where = 5 + $pos; /* find position of the end of the request with check the type and status of the post */
			$end_of_where_request = substr( $where, $end_pos_where ); /* save check the type and status of the post in variable */
			/* Exclude for gallery and gallery pro from search - dont show attachment with keywords */
			$flag_gllr_image = array();			 
			if ( in_array( 'gllr_image_text', $cstmfldssrch_options['fields'] ) || in_array( 'gllr_image_alt_tag', $cstmfldssrch_options['fields'] ) ||
				in_array( 'gllr_link_url', $cstmfldssrch_options['fields'] ) || in_array( 'gllr_image_description', $cstmfldssrch_options['fields'] ) ||
				in_array( 'gllr_lightbox_button_url', $cstmfldssrch_options['fields'] ) ) {
				foreach ( $cstmfldssrch_options['fields'] as $key => $value ) {
					if ( 'gllr_image_text' == $value || 'gllr_link_url' == $value || 'gllr_image_alt_tag' == $value ||
					 'gllr_lightbox_button_url' == $value || 'gllr_image_description' == $value ) {
						unset( $cstmfldssrch_options['fields'][ $key ] );
						$flag_gllr_image[] = $value;
					}
				}
			}
			
			$user_request = esc_sql( trim( $wp_query->query_vars['s'] ) );
			$user_request_arr = preg_split( "/[\s,]+/", $user_request ); /* The user's regular expressions are used to separate array for the desired keywords */

			if ( ! empty( $cstmfldssrch_options['fields'] ) ) {
				$cusfields_sql_request = "'" . implode( "', '", $cstmfldssrch_options['fields'] ) . "'"; /* forming a string with the list of meta_key, which user has selected */
				$where .=  " OR (" . $wpdb->postmeta . ".meta_key IN (" . $cusfields_sql_request . ") "; /* Modify the request */
				foreach ( $user_request_arr as $value ) {
					$where .= "AND " . $wpdb->postmeta . ".meta_value LIKE '%" . $value . "%' ";
				} 
				$where .= $end_of_where_request . ") ";
			}

			/* This code special for gallery plugin */
			if ( ! empty( $flag_gllr_image ) ) {
				foreach ( $flag_gllr_image as $flag_gllr_image_key => $flag_gllr_image_value ) {

					$where_new_end = '';
					/* save search keywords */
					foreach ( $user_request_arr as $value ) {
						$where_new_end .= "AND " . $wpdb->postmeta . ".meta_value LIKE '%" . $value . "%' ";
					}
					/* search posts-attachments */			
					$id_attachment_arr = $wpdb->get_col( "SELECT " . $wpdb->posts . ".id FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE  " . $wpdb->postmeta . ".meta_key = '" . $flag_gllr_image_value . "' " . $where_new_end );	
					/* if posts-attachments exists - search gallery post ID */
					if ( ! empty( $id_attachment_arr ) ) {
						$array_id_gallery = array();
						foreach ( $id_attachment_arr as $value ) {
							$id_gallery = $wpdb->get_col( "SELECT DISTINCT(" . $wpdb->posts . ".post_parent) FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".ID = " . $value );
							if ( ! in_array( $id_gallery[0],$array_id_gallery ) ) {
								$array_id_gallery[] = $id_gallery[0];
							}
						}
					}
					/* if gallery post ID exists - show on page */
					if ( ! empty( $array_id_gallery ) ) {
						foreach ( $array_id_gallery as $value ) {
							$where .= " OR " . $wpdb->posts . ".ID = " . $value;
						}
					}
				}
			}
		}
		return $where;
	}
}

/* Function is forming page of the settings of this plugin */
if ( ! function_exists( 'cstmfldssrch_page_of_settings' ) ) {
	function  cstmfldssrch_page_of_settings() {
		global $wpdb, $cstmfldssrch_options, $cstmfldssrch_plugin_info;
		$message = "";

		if ( isset( $_REQUEST['cstmfldssrch_submit_nonce'] ) && check_admin_referer( plugin_basename( __FILE__ ), 'cstmfldssrch_nonce_name' ) ) {
			$cstmfldssrch_options['fields'] = isset( $_REQUEST['cstmfldssrch_fields_array'] ) ? $_REQUEST['cstmfldssrch_fields_array'] : array();
			$cstmfldssrch_options['plugin_option_version'] = $cstmfldssrch_plugin_info["Version"];
			$cstmfldssrch_options['show_hidden_fields'] = isset( $_REQUEST['cstmfldssrch_show_hidden_fields'] ) ? 1 : 0;
			update_option( 'cstmfldssrch_options', $cstmfldssrch_options );
			$message = __( "Settings saved" , 'custom-fields-search' );
		} /* Retrieve all the values ​​of custom fields from the database - the keys */
		if ( 0 == $cstmfldssrch_options['show_hidden_fields'] ) {
			$meta_key_custom_posts	=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE  " . $wpdb->posts . ".post_type NOT IN ('revision', 'page', 'post', 'attachment', 'nav_menu_item') AND meta_key NOT REGEXP '^_'" );
			$meta_key_result		=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " WHERE `meta_key` NOT REGEXP '^_'" ); /* select all user's meta_key from table `wp_postmeta` */
		} else {
			$meta_key_custom_posts	=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta . " JOIN " . $wpdb->posts . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE  " . $wpdb->posts . ".post_type NOT IN ('revision', 'page', 'post', 'attachment', 'nav_menu_item')" );
			$meta_key_result		=	$wpdb->get_col( "SELECT DISTINCT(meta_key) FROM " . $wpdb->postmeta ); /* select all meta_key from table `wp_postmeta` */
		}
		$install_plugins		=	get_plugins(); ?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo get_admin_page_title(); ?></h2>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="admin.php?page=custom-fields-search.php"><?php _e( 'Settings', 'custom-fields-search' ); ?></a>
				<a class="nav-tab" href="http://bestwebsoft.com/products/custom-fields-search/faq" target="_blank"><?php _e( 'FAQ', 'custom-fields-search' ); ?></a>
			</h2>			
			<div class="updated fade" <?php if ( ! isset( $_REQUEST['cstmfldssrch_submit_nonce'] ) ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div id="cstmfldssrch_settings_notice" class="updated fade" style="display:none"><p><strong><?php _e( "Notice:", 'custom-fields-search' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'custom-fields-search' ); ?></p></div>
			<form method="post" action="" style="margin-top: 10px;" id="cstmfldssrch_settings_form">				
				<table class="form-table">
					<tr valign="top">
						<?php if ( 0 < count( $meta_key_result ) ) { ?>
							<th scope="row"><?php _e( 'Enable search for the custom field:', 'custom-fields-search' ); ?></th>
							<?php if ( is_plugin_active( 'custom-search-pro/custom-search-pro.php' ) || is_plugin_active( 'custom-search-plugin/custom-search-plugin.php' ) ) { ?>
								<td>
									<div id="cstmfldssrch_div_select_all" style="display:none;"><label ><input id="cstmfldssrch_select_all" type="checkbox" /><span style="text-transform: capitalize; padding-left: 5px;"><strong><?php _e( 'All', 'custom-fields-search' ); ?></strong></span></label></div>
									<?php foreach ( $meta_key_result as $value ) { ?>
										<label><input type="checkbox" <?php if ( in_array( $value, $cstmfldssrch_options['fields'] ) ) echo 'checked="checked"'; ?> name="cstmfldssrch_fields_array[]" value="<?php echo $value; ?>" /><span class="value_of_metakey"><?php echo $value; ?></span></label><br />
									<?php }	?>
								</td>
							<?php } else {
								$i = 1; ?>
								<td>
									<div id="cstmfldssrch_div_select_all" style="display:none;"><label ><input id="cstmfldssrch_select_all" type="checkbox" /><span style="text-transform: capitalize; padding-left: 5px;"><strong><?php _e( 'All', 'custom-fields-search' ); ?></strong></span></label></div>
									<?php foreach ( $meta_key_result as $value ) {
										if ( FALSE !== in_array( $value, $meta_key_custom_posts ) ) {
											$list_custom_key[ $i ] = $value;
											$i++;
										} else { ?>
											<label><input type="checkbox" <?php if ( in_array( $value, $cstmfldssrch_options['fields'] ) ) echo 'checked="checked"'; ?> name="cstmfldssrch_fields_array[]" value="<?php echo $value; ?>" /><span class="value_of_metakey"><?php echo $value; ?></span></label><br />
										<?php }
									}
									echo "<br />";
									if ( isset( $list_custom_key ) ) {
										foreach ( $list_custom_key as $value ) {
											$post_type_of_mkey = $wpdb->get_col( "SELECT DISTINCT(post_type) FROM " . $wpdb->posts . " JOIN " . $wpdb->postmeta . " ON " . $wpdb->posts . ".id = " . $wpdb->postmeta . ".post_id WHERE  " . $wpdb->postmeta . ".meta_key LIKE ('" . $value . "')" ); ?>
											<label><input type="checkbox" disabled="disabled" name="cstmfldssrch_fields_array[]" value="<?php echo $value; ?>" />
											<span class="disable_key">
												<?php echo $value . " (" . $post_type_of_mkey[0] . " " . __( 'custom post type', 'custom-fields-search' ); ?>)
											</span></label><br />
										<?php }
										if ( array_key_exists( 'custom-search-pro/custom-search-pro.php', $install_plugins ) || array_key_exists( 'custom-search-plugin/custom-search-plugin.php', $install_plugins ) ) { ?>
											<span class="note_bottom"><?php _e( 'You need to', 'custom-fields-search' ); ?> <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'activate plugin', 'custom-fields-search' ); ?> Custom Search</a><span>
										<?php } else { ?>
											<span class="note_bottom"><?php _e( 'If the type of the post is not default - you need to install and activate the plugin', 'custom-fields-search' ); ?> <a href="http://bestwebsoft.com/products/custom-search/download">Custom Search</a>.<span>
										<?php } 
									} ?>
								</td>
							<?php }
						} else { ?>
							<th scope="row" colspan="2"><?php _e( 'Custom fields not found.', 'custom-fields-search' ); ?></th>
						<?php } ?>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Show hidden fields', 'custom-fields-search' ); ?></th>
						<td>
							<input type="checkbox" <?php if ( 1 == $cstmfldssrch_options['show_hidden_fields'] ) echo  'checked="checked"'; ?> name="cstmfldssrch_show_hidden_fields" value="1" />
						</td>
					</tr>						
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'custom-fields-search' ); ?>" />
					<input type="hidden" name="cstmfldssrch_submit_nonce" value="submit" />
					<?php wp_nonce_field( plugin_basename( __FILE__ ), 'cstmfldssrch_nonce_name' ); ?>
				</p>				
			</form>
			<?php bws_plugin_reviews_block( $cstmfldssrch_plugin_info['Name'], 'custom-fields-search' ); ?>
		</div>
	<?php }
}

/* Function are using to create action-link 'settings' on admin page. */
if ( ! function_exists( 'cstmfldssrch_action_links' ) ) {
	function cstmfldssrch_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			$base = plugin_basename( __FILE__ );
			if ( $file == $base ) {
				$settings_link = '<a href="admin.php?page=custom-fields-search.php">' . __( 'Settings', 'custom-fields-search' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}

/* Function are using to create link 'settings' on admin page. */
if ( ! function_exists ( 'cstmfldssrch_links' ) ) {
	function cstmfldssrch_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[]	=	'<a href="admin.php?page=custom-fields-search.php">' . __( 'Settings','custom-fields-search' ) . '</a>';
			$links[]	=	'<a href="http://wordpress.org/plugins/custom-fields-search/faq/">' . __('FAQ','custom-fields-search') . '</a>';
			$links[]	=	'<a href="http://support.bestwebsoft.com">' . __('Support','custom-fields-search') . '</a>';
		}
		return $links;
	}
}

/* Function for delete options from table `wp_options` */
if ( ! function_exists( 'cstmfldssrch_delete_options' ) ) {
	function cstmfldssrch_delete_options() {
		delete_option( 'cstmfldssrch_options' );
	}
}

register_activation_hook( __FILE__, 'cstmfldssrch_register_options' );

add_action( 'admin_menu', 'cstmfldssrch_add_to_admin_menu' );
add_action( 'init', 'cstmfldssrch_init' );
add_action( 'admin_init', 'cstmfldssrch_admin_init' );
add_action( 'admin_enqueue_scripts', 'cstmfldssrch_admin_head' );

add_filter( 'posts_distinct', 'cstmfldssrch_distinct' );
add_filter( 'posts_join', 'cstmfldssrch_join' );
add_filter( 'posts_where', 'cstmfldssrch_request' );
add_filter( 'plugin_action_links', 'cstmfldssrch_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cstmfldssrch_links', 10, 2 );

register_uninstall_hook( __FILE__, 'cstmfldssrch_delete_options' );
?>