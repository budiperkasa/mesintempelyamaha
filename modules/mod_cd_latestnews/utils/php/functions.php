<?php
/**
 * Core Design Latest news module for Joomla! 1.0
 * @author		Daniel Rataj, <info@greatjoomla.com>
 * @package		Joomla 
 * @subpackage	Content
 * @category	Module
 * @version		1.0.4
 * @copyright	Copyright (C)  2007 - 2008 Core Design, http://www.greatjoomla.com
 * @license		http://creativecommons.org/licenses/by-nc/3.0/legalcode Creative Commons
 */

defined('_VALID_MOS') or die('Restricted access'); // no direct access

// ------------------------------------------------------------------------
/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */
if (!function_exists('CdLatestNewsCharacterLimiter'))
{
    function CdLatestNewsCharacterLimiter($str, $n = 0, $end_char = '&#8230;')
    {
    	// disable effect
        if ($n == 0 or strlen($str) < $n)
        {
            return $str;
        }
        // end
        
        $str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));

        if (strlen($str) <= $n)
        {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val . ' ';
            if (strlen($out) >= $n)
            {
                return trim($out) . $end_char;
            }
        }
    }
}
// ------------------------------------------------------------------------


?>
