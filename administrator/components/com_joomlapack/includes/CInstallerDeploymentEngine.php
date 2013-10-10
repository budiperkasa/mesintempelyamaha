<?php

/**
 * Installer deployment engine
 *
 * Deploys the installer to a temporary directory and adds its files to the
 * global file list.
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

class CInstallerDeploymentEngine {

	/**
     * Have we finished processing our task?
     * @access private
     * @var boolean
     */
	var $_isFinished;

	/**
     * Where we should put our copy of the installer
     * @access private
     * @var string
     */
	var $_targetDirectory;

	/**
     * The path to the compressed installer image
     * @access private
     * @var string
     */
	var $_tarName;

	/**
     * The list of installer files
     * @access private
     * @var array
     */
	var $_fileList;

	/**
	* Constructor for the CInstallerDeploymentEngine class
	*/
	function CInstallerDeploymentEngine(){
		global $JPConfiguration;

		// Indicate we've not finished
		$this->_isFinished = false;

		// Put default values from configuration
		$this->_targetDirectory = $JPConfiguration->TempDirectory;
		$this->_tarName = $JPConfiguration->_InstallationRoot . "/installers/" . $JPConfiguration->AltInstaller->Package;

		// Create the file list of the installer files
		$filePath = $this->_targetDirectory . "/installation/";
		$filePath = str_replace("\\\\", "/", $filePath);
		$filePath = str_replace("\\", "/", $filePath);

		$this->_fileList = array();
		$AltInstaller = $JPConfiguration->AltInstaller;
		foreach( $AltInstaller->fileList as $fileName ) {
			$this->_fileList[] = $filePath . $fileName;
		}

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CInstallerDeploymentEngine :: new instance");
	}

	/**
	* Try to execute the business logic of this step
	*/
	function tick(){
		if ($this->_isFinished) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "CInstallerDeploymentEngine :: Already finished");
			$returnArray = array();
			$returnArray['HasRun'] = false;
			$returnArray['Domain'] = "InstallerDeployment";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		} else {
			global $database;
			
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Extracting installer");
			if (defined('_JEXEC')){
				$testpath = JPATH_ROOT  . '/libraries/PEAR/PEAR.php';
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "$testpath");
			}
			// Include TAR file suport
			
			if (!defined('_JEXEC')) {
				require_once( JPSiteRoot . "/includes/PEAR/PEAR.php" );
				require_once( JPSiteRoot . "/includes/Archive/Tar.php" );
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Included TAR file support");
			}
			if (defined('_JEXEC')) {
				require_once( JPATH_ROOT . "/libraries/pear/archive_tar/Archive_Tar.php" );
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Included TAR file support");
			}
			
			// Remove any leftover files
			global $CUBE;
			$CUBE->_unlinkRecursive( $this->_targetDirectory . "/installation" );

			// Extract the installer image
			$tar = new Archive_Tar( $this->_tarName, 'gz' );
			$tar->extract( $this->_targetDirectory );

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Done extracting installer");
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Creating fragment for installer");

			// Get the current node number in file list db table and start adding
			// to the next fragment
			$sql = "SELECT COUNT(*) FROM #__jp_packvars WHERE `key` like 'fragment%'";
			$database->setQuery( $sql );
			$database->query();
			$currentNode = $database->loadResult() + 1;

			// Add the installer files to the file list stored in db; all files
			// are added as 'Branch 2' entries, indicating a different subpath
			// from the rest of the filelist has to be removed from them.
			$fileList = array();
			$fragmentSize = 0;
			foreach($this->_fileList as $fileName){
				$filesize = filesize( $fileName );
				$fragmentSize += $filesize;
				$fileList[] = $fileName;
			}
			$fragmentDescriptor = array();
			$fragmentDescriptor['type'] = "installation"; // Other possible values are 'site', 'sql', 'external'
			$fragmentDescriptor['size'] = $fragmentSize;
			$fragmentDescriptor['files'] = $fileList;

			$serializedDescriptor = serialize($fragmentDescriptor);
			unset($fragmentDescriptor);
			unset($fileList);
			$sql = "INSERT INTO #__jp_packvars (`key`, value2) VALUES ('fragment" . $currentNode . "', \"" . mysql_escape_string($serializedDescriptor) . "\")";
			$database->setQuery( $sql );
			$database->query();
			unset($serializedDescriptor);

			// Indicate we have finished
			$this->_isFinished = true;

			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Done creating fragment for installer");

			// return information
			$returnArray = array();
			$returnArray['HasRun'] = true;
			$returnArray['Domain'] = "InstallerDeployment";
			$returnArray['Step'] = "";
			$returnArray['Substep'] = "";
			return $returnArray; // Indicate we have finished
		}
	}

}

?>