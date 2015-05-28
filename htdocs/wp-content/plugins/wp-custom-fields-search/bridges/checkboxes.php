<?php
/*
 * Copyright 2013 Web Hammer Ltd.
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
class CheckboxInput extends DropDownField {
	function getInput($name,$joiner,$fieldName=null){
		if(!$fieldName) $fieldName = $name;
		$htmlName = $this->getHTMLName($name);
		$submitted = $this->getValue($name);
		if(!$submitted) $submitted = array();
		$options = $this->getOptions($joiner,$fieldName);

		$inputs = array();
		foreach($options as $value=>$label){
			if(!$value) continue;
			$full_name = htmlspecialchars($htmlName,ENT_QUOTES)."[$value]";
			$checked =  in_array($value,$submitted) ? " checked='true'" : "";
			$inputs[] = "<div class='checkbox-wrapper'><input type='checkbox' value='$value' name='$full_name'$checked/><label for='$full_name'>$label</label></div>";
		}
		return "<div class='checkbox-group-wrapper'>".join("\n",$inputs)."</div>";
	}
	function splitMultiValues($value){
		if(!is_array($value)) $value = array();
		return $value;
	}
}
class WPCheckboxPlugin {
	function add_checkboxes($types){
		require_once(dirname(__FILE__)."/checkboxes.php");
		$types['input']['CheckboxInput'] = __( "Checkboxes" , "wp-cfs-checkboxes");
		return $types;
	}
}
$cb_plugin = new WPCheckboxPlugin;
add_filter('custom_search_get_classes',array($cb_plugin,'add_checkboxes'));
