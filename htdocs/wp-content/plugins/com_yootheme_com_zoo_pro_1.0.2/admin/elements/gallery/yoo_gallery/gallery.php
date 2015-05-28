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

jimport('joomla.filesystem.file');

if (!class_exists('YOOThumbnail')) {
	require_once(dirname(__FILE__).'/lib/thumbnail.php');
}

class YOOGallery {
    
	// gallery params
	var $params;

	// gallery path
	var $path;
    
	// gallery uri
	var $uri;

	// joomla root
	var $jroot;

	// joomla uri
	var $juri;

	function YOOGallery($params) {
		$this->params = $params;
		$this->jroot  = $params->get('cfg_jroot');
		$this->juri   = $params->get('cfg_juri');
		$this->path   = $params->get('cfg_path');
		$this->uri    = $this->juri.$this->path;
	}

	function render() {
			
			// init vars
			$src           = '/'.trim(trim($this->params->get('src', '')), '/').'/';
			$title         = $this->params->get('title', '');
			$style         = $this->params->get('style', 'lightbox');
			$effect        = $this->params->get('effect', 'fade');
			$thumb_style   = $this->params->get('thumb', 'default');
			$order         = $this->params->get('order', 'asc');
			$width         = $this->params->get('width');
			$height        = $this->params->get('height');
			$resize        = $this->params->get('resize', 1);
			$count         = $this->params->get('count', 0);
			$spotlight     = $this->params->get('spotlight', 0);
			$prefix        = $this->params->get('prefix', 'thumb_');
			$cache_dir     = $this->params->get('thumb_cache_dir', 'thumbs');
			$cache_time    = $this->params->get('thumb_cache_time', 1440) * 60;
			$rel           = $this->params->get('rel', '');
			$load_lightbox = $this->params->get('load_lightbox', 1);
			$tmpl_style    = dirname(__FILE__).'/tmpl/'.$style.'.php';
			$tmpl_thumb    = dirname(__FILE__).'/tmpl/_thumbnail_'.$thumb_style.'.php'; 

			// check gd image processing library
			if (!YOOThumbnail::check()) {
				return $this->getAlertMessage("PHP GD image processing library is not installed (<a href=\"http://www.php.net/gd\" target=\"_blank\">http://www.php.net/gd</a>)");
			}

			// check thumbnail template files
			if (!is_readable($tmpl_style) || !is_readable($tmpl_thumb)) {
				return $this->getAlertMessage("Unable to read the thumbnail style template files");
			}

			// check source if directory exists
			if (!is_dir($this->jroot.$src)) {
				return $this->getAlertMessage("Unable to find the source directory (".$this->jroot.$src."), please check if your source directory exists");
			}

			// check source if directory is readable/writable
			if (!is_writable($this->jroot.$src)) {
				return $this->getAlertMessage("Unable to read/write the source directory (".$this->jroot.$src."), please verify the directory permissions (<a href=\"http://tutorials.yootheme.com/index.php?option=com_mtree&task=viewlink&link_id=122&Itemid=2\" target=\"_blank\">Go to: Fix permissions tutorial</a>)");
			}

			// set thumbnail cache directory
			if (!$this->getCacheDirectory($this->jroot.$src.$cache_dir.'/')) {
				return $this->getAlertMessage("Unable to read/write the thumbnail cache directory (".$this->jroot.$src.$cache_dir.'/'."), please verify the directory permissions (<a href=\"http://tutorials.yootheme.com/index.php?option=com_mtree&task=viewlink&link_id=122&Itemid=2\" target=\"_blank\">Go to: Fix permissions tutorial</a>)");
			}

			// get thumbnails
			$thumbs = $this->getThumbnails($title, $src, $width, $height, $resize, $prefix, $cache_dir, $cache_time);
			
			// no thumbnails found
			if (!count($thumbs)) {
				return $this->getAlertMessage("No thumbnails found");
			}

			// sort thumbnails
			$this->sortThumbnails($thumbs, $order);

			// limit thumbnails to count
			$count = intval($count);
			if ($count > 0 && $count < count($thumbs)) {
				$thumbs = array_slice($thumbs, 0, $count);
			}

			// add css
			if ($this->includeOnce('YOO_GALLERY_CSS')) {
				$document =& JFactory::getDocument();
				$document->addStyleSheet($this->uri.'gallery.css.php');
			}

			// init template vars
		   	static $gallery_count = 1;
			$gallery_id = 'yoo-gallery-'.$gallery_count++;

			// get template output
			$html = '';
			ob_start();
			include($tmpl_style);
			$html = ob_get_contents();
			ob_end_clean();		
		
			return $html;
	}

	function getThumbnails($title, $path, $width, $height, $resize, $prefix, $cache_dir, $cache_time) {
		$thumbs = array();
		$files  = $this->getFiles($this->jroot.$path);

		// set default thumbnail size, if incorrect sizes defined
		$width  = intval($width);
		$height = intval($height);
		if ($width < 1 && $height < 1) {
			$width  = 100;
			$height = null;
		}
		
		foreach ($files as $file) {
			$filename = basename($file);
			$thumb    = $this->jroot.$path.$cache_dir.'/'.$prefix.$filename;
			
			// create or re-cache thumbnail
			if (!is_file($thumb) || ($cache_time > 0 && time() > (filemtime($thumb) + $cache_time))) {
				$thumbnail = new YOOThumbnail($file);
				
				// set thumbnail size
				if ($width && $height) {
					$thumbnail->setSize($width, $height);
				} elseif ($height) {
					$thumbnail->sizeHeight($height);
				} else {
					$thumbnail->sizeWidth($width);
				}
				
				$thumbnail->setResize($resize);
				$thumbnail->save($thumb);
			} 

			// if thumbnail exists, add it to return value
			if (is_file($thumb)) {

				// set image name or title if exsist
				if ($title != '') {
					$name = $title;
				} else {
					$name = JFile::stripExt($filename);
					$name = JString::str_ireplace('_', ' ', $name);
					$name = JString::ucwords($name);
				}
				
				// get image info
				list($thumb_width, $thumb_height) = @getimagesize($thumb);
				
				$thumbs[] = array(
					'name'         => $name,
					'filename'     => $filename,
					'img'          => $this->juri.ltrim($path, '/').$filename,
					'img_file'     => $file,
					'thumb'        => $this->juri.ltrim($path, '/').$cache_dir.'/'.$prefix.$filename,
					'thumb_width'  => $thumb_width,
					'thumb_height' => $thumb_height
				);
			}
		}
		
		return $thumbs;
	}

	function sortThumbnails(&$thumbs, $order) {
		usort($thumbs, array('YOOGallery', 'compareThumbnails'));
		if ($order == 'random') shuffle($thumbs);
		if ($order == 'desc')   $thumbs =& array_reverse($thumbs);
	}

	function compareThumbnails($a, $b) {
	    return strcmp($a['filename'], $b['filename']);
	}
	
	function getFiles($path) {
		$path   = rtrim($path, '/');
		$files  = array();
	    $ignore = array('cgi-bin', '.', '..','.svn','.DS_Store');
		$dh     = @opendir($path);

	    while (false !== $file = readdir($dh)) {
	        if (!in_array($file, $ignore) && is_file($path.'/'.$file)) {
				$files[] = $path.'/'.$file;
	        }
	    }

	    closedir($dh);
		return $files;
	}

	function getCacheDirectory($path) {
		$path = rtrim($path, '/');

		if (!is_dir($path)) {
			@mkdir($path);
		}
		
		return is_dir($path);
	}

	function getAlertMessage($msg) {
		return "<div class=\"alert\"><strong>".$msg."</strong></div>\n";
	}

	function includeOnce($name) {
		if (!defined($name)) {
			define($name, true);
			return true;
		}
		
		return false;
	}

}