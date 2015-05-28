<?php
/*
 * Detail: This is main framework class for N-Media plugins
* Author: Najeeb Ahmad
*
* =============== Properties ===============
* $plugin_meta					An array contains all plugin properties/meta
* $wp_shipped_scripts			array containing array of shipped scripts
* $plugin_scripts				array containing arrays of plugin scripts
* $plugin_settings				plugin settings/options saved in backend
* $ajax_callbacks				array containing all ajax callbacks
* =============== HOOKS =============
* All hooks (add_actions) will be registered in this class
*
* ============== Model ==============
* All DB related function are defined in this class
* ----- get_single_data 		($select, $where_data, $switch="AND", $debug = false)
* ----- get_row_data 			($select, $where_data, $switch="AND", $debug = false)
* ----- get_rows_data 			($select, $where_data = NULL, $switch="AND", $debug = false)
* ----- insert_table			($table, $data, $format, $debug = false)
* ----- update_table			($table, $data, $where, $format, $where_format,$debug = false)
* ----- delete_data			($table, $where_data, $switch = "AND", $debug)
*/


class NM_Framwork_V1_wpregistration{


	/*
	 * An array contains all plugin properties/meta
	*/
	var $plugin_meta;


	/*
	 * load scripts shipped with WP
	* single array
	*/
	var $wp_shipped_scripts;

	/*
	 * JS scripts and styles to loaded FRONT END
	* Arrays of scripts to be loaded e.g:
	* array('script_name'	=> 'scripts',
			'script_source'	=> '/js/script.js',
			'localized'		=> true,
			'type'			=> 'js'
	)
	*/
	var $plugin_scripts;

	/*
	 * localized var for js script
	*/
	var $localized_vars;

	/*
	 * plugin settings/options saved in backend
	*/
	var $plugin_settings;


	/*
	 * following array are functions name and ajax callback handlers
	*/
	var $ajax_callbacks;


	/*
	 * validation array: contains error record when function called v();
	* added on 3 Feb, 2013
	*/
	var $validation = array();


	/*
	 * following function is registering all actions being set by plugin
	*/

	function load_scripts(){

		// shipped scripts
		if($this -> wp_shipped_scripts){
			foreach($this -> wp_shipped_scripts as $handler){
				wp_enqueue_script($handler);
			}
		}


		//front end scripts
		if($this -> plugin_scripts){
			foreach($this -> plugin_scripts as $script){

				//checking if it is style
				if( $script['type'] == 'js'){
					wp_enqueue_script($this->plugin_meta['shortname'].'-'.$script['script_name'], $this->plugin_meta['url'].$script['script_source'], __FILE__);

					//if localized
					if( $script['localized'] )
						wp_localize_script( $this->plugin_meta['shortname'].'-'.$script['script_name'], $this->plugin_meta['shortname'].'_vars', $this -> localized_vars);
				}else{

					wp_enqueue_style($this->plugin_meta['shortname'].'-'.$script['script_name'], $this->plugin_meta['url'].$script['script_source'], __FILE__);
				}
			}
		}
	}


	/*
	 * registering callback
	*/
	function do_callbacks(){

		foreach ($this -> ajax_callbacks as $callback){
			add_action( 'wp_ajax_'.$this->plugin_meta['shortname'].'_'.$callback, array($this, $callback) );
			add_action( 'wp_ajax_nopriv_'.$this->plugin_meta['shortname'].'_'.$callback, array($this, $callback) );
		}
	}


	/*
	 * for multi language support
	*/
	function wpp_textdomain() {
		load_plugin_textdomain($this->plugin_meta['shortname'], false, $this->plugin_meta['path'].'/locale/');
	}

	/*
	 * ===================== DB related functions ========================
	*
	* ====================================================================
	*/

	/*
	 * This functnio returning Single field value
	*/

	function get_single_data($select, $where_data, $switch="AND", $debug = false)
	{

		global $wpdb;

		$requested_col = '';
		foreach ($select as $table => $col){

			$requested_col = $col;

			$this -> qry .= 'SELECT '.$col.' FROM
			'.$wpdb -> prefix . $table;

		}

		$where = ' WHERE ';
		$format = array();
		foreach ($where_data as $type => $pair){

			if ($type == 's'){
				foreach ($pair as $f => $d){
					$where .= " $f = %s";
					$format[] = $d;
				}
			}else if ($type == 'd'){
				foreach ($pair as $f => $d){
					$where .= " $f = %d";
					$format[] = $d;
				}
			}


			$where .= " $switch ";
		}


		$where = substr_replace($where,"",-4);

		$this -> qry .= $where;

		$res = $wpdb -> get_results($wpdb -> prepare($this -> qry, $format));
		//pa($res);
			
		if($debug)
			$this->print_query(__FUNCTION__);

		$this -> qry = '';
		//clearing the cache
		$wpdb -> flush();

		return $res[0] -> $requested_col;
	}


	/*
		* this function returning the single row:
	* return is OBJECT
	*/
	function get_row_data($select, $where_data, $switch="AND", $debug = false)
	{

		global $wpdb;


		$requested_col = '';
		foreach ($select as $table => $cols){

			if (is_array($cols))
				$cols = implode(',', $cols);

			$this -> qry .= 'SELECT '.$cols.' FROM
			'.$wpdb -> prefix . $table;

		}

		$qry = 	"SELECT ".$column_name."
		FROM `".$table."`";

		$where = ' WHERE ';
		$format = array();
		foreach ($where_data as $type => $pair){

			if ($type == 's'){
				foreach ($pair as $f => $d){
					$where .= " $f = %s";
					$format[] = $d;
				}
			}else if ($type == 'd'){
				foreach ($pair as $f => $d){
					$where .= " $f = %d";
					$format[] = $d;
				}
			}


			$where .= " $switch ";
		}


		$where = substr_replace($where,"",-4);

		$this -> qry .= $where;


		$res = $wpdb -> get_results($wpdb -> prepare($this -> qry, $format));

		//$this -> setQuery($sql, 'getSingleData');

		if($debug)
			$this->print_query(__FUNCTION__);

		$this -> qry = '';
		//clearing the cache
		$wpdb -> flush();

		return $res[0];
	}


	/*
	 * this function returning the ROWS set:
	* return is OBJECT
	*/
	function get_rows_data($select, $where_data = NULL, $switch="AND", $debug = false)
	{

		global $wpdb;


		$requested_col = '';
		foreach ($select as $table => $cols){

			if (is_array($cols))
				$cols = implode(',', $cols);

			$this -> qry .= 'SELECT '.$cols.' FROM
			'.$wpdb -> prefix . $table;

		}

		$qry = 	"SELECT ".$column_name."
		FROM `".$table."`";

		//if criteria is false then no need below
		if($where_data != NULL){

			$where = ' WHERE ';
			$format = array();
			foreach ($where_data as $type => $pair){

				if ($type == 's'){
					foreach ($pair as $f => $d){
						$where .= " $f = %s";
						$format[] = $d;
					}
				}else if ($type == 'd'){
					foreach ($pair as $f => $d){
						$where .= " $f = %d";
						$format[] = $d;
					}
				}
					
					
				$where .= " $switch ";
			}


			$where = substr_replace($where,"",-4);

			$this -> qry .= $where;
		}else{
			
			$where = ' WHERE 1 = %d';
			$format[] = 1;
			$this -> qry .= $where;
		}



		$res = $wpdb -> get_results($wpdb -> prepare($this -> qry, $format));

		//$this -> setQuery($sql, 'getSingleData');

		if($debug)
			$this->print_query(__FUNCTION__);


		$this -> qry = '';
		//clearing the cache
		$wpdb -> flush();



		return $res;
	}

	/*
	 * DB injection function [insert, update, deletee]
	*/

	// INSERT ==========================================================
	public function insert_table($table, $data, $format, $debug = false)
	{
		global $wpdb;

		$wpdb->insert($wpdb->prefix.$table, $data, $format);

		if($debug)
			$this -> print_query(__FUNCTION__);

		if($wpdb -> insert_id)
			return true;
		else
			return false;
	}



	// UPDATE ==========================================================
	public function update_table($table, $data, $where, $format, $where_format,$debug = false)
	{
		global $wpdb;

		$rows_effected = $wpdb->update($wpdb ->prefix.$table, $data, $where, $format = null, $where_format = null);

		if($debug)
			$this -> print_query(__FUNCTION__);

		if($rows_effected)
			return true;
		else
			return false;
	}

	// DELETE ==========================================================
	function delete_data($table, $where_data, $switch = "AND", $debug){

		global $wpdb;

		$this -> qry = 	"DELETE	FROM `".$wpdb -> prefix . $table."`";

		$where = ' WHERE ';
		$format = array();
		foreach ($where_data as $type => $pair){

			if ($type == 's'){
				foreach ($pair as $f => $d){
					$where .= " $f = %s";
					$format[] = $d;
				}
			}else if ($type == 'd'){
				foreach ($pair as $f => $d){
					$where .= " $f = %d";
					$format[] = $d;
				}
			}


			$where .= " $switch ";
		}


		$where = substr_replace($where,"",-4);
		$this -> qry .= $where;

		$wpdb -> query($wpdb -> prepare($this -> qry, $format));

		//$this -> setQuery($sql, 'getSingleData');

		if($debug)
			$this->print_query(__FUNCTION__);

		$this -> qry = '';
		//clearing the cache
		$wpdb -> flush();

	}

	/*
	 * print query
	* and error iF ANY
	*/

	public function print_query($caller_function)
	{
		global $wpdb;

		echo '<br> The query is being called from '.$caller_function.'<br>';

		$wpdb->show_errors();
		$wpdb->print_error();

		if($this->kill_script)
		{
			echo '------- Exiting the script --------';
			exit;
		}

	}

	/*
	 This function checks for duplicate entry in database, returns True/False.
	*/
	/* public function isAlreadyExist($table, $field, $value)
	 {

	$duplicateRec = $this-> _getSingleData($table, 'COUNT(*)', "$field = '$value'");
	if($duplicateRec > 0)
	{
	return true;
	}
	else
	{
	return false;
	}

	} */

	/*
	 * ===================== ENDS DB related functions ========================
	*
	* =========================================================================
	*/


	/*
	 * ================================ SOME COMMON FUNCTION ===================
	* ==========================================================================
	*/

	/*
	 * this function is extrating single
	* option from nm_plugin_settings serialzied object/data
	* key must be prefixed e.g: _key
	*/
	function get_option($key){

		//HINT: $key should be under schore (_) prefix

		$full_key =  $this->plugin_meta['shortname'] . $key;
		$the_option = $this -> plugin_settings[ $full_key ];

		if (is_array($the_option))
			return $the_option;
		else
			return stripcslashes( $the_option );
			
	}


	function load_template($file_name, $variables=array('')){

		extract($variables);

		$file_path = $this -> plugin_meta['path'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $file_name;
		if (file_exists($file_path))
			include_once($file_path);
		else
			die('Could not load file '.$file_path);
	}

	/*
	 * print array in readable form
	*/
	function pa($arr){

		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}


	/*
	 * generate url with query strings
	*/
	function nm_plugin_fix_request_uri($vars){
		$uri = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		$parts = explode("?", $uri);

		$qsArr = array();
		if(isset($parts[1])){	////// query string present explode it
			$qsStr = explode("&", $parts[1]);
			foreach($qsStr as $qv){
				$p = explode("=",$qv);
				$qsArr[$p[0]] = $p[1];
			}
		}

		//////// updatig query string
		foreach($vars as $key=>$val){
			if($val==NULL) unset($qsArr[$key]); else $qsArr[$key]=$val;
		}

		////// rejoin query string
		$qsStr="";
		foreach($qsArr as $key=>$val){
			$qsStr.=$key."=".$val."&";
		}
		if($qsStr!="") $qsStr=substr($qsStr,0,strlen($qsStr)-1);
		$uri = $parts[0];
		if($qsStr!="") $uri.="?".$qsStr;
		return $uri;
	}


	/*
	 * return timestamp into time interval like 2 days ago etc
	*/
	function nm_plugin_time_difference($date)
	{
		if(empty($date)) {
			return "No date provided";
		}

		$periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths         = array("60","60","24","7","4.35","12","10");

		$now             = time();
		$unix_date         = strtotime($date);

		// check validity of date
		if(empty($unix_date)) {
			return "Bad date";
		}

		// is it future date or past date
		if($now > $unix_date) {
			$difference     = $now - $unix_date;
			$tense         = "ago";

		} else {
			$difference     = $unix_date - $now;
			$tense         = "from now";
		}

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if($difference != 1) {
			$periods[$j].= "s";
		}

		return "$difference $periods[$j] {$tense}";
	}

	/*
	 * ================================ SOME COMMON FUNCTION =======================================
	* =============================================================================================
	*/


	/*
	 * ================================ VALIDATION FUNCTION =======================================
	* =============================================================================================
	*/

	function validate($validate)
	{

		foreach ($validate as $item){

			if($item['data'] == '')
				$this -> validation[] .= $item['title'].' cannot be empty.<br />';

			switch ($item['type']){

				case 'email':
					if(!is_email($item['data']))
						$this -> validation[] .= $item['title'].' must be valid E-mail.<br />';
					break;

				case 'number':
					if(!$this -> is_number($item['data']))
						$this -> validation[] .= $item['title'].' must be valid Number.<br />';
					break;

				case 'integer':
					if(!$this -> is_integer($item['data']))
						$this -> validation[] .= $item['title'].' must be valid Integer.<br />';
					break;
			}

			if($item['data2'] != null && !$this -> is_matched($item['data'], $item['data2']))
				$this -> validation[] .= $item['title'].' are not matching, please recheck '.$item['title'].'<br />';
		}

	}



	function is_matched($p1, $p2)
	{
		if(strcmp($p1, $p2) == 0)
			return true;
		else
			return false;
	}

	function is_integer($integer)
	{
		if(ctype_digit($integer) == 1)
			return true;
		else
			return false;
	}

	function is_number($number)
	{
		if(is_numeric($number))
			return true;
		else
			return false;
	}


}