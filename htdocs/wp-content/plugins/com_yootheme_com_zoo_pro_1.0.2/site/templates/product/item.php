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
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/templates/product/assets/css/');
JHTML::script('zoo.js', 'components/com_zoo/assets/js/');

$elements = $this->item->getElements(true);

$image = null;
if (isset($elements['gallery'])) {
	$image = $elements['gallery'];
	unset($elements['gallery']);
	if (isset($elements['image'])) unset($elements['image']);
} elseif (isset($elements['image'])) {
	$image = $elements['image'];
	unset($elements['image']);
}


?>

<div id="yoo-zoo">
	<div class="product page-<?php echo $this->item->alias; ?>">

		<div class="item">
		
			<div class="box-t1">
				<div class="box-t2">
					<div class="box-t3"></div>
				</div>
			</div>

			<div class="box-1">
	
				<div class="details">
				
					<?php if ($image) : ?>
						<div class="image"><?php echo $image->render(ZOO_VIEW_ITEM); ?></div>
					<?php endif; ?>
	
					<h1 class="name"><?php echo $this->item->name; ?></h1>
	
					<?php if (isset($elements['rating'])) : ?>
					<?php echo $elements['rating']->render(ZOO_VIEW_ITEM); ?>
					<?php unset($elements['rating']);?>
					<?php endif; ?>
	
					<?php if (isset($elements['description'])) : ?>
					<div class="description"><?php echo $elements['description']->render(ZOO_VIEW_ITEM, 'readmore'); ?></div>
					<?php unset($elements['description']);?>
					<?php endif; ?>
					
					<?php if (count($elements)) : ?>
						<h2 class="specifications"><?php echo JText::_('Specifications'); ?></h2>
						<table cellspacing="0" cellpadding="0" border="0">
						<?php foreach ($elements as $name => $element) : ?>
							<?php if (!in_array($element->getDisplay(), array(1, 3))) continue; ?>
							<?php if (($element->type == 'radio') || ($element->type == 'select') || ($element->type == 'text') || ($element->type == 'textarea')) : ?>
							
								<tr>
									<td class="label"><?php echo $element->label; ?>:</td>
									<td class="value"><?php echo $element->render(ZOO_VIEW_ITEM); ?></td>
								</tr>
							
							<?php endif; ?>
						<?php endforeach; ?>
						</table>
					<?php endif; ?>
				</div>

				<?php if (isset($elements['comments'])) : ?>
				<div id="comments" class="comments"><?php echo $elements['comments']->render(ZOO_VIEW_ITEM); ?></div>
				<?php endif; ?>

			</div>
	
			<div class="box-b1">
				<div class="box-b2">
					<div class="box-b3"></div>
				</div>
			</div>
			
		</div>

	</div>
</div>