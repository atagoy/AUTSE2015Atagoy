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
   Class: ElementGetsatisfaction
       The Getsatisfaction element class (http://www.getsatisfaction.com)
*/
class ElementGetsatisfaction extends ElementSimple {

	/** @var string */
	var $company;

	/*
	   Function: Constructor
	*/
	function ElementGetsatisfaction() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'getsatisfaction';
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
		if ($this->company && $this->value) {
			$html[] = "<style type='text/css'>@import url('http://s3.amazonaws.com/getsatisfaction.com/feedback/feedback.css');</style>";
			$html[] = "<script src='http://s3.amazonaws.com/getsatisfaction.com/feedback/feedback.js' type='text/javascript'></script>";
			$html[] = "<script type=\"text/javascript\" charset=\"utf-8\">";
			$html[] = "var tab_options = {}";
			$html[] = "tab_options.placement = \"left\";  // left, right, bottom, hidden";
			$html[] = "tab_options.color = \"#222\"; // hex (#FF0000) or color (red)";
			$html[] = "GSFN.feedback('http://getsatisfaction.com/".$this->company."/feedback/topics/new?display=overlay&style=idea', tab_options);";
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