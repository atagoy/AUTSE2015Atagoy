<?php 
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// init vars
$colcount = is_front_page() ? $this->warp->config->get('multicolumns', 1) : 1;
$count    = $this->warp->system->getPostCount();
$rows     = ceil($count / $colcount);
$columns  = array();
$row      = 0;
$column   = 0;
$i        = 0;

// create columns
while (have_posts()) {
	the_post();

	if ($this->warp->config->get('multicolumns_order', 1) == 0) {
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
	echo '<div class="items items-col-'.$count.'">';
	for ($i = 0; $i < $count; $i++) {
		$first = ($i == 0) ? ' first' : null;
		$last  = ($i == $count - 1) ? ' last' : null;
		echo '<div class="width'.intval(100 / $count).$first.$last.'">'.$columns[$i].'</div>';
	}
	echo '</div>';
}