<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * YOOmenu
 *
 * Class to extend Joomla 1.0.x with a split menu function and more...
 *
 * @version 1.0.5 (24.03.2007)
 * @author yootheme.com
 * @copyright Copyright (C) 2007 YOOtheme Ltd & Co. KG. All rights reserved.
 */
class YtSplitMenu {

	var $menu_root;
	var $menu_items;
	var $menu_type;
	var $menu_class;
	var $show_all_children;
	var $accordion_style_for_level;
	var $slider_style_for_level;
	var $listitem_background_image;

	function YtSplitMenu($menu_type, $menu_class) {
		$this->menu_root = null;
		$this->menu_items = array();
		$this->menu_type = $menu_type;
		$this->menu_class = $menu_class;
		$this->show_all_children = false;
		$this->accordion_style_for_level = false;
		$this->slider_style_for_level = false;
		$this->listitem_background_image = false;
		$this->loadMenu();
	}

	function getShowAllChildren() {
		return $this->show_all_children;
	}

	function setShowAllChildren($val) {
		$this->show_all_children = $val;
	}

	function getAccordionStyleForLevel() {
		return $this->accordion_style_for_level;
	}

	function setAccordionStyleForLevel($val) {
		$this->accordion_style_for_level = $val;
	}

	function getSliderStyleForLevel() {
		return $this->slider_style_for_level;
	}

	function setSliderStyleForLevel($val) {
		$this->slider_style_for_level = $val;
	}

	function getListitemBackgroundImage() {
		return $this->listitem_background_image;
	}

	function setListitemBackgroundImage($val) {
		$this->listitem_background_image = $val;
	}

	function loadMenu() {
		global $database, $my, $cur_template, $Itemid;
		global $mosConfig_absolute_path, $mosConfig_shownoauth;

		$and = '';
		if (!$mosConfig_shownoauth) {
			$and = "\n AND access <= $my->gid";
		}

		$sql = "SELECT m.* FROM #__menu AS m"
		. "\n WHERE menutype = '" . $this->menu_type . "'"
		. "\n AND published = 1"
		. $and
		. "\n ORDER BY parent, ordering";

		$database->setQuery($sql);
		$rows = $database->loadObjectList('id');

		$this->menu_root = &new YtSplitMenuItem();
		$this->menu_root->setName("ROOT");
		$this->menu_root->setActiveWithParentItems(true);		
		$this->addMenuItem($this->menu_root);

		$this->loadMenuLevelItems($this->menu_root, $rows, 1);
	}

	function loadMenuLevelItems(&$parent, $menu_items, $sublevel) {
		global $Itemid;
		
		foreach($menu_items as $menu_item) {
			if($menu_item->parent == $parent->getId()) {
				// create new menu item
				$new_item = &new YtSplitMenuItem($menu_item);
				$new_item->setParent($parent);
				$new_item->setSublevel($sublevel);
				$parent->addChild($new_item);
				// set active menu items with its parent items
				if(isset($Itemid) && $new_item->getId() == $Itemid) {
					$new_item->setCurrent(true);
					$new_item->setActiveWithParentItems(true);
				}
				// add new item to menu item list
				$this->addMenuItem($new_item);
				// populate next menu level items
				$this->loadMenuLevelItems($new_item, $menu_items, ($sublevel+1));
			}
		}
	}

	function addMenuItem(&$menu_item) {
		$this->menu_items[$menu_item->id] = &$menu_item;
	}

	function getMenuLevel(&$parent, $levels) {
		$str = "";
		if($levels != 0 && $parent->hasChildren()) {

			// add accordion style class
			($this->getAccordionStyleForLevel() !== false && $this->getAccordionStyleForLevel() == $parent->getSublevel() && $parent->isSeparator()) ? $ul_class = " accordion" : $ul_class = "";
			
			$str .= "<ul class=\"" . $this->menu_class . $ul_class . "\">\n";				
			foreach($parent->getChildren() as $child) {

				// add background image style
				($this->listitem_background_image !== false && $child->hasMenuImage()) ? $li_style = " style=\"background-image: url(images/stories/" . $child->getMenuImage() . ");\"" : $li_style = "";
			
				// add accordion toggler
				($this->getAccordionStyleForLevel() !== false && $this->getAccordionStyleForLevel() == $child->getSublevel() && $child->hasChildren() && $child->isSeparator()) ? $li_class = " toggler" : $li_class = "";
				
				// add slider style class
				($this->getSliderStyleForLevel() !== false && $this->getSliderStyleForLevel() == $child->getSublevel()) ? $link_class = "slider" : $link_class = "";

				$str .= "<li" . $child->getHtmlId() . " class=\"" . $child->getHtmlClass() . $li_class . "\"" . $li_style . ">";				
				$str .= $child->getHtmlLink($link_class);
				if($this->show_all_children || (!$this->show_all_children && $child->active) ||
				  // show sub items if current child is accordion toggler
				  ($this->getAccordionStyleForLevel() !== false && $this->getAccordionStyleForLevel() == $child->getSublevel() && $child->isSeparator())) {
					$str .= $this->getMenuLevel($child, ($levels-1));
				}
				
				$str .= "</li>\n";
			}

			$str .= "</ul>\n";
		}
		return $str;
	}

	function getMenu($start_level = 1, $levels = -1) {
		if($start_level > 0) {
			foreach($this->menu_items as $menu_item) {
				if($menu_item->isActive() && $menu_item->getSublevel() == $start_level-1) {
					return $this->getMenuLevel($menu_item, $levels);
				}
			}
		}
		return "";
	}

	function showMenu($start_level = 1, $levels = -1) {
		echo $this->getMenu($start_level, $levels);
	}

	function getActiveMenuItemNumber($level) {
		if($level > 0) {
			foreach($this->menu_items as $menu_item) {
				if($menu_item->isActive() && $menu_item->getSublevel() == $level) {
					return $menu_item->getOrdering();
				}
			}
		}
		return -1;
	}

}

/**
 * YtSplitMenuItem
 *
 * Class MenuItem to extend Joomla 1.0.x with a split menu function
 *
 * @version 1.0.4 (17.02.2007)
 * @author yootheme.com
 * @copyright Copyright (C) 2007 YOOtheme Ltd & Co. KG. All rights reserved.
 */
class YtSplitMenuItem {

	var $id;
	var $name;
	var $link;
	var $type;
	var $browser_nav;
	var $sublevel;
	var $ordering;
	var $params;	
	var $active;
	var $current;
	var $parent;
	var $children;

	function YtSplitMenuItem($row = null) {
		$this->id = 0;
		$this->name = "";
		$this->link = "";
		$this->type = "";
		$this->browser_nav = 0;
		$this->sublevel = 0;
		$this->ordering = 0;
		$this->params = null;
		$this->active = false;
		$this->current = false;
		$this->parent = null;
		$this->children = array();

		if($row != null) {
			$this->id = $row->id;
			$this->name = $row->name;
			$this->link = $row->link;
			$this->type = $row->type;
			$this->browser_nav = $row->browserNav;
			$this->ordering = $row->ordering;
			$this->params = new mosParameters($row->params);
		}
	}

	function getId() {
		return $this->id;
	}

	function getName() {
		return $this->name;
	}

	function getSublevel() {
		return $this->sublevel;
	}

	function isActive() {
		return $this->active;
	}

	function isCurrent() {
		return $this->current;
	}

	function isSeparator() {
		return $this->browser_nav == 3;
	}

	function &getParent() {
		return $this->parent;
	}

	function &getChildren() {
		return $this->children;
	}

	function hasChildren() {
		return count($this->children) > 0;
	}

	function getMenuImage() {
		return $this->params->get('menu_image', false);
	}

	function hasMenuImage() {
		return $this->getMenuImage() != false && $this->getMenuImage() != -1;
	}

	function getOrdering() {
		return $this->ordering;
	}

	function setId($val) {
		$this->id = $val;
	}

	function setName($val) {
		$this->name = $val;
	}

	function setSublevel($val) {
		$this->sublevel = $val;
	}

	function setActiveWithParentItems($val) {
		$this->active = $val;
		if($this->parent != null) {
			$this->parent->setActiveWithParentItems($val);
		}
	}

	function setCurrent($val) {
		$this->current = $val;
	}

	function setParent(&$val) {
		$this->parent = &$val;
	}

	function addChild(&$menu_item) {
		$this->children[] = &$menu_item;
	}

	function getUrl() {
		$link = $this->link;

		switch ($this->type) {
			case 'separator':
				break;

			case 'url':
				if (eregi('index.php\?', $link)) {
					if (!eregi('Itemid=', $link)) {
						$link .= '&Itemid='. $this->id;
					}
				}
				break;

			default:
				$link .= '&Itemid='. $this->id;
				break;
		}

		return sefRelToAbs(ampReplace($link));
	}

	function getHtmlId() {
		if($this->current)
			return ' id="current"';

		return "";
	}

	function getHtmlClass() {
		$html_class = "level" . $this->sublevel . " item" . $this->ordering;

		if($this->hasChildren())
			$html_class .= " parent";

		if($this->active)
			$html_class .= " active";

		return $html_class;
	}

	function getHtmlLink($html_class = '') {
		$url = $this->getUrl();
		$name = stripslashes(ampReplace($this->name));
		
		if($html_class != '') {
			$html_class = 'class="' . $html_class . '" ';
		}

		switch ($this->browser_nav) {
			case 1:
				$html_link = '<a ' . $html_class . 'href="'. $url .'" target="_blank">'. $name .'</a>';
				break;

			case 2:
				$html_link = "<a ' . $html_class . 'href=\"#\" onclick=\"javascript: window.open('". $url ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\">". $name ."</a>";
				break;

			case 3:
				$html_link = '<span ' . $html_class . '>'. $name .'</span>';
				break;

			default:
				$html_link = '<a ' . $html_class . 'href="'. $url .'">'. $name .'</a>';
				break;
		}

		return $html_link;
	}

}

?>