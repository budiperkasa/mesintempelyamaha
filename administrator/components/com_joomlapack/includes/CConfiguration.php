<?php
/**
 * Configuration management class
 *
 * Takes care of storing and disseminating JoomlaPack configuration information
 * to other parts of the component.
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

require_once( JPComponentRoot . "/includes/CAltInstaller.php");
require_once( JPComponentRoot . "/includes/CJPLogger.php");

/**
 * CConfiguration is responsible for loading and saving configuration options
 *
 * Configuration is rather sparse at the moment, but this will change with next versions. All
 * configuration values are saved to and retrieved from a PHP file, in the fashion Joomla does.
 *
 * @package    JoomlaPacker
 * @author     Nicholas K. Dionysopoulos nikosdion@gmail.com
 * @copyright  2006 Nicholas K. Dionysopoulos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    1.0
 * @since      File available since Release 1.0
 */
class CConfiguration {
	/**
	 * The directory used to output packed files. It is suggested to be outside the
	 * web root for security reasons.
	 * @var string
	 */
	var $OutputDirectory;
	/**
	 * The directory used to output temporary files. It is suggested to be outside the
	 * web root for security reasons.
	 * @var string
	 */
	var $TempDirectory;
	/**
	 * MySQL Export compatibility options
	 * @var string
	 */
	var $MySQLCompat;

	/**
	 * The absolute path to the directory Joomla! Pack is installed
	 * @var string
	 */
	var $_InstallationRoot;

	/**
	 * The template name for the archive file; three tags are recognized: [DATE], [TIME], [HOST]
	 * @access public
	 * @var string
	 */
	var $TarNameTemplate;

	/**
	 * Should we use compression or not?
	 * @access public
	 * @var boolean
	 */
	var $boolCompress;

	/**
	 * Algorithm for filelist creation
	 * @access public
	 * @var string
	 */
	var $fileListAlgorithm;

	/**
	 * Algorithm for db backup
	 * @access public
	 * @var string
	 */
	var $dbAlgorithm;

	/**
	 * Algorithm for file packing
	 * @access public
	 * @var string
	 */
	var $packAlgorithm;

	/**
	 * The name of the installer package to include in the archive
	 * @access public
	 * @var string
	 */
	var $InstallerPackage;

	/**
	 * A CAltInstaller object of the selected installer package
	 * @access public
	 * @var object
	 */
	var $AltInstaller;

	/**
	 * The absolute path to the configuration.php file
	 * @access private
	 * @var string
	 */
	var $_configurationFile;

	/**
	 * The level over which to log events in the log file
	 * @access private
	 * @var integer
	 */
	var $logLevel;

	/**
	 * Toggle switch for frontend backup support
	 *
	 * @var boolean
	 */
	var $enableFrontend;
	
	/**
	 * Frontend backup secret word
	 *
	 * @var string
	 */
	var $secretWord;
	
	/**
	* Initializer. Loads a set of default values that are good enough - but not secure enough -
	* for most users.
	*/
	function CConfiguration() {
		// Private initializers
		$this->_InstallationRoot = JPComponentRoot . '/';
		$this->_configurationFile = $this->_InstallationRoot . "jpack.config.php";

		// Default configuration
		$this->TempDirectory = $this->_InstallationRoot . "temp";
		$this->OutputDirectory = $this->_InstallationRoot . "temp";
		$this->MySQLCompat = "default";
		$this->boolCompress = "zip";
		$this->TarNameTemplate = "site-[HOST]-[DATE]-[TIME]";
		$this->fileListAlgorithm = "smart";
		$this->dbAlgorithm = "smart";
		$this->packAlgorithm = "smart";
		$this->InstallerPackage = "jpi2.xml";
		$this->AltInstaller = new CAltInstaller();
		$this->AltInstaller->loadDefinition( $this->InstallerPackage );
		$this->logLevel = _JP_LOG_WARNING;
		$this->VersionCheck = 1;
	}

	/**
	* Loads configuration from disk
	* @return boolean
	*/
	function LoadConfiguration() {
		$fp = @fopen($this->_configurationFile, "r");
		if ($fp === false) { return false; }
		fclose($fp);
		require $this->_configurationFile;
		$this->OutputDirectory = $jpConfig_OutputDirectory;
		$this->OutputDirectory = $this->TranslateWinPath( $this->OutputDirectory );
		$this->TempDirectory = $jpConfig_TempDirectory;
		$this->TempDirectory = $this->TranslateWinPath( $this->TempDirectory );
		$this->MySQLCompat = $jpConfig_MySQLCompat;
		$this->boolCompress = $jpConfig_boolCompress;
		$this->TarNameTemplate = $jpConfig_TarNameTemplate;
		$this->fileListAlgorithm = $jpConfig_fileListAlgorithm;
		$this->dbAlgorithm = $jpConfig_dbAlgorithm;
		$this->InstallerPackage = $jpConfig_InstallerPackage;
		$this->packAlgorithm = $jpConfig_packAlgorithm;
		$this->AltInstaller->loadDefinition( $this->InstallerPackage );
		$this->logLevel = $jpConfig_logLevel;
		$this->enableFrontend = $jpConfig_enableFrontend;
		$this->secretWord = $jpConfig_secretWord;
		//$this->VersionCheck = $jpConfig_VersionCheck
		return true;
	}

	/**
	* Saves configuration to disk
	* @return boolean
	*/
	function SaveConfiguration() {
		if( !$this->isConfigurationWriteable() ) { return false; }
		$config = "<?php\n";
		$config .= "defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );\n";
		$config .= '$jpConfig_OutputDirectory = \'' . addslashes($this->OutputDirectory) . "';\n";
		$config .= '$jpConfig_TempDirectory = \'' . addslashes($this->TempDirectory) . "';\n";
		$config .= '$jpConfig_MySQLCompat = \'' . addslashes($this->MySQLCompat) . "';\n";
		$config .= '$jpConfig_boolCompress = "' . $this->boolCompress . "\";\n";
		$config .= '$jpConfig_TarNameTemplate = \'' . addslashes($this->TarNameTemplate) . "';\n";
		$config .= '$jpConfig_fileListAlgorithm = \'' . addslashes($this->fileListAlgorithm) . "';\n";
		$config .= '$jpConfig_dbAlgorithm = \'' . addslashes($this->dbAlgorithm) . "';\n";
		$config .= '$jpConfig_packAlgorithm = \'' . addslashes($this->packAlgorithm) . "';\n";
		$config .= '$jpConfig_InstallerPackage = \'' . addslashes($this->InstallerPackage) . "';\n";
		$config .= '$jpConfig_logLevel = \'' . addslashes($this->logLevel) . "';\n";
		$config .= '$jpConfig_enableFrontend = ' . ($this->enableFrontend ? 'true' : 'false') . ";\n";
		$config .= '$jpConfig_secretWord = \'' . addslashes($this->secretWord) . "';\n";
		//$config .= '$jpConfig_VersionCheck = \''.addslashes($this->VersionCheck)."';\n";
		$config .= "?>";
		$fp = @fopen($this->_configurationFile, "w");
		if ($fp === false) { return false; }
		fputs($fp, $config);
		fclose($fp);
		return true;
	}
	
	/**
	 * Saves the configuration, first updating it with whatever is passed in the request.
	 * This is used when applying or saving the configuration from the backend.
	 */
	function SaveFromPost()
	{
		$this->OutputDirectory		= CJVAbstract::getParam( 'outdir', '' );
		$this->TempDirectory 		= CJVAbstract::getParam( 'tempdir', '' );
		$this->MySQLCompat 			= CJVAbstract::getParam( 'sqlcompat', '' );
		$this->boolCompress 		= CJVAbstract::getParam( 'compress', '' );
		$this->TarNameTemplate 		= CJVAbstract::getParam( 'tarname', '' );
		$this->fileListAlgorithm 	= CJVAbstract::getParam( 'fileListAlgorithm', 'smart' );
		$this->dbAlgorithm 			= CJVAbstract::getParam( 'dbAlgorithm', 'smart' );
		$this->packAlgorithm 		= CJVAbstract::getParam( 'packAlgorithm', 'smart' );
		$this->InstallerPackage 	= CJVAbstract::getParam( 'altInstaller', 'jpi.xml' );
		$this->logLevel 			= CJVAbstract::getParam( 'logLevel', '3' );
		$this->enableFrontend		= CJVAbstract::getParam( 'enableFrontend', '' ) == 'on' ? true : false;
		$this->secretWord			= CJVAbstract::getParam( 'secretWord', '' );
		//$this->VersionCheck			= CJVAbstract::getParam( 'VersionCheck', 1);
		
		$this->SaveConfiguration();		
	}

	/**
	* Returns true if configuration.php is present
	* @return boolean
	*/
	function hasConfiguration() {
		return file_exists($this->_configurationFile);
	}

	/**
	* Returns true if configuration.php is present
	* @return boolean
	*/
	function isConfigurationWriteable() {
		if( $this->hasConfiguration() ) {
			return is_writable($this->_configurationFile);
		} else {
			return is_writable($this->_InstallationRoot);
		}
	}

	/**
	* checks to see if the file/folder is writable
	* @return boolean
	*/
	function is_writable($path) {
		//will work in despite of Windows ACLs bug
		//NOTE: use a trailing slash for folders!!!
		//see http://bugs.php.net/bug.php?id=27609
		//see http://bugs.php.net/bug.php?id=30931

		    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
		        return is_writable($path.uniqid(mt_rand()).'.tmp');
		    else if (is_dir($path))
		        return is_writable($path.'/'.uniqid(mt_rand()).'.tmp');
		    // check tmp file for read/write capabilities
		    $rm = file_exists($path);
		    $f = @fopen($path, 'a');
		    if ($f===false)
		        return false;
		    fclose($f);
		    if (!$rm)
		        unlink($path);
		    return true;
		}
	
	/**
	* Returns true if the output target directory is writeable by the PHP script
	* @return boolean
	*/
	function isOutputWriteable() {
		return is_writable($this->OutputDirectory);
	}

	/**
	* Returns true if the temporary files directory is writeable by the PHP script
	* @return boolean
	*/
	function isTempWriteable() {
		return is_writable($this->TempDirectory);
	}

	/**
	* Writes a debug variable to the database (#__jp_packvars)
	* @param string The name of the variable to write / update
	* @param mixed The value of the variable to write / update
	*/
	function WriteDebugVar( $varName, $value, $boolLongText = false ){
		global $database;

		// Kill exisiting variable (if any)
		$database->setQuery( "DELETE FROM #__jp_packvars WHERE `key`=\"" . mysql_escape_string($varName) . "\"" );
		$database->query();

		// Create variable
		if (!$boolLongText) {
			$sql = "INSERT INTO #__jp_packvars (`key`, value) VALUES (\"" . mysql_escape_string( $varName ) . "\", \"" .mysql_escape_string( $value ) . "\")";
		} else {
			$sql = "INSERT INTO #__jp_packvars (`key`, value2) VALUES (\"" . mysql_escape_string( $varName ) . "\", \"" .mysql_escape_string( $value ) . "\")";
		}

		$database->setQuery( $sql );
		$database->query();
	}

	/**
	* Reads a debug variable out of #__jp_packvars
	*/
	function ReadDebugVar( $key, $boolLongText = false ) {
		global $database;

		if (!$boolLongText) {
			$sql = "SELECT value FROM #__jp_packvars WHERE `key` = \"" . mysql_escape_string( $key ) . "\"";
		} else {
			$sql = "SELECT value2 FROM #__jp_packvars WHERE `key` = \"" . mysql_escape_string( $key ) . "\"";
		}
		$database->setQuery( $sql );
		$database->query();
		return $database->loadResult();
	}

	/**
	* Deletes a debug variable from #__jp_packvars
	*/
	function DeleteDebugVar( $key ) {
		global $database;

		$sql = "DELETE FROM #__jp_packvars WHERE `key` = \"" . mysql_escape_string( $key ) . "\"";
		$database->setQuery( $sql );
		$database->query();
	}

    function TranslateWinPath($p_path)
    {
		if (stristr(php_uname(), 'windows')){
			// ----- Change potential windows directory separator
			if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0, 1) == '\\')){
				$p_path = strtr($p_path, '\\', '/');
			}
		}
		return $p_path;
	}


}
global $JPConfiguration;
$JPConfiguration = new CConfiguration;
if ($JPConfiguration->hasConfiguration()) {
	$JPConfiguration->LoadConfiguration();
}
?>