<?php
/**
* @package   Symphony Template
* @file      index.php
* @version   5.5.0 December 2010
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

$warp =& Warp::getInstance();
echo $warp->template->render('template');