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

// include assets js/css
JHTML::script('rating.js', 'administrator/components/com_zoo/elements/rating/assets/js/');
JHTML::stylesheet('rating.css', 'administrator/components/com_zoo/elements/rating/assets/css/');

?>
<div id ="element-rating-<?php echo $instance; ?>" class="element-rating">

	<div class="rating star<?php echo $stars; ?>">
		<div class="previous-rating" style="width: <?php echo intval($rating / $stars * 100); ?>%;"></div>
		<div class="current-rating">
		
			<?php for($i = $stars; $i > 0; $i--) : ?>
			<div class="stars star<?php echo $i; ?>" title="<?php echo $i; ?> out of <?php echo $stars; ?>"></div>
			<?php endfor ?>
			
		</div>
	</div>
	
	<div class="vote-message">
		<?php echo $rating.'/<strong>'.$stars.'</strong> '.JText::sprintf('rating (%s votes)', $votes); ?>
	</div>
</div>
<?php if (!$disabled) : ?>
	<script type="text/javascript">
	// <!--
	var vt = new ElementRating('element-rating-<?php echo $instance; ?>', '<?php echo JRoute::_($link, false); ?>');
	// -->
	</script>
<?php endif; ?>