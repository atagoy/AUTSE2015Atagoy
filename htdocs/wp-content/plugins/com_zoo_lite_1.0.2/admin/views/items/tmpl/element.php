<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

defined('_JEXEC') or die('Restricted access');

global $mainframe;

// Initialize variables
$db		=& JFactory::getDBO();
$user	=& JFactory::getUser();
$config	=& JFactory::getConfig();
$now	=& JFactory::getDate();

?>

<form action="index.php" method="post" name="adminForm">

<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_('Filter'); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['select_category'];?>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['select_type'];?>
		</td>
		<td nowrap="nowrap">
			<?php echo $this->lists['select_author'];?>
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
					&nbsp;
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'Type', 'a.type_id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="8%">
					<?php echo JHTML::_('grid.sort', 'Access', 'groupname', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="8%">
					<?php echo JHTML::_('grid.sort', 'Author', 'author', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="10" align="center">
					<?php echo JHTML::_('grid.sort', 'Date', 'c.created', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="10" align="center">
					<?php echo JHTML::_('grid.sort', 'Hits', 'a.hits', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>	
		<tbody>
		<?php
		$k = 0;
		$nullDate = $db->getNullDate();
		for ($i=0, $n=count($this->items); $i < $n; $i++)
		{
			$row    = &$this->items[$i];
			
			// author
			$author = $row->created_by_alias;
			if (!$author && isset($this->users[$row->created_by])) {
				$author = $this->users[$row->created_by]->name;
			}
			
			// access
			$access = isset($this->groups[$row->access]) ? $this->groups[$row->access]->name : '';
		?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td align="center">
					<img src="<?php echo ZOO_ADMIN_URI; ?>assets/images/page_white.png" border="0" />
				</td>
				<td>
					<a style="cursor: pointer;" onclick="window.parent.jSelectArticle('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->name); ?>', '<?php echo JRequest::getVar('object'); ?>');">
						<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
					</a>
				</td>
				<td>
					<?php echo $row->itemtype; ?>
				</td>
			<td align="center">
				<?php echo $access;?>
			</td>
			<td>
				<?php echo $author; ?>
			</td>
			<td nowrap="nowrap">
				<?php echo JHTML::_('date',  $row->created, JText::_('DATE_FORMAT_LC4') ); ?>
			</td>
			<td nowrap="nowrap" align="center">
				<?php echo $row->hits ?>
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
	</table>
</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="element" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>