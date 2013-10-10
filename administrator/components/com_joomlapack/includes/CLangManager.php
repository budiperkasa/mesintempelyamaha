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
* Localisation (language file) manager
**/

// Ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

class CLangManager
{
	var $translations = null;		// The loaded translations array
	var $lang = "";					// User-selected language

	/**
	 * Initialises the class by loading the languages and populating the global $JPLang  
	 *
	 * @return CLangManager
	 */
	function CLangManager()
	{
		global $JPLang;
		
		$this->_loadLanguages();
		$JPLang = &$this->getTranslations();
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
	 * Returns a copy of the active translation file loaded
	 *
	 * @return array The translations array
	 */
	function getTranslations()
	{
		if( is_null($this->translations) ) $this->_loadLanguages();
		return $this->translations;
	}
	
	/**
	 * Returns a translated string
	 *
	 * @param string $scope The scope in which to search for a translation.
	 * @param string $key The translation key to retrieve
	 * @return string The translated string asked for.
	 */
	function get( $scope, $key )
	{
		if( is_null($scope) || is_null($key) )
		{
			return $key;
		} else {
			$trans = &$this->getTranslations();
			return $trans[$scope][$key];
		}
	}
	
	/**
	 * Loads the language files (first the default, then the user's language) into $this->translations
	 *
	 */
	function _loadLanguages()
	{
		// Get local language
		if( !defined('_JEXEC') )
		{
			global $mosConfig_lang;
			$lang = $mosConfig_lang;	
		} else {
			global $mainframe;
			$lang = $mainframe->getUserState( "application.lang", 'lang' );
		}
	
		// Load default language (English)
		$langEnglish = parse_ini_file( JPComponentRoot . "/lang/english.ini", true);
		
		// Load user's language file, if exists
		if (file_exists( JPComponentRoot . "/lang/$lang.ini" )) {
			$langLocal = parse_ini_file( JPComponentRoot . "/lang/$lang.ini", true );
			$this->translations = array_merge($langEnglish, $langLocal);
			unset( $langEnglish );
			unset( $langLocal );
		} else {
			$this->translations = $langEnglish;
			unset( $langEnglish );
		}

		// Support the original way of handling translations
		global $JPLang;
		$JPLang = $this->translations;
	}
}
?>