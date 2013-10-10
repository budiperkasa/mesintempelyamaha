<?php

/**
 * ZIP Creation Module
 *
 * Creates a ZIP file based on the contents of a given file list.
 * Based upon the Compress library of the Horde Project [http://www.horde.org],
 * modified to fit JoomlaPack needs. This class is safe to serialize and deserialize
 * between subsequent calls.
 *
 * JoomlaPack modifications : eficiently read data from files, selective compression,
 * defensive memory management to avoid memory exhaustion errors, separated central
 * directory and data section creation
 *
 * ----------------------------------------------------------------------------
 *
 * Original code credits, from Horde library:
 *
 * The ZIP compression code is partially based on code from:
 *   Eric Mueller <eric@themepark.com>
 *   http://www.zend.com/codex.php?id=535&single=1
 *
 *   Deins125 <webmaster@atlant.ru>
 *   http://www.zend.com/codex.php?id=470&single=1
 *
 * The ZIP compression date code is partially based on code from
 *   Peter Listiak <mlady@users.sourceforge.net>
 *
 * Copyright 2000-2006 Chuck Hagenbuch <chuck@horde.org>
 * Copyright 2002-2006 Michael Cochrane <mike@graftonhall.co.nz>
 * Copyright 2003-2006 Michael Slusarz <slusarz@horde.org>
 *
 * Additional Credits:
 *
 * Contains code from pclZip library [http://www.phpconcept.net/pclzip/index.en.php]
 *
 * Modifications for JoomlaPack:
 * Copyright 2007 Nicholas K. Dionysopoulos <nikosdion@gmail.com>
 *
 * ----------------------------------------------------------------------------
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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version    $Id$
 */

// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

define("_CZIPCREATOR_FORCE_FOPEN", false); // Don't force use of fopen() to read uncompressed data in memory

class CZIPCreator {
    /**
     * ZIP compression methods. JoomlaPack supports 0x0 (none) and 0x8 (deflated)
     *
     * @var array
     */
    var $_methods = array(
        0x0 => 'None',
        0x1 => 'Shrunk',
        0x2 => 'Super Fast',
        0x3 => 'Fast',
        0x4 => 'Normal',
        0x5 => 'Maximum',
        0x6 => 'Imploded',
        0x8 => 'Deflated'
    );

    /**
     * Beginning of central directory record.
     *
     * @var string
     */
    var $_ctrlDirHeader = "\x50\x4b\x01\x02";

    /**
     * End of central directory record.
     *
     * @var string
     */
    var $_ctrlDirEnd = "\x50\x4b\x05\x06\x00\x00\x00\x00";

    /**
     * Beginning of file contents.
     *
     * @var string
     */
    var $_fileHeader = "\x50\x4b\x03\x04";

    /**
     * The name of the temporary file holding the ZIP's Central Directory
     *
     * @var string
     */
	var $_ctrlDirFileName;

    /**
     * The name of the file holding the ZIP's data, which becomes the final archive
     *
     * @var string
     */
	var $_dataFileName;

    /**
     * The total number of files and directories stored in the ZIP archive
     *
     * @var integer
     */
	var $_totalFileEntries;

    /**
     * The chunk size for CRC32 calculations
     *
     * @var integer
     */
	var $CZIPCREATOR_CHUNK_SIZE;

    /**
     * Class constructor, create a new ZIP file.
     *
     * @param string $archiveFileName	The full pathname to the archive file
     * @param string $tempDir			If not null then use this directory for temporary files, otherwise use system's default temporary directory.
     * @param boolean $preferSystemTemp	Prefer system's temporary directory and use $tempDir only if system dir is not writable
     */
	function CZIPCreator( $archiveFileName, $tempDir = null, $preferSystemTemp = true )
	{
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CZIPCreator :: new instance - archive $archiveFileName");
		// Check if we should use system's directory
		if( (!is_null($tempDir)) && $preferSystemTemp ) {
			// Try creating a temporary file in system's default directory
			$tempFP = @tempnam( $tempDir, 'jptest' );
			if( !($tempFP === FALSE) ) {
				// Try opening the file for append
				$tempFP2 = @fopen( $tempFP, "ab" );
				if( !($tempFP2 === FALSE) ) {
					@fclose( $tempFP2 );
					$tempDir = null;
					@unlink( $tempFP );
				}
			}
			unset( $tempFP );
			unset( $tempFP2 );
		}

		// Try to use as much memory as it's possible for CRC32 calculation
		$memLimit = ini_get("memory_limit");
		if ( ($memLimit == "") ) {
			// No memory limit, use 2Mb chunks (fairly large, right?)
			$this->CZIPCREATOR_CHUNK_SIZE = 2097152;
		} elseif ( function_exists("memory_get_usage") ) {
			// PHP can report memory usage, see if there's enough available memory; Joomla! alone eats about 5-6Mb! This code is called on files <= 1Mb
			$memLimit = $this->_return_bytes( $memLimit );
			$availableRAM = $memLimit - memory_get_usage();

			if ($availableRAM <= 0) {
				// Some PHP implemenations also return the size of the httpd footprint!
				if ( ($memLimit - 6291456) > 0 ) {
					$this->CZIPCREATOR_CHUNK_SIZE = $memLimit - 6291456;
				} else {
					$this->CZIPCREATOR_CHUNK_SIZE = 2097152;
				}
			} else {
					$this->CZIPCREATOR_CHUNK_SIZE = $availableRAM * 0.5;
			}
		} else {
			// PHP can't report memory usage, use a conservative 512Kb
			$this->CZIPCREATOR_CHUNK_SIZE = 524288;
		}
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "Chunk size for CRC is now " . $this->CZIPCREATOR_CHUNK_SIZE . " bytes");


		if (is_null($tempDir)) CJPLogger::WriteLog(_JP_LOG_DEBUG, "CZIPCreator :: using System Default temporary directory");
		// Get names of temporary files
		$this->_ctrlDirFileName = tempnam( $tempDir, 'jpzcd' );
		$this->_dataFileName = $archiveFileName;

		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CZIPCreator :: CntDir Tempfile = " . $this->_ctrlDirFileName);

		// Create temporary file
		touch( $this->_ctrlDirFileName );

		// Try to kill the archive if it exists
		CJPLogger::WriteLog(_JP_LOG_DEBUG, "CZIPCreator :: Killing old archive");
		$fp = fopen( $this->_dataFileName, "wb" );
		if (!($fp === false)) {
			ftruncate( $fp,0 );
			fclose( $fp );
		} else {
			@unlink( $this->_dataFileName );
		}
		touch( $this->_dataFileName );
	}

	/**
	 * Adds the files of the $fileList to the archive (actually, updates Central Directory and Data File).
	 *
	 * @param array $fileList The list of files to be packed, as a simple array
	 *
	 * @return boolean TRUE on success, FALSE if data was not an array.
	 */
	function addFileList( &$fileList, $removePath, $addPath ) {
		if( !is_array($fileList) ) {
			CJPLogger::WriteLog(_JP_LOG_WARNING, "CZIPCreator :: addFileList called without a file list array");
			return FALSE;
		}

		foreach( $fileList as $file ) {
			$ret = $this->_addFile( $file, $removePath, $addPath );
			if( $ret === false ) {
				CJPLogger::WriteLog(_JP_LOG_WARNING, "Unreadable file $file. Check permissions.");
			}
		}

		return TRUE;
	}

    /**
     * Adds a single file to the ZIP archive (actually, updates Central Directory and Data File).
     * It intelligently choses when to compress based on file size and available memory.
     *
     * @access private
     *
     * @param string $fileName	Full pathname to file to be stored
     *
     * @return boolean True on success, false if file was skipped
     */
	function _addFile( $fileName, $removePath, $addPath )
	{
		// See if it's a directory
		$isDir = is_dir($fileName);

		// Get real size before compression
		$fileSize = $isDir ? 0 : filesize($fileName);

		// Get last modification time to store in archive
		$ftime = filemtime( $fileName );

		// Decide if we will compress
		if ($isDir) {
			$compressionMethod = 0; // don't compress directories...
		} else {
			// Do we have plenty of memory left?
			$memLimit = ini_get("memory_limit");
			if (($memLimit == "") || ($fileSize >= 1024768)) {
				// No memory limit, or over 1Mb files => always compress up to 1Mb files (otherwise it times out)
				$compressionMethod = ($fileSize <= 1024768) ? 8 : 0;
			} elseif ( function_exists("memory_get_usage") ) {
				// PHP can report memory usage, see if there's enough available memory; Joomla! alone eats about 5-6Mb! This code is called on files <= 1Mb
				$memLimit = $this->_return_bytes( $memLimit );
				$availableRAM = $memLimit - memory_get_usage();
				$compressionMethod = (($availableRAM / 2.5) >= $fileSize) ? 8 : 0;
			} else {
				// PHP can't report memory usage, compress only files up to 512Kb (conservative approach) and hope it doesn't break
				$compressionMethod = ($fileSize <= 524288) ? 8 : 0;;
			}
		}

		$compressionMethod = function_exists("gzcompress") ? $compressionMethod : 0;

		$storedName = $this->_addRemovePaths( $fileName, $removePath, $addPath );

		// Debug data
		// CJPLogger::WriteLog(_JP_LOG_DEBUG, $isDir ? "+ DIR  $storedName" : "+ FILE $storedName ($fileSize) - COMP $compressionMethod");

        /* "Local file header" segment. */
        $unc_len = &$fileSize; // File size

        if (!$isDir) {
        	// Get CRC for regular files, not dirs

        	$crcCalculator = new CRC32CalcClass;
			$crc     = $crcCalculator->crc32_file( $fileName, $this->CZIPCREATOR_CHUNK_SIZE ); // This is supposed to be the fast way to calculate CRC32 of a (large) file.
			unset( $crcCalculator );

			// If the file was unreadable, $crc will be false, so we skip the file
			if ($crc === false) {
				CJPLogger::WriteLog(_JP_LOG_DEBUG, "Could not calculate CRC32" );
				return false;
			}
		} else {
			// Dummy CRC for dirs
			$crc = 0;
			$storedName .= "/";
			$unc_len = 0;
		}


		// If we have to compress, read the data in memory and compress it
		if ($compressionMethod == 8) {
			// Get uncompressed data
			if( function_exists("file_get_contents") && (_CZIPCREATOR_FORCE_FOPEN == false) ) {
				$udata = @file_get_contents( $fileName ); // PHP > 4.3.0 saves us the trouble
			} else {
				// Argh... the hard way!
				$udatafp = @fopen( $fileName, "rb" );
				if( !($udatafp === false) ) {
					$udata = "";
					while( !feof($udatafp) ) {
						$udata .= fread($udatafp, 524288);
					}
					fclose( $udatafp );
				} else {
					$udata = false;
				}
			}
			if ($udata === FALSE) {
				// Unreadable file, skip it. Normally, we should have exited on CRC code above
				return false;
			} else {
				// Proceed with compression
				$zdata   = @gzcompress($udata);
				if ($zdata === false) {
					// If compression fails, let it behave like no compression was available
					$c_len = &$unc_len;
					$compressionMethod = 0;
				} else {
					unset( $udata );
					$zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
					$c_len   = strlen($zdata);
				}
			}
		} else {
			$c_len = $unc_len;
		}


        /* Get the hex time. */
        $dtime    = dechex($this->_unix2DosTime($ftime));
        $hexdtime = chr(hexdec($dtime[6] . $dtime[7])) .
                    chr(hexdec($dtime[4] . $dtime[5])) .
                    chr(hexdec($dtime[2] . $dtime[3])) .
                    chr(hexdec($dtime[0] . $dtime[1]));

        // Get current data file size
        $old_offset = filesize( $this->_dataFileName );

        // Open data file for output
        $fp = @fopen( $this->_dataFileName, "ab");
        if ($fp === false)
			CJPLogger::WriteLog(_JP_LOG_ERROR, "Could not open archive file for append!");
        $this->_fwrite( $fp, $this->_fileHeader );									/* Begin creating the ZIP data. */
        $this->_fwrite( $fp, "\x14\x00" );					/* Version needed to extract. */
        $this->_fwrite( $fp, "\x00\x00" ); 											/* General purpose bit flag. */
        $this->_fwrite( $fp, ($compressionMethod == 8) ? "\x08\x00" : "\x00\x00" );	/* Compression method. */
        $this->_fwrite( $fp, $hexdtime );											/* Last modification time/date. */
        $this->_fwrite( $fp, pack('V', $crc) );            /* CRC 32 information. */
        $this->_fwrite( $fp, pack('V', $c_len) );          /* Compressed filesize. */
        $this->_fwrite( $fp, pack('V', $unc_len) );        /* Uncompressed filesize. */
        $this->_fwrite( $fp, pack('v', strlen($storedName)) );   /* Length of filename. */
        $this->_fwrite( $fp, pack('v', 0) );               /* Extra field length. */
        $this->_fwrite( $fp, $storedName );                      /* File name. */

		/* "File data" segment. */
		if ($compressionMethod == 8) {
			// Just dump the compressed data
			$this->_fwrite( $fp, $zdata );
			unset( $zdata );
		} elseif (!$isDir) {
			// Copy the file contents, ignore directories
			$zdatafp = @fopen( $fileName, "rb" );
			while( !feof($zdatafp) ) {
				$zdata = fread($zdatafp, 524288);
				$this->_fwrite( $fp, $zdata );
			}
			fclose( $zdatafp );
		}

        /* "Data descriptor" segment (optional but necessary if archive is
           not served as file). */
		// Pitfall!! This should be present ONLY if bit 3 of the flags is set. Since we write 0x0000 there, we can't present a "data descriptor"!

		/*
		if( !$isDir ) {
			$this->_fwrite( $fp, pack('V', $crc) );
			$this->_fwrite( $fp, pack('V', $c_len) );
			$this->_fwrite( $fp, pack('V', $unc_len) );
        }
        */

        // Done with data file.
        fclose( $fp );

        // Open the central directory file for append
        $fp = @fopen( $this->_ctrlDirFileName, "ab");
        if ($fp === false)
			CJPLogger::WriteLog(_JP_LOG_ERROR, "Could not open Central Directory temporary file for append!");
        $this->_fwrite( $fp, $this->_ctrlDirHeader );
        $this->_fwrite( $fp, "\x00\x00" );                /* Version made by. */
		$this->_fwrite( $fp, "\x14\x00" );					/* Version needed to extract */
        $this->_fwrite( $fp, "\x00\x00" );                /* General purpose bit flag */
        $this->_fwrite( $fp, ($compressionMethod == 8) ? "\x08\x00" : "\x00\x00" );	/* Compression method. */
        $this->_fwrite( $fp, $hexdtime );                 /* Last mod time/date. */
        $this->_fwrite( $fp, pack('V', $crc) );           /* CRC 32 information. */
        $this->_fwrite( $fp, pack('V', $c_len) );         /* Compressed filesize. */
        $this->_fwrite( $fp, pack('V', $unc_len) );       /* Uncompressed filesize. */
        $this->_fwrite( $fp, pack('v', strlen($storedName)) );  /* Length of filename. */
        $this->_fwrite( $fp, pack('v', 0 ) );             /* Extra field length. */
        $this->_fwrite( $fp, pack('v', 0 ) );             /* File comment length. */
        $this->_fwrite( $fp, pack('v', 0 ) );             /* Disk number start. */
        $this->_fwrite( $fp, pack('v', 0 ) );             /* Internal file attributes. */
        $this->_fwrite( $fp, pack('V', $isDir ? 0x41FF0010 : 0xFE49FFE0) ); /* External file attributes -   'archive' bit set. */
        $this->_fwrite( $fp, pack('V', $old_offset) );    /* Relative offset of local
                                                header. */
        $this->_fwrite( $fp, $storedName );                     /* File name. */
        /* Optional extra field, file comment goes here. */

        // Finished with Central Directory
        fclose( $fp );

        // Finaly, increase the file counter by one
        $this->_totalFileEntries++;

        // ... and return TRUE = success
        return TRUE;

	}

	/**
	 * Write to file, defeating magic_quotes_runtime settings (pure binary write)
	 */
	function _fwrite( $fp, $data )
	{
		$len = strlen( $data );
		fwrite( $fp, $data, $len );
	}

    /**
     * Creates the ZIP file out of its pieces.
     * Official ZIP file format: http://www.pkware.com/appnote.txt
     *
     * @return boolean TRUE on success, FALSE on failure
     */
    function glueZIPFile()
    {
    	// 1. Get size of central directory
    	$cdSize = filesize( $this->_ctrlDirFileName );

    	// 2. Append Central Directory to data file and remove the CD temp file afterwards
    	$dataFP = fopen( $this->_dataFileName, "ab" );
    	$cdFP = fopen( $this->_ctrlDirFileName, "rb" );

    	if ( $cdFP === false ) {
    		// Already glued, return
			fclose( $dataFP );
			return false;
    	}

    	while( !feof($cdFP) )
    	{
    		$chunk = fread( $cdFP, 1024768 );
    		$this->_fwrite( $dataFP, $chunk );
    	}
    	unset( $chunk );
    	fclose( $cdFP );

    	@unlink( $this->_ctrlDirFileName );

    	// 3. Write the rest of headers to the end of the ZIP file
    	fclose( $dataFP );
    	$dataSize = filesize( $this->_dataFileName ) - $cdSize;
    	$dataFP = fopen( $this->_dataFileName, "ab" );

    	$this->_fwrite( $dataFP, $this->_ctrlDirEnd );
    	$this->_fwrite( $dataFP, pack('v', $this->_totalFileEntries) ); /* Total # of entries "on this disk". */
    	$this->_fwrite( $dataFP, pack('v', $this->_totalFileEntries) ); /* Total # of entries overall. */
    	$this->_fwrite( $dataFP, pack('V', $cdSize) ); /* Size of central directory. */
    	$this->_fwrite( $dataFP, pack('V', $dataSize) ); /* Offset to start of central dir. */
    	$this->_fwrite( $dataFP, "\x00\x00" ); /* ZIP file comment length. */
    	fclose( $dataFP );
		//sleep(2);
    	return true;
    }

    /**
     * Converts a UNIX timestamp to a 4-byte DOS date and time format
     * (date in high 2-bytes, time in low 2-bytes allowing magnitude
     * comparison).
     *
     * @access private
     *
     * @param integer $unixtime  The current UNIX timestamp.
     *
     * @return integer  The current date in a 4-byte DOS format.
     */
    function _unix2DOSTime($unixtime = null)
    {
        $timearray = (is_null($unixtime)) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
            $timearray['year']    = 1980;
            $timearray['mon']     = 1;
            $timearray['mday']    = 1;
            $timearray['hours']   = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        }

        return (($timearray['year'] - 1980) << 25) |
                ($timearray['mon'] << 21) |
                ($timearray['mday'] << 16) |
                ($timearray['hours'] << 11) |
                ($timearray['minutes'] << 5) |
                ($timearray['seconds'] >> 1);
    }

	function _return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val{strlen($val)-1});
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

	/**
	 * Translate a Windows path to a Unix-like syntax, optionally removing the disk letter.
	 * Copied verbatim from pclZip library
	 *
	 * @param string $p_path The path to translate
	 * @param boolean $p_remove_disk_letter Should we remove the disk letter?
	 *
	 * @return string The translated path
	 */
	function _TranslateWinPath($p_path, $p_remove_disk_letter=true)
	{
		if (stristr(php_uname(), 'windows')) {
			// ----- Look for potential disk letter
			if (($p_remove_disk_letter) && (($v_position = strpos($p_path, ':')) != false)) {
				$p_path = substr($p_path, $v_position+1);
			}
			// ----- Change potential windows directory separator
			if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0,1) == '\\')) {
				$p_path = strtr($p_path, '\\', '/');
			}
		}
		return $p_path;
	}

	/**
	* Removes the $p_remove_dir from $p_filename, while prepending it with $p_add_dir.
	* Largely based on code from the pclZip library.
	*/
	function _addRemovePaths( $p_filename, $p_remove_dir, $p_add_dir ) {
		$p_filename = $this->_TranslateWinPath( $p_filename );
		$p_remove_dir = $this->_TranslateWinPath( $p_remove_dir ); //should fix corrupt backups, fix by nicholas
		
		if( !($p_remove_dir == "") ) {
			if (substr($p_remove_dir, -1) != '/')
				$p_remove_dir .= "/";

			if ((substr($p_filename, 0, 2) == "./") || (substr($p_remove_dir, 0, 2) == "./"))
			{
				if ((substr($p_filename, 0, 2) == "./") && (substr($p_remove_dir, 0, 2) != "./"))
					$p_remove_dir = "./".$p_remove_dir;
				if ((substr($p_filename, 0, 2) != "./") && (substr($p_remove_dir, 0, 2) == "./"))
					$p_remove_dir = substr($p_remove_dir, 2);
			}

			$v_compare = $this->_PathInclusion($p_remove_dir, $p_filename);
			if ($v_compare > 0)
			{
				if ($v_compare == 2) {
					$v_stored_filename = "";
				}
				else {
					$v_stored_filename = substr($p_filename, strlen($p_remove_dir));
				}
			}
		} else {
			$v_stored_filename = $p_filename;
		}

		if( !($p_add_dir == "") ) {
			if (substr($p_add_dir, -1) == "/")
				$v_stored_filename = $p_add_dir.$v_stored_filename;
			else
				$v_stored_filename = $p_add_dir."/".$v_stored_filename;
		}

		return $v_stored_filename;
	}

	/**
	* This function indicates if the path $p_path is under the $p_dir tree. Or,
	* said in an other way, if the file or sub-dir $p_path is inside the dir
	* $p_dir.
	* The function indicates also if the path is exactly the same as the dir.
	* This function supports path with duplicated '/' like '//', but does not
	* support '.' or '..' statements.
	*
	* Copied verbatim from pclZip library
	*
	* @return integer 	0 if $p_path is not inside directory $p_dir,
	* 					1 if $p_path is inside directory $p_dir
	*					2 if $p_path is exactly the same as $p_dir
	*/
	function _PathInclusion($p_dir, $p_path)
	{
		$v_result = 1;

		// ----- Explode dir and path by directory separator
		$v_list_dir = explode("/", $p_dir);
		$v_list_dir_size = sizeof($v_list_dir);
		$v_list_path = explode("/", $p_path);
		$v_list_path_size = sizeof($v_list_path);

		// ----- Study directories paths
		$i = 0;
		$j = 0;
		while (($i < $v_list_dir_size) && ($j < $v_list_path_size) && ($v_result)) {
			// ----- Look for empty dir (path reduction)
			if ($v_list_dir[$i] == '') {
				$i++;
				continue;
			}
			if ($v_list_path[$j] == '') {
				$j++;
				continue;
			}

			// ----- Compare the items
			if (($v_list_dir[$i] != $v_list_path[$j]) && ($v_list_dir[$i] != '') && ( $v_list_path[$j] != ''))  {
				$v_result = 0;
			}

			// ----- Next items
			$i++;
			$j++;
		}

		// ----- Look if everything seems to be the same
		if ($v_result) {
			// ----- Skip all the empty items
			while (($j < $v_list_path_size) && ($v_list_path[$j] == '')) $j++;
			while (($i < $v_list_dir_size) && ($v_list_dir[$i] == '')) $i++;

			if (($i >= $v_list_dir_size) && ($j >= $v_list_path_size)) {
				// ----- There are exactly the same
				$v_result = 2;
			}
			else if ($i < $v_list_dir_size) {
				// ----- The path is shorter than the dir
				$v_result = 0;
			}
		}

		// ----- Return
		return $v_result;
	}

}

class CRC32CalcClass
{
	// It returns the CRC32 of a file, calculated using the most efficient method available
	// There are three methods:
	function crc32_file( $filename, $CZIPCREATOR_CHUNK_SIZE )
	{
		if( function_exists("hash_file") )
		{
			$res = $this->crc32_file_php512( $filename );
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [PHP512]" );
		}
		else if ( function_exists("file_get_contents") && ( filesize($filename) <= $CZIPCREATOR_CHUNK_SIZE ) ) {
			$res = $this->crc32_file_getcontents( $filename );
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [GETCONTENTS]" );
		} else {
			$res = $this->crc32_file_php4($filename, $CZIPCREATOR_CHUNK_SIZE);
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "File $filename - CRC32 = " . dechex($res) . " [PHP4]" );
		}

		if ($res === FALSE) {
			CJPLogger::WriteLog(_JP_LOG_DEBUG, "File $filename - NOT READABLE: CRC32 IS WRONG!" );
		}
		return $res;
	}

	// Very efficient CRC32 calculation for PHP 5.1.2 and greater
	// Cons: requires a recent PHP and the 'hash' PECL extension
	function crc32_file_php512($filename)
	{
		$res = @hash_file('crc32b', $filename, false );
		// It returns something like 04030201 when we should get 01020304. Duh!
		$res = substr($res,6,2) . substr($res,4,2) . substr($res,2,2) . substr($res,0,2);
		$res = hexdec( $res );
		return $res;
	}

	// A compatible CRC32 calculation using file_get_contents
	// Cons : uses lots of memory, despite PHP utilizing memory mapping techniques
	function crc32_file_getcontents($filename)
	{
		return crc32( @file_get_contents($filename) );
	}

	// Utility function for efficiently getting the CRC32 of large files - PHP4 mostly...
	// Cons: uses a bit of CPU time on large files...
	// Ported from zlib (converted C code to PHP)
	function crc32_file_php4($filename, $CZIPCREATOR_CHUNK_SIZE)
	{
		$count = 0;
		$fpoint=fopen($filename, "rb");
		$old_crc=false;

		if ($fpoint != false) {
			$buffer = '';

			while (!feof($fpoint)) {
				$count++;
				$buffer=fread($fpoint, $CZIPCREATOR_CHUNK_SIZE);
				$len=strlen($buffer);
				$t=crc32($buffer);
				unset( $buffer );

				if ($old_crc) {
					$crc32=$this->crc32_combine($old_crc, $t, $len);
					$old_crc=$crc32;
				} else {
					$crc32=$old_crc=$t;
				}
			}
			fclose($fpoint);
		} else {
			return false;
		}
		return $crc32;
	}

	function crc32_combine(&$crc1, &$crc2, &$len2)
	{
		$even = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$odd[0]=0xedb88320;
		$row=1;

		for($n=1;$n<32;$n++) {
			$odd[$n]=$row;
			$row<<=1;
		}

		$this->gf2_matrix_square($even,$odd); // put operator for two zero bits in even
		$this->gf2_matrix_square($odd,$even); // put operator for four zero bits in odd

		// apply len2 zeros to crc1 (first square will put the operator for one
       	// zero byte, eight zero bits, in even)
		do {
			/* apply zeros operator for this bit of len2 */
			$this->gf2_matrix_square($even, $odd);

			if ($len2 & 1)
				$crc1=$this->gf2_matrix_times($even, $crc1);

			$len2>>=1;

			/* if no more bits set, then done */
			if ($len2==0)
				break;

			/* another iteration of the loop with odd and even swapped */
			$this->gf2_matrix_square($odd, $even);
			if ($len2 & 1)
				$crc1=$this->gf2_matrix_times($odd, $crc1);
			$len2>>= 1;

		} while ($len2 != 0);

		$crc1 ^= $crc2;
		return $crc1;
	}

	function gf2_matrix_square(&$square, &$mat)
	{
		for ($n=0;$n<32;$n++) {
			$square[$n]=$this->gf2_matrix_times($mat, $mat[$n]);
		}
	}

	function gf2_matrix_times(&$mat, &$vec)
	{
		$i = 0;
		$sum=0;
		while ( ($vec != -1) && ($vec != 0) ) { // FIX 1.1.0-b2 : Deal with PHP bug with left shift of 32-bit integers
			if ((int)$vec & 1) {
				$sum ^= $mat[$i];
			}
			(int)$vec>>= 1;
			$i++;
		}
		return $sum;
	}
}
?>