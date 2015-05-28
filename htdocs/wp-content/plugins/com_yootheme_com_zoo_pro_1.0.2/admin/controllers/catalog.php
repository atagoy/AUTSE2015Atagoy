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
	Class: CatalogController
		The controller class for catalog
*/
class CatalogController extends YController {

	function __construct($default = array()) {
		parent::__construct($default);

		$this->registerTask('apply', 'save');
		$this->registerTask('unpublish', 'publish');
		$this->registerTask('preview', 'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('add', 'display');

		// clean component cache
		parent::cleanCacheOnTask('com_zoo', array('save', 'apply', 'remove'));
	}

	function display() {

		switch($this->getTask()) {
			case 'add':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('view', 'catalog');
				JRequest::setVar('edit', false);
				break;
			case 'edit':
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('view', 'catalog');
				JRequest::setVar('edit', true);
			break;
		}

		// set the default view
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'catalogs');
		}

		parent::display();
	}

	function save() {

		// check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		// init vars
		$msg    = null;
		$option = JRequest::getCmd('option');
		$post   = JRequest::get('post');
		$cid    = JRequest::getVar('cid', array(0), 'post', 'array');
		$id     = intval($cid[0]);

		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$catalog =& Catalog::getInstance($id);
		$catalog->bind($post);

		// filter alias
		$catalog->alias = YFilterOutput::stringDBSafe($catalog->alias == '' ? $catalog->name : $catalog->alias);

		// save catalog
		if ($catalog->save()) {
			$msg = JText::_('Catalog Saved');
		} else {
			JError::raiseNotice(0, JText::_('Error Saving Catalog').' ('.$catalog->getError().')');
			$this->_task = 'apply';
		}

		switch ($this->_task) {
			case 'apply':
				$link = 'index.php?option='.$option.'&controller='.$this->getName().
						'&view=catalog&task=edit&cid[]='.$catalog->id;
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
			JError::raiseError(500, JText::_('Select an catalog to delete'));
		}

		foreach ($cid as $id) {
			$catalog =& Catalog::getInstance($id);
			
			if ($catalog->delete()) {
				$msg = JText::_('Catalog Deleted');
			} else {
				$msg = null;
				JError::raiseNotice(0, JText::sprintf('Error Deleting Catalog, %s', $catalog->name).' ('.$catalog->getError().')');
				break;
			}
		}

		$link = 'index.php?option='.$option.'&controller='.$this->getName();
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

		if (CatalogHelper::checkAliasExists($alias, $id)) {
			echo $alias.';'.JText::_('Alias already exists, please choose a unique alias');
			return;
		}
		
		echo $alias.';Ok';
	}

}