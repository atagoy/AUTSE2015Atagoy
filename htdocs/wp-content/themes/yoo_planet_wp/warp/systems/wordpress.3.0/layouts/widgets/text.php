<?php
/**
* @package   Warp Theme Framework
* @file      text.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$out = trim($oldoutput);

if (preg_match('/^<div class="textwidget">/i', $out)) {
	$out = substr($out, 24, -6);
}

echo $out;