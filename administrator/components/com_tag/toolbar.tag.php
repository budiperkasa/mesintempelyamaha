<?php

/**
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

require_once( $mainframe->getPath( 'toolbar_html' ) );

// make sure we use the same url params used everywhere else
global $fwd_URL; // defined in admin.tag.php
$task = $fwd_URL->getParam('task');
$act = $fwd_URL->getParam('act');

/**
* Calls the appropriate Admin Toolbar Task and Act function in toolbar.tag.html.php
*/
$fwd_Toolbar_Tasker = new fwd_Tasker('admin_toolbar_html__', $task, $act);

// called before toolbar
admin_toolbar_html::before();

if (!$fwd_Toolbar_Tasker->callTaskAct()) {
	// toolbar class not found
	if ($fwd_Toolbar_Tasker->task_exists()) {
		$msg = 'No Toolbar handler exists for the requested action - "'.$task.'"';
	} else {
		$msg = 'No Toolbar handler exists for the requested task - "'.$act.'"';
	}
	echo '<!-- '.$msg.' -->';
}

// called after toolbar
admin_toolbar_html::after();
?>