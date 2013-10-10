<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/* JSON Template */
class com_tag_tmpl_rss20 extends HTML_tag {

	/* constructor */
	function com_tag_tmpl_rss20() {
		// xml http header
		header('Content-Type: text/xml');
	}
	
	// wrap in <resp>
	function Resp($xml) {
		return '<resp>'.$xml.'</resp>';
	}

	// generic error function
	function Error($msg) {
		echo $this->Resp('<error>'.$msg.'</error>');
	}
	
	function _removeMambots($str) { //return $str;
		//$str = preg_replace("/{.+?}[^{}]*?{\/.+?}/is", '', $str);
		return preg_replace("/{.+?}/i", '', $str);
	}

	
	// list the tagged content
	function listTagged($tag, &$rows, &$pageNav, &$params, &$tags) {
		global $mainframe, $Itemid;
		
		error_reporting(E_ERROR);
	
		// header
		$header = 'Articles Tagged:  '.$tag;
		$date = date(DATE_RFC822);
		$generator = 'Joomla Tags Component (c) www.fijiwebdesign.com';
		
		echo '<'.'?xml version="1.0" encoding="utf-8"?>'."\r\n";
		echo '<!-- generator="'.$generator.'" -->';
?>

<rss version="2.0">
	<channel>
		<title><?php echo $header; ?></title>
		<description><?php echo $GLOBALS['mosConfig_sitetitle']; ?></description>
		<link><?php echo $GLOBALS['mosConfig_live_site']; ?></link>
		<lastBuildDate><?php echo $date; ?></lastBuildDate>

		<generator><?php echo $generator; ?></generator>
		<image>
			<url><?php echo $GLOBALS['mosConfig_live_site']; ?>/images/M_images/joomla_rss.png</url>
			<title><?php echo $GLOBALS['mosConfig_sitetitle']; ?></title>
			<link><?php echo $GLOBALS['mosConfig_live_site']; ?></link>
			<description><?php echo $header; ?></description>

		</image>
		
		<?php 
		
		$count = is_array($rows) ? count($rows) : 0;
		if ($count > 0) {
			for($i = 0; $i < $count; $i++) {
				$row =& $rows[$i];
				$row->text = $row->introtext;
				
				// display/hide full text
				if ($params->def('rss_show_fulltext', false)) {
					$row->text .= $row->fulltext;
				}
				
				// render/remove mambots
				if ($params->def('rss_show_fulltext', false)) {
					list($after_title, $before_content, $row, $after_content) = $this->_callPlugins($row);
				} else {
					$row->text = $this->_removeMambots($row->text);
				}
				
				?>
				
			<item>
			<title><?php echo $row->title; ?></title>
			<link><?php echo str_replace('&', '&amp;', sefRelToAbs('index.php?option=com_content&task=view&id='.$row->id)); ?></link>
			<description><![CDATA[<?php echo $row->text; ?>]]></description>
			<category>Tags</category>

			<pubDate>Sat, 12 Jun 2004 11:54:06 +0100</pubDate>
			</item>
		
		<?php
		
			}
		}
?>
</channel>
</rss>	
<?php
		
	}
	
}

?>