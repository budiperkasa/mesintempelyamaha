<?php

/**
* Tag - Allows tagging of Joomla Content Items
* @version 0.1
* @copyright (c) 2006, Fiji Web Design, www.fijiwebdesign.com
* @license GNU/GPL
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

//error_reporting( E_ALL );

// only run mambot if the tags component is installed
// you can remove this if block for better performance
if (file_exists($mosConfig_absolute_path.'/components/com_tag/tag.php')) {
	$_MAMBOTS->registerFunction( 'onAfterDisplayContent', 'tag_rendercontent');
	tag_loadExternalScripts();
}

// adds CSS and JS tags to <head> section
function tag_loadExternalScripts() {
	global $mosConfig_live_site, $mainframe;

	$scripts[] = '<script type="text/javascript" src="'.$mosConfig_live_site.'/index2.php?option=com_tag&task=external&act=js&no_html=1"></script>';
	$scripts[] = '<link type="text/css" rel="stylesheet" href="'.$mosConfig_live_site.'/index2.php?option=com_tag&task=external&act=css&no_html=1" />';
	$mainframe->addCustomHeadTag(implode("\r\n", $scripts));
}

// get the params set for the plugin
function tag_getParams() {
	global $database, $_MAMBOTS;

	if ( !isset($_MAMBOTS->_content_mambot_params['tag']) ) {
		// load mambot params info
		$query = "SELECT params"
		. "\n FROM #__mambots"
		. "\n WHERE element = 'tag'"
		. "\n AND folder = 'content'"
		;
		$database->setQuery( $query );
		$database->loadObject($mambot);

		// save query to class variable
		$_MAMBOTS->_content_mambot_params['tag'] = $mambot;
	}

	$mambot = $_MAMBOTS->_content_mambot_params['tag'];

 	$botParams = new mosParameters( $mambot->params );
	return $botParams;
}

/**
* Checks if tag is being rendered in a module.
* This check may sometimes fail, as some mods may implemnt everything a content Item does.. 
* eg: "content anywhere module".
*/
function tag_isInModule($row) {
	if (!(isset($row->introtext) && isset($row->title_alias))) {
		return true;
	}
	return false;
}

/**
* Main function that renders the tag HTML.
*/
function tag_rendercontent(&$row, $params, $page = 0){
	global $mosConfig_absolute_path, $database, $Itemid;
	
	if (tag_isInModule($row)) {
		return false;
	}

	// Classes
	require_once($mosConfig_absolute_path.'/components/com_tag/tag.class.php');
	require_once( $mosConfig_absolute_path.'/components/com_tag/tag.api.php' );
	// instantiate db class
	$db =& new fwd_commonDB($database, false);
	// instantiate tag class
	$api = new com_tag_API($db);
	// configs
	$Config = new tag_Config($database, '#__tag_config', '#__tag_tabs');
	$config =& $Config->getConfig('config', true);
	
	// retrieve tags for content id
	$tags = $api->getTags($row->id); 
	$str = '';
	$str .= '<div class="tags">';
	if (is_array($tags)) {
		$sep = '';
		$str .= '<ul class="taglist" id="taglist_'.$row->id.'">';
		foreach($tags as $tag) {
			$str .= '<li class="tag">';
			$str .= '<span>'.$sep.'</span>';
			$str .= '<a rel="tag" href="'.sefRelToAbs('index.php?option=com_tag&amp;tag='.$tag->tag.'&amp;Itemid='.$Itemid).'">'.$tag->tag.'</a>';
			//$str .= ' ('.$tag->weight.')';
			$str .= '</li>';
			$sep = $config['tags_sep'];
		}
		$str .= '<li class="tag_add">&nbsp;|&nbsp;<a href="#" onclick="fwd_Tag(\''.$row->id.'\').addTags(\''.$row->id.'\');return false;">'.$config['add_link_txt'].'</a></li>';
		$str .= '</ul>';
	}
	
	$str .= '<span class="sep"></span>';
	$str .= '</div>';
	$str .= '<div class="tag_form" id="tagform_'.$row->id.'"></div>';
	
	return $str;
	
	// related content items w/ same tags
	$items = $api->getRelatedContentTitles($row->id);
	$str .= '<div class="rel_content">';
	if (is_array($items)) {
		$sep = '';
		$str .= '<ul class="rel_list" id="rel_content_'.$row->id.'">';
		foreach($items as $item) {
			$str .= '<li class="rel_item">';
			$str .= '<span>'.$sep.'</span>';
			$str .= '<a href="'.sefRelToAbs('index.php?option=com_content&task=view&id='.$item->id.'&Itemid='.$Itemid).'">'.$item->title.'</a>';
			$str .= ' ('.$item->weight.')';
			$str .= '</li>';
			$sep = ', ';
		}
		$str .= '</ul>';
	}
	
	$str .= '<span class="sep"></span>';
	$str .= '</div>';
	$str .= '<div class="tag_form" id="tagform_'.$row->id.'"></div>';
	
	return $str;
}

// exclude this Content ID as defined in the parameters
function tag_excludeCid($cid, $excluded_cids) {
	if (trim($excluded_cids) != '') {
		$arr = split(',', $excluded_cids);
		if (count($arr) > 0) {
			foreach($arr as $val) {
				if ($cid == trim($val)) {
					return true;
				}
			}
		}
	}
	return false;
}

?>