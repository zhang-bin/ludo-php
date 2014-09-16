<?php
class Menu {
	static $menu = null;
	static $subMenuUrlCache = null;
	static $currMenuId = null;
	
	static function init() {
        if (empty(self::$menu)) {
            self::$menu = Load::conf('Menu');
            self::$subMenuUrlCache = self::subMenuUrlCache(self::$menu);
            $curr = lcfirst(CURRENT_CONTROLLER).'/'.CURRENT_ACTION;
            $curr = str_replace('/index', '', $curr);

            $tmp = $curr;
            $curLevel = self::$subMenuUrlCache[$curr]['level'];
            if ($curLevel == 1) self::$currMenuId['level1'] = $tmp;

            for ($i = $curLevel; $i > 1; $i--) {
                $tmp = self::$subMenuUrlCache[$tmp]['parent'];
                self::$currMenuId['level'.($i-1)] = $tmp;
            }
        }
	}
	
	static function menuRender() {
		self::init();
		$html = '<ul class="nav">';
		foreach (self::$menu as $top=>$topMenus) {//1级菜单
			$active = self::$currMenuId['level1'] == $top ? 'active' : '';
			$li = '<li class="dropdown '.$active.'">';
            if (empty($topMenus['children'])) {
                $li .= '<a href="'.url($top).'">'.$topMenus['name'].'</a>';
            } else {
                $li .= '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">'.$topMenus['name'].'</a><ul class="dropdown-menu">';
				foreach ($topMenus['children'] as $second=>$secondMenus) {//2级菜单
					if (!empty($secondMenus['children'])) {
						$li .= '<li class="offset-right dropdown"><a href="javascript:;">'.$secondMenus['name'].'</a><ul class="dropdown-menu">';
						foreach ($secondMenus['children'] as $third => $thirdMenus) {//3级菜单
							$li .= '<li><a href="'.url($third).'">'.$thirdMenus['name'].'</a></li>';
						}
						$li .= '</li>';
					} else {
						$li .= '<li><a href="'.url($second).'">'.$secondMenus['name'].'</a></li>';
					}
				}
                $li .= '</ul>';
			}
			$li .= '</li>';
			$html .= $li;
		}
		$html .= '</ul>';

		return $html;
	}
	
	static function navRender($title, $toolBox) {
		self::init();
		$html = '<ul class="breadcrumb">';
		if (isset(self::$currMenuId['level1'])) {
			$menu = self::$currMenuId['level1'];
            $curMenu = self::$menu[$menu];
			$html .= '<li><a href="'.url($menu).'">'.$curMenu['name'].'</a></li>';
		}
		if (isset(self::$currMenuId['level2'])) {
			$subMenu = self::$currMenuId['level2'];
            $curMenu = $curMenu['children'][$subMenu];
			$html .= '<li><span class="divider">/</span><a href="'.url($subMenu).'">'.$curMenu['name'].'</a></li>';
		}
		if (isset(self::$currMenuId['level3'])) {
			$subSubMenu = self::$currMenuId['level3'];
            $curMenu = $curMenu['children'][$subSubMenu];
			$html .= '<li><span class="divider">/</span><a href="'.url($subSubMenu).'">'.$curMenu['name'].'</a></li>';
		}
        if (!empty($title)) $html .= '<li class="active"><span class="divider">/</span>'.$title.'</li>';
		$html .= '<span style="float:right;">'.$toolBox.'</span>';
		$html .= '</ul>';
		return $html;
	}
	
	static function subMenuUrlCache($menuConf) {
		$cache = array();
        foreach ($menuConf as $menuId => $menu) {
            $cache[$menuId]['level'] = 1;
            if (strstr($menuId, '/index') === false) {
                $cache[$menuId.'/index']['level'] = 1;
            }
            if (!empty($menu['children'])) {
                self::_cache($menuId, $menu['children'], $cache, 2);
            }
        }
        return $cache;
    }

    private static function _cache($parent, $menus, &$cache, $level) {
        foreach ($menus as $menuId => $menu) {
            $cache[$menuId]['parent'] = $parent;
            $cache[$menuId]['level'] = $level;
            if (!empty($menu['include'])) {
                foreach ($menu['include'] as $url) {
                    $cache[$url]['parent'] = $menuId;
                    $cache[$url]['level'] = $level+1;
                }
            }
            if (!empty($menu['children'])) self::_cache($menuId, $menu['children'], $cache, $level+1);
        }
    }
}