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
* Frontend full backup functions
**/

// Ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

class CFullBackup
{
	/**
	 * Key supplied in the request
	 *
	 * @var string
	 */
	var $key;

	function CFullBackup()
	{		
		// Force reloading always
		header ("Cache-Control: no-cache, must-revalidate");	// HTTP/1.1
		header ("Pragma: no-cache");	// HTTP/1.0
		
		// Check if the user can perform this operation 
		$this->_authenticate();
	}
	
	/**
	 * Static method to return the loaded instance of the class, or create a new if none is present.
	 * Implements the Singleton desing pattern
	 *
	 * @return CLangManager
	 */
	function getInstance()
    {
		static $instance;
		
		if (!isset($instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}

		return $instance;
	}
	
	/**
	 * Perform a step of the backup process
	 */
	function tick()
	{
		$task = CJVAbstract::getParam( 'task', 'init' );
		$error = CJVAbstract::getParam( 'error', false );
		
		require_once( JPComponentRoot . "/includes/CConfiguration.php" );
		require_once( JPComponentRoot . "/includes/CCUBE.php" );
		
		switch( $task )
		{
			case 'init':
				$ret = $this->_do(1);
				header( 'Location: ' . $this->_getNewURI(false) );
				break;
			
			case 'continue':
				$ret = $this->_do();
				
				if ($ret['Error'] != "") {
					header( 'Location: ' . $this->_getNewURI(true, true) );
				} elseif( $ret['Domain'] == 'finale' ) {
					header( 'Location: ' . $this->_getNewURI(true) );
				} else {
					header( 'Location: ' . $this->_getNewURI(false) );
				}

				break;
			
			case 'finished':
				$lang = CLangManager::getInstance();
				if( $error )
				{
					global $CUBE;
					loadJPCUBE();
					echo $lang->get('frontend', 'status500');
					echo $CUBE->_Error;
				} else {
					echo $lang->get('frontend', 'status200');
				}
				break;
			
			default:
				$lang = CLangManager::getInstance();
				echo $lang->get('frontend', 'accessdenied');
				break;
		}
	}
	
	/**
	 * Check for authorized use of this file, or die with 'Access Denied' message
	 *
	 */
	function _authenticate()
	{
		require_once( JPComponentRoot . "/includes/CConfiguration.php" );
		global $JPConfiguration;
		
		// Check if the frontend backup option is enabled
		if( !$JPConfiguration->enableFrontend )
		{
			$langManager = CLangManager::getInstance();
			die( $langManager->get('frontend', 'accessdenied') );
		}
		
		// Get key supplied in $_REQUEST
		$key1 = CJVAbstract::getParam('key', '');
		$key2 = CJVAbstract::getParam('secret', '');
		
		if( ($key1 == '') && ($key2 != '') ) {
			$this->key = $key2;
		} elseif( ($key1 != '') && ($key2 == '') ) {
			$this->key = $key1;
		} else {
			$this->key = '';
		}

		// Compare keys
		if( $this->key != $JPConfiguration->secretWord ) {
			$langManager = CLangManager::getInstance();
			die( $langManager->get('frontend', 'accessdenied') );
		}
		
		// Check no_html (must be '1')
		$no_html = CJVAbstract::getParam('no_html', 0);
		if( $no_html != 1 ) {
			$langManager = CLangManager::getInstance();
			die( $langManager->get('frontend', 'accessdenied') );
		}
	}
	
	/**
	 * Runs the CUBE tick
	 *
	 * @param integer $forceStart When set to 1 it forces a new instance of the CUBE to be created
	 * 
	 * @return array Status information from the CUBE
	 */
	function _do( $forceStart = 0 )
	{
		global $CUBE, $JPConfiguration;
		
		if ( $forceStart > 0 ) {
			$this->_checkCollision(); // Collision detection
			$JPConfiguration->DeleteDebugVar("CUBEObject");
			$JPConfiguration->DeleteDebugVar("CUBEArray");
			$CUBE = new CCUBE( false );
		} else {
			loadJPCUBE();
		}

		$ret = $CUBE->tick();
		
		saveJPCUBE();
		
		return $ret;
	}
	
	function _getNewURI($finished = false, $error = false)
	{		
		$option = CJVAbstract::getParam( 'option' );
		$key = CJVAbstract::getParam( 'key' );

		if ($finished) {
			if ($error) {
				return CJVAbstract::SiteURI() . "/index2.php?option=$option&act=fullbackup&task=finished&error=1&key=$key&no_html=1";
			} else {
				return CJVAbstract::SiteURI() . "/index2.php?option=$option&act=fullbackup&task=finished&key=$key&no_html=1";
			}
		} else {
			return CJVAbstract::SiteURI() . "/index2.php?option=$option&act=fullbackup&task=continue&key=$key&no_html=1";
		}
	}
	
	function _checkCollision()
	{
		require_once( JPComponentRoot . "/includes/CConfiguration.php" );
		global $JPConfiguration;
	
		if( !is_null($JPConfiguration->ReadDebugVar('CUBELock')) )
		{
			$lang = CLangManager::getInstance();
			die( $lang->get('frontend', 'status501') );
		}
		
	}
}
?>