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

	jimport('joomla.html.pane');

	$db		=& JFactory::getDBO();
	$editor =& JFactory::getEditor();
	$pane	=& JPane::getInstance('sliders');
	$row 	= $this->item;
	$format = JText::_('DATE_FORMAT_LC2');

	JHTML::_('behavior.tooltip');
?>

<form id="item-edit" action="index.php" method="post" name="adminForm">

	<div style="overflow:hidden;">
		<div class="col width-70">

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
							<input class="inputbox" type="text" name="name" id="name" size="60" value="<?php echo $row->name; ?>" />
						</td>
					</tr>
					<tr>
						<td width="110" class="key">
							<label for="alias">
								<?php echo JText::_('Alias'); ?>
							</label>
						</td>
						<td>
							<input class="inputbox" type="text" name="alias" id="alias" size="60" value="<?php echo $this->item->alias; ?>" /><span class="notice"></span>
						</td>
					</tr>					
					<tr>
						<td width="110" class="key">
							<label for="type">
								<?php echo JText::_('Type'); ?>
							</label>
						</td>
						<td>
							<?php echo $this->type->name; ?>
						</td>
					</tr>
					<tr>
						<td width="110" class="key">
							<label>
								<?php echo JText::_('Published'); ?>
							</label>
						</td>
						<td>
							<?php
							$text = 'No';
							$val	= 0;
							$options[]	= JHTML::_('select.option', $val, $text);
							$text = 'Yes';
							$val	= 1;
							$options[]	= JHTML::_('select.option', $val, $text);

							echo JHTML::_('select.radiolist', $options, "state", null, 'value', 'text', $row->state);
							?>
						</td>
					</tr>
					<tr>
						<td width="110" class="key">
							<label>
								<?php echo JText::_('Categories'); ?>
							</label>
						</td>
						<td>
							<?php echo $this->lists['select_categories']; ?>
						</td>
					</tr>
				</table>
				<?php echo JHTML::_('type.edit', $this->item); ?>
			</fieldset>

		</div>
		<div class="col width-30">

			<?php
				$db =& JFactory::getDBO();

				$create_date 	= null;
				$nullDate 		= $db->getNullDate();

				// used to hide "Reset Hits" when hits = 0
				if ( !$row->hits ) {
					$visibility = 'style="display: none; visibility: hidden;"';
				} else {
					$visibility = '';
				}

				?>
				<table width="100%" style="border: 1px dashed silver; padding: 5px; margin: 6px 0px 10px 0px;">
				<?php
				if ( $row->id ) {
				?>
				<tr>
					<td>
						<strong><?php echo JText::_('Item ID'); ?>:</strong>
					</td>
					<td>
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td>
						<strong><?php echo JText::_('State'); ?></strong>
					</td>
					<td>
						<?php echo $row->state > 0 ? JText::_('Published') : ($row->state < 0 ? JText::_('Archived') : JText::_('Draft Unpublished'));?>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo JText::_('Hits'); ?></strong>
					</td>
					<td>
						<?php echo $row->hits;?>
						<span <?php echo $visibility; ?>>
							<input name="reset_hits" type="button" class="button" value="<?php echo JText::_('Reset'); ?>" onclick="submitbutton('resethits');" />
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo JText::_('Created'); ?></strong>
					</td>
					<td>
						<?php
						if ( $row->created == $nullDate ) {
							echo JText::_('New item');
						} else {
							echo JHTML::_('date',  $row->created,  $format );
						}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<strong><?php echo JText::_('Modified'); ?></strong>
					</td>
					<td>
						<?php
							if ($row->modified == $nullDate) {
								echo JText::_('Not modified');
							} else {
								echo JHTML::_('date',  $row->modified, JText::_('DATE_FORMAT_LC2'));
							}
						?>
					</td>
				</tr>
				</table>
				<?php

					// Create the form
					$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'item.xml');

					// Details Group
					$active = (intval($row->created_by) ? intval($row->created_by) : $this->user->get('id'));
					$form->set('created_by', $active);
					$form->set('access', $row->access);
					$form->set('created_by_alias', $row->created_by_alias);

					$form->set('created', JHTML::_('date', $row->created, '%Y-%m-%d %H:%M:%S'));
					$form->set('publish_up', JHTML::_('date', $row->publish_up, '%Y-%m-%d %H:%M:%S'));
					if (JHTML::_('date', $row->publish_down, '%Y') <= 1969 || $row->publish_down == $db->getNullDate()) {
						$form->set('publish_down', JText::_('Never'));
					} else {
						$form->set('publish_down', JHTML::_('date', $row->publish_down, '%Y-%m-%d %H:%M:%S'));
					}

					// Metadata Group
					$form->set('description', $row->metadesc);
					$form->set('keywords', $row->metakey);
					$form->loadINI($row->metadata);

					$title = JText::_( 'Parameters - Item' );
					echo $pane->startPane("content-pane");
					echo $pane->startPanel( $title, "detail-page" );
					echo $form->render('details');

					$title = JText::_( 'Metadata Information' );
					echo $pane->endPanel();
					echo $pane->startPanel( $title, "metadata-page" );
					echo $form->render('meta', 'metadata');

					echo $pane->endPanel();
					echo $pane->endPane();
				?>

		</div>
	</div>

<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
<input type="hidden" name="type_id" value="<?php echo $row->type_id; ?>" />
<input type="hidden" name="hits" value="<?php echo $row->hits; ?>" />
<?php echo JHTML::_('form.token'); ?>

</form>

<?php echo ZOO_COPYRIGHT; ?>