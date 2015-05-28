<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperXml
		XML helper class, handle XML files
*/
class WarpHelperXml extends WarpHelper {
    
    /*
		Function: load
			Get Xml object from file/string.

		Returns:
			Xml Object
	*/
	function load($input, $mode = 'xml', $cache = false) {
		
		// is file ?
		if (substr(trim($input), 0, 1) != '<' && is_file($input)) {
			$input = file_get_contents($input);
		}

		// is cached ?
		if ($cache && ($path = $this->warp->path->path('cache:'))) {
			$file = rtrim($path, '/').sprintf('/%s-%s.php', $mode, md5($input));
		
			if (file_exists($file) && (time() - filemtime($file)) < $this->warp->system->cache_time) {
				$xml = unserialize(file_get_contents($file));
			} else {
				$xml = strtolower($mode) == 'xhtml' ? new WarpXHTML($input) : new WarpXML($input);
				file_put_contents($file, serialize($xml));
			}
			
			return $xml;
		}
		
		return strtolower($mode) == 'xhtml' ? new WarpXHTML($input) : new WarpXML($input);
	}

}

/*
	Class: WarpXML
		XML Parser Class
*/
class WarpXML{

    /*
		Variable: document
			XML root node.
    */
    var $document;
    
    /*
       Function: WarpXML
            Constructor.
       
       Parameters:
            $xmlstring  - xml string
            
       Returns:
            WarpXML
    */
    function WarpXML($xmlstring){
        $classname = get_class($this).'Element';
        $this->document = new $classname('document');
        $this->document->parse($xmlstring);
    }

}

/*
	Class: WarpXMLElement
		XML Node Container
*/
class WarpXMLElement{
    
    /*
		Variable: _attributes
			Array with the attributes of this XML element.
    */
	var $_attributes = array();

    /*
		Variable: _name
			The name of the element.
    */
	var $_name = '';

    /*
		Variable: _data
			The data the element contains.
    */
	var $_data = '';

    /*
		Variable: _children
			Array of references to the objects of all direct children of this XML object.
    */
	var $_children = array();

    /*
		Variable: _parent
			Reference to the parent element.
    */
    var $_parent = null;

	/*
	   Function: WarpXMLElement
	        Constructor, sets up all the default values.
   
	   Parameters:
	        $name - Node name
	        $attrs - Node attributes
        
	   Returns:
	        WarpXMLElement
	*/
	function WarpXMLElement($name, $attrs = array()){
		$this->_name = $name;
		$this->_attributes = is_array($attrs) ? $attrs : array();
	}
    
    /*
       Function: parse
            Parse a XML string.
       
       Parameters:
            $xmlstring  - xml string
            
       Returns:
            void
    */
    function parse($xmlstring){
		$this->_parse(simplexml_load_string($xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA),$this);
    }

    /*
       Function: _parse
            Parse xml string to xml tree.
       
       Parameters:
            $xmlobj
            $parent
            
       Returns:
            void
    */
    function _parse($xmlobj, $parent){
        
		if(!$xmlobj) return;
		
		$classname  = get_class($this);
		$attributes = get_object_vars($xmlobj->attributes());

		$element = new $classname($xmlobj->getName(), (isset($attributes['@attributes']) ? $attributes['@attributes'] : array()));
		$element->_parent = $parent;
		$parent->_children[] = $element;
      
		if (count($xmlobj->children())) {
			foreach ($xmlobj->children() as $child) {
				$this->_parse($child, $element);
			}
		} else {
			$element->_data = (string) $xmlobj;
		}
    }

    /*
		Function: name
			Get the name of the element.

		Returns:
			String
	*/
	function name() {
		return $this->_name;
	}

    /*
       Function: attributes
            Get the an attribute of the element.
       
       Parameters:
            $attribute  - attribute name
            
       Returns:
            Mixed
    */
	function attributes($attribute = null) {

		if (!isset($attribute)) {
			return $this->_attributes;
		}
		
		return isset($this->_attributes[$attribute]) ? $this->_attributes[$attribute] : null;
	}

    /*
		Function: data
			Get the data of the element.

		Returns:
			String
	*/
	function data() {
		return $this->_data;
	}

    /*
       Function: setData	
            Set the data of the element.
       
       Parameters:
            $data - data content
       
       Returns:
            void
    */
	function setData($data) {
		$this->_data = $data;
    	return $this;
	}
    
    /*
		Function: createElement
			Create a child element.

		Returns:
			Element node
	*/
	function createElement($name = null, $value = null, $attributes = array()) {

		$classname = get_class($this);
		$element = new $classname($name, $attributes);
		$element->_parent = $this;
		$element->_data = $value;
		$this->_children[] = $element;

		return $element;
	}
    
    /*
		Function: setParent
			Set the parent node of a node.
	*/
    function setParent($parent){
        
        if ($this->_parent) {
            foreach($this->_parent->_children as $index=> $c){
                if ($c === $this) {
                    array_splice($this->_parent->_children, $index, 1);
                }
            }
        }

        $this->_parent = $parent;
        $parent->_children[] = $this;
        
        return $this;
    }
    
    /*
		Function: parent
			Get the parent of a node.

		Returns:
			Element node
	*/
    function parent(){
        return $this->_parent;
    }
    
    /*
		Function: children
			Get the children of the element.

		Returns:
			Array
	*/
	function children($name=false) {
        if ($name === false) return $this->_children;
        
        $elements = array();
        
        for ($i=0; $i < count($this->_children); $i++) {
            if($name==$this->_children[$i]->name()){
                $elements[] = $this->_children[$i];
            }
        }
        
        return $elements;
	}

    /*
		Function: children
			Get the first child by tag name.
        
        Parameters:
			$name - tag name
        
		Returns:
			Element node
	*/
	function child($name) {
        
        for ($i=0; $i < count($this->_children); $i++) {
            if ($name == $this->_children[$i]->name()) return $this->_children[$i];
        }
        
        return null;
	}

    /*
       Function: addAttribute
            Adds an attribute to the element.
       
       Parameters:
            $name  - attribute name
            $value - attribute value            
       
       Returns:
            void
    */
	function addAttribute($name, $value) {

		// add the attribute to the element, override if it already exists
		$this->_attributes[$name] = $value;

        return $this;
	}

    /*
       Function: removeAttribute
            Removes an attribute from the element.
       
       Parameters:
            $name  - attribute name          
       
       Returns:
            void
    */
	function removeAttribute($name) {

		// remove the attribute from the element
		unset($this->_attributes[$name]);

		return $this;
	}

    /*
       Function: removeChild
            Removes a direct child from the element.
       
       Parameters:
            $child  - xml node          
            
       Returns:
            Boolean
    */
	function removeChild($child) {
		
        foreach ($this->_children as $index => $c) {
            if ($c === $child) {
                array_splice($this->_children, $index, 1);
				//unset($this->_children[$index]);
                return true;
            }
        }

        return false;
	}
    
    /*
       Function: findIndexOfChild
            Finds the the index of a child node.
       
       Parameters:
            $child  - xml node          
            
       Returns:
            Integer
    */
    function findIndexOfChild($child){

        foreach ($this->_children as $index => $c) {
            if ($c === $child) {
                return $index;
            }
        }

        return -1;
    }

    /*
       Function: map
            Traverses the tree calling the $callback.
       
       Parameters:
            $callback  - callback function         
            $args   - arguments array          
            
       Returns:
            void
    */
	function map($callback, $args = array()) {

		$callback($this, $args);

		// map to all children
		if ($n = count($this->_children)) {
			for ($i=0; $i < $n; $i++) {
				$this->_children[$i]->map($callback, $args);
			}
		}
	}

    /*
       Function: getElements
            Return elements by query.
       
       Parameters:
            $query  - query string  
            
       Returns:
            Xml nodes
    */
    function getElements($query){

        $parts    = explode(',', $query);
        $elements = array();
        
        for($i = 0; $i < count($this->_children); $i++) {
            foreach($parts as $p){
                $p = trim($p);

                if ($p == $this->_children[$i]->name()) {
                    $elements[] = $this->_children[$i];
                }
                
                if (count($this->_children[$i]->_children)) {
                    $tmp = $this->_children[$i]->getElements($query);
                    for ($e=0; $e < count($tmp); $e++) {
                        array_push($elements, $tmp[$e]);
                    }
                }
            }
        }
        
        return $elements;
    }
    
    /*
       Function: getElement
            Return first element by query.
       
       Parameters:
            $query  - query string  
            
       Returns:
            Xml node
    */
    function getElement($query){
		
		$element = null;
        
        for($i = 0; $i < count($this->_children); $i++) {
			if ($query == $this->_children[$i]->name()) {
				$element = $this->_children[$i];
				break;
			}
			
			if (count($this->_children[$i]->_children)) {
				$element = $this->_children[$i]->getElement($query);
				if ($element ) break;
			}
        }
        
        return $element;
    }
    
    /*
       Function: inject
            Injects a node into another.
       
       Parameters:
            $element  - xml node
            
       Returns:
            void
    */
    function inject($element){
        $this->setParent($element);
        return $this;
    }
    
    /*
       Function: adopt
            Inserts the passed element(s) inside the Element (which will then become the parent element).
       
       Parameters:
            $elements  - xml nodes
            
       Returns:
            void
    */
    function adopt(){
        $args = func_get_args();

		foreach ($args as $a) {
            $a->setParent($this);
        }
        
        return $this;
    }
    
    /*
       Function: wraps
            The Element is moved to the position of the passed element and becomes the parent.
       
       Parameters:
            $element  - xml node
            
       Returns:
            void
    */
    function wraps($element){
        $parent = $this->_parent;
        
        if ($parent) {
            $element->setParent($parent);
        }
        
        $this->setParent($element);
        
        return $this;
    }
    
    /*
       Function: dispose
            Removes the Element from the tree.
            
       Returns:
            void
    */
    function dispose(){
        if ($this->_parent) {
            $this->_parent->removeChild($this);
        }
    }
    
    /*
       Function: replaces
            Replaces the Element with an Element passed.
       
       Parameters:
            $element  - xml node
            
       Returns:
            Xml node
    */
    function replaces($element){
        if ($this->_parent) {
            $element->setParent($this->_parent);
            $this->_parent->_children[$this->_parent->findIndexOfChild($this)] = $element;
            return $element;
        }
    }

    /*
       Function: toString
            Return a well-formed XML string based on SimpleXML element.
       
       Parameters:
            $whitespace  - output with whitespaces     
            
       Returns:
            String
    */
	function toString($whitespace=true,$level=0){

		// start a new line, indent by the number indicated in $this->level, add a <, and add the name of the tag
		if ($whitespace) {
			$out = "\n".str_repeat("\t", $level).'<'.$this->_name;
		} else {
			$out = '<'.$this->_name;
		}

		// for each attribute, add attr="value"
		foreach($this->_attributes as $attr => $value) {
			$out .= ' '.$attr.'="'.htmlspecialchars($value).'"';
		}

		// if there are no children and it contains no data, end it off with a />
		if (empty($this->_children) && empty($this->_data)) {
			$out .= " />";
		} else {

			// if there are children
			if (!empty($this->_children)) {

				// close off the start tag
				$out .= '>';

				// for each child, call the asXML function (this will ensure that all children are added recursively)
				foreach($this->_children as $child)
					$out .= $child->toString($whitespace,$level+1);
                
				// add the newline and indentation to go along with the close tag
				if ($whitespace) {
					$out .= "\n".str_repeat("\t", $level);
				}
			}

			// if there is data, close off the start tag and add the data
			elseif (!empty($this->_data))
				$out .= '>'.htmlspecialchars($this->_data);

			// add the end tag
			$out .= '</'.$this->_name.'>';
		}

		// return the final output
		return $out;
	}
}

/*
	Class: WarpXHTML
		HTML Parser Class
*/
class WarpXHTML extends WarpXML {}

/*
	Class: WarpXHTMLElement
		XML Node Container with special methods for better handling (like Mootools)
*/
class WarpXHTMLElement extends WarpXMLElement{
    
    /*
       Function: WarpXHTMLElement
            Constructor, sets up all the default values.
       
       Parameters:
            $name - Node name
            $attrs - Node attributes
            
       Returns:
            WarpXHTMLElement
    */
	function WarpXHTMLElement($name, $attrs = array()){
		parent::__construct(strtolower($name), $attrs);
	}
	
	function parse($xmlstring){

		$xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
	    $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
	    
	            $xmlstring = preg_replace_callback("/&[\w]+;|&#[\d]+;|&#x[0-9a-fA-F]+;|(&)/m", create_function('$matches', 'return count($matches)==2 ? "&#38;": $matches[0];'), $xmlstring);
	    $xmlstring = str_ireplace($html,$xml,$xmlstring);

		$this->_parse(@simplexml_load_string($xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA),$this);
    }
	
    /*
       Function: get
            Shortcut to attributes($name).
       
    */
    function get($name){
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }
    
    /*
       Function: set
            Shortcut to addAttribute($name, $value).
       
    */
    function set($name, $value) {

		if ($name=='html') {
			$this->_data = null;
			$this->parse($value);
		} elseif ($name=='text') {
			$this->_data = $value;
		} else {
			$this->_attributes[$name] = $value;
		}

        return $this;
    }
       
    /*
       Function: hasClass
            Tests the Element to see if it has the passed in classname.
       
       Parameters:
            $classname  - class name
            
       Returns:
            Boolean
    */
    function hasClass($classname){

		if (isset($this->_attributes['class']) && stripos($this->_attributes['class'], $classname) !== false) {
		    foreach (explode(' ', $this->_attributes['class']) as $class) {
		        if (strtolower($classname) === strtolower($class)) {
					return true;
				}
		    }	    
		}

	    return false;
    }
    
    /*
       Function: addClass
            Adds the passed in class to the Element, if the Element doesnt already have it.
       
       Parameters:
            $classname  - class name
            
       Returns:
            void
    */
    function addClass($classname) {
	
        if (!$this->hasClass($classname)) {
		    $classes   = isset($this->_attributes['class']) ? explode(' ', $this->_attributes['class']) : array();
			$classes[] = strtolower($classname);
		    $this->_attributes['class'] = trim(implode(' ', $classes));
		}

        return $this;
    }
    
    /*
       Function: removeClass
            Works like addClass, but removes the class from the Element.
       
       Parameters:
            $classname  - class name
            
       Returns:
            void
    */
    function removeClass($classname){

	    if ($this->hasClass($classname)) {
	        $classes = explode(' ', $this->_attributes['class']);
			$classes = array_diff($classes, array(strtolower($classname)));
		    $this->_attributes['class'] = trim(implode(' ', $classes));
	    }
        
        return $this;
    }

    /*
       Function: findIndexOfChild
            Return a well-formed XML string based on SimpleXML element.
       
       Parameters:
            $whitespace  - output with whitespaces     
            
       Returns:
            String
    */
	function toString($whitespace=true,$level=0){
		//Start a new line, indent by the number indicated in $this->level, add a <, and add the name of the tag
		if ($whitespace) {
			$out = "\n".str_repeat("\t", $level).'<'.$this->_name;
		} else {
			$out = '<'.$this->_name;
		}

		//For each attribute, add attr="value"
		foreach($this->_attributes as $attr => $value) {
			$out .= ' '.$attr.'="'.htmlspecialchars($value).'"';
		}

		//If there are no children and it contains no data, end it off with a />
		if (empty($this->_children) && empty($this->_data)) {
			$out .= "></".$this->_name.">";
		}
		else //Otherwise...
		{
			//If there are children
			if(!empty($this->_children))
			{
				//Close off the start tag
				$out .= '>';

				//For each child, call the asXML function (this will ensure that all children are added recursively)
				foreach($this->_children as $child)
					$out .= $child->toString($whitespace,$level+1);
                
				//Add the newline and indentation to go along with the close tag
				if ($whitespace) {
					$out .= "\n".str_repeat("\t", $level);
				}
			}

			//If there is data, close off the start tag and add the data
			elseif(!empty($this->_data))
				$out .= '>'.htmlspecialchars($this->_data);

			//Add the end tag
			$out .= '</'.$this->_name.'>';
		}

		//Return the final output
		return $out;
	}

}