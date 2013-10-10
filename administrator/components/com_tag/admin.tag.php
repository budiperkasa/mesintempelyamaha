<?php

//echo '<pre>'.print_r($_REQUEST, 1).'</pre>';

/**
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

// authenticate user first
if (!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all')
  | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_tag'))) {
	mosRedirect('index2.php', _NOT_AUTH);
}

// required files
require_once($mainframe->getPath('admin_html'));
require_once($mainframe->getPath('class'));

// common functions
$fwdExt = new fwd_Extension('com_tag');

// http vars
$task = trim(mosGetParam($_REQUEST, 'task', 'index'));
$act = trim(mosGetParam($_REQUEST, 'act', $task));

// url class
$fwd_URL = new fwd_URL('index2.php');
$fwd_URL->setParam('option', $fwdExt->ext_name);
$fwd_URL->setParam('task', $task);
$fwd_URL->setParam('act', $act);

// include tasks file
require_once($fwdExt->absolute_path().'/administrator/components/com_tag/admin.tag.tasks.php');

/**
* Calls the appropriate Admin Task and Act function
*/
$fwd_Tasker = new fwd_Tasker('tag_admin_', $task, $act);

// called before UI
if (is_callable(array('tag_admin', 'before'))) {
	tag_admin::before();
}

if (!$fwd_Tasker->callTaskAct()) {
	// route error, we should not get here via the Admin Panel UI
	// only case should be if a user types a url in that isn't linked to a task or action
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

// called after UI
if (is_callable(array('tag_admin', 'before'))) {
	tag_admin::after();
}

?>
