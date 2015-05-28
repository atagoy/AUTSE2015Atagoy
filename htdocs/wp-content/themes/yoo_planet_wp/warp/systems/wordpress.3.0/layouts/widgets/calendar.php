<?php
/**
* @package   Warp Theme Framework
* @file      calendar.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
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