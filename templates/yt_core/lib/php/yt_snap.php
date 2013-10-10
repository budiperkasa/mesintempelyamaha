<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * YtSnap
 *
 * @version 1.0.0 (17.02.2007)
 * @author yootheme.com
 * @copyright Copyright (C) 2007 YOOtheme Ltd & Co. KG. All rights reserved.
 */
class YtSnap {

	var $ap;
	var $snapKey;
	var $sb;
	var $th;
	var $si;
	var $link_icon;
	var $preview_trigger;
	var $domain;

	function YtSnap() {
		// Help: http://www.snap.com/about/spa_faq.php
		$this->ap = "0"; 										/* 0 | 1 (0 = no preview by default, add a 'snap_preview' class) */
		$this->snapKey = "";									/* your snap key */
		$this->sb = "0";										/* 0 | 1 (search box) */
		$this->th = "asphalt";									/* theme */
		$this->si = "0";										/* 0 | 1 (snap preview for internal links)*/
		$this->link_icon = "on";								/* on | off */
		$this->preview_trigger = "both";						/* both | icon */
		$this->domain = "";										/* your domain */
	}

	function enableSnap() {
		$javascript = '<script id="snap_preview_anywhere" type="text/javascript" src="http://spa.snap.com/snap_preview_anywhere.js?';
		$javascript .= 'ap=' . $this->ap;
		$javascript .= '&amp;key=' . $this->snapKey;
		$javascript .= '&amp;sb=' . $this->sb;
		$javascript .= '&amp;th=' . $this->th;
		$javascript .= '&amp;cl=' . '1';
		$javascript .= '&amp;si=' . $this->si;
		$javascript .= '&amp;oi=' . '0';
		$javascript .= '&amp;link_icon=' . $this->link_icon;
		$javascript .= '&amp;preview_trigger=' . $this->preview_trigger;
		$javascript .= '&amp;domain=' . $this->domain;
		$javascript .= '"></script>';
		
		echo $javascript;
	}

}

?>