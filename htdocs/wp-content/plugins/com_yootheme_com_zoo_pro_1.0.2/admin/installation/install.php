<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// init vars
$error = false;
$extensions = array();

// fix joomla 1.5 bug
$this->parent->getDBO =& $this->parent->getDBO();

// additional extensions
$add =& $this->manifest->getElementByPath('additional');
if (is_a($add, 'JSimpleXMLElement') && count($add->children())) {
    $exts =& $add->children();
    foreach ($exts as $ext) {
		$extensions[] = array(
			'name' => $ext->data(),
			'type' => $ext->name(),
			'folder' => $this->parent->getPath('source').'/'.$ext->attributes('folder'),
			'installer' => new JInstaller(),
			'status' => false);
    }
}

// install additional extensions
for ($i = 0; $i < count($extensions); $i++) {
	$extension =& $extensions[$i];
	
	if ($extension['installer']->install($extension['folder'])) {
		$extension['status'] = true;
	} else {
		$error = true;
		break;
	}
}

// rollback on installation errors
if ($error) {
	$this->parent->abort(JText::_('Component').' '.JText::_('Install').': '.JText::_('Error'), 'component');
	for ($i = 0; $i < count($extensions); $i++) { 
		if ($extensions[$i]['status']) {
			$extensions[$i]['installer']->abort(JText::_($extensions[$i]['type']).' '.JText::_('Install').': '.JText::_('Error'), $extensions[$i]['type']);
			$extensions[$i]['status'] = false;
		}
	}
}

?>
<h3><?php echo JText::_('Additional Extensions'); ?></h3>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('Extension'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($extensions as $i => $ext) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="key"><?php echo $ext['name']; ?> (<?php echo JText::_($ext['type']); ?>)</td>
				<td>
					<?php $style = $ext['status'] ? 'font-weight: bold; color: green;' : 'font-weight: bold; color: red;'; ?>
					<span style="<?php echo $style; ?>"><?php echo $ext['status'] ? JText::_('Installed successfully') : JText::_('NOT Installed'); ?></span>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
