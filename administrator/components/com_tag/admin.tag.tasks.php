<?php

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/**
* Static class called before and after loading every admin UI
*/
class tag_admin {

	function before() {
		tag_admin_html::before();
	}
	
	function after() {
		tag_admin_html::after();
	}
}

/**
* Static task=index Class
*/
class tag_admin_index {

	/* constructor */
	function tag_admin_index() {
		$this->list_table = '#__tag';
		$this->config_table = '#__tag_settings';
		$this->tabs_table = '#__tag_tabs';
	}

	/**
	* Lists the tags
	*/
	function index($msg = false) {
		global $fwd_URL, $fwdExt, $database; // objects
		global $mosConfig_list_limit;
		
		// database
		$db = new fwd_commonDB($database, false);
		
		// http vars
		$limit = intval(mosGetParam($_REQUEST, 'limit', $mosConfig_list_limit));
		$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));
		$where = mosGetParam($_REQUEST, 'where', '');
		
		if (is_array($where)) {
			$str = $sep = '';
			foreach($where as $name=>$value) {
				$str .= $sep.$db->safeEscape($name)." = '".$db->safeEscape($value)."'";
				$sep = ' AND ';
			}
			$where = 'where '.$str;
		}
		
		// page navigation
		require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
		$total = $db->Result("SELECT count(id) FROM #__tag");
		$pageNav = new mosPageNav( $total, $limitstart, $limit );
		
		// get tags from db
		$query = "SELECT t.*, u.username, c.title AS ctitle FROM ".$this->list_table." AS t"
		."\n LEFT JOIN #__users AS u ON (t.user_id = u.id)"
		."\n LEFT JOIN #__content AS c ON (t.cid = c.id)"
		."\n $where"
		."\n LIMIT $limitstart, $limit"
		."";
		$rows = $db->ObjectList($query);
		//$fwdExt->dump($rows);
		
		if ($msg) {
			tag_admin_html::msg($msg);
		}
		tag_admin_html::adminHeader('Tags Management');
		tag_admin_html::listTags($rows, $pageNav);
		
	}
	
	/**
	* Edit Tags
	*/
	function edit() {
		global $fwd_URL, $fwdExt, $database; // objects
		
		// http
		$cid = mosGetParam($_REQUEST, 'cid', false);
		if (!is_array($cid)) {
			// return to index page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
			fwd_Extension::redirect($fwd_URL->toString(), 'An Error Occurred. No selection to edit.');
		}
		
		// database
		$db = new fwd_commonDB($database, false);
		
		// get tags from db
		$query = "SELECT * FROM ".$this->list_table 
		."\n WHERE id IN (".implode(',', $cid).")"
		."";
		$rows = $db->ObjectList($query);
		//$fwdExt->dump($rows);
		
		$fwd_URL->setParam('cid', implode(',', $cid)); // remember cid
		
		tag_admin_html::adminHeader('Tags Management - <small>Editing Tags</small>');
		tag_admin_html::editTags($rows);
		
	}
	
	/**
	* Cancel
	*/
	function cancel() {
		global $fwd_URL;
		
		// return to index page
		$fwd_URL->setParam('task', 'index');
		$fwd_URL->setParam('act', 'index');
		//fwd_Extension::redirect($fwd_URL->toString());
		$this->index();
	}
	
	/**
	* Update
	*/
	function update() {
		global $fwd_URL;
		
		// save the cid (ids being edited) to our URI
		$cid = mosGetParam($_REQUEST, 'cid', false);
		$cid = explode(',', $cid);
		foreach($cid as $i=>$id) {
			$fwd_URL->setParam("cid[$i]", $id);
		}
		$this->save(true);
	}
	
	/**
	* Save the Edits
	*/
	function save($return = false) {
		global $fwd_URL, $fwdExt, $database; // objects
		global $mosConfig_list_limit; 
		
		// database
		$db = new fwd_commonDB($database, false);
		
		// table columns
		$cols = $db->tableColumns($this->list_table);
		
		//$fwdExt->dump($cols);
		//$fwdExt->dump($_POST);

		// iterate through table columns and see if we have corresponding HTTP POST vars
		$rows = array();
		foreach($cols as $name=>$type) {
			$field = mosGetParam($_POST, $name, NULL);
			if (is_array($field)) {
				foreach($field as $id=>$value) {
					$rows[$id][$name] = $value;
				}
			}
		}
		
		//$fwdExt->dump($rows);
		//return;
		
		// save the rows into the database
		$db->Table($this->list_table);
		foreach($rows as $id=>$row) {
			if (is_array($row)) {
				foreach($row as $field=>$val) {
					if ($cols[$field] == 'int' || $cols[$field] == 'tinyint') {
						$val = (int) $val;
					}
					$db->Field($field, $val);
				}
				if (!$db->Update("WHERE id = ".intval($id))) {
					// return to index page
					$fwd_URL->setParam('task', 'index');
					$fwd_URL->setParam('act', 'index');
					fwd_Extension::redirect($fwd_URL->toString(), 'The Database Update Failed.');
				}
			} else {
				trigger_error('The entry was empty');
				die;
			}
		}
		
		if ($return) {
			// return to edit page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'edit');
		} else {
			// return to index page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
		}
		fwd_Extension::redirect($fwd_URL->toString(), 'The Entries were updated successfully.');
		
	}
	
	/**
	* Save a new entry
	*/
	function insert() {
		global $fwd_URL, $fwdExt, $database; // objects
		global $mosConfig_list_limit; 
		
		// database
		$db = new fwd_commonDB($database, false);
		
		// table columns
		$cols = $db->tableColumns($this->list_table);
		
		$rows = array();
		foreach($cols as $name=>$type) {
			$field = mosGetParam($_POST, $name, NULL);
			if (is_array($field)) {
				foreach($field as $id=>$value) {
					$rows[$id][$name] = $value;
				}
			}
		}
		
		//$fwdExt->dump($rows);
		//return;
		
		// save the rows into the database
		$db->Table($this->list_table);
		foreach($rows as $id=>$row) {
			if (is_array($row)) {
				foreach($row as $field=>$val) {
					if ($cols[$field] == 'int' || $cols[$field] == 'tinyint') {
						$val = (int) $val;
					}
					$db->Field($field, $val);
				}
				if (!$db->Insert()) {
					// return to index page
					$fwd_URL->setParam('task', 'index');
					$fwd_URL->setParam('act', 'index');
					fwd_Extension::redirect($fwd_URL->toString(), 'The Database Insert Failed.');
				}
			} else {
				trigger_error('The entry was empty');
				die;
			}
		}
		
		if ($return) {
			// return to add page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'add');
		} else {
			// return to index page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
		}
		fwd_Extension::redirect($fwd_URL->toString(), 'The New Entries were added successfully.');
		
	}
	
	/**
	* Remoeve an entry
	*/
	function delete() {
		global $fwd_URL, $fwdExt, $database; // objects
		global $mosConfig_list_limit; 
		
		// http
		$cid = mosGetParam($_REQUEST, 'cid', false);
		if (!is_array($cid)) {
			// return to index page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
			fwd_Extension::redirect($fwd_URL->toString(), 'An Error Occurred. No entries selected.');
		}
		
		// database
		$db = new fwd_commonDB($database, false);
		
		$query = "DELETE FROM ".$this->list_table.""
		."\n WHERE id IN (".implode(',', $cid).")"
		."\n LIMIT ".count($cid);

		if (!$db->Query($query)) {
			trigger_error('The mysql query failed');
			die;
		}
		
		$fwd_URL->setParam('task', 'index');
		$fwd_URL->setParam('act', 'index');
		fwd_Extension::redirect($fwd_URL->toString(), 'The Selected Entries were removed successfully.');
		
	}
	
	/**
	* Handles publish and unpublish
	*/
	function _publishing($state, $success_msg) {
		global $fwd_URL, $fwdExt, $database; // objects
		
		// http
		$cid = mosGetParam($_REQUEST, 'cid', false);
		if (!is_array($cid)) {
			// return to index page
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
			fwd_Extension::redirect($fwd_URL->toString(), 'An Error Occurred. No entries selected.');
		}
		
		// database
		$db = new fwd_commonDB($database, false);
		
		// get tags from db
		$query = "UPDATE ".$this->list_table 
		."\n SET published = ".intval($state).""
		."\n WHERE id IN (".implode(',', $cid).")"
		."";
		if ($db->Query($query)) {
			// publish ok
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
			fwd_Extension::redirect($fwd_URL->toString(), $success_msg);
		} else {
			// error
			$fwd_URL->setParam('task', 'index');
			$fwd_URL->setParam('act', 'index');
			fwd_Extension::redirect($fwd_URL->toString(), 'An Error Occurred. Database query failed.');
		}
	}
	
	/**
	* Publish Tags
	*/
	function publish() {
		$this->_publishing(1, 'The selected Entrie(s) were published successfully.');
	}
	
	/**
	* UnPublish Tags
	*/
	function unpublish() {
		$this->_publishing(0, 'The selected Entrie(s) were unpublished successfully.');
	}
	
	/**
	* Add Tags
	*/
	function add() {
		global $database;
		// number of inserts
		if (!$count = intval(mosGetParam($_REQUEST, 'count', 1))) {
			$count = 1;
		}
		
		// table columns
		$db = new fwd_commonDB($database, false);
		$cols = $db->tableColumns($this->list_table);
		
		// prepair fields
		for($i = 0; $i < $count; $i++) {
			$rows[] = $cols;
		}
		
		//global $fwdExt;
		//$fwdExt->dump($rows);
		//return;
		
		// database
		
		tag_admin_html::adminHeader('Tags Management - <small>Add Tags</small>');
		tag_admin_html::addTags($rows);
	}
	
}

/**
* Static task=config Class
*/
class tag_admin_config {

	/* constructor */
	function tag_admin_config() {
		$this->config_table = '#__tag_config';
		$this->tabs_table = '#__tag_tabs';
	}
	
	function config() {
		global $database, $mosConfig_absolute_path;
		
		$Config = new tag_Config($database, $this->config_table, $this->tabs_table);
		$config =& $Config->getConfigRaw('config', true, 'tabid', 'ASC');
		$tabs =& $Config->getTabs('config');
	
		tag_admin_html::adminHeader('Settings Manager');
		tag_admin_html::config($tabs, $config);
		return true;
	}
	
	function _save($table, $prefix) {
		global $database, $fwdExt;
		
		require_once $fwdExt->ext_path().'includes/json.pear.php';
		
		$db = new fwd_commonDB($database, false);
		$json = new Services_JSON();
		
		$db->Table($table);
		$count = 0;
		foreach($_REQUEST as $name=>$value) {
			if (strpos($name, $prefix) === 0) {
			
				$name = substr($name, 4, strlen($name) - 4);
				
				if (is_array($value)) {
					$value = $json->encode($value);
				}
				
				$db->Field('value', $value);
				if (!$db->Update("WHERE name = '".$db->safeEscape($name)."' LIMIT 1", true)) {
					return false;
				}
				$count++;
			}
		}
		return $count;
	}
	
	function save() {
		global $fwd_URL;
		
		if ($result = $this->_save($this->config_table, 'jmc_')) {
			$msg = 'Settings Saved Successfully ('.$result.' in total)';
		} else {
			if ($result === false)
				$msg = 'An Error Occured. Settings could not be saved.';
			else
				$msg = 'An Error Occured. No Settings were saved.';
		}
		
		// return to index page
		$fwd_URL->setParam('task', 'index');
		$fwd_URL->setParam('act', 'index');
		fwd_Extension::redirect($fwd_URL->toString(), $msg);

	}
	
	function update() {
		global $fwd_URL;
		
		if ($result = $this->_save($this->config_table, 'jmc_')) {
			$msg = 'Settings Updated Successfully ('.$result.' in total)';
		} else {
			if ($result === false)
				$msg = 'An Error Occured. Settings could not be saved.';
			else
				$msg = 'An Error Occured. No Settings were saved.';
		}
		
		// return to config page
		$fwd_URL->setParam('act', 'config');
		fwd_Extension::redirect($fwd_URL->toString(), $msg);
	}
	
	function cancel() {
		global $fwd_URL;
		// return to index page
		$fwd_URL->setParam('task', 'index');
		$fwd_URL->setParam('act', 'index');
		$msg = 'Settings were not changed.';
		fwd_Extension::redirect($fwd_URL->toString(), $msg);
	}
	
}

/**
* Static task=about Class
*/
class tag_admin_about {

	function about() {
		tag_admin_html::adminHeader('About This Component');
		tag_admin_html::about();
		return true;
	}
}

?>