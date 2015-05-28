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

class YOOThumbnail {

	var $img_file;
	var $img_format;
	var $img_source;
	var $img_width;
	var $img_height;
	var $thumb_width;
	var $thumb_height;
	var $thumb_resize;
	var $thumb_quality;

    function YOOThumbnail($file) {
        $this->img_file      = $file;
        $this->thumb_resize  = true;
        $this->thumb_quality = 90;

		// get image info
		list($width, $height, $type, $attr) = @getimagesize($this->img_file, $info);

		// set image dimensions and type
		if (is_array($info)) {

	        $this->img_width    = $width;
	        $this->img_height   = $height;
	        $this->thumb_width  = $width;
	        $this->thumb_height = $height;
	
			switch ($type) {
	            case 1:
	                $this->img_format = 'gif';
	            	$this->img_source = imagecreatefromgif($this->img_file);
	                break;
	            case 2:
                	$this->img_format = 'jpeg';
	            	$this->img_source = imagecreatefromjpeg($this->img_file);
	                break;
	            case 3:
            		$this->img_format = 'png';
	            	$this->img_source = imagecreatefrompng($this->img_file);
	                break;
	            default:
	                $this->img_format = null;
	            	$this->img_source = null;
	                break;
	        } 
		}		
    } 

    function setResize($resize) {
		$this->thumb_resize = $resize;
    } 

    function setSize($width, $height) {
		$this->thumb_width  = $width;
		$this->thumb_height = $height;
    } 

    function sizeWidth($width) { 
		$this->thumb_width  = $width;
		$this->thumb_height = @($width / $this->img_width) * $this->img_height;
    } 

    function sizeHeight($height) {
		$this->thumb_width  = @($height / $this->img_height) * $this->img_width;
		$this->thumb_height = $height;
    } 

    function save($file) { 
		$return = false;
		
		if ($this->img_format) {

			$src   = $this->img_source;
			$src_x = 0;
			$src_y = 0;

	        // smart resize thumbnail image
			if ($this->thumb_resize) {
				$resized_width  = @($this->thumb_height / $this->img_height) * $this->img_width;
				$resized_height = @($this->thumb_width / $this->img_width) * $this->img_height;
				
				if ($this->thumb_width <= $resized_width) {
					$width  = $resized_width;
					$height = $this->thumb_height;
					$src_x  = intval(($resized_width - $this->thumb_width) / 2);
				} else {
					$width  = $this->thumb_width;
					$height = $resized_height;
					$src_y  = intval(($resized_height - $this->thumb_height) / 2);
				}

				$src = imagecreatetruecolor($width, $height);

				if (function_exists('imagecopyresampled')) {
					@imagecopyresampled($src, $this->img_source, 0, 0, 0, 0, $width, $height, $this->img_width, $this->img_height);
				} else {
					@imagecopyresized($src, $this->img_source, 0, 0, 0, 0, $width, $height, $this->img_width, $this->img_height);
				}
			}

	        // create thumbnail image
			$thumbnail = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
			@imagecopy($thumbnail, $src, 0, 0, $src_x, $src_y, $this->thumb_width, $this->thumb_height);

			// save thumbnail to file
			switch ($this->img_format) {
	            case 'gif':
	            	$return = imagegif($thumbnail, $file);
	                break;
	            case 'jpeg':
	       			$return = imagejpeg($thumbnail, $file, $this->thumb_quality);
	                break;
	            case 'png':
	    			$return = imagepng($thumbnail, $file);
					break;
	        }

			// free memory resources
			imagedestroy($thumbnail);	 
			imagedestroy($src);
		}

		return $return;
    } 

    function check() {
		$gd_functions = array(
			'getimagesize',
			'imagecreatefromgif',
			'imagecreatefromjpeg',
			'imagecreatefrompng',
			'imagecreatetruecolor',
			'imagecopyresized',
			'imagecopy',
			'imagegif',
			'imagejpeg',
			'imagepng'
			);
		
		foreach ($gd_functions as $name) {
			if (!function_exists($name)) return false;
		}
		
		return true;
    } 

} 

?>