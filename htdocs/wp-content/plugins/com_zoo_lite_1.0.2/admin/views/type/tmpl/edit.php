<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	// JFilterOutput::objectHTMLSafe($this->type, ENT_QUOTES);
	$editor =& JFactory::getEditor();
?>

<form id="type-edit" action="index.php" method="post" name="adminForm">

<div style="overflow:hidden;">

	<fieldset class="adminform">
	<legend><?php echo JText::_('Details'); ?></legend>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="name">
					<?php echo JText::_('Name'); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->type->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_('Alias'); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="alias" id="alias" size="60" value="<?php echo $this->type->alias; ?>" /><span class="notice"></span>
			</td>
		</tr>
	</table>
	</fieldset>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->type->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->type->id; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>