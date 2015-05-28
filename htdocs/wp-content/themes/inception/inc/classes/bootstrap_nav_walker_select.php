<?php 

/**
 * Class Name: select_mobile_Walker_Nav_Menu
 * GitHub URI: not available
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a mobile friendly method in order to support multi level menus.
 * Version: 1.0.0
 * Author: not available
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */
//exit if accessed directly
if(!defined('ABSPATH')) exit;

class select_mobile_Walker_Nav_Menu extends Walker_Nav_Menu {

  function start_lvl(&$output, $depth=0, $args=array()) {

	//  $indent = str_repeat("\t", $depth);
	//   $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
  }
  function start_el( &$output, $item, $depth=0, $args=array(), $id=0 ) {
	  global $wp;
	  	$indent = "- ";
	  	(strpos($item->title,"[dd-ph]") !== false) ? $is_dd_placeholder = true : $is_dd_placeholder = false;
	  
	  	$indent = ( $depth ) ? str_repeat( "- ", $depth ) : '';
		$class_names = $value = '';
	  	 //	var_dump($item->title);
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		if($is_dd_placeholder)	$classes[] = 'dd-ph';
		$classes[] = "depth".$depth;
		$classes_str = implode(" ",$classes);
		if(strpos($classes_str,"menu-item-has-children") !== false) {
		//	if(  $depth == 0 ) $classes[] .= ' dropdown';	
		}
		if(  $depth == 1 && strpos($classes_str,"menu-item-has-children") !== false  ) {
		
		//		$classes[] = " dropdown-submenu";
		//	var_dump($css_class);
		//	
		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		//	$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		if( $is_dd_placeholder	)	$atts['data-toggle'] = "dropdown";
		if( $is_dd_placeholder	)	$atts['class'] = 'dropdown-toggle';
		

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
		 *
		 *     @type string $title  The title attribute.
		 *     @type string $target The target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item The current menu item.
		 * @param array  $args An array of arguments. @see wp_nav_menu()
		 */
		 
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		
		/*
		$item_output .= '<a'. $attributes .'>';
		if(strpos($classes_str,"fa_") !== false ) {
			foreach ($classes as $clas) {
				if(strpos($clas,"fa_") !== false) 	{
						$fa_name = str_replace("fa_","",$clas);
						$item_output .= '<i class="fa fa-'.$fa_name.'"></i>';
				}
			}	
		}
		*/
		
		/** This filter is documented in wp-includes/post-template.php */
		
		/*
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		*/
		
		$is_selected = '';
		//	$pg_url = get_bloginfo('wpurl').$_SERVER['REQUEST_URI'];
		// _eo-review: do we really need all these 3 ? Test with different permalinks and cut down to 1 var
		$current_u = add_query_arg( $wp->query_string, '', home_url( '/' . $wp->request ) );
		$current_ur = home_url( '/' . $wp->request ) ;
		$current_url = trailingslashit(home_url( '/' . $wp->request ) );

		if ( $current_u == $item->url || $current_ur == $item->url || $current_url == $item->url ) $is_selected = ' selected="selected"';
		
		if ($is_dd_placeholder) {
			$rep_title = str_replace("[dd-ph]","",$item->title);
			$output .= "<optgroup class='mobile-children-optgr mobile-dropd-menu depth".$depth."' label='".$rep_title."'></optgroup>\n";
		}
		else {
			$output .= '<option ' . $id . $class_names . ' value="' . $item->url . '"'.$is_selected.'>' . $indent . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after . '</option>';
		}
				
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

	  
  }
}
?>