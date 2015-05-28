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
JLoader::register('ElementSimpleParams', ZOO_ADMIN_PATH.'/elements/simpleparams/simpleparams.php');

/*
	Class: ElementRating
		The rating element class
*/
class ElementRating extends ElementSimpleParams {

	/** @var int */
	var $stars = null;
	/** @var int */
	var $allow_vote = null;
	/** @var boolean */
	var $disabled = false;

	/*
	   Function: Constructor
	*/
	function ElementRating() {

		// call parent constructor
		parent::ElementSimpleParams();
		
		// init vars
		$this->type  = 'rating';

		// set callbacks
		$this->registerCallback('vote');
	}

	/*
		Function: hasValue
			Checks if the element's value is set.

		Returns:
			Boolean - true, on success
	*/
	function hasValue() {
		return true;
	}
	
	/*
		Function: render
			Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {

		static $instance;

		// init vars
		$instance = empty($instance) ? 1 : $instance + 1;
		$link     = 'index.php?option=com_zoo&view=element&task=callelement&format=raw&item_id='.$this->_item->id.'&element='.$this->name;

		// print_r($this);

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('instance' => $instance, 'stars' => $this->stars, 'allow_vote' => $this->allow_vote, 'disabled' => $this->disabled, 'rating' => $this->getRating(), 'votes' => $this->getVotes(), 'link' => $link));
		}
		
		return null;
	}

	/*
		Function: rating
			Get rating.

		Returns:
			String - Rating number
	*/
	function getRating() {
		return number_format((empty($this->value) ? 0 : $this->value), 1);
	}

	/*
		Function: getVotes
			Get number of votes.

		Returns:
			Int - Vote count
	*/
	function getVotes() {
		$params =& $this->getParams();
		return $params->get('votes', 0);		
	}

	/*
		Function: vote
			Execute vote.

		Returns:
			String - Message
	*/
	function vote($vote = null) {
		
		$db   =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$date =& JFactory::getDate();
		$vote = (int) $vote;

		for ($i = 1; $i <= $this->stars; $i++) { 
			$stars[] = $i;
		}

		if ($this->allow_vote > $user->get('aid', 0)) {
			return '0,'.JText::_('You are not allowed to vote');
		}

		if (in_array($vote, $stars) && isset($_SERVER['REMOTE_ADDR']) && ($ip = $_SERVER['REMOTE_ADDR'])) {

			// check if ip already exists
			$query = 'SELECT *'
				    .' FROM #__zoo_core_rating'
			   	    .' WHERE element = '.$db->Quote($this->name)
			   	    .' AND item_id = '.(int) $this->_item->id
			   	    .' AND ip = '.$db->Quote($ip);
			
			$db->setQuery($query);
			if (!$db->query()) {
				return '0,'.JText::_('Vote failed');
			}

			// voted already
			if ($db->getNumRows()) {
				return '0,'.JText::_('You voted already');
			}

			// insert vote
			$query    = "INSERT INTO #__zoo_core_rating"
	   	               ." SET element = ".$db->Quote($this->name)
			   	       ." ,item_id = ".(int) $this->_item->id
		   	           ." ,user_id = ".(int) $user->id
		   	           ." ,value = ".(int) $vote
	   	               ." ,ip = ".$db->Quote($ip)
   	                   ." ,created = ".$db->Quote($date->toMySQL());

			// execute query
			$db->setQuery($query);
			if (!$db->query()) {
				return '0,'.JText::_('Vote failed');
			}
			
			$this->save();
		}				

		return intval($this->getRating() / $this->stars * 100).','.$this->getRating().JText::_(' rating from ').$this->getVotes().JText::_(' votes');
	}
	
	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {
		
		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Rating'), $this->getRating());
		$html .= JHTML::_('element.editrow', JText::_('Votes'), $this->getVotes());
		$html .= '</table>';

		return $html;
	}

	/*
		Function: save
			Save the elements item data.

		Returns:
			Boolean - true on success
	*/
	function save() {

		// init vars
		$db =& JFactory::getDBO();
		$params =& $this->getParams();

		// calculate rating/votes
		$query = 'SELECT AVG(value) AS rating, COUNT(id) AS votes'
			    .' FROM #__zoo_core_rating'
			   	.' WHERE element = '.$db->Quote($this->name)			
			    .' AND item_id = '.$this->_item->id
			    .' GROUP BY item_id';
			
		$db->setQuery($query);

		if ($res = $db->loadAssoc()) {
			$params->set('votes', $res['votes']);		
			$this->params = $params->toString();
			$this->value  = $res['rating'];
		} else {
			$params->set('votes', 0);		
			$this->params = $params->toString();
			$this->value  = 0;
		}
		
		return parent::save();
	}

	/*
		Function: delete
		    Deletes the elements item data.

		Returns:
			Boolean - true on success
	*/
	function delete(){

		// init vars
		$db =& JFactory::getDBO();

		// delete item related ratings
		$query = "DELETE FROM #__zoo_core_rating"
		        ." WHERE item_id = ".$this->_item->id;

		$db->setQuery($query);

		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return parent::delete();
	}
		
	/*
	   Function: configAdd
	   	  Add elements configuration.
	   	  Can be overloaded/supplemented by the child class.

	   Parameters:
          $data - element configuration

	   Returns:
		  boolean - true on success
	*/
	function configAdd($data){

		// create rating table, if not exists
		if (!$this->configAddTable()) {
			return false;
		}

		return parent::configAdd($data);
	}

	/*
	   Function: configSave
	   Saves elements configuration.

	   Parameters:
	      $data - element configuration

	   Returns:
		  boolean - true on success
	*/
	function configSave($data){

		// init vars
		$db =& JFactory::getDBO();

		// create rating table, if not exists
		if (!$this->configAddTable()) {
			return false;
		}

		// rename elements related ratings
		if ($this->name != $data['name']) {
			$query = "UPDATE #__zoo_core_rating, ".ZOO_TABLE_ITEM
			        ." SET #__zoo_core_rating.element = ".$db->Quote($data['name'])
				    ." WHERE ".ZOO_TABLE_ITEM.".id = #__zoo_core_rating.item_id"
			        ." AND #__zoo_core_rating.element = ".$db->Quote($this->name)
			        ." AND ".ZOO_TABLE_ITEM.".type_id = ".$this->_type->id;
			
			$db->setQuery($query);
			
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return parent::configSave($data);
	}

	/*
		Function: configDelete
	   		Deletes elements configuration.

		Returns:
 		  	boolean - true on success
	*/
	function configDelete(){

		// init vars
		$db =& JFactory::getDBO();

		// delete elements related ratings
		$query = "DELETE #__zoo_core_rating FROM #__zoo_core_rating"
		        ." JOIN ".ZOO_TABLE_ITEM." AS i ON i.id = #__zoo_core_rating.item_id"
			    ." WHERE #__zoo_core_rating.element = ".$db->Quote($this->name)
		        ." AND i.type_id = ".$this->_type->id;

		$db->setQuery($query);

		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		return parent::configDelete();
	}


	/*
	   Function: configAddTable
	   	   Add ratings own table to database, if not exists.

	   Returns:
		  boolean - true on success
	*/
	function configAddTable(){

		// init vars
		$db     =& JFactory::getDBO();
		$tables = $db->getTableList();

		// create rating table, if not exists
		if (!in_array($db->getPrefix().'zoo_core_rating', $tables)) {

			$query = "CREATE TABLE #__zoo_core_rating ("
			        ." id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
			        ." element VARCHAR(255),"
			        ." item_id INT,"
			        ." user_id INT,"
			        ." value TINYINT,"
			        ." ip VARCHAR(255),"
			        ." created DATETIME"
			        .") TYPE=MyISAM";

			$db->setQuery($query);

			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

}