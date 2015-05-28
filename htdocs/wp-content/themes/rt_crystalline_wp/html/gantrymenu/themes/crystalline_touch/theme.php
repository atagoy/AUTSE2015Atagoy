<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
 
class CrystallineTouchMenuTheme extends AbstractRokMenuTheme {

    protected $defaults = array(

    );

    public function getFormatter($args){
        require_once(dirname(__FILE__).'/formatter.php');
        return new CrystallineTouchMenuFormatter($args);
    }

    public function getLayout($args){
        require_once(dirname(__FILE__).'/layout.php');
        return new CrystallineTouchMenuLayout($args);
    }
}
