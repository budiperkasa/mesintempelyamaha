<?php

/**
 * JooMail
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */

// make sure the file is being included by a parent file
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

require_once $mosConfig_absolute_path.'/components/com_tag/includes/json.pear.php';
$JSON = new Services_JSON();

/**
* Static Class methods for JSON output for the Admin UI
*/
class tag_admin_json {
	
	function publish($list) {
		global $JSON;
		
		if ($list) {
			echo $JSON->encode($list);
		} else {
			echo $JSON->encode(false);
		}
	}

}

?>
