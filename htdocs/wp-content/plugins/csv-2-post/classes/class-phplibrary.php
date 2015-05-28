<?php
/** 
 * General PHP function library - I welcome any improvements you can suggest
 * 
 * where possible we really want to replace the use of these with WP core functions
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
//class CSV2POST_PHP extends C2P_Flags { commented 14092014
class CSV2POST_PHP {  
    /**
    * Performs a var_dump if debug_mode active (debug_mode is only active when on installation blog unless manually activated)
    */
    public function var_dump( $value, $header = false, $append = '<br>' ){
        global $c2p_debug_mode;
        if( $c2p_debug_mode){
            if( $header !== false ){echo '<h1>'.$header.'</h1>';}
            echo '<pre>';
            var_dump( $value );
            echo '</pre>';
            echo $append;
        }
    }    
  
    /**
    * Checks if a directory is empty
    * 1. Returns NULL if directory is not readable
    * 2. Avoid actions on directory if NULL or 0 returned
    * 
    * @param mixed $dir
    */ 
    public function dir_is_empty( $dir) {
        if (!is_readable( $dir) ){
            return NULL;
        }else{
            return (count(scandir( $dir) ) == 2);
        }
    }    
   
    /**
     * Counts rows in CSV file and returns (does no include header row)
     * @uses eci_csvfileexists
     * @param filename $filename
     * @param array $pro
     */
    public function count_csvfilerows( $route, $file, $include_header = false ){ 
        $counted = count(file( $route . $file) ); 
        if( $include_header == false ){
            $counted = $counted - 1;
        }  
        return $counted;
    }    
   
    /**
     * Formats number to a size for interface display, usually bytes returned from checking a file size
     * @param integer $size
     */
    public function format_file_size( $size) {
          $sizes = array( " Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
          if ( $size == 0) { 
              return( 'n/a' ); 
          } else {
              return (round( $size/pow(1024, ( $i = floor(log( $size, 1024) )) ), $i > 1 ? 2 : 0) . $sizes[$i] ); 
          }
    }    
   
    /**
     * Returns cleaned string for use as filename - we remove all characters for the sake of shortenening
     * @param string $filename
     */
    public function cleanfilename( $filename ){
        $remove_these = array( '-', '_', ' ', ' )', '( ',);
        $filename = str_replace ( $remove_these , '' ,  $filename );
        return $filename;
    }
   
    /**
     * Checks if an extension is loaded on the server
     * @uses get_loaded_extensions()
     * @param string $giving_extension (name of the extension)
     * @return boolean (if extension is loaded returns true)
     */
    public function is_extensionloaded( $giving_extension){
        $loaded_extensions = get_loaded_extensions();
        foreach( $loaded_extensions as $key => $extension){
            if( $extension == $giving_extension){
                return true;
            }
        }
        return false;
    }    
   
    /**
    * Determines if a string is alphanumeric (English characters) or not
    */
    public function alphanumeric( $string, $minimum_length = 1, $maximum_length = 99){ 
        if (preg_match( '/^[A-Z0-9]{'.$minimum_length.', '.$maximum_length.'}$/i', $string) ) {
            return true;
        } else {
            return false;
        }     
    }    
  
    /**
    * PHP strtotime does not allow UK time, it treats it as US format.
    * 
    * @param mixed $format This function allows specification of the US or UK or other format.
    */
    public function strto_timeformat( $date_string, $format = 'UK' ){
        if( $format == 'US' ){
            return strtotime( $date_string);    
        }elseif( $format == 'UK' ){
            $date_explode = explode( "/", $date_string);
            return mktime(0,0,0, $date_explode[1], $date_explode[0], $date_explode[2] );     
        }else{
            return strtotime( $date_string);    
        }    
    }    
  
    /**
    * Compares giving possible/expected prepend (needle) characters to haystack string and determines if the
    * needle exists at the beginning of the haystack.
    * 
    * @returns boolean true means needle exists at the beginning of the haystick, false indicates it does not
    */
    public function prepend_exists( $haystack, $needle){
        $strncmp_abc = strncmp( $haystack, $needle,strlen( $needle) );// 0 means there is a match (-1 or 1 means one string is greater than other in matching characters)
        if( $strncmp_abc == 0){return true;}else{return false;}                
    }
  
    /**
    * get multiple token strings within a large string
    * 1. currently requires string to be wrapped in asterix
    * 2. the returned value must be unique to the data value expected to replace it
    * 3. functions used to replace tokens will use array that includes the token strings as key
    * 
    * @param mixed $string
    */
    public function get_many_strings_in_string( $string){                      
        if(preg_match_all( "/\*(.*?)\*/", $string, $match) ) {            
            return $match[1];            
        }
        return false;
    }    
  
    /**
    * Prepares a string for returning as a numeric
    * 
    * This is mainly used to remove spaces/whitespace from user input on forms. There is
    * no check for none numeric in this function.
    * 
    * @package CSV 2 POST
    * @since 8.0.0  
    */
    public function clean_numeric( $string ) {
        $string = str_replace( ' ', '', $string);
        return trim( $string);
    }    
  
    /**
     * Returns the current url as viewed with all variables etc
     * @return string current url with all GET variables
     */
    public function currenturl() {
        $pageURL = 'http';
        if (isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        
            $pageURL .= "://";
            
        if (isset( $_SERVER["SERVER_PORT"] ) && $_SERVER["SERVER_PORT"] != "80" && isset( $_SERVER["SERVER_NAME"] ) && isset( $_SERVER["REQUEST_URI"] ) ) {

            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

        }elseif( isset( $_SERVER["SERVER_NAME"] ) && isset( $_SERVER["REQUEST_URI"] ) ){
            
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            
        }else{
            
            return 'Error Unexpected State In Current URL Function';
            
        }
        return $pageURL;
    }    
   
    /**
    * Creates random code or can be used to make passwords
    */
    public function create_code( $length = 10, $specialchars = false ) { 

        if( $specialchars){
            $chars = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789£%^&*";        
        }else{
            $chars = "abcdefghjkmnopqrstuvwxyz023456789";
        }
         
        srand((double)microtime()*1000000); 
        $i = 0; 
        $pass = '' ; 

        $length = $length - 1;
        
        while ( $i <= $length) { 
            $num = rand() % 33; 
            $tmp = substr( $chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 

        return $pass; 
    } 
   
    /**
    * Returns human readable time passed since giving date.
    * Years,months etc all separated with comma and as plurals where required.
    * 
    * PHP 5.3 or above only
    * 
    * @param mixed $datetime 
    * @param boolean $use_year (this will only be used if value is not 0)
    * @param boolean $use_month (this will also only be used if value is not 0)
    * @param boolean $use_day
    * @param boolean $use_hour
    * @param boolean $use_minute
    * @param boolean $use_second (false by default)
    */
    public function how_long_ago( $datetime, $use_year = true, $use_month = true, $use_day = true, $use_hour = true, $use_minute = true, $use_second = false ){
     
        // PHP 5.3 method is currently the best             
        $interval = date_create( 'now' )->diff( $datetime );
        
        $ago_string = ' ';
                 
        // year
        if( $use_year){
            if ( $interval->y >= 1 ){
                $ago_string .= $this->pluralize( $interval->y, 'year' );        
            } 
        }

        // month
        if( $use_month){
            if ( $interval->m >= 1 ){
                $ago_string_with_comma_month = $this->ago_commas( $ago_string); 
                $ago_string = $ago_string_with_comma_month . $this->pluralize( $interval->m, 'month' );        
            } 
        }  
            
        // day
        if( $use_day ){
            if ( $interval->d >= 1 ){
                $ago_string_with_comma_day = $this->ago_commas( $ago_string);            
                $ago_string = $ago_string_with_comma_day . $this->pluralize( $interval->d, 'day' );        
            } 
        }
        
        // hour
        if( $use_hour){
            if ( $interval->h >= 1 ){
                $ago_string_with_comma_hour = $this->ago_commas( $ago_string);            
                $ago_string = $ago_string_with_comma_hour . $this->pluralize( $interval->h, 'hour' );        
            } 
        }       
     
        // minute
        if( $use_hour){
            if ( $interval->m >= 1 ){
                $ago_string_with_comma_minute = $this->ago_commas( $ago_string);            
                $ago_string = $ago_string_with_comma_minute . $this->pluralize( $interval->m, 'minute' );        
            } 
        }

        // second
        if( $use_second){
            if ( $interval->s >= 1 ){
                $ago_string_with_comma_second = $this->ago_commas( $ago_string);            
                $ago_string = $ago_string_with_comma_second . $this->pluralize( $interval->s, 'second' );        
            } 
        }    
        
        return $ago_string;
    }
   
    /**
    * Adds an 's at the end of a string if pluralize required. Used where a total number of something 
    * is being displayed on UI.
    * 
    * Requires a count of what the string equals i.e. 2 and apple to make "2 apple's". Can remove the
    * number to make "apple's" and in a situation where the count may vary.
    * 
    * @param integer $count for use in procedures where the total may vary
    * @param mixed $prepend_count pass false to prevent count being added to the beginning of string
    */
    public function pluralize( $count, $text, $prepend_count = true ) { 
        $string = '';
        if( $prepend_count){$string .= $count;}
        if( $count > 1){$string .= " $text";}else{$string .= " ${text}s";}    
        return $string;
    }       
  
    /**
    * Adds comma to the end of giving string based on what has already been added to the string value. 
    */
    public function ago_commas( $originalstring){
        if( $originalstring != ' ' ){$result = $originalstring . ', ';return $result;}else{return $originalstring;}    
    }
  
    /**
    * truncate string to a specific length 
    * 
    * @param string $string, string to be shortened if too long
    * @param integer $max_length, maximum length the string is allowed to be
    * @return string, possibly shortened if longer than
    */
    public function truncatestring( $string, $max_length ){
        if (strlen( $string) > $max_length) {
            $split = preg_split( "/\n/", wordwrap( $string, $max_length) );
            return ( $split[0] );
        }
        return ( $string );
    }
   
    /**
    * Formats number into currency, default is en_GB and no GBP i.e. not GBP145.50 just 145.50 is returned
    * 
    * @param mixed $s
    */
    public function format_money( $s ){
        setlocale(LC_MONETARY, 'en_GB' );
        return money_format( '%!2n', $s) . "\n";        
    } 
  
    /**
    * Validate a url (http https ftp), not for paths to files, only for web pages and directories
    * 
    * @return true if valid false if not a valid url
    * @param url $url
    * 
    * @deprecated use is_url()
    */
    public function validate_url( $url ){
        return self::is_url( $url );
    }
            
    /**
    * Checks if value is valid a url (http https ftp)
    * 1. Does not check if url is active
    * 2. Removes a filename if exists
    * 
    * @uses dirname()
    * @return true if valid false if not a valid url
    * @param url $url
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.2
    */
    public function is_url( $url ){
        if (!preg_match( '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', dirname( $url )) ){
            return false;
        } else {
            return true;
        }
    }
   
    /**
    * Uses preg_match pattern to determine if string is a valid domain with top level included
    * 
    * @param mixed $domain_name
    */
    public function is_valid_domain_name( $domain_name ){
        return (preg_match( "/^([a-z\d](-*[a-z\d] )*)(\.([a-z\d](-*[a-z\d] )*) )*$/i", $domain_name) //valid chars check
                && preg_match( "/^.{1,253}$/", $domain_name) //overall length check
                && preg_match( "/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }      
   
    /**
    * WebTechGlobal array info function.
    * Returns array for updating another array. This is a standard set of values used to maintain 
    * stored arrays and it also helps in debugging.
    * 
    * @param mixed $line
    * @param mixed $function
    * @param mixed $file
    * @param mixed $reason
    * @param mixed $version
    */
    public function arrayinfo_set( $array, $line, $function, $file ){
        global $csv2post_filesversion;
        $array['arrayinfo']['version'] = $csv2post_filesversion;
        $array['arrayinfo']['line'] = $line;
        $array['arrayinfo']['function'] = $function;
        $array['arrayinfo']['file'] = $file;
        $array['arrayinfo']['date'] = CSV2POST::datewp();
        return $array;   
    }  
   
    /**
    * Establishes if an arrays element count is odd or even (currently divided by 2)
    * For using when balancing tables
    * @param array $array
    */
    public function oddeven_array( $array ){
        $oddoreven_array = array();

        // store total number of items in totalelements key
        $oddoreven_array['totalelements'] = count( $array );

        // store the calculation result from division before rounding up or down, usually up
        $oddoreven_array['divisionbeforerounded'] = $oddoreven_array['totalelements'] / 2;

        // round divisionbeforerounded using ceil and store the answer in columnlimitceil, this is the first columns maximum number of items
        $oddoreven_array['columnlimitceil'] = ceil( $oddoreven_array['divisionbeforerounded'] );

        // compare our maths answer with the ceil value - if they are not equal then the total is odd
        // if the total is oddd we then know the last column must have one less item, a blank row in the table
        if( $oddoreven_array['divisionbeforerounded'] == $oddoreven_array['columnlimitceil'] ){
            $oddoreven_array['balance'] = 'even';
        }else{
            $oddoreven_array['balance'] = 'odd';
        }

        return $oddoreven_array;
    } 
   
    /**
    * Returns MySQL version 
    */
    public function mysqlversion() { 
        $output = shell_exec( 'mysql -V' );    
        if(!$output){return 'Unknown';}
        preg_match( '@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
        return $version[0]; 
    }
   
    /**
    * Creates a new directory (folder) using giving path, validation is expected to have been done already
    * @param uri $pathdir
    * @param numeric $per (chmod permissions)
    */
    public function createfolder( $path, $chmod = 0700){
        if(mkdir( $path,0700, true) ){
            chmod( $path, $chmod);
            return true;
        }else{
            return false;
        }
    } 
    
    /**
    * Determines if giving path contains any files with the giving extension
    * 1. Extension value should not have . passed with it 
    * 2. Faster than csv2post_count_extension_in_directory() because it returns sooner, important for directory with a lot of files
    * 
    * @returns boolean
    */
    public function does_folder_contain_file_type( $path, $extension ){
        $all_files  = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) );
        $html_files = new RegexIterator( $all_files, '/\.'.$extension.'/' );                        
        foreach( $html_files as $file) {
            return true;// a file with $extension was found
        }    
        return false;// no files with our extension found
    }
   
    /**
    * Counts number of files with giving extension
    */
    public function count_extension_in_directory() {
        $all_files  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( $path) );
        $html_files = new RegexIterator( $all_files, '/\.'.$extension.'/' );
        $count = 0;                        
        foreach( $html_files as $file) {
            ++$count;
        }    
        return $count;
    } 
    
    public function stringinstring_using_strpos( $needle, $haystack ){
        return @strpos( $haystack, $needle) !== false;
    }
    
    /**
    * Get part of string after the last occurence of giving character.
    * 
    * This approach accepts a string with the special character used any number of times. Some
    * approaches giving online are suitable for specific strings i.e. one or two instances of the character.
    * This one simply splits the string into array based on the character then returns the end value.
    * 
    * @uses explode(),end()
    * @param mixed $string
    * @param mixed $character
    */
    public function get_string_after_last_character( $string, $character ){
        $explode = explode( $character, $string);
        return end( $explode);
    }
    
    public function str_replaceFirst( $s, $r, $str){
        $l = strlen( $str);
        $a = strpos( $str, $s);
        $b = $a + strlen( $s);
        $temp = substr( $str,0, $a) . $r . substr( $str, $b,( $l-$b) );
        return $temp;
    }  
    
    /**
    * Get the string between two giving characters
    * 
    * @param string $start_limiter, start character
    * @param string $end_limiter, end character
    * @param string $haystack, string to be searched
    * @return string or false on failure
    * 
    */
    public function get_string_between_two_characters( $start_limiter, $end_limiter, $haystack ){
        $start_pos = strpos( $haystack, $start_limiter);
        if ( $start_pos === false ){
            return false;
        }

        $end_pos = strpos( $haystack, $end_limiter, $start_pos);

        if ( $end_pos === false ){
            return false;
        }

        return substr( $haystack, $start_pos+1, ( $end_pos-1)-$start_pos);
    }
    
    /**
    * Determines if a string matches a known format. Meant for established intended purpose of a value.
    * 1. url
    * 2. image
    * 3. number
    * 4. text
    * 5. money
    * 6. decimal
    * 7. email
    * @returns unknown if could not establish types
    */
    public function type( $value ){
        if( CSV2POST::is_image_url( $value ) ){
            return 'image';
        }
        
        // so its not an image url, is it any type of url? either for using as link or PHP functionality
        if( CSV2POST::is_url( $value ) ){
            return 'url';    
        }
        
        // check if decimal before checking if a plain number or money
        if( CSV2POST::is_decimalnumber( $value ) ){
            return 'decimal';
        }

        // is money value
        if( CSV2POST::is_string_money( $value ) ){
            return 'money'; 
        }
                  
        // is not money, is it even a number?  
        if(is_numeric( $value ) ){
            return 'numeric';
        }
        
        // is email address
        if( CSV2POST::is_valid_emailaddress( $value ) ){
            return 'email';
        }                  
        // it must be text but we will do the check anyway
        if(is_string( $value ) ){
            return 'text';
        }
                       
        return 'unknown';# should never happen    
    } 
       
    public function is_valid_emailaddress( $value ){
        if(filter_var( $value, FILTER_VALIDATE_EMAIL) ) {
            return true;
        }
        else {
            return false;
        }    
    }  
       
    public function is_string_money( $value, $currency = 'nonespecified' ){
        // get first character and test it unless specific currency enforced
        if( $currency == 'nonespecified' ){
                    
            // is first character numeric or a letter, if so this is not money it may just be decimal (numeric), return false
            if(is_numeric( $value['0'] ) || ctype_alpha ( $value['0'] ) ){ 
                return false;
            }
            
            // lets assume the first character, which we know is not numeric, is a currency symbol
            // remove it and test the rest of the $value to determine if that is numeric
            if( strstr( $value, '£' ) ){
                $possiblenumeric = substr( $value,2);       
            }else{    
                $possiblenumeric = substr( $value,1);
            }
            
            // is the remaining string a number, which would indicate a high possibility that the value is money    
            if(is_numeric( $possiblenumeric) ){
                return true;
            }
           
        }else{
            // strings first character must be = $currency for a true else false    
        }
        return false;
    }
    
    /**
    * Determines if numeric value is decimal.
    * 1. checks if value is actually numeric first
    * @returns boolean
    */
    public function is_decimalnumber( $img_url){
        return is_numeric( $img_url ) && floor( $img_url ) != $img_url;    
    }     
    
    /**
    * Checks if url has an image extension, does not validate that resource exists
    * @returns boolean
    */
    public function is_image_url( $img_url){
        $img_formats = array( "png", "jpg", "jpeg", "gif", "tiff", "bmp"); 
        $path_info = pathinfo( $img_url);
        if(is_array( $path_info) && isset( $path_info['extension'] ) ){
            if (in_array(strtolower( $path_info['extension'] ), $img_formats) ) {
                return true;
            }
        }
        return false;
    } 
    
    /**
    * Get time() for the beginning and end of a giving month within a giving year.
    * 
    * @uses date( 't' ) to get the number of days in the giving month and year
    * 
    * @param mixed $month
    * @param mixed $year
    * @param mixed $return
    * @param mixed $format
    */
    public function months_time_range( $month, $year, $return = 'array', $format = 'time' ){
        $array = array();
        
        // create a date range that spans a month whatever the months total days 
        $array['start'] = mktime(0,0,0, $month,1, $year);                                                         
        if( $format == 'date' )
        {
            $array['start'] = CSV2POST::datewp(0, $array['start'] );
        }
        
        // t = number of days in giving month   -  set timestamp only to point to correct month "1" does not matter
        $array['end'] = $end_time = mktime(23,59,59, $month,date( 't',strtotime( $month . '/1/'.$year) ), $year);
        if( $format == 'date' )
        {    
            $array['end'] = CSV2POST::datewp(0, $array['end'] );   
        }
        
        if( $return == 'start' )
        {
            return $array['start'];
        }
        elseif( $return == 'end' )
        {
            return $array['end'];
        }
        else
        {
            return $array;
        }
    }  
    
    /**
    * Used for testing/debugging. Dumps the giving __LINE__ with a string of text to help locate
    * the use of the function if not removed. 
    * 1. Does not print anything if debug_mode not on, this helps to keep the interface clean if these are left in functions 
    * 
    * @param mixed $line
    * @param mixed $string
    */
    public function line_dump( $line, $string = 'Line Number: ' ){
        global $c2p_debug_mode;
        if( $c2p_debug_mode){
            print $string .' '. $line .'<br>';
        }
    }  
    
    /**
    * remove any value from an array without knowing the key
    * 
    * @param mixed $array
    * @param mixed $value
    */
    public function remove_array_value( $array, $value ){
        if(( $key = array_search( $value, $array ) ) !== false ) {
            unset( $array[$key] );
        }    
        return $array;
    }  
    
    /**
    * extract the domain from a string i.e. domain.com would come from http://www.domain.com/blog
    * 
    * @param mixed $url
    */
    public function extract_domain ( $url ) { 
        $url = trim( $url);
        $url = preg_replace( "/^(http:\/\/)*(www.)*/is", "", $url); 
        $url = preg_replace( "/\/.*$/is" , "" , $url); 
        return $url; 
    }
    
    /**
    * Removes special characters and converts to lowercase (very strict)
    */
    public function clean_string( $string ){ 
        $string = preg_replace( "/[^a-zA-Z0-9s]/", "", $string);
        return strtolower ( $string );
    }
    
    /**
     * Returns a cleaned string so that it is suitable to be used as an SQL column or table name
     * @param string $column (characters removed = , / \ . - # _ and spaces)
     * 
     * @version 0.2 - now uses preg_replace and also allows underscore
     * @author Ryan R. Bayne
     */
    public function clean_sqlcolumnname( $column ){
        $string = preg_replace( "/[^a-zA-Z0-9s_]/", "", $column);
        return strtolower ( $string );        
    }  
        
}// end class CSV2POST_PHP
?>
