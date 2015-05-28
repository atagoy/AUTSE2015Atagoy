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

// include assets css/js
if (strtolower(substr($GLOBALS['mainframe']->getTemplate(), 0, 3)) != 'yoo') {
	JHTML::stylesheet('reset.css', 'components/com_zoo/assets/css/');
}
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/templates/blog_classic/assets/css/');

// init vars
$elements       = $this->item->getElements(true);
$image          = null;
$image_position = null;
$video          = null;
$video_position = null;


// prepare image html
if (isset($elements['image']) && isset($elements['imageposition'])) {
	
	$image_position = $elements['imageposition']->getValue();
	
	if (isset($elements['imagelink'])) {
		$image = '<a class="image-'.$image_position.'" href="'.$elements['imagelink']->render(ZOO_VIEW_ITEM).'" title="'.$this->item->name.'" target="_blank">'.$elements['image']->render(ZOO_VIEW_ITEM).'</a>';
	} else {
		$image = '<div class="image-'.$image_position.'">'.$elements['image']->render(ZOO_VIEW_ITEM).'</div>';
	}
}

// prepare video html
if (isset($elements['video']) && isset($elements['videoposition'])) {
	$video_position = $elements['videoposition']->getValue();
	$video = '<div class="video">'.$elements['video']->render(ZOO_VIEW_ITEM).'</div>';
}

?>

<div id="yoo-zoo">
	<div class="blog page-<?php echo $this->item->alias; ?>">
		
		<div class="item">
	
			<h1 class="name">
				<?php echo $this->item->name; ?>
			</h1>
	
			<p class="postmeta">
				<?php echo JText::_('Written'); ?>

				<?php if (isset($elements['author'])) : ?>
					<?php echo JText::_('by').' '.$elements['author']->render(ZOO_VIEW_ITEM); ?>
				<?php endif; ?>
				
				<?php echo JText::_('on').' '.JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC1')) ?>.
				
				<?php if ($this->item->getRelatedCategories()) : ?>
					<?php echo JText::_('Posted in'); ?>
					<?php
						$relatedCategories = array();
						foreach($this->item->getRelatedCategories() as $relatedCategory) {
							$categoryLink = JRoute::_($this->link_base.'&view=category&category_id='.$relatedCategory->id);
							$relatedCategories[] = '<a href="'.$categoryLink.'">'.$relatedCategory->name.'</a>';
						}
						echo implode(', ', $relatedCategories);
					?>
				<?php endif; ?>
			</p>
	
			<?php if (isset($elements['subheadline'])) : ?>
			<h2 class="subheadline"><?php echo $elements['subheadline']->render(ZOO_VIEW_ITEM); ?></h2>
			<?php endif; ?>
	
			<?php if ($image_position == 'top') echo $image; ?>
			<?php if ($video_position == 'top') echo $video; ?>

			<?php if (isset($elements['text'])) : ?>
			<div class="text">
				<?php if ($image_position == 'left' || $image_position == 'right') echo $image; ?>
				
				<?php
					echo $elements['text']->render(ZOO_VIEW_ITEM, 'intro');
					if ($image_position == 'between') echo $image;
					if ($video_position == 'between') echo $video;
					echo $elements['text']->render(ZOO_VIEW_ITEM, 'readmore');
				?>
			</div>
			<?php endif; ?>
			
			<?php if ($image_position == 'bottom') echo $image; ?>
			<?php if ($video_position == 'bottom') echo $video; ?>
			
			<?php if (isset($elements['socialbookmarks'])) : ?>
			<div class="socialbookmarks">
				<p><?php echo JText::_('Add this page'); ?></p>
				<?php echo $elements['socialbookmarks']->render(ZOO_VIEW_ITEM); ?>
			</div>
			<?php endif; ?>
	
    		<?php if (isset($elements['comments'])) : ?>
			<div id="comments" class="comments"><?php echo $elements['comments']->render(ZOO_VIEW_ITEM); ?></div>
			<?php endif; ?>
    
		</div>

	</div>
</div>