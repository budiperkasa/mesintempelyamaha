<?php

/**
 * Tag Component
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
 
defined('_VALID_MOS') or die('Direct Access to this tag is not allowed.');

global $database, $fwdExt, $my, $Itemid, $mosConfig_live_site;
$Config = new tag_Config($database, '#__tag_config', '#__tag_tabs');
$configs = $Config->getConfig('config');
$configs['user_id'] = $my->id;
$configs['Itemid'] = $Itemid;
$configs['live_site'] = $mosConfig_live_site;

// include JSON Lib
require_once($fwdExt->ext_path.'includes/json.pear.php');
$JSON = new Services_JSON();

$js_configs = $JSON->encode($configs);

echo "fwd_Tag.config = $js_configs;";

?>