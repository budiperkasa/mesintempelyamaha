<?php
/**
* @version $Id: login.php 119 2005-09-16 20:05:35Z stingrey $
* @package Joomla
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$tstart = mosProfiler::getmicrotime();
?>
<?php echo "<?xml version=\"1.0\"?>\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - Administration [Joomla]</title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<style type="text/css">
@import url(templates/blueplazza_admin/css/admin_login.css);
</style>
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.loginForm.usrname.select();
		document.loginForm.usrname.focus();
	}
</script>
<link rel="shortcut icon" href="<?php echo $mosConfig_live_site .'/images/favicon.ico';?>" />
</head>
<body onload="setFocus();">
<!-- BluePlazza Joomla Backend Template
Design By TemplatePlazza http://www.templateplazza.com -->
<div id="ctr" align="center">
<div class="login">
		<div class="login-form">
        	<form action="index.php" method="post" name="loginForm" id="loginForm">
			<div class="form-block">
			 			  <div class="inputlabel">Username</div>
		    	<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
	        	<div class="inputlabel">Password</div>
		    	<div><input name="pass" type="password" class="inputbox" size="15" /></div>
	        	<div><input type="submit" name="submit" class="button" value="Login" /></div>
        	</div>
			</form>
   	  </div>
		
	</div>
</div>
<div id="break"></div>
<noscript><center><strong>
!Warning! Javascript must be enabled for proper operation of the Administrator</strong></center>
</noscript>
<div class="footer" align="center">
<?php
	include ($mosConfig_absolute_path . "/includes/footer.php");
?>
</div>
</body>
</html>