<?php
/**
 * Core Design Scriptegrator plugin for Joomla! 1.0
 * @author		Daniel Rataj, <info@greatjoomla.com>
 * @package		Joomla 
 * @subpackage	Content
 * @category	Plugin
 * @version		1.1.4
 * @copyright	Copyright (C) 2007 - 2008 Core Design, http://www.greatjoomla.com
 * @license		http://creativecommons.org/licenses/by-nc/3.0/legalcode Creative Commons
 */

defined('_VALID_MOS') or die('Restricted access'); // no direct access

/**
 * Plugin parameters
 *
 * Load plugin parameters from database
 *
 * @access	public
 * @param	string $botParams
 * @param	string $from
 * @param	string $element
 * @param	string $folder 
 * @return	array
 */
if (!function_exists('loadMambotParam'))
{
    function loadMambotParam(&$botParams, $from = 'mambots', $element = '', $folder =
        'content')
    {
        global $database;
        $query = "SELECT id" . "\n FROM #__$from" . "\n WHERE element = '$element'" . "\n AND folder = '$folder'";
        $database->setQuery($query);
        $id = $database->loadResult();
        $mambot = new mosMambot($database);
        $mambot->load($id);
        $botParams = new mosParameters($mambot->params);

        return $botParams;
    }
}
// end --------------------------------------------------------------------

/**
 * Published module
 *
 * Set if module is published
 *
 * @access	public
 * @param	string $name
 * @return	bool
 */
if (!function_exists('setModulePublish'))
{
    function setModulePublish($name)
    {
        global $database, $mainframe;

        $query = "SELECT id, published" . "\n FROM #__modules" . "\n WHERE module = '$name'";
        $database->setQuery($query);
        $id = $database->loadResult();
        $module = new mosModule($database);
        $module->load($id);
        $params = new mosParameters($module->params);

        if ($module->published)
        {
            return true;
        } else
        {
            return false;
        }

        return false;
    }
}
// end --------------------------------------------------------------------

?>
