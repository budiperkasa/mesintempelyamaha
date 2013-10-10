<?php


// no direct access
defined('_VALID_MOS') or die('Restricted access');

global $mosConfig_frontend_login, $mosConfig_live_site, $mosConfig_absolute_path,
    $mosConfig_lang;

// load language file
$basePath = dirname(__file__);
$lang_path = $basePath . '/mod_cd_login/languages/' . $mosConfig_lang .
    '.php';
$lang_path_default = $basePath . '/mod_cd_login/languages/english.php';

if (file_exists($lang_path)) {
    require_once ($lang_path);
} else {
    require_once ($lang_path_default);
}
// end


if ($mosConfig_frontend_login != null && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login
    === '0'))
{
    return;
}

// url of current page that user will be returned to after login
if ($query_string = mosGetParam($_SERVER, 'QUERY_STRING', ''))
{
    $return = 'index.php?' . $query_string;
} else
{
    $return = 'index.php';
}

$registration_enabled = $mainframe->getCfg('allowUserRegistration');
$message_login = $params->def('login_message', 0);
$message_logout = $params->def('logout_message', 0);
$login = $params->def('login', $return);
$logout = $params->def('logout', $return);
$name = $params->def('name', 1);
$greeting = $params->def('greeting', 1);
$pretext = $params->get('pretext');
$posttext = $params->get('posttext');

$define_links = $params->def('define_links', '0');
$custom_link_new_account = $params->def('link_new_account', '');
$custom_link_lost_password = $params->def('link_lost_password', '');
$cd_login_border = $params->def('cd_login_border', 'none');

$outlineType = $params->def('outlineType', 'rounded-white');
$align = $params->def('align', 'auto');
$anchor = $params->def('anchor', 'auto');
$dimmingOpacity = $params->def('dimmingOpacity', '0');


$cd_poweredby = $params->get('cd_poweredby', 1);

// define login links
switch ($define_links)
{
	case '0': // Joomla! standard login
        $link_new_account = 'index.php?option=com_registration&amp;task=register';
        $link_lost_password = 'index.php?option=com_registration&amp;task=lostPassword';
        break;
    case '1': // Community Builder login
        $link_new_account = 'index.php?option=com_comprofiler&amp;task=registers';
        $link_lost_password = 'index.php?option=com_comprofiler&amp;task=lostPassword';
        break;
    case '2': // custom login
        $link_new_account = $custom_link_new_account;
        $link_lost_password = $custom_link_lost_password;
        break;
    default:
    	$link_new_account = 'index.php?option=com_comprofiler&amp;task=registers';
        $link_lost_password = 'index.php?option=com_registration&amp;task=lostPassword';
        break;
}
// end



?>

<?php if ($my->id)
{
    // Logout output
    // ie HTML when already logged in and trying to logout
    if ($name)
    {
        $name = $my->name;
    } else
    {
        $name = $my->username;
    } ?>
  
<?php if ($cd_login_border == 'top' or $cd_login_border == "both"): ?>
  <div class="cd_login_border-top"> </div>
<?php endif; ?>
  
<div class="cd_login-logout-greeting"> 
  <?php if ($greeting)
    {
        echo _HI;
        echo "<strong>";
        echo $name;
        echo "</strong>";
    }
?>
  <a href="#" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html-logoutform', wrapperClassName: 'mod_cd_login', outlineType: '<?php echo
    $outlineType; ?>', align: '<?php echo $align; ?>', anchor: '<?php echo $anchor; ?>', dimmingOpacity: '<?php echo $dimmingOpacity; ?>', slideshowGroup: 'mod_cd_login_logoutform' } )" title="<?php echo
    _BUTTON_LOGOUT; ?>"> </a>
</div>

<?php if ($cd_login_border == 'bottom' or $cd_login_border == "both"): ?>
  <div class="cd_login_border-bottom"> </div>
<?php endif; ?>
  
  <div class="highslide-html-content" id="highslide-html-logoutform" style="width: 350px">
    <div class="highslide-html-content-header">
      <div class="highslide-move" title="<?php echo _CD_LOGIN_TITLE_MOVE; ?>">
        <a href="#" onclick="return hs.close(this)" class="control" title="<?php echo
    _CD_LOGIN_CLOSELABEL; ?>"><?php echo _CD_LOGIN_CLOSELABEL; ?></a>
      </div>
    </div>
    <div class="highslide-body">
      <p class="cd_login-bold"><?php echo _CD_LOGIN_LOGOUT_CONFIRM; ?> </p>
      <div class="cd_login-logoutform">
        <form action="<?php echo sefRelToAbs('index.php?option=logout'); ?>" method="post" name="logout">	
		      <input type="submit" name="Submit" class="cd_login-logoutbutton" title="<?php echo
    _BUTTON_LOGOUT; ?>" value="<?php echo _BUTTON_LOGOUT; ?>" />
          <input type="hidden" name="option" value="logout" />
          <input type="hidden" name="op2" value="logout" />
          <input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
          <input type="hidden" name="return" value="<?php echo htmlspecialchars(sefRelToAbs
    ($logout)); ?>" />
          <input type="hidden" name="message" value="<?php echo htmlspecialchars($message_logout); ?>" />
        </form>
      </div>
    </div>
    <?php if (_CD_LOGIN_LOGOUT_MESSAGE == ''): ?>
  <?php else: ?>
  <div class="cd_login_message_to_users"><span><?php echo _CD_LOGIN_LOGOUT_MESSAGE; ?></span></div>
  <div style="height: 5px"></div>
  <?php endif; ?>
  </div>

	<?php
} else
{
    // Login output
    // ie HTML when not logged in and trying to login
    // used for spoof hardening
    $validate = josSpoofValue(1);
?>

<?php if ($cd_login_border == 'top' or $cd_login_border == "both"): ?>
  <div class="cd_login_border-top"> </div>
<?php endif; ?>

<div class="cd_moduletitle_logo">
  <a href="#" onclick="return hs.htmlExpand(this, { contentId: 'highslide-html-loginform', wrapperClassName: 'mod_cd_login', outlineType: '<?php echo
    $outlineType; ?>', align: '<?php echo $align; ?>', anchor: '<?php echo $anchor; ?>', dimmingOpacity: '<?php echo $dimmingOpacity; ?>', slideshowGroup: 'mod_cd_login_loginform' } )" title="<?php echo
    _CD_LOGIN_MODULE_TITLE; ?>"><?php echo _CD_LOGIN_MODULE_TITLE; ?> </a>
</div>

<?php if ($cd_login_border == 'bottom' or $cd_login_border == "both"): ?>
  <div class="cd_login_border-bottom"> </div>
<?php endif; ?>

<div class="highslide-html-content" id="highslide-html-loginform">
  
  <div class="highslide-html-content-header">
	  <div class="highslide-move" title="<?php echo _CD_LOGIN_TITLE_MOVE; ?>">
      <a href="#" onclick="return hs.close(this)" class="control" title="<?php echo
    _CD_LOGIN_CLOSELABEL; ?>"><?php echo _CD_LOGIN_CLOSELABEL; ?></a>
    </div>
	</div>
	
  <div class="highslide-body">

    <form action="<?php echo sefRelToAbs('index.php'); ?>" method="post" name="loginForm" >
      <?php echo $pretext; ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td>
              <label for="cd_login_username" class="cd_login-labelusername"><?php echo
    _USERNAME; ?></label>
              <br />
              <input name="username" id="cd_login_username" type="text" class="inputbox" alt="username" size="10" />
              <br />
              <label for="cd_login_password" class="cd_login-labelpassword"><?php echo
    _PASSWORD; ?></label>
              <br />
              <input type="password" id="cd_login_password" name="passwd" class="inputbox" size="10" alt="password" />
              <br />
              
              <div class="cd_login-form-submit">
                <div class="cd_login-form-input">
                  <input type="checkbox" name="remember" id="cd_login_remember" class="inputbox" value="yes" alt="<?php echo
    _REMEMBER_ME; ?>" title="<?php echo _REMEMBER_ME; ?>" />
                  <label for="cd_login_remember" class="cd_login-labelremember" title="<?php echo
    _REMEMBER_ME; ?>"><?php echo _REMEMBER_ME; ?></label>
                </div>
                <input type="submit" name="Submit" class="cd_login-loginbutton" title="<?php echo
    _BUTTON_LOGIN; ?>" value="" />
              </div>
          </td>
        </tr>
        <tr>
          <td>
              <div style="text-align: center">
                <a href="<?php echo sefRelToAbs($link_lost_password); ?>" title="<?php echo
    _CD_LOGIN_LOST_PASSWORD; ?>" class="cd_login-lostpassword"><?php echo
    _CD_LOGIN_LOST_PASSWORD; ?></a> | 
                <?php if ($registration_enabled): ?>
                  <a href="<?php echo sefRelToAbs($link_new_account); ?>" title="<?php echo
        _CD_LOGIN_CREATE_ACCOUNT; ?>" class="cd_login-createaccount"><?php echo
        _CD_LOGIN_CREATE_ACCOUNT; ?></a>
                <?php else: ?>
                  
                  <span style="text-decoration: line-through; color: #A9A9A9"><?php echo
        _CD_LOGIN_CREATE_ACCOUNT; ?></span>
                <?php endif; ?>
              </div>
          </td>
        </tr>
        <?php if ($registration_enabled): ?>
        <tr>
          <td> </td>
        </tr>
        <?php endif; ?>
      </table>
      <div class="cd_imglogo"></div>
      <?php echo $posttext; ?>
      <input type="hidden" name="option" value="login" />
      <input type="hidden" name="op2" value="login" />
      <input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
      <input type="hidden" name="return" value="<?php echo htmlspecialchars(sefRelToAbs
        ($login)); ?>" />
      <input type="hidden" name="message" value="<?php echo htmlspecialchars($message_login); ?>" />
      <input type="hidden" name="force_session" value="1" />
      <input type="hidden" name="<?php echo $validate; ?>" value="1" />
    </form>

  </div>
  <?php if (_CD_LOGIN_LOGIN_MESSAGE == ''): ?>
  <?php else: ?>
  <div class="cd_login_message_to_users"><span><?php echo _CD_LOGIN_LOGIN_MESSAGE; ?></span></div>
  <div style="height: 5px"></div>
  <?php endif; ?>
<?php
        // load licence
        $licence_file = $mosConfig_absolute_path .
            '/mambots/system/cd_scriptegrator/utils/php/licence.php';
        if (File_Exists($licence_file))
            require $licence_file;
?>

</div>
	<?php
    }
?>
