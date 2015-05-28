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

// register parent class
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
   Class: ElementIntensedebate
       The Intensedebate element class (http://www.intensedebate.com)
*/
class ElementIntensedebate extends ElementSimple {

	/** @var string */
	var $account;

	/*
	   Function: Constructor
	*/
	function ElementIntensedebate() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'intensedebate';
		$this->display = 1;
	}

	/*
		Function: render
			Override. Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		// render html
		if ($this->account && $this->value) {
			$html[] = "<script type='text/javascript'>";
			$html[] = "var idcomments_acct = '".$this->account."';";
			$html[] = "var idcomments_post_id = 'zoo-".$this->_item->id."';";
			$html[] = "var idcomments_post_url;";
			$html[] = "</script>";
			$html[] = '<span id="IDCommentsPostTitle" style="display:none"></span>';
			$html[] = "<script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>";
			return implode("\n", $html);
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {

		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$this->value = 1;
		}

		return JHTML::_('select.booleanlist', $this->name.'_value', '', $this->value);
	}

}