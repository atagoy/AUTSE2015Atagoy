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
JLoader::register('ElementFile', ZOO_ADMIN_PATH.'/elements/file/file.php');

/*
	Class: ElementDownload
		The file download element class
*/
class ElementDownload extends ElementFile {

	/** @var string */
	var $download_name;
	/** @var int */
	var $download_mode;
	/** @var int */
	var $download_limit;
	/** @var string */
	var $secret;

	/*
	   Function: Constructor
	*/
	function ElementDownload() {

		// call parent constructor
		parent::ElementFile();
		
		// init vars
		$this->type  = 'download';

		// set defaults
		$params               =& JComponentHelper::getParams('com_media');
		$this->directory      = $params->get('file_path');
		$this->download_name  = JText::_('Download').' {filename}';
		$this->download_mode  = 0;
		$this->download_limit = 0;
		$this->secret         = substr(md5(rand()), 0, 8);

		// set callbacks
		$this->registerCallback('download');
	}

	/*
		Function: getHits
			Gets the download hit count.

		Returns:
			Int - Download hit count
	*/
	function getHits() {

		// init vars
		$params =& $this->getParams();

		return $params->get('hits', 0);
	}

	/*
		Function: getSize
			Gets the download file size.

		Returns:
			String - Download file with KB/MB suffix
	*/
	function getSize() {

		// init vars
		$params =& $this->getParams();

		return YFile::formatFilesize($params->get('size', 0));
	}

	/*
	   Function: getExtension
	       Get the file extension string.

	   Returns:
	       String - file extension
	*/
	function getExtension() {

		if ($this->hasValue()) {
			return YFile::getExtension($this->file);
		}

		return null;
	}

	/*
		Function: getLink
			Gets the link to the download.

		Returns:
			String - link
	*/
	function getLink() {
		
		// init vars
		$filename      = basename($this->file);
		$download_name = JString::str_ireplace('{filename}', $filename, $this->download_name);

		// create download link
		$link = 'index.php?option=com_zoo&view=element&task=callelement&format=raw&item_id='.$this->_item->id;
		if ($this->download_mode == 1) {
			$download_link = $link.'&element='.$this->name.'&method=download';
		} else if ($this->download_mode == 2) {
			$download_link = $link.'&element='.$this->name.'&method=download&args[0]='.$this->filecheck();
		} else {
			$download_link = trim($this->directory, '/').'/'.$this->file;
		}
		
		return $download_link;
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
		$params        =& $this->getParams();
		$size          = $params->get('size', 0);
		$hits          = $params->get('hits', 0);
		$filename      = basename($this->file);
		$download_name = JString::str_ireplace('{filename}', $filename, $this->download_name);
		$download_link = $this->getLink();

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('file' => $this->file, 'filename' => $filename, 'size' => $size, 'hits' => $hits, 'download_name' => $download_name, 'download_link' => $download_link));
		}
		
		return null;
	}

	/*
		Function: download
			Download the file.

		Returns:
			Binary - File data
	*/
	function download($check = '') {
		
		// init vars
		$filepath = rtrim(JPATH_ROOT.DS.$this->directory, '/').'/'.$this->file;

		// check limit
		$limit = (int) $this->download_limit;
		if ($limit && $this->getHits() > $limit) {
			header('Content-Type: text/html');
			echo JText::_('Download limit reached!');
			return;
		}

		// output file
		if ($this->download_mode == 1 && is_readable($filepath) && is_file($filepath)) {
			$this->hit();
			YFile::output($filepath);
		} else if ($this->download_mode == 2 && $this->filecheck() == $check && is_readable($filepath) && is_file($filepath)) {
			$this->hit();
			YFile::output($filepath);
		} else {
			header('Content-Type: text/html');
			echo JText::_('Invalid file!');
		}
	}

	/*
	   Function: filecheck
	       Get the file check string.

	   Returns:
	       String - md5(file + secret + date) 
	*/
	function filecheck() {
		return md5($this->file.$this->secret.date('Y-m-d'));
	}

	/*
		Function: hit
			Count download hits.

		Returns:
			Boolean - true on success
	*/
	function hit() {

		// init vars
		$params =& $this->getParams();
		$hits   = $params->get('hits', 0);

		// increment hits
		$params->set('hits', empty($hits) ? 1 : $hits + 1);

		$this->params = $params->toString();

		return $this->save();
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

		// create info
		$info[] = JText::_('Size').': '.YFile::formatFilesize($params->get('size', 0));
		$info[] = JText::_('Hits').': '.$params->get('hits', 0);
		$info   = ' ('.implode(', ', $info).')';

		return parent::edit().$info;
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

		// set file size
		if (is_readable($filepath) && is_file($filepath)) {
			$params->set('size', sprintf('%u', filesize($filepath)));
		} else {
			$params->set('size', 0);
		}

		$this->params = $params->toString();
	}

}