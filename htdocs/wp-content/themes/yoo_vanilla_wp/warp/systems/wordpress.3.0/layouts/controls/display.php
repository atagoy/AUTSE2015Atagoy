<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

	$options  = array();
    $defaults = array(
        '*'          => 'All',
		'front_page' => 'Frontpage',
        'home'       => 'Home (Posts page)',
        'archive'    => 'Archive',
        'search'     => 'Search',
        'single'     => 'Single',
        'pages'      => 'Pages',
    );

	$selected = is_array($value) ? $value : array('*');

	if (count($selected) > 1 && in_array('*', $selected)) {
	    $selected = array('*');
	}

	// set default options
	foreach ($defaults as $val => $label) {
		$attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
		$options[]  = sprintf('<option %s />%s</option>', $control->attributes($attributes), $label);
	}
	
	// set pages
	if ($pages = get_pages()) {
		$options[] = '<optgroup label="Pages">';

		foreach ($pages as $page) {
			$val        = 'page-'.$page->ID;
			$attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
			$options[]  = sprintf('<option %s />%s</option>', $control->attributes($attributes), $page->post_title);
		}

		$options[] = '</optgroup>';                  
	}


	// set categories
	if ($categories = get_categories()) {
		$options[] = '<optgroup label="Categories">';

		foreach ($categories as $category) {
			$val        = 'cat-'.$category->cat_ID;
			$attributes = in_array($val, $selected) ? array('value' => $val, 'selected' => 'selected') : array('value' => $val);
			$options[]  = sprintf('<option %s />%s</option>', $control->attributes($attributes), $category->cat_name);
		}

		$options[] = '</optgroup>';                  
	}

?>
<select name="<?php echo $name;?>[]" style="width:220px;height:120px;" multiple="multiple">
	<?php echo implode("", $options); ?>
</select>