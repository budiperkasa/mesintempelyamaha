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
* Joomla! Version Abstraction Layer
**/

// Ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

/**
 * Provides static methods to abstract source-level differences between Joomla! 1.0.x (built upon the
 * Mambo codebase and coding conventions) and Joomla! 1.5.x (built upon Joomla! Framework 1.5.x).
 *
 */
class CJVAbstract
{
	
	/**
	 * Discourage creating objects from this class
	 *
	 * @return CJVAbstract
	 */
	function CJVAbstract()
	{
		die('CJVAbstract does not support object creation.');
	}
	
	/**
	 * Gets a parameter value from the $_REQUEST object
	 *
	 * @param string $paramName The parameter name
	 * @param string $defaultValue The default value (null if not specified)
	 * @return mixed The parameter value
	 */
	function getParam( $paramName, $defaultValue = null )
	{
		if( !defined('_JEXEC') ) {
			return mosGetParam( $_REQUEST, $paramName, $defaultValue );
		} else {
			return JRequest::getVar($paramName, $defaultValue);
		}
	}
	
	/**
	 * Returns the site's base URI
	 *
	 * @return string The site's URI, e.g. http://www.example.com
	 */
	function SiteURI()
	{
		$port = ( $_SERVER['SERVER_PORT'] == 80 ) ? '' : ":".$_SERVER['SERVER_PORT'];
		$root = $_SERVER['SERVER_NAME'] . $port . $_SERVER['PHP_SELF'];
		$upto = stripos( $root, "/index" );
		$root = substr( $root, 0, $upto );
		return "http://".$root;		
	}
}
?>