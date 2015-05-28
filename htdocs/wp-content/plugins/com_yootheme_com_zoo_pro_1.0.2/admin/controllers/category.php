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
	Class: CategoryController
		The controller class for category
*/
class CategoryController extends YController {

	function __construct($default = array()) {
		parent::__construct($default);

		$this->registerTask('apply', 'save');
		$this->registerTask('preview', 'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('add', 'display');
		$this->registerTask('move', 'display');

		// clean component cache
		parent::cleanCacheOnTask('com_zoo', array('save', 'apply', 'remove', 'domove', 'orderup', 'orderdown', 'saveorder', 'publish', 'unpublish'));
	}

	function display() {

		switch($this->getTask()) {
			case 'move':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('view', 'categories');
				JRequest::setVar('layout', 'move');
				break;
			case 'add':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('view', 'category');
				JRequest::setVar('edit', false);
				break;
			case 'edit':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('view', 'category');
				JRequest::setVar('edit', true);
				break;
		}

		// set the default view
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'categories');
		}

		parent::display();
	}

	function save() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		// init vars
		$msg    = null;
		$option = JRequest::getCmd('option');
		$catid  = JRequest::getInt('catalog_id');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$id     = intval($cid[0]);

		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$category = new Category($id);
		$category->bind($post);

		// filter alias
		$category->alias = JFilterOutput::stringURLSafe($category->alias == '' ? $category->name : $category->alias);

		// save category
		if ($category->save()) {
			$msg = JText::_('Category Saved');

			// update category ordering
			$table =& JTable::getInstance('category', 'Table');
			if (!$table->updateorder($catid, $category->parent)) {
				JError::raiseNotice(0, JText::_('Error Saving Category').' ('.$table->getError().')');
				$this->_task = 'apply';
			}

		} else {
			JError::raiseNotice(0, JText::_('Error Saving Category').' ('.$category->getError().')');
			$this->_task = 'apply';
		}
		
		switch ($this->_task) {
			case 'apply':
				$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid.
						'&view=category&task=edit&cid[]='.$category->id;
				break;
			case 'save':
			default:
				$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
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
		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a Category to delete'));
		}

		// delete categories
		$parents = array();
		foreach ($cid as $id) {
			$category = new Category($id);
			if ($category->delete()) {
				$msg = JText::_('Category Deleted');
				$parents[] = $category->parent;
			} else {
				$msg = null;
				JError::raiseNotice(0, JText::sprintf('Error Deleting Category, %s', $category->name).' ('.$category->getError().')');
				break;
			}
		}

		// update category ordering
		$table =& JTable::getInstance('category', 'Table');
		if (!$table->updateorder($catid, $parents)) {
			JError::raiseNotice(0, JText::_('Error Deleting Category').' ('.$table->getError().')');
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
		$this->setRedirect($link, $msg);
	}

	function docopy() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$option = JRequest::getCmd('option');
		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');

		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a category to copy'));
		}
		
		// copy categories
		$parents = array();
		foreach ($cid as $id) {
			
			// get category
			$category = new Category($id);
			
			// copy category
			$category->id         = 0;                         // set id to 0, to force new category
			$category->name      .= ' ('.JText::_('Copy').')'; // set copied name
			$category->alias     .= '-copy';                   // set copied alias			
			$category->published  = 0;                         // unpublish category

			// track parent for ordering update
			$parents[] = $category->parent;

			// save copied category data
			if (!$category->save()) {
				JError::raiseNotice(0, JText::_('Error Copying Category').' ('.$category->getError().')');
				break;
			}
		}

		// update category ordering
		$table =& JTable::getInstance('category', 'Table');
		if (!$table->updateorder($catid, $parents)) {
			JError::raiseNotice(0, JText::_('Error Copying Category').' ('.$table->getError().')');
		}

		if (!$category->getError()) {
			$msg = JText::_('Category Copied');
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
		$this->setRedirect($link, $msg);
	}

	function domove() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$table  =& JTable::getInstance('category', 'Table');
		$option = JRequest::getCmd('option');
		$catid  = JRequest::getInt('catalog_id');
		$parent = JRequest::getString('parent');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		$model  = $this->getModel('categories');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, JText::_('Select a category to move'));
		}

		// parse target catalog/category
		if (strpos($parent, ':')) {
			list($catalog_id, $category_id) = explode(':', $parent, 2);
		} else {
			JError::raiseError(500, JText::_('Select a parent category'));
		}
				
		// move categories
		$categories   =& $model->getCategories();
		$category_ids = array_keys($categories);
		foreach ($categories as $category) {
			if (!$table->moveCategory($category, $category_ids, $catalog_id, $category_id)) {
				JError::raiseNotice(0, JText::_('Error Moving Category').' ('.$table->getError().')');
				break;
			}
		}

		$msg  = JText::_('Category Moved');
		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catalog_id;
		$this->setRedirect($link, $msg);
	}

	function orderup() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
 		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$id     = intval($cid[0]);

		$category = new Category($id);
		$category->move(-1);

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
		$this->setRedirect($link);
	}

	function orderdown() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
 		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$id     = intval($cid[0]);

		$category = new Category($id);
		$category->move(1);

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
		$this->setRedirect($link);
	}

	function saveorder() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
 		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		$order  = JRequest::getVar('order', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		// save new ordering
		$table =& JTable::getInstance('category', 'Table');
		$table->saveorder($catid, $cid, $order);

		$msg  = JText::_('New ordering saved');
		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
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

		if (CategoryHelper::checkAliasExists($alias, $id)) {
			echo $alias.';'.JText::_('Alias already exists, please choose a unique alias');
			return;
		}
		
		echo $alias.';Ok';
	}

	function publish() {
		$this->_editPublished(1, JText::_('Select a category to publish'));
	}

	function unpublish() {
		$this->_editPublished(0, JText::_('Select a category to unpublish'));
	}

	function _editPublished($published, $msg) {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$option = JRequest::getCmd('option');
 		$catid  = JRequest::getInt('catalog_id');
		$cid    = JRequest::getVar('cid', array(), 'post', 'array');
		
		JArrayHelper::toInteger($cid);

		if (count($cid) < 1) {
			JError::raiseError(500, $msg);
		}

		foreach ($cid as $id) {
			$category = new Category($id);
			$category->setPublished($published);
			if (!$category->save()) {
				echo "<script> alert('".$category->getError(true)."'); window.history.go(-1); </script>\n";
				break;
			}
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName().'&catalog_id='.$catid;
		$this->setRedirect($link);
	}

}