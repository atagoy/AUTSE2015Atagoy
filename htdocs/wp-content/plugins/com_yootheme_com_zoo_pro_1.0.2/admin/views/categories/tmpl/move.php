<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
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
			<td width="40%">				
				<fieldset>
					<legend><?php echo JText::_('Select Target Category'); ?></legend>
					<?php echo $this->lists['select_parent']; ?>
				</fieldset>
			</td>
			<td width="60%">
				<fieldset>
					<legend><?php echo JText::_('Categories being moved'); ?></legend>
					<?php
						if (count($this->categories)) {
						    echo '<ol>';
							foreach ($this->categories as $category) {	
								echo '<li>'.$category->name.'<input type="hidden" name="cid[]" value="'.$category->id.'" /></li>';
							}
						    echo '</ol>';
						}			
					?>
				</fieldset>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="catalog_id" value="<?php echo $this->catalog_id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>

<?php echo ZOO_COPYRIGHT; ?>