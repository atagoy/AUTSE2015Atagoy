<?php
/** 
 * Classes for handling files
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/** 
* Main file handling class
* 
* @since 8.0.0
* 
* @author Ryan Bayne 
*/                                                 
//class C2P_Files extends CSV2POST { commented 14092014
class C2P_Files { 
    
    public function __construct() {
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' ); # php library by Ryan R. Bayne  
    }
    
    /**
    * Pass $_FILE[]['error'] and if it is > 0 an error message will be returned
    * 
    * @param mixed $file_error
    * 
    * @returns boolean false if no error else returns string indicating error
    */
    public function upload_error( $file_error ) {
        $upload_error_strings = array( false,
            __( "The uploaded file exceeds the <code>upload_max_filesize</code> directive in <code>php.ini</code>.", 'csv2post' ),
            __( "The uploaded file exceeds the <em>MAX_FILE_SIZE</em> directive that was specified in the HTML form.", 'csv2post' ),
            __( "The uploaded file was only partially uploaded.", 'csv2post' ),
            __( "No file was uploaded.", 'csv2post' ),
            __( "Missing a temporary folder.", 'csv2post' ),
            __( "Failed to write file to disk.", 'csv2post' ) );  
            
        return $upload_error_strings[ $file_error ];      
    }
    
    /**
    * Handles single file upload
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function singlefile_uploader( $file_array, $uploads = false, $test_size = true, $test_type = false, $overwrite = false ) {
        $result_array = array( 'outcome' => false,
                               'error' => false, 
                               'message' => __( 'The file was uploaded.', 'csv2post' ) );
        
        // if no $uploads array provided with path and other information then default to wp_upload_dir()
        if( $uploads === false ) { 
            $uploads = wp_upload_dir();
        }
        
        // ensure directory writeable - else use $uploads['error'] as the failure message
        if ( false !== $uploads['error'] ) {
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = $uploads['error'];
            return $result_array;
        }
                                                
        // handle failed upload, the returned array can be used to generate a notice that means something
        $is_file_error = self::upload_error( $file_array['error'] );
        if( $is_file_error ) {
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = $is_file_error;
            return $result_array;    
        }
              
        // test if file is empty
        if ( $test_size && !( $file_array['size'] > 0 ) ) {
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = __( 'Your file is empty. Empty files are not permitted in this operation.', 'csv2post' );
            return $result_array;            
        }

        // ensure temp file uploaded
        if (! @ is_uploaded_file( $file_array['tmp_name'] ) ) {
            $result_array['outcome'] = false;
            $result_array['error'] = true;
            $result_array['message'] = __( 'Sorry your file upload failed. A temporary file could not be created on the server. This may be a temporary problem, please try again.', 'csv2post' );
            return $result_array;                    
        }
        
        // test the file type and users permission to upload unknown types
        if ( $test_type ) {
            $wp_filetype = wp_check_filetype( $file_array['name'], false );
            extract( $wp_filetype );
            
            // if missing file info parts and user has no permission to upload anything they bloody like then stop
            if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) ) {
                $result_array['outcome'] = false;
                $result_array['error'] = true;
                $result_array['message'] = __( 'Your file does not meet security requirements and has not been uploaded.', 'csv2post' );
                return $result_array;                
            }
            
            // set extension
            if ( !$ext ) {
                $ext = ltrim( strrchr( $file_array['name'], '.' ), '.' );
            }
            
            // set the type using 
            if ( !$type ) {
                $type = $file_array['type'];
            }
        }
        
        // trailing slash is required
        $uploads['path'] = trailingslashit( $uploads['path'] );

        // handle a situation where file already exists
        if( file_exists( $uploads['path'] . $file_array['name'] ) ) {   
        
            // either overwrite or rename, renaming is done 
            if ( $overwrite ){             
                $filename = $file_array['name'];
                $overwrite_applied = true;      
            } else {        
               // create unique filename (this WP function also sanatizes)
               $filename = wp_unique_filename( $uploads['path'], $file_array['name'] );        
               $filerename_applied = true;                                   
            }
            
        } else {
            $filename = $file_array['name'];
        }    
                                    
        // make directory if it doesnt exist
        if ( !file_exists( $uploads['path'] ) ) {
            mkdir( $uploads['path'], 0777, true);
        }  
                            
        // move_uploaded_file - if it fails try a new file name            
        if ( false === @ move_uploaded_file( $file_array['tmp_name'], $uploads['path'] . $filename ) ) {
                $result_array['outcome'] = false;
                $result_array['error'] = true;
                $result_array['message'] = __( 'Your file could not be moved into the set directory. If a file with the same name exists in that directory an overwrite may not be possible.', 'csv2post' );
                return $result_array; 
        }    
        
        // need to return the final filepath 
        $result_array['filepath'] = $uploads['path'] . $filename;    
        
        // set chmod
        $stat = stat( dirname( $uploads['path'] . '/' . $filename ) );
        $perms = $stat['mode'] & 0000666;
        @ chmod( $uploads['path'] . '/' . $filename, $perms );
                
        // default is a true result
        $result_array['outcome'] = true;
        return $result_array;    
    }
        
    /**
    * Gets a file at a location indicates by URL
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    * 
    * @param string $url
    * @param array $uploads - array( 'path' => 'thepath/forfile/tobeput', 'url' => $url, 'subdir' => '', 'error' => false );
    * @param mixed $overwrite
    * @param mixed $rename
    * 
    * @returns array $result with boolean 'outcome' and string 'failurereason'
    */
    public function file_from_url( $url, $uploads = array(), $overwrite = false, $allow = false ) {
        $result = array( 'outcome' => false, 'error' => false, 'message' => 'not set' );
        $file = array();
        $overwrite_applied = false;
        $filerename_applied = false;
        
        // ensure $url is valid
        $url_is_valid = $this->PHP->validate_url( $url );
        if( !$url_is_valid ) {
            $result['message'] = __( 'URL is invalid', 'csv2post' );
            return false;    
        }
                          
        // get the submitted files name, store it as the originalName name because it may need to be changed 
        $path_parts = pathinfo( $url );
        $file['originalName'] = $path_parts['basename'];
        
        // use file_get_contents() and on failure else use curl functions
        if ( false === ( $data = @file_get_contents( $url ) ) ) {
            $curl = curl_init( $url );
            curl_setopt( $curl, CURLOPT_HEADER, 0 );  // ignore any headers
            ob_start();  // use output buffering so the contents don't get sent directly to the browser
            curl_exec( $curl );  // get the file
            curl_close( $curl );
            $data = ob_get_contents();  // save the contents of the file into $file
            ob_end_clean();  // turn output buffering back off
        }
    
        // if $uploads is empty use WordPress default uploads directory function wp_upload_dir()       
        if ( empty( $uploads ) ) {
            $uploads = wp_upload_dir();
        }
        
        // trailing slash is required
        $uploads['path'] = trailingslashit( $uploads['path'] );
        
        // make directory if it doesnt exist
        // I would like to see this logged and flagged to make user aware a folder is created
        if ( !file_exists( $uploads['path'] ) ) {
            mkdir( $uploads['path'], 0777, true);
        }

        // if WP error return $result array and error
        if ( false !== $uploads['error'] ) {
            $result['message'] = __( 'WordPress wp_upload_dir() returned an error.', 'csv2post' );
            $result['error'] = $uploads['error'];
            return $result;    
        } 
            
        // handle a situation where file already exists
        if( file_exists( $uploads['path'] . $file['originalName'] ) ) {   
        
            // either overwrite or rename, renaming is done 
            if ( $overwrite ){
                
                $filename = $file['originalName'];
                $overwrite_applied = true;
                
            } else {
                
               // create unique filename (this WP function also sanatizes)
               $filename = wp_unique_filename( $uploads['path'], $file['originalName'] );        
               $filerename_applied = true;
                                        
            }
            
        } else {
            $filename = $file['originalName'];
        }             
        
        $write_result = self::write( $uploads['path'] . $filename, $data ); 
    
        // if cannot write, rename the $filename again
        if( $write_result === false ) {
            
            if( file_exists( $uploads['path'] . $file['originalName'] ) ) {
                $result['message'] = __( 'The filename already exists and the file cannot be overwritten.', 'csv2post' );
                return $result;                 
            }
            
        } elseif( $write_result === false && $rename !== true ) {
            $result['failurereason'] = __( 'File already exists and renaming is not allowed.', 'csv2post' );
            return $result;                
        }
        
        // The file permissions are and-ed with the octal value 0000666 to make
        // sure that the file mode is no higher than 666. In other words, it locks
        // the file down, making sure that current permissions are no higher than 666,
        // or owner, group and world read/write.
        $stat = stat( dirname( $uploads['path'] . $filename ) );
        $perms = $stat['mode'] & 0000666;
        @ chmod( $uploads['path'] . '/' . $filename, $perms );

        // arriving here should mean operation is a success
        $result['outcome'] = true;
        $result['filepath'] = $uploads['path'] . $filename;    
        $result['error'] = false;
        
        // create suitable message based on what happened, the user needs to know how to proceed
        $message = __( 'The file was transferred.', 'csv2post' ); 
        
        if( $overwrite_applied ) {
            $message .= ' ' . __( 'An existing file was overwritten as requested.', 'csv2post' );
        }
        
        if( $filerename_applied ) {
            $message .= ' ' . __( 'The files name had to be changed as it already exists.', 'csv2post' );    
        }

        $result['message'] = $message;

        return $result;
    }
    
    /**
    * write file
    * 
    * @uses fopen(), fwrite(), fclose()
    * @returns boolean
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function write( $path, $data ) {
        
        // open the path
        if ( false !== ( $destination = @fopen( $path, 'w' ) ) ) {
            
            if ( fwrite( $destination, $data ) ) {
                
                @fclose( $destination );
                return true;
                
            }
            
            @fclose( $destination );
        }
        
        return false;    
    }

    /**
    * Compares the configuration of two .csv files
    * 
    * @returns $result_array
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function compare_csv_files( $filepath_one, $filepath_two ) {
        $result_array = array( 'outcome' => false,
                               'error' => false, 
                               'message' => '' );
                               
                               $message_set = false;
                              
        // establish separators
        $file_one_sep = self::established_separator( $filepath_one );        
        $file_two_sep = self::established_separator( $filepath_two );
        
        if( $file_one_sep !== $file_two_sep ) {
            $result_array['outcome'] = false;
            $result_array['error'] = false;
            // add a space if the message already has text
            if( $message_set === true ) { $result_array['message'] .= ' '; }
            $result_array['message'] = 'The separator for file one is ' . $file_one_sep . ' but
            the separator for file two is ' . $file_two_sep . '. The plugin is not ready to deal
            with a switch to a file with different configuration of this kind.';
            $message_set = true;
        }
        
        // create SplFileObject for each file
        $file_one_SplFileObject = new SplFileObject( $filepath_one );
        $file_two_SplFileObject = new SplFileObject( $filepath_two );
        
        // get each files header 
        while (!$file_one_SplFileObject->eof() ) {
            $header_array = $file_one_SplFileObject->fgetcsv( $file_one_sep, '"' );
            break;// we just need the first line to do a count()
        }       
        while (!$file_two_SplFileObject->eof() ) {
            $header_array = $file_two_SplFileObject->fgetcsv( $file_two_sep, '"' );
            break;// we just need the first line to do a count()
        }       
        
        // now compare the arrays, return false if they do not match true if they do
        $result_array['outcome'] = true;
        $result_array['error'] = false;
        $result_array['message'] = __( 'Both .csv file headers match and use the same separator. The datasource
        can switch to the newer file if required.', 'csv2post' );      
        
        return $result_array;
    } 
    
    /**
    * Counts separator characters per row, compares total over all rows counted to determine probably Separator
    * 
    * @param mixed $filepath
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.0.2
    */
    public function established_separator( $filepath ){
        $probable_separator = ','; 
        if ( ( $handle = fopen( $filepath, "r" ) ) !== FALSE ) {

            // count Separators
            $comma_count = 0;
            $pipe_count = 0;
            $semicolon_count = 0;
            $colon_count = 0;          

            // one row at a time we will count each possible Separator
            while ( ( $rowstring = fgets( $handle, 4096 ) ) !== false ) {
                
                $comma_count = $comma_count + substr_count ( $rowstring , ',' );
                $pipe_count = $pipe_count + substr_count ( $rowstring , '|' );                    
                $semicolon_count = $semicolon_count + substr_count ( $rowstring , ';' );
                $colon_count = $colon_count + substr_count ( $rowstring , ':' ); 
                            
            }  
            
            if ( !feof( $handle) ) {
                wp_die( 'Please take a screenshot of this message. The PHP function feof() returned false for
                some reason and I would really like to know about it.', 'Something Went Wrong' );
            }
            fclose( $handle);                

            // compare count results - comma
            if( $comma_count > $pipe_count && $comma_count > $semicolon_count && $comma_count > $colon_count )
            {       
                $probable_separator = ',';
            }
            
            // pipe
            if( $pipe_count > $comma_count && $pipe_count > $semicolon_count && $pipe_count > $colon_count )
            {        
                $probable_separator = '|';       
            }
            
            // semicolon
            if( $semicolon_count > $comma_count && $semicolon_count > $pipe_count && $semicolon_count > $colon_count )
            {    
                $probable_separator = ';';        
            }
            
            // colon
            if( $colon_count > $comma_count && $colon_count > $pipe_count && $colon_count > $semicolon_count )
            {
                $probable_separator = ':';      
            }
            
        }// if handle open for giving file
        
        return $probable_separator; 
    }  
    
    /**
    * Gets the newest file in a directory
    * 
    * @uses filectime()
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function directories_newest_file( $dir, $extension = false ) {
            
        $latest_ctime = 0;
        $latest_filename = '';    

        $d = dir( $dir );
        while ( false !== ( $entry = $d->read() ) ) {
            
            $filepath = "{$dir}/{$entry}";
            
            // if focusing on a specific extension
            if( is_string( $extension ) ) {
                if( pathinfo( $filepath, PATHINFO_EXTENSION ) !== $extension ) {
                    continue;
                }              
            }
            
            // could do also other checks than just checking whether the entry is a file
            if( is_file( $filepath ) && filectime( $filepath ) > $latest_ctime ) {
                $latest_filename = $entry;
            } 
            
        }
        
        return $latest_filename;
    }
    
    /**
    * Gets the newest file in a directory
    * 
    * @uses filemtime()
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function directories_lastedited_file( $dir, $extension = false ) {
            
        $latest_ctime = 0;
        $latest_filename = '';    

        $d = dir( $dir );
        while ( false !== ( $entry = $d->read() ) ) {
            
            $filepath = "{$dir}/{$entry}";
            
            // if focusing on a specific extension
            if( is_string( $extension ) ) {
                if( pathinfo( $filepath, PATHINFO_EXTENSION) !== $extension ) {
                    continue;
                }              
            }
            
            // could do also other checks than just checking whether the entry is a file
            if( is_file( $filepath ) && filemtime( $filepath ) > $latest_ctime ) {
                $latest_filename = $entry;
            } 
            
        }
     
        return $latest_filename;
    }     
}  
?>
