<?php
/**
 * Beta Test 1 [page]   
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Beta Test 1 [class] 
 * 
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne
 * @since 8.1.3
 */
class CSV2POST_Betatest1_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 2;
    
    protected $view_name = 'betatest1';

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function meta_box_array() {
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( 'betatest1-currenttesting', __( 'Test Introduction', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'currenttesting' ), true, 'activate_plugins' ),
        );    
    }
        
    /**
     * Set up the view with data and do things that are specific for this view
     *
     * @since 8.1.3
     *
     * @param string $action Action for this view
     * @param array $data Data for this view
     */
    public function setup( $action, array $data ) {
        global $c2p_settings;
        
        // create constant for view name
        if(!defined( "WTG_CSV2POST_VIEWNAME") ){define( "WTG_CSV2POST_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'C2P_UI', 'class-ui.php', 'classes' );  
        $this->DB = CSV2POST::load_class( 'C2P_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'C2P_PHP', 'class-phplibrary.php', 'classes' );
        $this->Class_Categories = CSV2POST::load_class( 'C2P_Categories', 'class-categories.php', 'classes' );
                        
        // load the current project row and settings from that row
        if( isset( $c2p_settings['currentproject'] ) && $c2p_settings['currentproject'] !== false ) {
            
            $this->project_object = $this->CSV2POST->get_project( $c2p_settings['currentproject'] ); 
            if( !$this->project_object ) {
                $this->current_project_settings = false;
            } else {
                $this->current_project_settings = maybe_unserialize( $this->project_object->projectsettings ); 
            }

            parent::setup( $action, $data );
            
            // add test boxes, add more functions if increasing the number
            for($i=1;$i<=16;$i++){
                $this->add_meta_box( 'betatest1-t' . $i, __( 'Test ' . $i, 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 't' . $i ) );      
            }
                
            // permanent generic information
            $this->add_meta_box( 'betatest1-errors', __( 'Errors', 'csv2post' ), array( $this, 'parent' ), 'side','default',array( 'formid' => 'errors' ) );      
            
        } else {
            $this->add_meta_box( 'betatest1-nocurrentproject', __( 'No Current Project', 'csv2post' ), array( $this->UI, 'metabox_nocurrentproject' ), 'normal','default',array( 'formid' => 'nocurrentproject' ) );      
        }   
    }

    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function metaboxes() {
        parent::register_metaboxes( self::meta_box_array() );     
    }

    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class. 
    * 
    * @uses dashboard_widgets() in parent class CSV2POST_View which loops through meta boxes and registeres widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.0.0
    */
    public function dashboard() { 
        parent::dashboard_widgets( self::meta_box_array() );  
    }
    
    /**
    * All add_meta_box() callback to this function to keep the add_meta_box() call simple.
    * 
    * This function also offers a place to apply more security or arguments.
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.32
    * @version 1.0.1
    */
    function parent( $data, $box ) {
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    }
     
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_currenttesting( $data, $box ) {         
        ?>
        
        <p>Currently testing a new category class.</p>
        
        <p>More information and discussion about this test visit the <a href="http://forum.webtechglobal.co.uk/viewtopic.php?f=8&t=33">WebTechGlobal forum.</a></p>                                                                    
        
        <h4>The following work and tests are to be carried out...</h4>
        
        <table class="form-table">
            <tr>
                <td>ID</td>
                <td>Status</td>
                <td>Description</td>
            </tr>
            <tr>
                <td>1</td><td>Complete</td><td>Establish a categories level: 0 being the first level.</td>
            </tr>            
            <tr>
                <td>2</td><td>Complete</td><td>Return category ID based on exactly matching term and level and a related category (using term ID)</td>
            </tr>
            <tr>
                <td>3</td><td></td><td>Return a close matching PARENT category i.e. term in data is different but meant to match category in blog.</td>
            </tr>
            <tr>
                <td>4</td><td></td><td>Create a function to create a set of categories i.e. one posts category terms and no more, it will not be a loop through multiple rows of imported data.</td>
            </tr>
            <tr>
                <td>5</td><td></td><td>Submit post ID for categories to be checked and fixed for that post.</td>
            </tr>
            <tr>
                <td>6</td><td></td><td>Category Update: as above really but mass. Re-check and re-organize based on imported data and changes by user.</td>
            </tr>
            <tr>
                <td>7</td><td></td><td>Category description templates including text spinning.</td>
            </tr>
            <tr>
                <td>8</td><td></td><td>Implement a Do Not Create Categories setting to prevent categories being made during autoblogging. So if a post has category
            terms in data that do not match existing categories then the post will not be created but the row of data will be flagged for attention.</td>
            </tr>            
            <tr>
                <td>9</td><td></td><td>create_categories_mass_withoutmapping() - will require using imported data from current project .</td>
            </tr>            
            <tr>
                <td>10</td><td></td><td>create_categories_mass_mappingincluded() - will require mapping form to be submitted and imported data from current project. Mapping form may need to be improved first as it does not make parents and levels clear enough.</td>
            </tr>            
            <tr>
                <td>11</td><td>Complete</td><td>Get a categories related categories, both parents and children. The merged array of both will be used to establish if a term name is a match for a term in data.</td>
            </tr>             
            <tr>
                <td>12</td><td>Complete</td><td>WP get_category_parents() returns category terms, we need to replace its use with a function that returns the IDs. This test will output the results of the new function, an array of parent ID's.</td>
            </tr>             
            <tr>
                <td>13</td><td></td><td>Return a close matching CHILD category i.e. term in data is different but meant to match category in blog. This will be used in pairing. This test will need require establishing correct category group.</td>
            </tr> 
            <tr>
                <td>14</td><td></td><td>Correct a single post in the wrong category i.e. data indicates parent post should be SOMETHING but somehow post has ended up in ANYTHING, including Uncategorised or possible even being in a Pre-Set parent only and not the child. This test will not use project data but will be fed manually typed data.</td>
            </tr>            
            <tr>
                <td>15</td><td></td><td>Locate posts in wrong category, put the ID's into array.</td>
            </tr>            
            <tr>
                <td>16</td><td></td><td>Correct multiple posts in wrong category. As above but will loop through post IDs.</td>
            </tr>             
                                                                                                        
        </table>
        
        <p>Please note that level 4 in PHP is the same as Level Five on the interface. Zero in PHP is the first level.</p>
        <?php                           
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t1( $data, $box ) {                            
        $C2P_Categories = new C2P_Categories();         
        ?>  
        
        <h4>Get Categories Level</h4>
        
        <p>My Five Level Cat ID's: 228, 229, 230, 231, 232. Only one is a parent and the rest are children of each
        other. So the result should be: 0,1,2,3,4,5 in this test.</p>                

        <ol>
            <li><?php echo $C2P_Categories->level( 228, '/' ); ?></li>
            <li><?php echo $C2P_Categories->level( 229, '/' ); ?></li>
            <li><?php echo $C2P_Categories->level( 230, '/' ); ?></li>
            <li><?php echo $C2P_Categories->level( 231, '/' ); ?></li>
            <li><?php echo $C2P_Categories->level( 232, '/' ); ?></li>
        </ol>
  
        <?php                    
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t2( $data, $box ) {                             
        ?>  
        
        <h4>Get Matching Term By String and Related Category</h4>

        <p>This tests a method which queries term name, establishes the level of each matching term (not enough
        to validate a parent) then checks if related categories (both parents and children) contains a specific
        category ID. Use this when we have a category ID that is not the direct parent or child of a category 
        that either exists or needs to be created.</p>
        
        <?php 
        // cat id 232 is named Level Five, it will be used to do the group check
        $match_term_result = $this->Class_Categories->match_term_byrelated( 'Level Three', 2, 232 );
        
        echo 'Category ID: ' . $match_term_result;                   
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t3( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    } 
       
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t4( $data, $box ) {   
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Create Category Set: No Mapping In Use', 'csv2post' ), false );        
        $this->UI->hidden_form_values( $box['args']['formid'], $box['title']);                                 
        ?>  
        
        <p>Submission will create a single set of categories. Do not use in a live blog. The function used is intended
        to be used within a loop. It requires an array of terms only. They must be in heirarchical order. This function
        should also take the Pre-Set category into account for the current project. If the Pre-Set has been used then
        all categories I code for this test should become children of the Pre-Set Category.</p> 
        
        <p>Usually the create function must take mapping into account and avoid creating categories where the term has been
        manually paired by user. This function will be specifically called when no mapping exists.</p>
        
        <?php        
                        
        $this->UI->postbox_content_footer();                    
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t5( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t6( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }
  
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t7( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t8( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }
 
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t9( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }
  
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t10( $data, $box ) {                                
        ?>  
        
        <h4>Test Name</h4>
            
        
        <?php                    
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t11( $data, $box ) {                                
        ?>  
        
        <h4>Get Related Categories</h4>
        <p>This test is done using a category that has parents and children. The result is an
        array of all parent and child ID. It is used to determine if a known column ID exists within
        a group of related categories. We can also check the level of such a category to ensure
        a match. We cannot get a 100% match with the relation of categories only or the level only as it
        is possible to have the same category term names in different groups of categories and each of the
        matching names in different levels within their groups.</p>    
        
        <?php    
        $C2P_Categories = new C2P_Categories();
        $related_categories = $C2P_Categories->get_related_categories( 230 );
        
        echo '<pre>';
        var_dump( $related_categories );   
        echo '</pre>';             
    }

    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t12( $data, $box ) {                                
        ?>  
        
        <h4>Get Parent Categories</h4>
        <p>Testing a new function that gets all parent ID's for a term/category. Wordpress has a function
        that returns parent names but not the ID.</p>    
        
        <?php    
        $C2P_Categories = new C2P_Categories();
        $result = $C2P_Categories->get_category_parents_id( 231 );
        echo '<pre>';
        var_dump( $result );   
        echo '</pre>';         
    }
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t13( $data, $box ) {                                
        ?>  
        
        <h4>Get Close Matching Child Category</h4>
        <p>I want to explore the idea of Smart Category Pairing, reducing the users need to manually
        pair terms in data to categories already created in the blog. Once again we have a feature that will
        probably work well but if the user does not understand enough about how it operates it could go wrong.</p>    
        
        <?php    
        
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t14( $data, $box ) {                                
        ?>  
        
        <h4>Test</h4>
        <p>Description.</p>    
        
        <?php    
        
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t15( $data, $box ) {                                
        ?>  
        
        <h4>Get Posts In Wrong Category</h4>
        
        <p>My approach is going to be to put an imported row (post ID, category terms) in an array. Then compare that to the
        results of a query which gets the categories that post is in and then finally compares them. </p>
        
        <p>So the user makes a mistake in their category setup, blog is already live. Maybe the plugin was updated and 
        a change in the new version causes mass posts to go into wrong categories. Whatever the cause we need to
        use selected data (for multiple levels) to check if each post is in the correct category or not.</p> 
        
        <h4>Applied Category Settings</h4>
        <p>This gets more complicated when we consider what levels of categories are applied to a post. My approach is going to be
        to compare the actual parent category and intended parent category. Then check the last level. Anything inbetween can 
        come later. The parent and the last child should be enough to determine if a post is not only in the correct category
        but in the correct category group. It will be a rare occasion where a parent has multiple children of children with the
        same term and so I will not be concerned about that right now.</p>
          
        <?php    
        // category ID's for categories that the post should be in, we would get these using imported terms
        // we will compare this array to an array of ID's for categories our post is actually in and
        // a difference would indicate post is in incorrect category, rather than bother checking which
        // category we will just process all categories for the post, adding post to applicable ones
        $imported_terms = array(228,229,230,231,232);
        
        
        // post in wrong category ID 678 (post should be put into "Wrong Level Five" and we will detect this then return the ID
        // we wont correct it, for testing we will keep it in incorrect category
        $post_categories = wp_get_post_categories( 678 );
        
        $difference = array_diff( $imported_terms, $post_categories );
        
        echo '<h4>Result</h4>';
        echo '<pre>';
        var_dump( $difference );
        echo '</pre>'; 
        
        if( $difference ){
            echo '<p>No Match. The posts categories are different to that in the imported row of data.</p>';
        }else{
            echo '<p>Perfect Match. The posts categories are the same as those in the row of data used to create the post.</p>';
        }
        
        // lets do the same again but with a post in the correct categories
        $imported_terms = array(240,236,238);

        $post_categories = wp_get_post_categories( 680 );
        
        $difference = array_diff( $imported_terms, $post_categories );
        
        if( $difference ){
            echo '<p>No Match. The posts categories are different to that in the imported row of data.</p>';
        }else{
            echo '<p>Perfect Match. The posts categories are the same as those in the row of data used to create the post.</p>';
        }
                
    }    
    
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_t16( $data, $box ) {                                
        ?>  
        
        <h4>Test</h4>
        <p>Description.</p>   
        
        <?php    
        
    }
                           
    /**
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package CSV 2 POST
    * @since 8.1.3
    * @version 1.0.0
    */
    public function postbox_betatest1_errors( $data, $box ) {
        ?>  
        
        <p>Errors and notices will be experienced in this area. They will show on your servers error log.
        Normally this will happen when you have visited beta testing pages. It does not means the plugin
        is faulty but you may report what you experience. Just keep in mind that some tests are setup for
        my blog and will fail on others unless you edit the PHP i.e. change post or category ID values to 
        match those in your own blog.</p>
        
        <?php 
    }     
}