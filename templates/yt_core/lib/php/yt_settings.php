<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * YtSettings
 *
 * Class to store yootheme template settings
 *
 * @version 1.0.1 (29.04.2007)
 * @author yootheme.com
 * @copyright Copyright (C) 2007 YOOtheme Ltd & Co. KG. All rights reserved.
 */
class YtSettings {

	/* yootheme global default template settings */
	var $defaults;  

	/* javascript settings */
	var $javascript;

	/* template settings */
	var $settings;

	function YtSettings($settings = array()) {

		$this->defaults = array(
			/* color */
			"color"               => "white",
			/* item color variation */
			"item1"               => "red",
			"item2"               => "blue",
			"item3"               => "green",
			"item4"               => "yellow",
			"item5"               => "lilac",
			/* layout */
			"dogear"              => true,
			"date"    	          => true,			
			"styleswitcherFont"   => true,
			"styleswitcherWidth"  => true,
			"layout"              => "left",
			/* features */
			"lightbox"            => true,
			"reflection"          => true,
			"snap"                => false,
			/* style switcher */
			"fontDefault"         => "font-medium",
			"widthDefault"        => "width-wide",
			"widthThinPx"         => 780,
			"widthWidePx"         => 900,
			"widthFluidPx"        => 0.9,
			/* top panel */
		    "toppanel"            => true,
			"heightToppanel"      => 320,
			"textToppanel"        => "Top Panel",
			/* javascript */
			"loadJavascript"      => true
			);

		$this->javascript = array(
			/* color */
			"color"               => "'<VAL>'",
			/* layout */
			"layout"              => "'<VAL>'",
			/* features */
			"lightbox"            => "<VAL>",
			/* style switcher */
			"fontDefault"         => "'<VAL>'", 
			"widthDefault"        => "'<VAL>'",
			"widthThinPx"         => "<VAL>",
			"widthWidePx"         => "<VAL>",
			"widthFluidPx"        => "<VAL>",
			/* top panel */
			"heightToppanel"      => "<VAL>",
			);

		$this->settings = $settings + $this->defaults;
	}

	function get($key) {
		return $this->settings[$key];
	}
	
	function getJavaScript() { 
		$js = "var YtSettings = { ";
		$seperator = false;
		foreach($this->javascript as $key => $val) {
			$setting = $this->get($key);
			if(is_bool($setting)) {
				$setting ? $setting = "true" : $setting = "false";
			}
			if(is_float($setting)) {
				$setting = number_format($setting, 2, ".", "");
			}
			$seperator ? $js .= ", " : $seperator = true;			
			$js .= $key . ": " . str_replace("<VAL>", $setting, $val);
		}		
		$js .= " };";
		return $js;
	}

	function showJavaScript() {
		echo $this->getJavaScript();
	}

}

?>