<?php

/**
 * Directory Exclusion Filter Class
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

class CDirExclusionFilter {

	/** @var array Array of the database filters */
	var $_filterArray;

	/**
	* Class initializer, loads existing filters
	*/
	function CDirExclusionFilter(){
		global $database;

		// Initialize by loading any exisiting filters
		$sql = "SELECT * FROM #__jp_def";
		$database->setQuery( $sql );
		$database->query();

		$this->_filterArray = $database->loadAssocList();
	}

	function ReplaceSlashes($string){
		return str_replace("\\", "/", $string);
	}

	/**
	* Returns the array of the filters
	* @return array The exclusion filters
	*/
	function getFilters(){
		global $JPConfiguration;

		// Initialize with existing filters
		if (is_null($this->_filterArray)) {
			$myArray = array();
		} else {
			$myArray = array();

			foreach($this->_filterArray as $filter){
				$myArray[] = $filter['directory'];
			}
		}

		// Add output, temporary and installation directory to exclusion filters
		$myArray[] = $this->ReplaceSlashes($JPConfiguration->OutputDirectory);
		$myArray[] = $this->ReplaceSlashes($JPConfiguration->TempDirectory);
		$myArray[] = $this->ReplaceSlashes(JPSiteRoot . DIRECTORY_SEPARATOR . "installation");
		return $myArray;
	}

	/**
	* Returns the contents of a directory and their exclusion status
	* @param $root string Start from this folder
	* @return array Directories and their status
	*/
	function getDirectory( $root ){
		// If there's no root directory specified, use the site's root
		$root = is_null($root) ? JPSiteRoot : $root ;

		// Initialize filter list
		$tempFilterArray = $this->getFilters();

		$FilterArray = array();
		foreach($tempFilterArray as $filter){
			$FilterArray[] = $this->ReplaceSlashes($filter);
		}

		// Initialize directories array
		$arDirs = array();

		// Get subfolders
		require_once("CFSAbstraction.php");
		$FS = new CFSAbstraction();

		$allFilesAndDirs = $FS->getDirContents( $root );

		if (!($allFilesAndDirs === false)) {
			foreach($allFilesAndDirs as $fileDef) {
				$fileName = $fileDef['name'];
				if ($fileDef['type'] == "dir") {
					$fileName = basename( $fileName );
					if (($this->ReplaceSlashes($root) == $this->ReplaceSlashes(JPSiteRoot)) && ( ($fileName == ".") || ($fileName == "..") )) {
					} else {
						if ($this->_filterArray == "") {
							$arDirs[$fileName] = false;
						} else {
							$arDirs[$fileName] = in_array($this->ReplaceSlashes($root . DIRECTORY_SEPARATOR . $fileName), $FilterArray);
						}
					}
				} // if
			} // foreach
		} // if

		ksort($arDirs);
		return $arDirs;
	}

	function modifyFilter($root, $dir, $checked){
		global $database;

		$activate = ($checked == "on") || ($checked == "yes") || ($checked == "checked") ? true : false;

		$sql = "SELECT `def_id` FROM #__jp_def WHERE `directory`=\"" . mysql_escape_string( $this->ReplaceSlashes($root . "/" . $dir) ) . "\"";
		$database->setQuery( $sql );
		$database->query();
		$def_id = $database->loadResult();

		if ($activate) {
			// Add the filter, if it doesn't exist
			if (is_null($def_id)) {
				$sql = "INSERT INTO #__jp_def (`directory`) VALUES (\"" . mysql_escape_string($this->ReplaceSlashes($root . "/" . $dir) ) . "\")";
				$database->setQuery( $sql );
				$database->query();
			}
		} else {
			// Remove the filter, if it exists
			$sql = "DELETE FROM #__jp_def WHERE `directory` = \"" . mysql_escape_string($this->ReplaceSlashes($root . "/" . $dir) ) . "\"";
			$database->setQuery( $sql );
			$database->query();
		}
	}

}
?>