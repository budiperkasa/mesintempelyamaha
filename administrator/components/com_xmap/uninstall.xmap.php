<?php 

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' ); 

/**
 * Uninstall routine for Xmap.
 * Drops the settings table from the Joomla! database
 * @author Daniel Grothe
 * @see XmapConfig.php
 * @package Xmap_Admin
 * @version $Id: uninstall.xmap.php,v 0.1 2007/08/25 20:27:27 mic Exp $
 */
function com_uninstall() {
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/components/com_xmap/classes/XmapConfig.php' );
	XmapConfig::backup();
	XmapConfig::remove();
}