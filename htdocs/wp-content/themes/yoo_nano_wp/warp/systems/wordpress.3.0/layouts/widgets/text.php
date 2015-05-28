<?php
/**
* @package   Warp Theme Framework
* @file      text.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$content = trim($module->content);

if (preg_match('/^<div class="textwidget">/i', $content)) {
	$content = substr($content, 24, -6);
}

echo $content;