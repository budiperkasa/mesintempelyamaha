<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

// load needed HTTP variables
$task = mosGetParam( $_REQUEST, 'task', 'index');
$act = mosGetParam( $_REQUEST, 'act', $task );
$format = mosGetParam( $_REQUEST, 'f', 'html' );

// load required files
require_once( $mainframe->getPath( 'front_html' ) );
require_once( $mainframe->getPath( 'class' ) );

// Global "Common Extension Functions" Class Instance
$fwdExt = new fwd_Extension('com_tag');
// load config
require_once( $fwdExt->ext_path.'tag.config.php' );
// load API
require_once( $fwdExt->ext_path.'tag.api.php' );

// load language
//require_once( $fwdExt->ext_path.'language/'.$fwdExt->Get($jmConfig,'lang').'.php' );

// build url
$fwd_URL = new fwd_URL('index.php');
$fwd_URL->setParam('option', $fwdExt->ext_name);
$fwd_URL->setParam('task', $task);
$fwd_URL->setParam('act', $act);
$fwd_URL->setParam('Itemid', $Itemid);

// instantiate template tasker
$fwd_Template = new fwd_Template('com_tag_tmpl_', $format);

// instantiate tasker
require_once($fwdExt->ext_path.'tag.tasks.php');
$fwd_Tasker = new fwd_Tasker('com_tag_', $task, $act);

// call task and handle any errors
if (!$fwd_Tasker->callTaskAct()) {

	if ($fwd_Tasker->task_exists()) {
		$fwd_URL->setParam('task', $task);
		$msg = 'No handler exists for the requested action - "'.$act.'"';
		//$fwdExt->redirect($jm_URL->toString(), $msg);
	} else {
		$msg = 'No handler exists for the requested task - "'.$task.'"';
		//$fwdExt->redirect($jm_URL->toString(), $msg);
	}
	echo $msg; // todo, fix redirect issues
}

?>