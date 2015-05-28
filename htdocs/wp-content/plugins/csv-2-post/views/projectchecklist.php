<?php
/**
 * Table of projects.
 *
 * @package CSV 2 POST
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 8.1.3
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class CSV2POST_Projectchecklist_View extends CSV2POST_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 8.1.3
     *
     * @var int
     */
    protected $screen_columns = 1;
    
    protected $view_name = 'projectchecklist';
    
    public $purpose = 'normal';// normal, dashboard

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.1.33
    * @version 1.1
    */
    public function meta_box_array() {
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            //array( $this->view_name . '-datasourcestable', __( 'Data Sources Table', 'csv2post' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'datasourcestable' ), true, 'activate_plugins' ),
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
        global $csv2post_settings;
        
        // create constant for view name
        if(!defined( "WTG_CSV2POST_VIEWNAME") ){define( "WTG_CSV2POST_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->CSV2POST = CSV2POST::load_class( 'CSV2POST', 'class-csv2post.php', 'classes' );
        $this->UI = CSV2POST::load_class( 'CSV2POST_UI', 'class-ui.php', 'classes' ); 
        $this->DB = CSV2POST::load_class( 'CSV2POST_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = CSV2POST::load_class( 'CSV2POST_PHP', 'class-phplibrary.php', 'classes' );
        $this->FORMS = CSV2POST::load_class( 'CSV2POST_FORMS', 'class-forms.php', 'classes' );
        
        // set current project values
        if( isset( $csv2post_settings['currentproject'] ) && $csv2post_settings['currentproject'] !== false ) {
            $this->project_object = $this->CSV2POST->get_project( $csv2post_settings['currentproject'] ); 
            if( !$this->project_object ) {
                $this->current_project_settings = false;
            } else {
                $this->current_project_settings = maybe_unserialize( $this->project_object->projectsettings ); 
            }
        }
        
        parent::setup( $action, $data );

        $this->add_text_box( 'head1', array( $this, 'intro' ), 'normal' );

        // create a data table ( use "head" to position before any meta boxes and outside of meta box related divs)
        $this->add_text_box( 'head2', array( $this, 'checklist' ), 'normal' );                  
    }

    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 0.0.3
    * @version 1.0
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
    * @since 0.0.2
    * @version 1.0
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
    * @version 1.0
    */
    function parent( $data, $box ) {
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    } 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
    public function intro( $data, $box ) {
      
    }    

    /**
    * Displays one or more tables of data at the top of the page before post boxes
    * 
    * @author Ryan R. Bayne
    * @package CSV 2 POST
    * @since 8.2.0
    * @version 1.0
    * 
    * @todo add arguments to apply specific, thorough validation suitable for each setting...
    * righ now most only check if value exists (isset) but this is not enough. 
    */
    public function checklist( $data, $box ) { 
        global $wpdb, $csv2post_settings;
        
        // get current project
        $p = $this->current_project_settings;
        
        echo '<h2>Key</h2>';
        
        echo $this->UI->notice_return( 'success', 'Tiny', __( 'Ready/Complete/On', 'csv2post' ), __( 'Option saved and is complete withou any issues.', 'csv2post' ), false, true );        
        echo $this->UI->notice_return( 'info', 'Tiny', __( 'Not Set/Optional', 'csv2post' ), __( 'Option not used but it can be ignored.', 'csv2post' ), false, true );
        echo $this->UI->notice_return( 'warning', 'Tiny', __( 'Disabled/Off', 'csv2post' ), __( 'Option set to off, disabled or no.', 'csv2post' ), false, true );
        echo $this->UI->notice_return( 'error', 'Tiny', __( 'Not Set/Required', 'csv2post' ), __('You must complete the option.', 'csv2post' ), false, true );
        
        echo '<h2>Checklist</h2>';
        
        // post status
        if( isset( $p['basicsettings']['poststatus'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Default Post Status', 'csv2post' ), sprintf( __('You have set %s as your default post status.', 'csv2post' ), $p['basicsettings']['poststatus'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Default Post Status', 'csv2post' ), __('No plugin default. Your WordPress core default post status will be applied.', 'csv2post' ), false, true );    
        }
  
        // ping status
        if( isset( $p['basicsettings']['pingstatus'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Ping Status', 'csv2post' ), sprintf( __('You have set your ping status to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['pingstatus'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Ping Status', 'csv2post' ), __('No plugin default. Your WordPress default ping status will be applied.', 'csv2post' ), false, true );    
        }
        
        // comment status
        if( isset( $p['basicsettings']['commentstatus'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Comment Status', 'csv2post' ), sprintf( __('You have set your comment status to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['commentstatus'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Comment Status', 'csv2post' ), __('No plugin default. Your WordPress default comment status will be applied.', 'csv2post' ), false, true );    
        }
        
        // default author
        if( isset( $p['basicsettings']['defaultauthor'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Default Author', 'csv2post' ), sprintf( __('You have set your default author to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['defaultauthor'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Default Author', 'csv2post' ), __('No plugin default author. Your WordPress default author will be applied.', 'csv2post' ), false, true );    
        }
        
        // default category
        if( isset( $p['basicsettings']['defaultcategory'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Default Category', 'csv2post' ), sprintf( __('You have set your default category to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['defaultcategory'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Default Category', 'csv2post' ), __('No plugin default category. Your WordPress default category will be applied.', 'csv2post' ), false, true );    
        }
        
        // default post type
        if( isset( $p['basicsettings']['defaultposttype'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Default Post Type', 'csv2post' ), sprintf( __('You have set your default post type to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['defaultposttype'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Default Post Type', 'csv2post' ), __('No plugin default post-type. Your WordPress default post-type will be applied.', 'csv2post' ), false, true );    
        }
        
        // default post format
        if( isset( $p['basicsettings']['defaultpostformat'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Default Post Format', 'csv2post' ), sprintf( __('You have set your default post type to %s. (applies to posts created by CSV 2 POST only)', 'csv2post' ), $p['basicsettings']['defaultposttype'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Default Post Formats', 'csv2post' ), __('No plugin default post-type. Your WordPress default post-type will be applied.', 'csv2post' ), false, true );    
        }
        
        // category assignment (how many categories will be applied to posts)
        if( isset( $p['basicsettings']['categoryassignment'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Category Assignment', 'csv2post' ), sprintf( __('You have set your category assignment mode to %s.', 'csv2post' ), $p['basicsettings']['categoryassignment'] ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Category Assignment', 'csv2post' ), __('No category assignment mode selected. The default mode is "all" which means all levels of categories will be added to every post.', 'csv2post' ), false, true );    
        }
        
        // custom permalink data
        if( isset( $p['permalinks']['table'] ) && isset( $p['permalinks']['table'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( 'Permalink Data Setup', 'csv2post' ), __('Permalinks will be set using your own data.', 'csv2post' ), false, true );        
        } elseif( isset( $p['permalinks']['table'] ) && !isset( $p['permalinks']['column'] ) ) {
            echo $this->UI->notice_return( 'error', 'Small', __( 'Permalink Data Problem', 'csv2post' ), __('You have not setup a database column table that holds your permalink data but you did select a table.', 'csv2post' ), false, true );
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( 'Permalink Data', 'csv2post' ), __('You have not setup custom permalinks using data.', 'csv2post' ), false, true );    
        }    
  
        // URL cloaking
        if( !isset( $p['cloaking'] ) ) {
            echo $this->UI->notice_return( 'info', 'Small', __( 'URL Cloaking', 'csv2post' ), __('You have not setup URL cloaking.', 'csv2post' ), false, true );    
        } else {
            $total_cloaks = count( $p['cloaking'] );
            echo $this->UI->notice_return( 'success', 'Small', __( "$total_cloaks URL Cloaks", 'csv2post' ), __( "You have not setup $total_cloaks URL cloaks.", 'csv2post' ), false, true );
        
            /* TODO: add validation
            $p['cloaking']['urlcloak1']['table']
            $p['cloaking']['urlcloak1']['column']
            */
        }
        
        // featured images
        if( isset( $p['images']['featuredimage']['table'] ) && $p['images']['featuredimage']['column'] ) {
            echo $this->UI->notice_return( 'success', 'Small', __( "Featured Images", 'csv2post' ), __( "Image data has been selected for adding featured images (thumbnails).", 'csv2post' ), false, true );    
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( "Featured Images", 'csv2post' ), __( "You have not selected image data for creating featured images.", 'csv2post' ), false, true );                
        }

        // authors data
        if( isset( $p['authors']['email']['table'] ) 
        && isset( $p['authors']['email']['column'] )
        && isset( $p['authors']['username']['table'] )
        && isset( $p['authors']['username']['column'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( "Author Data", 'csv2post' ), __( "You have setup author data and posts will not be created using a default author.", 'csv2post' ), false, true );    
        } else {
            echo $this->UI->notice_return( 'info', 'Small', __( "Author Data", 'csv2post' ), __( "You have not selected author data and posts will be assigned to the default author.", 'csv2post' ), false, true );                
        }
        
        // title template
        if( isset( $p['titles']['defaulttitletemplate'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( "Default Title Template", 'csv2post' ), __( "Your main/default title template has been setup.", 'csv2post' ), false, true );    
        } else {
            echo $this->UI->notice_return( 'error', 'Small', __( "Default Title Template Required", 'csv2post' ), __( "You must create a defaul title template which will tell the plugin what columns of data to use when building post titles.", 'csv2post' ), false, true );    
        }
        
        // default content template
        if( isset( $p['content']['wysiwygdefaultcontent'] ) ) {
            echo $this->UI->notice_return( 'success', 'Small', __( "Default Content Template", 'csv2post' ), __( "Your main content template has been setup and will be used unless more advanced rules are setup.", 'csv2post' ), false, true );    
        } else {
            echo $this->UI->notice_return( 'error', 'Small', __( "Default Title Template Required", 'csv2post' ), __( "You need to tell CSV 2 POST where to place your data in posts. Please create the Defaul Content Template.", 'csv2post' ), false, true );        
        }
        
        /*   TODO: still to complete but I want feedback before
        spending more time on it. If users like the checklist I
        will extend it.
                
        array (size=19)
          'basicsettings' => 
          'permalinks' => 
          'cloaking' => 
          'images' => 
          'authors' => 
          'titles' => 

          'content' => 
            array (size=15)
            
              'designrule1' => 
                array (size=2)
                  'column' => string 'currency' (length=8)
                  'table' => string 'wtg_computers1csv' (length=17)
              'designruletrigger1' => string 'qwerty' (length=6)
              'designtemplate1' => string 'notselected' (length=11)
              
              'designrule2' => 
                array (size=2)
                  'column' => string 'mainimage' (length=9)
                  'table' => string 'wtg_computers1csv' (length=17)
              'designruletrigger2' => string 'asdf' (length=4)
              'designtemplate2' => string 'notselected' (length=11)
              'designrule3' => 
                array (size=1)
                  'column' => string '' (length=0)
              'designruletrigger3' => string '' (length=0)
              'designtemplate3' => string '' (length=0)
              'enablegroupedimageimport' => string 'enabled' (length=7)
              'localimages' => 
                array (size=2)
                  'table' => string 'wtg_computers1csv' (length=17)
                  'column' => string 'processorsimple' (length=15)
              'incrementalimages' => string 'disabled' (length=8)
              'groupedimagesdir' => string 'this/is/a/directory' (length=19)
              'valuerules' => 
                array (size=2)
                  0 => 
                    array (size=5)
                      ...
                  1 => 
                    array (size=5)
                      ...
                      
          'customfields' => 
            array (size=7)
              'seotitlekey' => string '' (length=0)
              'seotitletemplate' => string '' (length=0)
              'seodescriptionkey' => string '' (length=0)
              'seodescriptiontemplate' => string '' (length=0)
              'seokeywordskey' => string '' (length=0)
              'seokeywordstemplate' => string '' (length=0)
              'cflist' => 
                array (size=3)
                  0 => 
                    array (size=5)
                      ...
                  1 => 
                    array (size=5)
                      ...
                  2 => 
                    array (size=5)
                      ...
                      
          'categories' => 
            array (size=5)
              'presetcategoryid' => string '5' (length=1)
              'descriptiondata' => 
                array (size=5)
                  0 => 
                    array (size=2)
                      ...
                  1 => 
                    array (size=2)
                      ...
                  2 => 
                    array (size=2)
                      ...
                  3 => 
                    array (size=2)
                      ...
                  4 => 
                    array (size=2)
                      ...
              'descriptiontemplates' => 
                array (size=5)
                  0 => string '' (length=0)
                  1 => string '' (length=0)
                  2 => string '' (length=0)
                  3 => string '' (length=0)
                  4 => string '' (length=0)
              'meta' => 
                array (size=5)
                  0 => 
                    array (size=1)
                      ...
                  1 => 
                    array (size=1)
                      ...
                  2 => 
                    array (size=1)
                      ...
                  3 => 
                    array (size=1)
                      ...
                  4 => 
                    array (size=1)
                      ...
              'data' => 
                array (size=3)
                  0 => 
                    array (size=2)
                      ...
                  1 => 
                    array (size=2)
                      ...
                  2 => 
                    array (size=2)
                      ...
                      
                      
          'adoption' => 
            array (size=4)
              'adoptionmethod' => string 'title' (length=5)
              'allowedchanges' => string 'everything' (length=10)
              'adoptionmetakey' => string '' (length=0)
              'adoptionmeta' => 
                array (size=1)
                  'column' => string '' (length=0)
                  
                  
          'tags' => 
            array (size=10)
              'table' => string 'wtg_computers1csv' (length=17)
              'column' => string 'memory' (length=6)
              'textdata' => 
                array (size=1)
                  'column' => string '' (length=0)
              'defaultnumerics' => string 'disallow' (length=8)
              'tagstringlength' => string '50' (length=2)
              'maximumtags' => string '3' (length=1)
              'excludedtags' => string 'to,from,about,where,too,see,him,his,her,hers,ours,were' (length=54)
              'generatetags' => 
                array (size=2)
                  'table' => string 'wtg_computers1csv' (length=17)
                  'column' => string 'shortdescription' (length=16)
              'generatetagsexample' => string '' (length=0)
              'numerictags' => string 'allow' (length=5)
              
              
          'posttypes' => 
            array (size=9)
              'posttyperule1' => 
                array (size=2)
                  'table' => string 'wtg_computers1csv' (length=17)
                  'column' => string 'price' (length=5)
              'posttyperuletrigger1' => string 'thetrigger' (length=10)
              'posttyperuleposttype1' => string 'page' (length=4)
              'posttyperule2' => 
                array (size=2)
                  'table' => string 'wtg_computers1csv' (length=17)
                  'column' => string 'name' (length=4)
              'posttyperuletrigger2' => string 'thenexttrigger' (length=14)
              'posttyperuleposttype2' => string 'revision' (length=8)
              'posttyperule3' => 
                array (size=2)
                  'table' => string '' (length=0)
                  'column' => string '' (length=0)
              'posttyperuletrigger3' => string '' (length=0)
              'posttyperuleposttype3' => string '' (length=0)
              
              
          'dates' => 
            array (size=9)
              'publishdatemethod' => string 'incremental' (length=11)
              'table' => string 'wtg_computers1csv' (length=17)
              'column' => string 'mainimage' (length=9)
              'dateformat' => string 'mysql' (length=5)
              'incrementalstartdate' => string '4/4/2014' (length=8)
              'naturalvariationlow' => string '30' (length=2)
              'naturalvariationhigh' => string '90' (length=2)
              'randomdateearliest' => string '4/4/2014' (length=8)
              'randomdatelatest' => string '4/4/2016' (length=8)
              
              
          'updating' => 
            array (size=1)
              'updatepostonviewing' => string '' (length=0)
              
              
          'idcolumn' => string 'id' (length=2)
          
          
          'featuredimage' => 
            array (size=2)
              'table' => string 'wtg_computers1csv' (length=17)
              'column' => string 'operatingsystem' (length=15)
              
              
          'permalink' => 
            array (size=2)
              'table' => string 'wtg_computers1csv' (length=17)
              'column' => string 'harddrive' (length=9)
              
              
          'urlcloak1' => 
            array (size=2)
              'table' => string 'wtg_computers1csv' (length=17)
              'column' => string 'harddrive' (length=9)
              
              
          'taxonomies' => 
            array (size=1)
              'columns' => 
                array (size=1)
                  'post' => 
                    array (size=2)
                      ...
                      
                      */
                      
 
         
        //$project['lockcontent']
        //$project['lockmeta']
   
   
                                
    }   
}
?>