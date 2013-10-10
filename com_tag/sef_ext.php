<?php
/**
* openSEF Plugin for com_tag, http://www.fijiwebdesign.com
* @copyright Fiji Web Design
* @author gabe@fijiwebdesign.com
**/

class sef_tag {
	
	function create ($string) {

		$sefstring = "";

		if (stristr($string, "&amp;tag=")) {
			$temp = split("&amp;tag=", $string);
			$temp = split("&amp;", @$temp[1]);
			$sefstring .= sefencode($temp[0])."/";
		}

		if (stristr($string, "&amp;limit=")) {
			$temp = split("&amp;limit=", $string);
			@$temp = split("&", @$temp[1]);
			$sefstring .= @sefencode($temp[0])."/";
		}

		if (stristr($string, "&amp;limitstart=")) {
			$temp = split("&amp;limitstart=", $string);
			@$temp = split("&", @$temp[1]);
			$sefstring .= @sefencode($temp[0])."/";
		}

		return $sefstring;
	}

	function revert ($url_array, $pos) {
		$QUERY_STRING = "";
		if (isset($url_array[$pos+2])) {
			if ($url_array[$pos+2]=='cloud'){
				$_GET['page'] = $url_array[$pos+2];
				$_REQUEST['page'] = $url_array[$pos+2];
				$QUERY_STRING .= "&page=".$url_array[$pos+2];
			} else {

				$t = sefdecode($url_array[$pos+2]);
				$_GET['tag'] = $t;
				$_REQUEST['tag'] = $t;
				$QUERY_STRING .= "&tag=".$t;
			}
		}
		if (isset($url_array[$pos+3]) && $url_array[$pos+3] > 0) {
			$t = sefdecode($url_array[$pos+3]);
			$_GET['limit'] = $t;
			$_REQUEST['limit'] = $t;
			$QUERY_STRING .= "&limit=".$t;
		}
		if (isset($url_array[$pos+4])) {
			$t = sefdecode($url_array[$pos+4]);
			$_GET['limitstart'] = $t;
			$_REQUEST['limitstart'] = $t;
			$QUERY_STRING .= "&limitstart=".$t;
		}

		return $QUERY_STRING;
	}
}
?>