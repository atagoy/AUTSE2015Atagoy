<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.tooltip'); ?>

<form action="index.php" method="post" name="adminForm">

<div id="edit-elements">
	<div id="element-list" class="col width-55">
		<?php
			$elements = $this->type->getElements();
			if ($elements !== false) {
				foreach ($elements as $element) {
					echo '<div class="element">'.JHTML::_('element.configedit', $element, $element->name, 'elements['.$element->name.']').'</div>';
				}
			} 
			if ($elements === false || count($elements) == 0) {
				echo '<i>'.JText::_('No elements defined for this type').'</i>';
			}
		?>
	</div>
	<div id="add-element" class="col width-45">
		<fieldset>
			<legend>Elements</legend>
			<?php
				if (count($this->elements)) {
				    echo '<ul class="groups">';
					foreach ($this->elements as $group => $elements) {	
					    echo '<li class="group">'.JText::_($group).'<ul class="elements">';
						foreach ($elements as $element) {	
							$element->configLoadAssets();
							$metadata = $element->configMetaData();
							echo '<li class="'.$element->type.'" title="'.JText::_('Add element').'">'.JText::_($metadata['name']).'</li>';
						}
					    echo '</ul></li>';
					}
				    echo '</ul>';
				}			
			?>
		</fieldset>
	</div>
</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->type->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->type->id; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<script type="text/javascript">
	window.addEvent('domready', function(){
		var app = new Zoo('<?php echo JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=type&format=raw', false);?>', { msgDeletelog: '<?php echo JText::_('Are you sure you want to delete the element?'); ?>' });
		app.attachEvents();
	});
</script>

<?php echo ZOO_COPYRIGHT; ?>