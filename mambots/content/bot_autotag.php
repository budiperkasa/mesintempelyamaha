<?php
/**
* @copyright Copyright (C) 2007 Fiji Web Design. All rights reserved.
* @license http://www.fijiwebdesign.com/
* @author gabe@fijiwebdesign.com
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

// register content event handlers
$_MAMBOTS->registerFunction( 'onPrepareContent', 'bot_autotag_content' );

/**
* Runs the Custom Code in the onprepare_php param to modify displayed content
*/
function bot_autotag_content( $published, &$row, &$params, $page=0 ) {
	global $mosConfig_absolute_path, $database;
	
	// only if published
	if (!$published) {
		return;
	}
	
	// only articles plz
	if (!isset($row->title_alias) || !isset($row->introtext) || !isset($row->id)) {
		return;
	}
	
	// get the plugin parameters
	$botParams = bot_autotag_getParams('bot_autotag');
	
	// params
	$min_keyword_len = $botParams->def('min_keyword_len', 4);
	$common_keywords = $botParams->def('common_keywords', '');
	
	// get keywords from title
	$title = isset($row->title) ? preg_replace("/[^a-z0-9 ]/", '', strtolower($row->title)) : '';
	$title_alias = isset($row->title_alias) ? preg_replace("/[^a-z0-9 ]/", '', strtolower($row->title_alias)) : '';
	$keywords = array_unique(explode(' ', $title.' '.$title_alias));
	
	// do we have keywords?
	if (!count($keywords)) {
		return;
	}
	// get only keywords > $min_keyword_len
	$good_keywords = array();
	$ignored_keywords = explode(' ', $common_keywords);
	foreach($keywords as $i=>$keyword) {
		if (strlen($keyword) >= $min_keyword_len && !in_array($keyword, $ignored_keywords)) {
			$good_keywords[] = $keyword;
		}
	}
	unset($common_keywords, $ignored_keywords);

	// select current tags in com_tag db (#__tag) for this content item
	$query = "SELECT tag FROM #__tag WHERE cid = ".intval($row->id);
	$database->setQuery($query);
	$tag_rows = $database->loadObjectList();
	$tags = array();
	if (count($tag_rows)) {
		foreach($tag_rows as $tag_row) {
			$tags[] = $tag_row->tag;
		}
	}
	// remove existing keywords
	$insert_keywords = array();
	foreach($good_keywords as $keyword) {
		if (!in_array($keyword, $tags)) {
			$insert_keywords[] = $keyword;
		}
	}
	// build sql query
	if (count($insert_keywords)) {
		$query = "INSERT INTO #__tag (`cid`, `tag`, `published`) VALUES ";
		$comma = '';
		foreach($insert_keywords as $keyword) {
			$query .= "$comma ({$row->id}, '{$keyword}', 1)\n";
			$comma = ',';
		}
		$database->setQuery($query);
		if (!$database->query()) {
			echo $database->stdErr();
		}
		//var_dump($query);
	}

	return true;
}

/**
* Retrieves parameters for a plugin
* @param plugin name (value of 'element' column in #__mambots db table)
*/
function bot_autotag_getParams($plugin) {
	global $database, $_MAMBOTS;

	if ( !isset($_MAMBOTS->_content_mambot_params[$plugin]) ) {
		// load mambot params info
		$query = "SELECT params"
		. "\n FROM #__mambots"
		. "\n WHERE element = '$plugin'"
		. "\n AND folder = 'content'"
		;
		$database->setQuery( $query );
		$database->loadObject($mambot);

		// save query to class variable
		$_MAMBOTS->_content_mambot_params[$plugin] = $mambot;
	}

	$mambot = $_MAMBOTS->_content_mambot_params[$plugin];

 	$botParams = new mosParameters( $mambot->params );
	return $botParams;
}



?>