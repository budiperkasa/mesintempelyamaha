<?php
/**
* @version toolbar.recaptcha.php
* @package Joomla
* @subpackage recaptcha
* @copyright Copyright (C) 2007 Robert van den Breemen. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class HTML_recaptcha {

	function settings( $option, &$params, $id ) {
		global $mosConfig_live_site, $mosConfig_cachepath, $my;
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			reCAPTCHA Settings
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th>
			Parameters
			</th>
		</tr>
		<tr>
			<td>
			Before you can start using the reCAPTCHA secured registration component, you have to register with Carnegie Mellon University to be able to use the reCAPTCHA on your site.<br />
			<a href="http://recaptcha.net/whyrecaptcha.html">Read here why</a> you should use the reCAPTCHA technology.<br />
			<a href="http://recaptcha.net/api/getkey">Then sign up here</a> to get the keys for your website.<br />
			</td>
		</tr>
		<tr>
			<td>
			<?php
			echo $params->render();
			?>
			</td>
		</tr>
		</table>
		
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="name" value="recaptcha" />
		<input type="hidden" name="admin_menu_link" value="option=com_recaptcha" />
		<input type="hidden" name="admin_menu_alt" value="Manage Recaptcha Settings" />
		<input type="hidden" name="option" value="com_recaptcha" />
		<input type="hidden" name="admin_menu_img" value="js/ThemeOffice/component.png" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>