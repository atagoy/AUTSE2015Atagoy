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
$tables = array();
$extensions = array();
$db =& JFactory::getDBO();

// delete related database tables
foreach ($db->getTableList() as $table_name) {
	if (strpos($table_name, $db->getPrefix().'zoo_core') !== false || strpos($table_name, $db->getPrefix().'zoo_type') !== false) {
		$tables[] = $table_name;
	}
}
foreach ($tables as $table_name) {
	$db->setQuery('DROP TABLE `'.$table_name.'`');
	if (!$db->query()) {
	    JError::raiseWarning(0, JText::_('Component').' '.JText::_('Uninstall').': '.$db->stderr(true));
	}
}

// additional extensions
$add =& $this->manifest->getElementByPath('additional');
if (is_a($add, 'JSimpleXMLElement') && count($add->children())) {
    $exts =& $add->children();
    foreach ($exts as $ext) {

		// set query
		switch ($ext->name()) {
			case 'plugin':
				$query = 'SELECT * FROM #__plugins WHERE element='.$db->Quote($ext->attributes('name'));
				break;
			case 'module':
				$query = 'SELECT * FROM #__modules WHERE module='.$db->Quote($ext->attributes('name'));
				break;
		}

		// query extension id and client id
		$db->setQuery($query);
		$res = $db->loadObject();

		$extensions[] = array(
			'name' => $ext->data(),
			'type' => $ext->name(),
			'id' => isset($res->id) ? $res->id : 0,
			'client_id' => isset($res->client_id) ? $res->client_id : 0,
			'installer' => new JInstaller(),
			'status' => false);
    }
}

// uninstall additional extensions
for ($i = 0; $i < count($extensions); $i++) {
	$extension =& $extensions[$i];
	
	if ($extension['id'] > 0 && $extension['installer']->uninstall($extension['type'], $extension['id'], $extension['client_id'])) {
		$extension['status'] = true;
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
					<span style="<?php echo $style; ?>"><?php echo $ext['status'] ? JText::_('Uninstalled successfully') : JText::_('Uninstall FAILED'); ?></span>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
