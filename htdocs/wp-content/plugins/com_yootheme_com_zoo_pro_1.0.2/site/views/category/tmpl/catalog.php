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
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/assets/css/');
JHTML::script('zoo.js', 'components/com_zoo/assets/js/');

?>

<div id="yoo-zoo">
	<div class="default page-<?php echo $this->catalog->alias; ?>">
	
		<?php if ($this->alpha_index) : ?>
		<div class="alpha-index">
			<?php
				$alpha_index = array('#', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
				foreach($alpha_index as $char) {
					$link = JRoute::_($this->link_base.'&view=category&alpha_char='.($char == '#' ? 0 : strtolower($char)));
					
					if (count($this->categories['index'][($char == '#' ? 0 : strtolower($char))])) {
						echo '<a href="'.$link.'" title="'.$char.'">'.$char.'</a>';
					} else {
						echo '<span title="'.$char.'">'.$char.'</span>';
					}
				}
			?>
		</div>
		<?php endif; ?>
	
		<?php if (($image = $this->catalog->getImage()) || $this->catalog->description) : ?>
		<div class="category">
		
			<h1 class="name"><?php echo $this->catalog->name; ?></h1>
			
			<div class="description">
				<?php if ($image) : ?>
					<img class="image" src="<?php echo $image['src']; ?>" title="<?php echo $this->catalog->name; ?>" alt="<?php echo $this->catalog->name; ?>" <?php echo $image['width_height']; ?>/>
				<?php endif; ?>
				<?php echo $this->catalog->description; ?>
			</div>
			
		</div>
		<?php endif; ?>	
	
		<?php if ($this->category->countItems() - $this->category->item_count) : ?>
		<div class="sub-categories box">
		
			<?php if ($title = $this->catalog->get('categories_title')) : ?>
				<h1 class="sub-categories-title"><?php echo $title; ?></h1>
			<?php endif; ?>
	
			<?php $i = 0; ?>
			<?php while((list($key, $category) = each($this->selected_categories)) || ($i % $this->category_cols != 0)) : ?>
				<?php if ($category && !$category->countItems()) continue; ?>
				<?php if ($i % $this->category_cols == 0) echo ($i > 0 ? '</div><div class="row">' : '<div class="row first-row">'); ?>
				<div class="<?php echo 'width'.intval(100 / $this->category_cols); ?> <?php if ($i % $this->category_cols == 0) echo 'first-cell'; ?>">
					<div class="sub-category">
					<?php if ($category) : ?>

						<h2 class="name">
							<?php $link = JRoute::_($this->link_base.'&view=category&category_id='.$category->id); ?>
							<a href="<?php echo $link; ?>" title="<?php echo $category->getName(); ?>"><?php echo $category->getName(); ?></a>
							<?php if ($this->item_count) echo '<span>('.$category->countItems().')</span>'; ?>
						</h2>

						<?php if ($category->teaser_description) : ?>
						<div class="description"><?php echo $category->teaser_description; ?></div>
						<?php endif; ?>

						<?php if ($image = $category->getTeaserImage()) : ?>
						<a class="teaser-image" href="<?php echo $link; ?>" title="<?php echo $category->getName(); ?>">
							<img src="<?php echo $image['src']; ?>" title="<?php echo $category->name; ?>" alt="<?php echo $category->name; ?>" <?php echo $image['width_height']; ?>/>
						</a>
						<?php endif; ?>

						<?php if ($this->sub_categories && $category->_children): ?>
						<p class="more-sub-categories">
							<?php
								$children = array();
								foreach ($category->_children as $child) {
									if (!$child->countItems()) continue;
									$link = JRoute::_($this->link_base.'&view=category&category_id='.$child->id);
									$children[] = '<a href="'.$link.'" title="'.$child->getName().'">'.$child->getName().'</a>';
								}
								echo implode(', ', $children);
							?>
						</p>
						<?php endif; ?>

					<?php endif; ?>
					</div>
				</div>
				<?php $i++; ?>
			<?php endwhile; ?>
			</div>

		</div>
		<?php endif; ?>
		
		<?php if (!$this->alpha_char && count($this->items)) : ?>
		<div class="items box">
			
			<?php if ($title = $this->catalog->get('items_title')) : ?>
				<h1 class="items-title"><?php echo $title; ?></h1>
			<?php endif; ?>
			
			<?php $i = 0; ?>
			<?php while((list($key, $item) = each($this->items)) || ($i % $this->item_cols != 0)) : ?>
				<?php if ($i % $this->item_cols == 0) echo ($i > 0 ? '</div><div class="row">' : '<div class="row first-row">'); ?>
				<div class="<?php echo 'width'.intval(100 / $this->item_cols); ?> <?php if ($i % $this->item_cols == 0) echo 'first-item'; ?>">
					<?php echo $this->partial('item', array('item' => $item)); ?>
				</div>
				<?php $i++; ?>
			<?php endwhile; ?>
			</div>

			<?php $link = $this->link_base.'&view=category&category_id='.$this->category->id; ?>
			<?php if ($pagination = $this->pagination->render($link)) : ?>
				<div class="pagination"><span class="pagination-bg"><?php echo $pagination; ?></span></div>
			<?php endif; ?>

		</div>
		<?php endif; ?>
	
	</div>
</div>