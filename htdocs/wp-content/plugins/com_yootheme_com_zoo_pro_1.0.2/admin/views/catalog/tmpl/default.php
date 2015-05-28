<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	JFilterOutput::objectHTMLSafe($this->catalog, ENT_QUOTES);
	$editor =& JFactory::getEditor();
	$params =& JComponentHelper::getParams('com_media');
	$img_ext = str_replace(',', '|', trim($params->get('image_extensions'), ','));
?>

<form id="catalog-default" action="index.php" method="post" name="adminForm">

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
				<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $this->catalog->name; ?>" />
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="alias">
					<?php echo JText::_('Alias'); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="alias" id="alias" size="60" value="<?php echo $this->catalog->alias; ?>" /><span class="notice"></span>
			</td>
		</tr>
		<tr>
			<td class="key" valign="top">
				<label>
					<?php echo JText::_('Description'); ?>
				</label>
			</td>
			<td>
				<?php
				// parameters : areaname, content, width, height, cols, rows, show xtd buttons
				echo $editor->display('description', $this->catalog->description, '550', '300', '60', '20', array('pagebreak', 'readmore')) ;
				?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label>
					<?php echo JText::_('Image'); ?>
				</label>
			</td>
			<td>
				<?php echo JHTML::_('control.selectfile', JPATH_ROOT.DS.ZOO_IMAGE_PATH, '/^.*('.$img_ext.')$/i', 'params[image]', $this->catalog->get('image'), 'class="image-select"'); ?>
				<div class="image-preview" title="<?php echo JURI::root().ZOO_IMAGE_PATH.'/'; ?>">
					<img src="<?php echo JURI::root().ZOO_IMAGE_PATH.'/'.$this->catalog->get('image'); ?>" />
				</div>
			</td>
		</tr>			
		<tr>
			<td width="110" class="key">
				<label for="paramscategories_title">
					<?php echo JText::_('Categories Title'); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="params[categories_title]" id="paramscategories_title" size="60" value="<?php echo $this->catalog->get('categories_title'); ?>" />
			</td>
		</tr>		
		<tr>
			<td width="110" class="key">
				<label for="paramsitems_title">
					<?php echo JText::_('Items Title'); ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="params[items_title]" id="paramsitems_title" size="60" value="<?php echo $this->catalog->get('items_title'); ?>" />
			</td>
		</tr>		
	</table>
	</fieldset>

</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->catalog->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->catalog->id; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>