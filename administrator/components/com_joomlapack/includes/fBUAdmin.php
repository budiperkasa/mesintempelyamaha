<?php
/**
 * Application Pages :: Backup administration page
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

$task = mosGetParam( $_REQUEST, 'task', '' );
$filename = mosGetParam( $_REQUEST, 'filename', '' );

switch( $task ) {
	case "deletefile":
		// Delete the file
		unlink( $filename );
		echo "<p>" . $JPLang['buadmin']['deletesuccess'] . "</p>";
		echo "<p><a href=\"index2.php?option=$option&act=backupadmin\">" . $JPLang['cpanel']['buadmin'] . "</a></p>";
		break;
	case "downloadfile":
		ob_end_clean(); // In case some braindead mambot spits its own HTML despite no_html=1
		$filename = stripslashes( $filename ); // Make sure the filename is OK
		if (file_exists( $filename )) {
			// Since we're not outputting text/html, we need to send the correct headers!
			// Tell the browser we'll be outputting a gzip file
			header('Content-type: application/x-compressed'); // TODO - Find the correct file type!
			// It will be called... whatever the filename is
			header('Content-Disposition: attachment; filename="'. basename($filename) .'"');

			readfile( $filename );
		}
		break;
	case "":
	default:
		JP_BUFA_Main();
		break;
}


function JP_BUFA_Main() {
	global $option, $JPLang;

	$fileCount = JP_GetNoOfBackupFiles();

	?>
	<script>
		function postTaskForm( myTask, myFile ) {
			document.adminForm.task.value=myTask;
			document.adminForm.filename.value=myFile;
			try {
				document.adminForm.onsubmit();
				}
			catch(e){}
			document.adminForm.submit();
		}
	</script>
	<form name="adminForm" id="JPadminForm" action="index2.php" method="get">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="backupadmin" />
		<input type="hidden" name="no_html" id="no_html" value="1" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="filename" value="" />
	</form>

	<table class="adminheading">
		<tr>
			<th class="config" nowrap rowspan="2">
				<?php echo $JPLang['common']['jptitle']; ?>
			</th>
		</tr>
		<tr>
			<td nowrap><h2><?php echo $JPLang['cpanel']['buadmin']; ?></h2></td>
		</tr>
	</table>
<?php echo $JPLang['buadmin']['filecount']; echo ':'.$fileCount  //frozenfx fix?>
	<table class="adminlist">
		<tr>
			<th width="5">
				#
			</th>
			<th class="title">
				<?php echo $JPLang['buadmin']['filename']; ?>
			</th>
			<th align="left" width="100">
				<?php echo $JPLang['buadmin']['size']; ?>
			</th>
			<th width="80" align="right">
			</th>
			<th width="80" align="right">
			</th>
			<th align="center" width="120">
				<?php echo $JPLang['buadmin']['date']; ?>
			</th>
		</tr>
	<?php
		JP_GetFileList();
	?>
	</table>
<?php
}


function JP_GetNoOfBackupFiles() {
	global $JPConfiguration;

	require_once "CFSAbstraction.php";
	$FS = new CFSAbstraction();

	$files1 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.zip*");
	$files2 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.jpa*");
	$files3 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.sql*");
	$allFilesAndDirs = _selectiveMergeArrays( $files1, $files2 );
	$allFilesAndDirs = _selectiveMergeArrays( $allFilesAndDirs, $files3 );
	if ($allFilesAndDirs === false) return false;

	$fileCount = 0;
	foreach($allFilesAndDirs as $fileName) {
		switch( $fileName['type']) {
			case "file":
				$fileCount++;
				break;
			default:
				break;
		}
	}

	return $fileCount;
}

function JP_GetFileList() {
	global $JPConfiguration, $JPLang;

	require_once "CFSAbstraction.php";
	$FS = new CFSAbstraction();

	$files1 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.zip*");
	$files2 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.jpa*");
	$files3 = $FS->getDirContents($JPConfiguration->OutputDirectory, "*.sql*");
	$allFilesAndDirs = _selectiveMergeArrays( $files1, $files2 );
	$allFilesAndDirs = _selectiveMergeArrays( $allFilesAndDirs, $files3 );
	if ($allFilesAndDirs === false) return false;

	$count = 0;
	foreach($allFilesAndDirs as $fileDef) {
		$fileName = $fileDef['name'];
		switch($fileDef['type']) {
			case "file":
				$fileName = realpath($fileName);
				$count++;
				$createdTime	= date( "Y-m-d H:i:s", filemtime( $fileName ) );
				$fileSizeKb		= round( $fileDef['size'] / 1024, 2 );
				//$onlyName		= str_replace( $JPConfiguration->OutputDirectory . '\\', "", $fileName );
				$onlyName		= basename($fileName);
				$linkDownload	= "javascript:postTaskForm('downloadfile', '". addslashes($fileName) ."');";
				$linkDelete		= "javascript:if (confirm('". $JPLang['buadmin']['confirmtitle'] ."')){ document.getElementById('no_html').value = 0; postTaskForm('deletefile', '". addslashes($fileName) ."'); }";
				?>
			<tr class="<?php echo "row$count"; ?>">
				<td><?php echo $count; ?></td>
				<td align="left"><?php echo $onlyName; ?></td>
				<td align="left"><?php echo $fileSizeKb; ?> Kb</td>
				<td align="center">
					<a href="<?php echo $linkDownload; ?>">
					<img src="images/downarrow.png" border=0>
					<?php echo $JPLang['buadmin']['download']; ?>
					</a>
				</td>
				<td align="center">
					<a href="<?php echo $linkDelete; ?>">
					<img src="images/publish_x.png" border=0>
					<?php echo $JPLang['buadmin']['delete']; ?>
					</a>
				</td>
				<td><?php echo $createdTime; ?></td>
			</tr>
				<?php
				break;
			default:
				break;
		}
	}
}

function _selectiveMergeArrays($files1, $files2)
{
	if( is_array($files1) ) {
		if( is_array($files2) ) {
			return array_merge($files1, $files2);
		} else {
			return $files1;
		}
	} else {
		if( is_array($files2) ) {
			return $files2;
		} else {
			return false;
		}
	}
}
?>
