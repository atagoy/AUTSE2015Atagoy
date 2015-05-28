<?php
/**
* @package   Warp Theme Framework
* @file      modules.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

if (function_exists('dynamic_sidebar')) {
	
	// get widgets
	ob_start();
	$result = dynamic_sidebar($position);
	$position_output = ob_get_clean();

	if ($position == 'menu') {
	    $result = true;
	    $position_output = $this->render('menu').((string)$position_output);
	}
	
	// handle preview
	if (!$result && $this->warp->system->isPreview($position)) {

	    $result = true;
		$position_output = $this->render('preview', compact('position'));
	}
	
	if ($result) {

		$count   = (int) preg_match_all('/<!--widget-([a-z0-9-_]+-[0-9]+)-->(.*)<!--widget-end-->/simU', $position_output, $matches, PREG_SET_ORDER);
		$modules = array();
		
		//prepare modules
		for ($i = 0; $i < $count; $i++) {
			
			$content = $matches[$i][2];

			// has title ?
			if (preg_match('/<!--title-start-->(.*)<!--title-end-->/simU', $content, $title)) {
				$content = str_replace($title[0], '', $content);
				$title = $title[1]; 
			} else {
				$title = '';
			}
			
			$module            = $this->warp->system->getWidget($matches[$i][1]);
			$module->title     = strip_tags($title);
            $module->showtitle = isset($module->options['title']) ? $module->options['title'] : 1;
			$module->content   = $content;
            $module->position  = $position;

            if (!$module->display) continue;
			
			$modules[] = $module;
		
		}
		
		$count = count($modules);

		//output modules
        for ($i = 0; $i < $count; $i++) {
			
			$module = $modules[$i];
          
			// set params
            $params          = $module->options;
		    $params['count'] = $count;
            $params['order'] = $i + 1;
            $params['first'] = $params['order'] == 1;
            $params['last']  = $params['order'] == $count;
            $params['menu']  = false;

            switch ($module->type) {
                
				// menu
                case 'nav_menu':
                    $params['menu']  = $position == 'menu' ? 'dropdown':'accordion';
                    $params['style'] = 'menu';
                    break;
				
				// core overrides
                case 'search':
                case 'links':                
                case 'categories':
                case 'pages':
                case 'archives':
                case 'recent-posts':
                case 'recent-comments':
                case 'calendar':
                case 'meta':
                case 'rss':
                case 'tag_cloud':
                case 'text':
                    $module->content = $this->render('widgets/'.$module->type, array('params' => $module->params, 'position'=> $position, 'oldoutput' => $module->content));
                    break;
            }
            
			$module->position_params = $params;
			
			// render module
           	$output = $this->render('module', compact('module', 'params'));
        
			// wrap module
			if (isset($wrapper) && $wrapper) {
				$layout = isset($layout) ? $layout : 'default';
				$output = $this->render('wrapper', compact('output', 'wrapper', 'layout', 'params'));
			}
        
        	echo $output;
		}
	}

}