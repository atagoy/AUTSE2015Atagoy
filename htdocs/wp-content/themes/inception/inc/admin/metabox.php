<?php
/**
 * Handles the theme's theme customizer functionality.
 *
 * @package    inception
 * @author     Rajeeb Banstola <rajeebthegreat@gmail.com>
 * @copyright  Copyright (c) 2014, Rajeeb Banstola
 * @link       http://rajeebbanstola.com.np
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */
 
/* Sets the path to the layout images directory. */
define( 'inception_ADMIN_IMAGES_URL', get_template_directory_uri() .'/inc/admin/img/' );

 add_action( 'add_meta_boxes', 'inception_add_custom_box' );
/**
 * Add Meta Boxes.
 * 
 * Add Meta box in page and post post types.
 */ 
function inception_add_custom_box() {
	add_meta_box(
		'siderbar-layout',							  								//Unique ID
		__( 'Select layout for this specific Page only ( Note: This setting only reflects if page Template is set as Default Template.)', 'inception' ),   								  //Title
		'inception_sidebar_layout',                   							//Callback function
		'page'                                          							//show metabox in pages
	); 
	add_meta_box(
		'siderbar-layout',							  								//Unique ID
		__( 'Select layout for this specific Post only', 'inception' ),   		//Title
		'inception_sidebar_layout',                   							//Callback function
		'post'                                          							//show metabox in posts
	); 
}

/****************************************************************************************/

global $sidebar_layout;
$sidebar_layout = array(
							'default-sidebar' 		=> array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'default',
															'label' 		=> __( 'Default Layout Set in', 'inception' ).' '.'<a href="'.home_url().'/wp-admin/customize.php" target="_blank">'.__( 'Theme Customizer', 'inception' ).'</a>',
															'thumbnail' => ' '
															),
							'full-width' 				=> array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'full-width',
															'label' 		=> __( '1 Column - Full Width', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '/1col.png'
															),
							'sidebar-content' => array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'sidebar-content',
															'label' 		=> __( '2 Column - Left Sidebar', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '2cl.png'
															),
							'content-sidebar' => array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'content-sidebar',
															'label' 		=> __( '2 Column - Right Sidebar', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '2cr.png'
															),																
							'sidebar-sidebar-content' => array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'sidebar-sidebar-content',
															'label' 		=> __( '3 Column - Sidebar Sidebar Content', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '3cl.png'
															),
							'sidebar-content-sidebar' => array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'sidebar-content-sidebar',
															'label' 		=> __( '3 Column - Sidebar Content Sidebar', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '3cm.png'
															),	
							'content-sidebar-sidebar' => array(
															'id'			=> 'inception_sidebarlayout',
															'value' 		=> 'content-sidebar-sidebar',
															'label' 		=> __( '3 Column - Content Sidebar Sidebar', 'inception' ),
															'thumbnail' => inception_ADMIN_IMAGES_URL . '3cr.png'
															)													
						);
	
/****************************************************************************************/

/**
 * Displays metabox to for sidebar layout
 */
function inception_sidebar_layout() {  
	global $sidebar_layout, $post;  
	// Use nonce for verification  
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );

	// Begin the field table and loop  ?>
	<table id="sidebar-metabox" class="form-table" width="100%">
		<tbody> 
			<tr>
				<?php  
				foreach ($sidebar_layout as $field) {  
					$meta = get_post_meta( $post->ID, $field['id'], true );
					if(empty( $meta ) ){
						$meta='default';
					}
					if( ' ' == $field['thumbnail'] ): ?>
						<label class="description">
						<input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
						</label>
					<?php else: ?>
						<td>
							<label class="description">
							<span><img src="<?php echo esc_url( $field['thumbnail'] ); ?>" width="45" height="36" alt="" /></span></br>
							<input type="radio" name="<?php echo $field['id']; ?>" value="<?php echo $field['value']; ?>" <?php checked( $field['value'], $meta ); ?>/>&nbsp;&nbsp;<?php echo $field['label']; ?>
							</label>
						</td>
					<?php endif;
				} // end foreach 
				?>
			</tr>
		</tbody>
	</table>
	<?php 
}

/****************************************************************************************/


add_action('save_post', 'inception_save_custom_meta'); 
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function inception_save_custom_meta( $post_id ) { 
	global $sidebar_layout, $post; 
	
	// Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
      return;
		
	// Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)  
      return;
		
	if ('page' == $_POST['post_type']) {  
      if (!current_user_can( 'edit_page', $post_id ) )  
         return $post_id;  
   } 
   elseif (!current_user_can( 'edit_post', $post_id ) ) {  
      return $post_id;  
   }  
	
	foreach ($sidebar_layout as $field) {  
		//Execute this saving function
		$old = get_post_meta( $post_id, $field['id'], true); 
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {  
			update_post_meta($post_id, $field['id'], $new);  
		} elseif ('' == $new && $old) {  
			delete_post_meta($post_id, $field['id'], $old);  
		} 
	} // end foreach   
}