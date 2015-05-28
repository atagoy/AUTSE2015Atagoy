<?php
/** 
 * Enhancements for posts post type
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

add_action( 'init', 'csv2post_init_posttype_contentdesigns' );
add_action( 'add_meta_boxes', 'csv2post_add_meta_boxes_post' );
add_action( 'save_post', 'csv2post_save_meta_boxes_post',10,2 );
/**
* Must be called using add_action( 'init', 'csv2post_register_customposttype_contentdesigns' )
* Registers custom post type for content only
*/
function csv2post_init_posttype_contentdesigns() {
    $labels = array(
        'name' => _x( 'CSV 2 POST Content Templates', 'post type general name' ),
        'singular_name' => _x( 'CSV 2 POST Content Template', 'post type singular name' ),
        'add_new' => _x( 'Create', 'wtgcsvcontent' ),
        'add_new_item' => __( 'Create Content Template' ),
        'edit_item' => __( 'Edit Content Template' ),
        'new_item' => __( 'Create Content Template' ),
        'all_items' => __( 'All Content Templates' ),
        'view_item' => __( 'View Content Template' ),
        'search_items' => __( 'Search Content Templates' ),
        'not_found' =>  __( 'No content templates found' ),
        'not_found_in_trash' => __( 'No content templates found in Trash' ), 
        'parent_item_colon' => '',
        'menu_name' => 'C2P Content'
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => 100,
        'supports' => array( 'title', 'editor', 'custom-fields' )
    );   

    register_post_type( 'wtgcsvcontent', $args );
}

/**
* Adds meta boxes for post posts type 
*/
function csv2post_add_meta_boxes_post() {
    //global $csv2post_settings;
    // author adsense (allows WordPress authors or users with publish_posts ability to add their own adsense snippet)
    //if( $csv2post_settings['monetizesection']['adsense']['authoradsense']['switch'] == 'enabled' && current_user_can( 'publish_posts' ) ){
    //    add_meta_box(
    //        'posts-meta-authoradsense',
    //        esc_html__( 'Author AdSense' ),
    //        'csv2post_metabox_authoradsense',
    //        'post',
    //        'side',
    //        'default'
    //    );
    //}
}

/**
* Tickets Meta Box: authoradsense  
*/
function csv2post_metabox_authoradsense( $object, $box ) { 
    //wp_nonce_field( basename( __FILE__ ), 'postnonce' );
    //$value = get_post_meta( $object->ID, '_c2ppost_authoradsense', true );
    //echo '<textarea id="c2pauthoradsense" name="csv2post_post_authoradsense" rows="5" cols="30">'.$value.'</textarea>'; 
} 

/**
* Save meta box's for post posts type
*/
function csv2post_save_meta_boxes_post( $post_id, $post ) {    
    global $wpdb, $csv2post_settings;
    
    $flagmeta_array = array(
        'authoradsense'
    );
    
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['postnonce'] ) || !wp_verify_nonce( $_POST['postnonce'], basename( __FILE__ ) ) )    
        return $post_id;
        
    // check autosave
    if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return $post_id;
    }
 
    // check permissions
    if ( (key_exists( 'post_type', $post) ) && ( 'post' == $post->post_type) ) {
        if (!current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
    } elseif (!current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }        

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
        return $post_id;
    }
 
    // loop through our terms and meta functions
    foreach( $flagmeta_array as $key => $term)
    {  
        $new_meta_value = '';
        $sent_emails = false;
        $email_recipients_string = '';// comma seperated email address
         
        /* Get the meta key. */
        $meta_key = '_c2ppost_' . $term;
                                            
        if( isset( $_POST['csv2post_post_'.$term] ) )
        {
            $new_meta_value = $_POST['csv2post_post_'.$term];    
        }

        /* Get the meta value of the custom field key. */
        $old_meta_value = get_post_meta( $post_id, $meta_key, true );

        if ( $new_meta_value && '' == $old_meta_value )
        {
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );# new meta value was added and there was no previous value
        }
        elseif ( $new_meta_value && $new_meta_value != $old_meta_value )
        {
            update_post_meta( $post_id, $meta_key, $new_meta_value );# new meta value does not match the old value, update it
        }
        elseif ( '' == $new_meta_value && $old_meta_value )
        {
            delete_post_meta( $post_id, $meta_key, $old_meta_value );# no new meta value but an old value exists, delete it
        }
    }
}  
?>
