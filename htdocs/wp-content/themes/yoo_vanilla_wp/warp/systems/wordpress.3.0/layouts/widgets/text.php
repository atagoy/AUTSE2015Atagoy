<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$out = trim($oldoutput);

if (preg_match('/^<div class="textwidget">/i', $out)) {
	$out = substr($out, 24, -6);
}

echo $out;