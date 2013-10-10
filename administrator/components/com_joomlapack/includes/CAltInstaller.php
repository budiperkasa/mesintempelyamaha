<?php

/**
 * Alternate installers class
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

require_once( JPSiteRoot . '/includes/domit/xml_domit_lite_include.php' );

class CAltInstaller {
	/** @var string Short name of the installer */
	var $Name;

	/** @var string Package file, wihout path */
	var $Package;

	/** @var string List of installer files */
	var $fileList;

	/** @var string Dump mode for the SQL data (split, one) */
	var $SQLDumpMode;

	/** @var string Filename of the unified or table definition dump, relative to installer root */
	var $BaseDump;

	/** @var string Filename of the data dump, relative to installer root */
	var $SampleDump;

	/**
	* Loads a definition file.
	* @param string The name of the file you want to load. Relative to 'installers' directory.
	* @return boolean True if loaded successful the file
	*/
	function loadDefinition( $file ){
		// Instanciate new parser object
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors( true );
		// Load XML file
		if (!$xmlDoc->loadXML( JPComponentRoot . "/installers/" . $file , false, true ))
		{
			return false;
		}
		$root = &$xmlDoc->documentElement;

		// Check if it is a valid description file
		if ($root->getTagName() != 'jpconfig') {
			return false;
		} elseif ($root->getAttribute( 'type' ) != 'installpack' ) {
			return false;
		}

		// Set basic elements
		$e = &$root->getElementsByPath('name', 1);
		$this->Name = $e->getText();
		$e = &$root->getElementsByPath('package', 1);
		$this->Package = $e->getText();
		$sqlDumpRoot = &$root->getElementsByPath('sqldump', 1);
		$this->SQLDumpMode = $sqlDumpRoot->getAttribute( 'mode' );

		// Get SQL filenames
		if ($sqlDumpRoot->hasChildNodes()) {
			$e = $sqlDumpRoot->getElementsByPath('basedump', 1);
			if ( !is_null($e) ) {
				$this->BaseDump = $e->getText();
			} else {
				$this->BaseDump = "";
			}

			$e = $sqlDumpRoot->getElementsByPath('sampledump', 1);
			if ( !is_null($e) ) {
				$this->SampleDump = $e->getText();
			} else {
				$this->SampleDump = "";
			}
		}

		// Get file list
		$this->fileList = array();
		$flRoot = &$root->getElementsByPath('filelist',1);
		if (!is_null($flRoot)) {
			if ($flRoot->hasChildNodes()) {
				$files = $flRoot->childNodes;
				foreach($files as $file){
					$this->fileList[] = $file->getText();
				}
			}
		}

		return true;
	}

	/**
	* Loads all installer definition files
	* @return array An array of the installer names and packages
	*/
	function loadAllDefinitions() {
		require_once JPComponentRoot . "/includes/CFSAbstraction.php";
		$FS = new CFSAbstraction;

		$defs = array();

		$fileList = $FS->getDirContents(JPComponentRoot . "/installers/", "*.xml");
		
		foreach($fileList as $fileDef){
			$file = $fileDef['name'];
			$baseName = basename( $file );
			if ($this->loadDefinition( $baseName )) {
				$newDef['name'] = $this->Name;
				$newDef['package'] = $this->Package;
				$newDef['meta'] = $baseName;
				$defs[] = $newDef;
			}
		}

		return $defs;
	}
}
?>