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
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/templates/article/assets/css/');

$elements = $this->item->getElements(true);

if (isset($elements['imagelink'])) {
	$imagelink  = $elements['imagelink']->render(ZOO_VIEW_ITEM);
	$linktarget = 'target="_blank"';
} else {
	$imagelink  = '';
	$linktarget = '';
}

?>

<div id="yoo-zoo">
	<div class="article page-<?php echo $this->item->alias; ?>">
		
		<div class="item">
	
			<h1 class="name"><?php echo $this->item->name; ?></h1>
				
			<p class="postmeta">
							
				<span class="date">
				<?php echo JText::_('Published on').' '.JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC1')); ?>
				</span>
			
				<?php if ($this->item->getRelatedCategories()) : ?>
				<span class="related">
					<?php echo JText::_('Posted in'); ?>
					<?php
						$relatedCategories = array();
						foreach($this->item->getRelatedCategories() as $relatedCategory) {
							$categoryLink = JRoute::_($this->link_base.'&view=category&category_id='.$relatedCategory->id);
							$relatedCategories[] = '<a href="'.$categoryLink.'">'.$relatedCategory->name.'</a>';
						}
						echo implode(', ', $relatedCategories);
					?>
				</span>
				<?php endif; ?>

				<span class="author">
				<?php
					$author = JFactory::getUser($this->item->created_by);
					echo JText::_('Written').' '.JText::_('by').' '.$author->name;
				?>
				</span>

			</p>
	
			<?php if (isset($elements['text'])) : ?>
			<div class="text">
				<?php echo $elements['text']->render(ZOO_VIEW_ITEM); ?>
			</div>
			<?php endif; ?>
    
		</div>

	</div>
</div>