<?php
/*
* mod_html allows inclusion of HTML/JS/CSS and now PHP, in Joomla/Mambo Modules
* (c) Copyright: Fiji Web Design, www.fijiwebdesign.com.
* email: info@fijiwebdesign.com 
* date: Feb 4, 2007
* Release: 1.0.0.Beta Test2
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path;

// make sure class loaded once only
if (!class_exists('mod_tagcloud')) {

// include fwd class
require_once($mosConfig_absolute_path.'/components/com_tag/tag.class.php');

// Global "Common Extension Functions" Class Instance
$fwdExt = new fwd_Extension('com_tag');
// load config
require_once( $fwdExt->ext_path.'tag.config.php' );
// load API
require_once( $fwdExt->ext_path.'tag.api.php' );


// our module class
class mod_tagcloud {

	// constructor
	function mod_tagcloud(&$fwdExt, &$params) {
		global $database;
		$this->db =& new fwd_commonDB($database, true);
		$this->api =& new com_tag_API($db);
		$this->params =& $params;
	}

	// view tag cloud
	function cloud() {
		
		// filters
		$http_overide = $this->params->def('http_overide', false);
		$keys = array('cid', 'catid', 'sectid', 'userid', 'tag');
		foreach($keys as $key) {
			$filters[$key] =  $this->params->def($key, false);
			if ($http_overide) {
				if ($value = mosGetParam($_REQUEST, $key, false)) {
					$filters[$key] = $value;
				}
			}
		}
		
		$limit = intval($this->params->def('limit', 50));
		
		// get the weighted tags
		$tags = $this->api->getTagCloud($filters, $limit); 

		// template
		$this->html_cloud($tags, $titles, $this->params);
	}
	
	// displays the tag cloud HTML
	function html_cloud(&$tags, &$titles, &$params) {
		global $mainframe, $Itemid, $mosConfig_live_site;
		
		// load CSS
		$mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="'.$mosConfig_live_site.'/components/com_tag/css/tag.global.css" />');
		
		echo '<!-- start tag cloud -->';
		echo '<div class="tag_cloud">';
		//echo '<table>';
		if ($count = count($tags)) {

			// get largest weight weights
			$max_weight = 1;
			$weight_limit = 80;
			for($i = 0; $i < $count; $i++) {
				if ($tags[$i]->weight > $max_weight) {
					$max_weight = $tags[$i]->weight;
				}
			}
			$weight_part = $weight_limit/$max_weight;
			
			$sep = '';
			for($i = 0; $i < $count; $i++) {
				$tag =& $tags[$i];

				$font = intval($tag->weight*$weight_part + 100 ).'%';
				//var_dump($font);
				echo $sep.'<a rel="tag" href="'.sefRelToAbs('index.php?option=com_tag&amp;tag='.$tag->tag.'&amp;Itemid='.$Itemid).'"><span class="tag" style="font-size:'.$font.';">'.$tag->tag.'</span></a>';
				$sep = ', ';
			}
				
		} else {
			echo '<div class="error">No Tags Yet.</div>';
		}
		echo '</div>';
		echo '<div style="font-size:60%;text-align:right;"><a title="Joomla Tag Cloud &copy; Fiji Web Design&trade;" target="blank" href="http://www.fijiwebdesign.com/products/joomla-tag-component.html">Joomla Tag Cloud</a>&trade;</div>';
		echo '<!-- end tag cloud -->';
	}
}

} // class exists

$tag_cloud = new mod_tagcloud($fwdExt, $params);
$tag_cloud->cloud();

?>