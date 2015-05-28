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
<?php if ($item) : ?>
	
	<?php 
	$elements = $item->getElements(true);
	$link = JRoute::_($this->link_base.'&view=item&category_id='.$this->category->id.'&item_id='.$item->id); ?>
	
	<h2 class="name"><a href="<?php echo $link; ?>" title="<?php echo $item->name; ?>"><?php echo $item->name; ?></a></h2>

	<?php foreach ($elements as $name => $element) : ?>
		<?php if (!in_array($element->getDisplay(), array(2, 3))) continue; ?>
			
			<div class="element">
				<strong><?php echo $element->label; ?></strong>: <?php echo $element->render(ZOO_VIEW_CATEGORY); ?>
			</div>
																		
	<?php endforeach; ?>

<?php endif; ?>
</div>