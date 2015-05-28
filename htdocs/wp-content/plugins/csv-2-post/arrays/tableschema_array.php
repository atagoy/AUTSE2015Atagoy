<?php
/** 
 * Database tables information for past and new versions.
 * 
 * This file is not fully in use yet. The intention is to migrate it to the
 * installation class and rather than an array I will simply store every version
 * of each tables query. Each query can be broken down to compare against existing 
 * tables. I find this array approach too hard to maintain over many plugins.
 * 
 * @todo move this to installation class but also reduce the array to actual queries per version
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 * @version 8.1.2
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
 
 
/*   Column Array Example Returned From "mysql_query( "SHOW COLUMNS FROM..."
        
          array(6) {
            [0]=>
            string(5) "row_id"
            [1]=>
            string(7) "int(11)"
            [2]=>
            string(2) "NO"
            [3]=>
            string(3) "PRI"
            [4]=>
            NULL
            [5]=>
            string(14) "auto_increment"
          }
                  
    +------------+----------+------+-----+---------+----------------+
    | Field      | Type     | Null | Key | Default | Extra          |
    +------------+----------+------+-----+---------+----------------+
    | Id         | int(11)  | NO   | PRI | NULL    | auto_increment |
    | Name       | char(35) | NO   |     |         |                |
    | Country    | char(3)  | NO   | UNI |         |                |
    | District   | char(20) | YES  | MUL |         |                |
    | Population | int(11)  | NO   |     | 0       |                |
    +------------+----------+------+-----+---------+----------------+            
*/
   
global $wpdb;   
$c2p_tables_array =  array();
##################################################################################
#                                 c2plog                                         #
##################################################################################        
$c2p_tables_array['tables']['c2plog']['name'] = $wpdb->prefix . 'c2plog';
$c2p_tables_array['tables']['c2plog']['required'] = false;// required for all installations or not (boolean)
$c2p_tables_array['tables']['c2plog']['pluginversion'] = '7.0.0';
$c2p_tables_array['tables']['c2plog']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$c2p_tables_array['tables']['c2plog']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$c2p_tables_array['tables']['c2plog']['primarykey'] = 'row_id';
$c2p_tables_array['tables']['c2plog']['uniquekey'] = 'row_id';
// c2plog - row_id
$c2p_tables_array['tables']['c2plog']['columns']['row_id']['type'] = 'bigint(20)';
$c2p_tables_array['tables']['c2plog']['columns']['row_id']['null'] = 'NOT NULL';
$c2p_tables_array['tables']['c2plog']['columns']['row_id']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['row_id']['default'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['row_id']['extra'] = 'AUTO_INCREMENT';
// c2plog - outcome
$c2p_tables_array['tables']['c2plog']['columns']['outcome']['type'] = 'tinyint(1)';
$c2p_tables_array['tables']['c2plog']['columns']['outcome']['null'] = 'NOT NULL';
$c2p_tables_array['tables']['c2plog']['columns']['outcome']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['outcome']['default'] = '1';
$c2p_tables_array['tables']['c2plog']['columns']['outcome']['extra'] = '';
// c2plog - timestamp
$c2p_tables_array['tables']['c2plog']['columns']['timestamp']['type'] = 'timestamp';
$c2p_tables_array['tables']['c2plog']['columns']['timestamp']['null'] = 'NOT NULL';
$c2p_tables_array['tables']['c2plog']['columns']['timestamp']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['timestamp']['default'] = 'CURRENT_TIMESTAMP';
$c2p_tables_array['tables']['c2plog']['columns']['timestamp']['extra'] = '';
// c2plog - line
$c2p_tables_array['tables']['c2plog']['columns']['line']['type'] = 'int(11)';
$c2p_tables_array['tables']['c2plog']['columns']['line']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['line']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['line']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['line']['extra'] = '';
// c2plog - file
$c2p_tables_array['tables']['c2plog']['columns']['file']['type'] = 'varchar(250)';
$c2p_tables_array['tables']['c2plog']['columns']['file']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['file']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['file']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['file']['extra'] = '';
// c2plog - function
$c2p_tables_array['tables']['c2plog']['columns']['function']['type'] = 'varchar(250)';
$c2p_tables_array['tables']['c2plog']['columns']['function']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['function']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['function']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['function']['extra'] = '';
// c2plog - sqlresult
$c2p_tables_array['tables']['c2plog']['columns']['sqlresult']['type'] = 'blob';
$c2p_tables_array['tables']['c2plog']['columns']['sqlresult']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlresult']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlresult']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['sqlresult']['extra'] = '';
// c2plog - sqlquery
$c2p_tables_array['tables']['c2plog']['columns']['sqlquery']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['sqlquery']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlquery']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlquery']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['sqlquery']['extra'] = '';
// c2plog - sqlerror
$c2p_tables_array['tables']['c2plog']['columns']['sqlerror']['type'] = 'mediumtext';
$c2p_tables_array['tables']['c2plog']['columns']['sqlerror']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlerror']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['sqlerror']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['sqlerror']['extra'] = '';
// c2plog - wordpresserror
$c2p_tables_array['tables']['c2plog']['columns']['wordpresserror']['type'] = 'mediumtext';
$c2p_tables_array['tables']['c2plog']['columns']['wordpresserror']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['wordpresserror']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['wordpresserror']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['wordpresserror']['extra'] = '';
// c2plog - screenshoturl
$c2p_tables_array['tables']['c2plog']['columns']['screenshoturl']['type'] = 'varchar(500)';
$c2p_tables_array['tables']['c2plog']['columns']['screenshoturl']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['screenshoturl']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['screenshoturl']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['screenshoturl']['extra'] = '';
// c2plog - userscomment
$c2p_tables_array['tables']['c2plog']['columns']['userscomment']['type'] = 'mediumtext';
$c2p_tables_array['tables']['c2plog']['columns']['userscomment']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['userscomment']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['userscomment']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['userscomment']['extra'] = '';
// c2plog - page
$c2p_tables_array['tables']['c2plog']['columns']['page']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['page']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['page']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['page']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['page']['extra'] = '';
// c2plog - version
$c2p_tables_array['tables']['c2plog']['columns']['version']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['version']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['version']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['version']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['version']['extra'] = '';
// c2plog - panelid
$c2p_tables_array['tables']['c2plog']['columns']['panelid']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['panelid']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['panelid']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['panelid']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['panelid']['extra'] = '';
// c2plog - panelname
$c2p_tables_array['tables']['c2plog']['columns']['panelname']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['panelname']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['panelname']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['panelname']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['panelname']['extra'] = '';
// c2plog - tabscreenid
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenid']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenid']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenid']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenid']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenid']['extra'] = '';
// c2plog - tabscreenname
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenname']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenname']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenname']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenname']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['tabscreenname']['extra'] = '';
// c2plog - dump
$c2p_tables_array['tables']['c2plog']['columns']['dump']['type'] = 'longblob';
$c2p_tables_array['tables']['c2plog']['columns']['dump']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['dump']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['dump']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['dump']['extra'] = '';
// c2plog - ipaddress
$c2p_tables_array['tables']['c2plog']['columns']['ipaddress']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['ipaddress']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['ipaddress']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['ipaddress']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['ipaddress']['extra'] = '';
// c2plog - userid
$c2p_tables_array['tables']['c2plog']['columns']['userid']['type'] = 'int(11)';
$c2p_tables_array['tables']['c2plog']['columns']['userid']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['userid']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['userid']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['userid']['extra'] = '';
// c2plog - comment
$c2p_tables_array['tables']['c2plog']['columns']['comment']['type'] = 'mediumtext';
$c2p_tables_array['tables']['c2plog']['columns']['comment']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['comment']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['comment']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['comment']['extra'] = '';
// c2plog - type
$c2p_tables_array['tables']['c2plog']['columns']['type']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['type']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['type']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['type']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['type']['extra'] = '';
// c2plog - category
$c2p_tables_array['tables']['c2plog']['columns']['category']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['category']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['category']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['category']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['category']['extra'] = '';
// c2plog - action
$c2p_tables_array['tables']['c2plog']['columns']['action']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['action']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['action']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['action']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['action']['extra'] = '';
// c2plog - priority
$c2p_tables_array['tables']['c2plog']['columns']['priority']['type'] = 'varchar(45)';
$c2p_tables_array['tables']['c2plog']['columns']['priority']['null'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['priority']['key'] = '';
$c2p_tables_array['tables']['c2plog']['columns']['priority']['default'] = 'NULL';
$c2p_tables_array['tables']['c2plog']['columns']['priority']['extra'] = '';
##################################################################################
#                               c2pprojects                                      #
##################################################################################        
$c2p_tables_array['tables']['c2pprojects']['name'] = $wpdb->prefix . 'c2pprojects';
$c2p_tables_array['tables']['c2pprojects']['required'] = true;// required for all installations or not (boolean)
$c2p_tables_array['tables']['c2pprojects']['pluginversion'] = '8.1.1';
$c2p_tables_array['tables']['c2pprojects']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$c2p_tables_array['tables']['c2pprojects']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$c2p_tables_array['tables']['c2pprojects']['primarykey'] = 'projectid';
$c2p_tables_array['tables']['c2pprojects']['uniquekey'] = 'projectid';
##################################################################################
#                               c2psources                                      #
##################################################################################        
$c2p_tables_array['tables']['c2psources']['name'] = $wpdb->prefix . 'c2psources';
$c2p_tables_array['tables']['c2psources']['required'] = true;// required for all installations or not (boolean)
$c2p_tables_array['tables']['c2psources']['pluginversion'] = '8.1.1';
$c2p_tables_array['tables']['c2psources']['usercreated'] = false;// if the table is created as a result of user actions rather than core installation put true
$c2p_tables_array['tables']['c2psources']['version'] = '0.0.1';// used to force updates based on version alone rather than individual differences
$c2p_tables_array['tables']['c2psources']['primarykey'] = 'sourceid';
$c2p_tables_array['tables']['c2psources']['uniquekey'] = 'sourceid';                
?>