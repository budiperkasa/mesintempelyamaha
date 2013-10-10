<?php 
/**
 * $Id: xmap.php 25 2007-09-29 20:20:16Z root $
 * $LastChangedDate: 2007-09-29 14:20:16 -0600 (sáb, 29 sep 2007) $
 * $LastChangedBy: root $
 * Xmap by Guillermo Vargas
 * a sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

// load Xmap language file
global $mosConfig_absolute_path,$mosConfig_locale,$mosConfig_sef;

$LangPath = $mosConfig_absolute_path . '/administrator/components/com_xmap/language/';
if( file_exists( $LangPath . $GLOBALS['mosConfig_lang'] . '.php') ) {
	 require_once( $LangPath . $GLOBALS['mosConfig_lang'] . '.php' );
} else {
	 require_once( $LangPath . 'english.php' );
}

require_once( $mosConfig_absolute_path.'/administrator/components/com_xmap/classes/XmapConfig.php' );
require_once( $mosConfig_absolute_path.'/administrator/components/com_xmap/classes/XmapSitemap.php' );
require_once( $mosConfig_absolute_path.'/administrator/components/com_xmap/classes/XmapPlugins.php' );

$config = new XmapConfig;
$config->load();

$view = mosGetParam( $_REQUEST, 'view', 'html' );
$Itemid = intval(mosGetParam( $_REQUEST, 'Itemid', '' ));
$sitemapid =  '';

// Firts lets try to get the sitemap's id from the menu's params
if ( $Itemid ) {
	$menu = new mosMenu( $database );
	$menu->load( $Itemid );
	$params = new mosParameters( $menu->params );
	$sitemapid=intval($params->get( 'sitemap','' ));
}

if (!$sitemapid) { //If the is no sitemap id specificated
	$sitemapid = intval(mosGetParam($_REQUEST,'sitemap',''));
}

if ( !$sitemapid && $config->sitemap_default ) {
	$sitemapid = $config->sitemap_default;
}

$sitemap = new XmapSitemap();
$sitemap->load($sitemapid);

if (!$sitemap->id) {
	echo _XMAP_MSG_NO_SITEMAP;
	return;
}
if ( $view=='xml' ) {
	Header("Content-type: text/xml; charset=UTF-8");
	Header("Content-encoding: UTF-8");
}

global $xmap;
$xmap = new Xmap( $config, $sitemap );
require_once( $mosConfig_absolute_path.'/administrator/components/com_xmap/classes/XmapCache.php' );
$xmapCache = XmapCache::getCache($sitemap);
if ($sitemap->usecache) {
	$xmapCache->call('xmapCallShowSitemap',$view,$sitemap->id,$mosConfig_locale,$mosConfig_sef);	// call plugin's handler function
} else {
	xmapCallShowSitemap($view,$sitemap->id);
}

switch ($view) {
	case 'html':
		$sitemap->views_html++;
		$sitemap->lastvisit_html = time();
		$sitemap->save();
	break;
	case 'xml':
		$sitemap->views_xml++;
				$sitemap->lastvisit_xml = time();
		$sitemap->save();

		$scriptname = basename($_SERVER['SCRIPT_NAME']);
		$no_html = intval(mosGetParam($_REQUEST, 'no_html', '0'));
		if ($view=='xml' && $scriptname != 'index2.php' || $no_html != 1) {
				die();
		}
	break;
}

/**
* Function called to generate and generate the tree. Created specially to
* use with the cache call method
* The params locale and sef are only for cache purppses
*/
function xmapCallShowSitemap($view,$sitemapid,$locale='',$sef='') {
	XmapPlugins::loadPlugins();
	global $xmap,$xmapCache;
	$sitemap = &$xmap->sitemap;
	$config = &$xmap->sitemap;

	$tree = $xmap->generateTree($view,$xmapCache);
	//$xmap->printDebugTree( $tree );		// DEBUG output

	switch( $view ) {
		case 'xml': 	// XML Sitemaps output
			require_once( $GLOBALS['mosConfig_absolute_path'] .'/components/com_xmap/xmap.xml.php' );
			$count = XmapXML::printTree( $xmap, $sitemap,  $tree );
			$sitemap->count_xml = $count;
			break;
		default:	// Html output
			global $mainframe;
			require_once( $mainframe->getPath('front_html') );
			$mainframe->addCustomHeadTag( '<link rel="stylesheet" type="text/css" media="all" href="' . $GLOBALS['mosConfig_live_site'] . '/components/com_xmap/css/xmap.css" />' );
			$count = XmapHtml::printTree( $xmap, $tree );
			$sitemap->count_html = $count;
			break;
	}
}

/**
 * Generates a node-tree of all the Menus in Joomla!
 * This is the main class of the Xmap component.
 * @author Daniel Grothe
 * @access public
 */
class Xmap {
	/** @var XmapConfig Configuration settings */
	var $config;
	/** @var XmapSitemap Configuration settings */
	var $sitemap;
	/** @var integer The current user's access level */
	var $gid;
	/** @var boolean Is authentication disabled for this website? */
	var $noauth;
	/** @var string Current time as a ready to use SQL timeval */
	var $now;
	/** @var object Access restrictions for user */
	var $access;
	/** @var bool True if we are over Joomla 1.5 */
	var $isJ15;

	/** Default constructor, requires the config as parameter. */
	function Xmap( &$config, &$sitemap ) {
		global $acl, $my, $mainframe,$mosConfig_offset;

		$access = new stdClass();
		$access->canEdit	 = $acl->acl_check( 'action', 'edit', 'users', $my->usertype, 'content', 'all' );
		$access->canEditOwn = $acl->acl_check( 'action', 'edit', 'users', $my->usertype, 'content', 'own' );
		$access->canPublish = $acl->acl_check( 'action', 'publish', 'users', $my->usertype, 'content', 'all' );
		$this->access = &$access;

		$this->noauth 	= $mainframe->getCfg( 'shownoauth' );
		$this->gid	= $my->gid;
		$this->now	= (time() - ($mosConfig_offset * 60 * 60));
		$this->config = &$config;
		$this->sitemap = &$sitemap;
		$this->isJ15 = defined ('_JEXEC') && defined('JPATH_COMPONENT');
	}

	/** Generate a full website tree */
	function &generateTree($type,$cache) {
		$menus = $this->sitemap->getMenus();
		$root = array();
		foreach ( $menus as $menutype => $menu ) {
			if ( ($type == 'html' && !$menu->show) || ($type == 'xml' && !$menu->showXML ) ) {
				continue;
			}

			$node = new stdclass();

			$menu->menutype = $menutype;
			$node->ordering = $menu->ordering;
			$node->priority = $menu->priority;
			$node->changefreq = $menu->changefreq;
			$node->tree = $this->getMenuTree($menu,$cache);

			if( count($node->tree) == 0 )	// ignore empty menus
				continue;

			$node->browserNav = 3;
			$node->type = 'separator';
			$node->name = $this->getMenuTitle($menutype);	// get the mod_mainmenu title from modules table
			$root[] = $node;				// add the menu to the sitetree
		}
		usort($root, array('Xmap','sort_ordering'));		//sort the root tree according to ordering
		return $root;
	}

	/** Get a Menu's tree
	 * Get the complete list of menu entries where the menu is in $menutype.
	 * If the component, that is linked to the menuentry, has a registered handler,
	 * this function will call the handler routine and add the complete tree.
	 * A tree with subtrees for each menuentry is returned.
	 */
	function &getMenuTree( &$menu, &$cache) {
		global $database;

		if( strlen($menu->menutype) == 0 ) {
			$result = null;
			return $result;
		}

		$menuExluded	= explode( ',', $this->sitemap->exclmenus ); 		// by mic: fill array with excluded menu IDs
		// echo '<br />[DEBUG excluded menus] ' . $this->sitemap->exclmenus . '<br />';

		/* * noauth is true:
			- Will show links to registered content, even if the client is not logged in.
			- The user will need to login to see the item in full.
			* noauth is false:
			- Will show only links to content for which the logged in client has access.
		*/
		$sql = "SELECT m.id, m.name, m.parent, m.link, m.type, m.browserNav, m.menutype, m.ordering, m.params, m.componentid, c.name AS component"
	 . "\n FROM #__menu AS m"
	 . "\n LEFT JOIN #__components AS c ON m.type='components' AND c.id=m.componentid"
	 . "\n WHERE m.published='1' AND m.menutype = '".$menu->menutype."'"
	 . ( $this->noauth ? '' : "\n AND m.access <= '". $this->gid ."'" )
	 . "\n ORDER BY m.menutype,m.parent,m.ordering";

		// Load all menuentries
		$database->setQuery( $sql );
		$items = $database->loadObjectList();

		if( count($items) <= 0) {	//ignore empty menus
			$result = null;
			return $result;
		}
		
		$root = array();

		foreach($items as $i => $item) {								// Add each menu entry to the root tree.
			$item->priority = $menu->priority;
			$item->changefreq = $menu->changefreq;
			if( in_array( $item->id, $menuExluded ) ) {						// ignore exluded menu-items
				continue;
			}

			$node = new stdclass;
			$node->tree 		= XmapPlugins::getTree( $this, $item, $cache  );		// Determine the menu entry's type and call it's handler

			$node->id 		= $item->id;
			$node->name 		= $item->name;							// displayed name of node
			$node->parent 		= $item->parent;						// id of parent node
			$node->browserNav 	= $item->browserNav;						// how to open link
			$node->ordering 	= isset( $item->ordering ) ? $item->ordering : $i;		// display-order of the menuentry
			$node->priority 	= $item->priority;
			$node->changefreq 	= $item->changefreq;
			if ( $this->isJ15 && substr($item->link,0,9) == 'index.php' ) {
				$node->link 	= 'index.php?Itemid=' . $node->id;				// For Joomla 1.5 SEF compatibility
			} else {
				$node->link 	= isset( $item->link ) ? htmlspecialchars( $item->link ) : '';	// convert link to valid xml
			}
			$node->type 		= $item->type;							// menuentry-type
			if( isset($item->modified) )								// getTree() might have added a modified date
				$node->modified = $item->modified;

			$root[ $node->id ] 	= $node;							//add this node to the root tree
		}

		foreach($root as $node) {									//move children into the tree of their parent
			if( $node->parent > 0 && isset($root[ $node->parent ]) ) {
				$root[ $node->parent ]->tree[] = &$root[ $node->id ];
			}
		}

		foreach($root as $node) {					//remove all children from the toptree and also empty separators
			if( $node->parent > 0 || ($node->type=='separator' && !count($node->tree))) {
				unset( $root[ $node->id ] );
			}
		}

		usort($root, array('Xmap','sort_ordering'));						//sort the top tree according to ordering

		return $root;
	}

	/** Look up the title for the module that links to $menutype */
	function getMenuTitle($menutype) {
		global $database;
		$query = "SELECT * FROM #__modules WHERE published='1' AND module='mod_mainmenu' AND params LIKE '%menutype=". $menutype ."%'";
		$database->setQuery( $query );
		if( !$database->loadObject($row) )
			return '';
		return $row->title;
	}

	function getItemLink (&$node) {
		global $mosConfig_live_site;

		$link = $node->link;
		if ( isset($node->id) ) {
			switch( @$node->type ) {
				case 'separator':
					break;
				case 'url':
					if ( preg_match( "#^/?index\.php\?#", $link ) ) {
						if ( strpos( $link, 'Itemid=') === FALSE ) {
							if (strpos( $link, '?') === FALSE ) {
								$link .= '?Itemid='.$node->id;
							} else {
								$link .= '&amp;Itemid='.$node->id;
							}
						}
					}
					break;
				default:
					if ( strpos( $link, 'Itemid=' ) === FALSE ) {
						$link .= '&amp;Itemid='.$node->id;
					}
					break;
			}
		}
		if( strcasecmp( substr( $link, 0, 4), 'http' ) ){
			if (strcasecmp( substr( $link, 0, 9), 'index.php' ) === 0 ){
				$link = sefRelToAbs($link);             // apply SEF transformation
				if( strcasecmp( substr($link,0,4), 'http' ) ) {       // fix broken sefRelToAbs()
					$link = $mosConfig_live_site. (substr($link,0,1) == '/'? '' : '/').$link;
				}
			} else { // Case for internal links not starting with index.php
				$link = $mosConfig_live_site. '/' .$link;
			}
		}

		return $link;
	}

	/** Print tree details for debugging and testing */
	function printDebugTree( &$tree ) {
		foreach( $tree as $menu) {
			echo $menu->name."<br />\n";
			echo '<pre>';
			print_r( $menu->tree );
			echo '</pre>';
		}
	}

	/** called with usort to sort menus */
	function sort_ordering( &$a, &$b) {
		if( $a->ordering == $b->ordering )
			return 0;
		return $a->ordering < $b->ordering ? -1 : 1;
	}
}

