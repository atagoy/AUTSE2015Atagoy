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

/*
	Class: TypeController
		The controller class for type
*/
class TypeController extends YController {

	function __construct($default = array()) {
		parent::__construct($default);

		$this->registerTask('add', 'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('apply', 'save');
		$this->registerTask('editelements', 'display');
		$this->registerTask('saveelements', 'saveelements');
		$this->registerTask('applyelements', 'saveelements');

		// clean component cache
		parent::cleanCacheOnTask('com_zoo', array('save', 'apply', 'remove', 'saveelements', 'applyelements'));
	}

	function display() {

		// set view
		$task  = $this->getTask();
		$tasks = array('add', 'edit', 'editelements', 'addelement', 'callelement');
		if (in_array($task, $tasks)) {
			JRequest::setVar('hidemainmenu', 1);
			JRequest::setVar('view', 'type');

			// set layout
			if ($task == 'add') {
				JRequest::setVar('layout', 'edit');
			} else {
				JRequest::setVar('layout', $task);
			}
		}

		// set default view
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'types');
		}

		parent::display();
	}

	function docopy() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$error  = false;		
		$user   =& JFactory::getUser();
		$table  =& JTable::getInstance('categoryitem', 'Table');
		$option = JRequest::getCmd('option');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$helper = new TypeHelper();

		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a type to copy'));
		}
		
		// copy types
		foreach ($cid as $id) {
			
			// get type
			$type = new Type($id);

			// copy type
			$type->id     = 0;                         // set id to 0, to force new item
			$type->name  .= ' ('.JText::_('Copy').')'; // set copied name
			$type->alias .= '_copy';                   // set copied alias

			// save copied type data
			if (!$type->save()) {
				$msg   = $type->getError();
			    $error = true;				
				break;
			}
			
			// create type table from alias
			if (!$helper->createTable($type->alias)) {
				$msg   = $helper->getError();
			    $error = true;				
				break;
			}
			
			// add elements
			foreach ($type->getElements() as $name => $element) {
				
				$data = get_object_vars($element);

				// check element data
				if (!$element->configCheck($data)) {
					$msg   = $element->getError();
				    $error = true;				
					break 2;
				}

				// create element data
				if (!$element->configAdd($data)) {
					$msg   = $element->getError();
				    $error = true;				
					break 2;
				}
			}
		}

		if (!$error) {
			$msg = JText::_('Type Copied');
		} else {
			JError::raiseNotice(0, JText::_('Error Copying Type').' ('.$msg.')');
			$msg = null;
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link, $msg);
	}

	function save() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$error  = false;
		$option = JRequest::getCmd('option');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');

		// load type
		$type   =& Type::getInstance(intval($cid[0]));
		$helper = new TypeHelper();

		// filter alias
		$post['alias'] = YFilterOutput::stringDBSafe($post['alias'] == '' ? $post['name'] : $post['alias']);

		// create or rename type table from alias
		if ($type->alias == '') {
			$error = !$helper->createTable($post['alias']);
		} else if ($type->alias != $post['alias']) {
			$error = !$helper->renameTable($type->alias, $post['alias']);
		}

		// bind post data and save type
		if (!$error) {
			$type->bind($post);
			$error = !$type->save();
		}

		if (!$error) {
			$msg = JText::_('Type Saved');
		} else {
			JError::raiseNotice(0, JText::_('Error Saving Type').' ('.$type->getError().$helper->getError().')');
			$this->_task = 'apply';
		}

		switch ($this->_task) {
			case 'apply':
				$link = 'index.php?option='.$option.'&controller='.$this->getName().
						'&view=type&task=edit&cid[]='.$type->id;
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
			JError::raiseError(500, JText::_('Select a type to delete'));
		}
		
		foreach ($cid as $id) {
			$type =& Type::getInstance($id);
			
			if ($type->delete()) {
				$msg = JText::_('Type Deleted');
			} else {
				JError::raiseNotice(0, JText::_('Error Deleting Type').' ('.$type->getError().')');
				break;
			}
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName();
		$this->setRedirect($link, $msg);
	}

	function saveElements() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$error  = false;
		$option = JRequest::getCmd('option');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$helper = new TypeHelper();
		
		// load type
		$type =& Type::getInstance(intval($cid[0]));

		// save elements
		$delete = false;
		if (!$helper->saveElements($post, $type, $delete)) {
			$msg   = $helper->getError();
			$error = true;
		}

		// save type
		if (!$error && !$type->save()) {
			$msg   = $type->getError();
			$error = true;
		}

		// reset related item search data
		if (!$error && $delete) {
			$table =& JTable::getInstance('item', 'Table');
			$items = $table->getByTypeId($type->id);

			if ($items === false) {
				$msg   = $table->getError();
				$error = true;
			}

			foreach ($items as $id => $item) {
				if (!$items[$id]->save()) {
					$msg   = $item->getError();
					$error = true;
				}
			}
		}

		if (!$error) {
			$msg = JText::_('Elements Saved');
		} else {
			JError::raiseNotice(0, JText::_('Error Saving Elements').' ('.$msg.')');
			$this->_task = 'applyelements';
			$msg = null;
		}

		switch ($this->_task) {
			case 'applyelements':
				$link = 'index.php?option='.$option.'&controller='.$this->getName().
						'&view=type&task=editelements&cid[]='.$type->id;
				break;
			case 'saveelements':
			default:
				$link = 'index.php?option='.$option.'&controller='.$this->getName();
				break;
		}

		$this->setRedirect($link, $msg);
	}

	function aliasexists() {

		// init vars
		$id    = JRequest::getInt('id', 0);
		$alias = YFilterOutput::stringDBSafe(JRequest::getString('alias', ''));
		
		if ($alias == '' || preg_match('/[^A-Za-z0-9_-]/', $alias)) {
			echo $alias.';'.JText::_('Invalid alias');
			return;
		}

		if (TypeHelper::checkAliasExists($alias, $id)) {
			echo $alias.';'.JText::_('Alias already exists, please choose a unique alias');
			return;
		}
		
		echo $alias.';Ok';
	}
	
}