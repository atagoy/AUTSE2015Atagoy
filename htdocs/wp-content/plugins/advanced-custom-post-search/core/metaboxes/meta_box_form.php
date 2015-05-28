<?php
// Meta Box Fields

//Get all [non-standard] post types
$not_default = array(
'public'   => true,
'_builtin' => false
);

//Get all data
$acps_post_meta = apply_filters( 'acps/get_meta_data', $post->ID );

//Set up post types array
$posttypes = get_post_types($not_default);

//Set blank values
$acps_post_type = false;
$acps_taxonomy_values = false;
$acps_form_title = false;
$acps_form_container_class = false;
$acps_form_labels = false;
$acps_keyword_text = false;
$acps_label_text = false;
$acps_submit_button_text = false;
$acps_title_position = false;
$acps_keyword_input = false;
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

//Grab title position value and save as variable
if( isset($acps_post_meta['acps_title_position']) )
{
	$acps_title_position = $acps_post_meta['acps_title_position'];
}

//Grab taxonomy values (if they exist)
if( isset($acps_post_meta['acps_taxonomy_values']) )
{
	$acps_taxonomy_values = $acps_post_meta['acps_taxonomy_values'];
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

//Grab keyword label text value ( if it exists )	
if( isset($acps_post_meta['acps_keyword_text']) )
{
	$acps_keyword_text = $acps_post_meta['acps_keyword_text'];
}

//Grab search button text (if it exists)
if( isset($acps_post_meta['acps_submit_button_text']) )
{
	$acps_submit_button_text = $acps_post_meta['acps_submit_button_text'];	
}

//Grab keyword option value ( if it exists )	
if( isset($acps_post_meta['acps_keyword_input']) )
{
	$acps_keyword_input = $acps_post_meta['acps_keyword_input'];
}

//Grab keyword option value ( if it exists )	
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

?>
<table class="widefat acps_search_form_table">
    <tbody>
        <tr class="form_label">
            <td class="label">
                <label>Post Type</label>
                <p class="description">Select a post type from the dropdown to begin creating a form</p>
            </td>
            <td>
			<?php if ( $posttypes ) { ?>
            <select data-placeholder="Select a post type" name="acps_post_type" class="acps-post-type chosen_select">
            <option value=""></option>
            <?php if(isset($acps_post_type) && $posttypeobject = get_post_type_object($acps_post_type)){ ?>
                <option selected="selected" value="<?php echo $posttypeobject->name ?>"><?php echo $posttypeobject->labels->name ?></option>
            <?php }
				foreach($posttypes as $posttype){
				if($posttype == $acps_post_type){ continue; }
					$posttypeobject = get_post_type_object($posttype);
					$posttypename = $posttypeobject->labels->name;
					echo '<option value="'.$posttypeobject->name.'">'.$posttypename.'</option>';
				} ?>
				</select>
			<?php } ?> 
            </td>
		</tr>
        <tr class="form_label acps_taxonomies <?php if( !$acps_post_type ){ echo 'hidden'; } ?>">
        	<td class="label">
            	<label>Taxonomies</label>
                <p class="description">Select one or more taxonomies</p>
            </td>
            <td class="acps_taxonomies_results">
            <?php if($acps_post_type)
			{
			$taxonomy_objects = get_object_taxonomies($acps_post_type, 'objects' );
		foreach($taxonomy_objects as $tax_object): ?>
            <label class="acps-checkbox">
            <?php $tax_name_checker = $tax_object->name; ?>
			<input type="checkbox" class="acps_taxonomy_value" name="acps_taxonomy_values[<?php echo $tax_object->label; ?>]" <?php if($acps_taxonomy_values){ if(in_array($tax_name_checker, $acps_taxonomy_values)){ echo 'checked="checked"'; } } ?> value="<?php echo $tax_object->name; ?>" name="<?php echo $tax_object->name; ?>" /><span class="acps_taxonomy_label"><?php echo $tax_object->label; ?></span>
            </label>
		<?php endforeach;
            }
			?>
            </td>
        </tr>
        <tr class="form_label">
            <td class="label">
                <label>Multiple Terms</label>
                <p class="description">Creates checkboxes instead of a dropdown to allow multiple taxonomy term selection.</p>
            </td>
            <td>
                <label>
                <input type="checkbox" value="enabled" class="acps_multiple_terms" name="acps_multiple_terms" <?php if($acps_multiple_terms) { echo 'checked="checked"'; } ?> />
                Enabled</label>
            </td>
        </tr>
        <tr class="form_label">
            <td class="label">
                <label>Blank Term Option</label>
                <p class="description">Tick this box to include a blank option in your frontend form</p>
            </td>
            <td>
            	<label>
				<input type="checkbox" value="enabled" class="acps_blank_term" name="acps_blank_term" <?php if($acps_blank_term) { echo 'checked="checked"'; } ?> />
                Enabled</label>
            </td>
		</tr>
        <tr class="form_label acps_text_field">
            <td class="label">
                <label>Form Title</label>
                <p class="description">Name your form title to be pulled out on page</p>
            </td>
            <td>
				<input type="text" class="acps_text_input" name="acps_form_title"<?php
                if( $acps_form_title && strlen(trim($acps_form_title)) > 0 )
				{
					echo 'value="'.$acps_form_title.'"';
				}
				?>/>
            </td>
		</tr>
        <tr class="form_label acps_text_field">
            <td class="label">
                <label>Title position</label>
                <p class="description">Choose where to position your form title on the frontend</p>
            </td>
            <td>
				<select name="acps_title_position" class="chosen_simple_select">
                	<option <?php if($acps_title_position && $acps_title_position == 'inside' ){ echo 'selected="selected"'; } ?> value="inside">Inside Wrap</option>
                    <option <?php if($acps_title_position == 'outside' ){ echo 'selected="selected"'; } ?> value="outside">Outside Wrap</option>
                </select>
            </td>
		</tr>
        <tr class="form_label acps_text_field">
            <td class="label">
                <label>Container Class</label>
                <p class="description">Specify your form container class to add custom styles</p>
            </td>
            <td>
				<input type="text" class="acps_text_input" name="acps_form_container_class"<?php
                if( $acps_form_container_class && strlen(trim($acps_form_container_class)) > 0 )
				{
					echo 'value="'.$acps_form_container_class.'"';
				} 
				else if($acps_post_type)
				{
					echo 'value="'.$acps_post_type.'_form_container"';
				}
				else
				{
					echo 'value="acps_form_container"';
				}
				?>/>
            </td>
		</tr>
        <tr class="form_label">
            <td class="label">
                <label>Labels</label>
                <p class="description">Select option to show or hide form labels</p>
            </td>
            <td>
            	<label>
				<input type="checkbox" value="enabled" class="acps_form_labels" name="acps_form_labels" <?php if($acps_form_labels) { echo 'checked="checked"'; } ?> />
                Enabled</label>
            </td>
		</tr>
        <tr class="form_label acps_form_label_fields <?php if(!$acps_form_labels) { echo 'hidden'; } else { echo 'active'; } ?>">
        	<td class="label">
            </td>
            <td class="acps_label_results">
            <?php
				if($acps_form_labels)
			{
				foreach($acps_label_text as $key => $value)
				{
				?>
				<div class="acps_label_container">
				<label class="label_label"><?php echo $key; ?></label>
                <input type="text" class="acps_text_input" name="acps_label_text[<?php echo $key; ?>]" <?php if(array_key_exists($key,$acps_label_text)){ echo 'value="'.$acps_label_text[$key].'"'; } ?> />
				</div>
				<?php
				}
			}
			?>
			</td>
        </tr>
        <tr class="form_label acps_text_field">
            <td class="label">
                <label>Submit Button Text</label>
                <p class="description">Edit to change the submit button text (defaults to 'submit')</p>
            </td>
            <td>
				<input type="text" class="acps_text_input" name="acps_submit_button_text"<?php
                if( $acps_submit_button_text && strlen(trim($acps_submit_button_text)) > 0 )
				{
					echo 'value="'.$acps_submit_button_text.'"';
				}
				?>/>
            </td>
		</tr>
        <tr class="form_label">
            <td class="label">
                <label>Keywords</label>
                <p class="description">Select option to show or hide a text input field</p>
            </td>
            <td>
            	<label>
				<input type="checkbox" value="enabled" class="acps_keyword_input" name="acps_keyword_input" <?php if($acps_keyword_input) { echo 'checked="checked"'; } ?> />
                Enabled</label>
            </td>
		</tr>
        <tr class="form_label acps_keyword_label_fields <?php if(!$acps_form_labels) { echo 'hidden'; } else { echo 'active'; } ?>">
            <td class="label">
            </td>
            <td class="acps_keyword_label_results">
			<div class="acps_label_container">
			<label class="label_label">Keyword Label</label>
			<input type="text" class="acps_text_input" <?php if($acps_keyword_text){ echo 'value="'.$acps_keyword_text.'"'; }?> name="acps_keyword_text" />
			</div>
            </td>
		</tr>
        <tr class="form_label acps_text_field">
            <td class="label">
                <label>Keyword Input value</label>
                <p class="description">Enter a default value for your keyword field (leave blank for nothing)</p>
            </td>
            <td>
				<input type="text" class="acps_text_input" name="acps_keyword_form_value"<?php
                if( $acps_keyword_form_value && strlen(trim($acps_keyword_form_value)) > 0 )
				{
					echo 'value="'.$acps_keyword_form_value.'"';
				}
				?>/>
            </td>
		</tr>
        <tr class="form_label">
            <td class="label">
                <label>Shortcode</label>
                <p class="description">Copy and paste the shortcode into your posts or templates</p>
            </td>
            <td>
				[acps id="<?php echo $post->ID; ?>"]
            </td>
		</tr>
	</tbody>
</table>