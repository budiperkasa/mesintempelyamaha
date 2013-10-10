<?php
/**
* @package		JoomlaPack
* @copyright	Copyright (C) 2006-2008 JoomlaPack Developers. All rights reserved.
* @version		1.1.1b2
* @license 		http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* JoomlaPack is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
**/

// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

global $JPLang;

class jpackScreens {
	function fConfig() {
		require_once( JPComponentRoot . "/includes/fConfig.php" );
	}

	function fPack() {
		require_once( JPComponentRoot . "/includes/fPack.php" );
	}

	function fMain() {
		require_once( JPComponentRoot . "/includes/fMain.php" );
	}

	function fBUAdmin() {
		require_once( JPComponentRoot . "/includes/fBUAdmin.php" );
	}

	function fDirExclusion() {
		require_once( JPComponentRoot . "/includes/fDirExclusion.php" );
	}

	function fLog() {
		require_once( JPComponentRoot . "/includes/fLog.php" );
	}

	function fDebug() {
		require_once( JPComponentRoot . "/includes/fDebug.php" );
	}
	
	function fUnlock()
	{
		require_once( JPComponentRoot . "/includes/fUnlock.php" );
	}

	function CommonFooter() {
		global $option, $JPLang;
	?>
		<p>
			[
			<a href="index2.php?option=<?php echo $option; ?>"><?php echo $JPLang['cpanel']['home']; ?></a>
			]
			<br />
			<span style="font-size:x-small;">
			JoomlaPack <?php echo _JP_VERSION; ?>. Copyright &copy; 2006-2007 <a href="http://www.joomlapack.net">JoomlaPack Developers</a>.<br/>
			<a href="http://www.joomlapack.net">JoomlaPack</a> is Free Software released under the GNU/GPL License.
			</span>
		</p>
	<?php
	}
}
?>