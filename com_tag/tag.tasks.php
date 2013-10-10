<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

/**
* Tasks
* Each Tasks Class is prefixed with com_tag_
* The $task classname determines the task, eg: com_tag_index is the $task = index
* Each method is the $act, eg: task=index&act=cloud calls, $task = new com_tag_index(); $task->cloud();
* Methods are called dynamically so we have a constructor which instantiates common properties and methods
* This format also allows redirecting of tasks without HTTP Redirects
*/

/* task=index */
class com_tag_index {

	/** constructor */
	function com_tag_index() {
		global $database, $fwd_Template;
		$this->db =& $database;
		$this->config_table = '#__tag_config';
		$this->tabs_table = '#__tag_tabs';
		$this->tag_table = '#__tag';
		$this->fwd_Tmpl =& $fwd_Template;
	}
	
	/**
	* Default Task when no task or act set
	* Here we can control what task->act to call depending on http params (allow short/SEF urls)
	*/
	function index() {
	
		$Tasker = new fwd_Tasker('com_tag_');

		// delegate task depending on http vars
		if ($tag = mosGetParam($_REQUEST, 'tag')) {
			$Tasker->setTask('view');
			if (!$Tasker->callTaskAct('tagged', $tag)) {
				$this->fwd_Tmpl->call('Error', 'An Error occurred handling the "tagged" action.');
			}
		} else {
			$Config = new tag_Config($this->db, $this->config_table, $this->tabs_table);
			$config = $Config->getConfig();
			
			//var_dump($config);
			
			$Tasker->setTask('view');
			if ($Tasker->callTaskAct($config['default_act']) === false) {
				$this->fwd_Tmpl->call('Error', 'An Error occurred handling the default action.');
			}
		}
	}
}

/* task=view */
class com_tag_view {

	/** constructor */
	function com_tag_view() {
		global $database, $fwd_Template, $fwdExt;
		$this->db =& new fwd_commonDB($database, true);
		$this->api =& new com_tag_API($db);
		$this->fwd_Tmpl =& $fwd_Template;
		$this->params =& $fwdExt->getMenuParams();
		// tables
		$this->config_table = '#__tag_config';
		$this->tabs_table = '#__tag_tabs';
		$this->tag_table = '#__tag';
	}
	
	// redirect to cloud
	function view() {
		$this->cloud();
	}
	
	// view tag cloud
	function cloud() {
		// filters
		$http_overide = $this->params->def('http_overide', true);
		$keys = array('cid', 'catid', 'sectid', 'userid', 'tag');
		foreach($keys as $key) {
			$filters[$key] =  $this->params->def($key, false);
			if ($http_overide) {
				if ($value = mosGetParam($_REQUEST, $key, false)) {
					$filters[$key] = $value;
				}
			}
		}
		$titles = array();
		
		// api handles $cid, $catid, $sectid filters
		$tags = $this->api->getTagCloud($filters);
		
		// select the titles for $cid, $catid, $sectid filters
		$tables = array('a'=>'#__content', 'c'=>'#__categories', 's'=>'#__sections');
		$ids = array('a'=>$filters['cid'], 'c'=>$filters['catid'], 's'=>$filters['sectid']);
		foreach($ids as $pre=>$id) {
			if ((bool) $id) {
				$query = "SELECT title"
				."\n FROM ".$tables[$pre]
				."\n WHERE id = ".$id
				."\n LIMIT 1"
				."";
				$title = $this->db->Result($query);
				$titles[$pre] = array('id'=>$id, 'title'=>$title);
			}
		}

		$this->fwd_Tmpl->call('cloud', $tags, $titles, $this->params);
	}
	
	// view content Items tagged with the given tag
	function tagged($tag = false) {
		global $Itemid, $mainframe;
		if (!$tag) {
			if (!$tag = mosGetParam($_REQUEST, 'tag')) {
				trigger_error('Tag Not Found', E_USER_ERROR);
				return false;
			}
		}
		$rows = false;
		if ($tag) {
			$total = intval($this->api->getTotalTagged($tag));
			$limitstart = intval(mosGetParam($_REQUEST, 'limitstart', 0));
			$limit =  intval(mosGetParam($_REQUEST, 'limit', 20));
			require_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/pageNavigation.php' );
			$pageNav = new mosPageNav( $total, $limitstart, $limit );
			$rows = $this->api->getTagged($tag, $limitstart, $limit);
			
			$rel_tags = $this->params->def('show_related_tags', false) ? $this->api->getRelatedTagCloud(array($tag)) : array();
			
			// configuration todo: override global configs with menu configs
			//global $database;
			//$Config = new tag_Config($database, $this->config_table, $this->tabs_table);
			//$Config->getConfigRaw('config');
			//$params = $Config->getParams();
			
			$this->fwd_Tmpl->call('listTagged', $tag, $rows, $pageNav, $this->params, $rel_tags);
		} else {
			$this->fwd_Tmpl->call('Error', 'No Tag was specified');
		}
	}
}

/* task=author */
class com_tag_author {

	var $tags_tbl;
	var $pspell; // spelling lib

	/** constructor */
	function com_tag_author() {
		global $database, $fwd_Template;
		$this->tags_tbl = '#__tag';	
		$this->db =& new fwd_commonDB($database);
		$this->api =& new com_tag_API($this->db);
		$this->tmpl =& $fwd_Template;
		
	}
	
	// view 
	function _view() {
		$db =& $this->db;
		$state = mosGetParam($_REQUEST, 'state');
		$f = mosGetParam($_REQUEST, 'f');
		if ($state) {
			$query = "SELECT * FROM #__tags WHERE state = '".$db->safeEscape($state)."' LIMIT 1";
			if ($row = $db->Object($query)) {
				HTML_tag::view($row, $f);
			} else {
				HTML_tag::error('The selected listing could not be found.');
			}
		} else {
			HTML_tag::error('Invalid Listing.');
		}
	}
	
	// edit tags
	function _edit() {
		$db =& $this->db;
		$tags = $db->safeEscape(mosGetParam($_REQUEST, 'tags'));
		$cid = intval(mosGetParam($_REQUEST, 'cid'));
		$limit = intval($config->limit);
		if ($tags) {
			$query = "SELECT id, tag, count(id) AS count "
			."\n FROM ".$this->tags_tbl." WHERE cid = $cid"
			//."\n ORDER BY "
			."\n GROUP BY tag"
			//."\n  LIMIT $limit"
			."";
			if ($tags = $db->Result($query)) {
				$tags = explode(',', $tags);
			} else {
				
			}
		} else {
			HTML_tag::error('No Tags specified.');
		}
	}
	
	// add tags
	function add() {
		global $my;
		if (!$my->id) {
			$this->tmpl->call('Error', 'Sorry, You need to be logged in to add Tags.');
			return;
		}
		$tags = mosGetParam($_REQUEST, 'tags');
		$tags = explode($this->api->sep, $tags); // convert to array
		$cid = mosGetParam($_REQUEST, 'cid');
		
		if ($cid && $tags) {
			$results = array();
			foreach($tags as $tag) {
				$tag = trim($tag);
				$results[$tag]->suggest = $this->_suggestSpelling($tag);
				$results[$tag]->id = $this->api->addTag($cid, $tag, $my->id);
			}
			$this->tmpl->call('addTags', $cid, $results);
		} else {
			$this->tmpl->call('Error', 'Invalid Input Params'); 
		}
	}
	
	// check spelling an return suggestions
	function _suggestSpelling($word) {
		if ($this->pspell) {
			if (!pspell_check($this->pspell, $word)) {
				$suggestions = pspell_suggest($this->pspell, $word);
				return $suggestions;
			}
		} else {
			return false;
		}
	}
	
	// instantiate pspell
	function _initPspell() {
		if (!$this->pspell) {
			if (function_exists('pspell_new')) {
				$this->pspell = pspell_new('en');
			} else {
				$this->pspell = false;
			}
		}
	}
}

/* Combines multiple HTML embedded files into one */
class com_tag_external {

	var $tags_tbl;
	var $files;

	/** constructor */
	function com_tag_external() {
		$this->files = array();
		// set the HTTP headers
		header("Cache-Control: must-revalidate");
		$this->expires = gmdate("D, d M Y H:i:s", time() + 3600)." GMT";
		$this->modified = gmdate("D, d M Y H:i:s", time())." GMT";
		header("Expires: ".$this->expires);
		header("Last-Modified: ".$this->modified);
		// notes
		echo "/*\r\n* info@fijiwebdesign.com \r\n* Generated: {$this->modified}\r\n";
		echo "* Expires: {$this->expires}\r\n*/";
	}
	
	/**
	* Add a file to be included in the output
	* @param string $path relative to $type folder.
	*/
	function addFile($path, $type = 'js') {
		$this->files[$type][] = $path;
	}
	
	/**
	* Echo the contents of the added files
	* @param string $type filetype
	*/
	function loadFiles($type, $content_type = false) {
		
		if ($content_type) {
			header('Content-Type: '.$content_type);
		}
	
		if (isset($this->files[$type])) {
			foreach($this->files[$type] as $file) {
				$this->_include_file($file, $type);
			}
		}
	}
	
	/** default */
	function external() {
		echo '/** Specify a file type to load eg: act=js or act=css **/';
	}
	
	/** load CSS Files */
	function css() {
		$this->addFile('tag.global.css', 'css');
		$this->loadFiles('css', 'text/css');
	}
	
	/** load JS files */
	function js() {
		$this->addFile('fwd.common.js');
		$this->addFile('fwd.xhr.js');
		$this->addFile('tag.functions.js');
		$this->addFile('tag.configs.php');
		$this->addFile('tag.reltags.js');
		$this->loadFiles('js', 'application/x-javascript');
	}
	
	function _include_file($file, $type = 'js') {
		global $fwdExt;
		echo "\r\n\r\n";
		include_once($fwdExt->ext_path.$type.'/'.$file);
	}
	
}

?>