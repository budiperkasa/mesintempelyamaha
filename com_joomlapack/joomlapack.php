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
* 
* Frontend part
**/

(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

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

// Always populate basic Joomla! page parameters and make them global
global $act, $task;

// Get the parameters from the request
$act	= CJVAbstract::getParam('act',	'default');
$task	= CJVAbstract::getParam('task',	'');

// Load language definitions
require_once( JPComponentRoot . '/includes/CLangManager.php' );
$LangManager = CLangManager::getInstance();

switch( $act )
{
	case "fullbackup":
		require_once( JPFrontendRoot . '/includes/CFullBackup.php' );
		$tickableObject = CFullBackup::getInstance();
		$tickableObject->tick();
		break;
		
	default:
		echo $LangManager->get('frontend', 'accessdenied');
		break;
}
?>