<?php

/**
 * Log Display
 *
 * PHP versions 4 and 5
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

global $JPLang, $JPConfiguration, $option;
?>
<table class="adminheading">
	<tr>
		<th class="cpanel" nowrap rowspan="2">
			<?php echo $JPLang['common']['jptitle']; ?>
		</th>
	</tr>
	<tr>
		<td nowrap><h2><?php echo $JPLang['log']['logbanner']; ?></h2></td>
	</tr>
	<tr>
		<td><a href="<?php echo "index2.php?option=$option&act=dllog&no_html=1"; ?>"><?php echo $JPLang['log']['downloadtext']; ?></a></td>
	</tr>
</table>
<div style="text-align: left; padding: 0.5em; background-color: #EEEEFE; border: thin solid black; margin: 0.5em;">
<?php
CJPLogger::VisualizeLogDirect();
?>
</div>