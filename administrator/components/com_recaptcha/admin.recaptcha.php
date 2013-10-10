<?php
/**
* @version toolbar.recaptcha.php
* @package Joomla
* @subpackage recaptcha
* @copyright Copyright (C) 2007 Robert van den Breemen. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// ensure user has access to this function
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_contact' ))) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	case 'save':
		saveRecaptcha( $option );
		break;

  	case 'cancel':
		cancelRecaptcha( );
		break;

	default:
		showRecaptcha( $option );
		break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showRecaptcha( $option ) {
	global $database, $mainframe;

	$query = "SELECT a.id"
	. "\n FROM #__components AS a"
	. "\n WHERE ( a.admin_menu_link = 'option=com_recaptcha' OR a.admin_menu_link = 'option=com_recaptcha&hidemainmenu=1' )"
	. "\n AND a.option = 'com_recaptcha'"
	;
	$database->setQuery( $query );
	$id = $database->loadResult();

	// load the row from the db table
	$row = new mosComponent( $database );
	$row->load( $id );

	// get params definitions
	$params = new mosParameters( $row->params, $mainframe->getPath( 'com_xml', $row->option ), 'component' );

	HTML_recaptcha::settings( $option, $params, $id );
}

/**
* Saves the record from an edit form submit
* @param string The current GET/POST option
*/
function saveRecaptcha( $option ) {
	global $database;

	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
		$txt = array();
		foreach ($params as $k=>$v) {
			$txt[] = "$k=$v";
		}
		$_POST['params'] = mosParameters::textareaHandling( $txt );
	}

	$id = intval( mosGetParam( $_POST, 'id', '17' ) );
	$row = new mosComponent( $database );
	$row->load( $id );

	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = 'Settings successfully Saved';
	mosRedirect( 'index2.php?option='. $option, $msg );
}

/**
* Cancels editing and checks in the record
*/
function cancelRecaptcha(){
	mosRedirect( 'index2.php' );
}
?>