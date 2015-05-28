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

// register parent class
JLoader::register('ElementFile', ZOO_ADMIN_PATH.'/elements/file/file.php');

/*
	Class: ElementImage
		The image element class
*/
class ElementImage extends ElementFile {

	/*
	   Function: Constructor
	*/
	function ElementImage() {
		
		// call parent constructor
		parent::ElementFile();
		
		// init vars
		$this->type  = 'image';
		
		// set defaults
		$params =& JComponentHelper::getParams('com_media');
		$this->directory = $params->get('image_path');
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
		$title  = $params->get('title', $this->_item->name);		
		$width  = $params->get('width', 0);		
		$height = $params->get('height', 0);		
		$link   = trim($this->directory, '/').'/'.$this->file;

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('file' => $this->file, 'title' => $title, 'link' => $link, 'width' => $width, 'height' => $height));
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

		// get params
		$params =& JComponentHelper::getParams('com_media');
		$img_ext = str_replace(',', '|', trim($params->get('image_extensions'), ','));

		// init vars
		$params =& $this->getParams();
		$title  = htmlspecialchars(html_entity_decode($params->get('title'), ENT_QUOTES), ENT_QUOTES);
		$width  = $params->get('width', 0);		
		$height = $params->get('height', 0);		
		$dir    = trim($this->directory, '/').'/';
		
		// image preview
		$javascript = "var img = new ElementImage('".$this->name."_file', '".$this->name."_preview', '".JURI::root().$dir."'); img.attachEvents();";
		$preview[]  = '<div id="'.$this->name.'_preview" class="image-preview"><img src="'.JURI::root().$dir.$this->file.'" /></div>';
		$preview[]  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";

		// create info
		$info[] = JText::_('Width').': '.$width;
		$info[] = JText::_('Height').': '.$height;
		$info   = ' ('.implode(', ', $info).')';
		
		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('File'), JHTML::_('control.selectfile', JPATH_ROOT.DS.$this->directory, '/^.*('.$img_ext.')$/i', $this->name.'_file', $this->file).$info);
		$html .= JHTML::_('element.editrow', JText::_('Title'), JHTML::_('control.text', $this->name.'_params[title]', $title, 'maxlength="255"'));
		$html .= JHTML::_('element.editrow', '', implode("\n", $preview));
		$html .= '</table>';

		return $html;
	}

	/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	function loadAssets() {
		JHTML::script('image.js', 'administrator/components/com_zoo/elements/image/');
		JHTML::stylesheet('image.css', 'administrator/components/com_zoo/elements/image/');
	}
	
	/*
    	Function: bind
    	  Binds a named array/hash to this object.
		
		Parameters:
	      $data - An associative array or object.
	
	   Returns:
	      Void	
 	*/
	function bind($data) {
		
		parent::bind($data);

		// init vars
		$params   =& $this->getParams();
		$filepath = rtrim(JPATH_ROOT.DS.$this->directory, '/').'/'.$this->file;

		// set image width/height
		if (is_file($filepath) && $size = getimagesize($filepath)) {
			$params->set('width', $size[0]);
			$params->set('height', $size[1]);
		} else {
			$params->set('width', 0);
			$params->set('height', 0);
		}

		$this->params = $params->toString();
	}

}