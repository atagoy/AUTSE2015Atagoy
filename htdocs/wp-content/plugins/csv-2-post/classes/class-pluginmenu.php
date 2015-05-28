<?php
/**
* Beta testing only (check if in use yet) - phasing array files into classes of their own then calling into the main class
*/
class C2P_TabMenu {
    public function menu_array() {
        $menu_array = array();
        
        ######################################################
        #                                                    #
        #                        MAIN                        #
        #                                                    #
        ######################################################
        // can only have one view in main right now until WP allows pages to be hidden from showing in
        // plugin menus. This may provide benefit of bringing user to the latest news and social activity
        // main page
        $menu_array['main']['groupname'] = 'main';        
        $menu_array['main']['slug'] = 'csv2post';// home page slug set in main file
        $menu_array['main']['menu'] = 'CSV 2 POST Dashboard';// plugin admin menu
        $menu_array['main']['pluginmenu'] = __( 'CSV 2 POST Dashboard' ,'csv2post' );// for tabbed menu
        $menu_array['main']['name'] = "main";// name of page (slug) and unique
        $menu_array['main']['title'] = 'Dashboard';// title at the top of the admin page
        $menu_array['main']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu         
        $menu_array['main']['tabmenu'] = false;// boolean - true indicates multiple pages in section, false will hide tab menu and show one page 

        ######################################################
        #                                                    #
        #                   DATA SOURCES                     #
        #                                                    #
        ###################################################### 
        // datatools 
        $menu_array['datatools']['groupname'] = 'datasources';
        $menu_array['datatools']['slug'] = 'csv2post_datatools'; 
        $menu_array['datatools']['menu'] = __( 'Data Sources', 'csv2post' );
        $menu_array['datatools']['pluginmenu'] = __( 'Data Tools', 'csv2post' );
        $menu_array['datatools']['name'] = "datatools";
        $menu_array['datatools']['title'] = __( 'Data Tools', 'csv2post' ); 
        $menu_array['datatools']['parent'] = 'parent'; 
        $menu_array['datatools']['tabmenu'] = true;
                
        // csvfiles
        $menu_array['csvfiles']['groupname'] = 'datasources';
        $menu_array['csvfiles']['slug'] = 'csv2post_csvfiles'; 
        $menu_array['csvfiles']['menu'] = __( 'Data Sources', 'csv2post' );
        $menu_array['csvfiles']['pluginmenu'] = __( 'CSV Files', 'csv2post' );
        $menu_array['csvfiles']['name'] = "csvfilelist";
        $menu_array['csvfiles']['title'] = __( 'CSV Files', 'csv2post' ); 
        $menu_array['csvfiles']['parent'] = 'datatools'; 
        $menu_array['csvfiles']['tabmenu'] = true;
                
        // directorysources
        $menu_array['directorysources']['groupname'] = 'datasources';
        $menu_array['directorysources']['slug'] = 'csv2post_directorysources'; 
        $menu_array['directorysources']['menu'] = __( 'Directory Sources', 'csv2post' );
        $menu_array['directorysources']['pluginmenu'] = __( 'Directory Sources', 'csv2post' );
        $menu_array['directorysources']['name'] = "directorysources";
        $menu_array['directorysources']['title'] = __( 'Directory Sources', 'csv2post' ); 
        $menu_array['directorysources']['parent'] = 'datatools'; 
        $menu_array['directorysources']['tabmenu'] = true;                
        
        // datahistory
        $menu_array['datahistory']['groupname'] = 'datasources';
        $menu_array['datahistory']['slug'] = 'csv2post_datahistory'; 
        $menu_array['datahistory']['menu'] = __( 'Data History', 'csv2post' );
        $menu_array['datahistory']['pluginmenu'] = __( 'Data History', 'csv2post' );
        $menu_array['datahistory']['name'] = "datahistory";
        $menu_array['datahistory']['title'] = __( 'Data History', 'csv2post' ); 
        $menu_array['datahistory']['parent'] = 'datatools'; 
        $menu_array['datahistory']['tabmenu'] = true;       

        ######################################################
        #                                                    #
        #                    PROJECTS                        #
        #                                                    #
        ###################################################### 
    
        // allprojectstools 
        $menu_array['allprojectstools']['groupname'] = 'manageprojects';
        $menu_array['allprojectstools']['slug'] = 'csv2post_allprojectstools'; 
        $menu_array['allprojectstools']['menu'] = __( 'All Projects', 'csv2post' );
        $menu_array['allprojectstools']['pluginmenu'] = __( 'Tools, Reports and Statistics', 'csv2post' );
        $menu_array['allprojectstools']['name'] = "allprojectstools";
        $menu_array['allprojectstools']['title'] = __( 'Tools, Reports and Statistics for ALL Projects', 'csv2post' ); 
        $menu_array['allprojectstools']['parent'] = 'parent'; 
        $menu_array['allprojectstools']['tabmenu'] = true;
    
        // projectstable  
        $menu_array['projectstable']['groupname'] = 'manageprojects';
        $menu_array['projectstable']['slug'] = 'csv2post_projectstable'; 
        $menu_array['projectstable']['menu'] = __( 'Projects Table', 'csv2post' );
        $menu_array['projectstable']['pluginmenu'] = __( 'Projects Table', 'csv2post' );
        $menu_array['projectstable']['name'] = "projectstable";
        $menu_array['projectstable']['title'] = __( 'Projects Table', 'csv2post' ); 
        $menu_array['projectstable']['parent'] = 'allprojectstools'; 
        $menu_array['projectstable']['tabmenu'] = true;
                
        // recent changes for all projects
        $menu_array['allprojectshistory']['groupname'] = 'manageprojects';
        $menu_array['allprojectshistory']['slug'] = 'csv2post_allprojectshistory'; 
        $menu_array['allprojectshistory']['menu'] = __( 'All Projects History', 'csv2post' );
        $menu_array['allprojectshistory']['pluginmenu'] = __( 'All Projects History', 'csv2post' );
        $menu_array['allprojectshistory']['name'] = "allprojectshistory";
        $menu_array['allprojectshistory']['title'] = __( 'All Projects History', 'csv2post' ); 
        $menu_array['allprojectshistory']['parent'] = 'allprojectstools'; 
        $menu_array['allprojectshistory']['tabmenu'] = true;
        
    
        //last post based on all projects 
        
        
        //defaultglobalpostsettings.php (one large form)       
        
        ######################################################
        #                                                    #
        #                  CURRENT PROJECT                   #
        #                                                    #
        ###################################################### 
    
        // projectchecklist  
        $menu_array['projectchecklist']['groupname'] = 'currentprojectmanagement';
        $menu_array['projectchecklist']['slug'] = 'csv2post_projectchecklist'; 
        $menu_array['projectchecklist']['menu'] = __( 'Current Project', 'csv2post' );
        $menu_array['projectchecklist']['pluginmenu'] = __( 'Checklist', 'csv2post' );
        $menu_array['projectchecklist']['name'] = "projectchecklist";
        $menu_array['projectchecklist']['title'] = __( 'Checklist', 'csv2post' ); 
        $menu_array['projectchecklist']['parent'] = 'parent'; 
        $menu_array['projectchecklist']['tabmenu'] = false;
                         
        ######################################################
        #                                                    #
        #                   PROJECT DATA                     #
        #                                                    #
        ###################################################### 
                
        // rules  
        $menu_array['rules']['groupname'] = 'import';
        $menu_array['rules']['slug'] = 'csv2post_rules'; 
        $menu_array['rules']['menu'] = __( '1. Project Data', 'csv2post' );
        $menu_array['rules']['pluginmenu'] = __( 'Rules', 'csv2post' );
        $menu_array['rules']['name'] = "rules";
        $menu_array['rules']['title'] = __( 'Projects Data Rules', 'csv2post' ); 
        $menu_array['rules']['parent'] = 'parent';  
        $menu_array['rules']['tabmenu'] = true;           
          
        // import (current project) 
        $menu_array['import']['groupname'] = 'import';
        $menu_array['import']['slug'] = 'csv2post_import'; 
        $menu_array['import']['menu'] = __( 'Data Import', 'csv2post' );
        $menu_array['import']['pluginmenu'] = __( 'Data Import', 'csv2post' );
        $menu_array['import']['name'] = "import";
        $menu_array['import']['title'] = __( 'Data Import', 'csv2post' ); 
        $menu_array['import']['parent'] = 'rules'; 
        $menu_array['import']['tabmenu'] = true;             
                
        // sources  
        $menu_array['sources']['groupname'] = 'import';
        $menu_array['sources']['slug'] = 'csv2post_sources'; 
        $menu_array['sources']['menu'] = __( 'Projects Data Sources', 'csv2post' );
        $menu_array['sources']['pluginmenu'] = __( 'Projects Data Sources', 'csv2post' );
        $menu_array['sources']['name'] = "sources";
        $menu_array['sources']['title'] = __( 'Projects Data Sources', 'csv2post' ); 
        $menu_array['sources']['parent'] = 'rules';  
        $menu_array['sources']['tabmenu'] = true;      
        
        // projectsdata  
        $menu_array['projectsdata']['groupname'] = 'import';
        $menu_array['projectsdata']['slug'] = 'csv2post_projectsdata'; 
        $menu_array['projectsdata']['menu'] = __( 'Data Table', 'csv2post' );
        $menu_array['projectsdata']['pluginmenu'] = __( 'Data Table', 'csv2post' );
        $menu_array['projectsdata']['name'] = "projectsdata";
        $menu_array['projectsdata']['title'] = __( 'Data Table', 'csv2post' ); 
        $menu_array['projectsdata']['parent'] = 'rules';
        $menu_array['projectsdata']['tabmenu'] = true;

        ######################################################
        #                                                    #
        #                   CATEGORIES                       #
        #                                                    #
        ######################################################
        
        // columns  
        $menu_array['columns']['groupname'] = 'categories';
        $menu_array['columns']['slug'] = 'csv2post_columns'; 
        $menu_array['columns']['menu'] = __( '2. Categories', 'csv2post' );
        $menu_array['columns']['pluginmenu'] = __( 'Columns', 'csv2post' );
        $menu_array['columns']['name'] = "columns";
        $menu_array['columns']['title'] = __( 'Columns', 'csv2post' ); 
        $menu_array['columns']['parent'] = 'parent'; 
        $menu_array['columns']['tabmenu'] = true;        
        
        // categorycreation  
        $menu_array['categorycreation']['groupname'] = 'categories';
        $menu_array['categorycreation']['slug'] = 'csv2post_categorycreation'; 
        $menu_array['categorycreation']['menu'] = __( 'Create Categories', 'csv2post' );
        $menu_array['categorycreation']['pluginmenu'] = __( 'Creation', 'csv2post' );
        $menu_array['categorycreation']['name'] = "categorycreation";
        $menu_array['categorycreation']['title'] = __( 'Create Categories', 'csv2post' ); 
        $menu_array['categorycreation']['parent'] = 'columns';                  
        $menu_array['categorycreation']['tabmenu'] = true;
        
        ######################################################
        #                                                    #
        #                     DESIGN                         #
        #                                                    #
        ######################################################
        
        // postsettings  
        $menu_array['postsettings']['groupname'] = 'design';
        $menu_array['postsettings']['slug'] = 'csv2post_postsettings'; 
        $menu_array['postsettings']['menu'] = __( '3. Design', 'csv2post' );
        $menu_array['postsettings']['pluginmenu'] = __( 'Post Settings', 'csv2post' );
        $menu_array['postsettings']['name'] = "postsettings";
        $menu_array['postsettings']['title'] = __( 'Post Settings', 'csv2post' ); 
        $menu_array['postsettings']['parent'] = 'parent';         
        $menu_array['postsettings']['tabmenu'] = true;
        
        // content  
        $menu_array['content']['groupname'] = 'design';
        $menu_array['content']['slug'] = 'csv2post_content'; 
        $menu_array['content']['menu'] = __( 'Content', 'csv2post' );
        $menu_array['content']['pluginmenu'] = __( 'Content', 'csv2post' );
        $menu_array['content']['name'] = "content";
        $menu_array['content']['title'] = __( 'Content Templates', 'csv2post' ); 
        $menu_array['content']['parent'] = 'postsettings'; 
        $menu_array['content']['tabmenu'] = true;
               
        // dates  
        $menu_array['dates']['groupname'] = 'design';
        $menu_array['dates']['slug'] = 'csv2post_dates'; 
        $menu_array['dates']['menu'] = __( 'Dates', 'csv2post' );
        $menu_array['dates']['pluginmenu'] = __( 'Dates', 'csv2post' );
        $menu_array['dates']['name'] = "dates";
        $menu_array['dates']['title'] = __( 'Dates', 'csv2post' ); 
        $menu_array['dates']['parent'] = 'postsettings';     
        $menu_array['dates']['tabmenu'] = true;
        
        // posttypes  
        $menu_array['posttypes']['groupname'] = 'design';
        $menu_array['posttypes']['slug'] = 'csv2post_posttypes'; 
        $menu_array['posttypes']['menu'] = __( 'Post Types', 'csv2post' );
        $menu_array['posttypes']['pluginmenu'] = __( 'Post Types', 'csv2post' );
        $menu_array['posttypes']['name'] = "posttypes";
        $menu_array['posttypes']['title'] = __( 'Post Types', 'csv2post' ); 
        $menu_array['posttypes']['parent'] = 'postsettings';
        $menu_array['posttypes']['tabmenu'] = true;
        
        // replacevaluerules  
        $menu_array['replacevaluerules']['groupname'] = 'design';
        $menu_array['replacevaluerules']['slug'] = 'csv2post_replacevaluerules'; 
        $menu_array['replacevaluerules']['menu'] = __( 'Replace Value Rules', 'csv2post' );
        $menu_array['replacevaluerules']['pluginmenu'] = __( 'Replace Value Rules', 'csv2post' );
        $menu_array['replacevaluerules']['name'] = "replacevaluerules";
        $menu_array['replacevaluerules']['title'] = __( 'Replace Value Rules', 'csv2post' ); 
        $menu_array['replacevaluerules']['parent'] = 'postsettings';
        $menu_array['replacevaluerules']['tabmenu'] = true;
                                                                                                
        ######################################################
        #                                                    #
        #                        META                        #
        #                                                    #
        ######################################################
        // customfields
        $menu_array['customfields']['groupname'] = 'meta'; 
        $menu_array['customfields']['slug'] = 'csv2post_customfields';// home page slug set in main file
        $menu_array['customfields']['menu'] = '4. Meta';// main menu title
        $menu_array['customfields']['pluginmenu'] = 'Custom Fields';// main menu title        
        $menu_array['customfields']['name'] = "customfields";// name of page (slug) and unique
        $menu_array['customfields']['title'] = 'Custom Fields';// page title seen once page is opened
        $menu_array['customfields']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu    
        $menu_array['customfields']['tabmenu'] = true;
        
        // taxonomies
        $menu_array['taxonomies']['groupname'] = 'meta';
        $menu_array['taxonomies']['slug'] = 'csv2post_taxonomies';// home page slug set in main file
        $menu_array['taxonomies']['menu'] = __( 'Taxonomies', 'csv2post' );// main menu title
        $menu_array['taxonomies']['pluginmenu'] = __( 'Taxonomies', 'csv2post' );// main menu title
        $menu_array['taxonomies']['name'] = "taxonomies";// name of page (slug) and unique
        $menu_array['taxonomies']['title'] = __( 'Taxonomies', 'csv2post' );// page title seen once page is opened 
        $menu_array['taxonomies']['parent'] = 'customfields';// either "parent" or the name of the parent - used for building tab menu   
        $menu_array['taxonomies']['tabmenu'] = true;
        
        ######################################################
        #                                                    #
        #                   POST CREATION                    #
        #                                                    #
        ######################################################
        // postcreation
        $menu_array['postcreation']['groupname'] = 'postcreation'; 
        $menu_array['postcreation']['slug'] = 'csv2post_postcreation';// home page slug set in main file
        $menu_array['postcreation']['menu'] = '5. Create';// main menu title
        $menu_array['postcreation']['pluginmenu'] = 'Creation Tools';// main menu title        
        $menu_array['postcreation']['name'] = "postcreation";// name of page (slug) and unique
        $menu_array['postcreation']['title'] = 'Creation Tools';// page title seen once page is opened
        $menu_array['postcreation']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu    
        $menu_array['postcreation']['tabmenu'] = true;
        
        // lastpost
        $menu_array['lastpost']['groupname'] = 'postcreation';
        $menu_array['lastpost']['slug'] = 'csv2post_lastpost';// home page slug set in main file
        $menu_array['lastpost']['menu'] = __( 'Last Post', 'csv2post' );// main menu title
        $menu_array['lastpost']['pluginmenu'] = __( 'Last Post', 'csv2post' );// main menu title
        $menu_array['lastpost']['name'] = "lastpost";// name of page (slug) and unique
        $menu_array['lastpost']['title'] = __( 'Last Post Created', 'csv2post' );// page title seen once page is opened 
        $menu_array['lastpost']['parent'] = 'postcreation';// either "parent" or the name of the parent - used for building tab menu   
        $menu_array['lastpost']['tabmenu'] = true;
                          
        return $menu_array;
    }
} 
?>
