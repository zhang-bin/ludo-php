<?php
class UserModel {
    public static function can($action, $controller = true) {
        if ($_SESSION[USER]['isAdmin']) return true;
        $controller = (true === $controller) ? CURRENT_CONTROLLER : $controller;
        $controller = lcfirst($controller);
        if (isset($_SESSION[USER]['permissions'][$controller])) return true;//allow all action in current controller

        $operation = $controller.'/'.$action;
        return $_SESSION[USER]['permissions'][$operation];
    }

    public static function canMenu($resource, $operation = null) {
        if ($_SESSION[USER]['isAdmin']) return true;

        if (is_null($operation)) {//一级菜单
            return $_SESSION[USER]['menus']['top'][$resource];
        } else {
            return $_SESSION[USER]['menus']['sub'][$resource][$operation];
        }
    }
}