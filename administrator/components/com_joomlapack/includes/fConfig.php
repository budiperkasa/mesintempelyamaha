<?php
/**
 * Application Pages :: Configuration page (site selection and packing)
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

global $JPConfiguration, $JPLang;
if (!defined('_JEXEC')){
	$task		= mosGetParam( $_REQUEST, 'task', 'default' );
	$act		= mosGetParam( $_REQUEST, 'act', 'default' );
	}
else {
	$task		= JRequest::getVar('task', 'default');
	$act		= JRequest::getVar('act', 'default');
}
?>
<table class="adminheading">
	<tr>
		<th class="config" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['cpanel']['config'];?></h2></td>
	</tr>
</table>
<?php

echo "<p align=\"center\">" . $JPLang['config']['filestatus'] . ' ' . colorizeWriteStatus($JPConfiguration->isConfigurationWriteable()) . "</p>";

outputConfig();

function outputConfig() {
	global $JPConfiguration, $JPLang, $option;
?>
	<form action="index2.php" method="post" name="adminForm">
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="config" />
		
		<table border="0" cellpadding="0" cellspacing="0" width="95%" class="adminform">
		<tr><td>
				
<?php
	$tabs = new mosTabs(1);
	$tabs->startPane(1);
	$tabs->startTab($JPLang['config']['basic_options'],'jpconfigbasic');
?>
		<table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">
			<tr align="center" valign="middle">
				<th width="20%">&nbsp;</th>
				<th width="20%"><?php echo $JPLang['config']['option']; ?></th>
				<th width="60%"><?php echo $JPLang['config']['cursettings']; ?></th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['common']['outdir']; ?></td>
				<td><input type="text" name="outdir" size="40" value="<?php echo $JPConfiguration->OutputDirectory; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['common']['tempdir']; ?></td>
				<td><input type="text" name="tempdir" size="40" value="<?php echo $JPConfiguration->TempDirectory; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['tarname']; ?></td>
				<td><input type="text" name="tarname" size="40" value="<?php echo $JPConfiguration->TarNameTemplate;?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['loglevel']; ?></td>
				<td><?php outputLogLevel( $JPConfiguration->logLevel ); ?></td>
			</tr>
		</table>
<?php
	$tabs->endTab();
	$tabs->startTab($JPLang['config']['advanced'],'jpconfigadvanced');
?>
		<table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">	
			<tr align="center" valign="middle">
				<th width="20%">&nbsp;</th>
				<th width="20%"><?php echo $JPLang['config']['option']; ?></th>
				<th width="60%"><?php echo $JPLang['config']['cursettings']; ?></th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['sqlcompat']; ?></td>
				<td><?php outputSQLCompat( $JPConfiguration->MySQLCompat ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['fla_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->fileListAlgorithm, "fileListAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['dba_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->dbAlgorithm, "dbAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['pa_label']; ?></td>
				<td><?php AlgorithmChooser( $JPConfiguration->packAlgorithm, "packAlgorithm" ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['compress']; ?></td>
				<td><?php outputBoolChooser( $JPConfiguration->boolCompress ); ?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['altinstaller']; ?></td>
				<td><?php echo AltInstallerChooser( $JPConfiguration->InstallerPackage ); ?></td>
			</tr>
		</table>	
<?php
	$tabs->endTab();
	$tabs->startTab($JPLang['config']['frontend'],'jpconfigfrontend');
	// TODO - Implement storing new values
?>
		<table cellpadding="4" cellspacing="0" border="0" width="95%" class="adminform">	
			<tr align="center" valign="middle">
				<th width="20%">&nbsp;</th>
				<th width="20%"><?php echo $JPLang['config']['option']; ?></th>
				<th width="60%"><?php echo $JPLang['config']['cursettings']; ?></th>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['enablefeb']; ?></td>
				<td><input name="enableFrontend" type="checkbox" <?php echo ($JPConfiguration->enableFrontend ) ? 'checked' : ''; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['secretprompt']; ?></td>
				<td><input name="secretWord" type="text" size="30" maxlength="30" value="<?php echo $JPConfiguration->secretWord; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><?php echo $JPLang['config']['secretinfo1']; ?><br />
				<?php echo makeFEBURL(); ?>
				</td>
			</tr>
		</table>
<?php
	$tabs->endTab();
	$tabs->endPane();
?>
	</td></tr></table>
	</form>
<?php
}

function colorizeWriteStatus( $status ) {
	global $JPLang;

	if ( $status ) {
		return '<span class="statusok">' . $JPLang['common']['writable']  . '</span>';
	} else {
		return '<span class="statusnotok">' . $JPLang['common']['unwritable'] . '</span>';
	}
}

function outputSQLCompat( $sqlcompat ) {
	global $JPLang;

	$options = array(
		array("value" => "compat", "desc" => $JPLang['config']['compat']),
		array("value" => "default", "desc" => $JPLang['config']['default'])
	);

	echo '<select name="sqlcompat">';
	foreach( $options as $choice ) {
		$selected = ( $sqlcompat == $choice['value'] ) ? "selected" : "";
		echo "<option value=\"". $choice['value'] ."\" $selected>". $choice['desc'] ."</option>";
	}
	echo '</select>';
}

function outputBoolChooser( $boolOption ) {
	global $JPLang;

	echo '<select name="compress">';
	$selected = ($boolOption == "zip") ? "selected" : "";
	echo "<option value=\"zip\" $selected>". $JPLang['config']['zip'] ."</option>";
	//$selected = ($boolOption == "jpa") ? "selected" : "";
	//echo "<option value=\"jpa\" $selected>". $JPLang['config']['jpa'] ."</option>";
	echo '</select>';
}

function AlgorithmChooser( $strOption, $strName ) {
	global $JPLang;

	echo "<select name=\"$strName\">";
	$selected = ($strOption == "single") ? "selected" : "";
	echo "<option value=\"single\" $selected>". $JPLang['config']['single'] ."</option>";
	$selected = ($strOption == "smart") ? "selected" : "";
	echo "<option value=\"smart\" $selected>". $JPLang['config']['smart'] ."</option>";
	$selected = ($strOption == "multi") ? "selected" : "";
	echo "<option value=\"multi\" $selected>". $JPLang['config']['multi'] ."</option>";
	echo '</select>';
}

function AltInstallerChooser( $strOption ) {
	global $JPConfiguration;

	$altInstallers = $JPConfiguration->AltInstaller->loadAllDefinitions();
	echo '<select name="altInstaller">';
	foreach ($altInstallers as $altInstaller) {
		$selected = ($strOption == $altInstaller['meta']) ? "selected" : "";
		echo "<option value=\"" . $altInstaller['meta'] . "\" $selected>". $altInstaller['name'] ."</option>";
	}
	echo '</select>';
}

function outputLogLevel( $strOption ) {
	global $JPConfiguration, $JPLang;

	echo '<select name="logLevel">';
	$selected = ($strOption == "1") ? "selected" : "";
	echo "<option value=\"1\" $selected>". $JPLang['config']['llerror'] ."</option>";
	$selected = ($strOption == "2") ? "selected" : "";
	echo "<option value=\"2\" $selected>". $JPLang['config']['llwarning'] ."</option>";
	$selected = ($strOption == "3") ? "selected" : "";
	echo "<option value=\"3\" $selected>". $JPLang['config']['llinfo'] ."</option>";
	$selected = ($strOption == "4") ? "selected" : "";
	echo "<option value=\"4\" $selected>". $JPLang['config']['lldebug'] ."</option>";
	$selected = ($strOption == "0") ? "selected" : "";
	echo "<option value=\"0\" $selected>". $JPLang['config']['llnone'] ."</option>";
	echo '</select>';
}

function makeFEBURL()
{
	global $mosConfig_live_site;
	$out = "<tt>";
	$out .= $mosConfig_live_site;
	$out .= '/index2.php?option=com_joomlapack&act=fullbackup&key=<b>secret_key</b>&no_html=1';
	$out .= "</tt>";
	return $out;
}
?>