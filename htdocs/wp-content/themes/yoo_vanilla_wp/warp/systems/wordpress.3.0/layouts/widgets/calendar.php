<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$xml  =& $this->getHelper('xml');
$cal  = $xml->load($oldoutput, 'xhtml');

if ($table = $cal->document->getElement('table')) {
    $table->_attributes = array();
	$table->addAttribute('class', 'calendar');
	$table->addAttribute('cellspacing', '0');
    echo $table->toString();
}