<?php
/**
* @package   Warp Theme Framework
* @file      display.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

	$options  = array();
    $defaults = array(
        '*'       => 'All',
        'home'    => 'Home',
        'archive' => 'Archive',
        'search'  => 'Search',
        'single'  => 'Single',
        'pages'   => 'Pages',
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

?>
<select name="<?php echo $name;?>[]" style="width:220px;height:120px;" multiple="multiple">
	<?php echo implode("", $options); ?>
</select>