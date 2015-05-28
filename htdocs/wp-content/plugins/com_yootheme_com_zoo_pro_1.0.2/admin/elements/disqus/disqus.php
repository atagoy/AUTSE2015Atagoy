<?php
/**
* @package   Zoo Component
* @version   1.0.2 2009-03-21 18:54:57
* @author    jSharing http://jsharing.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register parent class
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
   Class: ElementDisqus
       The Disqus element class (http://www.disqus.com)
*/
class ElementDisqus extends ElementSimple {

	/** @var string */
	var $website;
	/** @var int */
	var $developer;
	
	/*
	   Function: Constructor
	*/
	function ElementDisqus() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type    = 'disqus';
		$this->display = 1;
	}

	/*
		Function: render
			Override. Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		// render html
		if ($this->website && $this->value) {

			// developer mode
			if ($this->developer) {
				$html[] = "<script type='text/javascript'>";
				$html[] = "var disqus_developer = 1;";
				$html[] = "</script>";			
			}

			$html[] = "<div id=\"disqus_thread\"></div>";
			$html[] = "<script type=\"text/javascript\" src=\"http://disqus.com/forums/".$this->website."/embed.js\"></script>";
			$html[] = "<noscript><a href=\"http://".$this->website.".disqus.com/?url=ref\">View the discussion thread.</a></noscript>";
			$html[] = "<a href=\"http://disqus.com\" class=\"dsq-brlink\">blog comments powered by <span class=\"logo-disqus\">Disqus</span></a>";
			$html[] = "<script type=\"text/javascript\">
							//<![CDATA[
							(function() {
									var links = document.getElementsByTagName('a');
									var query = '?';
									for(var i = 0; i < links.length; i++) {
										if(links[i].href.indexOf('#disqus_thread') >= 0) {
											query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
										}
									}
									document.write('<script charset=\"utf-8\" type=\"text/javascript\" src=\"http://disqus.com/forums/".$this->website."/get_num_replies.js' + query + '\"></' + 'script>');
								})();
							//]]>
                       </script>";
			return implode("\n", $html);
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {

		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$this->value = 1;
		}

		return JHTML::_('select.booleanlist', $this->name.'_value', '', $this->value);
	}

}