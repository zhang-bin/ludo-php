<?php

namespace App\Models;

use App\Daos\UserRoleDao;
use App\Daos\RolePermissionDao;
use App\Helpers\Load;

class RoleModel
{
    public static function parsePermissions($uid)
    {
        $roleIds = (new UserRoleDao())->hasA('Role')->findAllUnique(['userId = ? and Role.deleted = 0', [$uid]], 'roleId');
        if (empty($roleIds)) {
            return [];
        }

        $permissionConf = Load::conf('Permission');
        $permissions = $menus = [];
        $permissionPolicies = (new RolePermissionDao())->findAllUnique('roleId in (' . implode(',', $roleIds) . ')', 'permissionPolicy');
        foreach ($permissionPolicies as $permissionPolicy) {
            $permissions[$permissionPolicy] = true;

            foreach ($permissionConf[$permissionPolicy]['url'] as $controller => $actions) {
                foreach ($actions as $action) {
                    $menus[$controller . '/' . $action] = true;
                }
            }

            if (!empty($permissionConf[$permissionPolicy]['include'])) {
                foreach ($permissionConf[$permissionPolicy]['include'] as $includePermissionPolicy) {
                    $permissions[$includePermissionPolicy] = true;
                    foreach ($permissionConf[$includePermissionPolicy]['url'] as $controller => $actions) {
                        foreach ($actions as $action) {
                            $menus[$controller . '/' . $action] = true;
                        }
                    }
                }
            }
        }

        return [$permissions, $menus];
    }

    public static function canMenu($url)
    {
        if ($_SESSION[USER]['isAdmin']) {
            return true;
        }

        return isset($_SESSION[USER]['menu'][$url]);
    }

    public static function can($permissionPolicy)
    {
        if ($_SESSION[USER]['isAdmin']) {
            return true;
        }

        return boolval($_SESSION[USER]['permission'][$permissionPolicy]);
    }
}