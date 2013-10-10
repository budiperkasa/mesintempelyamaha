<?php
/**
* @package		JoomlaPack
* @copyright	Copyright (C) 2006-2008 JoomlaPack Developers. All rights reserved.
* @version		1.1.1b2
* @license 		http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* JoomlaPack is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
**/

// ensure this file is being included by a parent file - Joomla! 1.0.x and 1.5 compatible
(defined( '_VALID_MOS' ) || defined('_JEXEC')) or die( 'Direct Access to this location is not allowed.' );

global $JPLang;

require_once( JPComponentRoot . '/includes/CCUBE.php' );
CCUBE::_cleanup();
?>
<table class="adminheading">
	<tr>
		<th class="config" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['cpanel']['unlock'];?></h2></td>
	</tr>
</table>
<p>
<?php echo $JPLang['unlock']['done'];?>
</p>