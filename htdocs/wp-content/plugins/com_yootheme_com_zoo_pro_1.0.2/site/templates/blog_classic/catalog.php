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

?>

<div id="yoo-zoo">
	<div class="blog page-<?php echo $this->catalog->alias; ?>">

		<?php $image = $this->catalog->getImage(); ?>
		<?php $title = $this->catalog->get('categories_title'); ?>
		<?php if ($image || $title || $this->catalog->description) : ?>
		<div class="category">
			
			<div class="headline">
							
				<h1 class="name">
					<?php echo $this->catalog->name; ?>
				</h1>
				
				<?php if ($title) : ?>
				<h2 class="sub-categories-title">
					<?php echo $title; ?>
				</h2>
				<?php endif; ?>
	
			</div>
	
			<?php if ($this->catalog->description || $image) : ?>
			<div class="description">
				<?php if ($image) : ?>
					<img class="image" src="<?php echo $image['src']; ?>" title="<?php echo $this->catalog->name; ?>" alt="<?php echo $this->catalog->name; ?>" <?php echo $image['width_height']; ?>/>
				<?php endif; ?>
				<?php echo $this->catalog->description; ?>
			</div>
			<?php endif; ?>
										
		</div>
		<?php endif; ?>

		<?php

			// render items in columns
			if (!$this->alpha_char && count($this->items)) {
	
				// init vars
				$i       = 0;
				$columns = array();
	
				// create columns
				foreach($this->items as $item) {
	
					$column = $i++ % $this->item_cols;
	
					if (!isset($columns[$column])) {
						$columns[$column] = '';
					}
	
					$columns[$column] .= $this->partial('item', array('item' => $item));
				}

				// render columns
				$count = count($columns);
				if ($count) {
					echo '<div class="items row items-col-'.$count.'">';
					for ($j = 0; $j < $count; $j++) {
						$first = ($j == 0) ? ' first' : null;
						$last  = ($j == $count - 1) ? ' last' : null;
						echo '<div class="width'.intval(100 / $count).$first.$last.'">'.$columns[$j].'</div>';
					}
					echo '</div>';
				}
				
				?>
		
				<?php $link = $this->link_base.'&view=category&category_id='.$this->category->id; ?>
				<?php if ($pagination = $this->pagination->render($link)) : ?>
					<div class="pagination"><span class="pagination-bg"><?php echo $pagination; ?></span></div>
				<?php endif; ?>
			
		<?php }	?>

	</div>
</div>