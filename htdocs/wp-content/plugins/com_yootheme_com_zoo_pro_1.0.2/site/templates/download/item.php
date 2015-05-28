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
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/templates/download/assets/css/');
JHTML::script('zoo.js', 'components/com_zoo/assets/js/');

$elements = $this->item->getElements(true);

?>

<div id="yoo-zoo">
	<div class="downloads page-<?php echo $this->item->alias; ?>">
	
		<div class="item">
	
			<h1 class="name"><?php echo $this->item->name; ?></h1>
	
			<div class="row">
	
				<?php if (isset($elements['file'])) : ?>
				<div class="download-type download-type-<?php echo str_replace('.','',$elements['file']->getExtension()); ?>">
					<a href="<?php echo $elements['file']->getLink(); ?>" title="<?php echo JText::_('Download').' '.$this->item->name; ?>"></a>
				</div>
				<?php endif; ?>
		
				<?php if (isset($elements['rating'])) : ?>
					<?php echo $elements['rating']->render(ZOO_VIEW_ITEM); ?>
				<?php endif; ?>
		
				<div class="details">
					<ul>
						<li class="modified"><strong><?php echo JText::_('Last Update'); ?></strong>: <?php echo JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')) ?></li>
	
						<?php if (isset($elements['file'])) : ?>
						<li class="filesize"><strong><?php echo JText::_('File size'); ?></strong>: <?php echo $elements['file']->getSize(); ?></li>
						<?php endif; ?>
	
						<?php if (isset($elements['version'])) : ?>
						<li class="version"><strong><?php echo $elements['version']->label; ?></strong>: <?php echo $elements['version']->render(ZOO_VIEW_ITEM); ?></li>
						<?php endif; ?>
	
						<?php if (isset($elements['file'])) : ?>
						<li class="hits"><strong><?php echo JText::_('Hits'); ?></strong>: <?php echo $elements['file']->getHits(); ?></li>
						<?php endif; ?>
						
						<?php if (isset($elements['author'])) : ?>
						<li class="author"><strong><?php echo $elements['author']->label; ?></strong>: <?php echo $elements['author']->render(ZOO_VIEW_ITEM); ?></li>
						<?php endif; ?>
						
						<?php if (isset($elements['license'])) : ?>
						<li class="license"><strong><?php echo $elements['license']->label; ?></strong>: <?php echo $elements['license']->render(ZOO_VIEW_ITEM); ?></li>
						<?php endif; ?>
					</ul>
				</div>
				
				<?php if (isset($elements['file'])) : ?>
				<a class="file" href="<?php echo $elements['file']->getLink(); ?>" title="<?php echo JText::_('Download').' '.$this->item->name; ?>"><span class="file-2"><span class="file-3"><?php echo JText::_('Download'); ?></span></span></a>
				<?php endif; ?>
	
			</div>
	
			<?php if (isset($elements['description']) || isset($elements['information'])) : ?>
			<div class="information">
				<?php if (isset($elements['description'])) : ?>
				<h2 class="description"><?php echo $elements['description']->render(ZOO_VIEW_ITEM); ?></h2>
				<?php endif; ?>
				
				<?php if (isset($elements['information'])) : ?>
				<?php echo $elements['information']->render(ZOO_VIEW_ITEM); ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>
	
			<?php if (isset($elements['comments'])) : ?>
			<div id="comments" class="comments"><?php echo $elements['comments']->render(ZOO_VIEW_ITEM); ?></div>
			<?php endif; ?>
	
		</div>

	</div>
</div>