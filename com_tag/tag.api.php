<?php

/**
 * Tag API Functions
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 */
class com_tag_API {

	var $tags_tbl;
	var $sep;

	/** constructor */
	function com_tag_api(&$db) {
		if (!$db) {
			global $database;
			$this->db =& new fwd_commonDB($database, false);
		} else {
			$this->db =& $db;
		}
		$this->tags_tbl = '#__tag';
		$this->sep = ',';
	}
	
	/**
	* Retrieve tags for a content Item
	* @param Int $cid Content Item ID
	* @param int $limit Maximun number of tags to retrieve
	*/
	function getTags($cid, $limit = 50, $limitstart = 0, $orderby = 'weight', $order = 'DESC') {
		$cid = intval($cid);
		if ($cid) {
			$query = "SELECT tag, count(id) AS weight "
			."\n FROM ".$this->tags_tbl." "
			."\n WHERE cid = $cid AND published = 1"
			."\n GROUP BY tag"
			."\n ORDER BY $orderby $order, tag, id"
			."\n LIMIT $limitstart, $limit"
			."";
			$tags = $this->db->ObjectList($query);
		} else {
			trigger_error('Content Id not specified', E_USER_ERROR);
			return false;
		}
		return $tags;
	}
	
	/**
	* Retrieve Tag Cloud
	* @param Array $filters And Array of filters in the format of the example:
		array(
			'cid'=>'1, 2, 3'
			'catid'=>'2'
		);
		Only the defined filters: cid, catid, sectid are allowed
	*/
	function getTagCloud($filters, $limit = 100, $limitstart = 0, $published = true) {
		$where = array();
		// only published tags are returned
		if ($published) {
			$where[] = 't.published = 1';
		}
		// where query creation and escaping
		if (is_array($filters)) {
			$fmeta = array(
				'cid'=>array('t.cid', 'int'),
				'catid'=>array('a.catid', 'int'),
				'sectid'=>array('a.sectionid', 'int'),
				'userid'=>array('u.id', 'int')
			);
			// filter rel tags
			if (isset($filters['tag']) && !empty($filters['tag'])) {
				$cids = implode(',', $this->getTagged($filters['tag'], 0, 100, false, false, false));
				$filters['cid'] = !empty($filters['cid']) ? $filters['cid'].','.$cids : $cids;
			}
			// filter the rest
			foreach($filters as $name=>$filter) {
				if (in_array($name, array_keys($fmeta))) {
					if (!empty($filter)) {
						$filter = $this->_escapeArray(explode(',', $filter), $fmeta[$name][1]);
						$where[] = $fmeta[$name][0].' IN ('.implode(',', $filter).')';
					}
				}
			}
		}
		
		// select tags ordered by weight
		$query = "SELECT t.tag, count(t.id) AS weight "
		."\n FROM ".$this->tags_tbl." AS t"
		."\n LEFT JOIN #__content AS a ON (t.cid = a.id)"
		."\n LEFT JOIN #__users AS u ON (t.user_id = u.id)"
		."\n ".(count($where) ? 'WHERE '.implode("\n AND ", $where) : '')
		."\n GROUP BY t.tag"
		."\n ORDER BY weight DESC"
		."\n LIMIT $limitstart, $limit"
		."";
		$tags = $this->db->ObjectList($query);
		
		// reorder results by Alpha
		usort($tags, array('com_tag_API', '_sortAlpha') );
		
		return $tags;
	}
	
	/**
	* Retrieve Tag Cloud for content Items tagged with the given $tag(s)
	* @param Array $tags And Array of tags
	*/
	function getRelatedTagCloud($tags, $limit = 100, $start = 0, $published = true) {
		$filters = array();
		if (!is_array($tags)) {
			trigger_error('$tags must be an Array');
			return false;
		}
		// retrieve ids of related articles
		$cids = $this->getTagged($tags, $start, $limit, false, false, false);
		$filters['cid'] = implode(', ', $cids);
		// retrieve related tags
		$rel_tags = $this->getTagCloud($filters, $limit, $start, $published);
		return $rel_tags;
	}
	
	/**
	* Re-sort results from DB in Alphabetical Order
	*/
	function _sortAlpha($row1, $row2) {
		return strcmp(strtolower($row1->tag), strtolower($row2->tag));
	}
	
	/**
	* Escapes values in array for direct SQL storage
	*/
	function _escapeArray(&$arr, $type = 'int') {
		if (is_array($arr)) {
			foreach($arr as $i=>$value) {
				if ($type == 'bool') {
					$value = $value ? 'TRUE' : 'FALSE';
				} else if ($type == 'str') {
					$value = "'".$this->db->safeEscape($value)."'";
				} else {
					$value = (int) $value;
				}
				$arr[$i] = $value;
			}
			return $arr;
		}
		return false;		
	}
	
	/**
	* Retrieve Content Items tagged with the given tag
	* @param string $tag mixed Tags
	* @param int $start Start Id in DB ResultSet
	* @param int $limit Number of Items to retrieve from ResultSet
	* @param string $order Order of Results
	* @param string $orderby Field Name to Order Results by
	*/
	function getTagged($tags, $start = 0, $limit = 10, $order = false, $orderby = false, $content = true) {
		global $mainframe, $my;
		$start = intval($start);
		$limit = intval($limit);
		$rows = NULL;
		if (!$tags) {
			trigger_error('Tags not specified', E_USER_ERROR);
			return false;
		}

		$now 		= _CURRENT_SERVER_TIME;
		$nullDate 	= $this->db->db->getNullDate();
		$noauth 	= !$mainframe->getCfg( 'shownoauth' );
		$gid 		= $my->gid;
		
		if (!is_array($tags)) {
			$tags = array($tags);
		}
		// tags
		foreach($tags as $tag) {
			$tag = $this->db->safeEscape($tag);
			$where[] = "t.tag = '$tag'";
		}
		// published articles
		$where[] = "( a.publish_up = '$nullDate' OR a.publish_up <= '$now' )";
		$where[] = "( a.publish_down = '$nullDate' OR a.publish_down >= '$now' )";
		// published sections
		$where[] = "s.published = 1";
		$where[] = "cc.published = 1";
		// auth
		$where[] = "a.access <= $gid";
		$where[] = "s.access <= $gid";
		$where[] = "cc.access <= $gid";
		// where query
		$where = "\n WHERE ".implode("\n AND ", $where);

		// query to determine total number of records
		$query = "SELECT "
		. ($content ? "a.*, count(t.id) as weight" : "a.id")
		. "\n FROM #__content AS a"
		//. "\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n INNER JOIN #__sections AS s ON s.id = a.sectionid"
		. "\n LEFT JOIN #__users AS u ON u.id = a.created_by"
		. "\n LEFT JOIN #__groups AS g ON a.access = g.id"
		. "\n LEFT JOIN #__tag AS t ON (t.cid = a.id)"
		. $where
		. "\n GROUP BY a.id"
		. "\n ORDER BY ".($content ? 'weight DESC,' : '')." a.id DESC"

		. "\n LIMIT $start, $limit"
		;	//echo $query;
		if ($content) 
			$rows  = $this->db->ObjectList( $query );
		else
			$rows  = $this->db->indexedArray( $query );

		return $rows;
	}
	
	/**
	* Retrieve Content Items tagged with the same tags as the content item given
	* @param int $cid Content ID
	* @param int $start Start Id in DB ResultSet
	* @param int $limit Number of Items to retrieve from ResultSet
	* @param string $order Order of Results
	* @param string $orderby Field Name to Order Results by
	*/
	function getRelatedContent($cid, $start = 0, $limit = 10, $order = false, $orderby = false) {
		return $this->_getRelatedContent($cid, $start, $limit, $order, $orderby, true);
	}
	
	/**
	* Retrieve Titles and IDs for Content Items tagged with the same tags as the content item given
	* @param int $cid Content ID
	* @param int $start Start Id in DB ResultSet
	* @param int $limit Number of Items to retrieve from ResultSet
	* @param string $order Order of Results
	* @param string $orderby Field Name to Order Results by
	*/
	function getRelatedContentTitles($cid, $start = 0, $limit = 10, $order = false, $orderby = false) {
		return $this->_getRelatedContent($cid, $start, $limit, $order, $orderby, false);
	}
	
	// internal get related content
	function _getRelatedContent($cid, $start, $limit, $order, $orderby, $content = true) {
		global $mainframe, $my;
		$cid = intval($cid);
		$start = intval($start);
		$limit = intval($limit);
		$rows = NULL;

		$now 		= _CURRENT_SERVER_TIME;
		$nullDate 	= $this->db->db->getNullDate();
		$noauth 	= !$mainframe->getCfg( 'shownoauth' );
		$gid 		= $my->gid;
		
		/** mysql4+
		* SELECT *, count(id) as weight FROM jos_tag 
		* WHERE tag in (select tag from jos_tag where cid = $cid) group by cid
		*/
		
		// tags in cid
		$where[] = "t.tag IN (select t.tag from #__tag AS t where t.cid = $cid)";
		$where[] = "a.id != $cid"; // exclude self
		// published articles
		$where[] = "( a.publish_up = '$nullDate' OR a.publish_up <= '$now' )";
		$where[] = "( a.publish_down = '$nullDate' OR a.publish_down >= '$now' )";
		// published sections
		$where[] = "s.published = 1";
		$where[] = "cc.published = 1";
		// auth
		$where[] = "a.access <= $gid";
		$where[] = "s.access <= $gid";
		$where[] = "cc.access <= $gid";
		// where query
		$where = "\n WHERE ".implode("\n AND ", $where);
		
		// select 
		if ($content) {
			$select = "SELECT a.id, a.title, count(a.id) as weight";
		} else {
			$select = "SELECT a.*, count(a.id) as weight";
		}

		// query
		$query = $select
		. "\n FROM #__content AS a"
		//. "\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n INNER JOIN #__sections AS s ON s.id = a.sectionid"
		. "\n LEFT JOIN #__users AS u ON u.id = a.created_by"
		. "\n LEFT JOIN #__groups AS g ON a.access = g.id"
		. "\n LEFT JOIN #__tag AS t ON (t.cid = a.id)"
		. $where
		. "\n GROUP BY a.id"
		. "\n ORDER BY weight DESC"
		. "\n LIMIT $start, $limit"
		;	//echo $query;
		$rows  = $this->db->ObjectList( $query );

		return $rows;
	}
	
	/**
	* Retrieve Total Content Items tagged with the given tag
	* @param string $tag Tag
	*/
	function getTotalTagged($tags) {
		global $mainframe;
		
		if (!is_array($tags)) {
			$tags = array($tags);
		}
		foreach($tags as $tag) {
			$tag = $this->db->safeEscape($tag);
			$where[] = "t.tag = '$tag'";
		}
		$where = "\n WHERE ".implode(' AND ', $where);

		$rows = NULL;

		$now 		= _CURRENT_SERVER_TIME;
		$nullDate 	= $this->db->db->getNullDate();
		$noauth 	= !$mainframe->getCfg( 'shownoauth' );

		// query to determine total number of records
		$query = "SELECT count(DISTINCT a.id)"
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__tag AS t ON (t.cid = a.id)"
		. $where
		;	//echo $query;
		$total = $this->db->Result( $query );
		return $total;
	}
	
	/**
	* Inset Tags into Database for Content Item
	* @param Int $cid Content Item ID
	* @param Array $tags Tags to be inserted into database
	*/
	function addTags($cid, $tags, $user_id, $published = 1) {
		if (!($cid && $tags && $user_id)) {
			if (!$cid)
				trigger_error('Content Id not specified', E_USER_ERROR);
			if (!$tags)
				trigger_error('Tags not specified', E_USER_ERROR);
			if (!$user_id)
				trigger_error('UserID not specified', E_USER_ERROR);
			return false;
		}
		if (is_array($tags)) {
			foreach($tags as $tag) {
				if (!$result[$tag] = $this->addTag($cid, $tag, $user_id, $published)) {
					return false;
				}
			}
			return $result;
		}
		return false;
	}
	
	/**
	* Inset a Tag into the DB
	* @param Int $cid Content Item ID
	* @param string $tags Tag to be inserted
	*/
	function addTag($cid, $tag, $user_id, $published = 1) {
		$this->db->Table($this->tags_tbl);
		$this->db->Field('tag', $this->db->safeEscape($tag));
		$this->db->Field('cid', intval($cid));
		$this->db->Field('user_id', intval($user_id));
		$this->db->Field('published', intval($published));
		if ($this->db->Insert()) {
			return $this->db->insertId();
		} else {
			return false;
		}
	}
}


?>