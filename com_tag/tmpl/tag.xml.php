<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/* JSON Template */
class com_tag_tmpl_xml {

	/* constructor */
	function com_tag_tmpl_xml() {
		// xml http header
		header('Content-Type: text/xml');
	}
	
	// wrap in <resp>
	function Resp($xml) {
		return '<resp>'.$xml.'</resp>';
	}
	
	// convert and object to xml
	function xml2obj($obj) {
		$xml = '';
		if (is_array($obj) || is_object($obj)) {
			foreach($obj as $name=>$value) {
				$nodeName = (strval(intval($name)) === strval($name)) ? 'list' : $name;
				$xml .= "<$nodeName>".$this->xml2obj($value)."</$nodeName>";
			}
		} else {
			$xml .= "<".gettype($obj).">$obj</".gettype($obj).">";
		}
		return $xml;
	}

	// generic error function
	function Error($msg) {
		echo $this->Resp('<error>'.$msg.'</error>');
	}
	
	// response when tags added successfully
	function addTags($cid, $tags) {
		$response = array(
			'cid'=>$cid,
			'tags'=>$tags
		);
		echo $this->xml2obj($response);
	}
	
	// displays the tag cloud
	function cloud(&$tags, &$titles, &$params) {
		$arr = array();
		foreach($tags as $tag) {
			$arr[$tag->tag] = intval($tag->weight);
		}
		echo $this->Resp($this->xml2obj($arr));
	}
	
}

?>