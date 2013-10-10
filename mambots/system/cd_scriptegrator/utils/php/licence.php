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
defined( '_VALID_MOS' ) or die( 'Restricted access' );
?>
<?php if ($cd_poweredby) : ?>
<div class="cd_core_design_poweredby">Powered by <a href="http://www.greatjoomla.com" title="http://www.greatjoomla.com" target="_blank">Core Design</a></div>
<?php else :?>
<div style="display: none">Powered by <a href="http://www.greatjoomla.com" title="http://www.greatjoomla.com" target="_blank">Core Design</a></div>
<?php endif; ?>
