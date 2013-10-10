<?php

/**
 * FijiWebDesign Classes for Joomla
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

// flag that fijiwebdesign classes are already loaded
if (!defined('_FWD_EXTS_LOADED')) {
	define('_FWD_EXTS_LOADED', true);
	require_once($GLOBALS['mosConfig_absolute_path'].'/components/com_tag/includes/fwd.class.php');
} 

/**
* Configuration API
*/
class tag_Config {

	/** fwd_Database Instance */
	var $db;
	/** Config Table */
	var $config_table;
	/** Tabs Table */
	var $tabs_table;
	
	/** Settings */
	var $config;
	/** Raw Config data in DB */
	var $raw_configs;

	/**
	* Constructor, instantiate our needed Classes
	*/
	function tag_Config(&$database, $config_table, $tabs_table) {
		$this->db = new fwd_commonDB($database, false); // db handler
		$this->config_table = $config_table;
		$this->tabs_table = $tabs_table;
	}
	
	/**
	* Retrieve Configuration as assoc array
	*/
	function getConfig($ns = false, $refresh = false) {
	
		if ($this->config && !$refresh) {
			return $this->config;
		}
		
		// retrieve config from DB
		$configs = $this->getConfigRaw($ns, $refresh);
		
		// todo: type cast configs
		// todo: write to cache file on admin save
		$settings = array();
		foreach($configs as $config) {
			if ($config->type == 'int') {
				$settings[$config->name] = (int) $config->value;
			} else if ($config->type == 'boo') {
				$settings[$config->name] = (bool) $config->value;
			} else {
				$settings[$config->name] = (string) $config->value;
			}
		}
		
		return $settings;
	}
	
	/**
	* Retrieve Configuration as string. 
	* The default format allows for: $params = new mosParameters( $this->getConfigStr() );
	*/
	function getConfigStr($del = "\n") {
		$config = array();
		if (!$this->raw_configs) {
			trigger_error('Call getConfigRaw() first');
			return false;
		}
		// format as str
		$str = '';
		foreach($this->raw_configs as $config) {
			$str = "{$config->name}={$config->value}".$del;
		}
		return $str;
	}
	
	/**
	* Retrieve Configuration as mosParameters
	*/
	function getParams($ns = false) {
		return new mosParameters( $this->getConfigStr() );
	}
	
	/**
	* Retrieve Raw Configuration Data from DB
	*/
	function getConfigRaw($namespace = false, $refresh = false, $order = false, $arrange = false) {
	
		if ($this->raw_configs && !$refresh) {
			return $this->raw_configs;
		}
		
		$query = "SELECT s.* FROM {$this->config_table} AS s"
		//." LEFT JOIN #__jm_tabs AS t ON (t.id = s.tabid)"
		."\n ".($namespace ? "WHERE s.namespace = '$namespace'" : '')
		."\n GROUP BY s.namespace, s.id"
		."\n ".($order ? "ORDER BY $order" : '')
		."\n ".($arrange && $order ? "$arrange" : "")
		."";
		$this->raw_configs = $this->db->ObjectList($query);
		
		return $this->raw_configs;
	}
	
	/**
	* Retrieve the Tabs from database
	* @param string 
	*/
	function getTabs($cat = 0) {
	
		if (isset($tabs[$cat])) {
			return $tabs[$cat];
		}

		$query = "SELECT * FROM {$this->tabs_table} "
		.($cat ? " WHERE category = '$cat'" : '')
		.'';
		$this->tabs[$cat] = $this->db->ObjectList($query);
		
		return $this->tabs[$cat];
	}
	
}

/**
* Parses the range functions, query(), array(), and list()
* This allows for dynamic ranges defined for configurations in the cdatabase table
* Supported are query($sql), array($assoc_array), list($numeric_array)
* Strings only, so you need to keep datatypes in db table or ignore it
*/
class tag_Config_range {

	/** @param Object Joomail DB class instance */
	var $db;
	
	/** 
	* constructor
	*/
	function tag_Config_range(&$database) {
		$this->db = new fwd_commonDB($database);
	}
	
	/**
	* Parse the Range code
	*/
	function parse($code) {
	
		// parse begin and end of params
		$start = strpos($code, '(')+1;
		$end = strpos($code, ')', $start);
		
		// retrieve params
		$params = substr($code, $start, $end - $start);
		
		// retrieve function name
		$func = 'range_'.substr($code, 0, $start-1);
		
		// call the function
		if (is_callable(array($this, $func))) {
			return $this->$func($params);
		} else {
			// nothing for now
			trigger_error('Range Method does not exist. Please see the Configuration API.');
		}
		
	}

	/**
	* Return a mosHTML::selectList Array from SQL query
	*/
	function range_query($sql) {
		$sql = trim($sql, '"\"');
		return $this->db->ObjectList($sql);
		
	}
	
	/**
	* Return a mosHTML::selectList Array from array()
	*/
	function range_array($params) {
		$str = '$_array = array('.$params.');';
		eval($str); // todo, parse without eval
		$array = array();
		if (count($_array)) {
			foreach($_array as $i=>$v) {
				$item = new stdClass();
				$item->value = $v;
				$item->text = $i;
				$array[] = $item;
			}
		}
		return $array;
		
	}
	
	/**
	* Return a mosHTML::selectList Array from list()
	*/
	function range_list($params) {

		$_array = explode(',', $params);
		if (count($_array)) {
			foreach($_array as $v) {
				$item = new stdClass();
				$v =  trim($v, "'\" ");
				$item->value = $item->text = $v;
				$array[] = $item;
			}
		}
		return $array;
	}
	
}


?>