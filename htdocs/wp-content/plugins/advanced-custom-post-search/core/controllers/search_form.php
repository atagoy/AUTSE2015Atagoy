<?php
//Search form aka post.php for acps post types
class acps_search_form
{
	//Search form construct function
	function __construct()
	{	
		//Admin scripts	
		add_action('admin_enqueue_scripts', array($this,'admin_enqueue_scripts'));
		
		//Save action
		add_action( 'save_post', array($this,'acps_meta_save') );
		
		//Ajax
		add_action('wp_ajax_acps_get_results', array($this,'acps_process_taxonomy_ajax') );
		add_action('wp_ajax_acps_get_label_fields', array($this,'process_acps_label_ajax') );
		add_action('wp_ajax_acps_get_keyword_field', array($this,'process_keyword_label_ajax') );	
	}
	
	//Enqueue scripts function
	function admin_enqueue_scripts()
	{
		//Admin_head hook
		add_action('admin_head', array($this,'admin_head'));	
		
		//Declare settings using filter
		$this->settings = apply_filters('acps/get_settings', 'all');
		
		//Run page validator function check
		if( $this->validate_page() === true )
		{
			//Add custom scripts
			wp_enqueue_script(array(
				'acps-admin-ajax',
				'acps-chzn-scripts'
			));
			
			//Localise ajax scripts
			wp_localize_script('acps-admin-ajax', 'acps_vars', array(
			'acps_nonce' => wp_create_nonce('acps-nonce')
				)
			);
			
			//Add custom styles
			wp_enqueue_style(array(
				'acps-admin-styles',
				'acps-chzn-styles'
			));
		}
	}
	
	//Admin_head function
	function admin_head()
	{	
		//Run page validator function check
		if( $this->validate_page() === true )
		{
			//Global $post variable for ajax variable
			global $post;
			
			//Add variable for ajax request
			echo '<script type="text/javascript">';
			echo 'var acps_post_id = '.$post->ID;
			echo '</script>';
		}
		
		//Add metaboxes
		add_meta_box('acps_fields', __("Form Settings",'acps'), array($this, 'acps_fields_box_html'), 'acps', 'normal', 'high');
	
	}
	
	//Html fields
	function acps_fields_box_html( $post )
	{
		//Include metabox file(s)
		include( $this->settings['path'] . 'core/metaboxes/meta_box_form.php' );
	}
	
	//Validate post page
	function validate_page()
	{
		//Grab WordPress global variables 'pagenow' and 'typenow'
		global $pagenow, $typenow;
		
		//Check if page is post new page
		if(in_array( $pagenow, array('post.php', 'post-new.php') ) )
		{
			//Check if on acps post type
			if($typenow=='acps')
			{
				return true;
			}
		}
	}
	
	//Save post function (saves post data)
	function acps_meta_save( $post_id ) {
	 
	 	//If the post isn't acps, don't run these scripts
	 	if(isset($_POST['post_type']) != "acps")
		{
			return;
		}
	 
		//Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'example_nonce' ] ) && wp_verify_nonce( $_POST[ 'example_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
		//Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce )
		{
			return;
		}
	 
		//Checks for post type and saves if needed
		if( isset( $_POST[ 'acps_post_type' ] ) )
		{
			update_post_meta( $post_id, 'acps_post_type', $_POST[ 'acps_post_type' ] );
		}
		else
		{
			delete_post_meta( $post_id, 'acps_post_type' );
		}
		
		//Checks for taxonomy values and saves if needed
		if( isset( $_POST[ 'acps_taxonomy_values' ] ) )
		{
			update_post_meta( $post_id, 'acps_taxonomy_values',  $_POST[ 'acps_taxonomy_values' ] );
		}
		else
		{
			delete_post_meta( $post_id, 'acps_taxonomy_values' );
		}
		
		//Checks for form title and saves if needed
		if( isset( $_POST[ 'acps_form_title' ] ) )
		{
			update_post_meta( $post_id, 'acps_form_title', $_POST[ 'acps_form_title' ] );
		}
		
		//Checks for tile position value and saves if needed
		if( isset( $_POST[ 'acps_title_position' ] ) )
		{
			update_post_meta( $post_id, 'acps_title_position', $_POST[ 'acps_title_position' ] );
		}
		
		//Checks for container class and saves if needed
		if( isset( $_POST[ 'acps_form_container_class' ] ) )
		{
			update_post_meta( $post_id, 'acps_form_container_class', $_POST[ 'acps_form_container_class' ] );
		}
		
		//Checks for label settings and saves if needed
		if( isset( $_POST[ 'acps_form_labels' ] ) )
		{
			update_post_meta( $post_id, 'acps_form_labels', $_POST[ 'acps_form_labels' ] );
		}
		else
		{
			delete_post_meta($post_id, 'acps_form_labels');
		}
		
		//Checks for keyword label text exists and saves if needed
		if( isset( $_POST[ 'acps_keyword_text' ] ) )
		{
			update_post_meta( $post_id, 'acps_keyword_text', $_POST[ 'acps_keyword_text' ] );
		}
		
		//Checks for label text fields and saves if needed
		if( isset( $_POST[ 'acps_label_text' ] ) )
		{
			update_post_meta( $post_id, 'acps_label_text', $_POST[ 'acps_label_text' ] );
		}
		
		//Checks for label text fields and saves if needed
		if( isset( $_POST[ 'acps_submit_button_text' ] ) )
		{
			update_post_meta( $post_id, 'acps_submit_button_text', $_POST[ 'acps_submit_button_text' ] );
		}
		
		//Checks for text field option exists and saves if needed
		if( isset( $_POST[ 'acps_keyword_input' ] ) )
		{
			update_post_meta( $post_id, 'acps_keyword_input', $_POST[ 'acps_keyword_input' ] );
		}
		else
		{
			delete_post_meta($post_id, 'acps_keyword_input');
		}
		
		//Checks for keyword form value and saves if needed
		if( isset( $_POST[ 'acps_keyword_form_value' ] ) )
		{
			update_post_meta( $post_id, 'acps_keyword_form_value', $_POST[ 'acps_keyword_form_value' ] );
		}
		
		//Checks for blank term settings and saves if needed
		if( isset( $_POST[ 'acps_blank_term' ] ) )
		{
			update_post_meta( $post_id, 'acps_blank_term', $_POST[ 'acps_blank_term' ] );
		}
		else
		{
			delete_post_meta($post_id, 'acps_blank_term');
		}

		//Checks for keyword form value and saves if needed
		if( isset( $_POST[ 'acps_multiple_terms' ] ) )
		{
			update_post_meta($post_id, 'acps_multiple_terms', $_POST[ 'acps_multiple_terms' ] );
		}
		else
		{
			delete_post_meta($post_id, 'acps_multiple_terms');
		}
	}
	
	//Find taxonomies ajax function
	function acps_process_taxonomy_ajax()
	{
		//Check nonce	
		if( !isset( $_POST['acps_post_type_nonce'] ) || !wp_verify_nonce($_POST['acps_post_type_nonce'], 'acps-nonce') )
			die('Permissions check failed');	
		
		//Get taxonomies as an object
		$taxonomy_objects = get_object_taxonomies( $_POST['acps_post_type'], 'objects' );
		
		//Check taxonomies exist
		if(sizeof($taxonomy_objects)<1)
		{
			die('<div class="acps_error"><p>No Taxonomies Found</p></div>');	
		}
		
		//Setup post variable
		$post_id = $_POST['acps_post_id'];
		
		//Get all data
		$acps_post_meta = apply_filters( 'acps/get_meta_data', $post_id );
		
		//Grab taxonomy values (if they exist)
		if( isset($acps_post_meta['acps_taxonomy_values']) )
		{
			$acps_taxonomy_values = $acps_post_meta['acps_taxonomy_values'];
		}
		
		//Loop taxonomies and create checkboxes
		foreach($taxonomy_objects as $tax_object):
		?>
            <label class="acps-checkbox">
			<input type="checkbox" class="acps_taxonomy_value" name="acps_taxonomy_values[<?php echo $tax_object->label; ?>]" <?php if(isset($acps_post_meta['acps_taxonomy_values'])) { if(in_array($tax_object->name, $acps_taxonomy_values)){ echo 'checked="checked"'; } } ?> value="<?php echo $tax_object->name; ?>" name="<?php echo $tax_object->name; ?>" /><span class="acps_taxonomy_label"><?php echo $tax_object->label; ?></span>
            </label>
		<?php endforeach;
		
		die();
	}
	
	//Load label text fields ajax function
	function process_acps_label_ajax()
	{
		//Check nonce	
		if( !isset( $_POST['acps_labels_nonce'] ) || !wp_verify_nonce($_POST['acps_labels_nonce'], 'acps-nonce') )
			die('Permissions check failed');
		
		//Setup blank values
		$acps_label_text = false;
		$acps_form_label_fields = false;
		$acps_keyword_option = false;
		
		//Grab post ID
		$post_id = $_POST['acps_post_id'];

		//Get all data
		$acps_post_meta = apply_filters( 'acps/get_meta_data', $post_id );
		
		//Get label fields (if they exist)
		if( isset($_POST['acps_form_label_fields']) )
		{
			$acps_form_label_fields = $_POST['acps_form_label_fields'];
		}
		
		//Get keyword field option (if it exists)
		if( isset($_POST['acps_keyword_option']) )
		{
			$acps_keyword_option = $_POST['acps_keyword_option'];
		}
		
		//Grab label text fields (if they exist)
		if( isset($acps_post_meta['acps_label_text']) )
		{
			$acps_label_text = $acps_post_meta['acps_label_text'];
		}
		
		//If form labels exist
		if($acps_form_label_fields)
		{
			foreach($acps_form_label_fields as $key => $value)
			{
			?>
			<div class="acps_label_container">
			<label class="label_label"><?php echo $key; ?></label>
			<input type="text" class="acps_text_input" name="acps_label_text[<?php echo $key; ?>]" <?php if($acps_label_text){ if(array_key_exists($key,$acps_label_text)){ echo 'value="'.$acps_label_text[$key].'"'; } } ?> />
			</div>
			<?php
			}
		}
		
		die();
	}
	
	function process_keyword_label_ajax()
	{	
		//Setup blank variables
		$acps_keyword_option = false;
		$acps_keyword_label_text = false;
		
		//Grab post ID
		$post_id = $_POST['acps_post_id'];
		
		//Get all data
		$acps_post_meta = apply_filters( 'acps/get_meta_data', $post_id );

		if( isset($acps_post_meta['acps_keyword_text']) )
		{
			$acps_keyword_label_text = $acps_post_meta['acps_keyword_text'];
		}
		
		//Grab the ajax variable to check whether the option has been enabled
		$acps_keyword_option = $_POST['acps_keyword_option'];
		?>
        
            <div class="acps_label_container">
            <label class="label_label">Keyword Label</label>
            <input type="text" class="acps_text_input" <?php if($acps_keyword_label_text && strlen($acps_keyword_label_text) > 0){ echo "value='{$acps_keyword_label_text}'"; } ?> name="acps_keyword_text" />
            </div>
            
        <?php	
	
		die();
		
	}
	
	
	
}
$acps_search_form = new acps_search_form();