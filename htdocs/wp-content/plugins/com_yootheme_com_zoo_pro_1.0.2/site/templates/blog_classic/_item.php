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
	<div class="teaser-item-bg">
	<?php if ($item) : ?>
		
		<?php
			// init vars
			$elements       = $item->getElements(true);
			$link           = JRoute::_($this->link_base.'&view=item&category_id='.$this->category->id.'&item_id='.$item->id);
			$image          = null;
			$image_position = null;
			$video          = null;
			$video_position = null;

			// get teaser image if exists
			if (isset($elements['teaserimage']) && isset($elements['teaserimageposition'])) {
				$image = $elements['teaserimage'];
				$image_position = $elements['teaserimageposition']->getValue();
			} elseif (isset($elements['image']) && isset($elements['imageposition'])) {
				$image = $elements['image'];
				$image_position = $elements['imageposition']->getValue();
				if ($image_position == "between") {
					$image_position = "bottom";
				}
			}
			
			// get alternative image link if exists
			$image_link  = (isset($elements['imagelink'])) ? $elements['imagelink']->render(ZOO_VIEW_CATEGORY) : $link;
			$link_target = (isset($elements['imagelink'])) ? 'target="_blank"' : '';
			
			// prepare image html
			if ($image && $image_position) {
				$image = '<a class="image-'.$image_position.'" href="'.$image_link.'" title="'.$item->name.'" '.$link_target.'>'.$image->render(ZOO_VIEW_CATEGORY).'</a>';
			}
			
			// prepare video html
			if (isset($elements['video']) && isset($elements['videoposition'])) {
				if ($elements['video']->display > 1 ) {
					$video_position = $elements['videoposition']->getValue();
					$video = '<div class="video">'.$elements['video']->render(ZOO_VIEW_ITEM).'</div>';
					if ($video_position == "between") {
						$video_position = "bottom";
					}
				}
			}
		?>

		<h1 class="name">
			<a href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></a>
		</h1>

		<p class="postmeta">
			<?php echo JText::_('Written'); ?>
			
			<?php if (isset($elements['author'])) : ?>
				<?php echo JText::_('by').' '.$elements['author']->render(ZOO_VIEW_CATEGORY); ?>
			<?php endif; ?>
			
			<?php echo JText::_('on').' '.JHTML::_('date', $item->created, JText::_('DATE_FORMAT_LC1')) ?>.
			
			<?php if ($item->getRelatedCategories()) : ?>
				<?php echo JText::_('Posted in'); ?>
				<?php
					$relatedCategories = array();
					foreach($item->getRelatedCategories() as $relatedCategory) {
						$categoryLink = JRoute::_($this->link_base.'&view=category&category_id='.$relatedCategory->id);
						$relatedCategories[] = '<a href="'.$categoryLink.'">'.$relatedCategory->name.'</a>';
					}
					echo implode(', ', $relatedCategories);
				?>
			<?php endif; ?>
		</p>

		<?php if (isset($elements['subheadline'])) : ?>
		<h2 class="subheadline"><?php echo $elements['subheadline']->render(ZOO_VIEW_CATEGORY); ?></h2>
		<?php endif; ?>

		<?php if ($image_position == 'top') echo $image; ?>
		<?php if ($video_position == 'top') echo $video; ?>

		<?php if (isset($elements['text'])) : ?>
		<div class="text">
			<?php if ($image_position == 'left' || $image_position == 'right') echo $image; ?>
			<?php echo $elements['text']->render(ZOO_VIEW_CATEGORY, "intro"); ?>
		</div>
		<?php endif; ?>
		
		<?php if ($image_position == 'bottom') echo $image; ?>
		<?php if ($video_position == 'bottom') echo $video; ?>

		<?php $continue = isset($elements['text']) && $elements['text']->hasValue('readmore'); ?>
		<?php $comments = isset($elements['comments']); ?>
		<?php if ($continue || $comments) : ?>
		<p class="links">
			<?php if ($continue) : ?>
			<a class="continue" href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo JText::_('Continue Reading'); ?> <span class="arrow">&raquo;</span></a>
			<?php endif; ?>
			
			<?php if ($continue && $comments) echo "&nbsp;&nbsp;|&nbsp;&nbsp;"; ?>
			
			<?php if ($comments) : ?>
			<a class="comments" href="<?php echo $link.'#comments'; ?>" title="<?php echo JText::_('View Comments').' '.JText::_('for').' '.$item->name; ?>"><?php echo JText::_('View Comments'); ?> <span class="arrow">&raquo;</span></a>
			<?php endif; ?>
		</p>
		<?php endif; ?>

	<?php endif; ?>
	</div>
</div>