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

// Ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

// Restrict back-end access only to Super Administrators
if( !defined('_JEXEC') )
{
	if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
		mosRedirect( 'index2.php', _NOT_AUTH );
	}
} else {
	// Note: We are checking if someone can 'manage' the 'com_config', which translates to
	// being a super administrator in Joomla! 1.5.0. Most probably this will change in the
	// future and we'll have to supply a real ACL solution.
	$user = & JFactory::getUser();
	if (!$user->authorize('com_config', 'manage')) {
		$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
	}
}

// 1.1.1b2 - Use constants with absolute paths
global $option;
if( !defined('_JEXEC') )
{
	$option	= mosGetParam( $_REQUEST, 'option',	'com_jpack' );
	if ( array_key_exists('mosConfig_asbolute_path', $_REQUEST ) )
	{
		die('Hacking attempt');
	} else {
		global $mosConfig_absolute_path;
		$myBase = $mosConfig_absolute_path;
	}
} else {
	$option	= JRequest::getCmd('option','com_jpack');
	$myBase = JPATH_SITE;
}

if (stristr(php_uname(), 'windows')){
	// Change potential windows directory separator
	if ((strpos($myBase, '\\') > 0) || (substr($myBase, 0, 1) == '\\')){
		$myBase = strtr($myBase, '\\', '/');
	}
}
// 1.1.1b2 - Defined constants contain the absolute paths to the site's root and this component's dirs
define( 'JPSiteRoot', realpath( $myBase ) );
define( 'JPComponentRoot', $myBase . '/administrator/components/' . $option );
define( 'JPFrontendRoot', $myBase . '/components/' . $option );

// Include Joomla! Version Abstraction Layer
require_once( JPComponentRoot . '/includes/CJVAbstract.php' );

// Get the parameters from the request
global $act, $task;
$act	= CJVAbstract::getParam('act',	'default');
$task	= CJVAbstract::getParam('task',	'');

// Some bureaucracy is only useful for non-AJAX calls. For AJAX calls, it's just a waste of CPU and memory :)
if ($act != "ajax") {
	
	// 1. Get component version from its XML file
	// ------------------------------------------------------------------------
	require_once( JPSiteRoot . '/includes/domit/xml_domit_lite_include.php' );
	$xmlDoc = new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );
	// TODO: I am not sure if J! 1.5 XML files use the same path. Must investigate.
	if ($xmlDoc->loadXML( JPComponentRoot . "/joomlapack.xml", false, true )) {
		$root = &$xmlDoc->documentElement;
		$e = &$root->getElementsByPath('version', 1);
		define("_JP_VERSION", $e->getText()) ;
		$root = &$xmlDoc->documentElement;
		$e = &$root->getElementsByPath('creationDate', 1);
		define("_JP_DATE", $e->getText()) ;
	} else {
		define("_JP_VERSION", "1.1 Series");
	}

	// 2. HTML generation library for JoomlaPack's back-end
	// ------------------------------------------------------------------------
	if( !defined('_JEXEC') ) {
		require_once( $mainframe->getPath( 'admin_html' ) );	
	} else {
		require_once( JApplicationHelper::getPath( 'admin_html' ) );
	}

	// 3. Localisation (language file loading)
	// ------------------------------------------------------------------------
	require_once( JPComponentRoot . '/includes/CLangManager.php' );
	$LangManager = CLangManager::getInstance(); // Force loading of the translation files
}

// Configuration class
require_once( JPComponentRoot . "/includes/CConfiguration.php" );

/** handle the task */
switch ($act) {
    case "config":
    	// Configuration screen
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	switch ($task) {
    		case "apply":
    			processSave();
    			jpackScreens::fConfig();
    			jpackScreens::CommonFooter();
    			break;
    		case "save":
    			processSave();
    			jpackScreens::fMain();
    			jpackScreens::CommonFooter();
    			break;
    		case "cancel":
    			jpackScreens::fMain();
    			jpackScreens::CommonFooter();
    			break;
    		default:
    			jpackScreens::fConfig();
    			jpackScreens::CommonFooter();
    			break;
    	}
		break;
    case "pack":
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	// Packing screen - that's where the actual backup takes place
    	require_once( JPComponentRoot . "/includes/sajax.php" );
    	require_once( JPComponentRoot . "/includes/ajaxtool.php" );
        jpackScreens::fPack();
        jpackScreens::CommonFooter();
        break;
    case "backupadmin":
        jpackScreens::fBUAdmin();
        switch( $task ) {
        	case "downloadfile":
        		break;
        	default:
        		jpackScreens::CommonFooter();
        		break;
        }
    	break;

	case "def" :
		// Directory exclusion filters
		require_once( JPComponentRoot . "/includes/CDirExclusionFilter.php" );
		jpackScreens::fDirExclusion();
		jpackScreens::CommonFooter();
		break;

    case "ajax":
		// Setup custom error handler
		global $JPConfiguration;
		if ( $JPConfiguration->isOutputWriteable() )
		{
			@error_reporting( E_ERROR );
			$old_error_handler = set_error_handler("userErrorHandler");
		}
    	// AJAX helper functions
		require_once( JPComponentRoot . "/includes/sajax.php" );
		require_once( JPComponentRoot . "/includes/ajaxtool.php" );
		// Restore error handler
		if( isset($old_error_handler) ) {
			restore_error_handler();
		}
    	break;

    case "test":
		jpackScreens::fDebug();
        jpackScreens::CommonFooter();
    	break;

    case "log":
		jpackScreens::fLog();
        jpackScreens::CommonFooter();
    	break;
    
    case "dllog": // Option to download raw log
    	global $JPConfiguration;
    	ob_end_clean(); // In case some braindead mambot spits its own HTML despite no_html=1
    	header('Content-type: text/plain');
    	header('Content-Disposition: attachment; filename="joomlapacklog.txt"');
    	@readfile( $JPConfiguration->TranslateWinPath( $JPConfiguration->OutputDirectory . "/joomlapack.log" ) );
    	break;

    case "unlock":
		jpackScreens::fUnlock();
        jpackScreens::CommonFooter();
    	break;

    default:
    	echo '<link rel="stylesheet" href="components/'.$option.'/css/jpcss.css" type="text/css" />';
    	// Application status check
        jpackScreens::fMain();
        jpackScreens::CommonFooter();
        // On main screen, add a PayPal donate button as well
		?>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<p>
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			</p>
			</form>
		<?php
        break;
}

function processSave() {
	global $JPConfiguration;
	$JPConfiguration->SaveFromPost();
}

/**
 * Custom PHP error handler, to cope for all those cheapy hosts who won't provide access to the
 * server's error logs. Kudos to the 1&1 how-to section for providing the bulk of this function.
 *
 * @param integer $errno PHP error type number
 * @param string $errmsg The message of the error
 * @param string $filename Script's filename where this error occured
 * @param integer $linenum Script's line number where this error occured
 * @param array $vars The variables passed (?) to the function, or something like that
 */
function userErrorHandler ($errno, $errmsg, $filename, $linenum,  $vars) 
{
	global $JPConfiguration;

	$time=date("d M Y H:i:s"); 
	// Get the error type from the error number 
	$errortype = array (1    => "Error",
						2    => "Warning",
						4    => "Parsing Error",
						8    => "Notice",
						16   => "Core Error",
						32   => "Core Warning",
						64   => "Compile Error",
						128  => "Compile Warning",
						256  => "User Error",
						512  => "User Warning",
						1024 => "User Notice");
	$errlevel=$errortype[$errno];

	//Write error to log file (CSV format)
	$errfile = @fopen( $JPConfiguration->OutputDirectory .  "/errors.csv", "a");
	if (!($errfile === FALSE)) { 
		if( ($errlevel != '') && ($errno != 8) ) fputs( $errfile, "\"$time\",\"$filename:$linenum\",\"($errlevel) $errmsg\"\r\n"); 
		fclose($errfile);
	}
 
	if( ($errno == 4) && ($errno == 16) && ($errno == 64) && ($errno == 256) ) {
		//Terminate script if fatal error
		die("A fatal error has occurred. Script execution has been aborted.");
	} 
}

?>