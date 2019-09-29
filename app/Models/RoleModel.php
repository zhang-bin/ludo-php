<?php

namespace App\Models;

use App\Daos\UserRoleDao;
use App\Daos\RolePermissionDao;
use App\Helpers\Load;

class RoleModel
{
    public static function parsePermissions($uid)
    {
        $roleIds = (new UserRoleDao())->hasA('Role')->findAllUnique(array('userId = ? and Role.deleted = 0', $uid), 'roleId');
        if (empty($roleIds)) {
            return [];
        }

        $permissionConf = Load::conf('Permission');
        $permissions = $menus = array();
        $rolePermissions = (new RolePermissionDao())->findAll('roleId in ('.implode(',', $roleIds).')');
        foreach ($rolePermissions as $rolePermission) {
            $permissions[$rolePermission['permissionPolicy']] = true;

            foreach ($permissionConf[$rolePermission['permissionPolicy']]['url'] as $urls) {
                foreach ($urls as $controller => $actions) {
                    if ($actions == '*') {
                        $menus[$controller] = true;
                    } else {
                        $actions = (array) $actions;
                        foreach ($actions as $action) {
                            $menus[$controller.'/'.$action] = true;
                        }
                    }
                }
            }
        }

        return [$permissions, $menus];
    }

    public static function canMenu($url)
    {
        if ($_SESSION[USER]['isAdmin']) return true;

        return isset($_SESSION[USER]['menu'][$url]);
    }

    public static function can($resource, $operation)
    {
        if ($_SESSION[USER]['isAdmin']) return true;

        return isset($_SESSION[USER]['permission'][$resource][$operation]);
    }
}