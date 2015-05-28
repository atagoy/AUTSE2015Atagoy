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
   Class: ElementAddthis
       The Addthis element class (http://www.addthis.com)
*/
class ElementAddthis extends ElementSimple {

	/** @var string */
	var $account;

	/*
	   Function: Constructor
	*/
	function ElementAddthis() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'addthis';
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
			$html[] = "<script type=\"text/javascript\">var addthis_pub=\"".$this->account."\";</script>";
			$html[] = "<a href=\"http://www.addthis.com/bookmark.php?v=20\" onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]')\" onmouseout=\"addthis_close()\" onclick=\"return addthis_sendto()\">";
			$html[] = "<img src=\"http://s7.addthis.com/static/btn/lg-bookmark-en.gif\" width=\"125\" height=\"16\" alt=\"Bookmark and Share\" style=\"border:0\"/>";
			$html[] = "</a>";
			$html[] = "<script type=\"text/javascript\" src=\"http://s7.addthis.com/js/200/addthis_widget.js\"></script>";
			return implode("\n", $html);
		}

		return '';
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