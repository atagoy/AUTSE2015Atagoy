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
        #                    PROJECTS                        #
        #                                                    #
        ###################################################### 
        
        // datasources  
        $menu_array['datasources']['groupname'] = 'projects';
        $menu_array['datasources']['slug'] = 'csv2post_datasources'; 
        $menu_array['datasources']['menu'] = __( '1. Manage Projects', 'csv2post' );
        $menu_array['datasources']['pluginmenu'] = __( 'Data Sources', 'csv2post' );
        $menu_array['datasources']['name'] = "datasources";
        $menu_array['datasources']['title'] = __( 'Data Sources', 'csv2post' ); 
        $menu_array['datasources']['parent'] = 'parent'; 
        $menu_array['datasources']['tabmenu'] = true; 

        // datasourceslist (global) 
        $menu_array['datasourceslist']['groupname'] = 'projects';
        $menu_array['datasourceslist']['slug'] = 'csv2post_datasourceslist'; 
        $menu_array['datasourceslist']['menu'] = __( 'Data Import', 'csv2post' );
        $menu_array['datasourceslist']['pluginmenu'] = __( 'Global CSV List', 'csv2post' );
        $menu_array['datasourceslist']['name'] = "datasourceslist";
        $menu_array['datasourceslist']['title'] = __( 'Global CSV List', 'csv2post' ); 
        $menu_array['datasourceslist']['parent'] = 'datasources'; 
        $menu_array['datasourceslist']['tabmenu'] = true;
                       
        // projects  
        $menu_array['projects']['groupname'] = 'projects';
        $menu_array['projects']['slug'] = 'csv2post_projects'; 
        $menu_array['projects']['menu'] = __( 'Projects', 'csv2post' );
        $menu_array['projects']['pluginmenu'] = __( 'Projects', 'csv2post' );
        $menu_array['projects']['name'] = "projects";
        $menu_array['projects']['title'] = __( 'Projects', 'csv2post' ); 
        $menu_array['projects']['parent'] = 'datasources'; 
        $menu_array['projects']['tabmenu'] = true;
                
        ######################################################
        #                                                    #
        #                      DATA                          #
        #                                                    #
        ###################################################### 
                
        // rules  
        $menu_array['rules']['groupname'] = 'import';
        $menu_array['rules']['slug'] = 'csv2post_rules'; 
        $menu_array['rules']['menu'] = __( '2. Project Data', 'csv2post' );
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
        
        // table  
        $menu_array['table']['groupname'] = 'import';
        $menu_array['table']['slug'] = 'csv2post_table'; 
        $menu_array['table']['menu'] = __( 'Data Table', 'csv2post' );
        $menu_array['table']['pluginmenu'] = __( 'Data Table', 'csv2post' );
        $menu_array['table']['name'] = "table";
        $menu_array['table']['title'] = __( 'Data Table', 'csv2post' ); 
        $menu_array['table']['parent'] = 'rules';
        $menu_array['table']['tabmenu'] = true;

        ######################################################
        #                                                    #
        #                   CATEGORIES                       #
        #                                                    #
        ######################################################
        
        // columns  
        $menu_array['columns']['groupname'] = 'categories';
        $menu_array['columns']['slug'] = 'csv2post_columns'; 
        $menu_array['columns']['menu'] = __( '3. Categories', 'csv2post' );
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
        $menu_array['postsettings']['menu'] = __( '4. Design', 'csv2post' );
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
        $menu_array['customfields']['menu'] = '5. Meta';// main menu title
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
        // tools
        $menu_array['tools']['groupname'] = 'postcreation'; 
        $menu_array['tools']['slug'] = 'csv2post_tools';// home page slug set in main file
        $menu_array['tools']['menu'] = '6. Post Creation';// main menu title
        $menu_array['tools']['pluginmenu'] = 'Tools';// main menu title        
        $menu_array['tools']['name'] = "tools";// name of page (slug) and unique
        $menu_array['tools']['title'] = 'Post Creation Tools';// page title seen once page is opened
        $menu_array['tools']['parent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu    
        $menu_array['tools']['tabmenu'] = true;
        
        // lastpost
        $menu_array['lastpost']['groupname'] = 'postcreation';
        $menu_array['lastpost']['slug'] = 'csv2post_lastpost';// home page slug set in main file
        $menu_array['lastpost']['menu'] = __( 'Last Post', 'csv2post' );// main menu title
        $menu_array['lastpost']['pluginmenu'] = __( 'Last Post', 'csv2post' );// main menu title
        $menu_array['lastpost']['name'] = "lastpost";// name of page (slug) and unique
        $menu_array['lastpost']['title'] = __( 'Last Post Created', 'csv2post' );// page title seen once page is opened 
        $menu_array['lastpost']['parent'] = 'tools';// either "parent" or the name of the parent - used for building tab menu   
        $menu_array['lastpost']['tabmenu'] = true;
                          
        return $menu_array;
    }
} 
?>
