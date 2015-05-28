<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined('GANTRY_VERSION') or die('Restricted access');

/*
 * Created on Jan 16, 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CrystallineTouchMenuFormatter extends AbstractRokMenuFormatter {
    function format_subnode(&$node) {
        // Format the current node
        if ($node->hasChildren()) {
            $node->addLinkClass("daddy");
        } else {
            $node->addLinkClass("orphan");
        }

        $node->addLinkClass("item");

        if ($node->getLevel() == 0) {
            $node->addListItemClass("root");

        }

    }

}