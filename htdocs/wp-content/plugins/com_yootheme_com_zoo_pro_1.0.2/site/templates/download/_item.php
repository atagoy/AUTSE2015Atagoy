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
<div class="teaser-item">
<?php if ($item) : ?>
	
	<?php
		$elements = $item->getElements(true);
		$link = JRoute::_($this->link_base.'&view=item&category_id='.$this->category->id.'&item_id='.$item->id);
	?>
	
	<?php if (isset($elements['file'])) : ?>
	<div class="download-type download-type-<?php echo str_replace('.','',$elements['file']->getExtension()); ?>">
		<a href="<?php echo $elements['file']->getLink(); ?>" title="<?php echo JText::_('Download').' '.$item->name; ?>"></a>
	</div>
	<?php endif; ?>

	<div class="details">
		<ul>
			<li class="name"><h2><a href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></a></h2></li>

			<li class="modified"><?php echo JText::_('Updated on').' '.JHTML::_('date', $item->modified, JText::_('DATE_FORMAT_LC3')) ?></li>

			<?php if (isset($elements['description'])) : ?>
			<li class="description"><?php echo $elements['description']->render(ZOO_VIEW_CATEGORY); ?></li>
			<?php endif; ?>

			<?php if (isset($elements['version'])) : ?>
			<li class="version"><strong><?php echo $elements['version']->label; ?></strong>: <?php echo $elements['version']->render(ZOO_VIEW_CATEGORY); ?></li>
			<?php endif; ?>

		</ul>
	</div>
	
	<?php if (isset($elements['file'])) : ?>
	<a class="file" href="<?php echo $elements['file']->getLink(); ?>" title="<?php echo JText::_('Download').' '.$item->name; ?>"><span class="file-2"><span class="file-3"><?php echo JText::_('Download'); ?></span></span></a>
	<?php endif; ?>
	
<?php endif; ?>	
</div>