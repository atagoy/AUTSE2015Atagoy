<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpMenuDropdown
		Menu base class
*/
class WarpMenuDropdown extends WarpMenu {
	
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
		
		if($level==0) {
            $xmlobj->addClass('menu-dropdown');
        }
        
        $lis = $xmlobj->children("li");
        
        for($i=0,$imax=count($lis);$i<$imax;$i++){
            
            $child_list =& $lis[$i]->child("ul");

            if($child_list){
            
              $dropdown_root =& $lis[$i]->createElement('div',null,array('class'=>'dropdown'));

              $dropdown_t1  =& $dropdown_root->createElement('div',null,array('class'=>'dropdown-t1'));
              $dropdown_t2  =& $dropdown_t1->createElement('div',null,array('class'=>'dropdown-t2'));
              $dropdown_t3  =& $dropdown_t2->createElement('div',null,array('class'=>'dropdown-t3'));
              
              $dropdown_1   =& $dropdown_root->createElement('div',null,array('class'=>'dropdown-1'));
              $dropdown_2   =& $dropdown_1->createElement('div',null,array('class'=>'dropdown-2'));
              $dropdown_3   =& $dropdown_2->createElement('div',null,array('class'=>'dropdown-3'));
              
              $dropdown_b1  =& $dropdown_root->createElement('div',null,array('class'=>'dropdown-b1'));
              $dropdown_b2  =& $dropdown_b1->createElement('div',null,array('class'=>'dropdown-b2'));
              $dropdown_b3  =& $dropdown_b2->createElement('div',null,array('class'=>'dropdown-b3'));
              $child_list->setParent($dropdown_3);
              
              $columns = isset($lis[$i]->_attributes['data-menu-columns']) ? (int) $lis[$i]->_attributes['data-menu-columns'] : 0;

              if ($columns > 1) {
    
                $cols     = ceil(count($child_list->_children) / $columns);
                $children = $child_list->children('li');
                
				$rows   = ceil(count($children) / $columns);
				
                for($c=0,$max=count($child_list->_children); $c<$max;$c++){
                    
                    $column = intval($c/$rows);
            
                    if(!isset($dropdown_3->_children[$column])){
                        $dropdown_3->createElement("ul");
                    }
                    
                    if($column!=0){
                        $children[$c]->setParent($dropdown_3->_children[$column]);
                    }
					
					$children[$c]->_attributes['class'] = "level2"; 
                }				
                
				if(isset($lis[$i]->_attributes['data-menu-columnwidth'])){
					$columnwidth = ($columns * $lis[$i]->_attributes['data-menu-columnwidth']).'px';
					$dropdown_root->addAttribute('style','width:'.$columnwidth.';');
				}
              }else{
				if (isset($lis[$i]->_attributes['data-menu-columnwidth']) && $lis[$i]->_attributes['data-menu-columnwidth'] > -1) {
					$columnwidth = $lis[$i]->_attributes['data-menu-columnwidth'].'px';
					$dropdown_root->addAttribute('style','width:'.$columnwidth.';');
				}  
			  }
              
              
              $child_lists = $dropdown_3->children("ul");
              //$dropdown_root->addClass('columns'.count($child_lists));
              $dropdown_root->addClass('columns'.$columns);
              
              
              for($c=0;$c<count($child_lists);$c++){
                  
				  $child_lists[$c]->addAttribute('class','');
                  $child_lists[$c]->addClass("col".($c+1));
                  $child_lists[$c]->addClass("level2");
                  
                  if($c==0) $child_lists[$c]->addClass("first");
                  if($c==count($child_lists)-1) $child_lists[$c]->addClass("last");
                  
                  $lis2 = $child_lists[$c]->children("li");
                  
                  if(!count($lis2)) continue;
                  
                  for($li=0;$li<count($lis2);$li++){
                  	
					$lis2[$li]->_attributes['class'] = "level2";
					$lis2[$li]->addClass("item".($li+1));
                 	
					if($li==0) $lis2[$li]->addClass('first');
					if($li==count($lis2)-1) $lis2[$li]->addClass('last');
					
					if ($lis2[$li]->_children[0]->name() == 'span') {
						$lis2[$li]->addClass('separator');
					}
					
					if($lis2[$li]->_children[0]->hasClass('active')) $lis2[$li]->addClass('active');
					if($lis2[$li]->_children[0]->hasClass('current')) $lis2[$li]->addClass('current');
					
					$lis2[$li]->_children[0]->_attributes['class'] = $lis2[$li]->_attributes['class'];
					
                    $group_box1  =& $lis2[$li]->createElement('div',null,array('class'=>'group-box1'));
                    $group_box2  =& $group_box1->createElement('div',null,array('class'=>'group-box2'));
                    $group_box3  =& $group_box2->createElement('div',null,array('class'=>'group-box3'));
                    $group_box4  =& $group_box3->createElement('div',null,array('class'=>'group-box4'));
                    $group_box5  =& $group_box4->createElement('div',null,array('class'=>'group-box5'));
                    
                    $hover_box1  =& $group_box5->createElement('div',null,array('class'=>'hover-box1'));
                    $hover_box2  =& $hover_box1->createElement('div',null,array('class'=>'hover-box2'));
                    $hover_box3  =& $hover_box2->createElement('div',null,array('class'=>'hover-box3'));
                    $hover_box4  =& $hover_box3->createElement('div',null,array('class'=>'hover-box4'));
                    
                    $lis2[$li]->_children[0]->setParent($hover_box4);

                    $child_lists3 = $lis2[$li]->children("ul");
                    
                    if(!count($child_lists3)) continue;
                    
					$hover_box4->_children[0]->addClass('parent');
					$lis2[$li]->addClass('parent');
					
                    for($g=0;$g<count($child_lists3);$g++){
                        $child_lists3[$g]->addClass("level3");
 
                        $sub =& $group_box5->createElement('div',null,array('class'=>'sub'));
                        $child_lists3[$g]->setParent($sub);
                    }

                  }
              }

            }
        }
	}

}