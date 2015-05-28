<?php
/**
* @package   Warp Theme Framework
* @file      helper.php
* @version   5.5.10
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright  2007 - 2010 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelper
		Helper base class
*/
class WarpHelper extends WarpObject {

	/* warp */
	var $warp;

	/* name */
	var $name;

	/* callbacks */
	var $callbacks = array();

	/*
		Function: Constructor
			Class Constructor.
	*/
	function __construct() {

		// set default name
		$this->warp  =& Warp::getInstance();
		$this->name = strtolower(substr(get_class($this), strlen(__CLASS__)));

	}

	/*
		Function: getName
			Get helper name

		Returns:
			String
	*/	
	function getName() {
		return $this->name;
	}

	/*
		Function: getWarp
			Retrieve related template object

		Returns:
			Template
	*/	
	function &getWarp() {
		return $this->warp;
	}
	
	/*
		Function: getHelper
			Retrieve Helper based on related template object

		Returns:
			Helper
	*/	
	function &getHelper($name) {
		return $this->warp->getHelper($name);
	}

}