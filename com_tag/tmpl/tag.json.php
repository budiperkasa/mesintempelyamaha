<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/* JSON Template */
class com_tag_tmpl_json {

	/* constructor */
	function com_tag_tmpl_json() {
		global $fwdExt;
		// include JSON Lib
		require_once($fwdExt->ext_path.'includes/json.pear.php');
		$this->JSON = new Services_JSON();
		header('Content-Type: text/javascript');
	}

	// generic error function
	function Error($msg) {
		echo "{err_msg: '$msg'}";
	}
	
	// response when tags added successfully
	function addTags($cid, $tags) {
		$response = array(
			'cid'=>$cid,
			'tags'=>$tags
		);
		echo $this->JSON->encode($response);
	}
	
	// returns tag cloud
	function cloud(&$tags, &$titles, &$params) {
		$arr = array();
		foreach($tags as $tag) {
			$arr[$tag->tag] = $tag->weight;
		}
		echo $this->JSON->encode($arr);
	}
	
}

?>