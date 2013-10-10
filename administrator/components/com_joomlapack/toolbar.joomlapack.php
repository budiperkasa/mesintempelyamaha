<?php
/**
 * Backend toolbar handle
 *
 * LICENSE: This source file is distributed subject to the GNU General
 * Public Licence (GPL) version 2 or later.
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the GNU GPL and are unable to obtain it through the web,
 * please send a note to nikosdion@gmail.com so we can mail you a copy immediately.
 *
 * @package    JoomlaPacker
 * @Author     Nicholas K. Dionysopoulos nikosdion@gmail.com
 * @copyright  2007 Nicholas K. Dionysopoulos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.0.3
 * @since      File available since Release 1.0
*/

// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

// handle the task
$act = mosGetParam( $_REQUEST, 'act', '' );
$task = mosGetParam( $_REQUEST, 'task', '' );

switch ($act){
	case "config":
		switch( $task ) {
			case "save":
				break;
			case "apply":
				TOOLBAR_jpack::_CONFIG();
				break;
			case "":
				TOOLBAR_jpack::_CONFIG();
				break;
			default:
				break;
		}
		break;
	default:
		break;
}

?>
