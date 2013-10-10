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

// no direct access
defined('_VALID_MOS') or die('Restricted access');

$_MAMBOTS->registerFunction('onAfterStart', 'botCdScriptegrator'); // register Joomla! function

global $mosConfig_lang; // define global variable

// load function and language file
$basePath = dirname(__file__);
$function_path = $basePath .
    '/cd_scriptegrator/utils/php/functions.php';
$lang_path = $basePath . '/cd_scriptegrator/languages/' . $mosConfig_lang .
    '.php';
$lang_path_default = $basePath . '/cd_scriptegrator/languages/english.php';

if (file_exists($function_path)) {
    require_once ($function_path);
} else {
    die('<h3>Scriptegrator: I can\'t load a function file.');
}
if (file_exists($lang_path)) {
    require_once ($lang_path);
} else {
    require_once ($lang_path_default);
}
// end

/**
 * Function to load a scripts such as jQuery, Highslide, CSS module styles... 
 *  
 * botCdScriptegrator()
 *
 * @return	void
 */
function botCdScriptegrator()
{
    global $mainframe, $database;

    // load parameters from database
    loadMambotParam($botParams, 'mambots', 'cd_scriptegrator', 'system');
    $cd_write_html = $botParams->def('cd_write_html', '0');
    $outlineType = $botParams->def('outlineType', 'rounded-white');
    $outlineWhileAnimating = $botParams->def('outlineWhileAnimating', '1');
    $showCredits = $botParams->def('showCredits', '1');
    $expandDuration = $botParams->def('expandDuration', '250');
    $anchor = $botParams->def('anchor', 'auto');
    $align = $botParams->def('align', 'auto');
    $transitions = $botParams->def('transitions', 'expand');
    $dimmingOpacity = $botParams->def('dimmingOpacity', '0');
    // end

    // define script parameters
    switch ($outlineWhileAnimating) {
        case 1:
            $outlineWhileAnimating = 'true';
            break;
        case 0:
            $outlineWhileAnimating = 'false';
            break;
        default:
            $outlineWhileAnimating = 'true';
            break;
    }

    switch ($showCredits) {
        case 1:
            $showCredits = 'true';
            break;
        case 0:
            $showCredits = 'false';
            break;
        default:
            $showCredits = 'true';
            break;
    }

    switch ($transitions) {
        case 'expand':
            $transitions = '["expand"]';
            break;
        case 'fade':
            $transitions = '["fade"]';
            break;
        case 'expand+fade':
            $transitions = '["expand", "fade"]';
            break;
        case 'fade+expand':
            $transitions = '["fade", "expand"]';
            break;
        default:
            $transitions = '["expand"]';
            break;
    }

    // end
    // Core Design scripts
    $default_core_js = "<script type=\"text/javascript\" src=\"" . $mainframe->
        getCfg('live_site') .
        "/mambots/system/cd_scriptegrator/utils/js/highslide.packed.js\"></script>\n";
    $default_core_css = "<style type=\"text/css\">@import \"" . $mainframe->getCfg('live_site') .
        "/mambots/system/cd_scriptegrator/css/cd_scriptegrator.css\";</style>";
    $default_core_script = "<script type=\"text/javascript\">    
    hs.graphicsDir = '" . $mainframe->getCfg('live_site') .
        "/mambots/system/cd_scriptegrator/graphics/';
    hs.outlineType = '" . $outlineType . "';
    hs.outlineWhileAnimating = " . $outlineWhileAnimating . ";
    hs.showCredits = " . $showCredits . ";
    hs.expandDuration = " . $expandDuration . ";
    hs.loadingText = '" . _CD_HS_LOADING . "';
	hs.loadingTitle = '" . _CD_HS_CANCELCLICK . "';
	hs.anchor = '" . $anchor . "';
	hs.align = '" . $align . "';
	hs.transitions = " . $transitions . ";
	hs.dimmingOpacity = " . $dimmingOpacity . ";
	</script>\n";
	
    // insert scripts in <head> </head>
    $mainframe->addCustomHeadTag($default_core_js);
    $mainframe->addCustomHeadTag($default_core_css);
    $mainframe->addCustomHeadTag($default_core_script);
    // end

    // specify array modules and call function
    $module_array = array('mod_cd_login', 'mod_cd_latestnews', 'mod_cd_mostread',
        'mod_cd_rssfeed', 'mod_cd_virtuemart', 'mod_cd_vm_login', 'mod_cd_umf');

    foreach ($module_array as $module) {
        if (setModulePublish($module)) {
            $mainframe->addCustomHeadTag("<style type=\"text/css\">@import \"" . $mainframe->
                getCfg('live_site') . "/modules/$module/css/$module.css\";</style>");
        } else {
        }
    }
    // end

    return; // return
}
?>
