<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// add links css class
$xml  =& $this->getHelper('xml');
$list = $xml->load($oldoutput, 'xhtml');

if ($ul = $list->document->getElement('ul')) {
    $ul->addAttribute('class', 'line');
    echo $ul->toString();
}