<?php
/*
 * rendering product meta on product page
*/

global $nm_wpregistration;

$existing_meta = get_option('wpregistration_meta');

//wpregistration_pa($existing_meta);

if($existing_meta){
?>

<style>
	#wp-registration-box label.meta-data-label{
		font-size: 18px;
		display:block;
	}
	
	#wp-registration-box .show_description{
		font-size: 12px;
		color: #CCCCCC;
	}
	
	#wp-registration-box p{
		float:left;
	}
	
	#wp-registration-box input[type="text"], input[type="date"],input[type="number"],input[type="email"],select{
		width:98%;
		}
	
	#wp-registration-box span.errors{
		display:block;
		}
	
	<?php echo $this -> get_option('_form_css');?>
	
</style>

<?php 

echo '<form id="wpregistration-meta"';
echo 'data-form="'.esc_attr( json_encode( $existing_meta )	 ).'">';
echo '<div id="wp-registration-box" class="wp-registration-box">';
echo "<input type='hidden' name='_post_id' id='_post_id' value='". $_REQUEST['postid']."'>";
//echo "post id is ".$_REQUEST['postid'];
$postid = $_REQUEST['postid'];

$started_section = '';

		foreach($existing_meta as $key => $meta)
		{
			
			//wpregistration_pa($meta);
			$type = $meta['type'];
			$name = strtolower( preg_replace("![^a-z0-9]+!i", "_", $meta['data_name']) );
			
			// conditioned elements

			$visibility = '';
			$conditions_data = '';
			if ( isset($meta['logic']) && $meta['logic'] == 'on' ) {
				
				if($meta['conditions']['visibility'] == 'Show')
					$visibility = 'display: none';
		
				$conditions_data	= 'data-rules="'.esc_attr( json_encode($meta['conditions'] )).'"';
			}
			
			$show_asterisk 		= (isset( $meta['required'] ) && $meta['required']) ? '<span class="show_required"> *</span>' : '';
			$show_description	= ($meta['description']) ? '<span class="show_description"> '.stripslashes($meta['description']).'</span>' : '';

			$the_width = ($meta['width'] == '' ? 50 : $meta['width']);
			$the_width = intval( $the_width ) - 1 .'%';
			$the_margin = '1%';

			$field_label = stripslashes( $meta['title'] ) . $show_asterisk . $show_description;
			
			if( isset( $meta['required'] ) ){
				$required = $meta['required'];
			} else {
				$required = '';
			}
			if( isset( $meta['error_message'] ) ){
				$error_message = $meta['error_message'];
			} else {
				$error_message = '';
			}
			$args = '';
			
			switch($type)
			{
				
				case 'text':
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
					
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);					
					
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
					
					
				case 'email':
						
					$args = array(	'name'			=> $name,
					'id'			=> $name,
					'data-type'		=> $type,
					'data-req'		=> $required,
					'data-message'	=> $error_message);
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
						
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);
						
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
					
				case 'number':
						
					$args = array(	'name'			=> $name,
					'id'			=> $name,
					'data-type'		=> $type,
					'data-req'		=> $required,
					'data-message'	=> $error_message,
					'max'	=> $meta['max_value'],
					'min'	=> $meta['min_value'],
					'step'	=> $meta['step'],);
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
						
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);
						
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
					
				case 'masked':
						
					$args = array(	'name'			=> $name,
					'id'			=> $name,
					'data-type'		=> $type,
					'data-req'		=> $required,
					'data-mask'		=> $meta['mask'],
					'data-ismask'	=> "no",
					'data-message'	=> $error_message);
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
					
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);
						
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
				
				
			
				case 'date':
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message,
									'data-format'	=> $meta['date_formats']);
			
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'">';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
			
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
		
		
				case 'textarea':
				
		
					$args = array(	'name'	=> $name,
							'id'			=> $name,
							'data-type'		=> $type,
							'data-req'		=> $required,
							'data-message'	=> $error_message);
					
					echo '<div id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'">'. $field_label.' </label>';
					
					$existing_value = get_post_meta($postid, $meta['data_name'], true);
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $existing_value);				
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</div>';
					break;
					
						
				case 'select':
					
					
					$options = explode("\n", $meta['options']);
					$default_selected = $meta['selected'];
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
				
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
					
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $options, $default_selected);
				
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
						
				case 'radio':
					
					$options = explode("\n", $meta['options']);
					$default_selected = $meta['selected'];
					
					$args = array(	'name'			=> $name,
									'id'			=> $name,
									'data-type'		=> $type,
									'data-req'		=> $required,
									'data-message'	=> $error_message);
				
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
					
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $options, $default_selected);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
		
				case 'checkbox':
			
					$options = explode("\n", $meta['options']);
					$defaul_checked = explode("\n", $meta['checked']);
		
					$args = array(	'name'			=> $name,
							'id'			=> $name,
							'data-type'		=> $type,
							'data-req'		=> $required,
							'data-message'	=> $error_message);
					
					echo '<p id="box-'.$name.'" style="width: '. $the_width.'; margin-right: '. $the_margin.';'.$visibility.'" '.$conditions_data.'>';
					echo '<label for="'.$name.'" class="meta-data-label">'. $field_label.' </label>';
					
					$nm_wpregistration -> inputs[$type]	-> render_input($args, $options, $defaul_checked);
					//for validtion message
					echo '<span class="errors"></span>';
					echo '</p>';
					break;
					
					
				case 'section':
				
					if($started_section){		//if section already started then close it first
						echo '<div style="clear: both"></div>';
						echo '</section>';
					}
				
					$section_title 		= strtolower(preg_replace("![^a-z0-9]+!i", "_", $meta['title']));
					$started_section 	= 'wpregistration-section-'.$section_title;
				
					$args = array(	'id'			=> $started_section,
							'data-type'		=> $type,
							'title'			=> $meta['title'],
							'description'	=> $meta['description'],
					);
				
					$nm_wpregistration -> inputs[$type]	-> render_input($args);
				
					break;
					
		
		
				}
		}
		
		
		if(($terms = $this -> get_option('_terms_condition')) != ''){
			
			echo '<div style="clear: both"></div>';
			
			echo '<div class="nm-terms-condition"><label for="nm_terms_accepted">';
			echo '<input type="checkbox" name="nm_terms_accepted" id="nm_terms_accepted" /> ';
			echo $terms;
			echo '</label></div>';
		}
		
		echo '<div style="clear: both"></div>';
		
	echo '</div>';  //ends wp-registration-box
	
	$button_title = $this -> get_option('_button_title');
	$button_title = ($button_title == '' ? 'Register' : $button_title);
	$button_class = $this -> get_option('_button_class');
	
	$button_text_color = $this -> get_option('_button_txt_color');
	$button_bg_color = $this -> get_option('_button_bg_color');
	$button_font_size = $this -> get_option('_button_fontsize');
	
	$custom_style = 'style="color:'.$button_text_color.'; background:'.$button_bg_color.'; font-size:'.$button_font_size.';"';
	
	echo '<p class="wpregistration-save-button"><input type="submit" '.$custom_style.' class="'.$button_class.'" value="'.sprintf(__("%s", 'wp-registration'), $button_title).'"></p>';
	
	echo '<span id="nm-saving-form-meta"></span>';
	wp_nonce_field('doing_registration','nm_wpregistration_nonce');
	echo '</form>';
}
?>