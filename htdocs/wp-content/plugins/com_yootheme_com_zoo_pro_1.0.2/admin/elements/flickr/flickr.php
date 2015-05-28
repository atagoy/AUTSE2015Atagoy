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
JLoader::register('ElementSimpleParams', ZOO_ADMIN_PATH.'/elements/simpleparams/simpleparams.php');

/*
   Class: ElementFlickr
       The Flickr element class (http://www.flickr.com)
*/
class ElementFlickr extends ElementSimpleParams {

	/** @var string */
	var $width;
	/** @var string */
	var $height;
	/*
	   Function: Constructor
	*/
	function ElementFlickr() {

		// call parent constructor
		parent::ElementSimpleParams();
		
		// init vars
		$this->type    = 'flickr';
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

		// init vars
		$params   =& $this->getParams();
		$tags     = $this->value;
		$flickrid = $params->get('flickrid', '');		

		// render html
		if ($this->width && $this->height && ($tags || $flickrid)) {

			$vars = array();
			
			if ($flickrid) {
				$vars[] = 'user_id='.$flickrid;
			}
			
			if ($tags) {
				$vars[] = 'tags='.$tags;
			}
		
			$html  = '<iframe src="http://www.flickr.com/slideShow/index.gne?'.implode('&', $vars). '"';
			$html .= ' align="middle" frameborder="0" height="'. $this->height .'"';
			$html .= ' scrolling="no" width="'. $this->width .'"';
			$html .= '></iframe>'	;
			
			return $html;
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

		// init vars
		$params   =& $this->getParams();
		$flickrid = $params->get('flickrid', '');		

		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Tags'), JHTML::_('control.text', $this->name.'_value', $this->value, 'maxlength="255"'));
		$html .= JHTML::_('element.editrow', JText::_('Flickr ID'), JHTML::_('control.text', $this->name.'_params[flickrid]', $flickrid, 'maxlength="255"'));
		$html .= '</table>';
		
		return $html;
	}

}