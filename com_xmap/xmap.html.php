<?php
/**
 * $Id: xmap.html.php 26 2007-09-29 20:20:56Z root $
 * $LastChangedDate: 2007-09-29 14:20:56 -0600 (sáb, 29 sep 2007) $
 * $LastChangedBy: root $
 * Xmap by Guillermo Vargas
 * A Sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/** Wraps HTML output */
class XmapHtml {

	/** Convert sitemap tree to an 'unordered' html list.
	 * This function uses recursion, keep unnecessary code out of this!
	 */
	function &getHtmlList( &$tree, &$exlink, $level = 0,&$count ) {
		global $Itemid;

		if( !$tree ) {
			$result = '';
			return $result;
		}

		$out = '<ul class="level_'.$level.'">';
		foreach($tree as $node) {
			$count++;
			if ( $Itemid == $node->id )
				$out .= '<li class="active">';
			else
				$out .= '<li>';

			$link = Xmap::getItemLink($node);;

			if( !isset($node->browserNav) )
				$node->browserNav = 0;

			switch( $node->browserNav ) {
				case 1:		// open url in new window
					$ext_image = '';
					if( $exlink[0] ){
						$ext_image = '&nbsp;<img src="'. $GLOBALS['mosConfig_live_site'] .'/components/com_xmap/images/'. $exlink[1] .'" alt="' . _XMAP_SHOW_AS_EXTERN_ALT . '" title="' . _XMAP_SHOW_AS_EXTERN_ALT . '" border="0" />';
					}
					$out .= '<a href="'. $link .'" title="'. $node->name .'" target="_blank">'. $node->name . $ext_image .'</a>';
					break;

				case 2:		// open url in javascript popup window
					$ext_image = '';
					if( $exlink[0] ) {
						$ext_image = '&nbsp;<img src="'. $GLOBALS['mosConfig_live_site'] .'/components/com_xmap/images/'. $exlink[1] .'" alt="' . _XMAP_SHOW_AS_EXTERN_ALT . '" title="' . _XMAP_SHOW_AS_EXTERN_ALT . '" border="0" />';
					}
					$out .= '<a href="'. $link .'" title="'. $node->name .'" target="_blank" '. "onClick=\"javascript: window.open('". $link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false;\">". $node->name . $ext_image."</a>";
					break;

				case 3:		// no link
					$out .= '<span>'. $node->name .'</span>';
					break;

				default:	// open url in parent window
					$out .= '<a href="'. $link .'" title="'. $node->name .'">'. $node->name .'</a>';
					break;
			}

			if( isset($node->tree) ) {
				$out .= XmapHtml::getHtmlList( $node->tree, $exlink, $level + 1,$count );
			}
			$out .= '</li>' . "\n";
		}
		$out .= '</ul>' . "\n";
		return $out;
	}

	/** Print component heading, etc. Then call getHtmlList() to print list */
	function printTree( &$xmap, &$root ) {
		global $database, $Itemid;
		$config = &$xmap->config;
		$sitemap = &$xmap->sitemap;
	
		$menu = new mosMenu( $database );
		$menu->load( $Itemid );												// Load params for the Xmap menu-item
		$title = $menu->name;
		
		$exlink[0] = $sitemap->exlinks;										// image to mark popup links
		$exlink[1] = $sitemap->ext_image;

		if( $sitemap->columns > 1 ) {										// calculate column widths
			$total = count($root);
			$columns = $total < $sitemap->columns ? $total : $sitemap->columns;
			$width	= (100 / $columns) - 1;
		}
		echo '<div class="'. $sitemap->classname .'">';
		echo '<div class="componentheading">'.$title.'</div>';
		echo '<div class="contentpaneopen"'. ($sitemap->columns > 1 ? ' style="float:left;width:100%;"' : '') .'>';
		
		$count=0;
		if( $sitemap->show_menutitle || $sitemap->columns > 1 ) {				// each menu gets a separate list
			foreach( $root as $menu ) {
				
				if( $sitemap->columns > 1 )									// use columns
					echo '<div style="float:left;width:'.$width.'%;">';
				
				if( $sitemap->show_menutitle )								// show menu titles
					echo '<h2 class="menutitle">'.$menu->name.'</h2>';

				echo XmapHtml::getHtmlList( $menu->tree, $exlink,0,$count );
				if( $sitemap->columns > 1 )
					echo "</div>\n";
			}

			if( $sitemap->columns > 1 )
				echo '<div style="clear:left"></div>';

		} else {															// don't show menu titles, all items in one big tree
			$tmp = array();
			foreach( $root as $menu ) {										// concatenate all menu-trees
				foreach( $menu->tree as $node ) {
					$tmp[] = $node;
				}
			}
			echo XmapHtml::getHtmlList( $tmp, $exlink,0,$count );
		}
		
		//BEGIN: Advertisement
		if( $sitemap->includelink ) {
			echo "<p style=\"text-align:center;\">Powered by <a href=\"http://joomla.vargas.co.cr\" target=\"_blank\">Xmap!</a></p>";
		}
		//END: Advertisement
		
		echo "</div>";
		echo "</div>\n";

		return $count;

	}
}
