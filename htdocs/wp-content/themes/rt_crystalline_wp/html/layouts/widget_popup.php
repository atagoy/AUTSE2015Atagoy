<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutWidget_Popup extends GantryLayout {
    var $render_params = array(
        'gridCount'     =>  null,
        'prefixCount'   =>  0,
        'extraClass'      =>  ''
    );
    function render($params = array()){
        global $gantry;

        $params = $gantry->renderLayout("chrome_".$params[0]['chrome'], $params);

        $params[0]['position_open'] ='';
        $params[0]['position_close'] ='';

        $rparams = $this->_getParams($params[0]);
        $start_tag = "";

        // see if this is the first widget in the postion
        if (property_exists($rparams,'start') && $rparams->start == $rparams->widget_id) {
            ob_start();
            ?>
            <div id="rt-popup">
            <?php
            $start_tag = ob_get_clean();
            $params[0]['position_open'] = $start_tag;
        }

        if (property_exists($rparams,'end') && $rparams->end == $rparams->widget_id) {
             $params[0]['position_close'] = "</div>";
        }

        $params[0]['before_widget'] = $params[0]['position_open'].$params[0]['before_widget'] ;
        $params[0]['after_widget'] = $params[0]['after_widget'] . $params[0]['position_close'];
        
        return $params;
    }
}