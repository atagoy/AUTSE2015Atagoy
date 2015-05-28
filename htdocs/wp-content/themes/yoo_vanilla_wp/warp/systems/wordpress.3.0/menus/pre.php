<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/


/*
	Class: WarpMenuPre
		Menu base class
*/
class WarpMenuPre extends WarpMenu {
	
	/*
		Function: process

		Returns:
			Xml Object
	*/	
	function process(&$module, &$xmlobj) {
		
		$ul = $xmlobj->document->getElement("ul");
		
		if ($ul) {
			$this->_process($module, $ul);
			
			return $ul;
		} else {
			return false;
		}
	}
	
	function _process(&$module, &$xmlobj, $level=0) {
		
        $lis  = $xmlobj->children("li");
        
        for($i=0,$imax=count($lis);$i<$imax;$i++){

            $isActive = preg_match('/(current_page_item|current-menu-item)/', $lis[$i]->_attributes['class']) ? 2 : 0;
            
			$id = null;
			
			if (!is_null($lis[$i]->attributes("id"))) {
				$tmp = explode("-", $lis[$i]->attributes("id"));
				
				if (count($tmp)==3 && is_numeric($tmp[2])) {
					$id = $tmp[2];
				}
			}
			
			$warp = Warp::getInstance();
			
			$menu_settings = $warp->system->getMenuItemOptions($id);
			
            $lis[$i]->_attributes = array();
            
            $columns     = $menu_settings['columns'];
            $columnwidth = $menu_settings['columnwidth'];
              
            $lis[$i]->addAttribute('data-menu-active',(int) $isActive);
            $lis[$i]->addAttribute('data-menu-columns', (is_numeric($columns) ? $columns:1));
            $lis[$i]->addAttribute('data-menu-columnwidth',(is_numeric($columnwidth) ? $columnwidth:-1));

            $childList =& $lis[$i]->child("ul");
            
            if($childList){
              $this->_process($module, $childList, ($level+1));                   
            }
            
            if($lis[$i]->child("a")){
			
				$link =& $lis[$i]->child("a");

				if ($link->attributes('href') && preg_match('/^index.php/', $link->attributes('href'))) {
				  $homeurl = str_replace('index.php', rtrim(get_option('siteurl'), '/').'/', $link->attributes('href'));
				  
				  $link->addAttribute('href', $homeurl);
				  
				  if(is_home() || is_front_page()){
					  $lis[$i]->addAttribute('data-menu-active', 1);
				  }
				  
				}
				
				if ($link->attributes('href') && $link->attributes('href')=='#') {
				  	$link->_name = 'span';
				  	unset($link->_attributes['href']);			  
				}			  

				if(!count($link->_children)){
				$link->parse('<span>'.$link->_data.'</span>');
				$link->_data = null;
				}
							  

				if(!empty($menu_settings['image'])){
					$imgurl = rtrim(get_option('siteurl'), '/').'/wp-content/uploads/'.$menu_settings['image'];
					$lis[$i]->_children[0]->_children[0]->addAttribute('data-menu-image', $imgurl);
				}
					
	
            }
        }
	}

}