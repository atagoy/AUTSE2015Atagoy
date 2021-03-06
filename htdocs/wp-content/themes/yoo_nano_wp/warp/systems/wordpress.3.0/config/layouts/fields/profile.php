<?php
/**
* @package   Warp Theme Framework
* @file      profile.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// profile options
$select   = array();
$selected = array();
$profiles = $config->get('profile_data', array('default' => array()));

foreach ($profiles as $profile => $values) {
	$attr = array('value' => $profile);

	// is checked ?
	if ((string) $config->get('profile_default') == $profile) {
		$attr = array_merge($attr, array('selected' => 'selected'));
	}

	$select[]   = sprintf('<option %s />%s</option>', $control->attributes($attr, array('selected')), $profile);
	$selected[] = sprintf('<option %s />%s</option>', $control->attributes($attr), $profile);
}

// pages & section options
$options  = array();
$defaults = array(
    'home'    => 'Home',
    'archive' => 'Archive',
    'search'  => 'Search',
    'single'  => 'Single',
    'page'    => 'Pages',
);

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

<select class="profile" name="<?php echo $name; ?>"><?php echo implode("\n", $selected); ?></select>			

<div id="profile">

	<select><?php echo implode("\n", $select); ?></select>			

	<a class="add" href="#">Add</a>
	<a class="rename" href="#">Rename</a>
	<a class="remove" href="#">Remove</a>
	<a class="assign" href="#">Assign Pages</a>

	<div class="items">
		<select name="items" style="width:220px;height:120px;" multiple="multiple">
			<?php echo implode("", $options); ?>
		</select>
	</div>

	<?php foreach ($config->get('profile_map', array()) as $page => $profile): ?>
		<?php printf('<input %s />', $control->attributes(array('type' => 'hidden', 'name' => "profile_map[$page]", 'value' => $profile))); ?>
	<?php endforeach; ?>

</div>