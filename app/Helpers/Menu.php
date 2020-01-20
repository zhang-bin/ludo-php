<?php

namespace App\Helpers;

use App\Models\RoleModel;
use Ludo\Support\Facades\Context;

class Menu
{
    public static $menu = null;

    /**
     * 生成菜单
     *
     * @return string
     */
    public static function render()
    {
        $currentController = lcfirst(Context::get('current-controller'));
        $currentAction = Context::get('current-action');
        empty($currentAction) && $currentAction = 'index';

        $menu = Load::conf('Menu');
        $tree = <<<EOF
<li class="treeview %s">
    <a href="#">
        <i class="fa %s"></i>
        <span>%s</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        %s
    </ul>
</li>
EOF;
        $html = '<aside class="main-sidebar"><section class="sidebar" style="height: auto;"><ul class="sidebar-menu tree" data-widget="tree">';
        foreach ($menu as $mainMenuId => $mainMenu) {
            $leaf = '';
            $treeClass = '';
            foreach ($mainMenu['children'] as $subMenu) {
                if (!RoleModel::canMenu($subMenu['href'])) {
                    continue;
                }

                $active = '';
                if (in_array($currentController . '/' . $currentAction, $subMenu['active'])) {
                    $active = 'active';
                    $treeClass = 'active menu-open';
                }
                $icon = empty($subMenu['icon']) ? 'fa-circle-o' : $subMenu['icon'];
                $leaf .= sprintf('<li class="%s"><a href="%s"><i class="fa %s"></i>%s</a></li>', $active, url($subMenu['href']), $icon, $subMenu['name']);
            }

            if (!empty($leaf)) {
                $html .= sprintf($tree, $treeClass, $mainMenu['icon'], $mainMenu['name'], $leaf);
            }
        }
        $html .= '</ul></section></aside>';

        return $html;
    }

    /**
     * 生成导航条
     *
     * @return string
     */
    public static function breadcrumb()
    {
        $html = '<ol class="breadcrumb">';

        $menu = Load::conf('Menu');

        $currentController = lcfirst(Context::get('current-controller'));
        $currentAction = Context::get('current-action');
        empty($currentAction) && $currentAction = 'index';

        foreach ($menu as $mainMenuId => $mainMenu) {
            foreach ($mainMenu['children'] as $subMenu) {
                if (in_array($currentController . '/' . $currentAction, $subMenu['active'])) {
                    $html .= sprintf('<li><a href="%s"><i class="fa %s"></i> %s</a></li>', url($mainMenuId . '/index'), $mainMenu['icon'], $mainMenu['name']);
                    $html .= sprintf('<li class="active">%s</li>', $subMenu['name']);
                    break 2;
                }
            }
        }

        $html .= '</ol>';
        return $html;
    }
}
