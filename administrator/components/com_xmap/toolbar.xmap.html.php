<?php
	/**
	 * The Xmap component administrator toolbar
	 * @author Daniel Grothe
	 * @see admin.xmap.php
	 * @package Xmap
	 */
	 
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/** Administrator Toolbar output */
class TOOLBAR_xmap {
	/**
	* Draws the toolbar
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		/*
			//Testing
			mosMenuBar::custom('backup', 'archive.png', 'archive_f2.png', "Backup Settings", false);
			mosMenuBar::custom('restore', 'restore.png', 'restore_f2.png', "Restore Settings", false);
			mosMenuBar::spacer();
		*/
		if ( defined('_JEXEC') ) {
			JToolBarHelper::title( 'Xmap', 'addedit.png' );
		}
		mosMenuBar::save('save', _XMAP_TOOLBAR_SAVE);
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel', _XMAP_TOOLBAR_CANCEL);
		mosMenuBar::endTable();
	}
}
