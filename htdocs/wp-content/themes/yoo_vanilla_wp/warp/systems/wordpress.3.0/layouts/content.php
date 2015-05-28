<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/
    
$content = '';

if (is_home()) {
	$content = 'index';
} elseif (is_page()) {
	$content = 'page';
} elseif (is_attachment()) {
	$content = 'attachment';
} elseif (is_single()) {
	$content = 'single';
} elseif (is_search()) {
	$content = 'search';
} elseif (is_archive() && is_author()) {
	$content = 'author';
} elseif (is_archive()) {
	$content = 'archive';
} elseif (is_404()) {
	$content = '404';
}

echo $this->render(apply_filters('warp_content', $content));