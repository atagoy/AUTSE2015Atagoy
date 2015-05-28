<?php
/**
* @package   Warp Theme Framework
* @file      links.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// add links css class
$xml  =& $this->getHelper('xml');
$list = $xml->load($oldoutput, 'xhtml');

if ($ul = $list->document->getElement('ul')) {
    $ul->addAttribute('class', 'line');
    echo $ul->toString();
}