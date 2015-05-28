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
				<?php echo JText::_('NUM');?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
			</th>
			<th width="20">
				&nbsp;
			</th>
			<th  class="title">
				<?php echo JHTML::_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Published', 'a.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Order', 'a.ordering', @$this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php echo JHTML::_('grid.order',  $this->items ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	$j = 0;

	foreach ($this->items as $i => $item)
	{
			$row     	= &$item;
			$link    	= JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=category&task=edit&cid[]='.$row->id);
			$published 	= JHTML::_('grid.published', $row, $i );
			$checked 	= JHTML::_('grid.id', $i, $row->id);
			$ordering 	= ($this->lists['order'] == 'a.ordering');

		?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($j); ?>
				</td>
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td align="center">
					<img src="<?php echo ZOO_ADMIN_URI; ?>assets/images/folder.png" border="0" />
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Category');?>::<?php echo $row->name; ?>">
						<a href="<?php echo $link  ?>"><?php echo $row->treename; ?></a>
					</span>
				</td>
				<td align="center">
					<?php echo $published;?>
				</td>
				<td class="order">
					<span><?php echo $this->pagination->orderUpIcon($i, (CategoryHelper::hasNeighbor($this->allitems, $row, 0)),'orderup', 'Move Up', $ordering); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, 999999, (CategoryHelper::hasNeighbor($this->allitems, $row, 1)), 'orderdown', 'Move Down', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
				</td>
				<td align="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
	<?php
		$j++;
		$k = 1 - $k;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
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
<input type="hidden" name="catalog_id" value="<?php echo $this->catalog_id; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>