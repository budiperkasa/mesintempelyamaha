<?php

/**
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

class HTML_tag {

	// defauld
	function view(&$row, $f = 'html') {
		global $fwdExt;

	}
	
	// back button
	function back_button($label = '[Back]') {
		echo '<input type="button" onclick="history.go(-1);" value="'.$label.'" />';
	}
	
	// gen error messages
	function error($msg = false, $class_sfx = 'error') {
		echo '<span class="error">';
		echo $msg;
		echo '</span>';
	}
	
	// render mambots
	function _callPlugins(&$row) {
		global $_MAMBOTS;
		// get necessary params for plugins
		$page = 0; // todo
		$attribs = NULL; // todo
		$params = new mosParameters( $attribs );
		
		// process plugins
		$_MAMBOTS->loadBotGroup( 'content' );
		
		$results = $_MAMBOTS->trigger( 'onAfterDisplayTitle', array( $row, $params, $page ) );
		$after_title = trim( implode( "\n", $results ) );

		$results = $_MAMBOTS->trigger( 'onBeforeDisplayContent', array( $row, $params, $page ) );
		$before_content = trim( implode( "\n", $results ) );
		
		$_MAMBOTS->trigger( 'onPrepareContent', array( &$row, &$params, $page ), true );
		
		$results = $_MAMBOTS->trigger( 'onAfterDisplayContent', array( $row, $params, $page ) );
		$after_content = trim( implode( "\n", $results ) );
		
		return array($after_title, $before_content, $row, $after_content);
	}
}
      
?>
