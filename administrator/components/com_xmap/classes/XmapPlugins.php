<?php 
/**
 * The Xmap Plugin Manager
 * @author Daniel Grothe
 * @package Xmap
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/** Wraps all plugin functions for Xmap */
class XmapPlugins {
	
	/** list all plugin files found in the plugins directory */
	function listPlugins( ) {
		$list = array();

		$pathname = $GLOBALS['mosConfig_absolute_path'].'/administrator/components/com_xmap/plugins/';
		if ( is_dir($pathname) ) {
			$dir_handle = opendir($pathname);
			if( $dir_handle ) {
				while (($filename = readdir($dir_handle)) !== false) {
					if( substr( $filename, -11 ) == '.plugin.php' ) {
						$list[] = $filename;
					}
				}
				closedir($dir_handle);
			}
		}
		return $list;
	}
	
	/** include() all plugin files */
	function loadPlugins() {
		$pathname = $GLOBALS['mosConfig_absolute_path'].'/administrator/components/com_xmap/plugins/';
		$plugins = XmapPlugins::listPlugins();
		foreach( $plugins as $plugin ) {
			include_once( $pathname.$plugin );
		}
	}
	
	/** Add class to list of registered plugins */
	function addPlugin( &$plugin ) {
		global $xmap_plugins;
		
		if( !is_object($plugin) )
			return;								// check if parameter is valid

		if( !method_exists($plugin, 'isOfType')	|| !method_exists($plugin, 'getTree') )
			return;								// check if object implements required functions
		
		$xmap_plugins[] = $plugin;
	}

	/** Determine which plugin-object handles this content and let it generate a tree */
	function &getTree( &$xmap, &$parent, &$cache ) {
		$result = null;

		global $xmap_plugins,$xmapCurrentParent,$xmapCurrentXmap;
		for ( $i=0; $i< count($xmap_plugins); $i++) {
			if( $xmap_plugins[$i]->isOfType($xmap, $parent) ) {	// check if plugin handles this kind of content
				$xmapCurrentParent = $parent;
				$xmapCurrentXmap = $xmap;
				# $result = &$cache->call('xmapCallGetTree',$i,get_class($xmap_plugins[$i]), $parent->id);	// call plugin's handler function
				$result = xmapCallGetTree($i,get_class($xmap_plugins[$i]), $parent->id);	// call plugin's handler function
				break;
			}
		}
		return $result;
	}
}

function xmapCallGetTree($pluginIndex,$pluginName,$parentid) {
	global $xmap_plugins,$xmapCurrentParent,$xmapCurrentXmap;
	return $xmap_plugins[$pluginIndex]->getTree($xmapCurrentXmap,$xmapCurrentParent);
}