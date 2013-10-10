<?php 
	/**
	 * Xmap by Daniel Grothe
	 * a sitemap component for Joomla! CMS (http://www.joomla.org)
	 * Author Website: http://www.ko-ca.com
	 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
	 * Additional work by mic (http://www.mgfi.info)
	 * @version $Id: admin.xmap.php,v 0.1 2006/03/18 15:12:12 mic Exp $
	 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );


// check access permissions (only superadmins & admins)
if ( !( $acl->acl_check('administration', 'config', 'users', $my->usertype) ) 
	||  $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_xmap') ) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}

$cid 		= mosGetParam( $_POST, 'cid', array(0) );
$task 		= mosGetParam( $_REQUEST, 'task', '' );

global $mosConfig_live_site;

if (defined('JPATH_ADMINISTRATOR')) {
	global $xmapComponentURL,$xmapSiteURL,$xmapComponentPath,$xmapAdministratorURL,$xmapLang,$xmapAdministratorPath;
	define ('_XMAP_JOOMLA15',1);
	$xmapLang = $mosConfig_lang;
	$xmapComponentPath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_xmap';
	$xmapComponentURL = $mosConfig_live_site.'/administrator/components/com_xmap';
	$xmapAdministratorPath = JPATH_ADMINISTRATOR;
	$xmapAdministratorURL = $mosConfig_live_site.'/administrator';
	$xmapSiteURL = $mosConfig_live_site;
} else {
	global $mosConfig_lang,$mosConfig_absolute_path,$xmapComponentURL,$xmapSiteURL,$xmapComponentPath,$xmapAdministratorURL,$xmapLang,$xmapAdministratorPath;
	define ('_XMAP_JOOMLA15',0);
	$xmapLang = $mosConfig_lang;
	$xmapComponentPath = $mosConfig_absolute_path.'/administrator/components/com_xmap';
	$xmapAdministratorPath = $mosConfig_absolute_path.'/administrator';
	$xmapComponentURL = $mosConfig_live_site.'/administrator/components/com_xmap';
	$xmapAdministratorURL = $mosConfig_live_site.'/administrator';
	$xmapSiteURL = $mosConfig_live_site;
}
if (!file_exists($mosConfig_absolute_path.'/includes/joomla.php')) {
	define('_XMAP_MAMBO',1);
}else{
	define('_XMAP_MAMBO',0);
}

// load language file
if( file_exists( $xmapComponentPath .'/language/' . $xmapLang . '.php') ) {
	require_once( $xmapComponentPath .'/language/' . $xmapLang . '.php' );
} else {
	if ($task != 'ajax_request') {
		echo 'Language file [ '. $xmapLang .' ] not found, using default language: english<br />';
	}
	$xmapLang = 'english';
	require_once( $xmapComponentPath .'/language/english.php' );
}

// load settings from database
require_once( $xmapComponentPath.'/classes/XmapConfig.php' );
require_once( $xmapComponentPath.'/admin.xmap.html.php' );
$config = new XmapConfig;
if( !$config->load() ) {
	$text = _XMAP_ERR_NO_SETTINGS."<br />\n";
	$link = 'index2.php?option=com_xmap&task=create';
	echo sprintf( $text, $link );
}

$admin = new XmapAdmin();
$admin->show( $config, $task, $cid );

class XmapAdmin {
	
	var $config = null;
	
	/** Parses input parameters and calls appropriate function */
	function show( &$config, &$task, &$cid ) {
		$this->config = &$config;
		
		switch ($task) {
			
			case 'save':
				$this->saveOptions( );
				break;
			
			case 'cancel':
				mosRedirect( 'index2.php' );
				break;
			
			case 'publish':
				$this->toggleMenu( $cid[0], true, 'site' );
				break;
			
			case 'publish_google':
				$this->toggleMenu( $cid[0], true, 'google' );
				break;
			
			case 'unpublish':
				$this->toggleMenu( $cid[0], false, 'google' );
			
			case 'unpublish_google':
				$this->toggleMenu( $cid[0], false, 'site' );
				break;
			
			case 'orderup':
				$this->orderMenu( $cid[0], -1 );
				break;
			
			case 'orderdown':
				$this->orderMenu( $cid[0], 1 );
				break;
			
			case 'create':
				$config->create();
				$this->showSettingsDialog();
				break;
			
			case 'restore':
				if( $config->restore() ){
					echo _XMAP_MSG_SET_RESTORED . "<br />\n";
				}
				$this->showSettingsDialog();
				break;
			
			case 'backup':
				if( $config->backup() ){
					echo _XMAP_MSG_SET_BACKEDUP . "<br />\n";
				}
				$this->showSettingsDialog();
				break;
			case 'ajax_request':
				include('ajaxResponse.php');
				exit; 
				break;
			default:
				$success = mosGetParam($_REQUEST,'success','');
				$this->showSettingsDialog($success);
				break;
			
		}
	}
	
	/** Show settings dialog
	  * @param integer  configuration save success
	  */
	function showSettingsDialog( $success = 0 ) {
		global $mainframe, $database;

		$menus = $this->getMenus();
		# $this->sortMenus( $menus );
		
		$config = &$this->config;

	
	    // success messages
		switch( $success ) {
	    	case 1:
	    		$lists['msg_success'] = _XMAP_MSG_SET_BACKEDUP;
	    		break;
	    	case 2:
	    		$lists['msg_success'] = _XMAP_ERR_CONF_SAVE;
	    		break;
	    	default:
	    		$lists['msg_success'] =  _XMAP_CFG_COM_TITLE;
	    		break;
		}
		require_once( $mainframe->getPath( 'admin_html' ) );
		XmapAdminHtml::show( $config, $menus, $pageNav, $lists );
	}

	/** Save settings handed via POST */
	function saveOptions( ) {
		global $mosConfig_absolute_path;
	
		// save css
		$csscontent	= mosGetParam( $_POST, 'csscontent', '', _MOS_ALLOWHTML );	// CSS
		$file 		= $mosConfig_absolute_path.'/components/com_xmap/css/xmap.css';
		$enable_write	= mosGetParam( $_POST, 'enable_write', 0 );
		$oldperms	= fileperms($file);
		$success	= 1;
	
		if ( $enable_write ) {
			@chmod( $file, $oldperms | 0222 );
		}
	
		clearstatcache();
		
		if( $fp = @fopen( $file, 'w' )) {
			fputs( $fp, stripslashes( $csscontent ) );
			fclose( $fp );
			if( $enable_write ) {
				@chmod( $file, $oldperms );
			}else{
				if( mosGetParam( $_POST, 'disable_write', 0 )){
					@chmod($file, $oldperms & 0777555);
				}
			}
		} else {
			if( $enable_write ){
				@chmod( $file, $oldperms );
			}
		}
		// end CSS
	
		
		mosRedirect('index2.php?option=com_xmap&success='.$success);
		exit;
	}



//------------------------------ MISC FUNCTIONS ------------------------------//


	/** get the complete list of menus in joomla */
	function &getMenus() {
		$config = &$this->config;
		
		if (defined('JPATH_ADMINISTRATOR')) {
			require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
			$menutypes  = MenusHelper::getMenuTypeList();

			$allmenus = array();
			$i=0;
			foreach( $menutypes as $menu ) {
				$menutype = $menu->menutype;
				$allmenus[$menutype] = new stdclass;
				$allmenus[$menutype]->ordering = $i;
				$allmenus[$menutype]->show = false;
				$allmenus[$menutype]->showSitemap = false;
				$allmenus[$menutype]->priority = '0.5';
				$allmenus[$menutype]->changefreq = 'weekly';
				$allmenus[$menutype]->id = $i;
				$allmenus[$menutype]->type = $menutype;
				$i++;
			}
			
		} else {
			$menutypes  = mosAdminMenus::menutypes();

			$allmenus = array();
			foreach( $menutypes as $index => $menutype ) {
				$allmenus[$menutype] = new stdclass;
				$allmenus[$menutype]->ordering = $index;
				$allmenus[$menutype]->show = false;
				$allmenus[$menutype]->showSitemap = false;
				$allmenus[$menutype]->priority = '0.5';
				$allmenus[$menutype]->changefreq = 'weekly';
				$allmenus[$menutype]->id = $index;
				$allmenus[$menutype]->type = $menutype;
			}
		}
	
		return $allmenus;
	}
}