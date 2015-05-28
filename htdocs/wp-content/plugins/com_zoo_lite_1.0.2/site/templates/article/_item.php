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

?>
<div class="teaser-item">
	<div class="teaser-item-bg">
	<?php if ($item) : ?>
		
		<?php
			$elements = $item->getElements(true);
			$link = JRoute::_($this->link_base.'&view=item&category_id='.$this->category->id.'&item_id='.$item->id);
		?>

		<h1 class="name">
			<a href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></a>
		</h1>

		<p class="postmeta">
		
			<span class="date">
			<?php echo JText::_('Published on').' '.JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC1')); ?>
			</span>
		
			<?php if ($item->getRelatedCategories()) : ?>
			<span class="related">
				<?php echo JText::_('Posted in'); ?>
				<?php
					$relatedCategories = array();
					foreach($item->getRelatedCategories() as $relatedCategory) {
						$categoryLink = JRoute::_($this->link_base.'&view=category&category_id='.$relatedCategory->id);
						$relatedCategories[] = '<a href="'.$categoryLink.'">'.$relatedCategory->name.'</a>';
					}
					echo implode(', ', $relatedCategories);
				?>
			</span>
			<?php endif; ?>

			<span class="author">
				<?php echo JText::_('Written').' '.JText::_('by').' '.$item->getAuthor(); ?>
			</span>

		</p>

		<?php if (isset($elements['text'])) : ?>
		<div class="text">
			<?php echo $elements['text']->render(ZOO_VIEW_CATEGORY, "intro"); ?>
		</div>
		<?php endif; ?>

		<?php $continue = isset($elements['text']) && $elements['text']->hasValue('readmore'); ?>
		<?php if ($continue) : ?>
		<p class="continue">
			<?php if ($continue) : ?>
			<a class="continue" href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo JText::_('Continue Reading'); ?> <span class="arrow">&raquo;</span></a>
			<?php endif; ?>
		</p>
		<?php endif; ?>

	<?php endif; ?>
	</div>
</div>
