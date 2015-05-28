<?php 
/**
* @package   Warp Theme Framework
* @file      _posts.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// init vars
$colcount = is_front_page() ? $this['config']->get('multicolumns', 1) : 1;
$count    = $this['system']->getPostCount();
$rows     = ceil($count / $colcount);
$columns  = array();
$row      = 0;
$column   = 0;
$i        = 0;

// create columns
while (have_posts()) {
	the_post();

	if ($this['config']->get('multicolumns_order', 1) == 0) {
		// order down
		if ($row >= $rows) {
			$column++;
			$row  = 0;
			$rows = ceil(($count - $i) / ($colcount - $column));
		}
		$row++;
	} else {
		// order across
		$column = $i % $colcount;
	}

	if (!isset($columns[$column])) {
		$columns[$column] = '';
	}

	$columns[$column] .= $this->render('_post');
	$i++;
}

// render columns
if ($count = count($columns)) {
	echo '<div class="items items-col-'.$count.' grid-block">';
	for ($i = 0; $i < $count; $i++) {
		echo '<div class="grid-box width'.intval(100 / $count).'">'.$columns[$i].'</div>';
	}
	echo '</div>';
}