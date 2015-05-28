<?php
/**
 * @version   1.0 October 16, 2010
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2010 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('GANTRY_VERSION') or die();

gantry_import('core.gantrygizmo');

/**
 * @package     gantry
 * @subpackage  features
 */
 
class GantryGizmoTypographyShortcodes extends GantryGizmo {

    var $_name = 'typographyshortcodes';

    function query_parsed_init() {
    	add_shortcode('h1', array('GantryGizmoTypographyShortcodes', 'roktypo_h1'));
    	add_shortcode('h2', array('GantryGizmoTypographyShortcodes', 'roktypo_h2'));
    	add_shortcode('h3', array('GantryGizmoTypographyShortcodes', 'roktypo_h3'));
    	add_shortcode('h4', array('GantryGizmoTypographyShortcodes', 'roktypo_h4'));
    	add_shortcode('h5', array('GantryGizmoTypographyShortcodes', 'roktypo_h5'));
    	add_shortcode('contentheading', array('GantryGizmoTypographyShortcodes', 'roktypo_contentheading'));
    	add_shortcode('componentheading', array('GantryGizmoTypographyShortcodes', 'roktypo_componentheading'));
    	add_shortcode('div2', array('GantryGizmoTypographyShortcodes', 'roktypo_div2'));
    	add_shortcode('div', array('GantryGizmoTypographyShortcodes', 'roktypo_div'));
    	add_shortcode('div3', array('GantryGizmoTypographyShortcodes', 'roktypo_div3'));
    	add_shortcode('pre', array('GantryGizmoTypographyShortcodes', 'roktypo_pre'));
    	add_shortcode('blockquote', array('GantryGizmoTypographyShortcodes', 'roktypo_blockquote'));
    	add_shortcode('list', array('GantryGizmoTypographyShortcodes', 'roktypo_list'));
    	add_shortcode('li', array('GantryGizmoTypographyShortcodes', 'roktypo_li'));
    	add_shortcode('emphasis', array('GantryGizmoTypographyShortcodes', 'roktypo_emphasis'));
    	add_shortcode('emphasisbold', array('GantryGizmoTypographyShortcodes', 'roktypo_emphasisbold'));
    	add_shortcode('inset', array('GantryGizmoTypographyShortcodes', 'roktypo_inset'));
    	add_shortcode('dropcap', array('GantryGizmoTypographyShortcodes', 'roktypo_dropcap'));
    	add_shortcode('important', array('GantryGizmoTypographyShortcodes', 'roktypo_important'));
    	add_shortcode('underline', array('GantryGizmoTypographyShortcodes', 'roktypo_underline'));
    	add_shortcode('bold', array('GantryGizmoTypographyShortcodes', 'roktypo_bold'));
    	add_shortcode('italic', array('GantryGizmoTypographyShortcodes', 'roktypo_italic'));
    	add_shortcode('clear', array('GantryGizmoTypographyShortcodes', 'roktypo_clear'));
    	add_shortcode('readon', array('GantryGizmoTypographyShortcodes', 'roktypo_readon'));
    	add_shortcode('readon2', array('GantryGizmoTypographyShortcodes', 'roktypo_readon2'));
	}
	
	function roktypo_h1($atts, $content = null) {
		return '<h1>'.$content.'</h1>';
	}
	
	function roktypo_h2($atts, $content = null) {
		return '<h2>'.$content.'</h2>';
	}
	
	function roktypo_h3($atts, $content = null) {
		return '<h3>'.$content.'</h3>';
	}
	
	function roktypo_h4($atts, $content = null) {
		return '<h4>'.$content.'</h4>';
	}
	
	function roktypo_h5($atts, $content = null) {
		return '<h5>'.$content.'</h5>';
	}
	
	function roktypo_contentheading($atts, $content = null) {
		return '<div class="contentheading">'.$content.'</div>';
	}
	
	function roktypo_componentheading($atts, $content = null) {	
		return '<div class="component-header"><h2 class="componentheading">'.$content.'</h2></div>';
	}
	
	function roktypo_div2($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		return '<div'.$class.'>'.do_shortcode($content).'</div>';
	}
	
	function roktypo_div($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'class2' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		if($class2 != '') $class2 = ' class="'.$class2.'"';
		return '<div'.$class.'><div'.$class2.'>'.do_shortcode($content).'</div></div>';
	}
	
	function roktypo_div3($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => '',
			'class2' => '',
			'class3' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		if($class2 != '') $class2 = ' class="'.$class2.'"';
		if($class3 != '') $class3 = ' class="'.$class3.'"';
		return '<div'.$class.'><div'.$class2.'><div'.$class3.'>'.do_shortcode($content).'</div></div></div>';
	}
	
	function roktypo_pre($atts, $content = null) {	
		return '<pre>'.$content.'</pre>';
	}
	
	function roktypo_blockquote($atts, $content = null) {	
		return '<blockquote>'.$content.'</blockquote>';
	}
	
	function roktypo_list($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));

		if($class != '') $class = ' class="'.$class.'"';
		return '<ul'.$class.'>'.do_shortcode($content).'</ul>';
	}
	
	function roktypo_li($atts, $content = null) {
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));
		
		if($class != '') : 
			return '<li><div class="'.$class.'">'.do_shortcode($content).'</div></li>';
		else :
			return '<li>'.do_shortcode($content).'</li>';
		endif;
	}
	
	function roktypo_emphasis($atts, $content = null) {	
		return '<em class="color">'.$content.'</em>';
	}
	
	function roktypo_emphasisbold($atts, $content = null) {	
		return '<em class="bold">'.$content.'</em>';
	}
	
	function roktypo_inset($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => '',
			'side' => 'left'
		), $atts));

		return '<span class="inset-'.$side.'"><span class="inset-'.$side.'-title">'.$title.'</span>'.$content.'</span>';
	}
	
	function roktypo_dropcap($atts, $content = null) {
		extract(shortcode_atts(array(
			'cap' => ''
		), $atts));

		return '<p class="dropcap"><span class="dropcap">'.$cap.'</span>'.$content.'</p>';
	}
	
	function roktypo_important($atts, $content = null) {
		extract(shortcode_atts(array(
			'title' => ''
		), $atts));

		return '<div class="important"><span class="important-title">'.$title.'</span>'.$content.'</div>';
	}
	
	function roktypo_underline($atts, $content = null) {	
		return '<span style="text-decoration:underline;">'.$content.'</span>';
	}
	
	function roktypo_bold($atts, $content = null) {	
		return '<span style="font-weight:bold;">'.$content.'</span>';
	}
	
	function roktypo_italic($atts, $content = null) {	
		return '<span style="font-style:italic;">'.$content.'</span>';
	}
	
	function roktypo_clear($atts, $content = null) {	
		return '<div class="clear"></div>';
	}
	
	function roktypo_readon($atts, $content = null) {
		extract(shortcode_atts(array(
			'url' => ''
		), $atts));

		return '<p class="rt-readon-surround"><a class="readon" href="'.$url.'"><span>'.$content.'</span></a></p>';
	}
	
	function roktypo_readon2($atts, $content = null) {
		extract(shortcode_atts(array(
			'url' => ''
		), $atts));

		return '<a class="readon" href="'.$url.'"><span>'.$content.'</span></a>';
	}
	
}