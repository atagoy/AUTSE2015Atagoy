<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/


/*
	Class: WarpMenuAccordion
		Menu base class
*/
class WarpMenuAccordion extends WarpMenu {
	
	/*
		Function: process

		Returns:
			Xml Object
	*/	
	function process(&$module, &$xmlobj) {
		
		$this->_process($module, $xmlobj);
		
		return $xmlobj;
	}
	
	function _process(&$module, &$xmlobj, $level=0) {
		
        $xmlobj->addClass("menu-accordion");
        
        $lis = $xmlobj->children("li");
        
        for($i=0,$imax=count($lis);$i<$imax;$i++){
            
            $child_list =& $lis[$i]->child("ul");

            if($child_list){
              
			  if ($lis[$i]->_children[0]->name()=='span') {
			  
				  $wrapper =&  $lis[$i]->createElement('div');
				  
				  $lis[$i]->addClass('toggler');  
				  $child_list->_attributes['class'] = 'accordion level2';
				  
				  $lis2 = $child_list->children("li");
				  
				  foreach($lis2 as $index=>$li2){
					
					$child_list2 =& $lis2[$index]->child("ul");
					
					if($child_list2 && !$lis2[$index]->hasClass('active')) {
						$child_list2->dispose();
					}
				  }
				  
				  $child_list->setParent($wrapper);
			  } else {
				if(!$module->showAllChildren && !$lis[$i]->hasClass('active')) $child_list->dispose();
			  }
            }
        }
	}

}