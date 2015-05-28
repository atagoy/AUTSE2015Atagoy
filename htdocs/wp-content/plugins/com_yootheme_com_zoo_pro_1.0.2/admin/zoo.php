<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// load config
require_once(dirname(__FILE__).'/config.php');

// add css, js
JHTML::script('default.js', 'administrator/components/com_zoo/assets/js/');
JHTML::script('zoo.js', 'administrator/components/com_zoo/assets/js/');
JHTML::stylesheet('default.css', 'administrator/components/com_zoo/assets/css/');

// get request vars
$controller = JRequest::getWord('controller');
$task       = JRequest::getCmd('task');

// validate controller
$controllers = array('catalog', 'category', 'item', 'type');
if (!in_array($controller, $controllers)) $controller = 'catalog';

// add submenus
JSubMenuHelper::addEntry(JText::_('Catalogs'), 'index.php?option=com_zoo&controller=catalog', $controller == 'catalog' || $controller == 'category');
JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_zoo&controller=item', $controller == 'item');
JSubMenuHelper::addEntry(JText::_('Types'), 'index.php?option=com_zoo&controller=type', $controller == 'type');

// set the table include path
JTable::addIncludePath(ZOO_ADMIN_PATH.'/tables');

// load controller
require_once(ZOO_ADMIN_PATH.'/controllers/'.$controller.'.php');

$classname  = $controller.'Controller';
$controller = new $classname();

// perform the request task
$controller->execute($task);
$controller->redirect();