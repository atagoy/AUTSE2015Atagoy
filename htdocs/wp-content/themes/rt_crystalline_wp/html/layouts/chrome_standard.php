<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutChrome_Standard extends GantryLayout {
    var $render_params = array(
        'gridCount'     =>  null,
        'prefixCount'   =>  0,
        'extraClass'      =>  ''
    );

    function render($params = array()){
        global $gantry, $wp_registered_widgets;
        $rparams = $this-> _getParams($params[0]);
        $instance_params = $this->_getWidgetInstanceParams($params[0]['widget_id']);
        
        $id =  $params[0]['widget_id'];
        $classname = $wp_registered_widgets[$params[0]['widget_id']]['classname'];

        // gantry render params
        $params[0]['pre_widget'] ='';
        $params[0]['widget_open'] ='';
        $params[0]['title_open'] ='';
        $params[0]['title_close'] ='';
        $params[0]['widget_close'] ='';
        $params[0]['post_widget'] ='';
        $params[0]['pre_render'] ='';
        $params[0]['post_render'] ='';

        // normal wp widget params
        $params[0]['before_widget'] = '';
        $params[0]['before_title']  = '';
        $params[0]['after_title']  = '';
        $params[0]['after_widget']  = '';

        if ((array_key_exists('widgetstyle',$instance_params) && $instance_params['widgetstyle']!='') || (array_key_exists('variation',$instance_params) && $instance_params['variation']!='')) {
            if ($instance_params['widgetstyle'] != '' && $instance_params['variation'] != '') : $instance_params['variation'] = ' '.$instance_params['variation']; endif;
            $params[0]['pre_widget'] = '<div class="'.$instance_params['widgetstyle'].$instance_params['variation'].'">';
            $params[0]['post_widget'] = '</div>';
        }

        $params[0]['widget_open'] = sprintf('<div id="%1$s" class="widget %2$s rt-block"><div class="module-content"><div class="module-tm"><div class="module-tl"><div class="module-tr"></div></div></div><div class="module-l"><div class="module-r">', $id, $classname);
        $params[0]['widget_close'] = '</div></div><div class="module-bm"><div class="module-bl"><div class="module-br"></div></div></div></div></div>';
 
 		if($instance_params['title'] != '') :
 			$pre_render_extra = '<div class="module-inner2">';
 		else :
 			$pre_render_extra = '<div class="module-inner3">';
 		endif;	
        
        $params[0]['pre_render'] = '<div class="module-inner">'.$pre_render_extra;
        $params[0]['post_render'] = '</div></div>';

        $params[0]['title_open'] =  '<div class="module-title"><div class="module-title2"><h2 class="title">';
        $params[0]['title_close'] = '</h2></div></div>';

        if($instance_params['title'] != '') :
	        $params[0]['before_widget'] = $params[0]['pre_widget'].$params[0]['widget_open'];
	        $params[0]['before_title'] = $params[0]['title_open'];
	        $params[0]['after_title'] =  $params[0]['title_close'].$params[0]['pre_render'];
	        $params[0]['after_widget'] = $params[0]['post_render'].$params[0]['widget_close'].$params[0]['post_widget'];
		else :
			$params[0]['before_widget'] = $params[0]['pre_widget'].$params[0]['widget_open'].$params[0]['pre_render'];
	        $params[0]['before_title'] = $params[0]['title_open'];
	        $params[0]['after_title'] =  $params[0]['title_close'];
	        $params[0]['after_widget'] = $params[0]['post_render'].$params[0]['widget_close'].$params[0]['post_widget'];
		endif;
        
        return $params;
        
    }
}