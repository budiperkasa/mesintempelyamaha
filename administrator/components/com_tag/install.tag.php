<?php

/**
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @package tags
 * @name tag
 */

defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

function com_install() {
	
	global $database;
	
	// saves error msgs
	$errors = array();
	
	// modify menu images
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_tag&task=config'");
	$database->query();
	
	// create tables and insert data if not exist
	// a bug in Mambo/Joomla database.php disallows our use of #__ in between single quotes (')
	$database->setQuery("SHOW TABLES LIKE '{$database->_table_prefix}tag'");
	$tags = strlen($database->loadResult());
	$database->setQuery("SHOW TABLES LIKE '{$database->_table_prefix}tag_config'");
	$tag_config = strlen($database->loadResult());
	$database->setQuery("SHOW TABLES LIKE '{$database->_table_prefix}tag_tabs'");
	$tag_tabs = strlen($database->loadResult());

	// make create and inserts
	if (!$tags) {
		$query = "CREATE TABLE `#__tag` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(25) collate utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `cid` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$database->setQuery($query);
		if (!$database->query()) {
			$errors[] = 'Creation of Tags Table failed';
		}
	}

	if (!$tag_config) {
		$query = "CREATE TABLE `#__tag_config` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(20) collate utf8_bin NOT NULL,
  `label` varchar(100) collate utf8_bin NOT NULL,
  `value` varchar(200) collate utf8_bin NOT NULL,
  `type` varchar(3) collate utf8_bin NOT NULL,
  `range` mediumtext collate utf8_bin NOT NULL,
  `desc` mediumtext collate utf8_bin NOT NULL,
  `namespace` varchar(20) collate utf8_bin NOT NULL,
  `tabid` tinyint(2) NOT NULL,
  `html` varchar(20) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;";
		$database->setQuery($query);
		if (!$database->query()) {
			$errors[] = 'Tags Configuration Table Creation Failed.';
		}
	}
	
	$database->setQuery("SELECT count(id) FROM #__tag_config");
	if ($database->loadResult() === 0) {
		$query = "INSERT INTO `#__tag_config` (`id`, `name`, `label`, `value`, `type`, `range`, `desc`, `namespace`, `tabid`, `html`) VALUES (1, 0x6164645f62746e5f747874, 0x41646420427574746f6e2054657874, 0x53617665, 0x737472, '', 0x546865206c6162656c206f662074686520627574746f6e20746f20636c69636b20746f207361766520746865207461677320696e70757474656420627920757365722e20, 0x636f6e666967, 1, 0x696e707574626f78),
(2, 0x6164645f6c696e6b5f747874, 0x4164642054616773204c696e6b2054657874, 0x4164642054616773, 0x737472, '', 0x546865206c6162656c206f6620746865204c696e6b207468617420697320636c69636b656420746f206164642074616773202873686f77732074686520616464207461677320666f726d292e, 0x636f6e666967, 1, 0x696e707574626f78),
(3, 0x746167735f736570, 0x5461677320536570657261746f72, 0x2c20, 0x737472, '', 0x54686520536570617261746f72206265747765656e20696e646976696475616c20746167732e206";
		$database->setQuery($query);
		if (!$database->query()) {
			$errors[] = 'Configuration Data Insert Failed';
		}
	}
	
	if (!$tag_tabs) {
		$query = "CREATE TABLE  `#__tag_tabs` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(100) collate utf8_bin NOT NULL,
  `desc` varchar(200) collate utf8_bin NOT NULL,
  `category` varchar(20) collate utf8_bin NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;";
		$database->setQuery($query);
		if (!$database->query()) {
			$errors[] = 'Tags Tabs Table Creation Failed.';
		}
	}
	
	$database->setQuery("SELECT count(id) FROM #__tag_tabs");
	if ($database->loadResult() === 0) {
		$query = "INSERT INTO `#__tag_tabs` (`id`, `label`, `desc`, `category`, `parent`) VALUES (1, 0x5549, 0x5573657220496e746572666163652053657474696e6773, 0x636f6e666967, 0);";
		$database->setQuery($query);
		if (!$database->query()) {
			$errors[] = 'Data Insert Failed';
		}
	}


?>
<!-- begin Fiji Web Design Component Credits //-->
<div style="text-align:center;">
  <table width="100%" border="0">
    <tr>
      <td><img src="components/com_tag/images/fwdlogo.jpg"></td>
      <td>
        <div style="font-weight:bold;">Tags - A Fiji Web Design Component!</div>
        <div class="small">Copyright &copy; 2007 <a href="http://fijiwebdesign.com">Fiji Web Design</a>. All Rights Reserved.</div>
        <div class="small">This component is copyrighted software. Distribution is prohibited.</div>
      </td>
    </tr>
    <tr>
      <td background="F0F0F0" colspan="2">
		<?php if (!count($errors)) { ?>
        <p style="color:green;font-weight:bold;">Installation completed successfully.</p>
		<?php } else { 
			echo '<p style="color:red;font-weight:bold;">Installation has errors. Email info@fijiwebdesign.com with this screen shot.</p>';
			foreach($errors as $error) {
				echo '<p style="color:red;">'.$error.'</p>';
			}
		}
		?>
      </td>
    </tr>
  </table>
</div>
<!-- end Fiji Web Design Component Credits //-->
<?php
}
?>