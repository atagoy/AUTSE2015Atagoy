<?php
/** 
 * Default administration settings for CSV 2 POST plugin. These settings are installed to the 
 * wp_options table and are used from there by default. 
 * 
 * @package CSV 2 POST
 * @author Ryan Bayne   
 * @since 8.0.0
 * @version 1.0.7
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// install main admin settings option record
$csv2post_settings = array();
$csv2post_settings['currentproject'] = false;// id 1 may not always exist but we will handle that automatically at some stage
// encoding
$csv2post_settings['standardsettings']['encoding']['type'] = 'utf8';
// admin user interface settings start
$csv2post_settings['standardsettings']['ui_advancedinfo'] = false;// hide advanced user interface information by default
// other
$csv2post_settings['standardsettings']['ecq'] = array();
$csv2post_settings['standardsettings']['chmod'] = '0750';
$csv2post_settings['standardsettings']['systematicpostupdating'] = 'enabled';
// testing and development
$csv2post_settings['standardsettings']['developementinsight'] = 'disabled';
// global switches
$csv2post_settings['standardsettings']['textspinrespinning'] = 'enabled';// disabled stops all text spin re-spinning and sticks to the last spin

##########################################################################################
#                                                                                        #
#                           SETTINGS WITH NO UI OPTION                                   #
#              array key should be the method/function the setting is used in            #
##########################################################################################
$csv2post_settings['create_localmedia_fromlocalimages']['destinationdirectory'] = 'wp-content/uploads/importedmedia/';
 
##########################################################################################
#                                                                                        #
#                            DATA IMPORT AND MANAGEMENT SETTINGS                         #
#                                                                                        #
##########################################################################################
$csv2post_settings['datasettings']['insertlimit'] = 100;

##########################################################################################
#                                                                                        #
#                                    WIDGET SETTINGS                                     #
#                                                                                        #
##########################################################################################
$csv2post_settings['widgetsettings']['dashboardwidgetsswitch'] = 'disabled';

##########################################################################################
#                                                                                        #
#                                    WIDGET SETTINGS                                     #
#                                                                                        #
##########################################################################################
$csv2post_settings['flagsystem']['status'] = 'disabled';

##########################################################################################
#                                                                                        #
#                                    NOTICE SETTINGS                                     #
#                                                                                        #
##########################################################################################
$csv2post_settings['noticesettings']['wpcorestyle'] = 'enabled';

##########################################################################################
#                                                                                        #
#                           YOUTUBE RELATED SETTINGS                                     #
#                                                                                        #
##########################################################################################
$csv2post_settings['youtubesettings']['defaultcolor'] = '&color1=0x2b405b&color2=0x6b8ab6';
$csv2post_settings['youtubesettings']['defaultborder'] = 'enable';
$csv2post_settings['youtubesettings']['defaultautoplay'] = 'enable';
$csv2post_settings['youtubesettings']['defaultfullscreen'] = 'enable';
$csv2post_settings['youtubesettings']['defaultscriptaccess'] = 'always';

##########################################################################################
#                                                                                        #
#                                  LOG SETTINGS                                          #
#                                                                                        #
##########################################################################################
$csv2post_settings['logsettings']['uselog'] = 1;
$csv2post_settings['logsettings']['loglimit'] = 1000;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['outcome'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['timestamp'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['line'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['function'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['page'] = true; 
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['panelname'] = true;   
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['userid'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['type'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['category'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['action'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['priority'] = true;
$csv2post_settings['logsettings']['logscreen']['displayedcolumns']['comment'] = true;

##########################################################################################
#                                                                                        #
#                             DEFAULT PROJECT OPTIONS                                    #
#                                                                                        #
##########################################################################################
$csv2post_settings['projectdefaults']['basicsettings']['poststatus'] = 'draft';
$csv2post_settings['projectdefaults']['basicsettings']['pingstatus'] = 'closed';// open | closed
$csv2post_settings['projectdefaults']['basicsettings']['commentstatus'] = 'open';// open | closed
$csv2post_settings['projectdefaults']['basicsettings']['defaultauthor'] = 1;// user id
$csv2post_settings['projectdefaults']['basicsettings']['defaultcategory'] = 0;// category id
$csv2post_settings['projectdefaults']['basicsettings']['defaultposttype'] = 'post';// post,page or a custom post type
$csv2post_settings['projectdefaults']['basicsettings']['defaultpostformat'] = 'standard';// aside,gallery,link,image,quote,status,video,audio,chat 
$csv2post_settings['projectdefaults']['basicsettings']['categoryassignment'] = 'all';// all or last

// wordpress post settings requiring data
$csv2post_settings['projectdefaults']['permalinks']['table'] = '';
$csv2post_settings['projectdefaults']['permalinks']['column'] = '';
$csv2post_settings['projectdefaults']['cloaking']['urlcloak1']['table'] = '';
$csv2post_settings['projectdefaults']['cloaking']['urlcloak1']['column'] = '';

$csv2post_settings['projectdefaults']['images']['featuredimage']['table'] = '';
$csv2post_settings['projectdefaults']['images']['featuredimage']['column'] = '';

// author settings
$csv2post_settings['projectdefaults']['authors']['email']['table'] = '';
$csv2post_settings['projectdefaults']['authors']['email']['column'] = '';
$csv2post_settings['projectdefaults']['authors']['username']['table'] = '';
$csv2post_settings['projectdefaults']['authors']['username']['column'] = '';
                                          
// title template
$csv2post_settings['projectdefaults']['titles']['defaulttitletemplate'] = ''; 

// content template                
$csv2post_settings['projectdefaults']['content']['wysiwygdefaultcontent'] = '';

// seo based custom fields
$csv2post_settings['projectdefaults']['customfields']['seotitlekey'] = '';
$csv2post_settings['projectdefaults']['customfields']['seotitletemplate'] = '';                
$csv2post_settings['projectdefaults']['customfields']['seodescriptionkey'] = '';
$csv2post_settings['projectdefaults']['customfields']['seodescriptiontemplate'] = '';                       
$csv2post_settings['projectdefaults']['customfields']['seokeywordskey'] = '';
$csv2post_settings['projectdefaults']['customfields']['seokeywordstemplate'] = '';

// custom fields                    
$csv2post_settings['projectdefaults']['customfields']['cflist'][0]['id'] = 0;// used in WP_Table class to pass what is actually the key, use they key though where unique value is required
$csv2post_settings['projectdefaults']['customfields']['cflist'][0]['name'] = '';// the key
$csv2post_settings['projectdefaults']['customfields']['cflist'][0]['value'] = '';// on UI it is a template 
$csv2post_settings['projectdefaults']['customfields']['cflist'][0]['unique'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][0]['updating'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][1]['id'] = 1;
$csv2post_settings['projectdefaults']['customfields']['cflist'][1]['name'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][1]['value'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][1]['unique'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][1]['updating'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][2]['id'] = 2;
$csv2post_settings['projectdefaults']['customfields']['cflist'][2]['name'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][2]['value'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][2]['unique'] = '';
$csv2post_settings['projectdefaults']['customfields']['cflist'][2]['updating'] = '';

// content template design rules
$csv2post_settings['projectdefaults']['content']['designrule1']['column'] = '';
$csv2post_settings['projectdefaults']['content']['designruletrigger1'] = '';
$csv2post_settings['projectdefaults']['content']['designtemplate1'] = '';
$csv2post_settings['projectdefaults']['content']['designrule2']['column'] = '';
$csv2post_settings['projectdefaults']['content']['designruletrigger2'] = '';
$csv2post_settings['projectdefaults']['content']['designtemplate2'] = '';
$csv2post_settings['projectdefaults']['content']['designrule3']['column'] = '';
$csv2post_settings['projectdefaults']['content']['designruletrigger3'] = '';
$csv2post_settings['projectdefaults']['content']['designtemplate3'] = '';

// images and media requireing token or shortcode to be entered to post content
$csv2post_settings['projectdefaults']['content']['enablegroupedimageimport'] = 'disabled';
$csv2post_settings['projectdefaults']['content']["localimages"]['table'] = '';
$csv2post_settings['projectdefaults']['content']["localimages"]['column'] = '';
$csv2post_settings['projectdefaults']['content']['incrementalimages'] = 'enabled';
$csv2post_settings['projectdefaults']['content']['groupedimagesdir'] = '';

// category pre-set level one
$csv2post_settings['projectdefaults']['categories']['presetcategoryid'] = false;

// category levels
$csv2post_settings['projectdefaults']['categories']['data'][0]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][0]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][1]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][1]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][2]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][2]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][3]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][3]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][4]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['data'][4]['column'] = '';
                                                    
// category descriptions from data      
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][0]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][0]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][1]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][1]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][2]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][2]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][3]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][3]['column'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][4]['table'] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiondata'][4]['column'] = '';

// category description templates
$csv2post_settings['projectdefaults']['categories']['descriptiontemplates'][0] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiontemplates'][1] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiontemplates'][2] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiontemplates'][3] = '';
$csv2post_settings['projectdefaults']['categories']['descriptiontemplates'][4] = '';

// adoption
$csv2post_settings['projectdefaults']['adoption']['adoptionmethod'] = 'title';// title | slug | content | meta 
$csv2post_settings['projectdefaults']['adoption']['allowedchanges'] = 'everything';// everything | meta | content
$csv2post_settings['projectdefaults']['adoption']['adoptionmetakey'] = '';
$csv2post_settings['projectdefaults']['adoption']['adoptionmeta']['column'] = '';

// tags
$csv2post_settings['projectdefaults']['tags']['table'] = '';// ready made tags
$csv2post_settings['projectdefaults']['tags']['column'] = '';// ready made tags
$csv2post_settings['projectdefaults']['tags']['textdata']['column'] = '';// enter a comma separated string of column names which have text suitable for generating tags from
$csv2post_settings['projectdefaults']['tags']['defaultnumerics'] = 'disallow';// allow | disallow
$csv2post_settings['projectdefaults']['tags']['tagstringlength'] = '50';// enter maximum length of all tags for a post when in a comma separated string
$csv2post_settings['projectdefaults']['tags']['maximumtags'] = '3';// enter maximum number of separate tags for all posts
$csv2post_settings['projectdefaults']['tags']['excludedtags'] = 'to,from,about,where,too,see,him,his,her,hers,ours,were';

// post type rules
$csv2post_settings['projectdefaults']['posttypes']['posttyperule1']['table'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperule1']['column'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuletrigger1'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuleposttype1'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperule2']['table'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperule2']['column'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuletrigger2'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuleposttype2'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperule3']['table'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperule3']['column'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuletrigger3'] = '';
$csv2post_settings['projectdefaults']['posttypes']['posttyperuleposttype3'] = '';

$csv2post_settings['projectdefaults']['dates']['publishdatemethod'] = 'wordpress';// wordpress | data | incremental | random
$csv2post_settings['projectdefaults']['dates']['table'] = '';
$csv2post_settings['projectdefaults']['dates']['column'] = '';
$csv2post_settings['projectdefaults']['dates']['dateformat'] = 'noformat';// noformat | uk | us | mysql | unsure

$csv2post_settings['projectdefaults']['dates']['incrementalstartdate'] = '';
$csv2post_settings['projectdefaults']['dates']['naturalvariationlow'] = '30';// enter the mininum number of minutes to add to the increment minutes (used to create a random more natural variation per increment)
$csv2post_settings['projectdefaults']['dates']['naturalvariationhigh'] = '90';// enter the maximum number of minutes to add to the incremental number of minutes
$csv2post_settings['projectdefaults']['dates']['randomdateearliest'] = '';
$csv2post_settings['projectdefaults']['dates']['randomdatelatest'] = '';

// post updating
$csv2post_settings['projectdefaults']['updating']['updatepostonviewing'] = '';// enabled | disabled
?>