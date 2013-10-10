<?php

/**
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/**
* Wrapper for Joomla HTML Toolbar
*/
class toolbar {

	function custom($task, $icon, $iconOver, $alt, $listSelect = false) {
		mosMenuBar::custom( $task, $icon, $iconOver, $alt, $listSelect );
	}

	function startTable() {
		mosMenuBar::startTable();
	}
	
	function endTable() {
		mosMenuBar::endTable();
	}
	
	function spacer() {
		mosMenuBar::spacer();
	}
	
	function publish($label = 'publish') {
		mosMenuBar::publish($label);
	}
	
	function unpublish($label = 'unpublish') {
		mosMenuBar::unpublish($label);
	}
	
	function divider() {
		mosMenuBar::divider();
	}
	
	function addNew($label = 'new') {
		mosMenuBar::addNew($label);
	}
	
	function editList($task = 'edit', $label = 'Edit') {
		mosMenuBar::editList($task, $label);
	}
	
	function deleteList($task = 'delete', $label = 'Remove') {
		mosMenuBar::deleteList(' ', $task, $label);
	}
	
	function save($task = 'save', $label = 'Save') {
		mosMenuBar::save($task, $label);
	}
	
	function apply($task = 'apply', $label = 'Apply' ) {
		mosMenuBar::apply($task, $label);
	}
	
	function assign($task = 'assign',$alt='Assign' ) {
		mosMenuBar::assign($task, $alt);
	}
	
	function cancel($task = 'cancel',$alt='Cancel' ) {
		mosMenuBar::cancel($task, $alt);
	}


}

/**
* Static Toolbar Class called every page load, wraps toolbar
*/
class admin_toolbar_html {

	function before() {
		toolbar::startTable();
	}
	
	function after() {
		toolbar::endTable();
	}
}

/**
* Static task=index Toolbar Class
*/
class admin_toolbar_html__index {

	function index() {
		toolbar::addNew('add');
		toolbar::editList('edit', 'Edit');
		toolbar::publish('publish', 'Publish');
		toolbar::unpublish('unpublish', 'Unpublish');
		toolbar::deleteList('delete', 'Delete');
	}
	
	function edit() {
		toolbar::save('save', 'Save');
		toolbar::apply('update', 'Update');
		toolbar::divider();
		toolbar::cancel();
	}
	
	function add() {
		toolbar::save('insert', 'Save');
		toolbar::divider();
		toolbar::cancel();
	}
}

/**
* Static task=config Toolbar Class
*/
class admin_toolbar_html__config {

	function config() {

		toolbar::save('save', 'Save');
		toolbar::apply('update', 'Update');
		toolbar::divider();
		toolbar::cancel();

	}
	
	function save() {
		toolbar::startTable();
	}
}

/**
* Static task=about Toolbar Class
*/
class admin_toolbar_html__about {

	function about() {

	}

}


?>
