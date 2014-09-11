<?php
class Menu {
	static $menu = null;
	static $submenuUrlCache = null;
	static $currMenuId = null;
	
	static function init() {
		if (self::$menu == null) {
			$menuConfFile = 'AdminMenu';
			self::$menu = Load::conf($menuConfFile);
			self::$submenuUrlCache = self::submenuUrlCache(self::$menu);
			$curr = lcfirst(CURRENT_CONTROLLER).'/'.CURRENT_ACTION;

			$curr = str_replace('/index', '', $curr);
			
			self::$currMenuId = self::$submenuUrlCache[$curr];
		}
	}	
	
	static function menuRender() {
		self::init();
		
		$html = '<ul class="nav">';
		foreach(self::$menu as $k=>$v) {//1级菜单
			$active = self::$currMenuId['level0'] == $k ? 'active' : '';
			$li = '<li class="dropdown '.$active.'"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">'.$v['name'].'</a>';
			if(!empty($v['submenu'])) {
				$li .= '<ul class="dropdown-menu">';
				foreach ($v['submenu'] as $kk=>$vv) {//2级菜单
					if (!empty($vv['submenu'])) {
						$li .= '<li class="offset-right dropdown"><a href="javascript:;">'.$vv['name'].'</a><ul class="dropdown-menu">';
						foreach ($vv['submenu'] as $kkk => $vvv) {//3级菜单
							$li .= '<li><a href="'.url($kkk).'">'.$vvv['name'].'</a></li>';
						}
						$li .= '</ul></li>';
					} else {
						$li .= '<li><a href="'.url($kk).'">'.$vv['name'].'</a></li>';
					}
				}
			}
			$li .= '</ul></li>';
			$html .= $li;
		}
		$html .= '</ul>';

		return $html;
	}
	
	static function naviRender($title, $toolBox) {
		self::init();
		$html = '<ul class="breadcrumb">';
		if (isset(self::$currMenuId['level0'])) {
			$menu = self::$currMenuId['level0'];
			$html .= '<li><a href="'.url($menu).'">'.self::$menu[$menu]['name'].'</a><span class="divider">/</span></li>';
		}
		if (isset(self::$currMenuId['level1'])) {
			$subMenu = self::$currMenuId['level1'];
			$html .= '<li><a href="'.url($subMenu).'">'.self::$menu[$menu]['submenu'][$subMenu]['name'].'</a><span class="divider">/</span></li>';
		}
		if (isset(self::$currMenuId['level2'])) {
			$subSubMenu = self::$currMenuId['level2'];
			$html .= '<li><a href="'.url($subSubMenu).'">'.self::$menu[$menu]['submenu'][$subMenu]['submenu'][$subSubMenu]['name'].'</a><span class="divider">/</span></li>';
		}
		$html .= '<li class="active">'.$title.'</li>';
		$html .= '<span style="float:right;">'.$toolBox.'</span>';
		$html .= '</ul>';
		return $html;
	}
	
	static function submenuUrlCache($menuConf) {
		$cache = array();
		foreach ($menuConf as $menuId => $menu) {
			$cache[$menuId] = $menu['name'];
			foreach ($menu['submenu'] as $subMenuId => $subMenus) {
				$cache[$subMenuId] = array('level0' => $menuId);
				if (!empty($subMenus['include'])) {
					foreach ($subMenus['include'] as $url) {
						$cache[$url] = array('level1' => $subMenuId, 'level0' => $menuId);
					}
				}
				if (!empty($subMenus['submenu'])) {
					foreach ($subMenus['submenu'] as $subSubMenuId => $subSubMenus) {
						$cache[$subSubMenuId] = array('level1' => $subMenuId, 'level0' => $menuId);
						if (!empty($subSubMenus['include'])) {
							foreach ($subSubMenus['include'] as $url) {
								$cache[$url] = array('level2' => $subSubMenuId, 'level1' => $subMenuId, 'level0' => $menuId);
							}
						}
					}
				}
			}
		}
		return $cache;
	}
}