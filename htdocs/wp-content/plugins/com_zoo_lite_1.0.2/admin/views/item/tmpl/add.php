<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/
	defined('_JEXEC') or die('Restricted access');
	JFilterOutput::objectHTMLSafe($this->item, ENT_QUOTES);
?>

<?php $link = JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=item&task=edit');?>
<form action="index.php" method="post" name="adminForm">

	<table class="admintable" width="100%">
		<tr valign="top">
			<td width="60%">
				<!-- Menu Type Section -->
				<fieldset>
					<legend><?php echo JText::_('Select Item Type'); ?></legend>
					<ul id="item-type" class="jtree">
						<ul>
							<?php for ($i=0,$n=count($this->types);$i<$n;$i++) : ?>
								<li><div class="node-open"><span></span><a href="<?php echo $link . '&amp;type_id='. $this->types[$i]->id ?>" id= "types"><?php echo $this->types[$i]->name; ?></a></div>
							</li>
							<?php endfor; ?>
						</ul>
					</ul>
				</fieldset>
			</td>
			<td width="40%">
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>

<?php echo ZOO_COPYRIGHT; ?>