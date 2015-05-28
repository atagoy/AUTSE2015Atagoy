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

// register yoo gallery class
JLoader::register('YOOGallery', ZOO_ADMIN_PATH.'/elements/gallery/yoo_gallery/gallery.php');

/*
	Class: ElementGallery
		The file element class
*/
class ElementGallery extends ElementSimpleParams {

	/** @var string */
	var $style;
	/** @var string */
	var $effect;
	/** @var string */
	var $thumb;
	/** @var string */
	var $order;
	/** @var string */
	var $spotlight;
	/** @var string */
	var $width;
	/** @var string */
	var $height;
	/** @var string */
	var $resize;
	/** @var string */
	var $count;
	/** @var string */
	var $prefix;
	/** @var string */
	var $thumb_cache_dir;
	/** @var string */
	var $thumb_cache_time;
	/** @var string */
	var $load_lightbox;
	/** @var string */
	var $rel;

	/*
	   Function: Constructor
	*/
	function ElementGallery() {

		// call parent constructor
		parent::ElementSimpleParams();
		
		// init vars
		$this->type  = 'gallery';
		$this->_table_columns = array('_value' => 'TEXT', '_params' => 'TEXT');
	}

	/*
		Function: hasValue
			Checks if the element's value is set.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		
		// init vars
		$directory = JPATH_ROOT.DS.trim($this->value, '/');				
		
		return !empty($this->value) && is_readable($directory) && is_dir($directory);
	}

	/*
		Function: render
			Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		// init vars
		$params =& $this->getParams();
		$title  = $params->get('title');		

		// set gallery params
		$gallery_params = $this->configParams();
		$gallery_params->set('cfg_path', 'administrator/components/com_zoo/elements/gallery/yoo_gallery/');
		$gallery_params->set('cfg_juri', JURI::base());
		$gallery_params->set('cfg_jroot', JPATH_ROOT);
		$gallery_params->set('src', $this->value);
		$gallery_params->set('title', $title);

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('params' => $gallery_params));
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
		$params =& $this->getParams();
		$title  = htmlspecialchars(html_entity_decode($params->get('title'), ENT_QUOTES), ENT_QUOTES);

		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Directory'), JHTML::_('control.text', $this->name.'_value', $this->value, 'maxlength="255"'));
		$html .= JHTML::_('element.editrow', JText::_('Thumbnail Title'), JHTML::_('control.text', $this->name.'_params[title]', $title, 'maxlength="255"'));
		$html .= '</table>';

		return $html;
	}

}