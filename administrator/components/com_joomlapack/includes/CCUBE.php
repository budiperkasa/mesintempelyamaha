<?php

/**
 * CUBE
 *
 * This is the interface to the Componentized Universal Backup Engine of
 * JoomlaPack.
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

global $CUBE; // The CUBE object instance

class CCUBE {
	/** @var string Current domain of operation **/
	var $_currentDomain;

	/** @var string Current step **/
	var $_currentStep;

	/** @var string Current substep **/
	var $_currentSubstep;

	/** @var object Current engine object executing work **/
	var $_currentObject;

	/** @var boolean Indicates if we are done **/
	var $_isFinished;

	/** @var string The current error, if any **/
	var $_Error;

	/**
	 * Indicates if we should backup only the database (when true) or files + database (when false) 
	 *
	 * @var boolean
	 */
	var $_OnlyDBMode;

	/**
	* Creates a new instance of the CUBE object and empties the temporary
	* database tables
	*/
	function CCUBE( $OnlyDBMode = false )
	{
		global $database;
		
		$this->_OnlyDBMode = $OnlyDBMode;

		// Remove old entries from 'packvars' table
		$sql = "DELETE FROM #__jp_packvars WHERE `key` LIKE \"%CUBE%\"";
		$database->setQuery( $sql );
		$database->query();

		// Initialize internal variables
		$this->_currentDomain = "init";		// Current domain of operation
		$this->_currentObject = null;		// Nullify current object
		$this->_isFinished = false;
		$this->_Error = false;

		// Create a lock file
		global $JPConfiguration;
		$JPConfiguration->WriteDebugVar('CUBELock', time() );

		CJPLogger::ResetLog();
		CJPLogger::WriteLog(_JP_LOG_INFO, "--------------------------------------------------------------------------------");
		CJPLogger::WriteLog(_JP_LOG_INFO, "JoomlaPack");
		CJPLogger::WriteLog(_JP_LOG_INFO, "Your one for all backup solution");
		CJPLogger::WriteLog(_JP_LOG_INFO, "--------------------------------------------------------------------------------");

		global $JPConfiguration;
		if ($JPConfiguration->logLevel >= 3) {
			CJPLogger::WriteLog(_JP_LOG_INFO, "--- PHP Configuration Values ---" );
			CJPLogger::WriteLog(_JP_LOG_INFO, "PHP Version        :" . phpversion() );
			CJPLogger::WriteLog(_JP_LOG_INFO, "OS Version         :" . php_uname('s') );
			CJPLogger::WriteLog(_JP_LOG_INFO, "Safe mode          :" . ini_get("safe_mode") );
			CJPLogger::WriteLog(_JP_LOG_INFO, "Display errors     :" . ini_get("display_errors") );
			CJPLogger::WriteLog(_JP_LOG_INFO, "Disabled functions :" . ini_get("disable_functions") );
			CJPLogger::WriteLog(_JP_LOG_INFO, "open_basedir restr.:" . ini_get('open_basedir') );
			CJPLogger::WriteLog(_JP_LOG_INFO, "Max. exec. time    :" . ini_get("max_execution_time") );
			CJPLogger::WriteLog(_JP_LOG_INFO, "Memory limit       :" . ini_get("memory_limit") );
			if(function_exists("memory_get_usage"))
				CJPLogger::WriteLog(_JP_LOG_INFO, "Current mem. usage :" . memory_get_usage() );
			if(function_exists("gzcompress")) {
				CJPLogger::WriteLog(_JP_LOG_INFO, "GZIP Compression   : available (good)" );
			} else {
				CJPLogger::WriteLog(_JP_LOG_INFO, "GZIP Compression   : n/a (no compression)" );
			}

			CJPLogger::WriteLog(_JP_LOG_INFO, "--------------------------------------------------------------------------------");
		}

		@unlink( $JPConfiguration->OutputDirectory . '/errors.csv' );
		
		if ($this->_OnlyDBMode) {
			CJPLogger::WriteLog(_JP_LOG_INFO, "JoomlaPack is starting a new database backup");
		} else {
			CJPLogger::WriteLog(_JP_LOG_INFO, "JoomlaPack is starting a new full site backup");
		}
	}

	/**
	* The main workhorse, does all the job for us
	*/
	function tick(){
		if (!$this->_isFinished)
		{
			switch( $this->_runAlgorithm() ){
				case 0:
					// more work to do, return OK
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "CUBE :: More work required in domain '" . $this->_currentDomain);
					return $this->_storeCUBEArray();
					break;
				case 1:
					// Engine part finished
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "CUBE :: Domain '" . $this->_currentDomain . "' has finished");
					$this->_getNextObject();
					if ($this->_currentDomain == "finale") {
						// We have finished the whole process.
						$this->_cleanup();
						CJPLogger::WriteLog(_JP_LOG_DEBUG, "CUBE :: Just finished");
					}
					return $this->_storeCUBEArray();
					break;
				case 2:
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "CUBE :: Error occured in domain '" . $this->_currentDomain);
					// An error occured...
					$ret = $this->_storeCUBEArray();
					$this->_cleanup();
					return $ret;
					break;
			} // switch
		}
	}

	/**
	* Post work clean-up of files & database
	*/
	function _cleanup()
	{
		global $database, $JPConfiguration;

		CJPLogger::WriteLog(_JP_LOG_INFO, "Cleaning up");
		// Define which entries to keep in #__jp_packvars
		$keepInDB = array(
			"CUBEArray"
		);

		// Clean installation files
		// ---------------------------------------------------------------------
		CCUBE::_unlinkRecursive($JPConfiguration->TempDirectory . "/installation");
		// Clean db backup files
		// TODO : Should make this a function common with CDBBackupEngine...
		$folderPath = $JPConfiguration->TempDirectory;
		if ($JPConfiguration->AltInstaller->SQLDumpMode == "split") {
			$file1 = $folderPath . "/joomla.sql";
			$file2 = $folderPath . "/sample_data.sql";
		} else {
			$file1 = $folderPath . "/" .$JPConfiguration->AltInstaller->BaseDump;
			$file2 = $folderPath . "/" . $JPConfiguration->AltInstaller->BaseDump;
		}

		CCUBE::_unlinkRecursive($file1);
		CCUBE::_unlinkRecursive($file2);

		// Clean database (also removes lock file)
		// ---------------------------------------------------------------------
		$sql = "SELECT `key` FROM #__jp_packvars";
		$database->setQuery( $sql );
		$keys = $database->loadResultArray();

		foreach($keys as $key){
			if (!in_array( $key, $keepInDB )) {
				$JPConfiguration->DeleteDebugVar( $key );
			}
		}

		unset( $keys );
	}

	/**
	* Recursively deletes file inside a directory
	* @param string $dirName Directory to delete
	*/
	function _unlinkRecursive( $dirName ) {
		// Fix 1.1.1: require_once now refers an absolute path to avoid problems with open_basedir restrictions (by nicholas)
		require_once dirname(__FILE__) . "/CFSAbstraction.php";
		$FS = new CFSAbstraction();

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Recursively unlinking $dirName");

		if (is_file( $dirName )) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Unlinking $dirName - <b>THIS IS A FILE, NOT A DIR</b>");
			@unlink( $dirName );
		} elseif (is_dir( $dirName )) {
			// Get the contents of the directory
			$fileList = $FS->getDirContents( $dirName );
			if ($fileList === false) {
				// A non-browsable directory.
				CJPLogger::WriteLog(_JP_LOG_WARNING, "Can't delete directory $dirName. Check permissions.");
			} else {
				foreach($fileList as $fileDescriptor) {
					switch($fileDescriptor['type']) {
						case "dir":
							CCUBE::_unlinkRecursive( $dirName . "/" . $fileDescriptor['name'] );
							break;
						case "file":
							@unlink( $dirName . "/" . $fileDescriptor['name'] );
							break;
						// All other types (links, character devices etc) are ignored.
					}
				}
				@unlink( $dirName );
			}
		}
	}

	/**
	* Single step algorithm. Runs the tick() function of the $_currentObject
	* until it finishes or produces an error, then returns the result array.
	* @return integer 1 if we finished correctly, 2 if error occured.
	*/
	function _algoSingleStep()
	{
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Single Stepping");
		$finished = false;
		$error = false;

		while( (!$finished) ){
			$result = $this->_currentObject->tick();
			$this->_currentDomain = $result['Domain'];
			$this->_currentStep = $result['Step'];
			$this->_currentSubstep = $result['Substep'];
			if(isset($result['Error'])) $error = !($result['Error'] == "");//joostina pach
			$finished = $error ? true : !($result['HasRun']);

			$this->_storeCUBEArray();
		} // while

		if (!$error) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Successful Fast algorithm on " . $this->_currentDomain);
		} else {
			CJPLogger::WriteLog(_JP_LOG_ERROR, "Failed Fast algorithm on " . $this->_currentDomain);
			CJPLogger::WriteLog(_JP_LOG_ERROR, $result['Error']);
		}
		$this->_Error = $error ? $result['Error'] : "";
		return $error ? 2 : 1;
	}

	/**
	* Multi-step algorithm. Runs the tick() function of the $_currentObject once
	* and returns.
	* @return integer 0 if more work is to be done, 1 if we finished correctly,
	* 2 if error eccured.
	*/
	function _algoMultiStep()
	{
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Multiple Stepping");
		$error = false;

		$result = $this->_currentObject->tick();
		$this->_currentDomain = $result['Domain'];
		$this->_currentStep = $result['Step'];
		$this->_currentSubstep = $result['Substep'];
		$error = !($result['Error'] == "");
		$finished = $error ? true : !($result['HasRun']);

		$this->_storeCUBEArray();

		if (!$error) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Successful Slow algorithm on " . $this->_currentDomain);
		} else {
			CJPLogger::WriteLog(_JP_LOG_ERROR, "Failed Slow algorithm on " . $this->_currentDomain);
			CJPLogger::WriteLog(_JP_LOG_ERROR, $result['Error']);
		}
		$this->_Error = $error ? $result['Error'] : "";
		return $error ? 2 : ( $finished ? 1 : 0 );
	}

	/**
	* Smart step algorithm. Runs the tick() function until we have consumed 75%
	* of the maximum_execution_time (minus 1 seconds) within this procedure. If
	* the available time is less than 1 seconds, it defaults to multi-step.
	* @return integer 0 if more work is to be done, 1 if we finished correctly,
	* 2 if error eccured.
	*/
	function _algoSmartStep()
	{
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Smart Stepping");

		// Get the maximum execution time
		$maxExecTime = ini_get("maximum_execution_time");
		$startTime = $this->_microtime_float();
		if ( ($maxExecTime == "") || ($maxExecTime == 0) ) {
			// If we have no time limit, set a hard limit of 30 secs (safe for Apache and IIS timeouts)
			$maxExecTime = 30;

			// Used to equate this with Single Stepping
			//return $this->_algoSingleStep();
		}

		if ( $maxExecTime <= 1.75 ) {
			// If the available time is less than the trigger value, switch to
			// multi-step
			return $this->_algoMultiStep();
		} else {
			// All checks passes, this is a SmartStep-enabled case
			$maxRunTime = ($maxExecTime - 1) * 0.75;
			$runTime = 0;
			$finished = false;
			$error = false;

			// Loop until time's up, we're done or an error occured
			while( ($runTime <= $maxRunTime) && (!$finished) && (!$error) ){
				$result = $this->_currentObject->tick();
				$this->_currentDomain = $result['Domain'];
				$this->_currentStep = $result['Step'];
				$this->_currentSubstep = $result['Substep'];
				if(isset($result['Error'])) $error = !($result['Error'] == "");
				$finished = $error ? true : !($result['HasRun']);

				$this->_storeCUBEArray();

				$endTime = $this->_microtime_float();
				$runTime = $endTime - $startTime;
			} // while

			// Return the result
		if (!$error) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Successful Smart algorithm on " . $this->_currentDomain);
		} else {
			CJPLogger::WriteLog(_JP_LOG_ERROR, "Failed Smart algorithm on " . $this->_currentDomain);
			CJPLogger::WriteLog(_JP_LOG_ERROR, $result['Error']);
		}
			$this->_Error = $error ? $result['Error'] : "";
			return $error ? 2 : ( $finished ? 1 : 0 );
		}
	}

	/**
	* Runs the user-selected algorithm for the current engine
	*/
	function _runAlgorithm(){
		$algo = $this->_selectAlgorithm();
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "I have chosen $algo algorithm for " . $this->_currentDomain);

		switch( $algo ){
			case "single":
				// Single-step algorithm - fast but leads to timeouts in medium / big sites
				return $this->_algoSingleStep();
				break;
			case "multi":
				// Multi-step algorithm - slow but most compatible
				return $this->_algoMultiStep();
				break;
			case "smart":
				// SmartStep algorithm - best compromise between speed and compatibility
				return $this->_algoSmartStep();
				break;
			default:
				// No algorithm (null algorithm) for "init" and "finale" domains. Always returns success.
				//return $this->_isFinished ? 1 : 0;
				return 1;
		} // switch
	}

	/**
	* Selects the algorithm to use based on the current domain
	* @return string The algorithm to use
	*/
	function _selectAlgorithm(){
		global $JPConfiguration;
		switch( $this->_currentDomain )
		{
			case "init":
			case "finale":
				return "(null)";
				break;
			case "FileList":
				return $JPConfiguration->fileListAlgorithm;
				break;
			case "PackDB":
				return $JPConfiguration->dbAlgorithm;
				break;
			case "Packing":
				return $JPConfiguration->packAlgorithm;
				break;
			case "InstallerDeployment":
				return "single";
				break;
		}
	}

	/**
	* Returns the current microtime as a float
	*/
	function _microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}

	/**
	* Creates the next engine object based on the current execution domain
	* @return integer 0 = success, 1 = all done, 2 = error
	*/
	function _getNextObject(){
		// Kill existing object
		$this->_currentObject = null;
		// Try to figure out what object to spawn next
		switch( $this->_currentDomain )
		{
			case "init":
				// Next domain : Filelist creation
				if ($this->_OnlyDBMode) {
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> Database backup");
					// Next domain : Database backup
					require_once("CDBBackupEngine.php");
					$this->_currentObject = new CDBBackupEngine( $this->_OnlyDBMode );
					$this->_currentDomain = "PackDB";
				} else {
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> Filelist");
					require_once("CFilelistEngine.php");
					$this->_currentObject = new CFilelistEngine();
					$this->_currentDomain = "FileList";
				}
				return 0;
				break;
			case "FileList":
				// Next domain : Installer Deployment
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> Installer Deployment");
				require_once("CInstallerDeploymentEngine.php");
				$this->_currentObject = new CInstallerDeploymentEngine();
				$this->_currentDomain = "InstallerDeployment";
				return 0;
				break;
			case "InstallerDeployment":
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> Database backup");
				// Next domain : Database backup
				require_once("CDBBackupEngine.php");
				$this->_currentObject = new CDBBackupEngine();
				$this->_currentDomain = "PackDB";
				return 0;
				break;
			case "PackDB":
				if ($this->_OnlyDBMode) {
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> finale");
					// Next domain : none (done)
					$this->_currentDomain = "finale";
					return 1;
				} else {
					CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> Packing");
					// Next domain : File packing
					require_once("CPackerEngine.php");
					$this->_currentObject = new CPackerEngine();
					$this->_currentDomain = "Packing";
					return 0;
				}
				break;
			case "Packing":
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain --> finale");
				// Next domain : none (done)
				$this->_currentDomain = "finale";
				return 1;
				break;
			case "finale":
			default:
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Next domain not applicable; already on 'finale'");
				return 1;
				break;
		}
	}

	/**
	* Creates the CUBE return array
	* @return array A CUBE return array with timestamp data
	*/
	function _makeCUBEArray(){
		$ret['HasRun'] = $this->_isFinished ? 0 : 1;
		$ret['Domain'] = $this->_currentDomain;
		$ret['Step'] = htmlentities( $this->_currentStep );
		$ret['Substep'] = htmlentities( $this->_currentSubstep );
		$ret['Error'] = htmlentities( $this->_Error );
		$ret['Timestamp'] = $this->_microtime_float();
		return $ret;
	}

	/**
	* Stores the CUBE return array to database
	* @return array The CUBE array we stored in the database
	*/
	function _storeCUBEArray(){
		global $JPConfiguration;
		$ret = $this->_makeCUBEArray();
		$serialized = serialize( $ret );
		$JPConfiguration->WriteDebugVar( "CUBEArray", $serialized, true);
		unset( $serialized );
		return $ret;
	}
}

/**
* Tries to load and unserialize a CUBE object from the database. If it fails, it
* creates a new object
*/
function loadJPCUBE( $forceNew = false ){
	global $database, $JPConfiguration, $CUBE;

	if ( $forceNew ) {
		$CUBE = new CCUBE();
	} else {
		// Search for CUBEObject entry in database
		$sql = "SELECT COUNT(*) FROM #__jp_packvars WHERE `key`='CUBEObject'";
		$database->setQuery( $sql );
		$numRecords = $database->loadResult();

		if ($numRecords < 1) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "No CUBE exists; creating new");
			$CUBE = new CCUBE();
		} else {
			// First, we need to see if we have to include an Engine class
			$cubeArray = loadJPCUBEArray();
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Found current domain " . $cubeArray['Domain']);
			switch( $cubeArray['Domain'] )
			{
				case "FileList":
					require_once(JPComponentRoot . "/includes/CFilelistEngine.php");
					break;
				case "InstallerDeployment":
					require_once(JPComponentRoot . "/includes/CInstallerDeploymentEngine.php");
					break;
				case "PackDB":
					require_once(JPComponentRoot . "/includes/CDBBackupEngine.php");
					break;
				case "Packing":
					require_once(JPComponentRoot . "/includes/CPackerEngine.php");
					break;
			}
			// Now, resume the CUBE object
			$serializedCUBE = $JPConfiguration->ReadDebugVar("CUBEObject", true);
			$CUBE = unserialize($serializedCUBE);
			unset( $serializedCUBE );
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "Unserialized existing CUBE");
		}
	}
}

/**
* Stores the current CUBE object to the database
*/
function saveJPCUBE(){
	global $JPConfiguration, $database, $CUBE;

	$CUBE->_storeCUBEArray();
	$serializedCUBE = serialize( $CUBE );
	$JPConfiguration->WriteDebugVar( "CUBEObject", $serializedCUBE, true);
	unset( $serializedCUBE );
	unset( $CUBE );
}

/**
* Returns the current CUBE Array from the database
*/
function loadJPCUBEArray(){
	global $database, $JPConfiguration, $CUBE;

	// Search for CUBEObject entry in database
	$sql = "SELECT COUNT(*) FROM #__jp_packvars WHERE `key`='CUBEArray'";
	$database->setQuery( $sql );
	$numRecords = $database->loadResult();

	if ($numRecords < 1) {
		if (is_object( $CUBE )) {
			$ret = $CUBE->_storeCUBEArray();
		} else {
			$ret = "finale";
		}
	} else {
		$sql = "SELECT `value2` FROM #__jp_packvars WHERE `key`='CUBEArray'";
		$database->setQuery( $sql );
		$serializedArray = $database->loadResult();
		$ret = unserialize( $serializedArray );
		unset( $serializedArray );
	}

	return $ret;
}

// Code to detect and log timeouts
function deadOnTimeOut()
{
	if( connection_status() >= 2 ) {
		CJPLogger::WriteLog(_JP_LOG_ERROR, "JoomlaPack has timed out. Please read the documentation.");
	}
}
register_shutdown_function("deadOnTimeOut");
?>