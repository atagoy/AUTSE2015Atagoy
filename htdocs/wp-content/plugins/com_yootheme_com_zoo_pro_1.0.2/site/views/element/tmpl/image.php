<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php if ($file) : ?>
	<img src="<?php echo JRoute::_($link); ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
<?php else : ?>
	<?php echo JText::_('No file selected.'); ?>
<?php endif; ?>