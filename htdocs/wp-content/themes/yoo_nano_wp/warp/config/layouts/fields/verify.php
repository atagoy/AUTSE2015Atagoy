<?php
/**
* @package   Warp Theme Framework
* @file      verify.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$html = array();

if (($checksums = $this['path']->path('template:checksums')) && filesize($checksums)) {
	$this['checksum']->verify($this['path']->path('template:'), $log);

	if ($count = count($log)) {
	
		$html[] = '<a href="#" class="verify-link">Some template files have been modified.</a>';
		$html[] = '<ul class="verify">';
		foreach (array('modified', 'missing') as $type) {
			if (isset($log[$type])) {
				foreach ($log[$type] as $file) {
					$html[] = '<li class="'.$type.'">'.$file.($type == 'missing' ? ' (missing)' : null).'</li>';
				}
			}
		}
		$html[] = '</ul>';

	} else {
		$html[] = 'Verification successful, no file modifications detected.';
	}

} else {
	$html[] = 'Checksum file is missing! Your template is maybe compromised.';
}

echo implode("\n", $html);