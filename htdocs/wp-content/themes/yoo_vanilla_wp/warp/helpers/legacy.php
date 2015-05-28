<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperLegacy
		Config helper class, configuration container
*/    
class WarpHelperLegacy extends WarpHelper {

	/*
		Function: Constructor
			Class Constructor.
	*/
	function __construct(){
		parent::__construct();

		// load legacy helpers first (eg. xml helper)
		if (version_compare(PHP_VERSION, '5.0', '<')) {
			$this->warp->path->register($this->warp->path->path('warp:libraries/legacy/helpers'),'helpers');
		}
	}

}

/*
	Function: json_encode
		JSON encode function for servers not using PHP 5.2
		Function based on http://freshmeat.net/projects/upgradephp, MIT-License
*/
if (!function_exists("json_encode")) {
   function json_encode($var, /*emu_args*/$obj=FALSE) {
   
      #-- prepare JSON string
      $json = "";
      
      #-- add array entries
      if (is_array($var) || ($obj=is_object($var))) {

         #-- check if array is associative
         if (!$obj) foreach ((array)$var as $i=>$v) {
            if (!is_int($i)) {
               $obj = 1;
               break;
            }
         }

         #-- concat invidual entries
         foreach ((array)$var as $i=>$v) {
            $json .= ($json ? "," : "")    // comma separators
                   . ($obj ? ("\"$i\":") : "")   // assoc prefix
                   . (json_encode($v));    // value
         }

         #-- enclose into braces or brackets
         $json = $obj ? "{".$json."}" : "[".$json."]";
      }

      #-- strings need some care
      elseif (is_string($var)) {
         if (!utf8_decode($var)) {
            $var = utf8_encode($var);
         }
         $var = str_replace(array("\\", "\"", "/", "\b", "\f", "\n", "\r", "\t"), array("\\\\", '\"', "\\/", "\\b", "\\f", "\\n", "\\r", "\\t"), $var);
         $json = '"' . $var . '"';
         //@COMPAT: for fully-fully-compliance   $var = preg_replace("/[\000-\037]/", "", $var);
      }

      #-- basic types
      elseif (is_bool($var)) {
         $json = $var ? "true" : "false";
      }
      elseif ($var === NULL) {
         $json = "null";
      }
      elseif (is_int($var) || is_float($var)) {
         $json = "$var";
      }

      #-- something went wrong
      else {
         trigger_error("json_encode: don't know what a '" .gettype($var). "' is.", E_USER_ERROR);
      }
      
      #-- done
      return($json);
   }
}



/*
	Function: json_decode
		JSON decode function for servers not using PHP 5
		Function based on http://freshmeat.net/projects/upgradephp, MIT-License
*/
if (!function_exists("json_decode")) {
   function json_decode($json, $assoc=FALSE, $limit=512, /*emu_args*/$n=0,$state=0,$waitfor=0) {

      #-- result var
      $val = NULL;
      static $lang_eq = array("true" => TRUE, "false" => FALSE, "null" => NULL);
      static $str_eq = array("n"=>"\012", "r"=>"\015", "\\"=>"\\", '"'=>'"', "f"=>"\f", "b"=>"\b", "t"=>"\t", "/"=>"/");
      if ($limit<0) return /* __cannot_compensate */;

      #-- flat char-wise parsing
      for (/*n*/; $n<strlen($json); /*n*/) {
         $c = $json[$n];

         #-= in-string
         if ($state==='"') {

            if ($c == '\\') {
               $c = $json[++$n];
               // simple C escapes
               if (isset($str_eq[$c])) {
                  $val .= $str_eq[$c];
               }

               // here we transform \uXXXX Unicode (always 4 nibbles) references to UTF-8
               elseif ($c == "u") {
                  // read just 16bit (therefore value can't be negative)
                  $hex = hexdec( substr($json, $n+1, 4) );
                  $n += 4;
                  // Unicode ranges
                  if ($hex < 0x80) {    // plain ASCII character
                     $val .= chr($hex);
                  }
                  elseif ($hex < 0x800) {   // 110xxxxx 10xxxxxx 
                     $val .= chr(0xC0 + $hex>>6) . chr(0x80 + $hex&63);
                  }
                  elseif ($hex <= 0xFFFF) { // 1110xxxx 10xxxxxx 10xxxxxx 
                     $val .= chr(0xE0 + $hex>>12) . chr(0x80 + ($hex>>6)&63) . chr(0x80 + $hex&63);
                  }
                  // other ranges, like 0x1FFFFF=0xF0, 0x3FFFFFF=0xF8 and 0x7FFFFFFF=0xFC do not apply
               }

               // no escape, just a redundant backslash
               //@COMPAT: we could throw an exception here
               else {
                  $val .= "\\" . $c;
               }
            }

            // end of string
            elseif ($c == '"') {
               $state = 0;
            }

            // yeeha! a single character found!!!!1!
            else/*if (ord($c) >= 32)*/ { //@COMPAT: specialchars check - but native json doesn't do it?
               $val .= $c;
            }
         }

         #-> end of sub-call (array/object)
         elseif ($waitfor && (strpos($waitfor, $c) !== false)) {
            return array($val, $n);  // return current value and state
         }
         
         #-= in-array
         elseif ($state===']') {
            list($v, $n) = json_decode($json, $assoc, $limit, $n, 0, ",]");
            $val[] = $v;
            if ($json[$n] == "]") { return array($val, $n); }
         }

         #-= in-object
         elseif ($state==='}') {
            list($i, $n) = json_decode($json, $assoc, $limit, $n, 0, ":");   // this allowed non-string indicies
            list($v, $n) = json_decode($json, $assoc, $limit, $n+1, 0, ",}");
            $val[$i] = $v;
            if ($json[$n] == "}") { return array($val, $n); }
         }

         #-- looking for next item (0)
         else {
         
            #-> whitespace
            if (preg_match("/\s/", $c)) {
               // skip
            }

            #-> string begin
            elseif ($c == '"') {
               $state = '"';
            }

            #-> object
            elseif ($c == "{") {
               list($val, $n) = json_decode($json, $assoc, $limit-1, $n+1, '}', "}");
               
               if ($val && $n) {
                  $val = $assoc ? (array)$val : (object)$val;
               }
            }

            #-> array
            elseif ($c == "[") {
               list($val, $n) = json_decode($json, $assoc, $limit-1, $n+1, ']', "]");
            }

            #-> comment
            elseif (($c == "/") && ($json[$n+1]=="*")) {
               // just find end, skip over
               ($n = strpos($json, "*/", $n+1)) or ($n = strlen($json));
            }

            #-> numbers
            elseif (preg_match("#^(-?\d+(?:\.\d+)?)(?:[eE]([-+]?\d+))?#", substr($json, $n), $uu)) {
               $val = $uu[1];
               $n += strlen($uu[0]) - 1;
               if (strpos($val, ".")) {  // float
                  $val = (float)$val;
               }
               elseif ($val[0] == "0") {  // oct
                  $val = octdec($val);
               }
               else {
                  $val = (int)$val;
               }
               // exponent?
               if (isset($uu[2])) {
                  $val *= pow(10, (int)$uu[2]);
               }
            }

            #-> boolean or null
            elseif (preg_match("#^(true|false|null)\b#", substr($json, $n), $uu)) {
               $val = $lang_eq[$uu[1]];
               $n += strlen($uu[1]) - 1;
            }

            #-- parsing error
            else {
               // PHPs native json_decode() breaks here usually and QUIETLY
              trigger_error("json_decode: error parsing '$c' at position $n", E_USER_WARNING);
               return $waitfor ? array(NULL, 1<<30) : NULL;
            }

         }//state
         
         #-- next char
         if ($n === NULL) { return NULL; }
         $n++;
      }//for

      #-- final result
      return ($val);
   }
}


/*
	Function: file_put_contents
		file_put_contents function for servers not using PHP 5
		Function based on http://freshmeat.net/projects/upgradephp, MIT-License
*/
if (!function_exists('file_put_contents')) {
	
	if (!defined('FILE_USE_INCLUDE_PATH')) define('FILE_USE_INCLUDE_PATH', 1);
	if (!defined('FILE_APPEND')) define('FILE_APPEND', 8);
	
   function file_put_contents($filename, $content, $flags=0, $resource=NULL) {

      #-- prepare
      $mode = ($flags & FILE_APPEND ? "a" : "w" ) ."b";
      $incl = $flags & FILE_USE_INCLUDE_PATH;
      $length = strlen($content);
//      $resource && trigger_error("EMULATED file_put_contents does not support \$resource parameter.", E_USER_ERROR);
      
      #-- write non-scalar?
      if (is_array($content) || is_object($content)) {
         $content = implode("", (array)$content);
      }

      #-- open for writing
      $f = fopen($filename, $mode, $incl);
      if ($f) {
      
         // locking
         if (($flags & LOCK_EX) && !flock($f, LOCK_EX)) {
            return fclose($f) && false;
         }

         // write
         $written = fwrite($f, $content);
         fclose($f);
         
         #-- only report success, if completely saved
         return($length == $written);
      }
   }
}

/*
	Function: scandir
		scandir function for servers not using PHP 5
		Function based on http://freshmeat.net/projects/upgradephp, MIT-License
*/
if (!function_exists("scandir")) {
   function scandir($dirname, $desc=0) {
   
      #-- check for file:// protocol, others aren't handled
      if (strpos($dirname, "file://") === 0) {
         $dirname = substr($dirname, 7);
         if (strpos($dirname, "localh") === 0) {
            $dirname = substr($dirname, strpos($dirname, "/"));
         }
      }
      
      #-- directory reading handle
      if ($dh = opendir($dirname)) {
         $ls = array();
         while ($fn = readdir($dh)) {
            $ls[] = $fn;  // add to array
         }
         closedir($dh);
         
         #-- sort filenames
         if ($desc) {
            rsort($ls);
         }
         else {
            sort($ls);
         }
         return $ls;
      }

      #-- failure
      return false;
   }
}

/*
	Function: stripos
		stripos function for servers not using PHP 5
		Function based on http://freshmeat.net/projects/upgradephp, MIT-License
*/
if (!function_exists("stripos")) {
   function stripos($haystack, $needle, $offset=NULL) {
   
      #-- simply lowercase args
      $haystack = strtolower($haystack);
      $needle = strtolower($needle);
      
      #-- search
      $pos = strpos($haystack, $needle, $offset);
      return($pos);
   }
}