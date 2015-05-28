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

/*
	Class: YError
		The Error Class. Provides global access to error logging.
*/
class YError {

	/*
	    Variable: _errors
	      Error message array.
	*/	
	var $_errors = array();

	/*
		Function: getInstance
			Get YError instance

		Returns:
			Object - YError object
 	*/
	function &getInstance() {
		static $instance;

		if (!isset($instance)) {
			$instance = new YError();
		}
		
		return $instance;
	}

	/*
		Function: getError
			Get the most recent error message

		Parameters:
			$i - Option error index
			$toString - Indicates if JError objects should return their error message

		Returns:
			String - Error message	
	*/	
	function getError($i = null, $toString = true) {
		$error =& YError::getInstance();
		return $error->get($i, $toString);
	}

	/*
		Function: getErrors
			Return all errors, if any

		Returns:
			Array - Error message array	
	*/	
	function getErrors() {
		$error =& YError::getInstance();
		return $error->getAll();
	}

	/*
		Function: setError
			Static. Add an error message

		Returns:
			Void	
	*/	
	function setError($e) {
		$error =& YError::getInstance();
		$error->set($e);
	}

	/*
		Function: get
			Get the most recent error message

		Parameters:
			$i - Option error index
			$toString - Indicates if JError objects should return their error message

		Returns:
			String - Error message	
	*/	
	function get($i = null, $toString = true) {

		// find the error
		if ($i === null) {
			// default, return the last message
			$error = end($this->_errors);
		} else if (!array_key_exists($i, $this->_errors)) {
			// if $i has been specified but does not exist, return false
			return false;
		} else {
			$error = $this->_errors[$i];
		}

		// check if only the string is requested
		if (JError::isError($error) && $toString) {
			return $error->toString();
		}

		return $error;
	}

	/*
		Function: getAll
			Return all errors, if any

		Returns:
			Array - Error message array	
	*/	
	function getAll() {
		return $this->_errors;
	}

	/*
		Function: getAll
			Add an error message

		Returns:
			Void	
	*/	
	function set($error) {
		array_push($this->_errors, $error);
	}

}