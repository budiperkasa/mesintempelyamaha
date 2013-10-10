<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/* JSON Template */
class com_tag_tmpl_xhtml extends HTML_tag {

	/* constructor */
	function com_tag_tmpl_xhtml() {
		// xml http header see: http://www.answers.com/topic/xml-and-mime
		header('Content-Type: text/html');
		// xml declaration
		echo '<?xml version="1.0" encoding="utf-8"?>';
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
		global $mainframe, $Itemid, $mosConfig_live_site;

		// display tags in cloud
		$percol = 8; // tags per col
		$font_size = 12; // normal font-size in px
		
		echo '<!-- start tag cloud -->';
		echo '<div class="tag_cloud">';
		if ($count = count($tags)) {
			$j = 0;
			$sep = '';
			for($i = 0; $i < $count; $i++) {
				$tag =& $tags[$i];
				$font = intval((($tag->weight/($count/2))*100)+100).'%';
				echo $sep;
				$sep = ', ';
				echo '<a rel="tag" href="'.sefRelToAbs('index.php?option=com_tag&tag='.$tag->tag.'&Itemid='.$Itemid).'"><span class="tag" style="font-size:'.$font.';">'.$tag->tag.'</span></a>';
				if ($j == $percol) {
					$j = 1;
				} else {
					$j++;
				}		
			}
			echo '<div style="clear:both;"></div>';
		} else {
			echo '<div class="error">No Tags Yet.</div>';
		}
		echo '</div>';
		echo '<!-- end tag cloud -->';
	}
	
	// list the tagged content
	function listTagged($tag, &$rows, &$pageNav, &$params, &$tags) {
		global $mainframe, $Itemid;
		
		// header
		$header = 'Articles Tagged:  <i>'.$tag.'</i>';
		echo '<div class="componentheading'. $params->get( 'pageclass_sfx' ) .'">'. $header .'</div>';
	
		// display articles
		$count = is_array($rows) ? count($rows) : 0;
		echo '<ul class="tagged">';
		if ($count > 0) {
			for($i = 0; $i < $count; $i++) {
				$row =& $rows[$i];
				$row->text = $row->introtext; //.$row->fulltext;
				// render mambots
				list($after_title, $before_content, $row, $after_content) = $this->_callPlugins($row);
				echo '<li><a href="'.sefRelToAbs('index.php?option=com_content&task=view&id='.$row->id).'" title="'.$row->title.'" class="tag_item">'.$row->title.'</a></li>';
				
			}
		} else {
			echo '<li class="error">No Content Found.</li>';
		}
		echo '</ul>';
		
		if ($params->def('show_related_tags', false)) {
		
			// display related tags in cloud
			$percol = 8; // tags per col
			$font_size = 12; // normal font-size in px
			
			echo '<!-- start rel tag cloud -->';
			echo '<div class="contentheading">Related Tag Cloud</div>';
			echo '<ul class="rel_tagcloud">';
			if ($count = count($tags)) {
				$j = 0;
				$sep = '';
				for($i = 0; $i < $count; $i++) {
					$tag =& $tags[$i];
					$font = intval((($tag->weight/($count/2))*100)+100).'%';
					echo $sep;
					$sep = ', ';
					echo '<li><a href="'.sefRelToAbs('index.php?option=com_tag&tag='.$tag->tag.'&Itemid='.$Itemid).'" title="'.$tag->tag.'"><span class="tag" style="font-size:'.$font.';">'.$tag->tag.'</span></a></li>';
					if ($j == $percol) {
						$j = 1;
					} else {
						$j++;
					}		
				}
				echo '<li style="clear:both;"></li>';
			} else {
				echo 'No related tags.';
			}
			echo '</ul>';
			echo '<!-- end rel tag cloud -->';

		}
	}
	
}

?>