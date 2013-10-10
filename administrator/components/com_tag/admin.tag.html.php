<?php

/**
 * Tag
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

// make sure the file is being included by a parent file
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

/**
* Static Class methods for HTML output in the Admin UI
*/
class tag_admin_html {

	/**
	* This breaks the Joomla Admin panel's task flow
	* Instead we use actions, which are sub-tasks
	* This allows a two-tier grouping of tasks. A main task, and many nested sub-tasks. 
	*/
	function before() {
		// javascript to overide submitbutton()
		?>
		
		<script type="text/javascript">
		
			jm_admin = function() { /** copyright www.fijiwebdesign.com **/ };
		
			// overrides submitbutton() so that is delegates acts (sub-tasks), not tasks 
			function submitbutton(act) {
				document.forms['adminForm'].act.value = act;
				jm_admin.submitAction(act);
			}
			
			// override if necessary
			jm_admin.submitAction = function(act) {
				document.forms['adminForm'].submit();
			}
		
		</script>
		
		
		<?php
		
	}
	
	function after() {
		// todo
	}
	
	/**
	* Display a Message
	*/
	function msg($msg, $class = 'message') {
		echo '<div class="'.$class.'">'.$msg.'</div>';
	}
	
	/**
	* The Admin Header
	*/
	function adminHeader($name = "Index", $image = 'fwdlogo.jpg', $html = '') {
		global $fwdExt;
		echo '<table class="adminheading">';
		echo '<tbody><tr>';
		echo '	<th style="background-image: url('.$fwdExt->live_site().'/administrator/components/com_tag/images/'.$image.');">';
			echo 'Tag - <small>'.$name.'</small>';

			echo '</th>';
			echo '<td>';
			echo $html;
			echo '<td>';
		echo '</tr>';
		echo '</tbody></table>';
	}
	
	/**
	* Start Admin Form. todo: change default method to post
	*/
	function startAdminForm($method = 'post') {
		echo '<form name="adminForm" action="index2.php" method="'.$method.'">';
	}
	
	/**
	* End Admin Form
	* @param array hidden fields
	*/
	function endAdminForm($hidden = false) {
		if ($hidden) {
			foreach($hidden as $i=>$v) {
				echo '<input type="hidden" name="'.$i.'" value="'.$v.'" />'."\r\n";
			}
		}
		echo '<input type="hidden" name="boxchecked" value="0" />'."\r\n";
		echo '</form>';
	}

	/**
	* Lists all the Tags
	*/
	function listTags(&$rows, &$pageNav) {
		global $fwdExt, $database, $fwd_URL;
		
		// styles
		?>
		<style>
		table.adminlist tr.header th {
			text-align: left;
		}
		td.row_publish, td.row_creator, .head_creator, .head_publish {
			text-align: center !important;
		}
		</style>
		<script>

		jm_admin.publishing  = function(cid, published) {
			var f = document.forms['adminForm'];
			var els = f.getElementsByTagName('input');
			for(var i = 0; i < els.length; i++) {
				if (els[i].type == 'checkbox') {
					els[i].checked = false;
				}
			}
			var checkbox = document.getElementById('cb'+cid);
			checkbox.checked = true;
			f.boxchecked.value = 1;
			var act = published ? 'unpublish' : 'publish';
			submitbutton(act);
		};
		
		// override
		jm_admin.submitAction = function(act) {
			var f = document.forms['adminForm'];
			if (act == 'publish' || act == 'unpublish') {
				if (f.boxchecked.value == 0) {
					alert('Please make a selection first.');
					return false;
				}
			}
			f.submit();
		}
		
		</script>
		<?php
		
		// start admin form
		tag_admin_html::startAdminForm();
		
		//$fwdExt->dump($rows[0]);
		$m = 0;

		// table
		echo '<table class="adminlist">';
		echo '<tr class="header">';
			echo '<th class="head_id"><input type="checkbox" name="toggle" value="" onclick="checkAll('.count( $rows ).');" /></th>';
			echo '<th class="head_tag">Tag</th>';
			echo '<th class="head_user">User Id</th>';
			echo '<th class="head_createdate">Create Date</th>';
			echo '<th class="head_cid">Content Title</th>';
			echo '<th class="head_published">Published</th>';
		echo '</tr>';
		foreach($rows as $i=>$row) {
			$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			$alt = $row->published ? 'Published' : 'Unpublished';
			echo '<tr class="row'.$m.'">';
			echo '<td class="row_id">'.mosHTML::idBox($i, $row->id).'</td>';
			echo '<td class="row_tag"><a href="index2.php?option=com_tag&task=index&where[tag]='.$row->tag.'">'.$row->tag.'</a></td>';
			echo '<td class="row_userid"><a href="index2.php?option=com_tag&task=index&where[u.username]='.$row->username.'">'.$row->username.'</a></td>';
			echo '<td class="row_createdate"><a href="index2.php?option=com_tag&task=index&where[t.create_date]='.$row->create_date.'">'.$row->create_date.'</a></td>';
			echo '<td class="row_cid"><a href="index2.php?option=com_tag&task=index&where[t.cid]='.$row->cid.'">'.$row->ctitle.'</a></td>';
			echo '<td class="row_publish"><img src="images/'.$img.'" width="12" height="12" border="0" alt="'.$alt.'" onclick="jm_admin.publishing('.$i.', '.$row->published.');" /></td>';
			echo '</tr>';	
			$m = 1 - $m;
		}
		echo '</table>';
		
		// navigation
		echo $pageNav->getListFooter();
		
		// end admin form
		$fields = array();
		$fields['option'] = $fwd_URL->getParam('option');
		$fields['task'] = $fwd_URL->getParam('task');
		$fields['act'] = $fwd_URL->getParam('act');
		tag_admin_html::endAdminForm($fields);
	}
	
	/**
	* Edits the Tags
	*/
	function editTags(&$rows) {
		global $fwdExt, $database, $fwd_URL;
		
		// styles
		?>
		<style>
		table.adminlist tr.header th {
			text-align: left;
		}
		td.row_publish, td.row_creator, .head_creator, .head_publish {
			text-align: center !important;
		}
		</style>
		<script>
		
		// override
		jm_admin.submitAction = function(act) {
			var f = document.forms['adminForm'];
			if (act == 'save' || act == 'update') {
				if (confirm('Save the changes you have made?')) {
					f.submit();
				}
			}
			if (act == 'cancel') {
				if (confirm('Discard the changes you may have made?')) {
					f.submit();
				}
			}
		}
		
		</script>
		<?php
		
		// start admin form
		tag_admin_html::startAdminForm();
		
		//$fwdExt->dump($rows[0]);
		$m = 0;
		
		// todo
		$descs = array(
			'id' => array('ID', 'The Entry ID', 'disabled'),
			'tag' => array('Tag', 'The Tag/Keyword', 'input'),
			'user_id' => array('User ID', 'Id of user that created the tag', 'input'),
			'create_date' => array('Creation Date', 'Creation Date', 'disabled'),
			'cid' => array('Item ID', 'Item ID tag is saved to', 'disabled'),
			'published' => array('Status', 'Publish or unpublish this Entry.', 'publish')
		);

		// table
		echo '<table class="adminform">';
		foreach($rows as $i=>$row) {
			echo '<tr><th colspan="3">Tag ID: '.$row->id.'</th></tr>';
			foreach($row as $name=>$value) {
				echo '<tr>';
				echo '<td class="label">'.$descs[$name][0].'</td>';
				echo '<td class="field">';
				switch($descs[$name][2]) {
					case 'disabled':
					echo '<input type="text" name="'.$name.'['.$row->id.']" value="'.$value.'" disabled="disabled" />';
					break;
					case 'bool':
					case 'publish':
					echo '<select name="published['.$row->id.']">';
					echo '<option value="0"'.(!$value ? ' selected="selected"' : '').'>Unpublished</option>';
					echo '<option value="1"'.($value ? ' selected="selected"' : '').'>Published</option>';
					echo '</select>'; 
					break;
					default:
					echo '<input type="text" name="'.$name.'['.$row->id.']" value="'.$value.'" />';
					break;
					echo '</td>';
				}
				echo '<td class="desc">'.$descs[$name][1].'</td>';
				echo '</tr>';
			}	
			$m = 1 - $m;
		}
		echo '</table>';
		
		// end admin form
		$fields = array();
		$fields['option'] = $fwd_URL->getParam('option');
		$fields['task'] = $fwd_URL->getParam('task');
		$fields['act'] = $fwd_URL->getParam('act');
		$fields['cid'] = $fwd_URL->getParam('cid'); // rememberd for update action
		tag_admin_html::endAdminForm($fields);
	}
	
	/**
	* Add Tags Template
	*/
	function addTags(&$rows) {
		global $fwdExt, $database, $fwd_URL, $my;
		
		// styles
		?>
		<style>
		table.adminlist tr.header th {
			text-align: left;
		}
		td.row_publish, td.row_creator, .head_creator, .head_publish {
			text-align: center !important;
		}
		</style>
		<script>
		
		// override
		jm_admin.submitAction = function(act) {
			var f = document.forms['adminForm'];
			if (act == 'insert') {
				if (confirm('Save the changes you have made?')) {
					f.submit();
				}
			}
			if (act == 'cancel') {
				if (confirm('Discard the changes you may have made?')) {
					f.submit();
				}
			}
		}
		// clone the table and add to form
		jm_admin.copyRow = function(input) {
			var i = 0;
			var table = input.parentNode;
			while(table.nodeName != 'TABLE' && i < 10) {
				table = table.parentNode;
				i++;
			}
			if (table.nodeName != 'TABLE') return false;
			var table2 = table.cloneNode(true);
			var div = document.getElementById('jm_tables');
			div.appendChild(table2);
		};
		// remove the table
		jm_admin.removeRow = function(input) {
			var div = document.getElementById('jm_tables');
			if (div.getElementsByTagName('table').length == 1) {
				submitbutton('cancel');
				return false;
			}
			if (!confirm('Remove this entry?')) {
				return false;
			}
			var i = 0;
			var table = input.parentNode;
			while(table.nodeName != 'TABLE' && i < 10) {
				table = table.parentNode;
				i++;
			}
			if (table.nodeName != 'TABLE') return false;
			table.parentNode.removeChild(table);
		};
		
		</script>
		<?php
		
		// start admin form
		tag_admin_html::startAdminForm();
		
		//$fwdExt->dump($rows[0]);
		$m = 0;
		
		// todo
		$descs = array(
			'id' => array('ID', 'The Entry ID', 'id'),
			'tag' => array('Tag', 'The Tag/Keyword', 'input'),
			'user_id' => array('User ID', 'Id of user that created the tag', 'input', $my->id),
			'create_date' => array('Creation Date', 'Creation Date', 'disabled'),
			'cid' => array('Item ID', 'Item ID tag is saved to', 'input'),
			'published' => array('Status', 'Publish or unpublish this Entry.', 'publish')
		);

		// table
		echo '<div id="jm_tables">';
		echo '<table class="adminform">';
		echo '<tbody>';
		foreach($rows as $i=>$row) {
			echo '<tr><th colspan="3"><span style="float:left;">New Tag</span><span style="float:right;"><input type="button" value="Remove" onclick="jm_admin.removeRow(this);" /><input type="button" value="Copy" onclick="jm_admin.copyRow(this);" /></span></th></tr>';
			foreach($row as $name=>$type) {
				echo '<tr>';
				echo '<td class="label">'.$descs[$name][0].'</td>';
				echo '<td class="field">';
				switch($descs[$name][2]) {
					case 'id':
					case 'disabled';
					echo '<input type="text" name="'.$name.'[]" disabled="disabled" />';
					break;
					case 'bool':
					case 'publish':
					echo '<select name="published[]">';
					echo '<option value="0">Unpublished</option>';
					echo '<option value="1">Published</option>';
					echo '</select>'; 
					break;
					default:
					echo '<input type="text" name="'.$name.'[]" value="'.(isset($descs[$name][3]) ? $descs[$name][3] : '').'" />';
					break;
					echo '</td>';
				}
				echo '<td class="desc">'.$descs[$name][1].'</td>';
				echo '</tr>';
			}	
			$m = 1 - $m;
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		
		// end admin form
		$fields = array();
		$fields['option'] = $fwd_URL->getParam('option');
		$fields['task'] = $fwd_URL->getParam('task');
		$fields['act'] = $fwd_URL->getParam('act');
		tag_admin_html::endAdminForm($fields);
	}
	
	/**
	* Iterates through config Items and lists them in a Tabbed interface for editing
	* Supports dynamic ranges specified via the database
	* todo: cache to disk
	*/
	function config(&$tabs, &$configs) {
		global $fwdExt, $database, $fwd_URL;
		
		// styles
		?>
		<style>
		.jm_config_label {
			width: 20%;
		}
		.jm_config_value {
			width: 20%;
		}
		.jm_config_input {
			width: 200px;
		}
		.jm_config_desc {
			width: 60%;
		}
		</style>
		<?php
		
		// start admin form
		tag_admin_html::startAdminForm();
		
		$Range = new tag_Config_range($database);
		
		// default HTML tabs class
		$mostabs = new mosTabs(0);
		
		// start the tabs pane
		$mostabs->startPane("jooMailConfigPane");
		
		foreach($tabs as $tab) {
		
			// start the tab
			$mostabs->startTab($tab->label, 'jooMailconfigTab-'.$tab->id);
		
			// list configs for the tab	
			echo '<table class="adminform">';
			echo '<tr>';
			echo '<th colspan="3">'.$tab->desc.'</th>';
			echo '</tr>';
			foreach($configs as $config) {
				if ($config->tabid == $tab->id) {
					$config->name = 'jmc_'.$config->name;	
					echo '<tr>';
						echo '<td class="jm_config_label">'.$config->label.'</td>';
						echo '<td class="jm_config_value">';
						// boolean value, show yes/no option
						if ($config->type == 'boo') {
							echo mosHTML::yesnoRadioList( $config->name, ' class="inputbox '.$config->name.'"', $config->value );
						} else if ($config->html == 'date') {
						// this is a date input
							echo mosHTML::integerSelectList( 1, 31, 1, $config->name.'[d]', ' id="'.$config->name.'[d]" class="inputbox"', $config->value);
							echo mosHTML::monthSelectList($config->name.'[m]', ' id="'.$config->name.'[m]"  class="inputbox"', $config->value);
							echo mosHTML::integerSelectList( 2007, 2050, 1, $config->name.'[y]', ' id="'.$config->name.'[y]" class="inputbox"', $config->value);
						} else if (!empty($config->range)) {
						// we have a range, evaluate it
							$list = $Range->parse($config->range);
							// todo: support other HTML Form Input types
							echo mosHTML::selectList( $list, $config->name, ' id="'.$config->name.'"  class="inputbox"', 'value', 'text', $config->value );
						} else if ($config->html == 'textarea') {
							echo '<textarea id="'.$config->name.'" name="'.$config->name.'" class="jm_config_input inputbox">'.$config->value.'</textarea>';
						} else {
						// default is inputbox. type should be str or int
							echo '<input type="text"  id="'.$config->name.'" name="'.$config->name.'" value="'.$config->value.'" class="jm_config_input" />';
						}
						echo '</td>';	
						echo '<td class="jm_config_desc">'.$config->desc.'</td>';
					echo '</tr>';
				}			
			}
			echo '</table>';
			
			// end the tab
			$mostabs->endTab();
		}
		
		// end the tabpane
		$mostabs->endPane();
		
		// end admin form
		$fields = array();
		$fields['option'] = $fwd_URL->getParam('option');
		$fields['task'] = $fwd_URL->getParam('task');
		$fields['act'] = $fwd_URL->getParam('act');
		tag_admin_html::endAdminForm($fields);
	}
	
	
	// about this component
	function about() {
	
		echo '<table class="adminform">';
		echo '<tr>';
		echo '<th colspan="3">Tag Component</th>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td>
		<div>
		This Component allows users to tag any component items in the Joomla CMS - such as Content Items.
		</div>
		<div>
		For help with this component contact <a href="mailto:info@fijiwebdesign.com">info@fijiwebdesign.com</a>. 
		</div>		
		</td>';
		echo '</tr>';
		
		echo '</table>';
		
	}


}

?>
