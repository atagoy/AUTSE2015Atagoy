<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperEvent
		Event helper class. Create and manage Events.
*/
class WarpHelperEvent extends WarpHelper {

	/* events */
	var $_events = array();

	/*
		Function: bind
			Bind a function to an event.

		Parameters:
			$event - Event name
			$callback - Function callback

		Returns:
			Void
	*/
	function bind($event, $callback) {
		
		if (!isset($this->_events[$event])) {
			$this->_events[$event] = array();
		}
		
		$this->_events[$event][] = $callback;
	}

	/*
		Function: trigger
			Trigger Event

		Parameters:
			$event - Event name
			$parameters - Function arguments

		Returns:
			Void
 	*/
	function trigger($event, $args = array()) {
		
		if (isset($this->_events[$event])) {
			foreach ($this->_events[$event] as $callback) {
				$this->_call($callback, $args);
			}
		}

	}
	
}