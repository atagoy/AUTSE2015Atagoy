<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperMenu
		Menu helper class
*/    
class WarpHelperMenu extends WarpHelper {
	
    /*
		Variable: _renderers
			Menu renderers.
    */	
	var $_renderers = array();
	
	/*
		Function: Constructor
			Class Constructor.
	*/
	function __construct(){
		parent::__construct();

		// load menu class
		require_once($this->warp->path->path('warp:classes/menu.php'));
	}	

	/*
		Function: process
			Process menu module and apply renderers

		Parameters:
			$module - Menu module
			$renderers - Array of renderers

		Returns:
			String
	*/	
	function process($module, $renderers){
	
		// init vars
		$renderers = (array) $renderers;
		$xml       =& $this->getHelper('xml');
		$menu      = $xml->load($module->content, 'xhtml'); 		
		
		foreach ($renderers as $renderer) {
			
			if (!isset($this->_renderers[$renderer])) {
				$classname = 'WarpMenu'.$renderer;
				
				if (!class_exists($classname) && ($path = $this->warp->path->path('menu:'.$renderer.'.php'))) {				
					require_once($path);
				}

				$this->_renderers[$renderer] = new $classname();
			}
			
			$menu = $this->_renderers[$renderer]->process($module, $menu);
			
			if (!$menu) {
				return $module->content;
			}
		}
		
		return $menu->toString(false);
	}

}