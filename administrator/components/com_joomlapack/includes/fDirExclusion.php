<?php

/**
 * Application Pages :: Directory Exclusion Filter adinistration
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is distributed subject to the GNU General
 * Public Licence (GPL) version 2 or later.
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the GNU GPL and are unable to obtain it through the web,
 * please send a note to nikosdion@gmail.com so we can mail you a copy immediately.
 *
 * Visit www.JoomlaPack.net for more details.
 *
 * @package    JoomlaPack
 * @Author     Nicholas K. Dionysopoulos nikosdion@gmail.com
 * @copyright  2006-2007 Nicholas K. Dionysopoulos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    $Id$
*/

// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_live_site, $JPLang;
require_once(JPComponentRoot . "/includes/sajax.php");
require_once(JPComponentRoot . "/includes/ajaxtool.php");

// 1.1.1b2 - Oops! This page wouldn't work on at least 1 windows system
$myRoot = JPSiteRoot;
$rootArray = explode('\\', $myRoot);
$myRoot = implode('//', $rootArray);

?>
<script language="javascript" src="<?php echo $mosConfig_live_site; ?>/administrator/components/<?php echo $option; ?>/js/xp_progress.js">
	// WinXP Progress Bar- By Brian Gosselin- http://www.scriptasylum.com/
</script>
<script language="JavaScript" type="text/javascript">
	/*
	 * (S)AJAX Library code
	 */
<?php
	sajax_show_javascript();
?>
	var globRoot;

	function ToggleFilter( myRoot, myDir, myID ) {
		var sCheckStatus = (document.getElementById(myID).checked == true) ? "on" : "off";

		globRoot = myRoot;

		document.getElementById("DEFScreen").style.display = "none";
		document.getElementById("DEFProgressBar").style.display = "block";

		x_toggleDirFilter( myRoot, myDir, sCheckStatus, ToggleFilter_cb );
	}

	function ToggleFilter_cb( myRet ) {
		dirSelectionHTML( globRoot );
		document.getElementById("DEFScreen").style.display = "block";
		document.getElementById("DEFProgressBar").style.display = "none";
	}

	function dirSelectionHTML( myRoot ) {
		globRoot = myRoot;
		x_dirSelectionHTML( myRoot, cb_dirSelectionHTML );
	}

	function cb_dirSelectionHTML( myRet ) {
		document.getElementById("DEFScreen").style.display = "block";
		document.getElementById("DEFProgressBar").style.display = "none";
		document.getElementById("DEFOperationList").innerHTML = myRet;
	}
</script>

<div id="DEFProgressBar" style="display:none;" class="sitePack">
	<h4>Please wait...</h4>
	<script type="text/javascript">
		var bar0 = createBar(320,15,'white',1,'black','blue',85,7,3,"");
	</script>
</div>

<div id="DEFScreen">
	<table class="adminheading">
		<tr>
			<th class="info" nowrap rowspan="2">
				<?php echo $JPLang['common']['jptitle']; ?>
			</th>
		</tr>
		<tr>
			<td nowrap><h2><?php echo $JPLang['cpanel']['def']; ?></h2></td>
		</tr>
	</table>

	<div id="DEFOperationList">
		<script type="text/javascript">
			dirSelectionHTML('<?php echo $myRoot; ?>');
		</script>
	</div>
</div>