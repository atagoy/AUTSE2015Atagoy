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
   Class: ElementUservoice
       The Uservoice element class (http://www.uservoice.com)
*/
class ElementUservoice extends ElementSimple {

	/** @var string */
	var $account;
	/** @var string */
	var $forumurl;

	/*
	   Function: Constructor
	*/
	function ElementUservoice() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'uservoice';
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
		if ($this->account && $this->forumurl && $this->value) {
			$html[] = "<script type=\"text/javascript\">;";
			$html[] = "var uservoiceJsHost = (\"https:\" == document.location.protocol) ? \"https://uservoice.com\" : \"http://cdn.uservoice.com\";";
			$html[] = "document.write(unescape(\"%3Cscript src='\" + uservoiceJsHost + \"/javascripts/widgets/tab.js' type='text/javascript'%3E%3C/script%3E\"))";
			$html[] = "</script>";
			$html[] = "<script type=\"text/javascript\">";
			$html[] = "UserVoice.Tab.show({";
			$html[] = "key: '".$this->account."',";
			$html[] = "host: '".basename($this->forumurl, '.uservoice.com').".uservoice.com',";
			$html[] = "forum: 'general',";
			$html[] = "alignment: 'left', /* 'left', 'right' */";
			$html[] = "background_color:'#f00',";
			$html[] = "text_color: 'white', /* 'white', 'black' */";
			$html[] = "hover_color: '#06C',";
			$html[] = "lang: 'en' /* 'en', 'de', 'nl', 'es', 'fr' */";
			$html[] = "});";
			$html[] = "</script>";
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