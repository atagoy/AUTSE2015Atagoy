<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
 
class CrystallineSplitMenuTheme extends AbstractRokMenuTheme {

    protected $defaults = array(
        'fusion_load_css' => 0,
        'fusion_enable_js' => 1,
        'fusion_opacity' => 1,
        'fusion_effect' => 'slidefade',
        'fusion_hidedelay' => 500,
        'fusion_menu_animation' => 'Sine.easeOut',
        'fusion_menu_duration' => 200,
        'fusion_pill' => 0,
        'fusion_pill_animation' => 'Sine.easeOut',
        'fusion_pill_duration' => 250,
        'fusion_centeredOffset' => 0,
        'fusion_tweakInitial_x' => 0,
        'fusion_tweakInitial_y' => -1,
        'fusion_tweakSubsequent_x' => 1,
        'fusion_tweakSubsequent_y' => -1,
        'fusion_enable_current_id' => 0
    );

    public function getFormatter($args){
        require_once(dirname(__FILE__).'/formatter.php');
        return new CrystallineSplitMenuFormatter($args);
    }

    public function getLayout($args){
        global $gantry;
        require_once(dirname(__FILE__).'/layout.php');
        return new CrystallineSplitMenuLayout($args);
    }
}
