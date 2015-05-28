<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:37
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/*
	Class: YPagination
		The YPagination Class. Provides Pagination functionality.
*/
class YPagination {

	var $name = null;
	var $total = null;
	var $current = null;
	var $limit = null;
	var $range = null;
	var $pages = null;
	var $showall = false;

	/*
    	Function: constructor
 		
		Parameters:
	      $name - Name of the pager http get var.
	      $total - Total item count.
	      $current - Current page.
	      $limit - Item limit per page.
	      $range - Range for displayed pagination pages.
	
	   Returns:
	      Void
 	*/
	function YPagination($name, $total, $current = 1, $limit = 10, $range = 10) {

		// init vars
		$this->name    = $name;
		$this->total   = (int) max($total, 0);
		$this->current = (int) max($current, 1);
		$this->limit   = (int) max($limit, 1);
        $this->range   = (int) max($range, 1);
		$this->pages   = (int) ceil($this->total / $this->limit);
	} 

	/*
    	Function: getShowAll
 			Get show all items parameter.

	   Returns:
	      Boolean	
 	*/
	function getShowAll() {
		return $this->showall;
	}

	/*
    	Function: setShowAll
 			Set show all items parameter.

		Parameters:
	      $showall - Show all parameter.

	   Returns:
	      Void	
 	*/
	function setShowAll($showall) {
		$this->showall = $showall;
	}

	/*
    	Function: limitStart
 			Get limit current limit start.

	   Returns:
	      Int
 	*/
	function limitStart() {
		return ($this->current - 1) * $this->limit;
	}

	/*
    	Function: link
 			Get link with added get parameter.

	   Returns:
			String - Link url
 	*/
	function link($url, $vars) {
		
		if (!is_array($vars)) {
			$vars = array($vars);
		}
		
		return $url.(strpos($url, '?') === false ? '?' : '&').implode('&', $vars);
	}

	/*
    	Function: render
 			Render the paginator.

	   Returns:
			String - Pagination html
 	*/
    function render($url = 'index.php') {

		$html = '';

		// check if show all
		if ($this->showall) {
			return $html;
		}
		
		// check if current page is valid
        if ($this->current > $this->pages) {
            $this->current = $this->pages;
		}
		
		if ($this->pages > 1) {
					
			$range_start = max($this->current - $this->range, 1);
			$range_end   = min($this->current + $this->range - 1, $this->pages);

            if ($this->current > 1) {
				$link  = $this->link($url, $this->name.'=1');
                $html .= '<a class="start" href="'.JRoute::_($link).'">&lt;&lt;</a>&nbsp;';
				$link  = $this->link($url, $this->name.'='.($this->current - 1));
				$html .= '<a class="previous" href="'.JRoute::_($link).'">&lt;</a>&nbsp;';
            } 

            for ($i = $range_start; $i <= $range_end; $i++) {
                if ($i == $this->current) {
	                $html .= '[<span>'.$i.'</span>]';
                } else {
					$link  = $this->link($url, $this->name.'='.$i);
	                $html .= '<a href="'.JRoute::_($link).'">'.$i.'</a>';
                } 
                $html .= "&nbsp;";
            } 

            if ($this->current < $this->pages) {
				$link  = $this->link($url, $this->name.'='.($this->current + 1));
                $html .= '<a class="next" href="'.JRoute::_($link).'">&gt;&nbsp;</a>&nbsp;';
				$link  = $this->link($url, $this->name.'='.($this->pages));
                $html .= '<a class="end" href="'.JRoute::_($link).'">&gt;&gt;&nbsp;</a>&nbsp;';
            } 
			
		}

        return $html;
    }

}