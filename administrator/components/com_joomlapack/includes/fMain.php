<?php
/**
 * Application Pages :: Main page (status and instructions)
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

global $JPConfiguration, $JPLang, $option;

// Get location and writable status of output directory and temporary folder
$WSOutdir = $JPConfiguration->isOutputWriteable();
$WSTemp = $JPConfiguration->isTempWriteable();

$appStatusGood = true;
if (!($WSOutdir && $WSTemp)) {
	$appStatusGood = false;
}

?>
<table class="adminheading">
	<tr>
		<th class="cpanel" nowrap rowspan="2">
			<?php 
			if (!defined('_JEXEC')) {
			echo $JPLang['common']['jptitle']; 
			}
			?>
		</th>
	</tr>
</table>
<table class="adminform">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
<?php
				$link = "index2.php?option=$option&act=config";
				JP_quickiconButton( $link, 'config.png', $JPLang['cpanel']['config'] );
				$link = "index2.php?option=$option&act=def";
				JP_quickiconButton( $link, 'folder_add_f2.png', "<br />" . $JPLang['cpanel']['def'] );
				$link = "index2.php?option=$option&act=pack";
				JP_quickiconButton( $link, 'backup.png', $JPLang['cpanel']['pack'] );
				$link = "index2.php?option=$option&act=backupadmin";
				JP_quickiconButton( $link, 'archive_f2.png', "<br/>" . $JPLang['cpanel']['buadmin'] );
				$link = "index2.php?option=$option&act=log";
				JP_quickiconButton( $link, 'note_f2.png', "<br/>" . $JPLang['cpanel']['log'] );
				$link = "index2.php?option=$option&act=unlock";
				JP_quickiconButton( $link, 'reload_f2.png', "<br/>" . $JPLang['cpanel']['unlock'] );
?>
			</div>
			<div style="clear:both;"> </div>
		</td>
		<td width="45%" valign="top">
<?php
			$tabs = new mosTabs(1);
			$tabs->startPane(1);
			$tabs->startTab($JPLang['main']['overview'],'jpstatusov');
?>
				<p class="sanityCheck"><?php echo $JPLang['main']['status'] . ": " . colorizeAppStatus( $appStatusGood ); ?></p>
<?php
			$tabs->endTab();
			$tabs->startTab($JPLang['main']['details'],'jpstatusdet');
?>
				<table align="center" border="1" cellspacing="0" cellpadding="5" class="adminlist">
					<thead>
						<tr>
							<th class="title"><?php echo $JPLang['main']['item']; ?></th>
							<th><?php echo $JPLang['main']['status']; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $JPLang['common']['tempdir']; ?></td>
							<td><?php echo colorizeWriteStatus($WSTemp, true); ?></td>
						</tr>
						<tr>
							<td><?php echo $JPLang['common']['outdir']; ?></td>
							<td><?php echo colorizeWriteStatus($WSOutdir, true); ?></td>
						</tr>
						<tr>
							<td><?php echo $JPLang['common']['version_check']; ?></td>
							<td><?php checkAppStatusV(1) ?></td>
						</tr>
					</tbody>
				</table>
<?php
			$tabs->endTab();
			$tabs->endPane();
?>
		</td>
	</tr>
</table>

<?php

/**
	Colorizes (red/green) the writable status of various components
*/
function colorizeWriteStatus( $status, $okstatus ) {
	global $JPLang;

	$statusVerbal = $status ? $JPLang['common']['writable'] : $JPLang['common']['unwritable'];
	if ( $status == $okstatus ) {
		return '<span class="statusok">' . $statusVerbal . '</span>';
	} else {
		return '<span class="statusnotok">' . $statusVerbal . '</span>';
	}
}

/* Get last run date.
      $path = "docs/";
      // show the most recent file
      echo "Most recent file is: ".getNewestFN($path);
      // Returns the name of the newest file
      // (My_name YYYY-MM-DD HHMMSS.inf)
      function getNewestFN ($path) {
        // store all .inf names in array
        $p = opendir($path);
        while (false !== ($file = readdir($p))) {
          if (strstr($file,".zip"))
            $list[]=date("YmdHis ", filemtime($path.$file)).$path.$file;
        }
        // sort array descending
        rsort($list);
        // return newest file name   
        return $list[0];
      }
*/

/**
	Colorizes (red/green) the overall application status
*/
function colorizeAppStatus( $status ) {
	global $JPLang;

	$statusVerbal = $status ? $JPLang['main']['appgood'] : $JPLang['main']['appnotgood'];
	if ( $status ) {
		return '<span class="statusok">' . $statusVerbal . '</span>';
	} else {
		return '<span class="statusnotok">' . $statusVerbal . '</span>';
	}
}

function checkAppStatusV ($app_status) {
	if ($app_status == 1) {
	$vcheck = urlencode(base64_encode(_JP_VERSION));
	//echo $vcheck;  //debug
	echo "<script type=\"text/javascript\" src=\"http://www.joomlapack.net/version.php?vid=2&verx=$vcheck\"></script>";
	}
}

/**
	Creates one of those cool cpanel kind of icons
*/
function JP_quickiconButton( $link, $image, $text ) {
	?>
	<div style="float:left;">
		<div class="icon">
			<a href="<?php echo $link; ?>">
				<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
				<span><?php echo $text; ?></span>
			</a>
		</div>
	</div>
	<?php
}
?>
