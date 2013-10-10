<?php
/**
  *
* @version install.recaptcha.php version 0.0.4
* @package Joomla
 * @author Robert van den Breemen
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
 
function com_install() {
?>
<p align="left">To finish implementing reCAPTCHA enable Registration do the following steps:<br /><br />
1) <a href="http://recaptcha.net/api/getkey">Register with reCAPATCHA</a>, to get the required private and public keys for your site.<br />
2) Goto the reCAPTCHA component configuration (Components->reCAPTCHA) and enter the keys.<br />
3) Install the modified reCAPTCHA login module.<br />
4) UNpublish the default login module (Modules->Site Modules), look for com_login in the type colum.<br />
5) Publish the reCAPTCHA login module (Modules->Site Modules), look for com_recaptachalogin in the type colum.<br /><br />
Now your registration of new users is protected by <href a="http://recaptcha.net">reCAPTCHA</a><br />

<b>Component reCAPTCHA Registration was installed successfully!</b></p><br /><br />

<?php
}
?>