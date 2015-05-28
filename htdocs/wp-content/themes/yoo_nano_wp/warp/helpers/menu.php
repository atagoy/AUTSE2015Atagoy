<?php
/**
* @package   Warp Theme Framework
* @file      menu.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: MenuWarpHelper
		Menu helper class
*/    
class MenuWarpHelper extends WarpHelper {
	
    /*
		Variable: _renderers
			Menu renderers.
    */	
	protected $_renderers = array();
	
	/*
		Function: Constructor
			Class Constructor.
	*/
	public function __construct(){
		parent::__construct();

		// load menu class
		require_once($this['path']->path('warp:classes/menu.php'));
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
	public function process($module, $renderers){

		// init vars
		$menu = $this['dom']->create($module->content);
		
		foreach ((array) $renderers as $renderer) {
			
			if (!isset($this->_renderers[$renderer])) {
				$classname = 'WarpMenu'.$renderer;
				
				if (!class_exists($classname) && ($path = $this['path']->path('menu:'.$renderer.'.php'))) {				
					require_once($path);
				}

				$this->_renderers[$renderer] = new $classname();
			}
			
			$menu = $this->_renderers[$renderer]->process($module, $menu);
			
			if (!$menu) {
				return $module->content;
			}
		}

		return $menu->first('ul:first')->html();
	}

}