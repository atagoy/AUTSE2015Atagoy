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

// register parent class
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
	Class: ElementSimpleParams
		The simple params element abstract class, extends the simple element with custom params ability
*/
class ElementSimpleParams extends ElementSimple {

	/** @var string */
	var $params;
	/** @var jparameter object */
	var $_params;

	/*
	   Function: Constructor
	*/
	function ElementSimpleParams() {

		// call parent constructor
		parent::ElementSimple();
		
		// init vars
		$this->type  = 'simpleparams';
		$this->_table_columns = array('_value' => 'VARCHAR(255)', '_params' => 'TEXT');
	}

	/*
		Function: getParams
			Gets element params.

		Returns:
			Object - JParameter
	*/
	function &getParams() {

		if (empty($this->_params)) {
			$this->_params = new JParameter($this->params);
		}

		return $this->_params;
	}

	/*
    	Function: bind
    	  Binds a named array/hash to this object.
		
		Parameters:
	      $data - An associative array or object.
	
	   Returns:
	      Void	
 	*/
	function bind($data) {
		
		parent::bind($data);

		// init vars
		$params =& $this->getParams();		

		// set params
		if (isset($data[$this->name.'_params']) && is_array($data[$this->name.'_params'])) {
			$params->loadArray($data[$this->name.'_params']);
		}

		$this->params = $params->toString();
	}

}