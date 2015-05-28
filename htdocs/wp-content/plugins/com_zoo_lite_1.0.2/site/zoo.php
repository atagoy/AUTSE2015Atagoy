<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');

// load controller
require_once (ZOO_SITE_PATH.'/controller.php');

$classname	= 'ZooController';
$controller = new $classname();

// perform the request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();