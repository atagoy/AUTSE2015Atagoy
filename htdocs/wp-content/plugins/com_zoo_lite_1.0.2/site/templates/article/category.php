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

// include assets css/js
if (strtolower(substr($GLOBALS['mainframe']->getTemplate(), 0, 3)) != 'yoo') {
	JHTML::stylesheet('reset.css', 'components/com_zoo/assets/css/');
}
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/templates/article/assets/css/');

?>

<div id="yoo-zoo">
	<div class="article page-<?php echo $this->category->alias; ?>">

		<?php $image = $this->category->getImage(); ?>
		<?php if ($image || $this->category->full_description) : ?>
		<div class="category">

			<h1 class="name">
				<?php echo $this->category->name; ?>
			</h1>
												
			<?php if ($this->category->full_description || $image) : ?>
			<div class="description">
				<?php if ($image) : ?>
					<img class="image" src="<?php echo $image['src']; ?>" title="<?php echo $this->category->name; ?>" alt="<?php echo $this->category->name; ?>" <?php echo $image['width_height']; ?>/>
				<?php endif; ?>
				<?php echo $this->category->full_description; ?>
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
						$firstlast = "";
						if ($j == 0) $first = "first";
						if ($j == $count - 1) $last = "last";
						echo '<div class="width'.intval(100 / $count).' '.$first.' '.$last.'">'.$columns[$j].'</div>';
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