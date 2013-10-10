<?php

/**
 * File List Engine
 *
 * Directory traversal engine to gather a file list. Unlike older versions, it
 * uses much less queries, removing load off your server and increasing the
 * speed of processing.
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

// Global variables
class CFilelistEngine {
	/**
     * Directories to exclude
     * @access private
     * @var array
     */
	var $_ExcludeDirs;

	/**
     * Have we finished processing our task?
     * @access private
     * @var boolean
     */
	var $_isFinished;

	/**
     * The next directory to scan
     * @access private
     * @var string
     */
	var $_nextDirectory;

	/**
     * The number of the current node (fragment)
     * @access private
     * @var long
     */
	var $_currentNode;

	/**
     * The size of the current node (fragment) in bytes
     * @access private
     * @var long
     */
	var $_currentNodeSize;

	/**
     * The list of branch nodes (directories not yet scanned for files)
     * @access private
     * @var array
     */
	var $_branchNodes;

	/**
     * Holds the list of files of the current fragment
     * @access private
     * @var array
     */
	var $_currentList;

	/**
	* Public constructor CFileListEngine
	* When the object is generated, it takes care of removing old entries and
	* initializing this task's algorithm
	*/
	function CFilelistEngine(){
		global $database;

		// Remove old entries from filelist database
		$sql = "DELETE FROM #__jp_packvars WHERE `key` like 'fragment%'";
		$database->setQuery( $sql );
		$database->query();

		// Get the directory exclusion filters - this only needs to be done once
		$this->_createExcludeDirs();

		// Initialize our variables
		$this->_isFinished = false; // We have not finished yet
		// FIX 1.1.0 $mosConfig_absolute_path may contain trailing slashes or backslashes incompatible with exclusion filters
		$this->_nextDirectory = realpath(JPSiteRoot); // Start scanning from Joomla! root
		$this->_currentNode = 1; // We start adding to the first fragment
		$this->_currentNodeSize = 0; // The size of this fragment is 0 bytes, as no files are added yet
		$this->_branchNodes = array();
		$this->_currentList = array();

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CFilelistEngine :: New instance");
	}

	/**
	* Scans the next directory if we have not finished
	*/
	function tick(){
		if ($this->_isFinished) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "CFilelistEngine :: Already finished");
			$returnArray = array();
			$returnArray['HasRun'] = false;
			$returnArray['Domain'] = "FileList";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		} else {
			// Process the next directory
			$this->_recurseDirectory( $this->_nextDirectory );

			// Get the next directory, or mark ourselves as finished
			$nextDir = $this->_getNextDirectory();
			if ($nextDir === false) {
				$this->_isFinished = true;
				// 30.08.2007 : Add finally save fragment - start fix by dimon(www.izumrud.com.ua;webstudio@ukr.net)
				if ($this->_currentNodeSize > 0 ){
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Saving fragment #" . $this->_currentNode);
				// Save current fragment
				$this->_saveFragment();
				// Start a new fragment
				$this->_currentList = array();
				$this->_currentNode++;
				$this->_currentNodeSize = 0;
				}
				// 30.08.2007 --- end fix by dimon(www.izumrud.com.ua;webstudio@ukr.net)
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "CFilelistEngine :: Finished");
			} else {
				$this->_nextDirectory = $nextDir;
			}

			// Return TRUE, indicating we have done some work
			$returnArray = array();
			$returnArray['HasRun'] = true;
			$returnArray['Domain'] = "FileList";
			$returnArray['Step'] = $nextDir;
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		}
	}

	function _recurseDirectory( $dirName ){
		global $JPConfiguration;
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Recursing into " . $dirName);

		require_once( "CFSAbstraction.php" );
		$FS = new CFSAbstraction();

		if (in_array( $dirName, $this->_ExcludeDirs )) {
			CJPLogger::WriteLog(_JP_LOG_INFO, "Skipping directory $dirName");
			return;
		}

		// Get the contents of the directory
		$fileList = $FS->getDirContents( $dirName );

		if (!is_readable($dirName)) {
			// A non-browsable directory
			CJPLogger::WriteLog(_JP_LOG_WARNING, "Unreadable directory $dirName. Check permissions.");
		}

		if ($fileList === false) {
			// A non-browsable directory; however, it seems that I never get FALSE reported here?!
			CJPLogger::WriteLog(_JP_LOG_WARNING, "Unreadable directory $dirName. Check permissions.");
		} else {
			// Initialize local processed files counter
			$processedFiles = 0;
			// Scan all directory entries
			foreach($fileList as $fileDescriptor) {
				switch($fileDescriptor['type']) {
					case "dir":
						// A new directory found. Mark it for recursion
						if (!( ( substr($fileDescriptor['name'], -1, 1) == "." ) || ( substr($fileDescriptor['name'], -1, 2) == ".." ) )) {
							$this->_branchNodes[] = $fileDescriptor['name'];
							$processedFiles++;
							CJPLogger::WriteLog(_JP_LOG_DEBUG, "Adding directory " . $fileDescriptor['name']);
						}
						break;
					case "file":
						// Just a file... process it.
						$processedFiles++;
						$filesize = $fileDescriptor['size'];
						if (($this->_currentNodeSize + $filesize <= 1048576) && (count($this->_currentList) < 100) ) {
							// It fits in the current fragment (max 1Mb or up to 100 files)
							$this->_currentNodeSize += $filesize;
						} else {
							CJPLogger::WriteLog(_JP_LOG_DEBUG, "Saving fragment #" . $this->_currentNode);
							// Save current fragment
							$this->_saveFragment();
							// Start a new fragment
							$this->_currentList = array();
							$this->_currentNode++;
							$this->_currentNodeSize = 0;
						}
						$this->_currentList[] = $fileDescriptor['name'];
						//CJPLogger::WriteLog(_JP_LOG_DEBUG, "Added file " . $fileDescriptor['name'] . "(" . $fileDescriptor['size'] . " bytes )");
						break;
					// All other types (links, character devices etc) are ignored.
				}
			}
			// Check for empty directories and add them to the list
			if ( $processedFiles == 0 ) {
				$this->_currentList[] = $dirName;
				CJPLogger::WriteLog(_JP_LOG_INFO, "Empty directory $dirName");
			}
		}
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Done recursing $dirName");
	}

	function _saveFragment(){
		global $database, $JPConfiguration;

		$fragmentDescriptor = array();
		$fragmentDescriptor['type'] = "site"; // Other possible values are 'installer', 'sql', 'external'
		$fragmentDescriptor['size'] = $this->_currentNodeSize;
		$fragmentDescriptor['files'] = $this->_currentList;

		$serializedDescriptor = serialize($fragmentDescriptor);
		unset($fragmentDescriptor);

		$sql = "INSERT INTO #__jp_packvars (`key`, value2) VALUES (\"" . mysql_escape_string( "fragment" . $this->_currentNode ) . "\", \"" .mysql_escape_string( $serializedDescriptor ) . "\")";
		$database->setQuery( $sql );
		$database->query();

		unset($serializedDescriptor);
	}

	function _getNextDirectory(){
		if (count($this->_branchNodes) == 0) {
			return false;
		} else {
			return array_shift( $this->_branchNodes );
		}
	}

	/**
	* Returns the array of the exclusion filters
	* TODO: Probably I should pass a reference to the CDirExclusion object instead of this
	*/
	function _createExcludeDirs() {
		require_once(JPComponentRoot . "/includes/CDirExclusionFilter.php");

		$def = new CDirExclusionFilter();
		$this->_ExcludeDirs = $def->getFilters();
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Got exclusion filters from database");
		unset($def);
	}

}
?>