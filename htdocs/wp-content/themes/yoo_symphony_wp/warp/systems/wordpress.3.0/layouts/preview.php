<?php
/**
* @package   Warp Theme Framework
* @file      preview.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $wp_registered_widgets;

$default_options = array(
    'style' => '',
    'icon' => '',
    'badge' => '',
    'permission' => array('*') 
);

// render default modules
switch ($position) {

	case 'logo':

	    $wp_registered_widgets['text-0'] = array(
	        'id' => 'text-0',
	        'name' => 'Text'
	    ); 

	    $this->warp->system->widget_options['text-0'] = $default_options;

	    echo '<!--widget-text-0-->Logo<!--widget-end-->';
		break;
		
	case 'right':
		
	    $wp_registered_widgets['search-0'] = array(
	        'id' => 'search-0',
	        'name' => 'Search'
	    );

		$wp_registered_widgets['archives-0'] = array(
	        'id' => 'archives-0',
	        'name' => 'Archives'
	    );
	    
	    $this->warp->system->widget_options['search-0'] = $default_options;
		$this->warp->system->widget_options['archives-0'] = $default_options;
		
	    echo "<!--widget-search-0--><!--title-start-->Search<!--title-end-->";
		get_search_form();
		echo "<!--widget-end-->\n";
		echo "<!--widget-archives-0--><!--title-start-->Archive<!--title-end--><ul>";
		wp_get_archives('type=monthly');
		echo "</ul><!--widget-end-->";
		break;

}