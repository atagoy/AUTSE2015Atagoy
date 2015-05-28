<?php 
function ih_input( $var, $type, $description = "", $value = "", $selected="", $onchange="" ) {
	echo "\n"; 	
	switch( $type ){	
	    case "text":
			if (eregi ("color", $var)) { 
			echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 80%\" class=\"color\" value=\"$value\" onchange=\"$onchange\"/>";		
			} else {
	 		echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 80%\" class=\"code\" value=\"$value\" onchange=\"$onchange\"/>";					
			}
	 		echo "<p style=\"font-size:0.9em; color:#999; margin:0;\">$description</p>";			
			break;

		case "smalltext":
	 		echo "<input name=\"$var\" id=\"$var\" type=\"$type\" style=\"width: 43%\" class=\"code\" value=\"$value\" onchange=\"$onchange\"/>";
	 		echo "<br /><span style=\"font-size:0.9em; color:#999; margin:0; padding-left:5px; \">$description</span><br />";			
			break;
			
		case "submit":		
	 		echo "<p class=\"submit\" align=\"right\"><input name=\"$var\" type=\"$type\" value=\"$value\" /></p>";
			break;

		case "option":		
			if( $selected == $value ) { $extra = "selected=\"true\""; }
			echo "<option value=\"$value\" $extra >$description</option>";		
		    break;
		    
  		case "radio":  		
			if( $selected == $value ) { $extra = "checked=\"true\""; }  			
  			echo "<label><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";  			
  			break;
  			
		case "checkbox":		
			if( $selected == $value ) { $extra = "checked=\"true\""; }
  			echo "<label><input name=\"$var\" id=\"$var\" type=\"$type\" value=\"$value\" $extra /> $description</label><br/>";
  			break;

		case "textarea":		
		    echo "<textarea name=\"$var\" id=\"$var\" style=\"width: 80%; height: 10em;\" class=\"code\">$value</textarea>";		
		    break;
	}
}


function ih_select( $var, $arrValues, $selected, $label ) {
	if( $label != "" ) {
		echo "<label for=\"$var\">$label</label>";
	}	
	echo "<select name=\"$var\" id=\"$var\">\n";	
	foreach( $arrValues as $arr ) {		
		$extra = "";	
		if( $selected == $arr[ 0 ] ) { $extra = " selected=\"true\""; }
		echo "<option value=\"" . $arr[ 0 ] . "\"$extra>" . $arr[ 1 ] . "</option>\n";
	}	
	echo "</select>";	
}

function ih_mselect( $var, $arrValues, $arrSelected, $label, $description ) {
	if( $label != "" ) {
		echo "<label for=\"$var\">$label</label>";
	}	
	echo "<select multiple=\"true\" size=\"7\" name=\"$var\" id=\"$var\" style=\"height:150px;\">\n";	
	foreach( $arrValues as $arr ) {		
		$extra = "";	
		if( in_array( $arr[ 0 ], $arrSelected ) ) { $extra = " selected=\"true\""; }
		echo "<option value=\"" . $arr[ 0 ] . "\"$extra>" . $arr[ 1 ] . "</option>\n";
	}	
	echo "</select>";	
	echo "<p style=\"font-size:0.9em; color:#999; margin:0;\">$description</p>";	
}

function nat_th( $title ) {
   	echo "<tr valign=\"top\">";
	echo "<th scope=\"row\">$title</th>";
	echo "<td>";
}

function nat_endth() {
	echo "</td>";
	echo "</tr>";	
}

function nat_hit( $hit ) {
	echo "<p><small>$hit</p></small>";
}
?>