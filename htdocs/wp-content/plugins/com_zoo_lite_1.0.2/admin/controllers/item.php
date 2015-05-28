<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: ItemController
		The controller class for item
*/
class ItemController extends YController {

	function __construct($default = array()) {
		parent::__construct($default);
		
		$this->registerTask('add', 'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('apply', 'save');
		
		// clean component cache
		parent::cleanCacheOnTask('com_zoo', array('docopy', 'save', 'apply', 'remove', 'publish', 'unpublish', 'accesspublic', 'accessregistered', 'accessspecial'));
	}

	function display() {

		// set view
		$task  = $this->getTask();
		$tasks = array('add', 'edit');
		if (in_array($task, $tasks)) {
			JRequest::setVar('hidemainmenu', 1);
			JRequest::setVar('view', 'item');
			JRequest::setVar('layout', $task);
		}
		if ($task == 'element') {
			JRequest::setVar('view', 'items');
			JRequest::setVar('layout', $task);
		}

		// set the default view
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'items');
		}

		parent::display();
	}

	function docopy() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$user   =& JFactory::getUser();
		$now    =& JFactory::getDate();
		$table  =& JTable::getInstance('categoryitem', 'Table');
		$option = JRequest::getCmd('option');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');

		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a item to copy'));
		}
		
		// copy items
		foreach ($cid as $id) {
			
			// get item
			$item       = new Item($id);
			$elements   = $item->getElements();
			$categories = $item->getRelatedCategoryIds();
			
			// copy item
			$item->id          = 0;                         // set id to 0, to force new item
			$item->name       .= ' ('.JText::_('Copy').')'; // set copied name
			$item->alias      .= '-copy';                   // set copied alias
			$item->state       = 0;                         // unpublish item
			$item->modified	   = $now->toMySQL();
			$item->modified_by = $user->get('id');

			// save copied item/element data
			if (!$item->save() || !$table->saveCategoryItemRelations($item, $categories)) {
				JError::raiseNotice(0, JText::_('Error Copying Item').' ('.$item->getError().')');
				break;
			}
		}

		if (!$item->getError()) {
			$msg = JText::_('Item Copied');
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link, $msg);
	}

	function save() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg        = null;
		$db         =& JFactory::getDBO();
		$config     =& JFactory::getConfig();
		$user       =& JFactory::getUser();
		$now        =& JFactory::getDate();
		$option     = JRequest::getCmd('option');
		$post       = JRequest::get('post');
		$categories	= JRequest::getVar('categories', null, 'post', 'array');
		$details	= JRequest::getVar('details', null, 'post', 'array');
		$metadata   = JRequest::getVar('meta', null, 'post', 'array');
		$cid        = JRequest::getVar('cid', array(0), 'post', 'array');
		$id         = intval($cid[0]);
		$tzoffset   = $config->getValue('config.offset');
		$post       = array_merge($post, $details);

		// get item
		$item = new Item($id);

		// set type, if new
		if ($item->id == 0) {
			$item->type_id = JRequest::getInt('type_id', 0);
		}

		// filter post data
		$post = ItemHelper::filterElementData($item, $post);

		// bind post data
		$item->bind($post);
		$item->bindMetadata($metadata);
		$item->bindElements($post);

		// filter alias
		$item->alias = JFilterOutput::stringURLSafe($item->alias == '' ? $item->name : $item->alias);

		// set modified
		$item->modified	   = $now->toMySQL();
		$item->modified_by = $user->get('id');

		// set created date
		if ($item->created && strlen(trim($item->created)) <= 10) {
			$item->created .= ' 00:00:00';
		}
		$date =& JFactory::getDate($item->created, $tzoffset);
		$item->created = $date->toMySQL();

		// set publish up date
		if (strlen(trim($item->publish_up)) <= 10) {
			$item->publish_up .= ' 00:00:00';
		}
		$date =& JFactory::getDate($item->publish_up, $tzoffset);
		$item->publish_up = $date->toMySQL();

		// set publish down date
		if (trim($item->publish_down) == JText::_('Never') || trim($item->publish_down) == '') {
			$item->publish_down = $db->getNullDate();
		} else {
			if (strlen(trim($item->publish_down)) <= 10) {
				$item->publish_down .= ' 00:00:00';
			}
			$date =& JFactory::getDate($item->publish_down, $tzoffset);
			$item->publish_down = $date->toMySQL();
		}

		// save item
		if ($item->save()) {

			// save items category relations
			$table =& JTable::getInstance('categoryitem', 'Table');
			if ($table->saveCategoryItemRelations($item, $categories)) {
				$msg = JText::_('Item Saved');
			} else {
				JError::raiseNotice(0, JText::_('Error Saving Item').' ('.$table->getError().')');
				$this->_task = 'apply';
			}

		} else {
			JError::raiseNotice(0, JText::_('Error Saving Item').' ('.$item->getError().')');
			$this->_task = 'apply';
		}
		
		switch ($this->_task) {
			case 'apply':
				$link = 'index.php?option='.$option.'&controller='.$this->getName().
						'&view=item&task=edit&type_id='.$item->type_id.'&cid[]='.$item->id;
				break;
			case 'save':
			default:
				$link = 'index.php?option='.$option.'&controller='.$this->getName();
				break;
		}

		$this->setRedirect($link, $msg);
	}

	function remove() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$option = JRequest::getCmd('option');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a item to delete'));
		}
		
		foreach ($cid as $id) {
			$item = new Item($id);
			
			if ($item->delete()) {
				$msg = JText::_('Item Deleted');
			} else {
				JError::raiseWarning(0, JText::_('Error Deleting Item').' ('.$item->getError().')');
				break;
			}
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link, $msg);
	}

	function resethits() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$option = JRequest::getCmd('option');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$id     = intval($cid[0]);

		// get item
		$item = new Item($id);

		// reset hits
		if ($item->hits > 0) {
			$item->hits = 0;

			// save item
			if ($item->save()) {
				$msg = JText::_('Item Hits Reseted');
			} else {
				JError::raiseNotice(0, JText::_('Error Reset Item Hits').' ('.$item->getError().')');
			}
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&view=item&task=edit&cid[]='.$item->id;
		$this->setRedirect($link, $msg);
	}

	function aliasexists() {

		// init vars
		$id    = JRequest::getInt('id', 0);
		$alias = JFilterOutput::stringURLSafe(JRequest::getString('alias', ''));
		
		if ($alias == '' || preg_match('/[^A-Za-z0-9_-]/', $alias)) {
			echo $alias.';'.JText::_('Invalid alias');
			return;
		}

		if (ItemHelper::checkAliasExists($alias, $id)) {
			echo $alias.';'.JText::_('Alias already exists, please choose a unique alias');
			return;
		}
		
		echo $alias.';Ok';
	}

	function publish() {
		$this->_editState(1);
	}

	function unpublish() {
		$this->_editState(0);
	}

	function accessPublic() {
		$this->_editAccess(0);
	}

	function accessRegistered() {
		$this->_editAccess(1);
	}

	function accessSpecial() {
		$this->_editAccess(2);
	}

	function _editAccess($access) {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a item to edit access'));
		}
		
		foreach ($cid as $id) {
			$item = new Item($id);
			$item->access = $access;

			if (!$item->save()) {
				JError::raiseWarning(0, JText::_('Error editing item access').' ('.$item->getError().')');
				break;
			}
		}
		
		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link);
	}

	function _editState($state) {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a item to edit publish state'));
		}
		
		foreach ($cid as $id) {
			$item = new Item($id);
			$item->state = $state;
			
			if (!$item->save()) {
				JError::raiseWarning(0, JText::_('Error editing item published state').' ('.$item->getError().')');
				break;
			}
		}
		
		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link);
	}

}