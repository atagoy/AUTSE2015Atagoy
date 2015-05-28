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
JHTML::stylesheet('zoo.css.php', 'components/com_zoo/assets/css/');
JHTML::script('zoo.js', 'components/com_zoo/assets/js/');

$elements = $this->item->getElements(true);

?>

<div id="yoo-zoo">
	<div class="default page-<?php echo $this->item->alias; ?>">

		<div class="item">

			<h1 class="name"><?php echo $this->item->name; ?></h1>

			<?php foreach ($elements as $name => $element) : ?>
				<?php if (!in_array($element->getDisplay(), array(1, 3))) continue; ?>
				
					<div class="element">
						<strong><?php echo $element->label; ?></strong>: <?php echo $element->render(ZOO_VIEW_ITEM); ?>
					</div>

			<?php endforeach; ?>
			
		</div>

	</div>
</div>