<?php

//Adds acps_widget widget
class acps_widget extends WP_Widget {

	//Register widget
	function __construct() {
		parent::__construct(
			'acps_widget',
			__('Advanced Custom Post Search', 'acps'),
			array( 'description' => __( 'An Advanced Custom Post Search Widget', 'acps' ), )
		);
		
	}

	//Frontend widget display
	public function widget( $args, $instance ) {
		
	//Get widget settings
	$post_id = apply_filters( 'widget_title', $instance[ 'acps_post_id' ] );

	$acps_post_type = (isset($_POST['acps_post_type'])) ? $_POST['acps_post_type'] : false;
	$acps_keyword = (isset($_POST['keywords'])) ? $_POST['acps_post_type'] : false;
	$post_data = ( isset($_POST['acps_post_type'])) ? $_POST : array();

	if($post_id != false)
	{
		//Get all data
		$acps_post_meta = apply_filters( 'acps/get_meta_data', $post_id );
		
		//Get all options
		$acps_options = get_option('acps_options');
		
		//Results page ID
		$acps_results_page_link = get_permalink($acps_options['acps_results_page']);
		
		//Form Styles
		$acps_form_styles = $acps_options['acps_form_styles'];
		
		//Setup blank variable for taxonomy values
		$acps_taxonomy_values = false;
				
		//Grab taxonomy values (if they exist)
		if( isset($acps_post_meta['acps_taxonomy_values']) )
		{
			$acps_taxonomy_values = $acps_post_meta['acps_taxonomy_values'];
		}
		
		//Loop through meta data to find terms
		foreach($acps_taxonomy_values as $key => $value)
		{
			$acps_check_terms = get_terms( $value, array('hide_empty' => 0) );
			
			//If term is empty, keep checking
			if (empty($acps_check_terms))
			{
				//Unset empty terms from original array
				unset($acps_taxonomy_values[$key]);
			}
			
		}
		
		//Validate post meta
		if( !$acps_post_meta || !isset($acps_post_meta['acps_taxonomy_values']) || empty( $acps_taxonomy_values ) )
		{
			$acps_warning = '<p class="acps_error">Error(acps): Unsufficient settings or invalid Post ID</p>';
			return($acps_warning);
		}
		else
		{
			//Enqeue Styles
			wp_enqueue_style('acps-frontend-styles');
			
			//Set blank values
			$acps_post_type = false;
			$acps_form_title = false;
			$acps_form_container_class = false;
			$acps_form_labels = false;
			$acps_label_text = false;
			$acps_submit_button_text = false;
			$acps_title_position = false;
			$acps_keyword_input = false;
			$acps_keyword_text = false;
			$acps_keyword_form_value = false;
			$acps_blank_term = false;
			$acps_multiple_terms = false;
			
			//Grab post type (if it exists)
			if( isset($acps_post_meta['acps_post_type']) )
			{
				$acps_post_type = $acps_post_meta['acps_post_type'];
			}
			
			//Grab form title (if it exists)
			if( isset($acps_post_meta['acps_form_title']) )
			{
				$acps_form_title = $acps_post_meta['acps_form_title'];
			}
			
			//Grab title position value and save (if it exists)
			if( isset($acps_post_meta['acps_title_position']) )
			{
				$acps_title_position = $acps_post_meta['acps_title_position'];	
			}
			
			//Grab form container value (if it exists)
			if( isset($acps_post_meta['acps_form_container_class']) )
			{
				$acps_form_container_class = $acps_post_meta['acps_form_container_class'];
			}
			
			//Grab label option value ( if it exists )	
			if( isset($acps_post_meta['acps_form_labels']) )
			{
				$acps_form_labels = $acps_post_meta['acps_form_labels'];
			}
			
			//Grab label text fields (if they exist)
			if( isset($acps_post_meta['acps_label_text']) )
			{
				$acps_label_text = $acps_post_meta['acps_label_text'];	
			}
			
			//Grab label text fields (if they exist)
			if( isset($acps_post_meta['acps_submit_button_text']) )
			{
				$acps_submit_button_text = $acps_post_meta['acps_submit_button_text'];	
			}
				
			//Grab text field option (if it exists)
			if( isset($acps_post_meta['acps_keyword_input']) )
			{
				$acps_keyword_input = $acps_post_meta['acps_keyword_input'];	
			}
			
			//Grab keyword text value (if it exists)
			if( isset($acps_post_meta['acps_keyword_text']) )
			{
				$acps_keyword_text = $acps_post_meta['acps_keyword_text'];	
			}
			
			//Grab keyword text value (if it exists)
			if( isset($acps_post_meta['acps_keyword_form_value']) )
			{
				$acps_keyword_form_value = $acps_post_meta['acps_keyword_form_value'];	
			}
			
			//Grab blank term setting (if it exists)
			if( isset($acps_post_meta['acps_blank_term']) )
			{
				$acps_blank_term = $acps_post_meta['acps_blank_term'];	
			}

			//Grab multiple terms setting (if it exists)
			if( isset($acps_post_meta['acps_multiple_terms']) )
			{
				$acps_multiple_terms = $acps_post_meta['acps_multiple_terms'];	
			}
			
			//If title is empty, setup class
			if( !$acps_form_title )
			{
				$acps_title_class = 'no_title';
			}
			else
			{
				$acps_title_class = 'has_title';
			}
				
			//Start Return html
			$acps_html = $args['before_widget'];
			$acps_html .= '<form role="search" method="post" id="acps_form_'.$post_id.'" class="acps_form acps_widget" action="'.$acps_results_page_link.'" >';
			$acps_html .= '<input type="hidden" name="acps_post_type" value="'.$acps_post_type.'" />';
			$acps_html .= '<input type="hidden" name="acps_form_id" value="'.$instance[ 'acps_post_id' ].'" />';

			if($acps_form_title && $acps_title_position == 'outside' )
			{
				$acps_html .= '<h3 class="widget-title acps_title">'.$acps_form_title.'</h3>';
			}
			if($acps_form_container_class)
			{
				if(!$acps_form_labels)
				{
					$acps_html .= '<div class="'.$acps_form_container_class.' '.$acps_title_class.' title_'.$acps_title_position.' no_labels" >';
				}
				else
				{
					$acps_html .= '<div class="'.$acps_form_container_class.' '.$acps_title_class.' title_'.$acps_title_position.'" >';
				}
			}
			else
			{
				$acps_html .= '<div class="acps_form_container">';	
			}
			if($acps_form_title && $acps_title_position == 'inside' )
			{
				$acps_html .= '<h3 class="widget-title acps_title">'.$acps_form_title.'</h3>';
			}
			if($acps_keyword_input)
			{
				$acps_html .= '<p>';
				if($acps_keyword_text && strlen($acps_keyword_text)>0 && $acps_form_labels)
				{
					$acps_html .= '<span class="acps_form_label">'.$acps_keyword_text.'</span>';
				}
				$acps_html .= '<span class="acps_form_control_wrap">';
				if(!$acps_keyword_form_value || strlen($acps_keyword_form_value)<1)
				{
					$acps_keyword_form_value = '';
				}
				if( isset($_POST['keywords']) )
				{
					$acps_html .= '<input class="acps_text_input" type="text" value="'.$_POST['keywords'].'" placeholder="'.$acps_keyword_form_value.'" name="keywords"/>';
				}
				else
				{
					$acps_html .= '<input class="acps_text_input" type="text" placeholder="'.$acps_keyword_form_value.'" name="keywords"/>';
				}
				$acps_html .= '</span>';
				$acps_html .= '</p>';	
			}
			foreach($acps_taxonomy_values as $key => $value)
			{
				$acps_taxonomy_terms = get_terms( $value, array('hide_empty' => 0) );
				
				$acps_html .= '<p>';
				
				if($acps_form_labels)
				{
					if(array_key_exists($key,$acps_label_text))
					{
						$acps_html .= '<span class="acps_form_label">'.$acps_label_text[$key].'</span>';
					}
					else
					{
						$acps_html .= '<span class="acps_form_label">'.$key.'</span>';
					}
				}
				
				$acps_html .= '<span class="acps_form_control_wrap '.$value.'">';
				
				if( $acps_multiple_terms )
				{
					foreach( $acps_taxonomy_terms as $acps_taxonomy_term )
					{
						if ( isset( $post_data[$value] ) && $post_data[$value] != array() ) {
							if( in_array($acps_taxonomy_term->slug,$post_data[$value] )) {
								$acps_html .= '<input id="term_' . $acps_taxonomy_term->slug . '" type="checkbox" name="' . $value . '[]" checked value="' . $acps_taxonomy_term->slug . '"><label for="term_' . $acps_taxonomy_term->slug . '">' . $acps_taxonomy_term->name . '</label><br>';
							}
							else {
								$acps_html .= '<input id="term_' . $acps_taxonomy_term->slug . '" type="checkbox" name="' . $value . '[]" value="' . $acps_taxonomy_term->slug.'"><label for="term_' . $acps_taxonomy_term->slug . '">' . $acps_taxonomy_term->name . '</label><br>';
							}
						}
						else {
							$acps_html .= '<input id="term_' . $acps_taxonomy_term->slug . '" type="checkbox" name="' . $value . '[]" value="' . $acps_taxonomy_term->slug . '"><label for="term_' . $acps_taxonomy_term->slug . '">' . $acps_taxonomy_term->name . '</label><br>';
						}
					}
				}
				else
				{
					$acps_html .= '<select name="'.$value.'">';
					if($acps_blank_term)
					{
						$acps_html .= '<option value="">'.__('Select', 'acps').' '.$key.'...</option>';
					}
					foreach($acps_taxonomy_terms as $acps_taxonomy_term)
					{
						if( isset( $post_data[$value] ) && $post_data[$value] == $acps_taxonomy_term->slug )
						{
							$acps_html .= '<option selected="selected" value="'.$acps_taxonomy_term->slug.'">'.$acps_taxonomy_term->name.'</option>';
						}
						else
						{
							$acps_html .= '<option value="'.$acps_taxonomy_term->slug.'">'.$acps_taxonomy_term->name.'</option>';
						}
					}
					$acps_html .= '</select>';
				}

				$acps_html .= '</span>';
				
				$acps_html .= '</p>';
			}
			
			$acps_html .= '<p>';
			$acps_html .= '<span class="acps_form_control_wrap submit_wrap">';
			
			//Setup defaul button value
			if( !$acps_submit_button_text )
				$acps_submit_button_text = 'Submit';
			
			$acps_html .= '<input type="submit" class="acps_submit" value="'.$acps_submit_button_text.'" />';
			
			$acps_html .= '</span>';
			$acps_html .= '</p>';
			
			$acps_html .= '</div>';
			$acps_html .= '</form>';
			
		}
		
		$acps_html .= $args['after_widget'];
		
		echo $acps_html;
		
	}
	
	}

	//Backend widget form
	public function form( $instance ) {
		
		$acps_find_forms = new WP_Query( 'post_type=acps' );
		
		//Check if posts exists
		if( $acps_find_forms->have_posts() )
		{
			echo '<p>';
			echo '<label for="'.$this->get_field_name('acps_post_id').'" >Form: </label>';
			echo '<select class="widefat" name="'.$this->get_field_name('acps_post_id').'">';
			while( $acps_find_forms->have_posts() ) : $acps_find_forms->the_post();
				
				if ( isset($instance[ 'acps_post_id' ]) && $instance[ 'acps_post_id' ] == get_the_ID() ) {
					echo '<option selected="selected" value="'.get_the_ID().'">'.get_the_title().'</option>';
				}
				else
				{
					echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
				}
			endwhile;
			echo '<select>';
			echo '</p>';
		?>
     
        
        <?php
		}
		
		//Reset all post data (avoids crash)
		wp_reset_postdata();	
	}

	//Sanitise widget values when they are saved
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		$instance['acps_post_id'] = ( ! empty( $new_instance['acps_post_id'] ) ) ? strip_tags( $new_instance['acps_post_id'] ) : '';

		return $instance;
	}

}

// register acps widget
function register_acps_widget() {
    register_widget( 'acps_widget' );
}
add_action( 'widgets_init', 'register_acps_widget' );
