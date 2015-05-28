<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$content = trim($module->content);

if (preg_match('/^<div class="textwidget">/i', $content)) {
	$content = substr($content, 24, -6);
}

echo $content;