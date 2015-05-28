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
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" />
			<button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
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
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'Published', 'a.state', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="8%">
					<?php echo JHTML::_('grid.sort', 'Access', 'a.access', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="8%">
					<?php echo JHTML::_('grid.sort', 'Author', 'a.created_by', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th width="10" align="center">
					<?php echo JHTML::_('grid.sort', 'Date', 'a.created', @$this->lists['order_Dir'], @$this->lists['order']); ?>
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
			$row     = &$this->items[$i];
			$link    = JRoute::_('index.php?option='.$this->option.'&controller='.$this->controller.'&view=item&task=edit&cid[]='.$row->id);
			$checked = JHTML::_('grid.id', $i, $row->id);

			$publish_up =& JFactory::getDate($row->publish_up);
			$publish_down =& JFactory::getDate($row->publish_down);
			$publish_up->setOffset($config->getValue('config.offset'));
			$publish_down->setOffset($config->getValue('config.offset'));

			if ( $now->toUnix() <= $publish_up->toUnix() && $row->state == 1 ) {
				$img = 'publish_y.png';
				$alt = JText::_( 'Published' );
			} else if ( ( $now->toUnix() <= $publish_down->toUnix() || $row->publish_down == $nullDate ) && $row->state == 1 ) {
				$img = 'publish_g.png';
				$alt = JText::_( 'Published' );
			} else if ( $now->toUnix() > $publish_down->toUnix() && $row->state == 1 ) {
				$img = 'publish_r.png';
				$alt = JText::_( 'Expired' );
			} else if ( $row->state == 0 ) {
				$img = 'publish_x.png';
				$alt = JText::_( 'Unpublished' );
			} else if ( $row->state == -1 ) {
				$img = 'disabled.png';
				$alt = JText::_( 'Archived' );
			}

			$times = '';

			if (isset($row->publish_up)) {
				if ($row->publish_up == $nullDate) {
					$times .= JText::_( 'Start: Always' );
				} else {
					$times .= JText::_( 'Start' ) .": ". $publish_up->toFormat();
				}
			}

			if (isset($row->publish_down)) {
				if ($row->publish_down == $nullDate) {
					$times .= "<br />". JText::_( 'Finish: No Expiry' );
				} else {
					$times .= "<br />". JText::_( 'Finish' ) .": ". $publish_down->toFormat();
				}
			}

			// author
			$author = $row->created_by_alias;
			if (!$author && isset($this->users[$row->created_by])) {

				$author = $this->users[$row->created_by]->name;
				
				if ($user->authorize('com_users', 'manage')) {
					$edit_link = 'index.php?option=com_users&task=edit&cid[]='.$row->created_by;
					$author = '<a href="'.JRoute::_($edit_link).'" title="'.JText::_('Edit User').'">'. $author.'</a>';					
				}
			}

			// access
			$group_access = isset($this->groups[$row->access]) ? $this->groups[$row->access]->name : '';

			if (!$row->access)  {
				$color_access = 'style="color: green;"';
				$task_access  = 'accessregistered';
			} else if ($row->access == 1) {
				$color_access = 'style="color: red;"';
				$task_access  = 'accessspecial';
			} else {
				$color_access = 'style="color: black;"';
				$task_access  = 'accesspublic';
			}

			$access = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''.$task_access .'\')" '.$color_access.'>'. JText::_($group_access) .'</a>';
		?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td align="center">
					<?php echo $checked; ?>
				</td>
				<td align="center">
					<img src="<?php echo ZOO_ADMIN_URI; ?>assets/images/page_white.png" border="0" />
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Item');?>::<?php echo $row->name; ?>">
						<a href="<?php echo $link  ?>"><?php echo $row->name; ?></a>
					</span>
				</td>
				<td>
					<?php echo $row->itemtype; ?>
				</td>

					<?php
						if ( $times ) {
							?>
							<td align="center">
								<span class="editlinktip hasTip" title="<?php echo JText::_( 'Publish Information' );?>::<?php echo $times; ?>"><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
									<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a></span>
							</td>
							<?php
						}
						?>
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>