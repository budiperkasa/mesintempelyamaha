<?php
/**
 * $Id: xmap.xml.php 26 2007-09-29 20:20:56Z root $
 * $LastChangedDate: 2007-09-29 14:20:56 -0600 (sáb, 29 sep 2007) $
 * $LastChangedBy: root $
 * Xmap by Guillermo Vargas
 * A Sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.'); 

/** Wraps XML Sitemaps output */
class XmapXML {

	/** Convert sitemap tree to a XML Sitemap list */
	function &getList( &$tree, $priority = '',$changefreq = '', &$count ) {
		global $Itemid, $mosConfig_live_site, $_xmap_xml_added;
		$out = '';
		if( !$tree )
			return $out;

		$len_live_site = strlen( $mosConfig_live_site );
		foreach($tree as $node) {
			$link = Xmap::getItemLink($node);
			if( strcasecmp( substr($link, 0, 5), 'http:' ) != 0 ) {
				if( strncmp($link, '/', 1) === 0) {	// removes the slash again, when live_site URL is empty
					$link = $mosConfig_live_site.$link;
				}
			}
			// $link = preg_replace('/\&(amp;)?/','&amp;',$link); //Fix form non xml compatible links
			$link = XmapXML::escapeURL($link);
			$is_extern = ( 0 != strcasecmp( substr($link, 0, $len_live_site), $mosConfig_live_site ) );

			if( !isset($node->browserNav) )
				$node->browserNav = 0;

			if( !isset($node->priority) )
				$node->priority = $priority;

			if( !isset($node->changefreq) )
				$node->changefreq= $changefreq;


			if ( $node->browserNav != 3			// ignore "no link"
			 && !$is_extern					// ignore external links
			 && !in_array($link, $_xmap_xml_added) ) {	// ignore links that have been added already

				$count++;
			 	$_xmap_xml_added[] = $link;

				$out .= "<url>\n";
				$out .= "<loc>". $link ."</loc>\n";	// http://complete-url
				$timestamp = (isset($node->modified) && $node->modified != FALSE && $node->modified != -1) ? $node->modified : time();
				$modified = gmdate('Y-m-d\TH:i:s\Z', $timestamp);		// ISO 8601 yyyy-mm-ddThh:mm:ss.sTZD
				$out .= "<lastmod>{$modified}</lastmod>\n";
	   			
	   			$out .= "<changefreq>".$node->changefreq."</changefreq>\n";				// valid: always, hourly, daily, weekly, monthly, yearly, never
				$out .= "<priority>".$node->priority."</priority>\n";						// valid: 0.0 - 1.0
				
	 			$out .= "</url>\n";
			}
			
			if( isset($node->tree) ) {
				$out .= XmapXML::getList( $node->tree,$priority,$changefreq,$count );
			}
		}
		return $out;
	}
	
	/** Print a XML Sitemaps representation of tree */
	function printTree( &$xmap,&$sitemap, &$root ) {
		global  $mosConfig_live_site;
		@ob_end_clean();
		$GLOBALS['_xmap_xml_added'] = array();
		echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		echo '<?xml-stylesheet type="text/xsl" href="'. $mosConfig_live_site.'/components/com_xmap/gss.xsl"?>'."\n";

		echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

		$tmp = array();
		$count=0;
		foreach( $root as $menu ) {											// concatenate all menu-trees
			echo XmapXML::getList( $menu->tree, $menu->priority,$menu->changefreq,$count );
		}
		echo "</urlset>\n";
		return $count;
	}

	function escapeURL($str) {
		static $xTrans;
		if (!isset($xTrans)) {
		$xTrans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
		foreach ($xTrans as $key => $value)
			$xTrans[$key] = '&#'.ord($key).';';
			// dont translate the '&' in case it is part of &xxx;
			$xTrans[chr(38)] = '&';
		}
		return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,4};)/","&amp;" , strtr($str, $xTrans));
        }
}