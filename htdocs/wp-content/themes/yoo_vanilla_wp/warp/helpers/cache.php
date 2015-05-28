<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperCache
		Cache helper class, combines css/js files
*/
class WarpHelperCache extends WarpHelper {
    
	var $images = array();

	/*
		Function: processStylesheets
			Combine multiple stylesheets to a cache file

		Parameters:
			$stylesheets - Stylesheet files

		Returns:
			Mixed
	*/	
	function processStylesheets($stylesheets, $options = array()) {

		// init options
		$options = array_merge(array('data_uri' => false), $options);

		// disable data-uri option for IE < 8.0
		$useragent =& $this->getHelper('useragent');
		if ($useragent->browser() == 'msie' && version_compare($useragent->version(), '8.0', '<')) {
			$options['data_uri'] = false;
		}		
		
		// get cache
		$cache  = new WarpCacheStylesheets($options);
		$result = $cache->process($stylesheets);
		
		if ($result && $result['new']) {

			// compress cached stylesheet
			$compressor = $this->getHelper('stylecompressor');
			$content = $compressor->process(file_get_contents($result['file']));
			$content = $options['data_uri'] ? $this->processDataUris($content) : $content;
			file_put_contents($result['file'], $content);

			// copy gzip file
			$path =& $this->getHelper('path');
			if (!$path->path('cache:css.php')) {
				copy($path->path('warp:gzip/css.php'), rtrim($path->path('cache:'), '/').'/css.php');
			}
		}

		return $result ? $result['urls'] : null;
	}

	/*
		Function: processJavascripts
			Combine multiple javascripts to a cache file

		Parameters:
			$javascripts - Javascript files

		Returns:
			Mixed
	*/	
	function processJavascripts($javascripts, $options = array()) {

		// get cache
		$cache  = new WarpCacheJavascripts($options);
		$result = $cache->process($javascripts);
		
		if ($result && $result['new']) {
      
			// compress cached javascript
			$compressor = $this->getHelper('scriptcompressor');
			$content = $compressor->process(file_get_contents($result['file']));
			file_put_contents($result['file'], $content);

			// copy gzip file
			$path =& $this->getHelper('path');
			if (!$path->path('cache:js.php')) {
				copy($path->path('warp:gzip/js.php'), rtrim($path->path('cache:'), '/').'/js.php');
			}
		}
						
		return $result ? $result['urls'] : null;
	}

	/*
		Function: processDataUris
			Create a data-uri's for image files

		Parameters:
			$content - Stylesheet

		Returns:
			String
	*/	
	function processDataUris($content) {

		// check if image exists and filesize < 10kb
		foreach ($this->images as $uri => $path) {
			if ($path && file_exists($path) && filesize($path) <= 10240 && preg_match('/\.(gif|png|jpg)$/i', $path, $extension)) {
				$content = str_replace($uri, sprintf('url(data:image/%s;base64,%s)', str_replace('jpg', 'jpeg', strtolower($extension[1])), base64_encode(file_get_contents($path))), $content);
			}
		}

		return $content;
	}

}

class WarpCache extends WarpObject {

	/* warp */
	var $warp;

	/* lifetime */
	var $lifetime;

	/* file name */
	var $filename;

	/* gzip name */
	var $gzipname;
	
	/* options */
	var $options;

	function __construct($options = array()) {

		// init vars
		$this->warp     =& Warp::getInstance();
		$this->lifetime = $this->warp->system->cache_time;
		$this->options  = array_merge(array('gzip' => false), $options);

	}

	function process($files) {

		// init vars
		$files = $this->_getFiles($files);
		$id    = md5(serialize($this->options).'_'.implode(';', $files['cache']));
		$file  = sprintf($this->filename, $id);
		$new   = false;

		// cache directory exists ?
		if ($path = $this->warp->path->path('cache:')) {
			$path = rtrim($path, '/').'/';

			// set cache true, if cache file already exists
			$cache = file_exists($path.$file) && (time() - filemtime($path.$file)) < $this->lifetime;

			// process files and create cache, if cache does not exists
			if (!$cache && count($files['cache'])) {
				$cache = $this->_buildCache($files['cache'], $path.$file);				
				$new   = true;
			}

			// return cache id, url and file
			if ($cache) {
				$url = $this->warp->path->url('cache:'.$file);

				if ($this->options['gzip']) {
					$url = rtrim(dirname($url), '/').'/'.sprintf($this->gzipname, $id);
				}

				return array('file' => $path.$file, 'new' => $new, 'urls' => array_merge(array($url), array_keys($files['nocache'])));
			}
		}

		return null;
	}
	
	function _buildCache($files, $cache_file) {

		// init vars
		$data = '';

		// get file data
		foreach ($files as $file => $filepath) {
			$data .= file_get_contents($filepath)."\n\n"; 
		}
		
	    // create cache file
	    return file_put_contents($cache_file, $data);
	}
	
	function _getFiles($files) {

		// init vars
		$pattern = '/'.preg_quote($this->warp->path->url('site:'), '/').'/';
		$array   = array('cache' => array(), 'nocache' => array());

		// remove site url part and get filesystem path, if exists
		foreach ($files as $file) {
			$path = $this->warp->path->path('site:'.preg_replace($pattern, '', $file, 1));
			$array[($path ? 'cache' : 'nocache')][$file] = $path;
		}

		return $array;
	}

}

class WarpCacheStylesheets extends WarpCache {
	
	function __construct($system) {
		parent::__construct($system);

		// init vars
		$this->filename = 'css-%s.css';
		$this->gzipname = 'css.php?id=%s';

	}

	function _buildCache($files, $cache_file) {

		// init vars
		$data  = '';

		// get stylesheet data
		foreach ($files as $file => $filepath) {
			$contents = WarpCacheStylesheets::_loadFile($filepath, false);
			
			// return the path to where this CSS file originated from
			WarpCacheStylesheets::_buildPath(null, dirname($file).'/', dirname($filepath));

			// prefix all paths within this CSS file, ignoring external and absolute paths
			$data .= preg_replace_callback('/url\([\'"]?(?![a-z]+:|\/+)([^\'")]+)[\'"]?\)/i', array('WarpCacheStylesheets', '_buildPath'), $contents);
		}
		
	    // move @import rules to the top
	    $regexp = '/@import[^;]+;/i';
	    preg_match_all($regexp, $data, $matches);
	    $data = preg_replace($regexp, '', $data);
	    $data = implode('', $matches[0]) . $data;

	    // create cache file
	    return file_put_contents($cache_file, $data);
	}

	function _buildPath($matches, $base = null, $filepath = null) {
		static $_base;
		static $_filepath;

		if (isset($base)) {
			$_base = $base;
		}
		
		if (isset($filepath)) {
			$_filepath = $filepath;
		}

		// prefix with base and remove '../' segments where possible
		$path = $_base.$matches[1];
		$last = '';
		while ($path != $last) {
			$last = $path;
			$path = preg_replace('`(^|/)(?!\.\./)([^/]+)/\.\./`', '$1', $path);
		}

		// map image to file path
		if (preg_match('/\.(gif|png|jpg)$/i', $matches[1])) {
			$warp =& Warp::getInstance();
			$warp->cache->images[sprintf('url("%s")', $path)] = realpath(rtrim($_filepath, '/').'/'.$matches[1]);
		}

		return sprintf('url("%s")', $path);
	}

	function _loadFile($file) {
		$contents = null;

		if (file_exists($file)) {
			// load the local CSS stylesheet
			$contents = file_get_contents($file); 

			// change to the current stylesheet's directory
			$cwd = getcwd();
			chdir(dirname($file));

			// replaces @import commands with the actual stylesheet content
			// this happens recursively but omits external files
			$contents = preg_replace_callback('/@import\s*(?:url\()?[\'"]?(?![a-z]+:)([^\'"\()]+)[\'"]?\)?;/', array('WarpCacheStylesheets', '_loadFileRecursive'), $contents);

			// remove multiple charset declarations for standards compliance (and fixing Safari problems)
			$contents = preg_replace('/^@charset\s+[\'"](\S*)\b[\'"];/i', '', $contents);

			// change back directory.
			chdir($cwd);
		}

		return $contents;
	}

	function _loadFileRecursive($matches) {
		$filename = $matches[1];

		// load the imported stylesheet and replace @import commands in there as well
		$file = WarpCacheStylesheets::_loadFile($filename);

		// if not current directory, alter all url() paths, but not external
		if (dirname($filename) != '.') {
			$file = preg_replace('/url\([\'"]?(?![a-z]+:|\/+)([^\'")]+)[\'"]?\)/i', 'url('.dirname($filename).'/\1)', $file);	
		}
		
		return $file;
	}	

}

class WarpCacheJavascripts extends WarpCache {
	
	function __construct($system) {
		parent::__construct($system);

		// init vars
		$this->filename = 'js-%s.js';
		$this->gzipname = 'js.php?id=%s';
	
	}

}