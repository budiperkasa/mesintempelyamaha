<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * YtStyleSwitcher
 *
 * @version 1.0.1 (17.02.2007)
 * @author yootheme.com
 * @copyright Copyright (C) 2007 YOOtheme Ltd & Co. KG. All rights reserved.
 */
class YtStyleSwitcher {
	
	var $settings;

	function YtStyleSwitcher($settings = array()) {
		$this->settings = new YtSettings($settings);
	}

	function getStyleFont() {
		if(isset($_COOKIE["ytstylefont"])) {
			return $_COOKIE["ytstylefont"];
		}
		return $this->settings->get('fontDefault');
	}

	function getStyleWidth() {
		if(isset($_COOKIE["ytstylewidth"])) {
			return $_COOKIE["ytstylewidth"];
		}
		return $this->settings->get('widthDefault');
	}

	function getCurrentStyle() {
		return $this->getStyleFont() . " " . $this->getStyleWidth();
	}

}

?>