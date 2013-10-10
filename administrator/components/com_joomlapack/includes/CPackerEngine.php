<?php

/**
 * Packing engine
 *
 * Takes care of putting gathered files (the file list) into an archive.
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

class CPackerEngine {
	/**
     * Have we finished processing our task?
     * @access private
     * @var boolean
     */
	var $_isFinished;

	/**
     * Full pathname to the archive file
     * @access private
     * @var string
     */
	var $_archiveFile;

	/**
	* Maximum fragment number
	* @access private
	* @var long
	*/
	var $_maxFragment;

	/**
	* Current fragment number
	* @access private
	* @var long
	*/
	var $_currentFragment;

	/**
	* Active file list descriptor
	* @access private
	* @var array
	*/
	var $_fileListDescriptor;

	/**
	* Total size of file lists
	* @access private
	* @var long
	*/
	var $_totalBytes;

	/**
	* Total size processed so far
	* @access private
	* @var long
	*/
	var $_currentBytes;

	function CPackerEngine(){
		global $JPConfiguration, $database;

		$this->_isFinished = false;
		$this->_archiveFile = $JPConfiguration->OutputDirectory . "/" . $this->_expandTarName( $JPConfiguration->TarNameTemplate, $JPConfiguration->boolCompress );
		$this->_currentFragment = 0;
		$this->_totalBytes = 0;
		$this->_currentBytes = 0;

		$sql = "SELECT * FROM #__jp_packvars WHERE `key` like 'fragment%'";
		$database->setQuery( $sql );
		$database->query();
		$this->_maxFragment = $database->getNumRows();
		for( $i=1; $i <= $this->_maxFragment; $i++ ) {
			$sql = "SELECT `value2` FROM #__jp_packvars WHERE `key` = 'fragment". $i ."'";
			$database->setQuery( $sql );
			$serialized = $database->loadResult();
			$descriptor = unserialize($serialized);
			$this->_totalBytes += $descriptor['size'];
			unset($descriptor);
		}

		// Remove any stored compression object
		$JPConfiguration->DeleteDebugVar( 'zipobject' );

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CPackerEngine :: new instance");
	}

	/**
	* Try to execute the business logic of this step
	*/
	function tick(){
		global $JPConfiguration;

		if ($this->_isFinished) {
			// We have already finished
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "CPackerEngine :: Already finished");
			$returnArray = array();
			$returnArray['HasRun'] = false;
			$returnArray['Domain'] = "Packing";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			// Also remove stored compression object, if exists
			$JPConfiguration->DeleteDebugVar( 'zipobject' );
			return $returnArray; // Indicate we have finished
		} else {
			// Try to pack next fragment
			$this->_currentFragment++;
			if ($this->_currentFragment > $this->_maxFragment) {
				CJPLogger::WriteLog(_JP_LOG_INFO, "Finalizing archive");
				// We have just finished, as we ended up on one fragment past the end. Glue archive and return.
				$this->_fileListDescriptor['files'] = null;
				$ret = $this->_archiveFileList();

				$this->_isFinished = true;
				$returnArray = array();
				$returnArray['HasRun'] = true;
				$returnArray['Domain'] = "Packing";
				$returnArray['Step'] = "";
				$returnArray['Substep'] = "";
				return $returnArray; // Indicate we have finished
			} else {
				CJPLogger::WriteLog(_JP_LOG_INFO, "Archiving fragment #" . $this->_currentFragment);
				$this->_importFragment( $this->_currentFragment );
				$this->_archiveFileList();
				$returnArray = array();
				$returnArray['HasRun'] = true;
				$returnArray['Domain'] = "Packing";
				$returnArray['Step'] = $this->_currentFragment;
				$this->_currentBytes += $this->_fileListDescriptor['size'];
				$returnArray['Substep'] = $this->_currentBytes . " / " . $this->_totalBytes;
				return $returnArray; // Indicate we have finished
			}
		}
	}

	/**
	* Loads a fragment's filelist
	*/
	function _importFragment( $fragmentID ){
		global $database;


		$sql = "SELECT `value2` FROM #__jp_packvars WHERE `key` = 'fragment" . $fragmentID . "'";
		$database->setQuery( $sql );
		$this->_fileListDescriptor = unserialize($database->loadResult());
		if ($this->_fileListDescriptor === false) {
			return false;
		} else {
			return true;
		}
	}

	/**
	* Returns the path to trim and the path to add to the fragment's files
	*/
	function _getPaths( $fragmentType ){
		global $JPConfiguration;

		$retArray = array();
		switch($fragmentType){
			case "site":
				$retArray['remove'] = $JPConfiguration->TranslateWinPath( JPSiteRoot );
				$retArray['add'] = "";
				break;
			case "installation":
				$filePath = $JPConfiguration->TranslateWinPath( $JPConfiguration->TempDirectory . "/installation/" );
				$retArray['remove'] = $filePath;
				$retArray['add'] = "installation";
				break;
			case "sql":
				$retArray['remove'] = $JPConfiguration->TranslateWinPath( $JPConfiguration->TempDirectory );
				$retArray['add'] = "installation/sql";
				break;
			// case "external":
			// TODO - Handle forcibly included directories (later versions will do that...)
		} // switch

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Fragment type is '$fragmentType'");
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "  path to remove : " . $retArray['remove']);
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "  path to add    : " . $retArray['add']);
		return $retArray;
	}

	/**
	* Performs the actual archiving of the current file list
	*/
	function _archiveFileList(){
		global $JPConfiguration, $database;

		// Include the necessary library
		require_once( JPComponentRoot . "/includes/CZIPCreator.php" );

		// Check for existing instance of the object stored in db
		$sql = "SELECT COUNT(*) FROM #__jp_packvars WHERE `key`='zipobject'";
		$database->setQuery( $sql );
		$numRows = $database->loadResult();

		if( $numRows == 0 ) {
			// Create new
			// TODO : Chech $JPConfiguration->boolCompress and create relevant archive
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Creating new instance of zipobject");
			$archive = new CZIPCreator( $this->_archiveFile, $JPConfiguration->TempDirectory, false );
		} else {
			// Load from db
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Loading saved instance of zipobject");
			$sql = "SELECT value2 FROM #__jp_packvars WHERE `key`='zipobject'";
			$database->setQuery( $sql );
			$serialized = $database->loadResult();
			$archive = unserialize( $serialized );
			unset( $serialized );
		}

		// Get paths to add / remove
		$pathsAddRemove = $this->_getPaths( $this->_fileListDescriptor['type'] );

		// Add files to archive, or finalize archive
		if( is_array($this->_fileListDescriptor['files']) ) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Adding files to archive");
			// Add files to archive
			$archive->addFileList( $this->_fileListDescriptor['files'], $pathsAddRemove['remove'], $pathsAddRemove['add'] );

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Storing zipobject");
			// Store object
			$serialized = serialize( $archive );
			$JPConfiguration->WriteDebugVar( 'zipobject', $serialized, true );
			unset( $serialized );
			unset( $archive );
		} else {
			// Finalize archive
			$archive->glueZIPFile();
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Archive is finalized");
		}

	}

	/**
	* Transforms a naming template to the final name of the archive by parsing template
	* tags within the name.
	* @param string $templateName The naming template
	* @param boolean $boolCompress "tgz" if the archive should be compressed (and thus have .tar.gz extension),
	* "tar" for not (and thus have a .tar extension) or "zip" for a .zip file.
	*/
	function _expandTarName( $templateName ){
		global $JPConfiguration;
		// Get the proper extension
		switch($JPConfiguration->boolCompress){
			case "zip":
				$extension = ".zip";
				break;
			case "jpa":
				$extension = ".jpa";
				break;
		} // switch

		$time = localtime(time(), true);
		
		// Parse [DATE] tag -- [^] 1.1.1 - Use local time
		$dateExpanded = substr($time['tm_year'],1,2) . str_pad($time['tm_mon'] + 1,2,"0",STR_PAD_LEFT) . str_pad($time['tm_mday'],2,'0',STR_PAD_LEFT);
		$templateName = str_replace("[DATE]", $dateExpanded, $templateName);

		// Parse [TIME] tag -- [^] 1.1.1 - Use local time
		$timeExpanded = str_pad($time['tm_hour'],2,'0',STR_PAD_LEFT) . str_pad($time['tm_min'],2,'0',STR_PAD_LEFT) . str_pad($time['tm_sec'],2,'0',STR_PAD_LEFT);
		$templateName = str_replace("[TIME]", $timeExpanded, $templateName);

		// Parse [HOST] tag
		$templateName = str_replace("[HOST]", $_SERVER['SERVER_NAME'], $templateName);

		return $templateName . $extension;
	}

}
?>