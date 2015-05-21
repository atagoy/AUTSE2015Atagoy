<?php
/** 
 * Class for WordPress category related functions 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 * @version 1.0.0
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

 
/**
 * Categories for WordPress
 * 
 * Create, modify and search categories in ways that plugins can use 
 * 
 * Has no involvement of handling form submissions for category related settings etc
 * 
 * @author Ryan R. Bayne
 * @package CSV 2 POST
 * @since 8.1.3
 * @version 1.0.0
 */
class C2P_Categories {

    public function __construct() {
        $this->DB = CSV2POST::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );    
    }
    
    /**
    * Establishes the code level (zero is level one) of an existing category
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function level( $category_id ) {
        $parents = get_category_parents( $category_id, false, '/' );
        
        $parents = explode( '/', $parents );      
        
        // remove the empty value, get_category_parents() returns string that ends with separator always
        // however if they change that behaviour it would cause problems so deducting 1 should be avoided
        $parents = array_diff( $parents, array( '', ' ' ) );
        
        $parents = count( $parents ); 
        
        // in code the levels will begin at zero, unlike UI, so deduct 1 
        // vale of 4 means level 5
        return $parents - 1; 
    }   
    
    /**
    * match a string (intended term) to a possible existing term that is in the same group as an $other_category_id
    * 
    * Function queries term name, establishes the level of each matching term (not enough
    * to validate a parent) then checks if related categories (both parents and children) contains a specific
    * category ID. Use this when we have a category ID that is not the direct parent or child of a category
    * that either exists or needs to be created.
    *   
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    * 
    * @returns false if no term exists else returns existing term (category) id
    * 
    * @param string $term_name
    * @param integer $level this should be 0|1|2|3|4
    * @param $other_category_id this is the ID of a category that is strongly related to $term_name
    */
    public function match_term_byrelated( $term_name, $level, $other_category_id ) {
        global $wpdb, $CSV2POST;
    
        // query all term names matching $term_string (it may return many, from different levels, with different parents)
        $all_terms_array = $this->DB->selectwherearray( $wpdb->terms, "name = '$term_name'", 'term_id', 'term_id' );
        
        // if none then return false, we may then create the category queried for example
        if( !$all_terms_array ){
            return false;
        }
                
        // loop through the terms with name $term_name, get the level of the term and return its ID if it is a match
        foreach( $all_terms_array as $key => $this_term_array ){
            
            // get all related categories (related to current term not our $other_category_id)
            $related_categories = self::get_related_categories( $this_term_array['term_id'] );
           
            // if $other_category_id is not in this array then continue, it means
            if( !in_array( $other_category_id, $related_categories ) ){     
                continue;
            }
            
            // get the level for this term
            $terms_level = self::level( $this_term_array['term_id'] );

            // if this $terms_level is match to $level then we have the exact category match by group and level
            if( $terms_level === $level ){
                return $this_term_array['term_id'];
            }
            
        } 
            
        // no terms match $term_name or no existing $term_name are in this $level
        return false; 
    }
    
    /**
    * gets all parents and child categories for the category passed
    * 
    * @returns array parent and children array merged to return a general array of related categories
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function get_related_categories( $category_id ){
        
        // get the categories parents
        $parent_array = self::get_category_parents_id( $category_id, false, '/', false );
        
        // remove empty values as is usually returned by get_category_parents()
        $parent_array = array_diff( $parent_array, array( '', ' ' ) );
        
        // get the categories children
        $child_string = get_category_children( $category_id, '/' );
        
        // turn string to array for the merge
        $child_array = explode( '/', $child_string );
        
        // remove possible empty values (not sure if the child function returns them as the parent one does
        $child_array = array_diff( $child_array, array( '', ' ' ) );
        
        // return a merge
        return array_merge( $parent_array, $child_array );
    }
    
    /**
    * gets a categories parents (every level upwards) and returns ID
    * use WordPress own get_category_parents() if you want to return the term names.
    * 
    * this function assumes that the $category_id exists as a term id 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    * 
    * @param integer $category_id
    * @param array $category_parents_array we do not normally use this, it is used by this function itself
    */
    public function get_category_parents_id( $category_id, $category_parents_array = array() ) {
        $term = get_term( $category_id, 'category' );
        
        if( isset( $term->parent ) && $term->parent !== 0 ){
            
            $category_parents_array[] = $term->parent;   
            
            $category_parents_array = self::get_category_parents_id( $term->parent, $category_parents_array );                  
        }

        // arriving here means the current term is level one, return the final array
        return $category_parents_array;
    }
    
    /**
    * start of category creating for a single set 
    * 
    * 1. can pass a pre-set parent which exists in any level does not need to be a top level parent
    * 2. pre-set parent should exist already but this function is ready to create it
    * 3. mapping is not performed here, the array of categories is instead modified based on mapping settings
    * 
    * @returns array of category ID's, 
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    * 
    * @param array $new_categories_array the term names (not slugs or ID's and do not pass a pre-set parent)
    * @param array $preset_parent pass the pre-set parent term name and ID as it should exist, but this function will create it as a top level parent if it doesnt.
    */
    public function create_categories_set( array $new_categories_array, $preset_parent = false ){
        // build an array for returning, it could be used to apply a post to categories
        $category_set_array = array();
        
        // count terms added to final array
        $terms_added = 0;
                
        // we need to set $category_set_array[0] without our category sets parent (does not need to be top level)
        if( is_numeric( $preset_parent ) ){
            
            // first item in array is the sets parent, the array key is not the level within the blog do not use it
            $category_set_array[0] = $preset_parent;    
            ++$terms_added;
                                                   
        }else{
            
            // no preset parent means we begin with a top level parent
            $parent_id = term_exists( $new_categories_array[0], '', 0 );
            
            // create the parent if it does not exist
            if( !$parent_id ){
                
                // create the term which serves are parent
                $new_term_id = wp_insert_term( $new_categories_array[0], 'category', array( 'parent' => 0 ) );
              
                // add the new term id to the final category which will be returned
                if( is_array( $new_term_id ) ){
                    $category_set_array[0] = $new_term_id['term_id'];
                    ++$terms_added;
                }
                
            }elseif( is_numeric( $parent_id ) ){
                $category_set_array[0] = $parent_id;
                ++$terms_added;
            }
        }
              
        // loop through categories and create or get existing ID's
        foreach( $new_categories_array as $key => $category ) { 
            
            // skip the first item in array unless $preset_parent is in use, then the first item is a child
            if( !$preset_parent && $key === 0 ){ continue; }
            
            // get previous terms array key for using the terms ID as this current terms parent
            // avoid -1 which generates errors as it is not a defined offset
            if( $terms_added > 0 ) {                
                $parent_key = $terms_added - 1;
            }
            
            // does the new term exist
            $term_id = term_exists( $category, '', $category_set_array[ $parent_key ] );

            if( !$term_id ){
                
                // new term needs to be inserted using previous term as parent
                $new_term_id = wp_insert_term( $category, 'category', array( 'parent' => $category_set_array[ $parent_key ] ) );
               
                // array is returned with taxonomy id and term id
                if( is_array( $new_term_id ) ){  
                    $category_set_array[] = $new_term_id['term_id'];
                    ++$terms_added;
                }
                
            }elseif( is_numeric( $term_id ) ){
                
                // the term exists with the giving parent, a match
                $category_set_array[] = $term_id;
                ++$terms_added;
                
            }            
        }
    
        return $category_set_array;
    }    
    
    /**
    * Requires an array with post ID as key and each key having an array of category terms
    * 
    * @uses create_categories_set()
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    * 
    * @param array $posts_array i.e. array( '123' => array( 'Category One', 'Category Two', 'Category Three' ) )
    * @param integer $preset_parent - determine if Pre-Set Category setting in use and pass it to this function
    */
    public function mass_update_posts_categories( $posts_array, $preset_parent = false ) {
        
        // loop through all posts
        foreach( $posts_array as $post_id => $terms_array ){
            
            // create a single set of categories (or makes use of existing categories)
            $categories_array = self::create_categories_set( $terms_array, $preset_parent );
                                        
            // update posts categories
            $result = wp_set_post_categories( $post_id, $categories_array, false );               
        }
    }
}
 
?>
