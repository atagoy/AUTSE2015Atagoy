﻿<?php
$category_name = single_cat_title("", false);

$mobile_url = home_url();

if ($category_name){
	
	$category_obj = get_term_by('name', $category_name, 'category');
	
	if ($category_obj && isset($category_obj->term_id) && is_numeric($category_obj->term_id)){
		
		$category_id = $category_obj->term_id;
		
		require_once(WMP_PLUGIN_PATH.'libs/safestring/safeString.php');
		$mobile_url .= "/#category/".safeString::clearString($category_name).'/'.$category_id;
	}
}

header("Location: ".$mobile_url);

?>