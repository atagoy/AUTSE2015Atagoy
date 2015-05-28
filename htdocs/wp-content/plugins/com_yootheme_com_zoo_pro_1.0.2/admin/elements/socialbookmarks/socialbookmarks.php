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
   Class: ElementSocialBookmarks
       The ElementSocialBookmarks element class
*/
class ElementSocialBookmarks extends ElementSimple {

	/** @var array */
	var $bookmarks = array();

	/*
	   Function: Constructor
	*/
	function ElementSocialBookmarks() {
		// call parent constructor
		parent::ElementSimple();
		
		// init vars		
		$this->type = 'socialbookmarks';
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

		$bookmarks = array();

		// get active bookmarks
		foreach ($this->getBookmarks() as $bookmark => $data) {
			if (isset($this->bookmarks[$bookmark]) && $this->bookmarks[$bookmark]) {
				$bookmarks[$bookmark] = $data;
			}
		}
		
		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('bookmarks' => $bookmarks));
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

	/*
		Function: configBindArray
			Binds the data array.

		Parameters:
			$data - data array
	        $ignore - An array or space separated list of fields not to bind

		Returns:
			Void
	*/
	function configBindArray($data, $ignore = array()) {
		parent::configBindArray($data, $ignore);
	
		foreach ($this->getBookmarks() as $bookmark => $value) {
			if (isset($data[$bookmark])) {
				$this->bookmarks[$bookmark] = $data[$bookmark];
			}
		}
	}

	/*
	   Function: configBindXML
	       Converts the XML to a Dataarray and calls the bind method.

	   Parameters:
	      XML - The XML for this Element
	*/
	function configBindXML($xml) {
		parent::configBindXML($xml);
	
		foreach ($this->getBookmarks() as $bookmark => $value) {
			$this->bookmarks[$bookmark] = $xml->attributes($bookmark);
		}
	}

	/*
		Function: configToXML
			Get elements XML.
			Can be overloaded/supplemented by the child class.

		Parameters:
	        $ignore - An array or space separated list of fields not to include

		Returns:
			Object - JSimpleXMLElement
	*/
	function configToXML($ignore = array()) {
		$xml = parent::configToXML($ignore);

		foreach ($this->getBookmarks() as $bookmark => $value) {
			if (isset($this->bookmarks[$bookmark])) {
				$xml->addAttribute($bookmark, $this->bookmarks[$bookmark]);
			}
		}
		
		return $xml;
	}

	/*
		Function: configParams
			Get elements params.

		Returns:
			Object - JParameter
	*/
	function &configParams() {
		
		$params = new ElementParameter('');
		$params->setElement($this);

		// set data
		foreach ($this->getProperties() as $name => $value) {
			if ($name != 'bookmarks') {
				$params->set($name, $value);
			}
		}
		
		return $params;
	}
	
	/*
		Function: getBookmarks
			Get array of supported bookmarks.

		Returns:
			Array - Bookmarks
	*/			
	function getBookmarks() {
		
		// Google
		$bookmarks['google']['link']  = "http://www.google.com/";
		$bookmarks['google']['click'] = "window.open('http://www.google.com/bookmarks/mark?op=add&hl=de&bkmk='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title));return false;";

		// Technorati
		$bookmarks['technorati']['link']  = "http://www.technorati.com/";
		$bookmarks['technorati']['click'] = "window.open('http://technorati.com/faves?add='+encodeURIComponent(location.href));return false;";

		// Yahoo
		$bookmarks['yahoo']['link']  = "http://www.yahoo.com/";
		$bookmarks['yahoo']['click'] = "window.open('http://myweb2.search.yahoo.com/myresults/bookmarklet?t='+encodeURIComponent(document.title)+'&u='+encodeURIComponent(location.href));return false;";

		// Delicious
		$bookmarks['delicious']['link']  = "http://del.icio.us/";
		$bookmarks['delicious']['click'] = "window.open('http://del.icio.us/post?v=2&url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title));return false;";

		// StumbleUpon
		$bookmarks['stumbleupon']['link']  = "http://www.stumbleupon.com/";
		$bookmarks['stumbleupon']['click'] = "window.open('http://www.stumbleupon.com/submit?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title));return false;";

		// Digg
		$bookmarks['digg']['link']  = "http://digg.com/";
		$bookmarks['digg']['click'] = "window.open('http://digg.com/submit?phase=2&url='+encodeURIComponent(location.href)+'&bodytext=&tags=&title='+encodeURIComponent(document.title));return false;";

		// Facebook
		$bookmarks['facebook']['link']  = "http://www.facebook.com/";
		$bookmarks['facebook']['click'] = "window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(location.href)+'&t='+encodeURIComponent(document.title));return false;";

		// Reddit
		$bookmarks['reddit']['link']  = "http://reddit.com/";
		$bookmarks['reddit']['click'] = "window.open('http://reddit.com/submit?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title));return false;";

		// Myspace
		$bookmarks['myspace']['link']  = "http://www.myspace.com/";
		$bookmarks['myspace']['click'] = "window.open('http://www.myspace.com/index.cfm?fuseaction=postto&'+'t='+encodeURIComponent(document.title)+'&c=&u='+encodeURIComponent(location.href)+'&l=');return false;";

		// Windows live
		$bookmarks['live']['link']  = "http://www.live.com/";
		$bookmarks['live']['click'] = "window.open('https://favorites.live.com/quickadd.aspx?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title));return false;";

		// Twitter
		$bookmarks['twitter']['link']  = "http://twitter.com/";
		$bookmarks['twitter']['click'] = "window.open('http://twitter.com/home?status='+encodeURIComponent(document.title));return false;";

		// Email
		$bookmarks['email']['link']  = "";
		$bookmarks['email']['click'] = "this.href='mailto:?subject='+document.title+'&body='+encodeURIComponent(location.href);";

		return $bookmarks;
	}

}