<?php
class UserModel {
    public static function canOperation($resource, $operation) {
        if ($_SESSION[USER]['isAdmin']) return true;

        return $_SESSION[USER]['permissions']['operation'][$resource][$operation];
    }

    public static function canMenu($url) {
        if ($_SESSION[USER]['isAdmin']) return true;

        return in_array($url, $_SESSION[USER]['permissions']['url']);
    }
}