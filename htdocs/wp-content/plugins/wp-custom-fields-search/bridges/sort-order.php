<?php
/*
 * Copyright 2009 Don Benjamin
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at 
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License.
 */

/** This file allows sorting fields to be defined in search forms.
 */
require_once(dirname(__FILE__).'/../extra_search_fields.php');

class SortingComparison extends Comparison {
	function addSQLWhere($key,$value){
		$this->key = $key;
		$this->value = $value;
		return 1;
	}
	function shouldJoin(){
		return true;
	}

	function sql_order($order,$field,$value){
		if(strtolower(substr($value,0,1))=='d') $field.=" DESC";
		return $field;
	}

	function describeSearch($desc){
		return "";
		return $desc;
	}
}



add_filter('custom_search_get_classes','add_sort_order_search_fields');
function add_sort_order_search_fields($classes){
	$classes['comparison']['SortingComparison']='Sort Order';
	return $classes;
}

?>
