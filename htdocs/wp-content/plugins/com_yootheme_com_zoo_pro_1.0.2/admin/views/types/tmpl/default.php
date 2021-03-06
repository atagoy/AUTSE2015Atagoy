<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" />
			<button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
		</td>
	</tr>
</table>
<div id="tablecell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_('NUM'); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			<th width="20">
				&nbsp;
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
			</th>
			<th width="5%">
				<?php echo JText::_('Elements'); ?>
			</th>						
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count($this->items); $i < $n; $i++)
	{
		$row      = &$this->items[$i];
		$edit     = JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=type&task=edit&cid[]='.$row->id);
		$elements = JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=type&task=editelements&cid[]='.$row->id);
		$checked  = JHTML::_('grid.id', $i, $row->id);
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td align="center">
				<img src="<?php echo ZOO_ADMIN_URI; ?>assets/images/bricks.png" border="0" />
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Type');?>::<?php echo $row->name; ?>">
					<a href="<?php echo $edit; ?>">
						<?php echo $row->name; ?>
					</a>
				</span>
			</td>
			<td align="center">
				<a href="<?php echo $elements; ?>" title="<?php echo JText::_('Edit Elements');?>">
					<img src="<?php echo ZOO_ADMIN_URI; ?>assets/images/brick_edit.png" border="0" />
				</a>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	</table>
</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>