<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

/* html template */
class com_tag_tmpl_html extends HTML_tag {
	
	// response when tags added successfully
	function addTags($cid, $tags) {
		echo 'added tags to '.$cid;
	}
	
	// generic error function
	function Error($msg, $class = 'error') {
		echo '<div class="'.$class.'">'.$msg.'</div>';
	}
	
	// list the tagged content
	function listTagged($tag, &$rows, &$pageNav, &$params, &$tags) {
		global $mainframe, $Itemid;
		
		$page_title = 'Aticles Tagged: '.$tag;
		$mainframe->setPageTitle($page_title);
	
		// header
		$header = 'Articles Tagged:  <i>'.$tag.'</i>';
		$rss_link = sefRelToAbs('index2.php?option=com_tag&amp;tag='.$tag.'&amp;f=rss20&amp;no_html=1');
		echo '<h1 class="componentheading'. $params->get( 'pageclass_sfx' ) .'">'. $header .'</h1>';
		echo '<div class="tag_rsslink"><a href="'.$rss_link.'">Subscribe</a></div>';
		// add rss link to autodiscovery
		$rss_discovery = '<link rel="alternate" type="application/rss+xml" title="'.$page_title.'" href="'.$rss_link.'" />';
		$mainframe->addCustomHeadTag($rss_discovery);
	
		// display articles
		$count = is_array($rows) ? count($rows) : 0;
		echo '<div class="tagged">';
		if ($count > 0) {
			for($i = 0; $i < $count; $i++) {
				$row =& $rows[$i];
				$row->text = $row->introtext; //.$row->fulltext;
				// render mambots
				list($after_title, $before_content, $row, $after_content) = $this->_callPlugins($row);
				echo '<h2 class="contentheading"><a href="'.sefRelToAbs('index.php?option=com_content&task=view&id='.$row->id).'" title="'.$row->title.'">'.$row->title.'</a></h2>';
				echo '<div class="after_title">'.$after_title.'</div>';
				echo '<div class="before_content">'.$before_content.'</div>';
				echo '<div class="content">'.$row->text.'</div>';
				echo '<div class="before_content">'.$after_content.'</div>';
			}
		} else {
			echo '<div class="error">No Content Found.</div>';
		}
		echo '</div>';
		
		if ($params->def('show_related_tags', false)) {
		
			// display related tags in cloud
			$percol = 8; // tags per col
			$font_size = 12; // normal font-size in px
			
			echo '<!-- start rel tag cloud -->';
			echo '<h3 class="contentheading">Related Tag Cloud</h3>';
			echo '<div class="rel_tagcloud">';
			if ($count = count($tags)) {
				$j = 0;
				$sep = '';
				for($i = 0; $i < $count; $i++) {
					$tag =& $tags[$i];
					$font = intval((($tag->weight/($count/2))*100)+100).'%';
					echo $sep;
					$sep = ', ';
					echo '<a href="'.sefRelToAbs('index.php?option=com_tag&tag='.$tag->tag.'&Itemid='.$Itemid).'" title="'.$tag->tag.'"><span class="tag" style="font-size:'.$font.';">'.$tag->tag.'</span></a>';
					if ($j == $percol) {
						$j = 1;
					} else {
						$j++;
					}		
				}
				echo '<div style="clear:both;"></div>';
			} else {
				echo 'No related tags.';
			}
			echo '</div>';
			echo '<!-- end rel tag cloud -->';
		
		}
		
		// page navigation
		echo '<div class="pagenav">';
			echo '<div class="links">';
			echo $pageNav->writePagesLinks('index.php?option=com_tag&task=view&act=tagged');
			echo '</div>';
			echo '<div class="total">';
			echo $pageNav->writePagesCounter();
			echo '</div>';
		echo '</div>';
	}
	
	// displays the tag cloud
	function cloud(&$tags, &$titles, &$params) {
		global $mainframe, $Itemid, $mosConfig_live_site;
		
		// load CSS
		$mainframe->addCustomHeadTag('<link type="text/css" rel="stylesheet" href="'.$mosConfig_live_site.'/components/com_tag/css/tag.global.css" />');
		// load JS
		$mainframe->addCustomHeadTag('<script type="text/javascript" src="'.sefRelToAbs('index2.php?option=com_tag&task=external&act=js&no_html=1&Itemid='.$Itemid).'"></script>');
		
		// header
		$header = 'Tag Cloud';
		if ($count = count($titles)) {
			$i = 1;
			$header .= ' : ';
			$titles = array_reverse($titles);
			foreach($titles as $pre=>$row) {
				$id = $row['id'];
				$title = $row['title'];
				if ($pre == 'a') {
					$header .= '<a href="'.sefRelToAbs('index.php?option=com_content&task=view&id='.$id).'">'.$title.'</a>';
				} else if ($pre == 'c') {
					$header .= '<a href="'.sefRelToAbs('index.php?option=com_content&task=blogcategory&id='.$id).'">'.$title.'</a>';
				} else if ($pre == 's') {
					$header .= '<a href="'.sefRelToAbs('index.php?option=com_content&task=blogsection&id='.$id).'">'.$title.'</a>';
				}
				if ($count > $i) {
					$header .= ' >> ';
				}
				$i++;
			}
		} else {
			$header .= ' : All Content';
		}
		$mainframe->setPageTitle($header);
		echo '<h1 class="componentheading'. $params->get( 'pageclass_sfx' ) .'">'. $header .'</h1>';

		echo '<!-- start tag cloud -->';
		echo '<div class="tag_cloud" id="com_tag_cloud">';
		
		//var_dump($tags);
		
		if ($count = count($tags)) {

			// get largest weight weights
			$weight_limit = 80; // max 180% font
			$weights = array(1);
			for($i = 0; $i < $count; $i++) {
				$weights[] = $tags[$i]->weight;
			}
			$max_weight = max($weights);
			$weight_part = $weight_limit/($max_weight);
			
			$sep = '';
			for($i = 0; $i < $count; $i++) {
				$tag =& $tags[$i];

				$font = intval($tag->weight*$weight_part + 100 ).'%';
				//var_dump($font);
				echo $sep.'<a rel="tag" href="'.sefRelToAbs('index.php?option=com_tag&tag='.$tag->tag.'&Itemid='.$Itemid).'"><span class="tag" style="font-size:'.$font.';">'.$tag->tag.'</span></a>';
				$sep = ', ';
	
			}

		} else {
			echo '<div class="error">No Tags Yet.</div>';
		}
		echo '</div>';
		echo '<!-- end tag cloud -->';
	}
}

?>