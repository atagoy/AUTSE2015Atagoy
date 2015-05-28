<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
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
		
		$parts   = explode('<!--widget-end-->', $position_output);
		$modules = array();
		
		//prepare modules
		foreach ($parts as $part) {
			
			if (!preg_match('/<!--widget-([a-z0-9-_]+)-->/smU', $part, $matches)) continue;

			$module  = $this->warp->system->getWidget($matches[1]);
			$content = str_replace($matches[0], '', $part);
			$title   = '';

			// display it ?
            if (!$module->display) continue;

			// has title ?
			if (preg_match('/<!--title-start-->(.*)<!--title-end-->/smU', $content, $matches)) {
				$content = str_replace($matches[0], '', $content);
				$title = $matches[1]; 
			}
			
			$module->title     = strip_tags($title);
            $module->showtitle = isset($module->options['title']) ? $module->options['title'] : 1;
			$module->content   = $content;
            $module->position  = $position;
			
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
                    
                    $module->showAllChildren = 0;
                    
					if (!isset($menu)) {
						$params['menu']  = $position == 'menu' ? 'dropdown':'accordion';
						$params['style'] = 'menu';
					} else {
						$params['menu'] = $menu;
					}
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