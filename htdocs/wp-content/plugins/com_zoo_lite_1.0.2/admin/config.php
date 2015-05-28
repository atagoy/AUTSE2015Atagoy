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

// init vars
global $mainframe;
$template = $mainframe->getTemplate();
$media_params =& JComponentHelper::getParams('com_media');

// set defines
define('ZOO_VERSION', '1.0.1');
define('ZOO_COPYRIGHT', '<div class="copyright"><a target="_blank" href="http://zoo.yootheme.com">ZOO</a> is developed by <a target="_blank" href="http://www.yootheme.com">YOOtheme</a>. All Rights Reserved.</div>');
define('ZOO_ICON', 'zoo.png');
define('ZOO_TOOLBAR_TITLE', 'Zoo - ');
define('ZOO_SITE_PATH', JPATH_ROOT.'/components/com_zoo');
define('ZOO_SITE_TEMPLATE_PATH', JPATH_ROOT.'/templates/'.$template.'/html/com_zoo');
define('ZOO_SITE_URI', JURI::root().'components/com_zoo/');
define('ZOO_ADMIN_PATH', dirname(__FILE__));
define('ZOO_ADMIN_TEMPLATE_PATH', JPATH_ROOT.'/administrator/templates/'.$template.'/html/com_zoo');
define('ZOO_ADMIN_URI', JURI::root().'administrator/components/com_zoo/');
define('ZOO_IMAGE_PATH', $media_params->get('image_path'));
define('ZOO_ROOT_ALIAS', 'root');
define('ZOO_TABLE_CATALOG', '#__zoo_core_catalog');
define('ZOO_TABLE_CATEGORY', '#__zoo_core_category');
define('ZOO_TABLE_CATEGORY_ITEM', '#__zoo_core_category_item');
define('ZOO_TABLE_ITEM', '#__zoo_core_item');
define('ZOO_TABLE_TYPE', '#__zoo_core_type');

// register framework
JLoader::register('YController', ZOO_ADMIN_PATH.'/framework/controller.php');
JLoader::register('YError', ZOO_ADMIN_PATH.'/framework/error.php');
JLoader::register('YFactory', ZOO_ADMIN_PATH.'/framework/factory.php');
JLoader::register('YFile', ZOO_ADMIN_PATH.'/framework/file.php');
JLoader::register('YFilterOutput', ZOO_ADMIN_PATH.'/framework/filteroutput.php');
JLoader::register('YObject', ZOO_ADMIN_PATH.'/framework/object.php');
JLoader::register('YPagination', ZOO_ADMIN_PATH.'/framework/pagination.php');
JLoader::register('YTable', ZOO_ADMIN_PATH.'/framework/table.php');
JLoader::register('YView', ZOO_ADMIN_PATH.'/framework/view.php');

// register classes
JLoader::register('Catalog', ZOO_ADMIN_PATH.'/classes/catalog.php');
JLoader::register('Category', ZOO_ADMIN_PATH.'/classes/category.php');
JLoader::register('Item', ZOO_ADMIN_PATH.'/classes/item.php');
JLoader::register('Type', ZOO_ADMIN_PATH.'/classes/type.php');

// register tables
JLoader::register('TableCatalog', ZOO_ADMIN_PATH.'/tables/catalog.php');
JLoader::register('TableCategory', ZOO_ADMIN_PATH.'/tables/category.php');
JLoader::register('TableCategoryItem', ZOO_ADMIN_PATH.'/tables/categoryitem.php');
JLoader::register('TableItem', ZOO_ADMIN_PATH.'/tables/item.php');
JLoader::register('TableType', ZOO_ADMIN_PATH.'/tables/type.php');

// register helpers
JLoader::register('CatalogHelper', ZOO_ADMIN_PATH.'/helpers/catalog.php');
JLoader::register('CategoryHelper', ZOO_ADMIN_PATH.'/helpers/category.php');
JLoader::register('ElementHelper', ZOO_ADMIN_PATH.'/helpers/element.php');
JLoader::register('ItemHelper', ZOO_ADMIN_PATH.'/helpers/item.php');
JLoader::register('MenuHelper', ZOO_ADMIN_PATH.'/helpers/menu.php');
JLoader::register('TypeHelper', ZOO_ADMIN_PATH.'/helpers/type.php');

// add jhtml path
JHTML::addIncludePath(ZOO_ADMIN_PATH.'/helpers');