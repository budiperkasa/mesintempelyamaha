<?php

/**
 * FijiWebDesign Classes for Joomla
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
/**
* Common Functions used by the extensions
* This was supposed to be here for portability but I believe Joomla 1.5x API is too different from Mambo 4.5x
* So scrap this as soon as Joomla 1.5 is stable
*/
class fwd_Extension {

	/** name of component */
	var $ext_name;
	/** path to live site */
	var $live_site;
	/** absolute path to site base */
	var $absolute_path;
	/** web path to component directory */
	var $ext_url;
	/** absolute file path to component directory */
	var $ext_path;
	/** relative url to component */
	var $ext_site;
	
	/** current task */
	var $task;
	/** current act */
	var $act;
	
	/* instantiate very common stuff */
	function fwd_Extension($ext_name, $_task = false, $_act = false) {
		global $task, $act;
		$this->ext_name = $ext_name;
		$this->live_site = $this->live_site();
		$this->absolute_path = $this->absolute_path();
		$this->ext_path = $this->ext_path();
		$this->task = $_task ? $_task : $task;
		$this->act = $_act ? $_act : $act;
	}
	
	// simple get a param if set
	function Get($obj, $index) {
		if (is_array($obj)) {
			if (isset($obj[$index])) {
				return $obj[$index];
			}
			return null;
		}
		if (is_object($obj)) {
			if (isset($obj->$index)) {
				return $obj->$index;
			}
			return null;
		}
	}
	
	// live joomla site
	function live_site() {
		if (isset($GLOBALS['mosConfig_live_site'])) {
			return $GLOBALS['mosConfig_live_site'];
		} else {
			global $mainframe;
			return $mainframe->getCfg('live_site');
		}
	}
	
	// absolute joomla install directory
	function absolute_path() {
		if (isset($GLOBALS['mosConfig_absolute_path'])) {
			return $GLOBALS['mosConfig_absolute_path'];
		} else {
			global $mainframe;
			return $mainframe->getCfg('absolute_path');
		}
	}
	
	// absolute path to extension directory
	function ext_path() {
		return $this->absolute_path.'/components/'.$this->ext_name.'/';
	}
	
	// absolute path to extension directory
	function live_ext() {
		return $this->live_site().'/components/'.$this->ext_name.'/';
	}
	
	// includes a file in the allowed list
	function ext_fileIncludeFactory($allowed_files, $file, $dir, $default = false) {	
		if (in_array($file, $allowed_files, true)) {
			require_once($dir.$file); // user defined
		} else {
			if ($default) {
				require_once($dir.$default); // default, code defined
			} else {
				trigger_error(_FWD_ERR_FILE_NOT_EXIST, E_USER_ERROR);
			}
		}
	}
	
	// returns files in the directory, with regex filename match
	function readDir($path, $regex = null) {
		return mosReadDirectory($path, $regex);
	}
	
	// Returns a new mosPageNav Instance
	function newPageNav($total, $limitstart = 0, $limit = 10) {
		require_once( $this->absolute_path . '/administrator/includes/pageNavigation.php' );
		return new mosPageNav( $total, $limitstart, $limit );
	}
	
	// strip slashes from magic quotes
	function stripMagicQuotes(&$string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string); // bad magic_quotes
		 }
	}
	
	// wrapper for mosRedirect
	function redirect($url, $msg = NULL) {
		//echo 'url: '.$url.' msg: '.$msg;
		mosRedirect($url, $msg);
		die;
	}
	
	// inject HTML in between the <head> section
	function injectCustomHeadTags($html) {
		$buf = ob_get_contents();
		if (!empty($buf) && !headers_sent()) {
			$buf = preg_replace("/<head(| .*?)>(.*?)<\/head>/is", "<head$1>$2".$html."</head>", $buf);
			ob_clean();
			echo $buf;
			return true;
		}
		return false;
	}
	
	// get the menu parameters by $Itemid
	function getMenuParams() {
		global $mainframe, $Itemid;
		if ( $Itemid ) {
			$menu = $mainframe->get( 'menu' );
			$params = new mosParameters( $menu->params );
		} else {
			$params = new mosParameters( '' );
		}
		return $params;
	}
	
	// dump an object to HTMl
	function dump($obj) {
		echo '<pre>'.htmlentities(print_r($obj, 1)).'</pre>';
	}

}

/**
* Common DB Queries
* @ver 0.1.jm.1
*/
class fwd_commonDB {

	/** @param Object Joomla Database Class Instance */
	var $database;
	/** @param int total number of queries by this instance */
	var $count;
	/** @param bool Turn debugging on or off */
	var $debug;
	/** @param Object Fields */
	var $fields;
	/** @param String Database version */
	var $version;

	function fwd_commonDB(&$database, $debug = false) {
		if (is_object($database)) {
			$this->db = $database;
			$this->count = 0;
			$this->version = false;
			$this->debug = $debug;
		} else {
			die('Joomla DB Object is needed for instantiating fwd_commonDB');
		}
	}
	
	function mysql_version() {
		if ($this->version) {
			return $this->version;
		} else {
			$this->version = $this->Result('SELECT Version()');
			return $this->version;
		}
	}
	
	function safeEscape($string, $httpvar = true) {
		if (get_magic_quotes_gpc() && $httpvar) {
			$string = stripslashes($string); // bad magic_quotes
		 }
		 if (function_exists('mysql_real_escape_string')) {
			return mysql_real_escape_string($string);
		 } elseif (function_exists('mysql_escape_string')) {
			return mysql_escape_string($string);
		 } else {
			return addslashes($string);
		 }
	}
	
	/** wrapper for global $database->setQuery() */
	function _setQuery($query) {
		if ($this->debug) {
			$start = $this->_microtime_float();
		}
		$this->db->setQuery($query);
		$this->count++;
		if ($this->debug) {
			$end = $this->_microtime_float();
			$change =  $end - $start;
			echo '<div class="debug">Query #'.$this->count.': '.'<pre>'.$query."</pre>\nin {$change}s</div>";
		}
	}
	
	function _microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function Object($query) {
		$this->_setQuery($query);
		$this->db->loadObject($obj);
		if ($this->db->_errorNum > 0) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return $obj;
	}
	
	function ObjectList($query) {
		$this->_setQuery($query);
		$objlist = $this->db->loadObjectList();
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return $objlist;
	}
	
	function Result($query) {
		$this->_setQuery($query);
		$result = $this->db->loadResult();
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return $result;
	}
	
	function Table($table, $clear = true) {
		$this->table = $table;
		if ($clear) {
			$this->clearFields();
		}
	}
	
	function clearFields() {
		$this->fields = array();
	}
	
	function Field($name, $value) {
		if (is_bool($value)) {
			$value = $value ? 'TRUE' : 'FALSE'; // todo: test
		} else if (is_string($value)) {
			$value = "'".$this->safeEscape($value)."'"; // safety first
		} else {
			$value = intval($value); // make sure
		}
		$field = "{$name} = {$value}";
		array_push($this->fields, $field);
	}
	
	function Fields($fields) {
		foreach($fields as $name=>$value) {
			if (is_bool($value)) {
				$value = $value ? 'TRUE' : 'FALSE'; // todo: test
			} else if (is_string($value)) {
				$value = "'".$this->safeEscape($value)."'"; // safety first
			} else {
				$value = intval($value); // make sure
			}
			$field = "{$name} = {$value}";
			array_push($this->fields, $field);
		}
	}
	
	function Insert($clear = true) {		
		$fields = implode(', ', $this->fields);
		$query = "INSERT INTO {$this->table} SET {$fields}";
		
		if ($clear) {
			$this->clearFields();
		}
		
		$this->_setQuery($query);
		$this->db->query();
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return true;
	}
	
	function InsertId() {
		return mysql_insert_id();
	}
	
	function Query($query) {		
		$this->_setQuery($query);
		$this->db->query();
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return true;
	}
	
	function Update($where, $clear = true) {
		$fields = implode(', ', $this->fields);
		$query = "UPDATE  {$this->table} SET {$fields} {$where}";
		
		if ($clear) {
			$this->clearFields();
		}
		
		$this->_setQuery($query);
		$result = $this->db->query();
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return $result;
	}
	
	// viewing functions
	
	/** 
	* Check if a table exists
	*/
	function table_exists($table) {
		// due to a bug in Joomla DB class, we have to replace prefix in quotes
		$prefix = $this->db->getPrefix();
		$table = str_replace('#__', $prefix, $table);
		$query = "SHOW TABLES LIKE '".$this->safeEscape($table)."'";
		return count($this->ObjectList($query));
	}
	
	/** 
	* Return the Table Fields in array
	*/
	function tableColumns($table) {
		$tables = $this->db->getTableFields(array($table));
		return isset($tables[$table]) ? $tables[$table] : false;
	}
	
	/** 
	* Check if a table column exists
	*/
	function column_exists($table, $column) {
		$table = $this->tableColumns($table);
		return isset($table[$column]);
	}
	
	/**
	* Return the dataset as an Indexed Array
	*/
	function indexedArray($query, $col_index = 0) {
		$this->_setQuery($query);
		$arr = $this->db->loadResultArray($col_index);
		if ($this->db->_errorNum) {
			trigger_error( $this->db->stdErr(), E_USER_WARNING );
			return false;
		}
		return $arr;
	}
}

/**
* OO handling of URL and SEF
*/
class fwd_URL {

	/** @param string URI */
	var $uri;
	/** @param array Parameters */
	var $params;

	/**
	* constructor
	*/
	function fwd_URL($uri = '', $params = array()) {
		$this->uri = $uri;
		$this->params = $params;
	}

	function setParam($name, $value = NULL) {
		$this->params[$name] = $value;
	}
	
	function getParam($name) {
		if (isset($this->params[$name])) {
			return $this->params[$name];
		}
		return NULL;
	}

	function toString($sep = '&', $eq = '=') {
		$str_params = '';
		foreach($this->params as $name=>$value) {
			$str_params .= urlencode($name).$eq.urlencode($value).$sep;
		}
		$url = urlencode($this->uri).'?'.substr($str_params, 0, strlen($str_params) - strlen($sep));
		return $url;
	}

	function toHTML($sep = '&') {
		return $this->toString(htmlentities($sep));
	}
	
	function toSEF($sep = '&', $eq = '=') {
		if (function_exists('sefRelToAbs'))
			return sefRelToAbs($this->toString($sep, $eq));
		return $this->toString($sep, $eq);
	}
	
	function toURL($sep = '&', $raw = false) {
		if ($raw) 
			return $this->toString(rawurlencode($sep));
		return $this->toString(urlencode($sep));
	}
}

/**
* FWD Tasker, Delegates tasks and acts
*/
class fwd_Tasker {

	/** @param string Task */
	var $task;
	/** @param string act */
	var $act;
	/** @param Object Task Class Instance */
	var $TaskInstance;

	/** 
	* Constructor, sets the NameSpace which is prepended to Task Functions
	* @param string Namespace
	*/
	function fwd_Tasker($ns = 'fwd_Task_', $task = false, $act = false) {
		$this->setNS($ns);		
		if ($task) $this->setTask($task);
		if ($act) $this->setAct($act);
	}
	
	function setTask($task) {
		$this->task = $this->ns.$task;
	}
	
	function setAct($act) {
		if (strpos($act, '_') === 0) {
			trigger_error('Private Functions begining with "_" cannot be set as actions');
			return false;
		}
		$this->act = $act;
	}
	
	function setNS($ns) {
		$this->ns = $ns;
	}
	
	/** 
	* Call the $task->$act()
	* @param string $act A non-private method of the $task instance
	*/
	function callTaskAct($act = false) {
	
		if ($act) $this->setAct($act);
		
		if (!$this->task || !$this->act) {
			trigger_error('Task and Act must be set before calling method "callTask()"');
			return false;
		}

		if ($this->task_exists()) {
			if (!$this->TaskInstance)
				$this->TaskInstance = new $this->task();
			if ($this->act_callable()) {
				$args = func_get_args();
				array_shift($args);
				if (call_user_func_array(array($this->TaskInstance, $this->act), $args) === false) {
					return false;
				}
				return true;
			} else {
				trigger_error('The action cannot be called.');
				return false;
			}
		} else {
			trigger_error('The requested action, "'.$this->task.'->'.$this->act.'", has no handler.');
			return false;
		}
	}
	
	function InstantiateTask() {
		$this->TaskInstance = new $this->$task;
	}

	function task_exists() {
		return class_exists($this->task);
	}
	
	function act_callable() {
		return is_callable(array($this->TaskInstance, $this->act));
	}
}

/**
* FWD Template, Delegates template tasks
*/
class fwd_Template extends fwd_Tasker {

	/** 
	* Constructor, sets the NameSpace which is prepended to Task Functions
	* @param string Namespace (json, html, xml etc.)
	*/
	function fwd_Template($ns = 'fwd_TMPL_', $format = false) {
		global $fwdExt;
		// make checks to ensure we don't allow abitrary code includes
		if (preg_match("/^[a-z0-9]+$/i", $format)) {
			$this->fwd_Tasker($ns, $format);
			require_once($fwdExt->ext_path.'tmpl/tag.'.$format.'.php');
		} else {
			trigger_error('Template type not allowed');
		}
	}

	
	/** 
	* Call the Template Task
	* @param string $act A non-private method of the $task instance
	*/
	function call($act) {
		$this->act = $act;
		if (!$this->TaskInstance)
			$this->TaskInstance = new $this->task();
		if ($this->act_callable()) {
			$args = func_get_args();
			array_shift($args);
			if (call_user_func_array(array($this->TaskInstance, $this->act), $args) === false) {
				return false;
			}
			return true;
		} else {
			trigger_error('Template Function not callable');
			return false;
		}
	}
}

?>