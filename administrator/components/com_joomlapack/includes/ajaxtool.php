<?php
// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

sajax_init();
sajax_export(
	"tick", "getCUBEArray", "ping", "dirSelectionHTML", "toggleDirFilter", "errorTrapReport"
);
sajax_handle_client_request();

// ===============================================================================================
// ===============================================================================================

/**
* Continues the procedure
* @param $forceStart boolean When set to true, forces the procedure to start over
*/
function tick( $forceStart = 0, $forceDBOnly = 0 ){
	global $CUBE, $JPConfiguration;

	require_once("CCUBE.php");

	if ( $forceStart > 0 ) {
		$JPConfiguration->DeleteDebugVar("CUBEObject");
		$JPConfiguration->DeleteDebugVar("CUBEArray");
	}

	if ( ($forceDBOnly > 0) && $forceStart > 0 ) {
		$CUBE = new CCUBE( true );
	} else {
		loadJPCUBE();
	}

	$ret = $CUBE->tick();
	saveJPCUBE();

	return $ret;
}

/**
* Returns the stored copy of the CUBE Array
*/
function getCUBEArray(){
	require_once("CCUBE.php");
	return loadJPCUBEArray();
}

/**
* Simple PING server for debugging purposes
* @return integer Always 1
*/
function ping(){
	return 1;
}

/**
* JPSetErrorReporting will reset error reporting to only syntax and parse errors,
* storing the old value for later use. It'll also try to set an infinite time
* limit on the script. All this is required to avoid PHP messing output meant
* for AJAX client-side parsing which caused notorious timeouts.
*/
function JPSetErrorReporting(){
	global $JP_Error_Reporting;

	$JP_Error_Reporting = @error_reporting(E_ERROR | E_PARSE);
	#$JP_Error_Reporting = error_reporting(E_WARNING | E_ERROR | E_PARSE);
	@set_time_limit(0);
}

/**
* JPRestoreErrorReporing will restore error reporting. It'll also try to clear
* (erase) the output buffer, so that the script can send back only the intended
* result. All this is required to avoid PHP messing output meant for AJAX
* client-side parsing which caused notorious timeouts.
*/
function JPRestoreErrorReporing(){
	global $JP_Error_Reporting;

	@error_reporting($JP_Error_Reporting);
	@ob_clean();
}

function dirSelectionHTML( $root ){
	global $JPLang;
	require_once( JPComponentRoot . "/includes/CDirExclusionFilter.php");

	JPSetErrorReporting();

	$root = realpath($root); // Make relative dirs into absolute, e.g. /www/test/images/../components --> /www/test/components

	$out = <<<END
		<h4>$root</h4>
		<table class="adminlist">
			<tr>
				<th align="left" width="50">
END;
	$out .= $JPLang['def']['exclude'] . "\n" . "</th><th class=\"title\">" .
			$JPLang['def']['directory'] . "</th></tr>";

	$def = new CDirExclusionFilter();
	$dirs = $def->getDirectory( $root );
	$id=0;
	foreach($dirs as $dir => $excluded){
		$id++;
		$checked = $excluded ? " checked = \"true\" " : "";
		$nocheck = ($dir == ".") || ($dir == "..");
		$out .= "\n<tr><td align=\"center\">";
		if (!$nocheck) {
			$out .= "<input type=\"checkbox\" $checked onclick=\"ToggleFilter('" . $def->ReplaceSlashes($root) . "', '$dir','def-$id');\" id=\"def-$id\">";
		} else {
			$out .= "&nbsp;";
		}

		$out .= "</td><td align=\"left\">";
		if ($excluded) {
			$out .= htmlentities($dir);
		} else {
			$out .= "<a href=\"javascript:dirSelectionHTML('". $def->ReplaceSlashes($root . DIRECTORY_SEPARATOR . $dir) ."');\">" . htmlentities($dir) . "</a>";
		}
		$out .= "</td></tr>";
	}
	$out .= "\n</table>";

	JPRestoreErrorReporing();

	return $out;
}

function toggleDirFilter( $root, $dir, $checked ){
	require_once(JPComponentRoot . "/includes/CDirExclusionFilter.php");

	JPSetErrorReporting();
	$def = new CDirExclusionFilter();
	$def->modifyFilter($root, $dir, $checked);
	JPRestoreErrorReporing();
	return 1;
}

function errorTrapReport( $badData ){
	global $JPConfiguration;

	JPSetErrorReporting();
	CJPLogger::WriteLog(_JP_LOG_ERROR, "Last operation failed. Server response:");
	CJPLogger::WriteLog(_JP_LOG_ERROR, htmlspecialchars($badData));
	JPRestoreErrorReporing();
	return 1;
}

function getLock()
{
	require_once(JPComponentRoot . "/includes/CConfiguration.php");
	global $JPConfiguration;
	
	return $JPConfiguration->ReadDebugVar('CUBELock');
}

?>